<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( empty( $data['description'] ) ) {
	return;
}
?>

<div class=""><?php echo esc_html( $data['description'] ); ?></div>