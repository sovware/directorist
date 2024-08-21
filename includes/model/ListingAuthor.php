<?php
/**
 * @author wpWax
 */

namespace Directorist;

use Directorist\database\DB;

if ( ! defined( 'ABSPATH' ) ) exit;

class Directorist_Listing_Author {

	protected static $instance = null;

	public $id;
	public $all_listings;
	public $rating;
	public $total_review;
	public $columns;

	public $listing_types;
	public $current_listing_type;

	private function __construct() {
		$this->prepare_data();
	}

	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
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
			'posts_per_page' => -1,
		);

		return DB::get_listings_data( $args );
	}

	// extract_user_id
	public function extract_user_id( $user_id = '' ) {
		$user_id = urldecode($user_id); //decode the URL to remove encoded spaces, special characters
		$extracted_user_id = ( is_numeric( $user_id ) ) ? $user_id : get_current_user_id();

		if ( is_string( $user_id ) && ! empty( $user_id ) ) {
			$user = get_user_by( 'login', $user_id );

			if ( $user ) {
				$extracted_user_id = $user->ID;
			}
		}

		$extracted_user_id = intval( $extracted_user_id );

		return $extracted_user_id;
	}

	public function prepare_data() {
		$this->listing_types        = $this->get_listing_types();
		$this->current_listing_type = $this->get_current_listing_type();
		$this->columns              = (int) atbdp_calculate_column( get_directorist_option( 'all_listing_columns', 3 ) );

		$this->id = $this->extract_user_id( get_query_var( 'author_id' ) );

		if ( ! $this->id ) {
			return \ATBDP_Helper::guard( [ 'type' => '404' ] );
		}

		$this->all_listings = $this->get_all_posts();
		$this->get_rating();

	}

	public function get_listing_types() {
		return directorist_get_directories_for_template();
	}

	public function get_current_listing_type() {
		$listing_types      = $this->get_listing_types();
		$current = !empty($listing_types) ? array_key_first( $listing_types ) : '';

		if ( ! empty( $_GET['directory_type' ] ) ) {
			$current = sanitize_text_field( wp_unslash( $_GET['directory_type' ] ) );
		}
		else if (  get_query_var( 'directory-type' ) ) {
			$current = get_query_var( 'directory-type' );
		} else {
			foreach ( $listing_types as $id => $type ) {
				$is_default = get_term_meta( $id, '_default', true );
				if ( $is_default ) {
					$current = $id;
					break;
				}
			}
		}

		if( ! is_numeric( $current ) ) {
			$term = get_term_by( 'slug', $current, ATBDP_TYPE );
			$current = ( ! empty( $term ) ) ? $term->term_id : 0;
		}

		return (int) $current;
	}

	// Hooks ------------
	public function archive_type( $listings ) {
		if ( count( $listings->listing_types ) > 1 && directorist_is_multi_directory_enabled() ) {
			Helper::get_template( 'archive/directory-type-nav', array('listings' => $listings) );
		}
	}

	public function get_rating() {
		$user_listings = $this->all_listings;

		$reviews_count = 0;
		$reviews_sum   = 0;

		if ( ! empty( $user_listings->ids ) ) {
			// Prime caches to reduce future queries.
			if ( function_exists( '_prime_post_caches' ) ) {
				_prime_post_caches( $user_listings->ids );
			}

			foreach ( $user_listings->ids as $listings_id ) {
				$average = directorist_get_listing_rating( $listings_id );

				if ( $average > 0 ) {
					$reviews_sum    += $average;
					$reviews_count += 1;
				}
			}
		}

		$total_rating = 0;
		if ( $reviews_count > 0 ) {
			$total_rating = number_format( ( $reviews_sum / $reviews_count ), 1 );
		}

		$this->rating       = $total_rating;
		$this->total_review = $reviews_count;

		return $total_rating;
	}

	public function get_review_count() {
		$this->get_rating();
		return $this->total_review;
	}

	public function author_listings_query() {
		$category = ! empty( $_GET['category'] ) ? sanitize_text_field( wp_unslash( $_GET['category'] ) ) : '';
		$paged    = atbdp_get_paged_num();
		$paginate = get_directorist_option( 'paginate_author_listings', 1 );

		$args = array(
			'post_type'      => ATBDP_POST_TYPE,
			'post_status'    => 'publish',
			'author'         => $this->get_id(),
			'posts_per_page' => (int)get_directorist_option( 'all_listing_page_items', 6 ),
		);

		if ( ! empty( $paginate ) ) {
			$args['paged'] = $paged;
		} else {
			$args['no_found_rows'] = true;
		}

		if ( ! empty( $this->current_listing_type ) ) {
			$args['tax_query'] = array(
				array(
					'taxonomy'         => ATBDP_TYPE,
					'field'            => 'term_id',
					'terms'            => $this->current_listing_type ,
					'include_children' => true, /*@todo; Add option to include children or exclude it*/
				),
			);
		}

		if ( ! empty( $category ) ) {
			$category = array(
				array(
					'taxonomy'         => ATBDP_CATEGORY,
					'field'            => 'slug',
					'terms'            => ! empty( $category ) ? $category : '',
					'include_children' => true, /*@todo; Add option to include children or exclude it*/
				),
			);
		}

		if ( ! empty( $category ) ) {
			$args['tax_query'] = $category;
		}
		$meta_queries   = array();
		// TODO: Status has been migrated, remove related code.
		// $meta_queries['expired'] = array(
		// 	array(
		// 		'key'     => '_listing_status',
		// 		'value'   => 'expired',
		// 		'compare' => '!=',
		// 	),
		// );

		$meta_queries       = apply_filters( 'atbdp_author_listings_meta_queries', $meta_queries );
		$count_meta_queries = count( $meta_queries );
		if ( $count_meta_queries ) {
			$args['meta_query'] = ( $count_meta_queries > 1 ) ? array_merge( array( 'relation' => 'AND' ), $meta_queries ) : $meta_queries;
		}

		return $args;
	}

	public function avatar_html() {
		$html = '';
		$author_id = $this->id;
		$u_pro_pic = get_user_meta( $author_id, 'pro_pic', true );

		if ( !empty( $u_pro_pic ) ) {
			$html = wp_get_attachment_image( $u_pro_pic );
		}

		if ( !$html ) {
			$html = get_avatar( $author_id );
		}

		return $html;
	}

	public function member_since_text() {
		$author_id = $this->id;
		$user_registered = get_the_author_meta('user_registered', $author_id);
		$member_since_text = sprintf(__('Member since %s ago', 'directorist'), human_time_diff(strtotime($user_registered), current_time('timestamp')));
		return $member_since_text;
	}

	public function review_count_html() {
		$review_count = $this->total_review;
		$review_count_html = sprintf( _nx( '<span>%s</span> Review', '<span>%s</span> Reviews', $review_count, 'author review count', 'directorist' ), $review_count );
		return $review_count_html;
	}

	public function listing_count_html() {
		$listing_count = ! empty( $this->all_listings ) ? $this->all_listings->total : '';
		$listing_count_html = sprintf( _nx( '<span>%s</span> Listing', '<span>%s</span> Listings', $listing_count, 'author review count', 'directorist' ), $listing_count );
		return $listing_count_html;
	}

	public function review_enabled() {
		return get_directorist_option('enable_review', 1);
	}

	public function rating_count() {
		return $this->rating;
	}

	public function display_name() {
		$author_id = $this->id;
		return get_the_author_meta('display_name', $author_id);
	}

	public function header_template() {
		$author_id = $this->id;
		Helper::get_template( 'author/header', [ 'author' => $this ] );
	}

	public function about_template() {
		$author_id = $this->id;

		$bio = get_user_meta($author_id, 'description', true);
		$bio = trim( $bio );

		$display_email = get_directorist_option('display_author_email', 'public');

		if ( $display_email == 'public' ) {
			$email_endabled = true;
		}
		elseif ( $display_email == 'logged_in' && is_user_logged_in() ) {
			$email_endabled = true;
		}
		else {
			$email_endabled = false;
		}

		$args = array(
			'author'         => $this,
			'bio'            => nl2br( $bio ),
			'address'        => get_user_meta($author_id, 'address', true),
			'phone'          => get_user_meta($author_id, 'atbdp_phone', true),
			'email_endabled' => $email_endabled,
			'email'          => get_the_author_meta('user_email', $author_id),
			'website'        => get_the_author_meta('user_url', $author_id),
			'facebook'       => get_user_meta($author_id, 'atbdp_facebook', true),
			'twitter'        => get_user_meta($author_id, 'atbdp_twitter', true),
			'linkedin'       => get_user_meta($author_id, 'atbdp_linkedin', true),
			'youtube'        => get_user_meta($author_id, 'atbdp_youtube', true),
		);

		Helper::get_template( 'author/about', $args );
	}

	public function author_listings_template() {
		$args = array(
			'author'   => $this,
			'listings' => $this->get_listings(),
		);

		Helper::get_template( 'author/listings', $args );
	}

	public function get_listings() {
		$query    = $this->author_listings_query();
		$listings = new Directorist_Listings( NULL, NULL, $query, ['cache' => false] );
		return $listings;
	}

	public function cat_filter_enabled() {
		return get_directorist_option( 'author_cat_filter', 1 );
	}

	public function get_listing_categories() {
		return get_terms( ATBDP_CATEGORY , array( 'hide_empty' => 0 ) );
	}

	public function listing_pagination_enabled() {
		return get_directorist_option( 'paginate_author_listings', 1 );
	}

	public function render_shortcode_author_profile( $atts ) {

		$atts = shortcode_atts( array(
			'logged_in_user_only' => '',
			'redirect_page_url'   => '',
		), $atts );

		$logged_in_user_only = $atts['logged_in_user_only'];
		$redirect_page_url   = $atts['redirect_page_url'];

		if ( $redirect_page_url ) {
			$redirect = '<script>window.location="' . esc_url( $redirect_page_url ) . '"</script>';

			return $redirect;
		}

		if ( 'yes' === $logged_in_user_only && ! is_user_logged_in() ) {
			return ATBDP()->helper->guard( array('type' => 'auth') );
		}

		if ( ! is_user_logged_in() && ! $this->id ) {
			return ATBDP()->helper->guard( array('type' => '404') );
		}

		return Helper::get_template_contents( 'author-contents', array( 'author' => $this ) );
	}
}