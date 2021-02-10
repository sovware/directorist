<?php
/**
 * @author AazzTech
 */

use Directorist\Helper;

class ATBDP_System_Info_Email_Link
{
    public function __construct() { 
        include_once  ATBDP_INC_DIR . '/system-status/system-info-template.php'; 
    }

    public function get_environment_info() {
		global $wpdb;

		// Figure out cURL version, if installed.
		$curl_version = '';
		if ( function_exists( 'curl_version' ) ) {
			$curl_version = curl_version();
			$curl_version = $curl_version['version'] . ', ' . $curl_version['ssl_version'];
		}

		// WP memory limit
		$wp_memory_limit = $this->directorist_let_to_num( WP_MEMORY_LIMIT );
		if ( function_exists( 'memory_get_usage' ) ) {
			$wp_memory_limit = max( $wp_memory_limit, $this->directorist_let_to_num( @ini_get( 'memory_limit' ) ) );
		}
		
		// User agent
		$user_agent = isset( $_SERVER['HTTP_USER_AGENT'] ) ? $_SERVER['HTTP_USER_AGENT'] : '';

		// Test POST requests
		$post_response = wp_safe_remote_post( 'http://api.wordpress.org/core/browse-happy/1.1/', array(
			'timeout'     => 10,
			'user-agent'  => 'WordPress/' . get_bloginfo( 'version' ) . '; ' . home_url(),
			'httpversion' => '1.1',
			'body'        => array(
				'useragent'	=> $user_agent,
			),
		) );
		
		$post_response_body = NULL;
		$post_response_successful = false;
		if ( ! is_wp_error( $post_response ) && $post_response['response']['code'] >= 200 && $post_response['response']['code'] < 300 ) {
			$post_response_successful = true;
			$post_response_body = json_decode( wp_remote_retrieve_body( $post_response ), true );
		}

		// Test GET requests
		$get_response = wp_safe_remote_get( 'https://plugins.svn.wordpress.org/directorist/trunk/readme.txt', array(
			'timeout'     => 10,
			'user-agent'  => 'Directorist/' . ATBDP_VERSION,
			'httpversion' => '1.1'
		) );
		$get_response_successful = false;
		if ( ! is_wp_error( $post_response ) && $post_response['response']['code'] >= 200 && $post_response['response']['code'] < 300 ) {
			$get_response_successful = true;
		}

		// Return all environment info. Described by JSON Schema.
		return array(
			'home_url'                  => get_option( 'home' ),
			'site_url'                  => get_option( 'siteurl' ),
			'version'                   => ATBDP_VERSION,
			'wp_version'                => get_bloginfo( 'version' ),
			'wp_multisite'              => is_multisite(),
			'wp_memory_limit'           => $wp_memory_limit,
			'wp_debug_mode'             => ( defined( 'WP_DEBUG' ) && WP_DEBUG ),
			'wp_cron'                   => ! ( defined( 'DISABLE_WP_CRON' ) && DISABLE_WP_CRON ),
			'language'                  => get_locale(),
			'server_info'               => $_SERVER['SERVER_SOFTWARE'],
			'php_version'               => phpversion(),
			'php_post_max_size'         => $this->directorist_let_to_num( ini_get( 'post_max_size' ) ),
			'php_max_execution_time'    => ini_get( 'max_execution_time' ),
			'php_max_input_vars'        => ini_get( 'max_input_vars' ),
			'curl_version'              => $curl_version,
			'suhosin_installed'         => extension_loaded( 'suhosin' ),
			'max_upload_size'           => wp_max_upload_size(),
			'mysql_version'             => ( ! empty( $wpdb->is_mysql ) ? $wpdb->db_version() : '' ),
			'default_timezone'          => date_default_timezone_get(),
			'fsockopen_or_curl_enabled' => ( function_exists( 'fsockopen' ) || function_exists( 'curl_init' ) ),
			'soapclient_enabled'        => class_exists( 'SoapClient' ),
			'domdocument_enabled'       => class_exists( 'DOMDocument' ),
			'gzip_enabled'              => is_callable( 'gzopen' ),
			'mbstring_enabled'          => extension_loaded( 'mbstring' ),
			'remote_post_successful'    => $post_response_successful,
			'remote_post_response'      => ( is_wp_error( $post_response ) ? $post_response->get_error_message() : $post_response['response']['code'] ),
			'remote_get_successful'     => $get_response_successful,
			'remote_get_response'       => ( is_wp_error( $get_response ) ? $get_response->get_error_message() : $get_response['response']['code'] ),
			'platform'       			=> ! empty( $post_response_body['platform'] ) ? $post_response_body['platform'] : '-',
			'browser_name'       		=> ! empty( $post_response_body['name'] ) ? $post_response_body['name'] : '-',
			'browser_version'       	=> ! empty( $post_response_body['version'] ) ? $post_response_body['version'] : '-',
			'user_agent'       			=> $user_agent
		);
    }

    /**
	 * Get array of database information. Version, prefix, and table existence.
	 *
	 * @return array
	 */
	public function get_database_info() {
		global $wpdb;

		$tables        = array();
		$database_size = array();

		// It is not possible to get the database name from some classes that replace wpdb (e.g., HyperDB)
		// and that is why this if condition is needed.
		if ( defined( 'DB_NAME' ) ) {
			$database_table_information = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT
					    table_name AS 'name',
						engine AS 'engine',
					    round( ( data_length / 1024 / 1024 ), 2 ) 'data',
					    round( ( index_length / 1024 / 1024 ), 2 ) 'index'
					FROM information_schema.TABLES
					WHERE table_schema = %s
					ORDER BY name ASC;",
					DB_NAME
				)
			);

			// WC Core tables to check existence of.
			$core_tables = apply_filters(
				'atbdp_database_tables',
				array(
					'atbdp_review',
				)
			);

			/**
			 * Adding the prefix to the tables array, for backwards compatibility.
			 *
			 * If we changed the tables above to include the prefix, then any filters against that table could break.
			 */
			$core_tables = array_map( array( $this, 'add_db_table_prefix' ), $core_tables );

			/**
			 * Organize WooCommerce and non-WooCommerce tables separately for display purposes later.
			 *
			 * To ensure we include all WC tables, even if they do not exist, pre-populate the WC array with all the tables.
			 */
			$tables = array(
				'directorist' => array_fill_keys( $core_tables, false ),
				'other'       => array(),
			);

			$database_size = array(
				'data'  => 0,
				'index' => 0,
			);

			$site_tables_prefix = $wpdb->get_blog_prefix( get_current_blog_id() );
			$global_tables      = $wpdb->tables( 'global', true );
			foreach ( $database_table_information as $table ) {
				// Only include tables matching the prefix of the current site, this is to prevent displaying all tables on a MS install not relating to the current.
				if ( is_multisite() && 0 !== strpos( $table->name, $site_tables_prefix ) && ! in_array( $table->name, $global_tables, true ) ) {
					continue;
				}
				$table_type = in_array( $table->name, $core_tables, true ) ? 'directorist' : 'other';

				$tables[ $table_type ][ $table->name ] = array(
					'data'   => $table->data,
					'index'  => $table->index,
					'engine' => $table->engine,
				);

				$database_size['data']  += $table->data;
				$database_size['index'] += $table->index;
			}
		}

		// Return all database info. Described by JSON Schema.
		return array(
			'database_prefix'        => $wpdb->prefix,
			'maxmind_geoip_database' => '',
			'database_tables'        => $tables,
			'database_size'          => $database_size,
		);
    }

    /**
	 * Add prefix to table.
	 *
	 * @param string $table table name
	 * @return strong
	 */
	protected function add_db_table_prefix( $table ) {
		global $wpdb;
		return $wpdb->prefix . $table;
    }
    
    /**
	 * Get array of counts of objects. Orders, products, etc.
	 *
	 * @return array
	 */
	public function get_post_type_counts() {
		global $wpdb;

		$post_type_counts = $wpdb->get_results( "SELECT post_type AS 'type', count(1) AS 'count' FROM {$wpdb->posts} GROUP BY post_type;" );

		return is_array( $post_type_counts ) ? $post_type_counts : array();
    }

     /**
	 * Returns security tips.
	 *
	 * @return array
	 */
	public function get_security_info() {
		$check_page = get_home_url();
		return array(
			'secure_connection' => 'https' === substr( $check_page, 0, 5 ),
			'hide_errors'       => ! ( defined( 'WP_DEBUG' ) && defined( 'WP_DEBUG_DISPLAY' ) && WP_DEBUG && WP_DEBUG_DISPLAY ) || 0 === intval( ini_get( 'display_errors' ) ),
		);
    }

    /**
	 * Get a list of plugins active on the site.
	 *
	 * @return array
	 */
	public function get_active_plugins() {
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		require_once( ABSPATH . 'wp-admin/includes/update.php' );

		if ( ! function_exists( 'get_plugin_updates' ) ) {
			return array();
		}

		// Get both site plugins and network plugins
		$active_plugins = (array) get_option( 'active_plugins', array() );
		if ( is_multisite() ) {
			$network_activated_plugins = array_keys( get_site_option( 'active_sitewide_plugins', array() ) );
			$active_plugins            = array_merge( $active_plugins, $network_activated_plugins );
		}

		$active_plugins_data = array();
		$available_updates   = get_plugin_updates();

		foreach ( $active_plugins as $plugin ) {
			$data           = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );

			// convert plugin data to json response format.
			$active_plugins_data[] = array(
				'plugin'            => $plugin,
				'name'              => $data['Name'],
				'version'           => $data['Version'],
				'url'               => $data['PluginURI'],
				'author_name'       => $data['AuthorName'],
				'author_url'        => esc_url_raw( $data['AuthorURI'] ),
				'network_activated' => $data['Network'],
				'latest_verison'	=> ( array_key_exists( $plugin, $available_updates ) ) ? $available_updates[$plugin]->update->new_version : $data['Version']
			);
		}

		return $active_plugins_data;
    }

    /**
	 * Get info on the current active theme, info on parent theme (if presnet)
	 * and a list of template overrides.
	 *
	 * @return array
	 */
	public function get_theme_info() {
		$active_theme = wp_get_theme();

		// Get parent theme info if this theme is a child theme, otherwise
		// pass empty info in the response.
		if ( is_child_theme() ) {
			$parent_theme      = wp_get_theme( $active_theme->template );
			$parent_theme_info = array(
				'parent_name'           => $parent_theme->name,
				'parent_version'        => $parent_theme->version,
				'parent_version_latest' => self::get_latest_theme_version( $parent_theme ),
				'parent_author_url'     => $parent_theme->{'Author URI'},
			);
		} else {
			$parent_theme_info = array(
				'parent_name'           => '',
				'parent_version'        => '',
				'parent_version_latest' => '',
				'parent_author_url'     => '',
			);
		}

		/**
		 * Scan the theme directory for all templates to see if our theme
		 * overrides any of them.
		 */
		$override_files     = array();
		$outdated_templates = false;
		$scan_files         = self::scan_template_files( Helper::template_directory() );
		foreach ( $scan_files as $file ) {
			$located = $file;

			if ( file_exists( $located ) ) {
				$theme_file = $located;
			} elseif ( file_exists( get_stylesheet_directory() . '/' . $file ) ) {
				$theme_file = get_stylesheet_directory() . '/' . $file;
			} elseif ( file_exists( get_stylesheet_directory() . '/' . 'directorist/' . $file ) ) {
				$theme_file = get_stylesheet_directory() . '/' . 'directorist/' . $file;
			} elseif ( file_exists( get_template_directory() . '/' . $file ) ) {
				$theme_file = get_template_directory() . '/' . $file;
			} elseif ( file_exists( get_template_directory() . '/' . 'directorist/' . $file ) ) {
				$theme_file = get_template_directory() . '/' . 'directorist/' . $file;
			} else {
				$theme_file = false;
			}
			
			if ( ! empty( $theme_file ) ) {
				$core_version  = self::get_file_version( ATBDP_DIR . '/templates' . $file );
				$theme_version = self::get_file_version( $theme_file );
				if ( $core_version && ( empty( $theme_version ) || version_compare( $theme_version, $core_version, '<' ) ) ) {
					if ( ! $outdated_templates ) {
						$outdated_templates = true;
					}
				}
				$override_files[] = array(
					'file'         => str_replace( WP_CONTENT_DIR . '/themes/', '', $theme_file ),
					'version'      => $theme_version,
					'core_version' => $core_version,
				);
			}
		}

		$active_theme_info = array(
			'name'                    => $active_theme->name,
			'version'                 => $active_theme->version,
			'version_latest'          => self::get_latest_theme_version( $active_theme ),
			'author_url'              => esc_url_raw( $active_theme->{'Author URI'} ),
			'is_child_theme'          => is_child_theme(),
			'has_outdated_templates'  => $outdated_templates,
			'overrides'               => $override_files,
		);

		return array_merge( $active_theme_info, $parent_theme_info );
    }
    
    /**
	 * Get latest version of a theme by slug.
     *
     * @since 2.0.0
     *
	 * @param  object $theme WP_Theme object.
	 * @return string Version number if found.
	 */
	public static function get_latest_theme_version( $theme ) {
		include_once( ABSPATH . 'wp-admin/includes/theme.php' );

		$api = themes_api( 'theme_information', array(
			'slug'     => $theme->get_stylesheet(),
			'fields'   => array(
				'sections' => false,
				'tags'     => false,
			),
		) );

		$update_theme_version = 0;

		// Check .org for updates.
		if ( is_object( $api ) && ! is_wp_error( $api ) ) {
			if ( isset( $api->version ) ) {
				$update_theme_version = $api->version;
			} else if ( isset( $api->stable_version ) ) {
				$update_theme_version = $api->stable_version;
			}

		// Check GeoDirectory Theme Version.
		} elseif ( strstr( $theme->{'Author URI'}, 'aazztech' ) ) {
			$theme_dir = substr( strtolower( str_replace( ' ','', $theme->Name ) ), 0, 45 );

			if ( false === ( $theme_version_data = get_transient( $theme_dir . '_version_data' ) ) ) {
				$theme_changelog = wp_safe_remote_get( 'http://dzv365zjfbd8v.cloudfront.net/changelogs/' . $theme_dir . '/changelog.txt' );
				$cl_lines  = explode( "\n", wp_remote_retrieve_body( $theme_changelog ) );
				if ( ! empty( $cl_lines ) ) {
					foreach ( $cl_lines as $line_num => $cl_line ) {
						if ( preg_match( '/^[0-9]/', $cl_line ) ) {
							$theme_date         = str_replace( '.' , '-' , trim( substr( $cl_line , 0 , strpos( $cl_line , '-' ) ) ) );
							$theme_version      = preg_replace( '~[^0-9,.]~' , '' ,stristr( $cl_line , "version" ) );
							$theme_update       = trim( str_replace( "*" , "" , $cl_lines[ $line_num + 1 ] ) );
							$theme_version_data = array( 'date' => $theme_date , 'version' => $theme_version , 'update' => $theme_update , 'changelog' => $theme_changelog );
							set_transient( $theme_dir . '_version_data', $theme_version_data , DAY_IN_SECONDS );
							break;
						}
					}
				}
			}

			if ( ! empty( $theme_version_data['version'] ) ) {
				$update_theme_version = $theme_version_data['version'];
			}
		}

		return $update_theme_version;
	}

    public function php_information() {
		$dump_php = array();
		$php_vars = array(
			'max_execution_time',
			'open_basedir',
			'memory_limit',
			'upload_max_filesize',
			'post_max_size',
			'display_errors',
			'log_errors',
			'track_errors',
			'session.auto_start',
			'session.cache_expire',
			'session.cache_limiter',
			'session.cookie_domain',
			'session.cookie_httponly',
			'session.cookie_lifetime',
			'session.cookie_path',
			'session.cookie_secure',
			'session.gc_divisor',
			'session.gc_maxlifetime',
			'session.gc_probability',
			'session.referer_check',
			'session.save_handler',
			'session.save_path',
			'session.serialize_handler',
			'session.use_cookies',
			'session.use_only_cookies',
		);
		
		$dump_php['Version'] = phpversion();
		foreach ( $php_vars as $setting ) {
			$dump_php[ $setting ] = ini_get( $setting );
		}
		$dump_php['Error Reporting'] = implode( '<br>', $this->_error_reporting() );
		$extensions                  = get_loaded_extensions();
		natcasesort( $extensions );
		$dump_php['Extensions'] = implode( '<br>', $extensions );
		return $dump_php;
	}

	public function _error_reporting() {
		$levels          = array();
		$error_reporting = error_reporting();
	
		$constants = array(
			'E_ERROR',
			'E_WARNING',
			'E_PARSE',
			'E_NOTICE',
			'E_CORE_ERROR',
			'E_CORE_WARNING',
			'E_COMPILE_ERROR',
			'E_COMPILE_WARNING',
			'E_USER_ERROR',
			'E_USER_WARNING',
			'E_USER_NOTICE',
			'E_STRICT',
			'E_RECOVERABLE_ERROR',
			'E_DEPRECATED',
			'E_USER_DEPRECATED',
			'E_ALL',
		);
	
		foreach ( $constants as $level ) {
			if ( defined( $level ) ) {
				$c = constant( $level );
				if ( $error_reporting & $c ) {
					$levels[ $c ] = $level;
				}
			}
		}
	
		return $levels;
    }
    
    /**
	 * Scan the template files.
	 *
	 * @param  string $template_path Path to the template directory.
	 * @return array
	 */
	public static function scan_template_files( $template_path ) {
		$files  = @scandir( $template_path ); // @codingStandardsIgnoreLine.
		$result = array();

		if ( ! empty( $files ) ) {

			foreach ( $files as $key => $value ) {

				if ( ! in_array( $value, array( '.', '..' ), true ) ) {

					if ( is_dir( $template_path . DIRECTORY_SEPARATOR . $value ) ) {
						$sub_files = self::scan_template_files( $template_path . DIRECTORY_SEPARATOR . $value );
						foreach ( $sub_files as $sub_file ) {
							$result[] = $value . DIRECTORY_SEPARATOR . $sub_file;
						}
					} else {
						$result[] = $value;
					}
				}
			}
		}
		return $result;
	}

    /**
	 * Retrieve metadata from a file. Based on WP Core's get_file_data function.
	 *
	 * @since  2.1.1
	 * @param  string $file Path to the file.
	 * @return string
	 */
	public static function get_file_version( $file ) {

		// Avoid notices if file does not exist.
		if ( ! file_exists( $file ) ) {
			return '';
		}

		// We don't need to write to the file, so just open for reading.
		$fp = fopen( $file, 'r' ); // @codingStandardsIgnoreLine.

		// Pull only the first 8kiB of the file in.
		$file_data = fread( $fp, 8192 ); // @codingStandardsIgnoreLine.

		// PHP will close file handle, but we are good citizens.
		fclose( $fp ); // @codingStandardsIgnoreLine.

		// Make sure we catch CR-only line endings.
		$file_data = str_replace( "\r", "\n", $file_data );
		$version   = '';

		if ( preg_match( '/^[ \t\/*#@]*' . preg_quote( '@version', '/' ) . '(.*)$/mi', $file_data, $match ) && $match[1] ) {
			$version = _cleanup_header_comment( $match[1] );
		}

		return $version;
	}
    
    /**
     * let_to_num function.
     *
     * This function transforms the php.ini notation for numbers (like '2M') to an integer.
     *
     * @since 2.0.0
     * @param $size
     * @return int
     */
    public function directorist_let_to_num( $size ) {
        $l   = substr( $size, -1 );
        $ret = substr( $size, 0, -1 );
        switch ( strtoupper( $l ) ) {
            case 'P':
                $ret *= 1024;
            case 'T':
                $ret *= 1024;
            case 'G':
                $ret *= 1024;
            case 'M':
                $ret *= 1024;
            case 'K':
                $ret *= 1024;
        }
        return $ret;
    }

    function directorist_help_tip( $tip ) {
	
        $tip = esc_attr( $tip );
        
        return '<span class="atbdp-help-tip dashicons dashicons-editor-help" title="' . $tip . '"></span>';
    }
}