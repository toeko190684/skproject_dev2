<?php
session_start();
include "../../../configuration/connection_inc.php";
include "../../../function/security.php";
include "../../../function/get_sql.php";



if ($_GET[data]=='acc_reco_claim'){
	  $access = read_security();
	  if($access=="allow"){
		if(($_POST[bulan]=="")||($_POST[tahun]=="")){
				$sql = "select a.bulan,a.tahun, a.department_id, b.kode_reco,c.tgl_promo ,c.title, c.cost_of_promo,
						d.claim_number_system,d.claim_number_dist,d.deskripsi,d.claim_approved_ammount,d.total_claim_approved_ammount,
						d.status,d.journal_id,c.cost_of_promo-(select sum(claim_approved_ammount) from 
						claim_request where kode_promo=c.kode_promo and status<>'rejected')as costofpromoleft  from master_budget a,
						detail_reco_item b, reco_request c, claim_request d where a.kode_budget=b.kode_budget and 
						b.kode_reco=c.kode_promo and c.kode_promo=d.kode_promo and c.status<>'rejected' 
						order by a.bulan,a.tahun,a.department_id, b.kode_reco,d.claim_number_system";
				$tampil=mysql_query($sql);
		}else{
				$sql = "select a.bulan,a.tahun, a.department_id, b.kode_reco,c.tgl_promo ,c.title, c.cost_of_promo,
						d.claim_number_system,d.claim_number_dist,d.deskripsi,d.claim_approved_ammount,d.total_claim_approved_ammount,
						d.status,d.journal_id,c.cost_of_promo-(select sum(claim_approved_ammount) from 
						claim_request where kode_promo=c.kode_promo and status<>'rejected')as costofpromoleft  from master_budget a,
						detail_reco_item b, reco_request c, claim_request d where a.kode_budget=b.kode_budget and 
						b.kode_reco=c.kode_promo and c.kode_promo=d.kode_promo and c.status<>'rejected' and 
						a.bulan='$_POST[bulan]' and a.tahun='$_POST[tahun]' order by a.bulan,a.tahun,a.department_id,
						b.kode_reco,d.claim_number_system";
				$tampil=mysql_query($sql);
		}
		echo "<div id='tabel'>
		        <table>
				<tr>
					<td colspan='6'>Reco</td><td colspan='8'>Claim</td>
				</tr>
				<tr>
					<td>Periode Budget</td><tD>Departemen</td><tD>Kode Reco</td>
					<td>Tgl Reco</td><tD>Description</td><tD>CostOfPromo</td>
					<td>Claim Number system</td><td>Claim Number Dist</td><td>Description</td><td>Claim Approved Ammount</td>
					<td>Total Claim Approved Ammount</td><td>Status</td><td>Journal ID</td>
					<td>Cost Of Promo Left</td>
				</tr>";
		$no = 1;
		while( $r = mysql_fetch_array($tampil)){
			echo "<tr>
					<td>$r[bulan] $r[tahun]</td>
					<tD>$r[department_id]</td>
					<tD>$r[kode_reco]</td>
					<td>$r[tgl_promo]</td>
					<td>$r[title]</td>
					<tD>".number_format($r[cost_of_promo],0,'.',',')."</td>
					<td>$r[claim_number_system]</td>
					<tD>$r[claim_number_dist]</td>
					<tD>$r[deskripsi]</td>
					<td>".number_format($r[claim_approved_ammount],0,'.',',')."</td>
					<td>".number_format($r[total_claim_approved_ammount],0,'.',',')."</td>
					<td>$r[status]</td>
					<td>$r[journal_id]</td>
					<td>".number_format($r[costofpromoleft],0,'.',',')."</td>
				</tr>";
			$no++;
		}
		echo "</table></div>";
	  }else{
		msg_security();
	  }
}
?>
