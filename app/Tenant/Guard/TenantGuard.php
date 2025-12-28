<?php

namespace App\Tenant\Guard;

use App\Tenant\Context\TenantContext;
use RuntimeException;

/**
 * Tenant Guard
 * 
 * Provides explicit assertions for tenant context.
 * 
 * Responsibilities:
 * - Provides methods to assert tenant context is present
 * - Throws specific exceptions when assertions fail
 * 
 * Non-Responsibilities:
 * - Does NOT handle tenant resolution
 * - Does NOT modify tenant context
 */
final class TenantGuard
{
    /**
     * Ensure tenant context is available
     * 
     * @throws RuntimeException If no tenant context is available
     */
    public static function ensureTenantContext(): void
    {
        if (!TenantContext::has()) {
            throw new RuntimeException(
                'Tenant context is required but not available. ' .
                'Ensure you are within a valid request context and tenant resolution has occurred.'
            );
        }
    }

    /**
     * Ensure the given tenant ID matches the current tenant context
     * 
     * @param string $tenantId The tenant ID to verify
     * @throws RuntimeException If tenant context is missing or doesn't match
     */
    public static function ensureTenantMatch(string $tenantId): void
    {
        self::ensureTenantContext();
        
        if (TenantContext::require() !== $tenantId) {
            throw new RuntimeException(
                'Operation not permitted for the current tenant context.'
            );
        }
    }
}
