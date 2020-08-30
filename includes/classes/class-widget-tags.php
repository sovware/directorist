<?php
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}
if (!class_exists('BD_Tags_Widget')) {
    /**
     * Adds BD_Popular_Listing_Widget widget.
     */
    class BD_Tags_Widget extends WP_Widget
    {

        /**
         * Register widget with WordPress.
         */
        function __construct()
        {
            $widget_options = array(
                'classname' => 'atbd_widget',
                'description' => esc_html__('You can show listing tags by this widget', 'directorist'),
            );
            parent::__construct(
                'bdtw_widget', // Base ID
                esc_html__('Directorist - Tags', 'directorist'), // Name
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
            $allowWidget = apply_filters('atbdp_allow_tags_widget', true);
            //$chech_tag = get_the_terms(get_the_ID(), ATBDP_TAGS);
            $chech_tag = get_the_terms(get_the_ID(), ATBDP_TAGS);
            if (!$allowWidget) return;
            
            $title = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Tags', 'directorist');

            $query_args = array(
                'template'               => !empty( $instance['display_as'] ) ? sanitize_text_field( $instance['display_as'] ) : 'list',
                'parent'                 => !empty( $instance['parent'] ) ? (int) $instance['parent'] : 0,
                'term_id'                => !empty( $instance['parent'] ) ? (int) $instance['parent'] : 0,
                'hide_empty'             => !empty( $instance['hide_empty'] ) ? 1 : 0,
                'orderby'                => !empty( $instance['order_by'] ) ? sanitize_text_field( $instance['order_by'] ) : 'id',
                'order'                  => !empty( $instance['order'] ) ? sanitize_text_field( $instance['order'] ) : 'asc',
                'show_count'             => !empty( $instance['show_count'] ) ? 1 : 0,
                'display_single_tag'     => !empty( $instance['display_single_tag'] ) ? 1 : 0,
                'pad_counts'             => true,
                'immediate_category'     => !empty( $instance['immediate_category'] ) ? 1 : 0,
                'max_number'             => !empty( $instance['max_number'] ) ? $instance['max_number'] : '',
                'active_term_id'         => 0,
                'ancestors'              => array()
            );

            if ( $query_args['display_single_tag'] && !$chech_tag) return ;

            if( $query_args['immediate_category'] ) {

                $term_slug = get_query_var( ATBDP_TAGS );

                if( '' != $term_slug ) {
                    $term = get_term_by( 'slug', $term_slug, ATBDP_TAGS );
                    $query_args['active_term_id'] = $term->term_id;

                    $query_args['ancestors'] = get_ancestors( $query_args['active_term_id'], 'atbdp_tags' );
                    $query_args['ancestors'][] = $query_args['active_term_id'];
                    $query_args['ancestors'] = array_unique( $query_args['ancestors'] );
                }

            }

            if( 'dropdown' == $query_args['template'] ) {
                $tags = $this->dropdown_tags( $query_args );
            } else {
                $tags = $this->atbdp_tags_list( $query_args );
            }

            $template_path = atbdp_get_widget_template_path( 'tags' );
            if ( file_exists( $template_path ) ) {
                include $template_path;
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
                'title'                 => __('Tags', 'directorist'),
                'display_as'            => 'list',
                'hide_empty'            => 0,
                'show_count'            => 0,
                'display_single_tag'    => 0,
                'parent'                => 0,
                'immediate_category'    => 0,
                'order_by'              => 'id',
                'order'                 => 'asc',
                'max_number'            => ''
            );
            $instance   =  wp_parse_args((array)$instance,$values);
            $title      = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Tags', 'directorist');
            $max_number = !empty($instance['max_number']) ? esc_html($instance['max_number']) : '';
            ?>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_attr_e('Title:', 'directorist'); ?></label>
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
                <input <?php checked( $instance['hide_empty'],1 ); ?> id="<?php echo $this->get_field_id( 'hide_empty' ); ?>" name="<?php echo $this->get_field_name( 'hide_empty' ); ?>" value="1" type="checkbox" />
                <label for="<?php echo $this->get_field_id( 'hide_empty' ); ?>"><?php _e( 'Hide empty tags', 'directorist' ); ?></label>
            </p>

            <p>
                <input <?php checked( $instance['show_count'],1 ); ?> id="<?php echo $this->get_field_id( 'show_count' ); ?>" name="<?php echo $this->get_field_name( 'show_count' ); ?>" value="1" type="checkbox" />
                <label for="<?php echo $this->get_field_id( 'show_count' ); ?>"><?php _e( 'Display listing counts', 'directorist' ); ?></label>
            </p>

            <p>
                <input <?php checked( $instance['display_single_tag'],1 ); ?> id="<?php echo $this->get_field_id( 'display_single_tag' ); ?>" name="<?php echo $this->get_field_name( 'display_single_tag' ); ?>" value="1" type="checkbox" />
                <label for="<?php echo $this->get_field_id( 'display_single_tag' ); ?>"><?php _e( 'Display single listing tags', 'directorist' ); ?></label>
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

            $instance['title']                  = ! empty( $new_instance['title'] ) ? strip_tags( $new_instance['title'] ) : '';
            $instance['display_as']             = isset( $new_instance['display_as'] ) ? sanitize_text_field( $new_instance['display_as'] ) : 'list';
            $instance['order_by']               = isset( $new_instance['order_by'] ) ? sanitize_text_field( $new_instance['order_by'] ) : 'id';
            $instance['order']                  = isset( $new_instance['order'] ) ? sanitize_text_field( $new_instance['order'] ) : 'asc';
            $instance['hide_empty']             = isset( $new_instance['hide_empty'] ) ? 1 : 0;
            $instance['show_count']             = isset( $new_instance['show_count'] ) ? 1 : 0;
            $instance['display_single_tag']     = isset( $new_instance['display_single_tag'] ) ? 1 : 0;
            $instance['max_number']             = isset( $new_instance['max_number'] ) ? $new_instance['max_number'] : '';

            return $instance;

        }

        public function atbdp_tags_list( $settings ) {

            if( $settings['display_single_tag'] ) {
                $terms = get_the_terms(get_the_ID(), ATBDP_TAGS);
                $html = '';
                if (!empty($terms)) {
                    $html .= '<ul>';
                    foreach ($terms as $term) {
                        $html .= '<li>';
                        $html .= '<a href="' . ATBDP_Permalink::atbdp_get_tag_page($term) . '">';
                        $html .= $term->name;
                        $html .= '</a>';
                        $html .= '</li>';
                    }
                    $html .= '</ul>';
                }
            } else {


                if ($settings['immediate_category']) {

                    if ($settings['term_id'] > $settings['parent'] && !in_array($settings['term_id'], $settings['ancestors'])) {
                        return;
                    }

                }

                $args = array(
                    'taxonomy' => ATBDP_TAGS,
                    'orderby' => $settings['orderby'],
                    'order' => $settings['order'],
                    'hide_empty' => $settings['hide_empty'],
                    'parent' => !empty($settings['term_id']) ? $settings['term_id'] : '',
                    'hierarchical' => !empty($settings['hide_empty']) ? true : false,
                    'number' => !empty($settings['max_number']) ? $settings['max_number'] : ''
                );
                $terms = get_terms( $args );

                $html = '';

                if (count($terms) > 0) {

                    $html .= '<ul>';

                    foreach ($terms as $term) {
                        $settings['term_id'] = $term->term_id;

                        $count = 0;
                        if (!empty($settings['hide_empty']) || !empty($settings['show_count'])) {
                            $count = atbdp_listings_count_by_tag($term->term_id);

                            if (!empty($settings['hide_empty']) && 0 == $count) continue;
                        }

                        $html .= '<li>';
                        $html .= '<a href="' . ATBDP_Permalink::atbdp_get_tag_page($term) . '">';
                        $html .= $term->name;
                        if (!empty($settings['show_count'])) {
                            $html .= ' (' . $count . ')';
                        }
                        $html .= '</a>';
                       // $html .= $this->atbdp_tags_list($settings);
                        $html .= '</li>';
                    }

                    $html .= '</ul>';

                }
            }
            return $html;



        }

        public function dropdown_tags( $settings, $prefix = '' ) {
            $term_slug = get_query_var(ATBDP_TAGS);
            if( $settings['display_single_tag'] ) {
                $terms = get_the_terms(get_the_ID(), ATBDP_TAGS);
                $html = '';
                if (!empty($terms)) {

                    foreach ($terms as $term) {
                        $html .= sprintf( '<option value="%s" %s>', $term->slug, selected( $term->slug, $term_slug, false ) );
                        $html .= $prefix . $term->name;
                        $html .= '</option>';
                    }

                }
            } else {

                if ($settings['immediate_category']) {

                    if ($settings['term_id'] > $settings['parent'] && !in_array($settings['term_id'], $settings['ancestors'])) {
                        return;
                    }

                }

                $args = array(
                        'taxonomy' => ATBDP_TAGS,
                        'orderby' => $settings['orderby'],
                        'order' => $settings['order'],
                        'hide_empty' => $settings['hide_empty'],
                        'parent' => !empty($settings['term_id']) ? $settings['term_id'] : '',
                        'hierarchical' => !empty($settings['hide_empty']) ? true : false,
                        'number' => !empty($settings['max_number']) ? $settings['max_number'] : ''
                    );

                $terms = get_terms( $args );

                $html = '';

                if (count($terms) > 0) {

                    foreach ($terms as $term) {
                        $settings['term_id'] = $term->term_id;

                        $count = 0;
                        if (!empty($settings['hide_empty']) || !empty($settings['show_count'])) {
                            $count = atbdp_listings_count_by_tag($term->term_id);

                            if (!empty($settings['hide_empty']) && 0 == $count) continue;
                        }

                        $html .= sprintf('<option value="%s" %s>', $term->term_taxonomy_id, selected($term->slug, $term_slug, false));
                        $html .= $prefix . $term->name;
                        if (!empty($settings['show_count'])) {
                            $html .= ' (' . $count . ')';
                        }
                        //$html .= $this->dropdown_tags($settings, $prefix . '&nbsp;&nbsp;&nbsp;');
                        $html .= '</option>';
                    }

                }
            }
            return $html;

        }

    } // class BD_Categories_Widget


}
