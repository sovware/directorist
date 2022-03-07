<?php
/**
 * Singleton class for handling widgets.
 * 
 * @author wpWax
 */

namespace Directorist\Widgets;

if ( ! defined( 'ABSPATH' ) ) exit;

class Init {

	protected static $instance = null;

	private function __construct() {
        add_action( 'widgets_init', [ $this , 'register_widgets' ] );
		Widget_Fields::init();
	}

	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	public function register_widgets() {
        register_widget( 'Directorist\Widgets\Popular_Listings' );
		register_widget( 'Directorist\Widgets\Listing_Video' );
		register_widget( 'Directorist\Widgets\Contact_Form' );
	}
}