<?php
session_start();
include "../../../configuration/connection_inc.php";
include "../../../function/security.php";

$module=$_GET[r];
$act=$_GET[act];

// Hapus divisi
if ($module=='approvalbudget' AND $act=='hapus'){
  $access = delete_security();
  if($access=="allow"){
		mysql_query("DELETE FROM master_approvalbudget WHERE coa_id ='$_GET[id]'");
  }
  header('location:../../index.php?r='.$module.'&mod='.$_SESSION[module]);
}

// Update divisi
elseif ($module=='approvalbudget' AND $act=='update'){
  if($_POST[approve]=="1"){ $approved = "Approved"; }else{ $approved = "Rejected";}
  $tgl = date('d/m/Y H:m:s');
  mysql_query("update master_budget set   status = '$approved',
										   tgl_approval1 = '$tgl',
										   approval1 = '$_SESSION[user_id]'
							    where kode_budget = '$_POST[id]'");
	//cari data lengkap request budget
	$sql = mysql_query("select a.*,b.divisi_name,c.department_name,d.subdepartemen_name 
				        from master_budget a,master_divisi b,master_department c, master_subdepartemen d 
				        where a.divisi_id=b.divisi_id and a.department_id=c.department_id and a.subdepartemen_id=d.subdepartemen_id and 
						a.kode_budget='$_POST[id]'");
	$r = mysql_fetch_array($sql);
	
	//cari user kemudian kirimkan lewat email
	$user  = mysql_query("select user_id,email,password from sec_users where user_id in
			             (SELECT b.atasan1 FROM `master_budget` a,sec_users b WHERE a.user=b.user_id and a.kode_budget='$r[kode_budget]')
						 and user_id in(select distinct user_id from sec_user_rules where module_id=15 and c=1 and r=1 and u=1 and d=1)");
	while($ruser = mysql_fetch_array($user)){
     	$body = "<p>Request Budget berikut,Sudah di $approved oleh <b>$_SESSION[user_id]</b> pada tanggal : <b>$tgl</b></p>
				<table>
					<tr><td>Kode Budget</td><td>:</td><td>$r[kode_budget]</td></tr>
					<tr><td>Tanggal Budget</td><td>:</td><td>$r[tgl_input]</td></tr>
					<tr><td>Divisi</td><td>:</td><td>$r[divisi_id] \ $r[divisi_name]</td></tr>
					<tr><td>Departemen</td><td>:</td><td>$r[department_id] \ $r[department_name]</td></tr>
					<tr><td>Sub Departemen</td><td>:</td><td>$r[subdepartemen_id] \ $r[subdepartemen_name]</td></tr>
					<tr><td>Bulan</td><td>:</td><td>$r[bulan]</td></tr>
					<tr><td>Tahun</td><td>:</td><td>$r[tahun]</td></tr>
				<tr><td>Value</td><td>:</td><td><b>Rp. ".number_format($r[value],2,',','.')."</b></td></tr>
				</table>";
						    
		$from = $ruser[email]; 
		$headers = "From: ".$from."\r\n";
		$headers .= "Reply-to: ".$from."\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=UTF-8\r\n"; 
		$subject = "Approved Budget";
						    
		//kirim email 
		$mail_sent = @mail($from, $subject, $body, $headers);	
		header('location:../../index.php?r='.$module.'&mod='.$_SESSION[module]);
	}
  header('location:../../index.php?r='.$module);
}
?>
