# Trust, Identity and Access Semantics

## Purpose & Authority

This document defines binding rules for trust, identity, authority, and access. This document constrains schemas, APIs, authentication mechanisms, authorization logic, runtime enforcement, and AI behavior. These rules are constitutional and cannot be overridden by implementation or convenience.

## Identity & Actor Taxonomy

### Human User
A Human User is a real person acting within a tenant context. Human Users are always associated with exactly one tenant. Human Users act only through assigned roles and permissions. Human Users are subject to governance and audit. Human Users have no inherent authority by existence. Authority is granted, not assumed.

### Tenant Administrator
A Tenant Administrator is a Human User acting under a privileged role within a tenant. Tenant Administrators have elevated permissions within the tenant boundary. Tenant Administrators have no authority outside the tenant. Tenant Administrators cannot bypass governance. Tenant Administrators cannot alter system-owned or cross-tenant state.

### System Actor
A System Actor represents the platform itself performing governed operations. System Actors act only under explicit governance rules. System Actors perform maintenance, enforcement, and automation. System Actors cannot violate invariants. System Actors cannot act outside defined lifecycle or trust boundaries.

### Service Identity
A Service Identity is a non-human actor representing an internal or external service. Service Identities are scoped to a specific purpose. Service Identities operate under least-privilege principles. Service Identities have no human-equivalent privileges. Service Identities have no implicit tenant-wide access.

### AI-Assisted Actor
An AI-Assisted Actor is an AI system providing suggestions or drafts under human supervision. AI-Assisted Actors cannot initiate actions independently. AI-Assisted Actors require explicit human approval for any effect. AI-Assisted Actors have no decision-making authority. AI-Assisted Actors have no self-authorization or delegation.

### Governance Authority
The Governance Authority is the constitutional authority that defines, approves, and enforces rules. The Governance Authority exists outside normal tenant roles. The Governance Authority controls phases, scope, and constraints. The Governance Authority is bound by its own documented rules. The Governance Authority acts transparently and auditable.

## Trust Boundaries & Authority

### Tenant Boundary
The Tenant Boundary is the hard separation between one tenant and all others. No actor trusted in one tenant is trusted in another by default. Authority never crosses tenants without explicit governance action.

### Authoring vs Runtime Boundary
The Authoring vs Runtime Boundary is the separation between mutation and delivery. Runtime is never trusted to mutate state. Runtime actors have read-only authority.

### Governance Boundary
The Governance Boundary is the separation between governed rules and execution. Execution is never trusted to redefine rules. Only governance may authorize phases, scope, or exceptions.

### Human vs System Boundary
The Human vs System Boundary is the separation between human intent and automated execution. System actors execute rules; they do not invent intent. System actors cannot exceed encoded governance.

### AI Boundary
The AI Boundary is the separation between assistance and authority. AI is never trusted as an authority. AI cannot approve, deploy, or override decisions.

### External Boundary
The External Boundary is the separation between the platform and external systems/services. External systems are untrusted by default. External access is least-privilege and explicitly scoped.

## Roles, Permissions & Delegation Semantics

### Role Definition and Constraints
A Role is a named authority profile that groups permissions for a specific context. Roles do not contain business logic. Roles do not imply identity. Roles are tenant-scoped unless explicitly governed otherwise. Roles exist only within a tenant context. A user may hold multiple roles. Roles do not grant authority outside their scope. Role assignment is governed and auditable. Role ambiguity triggers STOP.

### Permission Definition and Constraints
A Permission is an explicit allowance to perform a specific class of action on a defined scope. Permissions are atomic. Permissions are explicit, never implied. Absence of permission equals denial. Permissions define what can be done, not how. Permissions are evaluated at action time. Permissions never bypass governance or boundaries. Permissions do not cross tenant boundaries. Implicit permissions are forbidden.

### Delegation Rules, Limits, and Revocation
Delegation is the temporary granting of a subset of authority from one actor to another. Delegation must be explicitly granted. Delegation scope must be narrower than the grantor's authority. Delegation has a defined duration or condition. Delegation is non-transitive by default. Delegation cannot override invariants. Delegation ambiguity triggers STOP.

### Explicitly Forbidden Patterns
"Admin means everything" is forbidden. Implicit privilege escalation is forbidden. Permanent delegation without governance is forbidden. Cross-tenant role inheritance is forbidden. AI-held roles or permissions are forbidden.

## Authentication vs Authorization Semantics

### Authentication Meaning and Limits
Authentication is the process of proving identity. Authentication establishes that the actor's identity is verified, the actor belongs to a known category, and the actor is associated with a tenant context if applicable. Authentication does not establish permission to perform actions, authority level, role membership, or trust beyond identity proof. Successful authentication never implies authorization.

### Authorization Meaning and Limits
Authorization is the process of deciding whether an authenticated actor may perform a specific action. Authorization establishes whether an action is allowed at this moment based on roles, permissions, delegation, trust boundaries, and governance rules. Authorization does not establish identity proof, authentication validity, or long-term trust. Authorization is evaluated per action, not per session.

### Mandatory Separation Rules
Authentication must occur before authorization. Authorization must be evaluated after authentication. Authentication success without authorization results in DENY. Authorization without authentication is INVALID. Cached authorization decisions must respect context and time.

### Failure and Ambiguity Handling
Authentication Failure means identity not proven, resulting in STOP/DENY. No authorization attempt is made. Authorization Failure means identity known but action not allowed, resulting in DENY. Event is auditable. Ambiguity in either stage results in STOP and requires governance review.

## Global STOP Conditions

Immediate STOP is required if any actor identity is ambiguous, if any authority cannot be determined, if any trust boundary is violated, if any unauthorized privilege escalation is attempted, or if any AI assumes authority without explicit delegation.
