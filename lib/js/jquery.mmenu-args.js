jQuery(document).ready(function() {
	
	// Main Function
	if (jQuery("body").hasClass('mobile-enabled')) {
		jQuery("body").addClass( "mobile-on" );
		
		// Build out our mobile menu
		var $primaryMenu = '';
		
		if (jQuery('.menu-primary').length) { var $primaryMenu = '<ul>'+jQuery('.menu-primary').clone(true).find('li.toggle-menu').remove().end().html()+'</ul>'; } 
		
		var $mobileMenu = jQuery('<nav id="mobile-menu"><div>'+$primaryMenu+'<a class="close-menu" href="#mobile-menu"><i class="icon-close"></i><span class="screen-reader-text">Close Menu</span></a></div></nav>');
		
		jQuery($mobileMenu).mmenu({
			// Options
		}, {
			
			// Config
		});	
		
	// Toggle Button
	jQuery(".toggle-menu a").click(function(){
		jQuery("#mobile-menu").trigger("open.mm");
	});	
	
	// Close Button
	jQuery(".close-menu").click(function(){
		jQuery("#mobile-menu").trigger("close.mm");
	});
		
	}
	
});