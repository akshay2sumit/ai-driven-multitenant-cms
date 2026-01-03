# Request Semantics

## Request Definition

A request is a single, intentional interaction initiated by an actor (human/system/service) that asks the system to either read state (Query) or change state (Command). Every request is context-bound, identity-bound, authorization-checked, and time-bound.

### Core Characteristics

- **Intentional**: Each request has a clear, declarative purpose
- **Atomic**: Requests represent single, complete interactions
- **Bounded**: Requests operate within defined contexts and constraints
- **Traceable**: Every request must be auditable and observable

## Query vs Command Rule (Never Both)

### Fundamental Principle
Every request is either a Query or a Command - never both. This is a non-negotiable semantic rule with no exceptions.

### Query (Read Semantics)
A Query is a request that:
- **Observes state** without changing it
- **Produces output** representing current system state
- **Does not change system state** in any way
- **Must be side-effect free** except for audit logging

**Query Rules**:
- Must not mutate data
- Must not emit business events
- Must be safe to repeat
- Must not depend on or affect external state

### Command (Write Semantics)
A Command is a request that:
- **Intends to change system state**
- **Produces side effects** as intended
- **May emit events** as part of state changes
- **Must be authorized** before execution

**Command Rules**:
- May mutate state intentionally
- May emit business events
- Must be explicitly authorized
- Must be auditable

### Classification Enforcement
- **Ambiguous classification** → DENY
- **Mixed behavior** → DENY
- **Classification changes mid-processing** → DENY
- **Context-dependent classification** → DENY

## Request Lifecycle

Every request conceptually passes through these stages in order:

### 1. Receive
- Request enters the system
- Initial validation of request structure
- Context resolution begins

### 2. Classify
- Request is classified as Query or Command
- Classification must be deterministic
- Ambiguity results in immediate denial

### 3. Authenticate
- Identity verification using Phase 30 semantics
- Trust level establishment
- Tenant binding verification

### 4. Authorize
- Permission evaluation using Phase 29 semantics
- Capability checking
- Context-appropriate authorization

### 5. Execute
- Request processing according to classification
- Query execution or Command execution
- Result generation

### 6. Audit
- Comprehensive audit evidence emission
- Outcome recording
- Security event logging (if applicable)

### Failure Short-Circuit
If any stage fails → DENY & STOP. No fallback logic or partial execution is permitted.

## Fail-Closed Classification Rules

### Classification Determinism
Request classification must be:
- **Deterministic**: Same request always classifies the same way
- **Explicit**: Classification must be declared, not inferred
- **Stable**: Classification cannot change during processing

### Ambiguity Resolution
- **Ambiguous requests** are always denied
- **Mixed requests** (read + write) are always denied
- **Context-dependent classification** is forbidden
- **Late classification** is not permitted

### Classification Boundaries
- **Queries cannot become Commands** during processing
- **Commands cannot be downgraded** to Queries
- **Side effects cannot be added** to Queries
- **State mutation cannot be removed** from Commands

## Request Context Binding

### Required Bindings
Every request must be explicitly bound to:

#### Execution Context (Phase 28)
- **Authoring**: Content creation and management
- **Runtime/Public**: Public content access
- **System/Background**: System operations
- **Governance**: Audit and compliance activities

#### Identity & Trust State (Phase 30/31)
- **Authenticated identity**: Verified actor identity
- **Trust level**: Established trust classification
- **Tenant binding**: Actor and resource tenant association

#### Authorization State (Phase 29)
- **Permission evaluation**: Capability-based access
- **Role composition**: Role-based permissions
- **Contextual authorization**: Context-appropriate permissions

### Binding Validation
If any required binding is missing or invalid → DENY

## Explicitly Forbidden Request Patterns

### Prohibited Patterns
- **Read that mutates state**: Queries with hidden side effects
- **Write hidden inside read**: Commands disguised as Queries
- **Auto-write during preview**: Implicit state changes
- **Command disguised as query**: Misleading request classification
- **Context-dependent behavior**: Requests that change behavior based on hidden context

### Forbidden Behaviors
- **Implicit intent**: Requests that infer intent from context
- **Late authorization**: Authorization after execution
- **Partial execution**: Commands that partially succeed
- **Silent failures**: Errors that are not explicitly reported

## Audit and Evidence Requirements

### Mandatory Audit Fields
Every request must emit audit evidence containing:

#### Request Identification
- **Request ID**: Unique request identifier
- **Request type**: Query or Command classification
- **Timestamp**: Precise request receipt time

#### Actor Information
- **Actor identity**: Authenticated identity
- **Actor type**: Human, system, service, or AI operator
- **Tenant context**: Actor's tenant association

#### Context Information
- **Execution context**: Phase 28 context classification
- **Trust level**: Established trust level
- **Authorization scope**: Permission evaluation scope

#### Outcome Information
- **Decision**: Allowed or denied
- **Reason**: Specific reason for denial (if applicable)
- **Side-effect indicator**: Whether side effects occurred

### Security Event Classification
Requests must be classified for security monitoring:
- **Normal operations**: Standard business requests
- **Suspicious patterns**: Unusual request patterns
- **Security violations**: Policy violations or attacks
- **High-risk operations**: Sensitive or privileged requests

## Core Principles

1. **Requests are atomic**: Each request represents one complete interaction
2. **Classification is deterministic**: Same request always classified the same way
3. **Queries never mutate**: Read operations never change state
4. **Commands always authorize**: Write operations always require authorization
5. **Ambiguity always denies**: Unclear requests are always rejected

## Cross-Reference Dependencies

This request semantics model integrates with:
- Phase 28 runtime context semantics for execution context binding
- Phase 29 permission semantics for authorization evaluation
- Phase 31 lifecycle semantics for time-bound and trust validation
- command-semantics.md for detailed command behavior rules
- query-semantics.md for detailed query behavior rules
- authorization-hooks.md for authorization placement and timing
- Phase 30 identity semantics for identity binding and trust levels
