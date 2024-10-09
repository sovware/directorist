<?php
/**
 * Comment and review template for single view.
 *
 * @since   7.1.0
 * @version 8.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Directorist\Review\Bootstrap;
use Directorist\Review\Builder;
use Directorist\Review\Markup;
use Directorist\Review\Walker as Review_Walker;
use Directorist\Review\Comment_Form_Renderer;
use Directorist\Directorist_Single_Listing;

$review_rating = directorist_get_listing_rating( get_the_ID() );
$review_count  = directorist_get_listing_review_count( get_the_ID() );
$review_text   = sprintf( _n( '%s review', '%s reviews', $review_count, 'directorist' ), number_format_i18n( $review_count ) );

// Load walker class
Bootstrap::load_walker();

$listing       = Directorist_Single_Listing::instance( get_the_ID() );
$section_data  = $listing->get_review_section_data();
$builder       = Builder::get( $section_data['section_data'] );
$section_id    = isset( $section_data['id'] ) ? $section_data['id'] : '';
$section_class = isset( $section_data['class'] ) ? $section_data['class'] : '';
$section_icon  = isset( $section_data['icon'] ) ? $section_data['icon'] : '';
$section_label = isset( $section_data['label'] ) ? $section_data['label'] : '';
?>
<section id="<?php echo esc_attr( $section_id ); ?>" class="directorist-review-container <?php echo esc_attr( $section_class ); ?>">
	<div class="directorist-card directorist-review-content">
		<div class="directorist-card__header directorist-review-content__header <?php if ( ! have_comments() ) : ?>directorist-review-content__header--noreviews<?php endif;?>">
			<?php if ( ! have_comments() ) : ?><?php endif;?>
			<h3 class="directorist-card__header__title">
				<?php if ( ! empty( $section_icon ) ) : ?>
					<span class="directorist-card__header-icon"><?php directorist_icon( $section_icon ); ?> </span>
				<?php endif; ?>
				<span class="directorist-card__header-text"><?php echo esc_html( $section_label ); ?></span>
			</h3>

			<?php if ( directorist_can_current_user_review() || directorist_can_guest_review() ) : ?>
				<a href="#respond" rel="nofollow" class="directorist-btn directorist-btn-primary"><?php directorist_icon( 'las la-star' ); ?><?php esc_attr_e( 'Write a Review', 'directorist' ); ?></a>
			<?php elseif ( ! is_user_logged_in() ) : ?>
				<a href="<?php echo esc_url( ATBDP_Permalink::get_login_page_url( array( 'redirect' => get_the_permalink(), 'scope' => 'review' ) ) ); ?>" rel="nofollow" class="directorist-btn directorist-btn-primary"><?php directorist_icon( 'las la-star' ); ?><?php esc_attr_e( 'Login to Write Your Review', 'directorist' ); ?></a>
			<?php endif; ?>
		</div><!-- ends: .directorist-review-content__header -->

		<?php if ( ! have_comments() ) : ?>
			<div class="directorist-card__body">
				<p class="directorist-review-single directorist-noreviews"><?php esc_html_e( 'There are no reviews yet.', 'directorist' ); ?></p>
			</div>
		<?php endif;?>

		<?php if ( have_comments() ): ?>
			<div class="directorist-card__body directorist-review-content__overview">
				<div class="directorist-review-content__overview__rating">
					<div class="directorist-rating-point"><?php echo esc_html( $review_rating ); ?></div>
					<div class="directorist-rating-content">
						<span class="directorist-rating-stars"><?php Markup::show_rating_stars( $review_rating );?></span>
						<span class="directorist-rating-overall"><?php echo esc_html( $review_text );?></span>
					</div>
				</div>
			</div><!-- ends: .directorist-review-content__overview -->

			<ul class="commentlist directorist-review-content__reviews">
				<?php wp_list_comments( array(
					'avatar_size' => 50,
					'format'      => 'html5',
					'walker'      => new Review_Walker(),
				) );?>
			</ul>
		<?php endif;?>
	</div><!-- ends: .directorist-review-content -->

	<?php if ( get_comment_pages_count() > 1 ) : ?>
		<nav class="directorist-review-content__pagination directorist-pagination" aria-label="Review Pagination">
			<?php
			$prev_text = directorist_icon( 'las la-arrow-left', false );
			$next_text = directorist_icon( 'las la-arrow-right', false );
			paginate_comments_links( array(
				'prev_text'    => $prev_text,
				'next_text'    => $next_text,
				'type'         => 'plain',
				'add_fragment' => '#' . $section_id,
			) );
			?>
		</nav>
	<?php endif;?>

	<?php
	if ( is_user_logged_in() || directorist_is_guest_review_enabled() || directorist_is_review_reply_enabled() ) {
		$commenter = wp_get_current_commenter();
		$req       = get_option( 'require_name_email' );
		$html_req  = ( $req ? " required='required'" : '' );

		$fields = array(
			'author' => sprintf(
				'<div class="directorist-form-group form-group-author">%s %s</div>',
				sprintf(
					'<label for="author">%s%s</label>',
					$builder->get_name_label( __( 'Name', 'directorist' ) ),
					( $req ? ' <span class="required">*</span>' : '' )
				),
				sprintf(
					'<input id="author" autocomplete="name" class="directorist-form-element" placeholder="%s" name="author" type="text" value="%s" size="30" maxlength="245"%s />',
					$builder->get_name_placeholder( __( 'Enter your name', 'directorist' ) ),
					esc_attr( $commenter['comment_author'] ),
					$html_req
				)
			),
			'email'  => sprintf(
				'<div class="directorist-form-group form-group-email">%s %s</div>',
				sprintf(
					'<label for="email">%s%s</label>',
					$builder->get_email_label( __( 'Email', 'directorist' ) ),
					( $req ? ' <span class="required">*</span>' : '' )
				),
				sprintf(
					'<input id="email" autocomplete="email" class="directorist-form-element" placeholder="%s" name="email" type="email" value="%s" size="30" maxlength="100" aria-describedby="email-notes"%s />',
					$builder->get_email_placeholder( __( 'Enter your email', 'directorist' ) ),
					esc_attr( $commenter['comment_author_email'] ),
					$html_req
				)
			),
		);

		if ( $builder->is_website_field_active() ) {
			$fields['url'] = sprintf(
				'<div class="directorist-form-group form-group-url"><label for="url">%s</label> %s</div>',
				$builder->get_website_label( __( 'Website', 'directorist' ) ),
				sprintf(
					'<input id="url" autocomplete="url" class="directorist-form-element" placeholder="%s" name="url" type="url" value="%s" size="30" maxlength="200" />',
					$builder->get_website_placeholder( __( 'Enter your website', 'directorist' ) ),
					esc_attr( $commenter['comment_author_url'] )
				)
			);
		}

		if ( ! $builder->is_cookies_consent_active() ) {
			$fields['cookies'] = '';
		}

		$comment_fields = array();
		$comment_fields['rating'] = '<div class="directorist-review-criteria">' . Markup::get_rating( 0 ) . '</div>';

		$comment_fields['content'] = sprintf(
			'<div class="directorist-form-group form-group-comment">%s</div>',
			sprintf( '<textarea id="comment" class="directorist-form-element" placeholder="%s" name="comment" cols="30" rows="10" maxlength="65525" required="required"></textarea>',
				$builder->get_comment_placeholder( __( 'Share your experience and help others make better choices', 'directorist' ) )
			)
		);

		$comment_fields = (array) apply_filters( 'directorist/review_form/comment_fields', $comment_fields );

		$comment_fields['redirect_to'] = sprintf(
			'<input type="hidden" value="%s" name="redirect_to">',
			get_the_permalink()
		);

		$container_class = 'directorist-card directorist-review-submit';
		if ( ! directorist_can_current_user_review() && ! directorist_can_guest_review() ) {
			$container_class .= ' directorist-review-submit--hidden';
		}

		$args = array(
			'fields'             => $fields,
			'comment_field'      => implode( "\n", $comment_fields ),
			'logged_in_as'       => '',
			'cancel_reply_link'  => __( 'Cancel Reply', 'directorist' ),
			'class_container'    => $container_class,
			'title_reply'        => __( 'Leave a Review', 'directorist' ),
			'title_reply_before' => '<div class="directorist-card__header directorist-review-submit__header"><h3 id="reply-title" class="directorist-card__header__title">',
			'title_reply_after'  => '</h3></div>',
			'class_form'         => 'directorist-card__body directorist-review-submit__form',
			'class_submit'       => 'directorist-btn directorist-btn-primary directorist-btn-lg',
			'label_submit'       => __( 'Submit Your Review', 'directorist' ),
			'format'             => 'html5',
			'submit_field'       => '<div class="directorist-form-group directorist-mb-0">%1$s %2$s</div>',
			'submit_button'      => '<button name="%1$s" type="submit" id="%2$s" class="%3$s" value="%4$s">%4$s</button>',
		);

		Comment_Form_Renderer::comment_form( apply_filters( 'directorist/review_form/comment_form_args', $args ) );
	}
	?>
</section>
