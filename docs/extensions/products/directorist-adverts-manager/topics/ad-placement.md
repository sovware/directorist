# Directorist Ads Manager: ad-placement

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Page-specific placement hooks, in-loop positions and ad type selection control rendering. A configured slot requires the actual template to invoke its hook.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Create image/text ads; test all-listings and search-result loop positions with pagination, empty results and mobile. Compare selected page slots with rendered occurrences.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [Inc/Controller/AdDisplayPages/PageAddListing.php:1](https://github.com/sovware/directorist-adverts-manager/blob/b74a1c0372ec005708ea2bad386b30c3e439d402/Inc/Controller/AdDisplayPages/PageAddListing.php#L1) — 7 declarations
- [Inc/Controller/AdDisplayPages/PageAllCategories.php:1](https://github.com/sovware/directorist-adverts-manager/blob/b74a1c0372ec005708ea2bad386b30c3e439d402/Inc/Controller/AdDisplayPages/PageAllCategories.php#L1) — 7 declarations
- [Inc/Controller/AdDisplayPages/PageAllListings.php:1](https://github.com/sovware/directorist-adverts-manager/blob/b74a1c0372ec005708ea2bad386b30c3e439d402/Inc/Controller/AdDisplayPages/PageAllListings.php#L1) — 11 declarations
- [Inc/Controller/AdDisplayPages/PageAllLocations.php:1](https://github.com/sovware/directorist-adverts-manager/blob/b74a1c0372ec005708ea2bad386b30c3e439d402/Inc/Controller/AdDisplayPages/PageAllLocations.php#L1) — 7 declarations
- [Inc/Controller/AdDisplayPages/PageAuthorListing.php:1](https://github.com/sovware/directorist-adverts-manager/blob/b74a1c0372ec005708ea2bad386b30c3e439d402/Inc/Controller/AdDisplayPages/PageAuthorListing.php#L1) — 13 declarations
- [Inc/Controller/AdDisplayPages/PageDashboard.php:1](https://github.com/sovware/directorist-adverts-manager/blob/b74a1c0372ec005708ea2bad386b30c3e439d402/Inc/Controller/AdDisplayPages/PageDashboard.php#L1) — 11 declarations
- [Inc/Controller/AdDisplayPages/PageSearchHome.php:1](https://github.com/sovware/directorist-adverts-manager/blob/b74a1c0372ec005708ea2bad386b30c3e439d402/Inc/Controller/AdDisplayPages/PageSearchHome.php#L1) — 7 declarations
- [Inc/Controller/AdDisplayPages/PageSearchResult.php:1](https://github.com/sovware/directorist-adverts-manager/blob/b74a1c0372ec005708ea2bad386b30c3e439d402/Inc/Controller/AdDisplayPages/PageSearchResult.php#L1) — 11 declarations

[Complete topic source/data ledger](ad-placement-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
