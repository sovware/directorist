<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-form-group directorist-form-social-info-field">

	<?php $listing_form->field_label_template( $data );?>

	<div id="social_info_sortable_container">

		<input type="hidden" id="is_social_checked">

		<?php
		if ( !empty( $data['value'] ) ){
			foreach ( $data['value'] as $index => $social_info ) {
				$listing_form->social_item_template( $index, $social_info );
			}
		}
		?>

	</div>

	<button type="button" class="directorist-btn directorist-btn-primary directorist-btn-sm" id="addNewSocial"> <span class="plus-sign">+</span><?php esc_html_e('Add New', 'directorist'); ?></button>
	
	<div class="directorist-modal directorist-modal-js directorist-fade directorist-modal-alert"></div>

</div>