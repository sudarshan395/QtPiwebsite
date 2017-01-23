<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb; // this is how you get access to the database
$ip_address = sanitize_text_field($_POST['ip_add']);
$browser = sanitize_text_field($_POST['browser']);
$data = sanitize_text_field($_POST['offline_data']);
parse_str($data, $offline_data);
$q = $wpdb->insert($this->tbl_offlinechat,array('user_name' =>$offline_data['offln_name_user'] ,'user_email' => $offline_data['offln_email_user'],'conv_date' =>  date("d-m-Y h:i:s a"),'ip_address'=>$ip_address,'browser'=>$browser,'message'=>$offline_data['offln_msg_user']));	
if($q)
{
	 _e("Thankyou for Contacting. We will be back to you soon.", supportsystem); 
}else{
	_e("not done", supportsystem); 
}
wp_die();	
?>