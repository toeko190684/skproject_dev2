<script>
$(document).ready(function(){
	$('#nasional').change(function(){
		var nasional_id = $(this).val();
		$.post('../function/get_data.php?data=regional',{id:nasional_id},function(data){
			$('#regional').html(data);
		});
	});
	
	$('#example').dataTable();
		
	$('#example tbody').on('click', 'tr',function(){
		$(this).toggleClass('selected');		
	});
});
</script>
<?php
$aksi="modul/mod_area/aksi_area.php";
ses_module();
switch($_GET[act]){
  // Tampil area
  default:
  $access = read_security();
  if($access=="allow"){
		echo "<h2>Master Area</h2>
	          <button type=button class='btn btn-primary' 
			  onclick=\"window.location.href='index.php?r=area&act=tambaharea';\">Tambah</button><br><br>
	          <table id='example' class='table table-striped table-bordered table-hover' cellspacing='0' with='100%'>
	          <thead>
				<tr>
					<th>Kode Regional</th><th>Nama Regional</th><th>Kode area</th><th>Nama area</th><th>aksi</th>
				</tr>
			  </thead><tbody>";

		$tampil = mysql_query("SELECT a.*,b.regional_name FROM area a,regional b where a.regional_id=b.regional_id 
								   ORDER BY a.area_id ");

		while ($r=mysql_fetch_array($tampil)){
	      echo "<tr>
		            <td>$r[regional_id]</td>
					<td>$r[regional_name]</td>
					<td>$r[area_id]</td>
					<td>$r[area_name]</td>
		            <td><a href='index.php?r=area&act=editarea&id=$r[area_id]'><i class='icon-pencil'></i></a>
			              <a href='$aksi?r=area&act=hapus&id=$r[area_id]'><i class='icon-trash'></i></a>
		            </td>
				</tr>";
	    }
	    echo "</tbody></table>";
	}else{
		msg_security();
	}
    break;

  case "tambaharea":
	$access = create_security();
	if($access =="allow"){
	    echo "<form method=POST action='$aksi?r=area&act=input'>
	          <fieldset><legend>Tambah Area</legend>
			  <label>Nasional :</label>
			  <select name='nasional' id='nasional'><option>--Pilih Nasional--</option>";
			  $sql= mysql_query("select * from nasional_sales order by nasional_name");
			  while($r = mysql_fetch_array($sql)){
				echo "<option value='$r[nasional_id]'>$r[nasional_name]</option>";
			  }
		echo " </select><br>
			  <label>Regional :</label>
			  <select name='regional' id='regional'><option>--Pilih Regional--</option></select><br>
			  <label>Kode area :</label>
			  <input type='text' name='area_id' required><br>
			  <label>Nama area :</label>
			  <input type='text' name='area_name' required><br>
			  <label>Deskripsi :</label>
			  <input type='text' name='deskripsi'><br><br>
			  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
			  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
			  </fieldset></form>";
	}else{
		msg_security();
	}
     break;
 
  case "editarea":
	$access = update_security();
	if($access=="allow"){
    $edit = mysql_query("SELECT a.*,b.regional_name,c.nasional_name FROM area a,regional b,nasional_sales c 
	                    WHERE a.regional_id=b.regional_id and b.nasional_id=c.nasional_id and a.area_id='$_GET[id]'");
    $r    = mysql_fetch_array($edit);

    echo "<form method=POST action=$aksi?r=area&act=update>
          <input type=hidden name=id value='$r[area_id]'>
          <fieldset><legend>Edit area</legend>
		  <label>Nasional :</label>
		  <select name='nasional' id='nasional'><option >--Pilih Nasional--</option>";
		  $nas= mysql_query("select * from nasional_sales order by nasional_name");
		  while($rnas = mysql_fetch_array($nas)){
				echo "<option value='$rnas[nasional_id]'>$rnas[nasional_name]</option>";
		  }
	echo " </select><br>
		  <label>Regional :</label>
		  <select name='regional' id='regional'><option value='$r[regional_id]'>$r[regional_name]</option></select><br>
		  <label>Kode area :</label>
		  <input type='text' name='area_id' value='$r[area_id]' required><br>
		  <label>Nama area :</label>
		  <input type='text' name='area_name' value='$r[area_name]' ><br><br>
		  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
		  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
		  </fieldset></form>";
	}else{
		msg_security();
	}
    break;  
}
?>
