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
			//jika divisinya adalah all divisi maka 
			if($_POST[divisi]=='0'){
				//cek apakah departemenya all
				if($_POST[departemen]=='*'){
						//jika all divisi, all departemen, all subdepartemen
						if($_POST[subdepartemen]=='*'){
						    if($_POST[budget]==''){
							    //cek jika approval satusnya semua 
								if($_POST[approve]=='all'){	
								        if($_POST[groupby]=='kode_budget'){
											$sql = mysql_query("select kode_budget as groupby,keterangan,
											                   concat( substring( bulan, 1, 3 ) , ' ', substring(tahun, 3, 2 ) ) AS periode, 
															   value from master_budget where
											                   concat(bulan,tahun) between '$tgl_awal' and '$tgl_akhir'");
										}else{
											$sql = mysql_query("SELECT $groupby as groupby, $keterangan as keterangan, 
												                concat( substring( b.bulan, 1, 3 ) , ' ', substring( b.tahun, 3, 2 ) ) AS periode, 
																sum(b.value)as value, sum(b.value) - sum( a.value ) AS outstanding_cost, sum( a.value ) AS cost, 
																sum( a.value ) / sum(b.value) *100 AS persen
																FROM detail_reco_item a, master_budget b, reco_request c, master_divisi d,
																master_department e, master_subdepartemen f
																WHERE a.kode_reco = c.kode_promo
																AND a.kode_budget = b.kode_budget
																AND a.divisi_id = d.divisi_id
																AND b.divisi_id = d.divisi_id
																AND a.departemen_id = e.department_id
																AND b.department_id = e.department_id
																AND d.divisi_id = e.divisi_id
																AND a.subdepartemen_id = f.subdepartemen_id
																AND b.subdepartemen_id = f.subdepartemen_id
																AND e.department_id = f.department_id
																AND c.status <> 'rejected'
																AND c.complete <> ''
																AND concat(b.bulan,b.tahun)
																BETWEEN '$tgl_awal'
																AND '$tgl_akhir'
																GROUP BY $groupby");
										}
								}else{//jika bukan all
								    if($_POST[groupby]=='kode_budget'){
											$sql = mysql_query("select kode_budget as groupby,keterangan,
											                   concat( substring( bulan, 1, 3 ) , ' ', substring(tahun, 3, 2 ) ) AS periode, 
															   value from master_budget where
											                   concat(bulan,tahun) between '$tgl_awal' and '$tgl_akhir' and status='$_POST[approve]'");
									}else{
											$sql = mysql_query("SELECT $groupby as groupby, $keterangan as keterangan, 
												                concat( substring( b.bulan, 1, 3 ) , ' ', substring( b.tahun, 3, 2 ) ) AS periode, 
																sum(b.value)as value, sum(b.value) - sum( a.value ) AS outstanding_cost, sum( a.value ) AS cost, 
																sum( a.value ) / sum(b.value) *100 AS persen
																FROM detail_reco_item a, master_budget b, reco_request c, master_divisi d,
																master_department e, master_subdepartemen f
																WHERE a.kode_reco = c.kode_promo
																AND a.kode_budget = b.kode_budget
																AND a.divisi_id = d.divisi_id
																AND b.divisi_id = d.divisi_id
																AND a.departemen_id = e.department_id
																AND b.department_id = e.department_id
																AND d.divisi_id = e.divisi_id
																AND a.subdepartemen_id = f.subdepartemen_id
																AND b.subdepartemen_id = f.subdepartemen_id
																AND e.department_id = f.department_id
																AND concat(b.bulan,b.tahun)
																BETWEEN '$tgl_awal'
																AND '$tgl_akhir'
																AND b.status = '$_POST[approve]'
																GROUP BY $groupby");
									}
								}
							}else{
							    //cek jika approval satusnya semua 
								if($_POST[approve]=='all'){
									$sql = mysql_query("select * from master_budget where concat(bulan,tahun) between '$tgl_awal' 
									                   and '$tgl_akhir' and kode_budget='$_POST[budget]' group by $_POST[groupby]");
								}else{//jika bukan all
									$sql = mysql_query("select * from master_budget where concat(bulan,tahun) between '$tgl_awal' 
									                   and '$tgl_akhir' and kode_budget='$_POST[budget]'  and status='$_POST[approve]' 
													   group by $_POST[groupby]");
								}							
							}
						}else{//jika all divisi, alldepartemen, dan subdepartemen tertentu
							if($_POST[budget]==''){
								//cek jika approval satusnya semua  
								if($_POST[approve]=='all'){
									$sql = mysql_query("select * from master_budget where concat(bulan,tahun) between '$tgl_awal' 
									                   and '$tgl_akhir' and subdepartemen_id='$_POST[subdepartemen]' group by $_POST[groupby]");
								}else{//jika bukan all
									$sql = mysql_query("select * from master_budget where concat(bulan,tahun) between '$tgl_awal' 
									                   and '$tgl_akhir' and subdepartemen_id='$_POST[subdepartemen]' 
													   and status='$_POST[approve]' group by $_POST[groupby]");
								}
							}else{
								//cek jika approval satusnya semua  
								if($_POST[approve]=='all'){
									$sql = mysql_query("select * from master_budget where concat(bulan,tahun) between '$tgl_awal' 
									                   and '$tgl_akhir' and subdepartemen_id='$_POST[subdepartemen]' 
													   and kode_budget='$_POST[budget]' group by $_POST[groupby]");
								}else{//jika bukan all
									$sql = mysql_query("select * from master_budget where concat(bulan,tahun) between '$tgl_awal' 
									                   and '$tgl_akhir' and subdepartemen_id='$_POST[subdepartemen]' 
													   and kode_budget='$_POST[budget]' and status='$_POST[approve]' group by $_POST[groupby]");
								}							
							}
						}
				}else{//dek jika departemennya bukan all
						//jika all divisi, departemen dipilih, all subdepartemen
						if($_POST[subdepartemen]=='*'){
						    if($_POST[budget]==''){
								//cek jika approval satusnya semua 
								if($_POST[approve]=='all'){
									$sql = mysql_query("select * from master_budget where concat(bulan,tahun) between '$tgl_awal' 
									                   and '$tgl_akhir' and department_id='$_POST[departemen]' group by $_POST[groupby]");
								}else{//jika bukan all
									$sql = mysql_query("select * from master_budget where concat(bulan,tahun) between '$tgl_awal' 
									                   and '$tgl_akhir' and department_id='$_POST[departemen]' and status='$_POST[approve]' group by $_POST[groupby]");
								}
							}else{
								//cek jika approval satusnya semua 
								if($_POST[approve]=='all'){
									$sql = mysql_query("select * from master_budget where concat(bulan,tahun) between '$tgl_awal' 
									                   and '$tgl_akhir' and department_id='$_POST[departemen]' 
													   and kode_budget='$_POST[budget]' group by $_POST[groupby]");
								}else{//jika bukan all
									$sql = mysql_query("select * from master_budget where concat(bulan,tahun) between '$tgl_awal' 
									                   and '$tgl_akhir' and department_id='$_POST[departemen]' and status='$_POST[approve]' 
													   and kode_budget='$_POST[budget]' group by $_POST[groupby]");
								}							
							}
						}else{//jika all divisi, alldepartemen, dan subdepartemen tertentu
							if($_POST[budget]==''){
								//cek jika approval satusnya semua 
								if($_POST[approve]=='all'){
									$sql = mysql_query("select * from master_budget where concat(bulan,tahun) between '$tgl_awal' 
									                   and '$tgl_akhir' and department_id='$_POST[departemen]' and subdepartemen_id='$_POST[subdepartemen]' 
													   group by $_POST[groupby]");
								}else{//jika bukan all
									$sql = mysql_query("select * from master_budget where concat(bulan,tahun) between '$tgl_awal' 
									                   and '$tgl_akhir' and department_id='$_POST[departemen]' and subdepartemen_id='$_POST[subdepartemen]' 
													   and status='$_POST[approve]' group by $_POST[groupby]");
								}
							}else{
								//cek jika approval satusnya semua 
								if($_POST[approve]=='all'){
									$sql = mysql_query("select * from master_budget where concat(bulan,tahun) between '$tgl_awal' 
									                   and '$tgl_akhir' and department_id='$_POST[departemen]' and subdepartemen_id='$_POST[subdepartemen]' 
													   and kode_budget='$_POST[budget]' group by $_POST[groupby]");
								}else{//jika bukan all
									$sql = mysql_query("select * from master_budget where concat(bulan,tahun) between '$tgl_awal' 
									                   and '$tgl_akhir' and department_id='$_POST[departemen]' and subdepartemen_id='$_POST[subdepartemen]' 
													   and status='$_POST[approve]' and kode_budget='$_POST[budget]' group by $_POST[groupby]");
								}							
							}
						}
				}
			}else{//jika bukan all divisi
				//cek apakah departemenya all
				if($_POST[departemen]=='*'){
						//jika all divisi, all departemen, all subdepartemen
						if($_POST[subdepartemen]=='*'){
						    if($_POST[budget]==''){
								//cek jika approval satusnya semua 
								if($_POST[approve]=='all'){
									$sql = mysql_query("select * from master_budget where concat(bulan,tahun) between '$tgl_awal' 
									                   and '$tgl_akhir' and divisi_id='$_POST[divisi]'  group by $_POST[groupby]");
								}else{//jika bukan all
									$sql = mysql_query("select * from master_budget where concat(bulan,tahun) between '$tgl_awal' 
									                   and '$tgl_akhir' and divisi_id='$_POST[divisi]' and status='$_POST[approve]' 
													   group by $_POST[groupby]");
								}
							}else{
								//cek jika approval satusnya semua 
								if($_POST[approve]=='all'){
									$sql = mysql_query("select * from master_budget where concat(bulan,tahun) between '$tgl_awal' 
									                   and '$tgl_akhir' and divisi_id='$_POST[divisi]'  and kode_budget='$_POST[budget]' 
													   group by $_POST[groupby]");
								}else{//jika bukan all
									$sql = mysql_query("select * from master_budget where concat(bulan,tahun) between '$tgl_awal' 
									                   and '$tgl_akhir' and divisi_id='$_POST[divisi]' and status='$_POST[approve]' 
													   and kode_budget='$_POST[budget]' group by $_POST[groupby]");
								}							
							}
						}else{//jika all divisi, alldepartemen, dan subdepartemen tertentu
							if($_POST[budget]==''){
								//cek jika approval satusnya semua 
								if($_POST[approve]=='all'){
									$sql = mysql_query("select * from master_budget where concat(bulan,tahun) between '$tgl_awal' 
									                   and '$tgl_akhir' and divisi_id='$_POST[divisi]' and subdepartemen_id='$_POST[subdepartemen]'  
													   group by $_POST[groupby]");
								}else{//jika bukan all
									$sql = mysql_query("select * from master_budget where concat(bulan,tahun) between '$tgl_awal' 
									                   and '$tgl_akhir' and divisi_id='$_POST[divisi]' and subdepartemen_id='$_POST[subdepartemen]'
													   and status='$_POST[approve]' group by $_POST[groupby]");
								}
							}else{
								//cek jika approval satusnya semua 
								if($_POST[approve]=='all'){
									$sql = mysql_query("select * from master_budget where concat(bulan,tahun) between '$tgl_awal' 
									                   and '$tgl_akhir' and divisi_id='$_POST[divisi]' and subdepartemen_id='$_POST[subdepartemen]'  
													   and kode_budget='$_POST[budget]' group by $_POST[groupby]");
								}else{//jika bukan all
									$sql = mysql_query("select * from master_budget where concat(bulan,tahun) between '$tgl_awal' 
									                   and '$tgl_akhir' and divisi_id='$_POST[divisi]' and subdepartemen_id='$_POST[subdepartemen]'
													   and status='$_POST[approve]' and kode_budget='$_POST[kode_budget]' group by $_POST[groupby]");
								}							
							}
						}
				}else{//dek jika departemennya bukan all
				        //jika all divisi, all departemen, all subdepartemen
						if($_POST[subdepartemen]=='*'){
						    if($_POST[budget]==''){
								//cek jika approval satusnya semua 
								if($_POST[approve]=='all'){
									$sql = mysql_query("select * from master_budget where concat(bulan,tahun) between '$tgl_awal' 
									                   and '$tgl_akhir' and divisi_id='$_POST[divisi]' and department_id='$_POST[departemen]'   
													   group by $_POST[groupby]");
								}else{//jika bukan all
									$sql = mysql_query("select * from master_budget where concat(bulan,tahun) between '$tgl_awal' 
									                   and '$tgl_akhir' and divisi_id='$_POST[divisi]' and department_id='$_POST[departemen]' 
													   and status='$_POST[approve]' group by $_POST[groupby]");
								}
							}else{
								//cek jika approval satusnya semua 
								if($_POST[approve]=='all'){
									$sql = mysql_query("select * from master_budget where concat(bulan,tahun) between '$tgl_awal' 
									                   and '$tgl_akhir' and divisi_id='$_POST[divisi]' and department_id='$_POST[departemen]'   
													   and kode_budget='$_POST[budget]' group by $_POST[groupby]");
								}else{//jika bukan all
									$sql = mysql_query("select * from master_budget where concat(bulan,tahun) between '$tgl_awal' 
									                   and '$tgl_akhir' and divisi_id='$_POST[divisi]' and department_id='$_POST[departemen]' 
													   and status='$_POST[approve]' and kode_budget='$_POST[budget]' group by $_POST[groupby]");
								}							
							}
						}else{//jika all divisi, alldepartemen, dan subdepartemen tertentu
							if($_POST[budget]==''){
								if($_POST[approve]=='all'){
									$sql = mysql_query("select * from master_budget where concat(bulan,tahun) between '$tgl_awal' 
									                   and '$tgl_akhir' and divisi_id='$_POST[divisi]' and department_id='$_POST[departemen]'   
													   and subdepartemen_id='$_POST[subdepartemen]' group by $_POST[groupby]");
								}else{//jika bukan all
									$sql = mysql_query("select * from master_budget where concat(bulan,tahun) between '$tgl_awal' 
									                   and '$tgl_akhir' and divisi_id='$_POST[divisi]' and department_id='$_POST[departemen]' 
													   and subdepartemen_id='$_POST[subdepartemen]' and status='$_POST[approve]' 
													   group by $_POST[groupby]");
								}
							}else{
								if($_POST[approve]=='all'){
									$sql = mysql_query("select * from master_budget where concat(bulan,tahun) between '$tgl_awal' 
									                   and '$tgl_akhir' and divisi_id='$_POST[divisi]' and department_id='$_POST[departemen]'   
													   and subdepartemen_id='$_POST[subdepartemen]' and kode_budget='$_POST[budget]' group by $_POST[groupby]");
								}else{//jika bukan all
									$sql = mysql_query("select * from master_budget where concat(bulan,tahun) between '$tgl_awal' 
									                   and '$tgl_akhir' and divisi_id='$_POST[divisi]' and department_id='$_POST[departemen]' 
													   and subdepartemen_id='$_POST[subdepartemen]' and status='$_POST[approve]' 
													   and kode_budget='$_POST[kode_budget]' group by $_POST[groupby]");
								}							
							}
						}
				}
			}
			
					//menampilkan tabel 
					echo "<bR><bR><div id='tabel'><table class='table table-condensed table-hover ' >
							<tr><td><b>No.</b></td><tD><b>$_POST[groupby]</b></td><td ><b>Keterangan</b></td><td ><b>Periode</b></td>
							<tD><b>Budget Ammount</b></td><td><b>Used Ammount</b></td><tD><b>Outstanding</b></td><tD><b>( % )</b></td></tr>";
					$no =1;
					while($r = mysql_fetch_array($sql)){
							if($_POST[groupby]=='kode_budget'){
								$cost = mysql_query("select sum(a.value)as cost from detail_reco_item a,reco_request b where 
								                    a.kode_reco=b.kode_promo and a.kode_budget='$r[groupby]' and b.complete<>'' and b.status<>'rejected'");
								$rcost = mysql_fetch_array($cost);
								
								$cost = $rcost[cost];
								$outstanding = $r[value]-$cost ;
								$persen = $cost / $r[value] * 100;
							}else{
								$cost = $r[cost];
								$outstanding = $r[outstanding_cost];
								$persen = $r[persen];
							}
							echo "<tr>
									<td>$no.</td>
									<td>$r[groupby]</td>
									<td>$r[keterangan]</td>
									<td>$r[periode]</td>
									<tD>".number_format($r[value],2,',','.')."</td>
									<tD>".number_format($cost,2,',','.')."</td>
									<tD>".number_format($outstanding,2,',','.')."</td>
									<td>".number_format($persen,2,',','.')." %</td>
								</tr>";
							
							$total_budget = $total_budget + $r[value];
							$total_cost = $total_cost + $cost;
							$total_outstanding = $total_outstanding + $outstanding;
							
							$no++;
							
					}
					echo "<tR>
							<td colspan=3></td><td><b>TOTAL</b></td>
							<tD><b>".@number_format($total_budget,2,',','.')."</b></td>
							<tD><b>".@number_format($total_cost,2,',','.')."</b></td>
							<tD><b>".@number_format($total_outstanding,2,',','.')."</b></td>
							<tD><b>".@number_format($total_cost/$total_budget*100,2,',','.')." %</b></td>
						 </tr></table></div>";
	  }
}
?>
