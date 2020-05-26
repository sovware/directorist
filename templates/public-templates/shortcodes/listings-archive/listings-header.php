<?php
/**
 * @author  AazzTech
 * @since   7.0
 * @version 7.0
 */
?>
<div class="atbd_header_bar">
	<div class="<?php $listing->header_container_class();?>">
		<div class="row">
			<div class="col-md-12">
				<div class="atbd_generic_header">
					<?php if ($listing->has_listings_header()) { ?>
						<div class="atbd_generic_header_title">
							<?php if ($listing->has_filter_button()) { ?>
								<a href="#" class="more-filter btn btn-outline btn-outline-primary">
									<?php if ($listing->has_filter_icon()) { ?>
										<span class="<?php atbdp_icon_type(true); ?>-filter"></span>
									<?php } ?>
									<?php echo $listing->filters; ?>
								</a>
							<?php
							}
							/**
							* @since 5.4.0
							*/
							do_action('atbdp_after_filter_button_in_listings_header');
							if ($listing->has_header_title()) {
								echo apply_filters('atbdp_total_listings_found_text', "<h3>{$listing->header_title}</h3>", $listing->header_title);
							}
							?>
						</div>
					<?php
					}
					/**
					 * @since 5.4.0
					 */
					do_action('atbdp_after_total_listing_found_in_listings_header', $listing->header_title);
					
					if ($listing->has_listings_header_toolbar()) {
					?>
						<div class="atbd_listing_action_btn btn-toolbar" role="toolbar">
							<?php
							if ($listing->display_viewas_dropdown) {
								$listing->viewas_dropdown_html();
							}
							
							if ($listing->display_sortby_dropdown) {
								$listing->sortby_dropdown_html();
							}
							?>
						</div>
					<?php } ?>
				</div>

				<div class="<?php $listing->filter_container_class(); ?>">
					<?php $listing->advanced_search_form_html();?>
				</div>
			</div>
		</div>
	</div>
</div>