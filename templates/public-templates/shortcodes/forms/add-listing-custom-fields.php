<?php
/**
 * @author  AazzTech
 * @since   7.0
 * @version 7.0
 */
?>
<div class="form-group" id="atbdp_custom_field_area">
	<label>
		<?php echo esc_html( $title ); ?>

		<?php if ($cf_required): ?>
			<span style="color:red;"> *</span>
		<?php endif; ?>

		<?php if (!empty($instructions)): ?>
			<span class="atbd_tooltip atbd_tooltip--fw" aria-label="<?php echo esc_attr($instructions);?>"> <i class="fa fa-question-circle"></i></span>
		<?php endif; ?>
	</label>
	<?php echo $input_field;?>
</div>