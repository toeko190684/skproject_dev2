<?php
session_start();
include "../../../configuration/connection_inc.php";
include "../../../function/security.php";
include "../../../function/get_sql.php";



if ($_GET[data]=='budget_report'){
	  $access = read_security();
	  if($access=="allow"){
			$tgl_awal = bulan(date('m',strtotime($_POST[tgl_awal]))).date('Y',strtotime($_POST[tgl_awal]));
			$tgl_akhir = bulan(date('m',strtotime($_POST[tgl_akhir]))).date('Y',strtotime($_POST[tgl_akhir]));

			if($_POST[groupby]=='divisi_id'){
				$groupby = 'b.divisi_id';
				$keterangan = 'd.divisi_name';
			}else if($_POST[groupby]=='department_id'){
			    $groupby = 'b.department_id';
				$keterangan = 'e.department_name';
			}else if($_POST[groupby]=='subdepartemen_id'){
				$groupby = 'b.subdepartemen_id';
				$keterangan = 'f.subdepartemen_name';
			}else if($_POST[groupby]=='kode_budget'){
				$groupby = 'b.kode_budget';
				$keterangan = 'b.keterangan';
			}
			
			echo "<table>
					<tR><tD>Group By</td><td>:</td><td>$_POST[groupby]</td></tr>
					<tR><tD>Budget Periode</td><td>:</td><td>$_POST[tgl_awal] S/D $_POST[tgl_akhir]</td></tr>
					<tR><tD>Divisi</td><td>:</td><td>$_POST[divisi]</td></tr>
					<tR><tD>Departemen</td><td>:</td><td>$_POST[departemen]</td></tr>
					<tR><tD>Sub Departemen</td><td>:</td><td>$_POST[subdepartemen]</td></tr>
					<tR><tD>Kode Budget</td><td>:</td><td>$_POST[budget]</td></tr>
				  </table>";
			//jika divisi nya adalah all
			if($_POST[divisi]=='0'){
				if($_POST[departemen]=='*'){
					if($_POST[subdepartemen]=='*'){
						if($_POST[budget]==''){
							if($_POST[approve]=='all'){
								if($_POST[groupby]=='divisi_id'){ 
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Divisi ID</b></td><td ><b>Divisi Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.divisi_id as groupby ,b.divisi_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_divisi b where 
																   a.divisi_id = b.divisi_id and 
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir'
																   group by a.divisi_id,b.divisi_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where 
														                    a.kode_reco=b.kode_promo and a.divisi_id='$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='department_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Departemen ID</b></td><td ><b>Departemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.department_id as groupby ,b.department_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_department b where 
																   a.department_id = b.department_id and 
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir'
																   group by a.department_id,b.department_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where 
														                    a.kode_reco=b.kode_promo and a.departemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='subdepartemen_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Subdepartemen ID</b></td><td ><b>Subdepartemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.subdepartemen_id as groupby ,b.subdepartemen_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_subdepartemen b where 
																   a.department_id = b.department_id and a.subdepartemen_id = b.subdepartemen_id and 
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir'
																   group by a.subdepartemen_id,b.subdepartemen_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where 
														                    a.kode_reco=b.kode_promo and a.subdepartemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";	
								}else if($_POST[groupby]=='kode_budget'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Kode Budget</b></td><td ><b>Keterangan</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select kode_budget as groupby ,keterangan,
												                   concat( substring( bulan, 1, 3 ) , ' ', substring(tahun, 3, 2 ) ) AS periode, 
															       sum(value)as value from master_budget where 
																   concat(bulan,tahun) between '$tgl_awal' and '$tgl_akhir'
																   group by kode_budget,keterangan");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where 
														                    a.kode_reco=b.kode_promo and a.kode_budget = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}
							}else{
								if($_POST[groupby]=='divisi_id'){ 
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Divisi ID</b></td><td ><b>Divisi Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.divisi_id as groupby ,b.divisi_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_divisi b where 
																   a.divisi_id = b.divisi_id and 
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.status='$_POST[approve]'
																   group by a.divisi_id,b.divisi_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where 
														                    a.kode_reco=b.kode_promo and a.divisi_id='$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='department_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Departemen ID</b></td><td ><b>Departemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.department_id as groupby ,b.department_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_department b where 
																   a.department_id = b.department_id and 
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.status='$_POST[approve]'
																   group by a.department_id,b.department_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where 
														                    a.kode_reco=b.kode_promo and a.departemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='subdepartemen_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Subdepartemen ID</b></td><td ><b>Subdepartemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.subdepartemen_id as groupby ,b.subdepartemen_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_subdepartemen b where 
																   a.department_id = b.department_id and a.subdepartemen_id = b.subdepartemen_id and 
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.status='$_POST[approve]'
																   group by a.subdepartemen_id,b.subdepartemen_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where 
														                    a.kode_reco=b.kode_promo and a.subdepartemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";	
								}else if($_POST[groupby]=='kode_budget'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Kode Budget</b></td><td ><b>Keterangan</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select kode_budget as groupby ,keterangan,
												                   concat( substring( bulan, 1, 3 ) , ' ', substring(tahun, 3, 2 ) ) AS periode, 
															       sum(value)as value from master_budget where 
																   concat(bulan,tahun) between '$tgl_awal' and '$tgl_akhir' and status='$_POST[approve]'
																   group by kode_budget,keterangan");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where 
														                    a.kode_reco=b.kode_promo and a.kode_budget = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}
							}
						}else{
							if($_POST[approve]=='all'){
								if($_POST[groupby]=='divisi_id'){ 
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Divisi ID</b></td><td ><b>Divisi Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.divisi_id as groupby ,b.divisi_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_divisi b where 
																   a.divisi_id = b.divisi_id and a.kode_budget='$_POST[budget]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir'
																   group by a.divisi_id,b.divisi_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where 
														                    a.kode_reco=b.kode_promo and a.divisi_id='$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='department_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Departemen ID</b></td><td ><b>Departemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.department_id as groupby ,b.department_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_department b where 
																   a.department_id = b.department_id and a.kode_budget='$_POST[budget]' and 
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir'
																   group by a.department_id,b.department_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where 
														                    a.kode_reco=b.kode_promo and a.departemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='subdepartemen_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Subdepartemen ID</b></td><td ><b>Subdepartemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.subdepartemen_id as groupby ,b.subdepartemen_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_subdepartemen b where 
																   a.department_id = b.department_id and a.subdepartemen_id = b.subdepartemen_id and 
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.kode_budget='$_POST[budget]'
																   group by a.subdepartemen_id,b.subdepartemen_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where 
														                    a.kode_reco=b.kode_promo and a.subdepartemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";	
								}else if($_POST[groupby]=='kode_budget'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Kode Budget</b></td><td ><b>Keterangan</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select kode_budget as groupby ,keterangan,
												                   concat( substring( bulan, 1, 3 ) , ' ', substring(tahun, 3, 2 ) ) AS periode, 
															       sum(value)as value from master_budget where 
																   concat(bulan,tahun) between '$tgl_awal' and '$tgl_akhir' and kode_budget='$_POST[budget]'
																   group by kode_budget,keterangan");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where 
														                    a.kode_reco=b.kode_promo and a.kode_budget = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}
							}else{
								if($_POST[groupby]=='divisi_id'){ 
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Divisi ID</b></td><td ><b>Divisi Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.divisi_id as groupby ,b.divisi_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_divisi b where 
																   a.divisi_id = b.divisi_id and a.kode_budget='$_POST[budget]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.status='$_POST[approve]'
																   group by a.divisi_id,b.divisi_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where 
														                    a.kode_reco=b.kode_promo and a.divisi_id='$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='department_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Departemen ID</b></td><td ><b>Departemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.department_id as groupby ,b.department_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_department b where 
																   a.department_id = b.department_id and a.kode_budget='$_POST[budget]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.status='$_POST[approve]'
																   group by a.department_id,b.department_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where 
														                    a.kode_reco=b.kode_promo and a.departemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='subdepartemen_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Subdepartemen ID</b></td><td ><b>Subdepartemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.subdepartemen_id as groupby ,b.subdepartemen_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_subdepartemen b where 
																   a.department_id = b.department_id and a.subdepartemen_id = b.subdepartemen_id and 
																   a.kode_budget='$_POST[budget]' 
																   and concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.status='$_POST[approve]'
																   group by a.subdepartemen_id,b.subdepartemen_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where 
														                    a.kode_reco=b.kode_promo and a.subdepartemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";	
								}else if($_POST[groupby]=='kode_budget'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Kode Budget</b></td><td ><b>Keterangan</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select kode_budget as groupby ,keterangan,
												                   concat( substring( bulan, 1, 3 ) , ' ', substring(tahun, 3, 2 ) ) AS periode, 
															       sum(value)as value from master_budget where kode_budget='$_POST[budget]' and
																   concat(bulan,tahun) between '$tgl_awal' and '$tgl_akhir' and status='$_POST[approve]'
																   group by kode_budget,keterangan");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where 
														                    a.kode_reco=b.kode_promo and a.kode_budget = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}
							}
						}
					}else{//jika subdepartemennya bukan all
						if($_POST[budget]==''){
							if($_POST[approve]=='all'){
								if($_POST[groupby]=='divisi_id'){ 
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Divisi ID</b></td><td ><b>Divisi Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.divisi_id as groupby ,b.divisi_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_divisi b where 
																   a.divisi_id = b.divisi_id and a.subdepartemen_id = '$_POST[subdepartemen]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir'
																   group by a.divisi_id,b.divisi_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.subdepartemen_id='$_POST[subdepartemen]' and
														                    a.kode_reco=b.kode_promo and a.divisi_id='$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='department_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Departemen ID</b></td><td ><b>Departemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.department_id as groupby ,b.department_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_department b where 
																   a.department_id = b.department_id and a.subdepartemen_id='$_POST[subdepartemen]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir'
																   group by a.department_id,b.department_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.subdepartemen_id='$_POST[subdepartemen]' and
														                    a.kode_reco=b.kode_promo and a.departemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='subdepartemen_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Subdepartemen ID</b></td><td ><b>Subdepartemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.subdepartemen_id as groupby ,b.subdepartemen_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_subdepartemen b where 
																   a.department_id = b.department_id and a.subdepartemen_id = b.subdepartemen_id and 
																   a.subdepartemen_id = '$_POST[subdepartemen]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir'
																   group by a.subdepartemen_id,b.subdepartemen_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.subdepartemen_id = '$_POST[subdepartemen]' and
														                    a.kode_reco=b.kode_promo and a.subdepartemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";	
								}else if($_POST[groupby]=='kode_budget'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Kode Budget</b></td><td ><b>Keterangan</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select kode_budget as groupby ,keterangan,
												                   concat( substring( bulan, 1, 3 ) , ' ', substring(tahun, 3, 2 ) ) AS periode, 
															       sum(value)as value from master_budget where subdepartemen_id='$_POST[subdepartemen]' and
																   concat(bulan,tahun) between '$tgl_awal' and '$tgl_akhir'
																   group by kode_budget,keterangan");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where 
														                    a.kode_reco=b.kode_promo and a.kode_budget = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}
							}else{
								if($_POST[groupby]=='divisi_id'){ 
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Divisi ID</b></td><td ><b>Divisi Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.divisi_id as groupby ,b.divisi_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_divisi b where 
																   a.divisi_id = b.divisi_id and a.subdepartemen_id = '$_POST[subdepartemen]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.status='$_POST[approve]'
																   group by a.divisi_id,b.divisi_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.subdepartemen_id = '$_POST[subdepartemen]' and
														                    a.kode_reco=b.kode_promo and a.divisi_id='$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='department_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Departemen ID</b></td><td ><b>Departemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.department_id as groupby ,b.department_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_department b where 
																   a.department_id = b.department_id and a.subdepartemen_id = '$_POST[subdepartemen]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.status='$_POST[approve]'
																   group by a.department_id,b.department_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.subdepartemen_id = '$_POST[subdepartemen]' and
														                    a.kode_reco=b.kode_promo and a.departemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='subdepartemen_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Subdepartemen ID</b></td><td ><b>Subdepartemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.subdepartemen_id as groupby ,b.subdepartemen_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_subdepartemen b where 
																   a.department_id = b.department_id and a.subdepartemen_id = b.subdepartemen_id and 
																   a.subdepartemen_id = '$_POST[subdepartemen]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.status='$_POST[approve]'
																   group by a.subdepartemen_id,b.subdepartemen_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.subdepartemen_id = '$_POST[subdepartemen]' and
														                    a.kode_reco=b.kode_promo and a.subdepartemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";	
								}else if($_POST[groupby]=='kode_budget'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Kode Budget</b></td><td ><b>Keterangan</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select kode_budget as groupby ,keterangan,
												                   concat( substring( bulan, 1, 3 ) , ' ', substring(tahun, 3, 2 ) ) AS periode, 
															       sum(value)as value from master_budget where subdepartemen_id = '$_POST[subdepartemen]' and
																   concat(bulan,tahun) between '$tgl_awal' and '$tgl_akhir' and status='$_POST[approve]'
																   group by kode_budget,keterangan");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.subdepartemen_id = '$_POST[subdepartemen]' and
														                    a.kode_reco=b.kode_promo and a.kode_budget = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}
							}
						}else{//divisinya masih all, sdepartemen all, subdepartemen bukan all,budget bukan all
							if($_POST[approve]=='all'){
								if($_POST[groupby]=='divisi_id'){ 
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Divisi ID</b></td><td ><b>Divisi Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.divisi_id as groupby ,b.divisi_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_divisi b where 
																   a.divisi_id = b.divisi_id and a.kode_budget='$_POST[budget]' and
																   a.subdepartemen_id = '$_POST[subdepartemen]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir'
																   group by a.divisi_id,b.divisi_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.subdepartemen_id = '$_POST[subdepartemen]' and
														                    a.subdepartemen_id = '$_POST[subdepartemen]' and
																			a.kode_reco=b.kode_promo and a.divisi_id='$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='department_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Departemen ID</b></td><td ><b>Departemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.department_id as groupby ,b.department_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_department b where 
																   a.department_id = b.department_id and a.kode_budget='$_POST[budget]' and 
																   a.subdepartemen_id = '$_POST[subdepartemen]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir'
																   group by a.department_id,b.department_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.subdepartemen = '$_POST[subdepartemen_id]' and
														                    a.kode_reco=b.kode_promo and a.departemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='subdepartemen_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Subdepartemen ID</b></td><td ><b>Subdepartemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.subdepartemen_id as groupby ,b.subdepartemen_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_subdepartemen b where 
																   a.department_id = b.department_id and a.subdepartemen_id = b.subdepartemen_id and 
																   a.subdepartemen_id = '$_POST[subdepartemen]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.kode_budget='$_POST[budget]'
																   group by a.subdepartemen_id,b.subdepartemen_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.subdepartemen_id = '$_POST[subdepartemen]' and
														                    a.kode_reco=b.kode_promo and a.subdepartemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";	
								}else if($_POST[groupby]=='kode_budget'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Kode Budget</b></td><td ><b>Keterangan</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select kode_budget as groupby ,keterangan,
												                   concat( substring( bulan, 1, 3 ) , ' ', substring(tahun, 3, 2 ) ) AS periode, 
															       sum(value)as value from master_budget where subdepartemen_id = '$_POST[subdepartemen]' and
																   concat(bulan,tahun) between '$tgl_awal' and '$tgl_akhir' and kode_budget='$_POST[budget]'
																   group by kode_budget,keterangan");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.subdepartemen_id = '$_POST[subdepartemen]' and
														                    a.kode_reco=b.kode_promo and a.kode_budget = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}
							}else{
								if($_POST[groupby]=='divisi_id'){ 
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Divisi ID</b></td><td ><b>Divisi Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.divisi_id as groupby ,b.divisi_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_divisi b where 
																   a.divisi_id = b.divisi_id and a.kode_budget='$_POST[budget]' and
																   a.subdepartemen_id = '$_POST[subdepartemen]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.status='$_POST[approve]'
																   group by a.divisi_id,b.divisi_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.subdepartemen_id = '$_POST[subdepartemen]' and
														                    a.kode_reco=b.kode_promo and a.divisi_id='$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='department_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Departemen ID</b></td><td ><b>Departemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.department_id as groupby ,b.department_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_department b where 
																   a.department_id = b.department_id and a.kode_budget='$_POST[budget]' and
																   a.subdepartemen_id = '$_POST[subdepartemen]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.status='$_POST[approve]'
																   group by a.department_id,b.department_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.subdepartemen_id = '$_POST[subdepartemen]' and
														                    a.kode_reco=b.kode_promo and a.departemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='subdepartemen_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Subdepartemen ID</b></td><td ><b>Subdepartemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.subdepartemen_id as groupby ,b.subdepartemen_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_subdepartemen b where 
																   a.department_id = b.department_id and a.subdepartemen_id = b.subdepartemen_id and 
																   a.kode_budget='$_POST[budget]'  and a.subdepartemen_id = '$_POST[subdepartemen]'
																   and concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.status='$_POST[approve]'
																   group by a.subdepartemen_id,b.subdepartemen_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.subdepartemen_id='$_POST[subdepartemen]' and
														                    a.kode_reco=b.kode_promo and a.subdepartemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";	
								}else if($_POST[groupby]=='kode_budget'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Kode Budget</b></td><td ><b>Keterangan</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select kode_budget as groupby ,keterangan,
												                   concat( substring( bulan, 1, 3 ) , ' ', substring(tahun, 3, 2 ) ) AS periode, 
															       sum(value)as value from master_budget where kode_budget='$_POST[budget]' and
																   a.subdepartemen_id = '$_POST[subdepartemen]' and 
																   concat(bulan,tahun) between '$tgl_awal' and '$tgl_akhir' and status='$_POST[approve]'
																   group by kode_budget,keterangan");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.subdepartemen_id = '$_POST[subdepartemen]' and
														                    a.kode_reco=b.kode_promo and a.kode_budget = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}
							}
						}
					}
				}else{//jika divisi all , departemen tidak all
					if($_POST[subdepartemen]=='*'){
						if($_POST[budget]==''){
							if($_POST[approve]=='all'){
								if($_POST[groupby]=='divisi_id'){ 
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Divisi ID</b></td><td ><b>Divisi Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.divisi_id as groupby ,b.divisi_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_divisi b where 
																   a.divisi_id = b.divisi_id and a.department_id='$_POST[departemen]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir'
																   group by a.divisi_id,b.divisi_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.departemen_id = '$_POST[departemen]' and
														                    a.kode_reco=b.kode_promo and a.divisi_id='$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='department_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Departemen ID</b></td><td ><b>Departemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.department_id as groupby ,b.department_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_department b where 
																   a.department_id = b.department_id and a.department_id='$_POST[departemen]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir'
																   group by a.department_id,b.department_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.departemen_id = '$_POST[departemen]' and
														                    a.kode_reco=b.kode_promo and a.departemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='subdepartemen_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Subdepartemen ID</b></td><td ><b>Subdepartemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.subdepartemen_id as groupby ,b.subdepartemen_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_subdepartemen b where 
																   a.department_id = b.department_id and a.department_id='$_POST[departemen]' and
																   a.subdepartemen_id = b.subdepartemen_id and 
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir'
																   group by a.subdepartemen_id,b.subdepartemen_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.departemen_id = '$_POST[departemen]' and
														                    a.kode_reco=b.kode_promo and a.subdepartemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";	
								}else if($_POST[groupby]=='kode_budget'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Kode Budget</b></td><td ><b>Keterangan</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select kode_budget as groupby ,keterangan,
												                   concat( substring( bulan, 1, 3 ) , ' ', substring(tahun, 3, 2 ) ) AS periode, 
															       sum(value)as value from master_budget where department_id = '$_POST[departemen]' and
																   concat(bulan,tahun) between '$tgl_awal' and '$tgl_akhir'
																   group by kode_budget,keterangan");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.departemen_id = '$_POST[departemen]' and
														                    a.kode_reco=b.kode_promo and a.kode_budget = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}
							}else{ //jika approvenya tidak sama dengan all
								if($_POST[groupby]=='divisi_id'){ 
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Divisi ID</b></td><td ><b>Divisi Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.divisi_id as groupby ,b.divisi_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_divisi b where 
																   a.divisi_id = b.divisi_id and a.department_id = '$_POST[departemen]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.status='$_POST[approve]'
																   group by a.divisi_id,b.divisi_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.departemen_id ='$_POST[departemen]' and
														                    a.kode_reco=b.kode_promo and a.divisi_id='$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='department_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Departemen ID</b></td><td ><b>Departemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.department_id as groupby ,b.department_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_department b where 
																   a.department_id = b.department_id and a.department_id = '$_POST[departemen]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.status='$_POST[approve]'
																   group by a.department_id,b.department_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.departemen_id = '$_POST[departemen]' and
														                    a.kode_reco=b.kode_promo and a.departemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='subdepartemen_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Subdepartemen ID</b></td><td ><b>Subdepartemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.subdepartemen_id as groupby ,b.subdepartemen_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_subdepartemen b where 
																   a.department_id = b.department_id and a.department_id='$_POST[departemen]' and 
																   a.subdepartemen_id = b.subdepartemen_id and 
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.status='$_POST[approve]'
																   group by a.subdepartemen_id,b.subdepartemen_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.departemen_id = '$_POST[departemen]' and
														                    a.kode_reco=b.kode_promo and a.subdepartemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";	
								}else if($_POST[groupby]=='kode_budget'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Kode Budget</b></td><td ><b>Keterangan</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select kode_budget as groupby ,keterangan,
												                   concat( substring( bulan, 1, 3 ) , ' ', substring(tahun, 3, 2 ) ) AS periode, 
															       sum(value)as value from master_budget where department_id = '$_POST[departemen]' and
																   concat(bulan,tahun) between '$tgl_awal' and '$tgl_akhir' and status='$_POST[approve]'
																   group by kode_budget,keterangan");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.departemen_id = '$_POST[departemen]' and
														                    a.kode_reco=b.kode_promo and a.kode_budget = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}
							}
						}else{
							if($_POST[approve]=='all'){
								if($_POST[groupby]=='divisi_id'){ 
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Divisi ID</b></td><td ><b>Divisi Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.divisi_id as groupby ,b.divisi_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_divisi b where 
																   a.department_id = '$_POST[departemen]' and
																   a.divisi_id = b.divisi_id and a.kode_budget='$_POST[budget]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir'
																   group by a.divisi_id,b.divisi_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.departemen_id = '$_POST[departemen]' and
														                    a.kode_reco=b.kode_promo and a.divisi_id='$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='department_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Departemen ID</b></td><td ><b>Departemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.department_id as groupby ,b.department_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_department b where 
																   a.department_id = b.department_id and a.department_id='$_POST[departemen]' and
																   a.kode_budget='$_POST[budget]' and 
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir'
																   group by a.department_id,b.department_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.departemen_id = '$_POST[departemen]' and
														                    a.kode_reco=b.kode_promo and a.departemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='subdepartemen_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Subdepartemen ID</b></td><td ><b>Subdepartemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.subdepartemen_id as groupby ,b.subdepartemen_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_subdepartemen b where 
																   a.department_id = b.department_id and a.department_id ='$_POST[departemen]' and
																   a.subdepartemen_id = b.subdepartemen_id and 
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.kode_budget='$_POST[budget]'
																   group by a.subdepartemen_id,b.subdepartemen_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.departemen_id = '$_POST[departemen]' and
														                    a.kode_reco=b.kode_promo and a.subdepartemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";	
								}else if($_POST[groupby]=='kode_budget'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Kode Budget</b></td><td ><b>Keterangan</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select kode_budget as groupby ,keterangan,
												                   concat( substring( bulan, 1, 3 ) , ' ', substring(tahun, 3, 2 ) ) AS periode, 
															       sum(value)as value from master_budget where department_id = '$_POST[departemen]' and
																   concat(bulan,tahun) between '$tgl_awal' and '$tgl_akhir' and kode_budget='$_POST[budget]'
																   group by kode_budget,keterangan");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.departemen_id = '$_POST[departemen]' and
														                    a.kode_reco=b.kode_promo and a.kode_budget = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}
							}else{
								if($_POST[groupby]=='divisi_id'){ 
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Divisi ID</b></td><td ><b>Divisi Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.divisi_id as groupby ,b.divisi_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_divisi b where 
																   a.divisi_id = b.divisi_id and a.department_id = '$_POST[departemen]' and
																   a.kode_budget='$_POST[budget]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.status='$_POST[approve]'
																   group by a.divisi_id,b.divisi_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.departemen_id='$_POST[departemen]' and
														                    a.kode_reco=b.kode_promo and a.divisi_id='$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='department_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Departemen ID</b></td><td ><b>Departemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.department_id as groupby ,b.department_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_department b where 
																   a.department_id = b.department_id and a.department_id = '$_POST[departemen]' and 
																   a.kode_budget='$_POST[budget]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.status='$_POST[approve]'
																   group by a.department_id,b.department_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.departemen_id = '$_POST[departemen]' and
														                    a.kode_reco=b.kode_promo and a.departemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='subdepartemen_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Subdepartemen ID</b></td><td ><b>Subdepartemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.subdepartemen_id as groupby ,b.subdepartemen_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_subdepartemen b where 
																   a.department_id = b.department_id and a.department_id ='$_POST[departemen]' and
																   a.subdepartemen_id = b.subdepartemen_id and 
																   a.kode_budget='$_POST[budget]' 
																   and concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.status='$_POST[approve]'
																   group by a.subdepartemen_id,b.subdepartemen_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.departemen_id = '$_POST[departemen]' and
														                    a.kode_reco=b.kode_promo and a.subdepartemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";	
								}else if($_POST[groupby]=='kode_budget'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Kode Budget</b></td><td ><b>Keterangan</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select kode_budget as groupby ,keterangan,
												                   concat( substring( bulan, 1, 3 ) , ' ', substring(tahun, 3, 2 ) ) AS periode, 
															       sum(value)as value from master_budget where kode_budget='$_POST[budget]' and
																   department_id = '$_POST[departemen]' and
																   concat(bulan,tahun) between '$tgl_awal' and '$tgl_akhir' and status='$_POST[approve]'
																   group by kode_budget,keterangan");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.departemen_id = '$_POST[departemen]' and
														                    a.kode_reco=b.kode_promo and a.kode_budget = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}
							}
						}
					}else{//jika subdepartemennya bukan all
						if($_POST[budget]==''){
							if($_POST[approve]=='all'){
								if($_POST[groupby]=='divisi_id'){ 
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Divisi ID</b></td><td ><b>Divisi Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.divisi_id as groupby ,b.divisi_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_divisi b where 
																   a.divisi_id = b.divisi_id and a.department_id = '$_POST[departemen]' and 
																   a.subdepartemen_id = '$_POST[subdepartemen]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir'
																   group by a.divisi_id,b.divisi_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where 
																			a.department_id = '$_POST[departemen]' and a.subdepartemen_id='$_POST[subdepartemen]' and
														                    a.kode_reco=b.kode_promo and a.divisi_id='$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='department_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Departemen ID</b></td><td ><b>Departemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.department_id as groupby ,b.department_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_department b where 
																   a.department_id = b.department_id and a.department_id = '$_POST[departemen]' and 
																   a.subdepartemen_id='$_POST[subdepartemen]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir'
																   group by a.department_id,b.department_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where 
																			a.departemen_id = '$_POST[departemen]' and a.subdepartemen_id='$_POST[subdepartemen]' and
														                    a.kode_reco=b.kode_promo and a.departemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='subdepartemen_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Subdepartemen ID</b></td><td ><b>Subdepartemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.subdepartemen_id as groupby ,b.subdepartemen_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_subdepartemen b where 
																   a.department_id = b.department_id and a.department_id='$_POST[departemen]' and
																   a.subdepartemen_id = b.subdepartemen_id and 
																   a.subdepartemen_id = '$_POST[subdepartemen]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir'
																   group by a.subdepartemen_id,b.subdepartemen_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.departemen_id = '$_POST[departemen]' and
																			a.subdepartemen_id = '$_POST[subdepartemen]' and
														                    a.kode_reco=b.kode_promo and a.subdepartemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";	
								}else if($_POST[groupby]=='kode_budget'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Kode Budget</b></td><td ><b>Keterangan</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select kode_budget as groupby ,keterangan,
												                   concat( substring( bulan, 1, 3 ) , ' ', substring(tahun, 3, 2 ) ) AS periode, 
															       sum(value)as value from master_budget where department_id = '$_POST[departemen]' and 
																   subdepartemen_id='$_POST[subdepartemen]' and
																   concat(bulan,tahun) between '$tgl_awal' and '$tgl_akhir'
																   group by kode_budget,keterangan");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.departemen_id = '$_POST[departemen]' and
														                    a.kode_reco=b.kode_promo and a.kode_budget = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}
							}else{
								if($_POST[groupby]=='divisi_id'){ 
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Divisi ID</b></td><td ><b>Divisi Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.divisi_id as groupby ,b.divisi_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_divisi b where 
																   a.divisi_id = b.divisi_id and a.department_id='$_POST[departemen]' and
																   a.subdepartemen_id = '$_POST[subdepartemen]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.status='$_POST[approve]'
																   group by a.divisi_id,b.divisi_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.departemen_id='$_POST[departemen]' and 
																			a.subdepartemen_id = '$_POST[subdepartemen]' and
														                    a.kode_reco=b.kode_promo and a.divisi_id='$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='department_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Departemen ID</b></td><td ><b>Departemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.department_id as groupby ,b.department_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_department b where 
																   a.department_id = b.department_id and a.department_id = '$_POST[departemen]' and
																   a.subdepartemen_id = '$_POST[subdepartemen]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.status='$_POST[approve]'
																   group by a.department_id,b.department_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.departemen_id='$_POST[departemen]' and
																			a.subdepartemen_id = '$_POST[subdepartemen]' and
														                    a.kode_reco=b.kode_promo and a.departemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='subdepartemen_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Subdepartemen ID</b></td><td ><b>Subdepartemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.subdepartemen_id as groupby ,b.subdepartemen_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_subdepartemen b where 
																   a.department_id = b.department_id and a.department_id = '$_POST[departemen]' and
																   a.subdepartemen_id = b.subdepartemen_id and 
																   a.subdepartemen_id = '$_POST[subdepartemen]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.status='$_POST[approve]'
																   group by a.subdepartemen_id,b.subdepartemen_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.departemen_id = '$_POST[departemen]' and 
																			a.subdepartemen_id = '$_POST[subdepartemen]' and
														                    a.kode_reco=b.kode_promo and a.subdepartemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";	
								}else if($_POST[groupby]=='kode_budget'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Kode Budget</b></td><td ><b>Keterangan</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select kode_budget as groupby ,keterangan,
												                   concat( substring( bulan, 1, 3 ) , ' ', substring(tahun, 3, 2 ) ) AS periode, 
															       sum(value)as value from master_budget where department_id = '$_POST[departemen]' and
																   subdepartemen_id = '$_POST[subdepartemen]' and
																   concat(bulan,tahun) between '$tgl_awal' and '$tgl_akhir' and status='$_POST[approve]'
																   group by kode_budget,keterangan");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.departemen_id = '$_POST[departemen]' and
																			a.subdepartemen_id = '$_POST[subdepartemen]' and
														                    a.kode_reco=b.kode_promo and a.kode_budget = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}
							}
						}else{//divisinya masih all, sdepartemen all, subdepartemen bukan all,budget bukan all
							if($_POST[approve]=='all'){
								if($_POST[groupby]=='divisi_id'){ 
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Divisi ID</b></td><td ><b>Divisi Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.divisi_id as groupby ,b.divisi_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_divisi b where 
																   a.divisi_id = b.divisi_id and a.kode_budget='$_POST[budget]' and
																   a.subdepartemen_id = '$_POST[subdepartemen]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir'
																   group by a.divisi_id,b.divisi_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.subdepartemen_id = '$_POST[subdepartemen]' and
														                    a.subdepartemen_id = '$_POST[subdepartemen]' and
																			a.kode_reco=b.kode_promo and a.divisi_id='$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='department_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Departemen ID</b></td><td ><b>Departemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.department_id as groupby ,b.department_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_department b where 
																   a.department_id = b.department_id and a.kode_budget='$_POST[budget]' and 
																   a.subdepartemen_id = '$_POST[subdepartemen]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir'
																   group by a.department_id,b.department_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.subdepartemen = '$_POST[subdepartemen_id]' and
														                    a.kode_reco=b.kode_promo and a.departemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='subdepartemen_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Subdepartemen ID</b></td><td ><b>Subdepartemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.subdepartemen_id as groupby ,b.subdepartemen_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_subdepartemen b where 
																   a.department_id = b.department_id and a.subdepartemen_id = b.subdepartemen_id and 
																   a.subdepartemen_id = '$_POST[subdepartemen]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.kode_budget='$_POST[budget]'
																   group by a.subdepartemen_id,b.subdepartemen_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.subdepartemen_id = '$_POST[subdepartemen]' and
														                    a.kode_reco=b.kode_promo and a.subdepartemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";	
								}else if($_POST[groupby]=='kode_budget'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Kode Budget</b></td><td ><b>Keterangan</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select kode_budget as groupby ,keterangan,
												                   concat( substring( bulan, 1, 3 ) , ' ', substring(tahun, 3, 2 ) ) AS periode, 
															       sum(value)as value from master_budget where subdepartemen_id = '$_POST[subdepartemen]' and
																   concat(bulan,tahun) between '$tgl_awal' and '$tgl_akhir' and kode_budget='$_POST[budget]'
																   group by kode_budget,keterangan");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.subdepartemen_id = '$_POST[subdepartemen]' and
														                    a.kode_reco=b.kode_promo and a.kode_budget = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}
							}else{
								if($_POST[groupby]=='divisi_id'){ 
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Divisi ID</b></td><td ><b>Divisi Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.divisi_id as groupby ,b.divisi_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_divisi b where 
																   a.divisi_id = b.divisi_id and a.kode_budget='$_POST[budget]' and
																   a.subdepartemen_id = '$_POST[subdepartemen]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.status='$_POST[approve]'
																   group by a.divisi_id,b.divisi_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.subdepartemen_id = '$_POST[subdepartemen]' and
														                    a.kode_reco=b.kode_promo and a.divisi_id='$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='department_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Departemen ID</b></td><td ><b>Departemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.department_id as groupby ,b.department_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_department b where 
																   a.department_id = b.department_id and a.kode_budget='$_POST[budget]' and
																   a.subdepartemen_id = '$_POST[subdepartemen]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.status='$_POST[approve]'
																   group by a.department_id,b.department_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.subdepartemen_id = '$_POST[subdepartemen]' and
														                    a.kode_reco=b.kode_promo and a.departemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='subdepartemen_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Subdepartemen ID</b></td><td ><b>Subdepartemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.subdepartemen_id as groupby ,b.subdepartemen_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_subdepartemen b where 
																   a.department_id = b.department_id and a.subdepartemen_id = b.subdepartemen_id and 
																   a.kode_budget='$_POST[budget]'  and a.subdepartemen_id = '$_POST[subdepartemen]'
																   and concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.status='$_POST[approve]'
																   group by a.subdepartemen_id,b.subdepartemen_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.subdepartemen_id='$_POST[subdepartemen]' and
														                    a.kode_reco=b.kode_promo and a.subdepartemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";	
								}else if($_POST[groupby]=='kode_budget'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Kode Budget</b></td><td ><b>Keterangan</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select kode_budget as groupby ,keterangan,
												                   concat( substring( bulan, 1, 3 ) , ' ', substring(tahun, 3, 2 ) ) AS periode, 
															       sum(value)as value from master_budget where kode_budget='$_POST[budget]' and
																   a.subdepartemen_id = '$_POST[subdepartemen]' and 
																   concat(bulan,tahun) between '$tgl_awal' and '$tgl_akhir' and status='$_POST[approve]'
																   group by kode_budget,keterangan");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.subdepartemen_id = '$_POST[subdepartemen]' and
														                    a.kode_reco=b.kode_promo and a.kode_budget = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}
							}
						}
					}
				}
			}else{ // jika divisinya bukan all
				if($_POST[departemen]=='*'){
					if($_POST[subdepartemen]=='*'){
						if($_POST[budget]==''){
							if($_POST[approve]=='all'){
								if($_POST[groupby]=='divisi_id'){ 
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Divisi ID</b></td><td ><b>Divisi Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.divisi_id as groupby ,b.divisi_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_divisi b where 
																   a.divisi_id = b.divisi_id and a.divisi_id = '$_POST[divisi]' and 
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir'
																   group by a.divisi_id,b.divisi_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where
														                    a.kode_reco=b.kode_promo and a.divisi_id='$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='department_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Departemen ID</b></td><td ><b>Departemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.department_id as groupby ,b.department_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_department b where 
																   a.divisi_id = '$_POST[divisi]' and a.department_id = b.department_id and 
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir'
																   group by a.department_id,b.department_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where 
														                    a.kode_reco=b.kode_promo and a.divisi_id = '$_POST[divisi]' and
																			a.departemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='subdepartemen_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Subdepartemen ID</b></td><td ><b>Subdepartemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.subdepartemen_id as groupby ,b.subdepartemen_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_subdepartemen b where 
																   a.divisi_id = '$_POST[divisi]' and a.department_id = b.department_id and 
																   a.subdepartemen_id = b.subdepartemen_id and 
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir'
																   group by a.subdepartemen_id,b.subdepartemen_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id='$_POST[divisi]' and
														                    a.kode_reco=b.kode_promo and a.subdepartemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";	
								}else if($_POST[groupby]=='kode_budget'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Kode Budget</b></td><td ><b>Keterangan</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select kode_budget as groupby ,keterangan,
												                   concat( substring( bulan, 1, 3 ) , ' ', substring(tahun, 3, 2 ) ) AS periode, 
															       sum(value)as value from master_budget where divisi_id = '$_POST[divisi]' and
																   concat(bulan,tahun) between '$tgl_awal' and '$tgl_akhir'
																   group by kode_budget,keterangan");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where  a.divisi_id='$_POST[divisi]' and
														                    a.kode_reco=b.kode_promo and a.kode_budget = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}
							}else{
								if($_POST[groupby]=='divisi_id'){ 
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Divisi ID</b></td><td ><b>Divisi Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.divisi_id as groupby ,b.divisi_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_divisi b where 
																   a.divisi_id = b.divisi_id and a.divisi_id = '$_POST[divisi]' and 
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.status='$_POST[approve]'
																   group by a.divisi_id,b.divisi_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id = '$_POST[divisi]' and
														                    a.kode_reco=b.kode_promo and a.divisi_id='$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='department_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Departemen ID</b></td><td ><b>Departemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.department_id as groupby ,b.department_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_department b where 
																   a.divisi_id = '$_POST[divisi]' and a.department_id = b.department_id and 
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.status='$_POST[approve]'
																   group by a.department_id,b.department_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id = '$_POST[divisi]' and
														                    a.kode_reco=b.kode_promo and a.departemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='subdepartemen_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Subdepartemen ID</b></td><td ><b>Subdepartemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.subdepartemen_id as groupby ,b.subdepartemen_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_subdepartemen b where 
																   a.divisi_id = '$_POST[divisi]' and a.department_id = b.department_id and a.subdepartemen_id = b.subdepartemen_id and 
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.status='$_POST[approve]'
																   group by a.subdepartemen_id,b.subdepartemen_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id = '$_POST[divisi]' and
														                    a.kode_reco=b.kode_promo and a.subdepartemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";	
								}else if($_POST[groupby]=='kode_budget'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Kode Budget</b></td><td ><b>Keterangan</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select kode_budget as groupby ,keterangan,
												                   concat( substring( bulan, 1, 3 ) , ' ', substring(tahun, 3, 2 ) ) AS periode, 
															       sum(value)as value from master_budget where divisi_id = '$_POST[divisi]' and
																   concat(bulan,tahun) between '$tgl_awal' and '$tgl_akhir' and status='$_POST[approve]'
																   group by kode_budget,keterangan");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id = '$_POST[divisi]' and
														                    a.kode_reco=b.kode_promo and a.kode_budget = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}
							}
						}else{//divisi bukan all, departemen all, subdepartemen all, budget bukan all
							if($_POST[approve]=='all'){
								if($_POST[groupby]=='divisi_id'){ 
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Divisi ID</b></td><td ><b>Divisi Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.divisi_id as groupby ,b.divisi_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_divisi b where 
																   a.divisi_id = b.divisi_id and a.divisi_id='$_POST[divisi]' and 
																   a.kode_budget='$_POST[budget]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir'
																   group by a.divisi_id,b.divisi_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id = '$_POST[divisi]' and
														                    a.kode_reco=b.kode_promo and a.divisi_id='$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='department_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Departemen ID</b></td><td ><b>Departemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.department_id as groupby ,b.department_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_department b where 
																   a.divisi_id = '$_POST[divisi]' and a.department_id = b.department_id 
																   and a.kode_budget='$_POST[budget]' and 
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir'
																   group by a.department_id,b.department_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id = '$_POST[divisi]' and
														                    a.kode_reco=b.kode_promo and a.departemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='subdepartemen_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Subdepartemen ID</b></td><td ><b>Subdepartemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.subdepartemen_id as groupby ,b.subdepartemen_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_subdepartemen b where 
																   a.divisi_id = '$_POST[divisi]' and a.department_id = b.department_id 
																   and a.subdepartemen_id = b.subdepartemen_id and 
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.kode_budget='$_POST[budget]'
																   group by a.subdepartemen_id,b.subdepartemen_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id = '$_POST[divisi]' and
														                    a.kode_reco=b.kode_promo and a.subdepartemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";	
								}else if($_POST[groupby]=='kode_budget'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Kode Budget</b></td><td ><b>Keterangan</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select kode_budget as groupby ,keterangan,
												                   concat( substring( bulan, 1, 3 ) , ' ', substring(tahun, 3, 2 ) ) AS periode, 
															       sum(value)as value from master_budget where divisi_id = '$_POST[divisi]' and
																   concat(bulan,tahun) between '$tgl_awal' and '$tgl_akhir' and kode_budget='$_POST[budget]'
																   group by kode_budget,keterangan");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id = '$_POST[divisi]' and
														                    a.kode_reco=b.kode_promo and a.kode_budget = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}
							}else{
								if($_POST[groupby]=='divisi_id'){ 
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Divisi ID</b></td><td ><b>Divisi Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.divisi_id as groupby ,b.divisi_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_divisi b where 
																   a.divisi_id = b.divisi_id and a.divisi_id='$_POST[divisi]' and
																   a.kode_budget='$_POST[budget]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.status='$_POST[approve]'
																   group by a.divisi_id,b.divisi_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id = '$_POST[divisi]' and
														                    a.kode_reco=b.kode_promo and a.divisi_id='$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='department_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Departemen ID</b></td><td ><b>Departemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.department_id as groupby ,b.department_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_department b where 
																   a.divisi_id = '$_POST[divisi]' and
																   a.department_id = b.department_id and a.kode_budget='$_POST[budget]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.status='$_POST[approve]'
																   group by a.department_id,b.department_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where 
														                    a.kode_reco=b.kode_promo and a.departemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='subdepartemen_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Subdepartemen ID</b></td><td ><b>Subdepartemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.subdepartemen_id as groupby ,b.subdepartemen_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_subdepartemen b where 
																   a.divisi_id='$_POST[divisi]' and a.department_id = b.department_id and a.subdepartemen_id = b.subdepartemen_id and 
																   a.kode_budget='$_POST[budget]' 
																   and concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.status='$_POST[approve]'
																   group by a.subdepartemen_id,b.subdepartemen_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id='$_POST[divisi]' and 
														                    a.kode_reco=b.kode_promo and a.subdepartemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";	
								}else if($_POST[groupby]=='kode_budget'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Kode Budget</b></td><td ><b>Keterangan</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select kode_budget as groupby ,keterangan,
												                   concat( substring( bulan, 1, 3 ) , ' ', substring(tahun, 3, 2 ) ) AS periode, 
															       sum(value)as value from master_budget where divisi_id = '$_POST[divisi]' 
																   and kode_budget='$_POST[budget]' and
																   concat(bulan,tahun) between '$tgl_awal' and '$tgl_akhir' and status='$_POST[approve]'
																   group by kode_budget,keterangan");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id = '$_POST[divisi]' and
														                    a.kode_reco=b.kode_promo and a.kode_budget = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}
							}
						}
					}else{//jika subdepartemennya bukan all
						if($_POST[budget]==''){
							if($_POST[approve]=='all'){
								if($_POST[groupby]=='divisi_id'){ 
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Divisi ID</b></td><td ><b>Divisi Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.divisi_id as groupby ,b.divisi_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_divisi b where 
																   a.divisi_id = b.divisi_id and a.divisi_id = '$_POST[divisi]' and 
																   a.subdepartemen_id = '$_POST[subdepartemen]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir'
																   group by a.divisi_id,b.divisi_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id='$_POST[divisi]' and a.subdepartemen_id='$_POST[subdepartemen]' and
														                    a.kode_reco=b.kode_promo and a.divisi_id='$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='department_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Departemen ID</b></td><td ><b>Departemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.department_id as groupby ,b.department_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_department b where 
																   a.divisi_id = '$_POST[divisi]' and
																   a.department_id = b.department_id and a.subdepartemen_id='$_POST[subdepartemen]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir'
																   group by a.department_id,b.department_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id='$_POST[divisi]'
																			and a.subdepartemen_id='$_POST[subdepartemen]' and
														                    a.kode_reco=b.kode_promo and a.departemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='subdepartemen_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Subdepartemen ID</b></td><td ><b>Subdepartemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.subdepartemen_id as groupby ,b.subdepartemen_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_subdepartemen b where 
																   a.divisi_id = '$_POST[divisi]' and a.department_id = b.department_id and a.subdepartemen_id = b.subdepartemen_id and 
																   a.subdepartemen_id = '$_POST[subdepartemen]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir'
																   group by a.subdepartemen_id,b.subdepartemen_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id = '$_POST[divisi]' and 
																			a.subdepartemen_id = '$_POST[subdepartemen]' and
														                    a.kode_reco=b.kode_promo and a.subdepartemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";	
								}else if($_POST[groupby]=='kode_budget'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Kode Budget</b></td><td ><b>Keterangan</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select kode_budget as groupby ,keterangan,
												                   concat( substring( bulan, 1, 3 ) , ' ', substring(tahun, 3, 2 ) ) AS periode, 
															       sum(value)as value from master_budget where and divisi_id='$_POST[divisi]' and
																   subdepartemen_id='$_POST[subdepartemen]' and
																   concat(bulan,tahun) between '$tgl_awal' and '$tgl_akhir'
																   group by kode_budget,keterangan");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id = '$_POST[divisi]' and
														                    a.kode_reco=b.kode_promo and a.kode_budget = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}
							}else{
								if($_POST[groupby]=='divisi_id'){ 
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Divisi ID</b></td><td ><b>Divisi Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.divisi_id as groupby ,b.divisi_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_divisi b where 
																   a.divisi_id = b.divisi_id and a.divisi_id='$_POST[divisi]' and a.subdepartemen_id = '$_POST[subdepartemen]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.status='$_POST[approve]'
																   group by a.divisi_id,b.divisi_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.subdepartemen_id = '$_POST[subdepartemen]' and
														                    a.kode_reco=b.kode_promo and a.divisi_id='$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='department_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Departemen ID</b></td><td ><b>Departemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.department_id as groupby ,b.department_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_department b where 
																   a.divisi_id='$_POST[divisi]' and a.department_id = b.department_id 
																   and a.subdepartemen_id = '$_POST[subdepartemen]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.status='$_POST[approve]'
																   group by a.department_id,b.department_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id='$_POST[divisi]' and
																			a.subdepartemen_id = '$_POST[subdepartemen]' and
														                    a.kode_reco=b.kode_promo and a.departemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='subdepartemen_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Subdepartemen ID</b></td><td ><b>Subdepartemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.subdepartemen_id as groupby ,b.subdepartemen_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_subdepartemen b where 
																   a.divisi_id = '$_POST[divisi]' and
																   a.department_id = b.department_id and a.subdepartemen_id = b.subdepartemen_id and 
																   a.subdepartemen_id = '$_POST[subdepartemen]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.status='$_POST[approve]'
																   group by a.subdepartemen_id,b.subdepartemen_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id = '$_POST[divisi]' and
																			a.subdepartemen_id = '$_POST[subdepartemen]' and
														                    a.kode_reco=b.kode_promo and a.subdepartemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";	
								}else if($_POST[groupby]=='kode_budget'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Kode Budget</b></td><td ><b>Keterangan</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select kode_budget as groupby ,keterangan,
												                   concat( substring( bulan, 1, 3 ) , ' ', substring(tahun, 3, 2 ) ) AS periode, 
															       sum(value)as value from master_budget where divisi_id='$_POST[divisi]'
																   and subdepartemen_id = '$_POST[subdepartemen]' and
																   concat(bulan,tahun) between '$tgl_awal' and '$tgl_akhir' and status='$_POST[approve]'
																   group by kode_budget,keterangan");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id='$_POST[divisi]' and
																			a.subdepartemen_id = '$_POST[subdepartemen]' and
														                    a.kode_reco=b.kode_promo and a.kode_budget = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}
							}
						}else{//divisinya masih all, sdepartemen all, subdepartemen bukan all,budget bukan all
							if($_POST[approve]=='all'){
								if($_POST[groupby]=='divisi_id'){ 
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Divisi ID</b></td><td ><b>Divisi Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.divisi_id as groupby ,b.divisi_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_divisi b where 
																   a.divisi_id = b.divisi_id and a.divisi_id='$_POST[divisi]' and a.kode_budget='$_POST[budget]' and
																   a.subdepartemen_id = '$_POST[subdepartemen]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir'
																   group by a.divisi_id,b.divisi_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where 
																			a.divisi_id = '$_POST[divisi]' and a.subdepartemen_id = '$_POST[subdepartemen]' and
														                    a.subdepartemen_id = '$_POST[subdepartemen]' and
																			a.kode_reco=b.kode_promo and a.divisi_id='$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='department_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Departemen ID</b></td><td ><b>Departemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.department_id as groupby ,b.department_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_department b where 
																   a.divisi_id = '$_POST[divisi]' and 
																   a.department_id = b.department_id and a.kode_budget='$_POST[budget]' and 
																   a.subdepartemen_id = '$_POST[subdepartemen]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir'
																   group by a.department_id,b.department_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id = '$_POST[divisi]' and
																			a.subdepartemen = '$_POST[subdepartemen_id]' and
														                    a.kode_reco=b.kode_promo and a.departemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='subdepartemen_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Subdepartemen ID</b></td><td ><b>Subdepartemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.subdepartemen_id as groupby ,b.subdepartemen_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_subdepartemen b where 
																   a.divisi_id = '$_POST[divisi]' and
																   a.department_id = b.department_id and a.subdepartemen_id = b.subdepartemen_id and 
																   a.subdepartemen_id = '$_POST[subdepartemen]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.kode_budget='$_POST[budget]'
																   group by a.subdepartemen_id,b.subdepartemen_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id = '$_POST[divisi]' and
																			a.subdepartemen_id = '$_POST[subdepartemen]' and
														                    a.kode_reco=b.kode_promo and a.subdepartemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";	
								}else if($_POST[groupby]=='kode_budget'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Kode Budget</b></td><td ><b>Keterangan</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select kode_budget as groupby ,keterangan,
												                   concat( substring( bulan, 1, 3 ) , ' ', substring(tahun, 3, 2 ) ) AS periode, 
															       sum(value)as value from master_budget where divisi_id = '$_POST[divisi]' and 
																   subdepartemen_id = '$_POST[subdepartemen]' and
																   concat(bulan,tahun) between '$tgl_awal' and '$tgl_akhir' and kode_budget='$_POST[budget]'
																   group by kode_budget,keterangan");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id = '$_POST[divisi]' and 
																			a.subdepartemen_id = '$_POST[subdepartemen]' and
														                    a.kode_reco=b.kode_promo and a.kode_budget = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}
							}else{
								if($_POST[groupby]=='divisi_id'){ 
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Divisi ID</b></td><td ><b>Divisi Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.divisi_id as groupby ,b.divisi_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_divisi b where 
																   a.divisi_id = b.divisi_id and a.divisi_id = '$_POST[divisi]' and
																   a.kode_budget='$_POST[budget]' and
																   a.subdepartemen_id = '$_POST[subdepartemen]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.status='$_POST[approve]'
																   group by a.divisi_id,b.divisi_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id = '$_POST[divisi]' and
																			a.subdepartemen_id = '$_POST[subdepartemen]' and
														                    a.kode_reco=b.kode_promo and a.divisi_id='$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='department_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Departemen ID</b></td><td ><b>Departemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.department_id as groupby ,b.department_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_department b where 
																   a.divisi_id = '$_POST[divisi]' and
																   a.department_id = b.department_id and a.kode_budget='$_POST[budget]' and
																   a.subdepartemen_id = '$_POST[subdepartemen]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.status='$_POST[approve]'
																   group by a.department_id,b.department_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id = '$_POST[divisi]' and
																			a.subdepartemen_id = '$_POST[subdepartemen]' and
														                    a.kode_reco=b.kode_promo and a.departemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='subdepartemen_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Subdepartemen ID</b></td><td ><b>Subdepartemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.subdepartemen_id as groupby ,b.subdepartemen_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_subdepartemen b where 
																   a.divisi_id = '$_POST[divisi]' and
																   a.department_id = b.department_id and a.subdepartemen_id = b.subdepartemen_id and 
																   a.kode_budget='$_POST[budget]'  and a.subdepartemen_id = '$_POST[subdepartemen]'
																   and concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.status='$_POST[approve]'
																   group by a.subdepartemen_id,b.subdepartemen_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id = '$_POST[divisi]' and
																			a.subdepartemen_id='$_POST[subdepartemen]' and
														                    a.kode_reco=b.kode_promo and a.subdepartemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";	
								}else if($_POST[groupby]=='kode_budget'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Kode Budget</b></td><td ><b>Keterangan</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select kode_budget as groupby ,keterangan,
												                   concat( substring( bulan, 1, 3 ) , ' ', substring(tahun, 3, 2 ) ) AS periode, 
															       sum(value)as value from master_budget where divisi_id = '$_POST[divisi]' and
																   kode_budget='$_POST[budget]' and
																   a.subdepartemen_id = '$_POST[subdepartemen]' and 
																   concat(bulan,tahun) between '$tgl_awal' and '$tgl_akhir' and status='$_POST[approve]'
																   group by kode_budget,keterangan");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id = '$_POST[divisi]' and
																			a.subdepartemen_id = '$_POST[subdepartemen]' and
														                    a.kode_reco=b.kode_promo and a.kode_budget = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}
							}
						}
					}
				}else{
					if($_POST[subdepartemen]=='*'){
						if($_POST[budget]==''){
							if($_POST[approve]=='all'){
								if($_POST[groupby]=='divisi_id'){ 
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Divisi ID</b></td><td ><b>Divisi Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.divisi_id as groupby ,b.divisi_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_divisi b where 
																   a.divisi_id = b.divisi_id and a.divisi_id='$_POST[divisi]' and 
																   a.department_id='$_POST[departemen]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir'
																   group by a.divisi_id,b.divisi_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where 
																			a.divisi_id='$_POST[divisi]' and a.departemen_id = '$_POST[departemen]' and
														                    a.kode_reco=b.kode_promo and a.divisi_id='$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='department_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Departemen ID</b></td><td ><b>Departemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.department_id as groupby ,b.department_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_department b where 
																   a.divisi_id='$_POST[divisi]' and a.department_id = b.department_id and a.department_id='$_POST[departemen]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir'
																   group by a.department_id,b.department_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id = '$_POST[divisi]' and 
																			a.departemen_id = '$_POST[departemen]' and
														                    a.kode_reco=b.kode_promo and a.departemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='subdepartemen_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Subdepartemen ID</b></td><td ><b>Subdepartemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.subdepartemen_id as groupby ,b.subdepartemen_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_subdepartemen b where 
																   a.divisi_id = '$_POST[divisi]' and a.department_id = b.department_id and 
																   a.department_id='$_POST[departemen]' and
																   a.subdepartemen_id = b.subdepartemen_id and 
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir'
																   group by a.subdepartemen_id,b.subdepartemen_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id='$_POST[divisi]' and 
																			a.departemen_id = '$_POST[departemen]' and
														                    a.kode_reco=b.kode_promo and a.subdepartemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";	
								}else if($_POST[groupby]=='kode_budget'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Kode Budget</b></td><td ><b>Keterangan</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select kode_budget as groupby ,keterangan,
												                   concat( substring( bulan, 1, 3 ) , ' ', substring(tahun, 3, 2 ) ) AS periode, 
															       sum(value)as value from master_budget where divisi_id='$_POST[divisi]' 
																   and department_id = '$_POST[departemen]' and
																   concat(bulan,tahun) between '$tgl_awal' and '$tgl_akhir'
																   group by kode_budget,keterangan");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id='$_POST[divisi]' and 
																			a.departemen_id = '$_POST[departemen]' and
														                    a.kode_reco=b.kode_promo and a.kode_budget = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}
							}else{ //jika approvenya tidak sama dengan all
								if($_POST[groupby]=='divisi_id'){ 
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Divisi ID</b></td><td ><b>Divisi Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.divisi_id as groupby ,b.divisi_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_divisi b where 
																   a.divisi_id = b.divisi_id and a.divisi_id='$_POST[divisi]' and  
																   a.department_id = '$_POST[departemen]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.status='$_POST[approve]'
																   group by a.divisi_id,b.divisi_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id='$_POST[divisi]' and 
																			a.departemen_id ='$_POST[departemen]' and
														                    a.kode_reco=b.kode_promo and a.divisi_id='$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='department_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Departemen ID</b></td><td ><b>Departemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.department_id as groupby ,b.department_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_department b where 
																   a.divisi_id='$_POST[divisi]' and a.department_id = b.department_id and 
																   a.department_id = '$_POST[departemen]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.status='$_POST[approve]'
																   group by a.department_id,b.department_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id='$_POST[divisi]' and 
																			a.departemen_id = '$_POST[departemen]' and
														                    a.kode_reco=b.kode_promo and a.departemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='subdepartemen_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Subdepartemen ID</b></td><td ><b>Subdepartemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.subdepartemen_id as groupby ,b.subdepartemen_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_subdepartemen b where 
																   a.divisi_id='$_POST[divisi]' and a.department_id = b.department_id and
																   a.department_id='$_POST[departemen]' and 
																   a.subdepartemen_id = b.subdepartemen_id and 
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.status='$_POST[approve]'
																   group by a.subdepartemen_id,b.subdepartemen_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id='$_POST[divisi]' and 
																			a.departemen_id = '$_POST[departemen]' and
														                    a.kode_reco=b.kode_promo and a.subdepartemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";	
								}else if($_POST[groupby]=='kode_budget'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Kode Budget</b></td><td ><b>Keterangan</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select kode_budget as groupby ,keterangan,
												                   concat( substring( bulan, 1, 3 ) , ' ', substring(tahun, 3, 2 ) ) AS periode, 
															       sum(value)as value from master_budget where divisi_id='$_POST[divisi]' 
																   and department_id = '$_POST[departemen]' and
																   concat(bulan,tahun) between '$tgl_awal' and '$tgl_akhir' and status='$_POST[approve]'
																   group by kode_budget,keterangan");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id='$_POST[divisi]' and 
																			a.departemen_id = '$_POST[departemen]' and
														                    a.kode_reco=b.kode_promo and a.kode_budget = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}
							}
						}else{
							if($_POST[approve]=='all'){
								if($_POST[groupby]=='divisi_id'){ 
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Divisi ID</b></td><td ><b>Divisi Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.divisi_id as groupby ,b.divisi_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_divisi b where 
																   a.department_id = '$_POST[departemen]' and
																   a.divisi_id = b.divisi_id and a.divisi_id='$_POST[divisi]' and 
																   a.kode_budget='$_POST[budget]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir'
																   group by a.divisi_id,b.divisi_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id='$_POST[divisi]' and 
																			a.departemen_id = '$_POST[departemen]' and
														                    a.kode_reco=b.kode_promo and a.divisi_id='$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='department_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Departemen ID</b></td><td ><b>Departemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.department_id as groupby ,b.department_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_department b where 
																   a.divisi_id='$_POST[divisi]' and a.department_id = b.department_id and 
																   a.department_id='$_POST[departemen]' and
																   a.kode_budget='$_POST[budget]' and 
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir'
																   group by a.department_id,b.department_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id='$_POST[divisi]' and 
																			a.departemen_id = '$_POST[departemen]' and
														                    a.kode_reco=b.kode_promo and a.departemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='subdepartemen_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Subdepartemen ID</b></td><td ><b>Subdepartemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.subdepartemen_id as groupby ,b.subdepartemen_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_subdepartemen b where 
																   a.divisi_id='$_POST[divisi]' and 
																   a.department_id = b.department_id and a.department_id ='$_POST[departemen]' and
																   a.subdepartemen_id = b.subdepartemen_id and 
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.kode_budget='$_POST[budget]'
																   group by a.subdepartemen_id,b.subdepartemen_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id='$_POST[divisi]' and 
																			a.departemen_id = '$_POST[departemen]' and
														                    a.kode_reco=b.kode_promo and a.subdepartemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";	
								}else if($_POST[groupby]=='kode_budget'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Kode Budget</b></td><td ><b>Keterangan</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select kode_budget as groupby ,keterangan,
												                   concat( substring( bulan, 1, 3 ) , ' ', substring(tahun, 3, 2 ) ) AS periode, 
															       sum(value)as value from master_budget where divisi_id='$_POST[divisi]' and 
																   department_id = '$_POST[departemen]' and
																   concat(bulan,tahun) between '$tgl_awal' and '$tgl_akhir' and kode_budget='$_POST[budget]'
																   group by kode_budget,keterangan");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id='$_POST[divisi]' and 
																			a.departemen_id = '$_POST[departemen]' and
														                    a.kode_reco=b.kode_promo and a.kode_budget = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}
							}else{
								if($_POST[groupby]=='divisi_id'){ 
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Divisi ID</b></td><td ><b>Divisi Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.divisi_id as groupby ,b.divisi_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_divisi b where 
																   a.divisi_id = b.divisi_id and a.divisi_id='$_POST[divisi]' and 
																   a.department_id = '$_POST[departemen]' and
																   a.kode_budget='$_POST[budget]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.status='$_POST[approve]'
																   group by a.divisi_id,b.divisi_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id='$_POST[divisi]' and 
																			a.departemen_id='$_POST[departemen]' and
														                    a.kode_reco=b.kode_promo and a.divisi_id='$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='department_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Departemen ID</b></td><td ><b>Departemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.department_id as groupby ,b.department_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_department b where 
																   a.divisi_id='$_POST[divisi]' and 
																   a.department_id = b.department_id and a.department_id = '$_POST[departemen]' and 
																   a.kode_budget='$_POST[budget]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.status='$_POST[approve]'
																   group by a.department_id,b.department_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id='$_POST[divisi]' and 
																			a.departemen_id = '$_POST[departemen]' and
														                    a.kode_reco=b.kode_promo and a.departemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='subdepartemen_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Subdepartemen ID</b></td><td ><b>Subdepartemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.subdepartemen_id as groupby ,b.subdepartemen_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_subdepartemen b where 
																   a.divisi_id='$_POST[divisi]' and 
																   a.department_id = b.department_id and a.department_id ='$_POST[departemen]' and
																   a.subdepartemen_id = b.subdepartemen_id and 
																   a.kode_budget='$_POST[budget]' 
																   and concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.status='$_POST[approve]'
																   group by a.subdepartemen_id,b.subdepartemen_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id='$_POST[divisi]' and 
																			a.departemen_id = '$_POST[departemen]' and
														                    a.kode_reco=b.kode_promo and a.subdepartemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";	
								}else if($_POST[groupby]=='kode_budget'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Kode Budget</b></td><td ><b>Keterangan</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select kode_budget as groupby ,keterangan,
												                   concat( substring( bulan, 1, 3 ) , ' ', substring(tahun, 3, 2 ) ) AS periode, 
															       sum(value)as value from master_budget where divisi_id='$_POST[divisi]' and 
																   kode_budget='$_POST[budget]' and
																   department_id = '$_POST[departemen]' and
																   concat(bulan,tahun) between '$tgl_awal' and '$tgl_akhir' and status='$_POST[approve]'
																   group by kode_budget,keterangan");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id='$_POST[divisi]' and 
																			a.departemen_id = '$_POST[departemen]' and
														                    a.kode_reco=b.kode_promo and a.kode_budget = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}
							}
						}
					}else{//jika subdepartemennya bukan all
						if($_POST[budget]==''){
							if($_POST[approve]=='all'){
								if($_POST[groupby]=='divisi_id'){ 
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Divisi ID</b></td><td ><b>Divisi Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.divisi_id as groupby ,b.divisi_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_divisi b where 
																   a.divisi_id = b.divisi_id and a.divisi_id='$_POST[divisi]' and  
																   a.department_id = '$_POST[departemen]' and 
																   a.subdepartemen_id = '$_POST[subdepartemen]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir'
																   group by a.divisi_id,b.divisi_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where 
																			a.divisi_id='$_POST[divisi]' and 
																			a.departemen_id = '$_POST[departemen]' and a.subdepartemen_id='$_POST[subdepartemen]' and
														                    a.kode_reco=b.kode_promo and a.divisi_id='$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
										
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='department_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Departemen ID</b></td><td ><b>Departemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.department_id as groupby ,b.department_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_department b where 
																   a.divisi_id='$_POST[divisi]' and 
																   a.department_id = b.department_id and a.department_id = '$_POST[departemen]' and 
																   a.subdepartemen_id='$_POST[subdepartemen]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir'
																   group by a.department_id,b.department_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where 
																			a.divisi_id='$_POST[divisi]' and 
																			a.departemen_id = '$_POST[departemen]' and a.subdepartemen_id='$_POST[subdepartemen]' and
														                    a.kode_reco=b.kode_promo and a.departemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='subdepartemen_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Subdepartemen ID</b></td><td ><b>Subdepartemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.subdepartemen_id as groupby ,b.subdepartemen_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_subdepartemen b where 
																   a.divisi_id='$_POST[divisi]' and 
																   a.department_id = b.department_id and a.department_id='$_POST[departemen]' and
																   a.subdepartemen_id = b.subdepartemen_id and 
																   a.subdepartemen_id = '$_POST[subdepartemen]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir'
																   group by a.subdepartemen_id,b.subdepartemen_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id='$_POST[divisi]' and 
																			a.departemen_id = '$_POST[departemen]' and
																			a.subdepartemen_id = '$_POST[subdepartemen]' and
														                    a.kode_reco=b.kode_promo and a.subdepartemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";	
								}else if($_POST[groupby]=='kode_budget'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Kode Budget</b></td><td ><b>Keterangan</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select kode_budget as groupby ,keterangan,
												                   concat( substring( bulan, 1, 3 ) , ' ', substring(tahun, 3, 2 ) ) AS periode, 
															       sum(value)as value from master_budget where divisi_id='$_POST[divisi]' and 
																   department_id = '$_POST[departemen]' and 
																   subdepartemen_id='$_POST[subdepartemen]' and
																   concat(bulan,tahun) between '$tgl_awal' and '$tgl_akhir'
																   group by kode_budget,keterangan");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id='$_POST[divisi]' and 
																			a.departemen_id = '$_POST[departemen]' and
														                    a.kode_reco=b.kode_promo and a.kode_budget = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}
							}else{
								if($_POST[groupby]=='divisi_id'){ 
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Divisi ID</b></td><td ><b>Divisi Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.divisi_id as groupby ,b.divisi_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_divisi b where 
																   a.divisi_id = b.divisi_id and a.divisi_id='$_POST[divisi]' and 
																   a.department_id='$_POST[departemen]' and
																   a.subdepartemen_id = '$_POST[subdepartemen]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.status='$_POST[approve]'
																   group by a.divisi_id,b.divisi_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id='$_POST[divisi]' and 
																			a.departemen_id='$_POST[departemen]' and 
																			a.subdepartemen_id = '$_POST[subdepartemen]' and
														                    a.kode_reco=b.kode_promo and a.divisi_id='$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='department_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Departemen ID</b></td><td ><b>Departemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.department_id as groupby ,b.department_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_department b where 
																   a.divisi_id='$_POST[divisi]' and 
																   a.department_id = b.department_id and a.department_id = '$_POST[departemen]' and
																   a.subdepartemen_id = '$_POST[subdepartemen]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.status='$_POST[approve]'
																   group by a.department_id,b.department_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id='$_POST[divisi]' and 
																			a.departemen_id='$_POST[departemen]' and
																			a.subdepartemen_id = '$_POST[subdepartemen]' and
														                    a.kode_reco=b.kode_promo and a.departemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='subdepartemen_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Subdepartemen ID</b></td><td ><b>Subdepartemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.subdepartemen_id as groupby ,b.subdepartemen_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_subdepartemen b where 
																   a.divisi_id='$_POST[divisi]' and 
																   a.department_id = b.department_id and a.department_id = '$_POST[departemen]' and
																   a.subdepartemen_id = b.subdepartemen_id and 
																   a.subdepartemen_id = '$_POST[subdepartemen]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.status='$_POST[approve]'
																   group by a.subdepartemen_id,b.subdepartemen_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id='$_POST[divisi]' and 
																			a.departemen_id = '$_POST[departemen]' and 
																			a.subdepartemen_id = '$_POST[subdepartemen]' and
														                    a.kode_reco=b.kode_promo and a.subdepartemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";	
								}else if($_POST[groupby]=='kode_budget'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Kode Budget</b></td><td ><b>Keterangan</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select kode_budget as groupby ,keterangan,
												                   concat( substring( bulan, 1, 3 ) , ' ', substring(tahun, 3, 2 ) ) AS periode, 
															       sum(value)as value from master_budget where divisi_id='$_POST[divisi]' and 
																   department_id = '$_POST[departemen]' and
																   subdepartemen_id = '$_POST[subdepartemen]' and
																   concat(bulan,tahun) between '$tgl_awal' and '$tgl_akhir' and status='$_POST[approve]'
																   group by kode_budget,keterangan");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id='$_POST[divisi]' and 
																			a.departemen_id = '$_POST[departemen]' and
																			a.subdepartemen_id = '$_POST[subdepartemen]' and
														                    a.kode_reco=b.kode_promo and a.kode_budget = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}
							}
						}else{//divisinya masih all, sdepartemen all, subdepartemen bukan all,budget bukan all
							if($_POST[approve]=='all'){
								if($_POST[groupby]=='divisi_id'){ 
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Divisi ID</b></td><td ><b>Divisi Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.divisi_id as groupby ,b.divisi_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_divisi b where 
																   a.divisi_id = b.divisi_id and a.divisi_id='$_POST[divisi]' and 
																   a.kode_budget='$_POST[budget]' and
																   a.subdepartemen_id = '$_POST[subdepartemen]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir'
																   group by a.divisi_id,b.divisi_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id='$_POST[divisi]' and 
																			a.subdepartemen_id = '$_POST[subdepartemen]' and
														                    a.subdepartemen_id = '$_POST[subdepartemen]' and
																			a.kode_reco=b.kode_promo and a.divisi_id='$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='department_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Departemen ID</b></td><td ><b>Departemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.department_id as groupby ,b.department_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_department b where 
																   a.divisi_id='$_POST[divisi]' and 
																   a.department_id = b.department_id and a.kode_budget='$_POST[budget]' and 
																   a.subdepartemen_id = '$_POST[subdepartemen]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir'
																   group by a.department_id,b.department_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id='$_POST[divisi]' and 
																			a.subdepartemen = '$_POST[subdepartemen_id]' and
														                    a.kode_reco=b.kode_promo and a.departemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='subdepartemen_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Subdepartemen ID</b></td><td ><b>Subdepartemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.subdepartemen_id as groupby ,b.subdepartemen_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_subdepartemen b where 
																   a.divisi_id='$_POST[divisi]' and 
																   a.department_id = b.department_id and a.subdepartemen_id = b.subdepartemen_id and 
																   a.subdepartemen_id = '$_POST[subdepartemen]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.kode_budget='$_POST[budget]'
																   group by a.subdepartemen_id,b.subdepartemen_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id='$_POST[divisi]' and 
																			a.subdepartemen_id = '$_POST[subdepartemen]' and
														                    a.kode_reco=b.kode_promo and a.subdepartemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";	
								}else if($_POST[groupby]=='kode_budget'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Kode Budget</b></td><td ><b>Keterangan</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select kode_budget as groupby ,keterangan,
												                   concat( substring( bulan, 1, 3 ) , ' ', substring(tahun, 3, 2 ) ) AS periode, 
															       sum(value)as value from master_budget where divisi_id='$_POST[divisi]' and 
																   subdepartemen_id = '$_POST[subdepartemen]' and
																   concat(bulan,tahun) between '$tgl_awal' and '$tgl_akhir' and kode_budget='$_POST[budget]'
																   group by kode_budget,keterangan");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id='$_POST[divisi]' and 
																			a.subdepartemen_id = '$_POST[subdepartemen]' and
														                    a.kode_reco=b.kode_promo and a.kode_budget = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}
							}else{
								if($_POST[groupby]=='divisi_id'){ 
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Divisi ID</b></td><td ><b>Divisi Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.divisi_id as groupby ,b.divisi_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_divisi b where 
																   a.divisi_id = b.divisi_id and a.divisi_id='$_POST[divisi]' and 
																   a.kode_budget='$_POST[budget]' and
																   a.subdepartemen_id = '$_POST[subdepartemen]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.status='$_POST[approve]'
																   group by a.divisi_id,b.divisi_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id='$_POST[divisi]' and 
																			a.subdepartemen_id = '$_POST[subdepartemen]' and
														                    a.kode_reco=b.kode_promo and a.divisi_id='$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='department_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Departemen ID</b></td><td ><b>Departemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.department_id as groupby ,b.department_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_department b where 
																   a.divisi_id='$_POST[divisi]' and 
																   a.department_id = b.department_id and a.kode_budget='$_POST[budget]' and
																   a.subdepartemen_id = '$_POST[subdepartemen]' and
																   concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.status='$_POST[approve]'
																   group by a.department_id,b.department_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id='$_POST[divisi]' and 
																			a.subdepartemen_id = '$_POST[subdepartemen]' and
														                    a.kode_reco=b.kode_promo and a.departemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}else if($_POST[groupby]=='subdepartemen_id'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Subdepartemen ID</b></td><td ><b>Subdepartemen Name</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select a.subdepartemen_id as groupby ,b.subdepartemen_name as keterangan,
												                   concat( substring( a.bulan, 1, 3 ) , ' ', substring(a.tahun, 3, 2 ) ) AS periode, 
															       sum(a.value)as value from master_budget a,master_subdepartemen b where 
																   a.divisi_id='$_POST[divisi]' and 
																   a.department_id = b.department_id and a.subdepartemen_id = b.subdepartemen_id and 
																   a.kode_budget='$_POST[budget]'  and a.subdepartemen_id = '$_POST[subdepartemen]'
																   and concat(a.bulan,a.tahun) between '$tgl_awal' and '$tgl_akhir' and a.status='$_POST[approve]'
																   group by a.subdepartemen_id,b.subdepartemen_name");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id='$_POST[divisi]' and 
																			a.subdepartemen_id='$_POST[subdepartemen]' and
														                    a.kode_reco=b.kode_promo and a.subdepartemen_id = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";	
								}else if($_POST[groupby]=='kode_budget'){
												echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
														<tr><td><b>No.</b></td><tD><b>Kode Budget</b></td><td ><b>Keterangan</b></td>
														<td ><b>Periode</b></td><tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td>
														<tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
												
												$sql = mysql_query("select kode_budget as groupby ,keterangan,
												                   concat( substring( bulan, 1, 3 ) , ' ', substring(tahun, 3, 2 ) ) AS periode, 
															       sum(value)as value from master_budget where divisi_id='$_POST[divisi]' and 
																   kode_budget='$_POST[budget]' and
																   a.subdepartemen_id = '$_POST[subdepartemen]' and 
																   concat(bulan,tahun) between '$tgl_awal' and '$tgl_akhir' and status='$_POST[approve]'
																   group by kode_budget,keterangan");
												$no =1;
												while($r = mysql_fetch_array($sql)){
														$cost = mysql_query("select sum(a.value)as cost,$r[value]-sum(a.value)as outstanding ,
														                    sum(a.value)/$r[value]*100 as persen
																			from detail_reco_item a,reco_request b where a.divisi_id='$_POST[divisi]' and 
																			a.subdepartemen_id = '$_POST[subdepartemen]' and
														                    a.kode_reco=b.kode_promo and a.kode_budget = '$r[groupby]' and b.complete<>'' 
																			and b.status<>'Rejected' and a.kode_budget='$_POST[budget]'
																			and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'");
																			
														$rcost = mysql_fetch_array($cost);
														
														echo "<tr>
																<td>$no.</td>
																<td>$r[groupby]</td>
																<td>$r[keterangan]</td>
																<td>$r[periode]</td>
																<tD>".number_format($r[value],2,'.',',')."</td>
																<tD>".number_format($rcost[cost],2,'.',',')."</td>
																<tD>".number_format($rcost[outstanding],2,'.',',')."</td>
																<td>".number_format($rcost[persen],2,'.',',')." %</td>
															</tr>";
														
														$total_budget = $total_budget + $r[value];
														$total_cost = $total_cost + $rcost[cost];
														$total_outstanding = $total_outstanding + $rcost[outstanding];
														
														$no++;
														
												}
												echo "<tR>
														<td colspan=3></td><td><b>TOTAL</b></td>
														<tD><b>".@number_format($total_budget,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_outstanding,2,'.',',')."</b></td>
														<tD><b>".@number_format($total_cost/$total_budget*100,2,'.',',')." %</b></td>
													 </tr></table></div>";
								}
							}
						}
					}
				}
			}
	  }
}
?>
