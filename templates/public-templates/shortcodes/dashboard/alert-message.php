<?php
/**
 * @author  AazzTech
 * @since   7.0
 * @version 7.0
 */

if( $renew_token_expired ) { ?>
	<div class="alert alert-danger alert-dismissable fade show" role="alert"><span class="fa fa-info-circle"></span><?php esc_html_e('Link appears to be invalid.', 'directorist'); ?></div>
	<?php
}

if( $renew_succeed ) { ?>
	<div class="alert alert-info alert-dismissable fade show" role="alert"><span class="fa fa-info-circle"></span><?php esc_html_e('Renewed successfully.', 'directorist'); ?></div>
	<?php
}