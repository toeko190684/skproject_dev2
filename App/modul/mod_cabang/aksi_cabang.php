<?php
session_start();
include "../../../configuration/connection_inc.php";
include "../../../function/security.php";

$module=$_GET[r];
$act=$_GET[act];

// Hapus cabang
if ($module=='cabang' AND $act=='hapus'){
  $access = delete_security();
  if($access=="allow"){
		mysql_query("DELETE FROM cabang WHERE cabang_id ='$_GET[id]'");
  }
  header('location:../../index.php?r='.$module.'&mod='.$_SESSION[module]);
}

// Input cabang
elseif ($module=='cabang' AND $act=='input'){
  // Input data cabang
  mysql_query("INSERT INTO cabang (cabang_id,
                                 cabang_name) 
						VALUES ('$_POST[cabang_id]', '$_POST[cabang_name]')");
								  

  header('location:../../index.php?r='.$module);
}

// Update cabang
elseif ($module=='cabang' AND $act=='update'){
  mysql_query("UPDATE cabang SET cabang_name = '$_POST[cabang_name]'  
                          WHERE cabang_id   = '$_POST[id]'");
  header('location:../../index.php?r='.$module);
}
?>
