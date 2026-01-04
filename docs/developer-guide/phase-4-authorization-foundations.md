# Phase 4: Authorization & Access Control Foundations

## Purpose
This document explains the Phase 4 authorization and access control foundations implementation for the AIBOS Multi-Tenant CMS.

## Phase 4 Constraints
**CRITICAL**: Phase 4 is strictly limited to authorization and access control foundations only.

### ✅ Allowed in Phase 4
- Role and permission entities/contracts
- Policy interfaces (allow/deny)
- Authorization service skeletons (inactive)
- Guards/middleware stubs (fail-closed)
- Authorization-related migrations only

### 🚫 Forbidden in Phase 4
- Authentication logic, sessions, cookies
- Controllers, routes, or UI
- Permission assignment workflows
- CMS features or public APIs
- Seed or demo roles/users

## Authorization & Access Control Foundations

### Authorization Contracts

#### PermissionInterface (`app/Authorization/Contracts/PermissionInterface.php`)
- **Purpose**: Define permission as decision, not stored truth
- **Key Methods**: getCapability(), getActorIdentity(), getExecutionContext(), getDecision()
- **Permission Evaluation**: Capability-based, contextual, state-aware
- **Security**: Permission is decision, not property; actors have capabilities

#### RoleInterface (`app/Authorization/Contracts/RoleInterface.php`)
- **Purpose**: Define role as tenant-scoped capability bundles
- **Key Methods**: getCapabilities(), hasCapability(), getTenantScope(), getRoleType()
- **Role Types**: functional, administrative, system
- **Security**: Roles are data, not logic; never bypass capabilities

#### PolicyInterface (`app/Authorization/Contracts/PolicyInterface.php`)
- **Purpose**: Define policy evaluation for authorization decisions
- **Key Methods**: getRules(), appliesToContext(), getDecisionTypes(), isDeterministic()
- **Policy Types**: capability, context, resource, tenant, governance
- **Security**: Policies must be deterministic, auditable, explainable

### Authorization Models

#### BasePermission (`app/Authorization/Models/BasePermission.php`)
- **Purpose**: Provide foundation for permission implementations
- **Features**: Capability-based evaluation, context awareness, tenant binding
- **Validation**: Basic structural validation only
- **Future-Ready**: Structure for Phase 5+ authorization evaluation

#### BaseRole (`app/Authorization/Models/BaseRole.php`)
- **Purpose**: Provide foundation for role implementations
- **Features**: Tenant-scoped capabilities, least-privilege validation, escalation detection
- **Validation**: Basic structural validation only
- **Future-Ready**: Structure for Phase 5+ role management

### Authorization Services

#### AuthorizationService (`app/Authorization/Services/AuthorizationService.php`)
- **Purpose**: Provide authorization framework foundation
- **Features**: Authorization constants, evaluation placeholders, evidence generation
- **Constants**: DECISION_ALLOW, DECISION_DENY, DECISION_ESCALATE, DECISION_AUDIT
- **Security**: All methods return fail-closed responses in Phase 4

### Authorization Guards

#### AuthorizationGuard (`app/Authorization/Guards/AuthorizationGuard.php`)
- **Purpose**: Enforce authorization boundaries and prevent unauthorized access
- **Features**: Fail-closed guard enforcement, escalation detection, tenant continuity
- **Constants**: GUARD_ALLOW, GUARD_DENY, GUARD_FAIL, GUARD_ESCALATE, GUARD_AUDIT
- **Security**: Immediate denial on ambiguity or missing authorization

## Database Schema

### Authorization Tables

#### roles Table
- **Purpose**: Store tenant-scoped role definitions
- **Key Fields**: tenant_id, name, type, capabilities, is_active
- **Constraints**: Unique tenant+role name, foreign key to tenants
- **Security**: Tenant-scoped only, no global roles

#### permissions Table
- **Purpose**: Store permission decisions and audit evidence
- **Key Fields**: permission_id, actor_identity, capability, decision, decision_evidence
- **Constraints**: Unique permission_id, comprehensive indexing
- **Security**: Fail-closed default decisions, full audit trail

#### role_assignments Table
- **Purpose**: Store role assignments with audit trail
- **Key Fields**: tenant_id, role_id, actor_identity, assigner_identity, is_active
- **Constraints**: Unique active assignments, foreign keys to tenants and roles
- **Security**: Explicit assignment only, full audit trail, revocation support

## Authorization Model Compliance

### Permission Semantics
- ✅ Permission as decision, not property
- ✅ Capability-based evaluation
- ✅ Contextual and state-aware decisions
- ✅ Fail-closed evaluation order
- ✅ Deterministic, auditable, explainable

### Role Composition
- ✅ Tenant-scoped capability bundles
- ✅ Explicit assignment only
- ✅ No role escalation
- ✅ Least-privilege composition
- ✅ No cross-tenant role grants

### Authorization Hooks
- ✅ Framework-level authorization hooks
- ✅ Comprehensive audit requirements
- ✅ Fail-closed short-circuit rules
- ✅ Security event classification

## Fail-Closed Security Pattern

### Authorization Flow
1. **Tenant Validation**: Verify tenant context is valid
2. **Actor Validation**: Validate actor identity and type
3. **Capability Check**: Verify actor has required capability
4. **Context Validation**: Ensure capability is valid in context
5. **Resource State Check**: Verify resource state compatibility
6. **Tenant Continuity**: Ensure actor and resource tenant match
7. **Fail-Closed**: Any ambiguity results in immediate denial

### Security Guarantees
- No partial authorization allowed
- Ambiguity always results in denial
- Privilege escalation attempts detected
- Cross-tenant access prevented
- All decisions auditable and explainable

## Why This Approach?

1. **Authorization Model Compliance**: Strict adherence to permission semantics and role composition
2. **Fail-Closed Security**: Immediate denial on any uncertainty
3. **Phase Discipline**: No feature creep beyond Phase 4 scope
4. **Foundation Ready**: Structure supports Phase 5+ authorization evaluation

## Next Phase Readiness

The system is now ready for Phase 5 implementation:
- Database authorization evaluation
- Permission checking implementation
- Role-based authorization activation

## Testing Strategy

### Authorization Testing
- **Strategy Declaration**: Unit tests for contracts, models, and guards
- **Deferred Justification**: Authorization framework not executable in Phase 4
- **Future Implementation**: Integration tests in Phase 5+

### Role Testing
- **Strategy Declaration**: Structural validation of role models and assignments
- **Deferred Justification**: Role management requires database access
- **Future Implementation**: Role lifecycle tests in Phase 5+

## Phase 4 Limitations

### Current Limitations
- No actual authorization evaluation
- No permission checking logic
- No role assignment management
- No policy evaluation implementation

### Intentional Gaps
These gaps are intentional and will be addressed in future phases:
- Phase 5: Authorization evaluation and permission checking
- Phase 6: Role management and assignment workflows
- Phase 7: Policy evaluation and enforcement

---

*This document will be updated as phases complete.*
