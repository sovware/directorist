<?php 
global $wpdb;
 $environment  		= $this->get_environment_info();
 $database     		= $this->get_database_info();
 $post_type_counts  = $this->get_post_type_counts();
 $security			= $this->get_security_info();
 $active_plugins	= $this->get_active_plugins();
 $theme	      		= $this->get_Theme_info();
 $php_information 	= $this->php_information();
 
 $atbdp_option = get_option( 'atbdp_option' );
?>
<!-- WordPress environment -->
<table class="atbdp_status_table widefat" cellspacing="0" id="status">
    <thead>
        <tr>
            <th colspan="3" data-export-label="WordPress Environment"><h2><?php esc_html_e( 'WordPress environment', 'directorist' ); ?></h2></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td data-export-label="Home URL"><?php esc_html_e( 'Home URL', 'directorist' ); ?>:</td>
            <td class="help"><?php echo wp_kses_post( $this->directorist_help_tip( __( 'The homepage URL of your site.', 'directorist' ) ) ); ?></td>
            <td><?php echo wp_kses_post( $environment['home_url'] ) ?></td>
        </tr>
        <tr>
            <td data-export-label="Site URL"><?php esc_html_e( 'Site URL', 'directorist' ); ?>:</td>
            <td class="help"><?php echo wp_kses_post( $this->directorist_help_tip( __( 'The root URL of your site.', 'directorist' ) ) ); ?></td>
            <td><?php echo esc_html( $environment['site_url'] ) ?></td>
        </tr>
        <tr>
            <td data-export-label="Directorist Version"><?php esc_html_e( 'Directorist version', 'directorist' ); ?>:</td>
            <td class="help"><?php echo wp_kses_post( $this->directorist_help_tip( __( 'The version of GeoDirectory installed on your site.', 'directorist' ) ) ); ?></td>
            <td><?php echo esc_html( $environment['version'] ) ?></td>
        </tr>
        <tr>
            <td data-export-label="WP Version"><?php esc_html_e( 'WP version', 'directorist' ); ?>:</td>
            <td class="help"><?php echo wp_kses_post( $this->directorist_help_tip( __( 'The version of WordPress installed on your site.', 'directorist' ) ) ); ?></td>
            <td><?php echo esc_html( $environment['wp_version'] ) ?></td>
        </tr>
        <tr>
            <td data-export-label="WP Multisite"><?php esc_html_e( 'WP multisite', 'directorist' ); ?>:</td>
            <td class="help"><?php echo wp_kses_post( $this->directorist_help_tip( __( 'Whether or not you have WordPress Multisite enabled.', 'directorist' ) ) ); ?></td>
            <td><?php echo ( $environment['wp_multisite'] ) ? '<span class="dashicons dashicons-yes"></span>' : '&ndash;'; ?></td>
        </tr>
        <tr>
            <td data-export-label="WP Memory Limit"><?php esc_html_e( 'WP memory limit', 'directorist' ); ?>:</td>
            <td class="help"><?php echo wp_kses_post( $this->directorist_help_tip( __( 'The maximum amount of memory (RAM) that your site can use at one time.', 'directorist' ) ) ); ?></td>
            <td><?php
				if ( $environment['wp_memory_limit'] < 67108864 ) {
					$_memory_limit =  '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( __( '%1$s - We recommend setting memory to at least 64MB. See: %2$s', 'directorist' ), esc_html( size_format( $environment['wp_memory_limit'] ) ), '<a href="https://codex.wordpress.org/Editing_wp-config.php#Increasing_memory_allocated_to_PHP" target="_blank">' . esc_html__( 'Increasing memory allocated to PHP', 'directorist' ) . '</a>' ) . '</mark>';
				} else {
					$_memory_limit = '<mark class="yes">' . esc_html( size_format( $environment['wp_memory_limit'] ) ) . '</mark>';
				}

				echo wp_kses_post( $_memory_limit );
			?></td>
        </tr>
        <tr>
            <td data-export-label="WP Debug Mode"><?php esc_html_e( 'WP debug mode', 'directorist' ); ?>:</td>
            <td class="help"><?php echo wp_kses_post( $this->directorist_help_tip( __( 'Displays whether or not WordPress is in Debug Mode.', 'directorist' ) ) ); ?></td>
            <td>
                <?php if ( $environment['wp_debug_mode'] ) : ?>
					<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>
				<?php else : ?>
					<mark class="no">&ndash;</mark>
				<?php endif; ?>
            </td>
        </tr>
        <tr>
            <td data-export-label="WP Cron"><?php esc_html_e( 'WP cron', 'directorist' ); ?>:</td>
            <td class="help"><?php echo wp_kses_post( $this->directorist_help_tip( __( 'Displays whether or not WP Cron Jobs are enabled.', 'directorist' ) ) ); ?></td>
            <td>
                <?php if ( $environment['wp_cron'] ) : ?>
					<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>
				<?php else : ?>
					<mark class="no">&ndash;</mark>
				<?php endif; ?>
            </td>
        </tr>
        <tr>
            <td data-export-label="Language"><?php esc_html_e( 'Language', 'directorist' ); ?>:</td>
            <td class="help"><?php echo wp_kses_post( $this->directorist_help_tip( __( 'The current language used by WordPress. Default = English', 'directorist' ) ) ); ?></td>
            <td><?php echo esc_html( $environment['language'] ) ?></td>
        </tr>
    </tbody>
</table>
<!-- PHP -->
<table class="atbdp_status_table widefat" cellspacing="0" id="status">
    <thead>
        <tr>
            <th colspan="3" data-export-label="PHP"><h2><?php esc_html_e( 'PHP', 'directorist' ); ?></h2></th>
        </tr>
    </thead>
    <tbody>
		<?php foreach( $php_information as $item => $value) { ?>
        <tr>
            <td data-export-label="<?php echo esc_attr( $item ); ?>"><?php echo wp_kses_post( $item ); ?>:</td>
            <td><?php echo wp_kses_post( $value ); ?></td>
		</tr>
		<?php } ?>
    </tbody>
</table>
 <!-- Server environment -->
<table class="atbdp_status_table widefat" cellspacing="0">
	<thead>
		<tr>
			<th colspan="3" data-export-label="Server Environment"><h2><?php esc_html_e( 'Server environment', 'directorist' ); ?></h2></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td data-export-label="Server Info"><?php esc_html_e( 'Server info', 'directorist' ); ?>:</td>
			<td class="help"><?php echo wp_kses_post( $this->directorist_help_tip( __( 'Information about the web server that is currently hosting your site.', 'directorist' ) ) ); ?></td>
			<td><?php echo wp_kses_post( $environment['server_info'] ); ?></td>
		</tr>
		<?php
		$ver = $wpdb->db_server_info();
		if ( ! empty( $wpdb->is_mysql ) && ! stristr( $ver, 'MariaDB' ) ) : ?>
			<tr>
				<td data-export-label="MySQL Version"><?php esc_html_e( 'MySQL version', 'directorist' ); ?>:</td>
				<td class="help"><?php echo wp_kses_post( $this->directorist_help_tip( __( 'The version of MySQL installed on your hosting server.', 'directorist' ) ) ); ?></td>
				<td>
					<?php
					if ( version_compare( $environment['mysql_version'], '5.6', '<' ) ) {
						echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( esc_html__( '%1$s - We recommend a minimum MySQL version of 5.6. See: %2$s', 'directorist' ), esc_html( $environment['mysql_version'] ), '<a href="https://wordpress.org/about/requirements/" target="_blank">' . esc_html__( 'WordPress requirements', 'directorist' ) . '</a>' ) . '</mark>';
					} else {
						echo '<mark class="yes">' . esc_html( $environment['mysql_version'] ) . '</mark>';
					}
					?>
				</td>
			</tr>
		<?php endif; ?>
		<tr>
			<td data-export-label="Max Upload Size"><?php esc_html_e( 'Max upload size', 'directorist' ); ?>:</td>
			<td class="help"><?php echo wp_kses_post( $this->directorist_help_tip( __( 'The largest filesize that can be uploaded to your WordPress installation.', 'directorist' ) ) ); ?></td>
			<td><?php echo esc_html( size_format( $environment['max_upload_size'] ) ); ?></td>
		</tr>
		<tr>
			<td data-export-label="Default Timezone is UTC"><?php esc_html_e( 'Default timezone is UTC', 'directorist' ); ?>:</td>
			<td class="help"><?php echo wp_kses_post( $this->directorist_help_tip( __( 'The default timezone for your server.', 'directorist' ) ) ); ?></td>
			<td><?php
				if ( 'UTC' !== $environment['default_timezone'] ) {
					echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( esc_html__( 'Default timezone is %s - it should be UTC', 'directorist' ), esc_html( $environment['default_timezone'] ) ) . '</mark>';
				} else {
					echo '<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>';
				} ?>
			</td>
		</tr>
		<tr>
			<td data-export-label="fsockopen/cURL"><?php esc_html_e( 'fsockopen/cURL', 'directorist' ); ?>:</td>
			<td class="help"><?php echo wp_kses_post( $this->directorist_help_tip( __( 'Payment gateways can use cURL to communicate with remote servers to authorize payments, other plugins may also use it when communicating with remote services.', 'directorist' ) ) ); ?></td>
			<td><?php
				if ( $environment['fsockopen_or_curl_enabled'] ) {
					echo '<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>';
				} else {
					echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . esc_html__( 'Your server does not have fsockopen or cURL enabled - PayPal IPN and other scripts which communicate with other servers will not work. Contact your hosting provider.', 'directorist' ) . '</mark>';
				} ?>
			</td>
		</tr>
		<tr>
			<td data-export-label="SoapClient"><?php esc_html_e( 'SoapClient', 'directorist' ); ?>:</td>
			<td class="help"><?php echo wp_kses_post( $this->directorist_help_tip( __( 'Some webservices like shipping use SOAP to get information from remote servers, for example, live shipping quotes from FedEx require SOAP to be installed.', 'directorist' ) ) ); ?></td>
			<td><?php
				if ( $environment['soapclient_enabled'] ) {
					$_soapclient_enabled = '<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>';
				} else {
					$_soapclient_enabled = '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( __( 'Your server does not have the %s class enabled - some gateway plugins which use SOAP may not work as expected.', 'directorist' ), '<a href="https://php.net/manual/en/class.soapclient.php">SoapClient</a>' ) . '</mark>';
				} 
				
				echo wp_kses_post( $_soapclient_enabled ); ?>
			</td>
		</tr>
		<tr>
			<td data-export-label="DOMDocument"><?php esc_html_e( 'DOMDocument', 'directorist' ); ?>:</td>
			<td class="help"><?php echo wp_kses_post( $this->directorist_help_tip( __( 'HTML/Multipart emails use DOMDocument to generate inline CSS in templates.', 'directorist' ) ) ); ?></td>
			<td><?php
				if ( $environment['domdocument_enabled'] ) {
					echo '<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>';
				} else {
					echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( esc_html__( 'Your server does not have the %s class enabled - HTML/Multipart emails, and also some extensions, will not work without DOMDocument.', 'directorist' ), '<a href="https://php.net/manual/en/class.domdocument.php">DOMDocument</a>' ) . '</mark>';
				} ?>
			</td>
		</tr>
		<tr>
			<td data-export-label="GZip"><?php esc_html_e( 'GZip', 'directorist' ); ?>:</td>
			<td class="help"><?php echo wp_kses_post( $this->directorist_help_tip( __( 'GZip (gzopen) is used to open the GEOIP database from MaxMind.', 'directorist' ) ) ); ?></td>
			<td><?php
				if ( $environment['gzip_enabled'] ) {
					echo '<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>';
				} else {
					echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( esc_html__( 'Your server does not support the %s function - this is required to use the GeoIP database from MaxMind.', 'directorist' ), '<a href="https://php.net/manual/en/zlib.installation.php">gzopen</a>' ) . '</mark>';
				} ?>
			</td>
		</tr>
		<tr>
			<td data-export-label="Multibyte String"><?php esc_html_e( 'Multibyte string', 'directorist' ); ?>:</td>
			<td class="help"><?php echo wp_kses_post( $this->directorist_help_tip( __( 'Multibyte String (mbstring) is used to convert character encoding, like for emails or converting characters to lowercase.', 'directorist' ) ) ); ?></td>
			<td><?php
				if ( $environment['mbstring_enabled'] ) {
					echo '<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>';
				} else {
					echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( esc_html__( 'Your server does not support the %s functions - this is required for better character encoding. Some fallbacks will be used instead for it.', 'directorist' ), '<a href="https://php.net/manual/en/mbstring.installation.php">mbstring</a>' ) . '</mark>';
				} ?>
			</td>
		</tr>
		<tr>
			<td data-export-label="Remote Post"><?php esc_html_e( 'Remote post', 'directorist' ); ?>:</td>
			<td class="help"><?php echo wp_kses_post( $this->directorist_help_tip( __( 'PayPal uses this method of communicating when sending back transaction information.', 'directorist' ) ) ); ?></td>
			<td><?php
				if ( $environment['remote_post_successful'] ) {
					echo '<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>';
				} else {
					echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( esc_html__( '%s failed. Contact your hosting provider.', 'directorist' ), 'wp_remote_post()' ) . ' ' . esc_html( $environment['remote_post_response'] ) . '</mark>';
				} ?>
			</td>
		</tr>
		<tr>
			<td data-export-label="Remote Get"><?php esc_html_e( 'Remote get', 'directorist' ); ?>:</td>
			<td class="help"><?php echo wp_kses_post( $this->directorist_help_tip( __( 'GeoDirectory plugins may use this method of communication when checking for plugin updates.', 'directorist' ) ) ); ?></td>
			<td><?php
				if ( $environment['remote_get_successful'] ) {
					echo '<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>';
				} else {
					echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( esc_html__( '%s failed. Contact your hosting provider.', 'directorist' ), 'wp_remote_get()' ) . ' ' . esc_html( $environment['remote_get_response'] ) . '</mark>';
				} ?>
			</td>
		</tr>
		<?php
		$rows = apply_filters( 'geodir_system_status_environment_rows', array() );
		foreach ( $rows as $row ) {
			if ( ! empty( $row['success'] ) ) {
				$css_class = 'yes';
				$icon = '<span class="dashicons dashicons-yes"></span>';
			} else {
				$css_class = 'error';
				$icon = '<span class="dashicons dashicons-no-alt"></span>';
			}
			?>
			<tr>
				<td data-export-label="<?php echo esc_attr( $row['name'] ); ?>"><?php echo esc_html( $row['name'] ); ?>:</td>
				<td class="help"><?php echo isset( $row['help'] ) ? wp_kses_post( $row['help'] ) : ''; ?></td>
				<td>
					<mark class="<?php echo esc_attr( $css_class ); ?>">
						<?php echo wp_kses_data( $icon ); ?>  <?php echo wp_kses_data( ! empty( $row['note'] ) ? $row['note'] : '' ); ?>
					</mark>
				</td>
			</tr><?php
		} ?>
	</tbody>
</table>
<!-- User platform -->
<table class="atbdp_status_table widefat" cellspacing="0">
	<thead>
		<tr>
			<th colspan="3" data-export-label="User platform"><h2><?php esc_html_e( 'User platform', 'directorist' ); ?></h2></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td data-export-label="Platform"><?php esc_html_e( 'Platform', 'directorist' ); ?>:</td>
			<td class="help"></td>
			<td><?php echo esc_html( $environment['platform'] ); ?></td>
		</tr>
		<tr>
			<td data-export-label="Browser name"><?php esc_html_e( 'Browser name', 'directorist' ); ?>:</td>
			<td class="help"></td>
			<td><?php echo esc_html( $environment['browser_name'] ); ?></td>
		</tr>
		<tr>
			<td data-export-label="Browser version"><?php esc_html_e( 'Browser version', 'directorist' ); ?>:</td>
			<td class="help"></td>
			<td><?php echo esc_html( $environment['browser_version'] ); ?></td>
		</tr>
		<tr>
			<td data-export-label="User agent"><?php esc_html_e( 'User agent', 'directorist' ); ?>:</td>
			<td class="help"></td>
			<td><?php echo esc_html( $environment['user_agent'] ); ?></td>
		</tr>
	</tbody>
</table>
<!-- Settings -->
<table class="atbdp_status_table widefat" cellspacing="0">
    <thead>
        <tr>
            <th colspan="3" data-export-label="User platform"><h2><?php esc_html_e( 'Settings', 'directorist' ); ?></h2></th>
        </th>
        </tr>
    </thead>
    <tbody>
        <?php 
        if ( ! empty( $atbdp_option ) ) : 
            foreach ($atbdp_option as $name => $value) {
            ?>
            <tr>
                <td data-export-label="<?php echo esc_attr( ! empty( $name ) ? $name: '' ); ?>"> <?php echo esc_attr( ! empty( $name ) ? $name : '' ); ?> </td>
                <td> <?php print_r( $value ) ?> </td>
            </tr>
            <?php 
            }
        endif; 
        ?>
    </tbody>
</table>
<!-- Database -->
<table class="atbdp_status_table widefat" cellspacing="0">
    <thead>
    <tr>
        <th colspan="3" data-export-label="Database"><h2><?php esc_html_e( 'Database', 'directorist' ); ?></h2></th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td data-export-label="Directorist Database Prefix"><?php esc_html_e( 'Database prefix', 'directorist' ); ?></td>
        <td class="help">&nbsp;</td>
        <td><?php
			if ( strlen( $database['database_prefix'] ) > 20 ) {
				echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( esc_html__( '%1$s - We recommend using a prefix with less than 20 characters.', 'directorist' ), esc_html( $database['database_prefix'] ) ) . '</mark>';
			} else {
				echo '<mark class="yes">' . esc_html( $database['database_prefix'] ) . '</mark>';
			}
			?>
        </td>
    </tr>
    <tr>
        <td><?php esc_html_e( 'Total Database Size', 'directorist' ); ?></td>
        <td class="help">&nbsp;</td>
        <td><?php printf( '%.2fMB', esc_html( $database['database_size']['data'] ) + $database['database_size']['index'] ); ?></td>
    </tr>

    <tr>
        <td><?php esc_html_e( 'Database Data Size', 'directorist' ); ?></td>
        <td class="help">&nbsp;</td>
        <td><?php printf( '%.2fMB', esc_html( $database['database_size']['data'] ) ); ?></td>
    </tr>

    <tr>
        <td><?php esc_html_e( 'Database Index Size', 'directorist' ); ?></td>
        <td class="help">&nbsp;</td>
        <td><?php printf( '%.2fMB', esc_html( $database['database_size']['index'] ) ); ?></td>
    </tr>

	<?php foreach ( $database['database_tables']['directorist'] as $table => $table_data ) { ?>
    <tr>
        <td><?php echo esc_html( $table ); ?></td>
        <td class="help">&nbsp;</td>
        <td>
			<?php if ( ! $table_data ) {
				echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . esc_html__( 'Table does not exist', 'directorist' ) . '</mark>';
			} else {
				printf( esc_html__( 'Data: %.2fMB + Index: %.2fMB', 'directorist' ), wp_kses_post( $this->directorist_help_tip( $table_data['data'], 2 ) ), wp_kses_post( $this->directorist_help_tip( $table_data['index'], 2 ) ) );
			} ?>
        </td>
        </tr>
    <?php } ?>

    <?php foreach ( $database['database_tables']['other'] as $table => $table_data ) { ?>
        <tr>
            <td><?php echo esc_html( $table ); ?></td>
            <td class="help">&nbsp;</td>
            <td>
			    <?php printf( esc_html__( 'Data: %.2fMB + Index: %.2fMB', 'directorist' ), wp_kses_post( $this->directorist_help_tip( $table_data['data'], 2 ) ), wp_kses_post( $this->directorist_help_tip( $table_data['index'], 2 ) ) ); ?>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>
<!-- Post type counts -->
<table class="atbdp_status_table widefat" cellspacing="0">
    <thead>
    <tr>
        <th colspan="3" data-export-label="Post Type Counts"><h2><?php esc_html_e( 'Post Type Counts', 'directorist' ); ?></h2></th>
    </tr>
    </thead>
    <tbody>
	<?php
	foreach ( $post_type_counts as $post_type ) {
		?>
        <tr>
            <td><?php echo esc_html( $post_type->type ); ?></td>
            <td class="help">&nbsp;</td>
            <td><?php echo absint( $post_type->count ); ?></td>
        </tr>
		<?php
	}
	?>
    </tbody>
</table>
<!-- Security -->
<table class="atbdp_status_table widefat" cellspacing="0">
	<thead>
		<tr>
			<th colspan="3" data-export-label="Security"><h2><?php esc_html_e( 'Security', 'directorist' ); ?></h2></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td data-export-label="Secure connection (HTTPS)"><?php esc_html_e( 'Secure connection (HTTPS)', 'directorist' ); ?>:</td>
			<td class="help"><?php echo wp_kses_post( $this->directorist_help_tip( __( 'Is the connection to your site secure?', 'directorist' ) ) ); ?></td>
			<td>
				<?php if ( $security['secure_connection'] ) : ?>
					<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>
				<?php else : ?>
					<mark class="error"><span class="dashicons dashicons-warning"></span><?php echo esc_html__( 'HTTPS is not enabled on your site.', 'directorist' ); ?></mark>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<td data-export-label="Hide errors from visitors"><?php esc_html_e( 'Hide errors from visitors', 'directorist' ); ?></td>
			<td class="help"><?php echo wp_kses_post( $this->directorist_help_tip( __( 'Error messages can contain sensitive information about your site environment. These should be hidden from untrusted visitors.', 'directorist' ) ) ); ?></td>
			<td>
				<?php if ( $security['hide_errors'] ) : ?>
					<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>
				<?php else : ?>
					<mark class="error"><span class="dashicons dashicons-warning"></span><?php esc_html_e( 'Error messages should not be shown to visitors.', 'directorist' ); ?></mark>
				<?php endif; ?>
			</td>
		</tr>
	</tbody>
</table>
<!-- Active Plugins -->
<table class="adbdp_status_table widefat" cellspacing="0">
	<thead>
		<tr>
			<th colspan="3" data-export-label="Active Plugins (<?php echo esc_attr( count( $active_plugins ) ); ?>)"><h2><?php esc_html_e( 'Active plugins', 'directorist' ); ?> (<?php echo esc_html( count( $active_plugins ) ); ?>)</h2></th>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach ( $active_plugins as $plugin ) {
			if ( ! empty( $plugin['name'] ) ) {
				$dirname = dirname( $plugin['plugin'] );

				// Link the plugin name to the plugin url if available.
				$plugin_name = esc_html( $plugin['name'] );
				if ( ! empty( $plugin['url'] ) ) {
					$plugin_name = '<a href="' . esc_url( $plugin['url'] ) . '" aria-label="' . esc_attr__( 'Visit plugin homepage', 'directorist' ) . '" target="_blank">' . $plugin_name . '</a>';
				}

				$version_string = '';
				$network_string = '';
				if ( ! empty( $plugin['latest_verison'] ) && version_compare( $plugin['latest_verison'], $plugin['version'], '>' ) ) {
					/* translators: %s: plugin latest version */
					$version_string = ' &ndash; <strong style="color:red;">' . sprintf( esc_html__( '%s is available', 'directorist' ), $plugin['latest_verison'] ) . '</strong>';
				}

				if ( false != $plugin['network_activated'] ) {
					$network_string = ' &ndash; <strong style="color:black;">' . esc_html__( 'Network enabled', 'directorist' ) . '</strong>';
				}
				?>
				<tr>
					<td><?php echo wp_kses_post( $plugin_name ); ?></td>
					<td class="help">&nbsp;</td>
					<td><?php
						/* translators: %s: plugin author */
						printf( esc_html__( 'by %s', 'directorist' ), esc_html( $plugin['author_name'] ) );
						echo ' &ndash; ' . esc_html( $plugin['version'] ) . esc_html( $version_string ) . esc_html( $network_string );
					?></td>
				</tr>
				<?php
			}
		}
		?>
	</tbody>
</table>
<!-- Theme -->
<table class="atbd_status_table widefat" cellspacing="0">
	<thead>
		<tr>
			<th colspan="3" data-export-label="Theme"><h2><?php esc_html_e( 'Theme', 'directorist' ); ?></h2></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td data-export-label="Name"><?php esc_html_e( 'Name', 'directorist' ); ?>:</td>
			<td class="help"><?php echo wp_kses_post( $this->directorist_help_tip( __( 'The name of the current active theme.', 'directorist' ) ) ); ?></td>
			<td><?php echo esc_html( $theme['name'] ); ?></td>
		</tr>
		<tr>
			<td data-export-label="Version"><?php esc_html_e( 'Version', 'directorist' ); ?>:</td>
			<td class="help"><?php echo wp_kses_post( $this->directorist_help_tip( __( 'The installed version of the current active theme.', 'directorist' ) ) ); ?></td>
			<td><?php
				echo esc_html( $theme['version'] );
				if ( version_compare( $theme['version'], $theme['version_latest'], '<' ) ) {
					/* translators: %s: theme latest version */
					echo ' &ndash; <strong style="color:red;">' . sprintf( esc_html__( '%s is available', 'directorist' ), esc_html( $theme['version_latest'] ) ) . '</strong>';
				}
			?></td>
		</tr>
		<tr>
			<td data-export-label="Author URL"><?php esc_html_e( 'Author URL', 'directorist' ); ?>:</td>
			<td class="help"><?php echo wp_kses_post( $this->directorist_help_tip( __( 'The theme developers URL.', 'directorist' ) ) ); ?></td>
			<td><?php echo esc_html( $theme['author_url'] ) ?></td>
		</tr>
		<tr>
			<td data-export-label="Child Theme"><?php esc_html_e( 'Child theme', 'directorist' ); ?>:</td>
			<td class="help"><?php echo wp_kses_post( $this->directorist_help_tip( __( 'Displays whether or not the current theme is a child theme.', 'directorist' ) ) ); ?></td>
			<td><?php
				$_child_theme =  $theme['is_child_theme'] ? '<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>' : '<span class="dashicons dashicons-no-alt"></span> &ndash; ' . sprintf( __( 'If you are modifying Directorist on a parent theme that you did not build personally we recommend using a child theme. See: <a href="%s" target="_blank">How to create a child theme</a>', 'directorist' ), 'https://developer.wordpress.org/themes/advanced-topics/child-themes/' );

				echo wp_kses_post( $_child_theme );	
			?></td>
		</tr>
		<?php
		if ( $theme['is_child_theme'] ) :
		?>
		<tr>
			<td data-export-label="Parent Theme Name"><?php esc_html_e( 'Parent theme name', 'directorist' ); ?>:</td>
			<td class="help"><?php echo wp_kses_post( $this->directorist_help_tip( __( 'The name of the parent theme.', 'directorist' ) ) ); ?></td>
			<td><?php echo esc_html( $theme['parent_name'] ); ?></td>
		</tr>
		<tr>
			<td data-export-label="Parent Theme Version"><?php esc_html_e( 'Parent theme version', 'directorist' ); ?>:</td>
			<td class="help"><?php echo wp_kses_post( $this->directorist_help_tip( __( 'The installed version of the parent theme.', 'directorist' ) ) ); ?></td>
			<td><?php
				echo esc_html( $theme['parent_version'] );
				if ( version_compare( $theme['parent_version'], $theme['parent_version_latest'], '<' ) ) {
					/* translators: %s: parant theme latest version */
					echo ' &ndash; <strong style="color:red;">' . sprintf( esc_html__( '%s is available', 'directorist' ), esc_html( $theme['parent_version_latest'] ) ) . '</strong>';
				}
			?></td>
		</tr>
		<tr>
			<td data-export-label="Parent Theme Author URL"><?php esc_html_e( 'Parent theme author URL', 'directorist' ); ?>:</td>
			<td class="help"><?php echo wp_kses_post( $this->directorist_help_tip( __( 'The parent theme developers URL.', 'directorist' ) ) ); ?></td>
			<td><?php echo esc_html( $theme['parent_author_url'] ); ?></td>
		</tr>
		<?php endif ?>
	</tbody>
</table>
<!-- Template Overrides -->
<table class="atbdp_status_table widefat" cellspacing="0">
	<thead>
		<tr>
			<th colspan="3" data-export-label="Templates"><h2><?php esc_html_e( 'Templates', 'directorist' ); ?><?php echo wp_kses_post( $this->directorist_help_tip( __( 'This section shows any files that are overriding the default GeoDirectory template pages.', 'directorist' ) ) ); ?></h2></th>
		</tr>
	</thead>
	<tbody>
		<?php 		
			if ( ! empty( $theme['overrides'] ) ) { ?>
					<tr>
						<td data-export-label="Overrides"><?php esc_html_e( 'Overrides', 'directorist' ); ?></td>
						<td class="help">&nbsp;</td>
						<td>
							<?php
							$total_overrides = count( $theme['overrides'] );
							for ( $i = 0; $i < $total_overrides; $i++ ) {
								$override = $theme['overrides'][ $i ];
								if ( $override['core_version'] && ( empty( $override['version'] ) || version_compare( $override['version'], $override['core_version'], '<' ) ) ) {
									$current_version = $override['version'] ? $override['version'] : '-';
									printf(
										esc_html__( '%1$s version %2$s is out of date. The core version is %3$s', 'directorist' ),
										'<code>' . esc_attr( $override['file'] ) . '</code>',
										'<strong style="color:red">' . esc_html( $current_version ) . '</strong>',
										esc_attr( $override['core_version'] )
									);
								} else {
									echo esc_html( $override['file'] );
								}
								if ( ( count( $theme['overrides'] ) - 1 ) !== $i ) {
									echo ', ';
								}
								echo '<br />';
							}
							?>
						</td>
					</tr>
					<?php
			} else {
				?>
				<tr>
					<td data-export-label="Overrides"><?php esc_html_e( 'Overrides', 'directorist' ); ?>:</td>
					<td class="help">&nbsp;</td>
					<td>&ndash;</td>
				</tr>
				<?php
			}

			if ( true === $theme['has_outdated_templates'] ) {
				?>
				<tr>
					<td data-export-label="Outdated Templates"><?php esc_html_e( 'Outdated templates', 'directorist' ); ?>:</td>
					<td class="help">&nbsp;</td>
					<td><mark class="error"><span class="dashicons dashicons-warning"></span></mark></td>
				</tr>
				<?php
			}
		?>
	</tbody>
</table>