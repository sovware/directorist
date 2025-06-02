<?php

namespace Directorist\App\Models;

defined( "ABSPATH" ) || exit;

use Directorist\WpMVC\App;
use Directorist\WpMVC\Database\Resolver;
use Directorist\WpMVC\Database\Eloquent\Model;

class Order extends Model {
    public static function get_table_name():string {
        return 'directorist_orders';
    }

    public function resolver():Resolver {
        return App::$container->get( Resolver::class );
    }
}