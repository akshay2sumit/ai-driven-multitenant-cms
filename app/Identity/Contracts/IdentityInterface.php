<?php

namespace App\Identity\Contracts;

/**
 * Identity Interface
 * 
 * Contract for identity entities in the AIBOS Multi-Tenant CMS.
 * 
 * PURPOSE: Define stable, verifiable identity representation
 * PHASE: Execution Phase 3 — Authentication & Identity Foundations
 * 
 * NOTE: This interface defines identity properties only. No authentication
 * logic, authorization, or session management is allowed in Phase 3. This
 * interface exists only to establish the identity foundation for future phases.
 * 
 * SECURITY: Identity is distinct from roles and capabilities.
 * Identity establishment MUST be separate from permission assignment.
 * 
 * @package App\Identity\Contracts
 */
interface IdentityInterface
{
    /**
     * Get unique identity identifier
     * 
     * NOTE: Returns system-unique identifier for the identity.
     * This identifier is stable across sessions and operations.
     * 
     * @return string|int
     */
    public function getIdentityId();
    
    /**
     * Get identity type
     * 
     * NOTE: Returns the type classification of identity.
     * Must be one of: human, system, service, ai_operator
     * 
     * @return string
     */
    public function getIdentityType(): string;
    
    /**
     * Get tenant binding
     * 
     * NOTE: Returns the tenant identifier this identity is bound to.
     * Identity MUST be bound to exactly one tenant context.
     * 
     * @return string|null
     */
    public function getTenantIdentifier(): ?string;
    
    /**
     * Check if identity is active
     * 
     * NOTE: Returns whether identity is currently active.
     * Inactive identities cannot be used for authentication.
     * 
     * @return bool
     */
    public function isActive(): bool;
    
    /**
     * Check if identity is verifiable
     * 
     * NOTE: Returns whether identity can be verified.
     * Verifiable identities emit evidence of their existence.
     * 
     * @return bool
     */
    public function isVerifiable(): bool;
    
    /**
     * Get identity evidence
     * 
     * NOTE: Returns verifiable evidence of identity existence.
     * Evidence is used for authentication in future phases.
     * 
     * @return array|null
     */
    public function getEvidence(): ?array;
    
    /**
     * Check if identity supports delegation
     * 
     * NOTE: Returns whether identity can be delegated to others.
     * AI operators require delegation; human identities may delegate.
     * 
     * @return bool
     */
    public function supportsDelegation(): bool;
    
    /**
     * Get delegation chain
     * 
     * NOTE: Returns delegation chain if identity is delegated.
     * Used for AI operator traceability to delegating identity.
     * 
     * @return array|null
     */
    public function getDelegationChain(): ?array;
    
    /**
     * Validate identity structure
     * 
     * NOTE: Validates identity meets structural requirements.
     * Ensures tenant binding and required properties exist.
     * 
     * @return bool
     */
    public function validate(): bool;
    
    /**
     * Get identity metadata
     * 
     * NOTE: Returns additional identity metadata.
     * Used for audit trails and operational requirements.
     * 
     * @return array
     */
    public function getMetadata(): array;
}
