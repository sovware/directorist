<?php
/**
 * @author AazzTech
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Directorist_Listing_Search_Form {

    // Search Shortcode
    public $atts;
    public $defaults;
    public $params;

    public $show_title_subtitle;
    public $has_search_button;
    public $has_more_filters_button;
    public $logged_in_user_only;

    public $search_bar_title;
    public $search_bar_sub_title;
    public $search_button_text;
    public $more_filters_text;
    public $more_filters_display;

    // Common - Search Shortcode and Listing Header
    public $has_search_text_field;
    public $has_category_field;
    public $has_location_field;
    public $has_price_field;
    public $has_price_range_field;
    public $has_rating_field;
    public $has_radius_search;
    public $has_tag_field;
    public $has_custom_fields;
    public $has_website_field;
    public $has_email_field;
    public $has_phone_field;
    public $has_fax_field;
    public $has_address_field;
    public $has_zip_code_field;
    public $has_reset_filters_button;
    public $has_apply_filters_button;
    public $reset_filters_text;
    public $apply_filters_text;
    public $has_open_now_field;

    public $c_symbol;
    public $tag_label;
    public $website_label;
    public $email_label;
    public $fax_label;
    public $address_label;
    public $zip_label;
    public $default_radius_distance;
    public $tag_terms;
    public $search_text_placeholder;
    public $category_placeholder;
    public $location_placeholder;
    public $search_required_text;
    public $cat_required_text;
    public $loc_required_text;
    public $categories_fields;
    public $locations_fields;
    public $category_id;
    public $category_class;
    public $location_id;
    public $location_class;
    public $location_source;

    public function __construct( $type, $atts = array() ) {

        $this->atts = $atts;

        if ( $type=='search' ) {
            $this->prepare_search_data($atts);
        }

        if ( $type=='listing' ) {
            $this->prepare_listing_data($atts);
        }

        $this->c_symbol       = atbdp_currency_symbol( get_directorist_option( 'g_currency', 'USD' ) );
        $this->tag_label      = get_directorist_option( 'tag_label', __( 'Tag', 'directorist' ) );
        $this->website_label  = get_directorist_option( 'website_label', __( 'Website', 'directorist' ) );
        $this->email_label    = get_directorist_option( 'email_label', __( 'Email', 'directorist' ) );
        $this->fax_label      = get_directorist_option( 'fax_label', __( 'Fax', 'directorist' ) );
        $this->address_label  = get_directorist_option( 'address_label', __( 'Address', 'directorist' ) );
        $this->zip_label      = get_directorist_option( 'zip_label', __( 'Zip', 'directorist' ) );
        $this->categories_fields = search_category_location_filter( $this->search_category_location_args(), ATBDP_CATEGORY );
        $this->locations_fields  = search_category_location_filter( $this->search_category_location_args(), ATBDP_LOCATION );
    }

    public function prepare_search_data($atts) {

        $search_fields = get_directorist_option('search_tsc_fields', array('search_text', 'search_category', 'search_location'));
        $search_more_filters_fields = get_directorist_option('search_more_filters_fields', array('search_price', 'search_price_range', 'search_rating', 'search_tag', 'search_custom_fields', 'radius_search'));
        $search_filters = get_directorist_option('search_filters', array('search_reset_filters', 'search_apply_filters'));
        $search_location_address = get_directorist_option('search_location_address', 'address');

        $this->defaults = array(
            'show_title_subtitle'    => 'yes',
            'search_bar_title'       => get_directorist_option('search_title', __("Search here", 'directorist')),
            'search_bar_sub_title'   => get_directorist_option('search_subtitle', __("Find the best match of your interest
                ", 'directorist')),
            'text_field'             => in_array('search_text', $search_fields) ? 'yes' : '',
            'category_field'         => in_array('search_category', $search_fields) ? 'yes' : '',
            'location_field'         => in_array('search_location', $search_fields) ? 'yes' : '',
            'search_button'          => !empty( get_directorist_option( 'search_button', 1 ) ) ? 'yes' : '',
            'search_button_text'     => get_directorist_option('search_listing_text', __('Search Listing', 'directorist')),
            'more_filters_button'    => !empty( get_directorist_option( 'search_more_filter', 1 ) ) ? 'yes' : '',
            'more_filters_text'      => get_directorist_option('search_more_filters', __('More Filters', 'directorist')),
            'price_min_max_field'    => in_array('search_price', $search_more_filters_fields) ? 'yes' : '',
            'price_range_field'      => in_array('search_price_range', $search_more_filters_fields) ? 'yes' : '',
            'rating_field'           => in_array('search_rating', $search_more_filters_fields) ? 'yes' : '',
            'tag_field'              => in_array('search_tag', $search_more_filters_fields) ? 'yes' : '',
            'open_now_field'         => in_array('search_open_now', $search_more_filters_fields) ? 'yes' : '',
            'custom_fields'          => in_array('search_custom_fields', $search_more_filters_fields) ? 'yes' : '',
            'website_field'          => in_array('search_website', $search_more_filters_fields) ? 'yes' : '',
            'email_field'            => in_array('search_email', $search_more_filters_fields) ? 'yes' : '',
            'phone_field'            => in_array('search_phone', $search_more_filters_fields) ? 'yes' : '',
            'fax'                    => in_array('search_fax', $search_more_filters_fields) ? 'yes' : '',
            'address_field'          => in_array('search_address', $search_more_filters_fields) ? 'yes' : '',
            'zip_code_field'         => in_array('search_zip_code', $search_more_filters_fields) ? 'yes' : '',
            'radius_search'          => in_array('radius_search', $search_more_filters_fields) ? 'yes' : '',
            'reset_filters_button'   => in_array('search_reset_filters', $search_filters) ? 'yes' : '',
            'apply_filters_button'   => in_array('search_apply_filters', $search_filters) ? 'yes' : '',
            'reset_filters_text'     => get_directorist_option('search_reset_text', __('Reset Filters', 'directorist')),
            'apply_filters_text'     => get_directorist_option('search_apply_filter', __('Apply Filters', 'directorist')),
            'logged_in_user_only'    => '',
            'redirect_page_url'      => '',
            'more_filters_display'   => get_directorist_option('home_display_filter', 'overlapping'),
        );

        $this->params = shortcode_atts( $this->defaults, $this->atts );

        $this->show_title_subtitle      = $this->params['show_title_subtitle'] == 'yes' ? true : false;
        $this->has_search_text_field    = $this->params['text_field'] == 'yes' ? true : false;
        $this->has_category_field       = $this->params['category_field'] == 'yes' ? true : false;
        $this->has_location_field       = $this->params['location_field'] == 'yes' ? true : false;
        $this->has_search_button        = $this->params['search_button'] == 'yes' ? true : false;
        $this->has_more_filters_button  = $this->params['more_filters_button'] == 'yes' ? true : false;
        $this->has_price_field          = $this->params['price_min_max_field'] == 'yes' ? true : false;
        $this->has_price_range_field    = $this->params['price_range_field'] == 'yes' ? true : false;
        $this->has_rating_field         = $this->params['rating_field'] == 'yes' ? true : false;
        $this->has_tag_field            = $this->params['tag_field'] == 'yes' ? true : false;
        $this->has_open_now_field       = $this->params['open_now_field'] == 'yes' ? true : false;
        $this->has_custom_fields        = $this->params['custom_fields'] == 'yes' ? true : false;
        $this->has_website_field        = $this->params['website_field'] == 'yes' ? true : false;
        $this->has_email_field          = $this->params['email_field'] == 'yes' ? true : false;
        $this->has_phone_field          = $this->params['phone_field'] == 'yes' ? true : false;
        $this->has_fax_field            = $this->params['fax'] == 'yes' ? true : false;
        $this->has_address_field        = $this->params['address_field'] == 'yes' ? true : false;
        $this->has_zip_code_field       = $this->params['zip_code_field'] == 'yes' ? true : false;
        $this->has_radius_search        = ($this->params['radius_search'] == 'yes') && ('map_api' == $search_location_address) ? true : false;
        $this->has_reset_filters_button = $this->params['reset_filters_button'] == 'yes' ? true : false;
        $this->has_apply_filters_button = $this->params['apply_filters_button'] == 'yes' ? true : false;
        $this->logged_in_user_only      = $this->params['logged_in_user_only'] == 'yes' ? true : false;

        $this->search_bar_title     = $this->params['search_bar_title'];
        $this->search_bar_sub_title = $this->params['search_bar_sub_title'];
        $this->search_button_text   = $this->params['search_button_text'];
        $this->more_filters_text    = $this->params['more_filters_text'];
        $this->reset_filters_text   = $this->params['reset_filters_text'];
        $this->apply_filters_text   = $this->params['apply_filters_text'];
        $this->more_filters_display = $this->params['more_filters_display'];

        $this->default_radius_distance = get_directorist_option( 'listing_default_radius_distance', 0 );
        $this->tag_terms               = get_terms(ATBDP_TAGS);
        $this->search_text_placeholder = get_directorist_option('search_placeholder', __('What are you looking for?', 'directorist'));
        $this->category_placeholder    = get_directorist_option('search_category_placeholder', __('Select a category', 'directorist'));
        $this->location_placeholder    = get_directorist_option('search_location_placeholder', __('location', 'directorist'));
        $this->search_required_text    = !empty(get_directorist_option('require_search_text')) ? ' required' : '';
        $this->cat_required_text       = !empty(get_directorist_option('require_search_category')) ? ' required' : '';
        $this->loc_required_text       = !empty(get_directorist_option('require_search_location')) ? ' required' : '';     
        $this->category_id             = 'at_biz_dir-category';
        $this->category_class          = 'search_fields form-control';
        $this->location_id             = 'at_biz_dir-location';
        $this->location_class          = 'search_fields form-control';
        $this->location_source         = ($search_location_address == 'map_api') ? 'map' : 'address';
    }

    public function prepare_listing_data() {
        $search_more_filters_fields  = get_directorist_option( 'listing_filters_fields', array( 'search_text', 'search_category', 'search_location', 'search_price', 'search_price_range', 'search_rating', 'search_tag', 'search_custom_fields', 'radius_search' ) );
        $listing_location_address   = get_directorist_option( 'listing_location_address', 'map_api' );
        $filters_buttons            = get_directorist_option( 'listings_filters_button', array( 'reset_button', 'apply_button' ) );

        $this->has_search_text_field    = in_array( 'search_text', $search_more_filters_fields ) ? true : false;
        $this->has_category_field       = in_array( 'search_category', $search_more_filters_fields ) ? true : false;
        $this->has_location_field       = in_array( 'search_location', $search_more_filters_fields ) ? true : false;
        $this->has_price_field          = in_array( 'search_price', $search_more_filters_fields ) ? true : false;
        $this->has_price_range_field    = in_array( 'search_price_range', $search_more_filters_fields ) ? true : false;
        $this->has_rating_field         = in_array( 'search_rating', $search_more_filters_fields ) ? true : false;
        $this->has_radius_search        = ( 'map_api' == $listing_location_address) && in_array( 'radius_search', $search_more_filters_fields ) ? true : false;
        $this->has_tag_field            = in_array( 'search_tag', $search_more_filters_fields ) ? true : false;
        $this->has_custom_fields        = in_array( 'search_custom_fields', $search_more_filters_fields ) ? true : false;
        $this->has_website_field        = in_array( 'search_website', $search_more_filters_fields ) ? true : false;
        $this->has_email_field          = in_array( 'search_email', $search_more_filters_fields ) ? true : false;
        $this->has_phone_field          = in_array( 'search_phone', $search_more_filters_fields ) ? true : false;
        $this->has_fax_field            = in_array( 'search_fax', $search_more_filters_fields ) ? true : false;
        $this->has_address_field        = false;
        $this->has_zip_code_field       = in_array( 'search_zip_code', $search_more_filters_fields ) ? true : false;
        $this->has_open_now_field       = in_array( 'search_open_now', $search_more_filters_fields ) ? true : false;
        $this->has_reset_filters_button = in_array( 'reset_button', $filters_buttons ) ? true : false;
        $this->has_apply_filters_button = in_array( 'apply_button', $filters_buttons ) ? true : false;
        $this->reset_filters_text       = get_directorist_option( 'listings_reset_text', __( 'Reset Filters', 'directorist' ) );
        $this->apply_filters_text       = get_directorist_option( 'listings_apply_text', __( 'Apply Filters', 'directorist' ) );

        $this->default_radius_distance = get_directorist_option('search_default_radius_distance',0);
        $this->tag_terms               = $this->listing_tag_terms();
        $this->search_text_placeholder = get_directorist_option( 'listings_search_text_placeholder', __( 'What are you looking for?', 'directorist' ) );
        $this->category_placeholder  = get_directorist_option( 'listings_category_placeholder', __( 'Select a category', 'directorist' ) );
        $this->location_placeholder  = get_directorist_option( 'listings_location_placeholder', __( 'Select a location', 'directorist' ) );
        $this->search_required_text    = '';
        $this->cat_required_text       = '';
        $this->loc_required_text       = '';
        $this->category_id             = 'cat-type';
        $this->category_class          = 'form-control directory_field bdas-category-search';
        $this->location_id             = 'loc-type';
        $this->location_class          = 'form-control directory_field bdas-category-location';
        $this->location_source         = ($listing_location_address == 'map_api') ? 'map' : 'address';
    }

    public function price_range_template() {
        if ($this->has_price_field || $this->has_price_range_field) {
           atbdp_get_shortcode_template( 'search/price-range', array('searchform' => $this) );
        }
    }

    public function rating_template() {
        if ($this->has_rating_field) {
           atbdp_get_shortcode_template( 'search/rating', array('searchform' => $this) );
        }
    }

    public function radius_search_template() {
        if ($this->has_radius_search) {
           atbdp_get_shortcode_template( 'search/radius-search', array('searchform' => $this) );
        }
    }

    public function tag_template() {
        if ($this->has_tag_field && !empty($this->tag_terms)) {
           atbdp_get_shortcode_template( 'search/tag', array('searchform' => $this) );
        }
    }

    public function custom_fields_template() {
        if ($this->has_custom_fields) {
           atbdp_get_shortcode_template( 'search/custom-fields', array('searchform' => $this) );
        }
    }

    public function information_template() {
        if ( $this->has_website_field || $this->has_email_field || $this->has_phone_field || $this->has_fax_field || $this->has_address_field || $this->has_zip_code_field ) {
           atbdp_get_shortcode_template( 'search/information', array('searchform' => $this) );
        }
    }

    public function buttons_template() {
        if ($this->has_reset_filters_button || $this->has_apply_filters_button) {
           atbdp_get_shortcode_template( 'search/buttons', array('searchform' => $this) );
        }
    }

    public function open_now_template() {
        if ($this->has_open_now_field && in_array('directorist-business-hours/bd-business-hour.php', apply_filters('active_plugins', get_option('active_plugins')))) {
           atbdp_get_shortcode_template( 'search/open-now', array('searchform' => $this) );
        }
    }

    public function search_text_template() {
        if ($this->has_search_text_field) {
            atbdp_get_shortcode_template( 'search/search-text', array('searchform' => $this) );
        }
    }

    public function category_template() {
        if ($this->has_category_field) {
            atbdp_get_shortcode_template( 'search/category', array('searchform' => $this) );
        }
    }

    public function location_template() {
        if ($this->has_location_field) {
            atbdp_get_shortcode_template( 'search/location-select', array('searchform' => $this) );
        }
    }

    public function search_category_location_args() {
        return array(
            'parent'             => 0,
            'term_id'            => 0,
            'hide_empty'         => 0,
            'orderby'            => 'name',
            'order'              => 'asc',
            'show_count'         => 0,
            'single_only'        => 0,
            'pad_counts'         => true,
            'immediate_category' => 0,
            'active_term_id'     => 0,
            'ancestors'          => array(),
        );
    }

    public function price_value($arg) {
        if ( $arg == 'min' ) {
           return isset( $_GET['price'] ) ? $_GET['price'][0] : '';
        }

        if ( $arg == 'max' ) {
           return isset( $_GET['price'] ) ? $_GET['price'][1] : '';
        }

        return '';
    }

    public function the_price_range_input($range) {
        $checked = ! empty( $_GET['price_range'] ) && $_GET['price_range'] == $range ? ' checked="checked"' : '';
        printf('<input type="radio" name="price_range" value="%s"%s>', $range, $checked);
    }

    public function rating_field_data() {
        $rating_options = array(
            array(
                'selected' => '',
                'value'    => '',
                'label'    => __( 'Select Ratings', 'directorist' ),
            ),
            array(
                'selected' => ( ! empty( $_GET['search_by_rating'] ) && '5' == $_GET['search_by_rating'] ) ? ' selected' : '',
                'value'    => '5',
                'label'    => __( '5 Star', 'directorist' ),
            ),
            array(
                'selected' => ( ! empty( $_GET['search_by_rating'] ) && '4' == $_GET['search_by_rating'] ) ? ' selected' : '',
                'value'    => '4',
                'label'    => __( '4 Star & Up', 'directorist' ),
            ),
            array(
                'selected' => ( ! empty( $_GET['search_by_rating'] ) && '3' == $_GET['search_by_rating'] ) ? ' selected' : '',
                'value'    => '3',
                'label'    => __( '3 Star & Up', 'directorist' ),
            ),
            array(
                'selected' => ( ! empty( $_GET['search_by_rating'] ) && '2' == $_GET['search_by_rating'] ) ? ' selected' : '',
                'value'    => '2',
                'label'    => __( '2 Star & Up', 'directorist' ),
            ),
            array(
                'selected' => ( ! empty( $_GET['search_by_rating'] ) && '1' == $_GET['search_by_rating'] ) ? ' selected' : '',
                'value'    => '1',
                'label'    => __( '1 Star & Up', 'directorist' ),
            ),
        );

        return $rating_options;
    }

    public function listing_tag_terms() {
        $listing_tags_field = get_directorist_option( 'listing_tags_field', 'all_tags' );
        $category_slug      = get_query_var( 'atbdp_category' );
        $category           = get_term_by( 'slug', $category_slug, ATBDP_CATEGORY );
        $category_id        = ! empty( $category->term_id ) ? $category->term_id : '';
        $tag_args           = array(
            'post_type' => ATBDP_POST_TYPE,
            'tax_query' => array(
                array(
                    'taxonomy' => ATBDP_CATEGORY,
                    'terms'    => ! empty( $_GET['in_cat'] ) ? $_GET['in_cat'] : $category_id,
                ),
            ),
        );
        $category_select = ! empty( $_GET['in_cat'] ) ? $_GET['in_cat'] : $category_id;
        $tag_posts       = get_posts( $tag_args );
        if ( ! empty( $tag_posts ) ) {
            foreach ( $tag_posts as $tag_post ) {
                $tag_id[] = $tag_post->ID;
            }
        }
        $tag_id = ! empty( $tag_id ) ? $tag_id : '';
        $terms  = wp_get_object_terms( $tag_id, ATBDP_TAGS );

        if ( 'all_tags' == $listing_tags_field || empty( $category_select ) ) {
            $terms = get_terms( ATBDP_TAGS );
        }

        if ( ! empty( $terms ) ) {
            return $terms;
        }

        return null;
    }
}