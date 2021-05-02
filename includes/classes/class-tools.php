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
            $term_id 		        = sanitize_text_field( $_POST['directory_type'] );
            $file 		            = wp_unslash( $_POST['csv_file'] );

            if ( empty( $file ) && isset($_POST['file'] ) ) {
                $file = wp_unslash( $_POST['file'] );
            }

            $delimiter 		        = wp_unslash( $_POST['delimiter'] );
            $this->importable_fields = [];
            $this->setup_fields( $term_id );
            ob_start();
            ATBDP()->load_template('admin-templates/import-export/data-table',  array('data' => csv_get_data($file, false, $delimiter), 'fields' => $this->importable_fields ));
            echo ob_get_clean();
            die();
        }

        public function atbdp_import_listing()
        {
            $data               = array();
            $imported           = 0;
            $failed             = 0;
            $count              = 0;
            $preview_image      = isset($_POST['listing_img']) ? sanitize_text_field($_POST['listing_img']) : '';
            $default_directory  = get_directorist_option( 'atbdp_default_derectory', '' );
            $directory_type     = isset($_POST['directory_type']) ? sanitize_text_field($_POST['directory_type']) : '';
            $directory_type     = ( empty( $directory_type ) ) ? $default_directory : $directory_type;
            $new_listing_status = get_term_meta( $directory_type, 'new_listing_status', 'pending');
            $title              = isset($_POST['listing_title']) ? sanitize_text_field($_POST['listing_title']) : '';
            $delimiter          = isset($_POST['delimiter']) ? sanitize_text_field($_POST['delimiter']) : '';
            $description        = isset($_POST['listing_content']) ? sanitize_text_field($_POST['listing_content']) : '';
            $position           = isset($_POST['position']) ? sanitize_text_field($_POST['position']) : 0;
            $metas              = isset($_POST['meta']) ? atbdp_sanitize_array($_POST['meta']) : array();
            $tax_inputs         = isset($_POST['tax_input']) ? atbdp_sanitize_array($_POST['tax_input']) : array();
            $limit              = apply_filters('atbdp_listing_import_limit_per_cycle', 10);
            $all_posts          = csv_get_data($_POST['csv_file'], true, $delimiter);
            $posts              = array_slice($all_posts, $position);
            $total_length       = count($all_posts);
            $limit              = apply_filters('atbdp_listing_import_limit_per_cycle', ($total_length > 100) ? 20 : (($total_length < 35) ? 2 : 5));

            if ( empty( $total_length ) ) {

                $data['error']     = __('No data found', 'directorist');
                $data['_POST']     = $_POST;
                $data['file']      = $_POST['csv_file'];
                $data['all_posts'] = $all_posts;

                wp_send_json( $data );
            }

            foreach ($posts as $index => $post) {
                    if ($count === $limit ) break;
                    // start importing listings
                    $args = array(
                        "post_title"   => isset($post[$title]) ? html_entity_decode( $post[$title] ): '',
                        "post_content" => isset($post[$description]) ? html_entity_decode( $post[$description] ) : '',
                        "post_type"    => 'at_biz_dir',
                        "post_status"  => $new_listing_status,
                    );
                    $post_id = wp_insert_post($args);

                    if (!is_wp_error($post_id)) {
                        $imported++;
                    } else {
                        $failed++;
                    }

                    if ($tax_inputs) {
                        foreach ( $tax_inputs as $taxonomy => $term ) {
                            if ('category' == $taxonomy) {
                                $taxonomy = ATBDP_CATEGORY;
                            } elseif ('location' == $taxonomy) {
                                $taxonomy = ATBDP_LOCATION;
                            } else {
                                $taxonomy = ATBDP_TAGS;
                            }
                            
                            $final_term = isset($post[$term]) ? $post[$term] : '';
                            $term_exists = get_term_by( 'name', $final_term, $taxonomy );
                            if ( ! $term_exists ) { // @codingStandardsIgnoreLine.
                                $result = wp_insert_term( $final_term, $taxonomy );
                                if( !is_wp_error( $result ) ){
                                    $term_id = $result['term_id'];
                                    wp_set_object_terms($post_id, $term_id, $taxonomy);
                                }
                            }else{
                                wp_set_object_terms($post_id, $term_exists->term_id, $taxonomy);
                            }
                        }
                    }

                    foreach ($metas as $index => $value) {
                        $meta_value = $post[ $value ] ? $post[ $value ] : '';
                        $meta_value = $this->maybe_unserialize_csv_string( $meta_value );

                        if ( $meta_value ) {
                            update_post_meta( $post_id, '_' . $index, $meta_value );
                        }
                    }

                    $exp_dt = calc_listing_expiry_date();
                    update_post_meta($post_id, '_expiry_date', $exp_dt);
                    update_post_meta($post_id, '_featured', 0);
                    update_post_meta($post_id, '_listing_status', 'post_status');

                    if ( ! empty( $post['directory_type'] ) ) {
                        $directory_type_slug = $post['directory_type'];
                        $directory_type_term = get_term_by( 'slug', $directory_type_slug, ATBDP_DIRECTORY_TYPE );
                        $directory_type = ( ! empty( $directory_type_term ) ) ? $directory_type_term->term_id : $directory_type;
                    }
                    
                    update_post_meta($post_id, '_directory_type', $directory_type);
                    $preview_url = isset($post[$preview_image]) ? $post[$preview_image] : '';
                    $preview_url = explode( ',', $preview_url );

                    if ( $preview_url ) {
                        $attachment_ids = [];
                        foreach ( $preview_url as $_url_index => $_url ) {
                            $attachment_id = self::atbdp_insert_attachment_from_url($_url, $post_id);
                            if ( 1 === $_url_index ) {
                                update_post_meta($post_id, '_listing_prv_img', $attachment_id);
                            } else {
                                $attachment_ids[] = $attachment_id;
                            }
                        }

                        update_post_meta($post_id, '_listing_img', $attachment_ids );
                    }

                    $count++;
            }

            $data['next_position'] = (int) $position + (int) $count;
            $data['percentage']    = absint(min(round((($data['next_position']) / $total_length) * 100), 100));
            $data['url']           = admin_url('edit.php?post_type=at_biz_dir&page=tools&step=3');
            $data['total']         = $total_length;
            $data['imported']      = $imported;
            $data['failed']        = $failed;

            wp_send_json($data);
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
            if (isset($_POST['atbdp_save_csv_step'])) {
                check_admin_referer('directorist-csv-importer');
                // redirect to step two || data mapping
                $file = wp_import_handle_upload();
                $file = $file['file'];
                $url = admin_url() . "edit.php?post_type=at_biz_dir&page=tools&step=2";
                $params = array(
                    'step'            => 2,
                    'file'            => str_replace(DIRECTORY_SEPARATOR, '/', $file),
                    'delimiter'       => $this->delimiter,
                    'update_existing' => $this->update_existing,
                );
                wp_safe_redirect(add_query_arg($params, $url));
            }
        }


        public function prepare_data(){
            $this->default_directory = default_directory_type();
            $this->setup_fields();
        }


        public function setup_fields( $directory = '' ) {
                $directory      = $directory ? $directory : $this->default_directory;
                $fields         = directorist_get_form_fields_by_directory_type( 'id', $directory );
                foreach( $fields as $field ){
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
            $csv_data = csv_get_data( $this->file, false, $this->delimiter);
            $data = [
                'data'     => $csv_data,
                'csv_file' => $this->file,
                'fields'   => $this->importable_fields
            ];

            ATBDP()->load_template('admin-templates/import-export/data-table', $data );
        }

        public function render_tools_submenu_page()
        {
            ATBDP()->load_template('admin-templates/tools');
        }
    }

endif;
