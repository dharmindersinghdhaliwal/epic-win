<?php
/*
 * 
 * @encoding     UTF-8
 * @author       Aleksandr Glovatskyy (aleksandr1278@gmail.com)
 * @copyright    Copyright (C) 2016 torbara (http://torbara.com/). All rights reserved.
 * @license      Copyrighted Commercial Software
 * @support      support@torbara.com
 * 
 */
 
return array(

    'path' => array(
        'theme'   => array(get_template_directory()),
        'js'      => array(get_template_directory().'/js'),
        'css'     => array(get_template_directory().'/css'),
        'less'    => array(get_template_directory().'/less'),
        'layouts' => array(get_template_directory().'/layouts')
    ),

    'less' => array(

        'vars' => array(
            'less:theme.less'
        ),

        'files' => array(
            '/css/theme.css' => 'less:theme.less',
            '/css/woocommerce.css' => 'less:woocommerce.less'
        )

    ),

    'cookie' => $cookie = md5(get_template_directory()),

    'customizer' => isset($_COOKIE[$cookie])

);
