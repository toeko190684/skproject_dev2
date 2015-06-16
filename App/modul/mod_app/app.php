<script>
	$(document).ready(function(){
		$('#example').dataTable();
	});
</script>
<?php
$aksi="modul/mod_app/aksi_app.php";
ses_module();
switch($_GET[act]){
  // Tampil master_app
  default:
    $access = read_security();
	if($access=="allow"){
	        echo "<h2>Master Aplikasi</h2>
		          <input type=button class='btn btn-primary' value='Tambah' 
				  onclick=\"window.location.href='index.php?r=sec_app&act=tambahmaster_app';\"><br><bR>
		          <table id='example' class='table table-striped table-bordered table-hover'  cellspacing=0 width=100%>
		          <thead>
					<tr>
						<th>Nama Aplikasi</th><th>Folder Aplikasi</th><th>Image</th><th>Program</th><th>Urut</th><th>aksi</th>
					</tr>
				  </thead>
				  <tbody>";
					$tampil=mysql_query("SELECT a.*,b.pro_name FROM sec_app a,sec_pro b where a.pro_id=b.pro_id ORDER BY a.urut,a.app_name");
					while ($r=mysql_fetch_array($tampil)){
					  //menampilkan gambar
					  echo "<tr>
								<td>$r[app_name]</td>
								<td>$r[app_location]</td>
								<td>$r[image]</td>
								<td>$r[pro_name]</td>
								<td>$r[urut]</td>
								<td><a href='index.php?r=sec_app&act=editmaster_app&id=$r[app_id]'>Edit</a> | 
									  <a href='$aksi?r=sec_app&act=hapus&id=$r[app_id]'>Hapus</a>
								</td>
							</tr>";
					}
			echo "</tbody></table>";
	}else{
		msg_security();
	}
    break;

  case "tambahmaster_app":
	$access = create_security();
	if($access=="allow"){
			echo "<form method=POST action='$aksi?r=sec_app&act=input' enctype='multipart/form-data'>
		          <fieldset><legend>Tambah Aplikasi</legend>
				  <label>Nama Aplikasi :</label>
				  <input type='text' name='app_name' required><br>
				  <label>Folder Aplikasi :</label>
				  <input type='text' name='app_location' required><br>
				  <label>Program :</label>
				  <select name='program' required><option value=''></option>";
					$program = mysql_query("select * from sec_pro order by pro_name");
					while($rprogram = mysql_fetch_array($program)){
						echo "<option value='$rprogram[pro_id]'>$rprogram[pro_name]</option>";
					}
			echo "</select><br>
			      <label>Urut :</label>
				  <input type='text' name='urut' class='easyui-numberbox input-mini' required><br>
				  <label>Image :</label>
				  <input type='file' name='gambar'><br>
				  <i>size 260 x 180 pixel</i><br><br>
				  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
				  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
				  </fieldset></form>";
	}else{
		msg_security();
	}
     break;
 
  case "editmaster_app":
    $access = update_security();	
    if($access=="allow"){
			$edit = mysql_query("SELECT a.*,b.pro_name FROM sec_app a,sec_pro b  WHERE a.pro_id=b.pro_id and a.app_id='$_GET[id]'");
		    $r    = mysql_fetch_array($edit);

		    echo "<form method=POST action='$aksi?r=sec_app&act=update' enctype='multipart/form-data'>
		          <input type=hidden name=id value='$r[app_id]'>
		          <fieldset><legend>Edit Aplikasi</legend>
				  <label>Nama Aplikasi :</label>
				  <input type='text' name='app_name' value='$r[app_name]' required><br>
				  <label>Folder Aplikasi :</label>
				  <input type='text' name='app_location' value='$r[app_location]' required><br>
				  <label>Program :</label>
				  <select name='program'><option value='$r[pro_id]'>$r[pro_name]</option>";
					$program = mysql_query("select * from sec_pro order by pro_name");
					while($rprogram = mysql_fetch_array($program)){
						echo "<option value='$rprogram[pro_id]'>$rprogram[pro_name]</option>";
					}
			echo "</select><br>
				  <label>Urut :</label>
				  <input type='text' name='urut' value='$r[urut]' class='easyui-numberbox input-mini' required><br>
				  <label>Image :</label>
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
