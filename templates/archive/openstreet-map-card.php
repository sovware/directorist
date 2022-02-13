<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.6
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$listings = directorist()->listings;
?>

<div class="atbdp-body atbdp-map embed-responsive embed-responsive-16by9 atbdp-margin-bottom">

	<?php if ( $listings->display_map_image() ): ?>
		
		<div class="media-left">

			<?php echo !$listings->disable_single_listing() ? $listings->loop_wrap_permalink( $listings->loop_get_the_thumbnail() ) : $listings->loop_get_the_thumbnail(); ?>
			
		</div>

	<?php endif; ?>

	<div class="media-body">

		<?php if ( $listings->display_map_title() ): ?>

			<div class="atbdp-listings-title-block">
				<h3 class="atbdp-no-margin">

					<?php echo !$listings->disable_single_listing() ? $listings->loop_wrap_permalink( get_the_title() ) : get_the_title(); ?>

				</h3>
			</div>

		<?php endif; ?>

		<?php if ( $listings->loop_map_address() ): ?>

			<?php if ( $listings->display_map_address() ): ?>

				<div class="osm-iw-location">

					<span class="<?php atbdp_icon_type( true ); ?>-map-marker"></span>

					<a href="./" class="map-info-link"><?php echo esc_html( $listings->loop_map_address() ); ?></a>

				</div>
				
			<?php endif; ?>

			<?php if ( $listings->display_map_direction() ): ?>

				<div class="osm-iw-get-location">

					<a href="<?php echo esc_url( $listings->loop_map_direction_url() )?>" target="_blank"><?php esc_html_e( 'Get Direction', 'directorist' );?></a>

					<span class="<?php atbdp_icon_type( true ); ?>-arrow-right"></span>

				</div>

			<?php endif; ?>

		<?php endif; ?>

	</div>
</div>