<?php
/**
 * Torbara Maxx-Fitness Theme for WordPress, exclusively on Envato Market: http://themeforest.net/user/torbara
 * @encoding     UTF-8
 * @version      6.0.0
 * @copyright    Copyright (C) 2015 Torbara (http://torbara.com). All rights reserved.
 * @license      GNU General Public License version 2 or later, see http://www.gnu.org/licenses/gpl-2.0.html
 * @author       Alexandr Khmelnytsky (support@torbara.com)
 */

if (have_posts()) : ?>
jjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjjj
    <?php while (have_posts()) : the_post(); ?>

    <article <?php post_class('uk-article'); ?> data-permalink="<?php the_permalink(); ?>">

        <h1 class="uk-article-title "><?php the_title(); ?></h1>

        <p class="uk-article-meta">
            <?php
                $date = '<time datetime="'.get_the_date('Y-m-d').'">'.get_the_date().'</time>';
                printf(__('Written by %s on %s. Posted in %s', 'maxx-fitness'), '<a href="'.get_author_posts_url(get_the_author_meta('ID')).'" title="'.get_the_author().'">'.get_the_author().'</a>', $date, get_the_category_list(', '));
            ?>
        </p>

        <?php if (has_post_thumbnail()) : ?>
            <?php
            $width = get_option('thumbnail_size_w'); //get the width of the thumbnail setting
            $height = get_option('thumbnail_size_h'); //get the height of the thumbnail setting
            ?>
            <a href="<?php the_permalink() ?>" class="uk-align-left" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail(array($width, $height), array('class' => '')); ?></a>
        <?php endif; ?>
        
        <?php the_content(''); ?>

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

        <?php
            $prev = get_previous_post();
            $next = get_next_post();
        ?>

        <?php if ($this['config']->get('post_nav', 0) && ($prev || $next)) : ?>
        <ul class="uk-pagination">
            <?php if ($next) : ?>
            <li class="uk-pagination-next">
                <a href="<?php echo get_permalink($next->ID) ?>" title="<?php echo __('Next', 'maxx-fitness'); ?>">
                    <?php echo __('Next', 'maxx-fitness'); ?>
                    <i class="uk-icon-arrow-right"></i>
                </a>
            </li>
            <?php endif; ?>
            <?php if ($prev) : ?>
            <li class="uk-pagination-previous">
                <a href="<?php echo get_permalink($prev->ID) ?>" title="<?php echo __('Prev', 'maxx-fitness'); ?>">
                    <i class="uk-icon-arrow-left"></i>
                    <?php echo __('Prev', 'maxx-fitness'); ?>
                </a>
            </li>
            <?php endif; ?>
        </ul>
        <?php endif; ?>

    </article>

    <?php endwhile; ?>
 <?php endif; ?>