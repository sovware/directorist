<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */

if ( empty( $data['label'] ) ) {
	return;
}
?>
<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $data['label'] ); ?>:<?php echo !empty( $data['required'] ) ? '<span class="atbdp_make_str_red"> *</span>' : ''; ?></label>