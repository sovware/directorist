<?php
/**
 * Gutenberg block register functions.
 */
namespace WpWax\Directorist\Gutenberg;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

use Directorist\Helper;
use Exception;

require_once __DIR__ . '/includes/class-block-template-utils.php';
require_once __DIR__ . '/includes/class-block-templates-controller.php';

/**
 * Initialize gutenberg blocks.
 *
 * @return void
 */
function init_blocks() {
	$dir = __DIR__;

	$script_asset_path = "$dir/assets/index.asset.php";

	if ( ! file_exists( $script_asset_path ) ) {
		throw new Exception(
			'You need to run `npm run blocks:build` first.'
		);
	}

	$index_js     = 'assets/index.js';
	$script_asset = require( $script_asset_path );

	wp_register_script(
		'directorist-block-editor',
		plugins_url( $index_js, __FILE__ ),
		$script_asset['dependencies'],
		$script_asset['version']
	);

	wp_localize_script(
		'directorist-block-editor',
		'directoristBlockConfig',
		array(
			'multiDirectoryEnabled' => directorist_is_multi_directory_enabled(),
			'postType'              => ATBDP_POST_TYPE,
			'locationTax'           => ATBDP_LOCATION,
			'tagTax'                => ATBDP_TAGS,
			'categoryTax'           => ATBDP_CATEGORY,
			'typeTax'               => ATBDP_TYPE,
			'previewUrl'            => plugin_dir_url( __FILE__ )
		)
	);

	wp_set_script_translations( 'directorist-block-editor', 'directorist' );

	$editor_css = 'assets/index.css';

	wp_register_style(
		'directorist-block-editor',
		plugins_url( $editor_css, __FILE__ ),
		array(),
		filemtime( "$dir/$editor_css" )
	);

	$args = array(
		'editor_script'   => 'directorist-block-editor',
		'editor_style'    => 'directorist-block-editor',
		'render_callback' => __NAMESPACE__ . '\dynamic_render_callback',
	);

	$blocks = array(
		'add-listing',
		'all-categories',
		'all-listing',
		'all-locations',
		'author-profile',
		'category',
		'checkout',
		'custom-registration',
		'location',
		'payment-receipt',
		'search-result',
		'search-listing',
		'tag',
		'transaction-failure',
		'user-dashboard',
		'user-login',
		'single-listing',
	);

	foreach ( $blocks as $block ) {
		$args['attributes'] = array_merge(
			get_attributes_from_metadata( trailingslashit( __DIR__ ) . $block ),
			array(
				'isPreview' => array(
					'type'    => 'boolean',
					'default' => false,
				)
			)
		);

		register_block_type( 'directorist/' . $block, $args );
	}
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
function dynamic_render_callback( $atts, $content, $instance ) {
	$shortcode       = str_replace( array( '/', '-' ), '_', $instance->name );
	$block_name      = str_replace( 'directorist/', '', $instance->name );
	$registered_atts = get_attributes_from_metadata( trailingslashit( __DIR__ ) . $block_name );

	foreach ( $atts as $_key => $_value ) {
		if ( ! isset( $registered_atts[ $_key  ] ) || $_value === "" ) {
			unset( $atts[ $_key ] );
			continue;
		}

		if ( $registered_atts[ $_key ]['type'] === 'boolean' ) {
			$atts[ $_key ] = $_value ? 'yes' : 'no';
		}

		unset( $_key );
		unset( $_value );
	}

	$atts['is_block_editor'] = true;
	$output = do_shortcode_callback( $shortcode, $atts, $content );

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
 * Get block attributes map from json file.
 *
 * @param string $file_or_folder JSON file path or directory
 *
 * @return array Empty array if not found
 */
function get_attributes_from_metadata( $file_or_folder ) {
	$filename      = 'attributes.json';
	$metadata_file = ( substr( $file_or_folder, -strlen( $filename ) ) !== $filename ) ?
		trailingslashit( $file_or_folder ) . $filename :
		$file_or_folder;

	if ( ! file_exists( $metadata_file ) ) {
		return [];
	}

	$metadata = json_decode( file_get_contents( $metadata_file ), true );

	if ( empty( $metadata ) || ! is_array( $metadata )  ) {
		return [];
	}

	return $metadata;
}

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
