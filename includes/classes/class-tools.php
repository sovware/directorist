<?php
/**
 *
 * Handles directorist Tools Page
 *
 * @author AazzTech
 */

 if ( ! class_exists( 'ATBDP_Tools' ) ) :
    class ATBDP_Tools
    {
        /**
         * The current delimiter for the file being read.
         *
         * @var string
         */
        protected $postilion = 0;
        public $importable_fields = [];
        private $default_directory;

        public function __construct()
        {
			// Prevent frontend executions.
			if ( ! is_admin() ) {
				return;
			}

            add_action('admin_menu', array($this, 'add_tools_submenu'), 10);
            add_action('admin_init', array($this, 'atbdp_csv_import_controller'));
            add_action( 'init', [$this, 'prepare_data'] );
            add_action('wp_ajax_atbdp_import_listing', array($this, 'atbdp_import_listing'));
            add_action('wp_ajax_directorist_listing_type_form_fields', array($this, 'directorist_listing_type_form_fields'));
        }


        public function directorist_listing_type_form_fields() {

            if ( ! directorist_verify_nonce() ) {
                wp_send_json( array(
					'error' => esc_html__( 'Invalid nonce!', 'directorist' ),
				) );
            }

            $term_id = ! empty( $_POST['directory_type'] ) ? sanitize_text_field( wp_unslash( $_POST['directory_type'] ) ) : '';
            $file    = ! empty( $_POST['file_id'] ) ? get_attached_file( directorist_clean( wp_unslash( $_POST['file_id'] ) ) ) : '';

            if( ! $file ) {
                wp_send_json( array(
					'error' => esc_html__( 'Invalid file!', 'directorist' ),
				) );
            }

            $delimiter = ! empty( $_POST['delimiter'] ) ? directorist_clean( wp_unslash( $_POST['delimiter'] ) ) : '';
            $this->importable_fields = [];
            $this->setup_fields( $term_id );

            ob_start();

            ATBDP()->load_template( 'admin-templates/import-export/data-table', array( 'data' => csv_get_data( $file, false, $delimiter ), 'fields' => $this->get_importable_fields(), 'csv_file' => $file ) );

            $response = ob_get_clean();

            wp_send_json( $response );
        }

        public function atbdp_import_listing() {

			if ( ! current_user_can( 'import' ) ) {
                wp_send_json( array(
					'error' => esc_html__( 'Invalid request!', 'directorist' ),
				) );
			}

            if ( ! directorist_verify_nonce() ) {
                wp_send_json( array(
					'error' => esc_html__( 'Invalid nonce!', 'directorist' ),
				) );
            }

            $data                  = array();
            $preview_image         = isset( $_POST['listing_img'] ) ? directorist_clean( wp_unslash( $_POST['listing_img'] ) ) : '';
            $default_directory     =  directorist_default_directory();
            $directory_type        = isset( $_POST['directory_type'] ) ? absint( $_POST['directory_type'] ) : 0;
            $directory_type        = ( empty( $directory_type ) ) ? $default_directory : $directory_type;
            $title                 = isset( $_POST['listing_title'] ) ? directorist_clean( wp_unslash( $_POST['listing_title'] ) ) : '';
            $new_listing_status    = get_term_meta( $directory_type, 'new_listing_status', 'pending');
            $supported_post_status = array_keys( get_post_statuses() );
            $listing_status        = isset( $_POST['listing_status'] ) ? directorist_clean( wp_unslash( $_POST['listing_status'] ) ) : '';
            $delimiter             = isset( $_POST['delimiter'] ) ? directorist_clean( wp_unslash( $_POST['delimiter'] ) ) : '';
            $description           = isset( $_POST['listing_content'] ) ? directorist_clean( wp_unslash( $_POST['listing_content'] ) ) : '';
            $position              = isset( $_POST['position'] ) ? directorist_clean( wp_unslash( $_POST['position'] ) ) : 0;
            $position              = ( is_numeric( $position ) ) ? ( int ) $position : 0;
            $metas                 = isset( $_POST['meta'] ) ? directorist_clean( wp_unslash( $_POST['meta'] ) ) : array();
            $tax_inputs            = isset( $_POST['tax_input'] ) ? directorist_clean( wp_unslash( $_POST['tax_input'] ) ) : array();
            $limit                 = apply_filters( 'atbdp_listing_import_limit_per_cycle', 10 );
            $all_posts             = isset( $_POST['csv_file'] ) ? csv_get_data( directorist_clean( wp_unslash( $_POST['csv_file'] ) ), true, $delimiter ) : [];
            $total_length          = ( isset( $_POST['total_post'] ) && is_numeric( $_POST['total_post'] ) ) ? directorist_clean( wp_unslash( $_POST['total_post'] ) ) : count( $all_posts );
            $limit                 = apply_filters('atbdp_listing_import_limit_per_cycle', ( $total_length > 100 ) ? 20 : ( ( $total_length < 35 ) ? 2 : 5 ) );
            $posts                 = ( ! empty( $all_posts ) ) ? array_slice( $all_posts, $position ) : [];
            $posts                 = apply_filters( 'directorist_listings_importing_posts', $posts, $position, $limit, $_POST );
			$publish_date          = isset( $metas['publish_date'] ) ? directorist_clean( $metas['publish_date'] ) : '';

            if ( empty( $total_length ) ) {
                $data['error']     = __('No data found', 'directorist');
                $data['_POST']     = directorist_clean( $_POST );
                $data['file']      = directorist_clean( wp_unslash( $_POST['csv_file'] ) );
                $data['all_posts'] = $all_posts;

                wp_send_json( $data );
            }

            do_action( 'directorist_before_listings_import' );

            // Counters
            $imported = 0;
            $failed   = 0;
            $count    = 0;

            foreach ( $posts as $index => $post ) {

                    if ( $count === $limit ) {
                        break;
                    }

                    /**
                     * Filters whether the listing import process should start.
                     *
                     * This filter allows modifying the decision to start/skip the listing import process.
                     *
                     * @since 8.0
                     *
                     * @param bool $should_start    Indicates whether the import process should start. Default is true.
                     * @param array $post           The current post data being imported.
                     */
                    if( ! apply_filters( 'directorist_import_listing_starts', true, $post ) ){
                        $failed++;
                        $count++;
                        continue;
                    }

                    // start importing listings
                    $post_status = ( isset( $post[ $listing_status ] ) ) ? $post[ $listing_status ] : '';
                    $post_status = ( in_array( $post_status, $supported_post_status, true ) ) ? $post_status : $new_listing_status;

                    $args = array(
                        'post_title'   => isset( $post[ $title ] ) ? self::unescape_data( html_entity_decode( $post[ $title ] ) ) : '',
                        'post_content' => isset( $post[ $description ] ) ? self::unescape_data( html_entity_decode( $post[ $description ] ) ) : '',
                        'post_type'    => ATBDP_POST_TYPE,
                        'post_status'  => $post_status,
                    );

                    // Post Date
                    $post_date = ! empty( $post[ $publish_date ] ) ? directorist_clean( $post[ $publish_date ] ) : '';
                    $post_date = apply_filters( 'directorist_importing_listings_post_date', $post_date, $post, $args, $index );
					$post_date = strtotime( $post_date );
					if ( $post_date ) {
						$args['post_date'] = date( 'Y-m-d H:i:s', $post_date );
					}

					$listing_id  = ! empty( $post['id'] ) ? absint( $post['id'] ) : 0;
					if ( get_post( $listing_id ) && get_post_type( $listing_id ) === ATBDP_POST_TYPE ) {
						$args['ID'] = $listing_id;
						$post_id = wp_update_post( $args );
					} else {
						$post_id = wp_insert_post( $args );
					}

                    if ( is_wp_error( $post_id ) ) {
                        $failed++;
                        continue;
                    }

                    $imported++;

                    if ( $tax_inputs ) {
                        foreach ( $tax_inputs as $taxonomy => $value ) {

                            if( ! $value ) {
                                continue;
                            }

                            $terms = isset( $post[ $value ] ) && ! empty( $post[ $value ] ) ? explode( ',', $post[ $value ] ) : array();
                            if( ! $terms ) {
                                continue;
                            }

                            if ( 'category' === $taxonomy ) {
                                $taxonomy = ATBDP_CATEGORY;
                            } elseif ( 'location' === $taxonomy ) {
                                $taxonomy = ATBDP_LOCATION;
                            } else {
                                $taxonomy = ATBDP_TAGS;
                            }

                            $term_ids = array();
                            $multiple = $terms > 0;

                            foreach ( $terms as $term ) {
								$term_id = $this->get_or_create_term_id( $term, $taxonomy );

								if ( empty( $term_id ) ) {
									continue;
								}

								if ( $taxonomy === ATBDP_CATEGORY || $taxonomy === ATBDP_LOCATION ) {
									directorist_update_term_directory( $term_id, array( $directory_type ), true );
								}

                                $term_ids[] = $term_id;
                            }

                            wp_set_object_terms( $post_id, $term_ids, $taxonomy, $multiple );
                        }
                    }

                    foreach ( $metas as $index => $value ) {
                        $meta_value = $post[ $value ] ? self::unescape_data( $post[ $value ] ) : '';
                        $meta_value = $this->maybe_unserialize_csv_string( $meta_value );

                        if ( $meta_value ) {
                            update_post_meta( $post_id, '_' . $index, $meta_value );
                        }
                    }

                    $exp_dt = calc_listing_expiry_date( '', '', $directory_type );
                    update_post_meta( $post_id, '_expiry_date', $exp_dt );
                    update_post_meta( $post_id, '_featured', 0 );

					// TODO: Status has been migrated, remove related code.
                    update_post_meta( $post_id, '_listing_status', 'post_status' );

                    if ( ! empty( $post['directory_type'] ) ) {
                        $directory_type_slug = $post['directory_type'];
                        $directory_type_term = get_term_by( 'slug', $directory_type_slug, ATBDP_DIRECTORY_TYPE );
                        $directory_type = ( ! empty( $directory_type_term ) ) ? $directory_type_term->term_id : $directory_type;
                    }

                    update_post_meta( $post_id, '_directory_type', $directory_type );
                    wp_set_object_terms( $post_id, ( int ) $directory_type, ATBDP_DIRECTORY_TYPE );

                    $preview_url = isset($post[$preview_image]) ? $post[$preview_image] : '';
                    $preview_url = ( ! empty( $preview_url ) ) ? explode( ',', $preview_url ) : '';

                    if ( ! empty( $preview_url ) ) {
                        $attachment_ids = [];
                        foreach ( $preview_url as $_url_index => $_url ) {
                            $_url = trim( $_url );
                            $attachment_id = self::atbdp_insert_attachment_from_url($_url, $post_id);
                            if ( $_url_index == 0 ) {
                                update_post_meta($post_id, '_listing_prv_img', $attachment_id);
                            } else {
                                $attachment_ids[] = $attachment_id;
                            }
                        }
                        update_post_meta($post_id, '_listing_img', $attachment_ids );
                    }

                    /**
                     * Add Listing Meta - to track which listings are imported by CSV
                     */
                    update_post_meta( $post_id, '_directorist_imported_by_csv', 'yes' );

                    /**
                     * Fire this event once a listing is successfully imported from CSV.
                     *
                     * @since 7.2.0
                     *
                     * @param int $post_id Listing id.
                     * @param array $post  Listing data.
                     */
                    do_action( 'directorist_listing_imported', $post_id, $post );

                    $count++;
            }

            $data['next_position'] = ( int ) $position + ( int ) $count;
            $data['percentage']    = absint( min( round( ( ( $data['next_position'] ) / $total_length ) * 100 ), 100 ) );
            $data['url']           = esc_url( admin_url( 'edit.php?post_type=at_biz_dir&page=tools&step=3' ) );
            $data['total']         = $total_length;
            $data['imported']      = $imported;
            $data['failed']        = $failed;

            do_action( 'directorist_after_listings_import' );

            wp_send_json( $data );
        }

		/**
		 * @param string $term
		 * @param string $taxonomy
		 * @return int|null Term ID
		 */
		public function get_or_create_term_id( $term, $taxonomy ) {
			$term_data = term_exists( $term, $taxonomy );

			if ( is_array( $term_data ) ) {
				return (int) $term_data['term_id'];
			}

			$term_data = wp_insert_term( $term, $taxonomy );

			if ( ! is_wp_error( $term_data ) ) {
				return (int) $term_data['term_id'];
			}

			return null;
		}

        // maybe_unserialize_csv_string
        public function maybe_unserialize_csv_string( $data ) {
            if ( ! is_string( $data ) ) {
				return $data;
			}

            $_data = str_replace( "'", '"', $data );
            $_data = maybe_unserialize( maybe_unserialize( $_data ) );

            if ( ! empty( $_data ) ) {
                return $_data;
            }

            return $data;
        }

        public static function atbdp_legacy_insert_attachment_from_url( $file_url, $post_id ) {

            if (!filter_var($file_url, FILTER_VALIDATE_URL)) {
                return false;
            }
            $contents = @file_get_contents($file_url);

            if ($contents === false) {
                return false;
            }

            if( ! wp_check_filetype( $file_url )['ext'] ) {

                $headers = array(
                    'Accept'     => 'application/json',
                );

                $config = array(
                    'method'      => 'GET',
                    'timeout'     => 30,
                    'redirection' => 5,
                    'httpversion' => '1.0',
                    'headers'     => $headers,
                    'cookies'     => array(),
                );

                $upload = array();

                try {
                    $response = wp_remote_get( $file_url, $config );

                    if ( ! is_wp_error( $response ) ) {
                        $type = wp_remote_retrieve_header( $response, 'content-type' );
                        $extension = preg_replace("/\w+\//", '', $type );
                        $upload = wp_upload_bits(basename( $file_url . '.'. $extension ), '', wp_remote_retrieve_body($response));

                    }
                } catch ( Exception $e ) {

                }
            }else{
                $upload = wp_upload_bits(basename($file_url), null, $contents);
            }

            if (isset($upload['error']) && $upload['error']) {
                return false;
            }

            $type = '';
            if (!empty($upload['type'])) {
                $type = $upload['type'];
            } else {
                $mime = wp_check_filetype($upload['file']);
                if ($mime) {
                    $type = $mime['type'];
                }
            }
            $attachment = array( 'post_title' => basename($upload['file']), 'post_content' => '', 'post_type' => 'attachment', 'post_mime_type' => $type, 'guid' => $upload['url'] );
            $id = wp_insert_attachment( $attachment, $upload['file'], $post_id );
            wp_update_attachment_metadata( $id, wp_generate_attachment_metadata($id, $upload['file']) );
            return $id;

        }


        public static function atbdp_updated_insert_attachment_from_url( $image_url, $post_id ) {

            if (!filter_var($image_url, FILTER_VALIDATE_URL)) {
                return false;
            }

            $upload = directorist_rest_upload_image_from_url( esc_url_raw( $image_url ) );

            if ( is_wp_error( $upload ) ) {
                return $upload;
            }

            $image_id = directorist_rest_set_uploaded_image_as_attachment( $upload, $post_id );

            return $image_id;

        }

        public static function atbdp_insert_attachment_from_url( $image_url, $post_id ) {

            $legacy = apply_filters( 'directorist_legacy_attachment_importer', false, $image_url, $post_id );

            if( ! $legacy ) {
                return self::atbdp_updated_insert_attachment_from_url( $image_url, $post_id );
            }

            return self::atbdp_legacy_insert_attachment_from_url( $image_url, $post_id );
        }

		public static function on_wp_handle_upload_prefilter( $file ) {
			$allowed_mimes = array(
				'csv' => 'text/csv',
				'txt' => 'text/plain',
			);

			if ( empty( $file['size'] ) || empty( $file['type'] ) ) {
				$file['error'] = __( 'Please select a valid CSV or TXT file.', 'directorist' );
			} else if ( ! in_array( $file['type'], $allowed_mimes, true ) ) {
				$file['error'] = __( 'Sorry, only CSV and TXT files are allowed.', 'directorist' );
			}

			return $file;
		}

        public function atbdp_csv_import_controller() {
            if ( ! isset( $_POST[ 'atbdp_save_csv_step' ] ) ) {
                return;
            }

            check_admin_referer( 'directorist-csv-importer' );

			add_filter( 'wp_handle_upload_prefilter', array( __CLASS__, 'on_wp_handle_upload_prefilter' ) );

            $file = wp_import_handle_upload();

			remove_filter( 'wp_handle_upload_prefilter', array( __CLASS__, 'on_wp_handle_upload_prefilter' ) );

			if ( isset( $file['error'] ) ) {
				wp_die(
					wp_kses_post( $file['error'] ),
					'Directorist CSV Import Error!',
					array(
						'back_link' => true,
					) );
			}

            $base_url = admin_url( 'edit.php' );
            $params   = apply_filters( 'directorist_listings_import_form_submit_redirect_params', [
                'post_type'       => ATBDP_POST_TYPE,
                'page'            => 'tools',
                'file_id'          => $file['id'],
                'delimiter'       => isset( $_REQUEST['delimiter'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['delimiter'] ) ) : ',',
                'update_existing' => isset( $_REQUEST['update_existing'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['update_existing'] ) ) : false,
                'step'            => 2,
            ] );

            $url = add_query_arg( $params, $base_url );
            $url = apply_filters( 'directorist_listings_import_form_submit_redirect_url', $url, $base_url, $params );

            // redirect to step two || data mapping
            wp_safe_redirect( $url );
        }


        public function prepare_data(){
            $this->default_directory = default_directory_type();
            $this->setup_fields();
        }


        public function setup_fields( $directory = '' ) {
            $directory = $directory ? $directory : $this->default_directory;
            $fields    = directorist_get_form_fields_by_directory_type( 'id', $directory );

            $this->importable_fields[ 'publish_date' ]   = esc_html__( 'Publish Date', 'directorist' );
            $this->importable_fields[ 'listing_status' ] = esc_html__( 'Listing Status', 'directorist' );

            if ( empty( $fields ) || ! is_array( $fields ) ) {
                return;
            }

            foreach( $fields as $field ) {
                $field_key  = !empty( $field['field_key'] ) ? $field['field_key'] : '';
                $label      = !empty( $field['label'] ) ? $field['label'] : '';
                if( 'tax_input[at_biz_dir-location][]'  == $field_key ) {  $field_key = 'location'; }
                if( 'admin_category_select[]'           == $field_key ) {  $field_key = 'category';  }
                if( 'tax_input[at_biz_dir-tags][]'      == $field_key ) { $field_key = 'tag'; }

                if ( isset( $field['widget_name'] ) ) {
                    if( 'pricing' == $field['widget_name'] ) {
                        $this->importable_fields[ 'price' ] = esc_html__( 'Price', 'directorist' );
                        $this->importable_fields[ 'price_range' ] = esc_html__( 'Price Range', 'directorist' );
                        continue;
                        }
                    if( 'map' == $field['widget_name'] ) {
                        $this->importable_fields[ 'manual_lat' ] = esc_html__( 'Map Latitude', 'directorist' );
                        $this->importable_fields[ 'manual_lng' ] = esc_html__( 'Map Longitude', 'directorist' );
                        $this->importable_fields[ 'hide_map' ]   = esc_html__( 'Hide Map', 'directorist' );
                        continue;
                    }
                }

                $this->importable_fields[ $field_key ] = $label;
            }
        }

        /**
         * It adds a submenu for showing all the Tools and details support
         */
        public function add_tools_submenu() {
			add_submenu_page(
				'edit.php?post_type=at_biz_dir',
				__( 'Tools', 'directorist' ),
				__( 'Tools', 'directorist' ),
				'manage_options',
				'tools',
				array( $this, 'render_tools_submenu_page' )
			);

			// Remove to remove the menu item.
			remove_submenu_page( 'edit.php?post_type=at_biz_dir', 'tools' );
        }

        public function get_data_table( $file_path, $delimiter = ',' ){
            $csv_data = csv_get_data( $file_path, false, $delimiter );
            $data = [
                'data'     => $csv_data,
                'csv_file' => $file_path,
                'fields'   => $this->get_importable_fields(),
            ];

            ATBDP()->load_template('admin-templates/import-export/data-table', $data );
        }

        public function render_tools_submenu_page() {

            ATBDP()->load_template( 'admin-templates/import-export/import-export', [ 'controller' => $this ] );

        }

        /**
         * Importer Header Template
         *
         * @param bool $return
         * @return string $template
         */
        public function importer_header_template( $return = false ) {
            $template_data = [];

            $template_data['controller']    = $this;
            $template_data['download_link'] = esc_url( ATBDP_URL .'views/admin-templates/import-export/data/dummy.csv' );
            $template_data['nav_menu']      = $this->get_header_nav_menu();

            $template_path = 'admin-templates/import-export/header-templates/header';
            ATBDP()->load_template( $template_path, $template_data );

        }

        /**
         * Importer header nav menu item template
         *
         * @param bool $return
         * @return string $template
         */
        public function importer_header_nav_menu_item_template( $template_data = [], $return = false ) {

            $template_data['controller'] = $this;
            ATBDP()->load_template( 'admin-templates/import-export/header-templates/nav-item', $template_data );

        }

        /**
         * Get Header Nav Menu
         *
         * @return array
         */
        public function get_header_nav_menu() {
            $step = isset( $_GET['step'] ) ? sanitize_key( wp_unslash( $_GET['step'] ) ) : '';
            $nav_menu = [];

            // Item - 1
            $nav_item                   = [];
            $nav_item['nav_item_class'] = ! $step ? esc_attr('active') : ( $step > 1 ? esc_attr('done') : '');
            $nav_item['label']          = esc_html__('Upload CSV File', 'directorist');
            $nav_menu[]                 = $nav_item;

            // Item - 2
            $nav_item                   = [];
            $class                      = ( '2' == $step ) ? esc_attr('active') : ( $step > 2 ? esc_attr('done') : '' );
            $class                      = 'atbdp-mapping-step ' . $class;
            $nav_item['nav_item_class'] = trim( $class );
            $nav_item['label']          = esc_html__('Column Mapping', 'directorist');
            $nav_menu[]                 = $nav_item;

            // Item - 3
            $nav_item                   = [];
            $class                      = ( $step == 3 ) ? esc_attr('done') : '';
            $class                      = 'atbdp-progress-step ' . $class;
            $nav_item['nav_item_class'] = trim( $class );
            $nav_item['label']          = esc_html__('Import', 'directorist');
            $nav_menu[]                 = $nav_item;

            // Item - 4
            $nav_item                   = [];
            $nav_item['nav_item_class'] = ( '3' == $step ) ? esc_attr('active done') : '';
            $nav_item['label']          = esc_html__('Done', 'directorist');
            $nav_menu[]                 = $nav_item;

            $nav_menu = apply_filters( 'directorist_listings_importer_header_nav_menu', $nav_menu, $step );

            return $nav_menu;
        }

        /**
         * Importer Body Template
         *
         * @param bool $return
         * @return string $template
         */
        public function importer_body_template( $return = false ) {
            $step = ( isset( $_REQUEST['step'] ) ) ? directorist_clean( wp_unslash( $_REQUEST['step'] ) ) : 1;
            $step = ( ! empty( $step ) && is_numeric( $step ) ) ? ( int ) $step : 1;
            $template_base_path = 'admin-templates/import-export/body-templates';
            $template_paths = [
                1 => "{$template_base_path}/step-one",
                2 => "{$template_base_path}/step-two",
                3 => "{$template_base_path}/step-done",
            ];

            $template_path = ( isset( $template_paths[ $step ] ) ) ? $template_paths[ $step ] : $template_paths[ 1 ];

            $template_data = [
                'controller' => $this,
                'step'       => $step,
            ];

            ATBDP()->load_template( $template_path, $template_data );

        }

		public function get_importable_fields() {
			return apply_filters( 'directorist_importable_fields', $this->importable_fields );
		}

		/**
		 * The exporter prepends a ' to escape fields that start with =, +, - or @.
		 * Remove the prepended ' character preceding those characters.
		 *
		 * @since 7.7.1
		 * @param  string $value A string that may or may not have been escaped with '.
		 * @return string
		 */
		protected static function unescape_data( $value ) {
			$active_content_triggers = array( "'=", "'+", "'-", "'@" );

			if ( in_array( mb_substr( $value, 0, 2 ), $active_content_triggers, true ) ) {
				$value = mb_substr( $value, 1 );
			}

			return $value;
		}
    }

endif;