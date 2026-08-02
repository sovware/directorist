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
$requested_view        = isset( $_GET['te_view'] ) && is_scalar( $_GET['te_view'] ) ? sanitize_key( wp_unslash( $_GET['te_view'] ) ) : '';
$requested_type        = isset( $_GET['te_type'] ) && is_scalar( $_GET['te_type'] ) ? sanitize_key( wp_unslash( $_GET['te_type'] ) ) : '';
$initial_view          = $is_logged_in && in_array( $requested_view, [ 'dashboard', 'addons' ], true ) ? $requested_view : ( $is_logged_in ? 'dashboard' : 'addons' );
$initial_type          = $is_logged_in && in_array( $requested_type, [ 'all', 'extension', 'theme' ], true ) ? $requested_type : 'all';
$installed_extensions  = ! empty( $args['installed_extension_list'] ) && is_array( $args['installed_extension_list'] ) ? $args['installed_extension_list'] : [];
$installed_themes      = ! empty( $args['installed_theme_list'] ) && is_array( $args['installed_theme_list'] ) ? $args['installed_theme_list'] : [];
$dashboard_welcome     = ! empty( $args['dashboard_welcome'] ) && is_array( $args['dashboard_welcome'] ) ? $args['dashboard_welcome'] : [];
$dashboard_quick_actions = ! empty( $args['dashboard_quick_actions'] ) && is_array( $args['dashboard_quick_actions'] ) ? $args['dashboard_quick_actions'] : [];
$dashboard_metrics     = ! empty( $args['dashboard_metrics'] ) && is_array( $args['dashboard_metrics'] ) ? $args['dashboard_metrics'] : [];
$dashboard_setup       = ! empty( $args['dashboard_setup'] ) && is_array( $args['dashboard_setup'] ) ? $args['dashboard_setup'] : [];
$dashboard_setup_visible = ! array_key_exists( 'is_visible', $dashboard_setup ) || ! empty( $dashboard_setup['is_visible'] );
$dashboard_setup_dismiss_key = sprintf( 'directorist_te_dashboard_checklist_dismissed_%d_%d', get_current_blog_id(), get_current_user_id() );
$dashboard_activity    = ! empty( $args['dashboard_activity'] ) && is_array( $args['dashboard_activity'] ) ? $args['dashboard_activity'] : [];
$dashboard_activity_items = ! empty( $dashboard_activity['items'] ) && is_array( $dashboard_activity['items'] ) ? $dashboard_activity['items'] : [];
$dashboard_recommendations = ! empty( $args['dashboard_recommendations'] ) && is_array( $args['dashboard_recommendations'] ) ? $args['dashboard_recommendations'] : [];
$account_name          = ! empty( $dashboard_welcome['account_name'] ) ? (string) $dashboard_welcome['account_name'] : '';
$account_avatar_url    = ! empty( $dashboard_welcome['account_avatar_url'] ) ? (string) $dashboard_welcome['account_avatar_url'] : '';
$account_initials      = ! empty( $dashboard_welcome['account_initials'] ) ? (string) $dashboard_welcome['account_initials'] : 'D';
$connection_method     = ! empty( $dashboard_welcome['connection_method'] ) && 'access_key' === $dashboard_welcome['connection_method'] ? 'access_key' : 'account';
$plugin_version        = ! empty( $dashboard_welcome['plugin_version'] ) ? (string) $dashboard_welcome['plugin_version'] : '';
$account_plan_label    = ! empty( $dashboard_welcome['plan_label'] ) ? (string) $dashboard_welcome['plan_label'] : __( 'Connected account', 'directorist' );
$whats_new_url         = ! empty( $dashboard_welcome['whats_new_url'] ) ? (string) $dashboard_welcome['whats_new_url'] : 'https://directorist.com/changelog/';
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
            $required_lookup[ $required_lookup_key ] = $extension_key;
        }
    }
}

$get_required_extension_key = static function( $extension_key, $extension_base = '' ) use ( $required_lookup, $args ) {
    $extension_alias = $args['ATBDP_Extensions']->get_extension_alias_key( $extension_key );
    $lookup_keys     = array_filter(
        [
            $extension_key,
            $extension_alias,
            $extension_base,
            $extension_base ? preg_replace( '/\/.+/', '', $extension_base ) : '',
        ]
    );

    foreach ( $lookup_keys as $lookup_key ) {
        if ( isset( $required_lookup[ $lookup_key ] ) ) {
            return (string) $required_lookup[ $lookup_key ];
        }
    }

    return '';
};

$represented_required_extensions = [];

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
        $required_key  = $get_required_extension_key( $extension_key, $extension_base );
        $is_required   = '' !== $required_key;
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
                'label' => __( 'Delete', 'directorist' ),
                'class' => 'directorist-te-menu-link directorist-te-menu-link--danger ext-action-uninstall',
                'attrs' => [ 'data-target' => $extension_base ],
            ];
        }

        $badges = $get_product_badges( $product );
        if ( $is_required ) {
            $status .= ' required';
            $badges[] = __( 'Required', 'directorist' );
            $represented_required_extensions[ $required_key ] = true;
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
        $required_key = $get_required_extension_key( $extension_key );
        $is_required  = '' !== $required_key;
        $badges       = $get_product_badges( $product );

        if ( $is_required ) {
            $badges[] = __( 'Required', 'directorist' );
            $represented_required_extensions[ $required_key ] = true;
        }

        $add_row(
            [
                'key'         => 'extension-subscription-' . $extension_key,
                'type'        => 'extension',
                'status'      => $is_required ? 'required not-installed' : 'not-installed',
                'name'        => $name,
                'description' => $description,
                'image'       => $get_image( $product ),
                'badges'      => $badges,
                'statusLabel' => $is_required ? __( 'Required', 'directorist' ) : __( 'Not installed', 'directorist' ),
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

        if ( isset( $represented_required_extensions[ $extension_key ] ) || isset( $seen_rows[ 'extension-installed-' . $required_base ] ) || ! empty( $required_extension['installed'] ) ) {
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

        $represented_required_extensions[ $extension_key ] = true;
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

$promo_extensions = ! $is_logged_in
    ? $extensions
    : ( ! empty( $args['extensions_promo_list'] ) && is_array( $args['extensions_promo_list'] ) ? $args['extensions_promo_list'] : [] );

foreach ( $promo_extensions as $extension_key => $extension ) {
    $required_key = $get_required_extension_key( $extension_key );

    if ( $required_key && isset( $represented_required_extensions[ $required_key ] ) ) {
        continue;
    }

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

$promo_themes = ! $is_logged_in
    ? $themes
    : ( ! empty( $args['themes_promo_list'] ) && is_array( $args['themes_promo_list'] ) ? $args['themes_promo_list'] : [] );

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

$extension_update_count = max( 0, (int) ( $args['total_outdated_extensions'] ?? 0 ) );
$theme_update_count     = max( 0, (int) ( $args['total_outdated_themes'] ?? 0 ) );
$total_updates          = $extension_update_count + $theme_update_count;
$total_rows             = count( $rows );
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
$installed_rows = count(
    array_filter(
        $rows,
        static function( $row ) {
            $status = ! empty( $row['status'] ) ? preg_split( '/\s+/', (string) $row['status'] ) : [];

            return (bool) array_intersect( [ 'installed', 'active', 'update' ], $status );
        }
    )
);
$not_installed_rows = count(
    array_filter(
        $rows,
        static function( $row ) {
            $status = ! empty( $row['status'] ) ? preg_split( '/\s+/', (string) $row['status'] ) : [];

            return (bool) array_intersect( [ 'not-installed', 'marketplace' ], $status );
        }
    )
);
$required_rows = count(
    array_filter(
        $rows,
        static function( $row ) {
            $status = ! empty( $row['status'] ) ? preg_split( '/\s+/', (string) $row['status'] ) : [];

            return in_array( 'required', $status, true );
        }
    )
);
$notification_count = $total_updates + $required_rows;
?>

<div
    id="directorist"
    class="wrap atbd_wrapper directorist-te-page <?php echo esc_attr( $is_logged_in ? 'directorist-te-page--connected' : 'directorist-te-page--disconnected' ); ?>"
    data-initial-view="<?php echo esc_attr( $initial_view ); ?>"
    data-initial-type="<?php echo esc_attr( $initial_type ); ?>"
>
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
                        <button type="button" class="<?php echo esc_attr( 'dashboard' === $initial_view ? 'active' : '' ); ?>" data-directorist-te-view-target="dashboard" aria-controls="directorist-te-dashboard-view" <?php if ( 'dashboard' === $initial_view ) : ?>aria-current="page"<?php endif; ?>><?php esc_html_e( 'Dashboard', 'directorist' ); ?></button>
                        <button type="button" class="<?php echo esc_attr( 'addons' === $initial_view ? 'active' : '' ); ?>" data-directorist-te-view-target="addons" aria-controls="directorist-te-addons-view" <?php if ( 'addons' === $initial_view ) : ?>aria-current="page"<?php endif; ?>><?php esc_html_e( 'Themes and Extensions', 'directorist' ); ?></button>
                    </nav>
                    <div class="directorist-te-top-right">
                        <nav class="directorist-te-resource-links" aria-label="<?php esc_attr_e( 'Directorist resources', 'directorist' ); ?>">
                            <a href="https://directorist.com/documentation/directorist/" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Docs, opens in a new tab', 'directorist' ); ?>"><?php esc_html_e( 'Docs', 'directorist' ); ?></a>
                            <a href="https://www.youtube.com/@wpdirectorist" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Tutorials, opens in a new tab', 'directorist' ); ?>"><?php esc_html_e( 'Tutorials', 'directorist' ); ?></a>
                            <a href="https://directorist.com/contact/" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Support, opens in a new tab', 'directorist' ); ?>"><?php esc_html_e( 'Support', 'directorist' ); ?></a>
                        </nav>
                        <div class="directorist-te-notification-menu">
                            <button
                                type="button"
                                class="directorist-te-header-icon directorist-te-notification-toggle"
                                aria-haspopup="true"
                                aria-expanded="false"
                                aria-controls="directorist-te-notification-dropdown"
                            >
                                <i class="la la-bell" aria-hidden="true"></i>
                                <?php if ( $notification_count ) : ?>
                                    <span class="directorist-te-notification-badge" aria-hidden="true">
                                        <?php echo esc_html( $notification_count > 99 ? '99+' : $notification_count ); ?>
                                    </span>
                                <?php endif; ?>
                                <span class="screen-reader-text">
                                    <?php
                                    if ( $notification_count ) {
                                        printf(
                                            /* translators: %d: Number of Themes & Extensions items needing attention. */
                                            esc_html( _n( 'Open notifications, %d item needs attention', 'Open notifications, %d items need attention', $notification_count, 'directorist' ) ),
                                            absint( $notification_count )
                                        );
                                    } else {
                                        esc_html_e( 'Open notifications, no items need attention', 'directorist' );
                                    }
                                    ?>
                                </span>
                            </button>
                            <div
                                class="directorist-te-notification-dropdown"
                                id="directorist-te-notification-dropdown"
                                hidden
                                aria-hidden="true"
                                aria-labelledby="directorist-te-notification-title"
                            >
                                <div class="directorist-te-notification-dropdown__header">
                                    <strong id="directorist-te-notification-title"><?php esc_html_e( 'Notifications', 'directorist' ); ?></strong>
                                    <?php if ( $notification_count ) : ?>
                                        <span>
                                            <?php
                                            printf(
                                                /* translators: %d: Number of Themes & Extensions items needing attention. */
                                                esc_html( _n( '%d item', '%d items', $notification_count, 'directorist' ) ),
                                                absint( $notification_count )
                                            );
                                            ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="directorist-te-notification-list">
                                    <?php if ( $extension_update_count ) : ?>
                                        <button type="button" class="directorist-te-notification-item" data-notification-type="extension" data-notification-status="update">
                                            <span class="directorist-te-notification-item__icon directorist-te-notification-item__icon--update" aria-hidden="true"><i class="la la-plug"></i></span>
                                            <span class="directorist-te-notification-item__content">
                                                <strong>
                                                    <?php
                                                    printf(
                                                        /* translators: %d: Number of extension updates. */
                                                        esc_html( _n( '%d extension update', '%d extension updates', $extension_update_count, 'directorist' ) ),
                                                        absint( $extension_update_count )
                                                    );
                                                    ?>
                                                </strong>
                                                <span><?php esc_html_e( 'Review available extension updates.', 'directorist' ); ?></span>
                                            </span>
                                            <i class="la la-angle-right directorist-te-notification-item__arrow" aria-hidden="true"></i>
                                        </button>
                                    <?php endif; ?>

                                    <?php if ( $theme_update_count ) : ?>
                                        <button type="button" class="directorist-te-notification-item" data-notification-type="theme" data-notification-status="update">
                                            <span class="directorist-te-notification-item__icon directorist-te-notification-item__icon--update" aria-hidden="true"><i class="la la-paint-brush"></i></span>
                                            <span class="directorist-te-notification-item__content">
                                                <strong>
                                                    <?php
                                                    printf(
                                                        /* translators: %d: Number of theme updates. */
                                                        esc_html( _n( '%d theme update', '%d theme updates', $theme_update_count, 'directorist' ) ),
                                                        absint( $theme_update_count )
                                                    );
                                                    ?>
                                                </strong>
                                                <span><?php esc_html_e( 'Review available theme updates.', 'directorist' ); ?></span>
                                            </span>
                                            <i class="la la-angle-right directorist-te-notification-item__arrow" aria-hidden="true"></i>
                                        </button>
                                    <?php endif; ?>

                                    <?php if ( $required_rows ) : ?>
                                        <button type="button" class="directorist-te-notification-item" data-notification-type="extension" data-notification-status="required">
                                            <span class="directorist-te-notification-item__icon directorist-te-notification-item__icon--required" aria-hidden="true"><i class="la la-exclamation-circle"></i></span>
                                            <span class="directorist-te-notification-item__content">
                                                <strong>
                                                    <?php
                                                    printf(
                                                        /* translators: %d: Number of required extensions. */
                                                        esc_html( _n( '%d required extension', '%d required extensions', $required_rows, 'directorist' ) ),
                                                        absint( $required_rows )
                                                    );
                                                    ?>
                                                </strong>
                                                <span><?php esc_html_e( 'Review products required by your active theme.', 'directorist' ); ?></span>
                                            </span>
                                            <i class="la la-angle-right directorist-te-notification-item__arrow" aria-hidden="true"></i>
                                        </button>
                                    <?php endif; ?>

                                    <?php if ( ! $notification_count ) : ?>
                                        <div class="directorist-te-notification-empty">
                                            <span aria-hidden="true"><i class="la la-check-circle"></i></span>
                                            <strong><?php esc_html_e( 'You are all caught up', 'directorist' ); ?></strong>
                                            <p><?php esc_html_e( 'No add-on updates or required extensions need attention.', 'directorist' ); ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="directorist-te-account-menu">
                            <button
                                type="button"
                                class="directorist-te-avatar"
                                aria-haspopup="true"
                                aria-expanded="false"
                                aria-controls="directorist-te-account-dropdown"
                            >
                                <?php if ( $account_avatar_url ) : ?>
                                    <img src="<?php echo esc_url( $account_avatar_url ); ?>" alt="">
                                <?php else : ?>
                                    <span class="directorist-te-avatar__initials" aria-hidden="true"><?php echo esc_html( $account_initials ); ?></span>
                                <?php endif; ?>
                                <span class="screen-reader-text">
                                    <?php
                                    echo esc_html(
                                        $account_name
                                            ? sprintf(
                                                /* translators: %s: Connected Directorist account owner's display name. */
                                                __( 'Open Directorist account menu for %s', 'directorist' ),
                                                $account_name
                                            )
                                            : __( 'Open Directorist account menu', 'directorist' )
                                    );
                                    ?>
                                </span>
                                <i class="la la-angle-down" aria-hidden="true"></i>
                            </button>
                            <div
                                class="directorist-te-account-dropdown"
                                id="directorist-te-account-dropdown"
                                hidden
                                aria-hidden="true"
                                aria-labelledby="directorist-te-account-dropdown-title"
                            >
                                <div class="directorist-te-account-dropdown__header">
                                    <div class="directorist-te-account-dropdown__identity">
                                        <strong id="directorist-te-account-dropdown-title">
                                            <?php echo esc_html( $account_name ?: __( 'Directorist account', 'directorist' ) ); ?>
                                        </strong>
                                        <?php if ( $account_name ) : ?>
                                            <span><?php esc_html_e( 'Directorist account', 'directorist' ); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <span class="directorist-te-account-dropdown__status">
                                        <i class="la la-check-circle" aria-hidden="true"></i>
                                        <?php esc_html_e( 'Connected', 'directorist' ); ?>
                                    </span>
                                </div>
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
                                            <div class="directorist-te-refresh-panel__header">
                                                <strong><?php esc_html_e( 'Refresh purchases', 'directorist' ); ?></strong>
                                                <button type="button" class="directorist-te-refresh-close et-close-auth-btn" aria-label="<?php esc_attr_e( 'Close refresh form', 'directorist' ); ?>">
                                                    <i class="la la-times" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                            <label class="directorist-te-refresh-panel__label" for="directorist-te-refresh-credential">
                                                <?php
                                                echo esc_html(
                                                    'access_key' === $connection_method
                                                        ? __( 'Directorist access key', 'directorist' )
                                                        : __( 'Directorist password', 'directorist' )
                                                );
                                                ?>
                                            </label>
                                            <div class="directorist-te-refresh-form">
                                                <span class="directorist-te-password-control">
                                                    <input
                                                        type="password"
                                                        class="atbdp-form-control"
                                                        id="directorist-te-refresh-credential"
                                                        name="password"
                                                        autocomplete="<?php echo esc_attr( 'access_key' === $connection_method ? 'off' : 'current-password' ); ?>"
                                                        required
                                                        aria-describedby="directorist-te-refresh-feedback"
                                                    >
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
                                                <button type="submit" class="directorist-te-refresh-submit">
                                                    <i class="la la-refresh" aria-hidden="true"></i>
                                                    <?php esc_html_e( 'Refresh', 'directorist' ); ?>
                                                </button>
                                            </div>
                                            <div id="directorist-te-refresh-feedback" class="atbdp-form-feedback directorist-te-feedback" role="status" aria-live="polite"></div>
                                        </form>
                                    </div>
                                    <div class="purchase-refresh-btn-wrapper">
                                        <button type="button" class="directorist-te-account-dropdown__item purchase-refresh-btn">
                                            <span class="directorist-te-account-dropdown__item-icon" aria-hidden="true">
                                                <i class="la la-refresh"></i>
                                            </span>
                                            <span><?php esc_html_e( 'Refresh purchases', 'directorist' ); ?></span>
                                            <i class="la la-angle-right directorist-te-account-dropdown__item-arrow" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    <div class="directorist-te-account-dropdown__danger">
                                        <a href="#" class="directorist-te-account-dropdown__item directorist-te-account-dropdown__item--danger subscriptions-logout-btn" data-hard-logout="<?php echo esc_attr( $args['hard_logout'] ?? 0 ); ?>">
                                            <span class="directorist-te-account-dropdown__item-icon" aria-hidden="true">
                                                <i class="la la-sign-out-alt"></i>
                                            </span>
                                            <span><?php esc_html_e( 'Disconnect account', 'directorist' ); ?></span>
                                        </a>
                                    </div>
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

                <section class="directorist-te-view directorist-te-dashboard <?php echo esc_attr( 'dashboard' === $initial_view ? 'is-active' : '' ); ?>" id="directorist-te-dashboard-view" data-directorist-te-view="dashboard" <?php if ( 'dashboard' !== $initial_view ) : ?>hidden<?php endif; ?> aria-hidden="<?php echo esc_attr( 'dashboard' === $initial_view ? 'false' : 'true' ); ?>">
                    <section class="directorist-te-dashboard-welcome">
                        <div>
                            <h1><?php echo esc_html( $dashboard_welcome['title'] ?? __( 'Welcome back', 'directorist' ) ); ?></h1>
                            <p><?php echo esc_html( $dashboard_welcome['description'] ?? __( 'Your Directorist account is connected.', 'directorist' ) ); ?></p>
                        </div>
                        <div class="directorist-te-dashboard-welcome__actions">
                            <?php if ( ! empty( $dashboard_welcome['has_directories'] ) ) : ?>
                                <a class="directorist-te-btn directorist-te-btn--secondary" href="<?php echo esc_url( $dashboard_welcome['view_listings_url'] ?? home_url( '/' ) ); ?>" target="_blank" rel="noopener noreferrer">
                                    <i class="la la-external-link-alt" aria-hidden="true"></i>
                                    <?php esc_html_e( 'View directory', 'directorist' ); ?>
                                </a>
                            <?php endif; ?>
                            <a class="directorist-te-btn directorist-te-btn--primary" href="<?php echo esc_url( $dashboard_welcome['primary_action_url'] ?? home_url( '/' ) ); ?>">
                                <i class="<?php echo esc_attr( ! empty( $dashboard_welcome['has_directories'] ) ? 'la la-plus' : 'la la-folder-plus' ); ?>" aria-hidden="true"></i>
                                <?php echo esc_html( $dashboard_welcome['primary_action_text'] ?? __( 'Add listing', 'directorist' ) ); ?>
                            </a>
                        </div>
                    </section>

                    <?php if ( $dashboard_setup_visible ) : ?>
                        <?php
                        $dashboard_setup_progress    = min( 100, max( 0, (int) ( $dashboard_setup['progress'] ?? 0 ) ) );
                        $dashboard_setup_ring_length = 119.4;
                        $dashboard_setup_ring_offset = $dashboard_setup_ring_length * ( 1 - ( $dashboard_setup_progress / 100 ) );
                        $dashboard_setup_steps       = ! empty( $dashboard_setup['steps'] ) && is_array( $dashboard_setup['steps'] ) ? $dashboard_setup['steps'] : [];
                        ?>
                    <section class="directorist-te-dashboard-nudge" id="directorist-te-dashboard-nudge" data-dismiss-key="<?php echo esc_attr( $dashboard_setup_dismiss_key ); ?>">
                        <div class="directorist-te-dashboard-nudge__top">
                            <div
                                class="directorist-te-dashboard-ring"
                                aria-label="<?php echo esc_attr( sprintf( __( 'Setup progress %d percent', 'directorist' ), $dashboard_setup_progress ) ); ?>"
                            >
                                <svg width="46" height="46" viewBox="0 0 46 46" aria-hidden="true" focusable="false">
                                    <circle cx="23" cy="23" r="19" fill="none" stroke="#e5e7eb" stroke-width="4.5"></circle>
                                    <circle
                                        cx="23"
                                        cy="23"
                                        r="19"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="4.5"
                                        <?php if ( 0 === $dashboard_setup_progress ) : ?>
                                            visibility="hidden"
                                        <?php elseif ( $dashboard_setup_progress < 100 ) : ?>
                                            stroke-linecap="round"
                                            stroke-dasharray="<?php echo esc_attr( $dashboard_setup_ring_length ); ?>"
                                            stroke-dashoffset="<?php echo esc_attr( $dashboard_setup_ring_offset ); ?>"
                                        <?php endif; ?>
                                    ></circle>
                                </svg>
                                <span><?php echo esc_html( $dashboard_setup_progress . '%' ); ?></span>
                            </div>
                            <div>
                                <h2><?php echo esc_html( $dashboard_setup['title'] ?? __( 'A few steps to launch your directory', 'directorist' ) ); ?></h2>
                                <p><?php echo esc_html( $dashboard_setup['description'] ?? __( 'Complete the remaining setup tasks before accepting live submissions.', 'directorist' ) ); ?></p>
                            </div>
                            <button type="button" class="directorist-te-dashboard-nudge__dismiss" aria-label="<?php esc_attr_e( 'Dismiss setup steps', 'directorist' ); ?>">
                                <i class="la la-times" aria-hidden="true"></i>
                            </button>
                        </div>
                        <div class="directorist-te-dashboard-steps">
                            <?php foreach ( $dashboard_setup_steps as $dashboard_setup_step ) : ?>
                                <a
                                    class="directorist-te-dashboard-step <?php echo ! empty( $dashboard_setup_step['complete'] ) ? 'is-done' : ''; ?>"
                                    href="<?php echo esc_url( $dashboard_setup_step['url'] ?? '#' ); ?>"
                                >
                                    <span><i class="la la-check" aria-hidden="true"></i></span>
                                    <?php echo esc_html( $dashboard_setup_step['label'] ?? '' ); ?>
                                    <i class="la la-angle-right" aria-hidden="true"></i>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </section>
                    <?php endif; ?>

                    <?php
                    $revenue_symbol = html_entity_decode(
                        atbdp_currency_symbol( $dashboard_metrics['currency'] ?? atbdp_get_payment_currency() ),
                        ENT_QUOTES,
                        get_bloginfo( 'charset' )
                    );
                    ?>
                    <section class="directorist-te-dashboard-metrics" aria-label="<?php esc_attr_e( 'Directory metrics', 'directorist' ); ?>">
                        <div class="directorist-te-dashboard-metric">
                            <div class="directorist-te-dashboard-metric__top">
                                <span class="directorist-te-dashboard-icon directorist-te-dashboard-icon--blue"><i class="la la-th-large" aria-hidden="true"></i></span>
                                <div><strong><?php echo esc_html( number_format_i18n( (int) ( $dashboard_metrics['published_listings'] ?? 0 ) ) ); ?></strong><span><?php esc_html_e( 'Published listings', 'directorist' ); ?></span></div>
                            </div>
                            <div class="directorist-te-dashboard-metric__foot"><?php esc_html_e( 'Currently live in your directory', 'directorist' ); ?></div>
                        </div>
                        <div class="directorist-te-dashboard-metric">
                            <div class="directorist-te-dashboard-metric__top">
                                <span class="directorist-te-dashboard-icon directorist-te-dashboard-icon--teal"><i class="la la-eye" aria-hidden="true"></i></span>
                                <div><strong><?php echo esc_html( number_format_i18n( (int) ( $dashboard_metrics['listing_views'] ?? 0 ) ) ); ?></strong><span><?php esc_html_e( 'Listing views', 'directorist' ); ?></span></div>
                            </div>
                            <div class="directorist-te-dashboard-metric__foot"><?php esc_html_e( 'Across published listings', 'directorist' ); ?></div>
                        </div>
                        <div class="directorist-te-dashboard-metric directorist-te-dashboard-metric--attention">
                            <div class="directorist-te-dashboard-metric__top">
                                <span class="directorist-te-dashboard-icon directorist-te-dashboard-icon--amber"><i class="la la-clock" aria-hidden="true"></i></span>
                                <div><strong><?php echo esc_html( number_format_i18n( (int) ( $dashboard_metrics['pending_listings'] ?? 0 ) ) ); ?></strong><span><?php esc_html_e( 'Pending review', 'directorist' ); ?></span></div>
                            </div>
                            <div class="directorist-te-dashboard-metric__foot">
                                <?php
                                printf(
                                    /* translators: %s: Number of listings expiring this week. */
                                    esc_html( _n( '%s listing expires this week', '%s listings expire this week', (int) ( $dashboard_metrics['expiring_this_week'] ?? 0 ), 'directorist' ) ),
                                    esc_html( number_format_i18n( (int) ( $dashboard_metrics['expiring_this_week'] ?? 0 ) ) )
                                );
                                ?>
                                <?php if ( (int) ( $dashboard_metrics['pending_listings'] ?? 0 ) > 0 ) : ?>
                                    <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=at_biz_dir&post_status=pending' ) ); ?>"><?php esc_html_e( 'Review now', 'directorist' ); ?></a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="directorist-te-dashboard-metric">
                            <div class="directorist-te-dashboard-metric__top">
                                <span class="directorist-te-dashboard-icon directorist-te-dashboard-icon--violet"><i class="la la-dollar" aria-hidden="true"></i></span>
                                <div><strong><?php echo esc_html( $revenue_symbol . number_format_i18n( (float) ( $dashboard_metrics['revenue'] ?? 0 ), 2 ) ); ?></strong><span><?php esc_html_e( 'Revenue', 'directorist' ); ?></span></div>
                            </div>
                            <div class="directorist-te-dashboard-metric__foot">
                                <?php
                                printf(
                                    /* translators: %s: Number of paid orders in the last 30 days. */
                                    esc_html( _n( '%s paid order in the last 30 days', '%s paid orders in the last 30 days', (int) ( $dashboard_metrics['paid_orders'] ?? 0 ), 'directorist' ) ),
                                    esc_html( number_format_i18n( (int) ( $dashboard_metrics['paid_orders'] ?? 0 ) ) )
                                );
                                ?>
                            </div>
                        </div>
                    </section>

                    <section class="directorist-te-dashboard-grid">
                        <?php
                        $quick_action_directories = ! empty( $dashboard_quick_actions['directories'] ) && is_array( $dashboard_quick_actions['directories'] )
                            ? $dashboard_quick_actions['directories']
                            : [];
                        $quick_action_default_id  = (string) ( $dashboard_quick_actions['default_id'] ?? '' );
                        $quick_action_directory   = $quick_action_directories ? $quick_action_directories[0] : [];

                        foreach ( $quick_action_directories as $quick_action_directory_item ) {
                            if ( (string) ( $quick_action_directory_item['id'] ?? '' ) === $quick_action_default_id ) {
                                $quick_action_directory = $quick_action_directory_item;
                                break;
                            }
                        }

                        $quick_action_items = ! empty( $quick_action_directory['actions'] ) && is_array( $quick_action_directory['actions'] )
                            ? array_values( $quick_action_directory['actions'] )
                            : [];

                        if ( empty( $quick_action_items ) && ! empty( $dashboard_quick_actions['create_directory'] ) ) {
                            $quick_action_items[] = $dashboard_quick_actions['create_directory'];
                        }

                        if ( ! empty( $dashboard_quick_actions['email'] ) ) {
                            $quick_action_items[] = $dashboard_quick_actions['email'];
                        }
                        ?>
                        <div
                            class="directorist-te-dashboard-card"
                            data-directorist-te-quick-actions
                            data-directory-change-message="<?php echo esc_attr( __( 'Quick actions now use %s.', 'directorist' ) ); ?>"
                        >
                            <div class="directorist-te-dashboard-card__head directorist-te-dashboard-card__head--quick-actions">
                                <i class="la la-bolt" aria-hidden="true"></i>
                                <h2><?php esc_html_e( 'Quick actions', 'directorist' ); ?></h2>
                                <?php if ( count( $quick_action_directories ) > 1 ) : ?>
                                    <div class="directorist-te-dashboard-directory-control">
                                        <label for="directorist-te-quick-actions-directory"><?php esc_html_e( 'Directory', 'directorist' ); ?></label>
                                        <select id="directorist-te-quick-actions-directory" data-quick-actions-directory-select>
                                            <?php foreach ( $quick_action_directories as $quick_action_directory_item ) : ?>
                                                <?php $directory_actions = $quick_action_directory_item['actions'] ?? []; ?>
                                                <option
                                                    value="<?php echo esc_attr( $quick_action_directory_item['id'] ?? '' ); ?>"
                                                    data-directory-name="<?php echo esc_attr( $quick_action_directory_item['name'] ?? '' ); ?>"
                                                    data-add-listing-url="<?php echo esc_url( $directory_actions['add-listing']['url'] ?? '' ); ?>"
                                                    data-add-listing-description="<?php echo esc_attr( $directory_actions['add-listing']['description'] ?? '' ); ?>"
                                                    data-add-listing-aria-label="<?php echo esc_attr( $directory_actions['add-listing']['aria_label'] ?? '' ); ?>"
                                                    data-manage-categories-url="<?php echo esc_url( $directory_actions['manage-categories']['url'] ?? '' ); ?>"
                                                    data-manage-categories-aria-label="<?php echo esc_attr( $directory_actions['manage-categories']['aria_label'] ?? '' ); ?>"
                                                    data-listing-layout-url="<?php echo esc_url( $directory_actions['listing-layout']['url'] ?? '' ); ?>"
                                                    data-listing-layout-aria-label="<?php echo esc_attr( $directory_actions['listing-layout']['aria_label'] ?? '' ); ?>"
                                                    data-submission-form-url="<?php echo esc_url( $directory_actions['submission-form']['url'] ?? '' ); ?>"
                                                    data-submission-form-aria-label="<?php echo esc_attr( $directory_actions['submission-form']['aria_label'] ?? '' ); ?>"
                                                    <?php selected( (string) ( $quick_action_directory_item['id'] ?? '' ), $quick_action_default_id ); ?>
                                                >
                                                    <?php echo esc_html( $quick_action_directory_item['name'] ?? '' ); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <span class="screen-reader-text" data-quick-actions-live role="status" aria-live="polite"></span>
                                <?php endif; ?>
                            </div>
                            <div class="directorist-te-dashboard-actions">
                                <?php foreach ( $quick_action_items as $quick_action_item ) : ?>
                                    <?php
                                    $quick_action_key = sanitize_key( $quick_action_item['key'] ?? '' );

                                    if ( ! $quick_action_key || empty( $quick_action_item['url'] ) ) {
                                        continue;
                                    }
                                    ?>
                                    <a
                                        href="<?php echo esc_url( $quick_action_item['url'] ); ?>"
                                        class="directorist-te-dashboard-action directorist-te-dashboard-action--<?php echo esc_attr( sanitize_html_class( $quick_action_key ) ); ?>"
                                        data-quick-action="<?php echo esc_attr( $quick_action_key ); ?>"
                                        aria-label="<?php echo esc_attr( $quick_action_item['aria_label'] ?? $quick_action_item['label'] ?? '' ); ?>"
                                    >
                                        <span><i class="<?php echo esc_attr( $quick_action_item['icon'] ?? 'la la-angle-right' ); ?>" aria-hidden="true"></i></span>
                                        <strong><?php echo esc_html( $quick_action_item['label'] ?? '' ); ?></strong>
                                        <em><?php echo esc_html( $quick_action_item['description'] ?? '' ); ?></em>
                                        <i class="la la-angle-right" aria-hidden="true"></i>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="directorist-te-dashboard-card" data-directorist-te-activity-card>
                            <div class="directorist-te-dashboard-card__head">
                                <i class="la la-history" aria-hidden="true"></i>
                                <h2><?php esc_html_e( 'Recent activity', 'directorist' ); ?></h2>
                                <button
                                    type="button"
                                    class="directorist-te-dashboard-activity-view-all"
                                    data-activity-drawer-open
                                    aria-haspopup="dialog"
                                    aria-controls="directorist-te-activity-drawer"
                                >
                                    <?php esc_html_e( 'View all', 'directorist' ); ?>
                                </button>
                            </div>
                            <div class="directorist-te-dashboard-activity">
                                <?php if ( $dashboard_activity_items ) : ?>
                                    <?php foreach ( $dashboard_activity_items as $activity_item ) : ?>
                                        <article class="directorist-te-dashboard-activity-item">
                                            <span class="directorist-te-dashboard-activity-icon directorist-te-dashboard-activity-icon--<?php echo esc_attr( $activity_item['tone'] ?? 'blue' ); ?>">
                                                <i class="<?php echo esc_attr( $activity_item['icon'] ?? 'la la-history' ); ?>" aria-hidden="true"></i>
                                            </span>
                                            <div class="directorist-te-dashboard-activity-copy">
                                                <strong><?php echo esc_html( $activity_item['title'] ?? '' ); ?></strong>
                                                <span class="directorist-te-dashboard-activity-summary">
                                                    <?php if ( ! empty( $activity_item['subject'] ) ) : ?>
                                                        <b><?php echo esc_html( $activity_item['subject'] ); ?></b>
                                                    <?php endif; ?>
                                                    <?php if ( ! empty( $activity_item['context'] ) ) : ?>
                                                        <span><?php echo esc_html( $activity_item['context'] ); ?></span>
                                                    <?php endif; ?>
                                                </span>
                                                <small>
                                                    <i class="la la-clock" aria-hidden="true"></i>
                                                    <?php echo esc_html( $activity_item['time_label'] ?? '' ); ?>
                                                </small>
                                            </div>
                                            <?php if ( ! empty( $activity_item['action_url'] ) && ! empty( $activity_item['action_label'] ) ) : ?>
                                                <a class="directorist-te-btn directorist-te-btn--soft" href="<?php echo esc_url( $activity_item['action_url'] ); ?>">
                                                    <?php echo esc_html( $activity_item['action_label'] ); ?>
                                                </a>
                                            <?php endif; ?>
                                        </article>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <div class="directorist-te-dashboard-activity-empty">
                                        <i class="la la-check-circle" aria-hidden="true"></i>
                                        <p><?php esc_html_e( 'No recent Directorist activity was found.', 'directorist' ); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </section>

                    <?php if ( ! empty( $dashboard_recommendations['directories'] ) ) : ?>
                        <?php
                        $recommendation_directories = $dashboard_recommendations['directories'];
                        $recommendation_default_id  = (string) ( $dashboard_recommendations['default_id'] ?? $recommendation_directories[0]['id'] );
                        $recommendation_default     = $recommendation_directories[0];

                        foreach ( $recommendation_directories as $recommendation_directory ) {
                            if ( (string) ( $recommendation_directory['id'] ?? '' ) === $recommendation_default_id ) {
                                $recommendation_default = $recommendation_directory;
                                break;
                            }
                        }

                        $recommendation_default_name = (string) ( $recommendation_default['name'] ?? __( 'your directory', 'directorist' ) );
                        ?>
                        <section
                            class="directorist-te-dashboard-recommendations"
                            data-directorist-te-recommendations
                            data-default-directory="<?php echo esc_attr( $recommendation_default_id ); ?>"
                            data-heading-template="<?php echo esc_attr( __( 'Recommended for %s', 'directorist' ) ); ?>"
                            data-rotation-interval="6000"
                            role="region"
                            aria-roledescription="<?php esc_attr_e( 'carousel', 'directorist' ); ?>"
                            aria-label="<?php esc_attr_e( 'Directory extension recommendations', 'directorist' ); ?>"
                        >
                            <div class="directorist-te-dashboard-recommendations__head">
                                <div class="directorist-te-dashboard-recommendations__heading">
                                    <i class="la la-magic" aria-hidden="true"></i>
                                    <div>
                                        <h2 data-recommendation-heading>
                                            <?php
                                            printf(
                                                /* translators: %s: Directory type name. */
                                                esc_html__( 'Recommended for %s', 'directorist' ),
                                                esc_html( $recommendation_default_name )
                                            );
                                            ?>
                                        </h2>
                                        <p data-recommendation-description><?php echo esc_html( $recommendation_default['description'] ?? '' ); ?></p>
                                    </div>
                                </div>
                                <div class="directorist-te-dashboard-recommendations__tools">
                                    <span><?php esc_html_e( "Based on what you're building", 'directorist' ); ?></span>
                                    <div class="directorist-te-recommendation-directory-controls">
                                        <label for="directorist-te-recommendation-directory">
                                            <?php esc_html_e( 'Directory', 'directorist' ); ?>
                                        </label>
                                        <select
                                            id="directorist-te-recommendation-directory"
                                            class="directorist-te-recommendation-directory-select"
                                            data-recommendation-directory-select
                                        >
                                            <?php foreach ( $recommendation_directories as $recommendation_directory ) : ?>
                                                <option
                                                    value="<?php echo esc_attr( $recommendation_directory['id'] ?? '' ); ?>"
                                                    <?php selected( (string) ( $recommendation_directory['id'] ?? '' ), $recommendation_default_id ); ?>
                                                >
                                                    <?php echo esc_html( $recommendation_directory['name'] ?? '' ); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <span class="directorist-te-recommendation-directory-navigation">
                                            <button
                                                type="button"
                                                class="directorist-te-recommendation-control"
                                                data-recommendation-previous
                                                aria-label="<?php esc_attr_e( 'Previous directory', 'directorist' ); ?>"
                                                title="<?php esc_attr_e( 'Previous directory', 'directorist' ); ?>"
                                            >
                                                <i class="la la-angle-left" aria-hidden="true"></i>
                                            </button>
                                            <button
                                                type="button"
                                                class="directorist-te-recommendation-control"
                                                data-recommendation-next
                                                aria-label="<?php esc_attr_e( 'Next directory', 'directorist' ); ?>"
                                                title="<?php esc_attr_e( 'Next directory', 'directorist' ); ?>"
                                            >
                                                <i class="la la-angle-right" aria-hidden="true"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="screen-reader-text" data-recommendation-live role="status" aria-live="polite"></div>
                            <div class="directorist-te-dashboard-recommendations__grid">
                                <?php foreach ( $recommendation_directories as $recommendation_directory ) : ?>
                                    <div
                                        class="directorist-te-dashboard-recommendation-group"
                                        data-recommendation-group="<?php echo esc_attr( $recommendation_directory['id'] ?? '' ); ?>"
                                        data-directory-name="<?php echo esc_attr( $recommendation_directory['name'] ?? '' ); ?>"
                                        data-directory-description="<?php echo esc_attr( $recommendation_directory['description'] ?? '' ); ?>"
                                        <?php echo (string) ( $recommendation_directory['id'] ?? '' ) === $recommendation_default_id ? '' : 'hidden'; ?>
                                    >
                                        <?php foreach ( $recommendation_directory['items'] as $recommendation_index => $recommendation_item ) : ?>
                                            <article
                                                class="directorist-te-dashboard-recommendation"
                                                data-recommendation-card="<?php echo esc_attr( $recommendation_index ); ?>"
                                                <?php echo $recommendation_index < 3 ? '' : 'hidden'; ?>
                                            >
                                                <div class="directorist-te-dashboard-recommendation-top">
                                                    <span class="directorist-te-dashboard-recommendation-icon">
                                                        <?php if ( ! empty( $recommendation_item['image'] ) ) : ?>
                                                            <img src="<?php echo esc_url( $recommendation_item['image'] ); ?>" alt="">
                                                        <?php else : ?>
                                                            <i class="la la-puzzle-piece" aria-hidden="true"></i>
                                                        <?php endif; ?>
                                                    </span>
                                                    <h3><?php echo esc_html( $recommendation_item['name'] ?? '' ); ?></h3>
                                                </div>
                                                <p><?php echo esc_html( $recommendation_item['reason'] ?? '' ); ?></p>
                                                <div class="directorist-te-dashboard-recommendation__footer">
                                                    <span class="directorist-te-dashboard-recommendation__status directorist-te-dashboard-recommendation__status--<?php echo esc_attr( $recommendation_item['status'] ?? 'marketplace' ); ?>">
                                                        <i aria-hidden="true"></i>
                                                        <?php echo esc_html( $recommendation_item['label'] ?? '' ); ?>
                                                    </span>
                                                    <?php
                                                    if ( ! empty( $recommendation_item['action'] ) ) {
                                                        $render_action( $recommendation_item['action'] );
                                                    }
                                                    ?>
                                                </div>
                                            </article>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </section>
                    <?php endif; ?>

                    <footer class="directorist-te-dashboard-footer">
                        <?php
                        echo esc_html(
                            $plugin_version
                                ? sprintf(
                                    /* translators: %s: Installed Directorist plugin version. */
                                    __( 'Directorist %s', 'directorist' ),
                                    $plugin_version
                                )
                                : __( 'Directorist', 'directorist' )
                        );
                        ?>
                        <span aria-hidden="true">·</span>
                        <?php echo esc_html( $account_plan_label ); ?>
                        <span aria-hidden="true">·</span>
                        <a href="<?php echo esc_url( $whats_new_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'What\'s new', 'directorist' ); ?></a>
                    </footer>
                </section>

                <div
                    class="directorist-te-activity-drawer"
                    id="directorist-te-activity-drawer"
                    data-directorist-te-activity-drawer
                    data-loading-label="<?php esc_attr_e( 'Loading activity...', 'directorist' ); ?>"
                    data-loading-more-label="<?php esc_attr_e( 'Loading more activity...', 'directorist' ); ?>"
                    data-empty-title="<?php esc_attr_e( 'No activity found', 'directorist' ); ?>"
                    data-empty-message="<?php esc_attr_e( 'There is no Directorist activity in this category yet.', 'directorist' ); ?>"
                    data-error-title="<?php esc_attr_e( 'Unable to load activity', 'directorist' ); ?>"
                    data-error-message="<?php esc_attr_e( 'Activity could not be loaded. Close the panel and try again.', 'directorist' ); ?>"
                    hidden
                    aria-hidden="true"
                >
                    <div
                        class="directorist-te-activity-drawer__backdrop"
                        data-activity-drawer-close
                        aria-hidden="true"
                    ></div>
                    <section
                        class="directorist-te-activity-drawer__panel"
                        role="dialog"
                        aria-modal="true"
                        aria-labelledby="directorist-te-activity-drawer-title"
                    >
                        <header class="directorist-te-activity-drawer__header">
                            <div>
                                <span class="directorist-te-eyebrow"><?php esc_html_e( 'Directorist', 'directorist' ); ?></span>
                                <h2 id="directorist-te-activity-drawer-title"><?php esc_html_e( 'Activity', 'directorist' ); ?></h2>
                            </div>
                            <button
                                type="button"
                                class="directorist-te-activity-drawer__close"
                                data-activity-drawer-close
                                aria-label="<?php esc_attr_e( 'Close activity panel', 'directorist' ); ?>"
                            >
                                <i class="la la-times" aria-hidden="true"></i>
                            </button>
                        </header>
                        <div class="directorist-te-activity-drawer__filters" role="group" aria-label="<?php esc_attr_e( 'Filter activity', 'directorist' ); ?>">
                            <button type="button" class="is-active" data-activity-filter="all" aria-pressed="true"><?php esc_html_e( 'All', 'directorist' ); ?></button>
                            <button type="button" data-activity-filter="listing" aria-pressed="false"><?php esc_html_e( 'Listings', 'directorist' ); ?></button>
                            <button type="button" data-activity-filter="review" aria-pressed="false"><?php esc_html_e( 'Reviews', 'directorist' ); ?></button>
                            <button type="button" data-activity-filter="payment" aria-pressed="false"><?php esc_html_e( 'Payments', 'directorist' ); ?></button>
                            <button type="button" data-activity-filter="user" aria-pressed="false"><?php esc_html_e( 'Users', 'directorist' ); ?></button>
                        </div>
                        <div class="directorist-te-activity-drawer__body" data-activity-drawer-list></div>
                        <div class="directorist-te-activity-drawer__state" data-activity-drawer-state role="status" aria-live="polite"></div>
                        <footer class="directorist-te-activity-drawer__footer">
                            <button type="button" class="directorist-te-btn directorist-te-btn--secondary" data-activity-load-more hidden>
                                <?php esc_html_e( 'Load more', 'directorist' ); ?>
                            </button>
                        </footer>
                    </section>
                </div>

                <section class="directorist-te-view directorist-te-view--addons <?php echo esc_attr( 'addons' === $initial_view ? 'is-active' : '' ); ?>" id="directorist-te-addons-view" data-directorist-te-view="addons" <?php if ( 'addons' !== $initial_view ) : ?>hidden<?php endif; ?> aria-hidden="<?php echo esc_attr( 'addons' === $initial_view ? 'false' : 'true' ); ?>">
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
                        data-access-key-required="<?php esc_attr_e( 'Enter your Directorist account access key.', 'directorist' ); ?>"
                        data-invalid-access-key="<?php esc_attr_e( 'The access key is invalid. Check the key in your Directorist account and try again.', 'directorist' ); ?>"
                        data-invalid-credentials="<?php esc_attr_e( 'The username, email address, or password is incorrect. Please check your details and try again.', 'directorist' ); ?>"
                        data-unexpected-error="<?php esc_attr_e( 'Could not connect. Please check your details and try again.', 'directorist' ); ?>"
                        data-network-error="<?php esc_attr_e( 'Could not reach Directorist.com. Please try again.', 'directorist' ); ?>"
                    >
                        <div class="atbdp-form-page">
                            <input type="hidden" name="auth_method" value="account">
                            <div class="directorist-te-auth-methods" role="tablist" aria-label="<?php esc_attr_e( 'Choose a Directorist account connection method', 'directorist' ); ?>">
                                <button type="button" class="is-active" role="tab" aria-selected="true" aria-controls="directorist-te-auth-account" data-auth-method="account">
                                    <?php esc_html_e( 'Account login', 'directorist' ); ?>
                                </button>
                                <button type="button" role="tab" aria-selected="false" aria-controls="directorist-te-auth-access-key" data-auth-method="access_key">
                                    <?php esc_html_e( 'Access key', 'directorist' ); ?>
                                </button>
                            </div>
                            <div id="directorist-te-auth-account" class="directorist-te-auth-panel" role="tabpanel" data-auth-panel="account">
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
                            </div>
                            <div id="directorist-te-auth-access-key" class="directorist-te-auth-panel" role="tabpanel" data-auth-panel="access_key" hidden>
                                <div class="directorist-te-field-row directorist-te-field-row--single">
                                    <label>
                                        <span><?php esc_html_e( 'Directorist access key', 'directorist' ); ?></span>
                                        <span class="directorist-te-password-control">
                                            <input type="password" name="access_key" id="directorist-te-access-key" autocomplete="off" placeholder="<?php esc_attr_e( 'Paste your access key', 'directorist' ); ?>" aria-describedby="directorist-te-access-key-help directorist-te-connect-feedback" disabled>
                                            <button
                                                type="button"
                                                class="directorist-te-password-toggle"
                                                aria-label="<?php esc_attr_e( 'Show access key', 'directorist' ); ?>"
                                                aria-pressed="false"
                                                data-show-label="<?php esc_attr_e( 'Show access key', 'directorist' ); ?>"
                                                data-hide-label="<?php esc_attr_e( 'Hide access key', 'directorist' ); ?>"
                                            >
                                                <i class="la la-eye" aria-hidden="true"></i>
                                            </button>
                                        </span>
                                        <small id="directorist-te-access-key-help">
                                            <?php esc_html_e( 'Use the access key from your', 'directorist' ); ?>
                                            <a href="<?php echo esc_url( apply_filters( 'directorist_access_key_dashboard_url', 'https://directorist.com/dashboard/' ) ); ?>" target="_blank" rel="noopener noreferrer">
                                                <?php esc_html_e( 'Directorist account dashboard', 'directorist' ); ?>
                                            </a>
                                        </small>
                                    </label>
                                </div>
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
                        <button type="button" class="directorist-te-tab <?php echo esc_attr( 'all' === $initial_type ? 'is-active' : '' ); ?>" data-filter-type="all"><?php esc_html_e( 'All', 'directorist' ); ?> <span><?php echo esc_html( $total_rows ); ?></span></button>
                        <button type="button" class="directorist-te-tab <?php echo esc_attr( 'extension' === $initial_type ? 'is-active' : '' ); ?>" data-filter-type="extension"><?php esc_html_e( 'Extensions', 'directorist' ); ?> <span><?php echo esc_html( $extension_rows ); ?></span></button>
                        <button type="button" class="directorist-te-tab <?php echo esc_attr( 'theme' === $initial_type ? 'is-active' : '' ); ?>" data-filter-type="theme"><?php esc_html_e( 'Themes', 'directorist' ); ?> <span><?php echo esc_html( $theme_rows ); ?></span></button>
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
                    <div class="directorist-te-toolbar-row directorist-te-toolbar-row--filters" id="atbdp-required-extensions-form" tabindex="-1">
                        <div class="directorist-te-segmented" aria-label="<?php esc_attr_e( 'Product status', 'directorist' ); ?>">
                            <button type="button" class="is-active" data-filter-status="all"><?php esc_html_e( 'All', 'directorist' ); ?></button>
                            <button type="button" data-filter-status="installed"><?php esc_html_e( 'Installed', 'directorist' ); ?> <span class="directorist-te-status-count" data-status-count="installed"><?php echo esc_html( $installed_rows ); ?></span></button>
                            <button type="button" data-filter-status="not-installed"><?php esc_html_e( 'Not installed', 'directorist' ); ?> <span class="directorist-te-status-count" data-status-count="not-installed"><?php echo esc_html( $not_installed_rows ); ?></span></button>
                            <?php if ( $required_rows ) : ?>
                                <button type="button" data-filter-status="required"><?php esc_html_e( 'Required', 'directorist' ); ?> <span class="directorist-te-status-count directorist-te-status-count--required" data-status-count="required"><?php echo esc_html( $required_rows ); ?></span></button>
                            <?php endif; ?>
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
