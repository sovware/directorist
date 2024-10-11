<?php
/**
 * Admin functionality class.
 * Handles metabox, admin menu etc.
 *
 * @package Directorist\Review
 * @since 7.1.0
 */
namespace Directorist\Review;
use Directorist\Directorist_Single_Listing;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Metabox {

	public static function init() {
		add_action( 'admin_menu', [ __CLASS__, 'add_menu' ] );
		add_action( 'add_meta_boxes_comment', [ __CLASS__, 'register' ] );
		add_action( 'edit_comment', [ __CLASS__, 'on_edit_comment' ], 10, 2 );
		add_action( 'add_meta_boxes', [ __CLASS__, 'update_meta_boxes' ], 20 );
		add_filter( 'admin_comment_types_dropdown', [ __CLASS__, 'add_comment_types_in_dropdown' ] );

		add_action( 'directorist/admin/review/meta_fields', [ __CLASS__, 'render_rating_meta_field' ] );

		add_filter( 'comment_edit_redirect', [ __CLASS__, 'comment_edit_redirect' ], 10, 2 );
		add_filter( 'wp_update_comment_data', [ __CLASS__, 'update_comment_data' ], 10, 2 );
	}

	public static function update_comment_data( $data, $comment ) {
		if ( get_current_user_id() === intval( $comment['user_id'] ) && ! current_user_can( 'moderate_comments' ) ) {
			$data['comment_approved'] = $comment['comment_approved'];
			$data['comment_date']     = $comment['comment_date'];
			$data['comment_date_gmt'] = $comment['comment_date_gmt'];
		}

		return $data;
	}

	public static function comment_edit_redirect( $location, $comment_id ) {
		if ( ! current_user_can( 'moderate_comments' ) ) {
			return add_query_arg( array(
				'action' => 'editcomment',
				'c'      => $comment_id,
			) );
		}

		return $location;
	}

	public static function add_comment_types_in_dropdown( $comment_types ) {
		if ( ! isset( $comment_types['review'] ) ) {
			$comment_types['review'] = __( 'Review', 'directorist' );
		}
		return $comment_types;
	}

	public static function update_meta_boxes() {
		global $post;

		// Comments/Reviews.
		if ( isset( $post ) && get_post_type( $post ) === ATBDP_POST_TYPE ) {
			remove_meta_box( 'commentstatusdiv', ATBDP_POST_TYPE, 'normal' );
			remove_meta_box( 'commentsdiv', ATBDP_POST_TYPE, 'normal' );

			add_meta_box( 'commentsdiv', __( 'Reviews', 'directorist' ), 'post_comment_meta_box', ATBDP_POST_TYPE, 'normal' );
		}
	}

	public static function add_menu() {
		if ( ! directorist_is_review_enabled() ) {
			return;
		}

		$menu_slug    = 'edit.php?post_type='. ATBDP_POST_TYPE;
		$submenu_slug = 'edit-comments.php?post_type=' . ATBDP_POST_TYPE;

		add_submenu_page(
			$menu_slug,
			__( 'Reviews', 'directorist' ),
			__( 'Reviews', 'directorist' ),
			'edit_posts',
			$submenu_slug
			);

		// Make sure "Reviews" menu is active
		global $submenu, $pagenow;

		if ( $pagenow === 'edit-comments.php' && isset( $_GET['post_type'] ) && $_GET['post_type'] === ATBDP_POST_TYPE ) {
			if ( isset( $submenu[ $menu_slug ] ) ) {
				$_index = -1;

				foreach ( $submenu[ $menu_slug ] as $menu_key => $menu_item ) {
					if ( $menu_item[2] === $submenu_slug ) {
						$_index = $menu_key;
						break;
					}
				}

				if ( $_index !== -1 && isset( $submenu[ $menu_slug ][ $_index ] ) ) {
					if ( empty( $submenu[ $menu_slug ][ $_index ][4] ) ) {
						$submenu[ $menu_slug ][ $_index ][4] = 'current'; // @codingStandardsIgnoreLine.
					} else {
						$submenu[ $menu_slug ][ $_index ][4] .= ' current'; // @codingStandardsIgnoreLine.
					}
				}
			}
		}
	}

	public static function on_edit_comment( $comment_id, $comment_data ) {
		$comment = get_comment( $comment_id );

		if ( get_post_type( $comment->comment_post_ID ) !== ATBDP_POST_TYPE ) {
			return;
		}

		$nonce = ! empty( $_POST['directorist_comment_nonce'] ) ? sanitize_key( $_POST['directorist_comment_nonce'] ) : '';
		if ( ! wp_verify_nonce( $nonce, 'directorist_edit_comment' ) ) {
			return;
		}

		Comment::post_rating( $comment_id, $comment_data, $_POST );
		Comment::clear_transients( $comment->comment_post_ID );
	}

	public static function register( $comment ) {
		if ( get_post_type( $comment->comment_post_ID ) !== ATBDP_POST_TYPE ) {
			return;
		}

		if ( $comment->comment_type === 'review' ) {
			add_meta_box(
				'directorist-comment-mb',
				__( 'Review Data', 'directorist' ),
				array( __CLASS__, 'render_meta_fields' ),
				'comment',
				'normal',
				'high'
			);
		}
	}

	public static function render_meta_fields( $comment ) {
		wp_nonce_field( 'directorist_edit_comment', 'directorist_comment_nonce' );
		?>
		<table class="form-table">
			<tbody>
				<?php do_action( 'directorist/admin/review/meta_fields', $comment ); ?>
			</tbody>
		</table>
		<?php
	}

	public static function render_rating_meta_field( $comment ) {
		$listing       = Directorist_Single_Listing::instance( $comment->comment_post_ID );
		$section_data  = $listing->get_review_section_data();
		$builder       = Builder::get( $section_data['section_data'] );
		$rating  		= Comment_Meta::get_rating( $comment->comment_ID, 0 );

		if ( $builder->is_rating_type_single() ) : $r = floor( $rating ); ?>
		<tr>
			<th><?php esc_html_e( 'Rating', 'directorist' ); ?></th>
			<td>
				<select name="rating">
					<option value="0">No Rating</option>
					<option value="1" <?php selected( $r, 1 ); ?>>1</option>
					<option value="2" <?php selected( $r, 2 ); ?>>2</option>
					<option value="3" <?php selected( $r, 3 ); ?>>3</option>
					<option value="4" <?php selected( $r, 4 ); ?>>4</option>
					<option value="5" <?php selected( $r, 5 ); ?>>5</option>
				</select>
			</td>
		</tr>
		<?php endif;
	}
}

Metabox::init();
