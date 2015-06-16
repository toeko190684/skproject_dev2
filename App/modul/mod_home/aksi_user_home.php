<?php
session_start();
include "../../../configuration/connection_inc.php";
include "../../../function/security.php";

$users=$_GET[r];
$act=$_GET[act];


$uploads_dir = "../../../images/";
$tmp_name = $_FILES["gambar"]["tmp_name"];
$filename = $_FILES["gambar"]["name"];


if($users=='home' AND $act=='updateprofil'){
   	$sql = mysql_query("UPDATE sec_users SET   full_name = '$_POST[full_name]',
											   email = '$_POST[email]',
											   hp   = '$_POST[hp]'												   
	                          WHERE user_id   = '$_POST[id]'");
	if($sql){
		$_SESSION[pesan] = "Berhasil Memperbarui Data.!";
	}else{
		$_SESSION[pesan] = "Gagal Memperbarui Data.!";
	}
	header('location:../../index.php?r='.$users);
	
}elseif($users=='home' AND $act=='gantipassword'){
	$password_lama  = md5($_SESSION[user_id]."@".$_POST[password_lama]);
	if($password_lama <> $_SESSION[password]){
		$_SESSION[pesan] = "Password lama tidak sesuai..!";
	}else{
		if($_POST[password_baru]==""){
			$_SESSION[pesan] = "Password baru tidak boleh kosong..!";		
		}elseif($_POST[konfirmasi_password]==""){
			$_SESSION[pesan] = "Konfirmasi password tidak boleh kosong..!";	
		}else{
			if($_POST[password_baru]<>$_POST[konfirmasi_password]){
				$_SESSION[pesan] = "Password baru dan konfirmasi tidak sesuai..!";	
			}else{ 
				$password = md5($_SESSION[user_id]."@".$_POST[password_baru]);
				$sql = mysql_query("UPDATE sec_users SET   password = '$password' WHERE user_id   = '$_SESSION[user_id]'");
				if($sql){
					$_SESSION[pesan] = "Berhasil Mengganti Password.!";
				}else{
					$_SESSION[pesan] = "Gagal Mengganti Password.!";
				}
			}
		}
	}	
	header('location:../../index.php?r='.$users);
}elseif($users=='home' AND $act=='gantigambar'){
	if(empty($filename)){
			$_SESSION[pesan] = "Silahkan pilih gambar profile.!";
	}elseif(!empty($filename)){
			mysql_query("UPDATE sec_users SET   foto = 'images/$filename'
	                          WHERE user_id   = '$_POST[id]'");
							  								  
			move_uploaded_file($tmp_name,$uploads_dir.$filename);			
	}	
	header('location:../../index.php?r='.$users);
}
?>
