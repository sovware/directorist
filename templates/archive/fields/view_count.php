<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-view-count"><?php directorist_icon( 'las la-eye' ); ?><?php echo ( ! empty($listings->loop['post_view']) ) ? $listings->loop['post_view'] : 0;?></div>