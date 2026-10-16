# Source variants

Canonical means the investigation starting point selected from actual branch/file evidence, not a released build. A newer commit date identifies a candidate; compare changed files before choosing an issue-specific owner.

| Snapshot | Branch / commit | File delta versus baseline | Evidence |
| --- | --- | --- | --- |
| business-hours--main | main / c909d05184e786fd51cd0727553f3d389f64986b | 6 | [machine index](evidence/business-hours--main.json) |

## business-hours--main differences

These paths differ from the canonical baseline; direction/semantic impact requires reading the diff.
- [bd-business-hour.php:1](https://github.com/sovware/directorist-business-hours/blob/c909d05184e786fd51cd0727553f3d389f64986b/bd-business-hour.php#L1)
- [inc/class-rest-response.php:1](https://github.com/sovware/directorist-business-hours/blob/c909d05184e786fd51cd0727553f3d389f64986b/inc/class-rest-response.php#L1)
- `inc/csv_manager.php` (absent in this snapshot)
- [inc/directory_types_manager.php:1](https://github.com/sovware/directorist-business-hours/blob/c909d05184e786fd51cd0727553f3d389f64986b/inc/directory_types_manager.php#L1)
- [inc/helper-functions.php:1](https://github.com/sovware/directorist-business-hours/blob/c909d05184e786fd51cd0727553f3d389f64986b/inc/helper-functions.php#L1)
- [templates/business-hour-fields.php:1](https://github.com/sovware/directorist-business-hours/blob/c909d05184e786fd51cd0727553f3d389f64986b/templates/business-hour-fields.php#L1)
| business-hours--development | development / f4ce722e68bff3f85b8b845042c904c7bfd52ef4 | 0 | [machine index](evidence/business-hours--development.json) |
| business-hours--local | development / 5a42533eb7a29cdab35df86b42ad4749d5c408bd | 4 | [machine index](evidence/business-hours--local.json) |

## business-hours--local differences

These paths differ from the canonical baseline; direction/semantic impact requires reading the diff.
- [bd-business-hour.php:1](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-business-hours/bd-business-hour.php:1>)
- `inc/class-rest-response.php` (absent in this snapshot)

## Visible remote branch tips

All returned refs are recorded in [branch inventory](../../branches.json). The selected snapshots cover latest tip, development where distinct, catalog-named default, payment-generation variants and relevant local files. Other branch tips remain searchable candidates; they are not silently folded into the baseline.

Local snapshots include file hashes and dirty-state metadata. Local generated assets are inventoried but not interpreted as source-equivalent builds. Re-run source drift checks before reusing a mapping.
