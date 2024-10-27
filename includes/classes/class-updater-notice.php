<?php
/**
 * Background updater notice class.
 *
 * @since 7.0.6
 * @package Directorist
 */
namespace Directorist;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Updater_Notice {

	public static function init() {
		add_action( 'wp_loaded', [ __CLASS__, 'hide_notices' ] );
		add_action( 'admin_notices', [ __CLASS__, 'show_notices' ] );
	}

	/**
	 * Hide a notice if the GET variable is set.
	 */
	public static function hide_notices() {

		if( is_admin() && isset( $_GET["page"] ) && ( ( $_GET["page"] == 'atbdp-settings' ) || ( $_GET["page"] == 'atbdp-directory-types' ) || ( 'atbdp-layout-builder' === $_GET["page"] ) ) ) {
			remove_all_actions('admin_notices');
		}

		if ( isset( $_GET['directorist-hide-notice'] ) && isset( $_GET['_directorist_notice_nonce'] ) ) { // WPCS: input var ok, CSRF ok.
			if ( ! wp_verify_nonce( sanitize_key( wp_unslash( $_GET['_directorist_notice_nonce'] ) ), 'directorist_hide_notices_nonce' ) ) { // WPCS: input var ok, CSRF ok.
				wp_die( esc_html__( 'Action failed. Please refresh the page and retry.', 'directorist' ) );
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( esc_html__( 'You don&#8217;t have permission to do this.', 'directorist' ) );
			}

			update_option( 'directorist_dismissed_update_notice', true, false );
			delete_option( 'directorist_db_updated' );
		}
	}

	public static function get_screen_ids() {
		$root_slug = 'at_biz_dir';

		return array(
			$root_slug,
			'edit-' . $root_slug,
			'edit-' . $root_slug . '-location',
			'edit-' . $root_slug . '-category',
			'edit-' . $root_slug . '-tags',
			$root_slug . '_page_atbdp-directory-types',
			$root_slug . '_page_atbdp-settings',
			$root_slug . '_page_directorist-status',
			$root_slug . '_page_atbdp-extension',
			'edit-atbdp_listing_review',
			'dashboard',
			'plugins',
		);
	}

	public static function show_notices() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$screen    = get_current_screen();
		$screen_id = $screen ? $screen->id : '';

		if ( ! in_array( $screen_id, self::get_screen_ids(), true ) ) {
			return;
		}

		if ( version_compare( get_option( 'directorist_db_version' ), ATBDP_VERSION, '<' ) ) {
			// Delete this option to show the updated notice.
			delete_option( 'directorist_dismissed_update_notice' );
			delete_option( 'directorist_db_updated' );

			$updater = new Background_Updater();
			if ( $updater->is_updating() || ! empty( $_GET['do_update_directorist'] ) ) { // WPCS: input var ok, CSRF ok.
				self::updating_notice();
			} elseif ( \ATBDP_Installation::needs_db_update() ) {
				self::update_notice();
			}
		}

		// Show updated notice when db version and current version is same.
		if ( version_compare( get_option( 'directorist_db_version' ), ATBDP_VERSION, '=' ) && get_option( 'directorist_db_updated', false ) ) {
			self::updated_notice();
		}
	}

	public static function update_notice() {
		$update_url = wp_nonce_url(
			add_query_arg( 'do_update_directorist', 'true', admin_url( 'edit.php?post_type=at_biz_dir&page=atbdp-settings' ) ),
			'directorist_db_update',
			'directorist_db_update_nonce'
		);

		?>
		<div class="updated notice">
			<p>
				<strong><?php esc_html_e( 'Directorist data update', 'directorist' ); ?></strong> &#8211; <?php esc_html_e( 'We need to update your directory database to the latest version.', 'directorist' ); ?>
			</p>
			<p class="submit">
				<a href="<?php echo esc_url( $update_url ); ?>" class="directorist-update-now button-primary">
					<?php esc_html_e( 'Run the updater', 'directorist' ); ?>
				</a>
			</p>
		</div>
		<script type="text/javascript">
			jQuery( '.directorist-update-now' ).click( 'click', function() {
				return window.confirm( '<?php echo esc_js( __( 'It is strongly recommended that you backup your database before proceeding. Are you sure you wish to run the updater now?', 'directorist' ) ); ?>' ); // jshint ignore:line
			});
		</script>
		<?php
	}

	public static function updating_notice() {
		$force_update_url = wp_nonce_url(
			add_query_arg( 'force_update_directorist', 'true', admin_url( 'edit.php?post_type=at_biz_dir&page=atbdp-settings' ) ),
			'directorist_force_db_update',
			'directorist_force_db_update_nonce'
		);

		?>
		<div class="updated notice">
			<p>
				<strong><?php esc_html_e( 'Directorist data update', 'directorist' ); ?></strong> &#8211; <?php esc_html_e( 'Your database is being updated in the background.', 'directorist' ); ?>
				<a href="<?php echo esc_url( $force_update_url ); ?>">
					<?php esc_html_e( 'Taking a while? Click here to run it now.', 'directorist' ); ?>
				</a>
			</p>
		</div>
		<?php
	}

	public static function updated_notice() {
		if ( get_option( 'directorist_dismissed_update_notice' ) ) {
			return;
		}
		?>
		<div class="updated notice is-dismissible">
			<a class="directorist-message-close notice-dismiss" href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'directorist-hide-notice', 'update', remove_query_arg( 'do_update_directorist' ) ), 'directorist_hide_notices_nonce', '_directorist_notice_nonce' ) ); ?>"><span class="screen-reader-text"><?php esc_html_e( 'Dismiss', 'directorist' ); ?></span></a>

			<p><?php esc_html_e( 'Directorist data update complete. Thank you for updating to the latest version!', 'directorist' ); ?></p>
		</div>
		<?php
	}
}

Updater_Notice::init();
