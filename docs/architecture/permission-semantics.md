# Permission Semantics

## Core Permission Principle

Permission is a decision, not a property. Actors have capabilities. System decides permission at request time. Decision is always contextual and state-aware.

## Permission Evaluation Inputs

Every permission decision MUST consider all of the following:

### Actor
Identity and type: human user, system process, or future AI operator (controlled).

### Capability
Exact capability name being requested. No wildcard matching.

### Execution Context
One of the defined contexts from Phase 28: Authoring, Runtime/Public, System/Background, or Governance.

### Resource State
Current state of resource: Draft, Published, Archived, or Locked.

### Tenant Scope
Actor tenant and resource tenant must match.

## Decision Matrix

### Rule 1 — Capability Presence

Actor MUST possess requested capability. Capability MUST be tenant-scoped to same tenant. Missing capability results in deny.

### Rule 2 — Context Compatibility

Each capability is valid only in explicit contexts. Examples: content.read is valid in Authoring and Runtime/Public for published content, but not valid in Runtime/Public for draft content. Context mismatch results in deny.

### Rule 3 — Resource State Compatibility

Capability validity depends on resource state. Examples: content.edit is valid for Draft resources, but not for Published resources. Invalid state transition results in deny.

### Rule 4 — Tenant Continuity

Actor tenant MUST equal resource tenant. No cross-tenant evaluation allowed. Tenant mismatch results in deny.

## Fail-Closed Evaluation Order

Permission evaluation MUST follow this order:

1. Resolve context
2. Resolve tenant
3. Verify capability presence
4. Verify context compatibility
5. Verify resource state compatibility
6. Emit decision + evidence

If evaluation stops early, deny.

## Governance as Negative-Only

Governance may observe and deny, but never allow. Governance never grants positive permissions. System processes operate only within explicitly granted capabilities.

## Audit & Evidence

Every permission decision must be deterministic, explainable, and auditable. Evidence must record: actor, capability, context, resource state, decision, and timestamp. Storage and logging deferred to later phases.
