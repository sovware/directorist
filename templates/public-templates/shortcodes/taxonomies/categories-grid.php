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
	do_action( 'atbdp_before_all_categories_loop' );
	?>
	<div class="<?php echo esc_attr( $grid_container ); ?>">
		<div class="col-md-12">
			<div class="atbd_all_categories atbdp-no-margin">
				<div class="row">
					<?php
					foreach ($categories as $category) {
						$cat_class = $category['img'] ? '' : ' atbd_category-default';
						?>
						<div class="<?php echo esc_attr( $grid_col_class ); ?>">
							<a class="atbdp-no-image atbd_category_single<?php echo esc_attr( $cat_class ); ?>" href="<?php echo esc_url($category['permalink']); ?>">
								<figure>
									<?php if ($category['img']) { ?>
										<img src="<?php echo esc_url( $category['img'] ); ?>" title="<?php echo esc_attr($category['name']); ?>" alt="<?php echo esc_attr($category['name']); ?>">
										<?php
									}
									?>
									<figcaption class="overlay-bg">
										<div class="cat-box">
											<div>
												<?php if ($category['has_icon']) { ?>
													<div class="icon"><span class="<?php echo esc_attr($category['icon_class']);?>"></span></div>
													<?php
												}
												?>
												<div class="cat-info">
													<h4 class="cat-name"><?php echo esc_html($category['name']); ?></h4>
													<span class="cat-count">
														<?php echo $category['grid_count_html'];?> <span>listings</span>
													</span>
												</div>
											</div>
										</div>
									</figcaption>
								</figure>
							</a>
						</div>
						<?php
					}
					?>
				</div>
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