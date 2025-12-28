# Module Design

*Last Updated: 2025-12-27*  
*Governance Version: 1.3.3 LTS*

## Current Implementation Status (Phase 9)
- [x] Core multi-tenant architecture
- [x] Tenant resolution system (path-based)
- [x] Basic CMS Pages module (CRUD operations)
- [x] Public runtime boundary established
- [ ] Publishing and visibility (Design Only)
- [ ] Public rendering (Design Only)
- [ ] User management module
- [ ] Media management
- [ ] Theme system
- [ ] AI integration

## Implemented Components

### Core Components
- `app/Core/` - Framework extensions and base classes
- `app/Tenant/` - Multi-tenancy implementation
  - `Context/` - Tenant context management
  - `Resolution/` - Tenant resolution logic
  - `Guard/` - Tenant access control

### CMS Module (Pages)
- Basic CRUD operations for pages
- Tenant-scoped data access
- Publishing states defined (Draft/Scheduled/Published/Archived)
- Visibility controls (Public/Private/Role-based)

### Public Runtime Boundary (Phase 9)
- Public route namespace: `/p/{tenant}`
- Read-only access
- 404-by-design behavior
- No content access or rendering
- Strict tenant isolation

### Public Rendering Module (Design Phase - Not Implemented)
- Content resolution and rendering (Future)
- Tenant-specific theming support (Future)
- Caching layer integration (Future)
- URL routing for public content (Basic routing exists, no rendering)

## Module Interaction

### Tenant Resolution Flow
1. Request enters with `{tenant}.example.com` or `/t/{tenant}/...` path
2. `TenantFilter` resolves tenant from URL/subdomain
3. Tenant context is set for request duration
4. All subsequent operations are scoped to tenant

### Public Runtime Flow (Phase 9)
1. Request enters `/p/{tenant}` endpoint
2. Tenant context is established
3. Request is validated (read-only, tenant-scoped)
4. 404 response is returned (by design)

### Future Public Content Rendering Flow (Design Only)
1. Request enters public endpoint
2. Tenant context is established
3. Content resolver fetches published content
4. Visibility rules are enforced
5. Content is rendered using tenant-specific theme
6. Response is cached (if enabled)

### Data Access Pattern
```php
// In controllers:
$pages = $this->pageModel->where('tenant_id', TenantContext::require())->findAll();
```

## Implementation Guidelines

### 1. Module Structure
- Each module must be self-contained
- Follow PSR-4 autoloading standards
- Include proper documentation
- Define clear public API
- Document module dependencies

### Publishing Layer (Design Only)
- **Content States**: Draft, Scheduled, Published, Archived
- **Visibility Rules**: Public, Private, Role-based
- **Scheduling**: Future publication and expiration
- **Access Control**: Tenant-scoped with role-based restrictions

### 2. Module Boundaries

#### CMS Module
- Manages content creation/editing
- Handles content storage
- Enforces content structure
- Manages relationships

#### Publishing Module
- Controls content lifecycle
- Manages publication schedule
- Handles visibility rules
- Tracks publication history

#### Public Runtime Boundary (Phase 9)
- Defines public URL structure
- Enforces read-only access
- Returns 404 for all requests (by design)
- Maintains tenant isolation

#### Future Public Rendering Module (Not Implemented)
- Will serve published content
- Will apply tenant theming
- Will manage caching
- Will handle public content delivery

### 3. Data Access
- Always use tenant context
- Implement proper validation
- Follow framework patterns
- Document all public methods
- Use repository pattern for data access

### 4. Security
- Use TenantGuard for access control
- Validate all user input
- Follow least privilege principle

## Design Principles

### 1. Modularity
- Each module is self-contained with clear boundaries
- Communication through well-defined interfaces
- No direct module-to-module dependencies

### 2. Extensibility
- Hook system for extending functionality
- Event-driven architecture for cross-cutting concerns
- Configuration over code where possible

### 3. Maintainability
- Strict adherence to PSR standards
- Comprehensive inline documentation
- Automated testing requirements

### 4. Governance Compliance
- All modules must document:
  - Purpose and scope
  - Dependencies
  - Configuration requirements
  - Security considerations

## Implementation Status
- Core framework modules: In place
- Business modules: Not started
- All module implementations require explicit governance approval