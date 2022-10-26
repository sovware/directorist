<?php
/**
 * Deprecated function codes will go here.
 *
 * This file will be removed in the future, currently kept for
 * backward compatibility.
 *
 * @author wpWax
 */

namespace Directorist\database;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

trait Deprecated {

    public static function _get_atbdp_listings_ids() {
        $arg = array(
            'post_type'      => 'at_biz_dir',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'fields'         => 'ids'
        );

        $query = new \WP_Query( $arg );
        return $query;
    }

}
