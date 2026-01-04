<?php

namespace App\Identity\Contracts;

/**
 * Credential Interface
 * 
 * Contract for credential abstractions in the AIBOS Multi-Tenant CMS.
 * 
 * PURPOSE: Define credential handling abstractions
 * PHASE: Execution Phase 3 — Authentication & Identity Foundations
 * 
 * NOTE: This interface defines credential properties only. No authentication
 * logic, session management, or validation is allowed in Phase 3. This
 * interface exists only to establish the credential foundation for future phases.
 * 
 * SECURITY: Credentials are separate from identity and authorization.
 * Credential handling MUST be secure and tamper-resistant.
 * 
 * @package App\Identity\Contracts
 */
interface CredentialInterface
{
    /**
     * Get credential type
     * 
     * NOTE: Returns the type of credential.
     * Must be one of: password, token, certificate, api_key
     * 
     * @return string
     */
    public function getCredentialType(): string;
    
    /**
     * Get credential identifier
     * 
     * NOTE: Returns unique identifier for the credential.
     * Used for credential lookup and management.
     * 
     * @return string
     */
    public function getCredentialId(): string;
    
    /**
     * Get associated identity ID
     * 
     * NOTE: Returns the identity this credential belongs to.
     * Credentials MUST be bound to exactly one identity.
     * 
     * @return string|int
     */
    public function getIdentityId();
    
    /**
     * Check if credential is active
     * 
     * NOTE: Returns whether credential is currently active.
     * Inactive credentials cannot be used for authentication.
     * 
     * @return bool
     */
    public function isActive(): bool;
    
    /**
     * Check if credential is expired
     * 
     * NOTE: Returns whether credential has expired.
     * Expired credentials must be rejected immediately.
     * 
     * @return bool
     */
    public function isExpired(): bool;
    
    /**
     * Get credential expiration time
     * 
     * NOTE: Returns expiration timestamp if applicable.
     * Null for non-expiring credentials like passwords.
     * 
     * @return \DateTime|null
     */
    public function getExpirationTime(): ?\DateTime;
    
    /**
     * Check if credential supports revocation
     * 
     * NOTE: Returns whether credential can be revoked.
     * Some credentials like certificates support revocation.
     * 
     * @return bool
     */
    public function supportsRevocation(): bool;
    
    /**
     * Check if credential is revoked
     * 
     * NOTE: Returns whether credential has been revoked.
     * Revoked credentials must be rejected immediately.
     * 
     * @return bool
     */
    public function isRevoked(): bool;
    
    /**
     * Get revocation reason
     * 
     * NOTE: Returns reason for credential revocation.
     * Used for audit trails and user communication.
     * 
     * @return string|null
     */
    public function getRevocationReason(): ?string;
    
    /**
     * Get credential metadata
     * 
     * NOTE: Returns additional credential metadata.
     * Used for security policies and operational requirements.
     * 
     * @return array
     */
    public function getMetadata(): array;
    
    /**
     * Validate credential structure
     * 
     * NOTE: Validates credential meets structural requirements.
     * Ensures required properties and security constraints.
     * 
     * @return bool
     */
    public function validate(): bool;
}
