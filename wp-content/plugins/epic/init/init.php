<?php

	/* Load plugin text domain (localization) */
	add_action('init', 'epic_load_textdomain');
	function epic_load_textdomain() {
		load_plugin_textdomain( 'epic', false,'/epic/l10n');
	}
	
	//allow redirection, even if my theme starts to send output to the browser
	add_action('init', 'epic_output_buffer');
	function epic_output_buffer() {
		ob_start();
	}