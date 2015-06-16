<script>
$(document).ready(function(){
	$('#divisi').change(function(){
	    var grade_id = '<?php echo $_SESSION[grade_id];?>';
		var divisi_id = $(this).val(); 
		$.post('../function/get_data.php?data=departemen',{id:divisi_id,grade_id : grade_id},function(data){
			$('#departemen').html(data);
		});
	});
	
	$('#example').dataTable();
		
	$('#example tbody').on('click', 'tr',function(){
		$(this).toggleClass('selected');		
	});
});
</script>
<?php
$aksi="modul/mod_subdepartemen/aksi_subdepartemen.php";
ses_module();
switch($_GET[act]){
  // Tampil master_chart of account
  default:
  $access = read_security();
  if($access=="allow"){
		echo "<h2>Master Subdepartemen</h2>
	          <button type=button class='btn btn-primary'
			  onclick=\"window.location.href='index.php?r=subdepartemen&act=tambahsubdepartemen';\">Tambah</button><br><br>
	          <table id='example' class='table table-condensed table-hover table-bordered' >
				<thead>
				<tr>
					<td>Kode Departemen</th><th>Nama Departemen</th>
					<th>Kode Sub Departemen</th><th>Nama Sub Departemen</th><th>aksi</th>
				</tr>
				</thead><tbody>";

		$tampil=mysql_query("SELECT * FROM master_subdepartemen a,master_department b where a.department_id=b.department_id 
			                    ORDER BY b.department_name,a.subdepartemen_name");
						
		while ($r=mysql_fetch_array($tampil)){
		      echo "<tr>
						<td>$r[department_id]</td>
						<td>$r[department_name]</td>
						<td>$r[subdepartemen_id]</td>
						<td>$r[subdepartemen_name]</td>
			            <td><a href='index.php?r=subdepartemen&act=editsubdepartemen&id=$r[subdepartemen_id]'><i class='icon-pencil'></i></a> 
				              <a href='$aksi?r=subdepartemen&act=hapus&id=$r[subdepartemen_id]'><i class='icon-trash'></i></a>
			            </td>
					</tr>";
	    }
	    echo "</tbody></table>";
	}else{
		msg_security();
	}
    break;

  case "tambahsubdepartemen":
	$access = create_security();
	if($access =="allow"){
	    echo "<form method=POST action='$aksi?r=subdepartemen&act=input'>
	          <fieldset><legend>Tambah Sub Departemen</legend>
			  <label>Divisi :</label>
			  <select name='divisi' id='divisi'><option>--Pilih Divisi--</option>";
				$divisi = mysql_query("select * from master_divisi order by divisi_name");
				while($rdivisi = mysql_fetch_array($divisi)){
					echo "<option value='$rdivisi[divisi_id]'>$rdivisi[divisi_name]</option>";
				}
		echo "</select><br>
			  <label>Kode Departemen :</label>
			  <select name='departemen_id' id='departemen'><option>--Pilih Departemen--</option></select><br>
			  <label>Kode Sub Departemen :</label>
			  <input type='text' name='subdepartemen_id' required><br>
			  <label>Nama Sub Departemen :</label>
			  <input type='text' name='subdepartemen_name' required><br><br>
			  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
			  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
			  </fieldset></form>";
	}else{
		msg_security();
	}
     break;
 
  case "editsubdepartemen":
	$access = update_security();
	if($access=="allow"){
    $edit = mysql_query("SELECT * FROM master_subdepartemen a,master_department b 
	                     WHERE a.department_id=b.department_id and  a.subdepartemen_id='$_GET[id]'");
    $r    = mysql_fetch_array($edit);

    echo "<form method=POST action=$aksi?r=subdepartemen&act=update>
          <input type=hidden name=id value='$r[subdepartemen_id]'>
          <fieldset><legend>Edit Sub Departemen</legend>
		  <label>Divisi :</label>
		  <select name='divisi' id='divisi'><option>--Pilih Divisi--</option>";
		  $divisi = mysql_query("select * from master_divisi order by divisi_name");
		  while($rdivisi = mysql_fetch_array($divisi)){
				echo "<option value='$rdivisi[divisi_id]'>$rdivisi[divisi_name]</option>";
		  }
	echo "</select><br>
		  <label>Kode Departemen :</label>
		  <select name='departemen_id' id='departemen'><option value='$r[department_id]'>$r[department_name]</option></select><br>
		  <label>Kode Sub Departemen :</label>
		  <input type='text' name='subdepartemen_id' value='$r[subdepartemen_id]' required><br>
		  <label>Nama Type subdepartemen :</label>
		  <input type='text' name='subdepartemen_name' value='$r[subdepartemen_name]' required><br><br>
		  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
		  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
		  </fieldset></form>";
	}else{
		msg_security();
	}
    break;  
}
?>
