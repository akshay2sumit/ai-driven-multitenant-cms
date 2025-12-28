# System Context for CI4 Application Template

## Project Purpose and Goals
CI4 Application Template is an AI-integrated Content Management System built on CodeIgniter 4, designed to provide intelligent content creation, management, and delivery capabilities. Goals include AI-assisted workflows, scalable multi-tenant platform, and maintainable code via governance rules.

## Current Project Status and Phase
- Phase: 6 - Post-Remediation
- Status: Active
- Completion: Core tenant resolution and CMS Pages module implemented. Documentation updated to reflect current state.

## System Overview

The AI-Driven Multi-Tenant CMS is a modular, extensible web platform intended to act as a long-term foundation for multiple business applications under a single governed system.

The CMS is composed of:
- A core CMS layer (framework, routing, identity, tenancy)
- Tenant-specific configuration and data
- Optional AI-assisted automation layers
- Strict governance and documentation artifacts

CodeIgniter 4 serves as the execution framework, while governance artifacts serve as the true system authority.

## Key Constraints

- Must run on shared / reseller hosting
- Must avoid hard dependencies on background workers or queues
- Must degrade gracefully if AI services are unavailable
- Must remain understandable to human developers and operators

## Actors

- **End Users**: Non-technical business users managing content and operations
- **Tenants**: Isolated business entities within the same installation
- **Developers / Agents**: Humans or AI agents operating strictly under governance
- **Governance Authority**: Controls sequencing, scope, and architectural evolution

## High-Level Boundaries

- **CMS Core ≠ Business Logic**
- **Governance ≠ Application Code**
- **AI Assistance ≠ System Control**

AI may assist, but must never silently decide or mutate system behavior.

## Confirmed Technical Specifications
- Primary Language: PHP 8.1+
- Framework: CodeIgniter 4.6.4
- Application Type: Multi-tenant Web CMS
- Deployment Environment: Shared hosting / VPS (LAMP/LEMP)
- OS/Platform: Linux (Ubuntu 22.04 LTS)
- Database: MySQL 8.0+ / MariaDB 10.5+
- Web Server: Apache 2.4+ / Nginx 1.18+
- Cache: File-based (default), Redis/Memcached (optional)

## Implementation Status
- [x] Core Framework: CodeIgniter 4.6.4
- [x] Multi-tenancy: Path-based resolution (/t/{tenant}/...)
- [x] CMS Pages: Basic CRUD operations (non-public)
- [ ] User Management: Not started
- [ ] Media Management: Not started
- [ ] Theme System: Not started
- [ ] API Endpoints: Not started
- [ ] AI Integration: Not started

## High-Level Architecture Overview
- Frontend: HTML/JS dashboard for observability
- Backend: CI4 MVC with AI service integrations
- Data Layer: Database with tenant isolation
- Governance: Comprehensive logging, ADRs, progress tracking

## Key Decisions and ADR References
- ADR-001: Environment Readiness - Standard CI4 setup
- ADR-002: System Architecture Baseline - Multi-tenant design
- ADR-003: Tenant Resolution - Path-based strategy

## Current Project Structure Summary
- app/: CI4 application code
- public/dashboard/: Visual observatory
- writable/: CI4 writable
- governance/: Logs, summaries, backups
- docs/: User/developer guides, architecture, ADRs
- backups/: Governance, docs, database backups
- .windsurfrules: Governance ruleset
- README.md: Entry point
- VERSION: 1.0.0

## Open Tasks and Roadmap Highlights
1. Implement core CMS controllers/models
2. Integrate AI APIs
3. Set up multi-tenancy
4. Add testing suite
5. Configure deployment

## Known Issues and Risks
- AI API dependencies require secure key management
- Multi-tenancy implementation complexity
- Deployment environment variability

## Governance Expectations
- All development follows .windsurfrules v1.3.1 strictly
- Log decisions, assumptions, prompts
- Maintain docs and dashboard sync
- Ensure continuous deployability

## How to Resume
1. Review .windsurfrules
2. Check governance/project-summary.md
3. Access public/dashboard/index.html for status
4. Follow docs/developer-guide/setup.md for development

This context enables seamless continuation across chats/agents.