<?php
/**
 * Block register functions.
 */
namespace WpWax\Directorist\Block;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

use Exception;
use Directorist\Helper;

require_once __DIR__ . '/includes/class-block-template-utils.php';
require_once __DIR__ . '/includes/class-block-templates-controller.php';

/**
 * Initialize gutenberg blocks.
 *
 * @return void
 */
function init_blocks() {

	wp_localize_script(
		'wp-block-editor',
		'directoristBlockConfig',
		array(
			'tagTax'                => ATBDP_TAGS,
			'typeTax'               => ATBDP_TYPE,
			'categoryTax'           => ATBDP_CATEGORY,
			'locationTax'           => ATBDP_LOCATION,
			'postType'              => ATBDP_POST_TYPE,
			'previewUrl'            => plugin_dir_url( __FILE__ ),
			'multiDirectoryEnabled' => directorist_is_multi_directory_enabled(),
		)
	);

	// wp_set_script_translations( 'directorist-block-editor', 'directorist' );

	$args = array(
		'render_callback' => __NAMESPACE__ . '\dynamic_render_callback',
	);

	register_block_type( __DIR__ . '/build/listing-form', $args );
	register_block_type( __DIR__ . '/build/search-form', $args );
	register_block_type( __DIR__ . '/build/listings', $args );
	register_block_type( __DIR__ . '/build/categories', $args );
	register_block_type( __DIR__ . '/build/locations', $args );
	register_block_type( __DIR__ . '/build/single-category', $args );
	register_block_type( __DIR__ . '/build/single-location', $args );
	register_block_type( __DIR__ . '/build/single-tag', $args );
	register_block_type( __DIR__ . '/build/search-result', $args );
	register_block_type( __DIR__ . '/build/author-profile', $args );
	register_block_type( __DIR__ . '/build/checkout', $args );
	register_block_type( __DIR__ . '/build/payment-receipt', $args );
	register_block_type( __DIR__ . '/build/transaction-failure', $args );
	register_block_type( __DIR__ . '/build/login', $args );
	register_block_type( __DIR__ . '/build/registration', $args );
	register_block_type( __DIR__ . '/build/dashboard', $args );
	register_block_type( __DIR__ . '/build/single-listing', $args );
}
add_action( 'init', __NAMESPACE__ . '\init_blocks' );

/**
 * Register gutenberg block category.
 *
 * @param array $categories
 *
 * @return array Modified $categories
 */
function register_category( $categories ) {
	return array_merge(
		$categories,
		array(
			array(
				'slug'  => 'directorist-blocks-collection',
				'title' => __( 'Directorist', 'directorist' ),
			),
		)
	);
}

/**
 * Register directorist blocks category with backward compatibility.
 *
 * TODO: delete backward compatibility in the future.
 */
global $wp_version;
if ( version_compare( $wp_version, '5.8', '>=' ) ) {
	// For 5.8+
	add_filter( 'block_categories_all', __NAMESPACE__ . '\register_category' );
} else {
	// Before 5.8
	add_filter( 'block_categories', __NAMESPACE__ . '\register_category' );
}

/**
 * Single listing dynamic render callback.
 *
 * @param array $atts
 * @param string $content
 *
 * @return string
 */
function dynamic_render_callback( $attributes, $content, $instance ) {
	$shortcode         = str_replace( array( '/', '-' ), '_', $instance->name );
	$attributes_schema = $instance->block_type->get_attributes();

	foreach ( $attributes as $key => $value ) {
		if ( ! isset( $attributes_schema[ $key ] ) ) {
			unset( $attributes[ $key ] );
			continue;
		}

		if ( $attributes_schema[ $key ]['type'] === 'boolean' ) {
			$attributes[ $key ] = empty( $value ) ? 'no' : 'yes';
		}

		unset( $key );
		unset( $value );
	}

	$attributes['is_block_editor'] = true;
	$output = do_shortcode_callback( $shortcode, $attributes, $content );

	if ( empty( $output ) && current_user_can( 'edit_posts' ) ) {
		return sprintf(
			'<div>%s</div>',
			sprintf(
				__( 'Current block is %s and it is empty! Maybe the block is used incorrectly or there is something wrong.', 'directorist' ),
				'<mark>' . $instance->name . '</mark>'
			)
		);
	}

	return $output;
}

/**
 * Call a shortcode function by tag name.
 *
 * @param string $tag     The shortcode whose function to call.
 * @param array  $atts    The attributes to pass to the shortcode function. Optional.
 * @param array  $content The shortcode's content. Default is null (none).
 *
 * @return string|bool False on failure, the result of the shortcode on success.
 */
function do_shortcode_callback( $tag, array $atts = array(), $content = null ) {
	global $shortcode_tags;

	if ( ! isset( $shortcode_tags[ $tag ] ) ) {
		return false;
	}

	return call_user_func( $shortcode_tags[ $tag ], $atts, $content, $tag );
}

/**
 * Enable rest api for all directorist taxonomies.
 *
 * @param array $args Taxonomy arguments
 * @param string $name Taxonomy slug
 *
 * @return array Modified $args
 */
function tax_show_in_rest( $args, $name ) {
	if ( in_array( $name, array( ATBDP_LOCATION, ATBDP_CATEGORY, ATBDP_TAGS, ATBDP_TYPE ), true ) ) {
		$args['show_in_rest'] = true;
	}

	return $args;
}
add_filter( 'register_taxonomy_args', __NAMESPACE__ . '\tax_show_in_rest', 10, 2 );

/**
 * Enable rest api for directorist post type.
 *
 * @param array $args Post type arguments
 * @param string $name Post type slug
 *
 * @return array Modified $args
 */
function post_type_show_in_rest( $args, $name ) {
	if ( $name === ATBDP_POST_TYPE ) {
		$args['show_in_rest'] = true;
	}

	return $args;
}
add_filter( 'register_post_type_args', __NAMESPACE__ . '\post_type_show_in_rest', 10, 2 );

/**
 * Disable gutenberg editor or block editor for our directory post type.
 *
 * @param bool $current_status
 * @param string $post_type
 *
 * @return bool
 */
function disable_block_editor( $current_status, $post_type ) {
    if ( $post_type === ATBDP_POST_TYPE ) {
		return false;
	}
    return $current_status;
}
add_filter( 'use_block_editor_for_post_type', __NAMESPACE__ . '\disable_block_editor', 10, 2 );

function add_single_listing_shortcode( $atts = array() ) {
	if ( ! empty( $atts['is_block_editor'] ) ) {
		$source = _x( 'block', 'noun', 'directorist' );
	} else {
		$source = __( 'shortcode', 'directorist' );
	}

	try {
		if ( ! is_singular( ATBDP_POST_TYPE ) || ! is_main_query() ) {
			throw new Exception( sprintf( __( 'The only purpose of <mark>single listing %s</mark> is to show the single listing details. Maybe the block has been used in a wrong way!', 'directorist' ), $source ) );
		}

		if ( get_the_ID() !== get_queried_object_id() && get_post_type( get_the_ID() ) === 'page' ) {
			throw new Exception( sprintf( __( 'Looks like you are using <mark>single listing %s</mark> inside your custom single listing page. Please use the generated shortcodes from directory builder.', 'directorist' ), $source ) );
		}

		if ( get_post_type( get_the_ID() ) !== ATBDP_POST_TYPE ) {
			throw new Exception( sprintf( __( '<mark>Single listing %s</mark> has been used in a wrong way, please check documentation.', 'directorist' ), $source ) );
		}

		return Helper::get_template_contents( 'single-contents' );
	} catch( \Exception $e ) {
		if ( current_user_can( 'edit_posts' ) ) {
			return '<p class="directorist-alert directorist-alert-info" style="text-align:center">' . $e->getMessage() . '</p>';
		}

		return '';
	}
}
add_shortcode( 'directorist_single_listing', __NAMESPACE__ . '\add_single_listing_shortcode' );
