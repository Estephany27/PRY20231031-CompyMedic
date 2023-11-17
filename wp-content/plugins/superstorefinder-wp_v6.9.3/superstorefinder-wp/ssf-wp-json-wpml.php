<?php
include("ssf-wp-inc/includes/ssf-wp-env.php");
if(isset($_GET['wpml_lang']) && !empty($_GET['wpml_lang'])){
	do_action( 'wpml_switch_language', $_GET['wpml_lang']);
}
global $ssf_wp_vars,$wpdb;
if (!isset($wpdb)){  include_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );  }

$query=$wpdb->get_results("SELECT * FROM ".SSF_WP_TABLE." WHERE `ssf_wp_is_published`!='2' AND ssf_wp_store<>'' AND ssf_wp_longitude<>'' AND ssf_wp_longitude!='0' AND ssf_wp_latitude<>'' ORDER BY ssf_wp_store ASC", ARRAY_A);

$query2=$wpdb->get_results("SELECT * FROM ".SSF_WP_TAG_TABLE." WHERE ssf_wp_tag_id!=0 AND   `ssf_wp_id` in (select `ssf_wp_id` from ".SSF_WP_TABLE." WHERE `ssf_wp_is_published`!='2'
) GROUP BY(ssf_wp_tag_slug)", ARRAY_A);	

$checkAddon=false;
$addonRating=$wpdb->get_results("SELECT * FROM ".SSF_WP_ADDON_TABLE." WHERE ssf_wp_addon_name='ssf-rating-addon-wp' AND ssf_wp_addon_status='on' ", ARRAY_A);
if(!empty($addonRating) && is_dir(SSF_WP_ADDONS_PATH.'/ssf-rating-addon-wp'))
{	
	$checkAddon=true;
}



$CustomAddon=false;
$addonCustom=$wpdb->get_results("SELECT * FROM ".SSF_WP_ADDON_TABLE." WHERE ssf_wp_addon_name='ssf-custom-marker-wp' AND ssf_wp_addon_status='on'", ARRAY_A);
if(!empty($addonCustom))
{	
	$CustomAddon=true;
}

function ssf_to_wmpl_translate($ctrsing){
	do_action( 'wpml_register_single_string', 'superstorefinder-wp', $ctrsing, $ctrsing );
    $ctrsing = apply_filters( 'wpml_translate_single_string', $ctrsing, 'superstorefinder-wp', $ctrsing);
	return $ctrsing;
}

function ssfParseTojson($htmlStr) {
    $xmlStr=str_replace("&amp;",'&',$htmlStr); 
	$xmlStr=str_replace('&lt;','<',$xmlStr); 
	$xmlStr=str_replace('&gt;','>',$xmlStr); 
	$xmlStr=str_replace('&quot;','"',$xmlStr); 
	$xmlStr=str_replace("&#39;","'",$xmlStr); 
	$xmlStr=str_replace("&#44;" ,"," ,$xmlStr);
	return $xmlStr; 
} 

function tagsWithNumber($tag) {
	$tag=trim($tag);
	if(1 === preg_match('~[0-9]~', strtolower(substr($tag, 0, 1)))){
		$tag='_'.$tag;
	}
	$tag= str_replace(" / ","or",$tag);
	$tag= str_replace("/","or2",$tag);
	$tag=str_replace('&amp;#39;','',$tag);
	$tag=str_replace(" &amp; ","_n_",$tag);
	$tag=str_replace("&amp;","_n2_",$tag);
	$tag= str_replace(" ","_",$tag);
	//$tag= str_replace('’',"_s_",$tag);
	$tag= str_replace("\\","or",$tag);	
	$tag=str_replace(",","",$tag);
	$tag=str_replace("&#39;","",$tag); 
	$tag=str_replace(":","_",$tag); 	
	$tag= str_replace("-","_d_",$tag);
    $tag= str_replace("(","_b_o_",$tag);	
    $tag= str_replace(")","_b_c_",$tag);
    $tag= str_replace(".","_dot_",$tag);
    $tag= str_replace("~","_t_",$tag);
    $tag= str_replace("+","_p_",$tag);
    $tag= str_replace("-","_m_",$tag);
	$tag= str_replace("<","_lt_",$tag);
	$tag= str_replace(">","_gt_",$tag);
	$tag= str_replace("%","_per_",$tag);
	$tag= str_replace("&","n",$tag);
	$tag= str_replace(";","se",$tag);

	$tags=htmlentities($tag, ENT_QUOTES, 'UTF-8');
	if (strpos($tags, '&rsquo;') !== false) {
		$tag= str_replace("&rsquo;","_s_",$tags);
    }
   
	return $tag;
}
foreach ($query2 as $row2) {
   $tag=(trim($row2['ssf_wp_tag_slug'])!="")? ssfParseToXML($row2['ssf_wp_tag_slug']) : " " ;
// tag with space
$copy= $tag;
$tag=tagsWithNumber($tag);
$copy=ssf_to_wmpl_translate($copy);
	$json['tags'][] = array(
		'tag'=>$tag,
		'copy'=>$copy
	);
}

$sortOrd=0;
foreach ($query as $row) {
    $sortOrd=$sortOrd+1;
    $addr2=(trim($row['ssf_wp_address2'])!="")? " ".ssfParseTojson($row['ssf_wp_address2']). ", " : " " ;
 	$city=(trim($row['ssf_wp_city'])!="")? " ".ssfParseTojson($row['ssf_wp_city']). ", " : " " ;

  if(!empty($row['ssf_wp_contact_email']) && $row['ssf_wp_contact_email']!='0')
  {
  	$contactEmail=(trim($row['ssf_wp_contact_email'])=="1")? ssfParseTojson($row['ssf_wp_email']) : ssfParseTojson($ssf_wp_vars['ssf_conatct_email']) ;
  }
  else {
	  $contactEmail='';
  }
  $row['ssf_wp_description']=(trim($row['ssf_wp_description'])=="&lt;br&gt;" || trim($row['ssf_wp_description'])=="")? "" : $row['ssf_wp_description'];
  $row['ssf_wp_hours']=(trim($row['ssf_wp_hours'])=="&lt;br&gt;" || trim($row['ssf_wp_hours'])=="")? "" : $row['ssf_wp_hours'];
  $row['ssf_wp_url']=(!ssf_url_test($row['ssf_wp_url']) && trim($row['ssf_wp_url'])!="")? "http://".$row['ssf_wp_url'] : $row['ssf_wp_url'] ;
  $row['ssf_wp_ext_url']=(!ssf_url_test($row['ssf_wp_ext_url']) && trim($row['ssf_wp_ext_url'])!="")? "http://".$row['ssf_wp_ext_url'] : $row['ssf_wp_ext_url'] ;
  // Xml nodes 
  
  // store img
  $ssf_wp_uploads=wp_upload_dir();
  
  if ( is_ssl() ) {
	$ssf_wp_uploads = str_replace( 'http://', 'https://', $ssf_wp_uploads );
  }
	$ssf_wp_uploads_path=$ssf_wp_uploads['basedir']."/ssf-wp-uploads"; 
	$upload_dir=$ssf_wp_uploads_path."/images/".$row['ssf_wp_id'].'/*';
	$upload_dir_img=$ssf_wp_uploads_path."/images/".$row['ssf_wp_id'];
	$ssf_wp_uploads_base=$ssf_wp_uploads['baseurl']."/ssf-wp-uploads";
	
	$img = '';
	$files = array();
	if(is_dir($upload_dir_img))
	{
	foreach (glob($upload_dir) as $file) {
	  $files[] = $file;
	}

	if($files !== FALSE && isset($files[0])) {
	$files[0] = str_replace('ori_', '', $files[0]);
	$files[0] = str_replace($ssf_wp_uploads_path."/images/".$row['ssf_wp_id'], '', $files[0]);
	
		$img = $ssf_wp_uploads_base."/images/".$row['ssf_wp_id'].$files[0];
	}
	}
	
	 //*custom marker icon */
	$upload_dir=$ssf_wp_uploads_path."/images/icons/".$row['ssf_wp_id'].'/*';
	$upload_dir_icon=$ssf_wp_uploads_path."/images/icons/".$row['ssf_wp_id'];
	$ssf_wp_uploads_base=$ssf_wp_uploads['baseurl']."/ssf-wp-uploads";
	$mrkr = '';
	$files = array();
	if(is_dir($upload_dir_icon))
	{
		foreach (glob($upload_dir) as $file) {
		  $files[] = $file;
		}
		if($files !== FALSE && isset($files[0])) {
		$files[0] = str_replace($ssf_wp_uploads_path."/images/icons/".$row['ssf_wp_id'], '', $files[0]);
			$mrkr = $ssf_wp_uploads_base."/images/icons/".$row['ssf_wp_id'].$files[0];
		}
	}
	
	$catarray=array();
	$tagsList=array();
	$row['ssf_wp_tags'] = str_replace('&#44;', ',', $row['ssf_wp_tags']);
	//$row['ssf_wp_tags'] = preg_replace('/\s+/', '', $row['ssf_wp_tags']);
	$tagarray = explode(',',trim($row['ssf_wp_tags']));
	for($i=0;$i<sizeof($tagarray)-1;$i++){
		 array_push($tagsList,ssfParseToXML(__(ssf_to_wmpl_translate($tagarray[$i]),','),SSF_WP_TEXT_DOMAIN));
		 $tags=tagsWithNumber($tagarray[$i]);
		 array_push($catarray,$tags);
		 //*custom marker icon by category */
		if($mrkr=='' && $CustomAddon==true){
			$fileName = strtolower(preg_replace('/[^a-zA-Z0-9_.]/', '', $tagarray[$i]));
			$file_marker=SSF_WP_UPLOADS_BASE."/images/sprites/markers/".$fileName.".png?".time();
			$dir_marker=SSF_WP_UPLOADS_PATH."/images/sprites/markers/".$fileName.".png";
			if (file_exists($dir_marker)) {
					$mrkr=$file_marker;
			}
		}
	}
  $storeRat='';	
  $storeTotalRat='';
  if($checkAddon==true){ 
  $rating=$wpdb->get_results("SELECT count(*) as count, AVG(ssf_wp_ratings_score) as score FROM ".SSF_WP_SOCIAL_TABLE." WHERE 1 AND ssf_wp_store_id = '".intval($row['ssf_wp_id'])."'", ARRAY_A);
	 $storeRat=round($rating[0]["score"], 2);
	 $storeTotalRat=$rating[0]["count"];
  }
  $row['ssf_wp_store']=ssf_to_wmpl_translate($row['ssf_wp_store']);
  $row['ssf_wp_description']=ssf_to_wmpl_translate($row['ssf_wp_description']);
  $row['ssf_wp_hours']=ssf_to_wmpl_translate($row['ssf_wp_hours']);
  $row['ssf_wp_ext_url']=ssf_to_wmpl_translate($row['ssf_wp_ext_url']);
  $productsServices = implode(', ',$tagsList);
  $json['item'][] = array(
				'location'=>ssfParseTojson($row['ssf_wp_store']),
				'address'=>ssfParseTojson($row['ssf_wp_address']) .$addr2. $city. ' ' .ssfParseTojson($row['ssf_wp_state']).' ' .ssfParseTojson($row['ssf_wp_zip']),
				'sortord'=>$sortOrd,
				'latitude'=>$row['ssf_wp_latitude'],
				'longitude'=>$row['ssf_wp_longitude'],
				'description'=>html_entity_decode(ssfParseTojson($row['ssf_wp_description']),ENT_COMPAT,"UTF-8"),
				'website'=>ssfParseTojson($row['ssf_wp_url']) ,
				'exturl'=>ssfParseTojson($row['ssf_wp_ext_url']),
				'operatingHours'=>html_entity_decode(ssfParseToHXML($row['ssf_wp_hours']),ENT_COMPAT,"UTF-8"),
				'embedvideo'=>base64_encode(htmlspecialchars_decode($row['ssf_wp_embed_video'])),
				'defaultmedia'=>$row['ssf_wp_default_media'],
				'telephone'=>ssfParseTojson($row['ssf_wp_phone']),
				'fax'=>ssfParseTojson($row['ssf_wp_fax']),
				'email'=>ssfParseTojson($row['ssf_wp_email']),
				'custom_field1'=>ssfParseTojson($row['ssf_wp_custom_field1']),
				'custom_field2'=>ssfParseTojson($row['ssf_wp_custom_field2']),
				'custom_field3'=>ssfParseTojson($row['ssf_wp_custom_field3']),
				'custom_field4'=>ssfParseTojson($row['ssf_wp_custom_field4']),
				'custom_field5'=>ssfParseTojson($row['ssf_wp_custom_field5']),
				'custom_field6'=>ssfParseTojson($row['ssf_wp_custom_field6']),
				'country'=>ssfParseTojson($row['ssf_wp_country']),
				'contactus'=>$contactEmail,
				'category'=>$catarray,
				'productsServices' => rtrim($productsServices,','),
				'storeId'=>$row['ssf_wp_id'],
				'storeimage'=>$img,
				'custmmarker'=>$mrkr,
				'storeRat'=>$storeRat,
				'storeTotalRat'=>$storeTotalRat
			);
}

print json_encode($json);

?>