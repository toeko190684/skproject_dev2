<?php
session_start();
include "../../../configuration/connection_inc.php";
include "../../../function/security.php";
$module=$_GET[r];
$act=$_GET[act];

// Hapus grade
if ($module=='numbersetup' AND $act=='hapus'){
  $access = delete_security();
  if($access =="allow"){
		mysql_query("DELETE FROM master_setup WHERE id ='$_GET[id]'");
  }
  header('location:../../index.php?r='.$module.'&mod='.$_SESSION[mod]);
}

// Input grade
elseif ($module=='numbersetup' AND $act=='input'){
  // Input data grade
  mysql_query("INSERT INTO master_setup(jenis,
                                        divisi_id,
										department_id,
                                        prefix,
										number,
										module_id) 
	                       VALUES('$_POST[jenis]',
						          '$_POST[divisi]',
								  '$_POST[departemen]',
						          '$_POST[prefix]',
								  $_POST[number],
								  $_POST[module_id])");
  header('location:../../index.php?r='.$module);
}

// Update grade
elseif ($module=='numbersetup' AND $act=='update'){
  mysql_query("UPDATE master_setup SET jenis = '$_POST[jenis]',
                                       divisi_id = '$_POST[divisi]',
									   department_id = '$_POST[departemen]',
                                       prefix = '$_POST[prefix]',
                                       number = $_POST[number],
									   module_id = $_POST[module_id]									   
                          WHERE id   = '$_POST[id]'");
  header('location:../../index.php?r='.$module);
}
?>
