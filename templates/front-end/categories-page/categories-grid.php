<?php $span = 'col-md-' . floor( 12 /  $categories_settings['columns'] );?>
<div id="directorist" class="atbd_wrapper">
    <div class="container-fluid">
        <div class="col-md-12">
            <div class="atbd_all_categories">
                <?php
                $terms = is_array($terms) ? $terms : array();
                $i = 0;
                foreach($terms as $term) {
                    $count = 0;
                    if(!empty($categories_settings['hide_empty']) || !empty($categories_settings['show_count'])) {
                        $count = atbdp_listings_count_by_category($term->term_id);

                        if(!empty($categories_settings['hide_empty']) && 0 == $count) continue;
                    }
                    $icon = get_term_meta($term->term_id,'category_icon',true);
                    $image = get_term_meta($term->term_id,'image',true);
                    $cat_image = wp_get_attachment_image_src($image, array('350', '280'))[0];
                    $icon = !empty($icon)?$icon:'';
                    if( $i % $categories_settings['columns'] == 0 ) {
                        echo '<div class="row atbdp-no-margin">';
                    }
                    ?>
                    <div class="<?php echo $span;?>">
                        <a class="atbd_category_single" href="<?php  echo ATBDP_Permalink::atbdp_get_category_page($term) ?>">
                            <img src="<?php echo !empty($cat_image)?$cat_image: ATBDP_PUBLIC_ASSETS . 'images/grid.jpg'?>" alt="">
                            <div class="atbd_category_single_content">
                                <?php
                                if ('none' != $icon){
                                ?>
                                    <span class="fa <?php echo !empty($icon) ? $icon : '';?>"></span>
                                <?php
                                }
                                ?>
                                <p><?php echo $term->name;?>
                                    <?php
                                    if(!empty($categories_settings['show_count'])){
                                        echo "(". $count .")";
                                    }
                                    ?>
                                </p>
                            </div>
                        </a>
                    </div>

                <?php
                    $i++;

                    if( $i % $categories_settings['columns'] == 0 || $i == count( $terms ) ) {
                        echo '</div>';
                    }
                } ?>
            </div>
        </div>
    </div>
</div>
