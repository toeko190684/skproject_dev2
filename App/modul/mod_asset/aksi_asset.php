<?php
session_start();
include "../../../configuration/connection_inc.php";
include "../../../function/security.php";

$module=$_GET[r];
$act=$_GET[act];

// Hapus asset
if ($module=='asset' AND $act=='hapus'){
  $access = delete_security();
  if($access=="allow"){
		mysql_query("DELETE FROM asset WHERE asset_id ='$_GET[id]'");
  }
  header('location:../../index.php?r='.$module.'&mod='.$_SESSION[module]);
}

// Input asset
elseif ($module=='asset' AND $act=='input'){
  // Input data asset
  $tgl  = date ("d/m/Y H:m:s");
  mysql_query("INSERT INTO asset (asset_id,
                                 asset_name,
							     company_id,
								 cabang_id,
								 kategori_id,
								 location_id,
								 unit_name,
								 quantity,
								 price,
								 keterangan,
								 created_by,
								 last_update,
								 tgl_beli) 
						VALUES ('$_POST[kode_asset]', 
						        '$_POST[asset_name]', 
								'$_POST[company_id]', 
								'$_POST[cabang_id]',
								'$_POST[kategori_id]',
								'$_POST[location_id]',
								'$_POST[unit_name]',
								'$_POST[quantity]',
								'$_POST[price]',
								'$_POST[keterangan]',
								'$_SESSION[user_id]',
								'$tgl',
								'$_POST[tgl_beli]')");						

  header('location:../../index.php?r='.$module);
}

// Update asset
elseif ($module=='asset' AND $act=='update'){
  mysql_query("UPDATE asset SET asset_name = '$_POST[asset_name]'  
                          WHERE asset_id   = '$_POST[id]'");
  header('location:../../index.php?r='.$module);
}
?>
