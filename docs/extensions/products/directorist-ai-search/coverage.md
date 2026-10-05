# Feature and infrastructure coverage

Canonical snapshot: `ai-search--main`.

Feature-linked means declarations/file boundaries are connected to an authored user workflow/topic. It does not prove every conditional path was manually audited. Infrastructure is explicitly accounted for rather than presented as another supported feature. Support/generated/vendor-library files are not runtime proof.

Counts: feature-linked=22, infrastructure=3, support-or-generated=6.

Unresolved feature candidates: 0.

| Source | Classification | Topic or reason |
| --- | --- | --- |
| [.gitignore:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/.gitignore#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [CHANGELOG.md:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/CHANGELOG.md#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [README.md:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/README.md#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [directorist-ai-search.php:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/directorist-ai-search.php#L1) | infrastructure | Plugin bootstrap/header or shared root configuration; inspect include/registration chain. |
| [package.json:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/package.json#L1) | infrastructure | Declarative configuration or shared presentation asset; no independent runtime feature inferred from file existence. |
| [assets/css/frontend-search.css:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/assets/css/frontend-search.css#L1) | feature-linked | [ranked-results](topics/ranked-results.md) |
| [assets/js/frontend-search.js:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/assets/js/frontend-search.js#L1) | feature-linked | [ranked-results](topics/ranked-results.md) |
| [config/.gitkeep:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/config/.gitkeep#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [inc/Admin/AdminMenu.php:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Admin/AdminMenu.php#L1) | feature-linked | [indexing](topics/indexing.md) |
| [inc/Admin/AdminRestController.php:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Admin/AdminRestController.php#L1) | feature-linked | [indexing](topics/indexing.md) |
| [inc/Core/Activator.php:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Core/Activator.php#L1) | feature-linked | [indexing](topics/indexing.md) |
| [inc/Core/Deactivator.php:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Core/Deactivator.php#L1) | feature-linked | [indexing](topics/indexing.md) |
| [inc/Core/Plugin.php:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Core/Plugin.php#L1) | feature-linked | [indexing](topics/indexing.md) |
| [inc/Services/ApiClient.php:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/ApiClient.php#L1) | feature-linked | [indexing](topics/indexing.md) |
| [inc/Services/ApiKeyProvisioningService.php:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/ApiKeyProvisioningService.php#L1) | feature-linked | [indexing](topics/indexing.md) |
| [inc/Services/AutomaticIndexingService.php:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/AutomaticIndexingService.php#L1) | feature-linked | [indexing](topics/indexing.md) |
| [inc/Services/DirectoristAccountService.php:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/DirectoristAccountService.php#L1) | feature-linked | [indexing](topics/indexing.md) |
| [inc/Services/DirectoristDependencyService.php:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/DirectoristDependencyService.php#L1) | feature-linked | [indexing](topics/indexing.md) |
| [inc/Services/DirectoristQueryInterceptor.php:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/DirectoristQueryInterceptor.php#L1) | feature-linked | [ranked-results](topics/ranked-results.md) |
| [inc/Services/FrontendAssetsService.php:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/FrontendAssetsService.php#L1) | feature-linked | [ranked-results](topics/ranked-results.md) |
| [inc/Services/FrontendRestController.php:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/FrontendRestController.php#L1) | feature-linked | [indexing](topics/indexing.md), [ranked-results](topics/ranked-results.md) |
| [inc/Services/FrontendResultsService.php:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/FrontendResultsService.php#L1) | feature-linked | [ranked-results](topics/ranked-results.md) |
| [inc/Services/ListingPayloadService.php:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/ListingPayloadService.php#L1) | feature-linked | [indexing](topics/indexing.md) |
| [inc/Services/ListingService.php:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/ListingService.php#L1) | feature-linked | [indexing](topics/indexing.md) |
| [inc/Services/SearchContextService.php:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/SearchContextService.php#L1) | feature-linked | [indexing](topics/indexing.md), [ranked-results](topics/ranked-results.md) |
| [inc/Services/SemanticCandidateService.php:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/SemanticCandidateService.php#L1) | feature-linked | [ranked-results](topics/ranked-results.md) |
| [inc/Services/SettingsService.php:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/SettingsService.php#L1) | feature-linked | [indexing](topics/indexing.md) |
| [inc/Services/UniversalSearchInterceptor.php:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/UniversalSearchInterceptor.php#L1) | feature-linked | [ranked-results](topics/ranked-results.md) |
| [inc/Traits/Singleton.php:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Traits/Singleton.php#L1) | infrastructure | Data shapes, shared authorization/framework or schema bootstrap; inspect when a routed feature reaches this layer. |
| [languages/directorist-ai-search.pot:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/languages/directorist-ai-search.pot#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [scripts/build-release.js:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/scripts/build-release.js#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
