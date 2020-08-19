<?php
    global $epic_login_forgot_params;
    extract($epic_login_forgot_params);
?>
<div class="epic-forgot-pass" id="epic-forgot-pass-holder-<?php echo $login_code_count; ?>">
    <div class="epic-field epic-edit epic-edit-show">
        <label class="epic-field-type" for="user_name_email-<?php echo $login_code_count; ?>">
            <i class="epic-icon epic-icon-user"></i>
            <span><?php echo __('Username or Email', 'epic'); ?></span>
        </label>
        <div class="epic-field-value">
            <input type="text" class="epic-input" name="user_name_email" id="user_name_email-<?php echo $login_code_count; ?>" value=""></div>
    </div>

    <div class="epic-field epic-edit epic-edit-show">
        <label class="epic-field-type epic-blank-lable">&nbsp;</label>
        <div class="epic-field-value">
            <div class="epic-back-to-login">
            <a href="javascript:void(0);" title="<?php echo __('Back to Login', 'epic'); ?>" id="epic-back-to-login-<?php echo $login_code_count; ?>"><?php echo __('Back to Login', 'epic'); ?></a> <?php echo $register_link_forgot; ?>
            </div>

        <input type="button" name="epic-forgot-pass" id="epic-forgot-pass-btn-<?php echo $login_code_count; ?>" class="epic-button epic-login" value="<?php echo __('Forgot Password', 'epic'); ?>">
        </div>
    </div>
</div>