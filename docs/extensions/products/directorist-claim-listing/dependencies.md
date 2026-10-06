# External dependencies and runtime guard evidence

Package declarations show declared dependencies, not proof that each package is used or bundled in the installed build. Guard and request call sites below are actual source references; evaluate their surrounding condition and caller before diagnosing a missing dependency.

## Declared packages

| Manifest | Package | Constraint |
| --- | --- | --- |

## Guards and external requests

| Source | Check / request | Symbol / target expression |
| --- | --- | --- |
| [config-helper.php:3](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/config-helper.php:3>) | function_exists | `'dcl_get_version_from_content'` |
| [config-helper.php:15](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/config-helper.php:15>) | function_exists | `'dcl_get_version_from_file_content'` |
| [config.php:3](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/config.php:3>) | defined | `'DCL_VERSION'` |
| [config.php:5](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/config.php:5>) | defined | `'DCL_DIR'` |
| [config.php:7](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/config.php:7>) | defined | `'DCL_URL'` |
| [config.php:9](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/config.php:9>) | defined | `'DCL_BASE'` |
| [config.php:11](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/config.php:11>) | defined | `'DCL_INC_DIR'` |
| [config.php:13](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/config.php:13>) | defined | `'DCL_ASSETS'` |
| [config.php:15](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/config.php:15>) | defined | `'DCL_TEMPLATES_DIR'` |
| [config.php:17](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/config.php:17>) | defined | `'DCL_LANG_DIR'` |
| [config.php:19](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/config.php:19>) | defined | `'DCL_NAME'` |
| [config.php:21](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/config.php:21>) | defined | `'ATBDP_POST_TYPE'` |
| [config.php:23](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/config.php:23>) | defined | `'ATBDP_PRICING_PLANS_POST_TYPE'` |
| [config.php:26](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/config.php:26>) | defined | `'ATBDP_AUTHOR_URL'` |
| [config.php:30](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/config.php:30>) | defined | `'ATBDP_CLAIM_POST_ID'` |
| [directorist-claim-listing.php:15](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/directorist-claim-listing.php:15>) | defined | `'ABSPATH'` |
| [directorist-claim-listing.php:22](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/directorist-claim-listing.php:22>) | class_exists | `'DCL_Base'` |
| [directorist-claim-listing.php:153](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/directorist-claim-listing.php:153>) | function_exists | `'directorist_is_listing_feature_available'` |
| [directorist-claim-listing.php:661](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/directorist-claim-listing.php:661>) | function_exists | `'directorist_get_checkout_page_url'` |
| [directorist-claim-listing.php:728](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/directorist-claim-listing.php:728>) | function_exists | `'directorist_get_checkout_page_url'` |
| [directorist-claim-listing.php:757](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/directorist-claim-listing.php:757>) | function_exists | `'directorist_is_listing_feature_available'` |
| [directorist-claim-listing.php:780](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/directorist-claim-listing.php:780>) | defined | `'DOING_AUTOSAVE'` |
| [directorist-claim-listing.php:963](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/directorist-claim-listing.php:963>) | defined | `'DOING_AUTOSAVE'` |
| [directorist-claim-listing.php:1173](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/directorist-claim-listing.php:1173>) | class_exists | `'EDD_SL_Plugin_Updater'` |
| [directorist-claim-listing.php:1190](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/directorist-claim-listing.php:1190>) | defined | `'DCL_FILE'` |
| [directorist-claim-listing.php:1439](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/directorist-claim-listing.php:1439>) | function_exists | `'directorist_plan_key'` |
| [directorist-claim-listing.php:1508](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/directorist-claim-listing.php:1508>) | function_exists | `'directorist_plan_key'` |
| [directorist-claim-listing.php:1522](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/directorist-claim-listing.php:1522>) | function_exists | `'directorist_set_listing_status'` |
| [directorist-claim-listing.php:1553](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/directorist-claim-listing.php:1553>) | function_exists | `'directorist_get_pricing_plan_by_id'` |
| [directorist-claim-listing.php:1657](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/directorist-claim-listing.php:1657>) | function_exists | `'directorist_is_owner_notifiable_event'` |
| [directorist-claim-listing.php:1665](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/directorist-claim-listing.php:1665>) | function_exists | `'ATBDP'` |
| [directorist-claim-listing.php:1676](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/directorist-claim-listing.php:1676>) | function_exists | `'ATBDP'` |
| [directorist-claim-listing.php:1744](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/directorist-claim-listing.php:1744>) | function_exists | `'directorist_is_plugin_active'` |
| [directorist-claim-listing.php:1750](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/directorist-claim-listing.php:1750>) | function_exists | `'directorist_is_plugin_active_for_network'` |
| [uninstall.php:5](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/uninstall.php:5>) | defined | `'WP_UNINSTALL_PLUGIN'` |
| [inc/class-claim-now.php:35](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/inc/class-claim-now.php:35>) | function_exists | `'directorist_is_listing_feature_available'` |
| [inc/class-db.php:8](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/inc/class-db.php:8>) | defined | `'DOING_AUTOSAVE'` |
| [inc/class-db.php:59](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/inc/class-db.php:59>) | function_exists | `'directorist_plan_key'` |
| [inc/class-enqueuer.php:5](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/inc/class-enqueuer.php:5>) | class_exists | `'DCL_Enqueuer'` |
| [inc/directory_type.php:5](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/inc/directory_type.php:5>) | class_exists | `'Claim_Post_Type_Manager'` |
| [inc/directory_type.php:155](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/inc/directory_type.php:155>) | defined | `'DOING_AUTOSAVE'` |
| [inc/helper-functions.php:3](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/inc/helper-functions.php:3>) | defined | `'ABSPATH'` |
| [inc/helper-functions.php:8](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/inc/helper-functions.php:8>) | function_exists | `'atbdp_get_option'` |
| [inc/helper-functions.php:68](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/inc/helper-functions.php:68>) | function_exists | `'get_directorist_option'` |
| [inc/helper-functions.php:112](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/inc/helper-functions.php:112>) | function_exists | `'atbdp_sanitize_array'` |
| [inc/helper-functions.php:141](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/inc/helper-functions.php:141>) | function_exists | `'is_directoria_active'` |
| [inc/helper-functions.php:155](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/inc/helper-functions.php:155>) | function_exists | `'dcl_claim_status'` |
| [inc/helper-functions.php:167](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/inc/helper-functions.php:167>) | function_exists | `'is_pricing_plans_active'` |
| [inc/helper-functions.php:174](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/inc/helper-functions.php:174>) | function_exists | `'directorist_pricing_plans_singleton'` |
| [inc/helper-functions.php:178](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/inc/helper-functions.php:178>) | function_exists | `'is_pricing_plans_active_with_dcl'` |
| [inc/helper-functions.php:201](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/inc/helper-functions.php:201>) | function_exists | `'dcl_current_user'` |
| [inc/helper-functions.php:218](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/inc/helper-functions.php:218>) | function_exists | `'directorist_is_owner_notifiable_event'` |
| [inc/helper-functions.php:280](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/inc/helper-functions.php:280>) | function_exists | `'dcl_email_admin_listing_claim'` |
| [inc/helper-functions.php:293](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/inc/helper-functions.php:293>) | function_exists | `'directorist_is_admin_notifiable_event'` |
| [inc/helper-functions.php:349](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/inc/helper-functions.php:349>) | function_exists | `'dcl_need_to_charge_with_plan'` |
| [inc/helper-functions.php:401](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/inc/helper-functions.php:401>) | function_exists | `'dcl_need_to_charge_without_plan'` |
| [inc/helper-functions.php:417](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/inc/helper-functions.php:417>) | function_exists | `'need_claim_to_charge_manually'` |
| [inc/helper-functions.php:434](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/inc/helper-functions.php:434>) | function_exists | `'non_paid_claim'` |
| [inc/helper-functions.php:451](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/inc/helper-functions.php:451>) | function_exists | `'dcl_new_claim'` |
| [inc/helper-functions.php:485](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/inc/helper-functions.php:485>) | function_exists | `'dcl_tract_duplicate_claim'` |
| [templates/admin-meta-fields.php:3](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/templates/admin-meta-fields.php:3>) | defined | `'ABSPATH'` |
| [templates/partials/plan-selector.php:10](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/templates/partials/plan-selector.php:10>) | defined | `'ABSPATH'` |
| [templates/partials/plan-selector.php:54](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/templates/partials/plan-selector.php:54>) | class_exists | `'ATBDP_Permalink'` |

[All hook registrations and emissions](contracts.md) are searchable by exact name using the router CLI. A shared hook name indicates a candidate interaction, not a hard installation dependency. Platform themes/builders must be identified from the actual site; source guards cannot prove which is active.
