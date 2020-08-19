<?php
/*
Template Name: MaxxFitness Features Blog
*/

/**
 * Torbara Maxx-Fitness Theme for WordPress, exclusively on Envato Market: http://themeforest.net/user/torbara
 * @encoding     UTF-8
 * @version      6.0.0
 * @copyright    Copyright (C) 2015 Torbara (http://torbara.com). All rights reserved.
 * @license      GNU General Public License version 2 or later, see http://www.gnu.org/licenses/gpl-2.0.html
 * @author       Alexandr Khmelnytsky (support@torbara.com)
 */
?>

<?php get_header(); ?>

<?php if (have_posts()) : ?>
    <h1 class="tm-title"><?php single_cat_title( '', true ); ?></h1>
    <div class="uk-grid" data-uk-grid-match="" data-uk-grid-margin="">
        <div class="uk-width-medium-1-1">
            <?php while (have_posts()) : the_post(); ?>
                <article <?php post_class('uk-article'); ?> data-permalink="<?php the_permalink(); ?>">
                    <h1 class="uk-article-title">
                        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
                    </h1>

                    <a class="uk-align-left" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_post_thumbnail(); ?></a>

                    <div>
                        <?php echo get_the_content("Read more ..."); ?>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
    </div>    
<?php endif; ?>

<?php get_template_part('_pagination'); ?>
    
<?php get_footer();