<?php

namespace Directorist\Multi_Directory;

class Multi_Directory_Manager {
    use Multi_Directory_Helper;

    public static $fields  = [];
    public static $layouts = [];
    public static $config  = [];
    public static $options = [];

    public static $migration  = null;


    public function __construct() {
        self::$migration = new Multi_Directory_Migration([ 'multi_directory_manager' => $this ]);
    }

    // run
    public function run() {
        add_action( 'init', [$this, 'register_directory_taxonomy'] );
        add_action( 'init', [$this, 'setup_migration'] );

        if ( ! is_admin() ) {
            return;
        }

        add_filter( 'cptm_fields_before_update', [$this, 'cptm_fields_before_update'], 20, 1 );

        add_action( 'admin_menu', [$this, 'add_menu_pages'] );
        add_action( 'admin_post_delete_listing_type', [$this, 'handle_delete_listing_type_request'] );

        // Ajax
        add_action( 'wp_ajax_save_post_type_data', [ $this, 'save_post_type_data' ] );
        add_action( 'wp_ajax_save_imported_post_type_data', [ $this, 'save_imported_post_type_data' ] );
        add_action( 'wp_ajax_directorist_force_migrate', [ $this, 'handle_force_migration' ] );
        add_action( 'wp_ajax_directorist_directory_type_library', [ $this, 'directorist_directory_type_library' ] );
        add_action( 'wp_ajax_directorist_ai_directory_form', [ $this, 'directorist_ai_directory_form' ] );
        add_action( 'wp_ajax_directorist_ai_directory_creation', [ $this, 'directorist_ai_directory' ] );
    }

    public static function builder_data_backup( $term_id ) {
        $submission_form_fields     = get_term_meta( $term_id , 'submission_form_fields', true );
        $single_listings_contents   = get_term_meta( $term_id, 'single_listings_contents', true );
        $single_listing_header      = get_term_meta( $term_id, 'single_listing_header', true );

        // Fetch existing backup data from the option
        $existing_backup_data = get_option( 'directorist_builder_backup_data', [] );
        
        // Decode the JSON data if it exists
        $existing_backup_data = ! empty( $existing_backup_data ) ? json_decode( $existing_backup_data, true ) : [];

        if( ! empty( $submission_form_fields ) && ! empty( $single_listings_contents ) && ! empty( $single_listing_header ) ) {
            $existing_backup_data[$term_id] = [
                'submission_form_fields'    => $submission_form_fields,
                'single_listings_contents'  => $single_listings_contents,
                'single_listing_header'     => $single_listing_header,
            ];
            // Convert the backup data to JSON format
            $json_backup_data = wp_json_encode( $existing_backup_data );

            // Save the JSON backup data in options
            update_option( 'directorist_builder_backup_data', $json_backup_data );
        }
    }

    // custom field assign to category migration
    public static function migrate_custom_field( $term_id ) {

        $submission_form_fields = get_term_meta( $term_id , 'submission_form_fields', true );
        // custom field assign to category migration
        if ( empty( $submission_form_fields['fields'] ) ) {
            return;
        }

        // Modify the 'assign_to' value based on your criteria (e.g., change 'category' to 1)
        foreach ( $submission_form_fields['fields'] as $field_type => $options ) {
            if( empty( $options['assign_to'] ) ) {
                continue;
            }

            if ( $options['assign_to'] === 'category' ) {
                $submission_form_fields['fields'][ $field_type ]['assign_to'] = 1;
            } else {
                $submission_form_fields['fields'][ $field_type ]['assign_to'] = false;
            }
        }

        update_term_meta( $term_id, 'submission_form_fields', $submission_form_fields );
    }


    public static function migrate_review_settings( $term_id ) {
        $old_review_settings = get_term_meta( $term_id, 'review_config', true );
        $new_review_builder  = get_term_meta( $term_id, 'single_listings_contents', true );
    
        // Update review_cookies_consent in the 'groups' array if the review widget exists
        if ( ! empty( $old_review_settings['review_cookies_consent'] ) && is_array( $new_review_builder['groups'] ) ) {
            foreach ( $new_review_builder['groups'] as &$group ) {
                if ( isset( $group['widget_name'] ) && 'review' === $group['widget_name'] ) {
                    $group['review_cookies_consent'] = $old_review_settings['review_cookies_consent'];
                }
            }
        }
    
        // Mapping for fields outside of groups
        $fields_mapping = array(
            'review_comment' => array(
                'placeholder'       => 'review_comment_placeholder',
                'widget_name'       => 'review',
                'widget_child_name' => 'review_comment',
                'widget_key'        => 'review_comment',
                'widget_group'      => 'other_widgets',
            ),
            'review_email' => array(
                'label'             => 'review_email_label',
                'placeholder'       => 'review_email_placeholder',
                'widget_name'       => 'review',
                'widget_child_name' => 'review_email',
                'widget_key'        => 'review_email',
                'widget_group'      => 'other_widgets',
            ),
            'review_name' => array(
                'label'             => 'review_name_label',
                'placeholder'       => 'review_name_placeholder',
                'widget_name'       => 'review',
                'widget_child_name' => 'review_name',
                'widget_key'        => 'review_name',
                'widget_group'      => 'other_widgets',
            ),
            'review_website' => array(
                'enable'            => 'review_show_website_field',
                'label'             => 'review_website_label',
                'placeholder'       => 'review_website_placeholder',
                'widget_name'       => 'review',
                'widget_child_name' => 'review_website',
                'widget_key'        => 'review_website',
                'widget_group'      => 'other_widgets',
            ),
        );
    
        // Ensure the 'fields' key exists in the new_review_builder array
        if ( ! isset( $new_review_builder['fields'] ) || ! is_array( $new_review_builder['fields'] ) ) {
            $new_review_builder['fields'] = array(); // Initialize if not present
        }
    
        // Add or update fields based on the mapping
        foreach ( $fields_mapping as $field_key => $mapping ) {
            if ( ! isset( $new_review_builder['fields'][ $field_key ] ) ) {
                $new_review_builder['fields'][ $field_key ] = array(); // Initialize the field if it doesn't exist
            }
    
            // Add or update the mapped values
            foreach ( $mapping as $new_key => $old_key ) {
                if ( $new_key === 'widget_name' || $new_key === 'widget_child_name' || $new_key === 'widget_key' || $new_key === 'widget_group' ) {
                    // Directly assign widget-related keys
                    $new_review_builder['fields'][ $field_key ][ $new_key ] = $old_key;
                } else {
                    // Assign other keys if they exist in old_review_settings
                    if ( ! empty( $old_review_settings[ $old_key ] ) ) {
                        $new_review_builder['fields'][ $field_key ][ $new_key ] = $old_review_settings[ $old_key ];
                    }
                }
            }
        }
    
        // Ensure the 'groups' key exists in the new_review_builder array
        if ( ! isset( $new_review_builder['groups'] ) || ! is_array( $new_review_builder['groups'] ) ) {
            $new_review_builder['groups'] = array(); // Initialize if not present
        }
    
        // Add or update groups with the 'review' widget
        foreach ( $new_review_builder['groups'] as &$group ) {
            if ( isset( $group['widget_name'] ) && 'review' === $group['widget_name'] ) {
                foreach ( array_keys( $fields_mapping ) as $field_key ) {
                    // Add the field to the group if it doesn't already exist
                    if ( ! in_array( $field_key, $group['fields'] ) ) {
                        $group['fields'][] = $field_key;
                    }
                }
            }
        }
    
        // Update the term meta with the modified new_review_builder array
        update_term_meta( $term_id, 'single_listings_contents', $new_review_builder );
    }

    public static function migrate_contact_owner_settings( $term_id ) {
        // Get the current settings
        $single_listings_contents = get_term_meta( $term_id, 'single_listings_contents', true );
    
        // Check if necessary fields exist
        if ( empty( $single_listings_contents['fields'] ) || empty( $single_listings_contents['groups'] ) || ! is_array( $single_listings_contents['groups'] ) ) {
            return;
        }
    
        // Define the fields mapping
        $fields_mapping = array(
            'contact_name'    => array(
                'enable'            => 1,
                'placeholder'       => __( 'Name', 'directorist' ),
                'widget_group'      => 'other_widgets',
                'widget_name'       => 'contact_listings_owner',
                'widget_child_name' => 'contact_name',
                'widget_key'        => 'contact_name',
            ),
            'contact_email'   => array(
                'placeholder'       => __( 'Email', 'directorist' ),
                'widget_group'      => 'other_widgets',
                'widget_name'       => 'contact_listings_owner',
                'widget_child_name' => 'contact_email',
                'widget_key'        => 'contact_email',
            ),
            'contact_message' => array(
                'placeholder'       => __( 'Message...', 'directorist' ),
                'widget_group'      => 'other_widgets',
                'widget_name'       => 'contact_listings_owner',
                'widget_child_name' => 'contact_message',
                'widget_key'        => 'contact_message',
            ),
        );
    
        // Iterate over groups and update the contact listings owner group
        foreach ( $single_listings_contents['groups'] as &$group ) {
            if ( isset( $group['widget_name'] ) && 'contact_listings_owner' === $group['widget_name'] ) {
                foreach ( $fields_mapping as $field_key => $mapping ) {
                    // Add or update fields
                    $single_listings_contents['fields'][ $field_key ] = $mapping;
    
                    // Ensure the field is added to the group's fields
                    if ( ! in_array( $field_key, $group['fields'], true ) ) {
                        $group['fields'][] = $field_key;
                    }
                }
            }
        }
    
        // Update the term meta with the modified contents
        update_term_meta( $term_id, 'single_listings_contents', $single_listings_contents );
    }

    public static function migrate_related_listing_settings( $term_id ) {
        $number              = get_term_meta( $term_id, 'similar_listings_number_of_listings_to_show', true );
        $same_author         = get_term_meta( $term_id, 'listing_from_same_author', true );
        $logic               = get_term_meta( $term_id, 'similar_listings_logics', true );
        $column              = get_term_meta( $term_id, 'similar_listings_number_of_columns', true );
        $new_related_listing = get_term_meta( $term_id, 'single_listings_contents', true );
        
        if ( ! empty( $new_related_listing['groups'] ) && is_array( $new_related_listing['groups'] ) ) {
            foreach ( $new_related_listing['groups'] as &$group ) {
                if ( isset( $group['widget_name'] ) && 'related_listings' === $group['widget_name'] ) {
                    $group['similar_listings_logics']                     = $logic ?? 'OR';
                    $group['listing_from_same_author']                    = $same_author ?? false;
                    $group['similar_listings_number_of_listings_to_show'] = absint( $number ?? 3 );
                    $group['similar_listings_number_of_columns']          = absint( $column ?? 3 );
                }
            }
        }

        update_term_meta( $term_id, 'single_listings_contents', $new_related_listing );
    }

    public static function migrate_privacy_policy( $term_id ) {
        $display_privacy     = (bool) get_directorist_type_option( $term_id, 'listing_privacy' );
        $privacy_is_required = (bool) get_directorist_type_option( $term_id, 'privacy_is_required' );
        $display_terms       = (bool) get_directorist_type_option( $term_id, 'listing_terms_condition' );
        $terms_is_required   = (bool) get_directorist_type_option( $term_id, 'require_terms_conditions' );
        $submission_form     = get_term_meta( $term_id, 'submission_form_fields', true );
    
        // Generate the label with links to Privacy Policy and Terms of Service
        $terms_privacy_label = sprintf(
            __( 'I agree to the <a href="%s" target="_blank">Privacy Policy</a> and <a href="%s" target="_blank">Terms of Service</a>', 'directorist' ),
            \ATBDP_Permalink::get_privacy_policy_page_url(),
            \ATBDP_Permalink::get_terms_and_conditions_page_url()
        );
    
        // Determine if the field should be required
        $is_required = ( $privacy_is_required || $terms_is_required ) ? 1 : '';
    
        // Define the new field for terms and privacy
        $terms_privacy_field = [
            'type'         => 'text',
            'field_key'    => 'privacy_policy',
            'text'         => $terms_privacy_label,   // Use the generated label
            'required'     => $is_required,           // Dynamically set required status
            'widget_group' => 'preset',
            'widget_name'  => 'terms_privacy',
            'widget_key'   => 'terms_privacy',
        ];
    
        // Check if either privacy or terms should be displayed
        if ( $display_privacy || $display_terms ) {
            // Add the new field to the fields array
            $submission_form['fields']['terms_privacy'] = $terms_privacy_field;
            // Add the 'terms_privacy' field to the last group in the 'groups' array
            $last_group_key = array_key_last( $submission_form['groups'] ); // Get the last group key
            $submission_form['groups'][ $last_group_key ]['fields'][] = 'terms_privacy'; // Add to the last group's fields
        }
    
        // Update the term meta with the modified submission_form array
        update_term_meta( $term_id, 'submission_form_fields', $submission_form );
    }

    // add_missing_single_listing_section_id
    public function add_missing_single_listing_section_id() {
        $directory_types = directorist_get_directories();

        if ( is_wp_error( $directory_types ) || empty( $directory_types ) ) {
            return;
        }

        foreach ( $directory_types as $directory_type ) {
            $single_listings_contents = get_term_meta( $directory_type->term_id, 'single_listings_contents', true );
            $need_to_update = false;

            if ( empty( $single_listings_contents ) ) {
                continue;
            }

            if ( empty( $single_listings_contents['groups'] ) ) {
                continue;
            }

            foreach ( $single_listings_contents['groups'] as $group_index => $group ) {
                $has_section_id = ( ! empty( $group['section_id'] ) ) ? true : false;
                $renew = ( $has_section_id ) ? false : true;
                $renew = apply_filters( 'directorist_renew_single_listing_section_id', $renew );

                if ( ! $renew ) {
                    continue;
                }

                $group['section_id'] = $group_index + 1;
                $single_listings_contents['groups'][ $group_index ] = $group;
                $need_to_update = true;
            }

            if ( $need_to_update ) {
                update_term_meta( $directory_type->term_id, 'single_listings_contents', $single_listings_contents );
            }
        }

    }

    // update_default_directory_type_option
    public function update_default_directory_type_option() {
        $args = array(
            'meta_query' => array(
                array(
                    'key'   => '_default',
                    'value' => true,
                )
            ),
        );

        $default_directory = get_directorist_option( 'atbdp_default_derectory', '' );
        $terms = directorist_get_directories( $args );

        if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
            $default_directory = $terms[0]->term_id;
        }

        update_directorist_option( 'atbdp_default_derectory', $default_directory );
    }

    // setup_migration
    public function setup_migration() {
        $migrated = get_option( 'atbdp_migrated', false );
        $need_migration = ( empty( $migrated ) && ! self::has_multidirectory() && self::has_old_listings_data() ) ? true : false;

        if ( $need_migration ) {
            $this->prepare_settings();
            self::$migration->migrate();
            return;
        }

        $need_import_default = ( ! self::has_multidirectory() ) ? true : false;

        if ( apply_filters( 'atbdp_import_default_directory', $need_import_default ) ) {
            $this->prepare_settings();
            $this->import_default_directory();
        }
    }

    // has_multidirectory
    public static function has_multidirectory() {
        $directory_types = directorist_get_directories();

        return ( ! is_wp_error( $directory_types ) && ! empty( $directory_types ) ) ? true : false;
    }

    // has_old_listings_data
    public static function has_old_listings_data() {
        $get_listings = new \WP_Query([
            'post_type'      => ATBDP_POST_TYPE,
            'posts_per_page' => 1,
            'fields'         => 'ids',
        ]);

        $get_custom_fields = new \WP_Query([
            'post_type'      => ATBDP_CUSTOM_FIELD_POST_TYPE,
            'posts_per_page' => 1,
            'fields'         => 'ids',
        ]);

        $has_listings          = $get_listings->post_count;
        $has_custom_fields     = $get_custom_fields->post_count;

        return ( $has_listings || $has_custom_fields ) ? true : false;
    }

    // handle_force_migration
    public function handle_force_migration() {
        if ( ! directorist_verify_nonce() ) {
            wp_send_json([
                'status' => [
                    'success' => false,
                    'message' => __( 'Something is wrong! Please refresh and retry.', 'directorist' ),
                ],
            ], 200);
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json([
                'status' => [
                    'success' => false,
                    'message' => __( 'You are not allowed to access this resource', 'directorist' ),
                ],
            ], 200);
        }

        wp_send_json( $this->run_force_migration() );
    }

    // show the ai form
    public function directorist_ai_directory_form() {

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json([
                'status' => [
                    'success' => false,
                    'message' => __( 'You are not allowed to access this resource', 'directorist' ),
                ],
            ], 200);
        }

        $installed['success'] = true;
        ob_start();

        atbdp_load_admin_template('post-types-manager/ai/step-one', []);
        
        $form = ob_get_clean();

        $installed['html'] = $form;
        wp_send_json( $installed );
    }

    // handle step one
    public function directorist_ai_directory() {

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json([
                'status' => [
                    'success' => false,
                    'message' => __( 'You are not allowed to access this resource', 'directorist' ),
                ],
            ], 200);
        }

        $prompt     = ! empty( $_POST['prompt'] ) ? $_POST['prompt'] : '';
        $keywords   = ! empty( $_POST['keywords'] ) ? $_POST['keywords'] : '';
        $step       = ! empty( $_POST['step'] ) ? $_POST['step'] : '';
        $name       = ! empty( $_POST['name'] ) ? $_POST['name'] : '';
        $fields     = ! empty( $_POST['fields'] ) ? $_POST['fields'] : [];

        if( 1 == $step ) {
            $html = $this->ai_create_keywords( $prompt );
        }

        if( 3 == $step ) {
            $response = $this->ai_create_fields( $prompt, $keywords );
            wp_send_json([
                'success' => true,
                'html' => $response['html'],
                'fields' => $response['fields'],
            ]);

        }

        if( 3 < $step ) {
            $html = $this->build_directory( $name, $fields, $step );
        }


        $installed['success'] = true;
        $installed['html'] = $html;
        wp_send_json( $installed );
    }

    private function merge_ai_fields($existing_config, $new_fields, $name ) {

 
  
            // Decode new fields JSON safely
    $new_fields_array = json_decode(stripslashes($new_fields), true);

    if (is_null($new_fields_array)) {
        // throw new Exception('Failed to decode new fields JSON: ' . json_last_error_msg());
    }

    // Reformat new fields to match the old format and ensure unique field keys for same type fields
    $type_counts = [];
    $formatted_fields = [];
    foreach ($new_fields_array as $field) {
        $type = strtolower($field['type']);
        if (!isset($type_counts[$type])) {
            $type_counts[$type] = 0;
        } else {
            $type_counts[$type]++;
        }
        $suffix = $type_counts[$type] > 0 ? '-' . $type_counts[$type] : '';
        $field_key = 'custom-' . $type . $suffix;

        $formatted_fields[$type] = array_merge($field, [
            'widget_group' => 'custom',
            'widget_name' => $type,
            'field_key' => $field_key,
            'widget_key' => $type . $suffix,
        ]);
    }

    // Keep old title and description fields
    $title_description_fields = array_intersect_key(
        $existing_config['submission_form_fields']['fields'] ?? [],
        array_flip(['title', 'description'])
    );

    // Replace the old fields with new fields, keeping title and description
    $existing_config['submission_form_fields']['fields'] = array_merge(
        $title_description_fields,
        $formatted_fields
    );

    // Replace old groups with a new group containing the new fields and keeping title and description
    $existing_config['submission_form_fields']['groups'] = [
        [
            "type" => "general_group",
            "label" => "General Information",
            "fields" => array_merge(['title', 'description'], array_keys($formatted_fields)),
            "defaultGroupLabel" => "Section",
            "disableTrashIfGroupHasWidgets" => [
                [
                    "widget_name" => "title",
                    "widget_group" => "preset"
                ]
            ],
            "icon" => "las la-pen-nib",
        ]
    ];

    return $existing_config;


    }

    public function build_directory( $name, $fields, $step = '' ){

        $builder_content = directorist_get_json_from_url( 'http://app.directorist.com/wp-content/uploads/2024/10/business-1.zip' );

        $updated_config = $this->merge_ai_fields($builder_content, $fields, $name );


        self::prepare_settings();
        $term = self::add_directory([
            'directory_name' => $name . $step,
            'fields_value'   => $updated_config,
            'is_json'        => false
        ]);

        if( ! $term['status']['success'] ) {
            $term_id = $term['status']['term_id'];
        }else{
            $term_id = $term['term_id'];
        }

        return [
            'name' => $name,
            'term_id' => $term_id,
            'fields' => $fields,
            'ready_builder' => $builder_content,
            'updated' => $updated_config,
        ];
    }

    public function ai_create_fields( $prompt ) {

        $prompt = $prompt . '. I need the listing page fields list. For each field, return an array with the following keys:
"label": The label of the field without the @@.
"type": The input type (e.g., <input type="text">, <textarea>, etc.).
"options": An array of options if applicable (for select, radio, or checkbox fields), otherwise an empty array.
Return the result as an array of associative arrays in PHP format.';

        $response = directorist_get_form_groq_ai( $prompt );

        if( ! $response ) {
            wp_send_json([
                'status' => [
                    'success' => false,
                    'message' => __( 'Something went wrong, please try again', 'directorist' ),
                ],
            ], 200);
        }

        $start = strpos($response, 'array('); // Start position of the array
        $end = strrpos($response, ')') + 1; // End position of the array (the closing parenthesis)
        $php_array_string = substr($response, $start, $end - $start);
        
        // Output the extracted PHP array
        // echo $php_array_string;
        
        // Evaluate the array string into a PHP array
        $fields = eval("return $php_array_string;");

        ob_start();?>

        <?php 
        if( ! empty( $fields ) ) {
            foreach( $fields as $field ) { 
                $label      = ! empty( $field['label'] ) ? $field['label'] : '';
                $type       = ! empty( $field['type'] ) ? $field['type'] : '';
                $options    = ! empty( $field['options'] ) ? $field['options'] : '';
                ?>
                <div class="directorist-ai-generate-box__item">
                    <div class="directorist-ai-generate-dropdown" aria-expanded="false">
                        <div class="directorist-ai-generate-dropdown__header">
                            <div class="directorist-ai-generate-dropdown__header-title">
                                <div class="directorist-ai-generate-dropdown__pin-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M11.0616 1.29452C11.4288 1.05364 11.8763 0.967454 12.3068 1.05472C12.6318 1.12059 12.8801 1.29569 13.0651 1.44993C13.2419 1.59735 13.4399 1.79537 13.653 2.00849L17.9857 6.34116C18.1988 6.55424 18.3968 6.75223 18.5442 6.92908C18.6985 7.11412 18.8736 7.36242 18.9395 7.6874C19.0267 8.11785 18.9405 8.56535 18.6997 8.93261C18.5178 9.20988 18.263 9.3754 18.0511 9.48992C17.8485 9.59937 17.5911 9.70966 17.3141 9.82836L15.2051 10.7322C15.1578 10.7525 15.1347 10.7624 15.118 10.77C15.1176 10.7702 15.1173 10.7704 15.1169 10.7705C15.1166 10.7708 15.1163 10.7711 15.116 10.7714C15.1028 10.7841 15.0849 10.8018 15.0485 10.8382L13.7478 12.1389C13.6909 12.1959 13.6629 12.224 13.6432 12.2451C13.6427 12.2456 13.6423 12.2461 13.6418 12.2466C13.6417 12.2472 13.6415 12.2479 13.6414 12.2486C13.6347 12.2767 13.6268 12.3155 13.611 12.3944L12.9932 15.4835C12.92 15.8499 12.8541 16.1794 12.7773 16.438C12.7004 16.6969 12.5739 17.0312 12.289 17.2841C11.9244 17.6075 11.4366 17.7552 10.9539 17.6883C10.5765 17.636 10.2858 17.4279 10.0783 17.2552C9.87091 17.0826 9.63334 16.845 9.36915 16.5808L6.98049 14.1922L2.85569 18.317C2.53026 18.6424 2.00262 18.6424 1.67718 18.317C1.35175 17.9915 1.35175 17.4639 1.67718 17.1384L5.80198 13.0136L3.41338 10.625C3.14915 10.3608 2.91154 10.1233 2.73896 9.91588C2.56626 9.70833 2.3582 9.41765 2.30588 9.04027C2.23896 8.55756 2.38666 8.06974 2.7101 7.70522C2.96297 7.42025 3.29732 7.29379 3.55615 7.2169C3.81479 7.14007 4.14427 7.0742 4.51068 7.00094L7.59973 6.38313C7.67866 6.36735 7.71751 6.35946 7.7456 6.35282C7.74629 6.35266 7.74696 6.3525 7.7476 6.35234C7.74808 6.3519 7.74858 6.35143 7.7491 6.35095C7.7702 6.33126 7.79832 6.3033 7.85523 6.24639L9.15597 4.94565C9.19239 4.90923 9.21013 4.89143 9.22278 4.87819C9.22308 4.87787 9.22336 4.87757 9.22364 4.87729C9.22381 4.87692 9.22398 4.87654 9.22416 4.87615C9.23175 4.85949 9.24169 4.83641 9.26199 4.78906L10.1658 2.68009C10.2845 2.40306 10.3948 2.14567 10.5043 1.94311C10.6188 1.73117 10.7843 1.47638 11.0616 1.29452ZM10.5222 15.3768C10.82 15.6746 11.003 15.8565 11.1444 15.9741C11.1535 15.9817 11.162 15.9886 11.1699 15.995C11.173 15.9853 11.1762 15.9748 11.1796 15.9634C11.232 15.7871 11.2834 15.5343 11.366 15.1213L11.9767 12.0676C11.9787 12.0577 11.9808 12.0475 11.9828 12.037C12.0055 11.9222 12.0342 11.7776 12.0892 11.6374C12.1369 11.5156 12.1989 11.3999 12.2737 11.2926C12.3599 11.169 12.4643 11.065 12.5472 10.9825C12.5547 10.9749 12.5621 10.9676 12.5693 10.9604L13.87 9.6597C13.8746 9.65514 13.8793 9.65044 13.884 9.64564C13.9371 9.59249 14.0038 9.52556 14.08 9.46504C14.1462 9.41243 14.2164 9.36493 14.2899 9.32297C14.3743 9.2747 14.4613 9.23757 14.5303 9.20809C14.5366 9.20543 14.5427 9.20282 14.5486 9.20028L16.6272 8.30944C16.9451 8.17321 17.1311 8.09262 17.2588 8.02362C17.2656 8.01993 17.272 8.01642 17.2779 8.0131C17.2736 8.00783 17.269 8.00221 17.264 7.99624C17.1711 7.88475 17.0283 7.74085 16.7838 7.49631L12.4979 3.21037C12.2533 2.96583 12.1094 2.82309 11.9979 2.73014C11.992 2.72517 11.9864 2.72057 11.9811 2.71631C11.9778 2.72222 11.9743 2.72858 11.9706 2.73541C11.9016 2.86312 11.821 3.04909 11.6847 3.36696L10.7939 5.44559C10.7914 5.45153 10.7887 5.45762 10.7861 5.46387C10.7566 5.53289 10.7195 5.61984 10.6712 5.70432C10.6292 5.77777 10.5817 5.84793 10.5291 5.91417C10.4686 5.99036 10.4017 6.05713 10.3485 6.11013C10.3437 6.11492 10.339 6.1196 10.3345 6.12417L9.03374 7.4249C9.02658 7.43206 9.01924 7.43943 9.01172 7.44698C8.92915 7.52989 8.82513 7.63432 8.70159 7.72048C8.59429 7.79531 8.47855 7.85725 8.35677 7.90502C8.21655 7.96002 8.07196 7.98864 7.95717 8.01136C7.94673 8.01343 7.93652 8.01544 7.92659 8.01743L4.87287 8.62817C4.45985 8.71078 4.20704 8.7622 4.03076 8.81456C4.0194 8.81794 4.00891 8.82117 3.99923 8.82425C4.00557 8.83219 4.01251 8.84071 4.02009 8.84981C4.13771 8.99116 4.31954 9.17418 4.61738 9.47202L10.5222 15.3768Z" fill="currentColor"></path>
                                    </svg>
                                </div>
                                <div class="directorist-ai-generate-dropdown__title">
                                    <div class="directorist-ai-generate-dropdown__title-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M7.86583 1.16656C7.89797 1.16659 7.93036 1.16662 7.96302 1.16662L11.2869 1.16662C11.3196 1.16662 11.3519 1.16659 11.3841 1.16656C11.8588 1.16612 12.2773 1.16573 12.6838 1.26333C13.041 1.34907 13.3824 1.49048 13.6955 1.68238C14.052 1.90083 14.3477 2.19705 14.683 2.53304C14.7057 2.55578 14.7286 2.57871 14.7517 2.6018L23.7325 11.5826C24.3966 12.2466 24.9447 12.7947 25.3547 13.2777C25.7805 13.7793 26.1254 14.2868 26.3218 14.8912C26.6263 15.8285 26.6263 16.8381 26.3218 17.7754C26.1254 18.3798 25.7805 18.8873 25.3547 19.3889C24.9447 19.8719 24.3966 20.4199 23.7325 21.084L21.0839 23.7325C20.4199 24.3966 19.8718 24.9447 19.3889 25.3547C18.8873 25.7805 18.3797 26.1254 17.7754 26.3218C16.8381 26.6263 15.8285 26.6263 14.8912 26.3218C14.2868 26.1254 13.7793 25.7805 13.2777 25.3547C12.7947 24.9447 12.2466 24.3966 11.5826 23.7325L2.6018 14.7517C2.5787 14.7286 2.55577 14.7057 2.53303 14.683C2.19705 14.3477 1.90082 14.052 1.68238 13.6955C1.49048 13.3824 1.34907 13.041 1.26333 12.6838C1.16573 12.2773 1.16612 11.8588 1.16656 11.3841C1.16659 11.3519 1.16662 11.3196 1.16662 11.2869L1.16662 7.96302C1.16662 7.93036 1.16659 7.89796 1.16656 7.86583C1.16612 7.39112 1.16573 6.97258 1.26333 6.56606C1.34907 6.20893 1.49048 5.86753 1.68238 5.55438C1.90082 5.19791 2.19705 4.90224 2.53303 4.56688C2.55578 4.54418 2.5787 4.52129 2.6018 4.4982L4.4982 2.6018C4.52129 2.5787 4.54418 2.55578 4.56688 2.53303C4.90224 2.19705 5.19791 1.90082 5.55438 1.68238C5.86753 1.49048 6.20894 1.34907 6.56606 1.26333C6.97258 1.16573 7.39112 1.16612 7.86583 1.16656ZM7.96302 3.49996C7.33678 3.49996 7.21381 3.50745 7.11077 3.53219C6.99173 3.56077 6.87792 3.60791 6.77354 3.67188C6.68319 3.72724 6.59093 3.80889 6.14811 4.25171L4.25172 6.14811C3.8089 6.59093 3.72724 6.68319 3.67188 6.77354C3.60791 6.87792 3.56077 6.99172 3.53219 7.11077C3.50745 7.21381 3.49996 7.33678 3.49996 7.96302L3.49996 11.2869C3.49996 11.9131 3.50745 12.0361 3.53219 12.1391C3.56077 12.2582 3.60791 12.372 3.67188 12.4764C3.72724 12.5667 3.80889 12.659 4.25171 13.1018L13.1984 22.0485C13.9051 22.7552 14.3861 23.2349 14.7877 23.5759C15.179 23.908 15.4175 24.0394 15.6122 24.1027C16.0809 24.2549 16.5857 24.2549 17.0543 24.1027C17.2491 24.0394 17.4876 23.908 17.8788 23.5759C18.2805 23.2349 18.7615 22.7552 19.4681 22.0485L22.0485 19.4681C22.7552 18.7615 23.2349 18.2805 23.5759 17.8788C23.908 17.4876 24.0394 17.2491 24.1027 17.0543C24.2549 16.5857 24.2549 16.0809 24.1027 15.6122C24.0394 15.4175 23.908 15.179 23.5759 14.7877C23.2349 14.3861 22.7552 13.9051 22.0485 13.1984L13.1018 4.25172C12.659 3.8089 12.5667 3.72724 12.4764 3.67188C12.372 3.60791 12.2582 3.56077 12.1391 3.53219C12.0361 3.50745 11.9131 3.49996 11.2869 3.49996L7.96302 3.49996ZM7.58329 9.33329C7.58329 8.36679 8.36679 7.58329 9.33329 7.58329C10.2998 7.58329 11.0833 8.36679 11.0833 9.33329C11.0833 10.2998 10.2998 11.0833 9.33329 11.0833C8.36679 11.0833 7.58329 10.2998 7.58329 9.33329Z" fill="#4D5761"></path>
                                        </svg>
                                    </div>
                                    <div class="directorist-ai-generate-dropdown__title-main">
                                        <h6><?php echo $label; ?></h6>
                                        <?php 
                                        if( $options ):
                                        ?>
                                        <p>Select option</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="directorist-ai-generate-dropdown__header-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M4.41058 6.91058C4.73602 6.58514 5.26366 6.58514 5.58909 6.91058L9.99984 11.3213L14.4106 6.91058C14.736 6.58514 15.2637 6.58514 15.5891 6.91058C15.9145 7.23602 15.9145 7.76366 15.5891 8.08909L10.5891 13.0891C10.2637 13.4145 9.73602 13.4145 9.41058 13.0891L4.41058 8.08909C4.08514 7.76366 4.08514 7.23602 4.41058 6.91058Z" fill="#4D5761"></path>
                                </svg>
                            </div>
                        </div>
                        <?php if( $options ): ?>
                            <div class="directorist-ai-generate-dropdown__content" aria-expanded="false">
                            <div class="directorist-ai-keyword-field">
                                <div class="directorist-ai-keyword-field__items">
                                    <div class="directorist-ai-keyword-field__item">
                                        <div class="directorist-ai-keyword-field__list">
                                            <?php foreach( $options as $option ) :?>
                                            <div class="directorist-ai-keyword-field__list-item --px-12 --h-32">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M14.7046 5.88371C12.78 3.67922 9.50502 3.0689 6.87496 4.58736C5.07624 5.62586 3.98849 7.42276 3.78397 9.3442C3.73525 9.80185 3.32476 10.1334 2.86711 10.0847C2.40946 10.0359 2.07795 9.62545 2.12666 9.16779C2.38514 6.73941 3.76205 4.4601 6.04163 3.14399C9.48233 1.15749 13.7944 2.04731 16.1935 5.06737L16.284 4.72948C16.4031 4.28493 16.8601 4.02111 17.3046 4.14023C17.7492 4.25935 18.013 4.71629 17.8939 5.16085L17.2838 7.43756C17.2266 7.65104 17.087 7.83306 16.8956 7.94356C16.7042 8.05407 16.4767 8.08402 16.2632 8.02681L13.9865 7.41677C13.542 7.29765 13.2781 6.84071 13.3973 6.39615C13.5164 5.9516 13.9733 5.68778 14.4179 5.8069L14.7046 5.88371ZM17.1326 9.91571C17.5902 9.96443 17.9217 10.3749 17.873 10.8326C17.6145 13.261 16.2376 15.5403 13.958 16.8564C10.5175 18.8428 6.20571 17.9531 3.80654 14.9334L3.71611 15.2709C3.597 15.7154 3.14005 15.9793 2.69549 15.8601C2.25094 15.741 1.98712 15.2841 2.10624 14.8395L2.71628 12.5628C2.8354 12.1183 3.29235 11.8544 3.7369 11.9736L6.01361 12.5836C6.45817 12.7027 6.72198 13.1597 6.60287 13.6042C6.48375 14.0488 6.0268 14.3126 5.58225 14.1935L5.29497 14.1165C7.21956 16.3211 10.4946 16.9315 13.1247 15.413C14.9234 14.3745 16.0112 12.5776 16.2157 10.6562C16.2644 10.1985 16.6749 9.867 17.1326 9.91571Z" fill="#4D5761"></path>
                                                </svg>
                                                <?php echo $option; ?>
                                            </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php }
        }

        $html = ob_get_clean();

        return [
            'fields' => $fields,
            'html' => $html,
        ];
    }

    public function ai_create_keywords( $prompt ) {
        $prompt = "$prompt. Give me 10 relative keywords separated by @. Don't use anything like 'Here is the ten possible keywords'";
        $response = directorist_get_form_groq_ai( $prompt );

        if( ! $response ) {
            wp_send_json([
                'status' => [
                    'success' => false,
                    'message' => __( 'Something went wrong, please try again', 'directorist' ),
                ],
            ], 200);
        }

        $list = explode("@", $response);

        // Trim any leading/trailing spaces from each element
        $list = array_map('trim', $list);

        ob_start();?>

        <?php 
        if( ! empty( $list ) ) {
            foreach( $list as $keyword ) { ?>
                <li class="free-enabled"><?php echo ucwords( $keyword ); ?></li>
            <?php }
        }

        return ob_get_clean();
    }

    public function directorist_directory_type_library() {

        if ( ! directorist_verify_nonce() ) {
            wp_send_json([
                'status' => [
                    'success' => false,
                    'message' => __( 'Something is wrong! Please refresh and retryyy.', 'directorist' ),
                ],
            ], 200);
        }

        if ( ! current_user_can( 'install_plugins' ) || ! current_user_can( 'activate_plugins' ) ) {
            wp_send_json([
                'status' => [
                    'success' => false,
                    'message' => __( 'You are not allowed to add/activate new plugin', 'directorist' ),
                ],
            ], 200);
        }

        $installed = directorist_download_plugin( [ 'url' => 'https://downloads.wordpress.org/plugin/templatiq.zip' ] );
        $path = WP_PLUGIN_DIR . '/templatiq/templatiq.php';

        if( ! is_plugin_active( $path ) ){
            activate_plugin( $path );
        }

        $installed['redirect'] = admin_url( 'admin.php?page=templatiq' );
        wp_send_json( $installed );
    }

    // run_force_migration
    public function run_force_migration() {
        $general_directory = term_exists( 'General', 'atbdp_listing_types' );
        $args = [];

        if ( $general_directory ) {
            $args[ 'term_id' ] = $general_directory['term_id'];
        }

        $this->prepare_settings();
        $migration_status = self::$migration->migrate( $args );

        $status = [
            'success' => $migration_status['success'],
            'message' => ( $migration_status ) ? __( 'Migration Successful', 'directorist' ) : __( 'Migration Failed', 'directorist' ),
        ];

        return $status;
    }

    // import_default_directory
    public function import_default_directory( array $args = [] ) {
        $file = DIRECTORIST_ASSETS_DIR . 'sample-data/directory/directory.json';
        if ( ! file_exists( $file ) ) { return; }
        $file_contents = file_get_contents( $file );

        $add_directory = self::add_directory([
            'directory_name' => 'General',
            'fields_value'   => $file_contents,
            'is_json'        => true
        ]);

        if ( $add_directory['status']['success'] ) {
            update_option( 'atbdp_has_multidirectory', true );
            update_term_meta( $add_directory['term_id'], '_default', true );

            // Add directory type to all listings
            $listings = new \WP_Query([
                'post_type' => ATBDP_POST_TYPE,
                'status'    => 'publish',
                'per_page'  => -1,
            ]);

            if ( $listings->have_posts() ) {
                while ( $listings->have_posts() ) {
                    $listings->the_post();

                    wp_set_object_terms( get_the_id(), $add_directory['term_id'], 'atbdp_listing_types' );
                }
                wp_reset_postdata();
            }
        }
    }

    public function save_imported_post_type_data() {

        if ( ! directorist_verify_nonce() ) {
            wp_send_json([
                'status' => [
                    'success' => false,
                    'status_log' => [
                        'nonce_is_missing' => [
                            'type' => 'error',
                            'message' => __( 'Something is wrong! Please refresh and retry.', 'directorist' ),
                        ],
                    ],
                ],
            ], 200);
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json([
                'status' => [
                    'success' => false,
                    'status_log' => [
                        'access_denied' => [
                            'type' => 'error',
                            'message' => __( 'You are not allowed to access this resource', 'directorist' ),
                        ],
                    ],
                ],
            ], 200);
        }

        $term_id        = ( ! empty( $_POST[ 'term_id' ] ) ) ? absint( $_POST[ 'term_id' ] ) : 0;
        $directory_name = ( ! empty( $_POST[ 'directory-name' ] ) ) ? sanitize_text_field( wp_unslash( $_POST[ 'directory-name' ] ) ) : '';
        $json_file      = ( ! empty( $_FILES[ 'directory-import-file' ] ) ) ? directorist_clean( wp_unslash( $_FILES[ 'directory-import-file' ] ) ) : '';

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
                'message' => __( 'File is missing', 'directorist' ),
            ];

            $response['status']['error_count']++;
        }

        // Validate file data
        $file_contents = file_get_contents( $json_file['tmp_name'] );
        if ( empty( $file_contents ) ) {
            $response['status']['status_log']['invalid_data'] = [
                'type' => 'error',
                'message' => __( 'The data is invalid', 'directorist' ),
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

        $this->prepare_settings();

        $add_directory = self::add_directory([
            'term_id'        => $term_id,
            'directory_name' => $directory_name,
            'fields_value'   => $file_contents,
            'is_json'        => true
        ]);

        wp_send_json( $add_directory, 200 );
    }


    // cptm_fields_before_update
    public function cptm_fields_before_update( $fields ) {
        $new_fields     = $fields;
        $fields_group   = self::$config['fields_group'];

        foreach ( $fields_group as $group_name => $group_fields ) {
            $grouped_fields_value = [];

            foreach ( $group_fields as $field_index => $field_key ) {
                if ( is_string( $field_key ) && array_key_exists($field_key, self::$fields)) {
                    $grouped_fields_value[ $field_key ] = ( isset( $new_fields[ $field_key ] ) ) ? $new_fields[ $field_key ] : '';
                    unset( $new_fields[ $field_key ] );
                }

                if ( is_array( $field_key ) ) {
                    $grouped_fields_value[ $field_index ] = [];

                    foreach ( $field_key as $sub_field_key ) {
                        if ( array_key_exists( $sub_field_key, self::$fields ) ) {
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
        if ( ! directorist_verify_nonce() ) {
            wp_send_json([
                'status' => [
                    'success' => false,
                    'status_log' => [
                        'nonce_is_missing' => [
                            'type' => 'error',
                            'message' => __( 'Something is wrong! Please refresh and retry.', 'directorist' ),
                        ],
                    ],
                ],
            ], 200);
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json([
                'status' => [
                    'success' => false,
                    'status_log' => [
                        'access_denied' => [
                            'type' => 'error',
                            'message' => __( 'You are not allowed to access this resource', 'directorist' ),
                        ],
                    ],
                ],
            ], 200);
        }

        if ( empty( $_POST['name'] ) ) {
            wp_send_json([
                'status' => [
                    'success' => false,
                    'status_log' => [
                        'name_is_missing' => [
                            'type' => 'error',
                            'message' => __( 'Name is missing', 'directorist' ),
                        ],
                    ],
                ],
            ], 200);
        }

        $term_id        = ( ! empty( $_POST['listing_type_id'] ) ) ? absint( $_POST['listing_type_id'] ) : 0;
        $directory_name = sanitize_text_field( wp_unslash( $_POST['name'] ) );

        $fields     = [];
        $field_list = ! empty( $_POST['field_list'] ) ? directorist_maybe_json( wp_unslash( $_POST['field_list'] ) ) : [];

        foreach ( $field_list as $field_key ) {
            if ( isset( $_POST[$field_key] ) && 'name' !==  $field_key ) {
                $fields[ $field_key ] = directorist_maybe_json(
                    wp_unslash( $_POST[ $field_key ] ),
                    true,
                    'directorist_clean_post'
                );
            }
        }

        /**
         * @since 7.7.0
         * It fires before directory data updated
         */

         do_action( 'directorist_before_directory_type_updated' );

        $this->prepare_settings();

        $add_directory = self::add_directory([
            'term_id'        => $term_id,
            'directory_name' => $directory_name,
            'fields_value'   => $fields,
        ]);

        if ( ! $add_directory['status']['success'] ) {
            wp_send_json( $add_directory );
        }

        if ( directorist_is_multi_directory_enabled() && empty( $term_id ) ) {
            $redirect_url = admin_url( 'edit.php?post_type=at_biz_dir&page=atbdp-directory-types&action=edit&listing_type_id=' . $add_directory['term_id'] );
            $add_directory['redirect_url'] = $redirect_url;
        }

        wp_send_json( $add_directory );
    }

    // update_validated_term_meta
    public static function update_validated_term_meta( $term_id, $field_key, $value ) {
        if ( ! isset( self::$fields[$field_key] ) && ! array_key_exists( $field_key, self::$config['fields_group'] ) ) {
            return;
        }

        if ( ! empty( self::$fields[$field_key]['type'] ) && 'toggle' === self::$fields[$field_key]['type'] ) {
            $value = ('true' === $value || true === $value || '1' === $value || 1 === $value) ? true : 0;
        }

        $value = directorist_maybe_json( $value, false, 'directorist_clean_post' );
        
        update_term_meta( $term_id, $field_key, $value );
    }

    public function prepare_settings() {
        if ( empty( self::$fields ) ) {
            $builder_data = new Builder_Data();

            self::$fields  = $builder_data->get_fields();
            self::$layouts = $builder_data->get_layouts();
            self::$config  = $builder_data->get_config();
            self::$options = $builder_data->get_options();
        }
    }

    // add_menu_pages
    public function add_menu_pages()
    {
        $page_title = __( 'Directory Builder', 'directorist' );
        $page_slug  = 'atbdp-layout-builder';

        if ( directorist_is_multi_directory_enabled() ) {
            $page_title = __( 'Directory Builder', 'directorist' );
            $page_slug  = 'atbdp-directory-types';
        }

        add_submenu_page(
            'edit.php?post_type=at_biz_dir',
            $page_title,
            $page_title,
            'manage_options',
            $page_slug,
            [$this, 'menu_page_callback__directory_types'],
            5
        );
    }

    // get_default_directory_id
    public function get_default_directory_id() {
        $default_directory = get_directorist_option( 'atbdp_default_derectory', '' );

        if ( ! empty( $default_directory  ) ) {
            return $default_directory;
        }

        return 0;
    }

    // menu_page_callback__directory_types
    public function menu_page_callback__directory_types()
    {
        $enable_multi_directory = directorist_is_multi_directory_enabled();

        $action = isset( $_GET['action'] ) ? sanitize_text_field( wp_unslash( $_GET['action'] ) ) : '';
        $listing_type_id = 0;

        $data = [
            'add_new_link' => admin_url('edit.php?post_type=at_biz_dir&page=atbdp-directory-types&action=add_new'),
        ];

        if ( ! $enable_multi_directory || ( ! empty( $action ) && ('edit' === $action || 'add_new' === $action ) ) ) {
            $this->prepare_settings();
            $this->add_missing_single_listing_section_id();

            $listing_type_id = ( ! empty( $_REQUEST['listing_type_id'] ) ) ? absint( $_REQUEST['listing_type_id'] ) : 0;
            $listing_type_id = ( ! $enable_multi_directory ) ? default_directory_type() : $listing_type_id;

            $this->update_fields_with_old_data( $listing_type_id );

            $directory_builder_data = [
                'fields'  => self::$fields,
                'layouts' => self::$layouts,
                'config'  => self::$config,
                'options' => self::$options,
                'id'      => $listing_type_id,
            ];

			/**
			 * Filter directory builder's all configuration data.
			 *
			 * @since 7.0.5.*
			 * TODO: Update with exact version number.
			 */
			$directory_builder_data = apply_filters( 'directorist_builder_localize_data', $directory_builder_data );

            $data[ 'directory_builder_data' ] = $directory_builder_data;

            atbdp_load_admin_template('post-types-manager/edit-listing-type', $data);
            return;
        }

        atbdp_load_admin_template('post-types-manager/all-listing-types', $data);
    }

    public function update_fields_with_old_data( $listing_type_id = 0 ) {
        $term = get_term($listing_type_id, 'atbdp_listing_types');

        if ( is_wp_error( $term ) || empty( $term ) ) {
            return;
        }

        $term_name = ( $term ) ? $term->name : '';
        $term_id   = ( $term ) ? $term->term_id : 0;

        self::$options['name']['value'] = $term_name;

        $all_term_meta = get_term_meta( $term_id );
        $test_migration = apply_filters( 'atbdp_test_migration', false );

        if ( $test_migration ) {
            $all_term_meta = self::$migration->get_fields_data();
        }

        if ( ! is_array( $all_term_meta ) ) {
			return;
		}

        foreach ( $all_term_meta as $meta_key => $meta_value ) {
            if ( isset( self::$fields[$meta_key] ) ) {
                $_meta_value = ( ! $test_migration ) ? $meta_value[0] : $meta_value;
                $value = maybe_unserialize( maybe_unserialize( $_meta_value ) );

                self::$fields[ $meta_key ]['value'] = $value;
            }
        }

        foreach (self::$config['fields_group'] as $group_key => $group_fields) {
            if (array_key_exists($group_key, $all_term_meta)) {
                $_group_meta_value = ( ! $test_migration ) ? $all_term_meta[$group_key][0] : $all_term_meta[$group_key];
                $group_value = maybe_unserialize( maybe_unserialize( $_group_meta_value ) );

                foreach ($group_fields as $field_index => $field_key) {

                    if ( ! key_exists( $field_key, $group_value ) ) { continue; }

                    if ( is_string( $field_key ) && array_key_exists($field_key, self::$fields)) {
                        self::$fields[$field_key]['value'] = $group_value[$field_key];
                    }

                    if ( is_array( $field_key ) ) {
                        foreach ($field_key as $sub_field_key) {
                            if (array_key_exists($sub_field_key, self::$fields)) {
                                self::$fields[$sub_field_key]['value'] = $group_value[$field_index][$sub_field_key];
                            }
                        }
                    }
                }
            }
        }
    }

    // handle_delete_listing_type_request
    public function handle_delete_listing_type_request()
    {
        if ( ! directorist_verify_nonce( '_wpnonce', 'delete_listing_type' ) ) {
            wp_die( esc_html__( 'Invalid request', 'directorist' ) );
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'You are not allowed to delete directory type', 'directorist' ) );
        }

        $term_id = isset( $_REQUEST['listing_type_id'] ) ? absint( $_REQUEST['listing_type_id'] ) : 0;

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
                'message' => __( 'Successfully Deleted the listing type', 'directorist' ),
            ]);
        } else {
            atbdp_add_flush_alert([
                'id'      => 'deleting_listing_type_status',
                'page'    => 'all-listing-type',
                'type'    => 'error',
                'message' => __( 'Failed to delete the listing type', 'directorist' )
            ]);
        }
    }

    // register_directory_taxonomy
    public function register_directory_taxonomy()
    {
        register_taxonomy( ATBDP_DIRECTORY_TYPE, [ ATBDP_POST_TYPE ], [
            'hierarchical' => false,
            'labels'       => [
                'name'          => _x( 'Listing Directory', 'taxonomy general name', 'directorist' ),
                'singular_name' => _x( 'Listing Directory', 'taxonomy singular name', 'directorist' ),
                'search_items'  => __( 'Search listing directory', 'directorist' ),
                'menu_name'     => __( 'Listing Directory', 'directorist' ),
            ],
			'show_ui'      => false,
        ] );
    }

    /**
     * Get all the pages in an array where each page is an array of key:value:id and key:label:name
     *
     * Example : array(
     *                  array('value'=> 1, 'label'=> 'page_name'),
     *                  array('value'=> 50, 'label'=> 'page_name'),
     *          )
     * @return array page names with key value pairs in a multi-dimensional array
     * @since 3.0.0
     */
    public function get_pages_vl_arrays()
    {
        $pages = get_pages();
        $pages_options = array();
        if ($pages) {
            foreach ($pages as $page) {
                $pages_options[] = array('value' => $page->ID, 'label' => $page->post_title);
            }
        }

        return $pages_options;
    }
}
