<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// atbdp_get_shortcode_template_paths
function atbdp_get_shortcode_template_paths( $template_file ) {
    $theme_template_file  = '/directorist/shortcodes/' . $template_file . '.php';
    $theme_template_path  = get_stylesheet_directory() . $theme_template_file;
    $plugin_template_path = ATBDP_TEMPLATES_DIR . 'public-templates/shortcodes/' . $template_file . '.php';

    return array(
        'theme'  => $theme_template_path,
        'plugin' => $plugin_template_path,
    );
}

// atbdp_get_template
function atbdp_get_template( $template_file, $args = array() ) {
    if ( is_array( $args ) ) {
        extract( $args );
    }

    $theme_template  = '/directorist/' . $template_file . '.php';
    $plugin_template = ATBDP_TEMPLATES_DIR . 'public-templates/' . $template_file . '.php';

    if ( file_exists( get_stylesheet_directory() . $theme_template ) ) {
        $file = get_stylesheet_directory() . $theme_template;
    } elseif ( file_exists( get_template_directory() . $theme_template ) ) {
        $file = get_template_directory() . $theme_template;
    } else {
        $file = $plugin_template;
    }

    include $file;
}

// atbdp_get_template_path
function atbdp_get_template_path( $template_file ) {

    $theme_template  = '/directorist/' . $template_file . '.php';
    $plugin_template = ATBDP_TEMPLATES_DIR . 'public-templates/' . $template_file . '.php';

    if ( file_exists( get_stylesheet_directory() . $theme_template ) ) {
        $file = get_stylesheet_directory() . $theme_template;
    } elseif ( file_exists( get_template_directory() . $theme_template ) ) {
        $file = get_template_directory() . $theme_template;
    } else {
        $file = $plugin_template;
    }

    return $file;
}

// atbdp_get_shortcode_ext_template
function atbdp_get_shortcode_ext_template( string $extension_key = '',  $file_path = '', $data = [] ) {
    $extension_base_dir  = preg_replace( '/[_]/', '-', $extension_key );
    $extension_dir_path  = trailingslashit( apply_filters( "atbdp_ext_dir_path_{$extension_key}", "" ) ) ;
    $extension_file_path = ( ! empty( $file_path ) ) ? $file_path : apply_filters( "atbdp_ext_file_path_{$extension_key}", $extension_base_dir );

    $template_file      = "";
    $extension_template = "{$extension_dir_path}{$extension_file_path}.php";
    $theme_template     = get_stylesheet_directory() . "/directorist/extensions/{$extension_base_dir}/{$extension_file_path}.php";

    if ( file_exists( $extension_template ) ) {
        $template_file = $extension_template;
    }

    if ( file_exists( $theme_template ) ) {
        $template_file = $theme_template;
    }

    if ( file_exists( $template_file ) ) {
        if ( is_array( $data ) ) {
            extract( $data );
        }

        include $template_file;
    }
}


// atbdp_get_shortcode_template
function atbdp_get_shortcode_template( $template, $args = array() ) {
    $args = apply_filters( 'atbdp_shortcode_template_args', $args, $template );
    $template = 'shortcodes/' . $template;
    atbdp_get_template( $template, $args );
}

// atbdp_get_shortcode_template_path
function atbdp_get_shortcode_template_path( $template ) {
    $template = 'shortcodes/' . $template;
    return atbdp_get_template_path( $template);
}

// atbdp_get_widget_template
function atbdp_get_widget_template( $template, $args = array() ) {
    $args = apply_filters( 'atbdp_widget_template_args', $args, $template );
    $template = 'widgets/' . $template;
    atbdp_get_template( $template, $args );
}

// atbdp_get_widget_template_path
function atbdp_get_widget_template_path( $template ) {
    $template = 'widgets/' . $template;
    return atbdp_get_template_path( $template );
}

// atbdp_return_shortcode_template
function atbdp_return_shortcode_template( $template, $args = array() ) {
    ob_start();
    atbdp_get_shortcode_template( $template, $args );
    return ob_get_clean();
}

// atbdp_return_shortcode_template
function atbdp_return_widget_template( $template, $args = array() ) {
    ob_start();
    atbdp_get_widget_template( $template, $args );

    return ob_get_clean();
}

// atbdp_search_result_page_link
function atbdp_search_result_page_link() {
    echo ATBDP_Permalink::get_search_result_page_link();
}