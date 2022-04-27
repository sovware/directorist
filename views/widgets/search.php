<?php

$search_form_fields = Directorist\Helper::get_directory_type_term_data( get_the_ID(), 'search_form_fields' );
$directoriy_type = get_post_meta( get_the_ID(), '_directory_type', true );
$searchform = new Directorist\Directorist_Listing_Search_Form( 'listing', $directoriy_type );


if(  is_numeric( $searchform->listing_type ) ) {
	$term = get_term_by( 'id', $searchform->listing_type, ATBDP_TYPE );
	$listing_type = $term->slug;
} 

echo $args['before_widget'];
echo '<div class="atbd_widget_title">';
echo $args['before_title'] . esc_html(apply_filters('widget_title', $title)) . $args['after_title'];
echo '</div>';
?>
<div class="atbdp search-area default-ad-search <?php echo is_singular( ATBDP_POST_TYPE ) ? esc_html( 'directorist_single' ) : ''; ?>">
    <form action="<?php atbdp_search_result_page_link(); ?>" class="directorist-advanced-filter__form">
		<input type="hidden" name='directory_type' value='<?php echo ! empty( $listing_type ) ? $listing_type : $searchform->listing_type; ?>'>
		<div class="directorist-advanced-filter__basic">
			<?php foreach ( $searchform->form_data[0]['fields'] as $field ): ?>
				<div class="directorist-advanced-filter__basic--element"><?php $searchform->field_template( $field ); ?></div>
			<?php endforeach; ?>
		</div>

		<div class="directorist-advanced-filter__advanced">
			<?php foreach ( $searchform->form_data[1]['fields'] as $field ): ?>
				<div class="directorist-form-group directorist-advanced-filter__advanced--element directorist-advanced-filter__advanced--<?php echo esc_attr( $field['widget_name'] )?>"><?php $searchform->field_template( $field ); ?></div>
			<?php endforeach; ?>
		</div>
		<?php $searchform->buttons_template(); ?>
	</form>
</div><!-- ends: .default-ad-search -->

<?php echo $args['after_widget']; ?>