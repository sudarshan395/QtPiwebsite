<?php  if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;
$tempUid = sanitize_text_field( $_POST['tempUserId'] ) ;//user ID
if(!empty($_POST['agent']))
{
$adminChatId = 	sanitize_text_field($_POST['agent']);
}else{
	$adminChatId = $this->chatid;
}
$user_two = $adminChatId;

$convId = $wpdb->get_row("SELECT * FROM ".$this->tbl_conversation." WHERE (user_one='".$tempUid."' AND user_two='".$user_two."') OR (user_one='".$user_two."' AND user_two='".$tempUid."')");
if(count($convId) == 1){
 $conversation_id = $convId->id; //conversion ID
 $getMessages = $wpdb->get_row("select * from ".$this->tbl_messages." where conv_id = '".$conversation_id."' order by mid DESC");

 if(!empty($getMessages))
 {
	 $user1 = $getMessages->user_from; //from
	 $user2 = $getMessages->user_to; //to
	 $message = $getMessages->message; //msg
	 $attachment = $getMessages->attachment; //attachment
	$msgdatee = strtotime($getMessages->msgdate);
	 $msgtime = date("h:i:s a", $msgdatee);
	 $currentDate = date('d-m-Y');
	 $getDate = date('d-m-Y', $msgdatee);
	 if($getDate == $currentDate)
	 {
		$msgdate = 'Today - '.$msgtime; 
	 }
	 else
	 {
		$msgdate = $getMessages->msgdate;  
	 }
	 $actual_time =  date("d-m-Y h:i:s");
	 $cmpr = strtotime($getMessages->msgdate)+1;
	 $cmprtime = date("d-m-Y h:i:s", $cmpr);
	 if(($actual_time) == ($cmprtime))
	 {
		 $actual_t = 1;
	 }else{
		$actual_t = 0; 
	 }
	 $arr_variable = array('mid'=>$getMessages->mid,'user1'=>$user1,'user2'=>$user2,'message'=>$message,'msg_date'=>$msgdate,'msgdatee'=>$cmprtime, 'act'=>$actual_t);
		echo json_encode($arr_variable);
}
}
wp_die(); ?>                    