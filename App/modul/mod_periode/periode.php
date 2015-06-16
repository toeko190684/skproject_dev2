<script>
	$(document).ready(function() {
		$('#example').dataTable();
		
		$('#example tbody').on('click', 'tr',function(){
			$(this).toggleClass('selected');		
		});
	} );
</script>

<?php
$aksi="modul/mod_periode/aksi_periode.php";
ses_module();
switch($_GET[act]){
  // Tampil periode
  default:
    $access = read_security();
	if($access=="allow"){
		echo "<h2>Master Periode</h2>
	          <button type=button class='btn btn-primary'  
			  onclick=\"window.location.href='index.php?r=periode&act=tambahperiode';\">Tambah</button><br><bR>
	          <table id='example' class='table table-striped table-bordered table-hover' >
	          <thead>
				<tr>
					<th>periode ID</th><th>Bulan</th><th>Tahun</th><th>Status</th><th>aksi</th>
				</tr>
			  </thead></tbody>";

		$tampil=mysql_query("SELECT * FROM periode ORDER BY bulan,tahun ");
		while ($r=mysql_fetch_array($tampil)){
	      echo "<tr>
					<td>$r[periode_id]</td>
					<td>$r[bulan]</td>
					<td>$r[tahun]</td>
		            <td>$r[status]</td>
					<td><a href='index.php?r=periode&act=editperiode&id=$r[periode_id]'><i class='icon-pencil'></i></a>
			              <a href='$aksi?r=periode&act=hapus&id=$r[periode_id]'><i class='icon-trash'></i></a>
		            </td>
				</tr>";
	    }
	    echo "</tbody></table>";
	}else{
		msg_security();
	}
    break;

  case "tambahperiode":
	$access = create_security();
	if($access =="allow"){
	    echo "<form method=POST action='$aksi?r=periode&act=input'>
	          <fieldset><legend>Tambah Periode</legend>
			  <label>Periode :</label>
			  <select name='bulan' class='input-medium'>
				  <option value=''>--Pilih Bulan--</option>
				  <option value='Januari'>Januari</option>
				  <option value='Februari'>Februari</option>
				  <option value='Maret'>Maret</option>
				  <option value='April'>April</option>
				  <option value='Mei'>Mei</option>
				  <option value='Juni'>Juni</option>
				  <option value='Juli'>Juli</option>
				  <option value='Agustus'>Agustus</option>
				  <option value='September'>September</option>
				  <option value='Oktober'>Oktober</option>
				  <option value='November'>November</option>
				  <option value='Desember'>Desember</option>
			  </select>
			  <select name='tahun' class='input-small'>
				  <option value='".date('Y')."'>".date('Y')."</option>
			  </select>
			  <br>
			  <label>Status :</label>
			  <select name='status' class='input-small'>
					<option value='Open'>Open</option>
					<option value='Close' selected>Close</option>
			  </select><br><br>
			  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
			  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
			  </fieldset></form>";
	}else{
		msg_security();
	}
    break;
 
  case "editperiode":
	$access = update_security();
	if($access =="allow"){
	    $edit = mysql_query("SELECT * FROM periode WHERE periode_id='$_GET[id]'");
	    $r    = mysql_fetch_array($edit);

	    echo "<form method=POST action=$aksi?r=periode&act=update>
	          <input type=hidden name=id value='$r[periode_id]'>
	          <fieldset><legend>Edit periode</legend>
			  <label>Periode :</label>
			  <select name='bulan' class='input-medium'>
				  <option value='$r[bulan]'>$r[bulan]</option>
				  <option value='Januari'>Januari</option>
				  <option value='Februari'>Februari</option>
				  <option value='Maret'>Maret</option>
				  <option value='April'>April</option>
				  <option value='Mei'>Mei</option>
				  <option value='Juni'>Juni</option>
				  <option value='Juli'>Juli</option>
				  <option value='Agustus'>Agustus</option>
				  <option value='September'>September</option>
				  <option value='Oktober'>Oktober</option>
				  <option value='November'>November</option>
				  <option value='Desember'>Desember</option>
			  </select>
			  <select name='tahun' class='input-small'>
				  <option value='$r[tahun]'>$r[tahun]</option>
				  <option value='".date('Y')."'>".date('Y')."</option>
			  </select>
			  <br>
			  <label>Status :</label>
			  <select name='status' class='input-small'>
			        <option value='$r[status]' selected>$r[status]</option>
					<option value='Open'>Open</option>
					<option value='Close'>Close</option>
			  </select><br><br>
			  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
			  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
			  </fieldset></form>";
	}else{
		msg_security();
	}
    break;  
}
?>
