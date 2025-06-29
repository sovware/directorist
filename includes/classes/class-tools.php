<?php
/**
 *
 * Handles directorist Tools Page
 *
 * @author AazzTech
 */

use Directorist\Listings_CSV_Importer as Importer;

 if ( ! class_exists( 'ATBDP_Tools' ) ) :
    class ATBDP_Tools {
		/**
		 * Import starting time.
		 * @var int
		 */
		protected $start_time = 0;

		/**
		 * File position after the last read.
		 *
		 * @var int
		 */
		protected $file_position = 0;

		/**
		 * Valid fields map.
		 *
		 * @var array
		 */
        public $importable_fields = [];

		/**
		 * Default directory id.
		 *
		 * @var int
		 */
        private $default_directory;

		/**
		 * Listings_CSV_Importer instance.
		 *
		 * @var \Directorist\Listings_CSV_Importer||null
		 */
		private $importer = null;

        public function __construct() {
			// Prevent frontend executions.
			if ( ! is_admin() ) {
				return;
			}

            add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
            add_action( 'admin_init', array( $this, 'handle_csv_upload' ) );
            add_action( 'wp_ajax_directorist_import_listings', array( $this, 'handle_import_listings' ) );
            add_action( 'wp_ajax_directorist_update_csv_columns_to_listing_fields_table', array( $this, 'update_csv_columns_to_listing_fields_table' ) );

			add_action( 'admin_head', function() {
				echo '<style>#toplevel_page_tools, [href="edit.php?post_type=at_biz_dir&page=tools"] { display: none !important; }</style>';
			});

			include_once( __DIR__ . '/class-listings-csv-importer.php' );
        }

		/**
         * It adds a submenu for showing all the Tools and details support
         */
        public function add_admin_menu() {
			add_submenu_page(
				'edit.php?post_type=at_biz_dir',
				__( 'Tools', 'directorist' ),
				__( 'Tools', 'directorist' ),
				'manage_options',
				'tools',
				array( $this, 'render_page' )
			);
        }

		public function render_page() {
            ATBDP()->load_template(
				'admin-templates/import-export/import-export',
				[
					'controller' => $this
					]
			);
        }

		/**
		 * Check csv mime type. Only allow csv and text files.
		 *
		 * @param mixed $file
		 */
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

        /**
		 * Upload CSV file and redirect to step two.
		 *
		 * @return false|void
		 */
		public function handle_csv_upload() {
            if ( ! isset( $_POST['directorist_upload_csv'] ) ) {
                return false;
            }

			check_admin_referer( 'directorist_importer_upload_csv' );

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

            $args = [
                'post_type' => ATBDP_POST_TYPE,
                'page'      => 'tools',
                'file_id'   => $file['id'],
                'delimiter' => isset( $_POST['delimiter'] ) ? sanitize_text_field( wp_unslash( $_POST['delimiter'] ) ) : ',',
                'step'      => 2,
            ];

            wp_safe_redirect( add_query_arg( $args, admin_url( 'edit.php' ) ) );
        }

        public function update_csv_columns_to_listing_fields_table() {
            if ( ! directorist_verify_nonce() ) {
                wp_send_json( array(
					'error' => esc_html__( 'Invalid request.', 'directorist' ),
				) );
            }

			$directory_id = isset( $_POST['directory_type'] ) ? absint( $_POST['directory_type'] ) : 0;
			$file_id      = isset( $_POST['file_id'] ) ? absint( $_POST['file_id'] ) : 0;
			$delimiter    = ! empty( $_POST['delimiter'] ) ? directorist_clean( wp_unslash( $_POST['delimiter'] ) ) : ',';

			if ( ! directorist_is_directory( $directory_id ) ) {
				wp_send_json( array(
					'error' => esc_html__( 'Invalid request.', 'directorist' ),
				) );
			}

			$csv_file = $this->validate_csv_file( get_attached_file( $file_id ) );
            if ( is_wp_error( $csv_file ) ) {
                wp_send_json( array(
					'error' => $csv_file->get_error_message(),
				) );
            }

			try {
				$importer = $this->get_importer( $csv_file, $delimiter );
			} catch ( Exception $e ) {
				wp_send_json( array(
					'error' => $e->getMessage(),
				) );
			}

            $this->importable_fields = [];
            $this->setup_importable_fields( $directory_id );

            ob_start();

            ATBDP()->load_template(
				'admin-templates/import-export/data-table',
				array(
					'columns' => $importer->get_header(),
					'fields'  => $this->get_importable_fields(),
					)
				);

            $response = ob_get_clean();

            wp_send_json( $response );
        }

        public function handle_import_listings() {
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

			$directory_id = empty( $_POST['directory_type'] ) ? 0 : absint( $_POST['directory_type'] );
			if ( ! directorist_is_directory( $directory_id ) ) {
				$directory_id = directorist_get_default_directory();
			}

			$csv_file  = ! empty( $_POST['csv_file'] ) ? absint( $_POST['csv_file'] ) : 0;
			$delimiter = ! empty( $_POST['delimiter'] ) ? directorist_clean( wp_unslash( $_POST['delimiter'] ) ) : ',';
			$csv_file  = get_attached_file( $csv_file );

			$csv_file = $this->validate_csv_file( $csv_file );
			if ( is_wp_error( $csv_file ) ) {
				wp_send_json( array(
					'error' => $csv_file->get_error_message(),
				) );
			}

			try {
				$importer = $this->get_importer( $csv_file, $delimiter );
			} catch ( Exception $e ) {
				wp_send_json( array(
					'error' => $e->getMessage(),
				) );
			}

			$supported_post_status = array_keys( get_post_statuses() );
			$listing_create_status = directorist_get_listing_create_status( $directory_id );
			$offset                = ! empty( $_POST['_offset'] ) ? absint( $_POST['_offset'] ) : 0;
			$position              = ! empty( $_POST['_position'] ) ? absint( $_POST['_position'] ) : 0;
			$preview_image         = ! empty( $_POST['listing_img'] ) ? directorist_clean( wp_unslash( $_POST['listing_img'] ) ) : '';
			$title                 = ! empty( $_POST['listing_title'] ) ? directorist_clean( wp_unslash( $_POST['listing_title'] ) ) : '';
			$listing_status        = ! empty( $_POST['listing_status'] ) ? directorist_clean( wp_unslash( $_POST['listing_status'] ) ) : '';
			$description           = ! empty( $_POST['listing_content'] ) ? directorist_clean( wp_unslash( $_POST['listing_content'] ) ) : '';
			$metas                 = ! empty( $_POST['meta'] ) ? directorist_clean( wp_unslash( $_POST['meta'] ) ) : array();
			$tax_inputs            = ! empty( $_POST['tax_input'] ) ? directorist_clean( wp_unslash( $_POST['tax_input'] ) ) : array();
			$publish_date          = ! empty( $metas['publish_date'] ) ? directorist_clean( $metas['publish_date'] ) : '';

			$total_items = $importer->get_total_items();

            if ( $total_items < 1 ) {
                wp_send_json( array(
					'error'=> __( 'No data found', 'directorist' )
				) );
            }

			$this->start_time  = time();
			$batch_size        = apply_filters( 'atbdp_listing_import_limit_per_cycle', 50 );
			$batch_processed   = 1;
			$processed_logs    = [];
			$terms_cache       = [];
			$attachments_cache = [];

			$header        = array_keys( $importer->get_header() );
			$columns_count = count( $header );
			$file_object   = $importer->get_file_object();

			// Ignore header
			if ( $offset === 0 ) {
				$file_object->fgetcsv();
				$offset = $file_object->ftell();
			}

			$file_object->fseek( $offset, SEEK_SET );

			while ( ! $file_object->eof() ) {
				$position++;

				$row = $file_object->fgetcsv();

				if ( empty( array_filter( $row ) ) ) {
					$processed_logs[] = sprintf( '❌ [%d]: Empty row.', $position );
					continue;
				}

				if ( $columns_count !== count( $row ) ) {
					$processed_logs[] = sprintf( '❌ [%d]: Header and row mismatch.', $position );
					continue;
				}

				$post = array_combine( $header, $row );

				// /**
				//  * Filters whether the listing import process should start.
				//  *
				//  * This filter allows modifying the decision to start/skip the listing import process.
				//  *
				//  * @since 8.0
				//  *
				//  * @param bool $should_start    Indicates whether the import process should start. Default is true.
				//  * @param array $post           The current post data being imported.
				//  */
				// // if( ! apply_filters( 'directorist_import_listing_starts', true, $post ) ){
				// //     $failed++;
				// //     $count++;
				// //     continue;
				// // }

				// start importing listings
				$listing_status = $post[ $listing_status ] ?? '';
				if ( ! in_array( $listing_status, $supported_post_status, true ) ) {
					$listing_status = $listing_create_status;
				}

				$args = array(
					'post_title'   => static::sanitize_text( $post[ $title ] ?? '' ),
					'post_content' => static::sanitize_textarea( $post[ $description ] ?? '' ),
					'post_type'    => ATBDP_POST_TYPE,
					'post_status'  => $listing_status
				);

				// Post Date
				$post_date = ! empty( $post[ $publish_date ] ) ? directorist_clean( $post[ $publish_date ] ) : '';
				$post_date = apply_filters( 'directorist_importing_listings_post_date', $post_date, $post, $args );
				$post_date = strtotime( $post_date );
				if ( $post_date ) {
					$args['post_date'] = date( 'Y-m-d H:i:s', $post_date );
				}

				// Create listing
				$post_id = wp_insert_post( $args, true );
				if ( is_wp_error( $post_id ) ) {
					$processed_logs[] = sprintf(
						'❌ [%d]: %s (%s)',
						$position,
						$args['post_title'],
						$post_id->get_error_message()
					);
					continue;
				}

				$processed_logs[] = sprintf( '✅ [%d->%d]: %s', $position, $post_id, $args['post_title'] );

				// Save listing directory type.
				update_post_meta( $post_id, '_directory_type', $directory_id );
				wp_set_object_terms( $post_id,  $directory_id, ATBDP_DIRECTORY_TYPE );

				// Process taxonomies
				if ( $tax_inputs ) {
					foreach ( $tax_inputs as $taxonomy => $value ) {
						if ( ! $value ) {
							continue;
						}

						$terms = static::sanitize_text( $post[ $value ] ?? '' );
						$terms = empty( $terms ) ? array() : explode( ',', $post[ $value ] );
						if ( ! $terms ) {
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
						$multiple = count( $terms ) > 0;

						foreach ( $terms as $term ) {
							$term = trim( $term );

							if ( isset( $terms_cache[ $term ] ) ) {
								$term_id = $terms_cache[ $term ];
							} else {
								$term_id = $this->maybe_create_term( $term, $taxonomy );
							}

							if ( empty( $term_id ) ) {
								continue;
							}

							if ( $taxonomy === ATBDP_CATEGORY || $taxonomy === ATBDP_LOCATION ) {
								directorist_update_term_directory( $term_id, array( $directory_id ), true );
							}

							$term_ids[] = $term_id;
							$terms_cache[ $term ] = $term_id;
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

				$expire_date = calc_listing_expiry_date( '', '', $directory_id );
				update_post_meta( $post_id, '_expiry_date', $expire_date );
				update_post_meta( $post_id, '_featured', 0 );

				// TODO: Status has been migrated, remove related code.
				update_post_meta( $post_id, '_listing_status', 'post_status' );

				$image_urls = $post[ $preview_image ] ?? '';
				$image_urls = empty( $image_urls ) ? [] : explode( ',', $image_urls );

				if ( $image_urls ) {
				    $attachment_ids = [];

				    foreach ( $image_urls as $image_url ) {
				        $image_url = trim( $image_url );

				        if ( isset( $attachments_cache[ $image_url ] ) ) {
				            $attachment_id = $attachments_cache[ $image_url ];
				        } else {
							$attachment_id = self::atbdp_insert_attachment_from_url( $image_url, $post_id );
						}

						if ( is_wp_error( $attachment_id ) || ! $attachment_id ) {
							continue;
						}

						$attachment_ids[]                = $attachment_id;
						$attachments_cache[ $image_url ] = $attachment_id;
				    }

					if ( $attachment_ids ) {
						update_post_meta( $post_id, '_listing_prv_img', $attachment_ids[0] );
						array_shift( $attachment_ids );
					}

					if ( $attachment_ids ) {
						update_post_meta( $post_id, '_listing_img', $attachment_ids );
						/**
						 * Add Listing Meta - to track which listings are imported by CSV
						 */
						update_post_meta( $post_id, '_directorist_imported_by_csv', 'yes' );
					}
				}

				/**
				 * Fire this event once a listing is successfully imported from CSV.
				 *
				 * @since 7.2.0
				 *
				 * @param int $post_id Listing id.
				 * @param array $post  Listing data.
				 */
				do_action( 'directorist_listing_imported', $post_id, $post );

				if ( $this->time_exceeded() || $this->memory_exceeded() || $batch_size === $batch_processed ) {
					break;
				}

				$batch_processed++;
			}

			// Defer image resizing
			// if ( $attachments_cache ) {
			// 	$deferred_resizable_images = [];
			// 	foreach ( $attachments_cache as $attachment_id ) {
			// 		$deferred_resizable_images[ $attachment_id ] = get_attached_file( $attachment_id );
			// 	}
			// 	directorist_background_image_process( $deferred_resizable_images );
			// }

			$data['offset']       = $file_object->ftell();
			$data['done']         = $file_object->eof();
			$data['position']     = $position;
			$data['total']        = $total_items;
			$data['logs']         = $processed_logs;
			$data['redirect_url'] = esc_url( admin_url( 'edit.php?post_type=at_biz_dir&page=tools&step=3' ) );

            wp_send_json( $data );
        }

		/**
		 * Get term id if exists otherwise create and return term id.
		 *
		 * @param string $term
		 * @param string $taxonomy
		 * @return int|null Term ID
		 */
		public function maybe_create_term( $term, $taxonomy ) {
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

			// Ensure the required file is included before calling the function
			if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) {
				require_once ABSPATH . 'wp-admin/includes/image.php';
			}

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

        public function setup_importable_fields( $directory_id = 0 ) {
            $directory_id = $directory_id ? $directory_id : default_directory_type();
            $fields       = directorist_get_form_fields_by_directory_type( 'id', $directory_id );

			if ( empty( $fields ) || ! is_array( $fields ) ) {
                return;
            }

            $this->importable_fields[ 'publish_date' ]   = esc_html__( 'Publish Date', 'directorist' );
            $this->importable_fields[ 'listing_status' ] = esc_html__( 'Listing Status', 'directorist' );

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

        public function render_field_map_table( $file, $delimiter = ',' ){
			$importer = $this->get_importer( $file, $delimiter );

			$this->importable_fields = [];
            $this->setup_importable_fields();

            $data = [
                'columns'  => $importer->get_header(),
                'fields'   => $this->get_importable_fields(),
            ];

			ATBDP()->load_template('admin-templates/import-export/data-table', $data );
        }

        /**
         * Importer Header Template
         *
         * @param bool $return
         * @return void
         */
        public function importer_header_template() {
            ATBDP()->load_template(
				'admin-templates/import-export/header-templates/header',
				[
					'controller'    => $this,
					'download_link' => esc_url( ATBDP_URL .'views/admin-templates/import-export/data/dummy.csv' ),
					'nav_menu'      => $this->get_header_nav_menu()
				]
			);
        }

        /**
         * Importer header nav menu item template
         *
         * @param bool $return
         * @return void
         */
        public function importer_header_nav_menu_item_template( $template_data = [] ) {
            $template_data['controller'] = $this;
            ATBDP()->load_template( 'admin-templates/import-export/header-templates/nav-item', $template_data );
        }

		/**
		 * Get the navigation menu items for the importer header
		 *
		 * @since 7.0.0
		 * @return array Navigation menu items
		 */
		public function get_header_nav_menu() {
			$step = isset( $_GET['step'] ) ? absint( $_GET['step'] ) : 1;

			$nav_menu = array(
				// Upload step
				array(
					'label'          => __( 'Upload CSV File', 'directorist' ),
					'nav_item_class' => $this->get_nav_item_class( $step, 1 ),
				),

				// Mapping step
				array(
					'label'          => __( 'Column Mapping', 'directorist' ),
					'nav_item_class' => trim( 'atbdp-mapping-step ' . $this->get_nav_item_class( $step, 2 ) ),
				),

				// Import step
				array(
					'label'          => esc_html__( 'Import', 'directorist' ),
					'nav_item_class' => trim( 'atbdp-progress-step ' . ( 3 === $step ? 'done' : '' ) ),
				),

				// Done step
				array(
					'label'          => esc_html__( 'Done', 'directorist' ),
					'nav_item_class' => 3 === $step ? 'active done' : '',
				),
			);

			/**
			 * Filter the importer header navigation menu items
			 *
			 * @since 7.0.0
			 * @param array $nav_menu Navigation menu items
			 * @param int $step Current step number
			 */
			return apply_filters( 'directorist_listings_importer_header_nav_menu', $nav_menu, $step );
		}

		/**
		 * Get the CSS class for a navigation item based on the current step
		 *
		 * @since 7.0.0
		 * @param int $current_step Current step number
		 * @param int $item_step Step number for this item
		 * @return string CSS class
		 */
		private function get_nav_item_class( $current_step, $item_step ) {
			if ( $current_step === $item_step ) {
				return 'active';
			}

			return $current_step > $item_step ? 'done' : '';
		}

        /**
         * Importer Body Template
         *
         * @param bool $return
         * @return void
         */
        public function importer_body_template( $return = false ) {
            $step         = isset( $_REQUEST['step'] ) ? absint( $_REQUEST['step'] ) : 1;
            $base_path    = 'admin-templates/import-export/body-templates/';
            $template_map = [
                1 => 'step-one',
                2 => 'step-two',
                3 => 'step-done',
            ];

            $template_name = $template_map[ $step ] ?? $template_map[1];
			$template_name = $base_path . $template_name;

            ATBDP()->load_template( $template_name, [
                'controller' => $this,
                'step'       => $step,
            ] );
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

		public function get_importer( $file, $separator = ',' ) {
			if ( is_null( $this->importer ) ) {
				$this->importer = new Importer( $file, $separator );
			}

			return $this->importer;
		}

		public static function validate_csv_file( $file ) {
			if ( ! $file || ! is_readable( $file ) ) {
				return new WP_Error( 'invalid_csv_file', 'Invalid file path or file does not exists.' );
			}

			if ( ! wp_check_filetype( $file )['ext'] === 'csv' ) {
				return new WP_Error( 'invalid_csv_file', 'The file must be a CSV file.' );
			}

			$mime_type = mime_content_type( $file );
			if ( ! in_array( $mime_type, array( 'text/csv','text/plain', 'application/csv' ), true ) ) {
				return new WP_Error(
					'invalid_csv_file',
					sprintf(
						'Invalid file type. Only text/plain, text/csv, and application/csv are supported, given "%s".',
						$mime_type
					)
				);
			}

			return $file;
		}

		/**
		 * Memory exceeded
		 *
		 * Ensures the batch process never exceeds 90%
		 * of the maximum WordPress memory.
		 *
		 * @return bool
		 */
		protected function memory_exceeded() {
			$memory_limit   = $this->get_memory_limit() * 0.9; // 90% of max memory
			$current_memory = memory_get_usage( true );
			$return         = false;

			if ( $current_memory >= $memory_limit ) {
				$return = true;
			}

			return $return;
		}

		/**
		 * Get memory limit
		 *
		 * @return int
		 */
		protected function get_memory_limit() {
			if ( function_exists( 'ini_get' ) ) {
				$memory_limit = ini_get( 'memory_limit' );
			} else {
				// Sensible default.
				$memory_limit = '128M';
			}

			if ( ! $memory_limit || -1 === intval( $memory_limit ) ) {
				// Unlimited, set to 16GB.
				$memory_limit = '16000M';
			}

			return wp_convert_hr_to_bytes( $memory_limit );
		}

		/**
		 * Time exceeded.
		 *
		 * Ensures the batch never exceeds a sensible time limit.
		 * A timeout limit of 30s is common on shared hosting.
		 *
		 * @return bool
		 */
		protected function time_exceeded() {
			$finish = $this->start_time + apply_filters( 'directorist_listings_importer_default_time_limit', 20 ); // 20 seconds
			$return = false;

			if ( time() >= $finish ) {
				$return = true;
			}

			return $return;
		}

		protected static function sanitize_text( $value ) {
			return static::unescape_data( sanitize_text_field( $value ) );
		}

		protected static function sanitize_textarea( $value ) {
			return static::unescape_data( sanitize_textarea_field( $value ) );
		}
    }
endif;
