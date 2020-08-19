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
<html lang="<?php echo esc_attr($this['config']->get('language')); ?>" dir="<?php echo esc_attr($this['config']->get('direction')); ?>" class="uk-height-1-1 tm-error">
<head>
    <?php wp_head(); ?>
</head>
<body class="uk-height-1-1 uk-vertical-align uk-text-center tm-error-page">

	<div class="uk-vertical-align-bottom uk-container-center">

            <h1 class="tm-error-headline uk-text-left"><?php echo esc_html($error); ?></h1>
            <div class="tm-error-subline uk-text-left">Error</div>
            <h2 class="tm-error-text uk-text-left"><?php echo esc_html($title); ?></h2>

            <p class="uk-text-left"><a class="uk-button uk-button-large" href="javascript:history.go(-1)">Go to Home Page</a></p>
            
            <ul class="uk-subnav uk-subnav-line">
                <?php foreach ($this['config']->get('social_links') as $key => $soc) : ?>
                    <?php if (trim($soc["social_url"])) : ?>
                        <li><a href="<?php echo esc_url ($soc["social_url"]); ?>" target="_blank"><?php echo esc_html($key); ?></a></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
            
	</div>
        
</body>
</html>