<?php

namespace App\Governance\Contracts;

class FeatureFlags
{
    /**
     * @var array In-memory storage for feature flags
     */
    private static array $flags = [
        'global' => [],
        'tenants' => []
    ];

    /**
     * Enable a global feature flag
     */
    public static function enableGlobal(string $feature): void
    {
        self::$flags['global'][$feature] = true;
    }

    /**
     * Disable a global feature flag
     */
    public static function disableGlobal(string $feature): void
    {
        self::$flags['global'][$feature] = false;
    }

    /**
     * Enable a feature flag for a specific tenant
     */
    public static function enableForTenant(string $feature, string $tenantId): void
    {
        if (!isset(self::$flags['tenants'][$tenantId])) {
            self::$flags['tenants'][$tenantId] = [];
        }
        self::$flags['tenants'][$tenantId][$feature] = true;
    }

    /**
     * Disable a feature flag for a specific tenant
     */
    public static function disableForTenant(string $feature, string $tenantId): void
    {
        if (isset(self::$flags['tenants'][$tenantId])) {
            self::$flags['tenants'][$tenantId][$feature] = false;
        }
    }

    /**
     * Check if a global feature is enabled
     */
    public static function isGlobalEnabled(string $feature): bool
    {
        return self::$flags['global'][$feature] ?? false;
    }

    /**
     * Check if a feature is enabled for a specific tenant
     */
    public static function isEnabledForTenant(string $feature, string $tenantId): bool
    {
        // Check tenant-specific flag first, fall back to global
        return self::$flags['tenants'][$tenantId][$feature] ?? self::isGlobalEnabled($feature);
    }
}
