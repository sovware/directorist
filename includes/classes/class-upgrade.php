<?php

use Directorist\Core\API;
use Directorist\Multi_Directory\Multi_Directory_Manager;

// it handles directorist upgrade
class ATBDP_Upgrade
{
	public $upgrade_notice_id       = 'migrate_to_7';

	public $legacy_notice_id        = 'directorist_legacy_template';

	public $directorist_notices     = [];

	public $directorist_migration   = [];

	public function __construct()
	{
		if ( !is_admin() ) return;

		add_action('admin_init', array($this, 'configure_notices'));

		add_action('directorist_search_setting_sections', array($this, 'support_themes_hook'));

		add_action('admin_notices', array($this, 'upgrade_notice'), 100);

		add_action('directorist_before_settings_panel_header', array($this, 'promo_banner') );

		add_action('directorist_before_all_directory_types', array($this, 'promo_banner') );

		// add_action('directorist_before_directory_type_edited', array($this, 'promo_banner') );

		add_action( 'admin_notices', array( $this, 'bfcm_notice') );

		add_action( 'admin_init', [ $this, 'v8_force_migration' ] );

	}

	public function v8_force_migration() {

		if( get_option( 'directorist_v8_force_migrated' ) ) {
			return;
		}

		$listings = get_posts( 
			[
				'post_type' => 'at_biz_dir', 
				'posts_per_page' => 1,
				'fields' => 'ids'
			] 
		);

		if( empty( $listings ) ) {
			update_option( 'directorist_v8_force_migrated', true );
			return;
		}

		$this->run_v8_migration();

		update_option( 'directorist_v8_force_migrated', true );
	}

	public function run_v8_migration() {
	
		//create account page
		$options = get_option( 'atbdp_option' );
		$account = wp_insert_post(
			array(
				'post_title' 	 => 'Sign In',
				'post_content' 	 => '[directorist_signin_signup]',
				'post_status' 	 => 'publish',
				'post_type' 	 => 'page',
				'comment_status' => 'closed'
			)
		);
	
		if ( $account ) {
			$options['signin_signup_page'] = (int) $account;
			$options['marker_shape_color'] 	= '#444752';
			$options['marker_icon_color'] 	= '#ffffff';
			
			update_option( 'atbdp_option', $options );
		}
		// need to update adons kit for elementor
		$path = WP_PLUGIN_DIR . '/addonskit-for-elementor/addonskit-for-elementor.php';

		if ( did_action( 'elementor/loaded' ) && ! file_exists( $path ) ) {
			
			directorist_download_plugin( [ 'url' => 'https://downloads.wordpress.org/plugin/addonskit-for-elementor.zip' ] );
	
			if ( ! is_plugin_active( $path ) ){
				activate_plugin( $path );
			}
		}

		$directory_types = get_terms([
			'taxonomy'   => ATBDP_DIRECTORY_TYPE,
			'hide_empty' => false,
		]);

	
		if ( is_wp_error( $directory_types ) || empty( $directory_types ) ) {
			return;
		}
	
		foreach ( $directory_types as $directory_type ) {
	
			$this->search_field_label_migration( $directory_type->term_id );

			// backup the builder data
			Multi_Directory_Manager::builder_data_backup( $directory_type->term_id );
			//migrate custom field
			Multi_Directory_Manager::migrate_custom_field( $directory_type->term_id );
			//migrate review settings
			Multi_Directory_Manager::migrate_review_settings( $directory_type->term_id );
			//migrate contact form
			Multi_Directory_Manager::migrate_contact_owner_settings( $directory_type->term_id );
			//migrate related listing settings
			Multi_Directory_Manager::migrate_related_listing_settings( $directory_type->term_id );
			//migrate privacy policy
			Multi_Directory_Manager::migrate_privacy_policy( $directory_type->term_id );
	
			//migrate builder single listing header
			$new_structure   = [];
			$header_contents = get_term_meta( $directory_type->term_id, 'single_listing_header', true );
	
			if ( empty( $header_contents ) ) {
				continue;
			}
	
			$description = ! empty( $header_contents['options']['content_settings']['listing_description']['enable'] ) ? $header_contents['options']['content_settings']['listing_description']['enable'] : false;
			$tagline     = ! empty( $header_contents['options']['content_settings']['listing_title']['enable_tagline'] ) ? $header_contents['options']['content_settings']['listing_title']['enable_tagline'] : false;
			$contents    = get_term_meta( $directory_type->term_id, 'single_listings_contents', true );
	
			if ( $description ) {
	
				$contents['fields']['description'] = [
					"icon" => "las la-tag",
					"widget_group" => "preset_widgets",
					"widget_name" => "description",
					"original_widget_key" => "description",
					"widget_key" => "description"
				];
	
				$details = [
					"type" => "general_group",
					"label" => "Description",
					"fields" => [
						"description"
					],
					"section_id" => "1627188303" . $directory_type->term_id
				];
	
				array_unshift( $contents['groups'], $details );
	
				update_term_meta( $directory_type->term_id, 'single_listings_contents', $contents );
	
			}
	
			if ( empty( $header_contents['listings_header'] ) ) {
				continue;
			}
	
			foreach ( $header_contents['listings_header'] as $section_name => $widgets ) {
	
				if ( 'quick_actions' === $section_name ) {
					$quick_widget = [
						"type" => "placeholder_group",
						"placeholderKey" => "quick-widgets-placeholder",
						"placeholders" => [
							[
								"type" => "placeholder_group",
								"placeholderKey" => "quick-info-placeholder",
								"selectedWidgets" => [
									[
										"type" => "button",
										"label" => "Back",
										"widget_name" => "back",
										"widget_key" => "back"
									]
								]
							],
							[
								"type" => "placeholder_group",
								"placeholderKey" => "quick-action-placeholder",
								"selectedWidgets" => $widgets,
							]
						]
					];
	
					array_push( $new_structure, $quick_widget );
				}
	
	
				if ( 'thumbnail' === $section_name ) {
					$footer_thumbnail = ! empty( $widgets[0]['footer_thumbail'] ) ? $widgets[0]['footer_thumbail'] : true;
					$slider_widget = [
						"type" => "placeholder_item",
						"placeholderKey" => "slider-placeholder",
						"selectedWidgets" => [
							[
								"type" => "thumbnail",
								"label" => "Listing Image/Slider",
								"widget_name" => "slider",
								"widget_key" => "slider",
								'options'  => [
									'title'  => __( 'Listings Slider Settings', 'directorist' ),
									'fields' => [
										'footer_thumbnail' => [
											'type'  => 'toggle',
											'label' => __( 'Enable Footer Thumbnail', 'directorist' ),
											'value' => $footer_thumbnail,
										],
									],
								],
							]
						]
					];
	
					array_push( $new_structure, $slider_widget );
				}
	
				if ( 'quick_info' === $section_name ) {
	
					$title_widget = [
						"type" => "placeholder_item",
						"placeholderKey" => "listing-title-placeholder",
						"selectedWidgets" => [
							[
								"type" => "title",
								"label" => "Listing Title",
								"widget_name" => "title",
								"widget_key" => "title",
								'options' => [
									'title' => __( "Listing Title Settings", "directorist" ),
									'fields' => [
										'enable_tagline' => [
											'type' => "toggle",
											'label' => __( "Show Tagline", "directorist" ),
											'value' => $tagline,
										],
									],
								],
							]
						]
					];
	
					array_push( $new_structure, $title_widget );
	
					$more_widget = [
						"type" => "placeholder_item",
						"placeholderKey" => "more-widgets-placeholder",
						"selectedWidgets" => $widgets,
					];
	
					array_push( $new_structure, $more_widget );
				}
	
			}
	
			$new_structure = apply_filters( 'directorist_single_listing_header_migration_data', $new_structure, $header_contents );
	
			update_term_meta( $directory_type->term_id, 'single_listing_header', $new_structure );

		}
	}

	private function search_field_label_migration( $directory_id ) {
		$search_fields = get_term_meta( $directory_id, 'search_form_fields', true );
		$fields        = empty( $search_fields['fields'] ) ? [] : $search_fields['fields'];

		foreach ( $fields as $key => $field ) {
			$placeholder    = empty( $field['placeholder'] ) ? '' : $field['placeholder'];
			$label          = empty( $field['label'] ) ? $placeholder : $field['label'];
			$field['label'] = $label;

			$search_fields['fields'][ $key ] = $field;
		}

		update_term_meta( $directory_id, 'search_form_fields', $search_fields );
	}

	public function support_themes_hook( $data ) {
		$theme = wp_get_theme( is_child_theme() ? get_template() : '' );
	
		// Check if theme author is 'wpWax'
		if ( $theme->display( 'Author', false ) === 'wpWax' ) {

			if ( ( 'Pixetiq' !== $theme['Name'] ) && ( version_compare( 2, $theme['Version'], '>' ) ) ) {
				$data['search_form'] = [
					'fields' => [],
				];
			}

			if ( ( ( 'Direo' === $theme['Name'] ) || ( 'dList' === $theme['Name'] ) ) && ( version_compare( 3, $theme['Version'], '>' ) ) ) {
				$data['search_form'] = [
					'fields' => [],
				];
			}
		}
	
		return $data;
	}	

	public function is_pro_user() {
		$plugin = get_user_meta( get_current_user_id(), '_plugins_available_in_subscriptions', true );
		$theme  = get_user_meta( get_current_user_id(), '_themes_available_in_subscriptions', true );

		if( $plugin || $theme ) {
			return true;
		} else {
			return false;
		}
	}

	public function promo_banner(){
		if ( self::can_manage_plugins() && ! self::is_pro_user() ) {
			ATBDP()->load_template( 'admin-templates/admin-promo-banner' );
		}
	}

	protected static function can_manage_plugins() {
		return ( current_user_can( 'install_plugins' ) || current_user_can( 'manage_options' ) );
	}

	public function bfcm_notice() {
		if ( ! self::can_manage_plugins() || self::is_pro_user() ) {
			return;
		}

		$response_body  = self::promo_remote_get();
		$display        = ! empty( $response_body->promo_2_display ) ? $response_body->promo_2_display : '';
		$text           = ! empty( $response_body->promo_2_text ) ? $response_body->promo_2_text : '';
		$version        = ! empty( $response_body->promo_2_version ) ? $response_body->promo_2_version : '';
		$link           = ! empty( $response_body->get_now_button_link ) ? self::promo_link( $response_body->get_now_button_link ) : '';

		$closed_version = get_user_meta( get_current_user_id(), 'directorist_promo2_closed_version', true );

		if ( !$display || $version == $closed_version || !$text ) {
			return;
		}

		$text = str_replace( '{{link}}', $link, $text );

		$dismiss_url = add_query_arg(
			array(
				'directorist_promo2_closed_version' => $version,
				'directorist_promo_nonce'          => wp_create_nonce( 'directorist_promo_nonce' ),
			),
			atbdp_get_current_url()
		);

		$notice = '<div class="notice notice-info is-dismissible"><p style="font-size: 16px;">' . $text . '</p><a href="'.esc_url( $dismiss_url ).'" class="notice-dismiss" style="text-decoration: none;"><span class="screen-reader-text">'. __( 'Dismiss this notice.', 'directorist' ) .'</span></a></div>';

		echo wp_kses_post( $notice );
	}

	public static function promo_remote_get() {
		return API::get_promotion();
	}

	public function upgrade_notice() {
		if ( ! self::can_manage_plugins() ) {
			return;
		}

		// v8.0 compatibility notice
		// theme check
		$theme = wp_get_theme( is_child_theme() ? get_template() : '' );
		if( ( $theme->display( 'Author', FALSE ) === 'wpWax' ) && ( version_compare( 2, $theme['Version'], '>' ) ) && ( 'Pixetiq' !== $theme['Name'] ) ) {
			// show theme
			$this->v8_theme_upgrade_notice( $theme );
		}
		// extension check
		$plugins = get_plugins();
		$outdated_extensions = [];
		if( ! empty( $plugins ) ) {
			foreach( $plugins as $key => $plugin ) {
				if( ( str_starts_with($key, 'directorist') ) && ( version_compare( 2, $plugin['Version'], '>' ) ) ) {
					$outdated_extensions[] = $plugin['Name'];
				}
			}
		}

		if( ! empty( $outdated_extensions ) ) {
			$this->v8_extension_upgrade_notice( $outdated_extensions );
		}

	}

	public function v8_extension_upgrade_notice( $list = [] ) {
		if ( ! self::can_manage_plugins() ) {
			return;
		}

		$text = '';
		$link = 'https://directorist.com/blog/directorist-version-8-0/';
		$membership_page = admin_url('edit.php?post_type=at_biz_dir&page=atbdp-extension');

		$wp_rollback = admin_url( 'plugin-install.php?s=rollback&tab=search&type=term' );

		$text .= sprintf( __( '<p class="directorist__notice_new"><span style="font-size: 16px;">📣 Directorist Extension Compatibility Notice!</span><br/> Congratulations and welcome to Directorist v8.0 with some cool <a href="%s" target="_blank">new features</a>.You are using %s of our extensions which are not compatible with v8.0. Please <a target="_blank" href="%s">update your extensions</a> </p>', 'directorist' ), $link, count( $list ), $membership_page );

		$text .= sprintf(
			__( '<p class="directorist__notice_new_action">Mistakenly updated? Use <a target="_blank" href="%s">WP Rollback</a> to install your old Directorist</p>', 'directorist' ),
			$wp_rollback
		);

		$notice = '<div class="notice notice-warning is-dismissible directorist-plugin-updater-notice" style="padding-top: 5px;padding-bottom: 5px;">' . $text . '</div>';

		echo wp_kses_post( $notice );
	}

	public function v8_theme_upgrade_notice( $theme ) {
		if ( ! self::can_manage_plugins() ) {
			return;
		}

		$text = '';
		$link = 'https://directorist.com/blog/directorist-version-8-0/';
		$membership_page = admin_url('edit.php?post_type=at_biz_dir&page=atbdp-extension');

		$wp_rollback = admin_url( 'plugin-install.php?s=rollback&tab=search&type=term' );

		$text .= sprintf( __( '<p class="directorist__notice_new"><span style="font-size: 16px;">📣 Directorist Theme Compatibility Notice!</span><br/> Congratulations and welcome to Directorist v8.0 with some cool <a href="%s" target="_blank">new features</a>.Please update <a target="_blank" href="%s">%s theme</a> </p>', 'directorist' ), $link, $membership_page, $theme['Name'] );

		$text .= sprintf(
			__( '<p class="directorist__notice_new_action">Mistakenly updated? Use <a target="_blank" href="%s">WP Rollback</a> to install your old Directorist</p>', 'directorist' ),
			$wp_rollback
		);

		$notice = '<div class="notice notice-warning is-dismissible directorist-plugin-updater-notice" style="padding-top: 5px;padding-bottom: 5px;">' . $text . '</div>';

		echo wp_kses_post( $notice );
	}

	public function configure_notices() {
		if ( ! self::can_manage_plugins() ) {
			return;
		}

		$this->directorist_notices      = get_option( 'directorist_notices' );

		if ( isset( $_GET['close-directorist-promo-version'], $_GET['directorist_promo_nonce'] ) && wp_verify_nonce( $_GET['directorist_promo_nonce'], 'directorist_promo_nonce' ) ) {
			update_user_meta( get_current_user_id(), '_directorist_promo_closed', directorist_clean( wp_unslash( $_GET['close-directorist-promo-version'] ) ) );
		}

		if ( isset( $_GET['directorist_promo2_closed_version'], $_GET['directorist_promo_nonce'] ) && wp_verify_nonce( $_GET['directorist_promo_nonce'], 'directorist_promo_nonce' ) ) {
			update_user_meta( get_current_user_id(), 'directorist_promo2_closed_version', directorist_clean( wp_unslash( $_GET['directorist_promo2_closed_version'] ) ) );
		}
	}

	public static function promo_link( $link ) {
		if( defined( 'DIRECTORIST_AFFLILIATE_ID' ) && DIRECTORIST_AFFLILIATE_ID !== null ) {
			$link = $link . "ref/" . DIRECTORIST_AFFLILIATE_ID;
		}

		return $link;
	}

}
