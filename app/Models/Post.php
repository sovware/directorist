<?php

namespace Directorist\App\Models;

defined( 'ABSPATH' ) || exit;

use Directorist\WpMVC\App;
use Directorist\WpMVC\Database\Eloquent\Model;
use Directorist\WpMVC\Database\Eloquent\Relations\HasMany;
use Directorist\WpMVC\Database\Resolver;

class Post extends Model {
    public static function get_table_name():string {
        return 'posts';
    }

    // public function meta(): HasMany {
    //     return $this->has_many( PostMeta::class, 'post_id', 'ID' );
    // }

    public function resolver():Resolver {
        return App::$container->get( Resolver::class );
    }
}