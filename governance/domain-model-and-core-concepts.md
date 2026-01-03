# Domain Model and Core Concepts

## Purpose & Authority

This document defines the canonical domain vocabulary, relationships, and invariants for the system. This document is binding across governance, design, and future implementation. No semantic drift is permitted.

## Core Domain Concepts (Vocabulary)

### Tenant
A Tenant is an isolated logical domain representing a single customer, organization, or project operating within the platform. Tenants own their data, configuration, and content. Tenants are isolated from all other tenants and have no implicit access to other tenants.

### User
A User is a human actor authenticated within a tenant context. Users always belong to exactly one tenant. Users have roles and permissions scoped to that tenant and cannot act outside their tenant boundary.

### Role
A Role is a named set of permissions defining what actions a user may perform within a tenant. Roles are tenant-scoped, declarative, and evaluated by governance rules.

### Content
Content is any structured information authored, managed, and governed by the system. Content exists in authoring context, is versioned and stateful, and is tenant-owned.

### Content Type
A Content Type defines the structure and semantics of a class of content. Content Types are declarative definitions that govern validation and rendering rules and do not imply schema implementation.

### Authoring Context
The Authoring Context is the controlled environment where content and configuration may be created or modified. The Authoring Context is authenticated and authorized, allows mutating operations, and is never publicly exposed.

### Runtime Context
The Runtime Context is the read-only environment where approved content is delivered. The Runtime Context is non-mutating, publicly accessible only when explicitly authorized, and has deterministic behavior.

### Configuration
Configuration is governed data that influences system behavior without changing core code. Configuration is versioned, validated, and subject to governance approval.

### Governance
Governance is the set of constitutional rules, processes, and authorities that constrain and guide the system. Governance supersedes features and execution, controls phases, scope, and authority, and is enforced at all times.

### Phase
A Phase is a bounded unit of work with a single objective, explicit authorization, and formal closure. Only one phase may be active at a time, phases are defined in the phase backbone, and phases are closed only after documentation and audit.

### Capability
A Capability is a class of allowed system behavior defined by governance. Capabilities are not features, not promises, and are constrained by boundaries and invariants.

### Invariant
An Invariant is a non-negotiable rule that must always hold true. Violations trigger STOP conditions, invariants are independent of implementation, and invariants are constitutionally binding.

### Boundary
A Boundary is a hard separation between domains of responsibility or behavior. Boundaries define allowed vs forbidden actions, protect invariants, and cannot be crossed without governance change.

## Concept Relationships (Conceptual)

### Tenant ↔ User
A Tenant contains Users. A User belongs to exactly one Tenant. Users cannot exist outside a tenant context. All identity, permissions, and actions are tenant-scoped.

### Tenant ↔ Content
A Tenant owns Content. Content cannot exist without a tenant. Content is never shared across tenants by default. Tenant isolation applies to all content lifecycle stages.

### Content ↔ Content Type
A Content instance conforms to exactly one Content Type. A Content Type governs structure and semantics, not storage. Content meaning is declarative and governed.

### Authoring Context ↔ Content
Content is created and modified only in the Authoring Context. The Authoring Context is authenticated and governed. All mutations are controlled and auditable.

### Runtime Context ↔ Content
The Runtime Context reads approved Content. The Runtime Context never mutates Content. Public access is safe, deterministic, and fail-closed.

### Configuration ↔ System Behavior
Configuration influences behavior without altering code. Configuration changes are governed and versioned. Behavioral flexibility exists without breaking core immutability.

### Governance ↔ Phases
Governance authorizes, sequences, and closes Phases. Phases cannot self-authorize or overlap. All work proceeds constitutionally.

### Governance ↔ Capabilities
Governance defines and constrains Capabilities. Capabilities do not expand without explicit governance action. Scope creep is structurally prevented.

### Invariants ↔ Boundaries
Boundaries exist to protect Invariants. Invariants define what must always be true. Boundaries are enforcement mechanisms, not suggestions.

### AI (Assisted Intelligence) ↔ Governance
AI operates within governance-defined limits. AI suggestions require human approval. AI remains a tool, never an authority.

## Concept Invariants

### Tenant — Invariants
A Tenant is always isolated from all other tenants. A Tenant owns its data, configuration, and content. No implicit cross-tenant access exists.

### User — Invariants
A User always belongs to exactly one Tenant. A User cannot act without an authenticated tenant context. User permissions are tenant-scoped and governed.

### Role — Invariants
Roles are defined per tenant. Roles grant permissions; they do not embed business logic. Role evaluation is governed and auditable.

### Content — Invariants
Content is tenant-owned and versioned. Content mutation occurs only in the Authoring Context. Content state transitions are governed.

### Content Type — Invariants
A Content Type defines meaning and rules, not storage. Content Types are declarative and governed. Changes to Content Types are versioned and auditable.

### Authoring Context — Invariants
The Authoring Context is authenticated and authorized. Mutations are allowed only here. The Authoring Context is never publicly exposed.

### Runtime Context — Invariants
The Runtime Context is read-only. Runtime behavior is deterministic and fail-closed. No admin, authoring, or debug actions exist in runtime.

### Configuration — Invariants
Configuration is governed, versioned, and validated. Configuration influences behavior without changing core code. Invalid configuration results in fail-closed behavior.

### Governance — Invariants
Governance supersedes features, execution, and AI. Governance authorizes phases, scope, and changes. Governance rules are always enforceable.

### Phase — Invariants
Only one phase may be active at any time. A Phase has a single objective. A Phase is closed only after documentation and audit.

### Capability — Invariants
Capabilities define allowed behavior classes, not features. Capabilities expand only via authorized phases. Capabilities are constrained by boundaries and invariants.

### Invariant — Invariants
Invariants are absolute and non-negotiable. Violations trigger mandatory STOP. Invariants exist independently of implementation.

### Boundary — Invariants
Boundaries define allowed vs forbidden actions. Boundaries exist to protect invariants. Boundaries cannot be crossed without governance change.
