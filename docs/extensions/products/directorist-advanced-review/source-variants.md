# Source variants

Canonical means the investigation starting point selected from actual branch/file evidence, not a released build. A newer commit date identifies a candidate; compare changed files before choosing an issue-specific owner.

| Snapshot | Branch / commit | File delta versus baseline | Evidence |
| --- | --- | --- | --- |
| advanced-review--master | master / d21cfd0ea7d27149241495c578b7683cbd9823a9 | 3 | [machine index](evidence/advanced-review--master.json) |

## advanced-review--master differences

These paths differ from the canonical baseline; direction/semantic impact requires reading the diff.
- [resources/js/frontend.js:1](https://github.com/sovware/directorist-advanced-review/blob/d21cfd0ea7d27149241495c578b7683cbd9823a9/resources/js/frontend.js#L1)
- [resources/views/filter.php:1](https://github.com/sovware/directorist-advanced-review/blob/d21cfd0ea7d27149241495c578b7683cbd9823a9/resources/views/filter.php#L1)
- [resources/views/review.php:1](https://github.com/sovware/directorist-advanced-review/blob/d21cfd0ea7d27149241495c578b7683cbd9823a9/resources/views/review.php#L1)
| advanced-review--fix-review-translation-pluralization | fix/review-translation-pluralization / 38089538e124bb99123f0db34860992247bb6506 | 0 | [machine index](evidence/advanced-review--fix-review-translation-pluralization.json) |
| advanced-review--development | development / e3559325447ddb3ede6b916ffbf9defd658cbbf5 | 3 | [machine index](evidence/advanced-review--development.json) |

## advanced-review--development differences

These paths differ from the canonical baseline; direction/semantic impact requires reading the diff.
- [resources/js/frontend.js:1](https://github.com/sovware/directorist-advanced-review/blob/e3559325447ddb3ede6b916ffbf9defd658cbbf5/resources/js/frontend.js#L1)
- [resources/views/filter.php:1](https://github.com/sovware/directorist-advanced-review/blob/e3559325447ddb3ede6b916ffbf9defd658cbbf5/resources/views/filter.php#L1)
- [resources/views/review.php:1](https://github.com/sovware/directorist-advanced-review/blob/e3559325447ddb3ede6b916ffbf9defd658cbbf5/resources/views/review.php#L1)

## Visible remote branch tips

All returned refs are recorded in [branch inventory](../../branches.json). The selected snapshots cover latest tip, development where distinct, catalog-named default, payment-generation variants and relevant local files. Other branch tips remain searchable candidates; they are not silently folded into the baseline.

Local snapshots include file hashes and dirty-state metadata. Local generated assets are inventoried but not interpreted as source-equivalent builds. Re-run source drift checks before reusing a mapping.
