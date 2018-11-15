<?php
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

if ( !class_exists('BD_Map_Widget')) {
    /**
     * Adds BD_Map_Widget widget.
     */
    class BD_Map_Widget extends WP_Widget {

        /*
         * register search widget
         */
        public function __construct ()
        {
            $widget_options = array(
                'classname' => 'listings',
                'description' => esc_html__('You can show map by this widget', ATBDP_TEXTDOMAIN),
            );
            parent::__construct(
                'bdmw_widget', // Base ID
                esc_html__('Directorist - Map', ATBDP_TEXTDOMAIN), // Name
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
        public function widget ($args, $instance)
        {
            global $post;
            $manual_lat = get_post_meta($post->ID, '_manual_lat', true);
            $manual_lng = get_post_meta($post->ID, '_manual_lng', true);
            $title = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Map', ATBDP_TEXTDOMAIN);
            echo $args['before_widget'];
            echo $args['before_title'] . esc_html(apply_filters('widget_title', $title)) . $args['after_title'];

            ?>

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
        public function form ($instance)
        {
            $title = !empty($instance['title']) ? esc_html($instance['title']) : __( 'Map',ATBDP_TEXTDOMAIN );
            ?>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_attr_e('Title:', ATBDP_TEXTDOMAIN); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                       name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text"
                       value="<?php echo esc_attr($title); ?>">
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
        public function update($new_instance, $old_instance)
        {
            $instance = array();
            $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';

            return $instance;
        }
    }

} // end class exist