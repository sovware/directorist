<?php
/**
 * @author  wpWax
 * @since   8.0
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( $display_title ): ?>

    <h2 class="directorist-listing-details__listing-title"><?php echo esc_html( $listing->get_title() ); ?></h2>

<?php endif;

if ( $display_tagline && $listing->get_tagline() ): ?>

    <p class="directorist-listing-details__tagline"><?php echo esc_html( $listing->get_tagline() ); ?></p>
    
<?php endif; ?>

<?php do_action( 'directorist_single_listing_after_title', $listing->id ); ?>