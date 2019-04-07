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
                'description' => esc_html__('You can show search listing form by this widget', ATBDP_TEXTDOMAIN),
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
            $hide_category = !empty($instance['hide_category']) ? 1 : 0;
            $hide_location = !empty($instance['hide_location']) ? 1 : 0;
            echo $args['before_widget'];
            echo '<div class="atbd_widget_title">';
            echo $args['before_title'] . esc_html(apply_filters('widget_title', $title)) . $args['after_title'];
            echo '</div>';
            ?>
            <div class="directorist atbdp-search atbdp-search-vertical">
                <form action="<?php echo ATBDP_Permalink::get_search_result_page_link(); ?>" class="form-vertical" role="form">
                    <div class="form-group">
                        <input type="text" name="q" class="form-control" placeholder="<?php _e( 'Enter your keyword here ...', 'advanced-classifieds-and-directory-pro' ); ?>" value="">
                    </div>

                    <?php if(empty($hide_category)) { ?>
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
                    <?php } ?>
                    <?php if(empty($hide_location)) {?>
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
                   <?php } ?>
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
            // Define the array of defaults
            $defaults = array(
                'title'                   =>  __( 'Search', ATBDP_TEXTDOMAIN ),
                'search_by_text_field'    => 1,
                'search_by_category'      => 1,
                'search_by_location'      => 1,
                'search_by_tag'           => 1,
                'search_by_custom_fields' => 1,
                'search_by_price'         => 1,
                'search_by_review'        => 1,
                'search_by_website'       => 0,
                'search_by_email'         => 0,
                'search_by_phone'         => 0,
                'search_by_address'       => 0,
                'search_by_zip_code'      => 0,
            );
            // Parse incoming $instance into an array and merge it with $defaults
            $instance = wp_parse_args(
                (array) $instance,
                $defaults
            );

            require ATBDP_TEMPLATES_DIR . 'search-widget-form.php';
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
            $instance['search_by_text_field'] = (isset($new_instance['search_by_text_field'])) ? 1 : 0;
            $instance['search_by_category'] = (isset($new_instance['search_by_category'])) ? 1 : 0;
            $instance['search_by_location'] = (isset($new_instance['search_by_location'])) ? 1 : 0;
            $instance['search_by_tag'] = (isset($new_instance['search_by_tag'])) ? 1 : 0;
            $instance['search_by_custom_fields'] = (isset($new_instance['search_by_custom_fields'])) ? 1 : 0;
            $instance['search_by_price'] = (isset($new_instance['search_by_price'])) ? 1 : 0;
            $instance['search_by_review'] = (isset($new_instance['search_by_review'])) ? 1 : 0;
            $instance['search_by_website'] = (isset($new_instance['search_by_website'])) ? 1 : 0;
            $instance['search_by_email'] = (isset($new_instance['search_by_email'])) ? 1 : 0;
            $instance['search_by_phone'] = (isset($new_instance['search_by_phone'])) ? 1 : 0;
            $instance['search_by_address'] = (isset($new_instance['search_by_address'])) ? 1 : 0;
            $instance['search_by_zip_code'] = (isset($new_instance['search_by_zip_code'])) ? 1 : 0;

            return $instance;
        }
    }

} // end class exist