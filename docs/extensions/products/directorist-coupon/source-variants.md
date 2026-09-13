# Source variants

Canonical means the investigation starting point selected from actual branch/file evidence, not a released build. A newer commit date identifies a candidate; compare changed files before choosing an issue-specific owner.

| Snapshot | Branch / commit | File delta versus baseline | Evidence |
| --- | --- | --- | --- |
| coupon--main | main / c98c9236247e4f3075732cc345a729cade8f0103 | 10 | [machine index](evidence/coupon--main.json) |

## coupon--main differences

These paths differ from the canonical baseline; direction/semantic impact requires reading the diff.
- [Inc/Controller/Admin/SWBDPCouponCPT.php:1](https://github.com/sovware/directorist-coupon/blob/c98c9236247e4f3075732cc345a729cade8f0103/Inc/Controller/Admin/SWBDPCouponCPT.php#L1)
- [Inc/Controller/Base/AjaxHandler.php:1](https://github.com/sovware/directorist-coupon/blob/c98c9236247e4f3075732cc345a729cade8f0103/Inc/Controller/Base/AjaxHandler.php#L1)
- [Inc/Controller/Base/CouponHandler.php:1](https://github.com/sovware/directorist-coupon/blob/c98c9236247e4f3075732cc345a729cade8f0103/Inc/Controller/Base/CouponHandler.php#L1)
- [Inc/Controller/Base/Enqueue.php:1](https://github.com/sovware/directorist-coupon/blob/c98c9236247e4f3075732cc345a729cade8f0103/Inc/Controller/Base/Enqueue.php#L1)
- [Inc/Controller/Base/HelperFunctions.php:1](https://github.com/sovware/directorist-coupon/blob/c98c9236247e4f3075732cc345a729cade8f0103/Inc/Controller/Base/HelperFunctions.php#L1)
- [Inc/View/AdminCallbacks.php:1](https://github.com/sovware/directorist-coupon/blob/c98c9236247e4f3075732cc345a729cade8f0103/Inc/View/AdminCallbacks.php#L1)
- [assets/frontend/js/swbdpc-frontend-ajax.js:1](https://github.com/sovware/directorist-coupon/blob/c98c9236247e4f3075732cc345a729cade8f0103/assets/frontend/js/swbdpc-frontend-ajax.js#L1)
- [directorist-coupon.php:1](https://github.com/sovware/directorist-coupon/blob/c98c9236247e4f3075732cc345a729cade8f0103/directorist-coupon.php#L1)
| coupon--development | development / d98770d6b7dd98122f1be8fd0cd2038c25abe687 | 0 | [machine index](evidence/coupon--development.json) |

## Visible remote branch tips

All returned refs are recorded in [branch inventory](../../branches.json). The selected snapshots cover latest tip, development where distinct, catalog-named default, payment-generation variants and relevant local files. Other branch tips remain searchable candidates; they are not silently folded into the baseline.

Local snapshots include file hashes and dirty-state metadata. Local generated assets are inventoried but not interpreted as source-equivalent builds. Re-run source drift checks before reusing a mapping.
