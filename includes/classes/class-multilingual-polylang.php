<?php

defined( 'ABSPATH' ) || die( 'Direct access is not allowed.' );

if ( ! class_exists( 'Directorist_Multilingual_Polylang' ) ) :

class Directorist_Multilingual_Polylang {
    public function __construct() {
        if ( ! function_exists( 'PLL' ) ) {
			return;
		}

        // Prevent importing default directory
        add_filter( 'atbdp_import_default_directory', '__return_false' );

        // Make custom post type translatable
        add_filter( 'pll_get_post_types', [ $this, 'enable_translation_to_custom_post_types' ], 10, 1 );
            
        // Make custom taxonomy translatable
        add_filter( 'pll_get_taxonomies', [ $this, 'enable_translation_to_custom_taxonomies' ], 10, 1 );

        // Show UI for directory type taxonomy
		add_filter( 'directorist_register_directory_taxonomy_args', [ $this, 'polylang_directory_taxonomy_args' ], 10, 1  );
		
        // Add language to request headers
        add_filter( 'directorist_localized_data', [ $this, 'polylang_localized_data' ], 20, 1 );
		
        // Switch current language in Ajax
        add_action( 'directorist_ajax_before_request_handling', [ $this, 'polylang_switch_current_language_in_ajax' ], 20 );
		
        // Switch permalink's language in Ajax
        add_filter( 'post_type_link', [ $this, 'polylang_switch_permalinks_language_in_ajax' ], 50, 2 );
		
        // Update term's language link
        add_filter( 'pll_the_language_link', [ $this, 'term_language_link_update' ], 20, 2 );
    }

    public function enable_translation_to_custom_post_types( array $post_types ): array {
        $post_types[ ATBDP_POST_TYPE ] = ATBDP_POST_TYPE;

        return $post_types;
    }

    public function enable_translation_to_custom_taxonomies( array $taxonomies ): array {
        $taxonomies[ ATBDP_DIRECTORY_TYPE ] = ATBDP_DIRECTORY_TYPE;
        $taxonomies[ ATBDP_CATEGORY ]       = ATBDP_CATEGORY;
        $taxonomies[ ATBDP_LOCATION ]       = ATBDP_LOCATION;
        $taxonomies[ ATBDP_TAGS ]           = ATBDP_TAGS;
        
        return $taxonomies;
    }

	public function polylang_directory_taxonomy_args( array $args ): array {
		$args['show_ui'] = true;

		return $args;
	}

	public function polylang_localized_data( $data ) {
		if ( ! isset( $data['request_headers'] ) ) {
			$data['request_headers'] = [];
		}

		$data['request_headers']['Lang'] = pll_current_language();

		return $data;
	}

	public function polylang_switch_current_language_in_ajax() {
		if ( empty( $_SERVER['HTTP_DIRECTORIST_LANG'] ) ) {
			return;
		}

		if ( $_SERVER['HTTP_DIRECTORIST_LANG'] === pll_current_language() ) {
			return;
		}
		
		$languages = PLL()->model->get_languages_list();

		foreach ( $languages as $language ) {
			if ( $language->slug === $_SERVER['HTTP_DIRECTORIST_LANG'] ) {
				PLL()->curlang = $language;
				break;
			}
		}
	}

	public function polylang_switch_permalinks_language_in_ajax( string $permalink ) {
		if ( empty( $_SERVER['HTTP_DIRECTORIST_LANG'] ) ) {
			return $permalink;
		}

		return PLL()->links_model->switch_language_in_link( $permalink, PLL()->model->get_language( $_SERVER['HTTP_DIRECTORIST_LANG'] ) );
	}

	// Term's Language link update
	public function term_language_link_update( $url, $current_lang ) {
        if ( ! function_exists( 'pll_get_post_language' ) ) {
			return;
		}

		// Adjust the category link
        $category_url = $this->update_term_language_link( [
            'term_type'            => 'category',
            'term_default_page_id' => get_directorist_option('single_category_page'),
            'term_query_var'       => ( ! empty( $_GET['category'] ) ) ? sanitize_text_field( wp_unslash( $_GET['category'] ) ) : get_query_var('atbdp_category'),
            'current_lang'         => $current_lang,
            'url'                  => $url,
        ] );

        if ( ! empty( $category_url ) ) {
            return $category_url; 
        }

        // Adjust the location link
        $location_url = $this->update_term_language_link( [
            'term_type'            => 'location',
            'term_default_page_id' => get_directorist_option('single_location_page'),
            'term_query_var'       => ( ! empty( $_GET['location'] ) ) ? sanitize_text_field( wp_unslash( $_GET['location'] ) ) : get_query_var('atbdp_location'),
            'current_lang'         => $current_lang,
            'url'                  => $url,
        ] );

        if ( ! empty( $location_url ) ) {
            return $location_url; 
        }

        return $url;
	}

	// Update Term Language Link
	public function update_term_language_link( $args ) {
		$default = [
			'term_type'            => '',
			'term_query_var'       => '',
			'term_default_page_id' => '',
			'current_lang'         => '',
			'url'                  => '',
		];

		$args = array_merge( $default, $args );

		if ( empty( $args[ 'term_query_var' ] ) ) {
            return false; 
        }

		// Get language slug of the default page
		$page_lang = pll_get_post_language( $args[ 'term_default_page_id' ] );

		// If current lang slug != default page then modyfy the url
		if ( $args[ 'current_lang' ] !== $page_lang ) {
			return $args['url'] ."?". $args['term_type'] ."=". $args['term_query_var'];
		}

		if ( $args[ 'current_lang' ] === $page_lang  ) {
			return $args['url'] . $args['term_query_var'];
		}

		return false;
	}
}

endif;