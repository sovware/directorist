<?php

if (!defined('ABSPATH')) {
    die(ATBDP_ALERT_MSG);
}

if (!class_exists('ATBDP_Helper')) :

    class ATBDP_Helper
    {
        private $nonce_action = 'atbdp_nonce_action';
        private $nonce_name = 'atbdp_nonce';

        public function __construct()
        {
            if (!defined('ABSPATH')) {
                return;
            }
            add_action('init', array($this, 'check_req_php_version'), 100);
        }

        // the_thumbnail_card
        public static function the_thumbnail_card($img_src = '', $args = array())
        {
            $alt = ( isset($args['alt']) ) ? esc_html(stripslashes($args['alt'])) : esc_html(get_the_title());
            $show_blur_bg = ( isset($args['blur-background']) ) ? $args['blur-background'] : true;
            $ratio = ( isset($args['ratio']) ) ? $args['ratio'] : '350:260'; // 350 : 260

            $ratio_match = preg_match( '/^(\d+):(\d+)$/', $ratio, $matches );
            $ratio_width = ( $matches ) ? $matches[1] : '16';
            $ratio_height = ( $matches ) ? $matches[2] : '9';
            $padding_top = (int) $ratio_height / (int) $ratio_width * 100;

            // full cover contain
            $image_size_type = ( isset($args['image-size']) ) ? $args['image-size'] : 'cover';

            $front_wrap_html = <<<EOD
            <div class='atbd-thumbnail-card-front-wrap'>
                <img src='$img_src' alt="$alt" class='atbd-thumbnail-card-front-img'/>
            </div>
            EOD;

            $back_wrap_html = <<<EOD
            <div class='atbd-thumbnail-card-back-wrap'>
                <img src='$img_src'/ class="atbd-thumbnail-card-back-img">
            </div>
            EOD;
            $blur_bg = ( $show_blur_bg ) ? $back_wrap_html : '';

            $image_contain_html = <<<EOD
            <div class='atbd-thumbnail-card card-contain' style="padding-top: $padding_top%">
                $blur_bg
                $front_wrap_html
            </div>
            EOD;

            $image_cover_html = <<<EOD
            <div class='atbd-thumbnail-card card-cover' style="padding-top: $padding_top%">
                $front_wrap_html
            </div>
            EOD;

            $image_full_html = <<<EOD
            <div class='atbd-thumbnail-card card-full'>
                $front_wrap_html
            </div>
            EOD;

            $the_html = $image_cover_html;
            switch ($image_size_type) {
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

        public function check_req_php_version()
        {
            if (version_compare(PHP_VERSION, '5.4', '<')) {
                add_action('admin_notices', array($this, 'notice'), 100);


                // deactivate the plugin because required php version is less.
                add_action('admin_init', array($this, 'deactivate_self'), 100);

                return;
            }
        }
        public function notice()
        { ?>
            <div class="error">
                <p>
                    <?php
                    printf(__('%s requires minimum PHP 5.4 to function properly. Please upgrade PHP version. The Plugin has been auto-deactivated.. You have PHP version %d', 'directorist'), ATBDP_NAME, PHP_VERSION);
                    ?>
                </p>
            </div>
            <?php
            if (isset($_GET['activate'])) {
                unset($_GET['activate']);
            }
        }

        public function deactivate_self()
        {
            deactivate_plugins(ATBDP_BASE);
        }

        public function verify_nonce($nonce = '', $action = '', $method = '_REQUEST')
        {
            // if we do not pass any nonce and action then use default nonce and action name on this class,
            // else check provided nonce and action
            if (empty($nonce) || empty($action)) {
                $nonce      = (!empty($$method[$this->nonce_name()])) ? $$method[$this->nonce_name()] : null;
                $nonce_action  = $this->nonce_action();
            } else {
                $nonce      = (!empty($_REQUEST[$nonce])) ? $_REQUEST[$nonce] : null;
                $nonce_action = $action;
            }
            return wp_verify_nonce($nonce, $nonce_action);
        }

        public function nonce_action()
        {
            return $this->nonce_action;
        }
        public function nonce_name()
        {
            return $this->nonce_name;
        }

        public function social_links()
        {
            $s = array(
                'facebook' => __('Facebook', 'directorist'),
                'twitter'   => __('Twitter', 'directorist'),
                'linkedin' =>  __('LinkedIn', 'directorist'),
                'pinterest' =>  __('Pinterest', 'directorist'),
                'instagram' =>  __('Instagram', 'directorist'),
                'tumblr' =>  __('Tumblr', 'directorist'),
                'flickr' =>  __('Flickr', 'directorist'),
                'snapchat-ghost' =>  __('Snapchat', 'directorist'),
                'reddit' =>  __('Reddit', 'directorist'),
                'youtube-play' =>  __('Youtube', 'directorist'),
                'vimeo' =>  __('Vimeo', 'directorist'),
                'vine' =>  __('Vine', 'directorist'),
                'github' =>  __('Github', 'directorist'),
                'dribbble' =>  __('Dribbble', 'directorist'),
                'behance' =>  __('Behance', 'directorist'),
                'soundcloud' =>  __('SoundCloud', 'directorist'),
                'stack-overflow' =>  __('StackOverFLow', 'directorist'),
            );
            asort($s);
            return $s;
        }

        public static function getFreshIcon($id)
        {
            $icon = $id;
            switch ($id) {
                case 'youtube':
                    $icon = 'youtube-play';
                    break;
            }
            return $icon;
        }


        /**
         * Darken or lighten a given hex color and return it.
         * @param string $hex Hex color code to be darken or lighten
         * @param int $percent The number of percent of darkness or brightness
         * @param bool|true $darken Lighten the color if set to false, otherwise, darken it. Default is true.
         *
         * @return string
         */
        public function adjust_brightness($hex, $percent, $darken = true)
        {
            // determine if we want to lighten or draken the color. Negative -255 means darken, positive integer means lighten
            $brightness = $darken ? -255 : 255;
            $steps = $percent * $brightness / 100;

            // Normalize into a six character long hex string
            $hex = str_replace('#', '', $hex);
            if (strlen($hex) == 3) {
                $hex = str_repeat(substr($hex, 0, 1), 2) . str_repeat(substr($hex, 1, 1), 2) . str_repeat(substr($hex, 2, 1), 2);
            }

            // Split into three parts: R, G and B
            $color_parts = str_split($hex, 2);
            $return = '#';

            foreach ($color_parts as $color) {
                $color   = hexdec($color); // Convert to decimal
                $color   = max(0, min(255, $color + $steps)); // Adjust color
                $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
            }

            return $return;
        }





        /**
         * Lists of html tags that are allowed in a content
         * @return array List of allowed tags in a content
         */
        public function allowed_html()
        {
            return array(
                'i' => array(
                    'class' => array(),
                ),
                'strong' => array(
                    'class' => array(),
                ),
                'em' => array(
                    'class' => array(),
                ),
                'a' => array(
                    'class' => array(),
                    'href' => array(),
                    'title' => array(),
                    'target' => array(),
                ),

            );
        }

        /**
         * Prints pagination for custom post
         * @param $loop
         * @param int $paged
         *
         * @return string
         */
        public  function show_pagination($loop, $paged = 1)
        {
            //@TODO: look into this deeply later : http://www.insertcart.com/numeric-pagination-wordpress-using-php/
            $largeNumber = 999999999; // we need a large number here
            $links = paginate_links(array(
                'base' => str_replace($largeNumber, '%#%', esc_url(get_pagenum_link($largeNumber))),
                'format' => '?paged=%#%',
                'current' => max(1, $paged),
                'total' => $loop->max_num_pages,
                'prev_text' => __('&laquo; Prev', 'directorist'),
                'next_text' => __('Next &raquo;', 'directorist'),
                'type' => 'list',
            ));


            return $links;
        }

        public function show_login_message($message = '')
        {

            $t = !empty($message) ? $message : '';
            $t = apply_filters('atbdp_unauthorized_access_message', $t);
            ?>
            <div class="notice_wrapper">
                <div class="alert alert-warning"><span class="fa fa-info-circle" aria-hidden="true"></span> <?php echo $t; ?></div>
            </div>
            <?php
        }

        /**
         * It converts a mysql datetime string to human readable relative time
         * @param string $mysql_date Mysql Datetime string eg. 2018-5-11 17:02:26
         * @param bool $echo [optional] If $echo is true then print the value else return the value. default is true.
         * @param string $suffix [optional] Suffix to be added to the related time. Default is ' ago.' .
         * @return string|void It returns the relative time from a mysql datetime string
         */
        public function mysql_to_human_time($mysql_date, $echo = true, $suffix = ' ago.')
        {
            $date = DateTime::createFromFormat("Y-m-d H:i:s", $mysql_date);
            $time = human_time_diff($date->getTimestamp()) . $suffix;
            if (!$echo) return $time;
            echo $time;
        }

        /**
         * It outputs category and location related markup for the listing
         * @param WP_Term $cat Listing Category Object
         * @param WP_Term $loc Listing Location Object
         */
        public function output_listings_taxonomy_info($cat, $loc)
        {
            if (!empty($cat) || !empty($loc)) { ?>
                <div class="general_info">
                    <ul>
                        <?php if (!empty($cat)) { ?>
                            <li>
                                <p class="info_title"><?php _e('Category:', 'directorist'); ?></p>
                                <p class="directory_tag">
                                    <span class="fa <?php echo esc_attr(get_cat_icon(@$cat->term_id)); ?>" aria-hidden="true"></span>
                                    <span> <?php if (is_object($cat)) { ?>
                                            <a href="<?php echo ATBDP_Permalink::atbdp_get_category_page($cat); ?>">
                                                <?php echo esc_html($cat->name); ?>
                                            </a>
                                        <?php } ?>
                                    </span>
                                </p>
                            </li>
                        <?php }
                        if (!empty($loc)) { ?>
                            <li>
                                <p class="info_title"><?php _e('Location:', 'directorist'); ?>
                                    <span><?php if (is_object($loc)) { ?>
                                            <a href="<?php echo ATBDP_Permalink::atbdp_get_location_page($loc); ?>">
                                                <?php echo esc_html($loc->name); ?>
                                            </a>
                                        <?php } ?>
                                    </span>
                                </p>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            <?php }
        }

        /**
         * It prints a read more link to the given listing ID or the current post id inside a loop.
         * @param int $id [optional] Listing ID
         */
        public function listing_read_more_link($id = null)
        {
            if (empty($id)) {
                global $post;
                $id = $post->ID;
            }
            /*@todo; later make changeable via filter*/
            ?>
            <div class="read_more_area">
                <a class="btn btn-default " href="<?php echo esc_url(get_post_permalink($id)); ?>">
                    <?php esc_html_e('Read More', 'directorist'); ?>
                </a>
            </div>
            <?php
        }

        /**
         * It outputs all categories and locations related markup for the listing
         * @param array $cats [optional] the array of Listing Category Objects
         * @param array $locs [optional] the array of Listing Location Objects
         */
        public function output_listings_all_taxonomy_info($cats = array(), $locs = array())
        {
            // get terms from db if not provided
            $cats = !empty($cats) ? $cats : get_the_terms(null, ATBDP_CATEGORY);
            $locs = !empty($locs) ? $locs : get_the_terms(null, ATBDP_LOCATION);

            if (!empty($cats) || !empty($locs)) { ?>
                <div class="general_info">
                    <ul>
                        <?php if (!empty($cats) && is_array($cats)) { ?>
                            <li>
                                <ul>
                                    <p class="info_title"><?php _e('Category:', 'directorist'); ?></p>
                                    <?php foreach ($cats as $cat) { ?>
                                        <li>
                                            <p class="directory_tag">
                                                <span class="fa <?php echo esc_attr(get_cat_icon(@$cat->term_id)); ?>" aria-hidden="true"></span>
                                                <span> <?php if (is_object($cat)) { ?>
                                                        <a href="<?php echo esc_url(ATBDP_Permalink::atbdp_get_category_page($cat)); ?>">
                                                            <?php echo esc_html($cat->name); ?>
                                                        </a>
                                                    <?php } ?>
                                                </span>
                                            </p>
                                        </li>
                                    <?php  } ?>
                                </ul>
                            </li>
                        <?php }

                        if (!empty($locs) && is_array($locs)) {
                            $location_count = count($locs);
                        ?>
                            <li>
                                <ul>
                                    <p class="info_title"><?php _e('Location:', 'directorist'); ?></p>
                                    <?php foreach ($locs as $loc) {
                                        $location_count--; // reduce count to display comma for the right item
                                    ?>
                                        <li>
                                            <span><?php if (is_object($loc)) { ?>
                                                    <a href="<?php echo esc_url(ATBDP_Permalink::atbdp_get_location_page($loc)); ?>">
                                                        <?php echo esc_html($loc->name); ?>
                                                    </a>
                                                <?php } ?>
                                            </span><?php
                                                    // @todo; discuss with front-end dev if it is good to put comma here directly or he will do?
                                                    if ($location_count >= 1) echo ",";
                                                    ?>
                                        </li>
                                    <?php  } ?>
                                </ul>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
<?php }
        }
    }
endif;
