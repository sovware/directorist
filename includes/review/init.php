<?php
/**
 * Review bootstrap class.
 *
 * @package Directorist\Review
 * @since 7.1.0
 */
namespace Directorist\Review;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Directorist\Helper;

class Bootstrap {

	public static function init() {
		self::include_files();
		self::setup_hooks();
	}

	public static function include_files() {
		require_once 'directorist-review-functions.php';
		require_once 'class-email.php';
		require_once 'class-markup.php';
		require_once 'class-builder.php';
		require_once 'class-comment.php';
		require_once 'class-comment-meta.php';
		require_once 'class-listing-review-meta.php';
		require_once 'class-comment-form-renderer.php';
		require_once 'class-comment-form-processor.php';

		if ( is_admin() ) {
			require_once 'class-admin.php';
			require_once 'class-settings-screen.php';
			require_once 'class-builder-screen.php';
		}
	}

	public static function setup_hooks() {
		add_action( 'wp_error_added', array( __CLASS__, 'update_error_message' ), 10, 4 );
		add_action( 'pre_get_posts', array( __CLASS__, 'override_comments_pagination' ) );
		add_filter( 'comments_template', array( __CLASS__, 'load_comments_template' ), 9999 );
		add_filter( 'register_post_type_args', array( __CLASS__, 'add_comment_support' ), 10, 2 );
		add_filter( 'map_meta_cap', array( __CLASS__, 'map_meta_cap_for_review_author' ), 10, 4 );
		add_filter( 'atbdp_login_redirection_page_url', array( __CLASS__, 'setup_login_redirect' ) );
		add_filter( 'safe_style_css', array( __CLASS__, 'add_safe_style_css' ) );
		add_filter( 'option_comment_registration', array( __CLASS__, 'reset_option_comment_registration' ) );
	}

	/**
	 * Setup comment_registration based on guest review setting.
	 *
	 * @param string/bool $status 0 or 1
	 *
	 * @return string/bool
	 */
	public static function reset_option_comment_registration( $status ) {
		if ( ! isset( $_POST['comment_post_ID'] ) ) {
			return $status;
		}

		if ( ! directorist_is_listing_post_type( $_POST['comment_post_ID'] ) ) {
			return $status;
		}

		if ( ! directorist_is_guest_review_enabled() ) {
			return $status;
		}

		return false;
	}

	/**
	 * Allows Custom CSS Properties
	 *
	 * @param array $styles Allowed Style List
	 *
	 * @return array $styles
	 */
	public static function add_safe_style_css( $styles ) {
		$styles[] = 'display';
		return $styles;
	}

	/**
	 * Redirect to review form after login.
	 *
	 * @param string $redirect
	 *
	 * @return string
	 */
	public static function setup_login_redirect( $redirect ) {
		if ( ! empty( $_GET['redirect'] ) ) {
			$scope = null;

			if ( ! empty( $_GET['scope'] ) && sanitize_text_field( wp_unslash( $_GET['scope'] ) ) === 'review' ) {
				$scope = '#respond';
			}

			return wp_sanitize_redirect( wp_unslash( $_GET['redirect'] ) ) . $scope;
		}

		return $redirect;
	}

	public static function update_error_message( $code, $message, $data, $wp_error ) {
		if ( $code === 'require_valid_comment' || $code === 'not_logged_in' ) {
			remove_action( 'wp_error_added', array( __CLASS__, 'update_error_message' ) );

			$comment_post_id = ! empty( $_POST['comment_post_ID'] ) ? absint( $_POST['comment_post_ID'] ) : 0;

			if ( $code === 'require_valid_comment' && directorist_is_listing_post_type( $comment_post_id ) ) {
				if ( ! empty( $_POST['comment_parent'] ) ) { // @codingStandardsIgnoreLine.
					$error_message = __( 'To submit your reply, please add your comment.', 'directorist' );
				} else {
					$error_message = __( 'To submit your review, please describe your rating.', 'directorist' );
				}
			}

			if ( $code === 'not_logged_in' && directorist_is_listing_post_type( $comment_post_id ) ) {
				$error_message = __( 'Sorry, you must be logged in to review or comment.', 'directorist' );
			}

			$wp_error->remove( $code );
			$wp_error->add( $code, $error_message, $data );

			add_action( 'wp_error_added', array( __CLASS__, 'update_error_message' ), 10, 4 );
		}
	}

	/**
	 * Fix comments pagination issue and override defaults.
	 *
	 * @param WP_Query $wp_query
	 *
	 * @return void
	 */
	public static function override_comments_pagination( $wp_query ) {
		if ( ! is_admin() && directorist_is_review_enabled() && $wp_query->is_single && $wp_query->get( 'post_type' ) === ATBDP_POST_TYPE ) {
			add_filter( 'option_page_comments', '__return_true' );
			add_filter( 'option_comment_registration', '__return_false' );
			add_filter( 'option_thread_comments', 'directorist_is_review_reply_enabled' );
			add_filter( 'option_thread_comments_depth', array( __CLASS__, 'override_comment_depth' ) );
			add_filter( 'option_comments_per_page', 'directorist_get_review_per_page' );
			add_filter( 'option_default_comments_page', array( __CLASS__, 'override_default_comments_page_option' ) );
			add_filter( 'option_comment_order', array( __CLASS__, 'override_comment_order_option' ) );
			add_filter( 'comments_template_query_args', array( __CLASS__, 'comments_template_query_args' ) );

			$wp_query->set( 'comments_per_page', directorist_get_review_per_page() );
		}
	}

	public static function override_comment_depth() {
		return 3;
	}

	public static function comments_template_query_args( $args ) {
		if ( ! directorist_is_review_reply_enabled() ) {
			$args['type'] = 'review';
		}

		return $args;
	}

	public static function override_default_comments_page_option() {
		return 'oldest';
	}

	public static function override_comment_order_option() {
		return 'desc';
	}

	/**
	 * Map meta capabilities for comment or review author.
	 *
	 * Since subscriber cannot edit their own comment so meta cap remapping is necessary.
	 *
	 * @param array $caps
	 * @param string $cap
	 * @param int $user_id
	 * @param mixed $args
	 *
	 * @return array
	 */
	public static function map_meta_cap_for_review_author( $caps, $cap, $user_id, $args ) {
		if ( $cap !== 'edit_comment' ) {
			return $caps;
		}

		$comment_id = current( $args );
		if ( ! $comment_id ) {
			return $caps;
		}

		$comment = get_comment( $comment_id );
		if ( ! $comment || ! $user_id || $user_id !== intval( $comment->user_id ) || get_post_type( $comment->comment_post_ID ) !== ATBDP_POST_TYPE ) {
			return $caps;
		}

		$post_type = get_post_type_object( ATBDP_POST_TYPE );
		$caps      = array(
			$post_type->cap->edit_posts,
			$post_type->cap->edit_published_posts,
		);

		return $caps;
	}

	public static function add_comment_support( $args, $post_type ) {
		if ( $post_type !== ATBDP_POST_TYPE ) {
			return $args;
		}

		$args['supports'] = array_merge( $args['supports'], [ 'comments' ] );

		return $args;
	}

	public static function load_comments_template( $template ) {
		if ( get_post_type() === ATBDP_POST_TYPE && directorist_is_review_enabled() && file_exists( Helper::template_path( 'single-reviews' ) ) ) {
			$template = Helper::template_path( 'single-reviews' );
		}

		return $template;
	}

	public static function load_walker() {
		require_once ATBDP_INC_DIR . 'review/class-review-walker.php';
	}
}

Bootstrap::init();
