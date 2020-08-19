<?php

return array(

	////////////////////////////////////////
	// Localized JS Message Configuration //
	////////////////////////////////////////

	/**
	 * Validation Messages
	 */
	'validation' => array(
		'alphabet'     => __('Value needs to be Alphabet', 'wp-ultimate-exercise'),
		'alphanumeric' => __('Value needs to be Alphanumeric', 'wp-ultimate-exercise'),
		'numeric'      => __('Value needs to be Numeric', 'wp-ultimate-exercise'),
		'email'        => __('Value needs to be Valid Email', 'wp-ultimate-exercise'),
		'url'          => __('Value needs to be Valid URL', 'wp-ultimate-exercise'),
		'maxlength'    => __('Length needs to be less than {0} characters', 'wp-ultimate-exercise'),
		'minlength'    => __('Length needs to be more than {0} characters', 'wp-ultimate-exercise'),
		'maxselected'  => __('Select no more than {0} items', 'wp-ultimate-exercise'),
		'minselected'  => __('Select at least {0} items', 'wp-ultimate-exercise'),
		'required'     => __('This is required', 'wp-ultimate-exercise'),
	),

	/**
	 * Import / Export Messages
	 */
	'util' => array(
		'import_success'    => __('Import succeed, option page will be refreshed..', 'wp-ultimate-exercise'),
		'import_failed'     => __('Import failed', 'wp-ultimate-exercise'),
		'export_success'    => __('Export succeed, copy the JSON formatted options', 'wp-ultimate-exercise'),
		'export_failed'     => __('Export failed', 'wp-ultimate-exercise'),
		'restore_success'   => __('Restoration succeed, option page will be refreshed..', 'wp-ultimate-exercise'),
		'restore_nochanges' => __('Options identical to default', 'wp-ultimate-exercise'),
		'restore_failed'    => __('Restoration failed', 'wp-ultimate-exercise'),
	),

	/**
	 * Control Fields String
	 */
	'control' => array(
		// select2 select box
		'select2_placeholder' => __('Select option(s)', 'wp-ultimate-exercise'),
		// fontawesome chooser
		'fac_placeholder'     => __('Select an Icon', 'wp-ultimate-exercise'),
	),

);

/**
 * EOF
 */