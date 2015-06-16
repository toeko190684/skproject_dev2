<?php
session_start();
include "../../../configuration/connection_inc.php";
include "../../../function/security.php";

$module=$_GET[r];
$act=$_GET[act];

// Hapus emailnotification
if ($module=='emailnotification' AND $act=='hapus'){
  $access = delete_security();
  if($access=="allow"){
		mysql_query("DELETE FROM email_notification WHERE id ='$_GET[id]'");
  }
  header('location:../../index.php?r='.$module.'&mod='.$_SESSION[module]);
}

// Input emailnotification
elseif ($module=='emailnotification' AND $act=='input'){
  // Input data emailnotification
  mysql_query("INSERT INTO email_notification (report_id,
							     user_id) 
						VALUES ('$_POST[report_id]', 
						        '$_POST[user_id]')");
								  

  header('location:../../index.php?r='.$module);
}

// Update emailnotification
elseif ($module=='emailnotification' AND $act=='update'){
	 mysql_query("UPDATE email_notification SET  report_id = '$_POST[report_id]',
													 user_id = '$_POST[user_id]'
		                                    WHERE id   = '$_POST[id]'");

  header('location:../../index.php?r='.$module);
}
?>
