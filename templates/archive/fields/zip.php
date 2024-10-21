<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 8.0
 */
?>

<li class="directorist-listing-card-zip">
    <?php directorist_icon( $icon ); ?>
    <?php $listings->print_label( $label ); ?>
    <?php echo esc_html( $value ); ?>
</li>