<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */

$terms_link    = ATBDP_Permalink::get_terms_and_conditions_page_url();
$terms_checked = (bool) get_post_meta( get_query_var( 'atbdp_listing_id', 0 ), '_t_c_check', true );
?>

<div class="form-group" id="directorist-terms_conditions-field">

	<input id="listing_t" type="checkbox" name="t_c_check"<?php checked( $terms_checked ); ?>>
	<label for="listing_t"><?php echo esc_html( $label ); ?>
		<a style="color: red" target="_blank" href="<?php echo esc_url( $terms_link ); ?>"><?php echo esc_html( $linking_text ); ?></a> 
	</label>

</div>
