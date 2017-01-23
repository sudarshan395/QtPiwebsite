<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;
$opt = get_option('support_page_options'); 
$tempUid = sanitize_text_field($_POST['tempUserId']); //user ID
$admin_id = $wpdb->get_row("SELECT * FROM ".$this->tbl_conversation." WHERE user_one='".$tempUid."' ");
$chat_initiator = $admin_id->user_two;
if(!empty($chat_initiator))
{
$adminChatId =$chat_initiator;
$profile_id = $wpdb->get_row("SELECT * FROM ".$this->tbl_agents." WHERE agent_id ='".$adminChatId."' ");	
$profile = $profile_id->agent_image;
$profile_name = $profile_id->agent_name;	
}else{
	$adminChatId = $this->chatid;
}
$user_two = $adminChatId;
$convId = $wpdb->get_row("SELECT * FROM ".$this->tbl_conversation." WHERE (user_one='".$tempUid."' AND user_two='".$user_two."') OR (user_one='".$user_two."' AND user_two='".$tempUid."')");
if(count($convId) == 1){
 $conversation_id = $convId->id; //conversion ID
 $getMessages = $wpdb->get_results("select * from ".$this->tbl_messages." where conv_id = '".$conversation_id."' order by mid ASC");
 $html = '';
$user1 = $getMessages->user_from; 
 if(!empty($getMessages))
 {
	 foreach($getMessages as $getMsg):
	
	 $user1 = $getMsg->user_from; //from
	 $user2 = $getMsg->user_to; //to
	 $message = $getMsg->message; //msg
	 $attachment = $getMsg->attachment; //attachment
	 $msgdatee = strtotime($getMsg->msgdate);
	 $msgtime = date("h:i:s a", $msgdatee);
	 $currentDate = date('d-m-Y');
	 $getDate = date('d-m-Y', $msgdatee);
	// $song_url = plugins_url( "/music/noti.mp3", __FILE__ );
	 if($getDate == $currentDate)
	 {
		$msgdate = 'Today - '.$msgtime; 
	 }
	 else
	 {
		$msgdate = $getMsg->msgdate;  
	 }
	 $defImg1 = plugins_url( 'img/user1-default.png', __FILE__ );
	 if(!empty($profile))
	 { 
     $defImg2 = $profile;
    }else{
	 $defImg2 = plugins_url( 'img/user2-default.png', __FILE__ );
	}
	 if($user1 == $tempUid)
	 {
	    $html .= '<div class="user1 msg_container">';
		$html .= '<div class="msg_sent">';
		$html .= '<div class="msg_txt_outer">';
		$html .= '<div class="msg_txt">';
		$html .= '<p>'.$message.'</p>';
		$html .= '<time>'.$msgdate.'</time>';
		$html .= '</div></div>';
		$html .= '<div class="msg_avatar"><img src="'.$defImg1.'" ></div>';
		$html .= '</div></div>'; 
	 }
	 else
	 {
	 
	$html .= ' <div class="user2 msg_container" title="'.$profile_name.'">';
	$html .= '<div class="msg_recieve">';
	$html .= '<div class="msg_avatar"><img src="'.$defImg2.'" ></div>';
	$html .= '<div class="msg_txt_outer">';
	$html .= '<div class="msg_txt">';
	$html .= '<p>'.$message.' </p>';
	$html .= '<time>'.$msgdate.'</time>';
	$html .= '</div></div></div></div>';
	 }
	 endforeach;
	 echo $html;
 }
 else
 {
	if(!empty($profile))
	 { 
     $defImg2 = $profile;
    }else{
	 $defImg2 = plugins_url( 'img/user2-default.png', __FILE__ );
	}
	 $html .= ' <div class="user2 msg_container" title="'.$profile_name.'">';
	$html .= '<div class="msg_recieve">';
	$html .= '<div class="msg_avatar"><img src="'.$defImg2.'" ></div>';
	$html .= '<div class="msg_txt_outer">';
	$html .= '<div class="msg_txt">';
	$html .= '<p>'.$opt['text_to_display'].'</p>';
	$html .= '<time>'.$msgdate.'</time>';
	$html .= '</div></div></div></div>';
	 echo  $html;
	 
 }
}
else
{
	if(!empty($profile))
	 { 
     $defImg2 = $profile;
    }else{
	 $defImg2 = plugins_url( 'img/user2-default.png', __FILE__ );
	}
	$html .= ' <div class="user2 msg_container" title="'.$profile_name.'">';
	$html .= '<div class="msg_recieve">';
	$html .= '<div class="msg_avatar"><img src="'.$defImg2.'" ></div>';
	$html .= '<div class="msg_txt_outer">';
	$html .= '<div class="msg_txt">';
	$html .= '<p>'.$opt['text_to_display'].'</p>';
	$html .= '<time>'.$msgdate.'</time>';
	$html .= '</div></div></div></div>';
	 echo  $html;
}
wp_die(); ?>