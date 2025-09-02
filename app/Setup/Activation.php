<?php

namespace Directorist\App\Setup;

defined( "ABSPATH" ) || exit;

use Directorist\App\Enums\Order\Status as OrderStatus;
use Directorist\App\Enums\Payment\Status as PaymentStatus;
use Directorist\App\Enums\Refund\Status as RefundStatus;
use Directorist\WpMVC\Database\Schema\Blueprint;
use Directorist\WpMVC\Database\Schema\Schema;

class Activation {
    public static function run() {
        // Run the activation tasks.
        self::create_tables();
    }

    public static function create_tables() {
        $prefix = "directorist_";

        //Orders Table
        Schema::create(
            "{$prefix}orders", function( Blueprint $table ) {
                $table->big_increments( "id" );
                $table->integer( "subscription_id" )->nullable();
                $table->integer( "user_id" );
                $table->integer( "listing_id" )->nullable();
                $table->integer( "plan_id" )->nullable();
                $table->tiny_integer( "is_featured_listing" )->default( 0 );
                $table->decimal( "amount", 10, 2 )->default( 0.00 );
                $table->string( "currency", 10 )->default( "USD" );
                $table->decimal( "coupon_discount", 10, 2 )->default( 0.00 );
                $table->decimal( "final_amount", 10, 2 )->default( 0.00 );
                $table->enum( "status", OrderStatus::all() )->default( OrderStatus::PENDING );
                $table->timestamp( "expires_at" )->nullable();
                $table->timestamps();
            }
        );

        //Payment Table
        Schema::create(
            "{$prefix}payments", function( Blueprint $table ) {
                $table->big_increments( "id" );
                $table->integer( "order_id" );
                $table->decimal( "amount", 10, 2 )->default( 0.00 );
                $table->string( "currency", 10 )->default( "USD" );
                $table->enum( "status", PaymentStatus::all() )->default( PaymentStatus::PENDING );
                $table->string( "transaction_id" )->nullable();
                $table->string( "method" )->nullable();
                $table->timestamps();
            }
        );

        //Refund Table
        Schema::create(
            "{$prefix}refunds", function( Blueprint $table ) {
                $table->big_increments( "id" );
                $table->integer( "order_id" );
                $table->decimal( "amount", 10, 2 )->default( 0.00 );
                $table->enum( "status", RefundStatus::all() )->default( RefundStatus::PENDING );
                $table->string( "reason" )->nullable();
                $table->timestamps();
            }
        );
    }
}