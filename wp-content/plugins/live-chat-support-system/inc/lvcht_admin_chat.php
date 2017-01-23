<?php  if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<script>
function notifyMe1(message) {
var bell = "<?php echo plugins_url( 'img/bell-icon.png', __FILE__ ) ?>";
  // Let's check if the browser supports notifications
  if (!("Notification" in window)) {
    alert("This browser does not support desktop notification");
  }
// Let's check if the user is okay to get some notification
  else if (Notification.permission === "granted") {
    // If it's okay let's create a notification
  var options = {
        body: message,
        icon: bell,
        dir : "ltr"
    };
  var notification = new Notification("You Got one notification admin",options);
  }

  // Otherwise, we need to ask the user for permission
  // Note, Chrome does not implement the permission static property
  // So we have to check for NOT 'denied' instead of 'default'
  else if (Notification.permission !== 'denied') {
    Notification.requestPermission(function (permission) {
      // Whatever the user answers, we make sure we store the information
      if (!('permission' in Notification)) {
        Notification.permission = permission;
      }

      // If the user is okay, let's create a notification
      if (permission === "granted") {
        var options = {
              body: message,
              icon: bell,
              dir : "ltr"
          };
        var notification = new Notification("You Got one notification admin",options);
      }
    });
  }

  // At last, if the user already denied any notification, and you
  // want to be respectful there is no need to bother them any more.
}


var _Uid = "<?php echo sanitize_text_field($_GET['tempuser']); ?>"
jQuery(document).ready(function () {
 jQuery("input").keypress(function(event) {
        if (event.which == 13) {
            event.preventDefault();
            sendMessage(event);
        }
    });
});
/* Message Sent */
function sendMessage(e) {
	
    e.preventDefault();
    if(typeof e.type !== 'string' || (e.type == 'keyup' && e.keyCode != 13)) {
        return jQuery('#status').html('no call');
    }
	jQuery('#alertme').text('Sending..');
    var variabila = jQuery("#msg_area").val();
    var mesaj = "massage=" + variabila;

    var ajaxurl = "<?php echo admin_url( 'admin-ajax.php' )?>";
	var msg = jQuery('#msg_area').val();
    var agent_id = "<?php echo $agent_id;	?>";
	if(agent_id != '')
	{
		var agent = agent_id;
	}
	var data = {
			'action': 'admin_send_message',
			'msg': msg,
			'tempUserId': _Uid,
			'agent':agent
		};
        if((msg != '') ||(msg != ' ') )
		{
		jQuery.post(ajaxurl, data, function(response) {
			jQuery('#alertme').text('');
			jQuery('#msg_area').val('');
		});
		}
		else
		{
		jQuery('#alertme').text('Please Enter Text.');	
		}

}
function send_attachment_here(result){
	
	var ajaxurl = "<?php echo admin_url( 'admin-ajax.php' )?>";
	 var dd_msg = jQuery(this).html();
	var  url_site = '<?php echo get_bloginfo('url') ?>';
	var src = url_site + "/" + result;
	var msg = '<a href='+src+'><img src='+src+' height="130px" width="130px"></a>';	 
	var agent_id = "<?php echo $agent_id;	?>";
	if(agent_id != '')
	{
		var agent = agent_id;
	}
	var data = {
			'action': 'admin_send_message',
			'msg': msg,
			'tempUserId': _Uid,
			'agent':agent
		};
        if(msg != '')
		{
		jQuery.post(ajaxurl, data, function(response) {
			notii();
			jQuery('#alertme').text('');
			jQuery('#msg_area').val('');
			
		});
		}
		else
		{
		jQuery('#alertme').text('Please Enter Text.');	
		} 
}
jQuery(document).ready(function () {
	var ajaxurl = "<?php echo admin_url( 'admin-ajax.php' )?>";
	var tempUid = _Uid; 
	var agent_id = "<?php echo $agent_id;	?>";
	if(agent_id != '')
	{
		var agent = agent_id;
	}
	var data = {
			'action': 'load_admin_messages',
			'tempUserId': tempUid,
			'agent':agent
		};
		jQuery.post(ajaxurl, data, function(response) {
			jQuery('#loadchat').html(response);
			jQuery("#loadchat").animate({"scrollTop": jQuery('#loadchat')[0].scrollHeight}, "slow");
		});
});

jQuery(document).ready(function(e) {
/* Load Messages */

setInterval(function(){
		load_admin_chat_messages(_Uid);
	}, 1000);
});	
function load_admin_chat_messages(tempId)
{
	var ajaxurl = "<?php echo admin_url( 'admin-ajax.php' )?>";
	var tempUid = tempId; 
	var agent_id = "<?php echo $agent_id;	?>";
	if(agent_id != '')
	{
		var agent = agent_id;
	}
	var data = {
			'action': 'admin_load_message',
			'tempUserId': tempUid,
			'agent':agent
		};
		jQuery.post(ajaxurl, data, function(response) {
			//alert(response);
			//jQuery('#loadchat').html(response);
			var res = jQuery.parseJSON(response);
			//alert(res);
			var user1 = res.user1;
			//alert(tempUid);
			var user2 = res.user2;
			var message = res.message;	
			if(agent_id != '')
				{
					var adminChatId = agent_id;
				}else{			
			var adminChatId = "<?php echo $this->chatid ?>";
				}
			//alert(adminChatId);
			var defImg1 = "<?php echo plugins_url( 'img/user1-default.png', __FILE__ ) ?>";
	        var defImg2 = "<?php echo plugins_url( 'img/user2-default.png', __FILE__ ) ?>";
			var msgdate = res.msg_date;
			var msgdatee = res.msgdatee;
				var act = res.act;
			var tone = "<?php echo $opt['sound_to_play'];?>";
			 var noti ="<?php echo plugins_url( "/music/".$opt['sound_to_play']."", __FILE__ ) ?>";
			 var is_tone = "<?php echo $opt['sound_play'];?>";
			 if(is_tone == 'yes'){
			jQuery('<audio id="chatAudio1"><source src="'+noti+'" type="audio/ogg">').appendTo("#loadchat");
			 }
			if(user1 == tempUid && act == '1')
			{                        
			  jQuery('<li class="left clearfix clientmsg"></li>').html('<span class="chat-img pull-left">         <img class="" alt="User Avatar" src='+defImg1+' width="35"></span><div class="chat-body clearfix"><div class="header"><strong class="primary-font"> '+tempUid+' - Client </strong>        <small class="text-muted"><i class="fa fa-clock-o fa-fw"></i> '+msgdate+'</small></div><p>'+message+'</p></div>').appendTo("#loadchat");	  
			  jQuery("#loadchat").animate({"scrollTop": jQuery('#loadchat')[0].scrollHeight}, "slow");
			   if(is_tone == 'yes'){
				 jQuery('#chatAudio1')[0].play();
			   }
		       notifyMe1(message);					
		}
		else if(user1 == adminChatId && act == '1'){
			 jQuery('<li class="right clearfix adminmsg"></li>').html('<span class="chat-img pull-right"><img class="" alt="User Avatar" src='+defImg2+' width="35"></span><div class="chat-body clearfix"><div class="header"><small class=" text-muted"><i class="fa fa-clock-o fa-fw"></i>'+msgdate+'</small>   <strong class="pull-right primary-font">You&nbsp;</strong></div><p>'+message+'</p></div>').appendTo("#loadchat");				
				jQuery("#loadchat").animate({"scrollTop": jQuery('#loadchat')[0].scrollHeight}, "slow");
			}
		});
}
jQuery(document).ready(function(e) {
jQuery("#emojis1").click(function(){
	jQuery("#emojis_tbl1").toggle(1000);
});
jQuery(".snd_smily1").click(function(e){	
	 var ajaxurl = "<?php echo admin_url( 'admin-ajax.php' )?>";
	var msg = jQuery(this).html();
	var agent_id = "<?php echo $agent_id;	?>";
	if(agent_id != '')
	{
		var agent = agent_id;
	}
	var data = {
			'action': 'admin_send_message',
			'msg': msg,
			'tempUserId': _Uid,
			'agent':agent
		};
        if(msg != '')
		{
		jQuery.post(ajaxurl, data, function(response) {
			jQuery("#emojis_tbl").toggle(1000);
			jQuery('#msg_area').val('');
		});
		}
		else
		{
		jQuery('#alertme').text('Please Enter Text.');	
		}
});
jQuery('.close_op').click(function(){
	var ajaxurl = "<?php echo admin_url( 'admin-ajax.php' )?>";
	var data = {
			'action': 'set_chat_close',
			'tempUserId': _Uid
		};
		jQuery.post(ajaxurl, data, function(response) {
			if(response == 'success')
			{
			alert("chat closed");
			jQuery('#msg_area').prop('readonly', true);
			window.location.href = "<?php bloginfo('url');?>/wp-admin/admin.php?page=support_page";
			}
		});
});
});

                            jQuery(document).ready(function() {
                                // When the Upload button is clicked...
                                 jQuery('#file_browse1').change(function(e) {								
                                    e.preventDefault;                                					
                                   var fd = new FormData();
                                    var files_data = jQuery('.upload-form .files-data'); // The <input type="file" /> field
                                    
                                    // Loop through each data and create an array file[] containing our files data.
                                    jQuery.each(jQuery(files_data), function(i, obj) {
                                        jQuery.each(obj.files,function(j,file){
                                            fd.append('files[' + j + ']', file);
                                        })
                                    });
                                    
                                    // our AJAX identifier
                                    fd.append('action', 'send_attachment');  
                                    
                                    // uncomment this code if you do not want to associate your uploads to the current page.
                                    fd.append('post_id','1'); 

                                    jQuery.ajax({
                                        type: 'POST',
                                        url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
										data: fd,
                                        contentType: false,
                                        processData: false,
                                        success: function(response){
											//alert(response);
											send_attachment_here(response);
                                          //  $('.upload-response').html(response); // Append Server Response
                                        }
                                    });
                                });
                            });                     
  </script>