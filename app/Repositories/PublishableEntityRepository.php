<?php

namespace App\Repositories;

use App\Contracts\ReadOnlyPublishingRepositoryInterface;
use App\Models\PublishableEntityModel;
use App\Tenant\Context as TenantContext;
use InvalidArgumentException;
use RuntimeException;

class PublishableEntityRepository implements ReadOnlyPublishingRepositoryInterface
{
    protected $model;
    
    public function __construct(PublishableEntityModel $model)
    {
        $this->model = $model;
    }
    
    /**
     * Create a new draft version of an entity
     */
    public function createDraft(
        string $entityType,
        int $entityId,
        array $data,
        ?int $createdBy = null
    ): array {
        $data['created_by'] = $createdBy;
        
        $id = $this->model->createVersion(
            $entityType,
            $entityId,
            $data,
            PublishableEntityModel::STATE_DRAFT,
            $createdBy
        );
        
        if (!$id) {
            throw new RuntimeException('Failed to create draft');
        }
        
        return $this->getById($id);
    }
    
    /**
     * Submit a draft for review
     */
    public function submitForReview(int $entityId, ?int $submittedBy = null): array
    {
        $entity = $this->getById($entityId);
        
        if ($entity['state'] !== PublishableEntityModel::STATE_DRAFT) {
            throw new InvalidArgumentException('Only draft entities can be submitted for review');
        }
        
        $this->model->transitionState(
            $entityId,
            PublishableEntityModel::STATE_REVIEW,
            $submittedBy
        );
        
        return $this->getById($entityId);
    }
    
    /**
     * Publish a reviewed entity
     */
    public function publish(int $entityId, ?int $publishedBy = null): array
    {
        $entity = $this->getById($entityId);
        
        if ($entity['state'] !== PublishableEntityModel::STATE_REVIEW) {
            throw new InvalidArgumentException('Only reviewed entities can be published');
        }
        
        $this->model->transitionState(
            $entityId,
            PublishableEntityModel::STATE_PUBLISHED,
            $publishedBy
        );
        
        return $this->getById($entityId);
    }
    
    /**
     * Archive a published entity
     */
    public function archive(int $entityId, ?int $archivedBy = null): array
    {
        $entity = $this->getById($entityId);
        
        if ($entity['state'] !== PublishableEntityModel::STATE_PUBLISHED) {
            throw new InvalidArgumentException('Only published entities can be archived');
        }
        
        $this->model->transitionState(
            $entityId,
            PublishableEntityModel::STATE_ARCHIVED,
            $archivedBy
        );
        
        return $this->getById($entityId);
    }
    
    /**
     * Retract a published entity
     */
    public function retract(int $entityId, ?int $retractedBy = null): array
    {
        $entity = $this->getById($entityId);
        
        if ($entity['state'] !== PublishableEntityModel::STATE_PUBLISHED) {
            throw new InvalidArgumentException('Only published entities can be retracted');
        }
        
        $this->model->transitionState(
            $entityId,
            PublishableEntityModel::STATE_RETRACTED,
            $retractedBy
        );
        
        return $this->getById($entityId);
    }
    
    /**
     * Get entity by ID with tenant isolation
     */
    public function getById(int $id): array
    {
        $entity = $this->model->find($id);
        
        if (!$entity) {
            throw new InvalidArgumentException('Entity not found');
        }
        
        // Verify tenant access
        if ($entity['tenant_id'] !== TenantContext::get()) {
            throw new InvalidArgumentException('Entity not found');
        }
        
        return $entity;
    }
    
    /**
     * Get the latest published version of an entity (read-only)
     * 
     * This method is safe for runtime use as it only performs read operations
     * and enforces tenant isolation.
     * 
     * Security considerations:
     * - Tenant isolation is enforced by requiring $tenantId parameter
     * - Only returns published entities (state = 'published')
     * - Uses query builder's get() which is read-only
     * 
     * @param string $entityType The type of entity to fetch
     * @param int $entityId The ID of the entity to fetch
     * @param string $tenantId The ID of the tenant (required for isolation)
     * @return array|null The published entity data or null if not found
     * @throws \RuntimeException If the query fails
     */
    public function getPublishedVersion(string $entityType, int $entityId, string $tenantId): ?array
    {
        // Input validation - fail fast on invalid input
        if (empty($entityType) || empty($entityId) || empty($tenantId)) {
            throw new InvalidArgumentException('Missing required parameters');
        }

        try {
            // Note: This query is read-only as it uses the query builder's get() method
            // which doesn't modify data. The where() and orderBy() methods only affect the query.
            return $this->model
                ->where('entity_type', $entityType)
                ->where('entity_id', $entityId)
                ->where('tenant_id', $tenantId)  // Enforce tenant isolation
                ->where('state', 'published')    // Only return published content
                ->orderBy('version', 'DESC')     // Get the latest version
                ->first();
        } catch (\Exception $e) {
            // Log the error with context but don't expose internals
            log_message('error', sprintf(
                'Failed to fetch published entity [%s:%d] for tenant [%s]: %s',
                $entityType,
                $entityId,
                $tenantId,
                $e->getMessage()
            ));
            
            // Fail closed - don't expose internal errors
            throw new \RuntimeException('Failed to fetch published content');
        }
    }
    
    /**
     * Get the current draft version of an entity
     */
    public function getDraftVersion(string $entityType, int $entityId): ?array
    {
        $versions = $this->model
            ->where('entity_type', $entityType)
            ->where('entity_id', $entityId)
            ->where('state', PublishableEntityModel::STATE_DRAFT)
            ->orderBy('version', 'DESC')
            ->findAll(1);
            
        return $versions[0] ?? null;
    }
    
    /**
     * List all versions of an entity
     */
    public function listVersions(string $entityType, int $entityId): array
    {
        return $this->model
            ->where('entity_type', $entityType)
            ->where('entity_id', $entityId)
            ->orderBy('version', 'DESC')
            ->findAll();
    }
    
    /**
     * Check if an entity is published
     */
    public function isPublished(string $entityType, int $entityId): bool
    {
        return $this->model->isPublished($entityType, $entityId);
    }
    
    /**
     * Get the current state of an entity
     */
    public function getCurrentState(string $entityType, int $entityId): ?string
    {
        return $this->model->getCurrentState($entityType, $entityId);
    }
    
    /**
     * Soft delete all versions of an entity
     */
    public function deleteEntity(string $entityType, int $entityId): bool
    {
        return $this->model->deleteEntity($entityType, $entityId);
    }

        
}