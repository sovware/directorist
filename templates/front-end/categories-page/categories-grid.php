<div class="col-md-12">
    <?php
    $terms = is_array($terms) ? $terms : array();
    foreach($terms as $term) {
        $count = 0;
        if(!empty($categories_settings['hide_empty']) || !empty($categories_settings['show_count'])) {
            $count = atbdp_listings_count_by_category($term->term_id);

            if(!empty($categories_settings['hide_empty']) && 0 == $count) continue;
        }
        $icon = get_term_meta($term->term_id,'category_icon',true);
        ?>
        <div class="col-md-2">
            <a href="<?php  echo ATBDP_Permalink::get_category_archive($term) ?>" class="fa <?php echo !empty($icon) ? $icon : '';?>"> <?php echo $term->name;?>
                <?php
                if(!empty($categories_settings['show_count'])){
                    echo "( ". $count ." )";
                }
                ?>
            </a>

        </div>
    <?php } ?>
</div>