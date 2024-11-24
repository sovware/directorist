<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.7.0
 */

use \Directorist\Helper;

$columns = floor( 12 / $taxonomy->columns );

if ( '5' == $taxonomy->columns ) {
	$columns = $columns . '-5';
}
?>
<div id="directorist" class="atbd_wrapper directorist-w-100">
	<div class="<?php Helper::directorist_container_fluid(); ?>">
		<div class="directorist-location directorist-location--grid directorist-location--grid-one">
			<div class="<?php Helper::directorist_row(); ?>">
				<div class="directorist-col-12">
					<?php
						/**
						 * @since 5.6.6
						 */
						do_action( 'atbdp_before_all_locations_loop', $taxonomy );
					?>
				</div>
				<?php
				if( $locations ) {
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
					}
				}
				else {
					?>
					<p><?php esc_html_e( 'No Results found!', 'directorist' ); ?></p>
					<?php
				}
				?>
				
				<?php $taxonomy->pagination(); ?>
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