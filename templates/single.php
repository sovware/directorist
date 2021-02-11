<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;

get_header( 'directorist' );

Helper::get_template( 'single-contents' );

get_footer( 'directorist' );