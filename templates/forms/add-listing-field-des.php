<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */

if ( empty( $data['description'] ) ) {
	return;
}
?>
<p><?php echo esc_html( $data['description'] ); ?></p>