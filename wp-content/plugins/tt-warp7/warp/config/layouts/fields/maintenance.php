<?php
/**
 * Torbara Maxx-Fitness Theme for WordPress, exclusively on Envato Market: http://themeforest.net/user/torbara
 * @encoding     UTF-8
 * @version      1.0
 * @copyright    Copyright (C) 2015 Torbara (http://torbara.com). All rights reserved.
 * @license      GNU General Public License version 2 or later, see http://www.gnu.org/licenses/gpl-2.0.html
 * @author       Alexandr Khmelnytsky (support@torbara.com)
 */

?>
<p class="uk-form-controls-condensed">
    <select <?php echo $control->attributes(compact('name')); ?> > 
        <option value=""><?php echo esc_attr( 'Select Maintenance Page' ); ?></option> 
        

        <?php 
	$offline_pages = get_posts(array(
		'post_type'	=> 'maintenance_mode',
		'post_status'	=> 'publish',
		'orderby'	=> 'menu_order'
	));

        foreach ( $offline_pages as $page ) {
            if($page->ID == $value){
                $option = '<option value="' . $page->ID . '" selected="selected">';
            }else{
                $option = '<option value="' . $page->ID . '">';
            }
            $option .= $page->post_title;
            $option .= '</option>';
            echo $option;
        }?>
        
    </select>
    
    <?php
    if ($node->attr('description')) {
        echo '<span class="uk-form-help-inline">'.$node->attr('description').'</span>';
    } ?>
</p>