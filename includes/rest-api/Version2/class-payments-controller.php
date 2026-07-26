<?php

namespace Directorist\Rest_Api\Controllers\Version2;

defined( 'ABSPATH' ) || exit;

use Directorist\Rest_Api\Controllers\Version1\Payments_Controller as Version1_Payments_Controller;

class Payments_Controller extends Version1_Payments_Controller {
    protected $namespace = 'directorist/v2';
}
