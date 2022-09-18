<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.4.0
 */
use \Directorist\Helper;

$columns = floor( 12 / $taxonomy->columns );
?>
<div id="directorist" class="atbd_wrapper directorist-w-100">
	<?php
	/**
	 * @since 5.6.6
	 */
	do_action( 'atbdp_before_all_categories_loop', $taxonomy );
	?>
	<div class="<?php Helper::directorist_container_fluid(); ?>">
		<div class="atbd_all_categories atbdp-no-margin">
			<div class="<?php Helper::directorist_row(); ?>">
				<?php
				if( $categories ) {
					foreach ($categories as $category) {
						$cat_class = !$category['img'] ? ' atbd_category_no_image' : '';
						?>
						<div class="<?php Helper::directorist_column( $columns ); ?>">
							<a class="atbd_category_single<?php echo esc_attr( $cat_class ); ?>" href="<?php echo esc_url($category['permalink']); ?>">
								<figure>
									<?php if ( $category['img'] ) { ?>
										<img src="<?php echo esc_url( $category['img'] ); ?>" title="<?php echo esc_attr($category['name']); ?>" alt="<?php echo esc_attr($category['name']); ?>">
										<?php
									}
									?>
									<figcaption class="overlay-bg">
										<div class="cat-box">
											<div>
												<?php
												if ($category['has_icon']) { ?>
													<div class="icon"><?php directorist_icon( $category['icon_class'] ); ?></div>
													<?php
												}
												?>
												<div class="cat-info">
													<h4 class="cat-name"><?php echo esc_html($category['name']); ?></h4>
													<?php if( $taxonomy->show_count ){ ?>
													<span class="cat-count">
														<?php
														$listing_count_text = sprintf( _nx( 'listing', 'listings', $category['term']->count, 'number of listings', 'directorist' ), number_format_i18n( $category['term']->count ) );
														$output = sprintf( '%s <span>%s</span>', $category['grid_count_html'], $listing_count_text );
														echo wp_kses_post( $output );
														?></span>
													</span>
													<?php } ?>
												</div>
											</div>
										</div>
									</figcaption>
								</figure>
							</a>
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
	</div>

	<?php
    /**
     * @since 5.6.6
     */
    do_action( 'atbdp_after_all_categories_loop' );
    ?>
</div>