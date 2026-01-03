# Command Semantics

## Explicit Intent Requirement

A command represents an explicit, declarative intent to change system state. Commands are not request wrappers, API calls, or business logic - they are clear statements of intended change.

### Core Characteristics

- **Declarative**: Commands clearly state what change is intended
- **Explicit**: Intent must be declared, not inferred from context
- **Atomic**: Commands represent single, complete state changes
- **Authorizable**: Commands must be evaluable for permission

### Intent Declaration Rules
Commands MUST clearly declare:
- **What change is intended**: Specific state mutation
- **Target resources**: What entities or data will be affected
- **Expected outcome**: Resulting state after execution

**Forbidden Intent Sources**:
- Context inference
- UI state analysis
- Past action patterns
- Implicit assumptions

## Authorization is Mandatory

Every command MUST pass through complete authorization before execution:

### Authentication Prerequisite
- **Identity verification** using Phase 30 semantics
- **Trust level establishment** using Phase 31 semantics
- **Tenant binding verification** for actor and resource

### Permission Evaluation
- **Capability checking** using Phase 29 semantics
- **Role composition evaluation** for role-based permissions
- **Contextual authorization** for execution context appropriateness

### No Safe Commands
- **No "safe commands"**: All commands require authorization
- **No admin shortcuts**: No bypass mechanisms for privileged users
- **No implicit permissions**: All permissions must be explicit

## Side-Effect Rules

### Allowed Side Effects
Commands may produce these intended side effects:

#### State Mutation
- **Direct state changes**: Intended modifications to system state
- **Cascading updates**: Related state changes as part of business logic
- **Status transitions**: Entity lifecycle state changes

#### Event Emission
- **Business events**: Domain events representing state changes
- **Audit events**: Comprehensive audit trail entries
- **Notification events**: System notifications as appropriate

### Forbidden Side Effects
Commands must NOT produce these side effects:

#### Silent Mutations
- **Hidden state changes**: Unintended or undocumented mutations
- **Cross-context mutations**: Changes outside command's intended scope
- **Cascading side effects**: Uncontrolled chain reactions

#### Unintended Impacts
- **External system changes**: Modifications to external systems
- **Performance impacts**: Unintended system performance effects
- **Resource exhaustion**: Unbounded resource consumption

### Side Effect Traceability
Every side effect must be:
- **Expected**: Part of intended command outcome
- **Auditable**: Recorded in audit trail
- **Traceable**: Linked to the originating command

## Idempotency Semantics

### Definition
Idempotency is a property of command semantics where repeated execution results in the same final state. Idempotency is about command behavior, not transport mechanisms.

### Command Categories

#### A) Naturally Idempotent Commands
Commands that set specific state regardless of current state.

**Examples**:
- Set status = published
- Update configuration value
- Assign specific permission

**Rules**:
- Repeating the command does not change the final outcome
- Must not emit duplicate side effects
- Must not create duplicate entities

#### B) Non-Idempotent Commands
Commands that create new entities or generate unique identifiers.

**Examples**:
- Create new page
- Generate unique identifier
- Send notification

**Rules**:
- System MUST detect replay attempts
- Duplicate execution MUST be prevented or rejected
- No silent duplication allowed

### Idempotency Determination
Idempotency must be:
- **Determinable**: System can determine if command is idempotent
- **Declared**: Command must declare its idempotency nature
- **Consistent**: Same command always has same idempotency classification

## Replay Handling

### Replay Detection
System MUST detect and handle command replays:

#### Detection Mechanisms
- **Command identifiers**: Unique command identification
- **Intent signatures**: Hash of intended change
- **Execution tracking**: Record of executed commands

#### Replay Responses
- **Idempotent commands**: May succeed with same outcome
- **Non-idempotent commands**: Must be rejected
- **Ambiguous cases**: Must be denied

### Replay Prevention
For non-idempotent commands:
- **Duplicate prevention**: Block duplicate execution
- **Explicit rejection**: Clear rejection of replay attempts
- **Audit logging**: Log replay attempts as security events

## Fail-Closed Command Rules

### Validation Failures
- **Missing intent**: Commands without clear intent are denied
- **Authorization failure**: Unauthorized commands are denied
- **Idempotency ambiguity**: Commands with unclear idempotency are denied

### Execution Failures
- **Partial success**: Commands that partially succeed are forbidden
- **Side-effect failures**: Commands with unintended side effects are denied
- **Context violations**: Commands in wrong context are denied

### Replay Failures
- **Replay detection failure**: If replay cannot be determined, deny
- **Duplicate execution**: Non-idempotent command duplicates are rejected
- **Ambiguous replays**: Unclear replay situations result in denial

## Explicitly Forbidden Command Behaviors

### Prohibited Behaviors
- **Hidden queries**: Commands that perform read operations with side effects
- **Auto-retry without protection**: Commands that retry without replay protection
- **Partial success**: Commands that succeed partially
- **Validation mutations**: Commands that mutate state during validation

### Forbidden Patterns
- **Command chaining**: Commands that trigger other commands implicitly
- **Conditional side effects**: Side effects based on hidden conditions
- **State-dependent intent**: Commands that change intent based on current state
- **Context escalation**: Commands that attempt to upgrade their context

## Audit and Evidence Requirements

### Mandatory Command Audit
Each command must emit:

#### Command Identification
- **Command ID**: Unique command identifier
- **Command type**: Specific command classification
- **Intent declaration**: Stated intent of the command

#### Actor Information
- **Actor identity**: Authenticated identity executing command
- **Actor permissions**: Permission level used for authorization
- **Tenant context**: Actor and resource tenant information

#### Execution Information
- **Intended change**: What the command intended to do
- **Actual outcome**: What actually happened
- **Side effects**: All side effects produced
- **Idempotency handling**: How idempotency was handled

#### Security Information
- **Authorization decision**: Permission evaluation result
- **Replay detection**: Whether replay was detected and handled
- **Security events**: Any security-relevant events

### Event Emission Requirements
Commands must emit:
- **Business events**: Domain events for state changes
- **Audit events**: Comprehensive audit trail
- **Security events**: Security-relevant notifications

## Core Principles

1. **Commands are explicit**: Intent must be clearly declared
2. **Commands are always authorized**: No command bypasses authorization
3. **Commands have predictable side effects**: All side effects are intended and traceable
4. **Commands handle idempotency deterministically**: Idempotency behavior is well-defined
5. **Commands are auditable**: Every command execution is fully recorded

## Cross-Reference Dependencies

This command semantics model integrates with:
- request-semantics.md for request classification and lifecycle
- query-semantics.md for read operation contrast
- authorization-hooks.md for authorization placement and timing
- Phase 28 runtime context semantics for execution context requirements
- Phase 29 permission semantics for authorization evaluation
- Phase 31 lifecycle semantics for time-bound and trust validation
- Phase 30 identity semantics for identity binding and trust levels
