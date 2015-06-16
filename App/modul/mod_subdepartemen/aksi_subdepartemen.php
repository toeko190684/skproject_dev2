<?php
session_start();
include "../../../configuration/connection_inc.php";
include "../../../function/security.php";

$module=$_GET[r];
$act=$_GET[act];

// Hapus divisi
if ($module=='subdepartemen' AND $act=='hapus'){
  $access = delete_security();
  if($access=="allow"){
		mysql_query("DELETE FROM master_subdepartemen WHERE subdepartemen_id ='$_GET[id]'");
  }
  header('location:../../index.php?r='.$module.'&mod='.$_SESSION[module]);
}

// Input divisi
elseif ($module=='subdepartemen' AND $act=='input'){
  // Input data divisi
  mysql_query("INSERT INTO master_subdepartemen(department_id,
                                                subdepartemen_id,
                                                subdepartemen_name) 
	                       VALUES('$_POST[departemen_id]',
						          '$_POST[subdepartemen_id]',
						          '$_POST[subdepartemen_name]')");
  header('location:../../index.php?r='.$module);
}

// Update divisi
elseif ($module=='subdepartemen' AND $act=='update'){
  mysql_query("UPDATE master_subdepartemen SET department_id='$_POST[departemen_id]',
                                               subdepartemen_name = '$_POST[subdepartemen_name]'  
                          WHERE subdepartemen_id   = '$_POST[id]'");


  header('location:../../index.php?r='.$module);
}
?>
