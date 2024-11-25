<?php
/**
 * @author  wpWax
 * @since   7.3.0
 * @version 8.0.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

use Directorist\Review\Markup;

if ( empty( $related_listings->posts ) ) {
  return;
}

$default_icon = 'las la-tags';
?>

<div class="directorist-card__body">
  <div class="directorist-widget-listing">
    <?php
    foreach ($related_listings->posts as $related_listing) {

      // get only one parent or high level term object
      $top_category = ATBDP()->taxonomy->get_one_high_level_term($related_listing->ID, ATBDP_CATEGORY);
      $listing_img = directorist_get_listing_gallery_images( $related_listing->ID );
      $listing_prv_img = directorist_get_listing_preview_image( $related_listing->ID );
      $review_rating = directorist_get_listing_rating( get_the_ID() );
      $review_count  = directorist_get_listing_review_count( get_the_ID() );
      $review_text   = sprintf( _n( '%s review', $review_count > 0 ?  '%s reviews' : '%s review', $review_count, 'directorist' ), number_format_i18n( $review_count ) );
      $price = get_post_meta($related_listing->ID, '_price', true);
      $price_range = get_post_meta($related_listing->ID, '_price_range', true);
      $listing_pricing = get_post_meta($related_listing->ID, '_atbd_listing_pricing', true);
      $cats = get_the_terms($related_listing->ID, ATBDP_CATEGORY);
    ?>
      <div class="directorist-widget-listing__single">
        <div class="directorist-widget-listing__image">
          <?php
          $disable_single_listing = get_directorist_option('disable_single_listing');
          if (empty($disable_single_listing)) {
          ?>
            <a href="<?php echo esc_url(get_post_permalink($related_listing->ID)); ?>">
            <?php
          }
          $default_image = get_directorist_option('default_preview_image', DIRECTORIST_ASSETS . 'images/grid.jpg');
          if (!empty($listing_prv_img)) {
            echo '<img src="' . esc_url(wp_get_attachment_image_url($listing_prv_img, array(90, 90))) . '" alt="listing image">';
          } elseif (!empty($listing_img[0]) && empty($listing_prv_img)) {
            echo '<img src="' . esc_url(wp_get_attachment_image_url($listing_img[0], array(90, 90))) . '" alt="listing image">';
          } else {
            echo '<img src="' . esc_url( $default_image ) . '" alt="listing image">';
          }
          if (empty($disable_single_listing)) {
            echo '</a>';
          }
            ?>
        </div>
        <div class="directorist-widget-listing__content">
            <h4 class="directorist-widget-listing__title">
              <?php
              if (empty($disable_single_listing)) {
              ?>
                <a href="<?php echo esc_url(get_post_permalink($related_listing->ID)); ?>"><?php echo esc_html($related_listing->post_title); ?></a>
              <?php
              } else {
                echo esc_html($related_listing->post_title);
              } ?>

            </h4>
            <div class="directorist-widget-listing__meta">
              <span class="directorist-widget-listing__rating">
                  <?php Markup::show_rating_stars( $review_rating );?>
              </span>
              <span class="directorist-widget-listing__rating-point"><?php echo esc_html( $review_rating ); ?></span>
              <span class="directorist-widget-listing__reviews">(<?php echo $review_text ?>)</span>
            </div>
            <div class="directorist-widget-listing__price">
              <?php if (!empty($price) && ('price' === $listing_pricing)) { ?>
                <span><?php atbdp_display_price($price); ?></span>

              <?php } else {
                $output = atbdp_display_price_range($price_range);
                echo wp_kses_post( $output );
              } ?>
            </div>
        </div>
      </div>
    <?php } ?>
  </div>
</div>
<!--ends .categorized_listings-->