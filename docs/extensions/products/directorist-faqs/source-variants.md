# Source variants

Canonical means the investigation starting point selected from actual branch/file evidence, not a released build. A newer commit date identifies a candidate; compare changed files before choosing an issue-specific owner.

| Snapshot | Branch / commit | File delta versus baseline | Evidence |
| --- | --- | --- | --- |
| faqs--main | main / b83f4f571f845c19024f099fdc40438276c30563 | 2 | [machine index](evidence/faqs--main.json) |

## faqs--main differences

These paths differ from the canonical baseline; direction/semantic impact requires reading the diff.
- [directorist-faqs.php:1](https://github.com/sovware/directorist-faqs/blob/b83f4f571f845c19024f099fdc40438276c30563/directorist-faqs.php#L1)
- [widgets/class-widget.php:1](https://github.com/sovware/directorist-faqs/blob/b83f4f571f845c19024f099fdc40438276c30563/widgets/class-widget.php#L1)
| faqs--development | development / cdcc5031f77ca1333fa39d1ee45dca4ca9937c70 | 0 | [machine index](evidence/faqs--development.json) |

## Visible remote branch tips

All returned refs are recorded in [branch inventory](../../branches.json). The selected snapshots cover latest tip, development where distinct, catalog-named default, payment-generation variants and relevant local files. Other branch tips remain searchable candidates; they are not silently folded into the baseline.

Local snapshots include file hashes and dirty-state metadata. Local generated assets are inventoried but not interpreted as source-equivalent builds. Re-run source drift checks before reusing a mapping.
