<?php 
/*
Template Name: MaxxFitness Classes Blog
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

<div class="uk-grid tm-page-classes" data-uk-grid-match="" data-uk-grid-margin="">
    <div class="uk-width-1-1">
        <div class="uk-panel uk-panel-header">
            <h1 class="tm-title"><?php single_cat_title(); ?></h1>	
        </div>
    </div>
    
    <?php 
        $categories = get_the_category();
        $category_id = $categories[0]->cat_ID; 
        query_posts('category__in='.$category_id.'&showposts=20');
        
        

        while (have_posts()) {
            the_post();?>
            <div class="uk-width-medium-1-2">
                <article id="item-<?php echo get_the_ID(); ?>" <?php post_class('uk-article'); ?> data-permalink="<?php the_permalink() ?>">
                    <h1 class="uk-article-title">
                        <a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
                    </h1>

                    <div><?php the_content("Read more ..."); ?></div>
                    
                </article>
            </div><?php
        }
    ?>

</div>

<?php get_template_part('_pagination'); ?>
<?php get_sidebar(); ?>
<?php get_footer();

