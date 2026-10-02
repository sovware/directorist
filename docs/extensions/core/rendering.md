# Core rendering and builder context

Read for frontend/editor differences, missing content, asset initialization or overrides.

`includes/template-functions.php::atbdp_get_template` (L61) and `atbdp_get_template_path` (L82) resolve child-theme `directorist/<template>.php`, then parent theme, then `ATBDP_VIEWS_DIR`. The extension helper `atbdp_get_extension_template_path` (L8) is different: it checks the extension file and parent-theme `directorist/extensions/<directory>/<file>.php`. Do not copy the general child/parent hierarchy onto every extension helper. Individual extensions also implement their own `load_template` helpers; inspect the one actually called.

[includes/model/SingleListing.php](../../../includes/model/SingleListing.php), `Listings.php`, `ListingForm.php` and `SearchForm.php` prepare data consumed by templates. Core Elementor widgets in [includes/elementor/](../../../includes/elementor/) and blocks in `blocks/` adapt native behavior. Divi, Bricks and Oxygen integrations have their own render/query/context layers; use their mapped topic only when that builder is present.

For a missing extension widget, trace: active dependency → directory field/widget configuration → plan allowance if installed → queried listing ID → metadata reader → selected template → asset enqueue → browser initialization. Stop expanding when evidence isolates a boundary. Do not fabricate static replicas of native forms or mask permission/data failures with CSS.

Divi's `app/Helpers/ModuleDependencyHelper.php::get_module_plugin_map` explicitly maps Business Hours, Claim, Booking, Marketplace, FAQ, Gallery, linked directories, compare, Live Chat, Job Manager, Universal Search and Pricing Plans modules to plugin basenames. FormGent is an external dependency there, not an additional official extension entry in this catalog. `app/Http/Controllers/ListingsController.php` and module `render` methods supply editor/frontend contexts. See [Divi single modules](../products/directorist-divi-integration/topics/single-modules.md) only for a Divi issue.

Validate the actual browser workflow in both frontend and native editor as needed: save/reopen, keyboard interaction, AJAX state, no-results and relevant desktop/mobile sizes. Screen appearance does not establish successful submission, stored values or payment.
