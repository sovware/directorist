<?php
/**
 * @author  AazzTech
 * @since   6.7
 * @version 6.7
 */

$featured = get_post_meta( $listing->id, '_featured', true );
$feature_badge_text = get_directorist_option('feature_badge_text', 'Feature');
$popular_badge_text = get_directorist_option('popular_badge_text', 'Popular');
?>
<div class="atbd_badges">
	<?php echo new_badge();?>

	<?php if ( $featured ): ?>
		<span class="atbd_badge atbd_badge_featured"><?php echo esc_html( $feature_badge_text ); ?></span>
	<?php endif; ?>

	<?php if ( atbdp_popular_listings($listing->id) === $listing->id ): ?>
		<span class="atbd_badge atbd_badge_popular"><?php echo esc_html( $popular_badge_text ); ?></span>
	<?php endif; ?>
</div>