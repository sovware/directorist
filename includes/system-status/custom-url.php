<?php
/**
 * @author AazzTech
 */
class ATBDP_Custom_Url
{
    public function __construct() {
		add_action( 'wp_ajax_generate_url', array( $this, 'generate_url' ) );
		add_action( 'wp_ajax_revoke_url', array( $this, 'revoke_url' ) );
		add_action( 'template_redirect', array( $this, 'view' ) );
    }

    public function generate_url() {
		$token   = rand();
		$expires = apply_filters( 'atbdp_system_info_remote_token_expire', DAY_IN_SECONDS * 3 );
		set_transient( 'system_info_remote_token', $token, $expires );
		$url = home_url() . '/?atbdp-system-info=' . $token;
		wp_send_json_success(
			array(
				'url' => $url,
				'message' => __( 'Secret URL has been created.', 'directorist' ),
			)
		);
    }

    public function revoke_url() {
		delete_transient( 'system_info_remote_token' );
		wp_send_json_success( __( 'Secret URL has been revoked.', 'connections' ) );
    }
    
    public function view() {

		if ( ! isset( $_GET['atbdp-system-info'] ) || empty( $_GET['atbdp-system-info'] ) ) {
			return;
		}

		$queryValue = $_GET['atbdp-system-info'];
		$token      = get_transient( 'system_info_remote_token' );

		if ( $queryValue == $token ) {

			/** WordPress Plugin Administration API */
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			require_once( ABSPATH . 'wp-admin/includes/update.php' );
			
			echo '<pre>';
			echo $this->system_info();
			echo '</pre>';
			exit;

		} else {

			wp_redirect( home_url() );
			exit;
		}

	}
    public function system_info() {
		ob_start();
		new ATBDP_System_Info();
		return ob_get_clean();
	}

    public function custom_link() {
		$token = get_transient( 'system_info_remote_token' );
		$url   = $token ? home_url() . '/?atbdp-system-info=' . $token : '';
		?>
		<div class="postbox">
			<h3><span><?php _e( 'Remote Viewing', 'directorist' ); ?></span></h3>
			<div class="inside">
				<div id="atbdp-remote-response"></div>
				<p>
					<?php _e(
						'Create a secret URL that support can use to remotely view your system information. The secret URL will expire after 72 hours and can be revoked at any time.',
						'directorist'
					) ?>
				</p>
				<p>
				<input type="text" id="system-info-url" class="regular-text"
						onclick="this.focus();this.select()" value="<?php echo esc_url( $url ? $url : '' ); ?>"
						title="<?php _e(
							'To copy the URL, click then press Ctrl + C (PC) or Cmd + C (Mac).',
							'directorist'
						); ?>"/>&nbsp;&nbsp;<a class="button-secondary" href="<?php echo esc_url( $url ? $url : '#' ); ?>" target="_blank"
												id="system-info-url-text-link" style="display: <?php echo $url ? 'display-inline' : 'none' ; ?>"><?php _e( 'Test', 'directorist' ); ?></a>
				</p>
				<p class="submit">
					<input type="submit" onClick="return false;" class="button-secondary" id='generate-url' name="generate-url"
							value="<?php _e( 'Generate URL', 'directorist' ) ?>"
							data-nonce="<?php echo wp_create_nonce( 'generate_remote_system_info_url' ); ?>"/>
					<input type="submit" onClick="return false;" class="button-secondary" id='revoke-url' name="revoke-url"
							value="<?php _e( 'Revoke URL', 'directorist' ) ?>"
							data-nonce="<?php echo wp_create_nonce( 'revoke_remote_system_info_url' ); ?>"/>
				</p>
			</div>
		</div>
		<?php
	}

}