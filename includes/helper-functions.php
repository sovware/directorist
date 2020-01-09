<?php
// Prohibit direct script loading.
defined('ABSPATH') || die('No direct script access allowed!');

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
        $links = paginate_links(array(
            'base' => str_replace($largeNumber, '%#%', esc_url(get_pagenum_link($largeNumber))),
            'format' => '?paged=%#%',
            'current' => max(1, $paged),
            'total' => $custom_post_query->max_num_pages,
            'prev_text' => apply_filters('atbdp_pagination_prev_text', '<span class="fa fa-chevron-left"></span>'),
            'next_text' => apply_filters('atbdp_pagination_next_text', '<span class="fa fa-chevron-right atbdp_right_nav"></span>'),
        ));


        if ($links) {
            $navigation = _navigation_markup($links, 'pagination', ' ');
        }
        return apply_filters('atbdp_pagination', $navigation, $links, $custom_post_query, $paged);
    }
}

if (!function_exists('get_fa_icons')) {
    function get_fa_icons()
    {
        return $iconsFA = array("none", "la-adjust", "la-adn", "la-align-center", "la-align-justify", "la-align-left", "la-align-right", "la-amazon", "la-ambulance", "la-anchor", "la-android", "la-angellist", "la-angle-double-down", "la-angle-double-left", "la-angle-double-right", "la-angle-double-up", "la-angle-down", "la-angle-left", "la-angle-right", "la-angle-up", "la-apple", "la-archive", "la-area-chart", "la-arrow-circle-down", "la-arrow-circle-left", "la-arrow-circle-o-down", "la-arrow-circle-o-left", "la-arrow-circle-o-right", "la-arrow-circle-o-up", "la-arrow-circle-right", "la-arrow-circle-up", "la-arrow-down", "la-arrow-left", "la-arrow-right", "la-arrow-up", "la-arrows", "la-arrows-alt", "la-arrows-h", "la-arrows-v", "la-asterisk", "la-at", "la-automobile", "la-backward", "la-balance-scale", "la-ban", "la-bank", "la-bar-chart", "la-bar-chart-o", "la-barcode", "la-bars", "la-battery-0", "la-battery-1", "la-battery-2", "la-battery-3", "la-battery-4", "la-battery-empty", "la-battery-full", "la-battery-half", "la-battery-quarter", "la-battery-three-quarters", "la-bed", "la-beer", "la-behance", "la-behance-square", "la-bell", "la-bell-o", "la-bell-slash", "la-bell-slash-o", "la-bicycle", "la-binoculars", "la-birthday-cake", "la-bitbucket", "la-bitbucket-square", "la-bitcoin", "la-black-tie", "la-bold", "la-bolt", "la-bomb", "la-book", "la-bookmark", "la-bookmark-o", "la-briefcase", "la-btc", "la-bug", "la-building", "la-building-o", "la-bullhorn", "la-bullseye", "la-bus", "la-buysellads", "la-cab", "la-calculator", "la-calendar", "la-calendar-check-o", "la-calendar-minus-o", "la-calendar-o", "la-calendar-plus-o", "la-calendar-times-o", "la-camera", "la-camera-retro", "la-caret-down", "la-caret-left", "la-caret-right", "la-caret-square-o-down", "la-toggle-down", "la-caret-square-o-left", "la-toggle-left", "la-caret-square-o-right", "la-toggle-right", "la-caret-square-o-up", "la-toggle-up", "la-caret-up", "la-cart-arrow-down", "la-cart-plus", "la-cc", "la-cc-amex", "la-cc-diners-club", "la-cc-discover", "la-cc-jcb", "la-cc-mastercard", "la-cc-paypal", "la-cc-stripe", "la-cc-visa", "la-certificate", "la-chain", "la-chain-broken", "la-check", "la-check-circle", "la-check-circle-o", "la-check-square", "la-check-square-o", "la-chevron-circle-down", "la-chevron-circle-left", "la-chevron-circle-right", "la-chevron-circle-up", "la-chevron-down", "la-chevron-left", "la-chevron-right", "la-chevron-up", "la-child", "la-chrome", "la-circle", "la-circle-o", "la-circle-o-notch", "la-circle-thin", "la-clipboard", "la-clock-o", "la-clone", "la-close", "la-cloud", "la-cloud-download", "la-cloud-upload", "la-cny", "la-code", "la-code-fork", "la-codepen", "la-coffee", "la-cog", "la-cogs", "la-columns", "la-comment", "la-comment-o", "la-commenting", "la-commenting-o", "la-comments", "la-comments-o", "la-compass", "la-compress", "la-connectdevelop", "la-contao", "la-copy", "la-copyright", "la-creative-commons", "la-credit-card", "la-crop", "la-crosshairs", "la-css3", "la-cube", "la-cubes", "la-cut", "la-cutlery", "la-dashboard", "la-dashcube", "la-database", "la-dedent", "la-delicious", "la-desktop", "la-deviantart", "la-diamond", "la-digg", "la-dollar", "la-dot-circle-o", "la-download", "la-dribbble", "la-dropbox", "la-drupal", "la-edit", "la-eject", "la-ellipsis-v", "la-empire", "la-ge", "la-envelope", "la-envelope-o", "la-envelope-square", "la-eraser", "la-eur", "la-euro", "la-exchange", "la-exclamation", "la-exclamation-circle", "la-exclamation-triangle", "la-expand", "la-expeditedssl", "la-external-link", "la-external-link-square", "la-eye", "la-eye-slash", "la-eyedropper", "la-facebook-f", "la-facebook", "la-facebook-official", "la-facebook-square", "la-fast-backward", "la-fast-forward", "la-fax", "la-female", "la-fighter-jet", "la-file", "la-file-archive-o", "la-file-audio-o", "la-file-code-o", "la-file-excel-o", "la-file-image-o", "la-file-movie-o", "la-file-o", "la-file-pdf-o", "la-file-photo-o", "la-file-picture-o", "la-file-powerpoint-o", "la-file-sound-o", "la-file-text", "la-file-text-o", "la-file-video-o", "la-file-word-o", "la-file-zip-o", "la-files-o", "la-film", "la-filter", "la-fire", "la-fire-extinguisher", "la-firefox", "la-flag", "la-flag-checkered", "la-flag-o", "la-flash", "la-flask", "la-flickr", "la-floppy-o", "la-folder", "la-folder-o", "la-folder-open", "la-folder-open-o", "la-font", "la-fonticons", "la-forumbee", "la-forward", "la-foursquare", "la-frown-o", "la-futbol-o", "la-soccer-ball-o", "la-gamepad", "la-gavel", "la-gbp", "la-gear", "la-gears", "la-genderless", "la-get-pocket", "la-gg", "la-gg-circle", "la-gift", "la-git", "la-git-square", "la-github", "la-github-alt", "la-github-square", "la-glass", "la-globe", "la-google", "la-google-plus", "la-google-plus-square", "la-google-wallet", "la-graduation-cap", "la-gittip", "la-gratipay", "la-group", "la-h-square", "la-hand-grab-o", "la-hand-lizard-o", "la-hand-o-down", "la-hand-o-left", "la-hand-o-right", "la-hand-o-up", "la-hand-paper-o", "la-hand-peace-o", "la-hand-pointer-o", "la-hand-rock-o", "la-hand-scissors-o", "la-hand-spock-o", "la-hand-stop-o", "la-hdd-o", "la-header", "la-headphones", "la-heart", "la-heart-o", "la-heartbeat", "la-history", "la-home", "la-hospital-o", "la-hotel", "la-hourglass", "la-hourglass-1", "la-hourglass-2", "la-hourglass-3", "la-hourglass-end", "la-hourglass-half", "la-hourglass-o", "la-hourglass-start", "la-houzz", "la-html5", "la-i-cursor", "la-ils", "la-image", "la-inbox", "la-indent", "la-industry", "la-info", "la-info-circle", "la-inr", "la-instagram", "la-institution", "la-internet-explorer", "la-ioxhost", "la-italic", "la-joomla", "la-jpy", "la-jsfiddle", "la-key", "la-keyboard-o", "la-krw", "la-language", "la-laptop", "la-lastfm", "la-lastfm-square", "la-leaf", "la-leanpub", "la-legal", "la-lemon-o", "la-level-down", "la-level-up", "la-life-bouy", "la-life-buoy", "la-life-ring", "la-support", "la-life-saver", "la-lightbulb-o", "la-line-chart", "la-link", "la-linkedin", "la-linkedin-square", "la-linux", "la-list", "la-list-alt", "la-list-ol", "la-list-ul", "la-location-arrow", "la-lock", "la-long-arrow-down", "la-long-arrow-left", "la-long-arrow-right", "la-long-arrow-up", "la-magic", "la-magnet", "la-mail-forward", "la-mail-reply", "la-mail-reply-all", "la-male", "la-map", "la-map-marker", "la-map-o", "la-map-pin", "la-map-signs", "la-mars", "la-mars-double", "la-mars-stroke", "la-mars-stroke-h", "la-mars-stroke-v", "la-meanpath", "la-medium", "la-medkit", "la-meh-o", "la-mercury", "la-microphone", "la-microphone-slash", "la-minus", "la-minus-circle", "la-minus-square", "la-minus-square-o", "la-mobile", "la-mobile-phone", "la-money", "la-moon-o", "la-mortar-board", "la-motorcycle", "la-mouse-pointer", "la-music", "la-navicon", "la-neuter", "la-newspaper-o", "la-object-group", "la-object-ungroup", "la-odnoklassniki", "la-odnoklassniki-square", "la-opencart", "la-openid", "la-opera", "la-optin-monster", "la-outdent", "la-pagelines", "la-paint-brush", "la-paper-plane", "la-send", "la-paper-plane-o", "la-send-o", "la-paperclip", "la-paragraph", "la-paste", "la-pause", "la-paw", "la-paypal", "la-pencil", "la-pencil-square", "la-pencil-square-o", "la-phone", "la-phone-square", "la-photo", "la-picture-o", "la-pie-chart", "la-pied-piper", "la-pied-piper-alt", "la-pinterest", "la-pinterest-p", "la-pinterest-square", "la-plane", "la-play", "la-play-circle", "la-play-circle-o", "la-plug", "la-plus", "la-plus-circle", "la-plus-square", "la-plus-square-o", "la-power-off", "la-print", "la-puzzle-piece", "la-qq", "la-qrcode", "la-question", "la-question-circle", "la-quote-left", "la-quote-right", "la-ra", "la-random", "la-rebel", "la-recycle", "la-reddit", "la-reddit-square", "la-refresh", "la-registered", "la-renren", "la-reorder", "la-repeat", "la-reply", "la-reply-all", "la-retweet", "la-rmb", "la-road", "la-rocket", "la-rotate-left", "la-rotate-right", "la-rouble", "la-feed", "la-rss", "la-rss-square", "la-rub", "la-ruble", "la-rupee", "la-save", "la-scissors", "la-search", "la-search-minus", "la-search-plus", "la-sellsy", "la-server", "la-share", "la-share-alt", "la-share-alt-square", "la-share-square", "la-share-square-o", "la-shekel", "la-sheqel", "la-shield", "la-ship", "la-shirtsinbulk", "la-shopping-cart", "la-sign-in", "la-sign-out", "la-signal", "la-simplybuilt", "la-sitemap", "la-skyatlas", "la-skype", "la-slack", "la-sliders", "la-slideshare", "la-smile-o", "la-sort", "la-unsorted", "la-sort-alpha-asc", "la-sort-alpha-desc", "la-sort-amount-asc", "la-sort-amount-desc", "la-sort-asc", "la-sort-up", "la-sort-desc", "la-sort-down", "la-sort-numeric-asc", "la-sort-numeric-desc", "la-soundcloud", "la-space-shuttle", "la-spinner", "la-spoon", "la-spotify", "la-square", "la-square-o", "la-stack-exchange", "la-stack-overflow", "la-star", "la-star-half", "la-star-half-empty", "la-star-half-full", "la-star-half-o", "la-star-o", "la-steam", "la-steam-square", "la-step-backward", "la-step-forward", "la-stethoscope", "la-sticky-note", "la-sticky-note-o", "la-stop", "la-street-view", "la-strikethrough", "la-stumbleupon", "la-stumbleupon-circle", "la-subscript", "la-subway", "la-suitcase", "la-sun-o", "la-superscript", "la-table", "la-tablet", "la-tachometer", "la-tag", "la-tags", "la-tasks", "la-taxi", "la-television", "la-tv", "la-tencent-weibo", "la-terminal", "la-text-height", "la-text-width", "la-th", "la-th-large", "la-th-list", "la-thumb-tack", "la-thumbs-down", "la-thumbs-o-down", "la-thumbs-o-up", "la-thumbs-up", "la-ticket", "la-remove", "la-times", "la-times-circle", "la-times-circle-o", "la-tint", "la-toggle-on", "la-trademark", "la-train", "la-intersex", "la-transgender", "la-transgender-alt", "la-trash", "la-trash-o", "la-tree", "la-trello", "la-tripadvisor", "la-trophy", "la-truck", "la-try", "la-tty", "la-tumblr", "la-tumblr-square", "la-turkish-lira", "la-twitch", "la-twitter", "la-twitter-square", "la-umbrella", "la-underline", "la-undo", "la-university", "la-unlink", "la-unlock", "la-unlock-alt", "la-upload", "la-usd", "la-user", "la-user-md", "la-user-plus", "la-user-secret", "la-user-times", "la-users", "la-venus", "la-venus-double", "la-venus-mars", "la-viacoin", "la-video-camera", "la-vimeo", "la-vimeo-square", "la-vine", "la-vk", "la-volume-down", "la-volume-off", "la-volume-up", "la-warning", "la-wechat", "la-weibo", "la-weixin", "la-whatsapp", "la-wheelchair", "la-wifi", "la-wikipedia-w", "la-windows", "la-won", "la-wordpress", "la-wrench", "la-xing", "la-xing-square", "la-y-combinator", "la-y-combinator-square", "la-yahoo", "la-yc", "la-yc-square", "la-yelp", "la-yen", "la-youtube", "la-youtube-play", "la-youtube-square", "fa-500px", "fa-address-book", "fa-address-book-o", "fa-address-card", "fa-address-card-o", "fa-adjust", "fa-adn", "fa-align-center", "fa-align-justify", "fa-align-left", "fa-align-right", "fa-amazon", "fa-ambulance", "fa-american-sign-language-interpreting", "fa-anchor", "fa-android", "fa-angellist", "fa-angle-double-down", "fa-angle-double-left", "fa-angle-double-right", "fa-angle-double-up", "fa-angle-down", "fa-angle-left", "fa-angle-right", "fa-angle-up", "fa-apple", "fa-archive", "fa-area-chart", "fa-arrow-circle-down", "fa-arrow-circle-left", "fa-arrow-circle-o-down", "fa-arrow-circle-o-left", "fa-arrow-circle-o-right", "fa-arrow-circle-o-up", "fa-arrow-circle-right", "fa-arrow-circle-up", "fa-arrow-down", "fa-arrow-left", "fa-arrow-right", "fa-arrow-up", "fa-arrows", "fa-arrows-alt", "fa-arrows-h", "fa-arrows-v", "fa-assistive-listening-systems", "fa-asterisk", "fa-at", "fa-audio-description", "fa-backward", "fa-balance-scale", "fa-ban", "fa-bandcamp", "fa-bar-chart", "fa-barcode", "fa-bars", "fa-bath", "fa-battery-empty", "fa-battery-full", "fa-battery-half", "fa-battery-quarter", "fa-battery-three-quarters", "fa-bed", "fa-beer", "fa-behance", "fa-behance-square", "fa-bell", "fa-bell-o", "fa-bell-slash", "fa-bell-slash-o", "fa-bicycle", "fa-binoculars", "fa-birthday-cake", "fa-bitbucket", "fa-bitbucket-square", "fa-black-tie", "fa-blind", "fa-bluetooth", "fa-bluetooth-b", "fa-bold", "fa-bolt", "fa-bomb", "fa-book", "fa-bookmark", "fa-bookmark-o", "fa-braille", "fa-briefcase", "fa-btc", "fa-bug", "fa-building", "fa-building-o", "fa-bullhorn", "fa-bullseye", "fa-bus", "fa-buysellads", "fa-calculator", "fa-calendar", "fa-calendar-check-o", "fa-calendar-minus-o", "fa-calendar-o", "fa-calendar-plus-o", "fa-calendar-times-o", "fa-camera", "fa-camera-retro", "fa-car", "fa-caret-down", "fa-caret-left", "fa-caret-right", "fa-caret-square-o-down", "fa-caret-square-o-left", "fa-caret-square-o-right", "fa-caret-square-o-up", "fa-caret-up", "fa-cart-arrow-down", "fa-cart-plus", "fa-cc", "fa-cc-amex", "fa-cc-diners-club", "fa-cc-discover", "fa-cc-jcb", "fa-cc-mastercard", "fa-cc-paypal", "fa-cc-stripe", "fa-cc-visa", "fa-certificate", "fa-chain-broken", "fa-check", "fa-check-circle", "fa-check-circle-o", "fa-check-square", "fa-check-square-o", "fa-chevron-circle-down", "fa-chevron-circle-left", "fa-chevron-circle-right", "fa-chevron-circle-up", "fa-chevron-down", "fa-chevron-left", "fa-chevron-right", "fa-chevron-up", "fa-child", "fa-chrome", "fa-circle", "fa-circle-o", "fa-circle-o-notch", "fa-circle-thin", "fa-clipboard", "fa-clock-o", "fa-clone", "fa-cloud", "fa-cloud-download", "fa-cloud-upload", "fa-code", "fa-code-fork", "fa-codepen", "fa-codiepie", "fa-coffee", "fa-cog", "fa-cogs", "fa-columns", "fa-comment", "fa-comment-o", "fa-commenting", "fa-commenting-o", "fa-comments", "fa-comments-o", "fa-compass", "fa-compress", "fa-connectdevelop", "fa-contao", "fa-copyright", "fa-creative-commons", "fa-credit-card", "fa-credit-card-alt", "fa-crop", "fa-crosshairs", "fa-css3", "fa-cube", "fa-cubes", "fa-cutlery", "fa-dashcube", "fa-database", "fa-deaf", "fa-delicious", "fa-desktop", "fa-deviantart", "fa-diamond", "fa-digg", "fa-dot-circle-o", "fa-download", "fa-dribbble", "fa-dropbox", "fa-drupal", "fa-edge", "fa-eercast", "fa-eject", "fa-ellipsis-h", "fa-ellipsis-v", "fa-empire", "fa-envelope", "fa-envelope-o", "fa-envelope-open", "fa-envelope-open-o", "fa-envelope-square", "fa-envira", "fa-eraser", "fa-etsy", "fa-eur", "fa-exchange", "fa-exclamation", "fa-exclamation-circle", "fa-exclamation-triangle", "fa-expand", "fa-expeditedssl", "fa-external-link", "fa-external-link-square", "fa-eye", "fa-eye-slash", "fa-eyedropper", "fa-facebook", "fa-facebook-official", "fa-facebook-square", "fa-fast-backward", "fa-fast-forward", "fa-fax", "fa-female", "fa-fighter-jet", "fa-file", "fa-file-archive-o", "fa-file-audio-o", "fa-file-code-o", "fa-file-excel-o", "fa-file-image-o", "fa-file-o", "fa-file-pdf-o", "fa-file-powerpoint-o", "fa-file-text", "fa-file-text-o", "fa-file-video-o", "fa-file-word-o", "fa-files-o", "fa-film", "fa-filter", "fa-fire", "fa-fire-extinguisher", "fa-firefox", "fa-first-order", "fa-flag", "fa-flag-checkered", "fa-flag-o", "fa-flask", "fa-flickr", "fa-floppy-o", "fa-folder", "fa-folder-o", "fa-folder-open", "fa-folder-open-o", "fa-font", "fa-font-awesome", "fa-fonticons", "fa-fort-awesome", "fa-forumbee", "fa-forward", "fa-foursquare", "fa-free-code-camp", "fa-frown-o", "fa-futbol-o", "fa-gamepad", "fa-gavel", "fa-gbp", "fa-genderless", "fa-get-pocket", "fa-gg", "fa-gg-circle", "fa-gift", "fa-git", "fa-git-square", "fa-github", "fa-github-alt", "fa-github-square", "fa-gitlab", "fa-glass", "fa-glide", "fa-glide-g", "fa-globe", "fa-google", "fa-google-plus", "fa-google-plus-official", "fa-google-plus-square", "fa-google-wallet", "fa-graduation-cap", "fa-gratipay", "fa-grav", "fa-h-square", "fa-hacker-news", "fa-hand-lizard-o", "fa-hand-o-down", "fa-hand-o-left", "fa-hand-o-right", "fa-hand-o-up", "fa-hand-paper-o", "fa-hand-peace-o", "fa-hand-pointer-o", "fa-hand-rock-o", "fa-hand-scissors-o", "fa-hand-spock-o", "fa-handshake-o", "fa-hashtag", "fa-hdd-o", "fa-header", "fa-headphones", "fa-heart", "fa-heart-o", "fa-heartbeat", "fa-history", "fa-home", "fa-hospital-o", "fa-hourglass", "fa-hourglass-end", "fa-hourglass-half", "fa-hourglass-o", "fa-hourglass-start", "fa-houzz", "fa-html5", "fa-i-cursor", "fa-id-badge", "fa-id-card", "fa-id-card-o", "fa-ils", "fa-imdb", "fa-inbox", "fa-indent", "fa-industry", "fa-info", "fa-info-circle", "fa-inr", "fa-instagram", "fa-internet-explorer", "fa-ioxhost", "fa-italic", "fa-joomla", "fa-jpy", "fa-jsfiddle", "fa-key", "fa-keyboard-o", "fa-krw", "fa-language", "fa-laptop", "fa-lastfm", "fa-lastfm-square", "fa-leaf", "fa-leanpub", "fa-lemon-o", "fa-level-down", "fa-level-up", "fa-life-ring", "fa-lightbulb-o", "fa-line-chart", "fa-link", "fa-linkedin", "fa-linkedin-square", "fa-linode", "fa-linux", "fa-list", "fa-list-alt", "fa-list-ol", "fa-list-ul", "fa-location-arrow", "fa-lock", "fa-long-arrow-down", "fa-long-arrow-left", "fa-long-arrow-right", "fa-long-arrow-up", "fa-low-vision", "fa-magic", "fa-magnet", "fa-male", "fa-map", "fa-map-marker", "fa-map-o", "fa-map-pin", "fa-map-signs", "fa-mars", "fa-mars-double", "fa-mars-stroke", "fa-mars-stroke-h", "fa-mars-stroke-v", "fa-maxcdn", "fa-meanpath", "fa-medium", "fa-medkit", "fa-meetup", "fa-meh-o", "fa-mercury", "fa-microchip", "fa-microphone", "fa-microphone-slash", "fa-minus", "fa-minus-circle", "fa-minus-square", "fa-minus-square-o", "fa-mixcloud", "fa-mobile", "fa-modx", "fa-money", "fa-moon-o", "fa-motorcycle", "fa-mouse-pointer", "fa-music", "fa-neuter", "fa-newspaper-o", "fa-object-group", "fa-object-ungroup", "fa-odnoklassniki", "fa-odnoklassniki-square", "fa-opencart", "fa-openid", "fa-opera", "fa-optin-monster", "fa-outdent", "fa-pagelines", "fa-paint-brush", "fa-paper-plane", "fa-paper-plane-o", "fa-paperclip", "fa-paragraph", "fa-pause", "fa-pause-circle", "fa-pause-circle-o", "fa-paw", "fa-paypal", "fa-pencil", "fa-pencil-square", "fa-pencil-square-o", "fa-percent", "fa-phone", "fa-phone-square", "fa-picture-o", "fa-pie-chart", "fa-pied-piper", "fa-pied-piper-alt", "fa-pied-piper-pp", "fa-pinterest", "fa-pinterest-p", "fa-pinterest-square", "fa-plane", "fa-play", "fa-play-circle", "fa-play-circle-o", "fa-plug", "fa-plus", "fa-plus-circle", "fa-plus-square", "fa-plus-square-o", "fa-podcast", "fa-power-off", "fa-print", "fa-product-hunt", "fa-puzzle-piece", "fa-qq", "fa-qrcode", "fa-question", "fa-question-circle", "fa-question-circle-o", "fa-quora", "fa-quote-left", "fa-quote-right", "fa-random", "fa-ravelry", "fa-rebel", "fa-recycle", "fa-reddit", "fa-reddit-alien", "fa-reddit-square", "fa-refresh", "fa-registered", "fa-renren", "fa-repeat", "fa-reply", "fa-reply-all", "fa-retweet", "fa-road", "fa-rocket", "fa-rss", "fa-rss-square", "fa-rub", "fa-safari", "fa-scissors", "fa-scribd", "fa-search", "fa-search-minus", "fa-search-plus", "fa-sellsy", "fa-server", "fa-share", "fa-share-alt", "fa-share-alt-square", "fa-share-square", "fa-share-square-o", "fa-shield", "fa-ship", "fa-shirtsinbulk", "fa-shopping-bag", "fa-shopping-basket", "fa-shopping-cart", "fa-shower", "fa-sign-in", "fa-sign-language", "fa-sign-out", "fa-signal", "fa-simplybuilt", "fa-sitemap", "fa-skyatlas", "fa-skype", "fa-slack", "fa-sliders", "fa-slideshare", "fa-smile-o", "fa-snapchat", "fa-snapchat-ghost", "fa-snapchat-square", "fa-snowflake-o", "fa-sort", "fa-sort-alpha-asc", "fa-sort-alpha-desc", "fa-sort-amount-asc", "fa-sort-amount-desc", "fa-sort-asc", "fa-sort-desc", "fa-sort-numeric-asc", "fa-sort-numeric-desc", "fa-soundcloud", "fa-space-shuttle", "fa-spinner", "fa-spoon", "fa-spotify", "fa-square", "fa-square-o", "fa-stack-exchange", "fa-stack-overflow", "fa-star", "fa-star-half", "fa-star-half-o", "fa-star-o", "fa-steam", "fa-steam-square", "fa-step-backward", "fa-step-forward", "fa-stethoscope", "fa-sticky-note", "fa-sticky-note-o", "fa-stop", "fa-stop-circle", "fa-stop-circle-o", "fa-street-view", "fa-strikethrough", "fa-stumbleupon", "fa-stumbleupon-circle", "fa-subscript", "fa-subway", "fa-suitcase", "fa-sun-o", "fa-superpowers", "fa-superscript", "fa-table", "fa-tablet", "fa-tachometer", "fa-tag", "fa-tags", "fa-tasks", "fa-taxi", "fa-telegram", "fa-television", "fa-tencent-weibo", "fa-terminal", "fa-text-height", "fa-text-width", "fa-th", "fa-th-large", "fa-th-list", "fa-themeisle", "fa-thermometer-empty", "fa-thermometer-full", "fa-thermometer-half", "fa-thermometer-quarter", "fa-thermometer-three-quarters", "fa-thumb-tack", "fa-thumbs-down", "fa-thumbs-o-down", "fa-thumbs-o-up", "fa-thumbs-up", "fa-ticket", "fa-times", "fa-times-circle", "fa-times-circle-o", "fa-tint", "fa-toggle-off", "fa-toggle-on", "fa-trademark", "fa-train", "fa-transgender", "fa-transgender-alt", "fa-trash", "fa-trash-o", "fa-tree", "fa-trello", "fa-tripadvisor", "fa-trophy", "fa-truck", "fa-try", "fa-tty", "fa-tumblr", "fa-tumblr-square", "fa-twitch", "fa-twitter", "fa-twitter-square", "fa-umbrella", "fa-underline", "fa-undo", "fa-universal-access", "fa-university", "fa-unlock", "fa-unlock-alt", "fa-upload", "fa-usb", "fa-usd", "fa-user", "fa-user-circle", "fa-user-circle-o", "fa-user-md", "fa-user-o", "fa-user-plus", "fa-user-secret", "fa-user-times", "fa-users", "fa-venus", "fa-venus-double", "fa-venus-mars", "fa-viacoin", "fa-viadeo", "fa-viadeo-square", "fa-video-camera", "fa-vimeo", "fa-vimeo-square", "fa-vine", "fa-vk", "fa-volume-control-phone", "fa-volume-down", "fa-volume-off", "fa-volume-up", "fa-weibo", "fa-weixin", "fa-whatsapp", "fa-wheelchair", "fa-wheelchair-alt", "fa-wifi", "fa-wikipedia-w", "fa-window-close", "fa-window-close-o", "fa-window-maximize", "fa-window-minimize", "fa-window-restore", "fa-windows", "fa-wordpress", "fa-wpbeginner", "fa-wpexplorer", "fa-wpforms", "fa-wrench", "fa-xing", "fa-xing-square", "fa-y-combinator", "fa-yahoo", "fa-yelp", "fa-yoast", "fa-youtube", "fa-youtube-play", "fa-youtube-square", "fa-bank", "fa-bars", "fa-battery-0", "fa-battery-4", "fa-cab", "fa-file-sound-o", "fa-flash", "fa-group", "fa-hotel", "fa-hourglass-3", "fa-image", "fa-mail-reply-all", "fa-mobile-phone", "fa-mortar-board", "fa-navicon", "fa-rouble", "fa-ruble", "fa-save", "fa-send-o", "fa-sheqel", "fa-signing", "fa-sort-up", "fa-star-half-empty", "fa-support", "fa-times-rectangle-o", "fa-toggle-up", "fa-turkish-lira", "fa-wechat", "fa-yen", "fa-battery-1", "fa-dollar", "fa-drivers-license", "fa-euro", "fa-fa", "fa-file-photo-o", "fa-file-zip-o", "fa-hard-of-hearing", "fa-id-card", "fa-institution", "fa-life-saver", "fa-reorder", "fa-ra", "fa-resistance", "fa-rupee", "fa-soccer-ball-o", "fa-sort-down", "fa-star-half-full", "fa-thermometer-2", "fa-thermometer-full", "fa-tv", "fa-unsorted", "fa-yc", "fa-asl-interpreting", "fa-bar-chart-o", "fa-bathtub", "fa-battery-2", "fa-dashboard", "fa-deafness", "fa-drivers-license-o", "fa-edit", "fa-file-movie-o", "fa-file-picture-o", "fa-gear", "fa-google-plus-circle", "fa-hourglass-1", "fa-legal", "fa-life-bouy", "fa-mail-forward", "fa-paste", "fa-photo", "fa-rotate-left", "fa-s15", "fa-thermometer", "fa-thermometer-3", "fa-toggle-down", "fa-unlink", "fa-vcard", "fa-won", "fa-yc-square", "fa-automobile", "fa-battery", "fa-battery-3", "fa-bitcoin", "fa-close", "fa-cny", "fa-dedent", "fa-facebook-f", "fa-gears", "fa-gittip", "fa-hand-grab-o", "fa-hand-stop-o", "fa-hourglass-2", "fa-intersex", "fa-life-buoy", "fa-remove", "fa-rmb", "fa-rotate-right", "fa-send", "fa-shekel", "fa-thermometer-0", "fa-thermometer-4", "fa-times-rectangle", "fa-toggle-left", "fa-toggle-right", "fa-vcard-o", "fa-warning", "fa-y-combinator-square");
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
        if (!is_user_logged_in()) {
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
    function calc_listing_expiry_date($start_date = NULL)
    {

        $exp_days = get_directorist_option('listing_expire_in_days', 999, 999);
        // Current time
        $start_date = !empty($start_date) ? $start_date : current_time('mysql');
        // Calculate new date
        $date = new DateTime($start_date);
        $date->add(new DateInterval("P{$exp_days}D")); // set the interval in days
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
    $p = sprintf("<span class='atbd_meta atbd_listing_price'>%s</span>", $price);
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
function atbdp_listings_count_by_category($term_id)
{
    $args = array(
        'fields' => 'ids',
        'posts_per_page' => -1,
        'post_type' => ATBDP_POST_TYPE,
        'post_status' => 'publish',
        'tax_query' => array(
            array(
                'taxonomy' => ATBDP_CATEGORY,
                'field' => 'term_id',
                'terms' => $term_id,
                'include_children' => true
            )
        ),
        'meta_query' => apply_filters('atbdp_listings_with_category_meta_query', array(
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
function atbdp_listings_count_by_location($term_id)
{

    $args = array(
        'fields' => 'ids',
        'posts_per_page' => -1,
        'post_type' => ATBDP_POST_TYPE,
        'post_status' => 'publish',
        'tax_query' => array(
            array(
                'taxonomy' => ATBDP_LOCATION,
                'field' => 'term_id',
                'terms' => $term_id,
                'include_children' => true
            )
        ),
        'meta_query' => apply_filters('atbdp_listings_with_location_meta_query', array(
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
        'title-asc' => __("A to Z ( title )", 'directorist'),
        'title-desc' => __("Z to A ( title )", 'directorist'),
        'date-desc' => __("Latest listings", 'directorist'),
        'date-asc' => __("Oldest listings", 'directorist'),
        'views-desc' => __("Popular listings", 'directorist'),
        'price-asc' => __("Price ( low to high )", 'directorist'),
        'price-desc' => __("Price ( high to low )", 'directorist'),
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
        'post_type' => ATBDP_POST_TYPE,
        'post_status' => 'publish',
        'meta_key' => '_price'
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

    if (is_user_logged_in()) {

        if ($post_id == 0) {
            global $post;
            $post_id = $post->ID;
        }

        $favourites = (array)get_user_meta(get_current_user_id(), 'atbdp_favourites', true);

        if (in_array($post_id, $favourites)) {
            return '<span class="' . atbdp_icon_type() . '-heart" style="color: red"></span><a href="javascript:void(0)" class="atbdp-favourites" data-post_id="' . $post_id . '">' . __('Favorite', 'directorist') . '</a>';
        } else {
            return '<span class="' . atbdp_icon_type() . '-heart-o"></span><a href="javascript:void(0)" class="atbdp-favourites" data-post_id="' . $post_id . '">' . __('Favorite', 'directorist') . '</a>';
        }

    } else {

        return '<span class="' . atbdp_icon_type() . '-heart-o"></span><a href="javascript:void(0)" class="atbdp-require-login">' . __('Favorite', 'directorist') . '</a>';

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

    if (is_user_logged_in()) {

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
        $enable_new_listing = get_directorist_option('display_new_badge_cart', 1);
        $each_hours = 60 * 60 * 24; // seconds in a day
        $s_date1 = strtotime(current_time('mysql')); // seconds for date 1
        $s_date2 = strtotime($post->post_date); // seconds for date 2
        $s_date_diff = abs($s_date1 - $s_date2); // different of the two dates in seconds
        $days = round($s_date_diff / $each_hours); // divided the different with second in a day
        $new = '<span class="atbd_badge atbd_badge_new">' . $new_badge_text . '</span>';
        if ($days <= (int)$new_listing_time) {
            if (!empty($enable_new_listing)) {
                return $new;
            }

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


function listing_view_by_grid($all_listings, $paginate, $is_disable_price)
{
    ?>
    <div class="col-lg-12">
        <div class="row" <?php echo (get_directorist_option('grid_view_as', 'normal_grid') !== 'masonry_grid') ? '' : 'data-uk-grid'; ?>>


            <?php if ($all_listings->have_posts()) {
                while ($all_listings->have_posts()) {
                    $all_listings->the_post();
                    $cats = get_the_terms(get_the_ID(), ATBDP_CATEGORY);
                    $locs = get_the_terms(get_the_ID(), ATBDP_LOCATION);
                    $featured = get_post_meta(get_the_ID(), '_featured', true);
                    $price = get_post_meta(get_the_ID(), '_price', true);
                    $price_range = get_post_meta(get_the_ID(), '_price_range', true);
                    $atbd_listing_pricing = get_post_meta(get_the_ID(), '_atbd_listing_pricing', true);
                    $listing_img = get_post_meta(get_the_ID(), '_listing_img', true);
                    $listing_prv_img = get_post_meta(get_the_ID(), '_listing_prv_img', true);
                    $excerpt = get_post_meta(get_the_ID(), '_excerpt', true);
                    $tagline = get_post_meta(get_the_ID(), '_tagline', true);
                    $address = get_post_meta(get_the_ID(), '_address', true);
                    $phone_number = get_post_meta(get_the_Id(), '_phone', true);
                    $email = get_post_meta(get_the_Id(), '_email', true);
                    $web = get_post_meta(get_the_Id(), '_website', true);
                    $category = get_post_meta(get_the_Id(), '_admin_category_select', true);
                    $post_view = get_post_meta(get_the_Id(), '_atbdp_post_views_count', true);
                    $hide_contact_info = get_post_meta(get_the_ID(), '_hide_contact_info', true);
                    $disable_contact_info = get_directorist_option('disable_contact_info', 0);
                    $display_title = get_directorist_option('display_title', 1);
                    $display_review = get_directorist_option('enable_review', 1);
                    $display_price = get_directorist_option('display_price', 1);
                    $display_category = get_directorist_option('display_category', 1);
                    $display_view_count = get_directorist_option('display_view_count', 1);
                    $display_mark_as_fav = get_directorist_option('display_mark_as_fav', 1);
                    $display_author_image = get_directorist_option('display_author_image', 1);
                    $display_publish_date = get_directorist_option('display_publish_date', 1);
                    $display_contact_info = get_directorist_option('display_contact_info', 1);
                    $display_feature_badge_cart = get_directorist_option('display_feature_badge_cart', 1);
                    $display_popular_badge_cart = get_directorist_option('display_popular_badge_cart', 1);
                    $popular_badge_text = get_directorist_option('popular_badge_text', 'Popular');
                    $feature_badge_text = get_directorist_option('feature_badge_text', 'Featured');
                    $enable_tagline = get_directorist_option('enable_tagline');
                    $enable_excerpt = get_directorist_option('enable_excerpt');
                    $address_location = get_directorist_option('address_location', 'location');
                    /*Code for Business Hour Extensions*/
                    $bdbh = get_post_meta(get_the_ID(), '_bdbh', true);
                    $enable247hour = get_post_meta(get_the_ID(), '_enable247hour', true);
                    $business_hours = !empty($bdbh) ? atbdp_sanitize_array($bdbh) : array(); // arrays of days and times if exist
                    $author_id = get_the_author_meta('ID');
                    $u_pro_pic = get_user_meta($author_id, 'pro_pic', true);
                    $u_pro_pic = wp_get_attachment_image_src($u_pro_pic, 'thumbnail');
                    $avata_img = get_avatar($author_id, apply_filters('atbdp_avatar_size', 32));
                    $thumbnail_cropping = get_directorist_option('thumbnail_cropping', 1);
                    $crop_width = get_directorist_option('crop_width', 360);
                    $crop_height = get_directorist_option('crop_height', 300);
                    $display_tagline_field = get_directorist_option('display_tagline_field', 0);
                    $display_pricing_field = get_directorist_option('display_pricing_field', 1);
                    $display_excerpt_field = get_directorist_option('display_excerpt_field', 0);
                    $display_address_field = get_directorist_option('display_address_field', 1);
                    $display_phone_field = get_directorist_option('display_phone_field', 1);
                    $display_preview = get_directorist_option('display_preview_image', 1);
                    $display_email = get_directorist_option('display_email', 0);
                    $display_web_link = get_directorist_option('display_web_link', 0);
                    if (!empty($listing_prv_img)) {

                        if ($thumbnail_cropping) {

                            $prv_image = atbdp_image_cropping($listing_prv_img, $crop_width, $crop_height, true, 100)['url'];

                        } else {
                            $prv_image = wp_get_attachment_image_src($listing_prv_img, 'large')[0];
                        }

                    }
                    if (!empty($listing_img[0])) {
                        if ($thumbnail_cropping) {
                            $gallery_img = atbdp_image_cropping($listing_img[0], $crop_width, $crop_height, true, 100)['url'];

                        } else {
                            $gallery_img = wp_get_attachment_image_src($listing_img[0], 'medium')[0];
                        }

                    }
                    $columns = get_directorist_option('all_listing_columns', 3);
                    $column_width = 100 / $columns . '%';
                    /*Code for Business Hour Extensions*/
                    ?>
                    <div class="atbdp_column">
                        <div class="atbd_single_listing atbd_listing_card <?php echo get_directorist_option('info_display_in_single_line', 0) ? 'atbd_single_line_card_info' : ''; ?>">
                            <article
                                    class="atbd_single_listing_wrapper <?php echo ($featured) ? 'directorist-featured-listings' : ''; ?>">
                                <figure class="atbd_listing_thumbnail_area"
                                        style=" <?php echo empty($display_preview) ? 'display:none' : '' ?>">
                                    <div class="atbd_listing_image">
                                        <?php
                                        $disable_single_listing = get_directorist_option('disable_single_listing');
                                        if (empty($disable_single_listing)){
                                        ?>
                                        <a href="<?php echo esc_url(get_post_permalink(get_the_ID())); ?>">
                                            <?php
                                            }
                                            $default_image = get_directorist_option('default_preview_image', ATBDP_PUBLIC_ASSETS . 'images/grid.jpg');
                                            if (!empty($listing_prv_img)) {
                                                echo '<img src="' . esc_url($prv_image) . '" alt="' . esc_html(stripslashes(get_the_title())) . '">';
                                            }
                                            if (!empty($listing_img[0]) && empty($listing_prv_img)) {
                                                echo '<img src="' . esc_url($gallery_img) . '" alt="' . esc_html(stripslashes(get_the_title())) . '">';
                                            }
                                            if (empty($listing_img[0]) && empty($listing_prv_img) && !empty($default_image)) {
                                                echo '<img src="' . $default_image . '" alt="' . esc_html(stripslashes(get_the_title())) . '">';
                                            }
                                            if (empty($disable_single_listing)) {
                                                echo '</a>';
                                            }
                                            if (!empty($display_author_image)) {
                                                $author = get_userdata($author_id);
                                                $class = !empty($author->first_name && $author->last_name)?'atbd_tooltip':'';
                                                ?>
                                                <div class="atbd_author">
                                                    <a href="<?php echo ATBDP_Permalink::get_user_profile_page_link($author_id); ?>"
                                                       class="<?php echo $class; ?>"
                                                       aria-label="<?php echo $author->first_name . ' ' . $author->last_name; ?>"><?php if (empty($u_pro_pic)) {
                                                            echo $avata_img;
                                                        }
                                                        if (!empty($u_pro_pic)) { ?>
                                                            <img
                                                            src="<?php echo esc_url($u_pro_pic[0]); ?>"
                                                            alt="Author Image"><?php } ?>
                                                    </a>
                                                </div>
                                            <?php } ?>
                                    </div>


                                    <?php
                                    $plan_hours = true;
                                    $u_badge_html = '<span class="atbd_upper_badge">';
                                    if (is_fee_manager_active()) {
                                        $plan_hours = is_plan_allowed_business_hours(get_post_meta(get_the_ID(), '_fm_plans', true));
                                    }
                                    if (is_business_hour_active() && $plan_hours && empty($disable_bz_hour_listing)) {
                                        //lets check is it 24/7
                                        if ('2.2.6' > BDBH_VERSION) {
                                            ?>
                                            <style>
                                                .atbd_badge_close, .atbd_badge_open {
                                                    position: absolute;
                                                    left: 15px;
                                                    top: 15px;
                                                }
                                            </style>
                                            <?php
                                        }
                                        $open = get_directorist_option('open_badge_text', __('Open Now', 'directorist'));
                                        if (!empty($enable247hour)) {
                                            $u_badge_html .= ' <span class="atbd_badge atbd_badge_open">' . $open . '</span>';

                                        } else {
                                            $u_badge_html .= BD_Business_Hour()->show_business_open_close($business_hours);

                                        }
                                    }
                                    $u_badge_html .= '</span>';

                                    /**
                                     * @since 5.0
                                     */
                                    echo apply_filters('atbdp_upper_badges', $u_badge_html);


                                    //Start lower badge
                                    $l_badge_html = '<span class="atbd_lower_badge">';

                                    if ($featured && !empty($display_feature_badge_cart)) {
                                        $l_badge_html .= '<span class="atbd_badge atbd_badge_featured">' . $feature_badge_text . '</span>';
                                    }

                                    $popular_listing_id = atbdp_popular_listings(get_the_ID());
                                    $badge = '<span class="atbd_badge atbd_badge_popular">' . $popular_badge_text . '</span>';
                                    if ($popular_listing_id === get_the_ID()) {
                                        $l_badge_html .= $badge;
                                    }
                                    //print the new badge
                                    $l_badge_html .= new_badge();
                                    $l_badge_html .= '</span>';

                                    /**
                                     * @since 5.0
                                     */
                                    echo apply_filters('atbdp_grid_lower_badges', $l_badge_html);
                                    if (!empty($display_mark_as_fav)) {
                                        echo atbdp_listings_mark_as_favourite(get_the_ID());
                                    }
                                    ?>
                                </figure>
                                <div class="atbd_listing_info">
                                    <div class="atbd_content_upper">
                                        <?php if (!empty($display_title)) { ?>
                                            <h4 class="atbd_listing_title">
                                                <?php
                                                if (empty($disable_single_listing)) {
                                                    ?>
                                                    <a href="<?php echo esc_url(get_post_permalink(get_the_ID())); ?>"><?php echo esc_html(stripslashes(get_the_title())); ?></a>
                                                    <?php
                                                } else {
                                                    echo esc_html(stripslashes(get_the_title()));
                                                } ?>
                                            </h4>
                                        <?php }
                                        if (!empty($tagline) && !empty($enable_tagline) && !empty($display_tagline_field)) {

                                            ?>

                                            <p class="atbd_listing_tagline"><?php echo esc_html(stripslashes($tagline)); ?></p>
                                        <?php }
                                        /**
                                         * Fires after the title and sub title of the listing is rendered
                                         *
                                         *
                                         * @since 1.0.0
                                         */
                                        do_action('atbdp_after_listing_tagline');
                                        ?>

                                        <?php
                                        $meta_html = '';
                                        if (!empty($display_review) || !empty($display_price)) { ?>

                                            <?php
                                            $meta_html .= '<div class="atbd_listing_meta">';
                                            $average = ATBDP()->review->get_average(get_the_ID());
                                            $meta_html .= '<span class="atbd_meta atbd_listing_rating">' . $average . '<i class="' . atbdp_icon_type() . '-star"></i>
        </span>';
                                            $atbd_listing_pricing = !empty($atbd_listing_pricing) ? $atbd_listing_pricing : '';
                                            if (!empty($display_price) && !empty($display_pricing_field)) {
                                                if (!empty($price_range) && ('range' === $atbd_listing_pricing)) {
                                                    $output = atbdp_display_price_range($price_range);
                                                    $meta_html .= $output;
                                                } else {
                                                    $meta_html .= atbdp_display_price($price, $is_disable_price, $currency = null, $symbol = null, $c_position = null, $echo = false);
                                                }
                                            }
                                            /**
                                             * Fires after the price of the listing is rendered
                                             *
                                             *
                                             * @since 3.1.0
                                             */
                                            do_action('atbdp_after_listing_price');
                                            $meta_html .= '</div>';
                                        }
                                        /**
                                         * @since 5.0
                                         * universal action to fire after the price
                                         */
                                        echo apply_filters('atbdp_listings_review_price', $meta_html);
                                        ?>
                                        <?php if (!empty($display_contact_info) || !empty($display_publish_date) || !empty($display_emai) || !empty($display_web_link)) { ?>
                                            <div class="atbd_listing_data_list">
                                                <ul>
                                                    <?php
                                                    /**
                                                     * @since 4.7.6
                                                     */
                                                    do_action('atbdp_listings_before_location');

                                                    if (!empty($display_contact_info)) {
                                                        if (!empty($address) && 'contact' == $address_location && !empty($display_address_field)) { ?>
                                                            <li><p>
                                                                    <span class="<?php atbdp_icon_type(true); ?>-map-marker"></span><?php echo esc_html(stripslashes($address)); ?>
                                                                </p></li>
                                                        <?php } elseif (!empty($locs) && 'location' == $address_location) {
                                                            $local_names = array();
                                                            $locals = array();
                                                            foreach ($locs as $term) {
                                                                $local_names[$term->term_id] = $term->parent == 0 ? $term->slug : $term->slug;
                                                                ksort($local_names);
                                                                $locals = array_reverse($local_names);
                                                            }
                                                            $output = array();
                                                            foreach ($locals as $location) {
                                                                $term = get_term_by('slug', $location, ATBDP_LOCATION);
                                                                $link = ATBDP_Permalink::atbdp_get_location_page($term);
                                                                $space = str_repeat(' ', 1);
                                                                $output[] = "{$space}<a href='{$link}'>{$term->name}</a>";
                                                            }
                                                            ?>
                                                            <li>
                                                                <p>

                                                    <span>
                                                    <?php echo "<span class='" . atbdp_icon_type() . "-map-marker'></span>" . join(',', $output); ?>
                                                </span>
                                                                </p>
                                                            </li>
                                                        <?php }
                                                        /**
                                                         * @since 4.7.6
                                                         */
                                                        do_action('atbdp_listings_before_phone');
                                                        ?>
                                                        <?php if (!empty($phone_number) && !empty($display_phone_field)) { ?>
                                                            <li><p>
                                                                    <span class="
<?php atbdp_icon_type(true); ?>-phone"></span><a href="tel:<?php echo esc_html(stripslashes($phone_number)); ?>"><?php echo esc_html(stripslashes($phone_number)); ?></a>

                                                                </p></li>
                                                            <?php
                                                        }
                                                    }
                                                    /**
                                                     * @since 4.7.6
                                                     */
                                                    do_action('atbdp_listings_before_post_date');

                                                    if (!empty($display_publish_date)) { ?>
                                                        <li><p><span class="
<?php atbdp_icon_type(true); ?>-clock-o"></span><?php
                                                                $publish_date_format = get_directorist_option('publish_date_format', 'time_ago');
                                                                if ('time_ago' === $publish_date_format) {
                                                                    printf(__('Posted %s ago', 'directorist'), human_time_diff(get_the_time('U'), current_time('timestamp')));
                                                                } else {
                                                                    echo get_the_date();
                                                                }
                                                                ?></p></li>
                                                    <?php }
                                                    /**
                                                     * @since 4.7.6
                                                     */
                                                    do_action('atbdp_listings_after_post_date');
                                                    if (!empty($email && $display_email)):
                                                        echo '<li><p><span class="' . atbdp_icon_type() . '-envelope"></span><a target="_top" href="mailto:' . $email . '">' . $email . '</a></p></li>';
                                                    endif;
                                                    if (!empty($web && $display_web_link)):
                                                        ?>
                                                        <li><p>
                                                                <span class="<?php atbdp_icon_type(true); ?>-globe"></span>
                                                                <a target="_blank" href="<?php echo esc_url($web); ?>"
                                                                    <?php echo !empty($use_nofollow) ? 'rel="nofollow"' : ''; ?>><?php echo esc_html($web); ?></a>
                                                            </p></li>
                                                    <?php
                                                    endif;
                                                    ?>
                                                </ul>
                                            </div><!-- End atbd listing meta -->
                                            <?php
                                        }
                                        if (!empty($excerpt) && !empty($enable_excerpt) && !empty($display_excerpt_field)) {
                                            $excerpt_limit = get_directorist_option('excerpt_limit', 20);
                                            $display_readmore = get_directorist_option('display_readmore', 0);
                                            $readmore_text = get_directorist_option('readmore_text', __('Read More', 'directorist'));
                                            ?>
                                            <p class="atbd_excerpt_content"><?php echo esc_html(stripslashes(wp_trim_words($excerpt, $excerpt_limit)));

                                            /**
                                             * @since 5.0.9
                                             */
                                            do_action('atbdp_listings_after_exerpt');
                                            if (!empty($display_readmore)) {
                                                ?><a
                                                href="<?php the_permalink(); ?>"><?php printf(__(' %s', 'directorist'), $readmore_text); ?></a></p>
                                            <?php }
                                        } ?>
                                    </div><!-- end ./atbd_content_upper -->
                                    <?php 
                                     $catViewCount = '';
                                    if (!empty($display_category) || !empty($display_view_count)) {
                                        $catViewCount .= '<div class="atbd_listing_bottom_content">';
                                        if (!empty($display_category)) {
                                            if (!empty($cats)) {
                                                $totalTerm = count($cats);
                                                $catViewCount .= '<div class="atbd_content_left">';
                                                $catViewCount .= '<div class="atbd_listting_category">';
                                                $catViewCount .= '<a href="' . ATBDP_Permalink::atbdp_get_category_page($cats[0]) . '">';
                                                $catViewCount .= '<span class="' . atbdp_icon_type() . '-tags"></span>';
                                                $catViewCount .= $cats[0]->name;
                                                $catViewCount .= '</a>';
                                                if ($totalTerm > 1) {
                                                    $totalTerm = $totalTerm - 1;
                                                    $catViewCount .= '<div class="atbd_cat_popup">';
                                                    $catViewCount .= '<span>+' . $totalTerm . '</span>';
                                                    $catViewCount .= '<div class="atbd_cat_popup_wrapper">';
                                                    $output = array();
                                                    foreach (array_slice($cats, 1) as $cat) {
                                                        $link = ATBDP_Permalink::atbdp_get_category_page($cat);
                                                        $space = str_repeat(' ', 1);
                                                        $output [] = "{$space}<span><a href='{$link}'>{$cat->name}<span>,</span></a></span>";
                                                    }
                                                    $catViewCount .= '<span>' . join($output) . '</span>';
                                                    $catViewCount .= '</div>';
                                                    $catViewCount .= '</div>';
                                                }
                                                $catViewCount .= '</div>';
                                                $catViewCount .= '</div>';
                                            } else {
                                                $catViewCount .= '<div class="atbd_content_left">';
                                                $catViewCount .= '<div class="atbd_listting_category">';
                                                $catViewCount .= '<a href="">';
                                                $catViewCount .= '<span class="' . atbdp_icon_type() . '-tags"></span>';
                                                $catViewCount .= __('Uncategorized', 'directorist');
                                                $catViewCount .= '</a>';
                                                $catViewCount .= '</div>';
                                                $catViewCount .= '</div>';

                                            }
                                        }
                                        if (!empty($display_view_count)) {
                                                $fotter_right = '<ul class="atbd_content_right">';
                                                $fotter_right .= '<li class="atbd_count">';
                                                $fotter_right .= '<span class="' . atbdp_icon_type() . '-eye"></span>';
                                                $fotter_right .= !empty($post_view) ? $post_view : 0;
                                                $fotter_right .= '</li>';
                                                $fotter_right .= '</ul>';
                                            $catViewCount .= apply_filters('atbdp_grid_footer_right_html', $fotter_right);
                                        }

                                        $catViewCount .= '</div>'; //end ./atbd_listing_bottom_content

                                    }
                                    echo apply_filters('atbdp_listings_grid_cat_view_count', $catViewCount);

                                    /**
                                     * @since
                                     * @param mixed $footer_html
                                     * @package Directorist
                                     */
                                    //apply_filters('atbdp_listings_footer_content')
                                    ?>
                                </div>
                            </article>
                        </div>
                    </div>

                <?php }
                wp_reset_postdata();
            } else { ?>
                <p class="atbdp_nlf"><?php _e('No listing found.', 'directorist'); ?></p>
            <?php } ?>

        </div> <!--ends .row -->
        <div class="row">
            <div class="col-lg-12">
                <?php
                if (!empty($paginate)) {
                    ?>
                    <?php
                    $paged = atbdp_get_paged_num();
                    echo atbdp_pagination($all_listings, $paged);
                    ?>
                <?php } ?>
            </div>
        </div>

        <style>
            #directorist.atbd_wrapper .atbdp_column {
                width: <?php echo $column_width;?>;
            }
        </style>

    </div>
    <?php
    return true;
}

function related_listing_slider($all_listings, $pagenation, $is_disable_price, $templete = 'default')
{
    if ('default' != $templete) return;
    $rel_listing_title = get_directorist_option('rel_listing_title', __('Related Listings', 'directorist'))
    ?>
    <div class="<?php echo is_directoria_active() ? 'containere' : 'containess-fluid'; ?>">
        <div class="atbdp-related-listing-header">
            <h4><?php echo $rel_listing_title; ?></h4>
        </div>
        <div class="atbd_margin_fix">
            <div class="related__carousel">

                <?php if ($all_listings->have_posts()) {
                    while ($all_listings->have_posts()) {
                        $all_listings->the_post();
                        $cats = get_the_terms(get_the_ID(), ATBDP_CATEGORY);
                        $locs = get_the_terms(get_the_ID(), ATBDP_LOCATION);
                        $featured = get_post_meta(get_the_ID(), '_featured', true);
                        $price = get_post_meta(get_the_ID(), '_price', true);
                        $price_range = get_post_meta(get_the_ID(), '_price_range', true);
                        $atbd_listing_pricing = get_post_meta(get_the_ID(), '_atbd_listing_pricing', true);
                        $listing_img = get_post_meta(get_the_ID(), '_listing_img', true);
                        $listing_prv_img = get_post_meta(get_the_ID(), '_listing_prv_img', true);
                        $excerpt = get_post_meta(get_the_ID(), '_excerpt', true);
                        $tagline = get_post_meta(get_the_ID(), '_tagline', true);
                        $address = get_post_meta(get_the_ID(), '_address', true);
                        $email = get_post_meta(get_the_ID(), '_email', true);
                        $web = get_post_meta(get_the_ID(), '_website', true);
                        $phone_number = get_post_meta(get_the_Id(), '_phone', true);
                        $category = get_post_meta(get_the_Id(), '_admin_category_select', true);
                        $post_view = get_post_meta(get_the_Id(), '_atbdp_post_views_count', true);
                        $hide_contact_info = get_post_meta(get_the_ID(), '_hide_contact_info', true);
                        $disable_contact_info = get_directorist_option('disable_contact_info', 0);
                        $display_title = get_directorist_option('display_title', 1);
                        $display_review = get_directorist_option('enable_review', 1);
                        $display_price = get_directorist_option('display_price', 1);
                        $display_email = get_directorist_option('display_email', 0);
                        $display_web_link = get_directorist_option('display_web_link', 0);
                        $display_category = get_directorist_option('display_category', 1);
                        $display_view_count = get_directorist_option('display_view_count', 1);
                        $display_mark_as_fav = get_directorist_option('display_mark_as_fav', 1);
                        $display_author_image = get_directorist_option('display_author_image', 1);
                        $display_publish_date = get_directorist_option('display_publish_date', 1);
                        $display_contact_info = get_directorist_option('display_contact_info', 1);
                        $display_feature_badge_cart = get_directorist_option('display_feature_badge_cart', 1);
                        $display_popular_badge_cart = get_directorist_option('display_popular_badge_cart', 1);
                        $popular_badge_text = get_directorist_option('popular_badge_text', 'Popular');
                        $feature_badge_text = get_directorist_option('feature_badge_text', 'Featured');
                        $enable_tagline = get_directorist_option('enable_tagline');
                        $enable_excerpt = get_directorist_option('enable_excerpt');
                        $address_location = get_directorist_option('address_location', 'location');
                        /*Code for Business Hour Extensions*/
                        $bdbh = get_post_meta(get_the_ID(), '_bdbh', true);
                        $enable247hour = get_post_meta(get_the_ID(), '_enable247hour', true);
                        $business_hours = !empty($bdbh) ? atbdp_sanitize_array($bdbh) : array(); // arrays of days and times if exist
                        $disable_bz_hour_listing = get_post_meta(get_the_ID(), '_disable_bz_hour_listing', true);
                        $author_id = get_the_author_meta('ID');
                        $u_pro_pic = get_user_meta($author_id, 'pro_pic', true);
                        $u_pro_pic = wp_get_attachment_image_src($u_pro_pic, 'thumbnail');
                        $avata_img = get_avatar($author_id, apply_filters('atbdp_avatar_size', 32));
                        $thumbnail_cropping = get_directorist_option('thumbnail_cropping', 1);
                        $crop_width = get_directorist_option('crop_width', 360);
                        $crop_height = get_directorist_option('crop_height', 300);
                        $display_tagline_field = get_directorist_option('display_tagline_field', 0);
                        $display_pricing_field = get_directorist_option('display_pricing_field', 1);
                        $display_excerpt_field = get_directorist_option('display_excerpt_field', 0);
                        $display_address_field = get_directorist_option('display_address_field', 1);
                        $display_phone_field = get_directorist_option('display_phone_field', 1);
                        if (!empty($listing_prv_img)) {

                            if ($thumbnail_cropping) {

                                $prv_image = atbdp_image_cropping($listing_prv_img, $crop_width, $crop_height, true, 100)['url'];

                            } else {
                                $prv_image = wp_get_attachment_image_src($listing_prv_img, 'large')[0];
                            }

                        }
                        if (!empty($listing_img[0])) {
                            if ($thumbnail_cropping) {
                                $gallery_img = atbdp_image_cropping($listing_img[0], $crop_width, $crop_height, true, 100)['url'];

                            } else {
                                $gallery_img = wp_get_attachment_image_src($listing_img[0], 'medium')[0];
                            }

                        }
                        ?>
                        <div class="related_single_carousel" style="margin: 0 10px;">
                            <div class="atbd_single_listing atbd_listing_card <?php echo get_directorist_option('info_display_in_single_line', 0) ? 'atbd_single_line_card_info' : ''; ?>">
                                <article
                                        class="atbd_single_listing_wrapper <?php echo ($featured) ? 'directorist-featured-listings' : ''; ?>">
                                    <figure class="atbd_listing_thumbnail_area"
                                            style=" <?php echo empty(get_directorist_option('display_preview_image', 1)) ? 'display:none' : '' ?>">
                                        <div class="atbd_listing_image">
                                            <?php
                                            $disable_single_listing = get_directorist_option('disable_single_listing');
                                            if (empty($disable_single_listing)){
                                            ?>
                                            <a href="<?php echo esc_url(get_post_permalink(get_the_ID())); ?>">
                                                <?php
                                                }
                                                $default_image = get_directorist_option('default_preview_image', ATBDP_PUBLIC_ASSETS . 'images/grid.jpg');
                                                if (!empty($listing_prv_img)) {
                                                    echo '<img src="' . esc_url($prv_image) . '" alt="' . esc_html(stripslashes(get_the_title())) . '">';
                                                }
                                                if (!empty($listing_img[0]) && empty($listing_prv_img)) {
                                                    echo '<img src="' . esc_url($gallery_img) . '" alt="' . esc_html(stripslashes(get_the_title())) . '">';
                                                }
                                                if (empty($listing_img[0]) && empty($listing_prv_img) && !empty($default_image)) {
                                                    echo '<img src="' . $default_image . '" alt="' . esc_html(stripslashes(get_the_title())) . '">';
                                                }
                                                if (empty($disable_single_listing)) {
                                                    echo '</a>';
                                                }
                                                if (!empty($display_author_image)) {
                                                    $author = get_userdata($author_id);
                                                    $class = !empty($author->first_name && $author->last_name)?'atbd_tooltip':'';
                                                    ?>
                                                    <div class="atbd_author">
                                                        <a href="<?php echo ATBDP_Permalink::get_user_profile_page_link($author_id); ?>"
                                                           class="<?php echo $class; ?>"
                                                           aria-label="<?php echo $author->first_name . ' ' . $author->last_name; ?>"><?php if (empty($u_pro_pic)) {
                                                                echo $avata_img;
                                                            }
                                                            if (!empty($u_pro_pic)) { ?>
                                                                <img
                                                                src="<?php echo esc_url($u_pro_pic[0]); ?>"
                                                                alt="Author Image"><?php } ?>
                                                        </a>
                                                    </div>
                                                <?php } ?>

                                        </div>

                                        <?php
                                        $plan_hours = true;
                                        $u_badge_html = '<span class="atbd_upper_badge">';
                                        if (is_fee_manager_active()) {
                                            $plan_hours = is_plan_allowed_business_hours(get_post_meta(get_the_ID(), '_fm_plans', true));
                                        }
                                        if (is_business_hour_active() && $plan_hours && empty($disable_bz_hour_listing)) {
                                            //lets check is it 24/7
                                            if ('2.2.6' > BDBH_VERSION) {
                                                ?>
                                                <style>
                                                    .atbd_badge_close, .atbd_badge_open {
                                                        position: absolute;
                                                        left: 15px;
                                                        top: 15px;
                                                    }
                                                </style>
                                                <?php
                                            }
                                            $open = get_directorist_option('open_badge_text', __('Open Now', 'directorist'));
                                            if (!empty($enable247hour)) {
                                                $u_badge_html .= ' <span class="atbd_badge atbd_badge_open">' . $open . '</span>';

                                            } else {
                                                $bh_statement = BD_Business_Hour()->show_business_open_close($business_hours);

                                                $u_badge_html .= $bh_statement;
                                            }
                                        }
                                        $u_badge_html .= '</span>';

                                        /**
                                         * @since 5.0
                                         */
                                        echo apply_filters('atbdp_upper_badges', $u_badge_html);


                                        //Start lower badge
                                        $l_badge_html = '<span class="atbd_lower_badge">';

                                        if ($featured && !empty($display_feature_badge_cart)) {
                                            $l_badge_html .= '<span class="atbd_badge atbd_badge_featured">' . $feature_badge_text . '</span>';
                                        }

                                        $popular_listing_id = atbdp_popular_listings(get_the_ID());
                                        $badge = '<span class="atbd_badge atbd_badge_popular">' . $popular_badge_text . '</span>';
                                        if ($popular_listing_id === get_the_ID()) {
                                            $l_badge_html .= $badge;
                                        }
                                        //print the new badge
                                        $l_badge_html .= new_badge();
                                        $l_badge_html .= '</span>';

                                        /**
                                         * @since 5.0
                                         */
                                        echo apply_filters('atbdp_grid_lower_badges', $l_badge_html);
                                        if (!empty($display_mark_as_fav)) {
                                            // echo atbdp_listings_mark_as_favourite(get_the_ID());
                                        }
                                        ?>
                                    </figure>
                                    <div class="atbd_listing_info">
                                        <?php if (!empty($display_title) || !empty($enable_tagline) || !empty($display_review) || !empty($display_price)) { ?>
                                            <div class="atbd_content_upper">
                                                <?php if (!empty($display_title)) { ?>
                                                    <h4 class="atbd_listing_title">
                                                        <?php
                                                        if (empty($disable_single_listing)) {
                                                            ?>
                                                            <a href="<?php echo esc_url(get_post_permalink(get_the_ID())); ?>"><?php echo esc_html(stripslashes(get_the_title())); ?></a>
                                                            <?php
                                                        } else {
                                                            echo esc_html(stripslashes(get_the_title()));
                                                        } ?>
                                                    </h4>
                                                <?php }
                                                if (!empty($tagline) && !empty($enable_tagline) && !empty($display_tagline_field)) {

                                                    ?>

                                                    <p class="atbd_listing_tagline"><?php echo esc_html(stripslashes($tagline)); ?></p>
                                                <?php }
                                                /**
                                                 * Fires after the title and sub title of the listing is rendered
                                                 *
                                                 *
                                                 * @since 1.0.0
                                                 */
                                                do_action('atbdp_after_listing_tagline');
                                                ?>

                                                <?php
                                                $meta_html = '';
                                                if (!empty($display_review) || !empty($display_price)) { ?>

                                                    <?php
                                                    $meta_html .= '<div class="atbd_listing_meta">';
                                                    $average = ATBDP()->review->get_average(get_the_ID());
                                                    $meta_html .= '<span class="atbd_meta atbd_listing_rating">' . $average . '<i class="' . atbdp_icon_type() . '-star"></i></span>';
                                                    $atbd_listing_pricing = !empty($atbd_listing_pricing) ? $atbd_listing_pricing : '';
                                                    if (!empty($display_price) && !empty($display_pricing_field)) {
                                                        if (!empty($price_range) && ('range' === $atbd_listing_pricing)) {
                                                            $output = atbdp_display_price_range($price_range);
                                                            $meta_html .= $output;
                                                        } else {
                                                            $meta_html .= atbdp_display_price($price, $is_disable_price, $currency = null, $symbol = null, $c_position = null, $echo = false);
                                                        }
                                                    }
                                                    /**
                                                     * Fires after the price of the listing is rendered
                                                     *
                                                     *
                                                     * @since 3.1.0
                                                     */
                                                    do_action('atbdp_after_listing_price');
                                                    $meta_html .= '</div>';
                                                }
                                                /**
                                                 * @since 5.0
                                                 * universal action to fire after the price
                                                 */
                                                echo apply_filters('atbdp_listings_review_price', $meta_html);
                                                ?>
                                                <?php if (!empty($display_contact_info) || !empty($display_publish_date) || !empty($display_email) || !empty($display_web_link)) { ?>
                                                    <div class="atbd_listing_data_list">
                                                        <ul>
                                                            <?php
                                                            /**
                                                             * @since 4.7.6
                                                             */
                                                            do_action('atbdp_listings_before_location');
                                                            if (!empty($display_contact_info)) {
                                                                if (!empty($address) && 'contact' == $address_location && !empty($display_address_field)) { ?>
                                                                    <li><p>
                                                                            <span class="<?php atbdp_icon_type(true); ?>-map-marker"></span><?php echo esc_html(stripslashes($address)); ?>
                                                                        </p></li>
                                                                <?php } elseif (!empty($locs) && 'location' == $address_location) {
                                                                    $local_names = array();
                                                                    $locals = array();
                                                                    foreach ($locs as $term) {
                                                                        $local_names[$term->term_id] = $term->parent == 0 ? $term->slug : $term->slug;
                                                                        ksort($local_names);
                                                                        $locals = array_reverse($local_names);
                                                                    }
                                                                    $output = array();
                                                                    foreach ($locals as $location) {
                                                                        $term = get_term_by('slug', $location, ATBDP_LOCATION);
                                                                        $link = ATBDP_Permalink::atbdp_get_location_page($term);
                                                                        $space = str_repeat(' ', 1);
                                                                        $output[] = "{$space}<a href='{$link}'>{$term->name}</a>";
                                                                    }
                                                                    ?>
                                                                    <li>
                                                                        <p>

                                                    <span>
                                                    <?php echo "<span class='" . atbdp_icon_type() . "-map-marker'></span>" . join(',', $output); ?>
                                                </span>
                                                                        </p>
                                                                    </li>
                                                                <?php }
                                                                /**
                                                                 * @since 4.7.6
                                                                 */
                                                                do_action('atbdp_listings_before_phone');
                                                                ?>
                                                                <?php if (!empty($phone_number) && !empty($display_phone_field)) { ?>
                                                                    <li><p>
                                                                            <span class="<?php atbdp_icon_type(true); ?>-phone"></span><a
                                                                                    href="tel:<?php echo esc_html(stripslashes($phone_number)); ?>"><?php echo esc_html(stripslashes($phone_number)); ?></a>

                                                                        </p></li>
                                                                    <?php
                                                                }
                                                            }
                                                            /**
                                                             * @since 4.7.6
                                                             */
                                                            do_action('atbdp_listings_before_post_date');
                                                            if (!empty($display_publish_date)) { ?>
                                                                <li><p>
                                                                        <span class="<?php atbdp_icon_type(true); ?>-clock-o"></span><?php
                                                                        $publish_date_format = get_directorist_option('publish_date_format', 'time_ago');
                                                                        if ('time_ago' === $publish_date_format) {
                                                                            printf(__('Posted %s ago', 'directorist'), human_time_diff(get_the_time('U'), current_time('timestamp')));
                                                                        } else {
                                                                            echo get_the_date();
                                                                        }
                                                                        ?></p></li>
                                                            <?php }
                                                            /**
                                                             * @since 4.7.6
                                                             */
                                                            do_action('atbdp_listings_after_post_date');
                                                            if (!empty($email && $display_email)):
                                                                echo '<li><p><span class="' . atbdp_icon_type() . '-envelope"></span><a target="_top" href="mailto:' . $email . '">' . $email . '</a></p></li>';
                                                            endif;
                                                            if (!empty($web && $display_web_link)):
                                                                ?>
                                                                <li><p>
                                                                        <span class="<?php atbdp_icon_type(true); ?>-globe"></span>
                                                                        <a target="_blank"
                                                                           href="<?php echo esc_url($web); ?>"
                                                                            <?php echo !empty($use_nofollow) ? 'rel="nofollow"' : ''; ?>><?php echo esc_html($web); ?></a>
                                                                    </p></li>
                                                            <?php
                                                            endif;
                                                            ?>

                                                        </ul>
                                                    </div><!-- End atbd listing meta -->
                                                    <?php
                                                }
                                                if (!empty($excerpt) && !empty($enable_excerpt) && !empty($display_excerpt_field)) {
                                                    $excerpt_limit = get_directorist_option('excerpt_limit', 20);
                                                    ?>
                                                    <p class="atbd_excerpt_content"><?php echo esc_html(stripslashes(wp_trim_words($excerpt, $excerpt_limit)));
                                                        /**
                                                         * @since 5.0.9
                                                         */
                                                        do_action('atbdp_listings_after_exerpt');
                                                        ?></p>
                                                <?php } ?>
                                            </div><!-- end ./atbd_content_upper -->

                                        <?php }
                                        if (!empty($display_category) || !empty($display_view_count)) { ?>
                                            <div class="atbd_listing_bottom_content">
                                                <?php
                                                if (!empty($display_category)) {
                                                    if (!empty($cats)) {
                                                        $totalTerm = count($cats);
                                                        ?>
                                                        <div class="atbd_content_left">
                                                            <div class="atbd_listting_category">
                                                                <a href="<?php echo esc_url(ATBDP_Permalink::atbdp_get_category_page($cats[0])); ?>"><span class="<?php atbdp_icon_type(true); ?>-tags"></span><?php echo $cats[0]->name; ?>
                                                                </a>
                                                                <?php
                                                                if ($totalTerm > 1) {
                                                                    ?>
                                                                    <div class="atbd_cat_popup">
                                                                        <span>+<?php echo $totalTerm - 1; ?></span>
                                                                        <div class="atbd_cat_popup_wrapper">
                                                                            <?php
                                                                            $output = array();
                                                                            foreach (array_slice($cats, 1) as $cat) {
                                                                                $link = ATBDP_Permalink::atbdp_get_category_page($cat);
                                                                                $space = str_repeat(' ', 1);
                                                                                $output [] = "{$space}<a href='{$link}'>{$cat->name}<span>,</span></a>";
                                                                            } ?>
                                                                            <span><?php echo join($output); ?></span>
                                                                        </div>
                                                                    </div>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    <?php } else {
                                                        ?>
                                                        <div class="atbd_content_left">
                                                            <div class="atbd_listting_category">
                                                                <a href=""><span
                                                                            class="<?php atbdp_icon_type(true); ?>-tags"></span><?php echo __('Uncategorized', 'directorist'); ?>
                                                                </a>
                                                            </div>
                                                        </div>

                                                    <?php }
                                                }
                                                if (!empty($display_view_count)) {
                                                    /**
                                                     * @since 5.5.0
                                                     */
                                                    $fotter_right = '<ul class="atbd_content_right">';
                                                    $fotter_right .= '<li class="atbd_count">';
                                                    $fotter_right .= '<span class="' . atbdp_icon_type() . '-eye"></span>';
                                                    $fotter_right .= !empty($post_view) ? $post_view : 0;
                                                    $fotter_right .= '</li>';
                                                    $fotter_right .= '</ul>';
                                                    echo apply_filters('atbdp_grid_footer_right_html', $fotter_right);
                                                } ?>
                                            </div><!-- end ./atbd_listing_bottom_content -->
                                        <?php } ?>
                                    </div>
                                </article>
                            </div>
                        </div>

                    <?php }
                    wp_reset_postdata();
                } else { ?>
                    <p class="atbdp_nlf"><?php _e('No listing found.', 'directorist'); ?></p>
                <?php } ?>

            </div>
        </div> <!--ends .row -->
        <?php
        if (1 == $pagenation) {
            ?>
            <div class="row">
                <div class="col-md-12">
                    <?php
                    $paged = !empty($paged) ? $paged : '';
                    echo atbdp_pagination($all_listings, $paged);
                    ?>
                </div>
            </div>
        <?php } ?>
    </div>
    <?php
    return true;
}

function listing_view_by_list($all_listings, $display_image, $show_pagination, $paged)
{
    $class_name = 'container-fluid';
    $container = apply_filters('list_view_container', $class_name);
    ?>
    <div class="<?php echo !empty($container) ? $container : 'container'; ?>">
        <div class="row">
            <div class="<?php echo apply_filters('atbdp_listing_list_view_html_class', 'col-md-12') ?>">
                <?php
                while ($all_listings->have_posts()) {
                    $all_listings->the_post(); ?>
                    <?php
                    $cats = get_the_terms(get_the_ID(), ATBDP_CATEGORY);
                    $locs = get_the_terms(get_the_ID(), ATBDP_LOCATION);
                    $featured = get_post_meta(get_the_ID(), '_featured', true);
                    $price = get_post_meta(get_the_ID(), '_price', true);
                    $price_range = get_post_meta(get_the_ID(), '_price_range', true);
                    $listing_pricing = get_post_meta(get_the_ID(), '_atbd_listing_pricing', true);
                    $listing_img = get_post_meta(get_the_ID(), '_listing_img', true);
                    $listing_prv_img = get_post_meta(get_the_ID(), '_listing_prv_img', true);
                    $excerpt = get_post_meta(get_the_ID(), '_excerpt', true);
                    $tagline = get_post_meta(get_the_ID(), '_tagline', true);
                    $address = get_post_meta(get_the_ID(), '_address', true);
                    $phone_number = get_post_meta(get_the_Id(), '_phone', true);
                    $email = get_post_meta(get_the_Id(), '_email', true);
                    $web = get_post_meta(get_the_Id(), '_website', true);
                    $category = get_post_meta(get_the_Id(), '_admin_category_select', true);
                    $post_view = get_post_meta(get_the_Id(), '_atbdp_post_views_count', true);
                    $hide_contact_info = get_post_meta(get_the_ID(), '_hide_contact_info', true);
                    $disable_contact_info = get_directorist_option('disable_contact_info', 0);
                    $display_title = get_directorist_option('display_title', 1);
                    $display_review = get_directorist_option('enable_review', 1);
                    $display_price = get_directorist_option('display_price', 1);
                    $is_disable_price = get_directorist_option('disable_list_price');
                    $display_category = get_directorist_option('display_category', 1);
                    $display_view_count = get_directorist_option('display_view_count', 1);
                    $display_mark_as_fav = get_directorist_option('display_mark_as_fav', 1);
                    $display_author_image = get_directorist_option('display_author_image', 1);
                    $display_publish_date = get_directorist_option('display_publish_date', 1);
                    $display_email = get_directorist_option('display_email', 0);
                    $display_web_link = get_directorist_option('display_web_link', 0);
                    $display_contact_info = get_directorist_option('display_contact_info', 1);
                    $display_feature_badge_cart = get_directorist_option('display_feature_badge_cart', 1);
                    $display_popular_badge_cart = get_directorist_option('display_popular_badge_cart', 1);
                    $popular_badge_text = get_directorist_option('popular_badge_text', 'Popular');
                    $feature_badge_text = get_directorist_option('feature_badge_text', 'Featured');
                    $enable_tagline = get_directorist_option('enable_tagline');
                    $enable_excerpt = get_directorist_option('enable_excerpt');
                    $address_location = get_directorist_option('address_location', 'location');
                    /*Code for Business Hour Extensions*/
                    $bdbh = get_post_meta(get_the_ID(), '_bdbh', true);
                    $enable247hour = get_post_meta(get_the_ID(), '_enable247hour', true);
                    $disable_bz_hour_listing = get_post_meta(get_the_ID(), '_disable_bz_hour_listing', true);
                    $business_hours = !empty($bdbh) ? atbdp_sanitize_array($bdbh) : array(); // arrays of days and times if exist
                    /*Code for Business Hour Extensions*/
                    $author_id = get_the_author_meta('ID');
                    $u_pro_pic_meta = get_user_meta($author_id, 'pro_pic', true);
                    $u_pro_pic = wp_get_attachment_image_src($u_pro_pic_meta, 'thumbnail');
                    $avata_img = get_avatar($author_id, apply_filters('atbdp_avatar_size', 32));
                    $thumbnail_cropping = get_directorist_option('thumbnail_cropping', 1);
                    $crop_width = get_directorist_option('crop_width', 360);
                    $crop_height = get_directorist_option('crop_height', 300);
                    $display_tagline_field = get_directorist_option('display_tagline_field', 0);
                    $display_pricing_field = get_directorist_option('display_pricing_field', 1);
                    $display_excerpt_field = get_directorist_option('display_excerpt_field', 0);
                    $display_address_field = get_directorist_option('display_address_field', 1);
                    $display_phone_field = get_directorist_option('display_phone_field', 1);
                    if (!empty($listing_prv_img)) {
                        if ($thumbnail_cropping) {
                            $prv_image = atbdp_image_cropping($listing_prv_img, $crop_width, $crop_height, true, 100)['url'];
                        } else {
                            $prv_image = wp_get_attachment_image_src($listing_prv_img, 'large')[0];
                        }
                    }
                    if (!empty($listing_img[0])) {
                        if ($thumbnail_cropping) {
                            $gallery_img = atbdp_image_cropping($listing_img[0], $crop_width, $crop_height, true, 100)['url'];
                        } else {
                            $gallery_img = wp_get_attachment_image_src($listing_img[0], 'large')[0];
                        }
                    }
                    ?>
                    <div class="atbd_single_listing atbd_listing_list">
                        <article
                                class="atbd_single_listing_wrapper <?php echo ($featured) ? 'directorist-featured-listings' : ''; ?>">
                            <figure class="atbd_listing_thumbnail_area"
                                    style=" <?php echo (empty(get_directorist_option('display_preview_image')) || 'no' == $display_image) ? 'display:none' : '' ?>">
                                <?php
                                $disable_single_listing = get_directorist_option('disable_single_listing');
                                if (empty($disable_single_listing)){
                                ?>
                                <a href="<?php echo esc_url(get_post_permalink(get_the_ID())); ?>">
                                    <?php
                                    }
                                    $default_image = get_directorist_option('default_preview_image', ATBDP_PUBLIC_ASSETS . 'images/grid.jpg');
                                    if (!empty($listing_prv_img)) {

                                        echo '<img src="' . esc_url($prv_image) . '" alt="' . esc_html(stripslashes(get_the_title())) . '">';

                                    }
                                    if (!empty($listing_img[0]) && empty($listing_prv_img)) {

                                        echo '<img src="' . esc_url($gallery_img) . '" alt="' . esc_html(stripslashes(get_the_title())) . '">';

                                    }
                                    if (empty($listing_img[0]) && empty($listing_prv_img) && !empty($default_image)) {

                                        echo '<img src="' . $default_image . '" alt="' . esc_html(stripslashes(get_the_title())) . '">';

                                    }
                                    if (empty($disable_single_listing)) {
                                        echo '</a>';
                                    }
                                    //Start lower badge
                                    $l_badge_html = '<span class="atbd_lower_badge">';
                                    if ($featured && !empty($display_feature_badge_cart)) {
                                        $l_badge_html .= '<span class="atbd_badge atbd_badge_featured">' . $feature_badge_text . '</span>';
                                    }
                                    $popular_listing_id = atbdp_popular_listings(get_the_ID());
                                    $badge = '<span class="atbd_badge atbd_badge_popular">' . $popular_badge_text . '</span>';
                                    if ($popular_listing_id === get_the_ID() && !empty($display_popular_badge_cart)) {
                                        $l_badge_html .= $badge;
                                    }
                                    //print the new badge
                                    $l_badge_html .= new_badge();
                                    $l_badge_html .= '</span>';

                                    /**
                                     * @since 5.0
                                     */
                                    echo apply_filters('atbdp_list_lower_badges', $l_badge_html); ?>
                            </figure>
                            <div class="atbd_listing_info">
                                <div class="atbd_content_upper">
                                    <?php do_action('atbdp_list_view_before_title'); ?>
                                    <?php if (!empty($display_title)) { ?>
                                        <h4 class="atbd_listing_title">
                                            <?php
                                            if (empty($disable_single_listing)) {
                                                ?>
                                                <a href="<?php echo esc_url(get_post_permalink(get_the_ID())); ?>"><?php echo esc_html(stripslashes(get_the_title())); ?></a>
                                                <?php
                                            } else {
                                                echo esc_html(stripslashes(get_the_title()));
                                            } ?>
                                        </h4>
                                        <?php
                                    }
                                    if (!empty($tagline) && !empty($enable_tagline) && !empty($display_tagline_field)) { ?>
                                        <p class="atbd_listing_tagline"><?php echo esc_html(stripslashes($tagline)); ?></p>
                                        <?php
                                    }
                                    /**
                                     * Fires after the title and sub title of the listing is rendered
                                     *
                                     *
                                     * @since 1.0.0
                                     */
                                    do_action('atbdp_after_listing_tagline');
                                    ?>
                                    <?php
                                    $meta_html = '';
                                    if (!empty($display_review) || (!empty($display_price) && (!empty($price) || !empty($price_range)))) {
                                        $meta_html .= '<div class="atbd_listing_meta">';
                                        if (!empty($display_review)) {
                                            $average = ATBDP()->review->get_average(get_the_ID());
                                            $meta_html .= '<span class="atbd_meta atbd_listing_rating">' . $average . '<i class="' . atbdp_icon_type() . '-star"></i></span>';
                                        }
                                        $listing_pricing = !empty($listing_pricing) ? $listing_pricing : '';
                                        if (!empty($display_price) && !empty($display_pricing_field)) {
                                            if (!empty($price_range) && ('range' === $listing_pricing)) {
                                                $output = atbdp_display_price_range($price_range);
                                                $meta_html .= $output;
                                            } else {
                                                $meta_html .= atbdp_display_price($price, $is_disable_price, $currency = null, $symbol = null, $c_position = null, $echo = false);
                                            }
                                        }
                                        /**
                                         * Fires after the price of the listing is rendered
                                         *
                                         *
                                         * @since 3.1.0
                                         */
                                        do_action('atbdp_after_listing_price');
                                        $plan_hours = true;
                                        if (is_fee_manager_active()) {
                                            $plan_hours = is_plan_allowed_business_hours(get_post_meta(get_the_ID(), '_fm_plans', true));
                                        }
                                        if (is_business_hour_active() && $plan_hours && empty($disable_bz_hour_listing)) {
                                            //lets check is it 24/7
                                            if (!empty($enable247hour)) {
                                                $open = get_directorist_option('open_badge_text');
                                                $meta_html .= '<span class="atbd_badge atbd_badge_open">' . $open . '</span>';
                                            } else {
                                                $bh_statement = BD_Business_Hour()->show_business_open_close($business_hours, false); // show the business hour in an unordered list
                                                $meta_html .= $bh_statement;
                                            }
                                        }
                                        $meta_html .= '</div>'; // End atbd listing meta
                                    }
                                    echo apply_filters('atbdp_listings_list_review_price', $meta_html);
                                    if (!empty($display_contact_info) || !empty($display_publish_date) || !empty($display_email) || !empty($display_web_link)) { ?>
                                        <div class="atbd_listing_data_list">
                                            <ul>
                                                <?php
                                                /**
                                                 * @since 4.7.6
                                                 */
                                                do_action('atbdp_listings_before_location');
                                                if (!empty($display_contact_info)) {
                                                    if (!empty($address) && 'contact' == $address_location && !empty($display_address_field)) { ?>
                                                        <li><p>
                                                                <span class="<?php atbdp_icon_type(true); ?>-map-marker"></span><?php echo esc_html(stripslashes($address)); ?>
                                                            </p></li>
                                                    <?php } elseif (!empty($locs) && 'location' == $address_location) {
                                                        $local_names = array();
                                                        $locals = array();
                                                        foreach ($locs as $term) {
                                                            $local_names[$term->term_id] = $term->parent == 0 ? $term->slug : $term->slug;
                                                            ksort($local_names);
                                                            $locals = array_reverse($local_names);
                                                        }
                                                        $output = array();
                                                        foreach ($locals as $location) {
                                                            $term = get_term_by('slug', $location, ATBDP_LOCATION);
                                                            $link = ATBDP_Permalink::atbdp_get_location_page($term);
                                                            $space = str_repeat(' ', 1);
                                                            $output[] = "{$space}<a href='{$link}'>{$term->name}</a>";
                                                        }
                                                        ?>
                                                        <li>
                                                            <p>
                                                   <span>
                                                    <?php echo "<span class='" . atbdp_icon_type() . "-map-marker'></span>" . join(',', $output); ?>
                                                </span>
                                                            </p>
                                                        </li>
                                                    <?php }
                                                    /**
                                                     * @since 4.7.6
                                                     */
                                                    do_action('atbdp_listings_before_phone');
                                                    ?>
                                                    <?php if (!empty($phone_number) && !empty($display_phone_field)) { ?>
                                                        <li><p>
                                                                <span class="<?php atbdp_icon_type(true); ?>-phone"></span><a
                                                                        href="tel:<?php echo esc_html(stripslashes($phone_number)); ?>"><?php echo esc_html(stripslashes($phone_number)); ?></a>

                                                            </p></li>
                                                        <?php
                                                    }
                                                }
                                                /**
                                                 * @since 4.7.6
                                                 */
                                                do_action('atbdp_listings_before_post_date');
                                                if (!empty($display_publish_date)) { ?>
                                                    <li><p>
                                                            <span class="<?php atbdp_icon_type(true); ?>-clock-o"></span><?php
                                                            $publish_date_format = get_directorist_option('publish_date_format', 'time_ago');
                                                            if ('time_ago' === $publish_date_format) {
                                                                printf(__('Posted %s ago', 'directorist'), human_time_diff(get_the_time('U'), current_time('timestamp')));
                                                            } else {
                                                                echo get_the_date();
                                                            }
                                                            ?></p></li>
                                                <?php }
                                                /**
                                                 * @since 4.7.6
                                                 */
                                                do_action('atbdp_listings_after_post_date');
                                                if (!empty($email && $display_email)) {
                                                    echo '<li><p><span class="' . atbdp_icon_type() . '-envelope"></span><a target="_top" href="mailto:' . $email . '">' . $email . '</a></p></li>';
                                                }
                                                if (!empty($web && $display_web_link)):
                                                    ?>
                                                    <li><p>
                                                            <span class="<?php atbdp_icon_type(true); ?>-globe"></span>
                                                            <a target="_blank" href="<?php echo esc_url($web); ?>"
                                                                <?php echo !empty($use_nofollow) ? 'rel="nofollow"' : ''; ?>><?php echo esc_html($web); ?></a>
                                                        </p></li>
                                                <?php
                                                endif;
                                                ?>
                                            </ul>
                                        </div><!-- End atbd listing meta -->
                                        <?php
                                    }
                                    //show category and location info
                                    ?>
                                    <?php if (!empty($excerpt) && !empty($enable_excerpt) && !empty($display_excerpt_field)) {
                                        $excerpt_limit = get_directorist_option('excerpt_limit', 20);
                                        $excerpt_limit = get_directorist_option('excerpt_limit', 20);
                                        $display_readmore = get_directorist_option('display_readmore', 0);
                                        $readmore_text = get_directorist_option('readmore_text', __('Read More', 'directorist'));
                                        ?>
                                        <p class="atbd_excerpt_content"><?php echo esc_html(stripslashes(wp_trim_words($excerpt, $excerpt_limit)));
                                        /**
                                         * @since 5.0.9
                                         */
                                        do_action('atbdp_listings_after_exerpt');
                                        if (!empty($display_readmore)) {
                                            ?><a
                                            href="<?php the_permalink(); ?>"><?php printf(__(' %s', 'directorist'), $readmore_text); ?></a></p>
                                        <?php }
                                    }
                                    if (!empty($display_mark_as_fav)) {
                                        $mark_as_fav_for_list_view = apply_filters('atbdp_mark_as_fav_for_list_view', atbdp_listings_mark_as_favourite(get_the_ID()));
                                        echo $mark_as_fav_for_list_view;
                                    }
                                    ?>
                                </div><!-- end ./atbd_content_upper -->
                                <?php
                                $catViewCountAuthor = '';
                                if (!empty($display_category) || !empty($display_view_count) || !empty($display_author_image)) {
                                    $catViewCountAuthor .= '<div class="atbd_listing_bottom_content">';
                                    if (!empty($display_category)) {
                                        if (!empty($cats)) {
                                            $totalTerm = count($cats);
                                            $catViewCountAuthor .= '<div class="atbd_content_left">';
                                            $catViewCountAuthor .= '<div class="atbd_listting_category">';
                                            $catViewCountAuthor .= '<a href="' . ATBDP_Permalink::atbdp_get_category_page($cats[0]) . '">';
                                            $catViewCountAuthor .= '<span class="' . atbdp_icon_type() . '-tags"></span>';
                                            $catViewCountAuthor .= $cats[0]->name;
                                            $catViewCountAuthor .= '</a>';
                                            if ($totalTerm > 1) {
                                                $totalTerm = $totalTerm - 1;
                                                $catViewCountAuthor .= '<div class="atbd_cat_popup">';
                                                $catViewCountAuthor .= '<span>+' . $totalTerm . '</span>';
                                                $catViewCountAuthor .= '<div class="atbd_cat_popup_wrapper">';
                                                $output = array();
                                                foreach (array_slice($cats, 1) as $cat) {
                                                    $link = ATBDP_Permalink::atbdp_get_category_page($cat);
                                                    $space = str_repeat(' ', 1);
                                                    $output [] = "{$space}<span><a href='{$link}'>{$cat->name}<span>,</span></a></span>";
                                                }
                                                $catViewCountAuthor .= '<span>' . join($output) . '</span>';
                                                $catViewCountAuthor .= '</div>';
                                                $catViewCountAuthor .= '</div>';
                                            }
                                            $catViewCountAuthor .= '</div>';
                                            $catViewCountAuthor .= '</div>';
                                        } else {
                                            $catViewCountAuthor .= '<div class="atbd_content_left">';
                                            $catViewCountAuthor .= '<div class="atbd_listting_category">';
                                            $catViewCountAuthor .= '<a href="">';
                                            $catViewCountAuthor .= '<span class="' . atbdp_icon_type() . '-tags"></span>';
                                            $catViewCountAuthor .= __('Uncategorized', 'directorist');
                                            $catViewCountAuthor .= '</a>';
                                            $catViewCountAuthor .= '</div>';
                                            $catViewCountAuthor .= '</div>';
                                        }
                                    }
                                    if (!empty($display_view_count) || !empty($display_author_image)) {
                                        $catViewCountAuthor .= '<ul class="atbd_content_right">';
                                        if (!empty($display_view_count)) {
                                            $catViewCountAuthor .= '<li class="atbd_count">';
                                            $catViewCountAuthor .= '<span class="' . atbdp_icon_type() . '-eye"></span>';
                                            $catViewCountAuthor .= !empty($post_view) ? $post_view : 0;
                                            $catViewCountAuthor .= '</li>';
                                        }
                                        if (!empty($display_author_image)) {
                                            $author = get_userdata($author_id);
                                            $author_first_last_name = $author->first_name . ' ' . $author->last_name;
                                            $class = !empty($author->first_name && $author->last_name)?'atbd_tooltip':'';
                                            $catViewCountAuthor .= '<li class="atbd_author">';
                                            $catViewCountAuthor .= '<a href="' . ATBDP_Permalink::get_user_profile_page_link($author_id) . '" class="'. $class .'" aria-label="' . $author_first_last_name . '">';
                                            if (empty($u_pro_pic)) {
                                                $catViewCountAuthor .= $avata_img;
                                            }
                                            if (!empty($u_pro_pic)) {
                                                $catViewCountAuthor .= '<img src="' . esc_url($u_pro_pic[0]) . '" alt="Author Image">';
                                            }
                                            $catViewCountAuthor .= '</a>';
                                            $catViewCountAuthor .= '</li>';
                                        }
                                        $catViewCountAuthor .= ' </ul>';
                                    }
                                    $catViewCountAuthor .= ' </div>' //end ./atbd_listing_bottom_content
                                    ?>
                                <?php }
                                echo apply_filters('atbdp_listings_list_cat_view_count_author', $catViewCountAuthor);
                                ?>
                            </div>
                        </article>
                    </div>


                <?php }
                wp_reset_postdata(); ?>
                <?php
                /**
                 * @since 5.0
                 */
                do_action('atbdp_before_listings_pagination');

                if ('yes' == $show_pagination) { ?>
                    <?php
                    echo atbdp_pagination($all_listings, $paged);
                    ?>
                <?php } ?>

            </div>
        </div>
    </div>
    <?php
    return true;
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
                $html .= sprintf('<div class="bdas-child-terms bdas-child-terms-%d">', $args['parent']);
                $html .= sprintf('<select class="%s" data-taxonomy="%s" data-parent="%d">', $args['class'], $args['taxonomy'], $args['parent']);
                $html .= sprintf('<option value="%d">%s</option>', $args['parent'], '---');
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

function atbdp_get_custom_field_ids($category = 0)
{


    // Get global fields
    $args = array(
        'post_type' => ATBDP_CUSTOM_FIELD_POST_TYPE,
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'meta_query' => array(
            array(
                'key' => 'associate',
                'value' => 'form'
            ),
        )
    );

    $field_ids = get_posts($args);

    // Get category fields
    if ($category > 0) {

        $args = array(
            'post_type' => ATBDP_CUSTOM_FIELD_POST_TYPE,
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'fields' => 'ids',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'category_pass',
                    'value' => $category,
                    'compare' => 'EXISTS',
                ),
                array(
                    'key' => 'associate',
                    'value' => 'categories',
                    'compare' => 'LIKE',
                )
            )
        );

        $category_fields = get_posts($args);
        $field_ids = array_merge($field_ids, $category_fields);
        $field_ids = array_unique($field_ids);

    }

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
        $arg = (array(
            'post_type' => 'at_biz_dir',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        ));

        return new WP_Query($arg);
    }
}

/**
 * @return Wp_Query
 * @since 4.7.7
 */
if (!function_exists('atbdp_get_expired_listings')) {
    function atbdp_get_expired_listings($texonomy, $categories)
    {
        $arg = (array(
            'post_type' => 'at_biz_dir',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'meta_query' => array(
                'relation' => 'OR',
                array(
                    'key' => '_expiry_date',
                    'value' => current_time('mysql'),
                    'compare' => '<', // eg. expire date 6 <= current date 7 will return the post
                    'type' => 'DATETIME'
                ),
                array(
                    'key' => '_never_expire',
                    'value' => '',
                )
            ),
            'tax_query' => array(
                array(
                    'taxonomy' => $texonomy,
                    'field' => 'id',
                    'terms' => !empty($categories) ? $categories : array(),
                    'include_children' => true,
                )
            ),
        ));

        return new WP_Query($arg);
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
 * @return    bool     $can_use_yoast    "true" if can use Yoast, "false" if not.
 * @since     5.4.4
 *
 */
function atbdp_can_use_yoast()
{

    $can_use_yoast = false;
    if ((in_array('wordpress-seo/wp-seo.php', apply_filters('active_plugins', get_option('active_plugins'))))) {
        $can_use_yoast = true;
    }

    return $can_use_yoast;

}

/**
 *
 * @return    bool     $can_use_yoast    "true" if can use Yoast, "false" if not.
 * @since     5.5.2
 *
 */
function atbdp_disable_overwrite_yoast()
{

    $overwrite = false;
    $overwrite_yoast = get_directorist_option('overwrite_by_yoast');
    if (!empty($overwrite_yoast) || (!in_array('wordpress-seo/wp-seo.php', apply_filters('active_plugins', get_option('active_plugins'))))) {
        $overwrite = true;
    }

    return $overwrite;

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
        $reviews = new WP_Query(array(
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
        ));

        $review_meta = array();
        foreach ($reviews->posts as $key => $val) {
            $review_meta[] = !empty($val) ? $val : array();
        }

        return ($review_meta) ? $review_meta : false;
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
        $category_id = !empty($category_slug) ? $category->term_id : '';
        $term_id = isset($_GET['in_cat']) ? $_GET['in_cat'] : $category_id;
    } else {
        $location_slug = get_query_var('atbdp_location');
        $location = get_term_by('slug', $location_slug, ATBDP_LOCATION);
        $location_id = !empty($location_slug) ? $location->term_id : '';
        $term_id = isset($_GET['in_loc']) ? $_GET['in_loc'] : $location_id;
    }

    $args =  array(
        'orderby' => $settings['orderby'],
        'order' => $settings['order'],
        'hide_empty' => $settings['hide_empty'],
        'parent' => $settings['term_id'],
        'hierarchical' => !empty($settings['hide_empty']) ? true : false
    );

    if(ATBDP_CATEGORY == $taxonomy_id){
        $arg = apply_filters('atbdp_search_listing_category_argument', $args);
    }else{
        $arg = apply_filters('atbdp_search_listing_location_argument', $args);
    }


    $terms = get_terms($taxonomy_id, $arg);

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

function add_listing_category_location_filter($settings, $taxonomy_id, $term_id, $prefix = '', $plan_cat = array())
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

    $terms = get_terms($taxonomy_id, $args);

    $html = '';

    if (count($terms) > 0) {

        foreach ($terms as $term) {
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
            $html .= add_listing_category_location_filter($settings, $taxonomy_id, $term_id, $prefix . '&nbsp;&nbsp;&nbsp;');
            $html .= '</option>';
        }

    }

    return $html;

}

/**
 * @return boolean
 * @since 5.10.0
 */
function is_pyn_type()
{
    if (class_exists('Post_Your_Need')) {
        $service = pyn_get_listings_type();
        if ('service' === $service) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
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
        $msg = '<div class="alert alert-danger"><strong>' . __('Email already exists!', 'directorist') . '</strong></div>';
        return $msg;
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
            // user has been created successfully, now work on activation process
            wp_new_user_notification($user_id, null, 'both');
        }
    }
}

function atbdp_get_listing_attachment_ids($post_id){

    $listing_img = get_post_meta($post_id, '_listing_img', true);
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

function atbdp_email_html($subject, $message){
    $site_name = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
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
									<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_header" style=\'background-color: #8569fb; color: #ffffff; border-bottom: 0; font-weight: bold; line-height: 100%; vertical-align: middle; font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif; border-radius: 3px 3px 0 0;\'>
										<tr>
											<td id="header_wrapper" style="padding: 36px 48px; display: block;">
												<h1 style=\'font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif; font-size: 30px; font-weight: 300; line-height: 150%; margin: 0; text-align: left; text-shadow: 0 1px 0 #ab79a1; color: #ffffff;\'>'.$subject.'</h1>
											</td>
										</tr>
									</table>
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
        'all_categories_page' => array(
            'title' => __('All Categories', 'directorist'),
            'content' => '[directorist_all_categories]'
        ),
        'single_category_page' => array(
            'title' => __('Single Category', 'directorist'),
            'content' => '[directorist_category]'
        ),
        'all_locations_page' => array(
            'title' => __('All Locations', 'directorist'),
            'content' => '[directorist_all_locations]'
        ),
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
        ), 'user_login' => array(
            'title' => __('Login', 'directorist'),
            'content' => '[directorist_user_login]'
        ),
        'checkout_page' => array(
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
        ),
    ));
    $new_settings = 0; // lets keep track of new settings so that we do not update option unnecessarily.
    // lets iterate over the array and insert a new page with with the appropriate shortcode if the page id is not available in the option array.
    foreach ($directorist_pages as $op_name => $page_settings) {
        // $op_name is the page option name in the database.
        // if we do not have the page id assigned in the settings with the given page option name, then create an page
        // and update the option.
        if (empty($options[$op_name])) {

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
            if (!is_wp_error($replace_shortcode)) {
                update_user_meta(get_current_user_id(), '_atbdp_shortcode_regenerate_notice', 'true');
            }
        }
        // if we have new options then lets update the options with new option values.
        if ($new_settings) {
            update_option('atbdp_option', $options);
        };
        update_option('atbdp_pages_version', 1);
    }
}