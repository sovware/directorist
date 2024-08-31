<?php
use Directorist\Helper;
use Directorist\database\DB;

defined('ABSPATH') || die('No direct script access allowed!');

if ( ! function_exists( 'directorist_get_listings_directory_type' ) ) {
    function directorist_get_listings_directory_type( $listing_id = 0 ) {
		$directory_type = directorist_get_object_terms( $listing_id, ATBDP_DIRECTORY_TYPE, 'term_id' );
		return empty( $directory_type ) ? 0 : $directory_type[0];
    }
}

if ( ! function_exists( 'directorist_get_all_page_list' ) ) {
    function directorist_get_all_page_list( $listing_id = '' ) {
        $pages = get_pages();
        $pages_options = [
           ['value' => '', 'label' => 'Select...']
        ];

        if ( empty( $pages ) || ! is_array( $pages ) ) return $pages_options;

        foreach ($pages as $page) {
            $pages_options[] = array('value' => $page->ID, 'label' => $page->post_title);
        }

        return $pages_options;
    }
}

if ( ! function_exists( 'atbdp_is_truthy' ) ) {
    function atbdp_is_truthy( $data ) {

        if ( $data === true ||  $data === 'true'|| $data === 1 || $data === '1' ) {
            return true;
        }

        return false;
    }
}

if ( ! function_exists( 'e_var_dump' ) ) {
    function e_var_dump( $the_var = '' ) {
        echo '<pre>';
        print_r( $the_var );
        echo '</pre>';
    }
}

if ( ! function_exists( 'directorist_console_log' ) ) {
    function directorist_console_log( array $data = [] ) {
        $data = json_encode( $data ); ?>
        <script>
            var data = JSON.parse( '<?php echo esc_js( $data ); ?>' );
            console.log( data );
        </script>
        <?php
    }
}

if ( ! function_exists( 'atbdp_redirect_after_login' ) ) {
    // atbdp_redirect_after_login
    function atbdp_redirect_after_login( array $args = [] ) {
        $default = [ 'url' => '' ];
        $args = array_merge( $default, $args );

        if ( empty( $args['url'] ) ) { return; }

        set_transient( 'atbdp_redirect_after_login', $args['url'] );
    }
}

if ( ! function_exists( 'atbdp_add_flush_message' ) ) {
    // atbdp_add_flush_message
    function atbdp_add_flush_message( array $args = [] ) {
        $default = [ 'key' => '', 'type' => 'info', 'message' => '' ];
        $args = array_merge( $default, $args );

        if ( empty( $args['key'] ) ) { return; }
        if ( empty( $args['message'] ) ) { return; }

        $get_previous_messages = get_transient( 'atbdp_flush_messages' );
        $flush_messages = $get_previous_messages;

        if ( empty( $flush_messages ) ) {
            $flush_messages = [];
        }

        $key = $args[ 'key' ];
        $flush_messages[ $key ] = $args;

        set_transient( 'atbdp_flush_messages', $flush_messages );
    }
}

if ( ! function_exists( 'atbdp_get_flush_messages' ) ) {
    // atbdp_get_flush_messages
    function atbdp_get_flush_messages( array $args = [] ) {
        $flush_messages = get_transient( 'atbdp_flush_messages' );
        if ( empty( $flush_messages  ) ) { return; }

        delete_transient( 'atbdp_flush_messages' );

        ob_start();

        echo '<div class="atbdp-flush-message-container">';
        foreach ( $flush_messages as $message_key => $messages ) { ?>
            <div class="atbdp-flush-message-item type-<?php echo esc_attr( $messages['type'] ); ?>">
                <?php echo esc_html( $messages['message'] ); ?>
            </div>
        <?php }
        echo '</div>';

        $contents = apply_filters( 'atbdp_flush_message_content', ob_get_clean(), $flush_messages );
        echo directorist_kses( $contents );
    }
}

if ( ! function_exists( 'atbdp_auth_guard' ) ) {
    function atbdp_auth_guard( array $args = [] ) {
        $flush_message = [
            'key'     => 'logged_in_user_only',
            'type'    => 'info',
            'message' => __( 'You need to be logged in to view the content of this page', 'directorist' ),
        ];

		global $wp;

        $default = [
			'flush_message' => $flush_message,
		];

		$args          = array_merge( $default, $args );
		$current_page  = home_url( $wp->request );
        $migrated      = get_option( 'directorist_merge_dashboard_login_reg_page', false );
        
        if( $migrated ) {
            $login_page_id = (int) get_directorist_option( 'user_dashboard' );
        } else {
            $login_page_id = (int) get_directorist_option( 'user_login' );
        }
        
		$redirect_url  = $login_page_id ? get_page_link( $login_page_id ) : \ATBDP_Permalink::get_dashboard_page_link();
		$redirect_url  = add_query_arg( 'redirect', urlencode( $current_page ), $redirect_url );

        atbdp_add_flush_message( $args['flush_message'] );

        // atbdp_redirect_after_login( [ 'url' => $current_page ] );
        wp_safe_redirect( $redirect_url );
        die;
    }
}

function atbdp_add_flush_alert( array $args = [] ) {
    $default = [
        'id'          => '',
        'type'        => 'success',
        'page'        => '',
        'dismissible' => true,
        'message'     => '',
    ];

    $args = array_merge( $default, $args );

    if ( empty( $args['id'] ) ) { return; }

    $id = $args['id'];
    unset( $args['id'] );

    $fulsh_messages = get_transient( 'atbdp_flush_alerts' );

    if ( ! $fulsh_messages ) {
        $fulsh_messages = [];
    }

    $fulsh_messages[ $id ] = $args;

    set_transient( 'atbdp_flush_alerts', $fulsh_messages );
}

// atbdp_show_the_flush_alert
function atbdp_show_the_flush_alert( string $id = '' ) {
    if ( ! empty( $id ) ) { return; }

    $fulsh_alerts = get_transient( 'atbdp_flush_alerts' );
    if ( ! $fulsh_alerts && empty( $fulsh_alerts[ $id ] ) ) { return; }

    $fulsh_alert = $fulsh_alerts[ $id ];

    atbdp_render_the_flush_alert( $fulsh_alert );
    unset( $fulsh_alerts[ $id ] );

    set_transient( 'atbdp_flush_alerts', $fulsh_alerts );
}

// atbdp_show_flush_alerts
function atbdp_show_flush_alerts( array $args = [] ) {
    $default = [ 'page' => '' ];
    $args = array_merge( $default, $args );

    $fulsh_alerts = get_transient( 'atbdp_flush_alerts' );

    if ( $fulsh_alerts ) {
        foreach ( $fulsh_alerts as $id => $alert ) {
            $alert_page = ! empty( $alert['page'] ) ? $alert['page'] : '';

            if ( ! empty( $args['page'] ) && ( $alert_page !== $args['page'] && 'global' !== $alert_page ) ) {
                continue;
            }

            atbdp_render_the_flush_alert( $alert );
            unset( $fulsh_alerts[ $id ] );
        }
    }

    set_transient( 'atbdp_flush_alerts', $fulsh_alerts );
}

// atbdp_render_the_flush_alert
function atbdp_render_the_flush_alert( array $alert = [] ) {
    $classes = 'notice';
    $classes .= ( ! empty( $alert['type'] ) ) ? ' notice-' .  $alert['type'] : '';
    $classes .= ( empty( $alert['dismissible'] ) ) ? '' : ' is-dismissible';
    ?>
    <div class="<?php echo esc_attr( $classes ); ?>">
        <p><strong><?php echo directorist_kses( $alert['message'] ); ?></strong></p>
    </div>
    <?php
}


// atbdp_load_admin_template
function atbdp_load_admin_template( string $path = '', $data = [] ) {
    $file = trailingslashit( ATBDP_VIEWS_DIR ) . "admin-templates/$path.php";

    if ( file_exists( $file ) ) {
        include( $file );
    }
}

if ( !function_exists('get_help') ) {
    function get_help() {
        $path = ATBDP_CLASS_DIR . 'class-helper.php';
        if ( file_exists( $path ) ) {
            require_once( $path );
        }

        if ( class_exists( 'ATBDP_Helper' ) ) {
            $helper = new ATBDP_Helper;
            return $helper;
        }

        return null;
    }
}

if ( ! function_exists( 'atbdp_polylang_is_active' ) ) :
    function atbdp_required_polylang_url() {
        if ( class_exists('Polylang') ) {
            $pll_current_language = pll_current_language();
            $pll_default_language = pll_default_language();

            if ( $pll_current_language !== $pll_default_language ) {
                return true;
            }
        }

        return false;
    }
endif;


if ( ! function_exists( 'atbdp_get_listing_order' ) ) :
    // atbdp_get_listing_order
    function atbdp_get_listing_order( $listing_id ) {
        $order = new WP_Query([
            'post_type' => 'atbdp_orders',
            'meta_query' => array(
                array(
                    'key' => '_listing_id',
                    'value' => $listing_id,
                    'compare' => '=',
                )
            ),
            'per_page' => 1

        ]);

        return $order->post;
    }
endif;

if ( ! function_exists( 'atbdp_get_listing_status_after_submission' ) ) :
// atbdp_get_listing_status_after_submission
function atbdp_get_listing_status_after_submission( array $args = [] ) {
    $default = ['id' => '', 'edited' => true];
    $args = array_merge( $default, $args );

    $args['edited'] = ( true === $args['edited'] || '1' === $args['edited'] || 'yes' === $args['edited'] ) ? true : false;
    $listing_id = $args['id'];

    $new_l_status   = $args['new_l_status'];
    $edit_l_status  = ( 'publish' !== $new_l_status ) ? $new_l_status : $args['edit_l_status'];
    $edited         = $args['edited'];
    $listing_status = ( true === $edited || 'yes' === $edited || '1' === $edited ) ? $edit_l_status : $new_l_status;

    $monitization          = directorist_is_monetization_enabled();
    $featured_enabled      = directorist_is_featured_listing_enabled();
    $pricing_plans_enabled = is_fee_manager_active();

    $post_status =  $listing_status;

    // If Pricing Plans are Enabled
    if ( $monitization && $pricing_plans_enabled ) {
        $plan_id   = get_post_meta($listing_id, '_fm_plans', true);
        $plan_meta = get_post_meta($plan_id);
        $plan_type = ( ! empty( $plan_meta['plan_type'] ) && ! empty( $plan_meta['plan_type'][0] ) ) ? $plan_meta['plan_type'][0] : '';
        // $plan_type = $plan_meta['plan_type'][0];

        $_listing_id    = ( 'pay_per_listng' === $plan_type ) ? $listing_id : false;
        $plan_purchased = subscribed_package_or_PPL_plans(get_current_user_id(), 'completed', $plan_id, $_listing_id);

        $post_status = ( ! $plan_purchased ) ? 'pending' : $listing_status;
    }

    // If Featured Listing is Enabled
    if ( $monitization && ! $pricing_plans_enabled && $featured_enabled ) {
        $has_order      = atbdp_get_listing_order( $listing_id );
        $payment_status = ( $has_order ) ? get_post_meta( $has_order->ID, '_payment_status', true) : null;

        $post_status = ( $has_order && 'completed' !== $payment_status ) ? 'pending' : $listing_status;
    }

    return $post_status;
}
endif;


if (!function_exists('load_dependencies')):
    /**
     * It loads files from a given directory using require_once.
     * @param string|array $files list of the names of file or a single file name to be loaded. Default: all
     * @param string $directory the location of the files
     * @param string $ext the ext of the files to be loaded
     * @return resource|bool it requires all the files in a given directory
     */
    function load_dependencies($files = 'all', $directory = ATBDP_CLASS_DIR, $ext = '.php')
    {
        if (!file_exists($directory)) return; // vail if the directory does not exist

        switch ($files) {
            case is_array($files) && 'all' !== strtolower($files[0]):
                // include one or more file looping through the $files array
                load_some_file($files, $directory);
                break;
            case !is_array($files) && 'all' !== $files:
                //load a single file here
                (file_exists($directory . $files . $ext)) ? require_once $directory . $files . $ext : null;
                break;
            case 'all' == $files || 'all' == strtolower($files[0]):
                // load all php file here
                load_all_files($directory);
                break;
        }

        return false;

    }
endif;


if (!function_exists('load_all_files')):
    /**
     * It loads all files that has the extension named $ext from the $dir
     * @param string $dir Name of the directory
     * @param string $ext Name of the extension of the files to be loaded
     */
    function load_all_files($dir = '', $ext = '.php')
    {
        if (!file_exists($dir)) return;
        foreach (scandir($dir) as $file) {
            // require once all the files with the given ext. eg. .php
            if (preg_match("/{$ext}$/i", $file)) {
                require_once($dir . $file);
            }
        }
    }
endif;


if (!function_exists('load_some_file')):

    /**
     * It loads one or more files but not all files that has the $ext from the $dir
     * @param string|array $files the array of files that should be loaded
     * @param string $dir Name of the directory
     * @param string $ext Name of the extension of the files to be loaded
     */
    function load_some_file($files = array(), $dir = '', $ext = '.php')
    {
        if (!file_exists($dir)) return; // vail if directory does not exist

        if (is_array($files)) {  // if the given files is an array then
            $files_to_loads = array_map(function ($i) use ($ext) {
                return $i . $ext;
            }, $files);// add '.php' to the end of all files
            $found_files = scandir($dir); // get the list of all the files in the given $dir
            foreach ($files_to_loads as $file_to_load) {
                if(is_array($found_files)) {
                    in_array($file_to_load, $found_files) ? require_once $dir . $file_to_load : null;
                }
            }
        }

    }
endif;


if (!function_exists('attc_letter_to_number')):

    /**
     * Calculate the column index (number) of a column header string (example: A is 1, AA is 27, ...).
     *
     * For the opposite, @param string $column Column string.
     * @return int $number Column number, 1-based.
     * @see number_to_letter().
     *
     * @since 1.0.0
     *
     */
    function attc_letter_to_number($column)
    {
        $column = strtoupper($column);
        $count = strlen($column);
        $number = 0;
        for ($i = 0; $i < $count; $i++) {
            $number += (ord($column[$count - 1 - $i]) - 64) * pow(26, $i);
        }
        return $number;
    }

endif;

if (!function_exists('attc_number_to_letter')):

    /**
     * "Calculate" the column header string of a column index (example: 2 is B, AB is 28, ...).
     *
     * For the opposite, @param int $number Column number, 1-based.
     * @return string $column Column string.
     * @see letter_to_number().
     *
     * @since 1.0.0
     *
     */
    function attc_number_to_letter($number)
    {
        $column = '';
        while ($number > 0) {
            $column = chr(65 + (($number - 1) % 26)) . $column;
            $number = floor(($number - 1) / 26);
        }
        return $column;
    }
endif;

if (!function_exists('atbdp_v_d')):

    /**
     * It dumps data to the screen in a div that has margin left 200px.
     * It is good for dumping data in WordPress dashboard
     */
    function atbdp_v_d($a = null)
    {
        echo "<pre>";
        var_dump($a);
        echo "</pre>";
    }
endif;

if (!function_exists('list_file_name')):
    /**
     * It returns a list of names of all files which are not hidden files
     * @param string $path
     * @return array
     */
    function list_file_name($path = __DIR__)
    {
        $file_names = array();
        foreach (new DirectoryIterator($path) as $fileInfo) {
            if ($fileInfo->isDot()) continue;
            $file_names[] = $fileInfo->getFilename();
        }
        return $file_names;
    }

endif;

if (!function_exists('list_file_path')):
    /**
     * It returns a list of path of all files which are not hidden files
     * @param string $path
     * @return array
     */
    function list_file_path($path = __DIR__)
    {
        $file_paths = array();
        foreach (new DirectoryIterator($path) as $fileInfo) {
            if ($fileInfo->isDot()) continue;
            $file_paths[] = $fileInfo->getRealPath();
        }
        return $file_paths;
    }

endif;

if (!function_exists('beautiful_datetime')):
    /**
     * It display a nice date and time
     * @param $datetime
     * @param string $type
     * @param string $separator
     * @return string
     */
    function beautiful_datetime($datetime, $type = 'mysql', $separator = ' ')
    {
        if ('mysql' === $type) {
            return mysql2date(get_option('date_format'), $datetime) . $separator . mysql2date(get_option('time_format'), $datetime);
        } else {
            return date_i18n(get_option('date_format'), $datetime) . $separator . date_i18n(get_option('time_format'), $datetime);
        }
    }

endif;

if (!function_exists('aazztech_enc_serialize')) {
    /**
     * It will serialize and then encode the string and return the encoded data
     * @param $data
     * @return string
     */
    function aazztech_enc_serialize($data)
    {
        return (!empty($data)) ? base64_encode(serialize($data)) : null;
    }
}

if (!function_exists('aazztech_enc_unserialize')) {
    /**
     * It will decode the data and then unserialize the data and return it
     * @param string $data Encoded strings that should be decoded and then unserialize
     * @return mixed
     */
    function aazztech_enc_unserialize($data)
    {
        return (!empty($data)) ? unserialize(base64_decode($data)) : null;
    }
}

/**
 * Unused function
 *
 * @return object WP_Query
 */
if (!function_exists('atbd_get_related_posts')) {
    // get related post based on tags or categories
    function atbd_get_related_posts() {
		_deprecated_function( __FUNCTION__, '7.4.3' );
		return new WP_Query();
    }
}

if (!function_exists('atbdp_get_option')) {

    /**
     * It retrieves an option from the database if it exists and returns false if it is not exist.
     * It is a custom function to get the data of custom setting page
     * @param string $name The name of the option we would like to get. Eg. map_api_key
     * @param string $group The name of the group where the option is saved. eg. general_settings
     * @param mixed $default Default value for the option key if the option does not have value then default will be returned
     * @return mixed    It returns the value of the $name option if it exists in the option $group in the database, false otherwise.
     */
    function atbdp_get_option($name, $group, $default = false)
    {
        // at first get the group of options from the database.
        // then check if the data exists in the array and if it exists then return it
        // if not, then return false
        if (empty($name) || empty($group)) {
            if (!empty($default)) return $default;
            return false;
        } // vail if either $name or option $group is empty
        $options_array = (array)get_option($group);
        if (array_key_exists($name, $options_array)) {
            return $options_array[$name];
        } else {
            if (!empty($default)) return $default;
            return false;
        }
    }
}

function get_directorist_type_option( $type, $name, $default='' ) {
    $meta = get_term_meta( $type, $name, true );
    $result = $meta != '' ? $meta : $default;
    return $result;
}

if (!function_exists('get_directorist_option')) {

    /**
     * It retrieves an option from the database if it exists and returns false if it is not exist.
     * It is a custom function to get the data of custom setting page
     * @param string $name The name of the option we would like to get. Eg. map_api_key
     * @param mixed $default Default value for the option key if the option does not have value then default will be returned
     * @param bool $force_default Whether to use default value when database return anything other than NULL such as '', false etc
     * @return mixed    It returns the value of the $name option if it exists in the option $group in the database, false otherwise.
     */
    function get_directorist_option($name, $default = false, $force_default = false)
    {
        // at first get the group of options from the database.
        // then check if the data exists in the array and if it exists then return it
        // if not, then return false
        if (empty($name)) {
            return $default;
        }
        // get the option from the database and return it if it is not a null value. Otherwise, return the default value
        $options = (array)get_option('atbdp_option');
        $v = (array_key_exists($name, $options))
            ? $v = $options[sanitize_key($name)]
            : null;

        $newvalue = apply_filters( 'directorist_option', $v, $name );

        if ( $newvalue != $v ) {
           return $newvalue;
        }

        // use default only when the value of the $v is NULL
        if (is_null($v)) {
            return $default;
        }
        if ($force_default) {
            // use the default value even if the value of $v is falsy value returned from the database
            if (empty($v)) {
                return $default;
            }
        }
        return (isset($v)) ? $v : $default; // return the data if it is anything but NULL.
    }
}

if ( ! function_exists( 'update_directorist_option' ) ) {
    function update_directorist_option( $key = '', $value = '' ) {
        $options = ( array ) get_option( 'atbdp_option', [] );
        $options[ $key ] = $value;

        update_option( 'atbdp_option', $options );
    }
}


if (!function_exists('atbdp_yes_to_bool')) {
    function atbdp_yes_to_bool($v = false)
    {
        if (empty($v)) return false;
        return ('yes' == trim($v)) ? true : false;
    }
}


if (!function_exists('atbdp_pagination')) {
    /**
     * Prints pagination for custom post
     * @param object|WP_Query $custom_post_query
     * @param int $paged
     *
     * @return string
     */
    function atbdp_pagination($custom_post_query, $paged = 1)
    {
        $navigation = '';
        $largeNumber = 999999999; // we need a large number here

        $total = ( isset( $custom_post_query->total_pages ) ) ? $custom_post_query->total_pages : $custom_post_query->max_num_pages;
        $paged = ( isset( $custom_post_query->current_page ) ) ? $custom_post_query->current_page : $paged;

        $links = paginate_links(array(
            'base'      => str_replace($largeNumber, '%#%', esc_url(get_pagenum_link($largeNumber))),
            'format'    => '?paged=%#%',
            'current'   => max(1, $paged),
            'total'     => $total,
            'prev_text' => apply_filters('atbdp_pagination_prev_text', directorist_icon( 'fas fa-chevron-left', false )),
            'next_text' => apply_filters('atbdp_pagination_next_text', directorist_icon( 'fas fa-chevron-right', false )),
        ));

        if ($links) {
            $navigation = _navigation_markup($links, 'pagination', ' ');
        }

        return apply_filters('atbdp_pagination', $navigation, $links, $custom_post_query, $paged);
    }
}

if(!function_exists('get_recent_reviews')) {
    function get_recent_reviews( $number = 5 ){
        global $wpdb;

        $sql = $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}atbdp_review
            ORDER BY id DESC
            LIMIT %d",
            $number
        );

        $items = $wpdb->get_results( $sql );

        return $items;
    }
 }

if(!function_exists('get_review_by_ids')) {
    function get_review_by_ids( $review_ids = [], $number = 5 ){
        global $wpdb;
        $ids =  implode(",",$review_ids);
        $sql = $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}atbdp_review
            WHERE id IN ({$ids})
            LIMIT %d",
            $number
        );

        $items = $wpdb->get_results( $sql );

        return $items;
    }
}

if (!function_exists('get_cat_icon')) {
    function get_cat_icon($term_id)
    {
        $icon = get_term_meta($term_id, 'category_icon', true);
        return !empty($icon) ? $icon : '';
    }
}

if ( ! function_exists( 'str_starts_with' ) ) {
	/**
	 * Polyfill for `str_starts_with()` function added in WP 5.9.0.
	 *
	 * Performs a case-sensitive check indicating if
	 * the haystack begins with needle.
	 *
	 * @param string $haystack The string to search in.
	 * @param string $needle   The substring to search for in the `$haystack`.
	 * @return bool True if `$haystack` starts with `$needle`, otherwise false.
	 */
	function str_starts_with( $haystack, $needle ) {
		if ( '' === $needle ) {
			return true;
		}
		return 0 === strpos( $haystack, $needle );
	}
}

/**
 * Renders icon html.
 *
 * Supports FontAwesome 5.15.4, LineAwesome 1.3.0.
 *
 * Also supports Unicons 3.0.3 for backward compatibility, but should not be used.
 *
 * @param string  $icon   Icon class name eg. 'las la-home'.
 * @param bool    $echo   Either echo or return the html. Default true.
 * @param string  $class  Extra wrapper class. Default empty string.
 *
 * @return string Echo or return icon html string.
 */
function directorist_icon( $icon, $echo = true, $class = '' ) {
    if ( !$icon ) {
        return;
    }

	$icon_src = \Directorist\Helper::get_icon_src( $icon );

    if ( !$icon_src ) {
        return;
    }

	$class = $class ? 'directorist-icon-mask ' . $class : 'directorist-icon-mask';

	$html = sprintf(
		'<i class="%1$s" aria-hidden="true" style="--directorist-icon: url(%2$s)"></i>',
		esc_attr( $class ),
		esc_url( $icon_src )
	);

    if ( $echo ) {
        echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already escaped
    }
    else {
        return $html;
    }
}




if (!function_exists('atbdp_sanitize_array')) {
    /**
     * It sanitize a multi-dimensional array
	 *
	 * @deprecated 7.3.1
     * @param array &$array The array of the data to sanitize
     * @return mixed
     */
    function atbdp_sanitize_array( $array ) {
		return directorist_clean( $array );
    }
}

if (!function_exists('is_directoria_active')) {
    /**
     * It checks if the Directorist theme is installed currently.
     * @return bool It returns true if the directorist theme is active currently. False otherwise.
     */
    function is_directoria_active()
    {
        return wp_get_theme()->get_stylesheet() === 'directoria';
    }
}

if (!function_exists('is_multiple_images_active')) {
    /**
     * It checks if the Directorist Multiple images Extension is active and enabled
     * @return bool It returns true if the Directorist Multiple images Extension is active and enabled
     */
    function is_multiple_images_active()
    {

        return true; // plugin is active and enabled
    }
}


if (!function_exists('is_business_hour_active')) {
    /**
     * It checks if the Directorist Business Hour Extension is active and enabled
     * @return bool It returns true if the Directorist Business Hour Extension is active and enabled
     */
    function is_business_hour_active()
    {
        $enable = get_directorist_option('enable_business_hour');
        if ($enable && class_exists('BD_Business_Hour')) {
            return true;
        }
    }
}

if (!function_exists('is_empty_v')) {
    /**
     * It checks if the value of the given data ( array or string etc ) is empty
     * @param array $value The value to check if it is empty
     * @return bool It returns true if the value of the given data is empty, and false otherwise.
     */
    function is_empty_v($value)
    {
        if (!is_array($value)) return empty($value);
        foreach ($value as $key => $val) {
            if (!empty($val))
                return false;
        }
        return true;
    }
}

if (!function_exists('atbdp_get_paged_num')) {
    /**
     * Get current page number for the pagination.
     *
     * @return    int    $paged    The current page number for the pagination.
     * @since    1.0.0
     *
     */
    function atbdp_get_paged_num()
    {

        global $paged;

        if (get_query_var('paged')) {
            $paged = get_query_var('paged');
        } else if (get_query_var('page')) {
            $paged = get_query_var('page');
        } else {
            $paged = isset( $_REQUEST['paged'] ) ? directorist_clean( wp_unslash(  $_REQUEST['paged'] ) ) : 1;
        }

        return absint($paged);

    }


}

if (!function_exists('valid_js_nonce')) {
    /**
     * It checks if the nonce is set and valid
     * @return bool it returns true if the nonce is valid and false otherwise
     */
    function valid_js_nonce()
    {
        if (!empty($_POST['atbdp_nonce_js']) && (wp_verify_nonce($_POST['atbdp_nonce_js'], 'atbdp_nonce_action_js'))) // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
            return true;
        return false;
    }
}

if (!function_exists('atbdp_get_featured_settings_array')) {
    /**
     * It fetch all the settings related to featured listing.
     * @return array it returns an array of settings related to featured listings.
     */
    function atbdp_get_featured_settings_array()
    {
        return array(
            'active' => directorist_is_featured_listing_enabled(),
            'label' => get_directorist_option('featured_listing_title'),
            'desc' => get_directorist_option('featured_listing_desc'),
            'price' => get_directorist_option('featured_listing_price'),
        );
    }
}

if (!function_exists('atbdp_only_logged_in_user')) {

    /**
     * It informs a user to logged in and returns false if the user is not logged in.
     * if a user is not logged in.
     * @param string $message
     * @return bool It returns true if a user is logged in and false otherwise. Besides, it display a message to non-logged in users
     */
    function atbdp_is_user_logged_in($message = '')
    {
        if (!is_user_logged_in()) {
            // user not logged in;
            if( get_option( 'directorist_merge_dashboard_login_reg_page' ) ) {
                $error_message = ( empty( $message ) )
                    ? sprintf( __( 'You need to be logged in to view the content of this page. You can login/sign up %s', 'directorist' ), apply_filters( "atbdp_login_page_link", "<a href='" . ATBDP_Permalink::get_dashboard_page_link() . "'> " . __( 'Here', 'directorist' ) . "</a>" ) )
                    : $message;
            } else {
                $error_message = ( empty( $message ) )
                    ? sprintf( __( 'You need to be logged in to view the content of this page. You can login %s. Don\'t have an account? %s', 'directorist' ), apply_filters( "atbdp_login_page_link", "<a href='" . ATBDP_Permalink::get_login_page_link() . "'> " . __('Here', 'directorist') . "</a>" ), apply_filters("atbdp_signup_page_link", "<a href='" . ATBDP_Permalink::get_registration_page_link() . "'> " . __( 'Sign up', 'directorist' ) . "</a>" ) )
                    : $message;
            }
            $container_fluid = is_directoria_active() ? 'container' : 'container-fluid';
            ?>
            <section class="directory_wrapper single_area">
                <div class="<?php echo esc_attr( apply_filters('atbdp_login_message_container_fluid', $container_fluid) ); ?>">
                    <div class="row">
                        <div class="col-md-12">
                            <?php ATBDP()->helper->show_login_message($error_message); ?>
                        </div>
                    </div>
                </div> <!--ends container-fluid-->
            </section>
            <?php
            return false;
        }
        return true;
    }
}

if (!function_exists('atbdp_get_months')) {
    /**
     * Get an array of translatable month names
     * @return array
     * @since    3.1.0
     */
    function atbdp_get_months()
    {
        return array(
            __("Jan", 'directorist'),
            __("Feb", 'directorist'),
            __("Mar", 'directorist'),
            __("Apr", 'directorist'),
            __("May", 'directorist'),
            __("Jun", 'directorist'),
            __("Jul", 'directorist'),
            __("Aug", 'directorist'),
            __("Sep", 'directorist'),
            __("Oct", 'directorist'),
            __("Nov", 'directorist'),
            __("Dec", 'directorist')
        );
    }
}

if (!function_exists('calc_listing_expiry_date')) {
    /**
     * Calculate listing expiry date from the given date
     *
     * @param string $start_date Date from which the expiry date should be calculated.
     * @return   string   $date          It returns expiry date in the mysql date format
     * @since    3.1.0
     *
     */
    function calc_listing_expiry_date($start_date = NULL, $expire = NULL, $directory_type = '' )
    {
        $type = $directory_type ? $directory_type : default_directory_type();
        $exp_days = get_term_meta( $type, 'default_expiration', true );
        $exp_days = !empty( $exp_days ) ? $exp_days : 0;
        $expired_date = !empty($expire) ? $expire : $exp_days;
        // Current time
        $start_date = !empty($start_date) ? $start_date : current_time('mysql');
        // Calculate new date
        $date = new DateTime($start_date);
        $date->add(new DateInterval("P{$expired_date}D")); // set the interval in days
        return $date->format('Y-m-d H:i:s');

    }
}

if (!function_exists('get_date_in_mysql_format')) {
    /**
     * It converts a date array to MySQL date format (Y-m-d H:i:s).
     *
     * @param array $date Array of date values.
     * eg. array(
     * 'year'  => 0,
     * 'month' => 0,
     * 'day'   => 0,
     * 'hour'  => 0,
     * 'min'   => 0,
     * 'sec'   => 0
     * );
     * @return   string   $date    Formatted MySQL date string.
     * @since    3.1.0
     *
     */
    function get_date_in_mysql_format($date)
    {

        $defaults = array(
            'year' => 0,
            'month' => 0,
            'day' => 0,
            'hour' => 0,
            'min' => 0,
            'sec' => 0
        );
        $date = wp_parse_args($date, $defaults);

        $year = (int)$date['year'];
        $year = str_pad($year, 4, '0', STR_PAD_RIGHT);

        $month = (int)$date['month'];
        $month = max(1, min(12, $month));

        $day = (int)$date['day'];
        $day = max(1, min(31, $day));

        $hour = (int)$date['hour'];
        $hour = max(1, min(24, $hour));

        $min = (int)$date['min'];
        $min = max(0, min(59, $min));

        $sec = (int)$date['sec'];
        $sec = max(0, min(59, $sec));

        return sprintf('%04d-%02d-%02d %02d:%02d:%02d', $year, $month, $day, $hour, $min, $sec);

    }
}

if (!function_exists('atbdp_parse_mysql_date')) {
    /**
     * Parse MySQL date format.
     *
     * @param string $date MySQL date string.
     * @return   array     $date    Array of date values.
     * @since    3.1.0
     *
     */
    function atbdp_parse_mysql_date($date)
    {

        $date = preg_split('([^0-9])', $date);

        return array(
            'year' => $date[0],
            'month' => $date[1],
            'day' => $date[2],
            'hour' => $date[3],
            'min' => $date[4],
            'sec' => $date[5]
        );

    }
}

if (!function_exists('currency_has_decimal')) {
    /**
     * Check if currency has decimals.
     * @param string $currency
     * @return bool
     */
    function currency_has_decimals($currency)
    {
        if (in_array($currency, array('RIAL', 'SAR', 'HUF', 'JPY', 'TWD'))) {
            return false;
        }

        return true;
    }
}

/**
 * Print formatted Price inside a p tag
 *
 * @param int|string $price The price amount to display
 * @param bool $disable_price whether displaying price is enabled or disabled
 * @param string $currency The name of the currency
 * @param string $symbol currency symbol
 * @param string $c_position currency position
 * @param bool $echo Whether to Print value or to Return value. Default is printing value.
 * @return mixed
 */
function atbdp_display_price($price = '', $disable_price = false, $currency = '', $symbol = '', $c_position = '', $echo = true)
{
    if (empty($price) || $disable_price) return null; // vail if the price is empty or price display is disabled.

    $allow_decimal = get_directorist_option('allow_decimal', 1);
    $before = '';
    $after = '';
    if (empty($c_position)) {
        $c_position = directorist_get_currency_position();
    }
    if (empty($currency)) {
        $currency = directorist_get_currency();
    }
    if (empty($symbol)) {
        $symbol = atbdp_currency_symbol($currency);
    }

    ('after' == $c_position) ? $after = $symbol : $before = $symbol;
    $price = $before . atbdp_format_amount($price, $allow_decimal) . $after;
    $p = sprintf("<span class='directorist-listing-price'>%s</span>", $price);
    if ($echo) {
        echo wp_kses_post( $p );
    } else {
        return $p;
    }

}

/**
 * Print formatted Price inside a p tag
 *
 *
 * @return mixed
 */
function atbdp_display_price_range($price_range)
{
    $currency = directorist_get_currency();
    $c_symbol = atbdp_currency_symbol($currency);
    if (empty($price_range)) return null;
    $output = '';
    if ('skimming' == $price_range) {
        $output =
            '<span class="atbd_meta atbd_listing_average_pricing atbd_tooltip" aria-label="Skimming"><span class="atbd_active">' . $c_symbol . '</span><span class="atbd_active">' . $c_symbol . '</span><span class="atbd_active">' . $c_symbol . '</span><span class="atbd_active">' . $c_symbol . '</span>
        </span>';
    } elseif ('moderate' == $price_range) {
        $output =
            '<span class="atbd_meta atbd_listing_average_pricing atbd_tooltip" aria-label="Moderate"><span class="atbd_active">' . $c_symbol . '</span><span class="atbd_active">' . $c_symbol . '</span><span class="atbd_active">' . $c_symbol . '</span><span>' . $c_symbol . '</span>
            </span>';
    } elseif ('economy' == $price_range) {
        $output =
            '<span class="atbd_meta atbd_listing_average_pricing atbd_tooltip" aria-label="Economy"><span class="atbd_active">' . $c_symbol . '</span><span class="atbd_active">' . $c_symbol . '</span><span>' . $c_symbol . '</span><span>' . $c_symbol . '</span>
        </span>';
    } elseif ('bellow_economy' == $price_range) {

        $output =
            '<span class="atbd_meta atbd_listing_average_pricing atbd_tooltip" aria-label="Cheap"><span class="atbd_active">' . $c_symbol . '</span><span>' . $c_symbol . '</span><span>' . $c_symbol . '</span><span>' . $c_symbol . '</span>
        </span>';

    }
    return $output;

}


/**
 * Get total listings count.
 *
 * @param int $term_id Custom Taxonomy term ID.
 * @return   int                    Listings count.
 * @since    4.0.0
 *
 */
function atbdp_listings_count_by_category( $term_id, $lisitng_type = '' )
{
    $args = array(
        'fields'         => 'ids',
        'posts_per_page' => -1,
        'post_type'      => ATBDP_POST_TYPE,
        'post_status'    => 'publish',
    );

    if( ! empty( $lisitng_type ) ) {
        $args['tax_query'] = array(
            'relation' => 'AND',
            array(
                'taxonomy' => ATBDP_CATEGORY,
                'field' => 'term_id',
                'terms' => $term_id,
                'include_children' => true
            ),
            array(
                'taxonomy' => ATBDP_TYPE,
                'field' => 'term_id',
                'terms' => (int) $lisitng_type,
            )
        );
    } else {
        $args['tax_query'] = array(
            array(
                'taxonomy' => ATBDP_CATEGORY,
                'field' => 'term_id',
                'terms' => $term_id,
                'include_children' => true
            )
        );
    }

	$query = new WP_Query( $args );
    return count( $query->posts );
}

/**
 * List ACADP categories.
 *
 * @param array $settings Settings args.
 * @return   string                 HTML code that contain categories list.
 * @since    1.0.0
 *
 */
function atbdp_list_categories($settings)
{

    if ($settings['depth'] <= 0) {
        return;
    }

    $args = array(
        'orderby' => $settings['orderby'],
        'order' => $settings['order'],
        'hide_empty' => !empty($settings['hide_empty']) ? 1 : 0,
        'parent' => $settings['term_id'],
        'hierarchical' => false
    );

    $terms = get_terms(ATBDP_CATEGORY, $args);
    $html = '';

    if (count($terms) > 0) {

        --$settings['depth'];

        $html .= '<ul class="list-unstyled atbdp_child_category">';

        foreach ($terms as $term) {
            $settings['term_id'] = $term->term_id;
            $child_category = get_term_children($term->term_id, ATBDP_CATEGORY);
            $plus_icon = !empty($child_category) ? '<span class="expander">+</span>' : '';
            $count = 0;
            if (!empty($settings['hide_empty']) || !empty($settings['show_count'])) {
                $count = atbdp_listings_count_by_category($term->term_id);

                if (!empty($settings['hide_empty']) && 0 == $count) continue;
            }

            $html .= '<li>';
            $html .= '<a href=" ' . ATBDP_Permalink::atbdp_get_category_page($term) . ' ">';
            $html .= $term->name;
            if (!empty($settings['show_count'])) {
                $html .= ' (' . $count . ')';
            }
            $html .= "</a>$plus_icon";
            $html .= atbdp_list_categories($settings);
            $html .= '</li>';
        }

        $html .= '</ul>';

    }

    return $html;
}

/**
 * Get total listings count.
 *
 * @param int $term_id Custom Taxonomy term ID.
 * @return   int                    Listings count.
 * @since    4.0.0
 *
 */
function atbdp_listings_count_by_location( $term_id, $lisitng_type = '' ) {
    $args = array(
        'fields' => 'ids',
        'posts_per_page' => -1,
        'post_type' => ATBDP_POST_TYPE,
        'post_status' => 'publish',
    );

    if( ! empty( $lisitng_type ) ) {
        $args['tax_query'] = array(
            'relation' => 'AND',
            array(
                'taxonomy' => ATBDP_LOCATION,
                'field' => 'term_id',
                'terms' => $term_id,
                'include_children' => true
            ),
            array(
                'taxonomy' => ATBDP_TYPE,
                'field' => 'term_id',
                'terms' => (int) $lisitng_type,
            )
        );
    } else {
        $args['tax_query'] = array(
            array(
                'taxonomy' => ATBDP_LOCATION,
                'field' => 'term_id',
                'terms' => $term_id,
                'include_children' => true
            )
        );
    }

	$query = new WP_Query( $args );
	$count = count( $query->posts );
    return $count;
}

/**
 * List ACADP categories.
 *
 * @param array $settings Settings args.
 * @return   string                 HTML code that contain categories list.
 * @since    1.0.0
 *
 */
function atbdp_list_locations($settings)
{

    if ($settings['depth'] <= 0) {
        return;
    }

    $args = array(
        'orderby' => $settings['orderby'],
        'order' => $settings['order'],
        'hide_empty' => !empty($settings['hide_empty']) ? 1 : 0,
        'parent' => $settings['term_id'],
        'hierarchical' => false
    );

    $terms = get_terms(ATBDP_LOCATION, $args);

    $html = '';

    if (count($terms) > 0) {

        --$settings['depth'];

        $html .= '<ul class="list-unstyled atbdp_child_category">';

        foreach ($terms as $term) {
            $settings['term_id'] = $term->term_id;
            $child_category = get_term_children($term->term_id, ATBDP_LOCATION);
            $plus_icon = !empty($child_category) ? '<span class="expander">+</span>' : '';
            $count = 0;
            if (!empty($settings['hide_empty']) || !empty($settings['show_count'])) {
                $count = atbdp_listings_count_by_location($term->term_id);

                if (!empty($settings['hide_empty']) && 0 == $count) continue;
            }

            $html .= '<li>';
            $html .= '<a href=" ' . ATBDP_Permalink::atbdp_get_location_page($term) . ' ">';
            $html .= $term->name;
            if (!empty($settings['show_count'])) {
                $html .= ' (' . $count . ')';
            }
            $html .= "</a>$plus_icon";
            $html .= atbdp_list_locations($settings);
            $html .= '</li>';
        }

        $html .= '</ul>';

    }

    return $html;
}

/**
 * Get total listings count.
 *
 * @param int $term_id Custom Taxonomy term ID.
 * @return   int                    Listings count.
 * @since    4.0.0
 *
 */
function atbdp_listings_count_by_tag($term_id)
{

    $args = array(
        'fields' => 'ids',
        'posts_per_page' => -1,
        'post_type' => ATBDP_POST_TYPE,
        'post_status' => 'publish',
        'tax_query' => array(
            array(
                'taxonomy' => ATBDP_TAGS,
                'field' => 'term_id',
                'terms' => $term_id,
                'include_children' => true
            )
        ),
        'meta_query' => apply_filters('atbdp_listings_with_tag_meta_query', array(
            'relation' => 'OR',
            array(
                'key' => '_expiry_date',
                'value' => current_time('mysql'),
                'compare' => '>', // eg. expire date 6 <= current date 7 will return the post
                'type' => 'DATETIME'
            ),
            array(
                'key' => '_never_expire',
            ),
        ))
    );

	$query = new WP_Query( $args );
	$count = count( $query->posts );
    return $count;
}

/**
 * List ACADP categories.
 *
 * @param array $settings Settings args.
 * @return   string                 HTML code that contain categories list.
 * @since    1.0.0
 *
 */
function atbdp_list_tags($settings)
{

    if ($settings['depth'] <= 0) {
        return;
    }

    $args = array(
        'orderby' => $settings['orderby'],
        'order' => $settings['order'],
        'hide_empty' => !empty($settings['hide_empty']) ? 1 : 0,
        'parent' => $settings['term_id'],
        'hierarchical' => false
    );

    $terms = get_terms(ATBDP_TAGS, $args);

    $html = '';

    if (count($terms) > 0) {

        --$settings['depth'];

        $html .= '<ul class="list-unstyled">';

        foreach ($terms as $term) {
            $settings['term_id'] = $term->term_id;

            $count = 0;
            if (!empty($settings['hide_empty']) || !empty($settings['show_count'])) {
                $count = atbdp_listings_count_by_tag($term->term_id);

                if (!empty($settings['hide_empty']) && 0 == $count) continue;
            }

            $html .= '<li>';
            $html .= '<a href=" ' . ATBDP_Permalink::get_tag_archive($settings['term']) . ' ">';
            $html .= $term->name;
            if (!empty($settings['show_count'])) {
                $html .= ' (' . $count . ')';
            }
            $html .= '</a>';
            $html .= atbdp_list_tags($settings);
            $html .= '</li>';
        }

        $html .= '</ul>';

    }

    return $html;
}

/**
 * Get the current listings order.
 *
 * @param string $default_order Default Order.
 * @return   string    $order            Listings Order.
 * @since    4.0
 *
 */
function atbdp_get_listings_current_order($default_order = '')
{

    $order = $default_order;

    if (isset($_REQUEST['sort'])) {
        $order = directorist_clean( wp_unslash( $_REQUEST['sort'] ) );
    } else if (isset($_REQUEST['order'])) {
        $order = directorist_clean( wp_unslash( $_REQUEST['order'] ) );
    }

    return apply_filters('atbdp_get_listings_current_order', $order);

}

/**
 * Get orderby list.
 *
 * @return   array    $options    A list of the orderby options.
 * @since    1.0.0
 *
 */
function atbdp_get_listings_orderby_options( $orderby = array() ) {
    $orderby_options = array(
        'title-asc'  => __( 'A to Z (title)', 'directorist' ),
        'title-desc' => __( 'Z to A (title)', 'directorist' ),
        'date-desc'  => __( 'Latest listings', 'directorist' ),
        'date-asc'   => __( 'Oldest listings', 'directorist' ),
        'views-desc' => __( 'Popular listings', 'directorist' ),
        'price-asc'  => __( 'Price (low to high)', 'directorist' ),
        'price-desc' => __( 'Price (high to low)', 'directorist' ),
        'rand'       => __( 'Random listings', 'directorist' ),
    );

    if ( ! is_array( $orderby ) ) {
		$orderby = (array) $orderby;
	}

    if ( ! in_array( 'a_z', $orderby, true ) ) {
        unset( $orderby_options['title-asc'] );
    }
    if ( ! in_array( 'z_a', $orderby, true ) ) {
        unset( $orderby_options['title-desc'] );
    }
    if ( ! in_array( 'latest', $orderby, true ) ) {
        unset( $orderby_options['date-desc'] );
    }
    if ( ! in_array( 'oldest', $orderby, true ) ) {
        unset( $orderby_options['date-asc'] );
    }
    if ( ! in_array( 'popular', $orderby, true ) ) {
        unset( $orderby_options['views-desc'] );
    }
    if ( ! in_array( 'price_low_high', $orderby, true ) ) {
        unset( $orderby_options['price-asc'] );
    }
    if ( ! in_array( 'price_high_low', $orderby, true ) ) {
        unset( $orderby_options['price-desc'] );
    }
    if ( ! in_array( 'random', $orderby, true ) ) {
        unset( $orderby_options['rand'] );
    }

    $listings_price_disabled = (bool) get_directorist_option( 'disable_list_price', 0 );
	if ( $listings_price_disabled || ! directorist_have_listings_with_price() ) {
        unset( $orderby_options['price-asc'], $orderby_options['price-desc'] );
    }

    return apply_filters( 'atbdp_get_listings_orderby_options', $orderby_options );
}

function directorist_have_listings_with_price() {
	$args = array(
		'post_type'              => ATBDP_POST_TYPE,
		'post_status'            => 'publish',
		'meta_key'               => '_price',
		'no_found_rows'          => true,
		'posts_per_page'         => 1,
		'update_post_meta_cache' => false,
		'update_post_term_cache' => false,
    );

	$listings_with_price = new WP_Query( $args );
	return $listings_with_price->have_posts();
}

/**
 * Get the listing view.
 *
 * @param string $view Default View.
 * @return   string    $view    Grid or List.
 * @since    4.0.0
 *
 */
function atbdp_get_listings_current_view_name($view)
{


    if (isset($_REQUEST['view'])) {
        $view = directorist_clean( wp_unslash( $_REQUEST['view'] ) );
    }

    $allowed_views = array('list', 'grid', 'map');
    if (class_exists('BD_Map_View')) {
        array_push($allowed_views, 'listings_with_map');
    }
    if (!in_array($view, $allowed_views)) {
        $listing_view = get_directorist_option('default_listing_view');
        $listings_settings = !empty($listing_view) ? $listing_view : 'grid';
        $view = $listings_settings;
    }


    return $view;

}

function atbdp_calculate_column( $number ) {
    switch( $number ) {
        case 1:
            $columns = 12;
            break;
        case 2:
            $columns = 6;
            break;
        case 3:
            $columns = 4;
            break;
        case 4:
            $columns = 3;
            break;
        case 5:
            $columns = 2;
            break;
        case 6:
            $columns = 2;
            break;
            default:
            $columns = 3;
    }

    return $columns;
}

/**
 * Get the list of listings view options.
 *
 * @return   array    $view_options    List of view Options.
 * @since    4.0.0
 *
 */
function atbdp_get_listings_view_options($view_as_items)
{
    $listing_view = get_directorist_option('default_listing_view');
    $listings_settings = !empty($listing_view) ? $listing_view : 'grid';

    $options = array('grid', 'list', 'map');
    $display_map = get_directorist_option('display_map_field', 1);
    $select_listing_map = get_directorist_option('select_listing_map', 'google');

    $view_as_items = is_array( $view_as_items ) ? $view_as_items : [];
    if (!in_array('listings_grid', $view_as_items)) {
        unset($options[0]);
    }
    if (!in_array('listings_list', $view_as_items)) {
        unset($options[1]);
    }
    if (empty($display_map) || !in_array('listings_map', $view_as_items)) {
        unset($options[2]);
    }
    $options[] = isset($_GET['view']) ? directorist_clean( wp_unslash( $_GET['view'] ) ) : $listings_settings;
    $options = array_unique($options);

    $views = array();

    foreach ($options as $option) {

        switch ($option) {
            case 'list' :
                $views[$option] = __('List', 'directorist');
                break;
            case 'grid' :
                $views[$option] = __('Grid', 'directorist');
                break;
            case 'map' :
                $views[$option] = __('Map', 'directorist');
                break;
        }

    }

    return $views;

}

/**
 * @param $var
 * @return array|string
 */
function atbdp_get_view_as($view)
{
    $views = atbdp_get_listings_view_options($view);
    $ways = '';
    foreach ($views as $value => $label) {
        $active_class = ($view == $value) ? ' active' : '';
        $ways = sprintf('<a class="dropdown-item%s" href="%s">%s</a>', $active_class, add_query_arg('view', $value), $label);

    }
    return $ways;


}

/*
 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
 * Non-scalar values are ignored.
 *
 * @param string|array $var Data to sanitize.
 * @return string|array
 */
function directorist_clean($var)
{
    if (is_array($var)) {
        return array_map('directorist_clean', $var);
    } else {
        return is_scalar($var) ? sanitize_text_field($var) : $var;
    }
}

/**
 * Display the favourites link.
 *
 * @param int $post_id Post ID.
 * @return   mixed        Included the favourites and unfavourites button
 * @since    4.0
 *
 */
function the_atbdp_favourites_link( $post_id = 0 ) {
    if ( is_user_logged_in() ) {
        if ( $post_id == 0 ) {
            global $post;
            $post_id = $post->ID;
        }

        $favourites = directorist_get_user_favorites( get_current_user_id() );
        if ( in_array( $post_id, $favourites ) ) {
            return directorist_icon( 'las la-heart', false, 'directorist-added-to-favorite') . '<a href="#" class="atbdp-favourites" data-post_id="' . $post_id . '"></a>';
        } else {
            return directorist_icon( 'las la-heart', false ) . '<a href="#" class="atbdp-favourites" data-post_id="' . $post_id . '"></a>';
        }
    } else {
        return '<a href="#" class="atbdp-require-login">'.directorist_icon( 'las la-heart', false ).'</a>';
    }
}


function atbdp_listings_mark_as_favourite( $listing_id ) {
    $favourites = directorist_get_user_favorites( get_current_user_id() );
    $fav_class  = '';

    if ( in_array( $listing_id, $favourites ) ) {
        $fav_class = 'atbdp_fav_isActive';
    }

    $mark_as_fav_link = '<div class="atbdp_add_to_fav_listings"><a class="atbdp_mark_as_fav ' . $fav_class . '" id="atbdp-fav_' . $listing_id . '" data-listing_id="' . $listing_id . '" href=""><span class="atbd_fav_icon"></span><span class="atbd_fav_tooltip"></span></a></div>';
    return $mark_as_fav_link;
}

/**
 * Generate a permalink to remove from favourites.
 *
 * @param int $listing_id Listing ID.
 * @return   string                   URL to remove from favourites.
 * @since    1.0.0
 *
 */
function atbdp_get_remove_favourites_page_link($listing_id)
{

    $link = add_query_arg(array('atbdp_action' => 'remove-favourites', 'atbdp_listing' => $listing_id));

    return $link;

}

if (!function_exists('new_badge')) {
    function new_badge()
    {
        global $post;
        $new_listing_time = get_directorist_option('new_listing_day');
        $new_badge_text = get_directorist_option('new_badge_text', 'New');
        $each_hours = 60 * 60 * 24; // seconds in a day
        $s_date1 = strtotime(current_time('mysql')); // seconds for date 1
        $s_date2 = strtotime($post->post_date); // seconds for date 2
        $s_date_diff = abs($s_date1 - $s_date2); // different of the two dates in seconds
        $days = round($s_date_diff / $each_hours); // divided the different with second in a day
        $new = '<span class="atbd_badge atbd_badge_new">' . $new_badge_text . '</span>';
        if ($days <= (int)$new_listing_time) {
             return $new;

        }
    }
}

/**
 * Generate image crop.
 *
 * @param string $attachmentId Image Url.
 * @param int $width Image Width.
 * @param int $height Image Height.
 * @param bool $crop cropping condition.
 * @param int $quality Quality.
 * @return   array  $resizer return resize.
 * @since    4.0.0
 *
 */
function atbdp_image_cropping($attachmentId, $width, $height, $crop = true, $quality = 100)
{
    $resizer = new Atbdp_Image_resizer($attachmentId);

    return $resizer->resize($width, $height, $crop, $quality);
}

if (!function_exists('is_fee_manager_active')) {
    /**
     * It checks is user purchased plan included in that feature.
     * @return bool It returns true if the above mentioned exists.
     */
    function is_fee_manager_active()
    {
        $FM_disabled_byAdmin = get_directorist_option('fee_manager_enable', 1);
        $WFM_disabled_byAdmin = get_directorist_option('woo_pricing_plans_enable', 1);
        if (class_exists('ATBDP_Pricing_Plans') && $FM_disabled_byAdmin) {
            return true;
        } elseif (class_exists('DWPP_Pricing_Plans') && $WFM_disabled_byAdmin) {
            return true;
        } else {
            return false;
        }

    }
}

if ( ! function_exists( 'atbdp_pricing_plan_is_enabled' ) ) :
    // atbdp_pricing_plan_is_enabled
    function atbdp_pricing_plan_is_enabled() {
        $pricing_plan_is_enabled = get_directorist_option('fee_manager_enable', 1);

        if ( class_exists('ATBDP_Pricing_Plans') && $pricing_plan_is_enabled) {
            return true;
        }

        return false;
    }
endif;

if ( ! function_exists( 'atbdp_wc_pricing_plan_is_enabled' ) ) :
    // atbdp_wc_pricing_plan_is_enabled
    function atbdp_wc_pricing_plan_is_enabled() {
        $wc_pricing_plan_is_enabled = get_directorist_option('woo_pricing_plans_enable', 1);

        if ( class_exists('DWPP_Pricing_Plans') && $wc_pricing_plan_is_enabled) {
            return true;
        }

        return false;
    }
endif;

/**
 * Check that page is.
 *
 * @param string $atbdppages The page type.
 *
 * @return bool If valid returns true. Otherwise false.
 * @since   1.5.7 Updated to validate buddypress dashboard listings page as a author page.
 * @package atbdpectory
 * @global object $wp_query WordPress Query object.
 * @global object $post The current post object.
 *
 * @since   1.0.0
 * @since   1.5.6 Added to check GD invoices and GD checkout pages.
 */
function atbdp_is_page($atbdppages = '')
{
    global $post;

    $atbdppages = preg_replace( '/[-]/', '_', $atbdppages );

    switch ($atbdppages):
        case 'home':
            if (is_page() && get_the_ID() == get_directorist_option('search_listing')) {
                return true;
            } elseif (is_page() && isset($post->post_content) && has_shortcode($post->post_content, 'directorist_search_listing')) {
                return true;
            }
            break;
        case 'search_result':
            if (is_page() && get_the_ID() == get_directorist_option('search_result_page')) {
                return true;
            } elseif (is_page() && isset($post->post_content) && has_shortcode($post->post_content, 'directorist_search_result')) {
                return true;
            }
            break;
        case 'add_listing':
            if (is_page() && get_the_ID() == get_directorist_option('add_listing_page')) {
                return true;
            } elseif (is_page() && isset($post->post_content) && has_shortcode($post->post_content, 'directorist_add_listing')) {
                return true;
            }
            break;
        case 'all_listing':
            if (is_page() && get_the_ID() == get_directorist_option('all_listing_page')) {
                return true;
            } elseif (is_page() && isset($post->post_content) && has_shortcode($post->post_content, 'directorist_all_listing')) {
                return true;
            }
            break;
        case 'dashboard':
            if (is_page() && get_the_ID() == get_directorist_option('user_dashboard')) {
                return true;
            } elseif (is_page() && isset($post->post_content) && has_shortcode($post->post_content, 'directorist_user_dashboard')) {
                return true;
            }
            break;
        case 'author':
            if (is_page() && get_the_ID() == get_directorist_option('author_profile_page')) {
                return true;
            } elseif (is_page() && isset($post->post_content) && has_shortcode($post->post_content, 'directorist_author_profile')) {
                return true;
            }
            break;
        case 'category':
            if (is_page() && get_the_ID() == get_directorist_option('all_categories_page')) {
                return true;
            } elseif (is_page() && isset($post->post_content) && has_shortcode($post->post_content, 'directorist_all_categories')) {
                return true;
            }
            break;
        case 'single_listing':
            return is_singular('at_biz_dir');
            break;
        case 'single_category':
            if (is_page() && get_the_ID() == get_directorist_option('single_category_page')) {
                return true;
            } elseif (is_page() && isset($post->post_content) && has_shortcode($post->post_content, 'directorist_category')) {
                return true;
            }
            break;
        case 'all_locations':
            if (is_page() && get_the_ID() == get_directorist_option('all_locations_page')) {
                return true;
            } elseif (is_page() && isset($post->post_content) && has_shortcode($post->post_content, 'directorist_all_locations')) {
                return true;
            }
            break;
        case 'single_location':
            if (is_page() && get_the_ID() == get_directorist_option('single_location_page')) {
                return true;
            } elseif (is_page() && isset($post->post_content) && has_shortcode($post->post_content, 'directorist_location')) {
                return true;
            }
            break;
        case 'single_tag':
            if (is_page() && get_the_ID() == get_directorist_option('single_tag_page')) {
                return true;
            } elseif (is_page() && isset($post->post_content) && has_shortcode($post->post_content, 'directorist_tag')) {
                return true;
            }
            break;
        case 'registration':
            if (is_page() && get_the_ID() == get_directorist_option('custom_registration')) {
                return true;
            } elseif (is_page() && isset($post->post_content) && has_shortcode($post->post_content, 'directorist_custom_registration')) {
                return true;
            }
            break;
        case 'login':
            if (is_page() && get_the_ID() == get_directorist_option('user_login')) {
                return true;
            } elseif (is_page() && isset($post->post_content) && has_shortcode($post->post_content, 'directorist_user_login')) {
                return true;
            }
            break;

    endswitch;

    //endif;

    return false;
}

/**
 * @param $listing_id
 * @return integer $pop_listing_id
 * @since 4.7.8
 */
if (!function_exists('atbdp_popular_listings')) {
    function atbdp_popular_listings( $listing_id ) {
        $listing_popular_by         = get_directorist_option( 'listing_popular_by' );
        $average                    = directorist_get_listing_rating( $listing_id );
        $average_review_for_popular = (int) get_directorist_option( 'average_review_for_popular', 4 );
        $view_count                 = (int) get_post_meta( $listing_id, '_atbdp_post_views_count', true );
        $view_to_popular            = (int) get_directorist_option( 'views_for_popular' );

        if ( 'average_rating' === $listing_popular_by && $average_review_for_popular <= $average ) {
            return $listing_id;
        } elseif ( 'view_count' === $listing_popular_by && $view_count >= $view_to_popular ) {
			return $listing_id;
        } elseif ( $average_review_for_popular <= $average && $view_count >= $view_to_popular ) {
			return $listing_id;
        }

		return 0;
    }
}

function atbdp_get_custom_field_ids($category = 0, $all = false)
{
    // Get global fields
    $args = array(
        'post_type'      => ATBDP_CUSTOM_FIELD_POST_TYPE,
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'fields'         => 'ids',
    );

    if( !$all ){
        $args['meta_query'] = array(
            array(
                'key'   => 'associate',
                'value' => 'form'
            ),
        );
    }

    // Get category fields
    if ( $category > 0 ) {
        $args['meta_query'] = array(
            'relation' => 'OR',
            array(
                'key'   => 'associate',
                'value' => 'form'
            ),

            array(
                'relation' => 'AND',
                array(
                    'key'     => 'category_pass',
                    'value'   => $category,
                    'compare' => 'EXISTS',
                ),
                array(
                    'key'     => 'associate',
                    'value'   => 'categories',
                    'compare' => 'LIKE',
                ),
            ),
        );
    }

    $field_ids = ATBDP_Cache_Helper::get_the_transient([
        'group'      => 'atbdp_custom_field_query',
        'name'       => 'atbdp_custom_field_ids',
        'query_args' => $args,
        'cache'      => apply_filters( 'atbdp_cache_custom_field_ids', true ),
        'value'      => function( $data ) {
            return get_posts( $data['query_args'] );
        }
    ]);

    // Return
    if (empty($field_ids)) {
        $field_ids = array(0);
    }

    return $field_ids;

}

function get_advance_search_result_page_link()
{

    $link = home_url();

    if (get_option('permalink_structure')) {

        $page_settings = get_directorist_option('advance_search_result');;

        if ($page_settings > 0) {
            $link = get_permalink($page_settings);
        }

    }

    return $link;
}

/**
 * @return Wp_Query
 * @since 4.7.7
 */
if (!function_exists('atbdp_get_expired_listings')) {
    function atbdp_get_expired_listings($texonomy, $categories)
    {
        return new WP_Query(array());
    }
}

/**
 * Get current address bar URL.
 *
 * @return   string    Current Page URL.
 * @since    5.4.0
 *
 */
function atbdp_get_current_url()
{

    $current_url = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") ? "https://" : "http://";
    $current_url .= ! empty( $_SERVER["SERVER_NAME"] ) ? directorist_clean( wp_unslash( $_SERVER["SERVER_NAME"] ) ) : '';
    $server_port = ! empty( $_SERVER["SERVER_PORT"] ) ? directorist_clean( wp_unslash( $_SERVER["SERVER_PORT"] ) ) : '';
    if ($server_port != "80" && $server_port != "443") {
        $current_url .= ":" . $server_port;
    }
    $current_url .= ! empty( $_SERVER["REQUEST_URI"] ) ? directorist_clean( wp_unslash( $_SERVER["REQUEST_URI"] ) ) : '';

    return $current_url;

}

/**
 * Check if Yoast SEO plugin is active and Directorist can use that.
 *
 * @return bool $can_use_yoast "true" if can use Yoast, "false" if not.
 * @since 5.4.4
 *
 */
function atbdp_can_use_yoast()
{

    $can_use_yoast  = false;
    $active_plugins = apply_filters('active_plugins', get_option('active_plugins', []));

    $yoast_free_is_active    = ( in_array('wordpress-seo/wp-seo.php', $active_plugins) ) ? true : false;
    $yoast_premium_is_active = ( in_array('wordpress-seo-premium/wp-seo-premium.php', $active_plugins) ) ? true : false;

    if ( $yoast_free_is_active || $yoast_premium_is_active ) {
        $can_use_yoast = true;
    }

    return $can_use_yoast;

}

// atbdp_yoast_is_active
function atbdp_yoast_is_active() {
    return atbdp_can_use_yoast();
}

/**arg
 *
 * @return bool $can_use_yoast "true" if can use Yoast, "false" if not.
 * @since 5.5.2
 *
 */
function atbdp_can_overwrite_yoast()
{
    $overwrite = false;
    $overwrite_yoast = get_directorist_option('overwrite_by_yoast');
    if ( ! empty( $overwrite_yoast ) || ! atbdp_yoast_is_active() ) {
        $overwrite = true;
    }

    return $overwrite;

}

function atbdp_disable_overwrite_yoast() {
    atbdp_can_overwrite_yoast();
}


if (!function_exists('atbdp_page')) {
    function atbdp_page()
    {
        $pages = array(
            get_directorist_option('search_listing'), get_directorist_option('search_result_page'), get_directorist_option('add_listing_page'), get_directorist_option('all_listing_page'), get_directorist_option('all_categories_page'), get_directorist_option('single_category_page'), get_directorist_option('all_locations_page'), get_directorist_option('single_location_page'), get_directorist_option('single_tag_page'), get_directorist_option('author_profile_page'), get_directorist_option('user_dashboard'), get_directorist_option('custom_registration'), get_directorist_option('user_login'), get_directorist_option('checkout_page'), get_directorist_option('payment_receipt_page'), get_directorist_option('transaction_failure_page'),
        );
        foreach ($pages as $page) {
            return $page;
        }
    }
}
/**
 * @param $id
 * @param $tax
 * @return integer  Return the level of the term
 * @since 5.5.4
 */
function atbdp_get_tax_level($id, $tax)
{
    $ancestors = get_ancestors($id, $tax);
    return count($ancestors) + 1;
}

/**
 * @param $data
 * @since 5.6.5
 */
function send_review_for_approval($data)
{
    $listing_id = $data['post_id'];
    $review_id = wp_insert_post(array(
        'post_content' => '',
        'post_title' => get_the_title($listing_id),
        'post_status' => 'publish',
        'post_type' => 'atbdp_listing_review',
        'comment_status' => false,
    ));
    update_post_meta($review_id, '_review_listing', $listing_id);
    $listing_reviewer = $data['name'];
    update_post_meta($review_id, '_listing_reviewer', $listing_reviewer);
    update_post_meta($review_id, '_review_status', 'pending');
    $reviewer_details = $data['content'];
    update_post_meta($review_id, '_reviewer_details', $reviewer_details);
    $reviewer_rating = $data['rating'];
    update_post_meta($review_id, '_reviewer_rating', $reviewer_rating);

    $post_id = $data['post_id'];
    update_post_meta($review_id, '_post_id', $post_id);

    $email = $data['email'];
    update_post_meta($review_id, '_email', $email);

    $by_guest = $data['by_guest'];
    update_post_meta($review_id, '_by_guest', $by_guest);

    $by_user_id = $data['by_user_id'];
    update_post_meta($review_id, '_by_user_id', $by_user_id);
    //wp_send_json_success(array('id'=>$data));
    /* $message = array('error' => 0);
     $message['approve'] = 'plan';
     wp_send_json_success(array('id'=>$message));*/
}

/**
 * Check is user already submitted review for this listing
 *
 * @since 5.7.1
 * @return bool
 */
if (!function_exists('tract_duplicate_review')) {
    function tract_duplicate_review($reviewer, $listing) {
		_deprecated_function( __FUNCTION__, '7.4.3' );
		return false;
    }
}

function search_category_location_filter($settings, $taxonomy_id, $prefix = '')
{
	$lazy_load_taxonomy_fields = get_directorist_option( 'lazy_load_taxonomy_fields', false, true );

	if ( ! empty( $lazy_load_taxonomy_fields ) ) {
		return '';
	}

    if ($settings['immediate_category']) {

        if ($settings['term_id'] > $settings['parent'] && !in_array($settings['term_id'], $settings['ancestors'])) {
            return;
        }

    }
    if (ATBDP_CATEGORY == $taxonomy_id) {
        $category_slug = get_query_var('atbdp_category');
        $category = get_term_by('slug', $category_slug, ATBDP_CATEGORY);
        $category_id = !empty($category->term_id) ? $category->term_id : '';
        $term_id = isset($_GET['in_cat']) ? directorist_clean( wp_unslash( $_GET['in_cat'] ) ) : $category_id;
    } else {
        $location_slug = get_query_var('atbdp_location');
        $location = get_term_by('slug', $location_slug, ATBDP_LOCATION);
        $location_id = !empty($location->term_id) ? $location->term_id : '';
        $term_id = isset($_GET['in_loc']) ? directorist_clean( wp_unslash( $_GET['in_loc'] ) ) : $location_id;
    }

    $args =  array(
        'orderby'      => $settings['orderby'],
        'order'        => $settings['order'],
        'hide_empty'   => $settings['hide_empty'],
        'parent'       => $settings['term_id'],
        'hierarchical' => ! empty($settings['hide_empty']) ? true : false
    );

    if (ATBDP_CATEGORY == $taxonomy_id){
        $arg = apply_filters('atbdp_search_listing_category_argument', $args);
    } else {
        $arg = apply_filters('atbdp_search_listing_location_argument', $args);
    }

    $terms = get_terms( $taxonomy_id, $arg );

    $html = '';

    if (count($terms) > 0) {

        foreach ($terms as $term) {
            $directory_type = get_term_meta( $term->term_id, '_directory_type', true );
            $directory_type = ! empty( $directory_type ) ? $directory_type : array();
            if( in_array( $settings['listing_type'], $directory_type ) ) {
                $settings['term_id'] = $term->term_id;

                $count = 0;
                if (!empty($settings['hide_empty']) || !empty($settings['show_count'])) {
                    $count = atbdp_listings_count_by_category($term->term_id);

                    if (!empty($settings['hide_empty']) && 0 == $count) continue;
                }
                $selected = ($term_id == $term->term_id) ? "selected" : '';

                $custom_field = '';

                if(is_array($settings['assign_to_category']['assign_to_cat'])) {
                    $custom_field = in_array( $term->term_id, $settings['assign_to_category']['assign_to_cat'] ) ? true : '';
                }

                $html .= '<option data-custom-field="' . $custom_field . '" value="' . $term->term_id . '" ' . $selected . '>';
                $html .= $prefix . $term->name;
                if (!empty($settings['show_count'])) {
                    $html .= ' (' . $count . ')';
                }
                $html .= search_category_location_filter($settings, $taxonomy_id, $prefix . '&nbsp;&nbsp;&nbsp;');
                $html .= '</option>';
            }
        }

    }

    return $html;

}

function add_listing_category_location_filter( $lisitng_type, $settings, $taxonomy_id, $term_id, $prefix = '', $plan_cat = array())
{
    if ($settings['immediate_category']) {

        if ($settings['term_id'] > $settings['parent'] && !in_array($settings['term_id'], $settings['ancestors'])) {
            return;
        }

    }

    $args = array(
        'orderby' => $settings['orderby'],
        'order' => $settings['order'],
        'hide_empty' => $settings['hide_empty'],
        'parent' => $settings['term_id'],
        'exclude' => $plan_cat,
        'hierarchical' => !empty($settings['hide_empty']) ? true : false
    );

    $terms = get_terms($taxonomy_id, $args);
    $html  = '';

    if (count($terms) > 0) {

        foreach ($terms as $term) {
            $directory_type    = get_term_meta( $term->term_id, '_directory_type', true );
            $directory_type    = ! empty( $directory_type ) ? $directory_type : array();
            $directory_type    = is_array( $directory_type ) ? $directory_type : array( $directory_type );
            if( in_array( $lisitng_type, $directory_type ) ) {
                $settings['term_id'] = $term->term_id;

                $count = 0;
                if (!empty($settings['hide_empty']) || !empty($settings['show_count'])) {
                    $count = atbdp_listings_count_by_category($term->term_id);

                    if (!empty($settings['hide_empty']) && 0 == $count) continue;
                }
                $selected = in_array($term->term_id, $term_id) ? "selected" : '';
                $html .= sprintf('<option value="%s" %s>', $term->term_id, $selected);
                $html .= $prefix . $term->name;
                if (!empty($settings['show_count'])) {
                    $html .= ' (' . $count . ')';
                }
                $html .= add_listing_category_location_filter($lisitng_type, $settings, $taxonomy_id, $term_id, $prefix . '&nbsp;&nbsp;&nbsp;');
                $html .= '</option>';
            }
        }

    }

    return $html;

}


/*
 * @since 6.3.0
 */
function atbdp_guest_submission($guest_email)
{
    $string = $guest_email;
    $explode = explode("@", $string);
    array_pop($explode);
    $userName = join('@', $explode);
    //check if username already exist
    if (username_exists($userName)) {
        $random = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyz'), 1, 5);
        $userName = $userName . $random;
    }
    // Check if user exist by email
    if (email_exists($guest_email)) {
        wp_send_json(array(
                'error'                => true,
                'quick_login_required' => true,
                'email'                => $guest_email,
                'error_msg'            => __('Email already registered. Please login first', 'directorist'),
        ));
        die();
    } else {
        // lets register the user
        $reg_errors = new WP_Error;
        if (empty($reg_errors->get_error_messages())) {
            $password = wp_generate_password(12, false);
            $userdata = array(
                'user_login' => $userName,
                'user_email' => $guest_email,
                'user_pass' => $password,
            );
            $user_id = wp_insert_user($userdata); // return inserted user id or a WP_Error
            wp_set_current_user($user_id, $guest_email);
            wp_set_auth_cookie($user_id);
            do_action('atbdp_user_registration_completed', $user_id);
            update_user_meta($user_id, '_atbdp_generated_password', $password);
            
			if ( directorist_is_email_verification_enabled() ) {
				// Set unverified flag. Once verified this flag will be removed.
				update_user_meta( $user_id, 'directorist_user_email_unverified', 1 );
			}

			wp_new_user_notification($user_id, null, 'admin'); // send activation to the admin
            
			ATBDP()->email->custom_wp_new_user_notification_email($user_id);
        }
    }
}

function atbdp_get_listing_attachment_ids( $listing_id ) {
	$featured_image = (int) get_post_meta( $listing_id, '_listing_prv_img', true );
	$attachment_ids = array();

	if ( $featured_image ) {
		$attachment_ids[] = $featured_image;
	}

    $gallery_images = (array) get_post_meta( $listing_id, '_listing_img', true );

	if ( empty( $gallery_images ) ) {
		return $attachment_ids;
	}

	$gallery_images = wp_parse_id_list( $gallery_images );
	$gallery_images = array_filter( $gallery_images );

	if ( empty( $gallery_images ) ) {
		return $attachment_ids;
	}

    $attachment_ids = array_merge( $attachment_ids, $gallery_images );

    return $attachment_ids;
}


function get_uninstall_settings_submenus() {
    return apply_filters('atbdp_uninstall_settings_fields', array(
        array(
            'type' => 'toggle',
            'name' => 'enable_uninstall',
            'label' => __('Remove Data on Uninstall?', 'directorist'),
            'description'=> __('Checked it if you would like Directorist to completely remove all of its data when the plugin is deleted.','directorist'),
            'default' => 0,
        ),
    )
    );
}
function get_csv_import_settings_submenus() {
    return apply_filters('atbdp_csv_import_settings_fields', array(
        array(
            'type' => 'toggle',
            'name' => 'csv_import',
            'label' => __('CSV', 'directorist'),
        ),
    )
    );
}

function atbdp_email_html($subject, $message){
    $site_name = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
    $header = '';
    $email_header_color = get_directorist_option('email_header_color', '#8569fb');
    $allow_email_header = get_directorist_option('allow_email_header', 1);
    if ($allow_email_header){
        $header = apply_filters('atbdp_email_header', '<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_header" style=\'background-color: '.$email_header_color.'; color: #ffffff; border-bottom: 0; font-weight: bold; line-height: 100%; vertical-align: middle; font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif; border-radius: 3px 3px 0 0;\'>
                                        <tr>
                                            <td id="header_wrapper" style="padding: 36px 48px; display: block;">
                                                <h1 style=\'font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif; font-size: 30px; font-weight: 300; line-height: 150%; margin: 0; text-align: left; text-shadow: 0 1px 0 #ab79a1; color: #ffffff;\'>'.$subject.'</h1>
                                            </td>
                                        </tr>
                                    </table>');
    }

    return '<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Directorist</title>
    </head>
    <body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" style="padding: 0;">
        <div id="wrapper" dir="ltr" style="background-color: #f7f7f7; margin: 0; padding: 70px 0; width: 100%; -webkit-text-size-adjust: none;">
            <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                <tr>
                    <td align="center" valign="top">
                        <div id="template_header_image">
                                                    </div>
                        <table border="0" cellpadding="0" cellspacing="0" width="600" id="template_container" style="background-color: #ffffff; border: 1px solid #dedede; box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1); border-radius: 3px;">
                            <tr>
                                <td align="center" valign="top">
                                    <!-- Header -->
                                    '.$header.'
                                    <!-- End Header -->
                                </td>
                            </tr>
                            <tr>
                                <td align="center" valign="top">
                                    <!-- Body -->
                                    <table border="0" cellpadding="0" cellspacing="0" width="600" id="template_body">
                                        <tr>
                                            <td valign="top" id="body_content" style="background-color: #ffffff;">
                                                <!-- Content -->
                                                <table border="0" cellpadding="20" cellspacing="0" width="100%">
                                                    <tr>
                                                        <td valign="top" style="padding: 48px 48px 32px;">
                                                            <div id="body_content_inner" style=\'color: #636363; font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif; font-size: 14px; line-height: 150%; text-align: left;\'>
'.$message.'
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <!-- End Content -->
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- End Body -->
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td align="center" valign="top">
                        <!-- Footer -->
                        <table border="0" cellpadding="10" cellspacing="0" width="600" id="template_footer">
                            <tr>
                                <td valign="top" style="padding: 0; border-radius: 6px;">
                                    <table border="0" cellpadding="10" cellspacing="0" width="100%">
                                        <tr>
                                            <td colspan="2" valign="middle" id="credit" style=\'border-radius: 6px; border: 0; color: #8a8a8a; font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif; font-size: 12px; line-height: 150%; text-align: center; padding: 24px 0;\'>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <!-- End Footer -->
                    </td>
                </tr>
            </table>
        </div>
    </body>
</html>';
}

function atbdp_create_required_pages(){
    $options = get_option('atbdp_option'); // we are retrieving all of our custom options because it contains all the page options too. and we can filter this array instead of calling get_directorist_option() over and over.
    /*
    Remember: We can not add new option to atbdp_option if there is no key matched. Because VafPress will override it.
    Use normal update_option() instead if you need to add custom option that is not available in the settings fields of VP Framework.
    */

    $directorist_pages = apply_filters('atbdp_create_custom_pages', array(
        'search_listing' => array(
            'title' => __('Search Home', 'directorist'),
            'content' => '[directorist_search_listing]'
        ),
        'search_result_page' => array(
            'title' => __('Search Result', 'directorist'),
            'content' => '[directorist_search_result]'
        ),
        'add_listing_page' => array(
            'title' => __('Add Listing', 'directorist'),
            'content' => '[directorist_add_listing]'
        ),
        'all_listing_page' => array(
            'title' => __('All Listings', 'directorist'),
            'content' => '[directorist_all_listing]'
        ),
        /* 'all_categories_page' => array(
            'title' => __('All Categories', 'directorist'),
            'content' => '[directorist_all_categories]'
        ), */
        'single_category_page' => array(
            'title' => __('Single Category', 'directorist'),
            'content' => '[directorist_category]'
        ),
        /* 'all_locations_page' => array(
            'title' => __('All Locations', 'directorist'),
            'content' => '[directorist_all_locations]'
        ), */
        'single_location_page' => array(
            'title' => __('Single Location', 'directorist'),
            'content' => '[directorist_location]'
        ),
        'single_tag_page' => array(
            'title' => __('Single Tag', 'directorist'),
            'content' => '[directorist_tag]'
        ),
        'author_profile_page' => array(
            'title' => __('Author Profile', 'directorist'),
            'content' => '[directorist_author_profile]'
        ),
        'user_dashboard' => array(
            'title' => __('Dashboard', 'directorist'),
            'content' => '[directorist_user_dashboard]'
        ),
        /* 'checkout_page' => array(
            'title' => __('Checkout', 'directorist'),
            'content' => '[directorist_checkout]'
        ),
        'payment_receipt_page' => array(
            'title' => __('Payment Receipt', 'directorist'),
            'content' => '[directorist_payment_receipt]'
        ),
        'transaction_failure_page' => array(
            'title' => __('Transaction Failure', 'directorist'),
            'content' => '[directorist_transaction_failure]'
        ), */
    ));
    $new_settings = 0; // lets keep track of new settings so that we do not update option unnecessarily.
    // lets iterate over the array and insert a new page with with the appropriate shortcode if the page id is not available in the option array.
    foreach ($directorist_pages as $op_name => $page_settings) {
        // $op_name is the page option name in the database.
        // if we do not have the page id assigned in the settings with the given page option name, then create an page
        // and update the option.

        if (empty($options[$op_name]) || !get_post($options[$op_name])) {

            $id = wp_insert_post(
                array(
                    'post_title' => $page_settings['title'],
                    'post_content' => $page_settings['content'],
                    'post_status' => 'publish',
                    'post_type' => 'page',
                    'comment_status' => 'closed'
                )
            );
            // if we have added the page successfully, lets add the page id to the options array to save the page settings in the database after the loop.
            if ($id) {
                $options[$op_name] = (int)$id;

                /*TRYING TO SET THE DEFAULT PAGE TEMPLATE FOR THIS PAGE WHERE OUR SHORTCODE IS USED */
                // get the template list of the theme and if it has any full width template then assign it.
                $page_templates = wp_get_theme()->get_page_templates();
                $custom_template = ''; // place holder for full width template
                $temp_type = ('search_listing' == $op_name) ? 'home-page.php' : 'full'; // look for home template for search_listing page
                // lets see if we can find any full width template, then use it for the page where our shortcode is used.
                foreach ($page_templates as $slug => $name) {
                    // checkout page and payment receipt page looks better on non full-width template, so skip them.
                    if (in_array($op_name, array('checkout_page', 'payment_receipt_page'))) break;
                    if (strpos($slug, $temp_type)) {
                        $custom_template = $slug;
                        break;
                    }
                }
                if (!empty($custom_template)) update_post_meta($id, '_wp_page_template', sanitize_text_field($custom_template));


            }
            $new_settings++;
        } else {
            $replace_shortcode = wp_update_post(
                array(
                    'ID' => $options[$op_name],
                    'post_title' => $page_settings['title'],
                    'post_content' => $page_settings['content'],
                    'post_status' => 'publish',
                    'post_type' => 'page',
                    'comment_status' => 'closed'
                ), true
            );
        }
        // if we have new options then lets update the options with new option values.
        if ($new_settings) {
            update_option('atbdp_option', $options);
        };
        update_option('atbdp_pages_version', 1);
    }
}

/**
 * Check if user is logged in.
 *
 * @deprecated 7.0.6.3 Use the built-in is_user_logged_in() instead.
 *
 * @return bool
 */
function atbdp_logged_in_user(){
    return is_user_logged_in();
}

function atbdp_thumbnail_card($img_src = '', $_args = array())
{
    $args = apply_filters('atbdp_preview_image_args', $_args);

    // Default
    $is_blur           = get_directorist_option('prv_background_type', 'blur');     // blur | color
    $is_blur           = ('blur' === $is_blur ? true : false);
    $container_size_by = get_directorist_option('prv_container_size_by', 'px');
    $by_ratio          = ( 'px' === $container_size_by ) ? false : true;
    $image_size        = get_directorist_option('way_to_show_preview', 'cover');    // contain | full | cover
    $ratio_width       = get_directorist_option('crop_width', 360);
    $ratio_height      = get_directorist_option('crop_height', 300);
    $blur_background   = $is_blur;
    $background_color  = get_directorist_option('prv_background_color', '#fff');
    $image_quality     = get_directorist_option('preview_image_quality', 'directorist_preview');  // medium | large | full

    $thumbnail_img = '';

    $listing_prv_img   = get_post_meta(get_the_ID(), '_listing_prv_img', true);
    $listing_img       = get_post_meta(get_the_ID(), '_listing_img', true);
    $default_image_src = get_directorist_option('default_preview_image', DIRECTORIST_ASSETS . 'images/grid.jpg');

    if ( is_array( $listing_img ) && ! empty( $listing_img ) ) {
        $thumbnail_img = atbdp_get_image_source( $listing_img[0], $image_quality );
        $thumbnail_id = $listing_img[0];
    }

    if ( ! empty( $listing_prv_img ) ) {
        $thumbnail_img = atbdp_get_image_source( $listing_prv_img, $image_quality );
        $thumbnail_id = $listing_prv_img;
    }

    if ( ! empty( $img_src ) ) {
        $thumbnail_img = $img_src;
        $thumbnail_id = 0;
    }

    if ( empty( $thumbnail_img ) ) {
        $thumbnail_img = $default_image_src;
        $thumbnail_id = 0;
    }

    /* if ( 'cover' === $image_size && false === $by_ratio ) {
        $thumbnail_img = atbdp_image_cropping($thumbnail_id, $ratio_width, $ratio_height, true, 100)['url'];
    } */

    if ( empty( $thumbnail_img ) ) { return ''; }

    $image_src    = is_array($thumbnail_img) ? $thumbnail_img[0] : $thumbnail_img;
    $image_alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
    $image_alt = ( ! empty( $image_alt ) ) ? esc_attr( $image_alt ) : esc_html( get_the_title( $thumbnail_id ) );
    $image_alt = ( ! empty( $image_alt ) ) ? $image_alt : esc_html( get_the_title() );

    // Extend Default
    if ( isset($args['image']) ) {
        $image_src = esc_html(stripslashes($args['image']));
    }
    if ( isset($args['image-size']) ) {
        $image_size = esc_html(stripslashes($args['image-size']));
    }
    if ( isset($args['width']) ) {
        $ratio_width = esc_html(stripslashes($args['width']));
    }
    if ( isset($args['height']) ) {
        $ratio_height = esc_html(stripslashes($args['height']));
    }
    if ( isset($args['alt']) ) {
        $image_alt = esc_html(stripslashes($args['alt']));
    }
    if ( isset($args['blur-background']) ) {
        $blur_background = esc_html(stripslashes($args['blur-background']));
    }
    if ( isset($args['background-color']) ) {
        $background_color = esc_html(stripslashes($args['background-color']));
    }

    // Style
    $style = '';

    if ( $by_ratio ) {
        $padding_top_value = (int) $ratio_height / (int) $ratio_width * 100;
        $padding_top_css = "padding-top: $padding_top_value%;";
        $style .= $padding_top_css;
    } else {
        $height_value = (int) $ratio_height;
        $height_css = "height: {$height_value}px;";
        $style .= $height_css;
    }

    $background_color_css = '';
    if ( $image_size !== 'full' && !$blur_background ) {
        $background_color_css = "background-color: $background_color";
        $style .= $background_color_css;
    }


    // Card Front Wrap
    $card_front_wrap = "<div class='atbd-thumbnail-card-front-wrap'>";
    $card_front__img = "<img src='$image_src' alt='$image_alt' class='atbd-thumbnail-card-front-img'/>";
    $front_wrap_html = $card_front_wrap . $card_front__img . "</div>";

    // Card Back Wrap
    $card_back_wrap = "<div class='atbd-thumbnail-card-back-wrap'>";
    $card_back__img = "<img src='$image_src' class='atbd-thumbnail-card-back-img'/>";
    $back_wrap_html = $card_back_wrap . $card_back__img . "</div>";

    $blur_bg = ( $blur_background ) ? $back_wrap_html : '';

    // Card Contain
    $card_contain_wrap = "<div class='atbd-thumbnail-card card-contain' style='$style'>";
    $image_contain_html = $card_contain_wrap . $blur_bg . $front_wrap_html . "</div>";

    // Card Cover
    $card_cover_wrap = "<div class='atbd-thumbnail-card card-cover' style='$style'>";
    $image_cover_html = $card_cover_wrap . $front_wrap_html . "</div>";

    // Card Full
    $card_full_wrap = "<div class='atbd-thumbnail-card card-full' style='$background_color_css'>";
    $image_full_html = $card_full_wrap . $front_wrap_html . "</div>";

    $the_html = $image_cover_html;
    switch ($image_size) {
        case 'cover':
            $the_html = $image_cover_html;
            break;
        case 'contain':
            $the_html = $image_contain_html;
            break;
        case 'full':
            $the_html = $image_full_html;
            break;
    }

    echo wp_kses_post( $the_html );
}

function atbdp_style_example_image ($src) {
    $img = sprintf("<img src='%s'>", $src );
    echo wp_kses_post( $img );
}

if(!function_exists('csv_get_data')){
    function csv_get_data( $csv_file = null, $multiple = null, $delimiter = ',' ) {
		if ( empty( $delimiter ) ) {
			$delimiter = ',';
		}

        $data   = $multiple ? array() : '';
        $errors = array();

        if ( ! $csv_file ) {
			return $data;
		}

        // Attempt to change permissions if not readable
        if ( ! is_readable( $csv_file ) ) {
            chmod( $csv_file, 0744 );
        }

        // Check if file is writable, then open it in 'read only' mode
        if ( is_readable( $csv_file ) && $_file = fopen( $csv_file, 'r' ) ) {

            // To sum this part up, all it really does is go row by
            //  row, column by column, saving all the data
            $post = array();

            // Get first row in CSV, which is of course the headers
            $header = fgetcsv( $_file, 0, $delimiter );

            while ( $row = fgetcsv( $_file, 0, $delimiter ) ) {

                foreach ( $header as $i => $key ) {
                    $post[ $key ] = $row[ $i ];
                }

                if ( $multiple ) {
                    $data[] = $post;
                } else {
                    $data = $post;
                }
            }

            fclose( $_file );
        } else {
            $errors[] = sprintf(
				esc_html__( "File '%s' could not be opened. Check the file's permissions to make sure it's readable by your server.", 'directorist' ),
				$csv_file
			);
        }

        if ( ! empty( $errors ) ) {
            // ... do stuff with the errors
        }

        return $data;
    }
}

// Polyfill - backword comapbility of php7 function
if (!function_exists('array_key_first')) {
    function array_key_first(array $arr) {
        foreach($arr as $key => $unused) {
            return $key;
        }
        return NULL;
    }
}

function directorist_redirect_to_admin_setup_wizard() {
    // Delete the redirect transient
    delete_transient( '_directorist_setup_page_redirect' );

    wp_safe_redirect( add_query_arg( array( 'page' => 'directorist-setup' ), admin_url( 'index.php' ) ) );
    exit;
}

function directorist_default_directory(){
	return directorist_get_default_directory();
}


// @kowsar - remove later @for dev use only
function dvar_dump($data){
    return '';
    echo "<pre>";
    var_dump($data);
    echo "</pre>";
}

if( ! function_exists( 'atbdp_field_assigned_plan' ) ) {
    function atbdp_field_assigned_plan( $field_data, $selected_plan = NULL ) {
        if( ! $field_data ) return false;

        $quired_plan = ! empty( $_GET['plan'] ) ? sanitize_key( $_GET['plan'] ) : '';
        $selected_plan = ! empty( $selected_plan ) ? $selected_plan : $quired_plan;
        $plans = !empty( $field_data['plans'] ) ? $field_data['plans'] : [];

        if( $plans ) {
            foreach ( $plans as $plan ) {
                if( $plan['plan_id'] == $selected_plan ) {
                    return $plan;
                }
            }
        }
    }
}
if( !function_exists('directory_types') ){
    function directory_types() {
		return directorist_get_directories( array(
			'orderby'    => 'date',
            'order'      => 'DESC',
		) );
    }
}

if ( ! function_exists( 'directorist_get_default_directory' ) ) {
	/**
	 * Get default directory id.
	 *
	 * @return int Default directory id.
	 */
	function directorist_get_default_directory() {
		$directories = directorist_get_directories( array(
			'default_only' => true,
			'fields'       => 'ids',
		) );

		if ( empty( $directories ) || is_wp_error( $directories ) || ! isset( $directories[0] ) ) {
			return 0;
		}

		return $directories[0];
	}
}

if ( ! function_exists( 'default_directory_type' ) ) {
	/**
	 * Alias and backward compatible function of "directorist_get_default_directory".
	 *
	 * @see directorist_get_default_directory
	 *
	 * @return int Defualt directory id.
	 */
	function default_directory_type() {
		return directorist_get_default_directory();
	}
}

if( !function_exists('get_listing_types') ){
    function get_listing_types() {
        return directorist_get_directories_for_template();
    }
}

if( !function_exists('directorist_get_form_fields_by_directory_type') ){
    function directorist_get_form_fields_by_directory_type( $field = 'id', $value = '' ) {
        $term                   = get_term_by( $field, $value, ATBDP_TYPE );
        if( is_wp_error( $term ) ) {
            return [];
        }

		if ( ! ( $term instanceof \WP_Term ) ) {
		    return [];
		}
        
        $submission_form        = get_term_meta( $term->term_id, 'submission_form_fields', true );
        $submission_form_fields = ! empty( $submission_form['fields'] ) ? $submission_form['fields'] : [];
        return $submission_form_fields;
    }
}

if( !function_exists('directorist_legacy_mode') ){
    function directorist_legacy_mode() {
        return false;
    }
}

if( !function_exists('directorist_multi_directory') ){
    function directorist_multi_directory() {
        return directorist_is_multi_directory_enabled();
    }
}

if( ! function_exists( 'directorist_warnings' ) ) {
    function directorist_warnings() {
        $add_listing 			 	= get_directorist_option( 'add_listing_page' );
        $user_dashboard			 	= get_directorist_option( 'user_dashboard' );
        $user_profile   		 	= get_directorist_option( 'author_profile_page' );
        $single_category_page 	 	= get_directorist_option( 'single_category_page' );
        $single_location_page	 	= get_directorist_option( 'single_location_page' );
        $single_tag_page		 	= get_directorist_option( 'single_tag_page' );
        $custom_registration     	= get_directorist_option( 'custom_registration' );
        $user_login				 	= get_directorist_option( 'user_login' );
        $search_result_page      	= get_directorist_option( 'search_result_page' );
        $checkout_page			 	= get_directorist_option( 'checkout_page' );
        $payment_receipt_page	 	= get_directorist_option( 'payment_receipt_page' );
        $transaction_failure_page	= get_directorist_option( 'transaction_failure_page' );
        $enable_monetization	 	= directorist_is_monetization_enabled();
        $enable_featured_listing	= directorist_is_featured_listing_enabled();
        $select_listing_map			= get_directorist_option( 'select_listing_map' );
        $map_api_key				= get_directorist_option( 'map_api_key' );
        $host                       = gethostname();
        $connection                 =  @fsockopen( $host, 25, $errno, $errstr, 5 );
        $warnings = [];
        if( empty( $add_listing ) ) {
            $warnings[] = array(
                'title' => __( 'Add listing page not selected', 'directorist'),
                'desc'  => __( "Contains a collection of relevant data that will help you debug your website accurately and more efficiently.", 'directorist'),
                'link'  => admin_url()."/edit.php?post_type=at_biz_dir&page=aazztech_settings#_pages",
                'link_text' => __( 'Select Page', 'directorist' )
            );
        }
        if( empty( $user_dashboard ) ) {
            $warnings[] = array(
                'title' => __( 'Dashboard page not selected', 'directorist'),
                'desc'  => __( "Contains a collection of relevant data that will help you debug your website accurately and more efficiently.", 'directorist'),
                'link'  => admin_url()."/edit.php?post_type=at_biz_dir&page=aazztech_settings#_pages",
                'link_text' => __( 'Select Page', 'directorist' )
            );
        }
        if( empty( $user_profile ) ) {
            $warnings[] = array(
                'title' => __( 'User Profile page not selected', 'directorist'),
                'desc'  => __( "Contains a collection of relevant data that will help you debug your website accurately and more efficiently.", 'directorist'),
                'link'  => admin_url()."/edit.php?post_type=at_biz_dir&page=aazztech_settings#_pages",
                'link_text' => __( 'Select Page', 'directorist' )
            );
        }
        if( empty( $single_category_page ) ) {
            $warnings[] = array(
                'title' => __( 'Single Category page not selected', 'directorist'),
                'desc'  => __( "Contains a collection of relevant data that will help you debug your website accurately and more efficiently.", 'directorist'),
                'link'  => admin_url()."/edit.php?post_type=at_biz_dir&page=aazztech_settings#_pages",
                'link_text' => __( 'Select Page', 'directorist' )
            );
        }
        if( empty( $single_location_page ) ) {
            $warnings[] = array(
                'title' => __( 'Single Location page not selected', 'directorist'),
                'desc'  => __( "Contains a collection of relevant data that will help you debug your website accurately and more efficiently.", 'directorist'),
                'link'  => admin_url()."/edit.php?post_type=at_biz_dir&page=aazztech_settings#_pages",
                'link_text' => __( 'Select Page', 'directorist' )
            );
        }
        if( empty( $single_tag_page ) ) {
            $warnings[] = array(
                'title' => __( 'Single Location page not selected', 'directorist'),
                'desc'  => __( "Contains a collection of relevant data that will help you debug your website accurately and more efficiently.", 'directorist'),
                'link'  => admin_url()."/edit.php?post_type=at_biz_dir&page=aazztech_settings#_pages",
                'link_text' => __( 'Select Page', 'directorist' )
            );
        }
        if( empty( $custom_registration ) ) {
            $warnings[] = array(
                'title' => __( 'Registration page not selected', 'directorist'),
                'desc'  => __( "Contains a collection of relevant data that will help you debug your website accurately and more efficiently.", 'directorist'),
                'link'  => admin_url()."/edit.php?post_type=at_biz_dir&page=aazztech_settings#_pages",
                'link_text' => __( 'Select Page', 'directorist' )
            );
        }
        if( empty( $user_login ) ) {
            $warnings[] = array(
                'title' => __( 'Login page not selected', 'directorist'),
                'desc'  => __( "Contains a collection of relevant data that will help you debug your website accurately and more efficiently.", 'directorist'),
                'link'  => admin_url()."/edit.php?post_type=at_biz_dir&page=aazztech_settings#_pages",
                'link_text' => __( 'Select Page', 'directorist' )
            );
        }
        if( empty( $search_result_page ) ) {
            $warnings[] = array(
                'title' => __( 'Search Result page not selected', 'directorist'),
                'desc'  => __( "Contains a collection of relevant data that will help you debug your website accurately and more efficiently.", 'directorist'),
                'link'  => admin_url()."/edit.php?post_type=at_biz_dir&page=aazztech_settings#_pages",
                'link_text' => __( 'Select Page', 'directorist' )
            );
        }
        if( empty( $checkout_page ) && ! empty( $enable_monetization ) && ! empty( $enable_featured_listing ) ) {
            $warnings[] = array(
                'title' => __( 'Checkout page not selected', 'directorist'),
                'desc'  => __( "Contains a collection of relevant data that will help you debug your website accurately and more efficiently.", 'directorist'),
                'link'  => admin_url()."/edit.php?post_type=at_biz_dir&page=aazztech_settings#_pages",
                'link_text' => __( 'Select Page', 'directorist' )
            );
        }
        if( empty( $payment_receipt_page ) && ! empty( $enable_monetization ) && ! empty( $enable_featured_listing ) ) {
            $warnings[] = array(
                'title' => __( 'Payment Receipt page not selected', 'directorist'),
                'desc'  => __( "Contains a collection of relevant data that will help you debug your website accurately and more efficiently.", 'directorist'),
                'link'  => admin_url()."/edit.php?post_type=at_biz_dir&page=aazztech_settings#_pages",
                'link_text' => __( 'Select Page', 'directorist' )
            );
        }
        if( empty( $transaction_failure_page ) && ! empty( $enable_monetization ) && ! empty( $enable_featured_listing ) ) {
            $warnings[] = array(
                'title' => __( 'Transaction Failure page not selected', 'directorist'),
                'desc'  => __( "Contains a collection of relevant data that will help you debug your website accurately and more efficiently.", 'directorist'),
                'link'  => admin_url()."/edit.php?post_type=at_biz_dir&page=aazztech_settings#_pages",
                'link_text' => __( 'Select Page', 'directorist' )
            );
        }
        if( 'google' == $select_listing_map && empty( $map_api_key ) ) {
            $warnings[] = array(
                'title'      => __( 'Map Api Key is missing', 'directorist'),
                'desc'       => __( "Contains a collection of relevant data that will help you debug your website accurately and more efficiently.", 'directorist'),
                'link'       => admin_url()."/edit.php?post_type=at_biz_dir&page=aazztech_settings#_map_setting",
                'link_text'  => __( 'Give the Api', 'directorist' )
            );
        }
        if( ! is_resource( $connection ) ) {
            $warnings[] = array(
                'title'      => __( 'SMTP not configured', 'directorist'),
                'desc'       => __( "SMTP is a TCP/IP protocol responsible for email deliveries. You must configure SMTP to send or receive emails.", 'directorist'),
            );
        }

        return $warnings;
    }
}

/**
 * Get directory type url.
 *
 * @since 7.0.5.3
 *
 * @param string $type Directory type slug.
 * @param string|null|mixed $base_url Base url for type url.
 *
 * @return string Directory type url.
 */
function directorist_get_directory_type_nav_url( $type = 'all', $base_url = null ) {
	if ( empty( $base_url ) ) {
		$base_url = remove_query_arg( [ 'page', 'paged' ] );
		$base_url = preg_replace( '~/page/(\d+)/?~', '', $base_url );
		$base_url = preg_replace( '~/paged/(\d+)/?~', '', $base_url );
	}

	$url = add_query_arg( [ 'directory_type' => $type ], $base_url );

	return apply_filters( 'directorist_get_directory_type_nav_url', $url, $type, $base_url );
}

/**
 * Directorist add query args with no pagination
 *
 * @since 7.1.3
 *
 * @param string $query_args Query Args
 * @param string|null|mixed $base_url Base url for type url.
 *
 * @return string Final URL
 */
function directorist_add_query_args_with_no_pagination( $query_args = [], $base_url = null ) {

    if ( empty( $base_url ) ) {
		$base_url = ! empty( $_SERVER['REQUEST_URI'] ) ? directorist_clean( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
	}

    $base_url = remove_query_arg( [ 'page', 'paged' ], $base_url );
    $base_url = preg_replace( '~/page/(\d+)/?~', '', $base_url );
    $base_url = preg_replace( '~/paged/(\d+)/?~', '', $base_url );

	$url = add_query_arg( $query_args, $base_url );

	return apply_filters( 'directorist_add_query_args_with_no_pagination', $url, $query_args, $base_url );
}

if ( ! function_exists( 'directorist_is_plugin_active' ) ) {
    function directorist_is_plugin_active( $plugin ) {
        return in_array( $plugin, (array) get_option( 'active_plugins', array() ), true ) || directorist_is_plugin_active_for_network( $plugin );
    }
}

if ( ! function_exists( 'directorist_is_plugin_active_for_network' ) ) {
    function directorist_is_plugin_active_for_network( $plugin ) {
        if ( ! is_multisite() ) {
            return false;
        }

        $plugins = get_site_option( 'active_sitewide_plugins' );
        if ( isset( $plugins[ $plugin ] ) ) {
                return true;
        }

        return false;
    }
}

/**
 * Get error message based on error type.
 *
 * @since 7.0.6.2
 *
 * @param string $get_error_code
 *
 * @return string Error message.
 */
function directorist_get_registration_error_message( $error_code ) {
	$message = [
		'0' => __( 'Something went wrong!', 'directorist' ),
		'1' => __( 'Registration failed. Please make sure you filed up all the necessary fields marked with <span style="color: red">*</span>', 'directorist' ),
		'2' => sprintf( __( 'This email is already registered. Please <a href="%s">click here to login</a>.', 'directorist' ), ATBDP_Permalink::get_login_page_link() ),
		'3' => __( 'Username too short. At least 4 characters is required', 'directorist' ),
		'4' => sprintf( __( 'This username is already registered. Please <a href="%s">click here to login</a>.', 'directorist' ), ATBDP_Permalink::get_login_page_link() ),
		'5' => __( 'Password length must be greater than 5', 'directorist' ),
		'6' => __( 'Email is not valid', 'directorist' ),
		'7' => __( 'Space is not allowed in username', 'directorist' ),
		'8' => __( 'Please make sure you filed up the user type', 'directorist' ),
	];

	return isset( $message[ $error_code ] ) ? $message[ $error_code ] : '';
}

/**
 * Generate an unique nonce key using version constant.
 *
 * @since 7.0.6.2
 *
 * @return string nonce key with current version
 */
function directorist_get_nonce_key() {
    return 'directorist_nonce_' . ATBDP_VERSION;
}

/**
 * Check if the given nonce field contains a verified nonce.
 *
 * @since 7.0.6.2
 * @since 7.3.1 $action param added
 *
 * @see directorist_get_nonce_key()
 *
 * @param string $nonce_field $_GET or $_POST field name.
 * @param string $action Nonce action key. Default to directorist_get_nonce_key()
 *
 * @return boolen
 */
function directorist_verify_nonce( $nonce_field = 'directorist_nonce', $action = '' ) {
    $nonce = ! empty( $_REQUEST[ $nonce_field ] ) ? directorist_clean( wp_unslash( $_REQUEST[ $nonce_field ] ) ) : '';
    return wp_verify_nonce( $nonce, ( $action ? $action : directorist_get_nonce_key() ) );
}

/**
 * Get supported file types groups.
 *
 * @since 7.0.6.3
 *
 * @return array
 */
function directorist_get_supported_file_types_groups( $group = null ) {
	$groups = [
		'image' => [
			'jpg', 'jpeg', 'gif', 'png', 'bmp', 'ico'
		],
		'audio' => [
			'ogg', 'mp3', 'wav', 'wma',
		],
		'video' => [
			'asf', 'avi', 'mkv', 'mp4', 'mpg', 'mpeg', 'wmv', '3gp',
		],
		'document' => [
			'doc', 'docx', 'odt', 'pdf', 'ppt', 'pptx', 'xls', 'xlsx'
		]
	];

    $groups = apply_filters( 'directorist_supported_file_types_groups', $groups );

	if ( is_null( $group ) ) {
		return $groups;
	}

	return ( isset( $groups[ $group ] ) ? $groups[ $group ] : [] );
}

/**
 * Get supported file types.
 *
 * This function is used to for upload field options and to check uploaded file type validity.
 *
 * @since 7.0.6.3
 *
 * @return array
 */
function directorist_get_supported_file_types() {
	$groups = directorist_get_supported_file_types_groups();

	return array_reduce( $groups, function( $carry, $group ) {
		return array_merge( $carry, $group );
	}, [] );
}


function directorist_has_no_listing() {
	$listings = new WP_Query([
		'post_type'      => ATBDP_POST_TYPE,
		'posts_per_page' => 1,
		'no_found_rows'  => true,
	]);

	$has_no_listing = empty( $listings->posts );

	return $has_no_listing;
}

/**
 * Check if given listing id belongs to the given user id.
 *
 * @since 7.1.1
 * @param int $listing_id Listing id.
 * @param int $user_id User id.
 *
 * @return bool
 */
function directorist_is_listing_author( $listing_id = null, $user_id = null ) {
	if ( ! $user_id || ! is_int( $user_id ) ) {
		return false;
	}

	if ( ! $listing_id || ! is_int( $listing_id ) ) {
		$listing_id = get_the_ID();
	}

	$listing = get_post( $listing_id );
	if ( ! $listing || $listing->post_type !== ATBDP_POST_TYPE ) {
		return false;
	}

	if ( intval( $listing->post_author ) !== $user_id ) {
		return false;
	}

	return true;
}

/**
 * Check if given listing id belongs to the current user.
 *
 * @since 7.1.1
 * @param int $listing_id
 *
 * @return bool
 */
function directorist_is_current_user_listing_author( $listing_id = null ) {
	return directorist_is_listing_author( $listing_id, get_current_user_id() );
}

/**
 * Check if the current theme is a block theme.
 *
 * @since 7.2.0
 *
 * @return bool
 */
function directorist_current_theme_is_fse_theme() {
	if ( function_exists( 'wp_is_block_theme' ) ) {
		return (bool) wp_is_block_theme();
	}
	if ( function_exists( 'gutenberg_is_fse_theme' ) ) {
		return (bool) gutenberg_is_fse_theme();
	}

	return false;
}

/**
 * Get the user's favorite listings
 *
 * @since 7.2.0
 * @param int $user_id The user ID of the user whose favorites you want to retrieve.
 *
 * @return array An array of listing IDs.
 */
function directorist_get_user_favorites( $user_id = 0 ) {
	$favorites = get_user_meta( $user_id, 'atbdp_favourites', true );

	if ( ! empty( $favorites ) && is_array( $favorites ) ) {
		$favorites = directorist_prepare_user_favorites( $favorites );
	} else {
		$favorites = array();
	}

	/**
	 * User favorite listings filter hook.
	 *
	 * @since 7.2.0
	 * @param array $favorites
	 * @param int $user_id
	 */
	$favorites = apply_filters( 'directorist_user_favorites', $favorites, $user_id );

	return $favorites;
}

/**
 * This function update the user's favorites
 *
 * @since 7.2.0
 * @param int $user_id The ID of the user whose favorites are being updated.
 * @param int $listing_id The new favorite listing id.
 *
 * @return array
 */
function directorist_add_user_favorites( $user_id = 0, $listing_id = 0 ) {
	if ( get_post_type( $listing_id ) !== ATBDP_POST_TYPE ) {
		return array();
	}

	$old_favorites = directorist_get_user_favorites( $user_id );
	$new_favorites = array_merge( $old_favorites, array( $listing_id ) );
	$new_favorites = directorist_prepare_user_favorites( $new_favorites );

	update_user_meta( $user_id, 'atbdp_favourites', $new_favorites );

	$new_favorites = directorist_get_user_favorites( $user_id );

	/**
	 * Fire after user favorite listings updated.
	 *
	 * @since 7.2.0
	 * @param int $user_id
	 * @param array $new_favorites
	 * @param array $old_favorites
	 */
	do_action( 'directorist_user_favorites_added', $user_id, $new_favorites, $old_favorites );

	return $new_favorites;
}

/**
 * This function deletes a listing from a user's favorites
 *
 * @since 7.2.0
 * @param int $user_id The ID of the user who's favorites are being updated.
 * @param int $listing_id The listing ID that is being deleted from the user's favorites.
 *
 * @return array An array of listing IDs that are favorites for the user.
 */
function directorist_delete_user_favorites( $user_id = 0, $listing_id = 0 ) {
	if ( get_post_type( $listing_id ) !== ATBDP_POST_TYPE ) {
		return array();
	}

	$old_favorites = directorist_get_user_favorites( $user_id );
	$new_favorites = array_filter( $old_favorites, static function( $favorite ) use ( $listing_id ) {
		return ( $favorite !== $listing_id );
	} );

	if ( count( $old_favorites ) > count( $new_favorites ) ) {
		update_user_meta( $user_id, 'atbdp_favourites', $new_favorites );
	}

	/**
	 * Fire after user favorite listings updated.
	 *
	 * @since 7.2.0
	 * @param int $user_id
	 * @param array $new_favorites
	 * @param array $old_favorites
	 */
	do_action( 'directorist_user_favorites_deleted', $user_id, $new_favorites, $old_favorites );

	return $new_favorites;
}

/**
 * Process user favorites listings ids before saving and after retriving.
 *
 * @since 7.2.0
 * @param array $favorites
 * @access private
 *
 * @return array
 */
function directorist_prepare_user_favorites( $favorites = array() ) {
	$favorites = array_values( $favorites );
	$favorites = array_map( 'absint', $favorites );
	$favorites = array_filter( $favorites );
	$favorites = array_unique( $favorites );

	return $favorites;
}

/**
 * Check if email notification is enabled and user can get notification for a specific event.
 *
 * @since 7.2.0
 * @param string $event_name The name of the event.
 * @param string $user_type user or admin
 *
 * @return bool
 */
function directorist_user_notifiable_for( $event_name = '', $user_type = '' ) {
	if ( empty( $event_name ) || get_directorist_option( 'disable_email_notification' ) ) {
		return false;
	}

	if ( empty( $user_type ) || ! in_array( $user_type, array( 'user', 'admin' ), true ) ) {
		return false;
	}

	$user_type  = 'notify_' . (string) $user_type;
	$event_name = (string) $event_name;
	if ( ! in_array( $event_name, get_directorist_option( $user_type, array() ), true ) )  {
		return false;
	}

	return true;
}

/**
 * Check if admin can get email notification for a specific event.
 *
 * @since 7.2.0
 * @param string $event_name The name of the event.
 *
 * @return An array of user IDs.
 */
function directorist_admin_notifiable_for( $event_name = '' ) {
	return directorist_user_notifiable_for( $event_name, 'admin' );
}

/**
 * Check if listing owner can get email notification for a specific event.
 *
 * @since 7.2.0
 * @param string $event_name The name of the event.
 *
 * @return bool
 */
function directorist_owner_notifiable_for( $event_name = '' ) {
	return directorist_user_notifiable_for( $event_name, 'user' );
}

/**
 * This function returns the meta key for the listing views count.
 *
 * @since 7.2.0
 *
 * @return string The meta key for the views count.
 */
function directorist_get_listing_views_count_meta_key() {
	return '_atbdp_post_views_count';
}

/**
 * Get the number of views for a listing.
 *
 * @since 7.2.0
 * @param int $listing_id The ID of the listing.
 *
 * @return int The number of views for a given listing.
 */
function directorist_get_listing_views_count( $listing_id = 0 ) {
	if ( get_post_type( $listing_id ) !== ATBDP_POST_TYPE ) {
		return 0;
	}

	$views_count = get_post_meta( $listing_id, directorist_get_listing_views_count_meta_key(), true );
	return absint( $views_count );
}

/**
 * This function increments the views count of a listing by 1.
 *
 * @since 7.2.0
 * @param int $listing_id The ID of the listing.
 *
 * @return The number of views for a listing.
 */
function directorist_set_listing_views_count( $listing_id = 0 ) {
	if ( get_post_type( $listing_id ) !== ATBDP_POST_TYPE ) {
		return false;
	}

	$views_count = directorist_get_listing_views_count( $listing_id );
	$views_count = $views_count + 1; // Listing got a new view :D
	update_post_meta( $listing_id, directorist_get_listing_views_count_meta_key(), $views_count );

	/**
	 * Fire this hook when listing got a view.
	 *
	 * @since 7.2.0
	 * @param int $listing_id
	 */
	do_action( 'directorist_listing_views_count_updated', $listing_id );

	return true;
}


/**
 * Get listings field key by import file header key.
 * Used in listings import.
 *
 * @param  string $header_key CSV file header key.
 *
 * @return string Listing field key
 */
function directorist_translate_to_listing_field_key( $header_key = '' ) {
	//
    $fields_map = apply_filters( 'directorist_listings_field_label_to_key_map', array(
		'date'                     => 'publish_date',
		'publish_date'             => 'publish_date',
		'Published'                => 'publish_date',
		'status'                   => 'listing_status',
		'listing_status'           => 'listing_status',
		'Status'                   => 'listing_status',
		'name'                     => 'listing_title',
		'title'                    => 'listing_title',
		'Title'                    => 'listing_title',
		'details'                  => 'listing_content',
		'content'                  => 'listing_content',
		'Description'              => 'listing_content',
		'Excerpt'                  => 'excerpt',
		'price'                    => 'price',
		'Price'                    => 'price',
		'price_range'              => 'price_range',
		'Price Range'              => 'price_range',
		'location'                 => 'location',
		'Locations'                => 'location',
		'tag'                      => 'tag',
		'Tags'                     => 'tag',
		'category'                 => 'category',
		'Categories'               => 'category',
		'zip'                      => 'zip',
		'Zip'                      => 'zip',
		'phone'                    => 'phone',
		'Phone'                    => 'phone',
		'phone2'                   => 'phone2',
		'Phone2'                   => 'phone2',
		'fax'                      => 'fax',
		'Fax'                      => 'fax',
		'email'                    => 'email',
		'Email'                    => 'email',
		'website'                  => 'website',
		'Website'                  => 'website',
		'social'                   => 'social',
		'Socials'                  => 'social',
		'atbdp_post_views_count'   => 'atbdp_post_views_count',
		'views_count'              => 'atbdp_post_views_count',
		'Views Count'              => 'atbdp_post_views_count',
		'manual_lat'               => 'manual_lat',
		'Map Latitude'             => 'manual_lat',
		'manual_lng'               => 'manual_lng',
		'Map Logitude'             => 'manual_lng',
		'hide_map'                 => 'hide_map',
		'Hide Map?'                => 'hide_map',
		'hide_contact_info'        => 'hide_contact_owner',
		'Hide Owner Contact Form?' => 'hide_contact_owner',
		'listing_prv_img'          => 'listing_img',
		'preview'                  => 'listing_img',
		'Image'                    => 'listing_img',
		'listing_img'              => 'listing_img',
		'videourl'                 => 'videourl',
		'Video'                    => 'videourl',
		'tagline'                  => 'tagline',
		'Tagline'                  => 'tagline',
		'address'                  => 'address',
		'Address'                  => 'address',
    ) );

    return isset( $fields_map[ $header_key ] ) ? $fields_map[ $header_key ] : '';
}

/**
 * Get data if set, otherwise return a default value or null. Prevents notices when data is not set.
 *
 * @since  7.3.0
 * @param  mixed  $var     Variable.
 * @param  mixed|null $default Default value.
 * @return mixed
 */
function directorist_get_var( &$var, $default = null ) {
	return isset( $var ) ? $var : $default;
}

/**
 * Maybe JSON
 *
 * Converts input to an array if contains valid json string
 *
 * If input contains base64 encoded json string, then it
 * can decode it as well
 *
 * @param $input_data
 * @param $return_first_item
 *
 * Returns first item of the array if $return_first_item is set to true
 * Returns original input if it is not decodable
 *
 * @return mixed
 */
function directorist_maybe_json( $input_data = '', $return_first_item = false ) {
    return directorist_clean( Helper::maybe_json( $input_data, $return_first_item ) );
}

/**
 * Directorist get allowed attributes
 *
 * @return array
 */
function directorist_get_allowed_attributes() {
    $allowed_attributes = array(
        'style'       => array(),
        'class'       => array(),
        'id'          => array(),
        'name'        => array(),
        'rel'         => array(),
        'type'        => array(),
        'href'        => array(),
        'value'       => array(),
        'action'      => array(),
        'selected'    => array(),
		'checked'     => array(),
        'for'         => array(),
        'placeholder' => array(),
        'cols'        => array(),
        'rows'        => array(),
        'maxlength'   => array(),
        'required'    => array(),

        'xmlns'   => array(),
        'width'   => array(),
        'height'  => array(),
        'viewBox' => array(),
        'fill'    => array(),
        'd'       => array(),

		'data-custom-field' => array(),
    );

    return apply_filters( 'directorist_get_allowed_attributes', $allowed_attributes );
}

/**
 * Directorist get allowed form input tags
 *
 * @return array
 */
function directorist_get_allowed_form_input_tags() {
    $allowed_attributes = directorist_get_allowed_attributes();

    return apply_filters( 'directorist_get_allowed_form_input_tags', [
        'input'    => $allowed_attributes,
        'select'   => $allowed_attributes,
        'option'   => $allowed_attributes,
        'textarea' => $allowed_attributes,
    ] );
}

/**
 * Directorist get allowed svg tags
 *
 * @return array
 */
function directorist_get_allowed_svg_tags() {
    $allowed_attributes = directorist_get_allowed_attributes();

    return apply_filters( 'directorist_get_allowed_svg_tags', [
        'svg'  => $allowed_attributes,
        'g'    => $allowed_attributes,
        'path' => $allowed_attributes,
    ] );
}

/**
 * Directorist get allowed HTML tags
 *
 * @return array
 */
function directorist_get_allowed_html() {

    $allowed_attributes = directorist_get_allowed_attributes();

    $allowed_html = array(
        'h1'     => $allowed_attributes,
        'h2'     => $allowed_attributes,
        'h3'     => $allowed_attributes,
        'h4'     => $allowed_attributes,
        'h5'     => $allowed_attributes,
        'h6'     => $allowed_attributes,
        'p'      => $allowed_attributes,
        'a'      => $allowed_attributes,
		'ul'     => $allowed_attributes,
		'li'     => $allowed_attributes,
        'span'   => $allowed_attributes,
        'form'   => $allowed_attributes,
        'div'    => $allowed_attributes,
        'label'  => $allowed_attributes,
        'button' => $allowed_attributes,
    );

    $allowed_html = array_merge(
        $allowed_html,
        directorist_get_allowed_form_input_tags(),
        directorist_get_allowed_svg_tags()
    );

    return apply_filters( 'directorist_get_allowed_html', $allowed_html );
}


/**
 * Directorist KSES
 *
 * Filters text content and strips out disallowed HTML.
 *
 * This function makes sure that only the allowed HTML element names, attribute
 * names, attribute values, and HTML entities will occur in the given text string.
 *
 * This function expects unslashed data.
 *
 * @param string $content
 * @param string $allowed_html
 *
 * @return string
 */
function directorist_kses( $content, $allowed_html = 'all' ) {

    $allowed_html_types = [
        'all'        => directorist_get_allowed_html(),
        'form_input' => directorist_get_allowed_form_input_tags(),
        'svg'        => directorist_get_allowed_svg_tags(),
    ];

    $allowed_html_type = ( in_array( $allowed_html, $allowed_html_types ) ) ? $allowed_html_types[ $allowed_html ] : $allowed_html_types[ 'all' ];

    return wp_kses( $content, $allowed_html_type );
}

/*
 * Safe alternative for $_SERVER['REQUEST_URI'].
 *
 * @since 7.3.1
 * @return string
 */
function directorist_get_request_uri() {
	return empty( $_SERVER['REQUEST_URI'] ) ? home_url( '/' ) : directorist_clean( wp_unslash( $_SERVER['REQUEST_URI'] ) );
}

/**
 * It updates the user profile and meta data
 *
 * @since 7.3.1
 * @param array $data the user data to update.
 * @return bool It returns true on success and false on failure
 */
function directorist_update_profile( $user ) {
	return ATBDP()->user->update_profile( $user );
}

/**
 * Escape JSON for use on HTML or attribute text nodes.
 *
 * @since 7.4.0
 * @param string $json JSON to escape.
 * @param bool   $html True if escaping for HTML text node, false for attributes. Determines how quotes are handled.
 * @return string Escaped JSON.
 */
function directorist_esc_json( $json, $html = false ) {
	return _wp_specialchars(
		$json,
		$html ? ENT_NOQUOTES : ENT_QUOTES, // Escape quotes in attribute nodes only.
		'UTF-8',                           // json_encode() outputs UTF-8 (really just ASCII), not the blog's charset.
		true                               // Double escape entities: `&amp;` -> `&amp;amp;`.
	);
}

/**
 * This image size will be used as the default value of preview image. It can be seen in action
 * on all-listing page's grid view.
 *
 * Custom image size "directorist_preview" is generated based on this size.
 *
 * @since 7.4.2
 * @return array Image size data.
 */
function directorist_default_preview_size() {
	return apply_filters(
		'directorist_default_preview_size', array(
			'width'  => 640,
			'height' => 360,
			'crop'   => true,
		)
	);
}

/**
 *
 * @param $page_name
 * @since 7.5
 * @return int Page ID
 */
function directorist_get_page_id( string $page_name = '' ) : int {

    $page_to_option_map = apply_filters( 'directorist_pages', array(
        'location'      => 'single_location_page',
        'category'      => 'single_category_page',
        'tag'           => 'single_tag_page',
        'form'          => 'add_listing_page',
        'listings'      => 'all_listing_page',
        'dashboard'     => 'user_dashboard',
        'author'        => 'author_profile_page',
        'categories'    => 'all_categories_page',
        'locations'     => 'all_locations_page',
        'registration'  => 'custom_registration',
        'login'         => 'user_login',
        'search'        => 'search_listing',
        'results'       => 'search_result_page',
        'checkout'      => 'checkout_page',
        'receipt'       => 'payment_receipt_page',
        'failed'        => 'transaction_failure_page',
        'privacy'       => 'privacy_policy',
        'terms'         => 'terms_conditions',
    ));

    if ( ! isset( $page_to_option_map[ $page_name ] ) ) {
        return 0;
    }

    $page_id = (int) get_directorist_option( $page_to_option_map[ $page_name ] );

    if ( ! $page_id ) {
        return 0;
    }

    return (int) apply_filters( 'directorist_page_id', $page_id, $page_name );
}

function directorist_password_reset_url( $user, $password_reset = true, $confirm_mail = false) {

    if ( ! $user instanceof \Wp_User ) {
        return;
    }

    $args = array(
        'user' => $user->user_email
    );

    global $directories_user_rest_keys;

    if( is_array( $directories_user_rest_keys ) && ! empty( $directories_user_rest_keys[$user->user_email] ) ) {
        $args['key'] = $directories_user_rest_keys[$user->user_email];
    } else {
        $key                                           = get_password_reset_key( $user );
        $directories_user_rest_keys[$user->user_email] = $key;
        $args['key']                                   = $key;
    }

    if ( $password_reset ) {
        $args['password_reset'] = true;
    }

    if ( $confirm_mail ) {
        $args['confirm_mail'] = true;
    }

    $reset_password_url = ATBDP_Permalink::get_dashboard_page_link( $args );

    return apply_filters( 'directorist_password_reset_url', $reset_password_url );
}

/**
 * Get allowed mime types.
 *
 * @param string $filterby Filter allowed mime types by group. eg. image, audio, video, document etc.
 * @param string $return_type Get the full mime types map or only extensions. Valid args are extension and .extension.
 *
 * @return array
 */
function directorist_get_mime_types( $filterby = '', $return_type = '' ) {
	$allowed_mime_types = get_allowed_mime_types();

	if ( ! empty( $filterby ) ) {
		$allowed_mime_types = array_filter( $allowed_mime_types, static function( $mime_type, $extensions ) use ( $filterby ) {
			return stripos( $mime_type, $filterby ) !== false;
		}, ARRAY_FILTER_USE_BOTH );
	}

	if ( $return_type === 'extension' || $return_type === '.extension' ) {
		$allowed_mime_types = array_reduce( array_keys( $allowed_mime_types ), static function( $carry, $extension ) {
			return array_merge( $carry, explode( '|',  $extension ) );
		}, array() );

		if ( $return_type === '.extension' ) {
			$allowed_mime_types = array_map( static function( $extension ) {
				return '.' . $extension;
			}, $allowed_mime_types );
		}
	}

	return $allowed_mime_types;
}

/**
 * The function checks if email verification is enabled in the settings.
 *
 * @return bool a boolean value indicating whether email verification is enabled or not.
 */
function directorist_is_email_verification_enabled() {
	return (bool) get_directorist_option( 'enable_email_verification' );
}

/**
 * @param int $term_id
 * @param string $taxonomy
 *
 * @return string Term Label
 */
function directorist_get_term_label( $term_id, $taxonomy ) {
	$term = get_term_by( 'term_id', $term_id, $taxonomy );

	if ( false === $term ) {
		return '';
	}

	return $term->name;
}

/**
 * @param mixed $item
 * @return mixed Item
 */
function directorist_sanitize_term_item( $item ) {
	$item = trim( $item );
	return directorist_maybe_number( $item );
}

/**
 * @param mixed $item
 * @return mixed item
 */
function directorist_maybe_number( $item ) {
	if ( ! is_string( $item ) && ! is_numeric( $item )  ) {
		return $item;
	}

	if ( preg_match( "/[^0-9.]/", $item ) ) {
		return $item;
	}

	$item = trim( $item, '. ' );

	if ( false === strpos( $item, '.' ) ) {
		return absint( $item );
	}

	return ( float ) $item;
}

function directorist_generate_password_reset_code_transient_key( $data ) {
	return 'directorist_' . wp_hash( $data );
}

function directorist_set_password_reset_code_transient( $user, $code ) {
	set_transient( directorist_generate_password_reset_code_transient_key( $user->user_email ), $code, MINUTE_IN_SECONDS * 5 );
}

function directorist_get_password_reset_code_transient( $user ) {
	return get_transient( directorist_generate_password_reset_code_transient_key( $user->user_email ) );
}

function directorist_delete_password_reset_code_transient( $user ) {
	delete_transient( directorist_generate_password_reset_code_transient_key( $user->user_email ) );
}

function directorist_generate_password_reset_pin_code( $user ) {
	$password_reset_key = wp_generate_password( 12, false, false );
	$pin_code           = substr( $password_reset_key, 0, 4 );
	$tail_code          = substr( $password_reset_key, 4 );

	directorist_set_password_reset_code_transient( $user, $tail_code );
	update_user_meta( $user->ID, 'directorist_pasword_reset_key', wp_hash_password( $password_reset_key ) );

	return $pin_code;
}

function directorist_check_password_reset_pin_code( $user, $pin_code ) {
	global $wp_hasher;

	$tail_code = directorist_get_password_reset_code_transient( $user );

	if ( empty( $tail_code ) ) {
		return false;
	}

	$reset_key      = $pin_code . $tail_code;
	$reset_key_hash = get_user_meta( $user->ID, 'directorist_pasword_reset_key', true );

	if ( empty( $reset_key_hash ) ) {
		return false;
	}

	/*
	 * If the stored hash is longer than an MD5,
	 * presume the new style phpass portable hash.
	 */
	if ( empty( $wp_hasher ) ) {
		require_once ABSPATH . WPINC . '/class-phpass.php';
		// By default, use the portable hash from phpass.
		$wp_hasher = new PasswordHash( 8, true );
	}

	return $wp_hasher->CheckPassword( $reset_key, $reset_key_hash );
}
function directorist_validate_youtube_vimeo_url( $url ) {
    if ( preg_match( '/^(https?:\/\/)?(www\.)?vimeo\.com\/(\d+)/i', $url ) ) {
        return true;
    }

    if ( preg_match( '/^(https?:\/\/)?(www\.)?youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/i', $url ) ) {
        return true;
    }

    if ( preg_match( '/^https?:\/\/youtu\.be\/([a-zA-Z0-9_-]+)(\?.*)?$/', $url ) ) {
        return true;
    }

	if ( preg_match( '/^(https?:\/\/)?(www\.)?youtube\.com\/shorts\/([A-Za-z0-9_-]+)(\S+)?$/i', $url ) ) {
        return true;
    }

    return false;
}

function directorist_is_listing_post_type( $listing_id ) {
	return ( get_post_type( absint( $listing_id ) ) === ATBDP_POST_TYPE );
}

function directorist_background_image_process( $images ) {
	if ( empty( $images ) || ! is_array( $images ) ) {
		return;
	}

	$should_dispatch = false;

	foreach ( $images as $image_id => $image_path ) {
		if ( empty( $image_id ) || empty( $image_path ) ) {
			continue;
		}

		$should_dispatch = true;

		ATBDP()->background_image_process->push_to_queue( array( $image_id => $image_path ) );
	}

	if ( $should_dispatch ) {
		ATBDP()->background_image_process->save()->dispatch();
	}
}

/**
 * Delete directory even when non empty.
 *
 * @since 7.9.1
 *
 * @param $dir Directory path.
 */
function directorist_delete_dir( $dir ) {
	$objects = scandir( $dir );

	unset( $objects[0], $objects[1] ); // Remove '.' and '..' entries

	foreach ( $objects as $object ) {
		if ( is_dir( $dir . '/' . $object ) ) {
			directorist_delete_dir( $dir . '/' . $object );
		} else {
			unlink( $dir . '/' . $object );
		}
	}

	if ( ! rmdir( $dir ) ) {
		throw new Exception( "Failed to remove directory: $dir" );
	}
}

/**
 * Remove temporary upload directories.
 *
 * @since 7.9.1
 *
 * @return void
 */
function directorist_delete_temporary_upload_dirs() {
	$upload_dir = wp_get_upload_dir();
	$temp_dir   = trailingslashit( $upload_dir['basedir'] ) . 'directorist_temp_uploads/';

	$dirs = scandir( $temp_dir );
	$date = date( 'nj' );

	unset( $dirs[0], $dirs[1] ); // Remove '.' and '..' entries

	foreach ( $dirs as $dir ) {
		// Check if it's a directory and older than current date
		if ( is_dir( $temp_dir . $dir ) && $dir < $date ) {
			try {
				directorist_delete_dir( $temp_dir . $dir );
			} catch ( Exception $e ) {
				error_log( 'Error removing directory: ' . $temp_dir . $dir . ' - ' . $e->getMessage() );
			}
		}
	}
}

/**
 * Formats a given date value according to WordPress settings or provided format.
 *
 * @param string $date The date value to format.
 * @param string $format Optional. The format to use. If empty, uses the WordPress settings.
 * @return string The formatted date string, or an empty string if the input value is empty.
 */
function directorist_format_date( $date = '', $format = '' ) {
    $date = strtotime( $date );
    if ( ! $date ) {
        return '';
    }

    $format = apply_filters( 'directorist_date_format', ( $format ? $format : get_option( 'date_format' ) ) );

    return date( $format, $date );
}

/**
 * Formats a given time value according to WordPress settings or provided format.
 *
 * @param string $time The time value to format.
 * @param string $format Optional. The format to use. If empty, uses the WordPress settings.
 * @return string The formatted time string, or an empty string if the input value is empty.
 */
function directorist_format_time( $time = '', $format = '' ) {
    $time = strtotime( $time );
    if ( ! $time ) {
        return '';
    }

    $format = apply_filters( 'directorist_time_format', ( $format ? $format : get_option( 'time_format' ) ) );

    return date( $format, $time );
}

function directorist_filter_listing_empty_metadata( $meta_data ) {
	return array_filter( $meta_data, static function( $value, $key ) {
		if ( $key === '_hide_contact_owner' && ! $value ) {
			return false;
		}

		if ( is_array( $value ) ) {
			return ! empty( $value );
		}

		if ( is_null( $value ) ) {
			return false;
		}

		if ( is_string( $value ) && $value === '' ) {
			return false;
		}

		if ( is_numeric( $value ) && $value == 0 ) {
			return false;
		}

		return true;
	}, ARRAY_FILTER_USE_BOTH );
}

function directorist_delete_listing_empty_metadata( $listing_id, array $metadata = array(), array $valid_metadata = array() ) {
	$deletable_meta_data = array_diff_key( $metadata, $valid_metadata );
	foreach ( $deletable_meta_data as $deletable_meta_key => $v ) {
		delete_post_meta( $listing_id, $deletable_meta_key );
	}
}
