# Agent Helper Guide: CI4 Application Template Folder Structure & Usage

## Overview
This guide helps AI agents understand the CI4 Application Template project structure, a CodeIgniter 4 (CI4) application with AI integrations. The structure follows `.windsurfrules` v1.3.1 for governance-compliant development. Use this to navigate, resume work, and maintain the project.

## Root Directory: CI4 Application Template/
- **Purpose**: Project root for CI4 application.
- **Key Files**:
  - `.windsurfrules`: Governance ruleset (read first for all actions).
  - `README.md`: Project overview and setup instructions.
  - `VERSION`: Current version (single source of truth).
  - `.env.example`: Environment configuration template.
  - `Dockerfile`: Container setup for deployment.
  - `docker-compose.yml`: Multi-container setup (app + DB).
- **Usage**: Copy entire folder into fresh CI4 install. Commit `.windsurfrules` first.

## app/
- **Purpose**: CI4 application code (controllers, models, views, libraries).
- **Structure**: Standard CI4 (Controllers/, Models/, Views/, Config/, etc.).
- **Usage**: Develop core CMS logic here. Follow CI4 conventions and coding standards in `docs/developer-guide/coding-standards.md`.

## public/
- **Purpose**: Web-accessible assets.
- **Subfolders**:
  - `dashboard/`: Visual project observatory.
    - `index.html`: Main dashboard with charts and status.
    - `assets/`: CSS/JS for dashboard.
    - `data/`: JSON data for dynamic content.
- **Usage**: Access dashboard at `public/dashboard/index.html` for project health. Update data JSONs with progress.

## writable/
- **Purpose**: CI4 writable directory (logs, cache, sessions).
- **Usage**: Automatically managed by CI4. Check logs here for debugging.

## governance/
- **Purpose**: Core governance artifacts for project management and continuity.
- **Key Files**:
  - `project-summary.md`: High-level project overview, specs, status.
  - `assumptions-log.md`: Logged assumptions for traceability.
  - `progress-log.md`: Detailed progress tracking and milestones.
  - `decision-log.md`: Major decisions made.
  - `prompt-log.md`: All prompts given to agents.
  - `framework-observations.md`: Notes on CI4 and conventions.
  - `roadmap.md`: High-level tasks and timeline.
  - `system-context.md`: Bootstrap context for new chats/agents.
- **Subfolders**:
  - `backups/`: Manual backups of governance files (README.md).
  - `checksums/`: Integrity check for `.windsurfrules` (.windsurfrules.sha256).
- **Usage**: Read `project-summary.md` and `system-context.md` to resume work. Log all actions in `progress-log.md`. Update `prompt-log.md` with interactions.

## docs/
- **Purpose**: Comprehensive documentation hub.
- **Subfolders**:
  - `README.md`: Documentation index.
  - `user-guide/`: End-user docs (overview, getting-started, tasks, FAQ, screenshots/).
  - `developer-guide/`: Technical guides (setup, architecture, standards, security, contribution).
  - `architecture/`: System design (overview, modules, multi-tenancy, AI integration, deployment).
  - `adr/`: Architecture Decision Records (README.md, ADR-001, template.md).
  - `llm-handoff/`: Context for AI agents (project-context.md, technical-context.md, current-state.md, how-to-resume.md, how-to-use-daily.md).
- **Usage**: Reference for all documentation needs. Update with code changes. Use `llm-handoff/` for new agent onboarding.

## backups/
- **Purpose**: Manual and automated backups.
- **Subfolders**:
  - `governance/`: Backups of governance files.
  - `docs/`: Backups of documentation.
  - `database/`: Database dumps.
- **Usage**: Backup before major changes. Use for recovery.

## How to Use This Structure
1. **Starting/Resuming**: Read `.windsurfrules`, then `governance/project-summary.md` and `governance/system-context.md`.
2. **Development**: Follow rules in `.windsurfrules`. Log progress in `governance/progress-log.md`. Update docs in `docs/`.
3. **Dashboard**: Monitor health at `public/dashboard/index.html`. Update JSON data in `public/dashboard/data/`.
4. **Deployment**: Use `.env.example`, `Dockerfile`, `docker-compose.yml`. Follow `docs/architecture/deployment-strategy.md`.
5. **Governance**: All decisions logged in `governance/`. Assumptions in `assumptions-log.md`.
6. **Documentation**: Keep in sync. Use ADRs for decisions.
7. **Backups**: Create before changes.
8. **New Agents**: Use `docs/llm-handoff/` for context.

## Key Principles
- **First-Class Governance**: Treat logs/docs as code.
- **Traceability**: Log everything in governance/.
- **Compliance**: Follow `.windsurfrules` strictly.
- **Continuity**: Use bootstrap artifacts for seamless handoffs.

## Quick Reference
- **Health Check**: Dashboard or `governance/progress-log.md`.
- **Rules**: `.windsurfrules`.
- **Setup**: `docs/developer-guide/setup.md`.
- **Resume**: `docs/llm-handoff/how-to-resume.md`.

This guide ensures agents can effectively navigate and maintain the project.