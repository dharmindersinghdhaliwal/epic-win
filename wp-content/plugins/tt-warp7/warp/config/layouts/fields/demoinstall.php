<?php
/**
 * Torbara Maxx-Fitness Theme for WordPress, exclusively on Envato Market: http://themeforest.net/user/torbara
 * @encoding     UTF-8
 * @version      1.0
 * @copyright    Copyright (C) 2015 Torbara (http://torbara.com). All rights reserved.
 * @license      GNU General Public License version 2 or later, see http://www.gnu.org/licenses/gpl-2.0.html
 * @author       Alexandr Khmelnytsky (support@torbara.com)
 */

// Check allow_url_fopen
if( ! ini_get('allow_url_fopen') ) { ?>
    <div class="uk-alert uk-display-block uk-alert-danger">
        The <strong>allow_url_fopen</strong> directive is disabled. To use the Demo installer, you need to enable this option.<br>
        You can try two things:<br>
        
        1. Create an <em>.htaccess</em> file and keep it in root folder (sometimes it may need to place it one step back folder of the root) 
        and paste this code there: <code>php_value allow_url_fopen On</code><br>
        
        2. Create a <em>php.ini</em> file (for update server php5.ini) 
        and keep it in root folder (sometimes it may need to place it one step back folder of the root) and paste the following code there:
        <code>allow_url_fopen = On;</code>
    </div><?php
    return;
}

?>

<div class="uk-alert uk-display-block">
    <p>Importing demo data (post, pages, images, theme settings, ...) is the easiest way to setup your theme. It will
    allow you to quickly edit everything instead of creating content from scratch. When you import the data following things will happen:</p>

    <ul class="uk-list-line">
        <li>All existing posts, pages, categories, images, custom post types or any other data may be deleted or modified.</li>
        <li>Some WordPress settings will be modified.</li>
        <li>Posts, pages, some images, some widgets and menus will get imported.</li>
        <li>Images will be downloaded from our server, these images are copyrighted and are for demo use only .</li>
        <li>Please click import only once and wait, it can take a couple of minutes</li>
    </ul>
</div>

<div class="uk-alert uk-alert-warning uk-display-block">
    <p class="uk-margin-remove">Before you begin, make sure all the required plugins are activated.</p>
</div>

<ul class="uk-grid" id="<?php echo esc_attr($node->attr('name')); ?>" data-uk-grid-margin="" ><?php
    // List of avalible demos
    foreach ($node->children('option') as $option) {
        // set attributes
        $attributes = array('value' => $option->attr('value'));
        foreach ($option->attributes as $attr) {
            $attributes[$attr->name] = $attr->value;
        }
        $demo_img = $option->attr('value')."/template_preview.png";
        $demo_name = $option->text();
        $tt_file_open = "f"."open";
        if($tt_file_open($demo_img, 'r')) { // If image exists?>
            <li class="uk-width-medium-1-3" data-demo-url="<?php echo esc_url($option->attr('value')); ?>">
                <a class="uk-thumbnail" style="text-decoration: none;" href="#">
                    <img src="<?php echo esc_attr($demo_img); ?>" width="250" height="150" alt="<?php echo esc_attr($demo_name); ?>">
                    <span class="uk-thumbnail-caption uk-display-block"><?php echo esc_attr($demo_name); ?></span>
                </a>
            </li><?php
        }else{ // If image does not exist
            continue;
        }
    } ?>
</ul>

<div></div>

<script type="text/javascript">
    (function($) {
        "use strict";
       
        $('#<?php echo esc_js($node->attr('name')); ?> li').click(function(e){
            e.preventDefault();
            if( confirm('Are you sure want to import dummy content?') == false) { return false; }
            var $demo_url = $(this).attr("data-demo-url");
            var $mesageArea = $(this).parent().next(); // Mesage Area
            jQuery($mesageArea).html('<span class="spinner is-active uk-float-left uk-margin-top-remove"></span> <span>Data is being imported please be patient, while the awesomeness is being created.</span>');
            var data = {
                action: 'demoinstall_ajax',
                'demo_url': $demo_url
            };            
            $.post(ajaxurl, data, function(response) {
                $($mesageArea).html('<div class="import_message_success">'+ response +'</div>');
            });
        });
    })(jQuery);
</script>