<?php 
    global $epic_template_args;     
    extract($epic_template_args);
?>

<div class="epic-wrap">
    <div class="epic-team-design-three epic-team-design" style="background:<?php echo $background_color; ?>;color:<?php echo $font_color; ?>">
        <?php if($team_name != ''){ ?>
            <div class="epic-team-name"><?php echo $team_name; ?></div>
        <?php } ?>
        
        <?php if($team_description != ''){ ?>
            <div class="epic-team-desc"><?php echo $team_description; ?></div>
        <?php } ?>
        
        <?php 
            foreach($users as $key => $user){ 
                extract($user);
        ?>
        
        <div class="epic-single-profile">
            <div class="epic-profile-pic <?php echo $pic_style; ?> "  >
            <?php echo $profile_pic_display; ?>
            </div>
            <div class="epic-author-name">
                <a style="color:<?php echo $font_color; ?>" epic-data-user-id="<?php echo  $id; ?>" href="<?php echo $profile_url; ?>"  ><?php echo  $profile_title_display; ?></a>
            </div>

            <div class="epic-social-boxes">
            <?php echo $social_buttons; ?>
            </div>

            <div class="epic-clear"></div>
        </div>
        <?php } ?>
        <div class="epic-clear"></div>
    </div>
</div>