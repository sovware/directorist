<?php
/**
 * Advance review system init file.
 *
 * @package wpWax\Directorist
 * @subpackage Review
 * @since 7.x
 */
namespace wpWax\Directorist\Review;

defined( 'ABSPATH' ) || die();

use Directorist\Helper;

class Bootstrap {

	public static function init() {
		self::includes();
		self::hooks();
	}

	public static function includes() {
		require_once 'class-email.php';
		require_once 'class-markup.php';
		require_once 'class-builder.php';
		require_once 'class-comment.php';
		require_once 'class-activity.php';
		require_once 'class-review-meta.php';
		require_once 'class-comment-meta.php';

		if ( is_admin() ) {
			require_once 'class-metabox.php';
			require_once 'class-settings-panel.php';
			require_once 'class-builder-panel.php';
		}
	}

	public static function hooks() {
		add_filter( 'comments_template', array( __CLASS__, 'load_comments_template' ) );
		add_action( 'add_meta_boxes', array( __CLASS__, 'rename_comment_metabox' ), 20 );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_comment_scripts' ) );
		add_filter( 'register_post_type_args', array( __CLASS__, 'add_comment_support' ), 10, 2 );
		add_filter( 'admin_comment_types_dropdown', array( __CLASS__, 'add_comment_types_in_dropdown' ) );
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

	public static function rename_comment_metabox() {
		global $post;

		// Comments/Reviews.
		if ( isset( $post ) && ( 'publish' === $post->post_status || 'private' === $post->post_status ) && post_type_supports( ATBDP_POST_TYPE, 'comments' ) ) {
			remove_meta_box( 'commentsdiv', ATBDP_POST_TYPE, 'normal' );
			add_meta_box( 'commentsdiv', __( 'Reviews', 'directorist' ), 'post_comment_meta_box', ATBDP_POST_TYPE, 'normal' );
		}
	}

	public static function add_comment_types_in_dropdown( $comment_types ) {
		if ( ! isset( $comment_types['review'] ) ) {
			$comment_types['review'] = __( 'Review', 'directorist' );
		}
		return $comment_types;
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
		require_once ATBDP_INC_DIR . 'advanced-review/class-walker.php';
	}
}

Bootstrap::init();
