<?php
/**
 * Torbara Maxx-Fitness Theme for WordPress, exclusively on Envato Market: http://themeforest.net/user/torbara
 * @encoding     UTF-8
 * @version      6.0.0
 * @copyright    Copyright (C) 2015 Torbara (http://torbara.com). All rights reserved.
 * @license      GNU General Public License version 2 or later, see http://www.gnu.org/licenses/gpl-2.0.html
 * @author       Alexandr Khmelnytsky (support@torbara.com)
 */

/*
 * Generate 3-column layout
 */
$config          = $this['config'];
$sidebars        = $config->get('sidebars', array());
$columns         = array('main' => array('width' => 60, 'alignment' => 'right'));
$sidebar_classes = '';

$gcf = function($a, $b = 60) use(&$gcf) {
    return (int) ($b > 0 ? $gcf($b, $a % $b) : $a);
};

$fraction = function($nominator, $divider = 60) use(&$gcf) {
    return $nominator / ($factor = $gcf($nominator, $divider)) .'-'. $divider / $factor;
};

foreach ($sidebars as $name => $sidebar) {
	if (!$this['widgets']->count($name)) {
        unset($sidebars[$name]);
        continue;
    }

    $columns['main']['width'] -= @$sidebar['width'];
    $sidebar_classes .= " tm-{$name}-".@$sidebar['alignment'];
}

if ($count = count($sidebars)) {
	$sidebar_classes .= ' tm-sidebars-'.$count;
}

$columns += $sidebars;
foreach ($columns as $name => &$column) {

    $column['width']     = isset($column['width']) ? $column['width'] : 0;
    $column['alignment'] = isset($column['alignment']) ? $column['alignment'] : 'left';

    $shift = 0;
    foreach (($column['alignment'] == 'left' ? $columns : array_reverse($columns, true)) as $n => $col) {
        if ($name == $n) break;
        if (@$col['alignment'] != $column['alignment']) {
            $shift += @$col['width'];
        }
    }
    $column['class'] = sprintf('tm-%s uk-width-medium-%s%s', $name, $fraction($column['width']), $shift ? ' uk-'.($column['alignment'] == 'left' ? 'pull' : 'push').'-'.$fraction($shift) : '');
}

/*
 * Add grid classes
 */
$positions = array_keys($config->get('grid', array()));
$displays  = array('small', 'medium', 'large');
$grid_classes = array();
$display_classes = array();
foreach ($positions as $position) {

    $grid_classes[$position] = array();
    $grid_classes[$position][] = "tm-{$position} uk-grid";
    $display_classes[$position][] = '';

    if ($this['config']->get("grid.{$position}.divider", false)) {
        $grid_classes[$position][] = 'uk-grid-divider';
    }

    $widgets = $this['widgets']->load($position);

    foreach($displays as $display) {
        if (!array_filter($widgets, function($widget) use ($config, $display) { return (bool) $config->get("widgets.{$widget->id}.display.{$display}", true); })) {
            $display_classes[$position][] = "uk-hidden-{$display}";
        }
    }

    $display_classes[$position] = implode(" ", $display_classes[$position]);
    $grid_classes[$position] = implode(" ", $grid_classes[$position]);

}

/*
 * Add sections classes
 */
$styles = array();
$classes = array();
foreach (array_keys($config->get('grid', array())) as $name) {
    $block = array();
    if ($this['config']->get("grid.{$name}.section_bg", false)) {
        $block[] = ($this['config']->get("grid.{$name}.section_bg"));
    }
    
    $block[] = $this['config']->get("grid.{$name}.section_padding");
    
    if ($this['config']->get("grid.{$name}.Contrast", false)) {
        $block[] = 'uk-contrast';
    }
    if ($this['config']->get("grid.{$name}.FullHeight", false)) {
        $block[] = 'tm-block-fullheight';
    }
    if ($this['config']->get("grid.{$name}.FullWidth", false)) {
        $block[] = 'tm-block-fullwidth';
    }
    if ($this['config']->get("grid.{$name}.GridCollapse", false)) {
        $block[] = 'tm-grid-collapse';
    }
    if ($this['config']->get("grid.{$name}.SectionClass", false)) {
        $block[] = ($this['config']->get("grid.{$name}.SectionClass"));
    }
    $classes["block.$name"] = $block;
}

/*
 * Add body classes
 */

$body_classes  = $sidebar_classes;
$body_classes .= $this['system']->isBlog() ? ' tm-isblog' : ' tm-noblog';
$body_classes .= ' '.$config->get('page_class');

$config->set('body_classes', trim($body_classes));

/*
 * Add social buttons
 */

$body_config = array();
$body_config['twitter']  = (int) $config->get('twitter', 0);
$body_config['plusone']  = (int) $config->get('plusone', 0);
$body_config['facebook'] = (int) $config->get('facebook', 0);
$body_config['style']    = $config->get('style');

$config->set('body_config', json_encode($body_config));

// internet explorer
if ($this['useragent']->browser() == 'msie') {
    $head[] = sprintf('<!--[if lte IE 8]><script src="%s"></script><![endif]-->', $this['path']->url('js:html5.js'));
}

if (isset($head)) {
	$this['template']->set('head', implode("\n", $head));
}
