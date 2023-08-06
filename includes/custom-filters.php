<?php
/*This file will contain most common filters that will help other developer extends / modify our plugin settings or design */


/**
 * It lets you modify button classes used by the directorist plugin. You can add your custom class or modify existing ones.
 * @param string $type the type of the button being printed. eg. default or primary etc.
 * @return string it returns the names of the classed that should be added to a button.
 */

function atbdp_directorist_button_classes($type='primary'){
     /**
      * It lets you modify button classes used by the directorist plugin. You can add your custom class or modify existing ones.
      * @param $type string the type of the button eg. default, primary etc. Default value is default.
      *
      */
     return apply_filters('atbdp_button_class', "btn btn-{$type}", $type);
 }
/**
 * @since 6.3.4
 * @return string image scource
 */
 function atbdp_get_image_source($id = null, $size = 'medium'){
    // void if source id is empty
    if ( empty( $id ) || ! ( is_string( $id ) || is_int( $id ) ) ) { return ''; }
    $image_obj = wp_get_attachment_image_src($id, $size);
    return is_array($image_obj) ? $image_obj[0] : '';
 }