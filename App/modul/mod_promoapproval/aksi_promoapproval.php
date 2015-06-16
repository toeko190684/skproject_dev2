<?php
session_start();
include "../../../configuration/connection_inc.php";
include "../../../function/security.php";

$module=$_GET[r];
$act=$_GET[act];

if ($module=='promoapproval' AND $act=='update'){
	$tgl = date('d/m/Y H:m:s');
	$url = $_SESSION[url]."approval_reco.php";
	//jika mode pengirimannya adalah di approve
	if($_POST[approve]==1){
			//cari reco_budget
			$sql = mysql_query("select a.*,b.atasan1,b.atasan2,b.email from reco_request a,sec_users b where a.complete=b.user_id and  kode_promo='$_POST[id]'");
			$r = mysql_fetch_array($sql);

			if($r[approval1]==''){			
				//cek lagi apakah user yang login sesuai dengan session maka update data untuk approval1 dan tgl approval1
				if($r[atasan1]==$_SESSION[user_id]){
					$update1 = mysql_query("update reco_request set approval1='$_SESSION[user_id]' , tgl_approval1='$tgl' where kode_promo='$r[kode_promo]'");					
					//jika update data berhasil cek apakah atasan dari user yang mengcomplete sama dengan atasan1
					if($update1){
					    if(trim($r[jenis_biaya])=='P'){
								if($r[atasan2]==$r[atasan1]){
									$update = mysql_query("update reco_request set approval2='$r[atasan2]' , tgl_approval2='$tgl' where kode_promo='$r[kode_promo]'");
									//jika berhasil di update tampilakn pesan
									if($update){	
										mysql_query("update reco_request set status='approved' where kode_promo='$r[kode_promo]'");
										echo "<blockquote>Kode Reco : <b>$r[kode_promo]</b> berhasil di approved oleh <b>$r[atasan1]</b> tgl : <b>$tgl</b>..!</blockquote>";
									}else{
										echo "<blockquote>Kode Reco : <b>$r[kode_promo]</b> gagal di approved oleh <b>$r[atasan1]</b> tgl : <b>$tgl</b>..!</blockquote>";
									}
								}else{
									//jika atasan2 berbeda dengan atasan1 maka dikirimkan email notifikasi, cari email atasan 2
									$atasan = mysql_query("select * from sec_users where user_id='$r[atasan2]'");				
									$ratasan = mysql_fetch_array($atasan);	
									//kirim email untuk yang memiliki kode budget
									$uid = md5($ratasan[user_id]);
									$key = $ratasan[password];
									$id = md5($r[kode_promo]);
									
									$reco = mysql_query("select a.*,b.grouppromo_name,c.promotype_name,d.class_name,e.atasan1,e.atasan2 from reco_request a, 
														master_grouppromo b,master_promotype c,master_class d,sec_users e 
														where a.grouppromo_id=b.grouppromo_id and a.promotype_id=c.promotype_id and a.class_id=d.class_id 
														and a.complete=e.user_id and  a.kode_promo='$r[kode_promo]'");
									$xreco = mysql_fetch_array($reco);
									$body = "<h3><u>Request Reco From : $xreco[complete]</u></h3>
											<table>
												<tr><td>Kode Reco</td><td>:</td><td>$xreco[kode_promo]</td></tr>
												<tr><td>Tanggal Reco</td><td>:</td><td>$xreco[tgl_promo]</td></tr>
												<tr><td>Area</td><td>:</td><td>$xreco[area_id]/$xreco[area_name]</td></tr>
												<tr><td>Distributor</td><td>:</td><td>$xreco[distributor_id]/$xreco[distributor_name]</td></tr>
												<tr><td>Promo Group</td><td>:</td><td>$xreco[grouppromo_id]/$xreco[grouppromo_name]</td></tr>
												<tr><td>Promo Type</td><td>:</td><td>$xreco[promotype_id]/$xreco[promotype_name]</td></tr>
												<tr><td>Class</td><td>:</td><td>$xreco[class_id]/$xreco[class_name]</td></tr>
												<tr><td>Title/Theme</td><td>:</td><td>$xreco[class_id]/$xreco[class_name]</td></tr>
												<tr><td>Periode</td><td>:</td><td>$xreco[tgl_awal] s/d $xreco[tgl_akhir]</td></tr>
												<tr><td>Total Sales Target</td><td>:</td><td><b>Rp. ".number_format($xreco[total_sales_target],2,',','.')."</b></td></tr>
												<tr><td>Background</td><td>:</td><td>$xreco[background]</td></tr>
												<tr><td>Promo Mechanishm</td><td>:</td><td>$xreco[promo_mechanisme]</td></tr>
												<tr><td>Claim Mechanishm</td><td>:</td><td>$xreco[claim_mechanisme]</td></tr>
												<tr><td>Claim trade off</td><td>:</td><td>$xreco[claimtradeoff]</td></tr>
												<tr><td>Cost of Promo</td><td>:</td><td><b>Rp. ".number_format($xreco[cost_of_promo],2,',','.')."</b></td></tr>
												<tr><td>Cost Rasio</td><td>:</td><td><b>".number_format($xreco[cost_rasio],2,',','.')." %</b></td></tr>
											</table><br><h4><a href='$url?act=approval&uid=$uid&key=$key&id=$id'>Approved</a>
											&nbsp;&nbsp<a href='$url?act=reject&uid=$uid&key=$key&id=$id'>Reject</a></h4>";
									
									$from = $ratasan[email]; 
									$headers = "From:  no-reply\r\n";
									$headers .= "Reply-to: ".$from."\r\n";
									$headers .= "MIME-Version: 1.0\r\n";
									$headers .= "Content-Type: text/html; charset=UTF-8\r\n"; 
									$subject = "Request Reco";
									    
									//kirim email 
									$mail_sent = @mail($from, $subject, $body, $headers);	
									echo "<blockquote>Kode Reco : <b>$r[kode_promo]</b> berhasil di approved oleh <b>$r[atasan1]</b> tgl : <b>$tgl</b>..!</blockquote>";
								}
						}else{//jika jenis biaya tidak sama dengan P atau promosi maka berikanketerangan sudah di approve, dan langsung di update approval2 dengan atasan1
							$update = mysql_query("update reco_request set approval2='$r[atasan1]' , tgl_approval2='$tgl' where kode_promo='$r[kode_promo]'");
							//jika berhasil di update tampilkan pesan
							if($update){	
							    mysql_query("update reco_request set status='approved' where kode_promo='$r[kode_promo]'");
								//kirim email untuk yang memiliki kode budget
								$reco = mysql_query("select a.*,b.grouppromo_name,c.promotype_name,d.class_name,e.atasan1,e.atasan2 from reco_request a, 
													master_grouppromo b,master_promotype c,master_class d,sec_users e 
													where a.grouppromo_id=b.grouppromo_id and a.promotype_id=c.promotype_id and a.class_id=d.class_id 
													and a.complete=e.user_id and  a.kode_promo='$r[kode_promo]'");
								$xreco = mysql_fetch_array($reco);
								$body = "<h3><u>Request Reco : $xreco[kode_promo] sudah diapprove oleh $r[atasan2] tgl $tgl_approval2</u></h3>
										<table>
											<tr><td>Kode Reco</td><td>:</td><td>$xreco[kode_promo]</td></tr>
											<tr><td>Tanggal Reco</td><td>:</td><td>$xreco[tgl_promo]</td></tr>
											<tr><td>Area</td><td>:</td><td>$xreco[area_id]/$xreco[area_name]</td></tr>
											<tr><td>Distributor</td><td>:</td><td>$xreco[distributor_id]/$xreco[distributor_name]</td></tr>
											<tr><td>Promo Group</td><td>:</td><td>$xreco[grouppromo_id]/$xreco[grouppromo_name]</td></tr>
											<tr><td>Promo Type</td><td>:</td><td>$xreco[promotype_id]/$xreco[promotype_name]</td></tr>
											<tr><td>Class</td><td>:</td><td>$xreco[class_id]/$xreco[class_name]</td></tr>
											<tr><td>Title/Theme</td><td>:</td><td>$xreco[class_id]/$xreco[class_name]</td></tr>
											<tr><td>Periode</td><td>:</td><td>$xreco[tgl_awal] s/d $xreco[tgl_akhir]</td></tr>
											<tr><td>Total Sales Target</td><td>:</td><td><b>Rp. ".number_format($xreco[total_sales_target],2,',','.')."</b></td></tr>
											<tr><td>Background</td><td>:</td><td>$xreco[background]</td></tr>
											<tr><td>Promo Mechanishm</td><td>:</td><td>$xreco[promo_mechanisme]</td></tr>
											<tr><td>Claim Mechanishm</td><td>:</td><td>$xreco[claim_mechanisme]</td></tr>
											<tr><td>Claim trade off</td><td>:</td><td>$xreco[claimtradeoff]</td></tr>
											<tr><td>Cost of Promo</td><td>:</td><td><b>Rp. ".number_format($xreco[cost_of_promo],2,',','.')."</b></td></tr>
											<tr><td>Cost Rasio</td><td>:</td><td><b>".number_format($xreco[cost_rasio],2,',','.')." %</b></td></tr>
										</table>";
											
								$from = $r[email]; 
								$headers = "From:  no-reply\r\n";
								$headers .= "Reply-to: ".$from."\r\n";
								$headers .= "MIME-Version: 1.0\r\n";
								$headers .= "Content-Type: text/html; charset=UTF-8\r\n"; 
								$subject = "Request Reco";
												    
								//kirim email 
								$mail_sent = @mail($from, $subject, $body, $headers);
								echo "<blockquote>Kode Reco : <b>$r[kode_promo]</b> berhasil di approved oleh <b>$r[atasan1]</b> tgl : <b>$tgl</b>..!</blockquote>";
							}else{
								echo "<blockquote>Kode Reco : <b>$r[kode_promo]</b> gagal di approved oleh <b>$r[atasan1]</b> tgl : <b>$tgl</b>..!</blockquote>";
							}
						}
					}else{//jika update data gagal
						echo "<blockquote>Kode Reco : <b>$r[kode_promo]</b> gagal di approved oleh <b>$r[atasan1]</b> tgl : <b>$tgl</b>..!</blockquote>";
					}
				}else{
					echo "<blockquote>Anda tidak berhak untuk approved kode reco : $r[kode_reco]</blockquote>";
				}
			}else{//jika approval1 tidak kosong atau sudah terisi maka cek approval 2 apakah sama dengan session usernya 
				if($r[approval2]==''){
					if($r[atasan2]==$_SESSION[user_id]){//jika atasan2 sama dengan sessiion user yang login maka diupdate approval2 dan tgl approval 2nya 
						$update = mysql_query("update reco_request set approval2='$r[atasan2]' , tgl_approval2='$tgl' where kode_promo='$r[kode_promo]'");
						if($update){	
							mysql_query("update reco_request set status='approved' where kode_promo='$r[kode_promo]'");
							//kirim email untuk yang memiliki kode budget
							$reco = mysql_query("select a.*,b.grouppromo_name,c.promotype_name,d.class_name,e.atasan1,e.atasan2 from reco_request a, 
												master_grouppromo b,master_promotype c,master_class d,sec_users e 
												where a.grouppromo_id=b.grouppromo_id and a.promotype_id=c.promotype_id and a.class_id=d.class_id 
												and a.complete=e.user_id and  a.kode_promo='$r[kode_promo]'");
							$xreco = mysql_fetch_array($reco);
							$body = "<h3><u>Request Reco : $xreco[kode_promo] sudah diapprove oleh $r[atasan2] tgl $tgl_approval2</u></h3>
									<table>
										<tr><td>Kode Reco</td><td>:</td><td>$xreco[kode_promo]</td></tr>
										<tr><td>Tanggal Reco</td><td>:</td><td>$xreco[tgl_promo]</td></tr>
										<tr><td>Area</td><td>:</td><td>$xreco[area_id]/$xreco[area_name]</td></tr>
										<tr><td>Distributor</td><td>:</td><td>$xreco[distributor_id]/$xreco[distributor_name]</td></tr>
										<tr><td>Promo Group</td><td>:</td><td>$xreco[grouppromo_id]/$xreco[grouppromo_name]</td></tr>
										<tr><td>Promo Type</td><td>:</td><td>$xreco[promotype_id]/$xreco[promotype_name]</td></tr>
										<tr><td>Class</td><td>:</td><td>$xreco[class_id]/$xreco[class_name]</td></tr>
										<tr><td>Title/Theme</td><td>:</td><td>$xreco[class_id]/$xreco[class_name]</td></tr>
										<tr><td>Periode</td><td>:</td><td>$xreco[tgl_awal] s/d $xreco[tgl_akhir]</td></tr>
										<tr><td>Total Sales Target</td><td>:</td><td><b>Rp. ".number_format($xreco[total_sales_target],2,',','.')."</b></td></tr>
										<tr><td>Background</td><td>:</td><td>$xreco[background]</td></tr>
										<tr><td>Promo Mechanishm</td><td>:</td><td>$xreco[promo_mechanisme]</td></tr>
										<tr><td>Claim Mechanishm</td><td>:</td><td>$xreco[claim_mechanisme]</td></tr>
										<tr><td>Claim trade off</td><td>:</td><td>$xreco[claimtradeoff]</td></tr>
										<tr><td>Cost of Promo</td><td>:</td><td><b>Rp. ".number_format($xreco[cost_of_promo],2,',','.')."</b></td></tr>
										<tr><td>Cost Rasio</td><td>:</td><td><b>".number_format($xreco[cost_rasio],2,',','.')." %</b></td></tr>
									</table>";
										
							$from = $r[email]; 
							$headers = "From:  no-reply\r\n";
							$headers .= "Reply-to: ".$from."\r\n";
							$headers .= "MIME-Version: 1.0\r\n";
							$headers .= "Content-Type: text/html; charset=UTF-8\r\n"; 
							$subject = "Request Reco";
											    
							//kirim email 
							$mail_sent = @mail($from, $subject, $body, $headers);
							echo "<blockquote>Kode Reco : <b>$r[kode_promo]</b> berhasil di approved oleh <b>$r[atasan2]</b> tgl : <b>$tgl</b>..!</blockquote>";
						}else{//jika update approval 2 gagal maka tampilkan pesan 
							echo "<blockquote>Kode Reco : <b>$r[kode_promo]</b> gagal di approved oleh <b>$r[atasan2]</b> tgl : <b>$tgl</b>..!</blockquote>";
						}
					}else{//jika atasan2 tidak sesuai dengan session user yang login
						echo "<blockquote>Anda tidak berhak untuk approved kode reco : $r[kode_reco]</blockquote>";
					}
				}else{//jika approval2 sudah di approve maka tampilkan kalau sudah di approve
					echo "<blockquote>Kode Reco : <b>$r[kode_promo]</b> tersebut sudah diapproved oleh <b>$r[atasan2]</b> tgl <b>$tgl</b>..!</blockquote>";
				}
			}
    }else{
		
    }		
}
?>
