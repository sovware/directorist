<?php
/**
 * Review rating backward compatible class.
 *
 * @since 7.1.0
 */

defined( 'ABSPATH' ) || die();

/**
 * Review rating class for backward compatibility.
 * Use update review rating system instead.
 *
 * @deprecated 7.1.0
 * @see directorist_get_listing_rating
 */
class ATBDP_Review_Rating {

	/**
	 * Review rating db class.
	 *
	 * @var ATBDP_Review_Rating_DB
	 */
	public $db;

	public function __construct() {
		$this->db = new ATBDP_Review_Rating_DB();
	}

	/**
	 * Get rating html.
	 *
	 * @param int $star_number
	 * @deprecated 7.1.0
	 *
	 * @return string
	 */
	public function print_static_rating( $star_number = 1 ) {
		$v ='<ul>';
			for ( $i=1; $i<=5; $i++ ) {
				$v .= ( $i <= $star_number )
					? "<li><span class='directorist-rate-active'></span></li>"
					: "<li><span class='directorist-rate-disable'></span></li>";
			}
		$v .= '</ul>';

		return $v;
	}

	/**
	 * Get listing rating.
	 *
	 * @param int $listing_id
	 * @deprecated 7.1.0
	 * @see directorist_get_listing_rating()
	 *
	 * @return float|int
	 */
	public function get_average( $listing_id = 0 ) {
		return directorist_get_listing_rating( $listing_id );
	}
}

/**
 * Review rating database class for backward compatibility.
 * Use updated review rating system instead.
 *
 * @deprecated 7.1.0
 */
class ATBDP_Review_Rating_DB {

	/**
	 * Get listing review count.
	 *
	 * @param array $args
	 * @deprecated 7.1.0
	 * @see directorist_get_listing_review_count()
	 *
	 * @return int
	 */
	public function count( $args ) {
		if ( is_array( $args ) && ! empty( $args['post_id'] ) ) {
			$listing_id = absint( $args['post_id'] );
		} else {
			$listing_id = get_the_ID();
		}

		return directorist_get_listing_review_count( $listing_id );
	}

	/**
	 * Retrieves all reviews from the database based on the field given in the argument.
	 *
	 * @access public
	 * @since  2.3
	 * @deprecated 7.1.0 Use updated review system instead.
	 * @param  string $field  id or post_id, by_user_id, email
	 * @param  mixed  $value  The Review ID or post_id to search
	 * @param  int    $limit  Optional. Limit the number of the post we get from the database.
	 *
	 * @return mixed Upon success, an object of the review. Upon failure, NULL
	 */
	public function get_ratings_by( $field = 'id', $value = 0, $limit = PHP_INT_MAX ) {
		if ( empty( $field ) || empty( $value ) ) {
			return NULL;
		}

		if ( ! in_array( $field, array( 'id', 'post_id', 'by_user_id', 'email' ), true ) ) {
			return false;
		}

		if ( $field === 'email' && is_email( $value ) ) {
			$value = sanitize_email( $value );
		} else {
			$value = absint( $value );
		}

		if ( empty( $value ) ) {
			return false;
		}

		$args = array(
			'post_type' => ATBDP_POST_TYPE,
			'type'      => 'review',
			'fields'    => 'ids',
		);

		if ( $field === 'email' ) {
			$args['author_email'] = $value;
		}

		if ( $field === 'id' ) {
			$args['comment__in'] = array( $value );
		}

		if ( $field === 'by_user_id' ) {
			$args['user_id'] = $value;
		}

		if ( $field === 'post_id' ) {
			$args['post_id'] = $value;
		}

		$comments_query = new WP_Comment_Query( $args );
		$comments = $comments_query->comments;

		if ( empty( $comments ) ) {
			return false;
		}

		$data = array();
		foreach ( $comments as $comment_id ) {
			$data[] = (object) array(
				'rating' => (int) \Directorist\Review\Comment_Meta::get_rating( $comment_id )
			);
		}
		unset( $comments, $comments_query );

		return $data;
	}
}
