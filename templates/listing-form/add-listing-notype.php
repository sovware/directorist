<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.0.7
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-add-listing-types directorist-w-100">
	<div class="<?php Helper::directorist_container_fluid(); ?>">
		<p class="directorist-listing-type-admin-notice directorist-alert directorist-alert-info"><?php echo ! empty( $args['error_notice'] ) ? $args['error_notice'] : ''; ?></p>
	</div>
</div>