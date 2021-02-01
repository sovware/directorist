<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="atbd_auhor_profile_area">

	<div class="atbd_author_avatar">

		<?php echo $author->avatar_html(); ?>

		<div class="atbd_auth_nd">
			<h2><?php echo esc_html( $author->display_name() ); ?></h2>
			<p><?php echo esc_html( $author->member_since_text() ); ?></p>
		</div>

	</div>

	<div class="atbd_author_meta">

		<?php if ( $author->review_enabled() ): ?>
			<div class="atbd_listing_meta">
				<span class="atbd_meta atbd_listing_rating">
					<?php echo esc_html( $author->rating_count() ); ?><i class="<?php atbdp_icon_type(true); ?>-star"></i>
				</span>
			</div>
			<p class="meta-info"><?php echo $author->review_count_html(); ?></p>			
		<?php endif; ?>

		<p class="meta-info"><?php echo $author->listing_count_html(); ?></p>
	</div>

</div>