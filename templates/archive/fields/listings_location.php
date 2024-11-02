<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<li class="directorist-listing-card-location">
    <?php directorist_icon( $icon );?>
    <?php $listings->print_label( $label ); ?>
    <div class="directorist-listing-card-location-list">
        <?php directorist_the_locations(); ?>
    </div>
</li>