<div class="atbd_listing_category">
	<?php if ( !empty( $listings->loop['cats'] ) ){ ?>
		<a href="<?php echo ATBDP_Permalink::atbdp_get_category_page($listings->loop['cats'][0]) ?>"><span class="<?php echo atbdp_icon_type() ?>-tags"></span><?php echo $listings->loop['cats'][0]->name; ?></a>
		<?php
		$totalTerm = count($listings->loop['cats']);
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
			<?php
		}
	}
	else { ?>
		<a href="#"><span class="<?php atbdp_icon_type(true); ?>-tags"></span><?php _e('Uncategorized', 'directorist'); ?></a>
		<?php
	}
	?>
</div>