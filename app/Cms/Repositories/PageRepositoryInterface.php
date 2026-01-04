<?php

namespace App\Cms\Repositories;

use App\Cms\Domain\Entities\Page;

/**
 * Page Repository Interface
 * 
 * Contract for page data access in the AIBOS Multi-Tenant CMS.
 * 
 * PURPOSE: Define page repository operations without implementation
 * PHASE: Execution Phase 5 — CMS Domain Foundations
 * 
 * NOTE: This interface defines repository operations only. No actual
 * database queries, persistence, or data access is allowed in Phase 5.
 * This interface exists only to establish the repository foundation for future phases.
 * 
 * SECURITY: All repository operations must be tenant-scoped. No cross-tenant
 * data access is allowed. Repository must enforce tenant isolation.
 * 
 * @package App\Cms\Repositories
 */
interface PageRepositoryInterface
{
    /**
     * Find page by ID
     * 
     * NOTE: Returns page entity for given ID within tenant scope.
     * Must enforce tenant isolation and return null for not found.
     * 
     * @param int $pageId
     * @param int $tenantId
     * @return Page|null
     */
    public function findById(int $pageId, int $tenantId): ?Page;
    
    /**
     * Find page by slug
     * 
     * NOTE: Returns page entity for given slug within tenant scope.
     * Must enforce tenant isolation and return null for not found.
     * 
     * @param string $slug
     * @param int $tenantId
     * @return Page|null
     */
    public function findBySlug(string $slug, int $tenantId): ?Page;
    
    /**
     * Find homepage for tenant
     * 
     * NOTE: Returns homepage page for given tenant.
     * Must return null if no homepage is set.
     * 
     * @param int $tenantId
     * @return Page|null
     */
    public function findHomepage(int $tenantId): ?Page;
    
    /**
     * Find all pages for tenant
     * 
     * NOTE: Returns all pages for given tenant.
     * Must enforce tenant isolation and respect soft deletes.
     * 
     * @param int $tenantId
     * @return array
     */
    public function findAll(int $tenantId): array;
    
    /**
     * Find published pages for tenant
     * 
     * NOTE: Returns published pages for given tenant.
     * Used for runtime content display.
     * 
     * @param int $tenantId
     * @return array
     */
    public function findPublished(int $tenantId): array;
    
    /**
     * Find pages by status for tenant
     * 
     * NOTE: Returns pages with specific status for given tenant.
     * Must validate status and enforce tenant isolation.
     * 
     * @param string $status
     * @param int $tenantId
     * @return array
     */
    public function findByStatus(string $status, int $tenantId): array;
    
    /**
     * Find child pages for parent
     * 
     * NOTE: Returns child pages for given parent page.
     * Must enforce tenant isolation and parent-child relationship.
     * 
     * @param int $parentId
     * @param int $tenantId
     * @return array
     */
    public function findChildren(int $parentId, int $tenantId): array;
    
    /**
     * Find top-level pages for tenant
     * 
     * NOTE: Returns top-level pages (no parent) for given tenant.
     * Used for navigation and hierarchy building.
     * 
     * @param int $tenantId
     * @return array
     */
    public function findTopLevel(int $tenantId): array;
    
    /**
     * Save page entity
     * 
     * NOTE: Persists page entity to storage.
     * Must enforce tenant isolation and validation.
     * 
     * @param Page $page
     * @return bool
     */
    public function save(Page $page): bool;
    
    /**
     * Delete page entity
     * 
     * NOTE: Soft deletes page entity from storage.
     * Must enforce tenant isolation and preserve audit trail.
     * 
     * @param Page $page
     * @return bool
     */
    public function delete(Page $page): bool;
    
    /**
     * Check if slug exists for tenant
     * 
     * NOTE: Returns whether slug exists within tenant scope.
     * Used for slug uniqueness validation.
     * 
     * @param string $slug
     * @param int $tenantId
     * @param int|null $excludeId
     * @return bool
     */
    public function slugExists(string $slug, int $tenantId, ?int $excludeId = null): bool;
    
    /**
     * Count pages for tenant
     * 
     * NOTE: Returns total page count for given tenant.
     * Must respect soft deletes and tenant isolation.
     * 
     * @param int $tenantId
     * @return int
     */
    public function count(int $tenantId): int;
    
    /**
     * Count pages by status for tenant
     * 
     * NOTE: Returns page count by status for given tenant.
     * Used for dashboard and analytics.
     * 
     * @param string $status
     * @param int $tenantId
     * @return int
     */
    public function countByStatus(string $status, int $tenantId): int;
    
    /**
     * Get page hierarchy for tenant
     * 
     * NOTE: Returns hierarchical page structure for given tenant.
     * Must respect sort order and tenant isolation.
     * 
     * @param int $tenantId
     * @return array
     */
    public function getHierarchy(int $tenantId): array;
    
    /**
     * Search pages for tenant
     * 
     * NOTE: Returns pages matching search criteria for given tenant.
     * Must enforce tenant isolation and search safety.
     * 
     * @param string $query
     * @param int $tenantId
     * @return array
     */
    public function search(string $query, int $tenantId): array;
}
