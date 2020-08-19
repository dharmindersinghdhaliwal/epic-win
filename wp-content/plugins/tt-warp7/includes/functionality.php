<?php
/*
 * @package    TT Warp 7
 * @encoding   UTF-8
 * @version    7.3.29
 * @author     Alexander Khmelnitskiy (info@alexander.khmelnitskiy.ua)
 * @copyright  Copyright (C) 2016 torbarа (http://torbara.com/). All rights reserved.
 * @license    Copyrighted Commercial Software
 * @support    support@torbara.com
 */

// Shortcodes in default text widget
add_filter('widget_text', 'do_shortcode');
