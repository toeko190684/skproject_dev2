<?php
session_start();
include "../../../configuration/connection_inc.php";
include "../../../function/security.php";

$module=$_GET[r];
$act=$_GET[act];

// Hapus emailnotification
if ($module=='masterreport' AND $act=='hapus'){
  $access = delete_security();
  if($access=="allow"){
		mysql_query("DELETE FROM master_report WHERE report_id ='$_GET[id]'");
  }
  header('location:../../index.php?r='.$module.'&mod='.$_SESSION[module]);
}

// Input emailnotification
elseif ($module=='masterreport' AND $act=='input'){
  // Input data emailnotification
  mysql_query("INSERT INTO master_report (report_name,
                                 file,
								 jam,
								 keterangan) 
						VALUES ('$_POST[report_name]', 
						        '$_POST[file]',
						        '$_POST[jam]',
								'$_POST[keterangan]')");
								  

  header('location:../../index.php?r='.$module);
}

// Update emailnotification
elseif ($module=='masterreport' AND $act=='update'){
	if($_POST[file]==""){
	    mysql_query("UPDATE master_report SET report_name = '$_POST[report_name]',
		                                      jam = '$_POST[jam]',
	                                          keterangan = '$_POST[keterangan]'
			                        WHERE report_id   = '$_POST[id]'");
	}else{
	    mysql_query("UPDATE master_report SET report_name = '$_POST[report_name]',
		                                      file = '$_POST[file]',
		                                      jam = '$_POST[jam]',
	                                          keterangan = '$_POST[keterangan]'
			                        WHERE report_id   = '$_POST[id]'");
	}
	header('location:../../index.php?r='.$module);
}
?>
