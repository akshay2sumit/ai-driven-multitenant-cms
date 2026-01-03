# Data Consistency

## Single vs Multi-Entity Mutations

### Single-Entity Mutation
**Definition**: Mutation affecting exactly one data entity with a single mutation intent.

**Characteristics**:
- **One entity**: Only one data entity is modified
- **One intent**: Single, clear mutation purpose
- **Simple consistency**: Straightforward consistency expectations
- **Direct rollback**: Failure results in simple entity state reversal

**Single-Entity Rules**:
- If mutation fails → entity remains unchanged
- No partial state exposure is possible
- Consistency is maintained at entity level
- Rollback is conceptually simple

### Multi-Entity Mutation
**Definition**: One command that modifies multiple related entities as part of a single semantic intent.

**Characteristics**:
- **One command**: Single operation initiates all changes
- **Multiple entities**: Several related entities are modified
- **One semantic intent**: All changes serve one business purpose
- **Complex consistency**: Requires coordination across entities

**Multi-Entity Examples (Conceptual)**:
- **Publish page + update index**: Publish content and update search index
- **Delete user + revoke access**: Remove user and invalidate their access artifacts
- **Create content + assign ownership**: Create new content and set ownership metadata
- **Update order + adjust inventory**: Modify order and adjust inventory counts

## Semantic Transaction Boundaries

### Transaction Boundary Definition
A command defines a semantic transaction boundary that encompasses all mutations triggered by that single command.

### Boundary Characteristics
- **Command-scoped**: All mutations from one command belong to one boundary
- **All-or-nothing**: Either all mutations succeed or none take effect
- **Semantic integrity**: Business logic integrity is maintained
- **Failure isolation**: Failure is contained within the boundary

### Boundary Success Requirements
For a semantic transaction to succeed:
- **All mutations succeed**: Every intended mutation must complete successfully
- **All constraints satisfied**: All business and system constraints must be met
- **Consistency maintained**: Data consistency must be preserved
- **Audit completeness**: Full audit trail must be generated

### Boundary Failure Semantics
If any mutation within the boundary fails:
- **Entire command fails**: The complete semantic transaction fails
- **No partial state**: System must not expose partial mutation results
- **Rollback behavior**: System behaves as if the command never started
- **Failure audit**: Failure must be fully audited

## Consistency Guarantee Levels

### A) Strong Consistency (Default)
**Definition**: Post-command reads reflect the complete mutation immediately.

**Characteristics**:
- **Immediate visibility**: All mutations are visible immediately after completion
- **Complete state**: Readers see the complete, consistent state
- **No partial results**: No intermediate or partial states are visible
- **Predictable behavior**: System behavior is predictable and deterministic

**Used For**:
- **User-facing state**: Data directly visible to users
- **Authorization-relevant data**: Data used for permission evaluation
- **Financial operations**: Data with financial implications
- **Critical business data**: Data critical to business operations

### B) Deferred Consistency (Explicit Only)
**Definition**: Some secondary effects may lag behind the primary mutation, but this is explicitly documented and limited.

**Characteristics**:
- **Primary consistency**: Core mutations are immediately consistent
- **Secondary lag**: Non-critical secondary effects may be delayed
- **Explicit documentation**: Deferred consistency must be explicitly documented
- **Limited scope**: Used only for specific, approved use cases

**Allowed Only When**:
- **Explicitly documented**: Deferred behavior is clearly documented
- **Not used for decisions**: Lagging data is not used for decision-making
- **Business appropriate**: Business logic can tolerate the delay
- **Security safe**: Security is not compromised by the delay

**Examples**:
- **Analytics counters**: Usage statistics and analytics data
- **Search index updates**: Non-critical search index updates
- **Reporting data**: Data used for reporting and analytics
- **Cache warming**: Non-critical cache population

## Failure & Rollback Semantics

### System MUST NOT Expose Partial State
**Core Principle**: Partial truth is worse than no truth.

**Requirements**:
- **Atomic visibility**: Either all mutations are visible or none are
- **No intermediate states**: No partially completed mutations are exposed
- **Consistent queries**: Queries see either before-state or after-state, never mixed
- **Predictable reads**: Read operations return predictable, consistent results

### Abort Conditions
System MUST abort semantic transactions on:

#### Authorization Failures
- **Permission denied**: Actor lacks required permissions
- **Capability insufficient**: Actor's capabilities are inadequate
- **Context inappropriate**: Mutation is not appropriate for current context
- **Tenant mismatch**: Actor tenant does not match data tenant

#### State Incompatibility
- **Entity state conflict**: Target entity state does not permit mutation
- **Constraint violation**: Business or system constraints would be violated
- **Dependency failure**: Required dependencies are not available
- **Integrity compromise**: Data integrity would be compromised

#### Tenant Violations
- **Cross-tenant access**: Attempt to access data in different tenant
- **Tenant boundary breach**: Attempt to cross tenant boundaries
- **Isolation failure**: Tenant isolation cannot be maintained
- **Multi-tenant conflict**: Conflict between tenant requirements

#### Retention Violations
- **Minimum retention breach**: Attempt to delete before minimum retention
- **Maximum retention exceed**: Attempt to keep data beyond maximum retention
- **Compliance violation**: Mutation would violate compliance requirements
- **Policy conflict**: Mutation conflicts with retention policies

### Rollback Semantics (Conceptual)
Rollback is conceptual at this design level, focusing on behavior rather than implementation:

#### Rollback Requirements
- **Complete reversal**: All mutations must be reversed
- **State restoration**: System must return to pre-command state
- **Resource cleanup**: All allocated resources must be cleaned up
- **Audit completion**: Rollback must be fully audited

#### Rollback Triggers
- **Mutation failure**: Any individual mutation fails
- **Constraint violation**: Any constraint is violated
- **Authorization failure**: Authorization is lost during execution
- **System error**: System-level error prevents completion

#### Rollback Guarantees
- **No residual effects**: No side effects remain after rollback
- **Consistent state**: System is in consistent state after rollback
- **Resource availability**: Resources are released after rollback
- **Audit integrity**: Audit trail remains complete and accurate

## Consistency Enforcement Rules

### Pre-Mutation Validation
Before executing mutations:
- **Authority verification**: Verify all required authorities
- **State validation**: Verify entity states permit mutation
- **Constraint checking**: Verify all constraints can be satisfied
- **Tenant verification**: Verify tenant boundaries are respected

### Runtime Coordination
During mutation execution:
- **Progress tracking**: Track progress of all mutations
- **Failure detection**: Detect failures quickly and accurately
- **State monitoring**: Monitor intermediate states for consistency
- **Resource management**: Manage resources throughout the process

### Post-Mutation Verification
After mutation completion:
- **Consistency verification**: Verify final state is consistent
- **Constraint validation**: Verify all constraints remain satisfied
- **Audit completion**: Ensure complete audit trail
- **Resource cleanup**: Clean up any temporary resources

## Cross-Reference Dependencies

This data consistency model integrates with:
- data-ownership.md for ownership in multi-entity operations
- data-mutation-rules.md for mutation authority in consistency boundaries
- data-lifecycle.md for lifecycle state consistency
- Phase 29 permission semantics for authorization in consistency checks
- Phase 30 identity semantics for actor identification in consistency validation
- Phase 32 request semantics for command-based consistency boundaries
- Phase 28 runtime context semantics for context-appropriate consistency

## Core Principles

1. **All-or-nothing semantics**: Multi-entity mutations are all-or-nothing
2. **No partial state exposure**: System never exposes intermediate states
3. **Strong consistency by default**: Immediate consistency is the default
4. **Explicit deferred consistency**: Deferred consistency must be explicit
5. **Complete rollback capability**: Failed mutations must be completely reversible

## Consistency Monitoring and Auditing

### Consistency Monitoring
System must monitor:
- **Transaction progress**: Progress of multi-entity mutations
- **State consistency**: Ongoing consistency verification
- **Performance impact**: Impact of consistency requirements on performance
- **Failure patterns**: Patterns of consistency failures

### Consistency Auditing
All consistency operations must emit:
- **Transaction boundaries**: Scope and boundaries of semantic transactions
- **Entity involvement**: All entities involved in mutations
- **Consistency level**: Consistency guarantees applied
- **Outcome information**: Success, failure, or rollback information
- **Performance data**: Performance and timing information

### Consistency Violation Handling
When consistency violations occur:
- **Immediate detection**: Violations must be detected immediately
- **Automatic rollback**: Automatic rollback must be initiated
- **Security isolation**: Violated data must be isolated
- **Incident response**: Security incident response must be initiated

## Special Consistency Considerations

### Cross-Tenant Consistency
Multi-tenant environments require:
- **Tenant isolation**: Consistency boundaries must not cross tenant boundaries
- **Independent transactions**: Each tenant's transactions are independent
- **No cross-tenant rollback**: Rollback cannot affect other tenants
- **Tenant-specific monitoring**: Consistency monitoring is tenant-specific

### High-Volume Operations
High-volume mutations require:
- **Performance optimization**: Consistency requirements optimized for performance
- **Batch processing**: Batch operations maintain consistency guarantees
- **Resource management**: Careful resource management for large operations
- **Scalable rollback**: Rollback mechanisms must scale with operation size

### System-Owned Data
System-owned data consistency:
- **System authority**: System operations have appropriate authority
- **Enhanced consistency**: System data may require enhanced consistency
- **Global visibility**: System data may need global consistency
- **Special audit**: System data operations require special audit attention
