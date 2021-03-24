<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-listing-category">
	<?php if ( ! empty( $listings->loop['cats'] ) ) {
		$term_icon = get_term_meta( $listings->loop['cats'][0]->term_id, 'category_icon', true );
		$term_icon = atbdp_get_term_icon( [ 'icon' => $term_icon, 'default' => 'la la-folder-open' ] );
		$term_link = esc_url( get_term_link( $listings->loop['cats'][0]->term_id, ATBDP_CATEGORY ) );
		?>
		<a href="<?php echo esc_url( $term_link ); ?>"><?php echo $term_icon . esc_html($listings->loop['cats'][0]->name); ?></a>
		<?php
		$totalTerm = count($listings->loop['cats']);
		if ( $totalTerm > 1 ) { $totalTerm = $totalTerm - 1; ?>
			<div class="directorist-listing-category__popup">
				<span class="directorist-listing-category__extran-count">+<?php echo esc_html( $totalTerm ); ?></span>
				<div class="directorist-listing-category__popup__content">
					<?php foreach (array_slice($listings->loop['cats'], 1) as $cat) {
						$term_icon  = get_term_meta( $cat->term_id, 'category_icon', true );
						$term_icon  = atbdp_get_term_icon( [ 'icon' => $term_icon ] );
						$term_label = trim( "{$term_icon} {$cat->name}" );
						$term_link  = esc_url( ATBDP_Permalink::atbdp_get_category_page( $cat ) );
						$term_link  = esc_url( get_term_link( $cat->term_id, ATBDP_CATEGORY ) );

						echo "<a href='{$term_link}'>{$term_label}</a>";
					} ?>
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