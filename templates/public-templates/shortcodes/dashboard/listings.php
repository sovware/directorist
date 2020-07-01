<?php
/**
 * @author  AazzTech
 * @since   7.0
 * @version 7.0
 */
?>
<div class="atbd_tab_inner tabContentActive" id="my_listings">
    <div class="row data-uk-masonry">

        <?php
        if (!empty($listing_items)) {
            foreach ($listing_items as $item) { ?>
               
                <div class="col-lg-4 col-sm-6" id="listing_id_<?php echo esc_attr( $item['id'] ); ?>">
					<div class="atbd_single_listing atbd_listing_card">
						<article class="atbd_single_listing_wrapper<?php echo esc_attr( $featured_class ); ?>">

							<figure class="atbd_listing_thumbnail_area">
								<div class="atbd_listing_image">
									<a href="<?php echo esc_url($item['permalink']); ?>">
										<?php
										if ($item['has_thumbnail']) {
											atbdp_thumbnail_card($item['thumbnail_img']);
										}
										?>
									</a>

									<figcaption class="atbd_thumbnail_overlay_content">
	                                    <div class="atbd_upper_badge">
	                                        <?php
	                                        if ($item['featured']) { ?>
	                                        	<span class="atbd_badge atbd_badge_featured"><?php echo esc_html($featured_text);?></span>
	                                        	<?php
	                                        }
	                                        ?>
	                                    </div>
	                                    <?php do_action('atbdp_after_user_dashboard_upper_badge', $item['id']);?>
									</figcaption>
								</div>
							</figure>
							
							<div class="atbd_listing_info">
								<div class="atbd_content_upper">
									<div class="atbd_dashboard_title_metas">
										<h4 class="atbd_listing_title"><a href="<?php echo esc_url($item['permalink']); ?>"><?php echo esc_html( $item['title'] ); ?></a></h4>
                                        <?php
                                        /**
                                         * @since 1.0.0
                                         */
                                        do_action('atbdp_after_listing_tagline');
                                        ?>
									</div>
								</div>

                                <div class="atbd_listing_bottom_content">
                                    <div class="listing-meta">
                                        <div class="listing-meta-content">
	                                        <?php
	                                        /**
	                                         * @since 5.0.3
	                                         */
	                                        do_action('atbdp_user_dashboard_listings_before_expiration', $item['id']);
	                                        ?>

	                                        <p><span><?php esc_html_e( 'Expiration:', 'directorist' ); ?>:</span> <?php echo $item['exp_html'];?></p>

	                                        <p><?php printf('<span class="%s">%s</span>', $item['status_label_class'], $item['status_label'])?></p>

                                        </div>

                                        <?php
                                        /**
                                         * @since 6.3.3
                                         */
                                        do_action('atbdp_user_dashboard_before_button', $item['id']);
                                        ?>

                                        <div class="db_btn_area">
                                            <?php
                                            if ('renewal' == $item['status'] || 'expired' == $item['status']) {
                                                if ( $can_renew ) {
	                                                if (is_fee_manager_active()) {
	                                                    ?>
	                                                    <a href="" data-target="<?php echo esc_attr($item['modal_id']); ?>" data-listing_id="<?php echo esc_attr( $item['id'] ); ?>" class="directory_btn btn btn-outline-success atbdp_renew_with_plan"><?php esc_html_e('Renew', 'directorist'); ?></a>
	                                                    <?php
	                                                }
	                                                else { ?>
	                                                    <a href="<?php echo add_query_arg( 'renew_from', 'dashboard', esc_url(ATBDP_Permalink::get_renewal_page_link($item['id'])) ) ?>" id="directorist-renew" data-listing_id="<?php echo esc_attr( $item['id'] ); ?>" class="directory_btn btn btn-outline-success"><?php esc_html_e('Renew', 'directorist'); ?></a>
		                                                <?php
		                                            }
                                                }
                                            }
                                            else {
                                                // show promotions if the featured is available
                                                if ($featured_active && !$item['featured'] && !is_fee_manager_active()) { ?>
                                                    <div class="atbd_promote_btn_wrapper">
                                                        <a href="<?php echo esc_url(ATBDP_Permalink::get_checkout_page_link($item['id'])) ?>" data-listing_id="<?php echo $post->ID; ?>" class="directory_btn btn btn-primary"><?php esc_html_e('Promote', 'directorist'); ?></a>
                                                    </div>
	                                                <?php
	                                            }
                                            } 
                                            ?>
                                            <div>
                                            	<a href="<?php echo esc_url(ATBDP_Permalink::get_edit_listing_page_link($item['id'])); ?>"
                                               class="directory_edit_btn edit_listing"><?php esc_html_e('Edit', 'directorist'); ?></a>
	                                            <a href="#" data-listing_id="<?php echo esc_attr( $item['id'] ); ?>"class="directory_remove_btn remove_listing"><?php esc_html_e('Delete', 'directorist'); ?></a>
	                                        </div>

                                        </div>
                                    </div>
                                </div>

							</div>

						</article>
					</div>
                </div>
            	<?php
            }
        }

        else { ?>
        	<p class="col-12 atbdp_nlf"><?php esc_html_e("Looks like you have not created any listing yet!", 'directorist');?></p>
            <?php
        }

        if ($has_pagination) { ?>
            <div class="col-12"><?php echo atbdp_pagination($listings, $paged);?></div>
        <?php } ?>

    </div>
</div>