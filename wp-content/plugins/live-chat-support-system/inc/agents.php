<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb; // this is how you get access to the database
$agent_name = sanitize_text_field($_POST['agent_name']);
$agent_email = sanitize_text_field($_POST['agent_email']);
 $agent_pass = sanitize_text_field($_POST['agent_pass']);
 $agent_profile = sanitize_text_field($_POST['agent_profile']);

$user_id = username_exists( $agent_name );
if ( !$user_id and email_exists($agent_email) == false ) {
	$user_id = wp_create_user( $agent_name, $agent_pass, $agent_email );
	$user = new WP_User($user_id);
	$user->set_role('agent');
	$q = $wpdb->insert($this->tbl_agents,array('agent_name' =>$agent_name ,'agent_id'=>$user_id,'agent_email' => $agent_email,'agent_password' => $agent_pass,'agent_image' => $agent_profile,'is_busy'=>'0'));		
	
	//echo "Agent created successfully";
	_e("Agent created successfully", supportsystem); 
} else {
	//echo "Agent already exists";
	_e("Agent already exists", supportsystem); 
}
wp_die();
?>