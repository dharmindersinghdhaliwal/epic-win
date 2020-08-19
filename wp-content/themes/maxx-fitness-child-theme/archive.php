<?php
/*
	Template Name: Epic Achievement
*/
	# By Default Template For post type
/**
 * Maxx Fitness Workouts category template for WP, exclusively on Envato Market: http://codecanyon.net/user/aklare
 * @encoding     UTF-8
 * @version      6.0.0
 * @copyright    Copyright (C) 2015 Aklare (http://aklare.com). All rights reserved.
 * @license      GNU General Public License version 2 or later, see http://www.gnu.org/licenses/gpl-2.0.html
 * @author       Alexandr Khmelnytsky (support@aklare.com)
 */
get_header(); ?>
 <?php
	  $post_id		=	get_the_ID();
	  $post_meta	=	get_post_meta($post_id,'_dpa_points',true);
	  if($post_meta) { ?>
<style>

@media screen and (max-width:740px){
	.uk-width-small-2-5-1{
		width:50% !important;
		padding: 5px 0px !important;
	}
	.uk-width-small-2-5-1 span.tm-uppercase{
		font-size:8px !important;
		line-height:1.5 !important;
		    text-align: left;
	}
	.uk-width-small-2-5-1 h6.tm-uppercase{
		font-size:10px !important;
		    text-align: left;
	}
	.term-feats-of-strength .tm-page-workouts .hit-list, .term-feats-of-strength .tm-page-workouts .remove {
		font-size: 10px;
    	padding: 0 12px;
	}
	.term-feats-of-strength .tm-page-workouts .remove i {
		padding : 5px;
	}
}
</style>
<div class="uk-grid tm-page-workouts" data-uk-grid-match="" data-uk-grid-margin="">
    <div class="uk-width-1-1">
        <div class="uk-panel uk-panel-header">
            <h1 class="tm-title"><?php single_cat_title(); ?></h1>
        </div>
    </div> <?php
    while (have_posts()){
		the_post();?>
        <div class="uk-width-medium-1-3 ac-col uk-width-5-10"  pid="<?php echo get_the_ID()?> ">
            <article id="item-<?php echo get_the_ID();?>" <?php post_class('uk-article rltv'); ?> data-permalink="<?php the_permalink() ?>"><?php
				$id	=	$post->ID;
				$uid	=	get_current_user_id();
			if(dpa_has_user_unlocked_achievement($uid,$id)){
				?><img class="unlck" src="<?php echo get_stylesheet_directory_uri();?>/unlock.png" alt="unlock"/>
		<?php }
			if(has_post_thumbnail()==true){
				?><a class="uk-align-left " href="<?php the_permalink() ?>" title="">
                    <?php the_post_thumbnail(array(400,340));?>
                </a>
            <?php }
			else{?>
				<a class="uk-align-left" href="<?php the_permalink() ?>" title="">
          <img src="<?php echo get_stylesheet_directory_uri();?>/no-image.jpg">
        </a>
      <?php }	?>
   <div>
       <div class="uk-panel uk-width-1-1 "><?php
			$challenge_ids	=json_decode(get_user_meta($uid,'challenge_hit_list',true));
			if(!in_array(intval($id),$challenge_ids)){
				if(!dpa_has_user_unlocked_achievement($uid,$id)){?>
					<a href="javascript:void(0)" class="hit-list" id="<?php echo $id;?>"><i class="fa fa-plus" aria-hidden="true" ></i> HIT LIST</a><?php
				}
			}
			else{
				if(!dpa_has_user_unlocked_achievement($uid,$id)){?>
					<a href="javascript:void(0)" class="remove" id="<?php echo $id; ?>"><i class="fa fa-trash fa-6" aria-hidden="true"></i>Remove From list</a><?php
				}
			}?>
			<div class="uk-grid">
				<div class="uk-width-1-3 uk-width-small-2-5-1">
					<h6 class="tm-uppercase">Name</h6>
					<span class="tm-uppercase"> <?php the_title();?></span>
				</div>
				<div class="uk-width-1-3 uk-width-small-2-5-1">
					<h6 class="tm-uppercase">Category</h6>
					<span class="tm-uppercase"><?php single_cat_title(); ?></span>
				</div>
				<div class="uk-width-1-3 uk-width-small-2-5-1">
					<h6 class="tm-uppercase">Points</h6>
					<?php
					$p_id		=	get_the_ID();
					$p_meta		=	get_post_meta($p_id,'_dpa_points',true);	 ?>
					<span class="tm-uppercase"><?php echo $p_meta ;?></span>
				</div>
			</div>
		</div>
              <a href="<?php the_permalink() ?>" title="">Read more ...</a> </div>
            </article>
        </div>
<?php } ?>
</div>
<?php }
else {	?>
    <div class="uk-grid tm-page-workouts" data-uk-grid-match="" data-uk-grid-margin="">
    <div class="uk-width-1-1">
        <div class="uk-panel uk-panel-header">
            <h1 class="tm-title"><?php single_cat_title(); ?></h1>
        </div>
    </div>

    <div class="category-module-trainers nutMargin">
    	<div class="uk-grid">
    <?php
    while (have_posts()){
		the_post();?>
        <div class="uk-width-medium-1-2 uk-width-large-1-3 uk-text-left uk-scrollspy-init-inview uk-scrollspy-inview uk-animation-scale-up uk-row-first">
			<div class="uk-width-1-1">
				<div class="uk-panel" style="min-height: 295px;">
					<div class="uk-cover-background uk-position-relative">
            <article id="item-<?php echo get_the_ID(); ?>" <?php post_class('uk-article rltv'); ?> data-permalink="<?php the_permalink() ?>">
            <?php
			if(has_post_thumbnail()==true){
				?><a class="uk-align-left" href="<?php the_permalink() ?>" title="">
                    <?php the_post_thumbnail(array(400,340));?>
                </a>
                <?php }?>
              <div class="uk-position-cover uk-flex uk-flex-center uk-flex-bottom">
                  <div class="ak-overlay uk-width-1-1">
                    <h4 class="uk-panel-title uk-margin-remove"><?php the_title();?></h4>
                  </div>
           		</div>
			</article>
			</div>
			</div>
		</div>
        </div>
<?php } ?>
</div>
</div>
</div>
    <?php } ?>
<?php get_sidebar(); ?>
<?php get_template_part('_pagination'); ?>
<?php get_footer();?>
<style>
.titl { margin-bottom: 0;}
.uk-panel.uk-width-1-1 { min-height: 100px;}
.unlck{left: 0;position: absolute;z-index: 2;}
</style>
