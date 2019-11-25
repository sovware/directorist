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
                'description' => esc_html__('You can show similar listing by this widget', 'directorist'),
            );
            parent::__construct(
                'bdsl_widget', // Base ID
                esc_html__('Directorist - Similar Listings', 'directorist'), // Name
                $widget_options // Args
            );
        }

        /**
         * Front-end display of widget.
         *
         * @param array $args Widget arguments.
         * @param array $instance Saved values from database.
         * @see WP_Widget::widget()
         *
         */
        public function widget($args, $instance)
        {
            $allowWidget = apply_filters('atbdp_allow_similar_widget', true);
            if (!$allowWidget) return;
            if (is_singular(ATBDP_POST_TYPE)) {
                $title = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Similar Listings', 'directorist');
                $sim_listing_num = !empty($instance['sim_listing_num']) ? $instance['sim_listing_num'] : 5;
                echo $args['before_widget'];
                echo '<div class="atbd_widget_title">';
                echo $args['before_title'] . esc_html(apply_filters('widget_title', $title)) . $args['after_title'];
                echo '</div>';
                global $post;
                $related_listings = ATBDP()->get_related_listings_widget($post, $sim_listing_num); ?>
                <div class="atbd_categorized_listings">
                    <ul class="listings">
                        <?php
                        foreach ($related_listings->posts as $related_listing) {

                            // get only one parent or high level term object
                            $top_category = ATBDP()->taxonomy->get_one_high_level_term($related_listing->ID, ATBDP_CATEGORY);
                            $listing_img = get_post_meta($related_listing->ID, '_listing_img', true);
                            $listing_prv_img = get_post_meta($related_listing->ID, '_listing_prv_img', true);
                            $price = get_post_meta($related_listing->ID, '_price', true);
                            $price_range = get_post_meta($related_listing->ID, '_price_range', true);
                            $listing_pricing = get_post_meta($related_listing->ID, '_atbd_listing_pricing', true);
                            $cats = get_the_terms($related_listing->ID, ATBDP_CATEGORY);
                            ?>
                            <li>
                                <div class="atbd_left_img">
                                    <?php
                                    $disable_single_listing = get_directorist_option('disable_single_listing');
                                    if (empty($disable_single_listing)){
                                    ?>
                                    <a href="<?php echo esc_url(get_post_permalink($related_listing->ID)); ?>">
                                        <?php
                                        }
                                        $default_image = get_directorist_option('default_preview_image', ATBDP_PUBLIC_ASSETS . 'images/grid.jpg');
                                        if (!empty($listing_prv_img)) {
                                            echo '<img src="' . esc_url(wp_get_attachment_image_url($listing_prv_img, array(90, 90))) . '" alt="listing image">';
                                        } elseif (!empty($listing_img[0]) && empty($listing_prv_img)) {
                                            echo '<img src="' . esc_url(wp_get_attachment_image_url($listing_img[0], array(90, 90))) . '" alt="listing image">';
                                        } else {
                                            echo '<img src="' . $default_image . '" alt="listing image">';
                                        }
                                        if (empty($disable_single_listing)) {
                                            echo '</a>';
                                        }
                                        ?>
                                </div>
                                <div class="atbd_right_content">
                                    <div class="cate_title">
                                        <h4>
                                            <?php
                                            if (empty($disable_single_listing)) {
                                                ?>
                                                <a href="<?php echo esc_url(get_post_permalink($related_listing->ID)); ?>"><?php echo esc_html($related_listing->post_title); ?></a>
                                                <?php
                                            } else {
                                                echo esc_html($related_listing->post_title);
                                            } ?>

                                        </h4>
                                        <?php if (!empty($price) && ('price' === $listing_pricing)) { ?>
                                            <span><?php atbdp_display_price($price); ?></span>

                                        <?php } else {
                                            $output = atbdp_display_price_range($price_range);
                                            echo $output;
                                        } ?>
                                    </div>

                                    <?php if (!empty($cats)) {
                                        $totalTerm = count($cats);
                                        ?>

                                        <p class="directory_tag">
                                            <span class="<?php atbdp_icon_type(true);?>-tags"></span>
                                            <span>
                                                <a href="<?php echo ATBDP_Permalink::atbdp_get_category_page($cats[0]); ?>">
                                                                     <?php echo esc_html($cats[0]->name); ?>
                                                </a>
                                            <?php
                                            if ($totalTerm > 1) {
                                                ?>
                                                <span class="atbd_cat_popup">  +<?php echo $totalTerm - 1; ?>
                                                    <span class="atbd_cat_popup_wrapper">
                                                                    <?php
                                                                    $output = array();
                                                                    foreach (array_slice($cats, 1) as $cat) {
                                                                        $link = ATBDP_Permalink::atbdp_get_category_page($cat);
                                                                        $space = str_repeat(' ', 1);
                                                                        $output [] = "{$space}<a href='{$link}'>{$cat->name}<span>,</span></a>";
                                                                    } ?>
                                                        <span><?php echo join($output) ?></span>
                                                                </span>
                                                            </span>
                                            <?php } ?>

                                        </span>
                                        </p>
                                    <?php } ?>
                                </div>
                            </li>
                            <?php
                        }; ?>
                    </ul>
                </div> <!--ends .categorized_listings-->


                <?php echo $args['after_widget'];
            }
        }

        /**
         * Back-end widget form.
         *
         * @param array $instance Previously saved values from database.
         * @return void
         * @see WP_Widget::form()
         *
         */
        public function form($instance)
        {
            $title = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Similar Listings', 'directorist');
            $sim_listing_num = !empty($instance['sim_listing_num']) ? $instance['sim_listing_num'] : 5;
            ?>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_attr_e('Title:', 'directorist'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                       name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text"
                       value="<?php echo esc_attr($title); ?>">
            </p>

            <p>
                <label for="<?php echo esc_attr($this->get_field_id('sim_listing_num')); ?>"><?php esc_attr_e('Number of Listings:', 'directorist'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('sim_listing_num')); ?>"
                       name="<?php echo esc_attr($this->get_field_name('sim_listing_num')); ?>" type="text"
                       value="<?php echo esc_attr($sim_listing_num); ?>">
            </p>

            <?php
        }

        /**
         * Sanitize widget form values as they are saved.
         *
         * @param array $new_instance Values just sent to be saved.
         * @param array $old_instance Previously saved values from database.
         *
         * @return array Updated safe values to be saved.
         * @see WP_Widget::update()
         *
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
