<?php
/**
 * Deprecated functions will be moved here from time to time.
 *
 * @author  wpWax
 * @since   7.4.3
 */

use Directorist\Helper;

function atbdp_get_shortcode_template_paths( $template_file ) {
    _deprecated_function( __FUNCTION__, '7.0', 'Helper::get_template()' );
    $theme_template_file  = '/directorist/shortcodes/' . $template_file . '.php';
    $theme_template_path  = get_stylesheet_directory() . $theme_template_file;
    $plugin_template_path = Helper::template_directory() . 'public-templates/shortcodes/' . $template_file . '.php';

    return [
        'theme'  => $theme_template_path,
        'plugin' => $plugin_template_path,
    ];
}

function atbdp_get_shortcode_template( $template, $args = [] ) {
    _deprecated_function( __FUNCTION__, '7.0', 'Helper::get_template()' );

    return Helper::get_template( $template, $args );
}

function atbdp_return_shortcode_template( $template, $args = [] ) {
    _deprecated_function( __FUNCTION__, '7.0', 'Helper::get_template_contents()' );

    return Helper::get_template_contents( $template, $args );
}

function atbdp_return_widget_template( $template, $args = [] ) {
    _deprecated_function( __FUNCTION__, '7.0' );
    ob_start();
    atbdp_get_widget_template( $template, $args );

    return ob_get_clean();
}

function atbdp_get_shortcode_template_path( $template ) {
    _deprecated_function( __FUNCTION__, '7.0' );
    $template = 'shortcodes/' . $template;

    return atbdp_get_template_path( $template );
}

function bdas_dropdown_terms($args = array(), $echo = true) {
	_deprecated_function( __METHOD__, '7.3.1' );
	return '';
}

function the_thumbnail_card($img_src = '', $_args = array()) {
    _deprecated_function( __FUNCTION__, '7.0', 'atbdp_thumbnail_card()' );
    return atbdp_thumbnail_card($img_src,$_args);
}

if ( ! function_exists('get_fa_icons') ) {
    function get_fa_icons() {
		_deprecated_function( __FUNCTION__, '7.4.0' );
		return array();
    }
}

if (!function_exists('get_fa_icons_full')) {
    function get_fa_icons_full() {
		_deprecated_function( __FUNCTION__, '7.4.0' );
		return array();
    }
}

if (!function_exists('atbdp_icon_type')) {
    function atbdp_icon_type($echo = false) {
		_deprecated_function( __FUNCTION__, '7.4.0', 'directorist_icon' );
		$font_type = 'la la';
        if ($echo) {
            echo esc_html( $font_type );
        } else {
            return $font_type;
        }
    }
}

if ( ! function_exists( 'atbdp_get_term_icon' ) ) {
    function atbdp_get_term_icon( array $args = [] ) {

		_deprecated_function( __FUNCTION__, '7.4.0' );

        $default = [ 'icon' => '', 'default' => 'lar la-folder-open', 'echo' => false ];
        $args = array_merge( $default, $args );

        $icon = ( ! empty($args['icon'] ) ) ? $args['icon'] : $args['default'];
        $icon = ( ! empty( $icon ) ) ? '<span class="'. $icon .'"></span>' : $icon;

        if ( ! $args['echo'] ) { return $icon; }

        echo wp_kses_post( $icon );
    }
}

if (!function_exists('get_atbdp_listings_ids')) {
    function get_atbdp_listings_ids() {
		_deprecated_function( __FUNCTION__, '7.4.3' );
        $arg = array(
            'post_type'      => 'at_biz_dir',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'fields'         => 'ids'
        );

        $query = new \WP_Query( $arg );
        return $query;
    }
}