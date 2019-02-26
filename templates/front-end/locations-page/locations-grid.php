
<div id="directorist" class="atbd_wrapper">
    <div class="atbd_location_grid_wrap">
        <?php
        $terms = is_array($terms) ? $terms : array();
        $span = 'col-md-' . floor( 12 /  $locations_settings['columns'] );
        $i = 0;
        foreach ($terms as $term) {
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
            <a class="atbd_location_grid" href="<?php echo ATBDP_Permalink::atbdp_get_location_page($term) ?>"
               class=""> <?php echo $term->name; ?>
                <?php
                if (!empty($locations_settings['show_count'])) {
                    echo "(" . $count . ")";
                }
                ?>
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