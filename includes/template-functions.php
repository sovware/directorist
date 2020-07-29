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

// atbdp_get_shortcode_template
function atbdp_get_shortcode_template( $template, $args = array() ) {
    $args = apply_filters( 'atbdp_shortcode_template_args', $args, $template );
    $template = 'shortcodes/' . $template;
    atbdp_get_template( $template, $args );
}

// atbdp_get_widget_template
function atbdp_get_widget_template( $template, $args = array() ) {
    $args = apply_filters( 'atbdp_widget_template_args', $args, $template );
    $template = 'widgets/' . $template;
    atbdp_get_template( $template, $args );
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