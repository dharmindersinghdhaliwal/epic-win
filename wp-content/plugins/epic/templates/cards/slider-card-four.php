<?php 
    global $epic_template_args;     
    extract($epic_template_args);
?>

<div class="epic-wrap">
    
    
    
    <div class="epic-slider-design-four  epic-team-design-four epic-team-design" style="background:<?php echo $background_color; ?>;color:<?php echo $font_color; ?>" >
        
        <div class="flexslider flexslider-four">
            <ul class="slides">            
        
                <?php 
                    $x = 0;
                    foreach($users as $key => $user){ 
                        extract($user);
                        $x++;
                ?>
                
                
                  
                    <li class="epic-single-profile-li" >
          
                        
                        <div class="epic-single-profile">
                            <div class="epic-profile-pic <?php echo $pic_style; ?> "  >
                            <?php echo $profile_pic_display; ?>
                            </div>

                            <div class="epic-clear"></div>
                        </div>
                    
         
                    </li>
        
                
                <?php } ?>
                
                
            </ul>
        </div>

    </div>
</div>