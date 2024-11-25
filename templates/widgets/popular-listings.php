<?php
/**
 * @author  wpWax
 * @since   7.3.0
 * @version 8.0.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

use Directorist\Review\Markup;

if ( !$query->have_posts() ) {
    return;
}
$default_icon = 'las la-tags';
?>

<div class="directorist-card__body">
    <div class="directorist-widget-listing">
        <?php while ( $query->have_posts() ): ?>

            <?php
                $query->the_post();
                $id                     = get_the_ID();
                $disable_single_listing = get_directorist_option( 'disable_single_listing' );
                $top_category           = ATBDP()->taxonomy->get_one_high_level_term( $id, ATBDP_CATEGORY );
                $listing_img            = directorist_get_listing_gallery_images( $id );
                $listing_prv_img        = directorist_get_listing_preview_image( $id );
                $cats                   = get_the_terms( $id, ATBDP_CATEGORY );
                $post_link              = get_the_permalink( $id );
                $review_rating          = directorist_get_listing_rating( get_the_ID() );
                $review_count           = directorist_get_listing_review_count( get_the_ID() );
                $review_text            = sprintf( _n( '%s review', $review_count > 0 ?  '%s reviews' : '%s review', $review_count, 'directorist' ), number_format_i18n( $review_count ) );
                $price                  = get_post_meta( $id, '_price', true );
                $price_range            = get_post_meta( $id, '_price_range', true );
                $listing_pricing        = get_post_meta( $id, '_atbd_listing_pricing', true );
                ?>

                <div class="directorist-widget-listing__single">
                    <div class="directorist-widget-listing__image">
                        <?php if ( empty( $disable_single_listing ) ) { ?>
                            <a href="<?php echo esc_url( get_the_permalink() ); ?>">
                            <?php
                        }
                        $default_image = get_directorist_option( 'default_preview_image', DIRECTORIST_ASSETS . 'images/grid.jpg' );
                        if ( ! empty( $listing_prv_img ) ) {
                            echo '<img src="' . esc_url( wp_get_attachment_image_url( $listing_prv_img, array( 90, 90 ) ) ) . '" alt="' . esc_attr( get_the_title() ) . '">';
                        } elseif ( ! empty( $listing_img[0] ) && empty( $listing_prv_img ) ) {
                            echo '<img src="' . esc_url( wp_get_attachment_image_url( $listing_img[0], array( 90, 90 ) ) ) . '" alt="' . esc_attr( get_the_title() ) . '">';
                        } else {
                            echo '<img src="' . esc_url( $default_image ) . '" alt="' . esc_attr( get_the_title() ) . '">';
                        }
                        if ( empty( $disable_single_listing ) ) {
                            echo '</a>';
                        }
                        ?>
                    </div>

                    <div class="directorist-widget-listing__content">
                        <h4 class="directorist-widget-listing__title">
                            <?php
                            if ( empty( $disable_single_listing ) ) {
                                ?>
                                <a href="<?php echo esc_url( get_the_permalink() ); ?>"><?php echo esc_html( get_the_title() ); ?></a>
                                <?php
                            } else {
                                echo esc_html( get_the_title() );
                            } ?>
                        </h4>

                        <div class="directorist-widget-listing__meta">
                            <span class="directorist-widget-listing__rating">
                                <?php Markup::show_rating_stars( $review_rating );?>
                            </span>
                            <span class="directorist-widget-listing__rating-point"><?php echo esc_html( $review_rating ); ?></span>
                            <span class="directorist-widget-listing__reviews">(<?php echo wp_kses_post( $review_text ); ?>)</span>
                        </div>
                        <div class="directorist-widget-listing__price">
                            <?php if ( ! empty( $price ) && ( 'price' === $listing_pricing ) ) { ?>

                                <span><?php atbdp_display_price( $price ); ?></span>

                            <?php
                                } else {

                                    $output = atbdp_display_price_range( $price_range );
                                    echo wp_kses_post( $output );

                                }
                            ?>
                        </div>
                    </div>

                </div>

            <?php endwhile; ?>

		<?php wp_reset_postdata(); ?>
    </div>
</div>