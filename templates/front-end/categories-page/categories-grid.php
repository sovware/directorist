<?php
if (5 == $categories_settings['columns']) {
    $span = 'atbdp_col-5';
} else {
    $span = 'col-md-' . floor(12 / $categories_settings['columns']). ' col-sm-6';
}
$container_fluid = 'container-fluid';
?>
<div id="directorist" class="atbd_wrapper">
    <div class="<?php echo apply_filters('atbdp_cat_container_fluid', $container_fluid); ?>">
        <div class="col-md-12">
            <div class="atbd_all_categories  atbdp-no-margin">
                <div class="row">
                    <?php
                    $terms = is_array($terms) ? $terms : array();
                    foreach ($terms as $term) {
                        $count = 0;
                        if (!empty($categories_settings['hide_empty']) || !empty($categories_settings['show_count'])) {
                            $count = atbdp_listings_count_by_category($term->term_id);

                            if (!empty($categories_settings['hide_empty']) && 0 == $count) continue;
                        }
                        $icon = get_term_meta($term->term_id, 'category_icon', true);
                        $image = get_term_meta($term->term_id, 'image', true);
                        $cat_image = wp_get_attachment_image_src($image, apply_filters('atbdp_category_image_size', array('350', '280')))[0];
                        $icon = !empty($icon) ? $icon : '';

                        ?>
                        <div class="<?php echo $span; ?>">
                            <a class="atbd_category_single <?php echo !empty($cat_image) ? '' : 'atbd_category-default'; ?>"
                               href="<?php echo ATBDP_Permalink::atbdp_get_category_page($term) ?>">
                                <figure>
                                    <?php if (!empty($cat_image)) {
                                        ?>
                                        <img src="<?php echo !empty($cat_image) ? $cat_image : ATBDP_PUBLIC_ASSETS . 'images/grid.jpg' ?>"
                                             alt="">
                                        <?php
                                    } ?>
                                    <figcaption class="overlay-bg">
                                        <div class="cat-box">
                                            <div><?php
                                                if (('none' != $icon)) {
                                                    ?>
                                                    <div class="icon">

                                                        <span class="fa <?php echo !empty($icon) ? $icon : ''; ?>"></span>

                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                                <div class="cat-info">
                                                    <h4 class="cat-name">
                                                        <?php echo $term->name; ?>
                                                    </h4>

                                                    <?php
                                                    $html = '';
                                                    if (!empty($categories_settings['show_count'])) {
                                                        $expired_listings = atbdp_get_expired_listings(ATBDP_CATEGORY, $term->term_id);
                                                        $number_of_expired = $expired_listings->post_count;
                                                        $number_of_expired = !empty($number_of_expired) ? $number_of_expired : '0';
                                                        $totat = ($count) ? ($count - $number_of_expired) : $count;
                                                        $html = "<span>(" . $totat . ")</span>";
                                                    }
                                                    /**
                                                     * @since 5.0.0
                                                     */
                                                    echo apply_filters('atbdp_all_categories_after_category_name', $html, $term);
                                                    ?>

                                                </div>
                                            </div>
                                        </div>
                                    </figcaption>
                                </figure>
                            </a>

                        </div>
                        <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
