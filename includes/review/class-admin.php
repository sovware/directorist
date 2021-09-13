<?php
/**
 * Admin functionality class.
 * Handles metabox, admin menu etc.
 *
 * @package Directorist\Review
 * @since 7.0.6
 */
namespace Directorist\Review;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Metabox {

	public static function init() {
		add_action( 'admin_menu', [ __CLASS__, 'add_menu' ] );
		add_action( 'add_meta_boxes_comment', [ __CLASS__, 'register' ] );
		add_action( 'edit_comment', [ __CLASS__, 'on_edit_comment' ], 10, 2 );
		add_action( 'add_meta_boxes', [ __CLASS__, 'rename_comment_metabox' ], 20 );
		add_filter( 'admin_comment_types_dropdown', [ __CLASS__, 'add_comment_types_in_dropdown' ] );
	}

	public static function add_comment_types_in_dropdown( $comment_types ) {
		if ( ! isset( $comment_types['review'] ) ) {
			$comment_types['review'] = __( 'Review', 'directorist' );
		}
		return $comment_types;
	}

	public static function rename_comment_metabox() {
		global $post;

		// Comments/Reviews.
		if ( isset( $post ) && ( 'publish' === $post->post_status || 'private' === $post->post_status ) && post_type_supports( ATBDP_POST_TYPE, 'comments' ) ) {
			remove_meta_box( 'commentsdiv', ATBDP_POST_TYPE, 'normal' );
			add_meta_box( 'commentsdiv', __( 'Reviews', 'directorist' ), 'post_comment_meta_box', ATBDP_POST_TYPE, 'normal' );
		}
	}

	public static function add_menu() {
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
						$submenu[ $menu_slug ][ $_index ][4] = 'current';
					} else {
						$submenu[ $menu_slug ][ $_index ][4] .= ' current';
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

		$nonce = ! empty( $_POST['directorist_comment_nonce'] ) ?  $_POST['directorist_comment_nonce'] : '';
		if ( ! wp_verify_nonce( $nonce, 'directorist_edit_comment' ) ) {
			return;
		}

		Comment::post_rating( $comment_id, $comment_data );
		Comment::clear_transients( $comment->comment_post_ID );
	}

	public static function register( $comment ) {
		if ( get_post_type( $comment->comment_post_ID ) !== ATBDP_POST_TYPE ) {
			return;
		}

		add_meta_box(
			'directorist-comment-mb',
			( $comment->comment_type === 'review' ? __( 'Review extra', 'directorist' ) : __( 'Comment extra', 'directorist' ) ),
			array( __CLASS__, 'render' ),
			'comment',
			'normal',
			'high'
		);
	}

	public static function render( $comment ) {
		$comment_id  = $comment->comment_ID;
		$builder     = Builder::get( $comment->comment_post_ID );
		$helpful     = Comment_Meta::get_helpful( $comment_id, 0 );
		$unhelpful   = Comment_Meta::get_unhelpful( $comment_id, 0 );
		$reported    = Comment_Meta::get_reported( $comment_id, 0 );
		$rating      = Comment_Meta::get_rating( $comment_id );
		$attachments = Comment_Meta::get_attachments( $comment_id );
		?>
		<style>
		.comment-attachments {
			display: flex;
		}
		.comment-attachments a {
			display: block;
			max-width: 150px;
			flex: 0 0 150px;
			margin: 5px;
		}
		.comment-attachments img {
			height: 150px;
			width: 100%;
			object-fit: cover;
			padding: 3px;
			border: 1px solid #eee;
			border-radius: 3px;
			display: block;
		}
		</style>

		<?php wp_nonce_field( 'directorist_edit_comment', 'directorist_comment_nonce' ); ?>

		<table class="form-table">
			<tbody>
				<tr>
					<th><?php esc_html_e( 'Helpful', 'directorist' ); ?></th>
					<td><?php echo $helpful; ?></td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Unhelpful', 'directorist' ); ?></th>
					<td><?php echo $unhelpful; ?></td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Reported', 'directorist' ); ?></th>
					<td><?php echo $reported; ?></td>
				</tr>
				<?php if ( $builder->is_attachments_enabled() && ! empty( $attachments ) && is_array( $attachments ) ) : ?>
					<tr>
						<th><?php esc_html_e( 'Images', 'directorist' ); ?></th>
						<td>
							<div class="comment-attachments">
								<?php foreach ( $attachments as $attachment ) {
									printf( '<a href="%1$s" target="_blank"><img src="%1$s" alt=""></a>', self::get_image_url( $attachment ) );
								} ?>
							</div>
						</td>
					</tr>
				<?php endif; ?>

				<tr><td colspan="2"><hr></td></tr>
				<?php if ( $builder->is_rating_type_criteria() && count( $builder->get_rating_criteria() ) > 0 ) : ?>
					<tr>
						<th><?php esc_html_e( 'Avg Rating', 'directorist' ); ?></th>
						<td><?php echo $rating; ?></td>
					</tr>
					<?php
					$criteria_rating = Comment::get_criteria_rating( $comment_id );
					$criteria        = $builder->get_rating_criteria();

					foreach ( $criteria as $k => $v ) :
						$r = isset( $criteria_rating[ $k ] ) ? $criteria_rating[ $k ] : 0;
						?>
						<tr>
							<th><?php echo $v; ?></th>
							<td>
								<select name="rating[<?php echo $k; ?>]">
									<option value="0">No Rating</option>
									<option value="1" <?php selected( $r, 1 ); ?>>1</option>
									<option value="2" <?php selected( $r, 2 ); ?>>2</option>
									<option value="3" <?php selected( $r, 3 ); ?>>3</option>
									<option value="4" <?php selected( $r, 4 ); ?>>4</option>
									<option value="5" <?php selected( $r, 5 ); ?>>5</option>
								</select>
							</td>
						</tr>
					<?php
					endforeach;
				endif ?>

				<?php if ( $builder->is_rating_type_single() ) : $r = floor( $rating ); ?>
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
				<?php endif; ?>
			</tbody>
		</table>
		<?php
	}

	protected static function get_image_url( $attachment ) {
		$dir = wp_get_upload_dir();
		return trailingslashit( $dir['baseurl'] ) . $attachment;
	}
}

Metabox::init();
