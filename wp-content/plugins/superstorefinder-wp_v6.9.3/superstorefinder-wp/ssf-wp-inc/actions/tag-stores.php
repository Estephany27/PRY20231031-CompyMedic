<?php if (!is_user_logged_in()) {
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

if (!empty($_POST)) {
	$ssf_wp_id=$_POST['ssf_wp_id'];
	$ssf_wp_tags=$_POST['ssf_wp_tags'];
	$act=$_POST['act'];
}

if (is_array($ssf_wp_id)==1) {
	$rplc_arr=array_fill(0, count($ssf_wp_id), "%d");
	$id_string=implode(",", array_map(array($wpdb, "prepare"), $rplc_arr, $ssf_wp_id)); 	
} else {
	$id_string=$wpdb->prepare("%d", $ssf_wp_id);
}
if ($act=="add_tag") {
		$SSfcateGory=ssf_wp_prepare_tag_string($ssf_wp_tags);
		$wpdb->query($wpdb->prepare("UPDATE ".SSF_WP_TABLE." SET ssf_wp_tags=CONCAT(IFNULL(ssf_wp_tags, ''), %s ) WHERE ssf_wp_id IN ($id_string)", ssf_comma(stripslashes($SSfcateGory)))); 
		ssf_wp_process_tags(ssf_wp_prepare_tag_string($ssf_wp_tags), "insertTags", $ssf_wp_id); 
}
elseif ($act=="remove_tag") {

	if (empty($ssf_wp_tags)) {

		$wpdb->query("UPDATE ".SSF_WP_TABLE." SET ssf_wp_tags='' WHERE ssf_wp_id IN ($id_string)");
		ssf_wp_process_tags("", "delete", $id_string);
	}
	else {		
		
		$wpdb->query($wpdb->prepare("UPDATE ".SSF_WP_TABLE." SET ssf_wp_tags=REPLACE(ssf_wp_tags, %s, '') WHERE ssf_wp_id IN ($id_string)", $ssf_wp_tags.",")); 
		$wpdb->query($wpdb->prepare("UPDATE ".SSF_WP_TABLE." SET ssf_wp_tags=REPLACE(ssf_wp_tags, %s, '') WHERE ssf_wp_id IN ($id_string)", $ssf_wp_tags."&#44;")); 
		ssf_wp_process_tags($ssf_wp_tags, "delete", $id_string); 
	}
}

if($act=="add_tag" || $act=="remove_tag"){
	global $ssf_wp_vars;
	$data_source=(isset($ssf_wp_vars['data_source'])) ? trim($ssf_wp_vars['data_source']) : 'false';
	if($data_source=='true'){
		ssf_generate_json();
	}
}
?>