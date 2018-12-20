<?php
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

if ( !class_exists('BD_Search_Widget')) {
    /**
     * Adds BD_Search_Widget widget.
     */
    class Bd_search_Widget extends WP_Widget {

        /*
         * register search widget
         */
        public function __construct ()
        {
            $widget_options = array(
                'classname' => 'atbd_widget',
                'description' => esc_html__('You can show search listing by this widget', ATBDP_TEXTDOMAIN),
            );
            parent::__construct(
                'bdsw_widget', // Base ID
                esc_html__('Directorist - Search Listings', ATBDP_TEXTDOMAIN), // Name
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
            $categories = get_terms(ATBDP_CATEGORY, array('hide_empty' => 0));
            $locations = get_terms(ATBDP_LOCATION, array('hide_empty' => 0));
            $search_placeholder = get_directorist_option('search_placeholder', __('What are you looking for?', ATBDP_TEXTDOMAIN));
            $title = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Search Listings', ATBDP_TEXTDOMAIN);
            echo $args['before_widget'];
            echo '<div class="atbd_widget">';
            echo $args['before_title'] . esc_html(apply_filters('widget_title', $title)) . $args['after_title'];
            echo '</div>';
            ?>
            <div class="directorist atbdp-search atbdp-search-vertical">
                <form action="<?php echo ATBDP_Permalink::get_search_result_page_link(); ?>" class="form-vertical" role="form">
                    <div class="form-group">
                        <input type="text" name="q" class="form-control" placeholder="<?php _e( 'Enter your keyword here ...', 'advanced-classifieds-and-directory-pro' ); ?>" value="">
                    </div>


                    <div class="form-group single_search_field search_category" >
                        <select name="in_cat" class="directory_field form-control" id="at_biz_dir-category">
                            <option value=""><?php _e('Select a category', ATBDP_TEXTDOMAIN ); ?></option>

                            <?php
                            foreach ( $categories as $category ) {
                                echo "<option id='atbdp_category' value='$category->slug'>$category->name</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group single_search_field search_location">
                        <select name="in_loc" class="directory_field form-control" id="at_biz_dir-location">
                            <!--This text comes from js, translate them later @todo; translate js text-->
                            <option value=""><?php _e('Select a location', ATBDP_TEXTDOMAIN); ?></option>

                            <?php foreach ($locations as $location) {
                                echo "<option id='atbdp_location' value='$location->slug'>$location->name</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group submit_btn">
                        <button type="submit" class="btn btn-primary"><?php _e( 'Search Listings', ATBDP_TEXTDOMAIN ); ?></button>
                    </div>
                </form>
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
        public function form ($instance)
        {
            $title = !empty($instance['title']) ? esc_html($instance['title']) : __( 'Search Listings',ATBDP_TEXTDOMAIN );
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