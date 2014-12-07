function unloadPopupBox() {    // TO Unload the Popupbox

	var docHeight = jQuery(document).height();

	jQuery('#popup_box').fadeOut("slow");
	jQuery(".container").css({ // this is just for style       
		"opacity": "1" 
	});
	jQuery('#curtain').fadeOut("slow");
	return false;	
}   
			   
function loadPopupBox() {  

	var docHeight = jQuery(document).height();
	
	jQuery("html, body").animate({scrollTop: 0}, 1000);
	jQuery('#curtain').height(docHeight);
	jQuery('#curtain').fadeIn("slow");		
	
	jQuery('#popup_box').fadeIn("slow");
	jQuery(".container").css({ // this is just for style
		"opacity": "0.3" 
	});
	
	set_popup();
	
	return false;
} 
jQuery( window ).resize(function() {
	set_popup();
});


jQuery(document).ready(function($) {
	set_popup();
});

function set_popup() {
	popup = jQuery('#popup_box');
	curtain = jQuery('#curtain');
	
	if ( popup.length ) {
		popupHeight = popup.outerHeight();
		titleHeight = jQuery('#popup_title').outerHeight();
		descriptionHeight = jQuery('#popup_description').outerHeight();
		
		descrptionHeight = 0; 
		//alert( titleHeight ); 
		
		formHeight = popupHeight - titleHeight - descriptionHeight - 0;
		//alert( 'Popup:' +popupHeight+ ' + FormHeight:' +formHeight );
		$formContainer = jQuery('#form_container').height(formHeight);
		
		
		// Set Popup Left Position
		curtainWidth = curtain.width();
		popupWidth = popup.width();
		
		popLeft = (curtainWidth - popupWidth)/2;
		//alert(popLeft);
		
		popup.css( 'left', popLeft+'px' );
	}
}