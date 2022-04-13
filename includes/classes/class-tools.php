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
         * The path to the current file.
         *
         * @var string
         */
        protected $file = '';

        /**
         * Whether to skip existing products.
         *
         * @var bool
         */
        protected $update_existing = false;

        /**
         * The current delimiter for the file being read.
         *
         * @var string
         */
        protected $delimiter = ',';

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
            $this->file = isset($_GET['csv_file']) ? wp_unslash($_GET['csv_file']) : '';

            if ( empty( $this->file ) && isset($_GET['file'] ) ) {
                $this->file = wp_unslash( $_GET['file'] );
            }

            $this->update_existing = isset($_REQUEST['update_existing']) ? (bool) $_REQUEST['update_existing'] : false;
            $this->delimiter       = !empty($_REQUEST['delimiter']) ? wp_unslash($_REQUEST['delimiter']) : ',';
            add_action('wp_ajax_atbdp_import_listing', array($this, 'atbdp_import_listing'));
            add_action('wp_ajax_directorist_listing_type_form_fields', array($this, 'directorist_listing_type_form_fields'));
        }


        public function directorist_listing_type_form_fields() {
            $term_id = sanitize_text_field( $_POST['directory_type'] );
            $file    = wp_unslash( $_POST['csv_file'] );

            if ( empty( $file ) && isset( $_POST['file'] ) ) {
                $file = wp_unslash( $_POST['file'] );
            }

            $delimiter = wp_unslash( $_POST['delimiter'] );
            $this->importable_fields = [];
            $this->setup_fields( $term_id );

            ob_start();
            
            ATBDP()->load_template( 'admin-templates/import-export/data-table', array( 'data' => csv_get_data( $file, false, $delimiter ), 'fields' => $this->importable_fields ) );
            
            echo ob_get_clean();
            
            die();
        }

        public function atbdp_import_listing() {

			if ( ! current_user_can( 'import' ) ) {
                wp_send_json( array(
					'error' => __( 'Invalid request!', 'directorist' ),
				) );
			}

            $data                  = array();
            $preview_image         = isset( $_POST['listing_img'] ) ? sanitize_text_field( $_POST['listing_img'] ) : '';
            $default_directory     = get_directorist_option( 'atbdp_default_derectory', '' );
            $directory_type        = isset( $_POST['directory_type'] ) ? sanitize_text_field( $_POST['directory_type'] ) : '';
            $directory_type        = ( empty( $directory_type ) ) ? $default_directory : $directory_type;
            $title                 = isset( $_POST['listing_title'] ) ? sanitize_text_field( $_POST['listing_title'] ) : '';
            $new_listing_status    = get_term_meta( $directory_type, 'new_listing_status', 'pending');
            $supported_post_status = array_keys( get_post_statuses() );
            $publish_date          = isset( $_POST['publish_date'] ) ? sanitize_text_field( $_POST['publish_date'] ) : '';
            $listing_status        = isset( $_POST['listing_status'] ) ? sanitize_text_field( $_POST['listing_status'] ) : '';
            $delimiter             = isset( $_POST['delimiter'] ) ? sanitize_text_field( $_POST['delimiter'] ) : '';
            $description           = isset( $_POST['listing_content'] ) ? sanitize_text_field( $_POST['listing_content'] ) : '';
            $position              = isset( $_POST['position'] ) ? sanitize_text_field( $_POST['position'] ) : 0;
            $position              = ( is_numeric( $position ) ) ? ( int ) $position : 0;
            $metas                 = isset( $_POST['meta'] ) ? atbdp_sanitize_array( $_POST['meta'] ) : array();
            $tax_inputs            = isset( $_POST['tax_input'] ) ? atbdp_sanitize_array( $_POST['tax_input'] ) : array();
            $limit                 = apply_filters( 'atbdp_listing_import_limit_per_cycle', 10 );
            $all_posts             = isset( $_POST['csv_file'] ) ? csv_get_data( $_POST['csv_file'], true, $delimiter ) : [];
            $total_length          = ( isset( $_POST['total_post'] ) && is_numeric( $_POST['total_post'] ) ) ? ( int ) $_POST['total_post'] : count( $all_posts );
            $limit                 = apply_filters('atbdp_listing_import_limit_per_cycle', ( $total_length > 100 ) ? 20 : ( ( $total_length < 35 ) ? 2 : 5 ) );
            $posts                 = ( ! empty( $all_posts ) ) ? array_slice( $all_posts, $position ) : [];
            $posts                 = apply_filters( 'directorist_listings_importing_posts', $posts, $position, $limit, $_POST );

            if ( empty( $total_length ) ) {
                $data['error']     = __('No data found', 'directorist');
                $data['_POST']     = $_POST;
                $data['file']      = $_POST['csv_file'];
                $data['all_posts'] = $all_posts;

                wp_send_json( $data );
            }

            // Counters
            $imported = 0;
            $failed   = 0;
            $count    = 0;

            foreach ( $posts as $index => $post ) {

                    if ( $count === $limit ) {
                        break;
                    }

                    // start importing listings
                    $post_status = ( isset( $post[ $listing_status ] ) ) ? $post[ $listing_status ] : '';
                    $post_status = ( in_array( $post_status, $supported_post_status ) ) ? $post_status : $new_listing_status;

                    $args = array(
                        "post_title"   => isset( $post[ $title ] ) ? html_entity_decode( $post[ $title ] ): '',
                        "post_content" => isset( $post[ $description ] ) ? html_entity_decode( $post[ $description ] ) : '',
                        "post_type"    => ATBDP_POST_TYPE,
                        "post_status"  => $post_status,
                    );

                    // Post Date
                    $post_date = ! empty( $post[ $publish_date ] ) ? sanitize_text_field( $post[ $publish_date ] ) : '';
                    $post_date = apply_filters( 'directorist_importing_listings_post_date', $post_date, $post, $args, $index );

                    if ( Directorist\Helper::validate_date_format( $post_date ) ) {
                        $args[ 'post_date' ] = $post_date;
                    }

                    $post_id = wp_insert_post( $args );

                    if (  is_wp_error( $post_id ) ) {
                        $failed++;
                        continue;
                    } 
                    
                    $imported++;

                    if ( $tax_inputs ) {
                        foreach ( $tax_inputs as $taxonomy => $term ) {
                            if ('category' == $taxonomy) {
                                $taxonomy = ATBDP_CATEGORY;
                            } elseif ('location' == $taxonomy) {
                                $taxonomy = ATBDP_LOCATION;
                            } else {
                                $taxonomy = ATBDP_TAGS;
                            }

                            $final_term  = isset( $post[ $term ] ) ? $post[ $term ] : '';
                            $final_terms = ( ! empty( $final_term ) ) ? explode( ',', $final_term ) : [];

                            if ( ! empty( $final_terms ) ) {
                                foreach( $final_terms as $term_item ) {
                                    $term_exists = get_term_by( 'name', $term_item, $taxonomy );
                                
                                    if ( ! $term_exists ) { // @codingStandardsIgnoreLine.
                                        $result = wp_insert_term( $term_item, $taxonomy );
                                        
                                        if ( ! is_wp_error( $result ) ) {
                                            $term_id = $result['term_id'];
                                            wp_set_object_terms( $post_id, $term_id, $taxonomy);
                                            update_term_meta( $term_id, '_directory_type', [ $directory_type ] );
                                        }
                                    } else {
                                        wp_set_object_terms( $post_id, $term_exists->term_id, $taxonomy, true );
                                        update_term_meta( $term_exists->term_id, '_directory_type', [ $directory_type ] );
                                    }
                                }
                            }
                            
                        }
                    }

                    foreach ( $metas as $index => $value ) {
                        $meta_value = $post[ $value ] ? $post[ $value ] : '';
                        $meta_value = $this->maybe_unserialize_csv_string( $meta_value );

                        if ( $meta_value ) {
                            update_post_meta( $post_id, '_' . $index, $meta_value );
                        }
                    }

                    $exp_dt = calc_listing_expiry_date();
                    update_post_meta( $post_id, '_expiry_date', $exp_dt );
                    update_post_meta( $post_id, '_featured', 0 );
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
            $data['url']           = admin_url( 'edit.php?post_type=at_biz_dir&page=tools&step=3' );
            $data['total']         = $total_length;
            $data['imported']      = $imported;
            $data['failed']        = $failed;

            wp_send_json( $data );
        }

        // maybe_unserialize_csv_string
        public function maybe_unserialize_csv_string( $data ) {
            if ( 'string' !== gettype( $data ) ) { return $data; }

            $_data = str_replace( "'", '"', $data );
            $_data = maybe_unserialize( maybe_unserialize( $_data ) );

            if ( ! empty( $_data ) ) {
                return $_data;
            }

            return $data;
        }


       public static function atbdp_insert_attachment_from_url( $file_url ) {

        if (!filter_var($file_url, FILTER_VALIDATE_URL)) {
            return false;
        }
        $contents = @file_get_contents($file_url);
        if ($contents === false) {
            return false;
        }
        $upload = wp_upload_bits(basename($file_url), null, $contents);
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
        $attachment = array('post_title' => basename($upload['file']), 'post_content' => '', 'post_type' => 'attachment', 'post_mime_type' => $type, 'guid' => $upload['url']);
        $id = wp_insert_attachment($attachment, $upload['file']);
        wp_update_attachment_metadata($id, wp_generate_attachment_metadata($id, $upload['file']));
        return $id;

        }

        public function atbdp_csv_import_controller()
        {
            if ( ! isset( $_POST[ 'atbdp_save_csv_step' ] ) ) {
                return;
            }

            check_admin_referer('directorist-csv-importer');

            $file   = wp_import_handle_upload();
            $file   = $file['file'];

            $base_url = admin_url() . 'edit.php';

            $params = apply_filters( 'directorist_listings_import_form_submit_redirect_params', [
                'post_type'       => 'at_biz_dir',
                'page'            => 'tools',
                'step'            => 2,
                'file'            => str_replace( DIRECTORY_SEPARATOR, '/', $file ),
                'delimiter'       => $this->delimiter,
                'update_existing' => $this->update_existing,
            ]);

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

            $this->importable_fields[ 'publish_date' ]   = __( 'Publish Date', 'directorist' );
            $this->importable_fields[ 'listing_status' ] = __( 'Listing Status', 'directorist' );

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
                        $this->importable_fields[ 'price' ] = 'Price';
                        $this->importable_fields[ 'price_range' ] = 'Price Range';
                        continue;
                        }
                    if( 'map' == $field['widget_name'] ) {
                        $this->importable_fields[ 'manual_lat' ] = 'Map Latitude';
                        $this->importable_fields[ 'manual_lng' ] = 'Map Longitude';
                        $this->importable_fields[ 'hide_map' ]   = 'Hide Map';
                        continue;
                    }
                }

                apply_filters( 'directorist_importable_fields', $this->importable_fields[ $field_key ] = $label );
            }
        }

        /**
         * It adds a submenu for showing all the Tools and details support
         */
        public function add_tools_submenu()
        {
            add_submenu_page(null,
            __('Tools', 'directorist'),
            __('Tools', 'directorist'),
            'manage_options',
            'tools',
            array($this, 'render_tools_submenu_page'));
        }

        public function get_data_table(){
            $csv_data = csv_get_data( $this->file, false, $this->delimiter );
            $data = [
                'data'     => $csv_data,
                'csv_file' => $this->file,
                'fields'   => $this->importable_fields
            ];

            ATBDP()->load_template('admin-templates/import-export/data-table', $data );
        }

        public function render_tools_submenu_page() {
            ob_start();

            ATBDP()->load_template( 'admin-templates/import-export/import-export', [ 'controller' => $this ] );

            $template = apply_filters( 'directorist_listings_importer_template', ob_get_clean() );
            
            echo $template;
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
            $template_data['download_link'] = ATBDP_URL .'views/admin-templates/import-export/data/dummy.csv';
            $template_data['nav_menu']      = $this->get_header_nav_menu();
            

            $template_path = 'admin-templates/import-export/header-templates/header';

            ob_start();

            ATBDP()->load_template( $template_path, $template_data );

            $template = apply_filters( 'directorist_listings_importer_header_template', ob_get_clean(), $template_data );

            if ( $return ) {
                return $template;
            }

            echo $template;
        }

        /**
         * Importer header nav menu item template
         * 
         * @param bool $return
         * @return string $template
         */
        public function importer_header_nav_menu_item_template( $template_data = [], $return = false ) {

            ob_start();

            $template_data['controller'] = $this;

            ATBDP()->load_template( 'admin-templates/import-export/header-templates/nav-item', $template_data );

            $template = apply_filters( 'directorist_listings_importer_header_nav_menu_item_template', ob_get_clean(), $template_data );

            if ( $return ) {
                return $template;
            }

            echo $template;
        }

        /**
         * Get Header Nav Menu
         * 
         * @return array
         */
        public function get_header_nav_menu() {
            $step = isset( $_GET['step'] ) ? sanitize_key($_GET['step']) : '';
            $nav_menu = [];

            // Item - 1
            $nav_item                   = [];
            $nav_item['nav_item_class'] = ! $step ? esc_attr('active') : ( $step > 1 ? esc_attr('done') : '');
            $nav_item['label']          = __('Upload CSV File', 'directorist');
            $nav_menu[]                 = $nav_item;
            
            // Item - 2
            $nav_item                   = [];
            $class                      = ( '2' == $step ) ? esc_attr('active') : ( $step > 2 ? esc_attr('done') : '' );
            $class                      = 'atbdp-mapping-step ' . $class;
            $nav_item['nav_item_class'] = trim( $class );
            $nav_item['label']          = __('Column Mapping', 'directorist');
            $nav_menu[]                 = $nav_item;
            
            // Item - 3
            $nav_item                   = [];
            $class                      = ( $step == 3 ) ? esc_attr('done') : '';
            $class                      = 'atbdp-progress-step ' . $class;
            $nav_item['nav_item_class'] = trim( $class );
            $nav_item['label']          = __('Import', 'directorist');
            $nav_menu[]                 = $nav_item;

            // Item - 4
            $nav_item                   = [];
            $nav_item['nav_item_class'] = ( '3' == $step ) ? esc_attr('active done') : '';
            $nav_item['label']          = __('Done', 'directorist');
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
            $step = ( isset( $_REQUEST['step'] ) ) ? $_REQUEST['step'] : 1;
            $step = ( ! empty( $step ) && is_numeric( $step ) ) ? ( int ) $step : 1;
            $template_base_path = 'admin-templates/import-export/body-templates';
            $template_paths = [
                1 => "${template_base_path}/step-one",
                2 => "${template_base_path}/step-two",
                3 => "${template_base_path}/step-done",
            ];

            $template_path = ( isset( $template_paths[ $step ] ) ) ? $template_paths[ $step ] : $template_paths[ 1 ];
            
            $template_data = [ 
                'controller' => $this,
                'step'       => $step,
            ];

            ob_start();

            ATBDP()->load_template( $template_path, $template_data );

            $template = apply_filters( 'directorist_listings_importer_body_template', ob_get_clean(), $template_data );

            if ( $return ) {
                return $template;
            }

            echo $template;
        }
    }

endif;
