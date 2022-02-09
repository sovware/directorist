<?php
/**
 * @author wpWax
 */

namespace Directorist\Asset_Loader;

if ( ! defined( 'ABSPATH' ) ) exit;

require_once 'trait-enqueue-shortcodes.php';
require_once 'trait-enqueue-widgets.php';
require_once 'trait-enqueue-admin.php';

class Enqueue {

    use Enqueue_Shortcodes;
    use Enqueue_Widgets;
    use Enqueue_Admin;

}