<?php
/*
created by toeko triyanto
cek login.php adalah file untuk mengecek login dna menuliskan session yang akan digunakan untuk masuk keseluruh aplikasi
*/
require_once("function/get_sql.php");
session_start();

require_once("configuration/connection_inc.php");
$username = $_POST[username];
$password = md5($_POST[username]."@".$_POST[password]);

//cregister session
$_SESSION[pro_id] = $_POST[pro_id];
$_SESSION[pro_name] = $_POST[pro_name];


//cek apakah user tersebut ada di tabel user
$cek = mysql_query("select * from sec_users where user_id='$username' and password='$password'");
$rcek = mysql_num_rows($cek);
$grade = mysql_fetch_array($cek);

//mengecek apakah masuk jaringan lokal atau tidak
if(trim($grade[grade_id])=="*"){
	$app = mysql_query("select distinct app_id,app_location from v_sec_user_rules where pro_id='$_SESSION[pro_id]'");
}else{
	$app = mysql_query("select distinct app_id,app_location from v_sec_user_rules where  pro_id='$_SESSION[pro_id]' and user_id='$username'");
}
$app_path = mysql_fetch_array($app);

$url = 'http://morinaga-kino.co.id/skproject_dev2/app/';



//create tanggal
$data = array();
$tgl = mysql_query("SELECT concat(substring(a.tahun,3,2),case 
															when length(b.month_id)=1 then concat('0',month_id)
															when length(b.month_id)=2 then month_id
														 end) as tanggal
                   FROM periode a,month b where a.bulan=b.month_name and a.status='open'");
$rtgl = mysql_fetch_array($tgl);


if($rcek>0){
		//jika grade untuk user adalah admin (*) kemudian di direct langsung
		if(trim($grade[grade_id])=="*"){
			    //register session 
				$_SESSION[user_id] = $username;
				$_SESSION[password] = $password;
				$_SESSION[grade_id] = $grade[grade_id];
				$_SESSION[divisi_id] = $grade[divisi_id];
				$_SESSION[department_id] = $grade[department_id];	
				$_SESSION[tanggal] = $rtgl[tanggal];
				$_SESSION[url] = $url;
				header('Location:app/index.php?r=home');
		}else{
		   // cari module id 
		   $rapp = mysql_num_rows($app);
		   if($rapp>0){
		       //register session 
				$_SESSION[user_id] = $username;
				$_SESSION[password] = $password;
				$_SESSION[grade_id] = $grade[grade_id];
				$_SESSION[divisi_id] = $grade[divisi_id];
				$_SESSION[department_id] = $grade[department_id];
				$_SESSION[tanggal] = $rtgl[tanggal];
				$_SESSION[url] = $url;
		        header('location:app/index.php?r=home');
		   }else{
			   header('location:login.php?r='.$_SESSION[pro_id].'&n='.$_SESSION[pro_name]);	
		   }
		}
}else{
	$_SESSION[pesan] = 'Username atau password salah !';
	header('location:login.php?r='.$_GET[r].'&n='.$_GET[n]);	
}
?>