<?php
session_start();
include "../../../configuration/connection_inc.php";
include "../../../function/security.php";
include "../../../function/get_sql.php";

if ($_GET[data]=='tabel'){
	//cari yang approval limit
	$limit = $db->query("select * from approval_limit");
	$r_limit = $limit->fetch(PDO::FETCH_OBJ);
	if($r_limit->user_id == $_SESSION[user_id]){
		if($_POST[kode_reco]==''){
			$sql = $db->query("select * from reco_request where year(DATE_FORMAT(STR_TO_DATE(tgl_promo, '%m/%d/%Y'),'%Y-%m-%d'))='$_POST[tahun]' and
							  month(DATE_FORMAT(STR_TO_DATE(tgl_promo, '%m/%d/%Y'),'%Y-%m-%d'))='$_POST[bulan]'
							  and status='$_POST[status]' and cost_of_promo >= $r_limit->nominal order by kode_promo,tgl_promo");
		}else{
			$sql = $db->query("select * from reco_request where year(DATE_FORMAT(STR_TO_DATE(tgl_promo, '%m/%d/%Y'),'%Y-%m-%d'))='$_POST[tahun]' and
							  month(DATE_FORMAT(STR_TO_DATE(tgl_promo, '%m/%d/%Y'),'%Y-%m-%d'))='$_POST[bulan]'
							  and status='$_POST[status]' and cost_of_promo >= $r_limit->nominal 
							  and kode_promo like '%$_POST[kode_reco]%' order by kode_promo,tgl_promo");							  
		}
	}else{	
		switch ($_SESSION[grade_id]){
			case "*" : 
							if($_POST[kode_reco]==''){
									$sql = $db->query("select * from reco_request where year(DATE_FORMAT(STR_TO_DATE(tgl_promo, '%m/%d/%Y'),'%Y-%m-%d'))='$_POST[tahun]' and
													  month(DATE_FORMAT(STR_TO_DATE(tgl_promo, '%m/%d/%Y'),'%Y-%m-%d'))='$_POST[bulan]'
													  and status='$_POST[status]' order by kode_promo,tgl_promo");
							}else{
									$sql = $db->query("select * from reco_request where kode_promo like '%$_POST[kode_reco]%' and 
													  year(DATE_FORMAT(STR_TO_DATE(tgl_promo, '%m/%d/%Y'),'%Y-%m-%d'))='$_POST[tahun]' and
													  month(DATE_FORMAT(STR_TO_DATE(tgl_promo, '%m/%d/%Y'),'%Y-%m-%d'))='$_POST[bulan]'
													  and status='$_POST[status]' and kode_promo like '%$_POST[kode_reco]%' order by kode_promo,tgl_promo");
							}
						break;
			case "**" : 
							if($_POST[kode_reco]==''){
									$sql = $db->query("select a.* from reco_request a,detail_reco_item b where a.kode_promo=b.kode_reco
													  and b.divisi_id='$_SESSION[divisi_id]' and 
													  year(DATE_FORMAT(STR_TO_DATE(tgl_promo, '%m/%d/%Y'),'%Y-%m-%d'))='$_POST[tahun]' and
													  month(DATE_FORMAT(STR_TO_DATE(tgl_promo, '%m/%d/%Y'),'%Y-%m-%d'))='$_POST[bulan]' 
													  and a.status='$_POST[status]' order by a.kode_promo,tgl_promo");
							}else{
									$sql = $db->query("select a.* from reco_request a,detail_reco_item b where a.kode_promo=b.kode_reco
													  and b.divisi_id='$_SESSION[divisi_id]'  and a.kode_promo like '%$_POST[kode_reco]%' and 
													  year(DATE_FORMAT(STR_TO_DATE(tgl_promo, '%m/%d/%Y'),'%Y-%m-%d'))='$_POST[tahun]' and
													  month(DATE_FORMAT(STR_TO_DATE(tgl_promo, '%m/%d/%Y'),'%Y-%m-%d'))='$_POST[bulan]'
													  and a.status='$_POST[status]' and a.kode_promo like '%$_POST[kode_reco]%' order by a.kode_promo,tgl_promo");
							}
						break;
			default :   
							if($_POST[kode_reco]==''){
									$sql = $db->query("select a.* from reco_request a,detail_reco_item b where a.kode_promo=b.kode_reco
													  and b.divisi_id='$_POST[divisi_id]' and b.departemen_id='$_POST[department_id]' 
													  and a.status='$_POST[status]'  and 
													  year(DATE_FORMAT(STR_TO_DATE(a.tgl_promo, '%m/%d/%Y'),'%Y-%m-%d'))='$_POST[tahun]' and
													  month(DATE_FORMAT(STR_TO_DATE(a.tgl_promo, '%m/%d/%Y'),'%Y-%m-%d'))='$_POST[bulan]' 
													  order by a.kode_promo,a.tgl_promo desc ");
							}else{
									$sql = $db->query("select a.* from reco_request a,detail_reco_item b where a.kode_promo=b.kode_reco
													  and b.divisi_id='$_POST[divisi_id]' and b.departemen_id='$_POST[department_id]' 
													  and a.status='$_POST[status]' and a.kode_promo like '%$_POST[kode_reco]%' and 
													  year(DATE_FORMAT(STR_TO_DATE(tgl_promo, '%m/%d/%Y'),'%Y-%m-%d'))='$_POST[tahun]' and
													  month(DATE_FORMAT(STR_TO_DATE(tgl_promo, '%m/%d/%Y'),'%Y-%m-%d'))='$_POST[bulan]' 
													  and kode_promo like '%$_POST[kode_reco]%' order by a.kode_promo,a.tgl_promo desc");
							}
						break;
		}
	}
	
	echo "<table class='table table-condensed table-hover table-bordered'>
	        <tR>
	            <td>No</td><tD>No Reco</td><td>Tgl Reco</td><td>Title</td><td>Total Sales Target</td>
	    	    <td>Cost Of Promo</td><td>Jenis</td><td>Complete</td><td>Approval 1</td><td>Approval 2</td><td>Status</td>				
			</tr>";
			
	$no =1 ;
	$no = $no +$start;
	$cek = $sql->rowCount();
	if($cek==0){
		echo "<tR><td colspan='12'>Tidak ada data ditemukan..!</td></tr>";
	}else{
		while($r = $sql->fetch(PDO::FETCH_OBJ)){
		    //cari atasan terkait
			$cari = $db->query("select user_id,atasan1,atasan2 from sec_users where user_id in
								(select atasan1 from sec_users where user_id='$r->created_by')");
			$data = $cari->fetch(PDO::FETCH_OBJ);
			
			if(strtoupper($r->status)=='PENDING'){ $warna ="label label-warning"; }
			elseif(strtoupper($r->status)=='APPROVED'){ $warna ="label label-success"; }
			else{ $warna ="label label-important"; }
			
			if($r->complete<>''){ 
				$complete = "label label-success";
				$ucomplete = $r->complete;
			}else{
				$complete = "label label-warning";
				$ucomplete = $data->user_id;
			}
			
			if($r->approval1<>''){ 
				$approval1 = "label label-success";
				$uapproval1 = $r->approval1;
			}else{
				$approval1 = "label label-warning";
				$uapproval1 = $data->atasan1;
			}
			
			if($r->approval2<>''){ 
				$approval2 = "label label-success";
				$uapproval2 = $r->approval2;
			}else{
				$approval2 = "label label-warning";
				$uapproval2 = $data->atasan2;
			}
			
						
			echo "<tr>
					<td>$no $r->user_id</td>
					<tD>$r->kode_promo</td>
					<tD>$r->tgl_promo</td>
					<td>$r->title</td>
					<tD>".number_format($r->total_sales_target,2,',','.')."</td>
					<td>".number_format($r->cost_of_promo,2,',','.')."</td>
					<td>$r->jenis_biaya</td>
					<td><span class=\"$complete\">$ucomplete<br>$r->tgl_complete</span></td>
					<td><span class=\"$approval1\">$uapproval1<br>$r->tgl_approval1</span></td>
					<td><span class=\"$approval2\">$uapproval2<br>$r->tgl_approval2</span></td>
					<tD><span class=\"$warna\">$r->status</span></td>
			     </tr>";
			$no++;
		}
	}
	echo "</table></div>";
}


if ($_GET[data]=='all'){
	if(($_POST[grade_id]=='*')||($_POST[grade_id]=='***')){
		$tampil = mysql_query("select * from reco_request where year(DATE_FORMAT(STR_TO_DATE(tgl_promo, '%m/%d/%Y'),'%Y-%m-%d'))='$_POST[tahun]' and
							  month(DATE_FORMAT(STR_TO_DATE(tgl_promo, '%m/%d/%Y'),'%Y-%m-%d'))='$_POST[bulan]'
							  and status='$_POST[status]' order by kode_promo,tgl_promo");
	}else{
		if($_POST[grade_id]=='**'){
			$tampil = mysql_query("select a.* from reco_request a,detail_reco_item b where a.kode_promo=b.kode_reco
	   							  and b.divisi_id='$_SESSION[divisi_id]' and 
								  year(DATE_FORMAT(STR_TO_DATE(tgl_promo, '%m/%d/%Y'),'%Y-%m-%d'))='$_POST[tahun]' and
								  month(DATE_FORMAT(STR_TO_DATE(tgl_promo, '%m/%d/%Y'),'%Y-%m-%d'))='$_POST[bulan]' 
								  and a.status='$_POST[status]' order by a.kode_promo,tgl_promo ");
		}else{
			$tampil = mysql_query("select a.* from reco_request a,detail_reco_item b where a.kode_promo=b.kode_reco
	   							  and b.divisi_id='$_POST[divisi_id]' and b.departemen_id='$_POST[department_id]' 
								  and a.status='$_POST[status]'  and 
								  year(DATE_FORMAT(STR_TO_DATE(a.tgl_promo, '%m/%d/%Y'),'%Y-%m-%d'))='$_POST[tahun]' and
								  month(DATE_FORMAT(STR_TO_DATE(a.tgl_promo, '%m/%d/%Y'),'%Y-%m-%d'))='$_POST[bulan]' 
								  order by a.kode_promo,a.tgl_promo ");
		}
	}
	
	echo "<table class='table table-condensed table-hover table-bordered'>
	        <tR>
	            <td>No</td><tD>No Reco</td><td>Tgl Reco</td><td>Title</td><td>Total Sales Target</td>
	    	    <td>Cost Of Promo</td><td>Jenis</td><td>Complete</td><td>Approval 1</td><td>Approval 2</td><td>Status</td>				
			</tr>";
			
	$no =1 ;
	$no = $no +$start;
	$cek = mysql_num_rows($tampil);
	if($cek==0){
		echo "<tR><td colspan='12'>Tidak ada data ditemukan..!</td></tr>";
	}else{
		while($r = mysql_fetch_array($tampil)){
		    //jika complete atau approval1 atau approval2 kosong maka kasih tanda warna merah
			if((trim($r[complete])=='')||(trim($r[approval1])=='')||(trim($r[approval2])=='')){
				$color = 'red';
			}else{
				$color ='black';
			}
								
			//untuk kategori link, jika sudah di approve atau reject maka tidak ditampilkan viewnya
			if($r[status]<>'pending'){ $link='Ok';}else{
				$link = "<a href='?r=promoapproval&act=editpromoapproval&id=$r[kode_promo]' target='_blank'>View</a>";
			}
								
			//cari pending approval
			if(trim($r[complete])==''){
				//jika complete kosong maka cari atasan yang mebuat reco
				$atasan = mysql_query("select b.atasan1 from reco_request a,sec_users b where a.created_by=b.user_id and kode_promo='$r[kode_promo]'");
				$ratasan = mysql_fetch_array($atasan);
				$approve = $ratasan[atasan1];
			}else{
			    //cari atasan user yang mengcomplete 
				$atasan = mysql_query("select b.atasan1,b.atasan2 from reco_request a,sec_users b where a.complete=b.user_id 
				                       and kode_promo='$r[kode_promo]'");
				$ratasan = mysql_fetch_array($atasan);
				//cek apakah operasional atau promosi
				if(trim($r[approval1])==''){
				    $color = 'orange';
					$approve = $ratasan[atasan1];
				}else if(trim($r[approval2])==''){
				    $color = 'orange';
					$approve = $ratasan[atasan2];
				}else{
				    if(trim($r[status])=='rejected'){
						$color = 'red';
					}else{
						$color = 'green';
					}
					$approve = $r[status];
				}
			}
								
			echo "<tr>
					<td>$no</td>
					<tD>$r[kode_promo]</td>
					<tD>$r[tgl_promo]</td>
					<td>$r[title]</td>
					<tD>".number_format($r[total_sales_target],2,',','.')."</td>
					<td>".number_format($r[cost_of_promo],2,',','.')."</td>
					<td>$r[jenis_biaya]</td>
					<td>$r[complete]</td>
					<td>$r[approval1]</td>
					<td>$r[approval2]</td>
					<tD><font color='$color'>$approve</font></td>
			     </tr>";
			$no++;
		}
	}
	echo "</table></div>";
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
			$tgl = date('d-m-Y H:m:s');
			$cek = mysql_query("select complete,tgl_complete from reco_request where kode_promo='$_POST[kode_promo]'");
			$rcek = mysql_fetch_array($cek);
			if(trim($rcek[complete])==""){
				//cek apakah masuk dalam approval limit
				$limit  = $db->query("select * from approval_limit");
				$r_limit = $limit->fetch(PDO::FETCH_OBJ);
				if($r_limit->user_id == $_SESSION[user_id]){
					$sql = mysql_query("update reco_request set complete='$_POST[user_id]' , tgl_complete='$tgl',
										approval1='$_POST[user_id]', tgl_approval1 = '$tgl',
										approval2 ='$_POST[user_id]',tgl_approval2 = '$tgl' , status ='approved' where kode_promo='$_POST[kode_promo]'");
					if($sql){
						echo "proses complete / pengiriman permintaan approval berhasil..!";
					}else{
						echo "proses complete / pengiriman permintaan approval gagal..!";
					}
				}else{
					//update dulu ke complete
					$sql = mysql_query("update reco_request set complete='$_POST[user_id]' , tgl_complete='$tgl' where kode_promo='$_POST[kode_promo]'");
					if($sql){
						if(trim($_POST[approve])=='approval'){
									//cari datanya reconya
									$sql2 = mysql_query("select distinct a.*,b.grouppromo_name,c.promotype_name,d.class_name,e.atasan1,e.atasan2,e.email from reco_request a, 
														master_grouppromo b,master_promotype c,master_class d,sec_users e 
														where a.grouppromo_id=b.grouppromo_id and a.promotype_id=c.promotype_id and a.class_id=d.class_id 
														and a.complete=e.user_id and  a.kode_promo='$_POST[kode_promo]'");
									
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
				}
			}else{
				echo "Nomor Reco : $_POST[kode_promo] sudah di complete oleh $rcek[complete] tgl $rcek[tgl_complete]";
			}
	}
}

?>
