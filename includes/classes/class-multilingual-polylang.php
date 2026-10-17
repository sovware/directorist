<?php

defined( 'ABSPATH' ) || die( 'Direct access is not allowed.' );

if ( ! class_exists( 'Directorist_Multilingual_Polylang' ) ) :

    class Directorist_Multilingual_Polylang {
        private $reading_builder_meta = false;

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
            add_filter( 'directorist_register_directory_taxonomy_args', [ $this, 'polylang_directory_taxonomy_args' ], 10, 1 );
        
            // Add language to request headers
            add_filter( 'directorist_localized_data', [ $this, 'polylang_localized_data' ], 20, 1 );
        
            // Switch current language in Ajax
            add_action( 'directorist_ajax_before_request_handling', [ $this, 'polylang_switch_current_language_in_ajax' ], 20 );
        
            // Switch permalink's language in Ajax
            add_filter( 'post_type_link', [ $this, 'polylang_switch_permalinks_language_in_ajax' ], 50, 2 );
        
            // Update term's language link
            add_filter( 'pll_the_language_link', [ $this, 'term_language_link_update' ], 20, 2 );

            // Directory Type is a translated relationship, not a shared numeric ID.
            add_filter( 'pll_copy_post_metas', [ $this, 'copy_directory_type_meta' ], 10, 3 );
            add_filter( 'pll_translate_post_meta', [ $this, 'translate_directory_type_meta' ], 10, 3 );
            add_action( 'pll_save_post', [ $this, 'sync_listing_directory' ], 30 );
            add_action( 'added_post_meta', [ $this, 'sync_directory_meta' ], 20, 4 );
            add_action( 'updated_post_meta', [ $this, 'sync_directory_meta' ], 20, 4 );
            add_action( 'set_object_terms', [ $this, 'sync_directory_terms' ], 30, 4 );
            add_action( 'pll_save_term', [ $this, 'sync_taxonomy_directory' ], 30 );
            add_action( 'added_term_meta', [ $this, 'sync_taxonomy_directory_meta' ], 20, 4 );
            add_action( 'updated_term_meta', [ $this, 'sync_taxonomy_directory_meta' ], 20, 4 );

            add_filter( 'directorist_directory_attributes', [ $this, 'translate_directory_attributes' ] );
            add_filter( 'directorist_current_directory', [ $this, 'translate_current_directory' ] );
            add_filter( 'directorist_default_directory', [ $this, 'translate_default_directory' ], 10, 2 );
            add_filter( 'default_term_metadata', [ $this, 'inherit_directory_builder_meta' ], 10, 4 );
            add_filter( 'get_term_metadata', [ $this, 'inherit_all_directory_builder_meta' ], 10, 4 );
        }

        /** A translated term need not duplicate the source's global default flag. */
        public function translate_default_directory( $directory, $return ) {
            $language = pll_current_language();
            if ( ! $language ) {
                return $directory;
            }

            if ( ! $directory ) {
                $defaults = directorist_get_directories(
                    [
                        'default_only' => true,
                        'fields'       => 'ids',
                        'lang'         => '',
                    ]
                );
                $directory = ! is_wp_error( $defaults ) && isset( $defaults[0] ) ? $defaults[0] : 0;
            }

            $mapped = $this->translate_directory_type( $directory, $language );
            if ( 'slug' === $return && is_numeric( $mapped ) && $mapped ) {
                $term = get_term( (int) $mapped, ATBDP_DIRECTORY_TYPE );
                return $term && ! is_wp_error( $term ) ? $term->slug : $directory;
            }

            return $mapped;
        }

        /** Only builder presentation data is inherited, never identity or third-party meta. */
        private function directory_builder_meta_keys() {
            return [
                'submission_form_fields',
                'search_form_fields',
                'single_listing_header',
                'single_listings_contents',
                'single_listings_shortcodes',
                'listings_card_grid_view',
                'listings_card_list_view',
                'enable_sidebar',
                'preview_mode',
                'submit_button_label',
                'submit_button_label_old',
                'enable_single_listing_page',
            ];
        }

        /**
         * Read through to the linked default-language builder without database writes.
         * Existing values (including empty arrays/strings and false) remain authoritative.
         * The all-meta form also lets Directory Builder load inherited layouts for editing.
         */
        public function inherit_directory_builder_meta( $value, $term_id, $key, $single ) {
            if ( $this->reading_builder_meta || ! in_array( $key, $this->directory_builder_meta_keys(), true ) || $value !== ( $single ? '' : [] ) ) {
                return $value;
            }

            $meta = $this->get_inherited_directory_builder_meta( $term_id );
            if ( ! isset( $meta[ $key ] ) ) {
                return $value;
            }

            $values = array_map( 'maybe_unserialize', $meta[ $key ] );
            return $single ? reset( $values ) : $values;
        }

        public function inherit_all_directory_builder_meta( $value, $term_id, $key, $single ) {
            if ( null !== $value || $this->reading_builder_meta || '' !== $key || $single ) {
                return $value;
            }

            $meta = $this->get_inherited_directory_builder_meta( $term_id );
            return $meta ? $meta : $value;
        }

        /** Missing-key defaults must not affect metadata_exists() or metadata writes. */
        private function get_inherited_directory_builder_meta( $term_id ) {
            $term = get_term( $term_id, ATBDP_DIRECTORY_TYPE );
            if ( ! $term || is_wp_error( $term ) ) {
                return [];
            }

            $source = pll_get_term( $term_id, pll_default_language() );
            if ( ! $source || (int) $source === (int) $term_id ) {
                return [];
            }

            $this->reading_builder_meta = true;
            try {
                $stored    = get_term_meta( $term_id );
                $inherited = array_intersect_key( get_term_meta( $source ), array_flip( $this->directory_builder_meta_keys() ) );
            } finally {
                $this->reading_builder_meta = false;
            }

            // Keep the raw serialized representation expected by all-meta consumers.
            return $stored + $inherited;
        }

        /** Resolve a Directory Type ID or slug, retaining the original when untranslated. */
        public function translate_directory_type( $directory, $language ) {
            if ( ! $language || ! is_scalar( $directory ) || '' === (string) $directory ) {
                return $directory;
            }

            if ( is_numeric( $directory ) ) {
                $term = get_term( (int) $directory, ATBDP_DIRECTORY_TYPE );
            } else {
                // A source-language slug must be looked up outside the current
                // language filter before its translation can be resolved.
                $terms = get_terms(
                    [
                        'taxonomy'   => ATBDP_DIRECTORY_TYPE,
                        'slug'       => $directory,
                        'hide_empty' => false,
                        'number'     => 1,
                        'lang'       => '',
                    ]
                );
                $term = ! is_wp_error( $terms ) && $terms ? reset( $terms ) : false;
            }

            if ( ! $term || is_wp_error( $term ) ) {
                return $directory;
            }

            $translated_id = pll_get_term( $term->term_id, $language );
            $translated    = $translated_id ? get_term( $translated_id, ATBDP_DIRECTORY_TYPE ) : false;

            return $translated && ! is_wp_error( $translated ) ? (int) $translated->term_id : $directory;
        }

        public function copy_directory_type_meta( $keys, $sync, $from ) {
            if ( ATBDP_POST_TYPE === get_post_type( $from ) ) {
                $keys[] = '_directory_type';
            }

            return array_unique( $keys );
        }

        public function translate_directory_type_meta( $value, $key, $language ) {
            return '_directory_type' === $key ? $this->translate_directory_type( $value, $language ) : $value;
        }

        /** Also usable to repair an existing listing without rewriting its content. */
        public function sync_listing_directory( $post_id ) {
            static $syncing = [];

            if ( isset( $syncing[ $post_id ] ) || ATBDP_POST_TYPE !== get_post_type( $post_id ) || wp_is_post_revision( $post_id ) ) {
                return;
            }

            $language = pll_get_post_language( $post_id );
            $stored   = get_post_meta( $post_id, '_directory_type', true );
            $mapped   = $this->translate_directory_type( $stored, $language );

            // An unlinked/missing translation must not change an existing assignment.
            if ( ! $language || ! is_numeric( $mapped ) || pll_get_term_language( (int) $mapped ) !== $language ) {
                return;
            }

            $syncing[ $post_id ] = true;
            try {
                if ( (int) $stored !== (int) $mapped ) {
                    update_post_meta( $post_id, '_directory_type', (int) $mapped );
                }

                $terms = wp_get_object_terms( $post_id, ATBDP_DIRECTORY_TYPE, [ 'fields' => 'ids' ] );
                if ( ! is_wp_error( $terms ) && [ (int) $mapped ] !== $terms ) {
                    wp_set_object_terms( $post_id, [ (int) $mapped ], ATBDP_DIRECTORY_TYPE );
                }
            } finally {
                unset( $syncing[ $post_id ] );
            }
        }

        public function sync_directory_meta( $meta_id, $post_id, $key, $value ) {
            if ( '_directory_type' === $key ) {
                $this->sync_listing_directory( $post_id );
            }
        }

        public function sync_directory_terms( $object_id, $terms, $tt_ids, $taxonomy ) {
            // Setting a language can happen after the meta was copied (REST/import).
            if ( in_array( $taxonomy, [ ATBDP_DIRECTORY_TYPE, 'language' ], true ) ) {
                $this->sync_listing_directory( $object_id );
            }
        }

        /** Category/location associations are arrays, unlike listing Directory Type meta. */
        public function translate_taxonomy_directories( $term_id ) {
            $stored   = directorist_get_term_directory( $term_id );
            $language = pll_get_term_language( $term_id );
            $mapped   = [];
            foreach ( $stored as $directory ) {
                $mapped[] = (int) $this->translate_directory_type( $directory, $language );
            }

            return array_values( array_unique( $mapped ) );
        }

        public function sync_taxonomy_directory_meta( $meta_id, $term_id, $key, $value ) {
            if ( '_directory_type' === $key || 0 === strpos( $key, '_directory_type_' ) ) {
                $this->sync_taxonomy_directory( $term_id );
            }
        }

        /** Keep both legacy associations and indexed category/location queries aligned. */
        public function sync_taxonomy_directory( $term_id ) {
            static $syncing = [];
            if ( isset( $syncing[ $term_id ] ) ) {
                return;
            }

            $term = get_term( $term_id );
            if ( ! $term || is_wp_error( $term ) || ! in_array( $term->taxonomy, [ ATBDP_CATEGORY, ATBDP_LOCATION ], true ) || ! pll_get_term_language( $term_id ) ) {
                return;
            }

            $stored = directorist_get_term_directory( $term_id );
            if ( ! $stored ) {
                return;
            }

            $mapped              = $this->translate_taxonomy_directories( $term_id );
            $syncing[ $term_id ] = true;
            try {
                if ( $stored !== $mapped ) {
                    update_term_meta( $term_id, '_directory_type', $mapped );
                }
                foreach ( $mapped as $directory ) {
                    if ( ! metadata_exists( 'term', $term_id, '_directory_type_' . $directory ) ) {
                        update_term_meta( $term_id, '_directory_type_' . $directory, true );
                    }
                }
                foreach ( array_keys( get_term_meta( $term_id ) ) as $key ) {
                    if ( preg_match( '/^_directory_type_([0-9]+)$/', $key, $matches ) && ! in_array( (int) $matches[1], $mapped, true ) ) {
                        delete_term_meta( $term_id, $key );
                    }
                }
            } finally {
                unset( $syncing[ $term_id ] );
            }
        }

        public function translate_current_directory( $directory ) {
            return $this->translate_directory_type( $directory, pll_current_language() );
        }

        /** Blocks and shortcodes can retain source-language slugs after translation. */
        public function translate_directory_attributes( $attributes ) {
            $language = pll_current_language();
            if ( ! $language ) {
                return $attributes;
            }

            foreach ( [ 'directory_type', 'default_directory_type' ] as $key ) {
                if ( empty( $attributes[ $key ] ) || ! is_scalar( $attributes[ $key ] ) ) {
                    continue;
                }

                $values = explode( ',', (string) $attributes[ $key ] );
                foreach ( $values as &$value ) {
                    $value  = trim( $value );
                    $mapped = $this->translate_directory_type( $value, $language );
                    // directory_type is a slug list; default_directory_type accepts IDs.
                    if ( 'directory_type' === $key && is_numeric( $mapped ) ) {
                        $term  = get_term( (int) $mapped, ATBDP_DIRECTORY_TYPE );
                        $value = $term && ! is_wp_error( $term ) ? $term->slug : $value;
                    } else {
                        $value = $mapped;
                    }
                }
                unset( $value );
                $attributes[ $key ] = implode( ',', $values );
            }

            return $attributes;
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

            return PLL()->links_model->switch_language_in_link( $permalink, PLL()->model->get_language( $_SERVER['HTTP_DIRECTORIST_LANG'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
        }

        // Term's Language link update
        public function term_language_link_update( $url, $current_lang ) {
            if ( ! function_exists( 'pll_get_post_language' ) ) {
                return;
            }

            // Adjust the category link
            $category_url = $this->update_term_language_link(
                [
                    'term_type'            => 'category',
                    'term_default_page_id' => get_directorist_option( 'single_category_page' ),
                    'term_query_var'       => ( ! empty( $_GET['category'] ) ) ? sanitize_text_field( wp_unslash( $_GET['category'] ) ) : get_query_var( 'atbdp_category' ),
                    'current_lang'         => $current_lang,
                    'url'                  => $url,
                ] 
            );

            if ( ! empty( $category_url ) ) {
                return $category_url; 
            }

            // Adjust the location link
            $location_url = $this->update_term_language_link(
                [
                    'term_type'            => 'location',
                    'term_default_page_id' => get_directorist_option( 'single_location_page' ),
                    'term_query_var'       => ( ! empty( $_GET['location'] ) ) ? sanitize_text_field( wp_unslash( $_GET['location'] ) ) : get_query_var( 'atbdp_location' ),
                    'current_lang'         => $current_lang,
                    'url'                  => $url,
                ] 
            );

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
                return $args['url'] . "?" . $args['term_type'] . "=" . $args['term_query_var'];
            }

            if ( $args[ 'current_lang' ] === $page_lang ) {
                return $args['url'] . $args['term_query_var'];
            }

            return false;
        }
    }

endif;
