<?php
session_start();
include "../../../configuration/connection_inc.php";
include "../../../function/security.php";

$module=$_GET[r];
$act=$_GET[act];

// Hapus distributor
if ($module=='distributor' AND $act=='hapus'){
  $access = delete_security();
  if($access=="allow"){
		mysql_query("DELETE FROM distributor WHERE distributor_id ='$_GET[id]'");
  }
  header('location:../../index.php?r='.$module.'&mod='.$_SESSION[module]);
}

// Input distributor
elseif ($module=='distributor' AND $act=='input'){
  // Input data distributor
  mysql_query("INSERT INTO distributor(distributor_id,
                                         distributor_name,
										 deskripsi,
										 area_id) 
	                       VALUES('$_POST[distributor_id]',
						          '$_POST[distributor_name]',
								  '$_POST[deskripsi]',
								  '$_POST[area]')");
  header('location:../../index.php?r='.$module);
}

// Update distributor
elseif ($module=='distributor' AND $act=='update'){
  mysql_query("UPDATE distributor SET distributor_name = '$_POST[distributor_name]',
                                      deskripsi = '$_POST[deskripsi]',
									  area_id = '$_POST[area]'
                          WHERE distributor_id   = '$_POST[id]'");
  header('location:../../index.php?r='.$module);
}
?>
