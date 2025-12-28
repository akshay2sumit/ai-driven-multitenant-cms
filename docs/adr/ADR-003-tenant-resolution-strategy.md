# ADR-003: Tenant Resolution Strategy

## Status
**Accepted**  
*Date: 2025-12-26*  
*Governance Version: 1.3.1 LTS*

## Context

The system architecture baseline has been formally accepted and materialized (ADR-002).
The project is now entering runtime behavior phases, starting with multi-tenancy.

A core prerequisite for all future work (tenant context, data isolation, auth, modules, AI) is a deterministic, hosting-friendly tenant resolution mechanism.

Tenant resolution determines how the system identifies "which tenant is active" for a given request.

This decision must:
- Work reliably on shared / reseller hosting
- Integrate cleanly with the CodeIgniter 4 request lifecycle
- Avoid early coupling to infrastructure assumptions
- Be simple, explicit, and debuggable

## Decision

### Selected Option: Path-Based Tenant Resolution

The system SHALL resolve tenants using a path-based strategy as the primary and mandatory mechanism.

**Canonical Pattern:**
```
/t/{tenant_identifier}/...
```

**Examples:**
- `/t/acme/dashboard`
- `/t/clinic123/pages/home`

### Decision Rationale

This option best satisfies all architectural constraints:

✔️ Fully compatible with shared and reseller hosting  
✔️ No DNS, vhost, or server config requirements  
✔️ Simple CI4 routing and filter integration  
✔️ Explicit and transparent for debugging  
✔️ Easy to test locally  
✔️ Can coexist with future subdomain resolution if needed

## Architectural Implications

### 1. Tenant Identifier
- Tenant identifier is a string slug
- Appears only in routing and context resolution
- Must NOT be hard-coded into business logic

### 2. Request Lifecycle Placement
Tenant resolution SHALL occur:
- Early in the request lifecycle
- Before controllers or modules execute
- In a dedicated, isolated layer (Tenant Context Layer)

### 3. Failure Handling
- Missing tenant identifier → controlled error (no fallback)
- Invalid tenant identifier → tenant-not-found response
- No "default tenant" assumption at this stage

## Explicit Non-Decisions (Deferred)

This ADR intentionally does NOT decide:
- How tenants are stored (DB schema)
- How tenant validity is checked
- Authentication or authorization rules
- Tenant onboarding or provisioning
- Subdomain or API-based alternatives

## Consequences

### Positive
- Predictable and safe tenant resolution
- Minimal infrastructure assumptions
- Clean foundation for tenant isolation

### Constraints
- URLs are slightly more verbose
- All routing must respect the `/t/{tenant}` prefix

## Compliance
- [x] Follows .windsurfrules v1.3.1 LTS
- [x] No direct database access from controllers
- [x] No business logic in views
- [x] No framework modifications

## Related Documents
- [ADR-001: Environment Readiness](ADR-001-environment-readiness.md)
- [ADR-002: System Architecture Baseline](ADR-002-system-architecture-baseline.md)
