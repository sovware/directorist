<?php

return array(

	////////////////////////////////////////
	// Localized JS Message Configuration //
	////////////////////////////////////////

	/**
	 * Validation Messages
	 */
	'validation' => array(
		'alphabet'     => __('Value needs to be Alphabet', 'directorist'),
		'alphanumeric' => __('Value needs to be Alphanumeric', 'directorist'),
		'numeric'      => __('Value needs to be Numeric', 'directorist'),
		'email'        => __('Value needs to be Valid Email', 'directorist'),
		'url'          => __('Value needs to be Valid URL', 'directorist'),
		'maxlength'    => __('Length needs to be less than {0} characters', 'directorist'),
		'minlength'    => __('Length needs to be more than {0} characters', 'directorist'),
		'maxselected'  => __('Select no more than {0} items', 'directorist'),
		'minselected'  => __('Select at least {0} items', 'directorist'),
		'required'     => __('This is required', 'directorist'),
	),

	/**
	 * Import / Export Messages
	 */
	'util' => array(
		'import_success'    => __('Import succeed, option page will be refreshed..', 'directorist'),
		'import_failed'     => __('Import failed', 'directorist'),
		'export_success'    => __('Export succeed, copy the JSON formatted options', 'directorist'),
		'export_failed'     => __('Export failed', 'directorist'),
		'restore_success'   => __('Restoration succeed, option page will be refreshed..', 'directorist'),
		'restore_nochanges' => __('Options identical to default', 'directorist'),
		'restore_failed'    => __('Restoration failed', 'directorist'),
	),

	/**
	 * Control Fields String
	 */
	'control' => array(
		// select2 select box
		'select2_placeholder' => __('Select option(s)', 'directorist'),
		// fontawesome chooser
		'fac_placeholder'     => __('Select an Icon', 'directorist'),
	),

);

/**
 * EOF
 */