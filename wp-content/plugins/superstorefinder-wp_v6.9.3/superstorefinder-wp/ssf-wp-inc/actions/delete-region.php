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

if (is_array($_POST['ssf_wp_region_id'])==1) {
	$rplc_arr=array_fill(0, count($_POST['ssf_wp_region_id']), "%d");
	$id_string=implode(",", array_map(array($wpdb, "prepare"), $rplc_arr, $_POST['ssf_wp_region_id'])); 
} else { 
	$id_string=$wpdb->prepare("%d", $_POST['ssf_wp_region_id']); 
}
$wpdb->query("DELETE FROM ".SSF_WP_REGION_TABLE." WHERE ssf_wp_region_id IN ($id_string)");
?>