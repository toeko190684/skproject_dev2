<script>
	$(document).ready(function() {
		$('#example').dataTable();
		
		$('#example tbody').on('click', 'tr',function(){
			$(this).toggleClass('selected');		
		});
	} );
</script>

<?php
$aksi="modul/mod_grade/aksi_grade.php";
ses_module();
switch($_GET[act]){
  // Tampil master_grade
  default:
    $access = read_security();
	if($access=="allow"){
	    echo "<h2>Master Grade</h2>
	          <button type=button class='btn btn-primary' 
			  onclick=\"window.location.href='index.php?r=master_grade&act=tambahmaster_grade';\">Tambah</button><br><bR>
	          <table id='example' class='table table-striped table-bordered table-hover' >
	          <thead>
				<tr class='success'>
					<td>Grade ID</td><td>Grade Name</td><td>Description</td><td>aksi</td>
				</tr>
			  </thead><tbody>";
	    
		$tampil=mysql_query("SELECT * FROM master_grade ORDER BY grade_name");
		while ($r=mysql_fetch_array($tampil)){
	      echo "<tr>
					<td>$r[grade_id]</td>
					<td>$r[grade_name]</td>
					<td>$r[description]</td>
		            <td><a href='index.php?r=master_grade&act=editmaster_grade&id=$r[grade_id]'><i class='icon-pencil'></i></a> 
			              <a href='$aksi?r=master_grade&act=hapus&id=$r[grade_id]'><i class='icon-trash'></i></a>
		            </td>
				</tr>";
	    }
	    echo "</tbody></table>";
		
	}else{
		msg_security();
	}
    break;

  case "tambahmaster_grade":
	$access = create_security();
	if($access =="allow"){
	    echo "<form method=POST action='$aksi?r=master_grade&act=input'>
	          <fieldset><legend>Tambah Grade</legend>
			  <label>Grade ID :</label>
			  <input type='text' name='grade_id' required><br>
			  <label>Grade Name :</label>
			  <input type='text' name='grade_name' required><br>
			  <label>Description :</label>
			  <textarea name='description' required></textarea><br><br>
			  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
			  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
			  </fieldset></form>";
	}else{
		msg_security();
	}
    break;
 
  case "editmaster_grade":
	$access = update_security();
	if($access =="allow"){
	    $edit = mysql_query("SELECT * FROM master_grade WHERE grade_id='$_GET[id]'");
	    $r    = mysql_fetch_array($edit);

	    echo "<form method=POST action=$aksi?r=master_grade&act=update>
	          <input type=hidden name=id value='$r[grade_id]'>
	          <fieldset><legend>Edit Grade</legend>
			  <input type='text' name='grade_id' value='$r[grade_id]' disabled><br>
			  <label>Grade Name :</label>
			  <input type='text' name='grade_name' value='$r[grade_name]' required><br>
			  <label>Description :</label>
			  <textarea name='description' required>$r[description]</textarea><br><br>
			  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
			  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
			  </fieldset></form>";
	}else{
		msg_security();
	}
    break;  
}
?>
