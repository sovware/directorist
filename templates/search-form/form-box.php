<?php
/**
 * @author  wpWax
 * @since   7.2.2
 * @version 7.2.2
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$search_form = directorist()->search_form;
?>

<div class="directorist-search-form-box">

	<div class="directorist-search-form-top directorist-flex directorist-align-center directorist-search-form-inline">

		<?php
		foreach ( $search_form->form_data[0]['fields'] as $field ) {
			$search_form->field_template( $field );
		}
		if ( $search_form->filter_open_method() !== 'always_open' ) {
			$search_form->more_buttons_template();
		}
		?>

	</div>

	<?php
	if ( $search_form->filter_open_method() == 'always_open' ) {
		$search_form->advanced_search_form_fields_template();
	} else {
		if ( $search_form->dispaly_more_filters_button() ) {
			?>
			<div class="<?php Helper::search_filter_class( $search_form->filter_open_method() ); ?>">
				<?php $search_form->advanced_search_form_fields_template(); ?>
			</div>
			<?php
		}
	}
	?>

</div>
