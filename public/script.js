var VIDENGAGE_BASE_URL = 'https://videngageme.s3.amazonaws.com/';
var is_top_bar = 0;
var is_cta = 1;
var is_logo = 0;
var is_html = 0;
var is_header = 0;
var bar_position = 'bottomright';
var autoplay = 1;
var desc_text1 = "Apply For An E-Visa Now";
var desc_text2 = "";
var logo_url = "";
var custom_html = "";
var file_path = "https://videngageme.s3.amazonaws.com/28dd2c7955ce926456240b2ff0100bde/32cbf687880eb1674a07bf717761dd3a";
var video_type = 4;
var video_source = '<source src="72dtprmvai"  type="video/mp4" />';
var video_url = "72dtprmvai";
var fonts_to_include = [];
var mobile_display = "1";
var display_controls = 0;

autoplay_txt = '';

/*
video_source

*/
if (video_type == 1) { // yt
	if (autoplay)
		autoplay_txt = '&autoplay=1"';
	if ( display_controls )
		controls_txt = 'controls=1';
	else
		controls_txt = 'controls=0';
	document.write('<div id="veg-video-container-virtual">&nbsp;</div><div id="veg-video-container-holder">'+
	'<div id="veg-video-container">'+
	' <iframe width="630" height="354" src="//www.youtube.com/embed/'+video_url+'?rel=0&amp;'+controls_txt+'&amp;showinfo=0'+autoplay_txt+'" frameborder="0" allowfullscreen></iframe>'+
	'</div>'+
	'</div>');
}
else if (video_type == 2) { // vimeo
	if (autoplay)
		autoplay_txt = '&autoplay=1"';
	document.write('<div id="veg-video-container-virtual">&nbsp;</div><div id="veg-video-container-holder">'+
	'<div id="veg-video-container">'+
	'<iframe src="https://player.vimeo.com/video/'+video_url+'?title=0&byline=0&portrait=0'+autoplay_txt+'" width="630" height="354" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>'+
	'</div>'+
	'</div>');
}
else if (video_type == 3) {
	if (autoplay)
		autoplay_txt = 'autoplay="autoplay"';
	document.write('<div id="veg-video-container-virtual">&nbsp;</div><div id="veg-video-container-holder">'+
	'<div id="veg-video-container">'+
	// '  <video width="630" height="354" id="veg-player-1" controls="controls" preload="metadata" '+autoplay_txt+' style="max-width:100%;height:auto;">'+
	'  <video width="100%" height="100%" id="veg-player-1" controls="controls" preload="metadata" '+autoplay_txt+' style="max-width:100%;height:auto;">'+
	'    <source src="72dtprmvai"  type="video/mp4" />'+
	'  </video>'+
	'</div>'+
	'</div>');
}
else if (video_type == 4) {
	if (autoplay)
		autoplay_txt = '?autoPlay=true';
	document.write('<div id="veg-video-container-virtual">&nbsp;</div><div id="veg-video-container-holder">'+
	'<div id="veg-video-container">'+
	'<iframe src="//fast.wistia.net/embed/iframe/'+video_url+''+autoplay_txt+'" allowtransparency="true"  frameborder="0" scrolling="no" class="wistia_embed" name="wistia_embed" allowfullscreen mozallowfullscreen webkitallowfullscreen oallowfullscreen msallowfullscreen width="630" height="354"></iframe>'+
	'</div>'+
	'</div>');
	document.write('<script src="//fast.wistia.net/assets/external/E-v1.js" async></script>');
}
else if (video_type == 5) {
	if (autoplay)
		autoplay_txt = '?autoPlay=true';
	document.write('<script src="https://www.vooplayer.com/v3/watch/video.js"></script>');
	document.write('<div id="veg-video-container-virtual">&nbsp;</div><div id="veg-video-container-holder">'+
	'<div id="veg-video-container">'+
	'<iframe id="" name="vooplayerframe" style="max-width:100%;height:auto !important;" allowtransparency="true" allowfullscreen="true" src="https://www.vooplayer.com/v3/watch/watch.php?v='+video_url+'=&autoplay=false" frameborder="0" scrolling="no" width="630" height="354" > </iframe>'+
	'</div>'+
	'</div>');
}





var parent = document.createElement("div");
parent.id = "veg-scroll-bar";
parent.className = "veg-"+bar_position;
parent.style.cssText  = "display:none";

var child = document.createElement("div");
child.className = "veg-scroll-inner";
parent.appendChild(child);

var close_btn = document.createElement("a");
close_btn.className = "veg-close-btn";
close_btn.href = "#";
child.appendChild(close_btn);

var zoom_btn = document.createElement("a");
zoom_btn.id = "veg-zoom-btn";
zoom_btn.className = "zoomin";
zoom_btn.href = "#";
child.appendChild(zoom_btn);

if (is_top_bar) {
	if (is_logo && logo_url.length) {

		var logo = document.createElement("a");
		logo.className = "veg-logo";
		logo.href = "";
		logo.target = "_blank";

		var logo_img = document.createElement("img");
		logo_img.src = logo_url;
		logo.appendChild(logo_img);

		child.appendChild(logo);
	}
	else {
		var logo = document.createElement("span");
		logo.className = "veg-logo-padding";
		child.appendChild(logo);
	}

	if (is_header && desc_text1.length || desc_text2.length) {
		var extra_txt = document.createElement("div");
		extra_txt.id = "veg-desc-text";
		extra_txt.className = "veg-desc-text";

		if (desc_text1.length) {
			var desc_text1_el = document.createElement("span");
			desc_text1_el.className = "veg-desc-text1";
			desc_text1_el.innerHTML = desc_text1;
			extra_txt.appendChild(desc_text1_el);
		}
		if (desc_text2.length) {
			var desc_text2_el = document.createElement("span");
			desc_text2_el.className = "veg-desc-text2";
			desc_text2_el.innerHTML = desc_text2;
			extra_txt.appendChild(desc_text2_el);
		}
		child.appendChild(extra_txt);
	}

	if (is_html && custom_html.length) {
		var html_el = document.createElement("span");
		html_el.className = "veg-html-element";
		html_el.innerHTML = custom_html;
		child.appendChild(html_el);
	}
}

if (is_cta) {
	var cta = document.createElement("a");
	cta.className = "veg-cta";
	cta.href = "https://michaelwilson.thrivecart.com/secure-ssl-website-protector/";
	if ("0" == "1")
		cta.target = "_blank";
	cta.innerHTML = "Click Here To Get Started &gt;&gt;";
	child.appendChild(cta);
}
document.body.insertBefore(parent,document.body.childNodes[0]);

var show_btn = document.createElement("a");
show_btn.id = "veg-show-btn";
show_btn.className = "bar-"+bar_position;
show_btn.href = "#";
document.body.insertBefore(show_btn,document.body.childNodes[0]);

// '<div id="veg-scroll-bar"><div class="veg-scroll-inner"><span class="veg-desc-text">Lorem Ipsum...</span></div></div>';
if (video_type != 1 && video_type != 2) {
	document.addEventListener("DOMContentLoaded", function(event) {
	  //do work
		/* Step 1: include jquery if not already included */
		if (typeof jQuery == 'undefined') {
			// console.log('nojquery')
			var script = document.createElement('script');
			script.src = 'https://code.jquery.com/jquery-1.11.3.min.js';
			script.type = 'text/javascript';
			script.onload = videngage_include_mediaelement_css;                    //most browsers
			script.onreadystatechange = function() {   //ie
			    if (this.readyState == 'complete') {
			        videngage_include_mediaelement_css();
			    }
			}
			document.getElementsByTagName('head')[0].appendChild(script);
		}
		else {
			// JQuery already included
			// console.log('sijquery')
			// jQuery( document ).ready(function() {
				videngage_include_mediaelement_css();
			// });
		}
	});
	/* Step 2: include medialementjs if not already included */
	function videngage_include_mediaelement_css() {
		jQuery('head').append('<link rel="stylesheet" href="'+file_path+'.css" type="text/css" />');
		// jQuery('head').append('<link rel="stylesheet" href="'+VIDENGAGE_BASE_URL+'mediaelement/mediaelementplayer.min.css?tid='+Math.random()+'" type="text/css" />');
		jQuery('head').append('<link rel="stylesheet" href="'+VIDENGAGE_BASE_URL+'mediaelement/mediaelementplayer.min.css" type="text/css" />');
		videngage_include_mediaelement_js()
	}

	function videngage_include_mediaelement_js() {
		// jQuery('head').append('<script type="text/javascript" src="'+VIDENGAGE_BASE_URL+'mediaelement/mediaelement-and-player.min.js?tid='+Math.random()+'"><\/script>');

		var script = document.createElement('script');
		script.src = VIDENGAGE_BASE_URL+'mediaelement/mediaelement-and-player.min.js';
		script.type = 'text/javascript';
		script.onload = videngange_init_player;                    //most browsers
		script.onreadystatechange = function() {   //ie
		    if (this.readyState == 'complete') {
		        // videngage_include_mediaelement_css();
		        jQuery( document ).ready(function() {
		        	videngange_init_player()
		        });
		    }
		}
		document.getElementsByTagName('head')[0].appendChild(script);

		// jQuery('head').append('<script type="text/javascript" src="'+VIDENGAGE_BASE_URL+'mediaelement/froogaloop.js"><\/script>');
	}
}
else {
	// jQuery('head').append('<link rel="stylesheet" href="'+file_path+'.css" type="text/css" />');
	document.addEventListener("DOMContentLoaded", function(event) {

		var script = document.createElement('link');
		script.href = file_path+'.css';
		script.type = 'text/css';
		script.rel = 'stylesheet';
		script.onload = maybe_include_jquery;                    //most browsers
		script.onreadystatechange = function() {   //ie
		    if (this.readyState == 'complete') {
		        maybe_include_jquery();
		    }
		}
		document.getElementsByTagName('head')[0].appendChild(script);
		// maybe_include_jquery();
	});

}

function maybe_include_jquery() {
	if (typeof jQuery == 'undefined') {
		// console.log('nojquery')
		var script = document.createElement('script');
		script.src = 'https://code.jquery.com/jquery-1.11.3.min.js';
		script.type = 'text/javascript';
		script.onload = videngange_init_player;                    //most browsers
		script.onreadystatechange = function() {   //ie
		    if (this.readyState == 'complete') {
		        videngange_init_player();
		    }
		}
		document.getElementsByTagName('head')[0].appendChild(script);
	}
	else {
		// JQuery already included
		// console.log('sijquery')
		// jQuery( document ).ready(function() {
			videngange_init_player();
		// });
	}

}


var ve_is_main_video = true;
var ve_video_bottom = 0;
var max_device_with = 500;

// var ve_video_pos = 'right';
function videngange_init_player() {
	function LightenDarkenColor(col, amt) {
	    var usePound = false;
	    if (col[0] == "#") {
	        col = col.slice(1);
	        usePound = true;
	    }
	    var num = parseInt(col,16);
	    var r = (num >> 16) + amt;
	    if (r > 255) r = 255;
	    else if  (r < 0) r = 0;
	    var b = ((num >> 8) & 0x00FF) + amt;
	    if (b > 255) b = 255;
	    else if  (b < 0) b = 0;
	    var g = (num & 0x0000FF) + amt;
	    if (g > 255) g = 255;
	    else if (g < 0) g = 0;
	    return (usePound?"#":"") + (g | (b << 8) | (r << 16)).toString(16);
	}

	function ve_do_video_zoom( zoomtype ) {
		// console.log(zoomtype)
		if (zoomtype == 'in') {
			// make the video bigger
			jQuery('#veg-zoom-btn').removeClass('zoomin').addClass('zoomout');
			jQuery('#veg-video-container-holder, #veg-video-container, #veg-video-container iframe, #veg-video-container embed').removeClass('veg-vid-smallsize');
			jQuery('#veg-video-container-holder, #veg-video-container, #veg-video-container iframe, #veg-video-container embed').addClass('veg-vid-fullsize');
		}
		else {
			jQuery('#veg-zoom-btn').removeClass('zoomout').addClass('zoomin');
			jQuery('#veg-video-container-holder, #veg-video-container, #veg-video-container iframe, #veg-video-container embed').removeClass('veg-vid-fullsize');
			jQuery('#veg-video-container-holder, #veg-video-container, #veg-video-container iframe, #veg-video-container embed').addClass('veg-vid-smallsize');
		}
	}

	/*
		0 : nothing
		1 : everything
		2 : just video

	*/
	function ve_show_bar( should_display ) {
		if (!should_display) return;
	  	jQuery('#veg-video-container-virtual').show();
	  	

		// console.log(should_display)
	  	jQuery('#veg-show-btn').hide();
	  	jQuery("#veg-video-container-holder").addClass("veg-show-small").addClass("veg-video-"+bar_position);
	  	if (should_display == 1)
	  		jQuery("#veg-scroll-bar").show();
	  	else
	  		jQuery("#veg-scroll-bar").hide();

	  	if (video_type == 5) {
	  		$elem = jQuery('#veg-video-container-holder iframe');
	  		$elem.attr('style', 'max-width:100%;height: auto')
	  	}
		vengage_do_resize_videos();

	  	nobar_elements_position();
	  	// jQuery('"#veg-video-container-holder"').closest(' .ib2-wsection-el .ib2-section-el').css('z-index', 10000);
	  	// var $topSubMenu = jQuery(this).parents('.sub-menu').last();
	}

	function ve_hide_bar() {
	  	jQuery('#veg-video-container-virtual').hide();

			jQuery('#veg-zoom-btn').removeClass('zoomout').addClass('zoomin');

		jQuery('#veg-video-container-holder, #veg-video-container, #veg-video-container iframe, #veg-video-container embed').removeClass('veg-vid-fullsize');
			jQuery('#veg-video-container-holder, #veg-video-container, #veg-video-container iframe, #veg-video-container embed').removeClass('veg-vid-smallsize');

	  jQuery("#veg-video-container-holder").removeClass("veg-show-small").removeClass("veg-video-"+bar_position);
	  jQuery("#veg-scroll-bar").hide();
	  if (video_type == 5) {
	  	$elem = jQuery('#veg-video-container-holder iframe');
	  	$elem.attr('style', 'max-width:100%;height: 354px')
	  }
		vengage_do_resize_videos();

	}

	function ve_is_video_visible()
	{
	  var ve_scrollTop = jQuery(window).scrollTop();
	  return (ve_scrollTop < ve_video_bottom)
	}

	/*
		0: show all
		1: hide all
		2: show only video

		return:
			0 : show nothing
			1 : show everything
			2 : show just video
	*/
	function should_display_bar() {
		var current_w = jQuery(window).width();
		if (current_w > max_device_with)
			return 1;

		if (mobile_display == 1) {
			return 0;
		}
		else if (mobile_display == 2) {
			return 2;
		}
		else {
			return 1;
		}
	}

	function handle_bar_visibility() {
		if (ve_is_video_visible()) {
		  if (!ve_is_main_video) {
		    ve_is_main_video = true;
		    ve_hide_bar();
		  }
		}
		else {
		    if (should_display = should_display_bar()) {
			  if (ve_is_main_video) {
			    ve_is_main_video = false;
			    	ve_show_bar( should_display );
			  }
			}
		}
	}

	function nobar_elements_position() {
		if (!is_cta) return;
		/* Calculate positions, in case it is the simple version with video + button */
		var scroll_bar = jQuery('#veg-scroll-bar');
		if (
			scroll_bar.hasClass('veg-topleft') ||
			scroll_bar.hasClass('veg-topright') ||
			scroll_bar.hasClass('veg-bottomleft') ||
			scroll_bar.hasClass('veg-bottomright')
			) {
				jQuery('#veg-scroll-bar').css('width', 512+70)
				jQuery('#veg-scroll-bar').css('height', 320+100)
				var cta_el = jQuery('.veg-cta')

				if (scroll_bar.hasClass('veg-topleft') ||
					scroll_bar.hasClass('veg-bottomleft')) {
						jQuery('#veg-scroll-bar').css('left', 70);
					}
				else {
						jQuery('#veg-scroll-bar').css('right', 70);
						cta_el.css('right', '0');

					}

						cta_el.css('position', 'absolute');
						cta_el.css('float', 'none');
						cta_el.css('text-align', 'center');
						// cta_el.css('box-sizing', 'content-box');
						cta_el.css('width', (512));
						fix_btn_color();


				if (scroll_bar.hasClass('veg-topleft') ||
					scroll_bar.hasClass('veg-topright')) {
						cta_el.css('top', 320);
					}
				else {
						cta_el.css('bottom', 320);
					}



			// console.log(cta_el.html())
				/*if (cta_el.html().length) {
					height = cta_el.height();
					console.log(height)
					jQuery("<style>")
			    		.prop("type", "text/css")
			    		.html("\
			    		#veg-video-container-holder.veg-show-small {\
			        		bottom: "+height+"px;\
			    		}")
			    		.appendTo("head");
				}
		    }*/
	}
	else
		fix_btn_color()
}

function rgb2hex(rgb) {
	if (!rgb) return false;
	rgb_orig = rgb;
    rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
    function hex(x) {
        return ("0" + parseInt(x).toString(16)).slice(-2);
    }
    // console.log(rgb);
    if (rgb == null)
    	return false;
    return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
}

	function fix_btn_color() {
		if (!is_cta) return;
		var cta_el = jQuery('.veg-cta')
		// console.log(cta_el);
		if (cta_el) {
			original_color = cta_el.css('background-color');
			if (original_color == 'transparent') return;
			// console.log(original_color)
			original_color = rgb2hex(original_color);
			if (!original_color) return;
			border_color = LightenDarkenColor(original_color, -30);
			cta_el.css('border-bottom-color', border_color);
		}

		jQuery(".veg-cta").mouseenter(function() {
			// console.log('in')
		    jQuery(this).css("background-color", border_color);
			jQuery(this).css('border-bottom-color', original_color);

		}).mouseleave(function() {
		     jQuery(this).css("background-color", original_color);
			jQuery(this).css('border-bottom-color', border_color);

		});
	}
	try {
		jQuery('video, audio').mediaelementplayer({
			videoWidth: '100%',
			videoHeight: '100%',
			/*videoWidth: 630,
			videoHeight: 354,*/
			enableAutosize: true
		});
	}catch(err) {}


	var $allVideos = jQuery("#veg-video-container iframe[src^='//player.vimeo.com'], #veg-video-container iframe[src^='//www.youtube.com']"),

	    // The element that is fluid width
	    $fluidEl = jQuery("#veg-video-container");

	// Figure out and save aspect ratio for each video
	$allVideos.each(function() {

	  jQuery(this)
	    .data('aspectRatio', this.height / this.width)

	    // and remove the hard coded width/height
	    .removeAttr('height')
	    .removeAttr('width');

	});

	
		// When the window is resized
		jQuery(window).resize(function() {

			vengage_do_resize_videos()
			$btn = jQuery('#veg-video-container');
			ve_video_bottom = $btn.offset().top+$btn.height();
			// console.log('bottom ='+ ve_video_bottom)
		// Kick off one resize to fix all videos on page load
		}).resize();

	$btn = jQuery('#veg-video-container');
	ve_video_bottom = $btn.offset().top+$btn.height();


	jQuery( window ).scroll(function() {
		handle_bar_visibility();
	});



	jQuery( '.veg-close-btn' ).click(function(e) {
		e.preventDefault();
		// jQuery('#veg-scroll-bar').fadeOut();
		ve_hide_bar();
		jQuery('#veg-show-btn').show();
	})

	jQuery( '#veg-show-btn' ).click(function(e) {
		e.preventDefault();
		// jQuery('#veg-scroll-bar').fadeOut();
		jQuery('#veg-show-btn').hide();
		ve_show_bar( 1 );
	})
// console.log('aa')
	jQuery( '#veg-zoom-btn' ).click(function(e) {
		e.preventDefault();
		// console.log('click')
		if( jQuery(this).hasClass('zoomin'))
			ve_do_video_zoom('in')
		else
			ve_do_video_zoom('out')
	})

	// load google fonts if needed
	if (fonts_to_include.length) {
		str_fonts = '';
		for (i=0;i<fonts_to_include.length;i++) {
			if (i) str_fonts += '|';
			str_fonts += fonts_to_include[i];
		}
		// console.log(str_fonts);
	jQuery('head').append('<link rel="stylesheet" href="http://fonts.googleapis.com/css?family='+str_fonts+'" type="text/css" />');
	// jQuery('head').append('<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Tangerine:bold,bolditalic|Inconsolata:italic|Droid+Sans" type="text/css" />');

	}
	/*if (video_type == 5) {
		$elem = jQuery('#veg-video-container-holder iframe');
		$elem.attr('style', 'max-width:100%;height: auto !important')
		console.log($elem.attr('style'))
	}*/

/*
	// Find all YouTube videos
	var $allVideos = jQuery("#veg-video-container iframe[src^='//player.vimeo.com'], #veg-video-container iframe[src^='//www.youtube.com']"),

	    // The element that is fluid width
	    $fluidEl = jQuery("#veg-video-container");

	// Figure out and save aspect ratio for each video
	$allVideos.each(function() {

	  jQuery(this)
	    .data('aspectRatio', this.height / this.width)

	    // and remove the hard coded width/height
	    .removeAttr('height')
	    .removeAttr('width');

	});
*/

	function vengage_do_resize_videos() {
		var newWidth = $fluidEl.width();
		var first_height = 0;
		// Resize all videos according to their own aspect ratio
		$allVideos.each(function() {
		  var $el = jQuery(this);
			// parent = $el.closest('#veg-video-container-holder')
			// if( !parent.hasClass( 'veg-show-small' ) )
		  $el
		    .width(newWidth)
		    .height(newWidth * $el.data('aspectRatio'));
		    first_height = newWidth * $el.data('aspectRatio');
		    $el.closest('#veg-video-container').css('height', first_height+'px' );
		});
		jQuery('#veg-video-container-virtual').css('height', first_height+'px !important' );
		// $btn = jQuery('#veg-video-container');
		// new_bottom = $btn.offset().top+$btn.height()
		// if( new_bottom != ve_video_bottom)
		// 	ve_video_bottom = $btn.offset().top+$btn.height();
	}
};
