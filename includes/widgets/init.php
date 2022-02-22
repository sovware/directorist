<?php
/**
 * Base class for handling widgets.
 * 
 * @author wpWax
 */

namespace Directorist\Widgets;

if ( ! defined( 'ABSPATH' ) ) exit;

class Init {

	public function __construct() {
        add_action( 'widgets_init', [ $this , 'register_widgets' ] );
	}

}