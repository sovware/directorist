<?php
namespace WpWax\Directorist\Gutenberg;

use Elementor\Core\App\Modules\ImportExport\Directories\Templates;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Block_Template_Utils class used for serving block templates from Directorist Blocks.
 * IMPORTANT: These methods have been duplicated from Gutenberg/lib/full-site-editing/block-templates.php as those functions are not for public usage.
 */
class Block_Template_Utils {

	/**
	 * Directory names for block templates
	 *
	 * Directory names conventions for block templates have changed with Gutenberg 12.1.0,
	 * however, for backwards-compatibility, we also keep the older conventions, prefixed
	 * with `DEPRECATED_`.
	 *
	 * @var array {
	 *     @var string DEPRECATED_TEMPLATES  Old directory name of the block templates directory.
	 *     @var string DEPRECATED_TEMPLATE_PARTS  Old directory name of the block template parts directory.
	 *     @var string TEMPLATES_DIR_NAME  Directory name of the block templates directory.
	 *     @var string TEMPLATE_PARTS_DIR_NAME  Directory name of the block template parts directory.
	 * }
	 */
	const DIRECTORY_NAMES = array(
		'DEPRECATED_TEMPLATES'      => 'block-templates',
		'DEPRECATED_TEMPLATE_PARTS' => 'block-template-parts',
		'TEMPLATES'                 => 'templates',
		'TEMPLATE_PARTS'            => 'parts',
	);

	/**
	 * Directorist plugin slug
	 *
	 * This is used to save templates to the DB which are stored against this value in the wp_terms table.
	 *
	 * @var string
	 */
	const PLUGIN_SLUG = 'directorist/directorist-base';

	/**
	 * Returns an array containing the references of
	 * the passed blocks and their inner blocks.
	 *
	 * @param array $blocks array of blocks.
	 *
	 * @return array block references to the passed blocks and their inner blocks.
	 */
	public static function gutenberg_flatten_blocks( &$blocks ) {
		$all_blocks = array();
		$queue      = array();
		foreach ( $blocks as &$block ) {
			$queue[] = &$block;
		}
		$queue_count = count( $queue );

		while ( $queue_count > 0 ) {
			$block = &$queue[0];
			array_shift( $queue );
			$all_blocks[] = &$block;

			if ( ! empty( $block['innerBlocks'] ) ) {
				foreach ( $block['innerBlocks'] as &$inner_block ) {
					$queue[] = &$inner_block;
				}
			}

			$queue_count = count( $queue );
		}

		return $all_blocks;
	}

	/**
	 * Parses wp_template content and injects the current theme's
	 * stylesheet as a theme attribute into each wp_template_part
	 *
	 * @param string $template_content serialized wp_template content.
	 *
	 * @return string Updated wp_template content.
	 */
	public static function gutenberg_inject_theme_attribute_in_content( $template_content ) {
		$has_updated_content = false;
		$new_content         = '';
		$template_blocks     = parse_blocks( $template_content );

		$blocks = self::gutenberg_flatten_blocks( $template_blocks );
		foreach ( $blocks as &$block ) {
			if (
				'core/template-part' === $block['blockName'] &&
				! isset( $block['attrs']['theme'] )
			) {
				$block['attrs']['theme'] = wp_get_theme()->get_stylesheet();
				$has_updated_content     = true;
			}
		}

		if ( $has_updated_content ) {
			foreach ( $template_blocks as &$block ) {
				$new_content .= serialize_block( $block );
			}

			return $new_content;
		}

		return $template_content;
	}

	/**
	 * Build a unified template object based a post Object.
	 *
	 * @param \WP_Post $post Template post.
	 *
	 * @return \WP_Block_Template|\WP_Error Template.
	 */
	public static function gutenberg_build_template_result_from_post( $post ) {
		$terms = get_the_terms( $post, 'wp_theme' );

		if ( is_wp_error( $terms ) ) {
			return $terms;
		}

		if ( ! $terms ) {
			return new \WP_Error( 'template_missing_theme', __( 'No theme is defined for this template.', 'directorist' ) );
		}

		$theme          = $terms[0]->name;
		$has_theme_file = true;

		$template                 = new \WP_Block_Template();
		$template->wp_id          = $post->ID;
		$template->id             = $theme . '//' . $post->post_name;
		$template->theme          = $theme;
		$template->content        = $post->post_content;
		$template->slug           = $post->post_name;
		$template->source         = 'custom';
		$template->type           = $post->post_type;
		$template->description    = $post->post_excerpt;
		$template->title          = $post->post_title;
		$template->status         = $post->post_status;
		$template->has_theme_file = $has_theme_file;
		$template->is_custom      = false;
		$template->post_types     = array(); // Don't appear in any Edit Post template selector dropdown.

		if ( 'wp_template_part' === $post->post_type ) {
			$type_terms = get_the_terms( $post, 'wp_template_part_area' );
			if ( ! is_wp_error( $type_terms ) && false !== $type_terms ) {
				$template->area = $type_terms[0]->name;
			}
		}

		if ( self::PLUGIN_SLUG === $theme ) {
			$template->origin = 'plugin';
		}

		return $template;
	}

	/**
	 * Build a unified template object based on a theme file.
	 *
	 * @param array $template_file Theme file.
	 * @param array $template_type wp_template or wp_template_part.
	 *
	 * @return \WP_Block_Template Template.
	 */
	public static function gutenberg_build_template_result_from_file( $template_file, $template_type ) {
		$template_file = (object) $template_file;

		// If the theme has an archive-products.html template but does not have product taxonomy templates
		// then we will load in the archive-product.html template from the theme to use for product taxonomies on the frontend.
		$template_is_from_theme = 'theme' === $template_file->source;
		$theme_name             = wp_get_theme()->get( 'TextDomain' );

		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		$template_content  = file_get_contents( $template_file->path );
		$template          = new \WP_Block_Template();
		$template->id      = $template_is_from_theme ? $theme_name . '//' . $template_file->slug : self::PLUGIN_SLUG . '//' . $template_file->slug;
		$template->theme   = $template_is_from_theme ? $theme_name : self::PLUGIN_SLUG;
		$template->content = self::gutenberg_inject_theme_attribute_in_content( $template_content );
		// Plugin was agreed as a valid source value despite existing inline docs at the time of creating: https://github.com/WordPress/gutenberg/issues/36597#issuecomment-976232909.
		$template->source         = $template_file->source ? $template_file->source : 'plugin';
		$template->slug           = $template_file->slug;
		$template->type           = $template_type;
		$template->title          = ! empty( $template_file->title ) ? $template_file->title : self::convert_slug_to_title( $template_file->slug );
		$template->status         = 'publish';
		$template->has_theme_file = true;
		$template->origin         = $template_file->source;
		$template->is_custom      = false; // Templates loaded from the filesystem aren't custom, ones that have been edited and loaded from the DB are.
		$template->post_types     = array(); // Don't appear in any Edit Post template selector dropdown.
		if ( 'wp_template_part' === $template_type ) {
			$template->area = 'uncategorized';
		}
		return $template;
	}

	/**
	 * Build a new template object so that we can make Directorist Blocks default templates available in the current theme should they not have any.
	 *
	 * @param string $template_file Block template file path.
	 * @param string $template_type wp_template or wp_template_part.
	 * @param string $template_slug Block template slug e.g. single-LISTIN.
	 * @param bool   $template_is_from_theme If the block template file is being loaded from the current theme instead of Directorist Blocks.
	 *
	 * @return object Block template object.
	 */
	public static function create_new_block_template_object( $template_file, $template_type, $template_slug, $template_is_from_theme = false ) {
		$theme_name = wp_get_theme()->get( 'TextDomain' );

		$new_template_item = array(
			'slug'        => $template_slug,
			'id'          => $template_is_from_theme ? $theme_name . '//' . $template_slug : self::PLUGIN_SLUG . '//' . $template_slug,
			'path'        => $template_file,
			'type'        => $template_type,
			'theme'       => $template_is_from_theme ? $theme_name : self::PLUGIN_SLUG,
			// Plugin was agreed as a valid source value despite existing inline docs at the time of creating: https://github.com/WordPress/gutenberg/issues/36597#issuecomment-976232909.
			'source'      => $template_is_from_theme ? 'theme' : 'plugin',
			'title'       => self::convert_slug_to_title( $template_slug ),
			'description' => '',
			'post_types'  => array(), // Don't appear in any Edit Post template selector dropdown.
		);

		return (object) $new_template_item;
	}

	/**
	 * Finds all nested template part file paths in a theme's directory.
	 *
	 * @param string $base_directory The theme's file path.
	 * @return array $path_list A list of paths to all template part files.
	 */
	public static function gutenberg_get_template_paths( $base_directory ) {
		$path_list = array();
		if ( file_exists( $base_directory ) ) {
			$nested_files      = new \RecursiveIteratorIterator( new \RecursiveDirectoryIterator( $base_directory ) );
			$nested_html_files = new \RegexIterator( $nested_files, '/^.+\.html$/i', \RecursiveRegexIterator::GET_MATCH );
			foreach ( $nested_html_files as $path => $file ) {
				$path_list[] = $path;
			}
		}
		return $path_list;
	}

	/**
	 * Converts template slugs into readable titles.
	 *
	 * @param string $template_slug The templates slug (e.g. single-LISTIN).
	 * @return string Human friendly title converted from the slug.
	 */
	public static function convert_slug_to_title( $template_slug ) {
		if ( 'single-' . ATBDP_POST_TYPE === $template_slug ) {
			return __( 'Single Listing Page', 'directorist' );
		}

		// Replace all hyphens and underscores with spaces.
		return ucwords( preg_replace( '/[\-_]/', ' ', $template_slug ) );
	}

	/**
	 * Converts template paths into a slug
	 *
	 * @param string $path The template's path.
	 * @param string $directory_name The template's directory name.
	 * @return string slug
	 */
	public static function generate_template_slug_from_path( $path, $directory_name = 'block-templates' ) {
		return substr(
			$path,
			strpos( $path, $directory_name . DIRECTORY_SEPARATOR ) + 1 + strlen( $directory_name ),
			-5
		);
	}

	/**
	 * Check if the theme has a template. So we know if to load our own in or not.
	 *
	 * @param string $template_name name of the template file without .html extension e.g. 'single-listing'.
	 * @return boolean
	 */
	public static function theme_has_template( $template_name ) {
		return is_readable( get_template_directory() . '/block-templates/' . $template_name . '.html' ) ||
			is_readable( get_stylesheet_directory() . '/block-templates/' . $template_name . '.html' );
	}

	/**
	 * Check if the theme has a template. So we know if to load our own in or not.
	 *
	 * @param string $template_name name of the template file without .html extension e.g. 'single-LISTIN'.
	 * @return boolean
	 */
	public static function theme_has_template_part( $template_name ) {
		return is_readable( get_template_directory() . '/block-template-parts/' . $template_name . '.html' ) ||
			is_readable( get_stylesheet_directory() . '/block-template-parts/' . $template_name . '.html' );
	}

	/**
	 * Checks to see if they are using a compatible version of WP, or if not they have a compatible version of the Gutenberg plugin installed.
	 *
	 * @return boolean
	 */
	public static function supports_block_templates() {
		if (
			( ! function_exists( 'wp_is_block_theme' ) || ! wp_is_block_theme() ) &&
			( ! function_exists( 'gutenberg_supports_block_templates' ) || ! gutenberg_supports_block_templates() )
		) {
			return false;
		}

		return true;
	}
}
