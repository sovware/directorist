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
function atbdp_get_template( $template_file, $args = array(), $template = null, $extract_template = false ) {
    if ( is_array( $args ) ) {
        extract( $args );
    }

    if ( $template && is_object( $template ) && true == $extract_template ) {
        extract( (array)$template );
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

// atbdp_get_template
function atbdp_get_ext_template( $template_file, $extension_file, $args = array(), $template = null, $extract_template = false ) {
    if ( is_array( $args ) ) {
        extract( $args );
    }

    if ( $template && is_object( $template ) && true == $extract_template ) {
        extract( (array)$template );
    }

    $theme_template  = '/directorist/' . $template_file . '.php';
    $plugin_template = $extension_file . '.php';

    if ( file_exists( get_stylesheet_directory() . $theme_template ) ) {
        $file = get_stylesheet_directory() . $theme_template;
    } elseif ( file_exists( get_template_directory() . $theme_template ) ) {
        $file = get_template_directory() . $theme_template;
    } else {
        $file = $plugin_template;
    }

    include $file;
}

// atbdp_get_shortcode_template
function atbdp_get_shortcode_template( $template, $args = array(), $helper = null, $extract_helper = false ) {
    $template = 'shortcodes/' . $template;
    atbdp_get_template( $template, $args, $helper, $extract_helper );
}

// atbdp_return_shortcode_template
function atbdp_return_shortcode_template( $template, $args = array() ) {
    $template = 'shortcodes/' . $template;
    ob_start();
    atbdp_get_template( $template, $args );

    return ob_get_clean();
}

// atbdp_get_shortcode_template
function atbdp_get_shortcode_ext_template( $template_file, $extension_file, $args = array(), $helper = null, $extract_helper = false ) {
    $template_file = 'shortcodes/extension/' . $template_file;
    atbdp_get_ext_template( $template_file, $extension_file, $args, $helper, $extract_helper );
}

// atbdp_get_widget_template
function atbdp_get_widget_template( $template, $args = array(), $helper = null, $extract_helper = false ) {
    $template = 'widgets/' . $template;
    atbdp_get_template( $template, $args, $helper, $extract_helper );
}

// atbdp_search_result_page_link
function atbdp_search_result_page_link() {
    echo ATBDP_Permalink::get_search_result_page_link();
}
