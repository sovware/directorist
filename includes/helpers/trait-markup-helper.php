<?php
/**
 * @author wpWax
 */

namespace Directorist;

if ( ! defined( 'ABSPATH' ) ) exit;

trait Markup_Helper {

	public static function directorist_container() {
		$result = apply_filters( 'directorist-container', 'directorist-container' );
		echo esc_attr( $result );
	}

	public static function directorist_container_fluid() {
		$result = apply_filters( 'directorist-container-fluid', 'directorist-container-fluid' );
		echo esc_attr( $result );
	}

	public static function directorist_row() {
		$result = apply_filters( 'directorist-row', 'directorist-row' );
		echo esc_attr( $result );
	}

	public static function directorist_column( $column ) {
		$result = apply_filters( 'directorist-container', 'directorist-col-'. $column , $column );
		echo esc_attr( $result );
	}
}