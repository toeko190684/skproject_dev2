<?php
session_start();
include "../../../configuration/connection_inc.php";
include "../../../function/security.php";

$module=$_GET[r];
$act=$_GET[act];

// Hapus department
if ($module=='master_department' AND $act=='hapus'){
  $access = delete_security();
  if($access=="allow"){
		mysql_query("DELETE FROM master_department WHERE department_id ='$_GET[id]'");
  }
  header('location:../../index.php?r='.$module."&mod=".$_SESSION[mod]);
}

// Input modul
elseif ($module=='master_department' AND $act=='input'){
  // Input data modul
  mysql_query("INSERT INTO master_department(divisi_id,
                                            department_id,
                                            department_name) 
	                       VALUES('$_POST[divisi_id]',
						          '$_POST[department_id]',
						          '$_POST[department_name]')");
  header('location:../../index.php?r='.$module);
}

// Update department
elseif ($module=='master_department' AND $act=='update'){
  mysql_query("UPDATE master_department SET divisi_id='$_POST[divisi_id]',
                                            department_name = '$_POST[department_name]'  
                          WHERE department_id   = '$_POST[id]'");
  header('location:../../index.php?r='.$module);
}
?>
