<?php
/**
 * Comment and review template for single view.
 *
 * @since   7.0.6
 * @version 7.0.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Directorist\Review\Markup;
use Directorist\Review\Builder;
use Directorist\Review\Bootstrap;
use Directorist\Review\Walker as Review_Walker;
use Directorist\Review\Listing_Review_Meta as Review_Meta;

$builder       = Builder::get( get_the_ID() );
$review_rating = Review_Meta::get_rating( get_the_ID() );
$review_count  = Review_Meta::get_review_count( get_the_ID() );

// Load walker class
Bootstrap::load_walker();
?>
<div class="directorist-review-container">
	<div class="directorist-review-content">
		<div class="directorist-review-content__header">
			<h3><?php printf( '%s <span>%s</span>', strip_tags( get_the_title() ), sprintf( _n( '%s response', '%s responses', get_comments_number(), 'directorist' ), get_comments_number() ) ); ?></h3>
			<?php if ( is_user_logged_in() || directorist_is_guest_review_enabled() ) : ?>
				<a href="#respond" class="directorist-btn directorist-btn-primary"><span class="fa fa-star"></span> <?php esc_html_e( 'Write a review', 'directorist' ); ?></a>
			<?php endif; ?>
		</div><!-- ends: .directorist-review-content__header -->

		<?php if ( have_comments() ) : ?>
			<div class="directorist-review-content__overview">
				<div class="directorist-review-content__overview__rating">
					<span class="directorist-rating-point"><?php echo $review_rating; ?></span>
					<span class="directorist-rating-stars"><?php Markup::show_rating_stars( $review_rating ); ?></span>
					<span class="directorist-rating-overall"><?php printf( _n( '%s review', '%s reviews', $review_count, 'directorist' ), number_format_i18n( $review_count ) ); ?></span>
				</div>
			</div><!-- ends: .directorist-review-content__overview -->

			<ul class="commentlist directorist-review-content__reviews">
				<?php wp_list_comments( array(
					'avatar_size' => 50,
					'format'      => 'html5',
					'walker'      => new Review_Walker(),
				) ); ?>
			</ul>

			<?php if ( get_comment_pages_count() > 1 ) : ?>
			<nav class="directorist-review-content__pagination">
				<?php paginate_comments_links( array(
					'prev_text' => '<i class="la la-arrow-left"></i>',
					'next_text' => '<i class="la la-arrow-right"></i>',
					'type'      => 'list',
				) ); ?>
			</nav>
			<?php endif; ?>
		<?php else : ?>
			<div class="directorist-review-content__reviews">
				<p class="directorist-review-single directorist-noreviews">
					<?php
					if ( ! directorist_is_guest_review_enabled() ) {
						esc_html_e( 'There are no reviews yet.', 'directorist' );
					} else {
						printf( esc_html__( 'There are no reviews yet. %1$sBe the first reviewer%2$s.', 'directorist' ), '<a href="#respond">', '</a>' );
					}
					?>
				</p>
			</div>
		<?php endif; ?>
	</div><!-- ends: .directorist-review-content -->

	<?php
	if ( is_user_logged_in() || directorist_is_guest_review_enabled() ) {
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
					'<input id="author" class="directorist-form-element" placeholder="%s" name="author" type="text" value="%s" size="30" maxlength="245"%s />',
					$builder->get_name_placeholder( __( 'Enter your name', 'directorist' ) ),
					esc_attr( $commenter['comment_author'] ),
					$html_req
				)
			),
			'email' => sprintf(
				'<div class="directorist-form-group form-group-email">%s %s</div>',
				sprintf(
					'<label for="email">%s%s</label>',
					$builder->get_email_label( __( 'Email', 'directorist' ) ),
					( $req ? ' <span class="required">*</span>' : '' )
				),
				sprintf(
					'<input id="email" class="directorist-form-element" placeholder="%s" name="email" type="email" value="%s" size="30" maxlength="100" aria-describedby="email-notes"%s />',
					$builder->get_email_placeholder( __( 'Enter your email', 'directorist' ) ),
					esc_attr( $commenter['comment_author_email'] ),
					$html_req
				)
			),
			'url' => sprintf(
				'<div class="directorist-form-group form-group-url">%s %s</div>',
				sprintf(
					'<label for="url">%s</label>',
					$builder->get_website_label( __( 'Website', 'directorist' ) ),
				),
				sprintf(
					'<input id="url" class="directorist-form-element" placeholder="%s" name="url" type="url" value="%s" size="30" maxlength="200" />',
					$builder->get_website_placeholder( __( 'Enter your website', 'directorist' ) ),
					esc_attr( $commenter['comment_author_url'] )
				)
			),
		);

		$comment_fields = array();
		$comment_fields['rating'] = '<div class="directorist-review-criteria">' . Markup::get_rating( $builder ) . '</div>';

		$comment_fields['content'] = sprintf(
			'<div class="directorist-form-group form-group-comment">%s %s</div>',
			sprintf(
				'<label for="comment">%s</label>',
				$builder->get_comment_label( _x( 'Comment', 'noun', 'directorist' ) )
			),
			sprintf( '<textarea id="comment" class="directorist-form-element" placeholder="%s" name="comment" cols="30" rows="10" maxlength="65525" required="required"></textarea>',
				$builder->get_comment_placeholder( __( 'Share your experience and help others make better choices', 'directorist' ) )
			)
		);

		$comment_fields = (array) apply_filters( 'directorist/review_form/comment_fields', $comment_fields );

		$args = array(
			'fields'             => $fields,
			'comment_field'      => implode( "\n", $comment_fields ),
			'logged_in_as'       => '',
			'class_container'    => 'directorist-review-submit',
			'title_reply'        => __( 'Review', 'directorist' ),
			'title_reply_before' => '<div class="directorist-review-submit__header"><h3 id="reply-title">',
			'title_reply_after'  => '</h3></div>',
			'class_form'         => 'comment-form directorist-review-submit__form',
			'class_submit'       => 'directorist-btn directorist-btn-primary',
			'label_submit'       => __( 'Submit review', 'directorist' ),
			'format'             => 'html5',
			'submit_field'       => '<div class="directorist-form-group">%1$s %2$s</div>',
			'submit_button'      => '<button name="%1$s" type="submit" id="%2$s" class="%3$s" value="%4$s">%4$s</button>',
		);

		comment_form( apply_filters( 'directorist/review_form/comment_form_args', $args ) );
	}
	?>
</div>
