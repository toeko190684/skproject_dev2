<?php
session_start();
include "../../../configuration/connection_inc.php";
include "../../../function/security.php";

$module=$_GET[r];
$act=$_GET[act];

// Hapus vendor
if ($module=='vendor' AND $act=='hapus'){
  $access = delete_security();
  if($access=="allow"){
		mysql_query("DELETE FROM vendor WHERE vendor_id ='$_GET[id]'");
  }
  header('location:../../index.php?r='.$module.'&mod='.$_SESSION[module]);
}

// Input vendor
elseif ($module=='vendor' AND $act=='input'){
  // Input data vendor
  mysql_query("INSERT INTO vendor(vendor_id,
                                  vendor_name,
								  ap_account_type,
								  ap_account_id) 
	                       VALUES('$_POST[vendor_id]',
						          '$_POST[vendor_name]',
								  '$_POST[ap_account_type]',
								  '$_POST[ap_account_id]')");
	header('location:../../index.php?r='.$module);
}

// Update vendor
elseif ($module=='vendor' AND $act=='update'){
  mysql_query("UPDATE vendor SET vendor_name = '$_POST[vendor_name]',
                                 ap_account_type = '$_POST[ap_account_type]',
								 ap_account_id = '$_POST[ap_account_id]'
                          WHERE vendor_id   = '$_POST[id]'");
  header('location:../../index.php?r='.$module);
}
?>
