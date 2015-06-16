<?php
session_start();
require_once("../configuration/connection_inc.php");

$sql = mysql_query("select * from v_sec_user_rules where md5(user_id)='$_GET[uid]' and password = '$_GET[key]' 
                   and module_id=15 and c=1 and r=1 and u=1 and d=1 ");
$cek = mysql_num_rows($sql);
$r = mysql_fetch_array($sql);
	if($cek>0){
		//register session 
		$_SESSION[user_id] = $r[user_id];
		$_SESSION[password] = $r[password];
		$_SESSION[grade_id] = $r[grade_id];
		$_SESSION[divisi_id] = $r[divisi_id];
		$_SESSION[url] = $r[app_location];
		$_SESSION[pro_id] = '1';
		$url = $_SESSION[url];
		$_SESSION[app] = 'Budgeting';
		header('Location:'.$url.'index.php?r=approvalbudget&act='.$_GET[act].'&mod=15&kb='.$_GET[kb]);
	}else{
		$ip = $_SERVER[REMOTE_ADDR];
		$host = gethostbyaddr($ip);
		echo "<p align='center'><br><bR><b>Percobaan Ilegal ke system</b><br><br>IP Anda : $ip<br>Nama Komputer : $host<br><br>
				<a href='../index.php'>Klik disini untuk Login</a></p>";
	}
?>