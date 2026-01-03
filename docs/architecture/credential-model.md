# Credential Model

## Credential Definition and Purpose

A credential is a long-lived proof of identity used to establish trust through authentication. Credentials are the foundation for identity verification but never directly grant permissions or access.

### Core Characteristics

- **Long-lived**: Credentials persist across multiple authentication sessions
- **Identity-bound**: Each credential is tied to exactly one identity
- **Revocable**: Credentials can be permanently invalidated at any time
- **Highly sensitive**: Credentials require explicit protection and careful handling
- **Authentication-only**: Credentials are used solely to prove identity, not authorize actions

## Credential Lifecycle States

### Created
The credential has been generated or registered but not yet used for authentication.

**Properties:**
- Credential exists in the system
- No trust has been established
- Not yet bound to any active sessions
- May be subject to initial validation

**Transitions:**
- Can transition to Active when validation completes
- Can transition to Revoked if initial validation fails

### Active
The credential is valid and can be used for authentication attempts.

**Properties:**
- Eligible for authentication use
- Subject to policy checks and rate limiting
- May be used to issue tokens
- Trusted only within defined contexts

**Transitions:**
- Can transition to Suspended for temporary disablement
- Can transition to Revoked for permanent invalidation

### Suspended
The credential is temporarily disabled and cannot be used for authentication.

**Properties:**
- Authentication attempts are denied
- Existing tokens and sessions may remain valid (policy-dependent)
- Can be reactivated to Active state
- Used for temporary security measures

**Transitions:**
- Can transition to Active when suspension is lifted
- Can transition to Revoked for permanent disablement

### Revoked
The credential is permanently invalid and cannot be reactivated.

**Properties:**
- All authentication attempts are permanently denied
- Revocation is irreversible
- Must trigger downstream invalidation
- Audit trail must record revocation details

**Transitions:**
- Final state - no further transitions allowed

## Credential → Authentication Relationship

Credentials serve as the input to authentication processes:

1. **Credential Presentation**: Entity presents credential to system
2. **Authentication Attempt**: System validates credential against identity
3. **Trust Establishment**: Successful authentication establishes trust
4. **Token Issuance**: Trust is represented through time-bounded tokens

**Critical Rule:** Credentials never participate directly in authorization decisions. They only enable authentication, which may then lead to authorization evaluation.

## Revocation Semantics

### Immediate Effect
Revocation takes effect immediately upon issuance:

- No grace period for credential usage
- No "until expiry" allowances
- All future authentication attempts are denied
- System must enforce revocation instantly

### Downstream Invalidation
Revoking a credential MUST invalidate all derived trust:

- **All active tokens** issued from the credential become invalid
- **All active sessions** derived from the credential must terminate
- **Future authentication** attempts using the credential are denied
- **Cached authorizations** based on the credential must be cleared

### Scope of Revocation
Revocation follows identity and tenant boundaries:

- **Identity-scoped**: Revocation affects the specific identity only
- **Tenant-bound**: Revocation applies within the tenant context unless explicitly global
- **No collateral damage**: Unrelated identities and tenants are unaffected
- **Complete coverage**: All trust derived from the credential is invalidated

### Revocation Sources
Revocation may originate from multiple sources but has identical effects:

- **User action**: Voluntary credential revocation
- **Admin action**: Administrative credential management
- **System policy**: Automated security responses
- **Governance enforcement**: Compliance and regulatory requirements

## Audit and Evidence Expectations

Credential lifecycle events must emit comprehensive audit evidence:

### Required Evidence Fields
- **Identity**: The entity associated with the credential
- **Tenant**: The tenant context for the credential
- **Credential identifier**: Opaque identifier (never the actual credential)
- **State transition**: Old state → New state
- **Source of change**: User, admin, system, or governance
- **Timestamp**: Precise time of the event
- **Reason**: Justification for state changes (especially revocation)

### Event Types to Audit
- Credential creation and registration
- State transitions (Active, Suspended, Revoked)
- Authentication attempts using the credential
- Revocation events and downstream impact
- Policy violations and security incidents

## Explicit Non-Goals

This credential model explicitly does NOT address:

- Password policies or complexity requirements
- Key formats or cryptographic specifications
- Multi-factor authentication mechanisms
- Credential storage or encryption methods
- Biometric authentication systems
- Third-party identity federation protocols
- Credential provisioning workflows

## Core Principles

1. **Credentials never grant permission**: Authentication ≠ Authorization
2. **Credentials are never used directly for authorization**: Only through issued tokens
3. **Revocation always beats validity**: Revoked credentials are always denied
4. **Immediate effect**: No grace periods for revocation enforcement
5. **Complete downstream impact**: Revocation invalidates all derived trust

## Cross-Reference Dependencies

This credential model integrates with:
- Phase 30 identity-model.md for identity definition and taxonomy
- Phase 30 authentication-semantics.md for authentication state definitions
- Phase 30 trust-boundaries.md for trust boundary enforcement
- token-semantics.md for token issuance and lifecycle
- session-semantics.md for session continuity rules
- Phase 29 permission semantics for post-authentication authorization
- Phase 28 runtime context semantics for execution environment boundaries
