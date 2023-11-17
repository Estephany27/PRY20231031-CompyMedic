<?php
 include("../../ssf-wp-inc/includes/ssf-wp-env.php");
 global $wpdb;
    
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
	
	
    function filterData(&$str)
    {
        $str = preg_replace("/\t/", "\\t", $str);
        $str = preg_replace("/\r?\n/", "\\n", $str);
        if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
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
    
    // file name for download
    $fileName = "Report.xls";
    
    // headers for download
    header("Content-Disposition: attachment; filename=\"$fileName\"");
    header("Content-Type: application/vnd.ms-excel");
    $data=$wpdb->get_results("SELECT ssf_wp_email,ssf_wp_store,ssf_wp_state FROM ".SSF_WP_TABLE." ORDER BY ssf_wp_state ASC ", ARRAY_A);
    $flag = false;
	$ssf_wp_state="";
    foreach($data as $row) {
        if(!$flag) {
            // display column names as first row
            //echo implode("\t", array_keys($row)) . "\n";
			echo "Email Address\t";
			echo "Name\t";
			echo "Territories\t";
			echo "\n";
            $flag = true;
        }
        // filter data
		if($ssf_wp_state!=$row['ssf_wp_state']){
			print "\n";
			$ssf_wp_state=$row['ssf_wp_state'];
		}
        array_walk($row, 'filterData');
        echo implode("\t", array_values(parseToEXPRT($row)))."\n";

    }
    exit;
?>