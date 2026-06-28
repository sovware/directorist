<?php

namespace Directorist\DBModels;

defined( 'ABSPATH' ) || exit;

use Directorist\Utils\Database\Eloquent\Model;
// use Directorist\Utils\Database\Eloquent\Relations\HasMany;
use Directorist\Utils\Database\Resolver;

class Post extends Model {
    public static function get_table_name():string {
        return 'posts';
    }

    // public function meta(): HasMany {
    //     return $this->has_many( PostMeta::class, 'post_id', 'ID' );
    // }

    public function resolver():Resolver {
        return new Resolver();
    }
}