# ADR-006: Public Rendering Strategy

## Status
Proposed

## Context
This ADR defines the strategy for public content rendering in our multi-tenant CMS. It builds upon the publishing and visibility rules established in ADR-005, focusing on how published content is served to end-users.

## Decision

### 1. Purpose and Scope
- Serve published content to end-users based on visibility rules
- Support tenant-specific theming and branding
- Handle content versioning and caching
- Support SEO requirements
- Maintain tenant isolation

### 2. URL Strategy
**Chosen Approach: Subdomain-based Routing**
- `{tenant}.example.com` - Primary public site
- `{tenant}-admin.example.com` - Admin interface
- `example.com/t/{tenant}` - Fallback path-based routing

**Rationale**:
- Clear tenant identification
- Simplified cookie management
- Better SEO (treated as separate sites)
- Easier SSL certificate management

### 3. Integration with Publishing (ADR-005)
- Only content in "Published" state is rendered
- Scheduled content becomes available at specified publish time
- Content visibility rules from ADR-005 are enforced
- Draft/Archived content returns 404 in public routes

### 4. Tenant Isolation & Security
- Strict tenant context enforcement
- No cross-tenant data leakage
- Public routes are read-only
- Rate limiting per tenant
- Content security policies per tenant

### 5. Non-Goals
- User comments/engagement features
- Content personalization
- A/B testing
- Multi-language support
- Edge-side includes (ESI)

### 6. Future Extensibility
- Plugin system for custom renderers
- Edge caching support
- Content delivery network (CDN) integration
- Staging/preview environments
- Webhook notifications for content changes

## Consequences

### Positive
- Clear separation of concerns
- Predictable URL structure
- Tenant-specific theming support
- Scalable architecture

### Negative
- Requires wildcard DNS configuration
- Slightly more complex deployment
- Potential for cookie management complexity

## Related ADRs
- ADR-005: Publishing and Visibility Strategy
- ADR-003: Tenant Resolution Strategy

## Date
2025-12-27
