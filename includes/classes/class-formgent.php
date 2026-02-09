<?php

use FormGent\App\DTO\ResponseSingleDTO;
use FormGent\App\Models\Post;
use FormGent\App\Models\Response;
use FormGent\App\Models\ResponseMeta;

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'ATBDP_Formgent' ) ) {
    class ATBDP_Formgent
    {
        public function __construct() {
            add_action( 'formgent_after_create_form_response_token', [ $this, 'after_create_form_response_token' ], 10, 3 );
            add_action( 'rest_api_init', [ $this, 'rest_api_init' ] );
        }

        public function after_create_form_response_token( $response_token, $dto, \WP_REST_Request $wp_rest_request ) {
            $external_data = $wp_rest_request->get_param( 'external_data' );

            if ( empty( $external_data['listing_id'] ) ) {
                return;
            }

            $response_repository = formgent_response_repository();
            $response_repository->add_meta( $dto->get_id(), 'listing_id', absint( $external_data['listing_id'] ) );
        }

        public function rest_api_init() {
            register_rest_route(
                'directorist', '/formgent/responses', [
                    'methods' => 'GET',
                    'callback' => [ $this, 'get_responses' ],
                    'permission_callback' => [ $this, 'check_permission' ],
                ]
            );
        
            register_rest_route(
                'directorist', '/formgent/responses/kpis', [
                    'methods' => 'GET',
                    'callback' => [ $this, 'get_kpis' ],
                    'permission_callback' => [ $this, 'check_permission' ],
                ]
            );
        
            register_rest_route(
                'directorist', '/formgent/responses', [
                    'methods' => 'DELETE',
                    'callback' => [ $this, 'delete_responses' ],
                    'permission_callback' => [ $this, 'check_permission' ],
                ]
            );
        
            register_rest_route(
                'directorist', '/formgent/responses/read', [
                    'methods' => 'POST',
                    'callback' => [ $this, 'read_responses' ],
                    'permission_callback' => [ $this, 'check_permission' ],
                ]
            );
        
            register_rest_route(
                'directorist', '/formgent/responses/single', [
                    'methods' => 'GET',
                    'callback' => [ $this, 'single_response' ],
                    'permission_callback' => [ $this, 'check_permission' ],
                ]
            );
        }

        /**
         * Check if user is logged in for dashboard access.
         *
         * @param \WP_REST_Request $request REST request object.
         * @return bool
         */
        public function check_permission( $request ) {
            $user_id = get_current_user_id();
            
            // If user ID is 0, try to authenticate from cookies
            if ( empty( $user_id ) && isset( $_COOKIE[ LOGGED_IN_COOKIE ] ) ) {
                // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- wp_validate_auth_cookie() handles sanitization
                $cookie_value = wp_unslash( $_COOKIE[ LOGGED_IN_COOKIE ] );
                $user_id = wp_validate_auth_cookie( $cookie_value, 'logged_in' );
                if ( $user_id ) {
                    wp_set_current_user( $user_id );
                }
            }
            
            return ! empty( $user_id );
        }

        public function single_response( $request ) {
            $response_id = absint( $request->get_param( 'id' ) );

            if ( empty( $response_id ) ) {
                return rest_ensure_response( [ 'success' => false, 'message' => __( 'Response not found.', 'directorist' ) ] );
            }

            $response = $this->get_responses_query()->where( 'response.id', $response_id )->first();

            if ( empty( $response ) ) {
                return rest_ensure_response( [ 'success' => false, 'message' => __( 'Response not found.', 'directorist' ) ] );
            }

            $dto = ( new ResponseSingleDTO )->set_form_id( $response->form_id )->set_is_completed( 1 );

            $response = formgent_response_repository()->get_single( $dto, $response_id );

            $form = formgent_get_form_by_id( $response->form_id );

            $fields_settings = formgent_get_form_fields( $form );
            $response_controller = formgent_singleton( \FormGent\App\Http\Controllers\Admin\ResponseController::class );
            $fields          = $response_controller->prepare_fields( $fields_settings );

            $listing_id = formgent_response_repository()->get_meta_value( $response_id, 'listing_id' );
            $listing_permalink = '';

            if ( ! empty( $listing_id ) ) {
                $listing_id        = absint( $listing_id );
                $listing_permalink = ATBDP_Permalink::get_listing_permalink( $listing_id, get_the_permalink( $listing_id ) );
            }

            return rest_ensure_response( 
                [ 
                    'success'           => true,
                    'response'          => $response,
                    'fields'            => $fields,
                    'listing_permalink' => $listing_permalink,
                ]
            );
        }

        public function read_responses( $request ) {
            $response_id = absint( $request->get_param( 'id' ) );

            if ( empty( $response_id ) ) {
                return rest_ensure_response(
                    [
                        'success' => false,
                        'message' => __( 'Response not found.', 'directorist' ),
                    ]
                );
            }

            $response = $this->get_responses_query()->where( 'response.id', $response_id )->first();

            if ( empty( $response ) ) {
                return rest_ensure_response( [ 'success' => false, 'message' => __( 'Response not found.', 'directorist' ) ] );
            }

            $response_repository = formgent_response_repository();
            $response_repository->update_read( $response_id, 1 );

            return rest_ensure_response( [ 'success' => true, 'message' => __( 'Response has been marked as read successfully.', 'directorist' ) ] );
        }

        public function delete_responses( $request ) {
            $response_id = absint( $request->get_param( 'id' ) );

            if ( empty( $response_id ) ) {
                return rest_ensure_response(
                    [
                        'success' => false,
                        'message' => __( 'Response not found.', 'directorist' ),
                    ]
                );
            }

            $this->get_responses_query()->where( 'response.id', $response_id )->delete();

            return rest_ensure_response( [ 'success' => true, 'message' => __( 'Response has been deleted successfully.', 'directorist' ) ] );
        }

        public function get_responses( $request ) {
            $page = absint( $request->get_param( 'page' ) );
            $per_page = absint( $request->get_param( 'per_page' ) );
        
            $query = $this->get_responses_query();
            $count_query = clone $query;
        
            $responses = $query->select( 'response.*', 'post.post_title as listing_title', 'post.post_author as listing_owner' )->with(
                'user', function( $query ) {
                    $query->select( 'ID', 'user_email', 'display_name' );
                }
            )->pagination( $page, $per_page );
        
            $responses = array_map(
                function( $response ) {
                    // Handle cases where user might be null (non-logged-in submissions)
                    if ( isset( $response->user ) && is_object( $response->user ) && isset( $response->user->user_email ) ) {
                        $response->user->profile_url = get_avatar_url( $response->user->user_email );
                    } else {
                        // Create a default user object for non-logged-in submissions
                        $response->user = (object) [
                            'ID' => 0,
                            'user_email' => '',
                            'display_name' => __( 'Guest', 'directorist' ),
                            'profile_url' => get_avatar_url( '' ),
                        ];
                    }
                    return $response;
                }, $responses
            );
        
            return [
                'total' => $count_query->count(),
                'responses' => $responses
            ];
        }

        public function get_kpis() {
            $query = $this->get_responses_query();
            $count_query = clone $query;
            $this_week_query = clone $query;
            $un_read_query = clone $query;
            $read_query = clone $query;

            $this_week_query->where( 'response.created_at', '>=', date( 'Y-m-d', strtotime( 'this week' ) ) );
            $un_read_query->where( 'response.is_read', 0 );
            $read_query->where( 'response.is_read', 1 );

            return [
                'total' => $count_query->count(),
                'this_week' => $this_week_query->count(),
                'unread' => $un_read_query->count(),
                'read' => $read_query->count(),
            ];
        }

        protected function get_responses_query() {
            $user_id = get_current_user_id();
            
            if ( empty( $user_id ) ) {
                return Response::query( 'response' )->where( 'response.id', 0 );
            }
            
            return Response::query( 'response' )
                ->join(
                    ResponseMeta::get_table_name() . ' as response_meta', function( $join ) {
                        $join->on_column( 'response_meta.response_id', 'response.id' )
                             ->on( 'response_meta.meta_key', 'listing_id' );
                    }
                )
                ->left_join(
                    Post::get_table_name() . ' as post', function( $join ) {
                        $join->on_raw( 'post.ID = CAST(response_meta.meta_value AS UNSIGNED)' );
                    }
                )
                ->where_not_null( 'post.post_author' )
                ->where( 'post.post_author', $user_id )
                ->where( 'post.post_status', 'publish' )
                ->where( 'response.is_completed', 1 );
        }
    }
}
