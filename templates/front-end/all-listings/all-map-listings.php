<?php
/**
 * This template displays the Directorist listings in map view.
 */

!empty($args['data']) ? extract($args['data']) : array(); // data array contains all required var.
$all_listings               = !empty($all_listings) ? $all_listings : new WP_Query;
$is_disable_price           = get_directorist_option('disable_list_price');
$display_sortby_dropdown    = get_directorist_option('display_sort_by',1);
$display_viewas_dropdown    = get_directorist_option('display_view_as',1);
$pagenation                 = get_directorist_option('paginate_all_listings',1);
$select_listing_map                 = get_directorist_option('select_listing_map',1);
$zoom = get_directorist_option('map_zoom_level', 16);
wp_enqueue_script('atbdp-map-view',ATBDP_PUBLIC_ASSETS . 'js/map-view.js');
$data = array(
    'plugin_url' => ATBDP_URL,
    'zoom'       => !empty($zoom) ? $zoom : 16
);
wp_localize_script( 'atbdp-map-view', 'atbdp_map', $data );

?>
<div id="directorist" class="atbd_wrapper">
    <div class="atbdp atbdp-listings atbdp-map-view">
        <?php if( $display_header == 'yes'  ) { ?>
            <div class="header_bar">
                <div class="<?php echo is_directoria_active() ? 'container': 'container-fluid'; ?>">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="atbd_generic_header">
                                <div class="atbd_generic_header_title">
                                    <?php if(!empty($header_title)) {?>
                                        <h3>
                                            <?php echo esc_html($header_title); ?>
                                        </h3>
                                    <?php } ?>
                                    <p>
                                        <?php
                                        echo esc_html($header_sub_title) . ' ';
                                        if ($paginate){
                                            echo $all_listings->found_posts;
                                        }else{
                                            echo count($all_listings->posts);
                                        }
                                        ?>
                                    </p>
                                </div>
                                <?php if($display_viewas_dropdown || $display_sortby_dropdown) { ?>
                                    <div class="atbd_listing_action_btn btn-toolbar" role="toolbar">
                                        <!-- Views dropdown -->
                                        <?php if($display_viewas_dropdown) {
                                            $html = '<div class="dropdown">';
                                            $html .= '<a class="btn btn-outline-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            '. __( "View as", ATBDP_TEXTDOMAIN ).'<span class="caret"></span>
                                        </a>';
                                            $html .= '<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">';
                                            $views = atbdp_get_listings_view_options();
                                            foreach ($views as $value => $label) {
                                                $active_class = ($view == $value) ? ' active' : '';
                                                $html .= sprintf('<a class="dropdown-item%s" href="%s">%s</a>', $active_class, add_query_arg('view', $value), $label);

                                            }
                                            $html .= '</div>';
                                            $html .= '</div>';
                                            /**
                                             * @since 5.0.0
                                             * @package Directorist
                                             * @param htmlUms       $html it return the markup for list and grid
                                             * @param string        $view the shortcode attr view_as value
                                             * @param array         $views it return the views type array
                                             *
                                             */
                                            echo apply_filters('atbdp_listings_view_as', $html, $view, $views);
                                            ?>
                                        <?php } ?>
                                        <!-- Orderby dropdown -->
                                        <?php
                                        if($display_sortby_dropdown) {
                                            ?>
                                            <div class="dropdown">
                                                <a class="btn btn-outline-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <?php _e( "Sort by", ATBDP_TEXTDOMAIN ); ?> <span class="caret"></span>
                                                </a>

                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink2">
                                                    <?php
                                                    $options = atbdp_get_listings_orderby_options();

                                                    foreach( $options as $value => $label ) {
                                                        $active_class = ( $value == $current_order ) ? ' active' : '';
                                                        printf( '<a class="dropdown-item%s" href="%s">%s</a>', $active_class, add_query_arg( 'sort', $value ), $label );
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

        <div class="atbdp-divider"></div>

        <!-- the loop -->
        <div class="atbdp-body atbdp-map embed-responsive embed-responsive-16by9 atbdp-margin-bottom" data-type="markerclusterer">
            <?php while( $all_listings->have_posts() ) : $all_listings->the_post();
            global $post;
                $manual_lat         = get_post_meta($post->ID, '_manual_lat', true);
                $manual_lng        = get_post_meta($post->ID, '_manual_lng', true);
                $listing_img                    = get_post_meta(get_the_ID(), '_listing_img', true);
                $listing_prv_img                = get_post_meta(get_the_ID(), '_listing_prv_img', true);
                $crop_width                     = get_directorist_option('crop_width', 360);
                $crop_height                    = get_directorist_option('crop_height', 300);
                $address                        = get_post_meta(get_the_ID(), '_address', true);
                if(!empty($listing_prv_img)) {


                        $prv_image   = wp_get_attachment_image_src($listing_prv_img, 'large')[0];


                }
                if(!empty($listing_img[0])) {

                    $default_img = atbdp_image_cropping(ATBDP_PUBLIC_ASSETS . 'images/grid.jpg', $crop_width, $crop_height, true, 100)['url'];;
                    $gallery_img = wp_get_attachment_image_src($listing_img[0], 'medium')[0];


                }
                ?>

                <?php if( ! empty( $manual_lat ) && ! empty( $manual_lng ) ) : ?>
                    <div class="marker" data-latitude="<?php echo $manual_lat; ?>" data-longitude="<?php echo $manual_lng; ?>">
                        <div>

                                <div class="media-left">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php
                                        $default_image = get_directorist_option('default_preview_image', ATBDP_PUBLIC_ASSETS . 'images/grid.jpg');
                                        if(!empty($listing_prv_img)){

                                            echo '<img src="'.esc_url($prv_image).'" alt="'.esc_html(stripslashes(get_the_title())).'">';

                                        } if(!empty($listing_img[0]) && empty($listing_prv_img)) {

                                            echo '<img src="' . esc_url($gallery_img) . '" alt="'.esc_html(stripslashes(get_the_title())).'">';

                                        }if (empty($listing_img[0]) && empty($listing_prv_img)){

                                            echo '<img src="'.$default_image.'" alt="'.esc_html(stripslashes(get_the_title())).'">';

                                        }
                                        ?>
                                    </a>
                                </div>


                            <div class="media-body">
                                <div class="atbdp-listings-title-block">
                                    <h3 class="atbdp-no-margin"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

                                </div>

                                <span class="glyphicon glyphicon-briefcase"></span><a href=""><?php echo $address;?></a>


                                <?php do_action( 'atbdp_after_listing_content', $post->ID, 'map' ); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

            <?php endwhile; ?>
        </div>
        <!-- end of the loop -->

        <!-- Use reset postdata to restore orginal query -->
        <?php wp_reset_postdata(); ?>

        <div class="row atbd_listing_pagination">
            <?php
            if (1 == $pagenation){
                ?>
                <div class="col-md-12">
                    <div class="">
                        <?php
                        $paged = !empty($paged)?$paged:'';
                        echo atbdp_pagination($all_listings, $paged);
                        ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
