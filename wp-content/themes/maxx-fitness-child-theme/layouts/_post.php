<?php
/**
 * Torbara Maxx-Fitness Theme for WordPress, exclusively on Envato Market: http://themeforest.net/user/torbara
 * @encoding     UTF-8
 * @version      6.0.0
 * @copyright    Copyright (C) 2015 Torbara (http://torbara.com). All rights reserved.
 * @license      GNU General Public License version 2 or later, see http://www.gnu.org/licenses/gpl-2.0.html
 * @author       Alexandr Khmelnytsky (support@torbara.com)
 */
?>
<div class="uk-width-medium-1-3 uk-grid-margin">
<article id="item-<?php the_ID(); ?>" <?php post_class('uk-article');?> data-permalink="<?php the_permalink(); ?>">
    <h1 class="uk-article-title dummy"><a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>

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
    
    <?php //the_content(''); ?>

    <ul class="uk-subnav uk-subnav-line">
        <li><a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php _e('Continue Reading', 'maxx-fitness'); ?></a></li>
        <?php if(comments_open() || get_comments_number()) : ?>
            <li><?php comments_popup_link(__('No Comments', 'maxx-fitness'), __('1 Comment', 'maxx-fitness'), __('% Comments', 'maxx-fitness'), "", ""); ?></li>
        <?php endif; ?>
    </ul>
    <?php edit_post_link(__('Edit this post.', 'maxx-fitness'), '<p><i class="uk-icon-pencil"></i> ','</p>'); ?>

</article>

<div class="uk-panel uk-width-1-1">
  <div class="uk-grid">
    <div class="uk-width-1-3">
      <h6 class="tm-uppercase">Duration</h6>
      <span class="tm-uppercase">7:50 Minutes</span>
    </div>
    <div class="uk-width-1-3">
      <h6 class="tm-uppercase">Exercises</h6>
      <span class="tm-uppercase">Rowing</span>
    </div>
    <div class="uk-width-1-3">
      <h6 class="tm-uppercase">Level</h6>
      <span class="tm-uppercase">Hard</span>
    </div>
  </div>
</div>

</div>


