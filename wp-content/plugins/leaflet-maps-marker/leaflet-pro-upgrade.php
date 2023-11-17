<?php
/*
    Pro Upgrade - Leaflet Maps Marker Plugin
*/
//info prevent file from being accessed directly
if (basename($_SERVER['SCRIPT_FILENAME']) == 'leaflet-pro-upgrade.php') { die ("Please do not access this file directly. Thanks!<br/><a href='https://www.mapsmarker.com/go'>www.mapsmarker.com</a>"); }

include('inc' . DIRECTORY_SEPARATOR . 'admin-header.php');
$first_run = (isset($_GET['first_run']) ? 'true' : 'false');
$lmm_pro_readme = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'leaflet-maps-marker-pro' . DIRECTORY_SEPARATOR . 'readme.txt';
$lmm_pro_readme_four = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'maps-marker-pro' . DIRECTORY_SEPARATOR . 'readme.txt';
$action = isset($_POST['action']) ? sanitize_key($_POST['action']) : '';

if ( $action == NULL ) {
	if (file_exists($lmm_pro_readme) || (file_exists($lmm_pro_readme_four)) ) {
		echo '<h1>' . __('Upgrade to pro version','leaflet-maps-marker') . '</h1>';
		echo '<div class="error" style="padding:10px;"><strong>' . __('You already downloaded "Maps Marker Pro" to your server but did not activate the plugin yet!','leaflet-maps-marker') . '</strong></div>';
		if ( current_user_can( 'install_plugins' ) ) {
			echo sprintf(__('Please navigate to <a href="%1$s">Plugins / Installed Plugins</a> and activate the plugin "Maps Marker Pro".','leaflet-maps-marker'), LEAFLET_WP_ADMIN_URL . 'plugins.php');
		} else {
			echo sprintf(__('Please contact your administrator (%1s) to activate the plugin "Maps Marker Pro".','leaflet-maps-marker'), '<a href="mailto:' . get_bloginfo('admin_email') . '?subject=' . esc_attr__('Please activate the plugin "Maps Marker Pro"','leaflet-maps-marker') . '">' . get_bloginfo('admin_email') . '</a>' );
		}
	} else {
		$override_css = ($first_run == 'true') ? 'style="margin-top:16px;"' : 'style="margin-top:20px;"';
		echo '<div class="xpro-upgrade-logo-rtl" ' . $override_css . '><a href="https://www.mapsmarker.com" target="_blank" title="Visit www.mapsmarker.com for more details"><img src="' . LEAFLET_PLUGIN_URL . 'inc/img/logo-mapsmarker-pro.svg" alt="Pro Logo" title="Visit www.mapsmarker.com for more details" width="400" height="102" style="background:white;border-radius:5px;"/></a></div>';
		echo '<h1 style="margin:6px 0 0 0;">' . __('More power: try Maps Marker Pro for free!','leaflet-maps-marker') . '</h1>';
		echo '<form method="post"><input type="hidden" name="action" value="upgrade_to_pro_version" />';
		wp_nonce_field('pro-upgrade-nonce');
		echo '<p>' . __('Leaflet Maps Marker has been retired, meaning it will no longer be updated for new features (<a href="https://www.mapsmarker.com/v3.12.8" target="_blank">details</a>). If you’d like a faster plugin with dedicated support and new features added frequently, try Maps Marker Pro: Start a free 30-day-trial without any obligation.','leaflet-maps-marker') . ' ';
		echo '' . sprintf(__('If you want to compare the Leaflet Maps Marker and Maps Marker Pro side by side, please visit %1s.','leaflet-maps-marker'), '<a href="https://www.mapsmarker.com/comparison" target="_blank" style="text-decoration:none;">mapsmarker.com/comparison</a>') . '</p>';
		if ( current_user_can( 'install_plugins' ) ) {
			echo '<input style="font-weight:bold;" type="submit" name="submit_upgrade_to_pro_version" value="' . __('Sounds good! I will try it now','leaflet-maps-marker') . ' &raquo;" class="submit button-primary" />';
		} else {
			echo '<div class="error" style="padding:10px;"><strong>' . sprintf(__('Warning: your user does not have the capability to install new plugins - please contact your administrator (%1s)','leaflet-maps-marker'), '<a href="mailto:' . get_bloginfo('admin_email') . '?subject=' . esc_attr__('Please install the plugin "Maps Marker Pro"','leaflet-maps-marker') . '">' . get_bloginfo('admin_email') . '</a>' ) . '</strong></div>';
			echo '<input style="font-weight:bold;" type="submit" name="submit_upgrade_to_pro_version" value="' . __('Sounds good! I will try it now','leaflet-maps-marker') . ' &raquo;" class="submit button-secondary" disabled="disabled" />';
		}
		echo '</form>';
		//echo '<hr noshade size="1" style="margin-top:25px;"/><h2 style="margin-top:10px;">' . __('Highlights of Maps Marker Pro','leaflet-maps-marker') . '</h2>';
		echo '<script type="text/javascript">
				//info: toggle advanced menu items - duplicated from admin-footer.php
				jQuery("#show-advanced-menu-items-link, #hide-advanced-menu-items-link").click(function(e) {
					jQuery("#show-advanced-menu-items-link").toggle();
					jQuery("#hide-advanced-menu-items-link").toggle();
					jQuery("#advanced-menu-items").toggle();
				});
				</script>';
	}
} else {
	if (!wp_verify_nonce( $_POST['_wpnonce'], 'pro-upgrade-nonce') ) { wp_die('<br/>'.__('Security check failed - please call this function from the according admin page!','leaflet-maps-marker').''); };
	if ($action == 'upgrade_to_pro_version') {
		global $wpdb;
		$table_name_layers = $wpdb->prefix.'leafletmapsmarker_layers';
		$table_name_markers = $wpdb->prefix.'leafletmapsmarker_markers';
		$count_markers = $wpdb->get_var('SELECT count(*) FROM `'.$table_name_markers.'`');
		$count_layers = $wpdb->get_var('SELECT count(*) FROM `'.$table_name_layers.'`');
		if ( (!$count_markers) && ($count_layers == 1) ) {
			$dl = 'https://www.mapsmarker.com/download-pro'; //download latest version to skip migration (as no data with free version has been created yet)
		} else if (version_compare(phpversion(),"7.4",">")) {
			$dl = 'https://www.mapsmarker.com/download-pro'; //download latest version as 3.x is not PHP 8 compatible (no automatic migration possible)
		} else {
			//$dl = 'https://www.mapsmarker.com/upgrade-pro'; //download latest 3.x version for migrating existing data
			$dl = 'https://www.mapsmarker.com/download-pro'; //since MMP v4.25 direct migration is possible
		}
		include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		add_filter( 'https_ssl_verify', '__return_false' ); //info: otherwise SSL error on localhost installs.
		add_filter( 'https_local_ssl_verify', '__return_false' ); //info: not sure if needed, added to be sure
		$upgrader = new Plugin_Upgrader( new Plugin_Upgrader_Skin() );
		$upgrader->install( $dl );
		//info: check if download was successful
		if ( (!$count_markers) && ($count_layers == 1) ) {
			$lmm_pro_readme = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'maps-marker-pro' . DIRECTORY_SEPARATOR . 'readme.txt';
		} else {
			$lmm_pro_readme = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'leaflet-maps-marker-pro' . DIRECTORY_SEPARATOR . 'readme.txt';
		}
		if (file_exists($lmm_pro_readme)) {
			echo '<p>' . __('Please activate the plugin by clicking the link above','leaflet-maps-marker') . '</p>';
		} else {
			echo '<p>' . sprintf(__('The pro plugin package could not be downloaded automatically. Please download the plugin from <a href="%1s">%2s</a> and upload it to the directory /wp-content/plugins on your server manually','leaflet-maps-marker'), $dl, $dl) . '</p>';
		}
	}
}
?>
</div>
<!--wrap-->