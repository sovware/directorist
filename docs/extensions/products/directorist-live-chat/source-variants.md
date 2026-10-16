# Source variants

Canonical means the investigation starting point selected from actual branch/file evidence, not a released build. A newer commit date identifies a candidate; compare changed files before choosing an issue-specific owner.

| Snapshot | Branch / commit | File delta versus baseline | Evidence |
| --- | --- | --- | --- |
| live-chat--main | main / 821d8e2b813dca1f26b82fd9f9198b296a63a980 | 5 | [machine index](evidence/live-chat--main.json) |

## live-chat--main differences

These paths differ from the canonical baseline; direction/semantic impact requires reading the diff.
- [assets/public/main.js:1](https://github.com/sovware/directorist-live-chat/blob/821d8e2b813dca1f26b82fd9f9198b296a63a980/assets/public/main.js#L1)
- [directorist-live-chat.php:1](https://github.com/sovware/directorist-live-chat/blob/821d8e2b813dca1f26b82fd9f9198b296a63a980/directorist-live-chat.php#L1)
- [includes/directory_type.php:1](https://github.com/sovware/directorist-live-chat/blob/821d8e2b813dca1f26b82fd9f9198b296a63a980/includes/directory_type.php#L1)
- [includes/helper.php:1](https://github.com/sovware/directorist-live-chat/blob/821d8e2b813dca1f26b82fd9f9198b296a63a980/includes/helper.php#L1)
- [templates/live_chat.php:1](https://github.com/sovware/directorist-live-chat/blob/821d8e2b813dca1f26b82fd9f9198b296a63a980/templates/live_chat.php#L1)
| live-chat--fix-live-chat-visibility-socket-fallback | fix/live-chat-visibility-socket-fallback / 3a48f59612eb61ad2fc6308063870e59c02fdb34 | 0 | [machine index](evidence/live-chat--fix-live-chat-visibility-socket-fallback.json) |
| live-chat--development | development / 058296888c848f3cb1ddb4d8bf85713572c7021b | 3 | [machine index](evidence/live-chat--development.json) |

## live-chat--development differences

These paths differ from the canonical baseline; direction/semantic impact requires reading the diff.
- [assets/public/main.js:1](https://github.com/sovware/directorist-live-chat/blob/058296888c848f3cb1ddb4d8bf85713572c7021b/assets/public/main.js#L1)
- [directorist-live-chat.php:1](https://github.com/sovware/directorist-live-chat/blob/058296888c848f3cb1ddb4d8bf85713572c7021b/directorist-live-chat.php#L1)
- [templates/live_chat.php:1](https://github.com/sovware/directorist-live-chat/blob/058296888c848f3cb1ddb4d8bf85713572c7021b/templates/live_chat.php#L1)

## Visible remote branch tips

All returned refs are recorded in [branch inventory](../../branches.json). The selected snapshots cover latest tip, development where distinct, catalog-named default, payment-generation variants and relevant local files. Other branch tips remain searchable candidates; they are not silently folded into the baseline.

Local snapshots include file hashes and dirty-state metadata. Local generated assets are inventoried but not interpreted as source-equivalent builds. Re-run source drift checks before reusing a mapping.
