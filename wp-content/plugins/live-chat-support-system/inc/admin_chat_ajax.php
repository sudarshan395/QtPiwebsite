<?php  if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
 global $wpdb; // this is how you get access to the database
$msg = str_replace('\\','',$_POST['msg']);
if(sanitize_text_field($_POST['agent']) != '') {
$adminChatId = 	sanitize_text_field($_POST['agent']);
}else {
	$adminChatId = $this->chatid;
}
$tempUid = sanitize_text_field($_POST['tempUserId']);
if(!empty($tempUid)){
	$user_two = $adminChatId;
	$conver = $wpdb->get_row("SELECT * FROM ".$this->tbl_conversation." WHERE (user_one='".$tempUid."' AND user_two='".$user_two."') OR (user_one='".$user_two."' AND user_two='".$tempUid."')");					
	if(count($conver) == 1){
		$conversation_id = $conver->id;
	} else { 
	  $q = $wpdb->insert($this->tbl_conversation,array('user_one' =>$tempUid ,'user_two' => $user_two,'conv_date' =>  date("d-m-Y h:i:s a")));							
	  $conversation_id = $wpdb->insert_id;	
	} 
	if(!empty($conversation_id)){
		$saveChat = $wpdb->insert($this->tbl_messages, array('conv_id' => $conversation_id, 'user_from' => $adminChatId, 'user_to' => $tempUid, 'message' => $msg, 'attachment' => '', 'msgdate' => date("d-m-Y h:i:s a")));
		if($saveChat){
			echo 'Sent.';
		} else	{
			echo 'Not Sent.';
		}
	}	
} else {
	 _e("Message not sent. Please Refresh Page and try again.", supportsystem);
	
}
wp_die();
?>			