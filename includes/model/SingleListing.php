<?php
/**
 * @author AazzTech
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Directorist_Single_Listing {

	protected static $instance = null;

	public $id;
	public $post;

	public function __construct( $id = '' ) {
		if ( ! $id ) {
			$id = get_the_ID();
		}
		$this->id = (int) $id;
	}

	public function get_id() {
		return $this->id;
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

	public function header_actions_template() {
		$args = array(
			'listing_id'           => $this->get_id(),
			'enable_favourite'     => get_directorist_option('enable_favourite', 1),
			'enable_social_share'  => get_directorist_option('enable_social_share', 1),
			'social_share_data'    => $this->social_share_data(),
			'enable_report_abuse'  => get_directorist_option('enable_report_abuse', 1),
		);

		$html = atbdp_return_shortcode_template( 'single-listing/listing-header-actions', $args );
		
        /**
         * @since 5.0
         */
        echo apply_filters('atbdp_header_before_image_slider', $html);
    }

    public function the_slider() {

    	$id = $this->get_id();
    	$fm_plan              = get_post_meta($id, '_fm_plans', true);
    	$full_image_links     = array();
    	$listing_img          = get_post_meta($id, '_listing_img', true);
    	$display_slider_image = get_directorist_option('dsiplay_slider_single_page', 1);
    	$listing_imgs         = (!empty($listing_img) && !empty($display_slider_image)) ? $listing_img : array();

    	foreach ($listing_imgs as $value) {
    		$full_image_links[$value] = atbdp_get_image_source($value, 'large');
    	}

    	$display_prv_image = get_post_meta($id, '_listing_prv_img', true);
    	$listing_prv_img = get_post_meta($id, '_listing_prv_img', true);
    	$listing_prv_imgurl = !empty($listing_prv_img) ? atbdp_get_image_source($listing_prv_img, 'large') : '';
    	$plan_slider = true;
    	if (is_fee_manager_active()) {
    		$plan_slider = is_plan_allowed_slider($fm_plan);
    	}

    	$args = array(
    		'image_links'        => $full_image_links,
    		'display_prv_image'  => $display_prv_image,
    		'listing_prv_imgurl' => $listing_prv_imgurl,
    		'plan_slider'        => $plan_slider,
    		'listing_prv_img'    => $listing_prv_img,
    		'p_title'            => get_the_title(),
    	);

    	$slider = get_plasma_slider($args);

    	echo apply_filters('atbdp_single_listing_gallery_section', $slider);
    }

    public function header_meta_template() {
    	$id = $this->get_id();
    	$fm_plan = get_post_meta($id, '_fm_plans', true);

    	$reviews_count = ATBDP()->review->db->count(array('post_id' => $id));
    	$reviews = (($reviews_count > 1) || ($reviews_count === 0)) ? __(' Reviews', 'directorist') : __(' Review', 'directorist');
    	$review_count_html = $reviews_count . $reviews;

    	$args = array(
    		'listing'                      => $this,
    		'listing_id'                   => $this->get_id(),
    		'plan_price'                   => is_fee_manager_active() ? is_plan_allowed_price($fm_plan) : true,
    		'plan_average_price'           => is_fee_manager_active() ? is_plan_allowed_average_price_range($fm_plan) : true,
    		'enable_review'                => get_directorist_option('enable_review', 'yes'),
    		'is_disable_price'             => get_directorist_option('disable_list_price'),
    		'review_count_html'            => $review_count_html,
    		'price'                        => get_post_meta($id, '_price', true),
    		'price_range'                  => get_post_meta($id, '_price_range', true),
    		'atbd_listing_pricing'         => get_post_meta($id, '_atbd_listing_pricing', true),
    		'enable_new_listing'           => get_directorist_option('display_new_badge_cart', 1),
    		'display_feature_badge_single' => get_directorist_option('display_feature_badge_cart', 1),
    		'display_popular_badge_single' => get_directorist_option('display_popular_badge_cart', 1),
    		'featured'                     => get_post_meta($id, '_featured', true),
    		'feature_badge_text'           => get_directorist_option('feature_badge_text', 'Feature'),
    		'popular_badge_text'           => get_directorist_option('popular_badge_text', 'Popular'),
    		'cat_list'                     => get_the_term_list($id, ATBDP_CATEGORY,'',', '),
    	);

    	$html = atbdp_return_shortcode_template( 'single-listing/listing-header-meta', $args );
    	
        /**
         * @since 5.0
         */
        echo apply_filters('atbdp_before_listing_title', $html);
    }

    public static function single_content_wrapper($content) {
    	$id = get_the_ID();
    	$single_page_id = get_directorist_option('single_listing_page');

    	if (is_singular(ATBDP_POST_TYPE) && in_the_loop() && is_main_query()) {

    		$include = apply_filters('include_style_settings', true);

    		if ($include) {
    			include ATBDP_DIR . 'public/assets/css/style.php';
    		}

            /**
             * @since 5.10.0
             * It fires before single listing load
             */
            do_action('atbdp_before_single_listing_load', $id);

            if ( !empty($single_page_id) ) {
            	if ( did_action( 'elementor/loaded' ) && \Elementor\Plugin::$instance->db->is_built_with_elementor( $single_page_id ) ) {
            		$content = \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $single_page_id );
            	}
            	else {
            		$content = get_post_field('post_content', $single_page_id);
            		$content = do_shortcode($content);
            	}
            }
            else {
							ob_start();
							$single_listing_shortcodes = apply_filters( 'single_listing_shortcodes', [
								'directorist_listing_top_area' => [
									'order'  => apply_filters( 'directorist_listing_top_area_order', 0 ),
									'before' => '',
									'after'  => '',
								],
								'directorist_listing_tags' => [
									'order'  => apply_filters( 'directorist_listing_tags_order', 1 ),
									'before' => '',
									'after'  => function() {
										/**
										 * Fires after the title and sub title of the listing is rendered on the single listing page
										 *
										 * @since 1.0.0
										 */
										do_action('atbdp_after_listing_tagline');
									},
								],
								'directorist_listing_custom_fields' => [
									'order'  => apply_filters( 'directorist_listing_custom_fields_order', 2 ),
									'before' => '',
									'after'  => '',
								],
								'directorist_listing_video' => [
									'order'  => apply_filters( 'directorist_listing_video_order', 3 ),
									'before' => '',
									'after'  => '',
								],
								'directorist_listing_map' => [
									'order'  => apply_filters( 'directorist_listing_map_order', 4 ),
									'before' => '',
									'after'  => '',
								],
								'directorist_listing_contact_information' => [
									'order'  => apply_filters( 'directorist_listing_contact_information_order', 5 ),
									'before' => '',
									'after'  => '',
								],
								'directorist_listing_contact_owner' => [
									'order'  => apply_filters( 'directorist_listing_contact_owner_order', 6 ),
									'before' => '',
									'after'  => function( $id ){
										/**
										* @since 5.0.5
										*/
										do_action('atbdp_after_contact_listing_owner_section', $id);
									},
								],
								'directorist_listing_author_info' => [
									'order'  => apply_filters( 'directorist_listing_author_info_order', 7 ),
									'before' => '',
									'after'  => '',
								],
								'directorist_listing_review' => [
									'order'  => apply_filters( 'directorist_listing_review_order', 8 ),
									'before' => '',
									'after'  => '',
								],
								'directorist_related_listings' => [
									'order'  => apply_filters( 'directorist_related_listings_order', 9 ),
									'before' => '',
									'after'  => '',
								],
							]);

							// Sort the shortcodes
							$sorted_shortcodes = [];
							foreach ( $single_listing_shortcodes as $shortcode => $arg ) {
								$sorted_shortcodes[$shortcode] = ( isset( $arg['order'] ) && is_numeric( $arg['order'] )  ) ? $arg['order'] : 0;
							}
							asort( $sorted_shortcodes );

							// Print the sorted shortcodes
							foreach ( $sorted_shortcodes as $shortcode_key => $value ) {
								$shortcode = $single_listing_shortcodes[ $shortcode_key ];

								if ( ! empty( $shortcode['before'] ) && is_callable( $shortcode['before'] )  ) {
									$arg['before']( $id );
								} 
								
								do_action( "before_{$shortcode_key}", $id );

								echo "[{$shortcode_key}]";

								if ( ! empty( $shortcode['after'] ) && is_callable( $shortcode['after'] )  ) {
									$shortcode['after']( $id );
								} 
								
								do_action( "after_{$shortcode_key}", $id );
							}

							$content = ob_get_clean();
            	$content = do_shortcode($content);
            }

            $payment   = isset($_GET['payment']) ? $_GET['payment'] : '';
            $redirect  = isset($_GET['redirect']) ? $_GET['redirect'] : '';
            $edit_link = !empty($payment) ? add_query_arg('redirect', $redirect, ATBDP_Permalink::get_edit_listing_page_link($id)) : ATBDP_Permalink::get_edit_listing_page_link($id);

            $display_preview = get_directorist_option('preview_enable', 1);
            $url = $submit_text = '';

            if ( $display_preview && $redirect ) {
            	$post_id = isset($_GET['post_id']) ? $_GET['post_id'] : $id;
            	$edited = isset($_GET['edited']) ? $_GET['edited'] : '';
            	$pid = isset($_GET['p']) ? $_GET['p'] : '';
            	$pid = empty($pid) ? $post_id : $pid;
            	if (empty($payment)){
            		$url = add_query_arg(array('p' => $pid, 'post_id' => $pid, 'reviewed' => 'yes', 'edited' => $edited ? 'yes' : 'no'), $redirect);
            		$submit_text = __('Submit', 'directorist');
            	}
            	else {
            		$url = add_query_arg(array('atbdp_listing_id' => $pid, 'reviewed' => 'yes'), $_GET['redirect']);
            		$submit_text = __('Pay & Submit', 'directorist');
            	}
            }

            $args = array(
            	'author_id'         => get_post_field('post_author', $id),
            	'content'           => $content,
            	'class_col'         => is_active_sidebar('right-sidebar-listing') ? 'col-lg-8' : 'col-lg-12',
            	'class_float'       => $redirect ? 'atbdp_float_active' : 'atbdp_float_none',
            	'display_back_link' => get_directorist_option('display_back_link', 1),
            	'edit_link'         => $edit_link,
            	'edit_text'         => apply_filters('atbdp_listing_edit_btn_text', __(' Edit', 'directorist')),
            	'url'               => $url,
            	'submit_text'       => apply_filters('atbdp_listing_preview_btn_text', $submit_text),
            );

            $html = atbdp_return_shortcode_template( 'single-listing/content-wrapper', $args );
            return $html;
        }

        return $content;
    }

    public function render_shortcode_top_area() {

    	if ( !is_singular( ATBDP_POST_TYPE ) ) {
    		return;
    	}

    	$id      = $this->get_id();
    	$fm_plan = get_post_meta($id, '_fm_plans', true);
    	$post    = get_post($id);
    	$content = apply_filters('get_the_content', $post->post_content);
    	$content = do_shortcode(wpautop($content));

    	$args = array(
    		'listing'                      => $this,
    		'listing_id'                   => $id,
    		'listing_details_text'         => apply_filters('atbdp_single_listing_details_section_text', get_directorist_option('listing_details_text', __('Listing Details', 'directorist'))),
    		'p_title'                      => get_the_title(),
    		'fm_plan'                      => $fm_plan,
    		'tagline'                      => get_post_meta($id, '_tagline', true),
    		'display_tagline_field'        => get_directorist_option('display_tagline_field', 0),
    		'content'                      => $content,
    	);

    	return atbdp_return_shortcode_template( 'single-listing/listing-header', $args );
    }

    public function get_custom_field_type_value($field_id, $field_type, $field_details) {
    	switch ($field_type) {
    		case 'color':
    		$result = sprintf('<div class="atbd_field_type_color" style="background-color: %s;"></div>', $field_details);
    		break;

    		case 'date':
    		$result = date( get_option( 'date_format') , strtotime($field_details));
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
    			}
    			else {
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
    		$result = do_shortcode(wpautop($content));
    		break;
    	}

    	return $result;
    }

    public function get_custom_field_data() {
    	$result = array();

    	$id = $this->get_id();

    	$args = array(
    		'post_type' => ATBDP_CUSTOM_FIELD_POST_TYPE,
    		'posts_per_page' => -1,
    		'post_status' => 'publish',
		);
		
		$custom_fields = ATBDP_Cache_Helper::get_the_transient([
			'group'      => 'atbdp_custom_field_query',
			'name'       => 'atbdp_all_custom_fields',
			'query_args' => $args,
			'cache'      => apply_filters( 'atbdp_cache_atbdp_all_custom_fields', 1 ),
			'value'      => function( $data ) {
				return  new WP_Query( $data['query_args'] );
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
    		if ('form' != $fields){
    			$fields_id_with_cat = get_post_meta($custom_field_id, 'category_pass', true);
    			if (in_array($fields_id_with_cat, $category_ids)){
    				$has_field_details = get_post_meta($id, $custom_fields_post->ID, true);
    				if (!empty($has_field_details)){
    					$field_ids[] = $custom_field_id;
    				}
    			}
    		}
    		else {
    			$has_field_details = get_post_meta($id, $custom_fields_post->ID, true);
    			if (!empty($has_field_details)){
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

    public function render_shortcode_custom_fields() {

    	if ( !is_singular( ATBDP_POST_TYPE ) ) {
    		return;
    	}

    	$id                 = $this->get_id();
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

    	return atbdp_return_shortcode_template( 'single-listing/custom-field', $args );
    }

    public function render_shortcode_video() {
    	if ( !is_singular( ATBDP_POST_TYPE ) ) {
    		return;
    	}

    	$id      = $this->get_id();
    	$fm_plan = get_post_meta($id, '_fm_plans', true);

    	$args = array(
    		'listing'          => $this,
    		'enable_video_url' => get_directorist_option('atbd_video_url', 1),
    		'videourl'         => get_post_meta($id, '_videourl', true),
    		'plan_video'       => is_fee_manager_active() ? is_plan_allowed_listing_video($fm_plan) : true,
    		'video_label'      => get_directorist_option('atbd_video_title', __('Video', 'directorist')),
    	);

    	return atbdp_return_shortcode_template( 'single-listing/listing-video', $args );
    }

    public function render_shortcode_map() {
    	if ( !is_singular( ATBDP_POST_TYPE ) ) {
    		return;
    	}

    	$id      = $this->get_id();
    	$fm_plan = get_post_meta($id, '_fm_plans', true);

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
    	$img_url = !empty($listing_prv_imgurl)?$listing_prv_imgurl:$default_image;
    	$image = "<img src=". $img_url.">";
    	if(empty($display_image_map)) {
    		$image = '';
    	}

    	$t = get_the_title();
    	$t = !empty($t) ? $t : __('No Title', 'directorist');
    	if(empty($display_title_map)) {
    		$t = '';
    	}

    	$info_content = "";
    	if(!empty($display_image_map) || !empty($display_title_map)) {
    		$info_content .= "<div class='map-info-wrapper'><div class='map-info-img'>$image</div><div class='map-info-details'><div class='atbdp-listings-title-block'><h3>$t</h3></div>";
    	}
    	if(!empty($display_address_map) && !empty($ad)) {
    		$info_content .= apply_filters("atbdp_address_in_map_info_window", "<address>{$ad}</address>");
    	}
    	if(!empty($display_direction_map)) {
    		$info_content .= "<div class='map_get_dir'><a href='http://www.google.com/maps?daddr={$manual_lat},{$manual_lng}' target='_blank'> " . __('Get Direction', 'directorist') . "</a></div><span class='iw-close-btn'><i class='la la-times'></i></span></div></div>";
    	}

    	$cats = get_the_terms(get_the_ID(), ATBDP_CATEGORY);
    	if(!empty($cats)){
    		$cat_icon = get_cat_icon($cats[0]->term_id);
    	}
    	$cat_icon = !empty($cat_icon) ? $cat_icon : 'fa-map-marker';
    	$icon_type = substr($cat_icon, 0,2);
    	$fa_or_la = ('la' == $icon_type) ? "la " : "fa ";
    	$cat_icon = ('none' == $cat_icon) ? 'fa fa-map-marker' : $fa_or_la . $cat_icon ;

    	$args = array(
    		'listing'               => $this,
    		'disable_map'           => get_directorist_option('disable_map', 0),
    		'hide_map'              => get_post_meta($id, '_hide_map', true),
    		'default_latitude'      => get_directorist_option('default_latitude', '40.7127753'),
    		'default_longitude'     => get_directorist_option('default_longitude', '-74.0059728'),
    		'manual_lat'            => $manual_lat,
    		'manual_lng'            => $manual_lng,
    		'display_map_field'     => apply_filters('atbdp_show_single_listing_map', get_directorist_option('display_map_field', 1)),
    		'listing_location_text' => apply_filters('atbdp_single_listing_map_section_text', get_directorist_option('listing_location_text', __('Location', 'directorist'))),
    		'select_listing_map'    => get_directorist_option('select_listing_map', 'google'),
    		'info_content'          => $info_content,
    		'display_map_info'      => $display_map_info,
    		'map_zoom_level'        => get_directorist_option('map_zoom_level', 16),
    		'cat_icon'              => $cat_icon,
    	);

    	$args['show_map'] = ( ! $args['disable_map'] && (empty($args['hide_map'])) && !empty($args['manual_lng'] || $args['manual_lat']) && !empty($args['display_map_field']) ) ? true : false;

    	if ( $args['show_map'] && 'openstreet' === $args['select_listing_map'] ) {
    		wp_localize_script( 'atbdp-single-listing-osm', 'localized_data', $args );
    		wp_enqueue_script( 'atbdp-single-listing-osm' );
    	}

    	if ( $args['show_map'] && 'google' === $args['select_listing_map'] ) {
    		wp_localize_script( 'atbdp-single-listing-gmap', 'localized_data', $args );
    		wp_enqueue_script( 'atbdp-single-listing-gmap' );
    	}

    	return atbdp_return_shortcode_template( 'single-listing/listing-map', $args );
    }

    public function render_shortcode_contact_information() {
    	if ( !is_singular( ATBDP_POST_TYPE ) ) {
    		return;
    	}

    	$id      = $this->get_id();
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
    		'address_html'              => !empty($address_map_link)?'<a target="google_map" href="https://www.google.de/maps/search/?'.esc_html($address).'">'.esc_html($address).'</a>': esc_html($address),
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

    	return atbdp_return_shortcode_template( 'single-listing/contact-information', $args );
    }

    public function render_shortcode_author_info() {
    	if ( !is_singular( ATBDP_POST_TYPE ) ) {
    		return;
    	}

    	$id      = $this->get_id();
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

    	return atbdp_return_shortcode_template( 'single-listing/author-details', $args );
    }

    public function render_shortcode_contact_owner() {
    	if ( !is_singular( ATBDP_POST_TYPE ) ) {
    		return;
    	}

    	$id      = $this->get_id();
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

    	return atbdp_return_shortcode_template( 'single-listing/contact-owner', $args );
    }

    public function render_shortcode_tags() {
    	if ( !is_singular( ATBDP_POST_TYPE ) ) {
    		return;
    	}

    	$id = $this->get_id();

    	$args = array(
    		'listing'            => $this,
    		'listing_id'         => $id,
    		'tags'               => get_the_terms($id, ATBDP_TAGS),
    		'enable_single_tag'  => get_directorist_option('enable_single_tag', 1),
    		'tags_section_lable' => get_directorist_option('tags_section_lable', __('Tags', 'directorist')),
    	);

    	return atbdp_return_shortcode_template( 'single-listing/tags', $args );
    }

    public function get_reviewer_img() {
    	$author_id = wp_get_current_user()->ID;
    	$u_pro_pic = get_user_meta($author_id, 'pro_pic', true);
    	$u_pro_pic = !empty($u_pro_pic) ? wp_get_attachment_image_src($u_pro_pic, 'thumbnail') : '';
    	$u_pro_pic = is_array($u_pro_pic) ? $u_pro_pic[0] : $u_pro_pic;
    	$custom_gravatar = "<img src='$u_pro_pic' alt='Author'>";
    	$avatar_img = get_avatar($author_id, apply_filters('atbdp_avatar_size', 32));
    	$user_img = !empty($u_pro_pic) ? $custom_gravatar : $avatar_img;
    	return $user_img;
    }

    public function render_shortcode_listing_review() {
    	if ( !is_singular( ATBDP_POST_TYPE ) ) {
    		return;
    	}

    	$id           = $this->get_id();
    	$fm_plan      = get_post_meta($id, '_fm_plans', true);
    	$review_count = ATBDP()->review->db->count(array('post_id' => $id));
    	$author_id    = get_post_field('post_author', $id);

    	$args = array(
    		'listing'                  => $this,
    		'author_id'                => get_post_field('post_author', $id),
    		'enable_review'            => get_directorist_option('enable_review', 1),
    		'enable_owner_review'      => get_directorist_option('enable_owner_review'),
    		'plan_review'              => is_fee_manager_active() ? is_plan_allowed_listing_review($fm_plan) : true,
    		'allow_review'             => apply_filters('atbdp_single_listing_before_review_block', true),
    		'review_count'             => $review_count,
    		'review_count_text'        => _nx( 'Review', 'Reviews', $review_count, 'Number of reviews', 'directorist' ),
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

    	return atbdp_return_shortcode_template( 'single-listing/listing-review', $args );
    }

    public function related_listings_query() {
    	$id = get_the_ID();
    	$rel_listing_num = get_directorist_option('rel_listing_num', 2);
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
    	$relationship = get_directorist_option('rel_listings_logic','OR');
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
    		'posts_per_page' => (int)$rel_listing_num,
    		'post__not_in' => array($id),
    	);

    	$meta_queries = array();
		$meta_queries['expired'] = array(
			array(
				'key'     => '_listing_status',
				'value'   => 'expired',
				'compare' => '!=',
			),
		);

    	$meta_queries = apply_filters('atbdp_related_listings_meta_queries', $meta_queries);
    	$count_meta_queries = count($meta_queries);
    	if ($count_meta_queries) {
    		$args['meta_query'] = ($count_meta_queries > 1) ? array_merge(array('relation' => 'AND'), $meta_queries) : $meta_queries;
    	}

    	return apply_filters( 'atbdp_related_listing_args', $args );
    }

    public function render_shortcode_related_listings() {
    	if ( !is_singular( ATBDP_POST_TYPE ) ) {
    		return;
    	}

    	$enabled = get_directorist_option('enable_rel_listing', 1);
    	if ( empty( $enabled ) ) {
    		return;
    	}

    	$is_rtl = is_rtl() ? true : false;
    	$columns = get_directorist_option('rel_listing_column', 3);

    	$localized_data = array(
    		'is_rtl' => $is_rtl,
    		'rel_listing_column' => $columns,
    	);
    	
    	wp_enqueue_script('atbdp-related-listings-slider');
    	wp_localize_script( 'atbdp-related-listings-slider', 'data', $localized_data );

    	$query = $this->related_listings_query();
    	
    	$listings = new Directorist_Listings(array(), 'related', $query, ['cache' => false]);
    	$args = array(
    		'listings' => $listings,
    		'class'    => is_directoria_active() ? 'containere' : 'containess-fluid',
    		'title'    => get_directorist_option('rel_listing_title', __('Related Listings', 'directorist')),
    	);

    	return atbdp_return_shortcode_template( 'single-listing/related-listings', $args );
    }
}