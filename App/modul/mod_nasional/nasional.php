<script>
	$(document).ready(function() {
		$('#example').dataTable();
		
		$('#example tbody').on('click', 'tr',function(){
			$(this).toggleClass('selected');		
		});
	} );
</script>

<?php
$aksi="modul/mod_nasional/aksi_nasional.php";
ses_module();
switch($_GET[act]){
  // Tampil master_nasional
  default:
  $access = read_security();
  if($access=="allow"){
		echo "<h2>Master Nasional</h2>
	          <button type=button class='btn btn-primary' 
			  onclick=\"window.location.href='index.php?r=nasional&act=tambahnasional';\">Tambah</button><br><bR>
	          <table id='example' class='table table-striped table-bordered table-hover' >
	          <thead>
				<tr>
					<th>Kode nasional</th><th>Nama nasional</th><th>Deskripsi</th><th>aksi</th>
				</tr>
			  </thead></tbody>";
	    
		$tampil=mysql_query("SELECT * FROM nasional_sales ORDER BY nasional_id ");
		while ($r=mysql_fetch_array($tampil)){
	      echo "<tr>
					<td>$r[nasional_id]</td>
					<td>$r[nasional_name]</td>
					<td>$r[deskripsi]</td>
		            <td><a href='index.php?r=nasional&act=editnasional&id=$r[nasional_id]'><i class='icon-pencil'></i></a> 
			              <a href='$aksi?r=nasional&act=hapus&id=$r[nasional_id]'><i class='icon-trash'></i></a>
		            </td>
				</tr>";
	    }
	    echo "</tbody></table>";
	}else{
		msg_security();
	}
    break;

  case "tambahnasional":
	$access = create_security();
	if($access =="allow"){
	    echo "<form method=POST action='$aksi?r=nasional&act=input'>
	          <fieldset><legend>Tambah Nasional Sales</legend>
			  <label>Kode nasional :</label>
			  <input type='text' name='nasional_id' required><br>
			  <label>Nama nasional :</label>
			  <input type='text' name='nasional_name' required><br>
			  <label>Deskripsi :</label>
			  <input type='text' name='deskripsi' size=60><br><br>
			  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
			  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
			  </fieldset></form>";
	}else{
		msg_security();
	}
     break;
 
  case "editnasional":
	$access = update_security();
	if($access=="allow"){
    $edit = mysql_query("SELECT * FROM nasional_sales WHERE nasional_id='$_GET[id]'");
    $r    = mysql_fetch_array($edit);

    echo "<form method=POST action=$aksi?r=nasional&act=update>
          <input type=hidden name=id value='$r[nasional_id]'>
          <fieldset><legend>Edit Nasional Sales </legend>
		  <label>Kode nasional :</label>
		  <input type='text' name='nasional_id' value='$r[nasional_id]' required><br>
		  <label>Nama nasional :</label>
		  <input type='text' name='nasional_name' value='$r[nasional_name]' required><br>
		  <label>Deskripsi :</label>
		  <input type='text' name='deskripsi' value='$r[deskripsi]' ><br><br>
		  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
		  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
		  </fieldset></form>";
	}else{
		msg_security();
	}
    break;  
}
?>
