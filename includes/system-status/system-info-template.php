<?php 
global $wpdb;
 $environment  		= $this->get_environment_info();
 $database     		= $this->get_database_info();
 $post_type_counts  = $this->get_post_type_counts();
 $security			= $this->get_security_info();
 $active_plugins	= $this->get_active_plugins();
 $theme	      		= $this->get_Theme_info();
 $php_information 	= $this->php_information();
 
 $atbdp_option = get_option('atbdp_option');
?>
<!-- WordPress environment -->
<table class="atbdp_status_table widefat" cellspacing="0" id="status">
    <thead>
        <tr>
            <th colspan="3" data-export-label="WordPress Environment"><h2><?php _e( 'WordPress environment', 'directorist' ); ?></h2></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td data-export-label="Home URL"><?php _e( 'Home URL', 'directorist' ); ?>:</td>
            <td class="help"><?php echo $this->directorist_help_tip( __( 'The homepage URL of your site.', 'directorist' ) ); ?></td>
            <td><?php echo esc_html( $environment['home_url'] ) ?></td>
        </tr>
        <tr>
            <td data-export-label="Site URL"><?php _e( 'Site URL', 'directorist' ); ?>:</td>
            <td class="help"><?php echo $this->directorist_help_tip( __( 'The root URL of your site.', 'directorist' ) ); ?></td>
            <td><?php echo esc_html( $environment['site_url'] ) ?></td>
        </tr>
        <tr>
            <td data-export-label="Directorist Version"><?php _e( 'Directorist version', 'directorist' ); ?>:</td>
            <td class="help"><?php echo $this->directorist_help_tip( __( 'The version of GeoDirectory installed on your site.', 'directorist' ) ); ?></td>
            <td><?php echo esc_html( $environment['version'] ) ?></td>
        </tr>
        <tr>
            <td data-export-label="WP Version"><?php _e( 'WP version', 'directorist' ); ?>:</td>
            <td class="help"><?php echo $this->directorist_help_tip( __( 'The version of WordPress installed on your site.', 'directorist' ) ); ?></td>
            <td><?php echo esc_html( $environment['wp_version'] ) ?></td>
        </tr>
        <tr>
            <td data-export-label="WP Multisite"><?php _e( 'WP multisite', 'directorist' ); ?>:</td>
            <td class="help"><?php echo $this->directorist_help_tip( __( 'Whether or not you have WordPress Multisite enabled.', 'directorist' ) ); ?></td>
            <td><?php echo ( $environment['wp_multisite'] ) ? '<span class="dashicons dashicons-yes"></span>' : '&ndash;'; ?></td>
        </tr>
        <tr>
            <td data-export-label="WP Memory Limit"><?php _e( 'WP memory limit', 'directorist' ); ?>:</td>
            <td class="help"><?php echo $this->directorist_help_tip( __( 'The maximum amount of memory (RAM) that your site can use at one time.', 'directorist' ) ); ?></td>
            <td><?php
				if ( $environment['wp_memory_limit'] < 67108864 ) {
					echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( __( '%1$s - We recommend setting memory to at least 64MB. See: %2$s', 'directorist' ), size_format( $environment['wp_memory_limit'] ), '<a href="https://codex.wordpress.org/Editing_wp-config.php#Increasing_memory_allocated_to_PHP" target="_blank">' . __( 'Increasing memory allocated to PHP', 'directorist' ) . '</a>' ) . '</mark>';
				} else {
					echo '<mark class="yes">' . size_format( $environment['wp_memory_limit'] ) . '</mark>';
				}
			?></td>
        </tr>
        <tr>
            <td data-export-label="WP Debug Mode"><?php _e( 'WP debug mode', 'directorist' ); ?>:</td>
            <td class="help"><?php echo $this->directorist_help_tip( __( 'Displays whether or not WordPress is in Debug Mode.', 'directorist' ) ); ?></td>
            <td>
                <?php if ( $environment['wp_debug_mode'] ) : ?>
					<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>
				<?php else : ?>
					<mark class="no">&ndash;</mark>
				<?php endif; ?>
            </td>
        </tr>
        <tr>
            <td data-export-label="WP Cron"><?php _e( 'WP cron', 'directorist' ); ?>:</td>
            <td class="help"><?php echo $this->directorist_help_tip( __( 'Displays whether or not WP Cron Jobs are enabled.', 'directorist' ) ); ?></td>
            <td>
                <?php if ( $environment['wp_cron'] ) : ?>
					<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>
				<?php else : ?>
					<mark class="no">&ndash;</mark>
				<?php endif; ?>
            </td>
        </tr>
        <tr>
            <td data-export-label="Language"><?php _e( 'Language', 'directorist' ); ?>:</td>
            <td class="help"><?php echo $this->directorist_help_tip( __( 'The current language used by WordPress. Default = English', 'directorist' ) ); ?></td>
            <td><?php echo esc_html( $environment['language'] ) ?></td>
        </tr>
    </tbody>
</table>
<!-- PHP -->
<table class="atbdp_status_table widefat" cellspacing="0" id="status">
    <thead>
        <tr>
            <th colspan="3" data-export-label="PHP"><h2><?php _e( 'PHP', 'directorist' ); ?></h2></th>
        </tr>
    </thead>
    <tbody>
		<?php foreach( $php_information as $item => $value) { ?>
        <tr>
            <td data-export-label="<?php echo $item; ?>"><?php echo $item; ?>:</td>
            <td><?php echo $value; ?></td>
		</tr>
		<?php } ?>
    </tbody>
</table>
 <!-- Server environment -->
<table class="atbdp_status_table widefat" cellspacing="0">
	<thead>
		<tr>
			<th colspan="3" data-export-label="Server Environment"><h2><?php _e( 'Server environment', 'directorist' ); ?></h2></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td data-export-label="Server Info"><?php _e( 'Server info', 'directorist' ); ?>:</td>
			<td class="help"><?php echo $this->directorist_help_tip( __( 'Information about the web server that is currently hosting your site.', 'directorist' ) ); ?></td>
			<td><?php echo esc_html( $environment['server_info'] ); ?></td>
		</tr>
		<?php
		if ( $wpdb->use_mysqli ) {
			$ver = mysqli_get_server_info( $wpdb->dbh );
		} elseif(function_exists('mysql_get_server_info')) {
			$ver = mysql_get_server_info();
		}else{
			$ver = '';
		}
		if ( ! empty( $wpdb->is_mysql ) && ! stristr( $ver, 'MariaDB' ) ) : ?>
			<tr>
				<td data-export-label="MySQL Version"><?php _e( 'MySQL version', 'directorist' ); ?>:</td>
				<td class="help"><?php echo $this->directorist_help_tip( __( 'The version of MySQL installed on your hosting server.', 'directorist' ) ); ?></td>
				<td>
					<?php
					if ( version_compare( $environment['mysql_version'], '5.6', '<' ) ) {
						echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( __( '%1$s - We recommend a minimum MySQL version of 5.6. See: %2$s', 'directorist' ), esc_html( $environment['mysql_version'] ), '<a href="https://wordpress.org/about/requirements/" target="_blank">' . __( 'WordPress requirements', 'directorist' ) . '</a>' ) . '</mark>';
					} else {
						echo '<mark class="yes">' . esc_html( $environment['mysql_version'] ) . '</mark>';
					}
					?>
				</td>
			</tr>
		<?php endif; ?>
		<tr>
			<td data-export-label="Max Upload Size"><?php _e( 'Max upload size', 'directorist' ); ?>:</td>
			<td class="help"><?php echo $this->directorist_help_tip( __( 'The largest filesize that can be uploaded to your WordPress installation.', 'directorist' ) ); ?></td>
			<td><?php echo size_format( $environment['max_upload_size'] ) ?></td>
		</tr>
		<tr>
			<td data-export-label="Default Timezone is UTC"><?php _e( 'Default timezone is UTC', 'directorist' ); ?>:</td>
			<td class="help"><?php echo $this->directorist_help_tip( __( 'The default timezone for your server.', 'directorist' ) ); ?></td>
			<td><?php
				if ( 'UTC' !== $environment['default_timezone'] ) {
					echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( __( 'Default timezone is %s - it should be UTC', 'directorist' ), $environment['default_timezone'] ) . '</mark>';
				} else {
					echo '<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>';
				} ?>
			</td>
		</tr>
		<tr>
			<td data-export-label="fsockopen/cURL"><?php _e( 'fsockopen/cURL', 'directorist' ); ?>:</td>
			<td class="help"><?php echo $this->directorist_help_tip( __( 'Payment gateways can use cURL to communicate with remote servers to authorize payments, other plugins may also use it when communicating with remote services.', 'directorist' ) ); ?></td>
			<td><?php
				if ( $environment['fsockopen_or_curl_enabled'] ) {
					echo '<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>';
				} else {
					echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . __( 'Your server does not have fsockopen or cURL enabled - PayPal IPN and other scripts which communicate with other servers will not work. Contact your hosting provider.', 'directorist' ) . '</mark>';
				} ?>
			</td>
		</tr>
		<tr>
			<td data-export-label="SoapClient"><?php _e( 'SoapClient', 'directorist' ); ?>:</td>
			<td class="help"><?php echo $this->directorist_help_tip( __( 'Some webservices like shipping use SOAP to get information from remote servers, for example, live shipping quotes from FedEx require SOAP to be installed.', 'directorist' ) ); ?></td>
			<td><?php
				if ( $environment['soapclient_enabled'] ) {
					echo '<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>';
				} else {
					echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( __( 'Your server does not have the %s class enabled - some gateway plugins which use SOAP may not work as expected.', 'directorist' ), '<a href="https://php.net/manual/en/class.soapclient.php">SoapClient</a>' ) . '</mark>';
				} ?>
			</td>
		</tr>
		<tr>
			<td data-export-label="DOMDocument"><?php _e( 'DOMDocument', 'directorist' ); ?>:</td>
			<td class="help"><?php echo $this->directorist_help_tip( __( 'HTML/Multipart emails use DOMDocument to generate inline CSS in templates.', 'directorist' ) ); ?></td>
			<td><?php
				if ( $environment['domdocument_enabled'] ) {
					echo '<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>';
				} else {
					echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( __( 'Your server does not have the %s class enabled - HTML/Multipart emails, and also some extensions, will not work without DOMDocument.', 'directorist' ), '<a href="https://php.net/manual/en/class.domdocument.php">DOMDocument</a>' ) . '</mark>';
				} ?>
			</td>
		</tr>
		<tr>
			<td data-export-label="GZip"><?php _e( 'GZip', 'directorist' ); ?>:</td>
			<td class="help"><?php echo $this->directorist_help_tip( __( 'GZip (gzopen) is used to open the GEOIP database from MaxMind.', 'directorist' ) ); ?></td>
			<td><?php
				if ( $environment['gzip_enabled'] ) {
					echo '<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>';
				} else {
					echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( __( 'Your server does not support the %s function - this is required to use the GeoIP database from MaxMind.', 'directorist' ), '<a href="https://php.net/manual/en/zlib.installation.php">gzopen</a>' ) . '</mark>';
				} ?>
			</td>
		</tr>
		<tr>
			<td data-export-label="Multibyte String"><?php _e( 'Multibyte string', 'directorist' ); ?>:</td>
			<td class="help"><?php echo $this->directorist_help_tip( __( 'Multibyte String (mbstring) is used to convert character encoding, like for emails or converting characters to lowercase.', 'directorist' ) ); ?></td>
			<td><?php
				if ( $environment['mbstring_enabled'] ) {
					echo '<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>';
				} else {
					echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( __( 'Your server does not support the %s functions - this is required for better character encoding. Some fallbacks will be used instead for it.', 'directorist' ), '<a href="https://php.net/manual/en/mbstring.installation.php">mbstring</a>' ) . '</mark>';
				} ?>
			</td>
		</tr>
		<tr>
			<td data-export-label="Remote Post"><?php _e( 'Remote post', 'directorist' ); ?>:</td>
			<td class="help"><?php echo $this->directorist_help_tip( __( 'PayPal uses this method of communicating when sending back transaction information.', 'directorist' ) ); ?></td>
			<td><?php
				if ( $environment['remote_post_successful'] ) {
					echo '<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>';
				} else {
					echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( __( '%s failed. Contact your hosting provider.', 'directorist' ), 'wp_remote_post()' ) . ' ' . esc_html( $environment['remote_post_response'] ) . '</mark>';
				} ?>
			</td>
		</tr>
		<tr>
			<td data-export-label="Remote Get"><?php _e( 'Remote get', 'directorist' ); ?>:</td>
			<td class="help"><?php echo $this->directorist_help_tip( __( 'GeoDirectory plugins may use this method of communication when checking for plugin updates.', 'directorist' ) ); ?></td>
			<td><?php
				if ( $environment['remote_get_successful'] ) {
					echo '<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>';
				} else {
					echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( __( '%s failed. Contact your hosting provider.', 'directorist' ), 'wp_remote_get()' ) . ' ' . esc_html( $environment['remote_get_response'] ) . '</mark>';
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
				<td class="help"><?php echo isset( $row['help'] ) ? $row['help'] : ''; ?></td>
				<td>
					<mark class="<?php echo esc_attr( $css_class ); ?>">
						<?php echo $icon; ?>  <?php echo ! empty( $row['note'] ) ? wp_kses_data( $row['note'] ) : ''; ?>
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
			<th colspan="3" data-export-label="User platform"><h2><?php _e( 'User platform', 'directorist' ); ?></h2></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td data-export-label="Platform"><?php _e( 'Platform', 'directorist' ); ?>:</td>
			<td class="help"></td>
			<td><?php echo esc_html( $environment['platform'] ); ?></td>
		</tr>
		<tr>
			<td data-export-label="Browser name"><?php _e( 'Browser name', 'directorist' ); ?>:</td>
			<td class="help"></td>
			<td><?php echo esc_html( $environment['browser_name'] ); ?></td>
		</tr>
		<tr>
			<td data-export-label="Browser version"><?php _e( 'Browser version', 'directorist' ); ?>:</td>
			<td class="help"></td>
			<td><?php echo esc_html( $environment['browser_version'] ); ?></td>
		</tr>
		<tr>
			<td data-export-label="User agent"><?php _e( 'User agent', 'directorist' ); ?>:</td>
			<td class="help"></td>
			<td><?php echo esc_html( $environment['user_agent'] ); ?></td>
		</tr>
	</tbody>
</table>
<!-- Settings -->
<table class="atbdp_status_table widefat" cellspacing="0">
    <thead>
        <tr>
            <th colspan="3" data-export-label="User platform"><h2><?php _e( 'Settings', 'directorist' ); ?></h2></th>
        </th>
        </tr>
    </thead>
    <tbody>
        <?php 
        if ( ! empty( $atbdp_option ) ) : 
            foreach ($atbdp_option as $name => $value) {
            ?>
            <tr>
                <td data-export-label="<?php echo !empty( $name ) ? $name: ''; ?>"> <?php echo !empty( $name ) ? $name: ''; ?> </td>
                <td> <?php print_r($value) ?> </td>
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
        <th colspan="3" data-export-label="Database"><h2><?php _e( 'Database', 'directorist' ); ?></h2></th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td data-export-label="Directorist Database Prefix"><?php _e( 'Database prefix', 'directorist' ); ?></td>
        <td class="help">&nbsp;</td>
        <td><?php
			if ( strlen( $database['database_prefix'] ) > 20 ) {
				echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . sprintf( __( '%1$s - We recommend using a prefix with less than 20 characters.', 'directorist' ), esc_html( $database['database_prefix'] ) ) . '</mark>';
			} else {
				echo '<mark class="yes">' . esc_html( $database['database_prefix'] ) . '</mark>';
			}
			?>
        </td>
    </tr>
    <tr>
        <td><?php _e( 'Total Database Size', 'directorist' ); ?></td>
        <td class="help">&nbsp;</td>
        <td><?php printf( '%.2fMB', $database['database_size']['data'] + $database['database_size']['index'] ); ?></td>
    </tr>

    <tr>
        <td><?php _e( 'Database Data Size', 'directorist' ); ?></td>
        <td class="help">&nbsp;</td>
        <td><?php printf( '%.2fMB', $database['database_size']['data'] ); ?></td>
    </tr>

    <tr>
        <td><?php _e( 'Database Index Size', 'directorist' ); ?></td>
        <td class="help">&nbsp;</td>
        <td><?php printf( '%.2fMB', $database['database_size']['index'] ); ?></td>
    </tr>

	<?php foreach ( $database['database_tables']['directorist'] as $table => $table_data ) { ?>
    <tr>
        <td><?php echo esc_html( $table ); ?></td>
        <td class="help">&nbsp;</td>
        <td>
			<?php if( ! $table_data ) {
				echo '<mark class="error"><span class="dashicons dashicons-warning"></span> ' . __( 'Table does not exist', 'directorist' ) . '</mark>';
			} else {
				printf( __( 'Data: %.2fMB + Index: %.2fMB', 'directorist' ), $this->directorist_help_tip( $table_data['data'], 2 ), $this->directorist_help_tip( $table_data['index'], 2 ) );
			} ?>
        </td>
        </tr>
    <?php } ?>

    <?php foreach ( $database['database_tables']['other'] as $table => $table_data ) { ?>
        <tr>
            <td><?php echo esc_html( $table ); ?></td>
            <td class="help">&nbsp;</td>
            <td>
			    <?php printf( __( 'Data: %.2fMB + Index: %.2fMB', 'directorist' ), $this->directorist_help_tip( $table_data['data'], 2 ), $this->directorist_help_tip( $table_data['index'], 2 ) ); ?>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>
<!-- Post type counts -->
<table class="atbdp_status_table widefat" cellspacing="0">
    <thead>
    <tr>
        <th colspan="3" data-export-label="Post Type Counts"><h2><?php _e( 'Post Type Counts', 'directorist' ); ?></h2></th>
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
			<th colspan="3" data-export-label="Security"><h2><?php _e( 'Security', 'directorist' ); ?></h2></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td data-export-label="Secure connection (HTTPS)"><?php _e( 'Secure connection (HTTPS)', 'directorist' ); ?>:</td>
			<td class="help"><?php echo $this->directorist_help_tip( __( 'Is the connection to your site secure?', 'directorist' ) ); ?></td>
			<td>
				<?php if ( $security['secure_connection'] ) : ?>
					<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>
				<?php else : ?>
					<mark class="error"><span class="dashicons dashicons-warning"></span><?php echo __( 'HTTPS is not enabled on your site.', 'directorist' ); ?></mark>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<td data-export-label="Hide errors from visitors"><?php _e( 'Hide errors from visitors', 'directorist' ); ?></td>
			<td class="help"><?php echo $this->directorist_help_tip( __( 'Error messages can contain sensitive information about your site environment. These should be hidden from untrusted visitors.', 'directorist' ) ); ?></td>
			<td>
				<?php if ( $security['hide_errors'] ) : ?>
					<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>
				<?php else : ?>
					<mark class="error"><span class="dashicons dashicons-warning"></span><?php _e( 'Error messages should not be shown to visitors.', 'directorist' ); ?></mark>
				<?php endif; ?>
			</td>
		</tr>
	</tbody>
</table>
<!-- Active Plugins -->
<table class="adbdp_status_table widefat" cellspacing="0">
	<thead>
		<tr>
			<th colspan="3" data-export-label="Active Plugins (<?php echo count( $active_plugins ) ?>)"><h2><?php _e( 'Active plugins', 'directorist' ); ?> (<?php echo count( $active_plugins ) ?>)</h2></th>
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
					$plugin_name = '<a href="' . esc_url( $plugin['url'] ) . '" aria-label="' . esc_attr__( 'Visit plugin homepage' , 'directorist' ) . '" target="_blank">' . $plugin_name . '</a>';
				}

				$version_string = '';
				$network_string = '';
				if ( ! empty( $plugin['latest_verison'] ) && version_compare( $plugin['latest_verison'], $plugin['version'], '>' ) ) {
					/* translators: %s: plugin latest version */
					$version_string = ' &ndash; <strong style="color:red;">' . sprintf( esc_html__( '%s is available', 'directorist' ), $plugin['latest_verison'] ) . '</strong>';
				}

				if ( false != $plugin['network_activated'] ) {
					$network_string = ' &ndash; <strong style="color:black;">' . __( 'Network enabled', 'directorist' ) . '</strong>';
				}
				?>
				<tr>
					<td><?php echo $plugin_name; ?></td>
					<td class="help">&nbsp;</td>
					<td><?php
						/* translators: %s: plugin author */
						printf( __( 'by %s', 'directorist' ), $plugin['author_name'] );
						echo ' &ndash; ' . esc_html( $plugin['version'] ) . $version_string . $network_string;
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
			<th colspan="3" data-export-label="Theme"><h2><?php _e( 'Theme', 'directorist' ); ?></h2></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td data-export-label="Name"><?php _e( 'Name', 'directorist' ); ?>:</td>
			<td class="help"><?php echo $this->directorist_help_tip( __( 'The name of the current active theme.', 'directorist' ) ); ?></td>
			<td><?php echo esc_html( $theme['name'] ) ?></td>
		</tr>
		<tr>
			<td data-export-label="Version"><?php _e( 'Version', 'directorist' ); ?>:</td>
			<td class="help"><?php echo $this->directorist_help_tip( __( 'The installed version of the current active theme.', 'directorist' ) ); ?></td>
			<td><?php
				echo esc_html( $theme['version'] );
				if ( version_compare( $theme['version'], $theme['version_latest'], '<' ) ) {
					/* translators: %s: theme latest version */
					echo ' &ndash; <strong style="color:red;">' . sprintf( __( '%s is available', 'directorist' ), esc_html( $theme['version_latest'] ) ) . '</strong>';
				}
			?></td>
		</tr>
		<tr>
			<td data-export-label="Author URL"><?php _e( 'Author URL', 'directorist' ); ?>:</td>
			<td class="help"><?php echo $this->directorist_help_tip( __( 'The theme developers URL.', 'directorist' ) ); ?></td>
			<td><?php echo esc_html( $theme['author_url'] ) ?></td>
		</tr>
		<tr>
			<td data-export-label="Child Theme"><?php _e( 'Child theme', 'directorist' ); ?>:</td>
			<td class="help"><?php echo $this->directorist_help_tip( __( 'Displays whether or not the current theme is a child theme.', 'directorist' ) ); ?></td>
			<td><?php
				echo $theme['is_child_theme'] ? '<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>' : '<span class="dashicons dashicons-no-alt"></span> &ndash; ' . sprintf( __( 'If you are modifying Directorist on a parent theme that you did not build personally we recommend using a child theme. See: <a href="%s" target="_blank">How to create a child theme</a>', 'directorist' ), 'https://developer.wordpress.org/themes/advanced-topics/child-themes/' );
			?></td>
		</tr>
		<?php
		if ( $theme['is_child_theme'] ) :
		?>
		<tr>
			<td data-export-label="Parent Theme Name"><?php _e( 'Parent theme name', 'directorist' ); ?>:</td>
			<td class="help"><?php echo $this->directorist_help_tip( __( 'The name of the parent theme.', 'directorist' ) ); ?></td>
			<td><?php echo esc_html( $theme['parent_name'] ); ?></td>
		</tr>
		<tr>
			<td data-export-label="Parent Theme Version"><?php _e( 'Parent theme version', 'directorist' ); ?>:</td>
			<td class="help"><?php echo $this->directorist_help_tip( __( 'The installed version of the parent theme.', 'directorist' ) ); ?></td>
			<td><?php
				echo esc_html( $theme['parent_version'] );
				if ( version_compare( $theme['parent_version'], $theme['parent_latest_verison'], '<' ) ) {
					/* translators: %s: parant theme latest version */
					echo ' &ndash; <strong style="color:red;">' . sprintf( __( '%s is available', 'directorist' ), esc_html( $theme['parent_latest_verison'] ) ) . '</strong>';
				}
			?></td>
		</tr>
		<tr>
			<td data-export-label="Parent Theme Author URL"><?php _e( 'Parent theme author URL', 'directorist' ); ?>:</td>
			<td class="help"><?php echo $this->directorist_help_tip( __( 'The parent theme developers URL.', 'directorist' ) ); ?></td>
			<td><?php echo esc_html( $theme['parent_author_url'] ) ?></td>
		</tr>
		<?php endif ?>
	</tbody>
</table>
<!-- Template Overrides -->
<table class="atbdp_status_table widefat" cellspacing="0">
	<thead>
		<tr>
			<th colspan="3" data-export-label="Templates"><h2><?php _e( 'Templates', 'directorist' ); ?><?php echo $this->directorist_help_tip( __( 'This section shows any files that are overriding the default GeoDirectory template pages.', 'directorist' ) ); ?></h2></th>
		</tr>
	</thead>
	<tbody>
		<?php 		
			if ( ! empty( $theme['overrides'] ) ) { ?>
					<tr>
						<td data-export-label="Overrides"><?php _e( 'Overrides', 'directorist' ); ?></td>
						<td class="help">&nbsp;</td>
						<td>
							<?php
							$total_overrides = count( $theme['overrides'] );
							for ( $i = 0; $i < $total_overrides; $i++ ) {
								$override = $theme['overrides'][ $i ];
								if ( $override['core_version'] && ( empty( $override['version'] ) || version_compare( $override['version'], $override['core_version'], '<' ) ) ) {
									$current_version = $override['version'] ? $override['version'] : '-';
									printf(
										__( '%1$s version %2$s is out of date. The core version is %3$s', 'directorist' ),
										'<code>' . $override['file'] . '</code>',
										'<strong style="color:red">' . $current_version . '</strong>',
										$override['core_version']
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
					<td data-export-label="Overrides"><?php _e( 'Overrides', 'directorist' ); ?>:</td>
					<td class="help">&nbsp;</td>
					<td>&ndash;</td>
				</tr>
				<?php
			}

			if ( true === $theme['has_outdated_templates'] ) {
				?>
				<tr>
					<td data-export-label="Outdated Templates"><?php _e( 'Outdated templates', 'directorist' ); ?>:</td>
					<td class="help">&nbsp;</td>
					<td><mark class="error"><span class="dashicons dashicons-warning"></span></mark></td>
				</tr>
				<?php
			}
		?>
	</tbody>
</table>