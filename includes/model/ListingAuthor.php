<?php
/**
 * @author AazzTech
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Directorist_Listing_Author {

	protected static $instance = null;

	public $id;

	private function __construct() {
		$id = ! empty( $_GET['author_id'] ) ? $_GET['author_id'] : get_current_user_id();
		$this->id = intval( $id );
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

		return get_posts( $args );
	}

	public function get_rating() {

		$current_user_posts = $this->get_all_posts();

		$review_in_post = 0;
		$all_reviews    = 0;

		foreach ( $current_user_posts as $post ) {
			$average = ATBDP()->review->get_average( $post->ID );
			if ( ! empty( $average ) ) {
				$averagee = array( $average );
				foreach ( $averagee as $key ) {
					$all_reviews += $key;
				}
				$review_in_post++;
			}
		}

		$author_rating = ( ! empty( $all_reviews ) && ! empty( $review_in_post ) ) ? ( $all_reviews / $review_in_post ) : 0;
		$author_rating = substr( $author_rating, '0', '3' );

		return $author_rating;
	}

	public function get_review_count() {

		$current_user_posts = $this->get_all_posts();

		$review_in_post = 0;

		foreach ( $current_user_posts as $post ) {
			$average = ATBDP()->review->get_average( $post->ID );
			if ( ! empty( $average ) ) {
				$review_in_post++;
			}
		}

		return $review_in_post;
	}

	public function get_total_listing_number() {
		$count = count( $this->get_all_posts() );

		return apply_filters( 'atbdp_author_listing_count', $count );
	}

	private function enqueue_scripts() {
		wp_enqueue_script( 'adminmainassets' );
		wp_enqueue_script( 'atbdp-search-listing', ATBDP_PUBLIC_ASSETS . 'js/search-form-listing.js' );
		wp_localize_script( 'atbdp-search-listing', 'atbdp_search', array(
			'ajaxnonce'       => wp_create_nonce( 'bdas_ajax_nonce' ),
			'ajax_url'        => admin_url( 'admin-ajax.php' ),
			'added_favourite' => __( 'Added to favorite', 'directorist' ),
			'please_login'    => __( 'Please login first', 'directorist' ),
		) );
	}

	public function author_listings_query() {
		$category = ! empty( $_GET['category'] ) ? $_GET['category'] : '';
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
		$meta_queries[] = array(
			'relation' => 'OR',
			array(
				'key'     => '_expiry_date',
				'value'   => current_time( 'mysql' ),
                'compare' => '>', // eg. expire date 6 <= current date 7 will return the post
                'type'    => 'DATETIME',
            ),
			array(
				'key'   => '_never_expire',
				'value' => 1,
			),
		);

		$meta_queries       = apply_filters( 'atbdp_author_listings_meta_queries', $meta_queries );
		$count_meta_queries = count( $meta_queries );
		if ( $count_meta_queries ) {
			$args['meta_query'] = ( $count_meta_queries > 1 ) ? array_merge( array( 'relation' => 'AND' ), $meta_queries ) : $meta_queries;
		}

		return new WP_Query( $args );
	}

	public function header_template() {
		$author_id = $this->id;

		$u_pro_pic = get_user_meta($author_id, 'pro_pic', true);
		$avatar_img = empty($u_pro_pic) ? get_avatar($author_id, apply_filters('atbdp_avatar_size', 96)) : sprintf('<img src="%s" alt="%s" >', esc_url($u_pro_pic[0]), __('Author Image', 'directorist'));

		$user_registered = get_the_author_meta('user_registered', $author_id);
		$member_since_text = sprintf(__('Member since %s ago', 'directorist'), human_time_diff(strtotime($user_registered), current_time('timestamp')));

		$review_count = $this->get_review_count();
		$review_count_html = sprintf( _nx( '<span>%s</span>Review', '<span>%s</span>Reviews', $review_count, 'author review count', 'directorist' ), $review_count );

		$listing_count = $this->get_total_listing_number();
		$listing_count_html = sprintf( _nx( '<span>%s</span>Listing', '<span>%s</span>Listings', $listing_count, 'author review count', 'directorist' ), $listing_count );

		$args = array(
			'author'             => $this,
			'avatar_img'         => $avatar_img,
			'author_name'        => get_the_author_meta('display_name', $author_id),
			'member_since_text'  => $member_since_text,
			'enable_review'      => get_directorist_option('enable_review', 1),
			'rating_count'       => $this->get_rating(),
			'review_count_html'  => $review_count_html,
			'listing_count_html' => $listing_count_html,
		);

		atbdp_get_shortcode_template( 'author/author-header', $args );
	}

	public function about_template() {
		$author_id = $this->id;

		$bio = get_user_meta($author_id, 'description', true);
		$bio = trim( $bio );

		$display_email = get_directorist_option('display_author_email', 'public');

		if ( $display_email == 'public' ) {
			$email_endabled = true;
		}
		elseif ( $display_email == 'logged_in' && atbdp_logged_in_user() ) {
			$email_endabled = true;
		}
		else {
			$email_endabled = false;
		}

		$args = array(
			'author'         => $this,
			'bio'            => $bio,
			'address'        => get_user_meta($author_id, 'address', true),
			'phone'          => get_user_meta($author_id, 'atbdp_phone', true),
			'email_endabled' => $email_endabled,
			'email'          => get_the_author_meta('user_email', $author_id),
			'website'        => get_the_author_meta('user_url', $author_id),
			'facebook'       => get_user_meta($author_id, 'atbdp_facebook', true),
			'twitter'        => get_user_meta($author_id, 'atbdp_twitter', true),
			'linkedIn'       => get_user_meta($author_id, 'atbdp_linkedin', true),
			'youtube'        => get_user_meta($author_id, 'youtube', true),
		);

		atbdp_get_shortcode_template( 'author/author-about', $args );
	}

    // @todo @kowsar do_action('atbdp_author_listings_html', $all_listings) in "Post Your Need" ext
	public function author_listings_template() {
		$query    = $this->author_listings_query();
		$listings = new Directorist_Listings(NULL,NULL,$query);

		$args = array(
			'author'               => $this,
			'display_title'        => apply_filters('atbdp_author_listings_header_title', true),
			'display_cat_filter'   => get_directorist_option('author_cat_filter',1),
			'display_listings'     => apply_filters('atbdp_author_listings', true),
			'categories'           => get_terms(ATBDP_CATEGORY, array('hide_empty' => 0)),
			'listings'             => $listings,
			'display_pagination'   => get_directorist_option('paginate_author_listings', 1),
		);

		atbdp_get_shortcode_template( 'author/author-listings', $args );
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

		$this->enqueue_scripts();

		if ( 'yes' === $logged_in_user_only && ! atbdp_logged_in_user() ) {
			return ATBDP()->helper->guard( array('type' => 'auth') );
		}

		$container_fluid = apply_filters( 'atbdp_public_profile_container_fluid', 'container-fluid' );

		return atbdp_return_shortcode_template( 'author/author-profile', compact( 'container_fluid' ) );
	}
}