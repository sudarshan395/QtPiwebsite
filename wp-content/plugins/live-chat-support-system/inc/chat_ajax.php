<?php  if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb; // this is how you get access to the database
$msg = str_replace('\\','',$_POST['msg']); //post message
$tempUid = sanitize_text_field($_POST['tempUserId']); //user ID
$admin_id = $wpdb->get_row("SELECT * FROM ".$this->tbl_conversation." WHERE user_one='".$tempUid."' ");
$chat_initiator = $admin_id->user_two;
if(!empty($chat_initiator))
{
$adminChatId =$chat_initiator;	
}else{
	$adminChatId = $this->chatid;
}
 //predefined	
$ip_address = sanitize_text_field($_POST['ip_add']);
$browser = sanitize_text_field($_POST['browser']);
if(!empty($tempUid)){
					$user_two = $adminChatId;
						$conver = $wpdb->get_row("SELECT * FROM ".$this->tbl_conversation." WHERE (user_one='".$tempUid."' AND user_two='".$user_two."') OR (user_one='".$user_two."' AND user_two='".$tempUid."')");					
						if(count($conver) == 1){
							$conversation_id = $conver->id;
						}else{ 
							$q = $wpdb->insert($this->tbl_conversation,array('user_one' =>$tempUid ,'user_two' => $user_two,'conv_date' =>  date("d-m-Y h:i:s a"),'ip_address'=>$ip_address,'browser'=>$browser));							
							$conversation_id = $wpdb->insert_id;
						}
					if(!empty($conversation_id))
					{
						$saveChat = $wpdb->insert($this->tbl_messages, array('conv_id' => $conversation_id, 'user_from' => $tempUid, 'user_to' => $user_two, 'message' => $msg, 'attachment' => '', 'msgdate' => date("d-m-Y h:i:s a")));
						if($saveChat)
						{
							echo 'Sent.';
							//echo notifyMe();
						}
						else
						{
							echo 'Not Sent.';
						}
					}	
}
else
{
	//echo 'Message not sent. Please Refresh Page and try again.';
	 _e("Currently No Message Found.", supportsystem); 
}

wp_die();
?>			
		