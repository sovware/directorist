<?php

namespace Directorist\App\Models;

defined( "ABSPATH" ) || exit;

use Directorist\WpMVC\Database\Eloquent\Relations\HasOne;
use Directorist\WpMVC\App;
use Directorist\WpMVC\Database\Resolver;
use Directorist\WpMVC\Database\Eloquent\Model;
use Directorist\WpMVC\Database\Eloquent\Relations\HasMany;
use Directorist\WpMVC\Database\Eloquent\Relations\BelongsToOne;

class Order extends Model {
    public static function get_table_name():string {
        return 'directorist_orders';
    }

    public function user():BelongsToOne {
        return $this->belongs_to_one( User::class, 'ID', 'user_id' );
    }

    public function payments(): HasMany {
        return $this->has_many( Payment::class, 'order_id', 'id' );
    }

    public function payment():HasOne {
        return $this->has_one( Payment::class, 'order_id', 'id' );
    }

    public function resolver():Resolver {
        return App::$container->get( Resolver::class );
    }
}