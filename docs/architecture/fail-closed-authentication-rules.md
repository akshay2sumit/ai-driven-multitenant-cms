# Fail-Closed Authentication Rules

## Core Principle

Authentication MUST follow fail-closed semantics: any ambiguity, uncertainty, or failure condition MUST result in immediate denial of access. Ambiguity always results in DENY.

## Partial Authentication Handling

### Definition
Partial authentication occurs when authentication evidence is incomplete, insufficient, or inconsistent.

### Handling Rules
- Partial authentication MUST be treated as authentication failure
- No progressive authentication or step-up authentication is permitted
- Session establishment MUST require complete authentication
- Partial evidence MUST NOT be stored for future completion

### Examples
Partial authentication includes:
- Missing required authentication factors
- Incomplete identity verification
- Expired or invalid credentials
- Mismatched authentication evidence

## Ambiguity and Conflict Rules

### Ambiguity Resolution
- Ambiguous authentication evidence MUST result in denial
- Conflicting identity claims MUST result in denial
- Unclear authentication method MUST result in denial
- Multiple matching identities MUST result in denial

### Conflict Detection
Authentication systems MUST detect and reject:
- Identity claim conflicts
- Authentication method conflicts
- Tenant binding conflicts
- Timing and sequence conflicts

### Default Action
When ambiguity or conflict is detected:
- Access MUST be immediately denied
- Audit event MUST be generated
- Reason for denial MUST be logged
- Retry attempts MUST be rate-limited

## Stale Proof Handling

### Stale Evidence Definition
Authentication evidence is stale when it exceeds defined validity periods or has been superseded.

### Handling Rules
- Stale authentication evidence MUST be rejected
- Expired sessions MUST be terminated immediately
- Superseded credentials MUST be invalidated
- Grace periods for stale evidence are PROHIBITED

### Validity Enforcement
Authentication systems MUST enforce:
- Strict expiration times
- Evidence freshness requirements
- Immediate revocation effectiveness
- No cached or replayed evidence acceptance

## Revocation Timing Semantics

### Immediate Revocation
- Revocation MUST take effect immediately
- No grace periods for revocation are permitted
- Revoked identities MUST be denied access instantly
- Active sessions MUST be terminated on revocation

### Revocation Propagation
- Revocation MUST propagate to all system components
- Authentication caches MUST be invalidated
- Authorization decisions MUST reflect revocation
- Audit trails MUST record revocation events

### Revocation Evidence
Revocation MUST provide:
- Clear identity identification
- Revocation reason and timestamp
- Effective time of revocation
- Authority for revocation action

## Boundary Downgrade Attempts

### Definition
Boundary downgrade attempts occur when entities try to access lower-trust boundaries with higher-trust credentials.

### Prevention Rules
- Boundary downgrade attempts MUST be denied
- Trust level escalation is one-way only
- Higher trust credentials MUST NOT work at lower boundaries
- Boundary crossing MUST be directionally controlled

### Detection and Response
Systems MUST detect:
- Inappropriate boundary crossing attempts
- Trust level manipulation
- Privilege downgrade attempts
- Context switching violations

## Replay and Reuse Semantics

### Conceptual Framework
Authentication evidence MUST be resistant to replay and unauthorized reuse.

### Replay Prevention
- Authentication evidence MUST be single-use
- Replay detection MUST be implemented
- Timestamp validation MUST be enforced
- Nonce or challenge-response mechanisms MUST be used

### Reuse Restrictions
- Authentication tokens MUST have limited validity
- Credential reuse across sessions MUST be controlled
- Multi-factor evidence MUST not be reusable
- Context binding MUST prevent credential transfer

### Evidence Uniqueness
Each authentication attempt MUST generate:
- Unique transaction identifiers
- Non-replayable challenge responses
- Context-specific authentication evidence
- Tamper-evident proof of authenticity

## System-Wide Failure Handling

### Cascade Prevention
- Authentication failures MUST NOT cascade to unrelated components
- Isolation boundaries MUST contain authentication failures
- System stability MUST be maintained during authentication issues
- Graceful degradation MUST not compromise security

### Failure Recovery
- Authentication systems MUST support rapid recovery
- Failure modes MUST be well-defined and tested
- Recovery procedures MUST maintain security posture
- Operational continuity MUST not bypass authentication

## Cross-Reference Dependencies

These fail-closed rules implement the principles defined in:
- authentication-semantics.md for authentication state definitions
- trust-boundaries.md for boundary crossing requirements
- identity-model.md for identity verification standards
- Phase 28 runtime context for execution environment security
- Phase 29 permission semantics for post-authentication access control
