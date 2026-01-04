<?php

namespace App\Authorization\Contracts;

/**
 * Policy Interface
 * 
 * Contract for authorization policies in the AIBOS Multi-Tenant CMS.
 * 
 * PURPOSE: Define policy evaluation for authorization decisions
 * PHASE: Execution Phase 4 — Authorization & Access Control Foundations
 * 
 * NOTE: This interface defines policy evaluation structure only. No actual
 * policy evaluation, rule processing, or decision making is allowed in Phase 4.
 * This interface exists only to establish the policy foundation for future phases.
 * 
 * SECURITY: Policy evaluation must be deterministic, auditable, and explainable.
 * Authorization decisions must be consistent and traceable.
 * 
 * @package App\Authorization\Contracts
 */
interface PolicyInterface
{
    /**
     * Get policy identifier
     * 
     * NOTE: Returns unique policy identifier.
     * Used for policy lookup and evaluation.
     * 
     * @return string
     */
    public function getPolicyId(): string;
    
    /**
     * Get policy name
     * 
     * NOTE: Returns the human-readable policy name.
     * Used for policy documentation and user communication.
     * 
     * @return string
     */
    public function getPolicyName(): string;
    
    /**
     * Get policy description
     * 
     * NOTE: Returns the policy description.
     * Used for policy documentation and user communication.
     * 
     * @return string|null
     */
    public function getPolicyDescription(): ?string;
    
    /**
     * Get policy type
     * 
     * NOTE: Returns the type of policy.
     * Must be one of: capability, context, resource, tenant, governance
     * 
     * @return string
     */
    public function getPolicyType(): string;
    
    /**
     * Get policy scope
     * 
     * NOTE: Returns the scope of policy application.
     * Must be one of: tenant, system, global
     * 
     * @return string
     */
    public function getPolicyScope(): string;
    
    /**
     * Get policy rules
     * 
     * NOTE: Returns the policy evaluation rules.
     * Rules must be explicit and auditable.
     * 
     * @return array
     */
    public function getRules(): array;
    
    /**
     * Get policy priority
     * 
     * NOTE: Returns the policy priority for evaluation order.
     * Higher priority policies are evaluated first.
     * 
     * @return int
     */
    public function getPriority(): int;
    
    /**
     * Check if policy is active
     * 
     * NOTE: Returns whether policy is currently active.
     * Inactive policies are not used in evaluation.
     * 
     * @return bool
     */
    public function isActive(): bool;
    
    /**
     * Get policy evaluation context
     * 
     * NOTE: Returns the context where policy applies.
     * Must match execution contexts from Phase 28.
     * 
     * @return array
     */
    public function getEvaluationContext(): array;
    
    /**
     * Get policy decision types
     * 
     * NOTE: Returns the decision types policy can make.
     * Must be subset of: allow, deny, escalate, audit
     * 
     * @return array
     */
    public function getDecisionTypes(): array;
    
    /**
     * Check if policy applies to context
     * 
     * NOTE: Returns whether policy applies to given context.
     * Used for policy filtering and evaluation optimization.
     * 
     * @param string $context
     * @return bool
     */
    public function appliesToContext(string $context): bool;
    
    /**
     * Check if policy applies to resource
     * 
     * NOTE: Returns whether policy applies to given resource type.
     * Used for policy filtering and evaluation optimization.
     * 
     * @param string $resourceType
     * @return bool
     */
    public function appliesToResource(string $resourceType): bool;
    
    /**
     * Get policy audit requirements
     * 
     * NOTE: Returns audit requirements for policy evaluation.
     * Used for compliance and security monitoring.
     * 
     * @return array
     */
    public function getAuditRequirements(): array;
    
    /**
     * Get policy failure handling
     * 
     * NOTE: Returns failure handling rules for policy evaluation.
     * Fail-closed: policy evaluation failure results in deny.
     * 
     * @return array
     */
    public function getFailureHandling(): array;
    
    /**
     * Validate policy structure
     * 
     * NOTE: Validates policy meets structural requirements.
     * Ensures required properties and security constraints.
     * 
     * @return bool
     */
    public function validate(): bool;
    
    /**
     * Get policy metadata
     * 
     * NOTE: Returns additional policy metadata.
     * Used for audit trails and operational requirements.
     * 
     * @return array
     */
    public function getMetadata(): array;
    
    /**
     * Check if policy is deterministic
     * 
     * NOTE: Returns whether policy evaluation is deterministic.
     * Same inputs must produce same policy results.
     * 
     * @return bool
     */
    public function isDeterministic(): bool;
}
