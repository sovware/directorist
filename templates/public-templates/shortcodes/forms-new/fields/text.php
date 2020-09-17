<?php
$forms = Directorist_Listing_Forms::instance();
?>
<div class="form-group" id="atbdp_listing_title">
	<?php if (!empty($label)): ?>
		 <label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $label )?>:<?php echo !empty($required) ? $forms->add_listing_required_html() : '';?></label>
	<?php endif; ?>
    <input type="text" name="<?php echo esc_attr( $id ); ?>" id="<?php echo esc_attr( $id ); ?>" value="<?php echo esc_attr( $value ); ?>" class="form-control directory_field" placeholder="<?php echo esc_attr( $placeholder ); ?>">
</div>