<?php

if ( ! class_exists( 'ATBDP_Multi_Directory_Migration' ) ) :

class ATBDP_Multi_Directory_Migration {

    public $multi_directory_manager = null;

    public function __construct( array $args = [] ) {
        if ( isset( $args['multi_directory_manager'] ) ) {
            $this->multi_directory_manager = $args['multi_directory_manager'];
        }
    }

    // Run Migration
    public function run() {
        $this->migrate();
    }

    public function migrate() {
        $fields = $this->get_fields_data();
        
        echo '<pre>';
        print_r( $fields );
        echo '</pre>';
        
        $add_directory = $this->multi_directory_manager->add_directory([
            'directory_name' => 'General',
            'fields_value'   => $fields,
        ]);
        
        if ( $add_directory['status']['success'] ) {
            update_option( 'atbdp_migrated_to_multidirectory', true );
            update_term_meta( $add_directory['term_id'], '_default', true );

            // Add directory type to all listings
            $listings = new WP_Query([
                'post_type' => ATBDP_POST_TYPE,
                'status'    => 'publish',
                'per_page'  => -1,                
            ]);

            if ( $listings->have_posts() ) {
                while ( $listings->have_posts() ) {
                    $listings->the_post();

                    wp_set_object_terms( get_the_id(), $add_directory['term_id'], 'atbdp_listing_types' );
                }
            }
        }
        
    }

    // get_fields_data
    public function get_fields_data() {

        $submission_form_fields = $this->get_submission_form_fields_data();
        $form_fields_common_data = [ 'submission_form_custom_fields' => $submission_form_fields ];

        $listings_card_wedgets = $this->get_listings_card_wedgets_data();
        $listings_card_common_data = [ 'listings_card_wedgets' => $listings_card_wedgets ];

        $fields = apply_filters( 'atbdp_multidirectory_migration_fields', [
            "name"          => "General",
            "icon"          => "fa fa-home",
            "singular_name" => "listing",
            "plural_name"   => "listings",
            "permalink"     => "listing",
            "preview_image" => $this->get_preview_image(),
            "preview_mode"  => get_directorist_option( 'preview_enable', true ),

            "default_expiration"       => get_directorist_option( 'listing_expire_in_days', 365 ),
            "new_listing_status"       => get_directorist_option( 'new_listing_status', 'pending' ),
            "edit_listing_status"      => get_directorist_option( 'edit_listing_status', 'pending' ),
            "submit_button_label"      => get_directorist_option( 'submit_label', 'Save & Preview' ),
            "listing_terms_condition"  => get_directorist_option( 'listing_terms_condition', true ),
            "require_terms_conditions" => get_directorist_option( 'require_terms_conditions', true ),
            "terms_label"              => $this->get_terms_label(),
            "listing_privacy"          => get_directorist_option( 'listing_privacy', true ),
            "require_privacy"          => get_directorist_option( 'require_privacy', true ),
            "privacy_label"            => $this->get_privacy_label(),
            "submission_form_fields"   => $submission_form_fields,
            "single_listings_contents" => $this->get_single_listings_contents_data( $form_fields_common_data ),
            "similar_listings_title"   => get_directorist_option( 'rel_listing_title', true ),
            "enable_similar_listings"  => get_directorist_option( 'enable_rel_listing', true ),
            "similar_listings_logics"  => get_directorist_option( 'rel_listings_logic', 'OR' ),
            "search_form_fields"       => $this->get_search_form_fields( $form_fields_common_data ),
            "single_listing_header"    => $this->get_single_listing_header_data(),
            "listings_card_grid_view"  => $this->get_listings_card_grid_view_data( $listings_card_common_data ),
            "listings_card_list_view"  => $this->get_listings_card_list_view_data( $listings_card_common_data ),
            
            "similar_listings_number_of_listings_to_show" => get_directorist_option( 'rel_listing_num', 10 ),
            "similar_listings_number_of_columns"          => get_directorist_option( 'rel_listing_column', 3 ),
        ]);

        return $fields;
    }

    // get_submission_form_fields_data
    public function get_submission_form_fields_data() {
        // Submission Form Fields
        $submission_form_preset_fields = [];

        $submission_form_preset_fields[ "title" ] = [
            "type"         => "text",
            "field_key"    => "listing_title",
            "required"     => get_directorist_option( 'require_title', true ),
            "label"        => get_directorist_option( 'title_label', "Title" ),
            "placeholder"  => get_directorist_option( 'title_placeholder', "Enter a title" ),
            "widget_group" => "preset",
            "widget_name"  => "title",
        ];

        $submission_form_preset_fields[ "description" ] = [
            "type"           => "wp_editor",
            "field_key"      => "listing_content",
            "label"          => get_directorist_option( 'long_details_label', 'Long Details' ),
            "placeholder"    => "",
            "required"       => get_directorist_option( 'require_long_details', false ),
            "only_for_admin" => get_directorist_option( 'display_desc_for', false ),
            "widget_group"   => "preset",
            "widget_name"    => "description"
        ];

        $submission_form_preset_fields[ "pricing" ] = [
            "pricing_type"           => "both",
            "label"                  => get_directorist_option( 'pricing_label', 'Pricing' ),
            "price_range_label"      => get_directorist_option( 'price_range_label', 'Select Price Range' ),
            "price_range_options"    => "cheap",
            "price_unit_field_type"  => "number",
            "price_unit_field_label" => get_directorist_option( 'price_label', 'Price' ),
            "widget_group"           => "preset",
            "widget_name"            => "pricing",
        ];

        $submission_form_preset_fields[ "zip" ] = [
            "type"           => "text",
            "field_key"      => "zip",
            "label"          => get_directorist_option( 'zip_label', 'Zip/Post Code' ),
            "placeholder"    => get_directorist_option( 'zip_placeholder', 'Enter Zip/Post Code' ),
            "required"       => get_directorist_option( 'require_zip', false ),
            "only_for_admin" => get_directorist_option( 'display_zip_for', false ),
            "widget_group"   => "preset",
            "widget_name"    => "zip",
        ];

        $submission_form_preset_fields[ "phone" ] = [
            "type"           => "tel",
            "field_key"      => "phone",
            "label"          => get_directorist_option( 'phone_label', 'Phone' ),
            "placeholder"    => get_directorist_option( 'phone_placeholder', 'Phone Number' ),
            "required"       => get_directorist_option( 'require_phone_number', false ),
            "only_for_admin" => get_directorist_option( 'display_phone_for', false ),
            "widget_group"   => "preset",
            "widget_name"    => "phone"
        ];

        $submission_form_preset_fields[ "phone2" ] = [
            "type"           => "tel",
            "field_key"      => "phone2",
            "label"          => get_directorist_option( 'phone_label2', 'Phone 2' ),
            "placeholder"    => get_directorist_option( 'phone2_placeholder', 'Phone Number' ),
            "required"       => get_directorist_option( 'require_phone2_number', false ),
            "only_for_admin" => get_directorist_option( 'display_phone2_for', false ),
            "widget_group"   => "preset",
            "widget_name"    => "phone2"
        ];

        $submission_form_preset_fields[ "email" ] = [
            "type"           => "tel",
            "field_key"      => "phone2",
            "label"          => get_directorist_option( 'phone_label2', 'Phone 2' ),
            "placeholder"    => get_directorist_option( 'phone2_placeholder', 'Phone Number' ),
            "required"       => get_directorist_option( 'require_phone2_number', false ),
            "only_for_admin" => get_directorist_option( 'display_phone2_for', false ),
            "widget_group"   => "preset",
            "widget_name"    => "phone2"
        ];

        $submission_form_preset_fields[ "email" ] = [
            "type"           => "email",
            "field_key"      => "email",
            "label"          => get_directorist_option( 'email_label', 'Email' ),
            "placeholder"    => get_directorist_option( 'email_placeholder', 'Enter Email' ),
            "required"       => get_directorist_option( 'require_email', false ),
            "only_for_admin" => get_directorist_option( 'display_email_for', false ),
            "widget_group"   => "preset",
            "widget_name"    => "email"
        ];

        $submission_form_preset_fields[ "tag" ] = [
            "type"           => "multiple",
            "field_key"      => "tax_input[at_biz_dir-tags][]",
            "label"          => get_directorist_option( 'tag_label', 'Tag' ),
            "required"       => get_directorist_option( 'require_tags', false ),
            "allow_new"      => get_directorist_option( 'create_new_tag', true ),
            "only_for_admin" => get_directorist_option( 'display_tag_for', false ),
            "widget_group"   => "preset",
            "widget_name"    => "tag"
        ];

        $submission_form_preset_fields[ "website" ] = [
            "type"           => "text",
            "field_key"      => "website",
            "label"          => get_directorist_option( 'website_label', 'Website' ),
            "placeholder"    => get_directorist_option( 'website_placeholder', 'Listing Website eg. http://example.com' ),
            "required"       => get_directorist_option( 'require_website', false ),
            "only_for_admin" => get_directorist_option( 'display_website_for', false ),
            "plans"          => [],
            "widget_group"   => "preset",
            "widget_name"    => "website"
        ];

        $submission_form_preset_fields[ "social_info" ] = [
            "type"           => "add_new",
            "field_key"      => "social",
            "label"          => get_directorist_option( 'social_label', 'Social Information' ),
            "required"       => get_directorist_option( 'require_social_info', false ),
            "only_for_admin" => get_directorist_option( 'display_social_info_for', false ),
            "widget_group"   => "preset",
            "widget_name"    => "social_info"
        ];

        $submission_form_preset_fields[ "fax" ] = [
            "type"           => "number",
            "field_key"      => "fax",
            "label"          => get_directorist_option( 'fax_label', 'Fax' ),
            "placeholder"    => get_directorist_option( 'fax_placeholder', 'Enter Fax' ),
            "required"       => get_directorist_option( 'require_fax', false ),
            "only_for_admin" => get_directorist_option( 'display_fax_for', false ),
            "widget_group"   => "preset",
            "widget_name"    => "fax"
        ];

        $submission_form_preset_fields[ "address" ] = [
            "type"           => "text",
            "field_key"      => "address",
            "label"          => get_directorist_option( 'address_label', 'Address' ),
            "placeholder"    => get_directorist_option( 'address_placeholder', 'Listing address eg. New York, USA' ),
            "required"       => get_directorist_option( 'require_address', false ),
            "only_for_admin" => get_directorist_option( 'display_address_for', false ),
            "widget_group"   => "preset",
            "widget_name"    => "address"
        ];

        $submission_form_preset_fields[ "map" ] = [
            "type"           => "map",
            "field_key"      => "map",
            "label"          => "Map",
            "required"       => get_directorist_option( 'display_map_for', false ),
            "only_for_admin" => false,
            "widget_group"   => "preset",
            "widget_name"    => "map"
        ];

        $submission_form_preset_fields[ "view_count" ] = [
            "type"           => "number",
            "field_key"      => "atbdp_post_views_count",
            "label"          => get_directorist_option( 'views_count_label', 'Views Count' ),
            "placeholder"    => "",
            "required"       => false,
            "only_for_admin" => true,
            "widget_group"   => "preset",
            "widget_name"    => "view_count"
        ];

        $submission_form_preset_fields[ "location" ] = [
            "type"           => "multiple",
            "field_key"      => "tax_input[at_biz_dir-location][]",
            "label"          => get_directorist_option( 'location_label', 'Location' ),
            "required"       => get_directorist_option( 'require_location', false ),
            "only_for_admin" => get_directorist_option( 'display_loc_for', false ),
            "widget_group"   => "preset",
            "widget_name"    => "location"
        ];

        $submission_form_preset_fields[ "category" ] = [
            "type"           => "multiple",
            "field_key"      => "admin_category_select[]",
            "label"          => get_directorist_option( 'category_label', 'Select Category' ),
            "required"       => get_directorist_option( 'require_category', false ),
            "only_for_admin" => false,
            "widget_group"   => "preset",
            "widget_name"    => "category"
        ];

        $submission_form_preset_fields[ "image_upload" ] = [
            "type"                  => "media",
            "field_key"             => "listing_img",
            "label"                 => get_directorist_option( 'gallery_label', 'Select Files' ),
            "required"              => get_directorist_option( 'require_gallery_img', false ),
            "max_image_limit"       => get_directorist_option( 'require_gallery_img', 5 ),
            "max_per_image_limit"   => get_directorist_option( 'max_gallery_upload_size_per_file', 0 ),
            "max_total_image_limit" => get_directorist_option( 'max_gallery_upload_size', 2 ),
            "only_for_admin"        => get_directorist_option( 'display_glr_img_for', false ),
            "widget_group"          => "preset",
            "widget_name"           => "image_upload"
        ];

        $submission_form_preset_fields[ "video" ] = [
            "type"           => "text",
            "field_key"      => "videourl",
            "label"          => get_directorist_option( 'video_label', 'Video Url' ),
            "placeholder"    => get_directorist_option( 'video_placeholder', 'Only YouTube & Vimeo URLs.' ),
            "required"       => get_directorist_option( 'require_video', false ),
            "only_for_admin" => get_directorist_option( 'display_video_for', false ),
            "widget_group"   => "preset",
            "widget_name"    => "video"
        ];

        $submission_form_custom_fields = $this->get_old_custom_fields();
        $submission_form_fields = array_merge( $submission_form_preset_fields, $submission_form_custom_fields );
        
        $submission_form_groups = [];

        // General Group
        $submission_form_groups[] = [
            "label" => "General Group",
            "lock" => true,
            "fields" => [
                "title",
                "description",
                "view_count",
                "pricing",
                "location",
                "tag",
                "category"
            ],
        ];

        // Features
        if ( ! empty( $submission_form_custom_fields ) && is_array( $submission_form_custom_fields ) ) {
            $submission_form_groups[] = [
                "label" => "Features",
                "fields" => array_keys( $submission_form_custom_fields ),
            ];
        }

        // Contact Info
        $submission_form_groups[] = [
            "label" => "Contact Info",
            "fields" => [
                "zip",
                "phone",
                "phone2",
                "email",
                "fax",
                "website",
                "social_info"
            ],
        ];

        // Map
        $submission_form_groups[] = [
            "label" => "Map",
            "fields" => [
                "address",
                "map"
            ],
        ];

        // Gallery
        $submission_form_groups[] = [
            "label" => "Gallery",
            "fields" => [
                "image_upload",
                "video",
            ],
        ];

        $submission_form_fields = [
            "fields" => $submission_form_fields,
            "groups" => $submission_form_groups
        ];

        return $submission_form_fields;
    }

    // get_single_listings_contents_data
    public function get_single_listings_contents_data( array $args = [] ) {

        $default = [ 'submission_form_custom_fields' => [] ];
        $args    = array_merge( $default, $args );

        $submission_form_custom_fields = $args[ 'submission_form_custom_fields' ];

        // Single Listing Contents
        $single_listings_preset_fields = [
            "address" => [
                "widget_group" => "preset_widgets",
                "widget_name" => "address"
            ],
            "phone" => [
                "widget_group" => "preset_widgets",
                "widget_name" => "phone"
            ],
            "phone2" => [
                "widget_group" => "preset_widgets",
                "widget_name" => "phone2"
            ],
            "zip" => [
                "widget_group" => "preset_widgets",
                "widget_name" => "zip"
            ],
            "email" => [
                "widget_group" => "preset_widgets",
                "widget_name" => "email"
            ],
            "website" => [
                "widget_group" => "preset_widgets",
                "widget_name" => "website"
            ],
            "fax" => [
                "widget_group" => "preset_widgets",
                "widget_name" => "fax"
            ],
            "social_info" => [
                "widget_group" => "preset_widgets",
                "widget_name" => "social_info"
            ],
            "map" => [
                "widget_group" => "preset_widgets",
                "widget_name" => "map"
            ],
            "video" => [
                "widget_group" => "preset_widgets",
                "widget_name" => "video"
            ],
            "review" => [
                "label" => "Review",
                "widget_group" => "other_widgets",
                "widget_name" => "review"
            ]
        ];

        $single_listings_custom_fields = [];
        foreach ( $submission_form_custom_fields as $field_key => $args ) {
            $single_listings_custom_fields[ $field_key ] = [
                "label"        => $args['label'],
                "widget_group" => "preset_widgets",
                "widget_name"  => $args['widget_name']
            ];
        }

        $single_listings_fields = array_merge( $single_listings_preset_fields, $single_listings_custom_fields );
        
        $single_listings_contents = [
            "fields" => $single_listings_fields,
            "groups" => [
                [
                    "label" => get_directorist_option( 'custom_section_lable', __( 'Features', 'directorist' ) ),
                    "fields" => array_keys( $single_listings_custom_fields )
                ],
                [
                    "label" => get_directorist_option( 'listing_location_text', __( 'Location', 'directorist' ) ),
                    "fields" => [ "map" ]
                ],
                [
                    "label" => get_directorist_option( 'contact_info_text', __( 'Contact Info', 'directorist' ) ),
                    "fields" => [
                        "address", "phone", "phone2", "zip", "email", "fax", "website", "social_info"
                    ]
                ],
                [
                    "label" => get_directorist_option( 'atbd_video_title', __( 'Video', 'directorist' ) ),
                    "fields" => [ "video" ]
                ],
                [
                    "label" => "Review",
                    "fields" => [ "review" ]
                ]
            ]
        ];

        return $single_listings_contents;
    }

    // get_search_form_fields
    public function get_search_form_fields( array $args = [] ) {
        $default = [ 'submission_form_custom_fields' => [] ];
        $args    = array_merge( $default, $args );

        $submission_form_custom_fields = $args[ 'submission_form_custom_fields' ];

        // Search Form
        $search_fields_map = [
            // Basic
            'search_text'     => [
                'field_key' => 'title',
                'options' => [
                    "required"     => get_directorist_option( 'require_search_text', false ),
                    "placeholder"  => get_directorist_option( 'listings_search_text_placeholder', "What are you looking for?" ),
                    "widget_group" => "available_widgets",
                    "widget_name"  => "title"
                ],
            ],
            'search_category' => [
                'field_key' => 'category',
                'options' => [
                    "required"     => get_directorist_option( 'require_search_category', false ),
                    "widget_group" => "available_widgets",
                    "widget_name"  => "category",
                    "placeholder"  => get_directorist_option( 'search_category_placeholder', "Select a category" ),
                ],
            ],
            'search_location' => [
                'field_key' => 'location',
                "options" => [
                    "required"     => get_directorist_option( 'require_search_location', false ),
                    "widget_group" => "available_widgets",
                    "widget_name"  => "location",
                    "placeholder"  => get_directorist_option( 'search_location_placeholder', "Select a category" ),
                ],
            ],

            // Advanced
            'search_rating' => [
                'field_key' => 'review',
                "options" => [
                    "required"     => false,
                    "widget_group" => "available_widgets",
                    "widget_name"  => "",
                    "placeholder"  => ""
                ],
            ],
            'search_tag' => [
                'field_key' => 'tag',
                "options" => [
                    "required"     => false,
                    "widget_group" => "available_widgets",
                    "widget_name"  => "",
                    "placeholder"  => ""
                ],
            ],
            'search_website' => [
                'field_key' => 'website',
                "options" => [
                    "required"     => false,
                    "widget_group" => "available_widgets",
                    "widget_name"  => "",
                    "placeholder"  => ""
                ],
            ],
            'search_email' => [
                'field_key' => 'email',
                "options" => [
                    "required"     => false,
                    "widget_group" => "available_widgets",
                    "widget_name"  => "",
                    "placeholder"  => ""
                ],
            ],
            'search_phone' => [
                'field_key' => 'phone',
                "options" => [
                    "required"     => false,
                    "widget_group" => "available_widgets",
                    "widget_name"  => "",
                    "placeholder"  => ""
                ],
            ],
            'search_fax' => [
                'field_key' => 'fax',
                "options" => [
                    "required"     => false,
                    "widget_group" => "available_widgets",
                    "widget_name"  => "",
                    "placeholder"  => ""
                ],
            ],
            'search_zip_code' => [
                'field_key' => 'zip',
                "options" => [
                    "required"     => false,
                    "widget_group" => "available_widgets",
                    "widget_name"  => "",
                    "placeholder"  => ""
                ],
            ],
            'radius_search' => [
                'field_key' => 'radius_search',
                "options" => [
                    "required"     => false,
                    "widget_group" => "available_widgets",
                    "widget_name"  => "",
                    "placeholder"  => ""
                ],
            ],
        ];
        
        // Get Basic Search Fields
        $old_basic_search_fields = get_directorist_option( 'search_tsc_fields', [] );
        $search_form_fields_basic_items = [];

        foreach ( $old_basic_search_fields as $field_key ) {
            if ( empty( $search_fields_map[ $field_key ] ) ) { continue; }

            $new_field_key = $search_fields_map[ $field_key ][ 'field_key' ];
            $search_form_fields_basic_items[ $new_field_key ] = $search_fields_map[ $field_key ][ 'options' ];
        }

        // Get Advanced Fields
        $old_advanced_search_fields = get_directorist_option( 'search_more_filters_fields', [] );
        $search_form_fields_advanced_items = [];

        foreach ( $old_advanced_search_fields as $field_key ) {
            if ( empty( $search_fields_map[ $field_key ] ) ) { continue; }

            $new_field_key = $search_fields_map[ $field_key ][ 'field_key' ];
            $search_form_fields_advanced_items[ $new_field_key ] = $search_fields_map[ $field_key ][ 'options' ];
        }

        // Get Other Fields
        // Price Field
        if ( in_array( 'search_price', $old_advanced_search_fields ) || in_array( 'search_price_range', $old_advanced_search_fields ) ) {
            $search_form_fields_advanced_items[ 'pricing' ] = [
                "required" => false,
                "widget_group" => "available_widgets",
                "widget_name" => "pricing"
            ];
        }

        // Custom Fields
        foreach ( $submission_form_custom_fields as $field_key => $field_args ) {
            if ( empty( $field_args['searchable'] ) ) { continue; }
            $search_form_fields_advanced_items[ $field_key ] = [
                "required" => false,
                "widget_group" => "available_widgets",
                "widget_name" => $field_args['type']
            ];
        }

        $search_form_all_fields = array_merge( $search_form_fields_basic_items, $search_form_fields_advanced_items );
        $search_form_fields = [
            "fields" => $search_form_all_fields,
            "groups" => [
                [
                    "label"     => "Basic",
                    "lock"      => true,
                    "draggable" => false,
                    "fields"    => array_keys( $search_form_fields_basic_items )
                ],
                [
                    "label"     => "Advanced",
                    "lock"      => true,
                    "draggable" => false,
                    "fields"    => array_keys( $search_form_fields_advanced_items )
                ]
            ]
        ];

        return $search_form_fields;
    }

    // get_single_listing_header_data
    public function get_single_listing_header_data() {
        // Single Listing
        // Quick Actions Items
        $quick_actions = [];
        if ( '1' == get_directorist_option( 'enable_favourite', true ) ) {
            $quick_actions[] = [
                "type" => "button",
                "label" => "Bookmark",
                "hook" => "atbdp_single_listings_title",
                "key" => "bookmark",
                "id" => "bookmark",
                "options" => [
                    "icon" => "fa fa-home"
                ]
            ];
        } 
        if ( '1' == get_directorist_option( 'enable_report_abuse', true ) ) {
            $quick_actions[] = [
                "type" => "badge",
                "label" => "Share",
                "hook" => "atbdp_single_listings_title",
                "key" => "share",
                "id" => "share",
                "options" => [
                    "icon" => "fa fa-home"
                ]
            ];
        }
        if ( '1' == get_directorist_option( 'enable_social_share', true ) ) {
            $quick_actions[] = [
                "type" => "badge",
                "label" => "Report",
                "hook" => "atbdp_single_listings_title",
                "key" => "report",
                "id" => "report",
                "options" => [
                    "icon" => "fa fa-home"
                ]
            ];
        } 


        // Thumbnail Items
        $thumbnail = [];
        if ( '1' == get_directorist_option( 'dsiplay_slider_single_page', true ) ) {
            $thumbnail[] = [
                "type" => "thumbnail",
                "label" => "Select Files",
                "hook" => "atbdp_single_listings_slider",
                "key" => "listing_slider",
                "id" => "listing_slider",
                "options" => [
                    "footer_thumbail" => get_directorist_option( 'dsiplay_thumbnail_img', true )
                ]
            ];
        }

        // Quick Info Items
        $quick_info = [];
        if ( '0' == get_directorist_option( 'disable_list_price', true ) ) {
            $quick_info[] = [
                "type" => "badge",
                "label" => "Listings Price",
                "hook" => "atbdp_single_listings_price",
                "key" => "price",
                "id" => "price"
            ];
        }
        
        $quick_info[] = [
            "type" => "ratings-count",
            "label" => "Listings Ratings",
            "hook" => "atbdp_single_listings_ratings_count",
            "key" => "ratings_count",
            "id" => "ratings_count"
        ];

        $quick_info[] = [
            "type" => "reviews",
            "label" => "Listings Reviews",
            "hook" => "atbdp_single_listings_reviews",
            "key" => "reviews",
            "id" => "reviews"
        ];

        $quick_info[] = [
            "type" => "badge",
            "label" => "Badges",
            "hook" => "atbdp_single_listings_badges",
            "key" => "badges",
            "id" => "badges",
            "options" => [
                "new_badge" => get_directorist_option( 'display_new_badge_cart', true ),
                "popular_badge" => get_directorist_option( 'display_popular_badge_cart', true )
            ]
        ];
        
        $quick_info[] = [
            "type" => "badge",
            "label" => "Category",
            "hook" => "atbdp_single_listing_category",
            "key" => "category",
            "id" => "category"
        ];

        /* $quick_info[] = [
            "type" => "badge",
            "label" => "Location",
            "hook" => "atbdp_single_listing_location",
            "key" => "location",
            "id" => "location"
        ]; */
        
        
        $single_listing_header = [
            "listings_header" => [
                "quick_actions" => $quick_actions,
                "thumbnail"     => $thumbnail,
                "quick_info"    => $quick_info
            ],

            "options" => [
                "general" => [
                    "back" => [
                        "label" => get_directorist_option( 'display_back_link', true )
                    ],
                    "section_title" => [
                        "label" => get_directorist_option( 'listing_details_text', 'Listing Details' )
                    ]
                ],
                "content_settings" => [
                    "listing_title" => [
                        "enable_title" => true,
                        "enable_tagline" => true
                    ],
                    "listing_description" => [
                        "enable" => true
                    ]
                ]
            ]
        ];

        return $single_listing_header;
    }

    // get_listings_card_grid_view_data
    public function get_listings_card_grid_view_data( array $args = [] ) {
        $default = [ 'listings_card_wedgets' => [] ];
        $args    = array_merge( $default, $args );

        $listings_card_wedgets = $args[ 'listings_card_wedgets' ];

        // Listings Card Grid View - thumbnail_top_right
        $listings_card_grid_view_thumbnail_top_right = [];
        if ( get_directorist_option( 'display_mark_as_fav', true ) ) {
            $listings_card_grid_view_thumbnail_top_right[] = $listings_card_wedgets['favorite_badge'];
        }

        // Listings Card Grid View - thumbnail_top_left
        $listings_card_grid_view_thumbnail_top_left = [];
        if ( get_directorist_option( 'display_feature_badge_cart', true ) ) {
            $listings_card_grid_view_thumbnail_top_left[] = $listings_card_wedgets['featured_badge'];
        }

        // Listings Card Grid View - thumbnail_bottom_left
        $listings_card_grid_view_thumbnail_bottom_left = [];
        if ( get_directorist_option( 'display_new_badge_cart', true ) ) {
            $listings_card_grid_view_thumbnail_bottom_left[] = $listings_card_wedgets['new_badge'];
        }

        if ( get_directorist_option( 'display_popular_badge_cart', true ) ) {
            $listings_card_grid_view_thumbnail_bottom_left[] = $listings_card_wedgets['popular_badge'];
        }

        // listings_card_grid_view_thumbnail_avatar
        $listings_card_grid_view_thumbnail_avatar = [];
        if ( get_directorist_option( 'display_author_image', true ) ) {
            $user_avatar = $listings_card_wedgets['user_avatar'];
            unset( $user_avatar['options'] );
            $listings_card_grid_view_thumbnail_avatar[] = $user_avatar;
        }
        
        // listings_card_grid_view_body_top
        $listings_card_grid_view_body_top = [];
        if ( get_directorist_option( 'display_title', true ) ) {
            $listings_card_grid_view_body_top[] = $listings_card_wedgets['listing_title'];
        }

        // listings_card_grid_view_body_bottom
        $listings_card_grid_view_body_bottom = [];
        if ( get_directorist_option( 'display_contact_info', true ) ) {
            $listings_card_grid_view_body_bottom[] = $listings_card_wedgets['listings_location'];
        }

        if ( get_directorist_option( 'display_publish_date', true ) ) {
            $listings_card_grid_view_body_bottom[] = $listings_card_wedgets['posted_date'];
        }
        
        if ( get_directorist_option( 'display_contact_info', true ) ) {
            $listings_card_grid_view_body_bottom[] = $listings_card_wedgets['phone'];
        }

        if ( get_directorist_option( 'display_web_link', true ) ) {
            $listings_card_grid_view_body_bottom[] = $listings_card_wedgets['website'];
        }

        // listings_card_grid_view_footer_right
        $listings_card_grid_view_footer_right = [];
        if ( get_directorist_option( 'display_view_count', true ) ) {
            $listings_card_grid_view_footer_right[] = $listings_card_wedgets['view_count'];
        }

        // listings_card_grid_view_footer_left
        $listings_card_grid_view_footer_left = [];
        if ( get_directorist_option( 'display_category', true ) ) {
            $listings_card_grid_view_footer_left[] = $listings_card_wedgets['category'];
        }

        $listings_card_grid_view  = [
            "thumbnail"=> [ 
                "top_right"    => $listings_card_grid_view_thumbnail_top_right,
                "top_left"     => $listings_card_grid_view_thumbnail_top_left,
                "bottom_right" => [],
                "bottom_left"  => $listings_card_grid_view_thumbnail_bottom_left,
                "avatar"       => $listings_card_grid_view_thumbnail_avatar,
                "body" => [
                    "top"    => $listings_card_grid_view_body_top,
                    "bottom" => $listings_card_grid_view_body_bottom
                ] 
            ],
            "footer"=> [
                "right" => $listings_card_grid_view_footer_right,
                "left"  => $listings_card_grid_view_footer_left
            ]
        ];

        return $listings_card_grid_view;
    }

    // get_listings_card_list_view_data
    public function get_listings_card_list_view_data( array $args = [] ) {
        $default = [ 'listings_card_wedgets' => [] ];
        $args    = array_merge( $default, $args );

        $listings_card_wedgets = $args[ 'listings_card_wedgets' ];

        // $listings_card_list_view_thumbnail_top_right
        $listings_card_list_view_thumbnail_top_right = [];
        if ( get_directorist_option( 'display_feature_badge_cart', true ) ) {
            $listings_card_list_view_thumbnail_top_right[] = $listings_card_wedgets['featured_badge'];
        }

        if ( get_directorist_option( 'display_popular_badge_cart', true ) ) {
            $listings_card_list_view_thumbnail_top_right[] = $listings_card_wedgets['popular_badge'];
        }

        // $listings_card_list_view_body_top
        $listings_card_list_view_body_top = [];
        if ( get_directorist_option( 'display_title', true ) ) {
            $listings_card_list_view_body_top[] = $listings_card_wedgets['listing_title'];
        }

        if ( get_directorist_option( 'display_new_badge_cart', true ) ) {
            $listings_card_list_view_body_top[] = $listings_card_wedgets['new_badge'];
        }

        $listings_card_list_view_body_right = [];
        if ( get_directorist_option( 'display_mark_as_fav', true ) ) {
            $listings_card_list_view_body_right[] = $listings_card_wedgets['favorite_badge'];
        }
        
        // $listings_card_list_view_body_bottom
        $listings_card_list_view_body_bottom = [];
        if ( get_directorist_option( 'display_contact_info', true ) ) {
            $listings_card_list_view_body_bottom[] = $listings_card_wedgets['listings_location'];
        }

        if ( get_directorist_option( 'display_publish_date', true ) ) {
            $listings_card_list_view_body_bottom[] = $listings_card_wedgets['posted_date'];
        }

        if ( get_directorist_option( 'display_contact_info', true ) ) {
            $listings_card_list_view_body_bottom[] = $listings_card_wedgets['phone'];
        }

        if ( get_directorist_option( 'display_web_link', true ) ) {
            $listings_card_list_view_body_bottom[] = $listings_card_wedgets['website'];
        }
        
        // listings_card_list_view_footer_right
        $listings_card_list_view_footer_right = [];
        if ( get_directorist_option( 'display_view_count', true ) ) {
            $listings_card_list_view_footer_right[] = $listings_card_wedgets['view_count'];
        }

        if ( get_directorist_option( 'display_author_image', true ) ) {
            $listings_card_list_view_footer_right[] = $listings_card_wedgets['user_avatar'];
        }

        $listings_card_list_view_footer_left = [];
        if ( get_directorist_option( 'display_category', true ) ) {
            $listings_card_list_view_footer_left[] = $listings_card_wedgets['category'];
        }

        $listings_card_list_view = [
            "thumbnail"=> [
                "top_right" => $listings_card_list_view_thumbnail_top_right
            ],
            "body"=> [
                "top"    => $listings_card_list_view_body_top,
                "right"  => $listings_card_list_view_body_right,
                "bottom" => $listings_card_list_view_body_bottom
            ],
            "footer" => [
                "right" => $listings_card_list_view_footer_right,
                "left"  => $listings_card_list_view_footer_left
            ]
        ];

        return $listings_card_list_view;
    }

    // get_listings_card_wedgets_data
    public function get_listings_card_wedgets_data() {
        $listings_card_wedgets = [
            'favorite_badge' => [
                "type"    => "badge",
                "label"   => "Favorite",
                "hook"    => "atbdp_favorite_badge",
                "key"     => "favorite_badge",
                "id"      => "favorite_badge",
                "options" => [
                    "icon" => "fa fa-heart"
                ]
            ],
            'featured_badge' => [
                "type"    => "badge",
                "label"   => get_directorist_option( 'feature_badge_text', __('Fetured', 'directorist') ),
                "hook"    => "atbdp_featured_badge",
                "key"     => "featured_badge",
                "id"      => "featured_badge",
                "options" => [
                    "label" => get_directorist_option( 'feature_badge_text', __('Fetured', 'directorist') )
                ]
            ],
            'new_badge' => [
                "type"    => "badge",
                "label"   => get_directorist_option( 'new_badge_text', __('New', 'directorist') ),
                "hook"    => "atbdp_new_badge",
                "key"     => "new_badge",
                "id"      => "new_badge",
                "options" => [
                    "label"              => get_directorist_option( 'new_badge_text', __('New', 'directorist') ),
                    "new_badge_duration" => get_directorist_option( 'new_listing_day', __('New', 'directorist') )
                ]
            ],
            'popular_badge' => [
                "type"    => "badge",
                "label"   => get_directorist_option( 'popular_badge_text', __('Popular', 'directorist') ),
                "hook"    => "atbdp_popular_badge",
                "key"     => "popular_badge",
                "id"      => "popular_badge",
                "options" => [
                    "label"               => get_directorist_option( 'popular_badge_text', __('Popular', 'directorist') ),
                    "listing_popular_by"  => get_directorist_option( 'listing_popular_by', 'view_count' ),
                    "views_for_popular"   => get_directorist_option( 'views_for_popular', 5 ),
                    "count_loggedin_user" => get_directorist_option( 'count_loggedin_user', false )
                ]
            ],
            'user_avatar' => [
                "type"    => "avatar",
                "label"   => "User Avatar",
                "hook"    => "atbdp_user_avatar",
                "key"     => "user_avatar",
                "id"      => "user_avatar",
                "options" => [
                    "align"=> "right"
                ]
            ],
            'listing_title' => [
                "type"  => "title",
                "label" => "Title",
                "hook"  => "atbdp_listing_title",
                "key"   => "listing_title",
                "id"    => "listing_title",
            ],
            'listings_location' => [
                "type"    => "list-item",
                "label"   => "Listings Location",
                "hook"    => "atbdp_listings_location",
                "key"     => "listings_location",
                "id"      => "listings_location",
                "options" => [
                    "icon"=> "la la-map-marker"
                ]
            ],
            'phone' => [
                "type"    => "list-item",
                "hook"    => "atbdp_listings_phone",
                "id"      => "_phone",
                "key"     => "_phone",
                "label"   => "Listings Phone",
                "options" => [
                    "icon" => "la la-phone" 
                ],

            ],
            'website' => [
                "type"    => "list-item",
                "hook"    => "atbdp_listings_website",
                "id"      => "_website",
                "key"     => "_website",
                "label"   => "Listings Website",
                "options" => [
                    "icon" => "la la-globe"
                ],

            ],
            'posted_date' => [
                "type"    => "list-item",
                "label"   => "Posted Date",
                "hook"    => "atbdp_listings_posted_date",
                "key"     => "posted_date",
                "id"      => "posted_date",
                "options" => [
                    "icon"      => "la la-clock-o",
                    "date_type" => "post_date"
                ]
            ],
            'view_count' => [
                "type"    => "view-count",
                "label"   => "View Count",
                "hook"    => "atbdp_view_count",
                "key"     => "view_count",
                "id"      => "view_count",
                "options" => [
                    "icon"=> "fa fa-heart"
                ]
            ],
            'category' => [
                "type"    => "category",
                "label"   => "Category",
                "hook"    => "atbdp_category",
                "key"     => "category",
                "id"      => "category",
                "options" => [
                    "icon" => "fa fa-folder"
                ]
            ],

        ];

        return $listings_card_wedgets;
    }

    // get_terms_label
    public function get_terms_label() {
        $terms_label_a = get_directorist_option( 'terms_label', true );
        $terms_label_b = get_directorist_option( 'terms_label_link', true );
        $terms_label = "{$terms_label_a} %{$terms_label_b}%";

        return $terms_label;
    }

    // get_privacy_label
    public function get_privacy_label() {
        $privacy_label_a = get_directorist_option( 'privacy_label', true );
        $privacy_label_b = get_directorist_option( 'privacy_label_link', true );
        $privacy_label   = "{$privacy_label_a} %{$privacy_label_b}%";

        return $privacy_label;
    }
    
    // get_preview_image
    public function get_preview_image() {
        $preview_image_url = get_directorist_option( 'default_preview_image', ATBDP_PUBLIC_ASSETS . 'images/grid.jpg' );
        $preview_image     = [ 'id' => null, 'url' => $preview_image_url ];

        return $preview_image;
    }



    // get_old_custom_fields
    public function get_old_custom_fields()
    {
        $fields = [];
        $old_fields = atbdp_get_custom_field_ids( '', true );

        foreach ($old_fields as $old_field_id) {
            $field_type     = get_post_meta($old_field_id, 'type', true);
            $accepted_types = [ 'text', 'number', 'date', 'color', 'time', 'radio', 'checkbox', 'select', 'textarea', 'url', 'file' ];
            
            if ( ! in_array( $field_type, $accepted_types ) ) { continue; }
            // $get_post_meta = get_post_meta($old_field_id);
    
            $required      = get_post_meta($old_field_id, 'required', true);
            $admin_use     = get_post_meta($old_field_id, 'admin_use', true);
            $category_pass = get_post_meta($old_field_id, 'category_pass', true);
            $searchable    = get_post_meta($old_field_id, 'searchable', true);
            $field_data    = [];
            
            // Common Data
            $field_data['type']         = $field_type;
            $field_data['label']        = get_the_title($old_field_id);
            $field_data['field_key']    = $old_field_id;
            $field_data['placeholder']  = '';
            $field_data['description']  = get_post_meta($old_field_id, 'instructions', true);
            $field_data['required']     = ( $required == 1 ) ? true : false;

            $field_data['only_for_admin'] = ( $admin_use == 1 ) ? true : false;
            $field_data['assign_to']      = get_post_meta($old_field_id, 'associate', true);
            $field_data['category']       = ( is_numeric( $category_pass ) ) ? $category_pass : '';
            $field_data['searchable']     = ( $searchable == 1 ) ? true : false;

            $field_data['widget_group'] = 'custom';
            $field_data['widget_name']  = $field_type;
            
            // field group
            $field_group = [ 'radio', 'checkbox', 'select' ];
            if ( in_array( $field_type, $field_group ) ) {
                $choices = get_post_meta($old_field_id, 'choices', true);
                $field_data['options'] = $this->decode_custom_field_option_string( $choices );
            }

            if ( ('textarea' === $field_type) ) {
                $field_data['rows'] = get_post_meta($old_field_id, 'rows', true);
            }

            if ( ('url' === $field_type) ) {
                $field_data['target'] = get_post_meta($old_field_id, 'target', true);
            }

            if ( ('file' === $field_type) ) {
                $field_data['file_type'] = get_post_meta($old_field_id, 'file_type', true);
                $field_data['file_size'] = get_post_meta($old_field_id, 'file_size', true);
            }

            $fields[ $field_type . '_' . $old_field_id ] = $field_data;
        }

        return $fields;
    }

    // decode_custom_field_option_string
    public function decode_custom_field_option_string( string $string = '' ) {
        $choices = ( ! empty( $string ) ) ? explode( "\n", $string ) : [];
        $options = [];
        
        if ( count( $choices ) ) {
            foreach ( $choices as $option) {
                $value_match = [];
                $label_match = [];

                preg_match( '/(.+):/', $option, $value_match );
                preg_match( '/:(.+)/', $option, $label_match );
                
                if ( empty( $value_match[1] ) && empty( $label_match[1] ) ) {
                    $options[] = [
                        'value' => $option,
                        'label' => $option,
                    ];
                    continue;
                }

                $options[] = [
                    'value' => $value_match[1],
                    'label' => $label_match[1],
                ];
            }
        }

        return $options;
    }

}

endif;