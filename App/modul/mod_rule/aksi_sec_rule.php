<?php
session_start();
include "../../../configuration/connection_inc.php";
include "../../../function/security.php";

$module=$_GET[r];
$act=$_GET[act];

// Hapus rule
if ($module=='sec_rule' AND $act=='hapus'){
  $access = delete_security();
  if($access == "allow"){
	mysql_query("DELETE FROM  sec_user_rules WHERE user_id ='$_GET[id]'");
  }
  header('location:../../index.php?r='.$module.'&mod='.$_SESSION[mod]);
}

// Input rule
elseif ($module=='sec_rule' AND $act=='input'){
  for($i=0;$i<$_POST[jml];$i++){
		$module_id = $_POST[module_id.$i];
		$c = $_POST[c.$i];
		$r = $_POST[r.$i];
		$u = $_POST[u.$i];
		$d = $_POST[d.$i];
		mysql_query("INSERT INTO sec_user_rules(user_id,
												module_id,
												c,
												r,
												u,
												d) 
				                       VALUES('$_POST[user_id]',
									          '$module_id',
											  '$c',
											  '$r',
											  '$u',
											  '$d')");
	}
    header('location:../../index.php?r='.$module);
}

// Update rule
elseif ($module=='sec_rule' AND $act=='update'){
    for($i=0;$i<$_POST[jml];$i++){
		$id = $_POST[id.$i];
		$module_id = $_POST[module_id.$i];
		$c = $_POST[c.$i];
		$r = $_POST[r.$i];
		$u = $_POST[u.$i];
		$d = $_POST[d.$i];
        $cek = mysql_query("select * from sec_user_rules where module_id='$module_id' and user_id='$_POST[user_id]'");
		$rcek = mysql_num_rows($cek);
		if($rcek>0){
			mysql_query("update sec_user_rules set c = '$c',r='$r', u ='$u', d = '$d' where id='$id'");
		}else{
			mysql_query("INSERT INTO sec_user_rules(user_id,
											module_id,
											c,
											r,
											u,
											d) 
			                       VALUES('$_POST[user_id]',
								          '$module_id',
										  '$c',
										  '$r',
										  '$u',
										  '$d')");
		}		
	}
    header('location:../../index.php?r='.$module.'&act=edit_sec_rule&id='.$_POST[user_id]);
}
?>
