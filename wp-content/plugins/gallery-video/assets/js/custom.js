"use strict";
function galleryVideoIsotope(elem,option){
    if(typeof elem.isotope == 'function'){
        elem.isotope(option);
    }
    else{
        elem.hugeitmicro(option);
    }
}
function galleryVideolightboxInit() {
    jQuery('.gallery-video-content').each(function() {
        var galleryVideoId = jQuery(this).attr('data-gallery-video-id');
        jQuery(this).find(".slider-content:first .image-block_"+galleryVideoId+" a,.slider-content:last .image-block_"+galleryVideoId+" a").removeClass('group1');
        jQuery(this).find("a[href*='youtu'],a[href='vimeo']").removeClass('cboxElement ');
        jQuery(".group1").vcolorbox({rel:'group1'});
        jQuery(".vyoutube").vcolorbox({iframe: true, innerWidth: 640, innerHeight: 390});
        jQuery(".vvimeo").vcolorbox({iframe: true, innerWidth: 640, innerHeight: 390});
        jQuery(".lightbox_video").vcolorbox({iframe: true, innerWidth: 640, innerHeight: 390});
        jQuery(".iframe").vcolorbox({iframe: true, width: "80%", height: "80%"});
        jQuery(".inline").vcolorbox({inline: true, width: "50%"});
        jQuery(".callbacks").vcolorbox({
            onOpen: function () {
                alert('onOpen: vcolorbox is about to open');
            },
            onLoad: function () {
                alert('onLoad: vcolorbox has started to load the targeted content');
            },
            onComplete: function () {
                alert('onComplete: vcolorbox has displayed the loaded content');
            },
            onCleanup: function () {
                alert('onCleanup: vcolorbox has begun the close process');
            },
            onClosed: function () {
                alert('onClosed: vcolorbox has completely closed');
            }
        });

        jQuery('.non-retina').vcolorbox({rel: 'group5', transition: 'none'})
        jQuery('.retina').vcolorbox({rel: 'group5', transition: 'none', retinaImage: true, retinaUrl: true});
    });
}
jQuery(document).ready(function () {
    galleryVideolightboxInit();
});