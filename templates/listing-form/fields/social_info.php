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

		<?php if ( !empty( $data['value'] ) ): ?>
			<?php foreach ( $data['value'] as $index => $social_info ): ?>

				<div class="directorist-form-social-fields" id="socialID-<?php echo esc_attr( $index ); ?>">

					<div class="directorist-form-group">

						<select name="<?php echo esc_attr( $data['field_key'] ); ?>[<?php echo esc_attr( $index ); ?>][id]" id="atbdp_social" class="directorist-form-element">

							<?php foreach ( Helper::socials() as $social_id => $social_name ): ?>

								<option value="<?php echo esc_attr( $social_id ); ?>" <?php selected( $social_id, $social_info['id'] ); ?>><?php echo esc_html( $social_name ); ?></option>

							<?php endforeach; ?>

						</select>

					</div>

					<div class="directorist-form-group">
						<input type="url" name="<?php echo esc_attr( $data['field_key'] ); ?>[<?php echo esc_attr( $index ); ?>][url]" class="directorist-form-element directory_field atbdp_social_input" value="<?php echo esc_url( $social_info['url'] ); ?>" placeholder="<?php esc_attr_e( 'eg. http://example.com', 'directorist' ); ?>">
					</div>

					<div class="directorist-form-group">
						<span data-id="<?php echo esc_attr( $index ); ?>" class="directorist-form-social-fields__remove dashicons dashicons-trash" title="<?php esc_attr_e( 'Remove this item', 'directorist' ); ?>"></span>
					</div>

					<div class="directorist-form-group">
						<span class="adl-move-icon dashicons dashicons-move"></span>
					</div>

				</div>

			<?php endforeach; ?>
		<?php endif; ?>

	</div>

	<button type="button" class="directorist-btn directorist-btn-primary directorist-btn-sm" id="addNewSocial"> <span class="plus-sign">+</span><?php esc_html_e('Add New', 'directorist'); ?></button>

</div>