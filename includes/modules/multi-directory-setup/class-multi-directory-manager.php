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
        static::load_builder_data();
    }

	public static function load_builder_data() {
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
