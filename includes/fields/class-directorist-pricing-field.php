<?php
/**
 * Directorist Pricing Field class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Pricing_Field extends Base_Field {
    public $type = 'pricing';

    public function __construct( array $props = [] ) {
        if ( empty( $props['label'] ) ) {
            $props['label'] = __( 'Pricing', 'directorist' );
        }

        parent::__construct( $props );
    }

    public function get_value( $posted_data ) {
        if ( $this->get_price_type_prop() !== 'both' ) {
            $posted_data['atbd_listing_pricing'] = $this->get_price_type_prop();
        }

        if ( ! isset( $posted_data['atbd_listing_pricing'] ) && ( isset( $posted_data['price'] ) || isset( $posted_data['price_range'] ) ) ) {
            return [];
        }

        $posted_price_type  = $posted_data['atbd_listing_pricing'] ?? '';
        $posted_price       = $posted_data['price'] ?? 0;
        $posted_price_range = $posted_data['price_range'] ?? '';

        return [
            'price_type'  => sanitize_text_field( directorist_get_var( $posted_price_type ) ),
            'price'       => round( (float) directorist_get_var( $posted_price, 0 ), 2 ),
            'price_range' => sanitize_text_field( directorist_get_var( $posted_price_range ) )
        ];
    }

    public function validate( $posted_data ) {
        $value = wp_parse_args(
            $this->get_value( $posted_data ),
            [
                'price_type'  => '',
                'price'       => 0,
                'price_range' => ''
            ]
        );

        if ( $this->is_required() ) {
            $required_message = $this->get_required_error_message( $value );

            if ( ! empty( $required_message ) ) {
                $this->add_error( $required_message );
            }
        }

        if ( ! empty( $value['price_type'] ) && ! in_array( $value['price_type'], $this->get_price_types(), true ) ) {
            /* translators: %s: Price type value */
            $this->add_error( sprintf( __( 'Invalid price type: %s', 'directorist' ), esc_html( $value['price_type'] ) ) );
        }

        if ( $value['price_type'] === 'range' && ! empty( $value['price_range'] ) && ! in_array( $value['price_range'], $this->get_price_ranges(), true ) ) {
            $this->add_error( __( 'Invalid price range.', 'directorist' ) );
        }

        if ( $this->has_error() ) {
            return false;
        }

        return true;
    }

    protected function get_required_error_message( $value ) {
        if ( empty( $value['price_type'] ) ) {
            return ( $this->get_price_type_prop() === 'both' )
                ? __( 'Please choose a pricing option.', 'directorist' )
                : __( 'Price is required.', 'directorist' );
        }

        if ( $value['price_type'] === 'range' && empty( $value['price_range'] ) ) {
            return __( 'Price range is required.', 'directorist' );
        }

        if ( $value['price_type'] === 'price' && $this->is_price_empty( $value['price'] ) ) {
            return __( 'Price is required.', 'directorist' );
        }

        return '';
    }

    protected function is_price_empty( $price ) {
        return (float) $price === 0.0;
    }

    protected function get_price_types() {
        return [ 'price', 'range' ];
    }

    protected function get_price_ranges() {
        return [ 'skimming', 'moderate', 'economy', 'bellow_economy' ];
    }

    protected function get_price_type_prop() {
        $pricing_type = $this->__get( 'pricing_type' );

        if ( $pricing_type === 'price_unit' ) {
            return 'price';
        } elseif ( $pricing_type === 'price_range' ) {
            return 'range';
        } else {
            return 'both';
        }
    }
}

Fields::register( new Pricing_Field() );
