<?php
session_start();
include "../../../configuration/connection_inc.php";
include "../../../function/security.php";

$module=$_GET[r];
$act=$_GET[act];

// Hapus divisi
if ($module=='master_divisi' AND $act=='hapus'){
  $access = delete_security();
  if($access=="allow"){
		mysql_query("DELETE FROM master_divisi WHERE divisi_id ='$_GET[id]'");
  }
  header('location:../../index.php?r='.$module.'&mod='.$_SESSION[module]);
}

// Input divisi
elseif ($module=='master_divisi' AND $act=='input'){
  // Input data divisi
  mysql_query("INSERT INTO master_divisi(divisi_id,
                                         divisi_name) 
	                       VALUES('$_POST[divisi_id]',
						          '$_POST[divisi_name]')");
  header('location:../../index.php?r='.$module);
}

// Update divisi
elseif ($module=='master_divisi' AND $act=='update'){
  mysql_query("UPDATE master_divisi SET divisi_name = '$_POST[divisi_name]'  
                          WHERE divisi_id   = '$_POST[id]'");
  header('location:../../index.php?r='.$module);
}
?>
