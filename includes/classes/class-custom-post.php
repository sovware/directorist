<?php
defined( 'ABSPATH' ) || die( 'Direct access is not allowed.' );

if ( ! class_exists( 'ATBDP_Custom_Post' ) ) :

	/**
	 * Class ATBDP_Custom_Post
	 */
	class ATBDP_Custom_Post {

		public function __construct() {
			// Add the listing post type and taxonomies
			add_action( 'init', array( $this, 'register_new_post_types' ), 5 );

			// add new columns for ATBDP_SHORT_CODE_POST_TYPE
			add_filter( 'manage_' . ATBDP_POST_TYPE . '_posts_columns', array( $this, 'add_new_listing_columns' ) );
			add_action( 'manage_' . ATBDP_POST_TYPE . '_posts_custom_column', array( $this, 'manage_listing_columns' ), 10, 2 );
			/*make column sortable*/
			add_filter( 'manage_edit-' . ATBDP_POST_TYPE . '_sortable_columns', array( $this, 'make_sortable_column' ), 10, 1 );
			add_filter( 'post_row_actions', array( $this, 'add_listing_id_row' ), 10, 2 );

			add_filter( 'enter_title_here', array( $this, 'change_title_text' ) );
			add_filter( 'post_row_actions', array( $this, 'add_row_actions_for_quick_view' ), 10, 2 );
			add_filter( 'load-edit.php', array( $this, 'work_row_actions_for_quick_view' ), 10, 2 );

			// bulk directory type assign
			add_action( 'quick_edit_custom_box', array( $this, 'on_quick_edit_custom_box' ), 10, 2 );
			add_action( 'bulk_edit_custom_box', array( $this, 'on_quick_edit_custom_box' ), 10, 2 );
			add_action( 'save_post', array( $this, 'save_quick_edit_custom_box' ) );

			// Customize listing slug
			if ( get_directorist_option( 'single_listing_slug_with_directory_type', false ) ) {
				add_filter( 'post_type_link', array( $this, 'customize_listing_slug' ), 20, 2 );
				// add_filter( 'post_link', array( $this, 'customize_listing_slug' ), 20, 2 );
			}

			add_action( 'admin_footer', array( $this, 'quick_edit_scripts' ) );

			add_action( 'init', array( $this, 'register_post_status' ) );
		}

		public function register_post_status() {
			register_post_status(
				'expired',
				array(
					'label'       => _x( 'Expired', 'post status', 'directorist' ),
					'protected'   => true,
					/* translators: %s: Number of expired listings. */
					'label_count' => _n_noop(
						'Expired <span class="count">(%s)</span>',
						'Expired <span class="count">(%s)</span>',
						'directorist'
					),
				)
			);
		}

		public function quick_edit_scripts() {
			global $current_screen;

			if ( ! isset( $current_screen ) || 'edit-at_biz_dir' !== $current_screen->id ) {
				return;
			}
			?>
			<script>
			jQuery( function( $ ) {
				var wp_inline_edit_function = inlineEditPost.edit;

				// we overwrite the it with our own
				inlineEditPost.edit = function( post_id ) {
					// let's merge arguments of the original function
					wp_inline_edit_function.apply( this, arguments );

					// get the post ID from the argument
					if ( typeof( post_id ) === 'object' ) { // if it is object, get the ID number
						post_id = Number( this.getId( post_id ) );
					}

					// add rows to variables
					var $edit_row = $( '#edit-' + post_id );
					var $post_row = $( '#post-' + post_id );
					var directory_type = $( '.column-directory_type', $post_row ).text().trim();
					var $directory_select = $( 'select[name="directory_type"]', $edit_row );
					var $selected_option = $directory_select.find('option').filter(function(index, element) {
						return element.textContent.trim() === directory_type;
					});

					if ($selected_option.length > 0) {
						$directory_select.val($selected_option[0].value);
					}
				}
			});
			</script>
			<?php
		}

		// customize_listing_slug
		public function customize_listing_slug( $post_link, $post ) {
			$post_link = ATBDP_Permalink::get_listing_permalink( $post->ID, $post_link );
			return $post_link;
		}

		public function save_quick_edit_custom_box( $listing_id ) {
			if ( ! directorist_verify_nonce() || ! directorist_is_listing_post_type( $listing_id ) ) {
				return;
			}

			$action = isset( $_REQUEST['action'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['action'] ) ) : '';
			if ( $action !== 'inline-save' ) {
				return;
			}

			if ( ! current_user_can( get_post_type_object( ATBDP_POST_TYPE )->cap->edit_posts ) ) {
				return;
			}

			$directory_id = ! empty( $_REQUEST['directory_type'] ) ? absint( wp_unslash( $_REQUEST['directory_type'] ) ) : 0;

			if ( ! directorist_is_directory( $directory_id ) ) {
				return;
			}

			$should_update_directory_type = apply_filters( 'directorist_should_update_directory_type', (bool) $directory_id );

			if ( $should_update_directory_type ) {
				directorist_set_listing_directory( $listing_id, $directory_id );
			}
		}

		public function on_quick_edit_custom_box( $column_name, $post_type ) {
			if ( ATBDP_POST_TYPE !== $post_type || 'directory_type' !== $column_name ) {
				return;
			}
			?>
			<fieldset class="inline-edit-col-right" style="margin-top: 0;">
				<div class="inline-edit-group wp-clearfix">
					<?php wp_nonce_field( directorist_get_nonce_key(), 'directorist_nonce' ); ?>
					<label class="inline-edit-directory-type alignleft">
						<span class="title"><?php esc_html_e( 'Directory', 'directorist' ); ?></span>
						<select name="directory_type">
							<option value="">— <?php esc_html_e( 'Select type', 'directorist' ); ?> —</option>
							<?php
							$listing_types = directorist_get_directories();

							foreach ( $listing_types as $listing_type ) { ?>
								<option value="<?php echo esc_attr( $listing_type->term_id ); ?>"><?php echo esc_html( $listing_type->name ); ?></option>
							<?php } ?>
						</select>
					</label>
				</div>
			</fieldset>
			<?php
		}

		public function add_cpt_to_pll( $post_types, $hide ) {
			/*
			 echo '<pre>';
			var_dump($post_types);
			echo '</pre>';*/

			if ( $hide ) {
				// hides 'my_cpt' from the list of custom post types in Polylang settings
				unset( $post_types[ ATBDP_POST_TYPE ] );
			} else {
				// enables language and translation management for ATBDP_POST_TYPE
				$post_types[ ATBDP_POST_TYPE ] = ATBDP_POST_TYPE;
			}

			return $post_types;
		}

		public function work_row_actions_for_quick_view() {
			$nonce     = ! empty( $_REQUEST['_wpnonce'] ) ? wp_unslash( $_REQUEST['_wpnonce'] ) : ''; // @codingStandardsIgnoreLine
			$update_id = ! empty( $_REQUEST['update_id'] ) ? absint( wp_unslash( $_REQUEST['update_id'] ) ) : 0;

			if ( wp_verify_nonce( $nonce, 'quick-publish-action' ) && $update_id && is_admin() ) {
				$my_post                = array();
				$my_post['ID']          = $update_id;
				$my_post['post_status'] = 'publish';
				wp_update_post( $my_post );
				/**
				 * @since 5.4.0
				 */
				do_action( 'atbdp_listing_published', $my_post['ID'] ); // for sending email notification
				echo '<script>window.location="' . esc_url( admin_url() . 'edit.php?post_type=at_biz_dir' ) . '"</script>';
			}
		}

		/**
		 * Remove quick edit.
		 *
		 * @param array   $actions An array of row action links.
		 * @param WP_Post $post The post object.
		 * @return     array      $actions    Updated array of row action links.
		 * @since     1.0.0
		 * @access   public
		 */
		public function add_row_actions_for_quick_view( $actions, $post ) {
			if ( ATBDP_POST_TYPE !== $post->post_type ) {
				return $actions;
			}

			if ( get_post_status( $post ) !== 'publish' && current_user_can( 'publish_at_biz_dirs' ) ) {
				$nonce              = wp_create_nonce( 'quick-publish-action' );
				$link               = admin_url( "edit.php?update_id={$post->ID}&_wpnonce={$nonce}&post_type=at_biz_dir" );
				$actions['publish'] = "<a href='$link' style='color: #4caf50; font-weight: bold'>Publish</a>";
			}

			return $actions;
		}

		/**
		 * This function will register our custom post(s)
		 * Initiate registrations of post types and taxonomies.
		 */
		public function register_new_post_types() {
			$this->register_post_type();
		}

		/**
		 * Register the custom post type.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/register_post_type
		 */
		protected function register_post_type() {
			// Args for ATBDP_POST_TYPE, here any constant may not be available because this function will be called from the
			// register_activation_hook .
			$labels = array(
				'menu_name'                => __( 'Directory Listings', 'directorist' ),
				'name_admin_bar'           => __( 'Listing', 'directorist' ),
				'name'                     => _x( 'Listings', 'post type general name', 'directorist' ),
				'singular_name'            => _x( 'Listing', 'post type singular name', 'directorist' ),
				'add_new'                  => _x( 'Add New', 'listing', 'directorist' ),
				'add_new_item'             => __( 'Add New Listing', 'directorist' ),
				'edit_item'                => __( 'Edit Listing', 'directorist' ),
				'update_item'              => __( 'Update Listing', 'directorist' ),
				'new_item'                 => __( 'New Listing', 'directorist' ),
				'view_item'                => __( 'View Listing', 'directorist' ),
				'view_items'               => __( 'View Listings', 'directorist' ),
				'search_items'             => __( 'Search Listings', 'directorist' ),
				'not_found'                => __( 'No listings found.', 'directorist' ),
				'not_found_in_trash'       => __( 'No listings found in Trash.', 'directorist' ),
				'all_items'                => __( 'All Listings', 'directorist' ),
				'archives'                 => __( 'Listing Archives', 'directorist' ),
				'attributes'               => __( 'Listing Attributes', 'directorist' ),
				'insert_into_item'         => __( 'Insert into listing', 'directorist' ),
				'uploaded_to_this_item'    => __( 'Uploaded to this listing', 'directorist' ),
				'featured_image'           => _x( 'Featured image', 'listing', 'directorist' ),
				'set_featured_image'       => _x( 'Set featured image', 'listing', 'directorist' ),
				'remove_featured_image'    => _x( 'Remove featured image', 'listing', 'directorist' ),
				'use_featured_image'       => _x( 'Use as featured image', 'listing', 'directorist' ),
				'filter_items_list'        => __( 'Filter listings list', 'directorist' ),
				'items_list_navigation'    => __( 'Listings list navigation', 'directorist' ),
				'items_list'               => __( 'Listings list', 'directorist' ),
				'item_published'           => __( 'Listing published.', 'directorist' ),
				'item_published_privately' => __( 'Listing published privately.', 'directorist' ),
				'item_reverted_to_draft'   => __( 'Listing reverted to draft.', 'directorist' ),
				'item_trashed'             => __( 'Listing trashed.', 'directorist' ),
				'item_scheduled'           => __( 'Listing scheduled.', 'directorist' ),
				'item_updated'             => __( 'Listing updated.', 'directorist' ),
				'item_link'                => _x( 'Listing Link', 'navigation link block title', 'directorist' ),
				'item_link_description'    => _x( 'A link to a listing.', 'navigation link block description', 'directorist' ),
			);

			$args = array(
				'label'               => __( 'Directory Listing', 'directorist' ),
				'description'         => __( 'Directory listings', 'directorist' ),
				'labels'              => $labels,
				'supports'            => array( 'title', 'editor', 'author' ),
				// 'show_in_rest'         => true,
				'taxonomies'          => array( ATBDP_CATEGORY, ATBDP_LOCATION, ATBDP_TAGS, ATBDP_DIRECTORY_TYPE ),
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => current_user_can( 'edit_others_at_biz_dirs' ) ? true : false, // show the menu only to the admin
				'show_in_menu'        => true,
				'menu_position'       => 20,
				'menu_icon'           => DIRECTORIST_ASSETS . 'images/menu_icon.png',
				'show_in_admin_bar'   => true,
				'show_in_nav_menus'   => true,
				'can_export'          => true,
				'has_archive'         => false,
				'exclude_from_search' => false,
				'publicly_queryable'  => true,
				'capability_type'     => ATBDP_POST_TYPE,
				'map_meta_cap'        => true, // set this true, otherwise, even admin will not be able to edit this post. WordPress will map cap from edit_post to edit_at_biz_dir etc,
				'menu_position'       => 5,
			);

			$slug = get_directorist_option( 'atbdp_listing_slug', 'directory' );

			if ( get_directorist_option( 'single_listing_slug_with_directory_type', false ) ) {
				$slug = ATBDP_Permalink::get_listing_slug();
			}

			if ( ! empty( $slug ) ) {
				$args['rewrite'] = array(
					'slug'       => $slug,
					'with_front' => false,
				);
			}

			/**
			 * @since 6.2.3
			 * @package Directorist
			 * @param $args
			 */
			$arguments = apply_filters( 'atbdp_register_listing_post_type_arguments', $args );
			register_post_type( ATBDP_POST_TYPE, $arguments );

			// the flush_rewrite_rules() should never be called on every init hook every time a page loads.
			// Rather we should use it only once at the time of the plugin activation.
			// flush_rewrite_rules();
		}

		public function add_new_listing_columns( $columns ) {
			$columns          = array();
			$columns['cb']    = '<input type="checkbox" />';
			$columns['title'] = __( 'Name', 'directorist' );
			if ( directorist_is_multi_directory_enabled() ) {
				$columns['directory_type'] = __( 'Directory', 'directorist' );
			}
			$columns['atbdp_location'] = __( 'Location', 'directorist' );
			$columns['atbdp_category'] = __( 'Categories', 'directorist' );
			$columns['atbdp_author']   = __( 'Author', 'directorist' );
			$columns['atbdp_status']   = __( 'Status', 'directorist' );
			if ( directorist_is_featured_listing_enabled() || is_fee_manager_active() ) {
				$columns['atbdp_featured'] = __( 'Featured', 'directorist' );
			}
			$subscribed_package_id      = get_user_meta( get_current_user_id(), '_subscribed_users_plan_id', true );
			$num_featured_unl           = get_post_meta( $subscribed_package_id, 'num_featured_unl', true );
			$num_featured               = get_post_meta( $subscribed_package_id, 'num_featured', true );
			$featured_submited          = get_user_meta( get_current_user_id(), '_featured_type', true ) ? (int) get_user_meta( get_current_user_id(), '_featured_type', true ) : 1;
			$featured_available_in_plan = ( $num_featured - $featured_submited );
			if ( is_fee_manager_active() && $featured_available_in_plan > 1 || $num_featured_unl ) {
				$columns['atbdp_featured'] = __( 'Featured', 'directorist' );
			}
			$columns['atbdp_expiry_date'] = __( 'Expires on', 'directorist' );
			$columns['atbdp_date']        = __( 'Created on', 'directorist' );

			return apply_filters( 'atbdp_add_new_listing_column', $columns );
		}

		public function manage_listing_columns( $column_name, $post_id ) {
			/*@TODO; Next time we can add image column too. */
			$date_format = get_option( 'date_format' );
			$time_format = get_option( 'time_format' );

			switch ( $column_name ) {
				case 'atbdp_location':
					$locations = directorist_get_object_terms( $post_id, ATBDP_LOCATION );
					foreach ( $locations as $location ) {
						printf(
							'<a href="%1$s">%2$s</a><br>',
							esc_url( ATBDP_Permalink::atbdp_get_location_page( $location ) ),
							esc_html( $location->name )
						);
					}
					break;

				case 'atbdp_category':
					$categories = directorist_get_object_terms( $post_id, ATBDP_CATEGORY );
					foreach ( $categories as $category ) {
						printf(
							'<a href="%1$s">%2$s</a><br>',
							esc_url( ATBDP_Permalink::atbdp_get_category_page( $category ) ),
							esc_html( $category->name )
						);
					}
					break;

				case 'directory_type':
					$directory = directorist_get_object_terms( $post_id, ATBDP_TYPE );
					if ( count( $directory ) > 0 ) {
						$directory = current( $directory );
						// $directory_config = get_term_meta( $directory->term_id, 'general_config', true );
						// if ( is_array( $directory_config ) && ! empty( $directory_config['icon'] ) ) {
						// printf( '<span class="%1$s"></span>', esc_attr( $directory_config['icon'] ) );
						// }
						printf( '<span>%1$s</span>', esc_html( $directory->name ) );
					}
					break;

				case 'atbdp_author':
					$args = array(
						'post_type' => get_post_field( 'post_type' ),
						'author'    => get_post_field( 'post_author' ),
					);
					printf(
						'<a href="%1$s" title="%2$s">%3$s</a>',
						esc_url( add_query_arg( $args, 'edit.php' ) ),
						/* translators: 1: Author name */
						sprintf( esc_attr_x( 'Filter by %1$s', 'Author filter link', 'directorist' ), get_the_author() ),
						get_the_author()
					);
					break;

				case 'atbdp_status':
					// TODO: Status has been migrated, remove related code.
					// $status = get_post_meta( $post_id, '_listing_status', true );
					// $status = ( $status !== 'post_status' ? $status : get_post_status( $post_id ) );

					$status = get_post_status( $post_id );

					if ( $status === 'publish' && get_post_meta( $post_id, '_listing_status', true ) === 'renewal' ) {
						$status_label = _x( 'Renewal', 'Noun: listing status', 'directorist' );
					} else {
						$status_label = get_post_status_object( $status )->label;
					}

					echo esc_html( $status_label );
					break;

				case 'atbdp_featured':
					$is_featured = (bool) get_post_meta( $post_id, '_featured', true );
					if ( $is_featured ) {
						echo '<i class="fa fa-check-circle" style="margin-left: 20px; color:#46b450; font-size: 1.2em"></i>';
					}
					break;

				case 'atbdp_expiry_date':
					$never_exp = get_post_meta( $post_id, '_never_expire', true );
					if ( ! empty( $never_exp ) ) {
						esc_html_e( 'Never Expires', 'directorist' );
					} else {
						$expiry_date = get_post_meta( $post_id, '_expiry_date', true );
						echo esc_html( date_i18n( "$date_format @ $time_format", strtotime( $expiry_date ) ) );
					}
					break;
				case 'atbdp_date':
					$time = get_the_time( 'U' );
					printf(
						/* translators: 1: Creation date. 2: Human diff time */
						esc_html__( '%1$s (%2$s ago)', 'directorist' ),
						date_i18n( $date_format, $time ), // @codingStandardsIgnoreLine.
						human_time_diff( $time ) // @codingStandardsIgnoreLine.
					);
					break;
			}
			/*
					 * since 4.2.0
					 */
			apply_filters( 'atbdp_add_new_listing_column_content', $column_name, $post_id );
		}

		public function make_sortable_column( $columns ) {
			$columns['atbdp_author'] = 'author';

			return $columns;
		}

		/**
		 * Change the placeholder of title input box
		 *
		 * @param string $title Name of the Post Type
		 *
		 * @return string Returns modified title
		 */
		public function change_title_text( $title ) {
			global $typenow;
			if ( ATBDP_POST_TYPE == $typenow ) {
				$title_placeholder = get_directorist_option( 'title_placeholder', __( 'Enter a title', 'directorist' ) );
				$title             = ! empty( $title_placeholder ) ? esc_attr( $title_placeholder ) : __( 'Enter a title', 'directorist' );
			}

			return $title;
		}

		/**
		 * It adds an ID row on the listings list page
		 *
		 * @param array   $actions The array of post actions
		 * @param WP_Post $post The current post post
		 * @return array    $actions        It returns the array of post actions after adding an ID row
		 */
		public function add_listing_id_row( $actions, WP_Post $post ) {
			if ( ATBDP_POST_TYPE != $post->post_type ) {
				return $actions;
			}

			return array_merge( array( 'ID' => "ID: {$post->ID}" ), $actions );
		}
	}

endif;
