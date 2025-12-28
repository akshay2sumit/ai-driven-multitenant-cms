# ADR-004: Database Schema Strategy

## Status
Proposed

## Context
Our multi-tenant CMS requires a database architecture that balances tenant isolation, performance, and operational simplicity. Given our hosting constraints and the need for efficient resource utilization, we need to carefully consider our database strategy.

## Decision
We will implement a **single-database, shared-table** approach with the following characteristics:

### Tenant Isolation
- All tenant-specific tables will include a `tenant_id` column
- All queries will be scoped to the current tenant using this ID
- Row-level security will be enforced at the application layer

### Hosting Constraints
- Single database instance for all tenants
- No cross-tenant data access
- No tenant-specific database users
- No stored procedures or triggers for tenant isolation

### Non-Goals
- Multi-database or multi-schema tenancy
- Automatic tenant provisioning at the database level
- Database-level row security policies
- Tenant-specific database backups
- Cross-tenant analytics at the database level

## Consequences
### Positive
- Simpler operations and maintenance
- Lower resource overhead
- Easier schema migrations
- Better performance for small to medium tenant sizes
- Simplified backup/restore procedures

### Negative
- Requires careful query construction
- Potential for "noisy neighbor" issues
- Limited tenant-specific optimization
- Backup/restore affects all tenants

### Risks
- Accidental data leakage between tenants if queries are not properly scoped
- Performance degradation as tenant count grows
- Limited ability to move tenants between database instances

## Compliance
This decision aligns with:
- ADR-002: System Architecture Baseline (multi-tenant requirements)
- ADR-003: Tenant Resolution Strategy (tenant identification)

## Related Decisions
- All database access will go through a repository layer
- Tenant context will be managed by the application
- Database migrations will be tenant-agnostic

## Notes
This decision may be revisited if:
- We need to support tenant-specific database instances
- Performance becomes a significant concern
- Compliance requirements change
