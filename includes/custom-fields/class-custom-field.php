<?php
// Exit if accessed directly
if (!defined('ABSPATH')) exit;

/**
 * ATBDP_Custom_Field Class
 *
 * @since    3.1.0
 * @access   public
 */
class ATBDP_Custom_Field
{


    public function __construct()
    {
        add_action('init', array($this, 'register_custom_post_type'));
        add_action('save_post', array($this, 'atbdp_save_meta_data'));


        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));

        add_filter('manage_atbdp_fields_posts_columns', array($this, 'add_new_order_columns'));

        //add_filter( 'manage_edit-atbdp_fields_sortable_columns', array($this, 'get_sortable_columns') );

        add_filter('post_row_actions', array($this, 'set_custom_field_link'), 10, 2);

        add_action('manage_atbdp_fields_posts_custom_column', array($this, 'custom_field_column_content'), 10, 2);

        add_filter('post_row_actions', array($this, 'remove_row_actions_for_quick_view'), 10, 2);

        // action hook trigger the drop and drag feature
        add_action('admin_init', array($this, 'refresh'));

        add_action('admin_enqueue_scripts', array($this, 'load_script_css'));

        add_action('wp_ajax_update-menu-order', array($this, 'update_menu_order'));

        add_action('pre_get_posts', array($this, 'atbdp_pre_get_posts'));


    }


    function refresh()
    {
        global $wpdb;
        $objects = ATBDP_CUSTOM_FIELD_POST_TYPE;
        $objects = array($objects, 'atbdp_pricing_plans', 'product');

        if (!empty($objects)) {
            foreach ($objects as $object) {
                $result = $wpdb->get_results("
					SELECT count(*) as cnt, max(menu_order) as max, min(menu_order) as min
					FROM $wpdb->posts
					WHERE post_type = '" . $object . "' AND post_status IN ('publish', 'pending', 'draft', 'private', 'future')
				");
                if ($result[0]->cnt == 0 || $result[0]->cnt == $result[0]->max)
                    continue;

                $results = $wpdb->get_results("
					SELECT ID
					FROM $wpdb->posts
					WHERE post_type = '" . $object . "' AND post_status IN ('publish', 'pending', 'draft', 'private', 'future')
					ORDER BY menu_order ASC
				");
                foreach ($results as $key => $result) {
                    $wpdb->update($wpdb->posts, array('menu_order' => $key + 1), array('ID' => $result->ID));
                }
            }
        }

    }


    /*
     * to get the pre get post
     */
    function atbdp_pre_get_posts($wp_query)
    {
        $objects = $this->get_scporder_options_objects();
        if (empty($objects))
            return false;
        if (is_admin()) {

            if (isset($wp_query->query['post_type']) && !isset($_GET['orderby'])) {
                if (in_array($wp_query->query['post_type'], $objects)) {
                    $wp_query->set('orderby', 'menu_order');
                    $wp_query->set('order', 'ASC');
                }
            }
        } else {

            $active = false;

            if (isset($wp_query->query['post_type'])) {
                if (!is_array($wp_query->query['post_type'])) {
                    if (in_array($wp_query->query['post_type'], $objects)) {
                        $active = true;
                    }
                }
            } else {
                if (in_array('post', $objects)) {
                    $active = true;
                }
            }

            if (!$active)
                return false;

            if (isset($wp_query->query['suppress_filters'])) {
                if ($wp_query->get('orderby') == 'date')
                    $wp_query->set('orderby', 'menu_order');
                if ($wp_query->get('order') == 'DESC')
                    $wp_query->set('order', 'ASC');
            } else {
                if (!$wp_query->get('orderby'))
                    $wp_query->set('orderby', 'menu_order');
                if (!$wp_query->get('order'))
                    $wp_query->set('order', 'ASC');
            }

        }

    }


    function get_scporder_options_objects()
    {
        $atbdp_options = ATBDP_CUSTOM_FIELD_POST_TYPE;
        $objects = array($atbdp_options, 'atbdp_pricing_plans','product');
        return $objects;
    }


    /*
     * for update the drop and dragable field
     */
    public function update_menu_order()
    {
        global $wpdb;

        parse_str($_POST['order'], $data);

        if (!is_array($data))
            return false;

        $id_arr = array();
        foreach ($data as $key => $values) {
            foreach ($values as $position => $id) {
                $id_arr[] = $id;
            }
        }

        $menu_order_arr = array();
        foreach ($id_arr as $key => $id) {
            $results = $wpdb->get_results("SELECT menu_order FROM $wpdb->posts WHERE ID = " . intval($id));
            foreach ($results as $result) {
                $menu_order_arr[] = $result->menu_order;
            }
        }

        sort($menu_order_arr);

        foreach ($data as $key => $values) {
            foreach ($values as $position => $id) {
                $wpdb->update($wpdb->posts, array('menu_order' => $menu_order_arr[$position]), array('ID' => intval($id)));
            }
        }
    }


    function _check_load_script_css()
    {
        $active = false;

        $objects = $this->get_scporder_options_objects();

        if (empty($objects))
            return false;

        if (isset($_GET['orderby']) || strstr($_SERVER['REQUEST_URI'], 'action=edit') || strstr($_SERVER['REQUEST_URI'], 'wp-admin/post-new.php'))
            return false;

        if (!empty($objects)) {
            if (isset($_GET['post_type']) && !isset($_GET['taxonomy']) && in_array($_GET['post_type'], $objects)) { // if page or custom post types
                $active = true;
            }
            if (!isset($_GET['post_type']) && strstr($_SERVER['REQUEST_URI'], 'wp-admin/edit.php') && in_array('post', $objects)) { // if post
                $active = true;
            }
        }


        return $active;
    }


    /**
     * Load the settings require to the custom field.
     *
     * @since     3.1.6
     * @access   public
     *
     */


    public function load_script_css()
    {
        if ($this->_check_load_script_css()) {
            wp_enqueue_style('custom-field-css',ATBDP_ADMIN_ASSETS . 'css/drag_drop.css');
            wp_enqueue_script('jquery');
            wp_enqueue_script('jquery-ui-sortable');
            wp_enqueue_script('custom-field-js', ATBDP_ADMIN_ASSETS . 'js/custom-field.js', array('jquery'), null, true);
        }
    }


    /**
     * Remove quick edit.
     *
     * @since     1.0.0
     * @access   public
     *
     * @param     array $actions An array of row action links.
     * @param     WP_Post $post The post object.
     * @return     array      $actions    Updated array of row action links.
     */
    public function remove_row_actions_for_quick_view($actions, $post)
    {

        global $current_screen;

        if ($current_screen->post_type != 'atbdp_fields') return $actions;

        unset($actions['view']);
        unset($actions['inline hide-if-no-js']);

        return $actions;

    }


    public function custom_field_column_content($column, $post_id)
    {
        $current_val = esc_attr(get_post_meta($post_id, 'category_pass', true));
        $all_term = get_term_by('term_id', $current_val, ATBDP_CATEGORY);
        $selected_cat = !empty($all_term)?$all_term->name:'';
        echo '</select>';
        switch ($column) {
            case 'type' :
                $types = $this->atbdp_get_custom_field_types();

                $value = esc_attr(get_post_meta($post_id, 'type', true));
                echo !empty($types[$value])?$types[$value]:'';
                break;
            case 'asign' :

                $value = esc_attr(get_post_meta($post_id, 'associate', true));
                echo ('form' == $value) ? __('Form', ATBDP_TEXTDOMAIN) : $selected_cat . __(' Category', ATBDP_TEXTDOMAIN);

                break;

            case 'require' :
                $value = esc_attr(get_post_meta($post_id, 'required', true));
                echo '<span class="atbdp-tick-cross">' . ($value == 1 ? '&#x2713;' : '&#x2717;') . '</span>';
                break;
            case 'searchable' :
                $value = esc_attr(get_post_meta( $post_id, 'searchable', true ));
                echo '<span class="atbdp-tick-cross2">'.($value == 1 ? '&#x2713;' : '&#x2717;').'</span>';
                break;
        }
    }

    public function atbdp_save_meta_data($post_id)
    {

        if (!isset($_POST['post_type'])) {
            return $post_id;
        }


        // If this is an autosave, our form has not been submitted, so we don't want to do anything
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        // Check the logged in user has permission to edit this post
        if (!current_user_can('edit_post')) {
            return $post_id;
        }

        // Check if "atbdp_field_details_nonce" nonce is set
        if (isset($_POST['atbdp_field_details_nonce'])) {

            // Verify that the nonce is valid
            if (wp_verify_nonce($_POST['atbdp_field_details_nonce'], 'atbdp_save_field_details')) {


                // OK to save meta data
                $field_type = sanitize_key(isset($_POST['type']) ? $_POST['type'] : 'text');
                update_post_meta($post_id, 'type', $field_type);

                $field_instructions = esc_textarea($_POST['instructions']);
                update_post_meta($post_id, 'instructions', $field_instructions);

                $field_required = (int)$_POST['required'];
                update_post_meta($post_id, 'required', $field_required);

                $field_choices = esc_textarea($_POST['choices']);
                update_post_meta($post_id, 'choices', $field_choices);

                if ('checkbox' == $field_type || 'textarea' == $field_type) {
                    $field_default_value = esc_textarea($_POST['default_value_' . $field_type]);
                } else if ('url' == $field_type) {
                    $field_default_value = esc_url_raw($_POST['default_value']);
                } else {
                    $field_default_value = sanitize_text_field($_POST['default_value']);
                }
                update_post_meta($post_id, 'default_value', $field_default_value);

                $field_rows = (int)$_POST['rows'];
                update_post_meta($post_id, 'rows', $field_rows);

                $field_target = sanitize_text_field($_POST['target']);
                update_post_meta($post_id, 'target', $field_target);
            }
        }
        // Check if "atbdp_field_options_nonce" nonce is set
        if (isset($_POST['atbdp_field_options_nonce'])) {

            // Verify that the nonce is valid
            if (wp_verify_nonce($_POST['atbdp_field_options_nonce'], 'atbdp_save_field_options')) {

                // OK to save meta data
                $field_associate = sanitize_text_field($_POST['associate']);
                update_post_meta($post_id, 'associate', $field_associate);

                $field_category_pass = sanitize_text_field($_POST['category_pass']);
                update_post_meta($post_id, 'category_pass', $field_category_pass);
                 $field_searchable = (int) $_POST['searchable'];
                 update_post_meta( $post_id, 'searchable', $field_searchable );

            }

        }

        return $post_id;

    }

    /**
     * Register a custom post type "atbdp_fields".
     *
     * @since    3.1.0
     * @access   public
     */
    public function register_custom_post_type()
    {

        $labels = array(
            'name' => _x('Custom Field', 'Post Type General Name', ATBDP_TEXTDOMAIN),
            'singular_name' => _x('Custom Field', 'Post Type Singular Name', ATBDP_TEXTDOMAIN),
            'menu_name' => __('Custom Field', ATBDP_TEXTDOMAIN),
            'name_admin_bar' => __('Custom Field', ATBDP_TEXTDOMAIN),
            'all_items' => __('Custom Field', ATBDP_TEXTDOMAIN),
            'add_new_item' => __('Add New Field', ATBDP_TEXTDOMAIN),
            'add_new' => __('Add New Field', ATBDP_TEXTDOMAIN),
            'new_item' => __('New Field', ATBDP_TEXTDOMAIN),
            'edit_item' => __('Edit Field', ATBDP_TEXTDOMAIN),
            'update_item' => __('Update Field', ATBDP_TEXTDOMAIN),
            'view_item' => __('View Field', ATBDP_TEXTDOMAIN),
            'search_items' => __('Search Field', ATBDP_TEXTDOMAIN),
            'not_found' => __('No orders found', ATBDP_TEXTDOMAIN),
            'not_found_in_trash' => __('No orders found in Trash', ATBDP_TEXTDOMAIN),
        );

        $args = array(
            'labels' => $labels,
            'description' => __('This order post type will keep track of user\'s order and payment status', ATBDP_TEXTDOMAIN),
            'supports' => array('title'),
            'taxonomies' => array(''),
            'hierarchical' => false,
            'public' => true,
            'show_ui' => current_user_can('manage_atbdp_options') ? true : false, // show the menu only to the admin
            'show_in_menu' => current_user_can('manage_atbdp_options') ? 'edit.php?post_type=' . ATBDP_POST_TYPE : false,
            'show_in_admin_bar' => true,
            'show_in_nav_menus' => true,
            'can_export' => true,
            'has_archive' => true,
            'exclude_from_search' => true,
            'publicly_queryable' => true,
            'capability_type' => 'atbdp_order',
            'map_meta_cap' => true,
        );

        register_post_type('atbdp_fields', $args);

    }


    /**
     * Register meta boxes for custom fields.
     *
     * @since    3.1.6
     * @access   public
     */
    public function add_meta_boxes()
    {

        remove_meta_box('cf_metabox', 'atbdp_fields', 'normal');

        add_meta_box('atbdp-field-details', __('Field Details', ATBDP_TEXTDOMAIN), array($this, 'atbdp_meta_box_field_details'), 'atbdp_fields', 'normal', 'high');
        add_meta_box('atbdp-field-options', __('Display Options', ATBDP_TEXTDOMAIN), array($this, 'atbdp_display_meta_box_field_options'), 'atbdp_fields', 'normal', 'high');

    }


    /**
     * Display the field details meta box.
     *
     * @since     3.1.6
     * @access   public
     *
     * @param     WP_Post $post WordPress Post object
     */
    public function atbdp_display_meta_box_field_options($post)
    {
        $post_meta = get_post_meta($post->ID);

        // Add a nonce field so we can check for it later
        wp_nonce_field('atbdp_save_field_options', 'atbdp_field_options_nonce');
        /**
         * Display the "Field Options" meta box.
         */
        ?>

        <div id="directorist" class="atbd_wrapper">
            <div class="atbdp-input widefat" id="atbdp-field-options">


                 <tr>
                <td class="label">
                    <label><?php _e( 'Include this field in the search form?', ATBDP_TEXTDOMAIN );
                ?></label>
                </td>
                <td>
                    <?php $searchable = isset( $post_meta['searchable'] ) ? esc_attr($post_meta['searchable'][0]) : 0;
                ?>
                    <ul class="atbdp-radio-list radio horizontal">
                        <li>
                            <label>
                                <input type="radio" name="searchable" value="1" <?php echo checked( $searchable, 1, false );
                ?>><?php _e( 'Yes', ATBDP_TEXTDOMAIN );
                ?>
                            </label>
                        </li>
                        <li>
                            <label>
                                <input type="radio" name="searchable" value="0" <?php echo checked( $searchable, 0, false );
                ?>><?php _e( 'No', ATBDP_TEXTDOMAIN );
                ?>
                            </label>
                        </li>
                    </ul>
                </td>
            </tr>


                <label><?php _e('Assign to', ATBDP_TEXTDOMAIN); ?></label>

                <?php
                $associate = isset($post_meta['associate']) ? esc_attr($post_meta['associate'][0]) : 'form'; ?>
                <input type="hidden" id="is_checked_form" value="<?php echo $associate; ?>">
                <ul class="atbdp-radio-list radio horizontal">
                    <li>
                        <label>
                            <input id="custom_cat_tohide" type="radio" name="associate"
                                   value="form" <?php echo checked($associate, 'form', false); ?>>
                            <?php _e('Listing Form', ATBDP_TEXTDOMAIN); ?>
                        </label>
                    </li>
                    <script>
                        (function ($) {
                            $(document).ready(function () {
                                $('#custom_cat').on('click', function () {

                                    $('#cat_types_toshow').fadeIn(500);
                                });
                                var is_checked_cat = $('#to_Show_if_checked_cat').val();
                                var is_checked_form = $('#is_checked_form').val();
                                if (is_checked_cat > 0) {
                                    $('#cat_types_toshow').fadeIn(300);
                                }
                                if (is_checked_cat > 0 && is_checked_form == 'form') {
                                    $('#cat_types_toshow').hide();
                                }
                                $('#custom_cat_tohide').on('click', function () {
                                    $('#cat_types_toshow').fadeOut(500);
                                });
                            });
                        })(jQuery);
                    </script>
                    <li>
                        <label>
                            <input id="custom_cat" type="radio" name="associate"
                                   value="categories" <?php echo checked($associate, 'categories', false); ?>>
                            <?php _e('Specific Category', ATBDP_TEXTDOMAIN); ?>
                        </label>
                    </li>
                </ul>

                <div class="field-type-to-asign" id="cat_types_toshow" style="display:none">
                    <label>Select a category</label>
                    <div class="field_lable_to_asign">
                        <?php
                        $current_val = isset($post_meta['category_pass']) ? esc_attr($post_meta['category_pass'][0]) : '';
                        $categories = get_terms(ATBDP_CATEGORY, array('hide_empty' => 0));
                        echo '<select name="category_pass" class="form-control">';
                        echo '<option>' . __("-Select a category-", ATBDP_TEXTDOMAIN) . '</option>';
                        foreach ($categories as $key => $cat_title) {
                            printf('<option value="%s" %s>%s</option>', $cat_title->term_id, selected($cat_title->term_id, $current_val), $cat_title->name);
                        }
                        echo '</select>';
                        ?>
                        <input type="hidden" id="to_Show_if_checked_cat" value="<?php echo $current_val ?>">

                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Get custom field types.
     *
     * @since     3.1.6
     *
     * @return    array    $types    Array of custom field types.
     */
    private function atbdp_get_custom_field_types()
    {

        $types = array(
            'text' => __('Text', ATBDP_TEXTDOMAIN),
            'textarea' => __('Text Area', ATBDP_TEXTDOMAIN),
            'select' => __('Select', ATBDP_TEXTDOMAIN),
            'checkbox' => __('Checkbox', ATBDP_TEXTDOMAIN),
            'radio' => __('Radio Button', ATBDP_TEXTDOMAIN),
            'url' => __('URL', ATBDP_TEXTDOMAIN),
            'date' => __('Date', ATBDP_TEXTDOMAIN),
            'color' => __('Color', ATBDP_TEXTDOMAIN),
            'time' => __('Time', ATBDP_TEXTDOMAIN)
        );

        // Return
        return $types;

    }

    public function atbdp_meta_box_field_details($post)
    {
        $post_meta = get_post_meta($post->ID);
        // Add a nonce field so we can check for it later
        wp_nonce_field('atbdp_save_field_details', 'atbdp_field_details_nonce');
        /**
         * Display the "Field Details" meta box.
         */
        ?>
        <script>
            (function ($) {
                $(function () {
                    $('.field-type select', '#atbdp-field-details').on('change', function () {
                        var fields_number = $('.field-options').length;
                        var option = $(this).val();
                        $('.field-options', '#atbdp-field-details').fadeOut(400, function () {
                            if (--fields_number > 0) return;
                            $('.field-option-' + option, '#atbdp-field-details').fadeIn(400);
                        });
                    }).change();
                });
            })(jQuery);
        </script>

        <div id="directorist" class="atbd_wrapper">
            <div class="atbdp-input widefat" id="atbdp-field-details">

                <div class="field-type">
                    <label class="widefat"><?php _e('Field Type', ATBDP_TEXTDOMAIN); ?></label>
                    <div class="field_lable form-group">
                        <select class="select form-control" name="type">
                            <?php
                            $types = $this->atbdp_get_custom_field_types();

                            $selected_type = isset($post_meta['type']) ? esc_attr($post_meta['type'][0]) : 'text';

                            foreach ($types as $key => $label) {
                                printf('<option value="%s"%s>%s</option>', $key, selected($selected_type, $key, false), $label);
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="field-instructions form-group">
                    <label><?php _e('Field Description', ATBDP_TEXTDOMAIN); ?></label>
                    <p class="description"><?php _e('Tooltip instructions for author', ATBDP_TEXTDOMAIN); ?></p>
                    <textarea class="textarea form-control" name="instructions"
                              rows="6"><?php if (isset($post_meta['instructions'])) echo esc_textarea($post_meta['instructions'][0]); ?></textarea>
                </div>

                <div class="field-required">
                    <label><?php _e('Required?', ATBDP_TEXTDOMAIN); ?></label>
                    <?php $selected_required = isset($post_meta['required']) ? esc_attr($post_meta['required'][0]) : 0; ?>
                    <ul class="atbdp-radio-list radio horizontal">
                        <li>
                            <label>
                                <input type="radio" name="required" value="1" <?php echo checked($selected_required, 1, false); ?>><?php _e('Yes', ATBDP_TEXTDOMAIN); ?>
                            </label>
                        </li>
                        <li>
                            <label>
                                <input type="radio" name="required" value="0" <?php echo checked($selected_required, 0, false); ?>><?php _e('No', ATBDP_TEXTDOMAIN); ?>
                            </label>
                        </li>
                    </ul>
                </div>


                <div class="field-options field-option-select field-option-checkbox field-option-radio"
                     style="display:none;">
                    <label><?php _e('Options', ATBDP_TEXTDOMAIN); ?></label>
                    <p class="description">
                        <?php _e('Each on a new line, for example,', ATBDP_TEXTDOMAIN); ?><br/>
                        <?php _e('Male: Male', ATBDP_TEXTDOMAIN); ?><br/>
                        <?php _e('Female: Female', ATBDP_TEXTDOMAIN); ?><br/>
                        <?php _e('Other: Other', ATBDP_TEXTDOMAIN); ?>

                    </p>
                    <textarea class="textarea form-control" name="choices"
                              rows="8"><?php if (isset($post_meta['choices'])) echo esc_attr($post_meta['choices'][0]); ?></textarea>
                </div>

                <div class="field-options field-option-textarea" style="display:none;">
                    <label><?php _e('Rows', ATBDP_TEXTDOMAIN); ?></label>
                    <p class="description"><?php _e('Textarea height', ATBDP_TEXTDOMAIN); ?></p>
                    <div class="atbdp-input-wrap">
                        <input type="text" class="text form-control" name="rows" placeholder="8" value="<?php if (isset($post_meta['rows'])) echo esc_attr($post_meta['rows'][0]); ?>"/>
                    </div>
                </div>

                <div class="field-options field-option-url" style="display:none;">
                    <label><?php _e('Open link in a new window?', ATBDP_TEXTDOMAIN); ?></label>

                    <?php $selected_target = isset($post_meta['target']) ? esc_attr($post_meta['target'][0]) : '_blank'; ?>
                    <ul class="atbdp-radio-list radio horizontal">
                        <li>
                            <label><input type="radio" name="target"
                                          value="_blank" <?php echo checked($selected_target, '_blank', false); ?>><?php _e('Yes', ATBDP_TEXTDOMAIN); ?>
                            </label>
                        </li>
                        <li>
                            <label><input type="radio" name="target"
                                          value="_self" <?php echo checked($selected_target, '_self', false); ?>><?php _e('No', ATBDP_TEXTDOMAIN); ?>
                            </label>
                        </li>
                    </ul>
                </div>

                <div class="field-options field-option-date" style="display:none;"></div>

                <div class="field-options field-option-color" style="display:none;"></div>
            </div>
        </div>

        <?php
    }


    /**
     * Retrieve the table columns.
     *
     * @since    3.1.0
     * @access   public
     * @param    array $columns
     *
     * @return   array    $columns    Array of all the list table columns.
     */
    public function add_new_order_columns($columns)
    {

        $columns = array(
            'cb' => '<input type="checkbox" />', // Render a checkbox instead of text
            'title' => __('Title', ATBDP_TEXTDOMAIN),
            'type' => __('Type', ATBDP_TEXTDOMAIN),
            'asign' => __('Assigned to', ATBDP_TEXTDOMAIN),
            'require' => __('Required', ATBDP_TEXTDOMAIN),
            /* 'searchable'         => __( 'Is Searchable', ATBDP_TEXTDOMAIN ),*/
            'date' => __('Date', ATBDP_TEXTDOMAIN)
        );

        return $columns;

    }


    /**
     * It sets the view link of the order to the payment receipt page on the front end where the shortcode of payment receipt has been used.
     *
     * @param array $actions The array of post actions
     * @param WP_Post $post The current post post
     * @return array    $actions        It returns the array of post actions after modifying the order view link
     */
    public function set_custom_field_link($actions, WP_Post $post)
    {
        if ($post->post_type != 'atbdp_fields') return $actions;
        $actions['view'] = sprintf("<a href='%s'>%s</a>", ATBDP_Permalink::get_payment_receipt_page_link($post->ID), __('View', ATBDP_TEXTDOMAIN));
        return $actions;
    }

}