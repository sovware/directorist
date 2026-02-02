<?php

namespace Directorist\App\Repositories;

defined( "ABSPATH" ) || exit;

use Exception;
use Directorist\App\Models\Post;
use Directorist\WpMVC\Repositories\Repository;
use Directorist\WpMVC\Database\Query\Builder;

class ListingRepository extends Repository {
    public function get_query_builder(): Builder {
        return Post::query( 'post' );
    }

    public function update_listing_status(  int $listing_id, string $status ): void {
        $result = wp_update_post( [
            'ID'          => $listing_id,
            'post_status' => $status
        ] );

        if ( is_wp_error( $result ) ) {
            throw new Exception( $result->get_error_message(), 400 );
        }
    }
}