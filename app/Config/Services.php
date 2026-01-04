<?php

namespace App\Config;

/**
 * Services Configuration
 * 
 * Configuration stub for service definitions in the AIBOS Multi-Tenant CMS.
 * 
 * PURPOSE: Establish service configuration structure
 * PHASE: Execution Phase 1 - System Skeleton & Bootstrapping
 * 
 * NOTE: This configuration is intentionally inactive. No service
 * configuration is allowed in Phase 1. This file exists only to
 * establish the structural foundation for future service configuration.
 * 
 * INACTIVE: All service definitions are commented out until Phase 2+
 * 
 * @package App\Config
 */
class Services
{
    /**
     * Service definitions registry
     * 
     * NOTE: Empty registry is intentional for Phase 1.
     * Service definitions will be activated in future phases.
     * 
     * @var array
     */
    public static $services = [
        // Phase 1: No service definitions allowed
        // Future phases will define:
        // 'tenantService' => 'App\Services\Core\TenantService',
        // 'authenticationService' => 'App\Services\Core\AuthenticationService',
        // 'configurationService' => 'App\Services\Core\ConfigurationService',
    ];
    
    /**
     * Placeholder for service registration
     * 
     * NOTE: Inactive implementation for Phase 1.
     * Service registration will be activated in future phases.
     * 
     * @param string $name
     * @param string $class
     * @return void
     */
    public static function register(string $name, string $class): void
    {
        // Phase 1: Inactive - no service registration allowed
        // Future phases will implement service registration
    }
    
    /**
     * Placeholder for service resolution
     * 
     * NOTE: Inactive implementation for Phase 1.
     * Service resolution will be activated in future phases.
     * 
     * @param string $name
     * @return string|null
     */
    public static function resolve(string $name): ?string
    {
        // Phase 1: Inactive - no service resolution allowed
        // Future phases will implement service resolution
        return null;
    }
}
