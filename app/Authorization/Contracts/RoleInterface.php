<?php

namespace App\Authorization\Contracts;

/**
 * Role Interface
 * 
 * Contract for role entities in the AIBOS Multi-Tenant CMS.
 * 
 * PURPOSE: Define role as tenant-scoped capability bundles
 * PHASE: Execution Phase 4 — Authorization & Access Control Foundations
 * 
 * NOTE: This interface defines role structure only. No actual role assignment,
 * management, or permission aggregation is allowed in Phase 4. This interface
 * exists only to establish the role foundation for future phases.
 * 
 * SECURITY: Roles are tenant-scoped capability bundles with no authority of their own.
 * Roles never bypass capabilities, context rules, or tenant isolation.
 * Roles are data, not logic.
 * 
 * @package App\Authorization\Contracts
 */
interface RoleInterface
{
    /**
     * Get role identifier
     * 
     * NOTE: Returns unique role identifier.
     * Used for role lookup and assignment.
     * 
     * @return string|int
     */
    public function getRoleId();
    
    /**
     * Get role name
     * 
     * NOTE: Returns the human-readable role name.
     * Role names are tenant-scoped, not global.
     * 
     * @return string
     */
    public function getRoleName(): string;
    
    /**
     * Get role description
     * 
     * NOTE: Returns the role description.
     * Used for role documentation and user communication.
     * 
     * @return string|null
     */
    public function getRoleDescription(): ?string;
    
    /**
     * Get tenant scope
     * 
     * NOTE: Returns the tenant this role belongs to.
     * Every role belongs to exactly one tenant.
     * 
     * @return string
     */
    public function getTenantScope(): string;
    
    /**
     * Get role capabilities
     * 
     * NOTE: Returns the list of capabilities this role contains.
     * Roles contain only capabilities, no conditions or logic.
     * 
     * @return array
     */
    public function getCapabilities(): array;
    
    /**
     * Check if role has capability
     * 
     * NOTE: Returns whether role contains specific capability.
     * Used for permission evaluation and role composition.
     * 
     * @param string $capability
     * @return bool
     */
    public function hasCapability(string $capability): bool;
    
    /**
     * Get role type
     * 
     * NOTE: Returns the type classification of role.
     * Must be one of: functional, administrative, system
     * 
     * @return string
     */
    public function getRoleType(): string;
    
    /**
     * Check if role is active
     * 
     * NOTE: Returns whether role is currently active.
     * Inactive roles cannot be assigned or used for authorization.
     * 
     * @return bool
     */
    public function isActive(): bool;
    
    /**
     * Get role metadata
     * 
     * NOTE: Returns additional role metadata.
     * Used for audit trails and operational requirements.
     * 
     * @return array
     */
    public function getMetadata(): array;
    
    /**
     * Check if role is tenant-scoped
     * 
     * NOTE: Returns whether role is properly tenant-scoped.
     * No global roles or cross-tenant sharing allowed.
     * 
     * @return bool
     */
    public function isTenantScoped(): bool;
    
    /**
     * Check if role follows least-privilege
     * 
     * NOTE: Returns whether role follows least-privilege principle.
     * Roles should contain minimum necessary capabilities.
     * 
     * @return bool
     */
    public function isLeastPrivilege(): bool;
    
    /**
     * Get role assignment requirements
     * 
     * NOTE: Returns requirements for role assignment.
     * Used for role assignment validation and audit.
     * 
     * @return array
     */
    public function getAssignmentRequirements(): array;
    
    /**
     * Validate role structure
     * 
     * NOTE: Validates role meets structural requirements.
     * Ensures required properties and security constraints.
     * 
     * @return bool
     */
    public function validate(): bool;
    
    /**
     * Check if role allows escalation
     * 
     * NOTE: Returns whether role could allow privilege escalation.
     * Used for security validation and role composition.
     * 
     * @return bool
     */
    public function allowsEscalation(): bool;
    
    /**
     * Get role audit information
     * 
     * NOTE: Returns audit information for the role.
     * Used for compliance and security monitoring.
     * 
     * @return array
     */
    public function getAuditInfo(): array;
}
