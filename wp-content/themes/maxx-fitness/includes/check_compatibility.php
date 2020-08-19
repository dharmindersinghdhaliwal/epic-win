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

//Check compatibility
if (!defined('PHP_VERSION_ID') || PHP_VERSION_ID < 50300) {
    function renter_admin_php_notice() { ?>
        <div class="error">
            <p><b><?php echo wp_get_theme(); ?> Theme error:</b> This theme requires PHP version 5.3 or higher.</p>
        </div>
        <?php
    }
    add_action('admin_notices', 'renter_admin_php_notice');

    return;
}

//Bootstrap Warp 7 Framework
get_template_part('warp');