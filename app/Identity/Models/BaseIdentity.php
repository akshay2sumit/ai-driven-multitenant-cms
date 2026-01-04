<?php

namespace App\Identity\Models;

use App\Identity\Contracts\IdentityInterface;

/**
 * Base Identity Model
 * 
 * Base implementation for identity entities in the AIBOS Multi-Tenant CMS.
 * 
 * PURPOSE: Provide foundation for identity implementations
 * PHASE: Execution Phase 3 — Authentication & Identity Foundations
 * 
 * NOTE: This model provides basic identity functionality only. No authentication
 * logic, authorization, or session management is allowed in Phase 3. This
 * model exists only to establish the identity foundation for future phases.
 * 
 * SECURITY: Identity is distinct from roles and capabilities.
 * Identity establishment MUST be separate from permission assignment.
 * 
 * @package App\Identity\Models
 */
abstract class BaseIdentity implements IdentityInterface
{
    /**
     * Identity identifier
     * 
     * NOTE: System-unique identifier for the identity.
     * This identifier is stable across sessions and operations.
     * 
     * @var string|int
     */
    protected $identityId;
    
    /**
     * Identity type
     * 
     * NOTE: Type classification of identity.
     * Must be one of: human, system, service, ai_operator
     * 
     * @var string
     */
    protected $identityType;
    
    /**
     * Tenant binding
     * 
     * NOTE: Tenant identifier this identity is bound to.
     * Identity MUST be bound to exactly one tenant context.
     * 
     * @var string|null
     */
    protected $tenantIdentifier;
    
    /**
     * Identity status
     * 
     * NOTE: Current status of the identity.
     * Active identities can be used for authentication.
     * 
     * @var bool
     */
    protected $isActive;
    
    /**
     * Identity evidence
     * 
     * NOTE: Verifiable evidence of identity existence.
     * Used for authentication in future phases.
     * 
     * @var array|null
     */
    protected $evidence;
    
    /**
     * Delegation support
     * 
     * NOTE: Whether identity supports delegation.
     * AI operators require delegation; human identities may delegate.
     * 
     * @var bool
     */
    protected $supportsDelegation;
    
    /**
     * Delegation chain
     * 
     * NOTE: Delegation chain if identity is delegated.
     * Used for AI operator traceability.
     * 
     * @var array|null
     */
    protected $delegationChain;
    
    /**
     * Identity metadata
     * 
     * NOTE: Additional identity metadata.
     * Used for audit trails and operational requirements.
     * 
     * @var array
     */
    protected $metadata;
    
    /**
     * Base Identity constructor
     * 
     * NOTE: Protected constructor to enforce factory pattern.
     * Identity creation must go through specific identity classes.
     * 
     * @param string|int $identityId
     * @param string $identityType
     * @param string|null $tenantIdentifier
     */
    protected function __construct(
        $identityId,
        string $identityType,
        ?string $tenantIdentifier = null
    ) {
        $this->identityId = $identityId;
        $this->identityType = $identityType;
        $this->tenantIdentifier = $tenantIdentifier;
        $this->isActive = false;
        $this->evidence = null;
        $this->supportsDelegation = false;
        $this->delegationChain = null;
        $this->metadata = [];
    }
    
    /**
     * Get unique identity identifier
     * 
     * NOTE: Returns system-unique identifier for the identity.
     * This identifier is stable across sessions and operations.
     * 
     * @return string|int
     */
    public function getIdentityId()
    {
        return $this->identityId;
    }
    
    /**
     * Get identity type
     * 
     * NOTE: Returns the type classification of identity.
     * Must be one of: human, system, service, ai_operator
     * 
     * @return string
     */
    public function getIdentityType(): string
    {
        return $this->identityType;
    }
    
    /**
     * Get tenant binding
     * 
     * NOTE: Returns the tenant identifier this identity is bound to.
     * Identity MUST be bound to exactly one tenant context.
     * 
     * @return string|null
     */
    public function getTenantIdentifier(): ?string
    {
        return $this->tenantIdentifier;
    }
    
    /**
     * Check if identity is active
     * 
     * NOTE: Returns whether identity is currently active.
     * Inactive identities cannot be used for authentication.
     * 
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }
    
    /**
     * Check if identity is verifiable
     * 
     * NOTE: Returns whether identity can be verified.
     * Verifiable identities emit evidence of their existence.
     * 
     * @return bool
     */
    public function isVerifiable(): bool
    {
        return $this->evidence !== null && !empty($this->evidence);
    }
    
    /**
     * Get identity evidence
     * 
     * NOTE: Returns verifiable evidence of identity existence.
     * Evidence is used for authentication in future phases.
     * 
     * @return array|null
     */
    public function getEvidence(): ?array
    {
        return $this->evidence;
    }
    
    /**
     * Check if identity supports delegation
     * 
     * NOTE: Returns whether identity can be delegated to others.
     * AI operators require delegation; human identities may delegate.
     * 
     * @return bool
     */
    public function supportsDelegation(): bool
    {
        return $this->supportsDelegation;
    }
    
    /**
     * Get delegation chain
     * 
     * NOTE: Returns delegation chain if identity is delegated.
     * Used for AI operator traceability to delegating identity.
     * 
     * @return array|null
     */
    public function getDelegationChain(): ?array
    {
        return $this->delegationChain;
    }
    
    /**
     * Validate identity structure
     * 
     * NOTE: Validates identity meets structural requirements.
     * Ensures tenant binding and required properties exist.
     * 
     * @return bool
     */
    public function validate(): bool
    {
        // Phase 3: Basic structural validation
        // Future phases will add database validation
        
        // Identity ID must exist
        if ($this->identityId === null || $this->identityId === '') {
            return false;
        }
        
        // Identity type must be valid
        $validTypes = ['human', 'system', 'service', 'ai_operator'];
        if (!in_array($this->identityType, $validTypes)) {
            return false;
        }
        
        // Tenant binding must exist for non-system identities
        if ($this->identityType !== 'system' && $this->tenantIdentifier === null) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Get identity metadata
     * 
     * NOTE: Returns additional identity metadata.
     * Used for audit trails and operational requirements.
     * 
     * @return array
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }
    
    /**
     * Set identity active status
     * 
     * NOTE: Sets the active status of the identity.
     * Used in future phases for identity lifecycle management.
     * 
     * @param bool $isActive
     * @return void
     */
    protected function setActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }
    
    /**
     * Set identity evidence
     * 
     * NOTE: Sets verifiable evidence for the identity.
     * Used in future phases for authentication setup.
     * 
     * @param array|null $evidence
     * @return void
     */
    protected function setEvidence(?array $evidence): void
    {
        $this->evidence = $evidence;
    }
    
    /**
     * Add metadata
     * 
     * NOTE: Adds metadata to the identity.
     * Used for audit trails and operational requirements.
     * 
     * @param string $key
     * @param mixed $value
     * @return void
     */
    protected function addMetadata(string $key, $value): void
    {
        $this->metadata[$key] = $value;
    }
}
