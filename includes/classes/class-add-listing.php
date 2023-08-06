<?php
/**
 * ATBDP Add_Listing class
 *
 * This class is for interacting with Add_Listing, eg, saving data to the database
 *
 * @package     ATBDP
 * @subpackage  inlcudes/classes Add_Listing
 * @copyright   Copyright (c) 2018, AazzTech
 * @since       1.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || die( 'Direct access is not allowed.' );

if ( ! class_exists( 'ATBDP_Add_Listing' ) ) :

	/**
	 * Class ATBDP_Add_Listing
	 */
	class ATBDP_Add_Listing {

		/**
		 * Nonce name.
		 *
		 * @var string
		 */
		public $nonce = 'add_listing_nonce';

		/**
		 * Ajax action name.
		 *
		 * @var string
		 */
		public $nonce_action = 'add_listing_action';

		/**
		 * ATBDP_Add_Listing constructor.
		 */
		public function __construct() {
			// show the attachment of the current users only.
			add_filter( 'ajax_query_attachments_args', array( $this, 'show_current_user_attachments' ) );
			add_action( 'parse_query', array( $this, 'parse_query' ) ); // do stuff likes adding, editing, renewing, favorite etc in this hook.
			add_action( 'wp_ajax_add_listing_action', array( $this, 'atbdp_submit_listing' ) );
			add_action( 'wp_ajax_nopriv_add_listing_action', array( $this, 'atbdp_submit_listing' ) );
		}

		/**
		 * Not sure what this function does.
		 *
		 * @deprecated 7.3.1
		 * @param  array  $array
		 * @param  string $name
		 *
		 * @return mixed
		 */
		private function atbdp_get_file_attachment_id( $array, $name ) {
			$id = null;
			foreach ( $array as $item ) {
				if ( $item['name'] === $name ) {
					$id = $item['id'];
					break;
				}
			}
			return $id;
		}


		/**
		 * Process listing submission.
		 *
		 * @since 5.6.3
		 */
		public function atbdp_submit_listing() {
			if ( ! directorist_verify_nonce() ) {
				return wp_send_json( array(
					'error' => true,
					'error_msg' =>  __( 'Something is wrong! Please refresh and retry.', 'directorist' ),
				), 400 );
			}

			$data = array();

			$posted_data = wp_unslash( $_POST );

			/**
			 * It fires before processing a submitted listing from the front end
			 *
			 * @param array $_POST the array containing the submitted listing data.
			 * */
			do_action( 'atbdp_before_processing_submitted_listing_frontend', $posted_data );

			$guest_listing_enabled = (bool) get_directorist_option( 'guest_listings', 0 );
			$featured_enabled      = get_directorist_option( 'enable_featured_listing' );

			// data validation
			$directory             = ! empty( $posted_data['directory_type'] ) ? sanitize_text_field( $posted_data['directory_type'] ) : '';
			$submission_form_fields = array();

			if ( $directory ) {
				$term                   = get_term_by( ( is_numeric( $directory ) ? 'id' : 'slug' ), $directory, ATBDP_TYPE );
				$directory_type         = $term->term_id;
				$submission_form        = get_term_meta( $directory_type, 'submission_form_fields', true );
				$new_l_status           = get_term_meta( $directory_type, 'new_listing_status', true );
				$edit_l_status          = get_term_meta( $directory_type, 'edit_listing_status', true );
				$default_expiration     = get_term_meta( $directory_type, 'default_expiration', true );
				$preview_enable         = atbdp_is_truthy( get_term_meta( $directory_type, 'preview_mode', true ) );
				$submission_form_fields = $submission_form['fields'];
			}

			// isolate data
			$error                     = array();
			$meta_data                 = array();
			$images                    = ! empty( $posted_data['files_meta'] ) ? $posted_data['files_meta'] : array();
			$manual_lat                = ! empty( $posted_data['manual_lat'] ) ? $posted_data['manual_lat'] : array();
			$manual_lng                = ! empty( $posted_data['manual_lng'] ) ? $posted_data['manual_lng'] : array();
			$map                       = ! empty( $manual_lat ) && ! empty( $manual_lng ) ? true : false;
			$attachment_only_for_admin = false;
			$is_description_admin_only = false;

			$posted_tags                = directorist_get_var( $posted_data['tax_input'][ ATBDP_TAGS ], array() );
			$posted_locations           = directorist_get_var( $posted_data['tax_input'][ ATBDP_LOCATION ], array() );
			$posted_categories          = directorist_get_var( $posted_data['tax_input'][ ATBDP_CATEGORY ], array() );
			$is_tag_admin_only          = false;
			$is_category_admin_only     = false;
			$is_location_admin_only     = false;
			$is_tag_insert_allowed      = false;
			$is_category_insert_allowed = false;
			$is_location_insert_allowed = false;
			$max_allowed_location = 0;

			$public_fields_with_empty_post_data = array();

			// meta input
			foreach ( $submission_form_fields as $field_internal_key => $form_field ) {
				$field_type       = directorist_get_var( $form_field['type'] );
				$field_key        = ! empty( $form_field['field_key'] ) ? $form_field['field_key'] : '';
				$submitted_data   = ! empty( $posted_data[ $field_key ] ) ? $posted_data[ $field_key ] : '';
				$required         = ! empty( $form_field['required'] ) ? true : false;
				$admin_only_field = ! empty( $form_field['only_for_admin'] ) ? true : false;
				$label            = ! empty( $form_field['label'] ) ? $form_field['label'] : '';

				if ( ! $admin_only_field && $field_key && empty( $submitted_data ) ) {
					$public_fields_with_empty_post_data[] = '_' . $field_key;
				}

				// No need to process admin only fields on the frontend.
				if ( $admin_only_field ) {
					if ( 'image_upload' === $field_internal_key ) {
						$attachment_only_for_admin = true;
					}

					if ( 'tag' === $field_internal_key ) {
						$is_tag_admin_only = true;
					}

					if ( 'category' === $field_internal_key ) {
						$is_category_admin_only = true;
					}

					if ( 'location' === $field_internal_key ) {
						$is_location_admin_only = true;
					}

					if ( 'description' === $field_internal_key ) {
						$is_description_admin_only = true;
					}

					continue;
				}

				if ( 'location' === $field_internal_key ) {
					$is_location_insert_allowed = (bool) $form_field['create_new_loc'];
					$max_allowed_location       = (int) directorist_get_var( $form_field['max_location_creation'], 0 );
				}

				if ( 'category' === $field_internal_key ) {
					$is_category_insert_allowed = (bool) $form_field['create_new_cat'];
				}

				if ( 'tag' === $field_internal_key ) {
					$is_tag_insert_allowed = (bool) $form_field['allow_new'];
				}

				$should_validate = apply_filters( 'atbdp_add_listing_form_validation_logic', true, $form_field, $posted_data );

				$field_category_id = (int) directorist_get_var( $form_field['category'], 0 );
				if ( $field_category_id && is_array( $posted_categories ) && ! in_array( $field_category_id, $posted_categories, true ) ) {
					$should_validate = false;
				}

				if ( $should_validate && $required ) {
					$is_empty = false;

					if ( 'category' === $field_internal_key && empty( $posted_categories ) ) {
						$is_empty = true;
					} elseif ( 'location' === $field_internal_key && empty( $posted_locations ) ) {
						$is_empty = true;
					} elseif ( 'tag' === $field_internal_key && empty( $posted_tags ) ) {
						$is_empty = true;
					} elseif ( 'image_upload' === $field_internal_key && empty( $images ) ) {
						$is_empty = true;
					} elseif ( 'map' === $field_internal_key && ! $map ) {
						$is_empty = true;
					}

					if ( ! in_array( $field_internal_key, array( 'category', 'location', 'tag', 'image_upload', 'map' ), true ) && ! $submitted_data ) {
						$is_empty = true;
					}

					if ( $is_empty ) {
						// translators: %s field label.
						$error[] = sprintf( __( '<strong>%s</strong> field is required!', 'directorist' ), $label );
					}
				}

				// process meta
				if ( 'pricing' === $field_internal_key ) {
					$meta_data['_atbd_listing_pricing'] = isset( $posted_data['atbd_listing_pricing'] ) ? sanitize_text_field( $posted_data['atbd_listing_pricing'] ) : '';
					$meta_data['_price'] = isset( $posted_data['price'] ) ? sanitize_text_field( $posted_data['price'] ) : '';
					$meta_data['_price_range'] = isset( $posted_data['price_range'] ) ? sanitize_text_field( $posted_data['price_range'] ) : '';
				}

				if ( 'map' === $field_internal_key ) {
					$meta_data['_hide_map'] = isset( $posted_data['hide_map'] ) ? sanitize_text_field( $posted_data['hide_map'] ) : '';
					$meta_data['_manual_lat'] = isset( $posted_data['manual_lat'] ) ? sanitize_text_field( $posted_data['manual_lat'] ) : '';
					$meta_data['_manual_lng'] = isset( $posted_data['manual_lng'] ) ? sanitize_text_field( $posted_data['manual_lng'] ) : '';
				}

				if ( ! in_array( $field_key, array( 'listing_title', 'listing_content', 'tax_input' ), true ) && isset( $posted_data[ $field_key ] ) ) {
					$meta_field_key = '_' . $field_key;
					if ( $field_type === 'textarea' ) {
						$meta_data[ $meta_field_key ] = sanitize_textarea_field( $posted_data[ $field_key ] );
					} elseif ( $field_type === 'email' ) {
						$meta_data[ $meta_field_key ] = sanitize_email( $posted_data[ $field_key ] );
					} else {
						$meta_data[ $meta_field_key ] = directorist_clean( $posted_data[ $field_key ] );
					}
				}
			}

			if ( ! empty( $posted_data['privacy_policy'] ) ) {
				$meta_data['_privacy_policy'] = (bool) $posted_data['privacy_policy'];
			}

			if ( ! empty( $posted_data['t_c_check'] ) ) {
				$meta_data['_t_c_check'] = (bool) $posted_data['t_c_check'];
			}

			$meta_data['_directory_type'] = $directory_type;

			// guest user
			if ( ! is_user_logged_in() ) {
				$guest_email = isset( $posted_data['guest_user_email'] ) ? sanitize_email( $posted_data['guest_user_email'] ) : '';
				if ( $guest_listing_enabled && is_email( $guest_email ) ) {
					atbdp_guest_submission( $guest_email );
				}
			}

			if ( $error ) {
				$data['error_msg'] = implode( '<br>', $error );
				$data['error']     = true;
			}
			/**
			 * It applies a filter to the meta values that are going to be saved with the listing submitted from the front end
			 *
			 * @param array $meta_data the array of meta keys and meta values
			 */

			$meta_data = apply_filters( 'atbdp_listing_meta_user_submission', $meta_data );
			$meta_data = apply_filters( 'atbdp_ultimate_listing_meta_user_submission', $meta_data, $posted_data );

			$meta_input = array_filter( $meta_data, static function( $value ) {
				if ( is_array( $value ) ) {
					return ! empty( $value );
				}
				return ( $value !== '' );
			} );

			$args = array(
				'post_title'   => sanitize_text_field( directorist_get_var( $posted_data['listing_title'], '' ) ),
				'post_type'    => ATBDP_POST_TYPE,
				'meta_input'   => $meta_input,
			);

			if ( ! $is_description_admin_only && isset( $posted_data['listing_content'] ) ) {
				$args['post_content'] = wp_kses_post( $posted_data['listing_content'] );
			}

			// is it update post ? @todo; change listing_id to atbdp_listing_id later for consistency with rewrite tags
			if ( ! empty( $posted_data['listing_id'] ) ) {
				$listing_id = absint( $posted_data['listing_id'] );

				/**
				 * @since 5.4.0
				 */
				do_action( 'atbdp_before_processing_to_update_listing' );

				$deletable_meta_fields = array_merge(
					$public_fields_with_empty_post_data,
					array_keys( array_diff_key( $meta_data, $meta_input ) )
				);

				foreach ( $deletable_meta_fields as $deletable_meta_field ) {
					delete_post_meta( $listing_id, $deletable_meta_field );
				}

				$_args       = array(
					'id'            => $listing_id,
					'edited'        => true,
					'new_l_status'  => $new_l_status,
					'edit_l_status' => $edit_l_status,
				);
				$post_status = $edit_l_status;

				$args['post_status'] = $post_status;

				if ( 'pending' === $post_status ) {
					$data['pending'] = true;
				}

				// update the post
				$args['ID'] = $listing_id; // set the ID of the post to update the post

				if ( ! empty( $preview_enable ) ) {
					$args['post_status'] = 'private';
				}

				// Check if the current user is the owner of the post
				$post = get_post( $args['ID'] );

				// update the post if the current user own the listing he is trying to edit. or we and give access to the editor or the admin of the post.
				if ( get_current_user_id() == $post->post_author || current_user_can( 'edit_others_at_biz_dirs' ) ) {
					$post_id = wp_update_post( $args );

					// TODO: figure out why directory type is being updated again.
					update_post_meta( $post_id, '_directory_type', $directory_type );

					if ( ! empty( $directory_type ) ) {
						wp_set_object_terms( $post_id, (int) $directory_type, 'atbdp_listing_types' );
					}

					// Process locations.
					if ( ! $is_location_admin_only && is_array( $posted_locations ) ) {
						if ( empty( $posted_locations ) ) {
							wp_set_object_terms( $post_id, '', ATBDP_LOCATION );
						} else {
							$location_ids = array();

							foreach ( $posted_locations as $location ) {

								$location_id = (int) $location;
								if ( $location_id && term_exists( $location_id, ATBDP_LOCATION ) ) {
									$location_ids[] = $location_id;
									continue;
								}

								if ( $is_location_insert_allowed ) {
									$location_added = wp_insert_term( $location, ATBDP_LOCATION );

									if ( is_wp_error( $location_added ) ) {
										if ( $location_added->get_error_code() === 'term_exists' ) {
											$location_ids[] = $location_added->get_error_data();
										} else {
											continue;
										}
									} else {
										$location_ids[] = $location_added['term_id'];
										update_term_meta( $location_added['term_id'], '_directory_type', array( $directory_type ) );
									}
								}
							}

							if ( $max_allowed_location > 0 ) {
								$location_ids = array_slice( $location_ids, 0, $max_allowed_location );
							}

							wp_set_object_terms( $post_id, $location_ids, ATBDP_LOCATION );
						}
					}

					// Process tags.
					if ( ! $is_tag_admin_only && is_array( $posted_tags ) ) {
						$posted_tags = array_map( static function( $tag ) {
							return trim( $tag );
						}, $posted_tags );

						if ( empty( $posted_tags ) ) {
							wp_set_object_terms( $post_id, '', ATBDP_TAGS );
						} else {
							$tag_ids = array();

							foreach ( $posted_tags as $tag ) {

								if ( ( $_tag = term_exists( $tag, ATBDP_TAGS ) ) ) {
									$tag_ids[] = (int) $_tag['term_id'];
									continue;
								}

								if ( $is_tag_insert_allowed ) {
									$tag_added = wp_insert_term( $tag, ATBDP_TAGS );

									if ( is_wp_error( $tag_added ) ) {
										if ( $tag_added->get_error_code() === 'term_exists' ) {
											$tag_ids[] = $tag_added->get_error_data();
										} else {
											continue;
										}
									} else {
										$tag_ids[] = $tag_added['term_id'];
									}
								}
							}

							wp_set_object_terms( $post_id, $tag_ids, ATBDP_TAGS );
						}
					}

					// Process categories.
					if ( ! $is_category_admin_only && is_array( $posted_categories ) ) {
						if ( empty( $posted_categories ) ) {
							wp_set_object_terms( $post_id, '', ATBDP_CATEGORY );
						} else {
							$category_ids = array();

							foreach ( $posted_categories as $category ) {

								$category_id = (int) $category;
								if ( $category_id && term_exists( $category_id, ATBDP_CATEGORY ) ) {
									$category_ids[] = $category_id;
									continue;
								}

								if ( $is_category_insert_allowed ) {
									$category_added = wp_insert_term( $category, ATBDP_CATEGORY );

									if ( is_wp_error( $category_added ) ) {
										if ( $category_added->get_error_code() === 'term_exists' ) {
											$category_ids[] = $category_added->get_error_data();
										} else {
											continue;
										}
									} else {
										$category_ids[] = $category_added['term_id'];
										update_term_meta( $category_added['term_id'], '_directory_type', array( $directory_type ) );
									}
								}
							}

							wp_set_object_terms( $post_id, $category_ids, ATBDP_CATEGORY );

							//TODO: need to know the purpose of this.
							update_post_meta( $post_id, '_admin_category_select', $category_ids );
						}
					}

					// for dev
					do_action( 'atbdp_listing_updated', $post_id );// for sending email notification
				} else {
					// kick the user out because he is trying to modify the listing of other user.
					$data['redirect_url'] = esc_url_raw( directorist_get_request_uri() . '?error=true' );
					$data['error']        = true;
				}
			} else {

				// the post is a new post, so insert it as new post.
				if ( current_user_can( 'publish_at_biz_dirs' ) && ( ! isset( $data['error'] ) ) ) {
					$post_status         = $new_l_status;
					$args['post_status'] = $post_status;

					if ( 'pending' === $post_status ) {
						$data['pending'] = true;
					}

					if ( ! empty( $preview_enable ) ) {
						$args['post_status'] = 'private';
					}

					$post_id = wp_insert_post( $args );

					update_post_meta( $post_id, '_directory_type', $directory_type );
					do_action( 'atbdp_listing_inserted', $post_id );// for sending email notification

					// Every post with the published status should contain all the post meta keys so that we can include them in query.
					if ( 'publish' == $new_l_status || 'pending' == $new_l_status ) {

						if ( ! $default_expiration ) {
							update_post_meta( $post_id, '_never_expire', 1 );
						} else {
							$exp_dt = calc_listing_expiry_date( '', $default_expiration );
							update_post_meta( $post_id, '_expiry_date', $exp_dt );
						}

						update_post_meta( $post_id, '_featured', 0 );
						update_post_meta( $post_id, '_listing_status', 'post_status' );
						update_post_meta( $post_id, '_admin_category_select', $posted_categories );
						/*
							* It fires before processing a listing from the front end
							* @param array $_POST the array containing the submitted fee data.
							* */
						do_action( 'atbdp_before_processing_listing_frontend', $post_id );

						// set up terms
						if ( ! empty( $directory_type ) ) {
							wp_set_object_terms( $post_id, (int) $directory_type, 'atbdp_listing_types' );
						}

						// Process locations.
						if ( ! $is_location_admin_only && is_array( $posted_locations ) ) {
							if ( empty( $posted_locations ) ) {
								wp_set_object_terms( $post_id, '', ATBDP_LOCATION );
							} else {
								$location_ids = array();

								foreach ( $posted_locations as $location ) {

									$location_id = (int) $location;
									if ( $location_id && term_exists( $location_id, ATBDP_LOCATION ) ) {
										$location_ids[] = $location_id;
										continue;
									}

									if ( $is_location_insert_allowed ) {
										$location_added = wp_insert_term( $location, ATBDP_LOCATION );

										if ( is_wp_error( $location_added ) ) {
											if ( $location_added->get_error_code() === 'term_exists' ) {
												$location_ids[] = $location_added->get_error_data();
											} else {
												continue;
											}
										} else {
											$location_ids[] = $location_added['term_id'];
											update_term_meta( $location_added['term_id'], '_directory_type', array( $directory_type ) );
										}
									}
								}

								if ( $max_allowed_location > 0 ) {
									$location_ids = array_slice( $location_ids, 0, $max_allowed_location );
								}

								wp_set_object_terms( $post_id, $location_ids, ATBDP_LOCATION );
							}
						}

						// Process tags.
						if ( ! $is_tag_admin_only && is_array( $posted_tags ) ) {
							$posted_tags = array_map( static function( $tag ) {
								return trim( $tag );
							}, $posted_tags );

							if ( empty( $posted_tags ) ) {
								wp_set_object_terms( $post_id, '', ATBDP_TAGS );
							} else {
								$tag_ids = array();

								foreach ( $posted_tags as $tag ) {

									if ( ( $_tag = term_exists( $tag, ATBDP_TAGS ) ) ) {
										$tag_ids[] = (int) $_tag['term_id'];
										continue;
									}

									if ( $is_tag_insert_allowed ) {
										$tag_added = wp_insert_term( $tag, ATBDP_TAGS );

										if ( is_wp_error( $tag_added ) ) {
											if ( $tag_added->get_error_code() === 'term_exists' ) {
												$tag_ids[] = $tag_added->get_error_data();
											} else {
												continue;
											}
										} else {
											$tag_ids[] = $tag_added['term_id'];
										}
									}
								}

								wp_set_object_terms( $post_id, $tag_ids, ATBDP_TAGS );
							}
						}

						// Process categories.
						if ( ! $is_category_admin_only && is_array( $posted_categories ) ) {
							if ( empty( $posted_categories ) ) {
								wp_set_object_terms( $post_id, '', ATBDP_CATEGORY );
							} else {
								$category_ids = array();

								foreach ( $posted_categories as $category ) {

									$category_id = (int) $category;
									if ( $category_id && term_exists( $category_id, ATBDP_CATEGORY ) ) {
										$category_ids[] = $category_id;
										continue;
									}

									if ( $is_category_insert_allowed ) {
										$category_added = wp_insert_term( $category, ATBDP_CATEGORY );

										if ( is_wp_error( $category_added ) ) {
											if ( $category_added->get_error_code() === 'term_exists' ) {
												$category_ids[] = $category_added->get_error_data();
											} else {
												continue;
											}
										} else {
											$category_ids[] = $category_added['term_id'];
											update_term_meta( $category_added['term_id'], '_directory_type', array( $directory_type ) );
										}
									}
								}

								wp_set_object_terms( $post_id, $category_ids, ATBDP_CATEGORY );

								//TODO: need to know the purpose of this.
								update_post_meta( $post_id, '_admin_category_select', $category_ids );
							}
						}
					}
					if ( 'publish' == $new_l_status ) {
						do_action( 'atbdp_listing_published', $post_id );// for sending email notification
					}
				}
			}

			if ( ! empty( $post_id ) ) {
				do_action( 'atbdp_after_created_listing', $post_id );
				$data['id'] = $post_id;

				// handling media files
				if ( ! $attachment_only_for_admin ) {
					$listing_images = atbdp_get_listing_attachment_ids( $post_id );
					$files          = ! empty( $_FILES['listing_img'] ) ? directorist_clean( wp_unslash(  $_FILES['listing_img'] ) ) : array();
					$files_meta     = ! empty( $_POST['files_meta'] ) ? directorist_clean( wp_unslash( $_POST['files_meta'] ) ) : array();

					if ( ! empty( $listing_images ) ) {
						foreach ( $listing_images as $__old_id ) {
							$match_found = false;
							if ( ! empty( $files_meta ) ) {
								foreach ( $files_meta as $__new_id ) {
									$new_id = isset( $__new_id['attachmentID'] ) ? (int) $__new_id['attachmentID'] : '';
									if ( $new_id === (int) $__old_id ) {
										$match_found = true;
										break;
									}
								}
							}
							if ( ! $match_found ) {
								wp_delete_attachment( (int) $__old_id, true );
							}
						}
					}
					$attach_data = array();
					if ( $files ) {
						foreach ( $files['name'] as $key => $value ) {

							$filetype = wp_check_filetype( $files['name'][ $key ] );

							if ( empty( $filetype['ext'] ) ) {
								continue;
							}

							if ( $files['name'][ $key ] ) {
								$file                     = array(
									'name'     => $files['name'][ $key ],
									'type'     => $files['type'][ $key ],
									'tmp_name' => $files['tmp_name'][ $key ],
									'error'    => $files['error'][ $key ],
									'size'     => $files['size'][ $key ],
								);
								$_FILES['my_file_upload'] = $file;
								$meta_data                = array();
								$meta_data['name']        = $files['name'][ $key ];
								$meta_data['id']          = atbdp_handle_attachment( 'my_file_upload', $post_id );
								array_push( $attach_data, $meta_data );
							}
						}
					}

					$new_files_meta = array();
					foreach ( $files_meta as $key => $value ) {
						if ( $key === 0 && $value['oldFile'] === 'true' ) {
							update_post_meta( $post_id, '_listing_prv_img', $value['attachmentID'] );
							set_post_thumbnail( $post_id, $value['attachmentID'] );
						}
						if ( $key === 0 && $value['oldFile'] !== 'true' ) {
							foreach ( $attach_data as $item ) {
								if ( $item['name'] === $value['name'] ) {
									$id = $item['id'];
									update_post_meta( $post_id, '_listing_prv_img', $id );
									set_post_thumbnail( $post_id, $id );
								}
							}
						}
						if ( $key !== 0 && $value['oldFile'] === 'true' ) {
							array_push( $new_files_meta, $value['attachmentID'] );
						}
						if ( $key !== 0 && $value['oldFile'] !== 'true' ) {
							foreach ( $attach_data as $item ) {
								if ( $item['name'] === $value['name'] ) {
									$id = $item['id'];
									array_push( $new_files_meta, $id );
								}
							}
						}
					}
					update_post_meta( $post_id, '_listing_img', $new_files_meta );
				}
				$permalink = get_permalink( $post_id );
				// no pay extension own yet let treat as general user

				$submission_notice = get_directorist_option( 'submission_confirmation', 1 );
				$redirect_page     = get_directorist_option( 'edit_listing_redirect', 'view_listing' );

				if ( 'view_listing' == $redirect_page ) {
					$data['redirect_url'] = $submission_notice ? add_query_arg( 'notice', true, $permalink ) : $permalink;
				} else {
					$data['redirect_url'] = $submission_notice ? add_query_arg( 'notice', true, ATBDP_Permalink::get_dashboard_page_link() ) : ATBDP_Permalink::get_dashboard_page_link();
				}

				$states                           = array();
				$states['monetization_is_enable'] = get_directorist_option( 'enable_monetization' );
				$states['featured_enabled']       = $featured_enabled;
				$states['listing_is_featured']    = ( ! empty( $posted_data['listing_type'] ) && ( 'featured' === $posted_data['listing_type'] ) ) ? true : false;
				$states['is_monetizable']         = ( $states['monetization_is_enable'] && $states['featured_enabled'] && $states['listing_is_featured'] ) ? true : false;

				if ( $states['is_monetizable'] ) {
					$payment_status            = Directorist\Helper::get_listing_payment_status( $post_id );
					$rejectable_payment_status = array( 'failed', 'cancelled', 'refunded' );

					if ( empty( $payment_status ) || in_array( $payment_status, $rejectable_payment_status ) ) {
						$data['redirect_url'] = ATBDP_Permalink::get_checkout_page_link( $post_id );
						$data['need_payment'] = true;

						wp_update_post(
							array(
								'ID'          => $post_id,
								'post_status' => 'pending',
							)
						);
					}
				}

				$data['success'] = true;

			} else {
				$data['redirect_url'] = site_url() . '?error=true';
				$data['error']        = true;
			}

			if ( ! empty( $data['success'] ) && $data['success'] === true ) {
				$data['success_msg'] = __( 'Your Submission is Completed! redirecting..', 'directorist' );
			}

			if ( ! empty( $data['error'] ) && $data['error'] === true ) {
				$data['error_msg'] = isset( $data['error_msg'] ) ? $data['error_msg'] : __( 'Sorry! Something Wrong with Your Submission', 'directorist' );
			} else {
				$data['preview_url'] = $permalink;
			}

			if ( ! empty( $data['need_payment'] ) && $data['need_payment'] === true ) {
				$data['success_msg'] = __( 'Payment Required! redirecting to checkout..', 'directorist' );
			}

			if ( $preview_enable ) {
				$data['preview_mode'] = true;
			}

			if ( ! empty( $posted_data['listing_id'] ) ) {
				$data['edited_listing'] = true;
			}

			if ( ! empty( $posted_data['preview_url'] ) ) {
				$posted_data['preview_url'] = Directorist\Helper::escape_query_strings_from_url( $posted_data['preview_url'] );
			}

			if ( ! empty( $posted_data['redirect_url'] ) ) {
				$posted_data['redirect_url'] = Directorist\Helper::escape_query_strings_from_url( $posted_data['redirect_url'] );
			}

			wp_send_json( apply_filters( 'atbdp_listing_form_submission_info', $data ) );
		}

		/**
		 * It sets the author parameter of the attachment query for showing the attachment of the user only.
		 *
		 * @param array $query
		 * @return array
		 */
		public function show_current_user_attachments( array $query = array() ) {
			$user_id = get_current_user_id();
			if ( ! current_user_can( 'delete_pages' ) ) {
				if ( $user_id ) {
					$query['author'] = $user_id;
				}
			}
			return $query;
		}

		/**
		 * It outputs nonce field to any any form
		 *
		 * @param bool $referrer Optional. Whether to set the referer field for validation. Default true.
		 * @param bool $echo Optional. Whether to display or return hidden form field. Default true.
		 */
		public function show_nonce_field( $referrer = true, $echo = true ) {
			wp_nonce_field( $this->nonce_action, $this->nonce, $referrer, $echo );
		}

		/**
		 * It helps to perform different db related action to the listing
		 *
		 * @param WP_Query $query
		 * @since 3.1.0
		 */
		public function parse_query( $query ) {
			$temp_token = ! empty( $_GET['token'] ) ? sanitize_text_field( wp_unslash( $_GET['token'] ) ) : '';
			$renew_from = ! empty( $_GET['renew_from'] ) ? sanitize_text_field( wp_unslash( $_GET['renew_from'] ) ) : '';

			if ( empty( $temp_token ) && empty( $renew_from ) ) {
				return;
			}

			$action = $query->get( 'atbdp_action' );
			$id     = $query->get( 'atbdp_listing_id' );
			$token  = get_post_meta( $id, '_renewal_token', true );

			if ( ! empty( $action ) && ! empty( $id ) && 'renew' == $action ) {
				if ( $temp_token === $token || $renew_from ) {
					$this->renew_listing( $id );
				} else {
					$redirect_url = esc_url_raw( add_query_arg( 'renew', 'token_expired', ATBDP_Permalink::get_dashboard_page_link() ) );
					wp_safe_redirect( $redirect_url );
					exit;
				}
			}
		}

		/**
		 * It renews the given listing
		 *
		 * @param $listing_id
		 * @return mixed
		 * @since 3.1.0
		 */
		private function renew_listing( $listing_id ) {
			$can_renew = get_directorist_option( 'can_renew_listing' );

			if ( ! $can_renew ) {
				return false;// vail if renewal option is turned off on the site.
			}

			// Hook for developers
			do_action( 'atbdp_before_renewal', $listing_id );

			update_post_meta( $listing_id, '_featured', 0 ); // delete featured

			// for listing package extensions...
			$active_monetization     = get_directorist_option( 'enable_monetization' );
			$enable_featured_listing = get_directorist_option( 'enable_featured_listing' );

			if ( $active_monetization && $enable_featured_listing ) {
				// if paid submission enabled/triggered by an extension, redirect to the checkout page and let that handle it, and vail out.
				update_post_meta( $listing_id, '_refresh_renewal_token', 1 );
				wp_safe_redirect( ATBDP_Permalink::get_checkout_page_link( $listing_id ) );
				exit;
			}

			$time       = current_time( 'mysql' );
			$post_array = array(
				'ID'            => $listing_id,
				'post_status'   => 'publish',
				'post_date'     => $time,
				'post_date_gmt' => get_gmt_from_date( $time ),
			);

			// Updating listing
			wp_update_post( $post_array );

			$directory_type = get_post_meta( $listing_id, '_directory_type', true );
			// Update the post_meta into the database
			$old_status = get_post_meta( $listing_id, '_listing_status', true );
			if ( 'expired' == $old_status ) {
				$expiry_date = calc_listing_expiry_date();
			} else {
				$old_expiry_date = get_post_meta( $listing_id, '_expiry_date', true );
				$expiry_date     = calc_listing_expiry_date( $old_expiry_date, '',  $directory_type );
			}

			// update related post meta_data
			update_post_meta( $listing_id, '_expiry_date', $expiry_date );
			update_post_meta( $listing_id, '_listing_status', 'post_status' );

			$exp_days       = get_term_meta( $directory_type, 'default_expiration', true );
			if ( $exp_days <= 0 ) {
				update_post_meta( $listing_id, '_never_expire', 1 );
			} else {
				update_post_meta( $listing_id, '_never_expire', 0 );
			}

			do_action( 'atbdp_after_renewal', $listing_id );
			$r_url = add_query_arg( 'renew', 'success', ATBDP_Permalink::get_dashboard_page_link() );
			update_post_meta( $listing_id, '_renewal_token', 0 );
			// hook for dev
			do_action( 'atbdp_before_redirect_after_renewal', $listing_id );
			wp_safe_redirect( $r_url );
			exit;
		}

	} // ends ATBDP_Add_Listing


endif;
