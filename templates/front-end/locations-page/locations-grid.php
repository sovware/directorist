<?php $span = 'col-md-' . floor( 12 /  $locations_settings['columns'] );?>
<div id="directorist" class="atbd_wrapper">
    <div class="atbd_location_grid_wrap row">
        <?php
        $terms = is_array($terms) ? $terms : array();
        foreach ($terms as $term) {
            $count = 0;
            if (!empty($locations_settings['hide_empty']) || !empty($locations_settings['show_count'])) {
                $count = atbdp_listings_count_by_location($term->term_id);

                if (!empty($locations_settings['hide_empty']) && 0 == $count) continue;
            } ?>
            <div class="<?php echo $span;?>">
            <a class="atbd_location_grid" href="<?php echo ATBDP_Permalink::get_location_archive($term) ?>"
               class=""> <?php echo $term->name; ?>
                <?php
                if (!empty($locations_settings['show_count'])) {
                    echo "(" . $count . ")";
                }
                ?>
            </a>
            </div>
            <!--                </div>-->
        <?php } ?>
    </div>

</div>