<?php
wp_enqueue_script('atbdp-map-view');
$display_map_info               = get_directorist_option('display_map_info', 1);
$display_image_map              = get_directorist_option('display_image_map', 1);
$display_title_map              = get_directorist_option('display_title_map', 1);
$display_address_map            = get_directorist_option('display_address_map', 1);
$display_direction_map          = get_directorist_option('display_direction_map', 1);

$disable_single_listing         = get_directorist_option('disable_single_listing', false);
$disable_single_listing         = ( $disable_single_listing === true || $disable_single_listing === '1' ) ? true : false;

if(empty($display_map_info)) {
    $disable_info_window = 'yes';
}elseif (empty($display_image_map || $display_title_map || $display_address_map || $display_direction_map)){
    $disable_info_window = 'yes';
}else{
    $disable_info_window = 'no';
}
$data = array(
    'plugin_url' => ATBDP_URL,
    'disable_info_window' => $disable_info_window,
    'zoom'       => !empty($zoom) ? $zoom : 1,
);
wp_localize_script( 'atbdp-map-view', 'atbdp_map', $data );
?>
<div class="atbdp-body atbdp-map embed-responsive embed-responsive-16by9 atbdp-margin-bottom" data-type="markerclusterer" style="height: <?php echo !empty($listings_map_height)?$listings_map_height:'';?>px;">
    <?php while( $all_listings->have_posts() ) : $all_listings->the_post();
        global $post;
        $manual_lat                     = get_post_meta($post->ID, '_manual_lat', true);
        $manual_lng                     = get_post_meta($post->ID, '_manual_lng', true);
        $listing_img                    = get_post_meta(get_the_ID(), '_listing_img', true);
        $listing_prv_img                = get_post_meta(get_the_ID(), '_listing_prv_img', true);
        $crop_width                     = get_directorist_option('crop_width', 360);
        $crop_height                    = get_directorist_option('crop_height', 300);
        $address                        = get_post_meta(get_the_ID(), '_address', true);
        $font_type = get_directorist_option('font_type','line');
        $fa_or_la = ('line' == $font_type) ? "la " : "fa ";
        $cats                           = get_the_terms(get_the_ID(), ATBDP_CATEGORY);
        if(!empty($cats)){
            $cat_icon                       = get_cat_icon($cats[0]->term_id);
        }
        $cat_icon = !empty($cat_icon) ? $cat_icon : 'fa-map-marker';
        $icon_type = substr($cat_icon, 0,2);
        $fa_or_la = ('la' == $icon_type) ? "la " : "fa ";
        $cat_icon = ('none' == $cat_icon) ? 'fa fa-map-marker' : $fa_or_la . $cat_icon ;
        if(!empty($listing_prv_img)) {

            $prv_image   = atbdp_get_image_source($listing_prv_img, 'large');

        }
        if(!empty($listing_img[0])) {

            $default_img = atbdp_image_cropping(ATBDP_PUBLIC_ASSETS . 'images/grid.jpg', $crop_width, $crop_height, true, 100)['url'];
            $gallery_img = atbdp_get_image_source($listing_img[0], 'medium');

        }
        ?>
        <?php if( ! empty( $manual_lat ) && ! empty( $manual_lng ) ) : ?>
            <div class="marker" data-latitude="<?php echo $manual_lat; ?>" data-longitude="<?php echo $manual_lng; ?>" data-icon="<?php echo !empty($cats) ? $cat_icon : 'fa fa-map-marker';?>">
                <?php if(!empty($display_map_info) && (!empty($display_image_map) || !empty($display_title_map)|| !empty($display_address_map) || !empty($display_direction_map))) { ?>
                <div class="map-info-wrapper">
                    <input type="hidden" id="icon" value="fa fa-flag">
                    <?php if(!empty($display_image_map)) { ?>
                    <div class="map-info-img">
                        <?php
                        if ( !$disable_single_listing ) {
                            echo "<a href='". get_the_permalink() ."'>";
                        }
                        $default_image = get_directorist_option('default_preview_image', ATBDP_PUBLIC_ASSETS . 'images/grid.jpg');
                        if(!empty($listing_prv_img)){

                            echo '<img src="'.esc_url($prv_image).'" alt="'.esc_html(stripslashes(get_the_title())).'">';

                        }if(!empty($listing_img[0]) && empty($listing_prv_img)) {

                            echo '<img src="' . esc_url($gallery_img) . '" alt="'.esc_html(stripslashes(get_the_title())).'">';

                        }if (empty($listing_img[0]) && empty($listing_prv_img)){

                            echo '<img src="'.$default_image.'" alt="'.esc_html(stripslashes(get_the_title())).'">';

                        }
                        if ( !$disable_single_listing ) {
                            echo "</a>";
                        }
                        ?>
                    </div>
                    <?php } ?>
                    <?php if(!empty($display_title_map) || !empty($display_address_map) || !empty($display_direction_map)) { ?>
                    <div class="map-info-details">
                        <?php if(!empty($display_title_map)) { ?>
                        <div class="atbdp-listings-title-block">
                            <h3 class="atbdp-no-margin">
                                <?php if ( !$disable_single_listing ) : ?>
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                <?php else: 
                                    the_title();
                                endif ; ?>
                            </h3>
                        </div>
                        <?php } ?>
                        <?php if(!empty($address)) { ?>
                            <?php if(!empty(!empty($display_address_map))) {?>
                        <div class="map_addr"><span class="<?php atbdp_icon_type(true); ?>-map-marker"></span> <a href="" class="map-info-link"><?php echo $address;?></a></div>
                        <?php } ?>
                            <?php if(!empty($display_direction_map)) {?>
                            <div class="map_get_dir"><a href='http://www.google.com/maps?daddr=<?php echo $manual_lat; ?>,<?php echo $manual_lng; ?>' target='_blank'><?php _e('Get Direction', 'directorist') ?></a> <span class="<?php atbdp_icon_type(true); ?>-arrow-right"></span>
                            </div>
                        <?php } } ?>

                        <?php do_action( 'atbdp_after_listing_content', $post->ID, 'map' ); ?>
                    </div>
                    <?php } ?>
                    <span id="iw-close-btn"><i class="la la-times"></i></span>
                </div>
                <?php } ?>
            </div>
        <?php endif; ?>

    <?php endwhile;
    wp_reset_postdata();
    ?>
</div>
