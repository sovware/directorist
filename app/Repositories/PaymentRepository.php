<?php

namespace Directorist\App\Repositories;

defined( "ABSPATH" ) || exit;

use Directorist\App\Models\Payment;
use Directorist\WpMVC\Repositories\Repository;
use Directorist\WpMVC\Database\Query\Builder;
use Directorist\WpMVC\Exceptions\Exception;

class PaymentRepository extends Repository {
    public function get_query_builder(): Builder {
        return Payment::query( 'payment' );
    }

    public function get() {
        $query = $this->get_query_builder();
        return $query->order_by_desc( 'id' )->get();
    }
}