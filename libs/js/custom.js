jQuery(document).ready(function($) {
	
	$inputs = jQuery("#splite_popup_box input,  #splite_popup_box textarea, #splite_popup_box select");
	if($inputs.length) {
		$inputs.each(function(e) {
			var inputHeight = jQuery(this).height();
			var tip = jQuery(this).closest('span.wpcf7-not-valid-tip');
			var tipHeight = tip.outerHeight(); 			
			if( tipHeight != inputHeight ) {
				tip.css({'line-height':inputHeight});
			}			
			//console.log( inputHeight + ' - ' + tipHeight);
		});
		$inputs.each(function(e) {
			jQuery(this).focus(function(e){
				//console.log('focust');
				jQuery(this).removeClass('wpcf7-not-valid').next('span.wpcf7-not-valid-tip').css({'width':'0','padding':'0','visibility':'hidden'}); 
			})
		});
	}
	
	var external_unloader = jQuery( '.splite-closepopup' );	
	if( external_unloader.length ) {
		//console.log("sa");
		external_unloader.click( function(e) {
			if( external_loader.tagName() == 'A' ) {
				e.preventDefault(); 
				splite_unloader();
			}
			else {
				splite_unloader();
			}
		});
	}
	
	var external_loader = jQuery( '.splite-showpopup' ); 
	if( external_loader.length ) {
		external_loader.click( function(e) {
			if( external_loader.tagName() == 'A' ) {
				e.preventDefault(); 
				splite_loader();
			}
			else {
				splite_loader();
			}
		});
	}
	
	splite_set_popup();
});


/**
* Function: tagName 
* Returns the HTML tag of the element
* Used for determining the tag of external anchor tag
**/ 
jQuery.fn.tagName = function() {
	return this.prop("tagName");
};


/**
* Function: splite_unloader 
* Unloads the popup box
**/ 
function splite_unloader() {    

	$animator = jQuery('.splite_popup_animator');
	var ef = $animator.data('unloadeffect'); 
	var speed = $animator.data('unloadspeed'); 

	//console.log(ef,speed);
	jQuery("#splite_popup_box").addClass(ef + ' animated').css({"-webkit-animation-duration": speed+"s","animation-duration": speed+"s"}).one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
		jQuery(this).addClass('manage').removeClass(ef);
	});

	jQuery('#splite_curtain').fadeOut("slow");
	jQuery(".splite_container").css({ 
		"opacity": "1" 
	});
	//jQuery(this).addClass('manage');
	return false;	
}   


/**
* Function: splite_loader 
* Loads the popup box
**/ 
function splite_loader() {  
	
	$animator = jQuery('.splite_popup_animator');
	var ef = $animator.data('loadeffect'); 
	var speed = $animator.data('loadspeed'); 
	
	// Load the Popup
	var docHeight = jQuery(document).height();
	jQuery('#splite_curtain').height(docHeight);	
	jQuery('#splite_curtain').fadeIn("fast");			
	//jQuery('#splite_popup_box').fadeIn("slow");
	
	splite_set_popup(		
		jQuery("#splite_popup_box").removeClass('manage').addClass(ef + ' animated').
		css({"-webkit-animation-duration": speed+"s","animation-duration": speed+"s"}).
		one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){jQuery(this).removeClass(ef);})
	);
	
	return false;
}


/**
* Function: window.resize 
* Take action when window is resized
* Used to splite_set_popup() on resize
**/ 
jQuery( window ).resize(function() {	
	splite_set_popup();
});


/**
* Function: splite_set_popup
* Sets the popup position and layout
* Important and need improvement 30072016
**/ 
function splite_set_popup() {
	$popup = jQuery('#splite_popup_box');
	$curtain = jQuery('#splite_curtain');
	
	$title = jQuery('#splite_popup_title');	
	$description = jQuery('#splite_popup_description');	
	$formArea = jQuery('#splite_form_container');
	
	$animator = jQuery('.splite_popup_animator');
	var loadspeed = $animator.data('loadspeed'); 
		
	if ( $popup.length ) {
		var popupHeight = $popup.outerHeight();
		var titleHeight = $title.outerHeight();
		var descriptionHeight = $description.outerHeight();		
		$closeButton = jQuery('#splite_popupBoxClose');
		
		// Set Close Button Line Height to Title Height
		$closeButton.css({ 'line-height':titleHeight + 'px', });	
		descrptionHeight = 0; 
		formHeight = popupHeight - titleHeight - 24;
		$formArea.outerHeight(formHeight);
		formAreaHeight = $formArea.outerHeight();
		//alert( formAreaHeight + ' + ' +  formHeight ); 
		if( formAreaHeight >= formHeight ) {
			//formArea.css({'overflow-y':'scroll'});			
			//setTimeout(function(){
				$formArea.niceScroll({touchbehavior:false,cursorcolor:"#757575",cursoropacitymax:0,cursorwidth:0,cursorborder:"1px solid #2848BE",cursorborderradius:"0px",background:"#ccc",autohidemode:"scroll"});//.cursor.css({"background-image":"url(img/mac6scroll.png)"}); // MAC like scrollbar	
			//},0);			
			$formArea.getNiceScroll().resize()
		}
		
		// Set Popup Left Position
		curtainWidth = $curtain.width();
		popupWidth = $popup.width();		
		popLeft = (curtainWidth - popupWidth)/2;
		//alert(popLeft);
		
		// Set Popup Top Position
		windowHeight = jQuery(window).height();
		popTop = (windowHeight - popupHeight)/2;
		
		$popup.css( 'left', popLeft+'px' );
		$popup.css( 'top', popTop+'px' );
	}
	
	splite_set_side_button();
}


/**
* Function: set_side_button
* Sets the side button position
* Important and needs improvement 30072016
**/ 
function splite_set_side_button() {
	
	$side_button = jQuery('a.splite_sideEnquiry');
	$curtain = jQuery('#splite_curtain');
	
	var button_width = $side_button.outerWidth();
	var button_height = $side_button.outerHeight();
	
	if( button_width > button_height ) 
		var button_pull = button_height/2 - button_width/2;
	else 	
		var button_pull = button_width/2 - button_height/2;
	
	//alert(button_width + ' - ' + button_height + ' = ' + button_pull);
	
	// Adjust if button right
	var side_button_pos_right = jQuery('a.splite_sideEnquiry.pos_right');
	if( side_button_pos_right.length ) 
		side_button_pos_right.css( 'right', button_pull+ 'px' );
	
	// Adjust if button left
	var side_button_pos_left = jQuery('a.splite_sideEnquiry.pos_left');
	if( side_button_pos_left.length )
		side_button_pos_left.css( 'left', button_pull+ 'px' );

	
	//side_button_on_mobile = jQuery('a.splite_sideEnquiry.on_mobile');
}