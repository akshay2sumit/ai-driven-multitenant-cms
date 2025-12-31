# Full Project Documentation Taxonomy

## Definition of Full Project Documentation

**Full Project Documentation** constitutes the complete, organized body of documentation that provides comprehensive understanding of a project's purpose, architecture, implementation, operation, and governance. This documentation must be sufficient for complete project reconstruction, audit, and continuation by qualified personnel.

## Documentation Categories

### 1. Governance Documentation
**Status: Mandatory**

Documents that establish the authority, rules, and processes governing the project.

**Includes:**
- Governance frameworks and constitutions
- Project rules and compliance requirements
- Decision-making processes and authority structures
- Audit procedures and compliance verification
- Phase management and closure protocols
- Role definitions and boundaries

**Requirements:**
- Must be current and accessible
- Must be authoritative and enforceable
- Must include amendment procedures
- Must be audit-ready at all times

### 2. Architectural Decisions (ADRs)
**Status: Mandatory**

Formal records of significant architectural choices, their rationale, and consequences.

**Includes:**
- Architectural decision records
- Design trade-offs and alternatives considered
- Technical standards and conventions
- Integration decisions and interfaces
- Performance and scalability decisions
- Security and compliance architectural choices

**Requirements:**
- Each decision must have clear status
- Must include rationale and consequences
- Must be traceable and referenceable
- Must include implementation status

### 3. System Architecture Documentation
**Status: Mandatory**

Comprehensive documentation of the system's structure, components, and relationships.

**Includes:**
- System overview and high-level architecture
- Component diagrams and relationships
- Data flow and process documentation
- Security architecture and boundaries
- Integration patterns and interfaces
- Deployment architecture and environments

**Requirements:**
- Must be current with implementation
- Must include all major components
- Must be understandable to technical personnel
- Must be maintained in sync with changes

### 4. Current-State & Progress Tracking
**Status: Mandatory**

Documentation that tracks the project's current status, progress, and immediate context.

**Includes:**
- Current implementation status
- Phase completion and progress logs
- Immediate context and handoff information
- Known issues and limitations
- Current capabilities and boundaries
- Recent changes and their impacts

**Requirements:**
- Must be current and accurate
- Must reflect actual implementation state
- Must be updated at phase boundaries
- Must be sufficient for work continuation

### 5. Developer Documentation
**Status: Mandatory**

Documentation that enables developers to understand, modify, and extend the system.

**Includes:**
- Development environment setup
- Coding standards and conventions
- Build and deployment procedures
- Testing procedures and requirements
- Debugging and troubleshooting guides
- Contribution guidelines and processes

**Requirements:**
- Must be actionable and complete
- Must include all necessary procedures
- Must be maintained with code changes
- Must be sufficient for new developer onboarding

### 6. Testing Documentation
**Status: Mandatory**

Documentation that defines testing requirements, procedures, and results.

**Includes:**
- Testing strategy and approach
- Test environment setup and requirements
- Test procedures and automation
- Test results and coverage reports
- Quality assurance procedures
- Performance and security testing documentation

**Requirements:**
- Must cover all critical functionality
- Must include test execution procedures
- Must document test coverage and gaps
- Must be current with implementation

### 7. Deployment Documentation
**Status: Conditional**
**Condition: Required when deployment is planned or implemented**

Documentation that enables system deployment and operational management.

**Includes:**
- Deployment requirements and prerequisites
- Environment configuration and setup
- Deployment procedures and automation
- Operational monitoring and maintenance
- Backup and recovery procedures
- Scaling and performance optimization

**Requirements:**
- Must be sufficient for production deployment
- Must include all necessary configurations
- Must address security and compliance requirements
- Must include troubleshooting procedures

### 8. User Documentation
**Status: Conditional**
**Condition: Required when system has end users**

Documentation that enables users to understand and operate the system.

**Includes:**
- User guides and manuals
- Feature documentation and usage
- Configuration and customization guides
- Troubleshooting and support information
- Training materials and tutorials
- Frequently asked questions and support

**Requirements:**
- Must be understandable to target users
- Must cover all user-facing features
- Must include examples and use cases
- Must be maintained with feature changes

### 9. LLM / Agent Handoff Documentation
**Status: Conditional**
**Condition: Required when AI agents or LLMs participate in development**

Documentation that enables effective handoff between human and AI agents.

**Includes:**
- Project context and current state
- Technical context and constraints
- Agent permissions and boundaries
- Communication protocols and expectations
- Decision authority and escalation procedures
- Context preservation and recovery

**Requirements:**
- Must be sufficient for agent continuation
- Must clearly define boundaries and permissions
- Must include all relevant context
- Must be current with project state

## Documentation Classification

### Mandatory Documentation
Required for all projects regardless of size, complexity, or deployment status. Absence of mandatory documentation constitutes a project governance violation.

**Mandatory Categories:**
- Governance Documentation
- Architectural Decisions (ADRs)
- System Architecture Documentation
- Current-State & Progress Tracking
- Developer Documentation
- Testing Documentation

### Conditional Documentation
Required only when specific conditions or project characteristics are met. Projects must assess applicability and include conditional documentation when conditions are satisfied.

**Conditional Categories:**
- Deployment Documentation (when deployment is planned/implemented)
- User Documentation (when end users exist)
- LLM/Agent Handoff Documentation (when AI agents participate)

## Documentation Standards

### Quality Requirements
All documentation must be:
- **Current**: Reflects actual project state
- **Complete**: Covers all necessary information
- **Accessible**: Findable and readable by intended audience
- **Maintainable**: Can be kept current with reasonable effort
- **Auditable**: Sufficient for compliance and review

### Format Requirements
- Must use consistent formatting and structure
- Must include version control and change tracking
- Must be searchable and referenceable
- Must be stored in version control with the project
- Must use appropriate markup for readability

### Maintenance Requirements
- Must be reviewed and updated at phase boundaries
- Must be updated when corresponding components change
- Must include review and approval processes
- Must have defined ownership and responsibility

## Explicit Exclusions

### Chat History
**Chat history is NOT documentation.** Chat transcripts, conversation logs, and informal communications do not constitute project documentation regardless of their content or completeness.

**Rationale:**
- Chat history is not structured for reference
- Chat history lacks version control and audit trails
- Chat history is not maintained or curated
- Chat history may contain incomplete or incorrect information
- Chat history is not authoritative or binding

### Temporary Artifacts
Temporary files, draft documents, and work-in-progress artifacts are not considered documentation until they are formally reviewed, approved, and integrated into the documentation structure.

## Compliance and Audit

### Documentation Completeness
Projects must maintain complete documentation as defined by this taxonomy. Missing mandatory documentation constitutes a governance violation.

### Documentation Quality
Documentation must meet quality standards and be sufficient for audit, review, and project continuation.

### Documentation Currency
Documentation must be current with project implementation. Outdated documentation must be updated or clearly marked as historical.

### Documentation Accessibility
Documentation must be accessible to all authorized personnel and appropriately secured against unauthorized access.

### State Synchronization Requirement
Phase closure REQUIRES synchronization of docs/llm-handoff/current-state.md with actual completed phase. Absence of this synchronization INVALIDATES phase completion.

---

**Authority**: This taxonomy defines the complete and authoritative requirements for project documentation. All projects must comply with these requirements unless explicitly exempted by governance authority.

**Applicability**: This taxonomy applies to all projects regardless of technology, scale, or organizational context.

**Maintenance**: This taxonomy must be reviewed and updated as documentation practices evolve, but changes must maintain the principle of comprehensive, auditable documentation.
