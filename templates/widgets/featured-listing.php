<?php
/**
 * @author  wpWax
 * @since   7.3.0
 * @version 7.4.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
$f_listing_num = !empty($instance['f_listing_num']) ? $instance['f_listing_num'] : 5;

$featured_args = array(
    'post_type' => ATBDP_POST_TYPE,
    'post_status' => 'publish',
    'posts_per_page' => (int)$f_listing_num,
    'meta_query' => array(
        array(
            'key' => '_featured',
            'value' => 1,
            'compare' => '='
        )
    )
);
/**
 * Filter to modify featured listings arguments.
 *
 * @since 7.3.1
 *
 * @param array $featured_args  Featured Arguments.
 */
apply_filters( "directorist_widget_featured_listings_query_arguments", $featured_args );

$featured_listings = new WP_Query($featured_args);
?>
<div class="atbd_categorized_listings">
    <ul class="listings">
        <?php
        if ($featured_listings->have_posts()) {
            while ($featured_listings->have_posts()) {
                $featured_listings->the_post();
                // get only one parent or high level term object
                $listing_img = get_post_meta(get_the_ID(), '_listing_img', true);
                $listing_prv_img = get_post_meta(get_the_ID(), '_listing_prv_img', true);
                $price = get_post_meta(get_the_ID(), '_price', true);
                $price_range = get_post_meta(get_the_ID(), '_price_range', true);
                $listing_pricing = get_post_meta(get_the_ID(), '_atbd_listing_pricing', true);
                $cats = get_the_terms(get_the_ID(), ATBDP_CATEGORY);
                ?>
                <li>
                    <div class="atbd_left_img">
                        <?php
                        $default_image = get_directorist_option('default_preview_image', DIRECTORIST_ASSETS . 'images/grid.jpg');
                        if (!empty($listing_prv_img)) {
                            echo '<a href="'.esc_url( get_the_permalink() ).'"><img src="' . esc_url(wp_get_attachment_image_url($listing_prv_img, array(90, 90))) . '" alt="listing image"></a>';
                        } elseif (!empty($listing_img[0]) && empty($listing_prv_img)) {
                            echo '<a href="'.esc_url( get_the_permalink() ).'"><img src="' . esc_url(wp_get_attachment_image_url($listing_img[0], array(90, 90))) . '" alt="listing image"></a>';
                        } else {
                            echo '<a href="'.esc_url( get_the_permalink() ).'"><img src="' . esc_url( $default_image ) . '" alt="listing image"></a>';
                        }

                        ?>
                    </div>
                    <div class="atbd_right_content">
                        <div class="cate_title">
                            <h4>
                                <a href="<?php echo esc_url(get_post_permalink(get_the_ID())); ?>"><?php echo esc_html(stripslashes(get_the_title())); ?></a>
                            </h4>
                            <?php if (!empty($price) && ('price' === $listing_pricing)) { ?>
                                <span><?php atbdp_display_price($price); ?></span>

                            <?php } else {
                                $output = atbdp_display_price_range($price_range);
                                echo wp_kses_post( $output );
                            } ?>
                        </div>

                        <?php if (!empty($cats)) {
                            $totalTerm = count($cats);
                            ?>

                            <p class="directory_tag">
							<?php directorist_icon( 'las la-tags' ); ?>
                                <span>
                                            <a href="<?php echo esc_url( ATBDP_Permalink::atbdp_get_category_page($cats[0]) ); ?>">
                                                                    <?php echo esc_html($cats[0]->name); ?>
                                            </a>
                                        <?php
                                        if ($totalTerm > 1) {
                                            ?>
                                            <span class="atbd_cat_popup">  +<?php echo esc_html( $totalTerm - 1 ); ?>
                                                <span class="atbd_cat_popup_wrapper">
                                                                <?php
                                                                $output = array();
                                                                foreach (array_slice($cats, 1) as $cat) {
                                                                    $link = ATBDP_Permalink::atbdp_get_category_page($cat);
                                                                    $space = str_repeat(' ', 1);
                                                                    $output [] = "{$space}<a href='{$link}'>{$cat->name}<span>,</span></a>";
                                                                } ?>
                                                    <span><?php echo wp_kses_post( join($output) ); ?></span>
                                                            </span>
                                                        </span>
                                        <?php } ?>

                                    </span>
                            </p>
                        <?php } ?>
                    </div>
                </li>
                <?php
            }
            wp_reset_postdata();
        }; ?>
    </ul>
</div> <!--ends featured listing-->

