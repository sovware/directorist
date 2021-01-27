<?php
/**
 * The template for displaying all listings page
 *
 * This template can be overridden by copying it to yourtheme/directorist/archive.php
 *
 * @author  wpWax
 * @since   6.6
 * @version 6.6
 */

use Directorist\URI_Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

get_header( 'directorist' );
echo "string";
if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		URI_Helper::get_template( 'archive/contents' );
	}
}

get_footer( 'directorist' );