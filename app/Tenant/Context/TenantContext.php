<?php

namespace App\Tenant\Context;

use CodeIgniter\HTTP\RequestInterface;
use RuntimeException;

/**
 * Tenant Context
 * 
 * Provides request-scoped, type-safe access to the current tenant identifier.
 * 
 * Responsibilities:
 * - Provides controlled access to the current tenant identifier
 * - Ensures tenant context is only accessed when available
 * - Maintains thread safety through static properties
 * 
 * Non-Responsibilities:
 * - Does NOT validate tenant existence
 * - Does NOT handle tenant resolution
 * - Does NOT manage tenant data or persistence
 * 
 * @see ADR-002: System Architecture Baseline
 * @see ADR-003: Tenant Resolution Strategy
 * 
 * @method static string require() Get the current tenant identifier or throw an exception
 * @method static string|null get() Get the current tenant identifier if available
 * @method static bool has() Check if a tenant context is available
 * @method static void set(?string $tenantId) Set the current tenant identifier
 * @method static void ensure() Ensure a tenant context is available
 */
final class TenantContext
{
    private const ERROR_NO_TENANT = 'No tenant context is currently set. Ensure you are within a valid request context and tenant resolution has occurred.';
    private const ERROR_INVALID_ACCESS = 'Tenant context accessed outside of request scope or after request completion.';

    /**
     * @var string|null The current tenant identifier
     */
    private static ?string $currentTenant = null;

    /**
     * @var bool Whether the current request has been initialized
     */
    private static bool $initialized = false;

    /**
     * Prevent instantiation
     */
    private function __construct() {}

    /**
     * Get the current tenant identifier or throw an exception if not set
     * 
     * @return string The current tenant identifier
     * @throws RuntimeException If no tenant context is available
     */
    public static function require(): string
    {
        self::ensureInitialized();
        
        if (self::$currentTenant === null) {
            throw new RuntimeException(self::ERROR_NO_TENANT);
        }
        
        return self::$currentTenant;
    }

    /**
     * Get the current tenant identifier if available
     * 
     * @return string|null The current tenant identifier or null if not set
     */
    public static function get(): ?string
    {
        self::ensureInitialized();
        return self::$currentTenant;
    }

    /**
     * Check if a tenant context is available
     */
    public static function has(): bool
    {
        self::ensureInitialized();
        return self::$currentTenant !== null;
    }

    /**
     * Set the current tenant identifier
     * 
     * @internal This method should only be called by the tenant resolution system
     */
    public static function set(?string $tenantId): void
    {
        self::$initialized = true;
        self::$currentTenant = $tenantId;
    }

    /**
     * Ensure a tenant context is available
     * 
     * @throws RuntimeException If no tenant context is available
     */
    public static function ensure(): void
    {
        if (!self::has()) {
            throw new RuntimeException(self::ERROR_NO_TENANT);
        }
    }

    /**
     * Ensure the context has been properly initialized
     * 
     * @throws RuntimeException If accessed outside of request scope
     */
    private static function ensureInitialized(): void
    {
        if (!self::$initialized) {
            throw new RuntimeException(self::ERROR_INVALID_ACCESS);
        }
    }
}
