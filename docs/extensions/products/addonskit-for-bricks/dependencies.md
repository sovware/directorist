# External dependencies and runtime guard evidence

Package declarations show declared dependencies, not proof that each package is used or bundled in the installed build. Guard and request call sites below are actual source references; evaluate their surrounding condition and caller before diagnosing a missing dependency.

## Declared packages

| Manifest | Package | Constraint |
| --- | --- | --- |
| [package.json:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/package.json#L1) | canvas-confetti | ^1.9.0 |
| [package.json:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/package.json#L1) | copy-webpack-plugin | ^12.0.2 |
| [package.json:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/package.json#L1) | gulp | ^5.0.1 |
| [package.json:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/package.json#L1) | qs | ^6.10.2 |

## Guards and external requests

| Source | Check / request | Symbol / target expression |
| --- | --- | --- |
| [addonskit-for-bricks.php:35](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/addonskit-for-bricks.php#L35) | defined | `'ABSPATH'` |
| [addonskit-for-bricks.php:76](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/addonskit-for-bricks.php#L76) | function_exists | `'get_plugins'` |
| [src/Plugin.php:4](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Plugin.php#L4) | defined | `'ABSPATH'` |
| [src/Plugin.php:84](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Plugin.php#L84) | defined | `'ATBDP_POST_TYPE'` |
| [src/Plugin.php:89](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Plugin.php#L89) | function_exists | `'directorist_get_listing_directory'` |
| [src/Plugin.php:98](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Plugin.php#L98) | function_exists | `'get_directorist_type_option'` |
| [src/Plugin.php:113](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Plugin.php#L113) | defined | `'BRICKS_DB_PAGE_CONTENT'` |
| [src/DynamicTags/DataProvider.php:6](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/DynamicTags/DataProvider.php#L6) | defined | `'ABSPATH'` |
| [src/DynamicTags/Provider.php:4](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/DynamicTags/Provider.php#L4) | defined | `'ABSPATH'` |
| [src/DynamicTags/Provider.php:341](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/DynamicTags/Provider.php#L341) | class_exists | `'Bricks\Query'` |
| [src/DynamicTags/Provider.php:363](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/DynamicTags/Provider.php#L363) | defined | `'ATBDP_CATEGORY'` |
| [src/Elements/AddListing.php:5](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/AddListing.php#L5) | defined | `'ABSPATH'` |
| [src/Elements/AuthorProfile.php:5](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/AuthorProfile.php#L5) | defined | `'ABSPATH'` |
| [src/Elements/Authors.php:5](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/Authors.php#L5) | defined | `'ABSPATH'` |
| [src/Elements/BaseElement.php:5](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/BaseElement.php#L5) | defined | `'ABSPATH'` |
| [src/Elements/Categories.php:5](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/Categories.php#L5) | defined | `'ABSPATH'` |
| [src/Elements/Checkout.php:5](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/Checkout.php#L5) | defined | `'ABSPATH'` |
| [src/Elements/Dashboard.php:5](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/Dashboard.php#L5) | defined | `'ABSPATH'` |
| [src/Elements/Elements.php:5](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/Elements.php#L5) | defined | `'ABSPATH'` |
| [src/Elements/Listings.php:5](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/Listings.php#L5) | defined | `'ABSPATH'` |
| [src/Elements/Locations.php:5](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/Locations.php#L5) | defined | `'ABSPATH'` |
| [src/Elements/PaymentReceipt.php:5](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/PaymentReceipt.php#L5) | defined | `'ABSPATH'` |
| [src/Elements/SearchForm.php:5](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/SearchForm.php#L5) | defined | `'ABSPATH'` |
| [src/Elements/SearchResult.php:5](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/SearchResult.php#L5) | defined | `'ABSPATH'` |
| [src/Elements/SigninSignup.php:5](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/SigninSignup.php#L5) | defined | `'ABSPATH'` |
| [src/Elements/SingleCategory.php:5](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/SingleCategory.php#L5) | defined | `'ABSPATH'` |
| [src/Elements/SingleLocation.php:5](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/SingleLocation.php#L5) | defined | `'ABSPATH'` |
| [src/Elements/SingleTag.php:5](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/SingleTag.php#L5) | defined | `'ABSPATH'` |
| [src/Elements/SingleTerm.php:5](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/SingleTerm.php#L5) | defined | `'ABSPATH'` |
| [src/Elements/TransactionFailure.php:5](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/TransactionFailure.php#L5) | defined | `'ABSPATH'` |
| [src/Elements/Components/Account.php:5](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/Components/Account.php#L5) | defined | `'ABSPATH'` |
| [src/Elements/Components/SearchModal.php:5](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/Components/SearchModal.php#L5) | defined | `'ABSPATH'` |
| [src/Elements/Listing/ActionLinks.php:5](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/Listing/ActionLinks.php#L5) | defined | `'ABSPATH'` |
| [src/Elements/Listing/AuthorBio.php:5](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/Listing/AuthorBio.php#L5) | defined | `'ABSPATH'` |
| [src/Elements/Listing/ContactForm.php:5](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/Listing/ContactForm.php#L5) | defined | `'ABSPATH'` |
| [src/Elements/Listing/Images.php:5](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/Listing/Images.php#L5) | defined | `'ABSPATH'` |
| [src/Elements/Listing/Map.php:5](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/Listing/Map.php#L5) | defined | `'ABSPATH'` |
| [src/Elements/Listing/MetaInfo.php:5](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/Listing/MetaInfo.php#L5) | defined | `'ABSPATH'` |
| [src/Elements/Listing/QuickActions.php:5](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/Listing/QuickActions.php#L5) | defined | `'ABSPATH'` |
| [src/Elements/Listing/RelatedListings.php:5](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/Listing/RelatedListings.php#L5) | defined | `'ABSPATH'` |
| [src/Elements/Listing/Review.php:5](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/Listing/Review.php#L5) | defined | `'ABSPATH'` |
| [src/Elements/Listing/SocialMedia.php:5](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/Listing/SocialMedia.php#L5) | defined | `'ABSPATH'` |
| [src/Elements/Styles/Container.php:5](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/Styles/Container.php#L5) | defined | `'ABSPATH'` |
| [src/Support/Utils.php:4](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Support/Utils.php#L4) | defined | `'ABSPATH'` |

[All hook registrations and emissions](contracts.md) are searchable by exact name using the router CLI. A shared hook name indicates a candidate interaction, not a hard installation dependency. Platform themes/builders must be identified from the actual site; source guards cannot prove which is active.
