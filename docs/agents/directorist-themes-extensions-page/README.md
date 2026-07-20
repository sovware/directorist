# Directorist Themes & Extensions Page Agent

This folder contains the repo-local skill/playbook for agents working on the Directorist admin `Themes & Extensions` page.

## What This Skill Is For

Use it when working on:

- `wp-admin/edit.php?post_type=at_biz_dir&page=atbdp-extension`
- Directorist account connection from the admin product page
- extension/theme subscription discovery
- product install, update, activation, bulk actions, or required-extension UI
- no-reload performance improvements on this page
- responsive redesign of this page from a new design

Do not use it for the frontend user dashboard or installed extension settings unless the task directly connects those systems back to this admin page.

## How To Use

Tell the agent:

```text
Use docs/agents/directorist-themes-extensions-page/SKILL.md for this task.
```

Then provide the design, bug, feature request, or admin URL.

The agent should inspect the live admin page with Agent Browser, read the mapped source files, cross-check Directorist docs when copy/product claims are involved, and produce a feasibility report before implementation.

## Dynamic Data Policy

This skill must not store site-specific runtime data. Product counts, installed items, subscribed products, update counts, connected user/account state, screenshots, and network logs must be collected fresh during each task and treated as temporary evidence only.

## Files In This Package

- `SKILL.md`: required agent workflow and safety rules
- `references/page-architecture-map.md`: code, templates, assets, hooks, endpoints, and remote dependencies
- `references/api-data-flow-report.md`: step-by-step source/API flow from page load to rendered sections and product actions
- `references/dynamic-data-contract.md`: dynamic data shapes and collection methods
- `references/account-license-journey-map.md`: account, license, subscription, and product-action journeys
- `references/performance-improvement-guide.md`: reload-heavy behavior and safe no-reload strategy
- `references/rewrite-issue-register.md`: full rewrite issue notes, fix priorities, and regression checklist
- `references/qa-checklist.md`: verification matrix and destructive-action safeguards

## Maintenance

Update this package only with durable architecture facts, stable contracts, confirmed issue patterns, and safe workflow improvements. Do not add local runtime snapshots.
