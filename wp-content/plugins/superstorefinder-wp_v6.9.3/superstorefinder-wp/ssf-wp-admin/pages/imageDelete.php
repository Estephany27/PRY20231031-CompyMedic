<?php include("../../ssf-wp-inc/includes/ssf-wp-env.php");

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

if(isset($_POST['img']))
{
	 $dir=SSF_WP_UPLOADS_PATH."/images/icons/";
	 if($_POST['img']=='a')
	 {
		unlink($dir.'custom-marker.png');
	 }
	 else if($_POST['img']=='b')
	 {
		unlink($dir.'custom-marker-active.png'); 
	 }else{
		$dir=SSF_WP_UPLOADS_PATH.'/images/'.$_POST['img'];
		if (is_dir($dir)){
			$images = @scandir($dir);
			foreach($images as $k=>$v):
			endforeach;
			unlink($dir.'/'.$v);
			rmdir($dir);
		}
		 
	 }
}

if(isset($_POST['img_mrk']))
 {
 
   $MarkerDir=SSF_WP_UPLOADS_PATH.'/images/icons/'.$_POST['img_mrk'];
   
			if (is_dir($MarkerDir)){
				$images = @scandir($MarkerDir);
				foreach($images as $k=>$v):
				endforeach;
				unlink($MarkerDir.'/'.$v);
				rmdir($MarkerDir);
			}
 
 }

 if(isset($_POST['cat_mrk']))
 {
	$fileName=$_POST['cat_mrk'];
	$dir_marker=SSF_WP_UPLOADS_PATH."/images/sprites/markers/".$fileName.".png";
		if (file_exists($dir_marker)) {
				unlink($dir_marker);
				print "sucess";
		}
 }
 
$data = array('success' => 'success');
echo json_encode($data);
?>
