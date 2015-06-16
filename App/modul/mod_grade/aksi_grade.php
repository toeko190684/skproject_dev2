<?php
session_start();
include "../../../configuration/connection_inc.php";
include "../../../function/security.php";
$module=$_GET[r];
$act=$_GET[act];

// Hapus grade
if ($module=='master_grade' AND $act=='hapus'){
  $access = delete_security();
  if($access =="allow"){
		mysql_query("DELETE FROM master_grade WHERE grade_id ='$_GET[id]'");
  }
  header('location:../../index.php?r='.$module.'&mod='.$_SESSION[mod]);
}

// Input grade
elseif ($module=='master_grade' AND $act=='input'){
  // Input data grade
  mysql_query("INSERT INTO master_grade(grade_id,
                                        grade_name,
										description) 
	                       VALUES('$_POST[grade_id]',
						          '$_POST[grade_name]',
								  '$_POST[description]')");
  header('location:../../index.php?r='.$module);
}

// Update grade
elseif ($module=='master_grade' AND $act=='update'){
  mysql_query("UPDATE master_grade SET grade_name = '$_POST[grade_name]',
                                       description = '$_POST[description]'   
                          WHERE grade_id   = '$_POST[id]'");
  header('location:../../index.php?r='.$module);
}
?>
