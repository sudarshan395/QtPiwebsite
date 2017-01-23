
<?php   if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
$opt = get_option('support_page_options');
include('lvcht_admin_setting.php');
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
?>
<div class="wrap">
  <h2><?php _e('Support System Settings', 'supportsystem')?></h2>
  <?php
$supportpageoptions = array();
if(isset($_POST['chat_setting_submit']) && wp_verify_nonce($_POST['supportpage_nonce_field'], 'supportpage_action' )){
	_e("<strong>Saving Please wait...</strong>", 'supportsystem');
	$needToUnset = array('chat_setting_submit');//no need to save in Database
	foreach($needToUnset as $noneed):
	  unset($_POST[$noneed]);
	endforeach;
	//print_r($_POST);
	foreach($_POST as $key => $val):
		$supportpageoptions[$key] = $val;		
		endforeach;
		  $saveSettings = update_option('support_page_options', $supportpageoptions );
		if($saveSettings)
		{
			supportsystembymysense::lvcht_redirect('admin.php?page=support_settings&msg=1');
		}
		else
		{
			supportsystembymysense::lvcht_redirect('admin.php?page=support_settings&msg=2');
		} 
}
if ( is_user_logged_in() ) {
	$user = new WP_User( $user_ID );
	if ( !empty( $user->roles ) && is_array( $user->roles ) ) {
		foreach ( $user->roles as $role )
			echo $role;
	}
}
?>
<?php if($msg == 1) {?>
<div class="updated settings-error notice is-dismissible" id="setting-error-settings_updated"> 
<p><strong><?php _e('Settings saved.', 'supportsystem')?></strong></p><button class="notice-dismiss" type="button"><span class="screen-reader-text"><?php _e('Dismiss this notice.', 'supportsystem')?></span></button></div>
<?php } else if($msg == 2) {?>
<div class="error settings-error notice is-dismissible" id="setting-error-settings_updated"> 
<p><strong><?php _e('Settings not saved.', 'supportsystem')?></strong></p><button class="notice-dismiss" type="button"><span class="screen-reader-text"><?php _e('Dismiss this notice.', 'supportsystem')?></span></button></div>
<?php }?>
  <div class="wrap-setting">
  <ul class="nav nav-tabs tabs_sttng">
    <li class="active" id="nav_id"><a data-toggle="tab"  href="#mainsetting"><i class="fa fa-cog" aria-hidden="true"></i><?php _e('General', 'supportsystem')?></a></li>
    <li id="nav_id"><a data-toggle="tab"  href="#Chatbox"><i class="fa fa-envelope" aria-hidden="true"></i><?php _e('Chat Box', 'supportsystem')?></a></li>
    <li id="nav_id"><a data-toggle="tab"  href="#styling"><i class="fa fa-paint-brush" aria-hidden="true"></i><?php _e('Styling', 'supportsystem')?></a></li>
	<li id="nav_id"><a data-toggle="tab"  href="#offline_chat"><i class="fa fa-commenting-o" aria-hidden="true"></i><?php _e('Offline Chat', 'supportsystem')?></a></li>
	<li id="nav_id"><a data-toggle="tab"  href="#agents"><i class="fa fa fa-users" aria-hidden="true"></i><?php _e('Agents', 'supportsystem')?></a></li>
     </ul>
 <?php $list_of_sounds = array('noti.mp3','noti2.mp3','noti3.mp3','noti4.mp3'); ?>
<form action="" method="post" name="support_chat_setting_form">
<?php  wp_nonce_field( 'supportpage_action', 'supportpage_nonce_field' ); ?>
  <div class="tab-content">
    <div id="mainsetting" class="tab-pane fade in active">
      <h3><?php _e('Main Setting', 'supportsystem')?></h3>
      <table class="form-table">
       <tbody>
	   <tr>
<th scope="row"><label for="enable_chat"><?php _e('Enable chat', 'supportsystem')?></label> <a href="#" data-toggle="tooltip" title="if enable chatbox will appear on your site"><i class="fa fa-question-circle-o" aria-hidden="true"></i></a></th>
<td><select id="enable_chat" name="enable_chat">
	 <option value="yes" <?php echo($opt['enable_chat'] == 'yes' ) ? "selected = 'selected'" : ""; ?>>Yes</option>
	 <option value="no" <?php echo($opt['enable_chat'] == 'no' ) ? "selected = 'selected'" : ""; ?>>No</option>
    </select>
   </td>
</tr>    

	<tr>
		<th scope="row"><label for="input_replace"><?php _e('Input Field Replacement Text:', 'supportsystem')?></label><a href="#" data-toggle="tooltip" title="text will appear in input box"><i class="fa fa-question-circle-o" aria-hidden="true"></i></a></th>
		<td><textarea name="input_replace" rows="5" cols="35" id="input_replace" ><?php if(isset($opt['input_replace'])){ echo $opt['input_replace']; }else{} ?></textarea></td>
    </tr>
	<tr>
		<th scope="row"><label for="ip_add"><?php _e("Record a visitor's IP Address:", 'supportsystem')?></label><a href="#" data-toggle="tooltip" title="ip address of user will saved for records"><i class="fa fa-question-circle-o" aria-hidden="true"></i></a></th>
		<td><input type="checkbox" name="ip_address" id="ip_add" value="yes" <?php echo($opt['ip_address'] == 'yes' ) ? "checked = 'checked'" : "";?>></td>
    </tr>
	<tr>
		<th scope="row"><label for="sound_play"><?php _e("Notification sound:", 'supportsystem')?></label><a href="#" data-toggle="tooltip" title="Play a sound when a new message is received"><i class="fa fa-bell" aria-hidden="true"></i></a></th>
		<td><input type="checkbox" name="sound_play" id="sound_play" value="yes" <?php echo($opt['sound_play'] == 'yes' ) ? "checked = 'checked'" : "";?>></td>
    </tr>
	<tr>
		<th scope="row"><label for="sound_to_play"><?php _e("Notification Tone :", 'supportsystem')?></label><a href="#" data-toggle="tooltip" title="Select a sound when a new message is received"><i class="fa fa-bell" aria-hidden="true"></i></a></th>
		<td><select id="sound_to_play" name="sound_to_play">
	 <?php foreach($list_of_sounds as $list_of_sound):
	$optionDisplayVal = ucfirst($list_of_sound); ?>
	<option value="<?php echo $list_of_sound; ?>" <?php echo($opt['sound_to_play'] == $list_of_sound ) ? "selected = 'selected'" : ""; ?>><?php _e($optionDisplayVal, 'supportsystem')?></option>
    <?php endforeach; ?>
    </select></td>
    </tr>
	<tr>
		<th scope="row"><label for="emoticons"><?php _e("Add Emoticons:", 'supportsystem')?></label><a href="#" data-toggle="tooltip" title="Add emoticons option to the chatbox"><i class="fa fa-smile-o" aria-hidden="true"></i></a></th>
		<td><input type="checkbox" name="emoticons" id="emoticons" value="yes" <?php echo($opt['emoticons'] == 'yes' ) ? "checked = 'checked'" : "";?>></td>
    </tr>
	<tr>
		<th scope="row"><label for="attachments"><?php _e("Add Attachments:", 'supportsystem')?></label><a href="#" data-toggle="tooltip" title="Add attachments option to the chatbox"><i class="fa fa-file-image-o" aria-hidden="true"></i></a></th>
		<td><input type="checkbox" name="attachments" id="attachments" value="yes" <?php echo($opt['attachments'] == 'yes' ) ? "checked = 'checked'" : "";?>></td>
    </tr>
	   </tbody>
	   </table>
    </div>
    <div id="Chatbox" class="tab-pane fade">
	 <table class="form-table">
       <tbody>
      <h3><?php _e("Chatbox Window Settings", 'supportsystem')?></h3>
	  <tr>
		<th scope="row"><label for="chatbox_alignment"><?php _e("Chatbox Alignment:", 'supportsystem')?></label><a href="#" data-toggle="tooltip" title="set chatbox alignment to bottom left or bottom right"><i class="fa fa-question-circle-o" aria-hidden="true"></i></a></th>
      <td><select id="chatbox_alignment" name="chatbox_alignment">
	 <option value="bottom_right" <?php echo($opt['chatbox_alignment'] == "bottom_right" ) ? "selected = 'selected'" : ""; ?>>Bottom Right</option>
	 <option value="bottom_left" <?php echo($opt['chatbox_alignment'] == "bottom_left" ) ? "selected = 'selected'" : ""; ?>>Bottom Left</option>
    </select>
   </td>
   </tr>
   	<tr>
		<th scope="row"><label for="text_to_display"><?php _e("Text to display:", 'supportsystem')?></label><a href="#" data-toggle="tooltip" title="chatbox text"><i class="fa fa-question-circle-o" aria-hidden="true"></i></a></th>
		<td><textarea  name="text_to_display" rows="5" cols="35" id="text_to_display" ><?php if(isset($opt['text_to_display'])){ echo $opt['text_to_display']; }else{} ?></textarea></td>
    </tr>
	</tbody>
	   </table>
    </div>
    <div id="styling" class="tab-pane fade">
	 <table class="form-table">
       <tbody>
      <h3><?php _e("Styling", 'supportsystem')?></h3>
	  <tr>
	  <th scope="row"><label for="select_colour_theme"><?php _e("Choose Theme:", 'supportsystem')?></label><i class="fa fa-comment" aria-hidden="true"></i></th>
	  <td>
	<?php  $chatthemes = getThemes();	?>
	<select id="select_colour_theme" name="select_colour_theme">
                        <?php if(!empty($chatthemes) && is_array($chatthemes)):
    foreach($chatthemes as $chattheme):?>
             <option value="<?php echo $chattheme; ?>" <?php echo($opt['select_colour_theme'] == $chattheme ) ? "selected = 'selected'" : ""; ?> ><?php echo ucwords(str_replace('_',' ',$chattheme)); ?></option>
             <?php endforeach; endif;?>
            </select>
	
	</td>
	  </tr>
	 
      <tr>
	   BUY our pro plugin to avail more themes <a href="http://www.webdesi9.com/product/system-support-chat-plugin/">Pro</a>
	  </tr>
		   </tbody>
	   </table>      
    </div>
  <div id="offline_chat" class="tab-pane fade">
   <h3><?php _e("Main Setting", 'supportsystem')?></h3>
   <table class="chat_opt"><tr>
   <td class="tx_b"><label for="offline_chat"><?php _e("Chat Offline:", 'supportsystem')?></label><a href="#" data-toggle="tooltip" title="get offline messages"><i class="fa fa-question-circle-o" aria-hidden="true"></i></a></td>
     <td class="ch_b"><input type="radio" name="offline_chat" id="offline_chat" value="yes" <?php echo($opt['offline_chat'] == 'yes' ) ? "checked = 'checked'" : "";?>></td>
     <td class="tx_b"><label for="away_chat"><?php _e("Chat Away:", 'supportsystem')?></label><i class="fa fa-question-circle-o" aria-hidden="true"></i></td>
     <td class="ch_b"><input type="radio" name="offline_chat" id="away_chat" value="no" <?php echo($opt['offline_chat'] == 'no' ) ? "checked = 'checked'" : "";?>></td>
     <td class="tx_b"><label for="online_chat"><?php _e("Chat Online:", 'supportsystem')?></label><i class="fa fa-question-circle-o" aria-hidden="true"></i></td>
     <td class="ch_b"><input type="radio" name="offline_chat" id="online_chat" value="online" <?php echo($opt['offline_chat'] == 'online' ) ? "checked = 'checked'" : "";?>></td>
   </tr>
   </table>
      <table class="form-table">
       <tbody>	   
	 <tr>
		<th scope="row"><label for="offline_chat_text1"><?php _e("Chat Offline Text1:", 'supportsystem')?></label><a href="#" data-toggle="tooltip" title="chatbox text before message send"><i class="fa fa-question-circle-o" aria-hidden="true"></i></a></th>
		<td><input type="text" name="offline_chat_text1" id="offline_chat_text1" ></td>
     </tr>
	 <tr>
		<th scope="row"><label for="offline_chat_text2"><?php _e("Chat Offline Text2:", 'supportsystem')?></label><a href="#" data-toggle="tooltip" title="chatbox text after message send"><i class="fa fa-question-circle-o" aria-hidden="true"></i></a></th>
		<td><input type="text" name="offline_chat_text2" id="offline_chat_text2" ></td>
     </tr>
	    </tbody>
	   </table>  
  </div>  
	<div id="agents" class="tab-pane fade">
   <h3><?php _e("Agents", 'supportsystem')?></h3>
  <div class="success"></div>
   BUY our pro plugin to avail this feature <a href="http://www.webdesi9.com/product/system-support-chat-plugin/">Pro</a>

   </div>
  </div>
  <input type="submit" name="chat_setting_submit" value="Save" class="btn btn-success">
  </form>
  </div>
</div>