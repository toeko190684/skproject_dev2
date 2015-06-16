<?php
session_start();
include "../../../configuration/connection_inc.php";
include "../../../function/security.php";

$module=$_GET[r];
$act=$_GET[act];

	$uploads_dir = "../../../images/";
	$tmp_name = $_FILES["gambar"]["tmp_name"];
	$filename = $_FILES["gambar"]["name"];


// Hapus app
if ($module=='sec_app' AND $act=='hapus'){
    $access = delete_security();
	if($access=="allow"){
		  mysql_query("DELETE FROM sec_app WHERE app_id ='$_GET[id]'");
  	}
	header('location:../../index.php?r='.$module.'&mod='.$_SESSION[mod]);
}

// Input app
elseif ($module=='sec_app' AND $act=='input'){
	//upload data 
    
	move_uploaded_file($tmp_name,$uploads_dir.$filename);
      
	// Input data app
	mysql_query("INSERT INTO sec_app (app_name,
									app_location,
									pro_id,
									urut,
									image) 
	                       VALUES('$_POST[app_name]',
						          '$_POST[app_location]',
								  '$_POST[program]',
								  $_POST[urut],
								  'images/$filename')");
								 
	header('location:../../index.php?r='.$module);
}

// Update app
elseif ($module=='sec_app' AND $act=='update'){
	
	if(empty($filename)){
		mysql_query("UPDATE sec_app SET app_name = '$_POST[app_name]',
	                                       app_location = '$_POST[app_location]',
										   pro_id = '$_POST[program]',
										   urut = $_POST[urut]
	                          WHERE app_id   = '$_POST[id]'");
	}elseif(!empty($filename)){
		mysql_query("UPDATE sec_app SET app_name = '$_POST[app_name]',
	                                       app_location = '$_POST[app_location]',
										   pro_id = '$_POST[program]',
										   urut = $_POST[urut],
										   image = 'images/$filename'	
	                          WHERE app_id   = '$_POST[id]'");	
	
		move_uploaded_file($tmp_name,$uploads_dir.$filename);					  
		
	}
	
	header('location:../../index.php?r='.$module);
}
?>
