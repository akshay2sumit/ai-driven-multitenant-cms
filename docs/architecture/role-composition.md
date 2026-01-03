# Role Composition

## Core Role Principle

Roles are tenant-scoped capability bundles with no authority of their own. Roles never bypass capabilities, context rules (Phase 28), or tenant isolation. Roles are data, not logic. Roles are assigned explicitly to actors.

## Role Composition Rules

### Rule 1 — Roles Contain Only Capabilities

A role is a named list of capabilities. No conditions, no logic, no workflows. Roles must be explicit and auditable.

### Rule 2 — Tenant-Scoped Roles Only

Every role belongs to exactly one tenant. Same role name in two tenants does not equal same authority. No global roles. No shared role registry across tenants.

### Rule 3 — Explicit Assignment Only

Roles are assigned explicitly to actors. Assignment requires assigner identity, tenant match, capability subset rule, and audit emission. No automatic role assignment. No inheritance by creation.

### Rule 4 — Least-Privilege Composition

Roles should contain minimum necessary capabilities. "Convenience roles" are discouraged. If a role grows too powerful, split it into smaller, focused roles.

### Rule 5 — No Role Escalation

Actors cannot assign roles they do not hold authority for. Cannot grant higher privilege than own. No self-promotion. No circular grants.

## Role Types

### Functional Roles

#### Editor
Content-focused capabilities: content.read, content.write, content.edit. Limited to content operations only.

#### Publisher
Content publication capabilities: content.read, content.write, content.edit, content.publish. Can transition content to public state.

#### Viewer
Read-only capabilities: content.read for published content only.

### Administrative Roles

#### Tenant Administrator
Full tenant management: tenant.view, tenant.configure, tenant.admin, plus all content capabilities. Cannot bypass governance.

### System Roles

#### System Operator
System maintenance: system.observe, system.maintain. Highly restricted, never human-assignable.

#### System Auditor
Governance operations: governance.observe, governance.audit. Read-only with respect to business state.

## Assignment Invariants

For any role assignment: same tenant, assigner has authority, capability subset rule holds, audit evidence emitted. If any invariant fails, deny assignment.

## Explicitly Forbidden Role Patterns

No cross-tenant role grants. No global super-admin role. No hidden default roles. No AI-created roles.
