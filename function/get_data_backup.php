<?php
require_once("../configuration/connection_inc.php");
require_once("../function/security.php");
require_once("../function/email.php");
session_start();

if($_GET[data]=='departemen'){
    if($_POST[grade_id]=='*'){
		$sql = mysql_query("select * from master_department where divisi_id='$_POST[id]' order by department_name");
	}else{
		if($_POST[grade_id]=='**'){
			$sql = mysql_query("select * from master_department where divisi_id='$_POST[id]' order by department_name");
		}else{
			$sql = mysql_query("select * from master_department where divisi_id='$_POST[id]' 
			                   and department_id='$_POST[departemen_id]' order by department_name");
		}
	}
	echo "<option value=''>--Pilih Departemen--</option>";
	while($r = mysql_fetch_array($sql)){
		echo "<option value='$r[department_id]'>$r[department_name]</option>";
	}
}

if($_GET[data]=='subdepartemen'){
	$sql = mysql_query("select * from master_subdepartemen where department_id='$_POST[id]' order by subdepartemen_name");
	echo "<option value=''>--Pilih SubDepartemen--</option>";
	while($r = mysql_fetch_array($sql)){
		echo "<option value='$r[subdepartemen_id]'>$r[subdepartemen_name]</option>";
	}
}

if($_GET[data]=='promotype'){
	$sql = mysql_query("select * from master_promotype where grouppromo_id='$_POST[id]' order by promotype_name");
	echo "<option value=''>--Pilih Promo Type--</option>";
	while($r = mysql_fetch_array($sql)){
		echo "<option value='$r[promotype_id]'>$r[promotype_name]</option>";
	}	
}

if($_GET[data]=='class'){
	echo "<option value=''>--Pilih Class--</option>";
	$sql = mysql_query("select * from master_class where promotype_id='$_POST[id]' order by class_name");
	while($r = mysql_fetch_array($sql)){
		echo "<option value='$r[class_id]'>$r[class_name]</option>";
	}
}

if($_GET[data]=='regional'){
	echo "<option value=''>--Pilih Regional--</option>";
	$sql = mysql_query("select * from regional where nasional_id='$_POST[id]' order by regional_name");
	while($r = mysql_fetch_array($sql)){
		echo "<option value='$r[regional_id]'>$r[regional_name]</option>";
	}
}

if($_GET[data]=='area'){
	echo "<option value=''>--Pilih Area--</option>";
	$sql = mysql_query("select * from area where regional_id='$_POST[id]' order by area_name");
	while($r = mysql_fetch_array($sql)){
		echo "<option value='$r[area_id]'>$r[area_name]</option>";
	}
}

if($_GET[data]=='distributor'){
	echo "<option value=''>--Pilih Distributor--</option>";
	$sql = mysql_query("select * from distributor where area_id='$_POST[id]' order by distributor_name");
	while($r = mysql_fetch_array($sql)){
		echo "<option value='$r[distributor_id]'>$r[distributor_name]</option>";
	}
}

if($_GET[data]=='cek_kodebudget'){
	$sql = mysql_query("select * from master_budget where kode_budget='$_POST[id]'");
	$r = mysql_num_rows($sql);
	if($r>0){
		echo "ketemu";
	}
}

if($_GET[data]=='bulan'){
	echo "<option value=''>--Bulan--</option>";
	$sql = mysql_query("select distinct bulan from periode where tahun='$_POST[id]' and status='open' ");
	while($r = mysql_fetch_array($sql)){
		echo "<option value='$r[bulan]'>$r[bulan]</option>";
	}
}

if($_GET[data]=='reconumber'){
	//cari prefix didepannya
	$sql = mysql_query("select * from master_setup where module_id='$_POST[id]' and divisi_id='$_POST[divisi]' 
	                   and department_id='$_POST[departemen]'");
	$r = mysql_fetch_array($sql);
	print json_encode($r);
}

if($_GET[data]=='del_temp'){
	//delete data yang ada di tabel temp_detail_reco_target
	mysql_query("delete from temp_detail_reco_target where kode_reco='$_POST[id]'");
	mysql_query("delete from temp_detail_reco_item where kode_reco='$_POST[id]'");
	mysql_query("delete from detail_reco_target where kode_reco='$_POST[id]'");
	mysql_query("delete from detail_reco_item where kode_reco='$_POST[id]'");
}

if($_GET[data]=='jenis_biaya'){
	$sql = "select substring(created_by,1,1)as tipe_biaya,substring(created_by,2,1)as typeofcost 
	        from account where account_id='$_POST[account_id]'";
	$qsql = odbc_exec($conn2,$sql);
	$r = odbc_fetch_object($qsql);
	print json_encode($r);
}

if($_GET[data]=='complete'){
	$url = $_POST[url].'approval_reco.php';
	$cek_status_periode = mysql_query("select c.status,a.bulan,a.tahun,c.status from master_budget a,detail_reco_item b, periode c where 
										a.kode_budget = b.kode_budget and a.bulan=c.bulan and a.tahun=c.tahun and 
										b.kode_reco='$_POST[kode_promo]'");
	$rcek_status_periode = mysql_fetch_array($cek_status_periode);
	if($rcek_status_periode[status]=="Close"){
		echo "Kode Reco : $_POST[kode_promo], masuk ke periode bulan $rcek_status_periode[bulan] $rcek_status_periode[tahun], Status : $rcek_status_periode[status]";
	}else{
			$tgl = date('d M Y H:m:s');
			$cek = mysql_query("select complete,tgl_complete from reco_request where kode_promo='$_POST[kode_promo]'");
			$rcek = mysql_fetch_array($cek);
			if(trim($rcek[complete])==""){
				//update dulu ke complete
				$sql = mysql_query("update reco_request set complete='$_POST[user_id]' , tgl_complete='$tgl' where kode_promo='$_POST[kode_promo]'");
				if($sql){
					if(trim($_POST[approve])=='approval'){
							//cari datanya reconya
							$sql2 = mysql_query("select distinct a.*,b.grouppromo_name,c.promotype_name,d.class_name,e.atasan1,e.atasan2,e.email from reco_request a, 
												master_grouppromo b,master_promotype c,master_class d,sec_users e 
												where a.grouppromo_id=b.grouppromo_id and a.promotype_id=c.promotype_id and a.class_id=d.class_id 
												and a.complete=e.user_id and  a.kode_promo='$_POST[kode_promo]'");
							while($r = mysql_fetch_array($sql2)){
								//cari atasan user yang mengcomplete reco tersebut 
								$cari = mysql_query("select * from sec_users where user_id='$r[atasan1]'");
								
								$rcari = mysql_fetch_array($cari);
								
								//kirim email untuk yang memiliki kode budget
								$uid = md5($rcari[user_id]);
								$key = $rcari[password];
								$id = md5($r[kode_promo]);
										
								$body = "<h3><u>Request Reco From : $r[complete]</u></h3>
										<table>
											<tr><td>Kode Reco</td><td>:</td><td>$r[kode_promo]</td></tr>
											<tr><td>Tanggal Reco</td><td>:</td><td>$r[tgl_promo]</td></tr>
											<tr><td>Area</td><td>:</td><td>$r[area_id]/$r[area_name]</td></tr>
											<tr><td>Distributor</td><td>:</td><td>$r[distributor_id]/$r[distributor_name]</td></tr>
											<tr><td>Promo Group</td><td>:</td><td>$r[grouppromo_id]/$r[grouppromo_name]</td></tr>
											<tr><td>Promo Type</td><td>:</td><td>$r[promotype_id]/$r[promotype_name]</td></tr>
											<tr><td>Class</td><td>:</td><td>$r[class_id]/$r[class_name]</td></tr>
											<tr><td>Account ID</td><td>:</td><td>$r[account_id]</td></tr>
											<tr><td>Title/Theme</td><td>:</td><td>$r[class_id]/$r[class_name]</td></tr>
											<tr><td>Periode</td><td>:</td><td>$r[tgl_awal] s/d $r[tgl_akhir]</td></tr>
											<tr><td>Total Sales Target</td><td>:</td><td><b>Rp. ".number_format($r[total_sales_target],2,',','.')."</b></td></tr>
											<tr><td>Background</td><td>:</td><td>$r[background]</td></tr>
											<tr><td>Promo Mechanishm</td><td>:</td><td>$r[promo_mechanisme]</td></tr>
											<tr><td>Claim Mechanishm</td><td>:</td><td>$r[claim_mechanisme]</td></tr>
											<tr><td>Claim trade off</td><td>:</td><td>$r[claimtradeoff]</td></tr>
											<tr><td>Cost of Promo</td><td>:</td><td><b>Rp. ".number_format($r[cost_of_promo],2,',','.')."</b></td></tr>
											<tr><td>Cost Rasio</td><td>:</td><td><b>".number_format($r[cost_rasio],2,',','.')." %</b></td></tr>
											</table><br><h4><a href='$url?act=approval&uid=$uid&key=$key&id=$id&pro_id=$_SESSION[pro_id]'>Approved</a>
											&nbsp;&nbsp<a href='$url?act=reject&uid=$uid&key=$key&id=$id&pro_id=$_SESSION[pro_id]'>Reject</a></h4>";
					 
								
								$from = $rcari[email]; 
								$headers = "From: ".$from."\r\n";
								$headers .= "Reply-to: ".$from."\r\n";
								$headers .= "MIME-Version: 1.0\r\n";
								$headers .= "Content-Type: text/html; charset=UTF-8\r\n"; 
								$subject = "RECO REQUEST : $r[kode_promo]";
										
								$body = base64_encode($body);

								$mail_sent = mysql_query("INSERT INTO email (to_cc, subject, body, header, kode) 
														  VALUES ('$from', '$subject', '$body', '$headers', '$r[kode_promo]')");
								
								if($mail_sent){
									$body = 'KODE RECO  : '.$r[kode_promo].' berhasil dikirim ke '.$rcari[email].'..!';							
									//cari email penerima
									$from = $r[email]; 
									$headers = "From: ".$from."\r\n";
									$headers .= "Reply-to: ".$from."\r\n";
									$headers .= "MIME-Version: 1.0\r\n";
									$headers .= "Content-Type: text/html; charset=UTF-8\r\n"; 
									$subject = "RECO REQUEST : $r[kode_promo]";
									
									$body = base64_encode($body);

									mysql_query("INSERT INTO email (to_cc, subject, body, header, kode) 
												 VALUES ('$from', '$subject', '$body', '$headers', '$r[kode_promo]')");
									
									echo "proses complete / pengiriman permintaan approval berhasil..!";
								}else{
									echo "proses complete / pengiriman permintaan approval gagal..!";
								}
							}
					}else{//jika approvalnya adalah reject 
							$sql = mysql_query("update reco_request set approval1='$_POST[user_id]' , tgl_approval1='$tgl',  
												approval2='$_POST[user_id]' , tgl_approval2='$tgl',status='rejected' where kode_promo='$_POST[kode_promo]'");
							if($sql){
								echo "Kode Reco : $_POST[kode_promo] berhasil di rejected.!";
							}else{
								echo "Kode Reco : $_POST[kode_promo] gagal di rejected.!";
							}
					}
				}else{
					echo "Proses complete / pengiriman permintaan approval gagal..!";
				}
			}else{
				echo "Nomor Reco : $_POST[kode_promo] sudah di complete oleh $rcek[complete] tgl $rcek[tgl_complete]";
			}
	}
}


if($_GET[data]=='simpanreco'){
    //cari tahun dan bulan 
	$tgl = explode('/',$_POST[tgl_promo]);
		
	//mencari nomor reco yang ada 
	$kode_promo = mysql_query("select concat('RC/MKI/','$_POST[divisi_id]','/','$_POST[department_id]','/','$tgl[2]','/','$tg[0]','/',
	                           MAX(reverse(substring(reverse(kode_promo),1,4)))+1) FROM RECO_REQUEST
							   where trim(reverse(substring(reverse(kode_promo),10,20))) 
							   = concat('RC/MKI/','$_POST[divisi_id]','/','$_POST[department_id]')");
	$rkode_promo = mysql_fetch_array($kode_promo);
	
	
	//cek dulu apa sudah ada di database 
	$url = $_POST[url].'approval_reco.php';
	$sql1 = mysql_query("select * from reco_request where kode_promo='$rkode_promo[kode_promo]'");
	$cek = mysql_num_rows($sql1);
	$tgl = date('d M Y H:m:s');
	if($cek>0){
		//update data
		$sql = mysql_query("update reco_request set kode_promo='$rkode_promo[kode_promo]',
														tgl_promo='$_POST[tgl_promo]',
														area_id = '$_POST[area]',
														distributor_id = '$_POST[distributor]',
														grouppromo_id = '$_POST[grouppromo]',
														promotype_id = '$_POST[promotype]',
														class_id = '$_POST[classid]',
														account_id = '$_POST[account]',
														jenis_biaya = '$_POST[jenis_biaya]',
														title = '$_POST[title]',
														tgl_awal = '$_POST[tgl_awal]',
														tgl_akhir = '$_POST[tgl_akhir]',
														total_sales_target = $_POST[total_sales_target],
														background = '$_POST[background]',
														promo_mechanisme = '$_POST[promo_mechanishm]',
														claim_mechanisme = '$_POST[claim_mechanishm]',
														claimtradeoff = '$_POST[claimtradeoff]',
														cost_of_promo = $_POST[costofpromo],
														typeofcost = '$_POST[typeofcost]',
														cost_rasio = '$_POST[costrasio]',
														status = 'pending',
														created_by = '$_POST[user_id]',
														last_update = '$_POST[user_id]'
							where kode_promo = '$_POST[kode_promo]' ");
			if($sql){//jika berhasil disimpan maka 
				//sebelum dimasukan yang dari temporary , hapus dulu ditabel utama
				mysql_query("delete from detail_reco_target where kode_reco='$rkode_promo[kode_promo]'");
				//masukan temp_detail_reco_target ke tabel detail_reco_target
				mysql_query("insert into detail_reco_target select * from temp_detail_reco_target where kode_reco='$rkode_promo[kode_promo]'");
				
				//sebelum dimasukan dari temporary di hapus dulu di tabel detail reco item
				mysql_query("delete from detail_reco_item where kode_reco='$rkode_promo[kode_promo]'");
				//masukan temp_detail_reco_item ke tabel detail_reco_item
				mysql_query("insert into detail_reco_item select * from temp_detail_reco_item where kode_reco='$rkode_promo[kode_promo]'");
                echo "Update Reco : $rkode_promo[kode_promo] berhasil..!$_SESSION[pro_id]";
			}			
	}else{// kalau datanya masih kosong maka di simpan	
	    //cek apakah kode budget sudah dimasukan di tab reco item		
        $dbudget = mysql_query("select * from temp_detail_reco_item where kode_reco='$rkode_promo[kode_promo]'");
        $rbudget = mysql_num_rows($dbudget);
        if($rbudget>0){		
			$sql = mysql_query("insert into reco_request(kode_promo,
														tgl_promo,
														area_id,
														distributor_id,
														grouppromo_id,
														promotype_id,
														class_id,
														account_id,
														jenis_biaya,
														title,
														tgl_awal,
														tgl_akhir,
														total_sales_target,
														background,
														promo_mechanisme,
														claim_mechanisme,
														claimtradeoff,
														cost_of_promo,
														typeofcost,
														cost_rasio,
														status,
														created_by,
														last_update)
												values('$rkode_promo[kode_promo]',
														'$_POST[tgl_promo]',
														'$_POST[area]',
														'$_POST[distributor]',
														'$_POST[grouppromo]',
														'$_POST[promotype]',
														'$_POST[classid]',
														'$_POST[account]',
														'$_POST[jenis_biaya]',
														'$_POST[title]',
														'$_POST[tgl_awal]',
														'$_POST[tgl_akhir]',
														$_POST[total_sales_target],
														'$_POST[background]',
														'$_POST[promo_mechanishm]',
														'$_POST[claim_mechanishm]',
														'$_POST[claimtradeoff]',
														$_POST[costofpromo],
														'$_POST[typeofcost]',
														'$_POST[costrasio]',
														'pending',
														'$_POST[user_id]',
														'$tgl')");
			if($sql){ 
				//update running number ditabel master setup
				mysql_query("update master_setup set number=number+1 where module_id = '$_POST[id]' and department_id='$_POST[department_id]' and divisi_id='$_POST[divisi_id]'"); 

				
				//masukan temp_detail_reco_target ke tabel detail_reco_target
				mysql_query("insert into detail_reco_target select * from temp_detail_reco_target where kode_reco='$rkode_promo[kode_promo]'");
				
				//masukan temp_detail_reco_item ke tabel detail_reco_item
				mysql_query("insert into detail_reco_item select * from temp_detail_reco_item where kode_reco='$rkode_promo[kode_promo]'");
		
				echo "Reco request nomor : $rkode_promo[kode_promo] berhasil ditambahkan..!!"; 
			}else{ 
				echo "Gagal menyimpan Reco request nomor : $rkode_promo[kode_promo]..!";
			}			
		}else{
			//jika kode budget sama dengan 0 atau belum dipilih sama sekali maka tampilkan peringatan kalau harus input budgetnya dulu
			echo "Kode budget belum ditentukan, silahkan masukan kode budget di tab reco item..!";
		}
	}
}

if($_GET[data]=='edit_reco'){
	$sql = mysql_query("select * from reco_request where kode_promo='$_POST[id]'");
	$r = mysql_fetch_array($sql);
	echo json_encode($r);
}

if($_GET[data]=='save_reco_target'){
    //cari apakah sudah ada di tabel temp_detail_reco_target
	$sql = mysql_query("select * from temp_detail_reco_target where kode_reco='$_POST[kode_reco]' 
	                   and groupoutlet_id='$_POST[groupoutlet_id]' and sales_target='$_POST[sales_target]'");
	$r = mysql_num_rows($sql);
	if($r==0){
		mysql_query("insert into temp_detail_reco_target(kode_reco,
		                                                 groupoutlet_id,
														 sales_target)
		                                         values('$_POST[kode_reco]',
												        '$_POST[groupoutlet_id]',
														$_POST[sales_target])");
	}
	$sql = mysql_query("select * from temp_detail_reco_target where kode_reco='$_POST[kode_reco]'");
	while ($r = mysql_fetch_array($sql)){
		echo "<tr class='reco_target'><td>$r[groupoutlet_id]</td><td>".number_format($r[sales_target],2,',','.')."</td>
		      <td><input type='checkbox' id='cekgroup' value='$r[id]'></td></tR>";
	    $sales_target = $sales_target + $r[sales_target];
	}	
	echo "<tr class='reco_target'><td><b>TOTAL ALL</b></td>
	     <td><input type='hidden' id='total_target' value='$sales_target'><b>".number_format($sales_target,2,',','.')."</b></td><td></td></tr>";
}

if($_GET[data]=='del_reco_target'){
	mysql_query("delete from temp_detail_reco_target where id=$_POST[id]");
	
    $sql = mysql_query("select * from temp_detail_reco_target where kode_reco='$_POST[kode_reco]'");
	while ($r = mysql_fetch_array($sql)){
		echo "<tr class='reco_target'><td>$r[groupoutlet_id]</td><td>".number_format($r[sales_target],2,',','.')."</td>
		      <td><input type='checkbox' id='cekgroup' value='$r[id]'></td></tR>";
	    $sales_target = $sales_target + $r[sales_target];
	}	
	echo "<tr class='reco_target'><td><b>TOTAL ALL</b></td>
	     <td><input type='hidden' id='total_target' value='$sales_target'><b>".number_format($sales_target,2,',','.')."</b></td><td></td></tr>";
}

if($_GET[data]=='save_reco_item'){
    //cek apakah datanya sudah ada di temporary
	$sql = mysql_query("select * from temp_detail_reco_item where kode_reco='$_POST[kode_reco]' and divisi_id='$_POST[divisi]' 
	                   and departemen_id='$_POST[departemen]' and subdepartemen_id='$_POST[subdepartemen]' and 
	                   kode_budget='$_POST[kode_budget]' and alokasi='$_POST[alokasi]' and value=$_POST[value]");
	$r = mysql_num_rows($sql);
	if($r==0){
		mysql_query("insert into temp_detail_reco_item(kode_reco,
		                                                 divisi_id,
														 departemen_id,
														 subdepartemen_id,
														 kode_budget,
														 alokasi,
														 value)
		                                         values('$_POST[kode_reco]',
												        '$_POST[divisi]',
														'$_POST[departemen]',
														'$_POST[subdepartemen]',
														'$_POST[kode_budget]',
														'$_POST[alokasi]',
														'$_POST[value]')");
	}												
    $sql = mysql_query("select * from temp_detail_reco_item where kode_reco='$_POST[kode_reco]'");
	while ($r = mysql_fetch_array($sql)){
		echo "<tr class='reco_item'><td>$r[divisi_id]</td><td>$r[departemen_id]</td>
		      <td>$r[subdepartemen_id]</td><td>$r[kode_budget]</td><td>".number_format($r[alokasi],2,',','.')."</td>
			  <td>".number_format($r[value],2,',','.')."</td><td><input type='checkbox' id='cekitem' value='$r[id]'></td></tR>";
		$alokasi = $alokasi + $r[alokasi];
		$value = $value + $r[value];
	}	
	echo "<tr class='reco_item'><td colspan=4><b>TOTAL ALL</b></td>
	      <td><b>".number_format($alokasi,2,',','.')."</b></td>
		  <td><b>".number_format($value,2,',','.')."</b></td><td></td></tR>";
		  
	echo "<input type='text' id= 'total_detail_recoitem' name='total_detail_recoitem' value='$value'>";
}

if($_GET[data]=='del_reco_item'){
	mysql_query("delete from temp_detail_reco_item where id=$_POST[id]");
	
    $sql = mysql_query("select * from temp_detail_reco_item where kode_reco='$_POST[kode_reco]'");
	while ($r = mysql_fetch_array($sql)){
		echo "<tr class='reco_item'><td>$r[divisi_id]</td><td>$r[departemen_id]</td>
		      <td>$r[subdepartemen_id]</td><td>$r[kode_budget]</td><td>".number_format($r[alokasi],2,',','.')."</td>
			  <td>".number_format($r[value],2,',','.')."</td><td><input type='checkbox' id='cekitem' value='$r[id]'></td></tR>";
		$alokasi = $alokasi + $r[alokasi];
		$value = $value + $r[value];
	}	
	echo "<tr class='reco_item'><td colspan=4><b>TOTAL ALL</b></td>
	      <td><b>".number_format($alokasi,2,',','.')."</b></td>
		  <td><b>".number_format($value,2,',','.')."</b></td><td></td></tR>";
}

if($_GET[data]=='budget'){
	$sql = mysql_query("select * from master_budget where divisi_id='$_POST[divisi]' and department_id='$_POST[departemen]' 
						and subdepartemen_id ='$_POST[subdepartemen]'");
	echo "<option value=''>--Kode Budget--</option>";
	while($r = mysql_fetch_array($sql)){
		echo "<option value='$r[kode_budget]'>$r[keterangan]</option>";
	}
}

//create data json from mysql
if($_GET[data]=='json_area'){
	$sth = mysql_query("SELECT a.area_id,a.area_name,b.regional_id,b.regional_name FROM area a,regional b where a.regional_id=b.regional_id");
	$rows = array();
	while($r = mysql_fetch_assoc($sth)) {
		$rows[] = $r;
	}
	print json_encode($rows);
}

if($_GET[data]=='json_distributor'){
	$sth = mysql_query("select a.area_id,b.area_name,a.distributor_id,a.distributor_name from distributor a,area b 
		                    where a.area_id=b.area_id order by a.distributor_name");	
	$rows = array();
	while($r = mysql_fetch_assoc($sth)) {
		$rows[] = $r;
	}
	mysql_free_result($sth);
	print json_encode($rows);
}

if($_GET[data]=='json_grouppromo'){
	$sth = mysql_query("SELECT * from master_grouppromo");
	$rows = array();
	while($r = mysql_fetch_assoc($sth)) {
		$rows[] = $r;
	}
	print json_encode($rows);
}

if($_GET[data]=='json_promotype'){
	$sth = mysql_query("SELECT a.*,b.grouppromo_name FROM master_promotype a,master_grouppromo b 
	                   WHERE a.grouppromo_id=b.grouppromo_id and a.grouppromo_id='$_GET[id]'");
	$rows = array();
	while($r = mysql_fetch_assoc($sth)) {
		$rows[] = $r;
	}
	print json_encode($rows);
}

if($_GET[data]=='json_class'){
    if($_GET[id]==''){
		$sth = mysql_query("SELECT a.*,b.grouppromo_name,c.class_id,c.class_name FROM master_promotype a,master_grouppromo b, 
		                   master_class c WHERE a.grouppromo_id=b.grouppromo_id and a.promotype_id=c.promotype_id ");
	
	}else{
		$sth = mysql_query("SELECT a.*,b.grouppromo_name,c.class_id,c.class_name FROM master_promotype a,master_grouppromo b, 
		                   master_class c WHERE a.grouppromo_id=b.grouppromo_id and a.promotype_id=c.promotype_id and  
						   a.promotype_id='$_GET[id]'");
	}
	$rows = array();
	while($r = mysql_fetch_assoc($sth)) {
		$rows[] = $r;
	}
	print json_encode($rows);
}

if($_GET[data]=='json_coa'){
	$coa = "select account_id,account_name,substring(created_by,1,1)as tipe_biaya,substring(created_by,2,1)as typeofcost 
	       from account  where substring(created_by,1,1) in ('O','P')order by account_id";
	$qcoa = odbc_exec($conn2,$coa);
	$rows = array();
	while($r = odbc_fetch_array($qcoa)) {
		$rows[] = $r;
	}
	print json_encode($rows);
}

if($_GET[data]=='json_groupoutlet'){
	$sth = mysql_query("SELECT * from groupoutlet");
	$rows = array();
	while($r = mysql_fetch_assoc($sth)) {
		$rows[] = $r;
	}
	print json_encode($rows);
}

if($_GET[data]=='json_divisi'){
	$sth = mysql_query("SELECT * from master_divisi");
	$rows = array();
	while($r = mysql_fetch_assoc($sth)) {
		$rows[] = $r;
	}
	print json_encode($rows);
}

if($_GET[data]=='json_department'){
	$sth = mysql_query("SELECT a.*,b.divisi_name FROM master_department a,master_divisi b where a.divisi_id=b.divisi_id and a.divisi_id='$_GET[id]'");
	$rows = array();
	while($r = mysql_fetch_assoc($sth)) {
		$rows[] = $r;
	}
	print json_encode($rows);
}

if($_GET[data]=='json_subdepartemen'){
	$sth = mysql_query("SELECT * from master_subdepartemen where department_id='$_GET[id]'");
	$rows = array();
	while($r = mysql_fetch_assoc($sth)) {
		$rows[] = $r;
	}
	print json_encode($rows);
}

if($_GET[data]=='json_budget'){
	$sth = mysql_query("SELECT * from master_budget a,periode b where a.bulan=b.bulan and a.tahun=b.tahun 
	                   and  a.divisi_id='$_GET[divisi]' and a.department_id='$_GET[departemen]'  
					   and a.subdepartemen_id='$_GET[subdepartemen]' and a.status='Approved' and b.status='Open'");
	$rows = array();
	while($r = mysql_fetch_assoc($sth)) {
		$rows[] = $r;
	}
	print json_encode($rows);
}
//menampilkan daftar reco yang ada 
if($_GET[data]=='json_reco'){
    if($_GET[id]==''){
		$sth = mysql_query("SELECT kode_promo,title from reco_request where status='approved' 
		                   and kode_promo not in(select kode_promo from claim_request)order by kode_promo");
	}else{
		$sth = mysql_query("SELECT * from reco_request a where a.status='approved' and  a.distributor_id='XXXX' 
		                   and a.cost_of_promo>(select sum(claim_approved_ammount) from claim_request where 
						   kode_promo=a.kode_promo and status<>'rejected')");
		
	}
	$rows = array();
	while($r = mysql_fetch_assoc($sth)) {
		$rows[] = $r;
	}
	print json_encode($rows);
}

if($_GET[data]=='json_detailreco'){
    if($_POST[id]==''){
		$sth = mysql_query("SELECT kode_promo,title from reco_request order by kode_promo");
		$rows = array();
		while($r = mysql_fetch_assoc($sth)) {
			$rows[] = $r;
		}
		print json_encode($rows);
	}else{
	    $kode_reco = trim($_POST[id]);
		$sth = mysql_query("SELECT * from reco_request where kode_promo='$kode_reco'");	
		$rows = array();
		while($r = mysql_fetch_assoc($sth)){
			$rows = $r;
		}
		print json_encode($rows);
	}	
}

if($_GET[data]=='json_outstanding'){
	$sql = mysql_query("SELECT ifnull(sum(claim_approved_ammount),0) as total FROM claim_request 
	                   WHERE kode_promo='$_POST[id]' and status<>'rejected'");
	$r = mysql_fetch_array($sql);
	echo $r[total];
}

if($_GET[data]=='json_claimnumbersystem'){
    if($_GET[id]==''){
		$sth = mysql_query("SELECT * from claim_request order by claim_number_system");
	}else{
		$sth = mysql_query("SELECT * from claim_request where kode_promo = '$_GET[id]' ");
	}
	$rows = array();
	while($r = mysql_fetch_assoc($sth)) {
		$rows[] = $r;
	}
	print json_encode($rows);
}

if($_GET[data]=='claim_approval'){
		  $cari = mysql_query("select * from claim_request where claim_number_system='$_POST[id]'");
		  $rcari = mysql_fetch_array($cari);
		  
		  //cari jika statusnya tidak pending tampilkan pesan sudah di approve atau reject
		  if($rcari[status]<>'pending'){
				  echo "Claim number system : ".$_POST[id]." Sudah di ".$rcari[status]." Oleh : ".$rcari[approve_by]." Tgl : ".$rcari[tgl_approve];
		  }else{
				  $tgl = date('d-m-Y H:m:s');
				  $tgl2 = date('Y-m-d',strtotime($rcari[claim_date]));
				  //$tgl_journal = date('Y-m-d H:m:s',strtotime($rcari[claim_date]));
				  $tgl_journal = date('Y-m-d',strtotime($rcari[claim_date]));
				  
				  $sql = mysql_query("UPDATE claim_request set status ='$_POST[status]',
														approve_by ='$_POST[user_id]',
														tgl_approve ='$tgl'
											where claim_number_system = '$_POST[id]'");
						
				  //membuat nomor ap_journal
				  $no = "select 
							case len(isnull(max(substring(journal_id,9,4)),0)+1)
								when 1 then '000'
								when 2 then '00'
								when 3 then '0'
							end as prefix,
							isnull(max(substring(journal_id,9,4)),0)+1 as number
						from ap_journal where journal_date='$tgl2'";
						
				  $qno = odbc_exec($conn2,$no);
				  $rno = odbc_fetch_array($qno);
				  $journal_id = date('Ymd',strtotime($rcari[claim_date])).$rno[prefix].$rno[number]; //nomor journal_id  ditabel ap_journal
				  
				  if($sql){					
						if($_POST[status]=='approved'){		  
							  //cari dulu claim request yang ada 
							  $cari = mysql_query("select a.*,b.ap_account_type,b.ap_account_id from claim_request a, vendor b 
							                       where a.vendor_id=b.vendor_id and a.claim_number_system = '$_POST[id]'");
							  $rcari = mysql_fetch_array($cari);
							  $claim_number = strrev(substr(strrev($rcari[claim_number_system]),0,12));
							  $reco_number = strrev(substr(strrev($rcari[kode_promo]),0,12));
							  $ppn = $rcari[ppn]*$rcari[claim_approved_ammount]/100;
							  $account = "select account_id,account_type from account where account_id='$rcari[coa]'";
							  $qaccount = odbc_exec($conn2,$account);
							  $raccount = odbc_fetch_array($qaccount);

							  $update = "INSERT INTO [kinosentraacc].[dbo].[ap_journal]([user_id]
																				   ,[last_update]
																				   ,[created_by]   
																				   ,[company]
																				   ,[branch]
																				   ,[journal_id]
																				   ,[journal_date]
																				   ,[description]
																				   ,[vendor_id]
																				   ,[po_id]
																				   ,[po_rev]
																				   ,[debet]
																				   ,[credit]
																				   ,[due_date]
																				   ,[paid]
																				   ,[paid_date]
																				   ,[posted]
																				   ,[ok]
																				   ,[account_type]
																				   ,[account_id]
																				   ,[check_no]
																				   ,[check_date]
																				   ,[c_symbol]
																				   ,[ppn_no]
																				   ,[ppn]
																				   ,[vat_date]
																				   ,[vinvoice_id]
																				   ,[vinvoice_date]
																				   ,[ap_account_type]
																				   ,[ap_account_id]
																				   ,[as_account_type]
																				   ,[as_account_id]
																				   ,[vat_account_type]
																				   ,[vat_account_id]
																				   ,[transaction_id]
																				   ,[rec_id])
																			 VALUES
																				   ('$_POST[user_id]'
																				   ,'$tgl_journal'
																				   ,'$_POST[user_id]'
																				   ,'PT. MORINAGA KINO INDONESIA'
																				   ,'JAKARTA'
																				   ,'$journal_id'
																				   ,'$tgl_journal'
																				   ,'$rcari[claim_number_system]-$rcari[kode_promo]'
																				   ,'$rcari[vendor_id]'
																				   ,'$claim_number'
																				   ,''
																				   ,0
																				   ,'$rcari[total_claim_approved_ammount]'
																				   ,'$tgl_journal'
																				   ,0
																				   ,'$tgl_journal'
																				   ,0
																				   ,0
																				   ,''
																				   ,''
																				   ,''
																				   ,'$tgl_journal'
																				   ,'IDR'
																				   ,'$rcari[nomor_faktur_pajak]'
																				   ,$rcari[ppn]
																				   ,'$tgl_journal'
																				   ,''
																				   ,'$tgl_journal'
																				   ,'$rcari[ap_account_type]'
																				   ,'$rcari[ap_account_id]'
																				   ,'$raccount[account_type]'
																				   ,'$raccount[account_id]'
																				   ,''
																				   ,''
																				   ,''
																				   ,'$reco_number')";
																				   
							  $ap_journal = odbc_exec($conn2,$update); //insert ke sql server
							  if($ap_journal){ 
									echo "Update Claim Number System : $_POST[id] berhasil di approved,Insert ke Purwadika Ap Journal  Nomor : $journal_id berhasil..!"; 
									//jika berhasil update ke kinosentraacc maka update nomor ap_journal di tabel claim update
									mysql_query("UPDATE claim_request set journal_id ='$journal_id'
												where claim_number_system = '$_POST[id]'");
							  }else{ 
									echo "Update Claim Number System : $_POST[id] berhasil di approved,Insert ke Purwadika Ap Journal Nomor : $journal_id gagal..!"; 
									mysql_query("UPDATE claim_request set status ='pending'
											     where claim_number_system = '$_POST[id]'");
							  }
							  
							  
						}else{
							echo "Claim Number System : $_POST[id] telah di rejected..!";
						}
				 }else{
						echo "claim Number System : $_POST[id] gagal di approved..!";
				 }
		}
}

if($_GET[data]=='tanggal'){
   $startdate = strtotime($_POST[id]);
   $enddate = strtotime('-30 day',strtotime(date('m/d/Y')));
   if($startdate < $enddate){
        echo "no";
   }else{
		echo "yes";
   }   
}

if($_GET[data]=='tgl_budget'){
   $sql = mysql_query("SELECT concat( substring( a.tahun, 3, 2 ) , right( concat( '0', b.month_id ) , 2 ) ) AS tgl_budget
					FROM master_budget a,MONTH b WHERE a.bulan = b.month_name AND a.kode_budget = '$_POST[id]'");
	$r = mysql_fetch_array($sql);
	echo $r[tgl_budget];
}


if($_GET[data]=='outstanding'){
	$budget = mysql_query("select value from master_budget where kode_budget='$_POST[id]'");
	$rbudget = mysql_fetch_array($budget);

	$rows = array();	
	$sql = mysql_query("select $rbudget[value]-ifnull(sum(a.value),0)as outstanding_cost, 
	                    ifnull(sum(a.value),0) as cost from detail_reco_item a,reco_request b 
						where a.kode_reco=b.kode_promo and a.kode_budget='$_POST[id]' and b.status<>'rejected'"); 
	while($r = mysql_fetch_assoc($sql)) {
		$rows = $r;
	}
	print json_encode($rows);
}
?>