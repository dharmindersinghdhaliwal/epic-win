<?php
    global $epic_registration_params;
    extract($epic_registration_params);

    $user_role = isset($user_role) ? $user_role : '';
?>


<?php if('1' == $users_can_register){ ?>


<div id="epic-registration" class="epic-wrap epic-registration <?php echo $sidebar_class; ?> ">
    <div class="epic-inner epic-registration-wrapper epic-clearfix">

        <?php if($display_errors_status){ ?>

            <!-- epic Filters for before registration head section -->
            <?php $register_before_head_params = array('name' => $name,'user_role' => $user_role);
                  echo apply_filters( 'epic_register_before_head', '', $register_before_head_params); ?>
            <!-- End Filters -->

            <!-- epic Filters for customizing head section -->
            <?php 
                $registration_head_params = array('name' => $name,'user_role' => $user_role);
                echo apply_filters( 'epic_registration_head', $display_head , $registration_head_params);
            ?>
            <!-- End Filters -->

        <?php } ?>

        <!-- epic Filters for after registration head section -->
        <?php $register_after_head_params = array('name' => $name, 'user_role' => $user_role);
            echo apply_filters( 'epic_register_after_head', '', $register_after_head_params); ?>
        <!-- End Filters -->

        <div class="epic-main">
            <div class="epic-errors" style="display:none;" id="pass_err_holder">
                <span class="epic-error epic-error-block" id="pass_err_block">
                    <i class="epic-icon epic-icon-remove"></i>
                    <?php echo __('Please enter a username.','epic'); ?>
                </span>
            </div>

            <?php echo  $display_reg_post_errors; ?>
            <?php echo  $register_form; ?>

        </div>

        <!-- epic Filters for after registration fields section -->
        <?php 
            $register_after_fields_params = array('name' => $name,'user_role' => $user_role);
            echo apply_filters( 'epic_register_after_fields', '', $register_after_fields_params); ?>
        <!-- End Filters -->

    </div>
</div>

<?php  } else { ?>

<div id="epic-registration" class="epic-wrap epic-registration <?php echo $sidebar_class; ?> ">
    <div class="epic-inner epic-clearfix">
        <div class="epic-head">
            <?php echo  $registration_blocked_message; ?>
        </div>
    </div>
</div>

<?php  } ?>