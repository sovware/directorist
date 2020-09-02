<?php
/**
 * @author  AazzTech
 * @since   6.6
 * @version 6.6
 */

if( $renew_token_expired ) { ?>
	<div class="alert alert-danger alert-dismissable fade show" role="alert"><span class="fa fa-info-circle"></span><?php esc_html_e('Link appears to be invalid.', 'directorist'); ?></div>
	<?php
}

if( $renew_succeed ) { ?>
	<div class="alert alert-info alert-dismissable fade show" role="alert"><span class="fa fa-info-circle"></span><?php esc_html_e('Renewed successfully.', 'directorist'); ?></div>
	<?php
}