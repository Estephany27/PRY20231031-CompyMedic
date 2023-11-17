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

if ($_POST) {
	$ssf_wp_id=$_POST['ssf_wp_id'];
}
if (is_array($ssf_wp_id)==1) {
	$rplc_arr=array_fill(0, count($ssf_wp_id), "%d");
	$id_string=implode(",", array_map(array($wpdb, "prepare"), $rplc_arr, $ssf_wp_id)); 
} else { 
	$id_string=$wpdb->prepare("%d", $ssf_wp_id); 
}
$wpdb->query("DELETE FROM ".SSF_WP_TABLE." WHERE ssf_wp_id IN ($id_string)");
$imageId = explode(',', $id_string); //split string into array seperated by ', '

$imageId = explode(',', $id_string); //split string into array seperated by ', '
			

			foreach($imageId as $value) //loop over values

       {    
	   	    $dir=SSF_WP_UPLOADS_PATH."/images/".$value;
			if (is_dir($dir)){
				$images = @scandir($dir);
				foreach($images as $k=>$v):
				endforeach;
				unlink($dir.'/'.$v);
				rmdir($dir);
			}
			
			$MarkerDir=SSF_WP_UPLOADS_PATH."/images/icons/".$value;
			if (is_dir($MarkerDir)){
				$images = @scandir($MarkerDir);
				foreach($images as $k=>$v):
				endforeach;
				unlink($MarkerDir.'/'.$v);
				rmdir($MarkerDir);
			}
			
		}

ssf_wp_process_tags("", "delete", $id_string);

$data_source=(isset($ssf_wp_vars['data_source'])) ? trim($ssf_wp_vars['data_source']) : 'false';
if($data_source=='true'){
	ssf_generate_json();
}
		
?>