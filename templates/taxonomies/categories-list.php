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

$taxonomy->atts['type'] = 'category';
$taxonomy->atts['directory_type'] = isset( $_GET['directory_type'] ) && ! empty( $_GET['directory_type'] ) ? $_GET['directory_type'] : '';
?>
<div id="directorist" class="atbd_wrapper directorist-w-100">
	<div class="<?php Helper::directorist_container_fluid(); ?>">
		<div class="directorist-categories" data-attrs="<?php echo esc_attr(wp_json_encode( $taxonomy->atts )); ?>">
			<?php
				/**
				 * @since 5.6.6
				 */
				do_action( 'atbdp_before_all_categories_loop', $taxonomy );
			?>
			<?php if ( $categories ) : ?>
					<div class="<?php echo apply_filters( 'directorist_taxonomy_category_wrapper', Helper::directorist_row() . ' taxonomy-category-wrapper' ); ?>">
						<?php
						foreach ($categories as $category) {
							$toggle_class = $category['has_child'] ? 'directorist-taxonomy-list__toggle' : '';
							$toggle_icon = $category['has_child'] ? 'las la-angle-down' : '';
							$has_icon = $category['icon_class'] ? 'directorist-taxonomy-list__card--icon' : '';
							?>
							<div class="<?php Helper::directorist_column( $columns ); ?> directorist-taxonomy-list-one">
								<div class="directorist-taxonomy-list">
									<a href="<?php echo esc_url($category['permalink']);?>" class="directorist-taxonomy-list__card <?php echo wp_kses_post( $toggle_class ); ?> <?php echo wp_kses_post( $has_icon ); ?> ">
										<?php if($category['icon_class']){ ?>
											<span class="directorist-taxonomy-list__icon">
												<?php directorist_icon( $category['icon_class'] ); ?>
											</span>
										<?php } ?>
										<span class="directorist-taxonomy-list__name">
											<?php echo esc_html($category['name']);?>
										</span>
										<span class="directorist-taxonomy-list__count">
											<?php echo wp_kses_post( $category['list_count_html'] );?>
										</span>
										<?php if($category['has_child']){ ?>
											<span class="directorist-taxonomy-list__toggler">
												<?php directorist_icon( $toggle_icon ); ?>
											</span>
										<?php } ?>
									</a>
									<?php echo wp_kses_post( $category['subterm_html'] );?>
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
    do_action( 'atbdp_after_all_categories_loop' );
    ?>
</div>