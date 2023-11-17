<?php
/*
    Import/Export marker - Leaflet Maps Marker Plugin
*/
//info prevent file from being accessed directly
if (basename($_SERVER['SCRIPT_FILENAME']) == 'leaflet-import-export.php') { die ("Please do not access this file directly. Thanks!<br/><a href='https://www.mapsmarker.com/go'>www.mapsmarker.com</a>"); }
 
include('inc' . DIRECTORY_SEPARATOR . 'admin-header.php'); 
global $wpdb;
$lmm_options = get_option( 'leafletmapsmarker_options' );
$action = isset($_POST['action']) ? sanitize_key($_POST['action']) : '';
?>	
	
<h1><?php _e('Import/Export','leaflet-maps-marker'); ?> <a href="<?php echo LEAFLET_WP_ADMIN_URL ?>admin.php?page=leafletmapsmarker_pro_upgrade" title="<?php esc_attr_e('This feature is available in the pro version only! Click here to find out how you can start a free 30-day-trial easily','leaflet-maps-marker') ?>"><img src="<?php echo LEAFLET_PLUGIN_URL ?>inc/img/help-pro-feature.png" /></a></h1>

<p>The import and export feature is only available in Maps Marker Pro!</p>

<p><?php echo sprintf(__('For details and tutorials about imports and exports, please visit %1s','leaflet-maps-marker'), '<a href="https://www.mapsmarker.com/import-export" target="_blank" style="text-decoration:none;">www.mapsmarker.com/import-export</a>'); ?></p>

<p>
<table>
    <tr>
        <td colspan="2"><span style="font-weight:bold;"><?php _e('Import markers','leaflet-maps-marker'); ?></span></td>
        <td style="width:50px;"></td>
        <td colspan="2"><span style="font-weight:bold;"><?php _e('Import layers','leaflet-maps-marker'); ?></span></td>
    </tr>
    <tr>
        <td style="width:35px;"><img src="<?php echo LEAFLET_PLUGIN_URL ?>inc/img/icon-import.png" width="32" height="32" alt="import"></td>
        <td>
            <form method="post">
            <input style="font-weight:bold;" type="submit" name="submit" class="submit button-primary" disabled="disabled" value="<?php esc_attr_e('prepare import','leaflet-maps-marker'); ?>" />
            </form>
        </td>
        <td></td>
        <td style="width:35px;"><img src="<?php echo LEAFLET_PLUGIN_URL ?>inc/img/icon-import.png" width="32" height="32" alt="import"></td>
        <td>
            <form method="post">
            <input style="font-weight:bold;" type="submit" name="submit" class="submit button-primary" disabled="disabled" value="<?php esc_attr_e('prepare import','leaflet-maps-marker'); ?>" />
            </form>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="font-weight:bold;"><?php _e('Export markers','leaflet-maps-marker'); ?></td>
        <td></td>
        <td colspan="2"><span style="font-weight:bold;"><?php _e('Export layers','leaflet-maps-marker'); ?></span></td>
    </tr>
    <tr>
        <td style="width:35px;"><img src="<?php echo LEAFLET_PLUGIN_URL ?>inc/img/icon-export.png" width="32" height="32" alt="export"></td>
        <td>
            <form method="post">
            <input style="font-weight:bold;" type="submit" name="submit" class="submit button-primary" disabled="disabled" value="<?php esc_attr_e('prepare export','leaflet-maps-marker'); ?>" />
            </form>
        </td>
        <td></td>
        <td style="width:35px;"><img src="<?php echo LEAFLET_PLUGIN_URL ?>inc/img/icon-export.png" width="32" height="32" alt="export"></td>
        <td>
            <form method="post">
            <input style="font-weight:bold;" type="submit" name="submit" class="submit button-primary" disabled="disabled" value="<?php esc_attr_e('prepare export','leaflet-maps-marker'); ?>" />
            </form>
        </td>
    </tr>
</table>
<!--wrap-->
<?php 
include('inc' . DIRECTORY_SEPARATOR . 'admin-footer.php');
?>