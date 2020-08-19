<?php
/*
Template Name: MaxxFitness Workouts Blog
*/
/**
 * Maxx Fitness Workouts category template for WP, exclusively on Envato Market: http://codecanyon.net/user/aklare
 * @encoding     UTF-8
 * @version      6.0.0
 * @copyright    Copyright (C) 2015 Aklare (http://aklare.com). All rights reserved.
 * @license      GNU General Public License version 2 or later, see http://www.gnu.org/licenses/gpl-2.0.html
 * @author       Alexandr Khmelnytsky (support@aklare.com)
 */

get_header(); ?>

<div class="uk-grid tm-page-workouts" data-uk-grid-match="" data-uk-grid-margin="">
    <div class="uk-width-1-1">
        <div class="uk-panel uk-panel-header">
            <h1 class="tm-title"><?php single_cat_title(); ?></h1>	
        </div>
    </div>

    <?php       
    
    while (have_posts()) {
        the_post();?>
        <div class="uk-width-medium-1-3">
            <article id="item-<?php echo get_the_ID(); ?>" <?php post_class('uk-article'); ?> data-permalink="<?php the_permalink() ?>">
                
                <a class="uk-align-left" href="<?php the_permalink() ?>" title="">
                    <?php the_post_thumbnail(array(400,340)); ?>
                </a>

                <div><?php the_content("Read more ..."); ?></div>

            </article>
        </div><?php
    }
    ?>
</div>


<?php get_sidebar(); ?>
<?php get_template_part('_pagination'); ?>
<?php get_footer();