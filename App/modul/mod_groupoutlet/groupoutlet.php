<script>
	$(document).ready(function() {
		$('#example').dataTable();
		
		$('#example tbody').on('click', 'tr',function(){
			$(this).toggleClass('selected');		
		});
	} );
</script>

<?php
$aksi="modul/mod_groupoutlet/aksi_groupoutlet.php";
ses_module();
switch($_GET[act]){
  // Tampil groupoutlet
  default:
  $access = read_security();
  if($access=="allow"){
		echo "<h2>Master Group Outlet</h2>
	          <button type=button class='btn btn-primary'
			  onclick=\"window.location.href='index.php?r=groupoutlet&act=tambahgroupoutlet';\">Tambah</button><br><br>
			  <table id='example' class='table table-striped table-bordered table-hover' >
	          <thead>
				<tr>
					<th>Kode Group Outlet</th><th>Nama Group Outlet</th><th>aksi</th>
				</tr>
			  </thead></tbody>";
	    
		$tampil=mysql_query("SELECT * FROM groupoutlet  ORDER BY groupoutlet_id ");
		while ($r=mysql_fetch_array($tampil)){
	      echo "<tr>
					<td>$r[groupoutlet_id]</td>
					<td>$r[groupoutlet_name]</td>
		            <td><a href='index.php?r=groupoutlet&act=editgroupoutlet&id=$r[groupoutlet_id]'>Edit</a> | 
			              <a href='$aksi?r=groupoutlet&act=hapus&id=$r[groupoutlet_id]'>Hapus</a>
		            </td>
				</tr>";
	    }
	    echo "</tbody></table>";
	}else{
		msg_security();
	}
    break;

  case "tambahgroupoutlet":
	$access = create_security();
	if($access =="allow"){
	    echo "<form method=POST action='$aksi?r=groupoutlet&act=input'>
	          <fieldset><legend>Tambah groupoutlet</legend>
			  <label>Kode Group Outlet :</label>
			  <input type='text' name='groupoutlet_id' required><br>
			  <label>Nama Group Outlet :</label>
			  <input type='text' name='groupoutlet_name'  class='input-medium span5' required><br><br>
			  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
			  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
			  </fieldset></form>";
	}else{
		msg_security();
	}
     break;
 
  case "editgroupoutlet":
	$access = update_security();
	if($access=="allow"){
    $edit = mysql_query("SELECT * from groupoutlet where groupoutlet_id='$_GET[id]'");
    $r    = mysql_fetch_array($edit);

    echo "<form method=POST action=$aksi?r=groupoutlet&act=update>
          <input type=hidden name=id value='$r[groupoutlet_id]'>
          <fieldset><legend>Edit groupoutlet</legend>
		  <label>Kode Group Outlet :</label>
			  <input type='text' name='groupoutlet_id' value='$r[groupoutlet_id]' required><br>
			  <label>Nama Group Outlet :</label>
			  <input type='text' name='groupoutlet_name'  value='$r[groupoutlet_name]' class='input-medium span5' required><br><br>
		  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
		  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
		  </fieldset></form>";
	}else{
		msg_security();
	}
    break;  
}
?>
