# Directorist Settings Panel Agent Skill

This folder contains a tracked repo-local skill/playbook for agents working on the Directorist settings panel. It is intentionally stored in `docs/agents/directorist-settings-panel/` because `.agents/` is ignored in this repo.

## What This Skill Is For

Use it when you want an agent to:

- redesign the Directorist settings panel from a Figma design, screenshot, or idea
- add a new settings-panel feature
- add or adjust settings fields
- audit settings-panel limitations before implementation
- produce a report showing what is easy, what is complex, and what needs your decision

## How To Use

When starting a settings-panel task, tell the agent:

```text
Use docs/agents/directorist-settings-panel/SKILL.md for this task.
```

Then provide one of these:

- a Figma node/link or screenshot
- a written feature idea
- the current problem you want solved
- the settings page URL you want tested

The agent should first inspect the current source, then produce a feasibility report before making code changes.

## Expected First Output

The first useful output should be a report with:

- design or feature summary
- what can be achieved easily
- what is complex or risky
- affected settings/data/UI areas
- questions before risky work
- verification plan

If the change is visual-only and low risk, the report should say so. If the change affects storage, settings keys, AJAX save behavior, permissions, nonce handling, field contracts, layout structure, or extension-facing hooks, the agent must ask before implementation.

## Build Policy

The agent must not run these commands without your approval:

- `npm run dev`
- `npm run dev-vue`
- `npm run prod`
- any formatter or build command that rewrites tracked files

Your preferred workflow is to run `npm run dev-vue` manually when needed. The agent can then use the local admin page for browser QA if the compiled output is available.

## Local Settings URL

Default local page for QA:

```text
http://directorist-core.local/wp-admin/edit.php?post_type=at_biz_dir&page=atbdp-settings
```

## Files In This Package

- `SKILL.md`: the actual agent instructions
- `references/current-settings-panel-map.md`: architecture map and known limitations
- `references/settings-option-catalog.md`: grouped settings option map with purpose notes
- `templates/feasibility-report.md`: reusable report template

## Maintenance

When a future settings-panel task reveals a confirmed architecture fact, limitation, or setting-key change, update the reference notes in this folder. Keep the skill concise and keep detailed discoveries in `references/`.
