# Session Semantics

## Session as Continuity of Verified Trust

A session is a server-recognized continuity of verified trust across multiple actions. Sessions provide operational convenience but never extend authority or bypass security requirements.

### Core Characteristics

- **Continuity-based**: Sessions span multiple related actions
- **Trust-bound**: Sessions exist only while trust remains valid
- **Context-aware**: Sessions are tied to specific execution contexts
- **Revocable**: Sessions can be terminated at any time
- **Authorization-dependent**: Every action still requires independent authorization

### Critical Distinctions

- **Session ≠ Permission**: Sessions do not grant capabilities
- **Session ≠ Authority**: Sessions do not extend trust levels
- **Session ≠ Permanent Trust**: Sessions are temporary and conditional

## Session Lifecycle

### Started
The session begins after successful authentication and token issuance.

**Properties:**
- Bound to authenticated identity and tenant
- Associated with specific trust level
- Tied to execution context (authoring, runtime, etc.)
- May span multiple tokens conceptually

**Initiation Triggers:**
- Successful authentication with valid credential
- Token issuance and validation
- Context establishment and verification

### Active
The session is currently valid and may participate in operations.

**Properties:**
- Underlying tokens remain valid
- No revocation events have occurred
- Execution context remains stable
- Tenant binding is maintained

**Continuity Requirements:**
- Every request must re-evaluate authorization independently
- Context stability must be maintained
- Tenant boundaries must be enforced
- Trust level must remain appropriate

### Terminated
The session has ended and can no longer be used.

**Termination Causes:**
- **Explicit logout**: User-initiated session end
- **Token expiry**: Natural expiration of underlying tokens
- **Token revocation**: Authority-based token invalidation
- **Credential revocation**: Downstream credential invalidation
- **Trust downgrade**: Reduction in trust level
- **Boundary violation**: Attempt to cross trust boundaries
- **Context change**: Shift in execution context

**Properties:**
- Session identifier becomes invalid
- All continuity is broken
- Re-authentication required for new session
- Audit trail records termination reason

## Session Continuity Rules

### Rule 1 — Authorization Always Re-evaluated
Every request within a session MUST independently re-evaluate authorization:

- **No permission caching**: Authorization decisions are not cached across requests
- **Phase 29 compliance**: Every action must satisfy permission evaluation
- **Context verification**: Execution context must be validated per request
- **Tenant enforcement**: Tenant boundaries must be checked each time

**Rationale**: Session convenience must never weaken security controls.

### Rule 2 — Context Stability Required
Session continuity requires stable execution context:

- **Context changes break sessions**: Moving between contexts terminates session
- **No context escalation**: Sessions cannot upgrade their context level
- **Context validation**: Each request must verify context appropriateness
- **Boundary enforcement**: Trust boundaries must be respected

**Examples**:
- Authoring → Runtime: ❌ Session breaks, re-authentication required
- Runtime → System: ❌ Session breaks, re-authentication required

### Rule 3 — Tenant Continuity Required
Sessions are strictly tenant-bound:

- **Tenant mismatch**: Any tenant mismatch immediately invalidates session
- **No cross-tenant reuse**: Sessions cannot be reused across tenants
- **Tenant isolation**: Session data and state must remain tenant-isolated
- **Boundary enforcement**: Tenant boundaries must be strictly enforced

### Rule 4 — Trust Level Drift
Sessions must respond to trust level changes:

- **Trust level drops**: Session must terminate if trust level decreases
- **Higher trust required**: Re-authentication needed for trust escalation
- **Trust verification**: Trust level must be validated per request
- **No implicit upgrades**: Sessions cannot self-elevate trust

## Re-authentication Requirements

Re-authentication is REQUIRED in these situations:

### Token-Related Triggers
- **Token expires**: Natural expiry of underlying tokens
- **Token revoked**: Authority-based token invalidation
- **Token corruption**: Token integrity or format failures

### Credential-Related Triggers
- **Credential revoked**: Downstream credential invalidation
- **Credential suspended**: Temporary credential disablement
- **Credential changed**: Credential replacement or update

### Context-Related Triggers
- **Context change**: Shift in execution context
- **Context escalation**: Request for higher trust context
- **Boundary crossing**: Attempt to cross trust boundaries

### Security-Related Triggers
- **Sensitive capability**: Request for high-privilege operations
- **Long-lived session**: Sessions exceeding risk thresholds
- **Security events**: Detected anomalies or threats
- **Governance requirements**: Compliance or audit requirements

### Re-authentication Rules
- **No silent re-authentication**: User must explicitly provide credentials
- **Fresh authentication**: Re-authentication must use current, valid credentials
- **Context appropriate**: Re-authentication must match required context
- **Audit required**: All re-authentication events must be logged

## Explicit Termination Causes

### User-Initiated Termination
- **Explicit logout**: User chooses to end session
- **Security action**: User initiates security measures
- **Account changes**: User modifies account settings

### System-Initiated Termination
- **Token expiry**: Natural expiration of session tokens
- **Policy enforcement**: Automated security policies
- **Resource management**: System resource constraints

### Administrative Termination
- **Admin action**: Administrative session management
- **Security incident**: Response to detected threats
- **Compliance requirements**: Regulatory or audit requirements

### Governance-Initiated Termination
- **Policy violation**: Breach of governance rules
- **Compliance enforcement**: Regulatory compliance actions
- **Audit requirements**: Audit-driven session termination

## Audit and Evidence Emission

Session lifecycle events must emit comprehensive audit evidence:

### Required Evidence Fields
- **Session identifier**: Opaque session identifier
- **Identity**: The authenticated identity
- **Tenant**: The tenant context
- **Start/terminate reason**: Cause of session lifecycle events
- **Trigger source**: User, system, admin, or governance action
- **Timestamp**: Precise time of events
- **Context**: Execution context information
- **Trust level**: Current trust level at event time

### Event Types to Audit
- Session start and initialization
- Session continuity events
- Session termination and reasons
- Re-authentication events
- Context change attempts
- Security violations and incidents

## Explicitly Forbidden Session Behaviors

### Prohibited Behaviors
- **Infinite sessions**: Sessions must have defined lifetimes
- **Session survives credential revocation**: Sessions must die with credentials
- **Session auto-upgrades trust**: Sessions cannot self-elevate
- **Session reused across contexts**: Context changes break sessions
- **Session reused across tenants**: Tenant changes break sessions

### No Persistence Strategies
- No persistent login mechanisms
- No "remember me" functionality
- No automatic session restoration
- No background session extension

## Core Principles

1. **Session convenience never weakens security**: Every action still requires authorization
2. **Sessions are temporary continuity**: Not permanent authority
3. **Context stability is mandatory**: Context changes break sessions
4. **Tenant isolation is absolute**: Cross-tenant sessions are forbidden
5. **Re-authentication is explicit**: No silent or automatic re-authentication

## Cross-Reference Dependencies

This session semantics model integrates with:
- token-semantics.md for token lifecycle and revocation
- credential-model.md for credential lifecycle and downstream impact
- fail-closed-session-rules.md for edge case handling
- Phase 30 trust-boundaries.md for trust boundary enforcement
- Phase 30 authentication-semantics.md for authentication state management
- Phase 30 identity-model.md for identity binding and tenant rules
- Phase 29 permission semantics for authorization evaluation
- Phase 28 runtime context semantics for execution context requirements
