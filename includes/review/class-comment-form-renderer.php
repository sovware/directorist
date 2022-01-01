<?php
/**
 * Review form renderer.
 *
 * @package Directorist\Review
 * @since 7.1.0
 */
namespace Directorist\Review;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Exception;

class Comment_Form_Renderer {

	const AJAX_ACTION = 'directorist_get_comment_edit_form';

	public static function init() {
		add_action( 'wp_ajax_' . self::AJAX_ACTION, array( __CLASS__, 'render' ) );
		add_action( 'wp_ajax_nopriv_' . self::AJAX_ACTION, array( __CLASS__, 'render' ) );
	}

	public static function get_ajax_url( $type = 'add' ) {
		$url = add_query_arg(
			array(
				'action' => self::AJAX_ACTION,
				'nonce'  => wp_create_nonce( self::AJAX_ACTION ),
			),
			admin_url( 'admin-ajax.php', 'relative' )
		);

		return $url;
	}

	public static function render() {
		try {
			$nonce      = ! empty( $_GET['nonce'] ) ? $_GET['nonce'] : '';
			$post_id    = ! empty( $_GET['post_id'] ) ? absint( $_GET['post_id'] ) : 0;
			$comment_id = ! empty( $_GET['comment_id'] ) ? absint( $_GET['comment_id'] ) : 0;

			if ( ! wp_verify_nonce( $nonce, self::AJAX_ACTION ) ) {
				throw new Exception( __( 'Invalid request.', 'directorist' ), 400 );
			}

			if ( ! directorist_is_review_enabled() ) {
				throw new Exception( __( 'Review is disabled.', 'directorist' ) );
			}

			if ( get_post_type( $post_id ) !== ATBDP_POST_TYPE ) {
				throw new Exception( __( 'Invalid listing id.', 'directorist' ), 400 );
			}

			$comment = get_comment( $comment_id );
			if ( is_null( $comment ) ) {
				throw new Exception( __( 'Invalid resource id.', 'directorist' ) );
			}

			if ( ! is_user_logged_in() ) {
				throw new Exception( sprintf(
					__( 'Please login to update your %s.', 'directorist' ),
					( $comment->comment_type === 'review' ? __( 'review', 'directorist' ) : __( 'comment', 'directorist' ) )
				) );
			}

			$form = self::get_form_markup( $comment );

			wp_send_json_success( array(
				'error' => '',
				'html'  => $form,
			) );
		} catch ( Exception $e ) {
			$html = sprintf( '<div class="directorist-alert directorist-alert-danger">%s</div>', $e->getMessage() );

			wp_send_json_error( array(
				'error' => $e->getMessage(),
				'html'  => $html,
			) );
		}
	}

	public static function get_form_markup( $comment ) {
		$fields = self::get_fields();

		$submit_label = __( 'Update Comment', 'directorist' );
		if ( $comment->comment_type === 'review' ) {
			$submit_label = __( 'Update Review', 'directorist' );
		}

		ob_start();
		?>
		<form class="directorist-review-submit__form directorist-form-comment-edit" action="<?php echo esc_url( self::get_action_url() ); ?>">
			<?php
			foreach ( $fields as $field_key => $field ) {
				if ( $comment->comment_type !== 'review' && $field_key === 'rating' ) {
					continue;
				}
				echo $field;
			}
			?>
			<input type="hidden" value="<?php echo esc_attr( $comment->comment_post_ID ); ?>" name="post_id">
			<input type="hidden" value="<?php echo esc_attr( $comment->comment_ID ); ?>" name="comment_id">
			<div class="directorist-form-group directorist-mb-0">
				<?php
				printf(
					'<button name="%1$s" type="submit" class="%2$s" value="%3$s">%4$s</button>',
					'directorist-comment-submit',
					'directorist-btn directorist-btn-primary',
					esc_attr( $submit_label ),
					esc_html( $submit_label )
				);
				?>
				<a href="#" rel="nofollow" class="directorist-js-cancel-comment-edit" data-commentid="<?php echo esc_attr( $comment->comment_ID ); ?>"><?php esc_html_e( 'Cancel editing', 'directorist' ); ?></a>
			</div>
		</form>
		<?php

		return ob_get_clean();
	}

	public static function get_fields( $comment_id = 0 ) {
		$builder   = Builder::get( get_the_ID() );
		$commenter = wp_get_current_commenter();
		$html_req  = " required='required'";

		$fields = [];
		$fields['rating'] = '<div class="directorist-review-criteria">' . Markup::get_rating() . '</div>';
		$fields['content'] = sprintf(
			'<div class="directorist-form-group form-group-comment">%s %s</div>',
			sprintf(
				'<label for="comment">%s</label>',
				$builder->get_comment_label( _x( 'Comment', 'noun', 'directorist' ) )
			),
			sprintf( '<textarea id="comment" class="directorist-form-element" placeholder="%s" name="comment" cols="30" rows="10" maxlength="65525" required="required"></textarea>',
				$builder->get_comment_placeholder( __( 'Share your experience and help others make better choices', 'directorist' ) )
			)
		);

		if ( ! is_user_logged_in() ) {
			$fields['author'] = sprintf(
				'<div class="directorist-form-group form-group-author">%s %s</div>',
				sprintf(
					'<label for="author">%s <span class="required">*</span></label>',
					$builder->get_name_label( __( 'Name', 'directorist' ) )
				),
				sprintf(
					'<input id="author" autocomplete="name" class="directorist-form-element" placeholder="%s" name="author" type="text" value="%s" size="30" maxlength="245"%s />',
					$builder->get_name_placeholder( __( 'Enter your name', 'directorist' ) ),
					esc_attr( $commenter['comment_author'] ),
					$html_req
				)
			);

			$fields['email'] = sprintf(
				'<div class="directorist-form-group form-group-email">%s %s</div>',
				sprintf(
					'<label for="email">%s <span class="required">*</span></label>',
					$builder->get_email_label( __( 'Email', 'directorist' ) )
				),
				sprintf(
					'<input id="email" autocomplete="email" class="directorist-form-element" placeholder="%s" name="email" type="email" value="%s" size="30" maxlength="100" aria-describedby="email-notes"%s />',
					$builder->get_email_placeholder( __( 'Enter your email', 'directorist' ) ),
					esc_attr( $commenter['comment_author_email'] ),
					$html_req
				)
			);

			if ( $builder->is_website_field_active() ) {
				$fields['url'] = sprintf(
					'<div class="directorist-form-group form-group-url">%s %s</div>',
					sprintf(
						'<label for="url">%s</label>',
						$builder->get_website_label( __( 'Website', 'directorist' ) ),
					),
					sprintf(
						'<input id="url" autocomplete="url" class="directorist-form-element" placeholder="%s" name="url" type="url" value="%s" size="30" maxlength="200" />',
						$builder->get_website_placeholder( __( 'Enter your website', 'directorist' ) ),
						esc_attr( $commenter['comment_author_url'] )
					)
				);
			}
		}

		return $fields;
	}

	public static function get_action_url() {
		$url = add_query_arg(
			array(
				'action' => Comment_Form_Processor::AJAX_NONCE,
				'nonce'  => wp_create_nonce( Comment_Form_Processor::AJAX_NONCE )
			),
			admin_url( 'admin-ajax.php', 'relative' )
		);

		return $url;
	}
}

Comment_Form_Renderer::init();
