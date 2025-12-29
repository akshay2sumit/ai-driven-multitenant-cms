# Developer Guide: Overview

*Last Updated: 2025-12-29*  
*Governance Version: 1.3.3 LTS*  
*Phase: 14 - Documentation Audit (Completed & Audited)*

## Introduction
This guide documents the current implementation status and architecture of the AI-Driven Multi-Tenant CMS. It is maintained to reflect the actual state of the system.

## Current Implementation Status

### Authoring Service

#### Overview
The AuthoringService provides content authoring capabilities with strict role-based access control and tenant isolation.

#### Implementation Status
- **Current State**:
  - Core authoring workflows implemented
  - Role-based access control (author/reviewer/publisher)
  - State management (draft → review → published)
  - Tenant isolation enforced at service layer

- **Explicitly Not Implemented**:
  - No UI components
  - No public API endpoints
  - No authentication system
  - No mobile application support
  - No version resolution (deferred)

#### Service Boundaries
- **AuthoringService**:
  - Manages content workflow
  - Enforces business rules
  - Handles state transitions
  - No direct database access

- **Repositories**:
  - Handle data persistence
  - Enforce tenant isolation
  - Implement data access patterns

#### Security Model
- **Access Control**:
  - Author: Create/edit content
  - Reviewer: Approve/reject content
  - Publisher: Publish approved content
  - All operations are tenant-scoped

- **Isolation**:
  - Strict tenant separation
  - No cross-tenant operations
  - Fail-closed by default

### Public Runtime
- **Status**: Fail-closed (404-by-design)
- **Access**: No public content access implemented
- **Security**: Strict tenant isolation maintained
- **No Public Rendering**: All public routes return 404

## Development Practices

### Code Organization
- **Services**: Business logic and workflow
- **Repositories**: Data access layer
- **Models**: Data structure and validation
- **Tests**: Unit and feature tests

### Testing
- Unit tests for all business logic
- Service-level tests for workflows
- Tenant isolation verification
- No UI or end-to-end tests

## Important Notes
- All content operations are tenant-scoped
- Public access is explicitly disabled
- No authentication system is implemented
- Service boundaries are strictly enforced

#### Overview
The PublishingRuntime provides secure, read-only access to published content with strict tenant isolation. It's designed to be used internally by other services and is not exposed to public routes.

#### Key Features
- **Read-Only Access**:
  - Structured to prevent any write operations
  - Interface-based architecture enforces read-only behavior
  - No public API endpoints exposed

- **Security Model**:
  - **Tenant Isolation**: Strictly enforced at all access points
  - **Fail-Closed**: Default deny on any error condition
  - **No Public Exposure**: Internal use only, no public routes
  - **Audit Logging**: All access attempts are logged

- **Access Patterns**:
  ```php
  // Get the PublishingRuntime service
  $publishingRuntime = \Config\Services::publishingRuntime();
  
  // Get a published entity
  $entity = $publishingRuntime->getPublishedEntity('page', 123);
  
  // Check if an entity is published
  $isPublished = $publishingRuntime->isEntityPublished('page', 123);
  
  // Get published state information
  $state = $publishingRuntime->getPublishedState('page', 123);
  ```

#### Security Considerations
1. **Tenant Context**:
   - Tenant ID is required for all operations
   - Cross-tenant access is strictly prohibited
   - Tenant context is verified at multiple levels

2. **Read-Only Guarantees**:
   - Uses `ReadOnlyPublishingRepositoryInterface`
   - No write operations exposed through the interface
   - Immutable data structures used where possible

3. **Error Handling**:
   - All errors result in safe defaults
   - No internal system details are exposed
   - Comprehensive logging of security-relevant events

4. **Performance**:
   - Optimized for read operations
   - No expensive joins or complex queries
   - Caching can be implemented at the service level if needed

### Public Runtime
- **Public Runtime**: Boundary established (fail-closed)
  - `/p/{tenant}` namespace exists but returns 404
  - Read-only access enforced
  - 404-by-design behavior is working as intended
  - No content access or rendering implemented
  - Strict tenant isolation maintained

- **CMS Pages**: Basic CRUD operations with publishing workflow
  - Publishing workflow implemented (draft → review → published)
  - Versioning and history tracking
  - State management (draft, review, published, archived, retracted)
  - All operations tenant-scoped and audited
  - No public rendering implemented

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

### Public Rendering Status
- **Current State**: Not Implemented
- **Design**: Documented in ADR-006 (Design Complete)
- **Security**: System remains in fail-closed state
- **No Implementation**: No rendering code exists in the codebase

### Important Warnings for Developers
- **Public Runtime is a Boundary Only**
  - No content is accessible through public routes
  - 404 responses are expected behavior
  - This is not a bug or misconfiguration

### Implementation Constraints
- **DO NOT** add views/templates to public runtime
- **DO NOT** implement any rendering logic
- **DO NOT** query content from public controllers
- **DO NOT** implement caching mechanisms
- **DO NOT** create theme templates
- **DO NOT** modify the database schema
- **Governance First**: All changes require documentation
- **Explicit over Implicit**: Clear contracts and boundaries
- **Current State Only**: Document what exists, not future plans

## Core Architecture (Phase 11 - Documentation Audit Complete)
- **Framework**: CodeIgniter 4.6.4
- **Tenant Resolution**:
  - Admin: Path-based (`/t/{tenant}/...`)
  - Public: Path-based (`/p/{tenant}/...`) - 404-by-design
  - Future designs documented in ADR-006
- **Database**: MySQL 8.0+/MariaDB 10.5+ with tenant isolation
- **Documentation**:
  - ADR-002: System Architecture Baseline
  - ADR-003: Tenant Resolution Strategy
  - ADR-005: Publishing & Visibility (Design Complete)
  - ADR-006: Public Rendering Strategy (Design Complete)
  - Phase 11: Documentation Audit Complete

## Publishing Model (Phase 12 - Design Only)

### Conceptual Overview
The publishing model defines how content moves through different states in its lifecycle. This is currently in the design phase only.

### Key Concepts
- **Publishable Content**: Content entities that support the full publishing workflow
- **Lifecycle States**:
  - Draft → Review → Published → Archived/Retracted
  - Each state has specific access controls and behaviors
- **PublishingRuntime**: Future read-only interface for published content

### Design-Only Status
- **NO** schema changes in this phase
- **NO** implementation of publishing workflows
- **NO** UI components for state management
- **NO** API endpoints for state transitions

### Future Integration
- The same published content will be consumed by:
  - Website frontend
  - Mobile applications (Android/iOS)
  - Public APIs (future phase)
- All platforms will use the same content source
- Content will be rendered appropriately for each platform

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

## Implementation Ceiling (Phase 11)

### Implementation Constraints (Phase 11)

**DO NOT IMPLEMENT**:
- Publishing workflow code
- Public content rendering
- Scheduled publishing logic
- Visibility enforcement
- UI for publishing controls
- Any rendering endpoints
- Theme system
- Public API endpoints

### Current Implementation (Phase 11)
- Basic authentication (login/logout)
- CMS Pages CRUD operations (tenant-scoped)
- Path-based multi-tenancy
- Documentation audit complete


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