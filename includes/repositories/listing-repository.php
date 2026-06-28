<?php

namespace Directorist\Repositories;

defined( "ABSPATH" ) || exit;

use Directorist\DBModels\Post;
use Directorist\Utils\Exception;
use Directorist\Utils\Repositories\Repository;
use Directorist\Utils\Database\Query\Builder;

class ListingRepository extends Repository {
    public function get_query_builder(): Builder {
        return Post::query( 'post' );
    }

    public function update_listing_status( int $listing_id, string $status ): void {
        $result = wp_update_post(
            [
                'ID'          => $listing_id,
                'post_status' => $status
            ] 
        );

        if ( is_wp_error( $result ) ) {
            throw new Exception( $result->get_error_message(), 400 );
        }
    }
}