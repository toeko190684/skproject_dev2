<script>
$(document).ready(function(){
			$('#divisi').change(function(){
			    var divisi_id = $(this).val();
				$.post( "../function/get_data.php?data=departemen",{ id : divisi_id}, function( data ) {
						$('#departemen').html(data);
				});
			});
			
			$('#example').datatable();
			
			$('#myTab a').click(function (e) {
				e.preventDefault();
				$(this).tab('show');
			});
			
});
</script>	
<?php
$aksi="modul/mod_home/aksi_user_home.php";
ses_module();
switch($_GET['act']){
	default:
		$sql = $db->query("select * from sec_users where user_id='$_SESSION[user_id]'");
		$r = $sql->fetch(PDO::FETCH_OBJ);
		if($r->foto==""){
			$foto = "../images/psdicons81.jpg";
		}else{
			$foto = "../$r->foto";
		}
		
		echo "<div class=\"container\">";
				if($_SESSION[pesan]<>""){
					echo "<div class=\"alert alert-info fade in\">
							<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
							<h4>Warning</h4>
							$_SESSION[pesan]
						  </div>";
					$_SESSION[pesan] = "";
				}
				
		echo "		<div class=\"row\">
					<div class = \"span4 offset1\">
						<div class=\"span2\">							
							<img src=\"$foto\" class=\"img-polaroid\">
							<form method=\"post\" action=\"$aksi?r=home&act=gantigambar\" enctype=\"multipart/form-data\">
							<input type=\"hidden\" name=\"id\" value=\"$r->user_id\">
							<input type=\"file\" name=\"gambar\" ><br><br>
							<input type=\"submit\" value=\"Ganti Foto\" class=\"btn btn-block btn-primary\">
							</form>
						</div>
					</div>	
					<div class = \"span8\">	
						<div class =\"alert alert-info\">
							<h4>Welcome ".ucfirst($r->full_name)." !</h4>
							<p>
								<h6>Visi</h6> 
								\"Menjadi pemimpin dikategori makanan melalui semangat \"enak, menyenangkan dan sehat\" !\"<bR>
								<h6>Misi</h6>
								\" Memberi inspirasi dan nilai kepada insan diseluruh dunia melalui produk produk kami\"								
							</p>
						</div>						
					</div>
				</div>
				<div class=\"row\">
					<div class = \"span4 offset1\">
						<div class=\"span3\">
							<form method=\"post\" action=\"$aksi?r=home&act=updateprofil\">
							    <fieldset>
									<label>User ID</label>
									<input type=\"text\" name=\"id\" value=\"$r->user_id\" readonly>
									<label>Nama Lengkap</label>
									<input type=\"text\" name=\"full_name\" value=\"$r->full_name\">
									<label>Alamat Email</label>
									<input type=\"text\" name=\"email\" value=\"$r->email\">	
									<label>Handphone</label>
									<input type=\"text\" name=\"hp\" value=\"$r->hp\">
									<label>Atasan 1</label>
									<input type=\"text\" name=\"atasan1\" value=\"$r->atasan1\" readonly>
									<label>Atasan 2</label>
									<input type=\"text\" name=\"atasan2\" value=\"$r->atasan2\" readonly><br>											
									<button type=\"submit\" class=\"btn btn-success\">Ubah Detail</button>
								</fieldset>
							</form>	
						</div>
					</div>	
					<div class = \"span8\">	
						<ul class=\"nav nav-tabs\" id=\"myTab\">
						  <li class=\"active\"><a href=\"#dashboard\" data-toggle=\"tab\">Dashboard</a></li>
						  <li ><a href=\"#gantipassword\" data-toggle=\"tab\">Ganti Password</a></li>
						  <li><a href=\"#hakakses\" data-toggle=\"tab\">Hak Akses</a></li>
						</ul>
						 
						<div class=\"tab-content\">
						   <div class=\"tab-pane active\" id=\"dashboard\">
								<div class=\"span4\">
									<h6>#Periode</h6>
									<table class=\"table table-striped table-bordered\">
										<thead>
											<tr>
												<td>Bulan</td><td>Tahun</td><td>Status</td>
											</tr>
										</thead>
										<tbody>";
										//tampilkan open peride
										$periode = $db->query("select * from periode where status='open'");
										$rperiode = $periode->fetchAll(PDO::FETCH_OBJ);
										foreach($rperiode as $key => $value){
											/*mencari besarnya budget untuk bulan tertentu */
											if($_SESSION[grade_id]=="**"){
												$budget = $db->query("select sum(value)as budget from master_budget where bulan='$value->bulan'
																	and tahun=$value->tahun and divisi_id='$_SESSION[divisi_id]'
																	and upper(status)<>'REJECTED'");
											}else{
												$budget = $db->query("select sum(value)as budget from master_budget where bulan='$value->bulan'
																	and tahun=$value->tahun and divisi_id='$_SESSION[divisi_id]'
																	and department_id='$_SESSION[department_id]'
																	and upper(status)<>'REJECTED'");
											}
											$rbudget = $budget->fetch(PDO::FETCH_OBJ);
											
											// mencari besarnya reco yang digunakan bulan bersangkutan
											if($_SESSION[grade_id]=="**"){
												$reco = $db->query("SELECT sum(a.cost_of_promo)as reco FROM reco_request a ,detail_reco_item b,
																	master_budget c where a.kode_promo=b.kode_reco and upper(a.status)<>'REJECTED' 
																	and b.kode_budget=c.kode_budget and c.bulan='$value->bulan' and c.tahun=$value->tahun
																	and c.divisi_id='$_SESSION[divisi_id]'");
											}else{
												$reco = $db->query("SELECT sum(a.cost_of_promo)as reco FROM reco_request a ,detail_reco_item b,
																	master_budget c where a.kode_promo=b.kode_reco and upper(a.status)<>'REJECTED' 
																	and b.kode_budget=c.kode_budget and c.bulan='$value->bulan' and c.tahun=$value->tahun
																	and c.divisi_id='$_SESSION[divisi_id]' and c.department_id='$_SESSION[department_id]'");
											}
											$rreco = $reco->fetch(PDO::FETCH_OBJ);
											
											
											
											echo "<tr>
														<td>$value->bulan</td>
														<td>$value->tahun</td>
														<td><span class=\"label label-success\">$value->status</span></td>
												 </tr>";
										}							
		echo "			   				</tbody>
									</table>
								</div>
							</div>
						   <div class=\"tab-pane\" id=\"gantipassword\">
								<div class=\"span4\">
									<form method=\"post\" action=\"$aksi?r=home&act=gantipassword\">
										  <fieldset>
												<label>Password Lama</label>
												<input type=\"text\" name=\"password_lama\" placeholder=\"Password Lama\">
												<label>Masukan Password Baru</label>
												<input type=\"password\" name=\"password_baru\" placeholder=\"Masukan Password Baru\">
												<label>Konfirmasi Password Baru</label>
												<input type=\"password\" name=\"konfirmasi_password\" placeholder=\"Konfirmasi Password Baru\">	<br>											
												<button type=\"submit\" class=\"btn btn-warning\">Ganti Password</button>
										  </fieldset>
									</form>
								</div>
						  </div>
						  <div class=\"tab-pane\" id=\"hakakses\">";
								//cari applikasidulu
								$app = $db->query("select app_id,app_name from sec_app where pro_id=1");
								$rapp = $app->fetchAll(PDO::FETCH_OBJ);
								foreach($rapp as $key =>$value){
									echo "<div class=\"span6\">
											   <h6>#$value->app_name</h6>
											   <table class=\"table table-striped table-bordered\">
												<thead>
													<tr>
														<td>Nama Modul</td>
														<td>Create</td>
														<td>Read</td>
														<td>Update</td>
														<td>Delete</td>
													</tr>
												</thead>
												<tbody>";
													$modul = $db->query("SELECT a.module_name,b.c,b.r,b.u,b.d FROM sec_app_module a 
																		left join sec_user_rules b on a.module_id=b.module_id and app_id=$value->app_id
																		where b.user_id='$_SESSION[user_id]'");
													while($rmodul = $modul->fetch(PDO::FETCH_OBJ)){
														if($rmodul->c == 1){ $c ="icon-ok"; }else{ $c ="icon-fullscreen"; }
														if($rmodul->r == 1){ $r ="icon-ok"; }else{ $r ="icon-fullscreen"; }
														if($rmodul->u == 1){ $u ="icon-ok"; }else{ $u ="icon-fullscreen"; }
														if($rmodul->d == 1){ $d ="icon-ok"; }else{ $d ="icon-fullscreen"; }
														echo "<tr>
																	<td>$rmodul->module_name</td>
																	<td><i class=\"$c\"></i></td>
																	<td><i class=\"$r\"></i></td>
																	<td><i class=\"$u\"></i></td>
																	<td><i class=\"$d\"></i></td>
																	
															  </tr>";
													}
									echo "     <tbody>
											   </table>
										   </div>";
								}
		echo "			  </div>
					</div>
				</div>			  
			</div>";			
    break;
 
    case "edit_home":
		$edit = mysql_query("SELECT a.*,b.department_name,c.divisi_name,d.grade_name from sec_users a,
		                    master_department b,master_divisi c, master_grade d where a.department_id=b.department_id and
							a.divisi_id=c.divisi_id and a.grade_id=d.grade_id and a.user_id='$_GET[id]' order by a.user_id");
	    $r    = mysql_fetch_array($edit);
		if($r[user_id]==$_SESSION[user_id]){
		    echo "<form method='POST' action=$aksi?r=home&act=update enctype='multipart/form-data'>
		          <input type=hidden name=id value='$r[user_id]'>
		          <fieldset><legend>Edit Account</legend>
				  <label>User ID :</label>
				  <input type='text' name='user_id' value='$r[user_id]' required><br>
				  <label>Password :</label>
				  <input type='password' name='password'>&nbsp(* Kosongkan bila tidak dirubah<br>
				  <label>Nama Lengkap :</label>
				  <input type='text' name='nama_lengkap' value='$r[full_name]' required><br>
				  <label>HP :</label>
				  <input type='number' name='hp' value='$r[hp]' required><br>
				  <label>Email :</label>
				  <input type='email' name='email' value='$r[email]' required><br>
				  <label>Divisi :</label>
				  <select name='divisi' id='divisi'><option value='$r[divisi_id]'>$r[divisi_name]</option>";
				  $sql = mysql_query("select * from master_divisi order by divisi_name");
				  while($x = mysql_fetch_array($sql)){
					echo "<option value='$x[divisi_id]'>$x[divisi_name]</option>";
				  }
			echo "</select><br>
				  <label>Departemen :</label>
				  <select name='departemen' id=departemen><option value='$r[department_id]'>$r[department_name]</option>
				  </select><br>		      
			      <label>Grade :</label>
				  <select name='grade'><option value='$r[grade_id]'>$r[grade_name]</option>";
				  $sql = mysql_query("select * from master_grade order by grade_name");
				  while($x = mysql_fetch_array($sql)){
					echo "<option value='$x[grade_id]'>$x[grade_name]</option>";
				  }
			echo "</select><br>
				  <label>Atasan 1 :</label>
				  <select name='atasan1'><option value='$r[atasan1]'>$r[atasan1]</option>";
				  $user = mysql_query("select * from sec_users order by user_id");
				  while($ruser = mysql_fetch_array($user)){
					echo "<option value='$ruser[user_id]'>$ruser[user_id]</option>";
				  }
			echo "</select><br>
			      <label>Atasan 2 :</label>
				  <select name='atasan2'><option value='$r[atasan2]'>$r[atasan2]</option>";
				  $user = mysql_query("select * from sec_users order by user_id");
				  while($ruser = mysql_fetch_array($user)){
					echo "<option value='$ruser[user_id]'>$ruser[user_id]</option>";
				  }
			echo "</select><br>
			      <input type='file' name='gambar'><br>
				  <i>size 260 x 180 pixel</i><br><br>
				  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
				  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
				  </fieldset></form>";
		}else{
			msg_security();
		}
    break;  
}
?>
