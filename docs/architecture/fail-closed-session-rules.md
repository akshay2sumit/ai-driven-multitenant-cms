# Fail-Closed Session Rules

## Fail-Closed Session Principles

Session management MUST follow fail-closed semantics: any ambiguity, uncertainty, or failure condition MUST result in immediate session termination and denial of access. Ambiguity always results in DENY.

### Core Principle
When in doubt, terminate the session and require re-authentication. Session convenience must never compromise security.

## Edge Cases and Handling Rules

### Token Expiry Mid-Session
**Scenario**: A token expires while a session is active.

**Handling Rules**:
- **Immediate session termination**: Session ends immediately when token expires
- **No grace periods**: No temporary session continuation after expiry
- **Re-authentication required**: Fresh authentication needed for new session
- **Audit event**: Log token expiry and session termination

**Response Flow**:
1. Detect token expiry
2. Immediately terminate session
3. Deny current request
4. Require re-authentication for future access
5. Log security event

### Token Revocation Mid-Session
**Scenario**: A token is revoked while a session is active.

**Handling Rules**:
- **Immediate session termination**: Session ends instantly on revocation
- **No delayed enforcement**: Revocation takes effect immediately
- **Downstream impact**: All related sessions must terminate
- **Security event**: Treat as potential security incident

**Response Flow**:
1. Receive token revocation notification
2. Immediately terminate all affected sessions
3. Invalidate session continuity
4. Deny current and future requests
5. Log as security event

### Credential Revocation
**Scenario**: The underlying credential is revoked while sessions are active.

**Handling Rules**:
- **Cascade termination**: ALL sessions derived from credential must terminate
- **Immediate effect**: No grace period for credential revocation
- **Complete invalidation**: All tokens and sessions become invalid
- **Security response**: Treat as potential compromise

**Response Flow**:
1. Detect credential revocation
2. Immediately terminate ALL derived sessions
3. Invalidate ALL derived tokens
4. Deny all current and future requests
5. Log as high-priority security event

### Context Change
**Scenario**: Execution context changes during an active session.

**Handling Rules**:
- **Session termination**: Any context change terminates the session
- **No context switching**: Sessions cannot adapt to new contexts
- **Re-authentication required**: New context requires fresh authentication
- **Boundary enforcement**: Trust boundaries must be strictly enforced

**Context Change Examples**:
- Authoring → Runtime: ❌ Session terminates
- Runtime → System: ❌ Session terminates
- Public → Authenticated: ❌ Session terminates

**Response Flow**:
1. Detect context change attempt
2. Immediately terminate current session
3. Deny request in new context
4. Require context-appropriate re-authentication
5. Log context boundary violation

### Tenant Mismatch
**Scenario**: Session is used in a different tenant context.

**Handling Rules**:
- **Immediate denial**: Any tenant mismatch results in denial
- **Session termination**: Mismatched sessions are terminated
- **No cross-tenant access**: Sessions never cross tenant boundaries
- **Security event**: Log as tenant boundary violation

**Response Flow**:
1. Detect tenant mismatch
2. Immediately terminate session
3. Deny current request
4. Require tenant-appropriate re-authentication
5. Log tenant boundary violation

### Trust Level Downgrade
**Scenario**: Trust level decreases during an active session.

**Handling Rules**:
- **Session termination**: Trust level downgrade terminates session
- **No downgrade adaptation**: Sessions cannot adapt to lower trust
- **Re-authentication required**: New trust level requires fresh authentication
- **Audit required**: All trust level changes must be logged

**Response Flow**:
1. Detect trust level downgrade
2. Immediately terminate session
3. Deny current request
4. Require appropriate re-authentication
5. Log trust level change

## Explicit Denial Rules

### Ambiguity Resolution
- **Ambiguous session state**: Always terminate and deny
- **Unclear token validity**: Always terminate and deny
- **Context uncertainty**: Always terminate and deny
- **Tenant confusion**: Always terminate and deny

### Failure Handling
- **Token validation failure**: Always terminate and deny
- **Session lookup failure**: Always terminate and deny
- **Context verification failure**: Always terminate and deny
- **Identity verification failure**: Always terminate and deny

### Security Events
- **Suspicious activity**: Always terminate and investigate
- **Anomalous patterns**: Always terminate and log
- **Repeated failures**: Always terminate and potentially block
- **Boundary violations**: Always terminate and log as security event

## No Grace Periods, No Soft Fallback

### Strict Enforcement
- **No temporary access**: No grace periods for any failures
- **No soft fallback**: No degraded access modes
- **No partial sessions**: No partially functional sessions
- **No emergency access**: No backdoor access methods

### Immediate Termination
- **Instant effect**: All failures take immediate effect
- **No delayed enforcement**: No postponed security measures
- **No queued requests**: No request queuing during failures
- **No background recovery**: No silent session recovery

## Edge Case Decision Matrix

| Condition | Action | Reason |
|-----------|--------|---------|
| Token expired | Terminate + Deny | Time-based validity expired |
| Token revoked | Terminate + Deny | Authority-based invalidation |
| Credential revoked | Terminate + Deny | Downstream invalidation |
| Context changed | Terminate + Deny | Context boundary violation |
| Tenant mismatch | Terminate + Deny | Tenant boundary violation |
| Trust level downgraded | Terminate + Deny | Trust level no longer sufficient |
| Session state unclear | Terminate + Deny | Ambiguity resolution |
| Token validation failed | Terminate + Deny | Validation failure |
| Identity verification failed | Terminate + Deny | Authentication failure |
| System error during validation | Terminate + Deny | Fail-closed principle |

## Audit and Evidence Requirements

### Mandatory Audit Events
All session termination events MUST emit audit evidence:

**Required Fields**:
- **Session identifier**: Opaque session identifier
- **Termination reason**: Specific cause of termination
- **Trigger source**: User, system, admin, or governance
- **Timestamp**: Precise time of termination
- **Context**: Execution context at termination
- **Security impact**: Assessment of security implications

### Security Event Classification
Events must be classified by severity:

- **High Severity**: Credential revocation, tenant violations, trust downgrades
- **Medium Severity**: Token revocation, context changes, repeated failures
- **Low Severity**: Token expiry, normal session termination

### Incident Response Support
Audit evidence must support:
- **Forensic analysis**: Detailed event reconstruction
- **Security investigation**: Pattern detection and analysis
- **Compliance reporting**: Regulatory and audit requirements
- **System monitoring**: Real-time security monitoring

## Implementation Guidelines

### Fail-Closed Enforcement
All session management implementations MUST:

1. **Validate before use**: Check session validity before every operation
2. **Terminate on failure**: Immediately terminate on any validation failure
3. **Log comprehensively**: Record all termination events with full context
4. **Enforce boundaries**: Strictly enforce all trust and tenant boundaries
5. **Require re-authentication**: Never allow silent session recovery

### Performance Considerations
While maintaining fail-closed security:

- **Efficient validation**: Optimize session validation for performance
- **Scalable termination**: Handle mass session termination efficiently
- **Fast audit logging**: Ensure audit logging doesn't impact performance
- **Resource cleanup**: Clean up terminated session resources promptly

## Core Principles

1. **Ambiguity always results in DENY**: When uncertain, terminate and deny
2. **No grace periods**: All failures take immediate effect
3. **Complete termination**: No partial or degraded session states
4. **Comprehensive auditing**: All events must be fully auditable
5. **Security over convenience**: Session convenience never compromises security

## Cross-Reference Dependencies

These fail-closed session rules implement the principles defined in:
- session-semantics.md for session lifecycle and continuity requirements
- token-semantics.md for token lifecycle and revocation handling
- credential-model.md for credential revocation and downstream impact
- Phase 30 trust-boundaries.md for trust boundary enforcement
- Phase 30 authentication-semantics.md for authentication state management
- Phase 30 identity-model.md for identity and tenant binding rules
- Phase 29 permission semantics for authorization evaluation
- Phase 28 runtime context semantics for execution context requirements
