<?php

namespace App\Authorization\Models;

use App\Authorization\Contracts\PermissionInterface;

/**
 * Base Permission Model
 * 
 * Base implementation for permission entities in the AIBOS Multi-Tenant CMS.
 * 
 * PURPOSE: Provide foundation for permission implementations
 * PHASE: Execution Phase 4 — Authorization & Access Control Foundations
 * 
 * NOTE: This model provides basic permission functionality only. No actual
 * permission evaluation, assignment, or management is allowed in Phase 4.
 * This model exists only to establish the permission foundation for future phases.
 * 
 * SECURITY: Permission is a decision, not a property. Actors have capabilities.
 * System decides permission at request time. Decision is always contextual and state-aware.
 * 
 * @package App\Authorization\Models
 */
abstract class BasePermission implements PermissionInterface
{
    /**
     * Permission identifier
     * 
     * NOTE: Unique permission identifier.
     * Used for permission lookup and evaluation.
     * 
     * @var string
     */
    protected $permissionId;
    
    /**
     * Capability name
     * 
     * NOTE: The capability being requested.
     * No wildcard matching allowed - exact capability names only.
     * 
     * @var string
     */
    protected $capability;
    
    /**
     * Actor identity
     * 
     * NOTE: The actor requesting permission.
     * Actor must be authenticated and tenant-bound.
     * 
     * @var string|int
     */
    protected $actorIdentity;
    
    /**
     * Actor type
     * 
     * NOTE: The type of actor.
     * Must be one of: human, system, service, ai_operator
     * 
     * @var string
     */
    protected $actorType;
    
    /**
     * Execution context
     * 
     * NOTE: The execution context for permission.
     * Must be one of: authoring, runtime_public, system_background, governance
     * 
     * @var string
     */
    protected $executionContext;
    
    /**
     * Resource identifier
     * 
     * NOTE: The resource being accessed.
     * Resource must be tenant-scoped.
     * 
     * @var string|int|null
     */
    protected $resourceId;
    
    /**
     * Resource type
     * 
     * NOTE: The type of resource.
     * Examples: content, page, media, user, tenant
     * 
     * @var string|null
     */
    protected $resourceType;
    
    /**
     * Resource state
     * 
     * NOTE: The current state of resource.
     * Must be one of: draft, published, archived, locked
     * 
     * @var string|null
     */
    protected $resourceState;
    
    /**
     * Tenant scope
     * 
     * NOTE: The tenant scope for permission.
     * Actor tenant and resource tenant must match.
     * 
     * @var string
     */
    protected $tenantScope;
    
    /**
     * Permission decision
     * 
     * NOTE: The permission decision.
     * Must be one of: allow, deny
     * 
     * @var string
     */
    protected $decision;
    
    /**
     * Decision evidence
     * 
     * NOTE: Evidence supporting the decision.
     * Used for audit trails and compliance.
     * 
     * @var array
     */
    protected $evidence;
    
    /**
     * Decision timestamp
     * 
     * NOTE: When the permission decision was made.
     * Used for audit trails and temporal analysis.
     * 
     * @var \DateTime
     */
    protected $decisionTimestamp;
    
    /**
     * Permission metadata
     * 
     * NOTE: Additional permission metadata.
     * Used for audit trails and operational requirements.
     * 
     * @var array
     */
    protected $metadata;
    
    /**
     * Base Permission constructor
     * 
     * NOTE: Protected constructor to enforce factory pattern.
     * Permission creation must go through specific permission classes.
     * 
     * @param string $permissionId
     * @param string $capability
     * @param string|int $actorIdentity
     * @param string $actorType
     * @param string $executionContext
     * @param string $tenantScope
     */
    protected function __construct(
        string $permissionId,
        string $capability,
        $actorIdentity,
        string $actorType,
        string $executionContext,
        string $tenantScope
    ) {
        $this->permissionId = $permissionId;
        $this->capability = $capability;
        $this->actorIdentity = $actorIdentity;
        $this->actorType = $actorType;
        $this->executionContext = $executionContext;
        $this->tenantScope = $tenantScope;
        $this->resourceId = null;
        $this->resourceType = null;
        $this->resourceState = null;
        $this->decision = 'deny'; // Fail-closed default
        $this->evidence = [];
        $this->decisionTimestamp = new \DateTime();
        $this->metadata = [];
    }
    
    /**
     * Get permission identifier
     * 
     * NOTE: Returns unique permission identifier.
     * Used for permission lookup and evaluation.
     * 
     * @return string
     */
    public function getPermissionId(): string
    {
        return $this->permissionId;
    }
    
    /**
     * Get capability name
     * 
     * NOTE: Returns the capability being requested.
     * No wildcard matching allowed - exact capability names only.
     * 
     * @return string
     */
    public function getCapability(): string
    {
        return $this->capability;
    }
    
    /**
     * Get actor identity
     * 
     * NOTE: Returns the actor requesting permission.
     * Actor must be authenticated and tenant-bound.
     * 
     * @return string|int
     */
    public function getActorIdentity()
    {
        return $this->actorIdentity;
    }
    
    /**
     * Get actor type
     * 
     * NOTE: Returns the type of actor.
     * Must be one of: human, system, service, ai_operator
     * 
     * @return string
     */
    public function getActorType(): string
    {
        return $this->actorType;
    }
    
    /**
     * Get execution context
     * 
     * NOTE: Returns the execution context for permission.
     * Must be one of: authoring, runtime_public, system_background, governance
     * 
     * @return string
     */
    public function getExecutionContext(): string
    {
        return $this->executionContext;
    }
    
    /**
     * Get resource identifier
     * 
     * NOTE: Returns the resource being accessed.
     * Resource must be tenant-scoped.
     * 
     * @return string|int|null
     */
    public function getResourceId()
    {
        return $this->resourceId;
    }
    
    /**
     * Get resource type
     * 
     * NOTE: Returns the type of resource.
     * Examples: content, page, media, user, tenant
     * 
     * @return string|null
     */
    public function getResourceType(): ?string
    {
        return $this->resourceType;
    }
    
    /**
     * Get resource state
     * 
     * NOTE: Returns the current state of resource.
     * Must be one of: draft, published, archived, locked
     * 
     * @return string|null
     */
    public function getResourceState(): ?string
    {
        return $this->resourceState;
    }
    
    /**
     * Get tenant scope
     * 
     * NOTE: Returns the tenant scope for permission.
     * Actor tenant and resource tenant must match.
     * 
     * @return string
     */
    public function getTenantScope(): string
    {
        return $this->tenantScope;
    }
    
    /**
     * Get permission decision
     * 
     * NOTE: Returns the permission decision.
     * Must be one of: allow, deny
     * 
     * @return string
     */
    public function getDecision(): string
    {
        return $this->decision;
    }
    
    /**
     * Get decision evidence
     * 
     * NOTE: Returns evidence supporting the decision.
     * Used for audit trails and compliance.
     * 
     * @return array
     */
    public function getEvidence(): array
    {
        return $this->evidence;
    }
    
    /**
     * Get decision timestamp
     * 
     * NOTE: Returns when the permission decision was made.
     * Used for audit trails and temporal analysis.
     * 
     * @return \DateTime
     */
    public function getDecisionTimestamp(): \DateTime
    {
        return $this->decisionTimestamp;
    }
    
    /**
     * Check if permission is deterministic
     * 
     * NOTE: Returns whether permission decision is deterministic.
     * Same inputs must produce same authorization results.
     * 
     * @return bool
     */
    public function isDeterministic(): bool
    {
        // Phase 4: Basic deterministic check
        // Future phases will add complex validation
        return $this->permissionId !== null && $this->capability !== null;
    }
    
    /**
     * Check if permission is auditable
     * 
     * NOTE: Returns whether permission decision is auditable.
     * All authorization decisions must be logged.
     * 
     * @return bool
     */
    public function isAuditable(): bool
    {
        // Phase 4: Basic auditable check
        // Future phases will add audit trail validation
        return !empty($this->evidence) && $this->decisionTimestamp !== null;
    }
    
    /**
     * Check if permission is explainable
     * 
     * NOTE: Returns whether permission decision is explainable.
     * Authorization decisions must be traceable.
     * 
     * @return bool
     */
    public function isExplainable(): bool
    {
        // Phase 4: Basic explainable check
        // Future phases will add explanation generation
        return !empty($this->evidence);
    }
    
    /**
     * Validate permission structure
     * 
     * NOTE: Validates permission meets structural requirements.
     * Ensures required properties and security constraints.
     * 
     * @return bool
     */
    public function validate(): bool
    {
        // Phase 4: Basic structural validation
        // Future phases will add database validation
        
        // Permission ID must exist
        if ($this->permissionId === null || $this->permissionId === '') {
            return false;
        }
        
        // Capability must exist
        if ($this->capability === null || $this->capability === '') {
            return false;
        }
        
        // Actor must exist
        if ($this->actorIdentity === null || $this->actorIdentity === '') {
            return false;
        }
        
        // Actor type must be valid
        $validActorTypes = ['human', 'system', 'service', 'ai_operator'];
        if (!in_array($this->actorType, $validActorTypes)) {
            return false;
        }
        
        // Execution context must be valid
        $validContexts = ['authoring', 'runtime_public', 'system_background', 'governance'];
        if (!in_array($this->executionContext, $validContexts)) {
            return false;
        }
        
        // Decision must be valid
        $validDecisions = ['allow', 'deny'];
        if (!in_array($this->decision, $validDecisions)) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Set resource context
     * 
     * NOTE: Sets resource context for permission evaluation.
     * Used in future phases for resource-specific authorization.
     * 
     * @param string|int|null $resourceId
     * @param string|null $resourceType
     * @param string|null $resourceState
     * @return void
     */
    protected function setResourceContext($resourceId, ?string $resourceType, ?string $resourceState): void
    {
        $this->resourceId = $resourceId;
        $this->resourceType = $resourceType;
        $this->resourceState = $resourceState;
    }
    
    /**
     * Set permission decision
     * 
     * NOTE: Sets the permission decision with evidence.
     * Used in future phases for authorization evaluation.
     * 
     * @param string $decision
     * @param array $evidence
     * @return void
     */
    protected function setDecision(string $decision, array $evidence = []): void
    {
        $this->decision = $decision;
        $this->evidence = $evidence;
        $this->decisionTimestamp = new \DateTime();
    }
    
    /**
     * Add evidence
     * 
     * NOTE: Adds evidence to support the decision.
     * Used for audit trails and compliance.
     * 
     * @param string $key
     * @param mixed $value
     * @return void
     */
    protected function addEvidence(string $key, $value): void
    {
        $this->evidence[$key] = $value;
    }
    
    /**
     * Add metadata
     * 
     * NOTE: Adds metadata to the permission.
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
