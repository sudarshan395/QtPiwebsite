jQuery(document).ready(function(e) {
jQuery(".support_click_close").click(function(){
   jQuery(".support_chat_system").slideUp("slow");
	jQuery('.support_click').show();
});
jQuery(".support_click").click(function(){
    jQuery(".support_chat_system").slideDown("slow");
 	jQuery(this).hide();
});
});
/* ESC press close */
jQuery(document).keyup(function(e) {
  if (e.keyCode == 27)// esc
  { 
   jQuery(".support_chat_system").hide();
	jQuery('.support_click').show();
  }
});
function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
    }
    return "";
  }  
  
  function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
  } 
  function delete_cookie( name ) {
  document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}