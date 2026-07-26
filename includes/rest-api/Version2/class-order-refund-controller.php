<?php

namespace Directorist\Rest_Api\Controllers\Version2;

defined( 'ABSPATH' ) || exit;

use Directorist\Rest_Api\Controllers\Version1\Order_Refund_Controller as Version1_Order_Refund_Controller;

class Order_Refund_Controller extends Version1_Order_Refund_Controller {
    protected $namespace = 'directorist/v2';
}
