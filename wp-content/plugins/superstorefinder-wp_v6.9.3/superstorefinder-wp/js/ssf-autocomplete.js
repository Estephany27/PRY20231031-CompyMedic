
jQuery( document ).ready(function() {
	
/*if (typeof google === 'object' && typeof google.maps === 'object') {
  var initSSFwidget = new google.maps.event.addDomListener(window, 'load', initializeSSFWidget);
} else {
*/  
  
 //alert(typeof initStoreLocator);
 
 if(typeof initStoreLocator == 'function' || typeof get_coordinate == 'function'){
	 // don't load Google Maps
 } else {
           var googleApi='';
           if(ssf_api_key!='' && ssf_api_key!='undefined'){
		ssf_api_key = window.atob(ssf_api_key);
		googleApi='key='+ssf_api_key+'&';
	}

jQuery.getScript('https://maps.googleapis.com/maps/api/js?'+googleApi+'sensor=false&libraries=places&language='+ssf_m_lang+'&region='+ssf_m_rgn+'&callback=initializeSSFWidget');

 }
 
//var initSSFwidget = new google.maps.event.addDomListener(window, 'load', initializeSSFWidget);
/*}*/




//var initSSFwidget = new google.maps.event.addDomListener(window, 'load', initializeSSFWidget);


});

function initializeSSFWidget() {
	

	if(ssf_defualt_region=='false'){
              var inputSSFwidget = document.getElementById('ssfboxsearch');
					new google.maps.places.Autocomplete(inputSSFwidget, {
                    componentRestrictions: {'country': ssf_m_rgn}
					});
              
             }else{
				var inputSSFwidget = document.getElementById('ssfboxsearch');
					new google.maps.places.Autocomplete(inputSSFwidget, {  
					});
            }
  
}