<script>
	$(document).ready(function() {
		$('#example').dataTable();
		
		$('#example tbody').on('click', 'tr',function(){
			$(this).toggleClass('selected');		
		});
	} );
</script>

<?php
$aksi="modul/mod_master_department/aksi_master_department.php";
ses_module();
switch($_GET[act]){
  // Tampil master_department
  default:
    $access = read_security();
	if($access =="allow"){
		echo "<h2>Master Departemen</h2>
	          <button type=button class='btn btn-primary'  
			  onclick=\"window.location.href='index.php?r=master_department&act=tambahmaster_department';\">Tambah</button><br><Br>

			  <table id='example' class='table table-striped table-bordered table-hover'>
	          <thead>
				<tr>
					<th>Kode Divisi</th><th>Nama Divisi</th><th>Kode Departemen</th><th>Nama Departemen</th><th>aksi</th>
				</tr>
			  </thead><tbody>";

		$tampil=mysql_query("SELECT a.*,b.divisi_name FROM master_department a,master_divisi b where a.divisi_id=b.divisi_id 
		                    ORDER BY b.divisi_name,a.department_name");

		while ($r=mysql_fetch_array($tampil)){
		      echo "<tr>
						<td>$r[divisi_id]</td>
						<td>$r[divisi_name]</td>
						<td>$r[department_id]</td>
						<td>$r[department_name]</td>
			            <td><a href='index.php?r=master_department&act=editmaster_department&id=$r[department_id]' class='btn btn-success btn-small'><i class='icon-pencil'></i> Edit</a> 
				              <a href='$aksi?r=master_department&act=hapus&id=$r[department_id]' class='btn btn-danger btn-small'><i class='icon-trash'></i> Hapus</a>
			            </td>
					</tr>";
		    }
	    echo "</tbody></table>";

	}else{
	    msg_security();
	}
    break;

  case "tambahmaster_department":
    $access = create_security();
	if($access=="allow"){
	    echo "<form method=POST action='$aksi?r=master_department&act=input'>
	          <fieldset><legend>Tambah Departemen</legend>
			  <label>Kode Divisi :</label>
			  <select name='divisi_id'><option>--Pilih Divisi--</option>";
					$sql = mysql_query("select * from master_divisi order by divisi_name");
					while($x = mysql_fetch_array($sql)){
						echo "<option value='$x[divisi_id]'>$x[divisi_name]</option>";
					}
		echo "</select><br>
			  <label>Kode Departemen :</label>
			  <input type='text' name='department_id' required><br>
			  <label>Nama Departemen :</label>
			  <input type='text' name='department_name' required><br><br>
			  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
			  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
			  </fieldset></form>";
	}else{
		msg_security();
	}
    break;
 
  case "editmaster_department":
    $access = update_security();
	if($access=="allow"){
	    $edit = mysql_query("SELECT * FROM master_department a,master_divisi b  WHERE a.divisi_id=b.divisi_id and a.department_id='$_GET[id]'");
	    $r    = mysql_fetch_array($edit);

	    echo "<form method=POST action=$aksi?r=master_department&act=update>
	          <input type=hidden name=id value='$r[department_id]'>
	          <fieldset><legend>Edit Departemen</legend>
			  <label>Kode Divisi :</label>
			  <select name='divisi_id'><option value='$r[divisi_id]'>$r[divisi_name]</option>";
					$sql = mysql_query("select * from master_divisi order by divisi_name");
					while($x = mysql_fetch_array($sql)){
						echo "<option value='$x[divisi_id]'>$x[divisi_name]</option>";
					}
		echo "</select><br>
			  <label>Kode Departemen :</label>
			  <input type='text' name='department_id' value='$r[department_id]' required><br>
			  <label>Nama Departemen :</label>
			  <input type='text' name='department_name' value='$r[department_name]' required><br><br>
			  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
			  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
			  </fieldset></form>";
	}else{
		msg_security();
	}
    break;  
}
?>
