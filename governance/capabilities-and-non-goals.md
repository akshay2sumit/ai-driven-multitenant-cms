# Capabilities and Non-Goals

## Purpose & Authority

This document defines the system's allowed capability domains and explicit non-goals. This document constrains features, AI behavior, and future phases. This is binding governance that cannot be overridden by implementation, features, or AI behavior.

## Capability Taxonomy

### Content & Knowledge Authoring
Allow structured creation, organization, and governance of content and knowledge.

### Multi-Tenant Governance & Isolation
Allow multiple tenants to operate safely on a shared platform.

### Controlled Runtime Rendering
Allow safe, read-only delivery of tenant content.

### Governance-Driven Configuration
Allow system behavior to be adjusted through governed configuration, not ad-hoc code.

### Observability & Auditability
Allow visibility into system behavior without compromising safety.

### Assisted Intelligence (Controlled AI)
Allow AI to assist humans within strict limits.

### Extensibility by Design (Not Plugins)
Allow future capability expansion only through governed phases.

## Explicit Non-Goals

### No Plugin Marketplace or Third-Party Extensions
The system will not support a plugin marketplace. No third-party code execution inside the core.

### No Autonomous AI Behavior
AI will not act autonomously. AI will not self-improve, self-deploy, or self-authorize actions.

### No Low-Code / No-Code App Builder
The system is not a generic app builder. No drag-and-drop logic engines or citizen-developer scripting.

### No Real-Time Collaborative Editing (Initially)
No Google Docs–style real-time co-editing.

### No Direct Database or Infrastructure Access
Users will never access raw databases, servers, or infrastructure controls.

### No Public Admin or Debug Interfaces
No admin panels, debug tools, or diagnostics exposed publicly.

### No AI Training on Tenant Data by Default
Tenant data will not be used to train AI models implicitly.

### No WordPress-Parity Feature Race
The goal is not to copy WordPress, Wix, or similar platforms feature-by-feature.

### No Implicit Roadmaps or Promises
Capabilities do not imply timelines or guarantees. Documentation is not a roadmap.

### No Bypassing Governance for Speed
No "temporary hacks." No "just this once." No silent shortcuts.

## Capability-to-Boundary Alignment

All capabilities are allowed only because they respect system boundaries and invariants. Each capability exists to reinforce constitutional governance constraints.

All non-goals exist to protect system boundaries and invariants. Each non-goal prevents boundary erosion or violation.
