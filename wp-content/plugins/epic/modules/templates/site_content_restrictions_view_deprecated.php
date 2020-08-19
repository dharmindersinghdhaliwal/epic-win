<?php
    global $epic_admin;
    $site_content_restriction_status_val = $epic_admin->get_value('site_content_restriction_status');
    $site_content_allowed_pages_val = $epic_admin->get_value('site_content_allowed_pages');
    $site_content_allowed_pages_val = ('0' == $site_content_allowed_pages_val) ? '' : $site_content_allowed_pages_val;
    $site_content_allowed_posts_val = $epic_admin->get_value('site_content_allowed_posts');
    $site_content_allowed_posts_val = ('0' == $site_content_allowed_posts_val) ? '' : $site_content_allowed_posts_val;

    $site_content_allowed_urls_val = $epic_admin->get_value('site_content_allowed_urls');
?>
<tr valign="top">
    <th scope="row"><label for="Enable Restriction Rules"><?php _e('Restriction Rules', 'epic'); ?></label></th>
    <td>
        <?php 
            $site_restrictions_status = array(
                                        '0'  => __('Disable Restriction Rules', 'epic'),
                                        '1'   => __('Enable Restriction Rules', 'epic')
                                    );
            
            echo epic_Html::drop_down(array('name'=>'site_content_restriction_status','id'=>'site_content_restriction_status'), $site_restrictions_status, $site_content_restriction_status_val);
            
        ?><i class="epic-icon epic-icon-question-circle epic-tooltip2 option-help" original-title="<?php _e('Enable/ Disable site restriction rules defined in Content Restriction Rules section.', 'epic') ?>"></i>
    </td>
</tr>

<tr valign="top">
    <th scope="row"><label for="Allowed Pages"><?php _e('Allowed Pages', 'epic'); ?></label></th>
    <td>
        <?php 
            global $epic_admin;
            $site_content_allowed_pages = $epic_admin->get_all_pages();   

                               
            echo epic_Html::drop_down(array('name'=>'site_content_allowed_pages[]','id'=>'site_content_allowed_pages','class'=> 'chosen-admin_setting','multiple'=>'','data-placeholder'=>'Please Select'), $site_content_allowed_pages, $site_content_allowed_pages_val);
        ?><i class="epic-icon epic-icon-question-circle epic-tooltip2 option-help" original-title="<?php _e('These pages will be acessible to any user regardless of the restriction rule settings.', 'epic') ?>"></i>
    </td>
</tr>

<tr valign="top">
    <th scope="row"><label for="Allowed Posts"><?php _e('Allowed Posts', 'epic'); ?></label></th>
    <td>
        <?php 
            global $epic_admin;
            $site_content_allowed_posts = $epic_admin->get_all_posts();                      
            echo epic_Html::drop_down(array('name'=>'site_content_allowed_posts[]','id'=>'site_content_allowed_posts','class'=> 'chosen-admin_setting','multiple'=>'','data-placeholder'=>'Please Select'), $site_content_allowed_posts, $site_content_allowed_posts_val);
        ?><i class="epic-icon epic-icon-question-circle epic-tooltip2 option-help" original-title="<?php _e('These posts will be acessible to any user regardless of the restriction rule settings.', 'epic') ?>"></i>
    </td>
</tr>

<tr valign="top">
    <th scope="row"><label for="Allowed URL's"><?php _e('Allowed URLs', 'epic'); ?></label></th>
    <td>
        <?php 
            echo epic_Html::text_area(array('name' => 'site_content_allowed_urls', 'id' => 'site_content_allowed_urls', 'class' => 'large-text code text-area', 'value' => $site_content_allowed_urls_val, 'rows' => '3'));
            
        ?><i class="epic-icon epic-icon-question-circle epic-tooltip2 option-help" original-title="<?php _e('These posts will be acessible to any user regardless of the restriction rule settings.', 'epic') ?>"></i>
    </td>
</tr>




