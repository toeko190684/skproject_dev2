<?php
session_start();
include "../../../configuration/connection_inc.php";
include "../../../function/security.php";
include "../../../function/get_sql.php";



if ($_GET[data]=='acc_budget_report'){
	  $access = read_security();
	  if($access=="allow"){
		if(($_POST[bulan]=="")||($_POST[tahun]=="")){
				$sql = "SELECT kode_budget,keterangan,bulan,tahun,value,ifnull((select sum(reco_request.cost_of_promo) 
						from detail_reco_item,reco_request where detail_reco_item.kode_reco=reco_request.kode_promo 
						and kode_budget=a.kode_budget and reco_request.status<>'rejected'),0)as used_ammount,
						value-ifnull((select sum(reco_request.cost_of_promo) from detail_reco_item,reco_request where 
						detail_reco_item.kode_reco=reco_request.kode_promo and kode_budget=a.kode_budget 
						and reco_request.status<>'rejected'),0)as outstanding FROM master_budget a";
				$tampil=mysql_query($sql);
		}else{
				$sql = "SELECT kode_budget,keterangan,bulan,tahun,value,ifnull((select sum(reco_request.cost_of_promo) 
						from detail_reco_item,reco_request where detail_reco_item.kode_reco=reco_request.kode_promo 
						and kode_budget=a.kode_budget and reco_request.status<>'rejected'),0)as used_ammount,
						value-ifnull((select sum(reco_request.cost_of_promo) from detail_reco_item,reco_request where 
						detail_reco_item.kode_reco=reco_request.kode_promo and kode_budget=a.kode_budget 
						and reco_request.status<>'rejected'),0)as outstanding FROM master_budget a 
						WHERE bulan='$_POST[bulan]' and tahun='$_POST[tahun]'";
				$tampil=mysql_query($sql);
		}
		echo "<div id='tabel'><table>
				<tr>
					<td>No</td><td>Kode Budget</td><td>Keterangan</td>
					<tD>Bulan</td><tD>Tahun</td><td>Value</td><tD>Used Ammount</td>
					<td>Outstanding</td>
				</tr>";
		$no = 1;
		while( $r = mysql_fetch_array($tampil)){
			echo "<tr>
					<td>$no</td>
					<td>$r[kode_budget]</td>
					<tD>$r[keterangan]</td>
					<tD>$r[bulan]</td>
					<td>$r[tahun]</td>
					<tD>".number_format($r[value],0,'.',',')."</td>
					<td>".number_format($r[used_ammount],0,'.',',')."</td>
					<tD>".number_format($r[outstanding],0,'.',',')."</td>
				</tr>";
			$no++;
		}
		echo "</table></div>";
	  }else{
		msg_security();
	  }
}
?>
