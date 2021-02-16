<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-form-social-fields" id="socialID-<?php echo esc_attr( $index ); ?>">

	<div class="directorist-form-group">

		<select name="social[<?php echo esc_attr( $index ); ?>][id]" class="directorist-form-element">

			<?php foreach ( Helper::socials() as $social_id => $social_name ): ?>

				<option value="<?php echo esc_attr( $social_id ); ?>" <?php selected( $social_id, $social_info['id'] ); ?>><?php echo esc_html( $social_name ); ?></option>

			<?php endforeach; ?>

		</select>

	</div>

	<div class="directorist-form-group">
		<input type="url" name="social[<?php echo esc_attr( $index ); ?>][url]" class="directorist-form-element directory_field atbdp_social_input" value="<?php echo esc_url( $social_info['url'] ); ?>" placeholder="<?php esc_attr_e( 'eg. http://example.com', 'directorist' ); ?>">
	</div>

	<div class="directorist-form-group">
		<span data-id="<?php echo esc_attr( $index ); ?>" class="directorist-form-social-fields__remove dashicons dashicons-trash" title="<?php esc_attr_e( 'Remove this item', 'directorist' ); ?>"></span>
	</div>

	<div class="directorist-form-group">
		<span class="adl-move-icon dashicons dashicons-move"></span>
	</div>

</div>