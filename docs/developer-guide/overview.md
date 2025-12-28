# Developer Guide: Overview

*Last Updated: 2025-12-27*  
*Governance Version: 1.3.3 LTS*  
*Phase: 9 - Public Runtime Boundary (Implementation Complete)*

## Introduction
This guide is for developers working on the AI-Driven Multi-Tenant CMS. It documents the current implementation status, architecture, and development practices.

## Current Implementation Status (Phase 9 - Implementation Complete)
- **Public Runtime**: Basic boundary established
  - `/p/{tenant}` namespace added
  - Read-only access enforced
  - 404-by-design behavior
  - No content access or rendering
  - Strict tenant isolation

- **CMS Pages**: Basic CRUD operations only
  - Publishing workflow (Design Complete - ADR-005)
  - Public rendering (Design Only - ADR-006)
  - No versioning or history
  - No media uploads

- **Tenancy**: Path-based resolution with strict isolation
  - `/t/{tenant_identifier}/...` for admin
  - `/p/{tenant_identifier}/...` for public (404-by-design)
  - Tenant context management
  - No cross-tenant data access

- **Authentication**: Basic implementation only
  - No user roles or permissions
  - No password reset
  - No account management

## Development Principles (Phase 9 - Implementation Complete)

### Public Runtime Boundary (Phase 9)
- **URL Pattern**: `/p/{tenant}`
- **Access**: Read-only
- **Behavior**: 404-by-design
- **Security**: Strict tenant isolation
- **No Content Access**: Public routes return 404

### Public Rendering Semantics (Design Only)
- **URL Strategy**: Subdomain-based routing (`{tenant}.example.com`)
- **Content Resolution**: Only published content will be rendered (future)
- **Caching**: Multi-layered caching strategy (future)
- **Theming**: Tenant-specific theming support (future)
- **Security**: Strict tenant isolation enforced

### Important Warnings for Developers
- **Public Runtime is a Boundary Only**
  - No content is accessible through public routes
  - 404 responses are expected behavior
  - This is not a bug or misconfiguration

### What NOT to Implement Yet
- **DO NOT** add views/templates to public runtime
- **DO NOT** bypass publishing logic
- **DO NOT** query content from public controllers
- **DO NOT** implement caching mechanisms
- **DO NOT** create theme templates
- **DO NOT** modify the database schema
- **Governance First**: All changes require documentation
- **Explicit over Implicit**: Clear contracts and boundaries
- **Current State Only**: Document what exists, not future plans

## Core Architecture (Phase 9 - Implementation Complete)
- **Framework**: CodeIgniter 4.6.4
- **Tenant Resolution**:
  - Admin: Path-based (`/t/{tenant}/...`)
  - Public: Path-based (`/p/{tenant}/...`) - 404-by-design
  - Future: Subdomain-based (`{tenant}.example.com`)
- **Database**: MySQL 8.0+/MariaDB 10.5+ with tenant isolation
- **Documentation**:
  - ADR-002: System Architecture Baseline
  - ADR-003: Tenant Resolution Strategy
  - ADR-005: Publishing & Visibility (Design Complete)
  - ADR-006: Public Rendering Strategy (Design Phase)
  - Phase 9 Implementation Notes: Public Runtime Boundary

## Development Setup
1. **Prerequisites**:
   - PHP 8.1+
   - Composer
   - MySQL 8.0+ or MariaDB 10.5+
   - Web server (Apache/Nginx)

2. **Installation**:
   ```bash
   git clone [repository-url]
   cd aibos
   composer install
   cp env .env
   # Configure .env with database credentials
   php spark migrate
   ```

## Coding Standards (Phase 6)
- Follow PSR-12 coding style
- Use strict typing
- Document all public APIs
- No direct database access from controllers
- All database operations must be tenant-scoped
- No unit tests required for this phase

## Tenant Context

### Accessing Tenant Context
```php
use App\Tenant\Context\TenantContext;

// Get current tenant ID (throws if not set)
$tenantId = TenantContext::require();

// Check if tenant context is available
if (TenantContext::has()) {
    $tenantId = TenantContext::get();
}
```

### Tenant Guard
Use `TenantGuard` to validate tenant context:
```php
use App\Tenant\Guard\TenantGuard;

// Ensure tenant context exists
TenantGuard::ensureTenantContext();

// Ensure specific tenant access
TenantGuard::ensureTenantMatch($expectedTenantId);
```

## Implementation Ceiling (Phase 7)

### Implementation Constraints (Phase 7)

**DO NOT IMPLEMENT YET**:
- Any publishing workflow code
- Public content rendering
- Scheduled publishing logic
- Visibility enforcement
- Any UI for publishing controls

### What's Not Implemented (Phase 7)
This version implements ONLY the following:
- Basic authentication (login/logout)
- CMS Pages CRUD operations
- Path-based multi-tenancy


## Tenant Resolution Flow

### Overview
The system uses path-based tenant resolution as specified in ADR-003. The tenant identifier is extracted from the URL path following the pattern `/t/{tenant_identifier}/...`.

### Request Lifecycle
1. **Filter Execution**
   - The `TenantFilter` runs early in the request lifecycle
   - It's registered as a global before filter in `app/Config/Filters.php`

2. **Tenant Resolution**
   - The `TenantResolver` extracts and normalizes the tenant identifier
   - The identifier is stored in `TenantContext` for the request duration

3. **Controller Execution**
   - The resolved tenant is available via `TenantContext::getCurrentTenant()`
   - No tenant validation is performed at this stage

4. **Cleanup**
   - After the response is sent, the tenant context is cleared

### Accessing Tenant Context

#### Recommended Pattern
Always use the most specific method that fits your needs:

```php
use App\Tenant\Context\TenantContext;

// 1. Require a tenant (throws if not set)
try {
    $tenantId = TenantContext::require();
    // $tenantId is guaranteed to be a non-empty string here
} catch (\RuntimeException $e) {
    // Handle missing tenant context
}

// 2. Get tenant if available (returns null if not set)
if ($tenantId = TenantContext::get()) {
    // $tenantId is a non-empty string
}

// 3. Just check if tenant is set
if (TenantContext::has()) {
    // Tenant context is available
}

// 4. Ensure tenant is available (throws if not set)
TenantContext::ensure();
// Continue knowing tenant is available
```

#### When to Use Which Method
- **`require()`**: When you absolutely need a tenant ID and can't continue without it
- **`get()`**: When you want to handle the absence of a tenant gracefully
- **`has()`**: When you only need to check presence without getting the value
- **`ensure()`**: When you only need to verify a tenant is set but don't need the value

#### Important Notes
- Tenant context is only available during a request/response cycle
- Always handle the case where tenant context might be missing
- Never cache or store tenant context outside the current request
- Use dependency injection where possible rather than direct static calls

### Important Notes
- **DO NOT** assume a tenant is always set
- **DO NOT** hardcode tenant identifiers
- **DO** use `TenantContext` for all tenant-related operations
- **DO** handle cases where tenant is not available

### Related ADRs
- [ADR-002: System Architecture Baseline](../adr/ADR-002-system-architecture-baseline.md)
- [ADR-003: Tenant Resolution Strategy](../adr/ADR-003-tenant-resolution-strategy.md)

## Security
See [Security Guidelines](security-guidelines.md).

## Contribution
Check [Contribution](contribution.md) for how to contribute.