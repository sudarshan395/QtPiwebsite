<?php
 if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;
$allchats = $wpdb->get_row('SELECT * FROM '.$this->tbl_conversation.' order by id DESC');
          if(!empty($allchats) && count($allchats) != 0) {
			 $count = 1;
			 $currentDate = date('d-m-Y');
			 $convDate = strtotime($allchats->conv_date);
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
				 $convNewDate = $allchats->conv_date;
				 $class = 'alert-danger';
			 }
	  $actual_time =  date("d-m-Y h:i:s");
	 $cmpr = strtotime($allchats->conv_date)+1;
	 $cmprtime = date("d-m-Y h:i:s", $cmpr);
	 if(($actual_time) == ($cmprtime))
	 {
		 $actual_t = 1;
	 }else{
		$actual_t = 0; 
	 }
			 $getDetails = $wpdb->get_row('select * from '.$this->tbl_messages.' where conv_id = "'.$allchats->id.'" order by mid DESC');
			 $arr_variable = array('id'=>$allchats->id,'user_from'=>$getDetails->user_from,'allchat_id'=>$getDetails->mid,'class'=>$class,'browser'=>$allchats->browser,'ip'=>$allchats->ip_address,'convNewDate'=>$convNewDate,'act'=>$actual_t);
		echo json_encode($arr_variable);
		  }
		  wp_die();
?>