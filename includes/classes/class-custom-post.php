<?php
defined('ABSPATH') || die('Direct access is not allowed.');

if (!class_exists('ATBDP_Custom_Post')):

    /**
     * Class ATBDP_Custom_Post
     */
    class ATBDP_Custom_Post
    {
        public function __construct()
        {
            // Add the listing post type and taxonomies
            add_action('init', array($this, 'register_new_post_types'));

            // add new columns for ATBDP_SHORT_CODE_POST_TYPE
            add_filter('manage_' . ATBDP_POST_TYPE . '_posts_columns', array($this, 'add_new_listing_columns'));
            add_action('manage_' . ATBDP_POST_TYPE . '_posts_custom_column', array($this, 'manage_listing_columns'), 10, 2);
            /*make column sortable*/
            add_filter('manage_edit-' . ATBDP_POST_TYPE . '_sortable_columns', array($this, 'make_sortable_column'), 10, 1);
            add_filter('post_row_actions', array($this, 'add_listing_id_row'), 10, 2);

            add_filter('enter_title_here', array($this, 'change_title_text'));
            add_filter('post_row_actions', array($this, 'add_row_actions_for_quick_view'), 10, 2);
            add_filter('load-edit.php', array($this, 'work_row_actions_for_quick_view'), 10, 2);

            // bulk directory type assign
            add_filter('quick_edit_custom_box', array($this, 'on_quick_edit_custom_box'), 10, 2);
            add_filter('bulk_edit_custom_box', array($this, 'on_quick_edit_custom_box'), 10, 2);
            add_action( 'save_post', array( $this, 'save_quick_edit_custom_box') );
            
            // Customize listing slug
            if ( get_directorist_option( 'single_listing_slug_with_directory_type', false ) ) {
                add_filter( 'post_type_link', [ $this, 'customize_listing_slug' ], 20, 2 );
                add_filter( 'post_link ', [ $this, 'customize_listing_slug' ], 20, 2 );
            }
        }

         // customize_listing_slug
         public function customize_listing_slug( $post_link = '' ) {
            $post_link = ATBDP_Permalink::get_listing_permalink( get_the_ID(), $post_link );

            return $post_link;
        }

        

        public function save_quick_edit_custom_box( $post_id) {

            if( ! is_admin() ) return;
            if( get_post_type( $post_id ) !== ATBDP_POST_TYPE ) return;
            // if our current user can't edit this post, bail
            if ( ! current_user_can( 'edit_posts' ) ) return;
            // Make sure that it is set.
            if ( !empty( $_REQUEST['directory_type'] ) ) {
                update_post_meta( $post_id, '_directory_type', sanitize_text_field( $_REQUEST['directory_type'] ) );
                wp_set_object_terms($post_id, (int)$_REQUEST['directory_type'], ATBDP_TYPE);

            }
        }

        public function on_quick_edit_custom_box( $column_name, $post_type ) {

            if ( ( 'directory_type' === $column_name ) && ( $post_type == ATBDP_POST_TYPE ) ) { ?>
            <fieldset class="inline-edit-col-right" style="margin-top: 0;">
                <div class="inline-edit-group wp-clearfix">
                    <span class="title"><?php _e( 'Directory Type', 'directorist' ); ?></span>				
                    <select name="directory_type">
                        <option value="">- <?php _e( 'Select', 'directorist' ); ?> -</option>		
                        <?php 
                        $listing_types = get_terms([
                            'taxonomy'   => ATBDP_TYPE,
                            'hide_empty' => false,
                          ]);
                          foreach ($listing_types as $listing_type) { ?>
                            <option value="<?php echo esc_attr( $listing_type->term_id ); ?>"><?php echo esc_attr( $listing_type->name ); ?></option>
                            <?php } ?>						
                    </select>
                </div>
            </fieldset>
            <?php };
        }


        public function add_cpt_to_pll($post_types, $hide)
        {
            /* echo '<pre>';
             var_dump($post_types);
             echo '</pre>';*/
            if ($hide)
                // hides 'my_cpt' from the list of custom post types in Polylang settings
                unset($post_types[ATBDP_POST_TYPE]);
            else
                // enables language and translation management for ATBDP_POST_TYPE
                $post_types[ATBDP_POST_TYPE] = ATBDP_POST_TYPE;
            return $post_types;

        }

        public function work_row_actions_for_quick_view()
        {
            $nonce = isset($_REQUEST['_wpnonce']) ? $_REQUEST['_wpnonce'] : null;
            if (wp_verify_nonce($nonce, 'quick-publish-action') && isset($_REQUEST['update_id']) && is_admin()) {
                $my_post = array();
                $my_post['ID'] = $_REQUEST['update_id'];
                $my_post['post_status'] = 'publish';
                wp_update_post($my_post);
                /**
                 * @since 5.4.0
                 */
                do_action('atbdp_listing_published', $my_post['ID']);//for sending email notification
                echo '<script>window.location="' . esc_url(admin_url() . 'edit.php?post_type=at_biz_dir') . '"</script>';
            }
        }

        /**
         * Remove quick edit.
         *
         * @param array $actions An array of row action links.
         * @param WP_Post $post The post object.
         * @return     array      $actions    Updated array of row action links.
         * @since     1.0.0
         * @access   public
         *
         */
        public function add_row_actions_for_quick_view($actions, $post)
        {

            global $current_screen;

            if ($current_screen->post_type != ATBDP_POST_TYPE) return $actions;

            if (get_post_status($post) != 'publish' && current_user_can('publish_at_biz_dirs')) {
                $nonce = wp_create_nonce('quick-publish-action');
                $link = admin_url("edit.php?update_id={$post->ID}&_wpnonce={$nonce}&post_type=at_biz_dir");
                $actions['publish'] = "<a href='$link' style='color: #4caf50; font-weight: bold'>Publish</a>";
            }
            return $actions;

        }

        /**
         * This function will register our custom post(s)
         * Initiate registrations of post types and taxonomies.
         *
         */
        public function register_new_post_types()
        {
            $this->register_post_type();
        }

        /**
         * Register the custom post type.
         *
         * @link http://codex.wordpress.org/Function_Reference/register_post_type
         */
        protected function register_post_type()
        {
            // Args for ATBDP_POST_TYPE, here any constant may not be available because this function will be called from the
            // register_activation_hook .
            $labels = array(
                'name' => _x('Directory listings', 'Plural Name of Directory listing', 'directorist'),
                'singular_name' => _x('Directory listing', 'Singular Name of Directory listing', 'directorist'),
                'menu_name' => __('Directory listings', 'directorist'),
                'name_admin_bar' => __('Directory listing', 'directorist'),
                'parent_item_colon' => __('Parent Directory listing:', 'directorist'),
                'all_items' => __('All listings', 'directorist'),
                'add_new_item' => __('Add New listing', 'directorist'),
                'add_new' => __('Add New listing', 'directorist'),
                'new_item' => __('New listing', 'directorist'),
                'edit_item' => __('Edit listing', 'directorist'),
                'update_item' => __('Update listing', 'directorist'),
                'view_item' => __('View listing', 'directorist'),
                'search_items' => __('Search listing', 'directorist'),
                'not_found' => __('No listings found', 'directorist'),
                'not_found_in_trash' => __('Not listings found in Trash', 'directorist'),
            );

            $args = array(
                'label' => __('Directory Listing', 'directorist'),
                'description' => __('Directory listings', 'directorist'),
                'labels' => $labels,
                'supports' => array('title', 'editor', 'author'),
                //'show_in_rest'         => true,
                'taxonomies' => array(ATBDP_CATEGORY),
                'hierarchical' => false,
                'public' => true,
                'show_ui' => current_user_can('edit_others_at_biz_dirs') ? true : false, // show the menu only to the admin
                'show_in_menu' => true,
                'menu_position' => 20,
                'menu_icon' => ATBDP_ADMIN_ASSETS . 'images/menu_icon.png',
                'show_in_admin_bar' => true,
                'show_in_nav_menus' => true,
                'can_export' => true,
                'has_archive' => true,
                'exclude_from_search' => false,
                'publicly_queryable' => true,
                'capability_type' => ATBDP_POST_TYPE,
                'map_meta_cap' => true, // set this true, otherwise, even admin will not be able to edit this post. WordPress will map cap from edit_post to edit_at_biz_dir etc,
                'menu_position' => 5,
            );

            if ( get_directorist_option( 'single_listing_slug_with_directory_type', false ) ) {
                $args[ 'rewrite' ] = [
                    'slug' => ATBDP_Permalink::get_listing_slug(),
                    'with_front' => false,
                ];
            } else {
                $args[ 'rewrite' ] = [
                    'slug' => get_directorist_option('atbdp_listing_slug', 'directory'),
                    'with_front' => false,
                ];
            }
            
            /**
             * @since 6.2.3
             * @package Directorist
             * @param $args
             */
            $arguments = apply_filters('atbdp_register_listing_post_type_arguments', $args);
            register_post_type(ATBDP_POST_TYPE, $arguments);

            // the flush_rewrite_rules() should never be called on every init hook every time a page loads.
            // Rather we should use it only once at the time of the plugin activation.
            //flush_rewrite_rules();
        }


        public function add_new_listing_columns($columns)
        {
            $featured_active = get_directorist_option('enable_featured_listing');
            $enable_multi_directory = get_directorist_option( 'enable_multi_directory', false );

            $columns = array();
            $columns['cb'] = '<input type="checkbox" />';
            $columns['title'] = __('Listing Name', 'directorist');
            if ( atbdp_is_truthy( $enable_multi_directory ) ) {
                $columns['directory_type'] = __('Directory Type', 'directorist');
            }
            $columns['atbdp_location'] = __('Location', 'directorist');
            $columns['atbdp_category'] = __('Categories', 'directorist');
            $columns['atbdp_author'] = __('Author', 'directorist');
            $columns['atbdp_status'] = __('Status', 'directorist');
            if ($featured_active || is_fee_manager_active()) {
                $columns['atbdp_featured'] = __('Featured', 'directorist');
            }
            $subscribed_package_id = get_user_meta(get_current_user_id(), '_subscribed_users_plan_id', true);
            $num_featured_unl = get_post_meta($subscribed_package_id, 'num_featured_unl', true);
            $num_featured = get_post_meta($subscribed_package_id, 'num_featured', true);
            $featured_submited = get_user_meta(get_current_user_id(), '_featured_type', true) ? (int)get_user_meta(get_current_user_id(), '_featured_type', true) : 1;
            $featured_available_in_plan = $num_featured - $featured_submited;
            if (is_fee_manager_active() && $featured_available_in_plan > 1 || $num_featured_unl) {
                $columns['atbdp_featured'] = __('Featured', 'directorist');
            }
            $columns['atbdp_expiry_date'] = __('Expires on', 'directorist');
            $columns['atbdp_date'] = __('Created on', 'directorist');
            return (apply_filters('atbdp_add_new_listing_column', $columns));
        }

        public function manage_listing_columns($column_name, $post_id)
        {
            /*@TODO; Next time we can add image column too. */
            $date_format = get_option('date_format');
            $time_format = get_option('time_format');
            switch ($column_name) {
                case 'atbdp_location':
                    $terms = wp_get_post_terms($post_id, ATBDP_LOCATION);
                    if (!empty($terms) && is_array($terms)) {
                        foreach ($terms as $term) {
                            // link the tax term to the search page with custom query string so that plugin can show correct data from database
                            ?>
                            <a href="<?php echo ATBDP_Permalink::atbdp_get_location_page($term); ?>">
                                <?php echo $term->name; ?>
                            </a>
                            <?php
                        }
                    }
                    break;

                case 'atbdp_category':
                    $categories = get_the_terms($post_id, ATBDP_CATEGORY);
                    $cats = !empty($categories) ? $categories : array();

                    foreach ($cats as $cat_title) {
                        ?>
                        <a href="<?php echo ATBDP_Permalink::atbdp_get_category_page($cat_title); ?>">
                            <i class="fa <?php echo get_cat_icon($cat_title->term_name); ?>" aria-hidden="true"></i>
                            <?php echo $cat_title->name; ?>
                        </a>
                        <?php

                    }

                    break;
                    
                case 'directory_type':
                    $term_id = get_post_meta( $post_id, '_directory_type', true );
                    $term = get_term_by( is_numeric($term_id) ? 'id' : 'slug', $term_id, ATBDP_TYPE );
                    $config = get_term_meta( $term_id, 'general_config', true );
                    $icon   = is_array( $config ) ? $config['icon'] : '';
                    $term_name = !empty( $term  ) ? $term->name : '';
                    if( !empty( $icon ) ) { ?>
                    <span class="<?php echo esc_html( $icon );?>"></span>
                    <?php } ?>
                    <span><?php echo esc_attr( $term_name ); ?></span>
                    <?php
                    break;
                    
                case 'atbdp_author':
                    the_author_posts_link();
                    break;

                case 'atbdp_status':
                    $sts = get_post_meta($post_id, '_listing_status', true);

                    echo (empty($sts) || 'post_status' == $sts) ? get_post_status($post_id) : $sts;
                    break;

                case 'atbdp_featured':
                    $featured = get_post_meta($post_id, '_featured', true);
                    echo '<span style="margin-left: 15px">';
                    echo !empty($featured) ? '<i class="' . atbdp_icon_type() . '-check-circle"></i>' : '<i class="fa fa-times-circle"></i>';
                    echo '</span>';
                    break;

                case 'atbdp_expiry_date':
                    $exp_date = get_post_meta($post_id, '_expiry_date', true);
                    $never_exp = get_post_meta($post_id, '_never_expire', true);
                    echo !empty($never_exp) ? __('Never Expires', 'directorist') : date_i18n("$date_format @  $time_format", strtotime($exp_date));
                    break;
                case 'atbdp_date':
                    $t = get_the_time('U');
                    echo date_i18n($date_format, $t);
                    printf(__(' ( %s ago )', 'directorist'), human_time_diff($t));

                    break;

            }
            /*
             * since 4.2.0
             */
            apply_filters('atbdp_add_new_listing_column_content', $column_name, $post_id);
        }

        public function make_sortable_column($columns)
        {
            $columns['atbdp_author'] = 'author';
            return $columns;

        }


        /**
         * Change the placeholder of title input box
         * @param string $title Name of the Post Type
         *
         * @return string Returns modified title
         */
        public function change_title_text($title)
        {
            global $typenow;
            if (ATBDP_POST_TYPE == $typenow) {
                $title_placeholder = get_directorist_option( 'title_placeholder', __( 'Enter a title', 'directorist' ) );
                $title = !empty( $title_placeholder ) ? esc_attr( $title_placeholder ) : __( 'Enter a title', 'directorist' );
            }
            return $title;

        }

        /**
         * It adds an ID row on the listings list page
         *
         * @param array $actions The array of post actions
         * @param WP_Post $post The current post post
         * @return array    $actions        It returns the array of post actions after adding an ID row
         */
        public function add_listing_id_row($actions, WP_Post $post)
        {
            if (ATBDP_POST_TYPE != $post->post_type) return $actions;
            return array_merge(array('ID' => "ID: {$post->ID}"), $actions);
        }


    }

endif;