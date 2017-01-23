<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;
$opt = get_option('support_page_options');
$randmUser = rand(0,999999);
if(isset($opt['ip_address'])){
$ip = getenv("REMOTE_ADDR");
}
$ua=getBrowser();
$yourbrowser= $ua['name'] . " " . $ua['version'] . " on " .$ua['platform']; 
include('lvcht_footer.php');    
if(!empty($opt['chatbox_alignment'])){	 
	  if(($opt['chatbox_alignment'])=="bottom_right"){
		  $postision_chatbox= "right:0;";
	  }else if(($opt['chatbox_alignment'])=="bottom_left"){
		 $postision_chatbox= "left:0;";  
	  }
  }
  if(!empty($opt['change_color_scheme']) && $opt['change_color_scheme']=='yes'){
	  if(!empty($opt['color_scheme'])){		  
     if(($opt['color_scheme'])=="clr_shade_default"){
		  $top= "background:#262525; color:#fff;";
		  $bottom= "background:#ddt;";
		  $border = "border: 6px solid #262525;";
		  echo '<style>
		  .fa-minus::before, .fa-plus::before {
           color: #ffffff !important;
           } </style>';
	  }else if(($opt['color_scheme'])=="clr_shade_1"){
		   $top= "background:#ED832F;";
		  $bottom = "background:#666;"; 
		   $border = "border: 6px solid #ED832F;";
	  }  
	  else if(($opt['color_scheme'])=="clr_shade_2"){
		$top= "background:#B97B9D;";
		  $bottom = "background:#5A0031;"; 
		    $border = "border: 6px solid #B97B9D;";
	  } 
	  else if(($opt['color_scheme'])=="clr_shade_3"){
		 $top= "background:#7F7FB3;";
		  $bottom = "background:#666;";
		     $border = "border: 6px solid #7F7FB3;";
	  }   
	  }
  }?>
  <?php if(!empty($opt['offline_chat']) && $opt['offline_chat']=="yes" ){
$chat_clr = "c_red";
}elseif(!empty($opt['offline_chat']) && $opt['offline_chat']=="no" ){
$chat_clr = "c_yellow";	
}else{
$chat_clr = "";
}

?>
<?php if(!empty($opt['enable_chat']) && $opt['enable_chat'] == 'yes'):?>
<div class="chat_container" style="<?php echo $postision_chatbox; ?>">
<div class="chat_window_k">
<div class="chat_panel"  style="<?php echo $border; ?>">
<div class="chat_panel_heading_outer" >
<div class="chat_panel_heading toggle_pm" style="<?php echo $top;?> ">
<i class="fa fa-circle <?php echo $chat_clr; ?>" aria-hidden="true"></i> Live Chat <!--a href="javascript:void(0)" class="fa fa-minus" alt="Close" title="Close">  </a-->
</div> <!--chat_panel_heading-->
</div> <!--chat_panel_heading_outer-->


 <?php  $defImg2 = plugins_url( 'img/user2-default.png', __FILE__ );
$t=time();
 if(!empty($opt['offline_chat']) && $opt['offline_chat']=="yes" )
 { ?> 
<div class="offline_area chat_panel_body">
<p class="ofln_txt"><?php  if(!empty($opt['offline_chat_text1'])){ _e($opt['offline_chat_text1'],'support'); } else {  _e("We are currently offline, Drop your message here, we will be back to you soon.",'support'); }?></p>
<form id="offline_form">
<div class="offln_name"><input type="text" name="offln_name_user" class="ofln_frm name_off" placeholder="Name" ></div>
<div class="offln_email"><input type="text" name="offln_email_user" class="ofln_frm email_off" placeholder="Email" ></div>
<div class="offln_msg"><textarea name="offln_msg_user" cols="25" rows="1" class="ofln_txt msg_off" placeholder="Message" ></textarea></div>
<input type="submit" name="ofln_msg" value="Send" class="ofln_msg">
</form>
</div>
<?php  }else { ?>
<div class="chat_panel_body"  style=" <?php echo $border; ?>">
<div class="chat-area" id="chat_load">
<?php /* Loading Messages */?>
</div> <!--chat-area-->


<?php
$adminChatId = $this->chatid;
$user1 = $_COOKIE['_chatuser'];
 $chat_option = $wpdb->get_row("SELECT * FROM ".$this->tbl_conversation." WHERE (user_one='".$user1."' AND user_two='".$adminChatId."') OR (user_one='".$adminChatId."' AND user_two='".$user1."')");
 if(count($chat_option) == 1){
	$chat_options = $chat_option->chat_option;
 }
?>

</div> 
<form  enctype="multipart/form-data" id="supportform" onsubmit="event.preventDefault();">
<div class="chat_panel_footer" style=" <?php echo  $bottom;?> ">
<?php if(isset($opt['emoticons'])){ ?><div class="emoti_dv"><a href="javascript:void(0)" id="emojis"><img src="<?php echo plugins_url( 'img/emozi-icon.png', __FILE__ ); ?>" class="emoji_icn"/></a></div> <?php }else{} ?>
<div class="chat_input">
<input type="text" class="t_box" placeholder="<?php if(isset($opt['input_replace'])){echo $opt['input_replace'];}else{}?>" id="msg_area" required <?php if($chat_options == 1){echo "readonly";}?> >
</div>

<div id="emojis_tbl" style="display:none;">
<table class="table">
        <tr class="success">
        <td class="snd_smily">&#128512;</td>
        <td class="snd_smily">&#128513;</td>
		<td class="snd_smily">&#128514;</td>
		<td class="snd_smily">&#128515;</td>
        <td class="snd_smily">&#128516;</td>
		<td class="snd_smily">&#128527;</td>
		
      </tr>
      <tr class="danger">
       
        <td class="snd_smily">&#128519;</td>
		<td class="snd_smily">&#128520;</td>
        <td class="snd_smily">&#128521;</td>
		<td class="snd_smily">&#128530;</td>
		<td class="snd_smily">&#128531;</td>
        <td class="snd_smily">&#128532;</td>
      </tr>
      <tr class="warning">
        <td class="snd_smily">&#128522;</td>
		<td class="snd_smily">&#128523;</td>
		<td class="snd_smily">&#128524;</td>
        <td class="snd_smily">&#128525;</td>
        <td class="snd_smily">&#128526;</td>
		<td class="snd_smily">&#128533;</td>
		
      </tr>
      <tr class="info">
        <td class="snd_smily">&#128536;</td>
		<td class="snd_smily">&#128537;</td>
		<td class="snd_smily">&#128538;</td>
        <td class="snd_smily">&#128539;</td>
        <td class="snd_smily">&#128540;</td>
		<td class="snd_smily">&#128541;</td>
		
      </tr>
	  <tr class="warning">
        <td class="snd_smily">&#128528;</td>
        <td class="snd_smily">&#128529;</td>
		 <td class="snd_smily">&#128517;</td>
		<td class="snd_smily">&#128518;</td>
        <td class="snd_smily">&#128534;</td>
        <td class="snd_smily">&#128535;</td>		
      </tr>
	  <tr class="warning">
        <td class="snd_smily">&#128542;</td>
        <td class="snd_smily">&#128543;</td>
		 <td class="snd_smily">&#128555;</td>
		<td class="snd_smily">&#128556;</td>
        <td class="snd_smily">&#128557;</td>
        <td class="snd_smily">&#128558;</td>		
      </tr>
  </table>
</div>
          <?php if(isset($opt['attachments'])){ ?> 
			 <div class="attach_dv">
			  <div class = "upload-form" id = "file_browse_wrapper">                       
                 <input type = "file" id='file_browse' name = "files" accept = "image/*" class = "files-data" />
                 <input type = "submit" value = "Upload" class = "btn btn-primary btn-upload" />
			  </div> 
			  </div><?php } ?>                                  		
		 
</div>
</form>
 <?php } ?>
</div> <!--chat_panel-->
</div> <!--chat_window_k-->
</div> <!--chat_container-->
<?php endif;?>