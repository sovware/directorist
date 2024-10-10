<?php
/**
 * @author wpWax
 */

namespace Directorist;

if ( ! defined( 'ABSPATH' ) ) exit;

class Directorist_Listing_Search_Form {

	// Search Shortcode
	public $options = [];
	public $type;
	public $listing_type;
	public $form_data;

	public $atts;
	public $defaults;
	public $params;

	public $show_title_subtitle;
	public $has_search_button;
	public $has_more_filters_button;
	public $logged_in_user_only;
	public $redirect_page_url;
	public $search_bar_title;
	public $search_bar_sub_title;
	public $search_button_text;
	public $more_filters_text;
	public $more_filters_display;
	public $show_connector;
	public $connectors_title;
	public $popular_cat_title;
	public $popular_cat_num;
	public $show_popular_category;
	public $directory_type;
	public $default_directory_type;

	// Common - Search Shortcode and Listing Header
	public $has_reset_filters_button;
	public $has_apply_filters_button;
	public $reset_filters_text;
	public $apply_filters_text;

	public $c_symbol;
	public $category_id;
	public $category_class;
	public $location_id;
	public $location_class;
	public $select_listing_map;

	protected $deferred_data = array();

	protected $deferred_props = array(
		'categories_fields',
		'locations_fields',
	);

	public function __construct( $type, $directory_id = 0, $atts = array() ) {
		$this->type = $type;
		$this->atts = $atts;

		$this->set_default_options();

		// Search form shortcode
		if ( $type === 'search_form' ) {
			$this->update_options_for_search_form();
			$this->prepare_search_data( $atts );
		}

		if ( directorist_is_directory( $directory_id ) ) {
			$this->listing_type = (int) $directory_id;
		} else {
			$this->listing_type = $this->get_default_directory();
		}

		// Search result page
		if ( $type === 'search_result' || $type === 'instant_search' ) {
			$this->update_options_for_search_result_page();
			$this->prepare_search_data( $atts );
		}

		// Listing Archive page
		if ( $type === 'listing' ) {
			$this->prepare_listing_data();
		}

		$this->form_data          = $this->build_form_data();
		$this->c_symbol           = atbdp_currency_symbol( directorist_get_currency() );
		$this->select_listing_map = get_directorist_option( 'select_listing_map', 'google' );
		// $this->categories_fields  = search_category_location_filter( $this->search_category_location_args(), ATBDP_CATEGORY );
		// $this->locations_fields   = search_category_location_filter( $this->search_category_location_args(), ATBDP_LOCATION );
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

	// set_default_options
	public function set_default_options() {
		$this->options['more_filters_fields']     = get_directorist_option( 'listing_filters_fields', array( 'search_text', 'search_category', 'search_location', 'search_price', 'search_price_range', 'search_rating', 'search_tag', 'search_custom_fields', 'radius_search' ) );
		$this->options['search_fields']           = get_directorist_option('search_tsc_fields', array('search_text', 'search_category', 'search_location'));
		$this->options['search_filters']          = get_directorist_option('listings_filters_button', array('search_reset_filters', 'search_apply_filters'));
		$this->options['search_listing_text']     = get_directorist_option('search_listing_text', __('Search Listing', 'directorist'));
		$this->options['search_more_filter']      = !empty( get_directorist_option( 'search_more_filter', 1 ) ) ? 'yes' : '';
		$this->options['search_more_filters']     = get_directorist_option('search_more_filters', __('More Filters', 'directorist'));
		$this->options['search_button']           = !empty( get_directorist_option( 'search_button', 1 ) ) ? 'yes' : '';
		$this->options['search_placeholder']      = get_directorist_option('listings_search_text_placeholder', __('What are you looking for?', 'directorist'));
		$this->options['filters_buttons']         = get_directorist_option( 'listings_filters_button', array( 'reset_button', 'apply_button' ) );

		$this->options['more_filters_button']        	= get_directorist_option( 'listing_filters_button', 1 );
		$this->options['display_more_filter_icon']   	= get_directorist_option('listing_filters_icon', 1);
		$this->options['display_search_button_icon'] 	= get_directorist_option('listing_filters_icon', 1);
		$this->options['open_filter_fields']         	= get_directorist_option('listings_display_filter', 'sliding');

		$this->options['reset_filters_text']      		= get_directorist_option('listings_reset_text', __('Reset Filters', 'directorist'));
		$this->options['reset_sidebar_filters_text']    = get_directorist_option( 'listings_sidebar_reset_text', __('Clear All', 'directorist') );
		$this->options['apply_filters_text']      		= get_directorist_option( 'listings_apply_text', __( 'Apply Filters', 'directorist' ) );
	}

	// update_options_for_search_result_page
	public function update_options_for_search_result_page() {
		$this->options['more_filters_fields']     		= get_directorist_option('search_result_filters_fields', array('search_price', 'search_price_range', 'search_rating', 'search_tag', 'search_custom_fields', 'radius_search'));
		$this->options['search_filters']          		= get_directorist_option('search_result_filters_button', array( 'reset_button', 'apply_button' ), true);
		$this->options['more_filters_button']     		= get_directorist_option( 'search_result_filters_button_display', 1 );
		$this->options['reset_filters_text']      		= get_directorist_option('sresult_reset_text', __('Reset Filters', 'directorist'));
		$this->options['reset_sidebar_filters_text']    = get_directorist_option( 'sresult_sidebar_reset_text', __('Clear All', 'directorist') );
		$this->options['apply_filters_text']      		= get_directorist_option( 'sresult_apply_text', __( 'Apply Filters', 'directorist' ) );
	}

	// update_options_for_search_form
	public function update_options_for_search_form() {
		$this->options['more_filters_fields'] = get_directorist_option('search_more_filters_fields', array( 'search_price', 'search_price_range', 'search_rating', 'search_tag', 'search_custom_fields', 'radius_search'));

		$this->options['search_filters']             = get_directorist_option('search_filters', array('search_reset_filters', 'search_apply_filters'), true );
		$this->options['more_filters_button']        = get_directorist_option( 'search_more_filter', 1 );
		$this->options['display_more_filter_icon']   = get_directorist_option('search_more_filter_icon', 1);
		$this->options['display_search_button_icon'] = get_directorist_option('search_button_icon', 1);
		$this->options['open_filter_fields']         = get_directorist_option('home_display_filter', 'sliding');

		$this->options['reset_filters_text']      = get_directorist_option( 'search_reset_text', __('Reset Filters', 'directorist'));
		$this->options['apply_filters_text']      = get_directorist_option( 'search_apply_filter', __( 'Apply Filters', 'directorist' ) );
	}

	// prepare_search_data
	public function prepare_search_data($atts) {
		$search_more_filters_fields = $this->options['more_filters_fields'];
		$search_filters             = $this->options['search_filters'];

		$search_fields        = $search_more_filters_fields;
		$reset_filters_button = in_array('reset_button', $search_filters) ? 'yes' : '';
		$apply_filters_button = in_array('apply_button', $search_filters) ? 'yes' : '';

		if ( 'search_form' === $this->type ) {
			$search_fields = $this->options['search_fields'];
			$reset_filters_button = in_array('search_reset_filters', $search_filters) ? 'yes' : '';
			$apply_filters_button = in_array('search_apply_filters', $search_filters) ? 'yes' : '';
		}

		$this->defaults = array(
			'show_title_subtitle'    		=> 'yes',
			'search_bar_title'       		=> get_directorist_option('search_title', __("Search here", 'directorist')),
			'search_bar_sub_title'  		=> get_directorist_option('search_subtitle', __("Find the best match of your interest", 'directorist')),
			'search_button'          		=> $this->options['search_button'],
			'search_button_text'     		=> $this->options['search_listing_text'],
			'more_filters_button'    		=> ( $this->options['more_filters_button'] ) ? 'yes' : '',
			'more_filters_text'      		=> $this->options['search_more_filters'],
			'reset_filters_button'   		=> $reset_filters_button,
			'apply_filters_button'   		=> $apply_filters_button,
			'reset_filters_text'     		=> $this->options['reset_filters_text'],
			'apply_filters_text'     		=> $this->options['apply_filters_text'],
			'logged_in_user_only'    		=> '',
			'redirect_page_url'      		=> '',
			'more_filters_display'   		=> $this->options['open_filter_fields'],
			'directory_type'         		=> '',
			'default_directory_type'        => '',
			'show_popular_category'			=> ! empty( get_directorist_option('show_popular_category', 1 ) ) ? 'yes' : ''
		);

		$this->params = shortcode_atts( $this->defaults, $this->atts );

		$this->show_title_subtitle      = $this->params['show_title_subtitle'] == 'yes' ? true : false;
		$this->has_search_button        = $this->params['search_button'] == 'yes' ? true : false;
		$this->has_more_filters_button  = $this->params['more_filters_button'] == 'yes' ? true : false;
		$this->has_reset_filters_button = $this->params['reset_filters_button'] == 'yes' ? true : false;
		$this->has_apply_filters_button = $this->params['apply_filters_button'] == 'yes' ? true : false;
		$this->logged_in_user_only      = $this->params['logged_in_user_only'] == 'yes' ? true : false;
		$this->show_connector           = !empty( get_directorist_option('show_connector', 1) ) ? true : false;
		$this->show_popular_category    = ( 'yes' == $this->params['show_popular_category'] ) ? true : false;

		$this->search_bar_title     	= $this->params['search_bar_title'];
		$this->search_bar_sub_title 	= $this->params['search_bar_sub_title'];
		$this->search_button_text   	= $this->params['search_button_text'];
		$this->more_filters_text    	= $this->params['more_filters_text'];
		$this->reset_filters_text   	= $this->params['reset_filters_text'];
		$this->apply_filters_text   	= $this->params['apply_filters_text'];
		$this->more_filters_display 	= $this->params['more_filters_display'];
		$this->redirect_page_url    	= $this->params['redirect_page_url'];
		$this->directory_type           = !empty( $this->params['directory_type'] ) ? explode( ',', $this->params['directory_type'] ) : '';
		$this->default_directory_type   = !empty( $this->params['default_directory_type'] ) ? $this->params['default_directory_type'] : '';

		$this->category_id             = '';
		$this->category_class          = 'search_fields bdas-category-search directorist-category-select';
		$this->location_id             = '';
		$this->location_class          = 'search_fields directorist-location-select';
		$this->connectors_title        = get_directorist_option('connectors_title', __('Or', 'directorist'));
		$this->popular_cat_title       = get_directorist_option('popular_cat_title', __('Browse by popular categories', 'directorist'));
		$this->popular_cat_num         = get_directorist_option('popular_cat_num', 10);
	}

	public function prepare_listing_data() {
		$filters_buttons                = get_directorist_option( 'listings_filters_button', array( 'reset_button', 'apply_button' ), true );
		$this->has_reset_filters_button = in_array( 'reset_button', $filters_buttons ) ? true : false;
		$this->has_apply_filters_button = in_array( 'apply_button', $filters_buttons ) ? true : false;
		$this->reset_filters_text       = get_directorist_option('listings_reset_text', __('Reset Filters', 'directorist'));
		$this->apply_filters_text       = get_directorist_option( 'listings_apply_text', __( 'Apply Filters', 'directorist' ) );

		$this->category_id             = 'cat-type';
		$this->category_class          = 'directory_field bdas-category-search directorist-category-select';
		$this->location_id             = 'loc-type';
		$this->location_class          = 'directory_field bdas-category-location directorist-location-select';
	}

	public function build_search_data( $data ) {
		$search_form_fields = get_term_meta( $this->listing_type, 'search_form_fields', true );
		return $search_form_fields['fields'][ $data ];
	}

	/**
	 * Get default directory id.
	 *
	 * @deprecated 8.0 Use get_default_directory() instead.
	 *
	 * @return int Default directory id.
	 */
	public function get_default_listing_type() {
		return $this->get_default_directory();
	}

	public function get_default_directory() {
		$default_directory_id = 0;

		if ( $this->default_directory_type ) {
			$field = 'slug';

			if ( is_numeric( $this->default_directory_type ) ) {
				$field = 'id';
			}

			$default_directory_term = get_term_by( $field, $this->default_directory_type, ATBDP_DIRECTORY_TYPE );

			if ( $default_directory_term ) {
				$default_directory_id = (int) $default_directory_term->term_id;
			}
		} else {
			$default_directory_id = (int) directorist_get_default_directory();
		}

		if ( $this->directory_type && is_array( $this->directory_type ) ) {
			$directories = directorist_get_directories( array(
				'fields'     => 'ids',
				'slug'       => $this->directory_type,
			) );

			if ( ! is_wp_error( $directories ) && ! empty( $directories ) && ! in_array( $default_directory_id, $directories, true ) ) {
				$default_directory_id = $directories[0];
			}
		}

		return $default_directory_id;
	}

	public function build_form_data() {
		$form_data          = array();
		$search_form_fields     = get_term_meta( $this->listing_type, 'search_form_fields', true );
		$submission_form_fields = get_term_meta( $this->listing_type, 'submission_form_fields', true );

		if ( !empty( $search_form_fields['fields'] ) ) {

			foreach ( $search_form_fields['fields'] as $key => $value ) {

				if ( ! is_array( $value) ) {
					continue;
				}

				$search_form_fields['fields'][$key]['field_key'] = '';
				$search_form_fields['fields'][$key]['options'] = [];

				$form_key = isset( $value['original_widget_key'] ) ? $value['original_widget_key'] : '';

				unset( $search_form_fields['fields'][$key]['widget_key'] );
				unset( $search_form_fields['fields'][$key]['original_widget_key'] );

				if ( $form_key ) {
					if ( !empty( $submission_form_fields['fields'][$form_key]['field_key'] ) ) {
						$search_form_fields['fields'][$key]['field_key'] = $submission_form_fields['fields'][$form_key]['field_key'];
					}

					if ( !empty( $submission_form_fields['fields'][$form_key] ) ) {
						$search_form_fields['fields'][$key]['options'] = $submission_form_fields['fields'][$form_key];
					}
				}

			}
		}

		if ( !empty( $search_form_fields['groups'] ) ) {
			foreach ( $search_form_fields['groups'] as $group ) {
				$section           = $group;
				$section['fields'] = array();

				foreach ( $group['fields'] as $field ) {
					$search_field = $search_form_fields['fields'][$field];

					if ( $this->is_field_allowed_in_atts( $search_field['widget_name'] ) ) {
						$section['fields'][ $field ] = $search_field;
					}
				}

				$form_data[] = $section;
			}
		}

		return $form_data;
	}

	public function is_field_allowed_in_atts( $widget_name ) {
		$atts = ! empty( $this->atts[ 'filter_' . $widget_name ] ) ? $this->atts[ 'filter_' . $widget_name ] : '';

		if ( 'no' == $atts ){
			return false;
		}
		return true;
	}

	public function buttons_template() {
		if ($this->has_reset_filters_button || $this->has_apply_filters_button) {
			Helper::get_template( 'search-form/buttons', array('searchform' => $this) );
		}
	}

	public function range_slider_unit( $data ) {
		$radius_search_unit = !empty( $data['radius_search_unit'] ) ? $data['radius_search_unit'] : '';
		return ( 'kilometers' == $radius_search_unit ) ? __( 'Kilometers', 'directorist' ) : __( 'Miles', 'directorist' );
	}

	public function range_slider_minValue( $data ){
		return !empty( $_REQUEST['miles'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['miles'] ) ) : $data['default_radius_distance'];
	}

	public function range_slider_data( $data ) {

		$data = [
			'miles' => $this->range_slider_unit( $data ),
			'minValue' => $this->range_slider_minValue( $data ),
		];

		return json_encode( $data );;
	}

	public function get_pricing_type() {
		$submission_form_fields = get_term_meta( $this->listing_type, 'submission_form_fields', true );
		$ptype = !empty( $submission_form_fields['fields']['pricing']['pricing_type'] ) ? $submission_form_fields['fields']['pricing']['pricing_type'] : 'both';
		return $ptype;
	}

	// custom field assign to category
	public function assign_to_category(){
		$submission_form_fields = get_term_meta( $this->listing_type , 'submission_form_fields', true );
		$category_id = isset( $_REQUEST['cat_id'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['cat_id'] ) ) : '';
		$custom_field_key = array();
		$assign_to_cat = array();

		if( $submission_form_fields['fields'] ) {
			foreach( $submission_form_fields['fields'] as $field ) {
				if( ! empty( $field['assign_to'] ) && $category_id != $field['category'] ) {
					$custom_field_key[] = $field['field_key'];
					$assign_to_cat[]	= $field['category'];
				}
			}
		}

		$category_custom_field = array(
			'custom_field_key'	=> $custom_field_key,
			'assign_to_cat'		=> $assign_to_cat,
		);
		return $category_custom_field;
	}

	public function field_template( $field_data ) {
		$key = $field_data['field_key'];

		$field_data['lazy_load'] = get_directorist_option( 'lazy_load_taxonomy_fields', true );

		if ( $this->is_custom_field( $field_data ) ) {
			if ( !empty( $_REQUEST['custom_field'][$key] ) ) {
				$value = is_array( $_REQUEST['custom_field'][$key] ) ? array_map( 'sanitize_text_field', wp_unslash( $_REQUEST['custom_field'][$key] ) ) : sanitize_text_field( wp_unslash( $_REQUEST['custom_field'][$key] ) );
			} else {
				$value = '';
			}
		}
		else {
			$value = $key && isset( $_REQUEST[$key] ) ? sanitize_text_field( wp_unslash( $_REQUEST[$key] ) ): '';
		}

		$args = array(
			'searchform' 		=> $this,
			'data'       		=> $field_data,
			'value'      		=> $value,
		);

		// if ( $this->is_custom_field( $field_data ) && ( ! in_array( $field_data['field_key'], $this->assign_to_category()['custom_field_key'] ) ) ) {
		// 	if( ! empty( $field_data['type'] ) && 'number' != $field_data['type'] ) {
		// 		$template = 'search-form/custom-fields/number/' . $field_data['type'];
		// 	} else {
		// 		$template = 'search-form/custom-fields/' . $field_data['widget_name'];
		// 	}
		// }

		if ( $this->is_custom_field( $field_data ) ) {
			if ( ! empty( $field_data['type'] ) && 'number' !== $field_data['type'] ) {
				$template = 'search-form/custom-fields/number/' . $field_data['type'];
			} else {
				$template = 'search-form/custom-fields/' . $field_data['widget_name'];
			}
		} else {
			$template = 'search-form/fields/' . $field_data['widget_name'];
		}

		$template = apply_filters( 'directorist_search_field_template', $template, $field_data );
		Helper::get_template( $template, $args );
	}

	public function is_custom_field( $data ) {
		$fields = [ 'checkbox', 'color_picker', 'date', 'file', 'number', 'radio', 'select', 'text', 'textarea', 'time', 'url' ];

		return in_array( $data['widget_name'], $fields, true );
	}

	public function get_listing_type_data() {
		$args = array();

		if ( $this->directory_type ) {
			$args['slug'] = $this->directory_type;
		}

		return directorist_get_directories_for_template( $args );
	}


	public function directory_type_nav_template() {
		if ( count( $this->get_listing_type_data() ) < 2 || ! directorist_is_multi_directory_enabled() ) {
			return;
		}

		Helper::get_template( 'search-form/directory-type-nav', [ 'searchform' => $this ] );
	}

	public function has_more_filters_icon() {
		$more_filters_icon = $this->options['display_more_filter_icon'];
		return !empty( $more_filters_icon ) ? true : false;
	}

	public function has_search_button_icon() {
		$search_button_icon = $this->options['display_search_button_icon'];
		return !empty( $search_button_icon ) ? true : false;
	}

	public function get_basic_fields() {
		return ! empty( $this->form_data[0]['fields'] ) && is_array( $this->form_data[0]['fields'] )
        ? $this->form_data[0]['fields']
        : [];
	}

	public function get_advance_fields() {
		return ! empty( $this->form_data[1]['fields'] ) && is_array( $this->form_data[1]['fields'] )
        ? $this->form_data[1]['fields']
        : [];
	}

	public function more_buttons_template() {
		Helper::get_template( 'search-form/more-buttons', [ 'searchform' => $this ] );
	}

	public function advanced_search_form_basic_fields_template() {
		Helper::get_template( 'search-form/basic-search', array('searchform' => $this) );
	}

	public function advanced_search_form_fields_template() {
		Helper::get_template( 'search-form/adv-search', array('searchform' => $this) );
	}

	public function top_categories() {
		$top_categories = [];

		$args = array(
			'type'          => ATBDP_POST_TYPE,
			'parent'        => 0,
			'orderby'       => 'count',
			'order'         => 'desc',
			'hide_empty'    => 1,
			'number'        => (int)$this->popular_cat_num,
			'taxonomy'      => ATBDP_CATEGORY,
			'no_found_rows' => true,
		);

		$cats = get_categories( $args );

		foreach ( $cats as $cat ) {
			$directory_type 	 = get_term_meta( $cat->term_id, '_directory_type', true );
			$directory_type 	 = ! empty( $directory_type ) ? $directory_type : array();
			$listing_type_id     = $this->listing_type;

			if( in_array( $listing_type_id, $directory_type ) ) {
				$top_categories[] = $cat;
			}
		}

		return $top_categories;
	}

	public function top_categories_template() {
		if ( $this->show_popular_category ) {
			$top_categories = $this->top_categories();
			$title = get_directorist_option( 'popular_cat_title', __( 'Browse by popular categories', 'directorist' ) );

			if ( !empty($top_categories) ) {
				$args = array(
					'searchform'      => $this,
					'top_categories'  => $top_categories,
					'title'           => $title,
				);
				Helper::get_template( 'search-form/top-cats', $args );
			}
		}
	}

	public function search_category_location_args() {
		return array(
			'parent'                       => 0,
			'term_id'                      => 0,
			'hide_empty'                   => 0,
			'orderby'                      => 'name',
			'order'                        => 'asc',
			'show_count'                   => 0,
			'single_only'                  => 0,
			'pad_counts'                   => true,
			'immediate_category'           => 0,
			'active_term_id'               => 0,
			'ancestors'                    => array(),
			'listing_type'                 => $this->listing_type,
			'categories_with_custom_field' => array_values( directorist_get_category_custom_field_relations( $this->listing_type ) )
		);
	}

	public function price_value($arg) {
		if ( $arg == 'min' ) {
			return isset( $_REQUEST['price'][0] ) ? sanitize_text_field( wp_unslash( $_REQUEST['price'][0] ) ) : '';
		}

		if ( $arg == 'max' ) {
			return isset( $_REQUEST['price'][1] ) ? sanitize_text_field( wp_unslash( $_REQUEST['price'][1] ) ) : '';
		}

		return '';
	}

	public function the_price_range_input($range) {
		$checked = ! empty( $_REQUEST['price_range'] ) && $_REQUEST['price_range'] == $range ? ' checked="checked"' : '';
		printf('<input type="radio" name="price_range" value="%s"%s>', esc_attr( $range ), esc_attr( $checked ) );
	}

	public function get_atts_data() {
		$this->params['category_custom_fields_relations'] = directorist_get_category_custom_field_relations( $this->listing_type );

		return json_encode( $this->params );
	}

	public function render_search_shortcode( $atts = [] ) {

		if ( $this->logged_in_user_only && ! is_user_logged_in() ) {
			return ATBDP()->helper->guard( array('type' => 'auth') );
		}

		if ($this->redirect_page_url) {
			$redirect = '<script>window.location="' . esc_url($this->redirect_page_url) . '"</script>';
			return $redirect;
		}

		return Helper::get_template_contents( 'search-form-contents', [ 'searchform' => $this ] );
	}

	public function listing_type_slug() {
		$term_data = get_term( $this->listing_type, ATBDP_TYPE );
		if ( is_wp_error( $term_data ) ) {
			return '';
		}
		return $term_data->slug;
	}

	public function background_img_style() {
		$bgimg = get_directorist_option('search_home_bg');
		$style = !empty( $bgimg ) ? sprintf( "background-image: url(%s)", esc_url( $bgimg ) ) : '';
		return $style;
	}

	public function border_class() {
		$search_border = get_directorist_option( 'search_border', 1 );
		return empty( $search_border ) ? 'directorist-no-search-border' : 'directorist-with-search-border';
	}

	public function category_icon_class($cat) {
		$icon = get_cat_icon($cat->term_id);
		$icon_type = substr($icon, 0, 2);
		$icon_class = ('la' === $icon_type) ? $icon_type . ' ' . $icon : 'fa ' . $icon;
		return $icon_class;
	}

	public function zip_code_class() {
		$class 					= 'directorist-form-element';
		$radius_search 			= $this->build_search_data( 'radius_search' );
		$radius_search_based_on = ! empty( $radius_search['radius_search_based_on'] ) ? $radius_search['radius_search_based_on'] : 'address';

		if( ! empty( $radius_search ) && 'zip' == $radius_search_based_on ) {
			$class .= ' zip-radius-search';
		}
		return $class;
	}

	public function rating_field_data() {
		$search_by_rating = ! empty( $_REQUEST['search_by_rating'] ) ? $_REQUEST['search_by_rating'] : array();

		$rating_options = array(
			array(
				'checked' => ( is_array( $search_by_rating ) && in_array( '5', $search_by_rating, true ) ) ? ' checked' : '',
				'value'    => '5',
				'label'    => __( '5 Star', 'directorist' ),
			),
			array(
				'checked' => ( is_array( $search_by_rating ) && in_array( '4', $search_by_rating, true ) ) ? ' checked' : '',
				'value'    => '4',
				'label'    => __( '4 Star & Up', 'directorist' ),
			),
			array(
				'checked' => ( is_array( $search_by_rating ) && in_array( '3', $search_by_rating, true )  ) ? ' checked' : '',
				'value'    => '3',
				'label'    => __( '3 Star & Up', 'directorist' ),
			),
			array(
				'checked' => ( is_array( $search_by_rating ) && in_array( '2', $search_by_rating, true )  ) ? ' checked' : '',
				'value'    => '2',
				'label'    => __( '2 Star & Up', 'directorist' ),
			),
			array(
				'checked' => ( is_array( $search_by_rating ) && in_array( '1', $search_by_rating, true )  ) ? ' checked' : '',
				'value'    => '1',
				'label'    => __( '1 Star & Up', 'directorist' ),
			),
		);

		return $rating_options;
	}

	public function listing_tag_terms($tag_source='all_tags') {
		$category_slug   = get_query_var( 'atbdp_category' );
		$category        = get_term_by( 'slug', $category_slug, ATBDP_CATEGORY );
		$category_id     = ! empty( $category->term_id ) ? $category->term_id : '';
		$category_select = ! empty( $_REQUEST['in_cat'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['in_cat'] ) ) : $category_id;

		if ( 'all_tags' == $tag_source || empty( $category_select ) ) {
			$terms = get_terms( ATBDP_TAGS );
		} else {
			$tag_args = array(
				'post_type' => ATBDP_POST_TYPE,
				'tax_query' => array(
					array(
						'taxonomy' => ATBDP_CATEGORY,
						'terms'    => ! empty( $_REQUEST['in_cat'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['in_cat'] ) ) : $category_id,
					),
				),
			);

			$tag_posts       = get_posts( $tag_args );
			if ( ! empty( $tag_posts ) ) {
				foreach ( $tag_posts as $tag_post ) {
					$tag_id[] = $tag_post->ID;
				}
			}
			$tag_id = ! empty( $tag_id ) ? $tag_id : '';
			$terms  = wp_get_object_terms( $tag_id, ATBDP_TAGS );
		}

		if ( ! empty( $terms ) ) {
			return $terms;
		} else {
			return array();
		}
	}

	public static function get_selected_category_option_data() {
		$id = ( isset( $_REQUEST['in_cat'] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['in_cat'] ) ) : '';
		$id = ( isset( $_REQUEST['cat_id'] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['cat_id'] ) ) : $id;

		return self::get_taxonomy_select_option_data( $id );
	}

	public static function get_selected_location_option_data() {
		$id = ( isset( $_REQUEST['in_loc'] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['in_loc'] ) ) : '';
		$id = ( isset( $_REQUEST['loc_id'] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['loc_id'] ) ) : $id;

		return self::get_taxonomy_select_option_data( $id );
	}

	public static function get_taxonomy_select_option_data( $id ) {
		$item = [ 'id' => '', 'label' => '' ];

		if ( empty( $id ) ) {
			return $item;
		}

		$taxonomy = get_term( $id );

		if ( is_wp_error( $taxonomy ) ) {
			return $item;
		}

		$item[ 'id' ]    = $taxonomy->term_id;
		$item[ 'label' ] = $taxonomy->name;

		return $item;
	}

	public function load_radius_search_scripts( $data ) {
		_deprecated_function( __METHOD__, '7.1' );
	}

	public function load_map_scripts() {
		_deprecated_function( __METHOD__, '7.3' );
	}
}
