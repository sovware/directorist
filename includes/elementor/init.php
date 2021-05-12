<?php
/**
 * @author AazzTech
 */

namespace AazzTech\Directorist\Elementor;

use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_Init {

	public $prefix;
	public $category;
	public $widgets;

	protected static $instance;

	private function __construct() {
		$this->init();
		add_action( 'elementor/editor/after_enqueue_styles',    array( $this, 'editor_style' ) );
		add_action( 'elementor/elements/categories_registered', array( $this, 'widget_categoty' ) );
		add_action( 'elementor/widgets/widgets_registered',     array( $this, 'register_widgets' ) );
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
			Plugin::instance()->widgets_manager->register_widget_type( new $classname );
		}
	}
}

add_action( 'after_setup_theme', function() {
	if ( did_action( 'elementor/loaded' ) ) {
		$activated = apply_filters( 'atbdp_elementor_widgets_activated', true );
		if ( $activated ) {
			Widget_Init::instance();
		}
	}
} );