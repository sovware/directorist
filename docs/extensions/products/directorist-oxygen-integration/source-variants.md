# Source variants

Canonical means the investigation starting point selected from actual branch/file evidence, not a released build. A newer commit date identifies a candidate; compare changed files before choosing an issue-specific owner.

| Snapshot | Branch / commit | File delta versus baseline | Evidence |
| --- | --- | --- | --- |
| oxygen-integration--main | main / 4dc1f5b906b0bdb7c8175c917a58c5b457ddea39 | 10 | [machine index](evidence/oxygen-integration--main.json) |

## oxygen-integration--main differences

These paths differ from the canonical baseline; direction/semantic impact requires reading the diff.
- [directorist-oxygen-integration.php:1](https://github.com/sovware/directorist-oxygen-integration/blob/4dc1f5b906b0bdb7c8175c917a58c5b457ddea39/directorist-oxygen-integration.php#L1)
- `oxygen-6/elements/directorist-elements/element.php` (absent in this snapshot)
- `oxygen-6/elements/directorist-elements/support/bootstrap.php` (absent in this snapshot)
- `oxygen-6/elements/directorist-elements/support/element-fields.php` (absent in this snapshot)
- `oxygen-6/elements/directorist-elements/support/element-shortcode-runtime.php` (absent in this snapshot)
- `oxygen-6/elements/directorist-elements/support/element-ui.php` (absent in this snapshot)
| oxygen-integration--improvement-oxygen-6-integration | improvement/oxygen-6-integration / 6830012382294315a8c6dad1dedf5789540f1e99 | 0 | [machine index](evidence/oxygen-integration--improvement-oxygen-6-integration.json) |

## Visible remote branch tips

All returned refs are recorded in [branch inventory](../../branches.json). The selected snapshots cover latest tip, development where distinct, catalog-named default, payment-generation variants and relevant local files. Other branch tips remain searchable candidates; they are not silently folded into the baseline.

Local snapshots include file hashes and dirty-state metadata. Local generated assets are inventoried but not interpreted as source-equivalent builds. Re-run source drift checks before reusing a mapping.
