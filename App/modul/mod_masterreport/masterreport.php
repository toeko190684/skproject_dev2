<script>
	$(document).ready(function(){
		$('#example').dataTable();
	});
</script>
<?php
$aksi="modul/mod_masterreport/aksi_masterreport.php";
ses_module();
switch($_GET[act]){
  // Tampil masterreport
  default:
  $access = read_security();
  if($access=="allow"){
	    echo "<h2>Master Report</h2>
	          <input type=button class='btn btn-primary' value='Tambah' 
			  onclick=\"window.location.href='index.php?r=masterreport&act=tambahmasterreport';\"><br><bR>
	          <table id='example' class='table table-striped table-bordered table-hover'  cellspacing=0 width=100% >
	          <thead>
				<tr >
					<td>Nama Report</td><Td>File</td><td>Jam</td><td>Keterangan</td><td>aksi</td>
				</tr>
			  </thead>
			  <tbody>";
			  $tampil=mysql_query("SELECT * from master_report ");
				while ($r=mysql_fetch_array($tampil)){
				  echo "<tr>
							<td>$r[report_name]</td>
							<td>$r[file]</td>
							<td>$r[jam]</td>
							<td>$r[keterangan]</td>
							<td><a href='index.php?r=masterreport&act=editmasterreport&id=$r[report_id]'>Edit</a> | 
								  <a href='$aksi?r=masterreport&act=hapus&id=$r[report_id]'>Hapus</a>
							</td>
						</tr>";
				}
		echo "</tbody></table>";		
	}else{
		msg_security();
	}
    break;

  case "tambahmasterreport":
	$access = create_security();
	if($access =="allow"){
	    echo "<form method=POST action='$aksi?r=masterreport&act=input'>
	          <fieldset><legend>Tambah Master Report</legend>
			  <label>Nama Report</label>
			  <input type='text' name='report_name' required><br>
			  <label>File</label>
			  <input type='file' name='file'>
			  <label>Jam</label>
			  <input type='text' name='jam' class='easyui-timespinner input-mini' ><br>
			  <label>Keterangan :</label>
			  <input type='text' name='keterangan' class='input-xlarge'><br><br>
			  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
			  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
			  </fieldset></form>";
	}else{
		msg_security();
	}
     break;
 
  case "editmasterreport":
	$access = update_security();
	if($access=="allow"){
    $edit = mysql_query("SELECT * from master_report where report_id='$_GET[id]'");
    $r    = mysql_fetch_array($edit);

    echo "<form method=POST action=$aksi?r=masterreport&act=update>
          <input type=hidden name=id value='$r[report_id]'>
          <fieldset><legend>Edit Master Report</legend>
		  <label>Nama Report</label>
		  <input type='text' name='report_name' value='$r[report_name]' required><br>
		  <label>File</label>
		  <input type='file' name='file'>
		  <label>Jam</label>
		  <input type='text' name='jam' class='easyui-timespinner input-mini' value='$r[jam]' ><br>
		  <label>Keterangan :</label>
		  <input type='text' name='keterangan' class='input-xlarge' value='$r[keterangan]'><br><br>
		  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
		  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
		  </fieldset></form>";
	}else{
		msg_security();
	}
    break;  
}
?>
