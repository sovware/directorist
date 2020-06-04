<?php
/**
 * @author  AazzTech
 * @since   7.0
 * @version 7.0
 */
?>
<div class="atbd_listing_category">
	<?php if ( !empty( $listings->loop['cats'] ) ){ ?>
		<a href="<?php echo esc_url( ATBDP_Permalink::atbdp_get_category_page($listings->loop['cats'][0]) ); ?>"><span class="<?php atbdp_icon_type(true) ?>-tags"></span><?php echo esc_html($listings->loop['cats'][0]->name); ?></a>
		<?php
		$totalTerm = count($listings->loop['cats']);
		if ($totalTerm > 1) {
			$totalTerm = $totalTerm - 1;
			?>
			<div class="atbd_cat_popup">
				<span>+<?php echo esc_html( $totalTerm ); ?></span>
				<div class="atbd_cat_popup_wrapper">
					<span>
						<?php
						foreach (array_slice($listings->loop['cats'], 1) as $cat) {
							printf('<span><a href="%s">%s<span>,</span></a></span>', esc_url(ATBDP_Permalink::atbdp_get_category_page($cat)), esc_html( $cat->name ));
						}
						?>
					</span>
				</div>
			</div>
			<?php
		}
	}
	else { ?>
		<a href="#"><span class="<?php atbdp_icon_type(true); ?>-tags"></span><?php esc_html_e('Uncategorized', 'directorist'); ?></a>
		<?php
	}
	?>
</div>