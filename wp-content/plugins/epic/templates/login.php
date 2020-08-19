<?php
    global $epic_login_params;
    extract($epic_login_params);
?>

<div class="epic-wrap epic-login  <?php echo $sidebar_class; ?>">
    <div class="epic-inner epic-clearfix epic-login-wrapper">

        <!-- epic Filters for before login head section -->
        <?php echo apply_filters( 'epic_login_before_head', ''); ?>
        <!-- End Filters -->

        <!-- epic Filters for customizing head section -->
        <?php 
            $login_head_params = array();
            echo apply_filters( 'epic_login_head', $login_header , $login_head_params);
        ?>
        <!-- End Filters -->
        
    
        <!-- epic Filters for after login head section -->
        <?php echo apply_filters( 'epic_login_after_head', ''); ?>
        <!-- End Filters -->


        <div class="epic-main">
            <?php if(isset($common_msg) && $common_msg != ''){ ?>
                <div id="epic-login-view-msg-holder" class="<?php echo $common_msg_status;?>"  >
                <?php echo $common_msg; ?></div>
            <?php } ?>
            
            <?php echo $errors; ?>

            <?php if (!empty($act_status_message['msg'])) {    ?>
                <div class="epic-<?php echo $act_status_message['status']; ?>">
                    <span class="epic-error epic-error-block">
                        <?php if($act_status_message['status'] == 'success'){ ?>
                            <i class="epic-icon epic-icon-check"></i>                
                        <?php }else{ ?>
                            <i class="epic-icon epic-icon-remove"></i>
                        <?php } ?>
                        
                        <?php echo $act_status_message['msg']; ?>
                    </span>
                </div>
            <?php } ?>


            <?php echo $login_form_template; ?>

        </div>

        <!-- epic Filters for after login fields section -->
        <?php echo apply_filters( 'epic_login_after_fields', ''); ?>
        <!-- End Filters -->

    </div>
</div>