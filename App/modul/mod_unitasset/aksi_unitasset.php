<?php
session_start();
include "../../../configuration/connection_inc.php";
include "../../../function/security.php";

$module=$_GET[r];
$act=$_GET[act];

// Hapus unitasset
if ($module=='unitasset' AND $act=='hapus'){
  $access = delete_security();
  if($access=="allow"){
		mysql_query("DELETE FROM unit_asset WHERE unit_name ='$_GET[id]'");
  }
  header('location:../../index.php?r='.$module.'&mod='.$_SESSION[module]);
}

// Input unitasset
elseif ($module=='unitasset' AND $act=='input'){
  // Input data unitasset
  mysql_query("INSERT INTO unit_asset (unit_name) 
						VALUES ('$_POST[unit_name]')");
								  

  header('location:../../index.php?r='.$module);
}

// Update unitasset
elseif ($module=='unitasset' AND $act=='update'){
  mysql_query("UPDATE unit_asset SET unit_name = '$_POST[unit_name]'  
                          WHERE unit_name  = '$_POST[id]'");
  header('location:../../index.php?r='.$module);
}
?>
