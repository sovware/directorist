<?php
/**
 * @author  wpWax
 * @since   8.4.4
 * @version 8.4.4Add commentMore actions
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( empty( $value ) ) {
    return;
}

?>

<div class="directorist-single-info directorist-single-formgent-form">

    <?php echo do_shortcode( "[formgent id='{$value}']" ); ?>

</div>