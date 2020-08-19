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

        <h1 class="uk-article-title"><?php the_title(); ?></h1>

        <p class="uk-article-meta">
            <?php
                $date = '<time datetime="'.get_the_date('Y-m-d').'">'.get_the_date().'</time>';
                printf(__('Published by %s on %s', 'maxx-fitness'), '<a href="'.get_author_posts_url(get_the_author_meta('ID')).'" title="'.get_the_author().'">'.get_the_author().'</a>', $date);
            ?>.

            <?php
                if (wp_attachment_is_image()) {
                    $metadata = wp_get_attachment_metadata();
                    printf(__('Full size is %s pixels.', 'maxx-fitness'),
                        sprintf('<a href="%1$s" title="%2$s">%3$s&times;%4$s</a>',
                            wp_get_attachment_url(),
                            esc_attr(__('Link to full-size image', 'maxx-fitness')),
                            $metadata['width'],
                            $metadata['height']
                        )
                    );
                }
            ?>

        </p>

        <p><a href="<?php echo wp_get_attachment_url(); ?>" title="<?php the_title_attribute(); ?>"><?php echo wp_get_attachment_image($post->ID, 'full-size'); ?></a></p>

        <?php edit_post_link(__('Edit this attachment.', 'maxx-fitness'), '<p><i class="uk-icon-pencil"></i> ','</p>'); ?>

    </article>

    <?php comments_template(); ?>

    <?php endwhile; ?>
<?php endif; ?>