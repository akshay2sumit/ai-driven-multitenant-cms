<?php

namespace App\Authorization\Contracts;

/**
 * Permission Interface
 * 
 * Contract for permission entities in the AIBOS Multi-Tenant CMS.
 * 
 * PURPOSE: Define permission as decision, not stored truth
 * PHASE: Execution Phase 4 — Authorization & Access Control Foundations
 * 
 * NOTE: This interface defines permission decision structure only. No actual
 * permission evaluation, assignment, or management is allowed in Phase 4.
 * This interface exists only to establish the permission foundation for future phases.
 * 
 * SECURITY: Permission is a decision, not a property. Actors have capabilities.
 * System decides permission at request time. Decision is always contextual and state-aware.
 * 
 * @package App\Authorization\Contracts
 */
interface PermissionInterface
{
    /**
     * Get permission identifier
     * 
     * NOTE: Returns unique permission identifier.
     * Used for permission lookup and evaluation.
     * 
     * @return string
     */
    public function getPermissionId(): string;
    
    /**
     * Get capability name
     * 
     * NOTE: Returns the capability being requested.
     * No wildcard matching allowed - exact capability names only.
     * 
     * @return string
     */
    public function getCapability(): string;
    
    /**
     * Get actor identity
     * 
     * NOTE: Returns the actor requesting permission.
     * Actor must be authenticated and tenant-bound.
     * 
     * @return string|int
     */
    public function getActorIdentity();
    
    /**
     * Get actor type
     * 
     * NOTE: Returns the type of actor.
     * Must be one of: human, system, service, ai_operator
     * 
     * @return string
     */
    public function getActorType(): string;
    
    /**
     * Get execution context
     * 
     * NOTE: Returns the execution context for permission.
     * Must be one of: authoring, runtime_public, system_background, governance
     * 
     * @return string
     */
    public function getExecutionContext(): string;
    
    /**
     * Get resource identifier
     * 
     * NOTE: Returns the resource being accessed.
     * Resource must be tenant-scoped.
     * 
     * @return string|int|null
     */
    public function getResourceId();
    
    /**
     * Get resource type
     * 
     * NOTE: Returns the type of resource.
     * Examples: content, page, media, user, tenant
     * 
     * @return string|null
     */
    public function getResourceType(): ?string;
    
    /**
     * Get resource state
     * 
     * NOTE: Returns the current state of resource.
     * Must be one of: draft, published, archived, locked
     * 
     * @return string|null
     */
    public function getResourceState(): ?string;
    
    /**
     * Get tenant scope
     * 
     * NOTE: Returns the tenant scope for permission.
     * Actor tenant and resource tenant must match.
     * 
     * @return string
     */
    public function getTenantScope(): string;
    
    /**
     * Get permission decision
     * 
     * NOTE: Returns the permission decision.
     * Must be one of: allow, deny
     * 
     * @return string
     */
    public function getDecision(): string;
    
    /**
     * Get decision evidence
     * 
     * NOTE: Returns evidence supporting the decision.
     * Used for audit trails and compliance.
     * 
     * @return array
     */
    public function getEvidence(): array;
    
    /**
     * Get decision timestamp
     * 
     * NOTE: Returns when the permission decision was made.
     * Used for audit trails and temporal analysis.
     * 
     * @return \DateTime
     */
    public function getDecisionTimestamp(): \DateTime;
    
    /**
     * Check if permission is deterministic
     * 
     * NOTE: Returns whether permission decision is deterministic.
     * Same inputs must produce same authorization results.
     * 
     * @return bool
     */
    public function isDeterministic(): bool;
    
    /**
     * Check if permission is auditable
     * 
     * NOTE: Returns whether permission decision is auditable.
     * All authorization decisions must be logged.
     * 
     * @return bool
     */
    public function isAuditable(): bool;
    
    /**
     * Check if permission is explainable
     * 
     * NOTE: Returns whether permission decision is explainable.
     * Authorization decisions must be traceable.
     * 
     * @return bool
     */
    public function isExplainable(): bool;
    
    /**
     * Validate permission structure
     * 
     * NOTE: Validates permission meets structural requirements.
     * Ensures required properties and security constraints.
     * 
     * @return bool
     */
    public function validate(): bool;
}
