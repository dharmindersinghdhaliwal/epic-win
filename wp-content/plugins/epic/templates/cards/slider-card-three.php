<?php 
    global $epic_template_args;     
    extract($epic_template_args);
?>

<div class="epic-wrap">
    
    
    
    <div class="epic-slider-design-three  epic-team-design-three epic-team-design" style="background:<?php echo $background_color; ?>;color:<?php echo $font_color; ?>">
        
        <div class="flexslider flexslider-three">
            <ul class="slides">            
        
                <?php 
                    $x = 0;
                    foreach($users as $key => $user){ 
                        extract($user);
                        $x++;
                ?>
                
                
                    <?php if($x%3 == 1){ ?>
                    <li class="epic-single-profile-li" >
                    <?php } ?>
                        
                        <div class="epic-single-profile">
                            <div class="epic-profile-pic <?php echo $pic_style; ?> "  >
                            <?php echo $profile_pic_display; ?>
                            </div>
                            <div class="epic-author-name">
                                <a epic-data-user-id="<?php echo  $id; ?>" href="<?php echo $profile_url; ?>"  ><?php echo  $profile_title_display; ?></a>
                            </div>

                            <div class="epic-social-boxes">
                            <?php echo $social_buttons; ?>
                            </div>

                            <div class="epic-clear"></div>
                        </div>
                    
                    <?php if($x%3 == 0){ ?>
                    </li>
                    <?php } ?>
                
                <?php } ?>
                
                
            </ul>
        </div>

    </div>
</div>