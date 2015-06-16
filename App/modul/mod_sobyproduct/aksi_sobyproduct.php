<?php
session_start();
include "../../../configuration/connection_inc.php";
include "../../../function/security.php";

$module=$_GET[r];
$act=$_GET[act];

// Hapus nasional
if ($module=='nasional' AND $act=='hapus'){
  $access = delete_security();
  if($access=="allow"){
		mysql_query("DELETE FROM nasional_sales WHERE nasional_id ='$_GET[id]'");
  }
  header('location:../../index.php?r='.$module.'&mod='.$_SESSION[module]);
}

// Input nasional
elseif ($module=='nasional' AND $act=='input'){
  // Input data nasional
  mysql_query("INSERT INTO nasional_sales(nasional_id,
                                         nasional_name,
										 deskripsi) 
	                       VALUES('$_POST[nasional_id]',
						          '$_POST[nasional_name]',
								  '$_POST[deskripsi]')");
	header('location:../../index.php?r='.$module);
}

// Update nasional
elseif ($module=='nasional' AND $act=='update'){
  mysql_query("UPDATE nasional_sales SET nasional_name = '$_POST[nasional_name]',
                                   deskripsi = '$_POST[deskripsi]'   
                          WHERE nasional_id   = '$_POST[id]'");
  header('location:../../index.php?r='.$module);
}
?>
