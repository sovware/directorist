<?php
/**
 * Deprecated functions.
 *
 * @package wpWax\Directorist\Model
 * @author  wpWax
 */

namespace wpWax\Directorist\Model;

if ( ! defined( 'ABSPATH' ) ) exit;

trait Deprecated_Listings {

	public function loop_thumb_card_template() {
		_deprecated_function( 'loop_thumb_card_template', '7.1.2', 'loop_thumbnail_template' );
		$this->loop_thumbnail_template();
	}

	public function loop_get_published_date( $data ) {
		_deprecated_function( 'loop_get_published_date', '7.1.2' );
		$publish_date_format = $data['date_type'];
		if ('days_ago' === $publish_date_format) {
			$text = sprintf(__('Posted %s ago', 'directorist'), human_time_diff(get_the_time('U'), current_time('timestamp')));
		}
		else {
			$text = get_the_date();
		}
		return $text;
	}

	public function loop_get_review_average() {
		_deprecated_function( 'loop_get_review_average', '7.1.2', 'loop_rating_average' );
		return $this->loop_rating_average();
	}

	public function select_listing_map() {
		_deprecated_function( 'select_listing_map', '7.1.2', 'map_type' );
		return $this->map_type();
	}

	public function filters_display() {
		_deprecated_function( 'filters_display', '7.1.2', 'filter_open_method' );
		return $this->filter_open_method();
	}

	public function has_listings_header() {
		_deprecated_function( 'has_listings_header', '7.1.2' );
		return true;
	}

	public function header_title() {
		_deprecated_function( 'header_title', '7.1.2' );
		return $this->atts['header_title'];
	}

	public function item_found_title() {
		_deprecated_function( 'item_found_title', '7.1.2' );
		return $this->item_found_text();
	}

	public function item_found_title_for_search() {
		_deprecated_function( 'item_found_title_for_search', '7.1.2' );
		return $this->item_found_text();
	}

	public function header() {
		_deprecated_function( 'header', '7.1.2' );
		return $this->atts['header'] == 'yes' ? true : false;
	}

	public function has_filters_button() {
		_deprecated_function( 'has_filters_button', '7.1.2', 'display_search_form' );
		return $this->display_search_form();
	}

	public function has_filters_icon() {
		_deprecated_function( 'has_filters_icon', '7.1.2', 'display_search_filter_icon' );
		return $this->display_search_filter_icon();
	}

	public function filter_btn_html() {
		_deprecated_function( 'filter_btn_html', '7.1.2' );
		if ( $this->display_search_filter_icon() ) {
			return sprintf( '<span class="%s-filter"></span> %s', atbdp_icon_type(), $this->filter_button_text() );
		}
		else {
			return $this->filter_button_text();
		}
	}

	public function has_header_toolbar() {
		_deprecated_function( 'has_header_toolbar', '7.1.2' );
		return ( $this->display_viewas_dropdown() || $this->display_sortby_dropdown() ) ? true : false;
	}

	public function has_featured() {
		_deprecated_function( 'has_featured', '7.1.2', 'monetize_by_featued_enabled' );
		return monetize_by_featued_enabled();
	}

	public function get_sort_by_link_list() {
		_deprecated_function( 'get_sort_by_link_list', '7.1.2' );
		return [];
	}

	public function get_view_as_link_list() {
		_deprecated_function( 'get_view_as_link_list', '7.1.2' );
		return [];
	}

	public function show_pagination() {
		_deprecated_function( 'show_pagination', '7.1.2', 'display_pagination' );
		return $this->display_pagination();
	}

	public function pagination() {
		_deprecated_function( 'pagination', '7.1.2', 'pagination_template' );
		return $this->pagination_template();
	}

	public function get_listing_types() {
		_deprecated_function( 'get_listing_types', '7.1.2', 'allowed_directory_types' );
		return [];
	}

	public function get_current_listing_type() {
		_deprecated_function( 'get_current_listing_type', '7.1.2', 'current_directory_type_id' );
		return $current = $this->current_directory_type_id();
	}

	public function get_the_location() {
		_deprecated_function( 'get_the_location', '7.1.2', 'get_location_html' );
		return $this->get_location_html();
	}

	public function have_posts() {
		_deprecated_function( 'have_posts', '7.1.2' );
		return $this->query->have_posts();
	}

	public function card_view_data() {
		_deprecated_function( 'card_view_data', '7.1.2' );
		return [];
	}

}