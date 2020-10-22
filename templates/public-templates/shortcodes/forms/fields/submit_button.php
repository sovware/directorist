<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */
?>

<div class="form-group" id="directorist-submit_button-field">
	<div class="btn_wrap list_submit">
		<button type="submit" class="btn btn-primary btn-lg listing_submit_btn">
			<?php echo ! empty( get_query_var( 'atbdp_listing_id', 0 ) ) ? esc_html__( 'Preview Changes', 'directorist' ) : $label; ?>
		</button>
	</div>
</div>
