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

                        <a class="atbd_category_single atbd_category-default" href="<?php  echo ATBDP_Permalink::atbdp_get_category_page($term) ?>">
                            <figure>
                                <img src="<?php echo !empty($cat_image)?$cat_image: ATBDP_PUBLIC_ASSETS . 'images/grid.jpg'?>" alt="">
                                <figcaption class="overlay-bg">
                                    <div class="cat-box">
                                        <div>
                                            <div class="icon">
                                                <?php
                                                if ('none' != $icon){
                                                    ?>
                                                    <span class="fa <?php echo !empty($icon) ? $icon : '';?>"></span>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                            <div class="cat-info">
                                                <h4 class="cat-name">
                                                    <?php echo $term->name;?>
                                                </h4>
                                                <span>
                                                    <?php
                                                    if(!empty($categories_settings['show_count'])){
                                                        echo "(". $count .")";
                                                    }
                                                    ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </figcaption>
                            </figure>
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
