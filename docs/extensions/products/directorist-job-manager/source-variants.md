# Source variants

Canonical means the investigation starting point selected from actual branch/file evidence, not a released build. A newer commit date identifies a candidate; compare changed files before choosing an issue-specific owner.

| Snapshot | Branch / commit | File delta versus baseline | Evidence |
| --- | --- | --- | --- |
| job-manager--main | main / 3c106a5bc659890d81844f9f907011fcd9c77b20 | 3 | [machine index](evidence/job-manager--main.json) |

## job-manager--main differences

These paths differ from the canonical baseline; direction/semantic impact requires reading the diff.
- [directorist-job-manager.php:1](https://github.com/sovware/directorist-job-manager/blob/3c106a5bc659890d81844f9f907011fcd9c77b20/directorist-job-manager.php#L1)
- [templates/search-form/fields/dirjob_job_type.php:1](https://github.com/sovware/directorist-job-manager/blob/3c106a5bc659890d81844f9f907011fcd9c77b20/templates/search-form/fields/dirjob_job_type.php#L1)
- [templates/single/section-dirjob_job_details.php:1](https://github.com/sovware/directorist-job-manager/blob/3c106a5bc659890d81844f9f907011fcd9c77b20/templates/single/section-dirjob_job_details.php#L1)
| job-manager--development | development / 562615e4ede94f2c41c79c02e15035f7b9e64a41 | 0 | [machine index](evidence/job-manager--development.json) |

## Visible remote branch tips

All returned refs are recorded in [branch inventory](../../branches.json). The selected snapshots cover latest tip, development where distinct, catalog-named default, payment-generation variants and relevant local files. Other branch tips remain searchable candidates; they are not silently folded into the baseline.

Local snapshots include file hashes and dirty-state metadata. Local generated assets are inventoried but not interpreted as source-equivalent builds. Re-run source drift checks before reusing a mapping.
