<?php  if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<script>
jQuery(document).ready(function() {
    jQuery('#trackpage').DataTable();
});

function notifyMe() {
 var bell = "<?php echo plugins_url( 'img/bell-icon.png', __FILE__ ) ?>";
 if (!("Notification" in window)) {
    alert("This browser does not support desktop notification");
   }
 else if (Notification.permission === "granted") {
  var options = {
        body: 'New chat Invitation',
        icon: bell,
        dir : "ltr"
    };
  var notification = new Notification("You Got one notification admin",options);
  }  
  else if (Notification.permission !== 'denied') {
    Notification.requestPermission(function (permission) {
      if (!('permission' in Notification)) {
        Notification.permission = permission;
      }
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
}

jQuery(document).ready(function(e) {
setInterval(function(){
		load_admin_dashboard();
	}, 1000);
});
function load_admin_dashboard()
{
	var ajaxurl = "<?php echo admin_url( 'admin-ajax.php' )?>";
	var data = {
			'action': 'admin_dashboard',
	};
	jQuery.post(ajaxurl, data, function(response) {
		 var res = jQuery.parseJSON(response);
		 if(res != "" &&  res != null){
			var m_id = res.id;
			var user_from = res.user_from;
			var allchat_id = res.allchat_id;			
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
			jQuery('<tr></tr>').html('<td>'+m_id+'</td><td>'+user_from+'</td><td>'+allchat_id+'</td><td>'+ip+','+browser+'</td> <td class="'+classse+'">'+convNewDate+'</td><td><a href="admin.php?page=support_chat&convid='+allchat_id+'&tempuser='+user_from+'">Chat Now</a></td><td><a href="admin.php?page=support_page&action=delete&convid='+allchat_id+'" onClick="return confirm("Are you sure want to remove ?")" title="Delete Conversation and chat.">Delete</a></td>').appendTo("#trackpage");	 
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