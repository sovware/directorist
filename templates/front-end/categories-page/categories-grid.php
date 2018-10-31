<div class="col-md-12">
    <?php
    $terms = is_array($terms) ? $terms : array();
    foreach($terms as $term) {
        $count = 0;
        if(!empty($hide_empty) || !empty($display_listing_count)) {
            $count = atbdp_listings_count_by_category($term->term_id);

            if(!empty($hide_empty) && 0 == $count) continue;
        }
        $icon = get_term_meta($term->term_id,'category_icon',true);
        ?>
        <div class="col-md-2">
            <a class="fa <?php echo !empty($icon) ? $icon : '';?>"> <?php echo $term->name;?>
                <?php
                if(!empty($display_listing_count)){
                    echo "( ". $count ." )";
                }
                ?>
            </a>

        </div>
    <?php } ?>
</div>