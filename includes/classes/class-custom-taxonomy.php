<?php
defined('ABSPATH') || die( 'Direct access is not allowed.' );

if ( !class_exists('ATBDP_Custom_Taxonomy') ):
class ATBDP_Custom_Taxonomy {

    public function __construct() {

        add_action( 'init', array($this, 'add_custom_taxonomy'), 0 );
        add_filter('manage_' . ATBDP_CATEGORY . '_custom_column', array($this, 'category_rows'), 15, 3);
        add_filter('manage_edit-' . ATBDP_CATEGORY . '_columns',  array($this, 'category_columns'));

        /*show the select box form field to select an icon*/
        add_action( ATBDP_CATEGORY . '_add_form_fields', array($this, 'add_category_icon_field'), 10, 2 );
        /*create the meta data*/
        add_action( 'created_' . ATBDP_CATEGORY, array($this, 'save_category_icon_meta'), 10, 2 );

        /*Updating A Term With Meta Data*/
        add_action( ATBDP_CATEGORY . '_edit_form_fields', array($this, 'edit_category_icon_field'), 10, 2 );
        // update or save the meta data of the term
        add_action( 'edited_'. ATBDP_CATEGORY, array($this, 'update_category_icon'), 10, 2 );
        /*make the columns sortable */
        add_filter( 'manage_edit-' . ATBDP_CATEGORY . '_sortable_columns', array($this, 'add_category_icon_column_sortable') );

        // Modify the view link of the category tax
        add_filter(ATBDP_CATEGORY.'_row_actions', array($this, 'edit_taxonomy_view_link'), 10, 2);
        // Modify the view link of the category tax
        add_filter(ATBDP_LOCATION.'_row_actions', array($this, 'edit_taxonomy_view_link'), 10, 2);
        //to remove custom category metabox form add new listing page
        //add_action( 'admin_menu', array($this,'remove_custom_taxonomy') );



    }

    public function edit_taxonomy_view_link($actions, $tag)
    {
        // change the view link of ATBDP_Category
        if (ATBDP_CATEGORY == $tag->taxonomy){
            if ($actions['view']){
                $actions['view'] = sprintf(
                    '<a href="%s" aria-label="%s">%s</a>',
                    ATBDP_Permalink::get_category_archive($tag)
                    ,
                    /* translators: %s: taxonomy term name */
                    esc_attr( sprintf( __( 'View &#8220;%s&#8221; archive', ATBDP_TEXTDOMAIN ), $tag->name ) ),
                    __( 'View', ATBDP_TEXTDOMAIN )
                );
            }
        }elseif(ATBDP_LOCATION == $tag->taxonomy){
            if ($actions['view']){
                $actions['view'] = sprintf(
                    '<a href="%s" aria-label="%s">%s</a>',
                    ATBDP_Permalink::get_location_archive($tag)
                    ,
                    /* translators: %s: taxonomy term name */
                    esc_attr( sprintf( __( 'View &#8220;%s&#8221; archive', ATBDP_TEXTDOMAIN ), $tag->name ) ),
                    __( 'View', ATBDP_TEXTDOMAIN )
                );
            }
        }

        return $actions;
    }

    public function add_category_icon_column_sortable($sortable)
    {
        $sortable[ 'ID' ] = __('ID', ATBDP_TEXTDOMAIN);
        $sortable[ 'atbdp_category_icon' ] = 'atbdp_category_icon';
        $sortable[ 'atbdp_category_icon_name' ] = 'atbdp_category_icon_name';
        return $sortable;
    }

    /**
     * This function will run when our taxonomy term will will be updated
     * @param int $term_id Term id
     * @param int $tt_id   Taxonomy ID
     */
    public function update_category_icon($term_id, $tt_id)
    {

        if( !empty( $_POST['category_icon'] ) ){
            $category_icon = sanitize_text_field( $_POST['category_icon'] );
            update_term_meta( $term_id, 'category_icon', $category_icon );
        }
    }

    public function edit_category_icon_field($term, $taxonomy)
    {
        // get current group
        $icon_name = get_term_meta( $term->term_id, 'category_icon', true );

        $fa_icons = get_fa_icons_full(); // returns the array of FA icon names
        ?>
        <tr class="form-field term-group-wrap">
        <th scope="row"><label for="category_icon"><?php _e( 'Category Icon', ATBDP_TEXTDOMAIN ); ?></label></th>
        <td><select class="postform" id="category_icon" name="category_icon">
                <?php foreach ($fa_icons as $_fa_name => $unicode) : ?>
                    <option value="<?php echo $_fa_name; ?>" <?php selected($_fa_name, $icon_name, true); ?>>
                        <?php echo $_fa_name; ?>
                    </option>
                <?php endforeach; ?>
            </select></td>
        </tr><?php
    }

    public function save_category_icon_meta( $term_id, $tt_id)
    {
        if( !empty( $_POST['category_icon'] ) ){
            $category_icon = sanitize_text_field( $_POST['category_icon'] );
            add_term_meta( $term_id, 'category_icon', $category_icon, true );
        }
    }


    public function add_category_icon_field($taxonomy)
    {
        $fa_icons = get_fa_icons_full(); // returns the array of FA icon names
        ?>
        <div class="form-field term-group">
            <label for="category_icon"><?php _e('Category Icon', ATBDP_TEXTDOMAIN); ?></label>
            <select class="postform" id="category_icon" name="category_icon">
                <?php foreach ($fa_icons as $_fa_name => $unicode) : ?>
                    <option value="<?php echo $_fa_name; ?>">
                        <?php echo $_fa_name; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
<?php
    }

    public function add_custom_taxonomy() {

        /*LOCATION*/
        $labels = array(
            'name'              => _x( 'Directory Locations', 'Location general name', ATBDP_TEXTDOMAIN ),
            'singular_name'     => _x( 'Directory Location', 'Location singular name', ATBDP_TEXTDOMAIN ),
            'search_items'      => __( 'Search Location', ATBDP_TEXTDOMAIN ),
            'all_items'         => __( 'All Locations', ATBDP_TEXTDOMAIN ),
            'parent_item'       => __( 'Parent Location', ATBDP_TEXTDOMAIN ),
            'parent_item_colon' => __( 'Parent Location:', ATBDP_TEXTDOMAIN ),
            'edit_item'         => __( 'Edit Location', ATBDP_TEXTDOMAIN ),
            'update_item'       => __( 'Update Location', ATBDP_TEXTDOMAIN ),
            'add_new_item'      => __( 'Add New Location', ATBDP_TEXTDOMAIN ),
            'new_item_name'     => __( 'New Location Name', ATBDP_TEXTDOMAIN ),
            'menu_name'         => __( 'Directory Locations', ATBDP_TEXTDOMAIN ),
        );

        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'public'            => true,
            'show_in_nav_menus' => true,
        );


        // get the rewrite slug from the user settings, if exist use it.
        $slug = get_directorist_option('atbdp_loc_slug', ATBDP_LOCATION);
        if (!empty($slug)) {
            $args['rewrite'] = array(
                'slug'=> $slug,
            );
        }

        /*CATEGORY*/

        $labels2 = array(
            'name'              => _x( 'Directory categories', 'Category general name', ATBDP_TEXTDOMAIN ),
            'singular_name'     => _x( 'Directory category', 'Category singular name', ATBDP_TEXTDOMAIN ),
            'search_items'      => __( 'Search category', ATBDP_TEXTDOMAIN ),
            'all_items'         => __( 'All categories', ATBDP_TEXTDOMAIN ),
            'parent_item'       => __( 'Parent category', ATBDP_TEXTDOMAIN ),
            'parent_item_colon' => __( 'Parent category:', ATBDP_TEXTDOMAIN ),
            'edit_item'         => __( 'Edit category', ATBDP_TEXTDOMAIN ),
            'update_item'       => __( 'Update category', ATBDP_TEXTDOMAIN ),
            'add_new_item'      => __( 'Add New category', ATBDP_TEXTDOMAIN ),
            'new_item_name'     => __( 'New category Name', ATBDP_TEXTDOMAIN ),
            'menu_name'         => __( 'Directory Categories', ATBDP_TEXTDOMAIN ),
        );

        $args2 = array(
            'hierarchical'      => true,
            'labels'            => $labels2,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'public'            => true,
            'show_in_nav_menus' => true,

        );

        // get the rewrite slug from the user settings, if exist use it.
        $slug = get_directorist_option('atbdp_cat_slug', ATBDP_CATEGORY);
        if (!empty($slug)) {
            $args2['rewrite'] = array(
                'slug'=> $slug,
            );
        }



        /*TAGS*/
        $labels3 = array(
            'name'              => _x( 'Directory Tags', 'Tag general name', ATBDP_TEXTDOMAIN ),
            'singular_name'     => _x( 'Directory Tag', 'Tag singular name', ATBDP_TEXTDOMAIN ),
            'search_items'      => __( 'Search tag', ATBDP_TEXTDOMAIN ),
            'all_items'         => __( 'All Tags', ATBDP_TEXTDOMAIN ),
            'parent_item'       => __( 'Parent tag', ATBDP_TEXTDOMAIN ),
            'parent_item_colon' => __( 'Parent tag:', ATBDP_TEXTDOMAIN ),
            'edit_item'         => __( 'Edit tag', ATBDP_TEXTDOMAIN ),
            'update_item'       => __( 'Update tag', ATBDP_TEXTDOMAIN ),
            'add_new_item'      => __( 'Add New tag', ATBDP_TEXTDOMAIN ),
            'new_item_name'     => __( 'New tag Name', ATBDP_TEXTDOMAIN ),
            'menu_name'         => __( 'Directory Tags', ATBDP_TEXTDOMAIN ),
        );

        $args3 = array(
            'hierarchical'      => false,
            'labels'            => $labels3,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'public'            => true,
            'show_in_nav_menus' => true,
        );

        // get the rewrite slug from the user settings, if exist use it.
        $slug = get_directorist_option('atbdp_tag_slug', ATBDP_TAGS);
        if (!empty($slug)) {
            $args2['rewrite'] = array(
                'slug'=> $slug,
            );
        }

        register_taxonomy( ATBDP_LOCATION, ATBDP_POST_TYPE, $args );

        register_taxonomy( ATBDP_CATEGORY, ATBDP_POST_TYPE, $args2 );

        register_taxonomy( ATBDP_TAGS, ATBDP_POST_TYPE, $args3 );

    }



    public function category_columns($original_columns) {
        $new_columns = $original_columns;
        array_splice($new_columns, 1); // in this way we could place our columns on the first place after the first checkbox.

        $new_columns['ID'] = __('ID', ATBDP_TEXTDOMAIN);

        $new_columns['atbdp_category_icon'] = __('Icon', ATBDP_TEXTDOMAIN);

        $new_columns['atbdp_category_icon_name'] = __('Icon Name', ATBDP_TEXTDOMAIN);


        return array_merge($new_columns, $original_columns);
    }

    /**
     * Print data for custom rows in our custom category page
     * @see apply_filters( "manage_{$this->screen->taxonomy}_custom_column", '', $column_name, $tag->term_id );
     * @param string $empty_string
     * @param int $column_name
     * @param int $term_id
     * @return mixed
     */
    public function category_rows($empty_string, $column_name, $term_id) {
        $icon= get_term_meta($term_id, 'category_icon', true);
        if ($column_name == 'ID') {
            return $term_id;
        }
        if ($column_name == 'atbdp_category_icon') {

            return !empty($icon) ? "<i class='fa {$icon}'></i>" : ' ';
        }

            if ($column_name == 'atbdp_category_icon_name') {
                return !empty($icon) ? $icon : ' ';
            }


        return $empty_string;
    }






    public function display_terms_of_post( $post_id, $term_name = 'category' ) {
        global $post;
        $terms = get_the_terms( $post_id, $term_name );

        /* If terms were found. */
        if ( !empty( $terms ) ) {

            $out = array();

            /* Loop through each term, linking to the 'edit posts' page for the specific term. */
            foreach ( $terms as $term ) {
                $out[] = sprintf( '<a href="%s">%s</a>',
                    esc_url( add_query_arg( array( 'post_type' => $post->post_type, $term_name => $term->slug ), 'edit.php' ) ),
                    esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, $term_name, 'display' ) )
                );
            }

            /* Join the terms, separating them with a comma. */
            echo join( ', ', $out );
        }

        /* If no terms were found, output a default message. */
        else {
            _e( 'No Category', ATBDP_TEXTDOMAIN );
        }
    }
    /**
    * It returns a single high level term object of the given taxonomy
     *@TODO; improve it later if possible
     *@param int $post_id The post ID whose taxonomy we are searching through for a term
     *@param string $taxonomoy The name of the taxonomy whose term we are looking form
     *@return WP_Term | false It returns a term object on success and false on failure
    */
    public function get_one_high_level_term($post_id, $taxonomoy='category')
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
     *@TODO; improve it later if possible
     *@param int $post_id The post ID whose taxonomy we are searching through for a term
     *@param string $taxonomy The name of the taxonomy whose term we are looking form
     *@return WP_Term | false It returns a term object on success and false on failure
     */
    public function get_one_deepest_level_term($post_id, $taxonomy='category')
    {

        // get all taxes for the current listing
        $locations = get_the_terms( $post_id, $taxonomy );

        // wrapper to hide any errors from top level categories or listings without locations
        if ( $locations && ! is_wp_error( $locations ) ) {

            // loop through each location
            foreach ($locations as $location) {
                // get the children (if any) of the current $location
                $children = get_categories(array('taxonomy' => $taxonomy, 'parent' => $location->term_id));

                if (count($children) == 0) {
                    // if no children, then this ($location) is the deepest level location, if we want multiple deepest level location then we can sev the
                    return $location ;
                }
            }
        }
        return false;

    }


}


endif;