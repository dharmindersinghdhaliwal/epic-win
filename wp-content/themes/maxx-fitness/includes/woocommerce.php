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

//Remove the breadcrumbs from woocommerce, We use custom widget
add_action( 'init', 'maxxfitness_remove_wc_breadcrumbs' );
function maxxfitness_remove_wc_breadcrumbs() {
    remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
}

// Display 4 products per page.
add_filter( 'loop_shop_per_page', 'loop_shop_filter' , 20 );

function loop_shop_filter($cols= ''){
	return 4;
}