<?php

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
    $theme_template     = get_template_directory() . "/directorist/extensions/{$base_dirrectory}/{$ext_file_path}.php";

    if ( ! empty( $ext_dir_path ) && ! empty( $ext_file_path ) && file_exists( $extension_template ) ) {
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

function atbdp_get_admin_template( $template, $args = [] ) {
    if ( ! atbdp_has_admin_template( $template ) ) {
        return;
    }

    if ( is_array( $args ) ) {
        extract( $args );
    }

    $file = ATBDP_VIEWS_DIR . 'admin-templates/' . $template . '.php';

    include $file;
}

function atbdp_search_result_page_link() {
    echo esc_url( ATBDP_Permalink::get_search_result_page_link() );
}

function atbdp_get_template( $template_file, $args = [] ) {
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

function atbdp_get_widget_template( $template, $args = [] ) {
    $args     = apply_filters( 'atbdp_widget_template_args', $args, $template );
    $template = 'widgets/' . $template;
    atbdp_get_template( $template, $args );
}

function atbdp_get_widget_template_path( $template ) {
    $template = 'widgets/' . $template;

    return atbdp_get_template_path( $template );
}

function directorist_get_listing_thumbnail_id( $listing = null ) {
    $listing = get_post( $listing );

    if ( ! $listing ) {
        return false;
    }

    if ( $listing->post_type !== ATBDP_POST_TYPE ) {
        return false;
    }

    $thumbnail_id = get_post_thumbnail_id( $listing );
    if ( $thumbnail_id ) {
        return $thumbnail_id;
    }

    $thumbnail_id = directorist_get_listing_preview_image( $listing->ID );
    if ( $thumbnail_id ) {
        return $thumbnail_id;
    }

    $gallery_image_ids = directorist_get_listing_gallery_images( $listing->ID );
    if ( empty( $gallery_image_ids ) ) {
        return false;
    }

    return $gallery_image_ids[0];
}

function directorist_has_listing_thumbnail( $listing = null ) {
    return (bool) directorist_get_listing_thumbnail_id( $listing );
}

function directorist_sort_location_terms_by_hierarchy( $terms ) {
    if ( ! is_array( $terms ) || count( $terms ) < 2 ) {
        return $terms;
    }

    $sort_data = [];

    foreach ( $terms as $term ) {
        $ancestors = get_ancestors( $term->term_id, ATBDP_LOCATION, 'taxonomy' );
        $root_id   = empty( $ancestors ) ? $term->term_id : end( $ancestors );
        $root_term = $root_id === $term->term_id ? $term : get_term( $root_id, ATBDP_LOCATION );

        $sort_data[ $term->term_id ] = [
            'root_id'   => (int) $root_id,
            'root_name' => ! is_wp_error( $root_term ) && $root_term ? $root_term->name : '',
            'depth'     => count( $ancestors ),
            'name'      => $term->name,
        ];
    }

    usort(
        $terms,
        function ( $first_term, $second_term ) use ( $sort_data ) {
            $first  = $sort_data[ $first_term->term_id ];
            $second = $sort_data[ $second_term->term_id ];

            $root_name_comparison = strcasecmp( $first['root_name'], $second['root_name'] );
            if ( 0 !== $root_name_comparison ) {
                return $root_name_comparison;
            }

            if ( $first['root_id'] !== $second['root_id'] ) {
                return $first['root_id'] <=> $second['root_id'];
            }

            if ( $first['depth'] !== $second['depth'] ) {
                return $second['depth'] <=> $first['depth'];
            }

            $name_comparison = strcasecmp( $first['name'], $second['name'] );
            if ( 0 !== $name_comparison ) {
                return $name_comparison;
            }

            return $first_term->term_id <=> $second_term->term_id;
        }
    );

    return $terms;
}

function directorist_the_locations( $before = '', $sep = ', ', $after = '', $listing_id = null ) {
    $terms = get_the_terms( $listing_id, ATBDP_LOCATION );

    if ( is_wp_error( $terms ) || empty( $terms ) ) {
        return;
    }

    $links = [];
    $terms = directorist_sort_location_terms_by_hierarchy( $terms );

    foreach ( $terms as $term ) {
        $link = get_term_link( $term, ATBDP_LOCATION );
        if ( is_wp_error( $link ) ) {
            return;
        }

        $links[] = '<a href="' . esc_url( $link ) . '" rel="tag">' . $term->name . '</a>';
    }

    $term_links = apply_filters( 'term_links-' . ATBDP_LOCATION, $links );
    $term_list  = $before . implode( $sep, $term_links ) . $after;

    echo apply_filters( 'the_terms', $term_list, ATBDP_LOCATION, $before, $sep, $after ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Preserve the core the_terms() filter contract.
}
