<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;
$tempUid = sanitize_text_field($_POST['tempUserId']); //user ID
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
 $getMessages = $wpdb->get_results("select * from ".$this->tbl_messages." where conv_id = '".$conversation_id."' order by mid ASC");
 $html = '';
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
	 if($getDate == $currentDate)
	 {
		$msgdate = 'Today - '.$msgtime; 
	 }
	 else
	 {
		$msgdate = $getMsg->msgdate;  
	 }
	 $defImg1 = plugins_url( 'img/user1-default.png', __FILE__ );
	 $defImg2 = plugins_url( 'img/user2-default.png', __FILE__ );
   if($user1 == $user_two)
	 {
	 $html .= ' <li class="right clearfix adminmsg">
                                    <span class="chat-img pull-right">
                                        <img class="" alt="User Avatar" src="'.$defImg2.'" width="35">
                                    </span>
                                    <div class="chat-body clearfix">
                                        <div class="header">
                                            <small class=" text-muted">
                                                <i class="fa fa-clock-o fa-fw"></i> '.$msgdate.'</small>
                                            <strong class="pull-right primary-font">You&nbsp;</strong>
                                        </div>
                                        <p>
                                            '.$message.'
																				 
                                        </p>
                                    </div>
                                </li>';
	 }
	 else
	 {
	 $html .= '<li class="left clearfix clientmsg">
                                    <span class="chat-img pull-left">
                                        <img class="" alt="User Avatar" src="'.$defImg1.'" width="35">
                                    </span>
                                    <div class="chat-body clearfix">
                                        <div class="header">
                                            <strong class="primary-font"> '.$tempUid.' - Client </strong>
                                            <small class="text-muted">
                                                <i class="fa fa-clock-o fa-fw"></i> '.$msgdate.'
                                            </small>
                                        </div>
                                        <p>
                                            '.$message.'
										 </p>
                                    </div>
                                </li>';
	 }
	 $html .="<hr>";
	 endforeach;
	 echo $html;
 }
 else
 {
	 //echo 'Currently No Message Found.';
	 _e("Currently No Message Found.", supportsystem); 
 }
}
else
{
	 _e("Currently No Message Found.", supportsystem); 
}
wp_die(); ?>                    