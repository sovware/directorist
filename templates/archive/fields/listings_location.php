<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-listing-card-location">
    <?php directorist_icon( $icon );?><?php $listings->print_label( $label ); ?>
    <div class="directorist-listing-card-location-list">
        <?php echo $listings->get_the_location(); ?>
    </div>
</div>