<?php
/**
 * @author wpWax
 */

namespace Directorist;

if ( ! defined( 'ABSPATH' ) ) exit;

trait Markup_Helper {

	public static function directorist_container() {
		$result = apply_filters( 'directorist_container', 'directorist-container' );
		echo esc_attr( $result );
	}

	public static function directorist_container_fluid() {
		$result = apply_filters( 'directorist_container_fluid', 'directorist-container-fluid' );
		echo esc_attr( $result );
	}

	public static function directorist_row() {
		$result = apply_filters( 'directorist_row', 'directorist-row' );
		echo esc_attr( $result );
	}

	public static function directorist_column( $column ) {
		if ( is_array( $column ) ) {
			$result = '';
			foreach ( $column as $value ) {
				$result .= ' directorist-col-'. $value;
			}
		}
		else {
			$result = 'directorist-col-'. $column;
		}

		$result = apply_filters( 'directorist_column', $result , $column );
		echo esc_attr( $result );
	}

	public static function directorist_single_column() {
		$column = is_active_sidebar('right-sidebar-listing') ? 'md-8' : 'md-12';
		Helper::directorist_column( $column );
	}

	public static function search_filter_class( $display_type ) {
		$result = ( 'overlapping' === $display_type ) ? 'directorist-search-float' : 'directorist-search-slide';
		echo esc_attr( $result );
	}
}