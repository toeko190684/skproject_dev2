<?php
session_start();
include "../../../configuration/connection_inc.php";
include "../../../function/security.php";
include "../../../function/get_sql.php";



if ($_GET[data]=='acc_claim_report'){
	  $access = read_security();
	  if($access=="allow"){
		if(($_POST[tgl_awal]=="")||($_POST[tgl_akhir]=="")){
				$sql = "select journal_id,deskripsi,kode_promo,costofpromo-(select sum(claim_approved_ammount) 
						from claim_request where kode_promo=a.kode_promo and status<>'rejected')as costofpromoleft,claim_number_system,
						claim_date,claim_number_dist,distributor_id,claim_approved_ammount, total_claim_approved_ammount,status,po_so_number,
						ppn,nomor_faktur_pajak,pph, status, created_by,last_update,approve_by,tgl_approve,journal_id  from claim_request a
						group by journal_id,deskripsi, kode_promo,claim_number_system,claim_approved_ammount,
						total_claim_approved_ammount,status";
				$tampil=mysql_query($sql);
		}else{
				$sql = "select journal_id,deskripsi,kode_promo,costofpromo-(select sum(claim_approved_ammount) 
						from claim_request where kode_promo=a.kode_promo and status<>'rejected')as costofpromoleft,claim_number_system,
						claim_date,claim_number_dist,distributor_id,claim_approved_ammount, total_claim_approved_ammount,status,po_so_number,
						ppn,nomor_faktur_pajak,pph, status, created_by,last_update,approve_by,tgl_approve,journal_id from claim_request a where 
						str_to_date(claim_date,'%m/%d/%Y') between str_to_date('$_POST[tgl_awal]','%m/%d/%Y') 
						and str_to_date('$_POST[tgl_akhir]','%m/%d/%Y') 
						group by journal_id,deskripsi, kode_promo,claim_number_system,claim_approved_ammount,
						total_claim_approved_ammount,status";
				$tampil=mysql_query($sql);
		}
		echo "<div id='tabel'><table>
				<tr>
					<td>No</td><td>Claim Number System</td><td>Claim Number Dist</td>
					<tD>Dist.ID</td><tD>Claim_date</td><td>Kode Reco</td><tD>PO_SO_Number</td>
					<td>PPN</td><tD>PPH</td><tD>Nomor_Faktur_Pajak</td><td>Deskripsi</td>
					<td>CostofPromo</td><td>Cost Of Promo Left</td><td>Claim_approved_ammount</td>
					<tD>TotalClaimApprovedAmmount</td><td>COA</td><td>Status</td><td>Created By</td>
					<td>Last Update</td><td>Approve By</td><td>Tgl Approve</td><td>Journal ID</td>
				</tr>";
		$no = 1;
		while( $r = mysql_fetch_array($tampil)){
			echo "<tr>
					<td>$no</td><td>$r[claim_number_system]</td>
					<td>$r[claim_number_dist]</td><tD>$r[distributor_id]</td>
					<tD>$r[claim_date]</td><td>$r[kode_promo]</td>
					<tD>$r[po_so_number]</td><td>$r[ppn]</td>
					<tD>$r[pph]</td><tD>$r[nomor_faktur_pajak]</td>
					<td>$r[deskripsi]</td><td>".number_format($r[costofpromo],0,'.',',')."</td>
					<td>".number_format($r[costofpromoleft],0,'.',',')."</td>
					<td>".number_format($r[claim_approved_ammount],0,'.',',')."</td>
					<tD>".number_format($r[total_claim_approved_ammount],0,'.',',')."</td><td>$r[coa]</td>
					<td>$r[status]</td><td>$r[created_by]</td>
					<td>$r[last_update]</td><td>$r[approve_by]</td>
					<td>$r[tgl_approve]</td><td>$r[journal_id]</td>
				</tr>";
			$no++;
		}
		echo "</table></div>";
	  }else{
		msg_security();
	  }
}
?>
