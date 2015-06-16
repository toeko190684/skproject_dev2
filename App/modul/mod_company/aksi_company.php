<?php
session_start();
include "../../../configuration/connection_inc.php";
include "../../../function/security.php";

$module=$_GET[r];
$act=$_GET[act];

// Hapus company
if ($module=='company' AND $act=='hapus'){
  $access = delete_security();
  if($access=="allow"){
		mysql_query("DELETE FROM company WHERE company_id ='$_GET[id]'");
  }
  header('location:../../index.php?r='.$module.'&mod='.$_SESSION[module]);
}

// Input company
elseif ($module=='company' AND $act=='input'){
  // Input data company
  mysql_query("INSERT INTO company (company_id,
                                 company_name,
							     alamat,
								 telpon,
								 fax,
								 email,
								 website) 
						VALUES ('$_POST[company_id]', 
						        '$_POST[company_name]', 
								'$_POST[alamat]', 
								'$_POST[telpon]',
								'$_POST[fax]',
								'$_POST[email]',
								'$_POST[website]')");
								  

  header('location:../../index.php?r='.$module);
}

// Update company
elseif ($module=='company' AND $act=='update'){
  mysql_query("UPDATE company SET company_id = '$_POST[company_id]',
                                  company_name = '$_POST[company_name]', 
                                  alamat   = '$_POST[alamat]',
								  telpon = '$_POST[telpon]',
								  fax = '$_POST[fax]',
								  email = '$_POST[email]',
								  website = '$_POST[website]'
                          WHERE company_id   = '$_POST[id]'");
  header('location:../../index.php?r='.$module);
}
?>
