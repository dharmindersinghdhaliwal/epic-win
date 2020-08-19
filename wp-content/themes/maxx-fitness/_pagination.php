<?php
/**
 * Torbara Maxx-Fitness Theme for WordPress, exclusively on Envato Market: http://themeforest.net/user/torbara
 * @encoding     UTF-8
 * @version      6.0.0
 * @copyright    Copyright (C) 2015 Torbara (http://torbara.com). All rights reserved.
 * @license      GNU General Public License version 2 or later, see http://www.gnu.org/licenses/gpl-2.0.html
 * @author       Alexandr Khmelnytsky (support@torbara.com)
 */



global $wp_query, $post, $wpdb;

$page = get_query_var('paged');
$posts_per_page = intval(get_query_var('posts_per_page'));
$pages = intval(ceil($wp_query->found_posts / $posts_per_page));

$page = !empty($page) ? intval($page) : 1;

$output = array();

if ($pages > 1) {

    $current = $page;
    $max     = 3;
    $end     = $pages;
    $range   = ($current + $max < $end) ? range($current, $current + $max) : range($current - ($current + $max - $end), $end);


    $output[] = '<ul class="uk-pagination">';

    $range_start = max($page - $max, 1);
    $range_end   = min($page + $max - 1, $pages);

    if ($page > 1) {

        $link     = (isset($type)&&($type === 'posts')) ? get_pagenum_link($page-1) : get_comments_pagenum_link($page-1);
        //$output[] = '<li><a href="'.$link.'"><i class="uk-icon-angle-double-left"></i></a></li>';
    }
    
    for ($i = 1; $i <= $end; $i++) {


        if($i==1 || $i==$end || in_array($i, $range)) {

            if ($i == $page) {
                $output[] = '<li class="uk-active"><span>'.$i.'</span></li>';
            } else {
                $link  = get_pagenum_link($i);
                $output[] = '<li><a href="'.$link.'">'.$i.'</a></li>';
            }

        }else{
            $output[] = '#';
        }
    }

    if ($page < $pages) {
        $link     = (isset($type)&&($type === 'posts')) ? get_pagenum_link($page+1) : get_comments_pagenum_link($page+1);
        //$output[] = '<li><a href="'.$link.'"><i class="uk-icon-angle-double-right"></i></a></li>';
    }

    $output[] = '</ul>';

    $output   = preg_replace('/>#+</', '><li><span>...</span></li><', implode("", $output));

    echo  ' '.$output;
}