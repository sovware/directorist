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
	public $title;

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


	public function __construct($id = '') {
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

		$this->post   		 = get_post( $id );
		$this->title  		 = get_the_title( $id );
		$directory_type 	 = get_post_meta( $id, '_directory_type', true);
		$term 				 = get_term_by( is_numeric($directory_type) ? 'id' : 'slug', $directory_type, 'atbdp_listing_types' );
		$this->type          = (int) $term->term_id;
		$this->header_data   = get_term_meta( $this->type, 'single_listing_header', true );
		$this->content_data  = $this->build_content_data();
		// dvar_dump($this->content_data);
		
		$this->tagline               = get_post_meta( $id, '_tagline', true );
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
				if ( ! is_array( $value ) ) { continue; }

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

		// e_var_dump($content_data);

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
			Helper::get_template( 'single-listing/section-general', $args );
		}
		else {
			$template = 'single-listing/section-'. $section_data['widget_name'];
			$template = apply_filters( 'directorist_single_section_template', $template, $section_data );
			Helper::get_template( $template, $args );
		}
	}

	public function field_template( $data ) {
		$value = Helper::get_widget_value( $this->id, $data );

		if ( isset( $data['original_data']['options'] ) && is_string( $data['original_data']['options'] ) ) {
			$data['original_data']['options'] = Helper::parse_input_field_options_string_to_array( $data['original_data']['options'] );
		}

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

		$widget_name = ! empty( $data['original_data']['widget_name'] ) ? $data['original_data']['widget_name'] : $data['widget_name'];
		$template = 'single-listing/items/' . $widget_name;
		$template = apply_filters( 'directorist_single_item_template', $template, $data );
		if( $load_template )
		Helper::get_template( $template, $args );
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
		if( $actions )
		Helper::get_template('single-listing/quick-actions', $args );
	}

	public function quick_info_template() {
		$quick_info = ! empty( $this->header_data['listings_header']['quick_info'] ) ? $this->header_data['listings_header']['quick_info'] : '';
		$args = array(
			'listing' => $this,
			'info'    => $quick_info,
		);
		if( $quick_info )
		Helper::get_template('single-listing/quick-info', $args );
	}

	public function slider_template() {
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

		$args = array(
			'listing'    => $this,
			'has_slider' => !empty( $this->header_data['listings_header']['thumbnail'] ) ? true : false,
			'data'       => $data,
		);

		Helper::get_template('single-listing/slider', $args );
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
		$price_html = apply_filters('atbdp_listing_price', sprintf("<span class='atbd_meta atbd_listing_price'>%s</span>", $price));
		return $price_html;
	}

	public function review_count_html() {
		$id = $this->id;

		$reviews_count = ATBDP()->review->db->count(array('post_id' => $id));
		$reviews = (($reviews_count > 1) || ($reviews_count === 0)) ? __(' Reviews', 'directorist') : __(' Review', 'directorist');
		$review_count_html = $reviews_count . $reviews;
		return $review_count_html;
	}

	public static function single_content_wrapper($content)
	{
		$id = get_the_ID();
		$type = get_post_meta( $id, '_directory_type', true );

		if (is_singular(ATBDP_POST_TYPE) && in_the_loop() && is_main_query()) {

			/**
			 * @since 5.10.0
			 * It fires before single listing load
			 */
			do_action('atbdp_before_single_listing_load', $id);
			ob_start();
			$single_listing_shortcodes = apply_filters('single_listing_shortcodes', [
				'directorist_listing_top_area' => [
					'order'  => apply_filters('directorist_listing_top_area_order', 0),
					'before' => '',
					'after'  => '',
				],
				'directorist_listing_tags' => [
					'order'  => apply_filters('directorist_listing_tags_order', 1),
					'before' => '',
					'after'  => function () {
						/**
						 * Fires after the title and sub title of the listing is rendered on the single listing page
						 *
						 * @since 1.0.0
						 */
						do_action('atbdp_after_listing_tagline');
					},
				],
				'directorist_listing_custom_fields' => [
					'order'  => apply_filters('directorist_listing_custom_fields_order', 2),
					'before' => '',
					'after'  => '',
				],
				'directorist_listing_video' => [
					'order'  => apply_filters('directorist_listing_video_order', 3),
					'before' => '',
					'after'  => '',
				],
				'directorist_listing_map' => [
					'order'  => apply_filters('directorist_listing_map_order', 4),
					'before' => '',
					'after'  => '',
				],
				'directorist_listing_contact_information' => [
					'order'  => apply_filters('directorist_listing_contact_information_order', 5),
					'before' => '',
					'after'  => '',
				],
				'directorist_listing_contact_owner' => [
					'order'  => apply_filters('directorist_listing_contact_owner_order', 6),
					'before' => '',
					'after'  => function ($id) {
						/**
						 * @since 5.0.5
						 */
						do_action('atbdp_after_contact_listing_owner_section', $id);
					},
				],
				'directorist_listing_author_info' => [
					'order'  => apply_filters('directorist_listing_author_info_order', 7),
					'before' => '',
					'after'  => '',
				],
				'directorist_listing_review' => [
					'order'  => apply_filters('directorist_listing_review_order', 8),
					'before' => '',
					'after'  => '',
				],
				'directorist_related_listings' => [
					'order'  => apply_filters('directorist_related_listings_order', 9),
					'before' => '',
					'after'  => '',
				],
			]);

			// Sort the shortcodes
			$sorted_shortcodes = [];
			foreach ($single_listing_shortcodes as $shortcode => $arg) {
				$sorted_shortcodes[$shortcode] = (isset($arg['order']) && is_numeric($arg['order'])) ? $arg['order'] : 0;
			}
			asort($sorted_shortcodes);

			// Print the sorted shortcodes
			foreach ($sorted_shortcodes as $shortcode_key => $value) {
				$shortcode = $single_listing_shortcodes[$shortcode_key];

				if (!empty($shortcode['before']) && is_callable($shortcode['before'])) {
					$arg['before']($id);
				}

				do_action("before_{$shortcode_key}", $id);
				echo "[{$shortcode_key}]";
				do_action("after_{$shortcode_key}", $id);

				if (!empty($shortcode['after']) && is_callable($shortcode['after'])) {
					$shortcode['after']($id);
				}
				
			}

			$content = ob_get_clean();
			$content = do_shortcode($content);
			

			$payment   = isset($_GET['payment']) ? $_GET['payment'] : '';
			$redirect  = isset($_GET['redirect']) ? $_GET['redirect'] : '';
			$edit_link = !empty($payment) ? add_query_arg('redirect', $redirect, ATBDP_Permalink::get_edit_listing_page_link($id)) : ATBDP_Permalink::get_edit_listing_page_link($id);

			$display_preview = get_directorist_option('preview_enable', 1);
			$submit_text = __('Continue', 'directorist');
			$url = ''; 
			if ($display_preview && $redirect) {
				$post_id = isset($_GET['post_id']) ? $_GET['post_id'] : $id;
				$edited = isset($_GET['edited']) ? $_GET['edited'] : '';
				$pid = isset($_GET['p']) ? $_GET['p'] : '';
				$pid = empty($pid) ? $post_id : $pid;
				if (empty($payment)) {
					$redirect_page = get_directorist_option('edit_listing_redirect', 'view_listing');
					if( 'view_listing' === $redirect_page){
						$url = add_query_arg(array('p' => $pid, 'post_id' => $pid, 'reviewed' => 'yes', 'edited' => $edited ? 'yes' : 'no'), $redirect);
					}else{
						$url = $redirect;
					}
				} else {
					$url = add_query_arg(array('atbdp_listing_id' => $pid, 'reviewed' => 'yes'), $_GET['redirect']);
				}
			}
			$header 				= get_term_meta( $type, 'single_listing_header', true );
			
			$pending_msg 			= get_directorist_option('pending_confirmation_msg', __( 'Thank you for your submission. Your listing is being reviewed and it may take up to 24 hours to complete the review.', 'directorist' ) );
			$publish_msg 			= get_directorist_option('publish_confirmation_msg', __( 'Congratulations! Your listing has been approved/published. Now it is publicly available.', 'directorist' ) );
			$confirmation_msg = '';
			if( isset( $_GET['notice'] ) ) {
				$new_listing_status  = get_term_meta( $type, 'new_listing_status', true );
				$edit_listing_status = get_term_meta( $type, 'edit_listing_status', true );
				$edited              = ( isset( $_GET['edited'] ) ) ? $_GET['edited'] : 'no';

				if ( $edited === 'no' ) {
					$confirmation_msg = 'publish' === $new_listing_status ? $publish_msg : $pending_msg;
				} else {
					$confirmation_msg = 'publish' === $edit_listing_status ? $publish_msg : $pending_msg;
				}
			}
			
			$args = array(
				'author_id'         	  => get_post_field('post_author', $id),
				'content'           	  => $content,
				'class_col'         	  => is_active_sidebar('right-sidebar-listing') ? 'col-lg-8' : 'col-lg-12',
				'class_float'      		  => $redirect ? 'atbdp_float_active' : 'atbdp_float_none',
				'display_back_link' 	  => !empty( $header['options']['general']['back']['label'] ) ? $header['options']['general']['back']['label'] : '',
				'edit_link'         	  => $edit_link,
				'edit_text'         	  => apply_filters('atbdp_listing_edit_btn_text', __(' Edit', 'directorist')),
				'url'               	  => $url,
				'submit_text'       	  => apply_filters('atbdp_listing_preview_btn_text', $submit_text),
				'submission_confirmation' => get_directorist_option('submission_confirmation', 1 ),
				'confirmation_msg' 		  => $confirmation_msg,		
			);
			$html = Helper::get_template_contents('single-listing/content-wrapper', $args);
			return $html;
		}

		return $content;
	}

	public function header_template() {
		$section_title = !empty($this->header_data['options']['general']['section_title']['label']) ? $this->header_data['options']['general']['section_title']['label'] : '';
		$section_icon = !empty($this->header_data['options']['general']['section_title']['icon']) ? $this->header_data['options']['general']['section_title']['icon'] : '';
		$enable_title = !empty( $this->header_data['options']['content_settings']['listing_title']['enable_title'] ) ? $this->header_data['options']['content_settings']['listing_title']['enable_title'] : '';
		$enable_tagline = !empty( $this->header_data['options']['content_settings']['listing_title']['enable_tagline'] ) ? $this->header_data['options']['content_settings']['listing_title']['enable_tagline'] : '';
		$enable_content = !empty( $this->header_data['options']['content_settings']['listing_description']['enable'] ) ? $this->header_data['options']['content_settings']['listing_description']['enable'] : '';
		
		$args = array(
			'listing'           => $this,
			'section_title'     => $section_title,
			'section_icon'      => $section_icon,
			'data'              => $this->header_data,
			'enable_title'      => $enable_title,
			'enable_tagline'    => $enable_tagline,
			'enable_content'    => $enable_content,
		);
		
		return Helper::get_template('single-listing/header', $args);
	}

	public function render_shortcode_single_listing() {
		if (!is_singular(ATBDP_POST_TYPE)) {
			return;
		}

		$args = array(
			'listing' => $this,
		);

		return Helper::get_template_contents('single-listing/single-listing', $args);
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

	public function render_shortcode_custom_fields()
	{

		if (!is_singular(ATBDP_POST_TYPE)) {
			return;
		}

		$id                 = $this->id;
		$fm_plan            = get_post_meta($id, '_fm_plans', true);
		$field_data         = $this->get_custom_field_data();
		$has_custom_fields  = !empty($field_data) ? true : false;

		$args = array(
			'listing'            => $this,
			'plan_custom_field'  => is_fee_manager_active() ? is_plan_allowed_custom_fields($fm_plan) : true,
			'section_title'      => get_directorist_option('custom_section_lable', __('Details', 'directorist')),
			'has_custom_fields'  => apply_filters('atbdp_single_listing_custom_field', $has_custom_fields),
			'custom_field_data'  => $field_data,
		);

		return Helper::get_template_contents('single-listing/custom-field', $args);
	}

	public function render_shortcode_video()
	{
		if (!is_singular(ATBDP_POST_TYPE)) {
			return;
		}

		$id      = $this->id;
		$fm_plan = get_post_meta($id, '_fm_plans', true);

		$args = array(
			'listing'          => $this,
			'enable_video_url' => get_directorist_option('atbd_video_url', 1),
			'videourl'         => get_post_meta($id, '_videourl', true),
			'plan_video'       => is_fee_manager_active() ? is_plan_allowed_listing_video($fm_plan) : true,
			'video_label'      => get_directorist_option('atbd_video_title', __('Video', 'directorist')),
		);

		return Helper::get_template_contents('single-listing/listing-video', $args);
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
			wp_localize_script('atbdp-single-listing-osm', 'localized_data', $args);
			wp_enqueue_script('atbdp-single-listing-osm');
		}

		if ('google' === $args['select_listing_map']) {
			wp_localize_script('atbdp-single-listing-gmap', 'localized_data', $args);
			wp_enqueue_script('atbdp-single-listing-gmap');
		}
	}

	public function render_shortcode_contact_information()
	{
		if (!is_singular(ATBDP_POST_TYPE)) {
			return;
		}

		$id      = $this->id;
		$fm_plan = get_post_meta($id, '_fm_plans', true);

		$address = get_post_meta($id, '_address', true);
		$address_map_link = get_directorist_option('address_map_link', 0);

		$args = array(
			'listing'                   => $this,
			'address'                   => $address,
			'phone'                     => get_post_meta($id, '_phone', true),
			'phone2'                    => get_post_meta($id, '_phone2', true),
			'fax'                       => get_post_meta($id, '_fax', true),
			'email'                     => get_post_meta($id, '_email', true),
			'website'                   => get_post_meta($id, '_website', true),
			'zip'                       => get_post_meta($id, '_zip', true),
			'social'                    => get_post_meta($id, '_social', true),
			'hide_contact_info'         => get_post_meta($id, '_hide_contact_info', true),
			'plan_phone'                => is_fee_manager_active() ? is_plan_allowed_listing_phone($fm_plan) : true,
			'plan_email'                => is_fee_manager_active() ? is_plan_allowed_listing_email($fm_plan) : true,
			'plan_webLink'              => is_fee_manager_active() ? is_plan_allowed_listing_webLink($fm_plan) : true,
			'plan_social_networks'      => is_fee_manager_active() ? is_plan_allowed_listing_social_networks($fm_plan) : true,
			'disable_contact_info'      => apply_filters('atbdp_single_listing_contact_info', get_directorist_option('disable_contact_info', 0)),
			'contact_info_text'         => get_directorist_option('contact_info_text', __('Contact Information', 'directorist')),
			'display_address_field'     => get_directorist_option('display_address_field', 1),
			'address_label'             => get_directorist_option('address_label', __('Address', 'directorist')),
			'address_html'              => !empty($address_map_link) ? '<a target="google_map" href="https://www.google.de/maps/search/?' . esc_html($address) . '">' . esc_html($address) . '</a>' : esc_html($address),
			'display_phone_field'       => get_directorist_option('display_phone_field', 1),
			'phone_label'               => get_directorist_option('phone_label', __('Phone', 'directorist')),
			'display_phone2_field'      => get_directorist_option('display_phone_field2', 1),
			'phone_label2'              => get_directorist_option('phone_label2', __('Phone Number 2', 'directorist')),
			'display_fax_field'         => get_directorist_option('display_fax', 1),
			'fax_label'                 => get_directorist_option('fax_label', __('Fax', 'directorist')),
			'display_email_field'       => get_directorist_option('display_email_field', 1),
			'email_label'               => get_directorist_option('email_label', __('Email', 'directorist')),
			'display_website_field'     => get_directorist_option('display_website_field', 1),
			'website_label'             => get_directorist_option('website_label', __('Website', 'directorist')),
			'use_nofollow'              => get_directorist_option('use_nofollow'),
			'display_zip_field'         => get_directorist_option('display_zip_field', 1),
			'zip_label'                 => get_directorist_option('zip_label', __('Zip/Post Code', 'directorist')),
			'display_social_info_field' => get_directorist_option('display_social_info_field', 1),
			'display_social_info_for'   => get_directorist_option('display_social_info_for', 'admin_users'),
		);

		return Helper::get_template_contents('single-listing/contact-information', $args);
	}

	public function render_shortcode_author_info()
	{
		if (!is_singular(ATBDP_POST_TYPE)) {
			return;
		}

		$id      = $this->id;
		$fm_plan = get_post_meta($id, '_fm_plans', true);

		$author_id = get_post_field('post_author', $id);
		$u_pro_pic = get_user_meta($author_id, 'pro_pic', true);
		$user_registered = get_the_author_meta('user_registered', $author_id);

		$args = array(
			'listing'           => $this,
			'author_id'         => $author_id,
			'section_title'     => get_directorist_option('atbd_author_info_title', __('Author Info', 'directorist')),
			'u_pro_pic'         => !empty($u_pro_pic) ? wp_get_attachment_image_src($u_pro_pic, 'thumbnail') : '',
			'member_since'      => human_time_diff(strtotime($user_registered), current_time('timestamp')),
			'avatar_img'        => get_avatar($author_id, apply_filters('atbdp_avatar_size', 32)),
			'author_name'       => get_the_author_meta('display_name', $author_id),
			'address'           => get_user_meta($author_id, 'address', true),
			'phone'             => get_user_meta($author_id, 'atbdp_phone', true),
			'email_show'        => get_directorist_option('display_author_email', 'public'),
			'email'             => get_the_author_meta('user_email', $author_id),
			'website'           => get_the_author_meta('user_url', $author_id),
			'facebook'          => get_user_meta($author_id, 'atbdp_facebook', true),
			'twitter'           => get_user_meta($author_id, 'atbdp_twitter', true),
			'linkedIn'          => get_user_meta($author_id, 'atbdp_linkedin', true),
			'youtube'           => get_user_meta($author_id, 'atbdp_youtube', true),

		);

		return Helper::get_template_contents('single-listing/author-details', $args);
	}

	public function render_shortcode_contact_owner()
	{
		if (!is_singular(ATBDP_POST_TYPE)) {
			return;
		}

		$id      = $this->id;
		$fm_plan = get_post_meta($id, '_fm_plans', true);

		$args = array(
			'listing'               => $this,
			'listing_id'            => $id,
			'plan_permission'       => is_fee_manager_active() ? is_plan_allowed_owner_contact_widget($fm_plan) : true,
			'hide_contact_owner'    => get_post_meta($id, '_hide_contact_owner', true),
			'disable_contact_owner' => get_directorist_option('disable_contact_owner', 1),
			'section_title'         => get_directorist_option('contact_listing_owner', __('Contact Listing Owner', 'directorist')),
			'email'                 => get_post_meta($id, '_email', true),
		);

		return Helper::get_template_contents('single-listing/contact-owner', $args);
	}

	public function render_shortcode_tags()
	{
		if (!is_singular(ATBDP_POST_TYPE)) {
			return;
		}

		$id = $this->id;

		$args = array(
			'listing'            => $this,
			'listing_id'         => $id,
			'tags'               => get_the_terms($id, ATBDP_TAGS),
			'enable_single_tag'  => get_directorist_option('enable_single_tag', 1),
			'tags_section_lable' => get_directorist_option('tags_section_lable', __('Tags', 'directorist')),
		);

		return Helper::get_template_contents('single-listing/tags', $args);
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

		Helper::get_template('single-listing/listing-review', $args);
	}

	public function related_listings_query( $number, $relationship, $same_author )
	{
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

		return apply_filters('atbdp_related_listing_args', $args);
	}

	public function load_related_listings_script() {
		$columns = get_directorist_type_option( $this->type, 'similar_listings_number_of_columns', 3 );

		$is_rtl = is_rtl() ? 'true' : '';

		$localized_data = array(
			'is_rtl' => $is_rtl,
			'rel_listing_column' => $columns,
		);

		wp_enqueue_script('atbdp-related-listings-slider');
		wp_localize_script('atbdp-related-listings-slider', 'data', $localized_data);
	}

	public function related_listings_template() {
		$enabled = get_directorist_type_option( $this->type, 'enable_similar_listings', 1 );
		$title   = get_directorist_type_option( $this->type, 'similar_listings_title' );
		$logic   = get_directorist_type_option( $this->type, 'similar_listings_logics', 'OR' );
		$number  = get_directorist_type_option( $this->type, 'similar_listings_number_of_listings_to_show', 2 );
		$columns = get_directorist_type_option( $this->type, 'similar_listings_number_of_columns', 3 );
		$same_author   = get_directorist_type_option( $this->type, 'listing_from_same_author', false );

		$relationship = ( $logic == 'AND' ) ? 'AND' : 'OR';

		if (empty($enabled)) {
			return;
		}

		$is_rtl = is_rtl() ? 'true' : '';

		$localized_data = array(
			'is_rtl' => $is_rtl,
			'rel_listing_column' => $columns,
		);

		wp_enqueue_script('atbdp-related-listings-slider');
		wp_localize_script('atbdp-related-listings-slider', 'data', $localized_data);

		$query = $this->related_listings_query( $number, $relationship, $same_author );

		$related_listings = new Directorist_Listings(array(), 'related', $query, ['cache' => false]);
		$args = array(
			'listing'          => $this,
			'related_listings' => $related_listings,
			'title'            => $title,
		);

		Helper::get_template('single-listing/section-related-listings', $args);
	}
}