<?php
/**
 * @author AazzTech
 */

namespace AazzTech\Directorist\Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class DeprecatedNotice {
	public string $parent_label        = 'Addonskit for Elementor';
	public string $core_plugin         = 'Directorist';
	public string $addon_slug          = 'addonskit-for-elementor/addonskit-for-elementor.php';
	public string $addon_url           = 'https://wordpress.org/plugins/addonskit-for-elementor/';
	public ?string $addon_requires_php = '7.4';
	public ?string $addon_requires_wp  = '6.0';
	public bool $is_installed          = false;
	public bool $is_active             = false;
	public bool $is_valid              = false;
	public string $min_addon_version   = '8.0';

	public function __construct() {
		$parent_path        = WP_PLUGIN_DIR . "/{$this->addon_slug}";
		$this->is_installed = is_file( $parent_path );

		if ( $this->is_installed ) {
			$this->is_active = in_array( $parent_path, wp_get_active_and_valid_plugins(), true );
		}

		if ( $this->is_active ) {
			if ( ! function_exists( 'get_plugin_data' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			$parent_plugin_data    = get_plugin_data( $parent_path );
			$parent_plugin_version = explode( "-", $parent_plugin_data["Version"] )[0];
			$this->is_valid        = version_compare( $parent_plugin_version, $this->min_addon_version ) > -1;
		}
	}

	public function maybe_show_notice_for_required_plugins() {
		global $pagenow;

		if ( 'update.php' === $pagenow ) {
			return;
		}

		if ( $this->is_active ) {
			if ( ! $this->is_valid ) {
				$this->parent_plugin_invalid_notice();
			}

			return;
		}

		if ( $this->is_installed ) {
			$this->admin_notice_callback_to_activate_plugin();

			return;
		}

		$this->admin_notice_callback_to_install_plugin();
	}

	public function parent_plugin_invalid_notice() {
		?>
        <div class="notice notice-error" style="display: flex; flex-wrap:wrap; justify-content:space-between">
            <p style="display: flex; align-items: center;">
                The current version of your &nbsp; <strong><?php echo $this->parent_label; ?> is not compatible with <?php echo $this->core_plugin; ?></strong>. To ensure compatibility and access new features,&nbsp;<strong>update <?php echo $this->parent_label; ?> to version <?php echo $this->min_addon_version ?> or later</strong>.
            </p>
        </div>
    <?php }

	public function admin_notice_callback_to_activate_plugin(): void {
		$can_activate_plugin = $this->is_plugin_compatible( 'activate_plugin', $this->addon_requires_php, $this->addon_requires_wp );
		?>
        <div class="notice notice-warning" style="display: flex; flex-wrap:wrap; justify-content:space-between">
            <p style="display: flex; align-items: center;">
                <span>
                    <?php echo wp_kses( sprintf( __( '<b>Deprecation</b>: We are not going to provide Elementor support in Directorist plugin, We have introduced huge features packed in <a href="%1$s" target="_blank">%2$s</a>', 'directorist' ),
					esc_url( $this->addon_url ),
					esc_html( $this->parent_label ),
					esc_html( $this->core_plugin ) ),
					['a' => ['href' => true, 'target' => true], 'b' => []] ); ?>
				</span>
			</p>
            <?php if ( $can_activate_plugin ): ?>
				<p>
					<a class="button button-primary" href="<?php echo esc_url( $this->get_plugin_activation_url( $this->addon_slug ) ); ?>">Activate <?php echo $this->parent_label; ?></a>
				</p>
            <?php endif;?>
        </div>
    <?php }

	public function admin_notice_callback_to_install_plugin(): void {
		$can_install_plugin = $this->is_plugin_compatible( 'install_plugins', $this->addon_requires_php, $this->addon_requires_wp );
		?>
        <div class="notice notice-warning" style="display: flex; flex-wrap:wrap; justify-content:space-between">
            <p style="display: flex; align-items: center;">
                <span>
                    <?php echo wp_kses( sprintf(
						__( '<b>Deprecation</b>: We are not going to provide Elementor support in Directorist plugin, We have introduced huge features packed in <a href="%1$s" target="_blank">%2$s</a>', 'directorist' ),
						esc_url( $this->addon_url ),
						esc_html( $this->parent_label ),
						esc_html( $this->core_plugin ) ),
						['a' => ['href' => true, 'target' => true], 'b' => []] );
					?>
                </span>
            </p>
            <?php if ( $can_install_plugin ): ?>
				<p>
					<a class="button button-primary" href="<?php echo esc_url( $this->get_plugin_installation_url( $this->addon_slug ) ); ?>">Install <?php echo $this->parent_label; ?></a>
				</p>
            <?php endif;?>
        </div>
    <?php }

	public function is_plugin_compatible( string $context, ?string $requires_php = null, ?string $requires_wp = null ) {
		$can_use_plugin = current_user_can( $context );
		$compatible_php = is_php_version_compatible( $requires_php );
		$compatible_wp  = is_wp_version_compatible( $requires_wp );

		return $can_use_plugin && $compatible_php && $compatible_wp;
	}

	public function get_plugin_activation_url( $path ): string {
		return wp_nonce_url( self_admin_url( "plugins.php?action=activate&plugin={$path}&paged=1" ), 'activate-plugin_' . $path );
	}

	public function get_plugin_installation_url( $path ): string {
		$slug = preg_replace( '/\/.+$/', '', $path );

		return wp_nonce_url( self_admin_url( "update.php?action=install-plugin&plugin={$slug}" ), 'install-plugin_' . $slug );
	}
}