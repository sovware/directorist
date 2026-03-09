<?php

namespace Directorist\DBModels;

defined( "ABSPATH" ) || exit;

use Directorist\Utils\Database\Eloquent\Relations\HasOne;
use Directorist\Utils\Database\Resolver;
use Directorist\Utils\Database\Eloquent\Model;
use Directorist\Utils\Database\Eloquent\Relations\HasMany;
use Directorist\Utils\Database\Eloquent\Relations\BelongsToOne;

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
        return new Resolver();
    }
}