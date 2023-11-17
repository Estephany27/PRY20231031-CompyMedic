<?php
 include("../../ssf-wp-inc/includes/ssf-wp-env.php");
  global $wpdb;
  
  if (!isset($wpdb)){  include_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );  }
  
	if (!is_user_logged_in()) {
    	die("");
	} 
	$ssf_role='';
	if(!function_exists('get_ssf_current_user_role')){
	function get_ssf_current_user_role() {
		global $wp_roles;
		$current_user = wp_get_current_user();
		$roles = $current_user->roles;
		$role = array_shift($roles);
		return trim($role);
    }
	}
	$ssf_role=get_ssf_current_user_role();
	if(!isset($ssf_wp_vars['ssf_user_role'])){
		$ssf_wp_vars['ssf_user_role']='administrator';
	}
    $userRole=(trim($ssf_wp_vars['ssf_user_role'])!="")? $ssf_wp_vars['ssf_user_role'] : "administrator";
	$ex_cat = explode(",", $userRole);
    $ex_cat = array_map( 'trim', $ex_cat );
	
	if(!in_array($ssf_role,$ex_cat) && $ssf_role!='administrator'){
	die("");
    }
	
  function parseToEXPRT($htmlStr) 
{ 
$xmlStr=str_replace('&lt;','<',$htmlStr); 
$xmlStr=str_replace('&gt;','>',$xmlStr); 
$xmlStr=str_replace('&quot;','"',$xmlStr); 
$xmlStr=str_replace("&#44;","," ,$xmlStr);
$xmlStr=str_replace('&#39;',"'",$xmlStr); 
$xmlStr=str_replace('&amp;',"&",$xmlStr); 
return $xmlStr; 

} 
 
 $locales=$wpdb->get_results("SELECT * FROM ".SSF_WP_TABLE." ", ARRAY_A)  ;
//$data=array();
$content='Name,CategoriesTags,Address,City,State,Country,Zip,Phone,Fax,email,website,External URL,description,Operating Hours,Custom Field 1,Custom Field 2,Custom Field 3,Custom Field 4,Custom Field 5,Custom Field 6';
$content .="\n";
	foreach ($locales as $value) {
$content .='"'.parseToEXPRT($value["ssf_wp_store"]).'","'.parseToEXPRT($value["ssf_wp_tags"]).'","'.parseToEXPRT($value["ssf_wp_address"]).'","'.parseToEXPRT($value["ssf_wp_city"]).'","'.$value["ssf_wp_state"].'","'.$value["ssf_wp_country"].'","'.$value["ssf_wp_zip"].'","'.$value["ssf_wp_phone"].'","'.$value["ssf_wp_fax"].'","'.$value["ssf_wp_email"].'","'.$value["ssf_wp_url"].'","'.$value["ssf_wp_ext_url"].'","'.$value["ssf_wp_description"].'","'.parseToEXPRT($value["ssf_wp_hours"]).'","'.$value["ssf_wp_custom_field1"].'","'.$value["ssf_wp_custom_field2"].'","'.$value["ssf_wp_custom_field3"].'","'.$value["ssf_wp_custom_field4"].'","'.$value["ssf_wp_custom_field5"].'","'.$value["ssf_wp_custom_field6"].'"';
$content .="\n";
}

//print $content;
$file=SSF_WP_UPLOADS_PATH."/csv/Store.csv";
    @chmod($file,0666);
    $fp = fopen($file,'w');
    fwrite($fp,$content);
    fclose($fp);
    if (file_exists($file)) {
		ob_start();
        header('Content-Description: File Transfer');
        header('Content-Encoding: UTF-8');
        header('Content-type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename='.basename($file));
        
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        ob_clean();
        flush();
		echo "\xEF\xBB\xBF"; // UTF-8 BOM
        readfile($file);
        exit;
    }

?>
