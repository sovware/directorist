<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.1.1
 */

use wpWax\Directorist\Settings;

if ( ! defined( 'ABSPATH' ) ) exit;

$listings = directorist()->listings;
?>

<div class="marker" data-latitude="<?php echo esc_attr( $listings->loop_map_latitude() ); ?>" data-longitude="<?php echo esc_attr( $listings->loop_map_longitude() ); ?>" data-icon="<?php echo esc_attr( $listings->loop_map_cat_icon() ); ?>">

	<div class="map-info-wrapper" style="display:none;">

		<?php if ( Settings::display_map_image() ): ?>

			<div class="map-info-img">

				<?php echo !$listings->disable_single_listing() ? $listings->loop_wrap_permalink( $listings->loop_get_the_thumbnail() ) : $listings->loop_get_the_thumbnail(); ?>

			</div>

		<?php endif; ?>

		<div class="map-info-details">

			<?php if ( Settings::display_map_title() ): ?>

				<div class="atbdp-listings-title-block">
					<h3 class="atbdp-no-margin">
						
						<?php echo !$listings->disable_single_listing() ? $listings->loop_wrap_permalink( get_the_title() ) : get_the_title(); ?>

					</h3>
				</div>

			<?php endif; ?>

			<?php if ( $listings->loop_map_address() ): ?>

				<?php if ( Settings::display_map_address() ): ?>

					<div class="map_addr">

						<span class="<?php atbdp_icon_type( true );?>-map-marker"></span>

						<span><?php echo esc_html( $listings->loop_map_address() ); ?></span>
						
					</div>

				<?php endif; ?>

				<?php if ( Settings::display_map_direction() ): ?>

					<div class="map_get_dir">

						<a href="<?php echo esc_url( $listings->loop_map_direction_url() )?>" target="_blank"><?php esc_html_e( 'Get Direction', 'directorist' );?></a>

						<span class="<?php atbdp_icon_type( true );?>-arrow-right"></span>

					</div>

				<?php endif; ?>

			<?php endif; ?>

		</div>

		<span class="iw-close-btn"><i class="<?php atbdp_icon_type( true );?>-times"></i></span>

	</div>

</div>