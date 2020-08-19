<?php
/*
Template Name: MaxxFitness Trainers Article
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


<?php $category = get_the_category();?>
<h1 class="tm-title"><?php echo esc_html($category[0]->cat_name); ?></h1>
<?php if (have_posts()) : ?>
    <div class="uk-grid" data-uk-grid-match="" data-uk-grid-margin="">
        <div class="uk-width-medium-1-1">
            <?php while (have_posts()) : the_post(); ?>
                <article <?php post_class('uk-article'); ?> data-permalink="<?php the_permalink(); ?>">
                    <h1 class="uk-article-title">
                        <?php the_title(); ?>
                    </h1>
                    
                    <div class="uk-align-left"><?php the_post_thumbnail(array(295,295)); ?></div>

                    <div>
                        <?php the_content(''); ?>
                    </div>
                    
                    <?php wp_link_pages(); ?>
        
                    <?php the_tags('<p>'.__('Tags: ', 'maxx-fitness'), ', ', '</p>'); ?>

                    <?php edit_post_link(__('Edit this post.', 'maxx-fitness'), '<p><i class="uk-icon-pencil"></i> ','</p>'); ?>

                    <?php if (pings_open()) : ?>
                    <p><?php printf(__('<a href="%s">Trackback</a> from your site.', 'maxx-fitness'), get_trackback_url()); ?></p>
                    <?php endif; ?>

                    <?php if (get_the_author_meta('description')) : ?>
                    <div class="uk-panel uk-panel-box">

                        <div class="uk-align-medium-left">

                            <?php echo get_avatar(get_the_author_meta('user_email')); ?>

                        </div>

                        <h2 class="uk-h3 uk-margin-top-remove"><?php the_author(); ?></h2>

                        <div class="uk-margin"><?php the_author_meta('description'); ?></div>

                    </div>
                    <?php endif; ?>

                    <?php comments_template(); ?>
                    
                </article>
            <?php endwhile; ?>
        </div>
    </div>    
<?php endif; ?>

<?php get_footer(); ?>