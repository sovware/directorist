<?php
/**
 * @author AazzTech
 */

namespace AazzTech\Directorist\Elementor;

use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( class_exists( 'AddonskitForELementor' ) ) {
	return;
}

class Widget_Init {

	public $prefix;
	public $category;
	public $widgets;

	protected static $instance;

	private function __construct() {
		$this->init();
		add_action( 'elementor/editor/after_enqueue_styles',    array( $this, 'editor_style' ) );
		add_action( 'elementor/elements/categories_registered', array( $this, 'widget_categoty' ) );
		// add_action( 'elementor/widgets/register',     array( $this, 'register_widgets' ) );
	}

	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	private function init() {
		$this->prefix   = 'directorist';
		$this->category = __( 'Directorist', 'directorist' );

		// Widgets -- filename=>classname
		$widgets = array(
			'all-listing'                   => 'Directorist_All_Listing',
			'all-categories'                => 'Directorist_All_Categories',
			'all-locations'                 => 'Directorist_All_Locations',
			'category'                      => 'Directorist_Category',
			'location'                      => 'Directorist_Location',
			'tag'                           => 'Directorist_Tag',
			'search-listing'                => 'Directorist_Search_Listing',
			'search-result'                 => 'Directorist_Search_Result',
			'add-listing'                   => 'Directorist_Add_Listing',
			'user-login'                    => 'Directorist_User_Login',
			'custom-registration'           => 'Directorist_Custom_Registration',
			'user-dashboard'                => 'Directorist_User_Dashboard',
			'author-profile'                => 'Directorist_Author_Profile',
			'transaction-failure'           => 'Directorist_Transaction_Failure',
			'payment-receipt'               => 'Directorist_Payment_Receipt',
			'checkout'                      => 'Directorist_Checkout',
		);

		$this->widgets = apply_filters( 'atbdp_elementor_widgets', $widgets );
	}

	public function editor_style() {
		$img = DIRECTORIST_ASSETS . 'images/elementor-icon.png';
		wp_add_inline_style( 'elementor-editor', '.elementor-control-type-select2 .elementor-control-input-wrapper {min-width: 130px;}.elementor-element .icon .directorist-el-custom{content: url('.$img.');width: 22px;}' );
	}

	public function widget_categoty( $class ) {
		$id         = $this->prefix . '-widgets';
		$properties = array(
			'title' => $this->category,
		);

		Plugin::$instance->elements_manager->add_category( $id, $properties );
	}

	public function register_widgets() {
		require_once __DIR__ . '/base.php';

		foreach ( $this->widgets as $filename => $class ) {
			$template_name = '/directorist-elementor/' . $filename . '.php';
			if ( file_exists( STYLESHEETPATH . $template_name ) ) {
				$file = STYLESHEETPATH . $template_name;
			}
			elseif ( file_exists( TEMPLATEPATH . $template_name ) ) {
				$file = TEMPLATEPATH . $template_name;
			}
			else {
				$file = __DIR__ . '/' . $filename . '.php';
			}

			require_once $file;

			$classname = __NAMESPACE__ . '\\' . $class;

			$elementor_widgets_manager = Plugin::instance()->widgets_manager;

			if ( method_exists( $elementor_widgets_manager, 'register' ) ) {
				$elementor_widgets_manager->register( new $classname );
			} else {
				// Remove this deprececated check safely after 30 June, 2024
				$elementor_widgets_manager->register_widget_type( new $classname );
			}
		}
	}
}

add_action( 'after_setup_theme', function() {
	if ( did_action( 'elementor/loaded' ) ) {
		$activated = apply_filters( 'atbdp_elementor_widgets_activated', true );
		if ( $activated ) {
			Widget_Init::instance();
		}

		include_once 'deprecated-notice.php';
		$dn = new DeprecatedNotice();
		add_action( 'admin_notices', [$dn, 'maybe_show_notice_for_required_plugins']  );
	}
} );

/**
 * Elementor compatibility with custom single listing page.
 *
 * @param  string $content
 * @param  WP_Post $page
 *
 * @return string
 */
function directorist_add_custom_single_listing_page_content_from_elementor( $content, $page ) {
	if ( did_action( 'elementor/loaded' ) && \Elementor\Plugin::instance()->documents->get( $page->ID )->is_built_with_elementor() ) {
		return \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $page->ID );
	}

	return $content;
}
add_filter( 'directorist_custom_single_listing_pre_page_content', __NAMESPACE__ . '\\directorist_add_custom_single_listing_page_content_from_elementor', 10, 2 );
