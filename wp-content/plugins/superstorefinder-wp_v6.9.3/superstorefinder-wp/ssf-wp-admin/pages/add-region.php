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

if (!empty($_GET['pg']) && isset($wpdb) && $_GET['pg']=='add-region') { include_once(SSF_WP_INCLUDES_PATH."/top-nav.php"); print "<script>jQuery('.locations').removeAttr('id');</script>"; } 

if (!isset($wpdb)){ include("../../../../wp-load.php"); }
if (!defined("SSF_WP_INCLUDES_PATH")) { include("../ssf-wp-define.php"); }
if (!function_exists("ssf_wp_initialize_variables")) { include("../ssf-wp-functions.php"); }
if (defined('SSF_WP_ADDONS_PLATFORM_FILE') && file_exists(SSF_WP_ADDONS_PLATFORM_FILE)) { include_once(SSF_WP_ADDONS_PLATFORM_FILE); } //check if this inclusion is actually necessary here anymore - 3/19/14

print "<div class='wrap'>";

global $wpdb;
ssf_wp_initialize_variables();

if (!empty($_POST['ssf_wp_region_name']) && (empty($_GET['mode']) || $_GET['mode']!="pca")) {
	if (!empty($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], "add-location_single")){
		ssf_wp_add_region();
		print "<div class='ssf_wp_admin_success'>".__("Region successfully added",SSF_WP_TEXT_DOMAIN).". $view_link_region</div> <!--meta http-equiv='refresh' content='0'-->"; 
	} /*else {
		print "<div class='ssf-wp-menu-alert'>".__(" State failed to be added to the database.",SSF_WP_TEXT_DOMAIN).". $view_link</div>"; 
	}*/
}

print ssf_wp_region_form("add");

if (function_exists("do_ssf_wp_hook")) {do_ssf_wp_hook('ssf_wp_add_location_forms', 'select-top');}

print "
</div>";

include(SSF_WP_INCLUDES_PATH."/ssf-wp-footer.php");
?>