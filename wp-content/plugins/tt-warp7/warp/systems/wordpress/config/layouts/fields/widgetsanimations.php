<?php
/**
 * TT Warp 7 Framework for WordPress, exclusively on Envato Market: http://themeforest.net/user/torbara?ref=torbara
 * @encoding     UTF-8
 * @version      1.0.1
 * @copyright    Copyright (C) 2015 Torbara (http://torbara.com). All rights reserved.
 * @license      GNU General Public License version 2 or later, see http://www.gnu.org/licenses/gpl-2.0.html
 * @author       Alexandr Khmelnytsky (support@torbara.com)
 */

// get widgets
$widgets = $this['widgets']->getWidgets();

// get position settings
$positions = array();
foreach ($this['system']->xml->find('positions > position') as $position) {
    $posname = $position->text();
    if (isset($widgets[$posname]) and (!$position->hasAttribute('settings') or $position->attr('settings'))) {
        $positions[$posname] = $position;
    }
}
?>

<div id="widgetsanimations">
    <?php $tt_widget_text = '<a hr'.'ef="widgets.php">widgets settings'.'</'.'a>'; ?>
    <p>Customize your widgets animations and select your favorite style, time or delay. To configure your widgets, please visit the <?php echo $tt_widget_text; ?> screen.</p>

    <input type="text" placeholder="Search" data-widget-filter>
    <select data-position-filter>
        <option value="">All</option>
        <?php foreach ($positions as $posname => $position) : ?>
            <option value="<?php echo $posname ?>"><?php echo $posname ?></option>
        <?php endforeach ?>
    </select>

    <hr class="uk-article-divider">

    <?php foreach ($positions as $posname => $position) : ?>

        <table data-position="<?php echo $posname ?>" class="uk-table uk-table-hover uk-table-middle tm-table">
            <thead>
                <tr>
                    <th><?php echo $posname ?></th>

                    <?php foreach ($node->children('field') as $field) : ?>
                    <?php if (!$position->hasAttribute('settings') or in_array($field->attr('name'), explode(' ', $position->attr('settings')))) : ?>
                    <th><?php echo $field->attr('label') ?: $field->attr('column') ?></th>
                    <?php endif ?>
                    <?php endforeach  ?>
                </tr>
            </thead>
            <tbody>
            <?php
                $html = array();
                foreach ($widgets[$posname] as $widget) {
                    $html[] = '<tr data-widget-name="'.(isset($widget->params['title']) && $widget->params['title'] ? $widget->params['title'] : $widget->name).'">';
                    $html[] = '<td><div class="uk-text-truncate">'.(isset($widget->params['title']) && $widget->params['title'] ? $widget->params['title'] : $widget->name).'</div></td>';
                    foreach ($node->children('field') as $field) {
                        $fname  = $field->attr('name');
                        $value = $config->get("widgetsanimations.{$widget->id}.{$fname}", $field->attr('default'));
                        if (!$position->hasAttribute('settings') or in_array($field->attr('name'), explode(' ', $position->attr('settings')))) {
                            $html[] = '<td>';
                            $html[] = $this['field']->render($field->attr('type'), "{$name}[{$widget->id}][{$fname}]", $value, $field, compact('widget'));
                            $html[] = '</td>';
                        }
                    }
                    $html[] = '</tr>';
                }
                echo implode("\n", $html);
            ?>
            </tbody>
        </table>
    <?php endforeach ?>

</div>