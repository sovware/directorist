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
	<div class="atbdp atbdp-categories atbdp-text-list directorist-location">
		<div class="<?php Helper::directorist_row(); ?> atbdp-no-margin">
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
					$toggle_class = $location['has_child'] ? 'directorist-taxonomy-list__toggle' : '';
					$toggle_icon = $location['has_child'] ? 'las la-angle-down' : '';
					?>
					<div class="<?php Helper::directorist_column( $columns ); ?> directorist-taxonomy-list-one">
						<div class="directorist-taxonomy-list">
							<a class="directorist-taxonomy-list__card <?php echo wp_kses_post( $toggle_class ); ?> " href="<?php echo esc_url($location['permalink']);?>">
								<span class="directorist-taxonomy-list__name">
									<?php echo esc_html($location['name']);?>
								</span>
								<span class="directorist-taxonomy-list__count">
									<?php echo wp_kses_post( $location['list_count_html'] );?>
								</span>
								<?php if($location['has_child']){ ?>
									<span class="directorist-taxonomy-list__toggler">
										<?php directorist_icon( $toggle_icon ); ?>
									</span>
								<?php } ?>
							</a>
							<?php echo wp_kses_post( $location['subterm_html'] );?>
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