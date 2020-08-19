/**
 * MaxxFitness Schedule — Classes schedule module for Wordpress, exclusively on Envato Market: http://themeforest.net/user/torbara
 * @encoding     UTF-8
 * @version      1.0
 * @copyright    Copyright (C) 2015 Aklare (http://torbara.com). All rights reserved.
 * @license      GNU General Public License version 2 or later, see http://www.gnu.org/licenses/gpl-2.0.html
 * @author       Alexandr Khmelnytsky (support@torbara.com)
 */

jQuery(function() {
    if(jQuery("div").is(".akmf-box")){
        var pane = jQuery('.akmf-box').jScrollPane({animateScroll: true});
        var api = pane.data('jsp');
        var scrollTo = 9999;
        jQuery(".akmf-box .akmf-class").each(function(index){
            if(scrollTo > jQuery(this).position().left){
               scrollTo = jQuery(this).position().left;
            }
        });
        scrollTo = scrollTo-30;
        api.scrollTo(scrollTo);
    }
});