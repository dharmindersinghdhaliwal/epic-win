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
    <?php while (have_posts()) : the_post(); ?>

    <article <?php post_class('uk-article'); ?>>

        <?php if ($this['config']->get('page_title', true)) : ?>
        <h1 class="uk-article-title"><?php the_title(); ?></h1>
        <?php endif; ?>
        
        <?php if (has_post_thumbnail()) : ?>
            <?php
            $width = get_option('thumbnail_size_w'); //get the width of the thumbnail setting
            $height = get_option('thumbnail_size_h'); //get the height of the thumbnail setting
            ?>
            <a href="<?php the_permalink() ?>" class="uk-align-left" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail(array($width, $height), array('class' => '')); ?></a>
        <?php endif; ?>

        <?php the_content(''); ?>

        <?php edit_post_link(__('Edit this post.', 'maxx-fitness'), '<p><i class="uk-icon-pencil"></i> ','</p>'); ?>

    </article>

    <?php endwhile; ?>
<?php endif; ?>

<?php comments_template(); ?>