<?php

namespace Directorist\Enums\Payment;

defined( "ABSPATH" ) || exit;

class Status {

    const PENDING   = 'pending';
    const PAID      = 'paid';
    const FAILED    = 'failed';
    const CANCELLED = 'cancelled';
    const REFUNDED  = 'refunded';
    const UNPAID    = 'unpaid';
    const EXPIRED   = 'expired';

    public static function all() {
        return [
            self::PENDING,
            self::PAID,
            self::FAILED,
            self::CANCELLED,
            self::REFUNDED,
            self::UNPAID,
            self::EXPIRED,
        ];
    }

    public static function get_i18n( $status ) {
        switch ( $status ) {
            case self::PENDING:
                return esc_html__( 'Pending', 'directorist' );
            case self::PAID:
                return esc_html__( 'Paid', 'directorist' );
            case self::FAILED:
                return esc_html__( 'Failed', 'directorist' );
            case self::CANCELLED:
                return esc_html__( 'Cancelled', 'directorist' );
            case self::REFUNDED:
                return esc_html__( 'Refunded', 'directorist' );
            case self::UNPAID:
                return esc_html__( 'Unpaid', 'directorist' );
            case self::EXPIRED:
                return esc_html__( 'Expired', 'directorist' );
            default:
                return esc_html__( 'Invalid', 'directorist' );
        }
    }
}