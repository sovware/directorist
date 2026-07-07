<?php
/**
 * Directorist Button Field class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Button_Field extends Base_Field {
    public $type = 'button';

    public function validate( $posted_data ) {
        $value = $this->get_value( $posted_data );

        if ( $this->is_value_empty( $posted_data ) ) {
            return true;
        }

        if ( ! is_array( $value ) ) {
            $this->add_error( __( 'Invalid button field.', 'directorist' ) );

            return false;
        }

        if ( ( isset( $value['button_text'] ) && ! is_scalar( $value['button_text'] ) ) || ( isset( $value['button_url_label'] ) && ! is_scalar( $value['button_url_label'] ) ) ) {
            $this->add_error( __( 'Invalid button field.', 'directorist' ) );

            return false;
        }

        if ( $this->is_required() && ( empty( $value['button_text'] ) || empty( $value['button_url_label'] ) ) ) {
            $this->add_error( __( 'This field is required.', 'directorist' ) );

            return false;
        }

        return true;
    }

    public function sanitize( $posted_data ) {
        $value = $this->get_value( $posted_data );

        if ( ! is_array( $value ) ) {
            return [];
        }

        return [
            'button_text'      => isset( $value['button_text'] ) && is_scalar( $value['button_text'] ) ? sanitize_text_field( $value['button_text'] ) : '',
            'button_url_label' => isset( $value['button_url_label'] ) && is_scalar( $value['button_url_label'] ) ? esc_url_raw( $value['button_url_label'] ) : '',
        ];
    }
}

Fields::register( new Button_Field() );
