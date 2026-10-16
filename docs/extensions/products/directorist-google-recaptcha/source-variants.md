# Source variants

Canonical means the investigation starting point selected from actual branch/file evidence, not a released build. A newer commit date identifies a candidate; compare changed files before choosing an issue-specific owner.

| Snapshot | Branch / commit | File delta versus baseline | Evidence |
| --- | --- | --- | --- |
| google-recaptcha--beta | beta / 2fcd40af48879a19f1d30aff0ed5ee551709c083 | 2 | [machine index](evidence/google-recaptcha--beta.json) |

## google-recaptcha--beta differences

These paths differ from the canonical baseline; direction/semantic impact requires reading the diff.
- [directorist-google-recaptcha.php:1](https://github.com/sovware/directorist-google-recaptcha/blob/2fcd40af48879a19f1d30aff0ed5ee551709c083/directorist-google-recaptcha.php#L1)
- [recaptchalib.php:1](https://github.com/sovware/directorist-google-recaptcha/blob/2fcd40af48879a19f1d30aff0ed5ee551709c083/recaptchalib.php#L1)
| google-recaptcha--v3-support | v3-support / ae5173102e9d6f4a69d52159d610bf45856de762 | 0 | [machine index](evidence/google-recaptcha--v3-support.json) |

## Visible remote branch tips

All returned refs are recorded in [branch inventory](../../branches.json). The selected snapshots cover latest tip, development where distinct, catalog-named default, payment-generation variants and relevant local files. Other branch tips remain searchable candidates; they are not silently folded into the baseline.

Local snapshots include file hashes and dirty-state metadata. Local generated assets are inventoried but not interpreted as source-equivalent builds. Re-run source drift checks before reusing a mapping.
