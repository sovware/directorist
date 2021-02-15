<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="form-group directorist-social-info-field">

	<?php $listing_form->field_label_template( $data );?>

	<div id="social_info_sortable_container">

		<input type="hidden" id="is_social_checked">

		<?php if ( !empty( $data['value'] ) ): ?>
			<?php foreach ( $data['value'] as $index => $social_info ): ?>

				<div class="row atbdp_social_field_wrapper" id="socialID-<?php echo esc_attr( $index ); ?>">

					<div class="col-md-3 col-sm-12">
						<div class="form-group">

							<select name="<?php echo esc_attr( $data['field_key'] ); ?>[<?php echo esc_attr( $index ); ?>][id]" id="atbdp_social" class="form-control">

								<?php foreach ( Helper::socials() as $social_id => $social_name ): ?>

									<option value="<?php echo esc_attr( $social_id ); ?>" <?php selected( $social_id, $social_info['id'] ); ?>><?php echo esc_html( $social_name ); ?></option>

								<?php endforeach; ?>

							</select>

						</div>
					</div>

					<div class="col-md-6 col-sm-12">

						<input type="url" name="<?php echo esc_attr( $data['field_key'] ); ?>[<?php echo esc_attr( $index ); ?>][url]" class="form-control directory_field atbdp_social_input" value="<?php echo esc_url( $social_info['url'] ); ?>" placeholder="<?php esc_attr_e( 'eg. http://example.com', 'directorist' ); ?>">

					</div>

					<div class="col-md-3 col-sm-12">

						<span data-id="<?php echo esc_attr( $index ); ?>" class="removeSocialField dashicons dashicons-trash" title="<?php esc_attr_e( 'Remove this item', 'directorist' ); ?>"></span>

						<span class="adl-move-icon dashicons dashicons-move"></span>

					</div>

				</div>

			<?php endforeach; ?>
		<?php endif; ?>

	</div>

	<button type="button" class="btn btn-primary btn-sm" id="addNewSocial"> <span class="plus-sign">+</span><?php esc_html_e('Add New', 'directorist'); ?></button>

</div>