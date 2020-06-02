<?php
/**
 * @author AazzTech
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Directorist_Listings {

	public $defaults;
	public $atts;
	public $params;

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
    public $filters_buttons;
    public $reset_filters_text;
    public $apply_filters_text;
    public $display_sortby_dropdown;
    public $display_viewas_dropdown;
    public $sort_by_text;
    public $view_as_text;
    public $view_as;
    public $sort_by_items;
    public $views;
    public $text_placeholder;
    public $category_placeholder;
    public $location_placeholder;
    public $categories_fields;
    public $locations_fields;
    public $c_symbol;
    public $address_label;
    public $fax_label;
    public $email_label;
    public $website_label;
    public $tag_label;
    public $zip_label;
    public $popular_badge_text;
    public $feature_badge_text;
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
    public $display_view_count;
    public $display_mark_as_fav;
    public $display_publish_date;
    public $display_contact_info;
    public $display_feature_badge_cart;
    public $display_popular_badge_cart;
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

	public function __construct( $atts = array() ) {
		if ( ! empty( $atts ) ) {
			$this->atts = $atts;
		}

		$this->prepare_atts_data();
		$this->prepare_data();
		$this->set_query();
	}

	public function prepare_atts_data() {

		$defaults = array(
			'view'                     => get_directorist_option( 'default_listing_view', 'grid' ),
			'_featured'                => 1,
			'filterby'                 => '',
			'orderby'                  => get_directorist_option( 'order_listing_by', 'date' ),
			'order'                    => get_directorist_option( 'sort_listing_by', 'asc' ),
			'listings_per_page'        => get_directorist_option( 'all_listing_page_items', 6 ),
			'show_pagination'          => !empty( get_directorist_option( 'paginate_all_listings', 1 ) ) ? 'yes' : '',
			'header'                   => !empty( get_directorist_option( 'display_listings_header', 1 ) ) ? 'yes' : '',
			'header_title'             => get_directorist_option( 'all_listing_header_title', __( 'Items Found', 'directorist' ) ),
			'category'                 => '',
			'location'                 => '',
			'tag'                      => '',
			'ids'                      => '',
			'columns'                  => get_directorist_option( 'all_listing_columns', 3 ),
			'featured_only'            => '',
			'popular_only'             => '',
			'advanced_filter'          => !empty( get_directorist_option( 'listing_filters_button', 1 ) ) ? 'yes' : '',
			'display_preview_image'    => !empty( get_directorist_option( 'display_preview_image', 1 ) ) ? 'yes' : '',
			'action_before_after_loop' => 'yes',
			'logged_in_user_only'      => '',
			'redirect_page_url'        => '',
			'map_height'               => get_directorist_option( 'listings_map_height', 350 ),
		);

		$this->defaults  = apply_filters( 'atbdp_all_listings_params', $defaults );
		$this->params    = shortcode_atts( $this->defaults, $this->atts );

		$this->view                     = atbdp_get_listings_current_view_name( $this->params['view'] );
		$this->_featured                = $this->params['_featured'];
		$this->filterby                 = $this->params['filterby'];
		$this->orderby                  = $this->params['orderby'];
		$this->order                    = $this->params['order'];
		$this->listings_per_page        = (int) $this->params['listings_per_page'];
		$this->show_pagination          = $this->params['show_pagination'] == 'yes' ? true : false;
		$this->header                   = $this->params['header'] == 'yes' ? true : false;;
		$this->header_title             = $this->params['header_title'];
		$this->categories               = !empty( $this->params['category'] ) ? explode( ',', $this->params['category'] ) : '';
		$this->locations                = !empty( $this->params['tag'] ) ? explode( ',', $this->params['tag'] ) : '';
		$this->tags                     = !empty( $this->params['location'] ) ? explode( ',', $this->params['location'] ) : '';
		$this->ids                      = !empty( $this->params['ids'] ) ? explode( ',', $this->params['ids'] ) : '';
		$this->columns                  = (int) $this->params['columns'];
		$this->featured_only            = $this->params['featured_only'];
		$this->popular_only             = $this->params['popular_only'];
		$this->advanced_filter          = $this->params['advanced_filter'] == 'yes' ? true : false;
		$this->display_preview_image    = $this->params['display_preview_image'] == 'yes' ? true : false;
		$this->action_before_after_loop = $this->params['action_before_after_loop'] == 'yes' ? true : false;
		$this->logged_in_user_only      = $this->params['logged_in_user_only'] == 'yes' ? true : false;
		$this->redirect_page_url        = $this->params['redirect_page_url'];
		$this->listings_map_height      = (int) $this->params['map_height'];
	}

	public function prepare_data() {
		$this->has_featured                = get_directorist_option( 'enable_featured_listing' );
		$this->has_featured                = $this->has_featured || is_fee_manager_active() ? $this->_featured : $this->has_featured;
		$this->popular_by                  = get_directorist_option( 'listing_popular_by' );
		$this->average_review_for_popular  = get_directorist_option( 'average_review_for_popular', 4 );
		$this->view_to_popular             = get_directorist_option( 'views_for_popular' );
		$this->radius_search_unit          = get_directorist_option( 'radius_search_unit', 'miles' );
		$this->default_radius_distance     = get_directorist_option( 'listing_default_radius_distance', 0 );
		$this->select_listing_map          = get_directorist_option( 'select_listing_map', 'google' );
		$this->filters_display             = get_directorist_option( 'listings_display_filter', 'sliding' );
		$this->search_more_filters_fields  = get_directorist_option( 'listing_filters_fields', array( 'search_text', 'search_category', 'search_location', 'search_price', 'search_price_range', 'search_rating', 'search_tag', 'search_custom_fields', 'radius_search' ) );
		$this->has_filters_button          = $this->advanced_filter;
		$this->has_filters_icon            = get_directorist_option( 'listing_filters_icon', 1 ) ? true : false;
		$this->filter_button_text          = get_directorist_option( 'listings_filter_button_text', __( 'Filters', 'directorist' ) );
		$this->paged                       = atbdp_get_paged_num();
		$this->filters_buttons             = get_directorist_option( 'listings_filters_button', array( 'reset_button', 'apply_button' ) );
		$this->reset_filters_text          = get_directorist_option( 'listings_reset_text', __( 'Reset Filters', 'directorist' ) );
		$this->apply_filters_text          = get_directorist_option( 'listings_apply_text', __( 'Apply Filters', 'directorist' ) );
		$this->display_sortby_dropdown     = get_directorist_option( 'display_sort_by', 1 ) ? true : false;
		$this->display_viewas_dropdown     = get_directorist_option( 'display_view_as', 1 ) ? true : false;
		$this->sort_by_text                = get_directorist_option( 'sort_by_text', __( 'Sort By', 'directorist' ) );
		$this->view_as_text                = get_directorist_option( 'view_as_text', __( 'View As', 'directorist' ) );
		$this->view_as                     = get_directorist_option( 'grid_view_as', 'normal_grid' );
		$view_as_items               = get_directorist_option( 'listings_view_as_items', array( 'listings_grid', 'listings_list', 'listings_map' ) );
		$this->sort_by_items         = get_directorist_option( 'listings_sort_by_items', array( 'a_z', 'z_a', 'latest', 'oldest', 'popular', 'price_low_high', 'price_high_low', 'random' ) );
		$this->views                 = atbdp_get_listings_view_options( $view_as_items );
		$this->text_placeholder      = get_directorist_option( 'listings_search_text_placeholder', __( 'What are you looking for?', 'directorist' ) );
		$this->category_placeholder  = get_directorist_option( 'listings_category_placeholder', __( 'Select a category', 'directorist' ) );
		$this->location_placeholder  = get_directorist_option( 'listings_location_placeholder', __( 'Select a location', 'directorist' ) );
		$this->categories_fields = search_category_location_filter( $this->search_category_location_args(), ATBDP_CATEGORY );
		$this->locations_fields  = search_category_location_filter( $this->search_category_location_args(), ATBDP_LOCATION );
		$this->c_symbol                   = atbdp_currency_symbol( get_directorist_option( 'g_currency', 'USD' ) );
		$this->address_label              = get_directorist_option( 'address_label', __( 'Address', 'directorist' ) );
		$this->fax_label                  = get_directorist_option( 'fax_label', __( 'Fax', 'directorist' ) );
		$this->email_label                = get_directorist_option( 'email_label', __( 'Email', 'directorist' ) );
		$this->website_label              = get_directorist_option( 'website_label', __( 'Website', 'directorist' ) );
		$this->tag_label                  = get_directorist_option( 'tag_label', __( 'Tag', 'directorist' ) );
		$this->zip_label                  = get_directorist_option( 'zip_label', __( 'Zip', 'directorist' ) );
		$this->popular_badge_text         = get_directorist_option( 'popular_badge_text', __( 'Popular', 'directorist' ) );
		$this->feature_badge_text         = get_directorist_option( 'feature_badge_text', __( 'Featured', 'directorist' ) );
		$this->readmore_text              = get_directorist_option( 'readmore_text', __('Read More', 'directorist'));
		$this->listing_location_address   = get_directorist_option( 'listing_location_address', 'map_api' );
		$this->is_disable_price           = get_directorist_option( 'disable_list_price' );
		$this->disable_single_listing     = get_directorist_option( 'disable_single_listing') ? true : false;
		$this->disable_contact_info       = get_directorist_option( 'disable_contact_info', 0 ) ? true : false;
		$this->display_title              = get_directorist_option( 'display_title', 1 ) ? true : false;
		$this->display_review             = get_directorist_option( 'enable_review', 1 ) ? true : false;
		$this->display_price              = get_directorist_option( 'display_price', 1 ) ? true : false;
		$this->display_email              = get_directorist_option( 'display_email', 0 ) ? true : false;
		$this->display_web_link           = get_directorist_option( 'display_web_link', 0 ) ? true : false;
		$this->display_category           = get_directorist_option( 'display_category', 1 ) ? true : false;
		$this->display_view_count         = get_directorist_option( 'display_view_count', 1 ) ? true : false;
		$this->display_mark_as_fav        = get_directorist_option( 'display_mark_as_fav', 1 ) ? true : false;
		$this->display_publish_date       = get_directorist_option( 'display_tagline_field', 1 ) ? true : false;
		$this->display_contact_info       = get_directorist_option( 'display_contact_info', 1 ) ? true : false;
		$this->display_feature_badge_cart = get_directorist_option( 'display_feature_badge_cart', 1 ) ? true : false;
		$this->display_popular_badge_cart = get_directorist_option( 'display_popular_badge_cart', 1 ) ? true : false;
		$this->enable_tagline             = get_directorist_option( 'enable_tagline' ) ? true : false;
		$this->enable_excerpt             = get_directorist_option( 'enable_excerpt' ) ? true : false;
		$this->display_author_image       = get_directorist_option( 'display_author_image', 1 ) ? true : false;
		$this->display_tagline_field      = get_directorist_option( 'display_tagline_field', 0 ) ? true : false;
		$this->display_pricing_field      = get_directorist_option( 'display_pricing_field', 1 ) ? true : false;
		$this->display_excerpt_field      = get_directorist_option( 'display_excerpt_field', 0 ) ? true : false;
		$this->display_address_field      = get_directorist_option( 'display_address_field', 1 ) ? true : false;
		$this->display_phone_field        = get_directorist_option( 'display_phone_field', 1 ) ? true : false;
		$this->display_readmore           = get_directorist_option( 'display_readmore', 0) ? true : false;
		$this->address_location           = get_directorist_option( 'address_location', 'location' );
		$this->excerpt_limit              = get_directorist_option( 'excerpt_limit', 20);
        $this->display_map_info           = get_directorist_option('display_map_info', 1) ? true : false;
        $this->display_image_map          = get_directorist_option('display_image_map', 1) ? true : false;
        $this->display_title_map          = get_directorist_option('display_title_map', 1) ? true : false;
        $this->display_address_map        = get_directorist_option('display_address_map', 1) ? true : false;
        $this->display_direction_map      = get_directorist_option('display_direction_map', 1) ? true : false;
	}

	public function set_loop_data() {
		$id          = get_the_ID();
		$author_id   = get_the_author_meta( 'ID' );
		$author_data = get_userdata( $author_id );
        $u_pro_pic   = get_user_meta( $author_id, 'pro_pic', true );
        $u_pro_pic   = ! empty( $u_pro_pic ) ? wp_get_attachment_image_src( $u_pro_pic, 'thumbnail' ) : '';
        $bdbh        = get_post_meta( $id, '_bdbh', true );

		$data = array(
			'id'                   => $id,
			'permalink'            => get_permalink( $id ),
			'title'                => get_the_title(),
			'cats'                 => get_the_terms( $id, ATBDP_CATEGORY ),
			'locs'                 => get_the_terms( $id, ATBDP_LOCATION ),
			'featured'             => get_post_meta( $id, '_featured', true ),
			'price'                => get_post_meta( $id, '_price', true ),
			'price_range'          => get_post_meta( $id, '_price_range', true ),
			'atbd_listing_pricing' => get_post_meta( $id, '_atbd_listing_pricing', true ),
			'listing_img'          => get_post_meta( $id, '_listing_img', true ),
			'listing_prv_img'      => get_post_meta( $id, '_listing_prv_img', true ),
			'excerpt'              => get_post_meta( $id, '_excerpt', true ),
			'tagline'              => get_post_meta( $id, '_tagline', true ),
			'address'              => get_post_meta( $id, '_address', true ),
			'email'                => get_post_meta( $id, '_email', true ),
			'web'                  => get_post_meta( $id, '_website', true ),
			'phone_number'         => get_post_meta( $id, '_phone', true ),
			'category'             => get_post_meta( $id, '_admin_category_select', true ),
			'post_view'            => get_post_meta( $id, '_atbdp_post_views_count', true ),
			'hide_contact_info'    => get_post_meta( $id, '_hide_contact_info', true ),
			'business_hours'       => ! empty( $bdbh ) ? atbdp_sanitize_array( $bdbh ) : array(),
			'enable247hour'        => get_post_meta( $id, '_enable247hour', true ),
			'disable_bz_hour_listing' => get_post_meta( $id, '_disable_bz_hour_listing', true ),
			'author_id'            => $author_id,
			'author_data'          => $author_data,
			'author_full_name'     => $author_data->first_name . ' ' . $author_data->last_name,
			'author_link'          => ATBDP_Permalink::get_user_profile_page_link( $author_id ),
			'author_link_class'    => ! empty( $author_data->first_name && $author_data->last_name ) ? 'atbd_tooltip' : '',
			'u_pro_pic'            => $u_pro_pic,
			'avatar_img'           => get_avatar( $author_id, apply_filters( 'atbdp_avatar_size', 32 ) ),
		);

		$this->loop = $data;
	}

	public function set_query() {
		if ( 'rand' == $this->orderby ) {
			$current_order = atbdp_get_listings_current_order( $this->orderby );
		}
		else {
			$current_order = atbdp_get_listings_current_order( $this->orderby . '-' . $this->order );
		}

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

		if ( ! empty( $this->categories ) && ! empty( $this->locations ) && ! empty( $this->tags ) ) {

			$tax_queries['tax_query'] = array(
				'relation' => 'AND',
				array(
					'taxonomy'         => ATBDP_CATEGORY,
					'field'            => 'slug',
					'terms'            => ! empty( $this->categories ) ? $this->categories : array(),
					'include_children' => true, /*@todo; Add option to include children or exclude it*/
				),
				array(
					'taxonomy'         => ATBDP_LOCATION,
					'field'            => 'slug',
					'terms'            => ! empty( $this->locations ) ? $this->locations : array(),
					'include_children' => true, /*@todo; Add option to include children or exclude it*/
				),
				array(
					'taxonomy'         => ATBDP_TAGS,
					'field'            => 'slug',
					'terms'            => ! empty( $this->tags ) ? $this->tags : array(),
					'include_children' => true, /*@todo; Add option to include children or exclude it*/
				),
			);
		}
		elseif ( ! empty( $this->categories ) && ! empty( $this->tags ) ) {
			$tax_queries['tax_query'] = array(
				'relation' => 'AND',
				array(
					'taxonomy'         => ATBDP_CATEGORY,
					'field'            => 'slug',
					'terms'            => ! empty( $this->categories ) ? $this->categories : array(),
					'include_children' => true, /*@todo; Add option to include children or exclude it*/
				),
				array(
					'taxonomy'         => ATBDP_TAGS,
					'field'            => 'slug',
					'terms'            => ! empty( $this->tags ) ? $this->tags : array(),
					'include_children' => true, /*@todo; Add option to include children or exclude it*/
				),
			);
		}
		elseif ( ! empty( $this->categories ) && ! empty( $this->locations ) ) {
			$tax_queries['tax_query'] = array(
				'relation' => 'AND',
				array(
					'taxonomy'         => ATBDP_CATEGORY,
					'field'            => 'slug',
					'terms'            => ! empty( $this->categories ) ? $this->categories : array(),
					'include_children' => true, /*@todo; Add option to include children or exclude it*/
				),
				array(
					'taxonomy'         => ATBDP_LOCATION,
					'field'            => 'slug',
					'terms'            => ! empty( $this->locations ) ? $this->locations : array(),
					'include_children' => true, /*@todo; Add option to include children or exclude it*/
				),

			);
		}
		elseif ( ! empty( $this->tags ) && ! empty( $this->locations ) ) {
			$tax_queries['tax_query'] = array(
				'relation' => 'AND',
				array(
					'taxonomy'         => ATBDP_TAGS,
					'field'            => 'slug',
					'terms'            => ! empty( $tags ) ? $this->tags : array(),
					'include_children' => true, /*@todo; Add option to include children or exclude it*/
				),
				array(
					'taxonomy'         => ATBDP_LOCATION,
					'field'            => 'slug',
					'terms'            => ! empty( $this->locations ) ? $this->locations : array(),
					'include_children' => true, /*@todo; Add option to include children or exclude it*/
				),

			);
		}
		elseif ( ! empty( $this->categories ) ) {
			$tax_queries['tax_query'] = array(
				array(
					'taxonomy'         => ATBDP_CATEGORY,
					'field'            => 'slug',
					'terms'            => ! empty( $this->categories ) ? $this->categories : array(),
					'include_children' => true, /*@todo; Add option to include children or exclude it*/
				),
			);
		}
		elseif ( ! empty( $this->tags ) ) {
			$tax_queries['tax_query'] = array(
				array(
					'taxonomy'         => ATBDP_TAGS,
					'field'            => 'slug',
					'terms'            => ! empty( $this->tags ) ? $this->tags : array(),
					'include_children' => true, /*@todo; Add option to include children or exclude it*/
				),
			);
		}
		elseif ( ! empty( $this->locations ) ) {
			$tax_queries['tax_query'] = array(
				array(
					'taxonomy'         => ATBDP_LOCATION,
					'field'            => 'slug',
					'terms'            => ! empty( $this->locations ) ? $this->locations : array(),
					'include_children' => true, /*@todo; Add option to include children or exclude it*/
				),
			);
		}

		$args['tax_query'] = $tax_queries;    

		$meta_queries = array();

		$meta_queries['expired'] = array(
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

		$args['expired'] = $meta_queries;

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

		$listings = get_atbdp_listings_ids();
		$rated    = array();

		if (  ( 'yes' == $this->popular_only ) || ( 'views-desc' === $current_order ) ) {
			if ( $this->has_featured ) {
				if ( 'average_rating' === $this->popular_by ) {
					if ( $listings->have_posts() ) {
						while ( $listings->have_posts() ) {
							$listings->the_post();
							$id = get_the_ID();
							$average    = ATBDP()->review->get_average( $id );
							if ( $this->average_review_for_popular <= $average ) {
								$rated[] = $id;
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

					if ( $listings->have_posts() ) {
						while ( $listings->have_posts() ) {
							$listings->the_post();
							$id = get_the_ID();
							$average          = ATBDP()->review->get_average( $id );
							if ( $this->average_review_for_popular <= $average ) {
								$rated[] = $id;
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
					if ( $listings->have_posts() ) {
						while ( $listings->have_posts() ) {
							$listings->the_post();
							$id = get_the_ID();
							$average    = ATBDP()->review->get_average( $id );
							if ( $this->average_review_for_popular <= $average ) {
								$rated[] = $id;
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

					if ( $listings->have_posts() ) {
						while ( $listings->have_posts() ) {
							$listings->the_post();
							$id = get_the_ID();
							$average    = ATBDP()->review->get_average( $id );
							if ( $this->average_review_for_popular <= $average ) {
								$rated[] = $id;
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

		$meta_queries = apply_filters( 'atbdp_all_listings_meta_queries', $meta_queries );
		$count_meta_queries = count( $meta_queries );

		if ( $count_meta_queries ) {
			$args['meta_query'] = ( $count_meta_queries > 1 ) ? array_merge( array( 'relation' => 'AND' ), $meta_queries ) : $meta_queries;
		}

		$args    = apply_filters( 'atbdp_all_listings_query_arguments', $args );
		$this->query = new WP_Query( $args );
	}

	public function render_shortcode() {
		wp_enqueue_script('adminmainassets');
		wp_enqueue_script('atbdp-search-listing', ATBDP_PUBLIC_ASSETS . 'js/search-form-listing.js');
		wp_localize_script('atbdp-search-listing', 'atbdp_search', array(
			'ajaxnonce' => wp_create_nonce('bdas_ajax_nonce'),
			'ajax_url' => admin_url('admin-ajax.php'),
			'added_favourite' => __('Added to favorite', 'directorist'),
			'please_login' => __('Please login first', 'directorist')
		));
		wp_enqueue_script('atbdp-range-slider');

		if ( 'kilometers' == $this->radius_search_unit ) {
			$miles = __( ' Kilometers', 'directorist' );
		}
		else {
			$miles = __( ' Miles', 'directorist' );
		}

		wp_localize_script( 'atbdp-range-slider', 'atbdp_range_slider', array(
			'Miles'       => $miles,
			'default_val' => $this->default_radius_distance
		));

		if (!empty($this->redirect_page_url)) {
			$redirect = '<script>window.location="' . esc_url($this->redirect_page_url) . '"</script>';
			return $redirect;
		}

		// Extention - Listings with Map //@todo
		if ( 'listings_with_map' == $this->view ) {
			$template_file = "listing-with-map/map-view";
			$extension_file = BDM_TEMPLATES_DIR . '/map-view';

			atbdp_get_shortcode_ext_template( $template_file, $extension_file, null, $this, true );
			return ob_get_clean();
		}

		$template_file = "listings-archive/listings-{$this->view}";

		return atbdp_return_shortcode_template( $template_file, array('listings' => $this) );
	}

    public function get_view_as_link_list() {
        $link_list = array();
        $view      = ! empty( $this->view ) ? $this->view : '';

        foreach ( $this->views as $value => $label ) {
            $active_class = ( $view === $value ) ? ' active' : '';
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
        $current_order = ! empty( $this->current_order ) ? $this->current_order : '';

        foreach ( $options as $value => $label ) {
            $active_class = ( $value == $current_order ) ? ' active' : '';
            $link         = add_query_arg( 'sort', $value );

            $link_item['active_class'] = $active_class;
            $link_item['link']         = $link;
            $link_item['label']        = $label;

            array_push( $link_list, $link_item );
        }

        return $link_list;
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

	public function loop( $loop = 'grid' ) {
		while ($this->query->have_posts()) {
			$this->query->the_post();
			$this->set_loop_data();
			atbdp_get_shortcode_template( "listings-archive/loop/$loop", array('listings' => $this) );
		}
		wp_reset_postdata();
	}

	public function render_map() {
		if ( 'google' == $this->select_listing_map ) {
			$this->load_google_map();
		}
		else {
			$this->load_openstreet_map();
		}
	}

    public function get_map_options() {
        $opt['select_listing_map']    = $this->select_listing_map;
        $opt['crop_width']            = get_directorist_option('crop_width', 360);
        $opt['crop_height']           = get_directorist_option('crop_height', 300);
        $opt['display_map_info']      = get_directorist_option('display_map_info', 1);
        $opt['display_image_map']     = get_directorist_option('display_image_map', 1);
        $opt['display_title_map']     = get_directorist_option('display_title_map', 1);
        $opt['display_address_map']   = get_directorist_option('display_address_map', 1);
        $opt['display_direction_map'] = get_directorist_option('display_direction_map', 1);
        $opt['zoom']                  = get_directorist_option('map_zoom_level', 16);
        $opt['default_image']         = get_directorist_option('default_preview_image', ATBDP_PUBLIC_ASSETS . 'images/grid.jpg');

        $opt['disable_single_listing'] = $this->disable_single_listing;

        $map_is_disabled = ( empty($opt['display_map_info']) && (empty($opt['display_image_map']) || empty($opt['display_title_map']) || empty($opt['display_address_map']) || empty($opt['display_direction_map']))) ? true : false;
        $opt['map_is_disabled'] = $map_is_disabled;

        return $opt;
    }

    public function load_openstreet_map() {
        $script_path = ATBDP_URL . 'templates/front-end/all-listings/maps/openstreet/js/subGroup-markercluster-controlLayers-realworld.388.js';
        $opt = $this->get_map_options();

        wp_enqueue_script('leaflet-subgroup-realworld');
        wp_localize_script( 'leaflet-subgroup-realworld', 'atbdp_map', $opt );
        wp_localize_script( 'leaflet-subgroup-realworld', 'atbdp_lat_lon', array(
            'lat'=>40.7128,
            'lon'=>74.0060,
        ));

        $this->render_osm_map_info_card('leaflet-subgroup-realworld');

        $map_height = $this->listings_map_height . "px;";
        echo "<div id='map' style='width: 100%; height: ${map_height};'></div>";
        
        wp_localize_script( 'leaflet-load-scripts', 'loc_data', [
            'script_path'  => $script_path
        ]);
        wp_enqueue_script('leaflet-load-scripts');
    }

    public function render_osm_map_info_card( $script_id = '' ) {
        $opt = $this->get_map_options();
        
        $listings_data = [];
        $lat_lon = [];

        while ( $this->query->have_posts() ) : $this->query->the_post();
            global $post; $ls_data = [];

            $ls_data['manual_lat']      = get_post_meta($post->ID, '_manual_lat', true);
            $ls_data['manual_lng']      = get_post_meta($post->ID, '_manual_lng', true);
            $ls_data['listing_img']     = get_post_meta(get_the_ID(), '_listing_img', true);
            $ls_data['listing_prv_img'] = get_post_meta(get_the_ID(), '_listing_prv_img', true);
            $ls_data['address']         = get_post_meta(get_the_ID(), '_address', true);

            $lat_lon = [ 
                'lat' => $ls_data['manual_lat'],
                'lon' => $ls_data['manual_lng']
            ];

            if ( ! empty( $ls_data['listing_prv_img']) ) {
                $ls_data['prv_image']   = atbdp_get_image_source( $ls_data['listing_prv_img'], 'large' );
            }

            $ls_data['default_image'] = get_directorist_option('default_preview_image', ATBDP_PUBLIC_ASSETS . 'images/grid.jpg');
            
            if ( ! empty( $ls_data['listing_img'][0] ) ) {
                $ls_data['gallery_img'] = atbdp_get_image_source($ls_data['listing_img'][0], 'medium');
            }

            $cats      = get_the_terms(get_the_ID(), ATBDP_CATEGORY);
            $font_type = get_directorist_option('font_type','line');

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
                atbdp_get_shortcode_template( 'listings-archive/loop/openstreet-map', $opt );
            }

            $ls_data['info_content'] = trim( ob_get_clean() );
            $listings_data[] = $ls_data;
        endwhile;

        wp_localize_script( $script_id, 'atbdp_lat_lon', $lat_lon );
        wp_localize_script( $script_id, 'listings_data', $listings_data );
    }

    public function load_google_map() {
        wp_enqueue_script('atbdp-map-view');

        $opt = $this->get_map_options();
        $disable_info_window = 'no';

        if (empty($opt['display_map_info'])) {
            $disable_info_window = 'yes';
        } elseif (empty($opt['display_image_map'] || $opt['display_title_map'] || $opt['display_address_map'] || $opt['display_direction_map'])){
            $disable_info_window = 'yes';
        }

        $data = array(
            'plugin_url'          => ATBDP_URL,
            'disable_info_window' => $disable_info_window,
            'zoom'                => $opt['zoom'],
        );
        wp_localize_script( 'atbdp-map-view', 'atbdp_map', $data );

        ?><div class="atbdp-body atbdp-map embed-responsive embed-responsive-16by9 atbdp-margin-bottom" data-type="markerclusterer" style="height: <?php echo !empty($this->listings_map_height)?$this->listings_map_height:'';?>px;"><?php
        while( $this->query->have_posts() ) : $this->query->the_post();
            global $post; $ls_data = [];

            $ls_data['post_id']         = $post->ID;
            $ls_data['manual_lat']      = get_post_meta($post->ID, '_manual_lat', true);
            $ls_data['manual_lng']      = get_post_meta($post->ID, '_manual_lng', true);
            $ls_data['listing_img']     = get_post_meta(get_the_ID(), '_listing_img', true);
            $ls_data['listing_prv_img'] = get_post_meta(get_the_ID(), '_listing_prv_img', true);
            $ls_data['crop_width']      = get_directorist_option('crop_width', 360);
            $ls_data['crop_height']     = get_directorist_option('crop_height', 300);
            $ls_data['address']         = get_post_meta(get_the_ID(), '_address', true);
            $ls_data['font_type']       = get_directorist_option('font_type','line');
            $ls_data['fa_or_la']        = ('line' == $ls_data['font_type']) ? "la " : "fa ";
            $ls_data['cats']            = get_the_terms(get_the_ID(), ATBDP_CATEGORY);

            if(!empty($ls_data['cats'])){
                $cat_icon = get_cat_icon($ls_data['cats'][0]->term_id);
            }

            $cat_icon = !empty($cat_icon) ? $cat_icon : 'fa-map-marker';
            $icon_type = substr($cat_icon, 0,2);
            $fa_or_la = ('la' == $icon_type) ? "la " : "fa ";
            $ls_data['cat_icon'] = ('none' == $cat_icon) ? 'fa fa-map-marker' : $fa_or_la . $cat_icon ;
            
            if (!empty($ls_data['listing_prv_img'])) {
                $ls_data['prv_image']   = atbdp_get_image_source($ls_data['listing_prv_img'], 'large');
            }

            if (!empty($ls_data['listing_img'][0])) {
                $ls_data['default_img'] = atbdp_image_cropping(ATBDP_PUBLIC_ASSETS . 'images/grid.jpg', $ls_data['crop_width'], $ls_data['crop_height'], true, 100)['url'];
                $ls_data['gallery_img'] = atbdp_get_image_source($ls_data['listing_img'][0], 'medium');
            }
            
            if ( ! empty( $ls_data['manual_lat'] ) && ! empty( $ls_data['manual_lng'] ) ) :
                $opt['ls_data'] = $ls_data;
                atbdp_get_shortcode_template( 'listings-archive/loop/google-map', $opt );
            endif;
        endwhile;
        wp_reset_postdata();

        echo "</div>";
    }

	public function loop_price_meta_html() {
		$html = atbdp_return_shortcode_template( 'global/price-meta', array('listings' => $this) );
		echo apply_filters('atbdp_listings_review_price', $html);
	}

	public function loop_data_list_html() {
		atbdp_get_shortcode_template( 'global/data-list', array('listings' => $this) );
	}

    public function loop_wrapper_class() {
        return ($this->loop['featured']) ? 'directorist-featured-listings' : '';
    }

	public function loop_link_attr() {
        $title_link_attr = " " . apply_filters('grid_view_title_link_add_attr', '');
        return trim($title_link_attr);
	}

	public function loop_thumbnail_link_attr() {
        return trim( ' ' . apply_filters( 'grid_view_thumbnail_link_add_attr', '' ) );
	}

	public function loop_title_link_attr() {
        return trim( ' ' . apply_filters( 'grid_view_title_link_add_attr', '' ) );
	}

    public function geolocation_field_data() {
        $geo_loc            = ( 'google' == $this->select_listing_map ) ? '<span class="atbd_get_loc la la-crosshairs"></span>' : '<span class="atbd_get_loc la la-crosshairs"></span>';

        $value       = ! empty( $_GET['address'] ) ? $_GET['address'] : '';
        $placeholder = ! empty( $this->location_placeholder ) ? sanitize_text_field( $this->location_placeholder ) : __( 'location', 'directorist' );
        $cityLat     = ( isset( $_GET['cityLat'] ) ) ? esc_attr( $_GET['cityLat'] ) : '';
        $cityLng     = ( isset( $_GET['cityLng'] ) ) ? esc_attr( $_GET['cityLng'] ) : '';

        wp_localize_script( 'atbdp-geolocation', 'adbdp_geolocation', array( 'select_listing_map' => $this->select_listing_map ) );
        wp_enqueue_script( 'atbdp-geolocation' );

        $data = array(
            'select_listing_map' => $this->select_listing_map,
            'geo_loc'            => $geo_loc,
            'value'              => $value,
            'placeholder'        => $placeholder,
            'cityLat'            => $cityLat,
            'cityLng'            => $cityLng,
        );

        return $data;
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

    public function has_any_price_field() {
        if ( in_array( 'search_price', $this->search_more_filters_fields ) || in_array( 'search_price_range', $this->search_more_filters_fields ) ) {
            return true;
        }

        return false;
    }

    public function location_field_type( $type ) {
        if ( ! $this->has_location_field() ) {
            return false;
        }

        if ( $type !== $this->listing_location_address ) {
            return false;
        }

        return true;
    }

    public function price_field_data() {
        $min_price_value = ( isset( $_GET['price'] ) ) ? esc_attr( $_GET['price'][0] ) : '';
        $max_price_value = ( isset( $_GET['price'] ) ) ? esc_attr( $_GET['price'][1] ) : '';

        return compact( 'min_price_value', 'max_price_value' );
    }

    public function price_range_field_data() {
        $bellow_economy_value = '';
        $economy_value        = '';
        $moderate_value       = '';
        $skimming_value       = '';

        if ( ! empty( $_GET['price_range'] ) ) {
            $bellow_economy_value = ( 'bellow_economy' == $_GET['price_range'] ) ? "checked='checked'" : '';
            $economy_value        = ( 'economy' == $_GET['price_range'] ) ? "checked='checked'" : '';
            $moderate_value       = ( 'moderate' == $_GET['price_range'] ) ? "checked='checked'" : '';
            $skimming_value       = ( 'skimming' == $_GET['price_range'] ) ? "checked='checked'" : '';
        }

        $data = compact(
            'bellow_economy_value',
            'economy_value',
            'moderate_value',
            'skimming_value'
        );

        return $data;
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

    public function tag_field_data() {
        $listing_tags_field = get_directorist_option( 'listing_tags_field', 'all_tags' );
        $category_slug      = get_query_var( 'atbdp_category' );
        $category           = get_term_by( 'slug', $category_slug, ATBDP_CATEGORY );
        $category_id        = ! empty( $category_slug ) ? $category->term_id : '';
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

    public function open_now_field_data() {
        $checked = ! empty( $_GET['open_now'] ) && 'open_now' == $_GET['open_now'] ? " checked='checked'" : '';

        return $checked;
    }

    public function has_category_field() {
        return in_array( 'search_category', $this->search_more_filters_fields );
    }

    public function has_location_field() {
        return in_array( 'search_location', $this->search_more_filters_fields );
    }

    public function has_open_now_field() {
        $active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
        $plugin_path    = 'directorist-business-hours/bd-business-hour.php';

        return in_array( 'search_open_now', $this->search_more_filters_fields ) && in_array( $plugin_path, $active_plugins );
    }

    public function has_price_field() {
        return in_array( 'search_price', $this->search_more_filters_fields );
    }

    public function has_price_range_field() {
        return in_array( 'search_price_range', $this->search_more_filters_fields );
    }

    public function has_radius_search_field() {
        return ( 'map_api' == $this->listing_location_address && in_array( 'radius_search', $this->search_more_filters_fields ) );
    }

    public function has_rating_field() {
        return in_array( 'search_rating', $this->search_more_filters_fields );
    }

    public function has_search_field() {
        return in_array( 'search_text', $this->search_more_filters_fields );
    }

    public function has_tag_field() {
        return in_array( 'search_tag', $this->search_more_filters_fields );
    }

    public function filter_container_class() {
        echo ( 'overlapping' === $this->filters_display ) ? 'ads_float' : 'ads_slide';
    }

    public function listing_wrapper_class() {
        echo ($this->loop['featured']) ? 'directorist-featured-listings' : '';
    }

    public function grid_container_fluid() {
        $container = is_directoria_active() ? 'container' : 'container-fluid';
        return apply_filters( 'atbdp_listings_grid_container_fluid', $container );
    }

    public function sortby_dropdown_html() {
        $html = atbdp_return_shortcode_template( 'listings-archive/sortby-dropdown', array('listings' => $this) );
        echo apply_filters('atbdp_listings_header_sort_by_button', $html);
    }

    public function viewas_dropdown_html() {
        $html = atbdp_return_shortcode_template( 'listings-archive/viewas-dropdown', array('listings' => $this) );
        echo apply_filters('atbdp_listings_view_as', $html, $this->view, $this->views);
    }

    public function advanced_search_form_html() {
        atbdp_get_shortcode_template( 'listings-archive/advanced-search-form', array('listings' => $this) );
    }
}