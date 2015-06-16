<?php
session_start();
include "../../../configuration/connection_inc.php";
include "../../../function/security.php";

$module=$_GET[r];
$act=$_GET[act];

// Hapus groupoutlet
if ($module=='groupoutlet' AND $act=='hapus'){
  $access = delete_security();
  if($access=="allow"){
		mysql_query("DELETE FROM groupoutlet WHERE groupoutlet_id ='$_GET[id]'");
  }
  header('location:../../index.php?r='.$module.'&mod='.$_SESSION[module]);
}

// Input groupoutlet
elseif ($module=='groupoutlet' AND $act=='input'){
  // Input data groupoutlet
  mysql_query("INSERT INTO groupoutlet (groupoutlet_id,
                                 groupoutlet_name) 
						VALUES ('$_POST[groupoutlet_id]', '$_POST[groupoutlet_name]')");
								  

  header('location:../../index.php?r='.$module);
}

// Update groupoutlet
elseif ($module=='groupoutlet' AND $act=='update'){
  mysql_query("UPDATE groupoutlet SET groupoutlet_name = '$_POST[groupoutlet_name]'  
                          WHERE groupoutlet_id   = '$_POST[id]'");
  header('location:../../index.php?r='.$module);
}
?>
