<?php $span = 'col-md-' . floor( 12 /  $categories_settings['columns'] );?>
<div id="directorist" class="atbd_wrapper">
    <div class="container-fluid">
        <div class="col-md-12">
            <ul class="atbd_all_categories row">
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
                    $icon = !empty($icon)?$icon:'';
                    if( $i % $categories_settings['columns'] == 0 ) {
                        echo '<div class="row atbdp-no-margin">';
                    }
                    ?>
                    <li class="<?php echo $span;?>">
                        <a href="<?php  echo ATBDP_Permalink::get_category_archive($term) ?>">
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
                                ?></p>
                        </a>
                    </li>

                <?php
                    $i++;

                    if( $i % $categories_settings['columns'] == 0 || $i == count( $terms ) ) {
                        echo '</div>';
                    }
                } ?>
            </ul>
        </div>
    </div>
</div>
