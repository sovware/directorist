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


    if ( file_exists( $file ) ) {
        include $file;
    }
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


// atbdp_get_shortcode_template
function atbdp_get_shortcode_template( $template, $args = array(), string $shortcode_key = '' ) {
    $args = apply_filters( 'atbdp_shortcode_template_args', $args, $template );

    // Load extension template if exist
    if ( ! empty( $shortcode_key ) ) {
        $default = [ 'template_directory' => '', 'file_path' => '', 'base_directory' => '' ];
        $ex_args = apply_filters( "atbdp_ext_template_path_{$shortcode_key}", $default, $args );
        $ex_args = array_merge( $default, $ex_args );
        
        $extension_path = atbdp_get_extension_template_path( $ex_args['template_directory'], $ex_args['file_path'], $ex_args['base_directory'] );
        
        if ( file_exists( $extension_path ) ) {
            $old_template_data = isset( $GLOBALS['atbdp_template_data'] ) ? $GLOBALS['atbdp_template_data'] : null;
            $GLOBALS['atbdp_template_data'] = $args;

            include $extension_path;
            
            $GLOBALS['atbdp_template_data'] = $old_template_data;
            return;
        }
    }
    
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