<?php
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}
if (!class_exists('BD_Locations_Widget')) {
    /**
     * Adds BD_Locations_Widget widget.
     */
    class BD_Locations_Widget extends WP_Widget
    {

        /**
         * Register widget with WordPress.
         */
        function __construct()
        {
            $widget_options = array(
                'classname' => 'atbd_widget',
                'description' => esc_html__('You can show Locations by this widget', ATBDP_TEXTDOMAIN),
            );
            parent::__construct(
                'bdlw_widget', // Base ID
                esc_html__('Directorist - Locations', ATBDP_TEXTDOMAIN), // Name
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
            $title = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Directorist Locations', ATBDP_TEXTDOMAIN);

            $query_args = array(
                'template'       => ! empty( $instance['display_as'] ) ? sanitize_text_field( $instance['display_as'] ) : 'list',
                'parent'         => ! empty( $instance['parent'] ) ? (int) $instance['parent'] : 0,
                'term_id'        => ! empty( $instance['parent'] ) ? (int) $instance['parent'] : 0,
                'hide_empty'     => ! empty( $instance['hide_empty'] ) ? 1 : 0,
                'orderby'        => ! empty( $instance['orderby'] ) ? sanitize_text_field( $instance['order_by'] ) : 'id',
                'order'          => ! empty( $instance['order'] ) ? sanitize_text_field( $instance['order'] ) : 'asc',
                'show_count'     => ! empty( $instance['show_count'] ) ? 1 : 0,
                'pad_counts'     => true,
                'immediate_category' => ! empty( $instance['immediate_category'] ) ? 1 : 0,
                'active_term_id' => 0,
                'ancestors'      => array()
            );

            if( $query_args['immediate_category'] ) {

                $term_slug = get_query_var( ATBDP_LOCATION );

                if( '' != $term_slug ) {
                    $term = get_term_by( 'slug', $term_slug, ATBDP_LOCATION );
                    $query_args['active_term_id'] = $term->term_id;

                    $query_args['ancestors'] = get_ancestors( $query_args['active_term_id'], ATBDP_LOCATION );
                    $query_args['ancestors'][] = $query_args['active_term_id'];
                    $query_args['ancestors'] = array_unique( $query_args['ancestors'] );
                }

            }

            if( 'dropdown' == $query_args['template'] ) {
                $categories = $this->dropdown_locations( $query_args );
            } else {
                $categories = $this->list_locations( $query_args );
            }
            echo $args['before_widget'];
            echo '<div class="atbd_widget_title">';
            echo $args['before_title'] . esc_html(apply_filters('widget_title', $title)) . $args['after_title'];
            echo '</div>';
            ?>

            <div class="atbdp atbdp-widget-categories">
                <?php if( 'dropdown' == $query_args['template'] ) : ?>
                    <form action="<?php echo ATBDP_Permalink::get_search_result_page_link(); ?>" role="form">
                        <input type="hidden" name="q" placeholder="">
                        <select id="at_biz_dir-location" name="in_loc" onchange="this.form.submit()">
                            <?php echo $categories; ?>
                        </select>
                    </form>
                <?php else :
                    echo $categories;
                endif; ?>
            </div>

            <?php


            echo $args['after_widget'];
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
                'title' => __('Locations', ATBDP_TEXTDOMAIN),
                'display_as'=>'list',
                'hide_empty'=> 0,
                'show_count'=> 0,
                'parent'=>0,
                'immediate_category'=>0,
                'order_by'=>'id',
                'order'=>'asc',
            );

            $instance = wp_parse_args((array)$instance,$values);
            $title = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Locations', ATBDP_TEXTDOMAIN);
            ?>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_attr_e('Title:', ATBDP_TEXTDOMAIN); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                       name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text"
                       value="<?php echo esc_attr($title); ?>">
            </p>



            <p>
                <label for="<?php echo $this->get_field_id( 'display_as' ); ?>"><?php _e( 'View as', ATBDP_TEXTDOMAIN ); ?></label>
                <select class="widefat" id="<?php echo $this->get_field_id( 'display_as' ); ?>" name="<?php echo $this->get_field_name( 'display_as' ); ?>">
                    <option value="list" <?php selected( $instance['display_as'], 'list' ); ?>><?php _e( 'List', ATBDP_TEXTDOMAIN ); ?></option>
                    <option value="dropdown" <?php selected( $instance['display_as'], 'dropdown' ); ?>><?php _e( 'Dropdown', ATBDP_TEXTDOMAIN ); ?></option>
                </select>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'parent' ); ?>"><?php _e( 'Select Parent', ATBDP_TEXTDOMAIN ); ?></label>
                <?php
                wp_dropdown_categories( array(
                    'show_option_none'  => '-- '.__( 'Select Parent', ATBDP_TEXTDOMAIN ).' --',
                    'option_none_value' => 0,
                    'taxonomy'          => ATBDP_LOCATION,
                    'name' 			    => $this->get_field_name( 'parent' ),
                    'class'             => 'widefat',
                    'orderby'           => 'name',
                    'selected'          => (int) $instance['parent'],
                    'hierarchical'      => true,
                    'depth'             => 10,
                    'show_count'        => false,
                    'hide_empty'        => false,
                ) );
                ?>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'order_by' ); ?>"><?php _e( 'Order By', ATBDP_TEXTDOMAIN ); ?></label>
                <select class="widefat" id="<?php echo $this->get_field_id( 'order_by' ); ?>" name="<?php echo $this->get_field_name( 'order_by' ); ?>">
                    <option value="id" <?php selected( $instance['order_by'], 'id' ); ?>><?php _e( 'Id', ATBDP_TEXTDOMAIN ); ?></option>
                    <option value="count" <?php selected( $instance['order_by'], 'count' ); ?>><?php _e( 'Count', ATBDP_TEXTDOMAIN ); ?></option>
                    <option value="name" <?php selected( $instance['order_by'], 'name' ); ?>><?php _e( 'Name', ATBDP_TEXTDOMAIN ); ?></option>
                    <option value="slug" <?php selected( $instance['order_by'], 'slug' ); ?>><?php _e( 'Slug', ATBDP_TEXTDOMAIN ); ?></option>
                </select>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id( 'order' ); ?>"><?php _e( 'Sord By', ATBDP_TEXTDOMAIN ); ?></label>
                <select class="widefat" id="<?php echo $this->get_field_id( 'order' ); ?>" name="<?php echo $this->get_field_name( 'order' ); ?>">
                    <option value="asc" <?php selected( $instance['order'], 'asc' ); ?>><?php _e( 'Ascending', ATBDP_TEXTDOMAIN ); ?></option>
                    <option value="desc" <?php selected( $instance['order'], 'desc' ); ?>><?php _e( 'Descending', ATBDP_TEXTDOMAIN ); ?></option>
                </select>
            </p>

            <p>
                <input <?php checked( $instance['immediate_category'],1 ); ?> id="<?php echo $this->get_field_id( 'immediate_category' ); ?>" name="<?php echo $this->get_field_name( 'immediate_category' ); ?>" value="1" type="checkbox" />
                <label for="<?php echo $this->get_field_id( 'immediate_category' ); ?>"><?php _e( 'Show all the top level categories only', ATBDP_TEXTDOMAIN ); ?></label>
            </p>

            <p>
                <input <?php checked( $instance['hide_empty'],1 ); ?> id="<?php echo $this->get_field_id( 'hide_empty' ); ?>" name="<?php echo $this->get_field_name( 'hide_empty' ); ?>" value="1" type="checkbox" />
                <label for="<?php echo $this->get_field_id( 'hide_empty' ); ?>"><?php _e( 'Hide empty locations', ATBDP_TEXTDOMAIN ); ?></label>
            </p>

            <p>
                <input <?php checked( $instance['show_count'],1 ); ?> id="<?php echo $this->get_field_id( 'show_count' ); ?>" name="<?php echo $this->get_field_name( 'show_count' ); ?>" value="1" type="checkbox" />
                <label for="<?php echo $this->get_field_id( 'show_count' ); ?>"><?php _e( 'Display listing counts', ATBDP_TEXTDOMAIN ); ?></label>
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

            $instance['title']          = ! empty( $new_instance['title'] ) ? strip_tags( $new_instance['title'] ) : '';
            $instance['display_as']       = isset( $new_instance['display_as'] ) ? sanitize_text_field( $new_instance['display_as'] ) : 'list';
            $instance['order_by']       = isset( $new_instance['order_by'] ) ? sanitize_text_field( $new_instance['order_by'] ) : 'id';
            $instance['order']       = isset( $new_instance['order'] ) ? sanitize_text_field( $new_instance['order'] ) : 'asc';
            $instance['parent']         = isset( $new_instance['parent'] ) ? (int) $new_instance['parent'] : 0;
            $instance['immediate_category'] = isset( $new_instance['immediate_category'] ) ? 1 : 0;
            $instance['hide_empty']     = isset( $new_instance['hide_empty'] ) ? 1 : 0;
            $instance['show_count']     = isset( $new_instance['show_count'] ) ? 1 : 0;

            return $instance;

        }

        public function list_locations( $settings ) {

            if( $settings['immediate_category'] ) {

                if( $settings['term_id'] > $settings['parent'] && ! in_array( $settings['term_id'], $settings['ancestors'] ) ) {
                    return;
                }

            }

            $args = array(
                'orderby'      => $settings['orderby'],
                'order'        => $settings['order'],
                'hide_empty'   => $settings['hide_empty'],
                'parent'       => $settings['term_id'],
                'hierarchical' => ! empty( $settings['hide_empty'] ) ? true : false
            );

            $terms = get_terms( ATBDP_LOCATION, $args );

            $html = '';

            if( count( $terms ) > 0 ) {

                $html .= '<ul>';

                foreach( $terms as $term ) {
                    $child_category = get_term_children($term->term_id,ATBDP_LOCATION);
                    $plus_icon = (!empty($child_category) && empty($parent) ) ? '<span class="fa fa-plus"></span>' : '';
                    $settings['term_id'] = $term->term_id;

                    $count = 0;
                    if( ! empty( $settings['hide_empty'] ) || ! empty( $settings['show_count'] ) ) {
                        $count = atbdp_listings_count_by_location( $term->term_id, $settings['pad_counts'] );

                        if( ! empty( $settings['hide_empty'] ) && 0 == $count ) continue;
                    }

                    $html .= '<li>';
                    $html .= '<a href="' . ATBDP_Permalink::get_location_archive( $term ) . '">';
                    $html .= $term->name;
                    if( ! empty( $settings['show_count'] ) ) {
                        $html .= ' (' . $count . ')';
                    }
                    $html .= '</a>';
                    $html .= $this->list_locations( $settings );
                    $html .= '</li>';
                }

                $html .= '</ul>';

            }

            return $html;

        }

        public function dropdown_locations( $settings, $prefix = '' ) {

            if( $settings['immediate_category'] ) {

                if( $settings['term_id'] > $settings['parent'] && ! in_array( $settings['term_id'], $settings['ancestors'] ) ) {
                    return;
                }

            }

            $term_slug = get_query_var( ATBDP_LOCATION );

            $args = array(
                'orderby'      => $settings['orderby'],
                'order'        => $settings['order'],
                'hide_empty'   => $settings['hide_empty'],
                'parent'       => $settings['term_id'],
                'hierarchical' => ! empty( $settings['hide_empty'] ) ? true : false
            );

            $terms = get_terms( ATBDP_LOCATION, $args );

            $html = '';

            if( count( $terms ) > 0 ) {

                foreach( $terms as $term ) {
                    $settings['term_id'] = $term->term_id;

                    $count = 0;
                    if( ! empty( $settings['hide_empty'] ) || ! empty( $settings['show_count'] ) ) {
                        $count = atbdp_listings_count_by_category( $term->term_id );

                        if( ! empty( $settings['hide_empty'] ) && 0 == $count ) continue;
                    }

                    $html .= sprintf( '<option value="%s" %s>', $term->slug, selected( $term->slug, $term_slug, false ) );
                    $html .= $prefix . $term->name;
                    if( ! empty( $settings['show_count'] ) ) {
                        $html .= ' (' . $count . ')';
                    }
                    $html .= $this->dropdown_locations( $settings, $prefix . '&nbsp;&nbsp;&nbsp;' );
                    $html .= '</option>';
                }

            }

            return $html;

        }

    } // class BD_Locations_Widget


}
