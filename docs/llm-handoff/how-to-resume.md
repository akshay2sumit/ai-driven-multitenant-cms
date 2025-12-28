# How to Resume Work

## Agent Rotation Rule
- **Mandatory Rotation**: Start a new chat every 2 prompts
- **Purpose**: Prevent context window overflow
- **Exception**: Documentation updates may extend to 3 prompts if needed

## Governance-First Workflow
1. **Before Any Action**:
   - Read and understand .windsurfrules
   - Review all ADRs (especially ADR-002 and ADR-003)
   - Check governance/decision-log.md
   - Read docs/llm-handoff/current-state.md

2. **Documentation Requirements**:
   - All decisions must be documented
   - All prompts must be logged
   - All code changes must reference an ADR or decision log entry

## For New Chats
1. **First Action**:
   ```
   Read and understand:
   - .windsurfrules
   - governance/decision-log.md
   - docs/llm-handoff/current-state.md
   - All ADRs in docs/adr/
   ```

2. **Second Action**:
   - Update prompt-log.md with your presence
   - Note any context you need to carry forward

## Key Governance Files
- `.windsurfrules` - Project constitution
- `governance/decision-log.md` - All project decisions
- `governance/progress-log.md` - Project status and milestones
- `docs/llm-handoff/current-state.md` - Current system state
- `docs/adr/` - Architecture Decision Records

## Resuming Development
1. **Check Current Phase** in progress-log.md
2. **Verify Permissions** in current-state.md
3. **Follow Documentation** requirements
4. **Update Documentation** with any changes
5. **Log All Actions** in appropriate logs

## When in Doubt
1. Check the rules (.windsurfrules)
2. Check ADRs for architectural decisions
3. Ask for clarification
4. Document the question and any assumptions