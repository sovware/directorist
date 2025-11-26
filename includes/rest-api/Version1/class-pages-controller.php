<?php
/**
 * Pages Rest Controller.
 *
 * @package Directorist\Rest_Api
 * @version  1.0.0
 */

namespace Directorist\Rest_Api\Controllers\Version1;

defined( 'ABSPATH' ) || exit;

use WP_REST_Server;
use WP_REST_Request;
use WP_REST_Response;

/**
 * REST API Pages controller class.
 *
 * @package Directorist\Rest_Api
 * @extends Abstract_Controller
 */
class Pages_Controller extends Abstract_Controller {
    /**
     * Route base.
     *
     * @var string
     */
    protected $rest_base = 'pages';

    /**
     * Register the routes for pages.
     */
    public function register_routes() {
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base,
            array(
                array(
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => array( $this, 'get_items' ),
                    'permission_callback' => array( $this, 'get_items_permissions_check' ),
                ),
            )
        );
    }

    /**
     * Check if a given request has access to read pages.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return bool
     */
    public function get_items_permissions_check( $request ) {
        return true;
    }

    /**
     * Get all pages.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response
     */
    public function get_items( $request ) {
        $pages = get_pages();
        $pages_options = array(
            array( 'value' => '', 'label' => 'Select...' )
        );

        if ( empty( $pages ) || ! is_array( $pages ) ) {
            return rest_ensure_response( $pages_options );
        }

        foreach ( $pages as $page ) {
            $pages_options[] = array(
                'value' => $page->ID,
                'label' => $page->post_title
            );
        }

        return rest_ensure_response( $pages_options );
    }
}
