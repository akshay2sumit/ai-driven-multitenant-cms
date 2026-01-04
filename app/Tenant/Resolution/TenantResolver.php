<?php

namespace App\Tenant\Resolution;

use App\Tenant\Context\TenantContext;

/**
 * Tenant Resolver
 * 
 * Path-based tenant resolution for the AIBOS Multi-Tenant CMS.
 * 
 * PURPOSE: Extract tenant identifier from request path and create context
 * PHASE: Execution Phase 2 — Database Foundations & Tenant Resolution
 * 
 * NOTE: This resolver implements path-based tenant identification only.
 * No database validation, authentication, or business logic is allowed
 * in Phase 2. This resolver exists only to establish the tenant identification
 * foundation for future phases.
 * 
 * SECURITY: Fail-closed by design - invalid paths result in null context.
 * 
 * @package App\Tenant\Resolution
 */
class TenantResolver
{
    /**
     * Path pattern for tenant resolution
     * 
     * NOTE: Matches ADR-003 specification: /t/{tenant_identifier}/...
     * 
     * @var string
     */
    private const TENANT_PATH_PATTERN = '/^\/t\/([a-zA-Z0-9_-]+)(?:\/.*)?$/';
    
    /**
     * Resolve tenant from request path
     * 
     * NOTE: Path-based resolution only. No database validation in Phase 2.
     * This method extracts tenant identifier from path and creates basic context.
     * 
     * @param string $requestPath
     * @return TenantContext
     */
    public function resolveFromPath(string $requestPath): TenantContext
    {
        // Phase 2: Path-based extraction only
        $tenantIdentifier = $this->extractTenantIdentifier($requestPath);
        
        if ($tenantIdentifier === null) {
            // Fail-closed: Invalid path results in null context
            return TenantContext::createNull();
        }
        
        // Phase 2: Create context without database validation
        // Future phases will validate tenant against database
        return TenantContext::create($tenantIdentifier, 0, 'unknown');
    }
    
    /**
     * Extract tenant identifier from path
     * 
     * NOTE: Implements ADR-003 path-based pattern matching.
     * Returns null if path doesn't match expected pattern.
     * 
     * @param string $requestPath
     * @return string|null
     */
    private function extractTenantIdentifier(string $requestPath): ?string
    {
        // Phase 2: Simple regex pattern matching
        $matches = [];
        
        if (preg_match(self::TENANT_PATH_PATTERN, $requestPath, $matches)) {
            return $matches[1];
        }
        
        // Fail-closed: Invalid pattern returns null
        return null;
    }
    
    /**
     * Validate tenant identifier format
     * 
     * NOTE: Basic format validation only. No database validation in Phase 2.
     * This method ensures tenant identifier follows basic naming rules.
     * 
     * @param string $tenantIdentifier
     * @return bool
     */
    public function validateTenantIdentifier(string $tenantIdentifier): bool
    {
        // Phase 2: Basic format validation
        // Future phases will add database existence validation
        
        // Must be 3-50 characters
        if (strlen($tenantIdentifier) < 3 || strlen($tenantIdentifier) > 50) {
            return false;
        }
        
        // Must contain only alphanumeric, underscore, or hyphen
        if (!preg_match('/^[a-zA-Z0-9_-]+$/', $tenantIdentifier)) {
            return false;
        }
        
        // Must not start or end with underscore or hyphen
        if (preg_match('/^[_-]|[_-]$/', $tenantIdentifier)) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Check if path requires tenant resolution
     * 
     * NOTE: Determines if request path follows tenant pattern.
     * Used to decide whether tenant resolution should be attempted.
     * 
     * @param string $requestPath
     * @return bool
     */
    public function requiresTenantResolution(string $requestPath): bool
    {
        // Phase 2: Simple pattern check
        return preg_match(self::TENANT_PATH_PATTERN, $requestPath) === 1;
    }
    
    /**
     * Get tenant path pattern (for testing/documentation)
     * 
     * NOTE: Returns the regex pattern used for tenant resolution.
     * This method exists for testing and documentation purposes.
     * 
     * @return string
     */
    public function getTenantPathPattern(): string
    {
        return self::TENANT_PATH_PATTERN;
    }
}
