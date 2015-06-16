<?php
session_start();
include "../../../configuration/connection_inc.php";
include "../../../function/security.php";

$module=$_GET[r];
$act=$_GET[act];

// Hapus kategoriaset
if ($module=='kategoriaset' AND $act=='hapus'){
  $access = delete_security();
  if($access=="allow"){
		mysql_query("DELETE FROM kategori_asset WHERE kategori_id ='$_GET[id]'");
  }
  header('location:../../index.php?r='.$module.'&mod='.$_SESSION[module]);
}

// Input kategoriaset
elseif ($module=='kategoriaset' AND $act=='input'){
  // Input data kategoriaset
  mysql_query("INSERT INTO kategori_asset (kategori_id,
                                           kategori_name) 
						VALUES ('$_POST[kategori_id]',
						        '$_POST[kategori_name]')");
								  

  header('location:../../index.php?r='.$module);
}

// Update kategoriaset
elseif ($module=='kategoriaset' AND $act=='update'){
  mysql_query("UPDATE kategoriaset SET kategori_asset_name = '$_POST[kategori_name]'  
                          WHERE kategori_id   = '$_POST[id]'");
  header('location:../../index.php?r='.$module);
}
?>
