<?php  if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
global $post;
?>
<script type='text/javascript' src="<?php echo plugins_url( 'js/jquery.nicescroll.js', __FILE__ )?>"></script>
<script>
  jQuery(document).ready(function() {  
  var nicesx = jQuery(".chat_content").niceScroll({touchbehavior:false,cursorcolor:"#00e10b",cursoropacitymax:0.6,cursorwidth:8});    
  });  
/****Desktop notifications *****/
function notifyMe(message) {
	var bell = "<?php echo plugins_url( 'img/bell-icon.png', __FILE__ ) ?>";
    if (!("Notification" in window)) {
    alert("This browser does not support desktop notification");
  }
  else if (Notification.permission === "granted") {
   var options = {
        body: message,
        icon: bell,
        dir : "ltr"
    };
  var notification = new Notification("You Got one notification",options);
  }
  else if (Notification.permission !== 'denied') {
    Notification.requestPermission(function (permission) {
       if (!('permission' in Notification)) {
        Notification.permission = permission;
      }
      if (permission === "granted") {
        var options = {
              body: message,
              icon: bell,
              dir : "ltr"
          };
        var notification = new Notification("You Got one notification",options);
      }
    });
  }
 }

/*****offline script  ****/
jQuery('document').ready(function(){
jQuery('.ofln_msg').click(function(e){
	e.preventDefault();
	if(jQuery(".name_off").val() != ""){
		if(jQuery(".email_off").val() != ""){
			if(jQuery(".msg_off").val() != ""){					
			  var offline_data = jQuery( "#offline_form" ).serialize();
			  var browser = "<?php echo $yourbrowser ?>";
			  var ip_add = "<?php echo $ip ?>";
			  var ajaxurl = "<?php echo admin_url( 'admin-ajax.php' )?>";
			  var data = {
					'action': 'send_offline_message',
					'offline_data':offline_data,
					'browser':browser,
					'ip_add':ip_add
				 };				 
				 
			jQuery.post(ajaxurl, data, function(response) {
					jQuery("#offline_form").trigger('reset');
					jQuery('.offline_area p').html(response);
			});	
			} } } else {
			alert("Please fill the required fields!");
		}
	});
});

/***offline script end***/

var _chatUser = getCookie('_chatuser');
if( _chatUser == "")
{
	  var _Uid = "<?php echo $randmUser; ?>";
	  var date = new Date();
	  var minutes = 60;
	  date.setTime(date.getTime() + (minutes * 60 * 1000));
	  setCookie("_chatuser", "<?php echo $randmUser; ?>", { expires: date });
}
else
{
	 _Uid = _chatUser;
}
/****** send attachment ******/
function send_attachment_here(result){	
	var ajaxurl = "<?php echo admin_url( 'admin-ajax.php' )?>";
	var dd_msg = jQuery(this).html();
	var  url_site = '<?php echo get_bloginfo('url') ?>';
	var src = url_site + "/" + result;
	var msg = '<a href='+src+'><img src='+src+' height="130px" width="130px"></a>';	 
	var data = {
			'action': 'send_message',
			'msg': msg,
			'tempUserId': _Uid
	};
    if(msg != '')
	{
	 jQuery.post(ajaxurl, data, function(response) {
	 jQuery('#msg_area').val('');
	});
	}
}
/******end send attachment ******/
/***** Message Sent *****/
function sendMessage(e) {
   if(typeof e.type !== 'string' || (e.type == 'keyup' && e.keyCode != 13)) {
        return jQuery('#status').html('no call');
    }
	var variabila = jQuery("#msg_area").val();
    var mesaj = "massage=" + variabila;
    var browser = "<?php echo $yourbrowser ?>";
	var ip_add = "<?php echo $ip ?>";
	var ajaxurl = "<?php echo admin_url( 'admin-ajax.php' )?>";
	var msg = jQuery('#msg_area').val();
	var data = {
			'action': 'send_message',
			'msg': msg,
			'tempUserId': _Uid,
			'browser':browser,
			'ip_add':ip_add
	};
    if(msg != ''){
		jQuery.post(ajaxurl, data, function(response) {
		   jQuery('#msg_area').val('');
		});
	}
}

/*****end Message Sent *****/
/*****load Message*****/
jQuery(document).ready(function () {
	var ajaxurl = "<?php echo admin_url( 'admin-ajax.php' )?>";
	var tempUid = _Uid; 
	var data = {
			'action': 'load_messages',
			'tempUserId': tempUid
	};
	     jQuery.ajax({
            url: ajaxurl,
            global: false,
            type: 'POST',
            data: data,
            async: false, //blocks window close
            success: function(response) {
			jQuery('#chat_load').html(response);
			jQuery('.chat_panel_body').animate({"scrollTop": jQuery('#chat_load')[0].scrollHeight}, "slow");
				}
        });		
	/*jQuery.post(ajaxurl, data, function(response) {
			jQuery('#chat_load').html(response);
			jQuery('.chat_panel_body').animate({"scrollTop": jQuery('#chat_load')[0].scrollHeight}, "slow");
	});*/
});
/*****End load Message*****/
/*****Enter Message*****/
jQuery(document).ready(function () {
  jQuery("input").keypress(function(event) {
   if (event.which == 13) {
       event.preventDefault();
       sendMessage(event);
   }
  });
});
/*****End Enter Message*****/
jQuery(document).ready(function(e) {
/* Load Messages */
  if(_Uid != '') {
    setInterval(function(){
		load_chat_messages(_Uid);
	}, 1000);
  }	
/*****Send Emoticons*****/
jQuery("#emojis").click(function(e){
	e.preventDefault();
	jQuery("#emojis_tbl").toggle(500);
});

jQuery(".snd_smily").click(function(e){
	e.preventDefault();
	var ajaxurl = "<?php echo admin_url( 'admin-ajax.php' )?>";
	var dd_msg = jQuery(this).html();
	var msg = jQuery(this).html();
	var data = {
			'action': 'send_message',
			'msg': msg,
			'tempUserId': _Uid
	};
    if(msg != '') {
		jQuery.post(ajaxurl, data, function(response) {
			jQuery('#msg_area').val('');
			jQuery("#emojis_tbl").toggle(1000);
			
		});
	}
});
});
/*****End Send Emoticons*****/
/*****Load last message*****/
function load_chat_messages(tempId)
{
	var ajaxurl = "<?php echo admin_url( 'admin-ajax.php' )?>";
	var tempUid = tempId; 
	var data = {
			'action': 'load_message',
			'tempUserId': tempUid
	};
	jQuery.post(ajaxurl, data, function(response) {
	if(response != ""){
			var res = jQuery.parseJSON(response);
			if(res.refreshpage != '' && res.refreshpage == '1')
			{
			   window.location.href = window.location;
			}
			var user1 = res.user1;
			var user2 = res.user2;
			var message = res.message;			
			var adminChatId = res.chat_initiator;
			var image_suport = res.image_suport;
			var chat_option = res.chat_option;
			if( chat_option == 1)
			{
				jQuery('.t_box').attr('readonly', true);
			}else if(chat_option == 0){
				jQuery('.t_box').removeAttr('readonly');
			}
			var defImg1 = "<?php echo plugins_url( 'img/user1-default.png', __FILE__ ) ?>";
			if(image_suport != null)
			{
			 var defImg2 = image_suport;	
			}else{
	        var defImg2 = "<?php echo plugins_url( 'img/user2-default.png', __FILE__ ) ?>";
			}
			var msgdate = res.msg_date;
			var msgdatee = res.msgdatee;
			var act = res.act;
			var tone = "<?php echo $opt['sound_to_play'];?>";
			var noti ="<?php echo plugins_url( "/music/".$opt['sound_to_play']."", __FILE__ ) ?>";
			var is_tone = "<?php echo $opt['sound_play'];?>";
			if(is_tone == 'yes'){
			  jQuery('<audio id="chatAudio"><source src="'+noti+'" type="audio/ogg">').appendTo("#chat_load");
			}
			if(user1 == tempUid && act == '1')
			{
				jQuery('<div class="user1 msg_container"></div>').html('<div class="msg_sent"><div class="msg_txt_outer"><div class="msg_txt"><p>'+message+'</p><time>'+msgdate+'</time></div></div><div class="msg_avatar"><img src='+defImg1+'></div></div>').appendTo("#chat_load");				
				jQuery(".chat_panel_body").animate({"scrollTop": jQuery('#chat_load')[0].scrollHeight}, "slow");
		}else if(user1 == adminChatId && act == '1'){
				jQuery('<div class="user2 msg_container"></div>').html('<div class="msg_recieve"><div class="msg_avatar"><img src='+defImg2+'></div><div class="msg_txt_outer"><div class="msg_txt"><p>'+message+'</p><time>'+msgdate+'</time></div></div></div> ').appendTo("#chat_load");
				jQuery(".chat_panel_body").animate({"scrollTop": jQuery('#chat_load')[0].scrollHeight}, "slow");
				 if(is_tone == 'yes'){
						jQuery('#chatAudio')[0].play();
				  }
				 notifyMe(message);
				
		}
    }			
			
	});
}
/*****End Load last message*****/
jQuery(document).ready(function(e) {
jQuery(".toggle_pm").click(function(){
jQuery('.chat_panel_body').slideToggle()
//jQuery(".toggle_pm").toggleClass("fa-plus fa-minus");
});
});
/*** Send attachment ***/
jQuery(document).ready(function() {
jQuery('#file_browse').change(function(e) {
 e.preventDefault;
 var fd = new FormData();
 var files_data =jQuery('.upload-form .files-data'); 
  jQuery.each(jQuery(files_data), function(i, obj) {
     jQuery.each(obj.files,function(j,file){
     fd.append('files[' + j + ']', file);
     });
   });
    fd.append('action', 'send_attachment');  
    fd.append('post_id','1'); 
    jQuery.ajax({
		type: 'POST',
		url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
		data: fd,
		contentType: false,
		processData: false,
		success: function(response){
		   send_attachment_here(response);
		 }
     });
});
});                     
  jQuery(document).ready(function() {  
  var nicesx = jQuery(".chat_panel_body").niceScroll({touchbehavior:false,cursorcolor:"#00e10b",cursoropacitymax:0.6,cursorwidth:8});    
  });  
</script>