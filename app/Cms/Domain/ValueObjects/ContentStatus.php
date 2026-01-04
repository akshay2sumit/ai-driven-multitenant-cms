<?php

namespace App\Cms\Domain\ValueObjects;

/**
 * Content Status Value Object
 * 
 * Defines the lifecycle states for CMS content entities.
 * 
 * PURPOSE: Provide type-safe content status definitions
 * PHASE: Execution Phase 5 — CMS Domain Foundations
 * 
 * NOTE: This value object defines status constants only. No state transition
 * logic, validation, or persistence is allowed in Phase 5. This value object
 * exists only to establish the content lifecycle foundation for future phases.
 * 
 * SECURITY: Status transitions must be explicitly controlled in future phases.
 * No automatic state changes or unauthorized transitions allowed.
 * 
 * @package App\Cms\Domain\ValueObjects
 */
class ContentStatus
{
    /**
     * Draft status
     * 
     * NOTE: Content is in draft state, not published.
     * Only visible to authors and editors in authoring context.
     * 
     * @var string
     */
    public const DRAFT = 'draft';
    
    /**
     * Published status
     * 
     * NOTE: Content is published and publicly accessible.
     * Visible in runtime context according to visibility rules.
     * 
     * @var string
     */
    public const PUBLISHED = 'published';
    
    /**
     * Archived status
     * 
     * NOTE: Content is archived and not publicly accessible.
     * Preserved for historical reference but not active.
     * 
     * @var string
     */
    public const ARCHIVED = 'archived';
    
    /**
     * Review status
     * 
     * NOTE: Content is under review for publication.
     * Limited visibility to reviewers and editors.
     * 
     * @var string
     */
    public const REVIEW = 'review';
    
    /**
     * All valid statuses
     * 
     * NOTE: Complete list of all valid content statuses.
     * Used for validation and type safety.
     * 
     * @var array
     */
    public const VALID_STATUSES = [
        self::DRAFT,
        self::PUBLISHED,
        self::ARCHIVED,
        self::REVIEW,
    ];
    
    /**
     * Authoring context statuses
     * 
     * NOTE: Statuses that are valid in authoring context.
     * All statuses are valid in authoring context.
     * 
     * @var array
     */
    public const AUTHORING_STATUSES = [
        self::DRAFT,
        self::PUBLISHED,
        self::ARCHIVED,
        self::REVIEW,
    ];
    
    /**
     * Runtime context statuses
     * 
     * NOTE: Statuses that are valid in runtime context.
     * Only published content should be visible in runtime.
     * 
     * @var array
     */
    public const RUNTIME_STATUSES = [
        self::PUBLISHED,
    ];
    
    /**
     * Content Status constructor
     * 
     * NOTE: Private constructor to enforce static usage.
     * This is a value object with constants only.
     */
    private function __construct()
    {
        // Phase 5: No implementation allowed
        // Future phases may add validation logic
    }
    
    /**
     * Check if status is valid
     * 
     * NOTE: Returns whether status is in the valid list.
     * Used for type safety and validation.
     * 
     * @param string $status
     * @return bool
     */
    public static function isValid(string $status): bool
    {
        return in_array($status, self::VALID_STATUSES);
    }
    
    /**
     * Check if status is valid for authoring context
     * 
     * NOTE: Returns whether status is valid in authoring context.
     * Used for context-specific validation.
     * 
     * @param string $status
     * @return bool
     */
    public static function isValidForAuthoring(string $status): bool
    {
        return in_array($status, self::AUTHORING_STATUSES);
    }
    
    /**
     * Check if status is valid for runtime context
     * 
     * NOTE: Returns whether status is valid in runtime context.
     * Used for context-specific validation.
     * 
     * @param string $status
     * @return bool
     */
    public static function isValidForRuntime(string $status): bool
    {
        return in_array($status, self::RUNTIME_STATUSES);
    }
    
    /**
     * Get all valid statuses
     * 
     * NOTE: Returns complete list of valid statuses.
     * Used for dropdown options and validation.
     * 
     * @return array
     */
    public static function getAll(): array
    {
        return self::VALID_STATUSES;
    }
    
    /**
     * Get authoring context statuses
     * 
     * NOTE: Returns statuses valid in authoring context.
     * Used for authoring interface options.
     * 
     * @return array
     */
    public static function getAuthoringStatuses(): array
    {
        return self::AUTHORING_STATUSES;
    }
    
    /**
     * Get runtime context statuses
     * 
     * NOTE: Returns statuses valid in runtime context.
     * Used for runtime filtering and visibility.
     * 
     * @return array
     */
    public static function getRuntimeStatuses(): array
    {
        return self::RUNTIME_STATUSES;
    }
    
    /**
     * Get status display name
     * 
     * NOTE: Returns human-readable display name for status.
     * Used for user interface and communication.
     * 
     * @param string $status
     * @return string
     */
    public static function getDisplayName(string $status): string
    {
        $displayNames = [
            self::DRAFT => 'Draft',
            self::PUBLISHED => 'Published',
            self::ARCHIVED => 'Archived',
            self::REVIEW => 'Under Review',
        ];
        
        return $displayNames[$status] ?? 'Unknown';
    }
    
    /**
     * Get status description
     * 
     * NOTE: Returns detailed description for status.
     * Used for help text and documentation.
     * 
     * @param string $status
     * @return string
     */
    public static function getDescription(string $status): string
    {
        $descriptions = [
            self::DRAFT => 'Content is in draft state and not published',
            self::PUBLISHED => 'Content is published and publicly accessible',
            self::ARCHIVED => 'Content is archived and no longer active',
            self::REVIEW => 'Content is under review for publication',
        ];
        
        return $descriptions[$status] ?? 'Unknown status';
    }
}
