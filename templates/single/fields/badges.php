<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 6.7
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

if ( !$listing->has_badge( $data ) ) {
	return;
}
?>

<div class="atbd_badges">

	<?php if ( $listing->display_new_badge( $data ) ): ?>
		<span class="atbd_badge atbd_badge_new"><?php echo esc_html( Helper::new_badge_text() ); ?></span>
	<?php endif; ?>

	<?php if ( $listing->display_featured_badge( $data ) ): ?>
		<span class="atbd_badge atbd_badge_featured"><?php echo esc_html( Helper::featured_badge_text() ); ?></span>
	<?php endif; ?>

	<?php if ( $listing->display_popular_badge( $data ) ): ?>
		<span class="atbd_badge atbd_badge_popular"><?php echo esc_html( Helper::popular_badge_text() ); ?></span>
	<?php endif; ?>

</div>