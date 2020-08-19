<?php

/* Include open source files for acessing social network API's */
require_once epic_path.'modules/social/lib/OAuth.php';
require_once epic_path.'modules/social/lib/linkedin/linkedin_3.2.0.class.php';
//require_once epic_path.'modules/social/lib/facebook/facebook.php';
require_once epic_path.'modules/social/lib/twitter/twitteroauth.php';
require_once epic_path.'modules/social/lib/google/autoload.php';
//require_once UAIO_PLUGIN_DIR.'lib/google/Service/Oauth2.php';
require_once epic_path.'modules/social/lib/facebook_SDK/autoload.php';


/* Include classes for handling social network login and registration */
require_once epic_path.'modules/social/class-epic-social-settings.php';
require_once epic_path.'modules/social/class-epic-social-connect.php';
require_once epic_path.'modules/social/class-epic-linkedin-connect.php';
require_once epic_path.'modules/social/class-epic-facebook-connect.php';
require_once epic_path.'modules/social/class-epic-twitter-connect.php';
require_once epic_path.'modules/social/class-epic-google-connect.php';

/* Load the shortcode files */
require_once epic_path.'modules/social/shortcodes/social-login-shortcodes.php';

function epic_language_entry($string,$domain = 'epic'){
	return __($string,$domain);
}
