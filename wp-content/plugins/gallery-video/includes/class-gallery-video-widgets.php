<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * Class Gallery_Video_Widgets
 */
class Gallery_Video_Widgets{

	/**
	 * Gallery_Video_Widgets constructor.
	 */
	public function __construct() {
		add_action( 'widgets_init', array($this,'register_widget'));
	}

	/**
	 * Register Huge-IT  Gallery Video Widget
	 */
	public function register_widget(){
		register_widget( 'Huge_it_video_gallery_Widget' );
	}
}

new Gallery_Video_Widgets();
