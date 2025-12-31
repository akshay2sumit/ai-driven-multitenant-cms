# System Capability Map

## Purpose

This document provides a factual, conservative inventory of capabilities that are **CURRENTLY IMPLEMENTED** in the repository. This reflects the actual system state as of the last commit and is not a roadmap or future plan.

## Governance & Safety Capabilities

### Tenant Governance Guard
- **What it does**: Enforces tenant context validation for all operations requiring tenant scope
- **Who it is for**: System administrators and developers
- **Key boundaries**: Runtime validation, explicit context requirements, fail-closed on missing context

### Feature Flags Management
- **What it does**: Provides in-memory feature flag management for global and tenant-scoped features
- **Who it is for**: System administrators
- **Key boundaries**: In-memory storage only, no persistence, administrative interface only

### Governance Framework
- **What it does**: Maintains comprehensive governance logs, decision tracking, and compliance documentation
- **Who it is for**: Project managers and governance authorities
- **Key boundaries**: Documentation-only, audit-focused, no operational system control

## Tenant Resolution Capabilities

### Path-Based Tenant Resolution
- **What it does**: Resolves tenant context from URL paths using `/t/{tenant}/...` pattern
- **Who it is for**: System users and administrators
- **Key boundaries**: Path-based only, no subdomain support, tenant isolation enforced

### Tenant Context Management
- **What it does**: Maintains tenant context throughout request lifecycle
- **Who it is for**: System operations
- **Key boundaries**: Request-scoped only, no cross-request persistence, isolation enforced

### Multi-Tenant Data Isolation
- **What it does**: Enforces strict data separation between tenants at database and application levels
- **Who it is for**: System operations
- **Key boundaries**: Database-level isolation, application-level enforcement, no data leakage

## Authoring Capabilities (CMS Backend)

### Basic Authentication
- **What it does**: Provides email/password authentication with session management
- **Who it is for**: System users
- **Key boundaries**: Tenant-scoped only, basic email/password, CSRF protection, session-based

### Pages CRUD Operations
- **What it does**: Enables create, read, update, delete operations for tenant-scoped pages
- **Who it is for**: Content authors and administrators
- **Key boundaries**: Tenant-isolated only, basic validation only, no rich text features, no versioning

### User Management
- **What it does**: Manages user accounts and tenant-user associations
- **Who it is for**: System administrators
- **Key boundaries**: Basic user management only, tenant-scoped associations, no role hierarchy

### Publishing Schema (Internal)
- **What it does**: Defines and manages publishable content states and lifecycle
- **Who it is for**: System operations and content reviewers
- **Key boundaries**: Internal evaluation only, no public rendering, state management only

## Runtime / Publishing Capabilities (Fail-Closed)

### Fail-Closed Public Access
- **What it does**: Returns 404 responses for all public access attempts
- **Who it is for**: System security
- **Key boundaries**: No public content exposure, 404-by-design, read-only runtime

### Read-Only Publishing Runtime
- **What it does**: Provides read-only repository interface for publishing operations
- **Who it is for**: System operations
- **Key boundaries**: Read-only access only, no write operations, internal use only

### Internal Publishing Evaluation
- **What it does**: Evaluates content publishing states and visibility rules internally
- **Who it is for**: Content reviewers and system operations
- **Key boundaries**: Internal evaluation only, no public exposure, state-based logic

### Dashboard Observability
- **What it does**: Provides basic dashboard for system status and observability
- **Who it is for**: System administrators
- **Key boundaries**: Read-only information display, no operational controls, development environment only

## Explicit Non-Capabilities

### Public Content Rendering
- **Status**: NOT IMPLEMENTED
- **Current State**: Design documentation exists (ADR-006) but no implementation
- **Access**: All public access returns 404

### Admin User Interface
- **Status**: NOT IMPLEMENTED
- **Current State**: No administrative interface for content management
- **Access**: Backend operations only

### Content Preview Functionality
- **Status**: NOT IMPLEMENTED
- **Current State**: No preview capabilities for content before publishing
- **Access**: No preview endpoints or interfaces

### Rich Text Editing
- **Status**: NOT IMPLEMENTED
- **Current State**: Pages management supports basic text only
- **Access**: No WYSIWYG or rich text features

### Content Versioning
- **Status**: NOT IMPLEMENTED
- **Current State**: No version history or rollback capabilities
- **Access**: No version tracking or restoration

### Media Management
- **Status**: NOT IMPLEMENTED
- **Current State**: Database schema exists but no operational media handling
- **Access**: No file upload or media management features

### API Endpoints
- **Status**: NOT IMPLEMENTED
- **Current State**: No public or private API endpoints for external integration
- **Access**: No external system integration

### Mobile App Integration
- **Status**: NOT IMPLEMENTED
- **Current State**: No mobile application support or integration
- **Access**: No mobile-specific features or endpoints

### Production Deployment
- **Status**: NOT IMPLEMENTED
- **Current State**: Development environment only, no production deployment procedures
- **Access**: Not production-ready

### Backup and Recovery
- **Status**: NOT IMPLEMENTED
- **Current State**: No automated backup or recovery procedures
- **Access**: Manual backup only

### Monitoring and Logging
- **Status**: NOT IMPLEMENTED
- **Current State**: Basic error logging only, no comprehensive monitoring
- **Access**: No operational monitoring or alerting

## System Boundaries

### Security Boundaries
- **Public Access**: Fail-closed (404-by-design)
- **Authentication**: Required for all operations
- **Tenant Isolation**: Enforced at all layers
- **Data Protection**: No sensitive data exposure

### Functional Boundaries
- **Content Management**: Basic CRUD operations only
- **Publishing**: Internal evaluation only
- **User Interface**: Basic forms and dashboard only
- **Integration**: No external system integration

### Operational Boundaries
- **Environment**: Development environment only
- **Deployment**: No production deployment procedures
- **Scalability**: Not tested or configured for production scale
- **Monitoring**: Basic error logging only

## Current State Declaration

This document reflects the **CURRENT STATE ONLY** of implemented capabilities. It is not a roadmap, does not indicate future development plans, and does not make promises about unimplemented features.

Capabilities listed as "NOT IMPLEMENTED" are explicitly absent from the current system state. Any features not listed in the implemented capabilities section should be assumed to be non-existent.

---

**Authority**: This capability map is authoritative for the current system state only. It must be updated when capabilities are added or removed.

**Maintenance**: This document must be updated whenever the system's implemented capabilities change.

**Audit**: This document serves as the definitive audit reference for what the system can and cannot do in its current state.
