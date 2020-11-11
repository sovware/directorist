<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */

$value = isset( $_GET['q'] ) ? $_GET['q'] : '';
?>
<div class="single_search_field search_query">
	<input class="form-control search_fields" type="text" name="q" value="<?php echo esc_attr( $value ); ?>" placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>" <?php echo ! empty( $data['required'] ) ? 'required="required"' : ''; ?>>
</div>