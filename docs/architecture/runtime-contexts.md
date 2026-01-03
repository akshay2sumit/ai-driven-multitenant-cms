# Runtime Contexts

## Authoring Context

### Purpose
Content creation and management within tenant-scoped administrative interfaces.

### Access Scope
MUST be authenticated and tenant-scoped. MUST be bound to explicit user identity with appropriate permissions.

### Allowed Operations
- Draft creation and modification
- Preview generation
- Content moderation actions
- Version management
- Metadata editing
- Tenant-specific configuration

### Forbidden Operations
- Public content delivery
- Cross-tenant data access
- System-level configuration changes
- Background job execution
- Policy enforcement actions

### Fail-Closed Rule
Any ambiguity in context, tenant scope, or permissions MUST result in immediate denial of operation.

## Runtime / Public Context

### Purpose
Read-only delivery of published content to public or authorized consumers.

### Access Scope
MUST be either public access or token-based authorized access. MUST be tenant-isolated.

### Allowed Operations
- Read published content
- Render content in approved formats
- Access tenant-isolated public endpoints
- Cache-based content delivery

### Forbidden Operations
- Content modification or creation
- Draft access or modification
- Administrative operations
- System configuration changes
- Cross-tenant data access

### Fail-Closed Rule
Any request that is not explicitly published and tenant-isolated MUST result in immediate denial.

## System / Background Context

### Purpose
Deterministic maintenance and operational tasks that preserve system integrity.

### Access Scope
MUST be internal-only access with explicit system authority. MUST be tenant-aware when applicable.

### Allowed Operations
- Reindexing and search optimization
- Data cleanup and archival
- Backup and restore operations
- System health monitoring
- Scheduled maintenance tasks

### Forbidden Operations
- Business decision-making
- Content publication decisions
- AI autonomous actions
- Tenant policy modifications
- Cross-tenant operations

### Fail-Closed Rule
Any system operation lacking explicit tenant binding or scope MUST result in immediate denial.

## Governance Context

### Purpose
Policy enforcement, audit validation, and system integrity verification.

### Access Scope
MUST be internal-only with immutable governance authority. MUST be read-only with respect to business state.

### Allowed Operations
- Policy validation and enforcement
- Audit evidence generation
- Compliance verification
- System integrity checks
- Evidence preservation

### Forbidden Operations
- Content mutation or creation
- Business state modifications
- Tenant configuration changes
- User impersonation
- Policy bypass attempts

### Fail-Closed Rule
Any governance operation that attempts to mutate business state MUST result in immediate denial and violation recording.
