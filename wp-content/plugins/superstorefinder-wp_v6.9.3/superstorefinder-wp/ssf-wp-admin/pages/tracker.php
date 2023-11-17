<?php
include_once(SSF_WP_INCLUDES_PATH."/top-nav.php");
if (!function_exists("ssf_wp_initialize_variables")) { include("../ssf-wp-functions.php"); }

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

?>
<style>
.col-lg-1, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9 {
    float: left;
	width: 30%;
	position: relative;
    min-height: 1px;
    padding-right: 15px;
    padding-left: 15px;
}
.panel-primary {
    border-color: #337ab7;
}
.panel {
    /*margin-bottom: 20px;
    border: 1px solid transparent;*/
    background-color: #fff;
    border-radius: 4px;
    -webkit-box-shadow: 0 1px 1px rgba(0,0,0,.05);
    box-shadow: 0 1px 1px rgba(0,0,0,.05);
}
.panel-primary>.panel-heading {
    color: #fff;
    background-color: #337ab7;
    border-color: #337ab7;
}
.panel-heading {
    padding: 10px 15px;
    border-bottom: 1px solid transparent;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
}
.panel-footer {
    padding: 10px 15px;
    background-color: #f5f5f5;
    border-top: 1px solid #ddd;
    border-bottom-right-radius: 3px;
    border-bottom-left-radius: 3px;
}
.pull-left {
    float: left!important;
}
.pull-right {
    float: right!important;
}
hr {
    border: 0;
    border-top: 1px solid #eee;
    margin: 20px 0;
}
hr {
    box-sizing: content-box;
    height: 0;
    overflow: visible;
}
.huge{
	font-size:24px;
	font-weight:600;
}
.ltls{
	font-size: 14px;
    font-weight: 600;
}

.container{padding-right:15px;padding-left:15px;margin-right:auto;margin-left:auto}
.row{margin-right:-15px;margin-left:-15px}
.chart-timeline_heartrate .c3-legend-item,
.chart-scatter_exercise .c3-legend-item { display: none; }
.col-xs-12{ width:100%; float:left}
.chartpanel{
	border: 1px solid #ccc;
	margin-top: 20px;
    margin-bottom: 20px;
}

.location_link{
	text-decoration: none;
	cursor: pointer;
}
</style>
<div class='wrap' id="ssf_tracer">
<?php  
global $wpdb;
print "
<div class='input_section'>
					<div class='input_title'>
						<h3><span class='fa fa-line-chart'>&nbsp;</span>Analytics </h3>
						<div class='clearfix'></div></div>"; ?>
						
<div class='all_options'>

<!-- /.row -->
<div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="panel">
                        <div class="panel-heading">
							<div class="ltls">Locations</div>
							<hr>
							<?php $numMembers=$wpdb->get_results("SELECT * FROM ".SSF_WP_TABLE." WHERE 1=1", ARRAY_A); ?>
							<div class="huge">
								<a class="location_link" href="<?=SSF_WP_MANAGE_LOCATIONS_PAGE?>"><?=count($numMembers);?></a>
							</div>
                        </div>
                    </div>
                </div>
				
                <div class="col-lg-4">
                    <div class="panel">
                        <div class="panel-heading">
							<div class="ltls">Searches</div>
							<hr>
						<?php	 
						$row=$wpdb->get_row("SELECT SUM(ssf_wp_track_count) AS searchadd FROM ".SSF_WP_TRACKING_TABLE."", ARRAY_A);
						?>
						<div class="huge">
							<a href="javascript:void(0)" class="location_link" onclick="openSSF('address')">
								<?=isset($row['searchadd']) ? $row['searchadd']: '0';?>
							</a>
							</div>
                        </div>
                    </div>
                </div>
				
                <div class="col-lg-4">
                    <div class="panel">
                        <div class="panel-heading">
							<div class="ltls">Views</div>
							<hr>
						<?php	 
						$row=$wpdb->get_row("SELECT SUM(ssf_wp_trk_count) AS viewval FROM ".SSF_WP_TRACKING_STORE."", ARRAY_A);
						?>
						<div class="huge">
							<a href="javascript:void(0)" class="location_link" onclick="openSSF('store')">
								<?=isset($row['viewval']) ? $row['viewval']: '0';?>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	 </div>
            <!-- /.row -->

<div class='option_input option_text' style="clear: both;">		
<div class="panel with-nav-tabs panel-default">

<div class="tabcontent3" style="display:block;">
<ul class="tab">
  <li><a href="javascript:void(0)" class="tablinks3 active" id="openAddress" onclick="openSSF('address')">Searches</a></li>
  <li><a href="javascript:void(0)" class="tablinks3" id="openStores" onclick="openSSF('store')">Views</a></li>
  <li style="float:right;"><a class="btn btn-success btn-sm" onClick="javascript: printTracker();" style=" margin-right: 10px;margin-top: 5px; padding: 10px;" ><i class="fa fa-print" aria-hidden="true"></i> Export</a>
  </li>
  <li style="float:right;"><a class="btn btn-success btn-sm" onClick="javascript: exportReport();" style=" margin-right: 10px;margin-top: 5px; padding: 10px;" ><i class="fa fa-file-excel-o"></i> Get Report</a>
  </li>
</ul>
</div>

<div id="trackaddress" class="tabcontent3" style="display:block;">			
<ul class="tab">
  <li><a href="javascript:void(0)" class="tablinks active" onclick="openCity(event, 'daily')">This Week</a></li>
  <li><a href="javascript:void(0)" class="tablinks" onclick="openCity(event, 'weekly')">Last Week</a></li>
  <li><a href="javascript:void(0)" class="tablinks" onclick="openCity(event, 'monthly')">This Month</a></li>
  <li><a href="javascript:void(0)" class="tablinks" onclick="openCity(event, 'yearly')">Last Month</a></li>
  <li><a href="javascript:void(0)" class="tablinks" onclick="openCity(event, 'yreport')">Yearly Report</a></li>
</ul>


<div id="daily" class="tabcontent" style="display:block;">
                       <?php	 
						$row=$wpdb->get_results("SELECT SUM(ssf_wp_track_count) AS TotalItems,ssf_wp_track_add FROM ".SSF_WP_TRACKING_TABLE." WHERE WEEK(`ssf_wp_track_date`) = WEEK(CURRENT_DATE) GROUP BY(ssf_wp_track_add) ORDER BY TotalItems DESC", ARRAY_A);
						?>
						<table id="ssf_tab_one" class="table table-striped table-hover table-bordered">
						<thead>
						<th>Location</th>
						<!--<th>Date</th>-->
						<th>Search Counter</th>
						</thead>
						<tbody>
						<?php if(!empty($row)){ 
						$totalSearch=0;
						foreach($row as $k=>$v){ ?>
						<tr>
						<td><?php echo $v['ssf_wp_track_add']; ?></td>
						<td><?php echo $v['TotalItems']; 
						$totalSearch=$totalSearch+$v['TotalItems']; ?></td>
						</tr>
						<?php } ?>
                        <tr>
						<td><b>Total</b></td>
						<td><b><?php echo $totalSearch; ?></b></td>
						</tr>
						<?php } else { ?>
						<tr>
						<td colspan="3" style="text-align:center;">No records available</td>
						</tr>
						<?php } ?>
						</tbody>
						</table>
					</div>

					<div id="weekly" class="tabcontent">
					<?php	                  
						$row=$wpdb->get_results("SELECT SUM(ssf_wp_track_count) AS TotalItems,ssf_wp_track_add FROM ".SSF_WP_TRACKING_TABLE." WHERE ssf_wp_track_date >= CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+6 DAY AND ssf_wp_track_date < CURDATE() - INTERVAL DAYOFWEEK(CURDATE())-1 DAY GROUP BY(ssf_wp_track_add) ORDER BY TotalItems DESC", ARRAY_A);
						?>
						<table id="ssf_tab_two" class="table table-striped table-hover table-bordered">
						<thead>
						<th>Location</th>
						<th>Search Counter</th>
						</thead>
						<tbody>
						<?php if(!empty($row)){ 
						$totalSearch=0;
						foreach($row as $k=>$v){ ?>
						<tr>
						<td><?php echo $v['ssf_wp_track_add']; ?></td>
						<td><?php echo $v['TotalItems']; 
						$totalSearch=$totalSearch+$v['TotalItems'];
						?></td>
						</tr>
						<?php } ?>
						<tr>
						<td><b>Total</b></td>
						<td><b><?php echo $totalSearch; ?></b></td>
						</tr>

						<?php } else { ?>
						<tr>
						<td colspan="3" style="text-align:center;">No records available</td>
						</tr>
						<?php } ?>
						</tbody>
						</table>
				</div>

				<div id="monthly" class="tabcontent">
						<?php
						$row=$wpdb->get_results("SELECT SUM(ssf_wp_track_count) AS TotalItems,ssf_wp_track_add FROM ".SSF_WP_TRACKING_TABLE." WHERE MONTH(ssf_wp_track_date) = MONTH(CURRENT_DATE) AND YEAR(ssf_wp_track_date) = YEAR(CURRENT_DATE) GROUP BY(ssf_wp_track_add) ORDER BY TotalItems DESC", ARRAY_A);						
						?>
						<table id="ssf_tab_three" class="table table-striped table-hover table-bordered">
						<thead>
						<th>Location</th>
						<th>Date</th>
						<th>Search Counter</th>
						</thead>
						<tbody>
						<?php if(!empty($row)){
						   $totalSearch=0;
						foreach($row as $k=>$v){ ?>
						<tr>
						<td><?php echo $v['ssf_wp_track_add']; ?></td>
						<td><?php echo date('M Y'); ?></td>
						<td><?php echo $v['TotalItems'];
						$totalSearch=$totalSearch+$v['TotalItems'];
						?></td>
						</tr>
						<?php } ?>
                        <tr>
						<td colspan="2"><b>Total</b></td>
						<td><b><?php echo $totalSearch; ?></b></td>
						</tr>
						<?php } else { ?>
						<tr>
						<td colspan="3" style="text-align:center;">No records available</td>
						</tr>
						<?php } ?>
						</tbody>
						</table>
				</div>
            <div id="yearly" class="tabcontent">
						<?php 
						$row=$wpdb->get_results("SELECT SUM(ssf_wp_track_count) AS TotalItems,ssf_wp_track_add FROM ".SSF_WP_TRACKING_TABLE." WHERE YEAR(ssf_wp_track_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(ssf_wp_track_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) AND ssf_wp_track_add!='' GROUP BY(ssf_wp_track_add) ORDER BY TotalItems DESC", ARRAY_A);
						$pre_mth = date("M-Y", strtotime("last month"));
						$totalSearch=0;
						?>
						<table id="ssf_tab_four"  class="table table-striped table-hover table-bordered">
						<thead>
						<th>Location</th>
						<th>Date</th>
						<th>Search Counter</th>						
						</thead>
						<tbody>
						<?php if(!empty($row)){
						foreach($row as $k=>$v){ ?>
						<tr>
						<td><?php echo $v['ssf_wp_track_add']; ?></td>
						<td><?php echo  $pre_mth; ?></td>
						<td><?php echo $v['TotalItems']; 
								$totalSearch=$totalSearch+$v['TotalItems'];
						?></td>						
						</tr>
						<?php } ?> 
						<tr>
							<td colspan="2"><b>Total</b></td>
							<td><b><?php echo $totalSearch; ?></b></td>
						</tr>
						<?php } else { ?>
						<tr>
						<td colspan="3" style="text-align:center;">No records available</td>
						</tr>
						<?php } ?>
						</tbody>
						</table>
			</div>
			
			<div id="yreport" class="tabcontent">
						<?php 
						$yr =date("Y");
						$row=$wpdb->get_results("SELECT SUM(ssf_wp_track_count) AS TotalItems,ssf_wp_track_add FROM ".SSF_WP_TRACKING_TABLE." WHERE year(ssf_wp_track_date) = ".$yr."  AND ssf_wp_track_add!='' GROUP BY(ssf_wp_track_add) ORDER BY TotalItems DESC", ARRAY_A);
						$totalSearch=0;
						?>
						<table id="ssf_tab_five" class="table table-striped table-hover table-bordered">
						<thead>
						<th>Location</th>
						<th>Date</th>
						<th>Search Counter</th>
            			 <th>Change year 
						 <select id="getYear" onchange="getYear(this)" style="width:auto;">
						 <?php
						 	$year = date("Y");  
  							for($y=9;$y>=1;$y--){
  								$lastyear = $year - $y;
  								print '<option value="'.$lastyear.'">'.$lastyear.'</option>';
  							}
  						?>
						 <option value="<?=$year?>" selected><?=$year?></option></select>
						</th>					
						</thead>
						<tbody id="yreport_data">
						<?php if(!empty($row)){
						foreach($row as $k=>$v){ ?>
						<tr>
						<td><?php echo $v['ssf_wp_track_add']; ?></td>
						<td><?php echo  $yr; ?></td>
						<td colspan="2"><?php echo $v['TotalItems']; 
								$totalSearch=$totalSearch+$v['TotalItems'];
						?></td>						
						</tr>
						<?php } ?> 
						<tr>
							<td colspan="2"><b>Total</b></td>
							<td colspan="2"><b><?php echo $totalSearch; ?></b></td>
						</tr>
						<?php } else { ?>
						<tr>
						<td colspan="4" style="text-align:center;">No records available</td>
						</tr>
						<?php } ?>
						</tbody>
						</table>
			</div>



</div>


<div id="trackstore" class="tabcontent3" style="display:none;">			
<ul class="tab">
  <li><a href="javascript:void(0)" class="tablinks2 active" onclick="openStore(event, 'daily2')">This Week</a></li>
  <li><a href="javascript:void(0)" class="tablinks2" onclick="openStore(event, 'weekly2')">Last Week</a></li>
  <li><a href="javascript:void(0)" class="tablinks2" onclick="openStore(event, 'monthly2')">This Month</a></li>
  <li><a href="javascript:void(0)" class="tablinks2" onclick="openStore(event, 'yearly2')">Last Month</a></li>
  <li><a href="javascript:void(0)" class="tablinks2" onclick="openStore(event, 'yreport2')">Yearly Reports</a></li>
</ul>


<div id="daily2" class="tabcontent2" style="display:block;">
                       <?php	 
						$row=$wpdb->get_results("SELECT SUM(ssf_wp_trk_count) AS TotalItems,SUM(ssf_wp_tel_count) AS TotalTel, SUM(ssf_wp_email_count) AS TotalEmail, ssf_wp_trk_store FROM ".SSF_WP_TRACKING_STORE." WHERE YEARWEEK(`ssf_wp_trk_date`, 1) = YEARWEEK(CURDATE(), 1) GROUP BY(ssf_wp_trk_store) ORDER BY TotalItems DESC", ARRAY_A);
						$TotalItems=0;
						$TotalTel=0;
						$TotalEmail=0;
						?>
						<table id="ssf_tab_six"  class="table table-striped table-hover table-bordered">
						<thead>
						<th>Store Name</th>
						<th>Search Counter</th>
						<th>Telephone Call</th>
						<th>Email</th>
						</thead>
						<tbody>
						<?php if(!empty($row)){
						foreach($row as $k=>$v){ ?>
						<tr>
						<td><?php echo $v['ssf_wp_trk_store']; ?></td>
						<td><?php echo $v['TotalItems']; 
							$TotalItems=$TotalItems+$v['TotalItems']; 
						?></td>
						<td><?php echo $v['TotalTel'];
							$TotalTel=$TotalTel+$v['TotalTel']; 
						?></td>
						<td><?php echo $v['TotalEmail'];
							$TotalEmail=$TotalEmail+$v['TotalEmail']; 
						?></td>
						</tr>
						<?php } ?>
                        <tr>
						<td><b>Total</b></td>
						<td><b><?php echo $TotalItems; ?></b></td>
						<td><b><?php echo $TotalTel; ?></b></td>
						<td><b><?php echo $TotalEmail; ?></b></td>
						</tr>
						<?php } else { ?>
						<tr>
						<td colspan="5" style="text-align:center;">No records available</td>
						</tr>
						<?php } ?>
						</tbody>
						</table>
</div>

<div id="weekly2" class="tabcontent2">
  <?php	                  
						$row=$wpdb->get_results("SELECT SUM(ssf_wp_trk_count) AS TotalItems,SUM(ssf_wp_tel_count) AS TotalTel, SUM(ssf_wp_email_count) AS TotalEmail,ssf_wp_trk_store FROM ".SSF_WP_TRACKING_STORE." WHERE ssf_wp_trk_date >= CURDATE() - INTERVAL DAYOFWEEK(CURDATE())+6 DAY AND ssf_wp_trk_date < CURDATE() - INTERVAL DAYOFWEEK(CURDATE())-1 DAY GROUP BY(ssf_wp_trk_store) ORDER BY TotalItems DESC", ARRAY_A);						
						?>
						<table id="ssf_tab_seven"  class="table table-striped table-hover table-bordered">
						<thead>
						<th>Store Name</th>
						<th>Search Counter</th>
						<th>Telephone Call</th>
						<th>Email</th>
						</thead>
						<tbody>
						<?php if(!empty($row)){
						$TotalItems=0;
						$TotalTel=0;
						$TotalEmail=0;
						foreach($row as $k=>$v){ ?>
						<tr>
						<td><?php echo $v['ssf_wp_trk_store']; ?></td>
						<td><?php echo $v['TotalItems'];
						    $TotalItems=$TotalItems+$v['TotalItems'];
						?></td>
						<td><?php echo $v['TotalTel'];
							$TotalTel=$TotalTel+$v['TotalTel'];
						?></td>
						<td><?php echo $v['TotalEmail'];
							$TotalEmail=$TotalEmail+$v['TotalEmail'];
						?></td>
						</tr>
						<?php } ?> 
						<tr>
						<td><b>Total</b></td>
						<td><b><?php echo $TotalItems; ?></b></td>
						<td><b><?php echo $TotalTel; ?></b></td>
						<td><b><?php echo $TotalEmail; ?></b></td>
						</tr>
						<?php } else { ?>
						<tr>
						<td colspan="5" style="text-align:center;">No records available</td>
						</tr>
						<?php } ?>
						</tbody>
						</table>
				</div>

				<div id="monthly2" class="tabcontent2">
						<?php
					
			$row=$wpdb->get_results("SELECT SUM(ssf_wp_trk_count) AS TotalItems,SUM(ssf_wp_tel_count) AS TotalTel, SUM(ssf_wp_email_count) AS TotalEmail, ssf_wp_trk_store FROM ".SSF_WP_TRACKING_STORE." WHERE MONTH(ssf_wp_trk_date) = MONTH(CURRENT_DATE) AND YEAR(ssf_wp_trk_date) = YEAR(CURRENT_DATE) GROUP BY(ssf_wp_trk_store) ORDER BY TotalItems DESC", ARRAY_A);			
						?>
						<table id="ssf_tab_eight" class="table table-striped table-hover table-bordered">
						<thead>
						<th>Store Name</th>
						<th>Date</th>
						<th>Search Counter</th>
						<th>Telephone Call</th>
						<th>Email</th>
						</thead>
						<tbody>
						<?php if(!empty($row)){
							$TotalItems=0;
							$TotalTel=0;
							$TotalEmail=0;
						foreach($row as $k=>$v){ ?>
						<tr>
						<td><?php echo $v['ssf_wp_trk_store']; ?></td>
						<td><?php 
						echo date('M Y'); ?></td>
						<td><?php echo $v['TotalItems']; 
						$TotalItems=$TotalItems+$v['TotalItems'];
						?></td>
						<td><?php echo $v['TotalTel']; 
						$TotalTel=$TotalTel+$v['TotalTel'];
						?></td>
						<td><?php echo $v['TotalEmail']; 
						$TotalEmail=$TotalEmail+$v['TotalEmail'];
						?></td>
						</tr>
						<?php }   
						?> 
						<tr>
						<td colspan="2"><b>Total</b></td>
						<td><b><?php echo $TotalItems; ?></b></td>
						<td><b><?php echo $TotalTel; ?></b></td>
						<td><b><?php echo $TotalEmail; ?></b></td>
						</tr>
						<?php 
						} else { ?>
						<tr>
						<td colspan="5" style="text-align:center;">No records available</td>
						</tr>
						<?php } ?>
						</tbody>
						</table>
				</div>
            <div id="yearly2" class="tabcontent2"> 
						<?php 
						$row=$wpdb->get_results("SELECT SUM(ssf_wp_trk_count) AS TotalItems,SUM(ssf_wp_tel_count) AS TotalTel, SUM(ssf_wp_email_count) AS TotalEmail, ssf_wp_trk_store FROM ".SSF_WP_TRACKING_STORE." WHERE YEAR(ssf_wp_trk_date) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(ssf_wp_trk_date) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) GROUP BY(ssf_wp_trk_store) ORDER BY TotalItems DESC", ARRAY_A);
						$pre_mth = date("M-Y", strtotime("last month"));
						?>
						<table id="ssf_tab_nine" class="table table-striped table-hover table-bordered">
						<thead>
						<th>Store Name</th>
						<th>Date</th>
						<th>Search Counter</th>
						<th>Telephone Call</th>
						<th>Email</th>
						</thead>
						<tbody>
						<?php if(!empty($row)){
						    $TotalItems=0;
							$TotalTel=0;
							$TotalEmail=0;
						foreach($row as $k=>$v){ ?>
						<tr>
						<td><?php echo $v['ssf_wp_trk_store']; ?></td>
						<td><?php echo $pre_mth; ?></td>
						<td><?php echo $v['TotalItems']; 
						$TotalItems=$TotalItems+$v['TotalItems'];
						?></td>
						<td><?php echo $v['TotalTel']; 
						$TotalTel=$TotalTel+$v['TotalTel'];
						?></td>
						<td><?php echo $v['TotalEmail']; 
						$TotalEmail=$TotalEmail+$v['TotalEmail'];
						?></td>
						</tr>
						<?php } 
						?> 
						<tr>
						<td colspan="2"><b>Total</b></td>
						<td><b><?php echo $TotalItems; ?></b></td>
						<td><b><?php echo $TotalTel; ?></b></td>
						<td><b><?php echo $TotalEmail; ?></b></td>
						</tr>
						<?php 
						} else { ?>
						<tr>
						<td colspan="5" style="text-align:center;">No records available</td>
						</tr>
						<?php } ?>
						</tbody>
						</table>
					</div>


					<div id="yreport2" class="tabcontent2">
						<?php 
						$row=$wpdb->get_results("SELECT SUM(ssf_wp_trk_count) AS TotalItems,SUM(ssf_wp_tel_count) AS TotalTel, SUM(ssf_wp_email_count) AS TotalEmail, ssf_wp_trk_store FROM ".
						SSF_WP_TRACKING_STORE." WHERE year(ssf_wp_trk_date) = ".$yr." GROUP BY(ssf_wp_trk_store) ORDER BY TotalItems DESC", ARRAY_A);
						?>
						<table id="ssf_tab_ten" class="table table-striped table-hover table-bordered">
						<thead>
						<th>Store Name</th>
						<th>Date</th>
						<th>Search Counter</th>
						<th>Telephone Call</th>
						<th>Email</th>
						<th>Change year 
						 <select id="getYearSec" onchange="getYearSec(this)" style="width: auto;">
						 <?php
						 	$year = date("Y");  
  							for($y=9;$y>=1;$y--){
  								$lastyear = $year - $y;
  								print '<option value="'.$lastyear.'">'.$lastyear.'</option>';
  							}
  						?>
						 <option value="<?=$year?>" selected><?=$year?></option></select>
						 </th>	
						</thead>
						<tbody id="yreport1_data">
						<?php if(!empty($row)){
						    $TotalItems=0;
							$TotalTel=0;
							$TotalEmail=0;
						foreach($row as $k=>$v){ ?>
						<tr>
						<td><?php echo $v['ssf_wp_trk_store']; ?></td>
						<td><?php echo $yr; ?></td>
						<td><?php echo $v['TotalItems']; 
						$TotalItems=$TotalItems+$v['TotalItems'];
						?></td>
						<td><?php echo $v['TotalTel']; 
						$TotalTel=$TotalTel+$v['TotalTel'];
						?></td>
						<td colspan="2"><?php echo $v['TotalEmail']; 
						$TotalEmail=$TotalEmail+$v['TotalEmail'];
						?></td>
						</tr>
						<?php } 
						?> 
						<tr>
						<td colspan="2"><b>Total</b></td>
						<td><b><?php echo $TotalItems; ?></b></td>
						<td><b><?php echo $TotalTel; ?></b></td>
						<td colspan="2"><b><?php echo $TotalEmail; ?></b></td>
						</tr>
						<?php 
						} else { ?>
						<tr>
						<td colspan="6" style="text-align:center;">No records available</td>
						</tr>
						<?php } ?>
						</tbody>
						</table>
</div>

</div>
          
</div>

</div></div>			

</div>
<?php include(SSF_WP_INCLUDES_PATH."/ssf-wp-footer.php"); ?>
<style>
/* Style the list */
#ssf_tracer ul.tab {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    border: 1px solid #ccc;
    background-color: #f1f1f1;
}

/* Float the list items side by side */
#ssf_tracer ul.tab li {float: left;}

/* Style the links inside the list items */
#ssf_tracer ul.tab li a {
    display: inline-block;
    color: black;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
    transition: 0.3s;
    font-size: 17px;
}

/* Change background color of links on hover */
#ssf_tracer ul.tab li a:hover {background-color: #ddd;}

/* Create an active/current tablink class */
#ssf_tracer ul.tab li a:focus, .active {background-color: #ccc;}

/* Style the tab content */
#ssf_tracer .tabcontent,
#ssf_tracer .tabcontent2,
#ssf_tracer .tabcontent3 {
    display: none;
    /*padding: 6px 12px;
    border: 1px solid #ccc;*/
    border-top: none;
}
#ssf_tracer .tabcontent,#ssf_tracer .tabcontent2,#ssf_tracer .tabcontent3 {
    -webkit-animation: fadeEffect 1s;
    animation: fadeEffect 1s; /* Fading effect takes 1 second */
}

@-webkit-keyframes fadeEffect {
    from {opacity: 0;}
    to {opacity: 1;}
}

@keyframes fadeEffect {
    from {opacity: 0;}
    to {opacity: 1;}
}

#ssf_tracer table {
	max-width: 100%;
}

#ssf_tracer th {
	text-align: left;
}

#ssf_tracer .table {
	width: 100%;
	margin-bottom: 20px;
}

#ssf_tracer .table > thead > tr > th,
#ssf_tracer .table > tbody > tr > th,
#ssf_tracer .table > tfoot > tr > th,
#ssf_tracer .table > thead > tr > td,
#ssf_tracer .table > tbody > tr > td,
#ssf_tracer .table > tfoot > tr > td {
	padding: 8px;
	line-height: 1.428571429;
	vertical-align: top;
	/*border-top: 1px solid #ddd;*/
}

#ssf_tracer thead tr {
    background-color: #ddd;
    background-image: none;
    color: #0073aa;
}

#ssf_tracer .table > thead > tr > th {
	vertical-align: bottom;
	/*border-bottom: 2px solid #ddd;*/
}

#ssf_tracer .table > caption + thead > tr:first-child > th,
#ssf_tracer .table > colgroup + thead > tr:first-child > th,
#ssf_tracer .table > thead:first-child > tr:first-child > th,
#ssf_tracer .table > caption + thead > tr:first-child > td,
#ssf_tracer .table > colgroup + thead > tr:first-child > td,
#ssf_tracer .table > thead:first-child > tr:first-child > td {
	border-top: 0;
}

#ssf_tracer .table > tbody + tbody {
	border-top: 2px solid #ddd;
}

#ssf_tracer .table .table {
	background-color: #fff;
}

#ssf_tracer .table-condensed > thead > tr > th,
#ssf_tracer .table-condensed > tbody > tr > th,
#ssf_tracer .table-condensed > tfoot > tr > th,
#ssf_tracer .table-condensed > thead > tr > td,
#ssf_tracer .table-condensed > tbody > tr > td,
#ssf_tracer .table-condensed > tfoot > tr > td {
	padding: 5px;
}

#ssf_tracer .table-bordered {
	border: 1px solid #ddd;
}

#ssf_tracer .table-bordered > thead > tr > th,
#ssf_tracer .table-bordered > tbody > tr > th,
#ssf_tracer .table-bordered > tfoot > tr > th,
#ssf_tracer .table-bordered > thead > tr > td,
#ssf_tracer .table-bordered > tbody > tr > td,
#ssf_tracer .table-bordered > tfoot > tr > td {
	/*border: 1px solid #ddd;*/
}

#ssf_tracer .table-bordered > thead > tr > th,
#ssf_tracer .table-bordered > thead > tr > td {
	/*border-bottom-width: 2px;*/
}

/*#ssf_tracer .table-striped > tbody > tr:nth-child(odd) > td,
#ssf_tracer .table-striped > tbody > tr:nth-child(odd) > th {
	background-color: #eaeaea;
}
#ssf_tracer .table-hover > tbody > tr:hover > td,
#ssf_tracer .table-hover > tbody > tr:hover > th {
	background-color: #f5f5f5;
}*/
#ssf_tracer .table-hover > tbody >tr:nth-child(even) {background-color: #f2f2f2;}

</style>
<script>
function openCity(evt, cityName) {
    // Declare all variables
    var i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the link that opened the tab
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
}

function openStore(evt, cityName) {
    // Declare all variables
    var i, tabcontent, tablinks;
    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent2");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks2");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    // Show the current tab, and add an "active" class to the link that opened the tab
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
}

function openSSF(id){
	if(id=='store'){
	  jQuery('#trackaddress').hide();
	  jQuery('#trackstore').show();
	  jQuery('#openStores').addClass('active');
	  jQuery('#openAddress').removeClass('active');
	  }
	 if(id=='address'){
	  jQuery('#trackaddress').show();
	  jQuery('#trackstore').hide();
	  jQuery('#openStores').removeClass('active');
	  jQuery('#openAddress').addClass('active');
	  }
}

function printTracker(){
		exportTableToCSV('Store-Locator-Analytics.csv');
}


function exportTableToCSV(filename) {
	var divs = jQuery("#ssf_tracer table:visible");
	jQuery(divs).each(function (i) {
		var rows = document.querySelectorAll("#"+jQuery(this).attr("id")+" tr");
		var csv = [];
		var namess = '';
		for (var i = 0; i < rows.length; i++) {
			var row = [], cols = rows[i].querySelectorAll("td, th");
			if(jQuery('#trackaddress').is(":visible")){
				var lnt = (cols.length==4) ? 3 : cols.length;
				if(cols.length==4){
					namess = '-'+jQuery('#getYear').val();
				}
				
			}
			if(jQuery('#trackstore').is(":visible")){
				var lnt = (cols.length==6) ? 5 : cols.length;
				if(cols.length==6){
					namess = '-'+jQuery('#getYearSec').val();
				}
			}
			
			for (var j = 0; j < lnt; j++){ 
				var str=cols[j].innerText;
				var res = str.replace(/,/g , " ")
				row.push(res);
			}
			csv.push(row.join(","));        
		}
		// Download CSV file
		var filename = 'Store-Locator-Analytics'+namess+'.csv';
		downloadCSV(csv.join("\n"), filename);
	});
}


function downloadCSV(csv, filename) {
    var csvFile;
    var downloadLink;
    // CSV file
    csvFile = new Blob([csv], {type: "text/csv"});
    // Download link
    downloadLink = document.createElement("a");
    // File name
    downloadLink.download = filename;
    // Create a link to the file
    downloadLink.href = window.URL.createObjectURL(csvFile);
    // Hide download link
    downloadLink.style.display = "none";
    // Add the link to DOM
    document.body.appendChild(downloadLink);
    // Click download link
    downloadLink.click();
}

function getYear(year){
	var year=year.value;
	jQuery('#yreport_data').html('<tr><td colspan="4" style="text-align:center;"><img src="<?php echo SSF_WP_BASE;?>/images/icons/spinner.gif" class="ssf-loader"></td></tr>');
	jQuery.ajax({
	  type: 'POST',
	  data: {year:year} ,
	  url: '<?php echo SSF_WP_BASE;?>/ssf-wp-admin/pages/updateyear.php',
	  success: function(data, textStatus, XMLHttpRequest){
		jQuery('#yreport_data').html(data);
	  },
	  error: function(MLHttpRequest, textStatus, errorThrown){
	   console.log(data);
	  }
	});
}

function getYearSec(year){
	var year=year.value;
	jQuery('#yreport1_data').html('<tr><td colspan="6" style="text-align:center;"><img src="<?php echo SSF_WP_BASE;?>/images/icons/spinner.gif" class="ssf-loader"></td></tr>');
	jQuery.ajax({
	  type: 'POST',
	  data: {year2:year} ,
	  url: '<?php echo SSF_WP_BASE;?>/ssf-wp-admin/pages/updateyear.php',
	  success: function(data, textStatus, XMLHttpRequest){
		jQuery('#yreport1_data').html(data);
	  },
	  error: function(MLHttpRequest, textStatus, errorThrown){
	   jQuery('#yreport1_data').html(data);
	  }
	});
}

function exportReport(){
		var url='<?php echo SSF_WP_BASE;?>/ssf-wp-admin/pages/getReport.php';
		window.location=url;

	}
</script>