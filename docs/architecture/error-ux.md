# Error UX

## Core Trust Principle

Transparency without exposure builds trust; exposure without context destroys it.

Users must be told the truth, but not the system's internal truth. Users deserve clarity, not confusion, and must never be blamed.

## User Experience Semantics

### Error UX (Expected Issues)
When an error occurs, users see:

**What Users See**:
- Clear, simple explanation of what happened
- What they can do next (if applicable)
- Non-technical, non-blaming language
- Action-oriented guidance when safe

**Message Characteristics**:
- **Non-technical**: No implementation details or jargon
- **Non-blaming**: Focus on the issue, not user actions
- **Action-oriented**: Suggest specific, safe next steps when possible
- **Consistent**: Same error produces same message

**Error UX Examples**:
- "You don't have permission to perform this action."
- "This item cannot be published in its current state."
- "Please fill in all required fields before continuing."
- "The file you uploaded is not supported."

### Failure UX (Critical Issues)
When a failure occurs, users see:

**What Users See**:
- Generic, calm message acknowledging the issue
- No technical details or system internals
- Assurance that the issue is being addressed
- Guidance on when to try again (if appropriate)

**Message Characteristics**:
- **Generic**: No specific technical information
- **Calm**: Reassuring tone without alarm
- **Honest**: Acknowledges that something went wrong
- **Safe**: No information that could compromise security

**Failure UX Examples**:
- "Something went wrong. Please try again later."
- "This action could not be completed safely."
- "We're experiencing technical difficulties. Please try again in a few minutes."
- "The system is temporarily unavailable. Please try again later."

## Consistency of Messaging

### Messaging Consistency Rules
- **Same failure → same user message**: Identical conditions produce identical messages
- **No message variation**: Messages don't change based on internal state
- **No "sometimes it works" UX**: Consistent behavior builds confidence
- **Predictable responses**: Users can predict what they'll see

### Message Template Enforcement
- **Template-based**: All user messages come from approved templates
- **Parameter substitution**: Only safe parameters can be substituted
- **Version control**: Message templates are versioned and controlled
- **Localization ready**: Templates support localization when needed

### Consistency Benefits
- **User confidence**: Consistent responses build user trust
- **Support efficiency**: Support teams can anticipate user questions
- **Security**: Consistent messages prevent information leakage
- **Maintainability**: Centralized message management

## Security-Aware Messaging Rules

### Messages MUST NOT Reveal
**Internal Information**:
- Internal identifiers (user IDs, session IDs, etc.)
- System paths or file names
- Database details or table names
- Server information or IP addresses
- Stack traces or error codes

**Security Information**:
- Authorization structure or permissions
- Security measures in place
- Vulnerability details
- Attack patterns or attempts

**Tenant Information**:
- Existence of other tenants
- Cross-tenant data presence
- Tenant-specific configurations
- Multi-tenant architecture details

**Business Information**:
- Internal business logic
- Revenue or business metrics
- Competitive information
- Strategic business details

### Safe Message Construction
**Allowed Elements**:
- Pre-approved message templates
- Safe, non-sensitive parameters
- Generic action guidance
- Standard timing information

**Prohibited Elements**:
- Dynamic message construction
- Direct error message exposure
- System state information
- Internal process details

## Trust Preservation Strategies

### Predictability
**Definition**: Errors behave consistently and predictably.

**Implementation**:
- Same inputs produce same outcomes
- Error conditions produce consistent messages
- System behavior is predictable across sessions
- Error handling follows established patterns

**Benefits**:
- Users learn to trust system responses
- Support can anticipate user issues
- Testing becomes more reliable
- System behavior is more understandable

### Honesty (Bounded)
**Definition**: Admit when failures occur without over-explaining.

**Implementation**:
- Acknowledge failures honestly
- Don't speculate about causes
- Don't make promises that can't be kept
- Provide accurate timing information when possible

**Benefits**:
- Builds long-term trust
- Reduces user frustration
- Manages user expectations
- Maintains credibility

### Control
**Definition**: User actions never worsen system state.

**Implementation**:
- Retrying failed actions is safe
- User navigation doesn't cause additional errors
- User input validation prevents new errors
- System protects against user mistakes

**Benefits**:
- Users feel safe using the system
- Reduces user anxiety about making mistakes
- Prevents error cascades
- Maintains system stability

## Forbidden UX Patterns

### Prohibited Patterns
**Never Use These Patterns**:
- **Blaming the user**: "You entered invalid data" (use "Data format is not recognized")
- **Showing stack traces**: Never expose technical details to users
- **"Unknown error" without guidance**: Always provide some direction
- **Exposing partial success**: Never show "Partially completed" to users
- **UI masking backend failures**: Don't pretend everything is working when it's not

### Specific Prohibited Practices
**Error Handling**:
- Catching exceptions and showing generic messages without logging
- Swallowing errors without user notification
- Showing different messages based on user roles or permissions
- Revealing error codes or technical identifiers

**User Interaction**:
- Disabling all user interface elements during errors
- Showing modal dialogs that block all interaction
- Auto-retrying failed operations without user consent
- Hiding failed operations from user view

**Information Disclosure**:
- Revealing system architecture details
- Showing database query information
- Exposing API endpoints or internal service names
- Displaying network or server information

## Message Categorization

### Error Message Categories
**Validation Errors**:
- Focus on what needs to be corrected
- Provide specific guidance on fixes
- Use positive, instructional language
- Examples: "Please enter a valid email address"

**Authorization Errors**:
- State the denial clearly but politely
- Don't reveal what permissions exist
- Suggest contacting appropriate personnel if needed
- Examples: "You don't have permission to view this page"

**Business Rule Errors**:
- Explain the business constraint clearly
- Suggest alternatives when possible
- Don't reveal internal business logic
- Examples: "This item cannot be deleted while in use"

**System Errors**:
- Acknowledge the issue generically
- Provide timing information when available
- Suggest trying again later
- Examples: "System temporarily unavailable, please try again later"

## Message Localization Considerations

### Localization Requirements
- **Cultural sensitivity**: Messages must be culturally appropriate
- **Language clarity**: Translations must be clear and natural
- **Context preservation**: Meaning must be preserved across languages
- **Consistency maintenance**: Consistent experience across all languages

### Localization Process
- **Template-based approach**: Use templates designed for localization
- **Professional translation**: Use professional translators, not automated tools
- **Context review**: Review translations in context
- **User testing**: Test localized messages with target users

## Cross-Reference Dependencies

This error UX model integrates with:
- error-semantics.md for error classification affecting user experience
- error-visibility.md for visibility rules and exposure guidelines
- failure-containment.md for user experience during failure containment
- recovery-semantics.md for user experience during recovery processes
- Phase 32 request semantics for error handling in user requests
- Phase 33 data consistency for consistency error user presentation
- Phase 30 trust boundaries for trust-preserving user interactions

## Core Principles

1. **User safety first**: User actions must never worsen system state
2. **Clarity over technical detail**: Users need understanding, not technical information
3. **Consistency builds trust**: Same conditions produce same user experiences
4. **Security over transparency**: Never expose information that could compromise security
5. **Honesty without exposure**: Tell the truth without revealing dangerous details

## Enforcement Rules

### Message Enforcement
- All user messages must come from approved templates
- Message templates must be security-reviewed
- Message substitutions must be validated
- Message consistency must be tested

### UX Enforcement
- User interactions must be tested for safety
- Error flows must be user-tested
- Recovery experiences must be validated
- Trust preservation must be verified

### Security Enforcement
- All user-facing content must be security-reviewed
- Information disclosure must be prevented
- Security testing must include UX scenarios
- Security violations must trigger immediate fixes

## Special UX Considerations

### Multi-Tenant UX
- Error messages must not reveal tenant information
- UX must be consistent across all tenants
- Tenant-specific branding must be maintained in error states
- Cross-tenant errors must be handled carefully

### Mobile UX
- Error messages must be optimized for mobile screens
- Error flows must work well on touch interfaces
- Network-related errors must be handled gracefully
- Offline scenarios must be considered

### Accessibility UX
- Error messages must be accessible to screen readers
- Error states must be navigable by keyboard
- Color coding must not be the only indicator
- Error timing must respect accessibility needs
