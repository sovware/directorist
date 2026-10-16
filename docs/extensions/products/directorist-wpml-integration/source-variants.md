# Source variants

Canonical means the investigation starting point selected from actual branch/file evidence, not a released build. A newer commit date identifies a candidate; compare changed files before choosing an issue-specific owner.

| Snapshot | Branch / commit | File delta versus baseline | Evidence |
| --- | --- | --- | --- |
| wpml-integration--main | main / 7d9224d9f6937cf970a562c1abfcd1079c6aad15 | 0 | [machine index](evidence/wpml-integration--main.json) |
| wpml-integration--development | development / 599d77a4698af796bb8e9b04f0b75684a91ab021 | 46 | [machine index](evidence/wpml-integration--development.json) |

## wpml-integration--development differences

These paths differ from the canonical baseline; direction/semantic impact requires reading the diff.
- [app/Controller/Ajax/Get_Directory_Type_Translations.php:1](https://github.com/sovware/directorist-wpml-integration/blob/599d77a4698af796bb8e9b04f0b75684a91ab021/app/Controller/Ajax/Get_Directory_Type_Translations.php#L1)
- [app/Controller/Asset/AdminAsset.php:1](https://github.com/sovware/directorist-wpml-integration/blob/599d77a4698af796bb8e9b04f0b75684a91ab021/app/Controller/Asset/AdminAsset.php#L1)
- [app/Controller/Hook/Add_Listing_Form_Translation.php:1](https://github.com/sovware/directorist-wpml-integration/blob/599d77a4698af796bb8e9b04f0b75684a91ab021/app/Controller/Hook/Add_Listing_Form_Translation.php#L1)
- `app/Controller/Hook/Admin_Text_Translation.php` (absent in this snapshot)
- [app/Controller/Hook/Block_Widget_Translation.php:1](https://github.com/sovware/directorist-wpml-integration/blob/599d77a4698af796bb8e9b04f0b75684a91ab021/app/Controller/Hook/Block_Widget_Translation.php#L1)
- [app/Controller/Hook/Directory_Builder_Actions.php:1](https://github.com/sovware/directorist-wpml-integration/blob/599d77a4698af796bb8e9b04f0b75684a91ab021/app/Controller/Hook/Directory_Builder_Actions.php#L1)
- `app/Controller/Hook/Directory_Builder_String_Package.php` (absent in this snapshot)
- `app/Controller/Hook/Directory_Builder_UI_String_Package.php` (absent in this snapshot)
- [app/Controller/Hook/Directory_Translation.php:1](https://github.com/sovware/directorist-wpml-integration/blob/599d77a4698af796bb8e9b04f0b75684a91ab021/app/Controller/Hook/Directory_Translation.php#L1)
- `app/Controller/Hook/Directory_Type_Meta_Translation.php` (absent in this snapshot)
- `app/Controller/Hook/Directory_Type_Translation_Management_Button.php` (absent in this snapshot)
- [app/Controller/Hook/Email_Translation.php:1](https://github.com/sovware/directorist-wpml-integration/blob/599d77a4698af796bb8e9b04f0b75684a91ab021/app/Controller/Hook/Email_Translation.php#L1)
- [app/Controller/Hook/Filter_Permalinks.php:1](https://github.com/sovware/directorist-wpml-integration/blob/599d77a4698af796bb8e9b04f0b75684a91ab021/app/Controller/Hook/Filter_Permalinks.php#L1)
- [app/Controller/Hook/Init.php:1](https://github.com/sovware/directorist-wpml-integration/blob/599d77a4698af796bb8e9b04f0b75684a91ab021/app/Controller/Hook/Init.php#L1)
- [app/Controller/Hook/Listings_Actions.php:1](https://github.com/sovware/directorist-wpml-integration/blob/599d77a4698af796bb8e9b04f0b75684a91ab021/app/Controller/Hook/Listings_Actions.php#L1)
- [app/Controller/Hook/Option_Translation.php:1](https://github.com/sovware/directorist-wpml-integration/blob/599d77a4698af796bb8e9b04f0b75684a91ab021/app/Controller/Hook/Option_Translation.php#L1)
- `app/Controller/Hook/Page_Setup_Translation.php` (absent in this snapshot)
- `app/Controller/Hook/Page_Shortcode_UI_Translation.php` (absent in this snapshot)
- [app/Controller/Hook/Query_Filtering.php:1](https://github.com/sovware/directorist-wpml-integration/blob/599d77a4698af796bb8e9b04f0b75684a91ab021/app/Controller/Hook/Query_Filtering.php#L1)
- [app/Controller/Hook/REST_API.php:1](https://github.com/sovware/directorist-wpml-integration/blob/599d77a4698af796bb8e9b04f0b75684a91ab021/app/Controller/Hook/REST_API.php#L1)
- [app/Controller/Hook/Search_Form_Field_Translation.php:1](https://github.com/sovware/directorist-wpml-integration/blob/599d77a4698af796bb8e9b04f0b75684a91ab021/app/Controller/Hook/Search_Form_Field_Translation.php#L1)
- [app/Controller/Hook/Search_Form_Filter.php:1](https://github.com/sovware/directorist-wpml-integration/blob/599d77a4698af796bb8e9b04f0b75684a91ab021/app/Controller/Hook/Search_Form_Filter.php#L1)
- [app/Controller/Hook/Selectfield_Translation.php:1](https://github.com/sovware/directorist-wpml-integration/blob/599d77a4698af796bb8e9b04f0b75684a91ab021/app/Controller/Hook/Selectfield_Translation.php#L1)
- [app/Controller/Hook/Settings_Registration.php:1](https://github.com/sovware/directorist-wpml-integration/blob/599d77a4698af796bb8e9b04f0b75684a91ab021/app/Controller/Hook/Settings_Registration.php#L1)
- [app/Controller/Hook/Sorting_Options_Translation.php:1](https://github.com/sovware/directorist-wpml-integration/blob/599d77a4698af796bb8e9b04f0b75684a91ab021/app/Controller/Hook/Sorting_Options_Translation.php#L1)
- [app/Helper/WPML_Helper.php:1](https://github.com/sovware/directorist-wpml-integration/blob/599d77a4698af796bb8e9b04f0b75684a91ab021/app/Helper/WPML_Helper.php#L1)
- [directorist-wpml-integration.php:1](https://github.com/sovware/directorist-wpml-integration/blob/599d77a4698af796bb8e9b04f0b75684a91ab021/directorist-wpml-integration.php#L1)
- [helper/const.php:1](https://github.com/sovware/directorist-wpml-integration/blob/599d77a4698af796bb8e9b04f0b75684a91ab021/helper/const.php#L1)
- [wpml-config.xml:1](https://github.com/sovware/directorist-wpml-integration/blob/599d77a4698af796bb8e9b04f0b75684a91ab021/wpml-config.xml#L1)

## Visible remote branch tips

All returned refs are recorded in [branch inventory](../../branches.json). The selected snapshots cover latest tip, development where distinct, catalog-named default, payment-generation variants and relevant local files. Other branch tips remain searchable candidates; they are not silently folded into the baseline.

Local snapshots include file hashes and dirty-state metadata. Local generated assets are inventoried but not interpreted as source-equivalent builds. Re-run source drift checks before reusing a mapping.
