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


	private function __construct( $listing_id = 0 ) {
		if ( $listing_id && is_int( $listing_id ) ) {
			$this->id = $listing_id;
		} else {
			$this->id = get_the_ID();
		}

		$this->prepare_data();
	}

	public static function instance( $listing_id = 0 ) {
		if ( null == self::$instance ) {
			self::$instance = new self( $listing_id );
		}
		return self::$instance;
	}

	public function get_directory_type_id() {
		$directory_type = directorist_get_object_terms( $this->id, ATBDP_TYPE, 'term_id' );
		if ( empty( $directory_type ) ) {
			return 0;
		}

		return $directory_type[0];
	}

	public function prepare_data() {
		$this->author_id            = get_post_field( 'post_author', $this->id );
		$this->post                 = get_post( $this->id );
		$this->type                 = $this->get_directory_type_id();
		$this->header_data          = get_term_meta( $this->type, 'single_listing_header', true );
		$this->content_data         = $this->build_content_data();
		$this->fm_plan              = get_post_meta( $this->id, '_fm_plans', true );
		$this->price_range          = get_post_meta( $this->id, '_price_range', true );
		$this->atbd_listing_pricing = get_post_meta( $this->id, '_atbd_listing_pricing', true );
		$this->price                = get_post_meta( $this->id, '_price', true );
	}

	public function build_content_data() {
		$content_data           = array();
		$single_fields          = get_term_meta( $this->type, 'single_listings_contents', true );
		$submission_form_fields = get_term_meta( $this->type, 'submission_form_fields', true );

		if( !empty( $single_fields['fields'] ) ) {

			foreach ( $single_fields['fields'] as $key => $value ) {

				if ( ! is_array( $value ) ) {
					continue;
				}

				// If 'other_widgets', then no need to set values from submission form fields
				if ( $value['widget_group'] === 'other_widgets' ) {
					continue;
				}

				// Make sure form key name is valid
				if ( !isset( $value['original_widget_key'] ) ) {
					unset( $single_fields['fields'][$key] );
					continue;
				}

				$form_key = $value['original_widget_key'];

				// Make sure the same form field exists
				if ( empty( $submission_form_fields['fields'][$form_key] ) ) {
					unset( $single_fields['fields'][$key] );
					continue;
				}

				$single_fields['fields'][$key]['field_key'] = '';
				$single_fields['fields'][$key]['options'] = [];

				unset( $single_fields['fields'][$key]['widget_key'] );
				unset( $single_fields['fields'][$key]['original_widget_key'] );

				// Added form_field, field_key, label, widget_group from submission form
				$form_data = $submission_form_fields['fields'][$form_key];

				$single_fields['fields'][$key]['form_data'] = $form_data;

				if ( !empty( $form_data['field_key'] ) ) {
					$single_fields['fields'][$key]['field_key'] = $form_data['field_key'];
				}

				if ( !empty( $form_data['options'] ) ) {
					$single_fields['fields'][$key]['options'] = $form_data['options'];
				}

				$single_fields['fields'][$key]['label'] = !empty( $form_data['label'] ) ? $form_data['label'] : '';

				if( !empty( $form_data['widget_group'] ) ) {
					$single_fields['fields'][$key]['widget_group'] = $form_data['widget_group'];
				}
			}
		}

		if( !empty( $single_fields['groups'] ) ) {
			foreach ( $single_fields['groups'] as $group ) {
				$section           = $group;
				$section['fields'] = array();
				foreach ( $group['fields'] as $field ) {
					if ( ! isset( $single_fields['fields'][ $field ] ) ) {
						continue;
					}
					$section['fields'][ $field ] = $single_fields['fields'][ $field ];
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

			if ( 'other_widgets' === $field['widget_group'] ) {
				$has_contents = true;
				break;
			}

			$value = $this->get_field_value( $field );

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

			if( 'image_upload' === $field['widget_name'] ) {
				if( $this->get_contents() ) {
					$has_contents = true;
					break;
				}
			}

			if( 'description' === $field['widget_name'] ) {
				if( $this->get_contents() ) {
					$has_contents = true;
					break;
				}
			}

			if( 'map' === $field['widget_name'] ) {
				$address = get_post_meta( $this->id, '_address', true );
				$manual_lat = get_post_meta( $this->id, '_manual_lat', true );
				$manual_lng = get_post_meta( $this->id, '_manual_lng', true );

				if( $address || ( $manual_lat && $manual_lng ) ) {
					$has_contents = true;
					break;
				}
			}
		}

		return apply_filters( 'directorist_single_section_has_contents', $has_contents, $section_data );
	}

	public function has_whatsapp( $data ) {
		if ( !empty( $data['form_data']['whatsapp'] ) ) {
			return true;
		}
		else {
			return false;
		}
	}

	public function get_field_value( $data = [] ) {
		$post_id = $this->id;

		$value = '';

		if ( ! is_array( $data ) ) {
			return '';
		}

		if ( isset( $data['widget_name'] ) && $data['widget_name'] == 'custom_content' ) {
			return $data['content'];
		}

		if ( !empty( $data['field_key'] ) ) {
			$value = get_post_meta( $post_id, '_'.$data['field_key'], true );

			if ( empty( $value ) ) {
				$value = get_post_meta( $post_id, $data['field_key'], true ); //@kowsar @todo - remove double getmeta later
			}
		}

		return apply_filters( 'directorist_single_listing_widget_value', $value, $data );
	}

	public function field_template( $data ) {
		$value = '';

		if( 'tag' === $data['widget_name'] ) {
			$tags = get_the_terms( $this->id, ATBDP_TAGS );
			if( $tags ) {
				$value = true;
			}
		} elseif( 'map' === $data['widget_name'] ) {
			$manual_lat = get_post_meta( $this->id, '_manual_lat', true );
			$manual_lng = get_post_meta( $this->id, '_manual_lng', true );
			$hide_map 	= get_post_meta( $this->id, '_hide_map', true );
			if( ( $manual_lat && $manual_lng ) && ! $hide_map ) {
				$value = true;
			}
		} elseif( 'image_upload' === $data['widget_name'] ) {
			$listing_img 	=  get_post_meta( $this->id, '_listing_img', true );
			$preview_img   	= get_post_meta( $this->id, '_listing_prv_img', true);
			if( $listing_img || $preview_img ) {
				$value = true;
			}
		} elseif( 'description' === $data['widget_name'] ) {
			if( $this->get_contents() ) {
				$value = true;
			}
		}
		else {
			$value = $this->get_field_value( $data );
		}

		$load_template = true;

		$group = !empty( $data['widget_group'] ) ? $data['widget_group'] : '';

		if( ( ( $group === 'custom' ) || ( $group === 'preset' ) ) && !$value ) {
			$load_template = false;
		}

		$data['value']      = $value;
		$data['listing_id'] = $this->id;

		$args = array(
			'listing'               => $this,
			'data'                  => $data,
			'value'                 => $value,
			'icon'                  => ! empty( $data['icon'] ) ? $data['icon'] : '',
			'display_address_map'   => get_directorist_option( 'display_address_map', 1 ),
			'display_direction_map' => get_directorist_option( 'display_direction_map', 1 ),
			'address'               => get_post_meta( $this->id, '_address', true ),
			'manual_lat'			=> ! empty( $manual_lat ) ? $manual_lat : '',
			'manual_lng'			=> ! empty( $manual_lng ) ? $manual_lng : '',
		);

		if ( $this->is_custom_field( $data ) ) {
			$template = 'single/custom-fields/' . $data['widget_name'];
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
		$is_custom_field = in_array( $data['widget_name'], $fields ) ? true : false;
		return $is_custom_field;
	}

	public function get_custom_field_value( $type, $data ) {
		$result = '';
		$value = is_array( $data['value'] ) ? join( ",", $data['value'] ) : $data['value'];

		switch ( $type ) {
			case 'radio':
			case 'select':
			if(!empty($data['options'])) {
				foreach( $data['options'] as $option ) {
					$key = $option['option_value'];
					if( $key === $value ) {
						$result = $option['option_label'];
						break;
					}
				}
			}
			break;

			case 'checkbox':
			$option_value = [];
			foreach( $data['options'] as $option ) {
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
			printf( 'id="%s"', esc_attr( $id ) );
		}
		else {
			return;
		}
	}

	public function get_address( $data ) {
		$value = $data['value'];
		if (!empty($data['address_link_with_map'])) {
			$value = '<a target="google_map" href="https://www.google.com/maps/search/' . esc_html($value) . '">' . esc_html($value) . '</a>';
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

	public function single_page_enabled() {
		return get_directorist_type_option( $this->type, 'enable_single_listing_page', false );
	}

	/**
	 * Single Listing content when custom single listing page is enabled.
	 *
	 * @return string Single Listing content html.
	 */
	public function single_page_content() {
		$page_id = (int) get_directorist_type_option( $this->type, 'single_listing_page' );

		// Bail if custom single listing page is disabled
		if ( ! $page_id ) {
			return '';
		}

		// Bail if selected custom single listing page is not really a page
		$page = get_post( $page_id );
		if ( $page->post_type !== 'page' ) {
			return '';
		}

		/**
		 * Usually this hook is used to inject page builder content.
		 *
		 * @hook directorist_add_custom_single_listing_page_content_from_elementor
		 *
		 * @param string Page content.
		 * @param WP_Post $page
		 *
		 * @since 7.4.0
		 */
		$content = apply_filters( 'directorist_custom_single_listing_pre_page_content', '', $page );

		// Return page builder or other injected content if exists
		if ( ! empty( $content ) ) {
			return $content;
		}

		$content = get_post_field( 'post_content', $page_id ); // Raw content
		$content = $this->filter_single_listing_content( $content ); // Actual content after running several filters

		return $content;
	}

	private function filter_single_listing_content( $content ) {
		global $wp_embed;
		$content = $wp_embed->run_shortcode( $content );
		$content = $wp_embed->autoembed( $content );
		// do_blocks available from WP 5.0
		$content = function_exists( 'do_blocks' ) ? do_blocks( $content ) : $content;
		$content = wptexturize( $content );
		$content = convert_smilies( $content );
		$content = shortcode_unautop( $content );
		$content = wp_filter_content_tags( $content );
		$content = do_shortcode( $content );
		$content = str_replace( ']]>', ']]&gt;', $content );
		return $content;
	}

	public function social_share_data() {
		$title = get_the_title();
		$link  = get_the_permalink();

		$result = array(
			'facebook' => array(
				'title' => __('Facebook', 'directorist'),
				'icon'  => 'lab la-facebook',
				'link'  => "https://www.facebook.com/share.php?u={$link}&title={$title}",
			),
			'twitter' => array(
				'title' => __('Twitter', 'directorist'),
				'icon'  => 'lab la-twitter',
				'link'  => 'https://twitter.com/intent/tweet?text=' . $title . '&amp;url=' . $link,
			),
			'linkedin' => array(
				'title' => __('LinkedIn', 'directorist'),
				'icon'  => 'lab la-linkedin',
				'link'  => "http://www.linkedin.com/shareArticle?mini=true&url={$link}&title={$title}",
			),
		);

		return apply_filters( 'directorist_single_listing_social_sharing_items', $result );
	}

	public function quick_actions_template() {

		$actions = $this->listing_header( '', 'quick-widgets-placeholder', 'quick-action-placeholder' );
		$args = array(
			'listing'  => $this,
			'actions'  => $actions,
		);

		if( $actions ) {
			Helper::get_template('single/quick-actions', $args );
		}
	}

	public function quick_info_template() {

		$quick_info = $this->listing_header( '', 'more-widgets-placeholder' );

		$args = array(
			'listing' => $this,
			'info'    => $quick_info,
		);

		if( $quick_info ) {
			Helper::get_template('single/quick-info', $args );
		}
	}

	public function get_slider_data( $data = null ) {

		$show_slider = get_directorist_option( 'dsiplay_slider_single_page', true );

		if( ! $show_slider ) {
			return;
		}

		$listing_id    = $this->id;
		$listing_title = get_the_title( $listing_id );

		$type          = (int) get_post_meta( $this->id, '_directory_type', true );
		$default_image = Helper::default_preview_image_src( $type );

		$image_size = apply_filters( 'directorist_single_listing_slider_image_size', 'large' );

		// Get the preview images
		$preview_img_id   = get_post_meta( $listing_id, '_listing_prv_img', true);
		$preview_img_link = ! empty($preview_img_id) ? atbdp_get_image_source( $preview_img_id, $image_size ) : '';
		$preview_img_alt  = get_post_meta($preview_img_id, '_wp_attachment_image_alt', true);
		$preview_img_alt  = ( ! empty( $preview_img_alt )  ) ? $preview_img_alt : get_the_title( $preview_img_id );

		// Get the gallery images
		$listing_img  = get_post_meta( $listing_id, '_listing_img', true );
		$listing_imgs = ! empty( $listing_img ) ? ( ! is_array( $listing_img ) ? array( $listing_img ) : $listing_img ) : array();
		$image_links  = array(); // define a link placeholder variable

		foreach ( $listing_imgs as $img_id ) {
			$alt = get_post_meta( $img_id, '_wp_attachment_image_alt', true );
			$alt = ( ! empty( $alt )  ) ? $alt : get_the_title( $img_id );

			$image_links[] = [
				'alt' => ( ! empty( $alt )  ) ? $alt : $listing_title,
				'src' => atbdp_get_image_source( $img_id, $image_size ),
			];
		}

		// Get the options
		$background_type = get_directorist_option( 'single_slider_background_type', 'custom-color' );
		$height          = (int) get_directorist_option( 'gallery_crop_height', 670 );
		$width           = (int) get_directorist_option( 'gallery_crop_width', 750 );

		// Set the options
		$data = array(
			'images'             => [],
			'alt'                => $listing_title,
			'background-size'    => get_directorist_option( 'single_slider_image_size', 'cover' ),
			'blur-background'    => ( 'blur' === $background_type ) ? '1' : '0',
			'width'              => empty( $width ) ? 740 : $width,
			'height'             => empty( $height ) ? 580 : $height,
			'background-color'   => get_directorist_option( 'single_slider_background_color', 'gainsboro' ),
			'thumbnail-bg-color' => '',
			'show-thumbnails'    => ! empty( $data['footer_thumbnail'] ) ? '1' : '0',
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

		$data['padding-top'] = ( $data['height'] / $data['width'] ) * 100;

		return $data;
	}

	public function slider_template() {

		$slider = $this->listing_header( 'slider', 'slider-placeholder' );

		if ( ! $slider ) {
			return;
		}

		$this->slider_field_template( $slider );
	}

	public function slider_field_template( $slider = null ) {

		$args = array(
			'listing'    => $this,
			'has_slider' => true,
			'data'       => $this->get_slider_data( $slider ),
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
		$currency = directorist_get_currency();
		$c_symbol = atbdp_currency_symbol($currency);
		$active   = '<span class="directorist-price-active">' . $c_symbol . '</span>';
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

		$result = sprintf('<div class="directorist-listing-price-range directorist-tooltip" data-label="%s">%s</div>', ucfirst( $this->price_range ), $output);

		return $result;
	}

	public function contact_owner_form_disabled() {
		$author_id 			= get_post_field( 'post_author', $this->id );
		$hide_contact_form  = get_user_meta( $author_id, 'directorist_hide_contact_form', true );
		
		if ( ! empty( $hide_contact_form ) && 'yes' == $hide_contact_form ) {
			return true;
		}
		
		return false;
	}

	public function contact_owner_fields( $field_data = [] ) {
		$default_fields = array(
			'name' => array(
				'enable' => true,
				'placeholder' => __( 'Name', 'directorist' ),
			),
			'email' => array(
				'placeholder' => __( 'Email', 'directorist' ),
			),
			'message' => array(
				'placeholder' => __( 'Message...', 'directorist' ),
			),
		);
	
		$field_keys = ['contact_name' => 'name', 'contact_email' => 'email', 'contact_message' => 'message'];
	
		foreach ( $field_keys as $key => $field ) {
			if ( ! empty( $field_data[ $key ] ) ) {
				$default_fields[ $field ]['placeholder'] = $field_data[ $key ]['placeholder'] ?? $default_fields[ $field ]['placeholder'];
				if ( isset( $field_data[ $key ]['enable'] ) ) {
					$default_fields[ $field ]['enable'] = $field_data[ $key ]['enable'];
				}
			}
		}
	
		return $default_fields;
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

	public function author_display_email( $section_data = [] ) {
		$email_display_type = ! empty( $section_data['display_email'] ) ?? true;
		$email 				= $this->author_info( 'name' );

		if ( !$email ) {
			return false;
		}

		if ( $email_display_type == 'public' || ( $email_display_type == 'logged_in' && is_user_logged_in() ) ) {
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
		$c_position    = directorist_get_currency_position();
		$currency      = directorist_get_currency();
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

	public function get_review_section_data() {
		$data = array();

		foreach ( $this->content_data as $section_data ) {
			if ( isset( $section_data['widget_name'] ) && $section_data['widget_name'] == 'review' ) {
				$data = array(
					'section_data' => $section_data,
					'icon'         => !empty( $section_data['icon'] ) ? $section_data['icon'] : '',
					'label'        => !empty( $section_data['label'] ) ? $section_data['label'] : '',
					'id'           => !empty( $section_data['custom_block_id'] ) ? $section_data['custom_block_id'] : 'reviews',
					'class'        => !empty( $section_data['custom_block_classes'] ) ? $section_data['custom_block_classes'] : '',
				);
			}
		}

		return $data;
	}

	public function get_review_count() {
		return directorist_get_listing_review_count( $this->id );
	}

	public function get_rating_count() {
		return directorist_get_listing_rating( $this->id );
	}

	public function submit_link() {
		$payment         = isset( $_GET['payment'] ) ? sanitize_text_field( wp_unslash( $_GET['payment'] ) ) : '';
		$redirect        = isset( $_GET['redirect'] ) ? sanitize_url( wp_unslash( $_GET['redirect'] ) ) : '';
		$display_preview = (bool) get_directorist_option( 'preview_enable', 1 );
		$link            = '';

		if ( $display_preview && $redirect ) {
			$edited     = isset( $_GET['edited'] ) ? sanitize_text_field( wp_unslash( $_GET['edited'] ) ) : '';
			$listing_id = isset( $_GET['post_id'] ) ? sanitize_text_field( wp_unslash( $_GET['post_id'] ) ) : get_the_ID();
			$listing_id = isset( $_GET['p'] ) ? sanitize_text_field( wp_unslash( $_GET['p'] ) ) : $listing_id;

			if ( empty( $payment ) ) {
				$redirect_page = get_directorist_option('edit_listing_redirect', 'view_listing');

				if ( 'view_listing' === $redirect_page){
					$link = add_query_arg( array(
						'p'        => $listing_id,
						'post_id'  => $listing_id,
						'reviewed' => 'yes',
						'edited'   => $edited ? 'yes' : 'no'
					), $redirect );
				} else{
					$link = $redirect;
				}
			} else {
				$link = add_query_arg( array(
					'atbdp_listing_id' => $listing_id,
					'reviewed'         => 'yes'
				), $redirect );
			}
		}

		return $link;
	}

	public function has_redirect_link() {
		return isset( $_GET['redirect'] ) ;
	}

	public function edit_link() {
		$id = $this->id;
		$redirect  = isset($_GET['redirect']) ? sanitize_text_field( wp_unslash( $_GET['redirect'] ) ) : '';
		$edit_link = !empty($payment) ? add_query_arg('redirect', $redirect, ATBDP_Permalink::get_edit_listing_page_link($id)) : ATBDP_Permalink::get_edit_listing_page_link($id);
		return $edit_link;
	}

	public function edit_text() {
		return isset( $_GET['redirect'] ) ;
	}

	public function current_user_is_author() {
		$id = $this->id;
		$author_id = get_post_field( 'post_author', $id );

		if ( is_user_logged_in() && $author_id == get_current_user_id() ) {
			return true;
		}
		else {
			return false;
		}
	}

	public function display_back_link() {
		return $this->listing_header( 'back', 'quick-widgets-placeholder', 'quick-info-placeholder' );
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
			$edit_listing_status = ( 'publish' !== $new_listing_status ) ? $new_listing_status : directorist_get_listing_edit_status( $this->type );
			$edited = ( isset( $_GET['edited'] ) ) ? sanitize_text_field( wp_unslash( $_GET['edited'] ) ): 'no';

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

	public function listing_header( $key = '', $group = '', $subgroup = '' ) {

		foreach( $this->header_data as $data ) {

			if ( empty( $data['placeholderKey'] ) ) {
				continue;
			}

			if ( $data['placeholderKey'] !== $group ) {
				continue;
			}

			if ( $subgroup and ! empty( $data['placeholders'] ) ) {
				foreach ( $data['placeholders'] as $placeholder )  {
					if ( $placeholder['placeholderKey'] !== $subgroup ) {
						continue;
					}

					if ( ! $key ) {
						return $placeholder['selectedWidgets'];
					}

					foreach( $placeholder['selectedWidgets'] as $index => $widget ) {
						if ( $widget['widget_key'] === $key ) {
							return $widget;
						}
					}

				}
			}

			if ( empty( $data['selectedWidgets'] ) ) {
				return [];
			}

			if ( ! $key ) {
				return $data['selectedWidgets'];
			}

			foreach( $data['selectedWidgets'] as $index => $widget ) {
				if ( $widget['widget_key'] === $key ) {
					return $widget;
				}
			}

		}

	}

	public function header_template() {

		$display_title     = $this->listing_header( 'title', 'listing-title-placeholder' );
		$args = array(
			'listing'           => $this,
			'use_listing_title' => true,
			'section_title'     => '',
			'section_icon'      => '',
			'display_title'     => $display_title,
			'display_tagline'   => ! empty( $display_title['enable_tagline'] ) ? $display_title['enable_tagline'] : false,
			'display_content'   => false,
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
		return directorist_is_review_enabled();
	}

	public function guest_review_enabled() {
		return get_directorist_option('guest_review', 0);
	}

	public function owner_review_enabled() {
		return get_directorist_option('enable_owner_review', 1);
	}

	public function current_review() {
		// @cache @kowsar
		// $review = ATBDP()->review->db->get_user_review_for_post(get_current_user_id(), $this->id);
		// return !empty( $review ) ? $review : '';

		return '';
	}

	public function reviewer_name() {
		return wp_get_current_user()->display_name;;
	}

	public function review_count() {
		return directorist_get_listing_review_count( $this->id );
	}

	public function review_count_text() {
		$review_count_text = _nx('Review', 'Reviews', $this->review_count(), 'Number of reviews', 'directorist');
		return $review_count_text;
	}

	public function review_approve_immediately() {
		return get_directorist_option('approve_immediately', 1);
	}

	/**
	 * Unused method
	 *
	 * @return bool
	 */
	public function review_is_duplicate() {
		_deprecated_function( __METHOD__, '7.4.3' );
		return false;
	}

	public function get_tagline() {
		return get_post_meta( $this->id, '_tagline', true );
	}

	public function contact_owner_email() {
		$email = get_post_meta( $this->id, '_email', true );
		return $email;
	}

	public function guest_email_label() {
		return get_directorist_option( 'guest_email', __( 'Email Address', 'directorist' ) );
	}

	public function guest_email_placeholder() {
		return get_directorist_option( 'guest_email_placeholder', __( 'Enter email address', 'directorist' ) );
	}

	// TODO: When it's compatible with `the_content()` template tag then we won't have to use do_shortcode and wpautop functions.
	public function get_contents() {
		$content = $this->post->post_content;
		$content = wpautop( $content );
		$content = do_shortcode( $content );

		// TODO: Make it compatible with wp core `the_content` hook.
		return apply_filters( 'directorist_the_content', $content );
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
				$result = do_shortcode( $field_details );
				break;
		}

		return $result;
	}

	/**
	 * Unused method
	 *
	 * @return array
	 */
	public function get_custom_field_data() {
		_deprecated_function( __METHOD__, '7.4.3' );
		return array();
	}

	public function map_data() {
		$id      = $this->id;

		$manual_lat = get_post_meta( $id, '_manual_lat', true );
		$manual_lng = get_post_meta( $id, '_manual_lng', true );
		$phone      = get_post_meta( $id, '_phone', true );

		$address = get_post_meta($id, '_address', true);
		$ad = !empty($address) ? esc_html($address) : '';

		$display_map_info           = apply_filters( 'atbdp_listing_map_info_window', get_directorist_option( 'display_map_info', 1 ) );
		$display_image_map          = get_directorist_option( 'display_image_map', 1 );
		$display_title_map          = get_directorist_option( 'display_title_map', 1 );
		$display_address_map        = get_directorist_option( 'display_address_map', 1 );
		$display_direction_map      = get_directorist_option( 'display_direction_map', 1 );
		$display_user_avatar_map    = get_directorist_option( 'display_user_avatar_map', 1 );
		$display_review_map         = get_directorist_option( 'display_review_map', 1 );
		$display_price_map          = get_directorist_option( 'display_price_map', 1 );
		$display_favorite_badge_map = get_directorist_option( 'display_favorite_badge_map', 1 );

		$listing_prv_img = get_post_meta($id, '_listing_prv_img', true);
		$default_image = get_directorist_option('default_preview_image', DIRECTORIST_ASSETS . 'images/grid.jpg');
		$listing_prv_imgurl = !empty($listing_prv_img) ? atbdp_get_image_source($listing_prv_img, 'small') : '';
		$listing_prv_imgurl = atbdp_image_cropping($listing_prv_img, 150, 150, true, 100)['url'];
		$img_url = !empty($listing_prv_imgurl) ? $listing_prv_imgurl : $default_image;
		$image = "<figure><img src=" . $img_url . " /></figure>";
		if ( empty( $display_image_map ) ) {
			$image = '';
		}

		$t = get_the_title();
		$t = ! empty( $t ) ? $t : __('No Title', 'directorist');
		if ( empty( $display_title_map ) ) {
			$t = '';
		}

		$info_content = "";
		$info_content .= "<div class='map-info-wrapper map-listing-card-single'>";

		// favorite badge
		if( ! empty( $display_favorite_badge_map ) ) {
			$info_content .= $this->favorite_badge_template_map();
		}
		if (!empty($display_image_map)) {
			$info_content .= "<div class='map-listing-card-single__img'>$image</div>";
		}
		$info_content .= $this->user_avatar();
		$info_content .= "<div class='map-listing-card-single__content'>";
		if (!empty($display_title_map)) {
			$info_content .= "<h3 class='map-listing-card-single__content__title'>$t</h3>";
		}
		$info_content .= "<div class='map-listing-card-single__content__meta'>";
		$info_content .= $this->get_review_template();
		$info_content .= $this->price_html();
		$info_content .= "</div><div class='map-listing-card-single__content__info'>";

		if( ! empty( $phone ) ) {
			$info_content .= "<div class='directorist-info-item map-listing-card-single__content__phone'>" . directorist_icon( 'fas fa-phone-alt', false ) . "<div class='directorist-info-item'><a href='tel:{$phone}'>{$phone}</a></div></div>";
		}

		if (!empty($display_address_map) && !empty($ad)) {
			$info_content .= "<div class='directorist-info-item map-listing-card-single__content__address'>" .directorist_icon('fas fa-map-marker-alt', false). "<div class='directorist-info-item'>";
			$info_content .= apply_filters("atbdp_address_in_map_info_window", "<a href='http://www.google.com/maps?daddr={$manual_lat},{$manual_lng}' target='_blank'>{$ad}</a>");
		}

		$info_content .= "</div></div></div>";


		$cats = get_the_terms($this->id, ATBDP_CATEGORY);
		$cat_icon = '';
		// if (!empty($cats)) {
		// 	$cat_icon = get_cat_icon($cats[0]->term_id);
		// }
		$cat_icon = !empty($cat_icon) ? $cat_icon : 'fas fa-map-pin';
		$cat_icon = directorist_icon( $cat_icon, false );

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

		return json_encode( $args );
	}

	public function get_review_template() {
		// Review
		$average           = directorist_get_listing_rating( $this->id );
		$reviews_count     = directorist_get_listing_review_count( $this->id );

		// Icons
		$icon_empty_star = directorist_icon( 'fas fa-star', false, 'star-empty' );
		$icon_half_star  = directorist_icon( 'fas fa-star-half-alt', false, 'star-half' );
		$icon_full_star  = directorist_icon( 'fas fa-star', false, 'star-full' );

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

		$args = array(
			'review_stars'    => $review_stars,
			'total_reviews'   => $reviews_count,
			'average_reviews' => number_format( $average, 1 ),
		);

		ob_start();
			Helper::get_template( 'single/fields/map-rating', $args );
		return ob_get_clean();
	}

	/**
	 * Unused method
	 *
	 * @return string
	 */
	public function get_reviewer_img() {
		_deprecated_function( __METHOD__, '7.4.3' );
		return '';
	}

	/**
	 * Unused method
	 *
	 * @return void
	 */
	public function review_template() {
		_deprecated_function( __METHOD__, '7.4.3' );
	}

	public function loop_is_favourite() {
		$favourites = directorist_get_user_favorites( get_current_user_id() );
		return in_array( $this->id , $favourites );
	}

	/**
	 * This function loads the template file 'single/map-favorite-badge'
	 *
	 * The template file is used to display the favorite badge template for a single listing map.
	 */
	public function favorite_badge_template_map() {
		ob_start();
			Helper::get_template( 'single/fields/map-favorite-badge', array( 'listings' => $this ) );
		return ob_get_clean();
	}

	public function user_avatar() {

		$user_pro_pic   = get_user_meta( $this->author_id, 'pro_pic', true );
		$u_pro_pic   	= ! empty( $u_pro_pic ) ? wp_get_attachment_image_src( $u_pro_pic, 'thumbnail' ) : '';
		$author_data 	= get_userdata( $this->author_id );

		$author_first_name = ! empty( $author_data ) ?  $author_data->first_name : '';
		$author_last_name  = ! empty( $author_data ) ?  $author_data->last_name : '';

		$args = array(
			'author_link'      => ATBDP_Permalink::get_user_profile_page_link( $this->author_id ),
			'u_pro_pic'        => $u_pro_pic,
			'avatar_img'       => get_avatar( $this->author_id, apply_filters( 'atbdp_avatar_size', 32 ) ),
			'author_full_name' => $author_first_name . ' ' . $author_last_name,
		);

		ob_start();
			Helper::get_template( 'single/fields/user_avatar', $args );
		return ob_get_clean();
	}

	public function get_related_listings( $data = [] ) {
		$number       = ! empty( $data['similar_listings_number_of_listings_to_show'] ) ? $data['similar_listings_number_of_listings_to_show'] : 3;
		$same_author  = ! empty( $data['listing_from_same_author'] ) ? true : false;
		$logic        = ! empty( $data['similar_listings_logics'] ) ? $data['similar_listings_logics'] : 'OR';
		$relationship = ( $logic == 'AND' ) ? 'AND' : 'OR';

		$id            = $this->id;
		$atbd_cats     = get_the_terms($id, ATBDP_CATEGORY);
		$atbd_tags     = get_the_terms($id, ATBDP_TAGS);
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
					'field'    => 'term_id',
					'terms'    => $atbd_cats_ids,
				),
				array(
					'taxonomy' => ATBDP_TAGS,
					'field'    => 'term_id',
					'terms'    => $atbd_tags_ids,
				),
			),
			'posts_per_page' => (int)$number,
			'post__not_in'   => array($id),
		);

		if( !empty( $same_author ) ){
			$args['author']  = get_post_field( 'post_author', $id );
		}

		$meta_queries = array();
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

	public function related_slider_attr() {
		$atts = array(
			'columns'   => get_directorist_type_option( $this->type, 'similar_listings_number_of_columns', 3 ),
			'prevArrow' => sprintf( '<a class="directorist-slc__nav directorist-slc__nav--left">%s</a>', directorist_icon( 'las la-angle-left', false ) ),
            'nextArrow' => sprintf( '<a class="directorist-slc__nav directorist-slc__nav--right">%s</a>', directorist_icon( 'las la-angle-right', false ) ),
		);
		return json_encode( $atts );
	}

	public function get_related_columns() {
		$columns = get_directorist_type_option( $this->type, 'similar_listings_number_of_columns', 3 );
		return 12/$columns;
	}

	public function load_map_resources() {
		_deprecated_function( __METHOD__, '7.3' );
	}

	public function load_related_listings_script() {
		_deprecated_function( __METHOD__, '7.3' );
	}
}