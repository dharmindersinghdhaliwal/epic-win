<?php
/*
Template Name: MaxxFitness Child Blog
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
    <div class="uk-grid" data-uk-grid-match="" data-uk-grid-margin="">
        <?php while (have_posts()) : the_post(); ?>
            <div class="uk-width-1-1">
                <article <?php post_class('uk-article'); ?> data-permalink="<?php the_permalink(); ?>">
                    <h1 class="uk-article-title">
                        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
                    </h1>
                    
                    <p class="uk-article-meta">
                        <?php
                            $date = '<time datetime="'.get_the_date('Y-m-d').'">'.get_the_date().'</time>';
                            printf(__('Written by %s on %s. Posted in %s', 'maxx-fitness'), '<a href="'.get_author_posts_url(get_the_author_meta('ID')).'" title="'.get_the_author().'">'.get_the_author().'</a>', $date, get_the_category_list(', '));
                        ?>
                    </p>

                    <a class="uk-align-left" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_post_thumbnail('original'); ?></a>
                    
                    <div>
                        <?php echo get_the_content("Read more ..."); ?>
                    </div>
                </article>
            </div>
        <?php endwhile; ?>
    </div>

<?php endif; ?>

<?php get_template_part('_pagination'); ?>

<?php get_footer();