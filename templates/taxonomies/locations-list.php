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
	<?php
	/**
	 * @since 5.6.6
	 */
	do_action( 'atbdp_before_all_locations_loop', $taxonomy );
	?>
	<div class="<?php Helper::directorist_container_fluid(); ?>">
	<div class="atbdp atbdp-categories atbdp-text-list">
		<div class="<?php Helper::directorist_row(); ?> atbdp-no-margin">
			<?php
			if( $locations ) {
				foreach ($locations as $location) {
					$plus_icon = $location['has_child'] ? '<span class="expander">+</span>' : '';
					?>
					<div class="<?php Helper::directorist_column( $columns ); ?>">
						<div class="atbd_category_wrapper">
							<a href="<?php echo esc_url($location['permalink']);?>"><span><?php echo esc_html($location['name']);?></span><?php echo $location['list_count_html'];?></a><?php echo $plus_icon;?>
							<?php echo $location['subterm_html'];?>
						</div>
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