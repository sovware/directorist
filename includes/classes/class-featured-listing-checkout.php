<?php

namespace Directorist;

defined( "ABSPATH" ) || exit;

use Exception;
use WP_REST_Request;
use Directorist\Enums\Order\Status;
use Directorist\DTO\Order\DTO as OrderDTO;
use Directorist\Utils\Template;

class FeaturedListingCheckout {
    const CHECKOUT_TYPE = 'featured_listing';

    public function __construct()  {
        add_filter( 'directorist_checkout_types', [$this, 'add_checkout_type'] );
        add_filter( 'directorist_order_data', [ $this, 'handle_order_data' ] );
        add_action( 'directorist_after_order_update', [$this, 'handle_after_order_update'] );
        add_filter( 'directorist_payment_receipt_order_items', [$this, 'handle_payment_receipt_order_items'], 10, 2 );
        add_filter( 'directorist_checkout_validation', [$this, 'validate_checkout'], 10, 2 );
        add_action( 'directorist_checkout_table', [$this, 'handle_checkout_table'], 10, 4 );
        add_filter( 'directorist_checkout_subtotal', [$this, 'handle_checkout_subtotal'], 10, 3 );
        add_action( 'directorist_checkout_create_order', [$this, 'handle_checkout_create_order'], 10, 3 );
        add_action( 'directorist_before_order_update', [$this, 'handle_before_order_update'] );
        add_filter( 'directorist_checkout_product_name', [$this, 'handle_checkout_product_name'], 10, 2 );
        add_filter( 'directorist_ajax_listing_submission_response', [ $this, 'handle_listing_submission_response_data' ], 10, 2 );
        add_filter( 'directorist_listing_update_args_after_preview', [ $this, 'handle_listing_status_after_preview' ], 10, 2 );
    }

    public function handle_listing_submission_response_data( array $data, array $request ) {
        $featured_enabled = directorist_is_featured_listing_enabled( [ 'type' => 'featured_listing_checkout' ] );
        
        if ( ! $featured_enabled ) {
            return $data;
        }

        $listing_id          = (int) $data['id'];
        $is_featured_listing = isset( $request['listing_type'] ) && 'featured' === $request['listing_type'];

        if ( ! $is_featured_listing ) {
            return $data;
        }

        if ( directorist_order_repository()->listing_has_paid_featured_order( $listing_id ) ) {
            return $data;
        }

        directorist_set_listing_featured( $listing_id, false );
        directorist_set_listing_status( $listing_id, 'pending' );

        $data['redirect_url'] = directorist_get_checkout_page_url( self::CHECKOUT_TYPE, [ 'listing_id' => $listing_id ] );
        $data['need_payment'] = true;

        return $data;
    }

    public function handle_listing_status_after_preview( array $args ): array {
        $listing_id    = $args['ID'];
        $checkout_type = isset( $_GET['checkout_type'] ) ? sanitize_text_field( wp_unslash( $_GET['checkout_type'] ) ) : '';

        if ( self::CHECKOUT_TYPE !== $checkout_type ) {
            return $args;
        }

        if ( directorist_order_repository()->listing_has_paid_featured_order( $listing_id ) ) {
            return $args;
        }

        $args['post_status'] = 'pending';

        return $args;
    }

    public function add_checkout_type( array $checkout_types ) {
        $checkout_types[] = self::CHECKOUT_TYPE;
        return $checkout_types;
    }

    public function validate_checkout( string $checkout_type, WP_REST_Request $request ) {
        if ( $checkout_type !== self::CHECKOUT_TYPE ) {
            return;
        }

        if ( ! $request->has_param( 'listing_id' ) ) {
            throw new Exception( __( 'Listing ID is required.', 'directorist' ) );
        }

        $listing = get_post( $request->get_param( 'listing_id' ) );

        if ( ! $listing || $listing->post_type !== ATBDP_POST_TYPE ) {
            throw new Exception( __( 'Invalid listing id.', 'directorist' ) );
        }
    }

    public function handle_checkout_table( string $checkout_type, float $total, float $subtotal, WP_REST_Request $request ) {
        if ( $checkout_type !== self::CHECKOUT_TYPE ) {
            return;
        }

        $listing = get_post( $request->get_param( 'listing_id' ) );

        Template::render(
            'checkout/featured_listing-summary', [
                'listing'  => $listing,
                'request'  => $request,
                'subtotal' => $subtotal
            ]
        );
    }

    public function handle_checkout_subtotal( float $subtotal, string $checkout_type, WP_REST_Request $request ) {
        if ( $checkout_type !== self::CHECKOUT_TYPE ) {
            return $subtotal;
        }
        return get_directorist_option( 'featured_listing_price' );
    }

    public function handle_checkout_create_order( OrderDTO $dto, string $checkout_type, WP_REST_Request $request ) {
        if ( $checkout_type !== self::CHECKOUT_TYPE ) {
            return;
        }

        $amount = get_directorist_option( 'featured_listing_price' );
        $dto->set_listing_id( $request->get_param( 'listing_id' ) )->set_is_featured_listing( 1 )->set_ref_type( self::CHECKOUT_TYPE )->set_amount( $amount )->set_sub_total( $amount );
    }

    public function handle_before_order_update( OrderDTO $dto ) {
        if ( ! $this->is_featured_order( $dto ) ) {
            return;
        }

        if ( ! $dto->is_initialized( 'status' ) || $dto->get_status() !== Status::PAID ) {
            return;
        }

        $order = directorist_get_order_by_id( $dto->get_id() );

        if ( $order->is_featured_listing && ! $order->expires_at ) {
            $featured_days = get_directorist_option( 'featured_listing_time', 30 );
            $dto->set_expires_at( directorist_now()->add_days( $featured_days ) );
        }
    }

    public function handle_after_order_update( OrderDTO $dto ) {
        if ( ! $this->is_featured_order( $dto ) ) {
            return;
        }

        if ( $dto->get_is_featured_listing() !== 1 ) {
            return;
        }

        if ( Status::PAID === $dto->get_status() ) {
            directorist_set_listing_featured( $dto->get_listing_id(), true );

            // Publish the listing if it's pending
            $listing = get_post( $dto->get_listing_id() );
            
            if ( $listing && 'publish' !== $listing->post_status ) {
                directorist_set_listing_status( $dto->get_listing_id(), 'publish' );
            }
        } else {
            directorist_set_listing_featured( $dto->get_listing_id(), false );
        }
    }

    public function handle_payment_receipt_order_items( array $order_items, OrderDTO $order ) {
        if ( ! $this->is_featured_order( $order ) ) {
            return $order_items;
        }

        if ( $order->get_is_featured_listing() !== 1 ) {
            return $order_items;
        }

        $data = atbdp_get_featured_settings_array();

        $order_items[] = [
            'title' => $data['label'],
            'desc'  => $data['desc'],
            'price' => $order->get_sub_total()
        ];

        return $order_items;
    }

    public function handle_order_data( $order ) {
        if ( $order->ref_type !== self::CHECKOUT_TYPE ) {
            return $order;
        }

        $order->order_type = __( 'Featured Listing', 'directorist' );
        return $order;
    }

    public function handle_checkout_product_name( string $product_name, OrderDTO $dto ) {
        if ( ! $this->is_featured_order( $dto ) ) {
            return $product_name;
        }

        $listing = get_post( $dto->get_listing_id() );

        //translators: %s is the listing title
        return sprintf( __( 'Featured Listing: %s', 'directorist' ), $listing->post_title );
    }

    public function is_featured_order( OrderDTO $order_dto ): bool {
        if ( ! $order_dto->is_initialized( 'ref_type' ) || ! $order_dto->is_initialized( 'ref' ) || ! $order_dto->is_initialized( 'listing_id' ) ) {
            return false;
        }
        
        if ( self::CHECKOUT_TYPE !== $order_dto->get_ref_type() ) {
            return false;
        }

        return true;
    }
}
