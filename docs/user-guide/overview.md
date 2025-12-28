# User Guide: Overview

*Last Updated: 2025-12-27*  
*Governance Version: 1.3.3 LTS*  
*Phase: 9 - Public Runtime Boundary*

## Welcome to AI-Driven Multi-Tenant CMS
This is the user guide for the CMS administration interface. The current implementation provides basic content management capabilities with strict tenant isolation.

## Current Features (Phase 9)
- **Pages Management**: Basic create, read, update, delete operations
- **Multi-Tenant Support**: Isolated content per tenant
- **Simple Interface**: Focused on core functionality
- **Public Runtime**: Basic boundary established (404-by-design)

## Important Notice: Public Site Status
- The public website is **not yet available**
- Public URLs (starting with `/p/`) return 404 by design
- This is expected behavior and not a configuration error
- Public content delivery will be implemented in a future release

## Current Limitations
- No user authentication
- No media management
- No publishing workflow
- No public content rendering (404 by design)

## Accessing the Admin Interface

### URL Structure
Access the admin interface using your tenant-specific URL:

```
https://yourdomain.com/t/{your-tenant-identifier}/admin
```

**Example:**
- `https://example.com/t/acme/admin`

### Tenant Isolation
- Each tenant has a unique identifier in the URL
- Content is strictly isolated between tenants
- No cross-tenant data access is possible

### Important Notes
- Tenant identifier is case-sensitive
- Use only alphanumeric characters and hyphens
- Contact your administrator for your tenant identifier

## Available Features

### Pages Management
- Create and edit simple text pages
- Basic formatting options
- No rich text or media support

## Common Tasks
- [Creating a New Page](common-tasks.md#creating-a-new-page)
- [Editing Existing Pages](common-tasks.md#editing-pages)
- [Managing Page Content](common-tasks.md#managing-content)

## Need Help?
- Contact your system administrator for support
- Refer to the [Developer Documentation](../developer-guide/overview.md) for technical details