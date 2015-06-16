<?php
session_start();
include "../../../configuration/connection_inc.php";
include "../../../function/security.php";

$module=$_GET[r];
$act=$_GET[act];

// Hapus modul
if ($module=='sec_app_module' AND $act=='hapus'){
  $access = delete_security();
  if($access =="allow"){
	mysql_query("DELETE FROM sec_app_module WHERE module_id ='$_GET[id]'");
	
	// delete module yang ada di table sec_user_rules juga
	mysql_query("delete from sec_user_rules where module_id='$_GET[id]'");
  }
  header('location:../../index.php?r='.$module);
}

// Input modul
elseif ($module=='sec_app_module' AND $act=='input'){
  $sql = mysql_query("select max(module_id)+1 as max from sec_app_module");
  $r = mysql_fetch_array($sql);
  // Input data modul
  mysql_query("INSERT INTO sec_app_module (module_id,
									module_name,
									link,
									app_id) 
	                       VALUES('$r[max]',
								  '$_POST[module_name]',
						          '$_POST[link]',
								  '$_POST[aplikasi]')");
	//cari user seluruhnya
	$user = mysql_query("select * from sec_users where grade_id<>'*'");
	while($ruser = mysql_fetch_array($user)){
		//tambahkan kedalam tabel sec_user_rules
		mysql_query("insert into sec_user_rules(user_id,module_id,c,r,u,d)values('$ruser[user_id]','$r[max]',0,0,0,0)");
	}
  header('location:../../index.php?r='.$module);
}

// Update modul
elseif ($module=='sec_app_module' AND $act=='update'){
  mysql_query("UPDATE sec_app_module SET module_name = '$_POST[module_name]',
                                       link = '$_POST[link]',
									   app_id = '$_POST[aplikasi]'	
                          WHERE module_id   = '$_POST[id]'");
  header('location:../../index.php?r='.$module);
}
?>
