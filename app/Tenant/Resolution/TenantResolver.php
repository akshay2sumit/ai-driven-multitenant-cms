<?php

namespace App\Tenant\Resolution;

use App\Tenant\Context\TenantContext;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\Router\RouteCollectionInterface;

/**
 * Tenant Resolver
 * 
 * Implements path-based tenant resolution as specified in ADR-003.
 * 
 * Responsibilities:
 * - Extracts tenant identifier from request path (/t/{tenant_identifier}/...)
 * - Normalizes tenant identifiers according to system standards
 * - Provides a clean interface for tenant resolution
 * 
 * Non-Responsibilities:
 * - Does NOT validate tenant existence
 * - Does NOT handle tenant context storage
 * - Does NOT perform authentication/authorization
 * 
 * @see ADR-002: System Architecture Baseline
 * @see ADR-003: Tenant Resolution Strategy
 */
class TenantResolver
{
    /**
     * The URL segment index where the tenant identifier is expected
     * 
     * URL Pattern: /t/{tenant_identifier}/...
     * Example: /t/acme/dashboard → tenant_identifier = 'acme'
     */
    private const TENANT_SEGMENT = 1;

    /**
     * Resolve the tenant from the request path
     *
     * Extracts the tenant identifier from the URL path following the pattern:
     * /t/{tenant_identifier}/...
     *
     * @param RequestInterface $request The incoming HTTP request
     * @return string|null The normalized tenant identifier or null if not found
     * 
     * @see ADR-003: Tenant Resolution Strategy
     */
    public function resolveFromRequest(RequestInterface $request): ?string
    {
        $uri = $request->getUri();
        $segments = explode('/', trim($uri->getPath(), '/'));

        // Check if the first segment is 't' and a tenant identifier follows
        if (isset($segments[0]) && $segments[0] === 't' && isset($segments[1])) {
            return $this->normalizeTenantIdentifier($segments[1]);
        }

        return null;
    }

    /**
     * Normalize the tenant identifier
     * 
     * Applies standard normalization rules to ensure consistent tenant identifiers:
     * - Converts to lowercase
     * - Removes any invalid characters (only alphanumeric, hyphen, underscore allowed)
     * 
     * @param string $identifier The raw tenant identifier
     * @return string Normalized tenant identifier
     */
    private function normalizeTenantIdentifier(string $identifier): string
    {
        // Basic normalization - convert to lowercase and remove any invalid characters
        return strtolower(preg_replace('/[^a-zA-Z0-9\-_]/', '', $identifier));
    }
}
