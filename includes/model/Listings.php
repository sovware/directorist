<?php
/**
 * @author wpWax
 */

namespace Directorist;

use \ATBDP_Listings_Data_Store;
use \ATBDP_Permalink;
use Directory;
use WP_Query;

if ( ! defined( 'ABSPATH' ) ) exit;

class Directorist_Listings {
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
	public $advanced_filter;
	public $display_preview_image;
	public $action_before_after_loop;
	public $logged_in_user_only;
	public $redirect_page_url;
	public $listings_map_height;
	public $map_zoom_level;
	public $directory_type;
	public $default_directory_type;

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
	public $locations_fields;

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
	public $display_map_info;
	public $display_image_map;
	public $display_title_map;
	public $display_address_map;
	public $display_direction_map;

	public function __construct( $atts = array(), $type = 'listing', $query_args = false, array $caching_options = [] ) {
		$this->atts = !empty( $atts ) ? $atts : array();
		$this->type = !empty( $type ) ? $type : 'listing';

		$this->set_options();

		if ( 'search_result' == $this->type ) {
			$this->update_search_options();
		}

		$this->prepare_atts_data();
		$this->prepare_data();

		if ( $query_args ) {
			$this->query_args = $query_args;
		}
		else {
			if ( $this->type == 'search_result' ) {
				$this->query_args = $this->parse_search_query_args();
			}
			else {
				$this->query_args = $this->parse_query_args();
			}
		}

		$this->query_results = $this->get_query_results( $caching_options );
	}

	// set_options
	public function set_options() {
		$this->options['listing_view']                    = get_directorist_option( 'default_listing_view', 'grid' );
		$this->options['order_listing_by']                = apply_filters( 'atbdp_default_listing_orderby', get_directorist_option( 'order_listing_by', 'date' ) );
		$this->options['sort_listing_by']                 = get_directorist_option( 'sort_listing_by', 'asc' );
		$this->options['listings_per_page']               = get_directorist_option( 'all_listing_page_items', 6 );
		$this->options['paginate_listings']               = ! empty( get_directorist_option( 'paginate_all_listings', 1 ) ) ? 'yes' : '';
		$this->options['display_listings_header']         = ! empty( get_directorist_option( 'display_listings_header', 1 ) ) ? 'yes' : '';
		$this->options['listing_header_title']            = get_directorist_option( 'all_listing_header_title', __( 'Items Found', 'directorist' ) );
		$this->options['listing_columns']                 = get_directorist_option( 'all_listing_columns', 3 );
		$this->options['listing_filters_button']          = ! empty( get_directorist_option( 'listing_filters_button', 1 ) ) ? 'yes' : '';
		$this->options['display_preview_image']           = ! empty( get_directorist_option( 'display_preview_image', 1 ) ) ? 'yes' : '';
		$this->options['listings_map_height']             = get_directorist_option( 'listings_map_height', 350 );
		$this->options['enable_featured_listing']         = get_directorist_option( 'enable_featured_listing' );
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
		$this->options['address_location']                = get_directorist_option( 'address_location', 'location' );
		$this->options['excerpt_limit']                   = get_directorist_option( 'excerpt_limit', 20);
		$this->options['g_currency']                      = get_directorist_option( 'g_currency', 'USD' );
		$this->options['display_map_info']                = get_directorist_option('display_map_info', 1) ? true : false;
		$this->options['display_image_map']               = get_directorist_option('display_image_map', 1) ? true : false;
		$this->options['display_title_map']               = get_directorist_option('display_title_map', 1) ? true : false;
		$this->options['display_address_map']             = get_directorist_option('display_address_map', 1) ? true : false;
		$this->options['display_direction_map']           = get_directorist_option('display_direction_map', 1) ? true : false;
		$this->options['crop_width']                      = get_directorist_option('crop_width', 360);
		$this->options['crop_height']                     = get_directorist_option('crop_height', 360);
		$this->options['map_view_zoom_level']             = get_directorist_option('map_view_zoom_level', 16);
		$this->options['default_preview_image']           = get_directorist_option('default_preview_image', DIRECTORIST_ASSETS . 'images/grid.jpg');
		$this->options['font_type']                       = get_directorist_option('font_type','line');
		$this->options['display_publish_date']            = get_directorist_option('display_publish_date', 1) ? true : false;
		$this->options['publish_date_format']             = get_directorist_option('publish_date_format', 'time_ago');
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
		$this->options['listing_columns']                 = get_directorist_option( 'search_listing_columns', 3 );
		$this->options['paginate_listings']               = ! empty( get_directorist_option( 'paginate_search_results', 1 ) ) ? 'yes' : '';
		$this->options['listings_per_page']               = get_directorist_option( 'search_posts_num', 6 );
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
			'advanced_filter'          => $this->options['listing_filters_button'],
			'display_preview_image'    => $this->options['display_preview_image'],
			'action_before_after_loop' => 'yes',
			'logged_in_user_only'      => '',
			'redirect_page_url'        => '',
			'map_height'               => $this->options['listings_map_height'],
			'map_zoom_level'		   => $this->options['map_view_zoom_level'],
			'directory_type'	       => '',
			'default_directory_type'   => ''
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
		$this->advanced_filter          = $this->params['advanced_filter'] == 'yes' ? true : false;
		$this->display_preview_image    = $this->params['display_preview_image'] == 'yes' ? true : false;
		$this->action_before_after_loop = $this->params['action_before_after_loop'] == 'yes' ? true : false;
		$this->logged_in_user_only      = $this->params['logged_in_user_only'] == 'yes' ? true : false;
		$this->redirect_page_url        = $this->params['redirect_page_url'];
		$this->listings_map_height      = ( ! empty( $this->params['map_height'] ) ) ? (int) $this->params['map_height'] : $defaults['map_height'];
		$this->map_zoom_level           = ( ! empty( $this->params['map_zoom_level'] ) ) ? (int) $this->params['map_zoom_level'] : $defaults['map_zoom_level'];
		$this->directory_type           = !empty( $this->params['directory_type'] ) ? explode( ',', $this->params['directory_type'] ) : '';
		$this->default_directory_type   = !empty( $this->params['default_directory_type'] ) ? $this->params['default_directory_type'] : '';
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
		$this->categories_fields = search_category_location_filter( $this->search_category_location_args(), ATBDP_CATEGORY );
		$this->locations_fields  = search_category_location_filter( $this->search_category_location_args(), ATBDP_LOCATION );
		$this->c_symbol                   = atbdp_currency_symbol( $this->options['g_currency'] );
		$this->popular_badge_text         = $this->options['popular_badge_text'];
		$this->feature_badge_text         = $this->options['feature_badge_text'];
		$this->readmore_text              = $this->options['readmore_text'];
		$this->info_display_in_single_line = $this->options['info_display_in_single_line'];
		$this->listing_location_address   = $this->options['listing_location_address'];
		$this->is_disable_price           = $this->options['disable_list_price'];
		$this->disable_single_listing     = $this->options['disable_single_listing'];
		$this->disable_contact_info       = $this->options['disable_contact_info'];
		$this->display_map_info           = $this->options['display_map_info'];
		$this->display_image_map          = $this->options['display_image_map'];
		$this->display_title_map          = $this->options['display_title_map'];
		$this->display_address_map        = $this->options['display_address_map'];
		$this->display_direction_map      = $this->options['display_direction_map'];
	}

	public function set_loop_data() {
		$id          = get_the_ID();
		$author_id   = get_the_author_meta( 'ID' );
		$author_data = get_userdata( $author_id );

		$author_first_name = ! empty( $author_data ) ?  $author_data->first_name : '';
		$author_last_name  = ! empty( $author_data ) ?  $author_data->last_name : '';

		$u_pro_pic   = get_user_meta( $author_id, 'pro_pic', true );
		$u_pro_pic   = ! empty( $u_pro_pic ) ? wp_get_attachment_image_src( $u_pro_pic, 'thumbnail' ) : '';
		$bdbh        = get_post_meta( $id, '_bdbh', true );

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
			'post_view'            => get_post_meta( $id, '_atbdp_post_views_count', true ),

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
			'avatar_img'              => get_avatar( $author_id, apply_filters( 'atbdp_avatar_size', 32 ) ),
			'review'                  => $this->get_review_data(),
		);
	}

	public function get_review_data() {
		// Review
		$average           = ATBDP()->review->get_average(get_the_ID());
		$average           = (int) $average;
		$average_with_zero = number_format( $average, 1 );
		$reviews_count     = ATBDP()->review->db->count(array('post_id' => get_the_ID()));
		$review_text       = ( $reviews_count > 1 ) ? 'Reviews' : 'Review';

		// Icons
		$icon_empty_star = '<i class="'. 'far fa-star'.'"></i>';
		$icon_half_star  = '<i class="'. 'fas fa-star-half-alt'.'"></i>';
		$icon_full_star  = '<i class="'. 'fas fa-star'.'"></i>';

		// Stars
		$star_1 = ( $average >= 0.5 && $average < 1) ? $icon_half_star : $icon_empty_star;
		$star_1 = ( $average >= 1) ? $icon_full_star : $star_1;

		$star_2 = ( $average >= 1.5 && $average < 2) ? $icon_half_star : $icon_empty_star;
		$star_2 = ( $average >= 2) ? $icon_full_star : $star_2;

		$star_3 = ( $average >= 2.5 && $average < 3) ? $icon_half_star : $icon_empty_star;
		$star_3 = ( $average >= 3) ? $icon_full_star : $star_3;

		$star_4 = ( $average >= 3.5 && $average < 4) ? $icon_half_star : $icon_empty_star;
		$star_4 = ( $average >= 4) ? $icon_full_star : $star_4;

		$star_5 = ( $average >= 4.5 && $average < 5 ) ? $icon_half_star : $icon_empty_star;
		$star_5 = ( $average >= 5 ) ? $icon_full_star : $star_5;

		$review_stars = "{$star_1}{$star_2}{$star_3}{$star_4}{$star_5}";

		return [
			'review_stars'    => $review_stars,
			'review_text'     => $review_text,
			'average_reviews' => $average_with_zero,
			'total_reviews'   => $reviews_count,
		];
	}

	private function execute_meta_query_args(&$args, &$meta_queries) {
		if ( 'rand' == $this->orderby ) {
			$current_order = atbdp_get_listings_current_order( $this->orderby );
		}
		else {
			$current_order = atbdp_get_listings_current_order( $this->orderby . '-' . $this->order );
		}

		$meta_queries['directory_type'] = array(
				'key'     => '_directory_type',
				'value'   => $this->get_current_listing_type(),
				'compare' => '=',
			);

		$meta_queries['expired'] = array(
			'key'     => '_listing_status',
			'value'   => 'expired',
			'compare' => '!=',
		);
			
		if ( $this->has_featured ) {
			if ( '_featured' == $this->filterby ) {
				$meta_queries['_featured'] = array(
					'key'     => '_featured',
					'value'   => 1,
					'compare' => '=',
				);
			}
			else {
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

		$listings_ids = ATBDP_Listings_Data_Store::get_listings_ids();
		$rated        = array();

		if (  ( 'yes' == $this->popular_only ) || ( 'views-desc' === $current_order ) ) {
			if ( $this->has_featured ) {
				if ( 'average_rating' === $this->popular_by ) {
					if ( ! empty( $listings_ids ) ) {
						foreach ( $listings_ids as $listings_id ) {
							$average = ATBDP()->review->get_average( $listings_id );
							if ( $this->average_review_for_popular <= $average ) {
								$rated[] = $listings_id;
							}
						}
						$rating_id = array(
							'post__in' => ! empty( $rated ) ? $rated : array(),
						);
						$args = array_merge( $args, $rating_id );
					}
				}
				elseif ( 'view_count' === $this->popular_by ) {
					$meta_queries['views'] = array(
						'key'     => '_atbdp_post_views_count',
						'value'   => $this->view_to_popular,
						'type'    => 'NUMERIC',
						'compare' => '>=',
					);

					$args['orderby'] = array(
						'_featured' => 'DESC',
						'views'     => 'DESC',
					);
				}
				else {
					$meta_queries['views'] = array(
						'key'     => '_atbdp_post_views_count',
						'value'   => $this->view_to_popular,
						'type'    => 'NUMERIC',
						'compare' => '>=',
					);
					$args['orderby'] = array(
						'_featured' => 'DESC',
						'views'     => 'DESC',
					);

					if ( ! empty( $listings_ids ) ) {
						foreach ( $listings_ids as $listings_id ) {
							$average = ATBDP()->review->get_average( $listings_id );
							if ( $this->average_review_for_popular <= $average ) {
								$rated[] = $listings_id;
							}
						}
						$rating_id = array(
							'post__in' => ! empty( $rated ) ? $rated : array(),
						);
						$args = array_merge( $args, $rating_id );
					}
				}
			}
			else {
				if ( 'average_rating' === $this->popular_by ) {
					if ( ! empty( $listings_ids ) ) {
						foreach ( $listings_ids as $listings_id ) {
							$average    = ATBDP()->review->get_average( $listings_id );
							if ( $this->average_review_for_popular <= $average ) {
								$rated[] = $listings_id;
							}
						}
						$rating_id = array(
							'post__in' => ! empty( $rated ) ? $rated : array(),
						);
						$args = array_merge( $args, $rating_id );
					}
				}
				elseif ( 'view_count' === $this->popular_by ) {
					$meta_queries['views'] = array(
						'key'     => '_atbdp_post_views_count',
						'value'   => $this->view_to_popular,
						'type'    => 'NUMERIC',
						'compare' => '>=',
					);
					$args['orderby'] = array(
						'views' => 'DESC',
					);
				}
				else {
					$meta_queries['views'] = array(
						'key'     => '_atbdp_post_views_count',
						'value'   => (int)$this->view_to_popular,
						'type'    => 'NUMERIC',
						'compare' => '>=',
					);
					$args['orderby'] = array(
						'views' => 'DESC',
					);

					if ( ! empty( $listings_ids ) ) {
						foreach ( $listings_ids as $listings_id ) {
							$average    = ATBDP()->review->get_average( $listings_id );
							if ( $this->average_review_for_popular <= $average ) {
								$rated[] = $listings_id;
							}
						}
						$rating_id = array(
							'post__in' => ! empty( $rated ) ? $rated : array(),
						);
						$args = array_merge( $args, $rating_id );
					}
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
			}
			else {
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
			}
			else {
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
			}
			else {
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
			}
			else {
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
			}
			else {
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
			}
			else {
				$args['meta_key'] = '_price';
				$args['orderby']  = 'meta_value_num';
				$args['order']    = 'DESC';
			}
			break;

			case 'rand':
			if ( $this->has_featured ) {
				$args['meta_key'] = '_featured';
				$args['orderby']  = 'meta_value_num rand';
			}
			else {
				$args['orderby'] = 'rand';
			}
			break;
		}
	}

	// get_query_results
	public function get_query_results( array $caching_options = [] ) {
		if ( ! empty( $this->query_args['orderby'] ) ) {
			if ( is_string( $this->query_args['orderby'] ) && preg_match( '/rand/', $this->query_args['orderby'] ) ) {
				$caching_options['cache'] = false;
			}

			if ( is_array( $this->query_args['orderby'] ) ) {
				foreach ( $this->query_args['orderby'] as $key => $value ) {
					if ( preg_match( '/rand/', $value ) ) {
						$caching_options['cache'] = false;
					}
				}
			}

		}

		return ATBDP_Listings_Data_Store::get_archive_listings_query( $this->query_args, $caching_options );
	}

	public function parse_query_args() {
		$args = array(
			'post_type'      => ATBDP_POST_TYPE,
			'post_status'    => 'publish',
			'posts_per_page' => $this->listings_per_page,
		);

		if ( $this->show_pagination ) {
			$args['paged'] = $this->paged;
		}

		else {
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

		if( ! empty( $tax_queries ) ) {
			$args['tax_query'] = $tax_queries;
		}

		$meta_queries = array();
		$this->execute_meta_query_args($args, $meta_queries);

		$meta_queries = apply_filters( 'atbdp_all_listings_meta_queries', $meta_queries );
		$count_meta_queries = count( $meta_queries );

		if ( $count_meta_queries ) {
			$args['meta_query'] = array_merge( array( 'relation' => 'AND' ), $meta_queries );
		}

		// e_var_dump($args);
		// die;
		return apply_filters( 'atbdp_all_listings_query_arguments', $args );
	}

	public function parse_search_query_args() {
		$args = array(
			'post_type'      => ATBDP_POST_TYPE,
			'post_status'    => 'publish',
			'posts_per_page' => $this->listings_per_page,
		);

		if ( $this->show_pagination ) {
			$args['paged'] = $this->paged;
		}
		else {
			$args['no_found_rows'] = true;
		}

		if (!empty($_GET['q'])) {
			$args['s'] = sanitize_text_field($_GET['q']);
		}

		if ($this->has_featured) {
			$args['meta_key'] = '_featured';
			$args['orderby'] = array(
				'meta_value_num' => 'DESC',
				'title' => 'ASC',
			);
		}
		else {
			$args['orderby'] = 'title';
			$args['order'] = 'ASC';
		}

		$tax_queries = array();

		if (isset($_GET['in_cat']) && (int)$_GET['in_cat'] > 0) {
			$tax_queries[] = array(
				'taxonomy' => ATBDP_CATEGORY,
				'field' => 'term_id',
				'terms' => (int)$_GET['in_cat'],
				'include_children' => true,
			);
		}

		if (isset($_GET['in_loc']) && (int)$_GET['in_loc'] > 0) {
			$tax_queries[] = array(
				'taxonomy' => ATBDP_LOCATION,
				'field' => 'term_id',
				'terms' => (int)$_GET['in_loc'],
				'include_children' => true,
			);
		}

		if (isset($_GET['in_tag']) && (int)$_GET['in_tag'] > 0) {
			$tag_value = $_GET['in_tag'];
			$tax_queries[] = array(
				'taxonomy' => ATBDP_TAGS,
				'field' => 'term_id',
				'terms' => $tag_value,
			);

		}
		$count_tax_queries = count($tax_queries);
		if ($count_tax_queries) {
			$args['tax_query'] = array_merge(array('relation' => 'AND'), $tax_queries);
		}

		$meta_queries = array();

		$this->execute_meta_query_args($args, $meta_queries);

		if (isset($_GET['price'])) {
			$price = array_filter($_GET['price']);
			if ($n = count($price)) {
				if (2 == $n) {
					$meta_queries[] = array(
						'key' => '_price',
						'value' => array_map('intval', $price),
						'type' => 'NUMERIC',
						'compare' => 'BETWEEN'
					);
				} else {
					if (empty($price[0])) {
						$meta_queries[] = array(
							'key' => '_price',
							'value' => (int)$price[1],
							'type' => 'NUMERIC',
							'compare' => '<='
						);
					} else {
						$meta_queries[] = array(
							'key' => '_price',
							'value' => (int)$price[0],
							'type' => 'NUMERIC',
							'compare' => '>='
						);
					}
				}
			}
		}

		if (isset($_GET['price_range']) && 'none' != $_GET['price_range']) {
			$price_range = sanitize_text_field( $_GET['price_range'] );
			$meta_queries[] = array(
				'key' => '_price_range',
				'value' => $price_range,
				'compare' => 'LIKE'
			);
		}

		if (isset($_GET['website'])) {
			$website = sanitize_text_field( $_GET['website'] );
			$meta_queries[] = array(
				'key' => '_website',
				'value' => $website,
				'compare' => 'LIKE'
			);
		}

		if (isset($_GET['email'])) {
			$email = sanitize_text_field( $_GET['email'] );
			$meta_queries[] = array(
				'key' => '_email',
				'value' => $email,
				'compare' => 'LIKE'
			);
		}

		if (isset($_GET['phone'])) {
			$phone = sanitize_text_field( $_GET['phone'] );
			$meta_queries[] = array(
				'relation' => 'OR',
				array(
					'key' => '_phone2',
					'value' => $phone,
					'compare' => 'LIKE'
				),
				array(
					'key' => '_phone',
					'value' => $phone,
					'compare' => 'LIKE'
				)
			);
		}

		if (isset($_GET['fax'])) {
			$fax = sanitize_text_field( $_GET['fax'] );
			$meta_queries[] = array(
				'key' => '_fax',
				'value' => $fax,
				'compare' => 'LIKE'
			);
		}

		if (!empty($_GET['miles']) && $_GET['miles'] > 0 && !empty($_GET['cityLat']) && !empty($_GET['cityLng'])) {
			$args['atbdp_geo_query'] = array(
				'lat_field' => '_manual_lat',
				'lng_field' => '_manual_lng',
				'latitude' => sanitize_text_field( $_GET['cityLat'] ),
				'longitude' => sanitize_text_field( $_GET['cityLng'] ),
				'distance' => sanitize_text_field( $_GET['miles'] ),
				'units' => $this->radius_search_unit
			);
		}
		elseif ( ! empty($_GET['address']) ) {
			$address = sanitize_text_field( $_GET['address'] );
			$meta_queries[] = array(
				'key' => '_address',
				'value' => $address,
				'compare' => 'LIKE'
			);
		}

		if (!empty($_GET['zip'])) {
			$zip = sanitize_text_field( $_GET['zip'] );
			$meta_queries[] = array(
				'key' => '_zip',
				'value' => $zip,
				'compare' => 'LIKE'
			);
		}

		if (isset($_GET['search_by_rating'])) {
			$q_rating = sanitize_text_field( $_GET['search_by_rating'] );
			$listings_ids = ATBDP_Listings_Data_Store::get_listings_ids();
			$rated = array();
			if ( ! empty( $listings_ids ) ) {
				foreach ( $listings_ids as $listings_id ) {
					$average = ATBDP()->review->get_average( $listings_id );
					if ($q_rating === '5') {
						if (($average == '5')) {
							$rated[] = $listings_id;
						}
						else {
							$rated[] = array();
						}
					}
					elseif ($q_rating === '4') {
						if ($average >= '4') {
							$rated[] = $listings_id;
						}
						else {
							$rated[] = array();
						}
					}
					elseif ($q_rating === '3') {
						if ($average >= '3') {
							$rated[] = $listings_id;
						}
						else {
							$rated[] = array();
						}
					}
					elseif ($q_rating === '2') {
						if ($average >= '2') {
							$rated[] = $listings_id;
						}
						else {
							$rated[] = array();
						}
					}
					elseif ($q_rating === '1') {
						if ($average >= '1') {
							$rated[] = $listings_id;
						}
						else {
							$rated[] = array();
						}
					}
					elseif ('' === $q_rating) {
						if ($average === '') {
							$rated[] = $listings_id;
						}
					}
				}
				$rating_id = array(
					'post__in' => !empty($rated) ? $rated : array()
				);
				$args = array_merge($args, $rating_id);
			}
		}

		$meta_queries = apply_filters('atbdp_search_listings_meta_queries', $meta_queries);
		$count_meta_queries = count($meta_queries);
		if ($count_meta_queries) {
			$args['meta_query'] = array_merge(array('relation' => 'AND'), $meta_queries);
		}

		return apply_filters( 'atbdp_listing_search_query_argument', $args );
	}

	public function archive_view_template() {
		$template_file = "archive/{$this->view}-view";
		Helper::get_template( $template_file, array( 'listings' => $this ) );
	}

	public function render_shortcode( $atts = [] ) {
		wp_enqueue_script( 'directorist-search-form-listing' );
		wp_enqueue_script( 'directorist-range-slider' );
        wp_enqueue_script( 'directorist-search-listing' );

		$data = Script_Helper::get_search_script_data();
		wp_localize_script( 'directorist-search-form-listing', 'atbdp_search_listing', $data );
		wp_localize_script( 'directorist-search-listing', 'atbdp_search', [
			'ajaxnonce' => wp_create_nonce('bdas_ajax_nonce'),
			'ajax_url' => admin_url('admin-ajax.php'),
			'added_favourite' => __('Added to favorite', 'directorist'),
			'please_login' => __('Please login first', 'directorist')
		]);
		wp_localize_script( 'directorist-search-listing', 'atbdp_search_listing', $data );
		wp_localize_script( 'directorist-range-slider', 'atbdp_range_slider', $data );

		ob_start();

		if (!empty($this->redirect_page_url)) {
			$redirect = '<script>window.location="' . esc_url($this->redirect_page_url) . '"</script>';
			return $redirect;
		}

		if ( $this->logged_in_user_only ) {
			return \ATBDP_Helper::guard([ 'type' => 'auth' ]);
		}

		if ( ! empty( $atts['shortcode'] ) ) {
			Helper::add_shortcode_comment( $atts['shortcode'] );
		}
		
		// Load the template
		Helper::get_template( 'archive-contents', array( 'listings' => $this ), 'listings_archive' );

		return ob_get_clean();
	}

	public function have_posts() {
		return !empty( $this->query_results->ids ) ? true : false;
	}

	public function post_ids() {
		return $this->query_results->ids;
	}

	public function loop_template( $loop = 'grid', $id = NULL ) {
		if( ! $id ) return;
		global $post;
		$post = get_post( $id );
		setup_postdata( $id );
		$this->set_loop_data();

		if ( $loop == 'grid' && !empty( $this->loop['card_fields'] ) ) {
			$active_template = $this->loop['card_fields']['active_template'];
			$template = ( $active_template == 'grid_view_with_thumbnail' ) ? 'loop-grid' : 'loop-grid-nothumb';
			Helper::get_template( 'archive/' . $template, array( 'listings' => $this ) );
		}
		elseif ( $loop == 'list' && !empty( $this->loop['list_fields'] ) ) {
			$active_template = $this->loop['list_fields']['active_template'];
			$template = ( $active_template == 'list_view_with_thumbnail' ) ? 'loop-list' : 'loop-list-nothumb';
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

	public function get_sort_by_link_list() {
		$link_list = array();

		$options       = atbdp_get_listings_orderby_options( $this->sort_by_items );
		$queryString = $_SERVER['QUERY_STRING'];
		parse_str($queryString, $arguments);
		$actual_link = !empty( $_SERVER['REQUEST_URI'] ) ? esc_url( $_SERVER['REQUEST_URI'] ) : '';
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
		$listing_types = array();
		$args          = array(
			'taxonomy'   => ATBDP_TYPE,
			'hide_empty' => false
		);
		if( $this->directory_type ) {
			$args['slug']     = $this->directory_type;
		}
		$all_types     = get_terms( $args );

		foreach ( $all_types as $type ) {
			$listing_types[ $type->term_id ] = [
				'term' => $type,
				'name' => $type->name,
				'data' => get_term_meta( $type->term_id, 'general_config', true ),
			];
		}
		return $listing_types;
	}

	public function get_current_listing_type() {
		$listing_types      = $this->get_listing_types();
		$listing_type_count = count( $listing_types );

		$current = !empty($listing_types) ? array_key_first( $listing_types ) : '';

		if ( isset( $_GET['directory_type'] ) ) {
			$current = $_GET['directory_type'];
		}
		else if( $this->default_directory_type ) {
			$current = $this->default_directory_type;
		}
		else {

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
			$current = $term->term_id;
		}
		return (int) $current;
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

	public function render_map() {
		if ( 'google' == $this->select_listing_map ) {
			$this->load_google_map();
		}
		else {
			$this->load_openstreet_map();
		}
	}

	public function inline_map_template() {
		if ( 'google' == $this->select_listing_map ) {
			$this->load_google_map();
		}
		else {
			$this->load_inline_openstreet_map();
		}
	}

	public function load_openstreet_map() {
		$script_path = DIRECTORIST_VENDOR_JS . 'openstreet-map/subGroup-markercluster-controlLayers-realworld.388.js';
		$opt = $this->get_map_options();
		$map_card_data = $this->get_osm_map_info_card_data();

		$map_height = $this->listings_map_height . "px;";
		echo "<div id='map' style='width: 100%; height: ${map_height};'></div>";

		Helper::add_hidden_data_to_dom( 'loc_data', ['script_path'  => $script_path] );
		Helper::add_hidden_data_to_dom( 'atbdp_map', $opt );
		Helper::add_hidden_data_to_dom( 'atbdp_lat_lon', $map_card_data['lat_lon'] );
		Helper::add_hidden_data_to_dom( 'listings_data', $map_card_data['listings_data'] );

		wp_enqueue_script('directorist-openstreet-load-scripts');
	}

	public function load_inline_openstreet_map( array $map_options = [] ) {
		$script_path = DIRECTORIST_VENDOR_JS . 'openstreet-map/subGroup-markercluster-controlLayers-realworld.388.js';
		$opt = array_merge( $this->get_map_options(), $map_options ) ;

		$map_card_data     = $this->get_osm_map_info_card_data();

		$default_lat_lon   = array( 'lat' => 40.7128, 'lon' => 74.0060 );
		$atbdp_lat_lon     = ( ! empty( $map_card_data['lat_lon'] ) ) ? $map_card_data['lat_lon'] : $default_lat_lon;
		$load_scripts_path = DIRECTORIST_VENDOR_JS . 'openstreet-map/load-scripts.js';

		$map_height = $this->listings_map_height . "px;";
		echo "<div id='map' style='width: 100%; height: ${map_height};'></div>";

		wp_enqueue_script('no_script');
		wp_localize_script( 'no_script', 'atbdp_map', $opt );
		wp_localize_script( 'no_script', 'atbdp_lat_lon', $atbdp_lat_lon);

		wp_localize_script( 'no_script', 'atbdp_lat_lon', $map_card_data['lat_lon'] );
		wp_localize_script( 'no_script', 'loc_data', [
			'script_path'  => $script_path
		]);

		$listings_data = $map_card_data['listings_data'];
		?>
		<script>
			var listings_data = [];

			<?php foreach( $listings_data as $listing_data ) { ?>
			listings_data.push({
				address: `<?php echo isset( $listing_data['address'] ) ? $listing_data['address']: '' ; ?>`,
				cat_icon: `<?php echo isset( $listing_data['cat_icon'] ) ? $listing_data['cat_icon'] : ''; ?>`,
				default_image: `<?php echo isset( $listing_data['default_image'] ) ? $listing_data['default_image'] : ''; ?>`,
				prv_image: `<?php echo isset( $listing_data['prv_image'] ) ? $listing_data['prv_image'] : ''; ?>`,
				listing_img: `<?php echo isset( $listing_data['listing_img'] ) ? $listing_data['listing_img'] : ''; ?>`,
				listing_prv_img: `<?php echo isset( $listing_data['listing_prv_img'] ) ? $listing_data['listing_prv_img'] : ''; ?>`,
				info_content: `<?php echo isset( $listing_data['info_content'] ) ? $listing_data['info_content'] : ''; ?>`,
				manual_lat: `<?php echo isset( $listing_data['manual_lat'] ) ? $listing_data['manual_lat'] : ''; ?>`,
				manual_lng: `<?php echo isset( $listing_data['manual_lng'] ) ? $listing_data['manual_lng'] : ''; ?>`,
			});
			<?php } ?>
		</script>

		<script src="<?php echo $load_scripts_path; ?>"></script>
		<?php
	}

	public function get_map_options() {
		$opt['select_listing_map']    = $this->select_listing_map;
		$opt['crop_width']            = $this->options['crop_width'];
		$opt['crop_height']           = $this->options['crop_height'];
		$opt['display_map_info']      = $this->options['display_map_info'];
		$opt['display_image_map']     = $this->options['display_image_map'];
		$opt['display_title_map']     = $this->options['display_title_map'];
		$opt['display_address_map']   = $this->options['display_address_map'];
		$opt['display_direction_map'] = $this->options['display_direction_map'];
		$opt['zoom']                  = $this->map_zoom_level;
		$opt['default_image']         = $this->options['default_preview_image'];

		$opt['disable_single_listing'] = $this->disable_single_listing;

		$map_is_disabled = ( empty($opt['display_map_info']) && (empty($opt['display_image_map']) || empty($opt['display_title_map']) || empty($opt['display_address_map']) || empty($opt['display_direction_map']))) ? true : false;
		$opt['map_is_disabled'] = $map_is_disabled;

		return apply_filters( 'atbdp_map_options', $opt );
	}

	public function get_osm_map_info_card_data() {
		$opt = $this->get_map_options();

		$listings_data = [];
		$lat_lon = [];

		$listings = $this->query_results;

		if ( ! empty( $listings->ids ) ) :
			// Prime caches to reduce future queries.
			if ( ! empty( $listings->ids ) && is_callable( '_prime_post_caches' ) ) {
				_prime_post_caches( $listings->ids );
			}

			$original_post = $GLOBALS['post'];

			foreach ( $listings->ids as $listings_id ) :
				$GLOBALS['post'] = get_post( $listings_id );
				setup_postdata( $GLOBALS['post'] );
				$ls_data = [];

				$ls_data['manual_lat']      = get_post_meta($listings_id, '_manual_lat', true);
				$ls_data['manual_lng']      = get_post_meta($listings_id, '_manual_lng', true);
				$ls_data['listing_img']     = get_post_meta($listings_id, '_listing_img', true);
				$ls_data['listing_prv_img'] = get_post_meta($listings_id, '_listing_prv_img', true);
				$ls_data['address']         = get_post_meta($listings_id, '_address', true);

				$lat_lon = [
					'lat' => $ls_data['manual_lat'],
					'lon' => $ls_data['manual_lng']
				];

				if ( ! empty( $ls_data['listing_prv_img']) ) {
					$ls_data['prv_image'] = atbdp_get_image_source( $ls_data['listing_prv_img'], 'large' );
				}

				$ls_data['default_image'] = $this->options['default_preview_image'];

				if ( ! empty( $ls_data['listing_img'][0] ) ) {
					$ls_data['gallery_img'] = atbdp_get_image_source($ls_data['listing_img'][0], 'medium');
				}

				$cats      = get_the_terms(get_the_ID(), ATBDP_CATEGORY);
				$font_type = $this->options['font_type'];

				if ( !empty($cats) ) {
					$cat_icon = get_cat_icon($cats[0]->term_id);
				}

				$cat_icon  = !empty($cat_icon) ? $cat_icon : 'fa-map-marker';
				$icon_type = substr($cat_icon, 0,2);
				$fa_or_la  = ('la' == $icon_type) ? "la " : "fa ";
				$cat_icon  = ('none' == $cat_icon) ? 'fa fa-map-marker' : $fa_or_la . $cat_icon ;

				$ls_data['cat_icon'] = $cat_icon;
				$opt['ls_data'] = $ls_data;

				ob_start();

				if (!empty($opt['display_map_info']) && (!empty($opt['display_image_map']) || !empty($opt['display_title_map']) || $opt['display_address_map']) || !empty($opt['display_direction_map'])) {
					Helper::get_template( 'archive/fields/openstreet-map', $opt );
				}

				$ls_data['info_content'] = ob_get_clean();

				$listings_data[] = $ls_data;
			endforeach;

			$GLOBALS['post'] = $original_post;
			wp_reset_postdata();
		endif;

		return [
			'lat_lon'       => $lat_lon,
			'listings_data' => $listings_data,
		];
	}

	public function load_google_map() {
		wp_enqueue_script('directorist-map-view');

		$opt = $this->get_map_options();
		$disable_info_window = 'no';

		if (empty($opt['display_map_info'])) {
			$disable_info_window = 'yes';
		}
		elseif (empty($opt['display_image_map'] || $opt['display_title_map'] || $opt['display_address_map'] || $opt['display_direction_map'])){
			$disable_info_window = 'yes';
		}

		$data = array(
			'plugin_url'          => ATBDP_URL,
			'disable_info_window' => $disable_info_window,
			'zoom'                => $opt['zoom'],
		);

		wp_localize_script( 'directorist-map-view', 'atbdp_map', $data );
		Helper::add_hidden_data_to_dom( 'atbdp_map', $data );

		?>
		<div class="atbdp-body atbdp-map embed-responsive embed-responsive-16by9 atbdp-margin-bottom" data-type="markerclusterer" style="height: <?php echo !empty($this->listings_map_height)?$this->listings_map_height:'';?>px;">
			<?php

			$listings = $this->query_results;

			if ( ! empty( $listings->ids ) ) :
				// Prime caches to reduce future queries.
				if ( ! empty( $listings->ids ) && is_callable( '_prime_post_caches' ) ) {
					_prime_post_caches( $listings->ids );
				}

				$original_post = $GLOBALS['post'];

				foreach ( $listings->ids as $listings_id ) :
					$GLOBALS['post'] = get_post( $listings_id );
					setup_postdata( $GLOBALS['post'] );
					$ls_data = [];

					$ls_data['post_id']         = $listings_id;
					$ls_data['manual_lat']      = get_post_meta($listings_id, '_manual_lat', true);
					$ls_data['manual_lng']      = get_post_meta($listings_id, '_manual_lng', true);
					$ls_data['listing_img']     = get_post_meta($listings_id, '_listing_img', true);
					$ls_data['listing_prv_img'] = get_post_meta($listings_id, '_listing_prv_img', true);
					$ls_data['crop_width']      = $this->options['crop_width'];
					$ls_data['crop_height']     = $this->options['crop_height'];
					$ls_data['address']         = get_post_meta($listings_id, '_address', true);
					$ls_data['font_type']       = $this->options['font_type'];
					$ls_data['fa_or_la']        = ('line' == $ls_data['font_type']) ? "la " : "fa ";
					$ls_data['cats']            = get_the_terms($listings_id, ATBDP_CATEGORY);

					if(!empty($ls_data['cats'])){
						$cat_icon = get_cat_icon($ls_data['cats'][0]->term_id);
					}

					$cat_icon = !empty($cat_icon) ? $cat_icon : 'fa-map-marker';
					$icon_type = substr($cat_icon, 0,2);
					$fa_or_la = ('la' == $icon_type) ? "la " : "fa ";
					$ls_data['cat_icon'] = ('none' == $cat_icon) ? 'fa fa-map-marker' : $fa_or_la . $cat_icon ;
					$ls_data['default_img'] = atbdp_image_cropping(DIRECTORIST_ASSETS . 'images/grid.jpg', $ls_data['crop_width'], $ls_data['crop_height'], true, 100)['url'];

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

		public function loop_get_the_thumbnail( $class='' ) {
			$default_image_src = Helper::default_preview_image_src( $this->current_listing_type );

			$id = get_the_ID();
			$image_quality     = get_directorist_option('preview_image_quality', 'large');
			$listing_prv_img   = get_post_meta($id, '_listing_prv_img', true);
			$listing_img       = get_post_meta($id, '_listing_img', true);

			if ( is_array( $listing_img ) && ! empty( $listing_img ) ) {
				$thumbnail_img = atbdp_get_image_source( $listing_img[0], $image_quality );
				$thumbnail_id = $listing_img[0];
			}

			if ( ! empty( $listing_prv_img ) ) {
				$thumbnail_img = atbdp_get_image_source( $listing_prv_img, $image_quality );
				$thumbnail_id = $listing_prv_img;
			}

			if ( ! empty( $img_src ) ) {
				$thumbnail_img = $img_src;
				$thumbnail_id = 0;
			}

			if ( empty( $thumbnail_img ) ) {
				$thumbnail_img = $default_image_src;
				$thumbnail_id = 0;
			}

			$image_src    = $thumbnail_img;
			$image_alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
			$image_alt = ( ! empty( $image_alt ) ) ? esc_attr( $image_alt ) : esc_html( get_the_title( $thumbnail_id ) );
			$image_alt = ( ! empty( $image_alt ) ) ? $image_alt : esc_html( get_the_title() );

			return "<img src='$image_src' alt='$image_alt' class='$class' />";
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
				$title = sprintf('<a href="%s"%s>%s</a>', $this->loop['permalink'], $this->loop_link_attr(), $this->loop['title']);
			}
			else {
				$title = $this->loop['title'];
			}
			return $title;
		}

		public function loop_is_favourite() {
			$favourites = (array) get_user_meta( get_current_user_id(), 'atbdp_favourites', true );
			if ( in_array( get_the_id() , $favourites ) ) {
				return true;
			}
			else {
				return false;
			}
		}

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
				$loc_name = sanitize_text_field( $_GET['address'] );
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

			if ( $this->type == 'search_result' ) {
				$title = $this->item_found_title_for_search( $count );
			}
			else {
				$title = sprintf('<span>%s</span> %s', $count, $this->header_title );
			}
			return apply_filters('directorist_listings_found_text', $title );
		}

		public function masonary_grid_attr() {
			return ($this->view_as !== 'masonry_grid') ? '' : ' data-uk-grid';
		}

		public function grid_view_class() {
			return $this->view_as == 'masonry_grid' ? 'directorist-grid-masonary' : 'directorist-grid-normal';
		}

		public function get_the_location() {

			$id = get_the_ID();
			$locs = get_the_terms( $id, ATBDP_LOCATION );

			if ( empty( $locs ) ) {
				return;
			}

			$local_names = array();
			foreach ($locs as $term) {
				$local_names[$term->term_id] = $term->parent == 0 ? $term->slug : $term->slug;
				ksort($local_names);
				$locals = array_reverse($local_names);
			}
			$output = array();
			$link = array();
			foreach ($locals as $location) {
				$term = get_term_by('slug', $location, ATBDP_LOCATION);
				$link = esc_url( get_term_link( $term->term_id, ATBDP_LOCATION ) );
				$space = str_repeat(' ', 1);
				$output[] = "<a href='{$link}'>{$term->name}</a>";
			}

			return implode(', ', $output);
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
			$header_container_fluid = is_directoria_active() ? 'container' : 'container-fluid';
			$header_container_fluid = apply_filters( 'atbdp_listings_header_container_fluid', $header_container_fluid );
			echo ( ! empty( $header_container_fluid ) ) ? $header_container_fluid : '';
		}

		public function has_listings_header() {
			$has_filter_button = ( ! empty( $this->listing_filters_button ) && ! empty( $this->search_more_filters_fields ) );

			return ( $has_filter_button || ! empty( $this->header_title ) ) ? true : false;
		}

		public function has_header_toolbar() {
			return ( $this->display_viewas_dropdown || $this->display_sortby_dropdown ) ? true : false;
		}

		public function render_card_field( $field ) {
			if ( $field['type'] == 'badge' ) {
				$this->render_badge_template($field);
			}
			else {
				$submission_form_fields = get_term_meta( $this->current_listing_type, 'submission_form_fields', true );
				$original_field = '';

				if ( isset( $field['original_widget_key'] ) && isset( $submission_form_fields['fields'][$field['original_widget_key']] ) ) {
					$original_field = $submission_form_fields['fields'][$field['original_widget_key']];
				}
				if ( ! empty( $original_field ) ) {
					$field['original_field'] = $original_field;
				}
				
				$id = get_the_id();
				$load_template = true;
				$value = get_post_meta( $id, '_'.$field['widget_key'], true );
				if ( isset( $field['field_key']  ) ) {
					$value = ! empty( get_post_meta( $id, '_'.$field['field_key'], true ) ) ? get_post_meta( $id, '_'.$field['field_key'], true ) : get_post_meta( $id, $field['field_key'], true );
				}
				if ( isset( $original_field['field_key']  ) ) {
					$value = ! empty( get_post_meta( $id, '_'.$original_field['field_key'], true ) ) ? get_post_meta( $id, '_'.$original_field['field_key'], true ) : get_post_meta( $id, $original_field['field_key'], true );
				}

				if( 'listings_location' === $field['widget_name'] ) {
					$location = get_the_terms( $id, ATBDP_LOCATION );
					if( $location ) {
						$value = true;
					}
				}

				if( ( $field['type'] === 'list-item' ) && !$value  &&  ( 'posted_date' !== $field['widget_name'] ) ) {
					$load_template = false;
				}

				$label = !empty( $field['show_label'] ) ? $field['label']: '';
				$args = array(
					'listings' => $this,
					'post_id'  => $id,
					'data'     => $field,
					'value'    => $value,
					'label'    => $label,
					'icon'     => !empty( $field['icon'] ) ? $field['icon'] : '',
					'original_field' => $submission_form_fields,
				);

				$widget_name = $field['widget_name'];
				if ( isset( $data['original_field'] ) && isset( $data['original_field']['widget_name'] ) ) {
					$widget_name = $data['original_field']['widget_name'];
				}

				if ( $this->is_custom_field( $field ) ) {
					$field_type = !empty( $field['original_field']['type'] ) ? $field['original_field']['type'] : '';
					if( 'checkbox' === $field_type ){
						$option_value = [];
						$value = is_array( $value ) ? join( ",",$value ) : $value;
						foreach( $field['original_field']['options'] as $option ) {
							$key = $option['option_value'];
							if( in_array( $key, explode( ',', $value ) ) ) {
								$space = str_repeat(' ', 1);
								$option_value[] = $space . $option['option_label'];
							}
						}
						$output = join( ',', $option_value );
						$result = $output ? $output : $value;
						$args['value'] = $result;
					}

					$template = 'archive/custom-fields/' . $widget_name;
				} else {
					$template = 'archive/fields/' . $widget_name;
				}

				if( $load_template ) {
					Helper::get_template( $template, $args );
				}

			}
		}

		public function is_custom_field( $data ) {
			$fields = [ 'checkbox', 'color_picker', 'date', 'file', 'number', 'radio', 'select', 'text', 'textarea', 'time', 'url' ];
			$widget_name = $data['widget_name'];

			if ( isset( $data['original_field'] ) && isset( $data['original_field']['widget_name'] ) ) {
				$widget_name = $data['original_field']['widget_name'];
			}

			return in_array( $widget_name, $fields ) ? true : false;
		}

		public function print_label( $label ) {
			if ( $label ) {
				$label_text = $label . ': ';
				echo apply_filters( 'directorist_loop_label', $label_text, $label );
			}
			else {
				return;
			}
		}

		public function render_loop_fields( $fields, $before = '', $after = '' ) {
			
			if( !empty( $fields ) ) {
				foreach ( $fields as $field ) {
					echo $before;$this->render_card_field( $field );echo $after;
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

		public function sortby_dropdown_template() {
			Helper::get_template( 'archive/sortby-dropdown', array( 'listings' => $this ) );
		}

		public function viewas_dropdown_template() {
			Helper::get_template( 'archive/viewas-dropdown', array( 'listings' => $this ) );
		}

		public function search_form_template() {
			$args = array(
				'listings'   => $this,
				'searchform' => new Directorist_Listing_Search_Form( $this->type, $this->current_listing_type ),
			);
			Helper::get_template( 'archive/search-form', $args );
		}

		public function filter_btn_html() {
			if ( $this->has_filters_icon ) {
				return sprintf( '<span class="%s-filter"></span> %s', atbdp_icon_type(), $this->filter_button_text );
			}
			else {
				return $this->filter_button_text;
			}
		}

		public function directory_type_nav_template() {
			$count = count( $this->listing_types );
			$enable_multi_directory = get_directorist_option( 'enable_multi_directory', false );
			if ( $count > 1 && ! empty( $enable_multi_directory ) ) {
				Helper::get_template( 'archive/directory-type-nav', array('listings' => $this) );
			}
		}

		public function header_bar_template() {
			if ( !$this->header ) {
				return;
			}

			Helper::get_template( 'archive/header-bar', array('listings' => $this) );
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
				'prev_text' => apply_filters('directorist_pagination_prev_text', '<span class="fa fa-chevron-left"></span>'),
				'next_text' => apply_filters('directorist_pagination_next_text', '<span class="fa fa-chevron-right atbdp_right_nav"></span>'),
			));

			if ( $links ) {
				$navigation = '<div class="directorist-pagination">'.$links.'</div>';
			}


			$result = apply_filters('directorist_pagination', $navigation, $links, $this->query_results, $paged );

			if ( $echo ) {
				echo $result;
			}
			else {
				return $result;
			}
		}

    	// Hooks ------------
		public static function archive_type($listings) {
			$count = count( $listings->listing_types );
			$enable_multi_directory = get_directorist_option( 'enable_multi_directory', false );
			if ( $count > 1 && ! empty( $enable_multi_directory ) ) {
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
    	$content = '';
    	$plan_hours              = true;
    	$disable_bz_hour_listing = get_post_meta(get_the_ID(), '_disable_bz_hour_listing', true);
		$bdbh_version 			 = get_post_meta(get_the_ID(), '_bdbh_version', true);
    	$enable247hour           = get_post_meta(get_the_ID(), '_enable247hour', true);
    	$bdbh                    = get_post_meta(get_the_ID(), '_bdbh', true);
    	$business_hours          = !empty($bdbh) ? atbdp_sanitize_array($bdbh) : array();

    	if (is_fee_manager_active()) {
    		$plan_hours = is_plan_allowed_business_hours(get_post_meta(get_the_ID(), '_fm_plans', true));
    	}

    	if (is_business_hour_active() && $plan_hours && empty($disable_bz_hour_listing)) {
    		if ( ! empty($enable247hour) ) {
    			$content = "<span class='atbd_badge atbd_badge_open'>" . get_directorist_option('open_badge_text') . "</span>";
    		}
    		else {
    			if (class_exists('BD_Business_Hour')) {
    				$content = BD_Business_Hour()->show_business_open_close($business_hours, false);
    			}
    		}
    	}

    	echo $content;
    }

    public static function mark_as_favourite_button() {
    	$display_mark_as_fav = apply_filters('atbdp_listings_favourite_btn',get_directorist_option( 'display_mark_as_fav', 1 ));

    	if ( ! empty( $display_mark_as_fav ) ) {
    		echo atbdp_listings_mark_as_favourite( get_the_ID() );
    	}
    }
}