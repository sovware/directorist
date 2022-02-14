<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 6.7
 */

use wpWax\Directorist\Settings;

if ( ! defined( 'ABSPATH' ) ) exit;

if ( !$listing->has_badge( $data ) ) {
	return;
}
?>

<div class="directorist-info-item directorist-info-item-badges">

	<?php if ( $listing->display_new_badge( $data ) ): ?>
		<span class="directorist-badge directorist-badge-new"><?php echo esc_html( Settings::new_badge_text() ); ?></span>
	<?php endif; ?>

	<?php if ( $listing->display_featured_badge( $data ) ): ?>
		<span class="directorist-badge directorist-badge-featured"><?php echo esc_html( Settings::featured_badge_text() ); ?></span>
	<?php endif; ?>

	<?php if ( $listing->display_popular_badge( $data ) ): ?>
		<span class="directorist-badge directorist-badge-popular"><?php echo esc_html( Settings::popular_badge_text() ); ?></span>
	<?php endif; ?>

</div>