<?php

	add_filter('widget_text', 'do_shortcode');

	function epic_refresh_mce( $ver ) {
		$ver += 3;
		return $ver;
	}

	add_action( 'init', 'epic_add_shortcode_button' );
	//add_filter( 'tiny_mce_version', 'epic_refresh_mce' );

	function epic_add_shortcode_button() {
		if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) return;
		if ( get_user_option('rich_editing') == 'true') :
			add_filter('mce_external_plugins', 'epic_add_shortcode_tinymce_plugin');
			add_filter('mce_buttons', 'epic_register_shortcode_button');
		endif;
	}

	function epic_register_shortcode_button($buttons) {
		array_push($buttons,  "epic_shortcodes_button");
		return $buttons;
	}

	function epic_add_shortcode_tinymce_plugin($plugin_array) {
		global $epic,$wp_version;

		/* Only allows epic shortcode buttons in visual editor to admin users */
		if(current_user_can('manage_options') || current_user_can('manage_epic_options') ){
			#tinymce was not working so tried to run old file (Not sure functionality is workig or not) but erros removed _2020_
		//	if ( version_compare( $wp_version, '3.9', '>=' ) ) {
			//	$plugin_array['epicShortcodes'] = epic_url . 'admin/js/editor_plugin_tinymce_4.js';
			//} else {
				$plugin_array['epicShortcodes'] = epic_url . 'admin/js/editor_plugin.js';
			//}
		}

		return $plugin_array;
	}
