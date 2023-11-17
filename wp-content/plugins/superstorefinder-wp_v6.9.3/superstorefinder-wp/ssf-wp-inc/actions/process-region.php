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

	if (!empty($_GET['delete'])) {
		if (!empty($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce'], "delete-location_".$_GET['delete'])){
			$wpdb->query($wpdb->prepare("DELETE FROM ".SSF_WP_REGION_TABLE." WHERE ssf_wp_region_id='%d'", $_GET['delete'])); 
		} 
	}
	if (!empty($_POST) && !empty($_GET['edit']) && $_POST['act']!="delete") {
		$field_value_str=""; 
		foreach ($_POST as $key=>$value) {
			if (preg_match("@\-$_GET[edit]@", $key)) {
				$key=str_replace("-$_GET[edit]", "", $key); 
				if (is_array($value)){
					$value=serialize($value); 
					$field_value_str.=$key."='$value',";
				} else {
					$field_value_str.=$key."=".$wpdb->prepare("%s", trim(ssf_comma(stripslashes($value)))).", "; 
				}
				$_POST["$key"]=$value; 
			}
		}
		
		$field_value_str=substr($field_value_str, 0, strlen($field_value_str)-2);
		$edit=$_GET['edit'];
		 
		$wpdb->query($wpdb->prepare("UPDATE ".SSF_WP_REGION_TABLE." SET ".str_replace("%", "%%", $field_value_str)." WHERE ssf_wp_region_id='%d'", $_GET['edit']));  
		print "<script>location.replace('".str_replace("&edit=$_GET[edit]", "", $_SERVER['REQUEST_URI'])."');</script>"; 
	}
	
	if (!empty($_POST['act']) && !empty($_POST['ssf_wp_region_id']) && $_POST['act']=="delete") {
			include(SSF_WP_ACTIONS_PATH."/delete-region.php");
	}?>