<?php

namespace Directorist\App\Repositories;

defined( "ABSPATH" ) || exit;

use Directorist\App\Models\Subscription;
use Directorist\WpMVC\Repositories\Repository;
use Directorist\WpMVC\Database\Query\Builder;
use Directorist\WpMVC\Exceptions\Exception;

class SubscriptionRepository extends Repository {
    public function get_query_builder(): Builder {
        return Subscription::query( 'subscription' );
    }

    public function get(): array {
        $query = $this->get_query_builder();
        return $query->order_by_desc( 'id' )->get();
    }

    /**
     * Create a new subscription.
     *
     * @param \Directorist\App\DTO\Subscription\DTO $dto The DTO containing subscription data.
     * @return int The ID of the newly created subscription.
     * @throws Exception If the insert operation fails.
     */
    public function create( \Directorist\WpMVC\DTO\DTO $dto ): int {
        do_action( 'directorist_before_subscription_create', $dto );

        return parent::create( $dto ); 
    }

    /**
     * Update an existing subscription.
     *
     * @param \Directorist\App\DTO\Subscription\DTO $dto The DTO containing updated subscription data.
     * @return int The number of affected rows.
     * @throws Exception If the update operation fails.
     */
    public function update( \Directorist\WpMVC\DTO\DTO $dto ): int {
        do_action( 'directorist_before_subscription_update', $dto );

        return parent::update( $dto );
    }
}