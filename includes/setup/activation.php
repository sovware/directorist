<?php

namespace Directorist\Setup;

defined( "ABSPATH" ) || exit;

use Directorist\Enums\Order\Status as OrderStatus;
use Directorist\Enums\Payment\Status as PaymentStatus;
use Directorist\Enums\Refund\Status as RefundStatus;
use Directorist\Enums\Order\TaxType;
use Directorist\Enums\Order\DiscountType;
use Directorist\Utils\Database\Schema\Blueprint;
use Directorist\Utils\Database\Schema\Schema;

class Activation {
    public static function register_hooks() {
        add_action( 'directorist_installed', [ __CLASS__, 'run' ] );
        add_action( 'directorist_updated', [ __CLASS__, 'run' ] );
    }

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
                $table->unsigned_big_integer( "legacy_id" )->nullable();
                $table->integer( "subscription_id" )->nullable();
                $table->integer( "user_id" );
                $table->integer( "listing_id" )->nullable();
                $table->tiny_integer( "is_featured_listing" )->default( 0 );
                $table->string( "ref" )->nullable();
                $table->string( "ref_type" )->nullable();
                $table->decimal( "amount", 10, 2 )->default( 0.00 );
                $table->string( "currency", 10 )->default( "USD" );
                $table->string( "coupon_code" )->nullable();
                $table->decimal( "coupon_discount", 10, 2 )->default( 0.00 );
                $table->enum( "coupon_discount_type", DiscountType::all() )->nullable();
                $table->decimal( "tax_rate", 10, 2 )->default( 0.00 );
                $table->enum( "tax_type", TaxType::all() )->nullable();
                $table->decimal( "sub_total", 10, 2 )->default( 0.00 );
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