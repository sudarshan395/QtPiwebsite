<?php  if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;
$agent_id = sanitize_text_field($_POST['agent_id']);
$convo_id= sanitize_text_field($_POST['conv_id']);
$table=$this->tbl_conversation;
$table2=$this->tbl_messages;
$table3=$this->tbl_agents;
 $admin_ids = $wpdb->get_row("SELECT * FROM ".$table." WHERE ID=".$convo_id." ");
$agents_id = $admin_ids->user_two ;
$user_id = $admin_ids->user_one;
$chat_option = $admin_ids->chat_option;
if($chat_option == 2)
{
	if($agents_id == $agent_id)
	{
		_e("done", supportsystem);
	}else{
       	   _e("Client busy with another agent", supportsystem);
	}
}else if($chat_option == 1)
{
echo "Chat closed";
}else if($chat_option == ''){ 

if($agents_id == $agent_id)
{

	_e("done", supportsystem);
}  else{
$update = $wpdb->update($table, array( 'user_two' => $agent_id,'chat_option'=>2 ),  array( 'id' => $convo_id ));
if($update){
$update2 = $wpdb->update($table2, array( 'user_to' => $agent_id ),  array( 'conv_id' => $convo_id ));
 if($update2)
{
$update_agent = $wpdb->update($table3, array( 'is_busy' => 1 ,'user_id'=>$user_id),  array( 'agent_id' => $agent_id ));
if($update_agent)
{
	
_e("success", supportsystem);
}else{
	
_e("failed", supportsystem);
}	
} 
}
}
} 
wp_die();
?>