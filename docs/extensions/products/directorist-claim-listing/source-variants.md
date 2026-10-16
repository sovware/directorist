# Source variants

Canonical means the investigation starting point selected from actual branch/file evidence, not a released build. A newer commit date identifies a candidate; compare changed files before choosing an issue-specific owner.

| Snapshot | Branch / commit | File delta versus baseline | Evidence |
| --- | --- | --- | --- |
| claim-listing--main | main / f5e2920c3127bdda2954990f37eb6f1f1c849e16 | 12 | [machine index](evidence/claim-listing--main.json) |

## claim-listing--main differences

These paths differ from the canonical baseline; direction/semantic impact requires reading the diff.
- `.cache/phpcompat.json` (absent in this snapshot)
- [assets/js/main.js:1](https://github.com/sovware/directorist-claim-listing/blob/f5e2920c3127bdda2954990f37eb6f1f1c849e16/assets/js/main.js#L1)
- [config.php:1](https://github.com/sovware/directorist-claim-listing/blob/f5e2920c3127bdda2954990f37eb6f1f1c849e16/config.php#L1)
- [directorist-claim-listing.php:1](https://github.com/sovware/directorist-claim-listing/blob/f5e2920c3127bdda2954990f37eb6f1f1c849e16/directorist-claim-listing.php#L1)
- [inc/class-claim-now.php:1](https://github.com/sovware/directorist-claim-listing/blob/f5e2920c3127bdda2954990f37eb6f1f1c849e16/inc/class-claim-now.php#L1)
- [inc/class-db.php:1](https://github.com/sovware/directorist-claim-listing/blob/f5e2920c3127bdda2954990f37eb6f1f1c849e16/inc/class-db.php#L1)
- [inc/directory_type.php:1](https://github.com/sovware/directorist-claim-listing/blob/f5e2920c3127bdda2954990f37eb6f1f1c849e16/inc/directory_type.php#L1)
- [inc/helper-functions.php:1](https://github.com/sovware/directorist-claim-listing/blob/f5e2920c3127bdda2954990f37eb6f1f1c849e16/inc/helper-functions.php#L1)
- [templates/claim-listing-template.php:1](https://github.com/sovware/directorist-claim-listing/blob/f5e2920c3127bdda2954990f37eb6f1f1c849e16/templates/claim-listing-template.php#L1)
- `templates/partials/plan-selector.php` (absent in this snapshot)
| claim-listing--development | development / 5c797d03501cfe5366590b8d262c9d82460d86df | 4 | [machine index](evidence/claim-listing--development.json) |

## claim-listing--development differences

These paths differ from the canonical baseline; direction/semantic impact requires reading the diff.
- `.cache/phpcompat.json` (absent in this snapshot)
- [directorist-claim-listing.php:1](https://github.com/sovware/directorist-claim-listing/blob/5c797d03501cfe5366590b8d262c9d82460d86df/directorist-claim-listing.php#L1)
- [inc/directory_type.php:1](https://github.com/sovware/directorist-claim-listing/blob/5c797d03501cfe5366590b8d262c9d82460d86df/inc/directory_type.php#L1)
| claim-listing--local | fix/claimed-badge-tooltip / c0f000fef79d885c69881a38947e22fecd989eba | 0 | [machine index](evidence/claim-listing--local.json) |

## Visible remote branch tips

All returned refs are recorded in [branch inventory](../../branches.json). The selected snapshots cover latest tip, development where distinct, catalog-named default, payment-generation variants and relevant local files. Other branch tips remain searchable candidates; they are not silently folded into the baseline.

Local snapshots include file hashes and dirty-state metadata. Local generated assets are inventoried but not interpreted as source-equivalent builds. Re-run source drift checks before reusing a mapping.
