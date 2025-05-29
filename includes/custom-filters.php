<?php
/*This file will contain most common filters that will help other developer extends / modify our plugin settings or design */


/**
 * It lets you modify button classes used by the directorist plugin. You can add your custom class or modify existing ones.
 * @param string $type the type of the button being printed. eg. default or primary etc.
 * @return string it returns the names of the classed that should be added to a button.
 */

function atbdp_directorist_button_classes( $type = 'primary' ) {
     /**
      * It lets you modify button classes used by the directorist plugin. You can add your custom class or modify existing ones.
      * @param $type string the type of the button eg. default, primary etc. Default value is default.
      *
      */
     return apply_filters( 'atbdp_button_class', "directorist-btn directorist-btn-{$type} directorist-btn-lg", $type );
}

/**
 * @since 6.3.4
 * @return string image scource
 */
function atbdp_get_image_source( $id = null, $size = 'medium' ) {
    return wp_get_attachment_image_url( $id, $size );
}

/**
 * Backward compatibility for get_term_meta default directory call.
 * @param mixed $value
 * @param mixed $term_id
 * @param mixed $meta_key
 */
function directorist_bc_get_default_directory_term_meta( $value, $term_id, $meta_key ) {
    if ( $meta_key !== '_default' || ! directorist_is_directory( $term_id ) ) {
        return $value;
    }

    remove_filter( 'get_term_metadata',  'directorist_bc_get_default_directory_term_meta', 10 );
    remove_filter( 'default_term_metadata', 'directorist_bc_get_default_directory_term_meta', 10 );

    $is_default = directorist_get_default_directory() === $term_id;

    add_filter( 'get_term_metadata',  'directorist_bc_get_default_directory_term_meta', 10, 3 );
    add_filter( 'default_term_metadata', 'directorist_bc_get_default_directory_term_meta', 10, 3 );

    return $is_default;
}

add_filter( 'get_term_metadata',  'directorist_bc_get_default_directory_term_meta', 10, 3 );
add_filter( 'default_term_metadata', 'directorist_bc_get_default_directory_term_meta', 10, 3 );
