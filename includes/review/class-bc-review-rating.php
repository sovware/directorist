<?php
/**
 * Review rating backward compatible class.
 *
 * @package Directorist\Review
 * @since 7.1.0
 */
namespace Directorist\Review;

defined( 'ABSPATH' ) || die();

class BC_Review_Rating {

	/**
	 * Review database class.
	 *
	 * @var BC_Review_DB
	 */
	public $db;

	public function __construct() {
		$this->db = new BC_Review_DB();
	}

	/**
	 * Print the an html list of rating
	 * @param int $star_number the number of start that should be colored
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
	 * It returns the average of stars of a post.
	 * @param int|object $post_id_or_object The ID of the post to get all ratings or the reviews/rating objects
	 * @return float|int
	 */
	public function get_average( $listing_id = 0 ) {
		return directorist_get_listing_rating( $listing_id );
	}
}

class BC_Review_DB {

	public function count( $args ) {
		if ( is_array( $args ) && ! empty( $args['post_id'] ) ) {
			$listing_id = absint( $args['post_id'] );
		} else {
			$listing_id = get_the_ID();
		}

		return directorist_get_listing_review_count( $listing_id );
	}

}
