<?php
/**
 * @author  AazzTech
 * @since   7.0
 * @version 7.0
 */

if(isset($_GET['renew']) && ('token_expired' === $_GET['renew'])){?>
	<div class="alert alert-danger alert-dismissable fade show" role="alert">
		<span class="fa fa-info-circle"></span>
		<?php esc_html_e('Link appears to be invalid.', 'directorist'); ?>
	</div>
<?php }
if(isset($_GET['renew']) && ('success' === $_GET['renew'])){ ?>
	<div class="alert alert-info alert-dismissable fade show" role="alert">
		<span class="fa fa-info-circle"></span>
		<?php esc_html_e('Renewed successfully.', 'directorist'); ?>
	</div>
<?php }