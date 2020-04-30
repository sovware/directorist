<?php
/**
 * @author AazzTech
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class Directorist_Listing_Author {

	public $id;

	public function __construct( $id = '' ) {
		if ( !$id ) {
            $id = !empty($_GET['author_id']) ? $_GET['author_id'] : get_current_user_id();
            $id = intval( $id );
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

    private function enqueue_scripts() {
        wp_enqueue_script('adminmainassets');
        wp_enqueue_script('atbdp-search-listing', ATBDP_PUBLIC_ASSETS . 'js/search-form-listing.js');
        wp_localize_script('atbdp-search-listing', 'atbdp_search', array(
            'ajaxnonce'        => wp_create_nonce('bdas_ajax_nonce'),
            'ajax_url'         => admin_url('admin-ajax.php'),
            'added_favourite'  => __('Added to favorite', 'directorist'),
            'please_login'     => __('Please login first', 'directorist')
        ));
    }

	public function render_shortcode($atts) {

        $atts = shortcode_atts(array(
            'logged_in_user_only' => '',
            'redirect_page_url'   => ''
        ), $atts);

        $logged_in_user_only = $atts['logged_in_user_only'];
        $redirect_page_url   = $atts['redirect_page_url'];

        if ($redirect_page_url) {
            $redirect = '<script>window.location="' . esc_url($redirect_page_url) . '"</script>';
            return $redirect;
        }
        
        $this->enqueue_scripts();

        if ( 'yes' === $logged_in_user_only && ! atbdp_logged_in_user() ) {
            return ATBDP()->helper->guard( ['type' => 'auth'] );
        }

        $author_id = $this->get_id();

        //for pagination
        $category = !empty($_GET['category']) ? $_GET['category'] : '';
        $paged = atbdp_get_paged_num();
        $paginate = get_directorist_option('paginate_author_listings', 1);
        
        $args = array(
            'post_type' => ATBDP_POST_TYPE,
            'post_status' => 'publish',
            'author' => $author_id,
            'posts_per_page' => (int)get_directorist_option('all_listing_page_items', 6)
        );
        if (!empty($paginate)) {
            $args['paged'] = $paged;
        } else {
            $args['no_found_rows'] = true;
        }
        if (!empty($category)) {
            $category = array(
                array(
                    'taxonomy' => ATBDP_CATEGORY,
                    'field' => 'slug',
                    'terms' => !empty($category) ? $category : '',
                    'include_children' => true, /*@todo; Add option to include children or exclude it*/
                )

            );
        }
        if (!empty($category)) {
            $args['tax_query'] = $category;
        }
        $meta_queries = array();
        $meta_queries[] = array(
            'relation' => 'OR',
            array(
                'key' => '_expiry_date',
                'value' => current_time('mysql'),
                'compare' => '>', // eg. expire date 6 <= current date 7 will return the post
                'type' => 'DATETIME'
            ),
            array(
                'key' => '_never_expire',
                'value' => 1,
            )
        );

        $meta_queries = apply_filters('atbdp_author_listings_meta_queries', $meta_queries);
        $count_meta_queries = count($meta_queries);
        if ($count_meta_queries) {
            $args['meta_query'] = ($count_meta_queries > 1) ? array_merge(array('relation' => 'AND'), $meta_queries) : $meta_queries;
        }

        $all_listings = new WP_Query($args);
        $data_for_template = compact('all_listings', 'paged', 'paginate', 'author_id');
        ob_start();
        $include = apply_filters('include_style_settings', true);
        if ($include) {
            include ATBDP_DIR . 'public/assets/css/style.php';
        }
        if ($redirect_page_url) {
            $redirect = '<script>window.location="' . esc_url($redirect_page_url) . '"</script>';
            return $redirect;
        }

        !empty($args['data']) ? extract($args['data']) : array(); // data array contains all required var.
        $all_listings = !empty($all_listings) ? $all_listings : new WP_Query;
        $paginate = !empty($paginate) ? $paginate : '';
        $is_disable_price = get_directorist_option('disable_list_price');
        $container_fluid = apply_filters('atbdp_public_profile_container_fluid', 'container-fluid');

        $author_name = get_the_author_meta('display_name', $author_id);
        $user_registered = get_the_author_meta('user_registered', $author_id);
        $u_pro_pic = get_user_meta($author_id, 'pro_pic', true);
        $u_pro_pic = !empty($u_pro_pic) ? wp_get_attachment_image_src($u_pro_pic, 'thumbnail') : '';
        $bio = get_user_meta($author_id, 'description', true);
        $content = apply_filters('the_content', $bio);
        $avatar_img = get_avatar($author_id, apply_filters('atbdp_avatar_size', 96));
        $address = esc_attr(get_user_meta($author_id, 'address', true));
        $phone = esc_attr(get_user_meta($author_id, 'atbdp_phone', true));
        $email = get_the_author_meta('user_email', $author_id);
        $website = get_the_author_meta('user_url', $author_id);;
        $facebook = get_user_meta($author_id, 'atbdp_facebook', true);
        $twitter = get_user_meta($author_id, 'atbdp_twitter', true);
        $linkedIn = get_user_meta($author_id, 'atbdp_linkedin', true);
        $youtube = get_user_meta($author_id, 'atbdp_youtube', true);
        $categories = get_terms(ATBDP_CATEGORY, array('hide_empty' => 0));
        $email_show = get_directorist_option('display_author_email', 'public');

        $enable_review = get_directorist_option('enable_review', 1);

        if ( $enable_review ) {
            $author_rating = $this->get_rating();
            $author_review_count = $this->get_review_count();
            $total_listing = $this->get_total_listing_number();
        }

        $header_title = apply_filters('atbdp_author_listings_header_title', 1);
        $author_cat_filter = get_directorist_option('author_cat_filter',1);
        $listings = apply_filters('atbdp_author_listings', true);

        // Default Template
        $path = atbdp_get_theme_file("/directorist/shortcodes/author-profile.php");
        if ( $path ) {
            include $path;
        } else {
            include ATBDP_TEMPLATES_DIR . "public-templates/shortcodes/author-profile.php";
        }
        return ob_get_clean();
        
	}
}