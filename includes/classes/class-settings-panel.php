<?php

use Directorist\Asset_Loader\Enqueue;

if ( ! class_exists('ATBDP_Settings_Panel') ) {
	class ATBDP_Settings_Panel
	{
		private $extension_url    = '';
		public $fields            = [];
		public $layouts           = [];
		public $config            = [];
		public $default_form      = [];
		public $old_custom_fields = [];
		public $cetagory_options  = [];

		// run
		public function run()
		{
			add_action('directorist_installed', [ $this, 'update_init_options' ] );
			add_action('directorist_updated', [ $this, 'update_init_options' ] );

            if ( ! is_admin() ) {
                return;
            }

            add_action( 'admin_menu', [$this, 'add_menu_pages'] );
			add_action( 'wp_ajax_save_settings_data', [ $this, 'handle_save_settings_data_request' ] );
			add_action( 'wp_ajax_save_settings_data', [ $this, 'handle_save_settings_data_request' ] );
            add_filter( 'atbdp_listing_type_settings_field_list', [ $this, 'register_setting_fields' ] );

            $this->extension_url = sprintf("<a target='_blank' href='%s'>%s</a>", esc_url(admin_url('edit.php?post_type=at_biz_dir&page=atbdp-extension')), __('Checkout Awesome Extensions', 'directorist'));
		}

		public function update_init_options() {
			// Set lazy_load_taxonomy_fields option
			$enable_lazy_loading = directorist_has_no_listing() ? true : false;
			update_directorist_option( 'lazy_load_taxonomy_fields', $enable_lazy_loading );
		}

        public static function in_settings_page() {
            if ( ! is_admin() ) {
                return false;
            }

            if ( ! isset( $_REQUEST['post_type'] ) && ! isset( $_REQUEST['page'] ) ) {
                return false;
            }

            if ( 'at_biz_dir' !== $_REQUEST['post_type'] && 'atbdp-settings' !== $_REQUEST['page'] ) {
                return false;
            }

            return true;
        }

        // register_setting_fields
        public function register_setting_fields( $fields = [] ) {
            $fields['script_debugging'] = [
                'type'  => 'toggle',
                'label' => 'Script debugging',
                'description' => __( 'Loads unminified .css, .js files', 'directorist' ),
            ];

            $fields['import_settings'] = [
                'type'         => 'import',
                'label'        => 'Import',
                'button-label' => 'Upload .json File',
            ];

            $fields['export_settings'] = [
                'type'             => 'export',
                'label'            => 'Export',
                'button-label'     => 'Download Export File',
                'export-file-name' => 'directory-settings',
            ];

            $fields['single_listing_slug_with_directory_type'] = [
                'type'  => 'toggle',
                'label' => __('Add Directory Type to Permalink', 'directorist'),
                'value' => directorist_is_multi_directory_enabled(),
                'show-if' => [
                    'where' => "enable_multi_directory",
                    'conditions' => [
                        ['key' => 'value', 'compare' => '=', 'value' => true],
                    ],
                ],
            ];

            $fields['restore_default_settings'] = [
                'type'         => 'restore',
                'label'        => 'Restore Default Settings',
                'button-label' => 'Restore',
                'restor-data'  => $this->get_simple_data_content( [ 'path' => 'directory/directory-settings.json' ] ),
            ];

            $fields['enable_multi_directory'] = [
                'type'  => 'toggle',
                'label' => 'Enable Multi Directory',
                'value' => false,
                'confirm-before-change' => true,
                'confirmation-modal' => [
                    'show-model-header' => false
                ],
                'data-on-change' => [
                    'action' => 'updateData',
                    'args'   => [ 'reload_after_save' => true ]
                ],
                'componets' => [
                    'link' => [
                        'label' => __( 'Start Building Directory', 'directorist' ),
                        'type'  => 'success',
                        'url'   => admin_url( 'edit.php?post_type=at_biz_dir&page=atbdp-directory-types' ),
                        'show'  => directorist_is_multi_directory_enabled(),
                    ]
                ]
            ];

            $fields['regenerate_pages'] = [
                'type'                       => 'ajax-action',
                'action'                     => 'atbdp_upgrade_old_pages',
                'label'                      => 'Upgrade/Regenerate Pages',
                'button-label'               => 'Regenerate Pages',
                'button-label-on-processing' => '<i class="fas fa-circle-notch fa-spin"></i> Processing',
                'data'                       => [],
            ];

			$users = get_users(
				array(
					'role__not_in' => 'Administrator',   // Administrator | Subscriber
					'number'       => apply_filters( 'directorist_announcement_user_query_num', 1000 ),
				)
			);
            $recipient = [];

            if ( ! empty( $users ) ) {
                foreach ( $users as $user ) {
                    $recipient[] = [
                        'value' => $user->user_email,
                        'label' => ( ! empty( $user->display_name ) ) ? $user->display_name : $user->user_nicename,
                    ];
                }
            }

            $fields['listing_import_button'] = [
                'type'            => 'button',
                'url'             => admin_url( 'edit.php?post_type=at_biz_dir&page=tools' ),
                'open-in-new-tab' => true,
                'label'           => __( 'Import', 'directorist' ),
                'button-label'    => __( 'Run Importer', 'directorist' ),
            ];

            $fields['listing_export_button'] = [
                'type'                     => 'export-data',
                'label'                    => __( 'Export', 'directorist' ),
                'button-label'             => __( 'Download Export File', 'directorist' ),
                'export-file-name'         => __( 'listings-export-data', 'directorist' ),
                'prepare-export-file-from' => 'directorist_prepare_listings_export_file',
                'nonce'                    => [
                    'key' => 'directorist_nonce',
                    'value' => wp_create_nonce( directorist_get_nonce_key() ),
                ],
            ];

            $c = '<b><span style="color:#c71585;">'; //color start
            $e = '</span></b>'; // end color
            $description = <<<SWBD
                You can use the following keywords/placeholder in any of your email bodies/templates or subjects to output dynamic value. **Usage: place the placeholder name between $c == $e and $c == $e . For Example: use {$c}==SITE_NAME=={$e} to output The Your Website Name etc. <br/><br/>
                {$c}==NAME=={$e} : It outputs The listing owner's display name on the site<br/>
                {$c}==USERNAME=={$e} : It outputs The listing owner's user name on the site<br/>
                {$c}==SITE_NAME=={$e} : It outputs your site name<br/>
                {$c}==SITE_LINK=={$e} : It outputs your site name with link<br/>
                {$c}==SITE_URL=={$e} : It outputs your site url with link<br/>
                {$c}==EXPIRATION_DATE=={$e} : It outputs Expiration date<br/>
                {$c}==CATEGORY_NAME=={$e} : It outputs the category name that is going to expire<br/>
                {$c}==LISTING_ID=={$e} : It outputs the listing's ID<br/>
                {$c}==RENEWAL_LINK=={$e} : It outputs a link to renewal page<br/>
                {$c}==LISTING_TITLE=={$e} : It outputs the listing's title<br/>
                {$c}==LISTING_LINK=={$e} : It outputs the listing's title with link<br/>
                {$c}==LISTING_URL=={$e} : It outputs the listing's url with link<br/>
                {$c}==ORDER_ID=={$e} : It outputs the order id. It should be used for order related email only<br/>
                {$c}==ORDER_RECEIPT_URL=={$e} : It outputs a link to the order receipt page. It should be used for order related email only<br/>
                {$c}==ORDER_DETAILS=={$e} : It outputs order detailsc. It should be used for order related email only<br/>
                {$c}==TODAY=={$e} : It outputs the current date<br/>
                {$c}==NOW=={$e} : It outputs the current time<br/>
                {$c}==DASHBOARD_LINK=={$e} : It outputs the user dashboard page link<br/>
                {$c}==USER_PASSWORD=={$e} : It outputs new user's temporary passoword<br/>
                {$c}==CONFIRM_EMAIL_ADDRESS_URL=={$e} : It outputs verify email link<br/>
                {$c}==SET_PASSWORD_AND_CONFIRM_EMAIL_ADDRESS_URL=={$e} : It outputs set new password and verify email link<br/><br/>
                Additionally, you can also use HTML tags in your template.
SWBD;

            $fields['email_note'] = [
                'type'        => 'note',
                'title'       => 'You can use Placeholders to output dynamic value',
                'description' => $description,
            ];

            // Marker Clustering
            $fields['marker_clustering'] = [
                'type'  => 'toggle',
                'label' => __('Marker Clustering', 'directorist'),
                'value' => true,
                'show-if' => [
                    'where' => "select_listing_map",
                    'conditions' => [
                        ['key' => 'value', 'compare' => '=', 'value' => 'google'],
                    ],
                ],
            ];


            // Map Country Restriction Field
            $fields['country_restriction'] = [
                'type'  => 'toggle',
                'label' => __('Country Restriction', 'directorist'),
                'value' => false,
                'show-if' => [
                    'where' => "select_listing_map",
                    'conditions' => [
                        ['key' => 'value', 'compare' => '=', 'value' => 'google'],
                    ],
                ],
            ];

            // Use Default Latitude/Longitude in All Listing Map View
            $fields['use_def_lat_long'] = [
                'type'  => 'toggle',
                'label' => __('Force Default Location', 'directorist'),
                'value' => false,
                'description' => __('Enable this option to force the default latitude and longitude to create a default location on all listings map view.
                Otherwise default location works only on the add listing form map.', 'directorist'),
            ];

            $countries = atbdp_country_code_to_name();
            $items = array();

            foreach ($countries as $country => $code) {
                $items[] = array(
                    'value' => $country,
                    'label' => $code,
                );
            }

            $fields['restricted_countries'] = [
                'type'    => 'checkbox',
                'label'   => __('Select Countries', 'directorist'),
                'options' => $items,
                'value'   => '',
                'show-if' => [
                    'where' => "country_restriction",
                    'conditions' => [
                        ['key' => 'value', 'compare' => '=', 'value' => true],
                    ],
                ],
            ];


            // Single Listings
            $fields['submission_confirmation'] = [
                'type'  => 'toggle',
                'label' => __('Show Submission Confirmation', 'directorist'),
                'value' => true,
            ];

            $fields['pending_confirmation_msg'] = [
                'type'  => 'textarea',
                'label' => __('Pending Confirmation Message', 'directorist'),
                'value' => __('Thank you for your submission. Your listing is being reviewed and it may take up to 24 hours to complete the review.', 'directorist'),
                'show-if' => [
                    'where' => "submission_confirmation",
                    'conditions' => [
                        ['key' => 'value', 'compare' => '=', 'value' => true],
                    ],
                ],
            ];

            $fields['publish_confirmation_msg'] = [
                'type'  => 'textarea',
                'label' => __('Publish Confirmation Message', 'directorist'),
                'value' => __('Congratulations! Your listing has been approved/published. Now it is publicly available.', 'directorist'),
                'show-if' => [
                    'where' => "submission_confirmation",
                    'conditions' => [
                        ['key' => 'value', 'compare' => '=', 'value' => true],
                    ],
                ],
            ];

            return $fields;
        }

		// get_simple_data_content
		public function get_simple_data_content( array $args = [] ) {
			$default = [ 'path' => '', 'json_decode' => true ];
			$args = array_merge( $default,  $args );

			$path = ( ! empty( $args['path'] ) ) ? $args['path'] : '';

			// $path = 'directory/directory.json'
			$file = DIRECTORIST_ASSETS_DIR . "sample-data/{$path}";
			if ( ! file_exists( $file ) ) { return ''; }

			$data = file_get_contents( $file );

			if ( $args['json_decode'] ) {
				$data = json_decode( $data, true );
			}

			return $data;
		}

		// handle_save_settings_data_request
		public function handle_save_settings_data_request()
		{
			$status = [ 'success' => false, 'status_log' => [] ];

            if ( ! directorist_verify_nonce() ) {
                $status['status_log'] = [
					'type' => 'error',
					'message' => __( 'Something is wrong! Please refresh and retry.', 'directorist' ),
				];

                wp_send_json( [ 'status' => $status ] );
            }

            if ( ! current_user_can( 'manage_options' ) ) {
                $status['status_log'] = [
					'type' => 'error',
					'message' => __( 'You are not allowed to access this resource', 'directorist' ),
				];

				wp_send_json( [ 'status' => $status ] );
            }


			$field_list = ( ! empty( $_POST['field_list'] ) ) ? Directorist\Helper::maybe_json( sanitize_text_field( wp_unslash( $_POST['field_list'] ) ) ) : [];

			// If field list is empty
			if ( empty( $field_list ) || ! is_array( $field_list ) ) {
				$status['status_log'] = [
					'type' => 'success',
					'message' => __( 'No changes made', 'directorist' ),
				];

				wp_send_json( [ 'status' => $status, 'field_list' => $field_list ] );
			}

			$options = [];
			foreach ( $field_list as $field_key ) {
				if ( ! isset( $_POST[ $field_key ] ) ) { continue; }

				$options[ $field_key ] = sanitize_text_field( wp_unslash( $_POST[ $field_key ] ) );
			}

            // Prepare Settings
            $this->prepare_settings();

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

				$atbdp_options[ $option_key ] = Directorist\Helper::maybe_json( $option_value, true );
			}

			update_option( 'atbdp_option', $atbdp_options );

            /**
            * @since 7.3.0
            */
            do_action( 'directorist_options_updated' );

			// Send Status
			$status['options'] = $options;
			$status['success'] = true;
			$status['status_log'] = [
				'type' => 'success',
				'message' => __( 'Saving Successful', 'directorist' ),
			];

			return [ 'status' => $status ];
		}

		// maybe_serialize
		public function maybe_serialize($value = '')
		{
			return maybe_serialize(Directorist\Helper::maybe_json($value));
		}

		// prepare_settings
		public function prepare_settings()
		{
			$business_hours_label = sprintf(__('Open Now %s', 'directorist'), !class_exists('BD_Business_Hour') ? '(Requires Business Hours extension)' : '');

			$bank_transfer_instruction = "
Please make your payment directly to our bank account and use your ORDER ID (#==ORDER_ID==) as a Reference. Our bank account information is given below.

Account details :

Account Name : [Enter your Account Name]
Account Number : [Enter your Account Number]
Bank Name : [Enter your Bank Name]

Please remember that your order may be canceled if you do not make your payment within next 72 hours.";

        $bank_payment_desc = __('You can make your payment directly to our bank account using this gateway. Please use your ORDER ID as a reference when making the payment. We will complete your order as soon as your deposit is cleared in our bank.', 'directorist');

		$default_size = directorist_default_preview_size();
		$default_preview_size_text = $default_size['width'].'x'.$default_size['height'].' px';

            $this->fields = apply_filters('atbdp_listing_type_settings_field_list', [

                'enable_monetization' => [
                    'label'         => __( 'Enable Monetization', 'directorist' ),
                    'type'          => 'toggle',
                    'value'         => false,
                    'description'   => __( 'Enable monetization to accept payments from users and earn through listing submissions.
                    ', 'directorist' ),

                ],

                'enable_featured_listing' => [
                    'label'         => __( 'Monetize with Featured Listings', 'directorist' ),
                    'type'          => 'toggle',
                    'value'         => false,
                    'description'   => sprintf(
                        __( 'Enable this option to charge users for featuring their listing.
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        Note: You need to add the "Listing Type" field to the add listing form for this feature to work properly. 
                        <a href="%s" target="_blank">Watch how</a>', 'directorist' ),
                        esc_url( '' ) // Replace with your URL
                    ),
                    'show-if' => [
                        'where' => "enable_monetization",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],

                'featured_listing_desc' => [
                    'type' => 'textarea',
                    'label' => __('Listing Description at Checkout', 'directorist'),
                    'show-if' => [
                        'where' => "enable_featured_listing",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                    'value' => __('You are about to feature your listing, promoting it to the top of search results and listings pages for enhanced visibility.', 'directorist'),
                ],

                'featured_listing_price' => [
                    'label'         => __('Featured Listing Fee', 'directorist'),
                    'type'          => 'number',
                    'min'           => 0,
                    'step'           => '0.01',
                    'value'         => 19.99,
                    'description'   => __('Set the amount you want to charge users for featuring their listing.', 'directorist'),
                    'show-if' => [
                        'where' => "enable_featured_listing",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],

                'featured_listing_time' => [
                    'label'         => __( 'Featured Listing Duration (in Days)', 'directorist' ),
                    'description'   => __( 'Set how many days a listing stays featured', 'directorist' ),
                    'type'          => 'number',
                    'value'         => 30,
                    'show-if' => [
                        'where' => "enable_featured_listing",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],

                'active_gateways' => [
                    'label'     => __('Payment Methods', 'directorist'),
                    'type'      => 'checkbox',
                    'value'     => ['bank_transfer'],
                    'options'   => apply_filters( 'directorist_active_gateways', [
                        [
                            'value' => 'bank_transfer',
                            'label' => __('Bank Transfer (Offline Gateway)', 'directorist'),
                        ],
                    ] ),
                    'description' => __('Check the gateway(s) you would like to use to collect payment from your users. A user will be use any of the active gateways during the checkout process ', 'directorist'),
                ],

                'default_gateway' => [
                    'label'     => __('Default Gateway', 'directorist'),
                    'type'      => 'select',
                    'value'     => 'bank_transfer',
                    'options'   => apply_filters('atbdp_default_gateways', [
                        [
                            'value' => 'bank_transfer',
                            'label' => __('Bank Transfer (Offline Gateway)', 'directorist'),
                        ]
                    ] ),
                    'description' => __('Select the default gateway you would like to show as a selected gateway on the checkout page', 'directorist'),
                ],

                'payment_currency_note'  => [
                    'type'          => 'note',
                    'title'         => __('Note About This Currency Settings:', 'directorist'),
                    'description' => __('This currency settings lets you customize how you would like to accept payment from your user/customer and how to display pricing on the order form/history.', 'directorist'),
                ],

                'payment_currency' => [
                    'type'          => 'text',
                    'label'         => __('Currency Code', 'directorist'),
                    'value'         => __('USD', 'directorist'),
                    'description' => sprintf(
                        __( 'Enter the 3-letter currency code (e.g., USD for US Dollar). For a full list of currency codes, refer to %s.', 'directorist' ),
                        "<a href='" . esc_url( 'https://www.iban.com/currency-codes' ) . "'>" . __( 'ISO 4217 Currency Codes', 'directorist' ) . "</a>"
                    ),
                ],

                'payment_thousand_separator' => [
                    'type'          => 'text',
                    'label'         => __('Thousand Separator', 'directorist'),
                    'value'         => __(',', 'directorist'),
                    'description'   => __( 'Enter the currency thousand separator. Eg. , or . etc.', 'directorist' ),
                ],

                'payment_decimal_separator' => [
                    'type'          => 'text',
                    'label'         => __('Decimal Separator', 'directorist'),
                    'value'         => __('.', 'directorist'),
                    'description' => __('Enter the currency decimal separator. Eg. "." or ",". Default is "."', 'directorist'),
                ],

                'payment_currency_position' => [
                    'label'     => __('Currency Position', 'directorist'),
                    'type'      => 'select',
                    'value'     => 'before',
                    'options'   => [
                        [
                            'value' => 'before',
                            'label' => __('$5 - Before', 'directorist'),
                        ],
                        [
                            'value' => 'after',
                            'label' => __('After - 5$', 'directorist'),
                        ]
                    ],
                    'description' => __('Select where you would like to show the currency symbol. Default is before. Eg. $5', 'directorist'),
                ],

                // gateway settings
                'offline_payment_note'    => [
                    'type'                => 'note',
                    'title'               => __('Note About Bank Transfer Gateway:', 'directorist'),
                    'description'         => __('You should remember that this payment gateway needs some manual action to complete an order. After getting notification of order using this offline payment gateway, you should check your bank if the money is deposited to your account. Then you should change the order status manually from the "Order History" submenu.', 'directorist'),
                ],

                'bank_transfer_title' => [
                    'type'            => 'text',
                    'label'           => __('Gateway Title', 'directorist'),
                    'value'           => __('Bank Transfer', 'directorist'),
                    'description'     => __('Enter the title of this gateway that should be displayed to the user on the front end.', 'directorist'),
                ],

                'bank_transfer_description' => [
                    'type'          => 'textarea',
                    'label'         => __('Gateway Description', 'directorist'),
                    'value'         => $bank_payment_desc,
                    'description'   => __('Enter some description for your user to transfer funds to your account.', 'directorist'),
                ],

                'bank_transfer_instruction' => [
                    'type'          => 'textarea',
                    'label'         => __('Bank Information', 'directorist'),
                    'value'         => $bank_transfer_instruction,
                    'description'   => __('Enter your bank information below so that use can make payment directly to your bank account.', 'directorist'),
                ],

                //extension setting
                'extension_promotion'    => [
                    'type'          => 'note',
                    'title'         => __('Need more Features?', 'directorist'),
                    'description'   => sprintf(__('You can add new features and expand the functionality of the plugin even more by using extensions. %s', 'directorist'), $this->extension_url),
                ],

                'announcement_to' => [
                    'label'     => __('To', 'directorist'),
                    'type'      => 'select',
                    'value'     => 'all_user',
                    'options'   => [
                        [
                            'value' => 'all_user',
                            'label' => __( 'All User', 'directorist' )
                        ],
                        [
                            'value' => 'selected_user',
                            'label' => __( 'Selected User', 'directorist' )
                        ]
                    ]
                ],

                'announcement_subject' => [
                    'label' => __('Subject', 'directorist'),
                    'type'  => 'text',
                    'value' => false
                ],

                'announcement_send_to_email' => [
                    'label'   => __('Send a copy to email', 'directorist'),
                    'type'    => 'toggle',
                    'value' => true,
                ],

                'button_type' => [
                    'label' => __('Button Type', 'directorist'),
                    'type'  => 'select',
                    'value' => 'select_option',
                    'show-default-option' => true,
                    'options'   => [
                        [
                            'value' => 'solid_primary',
                            'label' => __('Solid Primary', 'directorist'),
                        ],
                        [
                            'value' => 'solid_secondary',
                            'label' => __('Solid Secondary', 'directorist'),
                        ],
                        [
                            'value' => 'solid_danger',
                            'label' => __('Solid Danger', 'directorist'),
                        ],
                        [
                            'value' => 'solid_success',
                            'label' => __('Solid Success', 'directorist'),
                        ],
                        [
                            'value' => 'solid_lighter',
                            'label' => __('Solid Lighter', 'directorist'),
                        ],
                        [
                            'value' => 'primary_outline',
                            'label' => __('Primary Outline', 'directorist'),
                        ],
                        [
                            'value' => 'primary_outline_light',
                            'label' => __('Primary Outline Light', 'directorist'),
                        ],
                        [
                            'value' => 'danger_outline',
                            'label' => __('Danger Outline', 'directorist'),
                        ],
                    ]
                ],

                // solid primary color
                'primary_example' => [
                    'label'       => __('Button Example', 'directorist'),
                    'type'        => 'wp-media-picker',
                    'default-img' => 'https://directorist.com/wp-content/uploads/2020/02/solid-primary.png',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'solid_primary'],
                        ],
                    ],
                ],
                'primary_color' => [
                    'label' => __('Text Color', 'directorist'),
                    'type' => 'color',
                    'value' => '#ffffff',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'solid_primary'],
                        ],
                    ],
                ],
                'primary_hover_color' => [
                    'label' => __('Text Hover Color', 'directorist'),
                    'type' => 'color',
                    'value' => '#ffffff',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'solid_primary'],
                        ],
                    ],
                ],
                'back_primary_color' => [
                    'label' => __('Background Color', 'directorist'),
                    'type' => 'color',
                    'value' => '#444752',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'solid_primary'],
                        ],
                    ],
                ],
                'back_primary_hover_color' => [
                    'label' => __('Background Hover Color', 'directorist'),
                    'type' => 'color',
                    'value' => '#222222',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'solid_primary'],
                        ],
                    ],
                ],
                'border_primary_color' => [
                    'label' => __('Border Color', 'directorist'),
                    'type' => 'color',
                    'value' => '#444752',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'solid_primary'],
                        ],
                    ],
                ],
                'border_primary_hover_color' => [
                    'label' => __('Border Hover Color', 'directorist'),
                    'type' => 'color',
                    'value' => '#222222',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'solid_primary'],
                        ],
                    ],
                ],
                //solid secondary color
                'secondary_example' => [
                    'label'       => __('Button Example', 'directorist'),
                    'type'        => 'wp-media-picker',
                    'default-img' => 'https://directorist.com/wp-content/uploads/2020/02/solid-secondary.png',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'solid_secondary'],
                        ],
                    ],
                ],
                'secondary_color' => [
                    'label' => __('Text Color', 'directorist'),
                    'type' => 'color',
                    'value' => '#fff',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'solid_secondary'],
                        ],
                    ],
                ],
                'secondary_hover_color' => [
                    'label' => __('Text Hover Color', 'directorist'),
                    'type' => 'color',
                    'value' => '#fff',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'solid_secondary'],
                        ],
                    ],
                ],
                'back_secondary_color' => [
                    'label' => __('Background Color', 'directorist'),
                    'type' => 'color',
                    'value' => '#122069',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'solid_secondary'],
                        ],
                    ],
                ],
                'back_secondary_hover_color' => [
                    'label' => __('Background Hover Color', 'directorist'),
                    'type' => 'color',
                    'value' => '#131469',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'solid_secondary'],
                        ],
                    ],
                ],
                'secondary_border_color' => [
                    'label' => __('Border Color', 'directorist'),
                    'type' => 'color',
                    'value' => '#131469',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'solid_secondary'],
                        ],
                    ],
                ],
                'secondary_border_hover_color' => [
                    'label' => __('Border Hover Color', 'directorist'),
                    'type' => 'color',
                    'value' => '#131469',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'solid_secondary'],
                        ],
                    ],
                ],
                // solid danger color
                'danger_example' => [
                    'label'       => __('Button Example', 'directorist'),
                    'type'        => 'wp-media-picker',
                    'default-img' => 'https://directorist.com/wp-content/uploads/2020/02/solid-danger.png',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'solid_danger'],
                        ],
                    ],
                ],
                'danger_color' => [
                    'label' => __('Text Color', 'directorist'),
                    'type' => 'color',
                    'value' => '#fff',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'solid_danger'],
                        ],
                    ],
                ],
                'danger_hover_color' => [
                    'label' => __('Text Hover Color', 'directorist'),
                    'type' => 'color',
                    'value' => '#fff',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'solid_danger'],
                        ],
                    ],
                ],
                'back_danger_color' => [
                    'label' => __('Background Color', 'directorist'),
                    'type' => 'color',
                    'value' => '#e23636',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'solid_danger'],
                        ],
                    ],
                ],
                'back_danger_hover_color' => [
                    'label' => __('Background Hover Color', 'directorist'),
                    'type' => 'color',
                    'value' => '#c5001e',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'solid_danger'],
                        ],
                    ],
                ],
                'danger_border_color' => [
                    'label' => __('Border Color', 'directorist'),
                    'type' => 'color',
                    'value' => '#e23636',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'solid_danger'],
                        ],
                    ],
                ],
                'danger_border_hover_color' => [
                    'label' => __('Border Hover Color', 'directorist'),
                    'type' => 'color',
                    'value' => '#c5001e',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'solid_danger'],
                        ],
                    ],
                ],
                // solid success
                'success_example' => [
                    'label'       => __('Button Example', 'directorist'),
                    'type'        => 'wp-media-picker',
                    'default-img' => 'https://directorist.com/wp-content/uploads/2020/02/solid-success.png',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'solid_success'],
                        ],
                    ],
                ],
                'success_color' => [
                    'label' => __('Text Color', 'directorist'),
                    'type' => 'color',
                    'value' => '#fff',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'solid_success'],
                        ],
                    ],
                ],
                'success_hover_color' => [
                    'label' => __('Text Color', 'directorist'),
                    'type' => 'color',
                    'value' => '#fff',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'solid_success'],
                        ],
                    ],
                ],
                'back_success_color' => [
                    'label' => __('Background Color', 'directorist'),
                    'type' => 'color',
                    'value' => '#32cc6f',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'solid_success'],
                        ],
                    ],
                ],
                'back_success_hover_color' => [
                    'label' => __('Background Hover Color', 'directorist'),
                    'type' => 'color',
                    'value' => '#2ba251',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'solid_success'],
                        ],
                    ],
                ],
                'border_success_color' => [
                    'label' => __('Border Color', 'directorist'),
                    'type' => 'color',
                    'value' => '#32cc6f',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'solid_success'],
                        ],
                    ],
                ],
                'border_success_hover_color' => [
                    'label' => __('Border Hover Color', 'directorist'),
                    'type' => 'color',
                    'value' => '#2ba251',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'solid_success'],
                        ],
                    ],
                ],
                // solid lighter
                'lighter_example' => [
                    'label'       => __('Button Example', 'directorist'),
                    'type'        => 'wp-media-picker',
                    'default-img' => 'https://directorist.com/wp-content/uploads/2021/05/roomrent.png',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'solid_lighter'],
                        ],
                    ],
                ],
                'lighter_color' => [
                    'label' => __('Text Color', 'directorist'),
                    'type' => 'color',
                    'value' => '#1A1B29',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'solid_lighter'],
                        ],
                    ],
                ],
                'lighter_hover_color' => [
                    'label' => __('Text Hover Color', 'directorist'),
                    'type' => 'color',
                    'value' => '#1A1B29',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'solid_lighter'],
                        ],
                    ],
                ],
                'back_lighter_color' => [
                    'label' => __('Background Color', 'directorist'),
                    'type' => 'color',
                    'value' => '#F6F7F9',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'solid_lighter'],
                        ],
                    ],
                ],
                'back_lighter_hover_color' => [
                    'label' => __('Background Hover Color', 'directorist'),
                    'type' => 'color',
                    'value' => '#F6F7F9',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'solid_lighter'],
                        ],
                    ],
                ],
                'border_lighter_color' => [
                    'label' => __('Border Color', 'directorist'),
                    'type' => 'color',
                    'value' => '#F6F7F9',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'solid_lighter'],
                        ],
                    ],
                ],
                'border_lighter_hover_color' => [
                    'label' => __('Border Hover Color', 'directorist'),
                    'type' => 'color',
                    'value' => '#F6F7F9',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'solid_lighter'],
                        ],
                    ],
                ],
                // primary outline
                'priout_example' => [
                    'label'       => __('Button Example Outline', 'directorist'),
                    'type'        => 'wp-media-picker',
                    'default-img' => 'https://directorist.com/wp-content/uploads/2020/02/outline-primary.png',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'primary_outline'],
                        ],
                    ],
                ],
                'priout_color' => [
                    'label' => __('Text Color Outline', 'directorist'),
                    'type' => 'color',
                    'value' => '#444752',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'primary_outline'],
                        ],
                    ],
                ],
                'priout_hover_color' => [
                    'label' => __('Text Hover Color Outline', 'directorist'),
                    'type' => 'color',
                    'value' => '#444752',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'primary_outline'],
                        ],
                    ],
                ],
                'back_priout_color' => [
                    'label' => __('Background Color Outline', 'directorist'),
                    'type' => 'color',
                    'value' => '#fff',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'primary_outline'],
                        ],
                    ],
                ],
                'back_priout_hover_color' => [
                    'label' => __('Background Hover Color Outline', 'directorist'),
                    'type' => 'color',
                    'value' => '#fff',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'primary_outline'],
                        ],
                    ],
                ],
                'border_priout_color' => [
                    'label' => __('Border Color Outline', 'directorist'),
                    'type' => 'color',
                    'value' => '#444752',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'primary_outline'],
                        ],
                    ],
                ],
                'border_priout_hover_color' => [
                    'label' => __('Border Hover Color Outline', 'directorist'),
                    'type' => 'color',
                    'value' => '#9299b8',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'primary_outline'],
                        ],
                    ],
                ],
                 // primary outline light
                 'prioutlight_example' => [
                    'label'       => __('Button Example Outline Light', 'directorist'),
                    'type'        => 'wp-media-picker',
                    'default-img' => 'https://directorist.com/wp-content/uploads/2020/02/outline-primary-light.png',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'primary_outline_light'],
                        ],
                    ],
                ],
                'prioutlight_color' => [
                    'label' => __('Text Color Outline Light', 'directorist'),
                    'type' => 'color',
                    'value' => '#444752',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'primary_outline_light'],
                        ],
                    ],
                ],
                'prioutlight_hover_color' => [
                    'label' => __('Text Hover Color Outline Light', 'directorist'),
                    'type' => 'color',
                    'value' => '#ffffff',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'primary_outline_light'],
                        ],
                    ],
                ],
                'back_prioutlight_color' => [
                    'label' => __('Background Color Outline Light', 'directorist'),
                    'type' => 'color',
                    'value' => '#ffffff',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'primary_outline_light'],
                        ],
                    ],
                ],
                'back_prioutlight_hover_color' => [
                    'label' => __('Background Hover Color Outline Light', 'directorist'),
                    'type' => 'color',
                    'value' => '#444752',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'primary_outline_light'],
                        ],
                    ],
                ],
                'border_prioutlight_color' => [
                    'label' => __('Border Color Outline Light', 'directorist'),
                    'type' => 'color',
                    'value' => '#e3e6ef',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'primary_outline_light'],
                        ],
                    ],
                ],
                'border_prioutlight_hover_color' => [
                    'label' => __('Border Hover Color Outline Light', 'directorist'),
                    'type' => 'color',
                    'value' => '#444752',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'primary_outline_light'],
                        ],
                    ],
                ],
                // Danger outline
                'danout_example' => [
                    'label'       => __('Button Example', 'directorist'),
                    'type'        => 'wp-media-picker',
                    'default-img' => 'https://directorist.com/wp-content/uploads/2020/02/outline-danger.png',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'danger_outline'],
                        ],
                    ],
                ],
                'danout_color' => [
                    'label' => __('Text Color', 'directorist'),
                    'type' => 'color',
                    'value' => '#e23636',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'danger_outline'],
                        ],
                    ],
                ],
                'danout_hover_color' => [
                    'label' => __('Text Hover Color', 'directorist'),
                    'type' => 'color',
                    'value' => '#fff',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'danger_outline'],
                        ],
                    ],
                ],
                'back_danout_color' => [
                    'label' => __('Background Color', 'directorist'),
                    'type' => 'color',
                    'value' => '#fff',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'danger_outline'],
                        ],
                    ],
                ],
                'back_danout_hover_color' => [
                    'label' => __('Background Hover Color', 'directorist'),
                    'type' => 'color',
                    'value' => '#e23636',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'danger_outline'],
                        ],
                    ],
                ],
                'border_danout_color' => [
                    'label' => __('Border Color', 'directorist'),
                    'type' => 'color',
                    'value' => '#e23636',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'danger_outline'],
                        ],
                    ],
                ],
                'border_danout_hover_color' => [
                    'label' => __('Border Hover Color', 'directorist'),
                    'type' => 'color',
                    'value' => '#e23636',
                    'show-if' => [
                        'where' => "button_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'danger_outline'],
                        ],
                    ],
                ],

                'featured_back_color' => [
                    'type' => 'color',
                    'label' => __('Background Color', 'directorist'),
                    'value' => '#fa8b0c',
                ],

                'popular_back_color' => [
                    'type' => 'color',
                    'label' => __('Background Color', 'directorist'),
                    'value' => '#f51957',
                ],

                'new_back_color' => [
                    'type' => 'color',
                    'label' => __('Background Color', 'directorist'),
                    'value' => '#2C99FF',
                ],

                'marker_shape_color' => [
                    'type' => 'color',
                    'label' => __('Marker Shape Color', 'directorist'),
                    'value' => '#444752',
                ],

                'marker_icon_color' => [
                    'type' => 'color',
                    'label' => __('Marker Icon Color', 'directorist'),
                    'value' => '#444752',
                ],

                'primary_dark_back_color' => [
                    'type' => 'color',
                    'label' => __('Background Color', 'directorist'),
                    'value' => '#444752',
                ],

                'primary_dark_border_color' => [
                    'type' => 'color',
                    'label' => __('Border Color', 'directorist'),
                    'value' => '#444752',
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
                'delete_expired_listing_permanently' => [
                    'label'       => __('Permanently Delete Expired Listings', 'directorist'),
                    'type'        => 'toggle',
                    'description' => __( 'Automatically delete trashed listings permanently after the defined duration', 'directorist' ),
                    'value'       => true,
                ],
                'delete_expired_listings_after' => [
                    'label' => __( 'Permanently Delete After (days) of Expiration', 'directorist' ),
                    'type'  => 'number',
                    'value' => 15,
                    'placeholder' => '15',
                    'rules' => [
                        'required' => true,
                    ],
                ],
                'paginate_author_listings' => [
                    'label' => __('Paginate Author Listings', 'directorist'),
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
                    'show-if' => [
                        'where' => "atbdp_enable_cache",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => false],
                        ],
                    ],
                    'value' => false,
                ],
                'guest_listings' => [
                    'label' => __('Guest Listing Submission', 'directorist'),
                    'type'  => 'toggle',
                    'value' => false,
                ],
                'guest_email_label' => [
                    'type'      => 'text',
                    'label'     => __( 'Guest Email Label', 'directorist' ),
                    'value'     => __( 'Your Email', 'directorist' ),
                    'show-if'   => [
                        'where' => "guest_listings",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'guest_email_placeholder' => [
                    'type'      => 'text',
                    'label'     => __( 'Guest Email Placeholder', 'directorist' ),
                    'value'     => __( 'example@email.com', 'directorist' ),
                    'show-if'   => [
                        'where' => "guest_listings",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],

                // listings page
                'all_listing_layout' => [
                    'label' => __( 'All Listings Layout', 'directorist' ),
                    'type'  => 'select',
                    'value' => 'left_sidebar',
                    'options' => [
                        [
                            'value' => 'left_sidebar',
                            'label' => __('Left Sidebar Filter', 'directorist'),
                        ],
                        [
                            'value' => 'right_sidebar',
                            'label' => __('Right Sidebar Filter', 'directorist'),
                        ],
                        [
                            'value' => 'no_sidebar',
                            'label' => __('Popup Filter', 'directorist'),
                        ],
                    ],
                ],

                'listing_hide_top_search_bar' => [
                    'type' => 'toggle',
                    'label' => __('Hide Top Search Bar', 'directorist'),
                    'value' => false,
                    'show-if' => [
                        'where' => "all_listing_layout",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '!=', 'value' => 'no_sidebar'],
                        ],
                    ],
                ],
                'listings_sidebar_filter_text' => [
                    'type' => 'text',
                    'label' => __('Filters Text', 'directorist'),
                    'value' => __('Filters', 'directorist'),
                    'show-if' => [
                        'where' => "all_listing_layout",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '!=', 'value' => 'no_sidebar'],
                        ],
                    ],
                ],
                'listings_filter_button_text' => [
                    'type' => 'text',
                    'label' => __('Filters Button Text', 'directorist'),
                    'value' => __('Filters', 'directorist'),
                    'show-if' => [
                        'where' => "all_listing_layout",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'no_sidebar'],
                        ],
                    ],
                ],
                'display_listings_count' => [
                    'type' => 'toggle',
                    'label' => __('Display Listings Count', 'directorist'),
                    'value' => true,
                    'show-if' => [
                        'where' => "display_listings_header",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'all_listing_title' => [
                    'type' => 'text',
                    'label'   => __('Listings Count Text', 'directorist'),
                    'value'   => __('Items Found', 'directorist'),
                    'show-if' => [
                        'where' => "display_listings_header",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'listings_reset_text' => [
                    'type' => 'text',
                    'label' => __('Reset Button text', 'directorist'),
                    'value' => __('Reset Filters', 'directorist'),
                    'show-if' => [
                        'where' => "all_listing_layout",
                        'conditions' => [
                            [ 'key' => 'value', 'compare' => '=', 'value' => 'no_sidebar' ],
                        ],
                    ],
                ],
                'listings_sidebar_reset_text' => [
                    'type' => 'text',
                    'label' => __('Reset text', 'directorist'),
                    'value' => __('Clear All', 'directorist'),
                    'show-if' => [
                        'where' => "all_listing_layout",
                        'conditions' => [
                            [ 'key' => 'value', 'compare' => '!=', 'value' => 'no_sidebar' ],
                        ],
                    ],
                ],
                'listings_apply_text' => [
                    'type' => 'text',
                    'label' => __('Apply Button text', 'directorist'),
                    'value' => __('Apply Filters', 'directorist'),
                    'show-if' => [
                        'where' => "all_listing_layout",
                        'conditions' => [
                            [ 'key' => 'value', 'compare' => '=', 'value' => 'no_sidebar' ],
                        ],
                    ],
                ],


                'display_sort_by' => [
                    'type' => 'toggle',
                    'label' => __('Enable Sorting Options', 'directorist'),
                    'value' => true,
                ],
                'sort_by_text'    => [
                    'type'          => 'text',
                    'label'         => __('"Sort By" Label', 'directorist'),
                    'value'         => __('Sort By', 'directorist'),
                    'show-if' => [
                        'where' => "display_sort_by",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'listings_sort_by_items' => [
                    'label' => __('Sort Options', 'directorist'),
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
                'listings_view_as_items' => [
                    'label' => __('View Type', 'directorist'),
                    'type'  => 'checkbox',
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
                'all_listing_columns' => [
                    'label' => __('Listings Columns', 'directorist'),
                    'type'  => 'number',
                    'value' => 2,
                    'placeholder' => '3',
                ],
                'preview_image_quality' => [
                    'label' => __('Image Quality', 'directorist'),
                    'type'  => 'select',
                    'value' => 'directorist_preview',
                    'options' => [
                        [
                            'value' => 'directorist_preview',
                            'label' => __( 'Default', 'directorist' ),
                        ],
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
					'description' => sprintf( __( 'Default: %s.<br/>If changed, regenerate thumbnails via <a href="%s" target="_blank">this</a> plugin for proper functionality.', 'directorist' ), $default_preview_size_text, 'https://wordpress.org/plugins/regenerate-thumbnails/' ),
                ],
                'way_to_show_preview' => [
                    'label' => __('Image Size', 'directorist'),
                    'type'  => 'select',
                    'value' => 'cover',

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
                    'label' => __('Width', 'directorist'),
                    'type'  => 'number',
                    'value' => '350',
                    'min' => '1',
                    'max' => '1200',
                    'step' => '1',
                    'group' => 'container',
                    'group_label' => 'Container',
                ],
                'crop_height' => [
                    'label' => __('Height', 'directorist'),
                    'type'  => 'number',
                    'value' => '260',
                    'min' => '1',
                    'max' => '1200',
                    'step' => '1',
                    'group' => 'container',
                    'group_label' => 'Container',
                ],
                'prv_container_size_by' => [
                    'label' => __('Size By', 'directorist'),
                    'type'  => 'select',
                    'value' => 'px',
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
                    'group' => 'container',
                    'group_label' => 'Container',
                ],
                'prv_background_type' => [
                    'label' => __('Background', 'directorist'),
                    'type'  => 'select',
                    'value' => 'blur',

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
                    'show-if' => [
                        'where' => "prv_background_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'color'],
                        ],
                    ],
                    'value' => 'gainsboro',
                ],
                'all_listing_page_items' => [
                    'label' => __('Listings Per Page', 'directorist'),
                    'type'  => 'number',
                    'value' => '6',
                    'min' => '1',
                    'max' => '100',
                    'step' => '1',
                ],
                'display_listings_header' => [
                    'label' => __('Enable Header', 'directorist'),
                    'type'  => 'toggle',
                    'value' => true,
                ],
                'listing_filters_button' => [
                    'type' => 'toggle',
                    'label' => __('Display Filters Button', 'directorist'),
                    'value' => true,
                    'show-if' => [
                        'where' => "all_listing_layout",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'no_sidebar'],
                        ],
                    ],
                ],
                // single listing settings
                'disable_single_listing' => [
                    'type' => 'toggle',
                    'label' => __('Disable Single Listing View'),
                    'value' => false,
                ],
                'restrict_single_listing_for_logged_in_user' => [
                    'type' => 'toggle',
                    'label' => __('Show Single Listings to Logged-In Users Only', 'directorist'),
                    'value' => false,
                ],
                'single_listing_template' => [
                    'label' => __('Template', 'directorist'),
                    'type'  => 'select',
                    'value' => 'directorist_template',
                    'show-if' => [
                        'where' => "disable_single_listing",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => false],
                        ],
                    ],
                    'options' => [
                        [
                            'value' => 'theme_template_page',
                            'label' => __('Theme Template (Page)', 'directorist'),
                        ],
                        [
                            'value' => 'current_theme_template',
                            'label' => __('Theme Template (Post)', 'directorist'),
                        ],
                        [
                            'value' => 'directorist_template',
                            'label' => __('Directorist Template', 'directorist'),
                        ],
                    ],
                ],
                'atbdp_listing_slug' => [
                    'type' => 'text',
                    'label' => __('Listing Slug', 'directorist'),
                    'show-if' => [
                        'where' => "disable_single_listing",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => false],
                        ],
                    ],
                    'value' => 'directory',
                ],
                'dsiplay_slider_single_page' => [
                    'type' => 'toggle',
                    'label' => __('Show Slider Image', 'directorist'),
                    'value' => true,
                    'description' => __('Hide/show slider image from single listing page.', 'directorist'),
                    'show-if' => [
                        'where' => "disable_single_listing",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => false],
                        ],
                    ],
                ],
                'single_slider_image_size' => [
                    'label' => __('Image Size', 'directorist'),
                    'type'  => 'select',
                    'value' => 'cover',
                    'show-if' => [
                        'where' => "dsiplay_slider_single_page",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                    'options' => [
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
                'single_slider_background_type' => [
                    'label' => __('Slider Background Type', 'directorist'),
                    'type'  => 'select',
                    'value' => 'custom-color',
                    'show-if' => [
                        'where' => "single_slider_image_size",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'contain'],
                        ],
                    ],
                    'options' => [
                        [
                            'value' => 'blur',
                            'label' => __('Blur', 'directorist'),
                        ],
                        [
                            'value' => 'custom-color',
                            'label' => __('Custom Color', 'directorist'),
                        ],
                    ],
                ],
                'single_slider_background_color' => [
                    'type' => 'color',
                    'label' => __('Background Color', 'directorist'),
                    'show-if' => [
                        'where' => "single_slider_background_type",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'custom-color'],
                        ],
                    ],
                    'value' => '#ffffff',
                ],

                'gallery_crop_width' => [
                    'label' => __('Image Width', 'directorist'),
                    'type'  => 'number',
                    'value' => '740',
                    'min' => '1',
                    'max' => '1200',
                    'step' => '1',
                    'show-if' => [
                        'where' => "dsiplay_slider_single_page",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'gallery_crop_height' => [
                    'label' => __('Image Height', 'directorist'),
                    'type'  => 'number',
                    'value' => '580',
                    'min' => '1',
                    'max' => '1200',
                    'step' => '1',
                    'show-if' => [
                        'where' => "dsiplay_slider_single_page",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                // badge settings
                'new_badge_text'    => [
                    'type'          => 'text',
                    'label'         => __('Badge Text', 'directorist'),
                    'description'   => __('Text displayed on the badge when a listing is newly created.', 'directorist'),
                    'value'         => __('New', 'directorist'),
                ],
                'new_listing_day' => [
                    'label'         => __('New Badge Display Duration', 'directorist'),
                    'description'   => __('Enter the number of days the "New" badge will appear on a new listing.', 'directorist'),
                    'type'          => 'number',
                    'value'         => '3',
                    'min'           => '1',
                    'max'           => '100',
                    'step'          => '1',
                ],
                'feature_badge_text' => [
                    'type'          => 'text',
                    'label'         => __('Badge Text', 'directorist'),
                    'description'   => __('Text displayed on the badge when a listing is marked as featured.', 'directorist'),
                    'value'         => __('Featured', 'directorist'),
                ],
                'popular_badge_text' => [
                    'type'          => 'text',
                    'label'         => __('Badge Text', 'directorist'),
                    'description'   => __('Text displayed on the badge when an item is marked as popular.', 'directorist'),
                    'value'         => __('Popular', 'directorist'),
                ],
                'listing_popular_by' => [
                    'label'     => __('Determine Popularity By', 'directorist'),
                    'description' => __('Select the criteria used to determine popularity.', 'directorist'),
                    'type'      => 'select',
                    'value'     => 'view_count',
                    'options'   => [
                        [
                            'value' => 'view_count',
                            'label' => __('View Count', 'directorist'),
                        ],
                        [
                            'value' => 'average_rating',
                            'label' => __('Average Rating', 'directorist'),
                        ],
                    ],
                ],
                'views_for_popular' => [
                    'type'          => 'text',
                    'label'         => __('View Count Threshold', 'directorist'),
                    'description'   => __('Minimum number of views to mark an item as popular.', 'directorist'),
                    'value'         => 5,
                ],
                'count_loggedin_user' => [
                    'type'          => 'toggle',
                    'label'         => __('Include Logged-In User Views', 'directorist'),
                    'description'   => __('Count views from logged-in users toward popularity.', 'directorist'),
                    'value'         => false,
                ],
                'average_review_for_popular' => [
                    'label'         => __('Minimum Average Rating', 'directorist'),
                    'description'   => __('Minimum average rating (equal or greater than) to mark an item as popular.', 'directorist'),
                    'type'          => 'number',
                    'value'         => '4',
                    'min'           => '.5',
                    'max'           => '4.5',
                    'step'          => '.5',
                ],

                // select map settings
                'select_listing_map' => [
                    'label' => __('Select Map', 'directorist'),
                    'type'  => 'select',
                    'value' => 'openstreet',
                    'options' => [
                        [
                            'value' => 'google',
                            'label' => __('Google Map', 'directorist'),
                        ],
                        [
                            'value' => 'openstreet',
                            'label' => __('OpenStreetMap', 'directorist'),
                        ],
                    ],
                ],
                'map_api_key' => [
                    'type' => 'text',
                    'label' => __('Google Map API key', 'directorist'),
                    'description' => sprintf(__('Please replace it by your own API. It\'s required to use Google Map. You can find detailed information %s.', 'directorist'), '<a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank"> <div class="atbdp_shortcodes" style="color: red;">here</div> </a>'),
                    'value' => '',
                    'show-if' => [
                        'where' => "select_listing_map",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'google'],
                        ],
                    ],
                ],
                'default_latitude'     => [
                    'type'           => 'text',
                    'label'          => __('Default Latitude', 'directorist'),
                    'description'    => sprintf(__('You can find it %s', 'directorist'), '<a href="https://www.maps.ie/coordinates.html" target="_blank" class="directorist-find-latlan">here</a>'),
                    'value'          => '40.7127753',
                ],
                'default_longitude'    => [
                    'type'          => 'text',
                    'label'         => __('Default Longitude', 'directorist'),
                    'description'   => sprintf(__('You can find it %s', 'directorist'), '<a href="https://www.maps.ie/coordinates.html" target="_blank" class="directorist-find-latlan">here</a>'),
                    'value'         => '-74.0059728',
                ],
                'map_zoom_level'       => [
                    'label'         => __('Zoom Level for Single Listing', 'directorist'),
                    'description'   => __('Here 0 means 100% zoom-out. 22 means 100% zoom-in. Minimum Zoom Allowed = 1. Max Zoom Allowed = 22.', 'directorist'),
                    'type'          => 'number',
                    'value'         => '16',
                    'min'           => '1',
                    'max'           => '22',
                    'step'          => '1',
                ],
                'map_view_zoom_level' => [
                    'label'         => __('Zoom Level for Map View', 'directorist'),
                    'description'   => __('Here 0 means 100% zoom-out. 18 means 100% zoom-in. Minimum Zoom Allowed = 1. Max Zoom Allowed = 22.', 'directorist'),
                    'type'          => 'number',
                    'value'         => '1',
                    'min'           => '1',
                    'max'           => '18',
                    'step'          => '1',
                ],
                'listings_map_height' => [
                    'label'         => __('Map Height', 'directorist'),
                    'description'   => __('In pixel.', 'directorist'),
                    'type'          => 'number',
                    'value'         => '350',
                    'min'           => '5',
                    'max'           => '1200',
                    'step'          => '5',
                ],
                'display_map_info' => [
                    'type' => 'toggle',
                    'label' => __('Display Map Info Window', 'directorist'),
                    'value' => true,
                ],
                'display_image_map' => [
                    'type' => 'toggle',
                    'label' => __('Display Preview Image', 'directorist'),
                    'value' => true,
                    'show-if' => [
                        'where' => "display_map_info",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'display_favorite_badge_map' => [
                    'type' => 'toggle',
                    'label' => __('Display Favorite Badge', 'directorist'),
                    'value' => true,
                    'show-if' => [
                        'where' => "display_map_info",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'display_user_avatar_map' => [
                    'type' => 'toggle',
                    'label' => __('Display User Avatar', 'directorist'),
                    'value' => true,
                    'show-if' => [
                        'where' => "display_map_info",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'display_title_map' => [
                    'type' => 'toggle',
                    'label' => __('Display Title', 'directorist'),
                    'value' => true,
                    'show-if' => [
                        'where' => "display_map_info",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'display_review_map' => [
                    'type' => 'toggle',
                    'label' => __('Display Review', 'directorist'),
                    'value' => true,
                    'show-if' => [
                        'where' => "display_map_info",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'display_price_map' => [
                    'type' => 'toggle',
                    'label' => __('Display Price', 'directorist'),
                    'value' => true,
                    'show-if' => [
                        'where' => "display_map_info",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'display_address_map' => [
                    'type' => 'toggle',
                    'label' => __('Display Address', 'directorist'),
                    'value' => true,
                    'show-if' => [
                        'where' => "display_map_info",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'display_direction_map' => [
                    'type' => 'toggle',
                    'label' => __('Display Get Direction', 'directorist'),
                    'value' => true,
                    'show-if' => [
                        'where' => "display_map_info",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'display_phone_map' => [
                    'type' => 'toggle',
                    'label' => __('Display Phone', 'directorist'),
                    'value' => true,
                    'show-if' => [
                        'where' => "display_map_info",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                // user dashboard settings
                'my_listing_tab' => [
                    'type' => 'toggle',
                    'label' => __('Display My Listing Tab', 'directorist'),
                    'value' => true,
                ],
                'my_listing_tab_text'    => [
                    'type'          => 'text',
                    'label'         => __('"My Listing" Tab Label', 'directorist'),
                    'value'         => __('My Listing', 'directorist'),
                    'show-if' => [
                        'where' => "my_listing_tab",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'user_listings_pagination' => [
                    'type'  => 'toggle',
                    'label' => __('Listings Pagination', 'directorist'),
                    'value' => true,
                    'show-if' => [
                        'where' => "my_listing_tab",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'user_listings_per_page' => [
                    'label'         => __('Listings Per Page', 'directorist'),
                    'type'          => 'number',
                    'value'         => '9',
                    'min'           => '1',
                    'max'           => '30',
                    'step'          => '1',
                    'show-if' => [
                        'where' => "my_listing_tab",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'my_profile_tab' => [
                    'type'  => 'toggle',
                    'label' => __('Display My Profile Tab', 'directorist'),
                    'value' => true,
                ],
                'my_profile_tab_text'    => [
                    'type'          => 'text',
                    'label'         => __('"My Profile" Tab Label', 'directorist'),
                    'value'         => __('My Profile', 'directorist'),
                    'show-if' => [
                        'where' => "my_profile_tab",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'fav_listings_tab' => [
                    'type'  => 'toggle',
                    'label' => __('Display Favourite Listings Tab', 'directorist'),
                    'value' => true,
                ],
                'fav_listings_tab_text'    => [
                    'type'          => 'text',
                    'label'         => __('"Favourite Listings" Tab Label', 'directorist'),
                    'value'         => __('Favorite Listings', 'directorist'),
                    'show-if' => [
                        'where' => "fav_listings_tab",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'announcement_tab' => [
                    'type'  => 'toggle',
                    'label' => __('Display Announcements Tab', 'directorist'),
                    'value' => true,
                ],
                'announcement_tab_text'    => [
                    'type'          => 'text',
                    'label'         => __('"Announcement" Tab Label', 'directorist'),
                    'value'         => __('Announcements', 'directorist'),
                    'show-if' => [
                        'where' => "announcement_tab",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'submit_listing_button' => [
                    'type'  => 'toggle',
                    'label' => __('Display Submit Listing Button', 'directorist'),
                    'value' => true,
                ],
                'become_author_button' => [
                    'type'  => 'toggle',
                    'label' => __('Display "Become An Author" button', 'directorist'),
                    'value' => true,
                ],
                'become_author_button_text'    => [
                    'type'          => 'text',
                    'label'         => __('"Become An Author" button Label', 'directorist'),
                    'value'         => __('Become An Author', 'directorist'),
                    'show-if' => [
                        'where' => "become_author_button",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                // all authors settings
                'all_authors_columns' => [
                    'label'         => __('Number of Columns', 'directorist'),
                    'type'          => 'number',
                    'value'         => '3',
                    'min'           => '1',
                    'max'           => '6',
                    'step'          => '1',
                ],
                'all_authors_sorting' => [
                    'type'  => 'toggle',
                    'label' => __('Display Alphabet Sorting', 'directorist'),
                    'value' => true,
                ],
                'all_authors_image' => [
                    'type'  => 'toggle',
                    'label' => __('Display Image', 'directorist'),
                    'value' => true,
                ],
                'all_authors_name' => [
                    'type'  => 'toggle',
                    'label' => __('Display Name', 'directorist'),
                    'value' => true,
                ],
                'all_authors_select_role' => [
                    'label' => __('Select Role', 'directorist'),
                    'type'  => 'select',
                    'value' => 'all',
                    'options' => $this->get_user_roles(),
                ],
                'all_authors_contact' => [
                    'label' => esc_html__( 'Contact Info', 'directorist' ),
                    'type'  => 'checkbox',
                    'value' => [
                            'phone',
                            'address',
                            'website',
                        ],
                    'description' => esc_html__( 'Email will show only for logged in user.', 'directorist' ),
                    'options' => [
                        [
                            'value' => 'phone',
                            'label' => esc_html__( 'Phone', 'directorist' ),
                        ],
                        [
                            'value' => 'email',
                            'label' => esc_html__( 'Email', 'directorist' ),
                        ],
                        [
                            'value' => 'address',
                            'label' => esc_html__( 'Address', 'directorist' ),
                        ],
                        [
                            'value' => 'website',
                            'label' => esc_html__( 'Website', 'directorist' ),
                        ],
                    ],
                ],
                'all_authors_description' => [
                    'type'  => 'toggle',
                    'label' => __('Display Description', 'directorist'),
                    'value' => true,
                ],
                'all_authors_description_limit' => [
                    'label'         => __('Description Word Limit', 'directorist'),
                    'type'          => 'number',
                    'value'         => '13',
                    'min'           => '1',
                    'max'           => '50',
                    'step'          => '1',
                    'show-if' => [
                        'where' => "all_authors_description",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'all_authors_social_info' => [
                    'type'  => 'toggle',
                    'label' => __('Display Social Info', 'directorist'),
                    'value' => true,
                ],
                'all_authors_button' => [
                    'type'  => 'toggle',
                    'label' => __('Display All Listings Button', 'directorist'),
                    'value' => true,
                ],
                'all_authors_button_text'  => [
                    'type'          => 'text',
                    'label'         => __('All Listings Button text', 'directorist'),
                    'value'         => __('View All Listings', 'directorist'),
                    'show-if' => [
                        'where' => "all_authors_button",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'all_authors_pagination' => [
                    'type'  => 'toggle',
                    'label' => __('Paginate All Authors ', 'directorist'),
                    'value' => true,
                ],
                'all_authors_per_page' => [
                    'label'         => __('Authors Per Page', 'directorist'),
                    'type'          => 'number',
                    'value'         => '9',
                    'min'           => '1',
                    'max'           => '50',
                    'step'          => '1',
                ],
                // search form settings
                'search_title'    => [
                    'type'          => 'text',
                    'label'         => __('Search Bar Title', 'directorist'),
                    'value' => __('Search here', 'directorist'),
                ],
                'search_subtitle'    => [
                    'type'          => 'text',
                    'label'         => __('Search Bar Sub-title', 'directorist'),
                    'value'         => __( 'Find the best match of your interest', 'directorist' ),
                ],

                'search_more_filter' => [
                    'type'  => 'toggle',
                    'label' => __('Display More Filters', 'directorist'),
                    'value' => true,
                ],
                'search_filters' => [
                    'type' => 'checkbox',
                    'label' => __('Filter Actions', 'directorist'),
                    'show-if' => [
                        'where' => "search_more_filter",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                    'value' => [
                        'search_reset_filters',
                        'search_apply_filters',
                    ],
                    'options' => [
                        [
                            'value' => 'search_reset_filters',
                            'label' => __('Reset', 'directorist'),
                        ],
                        [
                            'value' => 'search_apply_filters',
                            'label' => __('Apply', 'directorist'),
                        ],
                    ],
                ],
                'search_listing_text'    => [
                    'type'          => 'text',
                    'label'         => __('Search Button Text', 'directorist'),
                    'value'         => __('Search Listing', 'directorist'),
                ],
                'search_more_filters'    => [
                    'type'          => 'text',
                    'label'         => __('More Filters Button Text', 'directorist'),
                    'value'         => __('More Filters', 'directorist'),
                    'show-if' => [
                        'where' => "search_more_filter",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'search_reset_text'    => [
                    'type'          => 'text',
                    'label'         => __('Reset Button Text', 'directorist'),
                    'value'         => __('Reset Filters', 'directorist'),
                    'show-if' => [
                        'where' => "search_more_filter",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'search_apply_filter'    => [
                    'type'          => 'text',
                    'label'         => __('Apply Button Text', 'directorist'),
                    'value'         => __('Apply Filters', 'directorist'),
                    'show-if' => [
                        'where' => "search_more_filter",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'show_popular_category' => [
                    'type'  => 'toggle',
                    'label' => __('Display Popular Categories', 'directorist'),
                    'value' => false,
                ],

                'popular_cat_title'    => [
                    'type'          => 'text',
                    'label'         => __('Section Title', 'directorist'),
                    'value'         => __('Browse by popular categories', 'directorist'),
                    'show-if' => [
                        'where' => "show_popular_category",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'popular_cat_num' => [
                    'label'         => __('Number of Categories to Display', 'directorist'),
                    'type'          => 'number',
                    'value'         => '10',
                    'min'           => '1',
                    'max'           => '30',
                    'step'          => '1',
                    'show-if' => [
                        'where' => "show_popular_category",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'search_home_bg' => [
                    'label'       => __('Search Section Background', 'directorist'),
                    'type'        => 'wp-media-picker',
                    'default-img' => '',
                    'value'       => '',
                ],
                // search result settings
                'search_result_layout' => [
                    'label' => __( 'All Listings Layout', 'directorist' ),
                    'type'  => 'select',
                    'value' => 'left_sidebar',
                    'options' => [
                        [
                            'value' => 'left_sidebar',
                            'label' => __('Left Sidebar Filter', 'directorist'),
                        ],
                        [
                            'value' => 'right_sidebar',
                            'label' => __('Right Sidebar Filter', 'directorist'),
                        ],
                        [
                            'value' => 'no_sidebar',
                            'label' => __('Popup Filter', 'directorist'),
                        ],
                    ],
                ],
                'search_result_hide_top_search_bar' => [
                    'type' => 'toggle',
                    'label' => __('Hide Top Search Bar', 'directorist'),
                    'value' => false,
                    'show-if' => [
                        'where' => "search_result_layout",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '!=', 'value' => 'no_sidebar'],
                        ],
                    ],
                ],
                'search_result_sidebar_filter_text' => [
                    'type' => 'text',
                    'label' => __('Filters Text', 'directorist'),
                    'value' => __('Filters', 'directorist'),
                    'show-if' => [
                        'where' => "search_result_layout",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '!=', 'value' => 'no_sidebar'],
                        ],
                    ],
                ],
                'search_result_filter_button_text'    => [
                    'type'          => 'text',
                    'label'         => __('Filters Button Text', 'directorist'),
                    'value'         => __('Filters', 'directorist'),
                    'show-if' => [
                        'where' => "search_result_layout",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'no_sidebar'],
                        ],
                    ],
                ],
                'display_search_result_listings_count' => [
                    'type' => 'toggle',
                    'label' => __('Display Listings Count', 'directorist'),
                    'value' => true,
                    'show-if' => [
                        'where' => "search_header",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'search_result_listing_title' => [
                    'type' => 'text',
                    'label'   => __('Listings Count Text', 'directorist'),
                    'value'   => __('Items Found', 'directorist'),
                    'show-if' => [
                        'where' => "search_header",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'sresult_reset_text'    => [
                    'type'          => 'text',
                    'label'         => __('Reset Button Text', 'directorist'),
                    'value'         => __('Reset Filters', 'directorist'),
                    'show-if' => [
                        'where' => "search_result_layout",
                        'conditions' => [
                            [ 'key' => 'value', 'compare' => '=', 'value' => 'no_sidebar' ],
                        ],
                    ],
                ],
                'sresult_sidebar_reset_text'    => [
                    'type'          => 'text',
                    'label'         => __('Reset text', 'directorist'),
                    'value'         => __('Clear All', 'directorist'),
                    'show-if' => [
                        'where' => "search_result_layout",
                        'conditions' => [
                            [ 'key' => 'value', 'compare' => '!=', 'value' => 'no_sidebar' ],
                        ],
                    ],
                ],
                'sresult_apply_text'    => [
                    'type'          => 'text',
                    'label'         => __('Apply Filters Button Text', 'directorist'),
                    'value'         => __('Apply Filters', 'directorist'),
                    'show-if' => [
                        'where' => "search_result_layout",
                        'conditions' => [
                            [ 'key' => 'value', 'compare' => '=', 'value' => 'no_sidebar' ],
                        ],
                    ],
                ],
                'search_view_as_items' => [
                    'type' => 'checkbox',
                    'label' => __('View Type', 'directorist'),
                    'description' => '',
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
                'search_sort_by' => [
                    'type'  => 'toggle',
                    'label' => __('Enable Sorting Options', 'directorist'),
                    'value' => true,
                ],
                'search_sortby_text'    => [
                    'type'          => 'text',
                    'label'         => __('"Sort By" Label', 'directorist'),
                    'value'         => __('Sort By', 'directorist'),
                ],
                'search_sort_by_items' => [
                    'type' => 'checkbox',
                    'label' => __('Sort Options', 'directorist'),
                    'description' => '',
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
                'search_listing_columns' => [
                    'label'         => __('Number of Columns', 'directorist'),
                    'type'          => 'number',
                    'value'         => '3',
                    'min'           => '1',
                    'max'           => '5',
                    'step'          => '1',
                ],
                'search_posts_num' => [
                    'label'         => __('Search Results Per Page', 'directorist'),
                    'type'          => 'number',
                    'value'         => '6',
                    'min'           => '1',
                    'max'           => '100',
                    'step'          => '1',
                ],
                'search_header' => [
                    'type'  => 'toggle',
                    'label' => __('Display Header', 'directorist'),
                    'value' => true,
                ],
                'search_result_filters_button_display' => [
                    'type'  => 'toggle',
                    'label' => __('Display Filters Button', 'directorist'),
                    'show-if' => [
                        'where' => "search_result_layout",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'no_sidebar'],
                        ],
                    ],
                    'value' => true,
                ],
                // upgrade/ regenerate pages
                'shortcode-updated' => [
                    'type'  => 'toggle',
                    'label' => __('Upgrade/Regenerate Pages', 'directorist'),
                    'value' => true,
                ],
                // pages, links, views settings
                'add_listing_page' => [
                    'label' => __('Add Listing Page', 'directorist'),
                    'type'  => 'select',
                    'description' => sprintf(__('Following shortcode must be in the selected page %s', 'directorist'), '<div class="atbdp_shortcodes" style="color: #ff4500;">[directorist_add_listing]</div>'),
                    'value' => atbdp_get_option('add_listing_page', 'atbdp_general'),
                    'showDefaultOption' => true,
                    'options' => $this->get_pages_vl_arrays(),
                ],
                'all_listing_page' => [
                    'label' => __('All Listings Page', 'directorist'),
                    'type'  => 'select',
                    'description' => sprintf(__('Following shortcode must be in the selected page %s', 'directorist'), '<div class="atbdp_shortcodes" style="color: #ff4500;">[directorist_all_listing]</div>'),
                    'value' => atbdp_get_option('all_listing_page', 'atbdp_general'),
                    'showDefaultOption' => true,
                    'options' => $this->get_pages_vl_arrays(),
                ],
                'user_dashboard' => [
                    'label' => __('Dashboard Page', 'directorist'),
                    'type'  => 'select',
                    'description' => sprintf(__('Following shortcode must be in the selected page %s', 'directorist'), '<div class="atbdp_shortcodes" style="color: #ff4500;">[directorist_user_dashboard]</div>'),
                    'value' => atbdp_get_option('user_dashboard', 'atbdp_general'),
                    'showDefaultOption' => true,
                    'options' => $this->get_pages_vl_arrays(),
                ],
                'signin_signup_page' => [
                    'label'             => __( 'Sign In & Signup Page', 'directorist' ),
                    'type'              => 'select',
                    'description'       => sprintf(__('Following shortcode must be in the selected page %s', 'directorist'), '<div class="atbdp_shortcodes" style="color: #ff4500;">[directorist_signin_signup]</div>'),
                    'value'             => atbdp_get_option( 'signin_signup_page', 'atbdp_general' ),
                    'showDefaultOption' => true,
                    'options'           => $this->get_pages_vl_arrays(),
                ],
                'author_profile_page' => [
                    'label' => __('User Profile Page', 'directorist'),
                    'type'  => 'select',
                    'description' => sprintf(__('Following shortcode must be in the selected page %s', 'directorist'), '<div class="atbdp_shortcodes" style="color: #ff4500;">[directorist_author_profile]</div>'),
                    'value' => atbdp_get_option('author_profile', 'atbdp_general'),
                    'showDefaultOption' => true,
                    'options' => $this->get_pages_vl_arrays(),
                ],
                'all_categories_page' => [
                    'label' => __('All Categories Page', 'directorist'),
                    'type'  => 'select',
                    'description' => sprintf(__('Following shortcode must be in the selected page %s', 'directorist'), '<div class="atbdp_shortcodes" style="color: #ff4500;">[directorist_all_categories]</div>'),
                    'value' => atbdp_get_option('all_categories', 'atbdp_general'),
                    'showDefaultOption' => true,
                    'options' => $this->get_pages_vl_arrays(),
                ],
                'single_category_page' => [
                    'label' => __('Single Category Page', 'directorist'),
                    'type'  => 'select',
                    'description' => sprintf(__('Following shortcode must be in the selected page %s', 'directorist'), '<div class="atbdp_shortcodes" style="color: #ff4500;">[directorist_category]</div>'),
                    'value' => atbdp_get_option('single_category_page', 'atbdp_general'),
                    'showDefaultOption' => true,
                    'options' => $this->get_pages_vl_arrays(),
                ],
                'all_locations_page' => [
                    'label' => __('All Locations Page', 'directorist'),
                    'type'  => 'select',
                    'description' => sprintf(__('Following shortcode must be in the selected page %s', 'directorist'), '<div class="atbdp_shortcodes" style="color: #ff4500;">[directorist_all_locations]</div>'),
                    'value' => atbdp_get_option('all_locations', 'atbdp_general'),
                    'showDefaultOption' => true,
                    'options' => $this->get_pages_vl_arrays(),
                ],
                'single_location_page' => [
                    'label' => __('Single Location Page', 'directorist'),
                    'type'  => 'select',
                    'description' => sprintf(__('Following shortcode must be in the selected page %s', 'directorist'), '<div class="atbdp_shortcodes" style="color: #ff4500;">[directorist_location]</div>'),
                    'value' => atbdp_get_option('single_location_page', 'atbdp_general'),
                    'showDefaultOption' => true,
                    'options' => $this->get_pages_vl_arrays(),
                ],
                'single_tag_page' => [
                    'label' => __('Single Tag Page', 'directorist'),
                    'type'  => 'select',
                    'description' => sprintf(__('Following shortcode must be in the selected page %s', 'directorist'), '<div class="atbdp_shortcodes" style="color: #ff4500;">[directorist_tag]</div>'),
                    'value' => atbdp_get_option('single_tag_page', 'atbdp_general'),
                    'showDefaultOption' => true,
                    'options' => $this->get_pages_vl_arrays(),
                ],
                'custom_registration' => [
                    'label' => __('Registration Page', 'directorist'),
                    'type'  => 'select',
                    'description' => sprintf(__('Following shortcode must be in the selected page %s', 'directorist'), '<div class="atbdp_shortcodes" style="color: #ff4500;">[directorist_custom_registration]</div>'),
                    'value' => atbdp_get_option('custom_registration', 'atbdp_general'),
                    'showDefaultOption' => true,
                    'options' => $this->get_pages_vl_arrays(),
                ],
                'user_login' => [
                    'label' => __('Login Page', 'directorist'),
                    'type'  => 'select',
                    'description' => sprintf(__('Following shortcode must be in the selected page %s', 'directorist'), '<div class="atbdp_shortcodes" style="color: #ff4500;">[directorist_user_login]</div>'),
                    'value' => atbdp_get_option('user_login', 'atbdp_general'),
                    'showDefaultOption' => true,
                    'options' => $this->get_pages_vl_arrays(),
                ],
                'search_listing' => [
                    'label' => __('Listing Search Page', 'directorist'),
                    'type'  => 'select',
                    'description' => sprintf(__('Following shortcode must be in the selected page %s', 'directorist'), '<div class="atbdp_shortcodes" style="color: #ff4500;">[directorist_search_listing]</div>'),
                    'value' => atbdp_get_option('search_listing', 'atbdp_general'),
                    'showDefaultOption' => true,
                    'options' => $this->get_pages_vl_arrays(),
                ],
                'search_result_page' => [
                    'label' => __('Listing Search Result Page', 'directorist'),
                    'type'  => 'select',
                    'description' => sprintf(__('Following shortcode must be in the selected page %s', 'directorist'), '<div class="atbdp_shortcodes" style="color: #ff4500;">[directorist_search_result]</div>'),
                    'value' => atbdp_get_option('search_result_page', 'atbdp_general'),
                    'showDefaultOption' => true,
                    'options' => $this->get_pages_vl_arrays(),
                ],
                'checkout_page' => [
                    'label' => __('Checkout Page', 'directorist'),
                    'type'  => 'select',
                    'description' => sprintf(__('Following shortcode must be in the selected page %s', 'directorist'), '<div class="atbdp_shortcodes" style="color: #ff4500;">[directorist_checkout]</div>'),
                    'value' => '',
                    'showDefaultOption' => true,
                    'options' => $this->get_pages_vl_arrays(),
                ],
                'payment_receipt_page' => [
                    'label' => __('Payment/Order Receipt Page', 'directorist'),
                    'type'  => 'select',
                    'description' => sprintf(__('Following shortcode must be in the selected page %s', 'directorist'), '<div class="atbdp_shortcodes" style="color: #ff4500;">[directorist_payment_receipt]</div>'),
                    'value' => '',
                    'showDefaultOption' => true,
                    'options' => $this->get_pages_vl_arrays(),
                ],
                'transaction_failure_page' => [
                    'label' => __('Transaction Failure Page', 'directorist'),
                    'type'  => 'select',
                    'description' => sprintf(__('Following shortcode must be in the selected page %s', 'directorist'), '<div class="atbdp_shortcodes" style="color: #ff4500;">[directorist_transaction_failure]</div>'),
                    'value' => '',
                    'showDefaultOption' => true,
                    'options' => $this->get_pages_vl_arrays(),
                ],
                'privacy_policy' => [
                    'label' => __('Privacy Policy Page', 'directorist'),
                    'type'  => 'select',
                    'value' => '',
                    'showDefaultOption' => true,
                    'options' => $this->get_pages_vl_arrays(),
                ],
                'terms_conditions' => [
                    'label' => __('Terms & Conditions Page', 'directorist'),
                    'type'  => 'select',
                    'value' => '',
                    'showDefaultOption' => true,
                    'options' => $this->get_pages_vl_arrays(),
                ],
                // seo settings
                'atbdp_enable_seo' => [
                    'type'  => 'toggle',
                    'label' => __('Enable SEO', 'directorist'),
                    'value' => true,
                ],
                'add_listing_page_meta_title'    => [
                    'type'          => 'text',
                    'label'         => __('Add Listing Page Meta Title', 'directorist'),
                    'description'   => __('Default the title of the page set as frontpage.', 'directorist'),
                    'value'         => '',
                ],
                'add_listing_page_meta_desc'    => [
                    'type'          => 'text',
                    'label'         => __('Add Listing Page Meta Description', 'directorist'),
                    'value'         => '',
                ],
                'all_listing_meta_title'    => [
                    'type'          => 'text',
                    'label'         => __('All Listing Page Meta Title', 'directorist'),
                    'description'   => __('Default the title of the page set as frontpage.', 'directorist'),
                    'value'         => '',
                ],
                'all_listing_meta_desc'    => [
                    'type'          => 'text',
                    'label'         => __('All Listing Page Meta Description', 'directorist'),
                    'value'         => '',
                ],
                'dashboard_meta_title'    => [
                    'type'          => 'text',
                    'label'         => __('User Dashboard Page Meta Title', 'directorist'),
                    'description' => __('Default the title of the page set as frontpage.', 'directorist'),
                    'value'         => '',
                ],
                'dashboard_meta_desc'    => [
                    'type'          => 'text',
                    'label'         => __('Dashboard Page Meta Description', 'directorist'),
                    'value'         => '',
                ],
                'author_profile_meta_title' => [
                    'type'          => 'text',
                    'label'         => __('Author Page Meta Title', 'directorist'),
                    'description'   => __('Default the title of the page set as frontpage.', 'directorist'),
                    'value'         => '',
                ],
                'author_page_meta_desc'    => [
                    'type'          => 'text',
                    'label'         => __('Author Page Meta Description', 'directorist'),
                    'value'         => '',
                ],
                'category_meta_title'    => [
                    'type'          => 'text',
                    'label'         => __('Category Page Meta Title', 'directorist'),
                    'description' => __('Default the title of the page set as frontpage.', 'directorist'),
                    'value'         => '',
                ],
                'category_meta_desc'    => [
                    'type'          => 'text',
                    'label'         => __('Category Page Meta Description', 'directorist'),
                    'value'         => '',
                ],
                'single_category_meta_title'    => [
                    'type'          => 'text',
                    'label'         => __('Single Category Page Meta Title', 'directorist'),
                    'description'   => __('Default the title of the category.', 'directorist'),
                    'value'         => '',
                ],
                'single_category_meta_desc'    => [
                    'type'          => 'text',
                    'label'         => __('Single Category Page Meta Description', 'directorist'),
                    'description'   => __('Leave it blank to set category\'s description as meta description of this page', 'directorist'),
                    'value'         => '',
                ],
                'all_locations_meta_title'    => [
                    'type'          => 'text',
                    'label'         => __('All Locations Page Meta Title', 'directorist'),
                    'description'   => __('Default the title of the page set as frontpage.', 'directorist'),
                    'value'         => '',
                ],
                'all_locations_meta_desc'    => [
                    'type'          => 'text',
                    'label'         => __('All Locations Page Meta Description', 'directorist'),
                    'value'         => '',
                ],
                'single_locations_meta_title'    => [
                    'type'          => 'text',
                    'label'         => __('Single Location Page Meta Title', 'directorist'),
                    'description'   => __('Default the title of the location.', 'directorist'),
                    'value'         => '',
                ],
                'single_locations_meta_desc'    => [
                    'type'          => 'text',
                    'label'         => __('Single Locations Page Meta Description', 'directorist'),
                    'description'   => __('Leave it blank to set location\'s description as meta description of this page', 'directorist'),
                    'value'         => '',
                ],
                'registration_meta_title'    => [
                    'type'          => 'text',
                    'label'         => __('Registration Page Meta Title', 'directorist'),
                    'description'   => __('Default the title of the page set as frontpage.', 'directorist'),
                    'value'         => '',
                ],
                'registration_meta_desc'    => [
                    'type'          => 'text',
                    'label'         => __('Registration Page Meta Description', 'directorist'),
                    'value'         => '',
                ],
                'login_meta_title'    => [
                    'type'          => 'text',
                    'label'         => __('Login Page Meta Title', 'directorist'),
                    'description'   => __('Default the title of the page set as frontpage.', 'directorist'),
                    'value'         => '',
                ],
                'login_meta_desc'    => [
                    'type'          => 'text',
                    'label'         => __('Login Page Meta Description', 'directorist'),
                    'value'         => '',
                ],
                'homepage_meta_title'    => [
                    'type'          => 'text',
                    'label'         => __('Search Home Page Meta Title', 'directorist'),
                    'description' => __('Default the title of the page set as frontpage.', 'directorist'),
                    'value'         => '',
                ],
                'homepage_meta_desc'    => [
                    'type'          => 'text',
                    'label'         => __('Search Home Page Meta Description', 'directorist'),
                    'value'         => '',
                ],
                'meta_title_for_search_result' => [
                    'label' => __('Search Result Page Meta Title', 'directorist'),
                    'type'  => 'select',
                    'value' => 'searched_value',
                    'options' => [
                        [
                            'value' => 'searched_value',
                            'label' => __('From User Search', 'directorist'),
                        ],
                        [
                            'value' => 'custom',
                            'label' => __('Custom', 'directorist'),
                        ],
                    ],
                ],
                'search_result_meta_title'    => [
                    'type'          => 'text',
                    'label'         => __('Custom Meta Title', 'directorist'),
                    'description'   => __('Default the title of the page set as frontpage.', 'directorist'),
                    'value'         => '',
                    'show-if' => [
                        'where' => "meta_title_for_search_result",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'custom'],
                        ],
                    ],
                ],
                'search_result_meta_desc'    => [
                    'type'          => 'text',
                    'label'         => __('Search Result Page Meta Description', 'directorist'),
                    'value'         => '',
                ],
                //currency settings
                'g_currency_note'    => [
                    'type'          => 'note',
                    'title'         => __('Note:', 'directorist'),
                    'description' => __('Customize how prices are displayed on your site. To accept payments in a different currency, visit Monetization → General Settings', 'directorist'),
                ],
                'g_currency' => [
                    'type'        => 'text',
                    'label'       => __('Currency Code', 'directorist'),
                    'description' => sprintf(
                        __( 'Enter the 3-letter currency code (e.g., USD for US Dollar). For a full list of currency codes, refer to %s.', 'directorist' ),
                        "<a href='" . esc_url( 'https://www.iban.com/currency-codes' ) . "'>" . __( 'ISO 4217 Currency Codes', 'directorist' ) . "</a>"
                    ),
                    'value'       => 'USD',
                ],
                'g_currency_position' => [
                    'label'        => __('Currency Position', 'directorist'),
                    'type'        => 'select',
                    'value'       => 'before',
                    'description' => __( "Select where you'd like the currency symbol to appear. The default is before the amount (e.g., $5)", 'directorist' ),
                    'options' => [
                        [
                            'value' => 'before',
                            'label' => __('$5 - Before', 'directorist'),
                        ],
                        [
                            'value' => 'after',
                            'label' => __('After - 5$', 'directorist'),
                        ],
                    ],
                ],
                // categories settings
                'display_categories_as' => [
                    'label'        => __('Default View', 'directorist'),
                    'type'        => 'select',
                    'value'       => 'grid',
                    'options' => [
                        [
                            'value' => 'grid',
                            'label' => __('Grid', 'directorist'),
                        ],
                        [
                            'value' => 'list',
                            'label' => __('List', 'directorist'),
                        ],
                    ],
                ],
                'categories_column_number' => [
                    'label' => __('Number of  Columns', 'directorist'),
                    'description' => __('Set how many columns to display on categories page.', 'directorist'),
                    'type'        => 'select',
                    'value'       => '4',
                    'options' => [
                        [
                            'value' => 1,
                            'label' => 1,
                        ],
                        [
                            'value' => 2,
                            'label' => 2,
                        ],
                        [
                            'value' => 3,
                            'label' => 3,
                        ],
                        [
                            'value' => 4,
                            'label' => 4,
                        ],
                        [
                            'value' => 5,
                            'label' => 5,
                        ],
                        [
                            'value' => 6,
                            'label' => 6,
                        ],
                    ],
                ],
                'categories_depth_number' => [
                    'label' => __('Sub-category Depth', 'directorist'),
                    'description' => __('Set how many sub-categories to display.', 'directorist'),
                    'type'  => 'number',
                    'value' => '4',
                    'min' => '1',
                    'max' => '15',
                    'step' => '2',
                    'show-if' => [
                        'where' => "display_categories_as",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'list'],
                        ],
                    ],
                ],
                'order_category_by' => [
                    'label'        => __('Order By', 'directorist'),
                    'type'        => 'select',
                    'value'       => 'id',
                    'options' => [
                        [
                            'value' => 'id',
                            'label' => __('ID', 'directorist'),
                        ],
                        [
                            'value' => 'count',
                            'label' => __('Count', 'directorist'),
                        ],
                        [
                            'value' => 'name',
                            'label' => __('Name', 'directorist'),
                        ],
                        [
                            'value' => 'slug',
                            'label' => __('Slug', 'directorist'),
                        ],
                    ],
                ],
                'sort_category_by' => [
                    'label'       => __('Sort By', 'directorist'),
                    'type'        => 'select',
                    'value'       => 'asc',
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
                'display_listing_count' => [
                    'label'         => __('Display Listing Count', 'directorist'),
                    'type'          => 'toggle',
                    'value'         => true,
                ],
                'hide_empty_categories' => [
                    'label'         => __('Hide Empty Categories', 'directorist'),
                    'type'          => 'toggle',
                    'value'         => false,
                ],
                // locations settings
                'display_locations_as' => [
                    'label'        => __('Default View', 'directorist'),
                    'type'        => 'select',
                    'value'       => 'grid',
                    'options' => [
                        [
                            'value' => 'grid',
                            'label' => __('Grid', 'directorist'),
                        ],
                        [
                            'value' => 'list',
                            'label' => __('List', 'directorist'),
                        ],
                    ],
                ],
                'locations_column_number' => [
                    'label' => __('Number of  Columns', 'directorist'),
                    'description' => __('Set how many columns to display on locations page.', 'directorist'),
                    'type'        => 'select',
                    'value'       => '4',
                    'options' => [
                        [
                            'value' => 1,
                            'label' => 1,
                        ],
                        [
                            'value' => 2,
                            'label' => 2,
                        ],
                        [
                            'value' => 3,
                            'label' => 3,
                        ],
                        [
                            'value' => 4,
                            'label' => 4,
                        ],
                        [
                            'value' => 5,
                            'label' => 5,
                        ],
                        [
                            'value' => 6,
                            'label' => 6,
                        ],
                    ],
                ],
                'locations_depth_number' => [
                    'label' => __('Sub-location Depth', 'directorist'),
                    'description' => __('Set how many sub-locations to display.', 'directorist'),
                    'type'  => 'number',
                    'value' => '4',
                    'min' => '1',
                    'max' => '15',
                    'step' => '2',
                    'show-if' => [
                        'where' => "display_locations_as",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => 'list'],
                        ],
                    ],
                ],
                'order_location_by' => [
                    'label'        => __('Order By', 'directorist'),
                    'type'        => 'select',
                    'value'       => 'id',
                    'options' => [
                        [
                            'value' => 'id',
                            'label' => __('ID', 'directorist'),
                        ],
                        [
                            'value' => 'count',
                            'label' => __('Count', 'directorist'),
                        ],
                        [
                            'value' => 'name',
                            'label' => __('Name', 'directorist'),
                        ],
                        [
                            'value' => 'slug',
                            'label' => __('Slug', 'directorist'),
                        ],
                    ],
                ],
                'sort_location_by' => [
                    'label'       => __('Sort By', 'directorist'),
                    'type'        => 'select',
                    'value'       => 'asc',
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
                'display_location_listing_count' => [
                    'label'         => __('Display Listing Count', 'directorist'),
                    'type'          => 'toggle',
                    'value'         => true,
                ],
                'hide_empty_locations' => [
                    'label'         => __('Hide Empty Locations', 'directorist'),
                    'type'          => 'toggle',
                    'value'         => false,
                ],
                // registration settings
                'new_user_registration' => [
                    'label'         => __('Enable Registration', 'directorist'),
                    'type'          => 'toggle',
                    'value'         => true,
                ],
                'enable_email_verification' => [
                    'label'         => __('Enable Email Verification', 'directorist'),
                    'type'          => 'toggle',
                    'value'         => false,
                    'description'   => sprintf(__('Enable email verification to verify user email during registration. To view the verification status navigate to Users → %s.', 'directorist'), "<a href='" . admin_url('users.php') . "'>" . __('All Users', 'directorist') . "</a>")
                ],
                'reg_username'    => [
                    'type'          => 'text',
                    'label'         => __('Label', 'directorist'),
                    'value'         => __('Username', 'directorist'),
                ],
                'display_password_reg' => [
                    'label'         => __('Enable', 'directorist'),
                    'type'          => 'toggle',
                    'value'         => true,
                ],
                'reg_password'    => [
                    'type'          => 'text',
                    'label'         => __('Label', 'directorist'),
                    'value'         => __('Password', 'directorist'),
                    'show-if' => [
                        'where' => "display_password_reg",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'require_password_reg' => [
                    'label'         => __('Required', 'directorist'),
                    'type'          => 'toggle',
                    'value'         => true,
                    'show-if' => [
                        'where' => "display_password_reg",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'reg_email'    => [
                    'type'          => 'text',
                    'label'         => __('Label', 'directorist'),
                    'value'         => __('Email', 'directorist'),
                ],
                'display_website_reg' => [
                    'label'         => __('Enable', 'directorist'),
                    'type'          => 'toggle',
                    'value'         => false,
                ],
                'reg_website'    => [
                    'type'          => 'text',
                    'label'         => __('Label', 'directorist'),
                    'value'         => __('Website', 'directorist'),
                    'show-if' => [
                        'where' => "display_website_reg",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'require_website_reg' => [
                    'label'         => __('Required', 'directorist'),
                    'type'          => 'toggle',
                    'value'         => false,
                    'show-if' => [
                        'where' => "display_website_reg",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'display_fname_reg' => [
                    'label'         => __('Enable', 'directorist'),
                    'type'          => 'toggle',
                    'value'         => false,
                ],
                'reg_fname'    => [
                    'type'          => 'text',
                    'label'         => __('Label', 'directorist'),
                    'value'         => __('First Name', 'directorist'),
                    'show-if' => [
                        'where' => "display_fname_reg",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'require_fname_reg' => [
                    'label'         => __('Required', 'directorist'),
                    'type'          => 'toggle',
                    'value'         => false,
                    'show-if' => [
                        'where' => "display_fname_reg",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'display_lname_reg' => [
                    'label'         => __('Enable', 'directorist'),
                    'type'          => 'toggle',
                    'value'         => false,
                ],
                'reg_lname'    => [
                    'type'          => 'text',
                    'label'         => __('Label', 'directorist'),
                    'value'         => __('Last Name', 'directorist'),
                    'show-if' => [
                        'where' => "display_lname_reg",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'require_lname_reg' => [
                    'label'         => __('Required', 'directorist'),
                    'type'          => 'toggle',
                    'value'         => false,
                    'show-if' => [
                        'where' => "display_lname_reg",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'display_bio_reg' => [
                    'label'         => __('Enable', 'directorist'),
                    'type'          => 'toggle',
                    'value'         => false,
                ],
                'reg_bio'    => [
                    'type'          => 'text',
                    'label'         => __('Label', 'directorist'),
                    'value'         => __('About/bio', 'directorist'),
                    'show-if' => [
                        'where' => "display_bio_reg",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'require_bio_reg' => [
                    'label'         => __('Required', 'directorist'),
                    'type'          => 'toggle',
                    'value'         => false,
                    'show-if' => [
                        'where' => "display_bio_reg",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'registration_privacy' => [
                    'label'         => __('Enable', 'directorist'),
                    'type'          => 'toggle',
                    'value'         => true,
                ],
                'registration_privacy_label'    => [
                    'type'          => 'text',
                    'label'         => __('Label', 'directorist'),
                    'value'         => __('I agree to the', 'directorist'),
                    'show-if' => [
                        'where' => "registration_privacy",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'registration_privacy_label_link'    => [
                    'type'          => 'text',
                    'label'         => __('Linking Text', 'directorist'),
                    'value'         => __('Privacy & Policy', 'directorist'),
                    'show-if' => [
                        'where' => "registration_privacy",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'regi_terms_condition' => [
                    'label'         => __('Enable', 'directorist'),
                    'type'          => 'toggle',
                    'value'         => true,
                ],
                'regi_terms_label'    => [
                    'type'          => 'text',
                    'label'         => __('Label', 'directorist'),
                    'value'         => __('I agree with all', 'directorist'),
                    'show-if' => [
                        'where' => "regi_terms_condition",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'regi_terms_label_link'    => [
                    'type'          => 'text',
                    'label'         => __('Linking Text', 'directorist'),
                    'value'         => __('terms & conditions', 'directorist'),
                    'show-if' => [
                        'where' => "regi_terms_condition",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'display_user_type' => [
                    'label'         => __('Enable', 'directorist'),
                    'type'          => 'toggle',
                    'value'         => false,
                ],
                'reg_signup'    => [
                    'type'          => 'text',
                    'label'         => __('Text', 'directorist'),
                    'value'         => __('Sign Up', 'directorist'),
                ],
                'display_login' => [
                    'label'         => __('Enable', 'directorist'),
                    'type'          => 'toggle',
                    'value'         => true,
                ],
                'login_text'    => [
                    'type'          => 'text',
                    'label'         => __('Text', 'directorist'),
                    'value'         => __('Already have an account?', 'directorist'),
                    'show-if' => [
                        'where' => "display_login",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'log_linkingmsg'    => [
                    'type'          => 'text',
                    'label'         => __('Linking Text', 'directorist'),
                    'value'         => __('Login', 'directorist'),
                    'show-if' => [
                        'where' => "display_login",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'auto_login' => [
                    'label'         => __('Auto Login after Registration', 'directorist'),
                    'type'          => 'toggle',
                    'value'         => false,
                ],
                'redirection_after_reg' => [
                    'label' => __('Redirection after Registration', 'directorist'),
                    'type'  => 'select',
                    'value' => get_directorist_option( 'signin_signup_page' ),
                    'options' => $this->get_pages_with_prev_page(),
                ],
                // login settings
                'log_username'    => [
                    'type'          => 'text',
                    'label'         => __('Label', 'directorist'),
                    'value'         => __('Username or Email Address', 'directorist'),
                ],
                'log_password'    => [
                    'type'          => 'text',
                    'label'         => __('Label', 'directorist'),
                    'value'         => __('Password', 'directorist'),
                ],
                'display_rememberme' => [
                    'label'         => __('Enable', 'directorist'),
                    'type'          => 'toggle',
                    'value'         => true,
                ],
                'log_rememberme'    => [
                    'type'          => 'text',
                    'label'         => __('Label', 'directorist'),
                    'value'         => __('Remember Me', 'directorist'),
                    'show-if' => [
                        'where' => "display_rememberme",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'log_button'    => [
                    'type'          => 'text',
                    'label'         => __('Text', 'directorist'),
                    'value'         => __('Log In', 'directorist'),
                ],
                'display_signup' => [
                    'label'         => __('Enable', 'directorist'),
                    'type'          => 'toggle',
                    'value'         => true,
                ],
                'reg_text'    => [
                    'type'          => 'textarea',
                    'label'         => __('Text', 'directorist'),
                    'value'         => __("Don't have an account?", 'directorist'),
                    'show-if' => [
                        'where' => "display_signup",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'reg_linktxt'    => [
                    'type'          => 'text',
                    'label'         => __('Linking Text', 'directorist'),
                    'value'         => __('Sign Up', 'directorist'),
                    'show-if' => [
                        'where' => "display_signup",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'display_recpass' => [
                    'label'         => __('Enable', 'directorist'),
                    'type'          => 'toggle',
                    'value'         => true,
                ],
                'recpass_text'    => [
                    'type'          => 'text',
                    'label'         => __('Name', 'directorist'),
                    'value'         => __('Recover Password', 'directorist'),
                    'show-if' => [
                        'where' => "display_recpass",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'recpass_desc'    => [
                    'type'          => 'textarea',
                    'label'         => __('Description', 'directorist'),
                    'value'         => __('Lost your password? Please enter your email address. You will receive a link to create a new password via email.', 'directorist'),
                    'show-if' => [
                        'where' => "display_recpass",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'recpass_username'    => [
                    'type'          => 'text',
                    'label'         => __('Email Label', 'directorist'),
                    'value'         => __('E-mail', 'directorist'),
                    'show-if' => [
                        'where' => "display_recpass",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'recpass_placeholder'    => [
                    'type'          => 'text',
                    'label'         => __('Username or Email Placeholder', 'directorist'),
                    'value'         => __('eg. mail@example.com', 'directorist'),
                    'show-if' => [
                        'where' => "display_recpass",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'recpass_button'    => [
                    'type'          => 'text',
                    'label'         => __('Button Text', 'directorist'),
                    'value'         => __('Get New Password', 'directorist'),
                    'show-if' => [
                        'where' => "display_recpass",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'redirection_after_login' => [
                    'label' => __('Redirection after Login', 'directorist'),
                    'type'  => 'select',
                    'value' => 'previous_page',
                    'options' => $this->get_pages_with_prev_page(),
                ],
                // email general settings
                'disable_email_notification' => [
                    'label'         => __('Disable all Email Notifications', 'directorist'),
                    'type'          => 'toggle',
                    'value'         => false,
                ],
                'email_from_name'    => [
                    'type'           => 'text',
                    'label'          => __('Sender Name for Emails', 'directorist'),
                    'description'    => __('The name that will appear as the sender in emails generated by Directorist.', 'directorist'),
                    'value'         => get_option('blogname'),
                    'show-if' => [
                        'where' => "disable_email_notification",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => false],
                        ],
                    ],
                ],
                'email_from_email'   => [
                    'type'           => 'text',
                    'label'          => __('Sender Email Address', 'directorist'),
                    'description'    => __('The email address that will appear as the sender in emails generated by Directorist.', 'directorist'),
                    'value'          => get_option('admin_email'),
                    'show-if'        => [
                        'where' => "disable_email_notification",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => false],
                        ],
                    ],
                ],
                'admin_email_lists'     => [
                    'type'              => 'textarea',
                    'label'             => __('Admin Email Address(es) for Notifications', 'directorist'),
                    'description'       => __('Enter one or more email addresses (comma-separated) where admin notifications will be sent. Example: admin1@example.com, admin2@example.com.', 'directorist'),
                    'value'             => get_option('admin_email'),
                    'show-if'           => [
                        'where' => "disable_email_notification",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => false],
                        ],
                    ],
                ],
                'notify_admin' => [
                    'label'       => __('Notify the Admin when Any of the Selected Event Happens', 'directorist'),
                    'type'        => 'checkbox',
                    'value'       =>  $this->default_events_to_notify_admin(),
                    'options'     => $this->events_to_notify_admin(),
                    'description' => __('Select the situation when you would like to send an email to the Admin', 'directorist'),
                    'show-if' => [
                        'where' => "disable_email_notification",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => false],
                        ],
                    ],
                ],
                'notify_user' => [
                    'label'       => __('Notify the Listing Owner when Any of the Selected Event Happens', 'directorist'),
                    'type'        => 'checkbox',
                    'value'       => $this->default_events_to_notify_user(),
                    'options'     => $this->events_to_notify_user(),
                    'description' => __('Select the situation when you would like to send an email to the Listing', 'directorist'),
                    'show-if' => [
                        'where' => "disable_email_notification",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => false],
                        ],
                    ],
                ],
                // email templates settings
                'allow_email_header' => [
                    'label'         => __('Email Header', 'directorist'),
                    'type'          => 'toggle',
                    'value'         => true,
                ],
                'email_header_color'    => [
                    'type'           => 'color',
                    'label'          => __('Email Header Color', 'directorist'),
                    'value'          => '#8569fb',
                    'show-if' => [
                        'where' => "allow_email_header",
                        'conditions' => [
                            ['key' => 'value', 'compare' => '=', 'value' => true],
                        ],
                    ],
                ],
                'email_sub_new_listing'    => [
                    'type'           => 'text',
                    'label'          => __('Email Subject', 'directorist'),
                    'description'    => __('Edit the subject for sending to the user when a listing is submitted/received.', 'directorist'),
                    'value'          => __('[==SITE_NAME==] : Listing "==LISTING_TITLE==" Received', 'directorist'),
                ],
                'email_tmpl_new_listing'    => [
                    'type'           => 'textarea',
                    'label'          => __('Email Body', 'directorist'),
                    'description' => __('Edit the email template for sending to the user when a listing is submitted/received. HTML content is allowed too.', 'directorist'),
                    'value'          => __("
                    Dear ==NAME==,

                    This email is to notify you that your listing '==LISTING_TITLE==' has been received and it is under review now.
                    It may take up to 24 hours to complete the review.

                    Thanks,
                    The Administrator of ==SITE_NAME==
                    ", 'directorist'),
                ],
                'email_sub_pub_listing'    => [
                    'type'           => 'text',
                    'label'          => __('Email Subject', 'directorist'),
                    'description'    => __('Edit the subject for sending to the user when a listing is approved/published.', 'directorist'),
                    'value'          => __('[==SITE_NAME==] : Listing "==LISTING_TITLE==" published', 'directorist'),
                ],
                'email_tmpl_pub_listing'    => [
                    'type'           => 'textarea',
                    'label'          => __('Email Body', 'directorist'),
                    'description'    => __('Edit the email template for sending to the user when a listing is approved/published. HTML content is allowed too.', 'directorist'),
                    'value'          => __("
                    Dear ==NAME==,
                    Congratulations! Your listing '==LISTING_TITLE==' has been approved/published. Now it is publicly available at ==LISTING_URL==

                    Thanks,
                    The Administrator of ==SITE_NAME==
                    ", 'directorist'),
                ],
                'email_sub_edit_listing'    => [
                    'type'           => 'text',
                    'label'          => __('Email Subject', 'directorist'),
                    'description'    => __('Edit the subject for sending to the user when a listing is edited.', 'directorist'),
                    'value'          => __('[==SITE_NAME==] : Listing "==LISTING_TITLE==" Edited', 'directorist'),
                ],
                'email_tmpl_edit_listing'    => [
                    'type'           => 'textarea',
                    'label'          => __('Email Body', 'directorist'),
                    'description'    => __('Edit the email template for sending to the user when a listing is edited. HTML content is allowed too.', 'directorist'),
                    'value'          => __("
                    Dear ==NAME==,
                    Congratulations! Your listing '==LISTING_TITLE==' has been edited. It is publicly available at ==LISTING_URL==

                    Thanks,
                    The Administrator of ==SITE_NAME==
                    ", 'directorist'),
                ],
                'email_sub_to_expire_listing'    => [
                    'type'           => 'text',
                    'label'          => __('Email Subject', 'directorist'),
                    'description'    => __('Edit the subject for sending to the user when a listing is ABOUT TO EXPIRE.', 'directorist'),
                    'value'          => __('[==SITE_NAME==] : Your Listing "==LISTING_TITLE==" is about to expire.', 'directorist'),
                ],
                'email_tmpl_to_expire_listing'    => [
                    'type'           => 'textarea',
                    'label'          => __('Email Body', 'directorist'),
                    'description'    => __('Edit the email template for sending to the user when a listing is ABOUT TO EXPIRE. HTML content is allowed too.', 'directorist'),
                    'value'          => __("
                    Dear ==NAME==,
                    Your listing '==LISTING_TITLE==' is about to expire. It will expire on ==EXPIRATION_DATE==. You can renew it at ==RENEWAL_LINK==

                    Thanks,
                    The Administrator of ==SITE_NAME==
                    ", 'directorist'),
                ],
                'email_sub_expired_listing'    => [
                    'type'           => 'text',
                    'label'          => __('Email Subject', 'directorist'),
                    'description'    => __('Edit the subject for sending to the user when a Listing HAS EXPIRED.', 'directorist'),
                    'value'          => __("[==SITE_NAME==] : Your Listing '==LISTING_TITLE==' has expired.", 'directorist'),
                ],
                'email_tmpl_expired_listing'    => [
                    'type'           => 'textarea',
                    'label'          => __('Email Body', 'directorist'),
                    'description'    => __('Edit the email template for sending to the user when a Listing HAS EXPIRED. HTML content is allowed too.', 'directorist'),
                    'value'          => __("
                    Dear ==NAME==,
                    Your listing '==LISTING_TITLE==' has expired on ==EXPIRATION_DATE==. You can renew it at ==RENEWAL_LINK==

                    Thanks,
                    The Administrator of ==SITE_NAME==
                    ", 'directorist'),
                ],
                'email_sub_to_renewal_listing'    => [
                    'type'           => 'text',
                    'label'          => __('Email Subject', 'directorist'),
                    'description'    => __('Edit the subject for sending to the user to renew his/her listings.', 'directorist'),
                    'value'          => __('[==SITE_NAME==] : A Reminder to Renew your listing "==LISTING_TITLE=="', 'directorist'),
                ],
                'email_tmpl_to_renewal_listing'    => [
                    'type'           => 'textarea',
                    'label'          => __('Email Body', 'directorist'),
                    'description'    => __('Edit the email template for sending to the user to renew his/her listings. HTML content is allowed too.', 'directorist'),
                    'value'          => __("
                    Dear ==NAME==,

                    We have noticed that you might have forgot to renew your listing '==LISTING_TITLE==' at ==SITE_LINK==. We would like to remind you that it expired on ==EXPIRATION_DATE==. But please don't worry.  You can still renew it by clicking this link: ==RENEWAL_LINK==.

                    Thanks,
                    The Administrator of ==SITE_NAME==
                    ", 'directorist'),
                ],
                'email_sub_renewed_listing'    => [
                    'type'           => 'text',
                    'label'          => __('Email Subject', 'directorist'),
                    'description'    => __('Edit the subject for sending to the user his/her listings has renewed successfully.', 'directorist'),
                    'value'          => __('[==SITE_NAME==] : Your Listing "==LISTING_TITLE==" Has Renewed', 'directorist'),
                ],
                'email_tmpl_renewed_listing'    => [
                    'type'           => 'textarea',
                    'label'          => __('Email Body', 'directorist'),
                    'description'    => __('Edit the email template for sending to the user his/her listings has renewed successfully. HTML content is allowed too.', 'directorist'),
                    'value'          => __("
                    Dear ==NAME==,

                    Congratulations!
                    Your listing '==LISTING_LINK==' with the ID #==LISTING_ID== has been renewed successfully at ==SITE_LINK==.
                    Your listing is now publicly viewable at ==LISTING_URL==

                    Thanks,
                    The Administrator of ==SITE_NAME==
                    ", 'directorist'),
                ],
                'email_sub_deleted_listing'    => [
                    'type'           => 'text',
                    'label'          => __('Email Subject', 'directorist'),
                    'description'    => __('Edit the subject for sending to the user when his/her listings has deleted successfully.', 'directorist'),
                    'value'          => __('[==SITE_NAME==] : Your Listing "==LISTING_TITLE==" Has Been Deleted', 'directorist'),
                ],
                'email_tmpl_deleted_listing'    => [
                    'type'           => 'textarea',
                    'label'          => __('Email Body', 'directorist'),
                    'description'    => __('Edit the email template for sending to the user when his/her listings has deleted successfully. HTML content is allowed too.', 'directorist'),
                    'value'          => __("
                    Dear ==NAME==,

                    Your listing '==LISTING_LINK==' with the ID #==LISTING_ID== has been deleted successfully at ==SITE_LINK==.

                    Thanks,
                    The Administrator of ==SITE_NAME==
                    ", 'directorist'),
                ],
                'email_sub_new_order'    => [
                    'type'           => 'text',
                    'label'          => __('Email Subject', 'directorist'),
                    'description'    => __('Edit the subject for sending to the user when an order is created.', 'directorist'),
                    'value'          => __('[==SITE_NAME==] : Your Order (#==ORDER_ID==) Received.', 'directorist'),
                ],
                'email_tmpl_new_order'    => [
                    'type'           => 'textarea',
                    'label'          => __('Email Body', 'directorist'),
                    'description'    => __('Edit the email template for sending to the user when an order is created.', 'directorist'),
                    'value'          => __("
                    Dear ==NAME==,

                    Thank you very much for your order.
                    This email is to notify you that your order (#==ORDER_ID==) has been received. You can check your order details and progress by clicking the link below.

                    Order Details Page: ==ORDER_RECEIPT_URL==

                    Your order summery:
                    ==ORDER_DETAILS==


                    NB. You need to be logged in your account to access the order details page.

                    Thanks,
                    The Administrator of ==SITE_NAME==
                    ", 'directorist'),
                ],
                'email_sub_offline_new_order'    => [
                    'type'           => 'text',
                    'label'          => __('Email Subject', 'directorist'),
                    'description'    => __('Edit the subject for sending to the user when an order is created using offline payment like bank transfer.', 'directorist'),
                    'value'          => __('[==SITE_NAME==] : Your Order (#==ORDER_ID==) Received.', 'directorist'),
                ],
                'email_tmpl_offline_new_order'    => [
                    'type'           => 'textarea',
                    'label'          => __('Email Body', 'directorist'),
                    'description'    => __('Edit the email template for sending to the user when an order is created using offline payment like bank transfer.', 'directorist'),
                    'value'          => sprintf(__("
                    Dear ==NAME==,

                    Thank you very much for your order.
                    This email is to notify you that your order (#==ORDER_ID==) has been received.

                    %s

                    You can check your order details and progress by clicking the link below.
                    Order Details Page: ==ORDER_RECEIPT_URL==

                    Your order summery:
                    ==ORDER_DETAILS==


                    NB. You need to be logged in your account to access the order details page.

                    Thanks,
                    The Administrator of ==SITE_NAME==
                    ", 'directorist'), get_directorist_option('bank_transfer_instruction')),
                ],
                'email_sub_completed_order'    => [
                    'type'           => 'text',
                    'label'          => __('Email Subject', 'directorist'),
                    'description'    => __('Edit the subject for sending to the user when an order is completed', 'directorist'),
                    'value'          => __('[==SITE_NAME==] : Congratulation! Your Order #==ORDER_ID== Completed.', 'directorist'),
                ],
                'email_tmpl_completed_order'    => [
                    'type'           => 'textarea',
                    'label'          => __('Email Body', 'directorist'),
                    'description'    => __('Edit the email template for sending to the user when an order is completed.', 'directorist'),
                    'value'          => __("
                    Dear ==NAME==,

                    Congratulation! This email is to notify you that your order #==ORDER_ID== has been completed.

                    You can check your order details by clicking the link below.
                    Order Details Page: ==ORDER_RECEIPT_URL==

                    Your order summery:
                    ==ORDER_DETAILS==


                    NB. You need to be logged in your account to access the order details page.

                    Thanks,
                    The Administrator of ==SITE_NAME==
                    ", 'directorist'),
                ],
                'email_sub_listing_contact_email'    => [
                    'type'           => 'text',
                    'label'          => __('Email Subject', 'directorist'),
                    'description'    => __('Edit the subject for sending to the user when listing contact message send.', 'directorist'),
                    'value'          => __('==SITE_NAME== Contact via ==LISTING_TITLE==', 'directorist'),
                ],
                'email_tmpl_listing_contact_email'    => [
                    'type'           => 'textarea',
                    'label'          => __('Email Body', 'directorist'),
                    'description'    => __('Edit the email template for sending to the user when when listing contact message send', 'directorist'),
                    'value'          => __("
                    Dear ==NAME==,

                    You have received a message from your listing at ==LISTING_URL==.

                    Name: ==SENDER_NAME==
                    Email: ==SENDER_EMAIL==
                    Message: ==MESSAGE==
                    Time: ==NOW==

                    Thanks,
                    The Administrator of ==SITE_NAME==
                    ", 'directorist'),
                ],
                'email_sub_registration_confirmation'    => [
                    'type'           => 'text',
                    'label'          => __('Email Subject', 'directorist'),
                    'description'    => __('Edit the subject for sending to the user when listing contact message send.', 'directorist'),
                    'value'          => __('Registration Confirmation!', 'directorist'),
                ],
                'email_tmpl_registration_confirmation'    => [
                    'type'           => 'textarea',
                    'label'          => __('Email Body', 'directorist'),
                    'description'    => __('Note: Use ==USER_PASSWORD== to show a temporary password when password field is disable from registration page', 'directorist'),
                    'value'          => __('
                    Hi ==USERNAME==,

                    Thanks for creating an account on <b>==SITE_NAME==</b>. Your username is <b>==USERNAME==</b>. You can access your account area to view listings, change your password, and more at: ==DASHBOARD_LINK==

                    We look forward to seeing you soon', 'directorist'),
                ],
                // Email Verification
                'email_sub_email_verification'    => [
                    'type'           => 'text',
                    'label'          => __('Email Subject', 'directorist'),
                    'description'    => __('Edit the subject for sending to the user when listing contact message send.', 'directorist'),
                    'value'          => __('[==NAME==] Verify Your Email', 'directorist'),
                ],
                'email_tmpl_email_verification'    => [
                    'type'           => 'textarea',
                    'label'          => __('Email Body', 'directorist'),
                    'description'    => __('Note: Use ==USER_PASSWORD== to show a temporary password when password field is disable from registration page', 'directorist'),
                    'value'          => __('Hi ==USERNAME==,
                    Thank you for signing up at ==SITE_NAME==, to complete the registration, please verify your email address.
                    To activate your account simply click on the link below and verify your email address within 24 hours. For your safety, you will not be able to access your account until verification of your email has been completed.
                    ==CONFIRM_EMAIL_ADDRESS_URL==

                    <p align="center">If you did not sign up for this account you can ignore this email.</p>', 'directorist'),
                ],
                // single template settings

                'enable_uninstall'    => [
                    'type'           => 'toggle',
                    'label'          => __('Remove Data on Uninstall?', 'directorist'),
                    'description'=> __('Checked it if you would like Directorist to completely remove all of its data when the plugin is deleted.','directorist'),
                    'value'          => false,
                ],
            ]);

            $this->layouts = apply_filters('atbdp_listing_type_settings_layout', [
                'listing_settings' => [
                    'label' => __( 'Listings', 'directorist' ),
                    'icon' => '<i class="fa fa-list directorist_Blue"></i>',
                    'submenu' => apply_filters('atbdp_listing_settings_submenu', [
                        'general' => [
                            'label' => __('General', 'directorist'),
                            'icon' => '<i class="fa fa-sliders-h"></i>',
                            'sections' => apply_filters( 'atbdp_listing_settings_general_sections', [
                                'general_top_settings' => [
                                    'fields'      => [
                                        // 'all_listing_layout',
                                        'enable_multi_directory',
                                        'guest_listings',
                                        'guest_email_label',
                                        'guest_email_placeholder',
                                    ],
                                ],
                                'registration' => [
                                    'title'       => __( 'Registration', 'directorist' ),
                                    'fields'      => [
                                        'new_user_registration', 'enable_email_verification'
                                     ],
                                ],
                                'listings_currency' => [
                                    'title'       => __( 'Listing Currency', 'directorist' ),
                                    'fields'      => [
                                        'g_currency_note', 'g_currency', 'g_currency_position'
                                     ],
                                ],
                                'listings_renewal' => [
                                    'title'       => __( 'Listings Renewal', 'directorist' ),
                                    'fields'      => [
                                        'email_to_expire_day', 'email_renewal_day',
                                     ],
                                ],
                                'expired_listings_actions' => [
                                    'title'       => __( 'Expired Listings Management', 'directorist' ),
                                    'fields'      => [
                                        'delete_expired_listing_permanently', 'delete_expired_listings_after',
                                     ],
                                ],
                            ] ),
                        ],
                        'listings_page' => [
                            'label' => __('All Listings', 'directorist'),
                            'icon' => '<i class="fa fa-archive"></i>',
                            'sections' => apply_filters( 'atbdp_listing_settings_listings_page_sections', [
                                'layout_search' => [
                                    'title'       => __(' Layout & Search', 'directorist' ),
                                    'fields'      => [
                                        'all_listing_layout',
                                        'all_listing_columns',
                                        'all_listing_page_items',
                                        'listing_hide_top_search_bar',
                                        'listings_sidebar_filter_text',
                                        'listings_reset_text',
                                        'listings_sidebar_reset_text',
                                        'listings_apply_text',
                                     ],
                                ],
                                'header' => [
                                    'title'       => __( 'Header', 'directorist' ),
                                    'fields'      => [
                                        'display_listings_header',
                                        'listing_filters_button',
                                        'listings_filter_button_text',
                                        'display_listings_count',
                                        'all_listing_title',
                                        'listings_view_as_items',
                                        'default_listing_view',
                                        'display_sort_by',
                                        'sort_by_text',
                                        'listings_sort_by_items',
                                     ],
                                ],
                                'preview_image' => [
                                    'title'       => __( 'Preview Image', 'directorist' ),
                                    'fields'      => [
                                         'preview_image_quality',
                                         'way_to_show_preview',
                                         'crop_width',
                                         'crop_height',
                                         'prv_container_size_by',
                                         'prv_background_type',
                                         'prv_background_color'
                                    ],
                                ],
                            ] ),
                        ],
                        'single_listing' => [
                            'label' => __('Single Listing', 'directorist'),
                            'icon' => '<i class="fa fa-info"></i>',
                            'sections' => apply_filters( 'atbdp_listing_settings_listing_page_sections', [
                                'listing_template_view' => [
                                    'title'       => __( 'Listing Template and View', 'directorist' ),
                                    'fields'      => [
                                        'single_listing_template', 'disable_single_listing', 'restrict_single_listing_for_logged_in_user',  
                                    ],
                                ],
                                'listing_permalink' => [
                                    'title'       => __( 'Listing Permalink', 'directorist' ),
                                    'fields'      => [
                                        'atbdp_listing_slug', 
                                        'single_listing_slug_with_directory_type',
                                    ],
                                ],
                                'submission_confirmation' => [
                                    'title'       => __( 'Submission Confirmations', 'directorist' ),
                                    'fields'      => [
                                        'submission_confirmation', 
                                        'pending_confirmation_msg', 
                                        'publish_confirmation_msg',
                                    ],
                                ],
                                'slider_image' => [
                                    'title'       => __( 'Slider Image', 'directorist' ),
                                    'fields'      => [
                                        'dsiplay_slider_single_page', 
                                        'single_slider_image_size', 
                                        'single_slider_background_type', 
                                        'single_slider_background_color', 
                                        'gallery_crop_width', 
                                        'gallery_crop_height'
                                    ],
                                ],
                            ] ),
                        ],
                        'categories_locations' => [
                            'label' => __( 'Category & Location', 'directorist' ),
                            'icon' => '<i class="fa fa-list-alt"></i>',
                            'sections' => apply_filters( 'atbdp_categories_settings_sections', [
                                'categories_settings' => [
                                    'title'       => __('Categories Page', 'directorist'),
                                    'fields'      => [
                                        'display_categories_as', 'categories_column_number', 'categories_depth_number', 'order_category_by', 'sort_category_by', 'display_listing_count', 'hide_empty_categories'
                                     ],
                                ],
                                'locations_settings' => [
                                    'title'       => __('Locations Page', 'directorist'),
                                    'description' => '',
                                    'fields'      => [
                                        'display_locations_as', 'locations_column_number', 'locations_depth_number', 'order_location_by', 'sort_location_by', 'display_location_listing_count', 'hide_empty_locations'
                                     ],
                                ],
                            ] ),
                        ],

                        'map' => [
                            'label' => __('Map', 'directorist'),
                            'icon' => '<i class="fa fa-map"></i>',
                            'sections' => apply_filters( 'atbdp_listing_settings_map_sections', [
                                'map_settings' => [
                                    'title'       => __('Map', 'directorist'),
                                    'description' => '',
                                    'fields'      => [
                                        'select_listing_map', 'map_api_key', 'marker_clustering', 'country_restriction', 'restricted_countries', 'default_latitude', 'default_longitude', 'use_def_lat_long', 'map_zoom_level', 'map_view_zoom_level', 'listings_map_height'
                                    ],
                                ],
                                'map_info_window' => [
                                    'title'       => __('Map Info Window Settings', 'directorist'),
                                    'description' => '',
                                    'fields'      => [
                                        'display_map_info', 'display_image_map', 'display_favorite_badge_map', 'display_user_avatar_map', 'display_title_map', 'display_review_map', 'display_price_map', 'display_address_map', 'display_direction_map', 'display_phone_map'
                                    ],
                                ],
                            ] ),
                        ],
                        'badge' => [
                            'label' => __('Badges', 'directorist'),
                            'icon' => '<i class="fa fa-certificate"></i>',
                            'sections' => apply_filters( 'atbdp_listing_settings_badge_sections', [
                                'badge_management' => [
                                    'title'       => __('New Badge', 'directorist'),
                                    'description' => '',
                                    'fields'      => [
                                        'new_badge_text', 'new_listing_day', 'new_back_color',
                                    ],
                                ],
                                'popular_badge' => [
                                    'title'       => __('Popular Badge', 'directorist'),
                                    'description' => '',
                                    'fields'      => [
                                        'popular_badge_text', 'listing_popular_by', 'views_for_popular', 'average_review_for_popular', 'count_loggedin_user', 'popular_back_color',
                                    ],
                                ],
                                'featured_badge' => [
                                    'title'       => __('Featured Badge', 'directorist'),
                                    'description' => '',
                                    'fields'      => [
                                        'feature_badge_text', 'featured_back_color',
                                    ],
                                ],
                            ] ),
                        ],

                    ]),
                ],

                'page_settings' => [
                    'label' => __( 'Page Setup', 'directorist' ),
                    'icon' => '<i class="fa fa-desktop directorist_wordpress"></i>',
                    'sections' => apply_filters( 'atbdp_listing_settings_page_settings_sections', [
                        'upgrade_pages' => [
                            'title'       => __('Upgrade/Regenerate Pages', 'directorist'),
                            'description' => '',
                            'fields'      => [
                                'regenerate_pages'
                             ],
                        ],
                        'pages_links_views' => [
                            'title'       => __('Page, Links & View Settings', 'directorist'),
                            'description' => '',
                            'fields'      => apply_filters( 'atbdp_pages_settings_fields', [
                                'add_listing_page', 'all_listing_page', 'user_dashboard', 'signin_signup_page', 'author_profile_page', 'all_categories_page', 'single_category_page', 'all_locations_page', 'single_location_page', 'single_tag_page', 'search_listing', 'search_result_page', 'checkout_page', 'payment_receipt_page', 'transaction_failure_page', 'privacy_policy', 'terms_conditions'
                             ] ),
                        ],
                    ]),
                ],

                'search_settings' => [
                    'label' => __( 'Search', 'directorist' ),
                    'icon' => '<i class="fa fa-search directorist_warning"></i>',
                    'submenu' => apply_filters('atbdp_email_settings_submenu', [
                        'search_form' => [
                            'label' => __('Search Listing', 'directorist'),
                            'icon' => '<i class="fa fa-search"></i>',
                            'sections' => apply_filters( 'directorist_search_setting_sections', [
                                'search_bar' => [
                                    'title'       => __( 'Search Bar', 'directorist' ),
                                    'fields'      => [
                                        'search_title', 'search_subtitle', 'search_home_bg', 'search_listing_text',
                                     ],
                                ],
                                'search_filters' => [
                                    'title'       => __( 'Filters', 'directorist' ),
                                    'fields'      => [
                                        'search_more_filter', 'search_more_filters', 'search_filters', 'search_reset_text', 'search_apply_filter',
                                     ],
                                ],
                                'poplar_categories' => [
                                    'title'       => __( 'Popular Categories', 'directorist' ),
                                    'fields'      => [
                                        'show_popular_category', 'popular_cat_title', 'popular_cat_num',
                                     ],
                                ],
                            ] ),
                        ],

                        'search_result' => [
                            'label' => __('Search Result', 'directorist'),
                            'icon' => '<i class="fa fa-check"></i>',
                            'sections' => apply_filters( 'atbdp_reg_settings_sections', [
                                'search_result_layout' => [
                                    'title'       => __('Layout & Search', 'directorist' ),
                                    'fields'      => [
                                        'search_result_layout',
                                        'search_listing_columns',
                                        'search_posts_num',
                                        'search_result_hide_top_search_bar',
                                        'search_result_sidebar_filter_text',
                                        'sresult_reset_text',
                                        'sresult_sidebar_reset_text',
                                        'sresult_apply_text',
                                     ],
                                ],
                                'search_result_header' => [
                                    'title'       => __('Header', 'directorist' ),
                                    'fields'      => [
                                        'search_header',
                                        'search_result_filters_button_display',
                                        'search_result_filter_button_text',
                                        'display_search_result_listings_count',
                                        'search_result_listing_title',
                                        'search_view_as_items',
                                        'search_sort_by',
                                        'search_sortby_text',
                                        'search_sort_by_items'
                                     ],
                                ],
                            ] ),
                        ],

                    ]),
                ],

                'user_settings' => [
                    'label' => __( 'User', 'directorist' ),
                    'icon' => '<i class="fa fa-users-cog directorist_green"></i>',
                    'submenu' => apply_filters('atbdp_user_settings_submenu', [
                        'registration_form' => [
                            'label' => __('Registration Form', 'directorist'),
                            'icon' => '<i class="fa fa-envelope-open"></i>',
                            'sections' => apply_filters( 'atbdp_reg_settings_sections', [
                                'username' => [
                                    'title'       => __('Username', 'directorist'),
                                    'description' => '',
                                    'fields'      => [
                                        'reg_username'
                                     ],
                                ],
                                'password' => [
                                    'title'       => __('Password', 'directorist'),
                                    'description' => '',
                                    'fields'      => [
                                        'display_password_reg', 'reg_password', 'require_password_reg'
                                     ],
                                ],
                                'email' => [
                                    'title'       => __('Email', 'directorist'),
                                    'description' => '',
                                    'fields'      => [
                                        'reg_email'
                                     ],
                                ],
                                'website' => [
                                    'title'       => __('Website', 'directorist'),
                                    'description' => '',
                                    'fields'      => [
                                        'display_website_reg', 'reg_website', 'require_website_reg'
                                     ],
                                ],
                                'first_name' => [
                                    'title'       => __('First Name', 'directorist'),
                                    'description' => '',
                                    'fields'      => [
                                        'display_fname_reg', 'reg_fname', 'require_fname_reg'
                                     ],
                                ],
                                'last_name' => [
                                    'title'       => __('Last Name', 'directorist'),
                                    'description' => '',
                                    'fields'      => [
                                        'display_lname_reg', 'reg_lname', 'require_lname_reg'
                                     ],
                                ],
                                'about' => [
                                    'title'       => __('About/Bio', 'directorist'),
                                    'description' => '',
                                    'fields'      => [
                                        'display_bio_reg', 'reg_bio', 'require_bio_reg'
                                     ],
                                ],
                                'user_type' => [
                                    'title'       => __('User Type Registration', 'directorist'),
                                    'description' => '',
                                    'fields'      => [
                                        'display_user_type'
                                     ],
                                ],
                                'privacy_policy' => [
                                    'title'       => __('Privacy Policy', 'directorist'),
                                    'description' => '',
                                    'fields'      => [
                                        'registration_privacy', 'registration_privacy_label', 'registration_privacy_label_link'
                                     ],
                                ],
                                'terms_condition' => [
                                    'title'       => __('Terms Conditions', 'directorist'),
                                    'description' => '',
                                    'fields'      => [
                                        'regi_terms_condition', 'regi_terms_label', 'regi_terms_label_link'
                                     ],
                                ],

                                'signup_button' => [
                                    'title'       => __('Sign Up Button', 'directorist'),
                                    'description' => '',
                                    'fields'      => [
                                        'reg_signup'
                                     ],
                                ],
                                'login_message' => [
                                    'title'       => __('Login Message', 'directorist'),
                                    'description' => '',
                                    'fields'      => [
                                        'display_login', 'login_text', 'log_linkingmsg'
                                     ],
                                ],
                                'redirection' => [
                                    'title'       => __('', 'directorist'),
                                    'description' => '',
                                    'fields'      => [
                                        'auto_login', 'redirection_after_reg'
                                     ],
                                ],
                            ] ),
                        ],
                        'login_form' => [
                            'label' => __('Login Form', 'directorist'),
                            'icon' => '<i class="fa fa-mail-bulk"></i>',
                            'sections' => apply_filters( 'directorist_login_form_templates_settings_sections', [
                                'username' => [
                                    'title'       => __('Username', 'directorist'),
                                    'description' => '',
                                    'fields'      => [
                                        'log_username'
                                     ],
                                ],
                                'password' => [
                                    'title'       => __('Password', 'directorist'),
                                    'description' => '',
                                    'fields'      => [
                                        'log_password'
                                     ],
                                ],
                                'remember_login_info' => [
                                    'title'       => __('Remember Login Information', 'directorist'),
                                    'description' => '',
                                    'fields'      => [
                                        'display_rememberme', 'log_rememberme'
                                     ],
                                ],
                                'login_button' => [
                                    'title'       => __('Login Button', 'directorist'),
                                    'description' => '',
                                    'fields'      => [
                                        'log_button'
                                     ],
                                ],
                                'signup_message' => [
                                    'title'       => __('Sign Up Message', 'directorist'),
                                    'description' => '',
                                    'fields'      => [
                                        'display_signup', 'reg_text', 'reg_linktxt'
                                     ],
                                ],
                                'recover_password' => [
                                    'title'       => __('Recover Password', 'directorist'),
                                    'description' => '',
                                    'fields'      => [
                                        'display_recpass', 'recpass_text', 'recpass_desc', 'recpass_username', 'recpass_placeholder', 'recpass_button'
                                     ],
                                ],
                                'login_redirect' => [
                                    'title'       => '',
                                    'description' => '',
                                    'fields'      => [
                                        'redirection_after_login'
                                     ],
                                ],

                            ] ),
                        ],

                        'user_dashboard' => [
                            'label' => __('Dashboard', 'directorist'),
                            'icon' => '<i class="fa fa-chart-bar"></i>',
                            'sections' => apply_filters( 'atbdp_listing_settings_user_dashboard_sections', [
                                'general_dashboard' => [
                                    'fields'      => [
                                         'my_profile_tab', 'my_profile_tab_text', 'fav_listings_tab', 'fav_listings_tab_text', 'announcement_tab', 'announcement_tab_text'
                                    ],
                                ],
                                'author_dashboard' => [
                                    'title'       => __('Author Dashboard', 'directorist'),
                                    'description' => '',
                                    'fields'      => [
                                        'my_listing_tab', 'my_listing_tab_text', 'user_listings_pagination', 'user_listings_per_page', 'submit_listing_button'
                                        ],
                                ],
                                'user_dashboard' => [
                                    'title'       => __('User Dashboard', 'directorist'),
                                    'description' => '',
                                    'fields'      => [
                                        'become_author_button', 'become_author_button_text'
                                        ],
                                ],
                            ] ),
                        ],
                        'all_authors' => [
                            'label' => __('All Authors', 'directorist'),
                            'icon' => '<i class="fa fa-users"></i>',
                            'sections' => apply_filters( 'atbdp_listing_settings_user_dashboard_sections', [
                                'all_authors' => [
                                    'title'       => __('All Authors', 'directorist'),
                                    'description' => '',
                                    'fields'      => [
                                        'all_authors_columns', 'all_authors_sorting', 'all_authors_image', 'all_authors_name', 'all_authors_select_role', 'all_authors_contact', 'all_authors_description', 'all_authors_description_limit', 'all_authors_social_info', 'all_authors_button', 'all_authors_button_text', 'all_authors_pagination', 'all_authors_per_page'
                                        ],
                                ],
                            ] ),
                        ],
                    ]),
                ],

                'email_settings' => [
                    'label' => __( 'Email', 'directorist' ),
                    'icon' => '<i class="fa fa-envelope directorist_Blue"></i>',
                    'submenu' => apply_filters('atbdp_email_settings_submenu', [
                        'email_general' => [
                            'label' => __('General', 'directorist'),
                            'icon' => '<i class="fa fa-envelope-open directorist_info"></i>',
                            'sections' => apply_filters( 'atbdp_reg_settings_sections', [
                                'sender_details' => [
                                    'title'       => __( 'Sender Details', 'directorist' ),
                                    'fields'      => [
                                        'email_from_name', 
                                        'email_from_email',
                                     ],
                                ],
                                'email_notification' => [
                                    'title'       => __( 'Email Notifications', 'directorist' ),
                                    'fields'      => [
                                        'disable_email_notification', 'admin_email_lists', 'notify_admin', 'notify_user'
                                     ],
                                ],
                            ] ),
                        ],
                        'email_templates' => [
                            'label' => __('Templates', 'directorist'),
                            'icon' => '<i class="fa fa-mail-bulk directorist_info"></i>',
                            'sections' => apply_filters( 'atbdp_email_templates_settings_sections', [
                                'general' => [
                                    'title'       => __('General', 'directorist'),
                                    'description' => '',
                                    'fields'      => [
                                        'allow_email_header', 'email_header_color'
                                     ],
                                ],
                                'new_listing' => [
                                    'title'       => __('For New Listing', 'directorist'),
                                    'description' => '',
                                    'fields'      => [
                                        'email_note', 'email_sub_new_listing', 'email_tmpl_new_listing'
                                     ],
                                ],
                                'approved_listings' => [
                                    'title'       => __('For Approved/Published Listings', 'directorist'),
                                    'description' => '',
                                    'fields'      => [
                                        'email_sub_pub_listing', 'email_tmpl_pub_listing'
                                     ],
                                ],
                                'edited_listings' => [
                                    'title'       => __('For Edited Listings', 'directorist'),
                                    'description' => '',
                                    'fields'      => [
                                        'email_sub_edit_listing', 'email_tmpl_edit_listing'
                                     ],
                                ],
                                'about_expire_listings' => [
                                    'title'       => __('For About To Expire Listings', 'directorist'),
                                    'description' => '',
                                    'fields'      => [
                                       'email_sub_to_expire_listing', 'email_tmpl_to_expire_listing'
                                     ],
                                ],
                                'expired_listings' => [
                                    'title'       => __('For Expired Listings', 'directorist'),
                                    'description' => '',
                                    'fields'      => [
                                        'email_sub_expired_listing', 'email_tmpl_expired_listing'
                                     ],
                                ],
                                'remind_renewal_listings' => [
                                    'title'       => __('For Renewal Listings (Remind To Renew)', 'directorist'),
                                    'description' => '',
                                    'fields'      => [
                                        'email_sub_to_renewal_listing', 'email_tmpl_to_renewal_listing'
                                     ],
                                ],
                                'after_renewed_listings' => [
                                    'title'       => __('For Renewed Listings (After Renewed)', 'directorist'),
                                    'description' => '',
                                    'fields'      => [
                                        'email_sub_renewed_listing', 'email_tmpl_renewed_listing'
                                     ],
                                ],
                                'deleted_listings' => [
                                    'title'       => __('For Deleted/Trashed Listings', 'directorist'),
                                    'description' => '',
                                    'fields'      => [
                                        'email_sub_deleted_listing', 'email_tmpl_deleted_listing'
                                     ],
                                ],
                                'new_order_created' => [
                                    'title'       => __('For New Order (Created)', 'directorist'),
                                    'description' => '',
                                    'fields'      => [
                                        'email_sub_new_order', 'email_tmpl_new_order'
                                     ],
                                ],
                                'new_order_offline_bank' => [
                                    'title'       => __('For New Order (Created Using Offline Bank Transfer)', 'directorist'),
                                    'description' => '',
                                    'fields'      => [
                                        'email_sub_offline_new_order', 'email_tmpl_offline_new_order'
                                     ],
                                ],
                                'completed_order' => [
                                    'title'       => __('For Completed Order', 'directorist'),
                                    'description' => '',
                                    'fields'      => [
                                        'email_sub_completed_order', 'email_tmpl_completed_order'
                                     ],
                                ],
                                'listing_contact_email' => [
                                    'title'       => __('For Listing Contact Email', 'directorist'),
                                    'description' => '',
                                    'fields'      => [
                                        'email_sub_listing_contact_email', 'email_tmpl_listing_contact_email'
                                     ],
                                ],
                                'registration_confirmation' => [
                                    'title'       => __('Registration Confirmation', 'directorist'),
                                    'description' => '',
                                    'fields'      => [
                                        'email_sub_registration_confirmation', 'email_tmpl_registration_confirmation'
                                     ],
                                ],
                                'email_verification' => [
                                    'title'       => __('Email Verification', 'directorist'),
                                    'description' => '',
                                    'fields'      => [
                                        'email_sub_email_verification', 'email_tmpl_email_verification'
                                     ],
                                ],
                            ] ),
                        ],
                    ]),
                ],

                'monetization_settings' => [
                    'label' => __( 'Monetization', 'directorist' ),
                    'icon' => '<i class="fa fa-credit-card directorist_info"></i>',
                    'submenu' => apply_filters('atbdp_monetization_settings_submenu', [
                        'monetization_general' => [
                            'label' => __('General Settings', 'directorist'),
                            'icon' => '<i class="fa fa-home"></i>',
                            'sections' => apply_filters( 'atbdp_listing_settings_monetization_general_sections', [
                                'general' => [
                                    'description' => '',
                                    'fields'      => [ 
                                        'enable_monetization',
                                    ],
                                ],
                                'currency' => [
                                    'title'       => __( 'Currency', 'directorist' ),
                                    'description' => '',
                                    'fields'      => [ 
                                        'payment_currency_note',
                                        'payment_currency',
                                        'payment_thousand_separator',
                                        'payment_decimal_separator',
                                        'payment_currency_position'],
                                ],
                            ] ),
                        ],
                        'featured_listings' => [
                            'label' => __('Featured Listings', 'directorist'),
                            'icon' => '<i class="fa fa-arrow-up"></i>',
                            'sections' => apply_filters( 'atbdp_listing_settings_featured_sections', [
                                'featured' => [
                                    'fields'      => [
                                        'enable_featured_listing',
                                        'featured_listing_desc',
                                        'featured_listing_price',
                                        'featured_listing_time',
                                    ],
                                ],
                            ] ),
                        ],
                        'gateway' => [
                            'label' => __('Payment Gateways', 'directorist'),
                            'icon' => '<i class="fa fa-bezier-curve"></i>',
                            'sections' => apply_filters( 'atbdp_listing_settings_gateway_sections', [
                                'gateway_general' => [
                                    'fields'      => [
                                        'default_gateway',
                                        'active_gateways',
                                    ],
                                ],
                            ] ),
                        ],
                        'offline_gateway' => [
                            'label' => __('Bank Transfer', 'directorist'),
                            'icon' => '<i class="fa fa-university"></i>',
                            'sections' => apply_filters( 'atbdp_listing_settings_offline_gateway_sections', [
                                'offline_gateway_general' => [
                                    'fields'      => [
                                        'offline_payment_note',
                                        'bank_transfer_title',
                                        'bank_transfer_description',
                                        'bank_transfer_instruction'
                                    ],
                                ],
                            ] ),
                        ],
                    ]),
                ],

                'personalization' => [
                    'label' => __( 'Personalization', 'directorist' ),
                    'icon' => '<i class="fa fa-paint-brush directorist_success"></i>',
                    'sections'=> apply_filters('atbdp_style_settings_controls', [
                        'button_type' => [
                            'title' => __('Button Color', 'directorist'),
                            'fields' => [
                                'button_type', 'primary_example', 'primary_color', 'primary_hover_color', 'back_primary_color', 'back_primary_hover_color', 'border_primary_color', 'border_primary_hover_color', 'secondary_example', 'secondary_color', 'secondary_hover_color', 'back_secondary_color', 'back_secondary_hover_color', 'secondary_border_color', 'secondary_border_hover_color', 'danger_example', 'danger_color', 'danger_hover_color', 'back_danger_color', 'back_danger_hover_color', 'danger_border_color', 'danger_border_hover_color', 'success_example', 'success_color', 'success_hover_color', 'back_success_color', 'back_success_hover_color', 'border_success_color', 'border_success_hover_color', 'lighter_example', 'lighter_color', 'lighter_hover_color', 'back_lighter_color', 'back_lighter_hover_color', 'border_lighter_color', 'border_lighter_hover_color', 'priout_example', 'priout_color', 'priout_hover_color', 'back_priout_color', 'back_priout_hover_color', 'border_priout_color', 'border_priout_hover_color', 'prioutlight_example', 'prioutlight_color', 'prioutlight_hover_color', 'back_prioutlight_color', 'back_prioutlight_hover_color', 'border_prioutlight_color', 'border_prioutlight_hover_color', 'danout_example', 'danout_color', 'danout_hover_color', 'back_danout_color', 'back_danout_hover_color', 'border_danout_color', 'border_danout_hover_color'
                            ]
                        ],
                        'map_marker' => [
                            'title' => __('All Listings Map Marker', 'directorist'),
                            'fields' => apply_filters('atbdp_map_marker_color', [
                                'marker_shape_color',
                                'marker_icon_color'
                            ])
                        ],

                        'primary_color' =>  [
                            'title'  => __('Primary Color', 'directorist'),
                            'fields' => apply_filters('atbdp_primary_dark_color', [
                                'primary_dark_back_color',
                                'primary_dark_border_color'
                            ])
                        ],
                    ])
                ],

                'extension_settings' => [
                    'label' => __( 'Extensions', 'directorist' ),
                    'icon' => '<i class="fa fa-magic directorist_danger"></i>',
                    'submenu' => apply_filters('atbdp_extension_settings_submenu', [
                        'extensions_general' => [
                            'label' => __('Extensions General', 'directorist'),
                            'icon' => '<i class="fa fa-home"></i>',
                            'sections' => apply_filters( 'atbdp_extension_settings_controls', [
                                'general_settings' => [
                                    'fields' =>  apply_filters( 'atbdp_extension_fields', [
                                         'extension_promotion'
                                    ]) ,
                                ],
                            ] ),
                        ],
                    ]),
                ],

                'import_export' => [
                    'label' => __( 'Import and Export', 'directorist' ),
                    'icon' => '<i class="fa fa-tools directorist_info"></i>',
                    'sections'    => apply_filters('atbdp_listings_import_controls', [
                        'import_methods' => array(
                            'title'      => __( 'Listings', 'directorist' ),
                            'fields'     => apply_filters('atbdp_csv_import_settings_fields', [
                                'listing_import_button', 'listing_export_button',
                            ]),
                        ),
                        'export_methods' => array(
                            'title'      => __( 'Settings', 'directorist' ),
                            'fields'     => apply_filters('atbdp_csv_export_settings_fields', [
                                'import_settings', 'export_settings', 'restore_default_settings'
                            ]),
                        ),
                    ]),
                ],

                'advanced' => [
                    'label' => __( 'Advanced', 'directorist' ),
                    'icon' => '<i class="fa fa-filter directorist_wordpress"></i>',
                    'submenu' => apply_filters('atbdp_advanced_submenu', [
                        'seo_settings' => [
                            'label' => __( 'Title & Meta (SEO)', 'directorist' ),
                            'icon' => '<i class="fa fa-bolt"></i>',
                            'sections' => apply_filters( 'atbdp_seo_settings_sections', [
                                'title_metas' => [
                                    'fields'      => [
                                        'atbdp_enable_seo', 'add_listing_page_meta_title', 'add_listing_page_meta_desc', 'all_listing_meta_title', 'all_listing_meta_desc', 'dashboard_meta_title', 'dashboard_meta_desc', 'author_profile_meta_title', 'author_page_meta_desc', 'category_meta_title', 'category_meta_desc', 'single_category_meta_title', 'single_category_meta_desc', 'all_locations_meta_title', 'all_locations_meta_desc', 'single_locations_meta_title', 'single_locations_meta_desc', 'registration_meta_title', 'registration_meta_desc', 'login_meta_title', 'login_meta_desc', 'homepage_meta_title', 'homepage_meta_desc', 'meta_title_for_search_result', 'search_result_meta_title', 'search_result_meta_desc'
                                     ],
                                ],
                            ] ),
                        ],

                        'miscellaneous' => [
                            'label'     => __('Miscellaneous', 'directorist'),
                            'icon' => '<i class="fas fa-thumbtack"></i>',
                            'sections'  => apply_filters('atbdp_caching_controls', [
                                'caching' => [
                                    'title' => __( 'Caching', 'directorist' ),
                                    'fields'      => [
                                        'atbdp_enable_cache', 'atbdp_reset_cache',
                                     ],
                                ],
                                'debugging' => [
                                    'title' => __( 'Debugging', 'directorist' ),
                                    'fields'      => [
                                        'script_debugging',
                                     ],
                                ],
                                'uninstall' => [
                                    'title' => __( 'Uninstall', 'directorist' ),
                                    'fields' => [ 'enable_uninstall' ]
                                ],
                            ] ),
                        ],
                    ]),
                ],

            ]);

            $this->config = [
                'fields_theme' => 'butterfly',
                'submission' => [
                    'url' => admin_url('admin-ajax.php'),
                    'with' => [
                        'action' => 'save_settings_data',
                        'directorist_nonce' => wp_create_nonce( directorist_get_nonce_key() ),
                    ],
                ],
            ];


        }


        // add_menu_pages
        public function add_menu_pages()
        {
            add_submenu_page(
                'edit.php?post_type=at_biz_dir',
                'Settings',
                'Settings',
                'manage_options',
                'atbdp-settings',
                [ $this, 'menu_page_callback__settings_manager' ],
                12
            );
        }

        // menu_page_callback__settings_manager
        public function menu_page_callback__settings_manager()
        {
            // Prepare Settings
            $this->prepare_settings();

            // Get Saved Data
            $atbdp_options = get_option('atbdp_option');

            foreach( $this->fields as $field_key => $field_opt ) {
                if ( ! isset(  $atbdp_options[ $field_key ] ) ) {
                    $this->fields[ $field_key ]['forceUpdate'] = true;
                    continue;
                }

                $this->fields[ $field_key ]['value'] = $atbdp_options[ $field_key ];
            }

			$settings_builder_data = [
                'fields'  => $this->fields,
                'layouts' => $this->layouts,
                'config'  => $this->config,
            ];

            /* $status = $this->update_settings_options([
                'new_listing_status' => 'publish',
                'edit_listing_status' => 'publish',
            ]);

            $check_new = get_directorist_option( 'new_listing_status' );
            $check_edit = get_directorist_option( 'edit_listing_status' );

            var_dump( [ '$check_new' => $check_new,  '$check_edit' => $check_edit] ); */

			$settings_builder_data['fields'] = $this->sanitize_fields_data( $settings_builder_data['fields'] );

            $data = [
                'settings_builder_data' => base64_encode( json_encode( $settings_builder_data ) )
            ];

            atbdp_load_admin_template( 'settings-manager/settings', $data );
        }

		/**
		 * Sanitize Fields Data
		 *
		 * @param array $fields
		 * @return array Fields
		 */
		public function sanitize_fields_data( $fields ) {

			foreach( $fields as $key => $field_args ) {

				foreach( $field_args as $field_args_key => $field_args_value ) {

					$type = isset( $field_args['type'] ) ? $field_args['type'] : 'text';

					if ( 'value' === $field_args_key && 'textarea' === $type ) {
						$fields[ $key ][ $field_args_key ] = sanitize_textarea_field( $field_args_value );
						continue;
					}

					if ( 'value' === $field_args_key && 'number' === $type ) {
						$fields[ $key ][ $field_args_key ] = floatval( sanitize_text_field( $field_args_value ) );
						continue;
					}

					$fields[ $key ][ $field_args_key ] = directorist_clean_post( $field_args_value );
				}

			}

			return $fields;
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
        function get_pages_vl_arrays()
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

        function get_user_roles()
        {
            $get_editable_roles = get_editable_roles();
            $role               = array();
            $role[]             = array( 'value' => 'all', 'label' => __( 'All', 'directorist' ) );
            if( $get_editable_roles ) {
                foreach( $get_editable_roles as $key => $value ) {
                    $role[] = array(
                        'value' => $key,
                        'label' => $value['name']
                    );
                }
            }

            return $role;
        }

        /**
         * Get all the pages with previous page in an array where each page is an array of key:value:id and key:label:name
         *
         * Example : array(
         *                  array('value'=> 1, 'label'=> 'page_name'),
         *                  array('value'=> 50, 'label'=> 'page_name'),
         *          )
         * @return array page names with key value pairs in a multi-dimensional array
         * @since 3.0.0
         */
        function get_pages_with_prev_page()
        {
            $pages = get_pages();
            $pages_options = array();
            $pages_options[] = array( 'value' => 'previous_page', 'label' => 'Previous Page' );
            if ($pages) {
                foreach ($pages as $page) {
                    $pages_options[] = array('value' => $page->ID, 'label' => $page->post_title);
                }
            }

            return $pages_options;
        }

        /**
         * Get an array of events to notify both the admin and the users
         * @return array it returns an array of events
         * @since 3.1.0
         */
        private function default_notifiable_events()
        {
            return apply_filters('atbdp_default_notifiable_events', array(
                array(
                    'value' => 'order_created',
                    'label' => __('Order Created', 'directorist'),
                ),
                array(
                    'value' => 'order_completed',
                    'label' => __('Order Completed', 'directorist'),
                ),
                array(
                    'value' => 'listing_submitted',
                    'label' => __('New Listing Submitted', 'directorist'),
                ),
                array(
                    'value' => 'listing_published',
                    'label' => __('Listing Approved/Published', 'directorist'),
                ),
                array(
                    'value' => 'listing_edited',
                    'label' => __('Listing Edited', 'directorist'),
                ),
                array(
                    'value' => 'payment_received',
                    'label' => __('Payment Received', 'directorist'),
                ),
                array(
                    'value' => 'listing_deleted',
                    'label' => __('Listing Deleted', 'directorist'),
                ),
                array(
                    'value' => 'listing_contact_form',
                    'label' => __('Listing Contact Form', 'directorist'),
                ),
                array(
                    'value' => 'listing_review',
                    'label' => __('Listing Review', 'directorist'),
                ),
                array(
                    'value' => 'listing_renewed',
                    'label' => __('Listing Renewed', 'directorist'),
                ),
            ));
        }

        /**
         * Get the list of an array of notification events array to notify admin
         * @return array It returns an array of events when an admin should be notified
         * @since 3.1.0
         */
        public function events_to_notify_admin()
        {
            $events = $this->default_notifiable_events();
            return apply_filters('atbdp_events_to_notify_admin', $events);
        }

        /**
         * Get the list of an array of notification events array to notify user
         * @return array It returns an array of events when an user should be notified
         * @since 3.1.0
         */
        public function events_to_notify_user()
        {
            $events = array_merge($this->default_notifiable_events(), $this->only_user_notifiable_events());
            return apply_filters('atbdp_events_to_notify_user', $events);
        }

        /**
         * Get the default events to notify the user.
         * @return array It returns an array of default events when an user should be notified.
         * @since 3.1.0
         */
        public function default_events_to_notify_user()
        {
            return apply_filters('atbdp_default_events_to_notify_user', array(
                'order_created',
                'listing_submitted',
                'payment_received',
                'listing_published',
                'listing_to_expire',
                'listing_expired',
                'remind_to_renew',
                'listing_renewed',
                'order_completed',
                'listing_edited',
                'listing_deleted',
                'listing_contact_form',
            ));
        }

        /**
         * Get an array of events to notify only users
         * @return array it returns an array of events
         * @since 3.1.0
         */
        private function only_user_notifiable_events()
        {
            return apply_filters('atbdp_only_user_notifiable_events', array(
                array(
                    'value' => 'listing_to_expire',
                    'label' => __('Listing nearly Expired', 'directorist'),
                ),
                array(
                    'value' => 'listing_expired',
                    'label' => __('Listing Expired', 'directorist'),
                ),
                array(
                    'value' => 'remind_to_renew',
                    'label' => __('Remind to renew', 'directorist'),
                ),
            ));
        }

        /**
         * Get the default events to notify the admin.
         * @return array It returns an array of default events when an admin should be notified.
         * @since 3.1.0
         */
        public function default_events_to_notify_admin()
        {
            return apply_filters('atbdp_default_events_to_notify_admin', array(
                'order_created',
                'order_completed',
                'listing_submitted',
                'payment_received',
                'listing_published',
                'listing_deleted',
                'listing_contact_form',
                'listing_review'
            ));
        }
    }
}