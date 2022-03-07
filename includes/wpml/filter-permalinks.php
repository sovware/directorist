<?php

namespace Directorist\WPML;

class Filter_Permalinks {

    public static $instance = null;

     /**
     * Constuctor
     * 
     * @return void
     */
    public function __construct() {
        add_filter( 'wpml_ls_language_url', [ $this, 'filter_lang_switcher_url_for_archive_pages' ], 20, 2 );
        add_filter( 'wpml_ls_language_url', [ $this, 'filter_lang_switcher_url_for_author_profile_page' ], 20, 2 );
        add_filter( 'wpml_ls_language_url', [ $this, 'filter_lang_switcher_url_for_single_taxonomy_page' ], 20, 2 );
        
        add_filter( 'atbdp_checkout_page_url', [ $this, 'filter_checkout_page_url' ], 20, 2 );
        add_filter( 'atbdp_payment_receipt_page_url', [ $this, 'filter_payment_receipt_page_url' ], 20, 2 );
        add_filter( 'atbdp_edit_listing_page_url', [ $this, 'filter_edit_listing_page_url' ], 20, 2 );
        add_filter( 'atbdp_author_profile_page_url', [ $this, 'filter_author_profile_page_url' ], 20, 4 );
        
        add_filter( 'atbdp_single_category', [ $this, 'filter_single_category_page_url' ], 20, 4 );
        add_filter( 'atbdp_single_location', [ $this, 'filter_single_location_page_url' ], 20, 4 );
        add_filter( 'atbdp_single_tag', [ $this, 'filter_single_tag_page_url' ], 20, 4 );
        
        add_filter( 'directorist_pagination', [ $this, 'filter_directorist_pagination_url' ], 20, 4 );
        add_filter( 'directorist_get_directory_type_nav_url', [ $this, 'filter_directorist_directory_type_nav_url' ], 20, 4 );
    }

    /**
     * Filter directorist pagination URL
     * 
     * @return string
     */
    public function filter_directorist_pagination_url( $navigation, $links, $query_results, $paged ) {
        $paged = 1;
        $largeNumber = 999999999;

        $total = ( isset( $query_results->total_pages ) ) ? $query_results->total_pages : $query_results->max_num_pages;
        $paged = ( isset( $query_results->current_page ) ) ? $query_results->current_page : $paged;

        $links = paginate_links(array(
            'base'      => str_replace( $largeNumber, '%#%', get_pagenum_link( $largeNumber, false ) ),
            'format'    => '?paged=%#%',
            'current'   => max(1, $paged),
            'total'     => $total,
            'prev_text' => apply_filters('directorist_pagination_prev_text', '<span class="fa fa-chevron-left"></span>'),
            'next_text' => apply_filters('directorist_pagination_next_text', '<span class="fa fa-chevron-right atbdp_right_nav"></span>'),
        ));

        if ( $links ) {
            $navigation = '<div class="directorist-pagination">'.$links.'</div>';
        }

        return $navigation;
    }

    /**
     * Filter directorist directory type nav URL
     * 
     * @return string
     */
    public function filter_directorist_directory_type_nav_url( $url, $type, $base_url ) {
        
        if ( ! empty( $base_url ) ) {
            $base_url = remove_query_arg( [ 'page', 'paged', 'directory_type', 'directory-type' ] );
            $base_url = add_query_arg( [ 'directory_type' => $type ], $base_url );
            
            $base_url = preg_replace( '~/page/(\d+)/?~', '', $base_url );
            $base_url = preg_replace( '~/paged/(\d+)/?~', '', $base_url );
        }

        return $url;
    }

    /**
     * Filter author profile page URL
     * 
     * @return string
     */
    public function filter_author_profile_page_url( $url, $page_id, $author_id, $directory_type ) {

        if ( ! $page_id ) {
            return $url;
        }

        if ( empty(  get_option( 'permalink_structure' ) ) ) {
            return $url;
        }

        $url = get_permalink( $page_id );
        
        if ( ! empty( $directory_type ) ) {
            $query_args = [
                'author_id'      => $author_id,
                'directory-type' => $directory_type,
            ];

            $url = add_query_arg( $query_args, $url );

            return $url;
        }
        

        $query_args = [ 'author_id' => $author_id ];
        $url = add_query_arg( $query_args, $url );

        return $url;
    }

    /**
     * Filter single category page URL
     * 
     * @return string
     */
    public function filter_single_category_page_url( $link, $term, $page_id, $directory_type ) {

        return $this->filter_single_taxonomy_page_url([
            'link'           => $link,
            'term'           => $term,
            'page_id'        => $page_id,
            'directory_type' => $directory_type,
            'query_arg_key'  => 'atbdp_category',
        ]);

    }

    /**
     * Filter single location page URL
     * 
     * @return string
     */
    public function filter_single_location_page_url( $link, $term, $page_id, $directory_type ) {

        return $this->filter_single_taxonomy_page_url([
            'link'           => $link,
            'term'           => $term,
            'page_id'        => $page_id,
            'directory_type' => $directory_type,
            'query_arg_key'  => 'atbdp_location',
        ]);

    }

    /**
     * Filter single tag page URL
     * 
     * @return string
     */
    public function filter_single_tag_page_url( $link, $term, $page_id, $directory_type ) {

        return $this->filter_single_taxonomy_page_url([
            'link'           => $link,
            'term'           => $term,
            'page_id'        => $page_id,
            'directory_type' => $directory_type,
            'query_arg_key'  => 'atbdp_tag',
        ]);

    }

    /**
     * Filter single taxonomy page URL
     * 
     * @return string
     */
    public function filter_single_taxonomy_page_url( $args = [] ) {

        $link           = ( isset( $args['link'] ) ) ? $args['link'] : '';
        $term           = ( isset( $args['term'] ) ) ? $args['term'] : null;
        $page_id        = ( isset( $args['page_id'] ) ) ? $args['page_id'] : 0;
        $directory_type = ( isset( $args['directory_type'] ) ) ? $args['directory_type'] : 0;
        $query_arg_key  = ( isset( $args['query_arg_key'] ) ) ? $args['query_arg_key'] : '';

        $link = get_permalink( $page_id );

        if ( ! $page_id ) {
            return $link;
        }

        if ( empty( $term ) || is_wp_error( $term ) ) {
            return $link;
        }

        if ( empty(  get_option( 'permalink_structure' ) ) ) {
            return $link;
        }

        if ( ! empty( $query_arg_key ) ) {
            $link = add_query_arg( $query_arg_key, $term->slug, $link );
        }

        if ( ! empty( $directory_type ) && 'all' != $directory_type ) {
            $link = add_query_arg( 'directory_type', $directory_type, $link );
        }

        return $link;
    }

    /**
     * Filter language switcher URL for archive page
     * 
     * @return string
     */
    public function filter_lang_switcher_url_for_archive_pages( $url, $data ) {

        $page_ids = [
            'all_listings'  => get_directorist_option('all_listing_page'),
            'search_result' => get_directorist_option('search_result_page'),
        ];

        // Check is current page is archive page
        $is_archive_page = false;

        foreach ( $page_ids as $page_id ) {

            if ( empty( $page_id ) ) {
                continue;
            }

            $page_id = ( int ) $page_id;

            if (  $this->is_id_current_page( $page_id ) ) {
                $is_archive_page = true;
                break;
            }

        }

        if ( ! $is_archive_page ) {
            return $url;
        }

        if ( ! empty( $_REQUEST ) ) {
            $url = add_query_arg( $_REQUEST, $url );
        }

        // Pagination
        $page = atbdp_get_paged_num();

        if ( ! empty( $page ) ) {
            $url = remove_query_arg( 'paged' );
            $url = add_query_arg( 'paged', $page , $url );
        }

        return $url;
    }


    /**
     * Filter language switcher URL for author profile page
     * 
     * @return string
     */
    public function filter_lang_switcher_url_for_author_profile_page( $url, $data ) {

        $page_id = get_directorist_option('author_profile_page');

        if ( empty( $page_id ) ) {
            return $url;
        }

        $page_id = ( int ) $page_id;

        if ( ! $this->is_id_current_page( $page_id ) ) {
            return $url;
        }

        // Author ID
        $author_id = ( isset( $_REQUEST['author_id'] ) ) ? $_REQUEST['author_id'] : get_query_var( 'author_id' );

        if ( ! empty( $author_id ) ) {
            $url = add_query_arg( 'author_id', $author_id , $url );
        }

        // Directory Type
        $directory_type = ( isset( $_REQUEST['directory_type'] ) ) ? $_REQUEST['directory_type'] : get_query_var( 'directory_type' );
        
        if ( empty( $directory_type ) ) {
            $directory_type = ( isset( $_REQUEST['directory-type'] ) ) ? $_REQUEST['directory-type'] : get_query_var( 'directory-type' );
        }

        if ( ! empty( $directory_type ) ) {
            $url = add_query_arg( 'directory_type', $directory_type , $url );
        }

        // Pagination
        $page = atbdp_get_paged_num();

        if ( ! empty( $page ) ) {
            $url = add_query_arg( 'paged', $page , $url );
        }

        return $url;
    }

    /**
     * Filter language switcher URL for single taxonomy page
     * 
     * @return string
     */
    public function filter_lang_switcher_url_for_single_taxonomy_page( $url, $data ) {

        $taxonomy_data = $this->get_current_page_taxonomy_data();

        if ( $taxonomy_data ) {
            $lang = $data['tag'];

            return $this->get_formated_single_taxonomy_page_url( $url, $taxonomy_data, $lang );
        }

        return $url;
    }
    
    /**
     * Get current page taxonomy data
     * 
     * @return mixed null | $taxonomy_data
     */
    public function get_current_page_taxonomy_data() {
        $terms_pages = [ 
            'single_category_page' => [
                'taxonomy'  => ATBDP_CATEGORY,
                'query_var' => 'atbdp_category',
            ],
            'single_location_page' => [
                'taxonomy'  => ATBDP_LOCATION,
                'query_var' => 'atbdp_location',
            ],
            'single_tag_page' => [
                'taxonomy'  => ATBDP_TAGS,
                'query_var' => 'atbdp_tag',
            ],
        ];

        foreach( $terms_pages as $page_key => $taxonomy_data ) {
            $term_page_id = get_directorist_option( $page_key );

            if ( empty( $term_page_id ) ) {
                continue;
            }

            $term_page_translations = apply_filters( 'wpml_get_element_translations', null, $term_page_id, 'post_page' );

            if ( empty( $term_page_translations ) ) {
                continue;
            }

            foreach( $term_page_translations as $term_page_translation_key => $term_page_translation_args ) {
                $translation_page_id = (int) $term_page_translation_args->element_id;

                if ( $translation_page_id === get_the_ID() ) {
                    return $taxonomy_data;
                }
            } 
        }
        
        return null;
    }

    /**
     * Get formated single term page URL
     * 
     * @param string $url
     * @param array $current_term_data
     * @param string $lang
     * 
     * @return string
     */
    public function get_formated_single_taxonomy_page_url( $url = '', $current_term_data = [], $lang = 'en' ) {
        global $sitepress;

        $term_slug   = get_query_var( $current_term_data['query_var'] );
        $active_term = get_term_by( 'slug', $term_slug, $current_term_data['taxonomy'] );

        if ( empty( $active_term ) ) {
            return $url;
        }

        $term_page_url_lang = $lang;
        $translation_term_id = wpml_object_id_filter(
            $active_term->term_id, 
            $current_term_data['taxonomy'], 
            false, 
            $term_page_url_lang 
        );

        remove_filter( 'get_term', array( $sitepress,'get_term_adjust_id'), 1, 1 );
        $translation_term = get_term_by( 'id', $translation_term_id, $current_term_data['taxonomy'] );
        add_filter('get_term', array( $sitepress,'get_term_adjust_id'), 1, 1 );
        
        if ( empty( $translation_term ) || is_wp_error( $translation_term ) ) {
            return $url;
        }

        $translation_term_slug = $translation_term->slug;
        $page = atbdp_get_paged_num();

        $url = add_query_arg( $current_term_data['query_var'], $translation_term_slug , $url );

        if ( ! empty( $page ) ) {
            $url = add_query_arg( 'paged', $page , $url );
        }

        if ( isset( $_REQUEST['directory_type'] ) ) {
            $directory_type = $_REQUEST['directory_type'];
            $url = add_query_arg( [ 'directory_type' => $directory_type ], $url );  
        }
        
        return $url;
    }

    /**
     * Get Instance
     * 
     * @return void
     */
    public static function get_instance() {

        if ( null === self::$instance ) {
            self::$instance = new Filter_Permalinks();
        }

        return self::$instance;
    }

    /**
     * Filter Checkout Page URL
     * 
     * @param string $url = ''
     * @param string $listing_id = 0
     */
    public function filter_checkout_page_url( $url = '',  $listing_id = 0 ) {
        $pattern = '/(\/submit\/\d+)\/?/';

        if ( preg_match( $pattern, $url ) ) {
            $url = preg_replace( $pattern, '', $url );
            $url = add_query_arg( [ 'submit' => $listing_id ], $url );
        }
        
        return $url;
    }

    /**
     * Filter Payment Receipt Page URL
     * 
     * @param string $url = ''
     * @param string $order_id = 0
     */
    public function filter_payment_receipt_page_url( $url = '',  $order_id = 0 ) {
        $pattern = '/(\/order\/\d+)\/?/';

        if ( preg_match( $pattern, $url ) ) {
            $url = preg_replace( $pattern, '', $url );
            $url = add_query_arg( [ 'order' => $order_id ], $url );
        }

        return $url;
    }

    /**
     * Filter Edit Listing Page URL
     * 
     * @param string $url = ''
     * @param string $listing_id = 0
     */
    public function filter_edit_listing_page_url( $url = '',  $listing_id = 0 ) {
        $pattern = '/(\/edit\/\d+)\/?/';

        if ( preg_match( $pattern, $url ) ) {
            $url = preg_replace( $pattern, '', $url );
            $url = add_query_arg( [ 'edit' => $listing_id ], $url );
        }

        return $url;
    }


    /**
     * WPML language url format is pretty
     * 
     * @return bool
     */
    public function wpml_language_url_format_is_pretty() {
        $language_negotiation_type = apply_filters( 'wpml_setting', 1, 'language_negotiation_type' );
        $language_negotiation_type = ( ! empty( $language_negotiation_type ) && is_numeric( $language_negotiation_type ) ) ? ( int ) $language_negotiation_type : 1;

        return ( 3 != $language_negotiation_type );
    }

    /**
     * Checks if the given page ID matches with current page ID
     * 
     * @return bool
     */
    public function is_id_current_page( $page_id = 0, $element_type = 'post_page' ) {

        $page_translations = apply_filters( 'wpml_get_element_translations', null, $page_id, $element_type );

        if ( empty( $page_translations ) ) {
            return $page_id === get_the_ID();
        }


        foreach( $page_translations as $page_translation_args ) {
            $translation_page_id = (int) $page_translation_args->element_id;

            if ( $translation_page_id === get_the_ID() ) {
                return true;
            }
        }

        return false;
    }
}
