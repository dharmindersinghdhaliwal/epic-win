<?php
/**
 * Torbara Maxx-Fitness Theme for WordPress, exclusively on Envato Market: http://themeforest.net/user/torbara
 * @encoding     UTF-8
 * @version      6.0.0
 * @copyright    Copyright (C) 2015 Torbara (http://torbara.com). All rights reserved.
 * @license      GNU General Public License version 2 or later, see http://www.gnu.org/licenses/gpl-2.0.html
 * @author       Alexandr Khmelnytsky (support@torbara.com)
 */

// get scripts
$scripts = $this['asset']->get('js');

// add scripts
if ($scripts) {
    foreach ($scripts as $script) {
        if ($url = $script->getUrl()) {
            printf("<script src=\"%s\"></script>\n", $url);
        } else {
            printf("<script>%s</script>\n", $script->getContent());
        }
    }
}

do_action('get_footer', array());

// output tracking code
//echo $this['config']->get('tracking_code');
?>