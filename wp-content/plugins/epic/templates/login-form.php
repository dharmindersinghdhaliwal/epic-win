<?php
    global $epic_login_params;
    extract($epic_login_params);
?>
<form action="" method="post" id="epic-login-form-<?php echo $login_code_count; ?>">    
    <!-- Display login fields inside the form -->
    <?php echo $login_fields; ?>
    <!-- Display captcha verification fields after the login fields -->
    <?php echo $captcha_fields; ?>
    <div class="epic-field epic-edit epic-edit-show">
        <label class="epic-field-type epic-field-type-<?php echo $sidebar_class; ?>">&nbsp;</label>
        <div class="epic-field-value">
            <!-- epic Filters for adding extra fields or hidden data in forgot form -->
            <?php echo apply_filters('epic_forgot_form_extra_fields',''); ?>
            <!-- End Filter -->
            <input type="hidden" name="epic-hidden-login-form-name" value="<?php echo $login_form_name; ?>" />
            <input type="hidden" name="epic-hidden-login-form-name-hash" value="<?php echo $hash; ?>" />
            <div class="epic-rememberme <?php echo $remember_me_class; ?>">
                <i class="<?php echo $class; ?>"></i> <?php echo  __('Remember me', 'epic'); ?>
                <input type="hidden" name="rememberme" id="rememberme-<?php echo $login_code_count; ?>" value="0"/>
            </div>
            <input type="submit" name="epic-login" class="epic-button epic-login <?php echo $login_btn_class; ?>" value="<?php echo  __('Log In', 'epic'); ?>" /><br />
            <?php echo $login_form_link; ?>
        </div>
    </div>
    <div class="epic-clear"></div>
    <?php 
	### Custom Redriect
	$custom_url	=	get_site_url().'/login';
	?>
    <input type="hidden" name="redirect_to" value="<?php echo $custom_url; ?>" />
    
    <?php /*?><input type="hidden" name="redirect_to" value="<?php echo $redirect_to; ?>" /><?php */?>
    <!-- epic Filters for social login buttons section -->
    <?php echo apply_filters( 'epic_social_logins', ''); ?>
    <!-- End Filters -->
</form>       

        
<!-- epic Filters for adding extra fields or hidden data in login form -->
<?php echo  apply_filters('epic_login_form_extra_fields',''); ?>
<!-- End Filter -->

<!-- Generating Forgot Password Form-->
<?php echo $forgot_pass_html; ?>