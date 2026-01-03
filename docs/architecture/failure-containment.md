# Failure Containment

## Core Safety Principle

A system must prefer availability loss over integrity loss.

When faced with a choice:
- System temporarily unavailable ✔
- System serves incorrect data ❌

## Failure Detection

### Detection Principles
Failure detection must be:
- **Immediate**: Detect failures as soon as they occur
- **Accurate**: Minimize false positives and false negatives
- **Comprehensive**: Detect all types of failures
- **Contextual**: Consider execution context and impact

### Detection Triggers
System must detect failures when:
- **Integrity boundaries are breached**: Data consistency violations
- **Trust assumptions are violated**: Security or trust compromises
- **System invariants are broken**: Core system rules violated
- **Multi-entity operations fail**: Partial success scenarios
- **Tenant isolation is threatened**: Cross-tenant boundary breaches
- **Unhandled exceptions occur**: Unexpected system states
- **Resource exhaustion occurs**: System cannot continue safely

### Detection Methods
**Runtime Detection**:
- Consistency constraint violations
- Trust boundary violations
- Authorization failures beyond expected rates
- Data integrity check failures
- System invariant violations

**Behavioral Detection**:
- Abnormal error patterns
- Performance degradation
- Resource usage anomalies
- Cross-tenant access attempts
- Repeated authorization failures

## Immediate Containment

### Containment Actions
On detecting a failure, system MUST:
- **Abort current request**: Stop processing immediately
- **Prevent further side effects**: Block any additional operations
- **Isolate affected scope**: Contain failure to smallest possible scope
- **Preserve evidence**: Maintain audit trail and system state

### Containment Rules
- **No retries**: Do not retry after failure detection
- **No continuation**: Do not continue with partial operations
- **No silent handling**: Do not hide or ignore failures
- **No delayed response**: Respond immediately to contain impact

### Containment Sequence
1. **Immediate Abort**: Stop current operation
2. **Side Effect Prevention**: Block additional operations
3. **Scope Isolation**: Identify and isolate affected scope
4. **Evidence Preservation**: Secure audit trail and state
5. **Notification**: Alert appropriate systems/personnel

## Containment Scope

### Scope Hierarchy
Failure containment may be scoped to:
1. **Single Request** (best case): Failure contained to one request
2. **Single Identity/Session**: Failure contained to one user's session
3. **Single Tenant**: Failure contained to one tenant
4. **Subsystem**: Failure contained to specific system component
5. **Whole System** (last resort): System-wide failure

### Scope Selection Rules
**Primary Rule**: Smallest possible scope, but never unsafe scope.

**Scope Decision Factors**:
- **Failure type**: Different failures require different scopes
- **Impact assessment**: Evaluate potential impact radius
- **Tenant safety**: Prioritize tenant isolation
- **System integrity**: Maintain overall system integrity
- **Recovery complexity**: Consider recovery requirements

### Scope Implementation
**Request Scope**:
- Abort only the failing request
- Allow other requests to continue
- Maintain system availability
- Used for isolated failures

**Session Scope**:
- Terminate affected user session
- Prevent further operations from that user
- Allow other users to continue
- Used for user-specific failures

**Tenant Scope**:
- Isolate entire tenant
- Prevent cross-tenant contamination
- Allow other tenants to continue
- Used for tenant-specific failures

**System Scope**:
- System-wide shutdown or degraded mode
- Prevent all operations
- Used for catastrophic failures

## Failure Isolation Rules

### Cross-Tenant Isolation
**Critical Requirement**: Failure in one tenant MUST NOT affect others.

**Isolation Mechanisms**:
- **Resource isolation**: Separate resources per tenant
- **Process isolation**: Separate processes or containers
- **Data isolation**: Separate databases or schemas
- **Network isolation**: Separate network segments or virtual networks

**Isolation Verification**:
- Regular isolation testing
- Cross-tenant access monitoring
- Resource usage monitoring
- Performance impact assessment

### Session Isolation
**Requirement**: Failure in one session MUST NOT leak into others.

**Isolation Mechanisms**:
- **Session boundaries**: Strict session separation
- **Resource cleanup**: Proper resource cleanup on failure
- **State isolation**: Separate state management per session
- **Permission isolation**: Maintain permission boundaries

### Process Isolation
**Requirement**: Failure in one process MUST NOT cascade to others.

**Isolation Mechanisms**:
- **Process boundaries**: Separate process execution
- **Communication isolation**: Controlled inter-process communication
- **Resource isolation**: Separate resource allocation
- **Failure propagation control**: Prevent failure spread

## Fail-Fast vs Fail-Safe

### Fail-Fast
**Definition**: Abort immediately when failure is detected.

**Characteristics**:
- Immediate termination
- No attempt to continue
- Used when integrity risk is high
- Prioritizes correctness over availability

**When to Use**:
- Data integrity violations
- Security breaches
- Consistency failures
- Trust boundary violations

**Benefits**:
- Prevents cascade failures
- Maintains system integrity
- Simplifies recovery
- Reduces blast radius

### Fail-Safe
**Definition**: Enter restricted mode when failure is detected.

**Characteristics**:
- Continue with limited functionality
- Maintain system availability
- Used when availability matters but integrity is safe
- Prioritizes availability over full functionality

**When to Use**:
- Non-critical component failures
- Performance degradation
- Resource exhaustion
- External dependency issues

**Benefits**:
- Maintains service availability
- Provides degraded functionality
- Allows graceful degradation
- Reduces user impact

### Default Strategy
**Default**: Fail-Fast

System should default to fail-fast unless:
- Availability is explicitly more important than full functionality
- Integrity is not at risk
- Degraded mode is safe and useful
- Business requirements specifically require fail-safe

## Blast Radius Control

### Blast Radius Definition
The blast radius is the potential impact area of a failure.

### Control Mechanisms
**Architectural Controls**:
- **Microservices**: Isolated service boundaries
- **Circuit breakers**: Prevent cascade failures
- **Bulkheads**: Resource isolation
- **Rate limiting**: Prevent overload cascades

**Operational Controls**:
- **Monitoring**: Early failure detection
- **Alerting**: Rapid response to failures
- **Manual overrides**: Human intervention capability
- **Emergency procedures**: Pre-planned responses

### Blast Radius Assessment
**Assessment Criteria**:
- **Number of affected users**: User impact scope
- **Data impact scope**: Amount and sensitivity of affected data
- **System impact scope**: Number of affected components
- **Recovery complexity**: Difficulty and time to recover

**Assessment Process**:
1. **Immediate assessment**: Quick evaluation of impact
2. **Detailed analysis**: Comprehensive impact analysis
3. **Recovery planning**: Plan recovery based on impact
4. **Post-incident review**: Learn and improve

## Cross-Reference Dependencies

This failure containment model integrates with:
- error-semantics.md for failure detection and classification
- error-visibility.md for failure visibility during containment
- recovery-semantics.md for recovery processes after containment
- error-ux.md for user experience during failure containment
- Phase 32 request semantics for failure handling in request lifecycle
- Phase 33 data consistency for consistency failure containment
- Phase 30 trust boundaries for trust violation containment

## Core Principles

1. **Contain first**: Immediately isolate failures to prevent spread
2. **Integrity first**: Prioritize data integrity over availability
3. **Isolation mandatory**: Failures must be contained within defined boundaries
4. **Evidence preservation**: Maintain complete audit trail during containment
5. **Recovery preparation**: Containment should prepare for recovery

## Enforcement Rules

### Detection Enforcement
- Failure detection must be automatic and immediate
- Detection rules must be consistently applied
- False negatives must be minimized
- Detection must be audited and monitored

### Containment Enforcement
- Containment must be automatic and immediate
- Containment scope must be appropriate
- Containment must preserve evidence
- Containment must be audited

### Isolation Enforcement
- Isolation boundaries must be strictly enforced
- Cross-boundary failures must be prevented
- Isolation effectiveness must be monitored
- Isolation violations must trigger alerts

## Special Containment Considerations

### Multi-Tenant Failures
- Tenant isolation must be maintained at all costs
- Cross-tenant failures must trigger immediate system-wide response
- Tenant-specific failures must not affect other tenants
- Tenant recovery must be independent

### Security Failures
- Security failures must trigger immediate containment
- Security failures must be treated as critical
- Security failures must preserve forensic evidence
- Security failures may require law enforcement involvement

### Data Integrity Failures
- Data integrity failures must trigger immediate rollback
- Data integrity failures must preserve data state
- Data integrity failures may require data restoration
- Data integrity failures must be thoroughly investigated
