function sppro_copyToClipboard(element) {	
	element.preventDefault(); 
	var $temp = jQuery("<input>");
	jQuery("body").append($temp);
	$temp.val(jQuery(element).text()).select();
	document.execCommand("copy");
	$temp.remove();	
}


jQuery(document).ready(function() { // wait for page to finish loading 
	jQuery('.sp-btn-importer').click( function(e) {
		
		e.preventDefault();
		$btnClicked = jQuery(this); 
		$parent = jQuery(this).parent(); 
		$parentBox = jQuery(this).closest('.import-box'); 
		$loader = $parent.find('.sp-loader'); 
		$importResult = $parentBox.find('.import-box-result'); 
		
		//$btnClicked.addClass('animate'); 
		$loader.slideDown(); 		
		$importResult.removeClass('error success').slideUp(); 
		$btnClicked.addClass('disable');

		jQuery.post(
			ajaxurl,
			{
				action : 'splite_action_importDemo',
				title : $btnClicked.attr('data-title'),
			},
			function( response ) {				
				if( response.success === true ) {					
					$importResult.addClass('success').html(response.data.reason);
					if(response.data.reload)
						setTimeout(function() {location.reload();}, 1000);					 
				}
				else {
					$importResult.addClass('error').html(response.data.reason);
					if(response.data.reason.indexOf("exists")==0) {
					}						
				}
				$importResult.slideDown();
				$loader.slideUp(); 
			} 
		);
	});
});