<?php
    global $epic_admin;
    // $site_content_restriction_status_val = $epic_admin->get_value('site_lockdown_status');
    // $site_content_allowed_pages_val = $epic_admin->get_value('site_content_allowed_pages');
    // $site_content_allowed_pages_val = ('0' == $site_content_allowed_pages_val) ? '' : $site_content_allowed_pages_val;
    // $site_content_allowed_posts_val = $epic_admin->get_value('site_content_allowed_posts');
    // $site_content_allowed_posts_val = ('0' == $site_content_allowed_posts_val) ? '' : $site_content_allowed_posts_val;

    // $site_content_allowed_urls_val = $epic_admin->get_value('site_content_allowed_urls');


    $epic_admin->add_plugin_module_setting(
            'checkbox',
            'site_lockdown_status',
            'site_lockdown_status',
            __('Lock Entire Site', 'epic'),
            '1',
            __('If checked, access to your entire site will be restricted for guests and non-logged in users.You can create exceptions below by allowing posts, pages or URL\'s.', 'epic'),
            __('Checking this option will restrict access to your entire site for guests and non-logged in users .', 'epic')
    );

    $epic_admin->add_plugin_module_setting(
            'select',
            'site_lockdown_allowed_pages[]',
            'site_lockdown_allowed_pages',
            __('Allowed Pages', 'epic'),
            $site_content_allowed_pages = $epic_admin->get_all_pages(),
            __('These pages will be acessible to any user without any restrictions.', 'epic'),
            __('Define public pages .', 'epic'),
            array('class'=> 'chosen-admin_setting','multiple'=>'','init_value' => '')
    );

    $epic_admin->add_plugin_module_setting(
            'select',
            'site_lockdown_allowed_posts[]',
            'site_lockdown_allowed_posts',
            __('Allowed Posts', 'epic'),
            $site_content_allowed_posts = $epic_admin->get_all_posts(),
            __('These posts will be acessible to any user without any restrictions.', 'epic'),
            __('Define public posts .', 'epic'),
            array('class'=> 'chosen-admin_setting','multiple'=>'','init_value' => '')
    );

    $epic_admin->add_plugin_module_setting(
            'textarea',
            'site_lockdown_allowed_urls',
            'site_lockdown_allowed_urls',
            __('Allowed URL\'s', 'epic'),
            '',
            __('These URL\'s will be acessible to any user without any restrictions.', 'epic'),
            __('Define public URL\'s' , 'epic')
    );


    $site_lockdown_redirect_pages = $epic_admin->get_all_pages();
    if(!$epic_admin->options['redirect_backend_login']){
        $site_lockdown_redirect_pages['wp-login'] = 'wp-login.php';
    }
    
    $epic_admin->add_plugin_module_setting(
            'select',
            'site_lockdown_redirect_url',
            'site_lockdown_redirect_url',
            __('Redirect Page', 'epic'),
            $site_lockdown_redirect_pages,
            __('Guests will be redirected here when they try to access your locked site.The default is epic Login page which may be
                set in epic Settings -> System Pages. If you choose another page, be sure it includes <code>[epic_login]</code> shortcode.', 'epic'),
            __('Define redirection URL for violating site lockdown restrictions .', 'epic'),
            array('class'=> 'chosen-admin_setting')
    );

    $epic_admin->add_plugin_module_setting(
            'select',
            'site_lockdown_rss_feed',
            'site_lockdown_rss_feed',
            __('RSS Feed', 'epic'),
            array('0'=> __('RSS Enabled','epic'),'1' => __('RSS Disabled','epic'),'2' => __('Limited to Headlines/ Titles','epic')),
            __('RSS feed viewing does not require the user to login.Your RSS feed is publicly acessible if enabled.You may disable or limit the RSS feed above to prevent access to locked content through your RSS feed.', 'epic'),
            __('RSS feed viewing does not require the user to login.Your RSS feed is publicly acessible if enabled.You may disable or limit the RSS feed above to prevent access to locked content through your RSS feed.', 'epic'),
            array('class'=> 'chosen-admin_setting')
    );
?>
