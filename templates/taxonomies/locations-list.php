<?php
/**
 * @author  AazzTech
 * @since   6.6
 * @version 6.6
 */
?>
<div id="directorist" class="atbd_wrapper">
	<?php 
	/**
	 * @since 5.6.6
	 */
	do_action( 'atbdp_before_all_locations_loop' );
	?>
	<div class="atbdp atbdp-categories atbdp-text-list">
		<div class="row atbdp-no-margin">
			<?php
			foreach ($locations as $location) {
				$plus_icon = $location['has_child'] ? '<span class="expander">+</span>' : '';
				?>
				<div class="<?php echo esc_attr($list_col_class);?>">
					<div class="atbd_category_wrapper">
						<a href="<?php echo esc_url($location['permalink']);?>"><span><?php echo esc_html($location['name']);?></span><?php echo $location['list_count_html'];?></a><?php echo $plus_icon;?>
						<?php echo $location['subterm_html'];?>
					</div>
				</div>
				<?php
			}
			?>
		</div>
	</div>
	<?php
	/**
     * @since 5.6.6
     */
    do_action( 'atbdp_after_all_locations_loop' );
    ?>
</div>