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

    $badges = [];
    foreach ( [ 'badge', 'badges', 'product_badge', 'product_badges', 'product_status', 'release_status' ] as $badge_key ) {
        if ( empty( $product[ $badge_key ] ) ) {
            continue;
        }

        $badge_values = is_array( $product[ $badge_key ] ) ? $product[ $badge_key ] : [ $product[ $badge_key ] ];
        foreach ( $badge_values as $badge_value ) {
            if ( is_array( $badge_value ) ) {
                $badge_value = $badge_value['label'] ?? $badge_value['name'] ?? '';
            }

            if ( is_scalar( $badge_value ) && '' !== trim( (string) $badge_value ) ) {
                $badges[] = trim( (string) $badge_value );
            }
        }
    }

    return array_values( array_unique( $badges ) );
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
    ?>
    <span class="directorist-te-status directorist-te-status--<?php echo esc_attr( $status ); ?>"><?php echo esc_html( $label ); ?></span>
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
        $is_active     = is_plugin_active( $extension_base );
        $is_required   = isset( $required_lookup[ $extension_base ] ) || isset( $required_lookup[ $extension_key ] );
        $status        = $has_update ? 'update installed active' : ( $is_active ? 'active installed' : 'installed' );
        $status_label  = $has_update ? __( 'Update available', 'directorist' ) : ( $is_active ? __( 'Active', 'directorist' ) : __( 'Installed', 'directorist' ) );
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

        $menu = [
            [
                'label' => __( 'Settings', 'directorist' ),
                'href'  => $args['settings_url'],
                'class' => 'directorist-te-menu-link',
            ],
        ];

        if ( $is_active ) {
            $menu[] = [
                'label' => __( 'Deactivate', 'directorist' ),
                'class' => 'directorist-te-menu-link directorist-te-single-plugin-task',
                'attrs' => [
                    'data-task'   => 'deactivate',
                    'data-target' => $extension_base,
                ],
            ];
        }

        $menu[] = [
            'label' => __( 'Uninstall', 'directorist' ),
            'class' => 'directorist-te-menu-link directorist-te-menu-link--danger ext-action-uninstall',
            'attrs' => [ 'data-target' => $extension_base ],
        ];

        $badges = $get_product_badges( $product );
        if ( $is_required ) {
            $badges[] = __( 'Required', 'directorist' );
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
                'bulk'        => [
                    'id'    => $extension_base,
                    'name'  => $extension_base,
                    'class' => 'extension-name-checkbox directorist-te-check',
                ],
            ]
        );
    }
}

if ( $is_logged_in && ! empty( $args['current_active_theme_info'] ) && is_array( $args['current_active_theme_info'] ) ) {
    $active_theme = $args['current_active_theme_info'];
    $theme_key    = ! empty( $active_theme['stylesheet'] ) ? $active_theme['stylesheet'] : sanitize_title( $active_theme['name'] ?? '' );
    $product      = $themes[ $theme_key ] ?? [];
    $has_update   = ! empty( $active_theme['has_update'] );

    $add_row(
        [
            'key'         => 'theme-active-' . $theme_key,
            'type'        => 'theme',
            'status'      => $has_update ? 'update active installed' : 'active installed',
            'name'        => $active_theme['name'] ?? __( 'Active Theme', 'directorist' ),
            'version'     => $active_theme['version'] ?? '',
            'description' => $product['description'] ?? __( 'Currently active WordPress theme for this site.', 'directorist' ),
            'image'       => ! empty( $active_theme['thumbnail'] ) ? $active_theme['thumbnail'] : $get_image( $product ),
            'badges'      => array_merge( $get_product_badges( $product ), [ __( 'Active theme', 'directorist' ) ] ),
            'statusLabel' => $has_update ? __( 'Update available', 'directorist' ) : __( 'Active', 'directorist' ),
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
            'menu'        => [
                [
                    'label'    => __( 'Customize', 'directorist' ),
                    'href'     => $active_theme['customizer_link'] ?? '#',
                    'class'    => 'directorist-te-menu-link',
                    'external' => true,
                ],
            ],
        ]
    );
}

if ( $is_logged_in && ! empty( $args['extensions_available_in_subscriptions'] ) && is_array( $args['extensions_available_in_subscriptions'] ) ) {
    foreach ( $args['extensions_available_in_subscriptions'] as $extension_key => $extension ) {
        $product     = array_merge( $get_extension_product( $extension_key ), is_array( $extension ) ? $extension : [] );
        $name        = $product['title'] ?? $product['name'] ?? $extension_key;
        $description = $product['description'] ?? '';

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
                    <nav class="directorist-te-nav" aria-label="<?php esc_attr_e( 'Themes and extensions resources', 'directorist' ); ?>">
                        <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=at_biz_dir' ) ); ?>"><?php esc_html_e( 'Dashboard', 'directorist' ); ?></a>
                        <a href="#" class="active" aria-current="page"><?php esc_html_e( 'Themes & Extensions', 'directorist' ); ?></a>
                        <a href="https://directorist.com/documentation/directorist/" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Docs', 'directorist' ); ?></a>
                        <a href="https://www.youtube.com/@wpdirectorist" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Tutorials', 'directorist' ); ?></a>
                        <a href="https://directorist.com/contact/" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Support', 'directorist' ); ?></a>
                    </nav>
                    <div class="directorist-te-top-right">
                        <label class="directorist-te-top-search">
                            <span class="screen-reader-text"><?php esc_html_e( 'Search Directorist', 'directorist' ); ?></span>
                            <i class="la la-search" aria-hidden="true"></i>
                            <input type="search" placeholder="<?php esc_attr_e( 'Search...', 'directorist' ); ?>">
                        </label>
                        <span class="directorist-te-header-icon" aria-hidden="true"><i class="la la-bell"></i><span></span></span>
                        <span class="directorist-te-avatar" aria-hidden="true">
                            <?php echo get_avatar( get_current_user_id(), 32 ); ?>
                            <i class="la la-angle-down"></i>
                        </span>
                    </div>
                <?php else : ?>
                    <nav class="directorist-te-resource-links" aria-label="<?php esc_attr_e( 'Directorist resources', 'directorist' ); ?>">
                        <a href="https://directorist.com/documentation/directorist/" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Docs, opens in a new tab', 'directorist' ); ?>"><?php esc_html_e( 'Docs', 'directorist' ); ?></a>
                        <a href="https://www.youtube.com/@wpdirectorist" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Tutorials, opens in a new tab', 'directorist' ); ?>"><?php esc_html_e( 'Tutorials', 'directorist' ); ?></a>
                        <a href="https://directorist.com/contact/" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Support, opens in a new tab', 'directorist' ); ?>"><?php esc_html_e( 'Support', 'directorist' ); ?></a>
                    </nav>
                <?php endif; ?>
            </header>

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
                        data-username-required="<?php esc_attr_e( 'Enter your Directorist account username or email.', 'directorist' ); ?>"
                        data-password-required="<?php esc_attr_e( 'Enter your Directorist account password.', 'directorist' ); ?>"
                        data-unexpected-error="<?php esc_attr_e( 'Could not connect. Please check your details and try again.', 'directorist' ); ?>"
                        data-network-error="<?php esc_attr_e( 'Could not reach Directorist.com. Please try again.', 'directorist' ); ?>"
                    >
                        <div class="atbdp-form-page">
                            <div class="directorist-te-field-row">
                                <label>
                                    <span><?php esc_html_e( 'Username', 'directorist' ); ?></span>
                                    <input type="text" name="username" id="username" autocomplete="username" aria-describedby="directorist-te-connect-feedback">
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
            <?php else : ?>
                <section class="directorist-te-account">
                    <div class="directorist-te-stat">
                        <span><?php echo esc_html( $args['total_active_extensions'] ?? 0 ); ?></span>
                        <p><?php esc_html_e( 'Active extensions', 'directorist' ); ?></p>
                    </div>
                    <div class="directorist-te-stat">
                        <span><?php echo esc_html( $args['total_available_extensions'] ?? 0 ); ?></span>
                        <p><?php esc_html_e( 'Available extensions', 'directorist' ); ?></p>
                    </div>
                    <div class="directorist-te-stat">
                        <span><?php echo esc_html( $args['total_available_themes'] ?? 0 ); ?></span>
                        <p><?php esc_html_e( 'Available themes', 'directorist' ); ?></p>
                    </div>
                    <div class="directorist-te-account__actions">
                        <div class="et-auth-section directorist-te-refresh-panel">
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
                            <a href="#" class="directorist-te-btn directorist-te-btn--secondary purchase-refresh-btn">
                                <i class="la la-refresh" aria-hidden="true"></i>
                                <?php esc_html_e( 'Refresh Purchase', 'directorist' ); ?>
                            </a>
                        </div>
                        <a href="#" class="directorist-te-btn directorist-te-btn--ghost subscriptions-logout-btn" data-hard-logout="<?php echo esc_attr( $args['hard_logout'] ?? 0 ); ?>">
                            <i class="fas fa-sign-out-alt" aria-hidden="true"></i>
                            <?php esc_html_e( 'Logout', 'directorist' ); ?>
                        </a>
                    </div>
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
                            <button type="button" data-filter-status="update"><?php esc_html_e( 'Updates', 'directorist' ); ?><?php if ( $update_rows ) : ?> <span><?php echo esc_html( $update_rows ); ?></span><?php endif; ?></button>
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
                            <button type="button" class="directorist-te-bulk-action" data-task="update"><i class="la la-sync" aria-hidden="true"></i><?php esc_html_e( 'Update', 'directorist' ); ?></button>
                            <button type="button" class="directorist-te-bulk-action" data-task="activate"><?php esc_html_e( 'Activate', 'directorist' ); ?></button>
                            <button type="button" class="directorist-te-bulk-action" data-task="deactivate"><?php esc_html_e( 'Deactivate', 'directorist' ); ?></button>
                            <button type="button" class="directorist-te-bulk-action directorist-te-bulk-action--danger" data-task="uninstall"><i class="la la-trash" aria-hidden="true"></i><?php esc_html_e( 'Delete', 'directorist' ); ?></button>
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
                                        <span class="screen-reader-text"><?php esc_html_e( 'Select all installed extensions', 'directorist' ); ?></span>
                                    </label>
                                <?php endif; ?>
                                <span class="directorist-te-list-head__addon"><?php esc_html_e( 'Add-on', 'directorist' ); ?></span>
                                <span class="directorist-te-list-head__status"><?php esc_html_e( 'Status', 'directorist' ); ?></span>
                            </div>
                            <?php foreach ( $rows as $row ) : ?>
                                <article class="directorist-te-row" data-product-type="<?php echo esc_attr( $row['type'] ); ?>" data-product-status="<?php echo esc_attr( $row['status'] ); ?>" data-search-text="<?php echo esc_attr( strtolower( wp_strip_all_tags( $row['name'] . ' ' . $row['description'] ) ) ); ?>">
                                    <?php if ( $is_logged_in ) : ?>
                                        <div class="directorist-te-row__select">
                                            <?php if ( ! empty( $row['bulk'] ) ) : ?>
                                                <label class="directorist-te-checkbox">
                                                    <input type="checkbox" id="<?php echo esc_attr( $row['bulk']['id'] ); ?>" name="<?php echo esc_attr( $row['bulk']['name'] ); ?>" class="<?php echo esc_attr( $row['bulk']['class'] ); ?>">
                                                    <i class="la la-check" aria-hidden="true"></i>
                                                    <span class="screen-reader-text"><?php echo esc_html( $row['name'] ); ?></span>
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
                                            <span class="directorist-te-type directorist-te-type--<?php echo esc_attr( $row['type'] ); ?>">
                                                <?php echo esc_html( 'theme' === $row['type'] ? __( 'Theme', 'directorist' ) : __( 'Extension', 'directorist' ) ); ?>
                                            </span>
                                            <?php if ( ! empty( $row['version'] ) ) : ?>
                                                <span class="directorist-te-version"><?php echo esc_html( 'v' . $row['version'] ); ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <?php if ( ! empty( $row['badges'] ) ) : ?>
                                        <div class="directorist-te-row__meta">
                                            <?php foreach ( $row['badges'] as $badge ) : ?>
                                                <span class="directorist-te-badge"><?php echo esc_html( $badge ); ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                        <?php endif; ?>
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
        </div>
    </div>
</div>
