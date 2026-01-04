<?php

namespace App\Cms\Repositories;

use App\Cms\Domain\Entities\Media;

/**
 * Media Repository Interface
 * 
 * Contract for media data access in the AIBOS Multi-Tenant CMS.
 * 
 * PURPOSE: Define media repository operations without implementation
 * PHASE: Execution Phase 5 — CMS Domain Foundations
 * 
 * NOTE: This interface defines repository operations only. No actual
 * database queries, persistence, or data access is allowed in Phase 5.
 * This interface exists only to establish the repository foundation for future phases.
 * 
 * SECURITY: All repository operations must be tenant-scoped. No cross-tenant
 * media access is allowed. Repository must enforce tenant isolation.
 * 
 * @package App\Cms\Repositories
 */
interface MediaRepositoryInterface
{
    /**
     * Find media by ID
     * 
     * NOTE: Returns media entity for given ID within tenant scope.
     * Must enforce tenant isolation and return null for not found.
     * 
     * @param int $mediaId
     * @param int $tenantId
     * @return Media|null
     */
    public function findById(int $mediaId, int $tenantId): ?Media;
    
    /**
     * Find media by filename
     * 
     * NOTE: Returns media entity for given filename within tenant scope.
     * Must enforce tenant isolation and return null for not found.
     * 
     * @param string $filename
     * @param int $tenantId
     * @return Media|null
     */
    public function findByFilename(string $filename, int $tenantId): ?Media;
    
    /**
     * Find all media for tenant
     * 
     * NOTE: Returns all media for given tenant.
     * Must enforce tenant isolation and respect soft deletes.
     * 
     * @param int $tenantId
     * @return array
     */
    public function findAll(int $tenantId): array;
    
    /**
     * Find media by MIME type for tenant
     * 
     * NOTE: Returns media with specific MIME type for given tenant.
     * Must validate MIME type and enforce tenant isolation.
     * 
     * @param string $mimeType
     * @param int $tenantId
     * @return array
     */
    public function findByMimeType(string $mimeType, int $tenantId): array;
    
    /**
     * Find images for tenant
     * 
     * NOTE: Returns image media for given tenant.
     * Used for image galleries and selections.
     * 
     * @param int $tenantId
     * @return array
     */
    public function findImages(int $tenantId): array;
    
    /**
     * Find videos for tenant
     * 
     * NOTE: Returns video media for given tenant.
     * Used for video galleries and selections.
     * 
     * @param int $tenantId
     * @return array
     */
    public function findVideos(int $tenantId): array;
    
    /**
     * Find documents for tenant
     * 
     * NOTE: Returns document media for given tenant.
     * Used for document libraries and downloads.
     * 
     * @param int $tenantId
     * @return array
     */
    public function findDocuments(int $tenantId): array;
    
    /**
     * Find media by status for tenant
     * 
     * NOTE: Returns media with specific status for given tenant.
     * Must validate status and enforce tenant isolation.
     * 
     * @param string $status
     * @param int $tenantId
     * @return array
     */
    public function findByStatus(string $status, int $tenantId): array;
    
    /**
     * Save media entity
     * 
     * NOTE: Persists media entity to storage.
     * Must enforce tenant isolation and validation.
     * 
     * @param Media $media
     * @return bool
     */
    public function save(Media $media): bool;
    
    /**
     * Delete media entity
     * 
     * NOTE: Soft deletes media entity from storage.
     * Must enforce tenant isolation and preserve audit trail.
     * 
     * @param Media $media
     * @return bool
     */
    public function delete(Media $media): bool;
    
    /**
     * Check if filename exists for tenant
     * 
     * NOTE: Returns whether filename exists within tenant scope.
     * Used for filename uniqueness validation.
     * 
     * @param string $filename
     * @param int $tenantId
     * @param int|null $excludeId
     * @return bool
     */
    public function filenameExists(string $filename, int $tenantId, ?int $excludeId = null): bool;
    
    /**
     * Count media for tenant
     * 
     * NOTE: Returns total media count for given tenant.
     * Must respect soft deletes and tenant isolation.
     * 
     * @param int $tenantId
     * @return int
     */
    public function count(int $tenantId): int;
    
    /**
     * Count media by MIME type for tenant
     * 
     * NOTE: Returns media count by MIME type for given tenant.
     * Used for storage analytics and management.
     * 
     * @param string $mimeType
     * @param int $tenantId
     * @return int
     */
    public function countByMimeType(string $mimeType, int $tenantId): int;
    
    /**
     * Get total storage size for tenant
     * 
     * NOTE: Returns total storage size used by media for given tenant.
     * Used for quota management and analytics.
     * 
     * @param int $tenantId
     * @return int
     */
    public function getTotalStorageSize(int $tenantId): int;
    
    /**
     * Search media for tenant
     * 
     * NOTE: Returns media matching search criteria for given tenant.
     * Must enforce tenant isolation and search safety.
     * 
     * @param string $query
     * @param int $tenantId
     * @return array
     */
    public function search(string $query, int $tenantId): array;
    
    /**
     * Find recent media for tenant
     * 
     * NOTE: Returns recently uploaded media for given tenant.
     * Used for media library and recent uploads display.
     * 
     * @param int $tenantId
     * @param int $limit
     * @return array
     */
    public function findRecent(int $tenantId, int $limit = 10): array;
}
