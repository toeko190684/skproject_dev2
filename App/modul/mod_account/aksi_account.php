<?php
session_start();
include "../../../configuration/connection_inc.php";
include "../../../function/security.php";

$module=$_GET[r];
$act=$_GET[act];

// Hapus account
if ($module=='account' AND $act=='hapus'){
  $access = delete_security();
  if($access=="allow"){
		$sql = "update account set created_by='' where account_id='$_GET[id]'";
		odbc_exec($conn2,$sql);
  }
  header('location:../../index.php?r='.$module);
}

// Update account
elseif ($module=='account' AND $act=='update'){
  $created_by = $_POST[tipe_biaya].$_POST[typeofcost];
  $sql = "UPDATE account SET created_by = '$created_by'  
                          WHERE account_id   = '$_POST[id]'";

  odbc_exec($conn2,$sql);
  header('location:../../index.php?r='.$module);
}
?>
