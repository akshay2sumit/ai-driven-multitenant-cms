# Error Visibility

## Core Visibility Principle

The more dangerous the condition, the less detail exposed externally.

Security and trust requirements make this rule non-negotiable. Users deserve clarity, not internals. Systems deserve detail, not silence.

## Visibility Matrix

| Condition Type | Visible to User | Logged | Audited |
|----------------|-----------------|--------|---------|
| Error | Yes (safe) | Yes | Yes |
| Failure | No (generic) | Yes (detailed) | Yes |

## User-Facing Visibility Rules

### What Users Can See
**Safe to Expose**:
- High-level error descriptions
- Action guidance (when safe)
- Generic failure acknowledgments
- Non-technical explanations

**Safe Exposure Examples**:
- "You don't have permission to perform this action."
- "This item cannot be published in its current state."
- "Something went wrong. Please try again later."
- "This action could not be completed safely."

### What Users Cannot See
**Never Expose**:
- Stack traces
- Internal identifiers
- System topology information
- Database details
- Internal error codes
- File paths
- Server information
- Other tenant existence
- Authorization structure details
- Data presence across tenants

### User Message Characteristics
User-facing messages must be:
- **Non-technical**: No implementation details
- **Non-blaming**: Focus on the issue, not user fault
- **Action-oriented**: When safe, suggest next steps
- **Consistent**: Same condition produces same message
- **Secure**: No information leakage

## Internal Visibility Rules

### What Must Be Logged
**Comprehensive Internal Logging**:
- Full error details
- Context information
- Identity and tenant information
- Correlation identifiers
- Request details
- System state information
- Performance metrics
- Resource usage

### Log Content Requirements
**Every error/failure must log**:
- **Classification**: Error vs failure determination
- **Category**: Specific error/failure type
- **Severity**: Severity level assignment
- **Timestamp**: Precise occurrence time
- **Context**: Execution context and environment
- **Identity**: Actor and tenant information
- **Correlation ID**: Request correlation identifier
- **Stack trace**: Full technical details (internal only)
- **System state**: Relevant system state information

### Log Security Requirements
- Logs must be protected from unauthorized access
- Log retention must comply with data retention policies
- Log access must be audited
- Sensitive data in logs must be sanitized or encrypted

## Audit Trail Requirements

### Audit Content
Every error and failure must generate audit evidence:
- **Event type**: Error or failure classification
- **Category**: Specific error/failure category
- **Severity**: Assigned severity level
- **Outcome**: Denied, aborted, or completed
- **Timestamp**: Event occurrence time
- **Actor**: Identity of affected user or system
- **Tenant**: Tenant context
- **Correlation ID**: Request correlation identifier
- **User message**: Message key shown to user (not full text)

### Audit Security
- Audit trails must be immutable
- Audit access must be tightly controlled
- Audit retention must comply with regulations
- Audit integrity must be verifiable

## Safe Exposure Rules

### Exposure Decision Matrix
| Condition | Safe to Expose | Exposure Level |
|-----------|----------------|---------------|
| Client errors | Yes | High-level description |
| Domain errors | Yes | Business-level explanation |
| System errors | No | Generic message only |
| Failures | No | Generic acknowledgment only |

### Exposure Guidelines
**Client Errors**:
- Can expose what went wrong at high level
- Can suggest corrective actions
- Must not reveal internal implementation
- Must be consistent across occurrences

**Domain Errors**:
- Can explain business constraint violations
- Can suggest business-appropriate actions
- Must not reveal internal business logic
- Must maintain business confidentiality

**System Errors**:
- Must show generic message only
- Must not reveal system internals
- Must not suggest specific technical issues
- Must maintain system security

**Failures**:
- Must show generic acknowledgment
- Must not reveal failure details
- Must not suggest system vulnerabilities
- Must maintain security posture

## Logging vs Audit Distinction

### Logging Purpose
- **Operational monitoring**: System health and performance
- **Troubleshooting**: Technical diagnosis and resolution
- **Pattern analysis**: Identifying recurring issues
- **Capacity planning**: Resource usage trends

### Audit Purpose
- **Compliance**: Regulatory and policy compliance
- **Security**: Security incident tracking and response
- **Accountability**: Who did what and when
- **Legal**: Legal evidence and discovery

### Separation Requirements
- Logs may contain technical details
- Audit must contain business-relevant information
- Logs may be rotated or archived
- Audit must be preserved for required retention periods
- Log access may be broader
- Audit access must be highly restricted

## Cross-Reference Dependencies

This error visibility model integrates with:
- error-semantics.md for error vs failure classification
- failure-containment.md for failure visibility during containment
- recovery-semantics.md for recovery process visibility
- error-ux.md for user-facing message presentation
- Phase 32 request semantics for error visibility in request lifecycle
- Phase 33 data consistency for consistency error visibility
- Phase 30 trust boundaries for trust violation visibility

## Core Principles

1. **Security over transparency**: Never expose information that could compromise security
2. **Clarity over complexity**: Users need clear understanding, not technical details
3. **Consistency over variation**: Same conditions produce same user experiences
4. **Auditability over convenience**: Complete audit trails are mandatory
5. **Protection over exposure**: System internals must be protected

## Enforcement Rules

### Pre-Exposure Validation
Before exposing any information to users:
- **Security review**: Information must not compromise security
- **Privacy check**: Information must not violate privacy
- **Business review**: Information must not reveal business secrets
- **Consistency check**: Information must be consistent with similar cases

### Runtime Enforcement
During error/failure handling:
- **Message selection**: User messages must be from approved templates
- **Information filtering**: Internal details must be filtered out
- **Context preservation**: Necessary context must be maintained for logs
- **Correlation maintenance**: Correlation IDs must be preserved

### Post-Exposure Verification
After user notification:
- **Audit generation**: Complete audit trail must be generated
- **Log completion**: Internal logs must be completed
- **Security verification**: No sensitive information was exposed
- **Consistency verification**: User experience was consistent

## Special Visibility Considerations

### Multi-Tenant Environments
- Tenant information must never leak across boundaries
- Error messages must not reveal tenant existence
- Audit trails must maintain tenant isolation
- Cross-tenant errors must be handled with extreme care

### High-Security Environments
- Even less information may be exposed to users
- Internal logging may be more detailed
- Audit requirements may be more stringent
- Security review processes may be more rigorous

### Compliance Requirements
- Some regulations may require specific error disclosures
- Audit retention periods may be mandated
- Privacy requirements may limit logging
- Security standards may require specific protections
