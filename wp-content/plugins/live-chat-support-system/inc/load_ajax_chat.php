<?php  if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;
$tempUid = sanitize_text_field($_POST['tempUserId']); //user ID
$admin_id = $wpdb->get_row("SELECT * FROM ".$this->tbl_conversation." WHERE user_one='".$tempUid."' ");
$chat_initiator = $admin_id->user_two;
if(!empty($chat_initiator))
{
$adminChatId =$chat_initiator;
$profile_id = $wpdb->get_row("SELECT * FROM ".$this->tbl_agents." WHERE agent_id ='".$adminChatId."' ");	
$profile = $profile_id->agent_image; 
}else{
	$adminChatId = $this->chatid;
}
$user_two = $adminChatId;
//date_default_timezone_set("Asia/Kolkata"); //IST
$convId = $wpdb->get_row("SELECT * FROM ".$this->tbl_conversation." WHERE (user_one='".$tempUid."' AND user_two='".$user_two."') OR (user_one='".$user_two."' AND user_two='".$tempUid."')");
if(count($convId) == 1){
$conversation_id = $convId->id; //conversion ID
$getMessages = $wpdb->get_row("select * from ".$this->tbl_messages." where conv_id = '".$conversation_id."' order by mid DESC");
    $user1 =   $getMessages->user_from; //from
	 $user2 =  $getMessages->user_to; //to
	 $message = $getMessages->message; //msg
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
	 /* if not replying in 2 minutes then refresh this */
     $start = date_create($getMessages->msgdate);
     $end = date_create($actual_time);
     $diff = date_diff($end,$start);
	 $getInthour = $diff->h;
     $getIntMinute = $diff->i;
	 $getIntsec = $diff->s;
	 $refreshHours = array();
	 $setHours = range(0,23);
	 $rereshAftemints = array();
	 $minutess = range(1,59, 2);
	 foreach($minutess as $minute_s)
	 {
		 $rereshAftemints[] = $minute_s;
	 }
	 foreach($setHours as $setHour)
	 {
		 $refreshHours[] = $setHour;
	 }
	 $setAftersec = '10';
	 if(in_array($getInthour, $refreshHours) && in_array($getIntMinute, $rereshAftemints) && $getIntsec == $setAftersec)
	 {
		$arr_variable = array('refreshpage' => 1);
	 }
	 else
	 {
	 /* endif; */
$arr_variable = array('mid'=>$getMessages->mid,'user1'=>$user1,'user2'=>$user2,'message'=>$message,'msg_date'=>$msgdate,'msgdatee'=>$cmprtime, 'act'=>$actual_t,'chat_initiator'=>$adminChatId,'image_suport'=>$profile,'chat_option'=>$convId->chat_option, 'refreshpage' => '');
}
		echo json_encode($arr_variable);
}
wp_die();
?>