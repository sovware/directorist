# Source variants

Canonical means the investigation starting point selected from actual branch/file evidence, not a released build. A newer commit date identifies a candidate; compare changed files before choosing an issue-specific owner.

| Snapshot | Branch / commit | File delta versus baseline | Evidence |
| --- | --- | --- | --- |
| digital-marketplace--main | main / 4d6e283fd759a804d07b6510bbe1d055a80d4b79 | 9 | [machine index](evidence/digital-marketplace--main.json) |

## digital-marketplace--main differences

These paths differ from the canonical baseline; direction/semantic impact requires reading the diff.
- [app/Module/Core/Asset/AssetEnqueuer.php:1](https://github.com/sovware/directorist-digital-marketplace/blob/4d6e283fd759a804d07b6510bbe1d055a80d4b79/app/Module/Core/Asset/AssetEnqueuer.php#L1)
| digital-marketplace--fix-safari-source-map-errors | fix/safari-source-map-errors / dc80ed24ba02b23f9f8157084bc02a5dea7502c7 | 0 | [machine index](evidence/digital-marketplace--fix-safari-source-map-errors.json) |
| digital-marketplace--local |  / 4d6e283fd759a804d07b6510bbe1d055a80d4b79 | 16 | [machine index](evidence/digital-marketplace--local.json) |

## digital-marketplace--local differences

These paths differ from the canonical baseline; direction/semantic impact requires reading the diff.
- [app/Module/Core/Asset/AssetEnqueuer.php:1](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-digital-marketplace/app/Module/Core/Asset/AssetEnqueuer.php:1>)
- [package.json:1](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-digital-marketplace/package.json:1>)

## Visible remote branch tips

All returned refs are recorded in [branch inventory](../../branches.json). The selected snapshots cover latest tip, development where distinct, catalog-named default, payment-generation variants and relevant local files. Other branch tips remain searchable candidates; they are not silently folded into the baseline.

Local snapshots include file hashes and dirty-state metadata. Local generated assets are inventoried but not interpreted as source-equivalent builds. Re-run source drift checks before reusing a mapping.
