<?php
/*
Plugin Name: Live Chat
Plugin URI: https://wordpress.org/plugins/live-chat
Description: This Plugin Will provide Support to our clients.
Author: mndpsingh287
Version: 1.2
Author URI: https://profiles.wordpress.org/mndpsingh287
Text Domain: Live-Chat
*/
if(!class_exists('supportsystembymysense'))
{
	class supportsystembymysense
	{
		const VER = '1.2';
		var $chatid; 
		var $tbl_conversation;
		var $tbl_messages;
		var $tbl_offlinechat;
		var $tbl_agents;
		/*
		* Construct Function
		* Enqueue Styles and Js
		* WP Footer Hook to display chat system on front end
		* Install Tables Activation Hook
		* Static or Our defined Chat ID, Don't Change IT Please 
		* Table assignments to Variables
		*/
		public function __construct()
		{
		  global $wpdb;
		  add_action( 'wp_enqueue_scripts', array(&$this,'lvcht_supportsystem_scripts'));
		  add_action('wp_footer', array(&$this,'lvcht_chat_data'));
		  register_activation_hook(__FILE__, array(&$this, 'lvcht_supportsystem_tbl_install'));
		  add_action( 'admin_menu', array(&$this,'lvcht_supportsystem_menu_page'));
		  add_action('wp_ajax_send_message', array(&$this, 'lvcht_send_message_callback'));
		  add_action('wp_ajax_nopriv_send_message', array(&$this, 'lvcht_send_message_callback'));
		  add_action('wp_ajax_send_attachment', array(&$this, 'lvcht_send_attachment_callback'));
		  add_action('wp_ajax_nopriv_send_attachment', array(&$this, 'lvcht_send_attachment_callback'));
		  add_action('wp_ajax_load_message', array(&$this, 'lvcht_load_message_callback'));
		  add_action('wp_ajax_nopriv_load_message', array(&$this, 'lvcht_load_message_callback'));
		  add_action('wp_ajax_load_messages', array(&$this, 'lvcht_load_messages_callback'));
		  add_action('wp_ajax_nopriv_load_messages', array(&$this, 'lvcht_load_messages_callback'));
		  add_action('wp_ajax_load_admin_messages', array(&$this, 'lvcht_load_admin_messages_callback'));
		  add_action('wp_ajax_nopriv_load_admin_messages', array(&$this, 'lvcht_load_admin_messages_callback'));		  
		  add_action('wp_ajax_admin_send_message', array(&$this, 'lvcht_admin_send_message_callback'));
		  add_action('wp_ajax_nopriv_admin_send_message', array(&$this, 'lvcht_admin_send_message_callback'));
		  add_action('wp_ajax_admin_load_message', array(&$this, 'lvcht_admin_load_message_callback'));
		  add_action('wp_ajax_nopriv_admin_load_message', array(&$this, 'lvcht_admin_load_message_callback'));
		  add_action('wp_ajax_admin_dashboard', array(&$this, 'lvcht_admin_dashboard_callback'));
		  add_action('wp_ajax_nopriv_admin_dashboard', array(&$this, 'lvcht_admin_dashboard_callback'));
		  add_action('wp_ajax_set_chat_close', array(&$this, 'lvcht_set_chat_close_callback'));
		  add_action('wp_ajax_nopriv_set_chat_close', array(&$this, 'lvcht_set_chat_close_callback'));
		  add_action('wp_ajax_send_offline_message', array(&$this, 'lvcht_send_offline_message_callback'));
		  add_action('wp_ajax_nopriv_send_offline_message', array(&$this, 'lvcht_send_offline_message_callback'));
		  add_action('admin_enqueue_scripts', array(&$this, 'lvcht_load_admin_things'));
		 add_action( 'admin_init', array(&$this,'lvcht_scripts_style'));
		  $this->chatid = '74528693';
		  $this->tbl_conversation = $wpdb->prefix.'tbl_conversation';
		  $this->tbl_messages = $wpdb->prefix.'tbl_messages';
		  $this->tbl_offlinechat = $wpdb->prefix.'tbl_offlinechat';
		  $this->tbl_agents = $wpdb->prefix.'tbl_agents';
		 date_default_timezone_set(get_option('timezone_string'));
			}
		/*
		* lvcht load admin things
		*/
   public function lvcht_load_admin_things() {
    wp_enqueue_script('media-upload');
    wp_enqueue_script('thickbox');
    wp_enqueue_style('thickbox');
   } 
   /*--for admin-- */
   public function lvcht_scripts_style()
		{
			 $page = sanitize_text_field($_GET['page']);
			 $allowed_pages = array('support_page','offline_messages','support_settings','support_chat','agent_dashboard');
			 if(isset($page) && in_array($page,$allowed_pages)){
			 wp_enqueue_style("bootstrap.min.css",plugins_url("/inc/css/bootstrap.min.css",__FILE__));	
			 wp_enqueue_script("bootstrap.min.js",plugins_url("/inc/js/bootstrap.min.js",__FILE__));
			 }			 
			wp_enqueue_style('supportsystem.css',plugins_url("/inc/css/supportsystem.css",__FILE__));
			wp_enqueue_style('font-awesome.min.css',plugins_url("/inc/css/font-awesome.min.css",__FILE__));
  			wp_enqueue_style("dataTables.bootstrap.min.css",plugins_url("/inc/css/dataTables.bootstrap.min.css",__FILE__));
			wp_enqueue_script("jquery.dataTables.min.js",plugins_url("/inc/js/jquery.dataTables.min.js",__FILE__));
			wp_enqueue_script("dataTables.bootstrap.min.js",plugins_url("/inc/js/dataTables.bootstrap.min.js",__FILE__));
			
		}
   public function lvcht_supportsystem_tbl_install() {
   global $wpdb;
	$result = add_role(
		 'agent',
		 __( 'Agent' ),
		array(
          'read'         => true,  // true allows this capability
		  'manage_options' => false,
          'delete_posts' => false, // Use false to explicitly deny
         )
		);
		/*conversation Table*/	   
		 if($wpdb->get_var("show tables like ".$this->tbl_conversation."") != $this->tbl_conversation) 	{
				$sql = "CREATE TABLE " . $this->tbl_conversation . " (
				`id` mediumint(9) NOT NULL AUTO_INCREMENT,
				`user_one` varchar(255) NULL,
				`user_two` varchar(255) NULL,
				`ip_address` varchar(255) NULL,
				`browser` varchar(255) NULL,
				`conv_date` varchar(255) NULL,
				`chat_option`mediumint(9) DEFAULT 0,
				 UNIQUE KEY id (id)
				);";		 
				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
				dbDelta($sql);
			}
			/*message Table*/
		   if($wpdb->get_var("show tables like ".$this->tbl_messages."") != $this->tbl_messages) {
				$sql = "CREATE TABLE " . $this->tbl_messages . " (
				`mid` mediumint(9) NOT NULL AUTO_INCREMENT,
				`conv_id` int(11) NULL,
				`user_from` varchar(255) NULL,
				`user_to` varchar(255) NULL,
				`message` varchar(500) NULL,
				`attachment` varchar(500) NULL,
				`msgdate` varchar(255) NULL,
				 UNIQUE KEY mid (mid)
				);";		 
				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
				dbDelta($sql);
			}
			/*--- offline table-----*/
			if($wpdb->get_var("show tables like ".$this->tbl_offlinechat."") != $this->tbl_offlinechat) {
				$sql = "CREATE TABLE " . $this->tbl_offlinechat . " (
				`id` mediumint(9) NOT NULL AUTO_INCREMENT,
				`user_name` varchar(255) NULL,
				`user_email` varchar(255) NULL,
				`ip_address` varchar(255) NULL,
				`browser` varchar(255) NULL,
				`conv_date` varchar(255) NULL,
				`message` varchar(255) NULL,
				 UNIQUE KEY id (id)
				);";		 
				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
				dbDelta($sql);
			}			
			/*---agents table---- */			
			if($wpdb->get_var("show tables like ".$this->tbl_agents."") != $this->tbl_agents) {
				$sql = "CREATE TABLE " . $this->tbl_agents . " (
				`id` mediumint(9) NOT NULL AUTO_INCREMENT,
				`agent_name` varchar(255) NULL,
				`agent_id` varchar(255) NULL,
				`agent_email` varchar(255) NULL,
				`agent_password` varchar(255) NULL,
				`agent_image` varchar(255) NULL,
				`is_busy` varchar(255) NULL,
				`user_id` varchar(255) NULL,
				 UNIQUE KEY id (id)
				);";		 
				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
				dbDelta($sql);
			}
			/*--plugin Defaults ---*/
           $defaultsettings = array(
			     'enable_chat' => 'yes',
	             'input_replace' => 'Type Your Message...',
				 'ip_address' => 'yes',
				 'sound_play' => 'yes',
				 'sound_to_play' => 'noti.mp3',
			     'emoticons' => 'yes',
				 'attachments' => 'yes',
				 'chatbox_alignment' => 'bottom_right',
				 'name_to_display' => 'Admin',
				 'text_to_display' => 'Any Query ? Please send Us Message',
				 'select_colour_theme' => 'theme_default',
			     'color_scheme' => 'clr_shade_default',
				 'offline_chat' => 'no',
				 'change_color_scheme' =>'no'
			 );
	        $opt = get_option('support_page_options');
			if(!$opt['enable_chat']) {
				update_option('support_page_options', $defaultsettings);
			} 			
		}
		/*
		* Enqueue The Styles and JS for front end
		*/
		public function lvcht_supportsystem_scripts()	{ 
		    wp_enqueue_style( 'supportsystem_font', plugins_url( '/inc/css/font-awesome.min.css', __FILE__ ));
			wp_enqueue_script('supportsystem.js',plugins_url('/inc/js/supportsystem.js',__FILE__));
			if(!is_admin()) {  
			global $wpdb;
			$opt = get_option('support_page_options'); 
			if(!empty($opt['select_colour_theme'])){
				if($opt['select_colour_theme'] == 'default') {
						wp_enqueue_style( 'supportsystem', plugins_url( '/inc/css/default.css', __FILE__ ));
				} else {						
						wp_enqueue_style( 'supportsystem', plugins_url( '/inc/css/default.css', __FILE__ ));
						wp_enqueue_style( 'child-style', plugins_url( '/inc/themes/'.$opt['select_colour_theme'].'.css', __FILE__ ), array( 'supportsystem' )	);
			    }	
			} else {
					wp_enqueue_style( 'supportsystem', plugins_url( '/inc/css/default.css', __FILE__ ));
			}			   
		   }
		}
		/*
		* admin menu
		*/
		public function lvcht_send_attachment_callback(){    
			if ( FALSE === get_post_status( (int) $_POST['post_id'] ) ) {
		$parent_post_id = 0;
		} else {
		$parent_post_id = $_POST['post_id']; 
		}	
			$valid_formats = array("jpg", "png", "gif", "bmp", "jpeg","txt");
			$max_image_upload = 1000; 
			$wp_upload_dir = wp_upload_dir();
			$path = $wp_upload_dir['path'] . '/';
			$count = 0;
      $attachments = get_posts( array(
        'post_type'         => 'attachment',
        'posts_per_page'    => -1,
        'post_parent'       => $parent_post_id,
        'exclude'           => get_post_thumbnail_id() 
      ) );
      if( $_SERVER['REQUEST_METHOD'] == "POST" ){
        if( ( count( $attachments ) + count( $_FILES['files']['name'] ) ) > $max_image_upload ) {
            $upload_message[] = "Sorry you can only upload " . $max_image_upload . " images for each Ad";
        } else {
            foreach ( $_FILES['files']['name'] as $f => $name ) {
                $extension = pathinfo( $name, PATHINFO_EXTENSION );
                $new_filename = $this->lvcht_generate_random_code( 20 )  . '.' . $extension;
               if ( $_FILES['files']['error'][$f] == 4 ) {
                    continue; 
                }                
               if ( $_FILES['files']['error'][$f] == 0 ) {
                   if( ! in_array( strtolower( $extension ), $valid_formats ) ){
                        $upload_message[] = "$name is not a valid format";
                        continue; 
                    
                    } else{
                     if( move_uploaded_file( $_FILES["files"]["tmp_name"][$f], $path.$new_filename ) ) {
                      $count++; 
                      $filename = $path.$new_filename;
                      $filetype = wp_check_filetype( basename( $filename ), null );
                      $wp_upload_dir = wp_upload_dir();
                      $attachment = array(
                                'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ), 
                                'post_mime_type' => $filetype['type'],
                                'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
                                'post_content'   => '',
                                'post_status'    => 'inherit'
                            );
                     $attach_id = wp_insert_attachment( $attachment, $filename, $parent_post_id );
                     require_once( ABSPATH . 'wp-admin/includes/image.php' );                           
                     $attach_data = wp_generate_attachment_metadata( $attach_id, $filename ); 
                     wp_update_attachment_metadata( $attach_id, $attach_data );
                            
                        }
                    }
                }
            }
        }
    }
    /* Loop through each error then output it to the screen*/
    if ( isset( $upload_message ) ) :
        foreach ( $upload_message as $msg ){        
            echo '<p class="bg-danger">'.$msg.'</p>';
        }
    endif;    
    /* If no error, show success message*/
    if( $count != 0 ){       
	   echo $filenames =  strstr($filename , 'wp-content');
    }    
    exit();
}

/* Random code generator used for file names*/
public function lvcht_generate_random_code($length=10) { 
   $string = '';
   $characters = "23456789ABCDEFHJKLMNPRTVWXYZabcdefghijklmnopqrstuvwxyz"; 
   for ($p = 0; $p < $length; $p++) {
       $string .= $characters[mt_rand(0, strlen($characters)-1)];
   } 
   return $string; 
}	
		
public function lvcht_supportsystem_menu_page(){
	   global $wpdb,$current_user,$user_role_permission;
   $cm_role = $wpdb->prefix . "capabilities";
   $current_user->role = array_keys($current_user->$cm_role);
   $cm_role = $current_user->role[0];
   $allowedRoles = array('administrator' => 'manage_options', 'agent' => 'read');
   if(array_key_exists($cm_role, $allowedRoles))
   {
     $user_role_permission = $allowedRoles[$cm_role];  
   }
 add_menu_page(
          __( 'Support', 'supportsystem' ),
			'Support Live chat',
			$user_role_permission,
			'support_page',			
			array(&$this, 'lvcht_support_page_fxn'),
			'',
	        '30.1',
			plugins_url("inc/img/logo.png",__FILE__)
           );
		    //sub menu
		   add_submenu_page(
			'', //no parent slug, leave this independent
			'',
			'',
			$user_role_permission,
			'support_chat',
			array(&$this, 'lvcht_support_chat_fxn')
		  );
		  add_submenu_page(
			'support_page', //no parent slug, leave this independent
			'Offline Messages',
			'Offline Messages',
			$user_role_permission,
			'offline_messages',
			array(&$this, 'lvcht_offline_messages_fxn')
		  );
		  if($cm_role == 'administrator'){
		 add_submenu_page(
			'support_page', //no parent slug, leave this independent
			'Settings',
			'Settings',
			$user_role_permission,
			'support_settings',
			array(&$this, 'lvcht_setting_chat_fxn')
       );}
		 
		  		}
		/*
		* Sub menu
		*/
		public function lvcht_support_page_fxn()
		{
		 require_once('inc/admin_dashboard.php');	
		}
		public function lvcht_setting_chat_fxn()
		{
		 require_once('inc/setting_dashboard.php');	
		}
		
		public function lvcht_offline_messages_fxn()
		{
		 require_once('inc/offline_messages.php');	
		}
			
		/*
		* support chat function
		*/	
		public function lvcht_support_chat_fxn()
		{
		  require_once('inc/admin_support_chat.php');
		}
		/*
		* This will display at the front end of your site
		*/
		public function lvcht_chat_data()
		{  		
		  require_once('inc/footer_chat_data.php');
		}
		/*
		* chat ajax
		*/
		public function lvcht_send_message_callback()
		{
		   require_once('inc/chat_ajax.php');		
		}
		/*---- pro feature---- */
		 		/*---- pro feature end---- */
		/*Load chat */
		public function lvcht_load_message_callback()
		{
		  require_once('inc/load_ajax_chat.php');
		}
		public function lvcht_load_messages_callback()
		{
		  require_once('inc/load_msges.php');
		}
		public function lvcht_load_admin_messages_callback()
		{
		  require_once('inc/load_admin_msges.php');
		}		
		public function lvcht_admin_dashboard_callback()
		{
		  require_once('inc/admin_dashboard_callback.php');
		}
				
		public function lvcht_send_offline_message_callback()
		{
		  require_once('inc/send_offline_message.php');
		}
			    /*
		* chat ajax admin
		*/
		public function lvcht_admin_send_message_callback()
		{
		   require_once('inc/admin_chat_ajax.php');		
		}
		/*Load chat admin */
		public function lvcht_admin_load_message_callback()
		{
		  require_once('inc/admin_load_ajax_chat.php');
		}
		public function lvcht_set_chat_close_callback()
		{
		  require_once('inc/set_chat_close_callback.php');
		}
		static function lvcht_redirect($url)
		{
			echo '<script>window.location.href="'.$url.'"</script>';
		}
}
	/*
	* Instance
	*/
	new supportsystembymysense;
}
function lvcht_login_redirect( $redirect_to, $request, $user ) {
	//is there a user to check?
	if ( isset( $user->roles ) && is_array( $user->roles ) ) {
		//check for admins
		if ( in_array( 'agent', $user->roles ) ) {
			// redirect them to the default place
			return admin_url('/admin.php?page=agent_dashboard');
		} else {
			return admin_url();
		}
	} else {
		return $redirect_to;
	}
}

add_filter( 'login_redirect', 'lvcht_login_redirect', 10, 3 );
if(!function_exists('getThemes')){
function getThemes()
  {
  $dir = dirname(__FILE__).'/inc/themes/';  
  $theme_files = glob($dir."/*.css");
  $chatthemes = array();
  foreach($theme_files as $theme_file){
   $chatthemes[basename($theme_file,".css")]=basename($theme_file,".css");
  }
  return $chatthemes;
  }
}
if(!function_exists('getBrowser')){
function getBrowser() 
{ 
    $u_agent = $_SERVER['HTTP_USER_AGENT']; 
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";

    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }

    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
    { 
        $bname = 'Internet Explorer'; 
        $ub = "MSIE"; 
    } 
    elseif(preg_match('/Firefox/i',$u_agent)) 
    { 
        $bname = 'Mozilla Firefox'; 
        $ub = "Firefox"; 
    } 
    elseif(preg_match('/Chrome/i',$u_agent)) 
    { 
        $bname = 'Google Chrome'; 
        $ub = "Chrome"; 
    } 
    elseif(preg_match('/Safari/i',$u_agent)) 
    { 
        $bname = 'Apple Safari'; 
        $ub = "Safari"; 
    } 
    elseif(preg_match('/Opera/i',$u_agent)) 
    { 
        $bname = 'Opera'; 
        $ub = "Opera"; 
    } 
    elseif(preg_match('/Netscape/i',$u_agent)) 
    { 
        $bname = 'Netscape'; 
        $ub = "Netscape"; 
    } 

    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }

    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $version= $matches['version'][0];
        }
        else {
            $version= $matches['version'][1];
        }
    }
    else {
        $version= $matches['version'][0];
    }

    // check if we have a number
    if ($version==null || $version=="") {$version="?";}

    return array(
        'userAgent' => $u_agent,
        'name'      => $bname,
        'version'   => $version,
        'platform'  => $platform,
        'pattern'   => $pattern
    );
}
}