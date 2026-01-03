# Token Semantics

## Token as Time-Bounded Representation of Trust

A token is a temporary, time-bounded assertion of trust issued after successful authentication. Tokens represent that an identity was verified at a specific time and trust level, but do not themselves grant permissions or capabilities.

### Core Characteristics

- **Time-bounded**: Tokens have explicit validity windows
- **Derived**: Tokens are issued from valid credentials through authentication
- **Revocable**: Tokens can be invalidated before expiry
- **Context-aware**: Tokens are bound to specific execution contexts
- **Tenant-bound**: Tokens operate within single tenant boundaries

### Critical Distinctions

- **Token ≠ Identity**: Tokens represent authenticated identity, not the identity itself
- **Token ≠ Permission**: Tokens do not grant capabilities or permissions
- **Token ≠ Session**: Tokens may participate in sessions but are not sessions themselves

## Token States

### Issued
The token has been created after successful authentication and is ready for use.

**Properties:**
- Bound to specific identity and tenant
- Has defined validity window (start time, end time)
- Associated with trust level from authentication
- Not yet used for any operations

**Transitions:**
- Automatically transitions to Active when validity window begins
- Can transition to Revoked before becoming Active

### Active
The token is currently within its validity window and has not been revoked.

**Properties:**
- Current time is within validity window
- Token has not been revoked
- Eligible to participate in session continuity
- May be used for authorization evaluation (as identity proof)

**Transitions:**
- Transitions to Expired when validity window ends
- Transitions to Revoked if explicitly invalidated
- May transition to Suspended for temporary disablement (policy-dependent)

### Expired
The token's validity window has ended and it can no longer be used.

**Properties:**
- Validity window has passed
- Cannot be used for authentication continuation
- Cannot participate in authorization evaluation
- Must be treated as permanently invalid

**Transitions:**
- Final state - no further transitions allowed
- Expired tokens cannot be reactivated or renewed

### Revoked
The token has been explicitly invalidated before natural expiry.

**Properties:**
- Explicitly invalidated by authority
- Takes precedence over expiry status
- Irreversible - cannot be unrevoked
- Must trigger session termination

**Transitions:**
- Final state - no further transitions allowed

## Validity vs Expiry vs Revocation

### Decision Order (Fail-Closed)
Token evaluation must follow this exact decision sequence:

1. **If revoked → DENY**: Revocation always takes precedence
2. **Else if expired → DENY**: Expired tokens are always denied
3. **Else if invalid → DENY**: Structural or format failures are denied
4. **Else → MAY CONTINUE**: Token may participate in authorization evaluation

### Validity
Structural and format acceptability:
- Token format is correct
- Signature or integrity is valid (conceptual)
- Required fields are present and valid
- Token is not malformed

### Expiry
Time-based validity:
- Current time is within the defined window
- Not before the start time
- Not after the end time
- No implicit extensions or renewals

### Revocation
Authority-based invalidation:
- Explicit revocation by authorized source
- Takes immediate effect
- Overrides expiry and validity
- Irreversible once applied

## Token Misuse Cases

### Token Replay
**Meaning**: Using a token outside its intended continuity or context.

**Response**:
- Immediate denial
- Require re-authentication
- Log as potential security event
- Invalidate associated session continuity

### Token Used After Expiry
**Meaning**: Attempting to use a token after its validity window has ended.

**Response**:
- Immediate denial
- No soft revalidation or grace periods
- Require fresh authentication
- Log the expired usage attempt

### Token Used After Credential Revocation
**Meaning**: Using a token derived from a credential that has been revoked.

**Response**:
- Immediate denial
- Treat as security incident
- Invalidate all related tokens and sessions
- Require re-authentication with valid credential

### Token Used Across Tenants
**Meaning**: Attempting to use a token in a different tenant context.

**Response**:
- Immediate denial
- Log as tenant boundary violation
- Invalidate the token
- Require tenant-appropriate authentication

## Revocation Precedence Over Expiry

### Core Principle
Revocation always takes precedence over natural expiry:

- **Revoked tokens are dead immediately**, regardless of remaining validity
- **No grace periods** for revoked tokens
- **No delayed enforcement** of revocation
- **Complete downstream impact** on sessions and authorizations

### Revocation Triggers
Tokens may be revoked due to:

- **User action**: Explicit logout or security concern
- **Admin action**: Administrative security measures
- **System policy**: Automated threat detection
- **Governance enforcement**: Compliance requirements
- **Credential revocation**: Downstream invalidation

## Audit and Evidence Emission

Token lifecycle events must emit comprehensive audit evidence:

### Required Evidence Fields
- **Token identifier**: Opaque identifier (never the actual token)
- **Identity**: The authenticated identity represented
- **Tenant**: The tenant context for the token
- **State change**: Issued / Active / Expired / Revoked
- **Timestamp**: Precise time of the event
- **Reason**: Justification for revocation (if applicable)
- **Source**: User, admin, system, or governance action

### Event Types to Audit
- Token issuance after authentication
- State transitions (Active, Expired, Revoked)
- Revocation events and downstream impact
- Token usage in authorization attempts
- Security violations and misuse attempts

## Explicitly Forbidden Token Behaviors

### Prohibited Behaviors
- **Implicit renewal**: Tokens must not automatically extend their validity
- **Sliding expiry**: Validity windows must not shift based on usage
- **Token-based permission grants**: Tokens must not directly grant capabilities
- **Cross-tenant token reuse**: Tokens must not work across tenant boundaries
- **Survival after credential revocation**: Tokens must die when credentials die

### No Refresh Logic
- No automatic token refresh mechanisms
- No silent token renewal
- No background token extension
- No persistent login through token renewal

## Core Principles

1. **Tokens are temporary assertions**: Not permanent rights
2. **Revocation always wins**: Revoked tokens are always denied
3. **Time boundaries are strict**: No implicit extensions
4. **Context is enforced**: Tokens work only in intended contexts
5. **No permission grants**: Tokens only represent authenticated identity

## Cross-Reference Dependencies

This token semantics model integrates with:
- credential-model.md for credential lifecycle and revocation
- session-semantics.md for session continuity and termination
- Phase 30 identity-model.md for identity definition and binding
- Phase 30 authentication-semantics.md for authentication state management
- Phase 30 trust-boundaries.md for trust boundary enforcement
- Phase 29 permission semantics for authorization evaluation
- Phase 28 runtime context semantics for execution context requirements
