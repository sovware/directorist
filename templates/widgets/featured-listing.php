<?php
/**
 * @author  wpWax
 * @since   7.3.0
 * @version 8.0.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

use Directorist\Review\Markup;

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
$default_icon = 'las la-tags';
?>
<div class="directorist-card__body">
    <div class="directorist-widget-listing">
        <?php
        if ($featured_listings->have_posts()) {
            while ($featured_listings->have_posts()) {
                $featured_listings->the_post();
                $review_rating = directorist_get_listing_rating( get_the_ID() );
                $review_count  = directorist_get_listing_review_count( get_the_ID() );
                $review_text   = sprintf( _n( '%s review', $review_count > 0 ?  '%s reviews' : '%s review', $review_count, 'directorist' ), number_format_i18n( $review_count ) );
                // get only one parent or high level term object
                $listing_img = directorist_get_listing_gallery_images( get_the_ID() );
                $listing_prv_img = directorist_get_listing_preview_image( get_the_ID() );
                $listing_reviews = get_post_meta( get_the_ID(), '_directorist_reviews', true );
                $price = get_post_meta(get_the_ID(), '_price', true);
                $price_range = get_post_meta(get_the_ID(), '_price_range', true);
                $listing_pricing = get_post_meta(get_the_ID(), '_atbd_listing_pricing', true);
                $cats = get_the_terms(get_the_ID(), ATBDP_CATEGORY);
        ?>
                <div class="directorist-widget-listing__single">
                    <div class="directorist-widget-listing__image">
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
                    <div class="directorist-widget-listing__content">
                        <h4 class="directorist-widget-listing__title"><a href="<?php echo esc_url(get_post_permalink(get_the_ID())); ?>"><?php echo esc_html(stripslashes(get_the_title())); ?></a></h4>
                        <div class="directorist-widget-listing__meta">
                            <span class="directorist-widget-listing__rating">
                                <?php Markup::show_rating_stars( $review_rating );?>
                            </span>
                            <span class="directorist-widget-listing__rating-point"><?php echo esc_html( $review_rating ); ?></span>
                            <span class="directorist-widget-listing__reviews">(<?php echo $review_text ?>)</span>
                        </div>
                        <span class="directorist-widget-listing__price">
                            <?php if (!empty($price) && ('price' === $listing_pricing)) { ?>
                                <span><?php atbdp_display_price($price); ?></span>

                            <?php } else {
                                $output = atbdp_display_price_range($price_range);
                                echo wp_kses_post( $output );
                            } ?>
                        </span>
                    </div>
                </div>
        <?php
            }
            wp_reset_postdata();
        }; ?>
    </div>
</div>
<!--ends featured listing-->