<?php

namespace Directorist\App\Providers;

defined( "ABSPATH" ) || exit;

use Directorist\App\Enums\Order\Status;
use Directorist\App\DTO\Order\DTO;
use Directorist\App\Enums\Order\Type;
use Directorist\WpMVC\View\View;
use Directorist\WpMVC\Contracts\Provider;
use Directorist\WpMVC\RequestValidator\Validator;
use Directorist\WpMVC\Exceptions\Exception;
use WP_REST_Request;

class FeaturedListingCheckoutServiceProvider implements Provider {

    const CHECKOUT_TYPE = 'featured_listing';

    public function boot() {
        $featured_enabled = directorist_is_featured_listing_enabled();
        if ( ! $featured_enabled ) return;

        add_filter( 'directorist_checkout_types', [$this, 'add_checkout_type'] );
        add_filter( 'directorist_checkout_validation', [$this, 'validate_checkout'], 10, 3 );
        add_action( 'directorist_checkout_table', [$this, 'handle_checkout_table'], 10, 3 );
        add_filter( 'directorist_checkout_subtotal', [$this, 'handle_checkout_subtotal'], 10, 3 );
        add_action( 'directorist_checkout_create_order', [$this, 'handle_checkout_create_order'], 10, 3 );
        add_action( 'directorist_before_order_update', [$this, 'handle_before_order_update'] );
        add_action( 'directorist_after_order_update', [$this, 'handle_after_order_update'] );
    }

    public function add_checkout_type( array $checkout_types ) {
        $checkout_types[] = self::CHECKOUT_TYPE;
        return $checkout_types;
    }

    public function validate_checkout( string $checkout_type, WP_REST_Request $request, Validator $validator ) {
        if ( $checkout_type !== self::CHECKOUT_TYPE ) return;

        $validator->validate(
            [
                'listing_id' => 'required|numeric'
            ], false 
        );

        if ( $validator->is_fail() ) {
            throw new \Exception( __( 'Invalid listing id.', 'directorist' ) );
        }

        $listing = get_post( $request->get_param( 'listing_id' ) );

        if ( ! $listing || $listing->post_type !== ATBDP_POST_TYPE || $listing->post_status !== 'publish' ) {
            throw new \Exception( __( 'Invalid listing id.', 'directorist' ) );
        }
    }

    public function handle_checkout_table( string $checkout_type, int $subtotal, WP_REST_Request $request ) {
        if ( $checkout_type !== self::CHECKOUT_TYPE ) return;

        $listing = get_post( $request->get_param( 'listing_id' ) );

        View::render(
            'checkout/featured_listing-summary', [
                'listing'  => $listing,
                'request'  => $request,
                'subtotal' => $subtotal
            ]
        );
    }

    public function handle_checkout_subtotal( int $subtotal, string $checkout_type, WP_REST_Request $request ) {
        if ( $checkout_type !== self::CHECKOUT_TYPE ) return $subtotal;
        return get_directorist_option( 'featured_listing_price' );
    }

    public function handle_checkout_create_order( DTO $dto, string $checkout_type, WP_REST_Request $request ) {
        if ( $checkout_type !== self::CHECKOUT_TYPE ) return;

        $amount = get_directorist_option( 'featured_listing_price' );
        $dto->set_listing_id( $request->get_param( 'listing_id' ) )->set_is_featured_listing( 1 )->set_amount( $amount )->set_final_amount( $amount )->set_type( Type::ONE_TIME );
    }

    public function handle_before_order_update( DTO $dto ) {
        if ( ! $dto->is_initialized( 'status' ) || $dto->get_status() !== Status::PAID ) {
            return;
        }

        $order = directorist_order_repository()->get_by_id( $dto->get_id() );

        if ( $order->is_featured_listing && ! $order->expires_at ) {
            $featured_days = get_directorist_option( 'featured_listing_time', 30 );
            $dto->set_expires_at( directorist_now()->add_days( $featured_days ) );
        }
    }

    public function handle_after_order_update( DTO $dto ) {
        $order = directorist_order_repository()->get_by_id( $dto->get_id() );

        if ( ! $order->is_featured_listing ) {
            return;
        }

        if ( Status::PAID === $order->status ) {
            update_post_meta( $order->listing_id, '_featured', 1 );
        } else {
            update_post_meta( $order->listing_id, '_featured', 0 );
        }
    }
}