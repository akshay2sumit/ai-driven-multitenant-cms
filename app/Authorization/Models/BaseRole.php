<?php

namespace App\Authorization\Models;

use App\Authorization\Contracts\RoleInterface;

/**
 * Base Role Model
 * 
 * Base implementation for role entities in the AIBOS Multi-Tenant CMS.
 * 
 * PURPOSE: Provide foundation for role implementations
 * PHASE: Execution Phase 4 — Authorization & Access Control Foundations
 * 
 * NOTE: This model provides basic role functionality only. No actual role
 * assignment, management, or permission aggregation is allowed in Phase 4.
 * This model exists only to establish the role foundation for future phases.
 * 
 * SECURITY: Roles are tenant-scoped capability bundles with no authority of their own.
 * Roles never bypass capabilities, context rules, or tenant isolation.
 * Roles are data, not logic.
 * 
 * @package App\Authorization\Models
 */
abstract class BaseRole implements RoleInterface
{
    /**
     * Role identifier
     * 
     * NOTE: Unique role identifier.
     * Used for role lookup and assignment.
     * 
     * @var string|int
     */
    protected $roleId;
    
    /**
     * Role name
     * 
     * NOTE: The human-readable role name.
     * Role names are tenant-scoped, not global.
     * 
     * @var string
     */
    protected $roleName;
    
    /**
     * Role description
     * 
     * NOTE: The role description.
     * Used for role documentation and user communication.
     * 
     * @var string|null
     */
    protected $roleDescription;
    
    /**
     * Tenant scope
     * 
     * NOTE: The tenant this role belongs to.
     * Every role belongs to exactly one tenant.
     * 
     * @var string
     */
    protected $tenantScope;
    
    /**
     * Role capabilities
     * 
     * NOTE: The list of capabilities this role contains.
     * Roles contain only capabilities, no conditions or logic.
     * 
     * @var array
     */
    protected $capabilities;
    
    /**
     * Role type
     * 
     * NOTE: The type classification of role.
     * Must be one of: functional, administrative, system
     * 
     * @var string
     */
    protected $roleType;
    
    /**
     * Role status
     * 
     * NOTE: Current status of the role.
     * Active roles can be assigned and used for authorization.
     * 
     * @var bool
     */
    protected $isActive;
    
    /**
     * Role metadata
     * 
     * NOTE: Additional role metadata.
     * Used for audit trails and operational requirements.
     * 
     * @var array
     */
    protected $metadata;
    
    /**
     * Base Role constructor
     * 
     * NOTE: Protected constructor to enforce factory pattern.
     * Role creation must go through specific role classes.
     * 
     * @param string|int $roleId
     * @param string $roleName
     * @param string $tenantScope
     * @param string $roleType
     */
    protected function __construct(
        $roleId,
        string $roleName,
        string $tenantScope,
        string $roleType
    ) {
        $this->roleId = $roleId;
        $this->roleName = $roleName;
        $this->tenantScope = $tenantScope;
        $this->roleType = $roleType;
        $this->roleDescription = null;
        $this->capabilities = [];
        $this->isActive = true;
        $this->metadata = [];
    }
    
    /**
     * Get role identifier
     * 
     * NOTE: Returns unique role identifier.
     * Used for role lookup and assignment.
     * 
     * @return string|int
     */
    public function getRoleId()
    {
        return $this->roleId;
    }
    
    /**
     * Get role name
     * 
     * NOTE: Returns the human-readable role name.
     * Role names are tenant-scoped, not global.
     * 
     * @return string
     */
    public function getRoleName(): string
    {
        return $this->roleName;
    }
    
    /**
     * Get role description
     * 
     * NOTE: Returns the role description.
     * Used for role documentation and user communication.
     * 
     * @return string|null
     */
    public function getRoleDescription(): ?string
    {
        return $this->roleDescription;
    }
    
    /**
     * Get tenant scope
     * 
     * NOTE: Returns the tenant this role belongs to.
     * Every role belongs to exactly one tenant.
     * 
     * @return string
     */
    public function getTenantScope(): string
    {
        return $this->tenantScope;
    }
    
    /**
     * Get role capabilities
     * 
     * NOTE: Returns the list of capabilities this role contains.
     * Roles contain only capabilities, no conditions or logic.
     * 
     * @return array
     */
    public function getCapabilities(): array
    {
        return $this->capabilities;
    }
    
    /**
     * Check if role has capability
     * 
     * NOTE: Returns whether role contains specific capability.
     * Used for permission evaluation and role composition.
     * 
     * @param string $capability
     * @return bool
     */
    public function hasCapability(string $capability): bool
    {
        return in_array($capability, $this->capabilities);
    }
    
    /**
     * Get role type
     * 
     * NOTE: Returns the type classification of role.
     * Must be one of: functional, administrative, system
     * 
     * @return string
     */
    public function getRoleType(): string
    {
        return $this->roleType;
    }
    
    /**
     * Check if role is active
     * 
     * NOTE: Returns whether role is currently active.
     * Inactive roles cannot be assigned or used for authorization.
     * 
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }
    
    /**
     * Get role metadata
     * 
     * NOTE: Returns additional role metadata.
     * Used for audit trails and operational requirements.
     * 
     * @return array
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }
    
    /**
     * Check if role is tenant-scoped
     * 
     * NOTE: Returns whether role is properly tenant-scoped.
     * No global roles or cross-tenant sharing allowed.
     * 
     * @return bool
     */
    public function isTenantScoped(): bool
    {
        return $this->tenantScope !== null && $this->tenantScope !== '';
    }
    
    /**
     * Check if role follows least-privilege
     * 
     * NOTE: Returns whether role follows least-privilege principle.
     * Roles should contain minimum necessary capabilities.
     * 
     * @return bool
     */
    public function isLeastPrivilege(): bool
    {
        // Phase 4: Basic least-privilege check
        // Future phases will add capability analysis
        return count($this->capabilities) <= 10; // Arbitrary threshold for Phase 4
    }
    
    /**
     * Get role assignment requirements
     * 
     * NOTE: Returns requirements for role assignment.
     * Used for role assignment validation and audit.
     * 
     * @return array
     */
    public function getAssignmentRequirements(): array
    {
        return [
            'assigner_must_have_role' => false,
            'assigner_must_have_capabilities' => true,
            'tenant_match_required' => true,
            'audit_required' => true,
            'explicit_assignment_only' => true
        ];
    }
    
    /**
     * Validate role structure
     * 
     * NOTE: Validates role meets structural requirements.
     * Ensures required properties and security constraints.
     * 
     * @return bool
     */
    public function validate(): bool
    {
        // Phase 4: Basic structural validation
        // Future phases will add database validation
        
        // Role ID must exist
        if ($this->roleId === null || $this->roleId === '') {
            return false;
        }
        
        // Role name must exist
        if ($this->roleName === null || $this->roleName === '') {
            return false;
        }
        
        // Tenant scope must exist
        if (!$this->isTenantScoped()) {
            return false;
        }
        
        // Role type must be valid
        $validRoleTypes = ['functional', 'administrative', 'system'];
        if (!in_array($this->roleType, $validRoleTypes)) {
            return false;
        }
        
        // Capabilities must be array
        if (!is_array($this->capabilities)) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Check if role allows escalation
     * 
     * NOTE: Returns whether role could allow privilege escalation.
     * Used for security validation and role composition.
     * 
     * @return bool
     */
    public function allowsEscalation(): bool
    {
        // Phase 4: Basic escalation check
        // Future phases will add capability escalation analysis
        $escalationCapabilities = ['role.assign', 'role.create', 'role.delete', 'tenant.admin'];
        
        foreach ($escalationCapabilities as $capability) {
            if ($this->hasCapability($capability)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Get role audit information
     * 
     * NOTE: Returns audit information for the role.
     * Used for compliance and security monitoring.
     * 
     * @return array
     */
    public function getAuditInfo(): array
    {
        return [
            'role_id' => $this->roleId,
            'role_name' => $this->roleName,
            'tenant_scope' => $this->tenantScope,
            'role_type' => $this->roleType,
            'capability_count' => count($this->capabilities),
            'is_active' => $this->isActive,
            'allows_escalation' => $this->allowsEscalation(),
            'is_least_privilege' => $this->isLeastPrivilege(),
            'is_tenant_scoped' => $this->isTenantScoped()
        ];
    }
    
    /**
     * Set role description
     * 
     * NOTE: Sets the role description.
     * Used for role documentation and user communication.
     * 
     * @param string|null $roleDescription
     * @return void
     */
    protected function setRoleDescription(?string $roleDescription): void
    {
        $this->roleDescription = $roleDescription;
    }
    
    /**
     * Add capability
     * 
     * NOTE: Adds a capability to the role.
     * Used in future phases for role composition.
     * 
     * @param string $capability
     * @return void
     */
    protected function addCapability(string $capability): void
    {
        if (!in_array($capability, $this->capabilities)) {
            $this->capabilities[] = $capability;
        }
    }
    
    /**
     * Remove capability
     * 
     * NOTE: Removes a capability from the role.
     * Used in future phases for role modification.
     * 
     * @param string $capability
     * @return void
     */
    protected function removeCapability(string $capability): void
    {
        $key = array_search($capability, $this->capabilities);
        if ($key !== false) {
            unset($this->capabilities[$key]);
            $this->capabilities = array_values($this->capabilities); // Re-index
        }
    }
    
    /**
     * Set role active status
     * 
     * NOTE: Sets the active status of the role.
     * Used in future phases for role lifecycle management.
     * 
     * @param bool $isActive
     * @return void
     */
    protected function setActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }
    
    /**
     * Add metadata
     * 
     * NOTE: Adds metadata to the role.
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
