<?php
/**
 * Rest API filter functions.
 */
defined( 'ABSPATH' ) || exit;

/**
 * Allow public REST API read requests.
 *
 * Object-level post requests are public only when the post status is public.
 *
 * @param bool   $permission  Whether the request is permitted.
 * @param string $context     Permission context.
 * @param int    $object_id   Object ID, or zero for collection requests.
 * @param string $object_type Object type.
 * @return bool Whether the request is permitted.
 */
function directorist_allow_read_context_permission( $permission, $context, $object_id, $object_type ) {
    if ( $context === 'read' ) {
        $permission = true;

        if ( $object_id && post_type_exists( $object_type ) ) {
            $post = get_post( $object_id );

            if ( $post && $post->post_type === $object_type ) {
                $post_status = get_post_status_object( $post->post_status );
                $permission  = ( $post_status && $post_status->public ) || current_user_can( 'edit_post', $post->ID );
            }
        }
    }

    if ( $context === 'create' && $object_type === 'user' ) {
        $permission = true;
    }

    return $permission;
}

add_filter( 'directorist_rest_check_permissions', 'directorist_allow_read_context_permission', 10, 4 );
