<?php

namespace Directorist\App\Providers;

defined( "ABSPATH" ) || exit;

use Directorist\App\Contracts\PaymentInterface;
use Directorist\App\DTO\Order\DTO;
use Directorist\App\Enums\Order\Status;
use Directorist\App\Enums\Order\Type;
use Directorist\WpMVC\Contracts\Provider;
use Directorist\WpMVC\Exceptions\Exception;
use Directorist\WpMVC\Helpers\Helpers;

class OrderServiceProvider implements Provider {
    public function boot() {
        add_action( 'directorist_after_order_update', [$this, 'after_order_update'] );
        add_action( 'directorist_create_order', [$this, 'create_order'] );
    }

    public function create_order( $listing_id ) {
        $amount = get_directorist_option( 'featured_listing_price' );

        $dto = ( new DTO )
        ->set_user_id( get_current_user_id() )
        ->set_listing_id( $listing_id )
        ->set_is_featured_listing( 1 )
        ->set_type( Type::ONE_TIME )
        ->set_amount( $amount )
        ->set_currency( atbdp_get_payment_currency() )
        ->set_final_amount( $amount )
        ->set_status( Status::PENDING );
        
        $repository = directorist_order_repository();
        $repository->create( $dto );
        
        $order = $repository->get_by_id( $dto->get_id() );

        if ( $amount == 0 ) {
            $this->process_free_order( $dto->get_id() );
            $this->redirect_to_receipt_page( $order );
        }

        $request            = Helpers::request();
        $payment_gateway    = $request->get_param( 'payment_gateway' );
        $payment_processors = directorist_get_payment_processors();

        if ( isset( $payment_processors[$payment_gateway] ) && class_exists( $payment_processors[$payment_gateway] ) ) {
            $processor_instance = directorist_make( $payment_processors[$payment_gateway] );

            if ( $processor_instance instanceof PaymentInterface ) {
                $processor_instance->pay( $order );
            }
        }

        $this->redirect_to_receipt_page( $order );
    }
    
    private function redirect_to_receipt_page( $order ) {
        wp_safe_redirect( apply_filters( 'atbdp_payment_receipt_page_link', \ATBDP_Permalink::get_payment_receipt_page_link( $order->id ), $order->id ) );
        exit; 
    }

    private function process_free_order( int $order_id ) {
        $featured_days = get_directorist_option( 'featured_listing_time', 30 );

        $dto        = ( new DTO )->set_id( $order_id )->set_expires_at( directorist_now()->add_days( $featured_days ) );
        $repository = directorist_order_repository();
        $repository->update( $dto );
    }

    public function after_order_update( DTO $dto ) {
        $repository = directorist_order_repository();
        $order      = $repository->get_by_id( $dto->get_id() );

        if ( ! $order->is_featured_listing ) {
            return;
        }

        if ( 'paid' === $order->status ) {
            update_post_meta( $order->listing_id, '_featured', 1 );
        } else {
            update_post_meta( $order->listing_id, '_featured', 0 );
        }
    }
}