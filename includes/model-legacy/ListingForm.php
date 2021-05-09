<?php
/**
 * @author wpWax
 */

namespace Directorist;

use AazzTech\Directorist\Elementor\Directorist_Tag;
use \ATBDP_Permalink;

if ( ! defined( 'ABSPATH' ) ) exit;

class Directorist_Listing_Form {

	protected static $instance = null;

	public $add_listing_id;
	public $add_listing_post;
	public $directory_type;
	public $default_directory_type;
	public $current_listing_type;

	private function __construct( $id ) {
		if ( $id ) {
			$this->add_listing_id = $id;
			$this->add_listing_post = get_post( $id );

		}
		else {
			add_action( 'wp', array( $this, 'init' ) );
		}
		$this->current_listing_type = $this->get_current_listing_type();

	}

	public static function instance( $id = '' ) {
		if ( null == self::$instance ) {
			self::$instance = new self( $id );
		}
		return self::$instance;
	}

	public function init() {
		$this->add_listing_id   = get_query_var( 'atbdp_listing_id', 0 );
		$this->add_listing_post = ! empty( $this->add_listing_id ) ? get_post( $this->add_listing_id ) : '';
	}
	
	public function get_add_listing_id() {
		return $this->add_listing_id;
	}

	public function get_add_listing_post() {
		return $this->add_listing_post;
	}

	public function get_custom_fields_query() {
		$p_id    = $this->get_add_listing_id();
		$fm_plan = get_post_meta( $p_id, '_fm_plans', true );

		$custom_fields      = array(
			'post_type'      => ATBDP_CUSTOM_FIELD_POST_TYPE,
			'posts_per_page' => -1,
			'post_status'    => 'publish',
		);
		$meta_queries       = array();
		$meta_queries[]     = array(
			'key'     => 'associate',
			'value'   => 'form',
			'compare' => 'LIKE',
		);
		$meta_queries[]     = array(
			array(
				'relation' => 'OR',
				array(
					'key'     => 'admin_use',
					'compare' => 'NOT EXISTS',
				),
				array(
					'key'     => 'admin_use',
					'value'   => 1,
					'compare' => '!=',
				),
			),
		);
		$meta_queries       = apply_filters( 'atbdp_custom_fields_meta_queries', $meta_queries );
		$count_meta_queries = count( $meta_queries );
		if ( $count_meta_queries ) {
			$custom_fields['meta_query'] = ( $count_meta_queries > 1 ) ? array_merge( array( 'relation' => 'AND' ), $meta_queries ) : $meta_queries;
		}
		$custom_fields     = new \WP_Query( $custom_fields );
		$plan_custom_field = true;
		if ( is_fee_manager_active() ) {
			$plan_custom_field = is_plan_allowed_custom_fields( $fm_plan );
		}
		if ( $plan_custom_field ) {
			$fields = $custom_fields->posts;
		} else {
			$fields = array();
		}

		return $fields;
	}

	public function get_custom_field_input( $id, $value ) {
		$post_id        = $id;
		$cf_meta_val    = get_post_meta( $id, 'type', true );
		$cf_rows        = get_post_meta( $id, 'rows', true );
		$cf_placeholder = '';

		ob_start();

		switch ( $cf_meta_val ) {
			case 'text':
				echo '<div>';
				printf( '<input type="text" name="custom_field[%d]" class="form-control directory_field" placeholder="%s" value="%s"/>', $id, $cf_placeholder, $value );
				echo '</div>';
				break;
			case 'number':
				echo '<div>';
				printf( '<input type="number" %s  name="custom_field[%d]" class="form-control directory_field" placeholder="%s" value="%s"/>', ! empty( $allow_decimal ) ? 'step="any"' : '', $id, $cf_placeholder, $value );
				echo '</div>';
				break;
			case 'textarea':
				echo '<div>';
				$row = ( (int) $cf_rows > 0 ) ? (int) $cf_rows : 1;
				printf( '<textarea  class="form-control directory_field" name="custom_field[%d]" rows="%d" placeholder="%s">%s</textarea>', $id, $row, esc_attr( $cf_placeholder ), esc_textarea( $value ) );
				echo '</div>';
				break;
			case 'radio':
				echo '<div>';
				$choices = get_post_meta( $id, 'choices', true );
				$choices = explode( "\n", $choices );
				echo '<ul class="atbdp-radio-list vertical">';
				foreach ( $choices as $choice ) {
					if ( strpos( $choice, ':' ) !== false ) {
						$_choice = explode( ':', $choice );
						$_choice = array_map( 'trim', $_choice );

						$_value = $_choice[0];
						$_label = $_choice[1];
					} else {
						$_value = trim( $choice );
						$_label = $_value;
					}
					$_checked = '';
					if ( trim( $value ) == $_value ) {
						$_checked = ' checked="checked"';
					}

					printf( '<li><label><input type="radio" name="custom_field[%d]" value="%s"%s>%s</label></li>', $id, $_value, $_checked, $_label );
				}
				echo '</ul>';
				echo '</div>';
				break;

			case 'select':
				echo '<div>';
				$choices = get_post_meta( $id, 'choices', true );
				$choices = explode( "\n", $choices );
				printf( '<select name="custom_field[%d]" class="form-control directory_field">', $id );
				if ( ! empty( $field_meta['allow_null'][0] ) ) {
					printf( '<option value="">%s</option>', '- ' . __( 'Select an Option', 'directorist' ) . ' -' );
				}
				foreach ( $choices as $choice ) {
					if ( strpos( $choice, ':' ) !== false ) {
						$_choice = explode( ':', $choice );
						$_choice = array_map( 'trim', $_choice );

						$_value = $_choice[0];
						$_label = $_choice[1];
					} else {
						$_value = trim( $choice );
						$_label = $_value;
					}

					$_selected = '';
					if ( trim( $value ) == $_value ) {
						$_selected = ' selected="selected"';
					}

					printf( '<option value="%s"%s>%s</option>', $_value, $_selected, $_label );
				}
				echo '</select>';
				echo '</div>';
				break;

			case 'checkbox':
				echo '<div>';
				$choices = get_post_meta( $id, 'choices', true );
				$choices = explode( "\n", $choices );

				$values = explode( "\n", $value );
				$values = array_map( 'trim', $values );
				echo '<ul class="atbdp-checkbox-list vertical">';

				foreach ( $choices as $choice ) {
					if ( strpos( $choice, ':' ) !== false ) {
						$_choice = explode( ':', $choice );
						$_choice = array_map( 'trim', $_choice );

						$_value = $_choice[0];
						$_label = $_choice[1];
					} else {
						$_value = trim( $choice );
						$_label = $_value;
					}

					$_checked = '';
					if ( in_array( $_value, $values ) ) {
						$_checked = ' checked="checked"';
					}

					printf( '<li><label><input type="hidden" name="custom_field[%s][]" value="" /><input type="checkbox" name="custom_field[%d][]" value="%s"%s> %s</label></li>', $id, $id, $_value, $_checked, $_label );
				}
				echo '</ul>';
				echo '</div>';
				break;
			case 'url':
				echo '<div>';
				printf( '<input type="text" name="custom_field[%d]" class="form-control directory_field" placeholder="%s" value="%s"/>', $id, esc_attr( $cf_placeholder ), esc_url( $value ) );
				echo '</div>';
				break;

			case 'date':
				echo '<div>';
				printf( '<input type="date" name="custom_field[%d]" class="form-control directory_field" value="%s"/>', $id, esc_attr( $value ) );
				echo '</div>';
				break;

			case 'email':
				echo '<div>';
				printf( '<input type="email" name="custom_field[%d]" class="form-control directory_field" placeholder="%s" value="%s"/>', $id, esc_attr( $cf_placeholder ), esc_attr( $value ) );
				echo '</div>';
				break;
			case 'color':
				echo '<div>';
				printf( '<input type="text" name="custom_field[%d]" id="color_code2" class="my-color-field" value="%s"/>', $id, $value );
				echo '</div>';
				break;

			case 'time':
				echo '<div>';
				printf( '<input type="time" name="custom_field[%d]" class="form-control directory_field" value="%s"/>', $id, esc_attr( $value ) );
				echo '</div>';
				break;
			case 'file':
				require ATBDP_VIEWS_DIR . 'file-uploader.php';
				break;
		}

		return ob_get_clean();
	}

	public function get_plan_video() {
		$p_id    = $this->get_add_listing_id();
		$fm_plan = get_post_meta( $p_id, '_fm_plans', true );

		$display_video_for   = get_directorist_option( 'display_video_for', 0 );
		$display_video_field = get_directorist_option( 'display_video_field', 1 );

		$plan_video = false;

		if ( is_fee_manager_active() ) {
			$plan_video = is_plan_allowed_listing_video( $fm_plan );
		} elseif ( empty( $display_video_for ) && ! empty( $display_video_field ) ) {
			$plan_video = true;
		}

		return $plan_video;
	}

	public function get_plan_slider() {
		$p_id    = $this->get_add_listing_id();
		$fm_plan = get_post_meta( $p_id, '_fm_plans', true );

		$display_glr_img_for   = get_directorist_option( 'display_glr_img_for', 0 );
		$display_gallery_field = get_directorist_option( 'display_gallery_field', 1 );

		$plan_slider = false;

		if ( is_fee_manager_active() ) {
			$plan_slider = is_plan_allowed_slider( $fm_plan );
		} elseif ( empty( $display_glr_img_for ) && ! empty( $display_gallery_field ) ) {
			$plan_slider = true;
		}

		return $plan_slider;
	}

	public function get_map_info_content() {
		$p_id = $this->get_add_listing_id();

		$tagline     = get_post_meta( $p_id, '_tagline', true );
		$address     = get_post_meta( $p_id, '_address', true );
		$listing_img = atbdp_get_listing_attachment_ids( $p_id );
		$t           = get_the_title();
		$t           = ! empty( $t ) ? esc_html( $t ) : __( 'No Title ', 'directorist' );
		$tg          = ! empty( $tagline ) ? esc_html( $tagline ) : '';
		$ad          = ! empty( $address ) ? esc_html( $address ) : '';
		$image       = ( ! empty( $listing_img[0] ) ) ? "<img src='" . esc_url( wp_get_attachment_image_url( $listing_img[0], 'thumbnail' ) ) . "'>" : '';
		/*build the markup for google map info window*/
		$info_content  = "<div class='map_info_window'> <h3> {$t} </h3>";
		$info_content .= "<p> {$tg} </p>";
		$info_content .= $image; // add the image if available
		$info_content .= "<p> {$ad}</p></div>";
		return $info_content;
	}

	public function get_listing_info() {
		$listing_info = array();

		$p_id = $this->get_add_listing_id();

		if ( ! empty( $p_id ) ) {
			$listing_info['never_expire']            = get_post_meta( $p_id, '_never_expire', true );
			$listing_info['featured']                = get_post_meta( $p_id, '_featured', true );
			$listing_info['listing_type']            = get_post_meta( $p_id, '_listing_type', true );
			$listing_info['price']                   = get_post_meta( $p_id, '_price', true );
			$listing_info['videourl']                = get_post_meta( $p_id, '_videourl', true );
			$listing_info['price_range']             = get_post_meta( $p_id, '_price_range', true );
			$listing_info['atbd_listing_pricing']    = get_post_meta( $p_id, '_atbd_listing_pricing', true );
			$listing_info['listing_status']          = get_post_meta( $p_id, '_listing_status', true );
			$listing_info['tagline']                 = get_post_meta( $p_id, '_tagline', true );
			$listing_info['atbdp_post_views_count']  = get_post_meta( $p_id, '_atbdp_post_views_count', true );
			$listing_info['excerpt']                 = get_post_meta( $p_id, '_excerpt', true );
			$listing_info['address']                 = get_post_meta( $p_id, '_address', true );
			$listing_info['phone']                   = get_post_meta( $p_id, '_phone', true );
			$listing_info['phone2']                  = get_post_meta( $p_id, '_phone2', true );
			$listing_info['fax']                     = get_post_meta( $p_id, '_fax', true );
			$listing_info['email']                   = get_post_meta( $p_id, '_email', true );
			$listing_info['website']                 = get_post_meta( $p_id, '_website', true );
			$listing_info['zip']                     = get_post_meta( $p_id, '_zip', true );
			$listing_info['social']                  = get_post_meta( $p_id, '_social', true );
			$listing_info['faqs']                    = get_post_meta( $p_id, '_faqs', true );
			$listing_info['manual_lat']              = get_post_meta( $p_id, '_manual_lat', true );
			$listing_info['manual_lng']              = get_post_meta( $p_id, '_manual_lng', true );
			$listing_info['hide_map']                = get_post_meta( $p_id, '_hide_map', true );
			$listing_info['bdbh']                    = get_post_meta( $p_id, '_bdbh', true );
			$listing_info['enable247hour']           = get_post_meta( $p_id, '_enable247hour', true );
			$listing_info['disable_bz_hour_listing'] = get_post_meta( $p_id, '_disable_bz_hour_listing', true );
			$listing_info['bdbh_version'] 			 = get_post_meta( $p_id, '_bdbh_version', true );
			$listing_info['hide_contact_info']       = get_post_meta( $p_id, '_hide_contact_info', true );
			$listing_info['hide_contact_owner']      = get_post_meta( $p_id, '_hide_contact_owner', true );
			$listing_info['expiry_date']             = get_post_meta( $p_id, '_expiry_date', true );
			$listing_info['t_c_check']               = get_post_meta( $p_id, '_t_c_check', true );
			$listing_info['privacy_policy']          = get_post_meta( $p_id, '_privacy_policy', true );
			$listing_info['id_itself']               = $p_id;
		}

		return $listing_info;
	}

	public function add_listing_location_fields() {
		$terms = get_the_terms( $this->add_listing_id, ATBDP_LOCATION );
		$ids   = array();
		if ( ! empty( $terms ) ) {
			foreach ( $terms as $term ) {
				$ids[] = $term->term_id;
			}
		}

		$query_args = array(
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

		$location_fields = add_listing_category_location_filter( $this->current_listing_type, $query_args, ATBDP_LOCATION, $ids );
		return $location_fields;
	}

	public function add_listing_cat_ids() {
		$p_id  = $this->add_listing_id;
		$terms = get_the_terms( $p_id, ATBDP_CATEGORY );
		$ids   = array();
		if ( ! empty( $terms ) ) {
			foreach ( $terms as $term ) {
				$ids[] = $term->term_id;
			}
		}
		return $ids;
	}

	public function add_listing_tag_ids() {
		$p_id    = $this->add_listing_id;
		$tag_ids = array();
		$p_tags  = wp_get_post_terms( $p_id, ATBDP_TAGS );
		if ( ! empty( $p_tags ) ) {
			foreach ( $p_tags as $p_tag ) {
				$tag_ids[] = $p_tag->term_id;
			}
		}
		return $tag_ids;
	}

	public function add_listing_cat_fields() {
		$p_id     = $this->add_listing_id;
		$fm_plan  = get_post_meta( $p_id, '_fm_plans', true );
		$plan_cat = is_fee_manager_active() ? is_plan_allowed_category( $fm_plan ) : array();
		$ids      = $this->add_listing_cat_ids();

		$query_args = array(
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

		$categories_field = add_listing_category_location_filter( $this->current_listing_type, $query_args, ATBDP_CATEGORY, $ids, '', $plan_cat );
		return $categories_field;
	}

	public function add_listing_required_html() {
		$required_html = '<span class="atbdp_make_str_red"> *</span>';
		return $required_html;
	}

	public function add_listing_generate_label( $label, $required ) {
		$required_html = $this->add_listing_required_html();
		return sprintf( '%s:%s', $label, $required ? $required_html : '' );
	}

	public function add_listing_has_contact_info( $args ) {
		extract( $args );
		$result = ( empty( $display_fax_for ) || empty( $display_phone2_for ) || empty( $display_phone_for ) || empty( $display_address_for ) || empty( $display_email_for ) || empty( $display_website_for ) || empty( $display_zip_for ) || empty( $display_social_info_for ) ) && ( ! empty( $display_address_field ) || ! empty( $display_phone_field ) || ! empty( $display_phone2_field ) || ! empty( $display_fax_field ) || ! empty( $display_email_field ) || ! empty( $display_website_field ) || ! empty( $display_zip_field ) || ! empty( $display_social_info_field ) ) ? true : false;
		return $result;
	}

	public function generate_linktext( $text, $link ) {
		$pattern = '%\%(.+)\%%'; // extract 'text' from 'some %text%'
		preg_match( $pattern, $text, $matches );

		if ( !empty( $matches ) ) {
			$changed = sprintf( '<a target="_blank" href="%s">%s</a>', $link, $matches[1] );
			$result = str_replace( $matches[0], $changed, $text );
			return $result;
		}

		return $text;
	}

	public function featured_listing_description() {
		$description = get_directorist_option('featured_listing_desc', '(Top of the search result and listings pages for a number days and it requires an additional payment.)');
		return $description;

	}

	public function add_listing_submit_template() {
		$p_id              = $this->get_add_listing_id();
		$type = $this->get_current_listing_type();
		$guest_email_label = get_directorist_type_option( $type, 'guest_email_label', __( 'Your Email', 'directorist' ) );

		$privacy_link =  ATBDP_Permalink::get_privacy_policy_page_url();
		$terms_link   =  ATBDP_Permalink::get_terms_and_conditions_page_url();

		$privacy_label = get_directorist_type_option( $type, 'privacy_label', __( 'I agree to the %Privacy & Policy%', 'directorist' ) );
		$terms_label   = get_directorist_type_option( $type, 'terms_label', __( 'I agree with all %terms & conditions%', 'directorist' ) );

		$args = array(
			'form'                    => $this,
			'p_id'                    => $p_id,
			'display_guest_listings'  => get_directorist_option( 'guest_listings', 0 ),
			'guest_email_label_html'  => $this->add_listing_generate_label( $guest_email_label, true ),
			'guest_email_placeholder' => get_directorist_type_option( $type, 'guest_email_placeholder' ),

			'display_privacy'         => (bool) get_directorist_type_option( $type, 'listing_privacy', 1 ),
			'privacy_is_required'     => get_directorist_type_option( $type, 'require_privacy', 1 ),
			'privacy_checked'         => (bool) get_post_meta( $p_id, '_privacy_policy', true ),
			'privacy_text'            => $this->generate_linktext( $privacy_label, $privacy_link ),

			'display_terms'           => (bool) get_directorist_type_option( $type, 'listing_terms_condition', 1 ),
			'terms_is_required'       => get_directorist_type_option( $type, 'require_terms_conditions', 1 ),
			'terms_checked'           => (bool) get_post_meta( $p_id, '_t_c_check', true ),
			'terms_text'              => $this->generate_linktext( $terms_label, $terms_link ),

			'submit_label'            => get_directorist_type_option( $type, 'submit_button_label', __( 'Save & Preview', 'directorist' ) ),
		);

		Helper::get_template( 'forms/add-listing-submit', apply_filters( 'atbdp_add_listing_submission_template_args', $args ) );
	}

	public function add_listing_custom_fields_template() {
		$fields = $this->get_custom_fields_query();
		$p_id   = $this->get_add_listing_id();

		foreach ( $fields as $post ) {
			$id    = $post->ID;
			$value = get_post_meta( $p_id, $id, true );

			$args = array(
				'form'         => $this,
				'title'        => get_the_title( $id ),
				'cf_required'  => get_post_meta( $id, 'required', true ),
				'instructions' => get_post_meta( $id, 'instructions', true ),
				'input_field'  => $this->get_custom_field_input( $id, $value ),
			);

			Helper::get_template( 'forms/add-listing-custom-fields', $args );
		}
	}

	public function add_listing_type_template() {
		$all_types     = get_terms(array(
			'taxonomy'   => ATBDP_TYPE,
			'hide_empty' => false,
		));

		$value             = get_post_meta( $this->get_add_listing_id(), '_directory_type', true );
		$default_directory = default_directory_type();
		$current_type      = ! empty( $value ) ? $value : $default_directory;
		
		$args = array(
			'listing_form'  => $this,
			'listing_types' => $all_types,
			'current_type'  => $current_type,
		);
		
		Helper::get_template( 'forms/fields/type', $args );
	}

	public function add_listing_label_template( $data, $label_id = '' ) {
		$key = !empty( $data['field_key'] ) ? $data['field_key'] : 'random-'.rand();
		$args = array(
			'form'     => $this,
			'data'     => $data,
			'label_id' => $label_id ? $label_id : $key,
		);
		Helper::get_template( 'forms/add-listing-field-label', $args );
	}

	public function add_listing_description_template( $data ) {
		$args = array(
			'form'  => $this,
			'data'  => $data,
		);
		Helper::get_template( 'forms/add-listing-field-des', $args );
	}
  
	public function section_template( $section_data ) {
		$args = array(
			'form'          => $this,
			'section_data'  => $section_data,
		);
		$load_section = apply_filters( 'directorist_section_template', true, $args );
		if( $load_section ) {
			Helper::get_template( 'forms/add-listing-section', $args );
		}
	}


	public function add_listing_category_custom_field_template( $field_data, $listing_id = NULL ) {
		$value = '';
		if ( ! empty( $listing_id ) ) {

			$value = get_post_meta( $listing_id, '_'.$field_data['field_key'], true );
	
		}
		$field_data['value'] = $value;
		$field_data['form'] = $this;
		
		$args = array(
			'form'  => $this,
			'data'  => $field_data,
		);
		
		$template = 'forms/fields/' . $field_data['widget_name'];
		
		$template = apply_filters( 'directorist_field_template', $template, $field_data );
		
		if ( is_admin() && empty( $field_data['request_from_no_admin'] ) ) {
			$admin_template = 'listing-form/' . $field_data['widget_name'];
			$admin_template = apply_filters( 'directorist_field_admin_template', $admin_template, $field_data );

			if ( atbdp_has_admin_template( $admin_template ) ) {
				atbdp_get_admin_template( $admin_template, $args );
			}
			else {
				Helper::get_template( $template, $args );
			}
		}
		else {
			if ( empty( $field_data['only_for_admin'] ) ) {
				Helper::get_template( $template, $args );
			}
		}
		
	}

	public function field_template( $field_data ) {

		if( !empty( $field_data['assign_to'] ) && ( $field_data['assign_to'] !== 'form' ) ) return;
		$listing_id = $this->get_add_listing_id();
		$value = '';
		
		if ( ! empty( $listing_id ) ) {
			if ( $field_data['widget_name'] == 'title' ) {
				$value = $this->add_listing_post->post_title;
			}
			elseif ( $field_data['widget_name'] == 'description' ) {
				$value = $this->add_listing_post->post_content;
			}
			elseif ( !empty( $field_data['field_key'] ) ) {
				$value = get_post_meta( $listing_id, '_'.$field_data['field_key'], true );

				if ( empty( $value ) ) {
					$value = get_post_meta( $listing_id, $field_data['field_key'], true );
				}
			}
		}
		
		
		$field_data['value'] = $value;
		$field_data['form'] = $this;
		$field_data = apply_filters( 'directorist_form_field_data', $field_data );

		if ( isset( $field_data['options'] ) && is_string( $field_data['options'] ) ) {
			$field_data['options'] = Helper::parse_input_field_options_string_to_array( $field_data['options'] );
		}

		$args = array(
			'form'  => $this,
			'data'  => $field_data,
		);
		
		$template = 'forms/fields/' . $field_data['widget_name'];

		$template = apply_filters( 'directorist_field_template', $template, $field_data );
		if ( is_admin() ) {
			$admin_template = 'listing-form/' . $field_data['widget_name'];
			$admin_template = apply_filters( 'directorist_field_admin_template', $admin_template, $field_data );

			if ( atbdp_has_admin_template( $admin_template ) ) {
				atbdp_get_admin_template( $admin_template, $args );
			}
			else {
				Helper::get_template( $template, $args );
			}
		} else {
			
			if ( empty( $field_data['only_for_admin'] ) ) {
				Helper::get_template( $template, $args );
			}
		}
		
	}

	public function get_listing_types() {
		$enable_multi_directory = get_directorist_option( 'enable_multi_directory' );
		$listing_types = array();
		$args = array(
			'taxonomy'   => ATBDP_TYPE,
			'hide_empty' => false,
		);
		if( $this->directory_type ) {
			$args['slug'] = $this->directory_type;
		}
		$all_types     = get_terms( $args );

		foreach ( $all_types as $type ) {
			if(  empty( $enable_multi_directory ) ) {
				$is_default = get_term_meta( $type->term_id, '_default', true );
				if ( $is_default ) {
					$listing_types[ $type->term_id ] = [
						'term' => $type,
						'name' => $type->name,
						'data' => get_term_meta( $type->term_id, 'general_config', true ),
					];
					break;
				}
			} else {	
				$listing_types[ $type->term_id ] = [
					'term' => $type,
					'name' => $type->name,
					'data' => get_term_meta( $type->term_id, 'general_config', true ),
				];
			}
		}
		return $listing_types;
	}

	public function get_current_listing_type() {
		$listing_types      = $this->get_listing_types();
		$listing_type_count = count( $listing_types );
		$get_listing_type   = get_post_meta( $this->add_listing_id, '_directory_type', true );

		if ( $listing_type_count == 1 ) {
			$type = array_key_first( $listing_types );
		}
		elseif( ! empty( $get_listing_type ) ) {
			$type = $get_listing_type;
		}
		else {
			$type = isset( $_GET['directory_type'] ) ? $_GET['directory_type'] : '';
		}
		if( !empty( $type ) && ! is_numeric( $type ) ) {
			$term = get_term_by( 'slug', $type, ATBDP_TYPE );
			$type = $term->term_id;
		}

		return (int) $type;
	}

	public function build_form_data( $type ) {
		$form_data = [];

		if ( !$type ) {
			return $form_data;
		}

		$submission_form_fields = get_term_meta( $type, 'submission_form_fields', true );

		if( ! empty( $submission_form_fields['groups'] ) ) {
			foreach ( $submission_form_fields['groups'] as $group ) {
				$section           = $group;
				$section['fields'] = array();
				foreach ( $group['fields'] as $field ) {
					$section['fields'][ $field ] = $submission_form_fields['fields'][ $field ];
				}
				$form_data[] = $section;
	
			}
		}
		return $form_data;
	}

	public function get_map_data() {
		$p_id = $this->get_add_listing_id();

		$data = array(
			'p_id'               => $p_id,
			'listing_form'       => $this,
			'listing_info'       => $this->get_listing_info(),
			'select_listing_map' => get_directorist_option( 'select_listing_map', 'google' ),
			'display_map_for'    => get_directorist_option( 'display_map_for', 0 ),
			'display_map_field'  => get_directorist_option( 'display_map_field', 1 ),
			'manual_lat'         => get_post_meta( $p_id, '_manual_lat', true ),
			'manual_lng'         => get_post_meta( $p_id, '_manual_lng', true ),
			'default_latitude'   => get_directorist_option( 'default_latitude', '40.7127753', true ),
			'default_longitude'  => get_directorist_option( 'default_longitude', '-74.0059728', true ),
			'info_content'       => $this->get_map_info_content(),
			'map_zoom_level'     => get_directorist_option( 'map_zoom_level', 4 ),
			'marker_title'       => __( 'You can drag the marker to your desired place to place a marker', 'directorist' ),
			'geocode_error_msg'  => __( 'Geocode was not successful for the following reason: ', 'directorist' ),
			'map_icon'           => ATBDP_PUBLIC_ASSETS . 'images/map-icon.png',
		);

		return $data;
	}

	public function render_shortcode( $atts ) {

		wp_enqueue_script( 'adminmainassets' );

		$guest_submission = get_directorist_option( 'guest_listings', 0 );
		$user_id		  = get_current_user_id();
		$user_type        = get_user_meta( $user_id, '_user_type', true );

		if ( ! $guest_submission && ! atbdp_logged_in_user() ) {
			return \ATBDP_Helper::guard( array( 'type' => 'auth' ) );
		} elseif( ! empty( $user_type ) && ( 'general' == $user_type || 'become_author' == $user_type ) ) {
			return \ATBDP_Helper::guard( array( 'type' => 'user_type' ) );
		}

		// Check if current user can access this page
		$p_id = $this->get_add_listing_id();

		if ( ! empty( $p_id ) ) {
			$listing = get_post( $p_id );
			if ( $listing->post_author != get_current_user_id() && ! current_user_can( 'edit_others_at_biz_dirs' ) ) {
				return Helper::get_template_contents( 'forms/add-listing-error' );
			}
		}

		$atts = shortcode_atts( array(
			'directory_type' => '',
		), $atts );

		$this->directory_type           = !empty( $atts['directory_type'] ) ? explode( ',', $atts['directory_type'] ) : '';
		$args = array(
			'p_id'               => $p_id,
			'listing_form'       => $this,
			'listing_info'       => $this->get_listing_info(),
			'select_listing_map' => get_directorist_option( 'select_listing_map', 'google' ),
			'display_map_for'    => get_directorist_option( 'display_map_for', 0 ),
			'display_map_field'  => get_directorist_option( 'display_map_field', 1 ),
			'manual_lat'         => get_post_meta( $p_id, '_manual_lat', true ),
			'manual_lng'         => get_post_meta( $p_id, '_manual_lng', true ),
			'default_latitude'   => get_directorist_option( 'default_latitude', '40.7127753' ),
			'default_longitude'  => get_directorist_option( 'default_longitude', '-74.0059728' ),
			'info_content'       => $this->get_map_info_content(),
			'map_zoom_level'     => get_directorist_option( 'map_zoom_level', 4 ),
			'marker_title'       => __( 'You can drag the marker to your desired place to place a marker', 'directorist' ),
			'geocode_error_msg'  => __( 'Geocode was not successful for the following reason: ', 'directorist' ),
			'map_icon'           => ATBDP_PUBLIC_ASSETS . 'images/map-icon.png',
		);

		$display_map = ( empty( $args['display_map_for'] ) && ! empty( $args['display_map_field'] ) ) ? true : false;

		if ( $display_map && 'openstreet' === $args['select_listing_map'] ) {
			wp_localize_script( 'atbdp-add-listing-osm', 'localized_data', $args );
			wp_enqueue_script( 'atbdp-add-listing-osm' );
		}

		if ( $display_map && 'google' === $args['select_listing_map'] ) {
			wp_localize_script( 'atbdp-add-listing-gmap', 'localized_data', $args );
			wp_enqueue_script( 'atbdp-add-listing-gmap' );
		}

		ATBDP()->enquirer->add_listing_scripts_styles();

		$listing_types      = $this->get_listing_types();
		$listing_type_count = count( $listing_types );

		if ( $p_id ) {
			$terms =  get_the_terms( $p_id, ATBDP_TYPE );
			$type  = !empty($terms) ? $terms[0]->term_id : '';
			$args['form_data'] = $this->build_form_data( $type );
			$args['is_edit_mode'] = true;
			return Helper::get_template_contents( 'forms/add-listing', $args );
		}
		else {
			// if no listing type exists
			if ( $listing_type_count == 0 ) {
				return Helper::get_template_contents( 'forms/add-listing-notype', $args );
			}
			// if only one directory
			$type = $this->get_current_listing_type();
			if ( $type ) {
				$args['form_data'] = $this->build_form_data( $type );
				$args['single_directory'] = $type;
				$template = Helper::get_template_contents( 'forms/add-listing', $args );
				return apply_filters( 'atbdp_add_listing_page_template', $template, $args );
			}

			
			// multiple directory available
			$listing_type_args = array(
				'listing_types' => $listing_types,
			);
			$template = Helper::get_template_contents( 'forms/add-listing-type', $listing_type_args );
			return apply_filters( 'atbdp_add_listing_page_template', $template, $args );
		}
	}
	
	public function render_shortcode_user_login() {
		if ( atbdp_logged_in_user() ) {

			do_action( 'atbdp_show_flush_messages' );

			$error_message = sprintf( __( 'Login page is not for logged-in user. <a href="%s">Go to Dashboard</a>', 'directorist' ), esc_url( ATBDP_Permalink::get_dashboard_page_link() ) );
			ob_start();
			ATBDP()->helper->show_login_message( apply_filters( 'atbdp_login_page_loggedIn_msg', $error_message ) );
			return ob_get_clean();
		}

		return Helper::get_template_contents( 'forms/login' );
	}

	public function render_shortcode_custom_registration( $atts ) {
		if ( ! atbdp_logged_in_user() ) {
			$atts = shortcode_atts( array(
				'user_type'			  => '',
			), $atts );
			$user_type           = !empty( $atts['user_type'] ) ? $atts['user_type'] : '';
			$args = array(
				'parent'               => 0,
				'container_fluid'      => is_directoria_active() ? 'container' : 'container-fluid',
				'username'             => get_directorist_option( 'reg_username', 'Username' ),
				'password'             => get_directorist_option( 'reg_password', 'Password' ),
				'display_password_reg' => get_directorist_option( 'display_password_reg', 1 ),
				'require_password'     => get_directorist_option( 'require_password_reg', 1 ),
				'email'                => get_directorist_option( 'reg_email', 'Email' ),
				'display_website'      => get_directorist_option( 'display_website_reg', 0 ),
				'website'              => get_directorist_option( 'reg_website', 'Website' ),
				'require_website'      => get_directorist_option( 'require_website_reg', 0 ),
				'display_fname'        => get_directorist_option( 'display_fname_reg', 0 ),
				'first_name'           => get_directorist_option( 'reg_fname', 'First Name' ),
				'require_fname'        => get_directorist_option( 'require_fname_reg', 0 ),
				'display_lname'        => get_directorist_option( 'display_lname_reg', 0 ),
				'last_name'            => get_directorist_option( 'reg_lname', 'Last Name' ),
				'require_lname'        => get_directorist_option( 'require_lname_reg', 0 ),
				'display_bio'          => get_directorist_option( 'display_bio_reg', 0 ),
				'bio'                  => get_directorist_option( 'reg_bio', 'About/bio' ),
				'require_bio'          => get_directorist_option( 'require_bio_reg', 0 ),
				'reg_signup'           => get_directorist_option( 'reg_signup', 'Sign Up' ),
				'display_login'        => get_directorist_option( 'display_login', 1 ),
				'login_text'           => get_directorist_option( 'login_text', __( 'Already have an account? Please login', 'directorist' ) ),
				'login_url'            => ATBDP_Permalink::get_login_page_link(),
				'log_linkingmsg'       => get_directorist_option( 'log_linkingmsg', __( 'Here', 'directorist' ) ),
				'terms_label'          => get_directorist_option( 'regi_terms_label', __( 'I agree with all', 'directorist' ) ),
				'terms_label_link'     => get_directorist_option( 'regi_terms_label_link', __( 'terms & conditions', 'directorist' ) ),
				't_C_page_link'        => ATBDP_Permalink::get_terms_and_conditions_page_url(),
				'privacy_page_link'    => ATBDP_Permalink::get_privacy_policy_page_url(),
				'privacy_label'        => get_directorist_option( 'registration_privacy_label', __( 'I agree to the', 'directorist' ) ),
				'privacy_label_link'   => get_directorist_option( 'registration_privacy_label_link', __( 'Privacy & Policy', 'directorist' ) ),
				'user_type'			   => $user_type,
				'author_checked'	   => ( 'general' != $user_type ) ? 'checked' : '',
				'general_checked'	   => ( 'general' == $user_type ) ? 'checked' : ''
			);

			return Helper::get_template_contents( 'forms/registration', $args );
		}
		else {
			$error_message = sprintf( __( 'Registration page is only for unregistered user. <a href="%s">Go to Dashboard</a>', 'directorist' ), esc_url( ATBDP_Permalink::get_dashboard_page_link() ) );
			ob_start();
			ATBDP()->helper->show_login_message( apply_filters( 'atbdp_registration_page_registered_msg', $error_message ) );
			return ob_get_clean();
		}

		return '';
	}
}
