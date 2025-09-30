<?php
/**
 * Rest API filter functions.
 */
defined( 'ABSPATH' ) || exit;

/**
 * Allow rest api endpoint permission to all read context requests.
 *
 * @param boolen $permission
 * @param string $context
 * @param integer $object_id
 * @param string @object_type
 *
 * @return boolen
 */
function directorist_allow_read_context_permission( $permission, $context, $object_id, $object_type ) {
	if ( $context === 'read' ) {
		$permission = true;
	}

	if ( $context === 'create' && $object_type === 'user' ) {
		$permission = true;
	}

	return $permission;
}

add_filter( 'directorist_rest_check_permissions', 'directorist_allow_read_context_permission', 10, 4 );
