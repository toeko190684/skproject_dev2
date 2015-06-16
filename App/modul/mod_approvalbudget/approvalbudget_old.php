<?php
$aksi="modul/mod_approvalbudget/aksi_approvalbudget.php";
ses_module();
switch($_GET[act]){
  // Tampil master_chart of account
  default:
  $access = read_security();
  if($access=="allow"){
	    echo "<ul class='nav nav-tabs' id='myTab'>
				  <li class='active'><a href='#budget_approval' data-toggle='tab'>Approved Budget</a></li>
			  </ul>
			  <div class='tab-content'>
				  <div class='tab-pane active' id='budget_approval'>";		
						//master budget yang sudah di approve
						$per_page = 10;
						if($_POST[key]==""){
						    if(($_SESSION[grade_id]=='*')||($_SESSION[grade_id]=='***')){
								$page_query = mysql_query("SELECT count(*) FROM master_budget ");
							}else{
							    if($_SESSION[grade_id]=='**'){
									$page_query = mysql_query("SELECT count(*) FROM master_budget where divisi_id='$_SESSION[divisi_id]'");
								}else{
									$page_query = mysql_query("SELECT count(*) FROM master_budget where 
									                           divisi_id='$_SESSION[divisi_id]' and department_id='$_SESSION[department_id]'");								
								}
							}
						}else{
							if(($_SESSION[grade_id]=='*')||($_SESSION[grade_id]=='***')){
								$page_query = mysql_query("SELECT count(*) FROM master_budget where kode_budget='$_POST[key]' ");
							}else{
							    if($_SESSION[grade_id]=='**'){
									$page_query = mysql_query("SELECT count(*) FROM master_budget where kode_budget='$_POST[key]' and divisi_id='$_SESSION[divisi_id]'");
								}else{
									$page_query = mysql_query("SELECT count(*) FROM master_budget where 
									                           kode_budget='$_POST[key]' and divisi_id='$_SESSION[divisi_id]' 
															   and department_id='$_SESSION[department_id]'");								
								}
							}
						}
						$pages = ceil(mysql_result($page_query,0)/$per_page);
						$page = (isset($_GET[page]))? (int)$_GET[page]:1;
						$start = ($page-1)*$per_page;
						echo "<form class='form-search' method=post action='?r=approvalbudget'>	
									<div class='input-append'>
									    <div class='span8'></div>
										<input class='span3 search-query' id='key' name='key' type='text' placeholder='Masukan Kode Budget..!'>
									    <button class='btn' type='submit'>Cari</button>
									</div><br>
							  </form>
							  <table class='table table-condensed table-hover table-bordered' >
					          <tdead>
								<tr class='success'>
									<td>No</td><td>Kode Budget</td><td>Periode</td><td>Keterangan</td><td>Value</td><td>Outstanding</td><td>Cost</td><td>User</td><td>Tgl Approved</td>
									<td>Approved By</td><td>Status</td><td>Aksi</td>
								</tr>
							  </tdead>";
					    if($_POST[key]==""){
						    if(($_SESSION[grade_id]=='*')||($_SESSION[grade_id]=='***')){
								$tampil=mysql_query("select * from master_budget a,master_divisi b,master_department c,
								                     master_subdepartemen d where a.divisi_id=b.divisi_id and a.department_id=c.department_id 
													 and a.subdepartemen_id=d.subdepartemen_id and b.divisi_id=c.divisi_id and c.department_id=d.department_id
													 limit $start,$per_page");
							}else{
							    if($_SESSION[grade_id]=='**'){
									$tampil=mysql_query("select * from master_budget a,master_divisi b,master_department c,
									                     master_subdepartemen d where a.divisi_id=b.divisi_id and a.department_id=c.department_id 
														 and a.subdepartemen_id=d.subdepartemen_id and b.divisi_id=c.divisi_id and 
														 c.department_id=d.department_id and  a.divisi_id='$_SESSION[divisi_id]' limit $start,$per_page");								
								}else{
								    $tampil=mysql_query("select * from master_budget a,master_divisi b,master_department c,
									                     master_subdepartemen d where a.divisi_id=b.divisi_id and a.department_id=c.department_id 
														 and a.subdepartemen_id=d.subdepartemen_id and b.divisi_id=c.divisi_id and 
														 c.department_id=d.department_id and  a.divisi_id='$_SESSION[divisi_id]' 
														 and a.department_id='$_SESSION[department_id]' limit $start,$per_page");
								}
							}
						}else{
						    if(($_SESSION[grade_id]=='*')||($_SESSION[grade_id]=='***')){
								$tampil=mysql_query("select * from master_budget a,master_divisi b,master_department c,
								                     master_subdepartemen d where a.divisi_id=b.divisi_id and a.department_id=c.department_id 
													 and a.subdepartemen_id=d.subdepartemen_id and b.divisi_id=c.divisi_id and 
													 c.department_id=d.department_id and a.kode_budget='$_POST[key]' limit $start,$per_page");		
							}else{
								if($_SESSION[grade_id]=='**'){
								        $tampil=mysql_query("select * from master_budget a,master_divisi b,master_department c,
										                     master_subdepartemen d where a.divisi_id=b.divisi_id and a.department_id=c.department_id 
															 and a.subdepartemen_id=d.subdepartemen_id and b.divisi_id=c.divisi_id and 
														     c.department_id=d.department_id and a.kode_budget='$_POST[key]' and 
															 a.divisi_id='$_SESSION[divisi_id]' limit $start,$per_page");
								}else{
								        $tampil=mysql_query("select * from master_budget a,master_divisi b,master_department c,
										                     master_subdepartemen d where a.divisi_id=b.divisi_id and a.department_id=c.department_id 
															 and a.subdepartemen_id=d.subdepartemen_id and b.divisi_id=c.divisi_id and 
														     c.department_id=d.department_id and a.kode_budget='$_POST[key]' and
															 a.divisi_id='$_SESSION[divisi_id]' and a.department_id='$_SESSION[department_id]' limit $start,$per_page ");								
								}
							}
						}
						$no =1 ;
					    $no = $no +$start;
						$cek = mysql_num_rows($tampil);
						if($cek>0){
								while ($r=mysql_fetch_array($tampil)){
								  if(trim($r[status])=='Rejected'){$color = 'red';}
								  else if(trim($r[status])=='Approved'){ $color = 'green';}
								  else{$color='orange';}
							      //menghitung outstranding cost dan cost master budget
								  $cost =mysql_query("select b.value-sum(a.value)as outstanding_cost,sum(a.value)as cost from detail_reco_item a,
								                   master_budget b,reco_request c where a.kode_reco=c.kode_promo and  a.kode_budget=b.kode_budget 
												   and a.kode_budget='$r[kode_budget]' and c.status<>'rejected' and c.complete<>''");
								  $rcost = mysql_fetch_array($cost);
								  if(trim($r[status])=='Pending'){ 
									$link = "<a href='index.php?r=approvalbudget&act=editapprovalbudget&id=$r[kode_budget]' target='_blank'>View</a>";
								  }else{
									$link = 'Ok';
								  }
								  echo "<tr style='text-color:$color'>
											<td>$no</td>
											<td>$r[kode_budget]</td>
											<td>$r[bulan] $r[tahun]</td>
											<td>$r[keterangan]</td>
											<td>".number_format($r[value],0,'.','.')."</td>
											<td>".number_format($rcost[outstanding_cost],0,'.','.')."</td>
											<td>".number_format($rcost[cost],0,'.','.')."</td>
											<td>$r[user]</td>
											<td>$r[tgl_approval1]</td>
											<td>$r[approval1]</td>
											<td><font color=$color>$r[status]</font></td>
											<td>$link</td>
										</tr>";
										$no++;
							    }
						}else{
							echo "<tr><td colspan='7'>Tidak ada data ditemukan</td></tr>";
						}
					    echo "</table>";
						//memulai paginasi
						echo "<div class='pagination pagination-small pagination-right'><ul><li><a href='?r=approvalbudget&page=1'>First</a></li>";
						if($pages >= 1 && $page <= $pages){
						    for($x=1; $x<=$pages; $x++){
						        echo ($x == $page) ? '<li><a href="?r=approvalbudget&page='.$x.'">'.$x.'</a></li> ' : '
								                     <li><a href="?r=approvalbudget&page='.$x.'">'.$x.'</a></li>';
						    }
						}
						$x--;
						echo "<li><a href='?r=approvalbudget&page=$x'>Last</a></li></ul></div>
				</div>
			</div>";
	}else{
		msg_security();
	}
    break;

 case "editapprovalbudget":
	$access = read_security();
	if($access=="allow"){
	    $edit = mysql_query("select * from master_budget a,master_divisi b,master_department c,
							master_subdepartemen d where a.divisi_id=b.divisi_id and a.department_id=c.department_id 
							and a.subdepartemen_id=d.subdepartemen_id and a.kode_budget='$_GET[id]'");
	    $r    = mysql_fetch_array($edit);
		if($r[status]==''){
			    echo "<form method=POST action=$aksi?r=approvalbudget&act=update>
			          <input type=hidden name=id value='$r[kode_budget]'>
			          <fieldset><legend>Detail Request Budget</legend>
					  <div class='span5'>
					  <table>
						  <tr><td>Kode Budget</td><td>&nbsp:&nbsp&nbsp&nbsp</td><td>$r[kode_budget]</td></tr>
						  <tr><td>Keterangan</td><td>&nbsp:&nbsp</td><td>$r[keterangan]</td></tr>
						  <tr><td>Divisi</td><td>&nbsp:&nbsp</td><td>$r[divisi_name]</td></tr>
						  <tr><td>Departemen</td><td>&nbsp:&nbsp</td><td>$r[department_name]</td></tr>
						  <tr><td>Sub Departemen</td><td>&nbsp:&nbsp</td><td>$r[subdepartemen_name]</td></tr>
						  <tr><td>Periode</td><td>&nbsp:&nbsp</td><td>$r[bulan] $r[tahun]</td></tr>
						  <tr><td>Tanggal Input</td><td>&nbsp:&nbsp</td><td>$r[tgl_input]</td></tr>
						  <tr><td>Value</td><td>&nbsp:&nbsp</td><td><b>".number_format($r[value],2,',','.')."</b></td></tr>
						  <tr><td>Request By</td><td>&nbsp:&nbsp</td><td>$r[user]</td></tr>
						  <tr><td colspan=3></td></tr>
						  <tr><td>Approved</td><td>:</td><td><label>";
						  if(trim($r[status])=='Approved'){
						        echo "<input type='checkbox' name='approve' value='1' checked>";
						  }else{
						        echo "<input type='checkbox' name='approve' value='1'>";
						  }
				echo "</label></td></tr>
					  </table><br><Br>";
				$akses = update_security();
			    if($akses=="allow"){
				      if(trim($r[status])==''){
							echo "<input type='submit' class='btn btn-primary' value='Simpan'>";
					  }else{
							echo "<input type='submit' class='btn btn-primary' value='Simpan' disabled>";
					  }
				}else{
					  echo "<input type='submit' class='btn btn-primary' value='Simpan' disabled>";		
				}
				echo "&nbsp<input type='button' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
					  </div></fieldset></form>";
		}else{
			echo "<blockquote>Kode budget $r[kode_budget] sudah di $r[status] oleh $r[approval1] tanggal $r[tgl_approval1]...!</blockquote>";
		}
	}else{
		msg_security();
	}
    break; 
	
	case "approval":
		$access = update_security();
		if($access=="allow"){
				$cari = mysql_query("select distinct a.*,b.divisi_name,c.department_name,d.subdepartemen_name 
				                    from master_budget a,master_divisi b,master_department c, master_subdepartemen d 
				                    where a.divisi_id=b.divisi_id and a.department_id=c.department_id and a.subdepartemen_id=d.subdepartemen_id and 
									md5(a.kode_budget)='$_GET[kb]'");
				$rcari = mysql_fetch_array($cari);
				if($rcari[status]=='Pending'){
					$tgl = date('d/m/Y H:m:s');
					mysql_query("update master_budget set status='Approved',
					                                      approval1 = '$_SESSION[user_id]',
														  tgl_approval1 = '$tgl'
												where kode_budget='$rcari[kode_budget]'");
					echo "<blockquote>Kode Budget : <b>$rcari[kode_budget]<b> ,Berhasil Di <b>Approved</b>..!</blockquote>";
					
					//cari userid yang ada di 
					$user  = mysql_query("select user_id,email,password from sec_users where user_id in
			                            (SELECT user FROM master_budget  WHERE kode_budget='$rcari[kode_budget]')");
					while($ruser = mysql_fetch_array($user)){
					        $uid = md5($ruser[user_id]);
							$key = $ruser[password];
							$kb = md5($_POST[kode_budget]);
							
							$body = "<p>Request Budget berikut,Sudah di Approve oleh <b>$_SESSION[user_id]</b> pada tanggal : <b>$tgl</b></p>
									<table>
										<tr><td>Kode Budget</td><td>:</td><td>$rcari[kode_budget]</td></tr>
										<tr><td>Tanggal Budget</td><td>:</td><td>$rcari[tgl_input]</td></tr>
										<tr><td>Divisi</td><td>:</td><td>$rcari[divisi_id] \ $rcari[divisi_name]</td></tr>
										<tr><td>Departemen</td><td>:</td><td>$rcari[department_id] \ $rcari[department_name]</td></tr>
										<tr><td>Sub Departemen</td><td>:</td><td>$rcari[subdepartemen_id] \ $rcari[subdepartemen_name]</td></tr>
										<tr><td>Bulan</td><td>:</td><td>$rcari[bulan]</td></tr>
										<tr><td>Tahun</td><td>:</td><td>$rcari[tahun]</td></tr>
										<tr><td>Value</td><td>:</td><td><b>Rp. ".number_format($rcari[value],2,',','.')."</b></td></tr>
									</table>";

							$from = $ruser[email]; 
							$headers = "From: ".$from."\r\n";
							$headers .= "Reply-to: ".$from."\r\n";
							$headers .= "MIME-Version: 1.0\r\n";
							$headers .= "Content-Type: text/html; charset=UTF-8\r\n"; 
							$subject = "Approved Budget";
						    
							//kirim email 
							$mail_sent = @mail($from, $subject, $body, $headers);	
					}
				}else{
					echo "<blockquote>Kode budget tersebut sudah di $rcari[status] oleh $rcari[approval1] tanggal $rcari[tgl_approval1]..!</blockquote>";
				}
		}else{
			msg_security();
		}
	break;
	
	case "reject":
		$access = update_security();
		if($access =="allow"){
				$cari = mysql_query("select distinct a.*,b.divisi_name,c.department_name,d.subdepartemen_name 
				                    from master_budget a,master_divisi b,master_department c, master_subdepartemen d 
				                    where a.divisi_id=b.divisi_id and a.department_id=c.department_id and a.subdepartemen_id=d.subdepartemen_id and 
									md5(a.kode_budget)='$_GET[kb]'");
				$rcari = mysql_fetch_array($cari);
				if($rcari[status]=='Pending'){
					$tgl = date('d/m/Y H:m:s');
					mysql_query("update master_budget set status='Rejected',
					                                      approval1 = '$_SESSION[user_id]',
														  tgl_approval1 = '$tgl'
												where kode_budget='$rcari[kode_budget]'");
					echo "<blockquote>Kode Budget : <b>$rcari[kode_budget]</b> ,Berhasil Di <b>Rejected</b>..!</blockquote>";
					
					//cari userid yang ada di 
					$user  = mysql_query("select user_id,email,password from sec_users where user_id in
			                            (SELECT user FROM master_budget  WHERE kode_budget='$rcari[kode_budget]')");
					while($ruser = mysql_fetch_array($user)){
					        $uid = md5($ruser[user_id]);
							$key = $ruser[password];
							$kb = md5($_POST[kode_budget]);
							
							$body = "<p>Request Budget berikut,Sudah di Rejected oleh <b>$_SESSION[user_id]</b> pada tanggal : <b>$tgl</b></p>
									<table>
										<tr><td>Kode Budget</td><td>:</td><td>$rcari[kode_budget]</td></tr>
										<tr><td>Tanggal Budget</td><td>:</td><td>$rcari[tgl_input]</td></tr>
										<tr><td>Divisi</td><td>:</td><td>$rcari[divisi_id] \ $rcari[divisi_name]</td></tr>
										<tr><td>Departemen</td><td>:</td><td>$rcari[department_id] \ $rcari[department_name]</td></tr>
										<tr><td>Sub Departemen</td><td>:</td><td>$rcari[subdepartemen_id] \ $rcari[subdepartemen_name]</td></tr>
										<tr><td>Bulan</td><td>:</td><td>$rcari[bulan]</td></tr>
										<tr><td>Tahun</td><td>:</td><td>$rcari[tahun]</td></tr>
										<tr><td>Value</td><td>:</td><td><b>Rp. ".number_format($rcari[value],2,',','.')."</b></td></tr>
									</table>";
						    
							$from = $ruser[email]; 
							$headers = "From: ".$from."\r\n";
							$headers .= "Reply-to: ".$from."\r\n";
							$headers .= "MIME-Version: 1.0\r\n";
							$headers .= "Content-Type: text/html; charset=UTF-8\r\n"; 
							$subject = "Rejected Budget";

							//kirim email 
							$mail_sent = @mail($from, $subject, $body, $headers);	
					}
				}else{
					echo "<blockquote>Kode budget tersebut sudah di $rcari[status] oleh $rcari[approval1] tanggal $rcari[tgl_approval1]..!</blockquote>";
				}
		}else{
			msg_security();
		}
	break;
}
?>
