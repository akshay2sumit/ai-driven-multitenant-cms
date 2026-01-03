# Coding Guardrails

**Phase**: 35 - Execution Readiness Gate  
**Status**: ENFORCED - Implementation Boundaries  
**Effective Date**: 2026-01-04  

## Allowed Coding Scope

### Implementation Layers
- **Application Layer**: Business logic within defined capabilities
- **Data Layer**: Database operations within established schema
- **Presentation Layer**: UI components within established patterns
- **Integration Layer**: External connections within security boundaries

### Permitted Activities
- **Feature Implementation**: Within defined capability taxonomy
- **Bug Fixes**: Within existing architectural patterns
- **Performance Optimization**: Within established boundaries
- **Security Hardening**: Within frozen security model
- **Testing Implementation**: Per defined testing strategy
- **Documentation**: For implemented features only

### Technology Constraints
- **PHP 8.1+**: As established in ADR-001
- **CodeIgniter 4.6.4**: Framework version locked
- **MySQL**: Database technology locked
- **Apache/Nginx**: Web server within established patterns

## Forbidden Practices

### Architectural Violations
- **NO** new system boundaries or contexts
- **NO** domain model modifications
- **NO** data ownership pattern changes
- **NO** security boundary modifications
- **NO** authentication/authorization changes
- **NO** event semantics modifications

### Implementation Anti-Patterns
- **NO** hardcoded tenant IDs
- **NO** cross-tenant data access
- **NO** public data exposure without authorization
- **NO** authentication bypasses
- **NO** session manipulation outside defined patterns
- **NO** direct database queries without abstraction

### Code Quality Violations
- **NO** uncommented complex business logic
- **NO** undocumented security decisions
- **NO** untested security-critical code
- **NO** production credentials in code
- **NO** SQL injection vulnerabilities
- **NO** XSS vulnerabilities

## Approval-Required Changes

### Security Changes
- **Authentication Modifications**: Require security review
- **Authorization Changes**: Require access control review
- **Data Access Pattern Changes**: Require data governance review
- **Session Management Changes**: Require security architecture review

### Architecture Changes
- **New Capabilities**: Require capability taxonomy review
- **Database Schema Changes**: Require data model review
- **API Endpoint Changes**: Require interface review
- **Integration Pattern Changes**: Require integration review

### Operational Changes
- **Deployment Process Changes**: Require operations review
- **Monitoring Changes**: Require observability review
- **Backup Procedure Changes**: Require data protection review

## No Semantic Drift Rules

### Terminology Enforcement
- **Canonical Vocabulary**: Use only established domain terms
- **Concept Consistency**: Maintain established concept definitions
- **Naming Conventions**: Follow established patterns exactly
- **Documentation Language**: Use approved terminology only

### Behavioral Consistency
- **Error Handling**: Follow established error semantics
- **State Management**: Follow established state change patterns
- **Event Patterns**: Follow established event semantics
- **Audit Requirements**: Follow established audit patterns

### Interface Consistency
- **API Patterns**: Follow established request/response patterns
- **UI Patterns**: Follow established interaction patterns
- **Data Formats**: Follow established data structure patterns
- **Security Patterns**: Follow established access control patterns

## Compliance Enforcement

### Code Review Requirements
- **Architecture Compliance**: Verify no architectural violations
- **Security Review**: Verify no security boundary violations
- **Pattern Compliance**: Verify established patterns followed
- **Documentation Review**: Verify adequate documentation

### Automated Validation
- **Static Analysis**: Enforce coding standards
- **Security Scanning**: Enforce security patterns
- **Dependency Checking**: Enforce technology constraints
- **Pattern Detection**: Enforce architectural patterns

### Audit Requirements
- **Change Tracking**: All changes must be tracked
- **Decision Recording**: All architectural decisions recorded
- **Violation Reporting**: All violations reported immediately
- **Compliance Reporting**: Regular compliance status reports

## Cross-References

- **Phases 28-34 Architecture**: Frozen architectural patterns and semantics
- **.windsurfrules**: Universal development standards and constraints
- **Phase Backbone**: Execution framework and phase boundaries
- **Implementation Guardrails**: Detailed implementation boundaries

## Violation Consequences

### Immediate Actions
- **Stop Work**: Immediately stop violating activities
- **Rollback**: Reverse any violating changes
- **Report**: Report violation to governance board
- **Document**: Document violation and root cause

### Corrective Actions
- **Process Review**: Review and improve processes
- **Training**: Additional training if needed
- **Tooling**: Improve tooling to prevent recurrence
- **Monitoring**: Enhanced monitoring for compliance

## Authority

These guardrails are established under:
- Phase 35 Execution Readiness Gate authority
- Frozen architecture constitutional basis
- Implementation readiness completion
- Security and governance requirements

**Enforced By**: Phase 35 Execution Agent  
**Effective**: 2026-01-04  
**Status**: ACTIVE ENFORCEMENT
