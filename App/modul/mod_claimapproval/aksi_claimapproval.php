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
								 nomor_faktur_pajak,
								 deskripsi,
								 costofpromo,
								 costofpromoleft,
								 claim_approved_ammount,
								 status,
								 created_by,
								 last_update) 
						VALUES ('$_POST[claimnumbersystem]', 
						        '$_POST[claimnumberdist]',
								'$_POST[distributor]',
								'$_POST[claimdate]',
								'$_POST[kodereco]',
								'$_POST[posonumber]',
								'$_POST[nomorfakturpajak]',
								'$_POST[deskripsi]',
								'$_POST[costofpromo]',
								'$_POST[costofpromoleft]',
								'$_POST[claimapproveamount]',
								'pending',
								'$_SESSION[user_id]',
								'$tgl')");
								  
	  //jika berhasil disimpan ke tabel claim_request maka update tabel master setup
	  if($sql){
		  mysql_query("update master_setup set number=number +1 where divisi_id='$_POST[divisi]' and department_id='$_POST[departemen]' 
		              and module_id='$_POST[module]'");  
	  }		  
	  header('location:../../index.php?r='.$module);
}

// Update cabang
elseif ($module=='claimapproval' AND $act=='update'){
          $tgl = date('d-m-Y H:m:s');
		  $tgl2 = date('Y-m-d');
		  
		  $sql = mysql_query("UPDATE claim_request set status ='$_GET[status]',
											    approve_by ='$_SESSION[user_id]',
											    tgl_approve ='$tgl'
									where claim_number_system = '$_GET[id]'");
									
		  //membuat nomor ap_journal
		  $no = "select 
					case len(isnull(max(substring(journal_id,9,4)),0)+1)
						when 1 then '000'
				        when 2 then '00'
				        when 3 then '0'
				    end as prefix,
					isnull(max(substring(journal_id,9,4)),0)+1 as number
				from ap_journal where journal_date='$tgl2'";
				
		  $qno = odbc_exec($conn2,$no);
		  $rno = odbc_fetch_array($qno);
          $journal_id = date('Ymd').$rno[prefix].$rno[number]; //nomor journal_id  ditabel ap_journal

		  
		  if($sql){					
                if($_GET[status]=='approved'){		  
					  //cari dulu claim request yang ada 
					  $cari = mysql_query("select * from claim_request where claim_number_system = '$_GET[id]'");
					  $rcari = mysql_fetch_array($cari);
					  $claim_number = substr($rcari[claim_number_system],14,10);
					  $reco_number = substr($rcari[kode_promo],14,10);
					  $ppn = $rcari[ppn]*$rcari[claim_approved_ammount]/100;
					  
					  $account = "select account_id,account_type from account where account_id='$rcari[coa]'";
					  $qaccount = odbc_exec($conn2,$account);
					  $raccount = odbc_fetch_array($qaccount);
					  
					  $sql = "INSERT INTO [kinosentraacc].[dbo].[ap_journal]([user_id]
																           ,[last_update]
																           ,[created_by]   
																           ,[company]
																           ,[branch]
																           ,[journal_id]
																           ,[journal_date]
																           ,[description]
																           ,[vendor_id]
																           ,[po_id]
																           ,[po_rev]
																           ,[debet]
																           ,[credit]
																           ,[due_date]
																           ,[paid]
																           ,[paid_date]
																           ,[posted]
																           ,[ok]
																           ,[account_type]
																           ,[account_id]
																           ,[check_no]
																           ,[check_date]
																           ,[c_symbol]
																           ,[ppn_no]
																           ,[ppn]
																           ,[vat_date]
																           ,[vinvoice_id]
																           ,[vinvoice_date]
																           ,[ap_account_type]
																           ,[ap_account_id]
																           ,[as_account_type]
																           ,[as_account_id]
																           ,[vat_account_type]
																           ,[vat_account_id]
																           ,[transaction_id]
																           ,[rec_id])
																     VALUES
																           ('$_SESSION[user_id]'
																           ,getdate()
																           ,'$_SESSION[user_id]'
																           ,'PT MORINAGA KINO INDONESIA'
																           ,'JAKARTA'
																           ,'$journal_id'
																           ,getdate()
																           ,'clm : $rcari[claim_number_system] rec : $rcari[kode_promo]'
																           ,''
																           ,'$claim_number'
																           ,''
																           ,0
																           ,$rcari[claim_approved_ammount]
																           ,getdate()
																           ,0
																           ,''
																           ,0
																           ,0
																           ,''
																           ,''
																           ,''
																           ,''
																           ,'IDR'
																           ,'$r[nomor_faktur_pajak]'
																           ,$ppn
																           ,''
																           ,''
																           ,''
																           ,$raccount[account_type]
																           ,$raccount[account_id]
																           ,''
																           ,''
																           ,''
																           ,''
																           ,''
																           ,'$reco_number')";
					  odbc_exec($conn2,$sql); //insert ke sql server
	                  //update nomor ap_journal di tabel claim update
					  mysql_query("UPDATE claim_request set journal_id ='$journal_id'
										where claim_number_system = '$_GET[id]'");
			    }
		 }	 
		 header('location:../../index.php?r='.$module);
}
?>
