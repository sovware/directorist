<?php
/**
 * @author  wpWax
 * @since   7.2.0
 * @version 7.2.0
 */

use Directorist\Directorist_Listings;

if ( ! defined( 'ABSPATH' ) ) exit;

$count = !empty( $instance['pop_listing_num'] ) ? $instance['pop_listing_num'] : 5;

$query = Directorist_Listings::popular_listings_query( $count );

if ( !$query->have_posts() ) {
    return;
}

$popular_listings = Directorist_Listings::popular_listings_query( $count );
?>

<div class="atbd_categorized_listings">
    <ul class="listings">
        <?php while ( $query->have_posts() ): ?>

            <?php
            $query->the_post();
            $id = get_the_ID();
            $disable_single_listing = get_directorist_option('disable_single_listing');
            $top_category = ATBDP()->taxonomy->get_one_high_level_term($id, ATBDP_CATEGORY);
            $listing_img = get_post_meta($id, '_listing_img', true);
            $listing_prv_img = get_post_meta($id, '_listing_prv_img', true);
            $cats = get_the_terms($id, ATBDP_CATEGORY);
            $post_link = get_the_permalink( $id );

            ?>
        
            <li>

                <div class="atbd_left_img">
                    <?php if ( empty( $disable_single_listing) ) { ?>
                        <a href="<?php the_permalink(); ?>">
                        <?php
                    }
                    $default_image = get_directorist_option('default_preview_image', DIRECTORIST_ASSETS . 'images/grid.jpg');
                    if (!empty($listing_prv_img)) {
                        echo '<img src="' . esc_url(wp_get_attachment_image_url($listing_prv_img, array(90, 90))) . '" alt="' . esc_html(get_the_title()) . '">';
                    } elseif (!empty($listing_img[0]) && empty($listing_prv_img)) {
                        echo '<img src="' . esc_url(wp_get_attachment_image_url($listing_img[0], array(90, 90))) . '" alt="' . esc_html(get_the_title()) . '">';
                    } else {
                        echo '<img src="' . $default_image . '" alt="' . esc_html(get_the_title()) . '">';
                    }
                    if (empty($disable_single_listing)) {
                        echo '</a>';
                    }
                    ?>
                </div>

                <div class="atbd_right_content">
                    <div class="cate_title">
                        <h4>
                            <?php
                            if (empty($disable_single_listing)) {
                                ?>
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                <?php
                            } else {
                                the_title();
                            } ?>
                        </h4>
                    </div>
                </div>

                <?php
                if (!empty($cats)) {
                    $totalTerm = count($cats);
                    ?>

                    <p class="directory_tag">
                        <span class="<?php atbdp_icon_type(true); ?>-tags"></span>
                        <span>
                                <a href="<?php echo ATBDP_Permalink::atbdp_get_category_page($cats[0]); ?>">
                                                        <?php echo esc_html($cats[0]->name); ?>
                                </a>
                            <?php
                            if ($totalTerm > 1) {
                                ?>
                                <span class="atbd_cat_popup">  +<?php echo $totalTerm - 1; ?>
                                    <span class="atbd_cat_popup_wrapper">
                                                    <?php
                                                    $output = array();
                                                    foreach (array_slice($cats, 1) as $cat) {
                                                        $link = ATBDP_Permalink::atbdp_get_category_page($cat);
                                                        $space = str_repeat(' ', 1);
                                                        $output [] = "{$space}<a href='{$link}'>{$cat->name}<span>,</span></a>";
                                                    } ?>
                                        <span><?php echo join($output) ?></span>
                                                </span>
                                            </span>
                            <?php } ?>

                        </span>
                    </p>
                    <?php
                }

                ATBDP()->show_static_rating(get_post($id));
                ?>

            </li>


        <?php endwhile; ?>
    </ul>
</div>





<?php
return;
if ($popular_listings->have_posts()) { ?>
    <div class="atbd_categorized_listings">
        <ul class="listings">
            <?php foreach ($popular_listings->posts as $pop_post) {
                // get only one parent or high level term object
                $top_category = ATBDP()->taxonomy->get_one_high_level_term($id, ATBDP_CATEGORY);
                $listing_img = get_post_meta($id, '_listing_img', true);
                $listing_prv_img = get_post_meta($id, '_listing_prv_img', true);
                $cats = get_the_terms($id, ATBDP_CATEGORY);
                $post_link = get_the_permalink( $id );
                ?>
                <li>
                    <div class="atbd_left_img">
                        <?php
                        $disable_single_listing = get_directorist_option('disable_single_listing');
                        if (empty($disable_single_listing)){
                        ?>
                        <a href="<?php echo esc_url( $post_link ); ?>">
                            <?php
                            }
                            $default_image = get_directorist_option('default_preview_image', DIRECTORIST_ASSETS . 'images/grid.jpg');
                            if (!empty($listing_prv_img)) {
                                echo '<img src="' . esc_url(wp_get_attachment_image_url($listing_prv_img, array(90, 90))) . '" alt="' . esc_html(get_the_title()) . '">';
                            } elseif (!empty($listing_img[0]) && empty($listing_prv_img)) {
                                echo '<img src="' . esc_url(wp_get_attachment_image_url($listing_img[0], array(90, 90))) . '" alt="' . esc_html(get_the_title()) . '">';
                            } else {
                                echo '<img src="' . $default_image . '" alt="' . esc_html(get_the_title()) . '">';
                            }
                            if (empty($disable_single_listing)) {
                                echo '</a>';
                            }
                            ?>
                    </div>
                    <div class="atbd_right_content">
                        <div class="cate_title">
                            <h4>
                                <?php
                                if (empty($disable_single_listing)) {
                                    ?>
                                    <a href="<?php echo esc_url($post_link); ?>"><?php echo esc_html(get_the_title()); ?></a>
                                    <?php
                                } else {
                                    echo esc_html(get_the_title());
                                } ?>
                            </h4>
                        </div>

                        <?php if (!empty($cats)) {
                            $totalTerm = count($cats);
                            ?>

                            <p class="directory_tag">
                                <span class="<?php atbdp_icon_type(true); ?>-tags"></span>
                                <span>
                                        <a href="<?php echo ATBDP_Permalink::atbdp_get_category_page($cats[0]); ?>">
                                                             <?php echo esc_html($cats[0]->name); ?>
                                        </a>
                                    <?php
                                    if ($totalTerm > 1) {
                                        ?>
                                        <span class="atbd_cat_popup">  +<?php echo $totalTerm - 1; ?>
                                            <span class="atbd_cat_popup_wrapper">
                                                            <?php
                                                            $output = array();
                                                            foreach (array_slice($cats, 1) as $cat) {
                                                                $link = ATBDP_Permalink::atbdp_get_category_page($cat);
                                                                $space = str_repeat(' ', 1);
                                                                $output [] = "{$space}<a href='{$link}'>{$cat->name}<span>,</span></a>";
                                                            } ?>
                                                <span><?php echo join($output) ?></span>
                                                        </span>
                                                    </span>
                                    <?php } ?>

                                </span>
                            </p>
                        <?php }
                        ATBDP()->show_static_rating($pop_post);
                        ?>
                    </div>
                </li>
            <?php } // ends the loop
            ?>

        </ul>
    </div> <!--ends .categorized_listings-->
    <?php
}