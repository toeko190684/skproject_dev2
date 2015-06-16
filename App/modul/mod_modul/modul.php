<script>
	$(document).ready(function(){
		$('#example').dataTable();
	});
</script>
<?php
$aksi="modul/mod_modul/aksi_modul.php";
switch($_GET[act]){
  // Tampil master_modul
  default:
    $access = read_security();
	if($access=="allow"){
	    echo "<h2>Master Modul</h2>
	          <input type=button class='btn btn-primary' value='Tambah' 
			  onclick=\"window.location.href='index.php?r=sec_app_module&act=tambah_modul';\"><br><bR>
	          <table id='example' class='table table-striped table-bordered table-hover'  cellspacing=0 width=100% >
	          <thead>
				<tr>
					<td>Nama Modul</td><td>Link</td><td>Urut</td><td>aksi</td>
				</tr>
			  </thead>
			  <tbody>";
			  $tampil=mysql_query("SELECT a.*,b.app_name FROM sec_app_module a,sec_app b where a.app_id=b.app_id ORDER BY a.module_name ");
				while ($r=mysql_fetch_array($tampil)){
				  echo "<tr>
							<td>$r[module_name]</td>
							<td>$r[link]</td>
							<td>$r[urut]</td>
							<td><a href='index.php?r=sec_app_module&act=edit_modul&id=$r[module_id]'>Edit</a> | 
								  <a href='$aksi?r=sec_app_module&act=hapus&id=$r[module_id]'>Hapus</a>
							</td>
						</tr>";
				}
		echo "</tbody></table>";
	}else{
		msg_security();
	}
    break;

  case "tambah_modul":
    $access = create_security();
	if($access=="allow"){
	    echo "<form method=POST action='$aksi?r=sec_app_module&act=input' enctype='multipart/data'>
	          <fieldset><legend>Tambah Aplikasi</legend>
			  <label>Nama Modul :</label>
			  <input type='text' name='module_name' required><br>
			  <label>Link :</label>
			  <input type='text' name='link' required><br>
			  <label>Urut :</label>
			  <input type='text' name='urut' class='easyui-numberbox input-small'><br>
			  <label>Aplikasi :</label>
			  <select name='aplikasi'><option>--Pilih Aplikasi--</option>";
			  $sql = mysql_query("select * from sec_app order by app_name");
			  while($r = mysql_fetch_array($sql)){
				echo "<option value='$r[app_id]'>$r[app_name]</option>";
			  }
		echo "</select><br><bR>
			  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
			  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
			  </fieldset></form>";
	}else{
		msg_security();
	}
    break;
 
  case "edit_modul":
    $access = update_security();
	if($access=="allow"){
	    $edit = mysql_query("SELECT a.*,b.app_name FROM sec_app_module a,sec_app b WHERE a.app_id=b.app_id and  a.module_id='$_GET[id]'");
	    $r    = mysql_fetch_array($edit);

	    echo "<form method=POST action=$aksi?r=sec_app_module&act=update>
	          <input type=hidden name=id value='$r[module_id]'>
	          <fieldset><legend>Edit Aplikasi</legend>
			  <label>Nama Modul :</label>
			  <input type='text' name='module_name' value='$r[module_name]' required><br>
			  <label>Link :</label>
			  <input type='text' name='link' value='$r[link]' required><br>
			  <label>Urut :</label>
			  <input type='text' name='urut' class='easyui-numberbox input-small' value='$r[urut]'><br>
			  <label>Aplikasi :</label>
			  <select name='aplikasi'><option value='$r[app_id]'>$r[app_name]</option>";
			  $sql = mysql_query("select * from sec_app order by app_name");
			  while($r = mysql_fetch_array($sql)){
				echo "<option value='$r[app_id]'>$r[app_name]</option>";
			  }
		echo "</select><br><bR>
			  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
			  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
			  </fieldset></form>";
	}else{
		msg_security();
	}
    break;  
}
?>
