<?php

namespace Directorist;
class Multi_Directory_Migration {

    public $multi_directory_manager = null;

    public function __construct( array $args = [] ) {
        if ( isset( $args['multi_directory_manager'] ) ) {
            $this->multi_directory_manager = $args['multi_directory_manager'];
        }
    }

    public function migrate( array $args = [] ) {
        $default = [];
        $args = array_merge( $default, $args );

        $fields = $this->get_fields_data();
        $add_directory_args = [
            'directory_name' => 'General',
            'fields_value'   => $fields,
        ];

        $add_directory_args = array_merge( $add_directory_args, $args );
        $add_directory      = $this->multi_directory_manager->add_directory( $add_directory_args );
        
        if ( $add_directory['status']['success'] ) {
            $directory_types = get_terms([
                'taxonomy'   => ATBDP_DIRECTORY_TYPE,
                'hide_empty' => false,
            ]);
    
            if ( ! empty( $directory_types ) ) {
                foreach ( $directory_types as $directory_type ) {
                    update_term_meta( $directory_type->term_id, '_default', false );
                }
            }
    
            update_term_meta( $add_directory['term_id'], '_default', true );
            update_option( 'atbdp_migrated', true );

            // Add directory type to all locations and categories
            $terms = [ ATBDP_CATEGORY, ATBDP_LOCATION ];
            foreach( $terms as $term ) {
                $term_data = get_terms([
                    'taxonomy'   => $term,
                    'hide_empty' => false,
                    'orderby'    => 'date',
                    'order'      => 'DSCE',
                  ]);
                  if( !empty( $term_data ) ) {
                      foreach( $term_data as $data ) {
                          update_term_meta( $data->term_id, '_directory_type', [ $add_directory['term_id']] );
                      }
                  }
            }

            // Add directory type to all listings
            $listings = new \WP_Query([
                'post_type'      => ATBDP_POST_TYPE,
                'status'         => 'publish',
                'posts_per_page' => -1,
            ]);

            if ( $listings->have_posts() ) {
                while ( $listings->have_posts() ) {
                    $listings->the_post();

                    // Set Directory Type
                    wp_set_object_terms( get_the_id(), $add_directory['term_id'], 'atbdp_listing_types' );
                    update_post_meta( get_the_id(), '_directory_type', $add_directory['term_id'] );

                }
            }

            return [ 'success' => true ];
        }

        return [ 'success' => false ];
        
    }

    // get_fields_data
    public function get_fields_data() {
        $old_custom_fields = $this->get_old_custom_fields();
        $form_fields_common_data = [ 'old_custom_fields' => $old_custom_fields ];

        $submission_form_fields_data = $this->get_submission_form_fields_data( $form_fields_common_data );
        $form_fields_common_data['submission_form_fields_data'] = $submission_form_fields_data;

        $listings_card_wedgets = $this->get_listings_card_wedgets_data();
        $listings_card_common_data = [ 'listings_card_wedgets' => $listings_card_wedgets ];

        $fields = apply_filters( 'atbdp_multidirectory_migration_fields', [
            "icon"          => "fa fa-home",
            "singular_name" => "listing",
            "plural_name"   => "listings",
            "permalink"     => "listing",
            "preview_mode"  => get_directorist_option( 'preview_enable', true ),
            "preview_image" => get_directorist_option( 'default_preview_image', true ),

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
            "submission_form_fields"   => $submission_form_fields_data,
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
    public function get_submission_form_fields_data( array $args = [] ) {
        $default = [ 'old_custom_fields' => [] ];
        $args    = array_merge( $default, $args );

        // Submission Form Fields
        $preset_fields = [];

        // Group Keys
        $general_group_field_keys      = [];
        $contact_group_info_field_keys = [];
        $map_group_field_keys          = [];
        $gallery_group_field_keys      = [];

        // General Group
        // ------------------------------
        // Title
        $general_group_field_keys[] = 'title';
        $preset_fields[ "title" ] = [
            "field_key"    => "listing_title",
            "type"         => "text",
            "required"     => get_directorist_option( 'require_title', true ),
            "label"        => get_directorist_option( 'title_label', "Title" ),
            "placeholder"  => get_directorist_option( 'title_placeholder', "Enter a title" ),
            "widget_group" => "preset",
            "widget_name"  => "title",
            "widget_key"   => "title",
        ];

        // Description
        $general_group_field_keys[] = 'description';
        $preset_fields[ "description" ] = [
            "field_key"      => "listing_content",
            "type"           => "wp_editor",
            "label"          => get_directorist_option( 'long_details_label', 'Long Details' ),
            "placeholder"    => "",
            "required"       => get_directorist_option( 'require_long_details', false ),
            "only_for_admin" => get_directorist_option( 'display_desc_for', false ),
            "widget_group"   => "preset",
            "widget_name"    => "description",
            "widget_key"     => "description",
        ];

        // Excerpt
        if ( ! empty( get_directorist_option( 'display_excerpt_field', true ) ) ) {
            $general_group_field_keys[] = 'excerpt';
            $preset_fields[ "excerpt" ] = [
                "field_key"      => "excerpt",
                "label"          => get_directorist_option( 'excerpt_label', 'Short Description/Excerpt' ),
                "words_limit"    => '',
                "placeholder"    => get_directorist_option( 'excerpt_placeholder', false ),
                "required"       => get_directorist_option( 'require_excerpt', false ),
                "only_for_admin" => get_directorist_option( 'display_short_desc_for', false ),
                "widget_group"   => "preset",
                "widget_name"    => "excerpt",
                "widget_key"     => "excerpt",
            ];
        }

        if ( ! empty( get_directorist_option( 'display_views_count', true ) ) ) {
            $general_group_field_keys[] = 'view_count';
            $preset_fields[ "view_count" ] = [
                "type"           => "number",
                "field_key"      => "atbdp_post_views_count",
                "label"          => get_directorist_option( 'views_count_label', 'Views Count' ),
                "placeholder"    => "",
                "required"       => false,
                "only_for_admin" => true,
                "widget_group"   => "preset",
                "widget_name"    => "view_count",
                "widget_key"     => "view_count",
            ];
        }

        if ( ! empty( get_directorist_option( 'display_tagline_field', true ) ) ) {
            $general_group_field_keys[] = 'tagline';
            $preset_fields[ "tagline" ] = [
                "field_key"    => "tagline",
                "type"         => "text",
                "label"        => get_directorist_option( 'tagline_label', 'Tagline' ),
                "placeholder"  => get_directorist_option( 'tagline_placeholder', "Your Listing's motto or tag-line" ),
                "widget_group" => "preset",
                "widget_name"  => "tagline",
                "widget_key"   => "tagline",
            ];
        }

        if ( ! empty( get_directorist_option( 'display_pricing_field', true ) ) ) {
            $general_group_field_keys[] = 'pricing';
            $preset_fields[ "pricing" ] = [
                "field_key"              => "pricing",
                "pricing_type"           => "both",
                "label"                  => get_directorist_option( 'pricing_label', 'Pricing' ),
                "price_range_label"      => get_directorist_option( 'price_range_label', 'Select Price Range' ),
                "price_range_options"    => "cheap",
                "price_unit_field_type"  => "number",
                "price_unit_field_label" => get_directorist_option( 'price_label', 'Price' ),
                "widget_group"           => "preset",
                "widget_name"            => "pricing",
                "widget_key"             => "pricing",
            ];
        }

        if( !empty( $this->get_old_custom_fields() ) ){
                foreach( $this->get_old_custom_fields() as $key => $value ){
                    $general_group_field_keys[] = $key;
                    $preset_fields[ $key ] = $value;
                }
        }

        $general_group_field_keys[] = 'location';
        $preset_fields[ "location" ] = [
            "type"           => "multiple",
            "field_key"      => "tax_input[at_biz_dir-location][]",
            "label"          => get_directorist_option( 'location_label', 'Location' ),
            "required"       => get_directorist_option( 'require_location', false ),
            "only_for_admin" => get_directorist_option( 'display_loc_for', false ),
            "widget_group"   => "preset",
            "widget_name"    => "location",
            "widget_key"     => "location"
        ];

        $general_group_field_keys[] = 'tag';
        $preset_fields[ "tag" ] = [
            "type"           => "multiple",
            "field_key"      => "tax_input[at_biz_dir-tags][]",
            "label"          => get_directorist_option( 'tag_label', 'Tag' ),
            "required"       => get_directorist_option( 'require_tags', false ),
            "allow_new"      => get_directorist_option( 'create_new_tag', true ),
            "only_for_admin" => get_directorist_option( 'display_tag_for', false ),
            "widget_group"   => "preset",
            "widget_name"    => "tag",
            "widget_key"     => "tag"
        ];

        $general_group_field_keys[] = 'category';
        $preset_fields[ "category" ] = [
            "type"           => "multiple",
            "field_key"      => "admin_category_select[]",
            "label"          => get_directorist_option( 'category_label', 'Select Category' ),
            "required"       => get_directorist_option( 'require_category', false ),
            "only_for_admin" => false,
            "widget_group"   => "preset",
            "widget_name"    => "category",
            "widget_key"     => "category",
        ];

        // Contact Group
        // ------------------------------
        $contact_group_info_field_keys[] = 'hide_contact_owner';
        $preset_fields[ "hide_contact_owner" ] = [
            "type"         => "checkbox",
            "field_key"    => "hide_contact_owner",
            "label"        => 'Hide contact owner form for single listing page',
            "placeholder"  => get_directorist_option( 'zip_placeholder', 'Enter Zip/Post Code' ),
            "required"     => get_directorist_option( 'require_zip', false ),
            "widget_group" => "preset",
            "widget_name"  => "hide_contact_owner",
            "widget_key"   => "hide_contact_owner",
        ];

        if ( ! empty( get_directorist_option( 'display_zip_field', true ) ) ) {
            $contact_group_info_field_keys[] = 'zip';
            $preset_fields[ "zip" ] = [
                "type"           => "text",
                "field_key"      => "zip",
                "label"          => get_directorist_option( 'zip_label', 'Zip/Post Code' ),
                "placeholder"    => get_directorist_option( 'zip_placeholder', 'Enter Zip/Post Code' ),
                "required"       => get_directorist_option( 'require_zip', false ),
                "only_for_admin" => get_directorist_option( 'display_zip_for', false ),
                "widget_group"   => "preset",
                "widget_name"    => "zip",
                "widget_key"     => "zip",
            ];
        }

        if ( ! empty( get_directorist_option( 'display_phone_field', true ) ) ) {
            $contact_group_info_field_keys[] = 'phone';
            $preset_fields[ "phone" ] = [
                "type"           => "tel",
                "field_key"      => "phone",
                "label"          => get_directorist_option( 'phone_label', 'Phone' ),
                "placeholder"    => get_directorist_option( 'phone_placeholder', 'Phone Number' ),
                "required"       => get_directorist_option( 'require_phone_number', false ),
                "only_for_admin" => get_directorist_option( 'display_phone_for', false ),
                "widget_group"   => "preset",
                "widget_name"    => "phone",
                "widget_key"     => "phone"
            ];
        }

        if ( ! empty( get_directorist_option( 'display_phone_field2', true ) ) ) {
            $contact_group_info_field_keys[] = 'phone2';
            $preset_fields[ "phone2" ] = [
                "type"           => "tel",
                "field_key"      => "phone2",
                "label"          => get_directorist_option( 'phone_label2', 'Phone 2' ),
                "placeholder"    => get_directorist_option( 'phone2_placeholder', 'Phone Number' ),
                "required"       => get_directorist_option( 'require_phone2_number', false ),
                "only_for_admin" => get_directorist_option( 'display_phone2_for', false ),
                "widget_group"   => "preset",
                "widget_name"    => "phone2",
                "widget_key"     => "phone2",
            ];
        }

        if ( ! empty( get_directorist_option( 'display_fax', true ) ) ) {
            $contact_group_info_field_keys[] = 'fax';
            $preset_fields[ "fax" ] = [
                "type"           => "number",
                "field_key"      => "fax",
                "label"          => get_directorist_option( 'fax_label', 'Fax' ),
                "placeholder"    => get_directorist_option( 'fax_placeholder', 'Enter Fax' ),
                "required"       => get_directorist_option( 'require_fax', false ),
                "only_for_admin" => get_directorist_option( 'display_fax_for', false ),
                "widget_group"   => "preset",
                "widget_name"    => "fax",
                "widget_key"     => "fax",
            ];
        }

        if ( ! empty( get_directorist_option( 'display_email_field', true ) ) ) {
            $contact_group_info_field_keys[] = 'email';
            $preset_fields[ "email" ] = [
                "type"           => "email",
                "field_key"      => "email",
                "label"          => get_directorist_option( 'email_label', 'Email' ),
                "placeholder"    => get_directorist_option( 'email_placeholder', 'Enter Email' ),
                "required"       => get_directorist_option( 'require_email', false ),
                "only_for_admin" => get_directorist_option( 'display_email_for', false ),
                "widget_group"   => "preset",
                "widget_name"    => "email",
                "widget_key"     => "email",
            ];
        }

        if ( ! empty( get_directorist_option( 'display_website_field', true ) ) ) {
            $contact_group_info_field_keys[] = 'website';
            $preset_fields[ "website" ] = [
                "type"           => "text",
                "field_key"      => "website",
                "label"          => get_directorist_option( 'website_label', 'Website' ),
                "placeholder"    => get_directorist_option( 'website_placeholder', 'Listing Website eg. http://example.com' ),
                "required"       => get_directorist_option( 'require_website', false ),
                "only_for_admin" => get_directorist_option( 'display_website_for', false ),
                "plans"          => [],
                "widget_group"   => "preset",
                "widget_name"    => "website",
                "widget_key"     => "website",
            ];
        }

        if ( ! empty( get_directorist_option( 'display_social_info_field', true ) ) ) {
            $contact_group_info_field_keys[] = 'social_info';
            $preset_fields[ "social_info" ] = [
                "type"           => "add_new",
                "field_key"      => "social",
                "label"          => get_directorist_option( 'social_label', 'Social Information' ),
                "required"       => get_directorist_option( 'require_social_info', false ),
                "only_for_admin" => get_directorist_option( 'display_social_info_for', false ),
                "widget_group"   => "preset",
                "widget_name"    => "social_info",
                "widget_key"     => "social_info",
            ];
        }

        if ( ! empty( get_directorist_option( 'display_address_field', true ) ) ) {
            $map_group_field_keys[] = 'address';
            $preset_fields[ "address" ] = [
                "type"           => "text",
                "field_key"      => "address",
                "label"          => get_directorist_option( 'address_label', 'Address' ),
                "placeholder"    => get_directorist_option( 'address_placeholder', 'Listing address eg. New York, USA' ),
                "required"       => get_directorist_option( 'require_address', false ),
                "only_for_admin" => get_directorist_option( 'display_address_for', false ),
                "widget_group"   => "preset",
                "widget_name"    => "address",
                "widget_key"     => "address",
            ];
        }

        if ( ! empty( get_directorist_option( 'display_map_field', true ) ) ) {
            $map_group_field_keys[] = 'map';
            $preset_fields[ "map" ] = [
                "type"           => "map",
                "field_key"      => "map",
                "label"          => "Map",
                "only_for_admin" => get_directorist_option( 'display_map_for', false ),
                "lat_long"       => "Or Enter Coordinates (latitude and longitude) Manually",
                "required"       => false,
                "widget_group"   => "preset",
                "widget_name"    => "map",
                "widget_key"     => "map",
            ];
        }

        if ( ! empty( get_directorist_option( 'display_gallery_field', true ) ) ) {
            $gallery_group_field_keys[] = 'image_upload';
            $preset_fields[ "image_upload" ] = [
                "type"                  => "media",
                "field_key"             => "listing_img",
                "label"                 => "Images",
                "select_files_label"    => get_directorist_option( 'gallery_label', 'Select Files' ),
                "required"              => get_directorist_option( 'require_gallery_img', false ),
                "max_image_limit"       => get_directorist_option( 'require_gallery_img', 5 ),
                "max_per_image_limit"   => get_directorist_option( 'max_gallery_upload_size_per_file', 0 ),
                "max_total_image_limit" => get_directorist_option( 'max_gallery_upload_size', 2 ),
                "only_for_admin"        => get_directorist_option( 'display_glr_img_for', false ),
                "widget_group"          => "preset",
                "widget_name"           => "image_upload",
                "widget_key"            => "image_upload",
            ];
        }

        if ( ! empty( get_directorist_option( 'display_gallery_field', true ) ) ) {
            $gallery_group_field_keys[] = 'video';
            $preset_fields[ "video" ] = [
                "type"           => "text",
                "field_key"      => "videourl",
                "label"          => get_directorist_option( 'video_label', 'Video Url' ),
                "placeholder"    => get_directorist_option( 'video_placeholder', 'Only YouTube & Vimeo URLs.' ),
                "required"       => get_directorist_option( 'require_video', false ),
                "only_for_admin" => get_directorist_option( 'display_video_for', false ),
                "widget_group"   => "preset",
                "widget_name"    => "video",
                "widget_key"     => "video",
            ];
        }

        $custom_fields = $args[ 'old_custom_fields' ];
        $all_form_fields = array_merge( $preset_fields, $custom_fields );
        
        $form_groups = [];

        // General Group
        $form_groups[] = [
            "label"  => "General Information",
            "lock"   => true,
            "fields" => $general_group_field_keys,
        ];

        // Contact Info
        $form_groups[] = [
            "label"  => "Contact Information",
            "fields" => $contact_group_info_field_keys,
        ];

        // Map
        $form_groups[] = [
            "label" => "Map",
            "fields" => $map_group_field_keys,
        ];

        // Gallery
        $form_groups[] = [
            "label" => "Images & Video",
            "fields" => $gallery_group_field_keys,
        ];

        $submission_form_fields = [
            "fields" => $all_form_fields,
            "groups" => $form_groups
        ];

        return $submission_form_fields;
    }

    // get_single_listings_contents_data
    public function get_single_listings_contents_data( array $args = [] ) {

        $default = [ 'old_custom_fields' => [] ];
        $args    = array_merge( $default, $args );

        // Single Listing Contents
        $single_listings_preset_fields = [];
        $map_group_field_keys          = [];
        $contact_info_group_field_keys = [];
        $video_group_field_keys        = [];

        $single_listings_preset_fields["tag"] = [
            'icon'         => 'la la-tag',
            "widget_name"  => "tag",
            "widget_group" => "preset_widgets",
        ];

        if ( ! empty( get_directorist_option( 'display_map_field', true ) ) ) {
            $map_group_field_keys[] = 'map';
            $single_listings_preset_fields["map"] = [
                'icon'         => 'la la-map',
                "widget_name"  => "map",
                "widget_group" => "preset_widgets",
            ];
        }

        if ( ! empty( get_directorist_option( 'display_map_field', true ) ) ) {
            $contact_info_group_field_keys[] = 'address';
            $single_listings_preset_fields["address"] = [
                "icon"                  => "la la-map",
                'address_link_with_map' => false,
                "widget_name"           => "address",
                "widget_group"          => "preset_widgets",
            ];
        }

        if ( ! empty( get_directorist_option( 'display_phone_field', true ) ) ) {
            $contact_info_group_field_keys[] = 'phone';
            $single_listings_preset_fields["phone"] = [
                "icon"         => "la la-phone",
                "widget_name"  => "phone",
                "widget_group" => "preset_widgets",
            ];
        }

        if ( ! empty( get_directorist_option( 'display_phone_field2', true ) ) ) {
            $contact_info_group_field_keys[] = 'phone2';
            $single_listings_preset_fields["phone2"] = [
                "icon"         => "la la-phone",
                "widget_name"  => "phone2",
                "widget_group" => "preset_widgets",
            ];
        }
        
        if ( ! empty( get_directorist_option( 'display_zip_field', true ) ) ) {
            $contact_info_group_field_keys[] = 'zip';
            $single_listings_preset_fields["zip"] = [
                "icon"         => "la la-street-view",
                "widget_name"  => "zip",
                "widget_group" => "preset_widgets",
            ];
        }

        if ( ! empty( get_directorist_option( 'display_email_field', true ) ) ) {
            $contact_info_group_field_keys[] = 'email';
            $single_listings_preset_fields["email"] = [
                "icon"         => "la la-envelope",
                "widget_name"  => "email",
                "widget_group" => "preset_widgets",
            ];
        }

        if ( ! empty( get_directorist_option( 'display_website_field', true ) ) ) {
            $contact_info_group_field_keys[] = 'website';
            $single_listings_preset_fields["website"] = [
                "icon"         => "la la-globe",
                "widget_name"  => "website",
                "widget_group" => "preset_widgets",
            ];
        }

        if ( ! empty( get_directorist_option( 'display_fax', true ) ) ) {
            $contact_info_group_field_keys[] = 'fax';
            $single_listings_preset_fields["fax"] = [
                "icon"         => "la la-fax",
                "widget_name"  => "fax",
                "widget_group" => "preset_widgets",
            ];
        }

        if ( ! empty( get_directorist_option( 'display_social_info_field', true ) ) ) {
            $contact_info_group_field_keys[] = 'social_info';
            $single_listings_preset_fields["social_info"] = [
                "icon"         => "la la-share-alt",
                "widget_name"  => "social_info",
                "widget_group" => "preset_widgets",
            ];
        }

        if ( ! empty( get_directorist_option( 'display_video_field', true ) ) ) {
            $video_group_field_keys[] = 'video';
            $single_listings_preset_fields["video"] = [
                "icon"         => "la la-video",
                "widget_name"  => "video",
                "widget_group" => "preset_widgets",
            ];
        }

        $this->multi_directory_manager->prepare_settings();
        $single_listings_widgets = [];
        if (
            isset( $this->multi_directory_manager::$fields ) &&
            isset( $this->multi_directory_manager::$fields['single_listings_contents'] ) &&
            isset( $this->multi_directory_manager::$fields['single_listings_contents']['widgets'] ) &&
            isset( $this->multi_directory_manager::$fields['single_listings_contents']['widgets']['preset_widgets'] ) &&
            isset( $this->multi_directory_manager::$fields['single_listings_contents']['widgets']['preset_widgets']['widgets'] ) &&
            is_array( $this->multi_directory_manager::$fields['single_listings_contents']['widgets']['preset_widgets']['widgets'] )
        ) {
            $single_listings_widgets = $this->multi_directory_manager::$fields['single_listings_contents']['widgets']['preset_widgets']['widgets'];
        }

        $single_listings_widgets_keys = is_array( $single_listings_widgets ) ? array_keys( $single_listings_widgets ) : [];
        $single_listings_custom_fields = [];
        $custom_fields = $args[ 'old_custom_fields' ];
        foreach ( $custom_fields as $field_key => $args ) {
            $widget_args = [
                "label"        => $args['label'],
                "widget_group" => "preset_widgets",
                "widget_name"  => $field_key
            ];

            if ( ! in_array( $args['widget_name'],  $single_listings_widgets_keys ) ) {
                continue;
            }

            if ( ! isset( $single_listings_widgets[ $args['widget_name'] ]['options'] ) ) {
                continue;
            }

            foreach ( $single_listings_widgets[ $args['widget_name'] ]['options'] as $option_key => $option_args ) {
                $widget_args[ $option_key ] = $option_args['value'];
            }

            $single_listings_custom_fields[ $field_key ] = $widget_args;
        }

        $single_listings_fields = array_merge( $single_listings_preset_fields, $single_listings_custom_fields );
        $single_listings_groups = [];

        if ( ! empty( get_directorist_option( 'enable_single_tag', true ) ) ) {
            $single_listings_groups[] = [
                "label"  => get_directorist_option( 'tags_section_lable', __( 'Tags', 'directorist' ) ),
                "fields" => [ "tag" ],
                'type'   => 'general_group',
                'icon'   => 'la la-tags',
                'custom_block_id'      => '',
                'custom_block_classes' => '',
            ];
        }
        
        if ( ! empty( $single_listings_custom_fields ) && is_array( $single_listings_custom_fields ) ) {
            $single_listings_groups[] = [
                "label"  => get_directorist_option( 'custom_section_lable', __( 'Features', 'directorist' ) ),
                "fields" => array_keys( $single_listings_custom_fields ),
                'type'   => 'general_group',
                'icon'   => 'la la-bars',
                'custom_block_id'      => '',
                'custom_block_classes' => '',
            ];
        }

        if ( empty( get_directorist_option( 'disable_map', false )  ) && ! empty( $map_group_field_keys ) ) {
            $single_listings_groups[] = [
                "label"  => get_directorist_option( 'listing_location_text', __( 'Location', 'directorist' ) ),
                "fields" => $map_group_field_keys,
                'type'   => 'general_group',
                'icon'   => 'la la-map',
                'custom_block_id'      => '',
                'custom_block_classes' => '',
            ];
        }

        if ( empty( get_directorist_option( 'disable_contact_info', false ) ) && ! empty( $contact_info_group_field_keys ) ) {
            $single_listings_groups[] = [
                "label"                => get_directorist_option( 'contact_info_text', __( 'Contact Information', 'directorist' ) ),
                "fields"               => $contact_info_group_field_keys,
                'type'                 => 'general_group',
                'icon'                 => 'la la-envelope-o',
                'custom_block_id'      => '',
                'custom_block_classes' => '',
            ];
        }

        if ( ! empty( get_directorist_option( 'atbd_video_url', true ) ) && ! empty( $video_group_field_keys ) ) {
            $single_listings_groups[] = [
                "label"                => get_directorist_option( 'atbd_video_title', __( 'Video', 'directorist' ) ),
                "fields"               => $video_group_field_keys,
                'type'                 => 'general_group',
                'icon'                 => 'la la-video-camera',
                'custom_block_id'      => '',
                'custom_block_classes' => '',
            ];
        }

        $single_listings_groups[] = [
            "label"                => get_directorist_option( 'atbd_author_info_title', __( 'Author Info', 'directorist' ) ),
            "fields"               => [],
            'type'                 => 'widget_group',
            'widget_group'         => 'other_widgets',
            'widget_name'          => 'author_info',
            'custom_block_id'      => '',
            'custom_block_classes' => '',
        ];

        if ( empty( get_directorist_option( 'disable_contact_owner', false ) ) ) {
            $single_listings_groups[] = [
                "label"                => get_directorist_option( 'contact_listing_owner', __( 'Contact Listings Owner', 'directorist' ) ),
                "fields"               => [],
                'type'                 => 'widget_group',
                'widget_group'         => 'other_widgets',
                'widget_name'          => 'contact_listings_owner',
                'icon'                 => 'la la-phone',
                'custom_block_id'      => '',
                'custom_block_classes' => '',
            ];
        }

        if ( ! empty( get_directorist_option( 'enable_review', true ) ) ) {
            $single_listings_groups[] = [
                "label"                => "Review",
                "fields"               => [],
                'type'                 => 'widget_group',
                'widget_group'         => 'other_widgets',
                'widget_name'          => 'review',
                'custom_block_id'      => '',
                'custom_block_classes' => '',
            ];
        }

        if ( ! empty( get_directorist_option( 'enable_rel_listing', true ) ) ) {
            $single_listings_groups[] = [
                "label"                => get_directorist_option( 'rel_listing_title', __( 'Related Listings', 'directorist' ) ),
                "fields"               => [],
                'type'                 => 'widget_group',
                'widget_group'         => 'other_widgets',
                'widget_name'          => 'related_listings',
                'custom_block_id'      => '',
                'custom_block_classes' => '',
            ];
        }

        $single_listings_contents = [
            "fields" => $single_listings_fields,
            "groups" => $single_listings_groups
        ];

        // directorist_console_log( $single_listings_contents );

        return $single_listings_contents;
    }

    // get_search_form_fields
    public function get_search_form_fields( array $args = [] ) {
        $default = [ 'old_custom_fields' => [] ];
        $args    = array_merge( $default, $args );

        // Search Form
        $search_fields_map = [
            // Basic
            'search_text'     => [
                'field_key' => 'title',
                'options' => [
                    "required"     => get_directorist_option( 'require_search_text', false ),
                    "label"        => "",
                    "placeholder"  => get_directorist_option( 'listings_search_text_placeholder', "What are you looking for?" ),
                    "widget_name"  => "title",
                    "widget_group" => "available_widgets",
                ],
            ],
            'search_category' => [
                'field_key' => 'category',
                'options' => [
                    "required"     => get_directorist_option( 'require_search_category', false ),
                    "label"        => "",
                    "placeholder"  => get_directorist_option( 'search_category_placeholder', "Select a category" ),
                    "widget_name"  => "category",
                    "widget_group" => "available_widgets",
                ],
            ],
            'search_location' => [
                'field_key' => 'location',
                "options" => [
                    "required"     => get_directorist_option( 'require_search_location', false ),
                    "placeholder"  => get_directorist_option( 'search_location_placeholder', "Select a category" ),
                    "widget_group" => "available_widgets",
                    "label"        => "",
                    "widget_name"  => "location",
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
                    'label'              => 'Tag',
                    'tags_filter_source' => 'all_tags',
                    'widget_name'        => 'tag',
                    'widget_group'       => 'available_widgets',
                ],
            ],
            // 'search_open_now' => [
            //     'field_key' => 'open_now',
            //     "options" => [
            //         'label'              => 'Open Now',
            //         'widget_name'        => 'open_now',
            //         'widget_group'       => 'available_widgets',
            //     ],
            // ],
            'search_website' => [
                'field_key' => 'website',
                "options" => [
                    'label'        => 'Website',
                    "widget_group" => "available_widgets",
                    "widget_name"  => "",
                    "placeholder"  => ""
                ],
            ],
            'search_email' => [
                'field_key' => 'email',
                "options" => [
                    'label'        => 'Email',
                    "widget_group" => "available_widgets",
                    "widget_name"  => "",
                    "placeholder"  => ""
                ],
            ],
            'search_phone' => [
                'field_key' => 'phone',
                "options" => [
                    'label'        => 'Phone',
                    "widget_group" => "available_widgets",
                    "widget_name"  => "",
                    "placeholder"  => ""
                ],
            ],
            'search_fax' => [
                'field_key' => 'fax',
                "options" => [
                    'label'        => 'Fax',
                    "widget_group" => "available_widgets",
                    "widget_name"  => "fax",
                    "placeholder"  => ""
                ],
            ],
            'search_zip_code' => [
                'field_key' => 'zip',
                "options" => [
                    'label'        => 'Zip/Post Code',
                    'placeholder'  => 'Zip',
                    'required'     => false,
                    'widget_name'  => 'zip',
                    'widget_group' => 'available_widgets',
                ],
            ],
            'phone' => [
                'field_key' => 'phone',
                "options" => [
                    'label'        => 'Phone',
                    'placeholder'  => 'Phone',
                    'required'     => false,
                    'widget_name'  => 'phone',
                    'widget_group' => 'available_widgets',
                ],
            ],
            'phone2' => [
                'field_key' => 'phone',
                "options" => [
                    'label' => 'Phone 2',
                    'placeholder' => 'Phone 2',
                    'required' => false,
                    'widget_name' => 'phone2',
                    'widget_group' => 'available_widgets',
                ],
            ],
            'radius_search' => [
                'field_key' => 'radius_search',
                "options" => [
                    'label'                   => 'Radius Search',
                    'default_radius_distance' => 0,
                    'radius_search_unit'      => 'miles',
                    'widget_name'             => 'radius_search',
                    'widget_group'            => 'other_widgets',
                ],
            ],
            'search_rating' => [
                'field_key' => 'review',
                "options" => [
                    'label' => 'Review',
                    'widget_name' => 'review',
                    'widget_group' => 'other_widgets',
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

        // var_dump( $old_advanced_search_fields );

        foreach ( $old_advanced_search_fields as $field_key ) {
            if ( empty( $search_fields_map[ $field_key ] ) ) { continue; }

            $new_field_key = $search_fields_map[ $field_key ][ 'field_key' ];
            $search_form_fields_advanced_items[ $new_field_key ] = $search_fields_map[ $field_key ][ 'options' ];
        }

        // Get Other Fields
        // Price Field
        if ( in_array( 'search_price', $old_advanced_search_fields ) || in_array( 'search_price_range', $old_advanced_search_fields ) ) {
            $search_form_fields_advanced_items[ 'pricing' ] = [
                'price_range_min_placeholder' => 'Min',
                'price_range_max_placeholder' => 'Max',
                "widget_name"  => "pricing",
                "widget_group" => "available_widgets",
            ];
        }

        // Custom Fields
        if ( in_array( 'search_custom_fields', $old_advanced_search_fields ) && ! empty( $args[ 'old_custom_fields' ] ) ) {
            // submission_form_fields_data
            $custom_fields = $args[ 'old_custom_fields' ];
            $submission_form_field_keys = [];

            if ( ! empty( $args[ 'submission_form_fields_data' ] ) && ! empty( $args[ 'submission_form_fields_data' ]['fields'] ) && is_array( $args[ 'submission_form_fields_data' ]['fields'] ) ) {
                $submission_form_field_keys = array_keys( $args[ 'submission_form_fields_data' ]['fields'] );
            }

            foreach ( $custom_fields as $field_key => $field_args ) {
                if ( empty( $field_args['searchable'] ) || ! in_array( $field_key, $submission_form_field_keys ) ) { continue; }

                $search_form_fields_advanced_items[ $field_key ] = [
                    "label"        => $field_args['label'],
                    "widget_group" => "available_widgets",
                    "widget_name"  => $field_args['type']
                ];
            }
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
                "type"        => "button",
                "label"       => "Bookmark",
                "widget_name" => "bookmark",
                "widget_key"  => "bookmark",
                'icon'        => 'la la-hart',
            ];
        } 
        if ( '1' == get_directorist_option( 'enable_report_abuse', true ) ) {
            $quick_actions[] = [
                "type"        => "badge",
                "label"       => "Share",
                "hook"        => "atbdp_single_listings_title",
                "widget_name" => "share",
                "widget_key"  => "share",
                "icon"        => "la la-share"
            ];
        }
        if ( '1' == get_directorist_option( 'enable_social_share', true ) ) {
            $quick_actions[] = [
                "type"        => "badge",
                "label"       => "Report",
                "hook"        => "atbdp_single_listings_title",
                "widget_name" => "report",
                "widget_key"  => "report",
                "icon"        => "la la-flag"
            ];
        } 

        // Thumbnail Items
        $thumbnail = [];
        if ( '1' == get_directorist_option( 'dsiplay_slider_single_page', true ) ) {
            $thumbnail[] = [
                "type"            => "thumbnail",
                "label"           => "Select Files",
                "hook"            => "atbdp_single_listings_slider",
                "widget_name"     => "listing_slider",
                "widget_key"      => "listing_slider",
                "footer_thumbail" => get_directorist_option( 'dsiplay_thumbnail_img', true )
            ];
        }

        // Quick Info Items
        $quick_info = [];
        if ( '0' == get_directorist_option( 'disable_list_price', true ) ) {
            $quick_info[] = [
                "type"        => "badge",
                "label"       => "Listings Price",
                "hook"        => "atbdp_single_listings_price",
                "widget_name" => "price",
                "widget_key"  => "price"
            ];
        }
        
        $quick_info[] = [
            "type"        => "ratings-count",
            "label"       => "Listings Ratings",
            "widget_name" => "ratings_count",
            "widget_key"  => "ratings_count"
        ];

        $quick_info[] = [
            "type"        => "reviews",
            "label"       => "Listings Reviews",
            "widget_name" => "reviews",
            "widget_key"  => "reviews"
        ];

        $quick_info[] = [
            "type"          => "badge",
            "label"         => "Badges",
            "widget_name"   => "badges",
            "widget_key"    => "badges",
            "new_badge"     => get_directorist_option( 'display_new_badge_cart', true ),
            "popular_badge" => get_directorist_option( 'display_popular_badge_cart', true )
        ];
        
        $quick_info[] = [
            "type"        => "badge",
            "label"       => "Category",
            "widget_name" => "category",
            "widget_key"  => "category"
        ];

        $quick_info[] = [
            "type"        => "badge",
            "label"       => "Location",
            "widget_name" => "location",
            "widget_key"  => "location"
        ];
        
        
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
                        "enable_title"   => true,
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
        $active_template = ( ! empty( get_directorist_option( 'display_preview_image' ) ) ) ? 'grid_view_with_thumbnail' : 'grid_view_without_thumbnail';
        $grid_view['active_template'] = $active_template;

        $grid_view['template_data'] = [];
        $grid_view['template_data']['grid_view_with_thumbnail'] = $this->get_listings_card_grid_view_with_thumbnail_data( $args );
        $grid_view['template_data']['grid_view_without_thumbnail'] = $this->get_listings_card_grid_view_without_thumbnail_data( $args );

        return $grid_view;
    }

    // get_listings_card_grid_view_data
    public function get_listings_card_grid_view_with_thumbnail_data( array $args = [] ) {

        $default = [ 'listings_card_wedgets' => $this->get_listings_card_wedgets_data() ];
        $args    = array_merge( $default, $args );
        $listings_card_wedgets = $args[ 'listings_card_wedgets' ];

        $card_layouts = [
            'thumbnail_top_right'    => [],
            'thumbnail_top_left'     => [],
            'thumbnail_bottom_right' => [],
            'thumbnail_bottom_left'  => [],
            'thumbnail_avatar'       => [],
            'body_top'               => [],
            'body_bottom'            => [],
            'body_excerpt'           => [],
            'footer_right'           => [],
            'footer_left'            => [],
        ];

        $widget_layout_map = [
            'favorite_badge' => [
                'enable' => get_directorist_option( 'display_mark_as_fav', true ),
                'layout' => [ 'default' => 'thumbnail_top_right' ],
            ],
            'featured_badge' => [
                'enable' => get_directorist_option( 'display_feature_badge_cart', true ),
                'layout' => [ 'default' => 'thumbnail_top_left' ],
            ],
            'new_badge' => [
                'enable' => get_directorist_option( 'display_new_badge_cart', true ),
                'layout' => [
                    'default'   => 'thumbnail_bottom_left',
                    'direo'     => 'thumbnail_top_left',
                    'dlist'     => 'thumbnail_top_left',
                    'dservice'  => 'thumbnail_top_left',
                ],
            ],
            'popular_badge' => [
                'enable' => get_directorist_option( 'display_popular_badge_cart', true ),
                'layout' => [
                    'default'   => 'thumbnail_bottom_left',
                    'direo'     => 'thumbnail_top_left',
                    'dlist'     => 'thumbnail_top_left',
                    'dservice'  => 'thumbnail_top_left',
                ],
            ],
            'user_avatar' => [
                'enable' => get_directorist_option( 'display_author_image', true ),
                'layout' => [ 'default' => 'thumbnail_avatar' ],
            ],
            'listing_title' => [
                'enable' => get_directorist_option( 'display_title', true ),
                'layout' => [ 'default' => 'body_top' ],
            ],
            'rating' => [
                'enable' => get_directorist_option( 'enable_review', true ),
                'layout' => [ 'default' => 'body_top' ],
            ],
            'pricing' => [
                'enable' => get_directorist_option( 'display_pricing_field', true ),
                'layout' => [ 
                    'default'  => 'body_top',
                    'dservice' => 'thumbnail_bottom_right',
                ],
            ],
            'listings_location' => [
                'enable' => get_directorist_option( 'display_contact_info', true ),
                'layout' => [ 'default' => 'body_bottom' ],
            ],
            'posted_date' => [
                'enable' => get_directorist_option( 'display_publish_date', true ),
                'layout' => [ 'default' => 'body_bottom' ],
            ],
            'phone' => [
                'enable' => get_directorist_option( 'display_contact_info', true ),
                'layout' => [ 'default' => 'body_bottom' ],
            ],
            'website' => [
                'enable' => get_directorist_option( 'display_web_link', true ),
                'layout' => [ 'default' => 'body_bottom' ],
            ],
            'excerpt' => [
                'enable' => get_directorist_option( 'enable_excerpt', true ),
                'layout' => [ 'default' => 'body_excerpt' ],
            ],
            'view_count' => [
                'enable' => get_directorist_option( 'display_view_count', true ),
                'layout' => [ 'default' => 'footer_right' ],
            ],
            'contact_button' => [
                'layout' => [ 
                    'default' => '',
                    'dservice' => 'footer_right',
                ],
            ],
            'category' => [
                'enable' => get_directorist_option( 'display_category', true ),
                'layout' => [ 'default' => 'footer_left' ],
            ],
        ];

        $current_theme = wp_get_theme();
        $current_theme = $current_theme->stylesheet;

        // Fill layout with widgets
        foreach ( $widget_layout_map as $widget_key => $args ) {
            if ( isset( $args['enable'] ) && empty( $args['enable'] ) ) { continue; }

            $layout = ( in_array( $current_theme, array_keys( $args['layout'] ) ) ) ? $args['layout'][ $current_theme ] : $args['layout']['default'];

            if ( ! empty( $layout ) ) {
                $card_layouts[ $layout ][] = $listings_card_wedgets[ $widget_key ];
            }
        }

        $listings_card_grid_view = apply_filters( 'listings_card_grid_view_with_thumbnail', [
            "thumbnail"=> [ 
                "top_right"    => $card_layouts['thumbnail_top_right'],
                "top_left"     => $card_layouts['thumbnail_top_left'],
                "bottom_right" => $card_layouts['thumbnail_bottom_right'],
                "bottom_left"  => $card_layouts['thumbnail_bottom_left'],
                "avatar"       => $card_layouts['thumbnail_avatar'],
            ],
            "body" => [
                "top"     => $card_layouts['body_top'],
                "bottom"  => $card_layouts['body_bottom'],
                "excerpt" => $card_layouts['body_excerpt']
            ],
            "footer"=> [
                "right" => $card_layouts['footer_right'],
                "left"  => $card_layouts['footer_left'],
            ]
        ]);

        return $listings_card_grid_view;
    }
    // get_listings_card_grid_view_without_thumbnail_data
    public function get_listings_card_grid_view_without_thumbnail_data( array $args = [] ) {
        $default = [ 'listings_card_wedgets' => $this->get_listings_card_wedgets_data() ];
        $args    = array_merge( $default, $args );

        $listings_card_wedgets = $args[ 'listings_card_wedgets' ];
        
        // Listings Card Grid View - thumbnail_top_left
        $listings_card_grid_view_body_quick_info = [];
        if ( get_directorist_option( 'enable_review', true ) ) {
            $listings_card_grid_view_body_quick_info[] = $listings_card_wedgets['rating'];
        }

        if ( get_directorist_option( 'display_pricing_field', true ) && get_directorist_option( 'display_price', true ) ) {
            $listings_card_grid_view_body_quick_info[] = $listings_card_wedgets['pricing'];
        }

        if ( get_directorist_option( 'display_feature_badge_cart', true ) ) {
            $listings_card_grid_view_body_quick_info[] = $listings_card_wedgets['featured_badge'];
        }

        if ( get_directorist_option( 'display_popular_badge_cart', true ) ) {
            $listings_card_grid_view_body_quick_info[] = $listings_card_wedgets['popular_badge'];
        }

        if ( get_directorist_option( 'display_new_badge_cart', true ) ) {
            $listings_card_grid_view_body_quick_info[] = $listings_card_wedgets['new_badge'];
        }

        // listings_card_grid_view_body_avatar
        $listings_card_grid_view_body_avatar = [];
        if ( get_directorist_option( 'display_author_image', true ) ) {
            $user_avatar = $listings_card_wedgets['user_avatar'];
            $listings_card_grid_view_body_avatar[] = $user_avatar;
        }

        // listings_card_grid_view_body_title
        $listings_card_grid_view_body_title = [];
        if ( get_directorist_option( 'display_title', true ) ) {
            $listings_card_grid_view_body_title[] = $listings_card_wedgets['listing_title'];
        }

        // listings_card_grid_view_body_quick_actions
        $listings_card_grid_view_body_quick_actions = [];
        if ( get_directorist_option( 'display_mark_as_fav', true ) ) {
            $listings_card_grid_view_body_quick_actions[] = $listings_card_wedgets['favorite_badge'];
        }

        // listings_card_grid_view_body_quick_info
        $listings_card_grid_view_body_quick_info = [];
        if ( get_directorist_option( 'display_mark_as_fav', true ) ) {
            $listings_card_grid_view_body_quick_info[] = $listings_card_wedgets['favorite_badge'];
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

        $listings_card_grid_view_body_excerpt = [];
        if ( get_directorist_option( 'enable_excerpt', true ) ) {
            $listings_card_grid_view_body_excerpt[] = $listings_card_wedgets['excerpt'];
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

        $listings_card_grid_view = apply_filters( 'listings_card_grid_view_without_thumbnail', [
            "body" => [
                "avatar"        => $listings_card_grid_view_body_avatar,
                "title"         => $listings_card_grid_view_body_title,
                "quick_actions" => $listings_card_grid_view_body_quick_actions,
                "quick_info"    => $listings_card_grid_view_body_quick_info,
                "bottom"        => $listings_card_grid_view_body_bottom,
                "excerpt"       => $listings_card_grid_view_body_excerpt
            ],
            "footer" => [
                "right" => $listings_card_grid_view_footer_right,
                "left"  => $listings_card_grid_view_footer_left,
            ]
        ]);

        return $listings_card_grid_view;
    }

    // get_listings_card_list_view_data
    public function get_listings_card_list_view_data( array $args = [] ) {
        $active_template = ( ! empty( get_directorist_option( 'display_preview_image' ) ) ) ? 'list_view_with_thumbnail' : 'list_view_without_thumbnail';
        $list_view['active_template'] = $active_template;

        $list_view['template_data'] = [];
        $list_view['template_data']['list_view_with_thumbnail'] = $this->get_listings_card_list_view_with_thumbnail_data( $args );
        $list_view['template_data']['list_view_without_thumbnail'] = $this->get_listings_card_list_view_without_thumbnail_data( $args );

        return $list_view;
    }

    // get_listings_card_list_view_with_thumbnail_data
    public function get_listings_card_list_view_with_thumbnail_data( array $args = [] ) {
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

        if ( get_directorist_option( 'enable_review', true ) ) {
            $listings_card_list_view_body_top[] = $listings_card_wedgets['rating'];
        }

        if ( get_directorist_option( 'display_pricing_field', true ) && get_directorist_option( 'display_price', true ) ) {
            $listings_card_list_view_body_top[] = $listings_card_wedgets['pricing'];
        }

        if ( get_directorist_option( 'display_new_badge_cart', true ) ) {
            $listings_card_list_view_body_top[] = $listings_card_wedgets['new_badge'];
        }

        // listings_card_list_view_body_right
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

        // listings_card_list_view_body_excerpt
        $listings_card_list_view_body_excerpt = [];
        if ( get_directorist_option( 'enable_excerpt', true ) ) {
            $listings_card_list_view_body_excerpt[] = $listings_card_wedgets['excerpt'];
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

        $listings_card_list_view = apply_filters( 'listings_card_list_view_with_thumbnail', [
            "thumbnail"=> [
                "top_right" => $listings_card_list_view_thumbnail_top_right
            ],
            "body"=> [
                "top"     => $listings_card_list_view_body_top,
                "right"   => $listings_card_list_view_body_right,
                "bottom"  => $listings_card_list_view_body_bottom,
                "excerpt" => $listings_card_list_view_body_excerpt,
            ],
            "footer" => [
                "right" => $listings_card_list_view_footer_right,
                "left"  => $listings_card_list_view_footer_left
            ]
        ]);

        return $listings_card_list_view;
    }

    // get_listings_card_list_view_without_thumbnail_data
    public function get_listings_card_list_view_without_thumbnail_data( array $args = [] ) {
        $default = [ 'listings_card_wedgets' => [] ];
        $args    = array_merge( $default, $args );

        $listings_card_wedgets = $args[ 'listings_card_wedgets' ];  

        // $listings_card_list_view_body_top
        $listings_card_list_view_body_top = [];
        if ( get_directorist_option( 'display_title', true ) ) {
            $listings_card_list_view_body_top[] = $listings_card_wedgets['listing_title'];
        }

        if ( get_directorist_option( 'enable_review', true ) ) {
            $listings_card_list_view_body_top[] = $listings_card_wedgets['rating'];
        }

        if ( get_directorist_option( 'display_pricing_field', true ) && get_directorist_option( 'display_price', true ) ) {
            $listings_card_list_view_body_top[] = $listings_card_wedgets['pricing'];
        }

        if ( get_directorist_option( 'display_feature_badge_cart', true ) ) {
            $listings_card_list_view_body_top[] = $listings_card_wedgets['featured_badge'];
        }

        if ( get_directorist_option( 'display_popular_badge_cart', true ) ) {
            $listings_card_list_view_body_top[] = $listings_card_wedgets['popular_badge'];
        }

        if ( get_directorist_option( 'display_new_badge_cart', true ) ) {
            $listings_card_list_view_body_top[] = $listings_card_wedgets['new_badge'];
        }
        
        $listings_card_list_view_body_excerpt = [];
        if ( get_directorist_option( 'enable_excerpt', true ) ) {
            $listings_card_list_view_body_excerpt[] = $listings_card_wedgets['excerpt'];
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

        $listings_card_list_view = apply_filters( 'listings_card_list_view_with_thumbnail', [
            "body" => [
                "top"     => $listings_card_list_view_body_top,
                "right"   => $listings_card_list_view_body_right,
                "bottom"  => $listings_card_list_view_body_bottom,
                "excerpt" => $listings_card_list_view_body_excerpt,
            ],
            "footer" => [
                "right" => $listings_card_list_view_footer_right,
                "left"  => $listings_card_list_view_footer_left
            ]
        ]);

        return $listings_card_list_view;
    }

    // get_listings_card_wedgets_data
    public function get_listings_card_wedgets_data() {
        $listings_card_wedgets = [
            'favorite_badge' => [
                "type"        => "icon",
                "label"       => "Favorite",
                "hook"        => "atbdp_favorite_badge",
                "widget_name" => "favorite_badge",
                "widget_key"  => "favorite_badge",
            ],
            'featured_badge' => [
                "type"        => "badge",
                "label"       => get_directorist_option( 'feature_badge_text', __('Fetured', 'directorist') ),
                "hook"        => "atbdp_featured_badge",
                "widget_name" => "featured_badge",
                "widget_key"  => "featured_badge",
            ],
            'new_badge' => [
                "type"        => "badge",
                "label"       => get_directorist_option( 'new_badge_text', __('New', 'directorist') ),
                "hook"        => "atbdp_new_badge",
                "widget_name" => "new_badge",
                "widget_key"  => "new_badge",
            ],
            'popular_badge' => [
                "type"        => "badge",
                "label"       => get_directorist_option( 'popular_badge_text', __('Popular', 'directorist') ),
                "hook"        => "atbdp_popular_badge",
                "widget_name" => "popular_badge",
                "widget_key"  => "popular_badge",
            ],
            'user_avatar' => [
                "type"        => "avatar",
                "label"       => "User Avatar",
                "hook"        => "atbdp_user_avatar",
                'can_move'    => false,
                "widget_name" => "user_avatar",
                "widget_key"  => "user_avatar",
                "align"       => "right",
            ],
            'listing_title' => [
                "type"         => "title",
                "label"        => "Title",
                "hook"         => "atbdp_listing_title",
                "widget_name"  => "listing_title",
                "widget_key"   => "listing_title",
                'show_tagline' => false,
            ],
            'rating' => [
                "type"         => "rating",
                "label"        => "Rating",
                "hook"         => "atbdp_listings_rating",
                "widget_name"  => "rating",
                "widget_key"   => "rating",
            ],
            'pricing' => [
                "type"         => "price",
                "label"        => "Pricing",
                "hook"         => "pricing",
                "widget_name"  => "pricing",
                "widget_key"   => "pricing",
            ],
            'excerpt' => [
                "type"               => "excerpt",
                "label"              => "Excerpt",
                "hook"               => "atbdp_listing_excerpt",
                "words_limit"        => get_directorist_option( 'excerpt_limit', 30 ),
                "show_readmore"      => get_directorist_option( 'display_readmore', false ),
                "show_readmore_text" => get_directorist_option( 'readmore_text', 'Read More' ),
                "widget_key"         => "excerpt",
                "widget_name"        => "excerpt",
            ],
            'listings_location' => [
                "type"        => "list-item",
                "label"       => "Location",
                "hook"        => "atbdp_listings_location",
                "widget_name" => "listings_location",
                "widget_key"  => "listings_location",
                "icon"        => "la la-map-marker"
            ],
            'phone' => [
                "type"        => "list-item",
                "hook"        => "atbdp_listings_phone",
                "label"       => "Phone",
                "widget_name" => "phone",
                "widget_key"  => "phone",
                "icon"        => "la la-phone"

            ],
            'website' => [
                "type"        => "list-item",
                "hook"        => "atbdp_listings_website",
                "label"       => "Listings Website",
                "widget_name" => "website",
                "widget_key"  => "website",
                "icon"        => "la la-globe"

            ],
            'posted_date' => [
                "type"        => "list-item",
                "label"       => "Posted Date",
                "hook"        => "atbdp_listings_posted_date",
                "widget_name" => "posted_date",
                "widget_key"  => "posted_date",
                "icon"        => "la la-clock-o",
                "date_type"   => "days_ago"
            ],
            'view_count' => [
                "type"        => "view-count",
                "label"       => "View Count",
                "hook"        => "atbdp_view_count",
                "widget_name" => "view_count",
                "widget_key"  => "view_count",
                "icon"        => "fa fa-heart"
            ],
            'category' => [
                "type"        => "category",
                "label"       => "Category",
                "hook"        => "atbdp_category",
                "widget_name" => "category",
                "widget_key"  => "category",
                "icon"        => "fa fa-folder"
            ],
            'contact_button' => [
                "type"        => "button",
                "label"       => "Contact Label",
                "hook"        => "atbdp_open_close_badge",
                "widget_name" => "contact_button",
                "widget_key"  => "contact_button",
                "icon"        => "uil uil-phone"
            ],

        ];

        $current_theme = wp_get_theme();
        $current_theme = $current_theme->stylesheet;

        $theme_user_avatar = [
            'direo'    => [ 'align' => 'left' ],
            'dlist'    => [ 'align' => 'left' ],
            'dservice' => [ 'align' => 'left' ],
        ];

        if ( in_array( $current_theme, array_keys( $theme_user_avatar ) )  ) {
            $listings_card_wedgets['user_avatar'] = array_merge( $listings_card_wedgets['user_avatar'], $theme_user_avatar[ $current_theme ] );
        }

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
            
            $field_type = ( 'color' === $field_type ) ? 'color_picker' : $field_type;

            // Common Data
            $field_data['type']         = $field_type;
            $field_data['label']        = get_the_title($old_field_id);
            $field_data['field_key']    = $old_field_id;
            $field_data['placeholder']  = '';
            $field_data['description']  = get_post_meta($old_field_id, 'instructions', true);
            $field_data['required']     = ( $required == 1 ) ? true : false;

            $field_data['only_for_admin'] = ( $admin_use == 1 ) ? true : false;
            
            $assign_to = get_post_meta($old_field_id, 'associate', true);
            $assign_to = ( 'categories' === $assign_to ) ? 'category' : $assign_to;
            $field_data['assign_to']   = $assign_to;
            $field_data['category']    = ( is_numeric( $category_pass ) ) ? ( int ) $category_pass : '';
            $field_data['searchable']  = ( $searchable == 1 ) ? true : false;

            $field_data['widget_group'] = 'custom';
            $field_data['widget_name']  = $field_type;
            $field_data['widget_key']   = $field_type . '_' . $old_field_id;
            
            // field group
            $field_group = [ 'radio', 'checkbox', 'select' ];
            if ( in_array( $field_type, $field_group ) ) {
                $choices = get_post_meta($old_field_id, 'choices', true);
                $field_data['options'] = $choices;
            }

            if ( ('textarea' === $field_type) ) {
                $field_data['rows'] = get_post_meta($old_field_id, 'rows', true);
            }

            if ( ('url' === $field_type) ) {
                $field_data['target'] = get_post_meta($old_field_id, 'target', true);
            }

            if ( ('file' === $field_type) ) {
                $file_type = get_post_meta($old_field_id, 'file_type', true);
                $file_type = ( 'all_types' === $file_type ) ? 'all' : $file_type;
                $field_data['file_type'] = $file_type;
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
                        'option_value' => $option,
                        'option_label' => $option,
                    ];

                    continue;
                }

                $options[] = [
                    'option_value' => trim( $value_match[1] ),
                    'option_label' => trim( $label_match[1] ),
                ];
            }
        }

        return $options;
    }
}