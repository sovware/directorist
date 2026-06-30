<?php

namespace Directorist\Helpers;

defined( 'ABSPATH' ) || exit;

use DateTime as PHPDateTime;
use DateTimeZone;
use Exception;

class DateTime extends PHPDateTime {
    const SECONDS_PER_MINUTE = 60;
    const MINUTES_PER_HOUR   = 60;
    const HOURS_PER_DAY      = 24;
    const DAYS_PER_WEEK      = 7;
    const MONTHS_PER_QUARTER = 3;
    const QUARTERS_PER_YEAR  = 4;

    /**
     * @param DateTimeZone|null $timezone Date timezone.
     */
    public function __construct( string $datetime = "now", $timezone = null ) {
        parent::__construct( $datetime, $timezone ?? wp_timezone() );
    }

    public static function now() {
        return new static();
    }

    public function unit( string $unit, int $value = 1, string $type = 'add' ) {
        switch ( $unit ) {
            case 'minute':
                $value *= static::SECONDS_PER_MINUTE;
                break;
            case 'hour':
                $value *= static::MINUTES_PER_HOUR * static::SECONDS_PER_MINUTE;
                break;
            case 'day':
                $value *= static::HOURS_PER_DAY * static::MINUTES_PER_HOUR * static::SECONDS_PER_MINUTE;
                break;
            case 'week':
                $value *= static::DAYS_PER_WEEK * static::HOURS_PER_DAY * static::MINUTES_PER_HOUR * static::SECONDS_PER_MINUTE;
                break;
            case 'month':
                $value *= 30 * static::HOURS_PER_DAY * static::MINUTES_PER_HOUR * static::SECONDS_PER_MINUTE;
                break;
            case 'quarter':
                $value *= static::MONTHS_PER_QUARTER * 30 * static::HOURS_PER_DAY * static::MINUTES_PER_HOUR * static::SECONDS_PER_MINUTE;
                break;
            case 'year':
                $value *= 365 * static::HOURS_PER_DAY * static::MINUTES_PER_HOUR * static::SECONDS_PER_MINUTE;
                break;
            default:
                //phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped
                throw new Exception( "Invalid unit for real timestamp add/sub: '$unit'" );
        }
        if ( 'sub' === $type ) {
            return $this->setTimestamp( $this->getTimestamp() - $value );
        }
        return $this->setTimestamp( $this->getTimestamp() + $value );
    }

    public function add_days( int $days ) {
        return $this->unit( 'day', $days );
    }

    public function sub_days( int $days ) {
        return $this->unit( 'day', $days, 'sub' );
    }

    public function to_string() {
        return $this->format( 'D M d Y H:i:s \G\M\TO' );
    }

    public function to_date_time_string() {
        return $this->format( directorist_date_time_format() );
    }

    public function create_from_format( string $format, string $datetime, ?DateTimeZone $timezone = null ) {
        $this->setTimestamp( static::createFromFormat( $format, $datetime, $timezone )->getTimestamp() );
        return $this;
    }

    public function add_timestamp( int $time ) {
        $this->setTimestamp( $this->getTimestamp() + $time );
        return $this;
    }

    public function wp_date_time_string( string $date_time ) {
        $this->create_from_format( directorist_date_time_format(), $date_time );
        $format = get_option( 'date_format' ) . ' \a\t ' . get_option( 'time_format' );
        return $this->format( $format );
    }
}
