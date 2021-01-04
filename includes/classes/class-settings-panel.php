<?php

if ( ! class_exists('ATBDP_Settings_Panel') ) {
    class ATBDP_Settings_Panel
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

            add_action( 'admin_enqueue_scripts', [$this, 'register_scripts'] );
            add_action( 'init', [$this, 'prepare_settings'] );
            // add_action( 'init', [$this, 'initial_setup'] );
            add_action( 'admin_menu', [$this, 'add_menu_pages'] );

            add_action( 'wp_ajax_save_settings_data', [ $this, 'handle_save_settings_data_request' ] );
        }

        // initial_setup
        public function initial_setup() {
            
        }

        // handle_save_settings_data_request
        public function handle_save_settings_data_request()
        {
            /* wp_send_json([
                'status' => false,
                'field_list' => $this->maybe_json( $_POST['field_list'] ),
                'status_log' => [
                    'name_is_missing' => [
                        'type' => 'error',
                        'message' => 'Debugging',
                    ],
                ],
            ], 200 ); */


            $status = [ 'success' => false, 'status_log' => [] ];
            $field_list = ( ! empty( $_POST['field_list'] ) ) ? $this->maybe_json( $_POST['field_list'] ) : [];
            
            // If field list is empty
            if ( empty( $field_list ) || ! is_array( $field_list ) ) {
                $status['status_log'] = [
                    'type' => 'success',
                    'message' => __( 'No changes made', 'directorist' ),
                ];

                wp_send_json( [ 'status' => $status, '$field_list' => $field_list ] );
            }

            $options = [];
            foreach ( $field_list as $field_key ) {
                if ( ! isset( $_POST[ $field_key ] ) ) { continue; }

                $options[ $field_key ] = $_POST[ $field_key ];
            }

            $update_settings_options = $this->update_settings_options( $options );

            wp_send_json( $update_settings_options );
        }

        // update_settings_options
        public function update_settings_options( array $options = [] ) {
            $status = [ 'success' => false, 'status_log' => [] ];

            // If field list is empty
            if ( empty( $options ) || ! is_array( $options ) ) {
                $status['status_log'] = [
                    'type' => 'success',
                    'message' => __( 'Nothing to save', 'directorist' ),
                ];

                return [ 'status' => $status ];
            }

            
            // Update the options
            $atbdp_options = get_option('atbdp_option');
            foreach ( $options as $option_key => $option_value ) {
                if ( ! isset( $this->fields[ $option_key ] ) ) { continue; }

                $atbdp_options[ $option_key ] = $this->maybe_json( $option_value );
            }

            update_option( 'atbdp_option', $atbdp_options );
            
            // Send Status
            $status['options'] = $options;
            $status['success'] = true;
            $status['status_log'] = [
                'type' => 'success',
                'message' => __( 'Saving Successful', 'directorist' ),
            ];

            return [ 'status' => $status ];
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

        // prepare_settings
        public function prepare_settings()
        {
            $business_hours_label = sprintf(__('Open Now %s', 'directorist'), !class_exists('BD_Business_Hour') ? '(Requires Business Hours extension)' : '');

            $this->fields = apply_filters('atbdp_listing_type_settings_field_list', [
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
                'fix_js_conflict' => [
                    'label' => __('Fix Conflict with Bootstrap JS', 'directorist'),
                    'type'  => 'toggle',
                    'value' => true,
                    'description' => __('If you use a theme that uses Bootstrap Framework especially Bootstrap JS, then Check this setting to fix any conflict with theme bootstrap js.', 'directorist'),
                ],
                'font_type' => [
                    'label' => __('Icon Library', 'directorist'),
                    'type'  => 'select',
                    'value' => 'line',
                    'options' => [
                        [
                            'label' => __('Font Awesome', 'directorist'),
                            'value' => 'font',
                        ],
                        [
                            'label' => __('Line Awesome', 'directorist'),
                            'value' => 'line',
                        ],
                    ],
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
                'can_renew_listing' => [
                    'label' => __('Can User Renew Listing?', 'directorist'),
                    'type'  => 'toggle',
                    'value' => true,
                    'description' => __('Here YES means users can renew their listings.', 'directorist'),
                ],
                'email_to_expire_day' => [
                    'label' => __('When to send expire notice', 'directorist'),
                    'type'  => 'number',
                    'description' => __('Select the days before a listing expires to send an expiration reminder email', 'directorist'),
                    'value' => 7,
                    'placeholder' => '10',
                    'rules' => [
                        'required' => true,
                    ],
                ],
                'email_renewal_day' => [
                    'label' => __('When to send renewal reminder', 'directorist'),
                    'type'  => 'number',
                    'description' => __('Select the days after a listing expires to send a renewal reminder email', 'directorist'),
                    'value' => 7,
                    'placeholder' => '10',
                    'rules' => [
                        'required' => true,
                    ],
                ],
                'delete_expired_listing' => [
                    'label' => __('Delete/Trash Expired Listings', 'directorist'),
                    'type'  => 'toggle',
                    'value' => true,
                ],
                'delete_expired_listings_after' => [
                    'label' => __('Delete/Trash Expired Listings After (days) of Expiration', 'directorist'),
                    'type'  => 'number',
                    'description' => __('Select the days before a listing expires to send an expiration reminder email', 'directorist'),
                    'value' => 15,
                    'placeholder' => '15',
                    'rules' => [
                        'required' => true,
                    ],
                ],
                'deletion_mode' => [
                    'label' => __('Delete or Trash Expired Listings', 'directorist'),
                    'type'  => 'select',
                    'description' => __('Choose the Default actions after a listing reaches its deletion threshold.', 'directorist'),
                    'value' => 'trash',
                    'options' => [
                        [
                            'value' => 'force_delete',
                            'label' => __('Delete Permanently', 'directorist'),
                        ],
                        [
                            'value' => 'trash',
                            'label' => __('Move to Trash', 'directorist'),
                        ],
                    ],
                ],
                'paginate_author_listings' => [
                    'label' => __('Paginate Author Listings', 'directorist'),
                    'type'  => 'toggle',
                    'value' => true,
                ],
                'display_author_email' => [
                    'label' => __('Author Email', 'directorist'),
                    'type'  => 'select',
                    'value' => 'public',
                    'options' => [
                        [
                            'value' => 'public',
                            'label' => __('Display', 'directorist'),
                        ],
                        [
                            'value' => 'logged_in',
                            'label' => __('Display only for Logged in Users', 'directorist'),
                        ],

                        [
                            'value' => 'none_to_display',
                            'label' => __('Hide', 'directorist'),
                        ],
                    ],
                ],
                'author_cat_filter' => [
                    'label' => __('Show Category Filter on Author Page', 'directorist'),
                    'type'  => 'toggle',
                    'value' => true,
                ],
                'atbdp_enable_cache' => [
                    'label' => __('Enable Cache', 'directorist'),
                    'type'  => 'toggle',
                    'value' => true,
                ],
                'atbdp_reset_cache' => [
                    'label' => __('Reset Cache', 'directorist'),
                    'type'  => 'toggle',
                    'value' => false,
                ],
                'guest_listings' => [
                    'label' => __('Guest Listing Submission', 'directorist'),
                    'type'  => 'toggle',
                    'value' => false,
                ],

                // listings page
                'display_listings_header' => [
                    'label' => __('Display Header', 'directorist'),
                    'type'  => 'toggle',
                    'value' => true,
                ],
                'all_listing_title' => [
                    'type' => 'text',
                    'label' => __('Header Title', 'directorist'),
                    'value' => __('Items Found', 'directorist'),
                ],
                'listing_filters_button' => [
                    'type' => 'toggle',
                    'label' => __('Display Filters Button', 'directorist'),
                    'value' => true,
                ],
                'listing_filters_icon' => [
                    'type' => 'toggle',
                    'label' => __('Display Filters Icon', 'directorist'),
                    'value' => true,
                ],
                'listings_filter_button_text' => [
                    'type' => 'text',
                    'label' => __('Filters Button Text', 'directorist'),
                    'value' => __('Filters', 'directorist'),
                ],
                'listings_display_filter' => [
                    'label' => __('Open Filter Fields', 'directorist'),
                    'type'  => 'select',
                    'value' => 'sliding',
                    'options' => [
                        [
                            'value' => 'overlapping',
                            'label' => __('Overlapping', 'directorist'),
                        ],
                        [
                            'value' => 'sliding',
                            'label' => __('Sliding', 'directorist'),
                        ],
                    ],
                ],
                'listing_filters_fields' => [
                    'type' => 'checkbox',
                    'name' => 'package_list',
                    'label' => __('Select Packages', 'directorist'),
                    'value' => [
                        'search_text',
                        'search_category',
                        'search]location',
                        'search_price',
                        'search_price_range',
                        'search_rating',
                        'search_tag',
                        'search_custom_fields',
                        'radius_search'
                    ],
                    'options' => [
                        [
                            'value' => 'search_text',
                            'label' => __('Text', 'directorist'),
                        ],
                        [
                            'value' => 'search_category',
                            'label' => __('Category', 'directorist'),
                        ],
                        [
                            'value' => 'search_location',
                            'label' => __('Location', 'directorist'),
                        ],
                        [
                            'value' => 'search_price',
                            'label' => __('Price (Min - Max)', 'directorist'),
                        ],
                        [
                            'value' => 'search_price_range',
                            'label' => __('Price Range', 'directorist'),
                        ],
                        [
                            'value' => 'search_rating',
                            'label' => __('Rating', 'directorist'),
                        ],
                        [
                            'value' => 'search_tag',
                            'label' => __('Tag', 'directorist'),
                        ],
                        [
                            'value' => 'search_open_now',
                            'label' => $business_hours_label,
                        ],
                        [
                            'value' => 'search_custom_fields',
                            'label' => __('Custom Fields', 'directorist'),
                        ],
                        [
                            'value' => 'search_website',
                            'label' => __('Website', 'directorist'),
                        ],
                        [
                            'value' => 'search_email',
                            'label' => __('Email', 'directorist'),
                        ],
                        [
                            'value' => 'search_phone',
                            'label' => __('Phone', 'directorist'),
                        ],
                        [
                            'value' => 'search_fax',
                            'label' => __('Fax', 'directorist'),
                        ],
                        [
                            'value' => 'search_zip_code',
                            'label' => __('Zip/Post Code', 'directorist'),
                        ],
                        [
                            'value' => 'radius_search',
                            'label' => __('Radius Search', 'directorist'),
                        ],
                    ],
                ],
                'listing_location_fields' => [
                    'label' => __('Location Source for Search', 'directorist'),
                    'type'  => 'select',
                    'value' => 'map_api',
                    'options' => [
                        [
                            'value' => 'listing_location',
                            'label' => __('Display from Listing Location', 'directorist'),
                        ],
                        [
                            'value' => 'map_api',
                            'label' => __('Display From Map API', 'directorist'),
                        ],
                    ],
                ],
                'listing_tags_field' => [
                    'label' => __('Tags Filter Source', 'directorist'),
                    'type'  => 'select',
                    'value' => 'all_tags',
                    'options' => [
                        [
                            'value' => 'category_based_tags',
                            'label' => __('Category Based Tags', 'directorist'),
                        ],
                        [
                            'value' => 'all_tags',
                            'label' => __('All Tags', 'directorist'),
                        ],
                    ],
                ],
                'listing_default_radius_distance' => [
                    'label' => __('Default Radius Distance', 'directorist'),
                    'type'  => 'number',
                    'value' => 0,
                    'placeholder' => '10',
                ],
                'listings_filters_button' => [
                    'label' => __('Filter Buttons', 'directorist'),
                    'type'  => 'checkbox',
                    'value' => [
                            'reset_button',
                            'apply_button',
                        ],
                    'options' => [
                        [
                            'value' => 'reset_button',
                            'label' => __('Reset', 'directorist'),
                        ],
                        [
                            'value' => 'apply_button',
                            'label' => __('Apply', 'directorist'),
                        ],
                    ],
                ],
                'listings_reset_text' => [
                    'type' => 'text',
                    'label' => __('Reset Filters Button text', 'directorist'),
                    'value' => __('Reset Filters', 'directorist'),
                ],
                'listings_apply_text' => [
                    'type' => 'text',
                    'label' => __('Apply Filters Button text', 'directorist'),
                    'value' => __('Apply Filters', 'directorist'),
                ],
                'listings_search_text_placeholder' => [
                    'type' => 'text',
                    'label' => __('Search Bar Placeholder', 'directorist'),
                    'value' => __('What are you looking for?', 'directorist'),
                ],
                'listings_category_placeholder' => [
                    'type' => 'text',
                    'label' => __('Category Placeholder', 'directorist'),
                    'default' => __('Select a category', 'directorist'),
                ],
                'listings_location_placeholder' => [
                    'type' => 'text',
                    'label' => __('Location Placeholder', 'directorist'),
                    'value' => __('location', 'directorist'),
                ],
                'display_sort_by' => [
                    'type' => 'toggle',
                    'label' => __('Display "Sort By" Dropdown', 'directorist'),
                    'value' => true,
                ],
                'listings_sort_by_items' => [
                    'label' => __('"Sort By" Dropdown', 'directorist'),
                    'type'  => 'checkbox',
                    'value' => [
                        'a_z',
                        'z_a',
                        'latest',
                        'oldest',
                        'popular',
                        'price_low_high',
                        'price_high_low',
                        'random'
                        ],
                    'options' => [
                        [
                            'value' => 'a_z',
                            'label' => __('A to Z (title)', 'directorist'),
                        ],
                        [
                            'value' => 'z_a',
                            'label' => __('Z to A (title)', 'directorist'),
                        ],
                        [
                            'value' => 'latest',
                            'label' => __('Latest listings', 'directorist'),
                        ],
                        [
                            'value' => 'oldest',
                            'label' => __('Oldest listings', 'directorist'),
                        ],
                        [
                            'value' => 'popular',
                            'label' => __('Popular listings', 'directorist'),
                        ],
                        [
                            'value' => 'price_low_high',
                            'label' => __('Price (low to high)', 'directorist'),
                        ],
                        [
                            'value' => 'price_high_low',
                            'label' => __('Price (high to low)', 'directorist'),
                        ],
                        [
                            'value' => 'random',
                            'label' => __('Random listings', 'directorist'),
                        ],
                    ],
                ],
                'display_view_as' => [
                    'type' => 'toggle',
                    'label' => __('Display "View As" Dropdown', 'directorist'),
                    'value' => true,
                ],
                'view_as_text' => [
                    'type' => 'text',
                    'label' => __('"View As" Text', 'directorist'),
                    'show_if' => [
                        'where' => "self.display_view_as",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                    'value' => __('View As', 'directorist'),
                ],
                'listings_view_as_items' => [
                    'label' => __('"View As" Dropdown', 'directorist'),
                    'type'  => 'checkbox',
                    'show_if' => [
                        'where' => "self.display_view_as",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                    'value' => [
                        'listings_grid',
                        'listings_list',
                        'listings_map'
                        ],
                    'options' => [
                        [
                            'value' => 'listings_grid',
                            'label' => __('Grid', 'directorist'),
                        ],
                        [
                            'value' => 'listings_list',
                            'label' => __('List', 'directorist'),
                        ],
                        [
                            'value' => 'listings_map',
                            'label' => __('Map', 'directorist'),
                        ],
                    ],
                ],
                'default_listing_view' => [
                    'label' => __('Default View', 'directorist'),
                    'type'  => 'select',
                    'value' => 'grid',
                    'options' => [
                        [
                            'value' => 'grid',
                            'label' => __('Grid', 'directorist'),
                        ],
                        [
                            'value' => 'list',
                            'label' => __('List', 'directorist'),
                        ],
                        [
                            'value' => 'map',
                            'label' => __('Map', 'directorist'),
                        ],
                    ],
                ],
                'grid_view_as' => [
                    'label' => __('Grid View', 'directorist'),
                    'type'  => 'select',
                    'value' => 'normal_grid',
                    'options' => [
                        [
                            'value' => 'masonry_grid',
                            'label' => __('Masonry', 'directorist'),
                        ],
                        [
                            'value' => 'normal_grid',
                            'label' => __('Normal', 'directorist'),
                        ],
                    ],
                ],
                'all_listing_columns' => [
                    'label' => __('Number of Columns', 'directorist'),
                    'type'  => 'number',
                    'value' => 3,
                    'placeholder' => '3',
                ],
                'order_listing_by' => [
                    'label' => __('Listings Order By', 'directorist'),
                    'type'  => 'select',
                    'value' => 'date',
                    'options' => [
                       [
                            'value' => 'title',
                            'label' => __('Title', 'directorist'),
                       ],
                       [
                            'value' => 'date',
                            'label' => __('Date', 'directorist'),
                       ],
                       [
                            'value' => 'price',
                            'label' => __('Price', 'directorist'),
                       ],
                       [
                            'value' => 'rand',
                            'label' => __('Random', 'directorist'),
                       ],
                    ],
                ],
                'sort_listing_by' => [
                    'label' => __('Listings Sort By', 'directorist'),
                    'type'  => 'select',
                    'value' => 'desc',
                    'options' => [
                        [
                            'value' => 'asc',
                            'label' => __('Ascending', 'directorist'),
                        ],
                        [
                            'value' => 'desc',
                            'label' => __('Descending', 'directorist'),
                        ],
                    ],
                ],
                'display_preview_image' => [
                    'type' => 'toggle',
                    'label' => __('Show Preview Image', 'directorist'),
                    'description' => __('Hide/show preview image from all listing page.', 'directorist'),
                    'value' => true,
                ],
                'preview_image_quality' => [
                    'label' => __('Image Quality', 'directorist'),
                    'type'  => 'select',
                    'value' => 'large',
                    'show_if' => [
                        'where' => "self.display_preview_image",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                    'options' => [
                        [
                            'value' => 'medium',
                            'label' => __('Medium', 'directorist'),
                        ],
                        [
                            'value' => 'large',
                            'label' => __('Large', 'directorist'),
                        ],
                        [
                            'value' => 'full',
                            'label' => __('Full', 'directorist'),
                        ],
                    ],
                ],
                'way_to_show_preview' => [
                    'label' => __('Image Size', 'directorist'),
                    'type'  => 'select',
                    'value' => 'cover',
                    'show_if' => [
                        'where' => "self.display_preview_image",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                    'options' => [
                        [
                            'value' => 'full',
                            'label' => __('Original', 'directorist'),
                        ],
                        [
                            'value' => 'cover',
                            'label' => __('Fill with Container', 'directorist'),
                        ],
                        [
                            'value' => 'contain',
                            'label' => __('Fit with Container', 'directorist'),
                        ],
                    ],
                ],
                'crop_width' => [
                    'label' => __('Container Width', 'directorist'),
                    'type'  => 'number',
                    'value' => '350',
                    'min' => '1',
                    'max' => '1200',
                    'step' => '1',
                    'show_if' => [
                        'where' => "self.display_preview_image",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'crop_height' => [
                    'label' => __('Container Height', 'directorist'),
                    'type'  => 'number',
                    'value' => '260',
                    'min' => '1',
                    'max' => '1200',
                    'step' => '1',
                    'show_if' => [
                        'where' => "self.display_preview_image",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'prv_container_size_by' => [
                    'label' => __('Container Size By', 'directorist'),
                    'type'  => 'select',
                    'value' => 'px',
                    'show_if' => [
                        'where' => "self.display_preview_image",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                    'options' => [
                        [
                            'value' => 'px',
                            'label' => __('Pixel', 'directorist'),
                        ],
                        [
                            'value' => 'ratio',
                            'label' => __('Ratio', 'directorist'),
                        ],
                    ],
                ],
                'prv_background_type' => [
                    'label' => __('Background', 'directorist'),
                    'type'  => 'select',
                    'value' => 'blur',
                    'show_if' => [
                        'where' => "self.display_preview_image",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                    'options' => [
                        [
                            'value' => 'blur',
                            'label' => __('Blur', 'directorist'),
                        ],
                        [
                            'value' => 'color',
                            'label' => __('Custom Color', 'directorist'),
                        ],
                    ],
                ],
                'prv_background_color' => [
                    'type' => 'text',
                    'label' => __('Select Color', 'directorist'),
                    'show_if' => [
                        'where' => "self.prv_background_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'color'],
                        ],
                    ],
                    'value' => 'gainsboro',
                ],
                'default_preview_image' => [
                    'label'       => __('Select', 'directorist'),
                    'type'        => 'wp-media-picker',
                    'default-img' => ATBDP_PUBLIC_ASSETS . 'images/grid.jpg',
                    'value'       => '',
                     'show_if' => [
                        'where' => "self.display_preview_image",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'info_display_in_single_line' => [
                    'type' => 'toggle',
                    'label' => __('Display Each Grid Info on Single Line', 'directorist'),
                    'description' => __('Here Yes means display all the informations (i.e. title, tagline, excerpt etc.) of grid view on single line', 'directorist'),
                    'value' => false,
                ],
                'display_title' => [
                    'type' => 'toggle',
                    'label' => __('Display Title', 'directorist'),
                    'value' => true,
                ],
                'enable_tagline' => [
                    'type' => 'toggle',
                    'label' => __('Display Tagline', 'directorist'),
                    'value' => false,
                ],
                'enable_excerpt' => [
                    'type' => 'toggle',
                    'label' => __('Display Excerpt', 'directorist'),
                    'value' => false,
                ],
                'excerpt_limit' => [
                    'label' => __('Excerpt Words Limit', 'directorist'),
                    'type'  => 'number',
                    'value' => '20',
                    'min' => '5',
                    'max' => '200',
                    'step' => '1',
                    'show_if' => [
                        'where' => "self.enable_excerpt",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'display_readmore' => [
                    'type' => 'toggle',
                    'label' => __('Display Excerpt Readmore', 'directorist'),
                    'value' => false,
                    'show_if' => [
                        'where' => "self.enable_excerpt",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'readmore_text' => [
                    'type' => 'text',
                    'label' => __('Read More Text', 'directorist'),
                    'show_if' => [
                        'where' => "self.enable_excerpt",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                    'value' => __('Read More', 'directorist'),
                ],
                'display_price' => [
                    'type' => 'toggle',
                    'label' => __('Display Price', 'directorist'),
                    'value' => true,
                ],
                'display_email' => [
                    'type' => 'toggle',
                    'label' => __('Display Email', 'directorist'),
                    'value' => false,
                ],
                'display_web_link' => [
                    'type' => 'toggle',
                    'label' => __('Display Web Link', 'directorist'),
                    'value' => false,
                ],
                'display_contact_info' => [
                    'type' => 'toggle',
                    'label' => __('Display Contact Information', 'directorist'),
                    'value' => true,
                ],
                'address_location' => [
                    'label' => __('Address', 'directorist'),
                    'type'  => 'select',
                    'value' => 'contact',
                    'description' => __('Choose which address you want to show on listings page', 'directorist'),
                    'show_if' => [
                        'where' => "self.display_contact_info",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                    'options' => [
                        [
                            'value' => 'location',
                            'label' => __('Display From Location', 'directorist'),
                        ],
                        [
                            'value' => 'contact',
                            'label' => __('Display From Contact Information', 'directorist'),
                        ],
                    ],
                ],
                'display_publish_date' => [
                    'type' => 'toggle',
                    'label' => __('Display Publish Date'),
                    'value' => true,
                ],
                'publish_date_format' => [
                    'label' => __('Publish Date Format', 'directorist'),
                    'type'  => 'select',
                    'value' => 'time_ago',
                    'show_if' => [
                        'where' => "self.display_publish_date",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                    'options' => [
                        [
                            'value' => 'time_ago',
                            'label' => __('Number of Days Ago', 'directorist'),
                        ],
                        [
                            'value' => 'publish_date',
                            'label' => __('Standard Date Format', 'directorist'),
                        ],
                    ],
                ],
                'display_category' => [
                    'type' => 'toggle',
                    'label' => __('Display Category'),
                    'value' => true,
                ],
                'display_mark_as_fav' => [
                    'type' => 'toggle',
                    'label' => __('Display Mark as Favourite'),
                    'value' => true,
                ],
                'display_view_count' => [
                    'type' => 'toggle',
                    'label' => __('Display View Count'),
                    'value' => true,
                ],
                'display_author_image' => [
                    'type' => 'toggle',
                    'label' => __('Display Author Image'),
                    'value' => true,
                ],
                'paginate_all_listings' => [
                    'type' => 'toggle',
                    'label' => __('Paginate Listings'),
                    'value' => true,
                ],
                'all_listing_page_items' => [
                    'label' => __('Listings Per Page', 'directorist'),
                    'type'  => 'number',
                    'value' => '6',
                    'min' => '1',
                    'max' => '100',
                    'step' => '1',
                ],
            ]);

            $this->layouts = apply_filters('atbdp_listing_type_settings_layout', [
                'listing_settings' => [
                    'label' => __( 'Listing Settings', 'directorist' ),
                    'icon' => '<i class="fa fa-list directorist_Blue"></i>',
                    'submenu' => apply_filters('atbdp_listing_settings_submenu', [
                        'general' => [
                            'label' => __('General Settings', 'directorist'),
                            'icon' => '<i class="fa fa-sliders-h"></i>',
                            'sections' => apply_filters( 'atbdp_listing_settings_general_sections', [
                                'general_settings' => [
                                    'title'       => __('General Settings', 'directorist'),
                                    'description' => '',
                                    'fields'      => [
                                        'new_listing_status', 'edit_listing_status', 'fix_js_conflict', 'font_type', 'default_expiration', 'can_renew_listing', 'email_to_expire_day', 'email_renewal_day', 'delete_expired_listing', 'delete_expired_listings_after', 'deletion_mode', 'paginate_author_listings', 'display_author_email', 'author_cat_filter', 'atbdp_enable_cache', 'atbdp_reset_cache', 'guest_listings', 
                                    ],
                                ],
                            ] ),
                        ],
                        'listings_page' => [
                            'label' => __('Listings Page', 'directorist'),
                            'icon' => '<i class="fa fa-archive"></i>',
                            'sections' => apply_filters( 'atbdp_listing_settings_listings_page_sections', [
                                'labels' => [
                                    'title'       => __('Listings Page', 'directorist'),
                                    'description' => '',
                                    'fields'      => [
                                        'display_listings_header', 'all_listing_title', 'listing_filters_button', 'listing_filters_icon', 'listings_filter_button_text', 'listings_display_filter', 'listing_filters_fields', 'listing_location_fields', 'listing_tags_field', 'listing_default_radius_distance', 'listings_filters_button', 'listings_reset_text', 'listings_apply_text', 'listings_search_text_placeholder', 'listings_category_placeholder', 'listings_location_placeholder', 'display_sort_by', 'listings_sort_by_items', 'display_view_as', 'view_as_text', 'listings_view_as_items', 'default_listing_view', 'grid_view_as', 'all_listing_columns', 'order_listing_by', 'sort_listing_by', 'display_preview_image', 'preview_image_quality', 'way_to_show_preview', 'crop_width', 'crop_height', 'prv_container_size_by', 'prv_background_type', 'prv_background_color', 'default_preview_image', 'info_display_in_single_line', 'display_title', 'enable_tagline', 'enable_excerpt', 'excerpt_limit', 'display_readmore', 'readmore_text', 'display_price', 'display_email', 'display_web_link', 'display_contact_info', 'address_location', 'display_publish_date', 'publish_date_format', 'display_category', 'display_mark_as_fav', 'display_view_count', 'display_author_image', 'paginate_all_listings', 'all_listing_page_items' 
                                    ],
                                ],
                            ] ),
                        ],
                        'single_listing' => [
                            'label' => __('Single Listing', 'directorist'),
                            'sections' => apply_filters( 'atbdp_listing_settings_listing_page_sections', [
                                'labels' => [
                                    'title'       => __('Single Listing', 'directorist'),
                                    'description' => '',
                                    'fields'      => [],
                                ],
                            ] ),
                        ],
                        'badge' => [
                            'label' => __('Badge', 'directorist'),
                            'sections' => apply_filters( 'atbdp_listing_settings_badge_sections', [
                                'labels' => [
                                    'title'       => __('Badge', 'directorist'),
                                    'description' => '',
                                    'fields'      => [],
                                ],
                            ] ),
                        ],

                        'review' => [
                            'label' => __('Reveiw', 'directorist'),
                            'sections' => apply_filters( 'atbdp_listing_settings_review_sections', [
                                'labels' => [
                                    'title'       => __('Reveiw', 'directorist'),
                                    'description' => '',
                                    'fields'      => [],
                                ],
                            ] ),
                        ],

                        'map' => [
                            'label' => __('Map', 'directorist'),
                            'sections' => apply_filters( 'atbdp_listing_settings_map_sections', [
                                'labels' => [
                                    'title'       => __('Map', 'directorist'),
                                    'description' => '',
                                    'fields'      => [],
                                ],
                            ] ),
                        ],

                        'user_dashboard' => [
                            'label' => __('User Dashboard', 'directorist'),
                            'sections' => apply_filters( 'atbdp_listing_settings_user_dashboard_sections', [
                                'labels' => [
                                    'title'       => __('User Dashboard', 'directorist'),
                                    'description' => '',
                                    'fields'      => [],
                                ],
                            ] ),
                        ],
                    ]),
                ],

                'search_settings' => [
                    'label' => __( 'Search Settings', 'directorist' ),
                    'icon' => '<i class="fa fa-search directorist_warning"></i>',
                    'submenu' => apply_filters('atbdp_search_settings_submenu', [
                       
                    ]),
                ],

                'page_settings' => [
                    'label' => __( 'Page Settings', 'directorist' ),
                    'icon' => '<i class="fa fa-chart-line directorist_wordpress"></i>',
                    'submenu' => apply_filters('atbdp_page_settings_submenu', [
                       
                    ]),
                ],

                'seo_settings' => [
                    'label' => __( 'SEO Settings', 'directorist' ),
                    'icon' => '<i class="fa fa-bolt directorist_green"></i>',
                    'submenu' => apply_filters('atbdp_seo_settings_submenu', [
                       
                    ]),
                ],

                'currency_settings' => [
                    'label' => __( 'Currency Settings', 'directorist' ),
                    'icon' => '<i class="fa fa-money-bill directorist_danger"></i>',
                    'submenu' => apply_filters('atbdp_currency_settings_submenu', [
                       
                    ]),
                ],

            ]);

            $this->config = [
                'fields_theme' => 'butterfly',
                'submission' => [
                    'url' => admin_url('admin-ajax.php'),
                    'with' => [ 'action' => 'save_settings_data' ],
                ],
            ];
        }

        // add_menu_pages
        public function add_menu_pages()
        {
            add_submenu_page(
                'edit.php?post_type=at_biz_dir',
                'Settings Panel',
                'Settings Panel',
                'manage_options',
                'atbdp-settings',
                [ $this, 'menu_page_callback__settings_manager' ],
                5
            );
        }

        // menu_page_callback__settings_manager
        public function menu_page_callback__settings_manager()
        {   
            // Get Saved Data
            $atbdp_options = get_option('atbdp_option');
            foreach( $this->fields as $field_key => $field_opt ) {
                if ( ! isset(  $atbdp_options[ $field_key ] ) ) { continue; }

                $this->fields[ $field_key ]['value'] = $atbdp_options[ $field_key ];
            }

            $atbdp_settings_manager_data = [
                'fields'  => $this->fields,
                'layouts' => $this->layouts,
                'config'  => $this->config,
            ];

            $this->enqueue_scripts();
            wp_localize_script('atbdp_settings_manager', 'atbdp_settings_manager_data', $atbdp_settings_manager_data);
        
            /* $status = $this->update_settings_options([
                'new_listing_status' => 'publish',
                'edit_listing_status' => 'publish',
            ]);
            
            $check_new = get_directorist_option( 'new_listing_status' );
            $check_edit = get_directorist_option( 'edit_listing_status' );

            var_dump( [ '$check_new' => $check_new,  '$check_edit' => $check_edit] ); */
            
            atbdp_load_admin_template('settings-manager/settings');
        }

        // update_fields_with_old_data
        public function update_fields_with_old_data()
        {
            
        }

        // enqueue_scripts
        public function enqueue_scripts()
        {
            wp_enqueue_media();
            wp_enqueue_style('atbdp-unicons');
            wp_enqueue_style('atbdp-font-awesome');
            wp_enqueue_style('atbdp-select2-style');
            wp_enqueue_style('atbdp-select2-bootstrap-style');
            wp_enqueue_style('atbdp_admin_css');

            wp_localize_script('atbdp_settings_manager', 'ajax_data', ['ajax_url' => admin_url('admin-ajax.php')]);
            wp_enqueue_script('atbdp_settings_manager');
        }

        // register_scripts
        public function register_scripts()
        {
            wp_register_style('atbdp-unicons', '//unicons.iconscout.com/release/v3.0.3/css/line.css', false);
            wp_register_style('atbdp-select2-style', '//cdn.bootcss.com/select2/4.0.0/css/select2.css', false);
            wp_register_style('atbdp-select2-bootstrap-style', '//select2.github.io/select2-bootstrap-theme/css/select2-bootstrap.css', false);
            wp_register_style('atbdp-font-awesome', ATBDP_PUBLIC_ASSETS . 'css/font-awesome.min.css', false);

            wp_register_style( 'atbdp_admin_css', ATBDP_PUBLIC_ASSETS . 'css/admin_app.css', [], '1.0' );
            wp_register_script( 'atbdp_settings_manager', ATBDP_PUBLIC_ASSETS . 'js/settings_manager.js', ['jquery'], false, true );
        }
    }
}
