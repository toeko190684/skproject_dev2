<script>
	$(document).ready(function(){
		$('#simpan').click(function(){
			$.messager.confirm('Confirm','Yakin akan diteruskan ke atasan anda untuk proses approval ?',function(r){
					if(r){	
						var url = '<?php echo $_SESSION[url]; ?>';
						var approve = $("input[name='approve']:checked").val();
						var id = $('#id_enc').val();
						var x = url+'index.php?r=promoapproval&act='+approve+'&mod=21&id='+id;
						window.location.assign(x);
					}else{
						$.messager.alert('SKProject','Permintaan dibatalkan.! ','info');
					}
			});
		});
		
		$('#complete').click(function(){
		    var url = '<?php echo $_SESSION[url]; ?>';
		    var user_id = '<?php echo $_SESSION[user_id]; ?>';
			var approve = $("input[name='approve']:checked").val();
		    var module_id = '<?php echo $_GET[mod]; ?>';
			var kode_promo = $('#id').val();
			$.messager.confirm('Confirm','Yakin akan diteruskan ke atasan anda untuk proses approval ?',function(r){
				if(r){
					$.post('modul/mod_promoapproval/get_data.php?data=complete',{url:url,user_id:user_id,id:module_id, kode_promo : kode_promo,approve : approve},function(data){
						$.messager.alert('SKProject',data,'info');
						location = self.location;
					});
				}else{
					$.messager.alert('SKProject','Permintaan dibatalkan.! ','info');
				}				
			});			
		});
		
		$('#batal').click(function(){
			window.location='index.php?r=promoapproval'
		});

		$('#cari').click(function(){
			var grade_id = '<?php echo $_SESSION[grade_id]; ?>';
			var divisi_id = '<?php echo $_SESSION[divisi_id]; ?>';
			var department_id = '<?php echo $_SESSION[department_id]; ?>';
			var tahun = $('#thn').val();
			var bulan = $('#bln').val();
			var status = $('#status').val();
			var kode_reco = $('#kode_reco').val();
			if (tahun==''){
				$.messager.alert('SKProject','Tahun tidak boleh kosong!!','info');
			}else if (bulan==''){
				$.messager.alert('SKProject','Bulan tidak boleh kosong!!','info');
			}else{
				$.post('modul/mod_promoapproval/get_data.php?data=tabel',{grade_id : grade_id,
																		divisi_id : divisi_id, 
																		department_id : department_id,
																		bulan : bulan,
																		tahun : tahun,
																		status : status,
																		kode_reco : kode_reco},function(data){
					$('#tampil').html(data);
				});
			}
		});
		
		$('#tampil').html(function(){
			var grade_id = '<?php echo $_SESSION[grade_id]; ?>';
			var divisi_id = '<?php echo $_SESSION[divisi_id]; ?>';
			var department_id = '<?php echo $_SESSION[department_id]; ?>';
			var tahun = $('#thn').val();
			var bulan = $('#bln').val();
			var status = $('#status').val();
			var kode_reco = $('#kode_reco').val();
			$.post('modul/mod_promoapproval/get_data.php?data=all',{grade_id : grade_id,
																	divisi_id : divisi_id, 
																	department_id : department_id,
																	tahun : tahun,
																	bulan : bulan,
																	status : status,
																	kode_reco : kode_reco},function(data){
				$('#tampil').html(data);
			});
		});
	});		
</script>
<?php
$aksi="modul/mod_promoapproval/aksi_promoapproval.php";
ses_module();
switch($_GET[act]){
  // Tampil master_chart of account
  default:
  $access = read_security();
  if($access=="allow"){
		echo "<ul class='nav nav-tabs' id='myTab'>
				  <li class='active'><a href='#reco_pending' data-toggle='tab'>Reco Pending</a></li>
				  <li><a href='#reco_approved' data-toggle='tab'>Reco Approved</a></li>
		      </ul>";
		echo "<div class='tab-content'>
				  <div class='tab-pane active' id='reco_pending'>
						<table class='table table-condensed table-hover table-bordered'>
						    <tR>
							    <td>No</td><tD>No Reco</td><td>Tgl Reco</td><td>Title</td><td>Total Sales Target</td>
								<td>Cost Of Promo</td><td>Jenis</td>
								<td>Complete</td><td>Approval 1</td><td>Approval 2</td><td>Status</td><td>Aksi</td>
							</tr>";
						
						//cari user yang ada di approval limit
						$user = $db->query("select * from approval_limit");
						$data = $user->fetch(PDO::FETCH_OBJ);
						if($data->user_id == $_SESSION[user_id]){
							$sql = mysql_query("select a.*,b.atasan1 from reco_request a,sec_users b where a.created_by=b.user_id 
												and a.status='pending' and a.cost_of_promo>= $data->nominal order by a.tgl_promo desc");
						}else{						
							if($_SESSION[grade_id]=="*"){
									$sql = mysql_query("select a.*,b.atasan1 from reco_request a,sec_users b where a.created_by=b.user_id 
														and a.status='pending'   order by a.tgl_promo desc");
							}elseif($_SESSION[grade_id]=="**"){
									$sql = mysql_query("select a.*,b.atasan1 from reco_request a,sec_users b where a.created_by=b.user_id 
													and a.status='pending' and b.divisi_id='$_SESSION[divisi_id]' 
													and a.cost_of_promo< $data->nominal order by a.tgl_promo desc");
							}else{
									$sql = mysql_query("select a.*,b.atasan1 from reco_request a,sec_users b where a.created_by=b.user_id 
														and a.status='pending' and b.divisi_id='$_SESSION[divisi_id]' and 
														department_id='$_SESSION[department_id]'
														and a.cost_of_promo< $data->nominal order by a.tgl_promo desc");
							}
						}
						
						$no = 1;
						while($r = mysql_fetch_array($sql)){
							$cari = mysql_query("select user_id,atasan1,atasan2 from sec_users where user_id='$r[atasan1]'");
							$rcari = mysql_fetch_array($cari);
							
							
							if(strtoupper($r[status])=='PENDING'){ $warna ="label label-warning"; 
							}elseif(strtoupper($r[status])=='APPROVED'){ $warna ="label label-success"; 
							}elseif(strtoupper($r[status])=='REJECTED'){ $warna ="label label-important"; }
							
							if($r[complete]<>''){ 
								if(strtoupper($r[status])=='REJECTED'){
									$complete = "label label-important";
								}else{
									$complete = "label label-success";
								}	
								$ucomplete = $r[complete];
							}else{
								$complete = "label label-warning";
								$ucomplete = $rcari[user_id];
							}
							
							if($r[approval1]<>''){ 
								if(strtoupper($r[status])=='REJECTED'){
									$approval1 = "label label-important";
								}else{
									$approval1 = "label label-success";
								}
								$uapproval1 = $r[approval1];								
							}else{
								$approval1 = "label label-warning";
								$uapproval1 = $rcari[atasan1];
							}
							
							if($r[approval2]<>''){ 
								if(strtoupper($r[status])=='REJECTED'){
									$approval2 = "label label-important";
								}else{
									$approval2 = "label label-success";
								}	
								$uapproval2 = $r[approval2];								
							}else{
								$approval2 = "label label-warning";
								$uapproval2 = $rcari[atasan2];
							}
				
								echo "<tr>
										<td>$no</td>
										<tD>$r[kode_promo]</td>
										<tD>$r[tgl_promo]</td>
										<td>$r[title]</td>
										<tD>".number_format($r[total_sales_target],2,',','.')."</td>
										<td>".number_format($r[cost_of_promo],2,',','.')."</td>
										<td>$r[jenis_biaya]</td>
										<td><span class=\"$complete\">$ucomplete<br>$r[tgl_complete]</span></td>
										<td><span class=\"$approval1\">$uapproval1<br>$r[tgl_approval1]</span></td>
										<td><span class=\"$approval2\">$uapproval2<br>$r[tgl_approval2]</span></td>
										<td><span class=\"$warna\"><span class=\"$warna\">$r[status]</span></td>
										<tD><a href='?r=promoapproval&act=editpromoapproval&id=$r[kode_promo]' class='btn btn-info btn-small'>View</a></td>
									</tr>";
									$no++;

						}
						echo "</table><br><br><strong>Note :</strong><br>
						<span class=\"label label-warning\">Pending</span> = Warna orange menunjukan user tersebut belum melakukan approval<br>
						<span class=\"label label-success\">Approved</span> = Warna hijau pada nama user  berarti telah melakukan approval pada hari dan jam tanggal tersebut<br>
						<span class=\"label label-important\">Rejected</span> = Warna merah berarti user pada kolom approval 2 telah mereject reco.";
		echo "    </div>
				  <div class='tab-pane' id='reco_approved'>
				    <form class='form-inline' >	
					<div class='input-append'>
					    <div class='span4'></div>
						<select name='thn' id='thn' class='input-small'>";
						for($i=0;$i<5;$i++){
							$thn = date('Y');
							$thn = $thn-$i;
							echo "<option value='$thn'>$thn</option>";
						}
		echo "			</select>
						<select name='bln' id='bln' class='input-medium'>";
							$bln = mysql_query("select * from month");
							while($rbln = mysql_fetch_array($bln)){
								echo "<option value='$rbln[month_id]'>$rbln[month_name]</option>";
							}
		echo "			</select>
						<select name='status' id='status' class='input-medium'>
							<option value='pending'>Pending</option>
							<option value='approved'>Approved</option>
							<option value='rejected'>Rejected</option>
		     			</select>
						<input type='text' id='kode_reco' class='input-large' placeholder='Masukan Kode Reco..'>
					    <button id='cari' class='btn' type='button'>Cari</button>
					</div><br>
				    </form>					  
				  <div id='tampil'></div>";
	}else{
		msg_security();
	}
    break;

 case "editpromoapproval":
	$access = read_security();
	if($access=="allow"){
	    echo "<div id='filter'></div>";				
		$edit = mysql_query("select a.*,b.grouppromo_name,c.promotype_name,d.class_name,e.atasan1,e.atasan2,g.distributor_name 
		                    from reco_request a, 
							master_grouppromo b,master_promotype c,master_class d,sec_users e, area f ,distributor g
							Where a.grouppromo_id=b.grouppromo_id and a.promotype_id=c.promotype_id and a.class_id=d.class_id 
							and a.distributor_id=g.distributor_id and created_by=e.user_id and a.kode_promo='$_GET[id]'");
							
	    $r    = mysql_fetch_array($edit);
        $kode_promo = md5($r[kode_promo]);
		
		//menetukan warna dari status 
		if(strtoupper($r[status])=='PENDING'){ $warna = "label label-warning"; }
		elseif(strtoupper($r[status])=='APPROVED'){ $warna = "label label-success"; }
		else{ $warna = "label label-important"; }
		
		
	    echo "<form method=POST >
		      <input type=hidden id= id_enc value='$kode_promo'>
	          <input type=hidden id=id name=id value='$r[kode_promo]'>
	          <fieldset><legend>Detail Request Reco</legend>
			  <div class='span7'>
			  <table class='table table-hovered table-striped'>
				  <tr><td>Kode Reco</td><td><strong>$r[kode_promo]</strong></td></tr>
				  <tr><td>Tgl Reco</td><td><strong>$r[tgl_promo]</strong></td></tr>
				  <tr><td>Area</td><td><strong>$r[area_id] - $r[area_name]</strong></td></tr>
				  <tr><td>Distributor</td><td><strong>$r[distributor_id] - $r[distributor_name]</strong></td></tr>
				  <tr><td>Promo Group</td><td><strong>$r[grouppromo_id] - $r[grouppromo_name]</strong></td></tr>
				  <tr><td>Promo Type</td><td><strong>$r[promotype_id] - $r[promotype_name]</strong></td></tr>
				  <tr><td>Class</td><td><strong>$r[class_id] - $r[class_name]</strong></td></tr>
				  <tr><td>Title/Theme</td><td><strong>$r[title]</strong></td></tr>
				  <tr><td>Periode</td><td><strong>$r[tgl_awal] s/d $r[tgl_akhir]</strong></td></tr>
				  <tr><td>Total Sales Target</td><td><strong>".number_format($r[total_sales_target],2,'.',',')."</strong></td></tr>
				  <tr><td>Background</td><td><strong>$r[background]</strong></td></tr>
				  <tr><td>Promo Mechanisme</td><td><strong>$r[promo_mechanisme]</strong></td></tr>
				  <tr><td>Claim Mechanisme</td><td><strong>$r[claim_mechanisme]</strong></td></tr>
				  <tr><td>Claim Trade Off</td><td><strong>$r[claimtradeoff]</strong></td></tr>
				  <tr><td>Cost of Promo</td><td><strong>".number_format($r[cost_of_promo],2,'.',',')."</strong></td></tr>
				  <tr><td>Type of Cost</td><td><strong>$r[typeofcost]</strong></td></tr>
				  <tr><td>Cost Rasio</td><td><strong>".number_format($r[cost_rasio],2,'.',',')." %</strong></td></tr>
				  <tR><td>Created By</td><td><strong>$r[created_by]</strong></td></tr>
				  <tR><td>Tgl Create</td><td><strong>$r[last_update]</strong></td></tr>
				  <tR><td>Complete By</td><td><strong>$r[complete]</strong></td></tr>
				  <tR><td>Tgl Complete</td><td><strong>$r[tgl_complete]</strong></td></tr>
				  <tR><td>Approval 1</td><td><strong>$r[approval1]</strong></td></tr>
				  <tR><td>Tgl Approval 1</td><td><strong>$r[tgl_approval1]</strong></td></tr>
				  <tR><td>Approval 2</td><td><strong>$r[approval2]</strong></td></tr>
				  <tR><td>Tgl Approval 2</td><td><strong>$r[tgl_approval2]</strong></td></tr>
				  <tR><td>Status</td><td><strong><span class=\"$warna\">$r[status]</span></strong></td></tr>
				  <tr><td colspan=2><label class='radio inline'><input type='radio' id='approve' name='approve' value='approval' checked>Approve</label>
									<label class='radio inline'><input type='radio' id='approve' name='approve' value='reject'>Reject</label>
				  </td></tr>
			  </table><br>";
		$akses = update_security();
	    if($akses=="allow"){
				//cari apakah usernya diberiakses di approval limit 
				$sql = $db->query("select * from approval_limit");
				$data = $sql->fetch(PDO::FETCH_OBJ);
				if($_SESSION[user_id]==$data->user_id){
					if((strtoupper($r[status])=='APPROVED')||(strtoupper($r[status])=='REJECTED')){
						echo "<input type='button' id='simpan' class='btn btn-primary' value='Simpan' disabled>"; 
					}else{
						if(trim($r[complete])==''){
							echo "<input type='button' id='complete' class='btn btn-success' value='Complete'>";
						}else{
							echo "<input type='button' id='simpan' class='btn btn-primary' value='Simpan'>";
						}
					}
				}else{			
					  //jika completenya masih kosong
					  if(trim($r[complete])==''){
								//cek apakah atasan1 sama dengan session tampilkan tombol complete
								if(trim($r[atasan1])==$_SESSION[user_id]){
									echo "<input type='button' id='complete' class='btn btn-success' value='Complete'>";
								}else{
									echo "<input type='button' id='complete' class='btn btn-success' value='Complete' disabled>";
								}
					  }else{
								//cari atasan yang mengcomplete
								$atasan = mysql_query("select a.approval1,a.approval2,b.atasan1,b.atasan2 from reco_request a,sec_users b 
													  where a.complete=b.user_id and a.kode_promo='$_GET[id]'");
								$ratasan = mysql_fetch_array($atasan);
								//jika approval1 nya masih kosong maka cek apakah login atasan1 sama dengan session
								if(trim($ratasan[approval1])==''){
									if(trim($ratasan[atasan1])==$_SESSION[user_id]){
										echo "<input type='button' id='simpan' class='btn btn-primary' value='Simpan'>";							
									}else{
										echo "<input type='button' id='simpan' class='btn btn-primary' value='Simpan' disabled>";
									}
								}else{//jika approval1 sudah terisi
									//cek jika approval2 sama dengan kosong
									if(trim($ratasan[approval2])==''){
										//cek lagi apakah atasan 2 sama dengan session loginnya
										if(trim($ratasan[atasan2])==$_SESSION[user_id]){
											echo "<input type='button' id='simpan' class='btn btn-primary' value='Simpan'>";							
										}else{
											echo "<input type='button' id='simpan' class='btn btn-primary' value='Simpan' disabled>";
										}
									}						
								}
					  }	
				}
		}else{
			  echo "<input type='button' id='simpan' class='btn btn-primary' value='Simpan' disabled>";		
		}
		echo "&nbsp<input type='button' id='batal' class='btn btn-danger' value='Batal' >
			  </div>
			  <div class='span7 offset1'>
			    <blockquote>Reco Target</blockquote>
				<table class='table table-condensed table-hover table-bordered'>
				<tr '><td>No</td><td>Group Outlet</td><td>Sales Target</td></tr>";
				$no =1;
				$sql = mysql_query("select a.*,b.groupoutlet_name from detail_reco_target a,groupoutlet b where 
                                  a.groupoutlet_id=b.groupoutlet_id and a.kode_reco='$_GET[id]'");
				$cek = mysql_num_rows($sql);
				if($cek==0){
					echo "<tR><td colspan=3>Tidak ada data ditemukan..!</td></tr>";
				}else{
					while($r = mysql_fetch_array($sql)){
						echo "<tr><td>$no</td><tD>$r[groupoutlet_name]</td><td>".number_format($r[sales_target],2,'.',',')."</td></tR>";
						$no++;
					}
				}
		echo "</table></div>
		     <div class='span7 offset1'>
			    <blockquote>Reco Budget</blockquote>
				<table class='table table-condensed table-hover table-bordered'>
				<tr ><td>No</td><td>Divisi</td><td>Departemen</td><td>SubDepartemen</td>
				<td>Kode Budget</td><td>Alokasi %</td><tD>Value</td></tr>";
				$no =1;
				$sql = mysql_query("SELECT distinct a.*,b.divisi_name,c.department_name,d.subdepartemen_name FROM 
				                  detail_reco_item a,master_divisi b ,master_department c,master_subdepartemen d 
								  WHERE  a.divisi_id=b.divisi_id and a.departemen_id=c.department_id and 
								  a.subdepartemen_id=d.subdepartemen_id and b.divisi_id=c.divisi_id and 
								  c.department_id=d.department_id and a.kode_reco='$_GET[id]'");
				$cek = mysql_num_rows($sql);
				if($cek==0){
					echo "<tR><td colspan=4>Tidak ada data ditemukan..!</td></tr>";
				}else{
					while($r = mysql_fetch_array($sql)){
						echo "<tr><td>$no</td><tD>$r[divisi_name]</td><td>$r[department_name]</td>
						      <td>$r[subdepartemen_name]</td><td>$r[kode_budget]</td>
							  <td>".number_format($r[alokasi],2,'.',',')."</td>
							  <td>".number_format($r[value],2,'.',',')."</td></tR>";
						$no++;
					}
				}
		echo "</table></div></fieldset></form>";
	}else{
		msg_security();
	}
    break; 
	
	case "approval":
		$access = update_security();
		if($access=="allow"){
			$tgl = date('d/m/Y H:m:s');
			$url = $_SESSION[url]."approval_reco.php";
			//cari detail_reco_budget
			$sql = mysql_query("select a.*,b.atasan1,b.atasan2,b.email from reco_request a,sec_users b where a.complete=b.user_id and  md5(kode_promo)='$_GET[id]'");
			$r = mysql_fetch_array($sql);
			$cek_status_periode = mysql_query("select c.status,a.bulan,a.tahun,c.status from master_budget a,detail_reco_item b, periode c where 
												a.kode_budget = b.kode_budget and a.bulan=c.bulan and a.tahun=c.tahun and 
												md5(b.kode_reco)='$_GET[id]'");					
			$rcek_status_periode = mysql_fetch_array($cek_status_periode);
			if($rcek_status_periode[status]=="Close"){
				echo "<div class='alert alert-danger'>Kode Reco : $r[kode_promo], masuk ke periode bulan $rcek_status_periode[bulan] $rcek_status_periode[tahun], Status Periode : $rcek_status_periode[status]</div>";
			}else{			
				//cek approval limit untuk kkode reco
				$limit = $db->query("select * from approval_limit");
				$r_limit = $limit->fetch(PDO::FETCH_OBJ);
				if($r_limit->user_id = $_SESSION[user_id]){
					//cek jika completenya kosong
					if($r[approval1]==''){
						mysql_query("update reco_request set approval1='$r_limit->user_id',tgl_approval1='$tgl',approval2='$r_limit->user_id',
						            tgl_approval2='$tgl',status='approved' where kode_promo='$r[kode_promo]'");
						echo "<div class='alert alert-info'>Kode Reco : <b>$r[kode_promo]</b> berhasil di approved..!<br><br>
						      <a href='?r=promoapproval' class='btn btn-primary btn-small'>Ok</a></div>";
					}else{
						mysql_query("update reco_request set approval2='$r_limit->user_id',tgl_approval2='$tgl',status='approved' 
									where kode_promo='$r[kode_promo]'");	
						echo "<div class='alert alert-info'>Kode Reco : <b>$r[kode_promo]</b> berhasil di approved..!<br><br>
						      <a href='?r=promoapproval' class='btn btn-primary btn-small'>Ok</a></div>";
					}
				}else{
					//cek jika approval1 masih kosong maka dicek
					if(trim($r[approval1])==''){			
					//cek lagi apakah user yang login sesuai dengan session maka update data untuk approval1 dan tgl approval1
					if(trim($r[atasan1])==$_SESSION[user_id]){
						$update1 = mysql_query("update reco_request set approval1='$_SESSION[user_id]' , tgl_approval1='$tgl' where kode_promo='$r[kode_promo]'");					
						//jika update data berhasil cek apakah atasan dari user yang mengcomplete sama dengan atasan1
						if($update1){
							if(strtoupper(trim($r[jenis_biaya]))=='P'){
									if(trim($r[atasan2])==$r[atasan1]){
										$update = mysql_query("update reco_request set approval2='$r[atasan2]' , tgl_approval2='$tgl' where kode_promo='$r[kode_promo]'");
										//jika berhasil di update tampilakn pesan
										if($update){	
											mysql_query("update reco_request set status='approved' where kode_promo='$r[kode_promo]'");
											echo "<div class='alert alert-info'>Kode Reco : <b>$r[kode_promo]</b> berhasil di approved oleh <b>$r[atasan1]</b> tgl : <b>$tgl</b>..!</div>";
										}else{
											echo "<div class='alert alert-danger'>Kode Reco : <b>$r[kode_promo]</b> gagal di approved oleh <b>$r[atasan1]</b> tgl : <b>$tgl</b>..!</div>";
										}
									}else{
										//jika atasan2 berbeda dengan atasan1 maka dikirimkan email notifikasi, cari email atasan 2
										$atasan = mysql_query("select * from sec_users where user_id='$r[atasan2]'");				
										$ratasan = mysql_fetch_array($atasan);	
										//kirim email untuk yang memiliki kode budget
										$uid = md5($ratasan[user_id]);
										$key = $ratasan[password];
										$id = md5($r[kode_promo]);
										
										$reco = mysql_query("select a.*,b.grouppromo_name,c.promotype_name,d.class_name,e.atasan1,e.atasan2,e.email from reco_request a, 
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
											
										$body = base64_encode($body);

										$mail_sent = mysql_query("INSERT INTO email (to_cc, subject, body, header, kode) 
																  VALUES ('$from', '$subject', '$body', '$headers', '$r[kode_promo]')");	
														  
										if($mail_sent){
											$body = 'KODE RECO  : '.$r[kode_promo].' berhasil dikirim ke '.$ratasan[email].'..!';							
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
																	  
											echo "<div class='alert alert-info'>Kode Reco : <b>$r[kode_promo]</b> berhasil di approved oleh <b>$r[atasan1]</b> tgl : <b>$tgl</b>..!</div>";
										}else{
											echo "<div class='alert alert-danger'>Kode Reco : <b>$r[kode_promo]</b> Gagal di approved oleh <b>$r[atasan1]</b> tgl : <b>$tgl</b>..!</div>";
										}
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
									$body = "<h3><u>Request Reco : $xreco[kode_promo] sudah diapprove oleh $r[atasan1] tgl $tgl</u></h3>
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
									$subject = "RECO REQUEST : $xreco[kode_promo]";
														
									$body = base64_encode($body);

									mysql_query("INSERT INTO email (to_cc, subject, body, header, kode) 
												  VALUES ('$from', '$subject', '$body', '$headers', '$r[kode_promo]')");
									echo "<div class='alert alert-info'>Kode Reco : <b>$r[kode_promo]</b> berhasil di approved oleh <b>$r[atasan1]</b> tgl : <b>$tgl</b>..!</div>";
								}else{
									echo "<div class='alert alert-danger'>Kode Reco : <b>$r[kode_promo]</b> gagal di approved oleh <b>$r[atasan1]</b> tgl : <b>$tgl</b>..!</div>";
								}
							}
						}else{//jika update data gagal
							echo "<div class='alert alert-danger'>Kode Reco : <b>$r[kode_promo]</b> gagal di approved oleh <b>$r[atasan1]</b> tgl : <b>$tgl</b>..!</div>";
						}
					}else{
						echo "<div class='alert alert-danger'>Anda tidak berhak untuk approved kode reco : $r[kode_promo]</div>";
					}
				}else{//jika approval1 tidak kosong atau sudah terisi maka cek approval 2 apakah sama dengan session usernya 
					if(trim($r[approval2])==''){
						if(trim($r[atasan2])==$_SESSION[user_id]){//jika atasan2 sama dengan sessiion user yang login maka diupdate approval2 dan tgl approval 2nya 
							$update = mysql_query("update reco_request set approval2='$r[atasan2]' , tgl_approval2='$tgl' where kode_promo='$r[kode_promo]'");
							if($update){	
								mysql_query("update reco_request set status='approved' where kode_promo='$r[kode_promo]'");
								//kirim email untuk yang memiliki kode budget
								$reco = mysql_query("select a.*,b.grouppromo_name,c.promotype_name,d.class_name,e.atasan1,e.atasan2 from reco_request a, 
													master_grouppromo b,master_promotype c,master_class d,sec_users e 
													where a.grouppromo_id=b.grouppromo_id and a.promotype_id=c.promotype_id and a.class_id=d.class_id 
													and a.complete=e.user_id and  a.kode_promo='$r[kode_promo]'");
								$xreco = mysql_fetch_array($reco);
								$body = "<h3><u>Request Reco : $xreco[kode_promo] sudah diapprove oleh $r[atasan2] tgl $xreco[tgl_approval2]</u></h3>
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
								$subject = "REQUEST RECO : $xreco[kode_promo]";
													
								$body = base64_encode($body);

								mysql_query("INSERT INTO email (to_cc, subject, body, header, kode) 
											 VALUES ('$from', '$subject', '$body', '$headers', '$xreco[kode_promo]')");
														  
								echo "<div class='alert alert-info'>Kode Reco : <b>$r[kode_promo]</b> berhasil di approved oleh <b>$r[atasan2]</b> tgl : <b>$tgl</b>..!</div>";
							}else{//jika update approval 2 gagal maka tampilkan pesan 
								echo "<div class='alert alert-danger'>Kode Reco : <b>$r[kode_promo]</b> gagal di approved oleh <b>$r[atasan2]</b> tgl : <b>$tgl</b>..!</div>";
							}
						}else{//jika atasan2 tidak sesuai dengan session user yang login
							echo "<div class='alert alert-danger'>Anda tidak berhak untuk approved kode reco : $r[kode_promo]</div>";
						}
					}else{//jika approval2 sudah di approve maka tampilkan kalau sudah di approve
						echo "<div class='alert alert-danger'>Kode Reco : <b>$r[kode_promo]</b> tersebut sudah diapproved oleh <b>$r[atasan2]</b> tgl <b>$tgl</b>..!</div>";
					}
				}
				}
			}
		}else{//jika tidak punya akses create read update dan delete approval reco maka tampilakn pesan anda tidak berhak
			msg_security();
		}
	break;
	
	case "reject":
		$access = update_security();
		if($access=="allow"){
			$tgl = date('d/m/Y H:m:s');
			$url = $_SESSION[url]."approval_reco.php";
			//cari detail_reco_budget
			$sql = mysql_query("select a.*,b.atasan1,b.atasan2,b.email from reco_request a,sec_users b where a.complete=b.user_id and  md5(kode_promo)='$_GET[id]'");
			$r = mysql_fetch_array($sql);
						$cek_status_periode = mysql_query("select c.status,a.bulan,a.tahun,c.status from master_budget a,detail_reco_item b, periode c where 
												a.kode_budget = b.kode_budget and a.bulan=c.bulan and a.tahun=c.tahun and 
												md5(b.kode_reco)='$_GET[id]'");					
			$rcek_status_periode = mysql_fetch_array($cek_status_periode);
			if($rcek_status_periode[status]=="Close"){
					echo "<div class='alert alert-danger'>Kode Reco : $r[kode_promo], masuk ke periode bulan $rcek_status_periode[bulan] $rcek_status_periode[tahun], Status Periode : $rcek_status_periode[status]</div>";
			}else{
				//cek approval limit untuk kkode reco
				$limit = $db->query("select * from approval_limit");
				$r_limit = $limit->fetch(PDO::FETCH_OBJ);
				if($r_limit->user_id = $_SESSION[user_id]){
					//cek jika completenya kosong
					if($r[approval1]==''){
						mysql_query("update reco_request set approval1='$r_limit->user_id',tgl_approval1='$tgl',approval2='$r_limit->user_id',
						            tgl_approval2='$tgl',status='rejected' where kode_promo='$r[kode_promo]'");
						echo "<div class='alert alert-info'>Kode Reco : <b>$r[kode_promo]</b> berhasil di rejected..!<br><br>
						      <a href='?r=promoapproval' class='btn btn-primary btn-small'>Ok</a></div>";
					}else{
						mysql_query("update reco_request set approval2='$r_limit->user_id',tgl_approval2='$tgl',status='rejected' 
									where kode_promo='$r[kode_promo]'");	
						echo "<div class='alert alert-info'>Kode Reco : <b>$r[kode_promo]</b> berhasil di rejected..!<br><br>
						      <a href='?r=promoapproval' class='btn btn-primary btn-small'>Ok</a></div>";
					}
				}else{
					//jika approval1 kosong maka 
					if(trim($r[approval1])==''){
						 //jika atasan 1 sama dengan session user yang login maka diperbolehkan reject kode reco
						if(trim($r[atasan1])==$_SESSION[user_id]){
							$update = mysql_query("update reco_request set approval1='$r[atasan1]', tgl_approval1='$tgl' where kode_promo='$r[kode_promo]'");
							if($update){
								mysql_query("update reco_request set approval2='$r[atasan2]', tgl_approval2='$tgl' where kode_promo='$r[kode_promo]'");
								mysql_query("update reco_request set status='rejected' where kode_promo='$r[kode_promo]'");
								$reco = mysql_query("select a.*,b.grouppromo_name,c.promotype_name,d.class_name,e.atasan1,e.atasan2 from reco_request a, 
													master_grouppromo b,master_promotype c,master_class d,sec_users e 
													where a.grouppromo_id=b.grouppromo_id and a.promotype_id=c.promotype_id and a.class_id=d.class_id 
													and a.complete=e.user_id and  a.kode_promo='$r[kode_promo]'");
								$xreco = mysql_fetch_array($reco);
								$body = "<h3><u>Request Reco : $xreco[kode_promo] sudah diapprove oleh $r[atasan1] tgl $tgl</u></h3>
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
								$subject = "REQUEST RECO : $xreco[kode_promo]";
														
								$body = base64_encode($body);

								mysql_query("INSERT INTO email (to_cc, subject, body, header, kode) 
											 VALUES ('$from', '$subject', '$body', '$headers', '$xreco[kode_promo]')");
								
								echo "<div class='alert alert-info'>Kode Reco : <b>$r[kode_promo]</b> berhasil di rejected oleh <b>$r[atasan1]</b> tgl : <b>$tgl</b>..!</div>";
							}else{
								echo "<div class='alert alert-danger'>Kode Reco : <b>$r[kode_promo]</b> gagal di rejected oleh <b>$r[atasan2]</b> tgl : <b>$tgl</b>..!</div>";				
							}
						}else{//jika atasan 1 tidak sama dengan suer yang login maka tampilkan kalau anda tidak berhak
							echo "<div class='alert alert-danger'>Anda tidak berhak untuk rejected kode reco : $r[kode_promo]</div>";
						}
					}else{//jika  sudah diisi
						if(trim($r[atasan2])==$_SESSION[user_id]){//jika atasan2 sama dengan session user yang login
							$update = mysql_query("update reco_request set approval2='$r[atasan2]', tgl_approval2='$tgl' where kode_promo='$r[kode_promo]'");
							if($update){
								mysql_query("update reco_request set status='rejected' where kode_promo='$r[kode_promo]'");
								$reco = mysql_query("select a.*,b.grouppromo_name,c.promotype_name,d.class_name,e.atasan1,e.atasan2 from reco_request a, 
													master_grouppromo b,master_promotype c,master_class d,sec_users e 
													where a.grouppromo_id=b.grouppromo_id and a.promotype_id=c.promotype_id and a.class_id=d.class_id 
													and a.complete=e.user_id and  a.kode_promo='$r[kode_promo]'");
								$xreco = mysql_fetch_array($reco);
								$body = "<h3><u>Request Reco : $xreco[kode_promo] sudah direjected oleh $r[atasan2] tgl $tgl_approval2</u></h3>
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
								$subject = "REQUEST RECO : $xreco[kode_promo]";
														
								$body = base64_encode($body);

								mysql_query("INSERT INTO email (to_cc, subject, body, header, kode) 
											 VALUES ('$from', '$subject', '$body', '$headers', '$xreco[kode_promo]')");
								echo "<div class='alert alert-info'>Kode Reco : <b>$r[kode_promo]</b> berhasil di rejected oleh <b>$r[atasan1]</b> tgl : <b>$tgl</b>..!</div>";
							}else{
								echo "<div class='alert alert-danger'>Kode Reco : <b>$r[kode_promo]</b> gagal di rejected oleh <b>$r[atasan2]</b> tgl : <b>$tgl</b>..!</div>";				
							}
						}else{//jika atasan2 tidak sesuai dengan user login maka tampilkan akalau tidak berhak
							echo "<div class='alert alert-danger'>Anda tidak berhak untuk rejected kode reco : $r[kode_promo]</div>";
						}
					}
				}
			}
		}else{//jika tidak punya akses create read update dan delete approval reco maka tampilakn pesan anda tidak berhak
			msg_security();
		}
	break;
	
}
?>
