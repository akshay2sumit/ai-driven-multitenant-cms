# Context Transitions

## Allowed Transitions

### Authoring → Runtime (Publish Boundary)

#### Meaning
Content authored becomes publicly readable through explicit publish action.

#### Characteristics
One-way transition requiring explicit user intent. Versioned and state-based. Tenant-bound throughout.

#### Explicit Trigger Requirement
User-initiated publish action with satisfied preconditions: content validation, approval if required, and tenant authorization.

#### Tenant Continuity Requirement
Tenant identifier MUST remain identical throughout transition. No cross-tenant publication is permitted.

#### Preconditions
- Content must be in valid published state
- All required validations must pass
- Publish permissions must be verified
- Tenant isolation must be preserved

#### Audit Emission Requirement
Evidence MUST record: transition trigger, content identifiers, tenant context, timestamp, and authorization outcome.

### Authoring → System (Maintenance Invocation)

#### Meaning
User-triggered background work such as reindexing or cleanup operations.

#### Characteristics
User-initiated, narrow scope, time-bounded, and tenant-aware.

#### Explicit Trigger Requirement
Direct user request or approved user action requiring system maintenance.

#### Tenant Continuity Requirement
Operation MUST be scoped to requesting tenant only. No system-wide actions without explicit approval.

#### Preconditions
- User must have appropriate permissions
- Operation scope must be clearly defined
- Tenant context must be established
- Time bounds must be specified

#### Audit Emission Requirement
Evidence MUST record: user identity, operation type, tenant scope, initiation timestamp, and completion status.

### System → Runtime (Materialization Boundary)

#### Meaning
System prepares optimized, read-only artifacts for public delivery.

#### Characteristics
Deterministic, no business decision-making, tenant-aware, no visibility change.

#### Explicit Trigger Requirement
Scheduled system job or explicit materialization request with defined scope.

#### Tenant Continuity Requirement
Artifacts MUST be tenant-isolated. No cross-tenant materialization is permitted.

#### Preconditions
- Source data must be valid and tenant-scoped
- Materialization scope must be explicitly defined
- Output format must be approved
- Runtime context must be prepared

#### Audit Emission Requirement
Evidence MUST record: system job identifier, source data references, tenant scope, materialization timestamp, and output locations.

### Any → Governance (Observation Only)

#### Meaning
Execution emits evidence for audit validation or compliance verification.

#### Characteristics
Passive, read-only, non-blocking except for enforcement actions.

#### Explicit Trigger Requirement
Policy violation detection, compliance audit request, or integrity verification requirement.

#### Tenant Continuity Requirement
Observation MUST preserve tenant context. Evidence MUST be tenant-attributable.

#### Preconditions
- Valid context must exist to observe
- Observation scope must be defined
- Evidence storage must be available
- Audit policy must be applicable

#### Audit Emission Requirement
Evidence MUST record: observation trigger, context identifier, tenant reference, findings, and timestamp.

## Forbidden Transitions

### Runtime → Authoring
FORBIDDEN. Runtime context may not initiate content creation or modification.

### Runtime → System
FORBIDDEN. Runtime context may not trigger system maintenance or background jobs.

### Governance → Any
FORBIDDEN. Governance context may not transition to any other context or trigger business actions.

### Authoring → Governance (as Controller)
FORBIDDEN. Authoring context may not directly invoke governance actions except through defined observation pathways.

### Public-Triggered System Escalation
FORBIDDEN. Public requests may not trigger system context transitions or background jobs.

### Any Implicit or Inferred Transition
FORBIDDEN. All transitions must be explicit, documented, and auditable. No automatic or inferred transitions are permitted.

## Transition Requirements

### Explicit Trigger
Every transition MUST be triggered by explicit human action or documented system rule. No implicit or automatic transitions are allowed.

### Declared Source & Target Context
Transition MUST specify exact source context and target context. Ambiguous or undefined transitions are forbidden.

### Tenant Continuity
Tenant identifier MUST remain identical throughout transition. Cross-tenant transitions are architecturally impossible.

### Audit Emission
Every transition MUST emit audit evidence containing: transition identifier, source context, target context, tenant context, timestamp, and authorization outcome.

### Reversibility Rules
If transition is reversible, reversal MUST be explicit and follow same transition requirements. If irreversible (such as publish), irreversibility MUST be documented.

### Fail-Closed Behavior
Missing any transition requirement MUST result in denial of transition. State MUST remain unchanged and violation MUST be recorded.
