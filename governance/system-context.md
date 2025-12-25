# System Context for CI4 Application Template

## Project Purpose and Goals
CI4 Application Template is an AI-integrated Content Management System built on CodeIgniter 4, designed to provide intelligent content creation, management, and delivery capabilities. Goals include AI-assisted workflows, scalable multi-tenant platform, and maintainable code via governance rules.

## Current Project Status and Phase
- Phase: Structure Creation (Governance Complete)
- Status: Active
- Completion: Directory structure, docs, governance artifacts created. Ready for implementation.

## Confirmed Technical Specifications
- Primary Language: PHP
- Framework: CodeIgniter 4
- Application Type: Web / API / Hybrid (CMS)
- Deployment Environment: Flexible (shared hosting, VPS, container, cloud)
- OS/Platform: Linux (assumed)
- Public Entry-Point: Web dashboard and API
- Configuration: Environment-driven
- Database: Flexible (MySQL/PostgreSQL)
- Migration Strategy: CI4 migrations
- Constraints: None
- Hosting: Flexible

## High-Level Architecture Overview
- Frontend: HTML/JS dashboard for observability
- Backend: CI4 MVC with AI service integrations
- Data Layer: Database with tenant isolation
- Governance: Comprehensive logging, ADRs, progress tracking

## Key Decisions and ADR References
- ADR-001: Environment Readiness - Standard CI4 setup

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