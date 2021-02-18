<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.6
 */
use \Directorist\Helper;
?>
<div id="directorist" class="atbd_wrapper atbdp atbdp-categories atbdp-text-list directorist-w-100">
	<?php
    /**
     * @since 5.6.6
     */
    do_action( 'atbdp_before_all_categories_loop', $taxonomy );
    ?>
	<div class="<?php Helper::directorist_container_fluid(); ?>">
	<div class="<?php Helper::directorist_row(); ?> atbdp-no-margin">
		<?php
		if( $categories ) {
			foreach ($categories as $category) {
				$plus_icon = $category['has_child'] ? '<span class="expander">+</span>' : '';
				?>
				<div class="<?php Helper::directorist_column('md-3'); ?>">
					<div class="atbd_category_wrapper">
						<a href="<?php echo esc_url($category['permalink']);?>" class="atbd_parent_cat"><span><?php echo esc_html($category['name']);?></span><?php echo $category['list_count_html'];?></a><?php echo $plus_icon;?>
						<?php echo $category['subterm_html'];?>
					</div>
				</div>
				<?php
			}
		} else {
			_e('<p>No Results found!</p>', 'directorist');
		}
		?>
	</div>
	</div>
	<?php
	/**
     * @since 5.6.6
     */
    do_action( 'atbdp_after_all_categories_loop' );
    ?>
</div>