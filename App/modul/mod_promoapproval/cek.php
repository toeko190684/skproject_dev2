<?php
session_start();
include "../../../configuration/connection_inc.php";
include "../../../function/security.php";
include "../../../function/get_sql.php";


//pengecekan apakah session user sama dengan user id yang diapproval limit
if ($_GET[data]=='limit'){
	$sql = $db->query("select * from approval_limit");
	$r = $sql->fetch(PDO::FETCH_OBJ);
	if($r->user_id == $_GET[user_id]){
		echo "true";
	}else{
		echo "false";
	}
}




?>