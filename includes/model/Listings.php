<?php
/**
 * @author wpWax
 */

namespace Directorist;

use ATBDP_Permalink;
use Directorist\database\DB;

if ( ! defined( 'ABSPATH' ) ) exit;

class Directorist_Listings {
	protected $thumbnails_cached = false;

	public $query_args = [];
	public $query_results = [];
	public $options = [];

	public $atts;
	public $type;
	public $params;

	public $listing_types;
	public $current_listing_type;

    // shortcode properties
	public $view;
	public $_featured;
	public $filterby;
	public $orderby;
	public $order;
	public $listings_per_page;
	public $show_pagination;
	public $header;
	public $header_title;
	public $categories;
	public $locations;
	public $tags;
	public $ids;
	public $columns;
	public $featured_only;
	public $popular_only;
	public $display_preview_image;
	public $advanced_filter;
	public $action_before_after_loop;
	public $logged_in_user_only;
	public $redirect_page_url;
	public $listings_map_height;
	public $map_zoom_level;
	public $directory_type;
	public $default_directory_type;
	public $instant_search;
	public $radius_search_based_on;
	public $sidebar;

	public $query;
	public $loop;

	public $has_featured;
	public $popular_by;
	public $average_review_for_popular;
	public $view_to_popular;
	public $radius_search_unit;
	public $default_radius_distance;
	public $select_listing_map;
	public $filters_display;
	public $search_more_filters_fields;
	public $has_filters_button;
	public $has_filters_icon;
	public $paged;
	public $display_sortby_dropdown;
	public $display_viewas_dropdown;
	public $sort_by_text;
	public $view_as_text;
	public $view_as;
	public $sort_by_items;
	public $views;

	public $location_placeholder;
	public $category_placeholder;

	public $c_symbol;
	public $popular_badge_text;
	public $feature_badge_text;
	public $info_display_in_single_line;
	public $readmore_text;
	public $listing_location_address;
	public $is_disable_price;
	public $disable_single_listing;
	public $disable_contact_info;
	public $display_title;
	public $display_review;
	public $display_price;
	public $display_email;
	public $display_web_link;
	public $display_category;
	public $display_mark_as_fav;
	public $display_publish_date;
	public $display_contact_info;
	public $enable_tagline;
	public $enable_excerpt;
	public $display_author_image;
	public $display_tagline_field;
	public $display_pricing_field;
	public $display_excerpt_field;
	public $display_address_field;
	public $display_phone_field;
	public $display_readmore;
	public $address_location;
	public $excerpt_limit;
	public $use_def_lat_long;
	public $display_map_info;
	public $display_image_map;
	public $display_title_map;
	public $display_address_map;
	public $display_direction_map;
	public $filter_button_text;
	public $display_favorite_badge_map;
	public $display_user_avatar_map;
	public $display_review_map;
	public $display_price_map;
	public $display_phone_map;

	protected $deferred_data = array();

	protected $deferred_props = array(
		'categories_fields',
		'locations_fields',
	);

	public function __construct( $atts = array(), $type = 'listing', $query_args = false, array $caching_options = [] ) {
		$this->atts = !empty( $atts ) ? $atts : array();
		$this->type = !empty( $type ) ? $type : 'listing';

		$this->set_options();

		$current_page = !empty( $this->atts['_current_page'] ) ? $this->atts['_current_page'] : '';

		if ( 'search_result' === $this->type || ( 'instant_search' == $this->type && 'search_result' === $current_page ) ) {
			$this->update_search_options();
		}

		$this->prepare_atts_data();
		$this->prepare_data();

		if ( $query_args ) {
			$this->query_args = $query_args;
		}
		else {
			if ( $this->type == 'search_result' || $this->type == 'instant_search' || ! empty( $_GET ) ) {
				$this->query_args = $this->parse_search_query_args();
			}
			else {
				$this->query_args = $this->parse_query_args();
			}
		}

		$this->query_results = $this->get_query_results();
	}

	public function __get( $prop ) {
		if ( in_array( $prop, $this->deferred_props, true ) ) {
			if ( array_key_exists( $prop, $this->deferred_data ) ) {
				return $this->deferred_data[ $prop ];
			}

			if ( $prop === 'categories_fields' ) {
				$this->deferred_data[ $prop ] = search_category_location_filter( $this->search_category_location_args(), ATBDP_CATEGORY );
			}

			if ( $prop === 'locations_fields' ) {
				$this->deferred_data[ $prop ] = search_category_location_filter( $this->search_category_location_args(), ATBDP_LOCATION );
			}

			return $this->deferred_data[ $prop ];
		}
	}

	// set_options
	public function set_options() {
		$this->options['listing_view']                    = get_directorist_option( 'default_listing_view', 'grid' );
		$this->options['order_listing_by']                = apply_filters( 'atbdp_default_listing_orderby', get_directorist_option( 'order_listing_by', 'date' ) );
		$this->options['sort_listing_by']                 = get_directorist_option( 'sort_listing_by', 'asc' );
		$this->options['listings_per_page']               = get_directorist_option( 'all_listing_page_items', 6 );
		$this->options['paginate_listings']               = ! empty( get_directorist_option( 'paginate_all_listings', 1 ) ) ? 'yes' : '';
		$this->options['display_listings_header']         = ! empty( get_directorist_option( 'display_listings_header', 1 ) ) ? 'yes' : '';
		$this->options['listing_header_title']            = get_directorist_option( 'all_listing_title', __( 'Items Found', 'directorist' ) );
		$this->options['listing_columns']                 = get_directorist_option( 'all_listing_columns', 2 );
		$this->options['listing_filters_button']          = ! empty( get_directorist_option( 'listing_filters_button', 1 ) ) ? 'yes' : '';
		$this->options['listings_map_height']             = get_directorist_option( 'listings_map_height', 350 );
		$this->options['enable_featured_listing']         = directorist_is_featured_listing_enabled();
		$this->options['listing_popular_by']              = get_directorist_option( 'listing_popular_by' );
		$this->options['views_for_popular']               = get_directorist_option( 'views_for_popular', 4 );
		$this->options['radius_search_unit']              = get_directorist_option( 'radius_search_unit', 'miles' );
		$this->options['view_as_text']                    = get_directorist_option( 'view_as_text', __( 'View As', 'directorist' ) );
		$this->options['select_listing_map']              = get_directorist_option( 'select_listing_map', 'google' );
		$this->options['listings_display_filter']         = get_directorist_option( 'home_display_filter', 'sliding' );
		$this->options['listing_filters_fields']          = get_directorist_option( 'listing_filters_fields', array( 'search_text', 'search_category', 'search_location', 'search_price', 'search_price_range', 'search_rating', 'search_tag', 'search_custom_fields', 'radius_search' ) );
		$this->options['listing_filters_icon']            = get_directorist_option( 'listing_filters_icon', 1 ) ? true : false;
		$this->options['listings_sort_by_items']          = get_directorist_option( 'listings_sort_by_items', array( 'a_z', 'z_a', 'latest', 'oldest', 'popular', 'price_low_high', 'price_high_low', 'random' ) );
		$this->options['disable_list_price']              = get_directorist_option( 'disable_list_price' );
		$this->options['listings_view_as_items']          = get_directorist_option( 'listings_view_as_items', array( 'listings_grid', 'listings_list', 'listings_map' ) );
		$this->options['display_sort_by']                 = get_directorist_option( 'display_sort_by', 1 ) ? true : false;
		$this->options['sort_by_text']                    = get_directorist_option( 'sort_by_text', __( 'Sort By', 'directorist' ) );
		$this->options['display_view_as']                 = get_directorist_option( 'display_view_as', 1 );
		$this->options['grid_view_as']                    = get_directorist_option( 'grid_view_as', 'normal_grid' );
		$this->options['average_review_for_popular']      = get_directorist_option( 'average_review_for_popular', 4 );
		$this->options['listing_default_radius_distance'] = get_directorist_option( 'listing_default_radius_distance', 0 );
		$this->options['listings_category_placeholder']   = get_directorist_option( 'listings_category_placeholder', __( 'Select a category', 'directorist' ) );
		$this->options['listings_location_placeholder']   = get_directorist_option( 'listings_location_placeholder', __( 'Select a location', 'directorist' ) );
		$this->options['listings_filter_button_text']     = get_directorist_option( 'listings_filter_button_text', __( 'Filters', 'directorist' ) );
		$this->options['listing_location_address']        = get_directorist_option( 'listing_location_address', 'map_api' );
		$this->options['disable_single_listing']          = get_directorist_option( 'disable_single_listing') ? true : false;
		$this->options['disable_contact_info']            = get_directorist_option( 'disable_contact_info', 0 );
		$this->options['popular_badge_text']              = get_directorist_option( 'popular_badge_text', __( 'Popular', 'directorist' ) );
		$this->options['feature_badge_text']              = get_directorist_option( 'feature_badge_text', __( 'Featured', 'directorist' ) );
		$this->options['readmore_text']                   = get_directorist_option( 'readmore_text', __('Read More', 'directorist'));
		$this->options['info_display_in_single_line']     = get_directorist_option( 'info_display_in_single_line', 0 ) ? 'atbd_single_line_card_info' : '';
		$this->options['display_author_image']            = get_directorist_option( 'display_author_image', 1 ) ? true : false;
		$this->options['display_tagline_field']           = get_directorist_option( 'display_tagline_field', 0 ) ? true : false;
		$this->options['display_readmore']                = get_directorist_option( 'display_readmore', 0) ? true : false;
		$this->options['address_location']                = get_directorist_option( 'address_location', 'contact' );
		$this->options['excerpt_limit']                   = get_directorist_option( 'excerpt_limit', 20);
		$this->options['g_currency']                      = directorist_get_currency();
		$this->options['use_def_lat_long']                = get_directorist_option('use_def_lat_long', 1) ? true : false;
		$this->options['display_map_info']                = get_directorist_option('display_map_info', 1) ? true : false;
		$this->options['display_image_map']               = get_directorist_option('display_image_map', 1) ? true : false;
		$this->options['display_title_map']               = get_directorist_option('display_title_map', 1) ? true : false;
		$this->options['display_address_map']             = get_directorist_option('display_address_map', 1) ? true : false;
		$this->options['display_direction_map']           = get_directorist_option('display_direction_map', 1) ? true : false;
		$this->options['display_favorite_badge_map'] 	  = get_directorist_option('display_favorite_badge_map', 1) ? true : false;
		$this->options['display_user_avatar_map']    	  = get_directorist_option('display_user_avatar_map', 1) ? true : false;
		$this->options['display_review_map']         	  = get_directorist_option('display_review_map', 1) ? true : false;
		$this->options['display_price_map']          	  = get_directorist_option('display_price_map', 1) ? true : false;
		$this->options['display_phone_map']          	  = get_directorist_option('display_phone_map', 1) ? true : false;
		$this->options['crop_width']                      = get_directorist_option('crop_width', 360);
		$this->options['crop_height']                     = get_directorist_option('crop_height', 360);
		$this->options['map_view_zoom_level']             = get_directorist_option('map_view_zoom_level', 16);
		$this->options['default_preview_image']           = get_directorist_option('default_preview_image', DIRECTORIST_ASSETS . 'images/grid.jpg');
		$this->options['font_type']                       = 'line';
		$this->options['display_publish_date']            = get_directorist_option('display_publish_date', 1) ? true : false;
		$this->options['default_latitude']                = get_directorist_option('default_latitude', 40.7127753);
		$this->options['default_longitude']               = get_directorist_option('default_longitude', -74.0059728);
		$this->options['listing_instant_search']          = 'yes';
		$this->options['all_listing_layout']         	  = get_directorist_option( 'all_listing_layout', 'left_sidebar' );
		$this->options['listing_sidebar_top_search_bar']  = get_directorist_option( 'listing_hide_top_search_bar', false );
		$this->options['sidebar_filter_text']    		  = get_directorist_option( 'listings_sidebar_filter_text', 'Filters' );
		$this->options['display_listings_count']    	  = get_directorist_option( 'display_listings_count', true );
		$this->options['marker_clustering']               = get_directorist_option( 'marker_clustering', true ) ? 'markerclusterer' : '';
	}

	// update_search_options
	public function update_search_options() {
		$this->options['display_listings_header']         = ! empty( get_directorist_option( 'search_header', 1 ) ) ? 'yes' : '';
		$this->options['listing_filters_button']          = ! empty( get_directorist_option( 'search_result_filters_button_display', 1 ) ) ? 'yes' : '';
		$this->options['listings_filter_button_text']     = get_directorist_option( 'search_result_filter_button_text', __( 'Filters', 'directorist' ) );
		$this->options['listings_filter_button_text']     = get_directorist_option( 'search_result_filter_button_text', __( 'Filters', 'directorist' ) );
		$this->options['listings_display_filter']         = get_directorist_option( 'search_result_display_filter', 'sliding' );
		$this->options['listing_filters_fields']          = get_directorist_option( 'search_result_filters_fields', array( 'search_text', 'search_category', 'search_location', 'search_price', 'search_price_range', 'search_rating', 'search_tag', 'search_custom_fields', 'radius_search' ) );
		$this->options['listing_location_address']        = get_directorist_option( 'sresult_location_address', 'map_api' );
		$this->options['listing_default_radius_distance'] = get_directorist_option( 'sresult_default_radius_distance', 0 );
		$this->options['listings_category_placeholder']   = get_directorist_option( 'search_result_category_placeholder', __( 'Select a category', 'directorist' ) );
		$this->options['listings_location_placeholder']   = get_directorist_option( 'search_result_location_placeholder', __( 'Select a location', 'directorist' ) );
		$this->options['display_sort_by']                 = get_directorist_option( 'search_sort_by', 1 ) ? true : false;
		$this->options['display_view_as']                 = get_directorist_option( 'search_view_as', 1 );
		$this->options['view_as_text']                    = get_directorist_option( 'search_viewas_text', __( 'View As', 'directorist' ) );
		$this->options['listings_view_as_items']          = get_directorist_option( 'search_view_as_items', array( 'listings_grid', 'listings_list', 'listings_map' ) );
		$this->options['sort_by_text']                    = get_directorist_option( 'search_sortby_text', __( 'Sort By', 'directorist' ) );
		$this->options['listings_sort_by_items']          = get_directorist_option( 'search_sort_by_items', array( 'a_z', 'z_a', 'latest', 'oldest', 'popular', 'price_low_high', 'price_high_low', 'random' ) );
		$this->options['order_listing_by']                = apply_filters( 'atbdp_default_listing_orderby', get_directorist_option( 'search_order_listing_by', 'date' ) );
		$this->options['sort_listing_by']                 = get_directorist_option( 'search_sort_listing_by', 'asc' );
		$this->options['listing_columns']                 = get_directorist_option( 'search_listing_columns', 2 );
		$this->options['paginate_listings']               = ! empty( get_directorist_option( 'paginate_search_results', 1 ) ) ? 'yes' : '';
		$this->options['listings_per_page']               = get_directorist_option( 'search_posts_num', 6 );
		$this->options['all_listing_layout']         	  = get_directorist_option( 'search_result_layout', 'left_sidebar' );
		$this->options['listing_sidebar_top_search_bar']  = get_directorist_option( 'search_result_hide_top_search_bar', false );
		$this->options['sidebar_filter_text']    		  = get_directorist_option( 'search_result_sidebar_filter_text', 'Filters' );
		$this->options['display_listings_count']    	  = get_directorist_option( 'display_search_result_listings_count', true );
		$this->options['listing_header_title']            = get_directorist_option( 'search_result_listing_title', __( 'Items Found', 'directorist' ) );
	}

	public function build_search_data( $key, $value ) {
		$search_form_fields = get_term_meta( $this->get_current_listing_type(), 'search_form_fields', true );
		return ! empty( $search_form_fields['fields'][ $key ][ $value ] ) ? $search_form_fields['fields'][ $key ][ $value ] : '';
	}

	public function prepare_atts_data() {
		$defaults = array(
			'view'                     => $this->options['listing_view'],
			'_featured'                => 1,
			'filterby'                 => '',
			'orderby'                  => $this->options['order_listing_by'],
			'order'                    => $this->options['sort_listing_by'],
			'listings_per_page'        => $this->options['listings_per_page'],
			'show_pagination'          => $this->options['paginate_listings'],
			'header'                   => $this->options['display_listings_header'],
			'header_title'             => $this->options['listing_header_title'],
			'category'                 => '',
			'location'                 => '',
			'tag'                      => '',
			'ids'                      => '',
			'columns'                  => $this->options['listing_columns'],
			'featured_only'            => '',
			'popular_only'             => '',
			'display_preview_image'    => 'yes',
			'advanced_filter'          => $this->options['listing_filters_button'],
			'action_before_after_loop' => 'yes',
			'logged_in_user_only'      => '',
			'redirect_page_url'        => '',
			'map_height'               => $this->options['listings_map_height'],
			'map_zoom_level'           => $this->options['map_view_zoom_level'],
			'directory_type'           => '',
			'default_directory_type'   => '',
			'instant_search'           => $this->options['listing_instant_search'],
			'radius_search_based_on'   => $this->build_search_data( 'radius_search', 'radius_search_based_on' ),
			'sidebar'                  => $this->options['all_listing_layout'],
		);

		$defaults  = apply_filters( 'atbdp_all_listings_params', $defaults );
		$this->params = shortcode_atts( $defaults, $this->atts );

		$this->view                     = atbdp_get_listings_current_view_name( $this->params['view'] );
		$this->_featured                = $this->params['_featured'];
		$this->filterby                 = $this->params['filterby'];
		$this->orderby                  = $this->params['orderby'];
		$this->order                    = $this->params['order'];
		$this->listings_per_page        = (int) $this->params['listings_per_page'];
		$this->show_pagination          = $this->params['show_pagination'] == 'yes' ? true : false;
		$this->header                   = $this->params['header'] == 'yes' ? true : false;
		$this->header_title             = $this->params['header_title'];
		$this->categories               = !empty( $this->params['category'] ) ? explode( ',', $this->params['category'] ) : '';
		$this->tags                     = !empty( $this->params['tag'] ) ? explode( ',', $this->params['tag'] ) : '';
		$this->locations                = !empty( $this->params['location'] ) ? explode( ',', $this->params['location'] ) : '';
		$this->ids                      = !empty( $this->params['ids'] ) ? explode( ',', $this->params['ids'] ) : '';
		$this->columns                  = (int) atbdp_calculate_column( $this->params['columns'] );
		$this->featured_only            = $this->params['featured_only'];
		$this->popular_only             = $this->params['popular_only'];
		$this->display_preview_image    = $this->params['display_preview_image'] == 'yes' ? true : false;
		$this->advanced_filter          = $this->params['advanced_filter'] == 'yes' ? true : false;
		$this->action_before_after_loop = $this->params['action_before_after_loop'] == 'yes' ? true : false;
		$this->logged_in_user_only      = $this->params['logged_in_user_only'] == 'yes' ? true : false;
		$this->redirect_page_url        = $this->params['redirect_page_url'];
		$this->listings_map_height      = ( ! empty( $this->params['map_height'] ) ) ? (int) $this->params['map_height'] : $defaults['map_height'];
		$this->map_zoom_level           = ( ! empty( $this->params['map_zoom_level'] ) ) ? (int) $this->params['map_zoom_level'] : $defaults['map_zoom_level'];
		$this->directory_type           = !empty( $this->params['directory_type'] ) ? explode( ',', $this->params['directory_type'] ) : '';
		$this->default_directory_type   = !empty( $this->params['default_directory_type'] ) ? $this->params['default_directory_type'] : '';
		$this->instant_search          	= !empty( $this->params['instant_search'] ) ? $this->params['instant_search'] : '';
		$this->radius_search_based_on   = !empty( $this->params['radius_search_based_on'] ) ? $this->params['radius_search_based_on'] : 'address';
		$this->sidebar   				= !empty( $this->params['sidebar'] ) ? $this->params['sidebar'] : 'no_sidebar';
	}

	public function prepare_data() {
		$this->listing_types              = $this->get_listing_types();
		$this->current_listing_type       = $this->get_current_listing_type();

		$this->has_featured                = $this->options['enable_featured_listing'];
		$this->has_featured                = $this->has_featured || is_fee_manager_active() ? $this->_featured : $this->has_featured;
		$this->popular_by                  = $this->options['listing_popular_by'];
		$this->average_review_for_popular  = $this->options['average_review_for_popular'];
		$this->view_to_popular             = $this->options['views_for_popular'];
		$this->radius_search_unit          = $this->options['radius_search_unit'];
		$this->default_radius_distance     = $this->options['listing_default_radius_distance'];
		$this->select_listing_map          = $this->options['select_listing_map'];
		$this->filters_display             = $this->options['listings_display_filter'];
		$this->search_more_filters_fields  = $this->options['listing_filters_fields'];
		$this->has_filters_button          = $this->advanced_filter;
		$this->has_filters_icon            = $this->options['listing_filters_icon'];
		$this->filter_button_text          = $this->options['listings_filter_button_text'];
		$this->paged                       = atbdp_get_paged_num();
		$this->display_sortby_dropdown     = $this->options['display_sort_by'];
		$this->display_viewas_dropdown     = $this->options['display_view_as'];
		$this->sort_by_text                = $this->options['sort_by_text'];
		$this->view_as_text                = $this->options['view_as_text'];
		$this->view_as                     = $this->options['grid_view_as'];
		$view_as_items               = $this->options['listings_view_as_items'];
		$this->sort_by_items         = $this->options['listings_sort_by_items'];
		$this->views                 = atbdp_get_listings_view_options( $view_as_items );
		$this->category_placeholder  = $this->options['listings_category_placeholder'];
		$this->location_placeholder  = $this->options['listings_location_placeholder'];
		// $this->categories_fields = search_category_location_filter( $this->search_category_location_args(), ATBDP_CATEGORY );
		// $this->locations_fields  = search_category_location_filter( $this->search_category_location_args(), ATBDP_LOCATION );
		$this->c_symbol                    = atbdp_currency_symbol( $this->options['g_currency'] );
		$this->popular_badge_text          = $this->options['popular_badge_text'];
		$this->feature_badge_text          = $this->options['feature_badge_text'];
		$this->readmore_text               = $this->options['readmore_text'];
		$this->info_display_in_single_line = $this->options['info_display_in_single_line'];
		$this->listing_location_address    = $this->options['listing_location_address'];
		$this->is_disable_price            = $this->options['disable_list_price'];
		$this->disable_single_listing      = $this->options['disable_single_listing'];
		$this->disable_contact_info        = $this->options['disable_contact_info'];
		$this->use_def_lat_long            = $this->options['use_def_lat_long'];
		$this->display_map_info            = $this->options['display_map_info'];
		$this->display_image_map           = $this->options['display_image_map'];
		$this->display_title_map           = $this->options['display_title_map'];
		$this->display_address_map         = $this->options['display_address_map'];
		$this->display_direction_map       = $this->options['display_direction_map'];
		$this->display_favorite_badge_map  = $this->options['display_favorite_badge_map'];
		$this->display_user_avatar_map     = $this->options['display_user_avatar_map'];
		$this->display_review_map          = $this->options['display_review_map'];
		$this->display_price_map           = $this->options['display_price_map'];
		$this->display_phone_map           = $this->options['display_phone_map'];
	}

	public function set_loop_data() {
		$id          = get_the_ID();
		$author_id   = get_the_author_meta( 'ID' );
		$author_data = get_userdata( $author_id );

		$author_first_name   = ! empty( $author_data ) ?  $author_data->first_name : '';
		$author_last_name    = ! empty( $author_data ) ?  $author_data->last_name : '';
		$author_display_name = ! empty( $author_data->display_name ) ?  $author_data->display_name : '';

		$u_pro_pic           = get_user_meta( $author_id, 'pro_pic', true );
		$u_pro_pic           = ! empty( $u_pro_pic ) ? wp_get_attachment_image_src( $u_pro_pic, 'thumbnail' ) : '';
		$bdbh                = get_post_meta( $id, '_bdbh', true );


		$listing_type 		= $this->current_listing_type;
		$card_fields  		= get_term_meta( $listing_type, 'listings_card_grid_view', true );
		$list_fields  		= get_term_meta( $listing_type, 'listings_card_list_view', true );
		$get_directory_type = get_term_by( 'id', $this->current_listing_type, ATBDP_TYPE );
		$directory_type 	= ! empty( $get_directory_type ) ? $get_directory_type->slug : '';
		$this->loop = array(
			'id'                   => $id,
			'card_fields'          => $card_fields,
			'list_fields'          => $list_fields,
			'permalink'            => get_permalink( $id ),
			'title'                => get_the_title(),
			'cats'                 => get_the_terms( $id, ATBDP_CATEGORY ),
			'locs'                 => get_the_terms( $id, ATBDP_LOCATION ),
			'featured'             => get_post_meta( $id, '_featured', true ),
			'listing_img'          => get_post_meta( $id, '_listing_img', true ),
			'listing_prv_img'      => get_post_meta( $id, '_listing_prv_img', true ),
			'tagline'              => get_post_meta( $id, '_tagline', true ),
			'category'             => get_post_meta( $id, '_admin_category_select', true ),
			'post_view'            => directorist_get_listing_views_count( $id ),

			'business_hours'          => ! empty( $bdbh ) ? atbdp_sanitize_array( $bdbh ) : array(),
			'enable247hour'           => get_post_meta( $id, '_enable247hour', true ),
			'disable_bz_hour_listing' => get_post_meta( $id, '_disable_bz_hour_listing', true ),
			'bdbh_version' 			  => get_post_meta( $id, '_bdbh_version', true ),
			'author_id'               => $author_id,
			'author_data'             => $author_data,
			'author_full_name'        => $author_first_name . ' ' . $author_last_name,
			'author_link'             => ATBDP_Permalink::get_user_profile_page_link( $author_id, $directory_type ),
			'author_link_class'       => ! empty( $author_first_name && $author_last_name ) ? 'atbd_tooltip' : '',
			'u_pro_pic'               => $u_pro_pic,
			'avatar_img'              => get_avatar( $author_id, apply_filters( 'atbdp_avatar_size', 32 ), '', $author_display_name ),
			'review'                  => $this->get_review_data(),
		);
	}

	public function get_review_data() {
		// Review
		$average           = directorist_get_listing_rating( get_the_ID() );
		$reviews_count     = directorist_get_listing_review_count( get_the_ID() );

		// Icons
		$icon_empty_star = directorist_icon( 'fas fa-star', false, 'star-empty' );
		$icon_half_star  = directorist_icon( 'fas fa-star-half-alt', false, 'star-half' );
		$icon_full_star  = directorist_icon( 'fas fa-star', false, 'star-full' );

		// Stars
		$star_1 = ( $average > 0 && $average < 1) ? $icon_half_star : $icon_empty_star;
		$star_1 = ( $average >= 1) ? $icon_full_star : $star_1;

		$star_2 = ( $average > 1 && $average < 2) ? $icon_half_star : $icon_empty_star;
		$star_2 = ( $average >= 2) ? $icon_full_star : $star_2;

		$star_3 = ( $average > 2 && $average < 3) ? $icon_half_star : $icon_empty_star;
		$star_3 = ( $average >= 3) ? $icon_full_star : $star_3;

		$star_4 = ( $average > 3 && $average < 4) ? $icon_half_star : $icon_empty_star;
		$star_4 = ( $average >= 4) ? $icon_full_star : $star_4;

		$star_5 = ( $average > 4 && $average < 5 ) ? $icon_half_star : $icon_empty_star;
		$star_5 = ( $average >= 5 ) ? $icon_full_star : $star_5;

		$review_stars = "{$star_1}{$star_2}{$star_3}{$star_4}{$star_5}";

		return [
			'review_stars'    => $review_stars,
			'total_reviews'   => $reviews_count,
			'average_reviews' => number_format( $average, 1 ),
			'review_text'     => _nx( 'Review', 'Reviews', $reviews_count, 'Listing grid review text', 'directorist' ),
		];
	}

	private function execute_meta_query_args(&$args, &$meta_queries) {
		if ( 'rand' === $this->orderby ) {
			$current_order = atbdp_get_listings_current_order( $this->orderby );
		} else {
			$current_order = atbdp_get_listings_current_order( $this->orderby . '-' . $this->order );
		}

		$meta_queries['directory_type'] = array(
			'key'     => '_directory_type',
			'value'   => $this->get_current_listing_type(),
			'compare' => '=',
		);

		// TODO: Status has been migrated, remove related code.
		// $meta_queries['expired'] = array(
		// 	'key'     => '_listing_status',
		// 	'value'   => 'expired',
		// 	'compare' => '!=',
		// );

		if ( $this->has_featured ) {
			if ( '_featured' == $this->filterby ) {
				$meta_queries['_featured'] = array(
					'key'     => '_featured',
					'value'   => 1,
					'compare' => '=',
				);
			} else {
				$meta_queries['_featured'] = array(
					'key'     => '_featured',
					'type'    => 'NUMERIC',
					'compare' => 'EXISTS',
				);
			}
		}

		if ( 'yes' == $this->featured_only ) {
			$meta_queries['_featured'] = array(
				'key'     => '_featured',
				'value'   => 1,
				'compare' => '=',
			);
		}

		if ( 'yes' === $this->popular_only || 'views-desc' === $current_order ) {
			if ( $this->has_featured ) {
				if ( 'average_rating' === $this->popular_by ) {
					$meta_queries['_rating'] = array(
						'key'     => directorist_get_rating_field_meta_key(),
						'value'   => $this->average_review_for_popular,
						'type'    => 'NUMERIC',
						'compare' => '<=',
					);
				} elseif ( 'view_count' === $this->popular_by ) {
					$meta_queries['views'] = array(
						'key'     => directorist_get_listing_views_count_meta_key(),
						'value'   => $this->view_to_popular,
						'type'    => 'NUMERIC',
						'compare' => '>=',
					);

					$args['orderby'] = array(
						'_featured' => 'DESC',
						'views'     => 'DESC',
					);
				} else {
					$meta_queries['views'] = array(
						'key'     => directorist_get_listing_views_count_meta_key(),
						'value'   => $this->view_to_popular,
						'type'    => 'NUMERIC',
						'compare' => '>=',
					);

					$args['orderby'] = array(
						'_featured' => 'DESC',
						'views'     => 'DESC',
					);

					$meta_queries['_rating'] = array(
						'key'     => directorist_get_rating_field_meta_key(),
						'value'   => $this->average_review_for_popular,
						'compare' => '<=',
					);
				}
			} else {
				if ( 'average_rating' === $this->popular_by ) {
					$meta_queries['_rating'] = array(
						'key'     => directorist_get_rating_field_meta_key(),
						'value'   => $this->average_review_for_popular,
						'type'    => 'NUMERIC',
						'compare' => '<=',
					);
				} elseif ( 'view_count' === $this->popular_by ) {
					$meta_queries['views'] = array(
						'key'     => directorist_get_listing_views_count_meta_key(),
						'value'   => $this->view_to_popular,
						'type'    => 'NUMERIC',
						'compare' => '>=',
					);

					$args['orderby'] = array(
						'views' => 'DESC',
					);
				} else {
					$meta_queries['views'] = array(
						'key'     => directorist_get_listing_views_count_meta_key(),
						'value'   => (int) $this->view_to_popular,
						'type'    => 'NUMERIC',
						'compare' => '>=',
					);

					$args['orderby'] = array(
						'views' => 'DESC',
					);

					$meta_queries['_rating'] = array(
						'key'     => directorist_get_rating_field_meta_key(),
						'value'   => $this->average_review_for_popular,
						'type'    => 'NUMERIC',
						'compare' => '<=',
					);
				}
			}
		}

		switch ( $current_order ) {
			case 'title-asc':
				if ( $this->has_featured ) {
					$args['meta_key'] = '_featured';
					$args['orderby']  = array(
						'meta_value_num' => 'DESC',
						'title'          => 'ASC',
					);
				} else {
					$args['orderby'] = 'title';
					$args['order']   = 'ASC';
				}

				break;

			case 'title-desc':
				if ( $this->has_featured ) {
					$args['meta_key'] = '_featured';
					$args['orderby']  = array(
						'meta_value_num' => 'DESC',
						'title'          => 'DESC',
					);
				} else {
					$args['orderby'] = 'title';
					$args['order']   = 'DESC';
				}

				break;

			case 'date-asc':
				if ( $this->has_featured ) {
					$args['meta_key'] = '_featured';
					$args['orderby']  = array(
						'meta_value_num' => 'DESC',
						'date'           => 'ASC',
					);
				} else {
					$args['orderby'] = 'date';
					$args['order']   = 'ASC';
				}

				break;

			case 'date-desc':
				if ( $this->has_featured ) {
					$args['meta_key'] = '_featured';
					$args['orderby']  = array(
						'meta_value_num' => 'DESC',
						'date'           => 'DESC',
					);
				} else {
					$args['orderby'] = 'date';
					$args['order']   = 'DESC';
				}
				break;

			case 'price-asc':
				if ( $this->has_featured ) {
					$meta_queries['price'] = array(
						'key'     => '_price',
						'type'    => 'NUMERIC',
						'compare' => 'EXISTS',
					);

					$args['orderby'] = array(
						'_featured' => 'DESC',
						'price'     => 'ASC',
					);
				} else {
					$args['meta_key'] = '_price';
					$args['orderby']  = 'meta_value_num';
					$args['order']    = 'ASC';
				}
				break;

			case 'price-desc':
				if ( $this->has_featured ) {
					$meta_queries['price'] = array(
						'key'     => '_price',
						'type'    => 'NUMERIC',
						'compare' => 'EXISTS',
					);

					$args['orderby'] = array(
						'_featured' => 'DESC',
						'price'     => 'DESC',
					);
				} else {
					$args['meta_key'] = '_price';
					$args['orderby']  = 'meta_value_num';
					$args['order']    = 'DESC';
				}
				break;

			case 'rand':
				if ( $this->has_featured ) {
					$args['meta_key'] = '_featured';
					$args['orderby']  = 'meta_value_num rand';
				} else {
					$args['orderby'] = 'rand';
				}
				break;
		}
	}

	/**
	 * get_query_results
	 *
	 * @return object
	 */
	public function get_query_results() {
		return DB::get_listings_data( $this->query_args );
	}

	public function parse_query_args() {
		$args = array(
			'post_type'      => ATBDP_POST_TYPE,
			'post_status'    => 'publish',
			'posts_per_page' => $this->listings_per_page,
		);

		if ( $this->show_pagination ) {
			$args['paged'] = $this->paged;
		} else {
			$args['no_found_rows'] = true;
		}

		if ( $this->ids ) {
			$args['post__in'] = $this->ids;
		}

		$tax_queries = array();

		if ( ! empty( $this->categories ) ) {
			$tax_queries['tax_query'][] = array(
				'taxonomy'         => ATBDP_CATEGORY,
				'field'            => 'slug',
				'terms'            => ! empty( $this->categories ) ? $this->categories : array(),
				'include_children' => true, /*@todo; Add option to include children or exclude it*/
			);
		}

		if ( ! empty( $this->locations ) ) {
			$tax_queries['tax_query'][] = array(
				'taxonomy'         => ATBDP_LOCATION,
				'field'            => 'slug',
				'terms'            => ! empty( $this->locations ) ? $this->locations : array(),
				'include_children' => true, /*@todo; Add option to include children or exclude it*/
			);
		}

		if ( ! empty( $this->tags ) ) {
			$tax_queries['tax_query'][] = array(
				'taxonomy'         => ATBDP_TAGS,
				'field'            => 'slug',
				'terms'            => ! empty( $this->tags ) ? $this->tags : array(),
				'include_children' => true, /*@todo; Add option to include children or exclude it*/
			);
		}

		if ( ! empty( $tax_queries ) ) {
			$args['tax_query'] = $tax_queries;
		}

		$meta_queries = array();
		$this->execute_meta_query_args( $args, $meta_queries );

		$meta_queries = apply_filters( 'atbdp_all_listings_meta_queries', $meta_queries );
		$count_meta_queries = count( $meta_queries );

		if ( $count_meta_queries ) {
			$args['meta_query'] = array_merge( array( 'relation' => 'AND' ), $meta_queries );
		}

		/**
		 * Filters the All Listing main query to modify or extend it
		 *
		 * @since 7.4.2
		 *
		 * @param array 	$args 		All listing query arguments
		 * @param object 	$this 		Listings object
		 */
		$args = apply_filters( 'directorist_all_listings_query_arguments', $args, $this );

		return apply_filters_deprecated( 'atbdp_all_listings_query_arguments', array( $args ), '7.4.2', 'directorist_all_listings_query_arguments' );
	}

	public function parse_search_query_args() {
		$args = array(
			'post_type'      => ATBDP_POST_TYPE,
			'post_status'    => 'publish',
			'posts_per_page' => $this->listings_per_page,
		);

		if ( $this->show_pagination ) {
			$args['paged'] = $this->paged;
		} else {
			$args['no_found_rows'] = true;
		}

		if ( ! empty( $_REQUEST['ids'] ) ) {
			$args['post__in'] = wp_parse_id_list( wp_unslash( $_REQUEST['ids'] ) );
			$this->ids = $args['post__in'];
		}

		if ( ! empty( $_REQUEST['q'] ) ) {
			$args['s'] = sanitize_text_field( wp_unslash( $_REQUEST['q'] ) );
		}

		if ( $this->has_featured ) {
			$args['meta_key'] = '_featured';
			$args['orderby']  = array(
				'meta_value_num' => 'DESC',
				'title'          => 'ASC',
			);
		} else {
			$args['orderby'] = 'title';
			$args['order']   = 'ASC';
		}

		$tax_queries = array();

		if ( ! empty( $_REQUEST['in_cat'] ) ) {
			$tax_queries[] = array(
				'taxonomy'         => ATBDP_CATEGORY,
				'field'            => 'term_id',
				'terms'            => wp_parse_id_list( wp_unslash( $_REQUEST['in_cat'] ) ),
				'include_children' => true,
			);
		}

		if ( ! empty( $_REQUEST['in_loc'] ) ) {
			$tax_queries[] = array(
				'taxonomy'         => ATBDP_LOCATION,
				'field'            => 'term_id',
				'terms'            => wp_parse_id_list( wp_unslash( $_REQUEST['in_loc'] ) ),
				'include_children' => true,
			);
		}

		if ( ! empty( $_REQUEST['in_tag'] ) ) {
			$tax_queries[] = array(
				'taxonomy' => ATBDP_TAGS,
				'field'    => 'term_id',
				'terms'    => wp_parse_id_list( wp_unslash( $_REQUEST['in_tag'] ) ),
			);
		}

		if ( ! empty( $this->categories ) ) {
			$tax_queries['tax_query'][] = array(
				'taxonomy'         => ATBDP_CATEGORY,
				'field'            => 'slug',
				'terms'            => ! empty( $this->categories ) ? $this->categories : array(),
				'include_children' => true, /*@todo; Add option to include children or exclude it*/
			);
		}

		if ( ! empty( $this->locations ) ) {
			$tax_queries['tax_query'][] = array(
				'taxonomy'         => ATBDP_LOCATION,
				'field'            => 'slug',
				'terms'            => ! empty( $this->locations ) ? $this->locations : array(),
				'include_children' => true, /*@todo; Add option to include children or exclude it*/
			);
		}

		if ( ! empty( $this->tags ) ) {
			$tax_queries['tax_query'][] = array(
				'taxonomy'         => ATBDP_TAGS,
				'field'            => 'slug',
				'terms'            => ! empty( $this->tags ) ? $this->tags : array(),
				'include_children' => true, /*@todo; Add option to include children or exclude it*/
			);
		}

		if ( count( $tax_queries ) ) {
			$args['tax_query'] = array_merge( array( 'relation' => 'AND' ), $tax_queries );
		}

		$meta_queries = array();

		$this->execute_meta_query_args( $args, $meta_queries );

		if ( isset( $_REQUEST['custom_field'] ) ) {
			// Multi-dimensional array, sanitized inside
			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			$custom_fields = array_filter( wp_unslash( $_REQUEST['custom_field'] ) );

			foreach ( $custom_fields as $key => $values ) {
				$key = sanitize_text_field( $key );
				$meta_query = [];

				if ( is_array( $values ) ) {
					if ( count( $values ) > 1 ) {
						$sub_meta_queries = array(
							'relation' => 'OR'
						);

						foreach ( $values as $value ) {
							$sub_meta_queries[] = array(
								'key'     => '_' . $key,
								'value'   => sanitize_text_field( $value ),
								'compare' => 'LIKE'
							);
						}

						$meta_query = $sub_meta_queries;
					} else {
						$meta_query = array(
							'key'     => '_' . $key,
							'value'   => sanitize_text_field( $values[0] ),
							'compare' => 'LIKE'
						);
					}
				} else {
					$field_type = str_replace( 'custom-', '', $key );
					$field_type = preg_replace( '/([!^0-9])|(-)/', '', $field_type ); //replaces any additional numbering to just keep the field name, for example if previous line gives us "text-2", this line makes it "text"
					// Check if $values contains a hyphen
					if ( strpos( $values, '-' ) !== false ) {
						// If $values is in the format "40-50", create a range query
						list( $min_value, $max_value ) = array_map( 'intval', explode( '-', $values ) );

						$meta_query = array(
							'key'     => '_' . $key,
							'value'   => array( $min_value, $max_value ),
							'type'    => 'NUMERIC',
							'compare' => 'BETWEEN',
						);
					} else {
						$operator   = in_array( $field_type, array( 'text', 'textarea', 'url' ), true ) ? 'LIKE' : '=';
						$meta_query = array(
							'key'     => '_' . $key,
							'value'   => sanitize_text_field( $values ),
							'compare' => $operator
						);
					}
				}

				/**
				 * Filters the custom field meta query used in Directorist search functionality.
				 *
				 * This filter allows customization of the meta query for specific search criteria 
				 * by modifying the meta query parameters, key, and values.
				 *
				 * @since 8.0
				 *
				 * @param array  $meta_query Array of meta query parameters used in the search.
				 * @param string $key        Meta key being queried.
				 * @param mixed  $values     Values associated with the meta key for querying.
				 *
				 * @return array Filtered meta query.
				 */
				if ( ! empty( $meta_query ) ) {
					$meta_queries[] = apply_filters( 'directorist_custom_fields_meta_query_args', $meta_query, $key, $values );
				}
			}
		}

		if ( ! empty( $_REQUEST['price'] ) ) {
			$price = array_map( 'intval', wp_unslash( $_REQUEST['price'] ) );
			$price = array_filter( $price );

			if ($n = count($price)) {
				if ( 2 == $n ) {
					$meta_queries[] = array(
						'key'     => '_price',
						'value'   => $price,
						'type'    => 'NUMERIC',
						'compare' => 'BETWEEN'
					);
				} else {
					if (empty($price[0])) {
						$meta_queries[] = array(
							'key'     => '_price',
							'value'   => $price[1],
							'type'    => 'NUMERIC',
							'compare' => '<='
						);
					} else {
						$meta_queries[] = array(
							'key'     => '_price',
							'value'   => $price[0],
							'type'    => 'NUMERIC',
							'compare' => '>='
						);
					}
				}
			}
		}

		if ( ! empty( $_REQUEST['price_range'] ) && 'none' !== $_REQUEST['price_range'] ) {
			$meta_queries['_price_range'] = array(
				'key'     => '_price_range',
				'value'   => sanitize_text_field( wp_unslash( $_REQUEST['price_range'] ) ),
				'compare' => 'LIKE'
			);
		}

		if ( ! empty( $_REQUEST['website'] ) ) {
			$meta_queries['_website'] = array(
				'key'     => '_website',
				'value'   => sanitize_text_field( wp_unslash( $_REQUEST['website'] ) ),
				'compare' => 'LIKE'
			);
		}

		if ( ! empty( $_REQUEST['email'] ) ) {
			$meta_queries['_email'] = array(
				'key'     => '_email',
				'value'   => sanitize_text_field( wp_unslash( $_REQUEST['email'] ) ),
				'compare' => 'LIKE'
			);
		}

		if ( ! empty( $_REQUEST['phone'] ) ) {
			$phone = sanitize_text_field( wp_unslash( $_REQUEST['phone'] ) );
			$meta_queries['_phone'] = array(
				'relation' => 'OR',
				array(
					'key'     => '_phone2',
					'value'   => $phone,
					'compare' => 'LIKE'
				),
				array(
					'key'     => '_phone',
					'value'   => $phone,
					'compare' => 'LIKE'
				)
			);
		}

		if ( ! empty( $_REQUEST['fax'] ) ) {
			$meta_queries['_fax'] = array(
				'key'     => '_fax',
				'value'   => sanitize_text_field( wp_unslash( $_REQUEST['fax'] ) ),
				'compare' => 'LIKE'
			);
		}

		if ( 'address' == $this->radius_search_based_on && ! empty( $_REQUEST['miles'] ) && ! empty( $_REQUEST['address'] ) && ! empty( $_REQUEST['cityLat'] ) && ! empty( $_REQUEST['cityLng'] ) ) {
			$args['atbdp_geo_query'] = array(
				'lat_field' => '_manual_lat',
				'lng_field' => '_manual_lng',
				'latitude'  => sanitize_text_field( wp_unslash( $_REQUEST['cityLat'] ) ),
				'longitude' => sanitize_text_field( wp_unslash( $_REQUEST['cityLng'] ) ),
				'distance'  => sanitize_text_field( wp_unslash( $_REQUEST['miles'] ) ),
				'units'     => $this->radius_search_unit
			);
		} elseif ( ! empty($_REQUEST['address']) ) {
			$meta_queries['_address'] = array(
				'key'     => '_address',
				'value'   => sanitize_text_field( wp_unslash( $_REQUEST['address'] ) ),
				'compare' => 'LIKE'
			);
		}

		if ( 'zip' == $this->radius_search_based_on && ! empty( $_REQUEST['miles'] ) && ! empty( $_REQUEST['zip_cityLat'] ) && ! empty( $_REQUEST['zip_cityLng'] ) ) {
			$args['atbdp_geo_query'] = array(
				'lat_field' => '_manual_lat',
				'lng_field' => '_manual_lng',
				'latitude'  => sanitize_text_field( wp_unslash( $_REQUEST['zip_cityLat'] ) ),
				'longitude' => sanitize_text_field( wp_unslash( $_REQUEST['zip_cityLng'] ) ),
				'distance'  => sanitize_text_field( wp_unslash( $_REQUEST['miles'] ) ),
				'units'     => $this->radius_search_unit
			);
		} elseif ( ! empty( $_REQUEST['zip'] ) ) {
			$meta_queries['_zip'] = array(
				'key'     => '_zip',
				'value'   => sanitize_text_field( wp_unslash( $_REQUEST['zip'] ) ),
				'compare' => 'LIKE'
			);
		}

		if ( ! empty( $_REQUEST['search_by_rating'] ) ) {
			$rating_query = directorist_clean( wp_unslash( $_REQUEST['search_by_rating'] ) );
			$meta_queries['_rating'] = array(
				'key'     => directorist_get_rating_field_meta_key(),
				'value'   => $rating_query,
				'type'    => 'NUMERIC',
				'compare' => 'IN'
			);
		}

		$meta_queries = apply_filters( 'atbdp_search_listings_meta_queries', $meta_queries );
		if ( count( $meta_queries ) ) {
			$meta_queries['relation'] = 'AND';
			$args['meta_query'] = $meta_queries;
		}

		return apply_filters( 'atbdp_listing_search_query_argument', $args );
	}

	public function archive_view_template() {
		$template_file = "archive/{$this->view}-view";
		Helper::get_template( $template_file, array( 'listings' => $this ) );
	}

	public function render_shortcode( $atts = [] ) {
		ob_start();

		if (!empty($this->redirect_page_url)) {
			$redirect = '<script>window.location="' . esc_url($this->redirect_page_url) . '"</script>';
			return $redirect;
		}

		if ( $this->logged_in_user_only && ! is_user_logged_in() ) {
			return \ATBDP_Helper::guard([ 'type' => 'auth' ]);
		}

		if ( ! empty( $atts['shortcode'] ) ) {
			Helper::add_shortcode_comment( $atts['shortcode'] );
		}

		switch ( $this->sidebar ) {
			case 'left_sidebar':
				$template = 'sidebar-archive-contents';
				break;
			case 'right_sidebar':
				$template = 'sidebar-archive-contents';
				break;
			case 'no_sidebar':
				$template = 'archive-contents';
				break;
			default :
				$template = 'sidebar-archive-contents';
		}

		// Load the template
		Helper::get_template( $template, array( 'listings' => $this ), 'listings_archive' );

		return ob_get_clean();
	}

	public function have_posts() {
		return !empty( $this->query_results->ids ) ? true : false;
	}

	public function post_ids() {
		return $this->query_results->ids;
	}

	public function has_sidebar() {
		return $this->query_results->ids;
	}

	public function loop_template( $loop = 'grid', $id = NULL ) {
		if ( ! $id ) {
			return;
		}

		_prime_post_caches( $this->post_ids() );

		global $post;
		$post = get_post( $id );
		setup_postdata( $post );

		$this->set_loop_data();

		if ( $loop == 'grid' && !empty( $this->loop['card_fields'] ) ) {
			$active_template = $this->loop['card_fields']['active_template'];
			$template = ( $active_template == 'grid_view_with_thumbnail' && $this->display_preview_image ) ? 'loop-grid' : 'loop-grid-nothumb';
			Helper::get_template( 'archive/' . $template, array( 'listings' => $this ) );
		}
		elseif ( $loop == 'list' && !empty( $this->loop['list_fields'] ) ) {
			$active_template = $this->loop['list_fields']['active_template'];
			$template = ( $active_template == 'list_view_with_thumbnail' && $this->display_preview_image ) ? 'loop-list' : 'loop-list-nothumb';
			Helper::get_template( 'archive/' . $template, array( 'listings' => $this ) );
		}

		wp_reset_postdata();
	}


	public function setup_loop( array $args = [] ) {
		$default = [
			'template' => 'grid'
		];
		$args = array_merge( $default, $args );
		$listings = $this->query_results;

		if ( ! empty( $listings->ids ) ) :
			// Prime caches to reduce future queries.
			if ( ! empty( $listings->ids ) && is_callable( '_prime_post_caches' ) ) {
				_prime_post_caches( $listings->ids );
			}

			$original_post = $GLOBALS['post'];
			$counter = 0;
			foreach ( $listings->ids as $listings_id ) :
				$counter++;
				$GLOBALS['post'] = get_post( $listings_id );
				setup_postdata( $GLOBALS['post'] );
				$this->set_loop_data();

				if ( $args['template'] == 'grid' ) {
					$active_template = $this->loop['card_fields']['active_template'];
					$template = $active_template == 'grid_view_with_thumbnail' ? 'grid' : 'grid-nothumb';
				}
				elseif ( $args['template'] == 'list' ) {
					$active_template = $this->loop['list_fields']['active_template'];
					$template = $active_template == 'list_view_with_thumbnail' ? 'list' : 'list-nothumb';
				}
				else {
					$template = $args['template'];
				}

				Helper::get_template( "archive/fields/" . $template, array('listings' => $this) );
			endforeach;

			$GLOBALS['post'] = $original_post;
            wp_reset_postdata();
		else:
			?><p class="atbdp_nlf"><?php esc_html_e('No listing found.', 'directorist'); ?></p><?php
		endif;
	}

	public function get_view_as_link_list() {
		$link_list = array();
		$view      = ! empty( $this->view ) ? $this->view : '';

		foreach ( $this->views as $value => $label ) {
			$active_class = ( $view === $value ) ? 'active' : '';
			$link         = add_query_arg( 'view', $value );
			$link_item    = array();

			$link_item['active_class'] = $active_class;
			$link_item['link']         = $link;
			$link_item['label']        = $label;

			array_push( $link_list, $link_item );
		}

		return $link_list;
	}

	public function get_dropdown_toggle_button_icon_class() {
		_deprecated_function( __METHOD__, '7.3.1' );
	}

	public function dropdown_toggle_button_icon_class() {
		_deprecated_function( __METHOD__, '7.3.1' );
	}

	public function get_sort_by_link_list() {
		$link_list = array();

		$options       = atbdp_get_listings_orderby_options( $this->sort_by_items );
		$queryString = isset( $_SERVER['QUERY_STRING'] ) ? sanitize_text_field( wp_unslash( $_SERVER['QUERY_STRING'] ) ) : '';
		parse_str($queryString, $arguments);
		$actual_link = !empty( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
		foreach ( $options as $value => $label ) {
			$arguments['sort'] 		   = $value;

			$link_item['link']         = add_query_arg( $arguments, $actual_link );
			$link_item['label']        = $label;
			$link_item['key']          = $value;

			array_push( $link_list, $link_item );
		}

		return $link_list;
	}

	public function get_listing_types() {
		$args = array();

		if ( $this->directory_type ) {
			$args['slug']    = $this->directory_type;
			$args['orderby'] = 'slug__in';
		}

		return directorist_get_directories_for_template( apply_filters( 'directorist_all_listings_directory_type_args', $args ) );
	}

	public function get_current_listing_type() {
		$directory = 0;

		if ( is_singular( ATBDP_POST_TYPE ) ) {
			$directory = get_post_meta( get_the_ID(), '_directory_type', true );
		} else if ( ! empty( $_REQUEST['directory_type'] ) ) {
			$directory = sanitize_text_field( wp_unslash( $_REQUEST['directory_type'] ) );
		} else if ( ! empty( $this->default_directory_type ) ) {
			$directory = $this->default_directory_type;
		} else if ( ! empty( $this->directory_type ) ) {
			$directory = array_key_first( $this->get_listing_types() );
		}

		if ( ! is_numeric( $directory ) ) {
			$directory_term = get_term_by( 'slug', $directory, ATBDP_DIRECTORY_TYPE );
			$directory      = $directory_term ? $directory_term->term_id : 0;
		}

		if ( directorist_is_directory( $directory ) ) {
			return (int) $directory;
		}

		return directorist_get_default_directory();
	}

	public function get_directory_type_slug() {
		$current_directory_type = $this->get_current_listing_type();

		if ( is_numeric( $current_directory_type ) ) {
			$term                   = get_term_by( 'id', $current_directory_type, ATBDP_TYPE) ;
			$current_directory_type = $term->slug;
		}

		return $current_directory_type;
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
			'listing_type'		 => $this->listing_types
		);
	}

	public function render_map() {
		if ( 'google' == $this->select_listing_map ) {
			$this->load_google_map();
		}
		else {
			$this->load_openstreet_map();
		}
	}

	public function map_base_lat_long() {
		$ids = $this->post_ids();

		if ( !empty( $ids ) ) {
			$id = $ids[0];
			$lat_long = [
				'latitude'  => get_post_meta( $id, '_manual_lat', true ),
				'longitude' => get_post_meta( $id, '_manual_lng', true ),
			];
		} else {
			$lat_long = [
				'latitude'  => get_directorist_option( 'default_latitude', 40.7127753 ),
				'longitude' => get_directorist_option( 'default_longitude', -74.0059728 ),
			];
		}

		return $lat_long;
	}

	public function map_options() {
		$data = [
			'map_type'                => $this->select_listing_map,
			'crop_width'              => get_directorist_option( 'crop_width', 360 ),
			'crop_height'             => get_directorist_option( 'crop_height', 360 ),
			'display_map'             => get_directorist_option( 'display_map_info', true ),
			'display_image'           => get_directorist_option( 'display_image_map', true ),
			'display_title'           => get_directorist_option( 'display_title_map', true ),
			'display_address'         => get_directorist_option( 'display_address_map', true ),
			'display_direction'       => get_directorist_option( 'display_direction_map', true ),
			'display_favorite_badge_map'    => get_directorist_option( 'display_favorite_badge_map', true ),
			'display_user_avatar_map'       => get_directorist_option( 'display_user_avatar_map', true ),
			'display_review_map'      => get_directorist_option( 'display_review_map', true ),
			'display_price_map'       => get_directorist_option( 'display_price_map', true ),
			'display_phone_map'       => get_directorist_option( 'display_phone_map', true ),
			'zoom_level'              => $this->map_zoom_level,
			'default_image'           => get_directorist_option( 'default_preview_image', DIRECTORIST_ASSETS . 'images/grid.jpg' ),
			'base_latitude'           => $this->map_base_lat_long()['latitude'],
			'base_longitude'          => $this->map_base_lat_long()['longitude'],
			'default_latitude'        => get_directorist_option( 'default_latitude', 40.7127753 ),
			'default_longitude'       => get_directorist_option( 'default_longitude', -74.0059728 ),
			'force_default_location'  => get_directorist_option( 'use_def_lat_long', true ),
			'disable_single_listing'  => $this->disable_single_listing,
			'openstreet_script'       => DIRECTORIST_VENDOR_JS . 'openstreet-map/subGroup-markercluster-controlLayers-realworld.388.js?ver=' . DIRECTORIST_SCRIPT_VERSION,
		];

		return $data;
	}

	public function load_openstreet_map() {
		$card = json_encode( $this->openstreet_map_card_data() );
		$options = json_encode( $this->map_options() );
		$style = 'height:' . $this->listings_map_height . 'px';
		?>
		<div id="map" style="<?php echo esc_attr( $style ); ?>" data-card="<?php echo directorist_esc_json( $card ); ?>" data-options="<?php echo directorist_esc_json( $options ); ?>">
			<div id="gmap_full_screen_button">
				<span class="fullscreen-enable"><?php directorist_icon( 'fas fa-expand' ); ?></span>
				<span class="fullscreen-disable"><?php directorist_icon( 'fas fa-compress' ); ?></span>
			</div>
		</div>
		<?php
	}

	public function get_map_options() {
		$opt['select_listing_map']    		= $this->select_listing_map;
		$opt['crop_width']            		= $this->options['crop_width'];
		$opt['crop_height']           		= $this->options['crop_height'];
		$opt['display_map_info']      		= $this->options['display_map_info'];
		$opt['display_image_map']     		= $this->options['display_image_map'];
		$opt['display_title_map']     		= $this->options['display_title_map'];
		$opt['display_address_map']   		= $this->options['display_address_map'];
		$opt['display_direction_map'] 		= $this->options['display_direction_map'];
		$opt['display_favorite_badge_map'] 	= $this->options['display_favorite_badge_map'];
		$opt['display_user_avatar_map'] 	= $this->options['display_user_avatar_map'];
		$opt['display_review_map'] 			= $this->options['display_review_map'];
		$opt['display_price_map'] 			= $this->options['display_price_map'];
		$opt['display_phone_map'] 			= $this->options['display_phone_map'];
		$opt['zoom']                  		= $this->map_zoom_level;
		$opt['default_image']         		= $this->options['default_preview_image'];
		$opt['default_lat']           		= $this->options['default_latitude'];
		$opt['default_long']          		= $this->options['default_longitude'];
		$opt['use_def_lat_long']   			= $this->options['use_def_lat_long'];

		$opt['disable_single_listing'] = $this->disable_single_listing;

		$map_is_disabled = ( empty( $opt['display_map_info'] ) && ( empty($opt['display_image_map'] ) || empty( $opt['display_title_map'] ) || empty( $opt['display_address_map'] ) || empty( $opt['display_direction_map'] ) || empty( $opt['display_favorite_badge_map'] ) || empty( $opt['display_user_avatar_map'] ) || empty( $opt['display_review_map'] ) || empty( $opt['display_price_map'] ) || empty( $opt['display_phone_map'] ) ) ) ? true : false;
		$opt['map_is_disabled'] = $map_is_disabled;

		return apply_filters( 'atbdp_map_options', $opt );
	}

	public function loop_map_cat_icon() {
		$cats = get_the_terms( get_the_ID(), ATBDP_CATEGORY );

		$cat_icon = '';

		if ( !empty( $cats ) ) {
			$cat_icon = get_term_meta( $cats[0]->term_id, 'category_icon', true );
		}

		$cat_icon = !empty( $cat_icon ) ? $cat_icon : 'fas fa-map-pin';
		/**
		 * Get category icon for map marker.
		 *
		 * @since 7.3.1
		 *
		 * @param array $cats Categories for the post.
		 * @param string $cat_icon First category
		 *
		 * @return string CATEGORY ICON NAME
		 */
		return apply_filters( "directorist_listings_map_view_marker_icon", $cat_icon, $cats );
	}

	public function openstreet_map_card_data() {
		$opt = $this->get_map_options();

		$lat_lon = [];

		$map_data = [];

		$listings = $this->query_results;

		if ( ! empty( $listings->ids ) ) :
			// Prime caches to reduce future queries.
			if ( ! empty( $listings->ids ) && is_callable( '_prime_post_caches' ) ) {
				_prime_post_caches( $listings->ids );
			}

			$original_post = ! empty( $GLOBALS['post'] ) ? $GLOBALS['post'] : '';

			foreach ( $listings->ids as $listings_id ) :
				$GLOBALS['post'] = get_post( $listings_id );
				setup_postdata( $GLOBALS['post'] );
				$this->set_loop_data();
				$ls_data = [];

				$ls_data['manual_lat']      = get_post_meta($listings_id, '_manual_lat', true);
				$ls_data['manual_lng']      = get_post_meta($listings_id, '_manual_lng', true);
				$ls_data['listing_img']     = get_post_meta($listings_id, '_listing_img', true);
				$ls_data['listing_prv_img'] = get_post_meta($listings_id, '_listing_prv_img', true);
				$ls_data['address']         = get_post_meta($listings_id, '_address', true);
				$ls_data['phone'] 			= get_post_meta($listings_id, '_phone', true);
				$ls_data['font_type']       = $this->options['font_type'];
				$ls_data['listings']        = $this;

				$lat_lon = [
					'lat' => $ls_data['manual_lat'],
					'lon' => $ls_data['manual_lng']
				];

				$ls_data['lat_lon'] = $lat_lon;

				if ( ! empty( $ls_data['listing_prv_img']) ) {
					$ls_data['prv_image'] = atbdp_get_image_source( $ls_data['listing_prv_img'], 'large' );
				}

				$listing_type  				= get_post_meta( $listings_id, '_directory_type', true );
				$ls_data['default_image'] 	= Helper::default_preview_image_src( $listing_type );

				if ( ! empty( $ls_data['listing_img'][0] ) ) {
					$ls_data['gallery_img'] = atbdp_get_image_source($ls_data['listing_img'][0], 'medium');
				}

				$cat_icon = directorist_icon( $this->loop_map_cat_icon(), false );
				$ls_data['cat_icon'] = $cat_icon;

				$opt['ls_data'] = $ls_data;

				$map_data[] = [
					'content'   => Helper::get_template_contents( 'archive/fields/openstreet-map', $opt ),
					'latitude'  => get_post_meta( $listings_id, '_manual_lat', true ),
					'longitude' => get_post_meta( $listings_id, '_manual_lng', true ),
					'cat_icon'  => $cat_icon,
				];

			endforeach;

			$GLOBALS['post'] = $original_post;
			wp_reset_postdata();
		endif;

		return $map_data;
	}

	public function load_google_map() {
		$opt = $this->get_map_options();
		$disable_info_window = 'no';

		if (empty($opt['display_map_info'])) {
			$disable_info_window = 'yes';
		}
		elseif ( empty($opt['display_image_map'] || $opt['display_title_map'] || $opt['display_address_map'] || $opt['display_direction_map'] || $opt['display_favorite_badge_map'] || $opt['display_user_avatar_map'] || $opt['display_review_map'] || $opt['display_price_map'] || $opt['display_phone_map'] ) ){
			$disable_info_window = 'yes';
		}

		$data = array(
			'plugin_url'          => ATBDP_URL,
			'disable_info_window' => $disable_info_window,
			'zoom'                => $opt['zoom'],
			'default_latitude'    => $this->options['default_latitude'],
			'default_longitude'   => $this->options['default_longitude'],
			'use_def_lat_long'   => $this->options['use_def_lat_long'],
		);

		Helper::add_hidden_data_to_dom( 'atbdp_map', $data );
		$map_height = !empty( $this->listings_map_height ) ? $this->listings_map_height: '';
		?>
		<div class="atbdp-body atbdp-map embed-responsive embed-responsive-16by9 atbdp-margin-bottom" data-type="<?php echo $this->options['marker_clustering']; ?>" style="height: <?php echo esc_attr( $map_height );?>px;">
			<?php
			$listings = $this->query_results;

			if ( ! empty( $listings->ids ) ) :
				// Prime caches to reduce future queries.
				if ( ! empty( $listings->ids ) && is_callable( '_prime_post_caches' ) ) {
					_prime_post_caches( $listings->ids );
				}

				$original_post = ! empty( $GLOBALS['post'] ) ? $GLOBALS['post'] : '';

				foreach ( $listings->ids as $listings_id ) :
					$GLOBALS['post'] = get_post( $listings_id );
					setup_postdata( $GLOBALS['post'] );
					$this->set_loop_data();
					$ls_data = [];

					$ls_data['post_id']         = $listings_id;
					$ls_data['manual_lat']      = get_post_meta($listings_id, '_manual_lat', true);
					$ls_data['manual_lng']      = get_post_meta($listings_id, '_manual_lng', true);
					$ls_data['listing_img']     = get_post_meta($listings_id, '_listing_img', true);
					$ls_data['listing_prv_img'] = get_post_meta($listings_id, '_listing_prv_img', true);
					$ls_data['phone'] 			= get_post_meta($listings_id, '_phone', true);
					$ls_data['crop_width']      = $this->options['crop_width'];
					$ls_data['crop_height']     = $this->options['crop_height'];
					$ls_data['address']         = get_post_meta($listings_id, '_address', true);
					$ls_data['font_type']       = $this->options['font_type'];
					$ls_data['fa_or_la']        = ('line' === $ls_data['font_type']) ? "la " : "fa ";
					$ls_data['cats']            = get_the_terms($listings_id, ATBDP_CATEGORY);
					$ls_data['listings']        = $this;

					$cat_icon = directorist_icon( $this->loop_map_cat_icon(), false );
					$ls_data['cat_icon'] = json_encode( $cat_icon );

					$listing_type  			= get_post_meta( $listings_id, '_directory_type', true );
					$ls_data['default_img'] = Helper::default_preview_image_src( $listing_type );

					if (!empty($ls_data['listing_prv_img'])) {
						$ls_data['prv_image']   = atbdp_get_image_source($ls_data['listing_prv_img'], 'large');
					}

					if (!empty($ls_data['listing_img'][0])) {
						$ls_data['gallery_img'] = atbdp_get_image_source($ls_data['listing_img'][0], 'medium');
					}

					if ( ! empty( $ls_data['manual_lat'] ) && ! empty( $ls_data['manual_lng'] ) ) {
						$opt['ls_data'] = $ls_data;
						Helper::get_template( 'archive/fields/google-map', $opt );
					}

				endforeach;

				$GLOBALS['post'] = $original_post;
				wp_reset_postdata();
			endif;
			echo "</div>";
		}

		public function get_favorite_badge() {
			Helper::get_template( 'archive/fields/favorite_badge', array( 'listings' => $this ) );
		}

		public function get_user_avatar() {
			Helper::get_template( 'archive/fields/user_avatar', array( 'listings' => $this ) );
		}

		public function get_listing_review() {
			Helper::get_template( 'archive/fields/rating', array( 'listings' => $this ) );
		}

		public function get_price() {
			Helper::get_template( 'archive/fields/pricing', array( 'listings' => $this ) );
		}

		protected function cache_thumbnails() {
			if ( $this->thumbnails_cached || empty( $this->query_results->ids ) ) {
				return;
			}

			$thumb_ids = array();
			foreach ( $this->query_results->ids as $id ) {
				$id = directorist_get_listing_thumbnail_id( $id );
				if ( $id ) {
					$thumb_ids[] = $id;
				}
			}

			if ( ! empty( $thumb_ids ) ) {
				_prime_post_caches( $thumb_ids, false, true );
			}

			$this->thumbnails_cached = true;
		}

		function loop_get_the_thumbnail( $class = '' ) {
			$default_image_src = Helper::default_preview_image_src( $this->current_listing_type );

			$id = get_the_ID();
			$image_quality     = get_directorist_option('preview_image_quality', 'directorist_preview');
			$listing_prv_img   = get_post_meta($id, '_listing_prv_img', true);
			$listing_img       = get_post_meta($id, '_listing_img', true);



			$thumbnail_img_id  = array_filter( array_merge( (array) $listing_prv_img, (array) $listing_img ) );
			$link_start       = '<a href="'. esc_url( $this->loop['permalink'] ) .'"><figure>';
			$link_end         = '</figure></a>';

			if ( empty( $thumbnail_img_id ) ) {
				$thumbnail_img_id = $default_image_src;
				$image_alt        = esc_html( get_the_title( $id ) );
				$image            = "<img src='$default_image_src' alt='$image_alt' class='$class' />";
				if ( ! $this->disable_single_listing ) {
					$image = $link_start . $image . $link_end;
				}
				return $image;
			}
			
			$thumbnail_img_id = array_filter($thumbnail_img_id, function($value) {
				return is_numeric($value);
			});
			
			$image_count = count( $thumbnail_img_id );

			if ( 1 === (int) $image_count ) {
				$image_src  = atbdp_get_image_source( reset( $thumbnail_img_id ), $image_quality );
				$image_alt  = get_post_meta( reset( $thumbnail_img_id ), '_wp_attachment_image_alt', true );
				$image_alt  = ( ! empty( $image_alt ) ) ? esc_attr( $image_alt ) : esc_html( get_the_title( reset( $thumbnail_img_id ) ) );
				$image      = "<img src='$image_src' alt='$image_alt' class='$class' />";
				if ( ! $this->disable_single_listing ) {
					$image = $link_start . $image . $link_end;
				}
				return $image;
			} else {
				$output = "<div class='directorist-swiper directorist-swiper-listing' data-sw-items='1' data-sw-margin='2' data-sw-loop='true' data-sw-perslide='1' data-sw-speed='500' data-sw-autoplay='false' data-sw-responsive='{}'>
					<div class='swiper-wrapper'>";

				foreach ( $thumbnail_img_id as $img_id ) {

					$image_src = atbdp_get_image_source( $img_id, $image_quality );
					$image_alt = get_post_meta( $img_id, '_wp_attachment_image_alt', true );
					$image_alt = ! empty( $image_alt ) ? esc_attr( $image_alt ) : esc_html( get_the_title( $img_id ) );
					$image = "<img src='$image_src' alt='$image_alt' class='$class' />";

					if ( ! $this->disable_single_listing ) {
						$image = $link_start . $image . $link_end;
					}

					$output .= "<div class='swiper-slide'>$image</div>";
				}

				$output .= "</div>
					<div class='directorist-swiper__navigation'>
						<div class='directorist-swiper__nav directorist-swiper__nav--prev directorist-swiper__nav--prev-listing'>" . directorist_icon( 'las la-angle-left', false ) . "</div>
						<div class='directorist-swiper__nav directorist-swiper__nav--next directorist-swiper__nav--next-listing'>" . directorist_icon( 'las la-angle-right', false ) . "</div>
					</div>

					<div class='directorist-swiper__pagination directorist-swiper__pagination--listing'></div>
				</div>";

				return $output;

			}
		}

		public function loop_thumb_card_template() {
			Helper::get_template( 'archive/fields/thumb-card', array('listings' => $this) );
		}

		public function loop_get_published_date( $data ) {
			$publish_date_format = $data['date_type'];
			if ('days_ago' === $publish_date_format) {
				$text = sprintf(__('Posted %s ago', 'directorist'), human_time_diff(get_the_time('U'), current_time('timestamp')));
			}
			else {
				$text = get_the_date();
			}
			return $text;
		}

		public function loop_get_title() {
			if ( ! $this->disable_single_listing ) {
				$title = sprintf('<a href="%s"%s>%s</a>', apply_filters( 'directorist_archive_single_listing_url', $this->loop['permalink'], $this->loop['id'], 'title' ), $this->loop_link_attr(), $this->loop['title']);
			}
			else {
				$title = $this->loop['title'];
			}
			return $title;
		}

		public function loop_get_tagline() {
			return $this->loop['tagline'];
		}

		public function loop_is_favourite() {
			$favourites = directorist_get_user_favorites( get_current_user_id() );
			return in_array( get_the_id() , $favourites );
		}

		/**
		 * Unused method
		 *
		 * @todo remove
		 *
		 * @return string
		 */
		public function item_found_title_for_search($count) {
			$cat_name = $loc_name = '';

			if ( isset($_GET['in_cat'] ) ) {
				$cat_id = intval($_GET['in_cat']);
				$cat = get_term_by('id', $cat_id, ATBDP_CATEGORY);
				if ( $cat ) {
					$cat_name = $cat->name;
				}
			}

			if ( isset($_GET['in_loc'] ) ) {
				$loc_id = intval($_GET['in_cat']);
				$loc = get_term_by('id', $loc_id, ATBDP_LOCATION);
				if ( $loc ) {
					$loc_name = $loc->name;
				}
			}
			elseif ( isset($_GET['address'] ) ) {
				$loc_name = sanitize_text_field( wp_unslash( $_GET['address'] ) );
			}

			if ( $cat_name && $loc_name ) {
				$title = sprintf( _nx( '%s result for %s in %s', '%s results for %s in %s', $count, 'search result header', 'directorist' ), $count, $cat_name, $loc_name );
			}
			elseif ( $cat_name ) {
				$title = sprintf( _nx( '%s result for %s', '%s results for %s', $count, 'search result header', 'directorist' ), $count, $cat_name );
			}
			elseif ( $loc_name ) {
				$title = sprintf( _nx( '%s result in %s', '%s results in %s', $count, 'search result header', 'directorist' ), $count, $loc_name );
			}
			else {
				$title = sprintf( _nx( '%s result', '%s results', $count, 'search result header', 'directorist' ), $count );
			}

			if ( ! empty( $this->header_title ) ) {
				$title = sprintf('<span>%s</span> %s', $count, $this->header_title);
			}

			return $title;
		}

		public function item_found_title() {
			$count = $this->query_results->total;
			$title = sprintf('<span>%s</span> %s', $count, $this->header_title );
			return apply_filters('directorist_listings_found_text', $title );
		}

		public function has_masonry() {
			return ( $this->view_as == 'masonry_grid' ) ? true : false;
		}

		public function masonary_grid_attr() {
			return ($this->view_as !== 'masonry_grid') ? '' : ' data-uk-grid';
		}

		public function grid_view_class() {
			return $this->view_as == 'masonry_grid' ? 'directorist-grid-masonary' : 'directorist-grid-normal';
		}

		public function get_the_location() {
			return get_the_term_list( get_the_ID(), ATBDP_LOCATION, '', ', ', '' );
		}

		public function loop_wrapper_class() {
			$class  = [];

			if ( $this->loop['featured'] ) {
				$class[] = 'directorist-featured';
			}

			if ( $this->info_display_in_single_line ) {
				$class[] = 'directorist-single-line';
			}

			$class  = apply_filters( 'directorist_loop_wrapper_class', $class, $this->current_listing_type );

			return implode( ' ' , $class );
		}

		/**
		 * Displays the class names for the listings wrapper element.
		 *
		 * @since 7.2.0
		 *
		 * @param string|string[] $class Space-separated string or array of class names to add to the class list.
		 */
		public function wrapper_class( $class = '' ) {
			// Separates class names with a single space, collates class names for wrapper tag element.
			echo 'class="' . esc_attr( implode( ' ', $this->get_wrapper_class( $class ) ) ) . '"';
		}

		/**
		 * Retrieves an array of the class names for the listings wrapper element.
		 *
		 * @since 7.2.0
		 *
		 * @param string|string[] $class Space-separated string or array of class names to add to the class list.
		 * @return string[] Array of class names.
		 */
		public function get_wrapper_class( $class = '' ) {
			$classes = array(
				'directorist-archive-contents directorist-contents-wrap directorist-w-100',
			);

			if ( 'yes' === $this->instant_search ) {
				$classes[] = 'directorist-instant-search';
			}

			if ( ! empty( $class ) ) {
				if ( ! is_array( $class ) ) {
					$class = preg_split( '#\s+#', $class );
				}
				$classes = array_merge( $classes, $class );
			} else {
				// Ensure that we always coerce class to being an array.
				$class = array();
			}

			$classes = array_map( 'esc_attr', $classes );

			/**
			 * Filters the list of CSS listings wrapper class names for the wrapper.
			 *
			 * @since 7.2.0
			 *
			 * @param string[] $classes An array of listings wrapper class names.
			 * @param string[] $class   An array of additional class names added to the listings wrapper.
			 * @param object   $this    An instantce of Directorist_Listings
			 */
			$classes = apply_filters( 'directorist_listings_wrapper_class', $classes, $class, $this );

			return array_unique( $classes );
		}

		public function data_atts() {
			$this->atts['_current_page'] = $this->type; // search_result or listing
			$this->atts['category_custom_fields_relations'] = directorist_get_category_custom_field_relations( $this->current_listing_type );
			// Separates class names with a single space, collates class names for wrapper tag element.
			echo 'data-atts="' . esc_attr( json_encode( $this->atts ) ) . '"';
		}

		public function loop_link_attr() {
			$attr = " " . apply_filters('grid_view_title_link_add_attr', '');
			return trim($attr);
		}

		public function loop_thumbnail_link_attr() {
			return trim( ' ' . apply_filters( 'grid_view_thumbnail_link_add_attr', '' ) );
		}

		public function loop_title_link_attr() {
			return trim( ' ' . apply_filters( 'grid_view_title_link_add_attr', '' ) );
		}

		public function header_container_class() {
			_deprecated_function( __METHOD__, '7.3.1' );
		}

		public function has_listings_header() {
			$has_filter_button = ( ! empty( $this->listing_filters_button ) && ! empty( $this->search_more_filters_fields ) );

			return ( $has_filter_button || ! empty( $this->header_title ) ) ? true : false;
		}

		public function has_header_toolbar() {
			return ( $this->display_viewas_dropdown || $this->display_sortby_dropdown ) ? true : false;
		}

		public function render_card_field( $field, $before = '', $after = '' ) {
			if ( $field['type'] == 'badge' ) {
				$this->render_badge_template($field);
			} else {
				$original_field         = '';
				$submission_form_fields = get_term_meta( $this->current_listing_type, 'submission_form_fields', true );

				if ( isset( $field['original_widget_key'] ) && isset( $submission_form_fields['fields'][$field['original_widget_key']] ) ) {
					$original_field = $submission_form_fields['fields'][$field['original_widget_key']];
				}

				if ( ! empty( $original_field ) ) {
					$field['original_field'] = $original_field;
				}

				$load_template = true;

				if ( ! empty( $original_field['field_key'] ) ) {
					$meta_field_key = $original_field['field_key'];
				} else if ( ! empty( $field['field_key'] ) ) {
					$meta_field_key = $field['field_key'];
				} else {
					$meta_field_key = $field['widget_key'];
				}

				$value = get_post_meta( get_the_id(), '_' . $meta_field_key, true );
				if ( empty( $value ) ) {
					$value = get_post_meta( get_the_id(), $meta_field_key, true );
				}

				if ( 'listings_location' === $field['widget_name'] ) {
					$location = get_the_terms( get_the_id(), ATBDP_LOCATION );
					if ( ! is_wp_error( $location ) && ! empty( $location ) ) {
						$value = true;
					}
				}

				// If $load_template is false then what's the point going forward? why don't we return here!
				if ( ! $value && $field['type'] === 'list-item' && 'posted_date' !== $field['widget_name'] ) {
					return; // Return early, return wisely.
					$load_template = false;
				}

				$args = array(
					'listings'       => $this,
					'post_id'        => get_the_id(),
					'data'           => $field,
					'value'          => $value,
					'label'          => ( ! empty( $field['show_label'] ) ? $field['label'] : '' ),
					'icon'           => directorist_get_var( $field['icon'] ),
					'original_field' => $submission_form_fields,
					'before'		 => $before,
					'after'		 	 => $after,
				);

				// Didn't find any $data within this method.
				// if ( isset( $data['original_field'] ) && isset( $data['original_field']['widget_name'] ) ) {
				// 	$widget_name = $data['original_field']['widget_name'];
				// }

				$widget_name = $field['widget_name'];
				if ( $this->is_custom_field( $field ) ) {
					$field_type = directorist_get_var( $field['original_field']['type'] );

					if ( 'checkbox' === $field_type ) {
						if ( ! is_array( $value ) ) {
							$value = array_filter( explode( ',', $value ) );
						}

						$options_value = '';
						$options       = (array) directorist_get_var( $field['original_field']['options'], array() );
						foreach ( $options as $option ) {
							if ( in_array( $option['option_value'], $value, true ) ) {
								$options_value .= $option['option_label'] . ', ';
							}
						}

						$args['value'] = rtrim( $options_value, ', ' );
					}

					$template = 'archive/custom-fields/' . $widget_name;
				} else {
					$template = 'archive/fields/' . $widget_name;
				}

				if ( $load_template ) {
					// Print $before and $after here so that empty li or other wrapper tags are not printed.
					//echo wp_kses_post( $before );
					Helper::get_template( $template, $args );
					//echo wp_kses_post( $after );
				}
			}
		}

		public function is_custom_field( $data ) {
			$fields      = [ 'checkbox', 'color_picker', 'date', 'file', 'number', 'radio', 'select', 'text', 'textarea', 'time', 'url' ];
			$widget_name = $data['widget_name'];

			if ( isset( $data['original_field'] ) && isset( $data['original_field']['widget_name'] ) ) {
				$widget_name = $data['original_field']['widget_name'];
			}

			return in_array( $widget_name, $fields, true );
		}

		public function has_whatsapp( $data ) {
			if ( !empty( $data['original_field']['whatsapp'] ) ) {
				return true;
			}
			else {
				return false;
			}
		}

		public function print_label( $label ) {
			if ( $label ) {
				$label_text = $label . ': ';
				$label_text = apply_filters( 'directorist_loop_label', $label_text, $label );
				echo wp_kses_post( $label_text );
			}
			else {
				return;
			}
		}

		public function render_loop_fields( $fields, $before = '', $after = '' ) {
			if ( ! empty( $fields ) ) {
				foreach ( $fields as $field ) {
					$this->render_card_field( $field, $before, $after );
				}
			}
		}

		public function render_badge_template( $field ) {
			global $post;
			$id = get_the_ID();

			// for development purpose
			do_action( 'atbdp_all_listings_badge_template', $field );

			switch ($field['widget_key']) {
				case 'popular_badge':
				$field['class'] = 'popular';
				$field['label'] = Helper::popular_badge_text();
				if ( Helper::is_popular( $id ) ) {
					Helper::get_template( 'archive/fields/badge', $field );
				}
				break;

				case 'featured_badge':
				$field['class'] = 'featured';
				$field['label'] = Helper::featured_badge_text();
				if ( Helper::is_featured( $id ) ) {
					Helper::get_template( 'archive/fields/badge', apply_filters( 'directorist_featured_badge_field_data', $field ) );
				}
				break;

				case 'new_badge':
				$field['class'] = 'new';
				$field['label'] = Helper::new_badge_text();
				if ( Helper::is_new( $id ) ) {
					Helper::get_template( 'archive/fields/badge', $field );
				}
				break;
			}

		}

		public function listing_wrapper_class() {
			echo ($this->loop['featured']) ? 'directorist-featured-listings' : '';
		}

		public function grid_container_fluid() {
			$container = is_directoria_active() ? 'container' : 'container-fluid';
			return apply_filters( 'atbdp_listings_grid_container_fluid', $container );
		}

		public function sidebar_class() {
			$class = 'no-sidebar-contents';

			if ( $this->sidebar ) {

				switch ( $this->sidebar ) {
					case 'left_sidebar':
						$class = 'left-sidebar-contents';
						break;
					case 'right_sidebar':
						$class = 'right-sidebar-contents';
						break;
				}

			}

			return $class;
		}

		public function hide_top_search_bar_on_sidebar_layout() {
			return $this->options['listing_sidebar_top_search_bar'] ? $this->options['listing_sidebar_top_search_bar'] : false;
		}

		public function sortby_dropdown_template() {
			Helper::get_template( 'archive/sortby-dropdown', array( 'listings' => $this ) );
		}

		public function viewas_dropdown_template() {
			Helper::get_template( 'archive/viewas-dropdown', array( 'listings' => $this ) );
		}

		public function display_search_button() {
			// Check if the layout is 'no_sidebar', in which case the button should always be displayed
			if ( $this->options['all_listing_layout'] === 'no_sidebar' ) {
				return true;
			}

			// If the layout is 'right_sidebar' or 'left_sidebar' and instant search is disabled, display the button
			if ( in_array( $this->options['all_listing_layout'], ['right_sidebar', 'left_sidebar'] )
				&& empty( $this->options['listing_instant_search'] ) ) {
				return true;
			}

			// In all other cases, don't display the button
			return false;
		}

		public function search_form_template() {
			// only catch atts with the prefix 'filter_'
			$search_field_atts = array_filter( $this->atts, function( $key ) {
				return substr( $key, 0, 7 ) == 'filter_';
			}, ARRAY_FILTER_USE_KEY );

			$args = array(
				'listings'   => $this,
				'searchform' => new Directorist_Listing_Search_Form( $this->type, $this->current_listing_type, $search_field_atts ),
			);
			Helper::get_template( 'archive/search-form', $args );
		}

		public function basic_search_form_template() {
			// only catch atts with the prefix 'filter_'
			$search_field_atts = array_filter( $this->atts, function( $key ) {
				return substr( $key, 0, 7 ) == 'filter_';
			}, ARRAY_FILTER_USE_KEY );

			$args = array(
				'listings'   => $this,
				'searchform' => new Directorist_Listing_Search_Form( 'search_result', $this->current_listing_type, $search_field_atts ),
			);
			Helper::get_template( 'archive/basic-search-form', $args );
		}

		public function advance_search_form_template() {
			// only catch atts with the prefix 'filter_'
			$search_field_atts = array_filter( $this->atts, function( $key ) {
				return substr( $key, 0, 7 ) == 'filter_';
			}, ARRAY_FILTER_USE_KEY );

			$args = array(
				'listings'   => $this,
				'searchform' => new Directorist_Listing_Search_Form( 'search_result', $this->current_listing_type, $search_field_atts ),
			);
			Helper::get_template( 'archive/advance-search-form', $args );
		}

		public function filter_btn_html() {
			if ( $this->has_filters_icon ) {
				return sprintf( '%s %s', directorist_icon( 'las la-filter', false ), $this->filter_button_text );
			}
			else {
				return $this->filter_button_text;
			}
		}

		public function mobile_view_filter_template() {
			// only catch atts with the prefix 'filter_'
			$search_field_atts = array_filter( $this->atts, function( $key ) {
				return substr( $key, 0, 7 ) == 'filter_';
			}, ARRAY_FILTER_USE_KEY );

			$args = array(
				'listings'   => $this,
				'searchform' => new Directorist_Listing_Search_Form( $this->type, $this->current_listing_type, $search_field_atts ),
			);
			Helper::get_template( 'archive/mobile-search-form', $args );
		}

		public function directory_type_nav_template() {
			if ( count( $this->listing_types ) > 1 && directorist_is_multi_directory_enabled() ) {
				Helper::get_template( 'archive/directory-type-nav', array('listings' => $this) );
			}
		}

		public function header_bar_template() {
			if ( !$this->header ) {
				return;
			}

			Helper::get_template( 'archive/header-bar', array('listings' => $this) );
		}

		public function listings_header_title() {
			$html = "<span class='directorist-header-found-title'>" . wp_kses_post( $this->item_found_title() ) . "</span>";
			$html = apply_filters( 'directorist_listings_header_title', $html );

			return $html;
		}

		public function full_search_form_template() {
			// only catch atts with the prefix 'filter_'
			$search_field_atts = array_filter( $this->atts, function( $key ) {
				return substr( $key, 0, 7 ) == 'filter_';
			}, ARRAY_FILTER_USE_KEY );

			$args = array(
				'listings'   => $this,
				'searchform' => new Directorist_Listing_Search_Form( $this->type, $this->current_listing_type, $search_field_atts ),
			);

			Helper::get_template( 'archive/search-form', $args );
		}

		public function single_line_display_class() {
			return $this->info_display_in_single_line ? 'directorist-single-line' : '';
		}

		public function pagination( $echo = true ) {
			$navigation = '';
			$paged = 1;
			$largeNumber = 999999999;

			$total = ( isset( $this->query_results->total_pages ) ) ? $this->query_results->total_pages : $this->query_results->max_num_pages;
			$paged = ( isset( $this->query_results->current_page ) ) ? $this->query_results->current_page : $paged;

			$links = paginate_links(array(
				'base'      => str_replace($largeNumber, '%#%', esc_url(get_pagenum_link($largeNumber))),
				'format'    => '?paged=%#%',
				'current'   => max(1, $paged),
				'total'     => $total,
				'prev_text' => apply_filters('directorist_pagination_prev_text', directorist_icon( 'fas fa-chevron-left', false )),
				'next_text' => apply_filters('directorist_pagination_next_text', directorist_icon( 'fas fa-chevron-right', false )),
			));

			if ( $links ) {
				$navigation = '<nav class="directorist-pagination" aria-label="Listings Pagination">'.$links.'</nav>';
			}


			$result = apply_filters('directorist_pagination', $navigation, $links, $this->query_results, $paged );

			if ( $echo ) {
				echo wp_kses_post( $result );
			}
			else {
				return $result;
			}
		}

    	// Hooks ------------
		public static function archive_type($listings) {
			if ( count( $listings->listing_types ) > 1 && directorist_is_multi_directory_enabled() ) {
				Helper::get_template( 'archive/listing-types', array('listings' => $listings) );
			}
		}

		public static function archive_header($listings) {
			if ( !$listings->header ) {
				return;
			}

			Helper::get_template( 'archive/listings-header', array('listings' => $listings) );
		}

		public static function featured_badge( $content ) {
			$featured = get_post_meta( get_the_ID(), '_featured', true );
			$feature_badge_text         = get_directorist_option( 'feature_badge_text', __( 'Featured', 'directorist' ) );

			if ( $featured ) {
				$badge_html = '<span class="atbd_badge atbd_badge_featured">' . $feature_badge_text. '</span>';
				return $content . $badge_html;
			}

			return $content;
		}

		public static function popular_badge( $content ) {
			$popular_listing_id = atbdp_popular_listings(get_the_ID());
			$popular_badge_text         = get_directorist_option( 'popular_badge_text', __( 'Popular', 'directorist' ) );

			if ( $popular_listing_id === get_the_ID() ) {
				$badge = '<span class="atbd_badge atbd_badge_popular">' . $popular_badge_text . '</span>';
				return $content . $badge;
			}

			return $content;
		}

		public static function new_listing_badge( $content ) {
			global $post;

			$new_listing_time = get_directorist_option('new_listing_day');
			$new_badge_text = get_directorist_option('new_badge_text', 'New');
			$each_hours = 60 * 60 * 24; // seconds in a day
			$s_date1 = strtotime(current_time('mysql')); // seconds for date 1
			$s_date2 = strtotime($post->post_date); // seconds for date 2
			$s_date_diff = abs($s_date1 - $s_date2); // different of the two dates in seconds
			$days = round($s_date_diff / $each_hours); // divided the different with second in a day
			$new = '<span class="atbd_badge atbd_badge_new">' . $new_badge_text . '</span>';
			if ($days <= (int)$new_listing_time) {
				return  $content .= $new;

			}

        return $content;
    }

    public static function featured_badge_list_view( $content ) {
    	$featured = get_post_meta(get_the_ID(), '_featured', true);
    	$feature_badge_text = get_directorist_option('feature_badge_text', 'Featured');

    	if ( $featured ) {
    		$badge = "<span class='atbd_badge atbd_badge_featured'>$feature_badge_text</span>";
    		$content .= $badge;
    	}

    	return $content;
    }

    public static function populer_badge_list_view( $content ) {
    	$popular_badge_text = get_directorist_option('popular_badge_text', 'Popular');

    	if ( atbdp_popular_listings(get_the_ID()) === get_the_ID() ) {
    		$badge = "<span class='atbd_badge atbd_badge_popular'>$popular_badge_text</span>";
    		$content .= $badge;
    	}

    	return $content;
    }

    public static function new_badge_list_view( $content ) {
    	$content .= new_badge();

    	return $content;
    }

    public static function list_view_business_hours() {
    	_deprecated_function( __METHOD__, '7.3.1' );
    }

    public static function mark_as_favourite_button() {
		_deprecated_function( __METHOD__, '7.3.1' );
    }

}