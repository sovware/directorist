<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<li class="directorist-listing-card-address"><?php directorist_icon( $icon ); ?>
    <?php if ( ! empty( $label ) ) : ?>
        <?php $listings->print_label( $label ); ?>
    <?php endif; ?>
    <?php echo esc_html( $value ); ?>
</li>