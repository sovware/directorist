<?php

namespace Directorist\Repositories;

defined( "ABSPATH" ) || exit;

use Directorist\DBModels\Subscription;
use Directorist\Utils\Repositories\Repository;
use Directorist\Utils\Database\Query\Builder;
use Directorist\Utils\Exception;

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
     * @param \Directorist\DTO\Subscription\DTO $dto The DTO containing subscription data.
     * @return int The ID of the newly created subscription.
     * @throws Exception If the insert operation fails.
     */
    public function create( \Directorist\Utils\DTO $dto ): int {
        do_action( 'directorist_before_subscription_create', $dto );

        return parent::create( $dto ); 
    }

    /**
     * Update an existing subscription.
     *
     * @param \Directorist\DTO\Subscription\DTO $dto The DTO containing updated subscription data.
     * @return int The number of affected rows.
     * @throws Exception If the update operation fails.
     */
    public function update( \Directorist\Utils\DTO $dto ): int {
        do_action( 'directorist_before_subscription_update', $dto );

        return parent::update( $dto );
    }
}