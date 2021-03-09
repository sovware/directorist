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

	<div class="directorist-modal directorist-modal-js directorist-fade directorist-delete-social-modal directorist-delete-social-js directorist-w-100">

		<div class="directorist-modal__dialog">

			<div class="directorist-modal__content">

				<div class="directorist-modal-body">

					<div class="directorist-delete-modal-inner">

						<div class="directorist-delete-modal-icon">

							<i class="la la-exclamation"></i>

						</div>

						<div class="directorist-delete-modal-text">

							<h3>Are You Sure</h3>
							
							<p>Do you really want to remove this Social Link!</p>

						</div>

					</div>
					
				</div>

				<div class="directorist-modal__footer directorist-text-center">

					<button class="directorist-btn directorist-btn-danger directorist-modal-close directorist-modal-close-js">Cancel</button>

					<button class="directorist-btn directorist-btn-info directorist-delete-social-yes directorist-modal-close-js">Yes, Delete It!</button>

				</div>

			</div>

		</div>

	</div>

</div>