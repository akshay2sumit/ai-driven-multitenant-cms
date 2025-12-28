<?php

namespace App\Filters;

use App\Tenant\Context\TenantContext;
use App\Tenant\Resolution\TenantResolver;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Tenant Filter
 * 
 * Integrates tenant resolution into the CodeIgniter 4 request lifecycle.
 * 
 * Responsibilities:
 * - Executes tenant resolution early in the request lifecycle
 * - Sets the tenant context for the duration of the request
 * - Cleans up tenant context after request completion
 * 
 * Non-Responsibilities:
 * - Does NOT implement tenant resolution logic
 * - Does NOT handle tenant validation
 * - Does NOT manage tenant data or persistence
 * 
 * @see ADR-002: System Architecture Baseline
 * @see ADR-003: Tenant Resolution Strategy
 */
class TenantFilter implements FilterInterface
{
    /**
     * Tenant resolver instance
     * 
     * @var TenantResolver
     */
    private $resolver;

    public function __construct()
    {
        $this->resolver = new TenantResolver();
    }

    /**
     * Run before any controller execution
     * 
     * This method is called early in the request lifecycle, after routing but before
     * any controller is instantiated. It resolves the tenant from the request path
     * and sets it in the tenant context.
     *
     * @param RequestInterface $request The incoming request
     * @param array|null $arguments Optional filter arguments
     * @return RequestInterface
     * 
     * @see ADR-003: Tenant Resolution Strategy
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Resolve tenant from request path (e.g., /t/acme/dashboard → 'acme')
        $tenantId = $this->resolver->resolveFromRequest($request);
        
        // Store tenant in request-scoped context
        TenantContext::set($tenantId);

        // Continue to the next filter/controller
        return $request;
    }

    /**
     * Run after controller execution
     * 
     * Cleans up the tenant context after the request has been processed.
     * This ensures no tenant context leaks between requests.
     *
     * @param RequestInterface $request The request object
     * @param ResponseInterface $response The response object
     * @param array|null $arguments Optional filter arguments
     * @return ResponseInterface
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Clear tenant context to prevent leaks
        TenantContext::set(null);
        
        return $response;
    }
}
