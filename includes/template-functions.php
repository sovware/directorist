<?php

use Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// atbdp_get_extension_template_path
function atbdp_get_extension_template_path( string $base_path = '', string $file_path = '', string $base_dirrectory = '' ) {
    $ext_dir_path    = trailingslashit( $base_path );
    $ext_file_path   = $file_path;
    $base_dirrectory = preg_replace( '/(\/.+)?(\/)?/', '', $base_dirrectory );

    $template_file      = "";
    $extension_template = "{$ext_dir_path}{$ext_file_path}.php";
    $theme_template     = get_stylesheet_directory() . "/directorist/extensions/{$base_dirrectory}/{$ext_file_path}.php";

    if ( file_exists( $extension_template ) ) {
        $template_file = $extension_template;
    }

    if ( file_exists( $theme_template ) ) {
        $template_file = $theme_template;
    }

    return $template_file;
}

// atbdp_get_extension_template
function atbdp_get_extension_template( string $base_path = '', string $file_path = '', string $theme_dir = '', $data = [] ) {
    $template = atbdp_get_extension_template_path( $base_path, $file_path, $theme_dir );

    if ( file_exists( $template ) ) {
        include $template;
    }
}

function atbdp_has_admin_template( $template ) {
    $file = ATBDP_VIEWS_DIR . 'admin-templates/' . $template . '.php';

    return file_exists( $file ) ? true : false;
}

function atbdp_get_admin_template( $template, $args = array() ) {
    if ( !atbdp_has_admin_template( $template ) ) {
       return;
    }

    if ( is_array( $args ) ) {
        extract( $args );
    }

    $file = ATBDP_VIEWS_DIR . 'admin-templates/' . $template . '.php';

    include $file;
}

function atbdp_search_result_page_link() {
    echo ATBDP_Permalink::get_search_result_page_link();
}



function atbdp_get_template( $template_file, $args = array() ) {
    if ( is_array( $args ) ) {
        extract( $args );
    }

    $theme_template  = '/directorist/' . $template_file . '.php';
    $plugin_template = ATBDP_VIEWS_DIR . $template_file . '.php';

    if ( file_exists( get_stylesheet_directory() . $theme_template ) ) {
        $file = get_stylesheet_directory() . $theme_template;
    } elseif ( file_exists( get_template_directory() . $theme_template ) ) {
        $file = get_template_directory() . $theme_template;
    } else {
        $file = $plugin_template;
    }


    if ( file_exists( $file ) ) {
        include $file;
    }
}

function atbdp_get_template_path( $template_file ) {

    $theme_template  = '/directorist/' . $template_file . '.php';
    $plugin_template = ATBDP_VIEWS_DIR . $template_file . '.php';

    if ( file_exists( get_stylesheet_directory() . $theme_template ) ) {
        $file = get_stylesheet_directory() . $theme_template;
    } elseif ( file_exists( get_template_directory() . $theme_template ) ) {
        $file = get_template_directory() . $theme_template;
    } else {
        $file = $plugin_template;
    }

    return $file;
}

function atbdp_get_widget_template( $template, $args = array() ) {
    $args = apply_filters( 'atbdp_widget_template_args', $args, $template );
    $template = 'widgets/' . $template;
    atbdp_get_template( $template, $args );
}

function atbdp_get_widget_template_path( $template ) {
    $template = 'widgets/' . $template;
    return atbdp_get_template_path( $template );
}

function atbdp_get_shortcode_template_paths( $template_file ) {
    _deprecated_function( __FUNCTION__, '7.0', 'Helper::get_template()' );
    $theme_template_file  = '/directorist/shortcodes/' . $template_file . '.php';
    $theme_template_path  = get_stylesheet_directory() . $theme_template_file;
    $plugin_template_path = Helper::template_directory() . 'public-templates/shortcodes/' . $template_file . '.php';

    return array(
        'theme'  => $theme_template_path,
        'plugin' => $plugin_template_path,
    );
}

function atbdp_get_shortcode_template( $template, $args = array() ) {
    _deprecated_function( __FUNCTION__, '7.0', 'Helper::get_template()' );
    return Helper::get_template( $template, $args );
}

function atbdp_return_shortcode_template( $template, $args = array() ) {
    _deprecated_function( __FUNCTION__, '7.0', 'Helper::get_template_contents()' );
    return Helper::get_template_contents( $template, $args );
}

function atbdp_return_widget_template( $template, $args = array() ) {
    _deprecated_function( __FUNCTION__, '7.0' );
    ob_start();
    atbdp_get_widget_template( $template, $args );

    return ob_get_clean();
}

function atbdp_get_shortcode_template_path( $template ) {
    _deprecated_function( __FUNCTION__, '7.0' );
    $template = 'shortcodes/' . $template;
    return atbdp_get_template_path( $template);
}