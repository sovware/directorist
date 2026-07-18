<?php

use FormGent\App\DTO\ResponseSingleDTO;
use FormGent\App\Models\Post;
use FormGent\App\Models\Response;
use FormGent\App\Models\ResponseMeta;

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'ATBDP_Formgent' ) ) {
    class ATBDP_Formgent
    {
        protected static $hooks_registered = false;
        public function __construct() {
            if ( self::$hooks_registered ) {
                return;
            }

            self::$hooks_registered = true;

            add_action( 'formgent_after_create_form_response_token', [ $this, 'after_create_form_response_token' ], 10, 3 );
            add_action( 'rest_api_init', [ $this, 'rest_api_init' ] );

            add_filter( 'formgent_email_send_to', [ $this, 'route_email_to_listing_owner' ], 10, 5 );
        }

        public function after_create_form_response_token( $response_token, $dto, \WP_REST_Request $wp_rest_request ) {
            $external_data = $wp_rest_request->get_param( 'external_data' );

            if ( empty( $external_data['listing_id'] ) ) {
                return;
            }

            $response_repository = formgent_response_repository();
            $response_repository->add_meta( $dto->get_id(), 'listing_id', absint( $external_data['listing_id'] ) );
        }

        public function route_email_to_listing_owner( $send_to, $email, $response, $form_answers_data, $queue ) {
            $response_id = is_object( $queue ) && ! empty( $queue->response_id ) ? absint( $queue->response_id ) : 0;

            if ( empty( $response_id ) ) {
                return $send_to;
            }

            $listing_id = absint( formgent_response_repository()->get_meta_value( $response_id, 'listing_id' ) );

            if ( empty( $listing_id ) ) {
                return $send_to;
            }

            $should_route = ! $this->recipient_matches_form_email_answer( $send_to, $form_answers_data );

            $should_route = (bool) apply_filters(
                'directorist_formgent_route_email_to_listing_owner',
                $should_route,
                $listing_id,
                $send_to,
                $email,
                $response,
                $form_answers_data,
                $queue
            );

            if ( ! $should_route ) {
                return $send_to;
            }

            $recipient = $this->get_listing_owner_email_recipient( $listing_id );

            $recipient = apply_filters(
                'directorist_formgent_listing_owner_email_recipient',
                $recipient,
                $listing_id,
                $send_to,
                $email,
                $response,
                $form_answers_data,
                $queue
            );

            $listing_owner_recipients = $this->normalize_email_recipients( $recipient );

            if ( empty( $listing_owner_recipients ) ) {
                return $send_to;
            }

            $send_to_recipients = $this->normalize_email_recipients( $send_to );
            $recipients         = array_values( array_unique( array_merge( $send_to_recipients, $listing_owner_recipients ) ) );

            return count( $recipients ) > 1 ? $recipients : reset( $recipients );
        }

        protected function get_listing_owner_email_recipient( $listing_id ) {
            $post_author_id = absint( get_post_field( 'post_author', $listing_id ) );

            if ( empty( $post_author_id ) ) {
                return '';
            }

            $contact_recipient = get_user_meta( $post_author_id, 'directorist_contact_owner_recipient', true );
            $recipient_type    = ! empty( $contact_recipient ) ? $contact_recipient : 'author';

            if ( 'listing_email' === $recipient_type ) {
                $listing_email = sanitize_email( get_post_meta( $listing_id, '_email', true ) );

                if ( is_email( $listing_email ) ) {
                    return $listing_email;
                }
            }

            $user = get_userdata( $post_author_id );

            if ( empty( $user->user_email ) ) {
                return '';
            }

            return sanitize_email( $user->user_email );
        }

        protected function recipient_matches_form_email_answer( $send_to, $form_answers_data ) {
            $send_to_emails = $this->normalize_email_recipients( $send_to );

            if ( empty( $send_to_emails ) || empty( $form_answers_data ) || ! is_array( $form_answers_data ) ) {
                return false;
            }

            foreach ( $form_answers_data as $answer ) {
                $field_type = '';
                $value      = '';

                if ( is_object( $answer ) ) {
                    if ( method_exists( $answer, 'get_field_type' ) ) {
                        $field_type = $answer->get_field_type();
                    }

                    if ( method_exists( $answer, 'get_value' ) ) {
                        $value = $answer->get_value();
                    }
                } elseif ( is_array( $answer ) ) {
                    $field_type = $answer['field_type'] ?? '';
                    $value      = $answer['value'] ?? '';
                }

                if ( 'email' !== $field_type ) {
                    continue;
                }

                $answer_emails = $this->normalize_email_recipients( $value );

                if ( array_intersect( $send_to_emails, $answer_emails ) ) {
                    return true;
                }
            }

            return false;
        }

        protected function normalize_email_recipients( $emails ) {
            if ( empty( $emails ) ) {
                return [];
            }

            if ( is_array( $emails ) ) {
                $normalized = [];

                foreach ( $emails as $email ) {
                    $normalized = array_merge( $normalized, $this->normalize_email_recipients( $email ) );
                }

                return array_values( array_unique( $normalized ) );
            }

            if ( ! is_string( $emails ) ) {
                return [];
            }

            preg_match_all( '/[A-Z0-9._%+\-]+@[A-Z0-9.\-]+\.[A-Z]{2,}/i', $emails, $matches );
            $emails = ! empty( $matches[0] ) ? $matches[0] : preg_split( '/[,;]/', $emails );

            $normalized = [];

            foreach ( $emails as $email ) {
                $email = sanitize_email( trim( $email ) );

                if ( is_email( $email ) ) {
                    $normalized[] = strtolower( $email );
                }
            }

            return array_values( array_unique( $normalized ) );
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
            $fields          = $this->prepare_response_fields( $fields_settings );

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

        protected function prepare_response_fields( array $fields_settings, $parent_name = '', $parent_type = '' ) {
            $registered_fields = formgent_config( 'fields' );
            $fields = [];

            foreach ( $fields_settings as $field ) {
                if ( empty( $field['field_type'] ) || empty( $field['name'] ) || empty( $registered_fields[$field['field_type']]['allowed_in_response_table'] ) ) {
                    continue;
                }

                $name = ! empty( $parent_name ) ? $parent_name . '.' . $field['name'] : $field['name'];

                if ( ! empty( $field['children'] ) && is_array( $field['children'] ) ) {
                    $fields = array_merge( $fields, $this->prepare_response_fields( $field['children'], $name, $field['field_type'] ) );
                    continue;
                }

                $field_data = [
                    'name'  => sanitize_text_field( $name ),
                    'label' => isset( $field['label'] ) ? sanitize_text_field( $field['label'] ) : '',
                    'type'  => sanitize_text_field( $field['field_type'] ),
                ];

                if ( in_array( $field['field_type'], [ 'dropdown', 'single-choice', 'multiple-choice' ], true ) ) {
                    if ( 'dropdown' !== $field['field_type'] || 'address' !== $parent_type ) {
                        if ( ! empty( $field['options'] ) && is_array( $field['options'] ) ) {
                            $field_data['options'] = $this->sanitize_response_field_options( $field['options'] );
                        }
                    }
                }

                if ( in_array( $field['field_type'], [ 'single-choice', 'multiple-choice' ], true ) ) {
                    if ( isset( $field['allow_user_add_other_option'] ) ) {
                        $field_data['allow_user_add_other_option'] = (bool) $field['allow_user_add_other_option'];
                    }

                    if ( isset( $field['other_label'] ) ) {
                        $field_data['other_label'] = sanitize_text_field( $field['other_label'] );
                    }

                    if ( isset( $field['other_placeholder'] ) ) {
                        $field_data['other_placeholder'] = sanitize_text_field( $field['other_placeholder'] );
                    }
                }

                if ( 'range-slider' === $field['field_type'] ) {
                    if ( isset( $field['min_value'] ) && is_numeric( $field['min_value'] ) ) {
                        $field_data['min_value'] = floatval( $field['min_value'] );
                    }

                    if ( isset( $field['max_value'] ) && is_numeric( $field['max_value'] ) ) {
                        $field_data['max_value'] = floatval( $field['max_value'] );
                    }
                }

                if ( 'rating' === $field['field_type'] ) {
                    if ( isset( $field['rating_icon'] ) ) {
                        $field_data['rating_icon'] = sanitize_text_field( $field['rating_icon'] );
                    }

                    if ( isset( $field['rating_limit'] ) && is_numeric( $field['rating_limit'] ) ) {
                        $field_data['rating_limit'] = absint( $field['rating_limit'] );
                    }
                }

                if ( 'date-picker' === $field['field_type'] ) {
                    if ( isset( $field['option'] ) && is_string( $field['option'] ) ) {
                        $field_data['option'] = sanitize_text_field( $field['option'] );
                    }

                    if ( isset( $field['range'] ) ) {
                        $field_data['range'] = (bool) $field['range'];
                    }

                    if ( isset( $field['date_format'] ) && is_string( $field['date_format'] ) ) {
                        $field_data['date_format'] = sanitize_text_field( $field['date_format'] );
                    }

                    if ( isset( $field['separator'] ) && is_string( $field['separator'] ) ) {
                        $field_data['separator'] = sanitize_text_field( $field['separator'] );
                    }

                    if ( isset( $field['allowed_days'] ) && is_array( $field['allowed_days'] ) ) {
                        $field_data['allowed_days'] = array_map( 'sanitize_text_field', $field['allowed_days'] );
                    }
                }

                $fields[] = $field_data;
            }

            return $fields;
        }

        protected function sanitize_response_field_options( array $options ) {
            $sanitized_options = [];

            foreach ( $options as $option ) {
                if ( ! is_array( $option ) ) {
                    continue;
                }

                $sanitized_option = [];

                if ( isset( $option['label'] ) ) {
                    $sanitized_option['label'] = sanitize_text_field( $option['label'] );
                }

                if ( isset( $option['value'] ) ) {
                    $sanitized_option['value'] = sanitize_text_field( $option['value'] );
                }

                if ( ! empty( $sanitized_option ) ) {
                    $sanitized_options[] = $sanitized_option;
                }
            }

            return $sanitized_options;
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
