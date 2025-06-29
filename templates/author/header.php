<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.7.0
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<section class="directorist-author-profile-area directorist-author-profile">

	<?php do_action( 'directorist_before_author_profile_section' ); ?>

	<div class="directorist-card directorist-author-profile__wrap">

		<div class="directorist-author-avatar directorist-author-profile__avatar">

			<?php echo wp_kses_post( $author->avatar_html() ); ?>

			<div class="directorist-author-avatar__info directorist-author-profile__avatar__info">
				<h2 class="directorist-author-name directorist-author-profile__avatar__info__name"><?php echo esc_html( $author->display_name() ); ?></h2>
				<p><?php echo esc_html( $author->member_since_text() ); ?></p>
			</div>

		</div>

		<ul class="directorist-author-meta-list directorist-author-profile__meta-list">

			<?php if ( $author->review_enabled() ): ?>
				<li class="directorist-author-meta-list__item directorist-info-meta directorist-author-profile__meta-list__item">
					<?php directorist_icon( 'fas fa-star' ); ?>
					<span class="directorist-review-count">
						<?php echo wp_kses_post( $author->rating_count() ); ?>
						<?php echo wp_kses_post( $author->review_count_html() ); ?>
					</span>
				</li>

			<?php endif; ?>

			<li class="directorist-author-meta-list__item directorist-info-meta directorist-author-profile__meta-list__item">
				<?php directorist_icon( 'fas fa-list-ol' ); ?>
				<span class="directorist-listing-count">
					<?php echo wp_kses_post( $author->listing_count_html() ); ?>
				</span>
			</li>

		</ul>

	</div>

</section>