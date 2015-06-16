<?php
session_start();
include "../../../configuration/connection_inc.php";
include "../../../function/security.php";

$users=$_GET[r];
$act=$_GET[act];
$password = md5($_POST[user_id]."@".$_POST[password]);

$uploads_dir = "../../../images/";
$tmp_name = $_FILES["gambar"]["tmp_name"];
$filename = $_FILES["gambar"]["name"];

if ($users=='home' AND $act=='update'){
    if ($_POST[password] == ""){
	    if(empty($filename)){
				mysql_query("UPDATE sec_users SET   full_name = '$_POST[nama_lengkap]',
													hp   = '$_POST[hp]',
													email = '$_POST[email]'
		                          WHERE user_id   = '$_POST[id]'");
		}elseif(!empty($filename)){
				mysql_query("UPDATE sec_users SET   full_name = '$_POST[nama_lengkap]',
													hp   = '$_POST[hp]',
													email = '$_POST[email]'
													foto = 'images/$filename'
		                          WHERE user_id   = '$_POST[id]'");
								  								  
				move_uploaded_file($tmp_name,$uploads_dir.$filename);			
		}
	}else{
	    if(empty($filename)){
				mysql_query("UPDATE sec_users SET   password = '$password',
													full_name = '$_POST[nama_lengkap]',
													hp   = '$_POST[hp]',
													email = '$_POST[email]'
		                          WHERE user_id   = '$_POST[id]'");
		}elseif(!empty($filename)){
				mysql_query("UPDATE sec_users SET   password = '$password',
													full_name = '$_POST[nama_lengkap]',
													hp   = '$_POST[hp]',
													email = '$_POST[email]'
													foto = 'images/$filename'
		                          WHERE user_id   = '$_POST[id]'");
				move_uploaded_file($tmp_name,$uploads_dir.$filename);									  
		}
	}
  header('location:../../index.php?r='.$users);
}
?>
