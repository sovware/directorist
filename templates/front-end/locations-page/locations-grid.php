
<div id="directorist" class="atbd_wrapper">
    <div class="atbd_location_grid_wrap">
        <?php
        $terms = is_array($terms) ? $terms : array();
        $span = 'col-md-' . floor( 12 /  $locations_settings['columns'] );
        $i = 0;
        foreach ($terms as $term) {
            $image = get_term_meta($term->term_id,'image',true);
            $location_image = wp_get_attachment_image_src($image, array('350', '280'))[0];
            $count = 0;
            if (!empty($locations_settings['hide_empty']) || !empty($locations_settings['show_count'])) {
                $count = atbdp_listings_count_by_location($term->term_id);

                if (!empty($locations_settings['hide_empty']) && 0 == $count) continue;
            }
            if( $i % $locations_settings['columns'] == 0 ) {
                echo '<div class="row atbdp-no-margin">';
            }
            ?>
            <div class="<?php echo $span;?>">

                <a class="atbd_location_grid <?php echo !empty($location_image)?'':'atbd_location_grid-default';?>" href="<?php echo ATBDP_Permalink::atbdp_get_location_page($term) ?>">
                    <figure>
                        <?php if(!empty($location_image)) {
                            ?>
                            <img src="<?php echo !empty($location_image)?$location_image: ATBDP_PUBLIC_ASSETS . 'images/grid.jpg'?>" alt="">
                            <?php
                        }?>
                        <figcaption>
                            <h3><?php echo $term->name; ?></h3>

                                <?php
                                $html = '';
                                if (!empty($locations_settings['show_count'])) {
                                    $expired_listings = atbdp_get_expired_listings(ATBDP_LOCATION, $term->term_id);
                                    $number_of_expired = $expired_listings->post_count;
                                    $number_of_expired = !empty($number_of_expired)?$number_of_expired:'0';
                                    $totat = ($count)?($count-$number_of_expired):$count;
                                    $html = "<p>(" . $totat . ")</p>";
                                }
                                /**
                                 * @since 5.0.0
                                 */
                                echo apply_filters('atbdp_all_locations_after_location_name', $html, $term);
                                ?>

                        </figcaption>
                    </figure>
                </a>
            </div>
       <?php
            $i++;
            if( $i % $locations_settings['columns'] == 0 || $i == count( $terms ) ) {
        echo '</div>';
    } ?>

        <?php } ?>
    </div>

</div>