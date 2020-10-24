<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */

$privacy_link    = ATBDP_Permalink::get_privacy_policy_page_url();
$privacy_checked = (bool) get_post_meta( get_query_var( 'atbdp_listing_id', 0 ), '_privacy_policy', true );
?>

<div class="form-group" id="directorist-privacy_policy-field atbd_privacy_policy_area">
	<input id="privacy_policy" type="checkbox" name="privacy_policy"<?php checked( $privacy_checked ); ?>>
	<label for="privacy_policy"><?php echo esc_html( $label ); ?> 
		<a style="color: red" target="_blank" href="<?php echo esc_url( $privacy_link ); ?>"><?php echo esc_html( $linking_text ); ?></a>
	</label>
</div>
