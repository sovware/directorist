<?php
/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */

use Directorist\Helper;
use Directorist\Asset_Loader\Helper as AssetHelper;

define( 'DIRECTORIST_BLOCK_TEMPLATE_PATH', __DIR__ . '/templates' );

require_once __DIR__ . '/includes/class-block-template-utils.php';
require_once __DIR__ . '/includes/class-block-templates-controller.php';

// Check if the current theme is a block theme
if ( wp_is_block_theme() ) {
    // Enable widgets support
    add_theme_support( 'widgets' );
}

/**
 * Initialize gutenberg blocks.
 *
 * @return void
 */
function directorist_register_blocks() {

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

	$styles = [
		'directorist-main-style',
		'directorist-select2-style',
		'directorist-ez-media-uploader-style',
		'directorist-swiper-style',
		'directorist-sweetalert-style'
	];

	if ( AssetHelper::map_type() === 'openstreet' ) {
		$styles[] = 'directorist-openstreet-map-leaflet';
		$styles[] = 'directorist-openstreet-map-openstreet';
	}

	if ( (bool) get_directorist_option( 'legacy_icon' ) ) {
		$styles[] = 'directorist-line-awesome';
		$styles[] = 'directorist-font-awesome';
		$styles[] = 'directorist-unicons';
	}

	$args = array(
		'render_callback' => 'directorist_block_render_callback',
		'style'           => $styles,
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
	register_block_type( __DIR__ . '/build/authors', $args );
	register_block_type( __DIR__ . '/build/checkout', $args );
	register_block_type( __DIR__ . '/build/payment-receipt', $args );
	register_block_type( __DIR__ . '/build/transaction-failure', $args );
	register_block_type( __DIR__ . '/build/signin-signup', $args );
	register_block_type( __DIR__ . '/build/dashboard', $args );
	register_block_type( __DIR__ . '/build/single-listing', $args );
	register_block_type( __DIR__ . '/build/account-button' );
	register_block_type( __DIR__ . '/build/search-modal' );
}
add_action( 'init', 'directorist_register_blocks' );

/**
 * Register gutenberg block category.
 *
 * @param array $categories
 *
 * @return array Modified $categories
 */
function directorist_register_block_category( $categories ) {
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
	add_filter( 'block_categories_all', 'directorist_register_block_category' );
} else {
	// Before 5.8
	add_filter( 'block_categories', 'directorist_register_block_category' );
}

/**
 * Single listing dynamic render callback.
 *
 * @param array $atts
 * @param string $content
 *
 * @return string
 */
function directorist_block_render_callback( $attributes, $content, $instance ) {
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

		if ( isset( $attributes['sidebar'] ) && $attributes['sidebar'] === '' ) {
			unset( $attributes['sidebar'] );
		}

		unset( $key );
		unset( $value );
	}

	if ( isset( $_GET['context'] ) && $_GET['context'] === 'edit' && $shortcode === 'directorist_signin_signup' ) {
		return _directorist_render_editor_signin_signup_template( $attributes );
	}

	$attributes['is_block_editor'] = true;
	$output = directorist_do_shortcode_callback( $shortcode, $attributes, $content );

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
function directorist_do_shortcode_callback( $tag, array $atts = array(), $content = null ) {
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
function directorist_tax_show_in_rest( $args, $name ) {
	if ( in_array( $name, array( ATBDP_LOCATION, ATBDP_CATEGORY, ATBDP_TAGS, ATBDP_TYPE ), true ) ) {
		$args['show_in_rest'] = true;
	}

	return $args;
}
add_filter( 'register_taxonomy_args', 'directorist_tax_show_in_rest', 10, 2 );

/**
 * Enable rest api for directorist post type.
 *
 * @param array $args Post type arguments
 * @param string $name Post type slug
 *
 * @return array Modified $args
 */
function directorist_post_type_show_in_rest( $args, $name ) {
	if ( $name === ATBDP_POST_TYPE ) {
		$args['show_in_rest'] = true;
	}

	return $args;
}
add_filter( 'register_post_type_args', 'directorist_post_type_show_in_rest', 10, 2 );

/**
 * Disable gutenberg editor or block editor for our directory post type.
 *
 * @param bool $current_status
 * @param string $post_type
 *
 * @return bool
 */
function directorist_disable_block_editor( $current_status, $post_type ) {
    if ( $post_type === ATBDP_POST_TYPE ) {
		return false;
	}
    return $current_status;
}
add_filter( 'use_block_editor_for_post_type', 'directorist_disable_block_editor', 10, 2 );

function directorist_add_single_listing_shortcode( $atts = array() ) {
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
	} catch( Exception $e ) {
		if ( current_user_can( 'edit_posts' ) ) {
			return '<p class="directorist-alert directorist-alert-info" style="text-align:center">' . $e->getMessage() . '</p>';
		}

		return '';
	}
}
add_shortcode( 'directorist_single_listing', 'directorist_add_single_listing_shortcode' );

function directorist_account_block_avatar_image( $size = 40 ) {
	$image_id  = (int) get_user_meta( get_current_user_id(), 'pro_pic', true );
	$image_url = wp_get_attachment_image_url( $image_id, 'thumbnail' );

	if ( empty( $image_url ) ) {
		echo get_avatar(
			get_current_user_id(),
			$size,
			null,
			null,
			[
				'class' => 'rounded-circle'
			]
		);
	} else {
		echo sprintf(
			'<img width="%1$s" src="%2$s" alt="%2$s" class="avatar rounded-circle"/>',
			$size,
			esc_url( $image_url ),
			get_the_author_meta( 'display_name', get_current_user_id() )
		);
	}
}

function directorist_register_blocks_common_assets() {
	$asset_file = __DIR__ . '/assets/index.asset.php';

	if ( file_exists( $asset_file ) ) {
		$asset = include_once $asset_file;

		wp_enqueue_style(
			'directorist-blocks-common',
			plugin_dir_url( __FILE__ ) .  'assets/index' . ( is_rtl() ? '-rtl.css' : '.css' ),
			[],
			isset( $asset['version'] ) ?? ATBDP_VERSION
		);
	}
}
add_action( 'enqueue_block_assets', 'directorist_register_blocks_common_assets' );

function _directorist_render_editor_signin_signup_template( $attributes = array() ) {
	ob_start();
	include_once __DIR__ . '/templates/signin-signup.php';
	return ob_get_clean();
}
