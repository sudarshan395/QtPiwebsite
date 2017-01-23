<?php   if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<script>
(function($) {
    $(function() {
        $.fn.priya = function(options) {
            var selector = $(this).selector;
            var defaults = {
                'preview' : '.preview-upload',
                'text'    : '.text-upload',
                'button'  : '.button-upload',
            };
            var options  = $.extend(defaults, options);

        	// When the Button is clicked...
            $(options.button).click(function() { 
                // Get the Text element.
                var text = $(this).siblings(options.text);
                // Show WP Media Uploader popup
                tb_show('Upload a image','media-upload.php?referer=wptuts&type=image&TB_iframe=true&post_id=0', false);
        		// Re-define the global function 'send_to_editor'
        		// Define where the new value will be sent to
                window.send_to_editor = function(html) {
					//alert(html);
                	// Get the URL of new image
                     var src = $('img', html).attr('src');
					// alert(src);
					 if(src == undefined)
					 {
					 var src = $(html).attr('href');
					 }
					 
					// Send this value to the Text field.
                    text.attr('value', src).trigger('change'); 
					$('.showimg').show();
                    tb_remove(); // Then close the popup window
                }
                return false;
            });

            $(options.text).bind('change', function() {
            	// Get the value of current object
                var url = this.value;
				//alert(url);
                // Determine the Preview field
                var preview = $(this).siblings(options.preview);
                // Bind the value to Preview field
                $(preview).attr('src', url);
            });
        }

        // Usage
        $('.upload').priya(); // Use as default option.
    });		
}(jQuery));

jQuery(document).ready(function(){
    jQuery('[data-toggle="tooltip"]').tooltip();   
});

jQuery('document').ready(function(){
	jQuery('#create_agents').click(function(e){
		e.preventDefault();
	var agent_name = jQuery('#agent_name').val();
	var agent_email = jQuery('#agent_email').val();
	var agent_pass = jQuery('#agent_pass').val();
	var agent_profile = jQuery('#agent_profile').val();
	//alert(agent_profile);
	if((agent_name != '') &&  (agent_email != '') && (agent_pass != ''))
	{
	 var ajaxurl = "<?php echo admin_url( 'admin-ajax.php' )?>";
	    var data = {
			'action': 'create_agents',
			'agent_name':agent_name,
			'agent_email':agent_email,
			'agent_pass':agent_pass,
			'agent_profile':agent_profile
		};
   jQuery.post(ajaxurl, data, function(response) {
	  jQuery('#agent_name').val('');
	  jQuery('#agent_email').val('');
	  jQuery('#agent_pass').val('');
	  jQuery('#agent_profile').val('');
	jQuery('.success').html(response);  
	   });
	}else{
		alert('All fields are required!');
	}
	});
});
</script>