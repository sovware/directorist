<?php
/*This file will contain most common filters that will help other developer extends / modify our plugin settings or design */


/**
 * It lets you modify button classes used by the directorist plugin. You can add your custom class or modify existing ones.
 * @param string $type the type of the button being printed. eg. default or primary etc.
 * @return string it returns the names of the classed that should be added to a button.
 */
function atbdp_directorist_button_classes($type='default'){
     /**
      * It lets you modify button classes used by the directorist plugin. You can add your custom class or modify existing ones.
      * @param $type string the type of the button eg. default, primary etc. Default value is default.
      *
      */
     return apply_filters('atbdp_button_class', "directory_btn btn btn-{$type}", $type);
 }