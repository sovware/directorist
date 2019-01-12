<?php
if (!class_exists('BD_Similar_Listings_Widget')) {
    /**
     * Adds BD_Similar_Listings_Widget widget.
     */
    class BD_Similar_Listings_Widget extends WP_Widget
    {

        /**
         * Register widget with WordPress.
         */
        function __construct()
        {
            $widget_options = array(
                'classname' => 'atbd_widget',
                'description' => esc_html__('You can show similar listing by this widget', ATBDP_TEXTDOMAIN),
            );
            parent::__construct(
                'bdsl_widget', // Base ID
                esc_html__('Directorist - Similar Listings', ATBDP_TEXTDOMAIN), // Name
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
            $title = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Similar Listings', ATBDP_TEXTDOMAIN);
            $sim_listing_num = !empty($instance['sim_listing_num']) ? $instance['sim_listing_num'] : 5;
            echo $args['before_widget'];
            echo '<div class="atbd_widget_title">';
            echo $args['before_title'] . esc_html(apply_filters('widget_title', $title)) . $args['after_title'];
            echo '</div>';
            global $post;
            $related_listings = ATBDP()->get_related_listings_widget($post,$sim_listing_num); ?>
            <div class="atbd_categorized_listings">
                <ul class="listings">
                <?php
                    foreach ($related_listings->posts as $related_listing) {

                    // get only one parent or high level term object
                    $top_category = ATBDP()->taxonomy->get_one_high_level_term($related_listing->ID, ATBDP_CATEGORY);
                    $listing_img = get_post_meta($related_listing->ID, '_listing_img', true);
                    $price             = get_post_meta($related_listing->ID, '_price', true);
                    $cats              =  get_the_terms($related_listing->ID, ATBDP_CATEGORY);
                    ?>
                        <li>
                            <div class="atbd_left_img">
                                <?= (!empty($listing_img[0])) ? '<img src="'.esc_url(wp_get_attachment_image_url($listing_img[0],  array(90,90))).'" alt="listing image">' : '<img src="'.ATBDP_PUBLIC_ASSETS . 'images/grid.jpg'.'" alt="listing image">' ?>
                            </div>
                            <div class="atbd_right_content">
                                <div class="cate_title">
                                    <h4>
                                        <a href="<?= esc_url(get_post_permalink($related_listing->ID)); ?>"><?= esc_html($related_listing->post_title); ?></a>
                                    </h4>
                                    <?php if(!empty($price)) {?>
                                    <span><?php atbdp_display_price($price);?></span>

                                    <?php  } ?>
                                </div>

                                <?php if (!empty($cats)) { ?>

                                <p class="directory_tag">
                                    <span class="fa <?php echo esc_attr(get_cat_icon($cats[0]->term_id)); ?>"
                                      aria-hidden="true"></span>
                                    <span><a href="<?= ATBDP_Permalink::get_category_archive($cats[0]); ?>">
                                         <?= esc_html($cats[0]->name); ?>
                                    </a>

                                    </span>
                                </p>
                                <?php } ?>
                            </div>
                        </li>
            <?php
        }; ?>
                    </ul>
            </div> <!--ends .categorized_listings-->


          <?php  echo $args['after_widget'];
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
            $title = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Similar Listings', ATBDP_TEXTDOMAIN);
            $sim_listing_num = !empty($instance['sim_listing_num']) ? $instance['sim_listing_num'] : 5;
            ?>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_attr_e('Title:', ATBDP_TEXTDOMAIN); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                       name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text"
                       value="<?php echo esc_attr($title); ?>">
            </p>

            <p>
                <label for="<?php echo esc_attr($this->get_field_id('sim_listing_num')); ?>"><?php esc_attr_e('Number of Listings:', ATBDP_TEXTDOMAIN); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('sim_listing_num')); ?>"
                       name="<?php echo esc_attr($this->get_field_name('sim_listing_num')); ?>" type="text"
                       value="<?php echo esc_attr($sim_listing_num); ?>">
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
            $instance['sim_listing_num'] = (!empty($new_instance['sim_listing_num'])) ? intval($new_instance['sim_listing_num']) : '';

            return $instance;
        }

    } // class BD_Similar_Listings_Widget


}
