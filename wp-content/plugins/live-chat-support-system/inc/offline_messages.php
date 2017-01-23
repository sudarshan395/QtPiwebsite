<?php
 if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;
?>
<script>
 jQuery(document).ready(function() {
    jQuery('#trackpage1').DataTable();
});
 </script>
<div class="wrap">
<h2><?php _e("Offline messages", supportsystem);?></h2>
<?php
$action = !empty($_GET['action']) ? $_GET['action'] : '';
$msg = !empty($_GET['msg']) ? $_GET['msg'] : '';
 if($action == 'delete'){
$convId = sanitize_text_field($_GET['convid']);	
if(!empty($convId))
{
	//echo "Deleting Please Wait....";
	_e("Deleting Please Wait....", supportsystem); 
	$deleteConv = $wpdb->delete($this->tbl_offlinechat, array('id' => $convId)); //deleting Conversation
	echo '<script>
	window.location.href = "?page=offline_messages&msg=1"; 
	</script>';	
	}else{
		echo '<script>
	window.location.href = "?page=offline_messages&msg=2"; 
	</script>';
	}
 }?>
<?php $allchats = $wpdb->get_results('SELECT * FROM '.$this->tbl_offlinechat.' order by id DESC'); 
if($msg == 1)
{
	echo '<div class="updated settings-error notice is-dismissible" id="setting-error-settings_updated"> 
<p><strong>Sucess: Conversation And Chat Deleted Successfully.</strong></p><button class="notice-dismiss" type="button"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
}
elseif($msg == 2)
{
echo '<div class="updated settings-error error is-dismissible" id="setting-error-settings_updated"> 
<p><strong>Error: Conversation Not Deleted.</strong></p><button class="notice-dismiss" type="button"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';	
}
?>
<div class="wrap-setting">
<table id="trackpage1" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th><?php _e("Sr.", supportsystem); ?></th>
                <th><?php _e("User Name", supportsystem); ?></th>
                <th><?php _e("Email", supportsystem); ?></th>
				<th><?php _e("Browser and IP", supportsystem); ?></th>               
                <th><?php _e("Message", supportsystem); ?></th>
				<th><?php _e("Date", supportsystem); ?></th>
                <th></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
               <th><?php _e("Sr.", supportsystem); ?></th>
                <th><?php _e("User Name", supportsystem); ?></th>
                <th><?php _e("Email", supportsystem); ?></th>
				<th><?php _e("Browser and IP", supportsystem); ?></th>               
                <th><?php _e("Message", supportsystem); ?></th>
				<th><?php _e("Date", supportsystem); ?></th>
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
						 ?>
           <tr>
           <td><?php echo $count; ?></td> 
           <td><?php echo $allchat->user_name; ?></td> 
           <td><a href="mailto:<?php echo $allchat->user_email; ?>"><?php echo $allchat->user_email; ?></a></td> 
		    <td><?php echo $allchat->ip_address; ?>,<?php echo $allchat->browser; ?></td> 
		   <td><?php echo $allchat->message; ?></td> 
           <td class="<?php echo $class; ?>"><?php echo $convNewDate; ?></td> 
            <td><a href="admin.php?page=offline_messages&action=delete&convid=<?php echo $allchat->id; ?>" onClick="return confirm('Are you sure want to delete ?')" title="Delete Conversation and chat."><?php_e("Delete", supportsystem); ?></a></td>           
           </tr>
             <?php $count++; endforeach; } ?> 
        </tbody>
    </table>
</div>
</div>