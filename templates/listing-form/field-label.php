<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( empty( $data['label'] ) ) {
	return;
}
?>

<label for="<?php echo esc_attr( $label_id ); ?>"><?php echo esc_html( $data['label'] ); ?>:<?php echo !empty( $data['required'] ) ? '<span class="atbdp_make_str_red"> *</span>' : ''; ?></label>