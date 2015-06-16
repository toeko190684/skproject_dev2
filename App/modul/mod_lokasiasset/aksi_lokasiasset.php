<?php
session_start();
include "../../../configuration/connection_inc.php";
include "../../../function/security.php";

$module=$_GET[r];
$act=$_GET[act];

// Hapus lokasiasset
if ($module=='lokasiasset' AND $act=='hapus'){
  $access = delete_security();
  if($access=="allow"){
		mysql_query("DELETE FROM location WHERE location_id ='$_GET[id]'");
  }
  header('location:../../index.php?r='.$module.'&mod='.$_SESSION[module]);
}

// Input lokasiasset
elseif ($module=='lokasiasset' AND $act=='input'){
  // Input data lokasiasset
  mysql_query("INSERT INTO location (location_id,
                                     location_name) 
						VALUES ('$_POST[location_id]',
						        '$_POST[location_name]')");
								  

  header('location:../../index.php?r='.$module);
}

// Update lokasiasset
elseif ($module=='lokasiasset' AND $act=='update'){
  mysql_query("UPDATE location SET location_name = '$_POST[location_name]'  
                          WHERE location_id   = '$_POST[id]'");
  header('location:../../index.php?r='.$module);
}
?>
