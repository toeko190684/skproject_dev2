<script>
	$(document).ready(function(){
		$('#example').dataTable();
	
	
	});
</script>
<?php
$aksi="modul/mod_emailnotification/aksi_emailnotification.php";
ses_module();
switch($_GET[act]){
  // Tampil emailnotification
  default:
  $access = read_security();
  if($access=="allow"){
	   echo "<h2>Email Notification</h2>
	          <input type=button class='btn btn-primary' value='Tambah' 
			  onclick=\"window.location.href='index.php?r=emailnotification&act=tambahemailnotification';\"><br><bR>
	          <table id='example' class='table table-striped table-bordered table-hover'  cellspacing=0 width=100% >
	          <thead>
				<tr>
					<th>Nama Report</th><th>Keterangan</th><th>User ID</th><th>aksi</th>
				</tr>
			  </thead>
			  <tbody>";
			  $sql = mysql_query("SELECT a.*,b.report_name,b.keterangan from email_notification a,master_report b where a.report_id=b.report_id");
			  while($r = mysql_fetch_array($sql)){
				echo "<tr>
							<td>$r[report_name]</td>
							<td>$r[keterangan]</td>
							<td>$r[user_id]</td>
							<td><a href='index.php?r=emailnotification&act=editemailnotification&id=$r[id]'>Edit</a> | 
								<a href='$aksi?r=emailnotification&act=hapus&id=$r[id]'>Hapus</a>							
							</td>
					 </tr>";
			  }
		echo "</tbody></table>";	  
	}else{
		msg_security();
	}
    break;

  case "tambahemailnotification":
	$access = create_security();
	if($access =="allow"){
	    echo "<form method=POST action='$aksi?r=emailnotification&act=input'>
	          <fieldset><legend>Tambah Email Notification</legend>
			  <label>Nama Report :</label>
			  <select name='report_id'><option>--Pilih Report--</option>";
			  $report = mysql_query("select * from master_report order by report_name");
			  while($rreport = mysql_fetch_array($report)){
				echo "<option value='$rreport[report_id]'>$rreport[report_name]</option>";
			  }	
		echo "</select><br>
			  <label>User ID :</label>
			  <select name='user_id' id='user_id'><option>--Pilih UserID--</option>";
			  $sql= mysql_query("select * from sec_users order by user_id");
			  while($r = mysql_fetch_array($sql)){
				echo "<option value='$r[user_id]'>$r[user_id]</option>";
			  }
		echo " </select><br><br>
			  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
			  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
			  </fieldset></form>";
	}else{
		msg_security();
	}
     break;
 
  case "editemailnotification":
	$access = update_security();
	if($access=="allow"){
    $edit = mysql_query("SELECT a.*,b.report_name from email_notification a,master_report b  where a.report_id=b.report_id and  id='$_GET[id]'");
    $r    = mysql_fetch_array($edit);

    echo "<form method=POST action=$aksi?r=emailnotification&act=update>
          <input type=hidden name=id value='$r[id]'>
          <fieldset><legend>Edit Email Notification</legend>
		  <label>Nama Report :</label>
		  <select name='report_id'><option value='$r[report_id]'>$r[report_name]</option>";
		  $report = mysql_query("select * from master_report order by report_name");
		  while($rreport = mysql_fetch_array($report)){
			echo "<option value='$rreport[report_id]'>$rreport[report_name]</option>";
		  }	
	echo "</select><br>
		  <label>User ID :</label>
		  <select name='user_id' id='user_id'><option value='$r[user_id]'>$r[user_id]</option>";
		  $user= mysql_query("select * from sec_users order by user_id");
		  while($ruser = mysql_fetch_array($user)){
				echo "<option value='$ruser[user_id]'>$ruser[user_id]</option>";
		  }
	echo " </select><br><br>
		  <input type='submit' class='btn btn-primary' value='Simpan'>&nbsp
		  <input type='reset' class='btn btn-danger' value='Batal' onclick='history.go(-1)'>
		  </fieldset></form>";
	}else{
		msg_security();
	}
    break;  
}
?>
