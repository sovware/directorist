# Feature and infrastructure coverage

Canonical snapshot: `addonskit-for-bricks--main`.

Feature-linked means declarations/file boundaries are connected to an authored user workflow/topic. It does not prove every conditional path was manually audited. Infrastructure is explicitly accounted for rather than presented as another supported feature. Support/generated/vendor-library files are not runtime proof.

Counts: feature-linked=38, infrastructure=1, support-or-generated=9.

Unresolved feature candidates: 0.

| Source | Classification | Topic or reason |
| --- | --- | --- |
| [.gitignore:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/.gitignore#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [Gruntfile.js:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/Gruntfile.js#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [README.md:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/README.md#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [addonskit-for-bricks.php:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/addonskit-for-bricks.php#L1) | feature-linked | [elements](topics/elements.md), [dynamic-tags](topics/dynamic-tags.md) |
| [package.json:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/package.json#L1) | infrastructure | Declarative configuration or shared presentation asset; no independent runtime feature inferred from file existence. |
| [webpack.config.js:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/webpack.config.js#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [.github/PULL_REQUEST_TEMPLATE.md:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/.github/PULL_REQUEST_TEMPLATE.md#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [.github/delete-merged-branch-config.yml:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/.github/delete-merged-branch-config.yml#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [.github/workflows/deploy-to-build-zip.yml:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/.github/workflows/deploy-to-build-zip.yml#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [src/Plugin.php:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Plugin.php#L1) | feature-linked | [elements](topics/elements.md) |
| [src/DynamicTags/DataProvider.php:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/DynamicTags/DataProvider.php#L1) | feature-linked | [dynamic-tags](topics/dynamic-tags.md) |
| [src/DynamicTags/Provider.php:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/DynamicTags/Provider.php#L1) | feature-linked | [dynamic-tags](topics/dynamic-tags.md) |
| [src/Elements/AddListing.php:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/AddListing.php#L1) | feature-linked | [elements](topics/elements.md) |
| [src/Elements/AuthorProfile.php:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/AuthorProfile.php#L1) | feature-linked | [elements](topics/elements.md) |
| [src/Elements/Authors.php:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/Authors.php#L1) | feature-linked | [elements](topics/elements.md) |
| [src/Elements/BaseElement.php:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/BaseElement.php#L1) | feature-linked | [elements](topics/elements.md) |
| [src/Elements/Categories.php:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/Categories.php#L1) | feature-linked | [elements](topics/elements.md) |
| [src/Elements/Checkout.php:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/Checkout.php#L1) | feature-linked | [elements](topics/elements.md) |
| [src/Elements/Dashboard.php:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/Dashboard.php#L1) | feature-linked | [elements](topics/elements.md) |
| [src/Elements/Elements.php:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/Elements.php#L1) | feature-linked | [elements](topics/elements.md) |
| [src/Elements/Listings.php:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/Listings.php#L1) | feature-linked | [elements](topics/elements.md) |
| [src/Elements/Locations.php:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/Locations.php#L1) | feature-linked | [elements](topics/elements.md) |
| [src/Elements/PaymentReceipt.php:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/PaymentReceipt.php#L1) | feature-linked | [elements](topics/elements.md) |
| [src/Elements/SearchForm.php:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/SearchForm.php#L1) | feature-linked | [elements](topics/elements.md) |
| [src/Elements/SearchResult.php:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/SearchResult.php#L1) | feature-linked | [elements](topics/elements.md) |
| [src/Elements/SigninSignup.php:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/SigninSignup.php#L1) | feature-linked | [elements](topics/elements.md) |
| [src/Elements/SingleCategory.php:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/SingleCategory.php#L1) | feature-linked | [elements](topics/elements.md) |
| [src/Elements/SingleLocation.php:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/SingleLocation.php#L1) | feature-linked | [elements](topics/elements.md) |
| [src/Elements/SingleTag.php:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/SingleTag.php#L1) | feature-linked | [elements](topics/elements.md) |
| [src/Elements/SingleTerm.php:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/SingleTerm.php#L1) | feature-linked | [elements](topics/elements.md) |
| [src/Elements/TransactionFailure.php:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/TransactionFailure.php#L1) | feature-linked | [elements](topics/elements.md) |
| [src/Elements/Components/Account.php:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/Components/Account.php#L1) | feature-linked | [elements](topics/elements.md) |
| [src/Elements/Components/SearchModal.php:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/Components/SearchModal.php#L1) | feature-linked | [elements](topics/elements.md) |
| [src/Elements/Listing/ActionLinks.php:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/Listing/ActionLinks.php#L1) | feature-linked | [elements](topics/elements.md) |
| [src/Elements/Listing/AuthorBio.php:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/Listing/AuthorBio.php#L1) | feature-linked | [elements](topics/elements.md) |
| [src/Elements/Listing/ContactForm.php:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/Listing/ContactForm.php#L1) | feature-linked | [elements](topics/elements.md) |
| [src/Elements/Listing/Images.php:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/Listing/Images.php#L1) | feature-linked | [elements](topics/elements.md) |
| [src/Elements/Listing/Map.php:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/Listing/Map.php#L1) | feature-linked | [elements](topics/elements.md) |
| [src/Elements/Listing/MetaInfo.php:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/Listing/MetaInfo.php#L1) | feature-linked | [elements](topics/elements.md) |
| [src/Elements/Listing/QuickActions.php:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/Listing/QuickActions.php#L1) | feature-linked | [elements](topics/elements.md) |
| [src/Elements/Listing/RelatedListings.php:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/Listing/RelatedListings.php#L1) | feature-linked | [elements](topics/elements.md) |
| [src/Elements/Listing/Review.php:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/Listing/Review.php#L1) | feature-linked | [elements](topics/elements.md) |
| [src/Elements/Listing/SocialMedia.php:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/Listing/SocialMedia.php#L1) | feature-linked | [elements](topics/elements.md) |
| [src/Elements/Styles/Container.php:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/Styles/Container.php#L1) | feature-linked | [elements](topics/elements.md) |
| [src/Support/Utils.php:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Support/Utils.php#L1) | feature-linked | [elements](topics/elements.md), [dynamic-tags](topics/dynamic-tags.md) |
| [tools/utils.js:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/tools/utils.js#L1) | feature-linked | [elements](topics/elements.md) |
| [tools/webpack.compress.git.js:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/tools/webpack.compress.git.js#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [tools/webpack.compress.js:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/tools/webpack.compress.js#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
