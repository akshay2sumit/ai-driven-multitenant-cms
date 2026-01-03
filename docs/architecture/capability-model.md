# Capability Model

## Core Access Principle

Access is capability-based, not role-based. Capabilities define what can be done. Roles are collections of capabilities. Permissions are evaluated at runtime using context, tenant scope, capability presence, and no implicit access. No inheritance by assumption.

## Definitions

### Actor

Any entity that can request an action. Actors include human users, system processes, and future AI operators (controlled). Actors do not have permissions directly.

### Capability

A single, atomic right to perform a specific class of action. Capabilities are tenant-scoped, context-aware, and explicitly granted. No wildcard capabilities.

### Role

A named bundle of capabilities. Roles have no logic, do not bypass context rules, and do not bypass tenant isolation. Removing a capability from a role immediately reduces power.

### Permission Evaluation

A runtime decision answering: "Is this actor allowed to perform this action here and now?" Inputs include actor, capability, execution context (Phase 28), tenant binding, and resource state. Missing capability results in deny. Ambiguous capability results in deny. Context mismatch results in deny. Tenant mismatch results in deny. No soft failures.

## Capability Taxonomy

### Content Domain

#### content.read
Read content within tenant scope. Valid in authoring and runtime/public contexts for published content only.

#### content.write
Create or modify content. Valid only in authoring context. Forbidden in runtime/public context.

#### content.edit
Modify existing content. Valid only in authoring context. Forbidden for published content in runtime/public context.

#### content.publish
Transition content from draft to published state. Valid only in authoring context with explicit publish action.

#### content.archive
Move content to archived state. Valid in authoring and system contexts with appropriate scope.

### Tenant Domain

#### tenant.view
View tenant configuration and metadata. Valid in authoring context for tenant-scoped operations only.

#### tenant.configure
Modify tenant configuration. Valid only in authoring context with administrative permissions.

#### tenant.admin
Full tenant administration. Valid only in authoring context with highest tenant permissions.

### System Domain

#### system.observe
Read system operational data and metrics. Valid only in system/background and governance contexts.

#### system.maintain
Perform system maintenance operations. Valid only in system/background context with explicit scope.

#### system.configure
Modify system configuration. Highly restricted, valid only in system/background context with explicit authority.

### Governance Domain

#### governance.observe
Observe system for compliance and audit. Valid only in governance context.

#### governance.audit
Perform audit operations and evidence collection. Valid only in governance context.

## Capability Scoping Rules

Capabilities are tenant-scoped. Same capability in two tenants does not grant cross-tenant access. Capabilities are context-aware. Capability validity depends on execution context as defined in Phase 28. Capabilities are explicitly granted, never assumed.

## Explicit Non-Goals

No super-admin bypass. No implicit role inheritance. No "owner can do everything" assumption. No AI self-granted capabilities.
