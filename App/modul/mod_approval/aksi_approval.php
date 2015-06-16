<?php
session_start();
include "../../../configuration/connection_inc.php";
include "../../../function/security.php";

$module=$_GET[r];
$act=$_GET[act];

// Hapus divisi
if ($module=='approval' AND $act=='hapus'){
  $access = delete_security();
  if($access=="allow"){
		mysql_query("DELETE FROM approval WHERE user_id ='$_GET[id]'");
  }
  header('location:../../index.php?r='.$module.'&mod='.$_SESSION[module]);
}

// Input divisi
elseif ($module=='approval' AND $act=='input'){
  // Input data divisi
  mysql_query("INSERT INTO approval(user_id,
                                    level) 
	                       VALUES('$_POST[user_id]',
						          '$_POST[level]')");
  header('location:../../index.php?r='.$module);
}

// Update divisi
elseif ($module=='approval' AND $act=='update'){
  mysql_query("UPDATE approval SET level = '$_POST[level]'  
                          WHERE user_id   = '$_POST[id]'");
						  
  header('location:../../index.php?r='.$module);
}
?>
