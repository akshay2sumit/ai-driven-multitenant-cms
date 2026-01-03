# Authentication Semantics

## Authentication Meaning and Outcomes

Authentication is the process of verifying claimed identity through evidence. Authentication MUST produce a verifiable assertion that an entity is who it claims to be, based on presented proof.

Authentication outcomes MUST be deterministic and auditable.

## Authentication States

### Unauthenticated
The entity has not presented any identity evidence or has failed to provide valid proof. In this state:

- No identity claims are recognized
- All access MUST be denied
- Audit events MUST be generated for access attempts
- Session establishment MUST be blocked

### Authenticated
The entity has successfully proven its claimed identity. In this state:

- Identity is established but not tenant-bound
- Basic system access MAY be granted
- Tenant context resolution is REQUIRED for full access
- Audit trail MUST record authentication success

### Verified & Tenant-Bound
The entity has proven identity AND been bound to a specific tenant context. In this state:

- Full operational access within tenant scope
- All Phase 29 capabilities and permissions apply
- Tenant isolation MUST be enforced
- Cross-tenant access MUST be denied

### System-Trusted
The entity is a system identity with inherent trust. In this state:

- Bypasses normal authentication flows
- Operates with system-level privileges
- MUST be restricted to system operations only
- Audit trail MUST record all system-trusted actions

## Authentication vs Authorization Separation

Authentication and authorization MUST be separate concerns:

- **Authentication**: Verifying identity (who you are)
- **Authorization**: Granting permissions (what you can do)

Authentication MUST complete before authorization evaluation. Authentication failure MUST prevent authorization evaluation.

## Fail-Closed Authentication Rules

Authentication MUST follow fail-closed principles:

- Ambiguous authentication evidence MUST result in denial
- Partial authentication MUST be treated as failure
- Authentication timeouts MUST result in session termination
- Invalid credentials MUST trigger immediate denial
- Authentication failures MUST be rate-limited

## Audit and Evidence Emission Requirements

Authentication MUST emit comprehensive audit evidence:

- All authentication attempts MUST be logged
- Successful authentication MUST record identity, method, and timestamp
- Failed authentication MUST record reason, source, and timestamp
- Evidence MUST be tamper-evident and immutable
- Audit logs MUST support forensic analysis

Authentication evidence MUST include:

- Identity claim details
- Authentication method used
- Verification result
- Tenant binding outcome
- Timestamp and source information

## Cross-Reference Dependencies

This authentication semantics model builds upon:
- Phase 28 runtime context semantics for execution environment
- Phase 29 permission and capability semantics for post-authentication access
- identity-model.md for identity definition and taxonomy
- trust-boundaries.md for boundary crossing rules
- fail-closed-authentication-rules.md for failure handling specifics
