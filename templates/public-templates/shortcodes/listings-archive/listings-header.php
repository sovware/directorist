<?php
/**
 * @author  AazzTech
 * @since   7.0
 * @version 7.0
 */
?>
<!-- listings-archive > listings-header  -->
<div class="atbd_header_bar">
	<div class="<?php $listings->header_container_class();?>">
		<div class="row">
			<div class="col-md-12">
				<div class="atbd_generic_header">
					<?php if ($listings->has_listings_header()) { ?>
						<div class="atbd_generic_header_title">
							<?php if ($listings->has_filters_button) { ?>
								<a href="#" class="more-filter btn btn-outline btn-outline-primary">
									<?php if ($listings->has_filters_icon) { ?>
										<span class="<?php atbdp_icon_type(true); ?>-filter"></span>
									<?php } ?>
									<?php echo $listings->filter_button_text; ?>
								</a>
								<?php
							}
							/**
							* @since 5.4.0
							*/
							do_action('atbdp_after_filter_button_in_listings_header');
							
							if (!empty($listings->header_title)) {
								echo $listings->item_found_title();
							}
							?>
						</div>
						<?php
					}
					/**
					 * @since 5.4.0
					 */
					do_action('atbdp_after_total_listing_found_in_listings_header', $listings->header_title);
					
					if ($listings->has_header_toolbar()) { ?>
						<div class="atbd_listing_action_btn btn-toolbar" role="toolbar">
							<?php
							if ($listings->display_viewas_dropdown) {
								$listings->viewas_dropdown_template();
							}
							
							if ($listings->display_sortby_dropdown) {
								$listings->sortby_dropdown_template();
							}
							?>
						</div>
						<?php
					}
					?>
				</div>

				<div class="<?php $listings->filter_container_class(); ?>">
					<?php $listings->advanced_search_form_template();?>
				</div>
			</div>
		</div>
	</div>
</div>