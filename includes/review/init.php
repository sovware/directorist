<?php
/**
 * Review bootstrap class.
 *
 * @package Directorist\Review
 * @since 7.0.6
 */
namespace Directorist\Review;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Directorist\Helper;

class Bootstrap {

	public static function init() {
		self::includes();
		self::hooks();
	}

	public static function includes() {
		require_once 'review-functions.php';

		require_once 'class-email.php';
		require_once 'class-markup.php';
		require_once 'class-builder.php';
		require_once 'class-comment.php';
		require_once 'class-activity.php';
		require_once 'class-comment-meta.php';
		require_once 'class-listing-review-meta.php';

		if ( is_admin() ) {
			require_once 'class-admin.php';
			require_once 'class-settings-screen.php';
			require_once 'class-builder-screen.php';
		}
	}

	public static function hooks() {
		add_filter( 'comments_template', array( __CLASS__, 'load_comments_template' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_comment_scripts' ) );
		add_filter( 'register_post_type_args', array( __CLASS__, 'add_comment_support' ), 10, 2 );
		add_filter( 'map_meta_cap', array( __CLASS__, 'map_meta_cap_for_review_author' ), 10, 4 );

		// Set comments per page.
		add_filter( 'option_comments_per_page', array( __CLASS__, 'comments_per_page' ) );
	}

	public static function comments_per_page( $per_page ) {
		if ( get_query_var( 'post_type' ) === ATBDP_POST_TYPE ) {
			$per_page = directorist_get_review_per_page();
		}

		return $per_page;
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

		if ( isset( $args['supports'] ) ) {
			$args['supports'] = array_merge( $args['supports'], [ 'comments' ] );
		}

		return $args;
	}

	public static function enqueue_comment_scripts() {
		if ( is_singular( ATBDP_POST_TYPE) && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}

	public static function load_comments_template( $template ) {
		if ( get_post_type() === ATBDP_POST_TYPE && file_exists( Helper::template_path( 'single-reviews' ) ) ) {
			$template = Helper::template_path( 'single-reviews' );
		}

		return $template;
	}

	public static function load_walker() {
		require_once ATBDP_INC_DIR . 'review/class-walker.php';
	}
}

Bootstrap::init();
