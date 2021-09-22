<?php
/**
 * Advance review comment class
 *
 * @package Directorist\Review
 * @since 7.0.6
 */
namespace Directorist\Review;

defined( 'ABSPATH' ) || die();

use Exception;
use Directorist\Review\Listing_Review_Meta as Review_Meta;

class Comment {

	public static function init() {
		// Rating posts.
		add_filter( 'comments_open', [ __CLASS__, 'comments_open' ], 10, 2 );
		add_filter( 'preprocess_comment', [ __CLASS__, 'validate_comment_data' ], 0 );
		add_action( 'comment_post', [ __CLASS__, 'on_comment_post' ], 10, 3 );

		// Support avatars for `review` comment type.
		add_filter( 'get_avatar_comment_types', [ __CLASS__, 'set_avater_comment_types' ] );

		// Clear transients.
		add_action( 'wp_update_comment_count', [ __CLASS__, 'clear_transients'] );

		// Set comment type.
		add_filter( 'preprocess_comment', [ __CLASS__, 'preprocess_comment_data' ], 1 );

		// Set comment approval
		add_filter( 'pre_comment_approved', [ __CLASS__, 'set_comment_status' ], 10, 2 );

		// Count comments.
		add_filter( 'wp_count_comments', [ __CLASS__, 'wp_count_comments' ], 10, 2 );

		// Delete comments count cache whenever there is a new comment or a comment status changes.
		add_action( 'wp_insert_comment', [ __CLASS__, 'delete_comments_count_cache' ] );
		add_action( 'wp_set_comment_status', [ __CLASS__, 'delete_comments_count_cache' ] );
	}

	/**
	 * See if comments are open.
	 *
	 * @param  bool $open    Whether the current post is open for comments.
	 * @param  int  $post_id Post ID.
	 * @return bool
	 */
	public static function comments_open( $open, $post_id ) {
		if ( ATBDP_POST_TYPE === get_post_type( $post_id ) ) {
			$open = true;

			if ( ! post_type_supports( ATBDP_POST_TYPE, 'comments' ) ) {
				$open = false;
			}
		}

		return apply_filters( 'directorist/review/comments_open', $open, $post_id );
	}

	/**
	 * Validate the review ratings.
	 *
	 * @param  array $comment_data Comment data.
	 * @return array
	 */
	public static function validate_comment_data( $comment_data ) {
		if ( is_admin() || ! isset( $_POST['comment_post_ID'] ) || ATBDP_POST_TYPE !== get_post_type( absint( $_POST['comment_post_ID'] ) ) ) {
			return $comment_data;
		}

		try {
			// Exit when review is disabled.
			if ( ! directorist_is_review_enabled() ) {
				throw new Exception( __( '<strong>Error</strong>: Review is disabled.', 'directorist' ), 400 );
			}

			// Exit when guest review is disabled.
			if ( is_user_logged_in() || ( ! is_user_logged_in() && ! directorist_is_guest_review_enabled() ) ) {
				throw new Exception( __( '<strong>Error</strong>: You must login to share review.', 'directorist' ), 401 );
			}

			$builder = Builder::get( absint( $_POST['comment_post_ID'] ) );
			$errors  = array();

			if ( isset( $_POST['comment_parent'], $_POST['rating'], $comment_data['comment_type'] ) &&
				$comment_data['comment_parent'] === 0 && self::is_default_comment_type( $comment_data['comment_type'] ) ) {

				$rating_is_missing = ( $builder->is_rating_type_single() && empty( $_POST['rating'] ) );

				// Validate review is shared or not
				if ( $rating_is_missing ) {
					$errors[] = __( '<strong>Error</strong>: Please share review rating.', 'directorist' );
				}

				// Validate owner is sharing review or not
				$post_author_id = (int) get_post_field( 'post_author', absint( $_POST['comment_post_ID'] ) );

				if ( ! $rating_is_missing && ! directorist_is_owner_review_enabled() && $post_author_id === $comment_data['user_ID'] ) {
					$errors[] = __( '<strong>Error</strong>: You are not allowed to share review on your own listing.', 'directorist' );
				}

				// Validate if sharing multiple reviews
				if ( ! $rating_is_missing && self::review_exists_by( $comment_data['user_ID'], absint( $_POST['comment_post_ID'] ) ) ) {
					$errors[] = __( '<strong>Error</strong>: You already shared a review.', 'directorist' );
				}

				if ( count( $errors ) > 0 ) {
					throw new Exception( implode( '<br>', $errors ), 400 );
				}
			}

			do_action( 'directorist/review/validate_comment_data', $comment_data );
		} catch( Exception $e ) {
			wp_die( $e->getMessage(), $e->getCode() );
			exit;
		}

		return $comment_data;
	}

	/**
	 * Delete comments count cache whenever there is
	 * new comment or the status of a comment changes. Cache
	 * will be regenerated next time Comment::wp_count_comments()
	 * is called.
	 */
	public static function delete_comments_count_cache() {
		delete_transient( 'directorist_count_comments' );
	}

	/**
	 * Remove order notes and webhook delivery logs from wp_count_comments().
	 *
	 * @param  object $stats   Comment stats.
	 * @param  int    $post_id Post ID.
	 * @return object
	 */
	public static function wp_count_comments( $stats, $post_id ) {
		global $wpdb;

		if ( 0 === $post_id ) {
			$stats = get_transient( 'directorist_count_comments' );

			if ( ! $stats ) {
				$stats = array(
					'total_comments' => 0,
					'all'            => 0,
				);

				$count = $wpdb->get_results(
					"
					SELECT comment_approved, COUNT(*) AS num_comments
					FROM {$wpdb->comments}
					WHERE comment_type NOT IN ('action_log')
					GROUP BY comment_approved
					",
					ARRAY_A
				);

				$approved = array(
					'0'            => 'moderated',
					'1'            => 'approved',
					'spam'         => 'spam',
					'trash'        => 'trash',
					'post-trashed' => 'post-trashed',
				);

				foreach ( (array) $count as $row ) {
					// Don't count post-trashed toward totals.
					if ( ! in_array( $row['comment_approved'], array( 'post-trashed', 'trash', 'spam' ), true ) ) {
						$stats['all']            += $row['num_comments'];
						$stats['total_comments'] += $row['num_comments'];
					} elseif ( ! in_array( $row['comment_approved'], array( 'post-trashed', 'trash' ), true ) ) {
						$stats['total_comments'] += $row['num_comments'];
					}
					if ( isset( $approved[ $row['comment_approved'] ] ) ) {
						$stats[ $approved[ $row['comment_approved'] ] ] = $row['num_comments'];
					}
				}

				foreach ( $approved as $key ) {
					if ( empty( $stats[ $key ] ) ) {
						$stats[ $key ] = 0;
					}
				}

				$stats = (object) $stats;

				set_transient( 'directorist_count_comments', $stats );
			}
		}

		return $stats;
	}

	/**
	 * Make sure WP displays avatars for comments with the `review` type.
	 *
	 * @param  array $comment_types Comment types.
	 * @return array
	 */
	public static function set_avater_comment_types( $comment_types ) {
		return array_merge( $comment_types, array( 'review' ) );
	}

	public static function preprocess_comment_data( $comment_data ) {
		if ( is_admin() || ! isset( $_POST['comment_post_ID'] ) || ATBDP_POST_TYPE !== get_post_type( absint( $_POST['comment_post_ID'] ) ) ) {
			return $comment_data;
		}

		$builder = Builder::get( absint( $_POST['comment_post_ID'] ) );

		if ( isset( $_POST['comment_parent'], $_POST['rating'], $comment_data['comment_type'] ) &&
			$comment_data['comment_parent'] === 0 && self::is_default_comment_type( $comment_data['comment_type'] ) &&
			( $builder->is_rating_type_single() && ! empty( $_POST['rating'] ) ) ) {
			$comment_data['comment_type'] = 'review';
		}

		$comment_data = apply_filters( 'directorist/review/preprocess_comment_data', $comment_data );

		return $comment_data;
	}

	/**
	 * Set comment status.
	 *
	 * Apporoved pending comment immediately when "Approve Immediately?" is enabled,
	 * ignore trash and spam status.
	 *
	 * @param mixed $approved
	 * @param array $comment_data
	 *
	 * @return mixed
	 */
	public static function set_comment_status( $approved, $comment_data ) {
		if ( is_admin() || $comment_data['comment_type'] !== 'review' || ATBDP_POST_TYPE !== get_post_type( $comment_data['comment_post_ID'] ) ) {
			return $approved;
		}

		if ( directorist_is_immediate_review_approve_enabled() && $approved === 0 ) {
			$approved = 1; // set to approved
		}

		if ( ! directorist_is_immediate_review_approve_enabled() && $approved === 1 ) {
			$approved = 0; // set to pending
		}

		return $approved;
	}

	/**
	 * Determines if a comment is of the default type.
	 *
	 * Prior to WordPress 5.5, '' was the default comment type.
	 * As of 5.5, the default type is 'comment'.
	 *
	 * @param string $comment_type Comment type.
	 * @return bool
	 */
	private static function is_default_comment_type( $comment_type ) {
		return ( '' === $comment_type || 'comment' === $comment_type );
	}

	public static function on_comment_post( $comment_id, $comment_approved, $comment_data ) {
		$post_id = isset( $_POST['comment_post_ID'] ) ? absint( $_POST['comment_post_ID'] ) : 0; // WPCS: input var ok, CSRF ok.

		if ( $post_id && ATBDP_POST_TYPE === get_post_type( $post_id ) ) {
			do_action( 'directorist/review/save_comment' );

			self::post_rating( $comment_id, $comment_data );
			self::clear_transients( $post_id );
		}
	}

	/**
	 * Ensure listing average rating and review count is kept up to date.
	 *
	 * @param int $post_id Post ID.
	 */
	public static function clear_transients( $post_id ) {
		if ( ATBDP_POST_TYPE === get_post_type( $post_id ) ) {
			// Make sure to maintain the sequence. Update review count before updating the rating
			self::maybe_clear_transients( $post_id );
		}
	}

	public static function maybe_clear_transients( $listing_id ) {
		Review_Meta::update_rating_counts( $listing_id, self::get_rating_counts_for_listing( $listing_id ) );
		Review_Meta::update_review_count( $listing_id, self::get_review_count_for_listing( $listing_id ) );
		Review_Meta::update_rating( $listing_id, self::get_average_rating_for_listing( $listing_id ) );

		do_action( 'directorist/review/maybe_clear_transients', $listing_id );
	}

	/**
	 * Check if user already shared a review.
	 *
	 * @param int $user_id
	 * @param int $post_id.
	 * @return bool
	 */
	public static function review_exists_by( $user_id, $post_id ) {
		global $wpdb;

		$has_review = $wpdb->get_var(
			$wpdb->prepare(
				"
			SELECT count(comment_ID) FROM $wpdb->comments
			WHERE comment_post_ID = %d
			AND ( comment_approved = '1' OR comment_approved = '0' )
			AND comment_type = 'review'
			AND user_id = %d
				",
				$post_id,
				$user_id
			)
		);

		return (bool) $has_review;
	}

	/**
	 * Get listing review count for a listing (not replies). Please note this is not cached.
	 *
	 * @param int $post_id.
	 * @return int
	 */
	public static function get_review_count_for_listing( $post_id ) {
		$counts = self::get_review_counts_for_listing_ids( array( $post_id ) );

		return $counts[ $post_id ];
	}

	/**
	 * Get listing rating count for a directory listing. Please note this is not cached.
	 *
	 * @param $post_id.
	 * @return int[]
	 */
	public static function get_rating_counts_for_listing( $post_id ) {
		global $wpdb;

		$counts     = array();
		$raw_counts = $wpdb->get_results(
			$wpdb->prepare(
				"
			SELECT meta_value, COUNT( * ) as meta_value_count FROM $wpdb->commentmeta
			LEFT JOIN $wpdb->comments ON $wpdb->commentmeta.comment_id = $wpdb->comments.comment_ID
			WHERE meta_key = 'rating'
			AND comment_post_ID = %d
			AND comment_approved = '1'
			AND meta_value > 0
			GROUP BY meta_value
				",
				$post_id
			)
		);

		foreach ( $raw_counts as $count ) {
			$counts[ $count->meta_value ] = absint( $count->meta_value_count ); // WPCS: slow query ok.
		}

		return $counts;
	}

	/**
	 * Get listing rating for a listing. Please note this is not cached.
	 *
	 * @param $post_id.
	 * @return float
	 */
	public static function get_average_rating_for_listing( $post_id ) {
		global $wpdb;

		$count = Review_Meta::get_review_count( $post_id );

		if ( $count ) {
			$ratings = $wpdb->get_var(
				$wpdb->prepare(
					"
				SELECT SUM(meta_value) FROM $wpdb->commentmeta
				LEFT JOIN $wpdb->comments ON $wpdb->commentmeta.comment_id = $wpdb->comments.comment_ID
				WHERE meta_key = 'rating'
				AND comment_post_ID = %d
				AND comment_approved = '1'
				AND meta_value > 0
					",
					$post_id
				)
			);

			$average = number_format( $ratings / $count, 2, '.', '' );
		} else {
			$average = 0;
		}

		return $average;
	}

	/**
	 * Utility function for getting review counts for multiple listings in one query. This is not cached.
	 *
	 * @param array $listing_ids Array of listing IDs.
	 *
	 * @return array
	 */
	public static function get_review_counts_for_listing_ids( $listing_ids ) {
		global $wpdb;

		if ( empty( $listing_ids ) ) {
			return array();
		}

		$listing_id_string_placeholder = substr( str_repeat( ',%s', count( $listing_ids ) ), 1 );

		$review_counts = $wpdb->get_results(
			// phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Ignored for allowing interpolation in IN query.
			$wpdb->prepare(
				"
					SELECT comment_post_ID as listing_id, COUNT( comment_post_ID ) as review_count
					FROM $wpdb->comments
					WHERE
						comment_parent = 0
						AND comment_post_ID IN ( $listing_id_string_placeholder )
						AND comment_approved = '1'
						AND comment_type = 'review'
					GROUP BY listing_id
				",
				$listing_ids
			),
			// phpcs:enable WordPress.DB.PreparedSQL.InterpolatedNotPrepared.
			ARRAY_A
		);

		// Convert to key value pairs.
		$counts = array_replace( array_fill_keys( $listing_ids, 0 ), array_column( $review_counts, 'review_count', 'listing_id' ) );

		return $counts;
	}

	public static function post_rating( $comment_id, $comment_data ) {
		if ( $comment_data['comment_type'] !== 'review' || empty( $_POST['rating'] ) ) {
			return;
		}

		$builder = Builder::get( $comment_data['comment_post_ID'] );
		$rating  = 0;

		if ( is_array( $_POST['rating'] ) && $builder->is_rating_type_single() ) {
			$rating = current( $_POST['rating'] );

			// Base max rating is "5" and min is "0", make sure given rating is not out of the range
			$rating = max( 0, min( 5, intval( $rating ) ) );
			$rating = number_format( $rating, 2, '.', '' );
		} else {
			// Base max rating is "5" and min is "0", make sure given rating is not out of the range
			$rating = max( 0, min( 5, intval( $_POST['rating'] ) ) );
			$rating = number_format( $rating, 2, '.', '' );
		}

		if ( $rating ) {
			Comment_Meta::set_rating( $comment_id, $rating );
		} else {
			delete_comment_meta( $comment_id, 'rating' );
		}
	}

	public static function get_rating( $comment_id ) {
		return (float) Comment_Meta::get_rating( $comment_id, 0 );
	}
}

Comment::init();
