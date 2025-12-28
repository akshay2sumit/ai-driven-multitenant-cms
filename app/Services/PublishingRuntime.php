<?php

namespace App\Services;

use App\Contracts\ReadOnlyPublishingRepositoryInterface;
use App\Tenant\Context as TenantContext;
use CodeIgniter\I18n\Time;

class PublishingRuntime
{
    /**
     * @var ReadOnlyPublishingRepositoryInterface
     */
    protected $repository;

    /**
     * @param ReadOnlyPublishingRepositoryInterface $repository Read-only repository for published content
     */
    public function __construct(ReadOnlyPublishingRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
    /**
     * Evaluates if content is publishable based on its metadata
     * 
     * @param array $contentMetadata {
     *     @var string $tenant_id       Required. The tenant identifier
     *     @var string $current_state   Required. Current state of the content (draft|scheduled|published|archived)
     *     @var string $publish_at      Optional. ISO 8601 datetime string for scheduled publishing
     *     @var string $expire_at       Optional. ISO 8601 datetime string for content expiration
     * }
     * @return array{
     *     is_publishable: bool,
     *     reason: string,
     *     resolved_state: string
     * }
     */
    public function evaluatePublishability(array $contentMetadata): array
    {
        try {
            // Validate required fields
            if (empty($contentMetadata['tenant_id'])) {
                throw new \InvalidArgumentException('Tenant ID is required');
            }
            
            $currentState = strtolower($contentMetadata['current_state'] ?? 'draft');
            
            // State machine for publishability
            switch ($currentState) {
                case 'draft':
                    return [
                        'is_publishable' => false,
                        'reason' => 'Content is in draft state',
                        'resolved_state' => 'draft'
                    ];
                    
                case 'scheduled':
                    $publishAt = $contentMetadata['publish_at'] ?? null;
                    if (!$publishAt) {
                        return [
                            'is_publishable' => false,
                            'reason' => 'Scheduled content requires publish_at datetime',
                            'resolved_state' => 'invalid_schedule'
                        ];
                    }
                    
                    $publishTime = strtotime($publishAt);
                    if ($publishTime === false) {
                        return [
                            'is_publishable' => false,
                            'reason' => 'Invalid publish_at datetime format',
                            'resolved_state' => 'invalid_datetime'
                        ];
                    }
                    
                    if ($publishTime > time()) {
                        return [
                            'is_publishable' => false,
                            'reason' => 'Content is scheduled for future publishing',
                            'resolved_state' => 'scheduled_future'
                        ];
                    }
                    break;
                    
                case 'published':
                    // Continue to expiration check
                    break;
                    
                case 'archived':
                    return [
                        'is_publishable' => false,
                        'reason' => 'Content is archived',
                        'resolved_state' => 'archived'
                    ];
                    
                default:
                    return [
                        'is_publishable' => false,
                        'reason' => 'Unknown content state: ' . $currentState,
                        'resolved_state' => 'unknown_state'
                    ];
            }

            // Check expiration if applicable
            if (!empty($contentMetadata['expire_at'])) {
                $expireTime = strtotime($contentMetadata['expire_at']);
                if ($expireTime === false) {
                    return [
                        'is_publishable' => false,
                        'reason' => 'Invalid expire_at datetime format',
                        'resolved_state' => 'invalid_datetime'
                    ];
                }
                
                if ($expireTime < time()) {
                    return [
                        'is_publishable' => false,
                        'reason' => 'Content has expired',
                        'resolved_state' => 'expired'
                    ];
                }
            }

            return [
                'is_publishable' => true,
                'reason' => 'Content is publishable',
                'resolved_state' => 'publishable'
            ];

        } catch (\Throwable $e) {
            log_message('error', sprintf(
                'Publishing evaluation failed: %s',
                $e->getMessage()
            ));
            
            return [
                'is_publishable' => false,
                'reason' => 'Publishing evaluation failed: ' . $e->getMessage(),
                'resolved_state' => 'error'
            ];
        }
    }

    /**
     * Get a published entity by type and ID
     * 
     * This method provides read-only access to published content with the following guarantees:
     * 1. Tenant isolation is strictly enforced
     * 2. Only published content is accessible
     * 3. Fail-closed behavior on any error
     * 4. No write operations are possible through this interface
     * 
     * @param string $entityType The type of entity (e.g., 'page', 'post')
     * @param int $entityId The ID of the entity
     * @return array|null The published entity data or null if not found
     * @throws \RuntimeException If tenant context is missing or access is denied
     */
    public function getPublishedEntity(string $entityType, int $entityId): ?array
    {
        try {
            // Tenant context is required for all operations
            $tenantId = TenantContext::getCurrentTenantId();
            if (empty($tenantId)) {
                // Fail-closed: If we can't verify the tenant, we deny access
                throw new \RuntimeException('Tenant context not available');
            }

            // The repository enforces read-only access at the architectural level
            $entity = $this->repository->getPublishedVersion($entityType, $entityId, $tenantId);

            if (!$entity) {
                // Entity not found or not published
                return null;
            }

            // Additional verification that the entity belongs to the current tenant
            // This is a defense-in-depth measure, as the repository should enforce this
            if (($entity['tenant_id'] ?? null) !== $tenantId) {
                // Security boundary violation: This should never happen if the repository is working correctly
                throw new \RuntimeException('Access denied: Entity does not belong to current tenant');
            }

            // Verify the entity is in the correct state
            if (($entity['state'] ?? '') !== 'published') {
                // State changed after retrieval, return not found
                return null;
            }

            return $entity;

        } catch (\Exception $e) {
            // Log the error with context for security auditing
            log_message('error', sprintf(
                'Failed to fetch published entity [%s:%d]: %s',
                $entityType,
                $entityId,
                $e->getMessage()
            ));
            
            // Fail-closed: Never expose internal errors to callers
            throw new \RuntimeException('Failed to fetch published content', 0, $e);
        }
    }

     /**
     * Check if an entity is currently published
     * 
     * @param string $entityType The type of entity (e.g., 'page', 'post')
     * @param int $entityId The ID of the entity
     * @return bool True if the entity is published and accessible
     */
    public function isEntityPublished(string $entityType, int $entityId): bool
    {
        try {
            return $this->getPublishedEntity($entityType, $entityId) !== null;
        } catch (\Exception $e) {
            log_message('error', 'Error checking if entity is published: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get the published state of an entity
     * 
     * @param string $entityType The type of entity
     * @param int $entityId The ID of the entity
     * @return array|null The published state info or null if not published
     */
    public function getPublishedState(string $entityType, int $entityId): ?array
    {
        $entity = $this->getPublishedEntity($entityType, $entityId);
        if (!$entity) {
            return null;
        }
        return [
            'state' => $entity['state'],
            'published_at' => $entity['published_at'] ?? null,
            'published_by' => $entity['published_by'] ?? null,
            'version' => $entity['version'] ?? 1
        ];
    }

}
