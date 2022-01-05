<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$listings = directorist()->listings;
?>

<div class="directorist-view-count"><span class="<?php atbdp_icon_type(true) ?>-eye"></span><?php echo esc_html( $listings->loop_post_view_count() );?></div>