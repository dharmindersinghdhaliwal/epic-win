<?php
/**
 * Torbara Maxx-Fitness Theme for WordPress, exclusively on Envato Market: http://themeforest.net/user/torbara
 * @encoding     UTF-8
 * @version      6.0.0
 * @copyright    Copyright (C) 2015 Torbara (http://torbara.com). All rights reserved.
 * @license      GNU General Public License version 2 or later, see http://www.gnu.org/licenses/gpl-2.0.html
 * @author       Alexandr Khmelnytsky (support@torbara.com)
 */

// get theme configuration
include($this['path']->path('layouts:theme.config.php'));

?>
<!DOCTYPE HTML>
<html <?php body_class(); ?> <?php language_attributes(); ?>  dir="<?php echo esc_attr($this['config']->get('direction')); ?>" data-config='<?php echo esc_attr($this['config']->get('body_config','{}')); ?>'>
    <head>
        <?php echo $this['template']->render('head'); ?>
        <?php wp_head(); ?>
    </head>
    <body class="<?php echo esc_attr($this['config']->get('body_classes')); ?>">
        <div class="ak-page">
                
            <?php if ($this['widgets']->count('toolbar-l + toolbar-r')) : // toolbar-l and toolbar-r module positions ?>
                <div class="tm-toolbar uk-clearfix uk-hidden-small">
                    <div class="uk-container uk-container-center">

                        <?php if ($this['widgets']->count('toolbar-l')) : ?>
                            <div class="uk-float-left"><?php echo $this['widgets']->render('toolbar-l'); ?></div>
                        <?php endif; ?>

                        <?php if ($this['widgets']->count('toolbar-r')) : ?>
                            <div class="uk-float-right"><?php echo $this['widgets']->render('toolbar-r'); ?></div>
                        <?php endif; ?>

                    </div>
                </div>
            <?php endif; ?>
                
            <nav class="tm-navbar uk-navbar <?php if ($this['config']->get('sticky_menu')=="0"){ echo "tm-sticky-menu"; } ?>  <?php echo $this['widgets']->count('fullscreen') !== 0 ? '' : 'uk-navbar-attached'; ?>">
                <div class="uk-container uk-container-center">

                    <?php if ( $this['widgets']->count('logo') ) : ?>
                        <a class="tm-logo" href="<?php echo esc_url($this['config']->get('site_url')); ?>"><?php echo $this['widgets']->render('logo'); ?></a>
                    <?php else : ?>
                        <a class="tm-logo" href="<?php echo esc_url($this['config']->get('site_url')); ?>"><span class="color-1"><?php echo esc_html($this['config']->get('logo_text')); ?></span></a>
                    <?php endif; ?>

                    <?php if ($this['widgets']->count('search')) : ?>
                        <div class="uk-navbar-flip">
                            <div class="uk-navbar-content uk-hidden-small"><?php echo $this['widgets']->render('search'); ?></div>
                        </div>
                    <?php endif; ?>

                    <?php if ($this['widgets']->count('menu')) : ?>
                        <?php echo $this['widgets']->render('menu'); ?>
                    <?php endif; ?>

                    <?php if ($this['widgets']->count('offcanvas')) : ?>
                        <a href="#offcanvas" class="uk-navbar-toggle uk-visible-small" data-uk-offcanvas></a>
                    <?php endif; ?>

                </div>
            </nav>
            
            <?php if ($this['widgets']->count('fullscreen')) : ?>
                <div class="tm-fullscreen uk-position-relative">
                    <?php echo $this['widgets']->render('fullscreen'); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($this['widgets']->count('top-a')) : ?>
                <div class="tm-block tm-block-top-a <?php echo esc_attr(implode(" ", $classes['block.top-a'])); ?>">
                    <div class="uk-container uk-container-center">
                        <section class="<?php echo esc_attr($grid_classes['top-a']); echo esc_attr($display_classes['top-a']); ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin>
                            <?php echo $this['widgets']->render('top-a', array('layout'=>$this['config']->get('grid.top-a.layout'))); ?>
                        </section>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($this['widgets']->count('top-b')) : ?>
                <div class="tm-block tm-block-top-b <?php echo esc_attr(implode(" ", $classes['block.top-b'])); ?>">
                    <div class="uk-container uk-container-center">
                        <section class="<?php echo esc_attr($grid_classes['top-b']); echo esc_attr($display_classes['top-b']); ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin>
                            <?php echo $this['widgets']->render('top-b', array('layout'=>$this['config']->get('grid.top-b.layout'))); ?>
                        </section>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if ($this['widgets']->count('top-c')) : ?>
                <div class="tm-block tm-block-top-c <?php echo esc_attr(implode(" ", $classes['block.top-c'])); ?>">
                    <div class="uk-container uk-container-center">
                        <section class="<?php echo esc_attr($grid_classes['top-c']); echo esc_attr($display_classes['top-c']); ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin>
                            <?php echo $this['widgets']->render('top-c', array('layout'=>$this['config']->get('grid.top-c.layout'))); ?>
                        </section>
                    </div>
                </div>        
            <?php endif; ?>
            
            <?php if ($this['widgets']->count('top-d')) : ?>
                <div class="tm-block tm-block-top-d <?php echo esc_attr(implode(" ", $classes['block.top-d'])); ?>">
                    <div class="uk-container uk-container-center">
                        <section class="<?php echo esc_attr($grid_classes['top-d']); echo esc_attr($display_classes['top-d']); ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin>
                            <?php echo $this['widgets']->render('top-d', array('layout'=>$this['config']->get('grid.top-d.layout'))); ?>
                        </section>
                    </div>
                </div>        
            <?php endif; ?>
            

            <?php if ($this['widgets']->count('main-top + main-bottom + sidebar-a + sidebar-b') || $this['config']->get('system_output', true)) : ?>
                <div class="uk-container uk-container-center">
                    <div class="tm-middle uk-grid" data-uk-grid-match data-uk-grid-margin>   
                        <?php if ($this['widgets']->count('main-top + main-bottom') || $this['config']->get('system_output', true)) : ?>
                        <div class="<?php echo $columns['main']['class'] ?>">

                            <?php if ($this['widgets']->count('main-top')) : ?>
                            <section class="<?php echo esc_attr($grid_classes['main-top']); echo esc_attr($display_classes['main-top']); echo " ".esc_attr(implode(" ", $classes['block.main-top'])); ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin>
                                <?php echo $this['widgets']->render('main-top', array('layout'=>$this['config']->get('grid.main-top.layout'))); ?>
                            </section>
                            <?php endif; ?>

                            <?php if ($this['config']->get('system_output', true)) : ?>
                            <main class="tm-content uk-position-relative">
                                <?php if ($this['widgets']->count('breadcrumbs')) : ?>
                                    <?php echo $this['widgets']->render('breadcrumbs'); ?>
                                <?php endif; ?>
                                
                                <?php echo $this['template']->render('content'); ?>
                            </main>
                            <?php endif; ?>

                            <?php if ($this['widgets']->count('main-bottom')) : ?>
                            <section class="<?php echo esc_attr($grid_classes['main-bottom']); echo esc_attr($display_classes['main-bottom']); echo " ".esc_attr(implode(" ", $classes['block.main-bottom'])); ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin>
                                <?php echo $this['widgets']->render('main-bottom', array('layout'=>$this['config']->get('grid.main-bottom.layout'))); ?>
                            </section>
                            <?php endif; ?>

                        </div>
                        <?php endif; ?>

                        <?php foreach($columns as $name => &$column) : ?>
                            <?php if ($name != 'main' && $this['widgets']->count($name)) : ?>
                                <aside class="<?php echo $column['class'] ?>" <?php if (!$this['config']->get('system_output', true)){ echo 'style="left:auto;"';} ?>><?php echo $this['widgets']->render($name) ?></aside>
                            <?php endif ?>
                        <?php endforeach ?>
                    </div>
                </div>
            <?php endif; ?>
                
            
            <?php if ($this['widgets']->count('bottom-a')) : ?>
                <div class="tm-block tm-block-bottom-a <?php echo esc_attr(implode(" ", $classes['block.bottom-a'])); ?>">
                    <div class="uk-container uk-container-center">
                        <section class="<?php echo esc_attr($grid_classes['bottom-a']); echo esc_attr($display_classes['bottom-a']); ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin>
                            <?php echo $this['widgets']->render('bottom-a', array('layout'=>$this['config']->get('grid.bottom-a.layout'))); ?>
                        </section>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($this['widgets']->count('bottom-b')) : ?>
                <div class="tm-block tm-block-bottom-b <?php echo esc_attr(implode(" ", $classes['block.bottom-b'])); ?>">
                    <div class="uk-container uk-container-center">
                        <section class="<?php echo esc_attr($grid_classes['bottom-b']); echo esc_attr($display_classes['bottom-b']); ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin>
                            <?php echo $this['widgets']->render('bottom-b', array('layout'=>$this['config']->get('grid.bottom-b.layout'))); ?>
                        </section>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($this['widgets']->count('bottom-c')) : ?>
                <div class="tm-block tm-block-bottom-c <?php echo esc_attr(implode(" ", $classes['block.bottom-c'])); ?>">
                    <div class="uk-container uk-container-center">
                        <section class="<?php echo esc_attr($grid_classes['bottom-c']); echo esc_attr($display_classes['bottom-c']); ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin>
                            <?php echo $this['widgets']->render('bottom-c', array('layout'=>$this['config']->get('grid.bottom-c.layout'))); ?>
                        </section>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($this['widgets']->count('bottom-d')) : ?>
                <div class="tm-block tm-block-bottom-d <?php echo esc_attr(implode(" ", $classes['block.bottom-d'])); ?>">
                    <div class="uk-container uk-container-center">
                        <section class="<?php echo esc_attr($grid_classes['bottom-d']); echo esc_attr($display_classes['bottom-d']); ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin>
                            <?php echo $this['widgets']->render('bottom-d', array('layout'=>$this['config']->get('grid.bottom-d.layout'))); ?>
                        </section>
                    </div>
                </div>
            <?php endif; ?>
            

            <?php if ($this['widgets']->count('social')) : ?>
                <div class="tm-block tm-block-social">
                    <div class="uk-container uk-container-center">
                        <div class="uk-panel ak-social">
                            <?php echo $this['widgets']->render('social'); ?>
                        </div>
                    </div>
                </div>
            <?php else : ?>
                <?php if(   $this['config']->get('social_links.Facebook.social_url') ||
                            $this['config']->get('social_links.Twitter.social_url') ||
                            $this['config']->get('social_links.Instagram.social_url') ||
                            $this['config']->get('social_links.Google+.social_url') ||
                            $this['config']->get('social_links.Linkedin.social_url') ||
                            $this['config']->get('social_links.Skype.social_url') ) : ?>
                    <div class="tm-block tm-block-social">
                        <div class="uk-container uk-container-center">
                            <div class="uk-panel ak-social">
                                <ul class="uk-subnav uk-subnav-line uk-text-center">
                                    <?php foreach ($this['config']->get('social_links') as $key => $soc) : ?>
                                        <?php if (trim($soc["social_url"])) : ?>
                                            <li><a href="<?php echo $soc["social_url"]; ?>" target="_blank"><?php echo $key; ?></a></li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
                
            <?php if ($this['widgets']->count('footer') || $this['config']->get('totop_scroller', true) || $this['config']->get('warp_branding', true) ) : ?>
                <div class="ak-footer">
                    <div class="uk-container uk-container-center">
                        <footer class="tm-footer uk-grid <?php echo esc_attr($grid_classes['footer']); echo " ".esc_attr(implode(" ", $classes['block.footer'])); ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin>

                                <?php if ($this['config']->get('totop_scroller', true)) : ?>
                                    <a class="tm-totop-scroller" data-uk-smooth-scroll href="#"></a>
                                <?php endif; ?>
                                <?php if ( $this['widgets']->count('footer') ): ?>
                                    <?php echo $this['widgets']->render('footer', array('layout'=>$this['config']->get('grid.footer.layout'))); ?>
                                <?php else : ?>
                                    <div><?php $this->output('warp_branding'); ?></div>
                                <?php endif; ?>

                        </footer>
                    </div>
                </div>
            <?php endif; ?>
            
        </div>

        <?php if ($this['widgets']->count('offcanvas')) : ?>
            <div id="offcanvas" class="uk-offcanvas">
                <div class="uk-offcanvas-bar"><?php echo $this['widgets']->render('offcanvas'); ?></div>
            </div>
        <?php endif; ?>
        
        <?php echo $this['widgets']->render('debug'); ?>
        
        <?php echo $this->render('footer'); ?>
        <?php wp_footer(); ?>
    </body>
</html>