<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.3.1
 */

use \Directorist\Helper;

$columns = floor( 12 / $taxonomy->columns );
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
				<div class="<?php Helper::directorist_column( $columns ); ?>">
					<div class="atbd_category_wrapper">
						<a href="<?php echo esc_url($category['permalink']);?>" class="atbd_parent_cat"><span><?php echo esc_html($category['name']);?></span><?php echo wp_kses_post( $category['list_count_html'] );?></a><?php echo wp_kses_post( $plus_icon );?>
						<?php echo wp_kses_post( $category['subterm_html'] );?>
					</div>
				</div>
				<?php
			}
		} else {
			?>
			<p><?php esc_html_e( 'No Results found!', 'directorist' ); ?></p>
			<?php
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