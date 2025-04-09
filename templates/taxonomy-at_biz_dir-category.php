<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Directorist\ATBDP_Shortcode;
?>

<?php get_header(); ?>

<?php echo (new ATBDP_Shortcode())->category_archive(); ?>

<?php get_footer( 'directorist' ); ?>
