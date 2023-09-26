<?php
/**
 * @author  wpWax
 * @since   8.0
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-listing-details">

    <?php $listing->quick_info_template(); ?>

    <?php do_action( 'directorist_single_listing_after_title', $listing->id ); ?>

</div>