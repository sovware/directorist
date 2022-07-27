<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.3.1
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-view-count"><span class="<?php atbdp_icon_type(true) ?>-eye"></span><?php echo ( ! empty($listings->loop['post_view']) ) ? esc_html( $listings->loop['post_view'] ) : 0;?></div>