<?php

namespace App\Features;

/**
 * Feature Flags
 * 
 * Provides a simple, in-memory feature flag system.
 * 
 * Note: This is an INERT implementation. All flags default to false.
 * In a production environment, this would be backed by a proper feature flag service.
 */
final class FeatureFlag
{
    private static array $globalFlags = [];
    private static array $tenantFlags = [];

    /**
     * Check if a global feature is enabled
     */
    public static function isGlobalEnabled(string $feature): bool
    {
        return self::$globalFlags[$feature] ?? false;
    }

    /**
     * Enable a global feature flag
     */
    public static function enableGlobal(string $feature): void
    {
        self::$globalFlags[$feature] = true;
    }

    /**
     * Disable a global feature flag
     */
    public static function disableGlobal(string $feature): void
    {
        self::$globalFlags[$feature] = false;
    }

    /**
     * Check if a tenant-specific feature is enabled
     */
    public static function isTenantEnabled(string $tenantId, string $feature): bool
    {
        return self::$tenantFlags[$tenantId][$feature] ?? false;
    }

    /**
     * Enable a tenant-specific feature flag
     */
    public static function enableForTenant(string $tenantId, string $feature): void
    {
        self::$tenantFlags[$tenantId][$feature] = true;
    }

    /**
     * Disable a tenant-specific feature flag
     */
    public static function disableForTenant(string $tenantId, string $feature): void
    {
        self::$tenantFlags[$tenantId][$feature] = false;
    }
}