<?php
session_start();
include "../../../configuration/connection_inc.php";
include "../../../function/security.php";
include "../../../function/get_sql.php";


if ($_GET[data]=='reco_report'){
		$access = read_security();
		if($access=="allow"){
				$tgl_awal = bulan(date('m',strtotime($_POST[tgl_awal]))).date('Y',strtotime($_POST[tgl_awal]));
				$tgl_akhir = bulan(date('m',strtotime($_POST[tgl_akhir]))).date('Y',strtotime($_POST[tgl_akhir]));
				$approve = strtolower($_POST[approve]);

				echo "<table>
						<tR><tD>Group By</td><td>:</td><td>$_POST[groupby]</td></tr>
						<tR><tD>Claim Periode</td><td>:</td><td>$_POST[tgl_awal] S/D $_POST[tgl_akhir]</td></tr>
						<tR><tD>Divisi</td><td>:</td><td>$_POST[divisi]</td></tr>
						<tR><tD>Departemen</td><td>:</td><td>$_POST[departemen]</td></tr>
						<tR><tD>Sub Departemen</td><td>:</td><td>$_POST[subdepartemen]</td></tr>
						<tR><tD>Area</td><td>:</td><td>$_POST[area]</td></tr>
						<tR><tD>Distributor</td><td>:</td><td>$_POST[distributor]</td></tr>
						<tR><tD>Promo Group</td><td>:</td><td>$_POST[promogroup]</td></tr>
						<tR><tD>Promo Type</td><td>:</td><td>$_POST[promotype]</td></tr>
						<tR><tD>Promo Class</td><td>:</td><td>$_POST[classpromo]</td></tr>
						<tR><tD>Kode Reco</td><td>:</td><td>$_POST[kode_reco]</td></tr>					
				  </table>";
				
				if($_POST[groupby]=='divisi_id'){ $groupid = 'divisi_id'; $groupname = 'divisi_name'; }
				if($_POST[groupby]=='department_id'){ $groupid = 'departemen_id'; $groupname = 'department_name'; }
				if($_POST[groupby]=='subdepartemen_id'){ $groupid= 'subdepartemen_id'; $groupname = 'subdepartemen_name'; }
				if($_POST[groupby]=='area_id'){ $groupid = 'area_id'; $groupname = 'area_name'; }
				if($_POST[groupby]=='distributor_id'){ $groupid = 'distributor_id'; $groupname = 'distributor_name'; }
				if($_POST[groupby]=='promogroup_id'){ $groupid='grouppromo_id'; $groupname = 'grouppromo_name'; }
				if($_POST[groupby]=='promotype_id'){ $groupid = 'promotype_id'; $groupname = 'promotype_name'; }
				if($_POST[groupby]=='class_id'){ $groupid = 'class_id'; $groupname = 'class_id'; }
				if($_POST[groupby]=='kode_promo'){ $groupid = 'kode_promo'; $groupname = 'title'; }
				
				//jika user memilih laporan untuk summary
				if($_POST[display]=='summary'){
						if($_POST[divisi]=='0'){
								if($_POST[departemen]=='*'){
									if($_POST[subdepartemen]=='*'){
										if($_POST[area]=='DAXX'){
											if($_POST[distributor]=='XXXX'){
												if($_POST[promogroup]==''){
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]' 
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}else{//jika promogroupnya tidak semua ...
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and grouppromo_id='$_POST[promogroup]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]'  and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and grouppromo_id='$_POST[promogroup]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and grouppromo_id='$_POST[promogroup]' 
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{//jika class promo tidak semua
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}
											}else{//jika distributornya tidak sama dengan all distributor
												if($_POST[promogroup]==''){
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]' 
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and distributor_id='$_POST[distributor]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]' 
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and distributor_id='$_POST[distributor]'
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and distributor_id='$_POST[distributor]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and distributor_id='$_POST[distributor]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}else{//jika promogroupnya tidak semua ...
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]'  and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]' 
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{//jika class promo tidak semua
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}
											}
										}else{//kalau areanya tidak semua
											if($_POST[distributor]=='XXXX'){
												if($_POST[promogroup]==''){
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}else{//jika promogroupnya tidak semua ...
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]'  and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and grouppromo_id='$_POST[promogroup]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and grouppromo_id='$_POST[promogroup]' 
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{//jika class promo tidak semua
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}
											}else{//jika distributornya tidak sama dengan all distributor
												if($_POST[promogroup]==''){
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]' 
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]' 
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}else{//jika promogroupnya tidak semua ...
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]'  and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]' 
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{//jika class promo tidak semua
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}
											}
										}
									}else{//jika subdepartemennya tidak semua
										if($_POST[area]=='DAXX'){
											if($_POST[distributor]=='XXXX'){
												if($_POST[promogroup]==''){
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}else{//jika promogroupnya tidak semua ...
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and grouppromo_id='$_POST[promogroup]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]' 
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]'  and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and grouppromo_id='$_POST[promogroup]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and grouppromo_id='$_POST[promogroup]' 
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{//jika class promo tidak semua
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}
											}else{//jika distributornya tidak sama dengan all distributor
												if($_POST[promogroup]==''){
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]' 
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and distributor_id='$_POST[distributor]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]' 
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and distributor_id='$_POST[distributor]'
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and distributor_id='$_POST[distributor]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and distributor_id='$_POST[distributor]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}else{//jika promogroupnya tidak semua ...
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]'  and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]' 
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{//jika class promo tidak semua
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}
											}
										}else{//kalau areanya tidak semua
											if($_POST[distributor]=='XXXX'){
												if($_POST[promogroup]==''){
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}else{//jika promogroupnya tidak semua ...
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]' 
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]'  and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and grouppromo_id='$_POST[promogroup]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and grouppromo_id='$_POST[promogroup]' 
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{//jika class promo tidak semua
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}
											}else{//jika distributornya tidak sama dengan all distributor
												if($_POST[promogroup]==''){
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]' 
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]' 
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}else{//jika promogroupnya tidak semua ...
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]'  and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]' 
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{//jika class promo tidak semua
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}
											}
										}
									}
								}else{ //jika departemen tidak dengan all
									if($_POST[subdepartemen]=='*'){
										if($_POST[area]=='DAXX'){
											if($_POST[distributor]=='XXXX'){
												if($_POST[promogroup]==''){
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and departemen_id='$_POST[departemen]' 
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]' 
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}else{//jika promogroupnya tidak semua ...
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and grouppromo_id='$_POST[promogroup]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]'  and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and grouppromo_id='$_POST[promogroup]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and grouppromo_id='$_POST[promogroup]' 
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{//jika class promo tidak semua
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}
											}else{//jika distributornya tidak sama dengan all distributor
												if($_POST[promogroup]==''){
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]' 
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and distributor_id='$_POST[distributor]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]' 
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and distributor_id='$_POST[distributor]'
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and distributor_id='$_POST[distributor]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and distributor_id='$_POST[distributor]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}else{//jika promogroupnya tidak semua ...
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]'  and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]' 
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{//jika class promo tidak semua
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}
											}
										}else{//kalau areanya tidak semua
											if($_POST[distributor]=='XXXX'){
												if($_POST[promogroup]==''){
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}else{//jika promogroupnya tidak semua ...
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]'  and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and grouppromo_id='$_POST[promogroup]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and grouppromo_id='$_POST[promogroup]' 
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{//jika class promo tidak semua
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}
											}else{//jika distributornya tidak sama dengan all distributor
												if($_POST[promogroup]==''){
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]' 
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]' 
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}else{//jika promogroupnya tidak semua ...
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]'  and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]' 
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{//jika class promo tidak semua
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}
											}
										}
									}else{//jika subdepartemennya tidak semua
										if($_POST[area]=='DAXX'){
											if($_POST[distributor]=='XXXX'){
												if($_POST[promogroup]==''){
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}else{//jika promogroupnya tidak semua ...
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and grouppromo_id='$_POST[promogroup]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]' 
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]'  and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and grouppromo_id='$_POST[promogroup]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and grouppromo_id='$_POST[promogroup]' 
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{//jika class promo tidak semua
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}
											}else{//jika distributornya tidak sama dengan all distributor
												if($_POST[promogroup]==''){
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]' 
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and distributor_id='$_POST[distributor]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]' 
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and distributor_id='$_POST[distributor]'
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and distributor_id='$_POST[distributor]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and distributor_id='$_POST[distributor]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}else{//jika promogroupnya tidak semua ...
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]'  and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]' 
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{//jika class promo tidak semua
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}
											}
										}else{//kalau areanya tidak semua
											if($_POST[distributor]=='XXXX'){
												if($_POST[promogroup]==''){
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}else{//jika promogroupnya tidak semua ...
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]' 
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]'  and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and grouppromo_id='$_POST[promogroup]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and grouppromo_id='$_POST[promogroup]' 
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{//jika class promo tidak semua
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}
											}else{//jika distributornya tidak sama dengan all distributor
												if($_POST[promogroup]==''){
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]' 
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]' 
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}else{//jika promogroupnya tidak semua ...
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]'  and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]' 
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{//jika class promo tidak semua
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}
											}
										}
									}
								}
						}else{// jika divisinya tidak sama dengan all
								if($_POST[departemen]=='*'){
									if($_POST[subdepartemen]=='*'){
										if($_POST[area]=='DAXX'){
											if($_POST[distributor]=='XXXX'){
												if($_POST[promogroup]==''){
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and divisi_id='$_POST[divisi]' 
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]' 
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}else{//jika promogroupnya tidak semua ...
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and grouppromo_id='$_POST[promogroup]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]'  and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and grouppromo_id='$_POST[promogroup]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and grouppromo_id='$_POST[promogroup]' 
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{//jika class promo tidak semua
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}
											}else{//jika distributornya tidak sama dengan all distributor
												if($_POST[promogroup]==''){
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]' 
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and distributor_id='$_POST[distributor]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]' 
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and distributor_id='$_POST[distributor]'
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and distributor_id='$_POST[distributor]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and distributor_id='$_POST[distributor]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}else{//jika promogroupnya tidak semua ...
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]'  and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]' 
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{//jika class promo tidak semua
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}
											}
										}else{//kalau areanya tidak semua
											if($_POST[distributor]=='XXXX'){
												if($_POST[promogroup]==''){
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}else{//jika promogroupnya tidak semua ...
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]'  and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and grouppromo_id='$_POST[promogroup]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and grouppromo_id='$_POST[promogroup]' 
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{//jika class promo tidak semua
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}
											}else{//jika distributornya tidak sama dengan all distributor
												if($_POST[promogroup]==''){
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]' 
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]' 
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}else{//jika promogroupnya tidak semua ...
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]'  and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]' 
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{//jika class promo tidak semua
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}
											}
										}
									}else{//jika subdepartemennya tidak semua
										if($_POST[area]=='DAXX'){
											if($_POST[distributor]=='XXXX'){
												if($_POST[promogroup]==''){
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}else{//jika promogroupnya tidak semua ...
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and grouppromo_id='$_POST[promogroup]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]' 
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]'  and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and grouppromo_id='$_POST[promogroup]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and grouppromo_id='$_POST[promogroup]' 
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{//jika class promo tidak semua
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}
											}else{//jika distributornya tidak sama dengan all distributor
												if($_POST[promogroup]==''){
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]' 
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and distributor_id='$_POST[distributor]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]' 
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and distributor_id='$_POST[distributor]'
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and distributor_id='$_POST[distributor]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and distributor_id='$_POST[distributor]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}else{//jika promogroupnya tidak semua ...
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]'  and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]' 
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{//jika class promo tidak semua
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}
											}
										}else{//kalau areanya tidak semua
											if($_POST[distributor]=='XXXX'){
												if($_POST[promogroup]==''){
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}else{//jika promogroupnya tidak semua ...
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]' 
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]'  and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and grouppromo_id='$_POST[promogroup]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and grouppromo_id='$_POST[promogroup]' 
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{//jika class promo tidak semua
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}
											}else{//jika distributornya tidak sama dengan all distributor
												if($_POST[promogroup]==''){
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]' 
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]' 
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}else{//jika promogroupnya tidak semua ...
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]'  and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]' 
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{//jika class promo tidak semua
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}
											}
										}
									}
								}else{ //jika departemen tidak dengan all
									if($_POST[subdepartemen]=='*'){
										if($_POST[area]=='DAXX'){
											if($_POST[distributor]=='XXXX'){
												if($_POST[promogroup]==''){
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]' 
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]' 
																					   group by $groupid,$groupname");
																					   echo "select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]' 
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]' 
																					   group by $groupid,$groupname";
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}else{//jika promogroupnya tidak semua ...
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and grouppromo_id='$_POST[promogroup]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]'  and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and grouppromo_id='$_POST[promogroup]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and grouppromo_id='$_POST[promogroup]' 
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{//jika class promo tidak semua
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}
											}else{//jika distributornya tidak sama dengan all distributor
												if($_POST[promogroup]==''){
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]' 
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and distributor_id='$_POST[distributor]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]' 
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and distributor_id='$_POST[distributor]'
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and distributor_id='$_POST[distributor]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and distributor_id='$_POST[distributor]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}else{//jika promogroupnya tidak semua ...
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]'  and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]' 
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{//jika class promo tidak semua
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}
											}
										}else{//kalau areanya tidak semua
											if($_POST[distributor]=='XXXX'){
												if($_POST[promogroup]==''){
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}else{//jika promogroupnya tidak semua ...
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]'  and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and grouppromo_id='$_POST[promogroup]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and grouppromo_id='$_POST[promogroup]' 
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{//jika class promo tidak semua
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}
											}else{//jika distributornya tidak sama dengan all distributor
												if($_POST[promogroup]==''){
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]' 
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]' 
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}else{//jika promogroupnya tidak semua ...
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]'  and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]' 
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{//jika class promo tidak semua
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}
											}
										}
									}else{//jika subdepartemennya tidak semua
										if($_POST[area]=='DAXX'){
											if($_POST[distributor]=='XXXX'){
												if($_POST[promogroup]==''){
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}else{//jika promogroupnya tidak semua ...
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and grouppromo_id='$_POST[promogroup]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]' 
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]'  and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and grouppromo_id='$_POST[promogroup]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and grouppromo_id='$_POST[promogroup]' 
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{//jika class promo tidak semua
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}
											}else{//jika distributornya tidak sama dengan all distributor
												if($_POST[promogroup]==''){
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]' 
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and distributor_id='$_POST[distributor]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]' 
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and distributor_id='$_POST[distributor]'
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and distributor_id='$_POST[distributor]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and distributor_id='$_POST[distributor]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}else{//jika promogroupnya tidak semua ...
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]'  and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]' 
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{//jika class promo tidak semua
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}
											}
										}else{//kalau areanya tidak semua
											if($_POST[distributor]=='XXXX'){
												if($_POST[promogroup]==''){
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}else{//jika promogroupnya tidak semua ...
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]' 
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]'  and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and grouppromo_id='$_POST[promogroup]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and grouppromo_id='$_POST[promogroup]' 
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{//jika class promo tidak semua
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}
											}else{//jika distributornya tidak sama dengan all distributor
												if($_POST[promogroup]==''){
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]' 
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]' 
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}else{//jika promogroupnya tidak semua ...
													if($_POST[promotype]==''){
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]'  and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]'
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]'  and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]' 
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]' 
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}else{//jika promotype tidak sama dengan kosong//edit here
														if($_POST[classpromo]==''){
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and kode_promo='$_POST[kode_reco]' and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}else{//jika class promo tidak semua
															if($_POST[kode_reco]==''){
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]' group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}else{//jika reco tidak semua 
																if($_POST[approve]=='all'){
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			            where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																						and departemen_id='$_POST[departemen]'
																						and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																						and subdepartemen_id='$_POST[departemen]'
																						and area_id='$_POST[area]'
																						and distributor_id='$_POST[distributor]'
																						and grouppromo_id='$_POST[promogroup]'
																						and promotype_id='$_POST[promotype]'  
																						and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]'  group by $groupid,$groupname");
																}else{
																	$sql = mysql_query("select $groupid as groupid,$groupname as groupname,sum(cost_of_promo)as cost_of_promo,
                																	   sum(total_sales_target)as total_sales_target from v_reco_request 
																			           where claimtradeoff='$_POST[claimtradeoff]' and divisi_id='$_POST[divisi]'
																					   and departemen_id='$_POST[departemen]'
																					   and tgl_promo between '$_POST[tgl_awal]' and '$_POST[tgl_akhir]'
																					   and subdepartemen_id='$_POST[departemen]'
																					   and area_id='$_POST[area]'
																					   and distributor_id='$_POST[distributor]'
																					   and grouppromo_id='$_POST[promogroup]'
																					   and promotype_id='$_POST[promotype]'  
																					   and class_id='$_POST[classpromo]'  and kode_promo='$_POST[kode_reco]' 
																					   and status='$_POST[approve]' group by $groupid,$groupname");
																}
															}
														}
													}
												}
											}
										}
									}
								}
						}
						
						echo "<br><Br><div id='tabel'><table class='table table-condensed table-hover '>
						<tr><td><b>No.</b></td><td><b>$groupid</b></td><td><b>$groupname</b></td><td><b>Cost Of Promo</b></td>";
						if($_POST[groupby]=='kode_promo'){ echo "<td><b>Cost Of Promo Left</b></td>"; }
						echo "<td><b>Target</b></td></tR>";
						$no =1;
						while($r = mysql_fetch_array($sql)){
							echo "<tR>
									<td>$no</td>
									<td>$r[groupid]</td>
									<tD>$r[groupname]</td>
									<td>".number_format($r[cost_of_promo],2,'.',',')."</td>";
							if($_POST[groupby]=='kode_promo')
							{ 
								$recoleft = mysql_query("SELECT $r[cost_of_promo]-ifnull(sum(claim_approved_ammount),0)as total FROM `claim_request` 
								                        WHERE kode_promo='$r[groupid]' and status<>'rejected'");
								$rrecoleft = mysql_fetch_array($recoleft);
								echo "<td>".number_format($rrecoleft[total],2,'.',',')."</td>"; 
							}
							echo "<td>".number_format($r[total_sales_target],2,'.',',')."</td>
								</tr>";
							$total_cost = $total_cost + $r[cost_of_promo];
							$total_left = $total_left + $rrecoleft[total];
							$total_target = $total_target + $r[total_sales_target];
							$no++;
						}
						echo "<tr>
								<td colspan=2><td><b>TOTAL</b></td>
								<td><b>".number_format($total_cost,2,'.',',')."</b></td>";
						if($_POST[groupby]=='kode_promo'){ echo "<td><b>".number_format($total_left,2,'.',',')."</b></td>"; }
						echo "	<td><b>".number_format($total_target,2,'.',',')."</b></td>
							 </tr>";
						echo "</table></div>";
				}	

				
		}else{
			msg_security();
		}
}

?>
