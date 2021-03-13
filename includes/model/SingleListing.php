<?php
/**
 * @author wpWax
 */

namespace Directorist;

use \ATBDP_Permalink;

if ( ! defined( 'ABSPATH' ) ) exit;

class Directorist_Single_Listing {

	protected static $instance = null;

	// Basic
	public $id;
	public $post;
	public $author_id;

	// Type
	public $type;
	public $header_data;
	public $content_data;

	// Meta
	public $tagline;
	public $fm_plan;
	public $price_range;
	public $atbd_listing_pricing;
	public $price;


	private function __construct($id = '') {
		if (!$id) {
			$id = get_the_ID();
		}
		$this->id = (int) $id;

		$this->prepare_data();
	}

	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	public function prepare_data() {
		$id = $this->id;

		$this->author_id     = get_post_field( 'post_author', $id );
		$this->post   		 = get_post( $id );
		$directory_type 	 = get_post_meta( $id, '_directory_type', true);
		$term 				 = get_term_by( is_numeric($directory_type) ? 'id' : 'slug', $directory_type, 'atbdp_listing_types' );
		$this->type          = (int) $term->term_id;
		$this->header_data   = get_term_meta( $this->type, 'single_listing_header', true );
		$this->content_data  = $this->build_content_data();
		
		$this->fm_plan               = get_post_meta( $id, '_fm_plans', true );
		$this->price_range           = get_post_meta( $id, '_price_range', true );
		$this->atbd_listing_pricing  = get_post_meta( $id, '_atbd_listing_pricing', true );
		$this->price                 = get_post_meta( $id, '_price', true );
	}

	public function build_content_data() {
		$content_data = array();
		$data  = get_term_meta( $this->type, 'single_listings_contents', true );
		$submission_form_fields = get_term_meta( $this->type, 'submission_form_fields', true );


		if( !empty( $data['fields'] ) ) {
			foreach ( $data['fields'] as $key => $value) {
				$data['fields'][$key]['field_key'] = !empty( $submission_form_fields['fields'][$key]['field_key'] ) ? $submission_form_fields['fields'][$key]['field_key'] : '';
				if( !empty( $submission_form_fields['fields'][$key]['label'] ) )
				$data['fields'][$key]['label'] = $submission_form_fields['fields'][$key]['label'];
				$data['fields'][$key]['original_data'] = !empty( $submission_form_fields['fields'][$key] ) ? $submission_form_fields['fields'][$key] : [];
			}
		}
		if( !empty( $data['groups'] ) ) {
			foreach ( $data['groups'] as $group ) {
				$section           = $group;
				$section['fields'] = array();
				foreach ( $group['fields'] as $field ) {
					if ( ! isset( $data['fields'][ $field ] ) ) { continue; }
					$section['fields'][ $field ] = $data['fields'][ $field ];
				}
				$content_data[] = $section;
			}
		}

		return $content_data;
	}

	public function section_template( $section_data ) {
		$args = array(
			'listing'      => $this,
			'section_data' => $section_data,
			'icon'         => !empty( $section_data['icon'] ) ? $section_data['icon'] : '',
			'label'        => !empty( $section_data['label'] ) ? $section_data['label'] : '',
			'id'           => !empty( $section_data['custom_block_id'] ) ? $section_data['custom_block_id'] : '',
			'class'        => !empty( $section_data['custom_block_classes'] ) ? $section_data['custom_block_classes'] : '',
		);
		if ( $section_data['type'] == 'general_group' ) {
			if ( $this->section_has_contents( $section_data ) ) {
				Helper::get_template( 'single/section-general', $args );
			}
		}
		else {
			$template = 'single/section-'. $section_data['widget_name'];
			$template = apply_filters( 'directorist_single_section_template', $template, $section_data );
			Helper::get_template( $template, $args );
		}
	}

	public function section_has_contents( $section_data ) {
		$has_contents = false;

		foreach ( $section_data['fields'] as $field ) {
			$value = Helper::get_widget_value( $this->id, $field );

			if ( $value ) {
				$has_contents = true;
				break;
			}

			if( 'tag' === $field['widget_name'] ) {
				$tags = get_the_terms( $this->id, ATBDP_TAGS );
				if( $tags ) {
					$has_contents = true;
					break;
				}
			}

			if( 'map' === $field['widget_name'] ) {
				$address = get_post_meta( $this->id, '_address', true );
				if( $address ) {
					$has_contents = true;
					break;
				}
			}
		}

		return $has_contents;
	}

	public function field_template( $data ) {
		$value = Helper::get_widget_value( $this->id, $data );

		if( 'tag' === $data['widget_name'] ) {
			$tags = get_the_terms( $this->id, ATBDP_TAGS );
			if( $tags ) {
				$value = true;
			}
		}

		if( 'map' === $data['widget_name'] ) {
			$manual_lat = get_post_meta( $this->id, '_manual_lat', true );
			$manual_lng = get_post_meta( $this->id, '_manual_lng', true );
			$hide_map 	= get_post_meta( $this->id, '_hide_map', true );
			if( ( $manual_lat && $manual_lng ) && ! $hide_map ) {
				$value = true;
			}
		}

		$load_template = true;
		$group = !empty( $data['original_data']['widget_group'] ) ? $data['original_data']['widget_group'] : '';
		
		if( ( ( $group === 'custom' ) || ( $group === 'preset' ) ) && !$value ) {
			$load_template = false;
		}

		$data['value'] = $value;
		$data['listing_id'] = $this->id;
		$args = array(
			'listing' => $this,
			'data'    => $data,
			'value'   => $value,
			'icon'    => !empty( $data['icon'] ) ? $data['icon'] : '',
		);

		if ( $this->is_custom_field( $data ) ) {
			$widget_name = ! empty( $data['original_data']['widget_name'] ) ? $data['original_data']['widget_name'] : $data['widget_name'];
			$template = 'single/custom-fields/' . $widget_name;
		}
		else {
			$template = 'single/fields/' . $data['widget_name'];
		}
		
		$template = apply_filters( 'directorist_single_item_template', $template, $data );

		if( $load_template ) {
			Helper::get_template( $template, $args );
		}
	}

	public function is_custom_field( $data ) {
		$fields = [ 'checkbox', 'color_picker', 'date', 'file', 'number', 'radio', 'select', 'text', 'textarea', 'time', 'url' ];
		$widget_name = ! empty( $data['original_data']['widget_name'] ) ? $data['original_data']['widget_name'] : $data['widget_name'];

		$is_custom_field = in_array( $widget_name, $fields ) ? true : false;

		return $is_custom_field;
	}

	public function get_custom_field_value( $type, $data ) {
		$result = '';
		$value = is_array( $data['value'] ) ? join( ",",$data['value'] ) : $data['value'];
		
		switch ( $type ) {
			case 'radio':
			case 'select':
			foreach( $data['original_data']['options'] as $option ) {
				$key = $option['option_value'];
				if( $key === $value ) {
					$result = $option['option_label'];
					break;
				}
			}
			break;

			case 'checkbox':
			$option_value = [];
			foreach( $data['original_data']['options'] as $option ) {
				$key = $option['option_value'];
				if( in_array( $key, explode( ',', $value ) ) ) {
					$space = str_repeat(' ', 1);
					$option_value[] = $space . $option['option_label'];
				}
			}
			$output = join( ',', $option_value );
			$result = $output ? $output : $value;
			break;
		}

		return $result;
	}

	public function get_socials() {
		return get_post_meta( $this->id, '_social', true);
	}

	public function section_id( $id ) {
		if ( $id ) {
			printf( 'id="%s"', $id );
		}
		else {
			return;
		}
	}

	public function get_address( $data ) {
		$value = $data['value'];
		if (!empty($data['address_link_with_map'])) {
			$value = '<a target="google_map" href="https://www.google.de/maps/search/?' . esc_html($value) . '">' . esc_html($value) . '</a>';
		}
		return $value;
	}

	public function get_cat_list() {
		// @cache @kowsar
		$cat_list = get_the_term_list( $this->id, ATBDP_CATEGORY, '', ', ');
		return $cat_list;
	}

	public function get_location_list() {
		// @cache @kowsar
		$loc_list = get_the_term_list( $this->id, ATBDP_LOCATION, '', ', ');
		return $loc_list;
	}

	public function get_tags() {
		// @cache @kowsar
		$tags = get_the_terms( $this->id, ATBDP_TAGS );
		return $tags;
	}



	public function social_share_data() {
		$title = get_the_title();
		$link  = get_the_permalink();

		$result = array(
			'facebook' => array(
				'title' => __('Facebook', 'directorist'),
				'icon'  => atbdp_icon_type() . '-facebook',
				'link'  => "https://www.facebook.com/share.php?u={$link}&title={$title}",
			),
			'twitter' => array(
				'title' => __('Twitter', 'directorist'),
				'icon'  => atbdp_icon_type() . '-twitter',
				'link'  => 'https://twitter.com/intent/tweet?text=' . $title . '&amp;url=' . $link,
			),
			'linkedin' => array(
				'title' => __('LinkedIn', 'directorist'),
				'icon'  => atbdp_icon_type() . '-linkedin',
				'link'  => "http://www.linkedin.com/shareArticle?mini=true&url={$link}&title={$title}",
			),
		);

		return $result;
	}

	public function quick_actions_template() {
		$actions = ! empty( $this->header_data['listings_header']['quick_actions'] ) ? $this->header_data['listings_header']['quick_actions'] : '';
		
		$args = array(
			'listing'  => $this,
			'actions'  => $actions,
		);

		if( $actions ) {
			Helper::get_template('single/quick-actions', $args );
		}
	}

	public function quick_info_template() {
		$quick_info = ! empty( $this->header_data['listings_header']['quick_info'] ) ? $this->header_data['listings_header']['quick_info'] : '';
		
		$args = array(
			'listing' => $this,
			'info'    => $quick_info,
		);

		if( $quick_info ) {
			Helper::get_template('single/quick-info', $args );
		}
	}

	public function get_slider_data() {
		$listing_id    = $this->id;
		$listing_title = get_the_title( $listing_id );

		$type          = get_post_meta( get_the_ID(), '_directory_type', true );
		$default_image = Helper::default_preview_image_src( $type );

		// Get the preview images
		$preview_img_id   = get_post_meta( $listing_id, '_listing_prv_img', true);
		$preview_img_link = ! empty($preview_img_id) ? atbdp_get_image_source($preview_img_id, 'large') : '';
		$preview_img_alt  = get_post_meta($preview_img_id, '_wp_attachment_image_alt', true);
		$preview_img_alt  = ( ! empty( $preview_img_alt )  ) ? $preview_img_alt : get_the_title( $preview_img_id );

		// Get the gallery images
		$listing_img  = get_post_meta( $listing_id, '_listing_img', true );
		$listing_imgs = ( ! empty( $listing_img ) ) ? $listing_img : array();
		$image_links  = array(); // define a link placeholder variable

		foreach ( $listing_imgs as $img_id ) {
			$alt = get_post_meta( $img_id, '_wp_attachment_image_alt', true );
			$alt = ( ! empty( $alt )  ) ? $alt : get_the_title( $img_id );

			$image_links[] = [
				'alt' => ( ! empty( $alt )  ) ? $alt : $listing_title,
				'src' => atbdp_get_image_source( $img_id, 'large' ),
			];
		}

		// Get the options
		$background_type  = get_directorist_option('single_slider_background_type', 'custom-color');
		
		// Set the options
		$data = array(
			'images'             => [],
			'alt'                => $listing_title,
			'background-size'    => get_directorist_option('single_slider_image_size', 'cover'),
			'blur-background'    => ( 'blur' === $background_type ) ? '1' : '0',
			'width'              => get_directorist_option('gallery_crop_width', 670),
			'height'             => get_directorist_option('gallery_crop_height', 750),
			'background-color'   => get_directorist_option('single_slider_background_color', 'gainsboro'),
			'thumbnail-bg-color' => '#fff',
			'show-thumbnails'    => get_directorist_option('dsiplay_thumbnail_img', true) ? '1' : '0',
			'gallery'            => true,
			'rtl'                => is_rtl() ? '1' : '0',
		);

		if ( ! empty( $image_links ) ) {
			$data['images'] = $image_links;
		}

		if ( ! empty( $preview_img_link ) ) {
			$preview_img = [
				'alt' => $preview_img_alt,
				'src' => $preview_img_link,
			];

			array_unshift( $data['images'], $preview_img );
		}
		
		if ( count( $data['images'] ) < 1 ) {
			$data['images'][] = [
				'alt' => $listing_title,
				'src' => $default_image,
			];
		}

		$padding_top         = $data['height'] / $data['width'] * 100;
		$data['padding-top'] = $padding_top;
		return $data;
	}

	public function slider_template() {
		$args = array(
			'listing'    => $this,
			'has_slider' => !empty( $this->header_data['listings_header']['thumbnail'] ) ? true : false,
			'data'       => $this->get_slider_data(),
		);

		Helper::get_template('single/slider', $args );
	}

	public function has_badge( $data ) {
		if ( $data['new_badge'] || $data['featured_badge'] || $data['popular_badge'] ) {
			if ( Helper::badge_exists( $this->id ) ) {
				return true;
			}
		}

		return false;
	}

	public function display_new_badge( $data ) {
		return $data['new_badge'] && Helper::is_new( $this->id );
	}

	public function display_featured_badge( $data ) {
		$featured_badge = !empty( $data['featured_badge'] ) ? $data['featured_badge'] : '';
		return $featured_badge && Helper::is_featured( $this->id );
	}

	public function display_popular_badge( $data ) {
		return $data['popular_badge'] && Helper::is_popular( $this->id );
	}

	public function has_price_range() {
		$id = $this->id;
		$plan_average_price = is_fee_manager_active() ? is_plan_allowed_average_price_range($this->fm_plan) : true;

		if (!empty($this->price_range) && ('range' === $this->atbd_listing_pricing) && $plan_average_price) {
			return true;
		}
		else {
			return false;
		}
	}

	public function price_range_html() {
		$id = $this->id;
		$currency = get_directorist_option('g_currency', 'USD');
		$c_symbol = atbdp_currency_symbol($currency);
		$active   = '<span class="atbd_active">' . $c_symbol . '</span>';
		$inactive = '<span>' . $c_symbol . '</span>';
		$output = '';

		switch ($this->price_range) {
			case 'skimming':
			$output = $active.$active.$active.$active;
			break;
			case 'moderate':
			$output = $active.$active.$active.$inactive;
			break;
			case 'economy':
			$output = $active.$active.$inactive.$inactive;
			break;
			case 'bellow_economy':
			$output = $active.$inactive.$inactive.$inactive;
			break;
		}

		$result = sprintf('<div class="atbd_meta atbd_listing_average_pricing atbd_tooltip" aria-label="%s">%s</div>', ucfirst( $this->price_range ), $output);

		return $result;
	}

	public function has_price() {
		$id         = $this->id;
		$plan_price = is_fee_manager_active() ? is_plan_allowed_price( $this->fm_plan ) : true;
		
		return ( $this->price && $plan_price ) ? true : false;
	}

	public function author_has_socials() {
		if ( $this->author_info( 'facebook' ) || $this->author_info( 'twitter' ) || $this->author_info( 'linkedIn' ) || $this->author_info( 'youtube' ) ) {
			return true;
		}
		else {
			return false;
		}
	}

	public function author_display_email() {
		$email_display_type  = get_directorist_option('display_author_email', 'public');
		$email = $this->author_info( 'name' );

		if ( !$email ) {
			return false;
		}

		if ( $email_display_type == 'public' || ( $email_display_type == 'logged_in' && atbdp_logged_in_user() ) ) {
			return true;
		}

		return false;
	}

	public function author_info( $arg ) {
		$author_id = $this->author_id;
		$result = '';

		switch ( $arg ) {
			case 'member_since':
			$user_registered = get_the_author_meta('user_registered', $author_id);
			$result = human_time_diff(strtotime($user_registered), current_time('timestamp'));
			break;

			case 'name':
			$result = get_the_author_meta('display_name', $author_id);
			break;

			case 'address':
			$result = get_user_meta($author_id, 'address', true);
			break;

			case 'phone':
			$result = get_user_meta($author_id, 'atbdp_phone', true);
			break;

			case 'email':
			$result = get_the_author_meta('user_email', $author_id);
			break;

			case 'website':
			$result = get_the_author_meta('user_url', $author_id);
			break;

			case 'facebook':
			$result = get_user_meta($author_id, 'atbdp_facebook', true);
			break;

			case 'twitter':
			$result = get_user_meta($author_id, 'atbdp_twitter', true);
			break;

			case 'linkedin':
			$result = get_user_meta($author_id, 'atbdp_linkedin', true);
			break;

			case 'youtube':
			$result = get_user_meta($author_id, 'atbdp_youtube', true);
			break;
		}

		return $result;
	}

	public function price_html() {
		$id            = $this->id;
		$allow_decimal = get_directorist_option('allow_decimal', 1);
		$c_position    = get_directorist_option('g_currency_position');
		$currency      = get_directorist_option('g_currency', 'USD');
		$symbol        = atbdp_currency_symbol($currency);

		$before = $after = '';
		if ('after' == $c_position) {
			$after = $symbol;
		}
		else {
			$before = $symbol;
		}

		$price = $before . atbdp_format_amount($this->price, $allow_decimal) . $after;
		$price_html = apply_filters('atbdp_listing_price', sprintf("<span class='directorist-listing-price'>%s</span>", $price));
		return $price_html;
	}

	public function get_review_count() {
		return ATBDP()->review->db->count(array('post_id' => $this->id));
	}

	public function get_rating_count() {
		return ATBDP()->review->get_average( $this->id );
	}

	public function submit_link() {
		$id = get_the_ID();
		$payment   = isset($_GET['payment']) ? $_GET['payment'] : '';
		$redirect  = isset($_GET['redirect']) ? $_GET['redirect'] : '';
		$display_preview = get_directorist_option('preview_enable', 1);
		$link = '';

		if ($display_preview && $redirect) {
			$post_id = isset($_GET['post_id']) ? $_GET['post_id'] : $id;
			$edited = isset($_GET['edited']) ? $_GET['edited'] : '';
			$pid = isset($_GET['p']) ? $_GET['p'] : '';
			$pid = empty($pid) ? $post_id : $pid;
			if (empty($payment)) {
				$redirect_page = get_directorist_option('edit_listing_redirect', 'view_listing');
				if( 'view_listing' === $redirect_page){
					$link = add_query_arg(array('p' => $pid, 'post_id' => $pid, 'reviewed' => 'yes', 'edited' => $edited ? 'yes' : 'no'), $redirect);
				}
				else{
					$link = $redirect;
				}
			}
			else {
				$link = add_query_arg(array('atbdp_listing_id' => $pid, 'reviewed' => 'yes'), $_GET['redirect']);
			}
		}

		return $link;
	}

	public function has_redirect_link() {
		return isset( $_GET['redirect'] ) ;
	}

	public function edit_link() {
		$id = get_the_ID();
		$redirect  = isset($_GET['redirect']) ? $_GET['redirect'] : '';
		$edit_link = !empty($payment) ? add_query_arg('redirect', $redirect, ATBDP_Permalink::get_edit_listing_page_link($id)) : ATBDP_Permalink::get_edit_listing_page_link($id);
		return $edit_link;
	}

	public function edit_text() {
		return isset( $_GET['redirect'] ) ;
	}

	public function current_user_is_author() {
		$id = get_the_ID();
		$author_id = get_post_field( 'post_author', $id );

		if ( atbdp_logged_in_user() && $author_id == get_current_user_id() ) {
			return true;
		}
		else {
			return false;
		}
	}

	public function display_back_link() {
		$id = get_the_ID();
		$type = get_post_meta( $id, '_directory_type', true );
		$header = get_term_meta( $type, 'single_listing_header', true );
		return !empty( $header['options']['general']['back']['label'] ) ? true : false;
	}

	public function has_sidebar() {
		return is_active_sidebar('right-sidebar-listing');
	}

	public function content_col_class() {
		return is_active_sidebar('right-sidebar-listing') ? Helper::directorist_column(8) : Helper::directorist_column(12);
	}

	public function notice_template() {
		$args = array(
			'listing'     => $this,
			'notice_text' => $this->notice_text(),		
		);

		Helper::get_template('single/notice', $args );
	}

	public function notice_text() {
		$notice_text = '';

		if( isset( $_GET['notice'] ) ) {
			$new_listing_status  = get_term_meta( $this->type, 'new_listing_status', true );
			$edit_listing_status = get_term_meta( $this->type, 'edit_listing_status', true );
			$edited = ( isset( $_GET['edited'] ) ) ? $_GET['edited'] : 'no';

			$pending_msg = get_directorist_option('pending_confirmation_msg', __( 'Thank you for your submission. Your listing is being reviewed and it may take up to 24 hours to complete the review.', 'directorist' ) );
			$publish_msg = get_directorist_option('publish_confirmation_msg', __( 'Congratulations! Your listing has been approved/published. Now it is publicly available.', 'directorist' ) );

			if ( $edited === 'no' ) {
				$notice_text = 'publish' === $new_listing_status ? $publish_msg : $pending_msg;
			}
			else {
				$notice_text = 'publish' === $edit_listing_status ? $publish_msg : $pending_msg;
			}
		}

		return $notice_text;
	}

	public function header_template() {
		$section_title = !empty($this->header_data['options']['general']['section_title']['label']) ? $this->header_data['options']['general']['section_title']['label'] : '';
		$section_icon = !empty($this->header_data['options']['general']['section_title']['icon']) ? $this->header_data['options']['general']['section_title']['icon'] : '';
		$display_title = !empty( $this->header_data['options']['content_settings']['listing_title']['enable_title'] ) ? $this->header_data['options']['content_settings']['listing_title']['enable_title'] : '';
		$display_tagline = !empty( $this->header_data['options']['content_settings']['listing_title']['enable_tagline'] ) ? $this->header_data['options']['content_settings']['listing_title']['enable_tagline'] : '';
		$display_content = !empty( $this->header_data['options']['content_settings']['listing_description']['enable'] ) ? $this->header_data['options']['content_settings']['listing_description']['enable'] : '';
		
		$args = array(
			'listing'           => $this,
			'section_title'     => $section_title,
			'section_icon'      => $section_icon,
			'display_title'     => $display_title,
			'display_tagline'   => $display_tagline,
			'display_content'   => $display_content,
		);
		
		return Helper::get_template('single/header', $args);
	}

	public function render_shortcode_single_listing() {
		
		if ( !is_singular( ATBDP_POST_TYPE ) ) {
			return;
		}

		$args = array(
			'listing' => $this,
		);

		return Helper::get_template_contents('single/single-listing', $args);
	}

	public function get_title() {
		return get_the_title( $this->id );
	}

	public function display_review() {
		return get_directorist_option( 'enable_review', 1 );
	}

	public function guest_review_enabled() {
		return get_directorist_option('guest_review', 0);
	}

	public function owner_review_enabled() {
		return get_directorist_option('enable_owner_review');
	}

	public function current_review() {
		// @cache @kowsar
		$review = ATBDP()->review->db->get_user_review_for_post(get_current_user_id(), $this->id);
		return !empty( $review ) ? $review : '';
	}

	public function reviewer_name() {
		return wp_get_current_user()->display_name;;
	}

	public function review_count() {
		return ATBDP()->review->db->count(array('post_id' => $this->id));
	}

	public function review_count_text() {
		$review_count_text = _nx('Review', 'Reviews', $this->review_count(), 'Number of reviews', 'directorist');
		return $review_count_text;
	}

	public function review_approve_immediately() {
		return get_directorist_option('approve_immediately', 1);
	}

	public function review_is_duplicate() {
		return tract_duplicate_review(wp_get_current_user()->display_name, $this->id );
	}

	public function get_tagline() {
		return get_post_meta( $this->id, '_tagline', true );	
	}

	public function contact_owner_email() {	
		$email = get_post_meta( $this->id, '_email', true );
		return $email;
	}

	public function guest_email_label() {	
		return get_directorist_option( 'guest_email', __( 'Your Email', 'directorist' ) );
	}

	public function guest_email_placeholder() {	
		return get_directorist_option( 'guest_email_placeholder', __( 'example@gmail.com', 'directorist' ) );
	}


	public function get_contents() {
		$post    = $this->post;
		$content = apply_filters('get_the_content', $post->post_content);
		$content = do_shortcode(wpautop($content));
		return $content;
	}

	public function get_custom_field_type_value($field_id, $field_type, $field_details)
	{
		switch ($field_type) {
			case 'color':
			$result = sprintf('<div class="atbd_field_type_color" style="background-color: %s;"></div>', $field_details);
			break;

			case 'date':
			$result = date(get_option('date_format'), strtotime($field_details));
			break;

			case 'time':
			$result = date('h:i A', strtotime($field_details));
			break;

			case 'url':
			$result = sprintf('<a href="%s" target="_blank">%s</a>', esc_url($field_details), esc_url($field_details));
			break;

			case 'file':
			$done = str_replace('|||', '', $field_details);
			$name_arr = explode('/', $done);
			$filename = end($name_arr);
			$result = sprintf('<a href="%s" target="_blank" download>%s</a>', esc_url($done), $filename);
			break;

			case 'checkbox':
			$choices = get_post_meta($field_id, 'choices', true);
			$choices = explode("\n", $choices);
			$values = explode("\n", $field_details);
			$values = array_map('trim', $values);
			$output = array();
			foreach ($choices as $choice) {
				if (strpos($choice, ':') !== false) {
					$_choice = explode(':', $choice);
					$_choice = array_map('trim', $_choice);

					$_value = $_choice[0];
					$_label = $_choice[1];
				} else {
					$_value = trim($choice);
					$_label = $_value;
				}
				$_checked = '';
				if (in_array($_value, $values)) {
					$space = str_repeat(' ', 1);
					$output[] = "{$space}$_value";
				}
			}
			$result = join(',', $output);
			break;

			default:
				$content = apply_filters('get_the_content', $field_details);
				$result = do_shortcode( $content );
				break;
		}

		return $result;
	}

	public function get_custom_field_data()
	{
		$result = array();

		$id = $this->id;

		$args = array(
			'post_type' => ATBDP_CUSTOM_FIELD_POST_TYPE,
			'posts_per_page' => -1,
			'post_status' => 'publish',
		);

		$custom_fields = \ATBDP_Cache_Helper::get_the_transient([
			'group'      => 'atbdp_custom_field_query',
			'name'       => 'atbdp_all_custom_fields',
			'query_args' => $args,
			'cache'      => apply_filters('atbdp_cache_atbdp_all_custom_fields', 1),
			'value'      => function ($data) {
				return  new \WP_Query($data['query_args']);
			}
		]);

		$cats = get_the_terms($id, ATBDP_CATEGORY);
		$category_ids = array();
		if (!empty($cats)) {
			foreach ($cats as $single_val) {
				$category_ids[] = $single_val->term_id;
			}
		}

		$field_ids = array();

		foreach ($custom_fields->posts as $custom_fields_post) {
			$custom_field_id = $custom_fields_post->ID;
			$fields = get_post_meta($custom_field_id, 'associate', true);
			if ('form' != $fields) {
				$fields_id_with_cat = get_post_meta($custom_field_id, 'category_pass', true);
				if (in_array($fields_id_with_cat, $category_ids)) {
					$has_field_details = get_post_meta($id, $custom_fields_post->ID, true);
					if (!empty($has_field_details)) {
						$field_ids[] = $custom_field_id;
					}
				}
			} else {
				$has_field_details = get_post_meta($id, $custom_fields_post->ID, true);
				if (!empty($has_field_details)) {
					$field_ids[] = $custom_field_id;
				}
			}
		}

		foreach ($field_ids as $field_id) {
			$field_details = get_post_meta($id, $field_id, true);
			$field_type    = get_post_meta($field_id, 'type', true);

			if (!empty($field_details)) {
				$result[] = array(
					'title' => get_the_title($field_id),
					'value' => $this->get_custom_field_type_value($field_id, $field_type, $field_details)
				);
			}
		}

		return $result;
	}

	public function load_map_resources() {
		$id      = $this->id;

		$manual_lat  = get_post_meta($id, '_manual_lat', true);
		$manual_lng  = get_post_meta($id, '_manual_lng', true);

		$address = get_post_meta($id, '_address', true);
		$ad = !empty($address) ? esc_html($address) : '';

		$display_map_info               = apply_filters('atbdp_listing_map_info_window', get_directorist_option('display_map_info', 1));
		$display_image_map              = get_directorist_option('display_image_map', 1);
		$display_title_map              = get_directorist_option('display_title_map', 1);
		$display_address_map            = get_directorist_option('display_address_map', 1);
		$display_direction_map          = get_directorist_option('display_direction_map', 1);

		$listing_prv_img = get_post_meta($id, '_listing_prv_img', true);
		$default_image = get_directorist_option('default_preview_image', ATBDP_PUBLIC_ASSETS . 'images/grid.jpg');
		$listing_prv_imgurl = !empty($listing_prv_img) ? atbdp_get_image_source($listing_prv_img, 'small') : '';
		$listing_prv_imgurl = atbdp_image_cropping($listing_prv_img, 150, 150, true, 100)['url'];
		$img_url = !empty($listing_prv_imgurl) ? $listing_prv_imgurl : $default_image;
		$image = "<img src=" . $img_url . ">";
		if (empty($display_image_map)) {
			$image = '';
		}

		$t = get_the_title();
		$t = !empty($t) ? $t : __('No Title', 'directorist');
		if (empty($display_title_map)) {
			$t = '';
		}

		$info_content = "";
		if (!empty($display_image_map) || !empty($display_title_map)) {
			$info_content .= "<div class='map-info-wrapper'><div class='map-info-img'>$image</div><div class='map-info-details'><div class='atbdp-listings-title-block'><h3>$t</h3></div>";
		}
		if (!empty($display_address_map) && !empty($ad)) {
			$info_content .= apply_filters("atbdp_address_in_map_info_window", "<address>{$ad}</address>");
		}
		if (!empty($display_direction_map)) {
			$info_content .= "<div class='map_get_dir'><a href='http://www.google.com/maps?daddr={$manual_lat},{$manual_lng}' target='_blank'> " . __('Get Direction', 'directorist') . "</a></div><span class='iw-close-btn'><i class='la la-times'></i></span></div></div>";
		}

		$cats = get_the_terms(get_the_ID(), ATBDP_CATEGORY);
		if (!empty($cats)) {
			$cat_icon = get_cat_icon($cats[0]->term_id);
		}
		$cat_icon = !empty($cat_icon) ? $cat_icon : 'fa-map-marker';
		$icon_type = substr($cat_icon, 0, 2);
		$fa_or_la = ('la' == $icon_type) ? "la " : "fa ";
		$cat_icon = ('none' == $cat_icon) ? 'fa fa-map-marker' : $fa_or_la . $cat_icon;

		$args = array(
			'listing'               => $this,
			'default_latitude'      => get_directorist_option('default_latitude', '40.7127753'),
			'default_longitude'     => get_directorist_option('default_longitude', '-74.0059728'),
			'manual_lat'            => $manual_lat,
			'manual_lng'            => $manual_lng,
			'listing_location_text' => apply_filters('atbdp_single_listing_map_section_text', get_directorist_option('listing_location_text', __('Location', 'directorist'))),
			'select_listing_map'    => get_directorist_option('select_listing_map', 'google'),
			'info_content'          => $info_content,
			'display_map_info'      => $display_map_info,
			'map_zoom_level'        => get_directorist_option('map_zoom_level', 16),
			'cat_icon'              => $cat_icon,
		);

		if ('openstreet' === $args['select_listing_map']) {
			wp_localize_script('directorist-single-listing-openstreet-map-custom-script', 'localized_data', $args);
			wp_enqueue_script('directorist-single-listing-openstreet-map-custom-script');
		}

		if ('google' === $args['select_listing_map']) {
			wp_localize_script('directorist-single-listing-gmap-custom-script', 'localized_data', $args);
			wp_enqueue_script('directorist-single-listing-gmap-custom-script');
		}
	}

	public function get_reviewer_img()
	{
		$author_id = wp_get_current_user()->ID;
		$u_pro_pic = get_user_meta($author_id, 'pro_pic', true);
		$u_pro_pic = !empty($u_pro_pic) ? wp_get_attachment_image_src($u_pro_pic, 'thumbnail') : '';
		$u_pro_pic = is_array($u_pro_pic) ? $u_pro_pic[0] : $u_pro_pic;
		$custom_gravatar = "<img src='$u_pro_pic' alt='Author'>";
		$avatar_img = get_avatar($author_id, apply_filters('atbdp_avatar_size', 32));
		$user_img = !empty($u_pro_pic) ? $custom_gravatar : $avatar_img;
		return $user_img;
	}

	public function review_template() {
		$id           = $this->id;
		$review_count = ATBDP()->review->db->count(array('post_id' => $id));
		$author_id    = get_post_field('post_author', $id);

		$args = array(
			'listing'                  => $this,
			'author_id'                => get_post_field('post_author', $id),
			'enable_review'            => get_directorist_option('enable_review', 1),
			'enable_owner_review'      => get_directorist_option('enable_owner_review'),
			'allow_review'             => apply_filters('atbdp_single_listing_before_review_block', true),
			'review_count'             => $review_count,
			'review_count_text'        => _nx('Review', 'Reviews', $review_count, 'Number of reviews', 'directorist'),
			'guest_review'             => get_directorist_option('guest_review', 0),
			'cur_user_review'          => ATBDP()->review->db->get_user_review_for_post(get_current_user_id(), $id),
			'reviewer_name'            => wp_get_current_user()->display_name,
			'reviewer_img'             => $this->get_reviewer_img(),
			'guest_email_label'        => get_directorist_option('guest_email', __('Your Email', 'directorist')),
			'guest_email_placeholder'  => get_directorist_option('guest_email_placeholder', __('example@gmail.com', 'directorist')),
			'approve_immediately'      => get_directorist_option('approve_immediately', 1),
			'review_duplicate'         => tract_duplicate_review(wp_get_current_user()->display_name, $id),
			'login_link'               => apply_filters('atbdp_review_login_link', "<a href='" . ATBDP_Permalink::get_login_page_link() . "'> " . __('Login', 'directorist') . "</a>"),
			'register_link'            => apply_filters('atbdp_review_signup_link', "<a href='" . ATBDP_Permalink::get_registration_page_link() . "'> " . __('Sign Up', 'directorist') . "</a>"),
		);

		Helper::get_template('single/listing-review', $args);
	}

	public function get_related_listings() {
		$number       = get_directorist_type_option( $this->type, 'similar_listings_number_of_listings_to_show', 2 );
		$same_author  = get_directorist_type_option( $this->type, 'listing_from_same_author', false );
		$logic        = get_directorist_type_option( $this->type, 'similar_listings_logics', 'OR' );
		$relationship = ( $logic == 'AND' ) ? 'AND' : 'OR';

		$id = get_the_ID();
		$atbd_cats = get_the_terms($id, ATBDP_CATEGORY);
		$atbd_tags = get_the_terms($id, ATBDP_TAGS);
		$atbd_cats_ids = array();
		$atbd_tags_ids = array();

		if (!empty($atbd_cats)) {
			foreach ($atbd_cats as $atbd_cat) {
				$atbd_cats_ids[] = $atbd_cat->term_id;
			}
		}
		if (!empty($atbd_tags)) {
			foreach ($atbd_tags as $atbd_tag) {
				$atbd_tags_ids[] = $atbd_tag->term_id;
			}
		}
		$args = array(
			'post_type' => ATBDP_POST_TYPE,
			'tax_query' => array(
				'relation' => $relationship,
				array(
					'taxonomy' => ATBDP_CATEGORY,
					'field' => 'term_id',
					'terms' => $atbd_cats_ids,
				),
				array(
					'taxonomy' => ATBDP_TAGS,
					'field' => 'term_id',
					'terms' => $atbd_tags_ids,
				),
			),
			'posts_per_page' => (int)$number,
			'post__not_in' => array($id),
		);

		if( !empty( $same_author ) ){
			$args['author']  = get_post_field( 'post_author', $id );
		}

		$meta_queries = array();
		$meta_queries['expired'] = array(
				'key'     => '_listing_status',
				'value'   => 'expired',
				'compare' => '!=',
			);
		$meta_queries['directory_type'] = array(
				'key'     => '_directory_type',
				'value'   => $this->type,
				'compare' => '=',
			);

		$meta_queries = apply_filters('atbdp_related_listings_meta_queries', $meta_queries);
		$count_meta_queries = count($meta_queries);
		if ($count_meta_queries) {
			$args['meta_query'] = ($count_meta_queries > 1) ? array_merge(array('relation' => 'AND'), $meta_queries) : $meta_queries;
		}

		$args = apply_filters( 'directorist_related_listing_args', $args, $this );

		$related = new Directorist_Listings( [], 'related', $args, ['cache' => false] );

		return $related;
	}

	public function get_related_columns() {
		$columns = get_directorist_type_option( $this->type, 'similar_listings_number_of_columns', 3 );
		return 12/$columns;
	}

	public function load_related_listings_script() {
		$columns = get_directorist_type_option( $this->type, 'similar_listings_number_of_columns', 3 );

		$is_rtl = is_rtl() ? 'true' : '';

		$localized_data = array(
			'is_rtl' => $is_rtl,
			'rel_listing_column' => $columns,
		);

		wp_enqueue_script('directorist-releated-listings-slider');
		wp_localize_script('directorist-releated-listings-slider', 'data', $localized_data);
	}
}