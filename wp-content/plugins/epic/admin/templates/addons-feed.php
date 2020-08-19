<?php   global $epic_addon_template_data;
        extract($epic_addon_template_data); 
?>
<div id="epic-addons-feed">
    
    <?php 

        foreach($addons as $addon){ 
            $addon = (array) $addon;
            extract($addon);           
            
            if(in_array($name,$active_plugins)){
                $status = __('Activated','epic');
                $status_class = 'activated';
            }else{
                $status = __('Deactivated','epic');
                $status_class = 'deactivated'; 
            }
    ?>
            <div class="epic-addon-single">
                <div class="epic-addon-single-title"><?php echo $title; ?></div>
                <div class="epic-addon-single-image">
                    <img src="<?php echo $image; ?>" />
                </div>
                <div class="epic-addon-single-desc"><?php echo $desc; ?></div>
                <div class="epic-addon-single-buttons">
                    <div class="epic-addon-single-status <?php echo $status_class; ?> "><?php echo $status; ?></div>
                    <div class="epic-addon-single-type"><?php echo $type; ?></div>
                    <div class="epic-addon-single-get"><a href="<?php echo $download; ?>"><?php echo __('Download','epic'); ?></a></div>
                    <div class="epic-clear"></div>
                </div>
                
            </div>
    <?php } ?>
    
    
</div>