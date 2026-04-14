<?php

namespace Directorist\DBModels;

defined( "ABSPATH" ) || exit;

use Directorist\Utils\Database\Resolver;
use Directorist\Utils\Database\Eloquent\Model;

class Payment extends Model {
    public static function get_table_name():string {
        return "directorist_payments";
    }

    public function resolver():Resolver {
        return new Resolver();
    }
}