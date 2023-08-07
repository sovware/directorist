<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.3.1 //TODO: update version
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-listing-card-location">
    <?php directorist_icon( $icon );?><span class="directorist-listing-single__info--list__label"><?php $listings->print_label( $label ); ?></span>
    <div class="directorist-listing-card-location-list">
        <?php directorist_the_locations(); ?>
    </div>
</div>