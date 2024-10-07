<?php
namespace Directorist\AddListingForm;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WP_Error;
use Exception;
use ATBDP_Permalink;
use Directorist\Helper;
use Directorist\Fields\Fields;

class SubmissionController {

	protected static $selected_categories = null;

	/**
	 * Request source origin.
	 * Possible values are: web, api
	 *
	 * @var string
	 */
	protected static $from = 'web';

	protected static function cache_selected_categories( $directory_id, &$posted_data ) {
		// Cache categories to check assigned categories in custom fields.
		$category_field = directorist_get_listing_form_category_field( $directory_id );

		if ( ! empty( $category_field ) ) {
			$selected_categories = Fields::create( $category_field )->get_value( $posted_data );

			if ( is_null( self::$selected_categories ) && ! empty( $selected_categories ) ) {
				self::$selected_categories = array_filter( wp_parse_id_list( $selected_categories ) );
			}
		}
	}

	protected static function is_admin_only_field( &$field ) {
		return $field->is_admin_only();
		// return ( $field->is_admin_only() && ! current_user_can( get_post_type_object( ATBDP_POST_TYPE )->cap->edit_others_posts ) );
	}

	protected static function should_ignore_category_custom_field( &$field ) {
		return ( $field->is_category_only() && ( is_null( self::$selected_categories ) || ! in_array( $field->get_assigned_category(), self::$selected_categories, true ) ) );
	}

	protected static function is_field_submission_empty( &$field, &$posted_data ) {
		return $field->is_value_empty( $posted_data );
	}

	protected static function validate_field( &$field, &$posted_data ) {
		$should_validate = (bool) apply_filters( 'atbdp_add_listing_form_validation_logic', true, $field->get_props(), $posted_data );

		if ( self::should_ignore_category_custom_field( $field ) ) {
			$should_validate = false;
		}

		if ( ! $should_validate ) {
			return array(
				'is_valid' => true,
				'message'  => ''
			);
		}

		if ( $field->is_required() && self::is_field_submission_empty( $field, $posted_data ) ) {
			$field->add_error( __( 'This field is required.', 'directorist' ) );
		} elseif ( ! self::is_field_submission_empty( $field, $posted_data ) ) {
			$field->validate( $posted_data );

			if ( self::$from === 'api' && $field->type === 'file' ) {
				self::validate_file_field( $field, $posted_data );
			}
		}

		return array(
			'is_valid' => ! $field->has_error(),
			'message'  => $field->get_error()
		);
	}

	protected static function validate_file_field( $field, &$posted_data ) {
		$value = $field->get_value( $posted_data );

		if ( ! is_string( $value ) ) {
			$field->add_error( __( 'Invalid data type, string allowed only.', 'directorist' ) );

			return;
		}

		// Ignore when stored value matched with given value.
		if ( ! empty( $posted_data['listing_id'] ) ) {
			$stored_value = get_post_meta( (int) $posted_data['listing_id'], '_' . $field->field_key, true );

			if ( $stored_value && ( $pos = strpos( $stored_value, '|' ) ) !== false ) {
				$stored_value = substr( $stored_value, 0, $pos );
			}

			if ( $stored_value && ( $value === $stored_value ) ) {
				return;
			}
		}

		try {
			$upload_dir = wp_get_upload_dir();
			$temp_dir   = trailingslashit( $upload_dir['basedir'] ) . trailingslashit( directorist_get_temp_upload_dir() . DIRECTORY_SEPARATOR . date( 'nj' ) );
			$filepath   = $temp_dir . $value;

			if ( is_dir( $filepath ) || ! file_exists( $filepath ) ) {
				$field->add_error( __( 'Invalid file or file does not exist.', 'directorist' ) );
				return;
			}

			$file_type = $field->get_file_types();

			if ( in_array( $file_type, array( '', 'all_types', 'all' ), true ) ) {
				$file_types = directorist_get_supported_file_types();
			} else {
				$groups = directorist_get_supported_file_types_groups();

				if ( isset( $groups[ $file_type ] ) ) {
					$file_types = $groups[ $file_type ];
				} else {
					$file_types = (array) $file_type;
				}
			}

			$supported_mimes = array();
			foreach ( get_allowed_mime_types() as $ext => $mime ) {
				$_exts = explode( '|', $ext );
				$match = array_intersect( $file_types, $_exts );
				if ( count( $match ) ) {
					$supported_mimes[ $ext ] = $mime;
				}
			}

			$mimetype = mime_content_type( $filepath );
			if ( ! in_array( $mimetype, $supported_mimes, true ) ) {
				$field->add_error( __( 'Invalid file type.', 'directorist' ) );
			}

			$size = filesize( $filepath );
			if ( $size > $field->get_file_size() ) {
				$field->add_error( sprintf(
					__( 'Uploaded file (%s) is larger than supported size (%s).', 'directorist' ),
					size_format( $size ),
					size_format( $field->get_file_size() )
					) );
			}

		} catch ( Exception $e ) {

			error_log( $e->getMessage() );

		}
	}

	protected static function get_file_value( $field, &$posted_data ) {
		$value = $field->get_value( $posted_data );

		if ( ! $value ) {
			return;
		}

		if ( ! empty( $posted_data['listing_id'] ) ) {
			$stored_value = get_post_meta( (int) $posted_data['listing_id'], '_' . $field->field_key, true );

			if ( $stored_value && ( $pos = strpos( $stored_value, '|' ) ) !== false ) {
				$stored_value = substr( $stored_value, 0, $pos );
			}

			if ( $stored_value && ( $value === $stored_value ) ) {
				return $stored_value;
			}
		}

		try {
			$upload_dir = wp_get_upload_dir();
			$temp_dir   = trailingslashit( $upload_dir['basedir'] ) . trailingslashit( directorist_get_temp_upload_dir() . DIRECTORY_SEPARATOR . date( 'nj' ) );
			$filepath   = $temp_dir . $value;
			$target_dir = trailingslashit( $upload_dir['basedir'] ) . trailingslashit( 'atbdp_temp' );

			// Clean old file
			if ( ! empty( $stored_value ) ) {
				$old_file = basename( $stored_value );
				if ( file_exists( $target_dir . $old_file ) ) {
					unlink( $target_dir . $old_file );
				}
			}

			if ( ! file_exists( $filepath ) ) {
				return;
			}

			if ( file_exists( $target_dir . $value ) ) {
				$value = wp_unique_filename( $target_dir, $value );
			}

			rename( $filepath, $target_dir . $value );

			return trailingslashit( $upload_dir['baseurl'] ) . trailingslashit( 'atbdp_temp' ) . $value;

		} catch ( Exception $e ) {

			error_log( $e->getMessage() );

		}

		return;
	}

	protected static function process_locations( &$field, &$posted_data, &$data, &$error ) {
		if ( $field->is_value_empty( $posted_data ) ) {
			$data[ ATBDP_LOCATION ] = array();

			return;
		}

		$locations    = $field->get_value( $posted_data );
		$location_ids = array();
		$max_allowed  = (int) $field->max_location_creation;

		foreach ( $locations as $location ) {

			$location_id = (int) $location;

			if ( $location_id && term_exists( $location_id, ATBDP_LOCATION ) ) {
				$location_ids[] = $location_id;

				if ( $field->user_can_select_multiple() && ( $max_allowed > 0 ) && ( count( $location_ids ) >= $max_allowed ) ) {
					break;
				}

				if ( ! $field->user_can_select_multiple() && count( $location_ids ) === 1 ) {
					break;
				}

				continue;
			}

			if ( $field->user_can_create() ) {
				$location_added = wp_insert_term( $location, ATBDP_LOCATION );

				if ( is_wp_error( $location_added ) ) {
					if ( $location_added->get_error_code() === 'term_exists' ) {
						$location_ids[] = $location_added->get_error_data();
					} else {
						continue;
					}
				} else {
					$location_ids[] = (int) $location_added['term_id'];

					directorist_update_location_directory( $location_added['term_id'], array( $posted_data['directory_id'] ) );
				}
			}

			if ( $field->user_can_select_multiple() && ( $max_allowed > 0 ) && ( count( $location_ids ) >= $max_allowed ) ) {
				break;
			}

			if ( ! $field->user_can_select_multiple() && count( $location_ids ) === 1 ) {
				break;
			}
		}

		if ( ! $field->user_can_select_multiple() && ! empty( $location_ids ) ) {
			$data[ ATBDP_LOCATION ] = array( $location_ids[0] );
		} else {
			$data[ ATBDP_LOCATION ] = $location_ids;
		}
	}

	protected static function process_categories( &$field, &$posted_data, &$data, &$error ) {
		if ( $field->is_value_empty( $posted_data ) ) {
			$data[ ATBDP_CATEGORY ] = array();

			return;
		}

		$categories    = $field->get_value( $posted_data );
		$category_ids = array();

		foreach ( $categories as $category ) {

			$category_id = (int) $category;

			if ( $category_id && term_exists( $category_id, ATBDP_CATEGORY ) ) {
				$category_ids[] = $category_id;

				if ( ! $field->user_can_select_multiple() && count( $category_ids ) === 1 ) {
					break;
				}

				continue;
			}

			if ( $field->user_can_create() ) {
				$category_added = wp_insert_term( $category, ATBDP_CATEGORY );

				if ( is_wp_error( $category_added ) ) {
					if ( $category_added->get_error_code() === 'term_exists' ) {
						$category_ids[] = $category_added->get_error_data();
					} else {
						continue;
					}
				} else {
					$category_ids[] = $category_added['term_id'];

					directorist_update_category_directory( $category_added['term_id'], array( $posted_data['directory_id'] ) );
				}
			}

			if ( ! $field->user_can_select_multiple() && count( $category_ids ) === 1 ) {
				break;
			}
		}

		if ( ! $field->user_can_select_multiple() && ! empty( $category_ids ) ) {
			$data[ ATBDP_CATEGORY ] = array( $category_ids[0] );
		} else {
			$data[ ATBDP_CATEGORY ] = $category_ids;
		}
	}

	protected static function process_tags( &$field, &$posted_data, &$data, &$error ) {
		if ( $field->is_value_empty( $posted_data ) ) {
			$data[ ATBDP_TAGS ] = array();

			return;
		}

		$tags    = $field->get_value( $posted_data );
		$tag_ids = array();

		foreach ( $tags as $tag ) {

			if ( $tag && ( $_tag = term_exists( $tag, ATBDP_TAGS ) ) ) {
				$tag_ids[] = (int) $_tag['term_id'];

				if ( ! $field->user_can_select_multiple() && count( $tag_ids ) === 1 ) {
					break;
				}

				continue;
			}

			if ( $field->user_can_create() ) {
				$tag_added = wp_insert_term( $tag, ATBDP_TAGS );

				if ( is_wp_error( $tag_added ) ) {
					if ( $tag_added->get_error_code() === 'term_exists' ) {
						$tag_ids[] = $tag_added->get_error_data();
					} else {
						continue;
					}
				} else {
					$tag_ids[] = (int) $tag_added['term_id'];
				}
			}

			if ( ! $field->user_can_select_multiple() && count( $tag_ids ) === 1 ) {
				break;
			}
		}

		if ( ! $field->user_can_select_multiple() && ! empty( $tag_ids ) ) {
			$data[ ATBDP_TAGS ] = array( $tag_ids[0] );
		} else {
			$data[ ATBDP_TAGS ] = $tag_ids;
		}
	}

	protected static function process_pricing( &$field, &$posted_data, &$data, &$error ) {
		if ( $field->is_value_empty( $posted_data ) ) {
			$data['_atbd_listing_pricing'] = '';
			$data['_price']                = '';
			$data['_price_range']          = '';

			return;
		}

		$value = $field->get_value( $posted_data );

		if ( empty( $value['price_type'] ) || ( empty( $value['price'] ) && empty( $value['price_range'] ) ) ) {
			$data['_atbd_listing_pricing'] = '';
			$data['_price']                = '';
			$data['_price_range']          = '';

			return;
		}

		$data['_atbd_listing_pricing'] = $value['price_type'];

		if ( $value['price_type'] === 'range' ) {
			$data['_price_range'] = $value['price_range'];
			$data['_price']       = '';
		} else {
			$data['_price']       = $value['price'];
			$data['_price_range'] = '';
		}
	}

	protected static function process_map( &$field, &$posted_data, &$data, &$error ) {
		if ( $field->is_value_empty( $posted_data ) ) {
			$data['_hide_map']   = '';
			$data['_manual_lat'] = '';
			$data['_manual_lng'] = '';

			return;
		}

		$value = $field->get_value( $posted_data );

		if ( $value['hide_map'] ) {
			$data['_hide_map']   = $value['hide_map'];
			$data['_manual_lat'] = '';
			$data['_manual_lng'] = '';

			return;
		}

		$data['_hide_map']   = $value['hide_map'];
		$data['_manual_lat'] = $value['manual_lat'];
		$data['_manual_lng'] = $value['manual_lng'];
	}

	protected static function filter_empty_meta_data( $meta_data ) {
		return array_filter( $meta_data, static function( $value, $key ) {
			if ( $key === '_hide_contact_owner' && ! $value ) {
				return false;
			}

			if ( is_array( $value ) ) {
				return ! empty( $value );
			}

			if ( is_null( $value ) ) {
				return false;
			}

			if ( is_string( $value ) && $value === '' ) {
				return false;
			}

			if ( is_numeric( $value ) && $value == 0 ) {
				return false;
			}

			return true;
		}, ARRAY_FILTER_USE_BOTH );
	}

	protected static function reset_listing_taxonomy( $listing_id, $taxonomy_data = array() ) {
		$taxonomies = array( ATBDP_LOCATION, ATBDP_CATEGORY, ATBDP_TAGS );

		foreach ( $taxonomies as $taxonomy ) {
			if ( isset( $taxonomy_data[ $taxonomy ] ) && empty( $taxonomy_data[ $taxonomy ] ) ) {
				wp_set_object_terms( $listing_id, '', $taxonomy );
			}
		}
	}

	protected static function clean_empty_metadata( $listing_id, $meta_data, $meta_input ) {
		$deletable_meta_fields = array_keys( array_diff_key( $meta_data, $meta_input ) );

		foreach ( $deletable_meta_fields as $deletable_meta_field ) {
			delete_post_meta( $listing_id, $deletable_meta_field );
		}
	}

	protected static function maybe_get_listing_id( &$posted_data ) {
		$listing_id = absint( directorist_get_var( $posted_data['listing_id'], 0 ) );

		if ( ! $listing_id ) {
			return 0;
		}

		if ( $listing_id && ! directorist_is_listing_post_type( $listing_id ) ) {
			return new WP_Error(
				'directorist_invalid_listing',
				__( 'Invalid listing!', 'directorist' ),
				array( 'status' => 400 )
			);
		}

		if ( $listing_id && ! current_user_can( get_post_type_object( ATBDP_POST_TYPE )->cap->edit_post, $listing_id ) ) {
			return new WP_Error(
				'directorist_invalid_permission',
				__( 'You are not allowed to edit this listing.', 'directorist' ),
				array( 'status' => 403 )
			);
		}

		return $listing_id;
	}

	protected static function maybe_get_directory_id( &$posted_data ) {
		$maybe_directory_id = sanitize_text_field( directorist_get_var( $posted_data['directory_type'], '' ) );
		$directory          = get_term_by( ( is_numeric( $maybe_directory_id ) ? 'id' : 'slug' ), $maybe_directory_id, ATBDP_DIRECTORY_TYPE );

		if ( directorist_is_multi_directory_enabled() && ! $directory ) {
			return new WP_Error(
				'directorist_invalid_directory',
				__( 'Invalid directory id.', 'directorist' ),
				array( 'status' => 400 )
			);
		}

		if ( ! $directory ) {
			return directorist_get_default_directory();
		}

		return ( (int) $directory->term_id );
	}

	protected static function validate_terms_and_conditions( $directory_id, &$posted_data ) {
		$error = new WP_Error();

		if ( directorist_should_check_privacy_policy( $directory_id ) && empty( $posted_data['privacy_policy'] ) ) {
			$error->add(
				'directorist_invalid_field',
				__( 'Privacy policy is required.', 'directorist' ),
				array(
					'status' => 400
				)
			);
		}

		if ( directorist_should_check_terms_and_condition( $directory_id ) && empty( $posted_data['t_c_check'] ) ) {
			$error->add(
				'directorist_invalid_field',
				__( 'Terms and condition is required.', 'directorist' ),
				array(
					'status' => 400
				)
			);
		}

		if ( $error->has_errors() ) {
			return $error;
		}

		return true;
	}

	/**
	 * Process form submission.
	 *
	 * @param  array $posted_data
	 * @param  string $from Request coming form web or api.
	 *
	 * @return WP_Error|array
	 */
	public static function submit( $posted_data, $from = 'web' ) {
		self::$from = $from;

		$listing_id = static::maybe_get_listing_id( $posted_data );
		if ( is_wp_error( $listing_id ) ) {
			return $listing_id;
		}

		$directory_id = static::maybe_get_directory_id( $posted_data );
		if ( is_wp_error( $directory_id ) ) {
			return $directory_id;
		}
		$posted_data['directory_id'] = $directory_id;

		$terms_conditions_check = static::validate_terms_and_conditions( $directory_id, $posted_data );
		if ( is_wp_error( $terms_conditions_check ) ) {
			return $terms_conditions_check;
		}

		static::cache_selected_categories( $directory_id, $posted_data );

		$error        = new WP_Error();
		$tax_data     = array();
		$meta_data    = array();
		$listing_data = array(
			'post_type' => ATBDP_POST_TYPE
		);

		/**
		 * Process form fields.
		 */
		$form_fields = directorist_get_listing_form_fields( $directory_id );

		foreach ( $form_fields as $form_field ) {
			$field = Fields::create( $form_field );

			// Ignore admin only fields when current user do not have that capability.
			if ( self::is_admin_only_field( $field ) ) {
				continue;
			}

			$result = self::validate_field( $field, $posted_data );

			if ( ! $result['is_valid'] ) {
				$error->add(
					'directorist_invalid_field',
					$result['message'],
					array(
						'field'  => $field->get_key(),
						'label'  => $field->label,
						'status' => 400
					)
				);

				continue;
			}

			if ( self::should_ignore_category_custom_field( $field ) ) {
				continue;
			}

			switch ( $field->get_internal_key() ) {
				case 'title':
					$listing_data['post_title'] = $field->sanitize( $posted_data );
					break;

				case 'excerpt':
					$listing_data['post_excerpt'] = $field->sanitize( $posted_data );
					$meta_data['_excerpt']        = $field->sanitize( $posted_data );
					break;

				case 'description':
					$listing_data['post_content'] = $field->sanitize( $posted_data );
					break;

				case 'location':
					self::process_locations( $field, $posted_data, $tax_data, $error );
					break;

				case 'category':
					self::process_categories( $field, $posted_data, $tax_data, $error );
					break;

				case 'tag':
					self::process_tags( $field, $posted_data, $tax_data, $error );
					break;

				case 'pricing':
					self::process_pricing( $field, $posted_data, $meta_data, $error );
					break;

				case 'map':
					self::process_map( $field, $posted_data, $meta_data, $error );
					break;

				case 'image_upload':
					break;

				default:
					$meta_data[ '_' . $field->get_key() ] = $field->sanitize( $posted_data );
			}

			if ( self::$from === 'api' && $field->type === 'file' ) {
				$meta_data[ '_' . $field->get_key() ] = self::get_file_value( $field, $posted_data );
			}
		}

		if ( $error->has_errors() ) {
			return $error;
		}

		if ( ! empty( $posted_data['privacy_policy'] ) ) {
			$meta_data['_privacy_policy'] = (bool) $posted_data['privacy_policy'];
		}

		if ( ! empty( $posted_data['t_c_check'] ) ) {
			$meta_data['_t_c_check'] = (bool) $posted_data['t_c_check'];
		}

		$listing_create_status = directorist_get_listing_create_status( $directory_id );
		$listing_edit_status   = directorist_get_listing_edit_status( $directory_id );
		$default_expiration    = directorist_get_default_expiration( $directory_id );
		$preview_enable        = atbdp_is_truthy( get_term_meta( $directory_id, 'preview_mode', true ) );

		/**
		 * It applies a filter to the meta values that are going to be saved with the listing submitted from the front end
		 *
		 * @param array $meta_data the array of meta keys and meta values
		 */
		$meta_data = apply_filters( 'atbdp_listing_meta_user_submission', $meta_data );
		$meta_data = apply_filters( 'atbdp_ultimate_listing_meta_user_submission', $meta_data, $posted_data );

		$meta_input = self::filter_empty_meta_data( $meta_data );

		$listing_data['meta_input'] = $meta_input;
		$listing_data['tax_input']  = $tax_data;

		// Handle edit
		if ( $listing_id ) {
			/**
			 * @since 5.4.0
			 */
			do_action( 'atbdp_before_processing_to_update_listing' );

			$listing_data['ID'] = $listing_id; // set the ID of the post to update the post

			if ( $from === 'web' && $preview_enable ) {
				$listing_data['post_status'] = 'private';
			} else {
				$listing_data['post_status'] = $listing_edit_status;
			}

			$listing_id = wp_update_post( $listing_data );

			if ( is_wp_error( $listing_id ) ) {
				return $listing_id;
			}

			self::reset_listing_taxonomy( $listing_id, $tax_data );
			directorist_set_listing_directory( $listing_id, $directory_id );

			// Clean empty meta data.
			self::clean_empty_metadata( $listing_id, $meta_data, $meta_input );

			do_action( 'atbdp_listing_updated', $listing_id );
		} else {
			if ( $from === 'web' && $preview_enable ) {
				$listing_data['post_status'] = 'private';
			} else {
				$listing_data['post_status'] = $listing_create_status;
			}

			$listing_id = wp_insert_post( $listing_data );

			if ( is_wp_error( $listing_id ) ) {
				return $listing_id;
			}

			directorist_set_listing_directory( $listing_id, $directory_id );

			do_action( 'atbdp_listing_inserted', $listing_id ); // for sending email notification

			// Every post with the published status should contain all the post meta keys so that we can include them in query.
			if ( 'publish' === $listing_create_status || 'pending' === $listing_create_status ) {
				if ( ! $default_expiration ) {
					update_post_meta( $listing_id, '_never_expire', 1 );
				} else {
					$expiration_date = calc_listing_expiry_date( '', $default_expiration );
					update_post_meta( $listing_id, '_expiry_date', $expiration_date );
				}

				update_post_meta( $listing_id, '_featured', 0 );
				// TODO: Status has been migrated, remove related code.
				update_post_meta( $listing_id, '_listing_status', 'post_status' );

				/*
				 * It fires before processing a listing from the front end
				 * @param array $_POST the array containing the submitted fee data.
				 * */
				do_action( 'atbdp_before_processing_listing_frontend', $listing_id );
			}

			if ( 'publish' === $listing_create_status ) {
				do_action( 'atbdp_listing_published', $listing_id );// for sending email notification
			}
		}

		do_action( 'atbdp_after_created_listing', $listing_id );

		// handling media files
		self::upload_images( $listing_id, $posted_data );

		$response = array(
			'id' => $listing_id
		);

		$permalink = get_permalink( $listing_id );
		// no pay extension own yet let treat as general user

		$submission_notice = get_directorist_option( 'submission_confirmation', 1 );
		$redirect_page     = get_directorist_option( 'edit_listing_redirect', 'view_listing' );

		if ( 'view_listing' === $redirect_page ) {
			$response['redirect_url'] = $submission_notice ? add_query_arg( 'notice', true, $permalink ) : $permalink;
		} else {
			$response['redirect_url'] = $submission_notice ? add_query_arg( 'notice', true, ATBDP_Permalink::get_dashboard_page_link() ) : ATBDP_Permalink::get_dashboard_page_link();
		}

		$is_listing_featured = ( ! empty( $posted_data['listing_type'] ) && ( 'featured' === $posted_data['listing_type'] ) );
		$should_monetize     = ( directorist_is_monetization_enabled() && directorist_is_featured_listing_enabled() && $is_listing_featured );

		if ( $should_monetize && ! is_fee_manager_active() ) {
			$payment_status            = Helper::get_listing_payment_status( $listing_id );
			$rejectable_payment_status = array( 'failed', 'cancelled', 'refunded' );

			if ( empty( $payment_status ) || in_array( $payment_status, $rejectable_payment_status, true ) ) {
				$response['redirect_url'] = ATBDP_Permalink::get_checkout_page_link( $listing_id );
				$response['need_payment'] = true;

				wp_update_post( array(
					'ID'          => $listing_id,
					'post_status' => 'pending',
				) );
			}
		}

		$response['success'] = true;
		$response['success_msg'] = __( 'Your listing submission is completed! Redirecting...', 'directorist' );
		$response['preview_url'] = $permalink;

		if ( ! empty( $response['need_payment'] ) && $response['need_payment'] === true ) {
			$response['success_msg'] = __( 'Payment required! Redirecting to checkout...', 'directorist' );
		}

		if ( $preview_enable ) {
			$response['preview_mode'] = true;
		}

		if ( ! empty( $posted_data['listing_id'] ) ) {
			$response['edited_listing'] = true;
		}

		if ( ! empty( $posted_data['preview_url'] ) ) {
			$response['preview_url'] = Helper::escape_query_strings_from_url( $posted_data['preview_url'] );
		}

		if ( ! empty( $posted_data['redirect_url'] ) ) {
			$response['redirect_url'] = Helper::escape_query_strings_from_url( $posted_data['redirect_url'] );
		}

		return $response;
	}

	public static function upload_images( $listing_id, $posted_data ) {
		$image_upload_field = directorist_get_listing_form_field( $posted_data['directory_id'], 'image_upload' );

		if ( empty( $image_upload_field ) ) {
			return;
		}

		$selected_images = Fields::create( $image_upload_field )->get_value( $posted_data );

		if ( is_null( $selected_images ) ) {
			// Cleanup listing meta when images field is empty.
			delete_post_thumbnail( $listing_id );
			delete_post_meta( $listing_id, '_listing_img' );
			delete_post_meta( $listing_id, '_listing_prv_img' );

			return;
		}

		$old_images = $selected_images['old'];
		$new_images = $selected_images['new'];

		self::clean_unselected_images( $listing_id, $old_images );

		if ( empty( $old_images ) && empty( $new_images ) ) {
			return;
		}

		try {
			$upload_dir                    = wp_get_upload_dir();
			$temp_dir                      = trailingslashit( $upload_dir['basedir'] ) . trailingslashit( directorist_get_temp_upload_dir() . DIRECTORY_SEPARATOR . date( 'nj' ) );
			$target_dir                    = trailingslashit( $upload_dir['path'] );
			$uploaded_images               = $old_images;
			$background_processable_images = array();

			foreach ( $new_images as $image ) {
				if ( empty( $image ) ) {
					continue;
				}

				$filepath = $temp_dir . $image;

				if ( is_dir( $filepath ) || ! file_exists( $filepath ) ) {
					continue;
				}

				if ( file_exists( $target_dir . $image ) ) {
					$image = wp_unique_filename( $target_dir, $image );
				}

				rename( $filepath, $target_dir . $image );

				$mime = wp_check_filetype( $image );
				$name = wp_basename( $image, ".{$mime['ext']}" );

				// Construct the attachment array.
				$attachment = array(
					'post_mime_type' => $mime['type'],
					'guid'           => trailingslashit( $upload_dir['url'] ) . $image,
					'post_parent'    => $listing_id,
					'post_title'     => sanitize_text_field( $name ),
				);

				$attachment_id = wp_insert_attachment( $attachment, $target_dir . $image, $listing_id, false );

				if ( is_wp_error( $attachment_id ) ) {
					throw new Exception( $attachment_id->get_error_message() );

					continue;
				}

				$background_processable_images[ $attachment_id ] = $target_dir . $image;

				$uploaded_images[] = $attachment_id;
			}

			if ( ! empty( $uploaded_images ) ) {
				update_post_meta( $listing_id, '_listing_prv_img', $uploaded_images[0] );
				set_post_thumbnail( $listing_id, $uploaded_images[0] );

				unset( $uploaded_images[0] );

				if ( count( $uploaded_images ) ) {
					update_post_meta( $listing_id, '_listing_img', $uploaded_images );
				}

				directorist_background_image_process( $background_processable_images );
			}

		} catch ( Exception $e ) {

			error_log( $e->getMessage() );

		}
	}

	protected static function clean_unselected_images( $listing_id, $selected_images ) {
		$saved_images = atbdp_get_listing_attachment_ids( $listing_id );
		if ( empty( $saved_images ) ) {
			return;
		}

		$unselected_images = array_diff( $saved_images, $selected_images );
		if ( empty( $unselected_images ) ) {
			return;
		}

		foreach ( $unselected_images as $unselected_image ) {
			wp_delete_attachment( $unselected_image, true );
		}
	}
}
