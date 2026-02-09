<?php
/**
 * Rest Listings Controller
 *
 * @package Directorist\Rest_Api
 * @version  1.0.0
 */

namespace Directorist\Rest_Api\Controllers\Version1;

defined( 'ABSPATH' ) || exit;

use Exception;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;
use WP_HTTP_Response;
use Directorist\Helper;

/**
 * Admin controller class.
 */
class Admin_Controller extends Abstract_Controller {
    /**
     * Route base.
     *
     * @var string
     */
    protected $rest_base = 'admin';

    /**
     * Register the routes for admin.
     */
    public function register_routes() {
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base . '/install-plugin',
            [
                [
                    'methods'             => WP_REST_Server::CREATABLE,
                    'callback'            => [ $this, 'install_plugin' ],
                    'permission_callback' => [ $this, 'install_plugins_permissions_check' ],
                    'args'                => [
                        'slug'     => [
                            'description'       => __( 'The slug of the plugin to be installed.' ),
                            'type'              => 'string',
                            'sanitize_callback' => 'sanitize_text_field',
                            'validate_callback' => [ $this, 'validate_not_empty' ],
                            'required'          => true,
                        ],
                        'activate'     => [
                            'description'       => __( 'The slug of the plugin to be installed.' ),
                            'type'              => 'string',
                            'sanitize_callback' => 'sanitize_text_field',
                            'required'          => false,
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Installs and activates plugin by given slug
     *
     * @since 8.4.4
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
     */
    public function install_plugin( WP_REST_Request $request ) {
        $slug     = $request->get_param( 'slug' );
        $activate = ( string ) $request->get_param( 'activate' ) === '1';

        try {
            if ( Helper::is_plugin_installed( $slug ) ) {
                if ( $activate ) {
                    if ( Helper::is_the_plugin_active( $slug ) ) {
                        return rest_ensure_response(
                            [
                                'message' => __( 'The plugin was already activated.' ),
                            ] 
                        );
                    }
    
                    Helper::activate_plugin( $slug );
    
                    return rest_ensure_response(
                        [
                            'message' => __( 'The plugin was successfully activated.' ),
                        ] 
                    );
                }
    
                return rest_ensure_response(
                    [
                        'message' => __( 'The plugin was already installed.' ),
                    ] 
                );
            }
    
            Helper::install_plugin( $slug );

            if ( $activate ) {
                Helper::activate_plugin( $slug );

                return rest_ensure_response(
                    [
                        'message' => __( 'The plugin was installed and activated successfully.' ),
                    ] 
                );
            }

            return rest_ensure_response(
                [
                    'message' => __( 'The plugin was installed successfully.' ),
                ] 
            );
        } catch ( Exception $e ) {
            return rest_ensure_response( new WP_HTTP_Response( [ 'message' => $e->getMessage() ], $e->getCode() ) );
        }
    }

    public function validate_not_empty( $value ) {
        if ( empty( $value ) ) {
            return new WP_Error( 'rest_empty_value', __( 'The value must not be empty.' ) );
        }

        return true;
    }

    public function install_plugins_permissions_check(): bool {
        return current_user_can( 'install_plugins' );
    }
}