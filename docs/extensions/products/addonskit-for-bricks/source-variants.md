# Source variants

Canonical means the investigation starting point selected from actual branch/file evidence, not a released build. A newer commit date identifies a candidate; compare changed files before choosing an issue-specific owner.

| Snapshot | Branch / commit | File delta versus baseline | Evidence |
| --- | --- | --- | --- |
| addonskit-for-bricks--main | main / abedcb63f283ae13e899a33c0b2a2a1c7a922c51 | 0 | [machine index](evidence/addonskit-for-bricks--main.json) |
| addonskit-for-bricks--development | development / c3056318b531babf4673c18bf2114525814244fa | 3 | [machine index](evidence/addonskit-for-bricks--development.json) |

## addonskit-for-bricks--development differences

These paths differ from the canonical baseline; direction/semantic impact requires reading the diff.
- [addonskit-for-bricks.php:1](https://github.com/sovware/addonskit-for-bricks/blob/c3056318b531babf4673c18bf2114525814244fa/addonskit-for-bricks.php#L1)
- [src/DynamicTags/Provider.php:1](https://github.com/sovware/addonskit-for-bricks/blob/c3056318b531babf4673c18bf2114525814244fa/src/DynamicTags/Provider.php#L1)
- [src/Plugin.php:1](https://github.com/sovware/addonskit-for-bricks/blob/c3056318b531babf4673c18bf2114525814244fa/src/Plugin.php#L1)

## Visible remote branch tips

All returned refs are recorded in [branch inventory](../../branches.json). The selected snapshots cover latest tip, development where distinct, catalog-named default, payment-generation variants and relevant local files. Other branch tips remain searchable candidates; they are not silently folded into the baseline.

Local snapshots include file hashes and dirty-state metadata. Local generated assets are inventoried but not interpreted as source-equivalent builds. Re-run source drift checks before reusing a mapping.
