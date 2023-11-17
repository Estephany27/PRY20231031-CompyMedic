<?php
include("ssf-wp-inc/includes/ssf-wp-env.php");
global $ssf_wp_vars,$wpdb;
if(isset($_POST['ssf_wp_track_add'])){
	$_POST['ssf_wp_track_add'] = trim(preg_replace('/\s*\([^)]*\)/', '', $_POST["ssf_wp_track_add"]));
    $_POST['ssf_wp_track_date']=date('Y-m-d');
	$check=$wpdb->get_results("SELECT * FROM ".SSF_WP_TRACKING_TABLE." WHERE ssf_wp_track_add='".$_POST['ssf_wp_track_add']."' AND ssf_wp_track_date='".$_POST['ssf_wp_track_date']."'", ARRAY_A); 
	if(!empty($check)){	
			$_POST['ssf_wp_track_count']=$check[0]['ssf_wp_track_count']+1;
			$wpdb->query($wpdb->prepare("UPDATE ".SSF_WP_TRACKING_TABLE." SET ssf_wp_track_count='".$_POST['ssf_wp_track_count']."' WHERE ssf_wp_track_id='%d'", $check[0]['ssf_wp_track_id'])); 
	}else{
		$_POST['ssf_wp_track_count']=1;
		$fieldList=""; $valueList="";
		foreach ($_POST as $key=>$value) {
			if (preg_match("@ssf_wp_@", $key)) {
				$fieldList.="$key,";
				if (is_array($value)){
					$value=serialize($value); //for arrays being submitted
					$valueList.="'$value',";
				} else {
					$valueList.=$wpdb->prepare("%s", stripslashes($value)).",";
				}
			}
		}
		$fieldList=substr($fieldList, 0, strlen($fieldList)-1);
		$valueList=substr($valueList, 0, strlen($valueList)-1);
		$wpdb->query("INSERT INTO ".SSF_WP_TRACKING_TABLE." ($fieldList) VALUES ($valueList)");
	}
}
if(isset($_POST['ssf_wp_trk_store'])){
    $_POST['ssf_wp_trk_date']=date('Y-m-d');
	
	$_POST['ssf_wp_trk_store'] = trim(preg_replace('/\s*\([^)]*\)/', '', $_POST["ssf_wp_trk_store"]));
    $check=$wpdb->get_results("SELECT * FROM ".SSF_WP_TRACKING_STORE." WHERE ssf_wp_trk_store='".$_POST['ssf_wp_trk_store']."' AND ssf_wp_trk_date='".$_POST['ssf_wp_trk_date']."'", ARRAY_A); 
	if(!empty($check)){	
		$_POST['ssf_wp_trk_count']=$check[0]['ssf_wp_trk_count']+1;
		$wpdb->query($wpdb->prepare("UPDATE ".SSF_WP_TRACKING_STORE." SET ssf_wp_trk_count='".$_POST['ssf_wp_trk_count']."' WHERE ssf_wp_trk_id='%d'", $check[0]['ssf_wp_trk_id'])); 
	}else{
		$_POST['ssf_wp_trk_count']=1;
		$fieldList=""; $valueList="";
		foreach ($_POST as $key=>$value) {
			if (preg_match("@ssf_wp_@", $key)) {
				$fieldList.="$key,";
				if (is_array($value)){
					$value=serialize($value); //for arrays being submitted
					$valueList.="'$value',";
				} else {
					$valueList.=$wpdb->prepare("%s", stripslashes($value)).",";
				}
			}
		}
		$fieldList=substr($fieldList, 0, strlen($fieldList)-1);
		$valueList=substr($valueList, 0, strlen($valueList)-1);
		$wpdb->query("INSERT INTO ".SSF_WP_TRACKING_STORE." ($fieldList) VALUES ($valueList)");
	}
}
if(isset($_POST['ssf_store_name'])){
	 //$_POST['ssf_wp_trk_store']= str_replace("&","&amp;",$_POST['ssf_store_name']);

	 $_POST['ssf_wp_trk_store'] = trim(preg_replace('/\s*\([^)]*\)/', '', $_POST["ssf_store_name"]));
	 
     $_POST['ssf_wp_trk_date']=date('Y-m-d');
    $check=$wpdb->get_results("SELECT * FROM ".SSF_WP_TRACKING_STORE." WHERE ssf_wp_trk_store='".$_POST['ssf_wp_trk_store']."' AND ssf_wp_trk_date='".$_POST['ssf_wp_trk_date']."'", ARRAY_A); 
	
	
	if(!empty($check)){	
		$_POST['ssf_wp_tel_count']=$check[0]['ssf_wp_tel_count']+1;
		$wpdb->query($wpdb->prepare("UPDATE ".SSF_WP_TRACKING_STORE." SET ssf_wp_tel_count='".$_POST['ssf_wp_tel_count']."' WHERE ssf_wp_trk_id='%d'", $check[0]['ssf_wp_trk_id'])); 
	}else{
		$_POST['ssf_wp_tel_count']=1;
		$fieldList=""; $valueList="";
		foreach ($_POST as $key=>$value) {
			if (preg_match("@ssf_wp_@", $key)) {
				$fieldList.="$key,";
				if (is_array($value)){
					$value=serialize($value); //for arrays being submitted
					$valueList.="'$value',";
				} else {
					$valueList.=$wpdb->prepare("%s", stripslashes($value)).",";
				}
			}
		}
		$fieldList=substr($fieldList, 0, strlen($fieldList)-1);
		$valueList=substr($valueList, 0, strlen($valueList)-1);
		$wpdb->query("INSERT INTO ".SSF_WP_TRACKING_STORE." ($fieldList) VALUES ($valueList)");
	}
}

if(isset($_POST['ssf_email_name'])){
	 //$_POST['ssf_email_name']= str_replace("&","&amp;",$_POST['ssf_email_name']);
	 $_POST['ssf_email_name'] = trim(preg_replace('/\s*\([^)]*\)/', '', $_POST["ssf_email_name"]));
     $_POST['ssf_wp_trk_date']=date('Y-m-d');
    $check=$wpdb->get_results("SELECT * FROM ".SSF_WP_TRACKING_STORE." WHERE ssf_wp_trk_store='".$_POST['ssf_email_name']."' AND ssf_wp_trk_date='".$_POST['ssf_wp_trk_date']."'", ARRAY_A); 
	
	
	if(!empty($check)){	
		$_POST['ssf_wp_email_count']=$check[0]['ssf_wp_email_count']+1;
		$wpdb->query($wpdb->prepare("UPDATE ".SSF_WP_TRACKING_STORE." SET ssf_wp_email_count='".$_POST['ssf_wp_email_count']."' WHERE ssf_wp_trk_id='%d'", $check[0]['ssf_wp_trk_id'])); 
	}else{
		$_POST['ssf_wp_email_count']=1;
		$fieldList=""; $valueList="";
		foreach ($_POST as $key=>$value) {
			if (preg_match("@ssf_wp_@", $key)) {
				$fieldList.="$key,";
				if (is_array($value)){
					$value=serialize($value); //for arrays being submitted
					$valueList.="'$value',";
				} else {
					$valueList.=$wpdb->prepare("%s", stripslashes($value)).",";
				}
			}
		}
		$fieldList=substr($fieldList, 0, strlen($fieldList)-1);
		$valueList=substr($valueList, 0, strlen($valueList)-1);
		$wpdb->query("INSERT INTO ".SSF_WP_TRACKING_STORE." ($fieldList) VALUES ($valueList)");
	}
}
?>