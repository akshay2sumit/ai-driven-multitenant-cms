# Query Semantics

## Read-Only Guarantee

A Query is a request that reads system state and produces a representation of that state without mutating it. Queries must be side-effect free and repeatable.

### Core Characteristics

- **Observational**: Queries observe existing state without changing it
- **Representational**: Queries produce representations of current state
- **Side-effect free**: Queries do not mutate system state
- **Repeatable**: Same query with same inputs produces same results

### Query Definition
A Query is a request that:
- **Reads system state** from authoritative sources
- **Produces output** representing current or recent state
- **Does not mutate state** in any way
- **May emit audit logs only** for tracking purposes

## Consistency Models

### 1️⃣ Strongly Consistent Read
Reflects the latest committed state with no staleness allowed.

**Characteristics**:
- **Latest state**: Most recent committed data
- **No staleness**: Zero tolerance for stale data
- **Correctness priority**: Consistency over latency
- **Decision support**: Suitable for critical decision-making

**Rules**:
- No stale data is permitted
- If freshness cannot be guaranteed → query must fail or retry
- Used when correctness is more important than performance
- Must reflect all committed changes up to query time

**Use Cases**:
- Critical business decisions
- Financial data queries
- Permission-sensitive operations
- Configuration validation

### 2️⃣ Read-Committed (Bounded Freshness)
Reflects a recent, committed snapshot with acceptable, bounded staleness.

**Characteristics**:
- **Recent snapshot**: Data from recent committed state
- **Bounded staleness**: Small, acceptable time lag
- **Internal consistency**: Snapshot is internally consistent
- **Performance balance**: Balance between freshness and performance

**Rules**:
- Staleness must be bounded conceptually
- Snapshot must be internally consistent
- Maximum staleness limits must be defined
- Used for most standard operations

**Use Cases**:
- Standard content queries
- Dashboard data display
- Reporting operations
- User interface data

### 3️⃣ Eventual Observation
Data may be stale and used only for non-critical insights.

**Characteristics**:
- **Potential staleness**: Data may be significantly stale
- **Non-critical use**: Not used for decision-making
- **Analytics focus**: Suitable for analytics and insights
- **High performance**: Optimized for performance over consistency

**Rules**:
- MUST be explicitly declared as eventual
- MUST NOT be used for decision-making commands
- Staleness is unbounded but acceptable
- Used only for analytical or reporting purposes

**Use Cases**:
- Analytics and reporting
- Trend analysis
- Historical data queries
- Non-critical insights

## Query Stability Rules

### Deterministic Behavior
Same query + same inputs + same context → same result (within declared consistency)

**Stability Requirements**:
- **Input consistency**: Same inputs must produce same outputs
- **Context stability**: Same execution context must produce same results
- **Temporal consistency**: Results must be consistent within consistency model
- **Deterministic processing**: No random or non-deterministic behavior

### Consistency Failure Handling
If declared consistency cannot be met:
- **Query MUST fail explicitly**: No silent degradation
- **Failure must be communicated**: Clear error indication
- **Retry guidance**: Information about whether retry is appropriate
- **Audit logging**: Consistency failures must be logged

### Context Binding
Queries must respect execution context constraints:
- **Authoring context**: May access authoring-specific data
- **Runtime context**: Limited to public/appropriate data
- **System context**: May access system-level data
- **Governance context**: May access audit and compliance data

## Read Guarantees

### Data Integrity Guarantees
Queries must provide:
- **Complete data**: All required data within scope
- **Accurate data**: Data must reflect true system state
- **Consistent data**: Data must be internally consistent
- **Authorized data**: Only data actor is authorized to see

### Performance Guarantees
Within consistency constraints:
- **Reasonable response time**: Queries must respond within acceptable time
- **Resource bounds**: Queries must not exhaust system resources
- **Scalability**: Query performance must scale appropriately
- **Isolation**: Queries must not interfere with each other

### Security Guarantees
Queries must enforce:
- **Tenant isolation**: No cross-tenant data leakage
- **Authorization compliance**: Only authorized data access
- **Privacy protection**: Sensitive data protection
- **Audit compliance**: All queries must be auditable

## Forbidden Read Behaviors

### Prohibited Behaviors
- **Queries triggering writes**: Read operations that cause state changes
- **Query auto-fixing data**: Queries that modify data during read
- **Query depending on UI state**: Queries that depend on user interface state
- **Query mutating caches with side effects**: Cache operations that affect system state
- **Query emitting domain events**: Read operations that trigger business events

### Forbidden Patterns
- **Read-modify-write**: Queries that read and then modify
- **Side-effect chains**: Queries that trigger cascading side effects
- **Hidden mutations**: Queries that change state indirectly
- **Context-dependent reads**: Queries that change behavior based on hidden context

### Cache Interaction Rules
- **Cache reads**: May read from caches if consistency is maintained
- **Cache writes**: Must not write to caches with side effects
- **Cache invalidation**: Must not invalidate caches as side effect
- **Cache warming**: Must not perform cache warming as side effect

## Fail-Closed Read Guarantees

### Failure Conditions
Queries must fail explicitly when:

#### Consistency Failures
- **Ambiguous consistency**: When consistency level cannot be determined
- **Consistency violation**: When declared consistency cannot be met
- **Freshness failure**: When data freshness requirements cannot be satisfied

#### Security Failures
- **Cross-tenant leakage risk**: Potential data leakage between tenants
- **Authorization failure**: When actor is not authorized for requested data
- **Context mismatch**: When query is not appropriate for execution context

#### System Failures
- **Resource exhaustion**: When query would exhaust system resources
- **Data corruption**: When underlying data is corrupted or unavailable
- **Network failures**: When required data cannot be accessed

### Failure Response
When queries fail:
- **Explicit failure**: Clear error indication
- **No partial results**: No partial or incomplete data returned
- **Error communication**: Meaningful error messages
- **Audit logging**: All failures must be logged

## Audit and Evidence Requirements

### Mandatory Query Audit
Each query must emit:

#### Query Identification
- **Query ID**: Unique query identifier
- **Query type**: Specific query classification
- **Consistency level**: Declared consistency model

#### Actor Information
- **Actor identity**: Authenticated identity executing query
- **Actor permissions**: Permission level used for authorization
- **Tenant context**: Actor's tenant association

#### Execution Information
- **Data accessed**: What data was accessed
- **Consistency achieved**: Actual consistency level achieved
- **Performance metrics**: Query execution performance
- **Outcome**: Success or failure with reason

#### Security Information
- **Authorization decision**: Permission evaluation result
- **Data access scope**: Scope of data accessed
- **Security events**: Any security-relevant events

### Performance Monitoring
Queries must support:
- **Response time tracking**: How long queries take
- **Resource usage monitoring**: System resource consumption
- **Consistency monitoring**: Whether consistency guarantees were met
- **Error rate tracking**: Frequency and types of failures

## Core Principles

1. **Queries observe state**: Queries never change state
2. **Queries are repeatable**: Same inputs produce same outputs
3. **Queries respect consistency**: Declared consistency must be met
4. **Queries are secure**: Authorization and isolation are enforced
5. **Queries are auditable**: All query execution is fully recorded

## Cross-Reference Dependencies

This query semantics model integrates with:
- request-semantics.md for request classification and lifecycle
- command-semantics.md for write operation contrast
- authorization-hooks.md for authorization placement and timing
- Phase 28 runtime context semantics for execution context requirements
- Phase 29 permission semantics for authorization evaluation
- Phase 31 lifecycle semantics for time-bound and trust validation
- Phase 30 identity semantics for identity binding and tenant rules
