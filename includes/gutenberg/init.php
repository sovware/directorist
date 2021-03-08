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
		'category',
		'location',
		'tag',

		'all-categories',
		'all-locations',

		'author-profile',

		'search-result',
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
	$atts_map  = get_attributes_map( str_replace( 'directorist/', '', $instance->name ) );
	
	foreach ( $atts as $_key => $_value ) {
		if ( ! isset( $atts_map[ $_key  ] ) || $_value === "" ) {
			unset( $atts[ $_key ] );
		}

		if ( $atts_map[ $_key ]['type'] === 'boolean' ) {
			$atts[ $_key ] = $_value ? 'yes' : 'no';
		}

		unset( $_key );
		unset( $_value );
	}

	$output = directorist_do_shortcode( $shortcode, $atts, $content );
	
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
	$common = array(
		'view' => array (
			'type'    => 'string',
			'default' => 'grid',
		),
		'orderby' => array(
			'type'    => 'string',
			'default' => 'date',
		),
		'order' => array(
			'type'    => 'string',
			'default' => 'desc',
		),
		'listings_per_page' => array(
			'type'    => 'number',
			'default' => 6,
		),
		'show_pagination' => array(
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
		'columns' => array(
			'type'    => 'number',
			'default' => 3,
		),
		'logged_in_user_only' => array(
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
	);

	$categories_locations_map = array(
		'view' => array (
			'type'    => 'string',
			'default' => 'grid',
		),
		'orderby' => array(
			'type'    => 'string',
			'default' => 'date',
		),
		'order' => array(
			'type'    => 'string',
			'default' => 'desc',
		),
		'columns' => array(
			'type'    => 'number',
			'default' => 3,
		),
		'slug' => array(
			'type'    => 'string',
			'default' => '',
		),
		'logged_in_user_only' => array(
			'type'    => 'boolean',
			'default' => false,
		),
		'redirect_page_url' => array(
			'type'    => 'string',
			'default' => '',
		),
		'directory_type' => array(
			'type'    => 'array',
			'default' => array(),
		),
		'default_directory_type' => array(
			'type'    => 'number',
			'default' => 0,
		)
	);

	$blocks_attributes = array(
		'all-listing' => array(
			'view' => array (
				'type'    => 'string',
				'default' => 'grid',
			),
			'_featured' =>  array(
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
			'listings_per_page' => array(
				'type'    => 'number',
				'default' => 6,
			),
			'show_pagination' => array(
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
				'type'    => 'string',
				'default' => '',
			),
			'location' => array(
				'type'    => 'string',
				'default' => '',
			),
			'tag' => array(
				'type'    => 'string',
				'default' => '',
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
			'advanced_filter' => array(
				'type'    => 'boolean',
				'default' => false,
			),
			'display_preview_image' => array(
				'type'    => 'boolean',
				'default' => false,
			),
			'action_before_after_loop' => array(
				'type'    => 'boolean',
				'default' => false,
			),
			'logged_in_user_only' => array(
				'type'    => 'boolean',
				'default' => false,
			),
			'redirect_page_url' => array(
				'type'    => 'string',
				'default' => '',
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
		),
		'category' => $common,
		'location' => $common,
		'tag' => $common,

		'all-categories' => array_merge( $categories_locations_map, array(
			'cat_per_page' => array(
				'type'    => 'number',
				'default' => 100,
			) ) ),
		'all-locations' => array_merge( $categories_locations_map, array(
			'loc_per_page' => array(
				'type'    => 'number',
				'default' => 100,
			) ) ),

		'author-profile' => array(
			'logged_in_user_only' => array(
				'type'    => 'boolean',
				'default' => false,
			) ),

		'search-result' => array_merge( $common, array(
			'featured_only' => array(
				'type'    => 'boolean',
				'default' => false,
			),
			'popular_only' => array(
				'type'    => 'boolean',
				'default' => false,
			),
		) )
	);

	return isset( $blocks_attributes[ $block ] ) ? $blocks_attributes[ $block ] : array();
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
	if ( in_array( $name, array( ATBDP_LOCATION, ATBDP_CATEGORY, ATBDP_TAGS ), true ) ) {
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
