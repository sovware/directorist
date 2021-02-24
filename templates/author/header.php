<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-author-profile-area">
	
	<?php do_action( 'directorist_before_author_profile_section' ); ?>

	<div class="directorist-card directorist-author-profile-wrap directorist-mb-40">
		<div class="directorist-card__body directorist-flex directorist-justify-content-between directorist-align-center">

			<div class="directorist-author-avatar">

				<?php echo $author->avatar_html(); ?>

				<div class="directorist-author-avatar__info">
					<h2 class="directorist-author-name"><?php echo esc_html( $author->display_name() ); ?></h2>
					<p><?php echo esc_html( $author->member_since_text() ); ?></p>
				</div>

			</div>

			<ul class="directorist-author-meta-list">

				<?php if ( $author->review_enabled() ): ?>
					
					<li class="directorist-author-meta-list__item">
						<span class="directorist-listing-rating-meta"><?php echo esc_html( $author->rating_count() ); ?><i class="<?php atbdp_icon_type(true); ?>-star"></i></span>
					</li>

					<li class="directorist-author-meta-list__item directorist-info-meta">
						<span class="directorist-review-count"><?php echo $author->review_count_html(); ?></span>
					</li>

				<?php endif; ?>

				<li class="directorist-author-meta-list__item directorist-info-meta">
					<span class="directorist-listing-count"><?php echo $author->listing_count_html(); ?></span>
				</li>

			</ul>

		</div>
	</div>

</div>