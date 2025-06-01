<?php
/**
 * @author  wpWax
 * @since 8.5
 * @version 1.0
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use \Directorist\ATBDP_Shortcode;
?>

<?php get_header(); ?>

<?php echo ( new ATBDP_Shortcode() )->location_archive(); ?>

<?php get_footer( 'directorist' ); ?>
