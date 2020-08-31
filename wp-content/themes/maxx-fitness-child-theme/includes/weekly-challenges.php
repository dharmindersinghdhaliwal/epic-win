<?php
add_shortcode('display_week_challenge','display_week_challenge');

#### Dynamic Week Challenge Begins Here
function display_week_challenge( $atts = ''){

  $a = shortcode_atts( array(
		'week'      => '',
		'per_page'  => 12,
    'hit_list'  => 'no'
	), $atts );

  //	Get Challenge Number
    $challenge_number = $a[ 'week' ];
    $per_page         =	$a[ 'per_page' ];

		switch( $challenge_number ){
			case 1:
				$challenge_category_id  =	620;
				break;
			case 2:
				$challenge_category_id  =	621;
				break;
			case 3:
				$challenge_category_id  =	622;
				break;
			case 4:
				$challenge_category_id  =	623;
				break;
			case 5:
				$challenge_category_id  =	624;
				break;
			case 6:
				$challenge_category_id  =	625;
				break;
			case 7:
				$challenge_category_id  =	626;
				break;
			case 8:
				$challenge_category_id  =	627;
				break;
			case 9:
				$challenge_category_id  =	628;
				break;
			case 10:
				$challenge_category_id  =	629;
				break;
			case 11:
				$challenge_category_id  =	630;
				break;
			case 12:
				$challenge_category_id  =	631;
				break;
      case 13:
        $challenge_category_id  =	894;
        break;
      case 14:
    		$challenge_category_id  =	895;
    		break;
      case 15:
        $challenge_category_id  =	896;
        break;
      case 16:
    		$challenge_category_id  =	897;
    		break;
      case 17:
        $challenge_category_id  =	898;
        break;
      case 18:
    		$challenge_category_id  =	899;
    		break;
      case 19:
        $challenge_category_id  =	900;
        break;
      case 20:
    		$challenge_category_id  =	901;
    		break;
      case 21:
        $challenge_category_id  =	902;
        break;
      case 22:
    		$challenge_category_id  =	903;
    		break;
      case 23:
      		$challenge_category_id  =	904;
      	break;
      case 24:
      		$challenge_category_id  =	905;
      	break;
		  default:
				 $challenge_category_id  =	'';
		}

		$args = array(
  		'post_type' 		=> 'achievement',
  		'post_status' 	=> 'publish',
      'numberposts'		=>	-1
		);

     if(!empty($challenge_category_id)){
      $args['category'] = $challenge_category_id;
    }

  	$posts = get_posts($args);
    $output = '';
	  $output.= '<div class="uk-grid tm-page-workouts" data-uk-grid-match="" data-uk-grid-margin="">';
    if($posts){
      $uid	         =	get_current_user_id();
      $challenge_ids =  json_decode(get_user_meta($uid,'challenge_hit_list',true));

      global $paged;
      $paged      = ($paged == 0) ? 1 : $paged;
      $total		  =	count($posts);
      $totalPages =	ceil( $total/ $per_page );
      $mpage 	    =	max($paged, 1);
      $page 		  =	min($mpage, $totalPages);
      $offset 	  =	($page - 1) * $per_page;
      if( $offset < 0 ) $offset = 0;

      $myposts 	= array_slice($posts,$offset,$per_page );
			foreach($myposts as $post){

				$pid	      =	$post->ID;
				$post_meta	=	get_post_meta($pid,'_dpa_points',true);

        $output.= '<div class="uk-width-medium-1-3 uk-width-5-10 achievement mobile-two-cols">';
        	$output.= '<article id="item-'.$pid.'" class="uk-article rltv post-'.$pid.' lesson type-lesson status-publish hentry post" data-permalink="'. get_permalink($pid).'">';
            if(dpa_has_user_unlocked_achievement($uid,$pid)){
              $output.= '<style>	.unlck {left: 0;position: absolute;}</style>
              <img class="unlck" src="'.get_site_url().'/wp-content/themes/maxx-fitness-child-theme/unlock.png" alt="'.$post->post_title.'">';
            }
            $output.= '<a class="uk-align-left" href="'.get_permalink($pid).'" >'.get_the_post_thumbnail($pid).'</a>';
            $output.= '<div>
            <div class="uk-panel uk-width-1-1">';

              /***Check if Hit List button is enabled from shortcode**/
              if($a[ 'hit_list' ] == 'yes'){

          			if(empty($challenge_ids) || !in_array(intval($pid),$challenge_ids)){
          				if(!dpa_has_user_unlocked_achievement($uid,$pid)){

          					$output.= '<a href="javascript:void(0)" class="hit-list" id="'.$pid.'">
                      <i class="fa fa-plus" aria-hidden="true" ></i>HIT LIST </a>';
          				}
          			}
          			else{
          				if(!dpa_has_user_unlocked_achievement($uid,$pid)){

          					$output.= '<a href="javascript:void(0)" class="remove" id="'.$pid.'">
                      <i class="fa fa-trash fa-6" aria-hidden="true"></i>Remove From list</a>';
          				}
          			}

              }
              $output.= '<div class="uk-grid">';
                $output.= '<div class="uk-width-1-3 uk-width-small-2-5-1">';
                  $output.= '<h6 class="tm-uppercase">Name</h6>';
                  $output.= '<span class="tm-uppercase">'.$post->post_title.'</span>';
                $output.= '</div>';
                $output.= '<div class="uk-width-1-3 uk-width-small-2-5-1">';
                  $output.= '<h6 class="tm-uppercase">Category</h6>';
                  $output.= '<span class="tm-uppercase">'.get_cat_name( $challenge_category_id ).'</span>';
                $output.= '</div>';
                $output.= '<div class="uk-width-1-3 uk-width-small-2-5-1">';
                  $output.= '<h6 class="tm-uppercase">Points</h6>';
                  $output.= '<span class="tm-uppercase">'.$post_meta.'</span>';
                $output.= '</div>';
              $output.= '</div>';
            $output.= '</div>';
        	 $output.= '</div>';
       	 $output.= '</article>';
        $output.= '</div>';
      }
      $output.= epicPagination($totalPages);
	 }else{
     $output.= '<h2>'.__("No Record Found","epic").'</h2>';
   }
   $output.= '</div>';
   return  $output;
}
