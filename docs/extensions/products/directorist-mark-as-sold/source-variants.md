# Source variants

Canonical means the investigation starting point selected from actual branch/file evidence, not a released build. A newer commit date identifies a candidate; compare changed files before choosing an issue-specific owner.

| Snapshot | Branch / commit | File delta versus baseline | Evidence |
| --- | --- | --- | --- |
| mark-as-sold--main | main / 0e783d2cd5bc97d13222f01611a2164a8ec86d0b | 1 | [machine index](evidence/mark-as-sold--main.json) |

## mark-as-sold--main differences

These paths differ from the canonical baseline; direction/semantic impact requires reading the diff.
- [directorist-mark-as-sold.php:1](https://github.com/sovware/directorist-mark-as-sold/blob/0e783d2cd5bc97d13222f01611a2164a8ec86d0b/directorist-mark-as-sold.php#L1)
| mark-as-sold--details-update | details-update / ce63a5af0e80c619d48fe9ba490acbf05b66fab2 | 0 | [machine index](evidence/mark-as-sold--details-update.json) |
| mark-as-sold--development | development / 9dbd24b25d3e9b31ea2850d7cee184559e2d4839 | 1 | [machine index](evidence/mark-as-sold--development.json) |

## mark-as-sold--development differences

These paths differ from the canonical baseline; direction/semantic impact requires reading the diff.
- [directorist-mark-as-sold.php:1](https://github.com/sovware/directorist-mark-as-sold/blob/9dbd24b25d3e9b31ea2850d7cee184559e2d4839/directorist-mark-as-sold.php#L1)

## Visible remote branch tips

All returned refs are recorded in [branch inventory](../../branches.json). The selected snapshots cover latest tip, development where distinct, catalog-named default, payment-generation variants and relevant local files. Other branch tips remain searchable candidates; they are not silently folded into the baseline.

Local snapshots include file hashes and dirty-state metadata. Local generated assets are inventoried but not interpreted as source-equivalent builds. Re-run source drift checks before reusing a mapping.
