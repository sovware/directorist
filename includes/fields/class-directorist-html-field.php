<?php
/**
 * Directorist HTML Field class.
 *
 */
namespace Directorist\Fields;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class HTML_Field extends Textarea_Field {
    public $type = 'html';

    /**
     * Allowed HTML for HTML custom field.
     *
     * @return array
     */
    public static function allowed_html( $field = null ) {
        $allowed_html = wp_kses_allowed_html( 'post' );

        // Keep editor visual formatting/alignment classes and safe inline styles.
        $formatting_tags = [ 'p', 'div', 'span', 'strong', 'em', 'b', 'i', 'u', 'ul', 'ol', 'li', 'blockquote', 'a', 'img', 'figure', 'figcaption' ];
        foreach ( $formatting_tags as $tag ) {
            if ( ! isset( $allowed_html[ $tag ] ) || ! is_array( $allowed_html[ $tag ] ) ) {
                $allowed_html[ $tag ] = [];
            }
            $allowed_html[ $tag ]['class'] = true;
            $allowed_html[ $tag ]['style'] = true;
            $allowed_html[ $tag ]['id']    = true;
        }

        // Allow common embed iframe markup for video/map/widgets.
        $allowed_html['iframe'] = [
            'src'             => true,
            'width'           => true,
            'height'          => true,
            'title'           => true,
            'name'            => true,
            'class'           => true,
            'id'              => true,
            'style'           => true,
            'frameborder'     => true,
            'allow'           => true,
            'allowfullscreen' => true,
            'loading'         => true,
            'referrerpolicy'  => true,
            'sandbox'         => true,
        ];

        /**
         * Filters allowed HTML tags/attributes for Directorist HTML custom field.
         *
         * @param array      $allowed_html Allowed HTML map.
         * @param Base_Field $field        Current field instance.
         */
        return apply_filters( 'directorist_html_custom_field_allowed_html', $allowed_html, $field );
    }

    public function sanitize( $posted_data ) {
        return wp_kses( $this->get_value( $posted_data ), self::allowed_html( $this ) );
    }
}

Fields::register( new HTML_Field() );
