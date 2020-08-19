<?php
/**
 * Torbara Image field for Warp 7, exclusively on Envato Market: http://themeforest.net/user/torbara?ref=torbara
 * @encoding     UTF-8
 * @version      1.0
 * @copyright    Copyright (C) 2016 Torbara (http://torbara.com). All rights reserved.
 * @license      GNU General Public License version 2 or later, see http://www.gnu.org/licenses/gpl-2.0.html
 * @author       Alexandr Khmelnytsky (support@torbara.com)
 */

// jQuery b
wp_enqueue_script('jquery');
// This will enqueue the Media Uploader script
wp_enqueue_media(); 
// OpenMediaFrame scripts
wp_enqueue_script('image_warp', get_template_directory_uri() . '/warp/config/layouts/fields/image.js', array('jquery')); ?>

<div style="width: 160px;">
<?php printf('<input %s  style="width:130px; margin-right: 5px;">', $control->attributes(array_merge($node->attr(), array('type' => 'text', 'name' => $name, 'value' => $value)), array('label', 'description', 'default'))); ?>
<?php echo '<a href="#" style="margin-top: 4px;" class="dashicons dashicons-images-alt2" title="'.$node->attr('description').'" onclick="OpenMediaFrame(jQuery(this).prev()); return false;"></a>'; ?>
</div>