<?php
/**
 * @author wpWax
 */

namespace Directorist;

if ( ! defined( 'ABSPATH' ) ) exit;

abstract class Asset_Loader_Base {

	abstract function debug_enabled();

	/**
	 * Register all assets.
	 */
	public function register_scripts() {

		foreach ( $this->scripts as $handle => $script ) {

			$url = $this->script_file_url( $script );

			if ( !empty( $script['dep'] ) ) {
				$dep = $script['dep'];
			}
			else {
				$dep = ( $script['type'] == 'js' ) ? ['jquery'] : [];
			}

			if ( $script['type'] == 'css' ) {
				wp_register_style( $handle, $url, $dep, $this->version );
			}
			else {
				wp_register_script( $handle, $url, $dep, $this->version );
			}
		}
	}

	/**
	 * Absoulute url based on various factors eg. min, rtl etc.
	 *
	 * $args$script Single item of $Asset_Loader::scripts array.
	 *
	 * @return string URL string.
	 */
	public function script_file_url( $script ) {
		if ( !empty( $script['ext'] ) ) {
			return $script['ext'];
		}

		$min  = $this->debug_enabled() ? '' : '.min';
		$rtl  = ( $script['type'] == 'css' && !empty( $script['rtl'] ) && is_rtl() ) ? '.rtl' : '';
		$ext  = $script['type'] == 'css' ? '.css' : '.js';
		$url = $script['path'] . $min . $rtl . $ext;
		return $url;
	}
}