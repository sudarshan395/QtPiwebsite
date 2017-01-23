<?php  if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if(empty($_GET['convid']) && empty($_GET['tempuser'])) {?>
		<script>
window.location.href="<?php echo 'admin.php?page=support_page' ?>";
<?php die(); ?>
</script>
<?php }
global $wpdb;
if(!empty($_GET['agent_id']))
{
	$agent_id = sanitize_text_field($_GET['agent_id']);
}
 $opt = get_option('support_page_options'); 
$getConv = $wpdb->get_row("select * from ".$this->tbl_conversation." where id = '".sanitize_text_field($_GET['convid'])."'");
if(count($getConv) == 0)
{ ?>
<script>
window.location.href="<?php echo 'admin.php?page=support_page' ?>";
</script>	
<?php die(); ?>
<?php }
include('lvcht_admin_chat.php');  ?>
 <?php 
$admin_ids = $wpdb->get_row("SELECT * FROM ".$this->tbl_conversation." WHERE user_one=".sanitize_text_field($_GET['tempuser'])." ");
$user_two = $admin_ids->user_two ; 
$update_chat = $wpdb->update($this->tbl_conversation, array('chat_option' => 0), array('user_one'=> sanitize_text_field($_GET['tempuser']), 'user_two'=>$user_two));  ?>
<div id="page-wrapper" class="wrap">
            <div class="row">
                <div class="col-lg-12">
                    <h2>Support Chat System <a href="admin.php?page=support_page" class="add-new-h2">Back</a></h2>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="chat-panel panel panel-default">
                        <div class="panel-heading">
                           <div> <i class="fa fa-comments fa-fw"></i>
						   
                          <?php _e("Chat with", supportsystem);?><strong><?php echo 'Conversation ID: '.sanitize_text_field($_GET['convid'])?></strong>
														<i class="fa fa-times" aria-hidden="true"></i>
                           <button class="close_op"><?php _e("Close Chat", supportsystem);?></button>
							</div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <ul class="chat" id="loadchat"><?php _e("Loading Chat...", supportsystem);?></ul> 
                        </div>
                        <!-- /.panel-body -->
                        <div class="panel-footer">
                            <div class="input-group">
                            <span id="alertme"></span>
                                <input type="text" placeholder="Type your message here..." class="form-control input-sm" id="msg_area">
                                <span class="input-group-btn">
                                 <div class = "upload-form" id = "file_browse_wrapper">                       
               <input type = "file" id='file_browse1' name = "files" accept = "image/*" class = "files-data" />
                 <input type = "submit" value = "Upload" class = "btn btn-primary btn-upload" />
                 </div>
                                </span>
								<button id="emojis1">&#128512;</button>
<div id="emojis_tbl1" style="display:none; position:absolute; bottom:50px;">
<table class="table">
        <tr class="success">
        <td class="snd_smily1">&#128512;</td>
        <td class="snd_smily1">&#128513;</td>
		<td class="snd_smily1">&#128514;</td>
		<td class="snd_smily1">&#128515;</td>
        <td class="snd_smily1">&#128516;</td>
		<td class="snd_smily1">&#128527;</td>
		<td class="snd_smily1">&#128528;</td>
        <td class="snd_smily1">&#128529;</td>
      </tr>
      <tr class="danger">
        <td class="snd_smily1">&#128517;</td>
		<td class="snd_smily1">&#128518;</td>
        <td class="snd_smily1">&#128519;</td>
		<td class="snd_smily1">&#128520;</td>
        <td class="snd_smily1">&#128521;</td>
		<td class="snd_smily1">&#128530;</td>
		<td class="snd_smily1">&#128531;</td>
        <td class="snd_smily1">&#128532;</td>
      </tr>
      <tr class="warning">
        <td class="snd_smily1">&#128522;</td>
		<td class="snd_smily1">&#128523;</td>
		<td class="snd_smily1">&#128524;</td>
        <td class="snd_smily1">&#128525;</td>
        <td class="snd_smily1">&#128526;</td>
		<td class="snd_smily1">&#128533;</td>
		<td class="snd_smily1">&#128534;</td>
        <td class="snd_smily1">&#128535;</td>
      </tr>
      <tr class="info">
        <td class="snd_smily1">&#128536;</td>
		<td class="snd_smily1">&#128537;</td>
		<td class="snd_smily1">&#128538;</td>
        <td class="snd_smily1">&#128539;</td>
        <td class="snd_smily1">&#128540;</td>
		<td class="snd_smily1">&#128541;</td>
		<td class="snd_smily1">&#128542;</td>
        <td class="snd_smily1">&#128543;</td>
      </tr>
  </table>
</div>
                            </div>
                        </div>
                        <!-- /.panel-footer -->
                    </div>
                <!-- /.col-lg-4 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>