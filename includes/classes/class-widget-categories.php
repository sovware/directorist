<?php
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}
if (!class_exists('BD_Categories_Widget')) {
    /**
     * Adds BD_Popular_Listing_Widget widget.
     */
    class BD_Categories_Widget extends WP_Widget
    {

        /**
         * Register widget with WordPress.
         */
        function __construct()
        {
            $widget_options = array(
                'classname' => 'atbd_widget',
                'description' => esc_html__('You can show Categories by this widget', 'directorist'),
            );
            parent::__construct(
                'bdcw_widget', // Base ID
                esc_html__('Directorist - Categories', 'directorist'), // Name
                $widget_options // Args
            );
        }

        /**
         * Front-end display of widget.
         *
         * @see WP_Widget::widget()
         *
         * @param array $args Widget arguments.
         * @param array $instance Saved values from database.
         */
        public function widget($args, $instance)
        {
            $allowWidget = apply_filters('atbdp_allow_categories_widget', true);
            if (!$allowWidget) return;
            wp_enqueue_script('loc_cat_assets');
            $title = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Directorist Categories', 'directorist');

            $query_args = array(
                'template'       => !empty( $instance['display_as'] ) ? sanitize_text_field( $instance['display_as'] ) : 'list',
                'parent'         => !empty( $instance['parent'] ) ? (int) $instance['parent'] : 0,
                'term_id'        => !empty( $instance['parent'] ) ? (int) $instance['parent'] : 0,
                'hide_empty'     => !empty( $instance['hide_empty'] ) ? 1 : 0,
                'orderby'        => !empty( $instance['order_by'] ) ? sanitize_text_field( $instance['order_by'] ) : 'id',
                'order'          => !empty( $instance['order'] ) ? sanitize_text_field( $instance['order'] ) : 'asc',
                'max_number'     => !empty( $instance['max_number'] ) ? $instance['max_number'] : '',
                'show_count'     => !empty( $instance['show_count'] ) ? 1 : 0,
                'single_only'    => !empty( $instance['single_only'] ) ? 1 : 0,
                'pad_counts'     => true,
                'immediate_category' => ! empty( $instance['immediate_category'] ) ? 1 : 0,
                'active_term_id' => 0,
                'ancestors'      => array()
            );


            if( $query_args['immediate_category'] ) {

                $term_slug = get_query_var( ATBDP_CATEGORY );
            
                if( '' != $term_slug ) {
                $term = get_term_by( 'slug', $term_slug, ATBDP_CATEGORY );
                $query_args['active_term_id'] = $term->term_id;
            
                $query_args['ancestors'] = get_ancestors( $query_args['active_term_id'], 'atbdp_categories' );
                $query_args['ancestors'][] = $query_args['active_term_id'];
                $query_args['ancestors'] = array_unique( $query_args['ancestors'] );
                }
            
            }
            
            if( 'dropdown' == $query_args['template'] ) {
                $categories = $this->dropdown_categories( $query_args );
            } else {
                $categories = $this->atbdp_categories_list( $query_args );
            }

            $template_path = atbdp_get_widget_template_path( 'categories' );
            if ( file_exists( $template_path ) ) {
                if(!empty($query_args['single_only'])) {
                    if(is_singular(ATBDP_POST_TYPE)) {
                        include $template_path;
                    }
                } else {
                    include $template_path;
                }
                
            }

        }

        /**
         * Back-end widget form.
         *
         * @see WP_Widget::form()
         *
         * @param array $instance Previously saved values from database.
         * @return void
         */
        public function form($instance)
        {

            $values = array(
                    'title'                 => __('Categories', 'directorist'),
                    'display_as'            => 'list',
                    'hide_empty'            => 0,
                    'show_count'            => 0,
                    'single_only'           => 0,
                    'parent'                => 0,
                    'immediate_category'    => 0,
                    'order_by'              => 'id',
                    'order'                 => 'asc'
            );

            $instance   = wp_parse_args((array)$instance,$values);
            $title      = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Categories', 'directorist');
            $max_number = !empty($instance['max_number']) ? esc_html($instance['max_number']) : '';
            ?>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id('title') ); ?>"><?php esc_attr_e('Title:', 'directorist'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                       name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text"
                       value="<?php echo esc_attr($title); ?>">
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'display_as' ); ?>"><?php _e( 'View as', 'directorist' ); ?></label>
                <select class="widefat" id="<?php echo $this->get_field_id( 'display_as' ); ?>" name="<?php echo $this->get_field_name( 'display_as' ); ?>">
                    <option value="list" <?php selected( $instance['display_as'], 'list' ); ?>><?php _e( 'List', 'directorist' ); ?></option>
                    <option value="dropdown" <?php selected( $instance['display_as'], 'dropdown' ); ?>><?php _e( 'Dropdown', 'directorist' ); ?></option>
                </select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'parent' ); ?>"><?php _e( 'Select Parent', 'directorist' ); ?></label>
                <?php
                wp_dropdown_categories( array(
                    'show_option_none'  => '-- '.__( 'Select Parent', 'directorist' ).' --',
                    'option_none_value' => 0,
                    'taxonomy'          => ATBDP_CATEGORY,
                    'name' 			    => $this->get_field_name( 'parent' ),
                    'class'             => 'widefat',
                    'orderby'           => 'name',
                    'selected'          => (int) $instance['parent'],
                    'hierarchical'      => true,
                    'depth'             => 10,
                    'show_count'        => false,
                    'hide_empty'        => false,
                    'max_number'        => ''
                ) );
                ?>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'order_by' ); ?>"><?php _e( 'Order By', 'directorist' ); ?></label>
                <select class="widefat" id="<?php echo $this->get_field_id( 'order_by' ); ?>" name="<?php echo $this->get_field_name( 'order_by' ); ?>">
                    <option value="id" <?php selected( $instance['order_by'], 'id' ); ?>><?php _e( 'Id', 'directorist' ); ?></option>
                    <option value="count" <?php selected( $instance['order_by'], 'count' ); ?>><?php _e( 'Count', 'directorist' ); ?></option>
                    <option value="name" <?php selected( $instance['order_by'], 'name' ); ?>><?php _e( 'Name', 'directorist' ); ?></option>
                    <option value="slug" <?php selected( $instance['order_by'], 'slug' ); ?>><?php _e( 'Slug', 'directorist' ); ?></option>
                </select>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'order' ); ?>"><?php _e( 'Sort By', 'directorist' ); ?></label>
                <select class="widefat" id="<?php echo $this->get_field_id( 'order' ); ?>" name="<?php echo $this->get_field_name( 'order' ); ?>">
                    <option value="asc" <?php selected( $instance['order'], 'asc' ); ?>><?php _e( 'Ascending', 'directorist' ); ?></option>
                    <option value="desc" <?php selected( $instance['order'], 'desc' ); ?>><?php _e( 'Descending', 'directorist' ); ?></option>
                </select>
            </p>

            <p>
                <label for="<?php echo esc_attr($this->get_field_id('max_number')); ?>"><?php esc_attr_e('Maximum Number', 'directorist'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('max_number')); ?>"
                       name="<?php echo esc_attr($this->get_field_name('max_number')); ?>" type="text"
                       value="<?php echo esc_attr($max_number); ?>">
            </p>

            <p>
                <input <?php checked( $instance['immediate_category'],1 ); ?> id="<?php echo $this->get_field_id( 'immediate_category' ); ?>" name="<?php echo $this->get_field_name( 'immediate_category' ); ?>" value="1" type="checkbox" />
                <label for="<?php echo $this->get_field_id( 'immediate_category' ); ?>"><?php _e( 'Show all the top-level categories only', 'directorist' ); ?></label>
            </p>

            <p>
                <input <?php checked( $instance['hide_empty'],1 ); ?> id="<?php echo $this->get_field_id( 'hide_empty' ); ?>" name="<?php echo $this->get_field_name( 'hide_empty' ); ?>" value="1" type="checkbox" />
                <label for="<?php echo $this->get_field_id( 'hide_empty' ); ?>"><?php _e( 'Hide empty categories', 'directorist' ); ?></label>
            </p>

            <p>
                <input <?php checked( $instance['show_count'],1 ); ?> id="<?php echo $this->get_field_id( 'show_count' ); ?>" name="<?php echo $this->get_field_name( 'show_count' ); ?>" value="1" type="checkbox" />
                <label for="<?php echo $this->get_field_id( 'show_count' ); ?>"><?php _e( 'Display listing counts', 'directorist' ); ?></label>
            </p>

            <p>
                <input <?php checked( $instance['single_only'],1 ); ?> id="<?php echo $this->get_field_id( 'single_only' ); ?>" name="<?php echo $this->get_field_name( 'single_only' ); ?>" value="1" type="checkbox" />
                <label for="<?php echo $this->get_field_id( 'single_only' ); ?>"><?php _e( 'Display only on single listing', 'directorist' ); ?></label>
            </p>
            <?php
        }

        /**
         * Sanitize widget form values as they are saved.
         *
         * @see WP_Widget::update()
         *
         * @param array $new_instance Values just sent to be saved.
         * @param array $old_instance Previously saved values from database.
         *
         * @return array Updated safe values to be saved.
         */
        public function update( $new_instance, $old_instance ) {

            $instance = $old_instance;

            $instance['title']              = ! empty( $new_instance['title'] ) ? strip_tags( $new_instance['title'] ) : '';
            $instance['display_as']         = isset( $new_instance['display_as'] ) ? sanitize_text_field( $new_instance['display_as'] ) : 'list';
            $instance['order_by']           = isset( $new_instance['order_by'] ) ? sanitize_text_field( $new_instance['order_by'] ) : 'id';
            $instance['order']              = isset( $new_instance['order'] ) ? sanitize_text_field( $new_instance['order'] ) : 'asc';
            $instance['parent']             = isset( $new_instance['parent'] ) ? (int) $new_instance['parent'] : 0;
            $instance['immediate_category'] = isset( $new_instance['immediate_category'] ) ? 1 : 0;
            $instance['hide_empty']         = isset( $new_instance['hide_empty'] ) ? 1 : 0;
            $instance['show_count']         = isset( $new_instance['show_count'] ) ? 1 : 0;
            $instance['single_only']        = isset( $new_instance['single_only'] ) ? 1 : 0;
            $instance['max_number']         = isset( $new_instance['max_number'] ) ? $new_instance['max_number'] : '';

            return $instance;

        }

        public function atbdp_categories_list( $settings ) {


            if( $settings['immediate_category'] ) {

                if( $settings['term_id'] > $settings['parent'] && ! in_array( $settings['term_id'], $settings['ancestors'] ) ) {
                    return;
                }

            }

            $args = array(
                'taxonomy'     => ATBDP_CATEGORY,
                'orderby'      => $settings['orderby'],
                'order'        => $settings['order'],
                'hide_empty'   => $settings['hide_empty'],
                'parent'       => $settings['term_id'],
                'hierarchical' => !empty( $settings['hide_empty'] ) ? true : false,
                'child_of'     => 0,
                'number'       => !empty($settings['max_number']) ? $settings['max_number'] : ''
            );

            $terms = get_terms( $args );
            $parent = $args['parent'];
            $child_class = !empty($parent) ? 'atbdp_child_category' : 'atbdp_parent_category';
            $html = '';
            if( count( $terms ) > 0 ) {
                $i = 1;
                $html .= '<ul class="' .$child_class. '">';
                foreach( $terms as $term ) {
                    $child_category = get_term_children($term->term_id,ATBDP_CATEGORY);
                    $plus_icon = (!empty($child_category) && empty($parent) )? '<span class="'.atbdp_icon_type().'-plus"></span>' : '';


                    $icon = get_term_meta($term->term_id,'category_icon',true);
                    $icon_type = substr($icon, 0,2);
                    $settings['term_id'] = $term->term_id;
                    $span = empty($parent)  ? '<span class="'. $icon .'"></span>' : '';

                    $count = 0;
                    if( ! empty( $settings['hide_empty'] ) || ! empty( $settings['show_count'] ) ) {
                        $count = atbdp_listings_count_by_category( $term->term_id );

                        if( ! empty( $settings['hide_empty'] ) && 0 == $count ) continue;
                    }

                    $html .= '<li>';
                    $html .= '<a href="' . ATBDP_Permalink::atbdp_get_category_page( $term ) . '">'. $span .'';
                    $html .= $term->name;
                    if( ! empty( $settings['show_count'] ) ) {
                        $expired_listings = atbdp_get_expired_listings(ATBDP_CATEGORY, $term->term_id);
                        $number_of_expired = $expired_listings->post_count;
                        $number_of_expired = !empty($number_of_expired)?$number_of_expired:'0';
                        $totat = ($count)?($count-$number_of_expired):$count;
                        $html .= ' (' . $totat . ')';
                    }
                    $html .= '</a>'. $plus_icon . '';
                    $html .= $this->atbdp_categories_list( $settings );
                    $html .= '</li>';
                    if(!empty($args['number'])) {
                        if( $i++ == $args['number'] ) break;
                    }
                }
                $html .= '</ul>';

            }

            return $html;

        }

        public function dropdown_categories( $settings, $prefix = '' ) {

            if( $settings['immediate_category'] ) {

                if( $settings['term_id'] > $settings['parent'] && ! in_array( $settings['term_id'], $settings['ancestors'] ) ) {
                    return;
                }

            }

            $term_slug = get_query_var( ATBDP_CATEGORY );

            $args = array(
                'taxonomy'     => ATBDP_CATEGORY,
                'orderby'      => $settings['orderby'],
                'order'        => $settings['order'],
                'hide_empty'   => $settings['hide_empty'],
                'parent'       => !empty($settings['term_id']) ? $settings['term_id'] : '',
                'hierarchical' => ! empty( $settings['hide_empty'] ) ? true : false,
                'number'       => !empty($settings['max_number']) ? $settings['max_number'] : ''
            );

            $terms = get_terms( $args );

            $html = '';

            if( count( $terms ) > 0 ) {
                $i = 1;
                foreach( $terms as $term ) {
                    $settings['term_id'] = $term->term_id;

                    $count = 0;
                    if( ! empty( $settings['hide_empty'] ) || ! empty( $settings['show_count'] ) ) {
                        $count = atbdp_listings_count_by_category( $term->term_id );

                        if( ! empty( $settings['hide_empty'] ) && 0 == $count ) continue;
                    }

                    $html .= sprintf( '<option value="%s" %s>', $term->term_id, selected( $term->term_id, $term_slug, false ) );
                    $html .= $prefix . $term->name;
                    if( ! empty( $settings['show_count'] ) ) {
                        $html .= ' (' . $count . ')';
                    }
                    //$html .= $this->dropdown_locations( $settings, $prefix . '&nbsp;&nbsp;&nbsp;' );
                    $html .= '</option>';
                    if(!empty($args['number'])) {
                        if( $i++ == $args['number'] ) break;
                    }
                }

            }

            return $html;

        }

    } // class BD_Categories_Widget


}
