<?php
/**
 * @author  wpWax
 * @since   7.7
 * @version 7.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>
<div class="directorist-listing-details__text">
    <?php echo wp_kses_post( $listing->get_contents() ); ?>
</div>