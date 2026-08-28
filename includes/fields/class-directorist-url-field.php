<?php
/**
 * Directorist Url Field class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Url_Field extends Base_Field {
    public $type = 'url';

    public function validate( $posted_data ) {
        $value      = $this->sanitize( $posted_data );
        $parsed_url = wp_parse_url( $value );

        if (
            ! filter_var( $value, FILTER_VALIDATE_URL ) ||
            false === $parsed_url ||
            isset( $parsed_url['user'] ) ||
            isset( $parsed_url['pass'] )
        ) {
            $this->add_error( __( 'Invalid URL.', 'directorist' ) );

            return false;
        }

        return true;
    }

    public function sanitize( $posted_data ) {
        $value = $this->get_value( $posted_data );
        if ( empty( $value ) ) {
            return $value;
        }

        return esc_url_raw( $value, [ 'http', 'https' ] );
    }
}

Fields::register( new Url_Field() );
