<?php

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
                ] 
            );

            register_rest_route(
                'directorist', '/formgent/responses/kpis', [
                    'methods' => 'GET',
                    'callback' => [ $this, 'get_kpis' ],
                ] 
            );

            register_rest_route(
                'directorist', '/formgent/responses', [
                    'methods' => 'DELETE',
                    'callback' => [ $this, 'delete_responses' ],
                ] 
            );
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
            $query = $this->get_responses_query();
            $count_query = clone $query;
            
            $responses = $query->select( 'response.*', 'post.post_title as listing_title', 'post.post_author as listing_owner' )->with(
                'user', function( $query ) {
                    $query->select( 'ID', 'user_email', 'display_name' );
                } 
            )->get();

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
                'un_read' => $un_read_query->count(),
                'read' => $read_query->count(),
            ];
        }

        protected function get_responses_query() {
            return Response::query( 'response' )->join(
                ResponseMeta::get_table_name() . ' as response_meta', function( $join ) {
                    $join->on_column( 'response_meta.response_id', 'response.id' )->on( 'response_meta.meta_key', 'listing_id' );
                } 
            )->left_join(
                Post::get_table_name() . ' as post', function( $join ) {
                        $join->on_column( 'post.ID', 'response_meta.meta_value' )->on( 'post.post_author', get_current_user_id() );
                }
            )->where_not_is_null( 'post.post_author' )->where( 'response.is_completed', 1 );
        }
    }
}
