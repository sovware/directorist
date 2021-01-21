<?php
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

if ( !class_exists('BD_All_Map_Widget')) {
    /**
     * Adds BD_Map_Widget widget.
     */
    class BD_All_Map_Widget extends WP_Widget {

        /*
         * register search widget
         */
        public function __construct ()
        {
            $widget_options = array(
                'classname' => 'atbd_widget',
                'description' => esc_html__('You can show all listings map by this widget', 'directorist'),
            );
            parent::__construct(
                'bdamw_widget', // Base ID
                esc_html__('Directorist - Map', 'directorist'), // Name
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
            $single_only  = !empty( $instance['single_only'] ) ? 1 : 0;
            
            $template_path = atbdp_get_widget_template_path( 'map-all' );
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
        public function form ($instance)
        {
            $title          = !empty($instance['title']) ? esc_html($instance['title']) : __( 'Map','directorist' );
            $map_zoom_level = !empty($instance['zoom']) ? esc_html($instance['zoom']) : 10;
            $single_only    = !empty($instance['single_only']) ? 1 : 0;
            ?>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_attr_e('Title:', 'directorist'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                       name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text"
                       value="<?php echo esc_attr($title); ?>">
            </p>

            <p>
                <label for="<?php echo esc_attr($this->get_field_id('zoom')); ?>"><?php esc_attr_e('Map zoom level:', 'directorist'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('zoom')); ?>"
                       name="<?php echo esc_attr($this->get_field_name('zoom')); ?>" type="number"
                       value="<?php echo esc_attr($map_zoom_level); ?>">
            </p>

            <p>
                <input <?php checked( $single_only,1 ); ?> id="<?php echo $this->get_field_id( 'single_only' ); ?>" name="<?php echo $this->get_field_name( 'single_only' ); ?>" value="1" type="checkbox" />
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
        public function update($new_instance, $old_instance)
        {
            $instance                = array();
            $instance['title']       = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
            $instance['zoom']        = (!empty($new_instance['zoom'])) ? strip_tags($new_instance['zoom']) : '3';
            $instance['single_only'] = (!empty($new_instance['single_only'])) ? 1 : 0;

            return $instance;
        }
    }

} // end class exist