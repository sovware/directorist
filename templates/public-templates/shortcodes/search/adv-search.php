<?php
/**
 * @author  AazzTech
 * @since   6.6
 * @version 6.6
 */
?>
<div class="ads-advanced">
	<?php foreach ( $searchform->form_data[1]['fields'] as $field ){ ?>
		<div class="form-group atbdp-search-field-<?php echo esc_attr( $field['widget_name'] )?>"><?php $searchform->field_template( $field ); ?></div>
	<?php } ?>
</div>