# ADR-005: Publishing and Visibility Strategy

## Status
Proposed

## Context
As we design the CMS, we need to define how content publishing and visibility will work across multi-tenant environments. This ADR focuses solely on the design aspects without implementing any code.

## Decision

### Content Lifecycle States
1. **Draft**
   - Initial state of new content
   - Visible only to content authors and editors within the tenant admin
   - Not accessible via public routes

2. **Scheduled**
   - Content is queued for future publication
   - Must have a valid publication date in the future
   - Treated as Draft until publication time

3. **Published**
   - Content is live and visible based on visibility rules
   - Accessible according to tenant and content visibility settings
   - Served through the public frontend

4. **Archived**
   - Previously published content that is no longer active
   - Retained for historical/reference purposes
   - Only accessible through admin interface

### Visibility Rules
1. **Tenant-Scoped Visibility**
   - Content is always scoped to its tenant
   - No cross-tenant content visibility
   - Tenant admins control all content within their tenant

2. **Public/Private Content**
   - Public: Accessible to all visitors (including unauthenticated)
   - Private: Restricted to authenticated tenant users
   - Role-based: Restricted to specific roles within the tenant

3. **Scheduled Publishing**
   - Content can be scheduled for future publication
   - Publication can be immediate or scheduled
   - Expiration dates can be set

### Tenant vs Public Access
1. **Tenant Admin Access**
   - Full CRUD operations on all content
   - Can manage publishing workflow
   - Can set visibility rules

2. **Public Access**
   - Read-only access to published content
   - Subject to visibility rules
   - No access to draft/scheduled/archived content

### Non-Goals (Explicitly Out of Scope)
1. Content versioning/history
2. Content approval workflows
3. Content scheduling UI/implementation
4. Public facing content rendering
5. Performance optimizations for public access
6. Caching strategy for public content

### Separation from Rendering
1. The publishing state is independent of rendering
2. Rendering layer will query for appropriate content states
3. No rendering logic in the publishing service

## Consequences
### Benefits
- Clear separation of concerns
- Flexible visibility controls
- Supports future expansion

### Risks
- Complexity in querying for appropriate content
- Potential performance impact with complex visibility rules
- Requires careful tenant isolation

### Future Considerations
- Content scheduling service
- Preview functionality for draft content
- Bulk operations on content states
- Audit logging of state changes
