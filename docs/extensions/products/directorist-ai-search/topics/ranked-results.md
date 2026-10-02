# Directorist AI Search: ranked-results

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Semantic candidates are constrained by directory/filter context. Native fallback, score thresholds and Universal Search interception require independent checks.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Use two directories and restrictive category/price filters. Compare native, semantic success, empty response and API timeout; ensure excluded listings never leak through ranking.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [inc/Services/DirectoristQueryInterceptor.php:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/DirectoristQueryInterceptor.php#L1) — 14 declarations
- [inc/Services/FrontendAssetsService.php:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/FrontendAssetsService.php#L1) — 6 declarations
- [inc/Services/FrontendRestController.php:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/FrontendRestController.php#L1) — 9 declarations
- [inc/Services/FrontendResultsService.php:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/FrontendResultsService.php#L1) — 10 declarations
- [inc/Services/SearchContextService.php:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/SearchContextService.php#L1) — 29 declarations
- [inc/Services/SemanticCandidateService.php:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/SemanticCandidateService.php#L1) — 3 declarations
- [inc/Services/UniversalSearchInterceptor.php:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/UniversalSearchInterceptor.php#L1) — 3 declarations
- [assets/js/frontend-search.js:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/assets/js/frontend-search.js#L1) — 54 declarations

[Complete topic source/data ledger](ranked-results-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
- [directorist-universal-search](../../directorist-universal-search/README.md) — only if active configuration or source connects it.
