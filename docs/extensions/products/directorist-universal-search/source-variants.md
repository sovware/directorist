# Source variants

Canonical means the investigation starting point selected from actual branch/file evidence, not a released build. A newer commit date identifies a candidate; compare changed files before choosing an issue-specific owner.

| Snapshot | Branch / commit | File delta versus baseline | Evidence |
| --- | --- | --- | --- |
| universal-search--master | master / 9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a | 0 | [machine index](evidence/universal-search--master.json) |
| universal-search--development | development / 3abafc6bd041a96e14cadeb17d0596d90497545c | 17 | [machine index](evidence/universal-search--development.json) |

## universal-search--development differences

These paths differ from the canonical baseline; direction/semantic impact requires reading the diff.
- [app/Repositories/ListingsRepository.php:1](https://github.com/sovware/directorist-universal-search/blob/3abafc6bd041a96e14cadeb17d0596d90497545c/app/Repositories/ListingsRepository.php#L1)
- [app/Repositories/TaxonomiesRepository.php:1](https://github.com/sovware/directorist-universal-search/blob/3abafc6bd041a96e14cadeb17d0596d90497545c/app/Repositories/TaxonomiesRepository.php#L1)
- [composer.json:1](https://github.com/sovware/directorist-universal-search/blob/3abafc6bd041a96e14cadeb17d0596d90497545c/composer.json#L1)
- [directorist-universal-search.php:1](https://github.com/sovware/directorist-universal-search/blob/3abafc6bd041a96e14cadeb17d0596d90497545c/directorist-universal-search.php#L1)
- [package-lock.json:1](https://github.com/sovware/directorist-universal-search/blob/3abafc6bd041a96e14cadeb17d0596d90497545c/package-lock.json#L1)
- [package.json:1](https://github.com/sovware/directorist-universal-search/blob/3abafc6bd041a96e14cadeb17d0596d90497545c/package.json#L1)
- [resources/sass/3.plugins/_search.scss:1](https://github.com/sovware/directorist-universal-search/blob/3abafc6bd041a96e14cadeb17d0596d90497545c/resources/sass/3.plugins/_search.scss#L1)

## Visible remote branch tips

All returned refs are recorded in [branch inventory](../../branches.json). The selected snapshots cover latest tip, development where distinct, catalog-named default, payment-generation variants and relevant local files. Other branch tips remain searchable candidates; they are not silently folded into the baseline.

Local snapshots include file hashes and dirty-state metadata. Local generated assets are inventoried but not interpreted as source-equivalent builds. Re-run source drift checks before reusing a mapping.
