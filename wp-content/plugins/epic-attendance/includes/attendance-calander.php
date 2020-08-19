<?php
add_shortcode('attendance_calander','attendance_calander');

function attendance_calander(){
	?>
	<script>
	jQuery(document).ready(function(e){(function($){
		var defaultCalendar = $("#cal1").rangeCalendar();
	}(jQuery))});
	</script>
    <div id="cal1"></div>
	<?php
	echo epic_day_classes();
	echo epic_class_posts_name();
}

# Get Class's Categories |
function epic_classes_cat(){
	$taxonomy = 'mrd_classes_cats';
	$terms	=	get_terms($taxonomy, array('parent'=>0,'orderby'=>'slug','hide_empty'=>false));
	foreach($terms as $term){
		echo $term->term_id;
	}
}

# Get Custom Post Type	|
function epic_class_posts(){
	$sun_arr	=	array();
	$mon_arr	=	array();
	$tue_arr	=	array();
	$wed_arr	=	array();
	$thu_arr	=	array();
	$fri_arr	=	array();
	$sat_arr	=	array();
	$args = array(
		'posts_per_page'	=> -1,
		'orderby'			=> 'date',
		'order'				=> 'DESC',
		'post_type'		=> 'mrd_classes',
		'post_status'		=> 'publish'
	);
	$posts	=	get_posts($args);
	foreach($posts as $post){
		$id		=	$post->ID;
		$title	=	$post->post_title;
		$day	=	substr($title,0,3);
		if($day=='Sun'){array_push($sun_arr,$id);}
		if($day=='Mon'){array_push($mon_arr,$id);}
		if($day=='Tue'){array_push($tue_arr,$id);}
		if($day=='Wed'){array_push($wed_arr,$id);}
		if($day=='Thu'){array_push($thu_arr,$id);}
		if($day=='Fri'){array_push($fri_arr,$id);}
		if($day=='Sat'){array_push($sat_arr,$id);}
	}
	$days_arr	=	array($sun_arr,$mon_arr,$tue_arr,$wed_arr,$thu_arr,$fri_arr,$sat_arr);
	return json_encode($days_arr);
}

# Get Classes of a Particular Day  |
function epic_day_classes(){
	?>
	<script>
	   jQuery(document).ready(function(e){(function($){
		   $(document).on('click','.class-count',function(event){
			   $('.schedule').hide();
			   var cell	=	$(this).parents('.cell-content');
			   var mnth	=	cell.find('.mnth').val();
			   var yer		=	cell.find('.yer').val();
			   var day		=	cell.find('.day').html();
			   var number	=	cell.find('.day-number').html();
			   var chead	=	number+'&nbsp;'+mnth+','+yer;
			   console.log(chead);
			   var cids	=	<?php echo epic_class_posts();?>;
			   if(day=='Sun'){ var cdind=0;}
			   if(day=='Mon'){ var cdind=1;}
			   if(day=='Tue'){ var cdind=2;}
			   if(day=='Wed'){ var cdind=3;}
			   if(day=='Thu'){ var cdind=4;}
			   if(day=='Fri'){ var cdind=5;}
			   if(day=='Sat'){ var cdind=6;}
			   var day_id	=	cdind;
			   console.log('day'+cdind);
			   $('.day'+day_id).css({'position':'absolute','display':'block','z-index': 1,'top':event.pageY-190,'left':event.pageX-160});
			   var current_day	=('.day'+cdind);
			   $(current_day+' .chead'+cdind).each(function(index, element) {
				   $(this).empty();
				   var ctime	= $(this).append(chead);
				});
			   $('.fa-times').click(function(){$('.schedule').hide();});
			   $(current_day+' .purl').each(function(index, element) {
				   var alink	= $(this).attr('href');
				   $(this).attr('href',alink);
				   $(this).attr('href',alink+'?date='+number+'&month='+mnth+','+yer);
			   });
		   });
		   }(jQuery))});
	</script>
	<?php
}

# Get Custom Post Type  Name  |
function epic_class_posts_name(){
	$class_ids		=	epic_class_posts();
	$json_decode	=	json_decode($class_ids,true);
	for($i=0;$i<count($json_decode);$i++){
		$classarr	=	$json_decode[$i];
		echo '<div class="schedule day'.$i.'"style="display:none">';
		echo '<i class="fa fa-times" aria-hidden="true"></i>';
		if($i==0){$mrd_class	=	'mrd_class_sunday';}
		if($i==1){$mrd_class	=	'mrd_class_monday';}
		if($i==2){$mrd_class	=	'mrd_class_tuesday';}
		if($i==3){$mrd_class	=	'mrd_class_wednesday';}
		if($i==4){$mrd_class	=	'mrd_class_thursday';}
		if($i==5){$mrd_class	=	'mrd_class_friday';}
		if($i==6){$mrd_class	=	'mrd_class_saturday';}
		for($j=0;$j<count($classarr);$j++){
			$pid	=	$classarr[$j];
			$p		=	get_post($pid);
			$ptitle	=	$p->post_title;
			$purl	=	get_permalink($pid);
			$pimg	=	get_the_post_thumbnail($pid);
			$time	=	get_post_meta($pid,$mrd_class,true);
				echo '<ul>';
					echo '<li class="chead'.$i.' chead"></li>';
					echo '<li>';
					echo '<a href="'.$purl.'" class="purl">';
					echo '<p class="pimg">'.$pimg.'</p>';
					echo '<span class="ptitle">'.$ptitle.'</span>';
					echo '<span class="ptime"><i class="fa fa-clock-o fa-6" aria-hidden="true"></i>'.$time.'</span>';
					echo '</a>';
					echo '</li>';
				echo '</ul>';
		}
		echo '</div>';
	}
}