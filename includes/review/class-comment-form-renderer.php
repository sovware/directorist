<?php
/**
 * Review form renderer.
 *
 * @package Directorist\Review
 * @since 7.7.0
 */
namespace Directorist\Review;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Exception;
use Directorist\Directorist_Single_Listing;

class Comment_Form_Renderer {

	const AJAX_ACTION = 'directorist_get_comment_edit_form';

	public static function init() {
		add_action( 'wp_ajax_' . self::AJAX_ACTION, array( __CLASS__, 'render' ) );
		add_action( 'wp_ajax_nopriv_' . self::AJAX_ACTION, array( __CLASS__, 'render' ) );
	}

	public static function get_ajax_url() {
		$url = add_query_arg(
			array(
				'action' => self::AJAX_ACTION,
				'nonce'  => wp_create_nonce( self::AJAX_ACTION ),
				'cpage'  => get_query_var( 'cpage' )
			),
			admin_url( 'admin-ajax.php', 'relative' )
		);

		return $url;
	}

	public static function render() {
		try {
			$nonce      = ! empty( $_REQUEST['nonce'] ) ? sanitize_key( $_REQUEST['nonce'] ) : '';
			$post_id    = ! empty( $_REQUEST['post_id'] ) ? absint( $_REQUEST['post_id'] ) : 0;
			$comment_id = ! empty( $_REQUEST['comment_id'] ) ? absint( $_REQUEST['comment_id'] ) : 0;

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
				throw new Exception( __( 'Invalid resource id.', 'directorist' ), 400 );
			}

			if ( get_post_type( $comment->comment_post_ID ) !== ATBDP_POST_TYPE ) {
				throw new Exception( __( 'Invalid listing id.', 'directorist' ), 400 );
			}

			$is_review          = ( $comment->comment_type === 'review' );
			$comment_type_label = $is_review ? __( 'review', 'directorist' ) : __( 'comment', 'directorist' );

			if ( ! is_user_logged_in() ) {
				throw new Exception( sprintf(
					__( 'Please login to update your %s.', 'directorist' ),
					$comment_type_label
				) );
			}

			if ( ! current_user_can( 'edit_comment', $comment_id ) ) {
				throw new Exception( sprintf( __( 'You are not allowed to edit this %s.', 'directorist' ), 400 ), $comment_type_label );
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
		$fields       = self::get_fields( $comment );
		$submit_label = __( 'Update Comment', 'directorist' );

		if ( $comment->comment_type === 'review' ) {
			$submit_label = __( 'Update Review', 'directorist' );
		}

		ob_start();
		?>
		<form id="directorist-form-comment-edit" class="directorist-review-submit__form directorist-form-comment-edit" action="<?php echo esc_url( self::get_action_url() ); ?>" method="post" enctype="multipart/form-data">
			<?php

			foreach ( $fields as $field ) {
				echo directorist_kses( $field );
			}

			wp_nonce_field( Comment_Form_Processor::AJAX_ACTION, 'directorist_comment_nonce' );
			?>
			<input type="hidden" value="<?php echo esc_attr( Comment_Form_Processor::AJAX_ACTION ); ?>" name="action">
			<input type="hidden" value="<?php echo esc_attr( $comment->comment_post_ID ); ?>" name="post_id">
			<input type="hidden" value="<?php echo esc_attr( $comment->comment_ID ); ?>" name="comment_id">
			<input type="hidden" value="<?php echo esc_attr( ! empty( $_REQUEST['cpage'] ) ? absint( $_REQUEST['cpage'] ) : 0 ); ?>" name="cpage">
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
				<a href="#" role="button" rel="nofollow" class="directorist-js-cancel-comment-edit" data-commentid="<?php echo esc_attr( $comment->comment_ID ); ?>"><?php esc_html_e( 'Cancel Editing', 'directorist' ); ?></a>
			</div>
		</form>
		<?php

		return ob_get_clean();
	}

	public static function get_fields( $comment ) {
		$fields  = array();
	
		$comment_type = __( 'comment', 'directorist' );
		if ( $comment->comment_type === 'review' ) {
			$rating = Comment::get_rating( $comment->comment_ID );
			$fields['rating'] = '<div class="directorist-review-criteria directorist-adv-criteria">' . Markup::get_rating( $rating, $comment ) . '</div>';
			$comment_type = __( 'review', 'directorist' );
		}
	
		$fields['content'] =  sprintf(
			'<div class="directorist-form-group form-group-comment">%s</div>',
			sprintf(
				'<textarea id="comment" class="directorist-form-element" placeholder="%s" name="comment" cols="30" rows="10" maxlength="65525" required="required">%s</textarea>',
				sprintf( __( 'Leave your update %s', 'directorist' ), $comment_type ),
				esc_textarea( $comment->comment_content )
			)
		);
	
		// Add custom action hook after textarea
		ob_start();
		do_action('directorist_after_comment_textarea', $comment);
		$fields['after_textarea'] = ob_get_clean();
	
		return $fields;
	}
	

	public static function get_action_url() {
		return admin_url( 'admin-ajax.php', 'relative' );
	}

	/**
	 * Render review, review reply and comment reply form.
	 *
	 * @see comment_form() wp core function. Directly copied from there and renamed filters.
	 *
	 * @param array $args
	 * @param int $post_id
	 *
	 * @return void
	 */
	public static function comment_form( $args = array(), $post_id = null ) {
		if ( null === $post_id ) {
			$post_id = get_the_ID();
		}

		// Exit the function when comments for the post are closed.
		if ( ! comments_open( $post_id ) ) {
			/**
			 * Fires after the comment form if comments are closed.
			 *
			 * @since 3.0.0
			 */
			do_action( 'directorist_comment_form_comments_closed' );

			return;
		}
		$listing       = Directorist_Single_Listing::instance();
		$section_data  = $listing->get_review_section_data();
		$builder       = Builder::get( $section_data['section_data'] );
		$commenter     = wp_get_current_commenter();
		$user          = wp_get_current_user();
		$user_identity = $user->exists() ? $user->display_name : '';

		$args = wp_parse_args( $args );
		if ( ! isset( $args['format'] ) ) {
			$args['format'] = current_theme_supports( 'html5', 'comment-form' ) ? 'html5' : 'xhtml';
		}

		$req      = get_option( 'require_name_email' );
		$html_req = ( $req ? " required='required'" : '' );
		$html5    = 'html5' === $args['format'];

		$fields = array(
			'author' => sprintf(
				'<p class="comment-form-author">%s %s</p>',
				sprintf(
					'<label for="author">%s%s</label>',
					__( 'Name', 'directorist' ),
					( $req ? ' <span class="required">*</span>' : '' )
				),
				sprintf(
					'<input id="author" name="author" type="text" value="%s" size="30" maxlength="245"%s />',
					esc_attr( $commenter['comment_author'] ),
					$html_req
				)
			),
			'email'  => sprintf(
				'<p class="comment-form-email">%s %s</p>',
				sprintf(
					'<label for="email">%s%s</label>',
					__( 'Email', 'directorist' ),
					( $req ? ' <span class="required">*</span>' : '' )
				),
				sprintf(
					'<input id="email" name="email" %s value="%s" size="30" maxlength="100" aria-describedby="email-notes"%s />',
					( $html5 ? 'type="email"' : 'type="text"' ),
					esc_attr( $commenter['comment_author_email'] ),
					$html_req
				)
			),
			'url'    => sprintf(
				'<p class="comment-form-url">%s %s</p>',
				sprintf(
					'<label for="url">%s</label>',
					__( 'Website', 'directorist' )
				),
				sprintf(
					'<input id="url" name="url" %s value="%s" size="30" maxlength="200" />',
					( $html5 ? 'type="url"' : 'type="text"' ),
					esc_attr( $commenter['comment_author_url'] )
				)
			),
		);

		if ( $builder->is_gdpr_consent()  ) {
			$args['fields']['gdpr_consent'] = sprintf(
				'<p class="comment-form-gdpr-consent comment-form-cookies-consent">
					<input id="directorist-gdpr-consent" name="directorist-gdpr-consent" type="checkbox" value="yes" required />
					<label for="directorist-gdpr-consent"><span class="required">*</span> %s</label>
				</p>',
				$builder->gdpr_consent_label()
			);
		}

		if ( has_action( 'set_comment_cookies', 'wp_set_comment_cookies' ) && get_option( 'show_comments_cookies_opt_in' ) ) {
			$consent = empty( $commenter['comment_author_email'] ) ? '' : ' checked="checked"';

			$fields['cookies'] = sprintf(
				'<p class="comment-form-cookies-consent">%s %s</p>',
				sprintf(
					'<input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"%s />',
					$consent
				),
				sprintf(
					'<label for="wp-comment-cookies-consent">%s</label>',
					__( 'Save my name, email, and website in this browser for the next time I comment.', 'directorist' )
				)
			);

			// Ensure that the passed fields include cookies consent.
			if ( isset( $args['fields'] ) && ! isset( $args['fields']['cookies'] ) ) {
				$args['fields']['cookies'] = $fields['cookies'];
			}
		}

		$required_text = sprintf(
			/* translators: %s: Asterisk symbol (*). */
			' ' . __( 'Required fields are marked %s', 'directorist' ),
			'<span class="required">*</span>'
		);

		/**
		 * Filters the default comment form fields.
		 *
		 * @since 3.0.0
		 *
		 * @param string[] $fields Array of the default comment fields.
		 */
		$fields = apply_filters( 'directorist_comment_form_default_fields', $fields );

		$defaults = array(
			'fields'               => $fields,
			'comment_field'        => sprintf(
				'<p class="comment-form-comment">%s %s</p>',
				sprintf(
					'<label for="comment">%s</label>',
					_x( 'Comment', 'noun', 'directorist' )
				),
				'<textarea id="comment" name="comment" cols="45" rows="8" maxlength="65525" required="required"></textarea>'
			),
			'must_log_in'          => sprintf(
				'<p class="must-log-in">%s</p>',
				sprintf(
					/* translators: %s: Login URL. */
					__( 'You must be <a href="%s">logged in</a> to post a comment.', 'directorist' ),
					/** This filter is documented in wp-includes/link-template.php */
					wp_login_url( apply_filters( 'the_permalink', get_permalink( $post_id ), $post_id ) )
				)
			),
			'logged_in_as'         => sprintf(
				'<p class="logged-in-as">%s</p>',
				sprintf(
					/* translators: 1: Edit user link, 2: Accessibility text, 3: User name, 4: Logout URL. */
					__( '<a href="%1$s" aria-label="%2$s">Logged in as %3$s</a>. <a href="%4$s">Log out?</a>', 'directorist' ),
					get_edit_user_link(),
					/* translators: %s: User name. */
					esc_attr( sprintf( __( 'Logged in as %s. Edit your profile.', 'directorist' ), $user_identity ) ),
					$user_identity,
					/** This filter is documented in wp-includes/link-template.php */
					wp_logout_url( apply_filters( 'the_permalink', get_permalink( $post_id ), $post_id ) )
				)
			),
			'comment_notes_before' => sprintf(
				'<p class="comment-notes">%s%s</p>',
				sprintf(
					'<span id="email-notes">%s</span>',
					__( 'Your email address will not be published.', 'directorist' )
				),
				( $req ? $required_text : '' )
			),
			'comment_notes_after'  => '',
			'action'               => site_url( '/wp-comments-post.php' ),
			'id_form'              => 'commentform',
			'id_submit'            => 'submit',
			'class_container'      => 'comment-respond',
			'class_form'           => 'comment-form',
			'class_submit'         => 'submit',
			'name_submit'          => 'submit',
			'title_reply'          => __( 'Leave a Reply', 'directorist' ),
			/* translators: %s: Author of the comment being replied to. */
			'title_reply_to'       => __( 'Leave a Reply to %s', 'directorist' ),
			'title_reply_before'   => '<h3 id="reply-title" class="comment-reply-title">',
			'title_reply_after'    => '</h3>',
			'cancel_reply_before'  => ' <small>',
			'cancel_reply_after'   => '</small>',
			'cancel_reply_link'    => __( 'Cancel reply', 'directorist' ),
			'label_submit'         => __( 'Post Comment', 'directorist' ),
			'submit_button'        => '<input name="%1$s" type="submit" id="%2$s" class="%3$s" value="%4$s" />',
			'submit_field'         => '<p class="form-submit">%1$s %2$s</p>',
			'format'               => 'xhtml',
		);

		/**
		 * Filters the comment form default arguments.
		 *
		 * Use {@see 'comment_form_default_fields'} to filter the comment fields.
		 *
		 * @since 3.0.0
		 *
		 * @param array $defaults The default comment form arguments.
		 */
		$args = wp_parse_args( $args, apply_filters( 'directorist_comment_form_defaults', $defaults ) );

		// Ensure that the filtered arguments contain all required default values.
		$args = array_merge( $defaults, $args );

		// Remove `aria-describedby` from the email field if there's no associated description.
		if ( isset( $args['fields']['email'] ) && false === strpos( $args['comment_notes_before'], 'id="email-notes"' ) ) {
			$args['fields']['email'] = str_replace(
				' aria-describedby="email-notes"',
				'',
				$args['fields']['email']
			);
		}

		/**
		 * Fires before the comment form.
		 *
		 * @since 3.0.0
		 */
		do_action( 'directorist_comment_form_before' );
		?>
		<div id="respond" class="<?php echo esc_attr( $args['class_container'] ); ?>">
			<?php
			echo wp_kses_post( $args['title_reply_before'] );

			comment_form_title( $args['title_reply'], $args['title_reply_to'] );

			// echo $args['cancel_reply_before'];



			// echo $args['cancel_reply_after'];

			echo wp_kses_post( $args['title_reply_after'] );

			if ( get_option( 'comment_registration' ) && ! is_user_logged_in() ) :

				echo wp_kses_post( $args['must_log_in'] );
				/**
				 * Fires after the HTML-formatted 'must log in after' message in the comment form.
				 *
				 * @since 3.0.0
				 */
				do_action( 'directorist_comment_form_must_log_in_after' );

			else :

				printf(
					'<form enctype="multipart/form-data" action="%s" method="post" id="%s" class="%s"%s>',
					esc_url( $args['action'] ),
					esc_attr( $args['id_form'] ),
					esc_attr( $args['class_form'] ),
					( $html5 ? ' novalidate' : '' )
				);

				/**
				 * Fires at the top of the comment form, inside the form tag.
				 *
				 * @since 3.0.0
				 */
				do_action( 'directorist_comment_form_top' );

				if ( is_user_logged_in() ) :

					/**
					 * Filters the 'logged in' message for the comment form for display.
					 *
					 * @since 3.0.0
					 *
					 * @param string $args_logged_in The logged-in-as HTML-formatted message.
					 * @param array  $commenter      An array containing the comment author's
					 *                               username, email, and URL.
					 * @param string $user_identity  If the commenter is a registered user,
					 *                               the display name, blank otherwise.
					 */
					echo wp_kses_post( apply_filters( 'directorist_comment_form_logged_in', $args['logged_in_as'], $commenter, $user_identity ) );

					/**
					 * Fires after the is_user_logged_in() check in the comment form.
					 *
					 * @since 3.0.0
					 *
					 * @param array  $commenter     An array containing the comment author's
					 *                              username, email, and URL.
					 * @param string $user_identity If the commenter is a registered user,
					 *                              the display name, blank otherwise.
					 */
					do_action( 'directorist_comment_form_logged_in_after', $commenter, $user_identity );

				else :

					echo wp_kses_post( $args['comment_notes_before'] );

				endif;

				// Prepare an array of all fields, including the textarea.
				$comment_fields = array( 'comment' => $args['comment_field'] ) + (array) $args['fields'];

				/**
				 * Filters the comment form fields, including the textarea.
				 *
				 * @since 4.4.0
				 *
				 * @param array $comment_fields The comment fields.
				 */
				$comment_fields = apply_filters( 'directorist_comment_form_fields', $comment_fields );

				// Get an array of field names, excluding the textarea.
				$comment_field_keys = array_diff( array_keys( $comment_fields ), array( 'comment' ) );

				// Get the first and the last field name, excluding the textarea.
				$first_field = reset( $comment_field_keys );
				$last_field  = end( $comment_field_keys );

				foreach ( $comment_fields as $name => $field ) {

					if ( 'comment' === $name || 'gdpr_consent' === $name || 'cookies' === $name ) {

						/**
						 * Filters the content of the comment textarea field for display.
						 *
						 * @since 3.0.0
						 *
						 * @param string $args_comment_field The content of the comment textarea field.
						 */
						echo directorist_kses( apply_filters( 'directorist_comment_form_field_comment', $field ) );

						echo wp_kses_post( $args['comment_notes_after'] );

					} elseif ( ! is_user_logged_in() ) {

						if ( $first_field === $name ) {
							/**
							 * Fires before the comment fields in the comment form, excluding the textarea.
							 *
							 * @since 3.0.0
							 */
							do_action( 'directorist_comment_form_before_fields' );
						}

						/**
						 * Filters a comment form field for display.
						 *
						 * The dynamic portion of the filter hook, `$name`, refers to the name
						 * of the comment form field. Such as 'author', 'email', or 'url'.
						 *
						 * @since 3.0.0
						 *
						 * @param string $field The HTML-formatted output of the comment form field.
						 */
						echo directorist_kses( apply_filters( "comment_form_field_{$name}", $field ) . "\n" );

						if ( $last_field === $name ) {
							/**
							 * Fires after the comment fields in the comment form, excluding the textarea.
							 *
							 * @since 3.0.0
							 */
							do_action( 'directorist_comment_form_after_fields' );
						}
					}
				}

				$submit_button = sprintf(
					$args['submit_button'],
					esc_attr( $args['name_submit'] ),
					esc_attr( $args['id_submit'] ),
					esc_attr( $args['class_submit'] ),
					esc_attr( $args['label_submit'] )
				);

				/**
				 * Filters the submit button for the comment form to display.
				 *
				 * @since 4.2.0
				 *
				 * @param string $submit_button HTML markup for the submit button.
				 * @param array  $args          Arguments passed to comment_form().
				 */
				$submit_button = apply_filters( 'directorist_comment_form_submit_button', $submit_button, $args );

				$submit_field = sprintf(
					$args['submit_field'],
					$submit_button . get_cancel_comment_reply_link( $args['cancel_reply_link'] ),
					get_comment_id_fields( $post_id )
				);

				/**
				 * Filters the submit field for the comment form to display.
				 *
				 * The submit field includes the submit button, hidden fields for the
				 * comment form, and any wrapper markup.
				 *
				 * @since 4.2.0
				 *
				 * @param string $submit_field HTML markup for the submit field.
				 * @param array  $args         Arguments passed to comment_form().
				 */

				echo directorist_kses( apply_filters( 'directorist_comment_form_submit_field', $submit_field, $args ) );

				/**
				 * Fires at the bottom of the comment form, inside the closing form tag.
				 *
				 * @since 1.5.0
				 *
				 * @param int $post_id The post ID.
				 */
				do_action( 'directorist_comment_form', $post_id );

				echo '</form>';

			endif;
			?>
		</div><!-- #respond -->
		<?php

		/**
		 * Fires after the comment form.
		 *
		 * @since 3.0.0
		 */
		do_action( 'directorist_comment_form_after' );
	}
}

Comment_Form_Renderer::init();
