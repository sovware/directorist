<?php
if (!class_exists('BD_Featured_Listings_Widget')) {
    /**
     * Adds BD_Featured_Listings_Widget widget.
     */
    class BD_Featured_Listings_Widget extends WP_Widget
    {

        /**
         * Register widget with WordPress.
         */
        function __construct()
        {
            $widget_options = array(
                'classname' => 'atbd_widget',
                'description' => esc_html__('You can show featured listing by this widget', ATBDP_TEXTDOMAIN),
            );
            parent::__construct(
                'bdfl_widget', // Base ID
                esc_html__('Directorist - Featured Listings', ATBDP_TEXTDOMAIN), // Name
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
            $title = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Featured Listings', ATBDP_TEXTDOMAIN);
            $f_listing_num = !empty($instance['f_listing_num']) ? $instance['f_listing_num'] : 5;
            echo $args['before_widget'];
            echo '<div class="atbd_widget_title">';
            echo $args['before_title'] . esc_html(apply_filters('widget_title', $title)) . $args['after_title'];
            echo '</div>';
            $common_args = array(
                'post_type'      => ATBDP_POST_TYPE,
                'post_status'    => 'publish',
                'posts_per_page' => (int) $f_listing_num,
                'meta_query'     =>array(
                    array(
                        'key'     => '_featured',
                        'value'   => 1,
                        'compare' => '='
                    )
                )
            );
            $featured_listings = new WP_Query($common_args);

            ?>
            <div class="atbd_categorized_listings">
                <ul class="listings">
                    <?php
                    if($featured_listings -> have_posts()) {
                         while ($featured_listings->have_posts()) { $featured_listings->the_post();
                        // get only one parent or high level term object
                        $listing_img = get_post_meta(get_the_ID(), '_listing_img', true);
                        $listing_prv_img = get_post_meta(get_the_ID(), '_listing_prv_img', true);
                        $price = get_post_meta(get_the_ID(), '_price', true);
                        $price_range = get_post_meta(get_the_ID(), '_price_range', true);
                        $listing_pricing = get_post_meta(get_the_ID(), '_atbd_listing_pricing', true);
                        $cats = get_the_terms(get_the_ID(), ATBDP_CATEGORY);
                        ?>
                        <li>
                            <div class="atbd_left_img">
                                <?php
                                $default_image = get_directorist_option('default_preview_image', ATBDP_PUBLIC_ASSETS . 'images/grid.jpg');
                                if (!empty($listing_prv_img)) {
                                    echo '<img src="' . esc_url(wp_get_attachment_image_url($listing_prv_img, array(90, 90))) . '" alt="listing image">';
                                } elseif (!empty($listing_img[0]) && empty($listing_prv_img)) {
                                    echo '<img src="' . esc_url(wp_get_attachment_image_url($listing_img[0], array(90, 90))) . '" alt="listing image">';
                                } else {
                                    echo '<img src="' . $default_image . '" alt="listing image">';
                                }

                                ?>
                            </div>
                            <div class="atbd_right_content">
                                <div class="cate_title">
                                    <h4>
                                        <a href="<?= esc_url(get_post_permalink(get_the_ID())); ?>"><?= esc_html(stripslashes(get_the_title())); ?></a>
                                    </h4>
                                    <?php if (!empty($price) && ('price' === $listing_pricing)) { ?>
                                        <span><?php atbdp_display_price($price); ?></span>

                                    <?php }else{
                                        $output = atbdp_display_price_range($price_range);
                                        echo $output;
                                    } ?>
                                </div>

                                <?php if (!empty($cats)) {
                                    $totalTerm = count($cats);
                                    ?>

                                    <p class="directory_tag">
                                        <span class="fa fa-folder-open">
                                        <span>
                                                <a href="<?= ATBDP_Permalink::atbdp_get_category_page($cats[0]); ?>">
                                                                     <?= esc_html($cats[0]->name); ?>
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
                    }
                    }; ?>
                </ul>
            </div> <!--ends featured listing-->


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
            $title = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Featured Listings', ATBDP_TEXTDOMAIN);
            $f_listing_num = !empty($instance['f_listing_num']) ? $instance['f_listing_num'] : 5;
            ?>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_attr_e('Title:', ATBDP_TEXTDOMAIN); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                       name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text"
                       value="<?php echo esc_attr($title); ?>">
            </p>

            <p>
                <label for="<?php echo esc_attr($this->get_field_id('f_listing_num')); ?>"><?php esc_attr_e('Number of Listings:', ATBDP_TEXTDOMAIN); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('f_listing_num')); ?>"
                       name="<?php echo esc_attr($this->get_field_name('f_listing_num')); ?>" type="text"
                       value="<?php echo esc_attr($f_listing_num); ?>">
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
            $instance['f_listing_num'] = (!empty($new_instance['f_listing_num'])) ? intval($new_instance['f_listing_num']) : '';

            return $instance;
        }

    } // class BD_Featured_Listings_Widget


}
