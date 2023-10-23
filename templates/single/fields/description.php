<?php
/**
 * @author  wpWax
 * @since   7.7
 * @version 7.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>
<div class="directorist-single-info directorist-listing-details__text">
    <?php echo wp_kses_post( $listing->get_contents() ); ?>
</div>