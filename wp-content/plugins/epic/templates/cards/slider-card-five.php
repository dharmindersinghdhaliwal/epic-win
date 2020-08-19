<?php 
    global $epic_template_args;     
    extract($epic_template_args);
?>

<div class="epic-wrap">
    
    
    
    <div class="epic-slider-design-five  " style="background:<?php echo $background_color; ?>;color:<?php echo $font_color; ?>" >
        
        <div class="flexslider flexslider-five">
            <ul class="slides">            
        
                <?php 
                    $x = 0;
                    foreach($users as $key => $user){ 
                        extract($user);
                        $x++;
                ?>
                
                
                  
                    <li class="epic-single-profile-li" >
          
                        
                        <div class="epic-author-design-one epic-author-design" style="background:<?php echo $background_color; ?>;color:<?php echo $font_color; ?>">

                              <div class="epic-left">
                                <div class="epic-profile-pic <?php echo $pic_style; ?> ">
                                  <?php echo $profile_pic_display; ?>

                                </div>
                                <div class="epic-author-name">
                                  <div class="epic-field-name">
                                    <a epic-data-user-id="<?php echo $id; ?>" href="<?php echo $profile_url; ?>" style="color:<?php echo $font_color; ?>" ><?php echo $profile_title_display; ?></a>
                                  </div>

                                  <?php echo $social_buttons; ?>

                                  <div class="epic-field-desc">
                                  <?php echo $description; ?>
                                  </div>

                                  <div class="epic-clear"></div>
                                </div>
                              </div>

                              <div class="epic-clear"></div>

                            </div>
                    
         
                    </li>
        
                
                <?php } ?>
                
                
            </ul>
        </div>

    </div>
</div>