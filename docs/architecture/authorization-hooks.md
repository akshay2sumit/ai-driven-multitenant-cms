# Authorization Hooks

## Context Binding Requirements

Every request must be explicitly bound to context, identity, and tenant before execution. No implicit binding is permitted.

### Required Bindings

#### Execution Context (Phase 28)
Requests must be bound to one of these contexts:
- **Authoring**: Content creation, editing, and management operations
- **Runtime/Public**: Public content access and user interactions
- **System/Background**: System operations, maintenance, and background tasks
- **Governance**: Audit, compliance, and governance activities

**Context Binding Rules**:
- Context must be determined before authorization
- Context cannot change during request processing
- Context must be appropriate for request type
- Context violations result in immediate denial

#### Identity & Trust State (Phase 30/31)
Requests must be bound to:
- **Authenticated identity**: Verified actor identity from Phase 30
- **Trust level**: Established trust classification (human, system, service, AI operator)
- **Tenant binding**: Actor's tenant association and resource tenant context
- **Session state**: Current session validity and continuity from Phase 31

**Identity Binding Rules**:
- Identity must be verified before authorization
- Trust level must be appropriate for request context
- Tenant binding must be enforced for all operations
- Session validity must be confirmed for session-based requests

#### Authorization State (Phase 29)
Requests must be bound to:
- **Permission evaluation**: Capability-based access control
- **Role composition**: Role-based permission aggregation
- **Contextual authorization**: Context-appropriate permission sets
- **Capability boundaries**: Defined capability limits and scopes

**Authorization Binding Rules**:
- Permission evaluation must use current identity and context
- Role composition must reflect current actor roles
- Contextual authorization must match execution context
- Capability boundaries must be enforced for all operations

### Binding Validation
If any required binding is missing or invalid → DENY

## Authorization Placement

### Authorization Invocation Points
Authorization MUST occur at these specific points in request processing:

#### 1. Post-Authentication, Pre-Execution
Authorization must happen:
- **After authentication** is complete and verified
- **Before any command execution** begins
- **Before any query execution** begins
- **Before any state access** is attempted

#### 2. Context-Specific Authorization
Authorization must be:
- **Context-aware**: Different rules for different execution contexts
- **Tenant-aware**: Enforced within tenant boundaries
- **Trust-level aware**: Different rules for different trust levels
- **Capability-aware**: Based on defined capability taxonomy

#### 3. Request-Type Specific Authorization
Authorization rules vary by request type:
- **Commands**: Strict authorization for state-changing operations
- **Queries**: Authorization even for read operations
- **System operations**: Special authorization for system-level requests
- **Governance operations**: Administrative authorization for audit/compliance

### No Late Authorization
Forbidden authorization patterns:
- **Execute then authorize**: Authorization after execution
- **Authorize only writes**: Skipping authorization for reads
- **Partial authorization**: Authorizing only parts of requests
- **Conditional authorization**: Authorization based on request outcomes

## Authorization Invocation Rules

### Universal Authorization Requirements
Every request must pass authorization:

#### Command Authorization
- **All commands require authorization**: No exceptions
- **Capability checking**: Verify command-specific capabilities
- **Context validation**: Ensure command is appropriate for context
- **Tenant enforcement**: Verify tenant access rights

#### Query Authorization
- **All queries require authorization**: No "safe reads" without authorization
- **Data access authorization**: Verify data access permissions
- **Scope validation**: Ensure query scope is authorized
- **Privacy protection**: Enforce data privacy and confidentiality

#### System Authorization
- **System operations**: Special authorization for system-level tasks
- **Background tasks**: Authorization for automated processes
- **Maintenance operations**: Authorization for system maintenance
- **Administrative functions**: Authorization for admin operations

### Authorization Decision Process
Authorization decisions must be:
- **Deterministic**: Same inputs produce same authorization results
- **Auditable**: All authorization decisions must be logged
- **Explainable**: Authorization decisions must be traceable
- **Consistent**: Authorization must be consistently applied

## Failure Short-Circuit Rules

### Short-Circuit Principle
Failure at ANY stage causes: STOP → DENY → AUDIT

### Failure Stages
Requests must stop immediately at these failure points:

#### 1. Context Resolution Failure
- **Context cannot be determined**: Request context is ambiguous
- **Context is inappropriate**: Request doesn't match execution context
- **Context changes during processing**: Context instability
- **Context validation fails**: Context is invalid or corrupted

#### 2. Authentication Failure
- **Identity cannot be verified**: Authentication fails
- **Trust level is insufficient**: Trust level too low for request
- **Tenant binding fails**: Tenant association is invalid
- **Session validation fails**: Session is invalid or expired

#### 3. Authorization Failure
- **Permission denied**: Actor lacks required permissions
- **Capability insufficient**: Actor lacks required capabilities
- **Role composition fails**: Role-based permissions insufficient
- **Contextual authorization fails**: Context-specific permissions insufficient

#### 4. Consistency Guarantee Failure
- **Consistency cannot be met**: Query consistency requirements not satisfied
- **Freshness requirements fail**: Data freshness requirements not met
- **Isolation failures**: Query isolation cannot be guaranteed
- **Resource constraints**: System cannot guarantee consistency

#### 5. Idempotency Check Failure
- **Idempotency cannot be determined**: Command idempotency is ambiguous
- **Replay detection fails**: Replay detection mechanism fails
- **Duplicate prevention fails**: Duplicate execution cannot be prevented
- **State validation fails**: Pre-execution state validation fails

### No Fallback Logic
Forbidden fallback behaviors:
- **Continue on degraded context**: No execution with reduced context
- **Retry after partial mutation**: No retry after partial command success
- **Silent degradation**: No hidden reduction in functionality
- **Best effort execution**: No partial or best-effort request processing

## Audit Evidence Requirements

### Mandatory Authorization Audit
Every authorization check must emit:

#### Request Information
- **Request ID**: Unique request identifier
- **Request type**: Query or Command classification
- **Timestamp**: Precise authorization check time

#### Actor Information
- **Actor identity**: Authenticated identity
- **Actor type**: Human, system, service, or AI operator
- **Trust level**: Established trust level
- **Tenant context**: Actor's tenant association

#### Authorization Information
- **Authorization decision**: Allow or deny decision
- **Permission evaluation**: Specific permissions evaluated
- **Capability check**: Capabilities verified
- **Role composition**: Role-based permissions applied

#### Context Information
- **Execution context**: Phase 28 context classification
- **Context validation**: Context appropriateness verification
- **Context binding**: Context binding status
- **Context-specific rules**: Context-specific authorization rules

#### Failure Information
- **Failure reason**: Specific reason for denial (if denied)
- **Failure stage**: Stage at which failure occurred
- **Security classification**: Security relevance of failure
- **Recommended action**: Guidance for resolution (if appropriate)

### Security Event Classification
Authorization events must be classified:
- **Normal authorization**: Standard permission checks
- **Security violations**: Policy violations or suspicious attempts
- **Privilege escalation**: Attempts to exceed authorized privileges
- **Cross-tenant violations**: Attempts to access other tenant data

### Comprehensive Audit Trail
Authorization audit must support:
- **Forensic analysis**: Complete reconstruction of authorization decisions
- **Compliance reporting**: Regulatory and audit requirements
- **Security monitoring**: Real-time security threat detection
- **Performance analysis**: Authorization performance monitoring

## Integration with Other Phases

### Phase 28 Integration
- **Context binding**: Execution context determination and validation
- **Context transitions**: Authorization across context boundaries
- **Context isolation**: Authorization within context constraints

### Phase 29 Integration
- **Permission evaluation**: Core permission checking mechanisms
- **Capability verification**: Capability-based authorization
- **Role composition**: Role-based permission aggregation

### Phase 31 Integration
- **Session validation**: Session-based authorization continuity
- **Trust level enforcement**: Trust-based authorization rules
- **Lifecycle awareness**: Time-bound authorization considerations

### Phase 30 Integration
- **Identity verification**: Identity-based authorization
- **Tenant enforcement**: Tenant-based authorization isolation
- **Trust semantics**: Trust-based authorization decisions

## Core Principles

1. **Authorization is mandatory**: No request bypasses authorization
2. **Authorization is early**: Authorization happens before execution
3. **Authorization is comprehensive**: All aspects of requests are authorized
4. **Authorization is auditable**: All authorization decisions are recorded
5. **Authorization is fail-closed**: Authorization failures always deny access

## Cross-Reference Dependencies

This authorization hooks model integrates with:
- request-semantics.md for request classification and lifecycle
- command-semantics.md for command-specific authorization requirements
- query-semantics.md for query-specific authorization requirements
- Phase 28 runtime context semantics for execution context binding
- Phase 29 permission semantics for permission evaluation mechanisms
- Phase 31 lifecycle semantics for session and trust validation
- Phase 30 identity semantics for identity and tenant binding
