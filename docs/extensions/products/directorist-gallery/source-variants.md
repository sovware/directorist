# Source variants

Canonical means the investigation starting point selected from actual branch/file evidence, not a released build. A newer commit date identifies a candidate; compare changed files before choosing an issue-specific owner.

| Snapshot | Branch / commit | File delta versus baseline | Evidence |
| --- | --- | --- | --- |
| gallery--alpha | alpha / a4bd383934f2fbedeaab6e00205282bcddbf0ff1 | 2 | [machine index](evidence/gallery--alpha.json) |

## gallery--alpha differences

These paths differ from the canonical baseline; direction/semantic impact requires reading the diff.
- [bd-directorist-gallery.php:1](https://github.com/sovware/directorist-gallery/blob/a4bd383934f2fbedeaab6e00205282bcddbf0ff1/bd-directorist-gallery.php#L1)
- [inc/importer.php:1](https://github.com/sovware/directorist-gallery/blob/a4bd383934f2fbedeaab6e00205282bcddbf0ff1/inc/importer.php#L1)
| gallery--fix-gallery-import-cron-scheduling | fix/gallery-import-cron-scheduling / 34048acde9eba93c63d2eb7b66f5d5b1ce21ba3b | 0 | [machine index](evidence/gallery--fix-gallery-import-cron-scheduling.json) |
| gallery--local | fix/gallery-import-cron-scheduling / 34048acde9eba93c63d2eb7b66f5d5b1ce21ba3b | 1 | [machine index](evidence/gallery--local.json) |

## gallery--local differences

These paths differ from the canonical baseline; direction/semantic impact requires reading the diff.

## Visible remote branch tips

All returned refs are recorded in [branch inventory](../../branches.json). The selected snapshots cover latest tip, development where distinct, catalog-named default, payment-generation variants and relevant local files. Other branch tips remain searchable candidates; they are not silently folded into the baseline.

Local snapshots include file hashes and dirty-state metadata. Local generated assets are inventoried but not interpreted as source-equivalent builds. Re-run source drift checks before reusing a mapping.
