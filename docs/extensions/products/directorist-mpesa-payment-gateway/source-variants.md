# Source variants

Canonical means the investigation starting point selected from actual branch/file evidence, not a released build. A newer commit date identifies a candidate; compare changed files before choosing an issue-specific owner.

| Snapshot | Branch / commit | File delta versus baseline | Evidence |
| --- | --- | --- | --- |
| mpesa-payment-gateway--main | main / f304ecfa35049b5cc827b182f54dcbc048dc5bf3 | 0 | [machine index](evidence/mpesa-payment-gateway--main.json) |
| mpesa-payment-gateway--development | development / a9ad328642699baba4c1f854b35544cf571a2587 | 0 | [machine index](evidence/mpesa-payment-gateway--development.json) |

## Visible remote branch tips

All returned refs are recorded in [branch inventory](../../branches.json). The selected snapshots cover latest tip, development where distinct, catalog-named default, payment-generation variants and relevant local files. Other branch tips remain searchable candidates; they are not silently folded into the baseline.

Local snapshots include file hashes and dirty-state metadata. Local generated assets are inventoried but not interpreted as source-equivalent builds. Re-run source drift checks before reusing a mapping.
