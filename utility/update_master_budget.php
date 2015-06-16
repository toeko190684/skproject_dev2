<?php
include "../configuration/connection_inc.php";
$sql = mysql_query("select * from master_budget");
while($r = mysql_fetch_array($sql)){
	//cari kode budget di approval 
	$cari = mysql_query("select * from approval_budget where kode_budget='$r[kode_budget]'");
	$rcari = mysql_fetch_array($cari);
	//update kode budget
	$update =mysql_query( "update master_budget set status='$rcari[status]',approval1='$rcari[user_id]', tgl_approval1='$rcari[tgl_approve]' 
	                      where kode_budget='$r[kode_budget]'");
	if($update){
		echo "berhasil<bR>";
	}else{
		echo "gagal<br>";
	}
}
?>