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
 * Read classes, inline styles, and the ID from saved block markup.
 *
 * Dynamic modal blocks replace their saved markup on the frontend. Reading the
 * native wrapper and button attributes keeps Gutenberg style support intact.
 *
 * @param string $content Saved block markup.
 * @param string $tag     Element tag to inspect.
 *
 * @return array
 */
function directorist_modal_block_saved_element_attributes( $content, $tag ) {
    $saved = [
        'class' => [],
        'style' => '',
        'id'    => '',
    ];
    $tag   = preg_replace( '/[^a-z0-9]/i', '', $tag );

    if ( ! $tag || ! preg_match( '/<' . preg_quote( $tag, '/' ) . '\\b([^>]*)>/i', $content, $element ) ) {
        return $saved;
    }

    foreach ( [ 'class', 'style', 'id' ] as $attribute ) {
        if ( ! preg_match( '/\\b' . $attribute . '\\s*=\\s*(["\'])(.*?)\\1/is', $element[1], $match ) ) {
            continue;
        }

        $value = html_entity_decode( $match[2], ENT_QUOTES, get_bloginfo( 'charset' ) ?: 'UTF-8' );

        if ( 'class' === $attribute ) {
            $saved['class'] = array_filter( array_map( 'sanitize_html_class', preg_split( '/\\s+/', $value ) ) );
        } elseif ( 'style' === $attribute ) {
            $saved['style'] = safecss_filter_attr( $value );
        } else {
            $saved['id'] = sanitize_html_class( $value );
        }
    }

    return $saved;
}

/**
 * Format safe HTML attributes for modal block elements.
 *
 * @param array  $classes Element classes.
 * @param string $style   Inline style declarations.
 * @param string $id      Optional element ID.
 *
 * @return string
 */
function directorist_modal_block_html_attributes( $classes, $style = '', $id = '' ) {
    $classes    = array_unique( array_filter( array_map( 'sanitize_html_class', $classes ) ) );
    $attributes = 'class="' . esc_attr( implode( ' ', $classes ) ) . '"';
    $style      = safecss_filter_attr( $style );

    if ( $style ) {
        $attributes .= ' style="' . esc_attr( $style ) . '"';
    }

    if ( $id ) {
        $attributes .= ' id="' . esc_attr( sanitize_html_class( $id ) ) . '"';
    }

    return $attributes;
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

    $icon          = ! empty( $attributes[ $class_key ] ) ? $attributes[ $class_key ] : $default;
    $icon_tokens   = array_filter( array_map( 'sanitize_html_class', preg_split( '/\s+/', $icon ) ) );
    $icon          = implode( ' ', $icon_tokens );
    $icon_src      = \Directorist\Helper::get_icon_src( $icon );
    $icon_base_url = ATBDP_URL . 'assets/icons/';
    $icon_exists   = false;

    if ( $icon_src && 0 === strpos( $icon_src, $icon_base_url ) ) {
        $icon_path   = ltrim( substr( $icon_src, strlen( $icon_base_url ) ), '/' );
        $icon_exists = file_exists( DIRECTORIST_ICON_PATH . $icon_path );
    }

    if ( ! $icon_exists ) {
        $icon = $default;
    }

    return directorist_icon(
        $icon,
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

    return implode( ';', $styles );
}

/**
 * Get native block container attributes while preserving saved wrapper state.
 *
 * @param array  $attributes Block attributes.
 * @param string $content    Saved block markup.
 * @param string $class_name Required container class names.
 *
 * @return string
 */
function directorist_modal_block_container_attributes( $attributes, $content, $class_name ) {
    $saved   = directorist_modal_block_saved_element_attributes( $content, 'div' );
    $classes = array_merge( preg_split( '/\s+/', $class_name ), $saved['class'] );
    $styles  = array_filter( [ $saved['style'] ] );

    if ( ! empty( $attributes['className'] ) ) {
        $classes = array_merge( $classes, preg_split( '/\s+/', $attributes['className'] ) );
    }

    if ( ! empty( $attributes['width'] ) ) {
        $width     = min( 100, max( 10, absint( $attributes['width'] ) ) );
        $classes[] = 'has-custom-width';
        $classes[] = 'wp-block-button__width-' . $width;
        $styles[]  = 'width:' . $width . '%';
    }

    $id = ! empty( $attributes['anchor'] ) ? $attributes['anchor'] : $saved['id'];

    return directorist_modal_block_html_attributes( $classes, implode( ';', $styles ), $id );
}

/**
 * Get the native block wrapper attributes for a modal trigger.
 *
 * @param array  $attributes Block attributes.
 * @param string $class_name Additional trigger classes.
 * @param string $prefix     Attribute prefix.
 * @param string $content    Saved block markup.
 *
 * @return string
 */
function directorist_modal_block_wrapper_attributes( $attributes, $class_name, $prefix = '', $content = '' ) {
    $icon_key     = $prefix ? $prefix . 'Icon' : 'icon';
    $position_key = $icon_key . 'Position';
    $position     = isset( $attributes[ $position_key ] ) ? $attributes[ $position_key ] : 'before';

    if ( 'after' === $position ) {
        $class_name .= ' directorist-modal-trigger--reverse';
    }

    $saved   = directorist_modal_block_saved_element_attributes( $content, 'button' );
    $classes = array_merge( preg_split( '/\s+/', $class_name ), $saved['class'] );
    $styles  = array_filter(
        [
            $saved['style'],
            directorist_modal_block_trigger_style( $attributes, $prefix ),
        ]
    );

    return directorist_modal_block_html_attributes( $classes, implode( ';', $styles ) );
}
