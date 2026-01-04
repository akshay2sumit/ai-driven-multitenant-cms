<?php

namespace App\Tenant\Context;

/**
 * Tenant Context
 * 
 * Read-only tenant context object for the AIBOS Multi-Tenant CMS.
 * 
 * PURPOSE: Provide immutable tenant information for request lifecycle
 * PHASE: Execution Phase 2 — Database Foundations & Tenant Resolution
 * 
 * NOTE: This context is intentionally read-only and minimal.
 * No business logic, permissions, or authentication coupling is allowed
 * in Phase 2. This context exists only to establish tenant identification
 * foundation for future phases.
 * 
 * SECURITY: Fail-closed by design - invalid or missing tenants result
 * in null context, never partial or assumed data.
 * 
 * @package App\Tenant\Context
 */
class TenantContext
{
    /**
     * Tenant identifier
     * 
     * NOTE: Read-only property set only during construction.
     * Tenant identifier validation happens during resolution, not here.
     * 
     * @var string|null
     */
    private ?string $tenantIdentifier = null;
    
    /**
     * Tenant ID (database primary key)
     * 
     * NOTE: Read-only property set only during construction.
     * This will be populated in future phases when database access is allowed.
     * 
     * @var int|null
     */
    private ?int $tenantId = null;
    
    /**
     * Tenant status
     * 
     * NOTE: Read-only property set only during construction.
     * This will be populated in future phases when database access is allowed.
     * 
     * @var string|null
     */
    private ?string $status = null;
    
    /**
     * Tenant Context constructor
     * 
     * NOTE: Private constructor to enforce factory pattern.
     * Context creation must go through TenantResolver in Phase 2+.
     * 
     * @param string|null $tenantIdentifier
     * @param int|null $tenantId
     * @param string|null $status
     */
    private function __construct(
        ?string $tenantIdentifier,
        ?int $tenantId = null,
        ?string $status = null
    ) {
        $this->tenantIdentifier = $tenantIdentifier;
        $this->tenantId = $tenantId;
        $this->status = $status;
    }
    
    /**
     * Create valid tenant context
     * 
     * NOTE: Factory method for creating valid tenant context.
     * This method will be used by TenantResolver in future phases.
     * 
     * @param string $tenantIdentifier
     * @param int $tenantId
     * @param string $status
     * @return self
     */
    public static function create(
        string $tenantIdentifier,
        int $tenantId,
        string $status
    ): self {
        return new self($tenantIdentifier, $tenantId, $status);
    }
    
    /**
     * Create null tenant context (fail-closed)
     * 
     * NOTE: Factory method for creating null context when tenant is invalid.
     * This implements the fail-closed security pattern.
     * 
     * @return self
     */
    public static function createNull(): self
    {
        return new self(null);
    }
    
    /**
     * Get tenant identifier
     * 
     * NOTE: Read-only getter. Returns null for invalid tenants.
     * 
     * @return string|null
     */
    public function getTenantIdentifier(): ?string
    {
        return $this->tenantIdentifier;
    }
    
    /**
     * Get tenant ID
     * 
     * NOTE: Read-only getter. Returns null until Phase 3+ database access.
     * 
     * @return int|null
     */
    public function getTenantId(): ?int
    {
        return $this->tenantId;
    }
    
    /**
     * Get tenant status
     * 
     * NOTE: Read-only getter. Returns null until Phase 3+ database access.
     * 
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }
    
    /**
     * Check if tenant context is valid
     * 
     * NOTE: Fail-closed validation. Context is valid only if tenant identifier exists.
     * This method will be enhanced in future phases with database validation.
     * 
     * @return bool
     */
    public function isValid(): bool
    {
        // Phase 2: Basic validation - tenant identifier must exist
        // Future phases will add database validation
        return $this->tenantIdentifier !== null;
    }
    
    /**
     * Check if tenant is active
     * 
     * NOTE: Always false in Phase 2 since database access is not allowed.
     * This method will be implemented in future phases.
     * 
     * @return bool
     */
    public function isActive(): bool
    {
        // Phase 2: No database access - assume inactive
        // Future phases will check actual tenant status
        return false;
    }
}
