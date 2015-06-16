<?php
session_start();
include "../../../configuration/connection_inc.php";
include "../../../function/security.php";
$module=$_GET[r];
$act=$_GET[act];

// Hapus grade
if ($module=='sotarget' AND $act=='hapus'){
  $access = delete_security();
  if($access =="allow"){
		mysql_query("DELETE FROM sales_order_target WHERE so_target_id ='$_GET[id]'");
  }
  header('location:../../index.php?r='.$module.'&mod='.$_SESSION[mod]);
}

// Input grade
elseif ($module=='sotarget' AND $act=='input'){
  // Input data grade
	mysql_query("INSERT INTO sales_order_target(bulan,
                                        tahun,
										divisi_id,
										value) 
	                       VALUES('$_POST[bulan]',
						          '$_POST[tahun]',
								  '$_POST[divisi]',
								   $_POST[value])");
	header('location:../../index.php?r='.$module);
}

// Update grade
elseif ($module=='sotarget' AND $act=='update'){
    mysql_query("UPDATE sales_order_target SET bulan = '$_POST[bulan]',
                                       tahun = '$_POST[tahun]',
									   divisi_id = '$_POST[divisi]',
									   value = '$_POST[value]'
                          WHERE so_target_id   = '$_POST[id]'");
  header('location:../../index.php?r='.$module);
}
?>
