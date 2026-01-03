# Data Lifecycle

## Lifecycle States Overview

Every data entity conceptually moves through defined lifecycle states. Not all entities must support all states, but transitions between states must be explicit and controlled.

### Conceptual Lifecycle Flow

```
Created → Active → (Soft Deleted) → (Archived) → (Hard Deleted)
```

**State Characteristics**:
- **Created**: Entity exists but may not be fully operational
- **Active**: Entity is fully operational and visible
- **Soft Deleted**: Entity is logically removed but recoverable
- **Archived**: Entity is stored for long-term retention
- **Hard Deleted**: Entity is permanently destroyed

## Soft Delete Semantics

### Definition
Soft delete means data is logically removed from normal operations while remaining physically present and recoverable.

### Soft Delete Characteristics
- **Logical removal**: Data is not visible in normal queries
- **Physical presence**: Data still exists in storage
- **Recoverability**: Data can be restored if authorized
- **Auditability**: Deletion is fully audited

### Soft Delete Rules
- **Explicit command required**: Soft delete requires explicit delete command
- **Delete authority required**: Actor must have deletion authority (Phase 29)
- **Tenant binding enforced**: Soft delete respects tenant boundaries
- **Full audit required**: Soft delete must be completely audited

### Soft-Deleted Data Behavior
**Prohibited Operations**:
- **Cannot be mutated**: Soft-deleted data cannot be modified
- **Cannot be acted upon**: Soft-deleted data cannot be used in operations
- **Cannot be queried normally**: Soft-deleted data is excluded from standard queries

**Permitted Operations**:
- **May be restored**: Soft-deleted data can be restored if authorized
- **May be hard deleted**: Soft-deleted data can be permanently destroyed
- **May be audited**: Soft-deleted data can be accessed for audit purposes

## Hard Delete Semantics

### Definition
Hard delete means data is permanently destroyed and cannot be recovered.

### Hard Delete Characteristics
- **Permanent destruction**: Data is completely removed
- **No recovery**: Data cannot be restored after hard delete
- **Reference cleanup**: All references to the data must be invalidated
- **Final audit**: Audit must be completed before destruction

### Hard Delete Requirements
**NEVER the default**: Hard delete is never the default deletion method.

**Required Conditions**:
- **Explicit command**: Hard delete requires explicit command
- **Higher authority**: Hard delete requires elevated authority
- **Governance justification**: Hard delete requires governance justification
- **Retention compliance**: Hard delete must respect retention rules
- **Pre-deletion audit**: Complete audit must occur before destruction

### Hard Delete Rules
- **Explicit justification required**: Reason for permanent deletion must be documented
- **Retention rule compliance**: Cannot delete before minimum retention period
- **Authority verification**: Enhanced authority verification required
- **Impact assessment**: Impact of deletion must be assessed
- **Reference cleanup**: All references must be properly invalidated

## Retention Semantics

### Retention Definition
Retention defines the time boundaries for data existence, including minimum retention periods and maximum retention limits.

### Retention Rules
- **Minimum retention**: Soft-deleted data must respect minimum retention windows
- **Maximum retention**: Data may not exceed maximum retention periods
- **Tenant awareness**: Retention rules are tenant-aware and configurable
- **Compliance requirements**: Retention must comply with regulatory requirements

### Retention Violations
- **Early hard delete**: Hard delete before minimum retention is forbidden
- **Excessive retention**: Keeping data beyond maximum retention may violate privacy rules
- **Cross-tenant retention**: Retention rules must not expose data across tenants
- **Compliance violations**: Retention must comply with applicable regulations

### Retention Enforcement
- **Automatic enforcement**: System must enforce retention rules automatically
- **Policy-based rules**: Retention rules based on data type and governance policies
- **Audit compliance**: Retention enforcement must be fully auditable
- **Exception handling**: Retention exceptions require explicit approval

## Restoration Semantics

### Restoration Definition
Restoration returns soft-deleted data to active state, making it visible and operational again.

### Restoration Requirements
Only soft-deleted data may be restored:
- **Explicit command required**: Restoration requires explicit command
- **Authorization required**: Actor must have restoration authority
- **State compatibility**: Data state must permit restoration
- **Context appropriateness**: Restoration must be appropriate for context

### Restoration Rules
- **Authority verification**: Restoration authority must be verified
- **State validation**: Data state must allow restoration
- **Tenant continuity**: Restoration must respect tenant boundaries
- **Audit required**: Restoration must be fully audited

### Restoration Limitations
Restoration does NOT:
- **Restore old permissions**: Permissions must be re-evaluated in current context
- **Restore expired trust**: Trust relationships must be re-established
- **Restore historical context**: Past context may not be recoverable
- **Restore dependencies**: Related entities may need separate restoration

## Lifecycle State Transitions

### Allowed Transitions
- **Created → Active**: Normal activation process
- **Active → Soft Deleted**: Standard deletion process
- **Soft Deleted → Active**: Restoration process
- **Soft Deleted → Hard Deleted**: Permanent destruction
- **Active → Archived**: Long-term storage
- **Archived → Hard Deleted**: Final destruction

### Forbidden Transitions
- **Created → Hard Deleted**: Bypasses soft delete and retention
- **Hard Deleted → Any state**: Hard delete is irreversible
- **Active → Created**: Cannot revert to created state
- **Cross-tenant transitions**: State changes across tenant boundaries

### Transition Requirements
All state transitions must:
- **Be explicit**: Transitions require explicit commands
- **Be authorized**: Transitions require appropriate authority
- **Be audited**: Transitions must be fully audited
- **Respect constraints**: Transitions must respect all defined constraints

## Lifecycle Metadata Requirements

### Mandatory Metadata
Every data entity must maintain:
- **Current state**: Current lifecycle state
- **State history**: History of state transitions
- **Transition timestamps**: When each transition occurred
- **Transition authority**: Who authorized each transition
- **Transition reason**: Why each transition occurred

### Audit Requirements
Lifecycle metadata must be:
- **Complete**: All lifecycle events must be recorded
- **Immutable**: Lifecycle history cannot be altered
- **Accessible**: Lifecycle data must be accessible for audit
- **Secure**: Lifecycle metadata must be protected

## Cross-Reference Dependencies

This data lifecycle model integrates with:
- data-ownership.md for ownership throughout lifecycle
- data-mutation-rules.md for mutation authority in lifecycle transitions
- data-consistency.md for consistency in lifecycle operations
- Phase 29 permission semantics for authorization in lifecycle changes
- Phase 30 identity semantics for actor identification in lifecycle events
- Phase 32 request semantics for command-based lifecycle transitions

## Core Principles

1. **Deletion is a lifecycle transition**: Delete is not destruction by default
2. **Safety over convenience**: Data recovery is prioritized over immediate cleanup
3. **Auditability over cleanliness**: Complete audit trails are prioritized over data removal
4. **Explicit transitions**: All lifecycle changes require explicit commands
5. **Retention compliance**: All lifecycle operations must respect retention rules

## Enforcement Rules

### State Validation
Before any lifecycle transition:
- **Current state verification**: Verify current entity state
- **Transition authority**: Verify authority for the transition
- **Transition validity**: Ensure transition is allowed
- **Constraint compliance**: Ensure all constraints are satisfied

### Runtime Enforcement
During lifecycle transitions:
- **State monitoring**: Monitor state changes
- **Authority tracking**: Track authority usage
- **Audit generation**: Generate comprehensive audit trails
- **Constraint enforcement**: Enforce all defined constraints

### Post-Transition Verification
After lifecycle transitions:
- **State confirmation**: Verify final state is correct
- **Audit completion**: Ensure audit trail is complete
- **Reference consistency**: Verify reference consistency
- **Compliance validation**: Validate compliance requirements

## Special Lifecycle Considerations

### System-Owned Data
System-owned data may have different lifecycle rules:
- **Extended retention**: System data may have longer retention periods
- **Different deletion rules**: System data may have special deletion requirements
- **Enhanced audit**: System data may require enhanced audit trails
- **Governance oversight**: System data may require special governance

### Cross-Tenant Data
Cross-tenant data (when explicitly allowed) requires:
- **Enhanced authority**: Higher authority requirements
- **Special audit**: Additional audit requirements
- **Governance approval**: Explicit governance approval
- **Compliance validation**: Additional compliance checks

### High-Sensitivity Data
High-sensitivity data requires:
- **Stricter retention**: More stringent retention rules
- **Enhanced deletion**: More rigorous deletion processes
- **Complete audit**: Comprehensive audit requirements
- **Regulatory compliance**: Strict regulatory compliance
