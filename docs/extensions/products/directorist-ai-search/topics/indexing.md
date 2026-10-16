# Directorist AI Search: indexing

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Listing publication/meta/taxonomy changes schedule index/delete operations. Site identity, remote API provisioning and supported environment checks can prevent indexing before query interception runs.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Stub or use an authorized test API; publish/edit/unpublish a listing, change taxonomy and compare queued payload, index status, failure feedback and deletion. Do not send client data to a new service by default.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [inc/Services/ApiClient.php:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/ApiClient.php#L1) — 7 declarations
- [inc/Services/ApiKeyProvisioningService.php:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/ApiKeyProvisioningService.php#L1) — 3 declarations
- [inc/Services/AutomaticIndexingService.php:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/AutomaticIndexingService.php#L1) — 18 declarations
- [inc/Services/DirectoristAccountService.php:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/DirectoristAccountService.php#L1) — 4 declarations
- [inc/Services/DirectoristDependencyService.php:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/DirectoristDependencyService.php#L1) — 4 declarations
- [inc/Services/FrontendRestController.php:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/FrontendRestController.php#L1) — 9 declarations
- [inc/Services/ListingPayloadService.php:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/ListingPayloadService.php#L1) — 10 declarations
- [inc/Services/ListingService.php:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/ListingService.php#L1) — 27 declarations

[Complete topic source/data ledger](indexing-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
- [directorist-universal-search](../../directorist-universal-search/README.md) — only if active configuration or source connects it.
