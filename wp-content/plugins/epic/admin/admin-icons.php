<?php

	/* admin icons for the plugin */
	add_action('admin_head',  'epic_admin_icon');
	function epic_admin_icon(){
		$screen = get_current_screen();
		if( !strstr($screen->id, 'epic') )
			return;

		$image_url = epic_url.'admin/images/epic-icon-32.png';
		echo "<style>
		#epic-icon-wp-epic {
			background: transparent url( '{$image_url }' ) no-repeat;
		}
		</style>";
	}