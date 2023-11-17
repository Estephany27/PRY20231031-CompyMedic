// JavaScript Document
/*Open Close hour and email script */
jQuery.getScript("https://www.google.com/recaptcha/api.js?onload=onloadCalls&render=explicit");
var $infoToggler = jQuery('.info__toggler'),
    $infoTogglerContents = jQuery('.info__toggler-contents');
    $infoToggler.togglerify({
        singleActive: true,
        slide: true,
        content: function(index) {
            return $infoTogglerContents.eq(index);
        }
    });

function SendMail(rcvEmail){
	var emailRegex = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
	var name = document.form.ssf_cont_name.value,
	email = document.form.ssf_cont_email.value,
	phone = document.form.ssf_cont_phone.value,
	message = document.form.ssf_cont_msg.value;
    email=email.trim();
	var storename =jQuery('#storeLocatorInfobox .store-location').html();
	
	 if(jQuery('#ssf-comment-form').html()=="undefined" || jQuery('#ssf-comment-form').html()==undefined){
		 var ssfgrecaptcharesponse=jQuery("#g-recaptcha-response").val();
	 } else {
		var ssfgrecaptcharesponse=jQuery("#g-recaptcha-response-1").val();
	 }

		if(name == "" )
		{
			document.form.ssf_cont_name.focus() ;
			document.getElementById("ssf-msg-status").innerHTML = "<span style='color:red;'>"+contact_plc_name+"</span>"
			return false;
		}
		if(email == "" )
		{
			document.form.ssf_cont_email.focus() ;
			document.getElementById("ssf-msg-status").innerHTML = "<span style='color:red;'>"+contact_plc_email+"</span>";
			return false;
		}
		else if(!emailRegex.test(email)){
		  document.form.ssf_cont_email.focus();
		  document.getElementById("ssf-msg-status").innerHTML = "<span style='color:red;'>"+contact_plc_email+" </span>";
		  return false;
		  }
		if(message == "" )
		{
			document.form.ssf_cont_msg.focus() ;
			document.getElementById("ssf-msg-status").innerHTML = "<span style='color:red;'>"+contact_plc_msg+"  </span>";
			return false;
		}
		
		if(ssfgrecaptcharesponse == "" )
		{

			document.getElementById("ssf-msg-status").innerHTML = "<span style='color:red;'>"+reCAPTCHA_warning+"  </span>";
			return false;
		}
		
		if (jQuery('#ssf_gdpr_check').length>0) {
			if(jQuery('input#ssf_gdpr_check').is(':checked')){
				//continue
			}else{
				jQuery('input#ssf_gdpr_check').addClass('ssf_invalid');
				return false;
			}
		}
		var name_lbl = ssf_cont_us_name;
		var email_lbl = ssf_cont_us_email;
		var msg_lbl = ssf_cont_us_msg;
		var phone_lbl = contact_plc_phone;
		
		 
	   jQuery.ajax({
		type: "POST",
		url: ssf_wp_base + '/sendMail.php?t='+ssfdate.getTime(),
		data: {name: name, email: email, phone: phone, message:message, rcvEmail: rcvEmail,subject: storename,name_lbl:name_lbl, email_lbl:email_lbl, msg_lbl:msg_lbl, phone_lbl:phone_lbl,ssfgrecaptcharesponse: ssfgrecaptcharesponse,google_rc_key: google_rc_key},
		cache: false,
		success: function (html)
		{
			 document.getElementById("ssf-contact-form").reset();
			 if(google_rc_key!=''){
				grecaptcha.reset();
			 }
			 document.getElementById("ssf-msg-status").innerHTML = "<span style='color:green;' id='imageMsgAlert'>"+ssf_msg_sucess+"</span>";
			 jQuery('#imageMsgAlert').fadeOut(5000);
		}
	});
}
if(google_rc_key!=''){
	

 var ssf_cf_verifyCallback = function(response) {
       
      };

 var onloadCalls = function() {
	 
	 if(jQuery('#ssf-comment-form').html()=="undefined" || jQuery('#ssf-comment-form').html()==undefined){

		grecaptcha.render('ssf-cf-rattingCaptcha', {
          'sitekey' : google_rc_key,
          'callback' : ssf_cf_verifyCallback,
          'theme' : 'white'
        });
		
	 } else {
			
		grecaptcha.render('rattingCaptcha', {
          'sitekey' : google_rc_key,
          'callback' : ssf_cf_verifyCallback,
          'theme' : 'white'
        });
		
        grecaptcha.render('ssf-cf-rattingCaptcha', {
          'sitekey' : google_rc_key,
          'callback' : ssf_cf_verifyCallback,
          'theme' : 'white'
        });
	 }
		
      };
	  
}

jQuery(document).ready(function() {
"use strict";
        jQuery("input#ssf_gdpr_check").click(function() {
            var s_checked = jQuery(this).is(':checked');
            if (s_checked) {
                jQuery('input#ssf_gdpr_check').removeClass('ssf_invalid');
            } 
        });
    });