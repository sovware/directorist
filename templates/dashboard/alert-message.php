<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.6
 */

if( $renew_token_expired ) { ?>
	<div class="atbd-alert atbd-alert-danger atbd-alert-dismissable"><span class="fa fa-info-circle"></span><?php esc_html_e('Link appears to be invalid.', 'directorist'); ?></div>
	<?php
}

if( $renew_succeed ) { ?>
	<div class="atbd-alert atbd-alert-info atbd-alert-dismissable"><span class="fa fa-info-circle"></span><?php esc_html_e('Renewed successfully.', 'directorist'); ?></div>
	<?php
}
if( $confirmation_msg ) { ?>
	 <div class="col-lg-12">
		<div class="atbd-alert atbd-alert-info atbd-alert-dismissible">
			<?php echo $confirmation_msg; ?>
			<button type="button" class="atbd-alert-close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
	</div>
	<?php
}