<?php

namespace Directorist\App\Models;

defined( "ABSPATH" ) || exit;

use Directorist\WpMVC\App;
use Directorist\WpMVC\Database\Resolver;
use Directorist\WpMVC\Database\Eloquent\Model;

class Refund extends Model {
    public static function get_table_name():string {
        return 'directorist_refunds';
    }

    public function resolver():Resolver {
        return App::$container->get( Resolver::class );
    }
}