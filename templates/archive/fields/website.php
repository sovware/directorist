<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<li class="directorist-listing-card-website">
    <?php directorist_icon( $icon ); ?>
    <?php $listings->print_label( $label ); ?>
    <a target="_blank" rel="noopener" href="<?php echo esc_url( $value ); ?>">
        <?php echo esc_html( $value ); ?>
    </a>
</li>