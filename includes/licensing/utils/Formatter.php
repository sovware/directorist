<?php

namespace Directorist\Licensing\Utils;

class Formatter {
    public static function get_formatted_price( string $price ): string {
        if ( '0.00' === $price ) {
            return '<span class="directorist-extension-price">Free</span>';
        }

        return sprintf(
            '<span class="directorist-extension-price">$%s</span><span class="directorist-extension-year"> /year </span>',
            esc_html( $price )
        );
    }
}