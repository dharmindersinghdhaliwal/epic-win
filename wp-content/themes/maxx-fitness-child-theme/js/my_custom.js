jQuery(document).ready(function(){(function($){
	$('.sfm-audio').load();
	$('.sfm-menu-item').load();
	jQuery('#search_user').parent('span').addClass('search_parent');
	jQuery('.search_parent').parent('div').addClass('search_section');
		
	$('.sfm-menu li a').click(function(){
		console.log('li clicked');
		$('.sfm-menu-item')[0].play();
	});
	
}(jQuery))});