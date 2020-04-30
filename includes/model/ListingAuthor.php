<?php
/**
 * @author AazzTech
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class Directorist_Listing_Author {

	public $id;

	public function __construct( $id = '' ) {
		if ( !$id ) {
			$id = get_current_user_id();
		}
		$this->id = $id;
	}

	public function get_id() {
		return $this->id;
	}

	private function get_all_posts() {
		$args = array(
			'post_type'      => ATBDP_POST_TYPE,
			'post_status'    => 'publish',
			'author'         => $this->id,
			'orderby'        => 'post_date',
			'order'          => 'ASC',
			'posts_per_page' => -1
		);

		return get_posts($args);		
	}

	public function get_rating() {

		$current_user_posts = $this->get_all_posts();		

		$review_in_post = 0;
		$all_reviews = 0;

		foreach ($current_user_posts as $post) {
			$average = ATBDP()->review->get_average($post->ID);
			if (!empty($average)) {
				$averagee = array($average);
				foreach ($averagee as $key) {
					$all_reviews += $key;
				}
				$review_in_post++;
			}
		}

		$author_rating = (!empty($all_reviews) && !empty($review_in_post)) ? ($all_reviews / $review_in_post) : 0;
		$author_rating = substr($author_rating, '0', '3');
		return $author_rating;
	}

	public function get_review_count() {

		$current_user_posts = $this->get_all_posts();

		$review_in_post = 0;

		foreach ($current_user_posts as $post) {
			$average = ATBDP()->review->get_average($post->ID);
			if (!empty($average)) {
				$review_in_post++;
			}
		}

		return $review_in_post;
	}

	public function get_total_listing_number() {
		$count = count( $this->get_all_posts() );
		return apply_filters('atbdp_author_listing_count', $count);
	}
}