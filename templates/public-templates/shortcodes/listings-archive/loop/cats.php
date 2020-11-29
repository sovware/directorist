<?php
/**
 * @author  AazzTech
 * @since   6.6
 * @version 6.6
 */
?>
<div class="atbd_listing_category">
	<?php if ( ! empty( $listings->loop['cats'] ) ) {
		$term_icon = get_term_meta( $listings->loop['cats']->term_id, 'category_icon', true );
		$term_icon = atbdp_get_term_icon( [ 'icon' => $term_icon, 'default' => 'la-folder-open' ] );
		?>
		<a href="<?php echo esc_url( ATBDP_Permalink::atbdp_get_category_page($listings->loop['cats'][0]) ); ?>"><?php echo $term_icon . esc_html($listings->loop['cats'][0]->name); ?></a>
		<?php
		$totalTerm = count($listings->loop['cats']);
		if ( $totalTerm > 1 ) { $totalTerm = $totalTerm - 1; ?>
			<div class="atbd_cat_popup">
				<span>+<?php echo esc_html( $totalTerm ); ?></span>
				<div class="atbd_cat_popup_wrapper">
					<span>
						<?php foreach (array_slice($listings->loop['cats'], 1) as $cat) {
							$term_icon  = get_term_meta( $cat->term_id, 'category_icon', true );
							$term_icon  = atbdp_get_term_icon( [ 'icon' => $term_icon ] );
							$term_label = trim( "{$term_icon} {$cat->name}" );
							$term_link  = esc_url( ATBDP_Permalink::atbdp_get_category_page( $cat ) );
							
							echo "<span><a href='{$term_link}'>{$term_label}</a></span>";
						} ?>
					</span>
				</div>
			</div>
			<?php
		}
	}
	else { ?>
		<a href="#"><?php echo atbdp_get_term_icon(); ?><?php esc_html_e('Uncategorized', 'directorist'); ?></a>
		<?php
	}
	?>
</div>