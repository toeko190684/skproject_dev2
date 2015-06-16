<?php
session_start();
include "../../../configuration/connection_inc.php";
include "../../../function/security.php";

$module=$_GET[r];
$act=$_GET[act];

// Hapus divisi
if ($module=='regional' AND $act=='hapus'){
  $access = delete_security();
  if($access=="allow"){
		mysql_query("DELETE FROM regional WHERE regional_id ='$_GET[id]'");
  }
  header('location:../../index.php?r='.$module.'&mod='.$_SESSION[module]);
}

// Input divisi
elseif ($module=='regional' AND $act=='input'){
  // Input data divisi
  mysql_query("INSERT INTO regional(regional_id,
                                         regional_name,
										 deskripsi,
										 nasional_id) 
	                       VALUES('$_POST[regional_id]',
						          '$_POST[regional_name]',
								  '$_POST[deskripsi]',
								  '$_POST[nasional]')");
	header('location:../../index.php?r='.$module);
}

// Update divisi
elseif ($module=='regional' AND $act=='update'){
  mysql_query("UPDATE regional SET regional_name = '$_POST[regional_name]',
                                   deskripsi = '$_POST[deskripsi]',
									nasional_id = '$_POST[nasional]'
                          WHERE regional_id   = '$_POST[id]'");
  header('location:../../index.php?r='.$module);
}
?>
