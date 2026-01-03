# Data Mutation Rules

## Mutation Authority vs Ownership

Mutation authority defines who may change data, which is distinct from data ownership. Ownership establishes responsibility, while mutation authority grants permission to change.

### Separation of Concerns

| Concept | Meaning | Scope |
|---------|---------|-------|
| **Ownership** | Responsibility & accountability | Legal and compliance responsibility |
| **Mutation Authority** | Permission to change | Technical permission to modify |
| **Execution** | Actual mutation action | Implementation of the change |

**Critical Rule**: These three concepts must never be mixed or conflated.

### Mutation Authority Definition
Mutation authority defines:
- **Who may mutate data**: Authorized identities or roles
- **In which context**: Appropriate execution contexts
- **What type of mutation**: Create, update, delete permissions
- **Under what constraints**: State, tenant, and capability limitations

### Authority Characteristics
- **Capability-based**: Authority derives from Phase 29 capabilities
- **Context-bound**: Authority is valid only in specific contexts (Phase 28)
- **Tenant-bound**: Authority is confined to tenant boundaries (Phase 33)
- **Explicit**: Authority must be explicitly granted, never inferred

## Create / Update / Delete Semantics

### Create Operations
**Definition**: Creation of new data entities within the system.

**Requirements**:
- **Explicit command**: Creation must be initiated through explicit command (Phase 32)
- **Authorization**: Creator must have creation authority for the data type
- **Tenant binding**: New entity must be bound to creator's tenant
- **Ownership assignment**: New entity must have explicit owner assigned

**Create Rules**:
- Creation requires valid mutation authority
- Created data inherits tenant context from creator
- Ownership must be explicitly assigned at creation
- Creation must be auditable with full context

### Update Operations
**Definition**: Modification of existing data entities.

**Requirements**:
- **Existing entity**: Target entity must exist and be accessible
- **Valid mutation authority**: Actor must have update authority for the entity
- **State compatibility**: Entity's current state must allow updates
- **Context validity**: Update must be appropriate for execution context

**Update Rules**:
- Updates require explicit mutation authority
- Entity state must permit modification
- Tenant continuity must be maintained
- Updates must preserve data integrity

### Delete Operations
**Definition**: Removal of data entities from active use (see data-lifecycle.md for soft vs hard delete).

**Requirements**:
- **Explicit delete command**: Deletion must be explicitly commanded
- **Delete authority**: Actor must have deletion authority for the entity
- **State compatibility**: Entity's current state must allow deletion
- **Lifecycle compliance**: Deletion must respect lifecycle rules

**Delete Rules**:
- Deletion requires explicit authority
- Entity state must permit deletion
- Deletion must respect retention rules
- Deletion must be fully auditable

## Context & Permission Requirements

### Context Validity Rules
Mutation is allowed only in valid execution contexts:

#### Authoring Context
- **Create**: Allowed with appropriate authority
- **Update**: Allowed with appropriate authority
- **Delete**: Allowed with appropriate authority

#### Runtime/Public Context
- **Create**: Generally forbidden
- **Update**: Generally forbidden
- **Delete**: Generally forbidden

#### System/Background Context
- **Create**: Allowed for system operations
- **Update**: Allowed for system maintenance
- **Delete**: Allowed for system cleanup

#### Governance Context
- **Create**: Allowed for audit and compliance
- **Update**: Allowed for governance operations
- **Delete**: Allowed for compliance requirements

### Permission Requirements
All mutations require:
- **Authentication**: Verified identity (Phase 30)
- **Authorization**: Capability-based permission (Phase 29)
- **Context appropriateness**: Context-appropriate permissions
- **Tenant matching**: Actor tenant matches data tenant

### State Compatibility Rules
Data state must allow mutation:

#### Mutable States
- **Draft**: Allows create, update, delete
- **Active**: Allows update, delete (with authority)
- **Pending**: Allows update, delete (with authority)

#### Immutable States
- **Archived**: Generally prohibits updates
- **Published**: May prohibit certain updates
- **Locked**: Prohibits all mutations

## Forbidden Mutation Patterns

### Prohibited Patterns
- **Mutation during validation**: Data changes during validation processes
- **Mutation during query**: Read operations that cause data changes
- **Implicit mutation on read**: Hidden side effects during data access
- **Cross-tenant mutation**: Modifying data in different tenants
- **Auto-fix mutation**: Automatic data correction without explicit command

### Forbidden Behaviors
- **Silent mutations**: Data changes without explicit audit trail
- **Cascading mutations**: Uncontrolled chain reactions of data changes
- **Conditional mutations**: Mutations based on hidden or implicit conditions
- **Retrospective mutations**: Changes that affect historical data
- **Unauthorized mutations**: Data changes without proper authority

### Mutation Constraint Violations
- **Context violations**: Mutations in inappropriate contexts
- **Authority violations**: Mutations without proper authorization
- **Tenant violations**: Cross-tenant data modifications
- **State violations**: Mutations that violate entity state rules

## Mutation Authority Boundaries

### Capability Boundaries
Mutation authority is limited by:
- **Capability scope**: Authority applies only to defined capabilities
- **Context limits**: Authority is valid only in appropriate contexts
- **Tenant isolation**: Authority does not cross tenant boundaries
- **State constraints**: Authority respects entity state constraints

### Temporal Boundaries
Mutation authority is time-bound:
- **Session validity**: Authority valid only during authenticated sessions
- **Trust level**: Authority depends on current trust level
- **Capability expiration**: Authority expires with capabilities
- **Context changes**: Authority may change with context changes

### Operational Boundaries
Mutation authority respects:
- **System limits**: Authority cannot exceed system operational limits
- **Resource constraints**: Authority respects resource availability
- **Performance constraints**: Authority considers performance implications
- **Compliance requirements**: Authority respects regulatory constraints

## Audit and Evidence Requirements

### Mandatory Mutation Audit
Every mutation must emit:

#### Mutation Identification
- **Mutation ID**: Unique mutation identifier
- **Mutation type**: Create, update, or delete
- **Target entity**: Entity being modified
- **Command ID**: Command that initiated the mutation

#### Actor Information
- **Actor identity**: Identity performing the mutation
- **Actor authority**: Mutation authority used
- **Actor context**: Execution context of the mutation
- **Actor tenant**: Actor's tenant association

#### Authority Information
- **Authority source**: Source of mutation authority
- **Permission level**: Level of authorization granted
- **Capability used**: Specific capability exercised
- **Authorization decision**: Permission evaluation result

#### Mutation Details
- **Before state**: Entity state before mutation (conceptual)
- **After state**: Entity state after mutation (conceptual)
- **Mutation scope**: Scope and impact of the mutation
- **Side effects**: Any additional side effects produced

### Security Event Classification
Mutations must be classified for security monitoring:
- **Normal mutations**: Standard business operations
- **Sensitive mutations**: High-impact or privileged changes
- **Suspicious mutations**: Unusual or unexpected changes
- **Security violations**: Unauthorized or malicious mutations

## Cross-Reference Dependencies

This data mutation rules model integrates with:
- data-ownership.md for ownership versus authority distinction
- data-lifecycle.md for deletion semantics and state transitions
- data-consistency.md for multi-entity mutation coordination
- Phase 29 permission semantics for capability-based authority
- Phase 30 identity semantics for actor identification and trust
- Phase 32 request semantics for command-based mutation initiation
- Phase 28 runtime context semantics for context-appropriate mutations

## Core Principles

1. **Authority is explicit**: Mutation authority must be explicitly granted
2. **Authority is contextual**: Authority applies only in appropriate contexts
3. **Authority is bounded**: Authority has defined limits and constraints
4. **Authority is auditable**: All mutations must be fully audited
5. **Authority is separable**: Mutation authority is distinct from ownership

## Enforcement Rules

### Pre-Mutation Validation
Before any mutation, system must verify:
- **Actor authority**: Actor has valid mutation authority
- **Context validity**: Mutation is appropriate for current context
- **Entity accessibility**: Target entity is accessible to actor
- **State compatibility**: Entity state permits the mutation

### Runtime Enforcement
During mutation execution:
- **Authority monitoring**: Continuous verification of authority
- **Context tracking**: Monitoring of execution context
- **State validation**: Ongoing validation of entity state
- **Boundary enforcement**: Enforcement of all defined boundaries

### Post-Mutation Verification
After mutation completion:
- **State verification**: Verification of resulting entity state
- **Authority audit**: Audit of authority usage
- **Context cleanup**: Cleanup of execution context
- **Evidence generation**: Generation of audit evidence
