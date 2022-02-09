<?php
/**
 * @author wpWax
 */

namespace Directorist\Asset_Loader;

if ( ! defined( 'ABSPATH' ) ) exit;

class Enqueue {

    use Enqueue_Shortcodes;
    use Enqueue_Widgets;
    use Enqueue_Admin;

}