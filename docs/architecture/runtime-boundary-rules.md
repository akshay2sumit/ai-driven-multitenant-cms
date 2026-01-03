# Runtime Boundary Rules

## Rule 1 — Explicit Context Declaration

### Rule Statement
Every execution MUST belong to exactly one defined context: Authoring, Runtime/Public, System/Background, or Governance.

### Enforcement
Context MUST be determined at request initiation and MUST remain constant throughout execution.

### Fail-Closed Behavior
If context cannot be determined or multiple contexts are inferred, execution MUST be denied immediately.

## Rule 2 — Mandatory Tenant Binding

### Rule Statement
Every request or process MUST have a resolved tenant identifier bound at the earliest possible boundary.

### Enforcement
Tenant binding MUST occur before any business logic execution. Late binding or inferred tenant identification is forbidden.

### Fail-Closed Behavior
If tenant is missing, ambiguous, or cannot be resolved, execution MUST be denied immediately.

## Rule 3 — Write Operations Are Context-Locked

### Rule Statement
Write operations are allowed ONLY in Authoring Context and System Context (for maintenance only).

### Enforcement
Runtime/Public Context MUST NEVER write. Governance Context MUST NEVER write. System Context writes MUST be maintenance-only and tenant-aware.

### Fail-Closed Behavior
If write operation is attempted from Runtime/Public or Governance context, execution MUST be denied and audit event MUST be generated.

## Rule 4 — Read Scope Is Context-Limited

### Rule Statement
Read operations MUST respect context-specific scope limitations.

### Enforcement
Runtime/Public Context MAY read published content only. Authoring Context MAY read drafts and published content within tenant scope. System Context MAY read operational data only. Governance Context MAY read metadata and evidence only.

### Fail-Closed Behavior
If read operation exceeds context scope or attempts cross-tenant access, execution MUST be denied immediately.

## Rule 5 — Context Escalation Is Forbidden

### Rule Statement
No context MAY upgrade itself or another context without explicit, documented transition.

### Enforcement
Context transitions MUST occur only through defined transition events. Implicit escalation, privilege elevation, or context switching is forbidden.

### Fail-Closed Behavior
Any attempt at context escalation MUST be denied immediately and recorded as security violation.

## Rule 6 — Public Entry Points Are Runtime-Only

### Rule Statement
Any public-facing entry point MUST execute strictly in Runtime/Public context.

### Enforcement
Public endpoints MUST be read-only, tenant-isolated, and MUST NOT contain administrative logic or conditional write capabilities.

### Fail-Closed Behavior
If public entry attempts non-runtime behavior or write operations, execution MUST be denied immediately.

## Rule 7 — Governance Is Observer-Only

### Rule Statement
Governance Context MUST observe, validate, and record only.

### Enforcement
Governance Context MUST NEVER mutate business state, modify content, or alter tenant configurations. Governance MUST emit evidence for all observations.

### Fail-Closed Behavior
If Governance attempts any mutation operation, execution MUST be denied and critical violation MUST be recorded.
