<?php
if ( $listings->display_category || $listings->display_view_count ) { ?>
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

		$listings->loop_grid_footer_right_template();
	    ?> 
	</div>
	<?php
}