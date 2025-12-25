This guide explains how to use the .windsurfrules system
in daily software development.

------------------------------------------------------------
1. STARTING A NEW PROJECT
------------------------------------------------------------
1. Copy the .windsurfrules file into the root of the project.
2. Commit it as the very first commit.
3. Start your AI session with the instruction:
   "Follow .windsurfrules strictly."

The agent will:
- Perform the Project Start Ritual
- Ask for technical and deployment specifications
- Design the project structure
- Generate required documentation, logs, and dashboard artifacts

------------------------------------------------------------
2. DURING DAILY DEVELOPMENT
------------------------------------------------------------
- Always let the agent ask clarifying questions before major changes.
- Expect self-audits before writing or refactoring significant code.
- Treat documentation and logs as first-class outputs.
- Use the dashboard as the single source of truth for project status.

------------------------------------------------------------
3. MAKING CHANGES
------------------------------------------------------------
When making changes to:
- Technical specifications
- Project structure
- Major features
- Deployment strategy

The agent MUST:
- Update architecture documentation
- Update project-summary.md
- Update dashboard data
- Log progress in progress-log.md

------------------------------------------------------------
4. RESUMING WORK IN A NEW CHAT OR AI
------------------------------------------------------------
1. Open /project/system-context.md
2. Copy its contents
3. Paste it into the new chat
4. Instruct the AI:
   "Continue this project under the existing .windsurfrules."

------------------------------------------------------------
5. DEPLOYMENT PRACTICE
------------------------------------------------------------
- Deployment is planned from Day 1.
- Keep the project deployable at all times.
- Deployment readiness must be visible in documentation and dashboard.
- Any deployment blocker must be logged and resolved.

------------------------------------------------------------
6. BEFORE MAJOR MILESTONES
------------------------------------------------------------
- Ensure self-audit passes
- Ensure dashboard health is green
- Ensure deployment readiness is confirmed
- Ensure backups are taken

------------------------------------------------------------
7. LONG-TERM USAGE
------------------------------------------------------------
- Treat .windsurfrules as a locked governance document.
- Do not bypass rules for speed.
- Update rules only with version increments and justification.

------------------------------------------------------------
END OF APPENDIX A
------------------------------------------------------------