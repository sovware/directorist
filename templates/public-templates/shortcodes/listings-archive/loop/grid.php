<div class="atbdp_column atbdp-col-<?php echo $listings->columns ?>">
	<div class="atbd_single_listing atbd_listing_card">
		<article class="atbd_single_listing_wrapper <?php echo esc_attr( $listings->loop_wrapper_class() ); ?>">
			<figure class="atbd_listing_thumbnail_area">
				<div class="atbd_listing_image">
					<?php
					if ( $listings->display_preview_image ) {

						if (!$listings->disable_single_listing) { ?>
							<a href="<?php echo $listings->loop['permalink']; ?>" <?php echo $listings->loop_link_attr(); ?>><?php atbdp_thumbnail_card(); ?></a>
							<?php
						}
						else {
							atbdp_thumbnail_card();
						}
					}

					if ($listings->display_author_image) { ?>
						<div class="atbd_author">
							<a href="<?php echo $listings->loop['author_link']; ?>" aria-label="<?php echo $listings->loop['author_full_name']; ?>" class="<?php echo $listings->loop['author_link_class']; ?>">
								<?php if ($listings->loop['u_pro_pic']) { ?>
									<img src="<?php echo esc_url($listings->loop['u_pro_pic'][0]); ?>" alt="<?php _e( 'Author Image', 'directorist' );?>">
									<?php
								}
								else {
									echo $listings->loop['avatar_img'];
								}
								?>
							</a>
						</div>
					<?php } ?>

				</div>

			    <?php
			    /**
			     * @since 5.0
			     * @hooked Directorist_Template_Hooks::business_hours_badge - 10
			     */
				?>
				<span class="atbd_upper_badge bh_only"><?php echo apply_filters('atbdp_upper_badges', '');?></span>

			    <?php
			    /**
			     * @since 5.0
			     * @hooked Directorist_Template_Hooks::featured_badge - 10
			     * @hooked Directorist_Template_Hooks::popular_badge - 15
			     * @hooked Directorist_Template_Hooks::new_listing_badge - 20
			     */
				?>
				<span class="atbd_lower_badge"><?php echo apply_filters('atbdp_grid_lower_badges', '');?></span>

				<?php
			    /**
			     * @since 7.0
			     * @hooked Directorist_Template_Hooks::mark_as_favourite_button - 10
			     */
				do_action('atbdp_listing_thumbnail_area');
				?>
			</figure>

			<div class="atbd_listing_info">

				<?php if ($listings->display_title || $listings->enable_tagline || $listings->display_review || $listings->display_price) { ?>
					<div class="atbd_content_upper">
						<?php if ($listings->display_title) { ?>
							<h4 class="atbd_listing_title">
								<?php
								if ( ! $listings->disable_single_listing ) {
									printf('<a href="%s"%s>%s</a>', $listings->loop['permalink'], $listings->loop_link_attr(), $listings->loop['title']);
								}
								else {
									echo $listings->loop['title'];
								}
								?>
							</h4>
							<?php
						}

						if (!empty($listings->loop['tagline']) && $listings->enable_tagline && $listings->display_tagline_field) { ?>
							<p class="atbd_listing_tagline"><?php echo esc_html(stripslashes($listings->loop['tagline'])); ?></p>
							<?php
						}

						/**
						 * Fires after the title and sub title of the listing is rendered
						 *
						 *
						 * @since 1.0.0
						 */

						do_action('atbdp_after_listing_tagline');

						if ($listings->display_review || $listings->display_price && (!empty($listings->loop['price']) || !empty($listings->loop['price_range']))) {
							$listings->loop_price_meta_html();
						}

					    if (!empty($listings->display_contact_info || $listings->display_publish_date || $listings->display_email || $listings->display_web_link)) {
					    	$listings->loop_data_list_html();
					    }

						if (!empty($listings->loop['excerpt']) && $listings->enable_excerpt && $listings->display_excerpt_field) { ?>
							<p class="atbd_excerpt_content">
								<?php echo esc_html(stripslashes(wp_trim_words($listings->loop['excerpt'], $listings->excerpt_limit)));

								/**
								* @since 5.0.9
								*/
								do_action('atbdp_listings_after_exerpt');

								if ($listings->display_readmore ) { 
									printf('<a href="%s"> %s</a>', $listings->loop['permalink'], $listings->readmore_text);
								}
								?>
							</p>
						<?php } ?>
					</div>
					<?php
				}

				if ( $listings->display_category || $listings->display_view_count ) {
					ob_start();
					?>
					<div class="atbd_listing_bottom_content">
						<?php if ( $listings->display_category && ! empty( $listings->loop['cats'] ) ) { $totalTerm = count($listings->loop['cats']); ?>
							<div class="atbd_content_left">
							    <div class="atbd_listing_category">
									<a href="<?php echo ATBDP_Permalink::atbdp_get_category_page($listings->loop['cats'][0]) ?>">
										<span class="<?php echo atbdp_icon_type() ?>-tags"></span><?php echo $listings->loop['cats'][0]->name; ?>
									</a>

									<?php
									if ($totalTerm > 1) {
										$totalTerm = $totalTerm - 1;
										?>
										<div class="atbd_cat_popup">
											<span>+<?php echo $totalTerm; ?></span>
											<div class="atbd_cat_popup_wrapper">
												<span>
													<?php
													foreach (array_slice($listings->loop['cats'], 1) as $cat) {
														$link = ATBDP_Permalink::atbdp_get_category_page($cat);
														$space = str_repeat(' ', 1);
														echo"{$space}<span><a href='{$link}'>{$cat->name}<span>,</span></a></span>"; 
													}
													?>
												</span>
											</div>
										</div>
									<?php } ?>
							    </div>
							</div>
					    	<?php
						}

					    if ( $listings->display_category && empty( $listings->loop['cats'] ) ) { ?>
							<div class="atbd_content_left">
								<div class="atbd_listing_category">
									<a href="#"><span class="<?php atbdp_icon_type(); ?>-tags"></span><?php _e('Uncategorized', 'directorist'); ?></a>
								</div>
							</div>
							<?php
						}

						if ($listings->display_view_count) {
							ob_start(); ?>
							<ul class="atbd_content_right">
								<li class="atbd_count"><span class="<?php echo atbdp_icon_type() ?>-eye"></span><?php echo ( ! empty($listings->loop['post_view']) ) ? $listings->loop['post_view'] : 0; ?></li>
							</ul>
							<?php
						}

					    echo apply_filters('atbdp_grid_footer_right_html', ob_get_clean() );
					    ?> 
					</div>
					<?php echo apply_filters('atbdp_listings_grid_cat_view_count', ob_get_clean() );
				}
				
				/**
				 * @param mixed $footer_html
				 * @since
				 * @package Directorist
				 */
				apply_filters('atbdp_listings_footer_content', '');
				?>
			</div>
		</article>
	</div>
</div>