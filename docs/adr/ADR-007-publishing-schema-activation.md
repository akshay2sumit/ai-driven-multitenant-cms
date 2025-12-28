# ADR-007: Publishing Schema Activation

## Status
**Proposed** (2024-12-29)

## Context
This ADR defines the conceptual model for publishable content and its lifecycle in our multi-tenant CMS, focusing on design aspects only.

## Decision

### What is "Publishable Content" (Conceptual)
Publishable content refers to any content entity that can go through a formal publishing workflow. This is a content modeling concept that will be implemented in future phases.

### Publishing Lifecycle
1. **Draft**: Initial state of new content
2. **Review**: Content is ready for editorial review
3. **Published**: Content is live and visible according to visibility rules
4. **Archived**: Content is preserved but not publicly visible
5. **Retracted**: Content is removed from public view (soft delete)

### Read-Only Guarantees
- PublishingRuntime will provide a read-only view of published content
- No write operations will be exposed through the PublishingRuntime interface
- All published content will be immutable through the PublishingRuntime

### Tenant Isolation
- Each tenant's published content is fully isolated
- Cross-tenant content access is strictly controlled and explicit
- Publishing operations in one tenant cannot affect another tenant's content

### Implementation Note
**NO SCHEMA IMPLEMENTATION** is included in this phase. This ADR is purely for design and documentation purposes.

## Consequences
- Establishes clear boundaries for future implementation
- Ensures tenant isolation is considered in the design phase
- Provides a foundation for implementing the publishing workflow in later phases
