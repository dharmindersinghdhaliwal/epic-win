<?php
    global $epic_registration_params;
    extract($epic_registration_params);
?>

<div class="epic-head">
                 
    <div class="epic-left">
        <div class=" <?php echo $pic_class; ?> ">
            <?php echo $user_pic; ?>
        </div>

        <div class="epic-name">
            <div class="epic-field-name epic-field-name-wide">
                <?php echo $display_head_title; ?>
            </div>
        </div>
    </div>

    <div class="epic-right"></div>
    <div class="epic-clear"></div>                 
</div>