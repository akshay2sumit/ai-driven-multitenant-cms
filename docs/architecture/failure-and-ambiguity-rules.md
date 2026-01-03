# Failure and Ambiguity Rules

## Failure Classes

### Failure Class A — Context Resolution Failure

#### Examples
- Context not declared at request initiation
- Multiple contexts inferred or ambiguous
- Context mismatch with entry point characteristics
- Context cannot be determined within allowed boundaries

#### System Response
Deny execution immediately. Emit audit evidence. Do not attempt fallback or retry.

### Failure Class B — Tenant Resolution Failure

#### Examples
- Missing tenant identifier in request
- Conflicting tenant sources in same request
- Late tenant binding attempted after execution start
- Tenant identifier cannot be resolved or is invalid

#### System Response
Deny execution. Do not retry. Record violation with specific tenant resolution failure details.

### Failure Class C — Boundary Violation

#### Examples
- Runtime context attempting write operations
- Governance context attempting mutations
- Cross-context access attempts
- Operations exceeding context scope

#### System Response
Hard deny. Mandatory audit emission. Mark as policy violation. No silent bypass allowed.

### Failure Class D — Transition Preconditions Not Met

#### Examples
- Publish action without required approvals
- System job without explicit scope definition
- Missing invariants for allowed transition
- Context transition without proper authorization

#### System Response
Transition denied. State remains unchanged. Explicit failure surfaced to requester with specific precondition details.

## Ordering Rules

### Rule 1 — Causality First

#### Rule Statement
Transitions and operations MUST respect established causal order: state order, version order, and approval order.

#### Enforcement
Out-of-order requests MUST be denied. System MUST not infer or correct order automatically.

#### Fail-Closed Behavior
Out-of-order request results in immediate denial and audit recording.

### Rule 2 — Idempotency Is Explicit

#### Rule Statement
System MUST explicitly know whether an operation is safely repeatable or forbidden to repeat.

#### Enforcement
No silent deduplication. No inferred retries. Repeat safety MUST be declared by operation type.

#### Fail-Closed Behavior
Ambiguous repeat safety results in denial. System MUST not assume repeat safety.

### Rule 3 — No Temporal Guessing

#### Rule Statement
System MUST not assume "this probably already happened" or "this will happen soon" without explicit evidence.

#### Enforcement
All temporal logic MUST be based on explicit timestamps and documented state, not inference or prediction.

#### Fail-Closed Behavior
Temporal ambiguity results in denial. System MUST not guess temporal intent.

## Ambiguity Handling

### Ambiguity Sources

#### Multiple Tenant Signals
Conflicting or multiple tenant identifiers in same request or operation.

#### Partial State
Incomplete or inconsistent state information that prevents clear decision-making.

#### Mixed Context Signals
Indications that operation spans multiple contexts without explicit transition.

#### Unclear Caller Intent
Request or operation where human or system intent cannot be determined.

### Unified Response Pattern

#### Stop Execution
All operations MUST stop immediately when ambiguity is detected.

#### Preserve Current State
System MUST NOT modify any state when ambiguity exists.

#### Emit Evidence
Audit evidence MUST record ambiguity detection, context, and decision to deny.

#### Require Explicit Retry
Retry MUST be allowed only with clarified, unambiguous request. No automatic retries.

## Core Safety Principle

Ambiguity is a failure condition, not a usability concern.

If the system is unsure about any aspect of execution—context, tenant, permissions, or intent—the system MUST stop and deny. No implicit allow paths, no convenience shortcuts, no smart guessing are permitted.

## Audit and Evidence Requirements

Every failure or ambiguity handling MUST be:

### Attributable
Clear identification of which context, rule, or boundary caused the failure.

### Classifiable
Specific failure class and rule that was violated.

### Tenant-Bound
All failure handling MUST preserve and record tenant context.

### Non-Destructive
Failure handling MUST NOT cause additional state changes or data corruption.

## Documentation References

This document implements the failure and ambiguity semantics defined in Phase 27 and provides specific rules for runtime boundary enforcement. All failure classes reference the audit evidence requirements established in Phase 27.
