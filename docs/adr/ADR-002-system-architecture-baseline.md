# ADR-002: System Architecture Baseline

## Status
**Accepted**  
*Date: 2025-12-26*  
*Governance Version: 1.3.1 LTS*

## Context
This ADR establishes the baseline system architecture for the AI-Driven Multi-Tenant CMS project, following the governance rules and technical specifications defined in the project's governance artifacts.

## Decision
### Core Architecture
- **Framework**: CodeIgniter 4.6.4
- **Pattern**: Modular HMVC (Hierarchical Model-View-Controller)
- **Multi-tenancy**: Database-per-tenant with shared schema
- **AI Integration**: Loosely coupled service architecture

### Directory Structure
```
app/
├── Core/               # Framework extensions
├── Tenant/             # Multi-tenancy implementation
│   ├── Context/        # Tenant context management
│   ├── Resolution/     # Tenant resolution logic
│   └── Config/         # Tenant-specific configuration
├── Modules/            # Business modules
│   ├── Content/        # Content management
│   ├── Users/          # User management
│   └── Settings/       # System settings
├── AI/                 # AI capabilities
│   ├── Assistants/     # AI assistant implementations
│   ├── Providers/      # AI service providers
│   └── Policies/       # AI behavior governance
└── Governance/         # Runtime governance
    ├── Contracts/      # Governance interfaces
    └── Guards/         # Rule enforcement
```

### Key Design Decisions
1. **Strict Layer Separation**
   - Presentation (Views/Controllers)
   - Application Services
   - Domain Logic
   - Data Access

2. **Tenant Isolation**
   - Database connection switching
   - File system partitioning
   - Cache key namespacing

3. **AI Integration**
   - Provider-agnostic design
   - Fallback mechanisms
   - Usage tracking

## Consequences
### Positive
- Clear separation of concerns
- Predictable code organization
- Easier maintenance
- Better testability

### Negative
- Slight performance overhead from abstraction layers
- Initial setup complexity

### Risks
- Potential over-engineering
- Learning curve for new developers

## Compliance
- [x] Follows .windsurfrules v1.3.1 LTS
- [x] No direct database access from controllers
- [x] No business logic in views
- [x] No framework modifications

## Related Documents
- [ADR-001: Environment Readiness](ADR-001-environment-readiness.md)
- [System Context](../governance/system-context.md)
- [Module Design](../architecture/module-design.md)
