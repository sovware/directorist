<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 8.0.13
 */

use \Directorist\Helper;

$columns = floor( 12 / $taxonomy->columns );

if ( '5' == $taxonomy->columns ) {
	$columns = $columns . '-5';
}

$taxonomy->atts['type'] = 'location';
$taxonomy->atts['directory_type'] = isset( $_GET['directory_type'] ) && ! empty( $_GET['directory_type'] ) ? $_GET['directory_type'] : '';
?>
<div id="directorist" class="atbd_wrapper directorist-w-100">
	<div class="<?php Helper::directorist_container_fluid(); ?>">
		<div class="directorist-location directorist-location--grid directorist-location--grid-one" data-attrs="<?php echo esc_attr(wp_json_encode( $taxonomy->atts )); ?>">
			<?php
				/**
				 * @since 5.6.6
				 */
				do_action( 'atbdp_before_all_locations_loop', $taxonomy );
			?>
			<?php if ( $locations ) : ?>
					<div class="<?php echo apply_filters( 'directorist_taxonomy_location_wrapper', Helper::directorist_row() . ' taxonomy-location-wrapper' ); ?>">
						<?php
						foreach ($locations as $location) {
							$loc_class = $location['img'] ? ' directorist-location__single--img' : '';
							?>
							<div class="<?php Helper::directorist_column( $columns ); ?>">

								<div class="directorist-location__single<?php echo esc_attr( $loc_class ); ?>">
									<?php if ($location['img']) { ?>
										<div class="directorist-location__single__img"><img src="<?php echo esc_url( $location['img'] ); ?>" title="<?php echo esc_attr($location['name']); ?>" alt="<?php echo esc_attr($location['name']); ?>"></div>
										<?php
									}
									?>
									<div class="directorist-location__content">
										<h3>
											<a href="<?php echo esc_url($location['permalink']); ?>">
												<?php echo esc_html($location['name']); ?>
											</a>
										</h3>
										<span class="directorist-location__count"><?php echo wp_kses_post( $location['grid_count_html'] );?></span>
									</div>
								</div>
							</div>
							<?php
						}?>

						<?php $taxonomy->pagination(); ?>
						
					</div>
				<?php else : ?>
					<p><?php esc_html_e( 'No Results found!', 'directorist' ); ?></p>
				<?php endif; ?>
		</div>
	</div>
	<?php
	/**
     * @since 5.6.6
     */
    do_action( 'atbdp_after_all_locations_loop' );
    ?>
</div>