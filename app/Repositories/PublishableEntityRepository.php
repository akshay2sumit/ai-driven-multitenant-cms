<?php

namespace App\Repositories;

use App\Contracts\ReadOnlyPublishingRepositoryInterface;
use App\Models\PublishableEntityModel;
use App\Tenant\Context as TenantContext;
use InvalidArgumentException;
use RuntimeException;

/**
 * PublishableEntityRepository
 * 
 * Handles the business logic for managing publishable entities including
 * creating drafts, submitting for review, publishing, archiving, and retracting.
 * 
 * This repository enforces tenant isolation and implements proper state transitions
 * for the entity lifecycle.
 */
class PublishableEntityRepository implements ReadOnlyPublishingRepositoryInterface
{
    /*
     * SECURITY NOTE:
     * - This repository assumes the caller has already performed all necessary
     *   authorization checks.
     * - Role and permission enforcement will be implemented in a dedicated
     *   authorization layer in a future phase.
     * - All methods enforce tenant isolation to prevent cross-tenant data access.
     * 
     * TODO: Implement comprehensive authorization checks in a dedicated layer
     * TODO: Add audit logging for all state transitions
     */
    /**
     * @var PublishableEntityModel
     */
    protected $model;
    
    /**
     * Constructor
     * 
     * @param PublishableEntityModel $model
     */
    public function __construct(PublishableEntityModel $model)
    {
        $this->model = $model;
    }
    
    /**
     * Create a new draft version of an entity
     * 
     * @param string $entityType The type of entity
     * @param int $entityId The ID of the entity
     * @param array $data The entity data
     * @param int|null $createdBy The ID of the user creating the draft
     * @return array The created draft entity
     * @throws RuntimeException If draft creation fails
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
     * 
     * @param int $entityId The ID of the entity to submit for review
     * @param int|null $submittedBy The ID of the user submitting for review
     * @return array The updated entity
     * @throws InvalidArgumentException If the entity is not in draft state
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
     * 
     * @param int $entityId The ID of the entity to publish
     * @param int|null $publishedBy The ID of the user publishing the entity
     * @return array The published entity
     * @throws InvalidArgumentException If the entity is not in review state
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
     * 
     * @param int $entityId The ID of the entity to archive
     * @param int|null $archivedBy The ID of the user archiving the entity
     * @return array The archived entity
     * @throws InvalidArgumentException If the entity is not in published state
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
     * 
     * @param int $entityId The ID of the entity to retract
     * @param int|null $retractedBy The ID of the user retracting the entity
     * @return array The retracted entity
     * @throws InvalidArgumentException If the entity is not in published state
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
     * Get a specific version of an entity by its ID with tenant isolation
     * 
     * Note: This retrieves a specific version of an entity, not the logical entity.
     * To get the latest version of an entity, use the appropriate getter method.
     * 
     * @param int $id The version-specific ID of the entity to retrieve
     * @return array The entity data
     * @throws InvalidArgumentException If the entity is not found or access is denied
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
                ->where('state', PublishableEntityModel::STATE_PUBLISHED)
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
     * 
     * @param string $entityType The type of entity
     * @param int $entityId The ID of the entity
     * @return array|null The draft entity or null if not found
     * @throws InvalidArgumentException If entity is not found or access denied
     */
    public function getDraftVersion(string $entityType, int $entityId): ?array
    {
        try {
            $tenantId = TenantContext::require();
            
            return $this->model
                ->where('entity_type', $entityType)
                ->where('entity_id', $entityId)
                ->where('tenant_id', $tenantId)
                ->where('state', PublishableEntityModel::STATE_DRAFT)
                ->orderBy('version', 'DESC')
                ->first();
        } catch (\Exception $e) {
            log_message('error', sprintf(
                'Failed to fetch draft version for entity [%s:%d]: %s',
                $entityType,
                $entityId,
                $e->getMessage()
            ));
            
            throw new \RuntimeException('Failed to fetch draft version');
        }
    }
    
    /**
     * Get all versions of an entity
     * 
     * @param string $entityType The type of entity
     * @param int $entityId The ID of the entity
     * @return array The versions of the entity
     * @throws \RuntimeException If the query fails
     */
    public function getVersions(string $entityType, int $entityId): array
    {
        try {
            $tenantId = TenantContext::require();
            
            return $this->model
                ->where('entity_type', $entityType)
                ->where('entity_id', $entityId)
                ->where('tenant_id', $tenantId)
                ->orderBy('version', 'ASC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', sprintf(
                'Failed to list versions for entity [%s:%d]: %s',
                $entityType,
                $entityId,
                $e->getMessage()
            ));
            
            throw new \RuntimeException('Failed to list versions');
        }
    }
    
    /**
     * Check if an entity has a published version
     * 
     * @param string $entityType The type of entity
     * @param int $entityId The ID of the entity
     * @return bool True if the entity has a published version, false otherwise
     */
    public function isPublished(string $entityType, int $entityId): bool
    {
        try {
            $tenantId = TenantContext::require();
            
            $count = $this->model
                ->where('entity_type', $entityType)
                ->where('entity_id', $entityId)
                ->where('tenant_id', $tenantId)
                ->where('state', PublishableEntityModel::STATE_PUBLISHED)
                ->countAllResults();
                
            return $count > 0;
        } catch (\Exception $e) {
            log_message('error', sprintf(
                'Failed to check published status for entity [%s:%d]: %s',
                $entityType,
                $entityId,
                $e->getMessage()
            ));
            
            return false;
        }
    }
    
    /**
     * Get the current state of an entity
     * 
     * @param string $entityType The type of entity
     * @param int $entityId The ID of the entity
     * @return string|null The current state or null if not found
     */
    public function getCurrentState(string $entityType, int $entityId): ?string
    {
        try {
            $tenantId = TenantContext::require();
            
            $entity = $this->model
                ->where('entity_type', $entityType)
                ->where('entity_id', $entityId)
                ->where('tenant_id', $tenantId)
                ->orderBy('version', 'DESC')
                ->first();
                
            return $entity ? $entity['state'] : null;
        } catch (\Exception $e) {
            log_message('error', sprintf(
                'Failed to get current state for entity [%s:%d]: %s',
                $entityType,
                $entityId,
                $e->getMessage()
            ));
            
            throw new \RuntimeException('Failed to get current state');
        }
    }
    
    /**
     * Delete an entity and all its versions
     * 
     * @param string $entityType The type of entity
     * @param int $entityId The ID of the entity to delete
     * @return never
     * @throws \RuntimeException Always throws as deletion is not yet implemented
     * 
     * @todo Implement entity deletion in a future phase with proper authorization
     * @todo Add comprehensive audit logging for deletion operations
     * @todo Consider implementing soft-delete with retention period
     */
    public function deleteEntity(string $entityType, int $entityId): bool
    {
        throw new \RuntimeException(
            'Entity deletion is not yet implemented. ' .
            'This feature will be added in a future phase with proper authorization and audit logging.'
        );
        
        // Future implementation will go here with proper authorization checks
        // and audit logging as per security requirements.
    }
}