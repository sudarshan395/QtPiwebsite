<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class BONT_Front_End {
	
	public function wp_footer() {
		global $bontact_main_class;
		$token = $bontact_main_class->settings->get_option( 'token' );
		
		if ( empty( $token ) )
			return;
		
		?>
		<script>

       !function (a) {
           function b(a, b) {
               var c = document.createElement("script");
               c.type = "text/javascript", c.readyState ? c.onreadystatechange = function () {
                   "loaded" != c.readyState && "complete" != c.readyState || (c.onreadystatechange = null, b())
               } : c.onload = function () {b()
               }, c.src = a, document.getElementsByTagName("head")[0].appendChild(c)
           }
           function g() {d = c.Bontact, d.init(e)}
           var c = a, d = c.Bontact, e = "<?php echo $token; ?>", f = "//widget.bontact.com/widgetscript/bontact.widget.js";
           "function" == typeof d ? d.init(e) : b(f, g)
       }(window);
   </script>
			<?php
	}

	public function __construct() {		
		add_action( 'wp_footer', array( &$this, 'wp_footer' ), 80 );
	}
	
}