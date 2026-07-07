<?php

namespace Directorist\Rest_Api\Controllers\Version2;

defined( 'ABSPATH' ) || exit;

use Directorist\Rest_Api\Controllers\Version1\Checkout_Controller as Version1_Checkout_Controller;

class Checkout_Controller extends Version1_Checkout_Controller {
    protected $namespace = 'directorist/v2';
}
