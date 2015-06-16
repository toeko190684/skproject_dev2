<script>
$(document).ready(function(){
	$('#nasional').change(function(){
		var nasional_id = $(this).val();
		$.post('../function/get_data.php?data=regional',{id:nasional_id},function(data){
			$('#regional').html(data);
		});
	});
	
	$('#regional').change(function(){
		var regional_id = $(this).val();
		$.post('../function/get_data.php?data=area',{id:regional_id},function(data){
			$('#area').html(data);
		});
	});
	
	$('#example').dataTable();
		
	$('#example tbody').on('click', 'tr',function(){
		$(this).toggleClass('selected');		
	});
});
</script>
<?php
$aksi="modul/mod_distributor/aksi_distributor.php";
ses_module();
switch($_GET[act]){
  // Tampil distributor
  default:
  $access = read_security();
  if($access=="allow"){
		echo "<h2>Master Distributor</h2>
	          <button type=button class='btn btn-primary'
			  onclick=\"window.location.href='index.php?r=distributor&act=tambahdistributor';\">Tambah</button><br><br>
	          <table id='example' class='table table-striped table-bordered table-hover' >
	          <thead>
				<tr>
					<th>Kode Area</th><th>Nama Area</th><th>Kode distributor</th><th>Nama distributor</th><th>Deskripsi</th><th>aksi</th>
				</tr>
			  </thead></tbody>";
	    
		$tampil=mysql_query("SELECT a.*,b.area_name FROM distributor a,area b where a.area_id=b.area_id ORDER BY a.distributor_id ");
		while ($r=mysql_fetch_array($tampil)){
	      echo "<tr>
		            <td>$r[area_id]</td>
					<td>$r[area_name]</td>
					<td>$r[distributor_id]</td>
					<td>$r[distributor_name]</td>
					<td>$r[deskripsi]</td>
		            <td><a href='index.php?r=distributor&act=editdistributor&id=$r[distributor_id]'><i class='icon-pencil'></i></a> 
			              <a href='$aksi?r=distributor&act=hapus&id=$r[distributor_id]'><i class='icon-trash'></i></a>
		            </td>
				</tr>";
	    }
	    echo "</tbody></table>";
	}else{
		msg_security();
	}
    break;

  case "tambahdistributor":
	$access = create_security();
	if($access =="allow"){
	    echo "<form method=POST action='$aksi?r=distributor&act=input'>
	          <fieldset><legend>Tambah distributor</legend>
			  <label>Nasional :</label>
			  <select name='nasional' id='nasional'><option>--Pilih Nasional--</option>";
			  $sql= mysql_query("select * from nasional_sales order by nasional_name");
			  while($r = mysql_fetch_array($sql)){
				echo "<option value='$r[nasional_id]'>$r[nasional_name]</option>";
			  }
		echo " </select><br>
			  <label>Regional :</label>
			  <select name='regional' id='regional'><option>--Pilih Regional--</option></select><br>
			  <label>Area :</label>
			  <select name='area' id='area'><option>--Pilih Area--</option></select><br>
			  <label>Kode distributor :</label>
			  <input type='text' name='distributor_id' required><br>
			  <label>Nama distributor :</label>
			  <input type='text' name='distributor_name' required><br>
			  <label>Deskripsi :</label>
			  <input type='text' name='deskripsi'><br><br>
			  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
			  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
			  </fieldset></form>";
	}else{
		msg_security();
	}
     break;
 
  case "editdistributor":
	$access = update_security();
	if($access=="allow"){
    $edit = mysql_query("SELECT a.*,b.area_name FROM distributor a,area b  WHERE a.area_id=b.area_id and a.distributor_id='$_GET[id]'");
    $r    = mysql_fetch_array($edit);

    echo "<form method=POST action=$aksi?r=distributor&act=update>
          <input type=hidden name=id value='$r[distributor_id]'>
          <fieldset><legend>Edit distributor</legend>
		  <label>Nasional :</label>
		  <select name='nasional' id='nasional'><option>--Pilih Nasional--</option>";
		  $nas= mysql_query("select * from nasional_sales order by nasional_name");
		  while($rnas = mysql_fetch_array($nas)){
			echo "<option value='$rnas[nasional_id]'>$rnas[nasional_name]</option>";
		  }
	echo " </select><br>
		  <label>Regional :</label>
		  <select name='regional' id='regional'><option>--Pilih Regional--</option></select><br>
		  <label>Area :</label>
		  <select name='area' id='area'><option value='$r[area_id]'>$r[area_name]</option></select><br>
		  <label>Kode distributor :</label>
		  <input type='text' name='distributor_id' value='$r[distributor_id]' required><br>
		  <label>Nama distributor :</label>
		  <input type='text' name='distributor_name' value='$r[distributor_name]' required><br>
		  <label>Deskripsi :</label>
		  <input type='text' name='deskripsi' value='$r[deskripsi]'><br><br>
		  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
		  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
		  </fieldset></form>";
	}else{
		msg_security();
	}
    break;  
}
?>
