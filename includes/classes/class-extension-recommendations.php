<?php
/**
 * Directory-aware extension recommendations for the connected admin dashboard.
 *
 * @package Directorist
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'ATBDP_Extension_Recommendations' ) ) {
    /**
     * Prepare extension recommendations without coupling presentation to catalog data.
     */
    class ATBDP_Extension_Recommendations {
        /**
         * Extension catalog.
         *
         * @var array
         */
        private $products = [];

        /**
         * Extension overview.
         *
         * @var array
         */
        private $overview = [];

        /**
         * Extension aliases.
         *
         * @var array
         */
        private $aliases = [];

        /**
         * Whether the current Directorist build is a beta.
         *
         * @var bool
         */
        private $is_beta = false;

        /**
         * Constructor.
         *
         * @param array $products Extension catalog.
         * @param array $overview Extension installation and entitlement overview.
         * @param array $aliases  Legacy extension aliases.
         * @param bool  $is_beta  Whether Directorist is a beta build.
         */
        public function __construct( array $products, array $overview, array $aliases = [], $is_beta = false ) {
            $this->products = $products;
            $this->overview = $overview;
            $this->aliases  = $aliases;
            $this->is_beta  = (bool) $is_beta;
        }

        /**
         * Build connected-dashboard recommendation data.
         *
         * @return array
         */
        public function get_dashboard_data() {
            $profiles       = $this->get_profiles();
            $directories    = directory_types();
            $directories    = is_array( $directories ) && ! is_wp_error( $directories ) ? $directories : [];
            $default_id     = (int) default_directory_type();
            $resolved_items = [];

            if ( empty( $directories ) ) {
                $resolved_items[] = $this->prepare_directory(
                    [
                        'id'   => 'generic',
                        'name' => __( 'your directory', 'directorist' ),
                        'slug' => 'generic',
                    ],
                    $profiles,
                    false
                );
                $default_id = 'generic';
            } else {
                foreach ( $directories as $directory ) {
                    if ( ! $directory instanceof WP_Term ) {
                        continue;
                    }

                    $resolved_items[] = $this->prepare_directory(
                        [
                            'id'   => (string) $directory->term_id,
                            'name' => $directory->name,
                            'slug' => $directory->slug,
                        ],
                        $profiles
                    );
                }
            }

            $resolved_items = array_values(
                array_filter(
                    $resolved_items,
                    static function( $directory ) {
                        return ! empty( $directory['items'] );
                    }
                )
            );

            if ( empty( $resolved_items ) ) {
                return [];
            }

            $available_ids = wp_list_pluck( $resolved_items, 'id' );
            $default_id    = (string) $default_id;

            if ( ! in_array( $default_id, $available_ids, true ) ) {
                $default_id = (string) $resolved_items[0]['id'];
            }

            $data = [
                'default_id'  => $default_id,
                'directories' => $resolved_items,
            ];

            /**
             * Filter the final directory-aware recommendation data.
             *
             * @param array $data     Prepared dashboard recommendation data.
             * @param array $products Current extension catalog.
             * @param array $overview Current extension overview.
             */
            return apply_filters(
                'directorist_extension_recommendation_data',
                $data,
                $this->products,
                $this->overview
            );
        }

        /**
         * Central recommendation registry.
         *
         * Product order controls the default recommendation priority. This registry is
         * a compatibility fallback; valid product API recommendation data may replace it.
         *
         * @return array
         */
        private function get_profiles() {
            $profiles = [
                'business' => [
                    'label'       => __( 'Business', 'directorist' ),
                    'aliases'     => [ 'business', 'local business', 'company', 'companies' ],
                    'description' => __( 'Add the essentials businesses need to publish, manage, and grow their listings.', 'directorist' ),
                    'products'    => [
                        'directorist-business-hours',
                        'directorist-claim-listing',
                        'directorist-listings-with-map',
                        'directorist-social-login',
                        'directorist-pricing-plans',
                        'directorist-advanced-review',
                    ],
                ],
                'classified' => [
                    'label'       => __( 'Classified', 'directorist' ),
                    'aliases'     => [ 'classified', 'classified ads', 'buy and sell', 'marketplace ads' ],
                    'description' => __( 'Help buyers and sellers communicate, track availability, and discover relevant listings.', 'directorist' ),
                    'products'    => [
                        'directorist-mark-as-sold',
                        'directorist-live-chat',
                        'directorist-pricing-plans',
                        'directorist-listings-with-map',
                        'directorist-social-login',
                        'directorist-gallery',
                        'directorist-search-alert',
                    ],
                ],
                'car' => [
                    'label'       => __( 'Car', 'directorist' ),
                    'aliases'     => [ 'car', 'cars', 'vehicle', 'vehicles', 'automotive', 'auto dealer', 'car sell', 'car rent' ],
                    'description' => __( 'Give vehicle listings the availability, location, booking, and comparison tools shoppers expect.', 'directorist' ),
                    'products'    => [
                        'directorist-mark-as-sold',
                        'directorist-listings-with-map',
                        'directorist-booking',
                        'directorist-compare-listing',
                        'directorist-gallery',
                        'directorist-faqs',
                        'directorist-business-hours',
                    ],
                ],
                'place' => [
                    'label'       => __( 'Place', 'directorist' ),
                    'aliases'     => [ 'place', 'places', 'travel', 'tourism', 'destination', 'destinations', 'attraction', 'attractions' ],
                    'description' => __( 'Make destinations easier to find, compare, explore, and book.', 'directorist' ),
                    'products'    => [
                        'directorist-listings-with-map',
                        'directorist-business-hours',
                        'directorist-booking',
                        'directorist-gallery',
                        'directorist-compare-listing',
                        'directorist-claim-listing',
                    ],
                ],
                'job' => [
                    'label'       => __( 'Job', 'directorist' ),
                    'aliases'     => [ 'job', 'jobs', 'career', 'careers', 'employment', 'recruitment' ],
                    'description' => __( 'Support job publishing, candidate access, and alerts for new opportunities.', 'directorist' ),
                    'products'    => [
                        'directorist-job-manager',
                        'directorist-social-login',
                        'directorist-search-alert',
                        'directorist-pricing-plans',
                        'directorist-ai-search',
                        'directorist-notifications-pro',
                    ],
                ],
                'hotel' => [
                    'label'       => __( 'Hotel', 'directorist' ),
                    'aliases'     => [ 'hotel', 'hotels', 'accommodation', 'lodging', 'motel', 'resort', 'resorts' ],
                    'description' => __( 'Add booking, comparison, opening-hour, and visual tools for accommodation listings.', 'directorist' ),
                    'products'    => [
                        'directorist-booking',
                        'directorist-compare-listing',
                        'directorist-business-hours',
                        'directorist-listings-with-map',
                        'directorist-gallery',
                        'directorist-faqs',
                    ],
                ],
                'restaurant' => [
                    'label'       => __( 'Restaurant', 'directorist' ),
                    'aliases'     => [ 'restaurant', 'restaurants', 'food', 'dining', 'cafe', 'cafes', 'coffee shop' ],
                    'description' => __( 'Add the opening hours, reservations, and rich photos restaurant visitors need.', 'directorist' ),
                    'products'    => [
                        'directorist-business-hours',
                        'directorist-booking',
                        'directorist-gallery',
                        'directorist-listings-with-map',
                        'directorist-claim-listing',
                        'directorist-faqs',
                        'directorist-live-chat',
                    ],
                ],
                'lawyer' => [
                    'label'       => __( 'Lawyer', 'directorist' ),
                    'aliases'     => [ 'lawyer', 'lawyers', 'legal', 'attorney', 'attorneys', 'law firm' ],
                    'description' => __( 'Help legal professionals build trust, publish availability, and receive appointments.', 'directorist' ),
                    'products'    => [
                        'directorist-booking',
                        'directorist-business-hours',
                        'directorist-advanced-review',
                        'directorist-claim-listing',
                        'directorist-listings-with-map',
                        'directorist-faqs',
                        'directorist-pricing-plans',
                    ],
                ],
                'doctor' => [
                    'label'       => __( 'Doctor', 'directorist' ),
                    'aliases'     => [ 'doctor', 'doctors', 'medical', 'healthcare', 'clinic', 'clinics', 'physician', 'dentist' ],
                    'description' => __( 'Support appointments, opening hours, trusted reviews, and verified provider profiles.', 'directorist' ),
                    'products'    => [
                        'directorist-booking',
                        'directorist-business-hours',
                        'directorist-advanced-review',
                        'directorist-claim-listing',
                        'directorist-listings-with-map',
                        'directorist-faqs',
                    ],
                ],
                'real-estate' => [
                    'label'       => __( 'Real Estate', 'directorist' ),
                    'aliases'     => [ 'real estate', 'realestate', 'property', 'properties', 'realtor', 'housing', 'rental' ],
                    'description' => __( 'Help property seekers search, compare, follow, and inspect listings.', 'directorist' ),
                    'products'    => [
                        'directorist-listings-with-map',
                        'directorist-compare-listing',
                        'directorist-search-alert',
                        'directorist-gallery',
                        'directorist-live-chat',
                        'directorist-business-hours',
                        'directorist-booking',
                        'directorist-faqs',
                    ],
                ],
                'post-your-need' => [
                    'label'       => __( 'Post Your Need', 'directorist' ),
                    'aliases'     => [ 'post your need', 'request a service', 'service request', 'find a provider' ],
                    'description' => __( 'Connect customer requests with suitable providers and keep conversations moving.', 'directorist' ),
                    'products'    => [
                        'directorist-live-chat',
                        'directorist-pricing-plans',
                        'directorist-social-login',
                        'directorist-notifications-pro',
                        'directorist-announcement',
                    ],
                ],
                'service' => [
                    'label'       => __( 'Service', 'directorist' ),
                    'aliases'     => [ 'service', 'services', 'service provider', 'professional service', 'contractor' ],
                    'description' => __( 'Help customers find, contact, and book the right service provider.', 'directorist' ),
                    'products'    => [
                        'directorist-booking',
                        'directorist-live-chat',
                        'directorist-business-hours',
                        'directorist-claim-listing',
                        'directorist-pricing-plans',
                    ],
                ],
                'generic' => [
                    'label'       => __( 'Directory', 'directorist' ),
                    'aliases'     => [],
                    'description' => __( 'Explore versatile add-ons that improve discovery, trust, and directory management.', 'directorist' ),
                    'products'    => [
                        'directorist-ai-search',
                        'directorist-analytics',
                        'directorist-pricing-plans',
                        'directorist-listings-with-map',
                        'directorist-advanced-review',
                        'directorist-business-hours',
                        'directorist-gallery',
                        'directorist-live-chat',
                    ],
                ],
            ];

            /**
             * Filter the centralized directory recommendation registry.
             *
             * @param array $profiles Directory profiles keyed by stable profile name.
             */
            $profiles = apply_filters( 'directorist_extension_recommendation_profiles', $profiles );
            $profiles = is_array( $profiles ) ? $profiles : [];

            return $this->apply_product_api_recommendations( $profiles );
        }

        /**
         * Apply optional per-product API recommendation data over local fallbacks.
         *
         * A missing field preserves the local mapping. An empty array intentionally
         * removes the product from all recommendation profiles.
         *
         * @param array $profiles Recommendation registry.
         *
         * @return array
         */
        private function apply_product_api_recommendations( array $profiles ) {
            foreach ( $this->products as $product_slug => $product ) {
                if ( ! is_array( $product ) || ! array_key_exists( 'recommendations', $product ) ) {
                    continue;
                }

                $recommendations = $product['recommendations'];

                if ( ! is_array( $recommendations ) ) {
                    continue;
                }

                $valid_recommendations = [];
                foreach ( $recommendations as $recommendation ) {
                    if ( ! is_array( $recommendation ) ) {
                        continue;
                    }

                    $profile_key = isset( $recommendation['profile'] ) ? sanitize_key( $recommendation['profile'] ) : '';
                    if ( ! $profile_key || ! isset( $profiles[ $profile_key ] ) ) {
                        continue;
                    }

                    $valid_recommendations[] = [
                        'profile'  => $profile_key,
                        'priority' => isset( $recommendation['priority'] ) ? max( 0, min( 100, (int) $recommendation['priority'] ) ) : 100,
                        'reason'   => isset( $recommendation['reason'] ) ? sanitize_text_field( (string) $recommendation['reason'] ) : '',
                    ];
                }

                if ( ! empty( $recommendations ) && empty( $valid_recommendations ) ) {
                    continue;
                }

                foreach ( $profiles as $profile_key => $profile ) {
                    $products = isset( $profile['products'] ) && is_array( $profile['products'] ) ? $profile['products'] : [];

                    $profiles[ $profile_key ]['products'] = array_values(
                        array_filter(
                            $products,
                            static function( $candidate ) use ( $product_slug ) {
                                $candidate_slug = is_array( $candidate ) ? ( $candidate['slug'] ?? '' ) : $candidate;

                                return (string) $candidate_slug !== (string) $product_slug;
                            }
                        )
                    );
                }

                foreach ( $valid_recommendations as $recommendation ) {
                    $profiles[ $recommendation['profile'] ]['products'][] = [
                        'slug'     => (string) $product_slug,
                        'priority' => $recommendation['priority'],
                        'reason'   => $recommendation['reason'],
                    ];
                }
            }

            foreach ( $profiles as $profile_key => $profile ) {
                if ( empty( $profile['products'] ) || ! is_array( $profile['products'] ) ) {
                    continue;
                }

                foreach ( $profiles[ $profile_key ]['products'] as $index => $candidate ) {
                    $candidate = is_array( $candidate ) ? $candidate : [ 'slug' => $candidate ];

                    if ( ! isset( $candidate['priority'] ) ) {
                        $candidate['priority'] = max( 1, 100 - ( (int) $index * 10 ) );
                    }

                    $candidate['_index'] = (int) $index;
                    $profiles[ $profile_key ]['products'][ $index ] = $candidate;
                }

                usort(
                    $profiles[ $profile_key ]['products'],
                    static function( $left, $right ) {
                        $left_priority  = isset( $left['priority'] ) ? (int) $left['priority'] : 0;
                        $right_priority = isset( $right['priority'] ) ? (int) $right['priority'] : 0;

                        if ( $left_priority === $right_priority ) {
                            return (int) ( $left['_index'] ?? 0 ) <=> (int) ( $right['_index'] ?? 0 );
                        }

                        return $right_priority <=> $left_priority;
                    }
                );
            }

            return $profiles;
        }

        /**
         * Prepare one real or generic directory recommendation group.
         *
         * @param array $directory Directory identity.
         * @param array $profiles  Recommendation registry.
         * @param bool  $classify  Whether to classify the directory name and slug.
         *
         * @return array
         */
        private function prepare_directory( array $directory, array $profiles, $classify = true ) {
            $profile_key = $classify ? $this->classify_directory( $directory, $profiles ) : 'generic';
            $profile     = isset( $profiles[ $profile_key ] ) ? $profiles[ $profile_key ] : ( $profiles['generic'] ?? [] );
            $items       = [];
            $seen        = [];

            foreach ( $profile['products'] ?? [] as $position => $candidate ) {
                $candidate_data = is_array( $candidate ) ? $candidate : [ 'slug' => $candidate ];
                $product_slug   = isset( $candidate_data['slug'] ) ? sanitize_key( (string) $candidate_data['slug'] ) : '';

                if ( ! $product_slug || isset( $seen[ $product_slug ] ) ) {
                    continue;
                }

                $item = $this->prepare_product( $product_slug, $candidate_data, $position );
                if ( empty( $item ) ) {
                    continue;
                }

                $seen[ $product_slug ] = true;
                $items[]               = $item;
            }

            return [
                'id'          => (string) ( $directory['id'] ?? 'generic' ),
                'name'        => sanitize_text_field( (string) ( $directory['name'] ?? __( 'your directory', 'directorist' ) ) ),
                'profile'     => $profile_key,
                'known'       => 'generic' !== $profile_key,
                'description' => sanitize_text_field( (string) ( $profile['description'] ?? '' ) ),
                'items'       => $items,
            ];
        }

        /**
         * Match a directory term to a canonical profile.
         *
         * @param array $directory Directory identity.
         * @param array $profiles  Recommendation registry.
         *
         * @return string
         */
        private function classify_directory( array $directory, array $profiles ) {
            $haystack = $this->normalize_phrase(
                (string) ( $directory['name'] ?? '' ) . ' ' . (string) ( $directory['slug'] ?? '' )
            );

            foreach ( $profiles as $profile_key => $profile ) {
                if ( 'generic' === $profile_key || empty( $profile['aliases'] ) || ! is_array( $profile['aliases'] ) ) {
                    continue;
                }

                foreach ( $profile['aliases'] as $alias ) {
                    $normalized_alias = $this->normalize_phrase( $alias );

                    if ( $normalized_alias && false !== strpos( ' ' . $haystack . ' ', ' ' . $normalized_alias . ' ' ) ) {
                        return (string) $profile_key;
                    }
                }
            }

            return 'generic';
        }

        /**
         * Normalize a phrase for conservative whole-phrase matching.
         *
         * @param mixed $value Phrase.
         *
         * @return string
         */
        private function normalize_phrase( $value ) {
            $value = strtolower( remove_accents( wp_strip_all_tags( (string) $value ) ) );
            $value = preg_replace( '/[^a-z0-9]+/', ' ', $value );

            return trim( preg_replace( '/\s+/', ' ', (string) $value ) );
        }

        /**
         * Prepare one catalog product with its canonical local state.
         *
         * @param string $product_slug  Product slug.
         * @param array  $candidate     Recommendation metadata.
         * @param int    $position      Default position.
         *
         * @return array
         */
        private function prepare_product( $product_slug, array $candidate, $position ) {
            $resolved_slug = $this->resolve_product_slug( $product_slug );
            $product       = isset( $this->products[ $resolved_slug ] ) && is_array( $this->products[ $resolved_slug ] )
                ? $this->products[ $resolved_slug ]
                : [];

            if ( empty( $product ) ) {
                return [];
            }

            $installed  = $this->find_installed_product( $resolved_slug );
            $entitlement = $this->find_entitlement( $resolved_slug );
            $status     = 'marketplace';
            $label      = __( 'Available', 'directorist' );
            $action     = [];

            if ( ! empty( $installed ) ) {
                if ( ! empty( $installed['active'] ) ) {
                    $status = 'active';
                    $label  = __( 'Active', 'directorist' );
                } else {
                    $status = 'installed';
                    $label  = __( 'Installed', 'directorist' );
                    $action = [
                        'label' => __( 'Activate', 'directorist' ),
                        'class' => 'directorist-te-btn directorist-te-btn--soft plugin-active-btn',
                        'attrs' => [
                            'data-type' => 'plugin',
                            'data-key'  => $installed['base'],
                        ],
                        'icon'  => 'la la-check',
                    ];
                }
            } elseif ( $entitlement ) {
                $status = 'not-installed';
                $label  = __( 'Not installed', 'directorist' );
                $action = [
                    'label' => $this->is_beta ? __( 'Install Beta', 'directorist' ) : __( 'Install', 'directorist' ),
                    'class' => 'directorist-te-btn directorist-te-btn--soft file-install-btn',
                    'attrs' => [
                        'data-type' => 'plugin',
                        'data-key'  => $entitlement,
                    ],
                    'icon'  => 'la la-download',
                ];
            } elseif ( ! empty( $product['link'] ) ) {
                $action = [
                    'label'    => __( 'View details', 'directorist' ),
                    'href'     => $product['link'],
                    'class'    => 'directorist-te-btn directorist-te-btn--soft',
                    'external' => true,
                    'icon'     => 'la la-external-link',
                ];
            }

            $reason = ! empty( $candidate['reason'] )
                ? sanitize_text_field( (string) $candidate['reason'] )
                : wp_trim_words( wp_strip_all_tags( (string) ( $product['description'] ?? '' ) ), 20, '...' );

            return [
                'slug'     => $resolved_slug,
                'name'     => sanitize_text_field( (string) ( $product['name'] ?? $resolved_slug ) ),
                'reason'   => $reason,
                'image'    => ! empty( $product['thumbnail'] ) ? esc_url_raw( (string) $product['thumbnail'] ) : '',
                'status'   => $status,
                'label'    => $label,
                'action'   => $action,
                'position' => isset( $candidate['priority'] ) ? (int) $candidate['priority'] : ( 1000 - (int) $position ),
            ];
        }

        /**
         * Resolve a current product slug through the legacy alias map.
         *
         * @param string $product_slug Product slug.
         *
         * @return string
         */
        private function resolve_product_slug( $product_slug ) {
            if ( isset( $this->products[ $product_slug ] ) ) {
                return $product_slug;
            }

            if ( ! empty( $this->aliases[ $product_slug ] ) && isset( $this->products[ $this->aliases[ $product_slug ] ] ) ) {
                return (string) $this->aliases[ $product_slug ];
            }

            $alias_key = array_search( $product_slug, $this->aliases, true );

            return $alias_key && isset( $this->products[ $alias_key ] ) ? (string) $alias_key : $product_slug;
        }

        /**
         * Find an installed product by folder slug or alias.
         *
         * @param string $product_slug Product slug.
         *
         * @return array
         */
        private function find_installed_product( $product_slug ) {
            $installed_extensions = isset( $this->overview['installed_extension_list'] ) && is_array( $this->overview['installed_extension_list'] )
                ? $this->overview['installed_extension_list']
                : [];
            $candidate_slugs      = $this->get_candidate_slugs( $product_slug );

            foreach ( $installed_extensions as $plugin_base => $plugin_data ) {
                $folder_slug = preg_replace( '/\/.+/', '', (string) $plugin_base );

                if ( in_array( $folder_slug, $candidate_slugs, true ) ) {
                    return [
                        'base'   => (string) $plugin_base,
                        'active' => is_plugin_active( $plugin_base ),
                    ];
                }
            }

            return [];
        }

        /**
         * Find the canonical entitlement key for an uninstalled product.
         *
         * @param string $product_slug Product slug.
         *
         * @return string
         */
        private function find_entitlement( $product_slug ) {
            $entitlements    = isset( $this->overview['extensions_available_in_subscriptions'] ) && is_array( $this->overview['extensions_available_in_subscriptions'] )
                ? $this->overview['extensions_available_in_subscriptions']
                : [];
            $candidate_slugs = $this->get_candidate_slugs( $product_slug );

            foreach ( $entitlements as $entitlement_key => $entitlement ) {
                $folder_slug = preg_replace( '/\/.+/', '', (string) $entitlement_key );

                if ( in_array( $folder_slug, $candidate_slugs, true ) ) {
                    return (string) $entitlement_key;
                }
            }

            return '';
        }

        /**
         * Get the current and legacy slugs that may represent one product.
         *
         * @param string $product_slug Product slug.
         *
         * @return array
         */
        private function get_candidate_slugs( $product_slug ) {
            $candidate_slugs = [ $product_slug ];

            if ( ! empty( $this->aliases[ $product_slug ] ) ) {
                $candidate_slugs[] = (string) $this->aliases[ $product_slug ];
            }

            $legacy_slug = array_search( $product_slug, $this->aliases, true );
            if ( $legacy_slug ) {
                $candidate_slugs[] = (string) $legacy_slug;
            }

            return array_values( array_unique( array_filter( $candidate_slugs ) ) );
        }
    }
}
