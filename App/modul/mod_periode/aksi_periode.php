<?php
session_start();
include "../../../configuration/connection_inc.php";
include "../../../function/security.php";
$module=$_GET[r];
$act=$_GET[act];

// Hapus grade
if ($module=='periode' AND $act=='hapus'){
  $access = delete_security();
  if($access =="allow"){
		mysql_query("DELETE FROM periode WHERE periode_id ='$_GET[id]' and status='Open'");
  }
  header('location:../../index.php?r='.$module.'&mod='.$_SESSION[mod]);
}

// Input grade
elseif ($module=='periode' AND $act=='input'){
  // Input data grade
	mysql_query("INSERT INTO periode(bulan,
                                        tahun,
										status) 
	                       VALUES('$_POST[bulan]',
						          '$_POST[tahun]',
								  '$_POST[status]')");
	  header('location:../../index.php?r='.$module);
}

// Update grade
elseif ($module=='periode' AND $act=='update'){
    $sql = mysql_query("select a.* from reco_request a,detail_reco_item b,master_budget c where a.kode_promo=b.kode_reco 
						and b.kode_budget=c.kode_budget and a.status='pending' and c.bulan='$_POST[bulan]' and c.tahun='$_POST[tahun]'");
	$r = mysql_num_rows($sql);
	if($r>0){
		$_SESSION[pesan] = 'Tidak bisa di Tutup karena masih ada reco pending!!';
	}else{
		mysql_query("UPDATE periode SET bulan = '$_POST[bulan]',
										tahun = '$_POST[tahun]',
										status = '$_POST[status]'
							  WHERE periode_id   = '$_POST[id]'");
	}
	header('location:../../index.php?r='.$module);
}
?>
