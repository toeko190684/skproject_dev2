<script>
$(document).ready(function(){
			$('#divisi').change(function(){
			    var divisi_id = $(this).val();
				$.post( "../function/get_data.php?data=departemen",{ id : divisi_id}, function( data ) {
						$('#departemen').html(data);
				});
			});
			
			$('#example').datatable();
			
});
</script>	
<?php
$aksi="modul/mod_home/aksi_home.php";
ses_module();
switch($_GET['act']){
  // Tampil master_users
  default:
		$tampil=mysql_query("SELECT a.*,b.department_name,c.divisi_name,d.grade_name from sec_users a,
		                    master_department b,master_divisi c, master_grade d where a.department_id=b.department_id and
							a.divisi_id=c.divisi_id and a.grade_id=d.grade_id and a.user_id='".$_SESSION['user_id']."'");
		$r=mysql_fetch_array($tampil);
		
		//cari pending budget
		$budget = mysql_query("SELECT count(a.kode_budget) as jml FROM master_budget a,sec_users b WHERE a.user=b.user_id and 
		                      b.atasan1='$_SESSION[user_id]' and ucase(a.status)='PENDING'");
		$rbudget = mysql_fetch_array($budget);
		
		//cari pending reco
		$reco = mysql_query("SELECT count(a.kode_promo) as jml FROM reco_request a, detail_reco_item b WHERE a.kode_promo = b.kode_reco
							 AND b.departemen_id = '$_SESSION[department_id]' AND ucase(a.status) = 'PENDING'");
		$rreco = mysql_fetch_array($reco);
		
		if($_SESSION[department_id]=='*'){
			$query1 = $db->query("select count(*)as jml_budget from master_budget where divisi_id='$_SESSION[divisi_id]' and upper(status)='PENDING'");
		}else{ 
			$query1 = $db->query("select count(*)as jml_budget from master_budget where divisi_id='$_SESSION[divisi_id]' and department_id='$_SESSION[department_id]' 
								and upper(status)='PENDING'");
		}
		$data = $query1->fetch(PDO::FETCH_OBJ);
		
		echo " <div class=\"row-fluid\">
					  <div class=\"span12\">					
						<ul class='inline'>
							<li><a href='?r=approvalbudget&mod=15' >Pending Budget &nbsp<span class='badge badge-important'>$rbudget[jml]</span></a></li>
							<li><a href='?r=promoapproval&mod=21' >Pending Reco &nbsp<span class='badge badge-important'>$rreco[jml]</span></a></li>
						</ul>
						<div class=\"row-fluid\">
							  <div class=\"span6\">
									<img src='../$r[foto]' alt='$r[foto]' width='100'  class='img-circle' style='border-style:solid;border-width:1px;padding:5px'/>
									<h3>".ucwords($r['full_name'])."</h3>
									
									<table id='example' class='table table-striped '>
										<tbody>
											<tr><td><i class=\"icon-user\"></i> Username</td><td><strong>$r[user_id]</strong></td></tr>
											<tr><td>Nama Lengkap</td><td><strong>$r[full_name]</strong></td></tr>
											<tr><td>Handphone</td><td><strong>$r[hp]</strong></td></tr>
											<tr><td><i class=\"icon-envelope\"></i> Email</td><td><strong>$r[email]</strong></td></tr>
											<tr><td>Divisi</td><td><strong>$r[divisi_name]</strong></td></tr>
											<tr><td>Departemen</td><td><strong>$r[department_name]</strong></td></tr>
											<tr><td>Grade</td><td><strong>$r[grade_name]</strong></td></tr>
											<tr><td><i class=\"icon-user\"></i> Atasan 1</td><td><strong>$r[atasan1]</strong></td></tr>
											<tr><td><i class=\"icon-user\"></i> Atasan 2</td><td><strong>$r[atasan2]</strong></td></tr>
										</tbody>
									</table>							
							  </div>
							  <div class=\"span6\">
									        <div class=\"tabbable\">
												  <ul class=\"nav nav-tabs\">
														<li class=\"active\"><a href=\"#tab1\" data-toggle=\"tab\">Dashboard</a></li>
														<li><a href=\"#tab2\" data-toggle=\"tab\">Activity</a></li>
												  </ul>
												  <div class=\"tab-content\">
														<div class=\"tab-pane active\" id=\"tab1\">
															<h4>#Pending Request</h4>
															<table id='example' class='table table-striped'>
															<tbody>
																<tr><td>Budget</td><td><span class=\"badge badge-success\">$data->jml_budget </span></td></tr>
																<tr><td>Reco</td><td><span class=\"badge badge-success\">2</span></td></tr>
																<tr><td>Claim</td><td><span class=\"badge badge-success\">2</span></td></tr>
															</tbody>
															</table>
														</div>
														<div class=\"tab-pane\" id=\"tab2\">
															<p>Howdy, I'm in Section 2.</p>
														</div>
													  </div>
											</div>					  
							  </div>
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
