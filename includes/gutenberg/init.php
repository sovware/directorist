<?php
/**
 * Gutenberg block register functions.
 */
namespace WpWax\Directorist\Gutenberg;

defined( 'ABSPATH' ) || die();

/**
 * Initialize gutenberg blocks.
 *
 * @return void
 */
function init_blocks() {
	$dir = __DIR__;

	$script_asset_path = "$dir/assets/index.asset.php";

	if ( ! file_exists( $script_asset_path ) ) {
		throw new Error(
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
		'checkout',
		'custom-registration',
		'payment-receipt',
		'transaction-failure',
		'user-dashboard',
		'user-login',

		'all-listing',
	);

	foreach ( $blocks as $block ) {
		$args['attributes'] = get_attributes_map( $block );
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
add_filter( 'block_categories', __NAMESPACE__ . '\register_category' );

/**
 * Single listing dynamic render callback.
 * 
 * @param array $atts
 * @param string $content
 * 
 * @return string
 */
function dynamic_render_callback( $atts, $content, $instance ) {
	$shortcode = str_replace( array( '/', '-' ), '_', $instance->name );
	$output    = directorist_do_shortcode( $shortcode, $atts, $content );
	
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
 *
 * @param string $tag     The shortcode whose function to call.
 * @param array  $atts    The attributes to pass to the shortcode function. Optional.
 * @param array  $content The shortcode's content. Default is null (none).
 *
 * @return string|bool False on failure, the result of the shortcode on success.
 */
function directorist_do_shortcode( $tag, array $atts = array(), $content = null ) {
	global $shortcode_tags;

	if ( ! isset( $shortcode_tags[ $tag ] ) ) {
			return false;
	}

	return call_user_func( $shortcode_tags[ $tag ], $atts, $content, $tag );
}

function get_attributes_map( $block ) {
	$blocks_attributes = array(
		'all-listing' => array(
			'view' => array (
				'type'    => 'string',
				'default' => 'grid',
			),
			'featured' =>  array(
				'type'    => 'boolean',
				'default' => false,
			),
			'filterby' => array(
				'type'    => 'string',
				'default' => '',
			),
			'orderby' => array(
				'type'    => 'string',
				'default' => 'date',
			),
			'order' => array(
				'type'    => 'string',
				'default' => 'desc',
			),
			'per_page' => array(
				'type'    => 'number',
				'default' => 6,
			),
			'pagination' => array(
				'type'    => 'boolean',
				'default' => false,
			),
			'header' => array(
				'type'    => 'boolean',
				'default' => false,
			),
			'header_title' => array(
				'type'    => 'string',
				'default' => '',
			),
			'category' => array(
				'type'    => 'array',
				'default' => array(),
			),
			'location' => array(
				'type'    => 'array',
				'default' => array(),
			),
			'tag' => array(
				'type'    => 'array',
				'default' => array(),
			),
			'ids' => array(
				'type'    => 'string',
				'default' => '',
			),
			'columns' => array(
				'type'    => 'number',
				'default' => 3,
			),
			'featured_only' => array(
				'type'    => 'boolean',
				'default' => false,
			),
			'popular_only' => array(
				'type'    => 'boolean',
				'default' => false,
			),
			'filter' => array(
				'type'    => 'boolean',
				'default' => false,
			),
			'preview_image' => array(
				'type'    => 'boolean',
				'default' => false,
			),
			'loop_hook' => array(
				'type'    => 'boolean',
				'default' => false,
			),
			'logged_in_only' => array(
				'type'    => 'boolean',
				'default' => false,
			),
			'map_height' => array(
				'type'    => 'number',
				'default' => 500,
			),
			'map_zoom_level' => array(
				'type'    => 'number',
				'default' => 0,
			),
			'directory_type' => array(
				'type'    => 'array',
				'default' => array(),
			),
			'default_directory_type' => array(
				'type'    => 'number',
				'default' => 0,
			)
		)
	);

	return isset( $blocks_attributes[ $block ] ) ? $blocks_attributes[ $block ] : array();
}
