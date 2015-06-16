<?php
$act  = $_GET[act];
$uid  = $_GET[uid];
$key  = $_GET[key];
$kb   = $_GET[kb];
$ip = substr($_SERVER['REMOTE_ADDR'],0,10);
if(($ip == '192.168.21')||('192.168.15')){
	//echo 'IP Anda : ', $_SERVER['REMOTE_ADDR'];
	$url = 'http://192.168.21.4/skproject_new/app/approval_cek.php?act='.$act.'&uid='.$uid.'&key='.$key.'&kb='.$kb;
}else{
	/*echo 'Melalui Proxy Server ', $_SERVER['REMOTE_ADDR'], '<br />';
	echo 'Terkoneksi lewat Engine : ', $_SERVER['HTTP_VIA'], '<br />';
	echo 'IP Anda : ', $_SERVER['HTTP_X_FORWARDED_FOR'], '<br />';*/
	$url = 'http://180.243.158.187/skproject_new/app/approval_cek.php?act='.$act.'&uid='.$uid.'&key='.$key.'&kb='.$kb;
}

header('Location:'.$url);
?>