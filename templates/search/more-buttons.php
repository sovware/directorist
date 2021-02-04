<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="atbd_submit_btn_wrapper">

	<?php if ( $searchform->has_more_filters_button ): ?>

		<div class="atbd_filter_btn">
			<a href="#" class="more-filter btn btn-lg">

				<?php if ( $searchform->has_more_filters_icon() ): ?>
					<span class="'<?php atbdp_icon_type( true );?>-filter"></span>
				<?php endif;?>

				<?php echo esc_html( $searchform->more_filters_text );?>
			</a>
		</div>

	<?php endif ?>

	<?php if ( $searchform->has_search_button ): ?>

		<div class="atbd_submit_btn">
			<button type="submit" class="btn btn-lg btn_search">

				<?php if ( $searchform->has_search_button_icon() ): ?>
					<span class="'<?php atbdp_icon_type( true );?>-search"></span>
				<?php endif;?>

				<?php echo esc_html( $searchform->search_button_text );?>
				
			</button>
		</div>

	<?php endif; ?>

</div>