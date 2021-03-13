<?php
defined('ABSPATH') || die('Direct access is not allowed.');

if (!class_exists('ATBDP_Custom_Taxonomy')):
    class ATBDP_Custom_Taxonomy
    {

        public function __construct()
        {

            add_action('init', array($this, 'add_custom_taxonomy'), 0);
            add_filter('manage_' . ATBDP_CATEGORY . '_custom_column', array($this, 'category_rows'), 15, 3);
            add_filter('manage_edit-' . ATBDP_CATEGORY . '_columns', array($this, 'category_columns'));

            add_filter('manage_' . ATBDP_LOCATION . '_custom_column', array($this, 'location_rows'), 15, 3);
            add_filter('manage_edit-' . ATBDP_LOCATION . '_columns', array($this, 'location_columns'));
            /*show the select box form field to select an icon*/
            add_action(ATBDP_CATEGORY . '_add_form_fields', array($this, 'add_category_icon_field'), 10, 2);
            /*create the meta data*/
            add_action('created_' . ATBDP_CATEGORY, array($this, 'save_category_icon_meta'), 10, 2);

            /*Updating A Term With Meta Data*/
            add_action(ATBDP_CATEGORY . '_edit_form_fields', array($this, 'edit_category_icon_field'), 10, 2);
            // update or save the meta data of the term
            add_action('edited_' . ATBDP_CATEGORY, array($this, 'update_category_icon'), 10, 2);
            /*make the columns sortable */
            add_filter('manage_edit-' . ATBDP_CATEGORY . '_sortable_columns', array($this, 'add_category_icon_column_sortable'));
            add_filter('manage_edit-' . ATBDP_LOCATION . '_sortable_columns', array($this, 'add_location_icon_column_sortable'));

            // Modify the view link of the category tax
            add_filter(ATBDP_CATEGORY . '_row_actions', array($this, 'edit_taxonomy_view_link'), 10, 2);
            // Modify the view link of the category tax
            add_filter(ATBDP_LOCATION . '_row_actions', array($this, 'edit_taxonomy_view_link'), 10, 2);
            //to remove custom category metabox form add new listing page
            //add_action( 'admin_menu', array($this,'remove_custom_taxonomy') );


            /*show the select box form field to select an icon*/
            add_action(ATBDP_LOCATION . '_add_form_fields', array($this, 'add_extra_location_field'), 10, 2);
            /*create the meta data*/
            add_action('created_' . ATBDP_LOCATION, array($this, 'save_location_extra_meta'), 10, 2);
            /*Updating A Term With Meta Data*/
            add_action(ATBDP_LOCATION . '_edit_form_fields', array($this, 'edit_location_extra_field'), 10, 2);
            // update or save the meta data of the term
            add_action('edited_' . ATBDP_LOCATION, array($this, 'update_location_field'), 10, 2);

            // Modify the view link of the category tax
            add_filter(ATBDP_LOCATION . '_row_actions', array($this, 'edit_taxonomy_view_link'), 10, 2);

            add_filter('term_link', array($this, 'taxonomy_redirect_page'), 10, 3);
            add_action('template_redirect', array($this, 'atbdp_template_redirect'));

            add_action('wp_loaded', array($this, 'directorist_bulk_term_update'));

        }


        public function directorist_bulk_term_update(){
            if( get_option( 'directorist_bulk_term_update_v7' ) ) return;
        
            $terms = [ ATBDP_CATEGORY, ATBDP_LOCATION ];
            foreach( $terms as $term ) {
                $term_data = get_terms([
                    'taxonomy'   => $term,
                    'hide_empty' => false,
                    'orderby'    => 'date',
                    'order'      => 'DSCE',
                    ]);
                if( !empty( $term_data ) ) {
                    foreach( $term_data as $data ) {

                        $old_data = get_term_meta( $data->term_id, '_directory_type', true );

                        $results = is_array( $old_data ) ? $old_data[0] : $old_data;

                        if( !empty( $results ) ){

                            if( is_array( $old_data ) ){
                                foreach( $old_data as $single_data ){

                                    if( ! is_numeric( $single_data ) ){
                                        $term_with_directory_slug = get_term_by( 'slug', $single_data, 'atbdp_listing_types' );
                                        $id = $term_with_directory_slug->term_id;
                                        update_term_meta( $data->term_id, '_directory_type', [ $id ] );
                                    }
                                }
                            }else{
                                if( ! is_numeric( $old_data ) ){
                                    $term_with_directory_slug = get_term_by( 'slug', $old_data, 'atbdp_listing_types' );
                                    $id = $term_with_directory_slug->term_id;
                                    update_term_meta( $data->term_id, '_directory_type', [ $id ] );
                                }
                            }
                            
                        }else{
                            update_term_meta( $data->term_id, '_directory_type', [ default_directory_type() ] );
                        }
                    }
                }
            }
            update_option( 'directorist_bulk_term_update_v7', 1 );
        }


        public function atbdp_template_redirect()
        {
            $redirect_url = '';

            if (!is_feed()) {


                // If Categories Page
                if (is_tax(ATBDP_CATEGORY)) {

                    $term = get_queried_object();
                    $redirect_url = ATBDP_Permalink::atbdp_get_category_page($term);

                }


            }

            // Redirect
            if (!empty($redirect_url)) {

                wp_redirect($redirect_url);
                exit();

            }
        }

        public function taxonomy_redirect_page($url, $term, $taxonomy)
        {
            $directory_type_id       = get_post_meta( get_the_ID(), '_directory_type', true );
            $directory_type_slug     = '';
            $is_directorist_taxonomy = false;

            if ( ! empty( $directory_type_id ) ) {
                $directory_type_term = get_term_by( 'id', $directory_type_id, ATBDP_DIRECTORY_TYPE );
                $directory_type_slug = ( $directory_type_term && is_object( $directory_type_term ) ) ? $directory_type_term->slug : '';
            }

            // Categories
            if (ATBDP_CATEGORY == $taxonomy) {
                $url = ATBDP_Permalink::atbdp_get_category_page($term);
                $is_directorist_taxonomy = true;
            }

            // Location
            if (ATBDP_LOCATION == $taxonomy) {
                $url = ATBDP_Permalink::atbdp_get_location_page($term);
                $is_directorist_taxonomy = true;
            }

            // Tag
            if (ATBDP_TAGS == $taxonomy) {
                $url = ATBDP_Permalink::atbdp_get_tag_page($term);
                $is_directorist_taxonomy = true;
            }

            if ( $is_directorist_taxonomy && ! empty( $directory_type_slug ) ) {
                $url = add_query_arg( 'directory_type', $directory_type_slug, $url );
            }   

            return $url;
        }

        public function edit_taxonomy_view_link($actions, $tag)
        {
            // change the view link of ATBDP_Category
            if (ATBDP_CATEGORY == $tag->taxonomy) {
                if ($actions['view']) {
                    $actions['view'] = sprintf(
                        '<a href="%s" aria-label="%s">%s</a>',
                        ATBDP_Permalink::atbdp_get_category_page($tag)
                        ,
                        /* translators: %s: taxonomy term name */
                        esc_attr(sprintf(__('View &#8220;%s&#8221; archive', 'directorist'), $tag->name)),
                        __('View', 'directorist')
                    );
                }
            } elseif (ATBDP_LOCATION == $tag->taxonomy) {
                if ($actions['view']) {
                    $actions['view'] = sprintf(
                        '<a href="%s" aria-label="%s">%s</a>',
                        ATBDP_Permalink::atbdp_get_location_page($tag)
                        ,
                        /* translators: %s: taxonomy term name */
                        esc_attr(sprintf(__('View &#8220;%s&#8221; archive', 'directorist'), $tag->name)),
                        __('View', 'directorist')
                    );
                }
            }

            return $actions;
        }

        public function add_category_icon_column_sortable($sortable)
        {
            $sortable['ID']                            = __('ID', 'directorist');
            $sortable['atbdp_category_icon']           = 'atbdp_category_icon';
            $sortable['atbdp_category_directory_type'] = 'atbdp_category_directory_type';
            return $sortable;
        }

        public function add_location_icon_column_sortable($sortable)
        {
            $sortable['atbdp_location_directory_type'] = 'atbdp_location_directory_type';
            return $sortable;
        }

        /**
         * This function will run when our taxonomy term will will be updated
         * @param int $term_id Term id
         * @param int $tt_id Taxonomy ID
         */
        public function update_category_icon($term_id, $tt_id)
        {
            $default_listing_type = $this->default_listing_type();
            if( ! $default_listing_type ) {
                if ( isset( $_POST['directory_type'] ) ) {
                    $directory_type =  $_POST['directory_type'];
                    update_term_meta( $term_id, '_directory_type', $directory_type );
                }
            } else {
                update_term_meta( $term_id, '_directory_type', array( $default_listing_type ) );
            }

            if (!empty($_POST['category_icon'])) {
                $category_icon = sanitize_text_field($_POST['category_icon']);
                update_term_meta($term_id, 'category_icon', $category_icon);
            }

            //UPDATED CATEGORY IMAGE
            if (isset($_POST['image']) && '' !== $_POST['image']) {
                update_term_meta($term_id, 'image', (int)$_POST['image']);
            } else {
                update_term_meta($term_id, 'image', '');
            }
        }

        /**
         * This function will run when our taxonomy term will will be updated
         * @param int $term_id Term id
         * @param int $tt_id Taxonomy ID
         */
        public function update_location_field($term_id, $tt_id)
        {

            $default_listing_type = $this->default_listing_type();
            if( ! $default_listing_type ) {
                if ( isset( $_POST['directory_type'] ) ) {
                    $directory_type =  $_POST['directory_type'];
                    update_term_meta( $term_id, '_directory_type', $directory_type );
                }
            } else {
                update_term_meta( $term_id, '_directory_type', array( $default_listing_type ) );
            }
            //UPDATED location IMAGE
            if (isset($_POST['image']) && '' !== $_POST['image']) {
                update_term_meta($term_id, 'image', (int)$_POST['image']);
            } else {
                update_term_meta($term_id, 'image', '');
            }
        }

        public function edit_category_icon_field($term, $taxonomy)
        {
            // get current group
            $icon_name       = get_term_meta($term->term_id, 'category_icon', true);
            $directory_type  = get_term_meta($term->term_id, '_directory_type', true);
            $value           = ! empty( $directory_type ) ? $directory_type : array();
            $fa_icons        = get_fa_icons(); // returns the array of FA icon names
            $directory_types = get_terms( array(
                'taxonomy'   => ATBDP_TYPE,
                'hide_empty' => false,
            ) );
            $default_listing_type = $this->default_listing_type();
            if( ! $default_listing_type ) {
            ?>
            <tr class="form-field term-group-wrap">
                <th scope="row"><label for="category_icon"><?php _e('Directory Type', 'directorist'); ?></label></th>
                <td>
                <div class="directory_types-wrapper">
                        <?php
                            if( $directory_types ) {
                                foreach( $directory_types as $type ) {
                                    $checked = in_array( $type->term_id, $value ) ? 'checked' : '';
                            ?>
                            <div class="directory_type-group">
                                <input type="checkbox" class="postform" name="directory_type[]" value='<?php echo $type->term_id; ?>' id="<?php echo $type->term_id; ?>" <?php echo $checked; ?>/>
                                    <label for="<?php echo $type->term_id; ?>"><?php echo $type->name; ?></label>
                                </div>
                            <?php
                                }
                            }
                        ?>
                    </div>
                </td>
            </tr>
            <?php } ?>
            <tr class="form-field term-group-wrap">
            <th scope="row"><label for="category_icon"><?php _e('Category Icon', 'directorist'); ?></label></th>
            <td><select class="postform gg" id="category_icon" name="category_icon">
                    <?php foreach ($fa_icons as $_fa_name) : ?>
                        <option value="<?php echo $_fa_name; ?>" <?php selected($_fa_name, $icon_name, true); ?>>
                            <span>
                                <?php echo $_fa_name; ?>
                                <i class="<?php echo $_fa_name; ?>"></i>
                            </span>
                        </option>
                    <?php endforeach; ?>
                </select></td>
            </tr><?php
            //get current cat image
            $image_id = get_term_meta($term->term_id, 'image', true);
            $image_src = ($image_id) ? wp_get_attachment_url((int)$image_id) : '';
            ?>
            <tr class="form-field term-group-wrap">
                <th scope="row">
                    <label for="atbdp-categories-image-id"><?php _e('Image', 'directorist'); ?></label>
                </th>
                <td>
                    <input type="hidden" id="atbdp-categories-image-id" name="image" value="<?php echo $image_id; ?>"/>
                    <div id="atbdp-categories-image-wrapper">
                        <?php
                        if ($image_src) : ?>
                            <img src="<?php echo $image_src; ?>"/>
                            <a href="" class="remove_cat_img"><span class="fa fa-times" title="Remove it"></span></a>
                        <?php endif; ?>
                    </div>
                    <p>
                        <input type="button" class="button button-secondary" id="atbdp-categories-upload-image"
                               value="<?php _e('Add Image', 'directorist'); ?>"/>
                    </p>
                </td>
            </tr>
            <?php
        }

        public function edit_location_extra_field($term, $taxonomy)
        {
            //get current cat image
            $image_id        = get_term_meta($term->term_id, 'image', true);
            $directory_type  = get_term_meta($term->term_id, '_directory_type', true);
            $value           = ! empty( $directory_type ) ? $directory_type : array();
            $image_src       = ($image_id) ? wp_get_attachment_url((int)$image_id) : '';
            $directory_types = get_terms( array(
                'taxonomy'   => ATBDP_TYPE,
                'hide_empty' => false,
            ) );
            $default_listing_type = $this->default_listing_type();
            if( ! $default_listing_type ) {
            ?>
            <tr class="form-field term-group-wrap">
                <th scope="row"><label for="category_icon"><?php _e('Directory Type', 'directorist'); ?></label></th>
                <td>
                    <div class="directory_types-wrapper">
                        <?php
                            if( $directory_types ) {
                                foreach( $directory_types as $type ) {
                                    $checked = in_array( $type->term_id, $value ) ? 'checked' : '';
                            ?>
                            <div class="directory_type-group">
                                <input type="checkbox" class="postform" name="directory_type[]" value='<?php echo $type->term_id; ?>' id="<?php echo $type->term_id; ?>" <?php echo $checked; ?>/>
                                    <label for="<?php echo $type->term_id; ?>"><?php echo $type->name; ?></label>
                                </div>
                            <?php
                                }
                            }
                        ?>
                    </div>
                </td>
            </tr>
            <?php } ?>
            <tr class="form-field term-group-wrap">
                <th scope="row">
                    <label for="atbdp-categories-image-id"><?php _e('Image', 'directorist'); ?></label>
                </th>
                <td>
                    <input type="hidden" id="atbdp-categories-image-id" name="image" value="<?php echo $image_id; ?>"/>
                    <div id="atbdp-categories-image-wrapper">
                        <?php if ($image_src) : ?>
                            <img src="<?php echo $image_src; ?>"/>
                            <a href="" class="remove_cat_img"><span class="fa fa-times" title="Remove it"></span></a>
                        <?php endif; ?>
                    </div>
                    <p>
                        <input type="button" class="button button-secondary" id="atbdp-categories-upload-image"
                               value="<?php _e('Add Image', 'directorist'); ?>"/>
                    </p>
                </td>
            </tr>
            <?php
        }

        public function save_category_icon_meta($term_id, $tt_id)
        {
            $default_listing_type = $this->default_listing_type();
            if( ! $default_listing_type ) {
                if ( isset( $_POST['directory_type'] ) ) {
                    $directory_type =  $_POST['directory_type'];
                    add_term_meta( $term_id, '_directory_type', $directory_type, true );
                }
            } else {
                add_term_meta( $term_id, '_directory_type', array( $default_listing_type ), true );
            }

            if (!empty($_POST['category_icon'])) {
                $category_icon = sanitize_text_field($_POST['category_icon']);
                add_term_meta($term_id, 'category_icon', $category_icon, true);
            }
            if (isset($_POST['image']) && '' !== $_POST['image']) {
                add_term_meta($term_id, 'image', (int)$_POST['image'], true);
            }

        }

        public function save_location_extra_meta($term_id, $tt_id)
        {
            if (isset($_POST['image']) && '' !== $_POST['image']) {
                add_term_meta($term_id, 'image', (int)$_POST['image'], true);
            }

            $default_listing_type = $this->default_listing_type();
            if( ! $default_listing_type ) {
                if ( isset( $_POST['directory_type'] ) ) {
                    $directory_type =  $_POST['directory_type'];
                    add_term_meta($term_id, '_directory_type', $directory_type, true);
                }
            } else {
                add_term_meta( $term_id, '_directory_type', array( $default_listing_type ), true );
            }
        }


        public function add_category_icon_field($taxonomy)
        {
            $fa_icons       = get_fa_icons(); // returns the array of FA icon names
            $directory_types = get_terms( array(
                'taxonomy'   => ATBDP_TYPE,
                'hide_empty' => false,
            ) );
            $default_listing_type = $this->default_listing_type();
            if( ! $default_listing_type ) {
            ?>
            <div class="form-field term-group">
            <label for="directory_type"><?php _e('Directory Type', 'directorist'); ?></label>
            <div class="directory_types-wrapper">
                <?php
                if( $directory_types ) {
                    foreach( $directory_types as $type ) {
                ?>
                    <div class="directory_type-group">
                    <input type="checkbox" class="postform" name="directory_type[]" id="directory_type-<?php echo $type->term_id; ?>" value='<?php echo $type->term_id; ?>'/><label for="directory_type-<?php echo $type->term_id; ?>"><?php echo $type->name; ?></label>
                    </div>
                <?php
                    }
                }
                ?>
                </div>
            </div>
            <?php } ?>
            <div class="form-field term-group">
                <label for="category_icon"><?php _e('Category Icon', 'directorist'); ?></label>
                <select class="postform" id="category_icon" name="category_icon">
                    <?php foreach ($fa_icons as $_fa_name) : ?>
                        <option value="<?php echo $_fa_name; ?>">
                            <?php echo $_fa_name; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-field term-group">
                <label for="atbdp-categories-image-id"><?php _e('Image', 'directorist'); ?></label>
                <input type="hidden" id="atbdp-categories-image-id" name="image"/>
                <div id="atbdp-categories-image-wrapper"></div>
                <p>
                    <input type="button" class="button button-secondary" id="atbdp-categories-upload-image"
                           value="<?php _e('Add Image', 'directorist'); ?>"/>
                </p>
            </div>
            <?php
        }

        public function add_extra_location_field($taxonomy)
        {
            $directory_types = get_terms( array(
                'taxonomy'   => ATBDP_TYPE,
                'hide_empty' => false,
            ) );
            $default_listing_type = $this->default_listing_type();
            if( ! $default_listing_type ) {
            ?>
            <div class="form-field term-group">
                <label for="directory_type"><?php _e('Directory Type', 'directorist'); ?></label>
                <div class="directory_types-wrapper">
                <?php
                if( $directory_types ) {
                    foreach( $directory_types as $type ) {
                ?>
                    <div class="directory_type-group">
                    <input type="checkbox" class="postform" name="directory_type[]" id="directory_type-<?php echo $type->term_id; ?>" value='<?php echo $type->term_id; ?>'/><label for="directory_type-<?php echo $type->term_id; ?>"><?php echo $type->name; ?></label>
                    </div>
                <?php
                    }
                }
                ?>
                </div>
            </div>
            <?php } ?>
            <div class="form-field term-group">
                <label for="atbdp-categories-image-id"><?php _e('Image', 'directorist'); ?></label>
                <input type="hidden" id="atbdp-categories-image-id" name="image"/>
                <div id="atbdp-categories-image-wrapper"></div>
                <p>
                    <input type="button" class="button button-secondary" id="atbdp-categories-upload-image"
                           value="<?php _e('Add Image', 'directorist'); ?>"/>
                </p>
            </div>
            <?php
        }

        public function add_custom_taxonomy()
        {

            /*LOCATION*/
            $labels = array(
                'name' => _x('Locations', 'Location general name', 'directorist'),
                'singular_name' => _x('Location', 'Location singular name', 'directorist'),
                'search_items' => __('Search Location', 'directorist'),
                'all_items' => __('All Locations', 'directorist'),
                'parent_item' => __('Parent Location', 'directorist'),
                'parent_item_colon' => __('Parent Location:', 'directorist'),
                'edit_item' => __('Edit Location', 'directorist'),
                'update_item' => __('Update Location', 'directorist'),
                'add_new_item' => __('Add New Location', 'directorist'),
                'new_item_name' => __('New Location Name', 'directorist'),
                'menu_name' => __('Locations', 'directorist'),
            );

            $args = array(
                'hierarchical' => true,
                'show_in_rest' => true,
                'labels' => $labels,
                'show_ui' => true,
                'show_admin_column' => true,
                'query_var' => true,
                'public' => true,
                'show_in_nav_menus' => true,
            );


            // get the rewrite slug from the user settings, if exist use it.
            $slug = ATBDP_LOCATION;
            if (!empty($slug)) {
                $args['rewrite'] = array(
                    'slug' => $slug,
                );
            }

            /*CATEGORY*/

            $labels2 = array(
                'name' => _x('Categories', 'Category general name', 'directorist'),
                'singular_name' => _x('Category', 'Category singular name', 'directorist'),
                'search_items' => __('Search category', 'directorist'),
                'all_items' => __('All categories', 'directorist'),
                'parent_item' => __('Parent category', 'directorist'),
                'parent_item_colon' => __('Parent category:', 'directorist'),
                'edit_item' => __('Edit category', 'directorist'),
                'update_item' => __('Update category', 'directorist'),
                'add_new_item' => __('Add New category', 'directorist'),
                'new_item_name' => __('New category Name', 'directorist'),
                'menu_name' => __('Categories', 'directorist'),
            );

            $args2 = array(
                'hierarchical' => true,
                'labels' => $labels2,
                'show_ui' => true,
                'show_admin_column' => true,
                'query_var' => true,
                'public' => true,
                'show_in_nav_menus' => true,
            );

            // get the rewrite slug from the user settings, if exist use it.
            $slug = ATBDP_CATEGORY;
            if (!empty($slug)) {
                $args2['rewrite'] = array(
                    'slug' => $slug,
                );
            }


            /*TAGS*/
            $labels3 = array(
                'name' => _x('Tags', 'Tag general name', 'directorist'),
                'singular_name' => _x('Tag', 'Tag singular name', 'directorist'),
                'search_items' => __('Search tag', 'directorist'),
                'all_items' => __('All Tags', 'directorist'),
                'parent_item' => __('Parent tag', 'directorist'),
                'parent_item_colon' => __('Parent tag:', 'directorist'),
                'edit_item' => __('Edit tag', 'directorist'),
                'update_item' => __('Update tag', 'directorist'),
                'add_new_item' => __('Add New tag', 'directorist'),
                'new_item_name' => __('New tag Name', 'directorist'),
                'menu_name' => __('Tags', 'directorist'),
            );

            $capabilities = array(
                'assign_terms' => 'publish_at_biz_dirs'
            );
            $args3 = array(
                'hierarchical' => false,
                'labels' => $labels3,
                'show_ui' => true,
                'show_admin_column' => true,
                'query_var' => true,
                'public' => true,
                'show_in_nav_menus' => true,
                'capabilities' => $capabilities,
            );

            // get the rewrite slug from the user settings, if exist use it.
            $slug = ATBDP_TAGS;
            if (!empty($slug)) {
                $args2['rewrite'] = array(
                    'slug' => $slug,
                );
            }

            register_taxonomy(ATBDP_LOCATION, ATBDP_POST_TYPE, $args);

            register_taxonomy(ATBDP_CATEGORY, ATBDP_POST_TYPE, $args2);

            register_taxonomy(ATBDP_TAGS, ATBDP_POST_TYPE, $args3);

        }

        public function category_columns($original_columns)
        {
            $new_columns = $original_columns;
            array_splice($new_columns, 1); // in this way we could place our columns on the first place after the first checkbox.
            $enable_multi_directory = get_directorist_option( 'enable_multi_directory' );

            $new_columns['ID']                                = __('ID', 'directorist');

            $new_columns['atbdp_category_icon']               = __('Icon', 'directorist');
            if( ! empty( $enable_multi_directory ) ) {
                $new_columns['atbdp_category_directory_type'] = __('Directory Type', 'directorist');
            }


            return array_merge($new_columns, $original_columns);
        }

        public function location_columns($original_columns)
        {
            $new_columns = $original_columns;
            array_splice( $new_columns, 2 ); // in this way we could place our columns on the first place after the first checkbox.
            $enable_multi_directory = get_directorist_option( 'enable_multi_directory' );
            if( ! empty( $enable_multi_directory ) ) {
                $new_columns['atbdp_location_directory_type'] = __('Directory Type', 'directorist');
            }

            return array_merge( $new_columns, $original_columns );
        }

        /**
         * Print data for custom rows in our custom category page
         * @see apply_filters( "manage_{$this->screen->taxonomy}_custom_column", '', $column_name, $tag->term_id );
         * @param string $empty_string
         * @param int $column_name
         * @param int $term_id
         * @return mixed
         */
        public function category_rows($empty_string, $column_name, $term_id)
        {
            $icon           = get_term_meta($term_id, 'category_icon', true);
            $directory_type = get_term_meta($term_id, '_directory_type', true);
            $directory_type = ! empty( $directory_type ) ? $directory_type : array();
            $directory_type = is_array( $directory_type ) ? $directory_type : array( $directory_type );

            /* $icon_type = array();
            if (!empty($icon)){
                $icon_type = explode('-', $icon);
            }
            if (!empty($icon_type[0]) && $icon_type[0] === 'fa'){
                $class = 'fa '.$icon;
            }elseif (!empty($icon_type[0]) && $icon_type[0] === 'la'){
                $class = 'la '.$icon;
            }else{
                $class = 'none';
            } */

            if ($column_name == 'ID') {
                return $term_id;
            }
            if ($column_name == 'atbdp_category_icon') {

                return !empty($icon) ? "<i class='{$icon}'></i>" : ' ';
            }

            if ($column_name == 'atbdp_category_directory_type') {
                if( $directory_type ) {
                    $listing_type = array();
                    foreach( $directory_type as $type ) {
                        if( is_numeric( $type ) ) {
                            $get_type = get_term_by( 'term_id', $type, ATBDP_TYPE );
                            $listing_type[] = ! empty( $get_type ) ? $get_type->slug : '';
                        } else {
                            $listing_type[] = $type;
                        }
                    }
                    return implode( ", ", $listing_type );
                }
            }


            return $empty_string;
        }

        public function location_rows($empty_string, $column_name, $term_id)
        {
            $directory_type = get_term_meta($term_id, '_directory_type', true);

            if ($column_name == 'atbdp_location_directory_type') {

                if( $directory_type ) {

                    $listing_type = array();
                    foreach( $directory_type as $type ) {

                        if( is_numeric( $type ) ) {
                            $get_type = get_term_by( 'term_id', $type, ATBDP_TYPE );
                            $listing_type[] = ! empty( $get_type ) ? $get_type->slug : '';
                        } else {

                            $listing_type[] = $type;

                        }

                    }

                    return implode( ", ", $listing_type );
                }
            }

            return $empty_string;
        }


        public function display_terms_of_post($post_id, $term_name = 'category')
        {
            global $post;
            $terms = get_the_terms($post_id, $term_name);

            /* If terms were found. */
            if (!empty($terms)) {

                $out = array();

                /* Loop through each term, linking to the 'edit posts' page for the specific term. */
                foreach ($terms as $term) {
                    $out[] = sprintf('<a href="%s">%s</a>',
                        esc_url(add_query_arg(array('post_type' => $post->post_type, $term_name => $term->slug), 'edit.php')),
                        esc_html(sanitize_term_field('name', $term->name, $term->term_id, $term_name, 'display'))
                    );
                }

                /* Join the terms, separating them with a comma. */
                echo join(', ', $out);
            } /* If no terms were found, output a default message. */
            else {
                _e('No Category', 'directorist');
            }
        }

        /**
         * It returns a single high level term object of the given taxonomy
         * @TODO; improve it later if possible
         * @param int $post_id The post ID whose taxonomy we are searching through for a term
         * @param string $taxonomoy The name of the taxonomy whose term we are looking form
         * @return WP_Term | false It returns a term object on success and false on failure
         */
        public function get_one_high_level_term($post_id, $taxonomoy = 'category')
        {
            $top_category = '';
            $terms = get_the_terms($post_id, $taxonomoy);
            if (!empty($terms)) {
                foreach ($terms as $term) {
                    if (!empty($top_category)) break; // vail out of the loop if we have found parent..
                    if ($term->parent == 0) $top_category = $term;

                }
                if (!empty($top_category)) return $top_category;
            }
            return false;

        }

        /**
         * It returns a single deepest level term object of the given taxonomy
         * @TODO; improve it later if possible
         * @param int $post_id The post ID whose taxonomy we are searching through for a term
         * @param string $taxonomy The name of the taxonomy whose term we are looking form
         * @return WP_Term | false It returns a term object on success and false on failure
         */
        public function get_one_deepest_level_term($post_id, $taxonomy = 'category')
        {

            // get all taxes for the current listing
            $locations = get_the_terms($post_id, $taxonomy);

            // wrapper to hide any errors from top level categories or listings without locations
            if ($locations && !is_wp_error($locations)) {

                // loop through each location
                foreach ($locations as $location) {
                    // get the children (if any) of the current $location
                    $children = get_categories(array('taxonomy' => $taxonomy, 'parent' => $location->term_id));

                    if (count($children) == 0) {
                        // if no children, then this ($location) is the deepest level location, if we want multiple deepest level location then we can sev the
                        return $location;
                    }
                }
            }
            return false;

        }

        public function get_listing_types() {
            $listing_types = array();
            $args          = array(
                'taxonomy'   => ATBDP_TYPE,
                'hide_empty' => false
            );
            $all_types     = get_terms( $args );

            foreach ( $all_types as $type ) {
                $listing_types[ $type->term_id ] = [
                    'term' => $type,
                    'name' => $type->name,
                    'data' => get_term_meta( $type->term_id, 'general_config', true ),
                ];
            }
            return $listing_types;
        }

        public function get_current_listing_type() {
            $listing_types      = $this->get_listing_types();
            $listing_type_count = count( $listing_types );

            $current = !empty($listing_types) ? array_key_first( $listing_types ) : '';

            if ( isset( $_GET['directory_type'] ) ) {
                $current = $_GET['directory_type'];
            }
            else {

                foreach ( $listing_types as $id => $type ) {
                    $is_default = get_term_meta( $id, '_default', true );
                    if ( $is_default ) {
                        $current = $id;
                        break;
                    }
                }
            }

            $term = get_term_by( 'id', $current, ATBDP_TYPE );
            $current = $term->slug;

            return $current;
        }

        public function default_listing_type() {
            $enable_multi_directory = get_directorist_option( 'enable_multi_directory' );
            if( ( 1 == count( $this->get_listing_types() ) ) || empty( $enable_multi_directory) ) {
                return $this->get_current_listing_type();
            }
        }
    }
endif;