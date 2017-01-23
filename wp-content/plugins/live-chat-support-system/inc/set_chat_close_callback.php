<?php 
 if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;
$tempUid = sanitize_text_field($_POST['tempUserId']);
$admin_ids = $wpdb->get_row("SELECT * FROM ".$this->tbl_conversation." WHERE user_one=".$tempUid." ");
$agents_id = $admin_ids->user_two ;
 $adminChatId = $agents_id;
if(!empty($tempUid))
{
   $user_two = $adminChatId;
   $update_chat = $wpdb->update($this->tbl_conversation, array('chat_option' => 1), array('user_one'=>$tempUid, 'user_two'=>$user_two)); 
	if($update_chat)
	{
		echo "success";
	}
	else
	{
		echo "failed";
	}  
}
wp_die();
?>