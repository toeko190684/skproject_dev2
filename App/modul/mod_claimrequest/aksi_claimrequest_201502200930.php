<?php
session_start();
include "../../../configuration/connection_inc.php";
include "../../../function/security.php";

$module=$_GET[r];
$act=$_GET[act];
$tgl = date('d-m-Y H:m:s');

// Hapus cabang
if ($module=='claimrequest' AND $act=='hapus'){
  $access = delete_security();
  if($access=="allow"){
	  $sql = mysql_query("DELETE FROM claim_request WHERE claim_number_system ='$_GET[id]' and status='pending'");
	
	  //jika berhasil hapus ke tabel claim_request maka update tabel master setup dikurang 1.
	  if($sql){
		  mysql_query("update master_setup set number=number-1 where divisi_id='$_SESSION[divisi_id]' and department_id='$_SESSION[department_id]' 
		              and module_id='$_SESSION[mod]'");  
		  header('location:../../index.php?r='.$module.'&mod='.$_SESSION[module]);
	  }else{
			echo "<script>alert(\"Tidak dapat menghapus claim tersebut, silahkan hubungi admin..!\");
			     window.history.go(-1);</script>";
	  }	  
  }
}

// Input cabang
elseif ($module=='claimrequest' AND $act=='input'){
	  // Input data cabang
	  $sql = mysql_query("INSERT INTO claim_request (claim_number_system,
					                                 claim_number_dist,
													 distributor_id,
													 claim_date,
													 kode_promo,
													 po_so_number,
													 ppn,
													 pph,
													 nomor_faktur_pajak,
													 deskripsi,
													 costofpromo,
													 costofpromoleft,
													 claim_approved_ammount,
													 coa,
													 status,
													 created_by,
													 last_update) 
											VALUES ('$_POST[claimnumbersystem]', 
											        '$_POST[claimnumberdist]',
													'$_POST[distributor]',
													'$_POST[claimdate]',
													'$_POST[kodereco]',
													'$_POST[posonumber]',
													'$_POST[ppn]',
													'$_POST[pph]',
													'$_POST[nomorfakturpajak]',
													'$_POST[deskripsi]',
													'$_POST[costofpromo]',
													'$_POST[costofpromoleft]',
													'$_POST[claimapproveamount]',
													'$_POST[coa]',
													'pending',
													'$_SESSION[user_id]',
													'$tgl')");
													
													echo "INSERT INTO claim_request (claim_number_system,
					                                 claim_number_dist,
													 distributor_id,
													 claim_date,
													 kode_promo,
													 po_so_number,
													 ppn,
													 pph,
													 nomor_faktur_pajak,
													 deskripsi,
													 costofpromo,
													 costofpromoleft,
													 claim_approved_ammount,
													 coa,
													 status,
													 created_by,
													 last_update) 
											VALUES ('$_POST[claimnumbersystem]', 
											        '$_POST[claimnumberdist]',
													'$_POST[distributor]',
													'$_POST[claimdate]',
													'$_POST[kodereco]',
													'$_POST[posonumber]',
													'$_POST[ppn]',
													'$_POST[pph]',
													'$_POST[nomorfakturpajak]',
													'$_POST[deskripsi]',
													'$_POST[costofpromo]',
													'$_POST[costofpromoleft]',
													'$_POST[claimapproveamount]',
													'$_POST[coa]',
													'pending',
													'$_SESSION[user_id]',
													'$tgl')";
								  
	  //jika berhasil disimpan ke tabel claim_request maka update tabel master setup
	  if($sql){
		  mysql_query("update master_setup set number=number +1 where divisi_id='$_POST[divisi]' and department_id='$_POST[departemen]' 
		              and module_id='$_POST[module]'");  
	  }		  
	  //header('location:../../index.php?r='.$module);
}

// Update cabang
elseif ($module=='claimrequest' AND $act=='update'){
		  mysql_query("UPDATE claim_request set claim_number_dist='$_POST[claimnumberdist]',
											 distributor_id='$_POST[distributor]',
											 claim_date='$_POST[claimdate]',
											 kode_promo='$_POST[kodereco]',
											 po_so_number='$_POST[posonumber]',
											 nomor_faktur_pajak='$_POST[nomorfakturpajak]',
											 deskripsi='$_POST[deskripsi]',
											 costofpromo='$_POST[costofpromo]',
											 costofpromoleft='$_POST[costofpromoleft]',
											 claim_approved_ammount='$_POST[claimapproveamount]',
											 created_by = '$_SESSION[user_id]',
											 last_update = '$tgl'
									where claim_number_system = '$_POST[id]'");
		 header('location:../../index.php?r='.$module);
}
?>
