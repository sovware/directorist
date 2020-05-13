<?php
/**
 * @author AazzTech
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Directorist_Listing_Author {

    public $id;

    public function __construct( $id = '' ) {
        if ( ! $id ) {
            $id = ! empty( $_GET['author_id'] ) ? $_GET['author_id'] : get_current_user_id();
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

    public function all_listings_query() {
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

        ob_start();
        $container_fluid = apply_filters( 'atbdp_public_profile_container_fluid', 'container-fluid' );

        atbdp_get_shortcode_template( 'author/author-profile', compact( 'container_fluid' ) );

        return ob_get_clean();
    }
}