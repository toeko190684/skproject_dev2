<?php
session_start();
include "../../../configuration/connection_inc.php";
include "../../../function/security.php";

$module=$_GET[r];
$act=$_GET[act];

// Hapus divisi
if ($module=='promo' AND $act=='hapus'){
  $access = delete_security();
  if($access=="allow"){
		mysql_query("DELETE FROM master_promotype WHERE promotype_id ='$_GET[id]'");
  }
  header('location:../../index.php?r='.$module.'&mod='.$_SESSION[module]);
}

// Input divisi
elseif ($module=='promo' AND $act=='input'){
  // Input data divisi
  mysql_query("INSERT INTO master_promotype(promotype_id,
                                         promotype_name,
										 grouppromo_id) 
	                       VALUES('$_POST[promotype_id]',
						          '$_POST[promotype_name]',
								  '$_POST[grouppromo_id]')");
  header('location:../../index.php?r='.$module);
}

// Update divisi
elseif ($module=='promo' AND $act=='update'){
  mysql_query("UPDATE master_promotype SET promotype_name = '$_POST[promotype_name]',
                                           grouppromo_id = '$_POST[grouppromo_id]'  
                          WHERE promotype_id   = '$_POST[id]'");
  header('location:../../index.php?r='.$module);
}
?>
