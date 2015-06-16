<?php
session_start();
include "../../../configuration/connection_inc.php";
include "../../../function/security.php";
include "../../../function/get_sql.php";

if ($_GET[data]=='tabel'){
	echo "		<table class='table table-condensed table-hover table-bordered' >
					<tr class='success'>
							<td>No</td><td>Kode Budget</td><tD>Periode</td><td>Keterangan</td><td>Value</td><td>Outstanding</td>
							<td>Cost</td><td>Status</td><td>aksi</td>
						</tr>";
						
				if($_POST[grade_id]=="*"){
					$tampil=mysql_query("SELECT distinct * FROM master_budget where tahun='$_POST[tahun]' and bulan='$_POST[bulan]'");
				}else{
				    if($_POST[grade_id]=="**"){
							$tampil=mysql_query("SELECT distinct * FROM master_budget where 
							                    divisi_id='$_POST[divisi_id]' and tahun='$_POST[tahun]' and bulan='$_POST[bulan]'");			
					}else{
							$tampil=mysql_query("SELECT distinct * FROM master_budget where divisi_id='$_POST[divisi_id]' and
												department_id='$_POST[department_id]' and tahun='$_POST[tahun]' and bulan='$_POST[bulan]'");
					}
				}
			   $no = 1;
			   $cek = mysql_num_rows($tampil);
			   if($cek >0){
					while ($r=mysql_fetch_array($tampil)){
				      $cost =mysql_query("select $r[value]-ifnull(sum(a.value),0)as outstanding_cost,ifnull(sum(a.value),0)as cost from detail_reco_item a, 
										   reco_request c where a.kode_reco=c.kode_promo and a.kode_budget='$r[kode_budget]' and c.status<>'rejected' 
										   and c.complete<>'' ");											   
										   
					  $rcost = mysql_fetch_array($cost);
					  echo "<tbody<tr>
					            <td>$no</td>
								<td>$r[kode_budget]</td>
								<td>$r[bulan]-$r[tahun]</td>
								<td>$r[keterangan]</td>
								<td>".number_format($r[value],0,'.','.')."</td>
								<td>".number_format($rcost[outstanding_cost],0,'.','.')."</td>
								<td>".number_format($rcost[cost],0,'.','.')."</td>
								<td>$r[status]</td>
					            <td><a href='index.php?r=masterbudget&act=editmasterbudget&id=$r[kode_budget]'>Edit</a> | 
						              <a href='$aksi?r=masterbudget&act=hapus&id=$r[kode_budget]'>Hapus</a>
					            </td>
							</tr></tbody>";
							$no++;
				    }
				}else{
					echo "<tr><td colspan=11>Tidak ada data ditemukan..!</td></tr>";
				}
			    echo "</table><br><bR>";
}

if ($_GET[data]=='all'){
	echo "		<table class='table table-condensed table-hover table-bordered' >
					<tr class='success'>
							<td>No</td><td>Kode Budget</td><tD>Periode</td><td>Keterangan</td><td>Value</td><td>Outstanding</td>
							<td>Cost</td><td>Status</td><td>aksi</td>
						</tr>";
						
				if($_POST[grade_id]=="*"){
					$tampil=mysql_query("SELECT distinct * FROM master_budget where tahun='$_POST[tahun]'");
				}else{
				    if($_POST[grade_id]=="**"){
							$tampil=mysql_query("SELECT distinct * FROM master_budget where 
							                    divisi_id='$_POST[divisi_id]' and tahun='$_POST[tahun]'");			
					}else{
							$tampil=mysql_query("SELECT distinct * FROM master_budget where divisi_id='$_POST[divisi_id]' and
												department_id='$_POST[department_id]' and tahun='$_POST[tahun]'");
					}
				}
			   $no = 1;
			   $cek = mysql_num_rows($tampil);
			   if($cek >0){
					while ($r=mysql_fetch_array($tampil)){
				      $cost =mysql_query("select $r[value]-ifnull(sum(a.value),0)as outstanding_cost,ifnull(sum(a.value),0)as cost from detail_reco_item a, 
										   reco_request c where a.kode_reco=c.kode_promo and a.kode_budget='$r[kode_budget]' and c.status<>'rejected' 
										   and c.complete<>'' ");
					  $rcost = mysql_fetch_array($cost);
					  echo "<tbody<tr>
					            <td>$no</td>
								<td>$r[kode_budget]</td>
								<td>$r[bulan]-$r[tahun]</td>
								<td>$r[keterangan]</td>
								<td>".number_format($r[value],0,'.','.')."</td>
								<td>".number_format($rcost[outstanding_cost],0,'.','.')."</td>
								<td>".number_format($rcost[cost],0,'.','.')."</td>
								<td>$r[status]</td>
					            <td><a href='index.php?r=masterbudget&act=editmasterbudget&id=$r[kode_budget]'>Edit</a> | 
						              <a href='$aksi?r=masterbudget&act=hapus&id=$r[kode_budget]'>Hapus</a>
					            </td>
							</tr></tbody>";
							$no++;
				    }
				}else{
					echo "<tr><td colspan=11>Tidak ada data ditemukan..!</td></tr>";
				}
			    echo "</table><br><bR>";
}
?>
