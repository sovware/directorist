<?php

if ( ! class_exists('ATBDP_Listing_Type_Manager') ) {
    class ATBDP_Listing_Type_Manager
    {
        public $fields            = [];
        public $layouts           = [];
        public $config            = [];
        public $default_form      = [];
        public $old_custom_fields = [];
        public $cetagory_options  = [];

        // run
        public function run()
        {
            add_filter( 'cptm_fields_before_update', [$this, 'cptm_fields_before_update'], 20, 1 );

            add_action( 'admin_enqueue_scripts', [$this, 'register_scripts'] );
            add_action( 'init', [$this, 'register_terms'] );
            add_action( 'init', [$this, 'initial_setup'] );
            add_action( 'admin_menu', [$this, 'add_menu_pages'] );
            add_action( 'admin_post_delete_listing_type', [$this, 'handle_delete_listing_type_request'] );

            add_action( 'wp_ajax_save_post_type_data', [ $this, 'save_post_type_data' ] );
            add_action( 'wp_ajax_save_imported_post_type_data', [ $this, 'save_imported_post_type_data' ] );

            add_action( '_template_redirect', function() {
                $listings = new WP_Query([
                    'post_type' => ATBDP_POST_TYPE,
                    'status' => 'publish',
                    'per_page' => 10,
                ]);

                var_dump( $listings );
            });
        }

        // get_cetagory_options
        public function get_cetagory_options() {
            $terms = get_terms( array(
                'taxonomy'   => ATBDP_CATEGORY,
                'hide_empty' => false,
            ));

            $options = [];

            if ( is_wp_error( $terms ) ) { return $options; }
            if ( ! count( $terms ) ) { return $options; }

            foreach( $terms as $term ) {
                $options[] = [ 
                    'id'    => $term->term_id,
                    'value' => $term->term_id,
                    'label' => $term->name,
                ];
            }

            return $options; 
        }
        
        // get_assign_to_field
        public function get_assign_to_field( array $args = [] ) {
            $default = [
                'type' => 'radio',
                'label' => __('Assign to', 'directorist'),
                'value' => 'form',
                'options' => [
                    [
                        'label' => __('Form', 'directorist'),
                        'value' => 'form',
                    ],
                    [
                        'label' => __('Category', 'directorist'),
                        'value' => 'category',
                    ],
                ],
            ];

            return array_merge( $default, $args );
        }

        // get_category_select_field
        public function get_category_select_field( array $args = [] ) {
            $default = [
                'type'    => 'select',
                'label'   => __('Select Category', 'directorist'),
                'value'   => '',
                'options' => $this->cetagory_options,
            ];

            return array_merge( $default, $args );
        }

        // initial_setup
        public function initial_setup() {
            // $this->import_default_directory();
            // return;

            // $this->migrate_to_multidirectory();
            // return;

            // update_option( 'atbdp_has_multidirectory', false );

            $has_listings       = false;
            $has_custom_fields  = false;
            $migrated           = get_option( 'atbdp_migrated_to_multidirectory', false );
            $has_multidirectory = get_option( 'atbdp_has_multidirectory', false );

            $get_listings = new WP_Query([
                'post_type' => ATBDP_POST_TYPE,
                'posts_per_page' => 1,
            ]);

            $get_custom_fields = new WP_Query([
                'post_type' => ATBDP_CUSTOM_FIELD_POST_TYPE,
                'posts_per_page' => 1,
            ]);

            $has_listings        = $get_listings->post_count;
            $has_custom_fields   = $get_custom_fields->post_count;
            $need_migration      = ( ! $has_multidirectory && ( $has_listings || $has_custom_fields ) ) ? true : false;
            $need_import_default = ( ! $has_multidirectory && ! ( $has_listings || $has_custom_fields ) ) ? true : false;


            /* echo '<pre>';
            var_dump([
                'has_listings'       => $has_listings,
                'has_custom_fields'  => $has_custom_fields,
                'has_multidirectory' => $has_multidirectory,
                'migrated'           => $migrated,
                'need_migration'     => $need_migration,
            ]);
            echo '</pre>';
            die; */

            if ( $need_migration ) {
                $this->migrate_to_multidirectory();
                return;
            }

            if ( $need_import_default ) {
                $this->import_default_directory();
            }
            
        }

        // migrate_to_multidirectory
        public function migrate_to_multidirectory() {
            // return;
            /* var_dump( 'migrate_to_multidirectory' );
            die;
            return; */
            
            $preview_image_url = get_directorist_option( 'default_preview_image', ATBDP_PUBLIC_ASSETS . 'images/grid.jpg' );
            $preview_image     = [ 'id' => null, 'url' => $preview_image_url ];


            // Submission Form Fields
            $submission_form_preset_fields = [
                "title" => [
                    "widget_group" => "preset",
                    "widget_name"  => "title",
                    "type"         => "text",
                    "field_key"    => "listing_title",
                    "required"     => get_directorist_option( 'require_title', true ),
                    "label"        => get_directorist_option( 'title_label', "Title" ),
                    "placeholder"  => get_directorist_option( 'title_placeholder', "Enter a title" ),
                ],
                "description" => [
                    "type"           => "wp_editor",
                    "field_key"      => "listing_content",
                    "label"          => get_directorist_option( 'long_details_label', 'Long Details' ),
                    "placeholder"    => "",
                    "required"       => get_directorist_option( 'require_long_details', false ),
                    "only_for_admin" => get_directorist_option( 'display_desc_for', false ),
                    "widget_group"   => "preset",
                    "widget_name"    => "description"
                ],
                "pricing" => [
                    "pricing_type"           => "both",
                    "price_range_label"      => get_directorist_option( 'price_range_label', 'Select Price Range' ),
                    "price_range_options"    => "cheap",
                    "price_unit_field_type"  => "number",
                    "price_unit_field_label" => get_directorist_option( 'price_label', 'Price' ),
                    "widget_group"           => "preset",
                    "widget_name"            => "pricing",
                    "label"                  => get_directorist_option( 'pricing_label', 'Pricing' ),
                ],
                "zip" => [
                    "type"           => "text",
                    "field_key"      => "zip",
                    "label"          => get_directorist_option( 'zip_label', 'Zip/Post Code' ),
                    "placeholder"    => get_directorist_option( 'zip_placeholder', 'Enter Zip/Post Code' ),
                    "required"       => get_directorist_option( 'require_zip', false ),
                    "only_for_admin" => get_directorist_option( 'display_zip_for', false ),
                    "widget_group"   => "preset",
                    "widget_name"    => "zip",
                ],
                "phone" => [
                    "type"           => "tel",
                    "field_key"      => "phone",
                    "label"          => get_directorist_option( 'phone_label', 'Phone' ),
                    "placeholder"    => get_directorist_option( 'phone_placeholder', 'Phone Number' ),
                    "required"       => get_directorist_option( 'require_phone_number', false ),
                    "only_for_admin" => get_directorist_option( 'display_phone_for', false ),
                    "widget_group"   => "preset",
                    "widget_name"    => "phone"
                ],
                "phone2" => [
                    "type"           => "tel",
                    "field_key"      => "phone2",
                    "label"          => get_directorist_option( 'phone_label2', 'Phone 2' ),
                    "placeholder"    => get_directorist_option( 'phone2_placeholder', 'Phone Number' ),
                    "required"       => get_directorist_option( 'require_phone2_number', false ),
                    "only_for_admin" => get_directorist_option( 'display_phone2_for', false ),
                    "widget_group"   => "preset",
                    "widget_name"    => "phone2"
                ],
                "email" => [
                    "type"           => "email",
                    "field_key"      => "email",
                    "label"          => get_directorist_option( 'email_label', 'Email' ),
                    "placeholder"    => get_directorist_option( 'email_placeholder', 'Enter Email' ),
                    "required"       => get_directorist_option( 'require_email', false ),
                    "only_for_admin" => get_directorist_option( 'display_email_for', false ),
                    "widget_group"   => "preset",
                    "widget_name"    => "email"
                ],
                "tag" => [
                    "type"           => "multiple",
                    "field_key"      => "tax_input[at_biz_dir-tags][]",
                    "label"          => get_directorist_option( 'tag_label', 'Tag' ),
                    "required"       => get_directorist_option( 'require_tags', false ),
                    "allow_new"      => get_directorist_option( 'create_new_tag', true ),
                    "only_for_admin" => get_directorist_option( 'display_tag_for', false ),
                    "widget_group"   => "preset",
                    "widget_name"    => "tag"
                ],
                "website" => [
                    "type"           => "text",
                    "field_key"      => "website",
                    "label"          => get_directorist_option( 'website_label', 'Website' ),
                    "placeholder"    => get_directorist_option( 'website_placeholder', 'Listing Website eg. http://example.com' ),
                    "required"       => get_directorist_option( 'require_website', false ),
                    "only_for_admin" => get_directorist_option( 'display_website_for', false ),
                    "plans"          => [],
                    "widget_group"   => "preset",
                    "widget_name"    => "website"
                ],
                "social_info" => [
                    "type"           => "add_new",
                    "field_key"      => "social",
                    "label"          => get_directorist_option( 'social_label', 'Social Information' ),
                    "required"       => get_directorist_option( 'require_social_info', false ),
                    "only_for_admin" => get_directorist_option( 'display_social_info_for', false ),
                    "widget_group"   => "preset",
                    "widget_name"    => "social_info"
                ],
                "fax" => [
                    "type"           => "number",
                    "field_key"      => "fax",
                    "label"          => get_directorist_option( 'fax_label', 'Fax' ),
                    "placeholder"    => get_directorist_option( 'fax_placeholder', 'Enter Fax' ),
                    "required"       => get_directorist_option( 'require_fax', false ),
                    "only_for_admin" => get_directorist_option( 'display_fax_for', false ),
                    "widget_group"   => "preset",
                    "widget_name"    => "fax"
                ],
                "address" => [
                    "type"           => "text",
                    "field_key"      => "address",
                    "label"          => get_directorist_option( 'address_label', 'Address' ),
                    "placeholder"    => get_directorist_option( 'address_placeholder', 'Listing address eg. New York, USA' ),
                    "required"       => get_directorist_option( 'require_address', false ),
                    "only_for_admin" => get_directorist_option( 'display_address_for', false ),
                    "widget_group"   => "preset",
                    "widget_name"    => "address"
                ],
                "map" => [
                    "type"           => "map",
                    "field_key"      => "map",
                    "label"          => "Map",
                    "required"       => get_directorist_option( 'display_map_for', false ),
                    "only_for_admin" => false,
                    "widget_group"   => "preset",
                    "widget_name"    => "map"
                ],
                "view_count" => [
                    "type"           => "number",
                    "field_key"      => "atbdp_post_views_count",
                    "label"          => get_directorist_option( 'views_count_label', 'Views Count' ),
                    "placeholder"    => "",
                    "required"       => false,
                    "only_for_admin" => true,
                    "widget_group"   => "preset",
                    "widget_name"    => "view_count"
                ],
                "location" => [
                    "type"           => "multiple",
                    "field_key"      => "tax_input[at_biz_dir-location][]",
                    "label"          => get_directorist_option( 'location_label', 'Location' ),
                    "required"       => get_directorist_option( 'require_location', false ),
                    "only_for_admin" => get_directorist_option( 'display_loc_for', false ),
                    "widget_group"   => "preset",
                    "widget_name"    => "location"
                ],
                "category" => [
                    "type"           => "multiple",
                    "field_key"      => "admin_category_select[]",
                    "label"          => get_directorist_option( 'category_label', 'Select Category' ),
                    "required"       => get_directorist_option( 'require_category', false ),
                    "only_for_admin" => false,
                    "widget_group"   => "preset",
                    "widget_name"    => "category"
                ],
                "image_upload" => [
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
                ],
                "video" => [
                    "type"           => "text",
                    "field_key"      => "videourl",
                    "label"          => get_directorist_option( 'video_label', 'Video Url' ),
                    "placeholder"    => get_directorist_option( 'video_placeholder', 'Only YouTube & Vimeo URLs.' ),
                    "required"       => get_directorist_option( 'require_video', false ),
                    "only_for_admin" => get_directorist_option( 'display_video_for', false ),
                    "widget_group"   => "preset",
                    "widget_name"    => "video"
                ],
            ];

            $submission_form_custom_fields = $this->get_old_custom_fields();
            $submission_form_fields = array_merge( $submission_form_preset_fields, $submission_form_custom_fields );
            $submission_form_groups = [
                [
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
                ],
                [
                    "label" => "Features",
                    "fields" => array_keys( $submission_form_custom_fields ),
                ],
                [
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
                ],
                [
                    "label" => "Map",
                    "fields" => [
                        "address",
                        "map"
                    ],
                ],
                [
                    "label" => "Gallery",
                    "fields" => [
                        "image_upload",
                        "video",
                    ],
                ]
            ];

            $submission_form_fields = [
                "fields" => $submission_form_fields,
                "groups" => $submission_form_groups
            ];
            

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
                        "label" => "Basic",
                        "lock" => true,
                        "draggable" => false,
                        "fields" => array_keys( $search_form_fields_basic_items )
                    ],
                    [
                        "label" => "Advanced",
                        "lock" => true,
                        "draggable" => false,
                        "fields" => array_keys( $search_form_fields_advanced_items )
                    ]
                ]
            ];
            

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
            
            
            $single_listing_header    = [
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

            // Listings Card
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
            // Listings Card Grid View
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

            $fields = apply_filters( 'atbdp_multidirectory_migration_fields', [
                "name"                     => "General",
                "icon"                     => "fa fa-home",
                "singular_name"            => "listing",
                "plural_name"              => "listings",
                "permalink"                => "listing",
                "preview_image"            => $preview_image,
                "default_expiration"       => get_directorist_option( 'listing_expire_in_days', 365 ),
                "new_listing_status"       => get_directorist_option( 'new_listing_status', 'pending' ),
                "edit_listing_status"      => get_directorist_option( 'edit_listing_status', 'pending' ),
                "submit_button_label"      => get_directorist_option( 'submit_label', 'Save & Preview' ),
                "preview_mode"             => get_directorist_option( 'preview_enable', true ),
                "submission_form_fields"   => $submission_form_fields,
                "single_listings_contents" => $single_listings_contents,
                "enable_similar_listings"  => get_directorist_option( 'enable_rel_listing', true ),
                "similar_listings_logics"  => get_directorist_option( 'rel_listings_logic', 'OR' ),
                "search_form_fields"       => $search_form_fields,
                "single_listing_header"    => $single_listing_header,
                "listings_card_grid_view"  => $listings_card_grid_view,
                "listings_card_list_view"  => $listings_card_list_view,
                "similar_listings_number_of_listings_to_show" => get_directorist_option( 'rel_listing_num', 10 ),
            ]);
            

            /* echo '<pre>';
            print_r( $fields );
            echo '</pre>';
            die; */
            

            $add_directory = $this->add_directory([
                'directory_name' => 'General Migration',
                'fields_value'   => $fields,
            ]);
            
            if ( $add_directory['status']['success'] ) {
                update_option( 'atbdp_has_multidirectory', true );
                // update_option( 'atbdp_migrated_to_multidirectory', true );
                update_term_meta( $add_directory['term_id'], '_default', true );
            }
        }
        
        // import_default_directory
        public function import_default_directory() {
            // var_dump( 'import_default_directory' );

            $file = trailingslashit( dirname( ATBDP_FILE ) )  . 'admin/assets/simple-data/directory/directory.json';
            if ( ! file_exists( $file ) ) { return; }

            $file_contents = file_get_contents( $file );

            $add_directory = $this->add_directory([
                'directory_name' => 'General',
                'fields_value'   => $file_contents,
                'is_json'        => true
            ]);
        
            // json_decode( $file_contents );
            // var_dump( json_decode( $file_contents ) );
            // die;

            if ( $add_directory['status']['success'] ) {
                update_option( 'atbdp_has_multidirectory', true );
                update_term_meta( $add_directory['term_id'], '_default', true );
            }
        }

        public function save_imported_post_type_data() {
            $term_id        = ( ! empty( $_POST[ 'term_id' ] ) ) ? ( int ) $_POST[ 'term_id' ] : 0;
            $directory_name = ( ! empty( $_POST[ 'directory-name' ] ) ) ? $_POST[ 'directory-name' ] : '';
            $json_file      = ( ! empty( $_FILES[ 'directory-import-file' ] ) ) ? $_FILES[ 'directory-import-file' ] : '';

            // Validation
            $response = [
                'status' => [
                    'success'     => true,
                    'status_log'  => [],
                    'error_count' => 0,
                ]
            ];

            // Validate file
            if ( empty( $json_file ) ) {
                $response['status']['status_log']['file_is_missing'] = [
                    'type' => 'error',
                    'message' => 'File is missing',
                ];

                $response['status']['error_count']++;
            }

            // Validate file data
            $file_contents = file_get_contents( $json_file['tmp_name'] );
            if ( empty( $file_contents ) ) {
                $response['status']['status_log']['invalid_data'] = [
                    'type' => 'error',
                    'message' => 'The data is invalid',
                ];

                $response['status']['error_count']++;
            }
        
            // Send respone if has error
            if ( $response['status']['error_count'] ) {
                $response['status']['success'] = false;
                wp_send_json( $response , 200 );
            }

            // If dierctory name is numeric update the term instead of creating
            if ( is_numeric( $directory_name ) ) {
                $term_id = (int) $directory_name;
                $directory_name = '';
            }

            $add_directory = $this->add_directory([ 
                'term_id'        => $term_id,
                'directory_name' => $directory_name,
                'fields_value'   => $file_contents,
                'is_json'        => true
            ]);

            wp_send_json( $add_directory, 200 );
        }

        // add_directory
        public function add_directory( array $args = [] ) {
            $default = [ 
                'term_id'        => 0,
                'directory_name' => '',
                'fields_value'   => [],
                'is_json'        => false
            ];
            $args = array_merge( $default, $args );

            $has_term_id = false;
            if ( ! empty( $args['term_id'] ) ) {
                $has_term_id = true;
            }

            if ( $has_term_id && ! is_numeric( $args['term_id'] ) ) {
                $has_term_id = false;
            }

            if ( $has_term_id && $args['term_id'] < 1 ) {
                $has_term_id = false;
            }
            
            $create_directory = [ 'term_id' => 0 ];

            if ( ! $has_term_id ) {
                $create_directory = $this->create_directory([ 
                    'directory_name' => $args['directory_name']
                ]);

                if ( ! $create_directory['status']['success'] ) {
                    return $create_directory;
                }
            }
            
            $update_directory = $this->update_directory([
                'term_id'        => ( ! $has_term_id ) ? $create_directory['term_id'] : $args['term_id'],
                'directory_name' => $args['directory_name'],
                'fields_value'   => $args['fields_value'],
                'is_json'        => $args['is_json'],
            ]);

            return $update_directory;
        }

        // create_directory
        public function create_directory( array $args = [] ) {
            $default = [ 'directory_name' => '' ];
            $args    = array_merge( $default, $args );

            $response = [
                'status' => [
                    'success'     => true,
                    'status_log'  => [],
                    'error_count' => 0,
                ]
            ];

            // Validate name
            if ( empty( $args['directory_name'] ) ) {
                $response['status']['status_log']['name_is_missing'] = [
                    'type'    => 'error',
                    'message' => 'Name is missing',
                ];

                $response['status']['error_count']++;
            }

            // Validate term name
            if ( ! empty( $args['directory_name'] ) && term_exists( $args['directory_name'], 'atbdp_listing_types' ) ) {
                $response['status']['status_log']['term_exists'] = [
                    'type'    => 'error',
                    'message' => 'The name already exists',
                ];

                $response['status']['error_count']++;
            }

            // Return status
            if ( $response['status']['error_count'] ) {
                $response['status']['success'] = false;
                return $response;
            }

            // Create the directory
            $term = wp_insert_term( $args['directory_name'], 'atbdp_listing_types');
            
            if ( is_wp_error( $term ) ) {
                $response['status']['status_log']['term_exists'] = [
                    'type'    => 'error',
                    'message' => 'The name already exists',
                ];

                $response['status']['error_count']++;
            }

            
            if ( $response['status']['error_count'] ) {
                $response['status']['success'] = false;
                return $response;
            }

            $response['term_id'] = $term['term_id'];
            $response['status']['status_log']['term_created'] = [
                'type'    => 'success',
                'message' => 'The directory has been created successfuly',
            ];

            return $response;
        }

        // update_directory
        public function update_directory( array $args = [] ) {
            $default = [ 
                'directory_name' => '',
                'term_id'        => 0,
                'fields_value'   => [],
                'is_json'        => false
            ];
            $args = array_merge( $default, $args );

            // return [ 'status' => true, 'mode' => 'debug' ];

            $response = [
                'status' => [
                    'success'     => true,
                    'status_log'  => [],
                    'error_count' => 0,
                ]
            ];

            $response['term_id'] = $args['term_id'];
            
            // Validation
            if ( $args['is_json'] ) {
                $args['fields_value'] = json_decode( $args['fields_value'], true );
            }

            // Validate data
            $has_invalid_data = false;

            if ( is_null( $args['fields_value'] ) ) {
                $has_invalid_data = true;
            }

            if ( 'array' !== gettype( $args['fields_value'] ) ) {
                $has_invalid_data = true;
            }

            if ( $has_invalid_data ) {
                $response['status']['status_log']['invalid_data'] = [
                    'type' => 'error',
                    'message' => 'The data is invalid',
                ];
    
                $response['status']['error_count']++;
            }

            // Validate term id
            $has_invalid_term_id = false;

            if ( empty( $args['term_id'] ) ) {
                $has_invalid_term_id = true;
            }

            if ( ! is_numeric( $args['term_id'] ) ) {
                $has_invalid_term_id = true;
            }

            $term_id = $args['term_id'];
            if ( is_numeric( $term_id ) ) {
                $args['term_id'] = ( int ) $term_id;
            }

            // Validate term id
            if ( ! term_exists( $args['term_id'], 'atbdp_listing_types' ) ) {
                $has_invalid_term_id = true;
            }

            if ( $has_invalid_term_id ) {
                $response['status']['status_log']['invalid_term_id'] = [
                    'type'    => 'error',
                    'message' => 'Invalid term ID',
                ];

                $response['status']['error_count']++;
            }

            
            $fields = apply_filters( 'cptm_fields_before_update', $args['fields_value'] );
            $directory_name = ( ! empty( $fields['name'] ) ) ? $fields['name'] : '';
            $directory_name = ( ! empty( $args['directory_name'] ) ) ? $args['directory_name'] : $directory_name;
            
            $response['fields_value']   = $fields;
            $response['directory_name'] = $args['directory_name'];

            unset( $fields['name'] );

            $term = get_term( $args['term_id'], 'atbdp_listing_types');
            $old_name = $term->name;

            if ( $old_name !== $directory_name && term_exists( $directory_name, 'atbdp_listing_types' ) ) {
                $response['status']['status_log']['name_exists'] = [
                    'type'    => 'error',
                    'message' => 'The name already exists',
                ];

                $response['status']['error_count']++;
            }

            // Return status
            if ( $response['status']['error_count'] ) {
                $response['status']['success'] = false;
                return $response;
            }
            
            // Update name if exist
            if ( ! empty( $directory_name ) ) {
                wp_update_term( $args['term_id'], 'atbdp_listing_types', ['name' => $directory_name] );
            }
            
            // Update the value
            foreach ( $fields as $key => $value ) {
                $this->update_validated_term_meta( $args['term_id'], $key, $value );
            }

            $response['status']['status_log']['updated'] = [
                'type'    => 'success',
                'message' => 'The directory has been updated successfuly',
            ];

            return $response;
        }


        // cptm_fields_before_update
        public function cptm_fields_before_update( $fields ) {
            $new_fields     = $fields;
            $fields_group   = $this->config['fields_group'];

            foreach ( $fields_group as $group_name => $group_fields ) {
                $grouped_fields_value = [];

                foreach ( $group_fields as $field_index => $field_key ) {
                    if ('string' === gettype( $field_key ) && array_key_exists($field_key, $this->fields)) {
                        $grouped_fields_value[ $field_key ] = ( isset( $new_fields[ $field_key ] ) ) ? $new_fields[ $field_key ] : '';
                        unset( $new_fields[ $field_key ] );
                    }

                    if ( 'array' === gettype( $field_key ) ) {
                        $grouped_fields_value[ $field_index ] = [];

                        foreach ( $field_key as $sub_field_key ) {
                            if ( array_key_exists( $sub_field_key, $this->fields ) ) {
                                $grouped_fields_value[ $field_index ][ $sub_field_key ] = ( isset( $new_fields[ $sub_field_key ] ) ) ? $new_fields[ $sub_field_key ] : '';
                                unset( $new_fields[ $sub_field_key ] );
                            }
                        }
                    }
                }

                $new_fields[ $group_name ] = $grouped_fields_value;
            }

            return $new_fields;
        }   

        // save_post_type_data
        public function save_post_type_data()
        {
            /* wp_send_json([
                'status' => false,
                'single_listings_contents' => $this->maybe_json( $_POST['single_listings_contents'] ),
                'status_log' => [
                    'name_is_missing' => [
                        'type' => 'error',
                        'message' => 'Debugging',
                    ],
                ],
            ], 200 ); */

            if (empty($_POST['name'])) {
                wp_send_json([
                    'status' => false,
                    'status_log' => [
                        'name_is_missing' => [
                            'type' => 'error',
                            'message' => 'Name is missing',
                        ],
                    ],
                ], 200);
            }

            $term_id = 0;
            $mode    = 'create';
            $listing_type_name = $_POST['name'];

            if (!empty($_POST['listing_type_id']) && absint($_POST['listing_type_id'])) {
                $mode = 'edit';
                $term_id = absint($_POST['listing_type_id']);
                wp_update_term($term_id, 'atbdp_listing_types', ['name' => $listing_type_name]);
            } else {
                $term = wp_insert_term($listing_type_name, 'atbdp_listing_types');

                if (is_wp_error($term)) {
                    if (!empty($term->errors['term_exists'])) {
                        wp_send_json([
                            'status' => false,
                            'status_log' => [
                                'name_exists' => [
                                    'type' => 'error',
                                    'message' => 'The name already exists',
                                ]
                            ],
                        ], 200);
                    }
                } else {
                    $mode = 'edit';
                    $term_id = $term['term_id'];
                }
            }

            if (empty($term_id)) {
                wp_send_json([
                    'status' => false,
                    'status_log' => [
                        'invalid_id' => [
                            'type' => 'error',
                            'message' => 'Error found, please try again',
                        ]
                    ],
                ], 200);
            }

            $created_message = ('create' == $mode) ? 'created' : 'updated';

            if (empty($_POST['field_list'])) {
                wp_send_json([
                    'status' => true,
                    'post_id' => $term_id,
                    'status_log' => [
                        'post_created' => [
                            'type' => 'success',
                            'message' => 'The Post type has been ' . $created_message . ' successfully',
                        ],
                        'field_list_not_found' => [
                            'type' => 'error',
                            'message' => 'Field list not found',
                        ],
                    ],
                ], 200);
            }
            $url = '';
            $field_list = $this->maybe_json($_POST['field_list']);
            foreach ($field_list as $field_key) {
                if (isset($_POST[$field_key]) && 'name' !==  $field_key) {
                    $this->update_validated_term_meta($term_id, $field_key, $_POST[$field_key]);
                }
            }
            $url = admin_url('edit.php?post_type=at_biz_dir&page=atbdp-directory-types&action=edit&listing_type_id=' . $term_id);
            wp_send_json([
                'status' => true,
                'post_id' => $term_id,
                'redirect_url' => $url,
                'status_log' => [
                    'post_created' => [
                        'type' => 'success',
                        'message' => 'The post type has been ' . $created_message . ' successfully'
                    ]
                ],
            ], 200);
        }

        // update_validated_term_meta
        public function update_validated_term_meta($term_id, $field_key, $value)
        {
            if ( ! isset( $this->fields[$field_key] ) && ! array_key_exists( $field_key, $this->config['fields_group'] ) ) {
                return;
            }

            if ( ! empty( $this->fields[$field_key]['type'] ) && 'toggle' === $this->fields[$field_key]['type'] ) {
                $value = ('true' === $value || true === $value || '1' === $value || 1 === $value) ? true : 0;
            }

            $value = $this->maybe_json($value);
            update_term_meta($term_id, $field_key, $value);
        }

        // maybe_json
        public function maybe_json( $string )
        {
            $string_alt = $string;

            if ( 'string' !== gettype( $string )  ) { return $string; }

            if (preg_match('/\\\\+/', $string_alt)) {
                $string_alt = preg_replace('/\\\\+/', '', $string_alt);
                $string_alt = json_decode($string_alt, true);
                $string     = (!is_null($string_alt)) ? $string_alt : $string;
            }

            $test_json = json_decode($string, true);
            if (!is_null($test_json)) {
                $string = $test_json;
            }

            return $string;
        }

        // maybe_serialize
        public function maybe_serialize($value = '')
        {
            return maybe_serialize($this->maybe_json($value));
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

        // prepare_settings
        public function prepare_settings()
        {
            $this->cetagory_options = $this->get_cetagory_options();

            $form_field_widgets = [
                'preset' => [
                    'title' => 'Preset Fields',
                    'description' => 'Click on a field to use it',
                    'allow_multiple' => false,
                    'widgets' => apply_filters('atbdp_form_preset_widgets', [
                        'title' => [
                            'label' => 'Title',
                            'icon' => 'fa fa-text-height',
                            'lock' => true,
                            'show' => true,
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'text',
                                ],
                                'field_key' => [
                                    'type'   => 'meta-key',
                                    'hidden' => true,
                                    'value'  => 'listing_title',
                                ],
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Label',
                                    'value' => 'Title',
                                ],
                                'placeholder' => [
                                    'type'  => 'text',
                                    'label' => 'Placeholder',
                                    'value' => '',
                                ],
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => true,
                                ],

                            ],
                        ],

                        'description' => [
                            'label' => 'Description',
                            'icon' => 'fa fa-align-left',
                            'show' => true,
                            'options' => [
                                'type' => [
                                    'type'  => 'select',
                                    'label' => 'Type',
                                    'value' => 'wp_editor',
                                    'options' => [
                                        [
                                            'label' => __('Textarea', 'directorist'),
                                            'value' => 'textarea',
                                        ],
                                        [
                                            'label' => __('WP Editor', 'directorist'),
                                            'value' => 'wp_editor',
                                        ],
                                    ]
                                ],
                                'field_key' => [
                                    'type'   => 'meta-key',
                                    'hidden' => true,
                                    'value' => 'listing_content',
                                ],
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Label',
                                    'value' => 'Description',
                                ],
                                'placeholder' => [
                                    'type'  => 'text',
                                    'label' => 'Placeholder',
                                    'value' => '',
                                    'show_if' => [
                                        'where' => "self.type",
                                        'conditions' => [
                                            ['key' => 'value', 'compare' => '=', 'value' => 'textarea'],
                                        ],
                                    ],
                                ],
                                'required' => [
                                    'type'  => 'toggle',
                                    'name'  => 'required',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                                'only_for_admin' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Only For Admin Use',
                                    'value' => false,
                                ],

                            ]
                        ],

                        'tagline' => [
                            'label' => 'Tagline',
                            'icon' => 'uil uil-text-fields',
                            'show' => true,
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'text',
                                ],
                                'field_key' => [
                                    'type'   => 'meta-key',
                                    'hidden' => true,
                                    'value'  => 'tagline',
                                ],
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Label',
                                    'value' => 'Tagline',
                                ],
                                'placeholder' => [
                                    'type'  => 'text',
                                    'label' => 'Placeholder',
                                    'value' => '',
                                ],
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                                'only_for_admin' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Only For Admin Use',
                                    'value' => false,
                                ],

                            ],
                        ],

                        'pricing' => [
                            'label' => 'Pricing',
                            'icon' => 'uil uil-bill',
                            'options' => [
                                'label' => [
                                    'type'  => 'text',
                                    'label'  => 'Label',
                                    'value'  => 'Pricing',
                                ],
                                'pricing_type' => [
                                    'type'  => 'select',
                                    'label'  => 'Select Pricing Type',
                                    'value' => 'both',
                                    // 'show-default-option' => true,
                                    'options' => [
                                        ['value' => 'both', 'label' => 'Both'],
                                        ['value' => 'price_unit', 'label' => 'Price Unit'],
                                        ['value' => 'price_range', 'label' => 'Price Range'],
                                    ],
                                ],
                                'price_range_label' => [
                                    'type'  => 'text',
                                    'show_if' => [
                                        'where' => "self.pricing_type",
                                        'compare' => 'or',
                                        'conditions' => [
                                            ['key' => 'value', 'compare' => '=', 'value' => 'both'],
                                            ['key' => 'value', 'compare' => '=', 'value' => 'price_range'],
                                        ],
                                    ],
                                    'label'  => 'Price Range label',
                                    'value' => 'Price Range',
                                ],
                                'price_range_placeholder' => [
                                    'type'  => 'text',
                                    'show_if' => [
                                        'where' => "self.pricing_type",
                                        'compare' => 'or',
                                        'conditions' => [
                                            ['key' => 'value', 'compare' => '=', 'value' => 'both'],
                                            ['key' => 'value', 'compare' => '=', 'value' => 'price_range'],
                                        ],
                                    ],
                                    'label'  => 'Price Range Placeholder',
                                    'value' => 'Select Price Range',
                                ],
                                'price_unit_field_type' => [
                                    'type'  => 'select',
                                    'label'  => 'Price Unit Field Type',
                                    'show_if' => [
                                        'where' => "self.pricing_type",
                                        'compare' => 'or',
                                        'conditions' => [
                                            ['key' => 'value', 'compare' => '=', 'value' => 'both'],
                                            ['key' => 'value', 'compare' => '=', 'value' => 'price_unit'],
                                        ],
                                    ],
                                    'value' => 'number',
                                    'options' => [
                                        ['value' => 'number', 'label' => 'Number',],
                                        ['value' => 'text', 'label' => 'Text',],
                                    ],
                                ],
                                'price_unit_field_label' => [
                                    'type'  => 'text',
                                    'label'  => 'Price Unit Field label',
                                    'show_if' => [
                                        'where' => "self.pricing_type",
                                        'compare' => 'or',
                                        'conditions' => [
                                            ['key' => 'value', 'compare' => '=', 'value' => 'both'],
                                            ['key' => 'value', 'compare' => '=', 'value' => 'price_unit'],
                                        ],
                                    ],
                                    'value' => 'Price [USD]',
                                ],
                                'price_unit_field_placeholder' => [
                                    'type'  => 'text',
                                    'label'  => 'Price Unit Field Placeholder',
                                    'show_if' => [
                                        'where' => "self.pricing_type",
                                        'compare' => 'or',
                                        'conditions' => [
                                            ['key' => 'value', 'compare' => '=', 'value' => 'both'],
                                            ['key' => 'value', 'compare' => '=', 'value' => 'price_unit'],
                                        ],
                                    ],
                                    'value' => 'Price of this listing. Eg. 100',
                                ],
                                'only_for_admin' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Only For Admin Use',
                                    'value' => false,
                                ],
                            ]
                        ],

                        'view_count' => [
                            'label' => 'View Count',
                            'icon' => 'uil uil-eye',
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'number',
                                ],
                                'field_key' => [
                                    'type'   => 'meta-key',
                                    'hidden' => true,
                                    'value' => 'atbdp_post_views_count',
                                ],
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Label',
                                    'value' => 'View Count',
                                ],
                                'placeholder' => [
                                    'type'  => 'text',
                                    'label' => 'Placeholder',
                                    'value' => '',
                                ],
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                                'only_for_admin' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Only For Admin Use',
                                    'value' => true,
                                ],


                            ],
                        ],

                        'excerpt' => [
                            'label' => 'Excerpt',
                            'icon' => 'uil uil-subject',
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'textarea',
                                ],
                                'field_key' => [
                                    'type'   => 'meta-key',
                                    'hidden' => true,
                                    'value' => 'excerpt',
                                ],
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Label',
                                    'value' => 'Excerpt',
                                ],
                                'placeholder' => [
                                    'type'  => 'text',
                                    'label' => 'Placeholder',
                                    'value' => '',
                                ],
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                                'only_for_admin' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Only For Admin Use',
                                    'value' => false,
                                ],


                            ],
                        ],

                        'location' => [
                            'label' => 'Location',
                            'icon' => 'uil uil-map-marker',
                            'options' => [
                                'field_key' => [
                                    'type'   => 'meta-key',
                                    'hidden' => true,
                                    'value'  => 'tax_input[at_biz_dir-location][]',
                                ],
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Label',
                                    'value' => 'Location',
                                ],
                                'type' => [
                                    'type'  => 'radio',
                                    'value' => 'multiple',
                                    'label' => 'Selection Type',
                                    'options' => [
                                        [
                                            'label' => __('Single Selection', 'directorist'),
                                            'value' => 'single',
                                        ],
                                        [
                                            'label' => __('Multi Selection', 'directorist'),
                                            'value' => 'multiple',
                                        ]
                                    ]
                                ],
                                'placeholder' => [
                                    'type'  => 'text',
                                    'label' => 'Placeholder',
                                    'value' => '',
                                ],
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                                'only_for_admin' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Only For Admin Use',
                                    'value' => false,
                                ],


                            ],
                        ],

                        'tag' => [
                            'label' => 'Tag',
                            'icon' => 'uil uil-tag-alt',
                            'options' => [
                                'field_key' => [
                                    'type'   => 'meta-key',
                                    'hidden' => true,
                                    'value'  => 'tax_input[at_biz_dir-tags][]',
                                ],
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Label',
                                    'value' => 'Tag',
                                ],
                                'placeholder' => [
                                    'type'  => 'text',
                                    'label' => 'Placeholder',
                                    'value' => 'Tag',
                                ],
                                'type' => [
                                    'type'  => 'radio',
                                    'value' => 'multiple',
                                    'label' => 'Selection Type',
                                    'options' => [
                                        [
                                            'label' => __('Single Selection', 'directorist'),
                                            'value' => 'single',
                                        ],
                                        [
                                            'label' => __('Multi Selection', 'directorist'),
                                            'value' => 'multiple',
                                        ]
                                    ]
                                ],
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                                'allow_new' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Allow New',
                                    'value' => true,
                                ],
                                'only_for_admin' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Only For Admin Use',
                                    'value' => false,
                                ],


                            ],
                        ],

                        'category' => [
                            'label' => 'Category',
                            'icon' => 'uil uil-folder-open',
                            'options' => [
                                'field_key' => [
                                    'type'   => 'meta-key',
                                    'hidden' => true,
                                    'value'  => 'admin_category_select[]',
                                ],
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Label',
                                    'value' => 'Category',
                                ],
                                'type' => [
                                    'type'  => 'radio',
                                    'value' => 'multiple',
                                    'label' => 'Selection Type',
                                    'options' => [
                                        [
                                            'label' => __('Single Selection', 'directorist'),
                                            'value' => 'single',
                                        ],
                                        [
                                            'label' => __('Multi Selection', 'directorist'),
                                            'value' => 'multiple',
                                        ]
                                    ]
                                ],
                                'placeholder' => [
                                    'type'  => 'text',
                                    'label' => 'Placeholder',
                                    'value' => '',
                                ],
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                                'only_for_admin' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Only For Admin Use',
                                    'value' => false,
                                ],


                            ],
                        ],   

                        'map' => [
                            'label' => 'Map',
                            'icon' => 'uil uil-map',
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'map',
                                ],
                                'field_key' => [
                                    'type'   => 'meta-key',
                                    'hidden' => true,
                                    'value'  => 'map',
                                ],
                                'label' => [
                                    'type'  => 'hidden',
                                    'value' => 'Map',
                                ],
                                'lat_long' => [
                                    'type'  => 'text',
                                    'label' => 'Enter Coordinates Label',
                                    'value' => 'Or Enter Coordinates (latitude and longitude) Manually',
                                ],
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                                'only_for_admin' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Only For Admin Use',
                                    'value' => false,
                                ],
                            ],
                        ],

                        'address' => [
                            'label' => 'Address',
                            'icon' => 'uil uil-map-pin',
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'text',
                                ],
                                'field_key' => [
                                    'type'   => 'meta-key',
                                    'hidden' => true,
                                    'value'  => 'address',
                                ],
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Label',
                                    'value' => 'Address',
                                ],
                                'placeholder' => [
                                    'type'  => 'text',
                                    'label' => 'Placeholder',
                                    'value' => 'Listing address eg. New York, USA',
                                ],
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                                'only_for_admin' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Only For Admin Use',
                                    'value' => false,
                                ],
                            ],
                        ],

                        'zip' => [
                            'label' => 'Zip/Post Code',
                            'icon' => 'uil uil-map-pin',
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'text',
                                ],
                                'field_key' => [
                                    'type'   => 'meta-key',
                                    'hidden' => true,
                                    'value'  => 'zip',
                                ],
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Label',
                                    'value' => 'Zip/Post Code',
                                ],
                                'placeholder' => [
                                    'type'  => 'text',
                                    'label' => 'Placeholder',
                                    'value' => '',
                                ],
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                                'only_for_admin' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Only For Admin Use',
                                    'value' => false,
                                ],


                            ],
                        ],

                        'phone' => [
                            'label' => 'Phone',
                            'icon' => 'uil uil-phone',
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'tel',
                                ],
                                'field_key' => [
                                    'type'   => 'meta-key',
                                    'hidden' => true,
                                    'value'  => 'phone',
                                ],
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Label',
                                    'value' => 'Phone',
                                ],
                                'placeholder' => [
                                    'type'  => 'text',
                                    'label' => 'Placeholder',
                                    'value' => '',
                                ],
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                                'only_for_admin' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Only For Admin Use',
                                    'value' => false,
                                ],


                            ],
                        ],

                        'phone2' => [
                            'label' => 'Phone 2',
                            'icon' => 'uil uil-phone',
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'tel',
                                ],
                                'field_key' => [
                                    'type'   => 'meta-key',
                                    'hidden' => true,
                                    'value'  => 'phone2',
                                ],
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Label',
                                    'value' => 'Phone 2',
                                ],
                                'placeholder' => [
                                    'type'  => 'text',
                                    'label' => 'Placeholder',
                                    'value' => '',
                                ],
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                                'only_for_admin' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Only For Admin Use',
                                    'value' => false,
                                ],


                            ],
                        ],

                        'fax' => [
                            'label' => 'Fax',
                            'icon' => 'uil uil-print',
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'number',
                                ],
                                'field_key' => [
                                    'type'   => 'meta-key',
                                    'hidden' => true,
                                    'value'  => 'fax',
                                ],
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Label',
                                    'value' => 'Fax',
                                ],
                                'placeholder' => [
                                    'type'  => 'text',
                                    'label' => 'Placeholder',
                                    'value' => '',
                                ],
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                                'only_for_admin' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Only For Admin Use',
                                    'value' => false,
                                ],


                            ],
                        ],

                        'email' => [
                            'label' => 'Email',
                            'icon' => 'uil uil-envelope',
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'email',
                                ],
                                'field_key' => [
                                    'type'   => 'meta-key',
                                    'hidden' => true,
                                    'value'  => 'email',
                                ],
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Label',
                                    'value' => 'Email',
                                ],
                                'placeholder' => [
                                    'type'  => 'text',
                                    'label' => 'Placeholder',
                                    'value' => '',
                                ],
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                                'only_for_admin' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Only For Admin Use',
                                    'value' => false,
                                ],


                            ],
                        ],

                        'website' => [
                            'label' => 'Website',
                            'icon' => 'uil uil-globe',
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'text',
                                ],
                                'field_key' => [
                                    'type'   => 'meta-key',
                                    'hidden' => true,
                                    'value'  => 'website',
                                ],
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Label',
                                    'value' => 'Website',
                                ],
                                'placeholder' => [
                                    'type'  => 'text',
                                    'label' => 'Placeholder',
                                    'value' => '',
                                ],
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                                'only_for_admin' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Only For Admin Use',
                                    'value' => false,
                                ],


                            ],
                        ],

                        'social_info' => [
                            'label' => 'Social Info',
                            'icon' => 'uil uil-user-arrows',
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'add_new',
                                ],
                                'field_key' => [
                                    'type'   => 'meta-key',
                                    'hidden' => true,
                                    'value'  => 'social',
                                ],
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Label',
                                    'value' => 'Social Info',
                                ],
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                                'only_for_admin' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Only For Admin Use',
                                    'value' => false,
                                ],


                            ],
                        ],

                        'image_upload' => [
                            'label' => 'Images',
                            'icon' => 'uil uil-image',
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'media',
                                ],
                                'field_key' => [
                                    'type'   => 'meta-key',
                                    'hidden' => true,
                                    'value'  => 'listing_img',
                                ],
                                'label' => [
                                    'type'  => 'hidden',
                                    'value' => 'Images',
                                ],
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                                'select_files_label' => [
                                    'type'  => 'text',
                                    'label' => 'Select Files Label',
                                    'value' => 'Select Files',
                                ],
                                'max_image_limit' => [
                                    'type'  => 'number',
                                    'label' => 'Max Image Limit',
                                    'value' => 5,
                                ],
                                'max_per_image_limit' => [
                                    'type'  => 'number',
                                    'label' => __( 'Max Upload Size Per Image in MB', 'directorist' ),
                                    'description' => __( 'Here 0 means unlimited.', 'directorist' ) ,
                                    'value' => 0,
                                ],
                                'max_total_image_limit' => [
                                    'type'  => 'number',
                                    'label' => 'Total Upload Size in MB',
                                    'value' => 2,
                                ],
                                'only_for_admin' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Only For Admin Use',
                                    'value' => false,
                                ],


                            ],
                        ],

                        'video' => [
                            'label' => 'Video',
                            'icon' => 'uil uil-video',
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'text',
                                ],
                                'field_key' => [
                                    'type'   => 'meta-key',
                                    'hidden' => true,
                                    'value'  => 'videourl',
                                ],
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Label',
                                    'value' => 'Video',
                                ],
                                'placeholder' => [
                                    'type'  => 'text',
                                    'label' => 'Placeholder',
                                    'value' => 'Only YouTube & Vimeo URLs.',
                                ],
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                                'only_for_admin' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Only For Admin Use',
                                    'value' => false,
                                ],


                            ],
                        ],

                        'hide_contact_owner' => [
                            'label' => 'Hiding Contact Owner Form',
                            'icon' => 'uil uil-postcard',
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'text',
                                ],
                                'field_key' => [
                                    'type'   => 'meta-key',
                                    'hidden' => true,
                                    'value'  => 'hide_contact_owner',
                                ],
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Label',
                                    'value' => 'Hide contact owner form for single listing page',
                                ],
                            ],
                        ],
                    ]),
                ],

                'custom' => [
                    'title' => 'Custom Fields',
                    'description' => 'Click on a field type you want to create',
                    'allow_multiple' => true,
                    'widgets' => apply_filters('atbdp_form_custom_widgets', [
                        'text' => [
                            'label' => 'Text',
                            'icon' => 'uil uil-text',
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'text',
                                ],
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Label',
                                    'value' => 'Text',
                                ],
                                'field_key' => [
                                    'type'  => 'meta-key',
                                    'label' => 'Key',
                                    'value' => 'custom-text',
                                ],
                                'placeholder' => [
                                    'type'  => 'text',
                                    'label' => 'Placeholder',
                                    'value' => '',
                                ],
                                'description' => [
                                    'type'  => 'text',
                                    'label' => 'Description',
                                    'value' => '',
                                ],
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                                'only_for_admin' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Only For Admin Use',
                                    'value' => false,
                                ],
                                'assign_to' => $this->get_assign_to_field(),
                                'category' => $this->get_category_select_field([
                                    'show_if' => [
                                        'where' => "self.assign_to",
                                        'conditions' => [
                                            ['key' => 'value', 'compare' => '=', 'value' => 'category'],
                                        ],
                                    ],
                                ]),


                            ]

                        ],

                        'textarea' => [
                            'label' => 'Textarea',
                            'icon' => 'uil uil-text-fields',
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'textarea',
                                ],
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Label',
                                    'value' => 'Textarea',
                                ],
                                'field_key' => [
                                    'type'  => 'meta-key',
                                    'label' => 'Key',
                                    'value' => 'custom-textarea',
                                ],
                                'rows' => [
                                    'type'  => 'number',
                                    'label' => 'Rows',
                                    'value' => 8,
                                ],
                                'placeholder' => [
                                    'type'  => 'text',
                                    'label' => 'Placeholder',
                                    'value' => '',
                                ],
                                'description' => [
                                    'type'  => 'text',
                                    'label' => 'Description',
                                    'value' => '',
                                ],
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                                'only_for_admin' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Only For Admin Use',
                                    'value' => false,
                                ],
                                'assign_to' => $this->get_assign_to_field(),
                                'category' => $this->get_category_select_field([
                                    'show_if' => [
                                        'where' => "self.assign_to",
                                        'conditions' => [
                                            ['key' => 'value', 'compare' => '=', 'value' => 'category'],
                                        ],
                                    ],
                                ]),


                            ]

                        ],

                        'number' => [
                            'label' => 'Number',
                            'icon' => 'uil uil-0-plus',
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'number',
                                ],
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Label',
                                    'value' => 'Number',
                                ],
                                'field_key' => [
                                    'type'  => 'meta-key',
                                    'label' => 'Key',
                                    'value' => 'custom-number',
                                ],
                                'placeholder' => [
                                    'type'  => 'text',
                                    'label' => 'Placeholder',
                                    'value' => '',
                                ],
                                'description' => [
                                    'type'  => 'text',
                                    'label' => 'Description',
                                    'value' => '',
                                ],
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                                'only_for_admin' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Only For Admin Use',
                                    'value' => false,
                                ],
                                'assign_to' => $this->get_assign_to_field(),
                                'category' => $this->get_category_select_field([
                                    'show_if' => [
                                        'where' => "self.assign_to",
                                        'conditions' => [
                                            ['key' => 'value', 'compare' => '=', 'value' => 'category'],
                                        ],
                                    ],
                                ]),


                            ]

                        ],

                        'url' => [
                            'label' => 'URL',
                            'icon' => 'uil uil-link-add',
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'text',
                                ],
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Label',
                                    'value' => 'URL',
                                ],
                                'field_key' => [
                                    'type'  => 'meta-key',
                                    'label' => 'Key',
                                    'value' => 'custom-url',
                                ],
                                'placeholder' => [
                                    'type'  => 'text',
                                    'label' => 'Placeholder',
                                    'value' => '',
                                ],
                                'target' => [
                                    'type'  => 'text',
                                    'label' => 'Open in new tab',
                                    'value' => '',
                                ],
                                'description' => [
                                    'type'  => 'text',
                                    'label' => 'Description',
                                    'value' => '',
                                ],
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                                'only_for_admin' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Only For Admin Use',
                                    'value' => false,
                                ],
                                'assign_to' => $this->get_assign_to_field(),
                                'category' => $this->get_category_select_field([
                                    'show_if' => [
                                        'where' => "self.assign_to",
                                        'conditions' => [
                                            ['key' => 'value', 'compare' => '=', 'value' => 'category'],
                                        ],
                                    ],
                                ]),


                            ]

                        ],

                        'date' => [
                            'label' => 'Date',
                            'icon' => 'uil uil-calender',
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'date',
                                ],
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Label',
                                    'value' => 'Date',
                                ],
                                'field_key' => [
                                    'type'  => 'meta-key',
                                    'label' => 'Key',
                                    'value' => 'custom-date',
                                ],
                                'placeholder' => [
                                    'type'  => 'text',
                                    'label' => 'Placeholder',
                                    'value' => '',
                                ],
                                'description' => [
                                    'type'  => 'text',
                                    'label' => 'Description',
                                    'value' => '',
                                ],
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                                'only_for_admin' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Only For Admin Use',
                                    'value' => false,
                                ],
                                'assign_to' => $this->get_assign_to_field(),
                                'category' => $this->get_category_select_field([
                                    'show_if' => [
                                        'where' => "self.assign_to",
                                        'conditions' => [
                                            ['key' => 'value', 'compare' => '=', 'value' => 'category'],
                                        ],
                                    ],
                                ]),


                            ]

                        ],

                        'time' => [
                            'label' => 'Time',
                            'icon' => 'uil uil-clock',
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'time',
                                ],
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Label',
                                    'value' => 'Time',
                                ],
                                'field_key' => [
                                    'type'  => 'meta-key',
                                    'label' => 'Key',
                                    'value' => 'custom-time',
                                ],
                                'placeholder' => [
                                    'type'  => 'text',
                                    'label' => 'Placeholder',
                                    'value' => '',
                                ],
                                'description' => [
                                    'type'  => 'text',
                                    'label' => 'Description',
                                    'value' => '',
                                ],
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                                'only_for_admin' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Only For Admin Use',
                                    'value' => false,
                                ],
                                'assign_to' => $this->get_assign_to_field(),
                                'category' => $this->get_category_select_field([
                                    'show_if' => [
                                        'where' => "self.assign_to",
                                        'conditions' => [
                                            ['key' => 'value', 'compare' => '=', 'value' => 'category'],
                                        ],
                                    ],
                                ]),


                            ]

                        ],

                        'color_picker' => [
                            'label' => 'Color',
                            'icon' => 'uil uil-palette',
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'color_picker',
                                ],
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Label',
                                    'value' => 'Color',
                                ],
                                'field_key' => [
                                    'type'  => 'meta-key',
                                    'label' => 'Key',
                                    'value' => 'custom-color-picker',
                                ],
                                'description' => [
                                    'type'  => 'text',
                                    'label' => 'Description',
                                    'value' => '',
                                ],
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                                'only_for_admin' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Only For Admin Use',
                                    'value' => false,
                                ],
                                'assign_to' => $this->get_assign_to_field(),
                                'category' => $this->get_category_select_field([
                                    'show_if' => [
                                        'where' => "self.assign_to",
                                        'conditions' => [
                                            ['key' => 'value', 'compare' => '=', 'value' => 'category'],
                                        ],
                                    ],
                                ]),


                            ]

                        ],

                        'select' => [
                            'label' => 'Select',
                            'icon' => 'uil uil-file-check',
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'select',
                                ],
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Label',
                                    'value' => 'Select',
                                ],
                                'field_key' => [
                                    'type'  => 'meta-key',
                                    'label' => 'Key',
                                    'value' => 'custom-select',
                                ],
                                'options' => [
                                    'type' => 'multi-fields',
                                    'label' => __('Options', 'directorist'),
                                    'add-new-button-label' => 'Add Option',
                                    'options' => [
                                        'option_value' => [
                                            'type'  => 'text',
                                            'label' => 'Option Value',
                                            'value' => '',
                                        ],
                                        'option_label' => [
                                            'type'  => 'text',
                                            'label' => 'Option Label',
                                            'value' => '',
                                        ],
                                    ]
                                ],
                                'description' => [
                                    'type'  => 'text',
                                    'label' => 'Description',
                                    'value' => '',
                                ],
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                                'only_for_admin' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Only For Admin Use',
                                    'value' => false,
                                ],
                                'assign_to' => $this->get_assign_to_field(),
                                'category' => $this->get_category_select_field([
                                    'show_if' => [
                                        'where' => "self.assign_to",
                                        'conditions' => [
                                            ['key' => 'value', 'compare' => '=', 'value' => 'category'],
                                        ],
                                    ],
                                ]),


                            ]

                        ],

                        'checkbox' => [
                            'label' => 'Checkbox',
                            'icon' => 'uil uil-check-square',
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'checkbox',
                                ],
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Label',
                                    'value' => 'Checkbox',
                                ],
                                'field_key' => [
                                    'type'  => 'meta-key',
                                    'label' => 'Key',
                                    'value' => 'custom-checkbox',
                                ],
                                'options' => [
                                    'type' => 'multi-fields',
                                    'label' => __('Options', 'directorist'),
                                    'add-new-button-label' => 'Add Option',
                                    'options' => [
                                        'option_value' => [
                                            'type'  => 'text',
                                            'label' => 'Option Value',
                                            'value' => '',
                                        ],
                                        'option_label' => [
                                            'type'  => 'text',
                                            'label' => 'Option Label',
                                            'value' => '',
                                        ],
                                    ]
                                ],
                                'description' => [
                                    'type'  => 'text',
                                    'label' => 'Description',
                                    'value' => '',
                                ],
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                                'only_for_admin' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Only For Admin Use',
                                    'value' => false,
                                ],
                                'assign_to' => $this->get_assign_to_field(),
                                'category' => $this->get_category_select_field([
                                    'show_if' => [
                                        'where' => "self.assign_to",
                                        'conditions' => [
                                            ['key' => 'value', 'compare' => '=', 'value' => 'category'],
                                        ],
                                    ],
                                ]),

                            ]

                        ],

                        'radio' => [
                            'label' => 'Radio',
                            'icon' => 'uil uil-circle',
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'radio',
                                ],
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Label',
                                    'value' => 'Radio',
                                ],
                                'field_key' => [
                                    'type'  => 'meta-key',
                                    'label' => 'Key',
                                    'value' => 'custom-radio',
                                ],
                                'options' => [
                                    'type' => 'multi-fields',
                                    'label' => __('Options', 'directorist'),
                                    'add-new-button-label' => 'Add Option',
                                    'options' => [
                                        'option_value' => [
                                            'type'  => 'text',
                                            'label' => 'Option Value',
                                            'value' => '',
                                        ],
                                        'option_label' => [
                                            'type'  => 'text',
                                            'label' => 'Option Label',
                                            'value' => '',
                                        ],
                                    ]
                                ],
                                'description' => [
                                    'type'  => 'text',
                                    'label' => 'Description',
                                    'value' => '',
                                ],
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                                'only_for_admin' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Only For Admin Use',
                                    'value' => false,
                                ],
                                'assign_to' => $this->get_assign_to_field(),
                                'category' => $this->get_category_select_field([
                                    'show_if' => [
                                        'where' => "self.assign_to",
                                        'conditions' => [
                                            ['key' => 'value', 'compare' => '=', 'value' => 'category'],
                                        ],
                                    ],
                                ]),
                            ]

                        ],

                        'file' => [
                            'label' => 'File Upload',
                            'icon' => 'uil uil-file-upload-alt',
                            'options' => [
                                'type' => [
                                    'type'  => 'hidden',
                                    'value' => 'file',
                                ],
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Label',
                                    'value' => 'File Upload',
                                ],
                                'field_key' => [
                                    'type'  => 'meta-key',
                                    'label' => 'Key',
                                    'value' => 'custom-file',
                                ],
                                'file_type' => [
                                    'type'  => 'select',
                                    'label' => 'Chose a file type',
                                    'value' => '',
                                    'options' => [
                                        [
                                            'label' => __('All Types', 'directorist'),
                                            'value' => 'all',
                                        ],
                                        [
                                            'group' => __('Image Format', 'directorist'),
                                            'options' => [
                                                [
                                                    'label' => __('jpg', 'directorist'),
                                                    'value' => 'jpg',
                                                ],
                                                [
                                                    'label' => __('jpeg', 'directorist'),
                                                    'value' => 'jpeg',
                                                ],
                                                [
                                                    'label' => __('gif', 'directorist'),
                                                    'value' => 'gif',
                                                ],
                                                [
                                                    'label' => __('png', 'directorist'),
                                                    'value' => 'png',
                                                ],
                                                [
                                                    'label' => __('bmp', 'directorist'),
                                                    'value' => 'bmp',
                                                ],
                                                [
                                                    'label' => __('ico', 'directorist'),
                                                    'value' => 'ico',
                                                ],
                                            ],
                                        ],
                                        [
                                            'group' => 'Video Format',
                                            'options' => [
                                                [
                                                    'label' => __('asf', 'directorist'),
                                                    'value' => 'asf',
                                                ],
                                                [
                                                    'label' => __('flv', 'directorist'),
                                                    'value' => 'flv',
                                                ],
                                                [
                                                    'label' => __('avi', 'directorist'),
                                                    'value' => 'avi',
                                                ],
                                                [
                                                    'label' => __('mkv', 'directorist'),
                                                    'value' => 'mkv',
                                                ],
                                                [
                                                    'label' => __('mp4', 'directorist'),
                                                    'value' => 'mp4',
                                                ],
                                                [
                                                    'label' => __('mpeg', 'directorist'),
                                                    'value' => 'mpeg',
                                                ],
                                                [
                                                    'label' => __('mpg', 'directorist'),
                                                    'value' => 'mpg',
                                                ],
                                                [
                                                    'label' => __('wmv', 'directorist'),
                                                    'value' => 'wmv',
                                                ],
                                                [
                                                    'label' => __('3gp', 'directorist'),
                                                    'value' => '3gp',
                                                ],
                                            ],
                                        ],
                                        [
                                            'group' => 'Audio',
                                            'options' => [
                                                [
                                                    'label' => __('ogg', 'directorist'),
                                                    'value' => 'ogg',
                                                ],
                                                [
                                                    'label' => __('mp3', 'directorist'),
                                                    'value' => 'mp3',
                                                ],
                                                [
                                                    'label' => __('wav', 'directorist'),
                                                    'value' => 'wav',
                                                ],
                                                [
                                                    'label' => __('wma', 'directorist'),
                                                    'value' => 'wma',
                                                ],
                                            ],
                                        ],
                                        [
                                            'group' => 'Text Format',
                                            'options' => [
                                                [
                                                    'label' => __('css', 'directorist'),
                                                    'value' => 'css',
                                                ],
                                                [
                                                    'label' => __('csv', 'directorist'),
                                                    'value' => 'csv',
                                                ],
                                                [
                                                    'label' => __('htm', 'directorist'),
                                                    'value' => 'htm',
                                                ],
                                                [
                                                    'label' => __('html', 'directorist'),
                                                    'value' => 'html',
                                                ],
                                                [
                                                    'label' => __('txt', 'directorist'),
                                                    'value' => 'txt',
                                                ],
                                                [
                                                    'label' => __('rtx', 'directorist'),
                                                    'value' => 'rtx',
                                                ],
                                                [
                                                    'label' => __('vtt', 'directorist'),
                                                    'value' => 'vtt',
                                                ],
                                            ],

                                        ],
                                        [
                                            'group' => 'Application Format',
                                            'options' => [
                                                [
                                                    'label' => __('doc', 'directorist'),
                                                    'value' => 'doc',
                                                ],
                                                [
                                                    'label' => __('docx', 'directorist'),
                                                    'value' => 'docx',
                                                ],
                                                [
                                                    'label' => __('odt', 'directorist'),
                                                    'value' => 'odt',
                                                ],
                                                [
                                                    'label' => __('pdf', 'directorist'),
                                                    'value' => 'pdf',
                                                ],
                                                [
                                                    'label' => __('pot', 'directorist'),
                                                    'value' => 'pot',
                                                ],
                                                [
                                                    'label' => __('ppt', 'directorist'),
                                                    'value' => 'ppt',
                                                ],
                                                [
                                                    'label' => __('pptx', 'directorist'),
                                                    'value' => 'pptx',
                                                ],
                                                [
                                                    'label' => __('rar', 'directorist'),
                                                    'value' => 'rar',
                                                ],
                                                [
                                                    'label' => __('rtf', 'directorist'),
                                                    'value' => 'rtf',
                                                ],
                                                [
                                                    'label' => __('swf', 'directorist'),
                                                    'value' => 'swf',
                                                ],
                                                [
                                                    'label' => __('xls', 'directorist'),
                                                    'value' => 'xls',
                                                ],
                                                [
                                                    'label' => __('xlsx', 'directorist'),
                                                    'value' => 'xlsx',
                                                ],
                                                [
                                                    'label' => __('gpx', 'directorist'),
                                                    'value' => 'gpx',
                                                ],
                                            ],
                                        ]

                                    ],
                                ],
                                'file_size' => [
                                    'type'  => 'text',
                                    'label' => 'File Size',
                                    'description' => __('Set maximum file size to upload', 'directorist'),
                                    'value' => '2mb',
                                ],
                                'description' => [
                                    'type'  => 'text',
                                    'label' => 'Description',
                                    'value' => '',
                                ],
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                                'only_for_admin' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Only For Admin Use',
                                    'value' => false,
                                ],
                                'assign_to' => $this->get_assign_to_field(),
                                'category' => $this->get_category_select_field([
                                    'show_if' => [
                                        'where' => "self.assign_to",
                                        'conditions' => [
                                            ['key' => 'value', 'compare' => '=', 'value' => 'category'],
                                        ],
                                    ],
                                ]),
                            ]

                        ],
                    ])

                ],
            ];

            $single_listings_contents_widgets = [
                'preset_widgets' => [
                    'title' => 'Preset Fields',
                    'description' => 'Click on a field to use it',
                    'allow_multiple' => false,
                    'template' => 'submission_form_fields',
                    'widgets' => [
                        'tag' => [
                            'options' => [
                                'icon' => [
                                    'type'  => 'icon',
                                    'label' => 'Icon',
                                    'value' => 'la la-tag',
                                ],
                            ]
                        ],
                        'address'          => [
                            'options' => [
                                'icon' => [
                                    'type'  => 'icon',
                                    'label' => 'Icon',
                                    'value' => 'la la-address-card',
                                ],
                                'address_link_with_map' => [
                                    'type'  => 'toggle',
                                    'label' => 'Address Linked with Map',
                                    'value' => false,
                                ],
                            ]
                        ],
                        'map' => [
                            'options' => [
                                'icon' => [
                                    'type'  => 'icon',
                                    'label' => 'Icon',
                                    'value' => 'la la-map',
                                ],
                            ]
                        ],
                        'zip' => [
                            'options' => [
                                'icon' => [
                                    'type'  => 'icon',
                                    'label' => 'Icon',
                                    'value' => 'la la-street-view',
                                ],
                            ]
                        ],
                        'phone' => [
                            'options' => [
                                'icon' => [
                                    'type'  => 'icon',
                                    'label' => 'Icon',
                                    'value' => 'la la-phone',
                                ],
                            ]
                        ],
                        'phone2' => [
                            'options' => [
                                'icon' => [
                                    'type'  => 'icon',
                                    'label' => 'Icon',
                                    'value' => 'la la-phone',
                                ],
                            ]
                        ],
                        'fax' => [
                            'options' => [
                                'icon' => [
                                    'type'  => 'icon',
                                    'label' => 'Icon',
                                    'value' => 'la la-fax',
                                ],
                            ]
                        ],
                        'email' => [
                            'options' => [
                                'icon' => [
                                    'type'  => 'icon',
                                    'label' => 'Icon',
                                    'value' => 'la la-envelope',
                                ],
                            ]
                        ],
                        'website' => [
                            'options' => [
                                'icon' => [
                                    'type'  => 'icon',
                                    'label' => 'Icon',
                                    'value' => 'la la-globe',
                                ],
                                'use_nofollow' => [
                                    'type'  => 'toggle',
                                    'label' => 'Use rel="nofollow" in Website Link',
                                    'value' => false,
                                ],
                            ]
                        ],
                        'social_info' => [
                            'options' => [
                                'icon' => [
                                    'type'  => 'icon',
                                    'label' => 'Icon',
                                    'value' => 'la la-share-alt',
                                ],
                            ]
                        ],
                        'video' => [
                            'options' => [
                                'icon' => [
                                    'type'  => 'icon',
                                    'label' => 'Icon',
                                    'value' => 'la la-video',
                                ],
                            ]
                        ],
                        'text' => [
                            'options' => [
                                'icon' => [
                                    'type'  => 'icon',
                                    'label' => 'Icon',
                                    'value' => 'la la-text-height',
                                ],
                            ]
                        ],
                        'textarea' => [
                            'options' => [
                                'icon' => [
                                    'type'  => 'icon',
                                    'label' => 'Icon',
                                    'value' => 'la la-align-center',
                                ],
                            ]
                        ],
                        'number' => [
                            'options' => [
                                'icon' => [
                                    'type'  => 'icon',
                                    'label' => 'Icon',
                                    'value' => 'la la-list-ol',
                                ],
                            ]
                        ],
                        'url' => [
                            'options' => [
                                'icon' => [
                                    'type'  => 'icon',
                                    'label' => 'Icon',
                                    'value' => 'la la-link',
                                ],
                            ]
                        ],
                        'date' => [
                            'options' => [
                                'icon' => [
                                    'type'  => 'icon',
                                    'label' => 'Icon',
                                    'value' => 'la la-calendar',
                                ],
                            ]
                        ],
                        'time' => [
                            'options' => [
                                'icon' => [
                                    'type'  => 'icon',
                                    'label' => 'Icon',
                                    'value' => 'la la-clock',
                                ],
                            ]
                        ],
                        'color_picker' => [
                            'options' => [
                                'icon' => [
                                    'type'  => 'icon',
                                    'label' => 'Icon',
                                    'value' => 'la la-palette',
                                ],
                            ]
                        ],
                        'select' => [
                            'options' => [
                                'icon' => [
                                    'type'  => 'icon',
                                    'label' => 'Icon',
                                    'value' => 'la la-clipboard-check',
                                ],
                            ]
                        ],
                        'checkbox' => [
                            'options' => [
                                'icon' => [
                                    'type'  => 'icon',
                                    'label' => 'Icon',
                                    'value' => 'la la-check-square',
                                ],
                            ]
                        ],
                        'radio' => [
                            'options' => [
                                'icon' => [
                                    'type'  => 'icon',
                                    'label' => 'Icon',
                                    'value' => 'la la-circle',
                                ],
                            ]
                        ],
                        'file' => [
                            'options' => [
                                'icon' => [
                                    'type'  => 'icon',
                                    'label' => 'Icon',
                                    'value' => 'la la-file-alt',
                                ],
                            ]
                        ],
                    ],
                ],
                'other_widgets' => [
                    'title' => 'Other Fields',
                    'description' => 'Click on a field to use it',
                    'allow_multiple' => false,
                    'widgets' => [
                        'address' => [
                            'label' => 'Address',
                            'icon' => 'uil uil-postcard',
                            'options' => [
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Label',
                                    'value' => 'Address',
                                ],
                                'icon' => [
                                    'type'  => 'icon',
                                    'label' => 'Icon',
                                    'value' => 'la la-address-card',
                                ],
                                'custom_block_id' => [
                                    'type'  => 'text',
                                    'label'  => 'Custom block ID',
                                    'value' => '',
                                ],
                                'custom_block_classes' => [
                                    'type'  => 'text',
                                    'label'  => 'Custom block Classes',
                                    'value' => '',
                                ],
                            ],
                            'show_if' => [
                                'where' => "submission_form_fields.value.fields",
                                'conditions' => [
                                    ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'map'],
                                ],
                            ],
                        ],
                        'review' => [ 
                            'type' => 'section',
                            'label' => 'Review',
                            'icon' => 'la la-star',
                            'options' => [
                                'custom_block_id' => [
                                    'type'  => 'text',
                                    'label'  => 'Custom block ID',
                                    'value' => '',
                                ],
                                'custom_block_classes' => [
                                    'type'  => 'text',
                                    'label'  => 'Custom block Classes',
                                    'value' => '',
                                ],
                            ],
                        ],
                        'author_info' => [
                            'type' => 'section',
                            'label' => 'Author Info',
                            'icon' => 'la la-user',
                            'options' => [
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Label',
                                    'value' => 'Author Info',
                                ],
                                'custom_block_id' => [
                                    'type'  => 'text',
                                    'label'  => 'Custom block ID',
                                    'value' => '',
                                ],
                                'custom_block_classes' => [
                                    'type'  => 'text',
                                    'label'  => 'Custom block Classes',
                                    'value' => '',
                                ],
                            ]
                        ],
                        'contact_listings_owner' => [
                            'type' => 'section',
                            'label' => 'Contact Listings Owner Form',
                            'icon' => 'la la-phone',
                            'options' => [
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Label',
                                    'value' => 'Contact Listings Owner Form',
                                ],
                                'icon' => [
                                    'type'  => 'icon',
                                    'label' => 'Icon',
                                    'value' => 'la la-phone',
                                ],
                                'custom_block_id' => [
                                    'type'  => 'text',
                                    'label'  => 'Custom block ID',
                                    'value' => '',
                                ],
                                'custom_block_classes' => [
                                    'type'  => 'text',
                                    'label'  => 'Custom block Classes',
                                    'value' => '',
                                ],
                            ]
                        ],
                        'related_listings' => [
                            'type' => 'section',
                            'label' => 'Related Listings',
                            'icon' => 'la la-copy',
                            'options' => [
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Label',
                                    'value' => 'Related Listings',
                                ],
                                'custom_block_id' => [
                                    'type'  => 'text',
                                    'label'  => 'Custom block ID',
                                    'value' => '',
                                ],
                                'custom_block_classes' => [
                                    'type'  => 'text',
                                    'label'  => 'Custom block Classes',
                                    'value' => '',
                                ],
                            ]
                        ],
                    ],
                ],
            ];

            $search_form_widgets = [
                'available_widgets' => [
                    'title' => 'Preset Fields',
                    'description' => 'Click on a field to use it',
                    'allow_multiple' => false,
                    'template' => 'submission_form_fields',
                    'widgets' => [
                        'title' => [
                            'label' => 'Search Bar',
                            'options' => [
                                'required' => [
                                    'type'  => 'toggle',
                                    'label' => 'Required',
                                    'value' => false,
                                ],
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Label',
                                    'value' => '',
                                    'sync' => false,
                                ],
                                'placeholder' => [
                                    'type'  => 'text',
                                    'label' => 'Placeholder',
                                    'value' => 'What are you looking for?',
                                ],
                            ],
                        ],

                        'category' => [
                            'options' => [
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Label',
                                    'value' => '',
                                    'sync' => false,
                                ],
                                'placeholder' => [
                                    'type'  => 'text',
                                    'label'  => 'Placeholder',
                                    'value' => 'Category',
                                ],
                            ]
                        ],

                        'location' => [
                            'options' => [
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Label',
                                    'value' => '',
                                    'sync' => false,
                                ],
                                'placeholder' => [
                                    'type'  => 'text',
                                    'label'  => 'Placeholder',
                                    'value' => 'Location',
                                ],
                                'location_source' => [
                                    'type'  => 'select',
                                    'label'  => 'Location Source',
                                    'options' => [
                                        [
                                            'label' => __('Display from Listing Location', 'directorist'),
                                            'value' => 'from_listing_location',
                                        ],
                                        [
                                            'label' => __('Display from Map API', 'directorist'),
                                            'value' => 'from_map_api',
                                        ],
                                    ],
                                    'value' => 'from_listing_location',
                                ],
                            ]
                        ],

                        'tag' => [
                            'options' => [
                                'label' => [
                                    'type'  => 'text',
                                    'label'  => 'Label',
                                    'value' => 'Tag',
                                ],
                                'tags_filter_source' => [
                                    'type'  => 'select',
                                    'label' => 'Tags Filter Source',
                                    'options' => [
                                        [
                                            'label' => __('All Tags', 'directorist'),
                                            'value' => 'all_tags',
                                        ],
                                        [
                                            'label' => __('Category Based Tags', 'directorist'),
                                            'value' => 'category_based_tags',
                                        ],
                                    ],
                                    'value' => 'all_tags',
                                ],
                            ]
                        ],

                        'pricing' => [
                            'options' => [
                                'price_range_min_placeholder' => [
                                    'type'  => 'text',
                                    'label'  => 'Price Range Min Placeholder',
                                    'value' => 'Min',
                                ],
                                'price_range_max_placeholder' => [
                                    'type'  => 'text',
                                    'label'  => 'Price Range Max Placeholder',
                                    'value' => 'Max',
                                ],
                            ]
                        ],

                        'address' => [
                            'options' => [
                                'label' => [
                                    'type'  => 'text',
                                    'label'  => 'Label',
                                    'value' => 'Tag',
                                ],
                                'placeholder' => [
                                    'type'  => 'text',
                                    'label'  => 'Placeholder',
                                    'value' => 'Address',
                                ],
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                            ]
                        ],

                        'zip' => [
                            'options' => [
                                'label' => [
                                    'type'  => 'text',
                                    'label'  => 'Label',
                                    'value' => 'Tag',
                                ],
                                'placeholder' => [
                                    'type'  => 'text',
                                    'label'  => 'Placeholder',
                                    'value' => 'Zip',
                                ],
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                            ]
                        ],

                        'phone' => [
                            'options' => [
                                'label' => [
                                    'type'  => 'text',
                                    'label'  => 'Label',
                                    'value' => 'Tag',
                                ],
                                'placeholder' => [
                                    'type'  => 'text',
                                    'label' => 'Placeholder',
                                    'value' => 'Phone',
                                ],
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                            ]
                        ],

                        'phone2' => [
                            'options' => [
                                'label' => [
                                    'type'  => 'text',
                                    'label'  => 'Label',
                                    'value' => 'Tag',
                                ],
                                'placeholder' => [
                                    'type'  => 'text',
                                    'label' => 'Placeholder',
                                    'value' => 'Phone 2',
                                ],
                                'required' => [
                                    'type'  => 'toggle',
                                    'label' => 'Required',
                                    'value' => false,
                                ],
                            ]
                        ],

                        'email' => [
                            'options' => [
                                'label' => [
                                    'type'  => 'text',
                                    'label'  => 'Label',
                                    'value' => 'Tag',
                                ],
                                'placeholder' => [
                                    'type'  => 'text',
                                    'label' => 'Placeholder',
                                    'value' => 'Email',
                                ],
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                            ]
                        ],

                        'fax' => [
                            'options' => [
                                'label' => [
                                    'type'  => 'text',
                                    'label'  => 'Label',
                                    'value' => 'Fax',
                                ],
                                'placeholder' => [
                                    'type'  => 'text',
                                    'label' => 'Placeholder',
                                    'value' => 'Fax',
                                ],
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                            ]
                        ],

                        'website' => [
                            'options' => [
                                'label' => [
                                    'type'  => 'text',
                                    'label'  => 'Label',
                                    'value' => 'Tag',
                                ],
                                'placeholder' => [
                                    'type'  => 'text',
                                    'label'  => 'Placeholder',
                                    'value' => 'Website',
                                ],
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                            ]
                        ],
                       
                        'text' => [
                            'options' => [
                                'label' => [
                                    'type'  => 'text',
                                    'label'  => 'Label',
                                    'value' => 'Tag',
                                ],
                                'placeholder' => [
                                    'type'  => 'text',
                                    'label' => 'Placeholder',
                                    'value' => 'Text',
                                ],
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                            ]

                        ],

                        'textarea' => [
                            'options' => [
                                'label' => [
                                    'type'  => 'text',
                                    'label'  => 'Label',
                                    'value' => 'Tag',
                                ],
                                'placeholder' => [
                                    'type'  => 'text',
                                    'label' => 'Placeholder',
                                    'value' => 'Textarea',
                                ],
                                'required' => [
                                    'type'  => 'toggle',
                                    'label' => 'Required',
                                    'value' => false,
                                ],
                            ]

                        ],

                        'number' => [
                            'options' => [
                                'label' => [
                                    'type'  => 'text',
                                    'label'  => 'Label',
                                    'value' => 'Tag',
                                ],
                                'placeholder' => [
                                    'type'  => 'text',
                                    'label' => 'Placeholder',
                                    'value' => 'Number',
                                ],
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                            ]

                        ],

                        'url' => [
                            'options' => [
                                'label' => [
                                    'type'  => 'text',
                                    'label'  => 'Label',
                                    'value' => 'Tag',
                                ],
                                'placeholder' => [
                                    'type'  => 'text',
                                    'label'  => 'Placeholder',
                                    'value' => 'URL',
                                ],
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                            ]

                        ],

                        'date' => [
                            'options' => [
                                'label' => [
                                    'type'  => 'text',
                                    'label'  => 'Label',
                                    'value' => 'Tag',
                                ],
                                'placeholder' => [
                                    'type'  => 'text',
                                    'label'  => 'Placeholder',
                                    'value' => 'Date',
                                ],
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                            ]

                        ],

                        'time' => [
                            'options' => [
                                'label' => [
                                    'type'  => 'text',
                                    'label' => 'Label',
                                    'value' => 'Tag',
                                ],
                                'placeholder' => [
                                    'type'  => 'text',
                                    'label' => 'Placeholder',
                                    'value' => 'Time',
                                ],
                                'required' => [
                                    'type'  => 'toggle',
                                    'label' => 'Required',
                                    'value' => false,
                                ],
                            ]

                        ],

                        'color_picker' => [
                            'options' => [
                                'label' => [
                                    'type'  => 'text',
                                    'label'  => 'Label',
                                    'value' => 'Tag',
                                ],
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                            ]

                        ],

                        'select' => [
                            'options' => [
                                'label' => [
                                    'type'  => 'text',
                                    'label'  => 'Label',
                                    'value' => 'Tag',
                                ],
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                            ]

                        ],

                        'checkbox' => [
                            'options' => [
                                'label' => [
                                    'type'  => 'text',
                                    'label'  => 'Label',
                                    'value' => 'Tag',
                                ],
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                            ]

                        ],

                        'radio' => [
                            'options' => [
                                'label' => [
                                    'type'  => 'text',
                                    'label'  => 'Label',
                                    'value' => 'Tag',
                                ],
                                'required' => [
                                    'type'  => 'toggle',
                                    'label'  => 'Required',
                                    'value' => false,
                                ],
                            ]

                        ],
                        
                    ],
                ],
                'other_widgets' => [
                    'title' => 'Other Fields',
                    'description' => 'Click on a field to use it',
                    'allow_multiple' => false,
                    'widgets' => [
                        'review' => [
                            'label' => 'Review',
                            'icon' => 'fa fa-star',
                            'options' => [
                                'label' => [
                                    'type'  => 'text',
                                    'label'  => 'Label',
                                    'value' => 'Review',
                                ],
                            ],
                        ],
                        'radius_search' => [
                            'label' => 'Radius Search',
                            'icon' => 'fa fa-map',
                            'options' => [
                                'label' => [
                                    'type'  => 'text',
                                    'label'  => 'Label',
                                    'value' => 'Radius Search',
                                ],
                                'default_radius_distance' => [
                                    'type'  => 'range',
                                    'label' => 'Default Radius Distance',
                                    'min'   => 0,
                                    'max'   => 750,
                                    'value' => 0,
                                ],
                                'radius_search_unit' => [
                                    'type'  => 'select',
                                    'label' => 'Radius Search Unit',
                                    'value' => 'miles',
                                    'options' => [
                                        [ 'value' => 'miles', 'label' => 'Miles' ],
                                        [ 'value' => 'kilometers', 'label' => 'Kilometers' ],
                                    ]
                                ],
                            ],
                        ],
                    ]
                ]
            ];

            $listing_card_widget = [
                'listing_title' => [
                    'type' => "title",
                    'label' => "Listing Title",
                    'icon' => 'uil uil-text-fields',
                    'hook' => "atbdp_listing_title",
                    'show_if' => [
                        'where' => "submission_form_fields.value.fields",
                        'conditions' => [
                            ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'title'],
                        ],
                    ],
                    'options' => [
                        'title' => "Listing Title Settings",
                        'fields' => [
                            'show_tagline' => [
                                'type' => "toggle",
                                'label' => "Show Tagline",
                                'value' => false,
                            ],
                        ],
                    ],
                ],

                'excerpt' => [
                    'type' => "excerpt",
                    'label' => "Excerpt",
                    'icon' => 'uil uil-text-fields',
                    'hook' => "atbdp_listing_title",
                    'show_if' => [
                        'where' => "submission_form_fields.value.fields",
                        'conditions' => [
                            ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'excerpt'],
                        ],
                    ],
                    'options' => [
                        'title' => "Excerpt Settings",
                        'fields' => [
                            'words_limit' => [
                                'type' => "range",
                                'label' => "Words Limit",
                                'min' => 5,
                                'max' => 200,
                                'value' => 20,
                            ],
                            'show_readmore' => [
                                'type' => "toggle",
                                'label' => "Show Readmore",
                                'value' => true,
                            ],
                            'show_readmore_text' => [
                                'type' => "text",
                                'label' => "Read More Text",
                                'value' => 'Read More',
                            ],
                        ],
                    ],
                ],

                'listings_location' => [
                    'type' => "list-item",
                    'label' => "Listings Location",
                    'icon' => 'uil uil-location-point',
                    'hook' => "atbdp_listings_location",
                    'show_if' => [
                        'where' => "submission_form_fields.value.fields",
                        'conditions' => [
                            ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'location'],
                        ],
                    ],
                    'options' => [
                        'title' => "Listings Location Settings",
                        'fields' => [
                            'icon' => [
                                'type' => "icon",
                                'label' => "Icon",
                                'value' => "la la-map-marker",
                            ],
                        ],
                    ],
                ],
                
                'posted_date' => [
                    'type' => "list-item",
                    'label' => "Posted Date",
                    'icon' => 'la la-clock-o',
                    'hook' => "atbdp_listings_posted_date",
                    'options' => [
                        'title' => "Posted Date",
                        'fields' => [
                            'icon' => [
                                'type' => "icon",
                                'label' => "Icon",
                                'value' => "la la-clock-o",
                            ],
                            'date_type' => [
                                'type' => "radio",
                                'label' => "Date Type",
                                'options' => [
                                    [ 'id' => 'atbdp_days_ago', 'label' => 'Days Ago', 'value' => 'days_ago' ],
                                    [ 'id' => 'atbdp_posted_date', 'label' => 'Posted Date', 'value' => 'post_date' ],
                                ],
                                'value' => "post_date",
                            ],
                        ],
                    ],
                ],

                'website' => [
                    'type' => "list-item",
                    'label' => "Listings Website",
                    'icon' => 'la la-globe',
                    'hook' => "atbdp_listings_website",
                    'show_if' => [
                        'where' => "submission_form_fields.value.fields",
                        'conditions' => [
                            ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'website'],
                        ],
                    ],
                    'options' => [
                        'title' => "Listings Website Settings",
                        'fields' => [
                            'icon' => [
                                'type' => "icon",
                                'label' => "Icon",
                                'value' => "la la-globe",
                            ],
                        ],
                    ],
                ],

                'zip' => [
                    'type' => "list-item",
                    'label' => "Listings Zip",
                    'icon' => 'la la-at',
                    'hook' => "atbdp_listings_zip",
                    'show_if' => [
                        'where' => "submission_form_fields.value.fields",
                        'conditions' => [
                            ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'zip'],
                        ],
                    ],
                    'options' => [
                        'title' => "Listings Zip Settings",
                        'fields' => [
                            'icon' => [
                                'type' => "icon",
                                'label' => "Icon",
                                'value' => "la la-at",
                            ],
                        ],
                    ],
                ],

                'email' => [
                    'type' => "list-item",
                    'label' => "Listings Email",
                    'icon' => 'la la-envelope',
                    'hook' => "atbdp_listings_email",
                    'show_if' => [
                        'where' => "submission_form_fields.value.fields",
                        'conditions' => [
                            ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'email'],
                        ],
                    ],
                    'options' => [
                        'title' => "Listings Email Settings",
                        'fields' => [
                            'icon' => [
                                'type' => "icon",
                                'label' => "Icon",
                                'value' => "la la-envelope",
                            ],
                        ],
                    ],
                ],

                'fax' => [
                    'type' => "list-item",
                    'label' => "Listings Fax",
                    'icon' => 'la la-fax',
                    'hook' => "atbdp_listings_fax",
                    'show_if' => [
                        'where' => "submission_form_fields.value.fields",
                        'conditions' => [
                            ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'fax'],
                        ],
                    ],
                    'options' => [
                        'title' => "Listings Fax Settings",
                        'fields' => [
                            'icon' => [
                                'type' => "icon",
                                'label' => "Icon",
                                'value' => "la la-fax",
                            ],
                        ],
                    ],
                ],

                'phone' => [
                    'type' => "list-item",
                    'label' => "Listings Phone",
                    'icon' => 'la la-phone',
                    'hook' => "atbdp_listings_phone",
                    'show_if' => [
                        'where' => "submission_form_fields.value.fields",
                        'conditions' => [
                            ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'phone'],
                        ],
                    ],
                    'options' => [
                        'title' => "Listings Phone Settings",
                        'fields' => [
                            'icon' => [
                                'type' => "icon",
                                'label' => "Icon",
                                'value' => "la la-phone",
                            ],
                        ],
                    ],
                ],

                'phone2' => [
                    'type' => "list-item",
                    'label' => "Listings Phone 2",
                    'icon' => 'la la-phone',
                    'hook' => "atbdp_listings_phone2",
                    'show_if' => [
                        'where' => "submission_form_fields.value.fields",
                        'conditions' => [
                            ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'phone2'],
                        ],
                    ],
                    'options' => [
                        'title' => "Listings Phone 2 Settings",
                        'fields' => [
                            'icon' => [
                                'type' => "icon",
                                'label' => "Icon",
                                'value' => "la la-phone",
                            ],
                        ],
                    ],
                ],

                'address' => [
                    'type' => "list-item",
                    'label' => "Listings Address",
                    'icon' => 'la la-map-marker',
                    'hook' => "atbdp_listings_map_address",
                    'show_if' => [
                        'where' => "submission_form_fields.value.fields",
                        'conditions' => [
                            ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'address'],
                        ],
                    ],
                    'options' => [
                        'title' => "Listings Address Settings",
                        'fields' => [
                            'icon' => [
                                'type' => "icon",
                                'label' => "Icon",
                                'value' => "la la-map-marker",
                            ],
                        ],
                    ],
                ],

                'pricing' => [
                    'type' => "price",
                    'label' => "Listings Price",
                    'icon' => 'uil uil-text-fields',
                    'hook' => "atbdp_single_listings_price",
                    'show_if' => [
                        'where' => "submission_form_fields.value.fields",
                        'conditions' => [
                            ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'pricing'],
                        ],
                    ],
                ],

                'rating' => [
                    'type' => "rating",
                    'label' => "Rating",
                    'hook' => "atbdp_listings_rating",
                    'icon' => 'uil uil-text-fields',
                ],

                'featured_badge' => [
                    'type' => "badge",
                    'label' => "Featured",
                    'icon' => 'uil uil-text-fields',
                    'hook' => "atbdp_featured_badge",
                ],

                'new_badge' => [
                    'type' => "badge",
                    'label' => "New",
                    'icon' => 'uil uil-text-fields',
                    'hook' => "atbdp_new_badge",
                ],

                'popular_badge' => [
                    'type' => "badge",
                    'label' => "Popular",
                    'icon' => 'uil uil-text-fields',
                    'hook' => "atbdp_popular_badge",
                ],

                'favorite_badge' => [
                    'type' => "icon",
                    'label' => "Favorite",
                    'icon' => 'uil uil-text-fields',
                    'hook' => "atbdp_favorite_badge",
                ],

                'view_count' => [
                    'type' => "view-count",
                    'label' => "View Count",
                    'icon' => 'uil uil-text-fields',
                    'hook' => "atbdp_view_count",
                    'show_if' => [
                        'where' => "submission_form_fields.value.fields",
                        'conditions' => [
                            ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'view_count'],
                        ],
                    ],
                    'options' => [
                        'title' => "View Count Settings",
                        'fields' => [
                            'icon' => [
                                'type' => "icon",
                                'label' => "Icon",
                                'value' => "fa fa-heart",
                            ],
                        ],
                    ],
                ],

                'category' => [
                    'type' => "category",
                    'label' => "Category",
                    'icon' => 'uil uil-text-fields',
                    'hook' => "atbdp_category",
                    'show_if' => [
                        'where' => "submission_form_fields.value.fields",
                        'conditions' => [
                            ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'category'],
                        ],
                    ],
                    'options' => [
                        'title' => "Category Settings",
                        'fields' => [
                            'icon' => [
                                'type' => "icon",
                                'label' => "Icon",
                                'value' => "fa fa-folder",
                            ],
                        ],
                    ],
                ],

                'user_avatar' => [
                    'type' => "avatar",
                    'label' => "User Avatar",
                    'icon' => 'uil uil-text-fields',
                    'hook' => "atbdp_user_avatar",
                    'can_move' => false,
                    'options' => [
                        'title' => "User Avatar Settings",
                        'fields' => [
                            'align' => [
                                'type' => "radio",
                                'label' => "Align",
                                'value' => "center",
                                'options' => [
                                    [ 'id' => 'atbdp_user_avatar_align_right', 'label' => 'Right', 'value' => 'right' ],
                                    [ 'id' => 'atbdp_user_avatar_align_center', 'label' => 'Center', 'value' => 'center' ],
                                    [ 'id' => 'atbdp_user_avatar_align_left', 'label' => 'Left', 'value' => 'left' ],
                                ],
                            ],
                        ],
                    ],
                ],
                
                // Custom Fields
                'text' => [
                    'type' => "list-item",
                    'label' => "Text",
                    'icon' => 'uil uil-text-fields',
                    'hook' => "atbdp_custom_text",
                    'show_if' => [
                        'where' => "submission_form_fields.value.fields",
                        'conditions' => [
                            ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'text'],
                        ],
                    ],
                    'options' => [
                        'title' => "Text Settings",
                        'fields' => [
                            'icon' => [
                                'type' => "icon",
                                'label' => "Icon",
                                'value' => "uil uil-text-fields",
                            ],
                        ],
                    ],
                ],
                
                'number' => [
                    'type' => "list-item",
                    'label' => "Number",
                    'icon' => 'uil uil-text-fields',
                    'hook' => "atbdp_custom_number",
                    'show_if' => [
                        'where' => "submission_form_fields.value.fields",
                        'conditions' => [
                            ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'number'],
                        ],
                    ],
                    'options' => [
                        'title' => "Number Settings",
                        'fields' => [
                            'icon' => [
                                'type' => "icon",
                                'label' => "Icon",
                                'value' => "uil uil-text-fields",
                            ],
                        ],
                    ],
                ],

                'url' => [
                    'type' => "list-item",
                    'label' => "URL",
                    'icon' => 'uil uil-text-fields',
                    'hook' => "atbdp_custom_url",
                    'show_if' => [
                        'where' => "submission_form_fields.value.fields",
                        'conditions' => [
                            ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'url'],
                        ],
                    ],
                    'options' => [
                        'title' => "URL Settings",
                        'fields' => [
                            'icon' => [
                                'type' => "icon",
                                'label' => "Icon",
                                'value' => "uil uil-text-fields",
                            ],
                        ],
                    ],
                ],

                'date' => [
                    'type' => "list-item",
                    'label' => "Date",
                    'icon' => 'uil uil-text-fields',
                    'hook' => "atbdp_custom_date",
                    'show_if' => [
                        'where' => "submission_form_fields.value.fields",
                        'conditions' => [
                            ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'date'],
                        ],
                    ],
                    'options' => [
                        'title' => "Date Settings",
                        'fields' => [
                            'icon' => [
                                'type' => "icon",
                                'label' => "Icon",
                                'value' => "uil uil-text-fields",
                            ],
                        ],
                    ],
                ],

                'time' => [
                    'type' => "list-item",
                    'label' => "Time",
                    'icon' => 'uil uil-text-fields',
                    'hook' => "atbdp_custom_time",
                    'show_if' => [
                        'where' => "submission_form_fields.value.fields",
                        'conditions' => [
                            ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'time'],
                        ],
                    ],
                    'options' => [
                        'title' => "Time Settings",
                        'fields' => [
                            'icon' => [
                                'type' => "icon",
                                'label' => "Icon",
                                'value' => "uil uil-text-fields",
                            ],
                        ],
                    ],
                ],

                'color_picker' => [
                    'type' => "list-item",
                    'label' => "Color Picker",
                    'icon' => 'uil uil-text-fields',
                    'hook' => "atbdp_custom_color",
                    'show_if' => [
                        'where' => "submission_form_fields.value.fields",
                        'conditions' => [
                            ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'color'],
                        ],
                    ],
                    'options' => [
                        'title' => "Color Picker Settings",
                        'fields' => [
                            'icon' => [
                                'type' => "icon",
                                'label' => "Icon",
                                'value' => "uil uil-text-fields",
                            ],
                        ],
                    ],
                ],

                'select' => [
                    'type' => "list-item",
                    'label' => "Select",
                    'icon' => 'uil uil-text-fields',
                    'hook' => "atbdp_custom_select",
                    'show_if' => [
                        'where' => "submission_form_fields.value.fields",
                        'conditions' => [
                            ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'select'],
                        ],
                    ],
                    'options' => [
                        'title' => "Select Settings",
                        'fields' => [
                            'icon' => [
                                'type' => "icon",
                                'label' => "Icon",
                                'value' => "uil uil-text-fields",
                            ],
                        ],
                    ],
                ],

                'checkbox' => [
                    'type' => "list-item",
                    'label' => "Checkbox",
                    'icon' => 'uil uil-text-fields',
                    'hook' => "atbdp_custom_checkbox",
                    'show_if' => [
                        'where' => "submission_form_fields.value.fields",
                        'conditions' => [
                            ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'checkbox'],
                        ],
                    ],
                    'options' => [
                        'title' => "Checkbox Settings",
                        'fields' => [
                            'icon' => [
                                'type' => "icon",
                                'label' => "Icon",
                                'value' => "uil uil-text-fields",
                            ],
                        ],
                    ],
                ],

                'radio' => [
                    'type' => "list-item",
                    'label' => "Radio",
                    'icon' => 'uil uil-text-fields',
                    'hook' => "atbdp_custom_radio",
                    'show_if' => [
                        'where' => "submission_form_fields.value.fields",
                        'conditions' => [
                            ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'radio'],
                        ],
                    ],
                    'options' => [
                        'title' => "Radio Settings",
                        'fields' => [
                            'icon' => [
                                'type' => "icon",
                                'label' => "Icon",
                                'value' => "uil uil-text-fields",
                            ],
                        ],
                    ],
                ],
            ];


            $listing_card_list_view_widget = $listing_card_widget;
            if ( ! empty( $listing_card_list_view_widget[ 'user_avatar' ] ) ) {
                $listing_card_list_view_widget[ 'user_avatar' ][ 'can_move' ] = true;

                if ( ! empty( $listing_card_list_view_widget[ 'user_avatar' ]['options'] ) ) {
                    unset( $listing_card_list_view_widget[ 'user_avatar' ]['options'] );
                }
            }

            $this->fields = apply_filters('atbdp_listing_type_settings_field_list', [
                'name' => [
                    'label' => 'Name *',
                    'type'  => 'text',
                    'value' => '',
                    'rules' => [
                        'required' => true,
                    ],
                ],

                'icon' => [
                    'label' => 'Icon',
                    'type'  => 'icon',
                    'value' => '',
                    'rules' => [
                        'required' => false,
                    ],
                ],

                'singular_name' => [
                    'label' => 'Singular name (e.g. Business)',
                    'type'  => 'text',
                    'value' => '',
                    'rules' => [
                        'required' => false,
                    ],
                ],

                'plural_name' => [
                    'label' => 'Plural name (e.g. Businesses)',
                    'type'  => 'text',
                    'value' => '',
                    'rules' => [
                        'required' => false,
                    ],
                ],

                'permalink' => [
                    'label' => 'Permalink',
                    'type'  => 'text',
                    'value' => '',
                    'rules' => [
                        'required' => false,
                    ],
                ],

                'preview_image' => [
                    'label'       => __('Select', 'directorist'),
                    'type'        => 'wp-media-picker',
                    'default-img' => ATBDP_PUBLIC_ASSETS . 'images/grid.jpg',
                    'value'       => '',
                ],

                'import_export' => [
                    'type'  => 'export',
                ],

                'default_expiration' => [
                    'label' => __('Default expiration in days', 'directorist'),
                    'type'  => 'number',
                    'value' => 30,
                    'placeholder' => '365',
                    'rules' => [
                        'required' => true,
                    ],
                ],

                'new_listing_status' => [
                    'label' => __('New Listing Default Status', 'directorist'),
                    'type'  => 'select',
                    'value' => 'pending',
                    'options' => [
                        [
                            'label' => __('Pending', 'directorist'),
                            'value' => 'pending',
                        ],
                        [
                            'label' => __('Publish', 'directorist'),
                            'value' => 'publish',
                        ],
                    ],
                ],

                'edit_listing_status' => [
                    'label' => __('Edited Listing Default Status', 'directorist'),
                    'type'  => 'select',
                    'value' => 'pending',
                    'options' => [
                        [
                            'label' => __('Pending', 'directorist'),
                            'value' => 'pending',
                        ],
                        [
                            'label' => __('Publish', 'directorist'),
                            'value' => 'publish',
                        ],
                    ],
                ],

                'global_listing_type' => [
                    'label' => __('Global Listing Type', 'directorist'),
                    'type'  => 'toggle',
                    'value' => '',
                ],

                'submission_form_fields' => apply_filters( 'atbdp_listing_type_form_fields', [
                    'type'    => 'form-builder',
                    'widgets' => $form_field_widgets,
                    'group-options' => [
                        'label' => [
                            'type'  => 'text',
                            'label' => 'Group Name',
                            'value' => 'Section',
                        ],
                    ],

                    'value' => [
                        'fields' => [
                            'title' => [
                                'widget_group' => 'preset',
                                'widget_name' => 'title',
                                'type'        => 'text',
                                'field_key'   => 'listing_title',
                                'required'    => true,
                                'label'       => 'Title',
                                'placeholder' => '',
                            ],
                        ],
                        'groups' => [
                            [
                                'label' => 'General Section',
                                'lock' => true,
                                'fields' => ['title'],
                                'plans' => []
                            ],
                        ]
                    ],

                ] ),

                // Submission Settings
                'preview_mode' => [
                    'label' => __('Enable Preview', 'directorist'),
                    'type'  => 'toggle',
                    'value' => true,
                ],
                'submit_button_label' => [
                    'label' => __('Submit Button Label', 'directorist'),
                    'type'  => 'text',
                    'value' => __('Save & Preview', 'directorist'),
                ],

                // Submit Button
                'submit_button_label' => [
                    'label' => __('Submit Button Label', 'directorist'),
                    'type'  => 'text',
                    'value' => __('Save & Preview', 'directorist'),
                ],

                // TERMS AND CONDITIONS
                'listing_terms_condition' => [
                    'label' => __('Enable', 'directorist'),
                    'type'  => 'toggle',
                    'value' => true,
                ],
                'require_terms_conditions' => [
                    'label' => __('Required', 'directorist'),
                    'type'  => 'toggle',
                    'value' => true,
                ],
                'terms_label' => [
                    'label'       => __('Label', 'directorist'),
                    'type'        => 'text',
                    'description' => 'Place the linking text between two <code>%</code> mark. Ex: %link% ',
                    'value'       => 'I agree with all %terms & conditions%',
                ],

                // PRIVACY AND POLICY
                'listing_privacy' => [
                    'label' => __('Enable', 'directorist'),
                    'type'  => 'toggle',
                    'value' => true,
                ],
                'require_privacy' => [
                    'label' => __('Required', 'directorist'),
                    'type'  => 'toggle',
                    'value' => true,
                ],
                'privacy_label' => [
                    'label' => __('Label', 'directorist'),
                    'type'  => 'text',
                    'description' => 'Place the linking text between two <code>%</code> mark. Ex: %link% ',
                    'value' => 'I agree to the %Privacy & Policy%',
                ],
                
                'single_listings_contents' => [
                    'type'     => 'form-builder',
                    'widgets'  => $single_listings_contents_widgets,
                    'group-options' => [
                        'icon' => [
                            'type'  => 'icon',
                            'label'  => 'Block/Section Icon',
                            'value' => '',
                        ],
                        'label' => [
                            'type'  => 'text',
                            'label' => 'Label',
                            'value' => 'Section',
                        ],
                        'custom_block_id' => [
                            'type'  => 'text',
                            'label'  => 'Custom block ID',
                            'value' => '',
                        ],
                        'custom_block_classes' => [
                            'type'  => 'text',
                            'label'  => 'Custom block Classes',
                            'value' => '',
                        ],
                    ],
                    'value' => [],
                ],
                

                'enable_similar_listings' => [
                    'type'  => 'toggle',
                    'label' => 'Enable similar listings',
                    'value' => true,
                ],
                'similar_listings_title' => [
                    'type'  => 'text',
                    'label' => 'Section Title',
                    'value' => 'Similar Listings',
                    /* 'show_if' => [
                        'id' => "enable_similar_listings__title",
                        'where' => "enable_similar_listings",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ], */
                ],
                'similar_listings_logics' => [
                    'type'    => 'radio',
                    'name'    => 'similar_listings_logics',
                    'label' => 'Similar listings logics',
                    'options' => [
                        ['id' => 'match_category_nd_location', 'label' => 'Must match category and location', 'value' => 'AND'],
                        ['id' => 'match_category_or_location', 'label' => 'Must match category or location', 'value' => 'OR'],
                    ],
                    'value'   => 'OR',
                ],
                'similar_listings_number_of_listings_to_show' => [
                    'type'  => 'range',
                    'min'   => 0,
                    'max'   => 20,
                    'label' => 'Number of listings to show',
                    'value' => 0,
                ],
                'similar_listings_number_of_columns' => [
                    'type'  => 'range',
                    'min'   => 1,
                    'max'   => 10,
                    'label' => 'Number of columns',
                    'value' => 3,
                ],

                'search_form_fields' => [
                    'type'     => 'form-builder',
                    'widgets'  => $search_form_widgets,
                    'allow_add_new_section' => false,
                    'value' => [
                        'groups' => [
                            [
                                'label'     => 'Basic',
                                'lock'      => true,
                                'draggable' => false,
                                'fields'    => [],
                            ],
                            [
                                'label'     => 'Advanced',
                                'lock'      => true,
                                'draggable' => false,
                                'fields'    => [],
                            ],
                        ]
                    ],
                ],

                'single_listing_header' => [
                    'type' => 'card-builder',
                    'template' => 'listing-header',
                    'value' => '',
                    'card-options' => [
                        'general' => [
                            'back' => [
                              'type' => "badge",
                              'label' => "Back",
                              'options' => [
                                  'title' => "Back Button Settings",
                                  'fields' => [
                                      'label' => [
                                          'type' => "toggle",
                                          'label' => "Enable",
                                          'value' => true,
                                      ],
                                  ],
                              ],
                            ],
                            'section_title' => [
                              'type' => "title",
                              'label' => "Section Title",
                              'options' => [
                                  'title' => "Section Title Options",
                                  'fields' => [
                                      'label' => [
                                          'type' => "text",
                                          'label' => "Label",
                                          'value' => "Section Title",
                                      ],
                                      'icon' => [
                                          'type' => "icon",
                                          'label' => "Icon",
                                          'value' => "",
                                      ],
                                  ],
                              ],
                            ],
                        ],
                        'content_settings' => [
                            'listing_title' => [
                              'type' => "title",
                              'label' => "Listing Title",
                              'options' => [
                                  'title' => "Listing Title Settings",
                                  'fields' => [
                                      'enable_title' => [
                                          'type' => "toggle",
                                          'label' => "Show Title",
                                          'value' => true,
                                      ],
                                      'enable_tagline' => [
                                          'type' => "toggle",
                                          'label' => "Show Tagline",
                                          'value' => true,
                                      ],
                                  ],
                              ],
                            ],
                            'listing_description' => [
                              'type' => "title",
                              'label' => "Description",
                              'options' => [
                                  'title' => "Description Settings",
                                  'fields' => [
                                    'enable' => [
                                        'type' => "toggle",
                                        'label' => "Show Description",
                                        'value' => true,
                                    ],
                                  ],
                              ],
                            ],
                        ],
                    ],
                    'options_layout' => [
                        'header' => ['back', 'section_title'],
                        'contents_area' => ['title_and_tagline', 'description'],
                    ],
                    'widgets' => [
                        'bookmark' => [
                            'type' => "button",
                            'label' => "Bookmark",
                            'icon' => 'la la-bookmark',
                        ],
                        'share' => [
                            'type' => "badge",
                            'label' => "Share",
                            'icon' => 'la la-share',
                            'options' => [
                                'title' => "Share Settings",
                                'fields' => [
                                    'icon' => [
                                        'type' => "icon",
                                        'label' => "Icon",
                                        'value' => 'la la-share',
                                    ],
                                ],
                            ],
                        ],
                        'report' => [
                            'type' => "badge",
                            'label' => "Report",
                            'icon' => 'la la-flag',
                            'options' => [
                                'title' => "Report Settings",
                                'fields' => [
                                    'icon' => [
                                        'type' => "icon",
                                        'label' => "Icon",
                                        'value' => 'la la-flag',
                                    ],
                                ],
                            ],
                        ],
                        
                        'listing_slider' => [
                            'type' => "thumbnail",
                            'label' => "Listings Slider",
                            'icon' => 'uil uil-text-fields',
                            'can_move' => false,
                            'show_if' => [
                                'where' => "submission_form_fields.value.fields",
                                'conditions' => [
                                    ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'image_upload'],
                                ],
                            ],
                            'options' => [
                                'title' => "Listings Slider Settings",
                                'fields' => [
                                    'footer_thumbail' => [
                                        'type' => "toggle",
                                        'label' => "Enable Footer Thumbail",
                                        'value' => true,
                                    ],
                                ],
                            ],
                        ],
                        'price' => [
                            'type' => "badge",
                            'label' => "Listings Price",
                            'icon' => 'uil uil-text-fields',
                        ],
                        'badges' => [
                            'type' => "badge",
                            'label' => "Badges",
                            'icon' => 'uil uil-text-fields',
                            'options' => [
                                'title' => "Badge Settings",
                                'fields' => [
                                    'new_badge' => [
                                        'type' => "toggle",
                                        'label' => "Display New Badge",
                                        'value' => true,
                                    ],
                                    'popular_badge' => [
                                        'type' => "toggle",
                                        'label' => "Display Popular Badge",
                                        'value' => true,
                                    ],
                                    'featured_badge' => [
                                        'type' => "toggle",
                                        'label' => "Display Featured Badge",
                                        'value' => true,
                                    ],
                                ],
                            ],
                        ],
                        
                        'reviews' => [
                            'type' => "reviews",
                            'label' => "Listings Reviews",
                            'icon' => 'uil uil-text-fields',
                        ],
                        'ratings_count' => [
                            'type' => "ratings-count",
                            'label' => "Listings Ratings",
                            'icon' => 'uil uil-text-fields',
                        ],
                        'category' => [
                            'type' => "badge",
                            'label' => "Listings Category",
                            'icon' => 'uil uil-text-fields',
                            'show_if' => [
                                'where' => "submission_form_fields.value.fields",
                                'conditions' => [
                                    ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'category'],
                                ],
                            ],
                        ],
                        'location' => [
                            'type' => "badge",
                            'label' => "Listings Location",
                            'icon' => 'uil uil-text-fields',
                            'show_if' => [
                                'where' => "submission_form_fields.value.fields",
                                'conditions' => [
                                    ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'location'],
                                ],
                            ],
                        ],
                    ],
 
                    'layout' => [
                        'listings_header' => [
                            'quick_actions' => [
                                'label' => 'Top Right',
                                'maxWidget' => 0,
                                'maxWidgetInfoText' => "Up to __DATA__ item{s} can be added",
                                'acceptedWidgets' => [ 'bookmark', 'share', 'report' ],
                            ],
                            'thumbnail' => [
                                'label' => 'Thumbnail',
                                'maxWidget' => 1,
                                'maxWidgetInfoText' => "Up to __DATA__ item{s} can be added",
                                'acceptedWidgets' => [ 'listing_slider' ],
                            ],
                            'quick_info' => [
                                'label' => 'Quick info',
                                'maxWidget' => 0,
                                'maxWidgetInfoText' => "Up to __DATA__ item{s} can be added",
                                'acceptedWidgets' => [ 'badges', 'price', 'reviews', 'ratings_count', 'category', 'location' ],
                            ],
                        ],
                    ],
                ],

                'listings_card_grid_view' => [
                    'type' => 'card-builder',
                    'template' => 'grid-view',
                    'value' => '',
                    'widgets' => $listing_card_widget,

                    'layout' => [
                        'thumbnail' => [
                            'top_right' => [
                                'label' => 'Top Right',
                                'maxWidget' => 3,
                                'maxWidgetInfoText' => "Up to __DATA__ item{s} can be added",
                                'acceptedWidgets' => ["favorite_badge", "popular_badge", "featured_badge", "new_badge"],
                            ],
                            'top_left' => [
                                'maxWidget' => 3,
                                'acceptedWidgets' => ["favorite_badge", "popular_badge", "featured_badge", "new_badge"],
                            ],
                            'bottom_right' => [
                                'maxWidget' => 2,
                                'acceptedWidgets' => ["favorite_badge", "popular_badge", "featured_badge", "new_badge"],
                            ],
                            'bottom_left' => [
                                'maxWidget' => 3,
                                'acceptedWidgets' => ["favorite_badge", "popular_badge", "featured_badge", "new_badge"],
                            ],
                            'avatar' => [
                                'maxWidget' => 1,
                                'acceptedWidgets' => ["user_avatar"],
                            ],
                        ],

                        'body' => [
                            'top' => [
                                'maxWidget' => 0,
                                'acceptedWidgets' => [
                                    "listing_title", "favorite_badge", "popular_badge", "featured_badge", "new_badge", "rating", "pricing",
                                ],
                            ],
                            'bottom' => [
                                'maxWidget' => 0,
                                'acceptedWidgets' => [
                                    "listings_location", "phone", "phone2", "website", "zip", "fax", "address", "email",
                                    'text', 'textarea', 'number', 'url', 'date', 'time', 'color_picker', 'select', 'checkbox', 'radio', 'file', 'posted_date',
                                ],
                            ],
                            'excerpt' => [
                                'maxWidget' => 1,
                                'acceptedWidgets' => [ "excerpt" ],
                                'show_if' => [
                                    'where' => "submission_form_fields.value.fields",
                                    'conditions' => [
                                        ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'excerpt'],
                                    ],
                                ],
                            ],
                        ],

                        'footer' => [
                            'right' => [
                                'maxWidget' => 2,
                                'acceptedWidgets' => ["category", "favorite_badge", "view_count"],
                            ],

                            'left' => [
                                'maxWidget' => 1,
                                'acceptedWidgets' => ["category", "favorite_badge", "view_count"],
                            ],
                        ],
                    ],
                ],

                'listings_card_list_view' => [
                    'type' => 'card-builder',
                    'template' => 'list-view',
                    'value' => '',
                    'widgets' => $listing_card_list_view_widget,
                    'layout' => [
                        'thumbnail' => [
                            'top_right' => [
                                'label' => 'Top Right',
                                'maxWidget' => 3,
                                'maxWidgetInfoText' => "Up to __DATA__ item{s} can be added",
                                'acceptedWidgets' => ["favorite_badge", "popular_badge", "featured_badge", "new_badge"],
                            ],
                        ],

                        'body' => [
                            'top' => [
                                'label' => 'Body Top',
                                'maxWidget' => 0,
                                'maxWidgetInfoText' => "Up to __DATA__ item{s} can be added",
                                'acceptedWidgets' => ["listing_title", "favorite_badge", "popular_badge", "featured_badge", "new_badge",  "rating", "pricing",],
                            ],
                            'right' => [
                                'label' => 'Body Right',
                                'maxWidget' => 2,
                                'maxWidgetInfoText' => "Up to __DATA__ item{s} can be added",
                                'acceptedWidgets' => ["favorite_badge", "popular_badge", "featured_badge", "new_badge"],
                            ],
                            'bottom' => [
                                'label' => 'Body Bottom',
                                'maxWidget' => 0,
                                'acceptedWidgets' => [
                                    "listings_location", "phone", "phone2", "website", "zip", "fax", "address", "email",
                                    'text', 'textarea', 'number', 'url', 'date', 'time', 'color_picker', 'select', 'checkbox', 'radio', 'file', 'posted_date'
                                ],
                            ],
                            'excerpt' => [
                                'maxWidget' => 1,
                                'acceptedWidgets' => [ "excerpt" ],
                                'show_if' => [
                                    'where' => "submission_form_fields.value.fields",
                                    'conditions' => [
                                        ['key' => '_any.widget_name', 'compare' => '=', 'value' => 'excerpt'],
                                    ],
                                ],
                            ],
                        ],

                        'footer' => [
                            'right' => [
                                'maxWidget' => 2,
                                'acceptedWidgets' => ["user_avatar", "category", "favorite_badge", "view_count"],
                            ],

                            'left' => [
                                'maxWidget' => 1,
                                'acceptedWidgets' => ["category", "favorite_badge", "view_count"],
                            ],
                        ],
                    ],
                ],

                // 'listings_card_height' => [
                //     'type' => 'number',
                //     'label' => 'Height',
                //     'value' => '250',
                //     'unit' => 'px',
                //     'units' => [
                //         ['label' => 'px', 'value' => 'px'],
                //         ['label' => '%', 'value' => '%'],
                //     ],
                // ],

                // 'listings_card_width' => [
                //     'type' => 'number',
                //     'label' => 'Width',
                //     'value' => '100',
                //     'unit' => '%',
                //     'units' => [
                //         ['label' => 'px', 'value' => 'px'],
                //         ['label' => '%', 'value' => '%'],
                //     ],
                // ],

            ]);

            $pricing_plan = '<a style="color: red" href="https://directorist.com/product/directorist-pricing-plans" target="_blank">Pricing Plans</a>';
            $wc_pricing_plan = '<a style="color: red" href="https://directorist.com/product/directorist-woocommerce-pricing-plans" target="_blank">WooCommerce Pricing Plans</a>';
            $plan_promo = sprintf(__('Monetize your website by selling listing plans using %s or %s extensions.', 'directorist'), $pricing_plan, $wc_pricing_plan);
            
            $this->layouts = apply_filters('atbdp_listing_type_settings_layout', [
                'general' => [
                    'label' => 'General',
                    'icon' => '<i class="uil uil-estate"></i>',
                    'submenu' => apply_filters('atbdp_listing_type_general_submenu', [
                        'general' => [
                            'label' => __('General', 'directorist'),
                            'sections' => [
                                'labels' => [
                                    'title'       => __('Labels', 'directorist'),
                                    'description' => '',
                                    'fields'      => [
                                        'name', 'icon', 'singular_name', 'plural_name', 'permalink',
                                    ],
                                ],
                            ],
                        ],
                        'preview_image' => [
                            'label' => __('Preview Image', 'directorist'),
                            'sections' => [
                                'labels' => [
                                    'title'       => __('Default Preview Image', 'directorist'),
                                    'description' => __('This image will be used when listing preview image is not present. Leave empty to hide the preview image completely.', 'directorist'),
                                    'fields'      => [
                                        'preview_image',
                                    ],
                                ],
                            ],
                        ],
                        'packages' => [
                            'label' => 'Packages',
                            'sections' => [
                                'packages' => [
                                    'title'       => 'Paid listing packages',
                                    'description' => $plan_promo,
                                ],
                            ],
                        ],
                        'other' => [
                            'label' => __('Other', 'directorist'),
                            'sections' => [
                                'listing_status' => [
                                    'title' => __('Default Status', 'directorist'),
                                    'description' => __('Need help?', 'directorist'),
                                    'fields'      => [
                                        'new_listing_status',
                                        'edit_listing_status',
                                    ],
                                ],

                                'expiration' => [
                                    'title'       => __('Expiration', 'directorist'),
                                    'description' => __('Default time to expire a listing.', 'directorist'),
                                    'fields'      => [
                                        'default_expiration',
                                    ],
                                ],

                                'export_import' => [
                                    'title'       => __('Export The Config File', 'directorist'),
                                    'description' => __('Export all the form, layout and settings', 'directorist'),
                                    'fields'      => [
                                        'import_export',
                                    ],
                                ],
                            ],
                        ],
                    ]),
                ],

                'submission_form' => [
                    'label' => 'Submission Form',
                    'icon' => '<span class="uil uil-file-edit-alt"></span>',
                    'submenu' => [
                        'form_fields' => [
                            'label' => 'Form Fields',
                            'container' => 'wide',
                            'sections' => [
                                'form_fields' => [
                                    'title' => __('Select or create fields for this listing type', 'directorist'),
                                    'description' => 'need help?',
                                    'fields' => [
                                        'submission_form_fields'
                                    ],
                                ],
                            ],
                        ],
                        'settings' => [
                            'label' => 'Settings',
                            'sections' => [
                                'submittion_settings' => [
                                    'title' => __('Submittion Settings', 'directorist'),
                                    'container' => 'short-width',
                                    'fields' => [
                                        'preview_mode',
                                        'submit_button_label',
                                    ],
                                ],
                                /* 'guest_submission' => [
                                    'title' => __('Guest Submittion', 'directorist'),
                                    'container' => 'short-width',
                                    'fields' => [
                                        'guest_email_label',
                                        'guest_email_placeholder',
                                    ],
                                ], */
                                'terms_and_conditions' => [
                                    'title' => __('Terms and Conditions', 'directorist'),
                                    'container' => 'short-width',
                                    'fields' => [
                                        'listing_terms_condition',
                                        'require_terms_conditions',
                                        'terms_label',
                                    ],
                                ],
                                'privacy_and_policy' => [
                                    'title' => __('Privacy and Policy', 'directorist'),
                                    'container' => 'short-width',
                                    'fields' => [
                                        'listing_privacy',
                                        'require_privacy',
                                        'privacy_label',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],

                'single_page_layout' => [
                    'label' => 'Single Page Layout',
                    'icon' => '<span class="uil uil-credit-card"></span>',
                    'submenu' => [
                        'listing_header' => [
                            'label' => 'Listing Header',
                            'container' => 'wide',
                            'sections' => [
                                'listing_header' => [
                                    'title' => 'Listing Header',
                                    'title_align' => 'center',
                                    'fields' => [
                                        'single_listing_header'
                                    ],
                                ]
                            ]
                        ],
                        'contents' => [
                            'label' => 'Contents',
                            'container' => 'wide',
                            'sections' => [
                                'contents' => [
                                    'title' => 'Contents',
                                    'description' => 'need help?',
                                    'fields' => [
                                        'single_listings_contents'
                                    ],
                                ]
                            ]
                        ],
                        'similar_listings' => [
                            'label' => 'Other Settings',
                            'sections' => [
                                'other' => [
                                    'title' => 'Similar Listings',
                                    'fields' => [
                                        'enable_similar_listings',
                                        'similar_listings_title',
                                        'similar_listings_logics',
                                        'similar_listings_number_of_listings_to_show',
                                        'similar_listings_number_of_columns',
                                    ],
                                ]
                            ]
                        ],
                    ]
                ],
                'listings_card_layout' => [
                    'label' => 'Listings Card Layout',
                    'icon' => '<span class="uil uil-list-ul"></span>',
                    'submenu' => [
                        'grid_view' => [
                            'label' => 'Listings Card Grid Layout',
                            'container' => 'wide',
                            'sections' => [
                                'listings_card' => [
                                    'title' => __('Create and customize the listing card for grid view', 'directorist'),
                                    'title_align' => 'center',
                                    'description' => 'need help? Read the documentation or open a ticket in our helpdesk.',
                                    'fields' => [
                                        'listings_card_grid_view'
                                    ],
                                ],
                            ],
                        ],
                        'list_view' => [
                            'label' => 'Listings Card List Layout',
                            'container' => 'full-width',
                            'sections' => [
                                'listings_card' => [
                                    'title' => __('Create and customize the listing card for listing view', 'directorist'),
                                    'title_align' => 'center',
                                    'description' => 'need help?',
                                    'fields' => [
                                        'listings_card_list_view'
                                    ],
                                ],
                            ],
                        ],
                        // 'options' => [
                        //     'label' => 'Listings Card Options',
                        //     'sections' => [
                        //         'listings_card_options' => [
                        //             'title' => __('Customize the options', 'directorist'),
                        //             'description' => 'need help?',
                        //             'fields' => [
                        //                 'listings_card_height',
                        //                 'listings_card_width'
                        //             ],
                        //         ],
                        //     ],
                        // ],
                    ],

                ],
                'search_forms' => [
                    'label' => 'Search Forms',
                    'icon' => '<span class="uil uil-search"></span>',
                    'container' => 'wide',
                    'sections' => [
                        'form_fields' => [
                            'title' => __('Customize the search form for this listing type', 'directorist'),
                            'description' => 'need help?',
                            'fields' => [
                                'search_form_fields'
                            ],
                        ],
                    ],
                ],
            ]);


            // Conditional Fields
            // -----------------------------
            // Guest Submission
            if ( get_directorist_option( 'guest_listings', 1 ) == '1' ) {
                $this->fields['guest_email_label'] = [
                    'label' => __('Guest Email Label', 'directorist'),
                    'type'  => 'text',
                    'value' => 'Your Email',
                ];

                $this->fields['guest_email_placeholder'] = [
                    'label' => __('Guest Email Placeholder', 'directorist'),
                    'type'  => 'text',
                    'value' => 'example@email.com',
                ];

                $this->layouts['submission_form']['submenu']['settings']['sections']['guest_submission'] = [
                    'title' => __('Guest Submittion', 'directorist'),
                    'container' => 'short-width',
                    'fields' => [
                        'guest_email_label',
                        'guest_email_placeholder',
                    ],
                ];
            }

            $this->config = [
                'submission' => [
                    'url' => admin_url('admin-ajax.php'),
                    'with' => [ 'action' => 'save_post_type_data' ],
                ],
                'fields_group' => [
                    'general_config' => [
                        'icon',
                        'singular_name',
                        'plural_name',
                        'permalink',
                        'preview_image',
                        // 'archive_general' => [
                        //     'listings_card_height',
                        //     'listings_card_width',
                        // ]
                    ]
                ]
            ];
        }

        // add_menu_pages
        public function add_menu_pages()
        {
            add_submenu_page(
                'edit.php?post_type=at_biz_dir',
                'Directory Types',
                'Directory Types',
                'manage_options',
                'atbdp-directory-types',
                [$this, 'menu_page_callback__directory_types'],
                5
            );
        }

        // menu_page_callback__directory_types
        public function menu_page_callback__directory_types()
        {
            $post_types_list_table = new Listing_Types_List_Table($this);

            $action = $post_types_list_table->current_action();
            $post_types_list_table->prepare_items();

            $listing_type_id = 0;

            if ( ! empty( $action ) && ( 'edit' === $action ) && ! empty( $_REQUEST['listing_type_id'] ) ) {
                $listing_type_id = $_REQUEST['listing_type_id'];
                $this->update_fields_with_old_data();
            }

            $data = [
                'post_types_list_table' => $post_types_list_table,
                'add_new_link'          => admin_url('edit.php?post_type=at_biz_dir&page=atbdp-directory-types&action=add_new'),
            ];

            $cptm_data = [
                'fields'  => $this->fields,
                'layouts' => $this->layouts,
                'config'  => $this->config,
                'id'      => $listing_type_id,
            ];

            $this->enqueue_scripts();

            if ( ! empty( $action ) && ('edit' === $action || 'add_new' === $action) ) {
                wp_localize_script('atbdp_admin_app', 'cptm_data', $cptm_data);
                atbdp_load_admin_template('post-types-manager/edit-listing-type', $data);

                return;
            }

            atbdp_load_admin_template('post-types-manager/all-listing-types', $data);
        }

        // update_fields_with_old_data
        public function update_fields_with_old_data()
        {
            $listing_type_id = absint($_REQUEST['listing_type_id']);
            $term = get_term($listing_type_id, 'atbdp_listing_types');

            if (!$term) {
                return;
            }

            $this->fields['name']['value'] = $term->name;

            $all_term_meta = get_term_meta($term->term_id);
            if ('array' !== getType($all_term_meta)) {
                return;
            }

            foreach ($all_term_meta as $meta_key => $meta_value) {
                if (isset($this->fields[$meta_key])) {
                    $value = maybe_unserialize(maybe_unserialize($meta_value[0]));
                    $this->fields[$meta_key]['value'] = $value;
                }
            }

            foreach ($this->config['fields_group'] as $group_key => $group_fields) {
                if (array_key_exists($group_key, $all_term_meta)) {
                    $group_value = maybe_unserialize(maybe_unserialize($all_term_meta[$group_key][0]));

                    foreach ($group_fields as $field_index => $field_key) {
                        if ('string' === gettype($field_key) && array_key_exists($field_key, $this->fields)) {
                            $this->fields[$field_key]['value'] = $group_value[$field_key];
                        }

                        if ('array' === gettype($field_key)) {
                            foreach ($field_key as $sub_field_key) {
                                if (array_key_exists($sub_field_key, $this->fields)) {
                                    $this->fields[$sub_field_key]['value'] = $group_value[$field_index][$sub_field_key];
                                }
                            }
                        }
                    }
                }
            }

            // $test = get_term_meta( $listing_type_id, 'single_listings_contents' )[0];
            // $test = get_term_meta( $listing_type_id, 'listings_card_grid_view' );
            // e_var_dump( $test );
            // var_dump( $test['fields']['video'] );
            // e_var_dump( $test );
            // e_var_dump( $this->fields[ 'single_listings_contents' ] );
            // var_dump( json_decode( $test ) );
        }

        // handle_delete_listing_type_request
        public function handle_delete_listing_type_request()
        {

            if (!wp_verify_nonce($_REQUEST['_wpnonce'], 'delete_listing_type')) {
                wp_die('Are you cheating? | _wpnonce');
            }

            if (!current_user_can('manage_options')) {
                wp_die('Are you cheating? | manage_options');
            }

            $term_id = isset($_REQUEST['listing_type_id']) ? absint($_REQUEST['listing_type_id']) : 0;

            $this->delete_listing_type($term_id);


            wp_redirect(admin_url('edit.php?post_type=at_biz_dir&page=atbdp-directory-types'));
            exit;
        }

        // delete_listing_type
        public function delete_listing_type($term_id = 0)
        {
            if (wp_delete_term($term_id, 'atbdp_listing_types')) {
                atbdp_add_flush_alert([
                    'id'      => 'deleting_listing_type_status',
                    'page'    => 'all-listing-type',
                    'message' => 'Successfully Deleted the listing type',
                ]);
            } else {
                atbdp_add_flush_alert([
                    'id'      => 'deleting_listing_type_status',
                    'page'    => 'all-listing-type',
                    'type'    => 'error',
                    'message' => 'Failed to delete the listing type'
                ]);
            }
        }

        // register_terms
        public function register_terms()
        {
            register_taxonomy('atbdp_listing_types', [ATBDP_POST_TYPE], [
                'hierarchical' => false,
                'labels' => [
                    'name' => _x('Listing Type', 'taxonomy general name', 'directorist'),
                    'singular_name' => _x('Listing Type', 'taxonomy singular name', 'directorist'),
                    'search_items' => __('Search Listing Type', 'directorist'),
                    'menu_name' => __('Listing Type', 'directorist'),
                ],
                'show_ui' => false,
            ]);

            $this->prepare_settings();
        }

        // enqueue_scripts
        public function enqueue_scripts()
        {
            wp_enqueue_media();
            wp_enqueue_style('atbdp-unicons');
            wp_enqueue_style('atbdp-font-awesome');
            wp_enqueue_style('atbdp-line-awesome');
            wp_enqueue_style('atbdp-select2-style');
            wp_enqueue_style('atbdp-select2-bootstrap-style');
            wp_enqueue_style('atbdp_admin_css');

            wp_localize_script('atbdp_admin_app', 'ajax_data', ['ajax_url' => admin_url('admin-ajax.php')]);
            wp_enqueue_script('atbdp_admin_app');
        }

        // register_scripts
        public function register_scripts()
        {
            wp_register_style('atbdp-unicons', '//unicons.iconscout.com/release/v3.0.3/css/line.css', false);
            wp_register_style('atbdp-select2-style', '//cdn.bootcss.com/select2/4.0.0/css/select2.css', false);
            wp_register_style('atbdp-select2-bootstrap-style', '//select2.github.io/select2-bootstrap-theme/css/select2-bootstrap.css', false);
            wp_register_style('atbdp-font-awesome', ATBDP_PUBLIC_ASSETS . 'css/font-awesome.min.css', false);
            wp_register_style( 'atbdp-line-awesome', ATBDP_PUBLIC_ASSETS . 'css/line-awesome.min.css', false, ATBDP_VERSION );
            // wp_register_style( 'atbdp-unicons-line', ATBDP_PUBLIC_ASSETS . 'css/unicons-line-min.css', false, ATBDP_VERSION );

            wp_register_style( 'atbdp_admin_css', ATBDP_PUBLIC_ASSETS . 'css/admin_app.css', [], '1.0' );
            wp_register_script( 'atbdp_admin_app', ATBDP_PUBLIC_ASSETS . 'js/admin_app.js', ['jquery'], false, true );
        }
    }
}
