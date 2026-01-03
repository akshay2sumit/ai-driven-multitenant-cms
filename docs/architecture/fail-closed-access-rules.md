# Fail-Closed Access Rules

## Core Fail-Closed Principle

Access control fails closed, never open. Missing data, conflicting data, or stale data results in deny. No retries that auto-allow. No grace windows. No "user experience" shortcuts.

## Fail-Closed Access Rules

### Rule 1 — Ambiguous Capability Resolution

#### Case
Actor has multiple roles, capability presence unclear or duplicated, or capability definition is ambiguous.

#### Decision
Evaluate strictly. If capability cannot be proven once, clearly deny.

### Rule 2 — Concurrent Role or Capability Changes

#### Case
Role revoked while request is in-flight, or capability added/removed concurrently.

#### Decision
Re-evaluate at decision point. If state changed mid-evaluation, deny. No optimistic locking for access.

### Rule 3 — Stale Authorization Data

#### Case
Cached roles or capabilities, delayed synchronization, or freshness cannot be guaranteed.

#### Decision
If freshness cannot be guaranteed, deny. Freshness is mandatory for allow.

### Rule 4 — Partial Authorization

#### Case
Action requires multiple capabilities but only some are present.

#### Decision
Deny entire action. No partial success. Example: Publish requires content.edit and content.publish. Missing one results in deny.

### Rule 5 — Revocation Timing

#### Case
Capability revoked after action started, or decision not yet finalized.

#### Decision
If decision not yet finalized, deny. If finalized and committed, treat as completed action (audited). No retroactive rollback here.

### Rule 6 — Cross-Context Drift

#### Case
Context changes during request. Example: Authoring → Runtime during content modification.

#### Decision
Re-evaluate permissions. If mismatch detected, deny. Context stability is required.

### Rule 7 — Resource State Mutation During Evaluation

#### Case
Resource state changes while permission is being evaluated. Example: Draft → Published during edit check.

#### Decision
If state mismatch detected, deny. Require explicit retry with new state.

## Explicitly Forbidden Edge Handling

No "allow but warn". No "allow once". No "allow until refresh". No "allow with degraded access". No UI-based override. No silent downgrade.

## Audit & Evidence

Every fail-closed denial must record: which rule triggered, actor identity, capability requested, context snapshot, resource state, decision, and timestamp. This makes denial explainable, not opaque.
