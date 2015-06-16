<?php
include "../configuration/connection_inc.php";
$url = 'http://192.168.21.4/skproject_new/app/approval_reco.php';
	$tgl = date('d M Y H:m:s');
					$sql2 = mysql_query("select distinct a.*,b.grouppromo_name,c.promotype_name,d.class_name,e.atasan1,e.atasan2 from reco_request a, 
					                    master_grouppromo b,master_promotype c,master_class d,sec_users e 
										where a.grouppromo_id=b.grouppromo_id and a.promotype_id=c.promotype_id and a.class_id=d.class_id 
										and a.created_by=e.user_id and a.status='Pending' and approval1=''");
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
						$subject = "Request Reco";
							    
						//kirim email 
						$mail_sent = @mail($from, $subject, $body, $headers);	
						if($mail_sent){
							echo "pengiriman email reco $r[kode_promo] ke $rcari[email] berhasil..!<br>";
						}else{
							echo "pengiriman email reco $r[kode_promo] ke $rcari[email] gagal..!<br>";
						}
					}
?>