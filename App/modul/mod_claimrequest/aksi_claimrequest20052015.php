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
	    // cari data dengan pdo 
		$sql = $db->query("select a.*,b.distributor_name from claim_request a,distributor b where a.distributor_id=b.distributor_id  
		                  and a.claim_number_system='".$_GET[id]."'");
		$data = $sql->fetch(PDO::FETCH_OBJ);
		if(strtoupper($data->status) <> 'PENDING'){			
			$_SESSION[pesan] = "<strong>Delete Error !!</strong>, Claim Number System $data->claim_number_system sudah di $data->status ";
		}else{
			$db->query("delete from claim_request where claim_number_system='".$_GET[id]."'");
			$_SESSION[pesan] = "<strong>Delete Success !!</strong>, Claim Number System $data->claim_number_system Successfully Deleted  ";
		}
		header('location:../../index.php?r='.$module);
  }
}

// Input cabang
elseif ($module=='claimrequest' AND $act=='input'){
	$access = create_security();
	if($access=="allow"){  
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
													 total_claim_approved_ammount,
													 coa,
													 vendor_id,
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
													'$_POST[totalclaimapproveamount]',
													'$_POST[coa]',
													'$_POST[vendor_id]',
													'pending',
													'$_SESSION[user_id]',
													'$tgl')");
													
																				  
	  //jika berhasil disimpan ke tabel claim_request maka update tabel master setup
	  if($sql){
			$_SESSION[pesan] = "<strong>Inserted !!</strong>, claim request $_POST[claimnumbersystem] successfully saved ! ";
			mysql_query("update master_setup set number=number +1 where divisi_id='$_POST[divisi]' and department_id='$_POST[departemen]' 
		              and module_id='$_POST[module]'");  
	  }		  
	}
	header('location:../../index.php?r='.$module);
}

// Update cabang
elseif ($module=='claimrequest' AND $act=='update'){
	$access = update_security();
	if($access=="allow"){
		mysql_query("UPDATE claim_request set claim_number_dist='$_POST[claimnumberdist]',
											 distributor_id='$_POST[distributor]',
											 claim_date='$_POST[claimdate]',
											 kode_promo='$_POST[kodepromo]',
											 po_so_number='$_POST[posonumber]',
											 nomor_faktur_pajak='$_POST[nomorfakturpajak]',
											 deskripsi='$_POST[deskripsi]',
											 costofpromo='$_POST[costofpromo]',
											 costofpromoleft='$_POST[costofpromoleft]',
											 claim_approved_ammount='$_POST[claimapproveamount]',
											 total_claim_approved_ammount='$_POST[totalclaimapproveamount]',
											 coa = '$_POST[coa]',
											 vendor_id = '$_POST[vendor_id]',
											 created_by = '$_SESSION[user_id]',
											 last_update = '$tgl'
									where claim_number_system = '$_POST[id]'");
			$_SESSION[pesan] = "<strong>Updated Data!!</strong> , Claim Number System : $_POST[kodepromo] Successfully Update!" ;
	}
	header('location:../../index.php?r='.$module);
}
?>
