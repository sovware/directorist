<?php

use Directorist\Core\API;
/**
 * Licensing helper functions.
 */
defined( 'ABSPATH' ) || exit;

function directorist_licensing_data(): array {
	$data = (array) get_option( 'directorist_licensing_account_data' );

	return $data ?? [];
}

function directorist_licensing_is_connected(): bool {
	$data = directorist_licensing_data();

	return isset( $data['account_data']['user_id'] );
}

function directorist_licensing_connection_method(): string {
	$data = directorist_licensing_data();

	return $data['method'] ?? 'access_token';
}

function directorist_licensing_get_disconnect_url(): string {
	$url = add_query_arg(
		[
			'post_type' => 'at_biz_dir',
			'page'      => 'directorist-licensing',
			'logout'    => 'true',
		],
		admin_url( 'edit.php' )
	);

	return $url;
}

/**
 * Access Key Functions
 */
function directorist_licensing_get_access_key(): string {
	$data = directorist_licensing_data();

	return $data['account_data']['access_key'] ?? '';
}

function directorist_licensing_get_access_key_with_obfuscation(): string {
	$key = directorist_licensing_get_access_key();

	return str_replace( ' ', ' ', substr( $key, 0, 3 ) . '********' . substr( $key, -3 ) );
}

/**
 * Account Functions
 */
function directorist_licensing_get_account_data(): array {
	$data = directorist_licensing_data();

	return $data['account_data'] ?? [];
}

function directorist_licensing_get_account_name(): string {
	$data = directorist_licensing_get_account_data();

	return $data['display_name'] ?? '';
}

function directorist_licensing_get_account_email(): string {
	$data = directorist_licensing_get_account_data();

	return $data['user_email'] ?? '';
}

/**
 * Plan Functions
 */
function directorist_licensing_get_plan_data(): array {
	$data = directorist_licensing_data();

	return $data['plan_data'] ?? [];
}

function directorist_licensing_get_plan_upgrade_url(): string {
	$data = directorist_licensing_get_plan_data();

	if ( isset( $data['license_data'][0]['upgrade_to'] ) && $data['license_data'][0]['upgrade_to'] ) {
		return $data['license_data'][0]['upgrade_to'];
	}

	return '';
}

function directorist_licensing_get_plan_has_active(): bool {
	$data = directorist_licensing_get_plan_data();

	return isset( $data['license_data'][0]['item_id'] );
}

function directorist_licensing_get_plan_is_expired(): bool {
	$data   = directorist_licensing_get_plan_data();
	$expire = $data['license_data'][0]['expiration'] ?? '';

	if ( 'lifetime' !== $expire ) {
		if ( $expire > time() ) {
			return false;
		} else {
			return true;
		}
	}

	return false;
}

function directorist_licensing_get_plan_next_payment(): string {
	$data   = directorist_licensing_get_plan_data();
	$expire = $data['license_data'][0]['expiration'] ?? '';

	if ( 'lifetime' !== $expire && $expire > time() ) {
		return \date( 'M d, Y', $data['license_data'][0]['expiration'] );
	}

	return '';
}

function directorist_licensing_get_plan_name(): string {
	$data = directorist_licensing_get_plan_data();

	return $data['license_data'][0]['item_name'] ?? __( 'You’re on Directorist Premium Membership', 'directorist' );
}

/**
 * Templates and Extensions
 */
function directorist_licensing_get( $endpoint = '' ) {
	$args = [
		'method'      => 'GET',
		'timeout'     => 30,
		'redirection' => 5,
		'headers'     => [
			'user-agent' => 'Directorist/' . ATBDP_VERSION,
			'Accept'     => 'application/json',
		],
		'cookies'     => [],
	];

	$url      = 'https://app.directorist.com/wp-json/directorist/' . $endpoint;
	$response = wp_remote_get( $url, $args );

	return wp_remote_retrieve_body( $response );
}

function directorist_licensing_get_products( string $type ) {
	return API::get_products()[$type] ?? [];
}

function directorist_licensing_get_template_list() {
	return directorist_licensing_get_products( 'themes' );
}

function directorist_licensing_get_extension_list() {
	return directorist_licensing_get_products( 'extensions' );
}

function directorist_licensing_get_extensions_overview( string $type ) {
	$extensions = directorist_licensing_get_extension_list();

	if ( ! is_array( $extensions ) ) {
		return 0;
	}

	$official_extensions  = array_keys( $extensions );
	$installed_plugins    = get_plugins();
	$updates_available    = get_site_transient( 'update_plugins' );
	$outdated_plugins_key = isset( $updates_available->response ) ? array_keys( $updates_available->response ) : [];

	$installed_extensions = array_filter( $installed_plugins, function ( $plugin_data, $plugin_base ) use ( $official_extensions ) {
		return preg_match( '/^directorist-/', $plugin_base ) && in_array( strtok( $plugin_base, '/' ), $official_extensions, true );
	}, ARRAY_FILTER_USE_BOTH );

	$active_extensions   = count( array_filter( array_keys( $installed_extensions ), 'is_plugin_active' ) );
	$outdated_extensions = count( array_intersect( array_keys( $installed_extensions ), $outdated_plugins_key ) );

	$counts = [
		'active'    => $active_extensions,
		'available' => count( $installed_extensions ),
		'outdated'  => $outdated_extensions,
	];

	return $counts[$type] ?? 0;
}

function directorist_licensing_get_extension_list_html() {
	$extensions = directorist_licensing_get_extension_list();
	$html       = '';

	if ( ! empty( $extensions ) ) {
		ob_start(); // Start output buffering
		foreach ( $extensions as $slug => $extension ): ?>

			<div class="directorist-col-xxl-3 directorist-col-lg-4 directorist-col-sm-6" extension-slug="<?php echo esc_attr( $slug ); ?>">

				<article class="directorist-extension-item">

					<figure class="directorist-extension-image">
                        <img src="<?php echo esc_url( $extension['thumbnail'] ); ?>" alt="<?php echo esc_attr( $extension['name'] ); ?>">
                    </figure>

					<div class="directorist-extension-content">
                        <header class="directorist-extension-header">
                            <h2 class="directorist-extension-title"><?php echo esc_html( $extension['name'] ); ?></h2>
                        </header>
                        <p class="directorist-extension-description">
                            <?php echo esc_html( $extension['description'] ); ?>
                        </p>
                    </div>

                    <footer class="directorist-extension-footer">

                        <div class="directorist-extension-price-wrap">
                            <span class="directorist-extension-price">$29</span>
                            <span class="directorist-extension-year">/ year</span>
                        </div>

                        <?php if ( isset( $extension['link'] ) ): ?>
                            <div class="directorist-extension-cta">
                                <a target="__blank" href="<?php echo esc_url( $extension['link'] ); ?>" class="directorist-extension-link directorist-extension-btn directorist-extension-btn-primary">
                                    <?php esc_html_e( 'Details', 'directorist' ); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                    </footer>

                </article>

            </div>

        <?php endforeach;
		$html = ob_get_clean(); // Get buffered content and clear buffer
	}

	return $html;
}

function directorist_licensing_get_template_list_html() {
	$templates = directorist_licensing_get_template_list();
	$html       = '';

	if ( ! empty( $templates ) ) {
		ob_start(); // Start output buffering
		foreach ( $templates as $slug => $template ): ?>

			<div class="directorist-col-xxl-3 directorist-col-lg-4 directorist-col-sm-6">
				<article class="directorist-template-item">
					<figure class="directorist-template-image">
						<img src="<?php echo esc_attr( $template['thumbnail'] ); ?>" alt="<?php echo esc_attr( $template['name'] ); ?>">
					</figure>
					<div class="directorist-template-content">
						<header class="directorist-template-header">
							<h2 class="directorist-template-title">
								<?php echo esc_html( $template['name'] ); ?>
							</h2>
						</header>
						<p class="directorist-template-description">
							<?php echo esc_html( $template['description'] ); ?>
						</p>
					</div>
					<footer class="directorist-template-footer">
						<div class="directorist-template-cta">
							<a href="<?php echo esc_attr( $template['permalinks'] ); ?>" class="directorist-template-get">
								<?php esc_attr_e( 'Get it now', 'directorist' ); ?>
							</a>
							<a href="#" class="directorist-template-demo">Live Demo</a>
						</div>
					</footer>
				</article>
			</div>

        <?php endforeach;
		$html = ob_get_clean(); // Get buffered content and clear buffer
	}

	return $html;
}