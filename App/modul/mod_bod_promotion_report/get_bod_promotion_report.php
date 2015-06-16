<?php
session_start();
include "../../../configuration/connection_inc.php";
include "../../../function/security.php";
include "../../../function/get_sql.php";



if ($_GET[data]=='bod_promotion_report'){
	  $access = read_security();
	  if($access=="allow"){
		if(($_POST[bulan]=="")||($_POST[tahun]=="")){
				$sql = "SELECT a.bulan,a.tahun,b.kode_promo,b.tgl_promo,b.title,b.total_sales_target,b.cost_of_promo,b.status 
						FROM master_budget a,reco_request b,detail_reco_item c where a.kode_budget=c.kode_budget and 
						b.kode_promo=c.kode_reco and b.jenis_biaya='P'";
				$tampil=mysql_query($sql);
		}else{
				$sql = "SELECT a.bulan,a.tahun,b.kode_promo,b.tgl_promo,b.title,b.total_sales_target,b.cost_of_promo,b.status 
						FROM master_budget a,reco_request b,detail_reco_item c where a.kode_budget=c.kode_budget and 
						b.kode_promo=c.kode_reco and b.jenis_biaya='P' and a.bulan='$_POST[bulan]' and a.tahun='$_POST[tahun]'";
				$tampil=mysql_query($sql);
		}
		echo "<div id='tabel'>
		        <table>
				<tr>
					<td colspan='6'>Reco</td><td colspan='7'>Claim</td>
				</tr>
				<tr>
					<td>Bulan</td><tD>Tahun</td><tD>Kode Reco</td>
					<td>Tgl Reco</td><tD>Title</td><tD>Total Sales Target</td>
					<td>Cost Of Promo</td><td>Status</td>
				</tr>";
		$no = 1;
		while( $r = mysql_fetch_array($tampil)){
			echo "<tr>
					<td>$r[bulan]</td>
					<tD>$r[tahun]</td>
					<tD>$r[kode_promo]</td>
					<td>$r[tgl_promo]</td>
					<td>$r[title]</td>
					<tD>".number_format($r[total_sales_target],0,'.',',')."</td>
					<td>".number_format($r[cost_of_promo],0,'.',',')."</td>
					<td>$r[status]</td>
				</tr>";
			$no++;
		}
		echo "</table></div>";
	  }else{
		msg_security();
	  }
}
?>
