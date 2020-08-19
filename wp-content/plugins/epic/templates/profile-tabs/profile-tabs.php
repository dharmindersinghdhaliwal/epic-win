<?php
    global $epic_profile_tabs_params;
    $params = $epic_profile_tabs_params;
    extract($params);

    if('enabled' == $initial_display) {
        $tabs_status = "display:block;";
    }else{
        $tabs_status = "display:none;";
    }
?>

<div id="epic-profile-tabs-panel">
    <div id="epic-profile-tabs" style="<?php echo $tabs_status; ?>">
        <div id="epic-user-profile-tab-panel" class="epic-profile-tab" data-tab-id="epic-profile-panel" >
            <?php echo apply_filters('epic_profile_tab_items_profile','<i class="epic-profile-icon epic-icon-user"></i>',$params); ?>
        </div>
        
        <?php echo apply_filters('epic_profile_tab_items','',$params); ?>

    </div>
    <div class="epic-clear"></div>
            
    <div id="epic-profile-tab-open" class="epic-profile-tab-button">
        <?php if('enabled' == $initial_display) { ?>
            <i class="epic-profile-icon epic-icon-arrow-circle-up "></i>
        <?php } else { ?>
            <i class="epic-profile-icon epic-icon-arrow-circle-down "></i>
        <?php }  ?>
        
    </div>
    <div class="epic-clear"></div>
            
</div>