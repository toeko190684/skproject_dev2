<?php
session_start();
include "../../../configuration/connection_inc.php";
include "../../../function/security.php";

$module=$_GET[r];
$act=$_GET[act];


// Update divisi
if ($module=='approval_limit' AND $act=='update'){
	$access = update_security();
	if($access=="allow"){
		$sql = mysql_query("UPDATE approval_limit SET nominal = '$_POST[nominal]', user_id = '$_POST[user_id]'  
                          WHERE id=1");
		
		if($sql){
			$_SESSION[pesan] = "<strong>Update sukses !!</strong>, Approval limit Successfully Updated  ";
		}
		header('location:../../index.php?r='.$module);
	}else{
		msg_security();
	}
}
?>
