<?php
session_start();
include "../../../configuration/connection_inc.php";
include "../../../function/security.php";

$module=$_GET[r];
$act=$_GET[act];

// Hapus divisi
if ($module=='class' AND $act=='hapus'){
  $access = delete_security();
  if($access=="allow"){
		mysql_query("DELETE FROM master_class WHERE class_id ='$_GET[id]'");
  }
  header('location:../../index.php?r='.$module.'&mod='.$_SESSION[module]);
}

// Input divisi
elseif ($module=='class' AND $act=='input'){
  // Input data divisi
  mysql_query("INSERT INTO master_class(promotype_id,
                                        class_id,
                                         class_name) 
	                       VALUES('$_POST[promotype_id]',
						          '$_POST[class_id]',
						          '$_POST[class_name]')");
  header('location:../../index.php?r='.$module);
}

// Update divisi
elseif ($module=='class' AND $act=='update'){
  mysql_query("UPDATE master_class SET class_name = '$_POST[class_name]'  
                          WHERE class_id   = '$_POST[id]'");
  header('location:../../index.php?r='.$module);
}
?>
