<?php
/*
    List all layers - Leaflet Maps Marker Plugin
*/
//info prevent file from being accessed directly
if (basename($_SERVER['SCRIPT_FILENAME']) == 'leaflet-list-layers.php') { die ("Please do not access this file directly. Thanks!<br/><a href='https://www.mapsmarker.com/go'>www.mapsmarker.com</a>"); }

include('inc' . DIRECTORY_SEPARATOR . 'admin-header.php'); ?>
<h1><?php _e('List all layers','leaflet-maps-marker') ?></h1>
<?php
global $wpdb;
$lmm_options = get_option( 'leafletmapsmarker_options' );
//info: security check if input variable is valid
$columnsort_values = array('id','multi_layer_map','name','m.panel','zoom','basemap','createdon','createdby','updatedon','updatedby','controlbox');
$columnsort_input = isset($_GET['orderby']) ? esc_sql($_GET['orderby']) : $lmm_options[ 'misc_layer_listing_sort_order_by' ];
$columnsort = (in_array($columnsort_input, $columnsort_values)) ? $columnsort_input : $lmm_options[ 'misc_layer_listing_sort_order_by' ];
//info: security check if input variable is valid
$columnsortorder_values = array('asc','desc','ASC','DESC');
$columnsortorder_input = isset($_GET['order']) ? esc_sql($_GET['order']) : $lmm_options[ 'misc_layer_listing_sort_sort_order' ];
$columnsortorder = (in_array($columnsortorder_input, $columnsortorder_values)) ? $columnsortorder_input : $lmm_options[ 'misc_layer_listing_sort_sort_order' ];
$table_name_layers = $wpdb->prefix.'leafletmapsmarker_layers';
$table_name_markers = $wpdb->prefix.'leafletmapsmarker_markers';

$action = isset($_POST['action']) ? sanitize_key($_POST['action']) : (isset($_GET['action']) ? sanitize_key($_GET['action']) : '');
$searchtext = isset($_POST['searchtext']) ? '%' .esc_sql($_POST['searchtext']) . '%' : (isset($_GET['searchtext']) ? '%' . esc_sql($_GET['searchtext']) : '') . '%';
if ($action == 'search') {
	$layersearchnonce = isset($_POST['_wpnonce']) ? esc_attr__($_POST['_wpnonce']) : '';
	if (! wp_verify_nonce($layersearchnonce, 'layersearch-nonce') ) die('<br/>'.__('Security check failed - please call this function from the according admin page!','leaflet-maps-marker').'');
	$lcount = intval($wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM `$table_name_layers` WHERE id like '%s' OR name like '%s' OR address like '%s'", $searchtext, $searchtext, $searchtext)));
	$layerlist = $wpdb->get_results( $wpdb->prepare("SELECT * FROM `$table_name_layers` WHERE id like '%s' OR name like '%s' OR address like '%s' order by $columnsort $columnsortorder", $searchtext, $searchtext, $searchtext), ARRAY_A);
} else {
	$layerlist = $wpdb->get_results( "SELECT * FROM `$table_name_layers` WHERE `id`>0 order by `$columnsort` $columnsortorder", ARRAY_A );
	$lcount = intval($wpdb->get_var('SELECT COUNT(*)-1 FROM '.$table_name_layers));
}
?>
<div style="float:right;">
<?php $nonce= wp_create_nonce  ('layersearch-nonce'); ?>
	<form method="post">
		<?php wp_nonce_field('layersearch-nonce'); ?>
		<input type="hidden" name="action" value="search" />
		<input type="text" id="searchtext" name="searchtext" value="<?php echo (isset($_POST['searchtext']) != NULL) ? htmlspecialchars(stripslashes($_POST['searchtext'])) : "" ?>"/>
		<input type="submit" class="button" name="searchsubmit" value="<?php _e('Search layers', 'leaflet-maps-marker') ?>"/>
	</form>
	<?php echo $showall = (isset($_POST['searchtext']) != NULL) ? "<a style=\"text-decoration:none;\" href=\"" . LEAFLET_WP_ADMIN_URL . "admin.php?page=leafletmapsmarker_layers\">" . __('list all layers','leaflet-maps-marker') . "</a>" : ""; ?>
</div>

<div style="display:inline;">
<p>
<?php _e('Total','leaflet-maps-marker') ?>: <?php echo $lcount; ?> <?php  echo $lcount_singular_plural = ($lcount == 1) ? __('layer','leaflet-maps-marker') : __('layers','leaflet-maps-marker'); ?>
</p>
<?php
$getorder = isset($_GET['order']) ? $_GET['order'] : $lmm_options[ 'misc_layer_listing_sort_sort_order' ];
if ($getorder == 'asc') { $sortorder = 'desc'; } else { $sortorder= 'asc'; };
if ($getorder == 'asc') { $sortordericon = 'asc'; } else { $sortordericon = 'desc'; };
?>
<table cellspacing="0" id="list-layers" class="wp-list-table widefat fixed">
  <thead>
  <tr>
	<th class="manage-column column-cb check-column" scope="col"><input type="checkbox" disabled="disabled"></th>
    <th class="manage-column column-id before_primary sortable <?php echo $sortordericon; ?>" id="id" scope="col"><a href="<?php echo LEAFLET_WP_ADMIN_URL; ?>admin.php?page=leafletmapsmarker_layers&orderby=id&order=<?php echo $sortorder; ?>"><span>ID</span><span class="sorting-indicator"></span></a></th>
    <th class="manage-column column-type before_primary sortable <?php echo $sortordericon; ?>" id="type" scope="col"><a href="<?php echo LEAFLET_WP_ADMIN_URL; ?>admin.php?page=leafletmapsmarker_layers&orderby=multi_layer_map&order=<?php echo $sortorder; ?>"><span><?php _e('Type', 'leaflet-maps-marker') ?></span><span class="sorting-indicator"></span></a></th>
    <th class="manage-column column-layername column-primary sortable <?php echo $sortordericon; ?>" id="name" scope="col"><a href="<?php echo LEAFLET_WP_ADMIN_URL; ?>admin.php?page=leafletmapsmarker_layers&orderby=name&order=<?php echo $sortorder; ?>"><span><?php _e('Name', 'leaflet-maps-marker') ?></span><span class="sorting-indicator"></span></a></th>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_address' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_address' ] == 1 )) { ?>
	<th class="manage-column column-address" scope="col"><?php _e('Location', 'leaflet-maps-marker') ?></th><?php } ?>
    <th class="manage-column column-count" scope="col">#&nbsp;<?php _e('Markers', 'leaflet-maps-marker') ?></th>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_layercenter' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_layercenter' ] == 1 )) { ?>
	<th class="manage-column column-coords" scope="col"><?php _e('Layer center', 'leaflet-maps-marker') ?></th><?php } ?>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_mapsize' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_mapsize' ] == 1 )) { ?>
	<th class="manage-column column-mapsize" scope="col"><?php _e('Map size','leaflet-maps-marker') ?></th><?php } ?>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_panelstatus' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_panelstatus' ] == 1 )) { ?>
	<th class="manage-column column-openpopup"><a href="<?php echo LEAFLET_WP_ADMIN_URL; ?>admin.php?page=leafletmapsmarker_layers&orderby=m.panel&order=<?php echo $sortorder; ?>"><span><?php _e('Panel status', 'leaflet-maps-marker') ?></span><span class="sorting-indicator"></span></a></th><?php } ?>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_zoom' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_zoom' ] == 1 )) { ?>
	<th class="manage-column column-zoom" scope="col"><a href="<?php echo LEAFLET_WP_ADMIN_URL; ?>admin.php?page=leafletmapsmarker_layers&orderby=zoom&order=<?php echo $sortorder; ?>"><span><?php _e('Zoom', 'leaflet-maps-marker') ?></span><span class="sorting-indicator"></span></a></th><?php } ?>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_basemap' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_basemap' ] == 1 )) { ?>
	<th class="manage-column column-basemap" scope="col"><a href="<?php echo LEAFLET_WP_ADMIN_URL; ?>admin.php?page=leafletmapsmarker_layers&orderby=basemap&order=<?php echo $sortorder; ?>"><span><?php _e('Basemap', 'leaflet-maps-marker') ?></span><span class="sorting-indicator"></span></a></th><?php } ?>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_createdby' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_createdby' ] == 1 )) { ?>
	<th class="manage-column column-createdby" scope="col"><a href="<?php echo LEAFLET_WP_ADMIN_URL; ?>admin.php?page=leafletmapsmarker_layers&orderby=createdby&order=<?php echo $sortorder; ?>"><span><?php _e('Created by', 'leaflet-maps-marker') ?></span><span class="sorting-indicator"></span></a></th><?php } ?>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_createdon' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_createdon' ] == 1 )) { ?>
	<th class="manage-column column-createdon" scope="col"><a href="<?php echo LEAFLET_WP_ADMIN_URL; ?>admin.php?page=leafletmapsmarker_layers&orderby=createdon&order=<?php echo $sortorder; ?>"><span><?php _e('Created on', 'leaflet-maps-marker') ?></span><span class="sorting-indicator"></span></a></th><?php } ?>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_updatedby' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_updatedby' ] == 1 )) { ?>
	<th class="manage-column column-updatedby" scope="col"><a href="<?php echo LEAFLET_WP_ADMIN_URL; ?>admin.php?page=leafletmapsmarker_layers&orderby=updatedby&order=<?php echo $sortorder; ?>"><span><?php _e('Updated by', 'leaflet-maps-marker') ?></span><span class="sorting-indicator"></span></a></th><?php } ?>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_updatedon' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_updatedon' ] == 1 )) { ?>
	<th class="manage-column column-updatedon" scope="col"><a href="<?php echo LEAFLET_WP_ADMIN_URL; ?>admin.php?page=leafletmapsmarker_layers&orderby=updatedon&order=<?php echo $sortorder; ?>"><span><?php _e('Updated on', 'leaflet-maps-marker') ?></span><span class="sorting-indicator"></span></a></th><?php } ?>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_controlbox' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_controlbox' ] == 1 )) { ?>
	<th class="manage-column column-code" scope="col"><a href="<?php echo LEAFLET_WP_ADMIN_URL; ?>admin.php?page=leafletmapsmarker_layers&orderby=controlbox&order=<?php echo $sortorder; ?>"><span><?php _e('Controlbox status', 'leaflet-maps-marker') ?></span><span class="sorting-indicator"></span></a></th><?php } ?>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_used_in_content' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_used_in_content' ] == 1 )) { ?>
	<th class="manage-column column-usedincontent" scope="col"><?php _e('Used in content','leaflet-maps-marker') ?></th><?php } ?>	
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_shortcode' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_shortcode' ] == 1 )) { ?>
	<th class="manage-column column-code" scope="col"><?php _e('Shortcode', 'leaflet-maps-marker') ?></th><?php } ?>
</tr>
  </thead>
  <tfoot>
  <tr>
	<th class="manage-column column-cb check-column" scope="col"><input type="checkbox" disabled="disabled"></th>
    <th class="manage-column column-id before_primary sortable <?php echo $sortordericon; ?>" scope="col"><a href="<?php echo LEAFLET_WP_ADMIN_URL; ?>admin.php?page=leafletmapsmarker_layers&orderby=id&order=<?php echo $sortorder; ?>"><span>ID</span><span class="sorting-indicator"></span></a></th>
    <th class="manage-column column-type before_primary sortable <?php echo $sortordericon; ?>" id="type" scope="col"><a href="<?php echo LEAFLET_WP_ADMIN_URL; ?>admin.php?page=leafletmapsmarker_layers&orderby=multi_layer_map&order=<?php echo $sortorder; ?>"><span><?php _e('Type', 'leaflet-maps-marker') ?></span><span class="sorting-indicator"></span></a></th>
    <th class="manage-column column-layername column-primary sortable <?php echo $sortordericon; ?>" scope="col"><a href="<?php echo LEAFLET_WP_ADMIN_URL; ?>admin.php?page=leafletmapsmarker_layers&orderby=name&order=<?php echo $sortorder; ?>"><span><?php _e('Name', 'leaflet-maps-marker') ?></span><span class="sorting-indicator"></span></a></th>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_address' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_address' ] == 1 )) { ?>
	<th class="manage-column column-address" scope="col"><?php _e('Location', 'leaflet-maps-marker') ?></th><?php } ?>
    <th class="manage-column column-count" scope="col">#&nbsp;<?php _e('Markers', 'leaflet-maps-marker') ?></th>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_layercenter' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_layercenter' ] == 1 )) { ?>
	<th class="manage-column column-coords" scope="col"><?php _e('Layer center', 'leaflet-maps-marker') ?></th><?php } ?>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_mapsize' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_mapsize' ] == 1 )) { ?>
	<th class="manage-column column-mapsize" scope="col"><?php _e('Map size','leaflet-maps-marker') ?></th><?php } ?>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_panelstatus' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_panelstatus' ] == 1 )) { ?>
	<th class="manage-column column-openpopup"><a href="<?php echo LEAFLET_WP_ADMIN_URL; ?>admin.php?page=leafletmapsmarker_layers&orderby=m.panel&order=<?php echo $sortorder; ?>"><span><?php _e('Panel status', 'leaflet-maps-marker') ?></span><span class="sorting-indicator"></span></a></th><?php } ?>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_zoom' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_zoom' ] == 1 )) { ?>
	<th class="manage-column column-zoom" scope="col"><a href="<?php echo LEAFLET_WP_ADMIN_URL; ?>admin.php?page=leafletmapsmarker_layers&orderby=zoom&order=<?php echo $sortorder; ?>"><span><?php _e('Zoom', 'leaflet-maps-marker') ?></span><span class="sorting-indicator"></span></a></th><?php } ?>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_basemap' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_basemap' ] == 1 )) { ?>
	<th class="manage-column column-basemap" scope="col"><a href="<?php echo LEAFLET_WP_ADMIN_URL; ?>admin.php?page=leafletmapsmarker_layers&orderby=basemap&order=<?php echo $sortorder; ?>"><span><?php _e('Basemap', 'leaflet-maps-marker') ?></span><span class="sorting-indicator"></span></a></th><?php } ?>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_createdby' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_createdby' ] == 1 )) { ?>
	<th class="manage-column column-createdby" scope="col"><a href="<?php echo LEAFLET_WP_ADMIN_URL; ?>admin.php?page=leafletmapsmarker_layers&orderby=createdby&order=<?php echo $sortorder; ?>"><span><?php _e('Created by', 'leaflet-maps-marker') ?></span><span class="sorting-indicator"></span></a></th><?php } ?>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_createdon' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_createdon' ] == 1 )) { ?>
	<th class="manage-column column-createdon" scope="col"><a href="<?php echo LEAFLET_WP_ADMIN_URL; ?>admin.php?page=leafletmapsmarker_layers&orderby=createdon&order=<?php echo $sortorder; ?>"><span><?php _e('Created on', 'leaflet-maps-marker') ?></span><span class="sorting-indicator"></span></a></th><?php } ?>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_updatedby' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_updatedby' ] == 1 )) { ?>
	<th class="manage-column column-updatedby" scope="col"><a href="<?php echo LEAFLET_WP_ADMIN_URL; ?>admin.php?page=leafletmapsmarker_layers&orderby=updatedby&order=<?php echo $sortorder; ?>"><span><?php _e('Updated by', 'leaflet-maps-marker') ?></span><span class="sorting-indicator"></span></a></th><?php } ?>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_updatedon' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_updatedon' ] == 1 )) { ?>
	<th class="manage-column column-updatedon" scope="col"><a href="<?php echo LEAFLET_WP_ADMIN_URL; ?>admin.php?page=leafletmapsmarker_layers&orderby=updatedon&order=<?php echo $sortorder; ?>"><span><?php _e('Updated on', 'leaflet-maps-marker') ?></span><span class="sorting-indicator"></span></a></th><?php } ?>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_controlbox' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_controlbox' ] == 1 )) { ?>
	<th class="manage-column column-code" scope="col"><a href="<?php echo LEAFLET_WP_ADMIN_URL; ?>admin.php?page=leafletmapsmarker_layers&orderby=controlbox&order=<?php echo $sortorder; ?>"><span><?php _e('Controlbox status', 'leaflet-maps-marker') ?></span><span class="sorting-indicator"></span></a></th><?php } ?>
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_used_in_content' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_used_in_content' ] == 1 )) { ?>
	<th class="manage-column column-usedincontent" scope="col"><?php _e('Used in content','leaflet-maps-marker') ?></th><?php } ?>	
	<?php if ((isset($lmm_options[ 'misc_layer_listing_columns_shortcode' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_shortcode' ] == 1 )) { ?>
	<th class="manage-column column-code" scope="col"><?php _e('Shortcode', 'leaflet-maps-marker') ?></th><?php } ?>
</tr>
  </tfoot>
  <tbody id="the-list">
<?php
  $layernonce = wp_create_nonce('layer-nonce'); //for delete-links
  if (count($layerlist) < 1)
    echo '<tr><td colspan="7">' . __('No layer created yet', 'leaflet-maps-marker') . '</td></tr>';
  else
    foreach ($layerlist as $row)
		{
		$markercount = 0; //info: needed for multi-layer-map count-bug
		if (current_user_can( $lmm_options[ 'capabilities_delete' ])) {
			$delete_link_layer = '<div style="float:right;"><a style="color:red;" onclick="if ( confirm( \'' . esc_attr__('Do you really want to delete this layer?','leaflet-maps-marker') . ' (ID ' . $row['id'] . ')\' ) ) { return true;}return false;" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_layer&action=delete&id=' . $row['id'] . '&_wpnonce=' . $layernonce . '">' . __('delete layer','leaflet-maps-marker') . '</a></div>';
		} else {
			$delete_link_layer = '';
		}
		$column_address = ((isset($lmm_options[ 'misc_layer_listing_columns_address' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_address' ] == 1 )) ? '<td class="lmm-border" data-colname="'.esc_attr__('Location','leaflet-maps-marker').'">' . $row['address'] . '</td>' : '';
		if ($row['multi_layer_map'] == 0) {
			$markercount = $wpdb->get_var('SELECT count(*) FROM `'.$table_name_layers.'` as l INNER JOIN `'.$table_name_markers.'` AS m ON l.id=m.layer WHERE l.id='.$row['id']);
		} else 	if ( ($row['multi_layer_map'] == 1) && ( $row['multi_layer_map_list'] == 'all' ) ) {
			$markercount = intval($wpdb->get_var('SELECT COUNT(*) FROM '.$table_name_markers));
		} else 	if ( ($row['multi_layer_map'] == 1) && ( $row['multi_layer_map_list'] != NULL ) && ($row['multi_layer_map_list'] != 'all') ) {
			$multi_layer_map_list_exploded = explode(",", $wpdb->get_var('SELECT l.multi_layer_map_list FROM `'.$table_name_layers.'` as l WHERE l.id='.$row['id']));
			foreach ($multi_layer_map_list_exploded as $mlmrowcount){
				$mlm_count_temp[$mlmrowcount] = $wpdb->get_var('SELECT count(*) FROM `'.$table_name_layers.'` as l INNER JOIN `'.$table_name_markers.'` AS m ON l.id=m.layer WHERE m.layer='.$mlmrowcount);
				$markercount = $markercount + $mlm_count_temp[$mlmrowcount];
			}
		} else 	if ( ($row['multi_layer_map'] == 1) && ( $row['multi_layer_map_list'] == NULL ) ) {
			$markercount = 0;
		}
		$multi_layer_map_type = ($row['multi_layer_map'] == 0) ? '&nbsp;&nbsp;<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-layer.png" width="16" height="16" title="' . esc_attr__('single layer map','leaflet-maps-marker') . '" />' : '&nbsp;&nbsp;<img src="' . LEAFLET_PLUGIN_URL . 'inc/img/icon-multi_layer_map.png" width="16" height="16" title="' . esc_attr__('multi layer map','leaflet-maps-marker') . '" />';
	    $openpanelstatus = ($row['panel'] == 1) ? __('visible','leaflet-maps-marker') : __('hidden','leaflet-maps-marker');
	 	if ($row['controlbox'] == 0) { $controlboxstatus = __('hidden','leaflet-maps-marker'); } else if ($row['controlbox'] == 1) { $controlboxstatus = __('collapsed (except on mobiles)','leaflet-maps-marker'); } else if ($row['controlbox'] == 2) { $controlboxstatus = __('expanded','leaflet-maps-marker'); };

		 //info: set column display variables - need for for-each
		 $column_layercenter = ((isset($lmm_options[ 'misc_layer_listing_columns_layercenter' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_layercenter' ] == 1 )) ? '<td class="lmm-border" data-colname="'.esc_attr__('Coordinates','leaflet-maps-marker').'">Lat: ' . $row['layerviewlat'] . '<br/>Lon: ' . $row['layerviewlon'] . '</td>' : '';
		 $column_mapsize = ((isset($lmm_options[ 'misc_layer_listing_columns_mapsize' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_mapsize' ] == 1 )) ? '<td class="lmm-border" data-colname="'.esc_attr__('Map size','leaflet-maps-marker').'">' . __('Width','leaflet-maps-marker') . ': '.$row['mapwidth'].$row['mapwidthunit'].'<br/>' . __('Height','leaflet-maps-marker') . ': '.$row['mapheight'].'px</td>' : '';
	     $column_panelstatus = ((isset($lmm_options[ 'misc_layer_listing_columns_panelstatus' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_panelstatus' ] == 1 )) ?
'<td class="lmm-border" data-colname="'.esc_attr__('Panel status','leaflet-maps-marker').'">' . $openpanelstatus . '</td>' : '';
		 $column_zoom = ((isset($lmm_options[ 'misc_layer_listing_columns_zoom' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_zoom' ] == 1 )) ? '<td style="text-align:center;" class="lmm-border centeralize" data-colname="'.esc_attr__('Zoom','leaflet-maps-marker').'">' . $row['layerzoom'] . '</td>' : '';
		 $column_controlbox = ((isset($lmm_options[ 'misc_layer_listing_columns_controlbox' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_controlbox' ] == 1 )) ? '<td style="text-align:center;" class="lmm-border centeralize" data-colname="'.esc_attr__('Controlbox','leaflet-maps-marker').'">' . $controlboxstatus . '</td>' : '';
		 //info: workaround - select shortcode on input focus doesnt work on iOS
		 global $wp_version;
		 if ( version_compare( $wp_version, '3.4', '>=' ) ) {
			 $is_ios = wp_is_mobile() && preg_match( '/iPad|iPod|iPhone/', $_SERVER['HTTP_USER_AGENT'] );
			 $shortcode_select = ( $is_ios ) ? '' : 'onfocus="this.select();" readonly="readonly"';
		 } else {
			 $shortcode_select = '';
		 }
 		 $column_shortcode = ((isset($lmm_options[ 'misc_layer_listing_columns_shortcode' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_shortcode' ] == 1 )) ? '<td class="lmm-border" data-colname="'.esc_attr__('Shortcode','leaflet-maps-marker').'"><input ' . $shortcode_select . ' style="width:95%;background:#f3efef;" type="text" value="[' . htmlspecialchars($lmm_options[ 'shortcode' ]) . ' layer=&quot;' . $row['id'] . '&quot;]"></td>' : '';
		 $column_basemap = ((isset($lmm_options[ 'misc_layer_listing_columns_basemap' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_basemap' ] == 1 )) ? '<td class="lmm-border" data-colname="'.esc_attr__('Basemap','leaflet-maps-marker').'">' . $row['basemap'] . '</td>' : '';
		 $column_createdby = ((isset($lmm_options[ 'misc_layer_listing_columns_createdby' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_createdby' ] == 1 )) ? '<td class="lmm-border" data-colname="'.esc_attr__('Created by','leaflet-maps-marker').'">' . esc_html($row['createdby']) . '</td>' : '';
		 $column_createdon = ((isset($lmm_options[ 'misc_layer_listing_columns_createdon' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_createdon' ] == 1 )) ? '<td class="lmm-border" data-colname="'.esc_attr__('Created on','leaflet-maps-marker').'">' . $row['createdon'] . '</td>' : '';
		 $column_updatedby = ((isset($lmm_options[ 'misc_layer_listing_columns_updatedby' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_updatedby' ] == 1 )) ? '<td class="lmm-border" data-colname="'.esc_attr__('Updated by','leaflet-maps-marker').'">' . esc_html($row['updatedby']) . '</td>' : '';
		 $column_updatedon = ((isset($lmm_options[ 'misc_layer_listing_columns_updatedon' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_updatedon' ] == 1 )) ? '<td class="lmm-border" data-colname="'.esc_attr__('Updated on','leaflet-maps-marker').'">' . $row['updatedon'] . '</td>' : '';
		 $add_new_marker_to_layer = ( $row['multi_layer_map'] == 0 ) ? '&nbsp;&nbsp;|&nbsp;&nbsp;<a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_marker&addtoLayer=' . $row['id'] . '&Layername=' . urlencode(stripslashes($row['name'])) . '" style="text-decoration:none;">' . __('add new marker to this layer','leaflet-maps-marker') . '</a>' : '';
		echo '<tr valign="middle" class="alternate" id="link-' . $row['id'] . '">
		<th class="lmm-border check-column" scope="row"><input type="checkbox" value="' . $row['id'] . '" name="checkedlayers[]" disabled="disabled"></th>
		<td class="lmm-border before_primary">'.$row['id'].'</td>
		<td class="lmm-border before_primary">'.$multi_layer_map_type.'</td>
		<td class="lmm-border column-primary"><strong><a title="' . esc_attr__('Edit', 'leaflet-maps-marker') . ' &laquo;' . htmlspecialchars($row['name']) . '&raquo;" href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_layer&id=' . $row['id'] . '" class="row-title">' . stripslashes(htmlspecialchars($row['name'])) . '</a></strong><br><div class="row-actions"><a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_layer&id=' . $row['id'] . '">' . __('edit layer','leaflet-maps-marker') . '</a> | <span style="color:#ccc;" title="' . esc_attr__('Feature available in pro version only','leaflet-maps-marker') . '">' . __('duplicate','leaflet-maps-marker') . '</span>'. $add_new_marker_to_layer . ' | <span style="color:#ccc;" title="' . esc_attr__('Feature available in pro version only','leaflet-maps-marker') . '">' . __('translate','leaflet-maps-marker') . '</span>' . $delete_link_layer . '</div> <button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button></td>
		  ' . $column_address . '
		<td style="text-align:center;" class="lmm-border centeralize" data-colname="'.esc_attr__('# Markers', 'leaflet-maps-marker').'"><a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_layer&id=' . $row['id'] . '#assigned_markers" title="' . esc_attr__('show markers assigned to this layer','leaflet-maps-marker') . '">'.$markercount.'</a></td>
		  ' . $column_layercenter . '
		  ' . $column_mapsize . '
		  ' . $column_panelstatus . '
		  ' . $column_zoom . '
		  ' . $column_basemap . '
		  ' . $column_createdby . '
		  ' . $column_createdon . '
		  ' . $column_updatedby . '
		  ' . $column_updatedon . '
		  ' . $column_controlbox;
		  echo ((isset($lmm_options[ 'misc_layer_listing_columns_used_in_content' ] ) == TRUE ) && ( $lmm_options[ 'misc_layer_listing_columns_used_in_content' ] == 1 )) ? '<td class="lmm-border" style="text-align:center;"><a href="' . LEAFLET_WP_ADMIN_URL . 'admin.php?page=leafletmapsmarker_pro_upgrade" title="' . esc_attr__('This feature is available in the pro version only! Click here to find out how you can start a free 30-day-trial easily','leaflet-maps-marker') . '"><img src="'. LEAFLET_PLUGIN_URL .'inc/img/help-pro-feature.png" width="65" height="15" /></a></td>' : '';
		  echo $column_shortcode . '
		</tr>';
		}
?>
  </tbody>
</table>

<table cellspacing="0" style="width:auto;margin-top:20px;" class="wp-list-table widefat fixed bookmarks">
<tr><td>
<p><b><?php _e('Bulk actions for selected layers','leaflet-maps-marker') ?></b> <a href="<?php echo LEAFLET_WP_ADMIN_URL ?>admin.php?page=leafletmapsmarker_pro_upgrade" title="<?php esc_attr_e('This feature is available in the pro version only! Click here to find out how you can start a free 30-day-trial easily','leaflet-maps-marker') ; ?>"><img src="<?php  echo LEAFLET_PLUGIN_URL ?>inc/img/help-pro-feature.png" width="70" height="15" /></a></p>
<p><input type="radio" id="duplicateselected" name="bulkactions-layers" value="duplicateselected" disabled="disabled" /> <label for="duplicateselected"><?php _e('duplicate layer(s) only','leaflet-maps-marker') ?></label></p>
<p><input type="radio" id="duplicatelayerandmarkers" name="bulkactions-layers" value="duplicatelayerandmarkers" disabled="disabled"/> <label for="duplicatelayerandmarkers"><?php _e('duplicate layer(s) and assigned markers','leaflet-maps-marker') ?></label></p>
<p><input type="radio" id="deleteselected" name="bulkactions-layers" value="deleteselected" disabled="disabled" /> <label for="deleteselected"><?php _e('delete layer(s) and assigned markers','leaflet-maps-marker') ?></label></p>
<?php 
$layerlist = $wpdb->get_results('SELECT * FROM `'.$table_name_layers.'` WHERE `id` > 0 AND `multi_layer_map` = 0', ARRAY_A); 
?>
<input type="radio" id="deleteassignselected" name="bulkactions-layers" value="deleteassignselected" disabled="disabled" /> <label for="deleteassignselected"><?php _e('delete layer(s) and assign markers to the following layer:','leaflet-maps-marker') ?></label>
<select id="layer" name="layer">
<option value="0"><?php _e('unassigned','leaflet-maps-marker') ?></option>
<?php
	foreach ($layerlist as $row)
	echo '<option value="' . $row['id'] . '" disabled="disabled">' . stripslashes(htmlspecialchars($row['name'])) . ' (ID ' . $row['id'] . ')</option>';
?>
</select><br/>
<input class="button-secondary" type="submit" value="<?php _e('submit', 'leaflet-maps-marker') ?>" style="margin: 0 0 5px 18px;" disabled="disabled" />
</td></tr></table>
</form>

<script type="text/javascript">
//info: show all API links on click on simplified editor
(function($) {
	$('#exportlinkstext').click(function(e) {
			$('#exportlinkstext').hide();
			$('#exportlinks').show();
	});
	$('.toggle-row').click(function(){
		$(this).parent().toggleClass('dynamic_border');
	});
})(jQuery)
</script>
<?php include('inc' . DIRECTORY_SEPARATOR . 'admin-footer.php'); ?>