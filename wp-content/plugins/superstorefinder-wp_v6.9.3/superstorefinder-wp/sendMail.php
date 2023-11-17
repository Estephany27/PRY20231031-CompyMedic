<?php
include("ssf-wp-inc/includes/ssf-wp-env.php");
$google_rc_key=$_POST['google_rc_key'];

if ((isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']))) {
if (strtolower(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST)) != strtolower($_SERVER['HTTP_HOST'])) {
// referer not from the same domain
die("POST is not from the same domain");
}
}

if(!isset($_POST['name']) && !isset($_POST['phone']) && !isset($_POST['message'])){
	die("Invalid Data");
}

if($_POST['name']=='' || $_POST['phone']=='' || $_POST['message']==''){
	die("Invalid Data");
}

if($google_rc_key!=""){
	
$ssfrecaptcha=$_POST['ssfgrecaptcharesponse'];
if(!empty($ssfrecaptcha))
{
	
$name=$_POST['name'];
$phone=$_POST['phone'];
$email=$_POST['email'];
$message=$_POST['message'];
$name_lbl  = (isset($_POST['name_lbl']) && !empty($_POST['name_lbl'])) ? $_POST['name_lbl'] : 'Name';
$email_lbl = (isset($_POST['email_lbl']) && !empty($_POST['email_lbl'])) ? $_POST['email_lbl'] : 'Email';
$msg_lbl   = (isset($_POST['msg_lbl']) && !empty($_POST['msg_lbl'])) ? $_POST['msg_lbl'] : 'Message';
$phone_lbl = (isset($_POST['phone_lbl']) && !empty($_POST['phone_lbl'])) ? $_POST['phone_lbl'] : 'Phone';

$to = $_POST['rcvEmail'];
$subject = $_POST['subject']. ' enquiry';
$body = $name_lbl.': '.$name.'<br/>';
$body .= $email_lbl.': '.$email.'<br/>';
if(!empty($phone)){
	$body .= $phone_lbl.': '.$phone.'<br/>';
}
$body .= $msg_lbl.': '.$message.'<br/>';
$headers[] = 'Content-Type: text/html; charset=UTF-8';
$headers[]   = 'Reply-To: '.$name.' <'.$email.'>';
wp_mail( $to, $subject, $body, $headers );

}

} else {
	
$name=$_POST['name'];
$phone=$_POST['phone'];
$email=$_POST['email'];
$message=$_POST['message'];
$name_lbl  = (isset($_POST['name_lbl']) && !empty($_POST['name_lbl'])) ? $_POST['name_lbl'] : 'Name';
$email_lbl = (isset($_POST['email_lbl']) && !empty($_POST['email_lbl'])) ? $_POST['email_lbl'] : 'Email';
$msg_lbl   = (isset($_POST['msg_lbl']) && !empty($_POST['msg_lbl'])) ? $_POST['msg_lbl'] : 'Message';
$phone_lbl = (isset($_POST['phone_lbl']) && !empty($_POST['phone_lbl'])) ? $_POST['phone_lbl'] : 'Phone';

$to = $_POST['rcvEmail'];
$subject = $_POST['subject']. ' enquiry';
$body = $name_lbl.': '.$name.'<br/>';
$body .= $email_lbl.': '.$email.'<br/>';
if(!empty($phone)){
	$body .= $phone_lbl.': '.$phone.'<br/>';
}
$body .= $msg_lbl.': '.$message.'<br/>';
$headers[] = 'Content-Type: text/html; charset=UTF-8';
$headers[]   = 'Reply-To: '.$name.' <'.$email.'>';
wp_mail( $to, $subject, $body, $headers );

}



?>