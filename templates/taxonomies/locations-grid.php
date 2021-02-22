<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.6
 */

use \Directorist\Helper;

$columns = floor( 12 / $taxonomy->columns );
?>
<div id="directorist" class="atbd_wrapper directorist-w-100">
	<div class="<?php Helper::directorist_container_fluid(); ?>">
		<?php
		/**
		 * @since 5.6.6
		 */
		do_action( 'atbdp_before_all_locations_loop', $taxonomy );
		?>
		<div class="atbd_location_grid_wrap atbdp-no-margin">
			<div class="<?php Helper::directorist_row(); ?>">
				<?php
				if( $locations ) {
					foreach ($locations as $location) {
						$loc_class = $location['img'] ? '' : ' atbd_location_grid-default';
						?>
						<div class="<?php Helper::directorist_column( $columns ); ?>">

							<a class="atbd_location_grid<?php echo esc_attr( $loc_class ); ?>" href="<?php echo esc_url($location['permalink']); ?>">
								<figure>
									<?php if ($location['img']) { ?>
										<img src="<?php echo esc_url( $location['img'] ); ?>" title="<?php echo esc_attr($location['name']); ?>" alt="<?php echo esc_attr($location['name']); ?>">
										<?php
									}
									?>
									<figcaption>
										<h3><?php echo esc_html($location['name']); ?></h3>
										<?php echo $location['grid_count_html'];?>
									</figcaption>
								</figure>
							</a>

						</div>
						<?php
					}
				}
				else {
					_e('<p>No Results found!</p>', 'directorist');
				}
				?>
			</div>
		</div>
	</div>
	<?php
	/**
     * @since 5.6.6
     */
    do_action( 'atbdp_after_all_locations_loop' );
    ?>
</div>