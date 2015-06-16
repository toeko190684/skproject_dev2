<?php
session_start();
include "../../../configuration/connection_inc.php";
include "../../../function/security.php";
include "../../../function/get_sql.php";



if ($_GET[data]=='mkt_reco_report'){
	  $access = read_security();
	  if($access=="allow"){
		if(($_POST[bulan]=="")||($_POST[tahun]=="")){
				$sql = "SELECT a.* FROM reco_request a,detail_reco_item b,master_budget c where
						a.kode_promo=b.kode_reco and b.kode_budget=c.kode_budget and c.bulan='$_POST[bulan]' and c.tahun='$_POST[tahun]'";
				$tampil=mysql_query($sql);
		}else{
				$sql = "SELECT a.* FROM reco_request a,detail_reco_item b,master_budget c where
						a.kode_promo=b.kode_reco and b.kode_budget=c.kode_budget and c.bulan='$_POST[bulan]' and c.tahun='$_POST[tahun]'
						and c.department_id='$_SESSION[department_id]'";
				$tampil=mysql_query($sql);
		}
		echo "<div id='tabel'>
		        <table>
				<tr>
					<td>Kode Promo</td><tD>Tgl Promo</td><tD>Area ID</td>
					<td>Distributor ID</td><tD>Group Promo ID</td><tD>Promotype ID</td>
					<td>Class ID</td><td>Account ID</td><td>Title</td>
					<td>Tgl Awal</td><td>Tgl Akhir</td><td>Total Sales Target</td>
					<td>Background</td><td>Promo Mechanisme</td><td>Claim Mechanisme</td>
					<td>Claim Trade Off</td><td>Cost Of Promo</td><td>Type of Cost</td>
					<td>Cost Rasio</td><td>Status</td><td>Complete</td><td>Tgl Complete</td>
					<td>Jenis Biaya</td><td>Created By</td><td>Last Update</td><td>Approval1</td>
					<tD>Tgl Approval1</td><td>Approval2</td><td>Tgl Approval2</td>
				</tr>";
		$no = 1;
		while( $r = mysql_fetch_array($tampil)){
			echo "<tr>
					<td>$r[kode_promo]</td>
					<tD>$r[tgl_promo]</td>
					<tD>$r[area_id]</td>
					<td>$r[distributor_id]</td>
					<td>$r[grouppromo_id]</td>
					<tD>$r[promotype_id]</td>
					<td>$r[class_id]</td>
					<tD>$r[account_id]</td>
					<td>$r[title]</td>
					<td>$r[tgl_awal]</td>
					<td>$r[tgl_akhir]</td>
					<td>".number_format($r[total_sales_target],0,'.',',')."</td>
					<tD>$r[background]</td>
					<tD>$r[promo_mechanisme]</td>
					<td>$r[claim_mechanisme]</td>
					<td>$r[claimtradeoff]</td>
					<td>".number_format($r[cost_of_promo],0,'.',',')."</td>
					<tD>$r[typeofcost]</td>
					<td>$r[cost_rasio]</td>
					<td>$r[status]</td>
					<td>$r[complete]</td>
					<td>$r[tgl_complete]</td>
					<td>$r[jenis_biaya]</td>
					<td>$r[created_by]</td>
					<td>$r[last_update]</td>
					<td>$r[approval1]</td>
					<tD>$r[tgl_approval1]</td>
					<td>$r[approval2]</td>
					<td>$r[tgl_approval2]</td>
				</tr>";
			$no++;
		}
		echo "</table></div>";
	  }else{
		msg_security();
	  }
}
?>
