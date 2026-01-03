# Error Semantics

## Error vs Failure Definitions

### Core Reliability Principle
Not all failures are errors, and not all errors are failures. The system must first understand:
- What went wrong
- How serious it is
- Who can see it

### Error Definition
An error is an expected, bounded condition that:
- Occurs during normal operation
- Can be handled deterministically
- Does NOT compromise system integrity

**Error Characteristics**:
- **Expected**: Part of normal operational scenarios
- **Bounded**: Well-defined scope and impact
- **Deterministic**: Predictable handling and outcomes
- **Safe**: Does not threaten system integrity

**Error Examples (Conceptual)**:
- Authorization denied
- Validation failed
- Resource not found
- Business rule violation

**Error Rules**:
- Errors are part of normal control flow
- Errors are recoverable at request level
- Errors do NOT require system recovery
- Errors can be safely exposed to users (with appropriate sanitization)

### Failure Definition
A failure is an unexpected or unsafe condition that:
- Violates system assumptions
- May compromise correctness or security
- Requires recovery action

**Failure Characteristics**:
- **Unexpected**: Outside normal operational expectations
- **Unsafe**: May threaten system integrity or security
- **Recovery Required**: System must take recovery action
- **Containment Needed**: Failure must be contained to prevent spread

**Failure Examples (Conceptual)**:
- Partial data mutation detected
- Consistency violation
- Trust boundary breach
- Unhandled exception
- Cross-tenant access attempt

**Failure Rules**:
- Failures are NOT part of normal flow
- Failures require containment
- Failures may trigger recovery or shutdown
- Failures must NOT be exposed to users in detail

## Error vs Failure Decision Rule

### Classification Rule
```
If integrity at risk → FAILURE
Else → ERROR
```

**No gray zone allowed.** The system must make a definitive classification.

### Integrity Risk Indicators
System integrity is at risk when:
- **Data consistency is compromised**: Partial or inconsistent state
- **Security boundaries are breached**: Unauthorized access or privilege escalation
- **Trust assumptions are violated**: System cannot trust its own state
- **Multi-entity operations are incomplete**: Partial success scenarios
- **Tenant isolation is threatened**: Cross-tenant data exposure

### Fail-Closed Classification Rules
- If system cannot classify → treat as FAILURE
- If ambiguity exists → treat as FAILURE
- If failure detected mid-request → abort immediately
- When in doubt, escalate to failure

## Error Classification

### Client Errors (Expected & Safe)
**Meaning**: User or caller caused the request to fail.

**Examples**:
- Invalid input format
- Missing required data
- Authorization denied
- Resource not found
- Invalid operation for current state

**Classification Rules**:
- Deterministic and predictable
- Safe to expose (with sanitization)
- No system recovery required
- Part of normal business logic

### Domain Errors (Business-Level)
**Meaning**: Request is valid but business rules don't allow the operation.

**Examples**:
- Publishing archived content
- Deleting locked entity
- State transition not allowed
- Business constraint violation

**Classification Rules**:
- Safe to expose (high-level)
- Must not reveal internal state
- Auditable for business compliance
- Recoverable at user action level

### System Errors (Operational)
**Meaning**: System infrastructure or runtime issue.

**Examples**:
- Dependency unavailable
- Timeout occurred
- Resource exhaustion
- Network connectivity issues

**Classification Rules**:
- User sees generic message
- Detailed logs only internally
- May escalate to failure if integrity at risk
- System recovery may be required

### Failures (Critical & Unsafe)
**Meaning**: System integrity, security, or consistency may be compromised.

**Examples**:
- Partial mutation detected
- Cross-tenant access attempt
- Trust boundary violation
- Unhandled exception
- Consistency constraint violation

**Classification Rules**:
- Treated as FAILURE (not error)
- Immediate abort required
- Containment and recovery required
- Never exposed to users in detail

## Severity Levels

### Severity Semantics
Severity is semantic, not numeric. It defines system response requirements.

### LOW Severity
**Meaning**: Safe, expected conditions

**System Response**:
- Return error to user
- Log for monitoring
- No escalation required
- Continue normal operation

**Examples**:
- Validation failures
- Authorization denied
- Resource not found

### MEDIUM Severity
**Meaning**: Business constraint violations

**System Response**:
- Return error to user
- Log for business monitoring
- May trigger business process alerts
- Continue normal operation

**Examples**:
- Business rule violations
- State transition restrictions
- Domain constraint failures

### HIGH Severity
**Meaning**: Operational risks that may affect service

**System Response**:
- Return generic error to user
- Log with high priority
- May trigger operational alerts
- Monitor for patterns

**Examples**:
- Resource exhaustion
- Dependency issues
- Performance degradation

### CRITICAL Severity
**Meaning**: Integrity risk or system compromise

**System Response**:
- Failure handling (not error handling)
- Immediate containment
- System recovery required
- Security incident response

**Examples**:
- Consistency violations
- Security breaches
- Data corruption
- System integrity failures

## Fail-Closed Escalation Rules

### Escalation Triggers
System must escalate to FAILURE when:
- Error classification fails
- Severity is unclear
- Error repeats abnormally
- Multiple concurrent errors occur
- Error patterns indicate systemic issues

### Escalation Rules
- If error classification fails → escalate to FAILURE
- If severity unclear → escalate to FAILURE
- If error repeats abnormally → escalate to FAILURE
- No silent downgrade allowed
- When in doubt, escalate

### Escalation Benefits
- Prevents silent failures
- Ensures appropriate response
- Maintains system integrity
- Provides clear audit trail

## Cross-Reference Dependencies

This error semantics model integrates with:
- error-visibility.md for visibility and exposure rules
- failure-containment.md for failure handling and containment
- recovery-semantics.md for recovery processes and outcomes
- error-ux.md for user-facing error presentation
- Phase 32 request semantics for error handling in request lifecycle
- Phase 33 data consistency for consistency-related error classification
- Phase 30 trust boundaries for trust violation detection

## Core Principles

1. **Classification is mandatory**: Every abnormal condition must be classified as error or failure
2. **Fail-closed by default**: When uncertain, escalate to failure
3. **Integrity first**: System integrity takes precedence over availability
4. **Deterministic handling**: Same conditions produce same classifications and responses
5. **Containment required**: Failures must be contained to prevent spread

## Enforcement Rules

### Classification Enforcement
- Every abnormal condition must be classified
- Classification must be deterministic
- Classification rules must be consistently applied
- Classification decisions must be auditable

### Severity Enforcement
- Severity levels must be consistently applied
- Severity must drive system response
- Severity escalation must be justified
- Severity decisions must be logged

### Escalation Enforcement
- Escalation rules must be automatically applied
- Escalation decisions must be auditable
- Escalation must trigger appropriate responses
- Escalation must preserve system integrity
