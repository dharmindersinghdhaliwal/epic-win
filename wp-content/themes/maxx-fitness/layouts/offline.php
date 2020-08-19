<?php
/**
 * Torbara Maxx-Fitness Theme for WordPress, exclusively on Envato Market: http://themeforest.net/user/torbara
 * @encoding     UTF-8
 * @version      6.0.0
 * @copyright    Copyright (C) 2015 Torbara (http://torbara.com). All rights reserved.
 * @license      GNU General Public License version 2 or later, see http://www.gnu.org/licenses/gpl-2.0.html
 * @author       Alexandr Khmelnytsky (support@torbara.com)
 */

?>
<!DOCTYPE HTML>
<html lang="<?php echo esc_attr($this['config']->get('language')); ?>" dir="<?php echo esc_attr($this['config']->get('direction')); ?>" class="uk-height-1-1 tm-offline">
<head>
    <?php wp_head(); ?>
</head>
<body class="uk-height-1-1 uk-vertical-align uk-text-center tm-offline-page">
    <?php 
    $id = $this['config']->get('maintenance_page', 0); 
    $post = get_post($id); 
    if($post){
        $content = apply_filters('the_content', $post->post_content); 
        echo $content;
    }
    ?>
    
</body>
</html>