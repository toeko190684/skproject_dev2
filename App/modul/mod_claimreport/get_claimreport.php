<?php
session_start();
include "../../../configuration/connection_inc.php";
include "../../../function/security.php";
include "../../../function/get_sql.php";



if ($_GET[data]=='claim_report'){
	  $access = read_security();
	  if($access=="allow"){
			$tgl_awal = bulan(date('m',strtotime($_POST[tgl_awal]))).date('Y',strtotime($_POST[tgl_awal]));
			$tgl_akhir = bulan(date('m',strtotime($_POST[tgl_akhir]))).date('Y',strtotime($_POST[tgl_akhir]));
			$approve = strtolower($_POST[approve]);
			
			if($_POST[groupby]=='journal_id'){ 
				$groupid = 'journal_id'; 
				$groupname = 'claim_deskripsi'; 
				$titleid = 'Journal ID';
				$titlename = 'Deksripsi';
			}
			if($_POST[groupby]=='claim_number_system'){ 
				$groupid = 'claim_number_system'; 
				$groupname = 'claim_deskripsi'; 
				$titleid = 'Claim Number System';
				$titlename = 'Deskripsi';
			}
			if($_POST[groupby]=='kode_promo'){ 
				$groupid = 'kode_promo'; 
				$groupname = 'title'; 
				$titleid = 'Kode Reco';
				$titlename = 'Title';
			}
			if($_POST[groupby]=='area_id'){ 
				$groupid = 'area_id'; 
				$groupname = 'area_name'; 
				$titleid = 'Area ID';
				$titlename = 'Area Name';
			}
			if($_POST[groupby]=='distributor_id'){ 
				$groupid = 'distributor_id'; 
				$groupname = 'distributor_name'; 
				$titleid = 'Dist. ID';
				$titlename = 'Dist. Name';
			}
			if($_POST[groupby]=='divisi_id'){ 
				$groupid = 'divisi_id'; 
				$groupname = 'divisi_name';
				$titleid = 'Divisi ID';
				$titlename = 'Divisi Name';
			}
			if($_POST[groupby]=='departemen_id'){ 
				$groupid = 'departemen_id'; 
				$groupname = 'department_name'; 
				$titleid = 'Dept. ID';
				$titlename = 'Dept. Name';
			}
			if($_POST[groupby]=='subdepartemen_id'){ 
				$groupid= 'subdepartemen_id'; 
				$groupname = 'subdepartemen_name'; 
				$titleid = 'Subdepartemen ID';
				$titlename = 'Subdepartemen Name';
			}
			if($_POST[groupby]=='po_so_number'){ 
				$groupid='po_so_number'; 
				$groupname = 'claim_deskripsi'; 
				$titleid = 'PO SO Number';
				$titlename = 'Deskripsi';
			}
			if($_POST[groupby]=='nomor_faktur_pajak'){ 
				$groupid = 'nomor_faktur_pajak'; 
				$groupname = 'claim_deskripsi'; 
				$titleid = 'No. Faktur Pajak';
				$titlename = 'Deskripsi';
			}
			
			
			echo "<table>
					<tR><tD>Group By</td><td>:</td><td>$_POST[groupby]</td></tr>
					<tR><tD>Claim Periode</td><td>:</td><td>$_POST[tgl_awal] S/D $_POST[tgl_akhir]</td></tr>
					<tR><tD>Divisi</td><td>:</td><td>$_POST[divisi]</td></tr>
					<tR><tD>Departemen</td><td>:</td><td>$_POST[departemen]</td></tr>
					<tR><tD>Sub Departemen</td><td>:</td><td>$_POST[subdepartemen]</td></tr>
					<tR><tD>Area</td><td>:</td><td>$_POST[area]</td></tr>
					<tR><tD>Distributor</td><td>:</td><td>$_POST[distributor]</td></tr>
					<tR><tD>Kode Reco</td><td>:</td><td>$_POST[kode_reco]</td></tr>		
					<tR><tD>Claim Number System</td><td>:</td><td>$_POST[claimnumbersystem]</td></tr>						
				  </table>";
			
			if($_POST[divisi]=='0'){
				if($_POST[departemen]=='*'){
					if($_POST[subdepartemen]=='*'){
						if($_POST[area]=='DAXX'){
							if($_POST[distributor]=='XXXX'){
								if($_POST[kode_reco]==''){
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}else{//jika kode reco tidak kosong
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where kode_promo='$_POST[kode_reco]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}
							}else{//jika distributor tidak sama dengan all
								if($_POST[kode_reco]==''){
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where distributor_id='$_POST[distributor]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where distributor_id='$_POST[distributor]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where distributor_id='$_POST[distributor]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where distributor_id='$_POST[distributor]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}else{//jika kode reco tidak kosong
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}
							}
						}else{//jika areanya tidak semua
							if($_POST[distributor]=='XXXX'){
								if($_POST[kode_reco]==''){
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where area_id='$_POST[area]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where area_id='$_POST[area]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where area_id='$_POST[area]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where area_id='$_POST[area]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where area_id='$_POST[area]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where area_id='$_POST[area]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where area_id='$_POST[area]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where area_id='$_POST[area]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}else{//jika kode reco tidak kosong
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}
							}else{//jika distributor tidak sama dengan all
								if($_POST[kode_reco]==''){
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}else{//jika kode reco tidak kosong
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}
							}
						}
					}else{//jika subdepartemen tidak sama dengan all
						if($_POST[area]=='DAXX'){
							if($_POST[distributor]=='XXXX'){
								if($_POST[kode_reco]==''){
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}else{//jika kode reco tidak kosong
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	kode_promo='$_POST[kode_reco]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}
							}else{//jika distributor tidak sama dengan all
								if($_POST[kode_reco]==''){
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}else{//jika kode reco tidak kosong
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}
							}
						}else{//jika areanya tidak semua
							if($_POST[distributor]=='XXXX'){
								if($_POST[kode_reco]==''){
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}else{//jika kode reco tidak kosong
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}
							}else{//jika distributor tidak sama dengan all
								if($_POST[kode_reco]==''){
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}else{//jika kode reco tidak kosong
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}
							}
						}
					}
				}else{//jika departemen bukan all
					if($_POST[subdepartemen]=='*'){
						if($_POST[area]=='DAXX'){
							if($_POST[distributor]=='XXXX'){
								if($_POST[kode_reco]==''){
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}else{//jika kode reco tidak kosong
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	kode_promo='$_POST[kode_reco]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}
							}else{//jika distributor tidak sama dengan all
								if($_POST[kode_reco]==''){
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	distributor_id='$_POST[distributor]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	distributor_id='$_POST[distributor]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	distributor_id='$_POST[distributor]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	distributor_id='$_POST[distributor]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}else{//jika kode reco tidak kosong
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}
							}
						}else{//jika areanya tidak semua
							if($_POST[distributor]=='XXXX'){
								if($_POST[kode_reco]==''){
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}else{//jika kode reco tidak kosong
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}
							}else{//jika distributor tidak sama dengan all
								if($_POST[kode_reco]==''){
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}else{//jika kode reco tidak kosong
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}
							}
						}
					}else{//jika subdepartemen tidak sama dengan all
						if($_POST[area]=='DAXX'){
							if($_POST[distributor]=='XXXX'){
								if($_POST[kode_reco]==''){
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}else{//jika kode reco tidak kosong
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	kode_promo='$_POST[kode_reco]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}
							}else{//jika distributor tidak sama dengan all
								if($_POST[kode_reco]==''){
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}else{//jika kode reco tidak kosong
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}
							}
						}else{//jika areanya tidak semua
							if($_POST[distributor]=='XXXX'){
								if($_POST[kode_reco]==''){
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}else{//jika kode reco tidak kosong
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}
							}else{//jika distributor tidak sama dengan all
								if($_POST[kode_reco]==''){
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}else{//jika kode reco tidak kosong
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}
							}
						}
					}
				}
			}else{
				if($_POST[departemen]=='*'){
					if($_POST[subdepartemen]=='*'){
						if($_POST[area]=='DAXX'){
							if($_POST[distributor]=='XXXX'){
								if($_POST[kode_reco]==''){
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}else{//jika kode reco tidak kosong
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	kode_promo='$_POST[kode_reco]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}
							}else{//jika distributor tidak sama dengan all
								if($_POST[kode_reco]==''){
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	distributor_id='$_POST[distributor]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	distributor_id='$_POST[distributor]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	distributor_id='$_POST[distributor]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	distributor_id='$_POST[distributor]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}else{//jika kode reco tidak kosong
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}
							}
						}else{//jika areanya tidak semua
							if($_POST[distributor]=='XXXX'){
								if($_POST[kode_reco]==''){
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	area_id='$_POST[area]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	area_id='$_POST[area]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	area_id='$_POST[area]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	area_id='$_POST[area]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	area_id='$_POST[area]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	area_id='$_POST[area]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	area_id='$_POST[area]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	area_id='$_POST[area]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}else{//jika kode reco tidak kosong
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}
							}else{//jika distributor tidak sama dengan all
								if($_POST[kode_reco]==''){
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}else{//jika kode reco tidak kosong
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}
							}
						}
					}else{//jika subdepartemen tidak sama dengan all
						if($_POST[area]=='DAXX'){
							if($_POST[distributor]=='XXXX'){
								if($_POST[kode_reco]==''){
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}else{//jika kode reco tidak kosong
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	kode_promo='$_POST[kode_reco]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}
							}else{//jika distributor tidak sama dengan all
								if($_POST[kode_reco]==''){
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}else{//jika kode reco tidak kosong
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}
							}
						}else{//jika areanya tidak semua
							if($_POST[distributor]=='XXXX'){
								if($_POST[kode_reco]==''){
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}else{//jika kode reco tidak kosong
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}
							}else{//jika distributor tidak sama dengan all
								if($_POST[kode_reco]==''){
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}else{//jika kode reco tidak kosong
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}
							}
						}
					}
				}else{//jika departemen bukan all
					if($_POST[subdepartemen]=='*'){
						if($_POST[area]=='DAXX'){
							if($_POST[distributor]=='XXXX'){
								if($_POST[kode_reco]==''){
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}else{//jika kode reco tidak kosong
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	kode_promo='$_POST[kode_reco]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}
							}else{//jika distributor tidak sama dengan all
								if($_POST[kode_reco]==''){
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	distributor_id='$_POST[distributor]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	distributor_id='$_POST[distributor]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	distributor_id='$_POST[distributor]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	distributor_id='$_POST[distributor]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}else{//jika kode reco tidak kosong
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}
							}
						}else{//jika areanya tidak semua
							if($_POST[distributor]=='XXXX'){
								if($_POST[kode_reco]==''){
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}else{//jika kode reco tidak kosong
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}
							}else{//jika distributor tidak sama dengan all
								if($_POST[kode_reco]==''){
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}else{//jika kode reco tidak kosong
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}
							}
						}
					}else{//jika subdepartemen tidak sama dengan all
						if($_POST[area]=='DAXX'){
							if($_POST[distributor]=='XXXX'){
								if($_POST[kode_reco]==''){
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}else{//jika kode reco tidak kosong
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	kode_promo='$_POST[kode_reco]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}
							}else{//jika distributor tidak sama dengan all
								if($_POST[kode_reco]==''){
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}else{//jika kode reco tidak kosong
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}
							}
						}else{//jika areanya tidak semua
							if($_POST[distributor]=='XXXX'){
								if($_POST[kode_reco]==''){
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}else{//jika kode reco tidak kosong
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}
							}else{//jika distributor tidak sama dengan all
								if($_POST[kode_reco]==''){
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}else{//jika kode reco tidak kosong
									if($_POST[claimnumbersystem]==''){
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}else{//jika claim number systemnya tidak all
										if($_POST[approve]=='all'){
											if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and 
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}else{
										    if($_POST[display]=='summary'){
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,
																	sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname order by $groupid");
											}else{
												$sql = mysql_query("select $groupid as groupid,$groupname as groupname,claim_number_system,claim_date,
																	claim_deskripsi, sum(claim_approved_ammount)as claim_approved_ammount 
																	from v_claim_request where divisi_id='$_POST[divisi]' and
																	departemen_id='$_POST[departemen]' and
																	subdepartemen_id='$_POST[subdepartemen]' and
																	area_id='$_POST[area]' and
																	distributor_id='$_POST[distributor]' and
																	kode_promo='$_POST[kode_reco]' and
																	claim_number_system='$_POST[claimnumbersystem]' and
																	claim_date between '$_POST[tgl_awal]' 
												                    and '$_POST[tgl_akhir]' and claim_status='$_POST[approve]' group by $groupid,$groupname,claim_number_system,claim_deskripsi 
																	order by $groupid");
											}
										}
									}
								}
							}
						}
					}
				}
			}
			
			if($_POST[display]=='summary'){
					echo "<br><br><div id='tabel'><table class='table table-bordered table-hover'>
					     <tr><tD><b>No.</b></tD><td><b>$titleid</b></td><td><b>$titlename</b></td><td><b>Claim Approved Ammount</b></td>
						 </tr>";
					$no = 1;
					while($r = mysql_fetch_array($sql)){
     					echo "<tr>
								<td>$no.</td>
								<td>$r[groupid]</td>
								<td>$r[groupname]</td>
								<td>".number_format($r[claim_approved_ammount],2,'.',',')."</td>
							 </tr>";

						$total = $total + $r[claim_approved_ammount];
						$no++;
					}			
					echo "<tr><td colspan='2'></td><td><b>TOTAL</b></td><tD><b>".number_format($total,2,'.',',')."</b></tD></tr></table></div>";
			}else{
					echo "<br><br><div id='tabel'><table class='table table-bordered table-hover'>
					     <tr><tD><b>No.</b></tD><td><b>$titleid</b></td><td><b>$titlename</b></td>
						     <td><b>Claim Number System</b></td><td><b>Claim Date</b></td><td><b>Claim Deskripsi</b></td><td><b>Claim Approved Ammount</b></td>
						 </tr>";
					$no = 1;
					while($r = mysql_fetch_array($sql)){
						echo "<tr>
								<td>$no.</td>
								<td>$r[groupid]</td>
								<td>$r[groupname]</td>
								<td>$r[claim_number_system]</td>
								<td>$r[claim_date]</td>
								<td>$r[claim_deskripsi]</td>
								<td>".number_format($r[claim_approved_ammount],2,'.',',')."</td>
							 </tr>";
						if($groupid==$r[groupid]){
							$subtotal = $subtotal + $r[claim_approved_ammount];
							$tampil_subtotal = '';
						}else{
							$subtotal = $subtotal + $r[claim_approved_ammount];
							$tampil_subtotal = "<tr><td colspan='5'></td><td><b>SUBTOTAL</b></td><tD><b>".number_format($subtotal,2,'.',',')."</b></tD></tr>";
							$subtotal = 0;
							$groupid = $r[groupid];
						}				
						echo $tampil_subtotal;
						$total = $total + $r[claim_approved_ammount];
						$no++;
					}			
					echo "<tr><td colspan='5'></td><td><b>TOTAL</b></td><tD><b>".number_format($total,2,'.',',')."</b></tD></tr></table></div>";
			}

	  }else{
		msg_security();
	  }
}
?>
