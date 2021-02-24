<?php
/**
 * @author  AazzTech
 * @since   6.6
 * @version 6.7
 */
?>

<?php 
 if(  is_numeric( $searchform->listing_type ) ) {
	$term = get_term_by( 'id', $searchform->listing_type, ATBDP_TYPE );
	$listing_type = $term->slug;
}
?>

<div class="ads-advanced">
	<form action="<?php atbdp_search_result_page_link(); ?>" class="atbd_ads-form">
		<input type="hidden" name='directory_type' value='<?php echo ! empty( $listing_type ) ? $listing_type : $searchform->listing_type; ?>'>
		<div class="atbd_seach_fields_wrapper">
			<div class="atbdp-search-form atbdp-basic-search-fields">
				<?php foreach ( $searchform->form_data[0]['fields'] as $field ){ ?>
					<div class="atbdp-basic-search-fields-each"><?php $searchform->field_template( $field ); ?></div>
				<?php } ?>
			</div>
		</div>

		<div class="atbdp-adv-search-fields">
			<?php foreach ( $searchform->form_data[1]['fields'] as $field ){ ?>
				<div class="form-group atbdp-search-field-<?php echo esc_attr( $field['widget_name'] )?>"><?php $searchform->field_template( $field ); ?></div>
			<?php } ?>
		</div>

		<?php $searchform->buttons_template(); ?>
	</form>
</div>