<?php
 include("../../ssf-wp-inc/includes/ssf-wp-env.php");
 global $wpdb;
 
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
 
 if(isset($_POST['year2'])){
 $yr=$_POST['year2'];
 $row=$wpdb->get_results("SELECT SUM(ssf_wp_trk_count) AS TotalItems,SUM(ssf_wp_tel_count) AS TotalTel, SUM(ssf_wp_email_count) AS TotalEmail,ssf_wp_trk_store FROM ".SSF_WP_TRACKING_STORE." WHERE year(ssf_wp_trk_date) = ".$yr." GROUP BY(ssf_wp_trk_store) ORDER BY TotalItems DESC", ARRAY_A);

 if(!empty($row)){
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
                        <td colspan="5" style="text-align:center;">No records available</td>
                        </tr>
<?php } 
}else{
    $yr =$_POST['year'];
    $row=$wpdb->get_results("SELECT SUM(ssf_wp_track_count) AS TotalItems,ssf_wp_track_add FROM ".SSF_WP_TRACKING_TABLE." WHERE   year(ssf_wp_track_date) = ".$yr."  AND ssf_wp_track_add!='' GROUP BY(ssf_wp_track_add) ORDER BY TotalItems DESC", ARRAY_A);
    $totalSearch=0;
     if(!empty($row)){
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
            <td colspan="2">Total</td>
            <td colspan="2"><?php echo $totalSearch; ?></td>
        </tr>
        <?php } else { ?>
        <tr>
        <td colspan="4" style="text-align:center;">No records available</td>
        </tr>
        <?php } 

}
?>