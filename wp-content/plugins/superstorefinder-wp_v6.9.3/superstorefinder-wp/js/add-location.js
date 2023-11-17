// Javascript for Admin Area
// Google Map API Standard Code
var map;
var marker;
var geocoder;
var region = 'us';
var markers = new Array();

jQuery(document).ready(function(){
"use strict";
	var address = default_location;
	var lat = "";
	var lng = "";
	var location = "";
		
	if(jQuery('#map_canvas').length) {

	geocoder = new google.maps.Geocoder();
	
		geocoder.geocode( {'address':address,'region':region}, function(results, status) {
		
			if(status == google.maps.GeocoderStatus.OK) {
			
				var lat = results[0].geometry.location.lat();
				var lng = results[0].geometry.location.lng();
				var location = results[0].geometry.location;
				if (jQuery("input[name=ssf_wp_longitude]" ).length ) {
				 jQuery('#ssf_wp_longitude').val(lng.toFixed(7)); // lat
			     jQuery('#ssf_wp_latitude').val(lat.toFixed(7)); // long
				}
		
				var gmap_marker = false;
				if(jQuery('#ssf_wp_latitude').length) {
				
					var val = jQuery('#ssf_wp_latitude').val()*1;
					
					if(val != '' && !isNaN(val)) {
						lat = val;
						gmap_marker = true;
					}
					
				}
		

				if(jQuery('#ssf_wp_longitude').length) {
				
					val = jQuery('#ssf_wp_longitude').val()*1;
					
					if(val != '' && !isNaN(val)) {
						lng = val;
					}
				}

				geocoder = new google.maps.Geocoder();

			var latlng = new google.maps.LatLng(lat,lng);
			if(ssf_request_map_code!="" && ssf_request_map_code!=undefined)
			{
				 var myOptions = {
					zoom: 9,
					styles:ssf_request_map_code,
					center: latlng,
					mapTypeId: google.maps.MapTypeId.ROADMAP
				};	


			} else{	
				
				if(request_style_map_color!=""){

					  var ssfMapColorStyle = [

					  {

						stylers: [

						  { hue: request_style_map_color },

						  { saturation: 0 },

						  { lightness: 50 },

						  { gamma: 1 },

						]

					  }

					];
					
					var myOptions = {
						zoom: 9,
						styles:ssfMapColorStyle,
						center: latlng,
						mapTypeId: google.maps.MapTypeId.ROADMAP
					};

				}else{
					var myOptions = {
						zoom: 9,
						center: latlng,
						mapTypeId: google.maps.MapTypeId.ROADMAP
					};
				}
			}

				map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

				if(gmap_marker) {

				}
				
				marker = new google.maps.Marker({
				  position: latlng, 
				  map: map, 
				  title: 'Drag Me',
				  draggable: true
				});
				
				var infowindow = new google.maps.InfoWindow({
								maxWidth: "300",
								content: 'Drag Me'
				});
							
				infowindow.open(map,marker);
				
				google.maps.event.addListener(marker, 'drag', function(event) {
					 jQuery('#ssf_wp_longitude').val(event.latLng.lng().toFixed(7)); 
					 jQuery('#ssf_wp_latitude').val(event.latLng.lat().toFixed(7)); 
				});
				 
				google.maps.event.addListener(marker, 'dragend', function(event) {
					 jQuery('#ssf_wp_longitude').val(event.latLng.lng().toFixed(7)); 
					 jQuery('#ssf_wp_latitude').val(event.latLng.lat().toFixed(7)); 
				});
		
		}});
		
		
		
	}

	if(jQuery('#address').length) {

		jQuery('#address, #ssf_wp_city, #ssf_wp_state, #ssf_wp_zip').blur(function(){

			var address='';
			address +=' '+ jQuery('#address').val();
			address +=' '+ jQuery('#ssf_wp_city').val();
			address +=' '+ jQuery('#ssf_wp_state').val();
			address +=' '+ jQuery('#ssf_wp_zip').val();
			if(address != '') {

				get_coordinate(address,region);
			}
		});
	}
	
	var autocompleadd = {
            el : document.getElementById('address')
        };
	var _autocompleadd = new google.maps.places.Autocomplete(autocompleadd.el, {});
	
});


/**
 * Get address location
 */
 
function get_coordinate(address, region) {
	
	if(region==null || region == '' || region == 'undefined') {
		region = 'us';
	}

	if(address != '') {
		jQuery('#ajax_msg').html('<p>Loading location</p>');

		geocoder.geocode( {'address':address,'region':region}, function(results, status) {

			if(status == google.maps.GeocoderStatus.OK) {
				jQuery('#ajax_msg').html('<p></p>');
				// populate form field with geo location
				jQuery('#ssf_wp_longitude').val( results[0].geometry.location.lng().toFixed(7));
				jQuery('#ssf_wp_latitude').val( results[0].geometry.location.lat().toFixed(7));

				map.setZoom(10);

				map.setCenter(results[0].geometry.location);

				// Google Map Marker

				marker.setPosition(results[0].geometry.location);
				
			} else {

				jQuery('#ajax_msg').html('<p>Google map geocoder failed: '+status+'</p>');
			}
		});
	}
}

jQuery(document).ready(function(){
	jQuery('.add-locationbody').fadeIn(500);
	bkLib.onDomLoaded(function() { new nicEditor({fullPanel : true}).panelInstance('ssf_wp_html_hours'); });
	bkLib.onDomLoaded(function() { new nicEditor({fullPanel : true}).panelInstance('ssf_wp_html_description'); });
	
	jQuery("input[name*='ssf_wp_store']").keyup(function(){
		jQuery("input[name*='ssf_wp_store']").removeClass('ssf-form-error');
	});
	
	jQuery("input[name*='ssf_wp_address']").keyup(function(){
		jQuery("input[name*='ssf_wp_address']").removeClass('ssf-form-error');
	});
	
	jQuery("#superLocationRequest").on("submit", function(e){
		
		e.preventDefault();	
		if(jQuery("input[name*='ssf_wp_store']").val()==''){
			jQuery("input[name*='ssf_wp_store']").focus();
			jQuery("input[name*='ssf_wp_store']").addClass('ssf-form-error');
			return;
		}
		if(jQuery("input[name*='ssf_wp_address']").val()==''){
			jQuery("input[name*='ssf_wp_address']").focus();
			jQuery("input[name*='ssf_wp_address']").addClass('ssf-form-error');
			return;
		}
		
		if (jQuery('#ssf_gdpr_check').length>0) {
			if(jQuery('input#ssf_gdpr_check').is(':checked')){
				//continue
			}else{
				jQuery('input#ssf_gdpr_check').addClass('ssf_invalid');
				return false;
			}
		}
		
		jQuery("#btnRequestLocation").css('background','#cccccc');
		jQuery("#btnRequestLocation").attr('disabled','disabled');
		
		var form = jQuery('#superLocationRequest')[0];
		// Create an FormData object 
		var data = new FormData(form);
		var ssfdes = jQuery('#ssf_lbl_description').find('.nicEdit-main').text();
		var ssfhour = jQuery('#ssf_lbl_hours').find('.nicEdit-main').text();
        data.append('ssf_wp_description', ssfdes);
		data.append('ssf_wp_hours', ssfhour);
		jQuery('#ssf_form_input').html('<img src="'+ssf_wp_base+'/images/icons/spinner.gif" class="ssfAjaxSubmit">');
		jQuery.ajax({
			type: 'POST',
			url: ssfMyAjax.ajaxurl,
			enctype: 'multipart/form-data',
			data: data,
			contentType: false,
			processData: false,
			success: function(response){
				jQuery('.ssfRequesttitle').html(response);
				jQuery('#ssf_form_input').fadeOut(500)
			}
		});
		
	});
})

jQuery(document).ready(function() {
        jQuery("input#ssf_gdpr_check").click(function() {
            var s_checked = jQuery(this).is(':checked');
            if (s_checked) {
                jQuery('input#ssf_gdpr_check').removeClass('ssf_invalid');
            } 
        });
    });
