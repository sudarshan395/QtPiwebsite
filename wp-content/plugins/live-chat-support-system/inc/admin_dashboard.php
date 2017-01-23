<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 
global $wpdb; 
 $opt = get_option('support_page_options'); 
$action = !empty($_GET['action']) ? $_GET['action'] : '';
$msg = !empty($_GET['msg']) ? $_GET['msg'] : '';
include('lvcht_admin_dashboard.php'); 
?>
<div class="wrap">
<h2><?php _e("Support", supportsystem); ?></h2>
<?php if($action == 'delete'){
$convId = sanitize_text_field($_GET['convid'] );	
if(!empty($convId))
{
	echo "Deleting Please Wait....";
	$deleteConv = $wpdb->delete($this->tbl_conversation, array('id' => $convId)); //deleting Conversation
	if($deleteConv):
	$deleteChat = $wpdb->delete($this->tbl_messages, array('conv_id' => $convId)); //deleting chat
	echo '<script>
	window.location.href = "?page=support_page&msg=1"; 
	</script>';
	else:
	echo '<script>
	window.location.href = "?page=support_page&msg=2"; 
	</script>';
	endif;
}
 }?>
<?php $allchats = $wpdb->get_results('SELECT * FROM '.$this->tbl_conversation.' order by id DESC'); 
if($msg == 1)
{
 _e('<div class="updated settings-error notice is-dismissible" id="setting-error-settings_updated"> 
<p><strong>Sucess: Conversation And Chat Deleted Successfully.</strong></p><button class="notice-dismiss" type="button"><span class="screen-reader-text">Dismiss this notice.</span></button></div>');
}
elseif($msg == 2)
{
 _e('<div class="updated settings-error error is-dismissible" id="setting-error-settings_updated"> 
<p><strong>Error: Conversation Not Deleted.</strong></p><button class="notice-dismiss" type="button"><span class="screen-reader-text">Dismiss this notice.</span></button></div>');	
}
?>
<div class="wrap-setting">
<table id="trackpage" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
               	<th><?php _e("Sr.", supportsystem); ?></th>
                <th><?php _e("User Temp ID", supportsystem);?></th>
                <th><?php _e("Conversation ID", supportsystem);?></th>
				<th><?php _e("Browser and IP", supportsystem);?></th>
                <th><?php _e("Conversation Date", supportsystem);?></th>
                <th><?php _e("Chat", supportsystem);?></th>
                <th></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
				<th><?php _e("Sr.", supportsystem); ?></th>
                <th><?php _e("User Temp ID", supportsystem);?></th>
                <th><?php _e("Conversation ID", supportsystem);?></th>
				<th><?php _e("Browser and IP", supportsystem);?></th>
                <th><?php _e("Conversation Date", supportsystem);?></th>
                <th><?php _e("Chat", supportsystem);?></th>
                <th></th>
            </tr>
        </tfoot>
        <tbody>
         <?php if(!empty($allchats) && count($allchats) != 0) {
			 $count = 1;
			 foreach($allchats as $allchat):
			 $currentDate = date('d-m-Y');
			 $convDate = strtotime($allchat->conv_date);
			 $getDate = date('d-m-Y',$convDate);
			 $getTime = date('h:i:s a',$convDate);
			 $class = '';
			 if($getDate == $currentDate)
			 {
				 $convNewDate = 'Today - '.$getTime;
				 $class = 'alert-success';
			 }
			 else
			 {
				 $convNewDate = $allchat->conv_date;
				 $class = 'alert-danger';
			 }
			 //getting somedetails
			 $getDetails = $wpdb->get_row('select * from '.$this->tbl_messages.' where conv_id = "'.$allchat->id.'" order by mid ASC');
			 ?>
           <tr>
           <td><?php  _e($count); ?></td> 
           <td><?php  _e($getDetails->user_from); ?></td> 
           <td><?php  _e($allchat->id); ?></td> 
		    <td><?php _e($allchat->ip_address); ?>,<?php _e( $allchat->browser); ?></td> 
           <td class="<?php _e( $class ); ?>"><?php _e($convNewDate); ?></td> 
           <td>
           <?php
		   if(!empty($getDetails->user_from)){ ?>
           <a href="admin.php?page=support_chat&convid=<?php _e( $allchat->id); ?>&tempuser=<?php _e($getDetails->user_from ) ;?>"><?php _e("Chat Now", supportsystem);?></a> <?php }?>
           </td> 
           <td><a href="admin.php?page=support_page&action=delete&convid=<?php _e($allchat->id); ?>" onClick="return confirm('<?php _e("Are you sure want to delete ?", supportsystem);?>')" title="Delete Conversation and chat."><?php _e("Delete", supportsystem);?></a></td>           
           </tr>
             <?php $count++; endforeach; } ?> 
        </tbody>
    </table>
	</div>
</div>