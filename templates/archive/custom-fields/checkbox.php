<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$tag = tag_escape( $before ? $before : 'div' );
$closing_tag = tag_escape( $after ? $after : 'div' );

?>
<<?php echo $tag; ?> class="directorist-listing-card-checkbox">
    <?php directorist_icon( $icon ); ?>
    <?php $listings->print_label( $label ); ?>
    <?php echo esc_html( $value ); ?>
</<?php echo $closing_tag; ?>>
