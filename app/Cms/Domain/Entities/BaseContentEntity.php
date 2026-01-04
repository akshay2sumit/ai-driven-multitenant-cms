<?php

namespace App\Cms\Domain\Entities;

use App\Cms\Domain\ValueObjects\ContentStatus;

/**
 * Base Content Entity
 * 
 * Base entity for all CMS content types in the AIBOS Multi-Tenant CMS.
 * 
 * PURPOSE: Provide foundation for content entities with tenant binding
 * PHASE: Execution Phase 5 — CMS Domain Foundations
 * 
 * NOTE: This entity provides basic content structure only. No actual content
 * management, persistence, or business logic is allowed in Phase 5. This entity
 * exists only to establish the content foundation for future phases.
 * 
 * SECURITY: All content entities are tenant-scoped. No cross-tenant content
 * access is allowed. Content status transitions must be explicitly controlled.
 * 
 * @package App\Cms\Domain\Entities
 */
abstract class BaseContentEntity
{
    /**
     * Content identifier
     * 
     * NOTE: Unique identifier for the content entity.
     * Used for content lookup and reference.
     * 
     * @var int|string
     */
    protected $id;
    
    /**
     * Tenant identifier
     * 
     * NOTE: Tenant this content belongs to.
     * All content must be tenant-scoped.
     * 
     * @var int
     */
    protected $tenantId;
    
    /**
     * Content title
     * 
     * NOTE: Human-readable title for the content.
     * Used for display and identification.
     * 
     * @var string
     */
    protected $title;
    
    /**
     * Content slug
     * 
     * NOTE: URL-friendly identifier for the content.
     * Must be unique within tenant scope.
     * 
     * @var string
     */
    protected $slug;
    
    /**
     * Content body
     * 
     * NOTE: Main content body or description.
     * Structure depends on content type.
     * 
     * @var string|null
     */
    protected $content;
    
    /**
     * Content status
     * 
     * NOTE: Current lifecycle status of the content.
     * Must be one of the defined content statuses.
     * 
     * @var string
     */
    protected $status;
    
    /**
     * Publication timestamp
     * 
     * NOTE: When content was published.
     * Null for unpublished content.
     * 
     * @var \DateTime|null
     */
    protected $publishedAt;
    
    /**
     * Creation timestamp
     * 
     * NOTE: When content was created.
     * Used for audit and sorting.
     * 
     * @var \DateTime
     */
    protected $createdAt;
    
    /**
     * Last update timestamp
     * 
     * NOTE: When content was last updated.
     * Used for audit and change tracking.
     * 
     * @var \DateTime|null
     */
    protected $updatedAt;
    
    /**
     * Deletion timestamp
     * 
     * NOTE: When content was soft deleted.
     * Null for active content.
     * 
     * @var \DateTime|null
     */
    protected $deletedAt;
    
    /**
     * Content metadata
     * 
     * NOTE: Additional content metadata.
     * Used for extensions and custom properties.
     * 
     * @var array
     */
    protected $metadata;
    
    /**
     * Base Content Entity constructor
     * 
     * NOTE: Protected constructor to enforce factory pattern.
     * Content creation must go through specific entity classes.
     * 
     * @param int $tenantId
     * @param string $title
     * @param string $slug
     * @param string $status
     */
    protected function __construct(int $tenantId, string $title, string $slug, string $status)
    {
        $this->id = null;
        $this->tenantId = $tenantId;
        $this->title = $title;
        $this->slug = $slug;
        $this->status = $status;
        $this->content = null;
        $this->publishedAt = null;
        $this->createdAt = new \DateTime();
        $this->updatedAt = null;
        $this->deletedAt = null;
        $this->metadata = [];
    }
    
    /**
     * Get content identifier
     * 
     * NOTE: Returns unique identifier for the content entity.
     * Used for content lookup and reference.
     * 
     * @return int|string|null
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Get tenant identifier
     * 
     * NOTE: Returns tenant this content belongs to.
     * All content must be tenant-scoped.
     * 
     * @return int
     */
    public function getTenantId(): int
    {
        return $this->tenantId;
    }
    
    /**
     * Get content title
     * 
     * NOTE: Returns human-readable title for the content.
     * Used for display and identification.
     * 
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }
    
    /**
     * Get content slug
     * 
     * NOTE: Returns URL-friendly identifier for the content.
     * Must be unique within tenant scope.
     * 
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }
    
    /**
     * Get content body
     * 
     * NOTE: Returns main content body or description.
     * Structure depends on content type.
     * 
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }
    
    /**
     * Get content status
     * 
     * NOTE: Returns current lifecycle status of the content.
     * Must be one of the defined content statuses.
     * 
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }
    
    /**
     * Get publication timestamp
     * 
     * NOTE: Returns when content was published.
     * Null for unpublished content.
     * 
     * @return \DateTime|null
     */
    public function getPublishedAt(): ?\DateTime
    {
        return $this->publishedAt;
    }
    
    /**
     * Get creation timestamp
     * 
     * NOTE: Returns when content was created.
     * Used for audit and sorting.
     * 
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
    
    /**
     * Get last update timestamp
     * 
     * NOTE: Returns when content was last updated.
     * Used for audit and change tracking.
     * 
     * @return \DateTime|null
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }
    
    /**
     * Get deletion timestamp
     * 
     * NOTE: Returns when content was soft deleted.
     * Null for active content.
     * 
     * @return \DateTime|null
     */
    public function getDeletedAt(): ?\DateTime
    {
        return $this->deletedAt;
    }
    
    /**
     * Get content metadata
     * 
     * NOTE: Returns additional content metadata.
     * Used for extensions and custom properties.
     * 
     * @return array
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }
    
    /**
     * Check if content is published
     * 
     * NOTE: Returns whether content is in published state.
     * Used for runtime visibility checks.
     * 
     * @return bool
     */
    public function isPublished(): bool
    {
        return $this->status === ContentStatus::PUBLISHED;
    }
    
    /**
     * Check if content is draft
     * 
     * NOTE: Returns whether content is in draft state.
     * Used for authoring context checks.
     * 
     * @return bool
     */
    public function isDraft(): bool
    {
        return $this->status === ContentStatus::DRAFT;
    }
    
    /**
     * Check if content is archived
     * 
     * NOTE: Returns whether content is in archived state.
     * Used for visibility and filtering.
     * 
     * @return bool
     */
    public function isArchived(): bool
    {
        return $this->status === ContentStatus::ARCHIVED;
    }
    
    /**
     * Check if content is under review
     * 
     * NOTE: Returns whether content is in review state.
     * Used for workflow and approval processes.
     * 
     * @return bool
     */
    public function isUnderReview(): bool
    {
        return $this->status === ContentStatus::REVIEW;
    }
    
    /**
     * Check if content is visible in runtime
     * 
     * NOTE: Returns whether content should be visible in runtime context.
     * Only published content is typically visible.
     * 
     * @return bool
     */
    public function isVisibleInRuntime(): bool
    {
        return ContentStatus::isValidForRuntime($this->status);
    }
    
    /**
     * Check if content is accessible in authoring
     * 
     * NOTE: Returns whether content is accessible in authoring context.
     * All non-deleted content is accessible in authoring.
     * 
     * @return bool
     */
    public function isAccessibleInAuthoring(): bool
    {
        return $this->deletedAt === null;
    }
    
    /**
     * Validate content structure
     * 
     * NOTE: Validates content meets structural requirements.
     * Ensures required properties and security constraints.
     * 
     * @return bool
     */
    public function validate(): bool
    {
        // Phase 5: Basic structural validation
        // Future phases will add business logic validation
        
        // Tenant ID must be positive
        if ($this->tenantId <= 0) {
            return false;
        }
        
        // Title must not be empty
        if (empty($this->title)) {
            return false;
        }
        
        // Slug must not be empty
        if (empty($this->slug)) {
            return false;
        }
        
        // Status must be valid
        if (!ContentStatus::isValid($this->status)) {
            return false;
        }
        
        // Created at must exist
        if ($this->createdAt === null) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Set content identifier
     * 
     * NOTE: Sets the unique identifier for content.
     * Used in future phases for persistence.
     * 
     * @param int|string $id
     * @return void
     */
    protected function setId($id): void
    {
        $this->id = $id;
    }
    
    /**
     * Set content body
     * 
     * NOTE: Sets the main content body.
     * Used in future phases for content editing.
     * 
     * @param string|null $content
     * @return void
     */
    protected function setContent(?string $content): void
    {
        $this->content = $content;
    }
    
    /**
     * Set content status
     * 
     * NOTE: Sets the content status with validation.
     * Used in future phases for status transitions.
     * 
     * @param string $status
     * @return void
     */
    protected function setStatus(string $status): void
    {
        if (ContentStatus::isValid($status)) {
            $this->status = $status;
            $this->updatedAt = new \DateTime();
        }
    }
    
    /**
     * Set publication timestamp
     * 
     * NOTE: Sets when content was published.
     * Used in future phases for publishing logic.
     * 
     * @param \DateTime|null $publishedAt
     * @return void
     */
    protected function setPublishedAt(?\DateTime $publishedAt): void
    {
        $this->publishedAt = $publishedAt;
        $this->updatedAt = new \DateTime();
    }
    
    /**
     * Add metadata
     * 
     * NOTE: Adds metadata to the content.
     * Used for extensions and custom properties.
     * 
     * @param string $key
     * @param mixed $value
     * @return void
     */
    protected function addMetadata(string $key, $value): void
    {
        $this->metadata[$key] = $value;
        $this->updatedAt = new \DateTime();
    }
}
