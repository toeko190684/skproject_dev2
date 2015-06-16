<?php
session_start();
include "../../../configuration/connection_inc.php";
include "../../../function/security.php";

$module=$_GET[r];
$act=$_GET[act];

// Hapus transaksi_asset
if ($module=='transaksiasset' AND $act=='hapus'){
  $access = delete_security();
  if($access=="allow"){
		mysql_query("DELETE FROM transaksi_asset WHERE transaksi_id ='$_GET[id]'");
  }
  header('location:../../index.php?r='.$module.'&mod='.$_SESSION[module]);
}

// Input transaksi_asset
elseif ($module=='transaksiasset' AND $act=='input'){
  // Input data transaksi_asset
  $tgl = date('d/m/Y h:m:s');
  mysql_query("INSERT INTO transaksi_asset (tgl_transaksi,
							     asset_id,
								 user_id,
								 touser,
								 keterangan) 
						VALUES ('$_POST[tgl_transaksi]', 
								'$_POST[asset_id]',
								'$_SESSION[user_id]',	
								'$_POST[touser]',
								'$_POST[keterangan]')");			
	  
  mysql_query("update asset set keterangan = '$_POST[keterangan]',
                                user_id= '$_SESSION[user_id]',
								last_update= '$tgl' 
				where asset_id='$_POST[asset_id]'");
				
  header('location:../../index.php?r='.$module);
}

// Update transaksi_asset
elseif ($module=='transaksiasset' AND $act=='update'){
  $tgl = date('d/m/Y h:m:s');
  mysql_query("UPDATE transaksi_asset SET tgl_transaksi = '$_POST[tgl_transaksi]',
                                          asset_id = '$_POST[asset_id]',
										  user_id = '$_SESSION[user_id]',
										  touser = '$_POST[touser]',
										  keterangan = '$_POST[keterangan]'
                          WHERE transaksi_id   = '$_POST[id]'");
	
    mysql_query("update asset set keterangan = '$_POST[keterangan]',
                                user_id= '$_SESSION[user_id]',
								last_update= '$tgl' 
				where asset_id='$_POST[asset_id]'");
				
  header('location:../../index.php?r='.$module);
}
?>
