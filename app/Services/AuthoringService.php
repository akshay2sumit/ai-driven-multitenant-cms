<?php

namespace App\Services;

use App\Repositories\PublishableEntityRepository;
use App\Tenant\Context as TenantContext;
use InvalidArgumentException;
use RuntimeException;

/**
 * Authoring Service
 * 
 * Handles the business logic for authoring operations with proper permission
 * enforcement and tenant isolation. This service acts as the single entry point
 * for all authoring operations, ensuring consistent permission checks and
 * maintaining the integrity of the publishing workflow.
 * 
 * Security:
 * - All methods require explicit permission checks
 * - Tenant isolation is strictly enforced (fail-closed on tenant mismatch)
 * - Repository calls are only made after successful authorization
 * - Fail-closed by default on security violations
 * - Tenant context is validated against active tenant to prevent cross-tenant access
 */
class AuthoringService
{
    /**
     * @var PublishableEntityRepository
     */
    private $repository;

    /**
     * @var string Current tenant context
     */
    private $currentTenant;

    /**
     * @var string Current user role
     */
    private $currentUserRole;

    /**
     * @var int Current user ID
     */
    private $currentUserId;

    /**
     * Constructor
     *
     * @param PublishableEntityRepository $repository
     * @param string $tenantId Current tenant context
     * @param string $userRole Current user role (author|reviewer|publisher)
     * @param int $userId Current user ID
     * @throws RuntimeException If tenant context is invalid or doesn't match
     */
    public function __construct(
        PublishableEntityRepository $repository,
        string $tenantId,
        string $userRole,
        int $userId
    ) {
        // Enforce tenant context is available and matches the provided tenantId
        $activeTenant = TenantContext::require();
        if ($activeTenant !== $tenantId) {
            throw new RuntimeException(
                'Tenant context mismatch. Operation not permitted.'
            );
        }

        $this->repository = $repository;
        $this->currentTenant = $tenantId;
        $this->currentUserRole = $userRole;
        $this->currentUserId = $userId;
    }

    /**
     * Create a new draft version of an entity
     *
     * @param string $entityType Type of the entity
     * @param int $entityId ID of the entity
     * @param array $data Entity data
     * @return array Created draft entity
     * @throws \RuntimeException If user lacks permissions or operation fails
     */
    public function createDraft(string $entityType, int $entityId, array $data): array
    {
        $this->requireRole('author');
        
        try {
            return $this->repository->createDraft(
                $entityType,
                $entityId,
                $data,
                $this->currentUserId
            );
        } catch (\Exception $e) {
            throw new \RuntimeException("Failed to create draft: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Update an existing draft
     *
     * @param string $entityType Type of the entity
     * @param int $entityId ID of the entity
     * @param array $data Updated entity data
     * @return array Updated draft entity
     * @throws \RuntimeException If user lacks permissions or operation fails
     * 
     * @note In Phase 14, we're reusing createDraft() for updates as the underlying
     *       repository handles versioning. This is an intentional simplification
     *       for the current phase and will be refactored in a future phase.
     */
    public function updateDraft(string $entityType, int $entityId, array $data): array
    {
        $this->requireRole('author');
        
        try {
            // Phase 14: Using createDraft for updates as per current architecture
            // This will be replaced with a dedicated update method in a future phase
            return $this->repository->createDraft(
                $entityType,
                $entityId,
                $data,
                $this->currentUserId
            );
        } catch (\Exception $e) {
            throw new \RuntimeException("Failed to update draft: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Submit a draft for review
     *
     * @param string $entityType Type of the entity
     * @param int $entityId ID of the entity
     * @return array The updated entity in review state
     * @throws \RuntimeException If user lacks permissions or operation fails
     */
    public function submitForReview(string $entityType, int $entityId): array
    {
        // Require at least reviewer role to submit for review
        $this->requireRole('reviewer');
        
        try {
            return $this->repository->submitForReview(
                $this->getVersionId($entityType, $entityId, 'draft'),
                $this->currentUserId
            );
        } catch (\Exception $e) {
            throw new \RuntimeException("Failed to submit for review: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Publish a reviewed entity
     *
     * @param string $entityType Type of the entity
     * @param int $entityId ID of the entity
     * @return array The published entity
     * @throws \RuntimeException If user lacks permissions or operation fails
     */
    public function publish(string $entityType, int $entityId): array
    {
        $this->requireRole('publisher');
        
        try {
            return $this->repository->publish(
                $this->getVersionId($entityType, $entityId, 'review'),
                $this->currentUserId
            );
        } catch (\Exception $e) {
            throw new \RuntimeException("Failed to publish: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Archive a published entity
     *
     * @param string $entityType Type of the entity
     * @param int $entityId ID of the entity
     * @return array The archived entity
     * @throws \RuntimeException If user lacks permissions or operation fails
     */
    public function archive(string $entityType, int $entityId): array
    {
        $this->requireRole('publisher');
        
        try {
            return $this->repository->archive(
                $this->getVersionId($entityType, $entityId, 'published'),
                $this->currentUserId
            );
        } catch (\Exception $e) {
            throw new \RuntimeException("Failed to archive: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Retract a published entity
     *
     * @param string $entityType Type of the entity
     * @param int $entityId ID of the entity
     * @return array The retracted entity
     * @throws \RuntimeException If user lacks permissions or operation fails
     */
    public function retract(string $entityType, int $entityId): array
    {
        $this->requireRole('publisher');
        
        try {
            return $this->repository->retract(
                $this->getVersionId($entityType, $entityId, 'published'),
                $this->currentUserId
            );
        } catch (\Exception $e) {
            throw new \RuntimeException("Failed to retract: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Get the ID of a specific version of an entity
     * 
     * @param string $entityType Type of the entity
     * @param int $entityId ID of the entity
     * @param string $state Expected state of the version
     * @return int The version ID
     * @throws RuntimeException As version resolution is deferred to a later phase
     * 
     * @note Version resolution is deferred to a future phase as per Phase 14 requirements.
     *       This method will be implemented when we add versioning support.
     */
    private function getVersionId(string $entityType, int $entityId, string $state): int
    {
        throw new RuntimeException(
            'Version resolution is not implemented in the current phase. ' .
            'This functionality will be added in a future update.'
        );
    }

    /**
     * Require that the current user has the specified role
     *
     * @param string $requiredRole Required role (author|reviewer|publisher)
     * @throws \RuntimeException If the user doesn't have the required role
     */
    private function requireRole(string $requiredRole): void
    {
        $roleHierarchy = [
            'author' => 1,
            'reviewer' => 2,
            'publisher' => 3
        ];

        if (!isset($roleHierarchy[$this->currentUserRole]) || 
            $roleHierarchy[$this->currentUserRole] < $roleHierarchy[$requiredRole]) {
            throw new \RuntimeException(
                sprintf(
                    'Insufficient permissions. Required role: %s, Current role: %s',
                    $requiredRole,
                    $this->currentUserRole
                )
            );
        }
    }
}
