<?php
// Prohibit direct script loading.
defined('ABSPATH') || die('No direct script access allowed!');

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

if ( ! function_exists( 'atbdp_console_log' ) ) {
    function atbdp_console_log( array $data = [] ) {
        $data = json_encode( $data ); ?>
        <script>
            var data = JSON.parse( '<?php echo $data ?>' );
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
            <div class="atbdp-flush-message-item type-<?php echo $messages['type'] ?>">
                <?php echo $messages['message'] ?>
            </div>
        <?php }
        echo '</div>';

        $contents = apply_filters( 'atbdp_flush_message_content', ob_get_clean(), $flush_messages );
        echo $contents;
    }
}

if ( ! function_exists( 'atbdp_auth_guard' ) ) {
    function atbdp_auth_guard( array $args = [] ) {
        $flush_message = [
            'key'     => 'logged_in_user_only',
            'type'    => 'info',
            'message' => __( 'You need to be logged in to view the content of this page', 'directorist' ),
        ];

        $default = [ 'flush_message' => $flush_message ];
        $args = array_merge( $default, $args );

        global $wp;

        $current_page  = home_url( $wp->request );
        $login_page_id = get_directorist_option( 'user_login' );
        $login_page    = ( ! empty( $login_page_id ) ) ? get_page_link( $login_page_id ) : '';
        $home_page     = home_url();
        $redirect_link = ( ! empty( $login_page ) ) ? $login_page : $home_page;

        atbdp_add_flush_message( $args['flush_message'] );

        atbdp_redirect_after_login( [ 'url' => $current_page ] );
        wp_redirect( $redirect_link );

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
    <div class="<?php echo $classes; ?>">
        <p><strong><?php echo $alert['message'] ?></strong></p>
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
    $edit_l_status  = $args['edit_l_status'];
    $edited         = $args['edited'];
    $listing_status = ( true === $edited || 'yes' === $edited || '1' === $edited ) ? $edit_l_status : $new_l_status;

    $monitization          = get_directorist_option('enable_monetization', 0);
    $featured_enabled      = get_directorist_option('enable_featured_listing');
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
                in_array($file_to_load, $found_files) ? require_once $dir . $file_to_load : null;
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


if (!function_exists('atbd_get_related_posts')) {
    // get related post based on tags or categories
    function atbd_get_related_posts()
    {
        global $post;
        // get all tags assigned to current post
        $tags = wp_get_post_tags($post->ID);
        $args = array();
        // set args to get related posts based on tags
        if (!empty($tags)) {
            $tag_ids = array();
            foreach ($tags as $tag) $tag_ids[] = $tag->term_id;
            $args = array(
                'tag__in' => $tag_ids,
                'post__not_in' => array($post->ID),
                'ignore_sticky_posts' => true,
                'posts_per_page' => 5,
                'orderby' => 'rand',
            );
        } else {
            // get all cats assigned to current post
            $cats = get_the_category($post->ID);
            // set the args to get all related posts based on category.
            if ($cats) {
                $cat_ids = array();
                foreach ($cats as $cat) $cat_ids[] = $cat->term_id;
                $args = array(
                    'category__in' => $cat_ids,
                    'post__not_in' => array($post->ID),
                    'ignore_sticky_posts' => true,
                    'posts_per_page' => 5,
                    'orderby' => 'rand',
                );
            }
        }
        if (!empty($args)) {
            // build the markup and return
            return new WP_Query($args);

        }
        return null;
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
            'prev_text' => apply_filters('atbdp_pagination_prev_text', '<span class="fa fa-chevron-left"></span>'),
            'next_text' => apply_filters('atbdp_pagination_next_text', '<span class="fa fa-chevron-right atbdp_right_nav"></span>'),
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

if ( ! function_exists('get_fa_icons') ) {
    function get_fa_icons()
    {
        $font_type = get_directorist_option('font_type', 'line');
        $iconsLA = array( 'accusoft','adn','adobe','adversal','affiliatetheme','airbnb','algolia','amazon','amilia','android','angellist','angrycreative','angular','app-store','app-store-ios','apper','apple','artstation','asymmetrik','atlassian','audible','autoprefixer','avianex','aviato','aws','bandcamp','battle-net','behance','behance-square','bimobject','bitbucket','bity','black-tie','blackberry','blogger','blogger-b','bootstrap','buffer','buromobelexperte','buy-n-large','buysellads','canadian-maple-leaf','centercode','centos','chrome','chromecast','cloudscale','cloudsmith','cloudversify','codepen','codiepie','confluence','connectdevelop','contao','cotton-bureau','cpanel','creative-commons','creative-commons-by','creative-commons-nc','creative-commons-nc-eu','creative-commons-nc-jp','creative-commons-nd','creative-commons-pd','creative-commons-pd-alt','creative-commons-remix','creative-commons-sa','creative-commons-sampling','creative-commons-sampling-plus','creative-commons-share','creative-commons-zero','css3','css3-alt','cuttlefish','dashcube','delicious','deploydog','deskpro','dev','deviantart','dhl','diaspora','digg','digital-ocean','discord','discourse','dochub','docker','draft2digital','dribbble','dribbble-square','dropbox','drupal','dyalog','earlybirds','ebay','edge','elementor','ello','ember','empire','envira','erlang','etsy','evernote','expeditedssl','facebook','facebook-f','facebook-messenger','facebook-square','fedex','fedora','figma','firefox','first-order','first-order-alt','firstdraft','flickr','flipboard','fly','font-awesome','font-awesome-alt','font-awesome-flag','fonticons','fonticons-fi','fort-awesome','fort-awesome-alt','forumbee','foursquare','free-code-camp','freebsd','fulcrum','get-pocket','git','git-alt','git-square','github','github-alt','github-square','gitkraken','gitlab','gitter','glide','glide-g','gofore','goodreads','goodreads-g','google','google-drive','google-play','google-plus','google-plus-g','google-plus-square','gratipay','grav','gripfire','grunt','gulp','hacker-news','hacker-news-square','hackerrank','hips','hire-a-helper','hooli','hornbill','hotjar','houzz','html5','hubspot','imdb','instagram','intercom','internet-explorer','invision','ioxhost','itch-io','itunes','itunes-note','java','jenkins','jira','joget','joomla','js','js-square','jsfiddle','kaggle','keybase','keycdn','kickstarter','kickstarter-k','korvue','laravel','lastfm','lastfm-square','leanpub','less','line','linkedin','linkedin-in','linode','linux','lyft','magento','mailchimp','mandalorian','markdown','mastodon','maxcdn','mdb','medapps','medium','medium-m','medrt','meetup','megaport','mendeley','microsoft','mix','mixcloud','mizuni','modx','monero','neos','nimblr','node','node-js','npm','ns8','nutritionix','odnoklassniki','odnoklassniki-square','opencart','openid','opera','optin-monster','orcid','osi','page4','pagelines','palfed','patreon','periscope','phabricator','phoenix-framework','phoenix-squadron','php','pied-piper','pied-piper-alt','pied-piper-hat','pied-piper-pp','pinterest','pinterest-p','pinterest-square','product-hunt','pushed','python','qq','quinscape','quora','r-project','raspberry-pi','ravelry','react','reacteurope','readme','rebel','red-river','reddit','reddit-alien','reddit-square','redhat','renren','replyd','researchgate','resolving','rev','rocketchat','rockrms','safari','salesforce','sass','schlix','scribd','searchengin','sellcast','sellsy','servicestack','shirtsinbulk','shopware','simplybuilt','sistrix','sith','sketch','skyatlas','skype','slack','slack-hash','slideshare','snapchat','snapchat-ghost','snapchat-square','sourcetree','speakap','speaker-deck','squarespace','stack-exchange','stack-overflow','stackpath','staylinked','sticker-mule','strava','studiovinari','stumbleupon','stumbleupon-circle','superpowers','supple','suse','swift','symfony','teamspeak','telegram','telegram-plane','tencent-weibo','the-red-yeti','themeco','themeisle','think-peaks','trade-federation','trello','tripadvisor','tumblr','tumblr-square','twitter','twitter-square','typo3','uber','ubuntu','uikit','umbraco','uniregistry','untappd','ups','usb','usps','ussunnah','vaadin','viacoin','viadeo','viadeo-square','viber','vimeo','vimeo-square','vimeo-v','vine','vk','vnv','vuejs','waze','weebly','weibo','weixin','whatsapp','whatsapp-square','whmcs','wikipedia-w','windows','wix','wolf-pack-battalion','wordpress','wordpress-simple','wpbeginner','wpexplorer','wpforms','wpressr','xing','xing-square','y-combinator','yahoo','yammer','yandex','yandex-international','yarn','yelp','yoast','youtube-square','zhihu');

        $iconsFA = array( 'accessible-icon','american-sign-language-interpreting','assistive-listening-systems','audio-description','blind','braille','closed-captioning','deaf','low-vision','phone-volume','question-circle','sign-language','tty','universal-access','wheelchair','bell','bell-slash','exclamation','exclamation-circle','exclamation-triangle','radiation','radiation-alt','skull-crossbones','cat','crow','dog','dove','dragon','feather','feather-alt','fish','frog','hippo','horse','horse-head','kiwi-bird','otter','paw','spider','angle-double-down','angle-double-left','angle-double-right','angle-double-up','angle-down','angle-left','angle-right','angle-up','arrow-alt-circle-down','arrow-alt-circle-left','arrow-alt-circle-right','arrow-alt-circle-up','arrow-circle-down','arrow-circle-left','arrow-circle-right','arrow-circle-up','arrow-down','arrow-left','arrow-right','arrow-up','arrows-alt','arrows-alt-h','arrows-alt-v','caret-down','caret-left','caret-right','caret-square-down','caret-square-left','caret-square-right','caret-square-up','caret-up','cart-arrow-down','chart-line','chevron-circle-down','chevron-circle-left','chevron-circle-right','chevron-circle-up','chevron-down','chevron-left','chevron-right','chevron-up','cloud-download-alt','cloud-upload-alt','compress-arrows-alt','download','exchange-alt','expand-arrows-alt','external-link-alt','external-link-square-alt','hand-point-down','hand-point-left','hand-point-right','hand-point-up','hand-pointer','history','level-down-alt','level-up-alt','location-arrow','long-arrow-alt-down','long-arrow-alt-left','long-arrow-alt-right','long-arrow-alt-up','mouse-pointer','play','random','recycle','redo','redo-alt','reply','reply-all','retweet','share','share-square','sign-in-alt','sign-out-alt','sort','sort-alpha-down','sort-alpha-down-alt','sort-alpha-up','sort-alpha-up-alt','sort-amount-down','sort-amount-down-alt','sort-amount-up','sort-amount-up-alt','sort-down','sort-numeric-down','sort-numeric-down-alt','sort-numeric-up','sort-numeric-up-alt','sort-up','sync','sync-alt','text-height','text-width','undo','undo-alt','upload','audio-description','backward','broadcast-tower','circle','closed-captioning','compress','compress-arrows-alt','eject','expand','expand-arrows-alt','fast-backward','fast-forward','file-audio','file-video','film','forward','headphones','microphone','microphone-alt','microphone-alt-slash','microphone-slash','music','pause','pause-circle','phone-volume','photo-video','play','play-circle','podcast','random','redo','redo-alt','rss','rss-square','step-backward','step-forward','stop','stop-circle','sync','sync-alt','tv','undo','undo-alt','video','volume-down','volume-mute','volume-off','volume-up','youtube','air-freshener','ambulance','bus','bus-alt','car','car-alt','car-battery','car-crash','car-side','charging-station','gas-pump','motorcycle','oil-can','shuttle-van','tachometer-alt','taxi','truck','truck-monster','truck-pickup','apple-alt','campground','cloud-sun','drumstick-bite','football-ball','hiking','mountain','tractor','tree','wind','wine-bottle','beer','blender','cocktail','coffee','flask','glass-cheers','glass-martini','glass-martini-alt','glass-whiskey','mug-hot','wine-bottle','wine-glass','wine-glass-alt','archway','building','campground','church','city','clinic-medical','dungeon','gopuram','home','hospital','hospital-alt','hotel','house-damage','igloo','industry','kaaba','landmark','monument','mosque','place-of-worship','school','store','store-alt','synagogue','torii-gate','university','vihara','warehouse','address-book','address-card','archive','balance-scale','balance-scale-left','balance-scale-right','birthday-cake','book','briefcase','building','bullhorn','bullseye','business-time','calculator','calendar','calendar-alt','certificate','chart-area','chart-bar','chart-line','chart-pie','city','clipboard','coffee','columns','compass','copy','copyright','cut','edit','envelope','envelope-open','envelope-square','eraser','fax','file','file-alt','folder','folder-minus','folder-open','folder-plus','glasses','globe','highlighter','industry','landmark','marker','paperclip','paste','pen','pen-alt','pen-fancy','pen-nib','pen-square','pencil-alt','percent','phone','phone-alt','phone-slash','phone-square','phone-square-alt','phone-volume','print','project-diagram','registered','save','sitemap','socks','sticky-note','stream','table','tag','tags','tasks','thumbtack','trademark','wallet','binoculars','campground','compass','fire','fire-alt','first-aid','frog','hiking','map','map-marked','map-marked-alt','map-signs','mountain','route','toilet-paper','tree','dollar-sign','donate','dove','gift','globe','hand-holding-heart','hand-holding-usd','hands-helping','handshake','heart','leaf','parachute-box','piggy-bank','ribbon','seedling','comment','comment-alt','comment-dots','comment-medical','comment-slash','comments','frown','icons','meh','phone','phone-alt','phone-slash','poo','quote-left','quote-right','smile','sms','video','video-slash','chess','chess-bishop','chess-board','chess-king','chess-knight','chess-pawn','chess-queen','chess-rook','square-full','apple-alt','baby','baby-carriage','bath','biking','birthday-cake','cookie','cookie-bite','gamepad','ice-cream','mitten','robot','school','shapes','snowman','graduation-cap','hat-cowboy','hat-cowboy-side','hat-wizard','mitten','shoe-prints','socks','tshirt','user-tie','archive','barcode','bath','bug','code','code-branch','coffee','file','file-alt','file-code','filter','fire-extinguisher','folder','folder-open','keyboard','laptop-code','microchip','project-diagram','qrcode','shield-alt','sitemap','stream','terminal','user-secret','window-close','window-maximize','window-minimize','window-restore','address-book','address-card','american-sign-language-interpreting','assistive-listening-systems','at','bell','bell-slash','bluetooth','bluetooth-b','broadcast-tower','bullhorn','chalkboard','comment','comment-alt','comments','envelope','envelope-open','envelope-square','fax','inbox','language','microphone','microphone-alt','microphone-alt-slash','microphone-slash','mobile','mobile-alt','paper-plane','phone','phone-alt','phone-slash','phone-square','phone-square-alt','phone-volume','rss','rss-square','tty','voicemail','wifi','database','desktop','download','ethernet','hdd','headphones','keyboard','laptop','memory','microchip','mobile','mobile-alt','mouse','plug','power-off','print','satellite','satellite-dish','save','sd-card','server','sim-card','stream','tablet','tablet-alt','tv','upload','brush','drafting-compass','dumpster','hammer','hard-hat','paint-roller','pencil-alt','pencil-ruler','ruler','ruler-combined','ruler-horizontal','ruler-vertical','screwdriver','toolbox','tools','truck-pickup','wrench','bitcoin','btc','dollar-sign','ethereum','euro-sign','gg','gg-circle','hryvnia','lira-sign','money-bill','money-bill-alt','money-bill-wave','money-bill-wave-alt','money-check','money-check-alt','pound-sign','ruble-sign','rupee-sign','shekel-sign','tenge','won-sign','yen-sign','bell','bell-slash','calendar','calendar-alt','calendar-check','calendar-minus','calendar-plus','calendar-times','clock','hourglass','hourglass-end','hourglass-half','hourglass-start','stopwatch','adjust','bezier-curve','brush','clone','copy','crop','crop-alt','crosshairs','cut','drafting-compass','draw-polygon','edit','eraser','eye','eye-dropper','eye-slash','fill','fill-drip','highlighter','icons','layer-group','magic','marker','object-group','object-ungroup','paint-brush','paint-roller','palette','paste','pen','pen-alt','pen-fancy','pen-nib','pencil-alt','pencil-ruler','ruler-combined','ruler-horizontal','ruler-vertical','save','splotch','spray-can','stamp','swatchbook','tint','tint-slash','vector-square','align-center','align-justify','align-left','align-right','bold','border-all','border-none','border-style','clipboard','clone','columns','copy','cut','edit','eraser','file','file-alt','font','glasses','heading','highlighter','i-cursor','icons','indent','italic','link','list','list-alt','list-ol','list-ul','marker','outdent','paper-plane','paperclip','paragraph','paste','pen','pen-alt','pen-fancy','pen-nib','pencil-alt','print','quote-left','quote-right','redo','redo-alt','remove-format','reply','reply-all','screwdriver','share','spell-check','strikethrough','subscript','superscript','sync','sync-alt','table','tasks','text-height','text-width','th','th-large','th-list','tools','trash','trash-alt','trash-restore','trash-restore-alt','underline','undo','undo-alt','unlink','wrench','apple-alt','atom','award','bell','bell-slash','book-open','book-reader','chalkboard','chalkboard-teacher','graduation-cap','laptop-code','microscope','music','school','shapes','theater-masks','user-graduate','angry','dizzy','flushed','frown','frown-open','grimace','grin','grin-alt','grin-beam','grin-beam-sweat','grin-hearts','grin-squint','grin-squint-tears','grin-stars','grin-tears','grin-tongue','grin-tongue-squint','grin-tongue-wink','grin-wink','kiss','kiss-beam','kiss-wink-heart','laugh','laugh-beam','laugh-squint','laugh-wink','meh','meh-blank','meh-rolling-eyes','sad-cry','sad-tear','smile','smile-beam','smile-wink','surprise','tired','atom','battery-empty','battery-full','battery-half','battery-quarter','battery-three-quarters','broadcast-tower','burn','charging-station','fire','fire-alt','gas-pump','industry','leaf','lightbulb','plug','poop','power-off','radiation','radiation-alt','seedling','solar-panel','sun','water','wind','archive','clone','copy','cut','file','file-alt','file-archive','file-audio','file-code','file-excel','file-image','file-pdf','file-powerpoint','file-video','file-word','folder','folder-open','paste','photo-video','save','sticky-note','balance-scale','balance-scale-left','balance-scale-right','book','cash-register','chart-line','chart-pie','coins','comment-dollar','comments-dollar','credit-card','donate','file-invoice','file-invoice-dollar','hand-holding-usd','landmark','money-bill','money-bill-alt','money-bill-wave','money-bill-wave-alt','money-check','money-check-alt','percentage','piggy-bank','receipt','stamp','wallet','bicycle','biking','burn','fire-alt','heart','heartbeat','hiking','running','shoe-prints','skating','skiing','skiing-nordic','snowboarding','spa','swimmer','walking','apple-alt','bacon','bone','bread-slice','candy-cane','carrot','cheese','cloud-meatball','cookie','drumstick-bite','egg','fish','hamburger','hotdog','ice-cream','lemon','pepper-hot','pizza-slice','seedling','stroopwafel','chess','chess-bishop','chess-board','chess-king','chess-knight','chess-pawn','chess-queen','chess-rook','dice','dice-d20','dice-d6','dice-five','dice-four','dice-one','dice-six','dice-three','dice-two','gamepad','ghost','headset','heart','playstation','puzzle-piece','steam','steam-square','steam-symbol','twitch','xbox','genderless','mars','mars-double','mars-stroke','mars-stroke-h','mars-stroke-v','mercury','neuter','transgender','transgender-alt','venus','venus-double','venus-mars','book-dead','broom','cat','cloud-moon','crow','ghost','hat-wizard','mask','skull-crossbones','spider','toilet-paper','allergies','fist-raised','hand-holding','hand-holding-heart','hand-holding-usd','hand-lizard','hand-middle-finger','hand-paper','hand-peace','hand-point-down','hand-point-left','hand-point-right','hand-point-up','hand-pointer','hand-rock','hand-scissors','hand-spock','hands','hands-helping','handshake','praying-hands','thumbs-down','thumbs-up','accessible-icon','ambulance','h-square','heart','heartbeat','hospital','medkit','plus-square','prescription','stethoscope','user-md','wheelchair','candy-cane','carrot','cookie-bite','gift','gifts','glass-cheers','holly-berry','mug-hot','sleigh','snowman','baby-carriage','bath','bed','briefcase','car','cocktail','coffee','concierge-bell','dice','dice-five','door-closed','door-open','dumbbell','glass-martini','glass-martini-alt','hot-tub','hotel','infinity','key','luggage-cart','shower','shuttle-van','smoking','smoking-ban','snowflake','spa','suitcase','suitcase-rolling','swimmer','swimming-pool','tv','umbrella-beach','utensils','wheelchair','wifi','bath','bed','blender','chair','couch','door-closed','door-open','dungeon','fan','shower','toilet-paper','tv','adjust','bolt','camera','camera-retro','chalkboard','clone','compress','compress-arrows-alt','expand','eye','eye-dropper','eye-slash','file-image','film','id-badge','id-card','image','images','photo-video','portrait','sliders-h','tint','award','ban','barcode','bars','beer','bell','bell-slash','blog','bug','bullhorn','bullseye','calculator','calendar','calendar-alt','calendar-check','calendar-minus','calendar-plus','calendar-times','certificate','check','check-circle','check-double','check-square','circle','clipboard','clone','cloud','cloud-download-alt','cloud-upload-alt','coffee','cog','cogs','copy','cut','database','dot-circle','download','edit','ellipsis-h','ellipsis-v','envelope','envelope-open','eraser','exclamation','exclamation-circle','exclamation-triangle','external-link-alt','external-link-square-alt','eye','eye-slash','file','file-alt','file-download','file-export','file-import','file-upload','filter','fingerprint','flag','flag-checkered','folder','folder-open','frown','glasses','grip-horizontal','grip-lines','grip-lines-vertical','grip-vertical','hashtag','heart','history','home','i-cursor','info','info-circle','language','magic','marker','medal','meh','microphone','microphone-alt','microphone-slash','minus','minus-circle','minus-square','paste','pen','pen-alt','pen-fancy','pencil-alt','plus','plus-circle','plus-square','poo','qrcode','question','question-circle','quote-left','quote-right','redo','redo-alt','reply','reply-all','rss','rss-square','save','screwdriver','search','search-minus','search-plus','share','share-alt','share-alt-square','share-square','shield-alt','sign-in-alt','sign-out-alt','signal','sitemap','sliders-h','smile','sort','sort-alpha-down','sort-alpha-down-alt','sort-alpha-up','sort-alpha-up-alt','sort-amount-down','sort-amount-down-alt','sort-amount-up','sort-amount-up-alt','sort-down','sort-numeric-down','sort-numeric-down-alt','sort-numeric-up','sort-numeric-up-alt','sort-up','star','star-half','sync','sync-alt','thumbs-down','thumbs-up','times','times-circle','toggle-off','toggle-on','tools','trash','trash-alt','trash-restore','trash-restore-alt','trophy','undo','undo-alt','upload','user','user-alt','user-circle','volume-down','volume-mute','volume-off','volume-up','wifi','wrench','box','boxes','clipboard-check','clipboard-list','dolly','dolly-flatbed','hard-hat','pallet','shipping-fast','truck','warehouse','ambulance','anchor','balance-scale','balance-scale-left','balance-scale-right','bath','bed','beer','bell','bell-slash','bicycle','binoculars','birthday-cake','blind','bomb','book','bookmark','briefcase','building','car','coffee','crosshairs','directions','dollar-sign','draw-polygon','eye','eye-slash','fighter-jet','fire','fire-alt','fire-extinguisher','flag','flag-checkered','flask','gamepad','gavel','gift','glass-martini','globe','graduation-cap','h-square','heart','heartbeat','helicopter','home','hospital','image','images','industry','info','info-circle','key','landmark','layer-group','leaf','lemon','life-ring','lightbulb','location-arrow','low-vision','magnet','male','map','map-marker','map-marker-alt','map-pin','map-signs','medkit','money-bill','money-bill-alt','motorcycle','music','newspaper','parking','paw','phone','phone-alt','phone-square','phone-square-alt','phone-volume','plane','plug','plus','plus-square','print','recycle','restroom','road','rocket','route','search','search-minus','search-plus','ship','shoe-prints','shopping-bag','shopping-basket','shopping-cart','shower','snowplow','street-view','subway','suitcase','tag','tags','taxi','thumbtack','ticket-alt','tint','traffic-light','train','tram','tree','trophy','truck','tty','umbrella','university','utensil-spoon','utensils','wheelchair','wifi','wine-glass','wrench','anchor','binoculars','compass','dharmachakra','frog','ship','skull-crossbones','swimmer','water','wind','ad','bullhorn','bullseye','comment-dollar','comments-dollar','envelope-open-text','funnel-dollar','lightbulb','mail-bulk','poll','poll-h','search-dollar','search-location','calculator','divide','equals','greater-than','greater-than-equal','infinity','less-than','less-than-equal','minus','not-equal','percentage','plus','square-root-alt','subscript','superscript','times','wave-square','allergies','ambulance','band-aid','biohazard','bone','bong','book-medical','brain','briefcase-medical','burn','cannabis','capsules','clinic-medical','comment-medical','crutch','diagnoses','dna','file-medical','file-medical-alt','file-prescription','first-aid','heart','heartbeat','hospital','hospital-alt','hospital-symbol','id-card-alt','joint','laptop-medical','microscope','mortar-pestle','notes-medical','pager','pills','plus','poop','prescription','prescription-bottle','prescription-bottle-alt','procedures','radiation','radiation-alt','smoking','smoking-ban','star-of-life','stethoscope','syringe','tablets','teeth','teeth-open','thermometer','tooth','user-md','user-nurse','vial','vials','weight','x-ray','archive','box-open','couch','dolly','people-carry','route','sign','suitcase','tape','truck-loading','truck-moving','wine-glass','drum','drum-steelpan','file-audio','guitar','headphones','headphones-alt','microphone','microphone-alt','microphone-alt-slash','microphone-slash','music','napster','play','record-vinyl','sliders-h','soundcloud','spotify','volume-down','volume-mute','volume-off','volume-up','ambulance','anchor','archive','award','baby-carriage','balance-scale','balance-scale-left','balance-scale-right','bath','bed','beer','bell','bicycle','binoculars','birthday-cake','blender','bomb','book','book-dead','bookmark','briefcase','broadcast-tower','bug','building','bullhorn','bullseye','bus','calculator','calendar','calendar-alt','camera','camera-retro','candy-cane','car','carrot','church','clipboard','cloud','coffee','cog','cogs','compass','cookie','cookie-bite','copy','cube','cubes','cut','dice','dice-d20','dice-d6','dice-five','dice-four','dice-one','dice-six','dice-three','dice-two','digital-tachograph','door-closed','door-open','drum','drum-steelpan','envelope','envelope-open','eraser','eye','eye-dropper','fax','feather','feather-alt','fighter-jet','file','file-alt','file-prescription','film','fire','fire-alt','fire-extinguisher','flag','flag-checkered','flask','futbol','gamepad','gavel','gem','gift','gifts','glass-cheers','glass-martini','glass-whiskey','glasses','globe','graduation-cap','guitar','hat-wizard','hdd','headphones','headphones-alt','headset','heart','heart-broken','helicopter','highlighter','holly-berry','home','hospital','hourglass','igloo','image','images','industry','key','keyboard','laptop','leaf','lemon','life-ring','lightbulb','lock','lock-open','magic','magnet','map','map-marker','map-marker-alt','map-pin','map-signs','marker','medal','medkit','memory','microchip','microphone','microphone-alt','mitten','mobile','mobile-alt','money-bill','money-bill-alt','money-check','money-check-alt','moon','motorcycle','mug-hot','newspaper','paint-brush','paper-plane','paperclip','paste','paw','pen','pen-alt','pen-fancy','pen-nib','pencil-alt','phone','phone-alt','plane','plug','print','puzzle-piece','ring','road','rocket','ruler-combined','ruler-horizontal','ruler-vertical','satellite','satellite-dish','save','school','screwdriver','scroll','sd-card','search','shield-alt','shopping-bag','shopping-basket','shopping-cart','shower','sim-card','skull-crossbones','sleigh','snowflake','snowplow','space-shuttle','star','sticky-note','stopwatch','stroopwafel','subway','suitcase','sun','tablet','tablet-alt','tachometer-alt','tag','tags','taxi','thumbtack','ticket-alt','toilet','toolbox','tools','train','tram','trash','trash-alt','tree','trophy','truck','tv','umbrella','university','unlock','unlock-alt','utensil-spoon','utensils','wallet','weight','wheelchair','wine-glass','wrench','500px','accusoft','adn','adobe','adversal','affiliatetheme','airbnb','algolia','amazon','amilia','android','angellist','angrycreative','angular','app-store','app-store-ios','apper','apple','artstation','asymmetrik','atlassian','audible','autoprefixer','avianex','aviato','aws','backspace','bandcamp','battle-net','behance','behance-square','bimobject','bitbucket','bity','black-tie','blackberry','blender-phone','blogger','blogger-b','bootstrap','buffer','buromobelexperte','buy-n-large','buysellads','canadian-maple-leaf','centercode','centos','chrome','chromecast','cloudscale','cloudsmith','cloudversify','codepen','codiepie','confluence','connectdevelop','contao','cotton-bureau','cpanel','creative-commons','creative-commons-by','creative-commons-nc','creative-commons-nc-eu','creative-commons-nc-jp','creative-commons-nd','creative-commons-pd','creative-commons-pd-alt','creative-commons-remix','creative-commons-sa','creative-commons-sampling','creative-commons-sampling-plus','creative-commons-share','creative-commons-zero','crown','css3','css3-alt','cuttlefish','dashcube','delicious','deploydog','deskpro','dev','deviantart','dhl','diaspora','digg','digital-ocean','discord','discourse','dochub','docker','draft2digital','dribbble','dribbble-square','dropbox','drupal','dumpster-fire','dyalog','earlybirds','ebay','edge','elementor','ello','ember','empire','envira','erlang','etsy','evernote','expeditedssl','facebook','facebook-f','facebook-messenger','facebook-square','fedex','fedora','figma','file-csv','firefox','first-order','first-order-alt','firstdraft','flickr','flipboard','fly','font-awesome','font-awesome-alt','font-awesome-flag','fonticons','fonticons-fi','fort-awesome','fort-awesome-alt','forumbee','foursquare','free-code-camp','freebsd','fulcrum','get-pocket','git','git-alt','git-square','github','github-alt','github-square','gitkraken','gitlab','gitter','glide','glide-g','gofore','goodreads','goodreads-g','google','google-drive','google-play','google-plus','google-plus-g','google-plus-square','gratipay','grav','gripfire','grunt','gulp','hacker-news','hacker-news-square','hackerrank','hips','hire-a-helper','hooli','hornbill','hotjar','houzz','html5','hubspot','imdb','instagram','intercom','internet-explorer','invision','ioxhost','itch-io','itunes','itunes-note','java','jenkins','jira','joget','joomla','js','js-square','jsfiddle','kaggle','keybase','keycdn','kickstarter','kickstarter-k','korvue','laravel','lastfm','lastfm-square','leanpub','less','line','linkedin','linkedin-in','linode','linux','lyft','magento','mailchimp','mandalorian','markdown','mastodon','maxcdn','mdb','medapps','medium','medium-m','medrt','meetup','megaport','mendeley','microsoft','mix','mixcloud','mizuni','modx','monero','neos','network-wired','nimblr','node','node-js','npm','ns8','nutritionix','odnoklassniki','odnoklassniki-square','opencart','openid','opera','optin-monster','orcid','osi','page4','pagelines','palfed','patreon','periscope','phabricator','phoenix-framework','phoenix-squadron','php','pied-piper','pied-piper-alt','pied-piper-hat','pied-piper-pp','pinterest','pinterest-p','pinterest-square','product-hunt','pushed','python','qq','quinscape','quora','r-project','raspberry-pi','ravelry','react','reacteurope','readme','rebel','red-river','reddit','reddit-alien','reddit-square','redhat','renren','replyd','researchgate','resolving','rev','rocketchat','rockrms','safari','salesforce','sass','schlix','scribd','searchengin','sellcast','sellsy','servicestack','shirtsinbulk','shopware','signature','simplybuilt','sistrix','sith','sketch','skull','skyatlas','skype','slack','slack-hash','slideshare','snapchat','snapchat-ghost','snapchat-square','sourcetree','speakap','speaker-deck','squarespace','stack-exchange','stack-overflow','stackpath','staylinked','sticker-mule','strava','studiovinari','stumbleupon','stumbleupon-circle','superpowers','supple','suse','swift','symfony','teamspeak','telegram','telegram-plane','tencent-weibo','the-red-yeti','themeco','themeisle','think-peaks','trade-federation','trello','tripadvisor','tumblr','tumblr-square','twitter','twitter-square','typo3','uber','ubuntu','uikit','umbraco','uniregistry','untappd','ups','usb','usps','ussunnah','vaadin','viacoin','viadeo','viadeo-square','viber','vimeo','vimeo-square','vimeo-v','vine','vk','vnv','vr-cardboard','vuejs','waze','weebly','weibo','weight-hanging','weixin','whatsapp','whatsapp-square','whmcs','wikipedia-w','windows','wix','wolf-pack-battalion','wordpress','wordpress-simple','wpbeginner','wpexplorer','wpforms','wpressr','xing','xing-square','y-combinator','yahoo','yammer','yandex','yandex-international','yarn','yelp','yoast','youtube-square','zhihu','alipay','amazon-pay','apple-pay','bell','bitcoin','bookmark','btc','bullhorn','camera','camera-retro','cart-arrow-down','cart-plus','cc-amazon-pay','cc-amex','cc-apple-pay','cc-diners-club','cc-discover','cc-jcb','cc-mastercard','cc-paypal','cc-stripe','cc-visa','certificate','credit-card','ethereum','gem','gift','google-wallet','handshake','heart','key','money-check','money-check-alt','paypal','receipt','shopping-bag','shopping-basket','shopping-cart','star','stripe','stripe-s','tag','tags','thumbs-down','thumbs-up','trophy','award','balance-scale','balance-scale-left','balance-scale-right','bullhorn','check-double','democrat','donate','dove','fist-raised','flag-usa','handshake','person-booth','piggy-bank','republican','vote-yea','ankh','atom','bible','church','cross','dharmachakra','dove','gopuram','hamsa','hanukiah','haykal','jedi','journal-whills','kaaba','khanda','menorah','mosque','om','pastafarianism','peace','place-of-worship','pray','praying-hands','quran','star-and-crescent','star-of-david','synagogue','torah','torii-gate','vihara','yin-yang','atom','biohazard','brain','burn','capsules','clipboard-check','dna','eye-dropper','filter','fire','fire-alt','flask','frog','magnet','microscope','mortar-pestle','pills','prescription-bottle','radiation','radiation-alt','seedling','skull-crossbones','syringe','tablets','temperature-high','temperature-low','vial','vials','galactic-republic','galactic-senate','globe','jedi','jedi-order','journal-whills','meteor','moon','old-republic','robot','rocket','satellite','satellite-dish','space-shuttle','user-astronaut','ban','bug','door-closed','door-open','dungeon','eye','eye-slash','file-contract','file-signature','fingerprint','id-badge','id-card','id-card-alt','key','lock','lock-open','mask','passport','shield-alt','unlock','unlock-alt','user-lock','user-secret','user-shield','bookmark','calendar','certificate','circle','cloud','comment','file','folder','heart','heart-broken','map-marker','play','shapes','square','star','bell','birthday-cake','camera','comment','comment-alt','envelope','hashtag','heart','icons','image','images','map-marker','map-marker-alt','photo-video','poll','poll-h','retweet','share','share-alt','share-square','star','thumbs-down','thumbs-up','thumbtack','user','user-circle','user-friends','user-plus','users','video','asterisk','atom','certificate','circle-notch','cog','compact-disc','compass','crosshairs','dharmachakra','fan','haykal','life-ring','palette','ring','slash','snowflake','spinner','stroopwafel','sun','sync','sync-alt','yin-yang','baseball-ball','basketball-ball','biking','bowling-ball','dumbbell','football-ball','futbol','golf-ball','hockey-puck','quidditch','running','skating','skiing','skiing-nordic','snowboarding','swimmer','table-tennis','volleyball-ball','allergies','broom','cloud-sun','cloud-sun-rain','frog','rainbow','seedling','umbrella','ban','battery-empty','battery-full','battery-half','battery-quarter','battery-three-quarters','bell','bell-slash','calendar','calendar-alt','calendar-check','calendar-day','calendar-minus','calendar-plus','calendar-times','calendar-week','cart-arrow-down','cart-plus','comment','comment-alt','comment-slash','compass','door-closed','door-open','exclamation','exclamation-circle','exclamation-triangle','eye','eye-slash','file','file-alt','folder','folder-open','gas-pump','info','info-circle','lightbulb','lock','lock-open','map-marker','map-marker-alt','microphone','microphone-alt','microphone-alt-slash','microphone-slash','minus','minus-circle','minus-square','parking','phone','phone-alt','phone-slash','plus','plus-circle','plus-square','print','question','question-circle','shield-alt','shopping-cart','sign-in-alt','sign-out-alt','signal','smoking-ban','star','star-half','star-half-alt','stream','thermometer-empty','thermometer-full','thermometer-half','thermometer-quarter','thermometer-three-quarters','thumbs-down','thumbs-up','tint','tint-slash','toggle-off','toggle-on','unlock','unlock-alt','user','user-alt','user-alt-slash','user-slash','video','video-slash','volume-down','volume-mute','volume-off','volume-up','wifi','acquisitions-incorporated','book-dead','critical-role','d-and-d','d-and-d-beyond','dice-d20','dice-d6','dragon','dungeon','fantasy-flight-games','fist-raised','hat-wizard','penny-arcade','ring','scroll','skull-crossbones','wizards-of-the-coast','archway','atlas','bed','bus','bus-alt','cocktail','concierge-bell','dumbbell','glass-martini','glass-martini-alt','globe-africa','globe-americas','globe-asia','globe-europe','hot-tub','hotel','luggage-cart','map','map-marked','map-marked-alt','monument','passport','plane','plane-arrival','plane-departure','shuttle-van','spa','suitcase','suitcase-rolling','swimmer','swimming-pool','taxi','tram','tv','umbrella-beach','wine-glass','wine-glass-alt','accessible-icon','address-book','address-card','baby','bed','biking','blind','chalkboard-teacher','child','female','frown','hiking','id-badge','id-card','id-card-alt','male','meh','people-carry','person-booth','poo','portrait','power-off','pray','restroom','running','skating','skiing','skiing-nordic','smile','snowboarding','street-view','swimmer','user','user-alt','user-alt-slash','user-astronaut','user-check','user-circle','user-clock','user-cog','user-edit','user-friends','user-graduate','user-injured','user-lock','user-md','user-minus','user-ninja','user-nurse','user-plus','user-secret','user-shield','user-slash','user-tag','user-tie','user-times','users','users-cog','walking','wheelchair','bolt','cloud','cloud-meatball','cloud-moon','cloud-moon-rain','cloud-rain','cloud-showers-heavy','cloud-sun','cloud-sun-rain','meteor','moon','poo-storm','rainbow','smog','snowflake','sun','temperature-high','temperature-low','umbrella','water','wind','glass-whiskey','icicles','igloo','mitten','skating','skiing','skiing-nordic','snowboarding','snowplow','tram');
        if ( 'line' == $font_type ) {
            return $iconsLA;
        } else {
            return $iconsFA;
        }
    }
}

if (!function_exists('get_fa_icons_full')) {
    function get_fa_icons_full()
    {
        return array(
            'none' => '',
            'glass' => 'f000',
            'remove' => 'f00d',
            'rmb' => 'f157',
            'rotate-right' => 'f01e',
            'send' => 'f1d8',
            'shekel' => 'f20b',
            'shower' => 'f2cc',
            'thermometer-0' => 'f2cb',
            'thermometer-4' => 'f2c7',
            'thermometer-quarter' => 'f2ca',
            'times-rectangle' => 'f2d3',
            'toggle-left' => 'f191',
            'toggle-right' => 'f152',
            'user-circle-o' => 'f2be',
            'vcard-o' => 'f2bc',
            'warning' => 'f071',
            'window-minimize' => 'f2d1',
            'y-combinator-square' => 'f1d4',
            'dedent' => 'f03b',
            'eercast' => 'f2da',
            'envelope-open-o' => 'f2b7',
            'etsy' => 'f2d7',
            'facebook-f' => 'f09a',
            'free-code-camp' => 'f2c5',
            'gears' => 'f085',
            'gittip' => 'f184',
            'grav' => 'f2d6',
            'hand-grab-o' => 'f255',
            'hand-stop-o' => 'f256',
            'hourglass-2' => 'f252',
            'intersex' => 'f224',
            'life-buoy' => 'f1cd',
            'linode' => 'f2b8',
            'microchip' => 'f2db',
            'ravelry' => 'f2d9',
            'rotate-left' => 'f0e2',
            's15' => 'f2cd',
            'superpowers' => 'f2dd',
            'thermometer' => 'f2c7',
            'thermometer-3' => 'f2c8',
            'thermometer-half' => 'f2c9',
            'toggle-down' => 'f150',
            'unlink' => 'f127',
            'user-circle' => 'f2bd',
            'vcard' => 'f2bb',
            'weixin' => 'f1d7',
            'window-maximize' => 'f2d0',
            'won' => 'f159',
            'yc-square' => 'f1d4',
            'automobile' => 'f1b9',
            'bandcamp' => 'f2d5',
            'battery' => 'f240',
            'battery-3' => 'f241',
            'bitcoin' => 'f15a',
            'book' => 'f02d',
            'close' => 'f00d',
            'cny' => 'f157',
            'bar-chart-o' => 'f080',
            'bathtub' => 'f2cd',
            'battery-2' => 'f242',
            'dashboard' => 'f0e4',
            'deafness' => 'f2a4',
            'drivers-license-o' => 'f2c3',
            'edit' => 'f044',
            'envelope-open' => 'f2b6',
            'file-movie-o' => 'f1c8',
            'file-picture-o' => 'f1c5',
            'gear' => 'f013',
            'google-plus-circle' => 'f2b3',
            'hourglass-1' => 'f251',
            'id-card-o' => 'f2c3',
            'legal' => 'f0e3',
            'life-bouy' => 'f1cd',
            'mail-forward' => 'f064',
            'paste' => 'f0ea',
            'photo' => 'f03e',
            'quora' => 'f2c4',
            'euro' => 'f153',
            'fa' => 'f2b4',
            'feed' => 'f09e',
            'file-photo-o' => 'f1c5',
            'file-zip-o' => 'f1c6',
            'hard-of-hearing' => 'f2a4',
            'id-card' => 'f2c2',
            'imdb' => 'f2d8',
            'institution' => 'f19c',
            'life-saver' => 'f1cd',
            'ra' => 'f1d0',
            'reorder' => 'f0c9',
            'resistance' => 'f1d0',
            'rupee' => 'f156',
            'soccer-ball-o' => 'f1e3',
            'sort-down' => 'f0dd',
            'star-half-full' => 'f123',
            'thermometer-2' => 'f2c9',
            'thermometer-full' => 'f2c7',
            'tv' => 'f26c',
            'unsorted' => 'f0dc',
            'user-o' => 'f2c0',
            'window-close-o' => 'f2d4',
            'wpexplorer' => 'f2de',
            'yc' => 'f23b',
            'address-book-o' => 'f2ba',
            'asl-interpreting' => 'f2a3',
            'address-card-o' => 'f2bc',
            'bank' => 'f19c',
            'bars' => 'f0c9',
            'battery-0' => 'f244',
            'battery-4' => 'f240',
            'cab' => 'f1ba',
            'file-sound-o' => 'f1c7',
            'flash' => 'f0e7',
            'group' => 'f0c0',
            'handshake-o' => 'f2b5',
            'hotel' => 'f236',
            'hourglass-3' => 'f253',
            'id-badge' => 'f2c1',
            'image' => 'f03e',
            'mail-reply-all' => 'f122',
            'meetup' => 'f2e0',
            'mobile-phone' => 'f10b',
            'mortar-board' => 'f19d',
            'navicon' => 'f0c9',
            'podcast' => 'f2ce',
            'rouble' => 'f158',
            'ruble' => 'f158',
            'save' => 'f0c7',
            'send-o' => 'f1d9',
            'sheqel' => 'f20b',
            'signing' => 'f2a7',
            'snowflake-o' => 'f2dc',
            'sort-up' => 'f0de',
            'star-half-empty' => 'f123',
            'support' => 'f1cd',
            'telegram' => 'f2c6',
            'thermometer-1' => 'f2ca',
            'thermometer-empty' => 'f2cb',
            'thermometer-three-quarters' => 'f2c8',
            'times-rectangle-o' => 'f2d4',
            'toggle-up' => 'f151',
            'turkish-lira' => 'f195',
            'wechat' => 'f1d7',
            'window-close' => 'f2d3',
            'window-restore' => 'f2d2',
            'yen' => 'f157',
            'address-book' => 'f2b9',
            'bath' => 'f2cd',
            'battery-1' => 'f243',
            'dollar' => 'f155',
            'drivers-license' => 'f2c2',
            'music' => 'f001',
            'search' => 'f002',
            'envelope-o' => 'f003',
            'heart' => 'f004',
            'star' => 'f005',
            'star-o' => 'f006',
            'user' => 'f007',
            'film' => 'f008',
            'th-large' => 'f009',
            'th' => 'f00a',
            'th-list' => 'f00b',
            'check' => 'f00c',
            'times' => 'f00d',
            'search-plus' => 'f00e',
            'search-minus' => 'f010',
            'power-off' => 'f011',
            'signal' => 'f012',
            'cog' => 'f013',
            'trash-o' => 'f014',
            'home' => 'f015',
            'file-o' => 'f016',
            'clock-o' => 'f017',
            'road' => 'f018',
            'download' => 'f019',
            'arrow-circle-o-down' => 'f01a',
            'arrow-circle-o-up' => 'f01b',
            'inbox' => 'f01c',
            'play-circle-o' => 'f01d',
            'repeat' => 'f01e',
            'refresh' => 'f021',
            'list-alt' => 'f022',
            'lock' => 'f023',
            'flag' => 'f024',
            'headphones' => 'f025',
            'volume-off' => 'f026',
            'volume-down' => 'f027',
            'volume-up' => 'f028',
            'qrcode' => 'f029',
            'barcode' => 'f02a',
            'tag' => 'f02b',
            'tags' => 'f02c',
            'television' => 'f02d',
            'bookmark' => 'f02e',
            'print' => 'f02f',
            'camera' => 'f030',
            'font' => 'f031',
            'bold' => 'f032',
            'italic' => 'f033',
            'text-height' => 'f034',
            'text-width' => 'f035',
            'align-left' => 'f036',
            'align-center' => 'f037',
            'align-right' => 'f038',
            'align-justify' => 'f039',
            'list' => 'f03a',
            'outdent' => 'f03b',
            'indent' => 'f03c',
            'video-camera' => 'f03d',
            'picture-o' => 'f03e',
            'pencil' => 'f040',
            'map-marker' => 'f041',
            'adjust' => 'f042',
            'tint' => 'f043',
            'pencil-square-o' => 'f044',
            'share-square-o' => 'f045',
            'check-square-o' => 'f046',
            'arrows' => 'f047',
            'step-backward' => 'f048',
            'fast-backward' => 'f049',
            'backward' => 'f04a',
            'play' => 'f04b',
            'pause' => 'f04c',
            'stop' => 'f04d',
            'forward' => 'f04e',
            'fast-forward' => 'f050',
            'step-forward' => 'f051',
            'eject' => 'f052',
            'chevron-left' => 'f053',
            'chevron-right' => 'f054',
            'plus-circle' => 'f055',
            'minus-circle' => 'f056',
            'times-circle' => 'f057',
            'check-circle' => 'f058',
            'question-circle' => 'f059',
            'info-circle' => 'f05a',
            'crosshairs' => 'f05b',
            'times-circle-o' => 'f05c',
            'check-circle-o' => 'f05d',
            'ban' => 'f05e',
            'arrow-left' => 'f060',
            'arrow-right' => 'f061',
            'arrow-up' => 'f062',
            'arrow-down' => 'f063',
            'share' => 'f064',
            'expand' => 'f065',
            'compress' => 'f066',
            'plus' => 'f067',
            'minus' => 'f068',
            'asterisk' => 'f069',
            'exclamation-circle' => 'f06a',
            'gift' => 'f06b',
            'leaf' => 'f06c',
            'fire' => 'f06d',
            'eye' => 'f06e',
            'eye-slash' => 'f070',
            'exclamation-triangle' => 'f071',
            'plane' => 'f072',
            'calendar' => 'f073',
            'random' => 'f074',
            'comment' => 'f075',
            'magnet' => 'f076',
            'chevron-up' => 'f077',
            'chevron-down' => 'f078',
            'retweet' => 'f079',
            'shopping-cart' => 'f07a',
            'folder' => 'f07b',
            'folder-open' => 'f07c',
            'arrows-v' => 'f07d',
            'arrows-h' => 'f07e',
            'bar-chart' => 'f080',
            'twitter-square' => 'f081',
            'facebook-square' => 'f082',
            'camera-retro' => 'f083',
            'key' => 'f084',
            'cogs' => 'f085',
            'comments' => 'f086',
            'thumbs-o-up' => 'f087',
            'thumbs-o-down' => 'f088',
            'star-half' => 'f089',
            'heart-o' => 'f08a',
            'sign-out' => 'f08b',
            'linkedin-square' => 'f08c',
            'thumb-tack' => 'f08d',
            'external-link' => 'f08e',
            'sign-in' => 'f090',
            'trophy' => 'f091',
            'github-square' => 'f092',
            'upload' => 'f093',
            'lemon-o' => 'f094',
            'phone' => 'f095',
            'square-o' => 'f096',
            'bookmark-o' => 'f097',
            'phone-square' => 'f098',
            'twitter' => 'f099',
            'facebook' => 'f09a',
            'github' => 'f09b',
            'unlock' => 'f09c',
            'credit-card' => 'f09d',
            'rss' => 'f09e',
            'hdd-o' => 'f0a0',
            'bullhorn' => 'f0a1',
            'bell' => 'f0f3',
            'certificate' => 'f0a3',
            'hand-o-right' => 'f0a4',
            'hand-o-left' => 'f0a5',
            'hand-o-up' => 'f0a6',
            'hand-o-down' => 'f0a7',
            'arrow-circle-left' => 'f0a8',
            'arrow-circle-right' => 'f0a9',
            'arrow-circle-up' => 'f0aa',
            'arrow-circle-down' => 'f0ab',
            'globe' => 'f0ac',
            'wrench' => 'f0ad',
            'tasks' => 'f0ae',
            'filter' => 'f0b0',
            'briefcase' => 'f0b1',
            'arrows-alt' => 'f0b2',
            'users' => 'f0c0',
            'link' => 'f0c1',
            'cloud' => 'f0c2',
            'flask' => 'f0c3',
            'scissors' => 'f0c4',
            'files-o' => 'f0c5',
            'paperclip' => 'f0c6',
            'floppy-o' => 'f0c7',
            'square' => 'f0c8',
            'bars' => 'f0c9',
            'list-ul' => 'f0ca',
            'list-ol' => 'f0cb',
            'strikethrough' => 'f0cc',
            'underline' => 'f0cd',
            'table' => 'f0ce',
            'magic' => 'f0d0',
            'truck' => 'f0d1',
            'pinterest' => 'f0d2',
            'pinterest-square' => 'f0d3',
            'google-plus-square' => 'f0d4',
            'google-plus' => 'f0d5',
            'money' => 'f0d6',
            'caret-down' => 'f0d7',
            'caret-up' => 'f0d8',
            'caret-left' => 'f0d9',
            'caret-right' => 'f0da',
            'columns' => 'f0db',
            'sort' => 'f0dc',
            'sort-desc' => 'f0dd',
            'sort-asc' => 'f0de',
            'envelope' => 'f0e0',
            'linkedin' => 'f0e1',
            'undo' => 'f0e2',
            'gavel' => 'f0e3',
            'tachometer' => 'f0e4',
            'comment-o' => 'f0e5',
            'comments-o' => 'f0e6',
            'bolt' => 'f0e7',
            'sitemap' => 'f0e8',
            'umbrella' => 'f0e9',
            'clipboard' => 'f0ea',
            'lightbulb-o' => 'f0eb',
            'exchange' => 'f0ec',
            'cloud-download' => 'f0ed',
            'cloud-upload' => 'f0ee',
            'user-md' => 'f0f0',
            'stethoscope' => 'f0f1',
            'suitcase' => 'f0f2',
            'bell-o' => 'f0a2',
            'coffee' => 'f0f4',
            'cutlery' => 'f0f5',
            'file-text-o' => 'f0f6',
            'building-o' => 'f0f7',
            'hospital-o' => 'f0f8',
            'ambulance' => 'f0f9',
            'medkit' => 'f0fa',
            'fighter-jet' => 'f0fb',
            'beer' => 'f0fc',
            'h-square' => 'f0fd',
            'plus-square' => 'f0fe',
            'angle-double-left' => 'f100',
            'angle-double-right' => 'f101',
            'angle-double-up' => 'f102',
            'angle-double-down' => 'f103',
            'angle-left' => 'f104',
            'angle-right' => 'f105',
            'angle-up' => 'f106',
            'angle-down' => 'f107',
            'desktop' => 'f108',
            'laptop' => 'f109',
            'tablet' => 'f10a',
            'mobile' => 'f10b',
            'circle-o' => 'f10c',
            'quote-left' => 'f10d',
            'quote-right' => 'f10e',
            'spinner' => 'f110',
            'circle' => 'f111',
            'reply' => 'f112',
            'github-alt' => 'f113',
            'folder-o' => 'f114',
            'folder-open-o' => 'f115',
            'smile-o' => 'f118',
            'frown-o' => 'f119',
            'meh-o' => 'f11a',
            'gamepad' => 'f11b',
            'keyboard-o' => 'f11c',
            'flag-o' => 'f11d',
            'flag-checkered' => 'f11e',
            'terminal' => 'f120',
            'code' => 'f121',
            'reply-all' => 'f122',
            'star-half-o' => 'f123',
            'location-arrow' => 'f124',
            'crop' => 'f125',
            'code-fork' => 'f126',
            'chain-broken' => 'f127',
            'question' => 'f128',
            'info' => 'f129',
            'exclamation' => 'f12a',
            'superscript' => 'f12b',
            'subscript' => 'f12c',
            'eraser' => 'f12d',
            'puzzle-piece' => 'f12e',
            'microphone' => 'f130',
            'microphone-slash' => 'f131',
            'shield' => 'f132',
            'calendar-o' => 'f133',
            'fire-extinguisher' => 'f134',
            'rocket' => 'f135',
            'maxcdn' => 'f136',
            'chevron-circle-left' => 'f137',
            'chevron-circle-right' => 'f138',
            'chevron-circle-up' => 'f139',
            'chevron-circle-down' => 'f13a',
            'html5' => 'f13b',
            'css3' => 'f13c',
            'anchor' => 'f13d',
            'unlock-alt' => 'f13e',
            'bullseye' => 'f140',
            'ellipsis-h' => 'f141',
            'ellipsis-v' => 'f142',
            'rss-square' => 'f143',
            'play-circle' => 'f144',
            'ticket' => 'f145',
            'minus-square' => 'f146',
            'minus-square-o' => 'f147',
            'level-up' => 'f148',
            'level-down' => 'f149',
            'check-square' => 'f14a',
            'pencil-square' => 'f14b',
            'external-link-square' => 'f14c',
            'share-square' => 'f14d',
            'compass' => 'f14e',
            'caret-square-o-down' => 'f150',
            'caret-square-o-up' => 'f151',
            'caret-square-o-right' => 'f152',
            'eur' => 'f153',
            'gbp' => 'f154',
            'usd' => 'f155',
            'inr' => 'f156',
            'jpy' => 'f157',
            'rub' => 'f158',
            'krw' => 'f159',
            'btc' => 'f15a',
            'file' => 'f15b',
            'file-text' => 'f15c',
            'sort-alpha-asc' => 'f15d',
            'sort-alpha-desc' => 'f15e',
            'sort-amount-asc' => 'f160',
            'sort-amount-desc' => 'f161',
            'sort-numeric-asc' => 'f162',
            'sort-numeric-desc' => 'f163',
            'thumbs-up' => 'f164',
            'thumbs-down' => 'f165',
            'youtube-square' => 'f166',
            'youtube' => 'f167',
            'xing' => 'f168',
            'xing-square' => 'f169',
            'youtube-play' => 'f16a',
            'dropbox' => 'f16b',
            'stack-overflow' => 'f16c',
            'instagram' => 'f16d',
            'flickr' => 'f16e',
            'adn' => 'f170',
            'bitbucket' => 'f171',
            'bitbucket-square' => 'f172',
            'tumblr' => 'f173',
            'tumblr-square' => 'f174',
            'long-arrow-down' => 'f175',
            'long-arrow-up' => 'f176',
            'long-arrow-left' => 'f177',
            'long-arrow-right' => 'f178',
            'apple' => 'f179',
            'windows' => 'f17a',
            'android' => 'f17b',
            'linux' => 'f17c',
            'dribbble' => 'f17d',
            'skype' => 'f17e',
            'foursquare' => 'f180',
            'trello' => 'f181',
            'female' => 'f182',
            'male' => 'f183',
            'gratipay' => 'f184',
            'sun-o' => 'f185',
            'moon-o' => 'f186',
            'archive' => 'f187',
            'bug' => 'f188',
            'vk' => 'f189',
            'weibo' => 'f18a',
            'renren' => 'f18b',
            'pagelines' => 'f18c',
            'stack-exchange' => 'f18d',
            'arrow-circle-o-right' => 'f18e',
            'arrow-circle-o-left' => 'f190',
            'caret-square-o-left' => 'f191',
            'dot-circle-o' => 'f192',
            'wheelchair' => 'f193',
            'vimeo-square' => 'f194',
            'try' => 'f195',
            'plus-square-o' => 'f196',
            'space-shuttle' => 'f197',
            'slack' => 'f198',
            'envelope-square' => 'f199',
            'wordpress' => 'f19a',
            'openid' => 'f19b',
            'university' => 'f19c',
            'graduation-cap' => 'f19d',
            'yahoo' => 'f19e',
            'google' => 'f1a0',
            'reddit' => 'f1a1',
            'reddit-square' => 'f1a2',
            'stumbleupon-circle' => 'f1a3',
            'stumbleupon' => 'f1a4',
            'delicious' => 'f1a5',
            'digg' => 'f1a6',
            'pied-piper-pp' => 'f1a7',
            'pied-piper-alt' => 'f1a8',
            'drupal' => 'f1a9',
            'joomla' => 'f1aa',
            'language' => 'f1ab',
            'fax' => 'f1ac',
            'building' => 'f1ad',
            'child' => 'f1ae',
            'paw' => 'f1b0',
            'spoon' => 'f1b1',
            'cube' => 'f1b2',
            'cubes' => 'f1b3',
            'behance' => 'f1b4',
            'behance-square' => 'f1b5',
            'steam' => 'f1b6',
            'steam-square' => 'f1b7',
            'recycle' => 'f1b8',
            'car' => 'f1b9',
            'taxi' => 'f1ba',
            'tree' => 'f1bb',
            'spotify' => 'f1bc',
            'deviantart' => 'f1bd',
            'soundcloud' => 'f1be',
            'database' => 'f1c0',
            'file-pdf-o' => 'f1c1',
            'file-word-o' => 'f1c2',
            'file-excel-o' => 'f1c3',
            'file-powerpoint-o' => 'f1c4',
            'file-image-o' => 'f1c5',
            'file-archive-o' => 'f1c6',
            'file-audio-o' => 'f1c7',
            'file-video-o' => 'f1c8',
            'file-code-o' => 'f1c9',
            'vine' => 'f1ca',
            'codepen' => 'f1cb',
            'jsfiddle' => 'f1cc',
            'life-ring' => 'f1cd',
            'circle-o-notch' => 'f1ce',
            'rebel' => 'f1d0',
            'empire' => 'f1d1',
            'git-square' => 'f1d2',
            'git' => 'f1d3',
            'hacker-news' => 'f1d4',
            'tencent-weibo' => 'f1d5',
            'qq' => 'f1d6',
            'question-circle-o' => 'f1d7',
            'paper-plane' => 'f1d8',
            'paper-plane-o' => 'f1d9',
            'history' => 'f1da',
            'circle-thin' => 'f1db',
            'header' => 'f1dc',
            'paragraph' => 'f1dd',
            'sliders' => 'f1de',
            'share-alt' => 'f1e0',
            'share-alt-square' => 'f1e1',
            'bomb' => 'f1e2',
            'futbol-o' => 'f1e3',
            'tty' => 'f1e4',
            'binoculars' => 'f1e5',
            'plug' => 'f1e6',
            'slideshare' => 'f1e7',
            'twitch' => 'f1e8',
            'yelp' => 'f1e9',
            'newspaper-o' => 'f1ea',
            'wifi' => 'f1eb',
            'calculator' => 'f1ec',
            'paypal' => 'f1ed',
            'google-wallet' => 'f1ee',
            'cc-visa' => 'f1f0',
            'cc-mastercard' => 'f1f1',
            'cc-discover' => 'f1f2',
            'cc-amex' => 'f1f3',
            'cc-paypal' => 'f1f4',
            'cc-stripe' => 'f1f5',
            'bell-slash' => 'f1f6',
            'bell-slash-o' => 'f1f7',
            'trash' => 'f1f8',
            'copyright' => 'f1f9',
            'at' => 'f1fa',
            'eyedropper' => 'f1fb',
            'paint-brush' => 'f1fc',
            'birthday-cake' => 'f1fd',
            'area-chart' => 'f1fe',
            'pie-chart' => 'f200',
            'line-chart' => 'f201',
            'lastfm' => 'f202',
            'lastfm-square' => 'f203',
            'toggle-off' => 'f204',
            'toggle-on' => 'f205',
            'bicycle' => 'f206',
            'bus' => 'f207',
            'ioxhost' => 'f208',
            'angellist' => 'f209',
            'cc' => 'f20a',
            'ils' => 'f20b',
            'meanpath' => 'f20c',
            'buysellads' => 'f20d',
            'connectdevelop' => 'f20e',
            'dashcube' => 'f210',
            'forumbee' => 'f211',
            'leanpub' => 'f212',
            'sellsy' => 'f213',
            'shirtsinbulk' => 'f214',
            'simplybuilt' => 'f215',
            'skyatlas' => 'f216',
            'cart-plus' => 'f217',
            'cart-arrow-down' => 'f218',
            'diamond' => 'f219',
            'ship' => 'f21a',
            'user-secret' => 'f21b',
            'motorcycle' => 'f21c',
            'street-view' => 'f21d',
            'heartbeat' => 'f21e',
            'venus' => 'f221',
            'mars' => 'f222',
            'mercury' => 'f223',
            'transgender' => 'f224',
            'transgender-alt' => 'f225',
            'venus-double' => 'f226',
            'mars-double' => 'f227',
            'venus-mars' => 'f228',
            'mars-stroke' => 'f229',
            'mars-stroke-v' => 'f22a',
            'mars-stroke-h' => 'f22b',
            'neuter' => 'f22c',
            'genderless' => 'f22d',
            'facebook-official' => 'f230',
            'pinterest-p' => 'f231',
            'whatsapp' => 'f232',
            'server' => 'f233',
            'user-plus' => 'f234',
            'user-times' => 'f235',
            'bed' => 'f236',
            'viacoin' => 'f237',
            'train' => 'f238',
            'subway' => 'f239',
            'medium' => 'f23a',
            'y-combinator' => 'f23b',
            'optin-monster' => 'f23c',
            'opencart' => 'f23d',
            'expeditedssl' => 'f23e',
            'battery-full' => 'f240',
            'battery-three-quarters' => 'f241',
            'battery-half' => 'f242',
            'battery-quarter' => 'f243',
            'battery-empty' => 'f244',
            'mouse-pointer' => 'f245',
            'i-cursor' => 'f246',
            'object-group' => 'f247',
            'object-ungroup' => 'f248',
            'sticky-note' => 'f249',
            'sticky-note-o' => 'f24a',
            'cc-jcb' => 'f24b',
            'cc-diners-club' => 'f24c',
            'clone' => 'f24d',
            'balance-scale' => 'f24e',
            'hourglass-o' => 'f250',
            'hourglass-start' => 'f251',
            'hourglass-half' => 'f252',
            'hourglass-end' => 'f253',
            'hourglass' => 'f254',
            'hand-rock-o' => 'f255',
            'hand-paper-o' => 'f256',
            'hand-scissors-o' => 'f257',
            'hand-lizard-o' => 'f258',
            'hand-spock-o' => 'f259',
            'hand-pointer-o' => 'f25a',
            'hand-peace-o' => 'f25b',
            'trademark' => 'f25c',
            'registered' => 'f25d',
            'creative-commons' => 'f25e',
            'gg' => 'f260',
            'gg-circle' => 'f261',
            'tripadvisor' => 'f262',
            'odnoklassniki' => 'f263',
            'odnoklassniki-square' => 'f264',
            'get-pocket' => 'f265',
            'wikipedia-w' => 'f266',
            'safari' => 'f267',
            'chrome' => 'f268',
            'firefox' => 'f269',
            'opera' => 'f26a',
            'internet-explorer' => 'f26b',
            'television' => 'f26c',
            'contao' => 'f26d',
            '500px' => 'f26e',
            'amazon' => 'f270',
            'calendar-plus-o' => 'f271',
            'calendar-minus-o' => 'f272',
            'calendar-times-o' => 'f273',
            'calendar-check-o' => 'f274',
            'industry' => 'f275',
            'map-pin' => 'f276',
            'map-signs' => 'f277',
            'map-o' => 'f278',
            'map' => 'f279',
            'commenting' => 'f27a',
            'commenting-o' => 'f27b',
            'houzz' => 'f27c',
            'vimeo' => 'f27d',
            'black-tie' => 'f27e',
            'fonticons' => 'f280',
            'reddit-alien' => 'f281',
            'edge' => 'f282',
            'credit-card-alt' => 'f283',
            'codiepie' => 'f284',
            'modx' => 'f285',
            'fort-awesome' => 'f286',
            'usb' => 'f287',
            'product-hunt' => 'f288',
            'mixcloud' => 'f289',
            'scribd' => 'f28a',
            'pause-circle' => 'f28b',
            'pause-circle-o' => 'f28c',
            'stop-circle' => 'f28d',
            'stop-circle-o' => 'f28e',
            'shopping-bag' => 'f290',
            'shopping-basket' => 'f291',
            'hashtag' => 'f292',
            'bluetooth' => 'f293',
            'bluetooth-b' => 'f294',
            'percent' => 'f295',
            'gitlab' => 'f296',
            'wpbeginner' => 'f297',
            'wpforms' => 'f298',
            'envira' => 'f299',
            'universal-access' => 'f29a',
            'wheelchair-alt' => 'f29b',
            'question-circle-o' => 'f29c',
            'blind' => 'f29d',
            'audio-description' => 'f29e',
            'volume-control-phone' => 'f2a0',
            'braille' => 'f2a1',
            'assistive-listening-systems' => 'f2a2',
            'american-sign-language-interpreting' => 'f2a3',
            'deaf' => 'f2a4',
            'glide' => 'f2a5',
            'glide-g' => 'f2a6',
            'sign-language' => 'f2a7',
            'low-vision' => 'f2a8',
            'viadeo' => 'f2a9',
            'viadeo-square' => 'f2aa',
            'snapchat' => 'f2ab',
            'snapchat-ghost' => 'f2ac',
            'snapchat-square' => 'f2ad',
            'pied-piper' => 'f2ae',
            'first-order' => 'f2b0',
            'yoast' => 'f2b1',
            'themeisle' => 'f2b2',
            'google-plus-official' => 'f2b3',
            'font-awesome' => 'f2b4',


        );
    }
}

if (!function_exists('get_cat_icon')) {
    function get_cat_icon($term_id)
    {
        $icon = get_term_meta($term_id, 'category_icon', true);
        return !empty($icon) ? $icon : '';
    }
}

/**
 * @since 5.3.0
 */

if (!function_exists('atbdp_icon_type')) {
    function atbdp_icon_type($echo = false)
    {
        $font_type = get_directorist_option('font_type', 'line');
        $font_type = ('line' === $font_type) ? "la la" : "fa fa";
        if ($echo) {
            echo $font_type;
        } else {
            return $font_type;
        }
    }
}

function directorist_icon( $icon, $echo = true ) {
    if ( !$icon ) {
        return;
    }

    $html = sprintf('<i class="directorist-icon %s"></i>', $icon );

    if ($echo) {
        echo $html;
    }
    else {
        return $html;
    }
}

if ( ! function_exists( 'atbdp_get_term_icon' ) ) {
    function atbdp_get_term_icon( array $args = [] )
    {  
        $default = [ 'icon' => '', 'default' => 'la-folder-open', 'echo' => false ];
        $args = array_merge( $default, $args );

        $icon = ( ! empty($args['icon'] ) ) ? 'la ' . $args['icon'] : $args['default'];
        $icon = ( ! empty( $icon ) ) ? 'la ' . $icon : '';
        $icon = ( ! empty( $icon ) ) ? '<span class="'. $icon .'"></span>' : $icon;

        if ( ! $args['echo'] ) { return $icon; } 

        echo $icon;
    }
}


if (!function_exists('atbdp_sanitize_array')) {
    /**
     * It sanitize a multi-dimensional array
     * @param array &$array The array of the data to sanitize
     * @return mixed
     */
    function atbdp_sanitize_array(&$array)
    {

        foreach ($array as &$value) {
            if (!is_array($value)) {
                // sanitize if value is not an array
                $value = sanitize_text_field($value);
            } else {
                // go inside this function again
                atbdp_sanitize_array($value);
            }
        }
        return $array;
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
            $paged = 1;
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
        if (!empty($_POST['atbdp_nonce_js']) && (wp_verify_nonce($_POST['atbdp_nonce_js'], 'atbdp_nonce_action_js')))
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
            'active' => get_directorist_option('enable_featured_listing'),
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
        if (!atbdp_logged_in_user()) {
            // user not logged in;
            $error_message = (empty($message))
                ? sprintf(__('You need to be logged in to view the content of this page. You can login %s. Don\'t have an account? %s', 'directorist'), apply_filters("atbdp_login_page_link", "<a href='" . ATBDP_Permalink::get_login_page_link() . "'> " . __('Here', 'directorist') . "</a>"), apply_filters("atbdp_signup_page_link", "<a href='" . ATBDP_Permalink::get_registration_page_link() . "'> " . __('Sign up', 'directorist') . "</a>"))
                : $message;
            $container_fluid = is_directoria_active() ? 'container' : 'container-fluid';
            ?>
            <section class="directory_wrapper single_area">
                <div class="<?php echo apply_filters('atbdp_login_message_container_fluid', $container_fluid) ?>">
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
    function calc_listing_expiry_date($start_date = NULL, $expire = NULL)
    {
        $exp_days = get_directorist_option('listing_expire_in_days', 999, 999);
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
        $c_position = get_directorist_option('g_currency_position');
    }
    if (empty($currency)) {
        $currency = get_directorist_option('g_currency', 'USD');
    }
    if (empty($symbol)) {
        $symbol = atbdp_currency_symbol($currency);
    }

    ('after' == $c_position) ? $after = $symbol : $before = $symbol;
    $price = $before . atbdp_format_amount($price, $allow_decimal) . $after;
    $p = apply_filters('atbdp_listing_price', sprintf("<span class='directorist-listing-price'>%s</span>", $price));
    if ($echo) {
        echo $p;
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
    $currency = get_directorist_option('g_currency', 'USD');
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
    return apply_filters('atbdp_listing_price', $output);

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

    $total_categories = ATBDP_Listings_Data_Store::get_listings( $args );

    return count( $total_categories );
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
function atbdp_listings_count_by_location( $term_id, $lisitng_type = '' )
{
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
                'taxonomy' => ATBDP_CATEGORY,
                'field' => 'term_id',
                'terms' => $term_id,
                'include_children' => true
            )
        );
    }

    $total_location = ATBDP_Listings_Data_Store::get_listings( $args );
    return count( $total_location );
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
                'value' => 1,
            ),
        ))
    );

    return count(get_posts($args));

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

    if (isset($_GET['sort'])) {
        $order = sanitize_text_field($_GET['sort']);
    } else if (isset($_GET['order'])) {
        $order = sanitize_text_field($_GET['order']);
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
function atbdp_get_listings_orderby_options($sort_by_items)
{
    $options = array(
        'title-asc' => __("A to Z (title)", 'directorist'),
        'title-desc' => __("Z to A (title)", 'directorist'),
        'date-desc' => __("Latest listings", 'directorist'),
        'date-asc' => __("Oldest listings", 'directorist'),
        'views-desc' => __("Popular listings", 'directorist'),
        'price-asc' => __("Price (low to high)", 'directorist'),
        'price-desc' => __("Price (high to low)", 'directorist'),
        'rand' => __("Random listings", 'directorist'),
    );

    if (!in_array('a_z', $sort_by_items)) {
        unset($options['title-asc']);
    }
    if (!in_array('z_a', $sort_by_items)) {
        unset($options['title-desc']);
    }
    if (!in_array('latest', $sort_by_items)) {
        unset($options['date-desc']);
    }
    if (!in_array('oldest', $sort_by_items)) {
        unset($options['date-asc']);
    }
    if (!in_array('popular', $sort_by_items)) {
        unset($options['views-desc']);
    }
    if (!in_array('price_low_high', $sort_by_items)) {
        unset($options['price-asc']);
    }
    if (!in_array('price_high_low', $sort_by_items)) {
        unset($options['price-desc']);
    }
    if (!in_array('random', $sort_by_items)) {
        unset($options['rand']);
    }
    $args = array(
        'post_type'   => ATBDP_POST_TYPE,
        'post_status' => 'publish',
        'meta_key'    => '_price'
    );

    $values = new WP_Query($args);
    $prices = array();
    if ($values->have_posts()) {
        while ($values->have_posts()) {
            $values->the_post();
            $prices[] = get_post_meta(get_the_ID(), '_price', true);
        }
        $has_price = join($prices);
    }
    $disabled_price_by_admin = get_directorist_option('disable_list_price', 0);
    if ($disabled_price_by_admin || empty($has_price)) {
        unset($options['price-asc'], $options['price-desc']);
    }

    return apply_filters('atbdp_get_listings_orderby_options', $options);

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


    if (isset($_GET['view'])) {
        $view = sanitize_text_field($_GET['view']);
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


    if (!in_array('listings_grid', $view_as_items)) {
        unset($options[0]);
    }
    if (!in_array('listings_list', $view_as_items)) {
        unset($options[1]);
    }
    if (empty($display_map) || !in_array('listings_map', $view_as_items)) {
        unset($options[2]);
    }
    $options[] = isset($_GET['view']) ? sanitize_text_field($_GET['view']) : $listings_settings;
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
    $views = atbdp_get_listings_view_options();
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
function the_atbdp_favourites_link($post_id = 0)
{
    if (atbdp_logged_in_user()) {
        if ($post_id == 0) {
            global $post;
            $post_id = $post->ID;
        }
        $favourites = (array)get_user_meta(get_current_user_id(), 'atbdp_favourites', true);
        if (in_array($post_id, $favourites)) {
            return '<span class="' . atbdp_icon_type() . '-heart" style="color: red"></span><a href="javascript:void(0)" class="atbdp-favourites" data-post_id="' . $post_id . '"></a>';
        } else {
            return '<span class="' . atbdp_icon_type() . '-heart"></span><a href="javascript:void(0)" class="atbdp-favourites" data-post_id="' . $post_id . '"></a>';
        }
    } else {
        return '<a href="javascript:void(0)" class="atbdp-require-login"><span class="' . atbdp_icon_type() . '-heart"></span></a>';
    }
}


function atbdp_listings_mark_as_favourite($listing_id)
{
    $favourites = (array)get_user_meta(get_current_user_id(), 'atbdp_favourites', true);
    $fav_class = '';
    if (in_array($listing_id, $favourites)) {
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


/**
 * Display the favourites link.
 *
 * @param int $post_id Post ID.
 * @since    4.0
 *
 */
/*function the_atbdp_favourites_all_listing($post_id = 0)
{

    if (atbdp_logged_in_user()) {

        if ($post_id == 0) {
            global $post;
            $post_id = $post->ID;
        }

        $favourites = (array)get_user_meta(get_current_user_id(), 'atbdp_favourites', true);
        if (in_array($post_id, $favourites)) {
            echo '<a href="javascript:void(0)" class="atbdp-favourites-all-listing" data-post_id="' . $post_id . '"><span style="color: red" class="fa fa-heart"></span></a>';
        } else {
            echo '<a href="javascript:void(0)" class="atbdp-favourites-all-listing" data-post_id="' . $post_id . '"><span class="fa fa-heart"></span></a>';

        }

    } else {

        echo '<a href="javascript:void(0)" class="atbdp-require-login"><span class="fa fa-heart"></span></a>';

    }

}*/

/*
 * to get the new badge
 * @return $
 */


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

if (!function_exists('atbdp_deactivate_reasons')) {
    /**
     * Reasons for deactivate plugin
     * @since 4.4.0
     */
    function atbdp_deactivate_reasons()
    {
        $reasons = array(
            array(
                'id' => 'could-not-understand',
                'text' => 'I couldn\'t understand how to make it work',
                'type' => 'textarea',
                'placeholder' => 'Would you like us to assist you?'
            ),
            array(
                'id' => 'found-better-plugin',
                'text' => 'I found a better plugin',
                'type' => 'text',
                'placeholder' => 'What\'s the plugin\'s name?'
            ),
            array(
                'id' => 'not-have-that-feature',
                'text' => 'The plugin is great, but I need specific feature that you don\'t support',
                'type' => 'textarea',
                'placeholder' => 'Could you tell us more about that feature?'
            ),
            array(
                'id' => 'is-not-working',
                'text' => 'I couldn\'t get the plugin not to work ',
                'type' => 'textarea',
                'placeholder' => 'Could you tell us a bit more whats not working?'
            ),
            array(
                'id' => 'looking-for-other',
                'text' => 'It\'s not what I was looking for',
                'type' => '',
                'placeholder' => ''
            ),
            array(
                'id' => 'did-not-work-as-expected',
                'text' => 'The plugin didn\'t work as expected',
                'type' => 'textarea',
                'placeholder' => 'What did you expect?'
            ),
            array(
                'id' => 'other',
                'text' => 'Other',
                'type' => 'textarea',
                'placeholder' => 'Could you tell us a bit more?'
            ),
        );

        return $reasons;
    }


}

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

    global $wp_query, $post, $wp;
    //if(!is_admin()):

    switch ($atbdppages):
        case 'home':
            if (is_page() && get_the_ID() == get_directorist_option('search_listing')) {
                return true;
            } elseif (is_page() && isset($post->post_content) && has_shortcode($post->post_content, 'directorist_search_listing')) {
                return true;
            }
            break;
        case 'search-result':
            if (is_page() && get_the_ID() == get_directorist_option('search_result_page')) {
                return true;
            } elseif (is_page() && isset($post->post_content) && has_shortcode($post->post_content, 'directorist_search_result')) {
                return true;
            }
            break;
        case 'add-listing':
            if (is_page() && get_the_ID() == get_directorist_option('add_listing_page')) {
                return true;
            } elseif (is_page() && isset($post->post_content) && has_shortcode($post->post_content, 'directorist_add_listing')) {
                return true;
            }
            break;
        case 'all-listing':
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
    function atbdp_popular_listings($listing_id)
    {
        $listing_popular_by = get_directorist_option('listing_popular_by');
        $average = ATBDP()->review->get_average($listing_id);
        $average_review_for_popular = get_directorist_option('average_review_for_popular', 4);
        $view_count = get_post_meta($listing_id, '_atbdp_post_views_count', true);
        $view_to_popular = get_directorist_option('views_for_popular');
        if ('average_rating' === $listing_popular_by) {
            if ($average_review_for_popular <= $average) {
                return $pop_listing_id = $listing_id;
            }
        } elseif ('view_count' === $listing_popular_by) {
            if ((int)$view_count >= (int)$view_to_popular) {
                return $pop_listing_id = $listing_id;
            }
        } else {
            if (($average_review_for_popular <= $average) && ((int)$view_count >= (int)$view_to_popular)) {
                return $pop_listing_id = $listing_id;
            }
        }
    }
}

/**
 * Outputs the directorist categories/locations dropdown.
 *
 * @param array $args Array of options to control the field output.
 * @param bool $echo Whether to echo or just return the string.
 * @return   string             HTML attribute or empty string.
 * @since    1.5.5
 *
 */
function bdas_dropdown_terms($args = array(), $echo = true)
{

    // Vars
    $args = array_merge(array(
        'show_option_none' => '-- ' . __('Select a category', 'advanced-classifieds-and-directory-pro') . ' --',
        'option_none_value' => '',
        'taxonomy' => 'at_biz_dir-category',
        'name' => 'bdas_category',
        'class' => 'form-control',
        'required' => false,
        'base_term' => 0,
        'parent' => 0,
        'orderby' => 'name',
        'order' => 'ASC',
        'selected' => 0,
    ), $args);

    if (!empty($args['selected'])) {
        $ancestors = get_ancestors($args['selected'], $args['taxonomy']);
        $ancestors = array_merge(array_reverse($ancestors), array($args['selected']));
    } else {
        $ancestors = array();
    }

    // Build data
    $html = '';

    if (isset($args['walker'])) {

        $selected = count($ancestors) >= 2 ? (int)$ancestors[1] : 0;

        $html .= '<div class="bdas-terms">';
        $html .= sprintf('<input type="hidden" name="%s" class="bdas-term-hidden" value="%d" />', $args['name'], $selected);

        $term_args = array(
            'show_option_none' => $args['show_option_none'],
            'option_none_value' => $args['option_none_value'],
            'taxonomy' => $args['taxonomy'],
            'child_of' => $args['parent'],
            'orderby' => $args['orderby'],
            'order' => $args['order'],
            'selected' => $selected,
            'hierarchical' => true,
            'depth' => 2,
            'show_count' => false,
            'hide_empty' => false,
            'walker' => $args['walker'],
            'echo' => 0
        );

        unset($args['walker']);

        $select = wp_dropdown_categories($term_args);
        $required = $args['required'] ? ' required' : '';
        $replace = sprintf('<select class="%s" data-taxonomy="%s" data-parent="%d"%s>', $args['class'], $args['taxonomy'], $args['parent'], $required);

        $html .= preg_replace('#<select([^>]*)>#', $replace, $select);

        if ($selected > 0) {
            $args['parent'] = $selected;
            $html .= bdas_dropdown_terms($args, false);
        }

        $html .= '</div>';

    } else {

        $has_children = 0;
        $child_of = 0;

        $term_args = array(
            'parent' => $args['parent'],
            'orderby' => 'name',
            'order' => 'ASC',
            'hide_empty' => false,
            'hierarchical' => false,
        );
        $terms = get_terms($args['taxonomy'], $term_args);

        if (!empty($terms) && !is_wp_error($terms)) {

            if ($args['parent'] == $args['base_term']) {
                $required = $args['required'] ? ' required' : '';

                $html .= '<div class="bdas-terms">';
                $html .= sprintf('<input type="hidden" name="%s" class="bdas-term-hidden" value="%d" />', $args['name'], $args['selected']);
                $html .= sprintf('<select class="%s" data-taxonomy="%s" data-parent="%d"%s>', $args['class'], $args['taxonomy'], $args['parent'], $required);
                $html .= sprintf('<option value="%s">%s</option>', $args['option_none_value'], $args['show_option_none']);
            } else {
                $category_placeholder = apply_filters('atbdp_search_sub_category_placeholder', __('Select a Sub Category','directorist') );
                $location_placeholder = apply_filters('atbdp_search_sub_location_placeholder', __('Select a Sub Location','directorist') );
                $placeholder = ( $args['taxonomy'] == 'at_biz_dir-location') ? $location_placeholder : $category_placeholder;
                $html .= sprintf('<div class="bdas-child-terms bdas-child-terms-%d">', $args['parent']);
                $html .= sprintf('<select class="%s" data-taxonomy="%s" data-parent="%d">', $args['class'], $args['taxonomy'], $args['parent']);
                $html .= sprintf('<option value="%d">%s</option>', $args['parent'], $placeholder);
            }

            foreach ($terms as $term) {
                $selected = '';
                if (in_array($term->term_id, $ancestors)) {
                    $has_children = 1;
                    $child_of = $term->term_id;
                    $selected = ' selected';
                } else if ($term->term_id == $args['selected']) {
                    $selected = ' selected';
                }

                $html .= sprintf('<option value="%s"%s>%s</option>', $term->term_id, $selected, $term->name);
            }

            $html .= '</select>';
            if ($has_children) {
                $args['parent'] = $child_of;
                $html .= bdas_dropdown_terms($args, false);
            }
            $html .= '</div>';

        } else {

            if ($args['parent'] == $args['base_term']) {
                $required = $args['required'] ? ' required' : '';

                $html .= '<div class="bdas-terms">';
                $html .= sprintf('<input type="hidden" name="%s" class="bdas-term-hidden" value="%d" />', $args['name'], $args['selected']);
                $html .= sprintf('<select class="%s" data-taxonomy="%s" data-parent="%d"%s>', $args['class'], $args['taxonomy'], $args['parent'], $required);
                $html .= sprintf('<option value="%s">%s</option>', $args['option_none_value'], $args['show_option_none']);
                $html .= '</select>';
                $html .= '</div>';
            }

        }

    }

    // Echo or Return
    if ($echo) {
        echo $html;
        return '';
    } else {
        return $html;
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
 * @since 1.0.0
 */
if (!function_exists('get_atbdp_listings_ids')) {
    function get_atbdp_listings_ids()
    {
        $arg = array(
            'post_type'      => 'at_biz_dir',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'fields'         => 'ids'
        );

        $query = new WP_Query( $arg );
        return $query;
    }
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
    $current_url .= $_SERVER["SERVER_NAME"];
    if ($_SERVER["SERVER_PORT"] != "80" && $_SERVER["SERVER_PORT"] != "443") {
        $current_url .= ":" . $_SERVER["SERVER_PORT"];
    }
    $current_url .= $_SERVER["REQUEST_URI"];

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
    $active_plugins = apply_filters('active_plugins', get_option('active_plugins'));

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
 * @since 5.7.1
 * check is user already submitted review for this listing
 */
if (!function_exists('tract_duplicate_review')) {
    function tract_duplicate_review($reviewer, $listing)
    {
        $args = [
            'post_type' => 'atbdp_listing_review',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => '_listing_reviewer',
                    'value' => $reviewer,
                ),
                array(
                    'key' => '_review_listing',
                    'value' => $listing,
                ),
                array(
                    'key' => '_review_status',
                    'value' => 'pending',
                )
            )
        ];


        $reviews = new WP_Query( $args );

        $review_meta = array();
        foreach ($reviews->posts as $key => $val) {
            $review_meta[] = !empty($val) ? $val : array();
        }

        return ( $review_meta ) ? $review_meta : false;
    }
}

function search_category_location_filter($settings, $taxonomy_id, $prefix = '')
{
    if ($settings['immediate_category']) {

        if ($settings['term_id'] > $settings['parent'] && !in_array($settings['term_id'], $settings['ancestors'])) {
            return;
        }

    }
    if (ATBDP_CATEGORY == $taxonomy_id) {
        $category_slug = get_query_var('atbdp_category');
        $category = get_term_by('slug', $category_slug, ATBDP_CATEGORY);
        $category_id = !empty($category->term_id) ? $category->term_id : '';
        $term_id = isset($_GET['in_cat']) ? $_GET['in_cat'] : $category_id;
    } else {
        $location_slug = get_query_var('atbdp_location');
        $location = get_term_by('slug', $location_slug, ATBDP_LOCATION);
        $location_id = !empty($location->term_id) ? $location->term_id : '';
        $term_id = isset($_GET['in_loc']) ? $_GET['in_loc'] : $location_id;
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
            $settings['term_id'] = $term->term_id;

            $count = 0;
            if (!empty($settings['hide_empty']) || !empty($settings['show_count'])) {
                $count = atbdp_listings_count_by_category($term->term_id);

                if (!empty($settings['hide_empty']) && 0 == $count) continue;
            }
            $selected = ($term_id == $term->term_id) ? "selected" : '';
            $html .= '<option value="' . $term->term_id . '" ' . $selected . '>';
            $html .= $prefix . $term->name;
            if (!empty($settings['show_count'])) {
                $html .= ' (' . $count . ')';
            }
            $html .= search_category_location_filter($settings, $taxonomy_id, $prefix . '&nbsp;&nbsp;&nbsp;');
            $html .= '</option>';
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

    $term_slug = get_query_var($taxonomy_id);

    $args = array(
        'orderby' => $settings['orderby'],
        'order' => $settings['order'],
        'hide_empty' => $settings['hide_empty'],
        'parent' => $settings['term_id'],
        'exclude' => $plan_cat,
        'hierarchical' => !empty($settings['hide_empty']) ? true : false
    );

    $terms             = get_terms($taxonomy_id, $args);
    $listing_type      = get_term_by( 'id', $lisitng_type, ATBDP_TYPE );
    $listing_type_slug = $listing_type->slug;
    
    $html = '';

    if (count($terms) > 0) {

        foreach ($terms as $term) {
            $directory_type    = get_term_meta( $term->term_id, '_directory_type', true );
            $directory_type    = ! empty( $directory_type ) ? $directory_type : array();
            if( in_array( $listing_type_slug, $directory_type ) ) {
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
            wp_new_user_notification($user_id, null, 'admin'); // send activation to the admin
            ATBDP()->email->custom_wp_new_user_notification_email($user_id);
        }
    }
}

function atbdp_get_listing_attachment_ids($post_id){

    $listing_img = get_post_meta($post_id, '_listing_img', true);
    $listing_img = !empty($listing_img) ? $listing_img : array();
    $listing_prv_img = get_post_meta($post_id, '_listing_prv_img', true);
    array_unshift($listing_img, $listing_prv_img);
    return $listing_img;
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
        'custom_registration' => array(
            'title' => __('Registration', 'directorist'),
            'content' => '[directorist_custom_registration]'
        ), 
        'user_login' => array(
            'title' => __('Login', 'directorist'),
            'content' => '[directorist_user_login]'
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

function atbdp_logged_in_user(){
    return _wp_get_current_user()->exists();
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
    $image_quality     = get_directorist_option('preview_image_quality', 'large');  // medium | large | full

    $thumbnail_img = '';

    $listing_prv_img   = get_post_meta(get_the_ID(), '_listing_prv_img', true);
    $listing_img       = get_post_meta(get_the_ID(), '_listing_img', true);
    $default_image_src = get_directorist_option('default_preview_image', ATBDP_PUBLIC_ASSETS . 'images/grid.jpg');

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

    echo $the_html;
}

function the_thumbnail_card($img_src = '', $_args = array()) {
    _deprecated_function( __FUNCTION__, '7.0', 'atbdp_thumbnail_card()' );
    return atbdp_thumbnail_card($img_src,$_args);
}


// get_plasma_slider
function get_plasma_slider()
{
    $show_slider       = get_directorist_option( 'dsiplay_slider_single_page', 1 );
    $slider_is_enabled = ( $show_slider === 1 || $show_slider === '1' ) ? true : false;
    
    if ( ! $slider_is_enabled ) { return ''; }

    global $post;
    $listing_id    = $post->ID;
    $listing_title = get_the_title( $post->ID );
    $data          = array();
    
    // Check if gallery is allowed or not
    $fm_plan      = get_post_meta($listing_id, '_fm_plans', true);
    $show_gallery = true;

    if ( is_fee_manager_active() ) {
        $show_gallery = is_plan_allowed_slider($fm_plan);
    }

    // Get the default image
    $default_image = get_directorist_option(
        'default_preview_image', ATBDP_PUBLIC_ASSETS . 'images/grid.jpg'
    );
    
    // Get the preview images
    $preview_img_id   = get_post_meta( $listing_id, '_listing_prv_img', true);
    $preview_img_link = ! empty($preview_img_id) ? atbdp_get_image_source($preview_img_id, 'large') : '';
    $preview_img_alt  = get_post_meta($preview_img_id, '_wp_attachment_image_alt', true);
    $preview_img_alt  = ( ! empty( $preview_img_alt )  ) ? $preview_img_alt : get_the_title( $preview_img_id );

    // Get the gallery images
    $listing_img  = get_post_meta( $listing_id, '_listing_img', true );
    $listing_imgs = ( ! empty( $listing_img ) ) ? $listing_img : array();
    $image_links  = array(); // define a link placeholder variable

    foreach ( $listing_imgs as $img_id ) {
        $alt = get_post_meta( $img_id, '_wp_attachment_image_alt', true );
        $alt = ( ! empty( $alt )  ) ? $alt : get_the_title( $img_id );

        $image_links[] = [
            'alt' => ( ! empty( $alt )  ) ? $alt : $listing_title,
            'src' => atbdp_get_image_source( $img_id, 'large' ),
        ];
    }

    // Get the options
    $background_type  = get_directorist_option('single_slider_background_type', 'custom-color');
    
    // Set the options
    $data['images']             = [];
    $data['alt']                = $listing_title;
    $data['background-size']    = get_directorist_option('single_slider_image_size', 'cover');
    $data['blur-background']    = ( 'blur' === $background_type ) ? true : false;
    $data['width']              = get_directorist_option('gallery_crop_width', 670);
    $data['height']             = get_directorist_option('gallery_crop_height', 750);
    $data['background-color']   = get_directorist_option('single_slider_background_color', 'gainsboro');
    $data['thumbnail-bg-color'] = '#fff';
    $data['show-thumbnails']    = get_directorist_option('dsiplay_thumbnail_img', true);
    $data['gallery']            = true;
    $data['rtl']                = is_rtl();

    if ( $show_gallery && ! empty( $image_links ) ) {
        $data['images'] = $image_links;
    }

    if ( ! empty( $preview_img_link ) ) {
        $preview_img = [
            'alt' => $preview_img_alt,
            'src' => $preview_img_link,
        ];

        array_unshift( $data['images'], $preview_img );
    }
    
    if ( count( $data['images'] ) < 1 ) {
        $data['images'][] = [
            'alt' => $listing_title,
            'src' => $default_image,
        ];
    }

    $padding_top         = $data['height'] / $data['width'] * 100;
    $data['padding-top'] = $padding_top;

    return get_view('plasma-slider', $data);
}


function atbdp_style_example_image ($src) {
    $img = sprintf("<img src='%s'>", $src );
    echo $img;
}

function view( $file_path, $data = null )
{
    $path = ATBDP_VIEW_DIR . $file_path . '.php';
    if ( file_exists($path) ) {
        include($path);
    }
}

function get_view( $file_path, $data = null )
{
    $view = '';
    ob_start();
    view( $file_path, $data );
    $view =  ob_get_contents();
    ob_end_clean();

    return $view;
}

if(!function_exists('csv_get_data')){
    function csv_get_data($default_file = null, $multiple = null, $delimiter = ',')
    {
        $data = $multiple ? array() : '';
        $errors = array();
        // Get array of CSV files
        $file = $default_file ? $default_file : '';
        if (!$file) return;

        // Attempt to change permissions if not readable
        if (!is_readable($file)) {
            chmod($file, 0744);
        }

        // Check if file is writable, then open it in 'read only' mode
        if (is_readable($file) && $_file = fopen($file, "r")) {

            // To sum this part up, all it really does is go row by
            //  row, column by column, saving all the data
            $post = array();

            // Get first row in CSV, which is of course the headers
            $header = fgetcsv($_file, 0, $delimiter);

            while ($row = fgetcsv($_file, 0, $delimiter)) {

                foreach ($header as $i => $key) {
                    $post[$key] = $row[$i];
                }

                if ($multiple) {
                    $data[] = $post;
                } else {
                    $data = $post;
                }
            }

            fclose($_file);
        } else {
            $errors[] = "File '$file' could not be opened. Check the file's permissions to make sure it's readable by your server.";
        }
        if (!empty($errors)) {
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
    $id = '';
    $all_types     = get_terms(array(
        'taxonomy'   => ATBDP_TYPE,
        'hide_empty' => false,
    ));
    foreach( $all_types as $type ) {
        $default = get_term_meta( $type->term_id, '_default', true );
        if( $default ){
            $id = $type->term_id;
        }
    }
    return $id;
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
        $listing_types = get_terms([
            'taxonomy'   => ATBDP_TYPE,
            'hide_empty' => false,
            'orderby'    => 'date',
            'order'      => 'DSCE',
          ]);
          return $listing_types;
    }
}
if( !function_exists('default_directory_type') ){
    function default_directory_type() {
        $default_directory = '';
        if( !empty( directory_types() ) ) {
            foreach( directory_types() as $term ) {
                $default = get_term_meta( $term->term_id, '_default', true );
                if( $default ) {
                    $default_directory = $term->term_id;
                    break;
                }
            }
        }
        return $default_directory;
    }
}

if( !function_exists('directorist_get_form_fields_by_directory_type') ){
    function directorist_get_form_fields_by_directory_type( $field = 'id', $value = '' ) {
        $term                   = get_term_by( $field, $value, ATBDP_TYPE );
        $submission_form        = get_term_meta( $term->term_id, 'submission_form_fields', true );   
        $submission_form_fields = $submission_form['fields'];
        return $submission_form_fields;
    }
}

if( !function_exists('directorist_legacy_mode') ){
    function directorist_legacy_mode() {
        return get_directorist_option( 'atbdp_legacy_template', false );
    }
}