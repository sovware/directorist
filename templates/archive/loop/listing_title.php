<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<h4 class="directorist-listing-title"><?php echo wp_kses_post( $listings->loop_get_title() );?></h4>