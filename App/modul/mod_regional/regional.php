<script>
	$(document).ready(function() {
		$('#example').dataTable();
		
		$('#example tbody').on('click', 'tr',function(){
			$(this).toggleClass('selected');		
		});
	} );
</script>

<?php
$aksi="modul/mod_regional/aksi_regional.php";
ses_module();
switch($_GET[act]){
  // Tampil master_regional
  default:
  $access = read_security();
  if($access=="allow"){
		echo "<h2>Master Regional</h2>
	          <button type=button class='btn btn-primary'
			  onclick=\"window.location.href='index.php?r=regional&act=tambahregional';\">Tambah</button><br><br>
	          <table id='example' class='table table-striped table-bordered table-hover' >
	          <thead>
				<tr>
					<th>Kode Nasional</th><th>Nama Nasional</th><th>Kode regional</th><th>Nama regional</th><th>Deskripsi</th><th>aksi</th>
				</tr>
			  </thead><tbody>";
	    
		$tampil=mysql_query("SELECT a.*,b.nasional_name FROM regional a,nasional_sales b where a.nasional_id=b.nasional_id 
                     		    ORDER BY a.regional_id ");
		while ($r=mysql_fetch_array($tampil)){
	      echo "<tr>
		            <td>$r[nasional_id]</td>
					<td>$r[nasional_name]</td>
					<td>$r[regional_id]</td>
					<td>$r[regional_name]</td>
					<td>$r[deskripsi]</td>
		            <td><a href='index.php?r=regional&act=editregional&id=$r[regional_id]'><i class='icon-pencil'></i></a> | 
			              <a href='$aksi?r=regional&act=hapus&id=$r[regional_id]'><i class='icon-trash'></i></a>
		            </td>
				</tr>";
	    }
	    echo "</tbody></table>";
	}else{
		msg_security();
	}
    break;

  case "tambahregional":
	$access = create_security();
	if($access =="allow"){
	    echo "<form method=POST action='$aksi?r=regional&act=input'>
	          <fieldset><legend>Tambah regional</legend>
			  <label>Nasional :</label>
			  <select name='nasional' id='nasional'><option>--Pilih Nasional--</option>";
			  $sql= mysql_query("select * from nasional_sales order by nasional_name");
			  while($r = mysql_fetch_array($sql)){
				echo "<option value='$r[nasional_id]'>$r[nasional_name]</option>";
			  }
		echo " </select><br>
			  <label>Kode regional :</label>
			  <input type='text' name='regional_id' required><br>
			  <label>Nama regional :</label>
			  <input type='text' name='regional_name' required><br>
			  <label>Deskripsi :</label>
			  <input type='text' name='deskripsi' ><br><br>
			  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
			  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
			  </fieldset></form>";
	}else{
		msg_security();
	}
     break;
 
  case "editregional":
	$access = update_security();
	if($access=="allow"){
    $edit = mysql_query("SELECT a.*,b.nasional_name FROM regional a,nasional_sales b WHERE a.nasional_id=b.nasional_id and  regional_id='$_GET[id]'");
    $r    = mysql_fetch_array($edit);

    echo "<form method=POST action=$aksi?r=regional&act=update>
          <input type=hidden name=id value='$r[regional_id]'>
          <fieldset><legend>Edit Regional</legend>
		  <label>Nasional :</label>
			  <select name='nasional' id='nasional'><option value='$r[nasional_id]'>$r[nasional_name]</option>";
			  $nas= mysql_query("select * from nasional_sales order by nasional_name");
			  while($rnas = mysql_fetch_array($nas)){
				echo "<option value='$rnas[nasional_id]'>$rnas[nasional_name]</option>";
			  }
		echo " </select><br>
		  <label>Kode regional :</label>
		  <input type='text' name='regional_id' value='$r[regional_id]' required><br>
		  <label>Nama regional :</label>
		  <input type='text' name='regional_name' value='$r[regional_name]' required><br>
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
