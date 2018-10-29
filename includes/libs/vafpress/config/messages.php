<?php

return array(

	////////////////////////////////////////
	// Localized JS Message Configuration //
	////////////////////////////////////////

	/**
	 * Validation Messages
	 */
	'validation' => array(
		'alphabet'     => __('Value needs to be Alphabet', ATBDP_TEXTDOMAIN),
		'alphanumeric' => __('Value needs to be Alphanumeric', ATBDP_TEXTDOMAIN),
		'numeric'      => __('Value needs to be Numeric', ATBDP_TEXTDOMAIN),
		'email'        => __('Value needs to be Valid Email', ATBDP_TEXTDOMAIN),
		'url'          => __('Value needs to be Valid URL', ATBDP_TEXTDOMAIN),
		'maxlength'    => __('Length needs to be less than {0} characters', ATBDP_TEXTDOMAIN),
		'minlength'    => __('Length needs to be more than {0} characters', ATBDP_TEXTDOMAIN),
		'maxselected'  => __('Select no more than {0} items', ATBDP_TEXTDOMAIN),
		'minselected'  => __('Select at least {0} items', ATBDP_TEXTDOMAIN),
		'required'     => __('This is required', ATBDP_TEXTDOMAIN),
	),

	/**
	 * Import / Export Messages
	 */
	'util' => array(
		'import_success'    => __('Import succeed, option page will be refreshed..', ATBDP_TEXTDOMAIN),
		'import_failed'     => __('Import failed', ATBDP_TEXTDOMAIN),
		'export_success'    => __('Export succeed, copy the JSON formatted options', ATBDP_TEXTDOMAIN),
		'export_failed'     => __('Export failed', ATBDP_TEXTDOMAIN),
		'restore_success'   => __('Restoration succeed, option page will be refreshed..', ATBDP_TEXTDOMAIN),
		'restore_nochanges' => __('Options identical to default', ATBDP_TEXTDOMAIN),
		'restore_failed'    => __('Restoration failed', ATBDP_TEXTDOMAIN),
	),

	/**
	 * Control Fields String
	 */
	'control' => array(
		// select2 select box
		'select2_placeholder' => __('Select option(s)', ATBDP_TEXTDOMAIN),
		// fontawesome chooser
		'fac_placeholder'     => __('Select an Icon', ATBDP_TEXTDOMAIN),
	),

);

/**
 * EOF
 */