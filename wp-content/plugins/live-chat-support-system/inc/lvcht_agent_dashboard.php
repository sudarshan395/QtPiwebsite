 <script>
jQuery(document).ready(function(){
jQuery('.initiate_chat').click(function(e){
	//alert('working');
	e.preventDefault();
	var url_id= jQuery(this).attr("url_id");
	//alert(url_id);
	//jQuery('#' + url_id).show();
	 var ajaxurl = "<?php echo admin_url( 'admin-ajax.php' )?>";
	var data = {
			'action': 'agent_initiate_chat',
			'agent_id':'<?php echo $userId ;?>',
			'conv_id': url_id
			};
		jQuery.post(ajaxurl, data, function(response) {
			alert(response);
			if(response=='success')
			{
			jQuery('#' + url_id).show();	
			}else if(response=='done')
			{
				jQuery('#' + url_id).show();	
			//jQuery('.' + url_id).hide();	
			jQuery('.' + url_id +'a' ).removeClass( "initiate_chat" );
			} else if(response=='Client busy with another agent')
			{
				alert(response);
			}
		}); 
	
	
});
});

jQuery(document).ready(function() {
    jQuery('#trackpage').DataTable();
});

function notifyMe() {
		var bell = "<?php echo plugins_url( 'img/bell-icon.png', __FILE__ ) ?>";
  // Let's check if the browser supports notifications
  if (!("Notification" in window)) {
    alert("This browser does not support desktop notification");
  }
// Let's check if the user is okay to get some notification
  else if (Notification.permission === "granted") {
    // If it's okay let's create a notification
  var options = {
        body: 'New chat Invitation',
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
              body: 'New chat Invitation',
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

jQuery(document).ready(function(e) {
	//alert('working');
setInterval(function(){
		load_admin_dashboard();
	}, 1000);
});
function load_admin_dashboard()
{
	var ajaxurl = "<?php echo admin_url( 'admin-ajax.php' )?>";
	var data = {
			'action': 'agent_dashboard',
			};
		jQuery.post(ajaxurl, data, function(response) {
			//jQuery('#loadchat').html(response);
			//console.log(response);
			 var res = jQuery.parseJSON(response);
			 if(res != "" &&  res != null){
			var m_id = res.id;
			var user_from = res.user_from;
			var allchat_id = res.allchat_id;
            var chat_option = res.chat_option;	
             var classse = res.class;
			 var browser =  res.browser;
			 var ip = res.ip;
			var convNewDate = res.convNewDate;
			var message = 'New chat request';
			var act = res.act;
			var tone = "<?php echo $opt['sound_to_play'];?>";
			 var noti ="<?php echo plugins_url( "/music/".$opt['sound_to_play']."", __FILE__ ) ?>";
			 var is_tone = "<?php echo $opt['sound_play'];?>";
			 if(is_tone == 'yes'){
			jQuery('<audio id="chatAudio1"><source src="'+noti+'" type="audio/ogg">').appendTo("#trackpage");
			 }                    
           if(act == '1'){
			   jQuery('.dataTables_empty').html("");
			jQuery('<tr></tr>').html('<td>'+m_id+'</td><td>'+user_from+'</td><td>'+m_id+'</td><td>'+ip+','+browser+'</td>  <td class="'+classse+'">'+convNewDate+'</td><td><div class="'+allchat_id+'"><a href="javascript:void(0)" class="initiate_chat" url_id="'+allchat_id+'" >Initiate Chat</a></div></td>').appendTo("#trackpage");	 

			  jQuery("#trackpage").animate({"scrollTop": jQuery('#trackpage')[0].scrollHeight}, "slow");
				if(is_tone == 'yes'){
				jQuery('#chatAudio1')[0].play();
				 }
		       notifyMe();	
		   }			   
			 }
		});
}
</script>