<?php
add_shortcode('display_spotlight_challenge','display_spotlight_challenge');

#### Spotlight Challenge Display home
function display_spotlight_challenge($atts =''){

  $a = shortcode_atts( array(
		'per_page'  => 6
	), $atts );

	$args = array(
  	'post_type' 			=> 'achievement',
  	'post_status' 		=> 'publish',
  	'posts_per_page' 	=> $a[ 'per_page' ]
	);

	$posts = get_posts($args);  ?>
  <div class="uk-grid tm-page-workouts" data-uk-grid-match="" data-uk-grid-margin="">
  <?php	if($posts){
		foreach($posts as $post){

			$pid	      =	$post->ID;
			$post_meta	=	get_post_meta($pid,'_dpa_points',true);
			$uid	      =	get_current_user_id();	?>
      <div class="uk-width-medium-1-3">
      	<article id="item-<?php echo $pid; ?>" class="uk-article rltv lesson type-lesson status-publish hentry post" data-permalink="<?php echo get_permalink($pid); ?>">
    <?php  if(dpa_has_user_unlocked_achievement($uid,$pid)){ ?>
            <style>	.unlck {left: 0;position: absolute;}</style>
            <img class="unlck" src="<?php echo get_site_url(); ?>/wp-content/themes/maxx-fitness-child-theme/unlock.png" alt="<?php echo  $post->post_title;?>"><?php
          }?>
          <a class="uk-align-left" href="<?php echo get_permalink($pid); ?>">
            <?php echo get_the_post_thumbnail($pid);?>
          </a>
          <div>
            <div class="uk-panel uk-width-1-1">
              <div class="uk-grid">
                <div class="uk-width-1-3">
                  <h6 class="tm-uppercase">Name</h6>
                  <span class="tm-uppercase"><?php echo  $post->post_title;?></span>
                </div>
                <div class="uk-width-1-3">
                  <h6 class="tm-uppercase">Category</h6>
                  <span class="tm-uppercase">Spotlight Challenge</span>
                </div>
                <div class="uk-width-1-3">
                  <h6 class="tm-uppercase">Points</h6>
                  <span class="tm-uppercase"><?php echo $post_meta ;?></span>
                </div>
              </div>
            </div>
          </div>
        </article>
      </div><?php
		}
	}
?></div><?php
}
