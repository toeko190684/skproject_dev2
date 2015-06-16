<script>
$(document).ready(function(){	
	$('#example').dataTable({
		"scrollX" : true
	});
		
	$('#example tbody').on('click', 'tr',function(){
		$(this).toggleClass('selected');		
	});
});
</script>

<?php
$aksi="modul/mod_approvalbudget/aksi_approvalbudget.php";
ses_module();
switch($_GET[act]){
  // Tampil master_chart of account
  default:
  $access = read_security();
  if($access=="allow"){
		echo "	<h2></h2>
				<table id='example' class='table table-striped table-bordered table-hover' >
		          <thead>
					<tr>
						<th>Kode Budget</th><th>Periode</th><th>Keterangan</th><th>Value</th><th>Outstanding</th>
						<th>Cost</th><th>User</th><th>Tgl Approved</th>
						<th>Approved By</th><th>Status</th><th>Aksi</th>
					</tr>
				  </thead></tbody>";

	    if(($_SESSION[grade_id]=='*')||($_SESSION[grade_id]=='***')){
				$tampil=mysql_query("select * from master_budget a,master_divisi b,master_department c,
				                     master_subdepartemen d where a.divisi_id=b.divisi_id and a.department_id=c.department_id 
									 and a.subdepartemen_id=d.subdepartemen_id and b.divisi_id=c.divisi_id and c.department_id=d.department_id
									 and a.status='pending'");
		}else{
			    if($_SESSION[grade_id]=='**'){
						$tampil=mysql_query("select * from master_budget a,master_divisi b,master_department c,
											master_subdepartemen d where a.divisi_id=b.divisi_id and a.department_id=c.department_id 
											and a.subdepartemen_id=d.subdepartemen_id and b.divisi_id=c.divisi_id and 
											c.department_id=d.department_id and  a.divisi_id='$_SESSION[divisi_id]' and a.status='pending'");								
				}else{
					    $tampil=mysql_query("select * from master_budget a,master_divisi b,master_department c,
						                     master_subdepartemen d where a.divisi_id=b.divisi_id and a.department_id=c.department_id 
											 and a.subdepartemen_id=d.subdepartemen_id and b.divisi_id=c.divisi_id and 
											 c.department_id=d.department_id and  a.divisi_id='$_SESSION[divisi_id]' 
											 and a.department_id='$_SESSION[department_id]' and a.status='pending'");
				}
		}


		while ($r=mysql_fetch_array($tampil)){
				if(strtoupper(trim($r[status]))=='REJECTED'){$color = 'red';}
				else if(strtoupper(trim($r[status]))=='APPROVED'){ $color = 'green';}
				else{$color='orange';}
				
				//menghitung outstranding cost dan cost master budget
				$cost =mysql_query("select b.value-sum(a.value)as outstanding_cost,sum(a.value)as cost from detail_reco_item a,
				                   master_budget b,reco_request c where a.kode_reco=c.kode_promo and  a.kode_budget=b.kode_budget 
								   and a.kode_budget='$r[kode_budget]' and c.status<>'rejected' and c.complete<>''
								   and a.kode_reco not in(select kode_promo from claim_request where status='rejected')");
				$rcost = mysql_fetch_array($cost);
				if(strtoupper(trim($r[status]))=='PENDING'){ 
					$link = "<a href='index.php?r=approvalbudget&act=editapprovalbudget&id=$r[kode_budget]' >View</a>";
				}else{
					$link = "<a href='index.php?r=approvalbudget&act=editapprovalbudget&id=$r[kode_budget]' >View</a>";
				}
				
				echo "<tr style='text-color:$color'>
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
	    }
	    echo "</tbody></table>";
	}else{
		msg_security();
	}
    break;

 case "editapprovalbudget":
	$access = update_security();
	if($access=="allow"){
	    $edit = mysql_query("select * from master_budget a,master_divisi b,master_department c,
							master_subdepartemen d where a.divisi_id=b.divisi_id and a.department_id=c.department_id 
							and a.subdepartemen_id=d.subdepartemen_id and a.kode_budget='$_GET[id]'");
	    $r    = mysql_fetch_array($edit);
		if(strtoupper(trim($r[status]))=='PENDING'){
			    echo "<div class='span5'><form method=POST action=$aksi?r=approvalbudget&act=update>
			          <input type=hidden name=id value='$r[kode_budget]'>
			          <fieldset><legend>Detail Request Budget</legend>
					  
					  <table class=\"table table-striped table-hover\">
						  <tr><td>Kode Budget</td><td><strong>$r[kode_budget]</strong></td></tr>
						  <tr><td>Keterangan</td><td><strong>$r[keterangan]</strong></td></tr>
						  <tr><td>Divisi</td><td><strong>$r[divisi_name]</strong></td></tr>
						  <tr><td>Departemen</td><td><strong>$r[department_name]</strong></td></tr>
						  <tr><td>Sub Departemen</td><td><strong>$r[subdepartemen_name]</strong></td></tr>
						  <tr><td>Periode</td><td><strong>$r[bulan] $r[tahun]</strong></td></tr>
						  <tr><td>Tanggal Input</td><td><strong>$r[tgl_input]</strong></td></tr>
						  <tr><td>Value</td><td><strong>".number_format($r[value],2,',','.')."</strong></td></tr>
						  <tr><td>Request By</td><td><strong>$r[user]</strong></td></tr>
						  <tr><td>Approved</td><td><label>";
						  if(trim($r[status])=='Approved'){
						        echo "<input type='checkbox' name='approve' value='1' checked>";
						  }else{
						        echo "<input type='checkbox' name='approve' value='1'>";
						  }
				echo "</label></td></tr>
					  </table>";
				$akses = update_security();
			    if($akses=="allow"){
				      if(strtoupper(trim($r[status]))=='PENDING'){
							echo "<input type='submit' class='btn btn-primary' value='Simpan'>";
					  }else{
							echo "<input type='submit' class='btn btn-primary' value='Simpan' disabled>";
					  }
				}else{
					  echo "<input type='submit' class='btn btn-primary' value='Simpan' disabled>";		
				}
				echo "&nbsp<input type='button' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
					  </fieldset></form></div>";
		}else{
			echo "<div class='alert alert-danger'>Kode budget $r[kode_budget] sudah di $r[status] oleh $r[approval1] tanggal $r[tgl_approval1]...!</div>";
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
				if(strtoupper(trim($rcari[status]))=='PENDING'){
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
						    
							$body = base64_encode($body);

							mysql_query("INSERT INTO email (to_cc, subject, body, header, kode) 
										 VALUES ('$from', '$subject', '$body', '$headers', '$rcari[kode_budget]')");
					}
				}else{
					echo "<div class='alert alert-danger'>Kode budget tersebut sudah di $rcari[status] oleh $rcari[approval1] tanggal $rcari[tgl_approval1]..!</div>";
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
				if(strtoupper(trim($rcari[status]))=='PENDING'){
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

							$body = base64_encode($body);

							mysql_query("INSERT INTO email (to_cc, subject, body, header, kode) 
										 VALUES ('$from', '$subject', '$body', '$headers', '$rcari[kode_budget]')");	
					}
				}else{
					echo "<div class='alert alert-danger'>Kode budget tersebut sudah di $rcari[status] oleh $rcari[approval1] tanggal $rcari[tgl_approval1]..!</div>";
				}
		}else{
			msg_security();
		}
	break;
}
?>
