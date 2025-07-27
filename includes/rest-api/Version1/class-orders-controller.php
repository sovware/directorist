<?php
/**
 * Rest Orders Controller
 *
 * @package Directorist\Rest_Api
 * @version  1.0.0
 */

namespace Directorist\Rest_Api\Controllers\Version1;

defined( 'ABSPATH' ) || exit;

use WP_Error;
use WP_Query;
use WP_REST_Server;

/**
 * Orders controller class.
 */
class Orders_Controller extends Posts_Controller {
    /**
     * Route base.
     *
     * @var string
     */
    protected $rest_base = 'orders';

    /**
     * Post type.
     *
     * @var string
     */
    protected $post_type = 'atbdp_orders';

    /**
     * Register the routes for orders.
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
                    'args'                => $this->get_collection_params(),
                ),
                array(
                    'methods'             => WP_REST_Server::CREATABLE,
                    'callback'            => array( $this, 'create_item' ),
                    'permission_callback' => array( $this, 'create_item_permissions_check' ),
                    'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::CREATABLE ),
                ),
                'schema' => array( $this, 'get_public_item_schema' ),
            )
        );

        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base . '/(?P<id>[\d]+)',
            array(
                'args'   => array(
                    'id' => array(
                        'description' => __( 'Order id.', 'directorist' ),
                        'type'        => 'integer',
                    ),
                ),
                array(
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => array( $this, 'get_item' ),
                    'permission_callback' => array( $this, 'update_item_permissions_check' ),
                    'args'                => array(
                        'context' => $this->get_context_param(
                            array(
                                'default' => 'view',
                            )
                        ),
                    ),
                ),
                'schema' => array( $this, 'get_public_item_schema' ),
            )
        );
    }

    public function get_items_permissions_check( $request ) {
        return is_user_logged_in();
    }

    public function create_item( $request ) {
        if ( ! directorist_is_monetization_enabled() ) {
            return new WP_Error( 'invalid_request', __( 'Monetization disabled.', 'directorist' ), array( 'status' => 400 ) );
        }

        if ( ! empty( $request['customer'] ) && ! get_user( $request['customer'] ) ) {
            return new WP_Error( 'invalid_customer', __( 'Invalid customer.', 'directorist' ), array( 'status' => 400 ) );
        }

        if ( empty( $request['customer'] ) ) {
            $customer_id = get_current_user_id();
        } else {
            $customer_id = $request['customer'];
        }

        $plan_id = $request['plan'];
        if ( get_post_type( $plan_id ) !== 'atbdp_pricing_plans' ) {
            return new WP_Error( 'invalid_plan', __( 'Invalid plan.', 'directorist' ), array( 'status' => 400 ) );
        }
        // To satisfy some odd stuffs.
        $_GET['plan_id'] = $plan_id;
        $_GET['plan']    = $plan_id;

        if ( isset( $request['listing'] ) && ! directorist_is_listing_post_type( $request['listing'] ) ) {
            return new WP_Error( 'invalid_listing', __( 'Invalid listing.', 'directorist' ), array( 'status' => 400 ) );
        }

        $listing_id = $request['listing'];
        if ( function_exists( 'directorist_direct_purchase' ) && directorist_direct_purchase() ) {
            $listing_id = 0;
        }

        $order_id = wp_insert_post(
            array(
                'post_title'     => sprintf( 'Order of plan #%s', $plan_id ),
                'post_status'    => 'publish',
                'post_type'      => 'atbdp_orders',
                'comment_status' => false,
                'post_author'    => $customer_id,
            ) 
        );

        if ( is_wp_error( $order_id ) ) {
            return new WP_Error( 'invalid_order', __( 'Unable to create order.', 'directorist' ), array( 'status' => 400 ) );
        }

        $order_details = apply_filters( 'atbdp_order_details', array(), $order_id, $listing_id );

        //If featured item is bought, attach it to the order.
        if ( $request['featured'] ) {
            update_post_meta( $order_id, '_featured', true );

            $order_details[] = atbdp_get_featured_settings_array();
        }

        $amount = 0.00;
        foreach ( $order_details as $order_detail ) {
            if ( isset( $order_detail['price'] ) ) {
                $amount = $order_detail['price'];
            }
        }

        $payment_status = 'created';

        if ( $request['created_by'] === 'app-store' || $request['created_by'] === 'play-store' ) {
            $amount         = $request['amount'];
            $payment_status = $request['payment_status'];

            if ( isset( $request['currency'] ) ) {
                update_post_meta( $order_id, '_currency', $request['currency'] );
            }
        }

        /**
         * Filter the order amount before tax calculation
         *
         * @since v7.4.3
         */
        $amount = apply_filters( 'directorist_order_amount_before_tax_calculation', $amount, $order_id, $request->get_params() );
        $amount = round( (float) apply_filters( 'atbdp_order_amount', $amount, $order_id ), 2 );

        $gateway = sanitize_key( $request['payment_gateway'] );
        if ( empty( $amount ) ) {
            $gateway = 'free';
        }

        if ( $request['transaction_id'] ) {
            update_post_meta( $order_id, '_transaction_id', sanitize_text_field( $request['transaction_id'] ) );
        }

        update_post_meta( $order_id, '_amount', $amount );
        update_post_meta( $order_id, '_payment_status', $payment_status );
        update_post_meta( $order_id, '_payment_gateway', $gateway );
        // update_post_meta( $order_id, '_fm_plan_ordered', $plan_id );
        update_post_meta( $order_id, '_created_by', $request['created_by'] );

        if ( $listing_id ) {
            update_post_meta( $order_id, '_listing_id', $listing_id );
            update_post_meta( $listing_id, '_plan_order_id', $order_id );
            update_post_meta( $listing_id, '_listing_order_id', $order_id );
        }

        do_action( 'atbdp_order_created', $order_id, $listing_id );

        $request->set_param( 'context', 'edit' );
        $response = $this->prepare_item_for_response( get_post( $order_id ), $request );
        $response = rest_ensure_response( $response );
        $response->set_status( 201 );

        $base = '/' . $this->namespace . '/' . $this->rest_base;

        $response->header( 'Location', rest_url( $base . '/' . $order_id ) );

        $response = apply_filters( 'directorist_rest_response', $response, 'create_order_item', $request );

        return $response;
    }

    /**
     * Get a collection of posts.
     *
     * @param \WP_REST_Request $request Full details about the request.
     * @return \WP_Error|\WP_REST_Response
     */
    public function get_items( $request ) {
        $query_args = $this->prepare_objects_query( $request );

        do_action( 'directorist_rest_before_query', 'get_order_items', $request, $query_args );

        $query_results = $this->get_orders( $query_args );

        $objects = array();
        foreach ( $query_results['objects'] as $object ) {
            // if ( ! $this->check_post_permissions( $this->post_type, 'edit', $object->ID ) ) {
            //  continue;
            // }

            $data = $this->prepare_item_for_response( $object, $request );
            $objects[] = $this->prepare_response_for_collection( $data );
        }

        $page      = (int) $query_args['paged'];
        $max_pages = $query_results['pages'];

        $response = rest_ensure_response( $objects );
        $response->header( 'X-WP-Total', $query_results['total'] );
        $response->header( 'X-WP-TotalPages', (int) $max_pages );

        $base = add_query_arg( $request->get_query_params(), rest_url( sprintf( '/%s/%s', $this->namespace, $this->rest_base ) ) );

        if ( $page > 1 ) {
            $prev_page = $page - 1;
            if ( $prev_page > $max_pages ) {
                $prev_page = $max_pages;
            }
            $prev_link = add_query_arg( 'page', $prev_page, $base );
            $response->link_header( 'prev', $prev_link );
        }

        if ( $max_pages > $page ) {
            $next_page = $page + 1;
            $next_link = add_query_arg( 'page', $next_page, $base );
            $response->link_header( 'next', $next_link );
        }

        do_action( 'directorist_rest_after_query', 'get_order_items', $request, $query_args );

        $response = apply_filters( 'directorist_rest_response', $response, 'get_order_items', $request, $query_args );

        return $response;
    }

    protected function get_orders( $query_args ) {
        $query  = new WP_Query();
        $result = $query->query( $query_args );

        $total_posts = $query->found_posts;
        if ( $total_posts < 1 ) {
            // Out-of-bounds, run the query again without LIMIT for total count.
            unset( $query_args['paged'] );
            $count_query = new WP_Query();
            $count_query->query( $query_args );
            $total_posts = $count_query->found_posts;
        }

        return array(
            'objects' => $result,
            'total'   => (int) $total_posts,
            'pages'   => (int) ceil( $total_posts / (int) $query->query_vars['posts_per_page'] ),
        );
    }

    /**
     * Prepare objects query.
     *
     * @param  \WP_REST_Request $request Full details about the request.
     * @return array
     */
    protected function prepare_objects_query( $request ) {
        $args                   = [];
        $args['order']          = $request['order'];
        $args['orderby']        = $request['orderby'];
        $args['paged']          = $request['page'];
        $args['author']         = $request['customer'];
        $args['posts_per_page'] = $request['per_page'];

        /**
         * Filter the query arguments for a request.
         *
         * Enables adding extra arguments or setting defaults for a post
         * collection request.
         *
         * @param array           $args    Key value array of query var to query value.
         * @param \WP_REST_Request $request The request used.
         */
        $args = apply_filters( "directorist_rest_{$this->post_type}_object_query", $args, $request );

        // Force the post_type argument, since it's not a user input variable.
        $args['post_type'] = $this->post_type;

        return $this->prepare_items_query( $args, $request );
    }

    /**
     * Add post meta fields.
     *
     * @param \WP_Post         $post Post Object.
     * @param \WP_REST_Request $request WP_REST_Request Object.
     * @return bool|\WP_Error
     */
    protected function add_post_meta_fields( $post, $request ) {
        return true;
    }

    /**
     * Get a single item.
     *
     * @param \WP_REST_Request $request Full details about the request.
     * @return \WP_Error|\WP_REST_Response
     */
    public function get_item( $request ) {
        $id = (int) $request['id'];

        do_action( 'directorist_rest_before_query', 'get_order_item', $request, $id );

        $post = get_post( $id );

        if ( empty( $id ) || empty( $post->ID ) || $post->post_type !== $this->post_type ) {
            return new WP_Error( "directorist_rest_invalid_{$this->post_type}_id", __( 'Invalid ID.', 'directorist' ), array( 'status' => 404 ) );
        }

        $data = $this->prepare_item_for_response( $post, $request );
        $response = rest_ensure_response( $data );

        do_action( 'directorist_rest_after_query', 'get_order_item', $request, $id );

        $response = apply_filters( 'directorist_rest_response', $response, 'get_order_item', $request, $id );

        return $response;
    }

    /**
     * Prepare a single orders output for response.
     *
     * @param \WP_Post         $object  Object data.
     * @param \WP_REST_Request $request Request object.
     *
     * @return \WP_REST_Response
     */
    public function prepare_item_for_response( $object, $request ) {
        $context       = ! empty( $request['context'] ) ? $request['context'] : 'view';
        $this->request = $request;
        $data          = $this->get_order_data( $object, $request, $context );

        $data     = $this->add_additional_fields_to_object( $data, $request );
        $data     = $this->filter_response_by_context( $data, $context );

        $response = rest_ensure_response( $data );
        $response->add_links( $this->prepare_links( $object, $request ) );

        /**
         * Filter the data for a response.
         *
         * The dynamic portion of the hook name, $this->post_type,
         * refers to object type being prepared for the response.
         *
         * @param \WP_REST_Response $response The response object.
         * @param \WP_Post          $object   Object data.
         * @param \WP_REST_Request  $request  Request object.
         */
        return apply_filters( "directorist_rest_prepare_{$this->post_type}_object", $response, $object, $request );
    }

    /**
     * Get order data.
     *
     * @param \WP_Post   $order WP_Post instance.
     * @param \WP_REST_Request $request Request object.
     * @param string    $context Request context. Options: 'view' and 'edit'.
     *
     * @return array
     */
    protected function get_order_data( $order, $request, $context = 'view' ) {
        $fields  = $this->get_fields_for_response( $request );

        $data = array();
        foreach ( $fields as $field ) {
            switch ( $field ) {
                case 'id':
                    $data[ $field ] = $order->ID;
                    break;
                case 'title':
                    $data[ $field ] = get_the_title( $order );
                    break;
                case 'date_created':
                    $data[ $field ] = directorist_rest_prepare_date_response( $order->post_date, false );
                    break;
                case 'date_modified':
                    $data[ $field ] = directorist_rest_prepare_date_response( $order->post_date_modified, false );
                    break;
                case 'customer':
                    $data[ $field ] = (int) $order->post_author;
                    break;
                case 'plan':
                    $data[ $field ] = $this->get_plan_id( $order );
                    break;
                case 'directory':
                    $data[ $field ] = (int) get_post_meta( $this->get_plan_id( $order ), '_assign_to_directory', true );
                    break;
                case 'listing':
                    $data[ $field ] = (int) get_post_meta( $order->ID, '_listing_id', true );
                    break;
                case 'featured':
                    $data[ $field ] = (bool) get_post_meta( $order->ID, '_featured', true );
                    break;
                case 'remaining_listings':
                    $data[ $field ] = $this->get_remaining_listings_count( $order );
                    break;
                case 'remaining_featured_listings':
                    $data[ $field ] = $this->get_remaining_featured_listings_count( $order );
                    break;
                case 'amount':
                    $data[ $field ] = round( (float) get_post_meta( $order->ID, '_amount', true ), 2 );
                    break;
                case 'currency':
                    $data[ $field ] = get_post_meta( $order->ID, '_currency', true );
                    break;
                case 'payment_status':
                    $data[ $field ] = get_post_meta( $order->ID, '_payment_status', true );
                    break;
                case 'payment_gateway':
                    $data[ $field ] = get_post_meta( $order->ID, '_payment_gateway', true );
                    break;
                case 'transaction_id':
                    $data[ $field ] = get_post_meta( $order->ID, '_transaction_id', true );
                    break;
                case 'created_by':
                    $data[ $field ] = get_post_meta( $order->ID, '_created_by', true );
                    break;
            }
        }

        return $data;
    }

    /**
     * Prepare links for the request.
     *
     * @param WP_Post         $object  Object data.
     * @param WP_REST_Request $request Request object.
     *
     * @return array Links for the given post.
     */
    protected function prepare_links( $object, $request ) {
        $links = array(
            'self'       => array(
				'href' => rest_url( sprintf( '/%s/%s/%d', $this->namespace, $this->rest_base, $object->ID ) ),  // @codingStandardsIgnoreLine.
            ),
            'collection' => array(
				'href' => rest_url( sprintf( '/%s/%s', $this->namespace, $this->rest_base ) ),  // @codingStandardsIgnoreLine.
            ),
        );

        return $links;
    }

    /**
     * Get the orders's schema, conforming to JSON Schema.
     *
     * @return array
     */
    public function get_item_schema() {
        $schema         = array(
            '$schema'    => 'http://json-schema.org/draft-04/schema#',
            'title'      => $this->post_type,
            'type'       => 'object',
            'properties' => array(
                'id'                    => array(
                    'description' => __( 'Order id.', 'directorist' ),
                    'type'        => 'integer',
                    'context'     => array( 'view', 'edit' ),
                    'readonly'    => true,
                ),
                'title'                  => array(
                    'description' => __( 'Order title.', 'directorist' ),
                    'type'        => 'string',
                    'context'     => array( 'view', 'edit' ),
                    'readonly'    => true,
                ),
                'date_created'          => array(
                    'description' => __( "The date the order was created, in the site's timezone.", 'directorist' ),
                    'type'        => 'date-time',
                    'context'     => array( 'view', 'edit' ),
                    'readonly'    => true,
                ),
                'date_modified'         => array(
                    'description' => __( "The date the order was last modified, in the site's timezone.", 'directorist' ),
                    'type'        => 'date-time',
                    'context'     => array( 'view', 'edit' ),
                    'readonly'    => true,
                ),
                'customer'           => array(
                    'description' => __( 'Customer id.', 'directorist' ),
                    'type'        => 'integer',
                    'context'     => array( 'view', 'edit' ),
                ),
                'plan'           => array(
                    'description' => __( 'Pricing plan id.', 'directorist' ),
                    'type'        => 'integer',
                    'required'    => true,
                    'context'     => array( 'view', 'edit' ),
                ),
                'directory'         => array(
                    'description' => __( 'Directory type of the plan.', 'directorist' ),
                    'type'        => 'integer',
                    'context'     => array( 'view' ),
                    'readonly'    => true,
                ),
                'listing'           => array(
                    'description' => __( 'Listing id.', 'directorist' ),
                    'type'        => 'integer',
                    'context'     => array( 'view', 'edit' ),
                    'required'    => ( ! function_exists( 'directorist_direct_purchase' ) || ! directorist_direct_purchase() )
                ),
                'featured'           => array(
                    'description' => __( 'Featured status.', 'directorist' ),
                    'type'        => 'boolean',
                    'context'     => array( 'view', 'edit' ),
                ),
                'remaining_listings'           => array(
                    'description' => __( 'Remaining regular listings count.', 'directorist' ),
                    'type'        => 'integer',
                    'context'     => array( 'view' ),
                    'readonly'    => true,
                ),
                'remaining_featured_listings'           => array(
                    'description' => __( 'Remaining featured listings count.', 'directorist' ),
                    'type'        => 'integer',
                    'context'     => array( 'view' ),
                    'readonly'    => true,
                ),
                'amount'           => array(
                    'description' => __( 'Payment amount.', 'directorist' ),
                    'type'        => 'float',
                    'context'     => array( 'view', 'edit' ),
                ),
                'currency'           => array(
                    'description' => __( 'Payment currency.', 'directorist' ),
                    'type'        => 'string',
                    'context'     => array( 'view', 'edit' ),
                ),
                'payment_status' => array(
                    'description' => __( 'Payment status.', 'directorist' ),
                    'type'        => 'string',
                    'context'     => array( 'view', 'edit' ),
                    'required'    => true,
                    'enum'        => array_keys( atbdp_get_payment_statuses() )
                ),
                'payment_gateway' => array(
                    'description' => __( 'Payment gateway.', 'directorist' ),
                    'type'        => 'string',
                    'context'     => array( 'view', 'edit' ),
                    'default'     => 'free',
                ),
                'transaction_id' => array(
                    'description' => __( 'Transaction id.', 'directorist' ),
                    'type'        => 'string',
                    'context'     => array( 'view', 'edit' ),
                ),
                'created_by' => array(
                    'description' => __( 'Created by.', 'directorist' ),
                    'type'        => 'string',
                    'required'    => true,
                    'context'     => array( 'view', 'edit' ),
                    'enum'        => array( 'app-store', 'play-store', 'web' )
                ),
            ),
        );

        return $this->add_additional_fields_schema( $schema );
    }

    /**
     * Get the query params for collections of orders.
     *
     * @return array
     */
    public function get_collection_params() {
        $params = parent::get_collection_params();

        $params['context']['default'] = 'view';

        unset( $params['search'] );

        $params['order'] = array(
            'default'            => 'desc',
            'description'        => __( 'Order sort attribute ascending or descending.', 'directorist' ),
            'enum'               => array( 'asc', 'desc' ),
            'type'               => 'string',
            'sanitize_callback'  => 'sanitize_key',
        );
        $params['orderby'] = array(
            'description'        => __( 'Sort collection by object attribute.', 'directorist' ),
            'enum'               => array_keys( $this->get_orderby_possibles() ),
            'type'               => 'string',
            'default'            => 'date',
            'sanitize_callback'  => 'sanitize_key',
        );
        $params['customer'] = array(
            'description'       => __( 'Limit result set to specific customer id.', 'directorist' ),
            'type'              => 'integer',
            'sanitize_callback' => 'absint',
            'validate_callback' => 'rest_validate_request_arg',
        );

        return $params;
    }

    protected function get_orderby_possibles() {
        return array(
            'customer' => 'author',
            'date'     => 'date',
        );
    }

    protected function get_plan_id( $order ) {
        return (int) get_post_meta( $order->ID, '_fm_plan_ordered', true );
    }

    protected function get_remaining_listings_count( $order ) {
        $plan_id      = $this->get_plan_id( $order );
        $is_unlimited = (bool) get_post_meta( $plan_id, 'num_regular_unl', true );

        if ( $is_unlimited ) {
            return -1;
        }

        $total_count = directorist_rest_count_regular_paid_listings( (int) $order->post_author, $plan_id, (int) $order->ID );
        $limit       = (int) get_post_meta( $plan_id, 'num_regular', true );

        return ( $limit - $total_count );
    }

    protected function get_remaining_featured_listings_count( $order ) {
        $plan_id      = $this->get_plan_id( $order );
        $is_unlimited = (bool) get_post_meta( $plan_id, 'num_featured_unl', true );

        if ( $is_unlimited ) {
            return -1;
        }

        $total_count = directorist_rest_count_featured_paid_listings( (int) $order->post_author, $plan_id, (int) $order->ID );
        $limit       = (int) get_post_meta( $plan_id, 'num_featured', true );

        return ( $limit - $total_count );
    }
}
