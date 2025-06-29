<?php
defined( 'ABSPATH' ) || die( 'Direct access is not allowed.' );

if ( ! class_exists( 'ATBDP_Custom_Taxonomy' ) ) :
	class ATBDP_Custom_Taxonomy {


		public function __construct() {

			add_action( 'init', array( $this, 'add_custom_taxonomy' ), 15 );

			// Category actions.
			add_filter( 'manage_edit-' . ATBDP_CATEGORY . '_columns', array( $this, 'register_category_columns' ) );
			add_filter( 'manage_' . ATBDP_CATEGORY . '_custom_column', array( $this, 'add_category_column_data' ), 15, 3 );
			add_action( ATBDP_CATEGORY . '_add_form_fields', array( $this, 'add_category_form_fields' ) );
			add_action( 'created_' . ATBDP_CATEGORY, array( $this, 'save_add_category_form_fields' ) );
			add_action( ATBDP_CATEGORY . '_edit_form_fields', array( $this, 'edit_category_form_fields' ) );
			add_action( 'edited_' . ATBDP_CATEGORY, array( $this, 'save_edit_category_form_fields' ) );
			add_filter( ATBDP_CATEGORY . '_row_actions', array( $this, 'edit_taxonomy_view_link' ), 10, 2 );

			// Location actions.
			add_filter( 'manage_edit-' . ATBDP_LOCATION . '_columns', array( $this, 'register_location_columns' ) );
			add_filter( 'manage_' . ATBDP_LOCATION . '_custom_column', array( $this, 'add_location_column_data' ), 15, 3 );
			add_action( ATBDP_LOCATION . '_add_form_fields', array( $this, 'add_location_form_fields' ) );
			add_action( 'created_' . ATBDP_LOCATION, array( $this, 'save_add_location_form_fields' ) );
			add_action( ATBDP_LOCATION . '_edit_form_fields', array( $this, 'edit_location_form_fields' ) );
			add_action( 'edited_' . ATBDP_LOCATION, array( $this, 'save_edit_location_form_fields' ) );
			add_filter( ATBDP_LOCATION . '_row_actions', array( $this, 'edit_taxonomy_view_link' ), 10, 2 );

			// Bulk actions
			add_filter( 'bulk_actions-edit-' . ATBDP_CATEGORY, array( $this, 'register_bulk_actions' ) );
			add_filter( 'handle_bulk_actions-edit-' . ATBDP_CATEGORY, array( $this, 'handle_bulk_actions' ), 10, 3 );
			add_filter( 'bulk_actions-edit-' . ATBDP_LOCATION, array( $this, 'register_bulk_actions' ) );
			add_filter( 'handle_bulk_actions-edit-' . ATBDP_LOCATION, array( $this, 'handle_bulk_actions' ), 10, 3 );

			// Other actions.
			add_filter( 'term_updated_messages', array( $this, 'add_term_updated_messages' ) );

			add_filter( 'term_link', array( $this, 'taxonomy_redirect_page' ), 10, 3 );
			add_action( 'template_redirect', array( $this, 'atbdp_template_redirect' ) );

			add_action( 'wp_loaded', array( $this, 'directorist_bulk_term_update' ) );

			// Filter.
			// add_filter( 'views_edit-' . ATBDP_CATEGORY, array( $this, 'add_directory_filter' ) );
			// add_filter( 'views_edit-' . ATBDP_LOCATION, array( $this, 'add_directory_filter' ) );

			add_action( 'delete_' . ATBDP_DIRECTORY_TYPE, array( $this, 'delete_directory_to_category_location_relation' ) );
		}

		public function directorist_bulk_term_update() {
			if ( get_option( 'directorist_bulk_term_update_v7_0_3_2' ) ) {
				return;
			}

			$terms = array( ATBDP_CATEGORY, ATBDP_LOCATION );
			foreach ( $terms as $term ) {
				$term_data = get_terms(
					array(
						'taxonomy'   => $term,
						'hide_empty' => false,
						'orderby'    => 'date',
						'order'      => 'DSCE',
					)
				);
				if ( ! empty( $term_data ) ) {
					foreach ( $term_data as $data ) {

						$old_data = get_term_meta( $data->term_id, '_directory_type', true );

						$results = is_array( $old_data ) ? $old_data[0] : $old_data;

						if ( ! empty( $results ) ) {

							if ( is_array( $old_data ) ) {
								foreach ( $old_data as $single_data ) {

									if ( ! is_numeric( $single_data ) ) {
										$term_with_directory_slug = get_term_by( 'slug', $single_data, 'atbdp_listing_types' );
										$id                       = $term_with_directory_slug->term_id;
										update_term_meta( $data->term_id, '_directory_type', array( $id ) );
									}
								}
							} else {
								if ( ! is_numeric( $old_data ) ) {
									$term_with_directory_slug = get_term_by( 'slug', $old_data, 'atbdp_listing_types' );
									$id                       = $term_with_directory_slug->term_id;
									update_term_meta( $data->term_id, '_directory_type', array( $id ) );
								} else {
									update_term_meta( $data->term_id, '_directory_type', array( $old_data ) );
								}
							}
						} else {
							update_term_meta( $data->term_id, '_directory_type', array( default_directory_type() ) );
						}
					}
				}
			}
			update_option( 'directorist_bulk_term_update_v7_0_3_2', 1 );
		}


		public function atbdp_template_redirect() {
			if ( is_feed() || ! is_tax( ATBDP_CATEGORY ) ) {
				return;
			}

			$redirect_url = '';
			if ( apply_filters( 'directorist_category_page_redirection_enabled', true ) ) {
				$category     = get_queried_object();
				$redirect_url = ATBDP_Permalink::atbdp_get_category_page( $category );
			}

			if ( empty( $redirect_url ) ) {
				return;
			}

			wp_safe_redirect( esc_url_raw( $redirect_url ) );
			exit();
		}

		public function taxonomy_redirect_page( $url, $term, $taxonomy ) {
			$directory_type_id       = get_post_meta( get_the_ID(), '_directory_type', true );
			$directory_type_slug     = '';
			$is_directorist_taxonomy = false;

			if ( ! empty( $directory_type_id ) ) {
				$directory_type_term = get_term_by( 'id', $directory_type_id, ATBDP_DIRECTORY_TYPE );
				$directory_type_slug = ( $directory_type_term && is_object( $directory_type_term ) ) ? $directory_type_term->slug : '';
			}

			// Categories
			if ( ATBDP_CATEGORY == $taxonomy ) {
				$url                     = ATBDP_Permalink::atbdp_get_category_page( $term );
				$is_directorist_taxonomy = true;
			}

			// Location
			if ( ATBDP_LOCATION == $taxonomy ) {
				$url                     = ATBDP_Permalink::atbdp_get_location_page( $term );
				$is_directorist_taxonomy = true;
			}

			// Tag
			if ( ATBDP_TAGS == $taxonomy ) {
				$url                     = ATBDP_Permalink::atbdp_get_tag_page( $term );
				$is_directorist_taxonomy = true;
			}

			if ( $is_directorist_taxonomy && ! empty( $directory_type_slug ) ) {
				$url = add_query_arg( 'directory_type', $directory_type_slug, $url );
			}

			return $url;
		}

		public function edit_taxonomy_view_link( $actions, $tag ) {
			// Get the directory_type from the term meta
			$directory_type         = get_term_meta( $tag->term_id, '_directory_type', true );
			$enable_multi_directory = get_directorist_option( 'enable_multi_directory' );
			$directory_type_slug    = is_array( $directory_type ) && ! empty( $directory_type[0] ) ? $this->get_directory_type_slug( absint( $directory_type[0] ) ) : 0;

			// change the view link of ATBDP_Category
			if ( ATBDP_CATEGORY == $tag->taxonomy ) {
				if ( $actions['view'] ) {

					$view_url = ATBDP_Permalink::atbdp_get_category_page( $tag );
					// Add the directory_type to the URL if it exists
					if ( ! empty( $enable_multi_directory ) && ! empty( $directory_type_slug ) ) {
						$view_url = add_query_arg( 'directory_type', $directory_type_slug, $view_url );
					}

					$actions['view'] = sprintf(
						'<a href="%s" aria-label="%s">%s</a>',
						esc_url( $view_url ),
						/* translators: %s: taxonomy term name */
						esc_attr( sprintf( __( 'View &#8220;%s&#8221; archive', 'directorist' ), $tag->name ) ),
						__( 'View', 'directorist' )
					);
				}
			} elseif ( ATBDP_LOCATION == $tag->taxonomy ) {
				if ( $actions['view'] ) {

					$view_url = ATBDP_Permalink::atbdp_get_location_page( $tag );
					// Add the directory_type to the URL if it exists
					if ( ! empty( $enable_multi_directory ) && ! empty( $directory_type_slug ) ) {
						$view_url = add_query_arg( 'directory_type', $directory_type_slug, $view_url );
					}

					$actions['view'] = sprintf(
						'<a href="%s" aria-label="%s">%s</a>',
						esc_url( $view_url ),
						/* translators: %s: taxonomy term name */
						esc_attr( sprintf( __( 'View &#8220;%s&#8221; archive', 'directorist' ), $tag->name ) ),
						__( 'View', 'directorist' )
					);
				}
			}

			return $actions;
		}

		public function get_directory_type_slug( $id ) {

			if ( is_numeric( $id ) ) {
				$term                   = get_term_by( 'id', $id, ATBDP_TYPE) ;
				// Check if the term exists
				if ( $term && ! is_wp_error( $term ) ) {
					$current_directory_type = $term->slug; // Get the slug if term exists
				}
			}

			return $current_directory_type;
		}

		public function save_edit_category_form_fields( $category_id ) {
			if ( ! directorist_verify_nonce() ) {
				return;
			}

			$directories = ! empty( $_POST['directory_type'] ) ? (array) directorist_clean( wp_unslash( $_POST['directory_type'] ) ) : array();
			$icon        = ! empty( $_POST['category_icon'] ) ? sanitize_text_field( wp_unslash( $_POST['category_icon'] ) ) : '';
			$image       = ! empty( $_POST['image'] ) ? absint( wp_unslash( $_POST['image'] ) ) : 0;

			$directories = wp_parse_id_list( $directories );

			if ( ! directorist_is_multi_directory_enabled() || empty( $directories ) ) {
				$directories = array( $this->default_listing_type() );
			}

			$directories = array_filter( $directories );

			if ( ! empty( $directories ) ) {
				directorist_update_category_directory( $category_id, $directories );
			} else {
				directorist_delete_term_directory( $category_id );
			}

			if ( $icon ) {
				update_term_meta( $category_id, 'category_icon', $icon );
			} else {
				delete_term_meta( $category_id, 'category_icon' );
			}

			if ( $image ) {
				update_term_meta( $category_id, 'image', $image );
			} else {
				delete_term_meta( $category_id, 'image', '' );
			}
		}

		public function save_edit_location_form_fields( $location_id ) {
			if ( ! directorist_verify_nonce() ) {
				return;
			}

			$directories = ! empty( $_POST['directory_type'] ) ? (array) directorist_clean( wp_unslash( $_POST['directory_type'] ) ) : array();
			$image       = ! empty( $_POST['image'] ) ? absint( wp_unslash( $_POST['image'] ) ) : 0;
			$directories = wp_parse_id_list( $directories );

			if ( ! directorist_is_multi_directory_enabled() || empty( $directories ) ) {
				$directories = array( $this->default_listing_type() );
			}

			$directories = array_filter( $directories );

			if ( ! empty( $directories ) ) {
				directorist_update_location_directory( $location_id, $directories );
			} else {
				directorist_delete_term_directory( $location_id );
			}

			if ( $image ) {
				update_term_meta( $location_id, 'image', $image );
			} else {
				delete_term_meta( $location_id, 'image', '' );
			}
		}

		public function edit_category_form_fields( $category_term ) {
			$icon_class           = get_term_meta( $category_term->term_id, 'category_icon', true );
			$selected_directories = directorist_get_category_directory( $category_term->term_id );
			$default_listing_type = $this->default_listing_type();
			$image_id             = get_term_meta( $category_term->term_id, 'image', true );
			$image_src            = ( $image_id ) ? wp_get_attachment_url( (int) $image_id ) : '';
			$directories          = directorist_get_directories( array(
				'fields' => 'id=>name',
			) );

			wp_nonce_field( directorist_get_nonce_key(), 'directorist_nonce' );

			if ( ! $default_listing_type && ! is_wp_error( $directories ) ) : ?>
				<tr class="form-field term-group-wrap">
					<th scope="row"><label for="directory-types"><?php esc_html_e( 'Directory', 'directorist' ); ?></label></th>
					<td>
						<div class="directory_types-wrapper">
							<?php
							foreach ( $directories as $directory_id => $directory_name ) :
								$checked = in_array( $directory_id, $selected_directories, true ) ? 'checked' : '';
								?>
								<div class="directory_type-group">
									<input type="checkbox" class="postform" name="directory_type[]" value="<?php echo esc_attr( $directory_id ); ?>" id="<?php echo esc_attr( $directory_id ); ?>" <?php echo esc_attr( $checked ); ?> />
									<label for="<?php echo esc_attr( $directory_id ); ?>"><?php echo esc_html( $directory_name ); ?></label>
								</div>
							<?php endforeach; ?>
						</div>
						<p class="description"><?php esc_html_e( 'You can assign any one or more of the above directories to this category.', 'directorist' ); ?></p>
					</td>
				</tr>
			<?php endif; ?>

			<tr class="form-field term-group-wrap">
				<th scope="row"><label for="category_icon"><?php esc_html_e( 'Icon', 'directorist' ); ?></label></th>
				<td>
					<div class="directorist-category-icon-picker"></div>
					<input type="hidden" class="category_icon_value" value="<?php echo esc_attr( $icon_class ); ?>" name="category_icon">
				</td>
			</tr>

			<tr class="form-field term-group-wrap">
				<th scope="row">
					<label for="atbdp-categories-image-id"><?php esc_html_e( 'Image', 'directorist' ); ?></label>
				</th>
				<td>
					<input type="hidden" id="atbdp-categories-image-id" name="image" value="<?php echo esc_attr( $image_id ); ?>"/>
					<div id="atbdp-categories-image-wrapper">
						<?php if ( $image_src ) : ?>
							<img src="<?php echo esc_url( $image_src ); ?>"/>
							<a href="" class="remove_cat_img"><span class="fa fa-times" title="Remove it"></span></a>
						<?php endif; ?>
					</div>
					<p>
						<input type="button" class="button button-secondary" id="atbdp-categories-upload-image" value="<?php esc_html_e( 'Add Image', 'directorist' ); ?>"/>
					</p>
				</td>
			</tr>
			<?php
		}

		public function edit_location_form_fields( $location_term ) {
			$selected_directories = directorist_get_location_directory( $location_term->term_id );
			$default_listing_type = $this->default_listing_type();
			$image_id             = get_term_meta( $location_term->term_id, 'image', true );
			$image_src            = ( $image_id ) ? wp_get_attachment_url( (int) $image_id ) : '';
			$directories          = directorist_get_directories( array(
				'fields' => 'id=>name',
			) );

			wp_nonce_field( directorist_get_nonce_key(), 'directorist_nonce' );

			if ( ! $default_listing_type && ! is_wp_error( $directories ) ) : ?>
				<tr class="form-field term-group-wrap">
					<th scope="row"><label for="directory-types"><?php esc_html_e( 'Directory', 'directorist' ); ?></label></th>
					<td>
						<div class="directory_types-wrapper">
							<?php
							foreach ( $directories as $directory_id => $directory_name ) :
								$checked = in_array( $directory_id, $selected_directories, true ) ? 'checked' : '';
								?>
								<div class="directory_type-group">
									<input type="checkbox" class="postform" name="directory_type[]" value="<?php echo esc_attr( $directory_id ); ?>" id="<?php echo esc_attr( $directory_id ); ?>" <?php echo esc_attr( $checked ); ?> />
									<label for="<?php echo esc_attr( $directory_id ); ?>"><?php echo esc_html( $directory_name ); ?></label>
								</div>
							<?php endforeach; ?>
						</div>
						<p class="description"><?php esc_html_e( 'You can assign any one or more of the above directories to this location.', 'directorist' ); ?></p>
					</td>
				</tr>
			<?php endif; ?>

			<tr class="form-field term-group-wrap">
				<th scope="row">
					<label for="atbdp-categories-image-id"><?php esc_html_e( 'Image', 'directorist' ); ?></label>
				</th>
				<td>
					<input type="hidden" id="atbdp-categories-image-id" name="image" value="<?php echo esc_attr( $image_id ); ?>"/>
					<div id="atbdp-categories-image-wrapper">
						<?php if ( $image_src ) : ?>
							<img src="<?php echo esc_url( $image_src ); ?>"/>
							<a href="" class="remove_cat_img"><span class="fa fa-times" title="Remove it"></span></a>
						<?php endif; ?>
					</div>
					<p>
						<input type="button" class="button button-secondary" id="atbdp-categories-upload-image" value="<?php esc_html_e( 'Add Image', 'directorist' ); ?>"/>
					</p>
				</td>
			</tr>
			<?php
		}

		public function save_add_category_form_fields( $category_id ) {
			if ( ! directorist_verify_nonce() ) {
				return;
			}

			$directories = ! empty( $_POST['directory_type'] ) ? (array) directorist_clean( wp_unslash( $_POST['directory_type'] ) ) : array();
			$icon        = ! empty( $_POST['category_icon'] ) ? sanitize_text_field( wp_unslash( $_POST['category_icon'] ) ) : '';
			$image       = ! empty( $_POST['image'] ) ? absint( wp_unslash( $_POST['image'] ) ) : 0;
			$directories = wp_parse_id_list( $directories );

			if ( ! directorist_is_multi_directory_enabled() || empty( $directories ) ) {
				$directories = array( $this->default_listing_type() );
			}

			$directories = array_filter( $directories );

			if ( ! empty( $directories ) ) {
				directorist_update_category_directory( $category_id, $directories );
			}

			if ( $icon ) {
				add_term_meta( $category_id, 'category_icon', $icon );
			}

			if ( $image ) {
				add_term_meta( $category_id, 'image', $image );
			}
		}

		public function save_add_location_form_fields( $location_id ) {
			$directories = ! empty( $_POST['directory_type'] ) ? (array) directorist_clean( wp_unslash( $_POST['directory_type'] ) ) : array();
			$image       = ! empty( $_POST['image'] ) ? absint( wp_unslash( $_POST['image'] ) ) : 0;
			$directories = wp_parse_id_list( $directories );

			if ( ! directorist_is_multi_directory_enabled() || empty( $directories ) ) {
				$directories = array( $this->default_listing_type() );
			}

			$directories = array_filter( $directories );

			if ( ! empty( $directories ) ) {
				directorist_update_location_directory( $location_id, $directories );
			}

			if ( $image ) {
				add_term_meta( $location_id, 'image', $image );
			}
		}

		public function add_category_form_fields() {
			$default_listing_type = $this->default_listing_type();
			$directories = directorist_get_directories( array(
				'fields' => 'id=>name',
			) );

			wp_nonce_field( directorist_get_nonce_key(), 'directorist_nonce' );

			if ( ! $default_listing_type && ! is_wp_error( $directories ) ) : ?>
				<div class="form-field term-group">
					<label for="directory_type"><?php esc_html_e( 'Directory', 'directorist' ); ?></label>
					<p><?php esc_html_e( 'You can assign any one or more of the following directories to the category.', 'directorist' ); ?></p>
					<div class="directory_types-wrapper">
						<?php foreach ( $directories as $directory_id => $directory_name ) : ?>
							<div class="directory_type-group">
								<input type="checkbox" class="postform" name="directory_type[]" id="directory_type-<?php echo esc_attr( $directory_id ); ?>" value='<?php echo esc_attr( $directory_id ); ?>'/>
								<label for="directory_type-<?php echo esc_attr( $directory_id ); ?>"><?php echo esc_html( $directory_name ); ?></label>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>

			<div class="form-field term-group">
				<label for="category_icon"><?php esc_html_e( 'Icon', 'directorist' ); ?></label>
				<div class="directorist-category-icon-picker"></div>
                <input type="hidden" class="category_icon_value" value="" name="category_icon">
			</div>

			<div class="form-field term-group">
				<label for="atbdp-categories-image-id"><?php esc_html_e( 'Image', 'directorist' ); ?></label>
				<input type="hidden" id="atbdp-categories-image-id" name="image"/>
				<div id="atbdp-categories-image-wrapper"></div>
				<p>
					<input type="button" class="button button-secondary" id="atbdp-categories-upload-image" value="<?php esc_attr_e( 'Add Image', 'directorist' ); ?>"/>
				</p>
			</div>
			<?php
		}

		public function add_location_form_fields() {
			$default_listing_type = $this->default_listing_type();
			$directories          = directorist_get_directories( array(
				'fields' => 'id=>name'
			) );

			wp_nonce_field( directorist_get_nonce_key(), 'directorist_nonce' );

			if ( ! $default_listing_type && ! is_wp_error( $directories ) ) : ?>
				<div class="form-field term-group">
					<label for="directory_type"><?php esc_html_e( 'Directory', 'directorist' ); ?></label>
					<p><?php esc_html_e( 'You can assign any one or more of the following directories to the location.', 'directorist' ); ?></p>
					<div class="directory_types-wrapper">
						<?php foreach ( $directories as $directory_id => $directory_name ) : ?>
							<div class="directory_type-group">
								<input type="checkbox" class="postform" name="directory_type[]" id="directory_type-<?php echo esc_attr( $directory_id ); ?>" value='<?php echo esc_attr( $directory_id ); ?>'/>
								<label for="directory_type-<?php echo esc_attr( $directory_id ); ?>"><?php echo esc_html( $directory_name ); ?></label>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>

			<div class="form-field term-group">
				<label for="atbdp-categories-image-id"><?php esc_html_e( 'Image', 'directorist' ); ?></label>
				<input type="hidden" id="atbdp-categories-image-id" name="image"/>
				<div id="atbdp-categories-image-wrapper"></div>
				<p>
					<input type="button" class="button button-secondary" id="atbdp-categories-upload-image" value="<?php esc_attr_e( 'Add Image', 'directorist' ); ?>"/>
				</p>
			</div>
			<?php
		}

		public function add_custom_taxonomy() {
			$this->register_category();
			$this->register_location();
			$this->register_tag();
		}

		protected function register_location() {
			$labels = array(
				'name'              => _x( 'Listing Locations', 'Location general name', 'directorist' ),
				'singular_name'     => _x( 'Listing Location', 'Location singular name', 'directorist' ),
				'search_items'      => __( 'Search Location', 'directorist' ),
				'all_items'         => __( 'All Locations', 'directorist' ),
				'parent_item'       => __( 'Parent Location', 'directorist' ),
				'parent_item_colon' => __( 'Parent Location:', 'directorist' ),
				'edit_item'         => __( 'Edit Location', 'directorist' ),
				'update_item'       => __( 'Update Location', 'directorist' ),
				'add_new_item'      => __( 'Add New Location', 'directorist' ),
				'new_item_name'     => __( 'New location name', 'directorist' ),
				'menu_name'         => __( 'Locations', 'directorist' ),
			);

			$args = array(
				'hierarchical'      => true,
				'show_in_rest'      => true,
				'labels'            => $labels,
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'public'            => true,
				'show_in_nav_menus' => true,
				'capabilities'      => array(
					'assign_terms' => get_post_type_object( ATBDP_POST_TYPE )->cap->publish_posts,
				),
			);

			$slug = ATBDP_LOCATION;
			if ( ! empty( $slug ) ) {
				$args['rewrite'] = array(
					'slug' => $slug,
				);
			}

			register_taxonomy( ATBDP_LOCATION, ATBDP_POST_TYPE, $args );
		}

		protected function register_category() {
			$labels = array(
				'name'              => _x( 'Listing Categories', 'Category general name', 'directorist' ),
				'singular_name'     => _x( 'Listing Category', 'Category singular name', 'directorist' ),
				'search_items'      => __( 'Search category', 'directorist' ),
				'all_items'         => __( 'All categories', 'directorist' ),
				'parent_item'       => __( 'Parent category', 'directorist' ),
				'parent_item_colon' => __( 'Parent category:', 'directorist' ),
				'edit_item'         => __( 'Edit category', 'directorist' ),
				'update_item'       => __( 'Update category', 'directorist' ),
				'add_new_item'      => __( 'Add New Category', 'directorist' ),
				'new_item_name'     => __( 'New category name', 'directorist' ),
				'menu_name'         => __( 'Categories', 'directorist' ),
			);

			$args = array(
				'hierarchical'      => true,
				'labels'            => $labels,
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'public'            => true,
				'show_in_nav_menus' => true,
				'capabilities'      => array(
					'assign_terms' => get_post_type_object( ATBDP_POST_TYPE )->cap->publish_posts,
				),
			);

			$slug = ATBDP_CATEGORY;
			if ( ! empty( $slug ) ) {
				$args['rewrite'] = array(
					'slug' => $slug,
				);
			}

			register_taxonomy( ATBDP_CATEGORY, ATBDP_POST_TYPE, $args );
		}

		protected function register_tag() {
			$labels = array(
				'name'              => _x( 'Listing Tags', 'Tag general name', 'directorist' ),
				'singular_name'     => _x( 'Listing Tag', 'Tag singular name', 'directorist' ),
				'search_items'      => __( 'Search tag', 'directorist' ),
				'all_items'         => __( 'All Tags', 'directorist' ),
				'parent_item'       => __( 'Parent tag', 'directorist' ),
				'parent_item_colon' => __( 'Parent tag:', 'directorist' ),
				'edit_item'         => __( 'Edit tag', 'directorist' ),
				'update_item'       => __( 'Update tag', 'directorist' ),
				'add_new_item'      => __( 'Add New Tag', 'directorist' ),
				'new_item_name'     => __( 'New tag name', 'directorist' ),
				'menu_name'         => __( 'Tags', 'directorist' ),
			);

			$args       = array(
				'hierarchical'      => false,
				'labels'            => $labels,
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'public'            => true,
				'show_in_nav_menus' => true,
				'capabilities'      => array(
					'assign_terms' => get_post_type_object( ATBDP_POST_TYPE )->cap->publish_posts,
				),
			);

			// get the rewrite slug from the user settings, if exist use it.
			$slug = ATBDP_TAGS;
			if ( ! empty( $slug ) ) {
				$args2['rewrite'] = array(
					'slug' => $slug,
				);
			}

			register_taxonomy( ATBDP_TAGS, ATBDP_POST_TYPE, $args );
		}

		public function register_category_columns( $columns ) {
			$new_columns = $columns;
			array_splice( $new_columns, 2 ); // in this way we could place our columns on the first place after the first checkbox.

			$new_columns['directorist_category_icon'] = __( 'Icon', 'directorist' );

			if ( directorist_is_multi_directory_enabled() ) {
				$new_columns['directorist_category_directory_type'] = __( 'Directory', 'directorist' );
			}

			unset( $columns['description'] );

			return array_merge( $new_columns, $columns );
		}

		public function register_location_columns( $columns ) {
			$new_columns = $columns;
			array_splice( $new_columns, 2 );

			if ( directorist_is_multi_directory_enabled() ) {
				$new_columns['directorist_location_directory_type'] = __( 'Directory', 'directorist' );
			}

			return array_merge( $new_columns, $columns );
		}

		public function add_category_column_data( $return_string, $column_name, $category_id ) {
			$icon = get_term_meta( $category_id, 'category_icon', true );

			if ( $column_name === 'directorist_category_icon' && $icon ) {
				return sprintf( '<span class="%s" style="font-size: 1.6em"></span>', esc_attr( $icon ) );
			}

			if ( $column_name === 'directorist_category_directory_type' && directorist_is_multi_directory_enabled() ) {
				$selected_directories = directorist_get_category_directory( $category_id );

				if ( empty( $selected_directories ) ) {
					return;
				}

				$directories = directorist_get_directories( array(
					'fields'  => 'id=>name',
				) );

				$directories = array_intersect_key( $directories, array_flip( $selected_directories ) );

				if ( ! empty( $directories ) ) {
					return implode( ', ', array_values( $directories ) );
				}
			}

			return $return_string;
		}

		public function add_location_column_data( $return_string, $column_name, $location_id ) {
			if ( $column_name === 'directorist_location_directory_type' && directorist_is_multi_directory_enabled() ) {
				$selected_directories = directorist_get_location_directory( $location_id );

				if ( empty( $selected_directories ) ) {
					return;
				}

				$directories = directorist_get_directories( array(
					'fields'  => 'id=>name',
				) );

				$directories = array_intersect_key( $directories, array_flip( $selected_directories ) );

				if ( ! empty( $directories ) ) {
					return implode( ', ', array_values( $directories ) );
				}
			}

			return $return_string;
		}


		public function display_terms_of_post( $post_id, $term_name = 'category' ) {
			global $post;
			$terms = get_the_terms( $post_id, $term_name );

			/* If terms were found. */
			if ( ! empty( $terms ) ) {

				$out = array();

				/* Loop through each term, linking to the 'edit posts' page for the specific term. */
				foreach ( $terms as $term ) :
					$term_url = add_query_arg(
						array(
							'post_type' => $post->post_type,
							$term_name  => $term->slug,
						),
						'edit.php'
					);

					$term_link_text = sanitize_term_field( 'name', $term->name, $term->term_id, $term_name, 'display' );
					?>
					<a href="<?php echo esc_url( $term_url ); ?>"><?php echo esc_html( $term_link_text ); ?></a>
					<?php
				endforeach;
			} /* If no terms were found, output a default message. */
			else {
				esc_html_e( 'No Category', 'directorist' );
			}
		}

		/**
		 * It returns a single high level term object of the given taxonomy
		 *
		 * @TODO; improve it later if possible
		 * @param int    $post_id The post ID whose taxonomy we are searching through for a term
		 * @param string $taxonomoy The name of the taxonomy whose term we are looking form
		 * @return WP_Term | false It returns a term object on success and false on failure
		 */
		public function get_one_high_level_term( $post_id, $taxonomoy = 'category' ) {
			$top_category = '';
			$terms        = get_the_terms( $post_id, $taxonomoy );
			if ( ! empty( $terms ) ) {
				foreach ( $terms as $term ) {
					if ( ! empty( $top_category ) ) {
						break; // vail out of the loop if we have found parent..
					}
					if ( $term->parent == 0 ) {
						$top_category = $term;
					}
				}
				if ( ! empty( $top_category ) ) {
					return $top_category;
				}
			}
			return false;

		}

		/**
		 * It returns a single deepest level term object of the given taxonomy
		 *
		 * @TODO; improve it later if possible
		 * @param int    $post_id The post ID whose taxonomy we are searching through for a term
		 * @param string $taxonomy The name of the taxonomy whose term we are looking form
		 * @return WP_Term | false It returns a term object on success and false on failure
		 */
		public function get_one_deepest_level_term( $post_id, $taxonomy = 'category' ) {

			// get all taxes for the current listing
			$locations = get_the_terms( $post_id, $taxonomy );

			// wrapper to hide any errors from top level categories or listings without locations
			if ( $locations && ! is_wp_error( $locations ) ) {

				// loop through each location
				foreach ( $locations as $location ) {
					// get the children (if any) of the current $location
					$children = get_categories(
						array(
							'taxonomy' => $taxonomy,
							'parent'   => $location->term_id,
						)
					);

					if ( count( $children ) == 0 ) {
						// if no children, then this ($location) is the deepest level location, if we want multiple deepest level location then we can sev the
						return $location;
					}
				}
			}
			return false;

		}

		public function get_listing_types() {
			return directorist_get_directories_for_template();
		}

		public function get_current_listing_type() {
			if ( ! empty( $_GET['directory_type'] ) ) {
				return sanitize_text_field( wp_unslash( $_GET['directory_type'] ) );
			}

			$directory_types = $this->get_listing_types();
			if ( empty( $directory_types ) ) {
				return '';
			}

			foreach ( $directory_types as $id => $type ) {
				$is_default = get_term_meta( $id, '_default', true );
				if ( $is_default ) {
					return $id;
				}
			}

			return array_key_first( $directory_types );
		}

		public function default_listing_type() {
			if ( ! directorist_is_multi_directory_enabled() || ( 1 == count( $this->get_listing_types() ) ) ) {
				return $this->get_current_listing_type();
			}
		}

		public function register_bulk_actions( $actions ) {
			$taxonomy = substr( current_filter(), 18 ); // Extract taxonomy name from current filter name.

			if ( directorist_is_multi_directory_enabled() && current_user_can( get_taxonomy( $taxonomy )->cap->edit_terms ) ) {
				$actions[ __( 'Directory', 'directorist') ] = array(
					'directory_reset_to_empty'   => __( 'Reset To Empty', 'directorist' ),
					'directory_reset_to_default' => __( 'Reset To Default', 'directorist' ),
					'directory_assign_all'     => __( 'Assign All Directories', 'directorist' ),
				);
			}

			return $actions;
		}

		public function handle_bulk_actions( $redirect_location, $action, $terms ) {
			$taxonomy = substr( current_filter(), 25 ); // Extract taxonomy name from current filter name.

			if ( ! directorist_is_multi_directory_enabled() || ! current_user_can( get_taxonomy( $taxonomy )->cap->edit_terms ) ) {
				return $redirect_location;
			}

			if ( $action === 'directory_reset_to_empty' ) {
				foreach ( $terms as $term ) {
					directorist_delete_term_directory( $term );
				}

				return add_query_arg( 'message', 7, $redirect_location );
			}

			if ( $action === 'directory_reset_to_default' ) {
				foreach ( $terms as $term ) {
					directorist_update_term_directory( $term, array( directorist_get_default_directory() ) );
				}

				return add_query_arg( 'message', 7, $redirect_location );
			}

			if ( $action === 'directory_assign_all' ) {
				$directory_ids = directorist_get_directories( array( 'fields' => 'ids' ) );

				foreach ( $terms as $term ) {
					directorist_update_term_directory( $term, $directory_ids );
				}

				return add_query_arg( 'message', 7, $redirect_location );
			}

			return $redirect_location;
		}

		public function add_term_updated_messages( $messages ) {
			$messages[ ATBDP_LOCATION ] = array(
				0 => '',
				1 => __( 'Location added.', 'directorist' ),
				2 => __( 'Location deleted.', 'directorist' ),
				3 => __( 'Location updated.', 'directorist' ),
				4 => __( 'Location not added.', 'directorist' ),
				5 => __( 'Location not updated.', 'directorist' ),
				6 => __( 'Locations deleted.', 'directorist' ),
				7 => __( 'Locations directory updated.', 'directorist' ),
			);

			$messages[ ATBDP_CATEGORY ] = array(
				0 => '',
				1 => __( 'Category added.', 'directorist' ),
				2 => __( 'Category deleted.', 'directorist' ),
				3 => __( 'Category updated.', 'directorist' ),
				4 => __( 'Category not added.', 'directorist' ),
				5 => __( 'Category not updated.', 'directorist' ),
				6 => __( 'Categories deleted.', 'directorist' ),
				7 => __( 'Categories directory updated.', 'directorist' ),
			);

			$messages[ ATBDP_TAGS ] = array(
				0 => '',
				1 => __( 'Tag added.', 'directorist' ),
				2 => __( 'Tag deleted.', 'directorist' ),
				3 => __( 'Tag updated.', 'directorist' ),
				4 => __( 'Tag not added.', 'directorist' ),
				5 => __( 'Tag not updated.', 'directorist' ),
				6 => __( 'Tags deleted.', 'directorist' ),
			);

			return $messages;
		}

		public function add_directory_filter( $filters ) {
			if ( ! directorist_is_multi_directory_enabled() ) {
				return $filters;
			}

			$directories = directorist_get_directories( array(
				'fields'  => 'id=>name',
				'order'   => 'asc',
				'orderby' => 'id'
			) );

			if ( is_wp_error( $directories ) ) {
				return $filters;
			}

			$current_directory = (int) ( $_GET['directory'] ?? 0 );

			$filters = array(
				'directory-filter-all' => sprintf(
					'<a href="%1$s" class="%2$s">%3$s</a>',
					esc_url( add_query_arg( 'directory', -1 ) ),
					$current_directory === -1 ? 'current' : '',
					esc_html__( 'All', 'directorist' )
				)
			);

			foreach ( $directories as $directory_id => $directory_name ) {
				$filters[ 'directory-filter-'. $directory_id ] = sprintf(
					'<a href="%1$s" class="%2$s">%3$s</a>',
					esc_url( add_query_arg( 'directory', $directory_id ) ),
					$current_directory === $directory_id ? 'current' : '',
					esc_html( $directory_name )
				);
			}

			return $filters;
		}

		public function delete_directory_to_category_location_relation( $directory_id ) {
			delete_metadata( 'term', null, '_directory_type_' . $directory_id, '', true );
		}
	}
endif;