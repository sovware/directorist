<?php

namespace Directorist\Rest_Api\Controllers\Version2;

defined( 'ABSPATH' ) || exit;

use Directorist\Rest_Api\Controllers\Version1\Order_Controller as Version1_Order_Controller;

class Order_Controller extends Version1_Order_Controller {
    protected $namespace = 'directorist/v2';
}
