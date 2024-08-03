<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>
<button class="directorist-single-listing-action directorist-action-bookmark directorist-btn directorist-btn-sm directorist-btn-light atbdp-favourites <?php echo ! is_user_logged_in() ? 'atbdp-require-login' : ''; ?>" data-listing_id="<?php echo esc_attr( get_the_ID() ); ?>" aria-label="Add to Favorite Button">
    <?php echo wp_kses_post( the_atbdp_favourites_link( get_the_ID() ) ); ?>
</button>