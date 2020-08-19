jQuery(document).ready(function(){(
  function($){
    $('.sfm-audio').load();
    $('.sfm-menu-item').load();

    jQuery('#search_user').parent('span').addClass('search_parent');
    jQuery('.search_parent').parent('div').addClass('search_section');

    $('.sfm-menu li a').click(function(){
      $('.sfm-menu-item')[0].play();
      });
  }(jQuery))
});

$=jQuery;
$(document).on('click','.hit-list', function(){
  var challenge_id  = $(this).parents('.ac-col').attr('pid');
  if(challenge_id == undefined || challenge_id == ''){
    challenge_id  = $(this).attr('id');
  }
  var btn = $(this);
  add_challenge_to_hit_list(challenge_id,btn);
});
$(document).on('click','.remove-hit-list', function(){
  var challenge_id=	$(this).attr('id');
  remove_hit_list(challenge_id);
});
/*------	ADD CHELLENGE INTO HIT LIST	-------*/
var add_challenge_to_hit_list=function(challenge_id,btn){
  console.log('id',challenge_id);
  $.ajax({
    type:"POST",url:wp_ajax_url(),
    data:{'action':'my_challenge_hit_list','challenge_id':challenge_id,'act':'add_to_hit_list'},
    beforeSend: function() {
      swal({
        title:"",
        text:"<h3>Please wait...</h3>",
        imageUrl:window.location.hostname+'/wp-content/plugins/wp-ultimate-exercise-premium/core/img/write.gif',
        showConfirmButton:false,
        html: true
      });
    },success:function(data){
      var res = $.parseJSON(data);
      if(res.response == 1){
        swal({
          title: "",
          text: "<h3>Challenge added to Hit List! Game On!</h3><a href=window.location.hostname+'/profile' class='go-to-hit-list'>Go to Hit List</a>",
          imageUrl: window.location.hostname+'/wp-content/plugins/wp-ultimate-exercise-premium/core/img/x2_jump.gif', timer: 3000,
          html: true,
          showConfirmButton: false
        });
        btn.removeClass('hit-list').addClass('remove').html('<i class="fa fa-trash fa-6" aria-hidden="true"></i>Remove From list');
      }else{
        swal({
          title: "",
          text: " <h1>Already on hit list! </h1> <i class='fa fa-exclamation-triangle' aria-hidden='true' style='color: red;display: block;font-size: 90px;'></i>Please check your profile for your current list",
          timer: 2000,
          html: true,
          showConfirmButton: false
        });
      }}
    });
};

/*-----	REMOVE CHELLENGE FROM HIT LIST	-----*/
var remove_hit_list=function(challenge_id,btn){
  $.ajax({
    type:"POST",
    url:wp_ajax_url(),
    data:{
      'action':'my_challenge_hit_list',
      'challenge_id':challenge_id,
      'act':'remove_hit_list'
    },
    beforeSend: function() {
      swal({
        title:"",
        text:"<h3>Please wait...</h3>",
        imageUrl:window.location.hostname+'/wp-content/plugins/wp-ultimate-exercise-premium/core/img/write.gif',
        showConfirmButton:false,
        html: true
      });
    },
    success:function(data){
      var res = $.parseJSON(data);
      if(res.response == 1){
        swal({
          title: "",
          text: "<h3>Challenge deleted</h3>",
          imageUrl:window.location.hostname+'/wp-content/plugins/wp-ultimate-exercise-premium/core/img/x2_jump.gif', timer: 4000,
          html: true,
          showConfirmButton: false
      });
      btn.removeClass('remove').addClass('hit-list').html('<i class="fa fa-plus" aria-hidden="true" ></i>HIT LIST');
    }else{
      swal({
        title: "",
        text: "<h1>Error </h1> <i class='fa fa-exclamation-triangle' aria-hidden='true' style='color: red;display: block;font-size: 90px;'></i>Please try again later",
        timer: 2000,
        html: true,
        showConfirmButton: false
      });
    }}
  });
}
/*--------------	ADMIN SECTION	------------*/
$(document).on('click','.serch_hit_list', function(){
  var uid=$('#hit_email').val();
  if(uid==''){
    alert('Please Select User');
    return false;
  }
  search_hit_list_by_user_name(uid);
});
$(document).on('click','.unlock-achievement-btn', function(){
  var challenge_id	=	$(this).attr('id');
  var uid	= $('#hit_email').val();
  var dpa_code  = $(this).attr('dpa_code');
  unlock_achievement_admin(challenge_id,uid,dpa_code);
});
$(document).on('click','.remove', function(){
  var btn = $(this);
  var challenge_id	=  $(this).attr('id');
  remove_hit_list(challenge_id,btn);
});
var search_hit_list_by_user_name=function(uid){
  $.ajax({
    type:"POST",
    url:wp_ajax_url(),
    data:{
      'action':'challenges_admin_ajax',
      'uid':uid,
      'act':'retrive_hit_list_challenges'
    },
    beforeSend: function() {
      swal({
        title:"",
        text:"<h3>Please wait...</h3>",
        imageUrl:window.location.hostname+'/wp-content/plugins/wp-ultimate-exercise-premium/core/img/write.gif',
        showConfirmButton:false,
        html:true
      });
    },
    success:function(data){
      $('#admin-hit-list').html('');
      if(data){
        swal.close();
        $('#admin-hit-list').html(data);
      }else{
        swal({
          title: "",
          text: "<h1>OOPS !</h1> <i class='fa fa-exclamation-triangle'aria-hidden='true' style='color: red;display: block;font-size: 90px;'></i>Something is going wrong",
          timer: 2000,
          html: true,
          showConfirmButton: false
        });
    	}
    }
  });
};
var unlock_achievement_admin=function(challenge_id,uid,dpa_code){
   $.ajax({
     type:"POST",
     url:wp_ajax_url(),
     data:{
       'action':'challenges_admin_ajax',
       'uid':uid,
       'pid':challenge_id,
       'dpa_code':dpa_code,
       'act':'unlock_challenge_ajax'
     },
     beforeSend: function() {
       swal({
         title:"",
         text:"<h3>Please wait...</h3>",
         imageUrl:window.location.hostname+'/wp-content/plugins/wp-ultimate-exercise-premium/core/img/write.gif',
         showConfirmButton:false,
         html:true
       });
     },
     success:function(result){
       var aData	=jQuery.parseJSON(result);
       if(aData.response==1){
         swal({
           title: "",
           text: "<h3>Congratulations !</h3>Achievement Unlocked Successfully",
           imageUrl:window.location.hostname+'/wp-content/plugins/wp-ultimate-exercise-premium/core/img/x2_jump.gif', timer: 4000,
           html: true,
           showConfirmButton: false
         });
       }else{
         swal({
           title: "",
           text: "<h1>OOPS !</h1> <i class='fa fa-exclamation-triangle'aria-hidden='true' style='color: red;display: block;font-size: 90px;'></i>Please Try again!",
           timer: 2000,
           html: true,
           showConfirmButton: false
         });
       }
     }
   });
 }
