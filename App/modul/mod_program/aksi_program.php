<?php
session_start();
include "../../../configuration/connection_inc.php";
include "../../../function/security.php";

$module=$_GET[r];
$act=$_GET[act];

	$uploads_dir = "../../../images/";
	$tmp_name = $_FILES["gambar"]["tmp_name"];
	$filename = $_FILES["gambar"]["name"];



if ($module=='program' AND $act=='hapus'){
    $access = delete_security();
	if($access=="allow"){
		  mysql_query("DELETE FROM sec_pro WHERE pro_id ='$_GET[id]'");
  	}
	header('location:../../index.php?r='.$module.'&mod='.$_SESSION[mod]);
}


elseif ($module=='program' AND $act=='input'){
	//upload data 
    
	move_uploaded_file($tmp_name,$uploads_dir.$filename);
      
	// Input data app
	mysql_query("INSERT INTO sec_pro (pro_name,
									pro_location,
									image) 
	                       VALUES('$_POST[pro_name]',
						          '$_POST[pro_location]',
								  'images/$filename')");
	header('location:../../index.php?r='.$module);
}

// Update app
elseif ($module=='program' AND $act=='update'){
	
	if(empty($filename)){
		mysql_query("UPDATE sec_pro SET pro_name = '$_POST[pro_name]',
	                                       pro_location = '$_POST[pro_location]'
	                          WHERE pro_id   = '$_POST[id]'");
	}elseif(!empty($filename)){
		mysql_query("UPDATE sec_pro SET pro_name = '$_POST[pro_name]',
	                                       pro_location = '$_POST[pro_location]',
										   image = 'images/$filename'	
	                          WHERE pro_id   = '$_POST[id]'");	
	
		move_uploaded_file($tmp_name,$uploads_dir.$filename);					  
		
	}
	
	header('location:../../index.php?r='.$module);
}
?>
