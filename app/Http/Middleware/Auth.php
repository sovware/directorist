<?php

namespace Directorist\App\Http\Middleware;

defined( 'ABSPATH' ) || exit;

use Directorist\WpMVC\Routing\Contracts\Middleware;
use WP_REST_Request;
use WP_Error;

class Auth implements Middleware {
    /**
    * Handle an incoming request.
    *
    * @param  WP_REST_Request  $wp_rest_request
    * @return bool|WP_Error
    */
    public function handle( WP_REST_Request $wp_rest_request ) {
        return is_user_logged_in();
    }
}