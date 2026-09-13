# Directorist extension documentation and skills

Canonical source-backed reference for the **40 verified official catalog products**, with a separate discoverable skill for each and one central router. Use [the router](ROUTER.md) for a ticket or [the A-Z index](INDEX.md) for a named product. Each skill loads identity plus the relevant short feature/topic, not the full corpus.

## Use in a future task

Invoke `$directorist-extension-router` with the issue text and known site/plugin context, or invoke a named skill such as `$directorist-ext-business-hours`. Automatic selection is enabled by default, but remains heuristic. The explicit router CLI makes selective routing observable:

```sh
python3 docs/extensions/scripts/query.py route "Business Hours missing only when no plan"
python3 docs/extensions/scripts/query.py route "io is not defined when messaging the listing owner"
python3 docs/extensions/scripts/query.py search directorist_form_field_data --product directorist-pricing-plans
python3 docs/extensions/scripts/query.py hooks directorist_single_item_template
python3 docs/extensions/scripts/query.py drift --product directorist-business-hours
```

Run commands from this Core checkout, or use the absolute `scripts/query.py` path. Output is bounded; use a product filter or a larger `--limit` for focused expansion. Identity/topic documents link pinned source files/symbols, data keys/call sites, integration candidates and concrete reproduction/regression recipes. Core contracts live once under `core/`. Large evidence ledgers are searchable on demand.

## Installation

```sh
python3 docs/extensions/scripts/install_skills.py
python3 docs/extensions/scripts/install_skills.py --check
```

The installer creates only missing `directorist-ext-*` and `directorist-extension-router` skills in `~/.codex/skills`, rewrites canonical links to this docs root and refuses to overwrite a different existing skill. For a reviewed future update, `--update-managed` can replace only a previously installed copy whose hash still matches its ownership marker; edited or unrelated skills remain protected. No existing support workflow skill is replaced. Use `--destination /alternate/skills` to test/install elsewhere. After moving the docs root, review/reinstall the affected copies explicitly; absolute installed links do not automatically follow a moved repository. New tasks can discover newly installed skills; an already-running task's skill catalog may need a new session.

## Maintenance and verification

`selection.json` is the explicit source manifest. Source checkouts are external read-only snapshots; documentation does not vendor or execute extension plugin code. `branches.json` records branch candidates, and each product's source-variants page records file differences. Preserve separate local/remote variants rather than overwriting local changes.

- `query.py drift` checks hashes plus added/deleted files in saved snapshots. It does not refresh remote branches or inspect a client installation.
- `refresh_refs.py` refreshes official catalog/branch metadata read-only and reports inventory/source drift; it does not automatically reclassify products, change a checkout or rewrite reviewed mappings.
- After reviewing source changes, update `selection.json`/`scripts/curation.py` and run `build_maps.py`. It tokenizes PHP without executing it, fingerprints files, generates evidence, concise topics and per-extension entry skills. `build_core.py` refreshes shared Core evidence; `build_coverage.py` refreshes per-product dependency and feature/infrastructure classifications.
- `validate.py` checks local/pinned-source links against inspected snapshots, manifests, structural coverage, skill frontmatter and topic sizes. `test_routing.py` tests observable selective-routing and source-lookup behavior. The official skill-creator validator should also be run for each skill.

[Inventory decisions and limits](INVENTORY.md) explain scope, exclusions and source selection. [Focused workflow](WORKFLOW.md) encodes full ticket intake, source verification, local reproduction, authorized read-only client comparison, narrow fixes and honest unresolved reports. [Validation report](VALIDATION.md) records the latest checks. These are source maps and recipes; they do not assert that all extensions passed runtime/browser compatibility or that future versions will remain compatible.
