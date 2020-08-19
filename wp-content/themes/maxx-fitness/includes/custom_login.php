<?php
/**
 * 
 * Torbara Maxx-Fitness Theme for WordPress, exclusively on Envato Market: http://themeforest.net/user/torbara?ref=torbara
 * @encoding     UTF-8
 * @version      6.0.0
 * @copyright    Copyright (C) 2015 Torbara (http://torbara.com). All rights reserved.
 * @license      GNU General Public License version 2 or later, see http://www.gnu.org/licenses/gpl-2.0.html
 * @author       Alexandr Khmelnytsky (support@torbara.com)
 * @support      support@torbara.com
 * 
 */

// Custom style for login page
function maxxfitness_login_logo() { ?>
    <style type="text/css">
        body.login {
            color: #151924;
        }
        
        .login h1 a {
            background-image: url(<?php echo get_template_directory_uri(); ?>/images/torbara-logo-white.png);
            padding-bottom: 30px;
        }
        
        body, html {
            background-color: #151924;
            color: #fff;
        }
        
        .login .message { 
            color: #333;
        }
        
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'maxxfitness_login_logo' );

//Logo url
function maxxfitness_login_logo_url() {
    return "http://themeforest.net/user/torbara/portfolio?ref=torbara";
}
add_filter('login_headerurl', 'maxxfitness_login_logo_url');

//Logo title
function maxxfitness_login_logo_url_title() {
    return 'Torbara - We are developing beautiful themes and applications for the web.';
}
add_filter( 'login_headertitle', 'maxxfitness_login_logo_url_title' );