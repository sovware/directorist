<?php
/**
 * @author wpWax
 */

namespace Directorist;

use \ATBDP_Permalink;

if ( ! defined( 'ABSPATH' ) ) exit;

class Directorist_Listing_Form {

	protected static $instance = null;
	public static $directory_type = '';

	public $add_listing_id;
	public $add_listing_post;
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
		$listing_id = get_query_var( 'atbdp_listing_id', 0 );
		$listing_id = empty( $listing_id ) && ! empty( $_REQUEST['edit'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['edit'] ) ) : $listing_id;

		$this->add_listing_post = ! empty( $this->add_listing_id ) ? get_post( $this->add_listing_id ) : '';
	}

	public function get_add_listing_id() {
		return $this->add_listing_id;
	}

	public function get_add_listing_post() {
		return $this->add_listing_post;
	}

	public function load_color_picker_script( $data ) {
		if( !empty( $data['value'] ) ) {
			?>
			<script> jQuery(document).ready(function ($) { $('.directorist-color-field-js').wpColorPicker(); }); </script>
			<?php
		}
		else {
			?>
			<script> jQuery(document).ready(function ($) { $('.directorist-color-field-js').wpColorPicker().empty(); }); </script>
			<?php
		}
	}

	public function required( $data ) {
		echo ! empty( $data['required'] ) ? 'required="required"' : '';
	}

	/**
	 * Unused method
	 *
	 * @return array
	 */
	public function get_custom_fields_query() {
		_deprecated_function( __METHOD__, '7.4.3' );
		return array();
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
				printf( '<input type="text" name="custom_field[%d]" class="form-control directory_field" placeholder="%s" value="%s"/>', esc_attr( $id ), esc_attr( $cf_placeholder ), esc_attr( $value ) );
				echo '</div>';
				break;
			case 'number':
				echo '<div>';
				printf( '<input type="number" %s name="custom_field[%d]" class="form-control directory_field" placeholder="%s" value="%s" min="0"/>', ! empty( $allow_decimal ) ? 'step="any"' : '', esc_attr( $id ), esc_attr( $cf_placeholder ), esc_attr( $value ) );
				echo '</div>';
				break;
			case 'textarea':
				echo '<div>';
				$row = ( (int) $cf_rows > 0 ) ? (int) $cf_rows : 1;
				printf( '<textarea  class="form-control directory_field" name="custom_field[%d]" rows="%d" placeholder="%s">%s</textarea>', esc_attr( $id ), esc_attr( $row ), esc_attr( $cf_placeholder ), esc_textarea( $value ) );
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

					printf( '<li><label><input type="radio" name="custom_field[%d]" value="%s"%s>%s</label></li>', esc_attr( $id ), esc_attr( $_value ), esc_attr( $_checked ), esc_html( $_label ) );
				}
				echo '</ul>';
				echo '</div>';
				break;

			case 'select':
				echo '<div>';
				$choices = get_post_meta( $id, 'choices', true );
				$choices = explode( "\n", $choices );
				printf( '<select name="custom_field[%d]" class="form-control directory_field">', esc_attr( $id ) );
				if ( ! empty( $field_meta['allow_null'][0] ) ) {
					printf( '<option value="">%s</option>', esc_html__( '- Select an Option -', 'directorist' ) );
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

					printf( '<option value="%s"%s>%s</option>', esc_attr( $_value ), esc_attr( $_selected ), esc_html( $_label ) );
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

					printf( '<li><label><input type="hidden" name="custom_field[%s][]" value="" /><input type="checkbox" name="custom_field[%d][]" value="%s"%s> %s</label></li>', esc_attr( $id ), esc_attr( $id ), esc_attr( $_value ), esc_attr( $_checked ), esc_html( $_label ) );
				}
				echo '</ul>';
				echo '</div>';
				break;
			case 'url':
				echo '<div>';
				printf( '<input type="text" name="custom_field[%d]" class="form-control directory_field" placeholder="%s" value="%s"/>', esc_attr( $id ), esc_attr( $cf_placeholder ), esc_url( $value ) );
				echo '</div>';
				break;

			case 'date':
				echo '<div>';
				printf( '<input type="date" name="custom_field[%d]" class="form-control directory_field" value="%s"/>', esc_attr( $id ), esc_attr( $value ) );
				echo '</div>';
				break;

			case 'email':
				echo '<div>';
				printf( '<input type="email" name="custom_field[%d]" class="form-control directory_field" placeholder="%s" value="%s"/>', esc_attr( $id ), esc_attr( $cf_placeholder ), esc_attr( $value ) );
				echo '</div>';
				break;
			case 'color':
				echo '<div>';
				printf( '<input type="text" name="custom_field[%d]" class="directorist-color-field-js" value="%s"/>', esc_attr( $id ), esc_attr( $value ) );
				echo '</div>';
				break;

			case 'time':
				echo '<div>';
				printf( '<input type="time" name="custom_field[%d]" class="form-control directory_field" value="%s"/>', esc_attr( $id ), esc_attr( $value ) );
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
			// TODO: Status has been migrated, remove related code.
			// $listing_info['listing_status']          = get_post_meta( $p_id, '_listing_status', true );
			$listing_info['listing_status']          = get_post_status( $p_id );
			$listing_info['tagline']                 = get_post_meta( $p_id, '_tagline', true );
			$listing_info['atbdp_post_views_count']  = directorist_get_listing_views_count( $p_id );
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

		$location_fields = add_listing_category_location_filter( $this->get_current_listing_type(), $query_args, ATBDP_LOCATION, $ids );
		return $location_fields;
	}

	public function add_listing_terms( $taxonomy ) {
		$terms = get_the_terms( $this->add_listing_id, $taxonomy );

		if ( is_wp_error( $terms ) || empty( $terms ) ) {
			return [];
		}

		return $terms;
	}

	public function add_listing_term_ids( $taxonomy ) {
		return directorist_get_object_terms( $this->add_listing_id, $taxonomy, 'term_id' );
	}

	public function add_listing_cat_ids() {
		return $this->add_listing_term_ids( ATBDP_CATEGORY );
	}

	/**
	 * Get current listing tag ids.
	 *
	 * @return int[]
	 */
	public function add_listing_tag_ids() {
		return directorist_get_object_terms( $this->add_listing_id, ATBDP_TAGS, 'term_id' );
	}

	public function add_listing_cat_fields() {
		$p_id     = $this->add_listing_id;
		$fm_plan  = get_post_meta( $p_id, '_fm_plans', true );
		$plan_cat = is_fee_manager_active() ? is_plan_allowed_category( $fm_plan ) : array();
		$ids      = $this->add_listing_term_ids( ATBDP_CATEGORY );

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

		$categories_field = add_listing_category_location_filter( $this->get_current_listing_type(), $query_args, ATBDP_CATEGORY, $ids, '', $plan_cat );
		return $categories_field;
	}

	public function add_listing_has_contact_info( $args ) {
		_deprecated_function( __METHOD__, '7.3.1' );
		return false;
	}

	public function featured_listing_description() {
		$description = get_directorist_option('featured_listing_desc', ' (Top of the search result and listings pages for a number days and it requires an additional payment.) ');
		return $description;

	}

	public function required_html() {
		$required_html = '<span class="atbdp_make_str_red"> *</span>';
		return $required_html;
	}

	public function generate_label( $label, $required ) {
		$required_html = $this->required_html();
		return sprintf( '%s:%s', $label, $required ? $required_html : '' );
	}

	public function privacy_label() {
		$type          = $this->current_listing_type;
		$privacy_label = get_directorist_type_option( $type, 'terms_privacy_label', __( 'I agree to the %privacy_name% and %terms_name%', 'directorist' ) );
		return $this->generate_linktext( $privacy_label );
	}

	public function submit_label() {
		$p_id = $this->get_add_listing_id();
		$type = $this->current_listing_type;

		if ( !empty($p_id) ) {
			$submit_label = __('Preview Changes', 'directorist');
		}
		else {
			$submit_label = get_directorist_type_option( $type, 'submit_button_label', __( 'Save & Preview', 'directorist' ) );
		}
		return $submit_label;
	}

	public function submit_template() {
		$p_id = $this->get_add_listing_id();
		$type = $this->current_listing_type;

		$args = array(
			'listing_form'            => $this,
			'display_guest_listings'  => directorist_is_guest_submission_enabled(),
			'guest_email_label'       => get_directorist_option( 'guest_email_label', __( 'Your Email', 'directorist' ) ),
			'guest_email_placeholder' => get_directorist_option( 'guest_email_placeholder', __( 'example@email.com', 'directorist' ) ),
			'display_privacy'         => (bool) get_directorist_type_option( $type, 'listing_privacy', 1 ),
			'privacy_is_required'     => get_directorist_type_option( $type, 'require_privacy', 1 ),
			'privacy_checked'         => (bool) get_post_meta( $p_id, '_privacy_policy', true ),
			'display_terms'           => false,
			'terms_checked'           => (bool) get_post_meta( $p_id, '_t_c_check', true ),
			'submit_label'            => get_directorist_type_option( $type, 'submit_button_label', __( 'Save & Preview', 'directorist' ) ),
		);

		Helper::get_template( 'listing-form/submit', $args );
	}

	public function social_item_template( $index='', $social_info='' ) {
		if ( !$social_info ) {
			$index = 'socialindex';
			$social_info = [
				'id'   => '',
				'url'  => '',
			];
		}

		$args = array(
			'listing_form'   => $this,
			'index'          => $index,
			'social_info'    => $social_info,
		);

		Helper::get_template( 'listing-form/social-item', $args );
	}

	public function generate_linktext( $text ) {
		$pattern = '/%([^%]+)%/';                // extract 'text' from 'some %text%'
		preg_match_all( $pattern, $text, $matches );

		if ( ! empty( $matches[1] ) ) {
			foreach( $matches[1] as $match ) {
				$label     = $this->terms_privacy_name( $match);
				$link      = $this->terms_privacy_link( $match );
				$changed[] = sprintf('<a target="_blank" href="%s">%s</a>', $link, $label);
			}

			$result = str_replace( $matches[0], $changed, $text );
			return $result;
		}

		return $text;
	}

	private function terms_privacy_name( $name ) {
		switch ( $name ) {
			case 'terms_name':
				$name = get_directorist_type_option( $this->current_listing_type, 'terms_name', __( 'Terms & Conditions', 'directorist' ) );
				break;
			case 'privacy_name':
				$name = get_directorist_type_option( $this->current_listing_type, 'privacy_name', __( 'Privacy & Policy', 'directorist' ) );
				break;
			default:
            	$name =  '';
		}
		return $name;
	}

	private function terms_privacy_link( $name ) {
		switch ( $name ) {
			case 'terms_name':
				$link = get_directorist_type_option( $this->current_listing_type, 'terms_link', ATBDP_Permalink::get_terms_and_conditions_page_url() );
				break;
			case 'privacy_name':
				$link = get_directorist_type_option( $this->current_listing_type, 'privacy_link', ATBDP_Permalink::get_privacy_policy_page_url() );
				break;
			default:
            	$link =  '';
		}
		return $link;
	}

	public function type_hidden_field() {
		$value             		= get_post_meta( $this->get_add_listing_id(), '_directory_type', true );
		$current_directory_type = $this->get_current_listing_type();
		$default_directory 		= default_directory_type();
		$directory_type         = ! empty( $current_directory_type ) ? $current_directory_type : $default_directory;
		$current_type      		= ! empty( $value ) ? $value : $directory_type;
		printf( '<input type="hidden" name="directory_type" value="%s">', esc_attr( $current_type ) );
	}

	public function field_label_template( $data, $label_id = '' ) {
		$key = !empty( $data['field_key'] ) ? $data['field_key'] : 'random-'.rand();
		$args = array(
			'listing_form' => $this,
			'data'         => $data,
			'label_id'     => $label_id ? $label_id : $key,
		);
		Helper::get_template( 'listing-form/field-label', $args );
	}

	public function field_description_template( $data ) {
		$args = array(
			'listing_form' => $this,
			'data'         => $data,
		);
		Helper::get_template( 'listing-form/field-description', $args );
	}

	public function section_template( $section_data ) {
		$args = array(
			'listing_form' => $this,
			'section_data' => $section_data,
		);

		if ( ! is_admin() && $this->all_fields_only_for_admin( $section_data['fields'] ) ) {
			return; // Exit if all fields are only for admin
		}
		
		$load_section = apply_filters( 'directorist_section_template', true, $args );
		
		if( $load_section && ! empty( $section_data['fields'] ) ) {
			Helper::get_template( 'listing-form/section', $args );
		}
	}

	public function all_fields_only_for_admin( $fields ) {
		// If fields array is empty, return false (no restriction)
		if ( empty( $fields ) ) {
			return false;
		}
	
		// Check if all fields have 'only_for_admin' set to 1 or true
		foreach ( $fields as $field ) {
			if ( empty( $field['only_for_admin'] ) ) {
				return false; // If any field is not for admin, return false
			}
		}
	
		return true; // All fields are for admin
	}


	public function add_listing_category_custom_field_template( $field_data, $listing_id = NULL ) {
		$value = '';
		if ( ! empty( $listing_id ) ) {

			$value = get_post_meta( $listing_id, '_'.$field_data['field_key'], true );
		}

		if ( $field_data['field_key'] === 'hide_contact_owner' && $value == 1 ) {
			$value = 'on';
		}

		$field_data['value'] = $value;
		$field_data['form'] = $this;

		$args = array(
			'listing_form' => $this,
			'data'         => $field_data,
		);

		if ( $this->is_custom_field( $field_data ) ) {
			$template = 'listing-form/custom-fields/' . $field_data['widget_name'];
		}
		else {
			$template = 'listing-form/fields/' . $field_data['widget_name'];
		}

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

		if( ! empty( $field_data['assign_to'] ) ) {
			return;
		}

		$listing_id = $this->get_add_listing_id();
		$value = '';

		$field_data['lazy_load'] = get_directorist_option( 'lazy_load_taxonomy_fields', true );

		if ( ! empty( $listing_id ) ) {
			if ( $field_data['widget_name'] == 'title' ) {
				$value = $this->add_listing_post->post_title;
			}
			elseif ( $field_data['widget_name'] == 'description' ) {
				$value = $this->add_listing_post->post_content;
			}
			elseif ( $field_data['widget_name'] == 'terms_privacy' ) {
				$field_data['privacy_checked'] = (bool) get_post_meta( $listing_id, '_privacy_policy', true );
			}
			elseif ( !empty( $field_data['field_key'] ) ) {
				$value = get_post_meta( $listing_id, '_'.$field_data['field_key'], true );

				if ( empty( $value ) ) {
					$value = get_post_meta( $listing_id, $field_data['field_key'], true );
				}
			}
		}

		if ( $field_data['field_key'] === 'hide_contact_owner' && $value == 1 ) {
			$value = 'on';
		}

		$field_data['value'] = $value;
		$field_data['form'] = $this;
		$field_data = apply_filters( 'directorist_form_field_data', $field_data );


		if ( $this->is_custom_field( $field_data ) ) {

			$template = 'listing-form/custom-fields/' . $field_data['widget_name'];

			if( 'checkbox' === $field_data['type'] ){

				$options_value = is_array( $value ) ? join( ",",$value ) : $value;
				$result = explode( ",", $options_value );

				if( ! is_array( $value ) ){
					$pure_string = trim(preg_replace('/\n+/', ' ', $options_value));
					$result = explode( " ", $pure_string );
				}

				$field_data['value'] = $result;
			}

		}
		else {
			$template = 'listing-form/fields/' . $field_data['widget_name'];
		}

		$template = apply_filters( 'directorist_field_template', $template, $field_data );

		$args = array(
			'listing_form'  => $this,
			'data'          => $field_data,
		);

		if ( is_admin() ) {
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

	public function is_custom_field( $data ) {
		$fields = [ 'checkbox', 'color_picker', 'date', 'file', 'number', 'radio', 'select', 'text', 'textarea', 'time', 'url' ];
		return in_array( $data['widget_name'], $fields ) ? true : false;
	}

	public function get_listing_types() {
		$args = array();

		if ( self::$directory_type ) {
			$term_slug = get_term_by( 'slug', self::$directory_type[0], 'atbdp_listing_types' );
			if ( $term_slug || current_user_can( 'manage_options' ) || current_user_can( 'edit_pages' ) ) {
				$args['slug'] = self::$directory_type;
			}
		}

		if ( ! directorist_is_multi_directory_enabled() ) {
			$args['default_only'] = true;

			return directorist_get_directories_for_template( $args );
		}

		return directorist_get_directories_for_template( $args );
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
			$type = isset( $_REQUEST['directory_type'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['directory_type'] ) ) : '';
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
			'map_icon'           => directorist_icon( 'fas fa-map-pin', false ),
		);

		return $data;
	}

	public function render_shortcode( $atts ) {
		$atts = shortcode_atts( ['directory_type' => ''], $atts );
		self::$directory_type = $atts['directory_type'] ? explode( ',', $atts['directory_type'] ) : '';

		$guest_submission = directorist_is_guest_submission_enabled();
		$user_id		  = get_current_user_id();
		$user_type        = get_user_meta( $user_id, '_user_type', true );

		if ( ! $guest_submission && ! is_user_logged_in() ) {
			return \ATBDP_Helper::guard( array( 'type' => 'auth' ) );
		}
		elseif( ! empty( $user_type ) && ( 'general' == $user_type || 'become_author' == $user_type ) ) {
			return \ATBDP_Helper::guard( array( 'type' => 'user_type' ) );
		}

		// Check if current user can access this page
		$p_id = $this->get_add_listing_id();

		if ( ! empty( $p_id ) ) {
			$listing = get_post( $p_id );
			if ( $listing->post_author != get_current_user_id() && ! current_user_can( 'edit_others_at_biz_dirs' ) ) {
				return Helper::get_template_contents( 'listing-form/restrict-access', [ 'listing_form' => $this ] );
			}
		}

		$args = $this->get_map_data();

		$listing_types      = $this->get_listing_types();
		$listing_type_count = count( $listing_types );

		// Edit Mode
		if ( $p_id ) {
			$terms                  = get_the_terms( $p_id, ATBDP_TYPE );
			$type                   = !empty($terms) ? $terms[0]->term_id : '';
			$args['form_data']      = $this->build_form_data( $type );
			$args['enable_sidebar'] = (bool) get_directorist_type_option( $type, 'enable_sidebar', 1 );
			$args['is_edit_mode']   = true;

			return Helper::get_template_contents( 'listing-form/add-listing', $args );
		} else {
			// if no listing type exists
			if ( $listing_type_count == 0 ) {

				if( ! directory_types() ) {
					if( current_user_can('manage_options') || current_user_can('edit_pages') ) {
						$args['error_notice'] = sprintf( __('Please add a directory type first %s', 'directorist' ), '<a href="'. admin_url() .'edit.php?post_type=at_biz_dir&page=atbdp-directory-types">Add Now</a>' );
					} else {
						$args['error_notice'] = __('There\'s something unexpected happen. Please contact site admin.', 'directorist');
					}
				} else {
					$args['error_notice'] = __('Notice: Your given directory type is not valid. Please use a valid directory type', 'directorist');
				}

				return Helper::get_template_contents( 'listing-form/add-listing-notype', $args );
			}
			// if only one directory
			$type = $this->get_current_listing_type();

			if ( $type ) {
				$args['form_data']        = $this->build_form_data( $type );
				$args['enable_sidebar']   = (bool) get_directorist_type_option( $type, 'enable_sidebar', 1 );
				$args['single_directory'] = $type;
				$template                 = Helper::get_template_contents( 'listing-form/add-listing', $args );

				return apply_filters( 'atbdp_add_listing_page_template', $template, $args );
			}

			// multiple directory available
			$template = Helper::get_template_contents( 'listing-form/add-listing-type', [ 'listing_form' => $this ] );
			return apply_filters( 'atbdp_add_listing_page_template', $template, $args );
		}
	}
}