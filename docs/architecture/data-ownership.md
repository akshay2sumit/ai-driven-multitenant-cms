# Data Ownership

## Ownership Definition

Data ownership establishes the authoritative responsibility and accountability for data entities within the system. Every piece of data has exactly one owner and belongs to exactly one tenant.

### Core Characteristics

- **Explicit**: Ownership must be explicitly declared, never inferred
- **Immutable**: Ownership cannot change without explicit transfer
- **Accountable**: Owners are responsible for their data
- **Tenant-bound**: All data is confined to tenant boundaries

## Owner vs Tenant Distinction

### Data Owner
The logical authority responsible for a data entity.

**Owner Properties**:
- **Identity or system role**: Owner is always an authenticated identity or defined system role
- **Responsibility focus**: Ownership implies responsibility, not necessarily mutation rights
- **Accountability**: Owners are accountable for their data's lifecycle and compliance
- **Transferable**: Ownership can be explicitly transferred through authorized processes

**Owner Rules**:
- Ownership does NOT automatically grant mutation authority
- Ownership is immutable once set (unless explicitly transferred)
- Ownership transfer requires explicit authorization and audit
- System roles can own data on behalf of the system

### Tenant Ownership
The tenant context to which data belongs and within which it is isolated.

**Tenant Properties**:
- **Exclusive belonging**: Every data entity belongs to exactly one tenant
- **Isolation by default**: Data is never visible across tenants by default
- **Mandatory binding**: Tenant ID is required for all data entities
- **Boundary enforcement**: Tenant boundaries are strictly enforced

**Tenant Rules**:
- Actor tenant MUST match data tenant for access
- Cross-tenant data requires explicit governance rules
- Tenant binding is immutable for the data lifecycle
- No "shared by accident" data is permitted

## System-Owned Data

Some data may be owned by the system rather than specific identities.

### System Ownership Characteristics
- **System authority**: Data is owned by the system itself
- **Tenant-bound unless global**: System data is still tenant-bound unless explicitly designated as global
- **Operational necessity**: System-owned data serves operational or governance purposes
- **Limited scope**: System ownership is used only where necessary

### System-Owned Data Examples (Conceptual)
- **Audit logs**: System-generated audit trails
- **System configuration**: Operational settings and parameters
- **Governance records**: Compliance and governance metadata
- **Telemetry data**: System performance and usage metrics

### System Ownership Rules
- System ownership must be explicitly declared
- System-owned data still respects tenant boundaries unless global
- System ownership does not bypass authorization requirements
- System-owned data is subject to the same lifecycle and audit rules

## Forbidden Ownership Patterns

### Prohibited Patterns
- **Implicit ownership**: "Creator owns everything" assumptions
- **Cross-tenant reads**: Accessing data from different tenants
- **Cross-tenant writes**: Modifying data in different tenants
- **Global tables without governance**: Unrestricted global data access
- **Inferred ownership**: Ownership derived from context or assumptions

### Forbidden Behaviors
- **Ownership by inference**: Determining ownership from hidden rules
- **Shared ownership**: Multiple owners for the same data entity
- **Orphaned data**: Data without clear ownership
- **Tenant leakage**: Data visible across tenant boundaries
- **Ownership ambiguity**: Unclear or undefined ownership

## Ownership Metadata Requirements

### Mandatory Metadata
Every data entity must maintain:
- **Owner identifier**: Unique identifier of the owning identity or role
- **Tenant identifier**: Tenant context for the data
- **Ownership type**: User-owned, system-owned, or role-owned
- **Creation timestamp**: When ownership was established
- **Transfer history**: Record of ownership transfers (if any)

### Audit Requirements
Ownership metadata must be:
- **Explicit**: Clearly stated and unambiguous
- **Auditable**: All ownership changes must be logged
- **Immutable**: Ownership history cannot be altered
- **Traceable**: Ownership must be traceable to authoritative sources

## Ownership Transfer Semantics

### Transfer Conditions
Ownership can be transferred only when:
- **Explicit command**: Transfer initiated through explicit command
- **Authorization**: Current owner or authorized system role initiates transfer
- **Recipient eligibility**: New owner is eligible to own the data
- **Context appropriateness**: Transfer is appropriate for the data type and context

### Transfer Requirements
- **Explicit intent**: Transfer must be explicitly declared
- **Authorization verification**: Both current and new owner must be verified
- **Audit logging**: Transfer must be fully audited
- **State preservation**: Data state must be preserved during transfer

## Cross-Reference Dependencies

This data ownership model integrates with:
- Phase 29 permission semantics for ownership-based authorization
- Phase 30 identity semantics for owner identification and trust levels
- Phase 32 request semantics for ownership validation in requests
- data-mutation-rules.md for mutation authority versus ownership
- data-lifecycle.md for ownership throughout data lifecycle
- data-consistency.md for ownership in multi-entity operations

## Core Principles

1. **Ownership is explicit**: Every data entity has clearly declared ownership
2. **Ownership is exclusive**: Each data entity has exactly one owner
3. **Ownership is tenant-bound**: All data is confined to tenant boundaries
4. **Ownership is accountable**: Owners are responsible for their data
5. **Ownership is immutable**: Ownership changes only through explicit transfer

## Enforcement Rules

### Access Validation
Every data access must verify:
- **Actor identity**: Who is attempting to access the data
- **Actor tenant**: Which tenant the actor belongs to
- **Data owner**: Who owns the data
- **Data tenant**: Which tenant the data belongs to

### Authorization Requirements
Data access requires:
- **Owner authorization**: Permission from data owner or system authority
- **Tenant matching**: Actor tenant must match data tenant
- **Context appropriateness**: Access must be appropriate for execution context
- **Capability verification**: Actor must have required capabilities

### Failure Conditions
Access is denied when:
- **Ownership is ambiguous**: Data ownership cannot be determined
- **Tenant mismatch**: Actor tenant does not match data tenant
- **Authorization fails**: Actor lacks required permissions
- **Context inappropriate**: Access is not appropriate for current context
