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
 *
 * @return boolen
 */
function directorist_allow_read_context_permission( $permission, $context ) {
	if ( $context === 'read' ) {
		$permission = true;
	}

	return $permission;
}

add_filter( 'directorist_rest_check_permissions', 'directorist_allow_read_context_permission', 10, 2 );
