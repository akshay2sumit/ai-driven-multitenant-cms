# Decision Log

## Logged Decisions
This log captures all significant decisions made during the project, beyond ADRs.

### 2025-12-25: ADR-002 System Architecture Baseline
- **Decision**: Adopted a multi-tenant architecture with clear separation of concerns
- **Reference**: ADR-002 (ACCEPTED & materialized)
- **Impact**: Established the foundation for all subsequent development

### 2025-12-25: ADR-003 Tenant Resolution Strategy
- **Decision**: Implemented path-based tenant resolution at `/t/{tenant}/...`
- **Reference**: ADR-003 (ACCEPTED & materialized)
- **Impact**: All tenant-specific routes and resources must follow this pattern

### 2025-12-25: Project Structure Design
- **Decision**: Follow the provided structure file for CI4 Ai-cms.
- **Rationale**: Matches .windsurfrules requirements for environment-aware scaffolding.
- **Impact**: Defines all folders and files for reusability.
- **Status**: Frozen per ADR-002 (only modifiable via new ADR)

### 2025-12-25: Deployment Artifacts
- **Decision**: Include .env.example, Dockerfile, docker-compose.yml for flexible deployment.
- **Rationale**: Supports multiple environments as per deployment strategy.
- **Impact**: Enables easy deployment across targets.

### 2025-12-25: Canonical Guide Source
- **Decision**: Use Appendix A from .windsurfrules as canonical for daily guide.
- **Rationale**: Ensures consistency and accessibility per rules.
- **Impact**: Dashboard links to authoritative guide.

## Architecture Freeze Notice
As of Phase 6, the architecture is considered frozen. Any changes to the established patterns (including but not limited to tenant resolution, project structure, and core architecture) require the creation of a new ADR and explicit approval.