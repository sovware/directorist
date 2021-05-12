<?php
$display_map = get_directorist_option('display_map_field',1);
$select_listing_map         = get_directorist_option('select_listing_map','google');
if(!empty($display_map) && 'google' == $select_listing_map) {
    $title = !empty($instance['title']) ? esc_html($instance['title']) : esc_html__('Map', 'directorist');
    echo $args['before_widget'];
    echo '<div class="atbd_widget_title">';
    echo $args['before_title'] . esc_html(apply_filters('widget_title', $title)) . $args['after_title'];
    echo '</div>';

    $map = array(
        'post_type' => ATBDP_POST_TYPE,
        'post_status' => 'publish',
        'posts_per_page' => -1,
    );
    $all_listings = new WP_Query($map); ?>
    <!-- the loop -->
    <?php
    if('google' == $select_listing_map) {
        $map_zoom_level = !empty($instance['zoom']) ? esc_html($instance['zoom']) : 3;
        wp_enqueue_script('atbdp-map-view', DIRECTORIST_ASSETS . 'other/map-view.js');
        $data = array(
            'plugin_url' => ATBDP_URL,
            'zoom' => !empty($map_zoom_level) ? $map_zoom_level : 3
        );
        wp_localize_script('atbdp-map-view', 'atbdp_map', $data);
    ?>
        <div class="atbdp-body atbdp-map embed-responsive embed-responsive-16by9 atbdp-margin-bottom"
         data-type="markerclusterer" style="height: 330px;">
        <?php while ($all_listings->have_posts()) : $all_listings->the_post();
            global $post;
            $manual_lat = get_post_meta($post->ID, '_manual_lat', true);
            $manual_lng = get_post_meta($post->ID, '_manual_lng', true);
            $listing_img = get_post_meta(get_the_ID(), '_listing_img', true);
            $listing_prv_img = get_post_meta(get_the_ID(), '_listing_prv_img', true);
            $crop_width = get_directorist_option('crop_width', 360);
            $crop_height = get_directorist_option('crop_height', 300);
            $address = get_post_meta(get_the_ID(), '_address', true);
            $font_type = get_directorist_option('font_type','line');
            $fa_or_la = ('line' == $font_type) ? "la " : "fa ";
            $cats                           = get_the_terms(get_the_ID(), ATBDP_CATEGORY);
            if(!empty($cats)){
                $cat_icon                       = get_cat_icon($cats[0]->term_id);
            }
            if (!empty($listing_prv_img)) {
                $prv_image = atbdp_get_image_source($listing_prv_img, 'large');
            }
            if (!empty($listing_img[0])) {

                $default_img = atbdp_image_cropping(DIRECTORIST_ASSETS . 'images/grid.jpg', $crop_width, $crop_height, true, 100)['url'];;
                $gallery_img = atbdp_get_image_source($listing_img[0], 'medium');

            }
            ?>

            <?php if (!empty($manual_lat) && !empty($manual_lng)) : ?>
                <div class="marker" data-latitude="<?php echo $manual_lat; ?>"
                     data-longitude="<?php echo $manual_lng; ?>" data-icon="<?php echo !empty($cat_icon) ? $fa_or_la . $cat_icon : 'fa fa-map-marker';?>">
                    <div>

                        <div class="media-left">
                            <a href="<?php the_permalink(); ?>">
                                <?php
                                $default_image = get_directorist_option('default_preview_image', DIRECTORIST_ASSETS . 'images/grid.jpg');
                                if (!empty($listing_prv_img)) {

                                    echo '<img src="' . esc_url($prv_image) . '" alt="' . esc_html(stripslashes(get_the_title())) . '">';

                                }
                                if (!empty($listing_img[0]) && empty($listing_prv_img)) {

                                    echo '<img src="' . esc_url($gallery_img) . '" alt="' . esc_html(stripslashes(get_the_title())) . '">';

                                }
                                if (empty($listing_img[0]) && empty($listing_prv_img)) {

                                    echo '<img src="' . $default_image . '" alt="' . esc_html(stripslashes(get_the_title())) . '">';

                                }
                                ?>
                            </a>
                        </div>


                        <div class="media-body">
                            <div class="atbdp-listings-title-block">
                                <h3 class="atbdp-no-margin"><a
                                        href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

                            </div>

                            <span class="<?php atbdp_icon_type(true);?>-map-marker"></span> <a
                                href="" class="map-info-link"><?php echo $address; ?></a>


                            <?php do_action('atbdp_after_listing_content', $post->ID, 'map'); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        <?php endwhile; ?>
    </div>
    <?php } ?>
    <!-- end of the loop -->

    <!-- Use reset postdata to restore orginal query -->
    <?php wp_reset_postdata();

    echo $args['after_widget'];
}