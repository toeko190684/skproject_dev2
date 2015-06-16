<script>
	$(document).ready(function() {
		$('#example').dataTable();
		
		$('#example tbody').on('click', 'tr',function(){
			$(this).toggleClass('selected');		
		});
	} );
</script>

<?php
$aksi="modul/mod_divisi/aksi_divisi.php";
ses_module();
switch($_GET[act]){
  // Tampil master_divisi
  default:
  $access = read_security();
  if($access=="allow"){
		echo "<h2>Master Divisi</h2><button type='button' class='btn btn-primary'  
			  onclick=\"window.location.href='index.php?r=master_divisi&act=tambahmaster_divisi';\">
			  Tambah</button><br><Br>
			  
	          <table id='example' class='table table-striped table-bordered table-hover'  cellspacing=0 width=100%>
	          <thead>
				<tr class='success'>
					<th>Kode Divisi</th><th>Nama Divisi</th><th>aksi</th>
				</tr>
			  </thead><tbody>";

	    $tampil=mysql_query("SELECT * FROM master_divisi");

		while ($r=mysql_fetch_array($tampil)){
            echo "<tr>
						<td>$r[divisi_id]</td>
						<td>$r[divisi_name]</td>
						<td><a href='index.php?r=master_divisi&act=editmaster_divisi&id=$r[divisi_id]' class='btn btn-success btn-small'><i class='icon-pencil'></i> Edit</a>
				             <a href='$aksi?r=master_divisi&act=hapus&id=$r[divisi_id]' class='btn btn-danger btn-small'><i class='icon-trash' ></i> Hapus</a>
						</td>
				  </tr>";
		}
	    echo "</tbody></table>";

	}else{
		msg_security();
	}
    break;

  case "tambahmaster_divisi":
	$access = create_security();
	if($access =="allow"){
	    echo "<form method=POST action='$aksi?r=master_divisi&act=input'>
	          <fieldset><legend>Tambah Divisi</legend>
			  <label>Kode Divisi :</label>
			  <input type='text' name='divisi_id' required><br><br>
			  <label>Nama Divisi :</label>
			  <input type='text' name='divisi_name' required><br><br>
			  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
			  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
			  </fieldset></form>";
	}else{
		msg_security();
	}
     break;
 
  case "editmaster_divisi":
	$access = update_security();
	if($access=="allow"){
    $edit = mysql_query("SELECT * FROM master_divisi WHERE divisi_id='$_GET[id]'");
    $r    = mysql_fetch_array($edit);

    echo "<form method=POST action=$aksi?r=master_divisi&act=update>
          <input type=hidden name=id value='$r[divisi_id]'>
          <fieldset><legend>Edit Divisi</legend>
		  <label>Kode Divisi :</label>
		  <input type='text' name='divisi_id' value='$r[divisi_id]' required><br>
		  <label>Nama Divisi :</label>
		  <input type='text' name='divisi_name' value='$r[divisi_name]' required><br><br>
		  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
		  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
		  </fieldset></form>";
	}else{
		msg_security();
	}
    break;  
}
?>
