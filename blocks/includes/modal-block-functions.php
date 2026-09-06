<?php
/**
 * Shared rendering helpers for Directorist modal trigger blocks.
 *
 * @package Directorist
 */

defined( 'ABSPATH' ) || exit;

/**
 * Get the next request-local instance number for a modal block.
 *
 * @param string $key Modal type key.
 *
 * @return int
 */
function directorist_modal_block_instance( $key ) {
    static $instances = [];

    $key = sanitize_key( $key );

    if ( ! isset( $instances[ $key ] ) ) {
        $instances[ $key ] = 0;
    }

    $instances[ $key ]++;

    return $instances[ $key ];
}

/**
 * Get trigger text from block attributes or legacy saved markup.
 *
 * Source-based block attributes are not available to a dynamic PHP renderer,
 * so existing content needs a saved-markup fallback.
 *
 * @param array  $attributes Block attributes.
 * @param string $content    Saved block markup.
 *
 * @return string
 */
function directorist_modal_block_trigger_text( $attributes, $content ) {
    if ( ! empty( $attributes['text'] ) ) {
        return $attributes['text'];
    }

    if ( preg_match( '/<(?:a|button)\b[^>]*>(.*?)<\/(?:a|button)>/is', $content, $matches ) ) {
        return $matches[1];
    }

    return '';
}

/**
 * Render a configurable modal trigger icon.
 *
 * @param array  $attributes Block attributes.
 * @param string $prefix     Attribute prefix.
 * @param string $default    Default Directorist icon class.
 *
 * @return string
 */
function directorist_modal_block_icon( $attributes, $prefix = '', $default = '' ) {
    $icon_key   = $prefix ? $prefix . 'Icon' : 'icon';
    $source_key = $icon_key . 'Source';
    $class_key  = $icon_key . 'Class';
    $url_key    = $icon_key . 'Url';
    $source     = isset( $attributes[ $source_key ] ) ? $attributes[ $source_key ] : 'library';

    if ( 'image' === $source && ! empty( $attributes[ $url_key ] ) ) {
        return sprintf(
            '<img class="directorist-modal-trigger__custom-icon" src="%s" alt="" aria-hidden="true" />',
            esc_url( $attributes[ $url_key ] )
        );
    }

    $icon        = ! empty( $attributes[ $class_key ] ) ? $attributes[ $class_key ] : $default;
    $icon_tokens = array_filter( array_map( 'sanitize_html_class', preg_split( '/\s+/', $icon ) ) );

    return directorist_icon(
        implode( ' ', $icon_tokens ),
        false,
        'directorist-modal-trigger__icon'
    );
}

/**
 * Build trigger styles from block attributes.
 *
 * @param array  $attributes Block attributes.
 * @param string $prefix     Attribute prefix.
 *
 * @return string
 */
function directorist_modal_block_trigger_style( $attributes, $prefix = '' ) {
    $icon_key  = $prefix ? $prefix . 'Icon' : 'icon';
    $size_key  = $icon_key . 'Size';
    $color_key = $icon_key . 'Color';
    $gap_key   = $icon_key . 'Gap';
    $size      = isset( $attributes[ $size_key ] ) ? absint( $attributes[ $size_key ] ) : 24;
    $gap       = isset( $attributes[ $gap_key ] ) ? absint( $attributes[ $gap_key ] ) : 8;
    $styles    = [
        '--directorist-modal-icon-size:' . min( 120, max( 12, $size ) ) . 'px',
        '--directorist-modal-icon-gap:' . min( 48, $gap ) . 'px',
    ];

    if ( ! empty( $attributes[ $color_key ] ) ) {
        $color = sanitize_hex_color( $attributes[ $color_key ] );

        if ( $color ) {
            $styles[] = '--directorist-modal-icon-color:' . $color;
        }
    }

    if ( ! empty( $attributes['width'] ) ) {
        $styles[] = 'width:' . min( 100, max( 10, absint( $attributes['width'] ) ) ) . '%';
    }

    return implode( ';', $styles );
}

/**
 * Get the native block wrapper attributes for a modal trigger.
 *
 * @param array  $attributes Block attributes.
 * @param string $class_name Additional trigger classes.
 * @param string $prefix     Attribute prefix.
 *
 * @return string
 */
function directorist_modal_block_wrapper_attributes( $attributes, $class_name, $prefix = '' ) {
    $icon_key     = $prefix ? $prefix . 'Icon' : 'icon';
    $position_key = $icon_key . 'Position';
    $position     = isset( $attributes[ $position_key ] ) ? $attributes[ $position_key ] : 'before';

    if ( 'after' === $position ) {
        $class_name .= ' directorist-modal-trigger--reverse';
    }

    return get_block_wrapper_attributes(
        [
            'class' => $class_name,
            'style' => directorist_modal_block_trigger_style( $attributes, $prefix ),
        ]
    );
}
