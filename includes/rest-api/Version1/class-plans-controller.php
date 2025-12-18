<?php
/**
 * Rest Plans Controller
 *
 * @package Directorist\Rest_Api
 * @version  1.0.0
 */

namespace Directorist\Rest_Api\Controllers\Version1;

defined( 'ABSPATH' ) || exit;

use WP_Error;
use WP_Query;
use WP_REST_Server;
use Directorist\Helper;

/**
 * Plans controller class.
 */
class Plans_Controller extends Posts_Controller {
    /**
     * Route base.
     *
     * @var string
     */
    protected $rest_base = 'plans';

    /**
     * Post type.
     *
     * @var string
     */
    protected $post_type = 'atbdp_pricing_plans';

    /**
     * Active pricing plan plugin type.
     * 'atbdp' for directorist-pricing-plans, 'dwpp' for directorist-woocommerce-pricing-plans
     *
     * @var string
     */
    protected $active_plugin_type = null;

    /**
     * Register the routes for plans.
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
                'schema' => array( $this, 'get_public_item_schema' ),
            )
        );

        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base . '/(?P<id>[\d]+)',
            array(
                'args'   => array(
                    'id' => array(
                        'description' => __( 'Plan id.', 'directorist' ),
                        'type'        => 'integer',
                    ),
                ),
                array(
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => array( $this, 'get_item' ),
                    'permission_callback' => array( $this, 'get_item_permissions_check' ),
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

    /**
     * Get active pricing plan plugin type.
     * Priority: directorist-pricing-plans > directorist-woocommerce-pricing-plans
     * 
     * Note: Plugins are mutually exclusive - only one can be active at a time.
     * This method checks both class existence and admin settings.
     *
     * @return string 'atbdp' or 'dwpp' or null
     */
    protected function get_active_plugin_type() {
        if ( null !== $this->active_plugin_type ) {
            return $this->active_plugin_type;
        }

        // Priority: directorist-pricing-plans first
        // Check class exists AND admin setting is enabled
        if ( class_exists( 'ATBDP_Pricing_Plans' ) ) {
            $fee_manager_enabled = get_directorist_option( 'fee_manager_enable', 1 );
            if ( $fee_manager_enabled ) {
                $this->active_plugin_type = 'atbdp';
                return $this->active_plugin_type;
            }
        }

        // Fallback to WooCommerce pricing plans
        // Check class exists AND admin setting is enabled
        if ( class_exists( 'DWPP_Pricing_Plans' ) ) {
            $woo_pricing_plans_enabled = get_directorist_option( 'woo_pricing_plans_enable', 1 );
            if ( $woo_pricing_plans_enabled ) {
                $this->active_plugin_type = 'dwpp';
                return $this->active_plugin_type;
            }
        }

        $this->active_plugin_type = null;
        return $this->active_plugin_type;
    }

    public function get_items_permissions_check( $request ) {
        if ( ! is_fee_manager_active() ) {
            return new WP_Error( 'extension_inactive', __( 'Pricing plan extension disabled.', 'directorist' ), array( 'status' => 400 ) );
        }
        
        $plugin_type = $this->get_active_plugin_type();
        if ( ! $plugin_type ) {
            return new WP_Error( 'extension_inactive', __( 'Pricing plan extension inactive.', 'directorist' ), array( 'status' => 400 ) );
        }

        // Verify post type is registered
        $post_type = ( 'dwpp' === $plugin_type ) ? 'product' : $this->post_type;
        if ( ! post_type_exists( $post_type ) ) {
            return new WP_Error( 'post_type_not_registered', __( 'Pricing plans post type "' . $post_type . '" is not registered.', 'directorist' ), array( 'status' => 500 ) );
        }
        
        return parent::get_items_permissions_check( $request );
    }

    public function get_item_permissions_check( $request ) {
        if ( ! is_fee_manager_active() ) {
            return new WP_Error( 'extension_inactive', __( 'Pricing plan extension disabled.', 'directorist' ), array( 'status' => 400 ) );
        }
        
        $plugin_type = $this->get_active_plugin_type();
        if ( ! $plugin_type ) {
            return new WP_Error( 'extension_inactive', __( 'Pricing plan extension inactive.', 'directorist' ), array( 'status' => 400 ) );
        }

        // Verify post type is registered
        $post_type = ( 'dwpp' === $plugin_type ) ? 'product' : $this->post_type;
        if ( ! post_type_exists( $post_type ) ) {
            return new WP_Error( 'post_type_not_registered', __( 'Pricing plans post type "' . $post_type . '" is not registered.', 'directorist' ), array( 'status' => 500 ) );
        }
        
        return parent::get_item_permissions_check( $request );
    }

    /**
     * Get a collection of posts.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function get_items( $request ) {
        $query_args = $this->prepare_objects_query( $request );

        do_action( 'directorist_rest_before_query', 'get_plan_items', $request, $query_args );

        $query_results = $this->get_plans( $query_args );

        $objects = array();
        $plugin_type = $this->get_active_plugin_type();
        $post_type = ( 'dwpp' === $plugin_type ) ? 'product' : $this->post_type;
        
        foreach ( $query_results['objects'] as $object ) {
            if ( ! $this->check_post_permissions( $post_type, 'read', $object->ID ) ) {
                continue;
            }

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

        do_action( 'directorist_rest_after_query', 'get_plan_items', $request, $query_args );

        $response = apply_filters( 'directorist_rest_response', $response, 'get_plan_items', $request, $query_args );

        return $response;
    }

    protected function get_plans( $query_args ) {
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
     * @param  WP_REST_Request $request Full details about the request.
     * @return array
     */
    protected function prepare_objects_query( $request ) {
        $plugin_type = $this->get_active_plugin_type();
        $post_type = ( 'dwpp' === $plugin_type ) ? 'product' : $this->post_type;

        $args                   = [];
        $args['order']          = $request['order'];
        $args['orderby']        = $request['orderby'];
        $args['paged']          = $request['page'];
        $args['posts_per_page'] = $request['per_page'];

        // For WooCommerce products, add tax query for product type
        if ( 'dwpp' === $plugin_type ) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'product_type',
                    'field'    => 'slug',
                    'terms'    => 'listing_pricing_plans',
                ),
            );
        }

        if ( directorist_is_multi_directory_enabled() && ! empty( $request['directory'] ) ) {
            $args['meta_key'] = '_assign_to_directory';
            $args['meta_value'] = $request['directory'];
        }

        if ( ! directorist_is_multi_directory_enabled() ) {
            $args['meta_key'] = '_assign_to_directory';
            $args['meta_value'] = directorist_get_default_directory();
        }

        /**
         * Filter the query arguments for a request.
         *
         * Enables adding extra arguments or setting defaults for a post
         * collection request.
         *
         * @param array           $args    Key value array of query var to query value.
         * @param WP_REST_Request $request The request used.
         */
        $args = apply_filters( "directorist_rest_{$this->post_type}_object_query", $args, $request );

        // Force the post_type argument, since it's not a user input variable.
        $args['post_type'] = $post_type;

        return $this->prepare_items_query( $args, $request );
    }

    /**
     * Add post meta fields.
     *
     * @param WP_Post         $post Post Object.
     * @param WP_REST_Request $request WP_REST_Request Object.
     * @return bool|WP_Error
     */
    protected function add_post_meta_fields( $post, $request ) {
        return true;
    }

    /**
     * Get a single item.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function get_item( $request ) {
        $id = (int) $request['id'];

        do_action( 'directorist_rest_before_query', 'get_plan_item', $request, $id );

        $post = get_post( $id );
        $plugin_type = $this->get_active_plugin_type();
        $expected_post_type = ( 'dwpp' === $plugin_type ) ? 'product' : $this->post_type;

        if ( empty( $id ) || empty( $post->ID ) || $post->post_type !== $expected_post_type ) {
            return new WP_Error( "directorist_rest_invalid_{$this->post_type}_id", __( 'Invalid ID.', 'directorist' ), array( 'status' => 404 ) );
        }

        // For WooCommerce products, verify it's a pricing plan product
        if ( 'dwpp' === $plugin_type ) {
            $product = wc_get_product( $id );
            if ( ! $product || ! $product->is_type( 'listing_pricing_plans' ) ) {
                return new WP_Error( "directorist_rest_invalid_{$this->post_type}_id", __( 'Invalid ID.', 'directorist' ), array( 'status' => 404 ) );
            }
        }

        $data = $this->prepare_item_for_response( $post, $request );
        $response = rest_ensure_response( $data );

        do_action( 'directorist_rest_after_query', 'get_plan_item', $request, $id );

        $response = apply_filters( 'directorist_rest_response', $response, 'get_plan_item', $request, $id );

        return $response;
    }

    /**
     * Prepare a single plans output for response.
     *
     * @param WP_Post         $object  Object data.
     * @param WP_REST_Request $request Request object.
     *
     * @return WP_REST_Response
     */
    public function prepare_item_for_response( $object, $request ) {
        $context       = ! empty( $request['context'] ) ? $request['context'] : 'view';
        $this->request = $request;
        $data          = $this->get_plan_data( $object, $request, $context );

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
         * @param WP_REST_Response $response The response object.
         * @param WP_Post          $object   Object data.
         * @param WP_REST_Request  $request  Request object.
         */
        return apply_filters( "directorist_rest_prepare_{$this->post_type}_object", $response, $object, $request );
    }

    protected function get_directory_id( $plan ) {
        return (int) get_post_meta( $plan->ID, '_assign_to_directory', true );
    }

    /**
     * Get meta value based on active plugin type.
     *
     * @param WP_Post $plan Plan post object.
     * @param string  $meta_key Meta key (for regular pricing plans).
     * @param string  $dwpp_meta_key Meta key for WooCommerce pricing plans (optional, uses $meta_key if not provided).
     * @param mixed   $default Default value.
     * @return mixed
     */
    protected function get_plan_meta( $plan, $meta_key, $dwpp_meta_key = null, $default = '' ) {
        $plugin_type = $this->get_active_plugin_type();
        $key = ( 'dwpp' === $plugin_type && null !== $dwpp_meta_key ) ? $dwpp_meta_key : $meta_key;
        $value = get_post_meta( $plan->ID, $key, true );
        return ( '' !== $value ) ? $value : $default;
    }

    /**
     * Get plan price based on active plugin type.
     *
     * @param WP_Post $plan Plan post object.
     * @return float
     */
    protected function get_plan_price( $plan ) {
        $plugin_type = $this->get_active_plugin_type();
        
        if ( 'dwpp' === $plugin_type ) {
            $product = wc_get_product( $plan->ID );
            if ( $product ) {
                $sale_price = $product->get_sale_price();
                if ( '' !== $sale_price && null !== $sale_price ) {
                    return (float) $sale_price;
                }
                return (float) $product->get_regular_price();
            }
            return 0.0;
        }
        
        return (float) $this->get_plan_meta( $plan, 'fm_price', null, 0 );
    }

    /**
     * Get plan data.
     *
     * @param WP_Post   $plan WP_Post instance.
     * @param WP_REST_Request $request Request object.
     * @param string    $context Request context. Options: 'view' and 'edit'.
     *
     * @return array
     */
    protected function get_plan_data( $plan, $request, $context = 'view' ) {
        $fields  = $this->get_fields_for_response( $request );

        $base_data = array();
        foreach ( $fields as $field ) {
            switch ( $field ) {
                case 'id':
                    $base_data['id'] = $plan->ID;
                    break;
                case 'name':
                    $base_data['name'] = get_the_title( $plan );
                    break;
                case 'slug':
                    $base_data['slug'] = $plan->post_name;
                    break;
                case 'date_created':
                    $base_data['date_created'] = directorist_rest_prepare_date_response( $plan->post_date, false );
                    break;
                case 'date_created_gmt':
                    $base_data['date_created_gmt'] = directorist_rest_prepare_date_response( $plan->post_date_gmt );
                    break;
                case 'date_modified':
                    $base_data['date_modified'] = directorist_rest_prepare_date_response( $plan->post_date_modified, false );
                    break;
                case 'date_modified_gmt':
                    $base_data['date_modified_gmt'] = directorist_rest_prepare_date_response( $plan->post_date_modified_gmt );
                    break;
                case 'description':
                    $base_data['description'] = $this->get_plan_meta( $plan, 'fm_description' );
                    break;
                case 'hide_description_from_plan':
                    $base_data['hide_description_from_plan'] = (bool) $this->get_plan_meta( $plan, 'hide_description', 'hide_description' );
                    break;
                case 'directory':
                    $base_data['directory'] = $this->get_directory_id( $plan );
                    break;
                case 'status':
                    $base_data['status'] = $plan->post_status;
                    break;
                case 'is_recommended':
                    $base_data['is_recommended'] = ( $this->get_plan_meta( $plan, 'default_pln' ) === 'yes' );
                    break;
                case 'is_hidden':
                    $base_data['is_hidden'] = ( $this->get_plan_meta( $plan, '_hide_from_plans' ) === 'yes' );
                    break;
                case 'type':
                    $base_data['type'] = $this->get_plan_type( $plan );
                    break;
                case 'type_label':
                    $base_data['type_label'] = $this->get_plan_type( $plan ) === 'package' ? esc_html__( 'Per Package', 'directorist' ) : esc_html__( 'Per Listing', 'directorist' );
                    break;
                case 'currency':
                    $base_data['currency'] = atbdp_get_payment_currency();
                    break;
                case 'currency_symbol':
                    $base_data['currency_symbol'] = html_entity_decode( atbdp_currency_symbol( atbdp_get_payment_currency() ) );
                    break;
                case 'is_free':
                    $plugin_type = $this->get_active_plugin_type();
                    if ( 'dwpp' === $plugin_type ) {
                        $product = wc_get_product( $plan->ID );
                        $base_data['is_free'] = $product ? ( (float) $product->get_price() <= 0 ) : false;
                    } else {
                        $base_data['is_free'] = (bool) $this->get_plan_meta( $plan, 'free_plan' );
                    }
                    break;
                case 'price':
                    $base_data['price'] = $this->get_plan_price( $plan );
                    break;
                case 'is_taxable':
                    $base_data['is_taxable'] = (bool) $this->get_plan_meta( $plan, 'plan_tax' );
                    break;
                case 'tax_type':
                    $base_data['tax_type'] = $this->get_tax_type( $plan );
                    break;
                case 'tax':
                    $base_data['tax'] = (float) $this->get_plan_meta( $plan, 'fm_tax' );
                    break;
                case 'validity_period':
                    $base_data['validity_period'] = (int) $this->get_plan_meta( $plan, 'fm_length', 'fm_length', 0 );
                    break;
                case 'validity_period_unit':
                    $base_data['validity_period_unit'] = $this->get_validity_period_unit( $plan );
                    break;
                case 'validity_period_label':
                    $base_data['validity_period_label'] = $this->get_validity_period_label( $plan );
                    break;
                case 'is_non_expiring':
                    $base_data['is_non_expiring'] = (bool) $this->get_plan_meta( $plan, 'fm_length_unl', 'fm_length_unl' );
                    break;
                case 'playstore_product_id':
                    $base_data['playstore_product_id'] = get_post_meta( $plan->ID, '_dpp_playstore_product_id', true );
                    break;
                case 'playstore_product_price':
                    $base_data['playstore_product_price'] = get_post_meta( $plan->ID, '_dpp_playstore_product_price', true );
                    break;
                case 'appstore_product_id':
                    $base_data['appstore_product_id'] = get_post_meta( $plan->ID, '_dpp_appstore_product_id', true );
                    break;
                case 'appstore_product_price':
                    $base_data['appstore_product_price'] = get_post_meta( $plan->ID, '_dpp_appstore_product_price', true );
                    break;
                case 'features':
                    $base_data['features'] = $this->get_features_data( $plan );
                    break;
                case 'fields':
                    $base_data['fields'] = $this->get_fields_data( $plan );
                    break;
            }
        }

        return $base_data;
    }

    protected function get_validity_period_label( $plan ) {
        $is_non_expiring = (bool) $this->get_plan_meta( $plan, 'fm_length_unl', 'fm_length_unl' );

        if ( $is_non_expiring ) {
            return esc_html__( 'Lifetime', 'directorist' );
        }

        $validity_period = (int) $this->get_plan_meta( $plan, 'fm_length', 'fm_length', 0 );

        $translations = array(
            'day'   => _n( 'Day', '%d days', $validity_period, 'directorist' ),
            'week'  => _n( 'Week', '%d weeks', $validity_period, 'directorist' ),
            'month' => _n( 'Month', '%d months', $validity_period, 'directorist' ),
            'year'  => _n( 'Year', '%d years', $validity_period, 'directorist' ),
        );

        return sprintf( $translations[ $this->get_validity_period_unit( $plan ) ], $validity_period );
    }

    protected function get_validity_period_unit( $plan ) {
        $unit = $this->get_plan_meta( $plan, '_recurrence_period_term', '_recurrence_period_term', 'day' );
        return ( in_array( $unit, array( 'day', 'week', 'month', 'year' ), true ) ? $unit : 'day' );
    }

    protected function get_tax_type( $plan ) {
        $tax_type = $this->get_plan_meta( $plan, 'plan_tax_type' );

        if ( ! empty( $tax_type ) && $tax_type === 'percent' ) {
            return 'percentage';
        }

        return 'fixed';
    }

    protected function get_plan_type( $plan ) {
        $plan_type = $this->get_plan_meta( $plan, 'plan_type', 'plan_type' );

        if ( ! empty( $plan_type ) && $plan_type === 'pay_per_listng' ) {
            return 'pay_per_listing';
        }

        return 'package';
    }

    protected function get_features_data( $plan ) {
        $features = array();
        $plugin_type = $this->get_active_plugin_type();

        // Auto renewal
        $recurring_key = ( 'dwpp' === $plugin_type ) ? '_enable_subscription' : '_atpp_recurring';
        $hide_recurring_key = ( 'dwpp' === $plugin_type ) ? '_hide_subscription' : 'hide_recurring';
        $features[] = array(
            'key'            => 'auto_renewal',
            'label'          => esc_html__( 'Auto renewing', 'directorist' ),
            'is_active'      => (bool) $this->get_plan_meta( $plan, '_atpp_recurring', $recurring_key ),
            'hide_from_plan' => (bool) $this->get_plan_meta( $plan, 'hide_recurring', $hide_recurring_key ),
        );

        if ( $this->get_plan_type( $plan ) === 'package' ) {
            $regular_listing_count = (int) $this->get_plan_meta( $plan, 'num_regular', 'num_regular', 0 );
            $unlimited_regular_listings = (bool) $this->get_plan_meta( $plan, 'num_regular_unl', 'num_regular_unl' );

            if ( $unlimited_regular_listings ) {
                $regular_listing_label = __( 'Unlimited Regular Listings', 'directorist' );
            } else {
                $regular_listing_label = sprintf( _n( '%s Regular Listing', '%s Regular Listings', $regular_listing_count, 'directorist' ), $regular_listing_count );
            }

            $hide_listings_key = ( 'dwpp' === $plugin_type ) ? '_dwpp_hide_listings' : 'hide_listings';
            $features[] = array(
                'key'            => 'regular_listings',
                'label'          => $regular_listing_label,
                'is_active'      => true,
                'hide_from_plan' => (bool) $this->get_plan_meta( $plan, 'hide_listings', $hide_listings_key ),
                'limit'          => $unlimited_regular_listings ? -1 : $regular_listing_count,
            );

            $featured_listing_count = (int) $this->get_plan_meta( $plan, 'num_featured', 'num_featured', 0 );
            $unlimited_featured_listings = (bool) $this->get_plan_meta( $plan, 'num_featured_unl', 'num_featured_unl' );

            if ( $unlimited_featured_listings ) {
                $featured_listing_label = __( 'Unlimited Featured Listings', 'directorist' );
            } else {
                $featured_listing_label = sprintf( _n( '%s Featured Listing', '%s Featured Listings', $featured_listing_count, 'directorist' ), $featured_listing_count );
            }

            $hide_featured_key = ( 'dwpp' === $plugin_type ) ? '_dwpp_hide_featured' : 'hide_featured';
            $features[] = array(
                'key'            => 'featured_listings',
                'label'          => $featured_listing_label,
                'is_active'      => true,
                'hide_from_plan' => apply_filters( 'atbdp_plan_featured_compare', (bool) $this->get_plan_meta( $plan, 'hide_featured', $hide_featured_key ) ),
                'limit'          => $unlimited_featured_listings ? -1 : $featured_listing_count,
            );
        } else {
            $hide_listing_featured_key = ( 'dwpp' === $plugin_type ) ? '_dwpp_hide_listing_featured' : 'hide_listing_featured';
            $features[] = array(
                'key'            => 'featured_listing',
                'label'          => esc_html__( 'Listing as featured', 'directorist' ),
                'is_active'      => (bool) $this->get_plan_meta( $plan, 'is_featured_listing', 'is_featured_listing' ),
                'hide_from_plan' => apply_filters( 'atbdp_plan_featured_compare', (bool) $this->get_plan_meta( $plan, 'hide_listing_featured', $hide_listing_featured_key ) ),
            );
        }

        $hide_cl_owner_key = ( 'dwpp' === $plugin_type ) ? '_dwpp_hide_cl_owner' : 'hide_Cowner';
        $features[] = array(
            'key'            => 'contact_listing_owner',
            'label'          => esc_html__( 'Contact Owner', 'directorist' ),
            'is_active'      => (bool) $this->get_plan_meta( $plan, 'cf_owner', 'cf_owner' ),
            'hide_from_plan' => apply_filters( 'atbdp_plan_contact_owner_compare', (bool) $this->get_plan_meta( $plan, 'hide_Cowner', $hide_cl_owner_key ) ),
        );

        $hide_customer_review_key = ( 'dwpp' === $plugin_type ) ? '_dwpp_hide_customer_review' : 'hide_review';
        $features[] = array(
            'key'            => 'reviews_allowed',
            'label'          => esc_html__( 'Allow Customer Review', 'directorist' ),
            'is_active'      => (bool) $this->get_plan_meta( $plan, 'fm_cs_review', 'fm_cs_review' ),
            'hide_from_plan' => apply_filters( 'atbdp_plan_review_compare', (bool) $this->get_plan_meta( $plan, 'hide_review', $hide_customer_review_key ) ),
        );

        $hide_claim_key = ( 'dwpp' === $plugin_type ) ? '_dwpp_hide_claim' : '_hide_claim';
        $features[] = array(
            'key'            => 'claim_badge_included',
            'label'          => esc_html__( 'Claim Badge Included', 'directorist' ),
            'is_active'      => (bool) $this->get_plan_meta( $plan, '_fm_claim', '_fm_claim' ),
            'hide_from_plan' => apply_filters( 'atbdp_plan_claim_compare', (bool) $this->get_plan_meta( $plan, '_hide_claim', $hide_claim_key ) ),
        );

        $hide_booking_key = ( 'dwpp' === $plugin_type ) ? '_dwpp_hide_booking' : '_hide_booking';
        $features[] = array(
            'key'            => 'booking_included',
            'label'          => esc_html__( 'Booking Included', 'directorist' ),
            'is_active'      => (bool) $this->get_plan_meta( $plan, '_fm_booking', '_fm_booking' ),
            'hide_from_plan' => apply_filters( 'atbdp_plan_booking_compare', (bool) $this->get_plan_meta( $plan, '_hide_booking', $hide_booking_key ) ),
        );

        $hide_live_chat_key = ( 'dwpp' === $plugin_type ) ? '_dwpp_hide_live_chat' : '_hide_live_chat';
        $features[] = array(
            'key'            => 'live_chat_included',
            'label'          => esc_html__( 'Live Chat Included', 'directorist' ),
            'is_active'      => (bool) $this->get_plan_meta( $plan, '_fm_live_chat', '_fm_live_chat' ),
            'hide_from_plan' => apply_filters( 'atbdp_plan_live_chat_compare', (bool) $this->get_plan_meta( $plan, '_hide_live_chat', $hide_live_chat_key ) ),
        );

        $hide_mark_as_sold_key = ( 'dwpp' === $plugin_type ) ? '_dwpp_hide_mark_as_sold' : '_hide_mark_as_sold';
        $features[] = array(
            'key'            => 'mark_as_sold_included',
            'label'          => esc_html__( 'Mark as Sold Included', 'directorist' ),
            'is_active'      => (bool) $this->get_plan_meta( $plan, '_fm_mark_as_sold', '_fm_mark_as_sold' ),
            'hide_from_plan' => apply_filters( 'atbdp_plan_mark_as_sold_compare', (bool) $this->get_plan_meta( $plan, '_hide_mark_as_sold', $hide_mark_as_sold_key ) ),
        );

        $hide_category_key = ( 'dwpp' === $plugin_type ) ? '_dwpp_hide_category' : 'hide_categories';
        $features[] = array(
            'key'            => 'categories_included',
            'label'          => esc_html__( 'All Categories', 'directorist' ),
            'is_active'      => (bool) $this->get_plan_meta( $plan, 'exclude_cat', 'exclude_cat' ),
            'hide_from_plan' => (bool) $this->get_plan_meta( $plan, 'hide_categories', $hide_category_key ),
        );

        return $features;
    }

    protected function get_fields_data( $plan ) {
        $form_fields  = directorist_get_listing_form_fields( $this->get_directory_id( $plan ) );
        $translations = array(
            'location'     => _n_noop( '%s (maximum %d item)', '%s (maximum %d items)', 'directorist' ),
            'category'     => _n_noop( '%s (maximum %d item)', '%s (maximum %d items)', 'directorist' ),
            'tag'          => _n_noop( '%s (maximum %d item)', '%s (maximum %d items)', 'directorist' ),
            'number'       => _n_noop( '%s (maximum %d)', '%s (maximum %d)', 'directorist' ),
            'textarea'     => _n_noop( '%s (maximum %d character)', '%s (maximum %d characters)', 'directorist' ),
            'description'  => _n_noop( '%s (maximum %d character)', '%s (maximum %d characters)', 'directorist' ),
            'excerpt'      => _n_noop( '%s (maximum %d character)', '%s (maximum %d characters)', 'directorist' ),
            'image_upload' => _n_noop( '%s (maximum %d item)', '%s (maximum %d items)', 'directorist' ),
        );
        $fields     = array_keys( $translations );
        $field_data = array();

        foreach ( $form_fields as $form_field ) {
            $field_key = $form_field['field_key'];

            if ( 'tax_input[at_biz_dir-location][]' === $field_key ) {
                $field_key = 'location';
            }

            if ( 'admin_category_select[]' === $field_key ) {
                $field_key = 'category';
            }

            if ( 'tax_input[at_biz_dir-tags][]' === $field_key ) {
                $field_key = 'tag';
            }

            if ( empty( $field_key ) ) {
                continue;
            }

            if ( $this->is_ignorable_field( $field_key ) ) {
                continue;
            }

            $plugin_type = $this->get_active_plugin_type();
            $active_key = '_' . $field_key;
            $hide_key = '_hide_' . $field_key;
            $unlimited_key = '_unlimited_' . $field_key;
            $max_key = '_max_' . $field_key;
            
            // For WooCommerce, check if it uses _dwpp_ prefix
            if ( 'dwpp' === $plugin_type ) {
                // Try _dwpp_ prefix first, fallback to regular
                $dwpp_active = get_post_meta( $plan->ID, '_dwpp_' . $field_key, true );
                $dwpp_hide = get_post_meta( $plan->ID, '_dwpp_hide_' . $field_key, true );
                $active_key = ( '' !== $dwpp_active || '' !== $dwpp_hide ) ? '_dwpp_' . $field_key : $active_key;
                $hide_key = ( '' !== $dwpp_hide ) ? '_dwpp_hide_' . $field_key : $hide_key;
            }

            $data = array(
                'key'            => $field_key,
                'label'          => ! empty( $form_field['label'] ) ? $form_field['label'] : '',
                'is_preset'      => ( $form_field['widget_group'] === 'preset' ),
                'is_active'      => (bool) $this->get_plan_meta( $plan, '_' . $field_key, $active_key ),
                'hide_from_plan' => (bool) $this->get_plan_meta( $plan, '_hide_' . $field_key, $hide_key ),
            );

            if ( $data['is_active'] && isset( $form_field['widget_name'] ) && in_array( $form_field['widget_name'], $fields, true ) ) {
                if ( (bool) $this->get_plan_meta( $plan, '_unlimited_' . $field_key, $unlimited_key ) ) {
                    $data['label'] = sprintf( __( '%s (unlimited)', 'directorist' ), $data['label'] );
                    $data['limit'] = -1;
                } elseif ( ( $count = (int) $this->get_plan_meta( $plan, '_max_' . $field_key, $max_key, 0 ) ) ) {
                    $data['label'] = sprintf(
                        translate_nooped_plural( $translations[ $form_field['widget_name'] ], $count, 'directorist' ),
                        $data['label'],
                        $count,
                    );
                    $data['limit'] = $count;
                }
            }

            $field_data[] = $data;
        }

        return $field_data;
    }

    protected function is_ignorable_field( $field_key ) {
        return in_array( $field_key, array( 'listing_type', 'booking' ), true );
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
     * Get the plans's schema, conforming to JSON Schema.
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
                    'description' => __( 'Unique identifier for the resource.', 'directorist' ),
                    'type'        => 'integer',
                    'context'     => array( 'view', 'edit' ),
                    'readonly'    => true,
                ),
                'name'                  => array(
                    'description' => __( 'plan name.', 'directorist' ),
                    'type'        => 'string',
                    'context'     => array( 'view', 'edit' ),
                ),
                'date_created'          => array(
                    'description' => __( "The date the plan was created, in the site's timezone.", 'directorist' ),
                    'type'        => 'date-time',
                    'context'     => array( 'view', 'edit' ),
                    'readonly'    => true,
                ),
                'date_modified'         => array(
                    'description' => __( "The date the plan was last modified, in the site's timezone.", 'directorist' ),
                    'type'        => 'date-time',
                    'context'     => array( 'view', 'edit' ),
                    'readonly'    => true,
                ),
                'description'           => array(
                    'description' => __( 'Plan description.', 'directorist' ),
                    'type'        => 'string',
                    'context'     => array( 'view', 'edit' ),
                ),
                'hide_description_from_plan'           => array(
                    'description' => __( 'Hide description from plan.', 'directorist' ),
                    'type'        => 'boolean',
                    'context'     => array( 'view', 'edit' ),
                ),
                'directory' => array(
                    'description' => __( 'Directory id.', 'directorist' ),
                    'type'        => 'integer',
                    'context'     => array( 'view', 'edit' ),
                ),
                'status'     => array(
                    'description' => __( 'Plan status.', 'directorist' ),
                    'type'        => 'string',
                    'context'     => array( 'view', 'edit' ),
                ),
                'is_recommended'     => array(
                    'description' => __( 'Plan recommendation status.', 'directorist' ),
                    'type'        => 'boolean',
                    'context'     => array( 'view', 'edit' ),
                ),
                'is_hidden'     => array(
                    'description' => __( 'Plan hidden during plan selection.', 'directorist' ),
                    'type'        => 'boolean',
                    'context'     => array( 'view', 'edit' ),
                ),
                'type'     => array(
                    'description' => __( 'Plan type.', 'directorist' ),
                    'type'        => 'string',
                    'enum'        => array( 'package', 'pay_per_listing' ),
                    'context'     => array( 'view', 'edit' ),
                ),
                'type_label'     => array(
                    'description' => __( 'Plan type label.', 'directorist' ),
                    'type'        => 'string',
                    'context'     => array( 'view', 'edit' ),
                ),
                'currency'     => array(
                    'description' => __( 'Plan currency.', 'directorist' ),
                    'type'        => 'string',
                    'context'     => array( 'view', 'edit' ),
                ),
                'currency_symbol'     => array(
                    'description' => __( 'Plan currency symbol.', 'directorist' ),
                    'type'        => 'string',
                    'context'     => array( 'view', 'edit' ),
                ),
                'is_free'     => array(
                    'description' => __( 'Is plan free?.', 'directorist' ),
                    'type'        => 'boolean',
                    'context'     => array( 'view', 'edit' ),
                ),
                'price'     => array(
                    'description' => __( 'Plan price.', 'directorist' ),
                    'type'        => 'float',
                    'context'     => array( 'view', 'edit' ),
                ),
                'is_taxable'     => array(
                    'description' => __( 'Is plan taxable?', 'directorist' ),
                    'type'        => 'boolean',
                    'context'     => array( 'view', 'edit' ),
                ),
                'tax_type'     => array(
                    'description' => __( 'Plan tax type', 'directorist' ),
                    'type'        => 'string',
                    'enum'        => array( 'fixed', 'percentage' ),
                    'context'     => array( 'view', 'edit' ),
                ),
                'tax'     => array(
                    'description' => __( 'Plan tax amount.', 'directorist' ),
                    'type'        => 'float',
                    'context'     => array( 'view', 'edit' ),
                ),
                'validity_period'     => array(
                    'description' => __( 'Plan validity period.', 'directorist' ),
                    'type'        => 'integer',
                    'context'     => array( 'view', 'edit' ),
                ),
                'validity_period_unit'     => array(
                    'description' => __( 'Plan validity period unit.', 'directorist' ),
                    'type'        => 'string',
                    'context'     => array( 'view', 'edit' ),
                ),
                'validity_period_label'     => array(
                    'description' => __( 'Plan validity period label.', 'directorist' ),
                    'type'        => 'string',
                    'context'     => array( 'view', 'edit' ),
                ),
                'is_non_expiring'    => array(
                    'description' => __( 'Is plan non expiring?', 'directorist' ),
                    'type'        => 'boolean',
                    'context'     => array( 'view', 'edit' ),
                ),
                'playstore_product_id'     => array(
                    'description' => __( 'PlayStore product Id.', 'directorist' ),
                    'type'        => 'string',
                    'context'     => array( 'view', 'edit' ),
                ),
                'playstore_product_price'     => array(
                    'description' => __( 'PlayStore product price.', 'directorist' ),
                    'type'        => 'string',
                    'context'     => array( 'view', 'edit' ),
                ),
                'appstore_product_id'     => array(
                    'description' => __( 'AppStore product Id.', 'directorist' ),
                    'type'        => 'string',
                    'context'     => array( 'view', 'edit' ),
                ),
                'appstore_product_price'     => array(
                    'description' => __( 'AppStore product price.', 'directorist' ),
                    'type'        => 'string',
                    'context'     => array( 'view', 'edit' ),
                ),
                'features'             => array(
                    'description' => __( 'Features data.', 'directorist' ),
                    'type'        => 'array',
                    'context'     => array( 'view', 'edit' ),
                    'items'       => array(
                        'type'       => 'object',
                        'properties' => array(
                            'key'    => array(
                                'description' => __( 'Feature key.', 'directorist' ),
                                'type'        => 'string',
                                'context'     => array( 'view', 'edit' ),
                                'readonly'    => true,
                            ),
                            'label' => array(
                                'description' => __( 'Feature label.', 'directorist' ),
                                'type'        => 'string',
                                'context'     => array( 'view', 'edit' ),
                            ),
                            'is_active' => array(
                                'description' => __( 'Feature active status.', 'directorist' ),
                                'type'        => 'bool',
                                'context'     => array( 'view', 'edit' ),
                            ),
                            'hide_from_plan' => array(
                                'description' => __( 'Feature visibility status from plan package.', 'directorist' ),
                                'type'        => 'bool',
                                'context'     => array( 'view', 'edit' ),
                            ),
                            'limit' => array(
                                'description' => __( 'Feature limited to number of times (-1 indicates unlimited).', 'directorist' ),
                                'type'        => 'number',
                                'context'     => array( 'view', 'edit' ),
                            ),
                        ),
                    ),
                ),
                'fields'             => array(
                    'description' => __( 'Fields data.', 'directorist' ),
                    'type'        => 'array',
                    'context'     => array( 'view', 'edit' ),
                    'items'       => array(
                        'type'       => 'object',
                        'properties' => array(
                            'key'    => array(
                                'description' => __( 'Field key.', 'directorist' ),
                                'type'        => 'string',
                                'context'     => array( 'view', 'edit' ),
                                'readonly'    => true,
                            ),
                            'label' => array(
                                'description' => __( 'Field label.', 'directorist' ),
                                'type'        => 'string',
                                'context'     => array( 'view', 'edit' ),
                            ),
                            'is_preset' => array(
                                'description' => __( 'Preset or custom field status.', 'directorist' ),
                                'type'        => 'bool',
                                'context'     => array( 'view', 'edit' ),
                            ),
                            'is_active' => array(
                                'description' => __( 'Field active status.', 'directorist' ),
                                'type'        => 'bool',
                                'context'     => array( 'view', 'edit' ),
                            ),
                            'hide_from_plan' => array(
                                'description' => __( 'Field visibility status from plan package.', 'directorist' ),
                                'type'        => 'bool',
                                'context'     => array( 'view', 'edit' ),
                            ),
                            'limit' => array(
                                'description' => __( 'Feature limited to number of times (-1 indicates unlimited).', 'directorist' ),
                                'type'        => 'number',
                                'context'     => array( 'view', 'edit' ),
                            ),
                        ),
                    ),
                ),
            ),
        );

        return $this->add_additional_fields_schema( $schema );
    }

    /**
     * Get the query params for collections of plans.
     *
     * @return array
     */
    public function get_collection_params() {
        $params = parent::get_collection_params();

        $params['context']['default'] = 'view';

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
            'default'            => 'title',
            'type'               => 'string',
            'sanitize_callback'  => 'sanitize_key',
        );

        if ( directorist_is_multi_directory_enabled() ) {
            $params['directory'] = array(
                'description'        => __( 'Query plans by directory id.', 'directorist' ),
                'type'               => 'integer',
                'sanitize_callback'  => 'absint',
            );
        }

        return $params;
    }

    protected function get_orderby_possibles() {
        return array(
            'title'   => 'title',
            'date'    => 'date',
        );
    }
}
