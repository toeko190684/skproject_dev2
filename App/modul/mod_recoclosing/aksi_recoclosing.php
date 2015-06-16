<?php
session_start();
include "../../../configuration/connection_inc.php";
include "../../../function/security.php";
include "../../../function/email.php";

$module=$_GET[r];
$act=$_GET[act];
$url = $_SESSION[url].'approval_cek.php';

// Hapus divisi
if ($module=='masterbudget' AND $act=='hapus'){
  $access = delete_security();
  if($access=="allow"){
		mysql_query("DELETE FROM master_budget WHERE kode_budget ='$_GET[id]' and upper(status)='PENDING'");
  }
  header('location:../../index.php?r='.$module.'&mod='.$_SESSION[module]);
}

// Input divisi
elseif ($module=='masterbudget' AND $act=='input'){
  //cek dulu apakah sudah ada didatabase atau belum
    $cek = mysql_query("select * from master_budget where kode_budget='$_POST[kode_budget]'");
	$no = mysql_num_rows($cek);
	if($no==0){
			mysql_query("INSERT INTO master_budget(kode_budget,
		                                         keterangan,
												 divisi_id,
												 department_id,
												 subdepartemen_id,
												 bulan,
												 tahun,
												 tgl_input,
												 status,
												 value,
												 user) 
			                       VALUES('$_POST[kode_budget]',
								          '$_POST[keterangan]',
										  '$_POST[divisi_id]',
										  '$_POST[departemen_id]',
										  '$_POST[subdepartemen_id]',
										  '$_POST[bulan]',
										  '$_POST[tahun]',
										  '$_POST[tgl_input]',
										  'pending',
										  '$_POST[value]',
										  '$_SESSION[user_id]')");
										  
			
			//cari penambahan budget yang baru saja di buat
			$sql = mysql_query("select * from v_master_budget where kode_budget='$_POST[kode_budget]'");
			$r = mysql_fetch_array($sql);
			
			//cari userid yang ada di 
			$user  = mysql_query("select user_id,email,password from sec_users where user_id in
			                     (SELECT b.atasan1 FROM `master_budget` a,sec_users b WHERE a.user=b.user_id and a.kode_budget='$r[kode_budget]')
								 and user_id in(select distinct user_id from sec_user_rules where module_id=15 and c=1 and r=1 and u=1 and d=1)");
			while($ruser = mysql_fetch_array($user)){
			        $uid = md5($ruser[user_id]);
					$key = $ruser[password];
					$kb = md5($_POST[kode_budget]);
					
					$body = "<h3><u>Request Budget From : $r[user_id]</u></h3>
							<table>
								<tr><td>Kode Budget</td><td>:</td><td>$r[kode_budget]</td></tr>
								<tr><td>Tanggal Budget</td><td>:</td><td>$r[tgl_input]</td></tr>
								<tr><td>Divisi</td><td>:</td><td>$r[divisi_name]</td></tr>
								<tr><td>Departemen</td><td>:</td><td>$r[department_name]</td></tr>
								<tr><td>Sub Departemen</td><td>:</td><td>$r[subdepartemen_name]</td></tr>
								<tr><td>Bulan</td><td>:</td><td>$r[bulan]</td></tr>
								<tr><td>Tahun</td><td>:</td><td>$r[tahun]</td></tr>
								<tr><td>Value</td><td>:</td><td><b>Rp. ".number_format($r[value],2,',','.')."</b></td></tr>
							</table><br><h4><a href='$url?act=approval&uid=$uid&key=$key&kb=$kb&pro_id=$_SESSION[pro_id]'>Approved</a>
							&nbsp;&nbsp<a href='$url?act=reject&uid=$uid&key=$key&kb=$kb&pro_id=$_SESSION[pro_id]'>Reject</a></h4>";
				    
					$from = $ruser[email]; 
					$headers = "From: ".$from."\r\n";
					$headers .= "Reply-to: ".$from."\r\n";
					$headers .= "MIME-Version: 1.0\r\n";
					$headers .= "Content-Type: text/html; charset=UTF-8\r\n"; 
					$subject = "BUDGET REQUEST NO : $r[kode_budget]";
					$body = base64_encode($body);

					mysql_query("INSERT INTO email (to_cc, subject, body, header, kode) 
					             VALUES ('$from', '$subject', '$body', '$headers', '$r[kode_budget]')");
			}
	}
	header('location:../../index.php?r='.$module);
}

// Update divisi
elseif ($module=='masterbudget' AND $act=='update'){
			mysql_query("UPDATE master_budget SET keterangan = '$_POST[keterangan]',
												divisi_id = '$_POST[divisi_id]',
												department_id = '$_POST[departemen_id]',
												subdepartemen_id = '$_POST[subdepartemen_id]',
												bulan = '$_POST[bulan]',
												tahun = '$_POST[tahun]',
												tgl_input = '$_POST[tgl_input]',
												value = '$_POST[value]',
												user = '$_SESSION[user_id]'
								  WHERE kode_budget   = '$_POST[id]'");
			//cari penambahan budget yang baru saja di buat
					$sql = mysql_query("select * from v_master_budget where kode_budget='$_POST[id]'");
					$r = mysql_fetch_array($sql);
					
					//cari userid yang ada di 
					$user  = mysql_query("select user_id,email,password from sec_users where user_id in
										 (SELECT b.atasan1 FROM master_budget a,sec_users b WHERE a.user=b.user_id and a.kode_budget='$r[kode_budget]')
										 and user_id in(select distinct user_id from sec_user_rules where module_id=15 and c=1 and r=1 and u=1 and d=1)");
					while($ruser = mysql_fetch_array($user)){
							$uid = md5($ruser[user_id]);
							$key = $ruser[password];
							$kb = md5($_POST[kode_budget]);
							
							$body = "<h3><u>Request Budget From : $r[user_id]</u></h3>
									<table>
										<tr><td>Kode Budget</td><td>:</td><td>$r[kode_budget]</td></tr>
										<tr><td>Tanggal Budget</td><td>:</td><td>$r[tgl_input]</td></tr>
										<tr><td>Divisi</td><td>:</td><td>$r[divisi_name]</td></tr>
										<tr><td>Departemen</td><td>:</td><td>$r[department_name]</td></tr>
										<tr><td>Sub Departemen</td><td>:</td><td>$r[subdepartemen_name]</td></tr>
										<tr><td>Bulan</td><td>:</td><td>$r[bulan]</td></tr>
										<tr><td>Tahun</td><td>:</td><td>$r[tahun]</td></tr>
										<tr><td>Value</td><td>:</td><td><b>Rp. ".number_format($r[value],2,',','.')."</b></td></tr>
									</table><br><h4><a href='$url?act=approval&uid=$uid&key=$key&kb=$kb'>Approved</a>
									&nbsp;&nbsp<a href='$url?act=reject&uid=$uid&key=$key&kb=$kb'>Reject</a></h4>";
							
							$from = $ruser[email]; 
							$headers = "From: ".$from."\r\n";
							$headers .= "Reply-to: ".$from."\r\n";
							$headers .= "MIME-Version: 1.0\r\n";
							$headers .= "Content-Type: text/html; charset=UTF-8\r\n"; 
							$subject = "BUDGET REQUEST NO : $r[kode_budget]";
							
							$body = base64_encode($body);

							mysql_query("INSERT INTO email (to_cc, subject, body, header, kode) 
										 VALUES ('$from', '$subject', '$body', '$headers', '$r[kode_budget]')");
					}			
	header('location:../../index.php?r='.$module);
}
?>
