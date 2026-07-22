<?php
/**
 * Themes & Extensions admin page.
 *
 * @package Directorist
 */

$extensions            = ! empty( $args['extension_list'] ) && is_array( $args['extension_list'] ) ? $args['extension_list'] : [];
$themes                = ! empty( $args['theme_list'] ) && is_array( $args['theme_list'] ) ? $args['theme_list'] : [];
$outdated_plugins      = ! empty( $args['outdated_plugin_list'] ) && is_array( $args['outdated_plugin_list'] ) ? $args['outdated_plugin_list'] : [];
$outdated_keys         = array_keys( $outdated_plugins );
$is_logged_in          = ! empty( $args['is_logged_in'] );
$is_beta               = ! empty( $args['is_beta'] );
$installed_extensions  = ! empty( $args['installed_extension_list'] ) && is_array( $args['installed_extension_list'] ) ? $args['installed_extension_list'] : [];
$installed_themes      = ! empty( $args['installed_theme_list'] ) && is_array( $args['installed_theme_list'] ) ? $args['installed_theme_list'] : [];
$has_local_products    = ! empty( $installed_extensions ) || ! empty( $installed_themes );
$connect_title         = $has_local_products
    ? __( 'Connect to manage installed Directorist products', 'directorist' )
    : __( 'Connect to manage subscribed products', 'directorist' );
$connect_description   = $has_local_products
    ? __( 'We found Directorist products installed on this site. They will keep working. Connect your Directorist account to verify subscriptions, install new products, receive updates, and manage license-backed product actions.', 'directorist' )
    : __( 'You can browse available products now. Connect your account when you need subscription installs, updates, and license-backed product management.', 'directorist' );
$rows                  = [];
$seen_rows             = [];

$get_extension_product = static function( $extension_key ) use ( $extensions, $args ) {
    $extension_key = is_string( $extension_key ) ? $extension_key : '';
    $alias_key     = $args['ATBDP_Extensions']->get_extension_alias_key( $extension_key );

    if ( isset( $extensions[ $extension_key ] ) ) {
        return $extensions[ $extension_key ];
    }

    if ( ! empty( $alias_key ) && isset( $extensions[ $alias_key ] ) ) {
        return $extensions[ $alias_key ];
    }

    return [];
};

$get_product_badges = static function( $product ) {
    if ( ! is_array( $product ) ) {
        return [];
    }

    $normalize_badge = static function( $badge_value ) {
        $label      = '';
        $type       = '';
        $expires_at = '';

        if ( is_array( $badge_value ) ) {
            $label      = $badge_value['label'] ?? $badge_value['name'] ?? '';
            $type       = $badge_value['type'] ?? '';
            $expires_at = $badge_value['expires_at'] ?? '';

            if ( is_scalar( $type ) && '' === trim( (string) $label ) && '' !== trim( (string) $type ) ) {
                $label = ucwords( str_replace( [ '-', '_' ], ' ', (string) $type ) );
            }
        } elseif ( is_scalar( $badge_value ) ) {
            $label = $badge_value;
        }

        if ( ! is_scalar( $label ) || '' === trim( (string) $label ) ) {
            return null;
        }

        $label = trim( (string) $label );
        $type  = is_scalar( $type ) && '' !== trim( (string) $type ) ? sanitize_title( (string) $type ) : sanitize_title( $label );

        if ( is_scalar( $expires_at ) && '' !== trim( (string) $expires_at ) ) {
            $expires_timestamp = strtotime( (string) $expires_at );

            if ( false !== $expires_timestamp && $expires_timestamp < time() ) {
                return null;
            }
        }

        return [
            'type'  => $type ?: 'default',
            'label' => $label,
        ];
    };

    $badges      = [];
    $seen_badges = [];
    foreach ( [ 'badge', 'badges', 'product_badge', 'product_badges', 'product_status', 'release_status' ] as $badge_key ) {
        if ( empty( $product[ $badge_key ] ) ) {
            continue;
        }

        $raw_badges = $product[ $badge_key ];
        $is_single  = is_array( $raw_badges ) && array_intersect( [ 'label', 'name', 'type', 'expires_at' ], array_keys( $raw_badges ) );

        $badge_values = is_array( $raw_badges ) && ! $is_single ? $raw_badges : [ $raw_badges ];
        foreach ( $badge_values as $badge_value ) {
            $badge = $normalize_badge( $badge_value );

            if ( empty( $badge ) ) {
                continue;
            }

            $badge_id = strtolower( $badge['type'] . '|' . $badge['label'] );

            if ( isset( $seen_badges[ $badge_id ] ) ) {
                continue;
            }

            $seen_badges[ $badge_id ] = true;
            $badges[]                 = $badge;
        }
    }

    return $badges;
};

$normalize_search_text = static function( $value ) {
    if ( ! is_scalar( $value ) ) {
        return '';
    }

    $value = strtolower( remove_accents( wp_strip_all_tags( (string) $value ) ) );
    $value = preg_replace( '/[-_]+/', ' ', $value );
    $value = preg_replace( '/\s+/', ' ', $value );

    return trim( (string) $value );
};

$get_badge_search_terms = static function( $badges ) use ( $normalize_search_text ) {
    if ( empty( $badges ) || ! is_array( $badges ) ) {
        return [];
    }

    $terms = [];
    foreach ( $badges as $badge ) {
        $badge_label = is_array( $badge ) ? ( $badge['label'] ?? '' ) : $badge;
        $badge_type  = is_array( $badge ) ? ( $badge['type'] ?? '' ) : '';

        if ( is_scalar( $badge_label ) && '' !== trim( (string) $badge_label ) ) {
            $terms[] = $normalize_search_text( $badge_label );
        }

        if ( is_scalar( $badge_type ) && '' !== trim( (string) $badge_type ) ) {
            $terms[] = $normalize_search_text( $badge_type );
        }
    }

    return array_values( array_unique( array_filter( $terms ) ) );
};

$get_row_search_index = static function( $row ) use ( $normalize_search_text, $get_badge_search_terms ) {
    $search_text = $normalize_search_text(
        implode(
            ' ',
            [
                $row['name'] ?? '',
                $row['description'] ?? '',
                $row['typeLabel'] ?? '',
            ]
        )
    );
    $badge_terms = $get_badge_search_terms( $row['badges'] ?? [] );

    return [
        'text'        => $search_text,
        'badge_text'  => implode( ' ', $badge_terms ),
        'badge_terms' => wp_json_encode( $badge_terms ) ?: '[]',
    ];
};

$get_link = static function( $product ) {
    if ( empty( $product['link'] ) ) {
        return '#';
    }

    return ATBDP_Upgrade::promo_link( $product['link'] );
};

$get_image = static function( $product ) {
    return ! empty( $product['thumbnail'] ) ? $product['thumbnail'] : DIRECTORIST_ASSETS . 'images/no-image.png';
};

$get_details_action = static function( $product ) use ( $get_link ) {
    return [
        'label'    => __( 'View Details', 'directorist' ),
        'href'     => $get_link( $product ),
        'class'    => 'directorist-te-btn directorist-te-btn--ghost directorist-te-btn--details',
        'external' => true,
    ];
};

$get_demo_action = static function( $product ) {
    if ( empty( $product['demo_link'] ) ) {
        return null;
    }

    return [
        'label'    => __( 'Demo', 'directorist' ),
        'href'     => $product['demo_link'],
        'class'    => 'directorist-te-btn directorist-te-btn--ghost directorist-te-btn--demo',
        'external' => true,
    ];
};

$add_row = static function( $row ) use ( &$rows, &$seen_rows ) {
    if ( empty( $row['key'] ) || isset( $seen_rows[ $row['key'] ] ) ) {
        return;
    }

    $seen_rows[ $row['key'] ] = true;
    $rows[]                  = $row;
};

$render_attrs = static function( $attrs ) {
    if ( empty( $attrs ) || ! is_array( $attrs ) ) {
        return;
    }

    foreach ( $attrs as $name => $value ) {
        printf( ' %s="%s"', esc_attr( $name ), esc_attr( $value ) );
    }
};

$get_bulk_control = static function( $id, $item, $type, $actions, $class = '' ) {
    $item    = is_scalar( $item ) ? (string) $item : '';
    $type    = is_scalar( $type ) ? (string) $type : '';
    $actions = array_values( array_unique( array_filter( array_map( 'sanitize_key', (array) $actions ) ) ) );

    if ( '' === $item || empty( $actions ) || ! in_array( $type, [ 'plugin', 'theme' ], true ) ) {
        return null;
    }

    return [
        'id'    => $id,
        'name'  => 'directorist_te_bulk[]',
        'class' => trim( 'directorist-te-select-checkbox directorist-te-check ' . $class ),
        'attrs' => [
            'value'             => $item,
            'data-bulk-item'    => $item,
            'data-bulk-type'    => $type,
            'data-bulk-actions' => implode( ' ', $actions ),
        ],
    ];
};

$get_update_version = static function( $update ) {
    if ( is_object( $update ) ) {
        $update = get_object_vars( $update );
    }

    if ( ! is_array( $update ) ) {
        return '';
    }

    foreach ( [ 'new_version', 'version' ] as $version_key ) {
        if ( ! empty( $update[ $version_key ] ) && is_scalar( $update[ $version_key ] ) ) {
            return ltrim( trim( (string) $update[ $version_key ] ), 'vV' );
        }
    }

    return '';
};

$get_update_status_label = static function( $version ) {
    if ( is_scalar( $version ) && '' !== trim( (string) $version ) ) {
        /* translators: %s: available product version. */
        return sprintf( __( 'v%s available', 'directorist' ), ltrim( trim( (string) $version ), 'vV' ) );
    }

    return __( 'Update available', 'directorist' );
};

$render_action = static function( $action ) use ( $render_attrs ) {
    if ( empty( $action['label'] ) ) {
        return;
    }

    $href   = ! empty( $action['href'] ) ? $action['href'] : '#';
    $class  = ! empty( $action['class'] ) ? $action['class'] : 'directorist-te-btn';
    $target = ! empty( $action['external'] ) ? ' target="_blank" rel="noopener noreferrer"' : '';
    ?>
    <a href="<?php echo esc_url( $href ); ?>" class="<?php echo esc_attr( $class ); ?>"<?php echo $target; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php $render_attrs( $action['attrs'] ?? [] ); ?>>
        <?php if ( ! empty( $action['icon'] ) ) : ?>
            <i class="<?php echo esc_attr( $action['icon'] ); ?>" aria-hidden="true"></i>
        <?php endif; ?>
        <?php echo esc_html( $action['label'] ); ?>
    </a>
    <?php
};

$render_status = static function( $label, $status ) {
    if ( empty( $label ) ) {
        return;
    }

    /* translators: %s: product status label. */
    $status_title = sprintf( __( 'Status: %s', 'directorist' ), $label );
    ?>
    <span class="directorist-te-status directorist-te-status--<?php echo esc_attr( $status ); ?>" title="<?php echo esc_attr( $status_title ); ?>"><?php echo esc_html( $label ); ?></span>
    <?php
};

$required_lookup = [];
if ( ! empty( $args['required_extensions_list'] ) && is_array( $args['required_extensions_list'] ) ) {
    foreach ( $args['required_extensions_list'] as $extension_key => $required_extension ) {
        $extension_alias = $args['ATBDP_Extensions']->get_extension_alias_key( $extension_key );
        $required_base   = ! empty( $required_extension['base'] ) ? $required_extension['base'] : "{$extension_key}/{$extension_key}.php";

        foreach ( array_filter( [ $extension_key, $extension_alias, $required_base, preg_replace( '/\/.+/', '', $required_base ) ] ) as $required_lookup_key ) {
            $required_lookup[ $required_lookup_key ] = $required_extension;
        }
    }
}

if ( $is_logged_in && ! empty( $args['installed_extension_list'] ) && is_array( $args['installed_extension_list'] ) ) {
    foreach ( $args['installed_extension_list'] as $extension_base => $extension ) {
        $extension_key = preg_replace( '/\/.+/', '', $extension_base );
        $product       = $get_extension_product( $extension_key );
        $name          = ! empty( $extension['Name'] ) ? $extension['Name'] : ( $product['name'] ?? $extension_key );
        $version       = ! empty( $extension['Version'] ) ? $extension['Version'] : '';
        $description   = $product['description'] ?? ( $extension['Description'] ?? '' );
        $has_update    = in_array( $extension_base, $outdated_keys, true );
        $update_version = $get_update_version( $outdated_plugins[ $extension_base ] ?? null );
        $is_active     = is_plugin_active( $extension_base );
        $is_required   = isset( $required_lookup[ $extension_base ] ) || isset( $required_lookup[ $extension_key ] );
        $status        = $has_update ? 'update installed active' : ( $is_active ? 'active installed' : 'installed' );
        $status_label  = $has_update ? $get_update_status_label( $update_version ) : ( $is_active ? __( 'Active', 'directorist' ) : __( 'Installed', 'directorist' ) );
        $primary       = $has_update
            ? [
                'label' => $is_beta ? __( 'Update Beta', 'directorist' ) : __( 'Update', 'directorist' ),
                'class' => 'directorist-te-btn directorist-te-btn--primary ext-update-btn',
                'attrs' => [ 'data-key' => $extension_base ],
                'icon'  => 'la la-refresh',
            ]
            : (
                $is_active
                    ? [
                        'label' => __( 'Settings', 'directorist' ),
                        'href'  => $args['settings_url'],
                        'class' => 'directorist-te-btn directorist-te-btn--secondary',
                        'icon'  => 'la la-settings',
                    ]
                    : [
                        'label' => __( 'Activate', 'directorist' ),
                        'class' => 'directorist-te-btn directorist-te-btn--primary plugin-active-btn',
                        'attrs' => [
                            'data-type' => 'plugin',
                            'data-key'  => $extension_base,
                        ],
                        'icon'  => 'la la-check',
                    ]
            );

        $menu = [];

        if ( $has_update && $is_active ) {
            $menu[] = [
                'label' => __( 'Settings', 'directorist' ),
                'href'  => $args['settings_url'],
                'class' => 'directorist-te-menu-link',
            ];
        }

        if ( $is_active ) {
            $menu[] = [
                'label' => __( 'Deactivate', 'directorist' ),
                'class' => 'directorist-te-menu-link directorist-te-single-plugin-task',
                'attrs' => [
                    'data-task'   => 'deactivate',
                    'data-target' => $extension_base,
                ],
            ];
        } else {
            $menu[] = [
                'label' => __( 'Delete plugin', 'directorist' ),
                'class' => 'directorist-te-menu-link directorist-te-menu-link--danger ext-action-uninstall',
                'attrs' => [ 'data-target' => $extension_base ],
            ];
        }

        $badges = $get_product_badges( $product );
        if ( $is_required ) {
            $badges[] = __( 'Required', 'directorist' );
        }

        $bulk_actions = [];
        if ( $has_update ) {
            $bulk_actions[] = 'update';
        }
        $bulk_actions[] = $is_active ? 'deactivate' : 'activate';
        if ( ! $is_active ) {
            $bulk_actions[] = 'uninstall';
        }

        $add_row(
            [
                'key'         => 'extension-installed-' . $extension_base,
                'type'        => 'extension',
                'status'      => $status,
                'name'        => $name,
                'version'     => $version,
                'description' => $description,
                'image'       => $get_image( $product ),
                'badges'      => $badges,
                'statusLabel' => $status_label,
                'primary'     => $primary,
                'menu'        => $menu,
                'bulk'        => $get_bulk_control( $extension_base, $extension_base, 'plugin', $bulk_actions, 'extension-name-checkbox' ),
            ]
        );
    }
}

if ( $is_logged_in && ! empty( $args['current_active_theme_info'] ) && is_array( $args['current_active_theme_info'] ) ) {
    $active_theme = $args['current_active_theme_info'];
    $theme_key    = ! empty( $active_theme['stylesheet'] ) ? $active_theme['stylesheet'] : sanitize_title( $active_theme['name'] ?? '' );
    $product      = $themes[ $theme_key ] ?? [];
    $has_update   = ! empty( $active_theme['has_update'] );
    $update_version = $get_update_version( $active_theme );
    $is_directorist_active_theme = ! empty( $product );

    $add_row(
        [
            'key'         => 'theme-active-' . $theme_key,
            'type'        => 'theme',
            'typeLabel'   => $is_directorist_active_theme ? __( 'Directorist theme', 'directorist' ) : __( 'WordPress theme', 'directorist' ),
            'typeClass'   => $is_directorist_active_theme ? 'directorist-theme' : 'wordpress-theme',
            'status'      => $has_update ? 'update active installed' : 'active installed',
            'name'        => $active_theme['name'] ?? __( 'Active Theme', 'directorist' ),
            'version'     => $active_theme['version'] ?? '',
            'description' => $product['description'] ?? __( 'Currently active WordPress theme for this site.', 'directorist' ),
            'image'       => ! empty( $active_theme['thumbnail'] ) ? $active_theme['thumbnail'] : $get_image( $product ),
            'badges'      => array_merge( $get_product_badges( $product ), [ __( 'Active site theme', 'directorist' ) ] ),
            'statusLabel' => $has_update ? $get_update_status_label( $update_version ) : __( 'Active', 'directorist' ),
            'primary'     => $has_update
                ? [
                    'label' => $is_beta ? __( 'Update Beta', 'directorist' ) : __( 'Update', 'directorist' ),
                    'class' => 'directorist-te-btn directorist-te-btn--primary theme-update-btn',
                    'attrs' => [ 'data-target' => $active_theme['stylesheet'] ?? '' ],
                    'icon'  => 'la la-refresh',
                ]
                : [
                    'label'    => __( 'Customize', 'directorist' ),
                    'href'     => $active_theme['customizer_link'] ?? '#',
                    'class'    => 'directorist-te-btn directorist-te-btn--secondary',
                    'external' => true,
                    'icon'     => 'la la-sliders',
                ],
            'menu'        => [],
            'bulk'        => $has_update ? $get_bulk_control( 'directorist-te-theme-update-' . sanitize_html_class( $theme_key ), $active_theme['stylesheet'] ?? $theme_key, 'theme', [ 'update' ] ) : null,
        ]
    );
}

if ( $is_logged_in && ! empty( $args['extensions_available_in_subscriptions'] ) && is_array( $args['extensions_available_in_subscriptions'] ) ) {
    foreach ( $args['extensions_available_in_subscriptions'] as $extension_key => $extension ) {
        $extension   = is_array( $extension ) ? $extension : [];
        $product     = array_merge( $get_extension_product( $extension_key ), $extension );
        $name        = $product['title'] ?? $product['name'] ?? $extension_key;
        $description = $product['description'] ?? '';
        $is_purchased = ! empty( $extension['purchased'] );

        $add_row(
            [
                'key'         => 'extension-subscription-' . $extension_key,
                'type'        => 'extension',
                'status'      => 'not-installed',
                'name'        => $name,
                'description' => $description,
                'image'       => $get_image( $product ),
                'badges'      => $get_product_badges( $product ),
                'statusLabel' => __( 'Not installed', 'directorist' ),
                'primary'     => [
                    'label' => $is_beta ? __( 'Install Beta', 'directorist' ) : __( 'Install', 'directorist' ),
                    'class' => 'directorist-te-btn directorist-te-btn--primary file-install-btn',
                    'attrs' => [
                        'data-type' => 'plugin',
                        'data-key'  => $extension_key,
                    ],
                    'icon'  => 'la la-download',
                ],
                'details'     => $is_purchased ? $get_details_action( $product ) : null,
                'menu'        => [],
                'bulk'        => $get_bulk_control( 'directorist-te-install-' . sanitize_html_class( $extension_key ), $extension_key, 'plugin', [ 'install' ] ),
            ]
        );
    }
}

if ( $is_logged_in && ! empty( $args['required_extensions_list'] ) && is_array( $args['required_extensions_list'] ) ) {
    foreach ( $args['required_extensions_list'] as $extension_key => $required_extension ) {
        $required_base = ! empty( $required_extension['base'] ) ? $required_extension['base'] : "{$extension_key}/{$extension_key}.php";

        if ( isset( $seen_rows[ 'extension-installed-' . $required_base ] ) || ! empty( $required_extension['installed'] ) ) {
            continue;
        }

        $product     = $get_extension_product( $extension_key );
        $name        = $product['name'] ?? $extension_key;
        $description = $product['description'] ?? '';
        $is_purchased = ! empty( $required_extension['purchased'] );

        $add_row(
            [
                'key'         => 'extension-required-' . $extension_key,
                'type'        => 'extension',
                'status'      => $is_purchased ? 'required not-installed' : 'marketplace required not-installed',
                'name'        => $name,
                'description' => $description,
                'image'       => $get_image( $product ),
                'badges'      => array_merge( $get_product_badges( $product ), [ __( 'Required', 'directorist' ) ] ),
                'statusLabel' => $is_purchased ? __( 'Required', 'directorist' ) : __( 'Required purchase', 'directorist' ),
                'primary'     => $is_purchased
                    ? [
                        'label' => $is_beta ? __( 'Install Beta', 'directorist' ) : __( 'Install', 'directorist' ),
                        'class' => 'directorist-te-btn directorist-te-btn--primary file-install-btn',
                        'attrs' => [
                            'data-type' => 'plugin',
                            'data-key'  => $extension_key,
                        ],
                        'icon'  => 'la la-download',
                    ]
                    : [
                        'label'    => __( 'Get It Now', 'directorist' ),
                        'href'     => $get_link( $product ),
                        'class'    => 'directorist-te-btn directorist-te-btn--secondary',
                        'external' => true,
                    ],
                'details'     => $is_purchased ? $get_details_action( $product ) : null,
                'menu'        => [],
                'bulk'        => $is_purchased ? $get_bulk_control( 'directorist-te-required-install-' . sanitize_html_class( $extension_key ), $extension_key, 'plugin', [ 'install' ] ) : null,
            ]
        );
    }
}

if ( $is_logged_in && ! empty( $args['themes_available_in_subscriptions'] ) && is_array( $args['themes_available_in_subscriptions'] ) ) {
    foreach ( $args['themes_available_in_subscriptions'] as $theme_key => $theme ) {
        $product      = array_merge( $themes[ $theme_key ] ?? [], is_array( $theme ) ? $theme : [] );
        $is_installed = ! empty( $product['is_installed'] );

        $add_row(
            [
                'key'         => 'theme-subscription-' . $theme_key,
                'type'        => 'theme',
                'typeLabel'   => __( 'Directorist theme', 'directorist' ),
                'typeClass'   => 'directorist-theme',
                'status'      => $is_installed ? 'installed' : 'not-installed',
                'name'        => $product['name'] ?? $theme_key,
                'version'     => $product['version'] ?? '',
                'description' => $product['description'] ?? '',
                'image'       => $get_image( $product ),
                'badges'      => $get_product_badges( $product ),
                'statusLabel' => $is_installed ? __( 'Installed', 'directorist' ) : __( 'Not installed', 'directorist' ),
                'primary'     => $is_installed
                    ? [
                        'label' => __( 'Activate', 'directorist' ),
                        'class' => 'directorist-te-btn directorist-te-btn--primary theme-activate-btn',
                        'attrs' => [ 'data-target' => $product['stylesheet'] ?? $theme_key ],
                        'icon'  => 'la la-check',
                    ]
                    : [
                        'label' => $is_beta ? __( 'Install Beta', 'directorist' ) : __( 'Install', 'directorist' ),
                        'class' => 'directorist-te-btn directorist-te-btn--primary file-install-btn',
                        'attrs' => [
                            'data-type' => 'theme',
                            'data-key'  => $theme_key,
                        ],
                        'icon'  => 'la la-download',
                    ],
                'demo'        => $get_demo_action( $product ),
                'details'     => $get_details_action( $product ),
                'menu'        => array_filter(
                    [
                        ! empty( $product['customizer_link'] )
                            ? [
                                'label'    => __( 'Live Preview', 'directorist' ),
                                'href'     => $product['customizer_link'],
                                'class'    => 'directorist-te-menu-link',
                                'external' => true,
                            ]
                            : null,
                    ]
                ),
                'bulk'        => ! $is_installed ? $get_bulk_control( 'directorist-te-theme-install-' . sanitize_html_class( $theme_key ), $theme_key, 'theme', [ 'install' ] ) : null,
            ]
        );
    }
}

$promo_extensions = ! empty( $args['extensions_promo_list'] ) && is_array( $args['extensions_promo_list'] ) ? $args['extensions_promo_list'] : [];
if ( ! $is_logged_in && empty( $promo_extensions ) && ! empty( $extensions ) ) {
    $promo_extensions = $extensions;
}

foreach ( $promo_extensions as $extension_key => $extension ) {
    $product = is_array( $extension ) ? $extension : [];
    $add_row(
        [
            'key'         => 'extension-promo-' . $extension_key,
            'type'        => 'extension',
            'status'      => 'marketplace not-installed',
            'name'        => $product['name'] ?? $extension_key,
            'description' => $product['description'] ?? '',
            'image'       => $get_image( $product ),
            'badges'      => $get_product_badges( $product ),
            'statusLabel' => __( 'Marketplace', 'directorist' ),
            'primary'     => $get_details_action( $product ),
            'details'     => null,
            'menu'        => [],
        ]
    );
}

$promo_themes = ! empty( $args['themes_promo_list'] ) && is_array( $args['themes_promo_list'] ) ? $args['themes_promo_list'] : [];
if ( ! $is_logged_in && empty( $promo_themes ) && ! empty( $themes ) ) {
    $promo_themes = $themes;
}

foreach ( $promo_themes as $theme_key => $theme ) {
    $product = is_array( $theme ) ? $theme : [];
    $add_row(
        [
            'key'         => 'theme-promo-' . $theme_key,
            'type'        => 'theme',
            'typeLabel'   => __( 'Directorist theme', 'directorist' ),
            'typeClass'   => 'directorist-theme',
            'status'      => 'marketplace not-installed',
            'name'        => $product['name'] ?? $theme_key,
            'description' => $product['description'] ?? '',
            'image'       => $get_image( $product ),
            'badges'      => $get_product_badges( $product ),
            'statusLabel' => __( 'Marketplace', 'directorist' ),
            'primary'     => $get_details_action( $product ),
            'demo'        => $get_demo_action( $product ),
            'details'     => null,
            'menu'        => [],
        ]
    );
}

$total_updates = (int) ( $args['total_outdated_extensions'] ?? 0 ) + (int) ( $args['total_outdated_themes'] ?? 0 );
$total_rows    = count( $rows );
$extension_rows = count(
    array_filter(
        $rows,
        static function( $row ) {
            return ! empty( $row['type'] ) && 'extension' === $row['type'];
        }
    )
);
$theme_rows = count(
    array_filter(
        $rows,
        static function( $row ) {
            return ! empty( $row['type'] ) && 'theme' === $row['type'];
        }
    )
);
$update_rows = count(
    array_filter(
        $rows,
        static function( $row ) {
            return ! empty( $row['status'] ) && false !== strpos( $row['status'], 'update' );
        }
    )
);
?>

<div id="directorist" class="wrap atbd_wrapper directorist-te-page <?php echo esc_attr( $is_logged_in ? 'directorist-te-page--connected' : 'directorist-te-page--disconnected' ); ?>">
    <div id="my-themes-extensions" class="atbdp-tab-content active">
        <div class="directorist-te-shell">
            <header class="directorist-te-header">
                <div class="directorist-te-brand">
                    <span class="directorist-te-brand__mark" aria-hidden="true">
                        <img src="<?php echo esc_url( DIRECTORIST_ASSETS . 'images/directorist-logo-solid.svg' ); ?>" alt="">
                    </span>
                    <span class="directorist-te-brand__name"><?php esc_html_e( 'Directorist', 'directorist' ); ?></span>
                </div>
                <?php if ( $is_logged_in ) : ?>
                    <nav class="directorist-te-nav" aria-label="<?php esc_attr_e( 'Directorist sections', 'directorist' ); ?>">
                        <button type="button" data-directorist-te-view-target="dashboard" aria-controls="directorist-te-dashboard-view"><?php esc_html_e( 'Dashboard', 'directorist' ); ?></button>
                        <button type="button" class="active" data-directorist-te-view-target="addons" aria-controls="directorist-te-addons-view" aria-current="page"><?php esc_html_e( 'Add-ons', 'directorist' ); ?></button>
                    </nav>
                    <div class="directorist-te-top-right">
                        <nav class="directorist-te-resource-links" aria-label="<?php esc_attr_e( 'Directorist resources', 'directorist' ); ?>">
                            <a href="https://directorist.com/documentation/directorist/" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Docs, opens in a new tab', 'directorist' ); ?>"><?php esc_html_e( 'Docs', 'directorist' ); ?></a>
                            <a href="https://www.youtube.com/@wpdirectorist" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Tutorials, opens in a new tab', 'directorist' ); ?>"><?php esc_html_e( 'Tutorials', 'directorist' ); ?></a>
                            <a href="https://directorist.com/contact/" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Support, opens in a new tab', 'directorist' ); ?>"><?php esc_html_e( 'Support', 'directorist' ); ?></a>
                        </nav>
                        <label class="directorist-te-top-search">
                            <span class="screen-reader-text"><?php esc_html_e( 'Search Directorist', 'directorist' ); ?></span>
                            <i class="la la-search" aria-hidden="true"></i>
                            <input type="search" placeholder="<?php esc_attr_e( 'Search...', 'directorist' ); ?>">
                        </label>
                        <span class="directorist-te-header-icon" aria-hidden="true"><i class="la la-bell"></i><span></span></span>
                        <div class="directorist-te-account-menu">
                            <button
                                type="button"
                                class="directorist-te-avatar"
                                aria-haspopup="true"
                                aria-expanded="false"
                                aria-controls="directorist-te-account-dropdown"
                            >
                                <?php echo get_avatar( get_current_user_id(), 32 ); ?>
                                <span class="screen-reader-text"><?php esc_html_e( 'Open Directorist account menu', 'directorist' ); ?></span>
                                <i class="la la-angle-down" aria-hidden="true"></i>
                            </button>
                            <div class="directorist-te-account-dropdown" id="directorist-te-account-dropdown" hidden aria-hidden="true">
                                <div class="directorist-te-account-summary" aria-label="<?php esc_attr_e( 'Directorist account product summary', 'directorist' ); ?>">
                                    <div>
                                        <strong><?php echo esc_html( $args['total_active_extensions'] ?? 0 ); ?></strong>
                                        <span><?php esc_html_e( 'Active extensions', 'directorist' ); ?></span>
                                    </div>
                                    <div>
                                        <strong><?php echo esc_html( $args['total_available_extensions'] ?? 0 ); ?></strong>
                                        <span><?php esc_html_e( 'Account extensions', 'directorist' ); ?></span>
                                    </div>
                                    <div>
                                        <strong><?php echo esc_html( $args['total_available_themes'] ?? 0 ); ?></strong>
                                        <span><?php esc_html_e( 'Account themes', 'directorist' ); ?></span>
                                    </div>
                                </div>
                                <div class="directorist-te-account-dropdown__actions">
                                    <div class="et-auth-section directorist-te-refresh-panel" hidden aria-hidden="true">
                                        <form id="purchase-refresh-form" action="#" method="post">
                                            <div class="directorist-te-refresh-form">
                                                <input type="password" class="atbdp-form-control" placeholder="<?php esc_attr_e( 'Confirm password', 'directorist' ); ?>" id="password" name="password">
                                                <button type="submit" class="directorist-te-icon-btn" aria-label="<?php esc_attr_e( 'Refresh purchase', 'directorist' ); ?>">
                                                    <i class="fas fa-arrow-right" aria-hidden="true"></i>
                                                </button>
                                                <button class="directorist-te-icon-btn directorist-te-icon-btn--danger et-close-auth-btn" aria-label="<?php esc_attr_e( 'Close refresh form', 'directorist' ); ?>">
                                                    <i class="fas fa-times" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                            <div class="atbdp-form-feedback directorist-te-feedback"></div>
                                        </form>
                                    </div>
                                    <div class="purchase-refresh-btn-wrapper">
                                        <button type="button" class="directorist-te-account-dropdown__item purchase-refresh-btn">
                                            <i class="la la-refresh" aria-hidden="true"></i>
                                            <?php esc_html_e( 'Refresh purchases', 'directorist' ); ?>
                                        </button>
                                    </div>
                                    <a href="#" class="directorist-te-account-dropdown__item directorist-te-account-dropdown__item--danger subscriptions-logout-btn" data-hard-logout="<?php echo esc_attr( $args['hard_logout'] ?? 0 ); ?>">
                                        <i class="fas fa-sign-out-alt" aria-hidden="true"></i>
                                        <?php esc_html_e( 'Disconnect', 'directorist' ); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else : ?>
                    <nav class="directorist-te-resource-links" aria-label="<?php esc_attr_e( 'Directorist resources', 'directorist' ); ?>">
                        <a href="https://directorist.com/documentation/directorist/" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Docs, opens in a new tab', 'directorist' ); ?>"><?php esc_html_e( 'Docs', 'directorist' ); ?></a>
                        <a href="https://www.youtube.com/@wpdirectorist" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Tutorials, opens in a new tab', 'directorist' ); ?>"><?php esc_html_e( 'Tutorials', 'directorist' ); ?></a>
                        <a href="https://directorist.com/contact/" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Support, opens in a new tab', 'directorist' ); ?>"><?php esc_html_e( 'Support', 'directorist' ); ?></a>
                    </nav>
                <?php endif; ?>
            </header>

            <?php if ( $is_logged_in ) : ?>
                <h1 class="screen-reader-text directorist-te-notice-heading"><?php esc_html_e( 'Directorist dashboard', 'directorist' ); ?></h1>

                <section class="directorist-te-view directorist-te-dashboard" id="directorist-te-dashboard-view" data-directorist-te-view="dashboard" hidden aria-hidden="true">
                    <section class="directorist-te-dashboard-welcome">
                        <div>
                            <h1><?php esc_html_e( 'Good morning, Olivia', 'directorist' ); ?></h1>
                            <p><?php esc_html_e( 'Your plan is active until Jan 22, 2027, so every theme and extension is unlocked.', 'directorist' ); ?></p>
                        </div>
                        <div class="directorist-te-dashboard-welcome__actions">
                            <a class="directorist-te-btn directorist-te-btn--secondary" href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank" rel="noopener noreferrer">
                                <i class="la la-external-link-alt" aria-hidden="true"></i>
                                <?php esc_html_e( 'View directory', 'directorist' ); ?>
                            </a>
                            <a class="directorist-te-btn directorist-te-btn--primary" href="<?php echo esc_url( admin_url( 'post-new.php?post_type=at_biz_dir' ) ); ?>">
                                <i class="la la-plus" aria-hidden="true"></i>
                                <?php esc_html_e( 'Add listing', 'directorist' ); ?>
                            </a>
                        </div>
                    </section>

                    <section class="directorist-te-dashboard-nudge" id="directorist-te-dashboard-nudge">
                        <div class="directorist-te-dashboard-nudge__top">
                            <div class="directorist-te-dashboard-ring" aria-label="<?php esc_attr_e( 'Setup progress 66 percent', 'directorist' ); ?>">
                                <svg width="46" height="46" viewBox="0 0 46 46" aria-hidden="true" focusable="false">
                                    <circle cx="23" cy="23" r="19" fill="none" stroke="#e5e7eb" stroke-width="4.5"></circle>
                                    <circle cx="23" cy="23" r="19" fill="none" stroke="currentColor" stroke-width="4.5" stroke-linecap="round" stroke-dasharray="119.4" stroke-dashoffset="40.6"></circle>
                                </svg>
                                <span><?php esc_html_e( '66%', 'directorist' ); ?></span>
                            </div>
                            <div>
                                <h2><?php esc_html_e( 'A few steps to your first paid listing', 'directorist' ); ?></h2>
                                <p><?php esc_html_e( 'Setup is done. These turn your directory into a working business.', 'directorist' ); ?></p>
                            </div>
                            <button type="button" class="directorist-te-dashboard-nudge__dismiss" aria-label="<?php esc_attr_e( 'Dismiss setup steps', 'directorist' ); ?>">
                                <i class="la la-times" aria-hidden="true"></i>
                            </button>
                        </div>
                        <div class="directorist-te-dashboard-steps">
                            <button type="button" class="directorist-te-dashboard-step is-done">
                                <span><i class="la la-check" aria-hidden="true"></i></span>
                                <?php esc_html_e( 'Import demo content', 'directorist' ); ?>
                            </button>
                            <button type="button" class="directorist-te-dashboard-step is-done">
                                <span><i class="la la-check" aria-hidden="true"></i></span>
                                <?php esc_html_e( 'Add your real categories', 'directorist' ); ?>
                            </button>
                            <button type="button" class="directorist-te-dashboard-step">
                                <span><i class="la la-check" aria-hidden="true"></i></span>
                                <?php esc_html_e( 'Connect Stripe to take payments', 'directorist' ); ?>
                                <i class="la la-angle-right" aria-hidden="true"></i>
                            </button>
                            <button type="button" class="directorist-te-dashboard-step">
                                <span><i class="la la-check" aria-hidden="true"></i></span>
                                <?php esc_html_e( 'Publish your first real listing', 'directorist' ); ?>
                                <i class="la la-angle-right" aria-hidden="true"></i>
                            </button>
                        </div>
                    </section>

                    <section class="directorist-te-dashboard-metrics" aria-label="<?php esc_attr_e( 'Directory metrics', 'directorist' ); ?>">
                        <div class="directorist-te-dashboard-metric">
                            <div class="directorist-te-dashboard-metric__top">
                                <span class="directorist-te-dashboard-icon directorist-te-dashboard-icon--blue"><i class="la la-th-large" aria-hidden="true"></i></span>
                                <div><strong><?php esc_html_e( '12,900', 'directorist' ); ?></strong><span><?php esc_html_e( 'Published listings', 'directorist' ); ?></span></div>
                            </div>
                            <div class="directorist-te-dashboard-metric__foot"><span><i class="la la-arrow-up" aria-hidden="true"></i><?php esc_html_e( '8.2%', 'directorist' ); ?></span><?php esc_html_e( 'vs last 30 days', 'directorist' ); ?></div>
                            <svg class="directorist-te-dashboard-metric-spark" viewBox="0 0 96 42" preserveAspectRatio="none" aria-hidden="true" focusable="false"><path d="M0,34 L13,29 L26,31 L40,22 L53,24 L66,15 L80,17 L96,7" fill="none" stroke="#4f6ef7" stroke-width="2.2"></path></svg>
                        </div>
                        <div class="directorist-te-dashboard-metric">
                            <div class="directorist-te-dashboard-metric__top">
                                <span class="directorist-te-dashboard-icon directorist-te-dashboard-icon--teal"><i class="la la-eye" aria-hidden="true"></i></span>
                                <div><strong><?php esc_html_e( '86,420', 'directorist' ); ?></strong><span><?php esc_html_e( 'Listing views', 'directorist' ); ?></span></div>
                            </div>
                            <div class="directorist-te-dashboard-metric__foot"><span><i class="la la-arrow-up" aria-hidden="true"></i><?php esc_html_e( '14%', 'directorist' ); ?></span><?php esc_html_e( 'vs last 30 days', 'directorist' ); ?></div>
                            <svg class="directorist-te-dashboard-metric-spark" viewBox="0 0 96 42" preserveAspectRatio="none" aria-hidden="true" focusable="false"><path d="M0,32 L13,28 L26,30 L40,20 L53,22 L66,18 L80,11 L96,9" fill="none" stroke="#0ea5b7" stroke-width="2.2"></path></svg>
                        </div>
                        <div class="directorist-te-dashboard-metric directorist-te-dashboard-metric--attention">
                            <div class="directorist-te-dashboard-metric__top">
                                <span class="directorist-te-dashboard-icon directorist-te-dashboard-icon--amber"><i class="la la-clock" aria-hidden="true"></i></span>
                                <div><strong><?php esc_html_e( '7', 'directorist' ); ?></strong><span><?php esc_html_e( 'Pending review', 'directorist' ); ?></span></div>
                            </div>
                            <div class="directorist-te-dashboard-metric__foot"><?php esc_html_e( '3 listings expire this week', 'directorist' ); ?><a href="<?php echo esc_url( admin_url( 'edit.php?post_type=at_biz_dir' ) ); ?>"><?php esc_html_e( 'Review now', 'directorist' ); ?></a></div>
                        </div>
                        <div class="directorist-te-dashboard-metric">
                            <div class="directorist-te-dashboard-metric__top">
                                <span class="directorist-te-dashboard-icon directorist-te-dashboard-icon--violet"><i class="la la-dollar" aria-hidden="true"></i></span>
                                <div><strong><?php esc_html_e( '$2,400', 'directorist' ); ?></strong><span><?php esc_html_e( 'Revenue', 'directorist' ); ?></span></div>
                            </div>
                            <div class="directorist-te-dashboard-metric__foot"><span><i class="la la-arrow-up" aria-hidden="true"></i><?php esc_html_e( '18%', 'directorist' ); ?></span><?php esc_html_e( 'last 30 days', 'directorist' ); ?></div>
                            <svg class="directorist-te-dashboard-metric-spark" viewBox="0 0 96 42" preserveAspectRatio="none" aria-hidden="true" focusable="false"><path d="M0,38 L13,34 L26,28 L40,30 L53,20 L66,16 L80,12 L96,5" fill="none" stroke="#7c5cff" stroke-width="2.2"></path></svg>
                        </div>
                    </section>

                    <section class="directorist-te-dashboard-grid">
                        <div class="directorist-te-dashboard-card">
                            <div class="directorist-te-dashboard-card__head"><i class="la la-bolt" aria-hidden="true"></i><h2><?php esc_html_e( 'Quick actions', 'directorist' ); ?></h2></div>
                            <div class="directorist-te-dashboard-actions">
                                <a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=at_biz_dir' ) ); ?>" class="directorist-te-dashboard-action"><span><i class="la la-plus" aria-hidden="true"></i></span><strong><?php esc_html_e( 'Add a listing', 'directorist' ); ?></strong><em><?php esc_html_e( 'Create a new directory entry', 'directorist' ); ?></em><i class="la la-angle-right" aria-hidden="true"></i></a>
                                <a href="<?php echo esc_url( admin_url( 'edit-tags.php?taxonomy=at_biz_dir-category&post_type=at_biz_dir' ) ); ?>" class="directorist-te-dashboard-action"><span><i class="la la-tags" aria-hidden="true"></i></span><strong><?php esc_html_e( 'Manage categories', 'directorist' ); ?></strong><em><?php esc_html_e( 'Organize how listings are grouped', 'directorist' ); ?></em><i class="la la-angle-right" aria-hidden="true"></i></a>
                                <a href="#" class="directorist-te-dashboard-action"><span><i class="la la-paint-roller" aria-hidden="true"></i></span><strong><?php esc_html_e( 'Customize listing layout', 'directorist' ); ?></strong><em><?php esc_html_e( 'Design the single listing page', 'directorist' ); ?></em><i class="la la-angle-right" aria-hidden="true"></i></a>
                                <a href="#" class="directorist-te-dashboard-action"><span><i class="la la-file-alt" aria-hidden="true"></i></span><strong><?php esc_html_e( 'Submission form settings', 'directorist' ); ?></strong><em><?php esc_html_e( 'Control what users can submit', 'directorist' ); ?></em><i class="la la-angle-right" aria-hidden="true"></i></a>
                                <a href="#" class="directorist-te-dashboard-action"><span><i class="la la-envelope" aria-hidden="true"></i></span><strong><?php esc_html_e( 'Email notifications', 'directorist' ); ?></strong><em><?php esc_html_e( 'Set who gets notified, and when', 'directorist' ); ?></em><i class="la la-angle-right" aria-hidden="true"></i></a>
                            </div>
                        </div>

                        <div class="directorist-te-dashboard-card">
                            <div class="directorist-te-dashboard-card__head">
                                <i class="la la-history" aria-hidden="true"></i>
                                <h2><?php esc_html_e( 'Recent activity', 'directorist' ); ?></h2>
                                <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=at_biz_dir' ) ); ?>"><?php esc_html_e( 'View all', 'directorist' ); ?></a>
                            </div>
                            <div class="directorist-te-dashboard-activity">
                                <div><span class="directorist-te-dashboard-activity-icon directorist-te-dashboard-activity-icon--blue"><i class="la la-plus" aria-hidden="true"></i></span><p><strong><?php esc_html_e( 'New listing submitted', 'directorist' ); ?></strong><b><?php esc_html_e( 'The Cozy Corner', 'directorist' ); ?></b><?php esc_html_e( ' added by Sunny Cafe', 'directorist' ); ?><small><?php esc_html_e( '4 minutes ago', 'directorist' ); ?></small></p><button type="button" class="directorist-te-btn directorist-te-btn--soft"><?php esc_html_e( 'Review', 'directorist' ); ?></button></div>
                                <div><span class="directorist-te-dashboard-activity-icon directorist-te-dashboard-activity-icon--green"><i class="la la-star" aria-hidden="true"></i></span><p><strong><?php esc_html_e( 'New review received', 'directorist' ); ?></strong><?php esc_html_e( '5-star review on ', 'directorist' ); ?><b><?php esc_html_e( 'Spice Route Restaurant', 'directorist' ); ?></b><small><?php esc_html_e( 'Today, 2:30 PM', 'directorist' ); ?></small></p></div>
                                <div><span class="directorist-te-dashboard-activity-icon directorist-te-dashboard-activity-icon--violet"><i class="la la-dollar" aria-hidden="true"></i></span><p><strong><?php esc_html_e( 'Payment received', 'directorist' ); ?></strong><b><?php esc_html_e( '$15.00', 'directorist' ); ?></b><?php esc_html_e( ' from Kenji Tanaka for a featured listing', 'directorist' ); ?><small><?php esc_html_e( 'Yesterday, 10:00 PM', 'directorist' ); ?></small></p><button type="button" class="directorist-te-btn directorist-te-btn--secondary"><?php esc_html_e( 'Receipt', 'directorist' ); ?></button></div>
                                <div><span class="directorist-te-dashboard-activity-icon directorist-te-dashboard-activity-icon--info"><i class="la la-user-plus" aria-hidden="true"></i></span><p><strong><?php esc_html_e( 'New user registered', 'directorist' ); ?></strong><b><?php esc_html_e( 'Namrid Mova', 'directorist' ); ?></b><?php esc_html_e( ' signed up as a listing owner', 'directorist' ); ?><small><?php esc_html_e( 'Today, 11:00 AM', 'directorist' ); ?></small></p></div>
                                <div><span class="directorist-te-dashboard-activity-icon directorist-te-dashboard-activity-icon--amber"><i class="la la-hourglass-half" aria-hidden="true"></i></span><p><strong><?php esc_html_e( 'Listing expiring soon', 'directorist' ); ?> <span><?php esc_html_e( 'Sample', 'directorist' ); ?></span></strong><b><?php esc_html_e( 'Sunny Cafe', 'directorist' ); ?></b><?php esc_html_e( ' expires in 3 days', 'directorist' ); ?><small><?php esc_html_e( '12 May, 9:00 PM', 'directorist' ); ?></small></p><button type="button" class="directorist-te-btn directorist-te-btn--secondary"><?php esc_html_e( 'Renew', 'directorist' ); ?></button></div>
                            </div>
                        </div>
                    </section>

                    <section class="directorist-te-dashboard-recommendations">
                        <div class="directorist-te-dashboard-recommendations__head">
                            <i class="la la-magic" aria-hidden="true"></i>
                            <h2><?php esc_html_e( 'Recommended for your restaurant directory', 'directorist' ); ?></h2>
                            <span><?php esc_html_e( "Based on what you're building", 'directorist' ); ?></span>
                        </div>
                        <p><?php esc_html_e( "You're running paid, food-focused listings. These three add-ons match what your directory needs next, so owners can do more and you can earn more.", 'directorist' ); ?></p>
                        <div class="directorist-te-dashboard-recommendations__grid">
                            <div><div class="directorist-te-dashboard-recommendation-top"><span class="directorist-te-dashboard-recommendation-icon directorist-te-dashboard-recommendation-icon--green"><i class="la la-clock" aria-hidden="true"></i></span><h3><?php esc_html_e( 'Business Hours', 'directorist' ); ?></h3></div><p><?php esc_html_e( 'Show open and closed times on every listing, so visitors know when to go.', 'directorist' ); ?></p><button type="button" class="directorist-te-btn directorist-te-btn--soft"><i class="la la-download" aria-hidden="true"></i><?php esc_html_e( 'Install', 'directorist' ); ?></button></div>
                            <div><div class="directorist-te-dashboard-recommendation-top"><span class="directorist-te-dashboard-recommendation-icon directorist-te-dashboard-recommendation-icon--orange"><i class="la la-images" aria-hidden="true"></i></span><h3><?php esc_html_e( 'Image Gallery', 'directorist' ); ?></h3></div><p><?php esc_html_e( 'Add several photos per listing, so restaurants look far more appealing.', 'directorist' ); ?></p><button type="button" class="directorist-te-btn directorist-te-btn--soft"><i class="la la-download" aria-hidden="true"></i><?php esc_html_e( 'Install', 'directorist' ); ?></button></div>
                            <div><div class="directorist-te-dashboard-recommendation-top"><span class="directorist-te-dashboard-recommendation-icon directorist-te-dashboard-recommendation-icon--violet"><i class="la la-comments" aria-hidden="true"></i></span><h3><?php esc_html_e( 'Live Chat', 'directorist' ); ?></h3></div><p><?php esc_html_e( 'Let visitors ask questions on a listing, so more of them book a table.', 'directorist' ); ?></p><button type="button" class="directorist-te-btn directorist-te-btn--soft"><i class="la la-download" aria-hidden="true"></i><?php esc_html_e( 'Install', 'directorist' ); ?></button></div>
                        </div>
                    </section>

                    <footer class="directorist-te-dashboard-footer">
                        <?php esc_html_e( 'Directorist 8.0', 'directorist' ); ?>
                        <span aria-hidden="true">·</span>
                        <?php esc_html_e( 'Agency plan', 'directorist' ); ?>
                        <span aria-hidden="true">·</span>
                        <a href="https://directorist.com/changelog/" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'What\'s new', 'directorist' ); ?></a>
                    </footer>
                </section>

                <section class="directorist-te-view directorist-te-view--addons is-active" id="directorist-te-addons-view" data-directorist-te-view="addons" aria-hidden="false">
            <?php endif; ?>

            <section class="directorist-te-hero">
                <div>
                    <h1><?php esc_html_e( 'Themes & Extensions', 'directorist' ); ?></h1>
                    <p><?php esc_html_e( 'Add features and looks to your directory. Each one solves a real job, so pick what fits.', 'directorist' ); ?></p>
                </div>
            </section>

            <?php if ( ! $is_logged_in ) : ?>
                <section class="directorist-te-connect account-connect">
                    <div>
                        <span class="directorist-te-eyebrow"><?php esc_html_e( 'Directorist account', 'directorist' ); ?></span>
                        <h2><?php echo esc_html( $connect_title ); ?></h2>
                        <p><?php echo esc_html( $connect_description ); ?></p>
                    </div>

                    <form
                        method="post"
                        id="atbdp-directorist-license-login-form"
                        class="atbdp-directorist-license-login-form directorist-te-connect-form"
                        data-connecting-label="<?php esc_attr_e( 'Connecting...', 'directorist' ); ?>"
                        data-username-required="<?php esc_attr_e( 'Enter your Directorist account username or email address.', 'directorist' ); ?>"
                        data-password-required="<?php esc_attr_e( 'Enter your Directorist account password.', 'directorist' ); ?>"
                        data-invalid-credentials="<?php esc_attr_e( 'The username, email address, or password is incorrect. Please check your details and try again.', 'directorist' ); ?>"
                        data-unexpected-error="<?php esc_attr_e( 'Could not connect. Please check your details and try again.', 'directorist' ); ?>"
                        data-network-error="<?php esc_attr_e( 'Could not reach Directorist.com. Please try again.', 'directorist' ); ?>"
                    >
                        <div class="atbdp-form-page">
                            <div class="directorist-te-field-row">
                                <label>
                                    <span><?php esc_html_e( 'Username or email address', 'directorist' ); ?></span>
                                    <input type="text" name="username" id="username" autocomplete="username" placeholder="<?php esc_attr_e( 'name@example.com', 'directorist' ); ?>" aria-describedby="directorist-te-connect-feedback">
                                </label>
                                <label>
                                    <span><?php esc_html_e( 'Password', 'directorist' ); ?></span>
                                    <span class="directorist-te-password-control">
                                        <input type="password" name="password" id="password" autocomplete="current-password" aria-describedby="directorist-te-connect-feedback">
                                        <button
                                            type="button"
                                            class="directorist-te-password-toggle"
                                            aria-label="<?php esc_attr_e( 'Show password', 'directorist' ); ?>"
                                            aria-pressed="false"
                                            data-show-label="<?php esc_attr_e( 'Show password', 'directorist' ); ?>"
                                            data-hide-label="<?php esc_attr_e( 'Hide password', 'directorist' ); ?>"
                                        >
                                            <i class="la la-eye" aria-hidden="true"></i>
                                        </button>
                                    </span>
                                </label>
                            </div>
                            <div id="directorist-te-connect-feedback" class="atbdp-form-feedback directorist-te-feedback" role="status" aria-live="polite"></div>
                            <button type="submit" class="account-connect__btn directorist-te-btn directorist-te-btn--primary">
                                <?php esc_html_e( 'Connect', 'directorist' ); ?>
                                <i class="la la-arrow-right" aria-hidden="true"></i>
                            </button>
                        </div>
                        <div class="atbdp-form-response-page"></div>
                    </form>
                </section>
            <?php endif; ?>

            <?php if ( $is_logged_in && $total_updates > 0 ) : ?>
                <section class="directorist-te-update-banner">
                    <div class="directorist-te-update-icon" aria-hidden="true"><i class="la la-arrow-circle-up"></i></div>
                    <div>
                        <h2>
                            <?php
                            printf(
                                esc_html( _n( '%d update available', '%d updates available', $total_updates, 'directorist' ) ),
                                absint( $total_updates )
                            );
                            ?>
                        </h2>
                        <p><?php esc_html_e( 'Keep your extensions current, so bugs get fixed and your site stays secure.', 'directorist' ); ?></p>
                    </div>
                    <button type="button" class="directorist-te-btn directorist-te-btn--primary directorist-te-update-all" data-update-extensions="<?php echo esc_attr( ! empty( $args['total_outdated_extensions'] ) ? '1' : '0' ); ?>" data-update-themes="<?php echo esc_attr( ! empty( $args['total_outdated_themes'] ) ? '1' : '0' ); ?>">
                        <i class="la la-sync" aria-hidden="true"></i>
                        <?php esc_html_e( 'Update all', 'directorist' ); ?>
                    </button>
                </section>
            <?php endif; ?>

            <section class="directorist-te-toolbar" id="atbdp-themes-extensions-contents">
                <div class="directorist-te-toolbar-main">
                    <div class="directorist-te-tabs" role="tablist" aria-label="<?php esc_attr_e( 'Product type', 'directorist' ); ?>">
                        <button type="button" class="directorist-te-tab is-active" data-filter-type="all"><?php esc_html_e( 'All', 'directorist' ); ?> <span><?php echo esc_html( $total_rows ); ?></span></button>
                        <button type="button" class="directorist-te-tab" data-filter-type="extension"><?php esc_html_e( 'Extensions', 'directorist' ); ?> <span><?php echo esc_html( $extension_rows ); ?></span></button>
                        <button type="button" class="directorist-te-tab" data-filter-type="theme"><?php esc_html_e( 'Themes', 'directorist' ); ?> <span><?php echo esc_html( $theme_rows ); ?></span></button>
                    </div>
                    <div class="directorist-te-toolbar-catalog">
                        <label class="directorist-te-search directorist-te-toolbar-search">
                            <span class="screen-reader-text"><?php esc_html_e( 'Search themes and extensions', 'directorist' ); ?></span>
                            <i class="la la-search" aria-hidden="true"></i>
                            <input type="search" class="directorist-te-search-input" placeholder="<?php esc_attr_e( 'Search add-ons...', 'directorist' ); ?>">
                        </label>
                        <span class="directorist-te-count"><?php printf( esc_html( _n( '%d add-on', '%d add-ons', $total_rows, 'directorist' ) ), absint( $total_rows ) ); ?></span>
                    </div>
                </div>
                <?php if ( $is_logged_in ) : ?>
                    <div class="directorist-te-toolbar-row directorist-te-toolbar-row--filters">
                        <div class="directorist-te-segmented" aria-label="<?php esc_attr_e( 'Product status', 'directorist' ); ?>">
                            <button type="button" class="is-active" data-filter-status="all"><?php esc_html_e( 'All', 'directorist' ); ?></button>
                            <button type="button" data-filter-status="installed"><?php esc_html_e( 'Installed', 'directorist' ); ?></button>
                            <button type="button" data-filter-status="not-installed"><?php esc_html_e( 'Not installed', 'directorist' ); ?></button>
                            <button type="button" data-filter-status="update"><?php esc_html_e( 'Updates', 'directorist' ); ?><?php if ( $update_rows ) : ?> <span class="directorist-te-update-count"><?php echo esc_html( $update_rows ); ?></span><?php endif; ?></button>
                        </div>
                    </div>
                <?php endif; ?>
            </section>

            <form id="atbdp-my-extensions-form" class="atbdp-my-extensions-form directorist-te-products-form" method="post">
                <div id="atbdp-extensions-tab" class="directorist-te-products atbdp-tab__content active">
                    <?php if ( $is_logged_in ) : ?>
                        <div class="directorist-te-bulkbar" hidden>
                            <span class="directorist-te-selected-count"></span>
                            <span class="directorist-te-bulkbar__sep"></span>
                            <span class="directorist-te-bulkbar__empty" hidden><?php esc_html_e( 'No safe bulk action is available for this selection.', 'directorist' ); ?></span>
                            <span class="directorist-te-bulkbar__notice" hidden><?php esc_html_e( 'Action counts show eligible selected items. Unsupported selected items are skipped.', 'directorist' ); ?></span>
                            <button type="button" class="directorist-te-bulk-action" data-task="install"><i class="la la-download" aria-hidden="true"></i><span class="directorist-te-bulk-action__label"><?php esc_html_e( 'Install', 'directorist' ); ?></span><span class="directorist-te-bulk-action__count" hidden></span></button>
                            <button type="button" class="directorist-te-bulk-action" data-task="update"><i class="la la-sync" aria-hidden="true"></i><span class="directorist-te-bulk-action__label"><?php esc_html_e( 'Update', 'directorist' ); ?></span><span class="directorist-te-bulk-action__count" hidden></span></button>
                            <button type="button" class="directorist-te-bulk-action" data-task="activate"><span class="directorist-te-bulk-action__label"><?php esc_html_e( 'Activate', 'directorist' ); ?></span><span class="directorist-te-bulk-action__count" hidden></span></button>
                            <button type="button" class="directorist-te-bulk-action" data-task="deactivate"><span class="directorist-te-bulk-action__label"><?php esc_html_e( 'Deactivate', 'directorist' ); ?></span><span class="directorist-te-bulk-action__count" hidden></span></button>
                            <button type="button" class="directorist-te-bulk-action directorist-te-bulk-action--danger" data-task="uninstall"><i class="la la-trash" aria-hidden="true"></i><span class="directorist-te-bulk-action__label"><?php esc_html_e( 'Delete', 'directorist' ); ?></span><span class="directorist-te-bulk-action__count" hidden></span></button>
                            <button type="button" class="directorist-te-bulk-clear"><i class="la la-times" aria-hidden="true"></i><?php esc_html_e( 'Clear', 'directorist' ); ?></button>
                        </div>

                        <div class="ei-action-wrapper directorist-te-legacy-bulk" aria-hidden="true">
                            <div class="ei-action-dropdown">
                                <select id="bulk-actions" name="bulk-actions" tabindex="-1">
                                    <option value=""></option>
                                    <option value="activate"><?php esc_html_e( 'Activate', 'directorist' ); ?></option>
                                    <option value="deactivate"><?php esc_html_e( 'Deactivate', 'directorist' ); ?></option>
                                    <option value="uninstall"><?php esc_html_e( 'Uninstall', 'directorist' ); ?></option>
                                </select>
                            </div>
                            <button type="submit" class="ei-action-btn" tabindex="-1"><?php esc_html_e( 'Apply', 'directorist' ); ?></button>
                        </div>
                    <?php endif; ?>

                    <?php if ( ! empty( $rows ) ) : ?>
                        <div class="directorist-te-list">
                            <div class="directorist-te-list-head">
                                <?php if ( $is_logged_in ) : ?>
                                    <label class="directorist-te-checkbox directorist-te-checkbox--head">
                                        <input type="checkbox" name="select-all-installed" id="select-all-installed">
                                        <span class="screen-reader-text"><?php esc_html_e( 'Select all selectable products', 'directorist' ); ?></span>
                                    </label>
                                <?php endif; ?>
                                <span class="directorist-te-list-head__addon"><?php esc_html_e( 'Add-on', 'directorist' ); ?></span>
                                <span class="directorist-te-list-head__status"><?php esc_html_e( 'Status', 'directorist' ); ?></span>
                            </div>
                            <?php foreach ( $rows as $row ) : ?>
                                <?php
                                $search_index   = $get_row_search_index( $row );
                                $row_type_label = ! empty( $row['typeLabel'] ) && is_scalar( $row['typeLabel'] )
                                    ? (string) $row['typeLabel']
                                    : ( 'theme' === $row['type'] ? __( 'Theme', 'directorist' ) : __( 'Extension', 'directorist' ) );
                                $row_type_class = ! empty( $row['typeClass'] ) && is_scalar( $row['typeClass'] ) ? sanitize_html_class( (string) $row['typeClass'] ) : $row['type'];
                                ?>
                                <article class="directorist-te-row" data-product-type="<?php echo esc_attr( $row['type'] ); ?>" data-product-status="<?php echo esc_attr( $row['status'] ); ?>" data-search-text="<?php echo esc_attr( $search_index['text'] ); ?>" data-badge-search-text="<?php echo esc_attr( $search_index['badge_text'] ); ?>" data-badge-search-terms="<?php echo esc_attr( $search_index['badge_terms'] ); ?>">
                                    <?php if ( $is_logged_in ) : ?>
                                        <div class="directorist-te-row__select">
                                            <?php if ( ! empty( $row['bulk'] ) ) : ?>
                                                <label class="directorist-te-checkbox">
                                                    <input type="checkbox" id="<?php echo esc_attr( $row['bulk']['id'] ); ?>" name="<?php echo esc_attr( $row['bulk']['name'] ); ?>" class="<?php echo esc_attr( $row['bulk']['class'] ); ?>"<?php $render_attrs( $row['bulk']['attrs'] ?? [] ); ?>>
                                                    <i class="la la-check" aria-hidden="true"></i>
                                                    <span class="screen-reader-text"><?php echo esc_html( sprintf( __( 'Select %s', 'directorist' ), $row['name'] ) ); ?></span>
                                                </label>
                                            <?php else : ?>
                                                <label class="directorist-te-checkbox directorist-te-checkbox--disabled">
                                                    <input type="checkbox" disabled>
                                                    <i class="la la-check" aria-hidden="true"></i>
                                                    <span class="screen-reader-text"><?php echo esc_html( sprintf( __( 'Bulk actions unavailable for %s', 'directorist' ), $row['name'] ) ); ?></span>
                                                </label>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="directorist-te-row__media">
                                        <img src="<?php echo esc_url( $row['image'] ); ?>" alt="">
                                    </div>

                                    <div class="directorist-te-row__content">
                                        <div class="directorist-te-row__title">
                                            <h2><?php echo esc_html( $row['name'] ); ?></h2>
                                            <span class="directorist-te-type directorist-te-type--<?php echo esc_attr( $row_type_class ); ?>">
                                                <?php echo esc_html( $row_type_label ); ?>
                                            </span>
                                            <?php if ( ! empty( $row['badges'] ) ) : ?>
                                                <?php foreach ( $row['badges'] as $badge ) : ?>
                                                    <?php
                                                    $badge_label = is_array( $badge ) ? ( $badge['label'] ?? '' ) : $badge;
                                                    $badge_type  = is_array( $badge ) ? ( $badge['type'] ?? '' ) : sanitize_title( (string) $badge_label );

                                                    if ( ! is_scalar( $badge_label ) || '' === trim( (string) $badge_label ) ) {
                                                        continue;
                                                    }

                                                    $badge_type = is_scalar( $badge_type ) && '' !== trim( (string) $badge_type ) ? sanitize_html_class( (string) $badge_type ) : 'default';
                                                    ?>
                                                    <span class="directorist-te-badge directorist-te-badge--<?php echo esc_attr( $badge_type ); ?>"><?php echo esc_html( (string) $badge_label ); ?></span>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                            <?php if ( ! empty( $row['version'] ) ) : ?>
                                                <span class="directorist-te-version"><?php echo esc_html( 'v' . $row['version'] ); ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <?php if ( ! empty( $row['description'] ) ) : ?>
                                            <p><?php echo esc_html( $row['description'] ); ?></p>
                                        <?php endif; ?>
                                    </div>

                                    <div class="directorist-te-row__status">
                                        <?php $render_status( $row['statusLabel'], strtok( $row['status'], ' ' ) ); ?>
                                    </div>

                                    <div class="directorist-te-row__actions ext-action ext-action-<?php echo esc_attr( sanitize_html_class( $row['key'] ) ); ?>">
                                        <?php $render_action( $row['primary'] ); ?>
                                        <?php if ( ! empty( $row['demo'] ) ) : ?>
                                            <?php $render_action( $row['demo'] ); ?>
                                        <?php endif; ?>
                                        <?php if ( ! empty( $row['details'] ) ) : ?>
                                            <?php $render_action( $row['details'] ); ?>
                                        <?php endif; ?>
                                        <?php if ( ! empty( $row['menu'] ) ) : ?>
                                            <div class="directorist-te-menu">
                                                <button type="button" class="directorist-te-icon-btn ext-action-drop directorist-te-menu-toggle" aria-label="<?php esc_attr_e( 'More actions', 'directorist' ); ?>">
                                                    <i class="la la-ellipsis-v" aria-hidden="true"></i>
                                                </button>
                                                <div class="directorist-te-menu__items ext-action-drop__item">
                                                    <?php foreach ( $row['menu'] as $menu_action ) : ?>
                                                        <?php $render_action( $menu_action ); ?>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                            <div class="directorist-te-empty directorist-te-empty--filter">
                                <i class="la la-search" aria-hidden="true"></i>
                                <h2><?php esc_html_e( 'Nothing here yet', 'directorist' ); ?></h2>
                                <p><?php esc_html_e( 'No add-ons match this filter. Try a different tab or search.', 'directorist' ); ?></p>
                                <button type="button" class="directorist-te-empty-reset directorist-te-btn directorist-te-btn--ghost">
                                    <?php esc_html_e( 'Clear filters', 'directorist' ); ?>
                                </button>
                            </div>
                        </div>
                    <?php else : ?>
                        <div class="directorist-te-empty">
                            <h2><?php esc_html_e( 'No themes or extensions found', 'directorist' ); ?></h2>
                            <p><?php esc_html_e( 'Reconnect your account or refresh products to collect the latest available items.', 'directorist' ); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </form>

            <section class="directorist-te-upsell">
                <div>
                    <h2><?php esc_html_e( 'Unlock every theme and extension', 'directorist' ); ?></h2>
                    <p><?php esc_html_e( 'Browse the full library and add anything your directory needs.', 'directorist' ); ?></p>
                </div>
                <a href="https://directorist.com/extensions/" target="_blank" rel="noopener noreferrer" class="directorist-te-btn directorist-te-btn--primary">
                    <i class="la la-th" aria-hidden="true"></i>
                    <?php esc_html_e( 'Browse all add-ons', 'directorist' ); ?>
                </a>
            </section>

            <footer class="directorist-te-footer">
                <?php esc_html_e( 'Showing add-ons for your site', 'directorist' ); ?>
                <span aria-hidden="true">·</span>
                <?php esc_html_e( 'Need something specific?', 'directorist' ); ?>
                <a href="https://directorist.com/contact/" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Request an extension', 'directorist' ); ?></a>
            </footer>
            <?php if ( $is_logged_in ) : ?>
                </section>
            <?php endif; ?>
        </div>
    </div>
</div>
